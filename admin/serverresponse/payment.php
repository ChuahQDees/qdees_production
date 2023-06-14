<?php

    session_start();
    include_once("../../mysql.php");

	$aColumns = array("name","student_code","age","subject","start_date_at_centre","nric_no","id");

    $centre_code = $_SESSION['CentreCode'];
    $year=$_SESSION['Year'];
    $programme=isset($_GET['programme']) ? $_GET['programme'] : '';
    $level=isset($_GET['level']) ? $_GET['level'] : '';
    $module=isset($_GET['module']) ? $_GET['module'] : '';
    $group=isset($_GET['group']) ? $_GET['group'] : '';
    $status=isset($_GET['status']) ? $_GET['status'] : '';
    $sname=isset($_GET['sname']) ? mysqli_real_escape_string($connection,$_GET['sname']) : '';
    $subject=isset($_GET['subject']) ? $_GET['subject'] : '';
    $age=isset($_GET['age']) ? $_GET['age'] : '';

    $where = '';
	$having = '';
    if($age != '') { $where .= " and (".date('Y',strtotime($year_start_date))." - YEAR(`student`.`dob`)) = '".$age."'"; }

    if($sname != '') { $where .= " and (student.student_code like '%$sname%' or `name` like '%$sname%')"; }

    if($subject != '') { $having .= "HAVING `subject` = '".$subject."'"; }

	$sIndexColumn = "rowNumber";
	$sTable = "
		(
			SELECT
				(SELECT @rownum := @rownum + 1 FROM ( SELECT @rownum := 0 ) AS `rowtable`) AS `rowNumber`,
				`student`.*
				FROM
				(
					SELECT 
                        `student`.`id`,
						`student`.`student_code`,
                        `student`.`nric_no`,

                        (".date('Y',strtotime($year_start_date))." - YEAR(`student`.`dob`)) AS `age`,

                        CONCAT(DATE_FORMAT(`student`.`start_date_at_centre`, '%d-%m-%Y')) AS `start_date_at_centre`,

                        `student`.`name`,
                        
                        (SELECT `c`.`subject` FROM `allocation` a LEFT JOIN `course` c ON a.course_id = c.id WHERE a.year = '".$_SESSION['Year']."' AND a.student_id = `student`.`id` AND a.deleted = 0 order by a.id desc LIMIT 1) AS `subject`

						FROM `student` WHERE
                            `centre_code` = '".$_SESSION['CentreCode']."' AND `student`.`student_status` = 'A' AND `student`.`deleted` = 0 AND `student`.`extend_year` >= '".$_SESSION['Year']."' ".$where."
                        GROUP BY `student`.`name`, `student`.`student_code` ".$having."

                        ORDER BY `student`.`start_date_at_centre` DESC
                        
				) AS `student`
		) AS `student`";
		
	$sLimit = "";
	if(isset($_GET['iDisplayStart']) && $_GET['iDisplayLength'] != '-1')
	{
		$sLimit = "LIMIT " . mysqli_real_escape_string($connection, $_GET['iDisplayStart']) . ", " . mysqli_real_escape_string($connection, $_GET['iDisplayLength']);
	}
	
	if(isset($_GET['iSortCol_0']))
	{
		$sOrder = "ORDER BY  ";
		for($i=0 ; $i<intval($_GET['iSortingCols']) ; $i++)
		{
			if($_GET[ 'bSortable_'.intval($_GET['iSortCol_'.$i]) ] == "true")
			{
				$sOrder .= $aColumns[ intval($_GET['iSortCol_'.$i]) ] . "
				 	" . mysqli_real_escape_string($connection, $_GET['sSortDir_'.$i]) . ", ";
			}
		}
		
		$sOrder = substr_replace($sOrder, "", -2);
		if($sOrder == "ORDER BY")
		{
			$sOrder = "";
		}
	}
	
	$sWhere = "";
	if($_GET['sSearch'] != "")
	{
		$sWhere = "WHERE (";
		for($i = 0 ; $i < count($aColumns) ; $i++)
		{
			$sWhere .= $aColumns[$i] . " LIKE '%" . mysqli_real_escape_string($connection, $_GET['sSearch'])."%' OR ";
		}
		$sWhere = substr_replace($sWhere, "", -3);
		$sWhere .= ')';
	}
	
	for($i = 0 ; $i < count($aColumns) ; $i++)
	{
		if($_GET['bSearchable_'.$i] == "true" && $_GET['sSearch_'.$i] != '')
		{
			if($sWhere == "")
			{
				$sWhere = "WHERE ";
			}
			else
			{
				$sWhere .= " AND ";
			}
			$sWhere .= $aColumns[$i] . " LIKE '%" . mysqli_real_escape_string($connection, $_GET['sSearch_'.$i]) . "%' ";
		}
	}
	
	$sQuery = "
		SELECT SQL_CALC_FOUND_ROWS ".str_replace(" , ", " ", implode(", ", $aColumns))."
		FROM   $sTable
		$sWhere
		$sOrder
		$sLimit
	";
	$rResult = mysqli_query($connection, $sQuery) or die(mysqli_error($connection));
	
	$sQuery = "
		SELECT FOUND_ROWS()
	";
	$rResultFilterTotal = mysqli_query($connection, $sQuery) or die(mysqli_error($connection));
	$aResultFilterTotal = mysqli_fetch_array($rResultFilterTotal);
	$iFilteredTotal = $aResultFilterTotal[0];
	
	$sQuery = "
		SELECT COUNT(".$sIndexColumn.")
		FROM   $sTable
	";
	$rResultTotal = mysqli_query($connection, $sQuery) or die(mysqli_error($connection));
	$aResultTotal = mysqli_fetch_array($rResultTotal);
	$iTotal = $aResultTotal[0];
	
	$output = array(
		"sEcho" => intval($_GET['sEcho']),
		"iTotalRecords" => $iTotal,
		"iTotalDisplayRecords" => $iFilteredTotal,
		"aaData" => array()
	);
	
	$sr = 1;
	while($aRow = mysqli_fetch_array($rResult))
	{
		$row = array();
		for($i = 0 ; $i < count($aColumns) ; $i++)
		{
			if($aColumns[$i] != ' ')
			{	
				if($aColumns[$i] == 'name')
				{
					$aRow[ $aColumns[$i] ] = '<a href="index.php?p=student_info&ssid='.sha1($aRow['id']).'">'.$aRow['name'].'</a>';
				}

				if($aColumns[$i] !='id')
				{
					$row[] = $aRow[ $aColumns[$i] ];
				}
			}
		}
		$output['aaData'][] = $row;
	}
	echo json_encode($output);
?>
