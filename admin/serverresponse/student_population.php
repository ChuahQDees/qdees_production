<?php

    session_start();
    include_once("../../mysql.php");

	include_once("../functions.php");

    include_once("../student_func.php");

	$aColumns = array("transferStudent","photo_file_name","student_code","name","gender","age","nric_no","parent_name","parent_contact","subject","race","country","state","student_status","joined_date","start_date","action","id","delete_request");
	
    function displayStudentStatus($status){
        switch($status){
           case "A":
           return "Active";
         case "D":
           return "Deferred";
         case "G":
           return "Graduated";
         case "I":
           return "Dropout";
         case "S":
           return "Suspended";
         case "T":
           return "Transferred";
           default:
              return $status;
        }
    }

    $str=isset($_GET["s"]) ? mysqli_real_escape_string($connection, $_GET["s"]) : '';
    $year=$_SESSION['Year'];
	$m=isset($_GET["m"]) ? $_GET["m"] : '';

    $where = '';
	$having = '';

    if($_GET['country'] != '') { $where .= " and `student`.`country` = '".$_GET['country']."'"; }
    if($_GET['state'] != '') { $where .= " and `student`.`state` = '".$_GET['state']."'"; }
    if($_GET['race'] != '') { $where .= " and `student`.`race` = '".$_GET['race']."'"; }
    if($_GET['age'] != '') { $where .= " and (".date('Y',strtotime($year_start_date))." - YEAR(`student`.`dob`)) = '".$_GET['age']."'"; }
	if($_GET['student_status'] != '') { $where .= " and `student`.`student_status` = '".$_GET['student_status']."'"; }
	if($str != '') { $where .= " and (student.student_code like '%$str%' or `name` like '%$str%' or `mobile` like '%$str%' or `email` like '%$str%')"; }

	if(isset($_GET['subject']) && $_GET['subject'] != '') { $having .= "HAVING `subject` = '".$_GET['subject']."'"; }

	$sIndexColumn = "rowNumber";
	$sTable = "
		(
			SELECT
				(SELECT @rownum := @rownum + 1 FROM ( SELECT @rownum := 0 ) AS `rowtable`) AS `rowNumber`,
				`student`.*
				FROM
				(
					SELECT DISTINCT
						`student`.`student_code`,
						`student`.`name`,
                        `student`.`gender`,
                        (".date('Y',strtotime($year_start_date))." - YEAR(`student`.`dob`)) AS `age`,
						`student`.`nric_no`,
                        `student`.`country`, 
                        `student`.`state`, 
                        `student`.`race`,
						`student`.`id`,
						`student`.`photo_file_name`,
						`student`.`delete_request`,
						CONCAT(DATE_FORMAT(`student`.`date_created`, '%d-%m-%Y')) AS `joined_date`,
						CONCAT(DATE_FORMAT(`student`.`start_date_at_centre`, '%d-%m-%Y')) AS `start_date`,
						`se`.`full_name` AS `parent_name`,
						
                        CONCAT(CASE 
                            WHEN `student`.`student_status`='A' THEN 'Active'
                            WHEN `student`.`student_status`='D' THEN CONCAT('Deferred ',IF(`student`.`defer_date` != '',CONCAT('\n',DATE_FORMAT(`student`.`defer_date`, '%d-%m-%Y')),''))
                            WHEN `student`.`student_status`='G' THEN 'Graduated'
                            WHEN `student`.`student_status`='I' THEN 'Dropout'
                            WHEN `student`.`student_status`='S' THEN 'Suspended'
                            WHEN `student`.`student_status`='T' THEN 'Transferred'
                            ELSE `student`.`student_status`
                        END ) AS `student_status`,

						CONCAT(IF(`student`.`student_status` != 'I' && `student_status` != 'G' && `student_status` != 'T',CONCAT('<input type=\"checkbox\" class=\"transfer_student\" value=\"',sha1(`student`.`id`),'\">'),'')) AS `transferStudent`,

                        CONCAT(IF(`student`.`photo_file_name`='','',CONCAT('<img src=\"/admin/student_photo/',`student`.`photo_file_name`,'_60x80.jpg\" width=\"60px\" height=\"80px\">'))) AS `photo_file_name1`,
                        
                        CONCAT('+',`se`.`mobile_country_code`,`se`.`mobile`,'\n',`se`.`email`) AS `parent_contact`,

						(SELECT `c`.`subject` FROM `allocation` a LEFT JOIN `course` c ON a.course_id = c.id WHERE a.year = '".$_SESSION['Year']."' AND a.student_id = `student`.`id` AND a.deleted = 0 order by a.id desc LIMIT 1) AS `subject`,
                        
                        CONCAT(IF(`student`.`student_status` != 'I' && `student_status` != 'G' && `student_status` != 'T',CONCAT('<a data-uk-tooltip title=\"Transfer Student to ".($year + 1)."\"><i style=\"font-size: 1.4em; color: #FF6e6e;\" class=\"fas fa-exchange-alt text-success\"></i></a>'),'')) AS `action`,

                        CONCAT(IF(`student`.`delete_request` = 0 || `student`.`delete_request` = 2,CONCAT('<a onclick=\"dlgDeleteStudent()\" id=\"delete',sha1(`student`.`id`),'\"  title=\"Delete Request\"><i style=\"font-size: 1.4em; color: #FF6e6e;\" class=\"fas fa-trash\"></i></a>'),'')) AS `deleteStudent`
                        
						FROM `student`
						  left join (select * from student_emergency_contacts where id in( SELECT min(id) id FROM `student_emergency_contacts` group BY `student_code` order by id asc) ) se on `student`.`student_code`= se.student_code
						WHERE
						   `student`.`deleted` = 0 AND `student`.`centre_code` = '".$_SESSION["CentreCode"]."' ".$where."
                           AND `student`.`start_date_at_centre` <= '".$year_end_date."' AND `student`.`extend_year` >= '".$_SESSION['Year']."' ".$having."
                        ORDER BY `student`.`student_status`, `student`.`student_code` asc 
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
				if($aColumns[$i] == 'photo_file_name')
				{
					
					$student_photo_src = getStudentPhotoSrc($aRow[ $aColumns[$i] ]);
					if( $student_photo_src ){
						$aRow[ $aColumns[$i] ] = '<img src="' . $student_photo_src . '" width="60px" height="80px">';
					} else {
						$aRow[ $aColumns[$i] ] = '';
					}
				}
				else if($aColumns[$i] == 'action')
				{
					$action = '';
					if (hasRightGroupXOR($_SESSION["UserName"], "StudentEdit")) {

						if ($aRow["student_status"] != 'Dropout' && $aRow["student_status"] != 'Graduated' && $aRow["student_status"] != 'Transferred') {

							$action .= '<a href="index.php?p=student_reg&m='.$m.'&id='.sha1($aRow['id']).'&mode=EDIT" data-uk-tooltip title="Edit '.$aRow['name'].'"><i class="fas fa-user-edit" style="font-size: 1.4em;"></i></a><a href="index.php?p=dropout&student_sid='.sha1($aRow['id']).'" data-uk-tooltip title="Dropout '.$aRow['name'].'" id="btnDelete"><i style="font-size: 1.4em; color: #FF6e6e;" class="fas fa-box-open"></i></a>';

							$action .= '<a onclick=\'doTransferStudent("'.sha1($aRow['id']).'")\'  data-uk-tooltip title="Transfer Student to '.getNextYear().'"><i style="font-size: 1.4em; color: #FF6e6e;" class="fas fa-exchange-alt text-success"></i></a>';
						}
					}
					if($aRow['delete_request'] == 0 || $aRow['delete_request'] == 2) 
					{ 
						$action .= '<a onclick="dlgDeleteStudent(\''.sha1($aRow['id']).'\')" id="delete'.sha1($aRow['id']).'"  title="Delete Request">
						  <i style="font-size: 1.4em; color: #FF6e6e;" class="fas fa-trash"></i></a>';
					} 
					$aRow[ $aColumns[$i] ] = $action;
				}
				
				if($aColumns[$i] !='id' && $aColumns[$i] != 'delete_request')
				{
					$row[] = $aRow[ $aColumns[$i] ];
				}
			}
		}
		$output['aaData'][] = $row;
	}
	echo json_encode($output);
?>
