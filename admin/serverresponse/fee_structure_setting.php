<?php

    session_start();
    include_once("../../mysql.php");

	$aColumns = array("fees_structure","subject","student_number","programme_package","from_date","to_date","status","remarks_master","action","id");

    $centre_code = $_SESSION['CentreCode'];
    $n=isset($_GET['n']) ? $_GET['n'] : '';
    $st=isset($_GET['st']) ? $_GET['st'] : '';
    $sb=isset($_GET['sb']) ? $_GET['sb'] : '';
    $year=$_SESSION['Year'];
    $p=isset($_GET["p"]) ? $_GET["p"] : '';
	$sdate=isset($_GET['sdate']) ? $_GET['sdate'] : '';
	$edate=isset($_GET['edate']) ? $_GET['edate'] : '';

    $where = '';

	if($n!=""){
        $where .= " and fees_structure like '%$n%' ";
    }
    if($st!=""){
        $where .= " and programme_package like '%$st%' ";
    }
    if($sb!=""){
        $where .= " and subject like '%$sb%' ";
    }
	if($sdate!=""){
        $where .= " and from_date >= '$sdate' AND to_date <= '$edate' ";
    }

	$sIndexColumn = "rowNumber";
	/*
	$sTable = "
		(
			SELECT
				(SELECT @rownum := @rownum + 1 FROM ( SELECT @rownum := 0 ) AS `rowtable`) AS `rowNumber`,
				`fee_structure`.*
				FROM
				(
					SELECT
                        `fee_structure`.`id`,
						`fee_structure`.`fees_structure`,
                        `fee_structure`.`subject`,
                        `fee_structure`.`programme_package`,
                        `fee_structure`.`from_date`,
                        `fee_structure`.`to_date`,
                        `fee_structure`.`status`,
                        `fee_structure`.`remarks_master`,

                        (SELECT count(s.id) student_nimber from student s, programme_selection ps, student_fee_list fl, fee_structure f  where s.id=ps.student_id and ps.id = fl.programme_selection_id and f.id=fl.fee_id and ".date('m')." BETWEEN month(fl.programme_date) AND month(fl.programme_date_end) and f.id=`fee_structure`.`id` and (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and s.centre_code='$centre_code' and s.student_status = 'A' and s.deleted='0') AS `student_number`,

                        CONCAT('<div></div>') AS `action`

						FROM `fee_structure` WHERE
                            `centre_code` = '$centre_code' and 1=1 ".$where."
				) AS `fee_structure`
		) AS `fee_structure`";
		*/
	
	$sTable = "
	(
		SELECT
			(SELECT @rownum := @rownum + 1 FROM ( SELECT @rownum := 0 ) AS `rowtable`) AS `rowNumber`,
			`fee_structure`.*
			FROM
			(
				SELECT
					`fee_structure`.`id`,
					`fee_structure`.`fees_structure`,
					`fee_structure`.`subject`,
					`fee_structure`.`programme_package`,
					`fee_structure`.`from_date`,
					`fee_structure`.`to_date`,
					`fee_structure`.`status`,
					`fee_structure`.`remarks_master`,

					(SELECT count(s.id) student_nimber from student s, programme_selection ps, student_fee_list fl, fee_structure f 
						where s.id=ps.student_id and ps.id = fl.programme_selection_id 
						and f.id=fl.fee_id 
						and f.id=`fee_structure`.`id` 
						and s.student_status = 'A' 
						and s.deleted='0') AS `student_number`,

					CONCAT('<div></div>') AS `action`

					FROM `fee_structure` WHERE
						`centre_code` = '$centre_code' and 1=1 ".$where."
			) AS `fee_structure`
	) AS `fee_structure`";

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
				if($aColumns[$i] == 'action')
				{
					$action = '';

                    if($aRow['status'] != 'Approved') {
                        $action .= '<a href="index.php?p=fee_structure_setting&id='.sha1($aRow['id']).'&mode=EDIT" data-uk-tooltip title="Edit"><img src="images/edit.png"></a>&nbsp; &nbsp;';
                    } else {
                        $action .= '<a href="index.php?p=fee_structure_setting&id='.sha1($aRow['id']).'&mode=EDIT" style="font-size:14px;" data-uk-tooltip title="View"><i class="fa fa-eye"></i></a>&nbsp; &nbsp;';
                    }

                    $action .= '<a href="index.php?p=fee_structure_setting&id='.sha1($aRow['id']).'&mode=DUPLICATE" data-uk-tooltip title="Duplicate"><img
                    src="images/duplicate.jpg" style="width: 25px;"></a> &nbsp; &nbsp; ';

                    $id = $aRow["id"];
                    $sql3="SELECT COUNT(id) from `collection` c inner join programme_selection p on p.id = c.`allocation_id` and p.student_id = c.`student_id` inner join student_fee_list fl on p.id = fl.programme_selection_id inner join fee_structure f on fl.fee_id=f.id where f.id = '$id' limit 1";

                    $result3=mysqli_query($connection, $sql3);
                    $num_row3=mysqli_num_rows($result3);
                    if ($num_row3==0) {

                            $action .= '<a onclick=\'doDeleteRecord("'.sha1($aRow['id']).'")\' href="#" data-uk-tooltip title="Delete" id="btnDelete"><img src="images/delete.png"></a>';

                    }

                    if($aRow["student_number"] > 0) {

                        $action .= '<a data-uk-tooltip title="Students Detail"><img src="images/Visitor.png" style="width:40px;" onclick=\'dlgStudentList("'.sha1($aRow['id']).'")\' ></a>';

                     }

					$aRow[ $aColumns[$i] ] = $action;
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
