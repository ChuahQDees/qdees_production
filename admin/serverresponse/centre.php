<?php

    session_start();
    include_once("../../mysql.php");
	include_once("../functions.php");
    include_once("../centre_func.php");

	$aColumns = array("name","centre_detail","kindergarten_name","franchisee_company_name","operator_detail","principle_detail","assistant_name","ANP_contact","status","action","id");

    $str=isset($_GET["s"]) ? $_GET["s"] : '';
    $p=isset($_GET["p"]) ? $_GET["p"] : '';

    $where = '';

	if($str != '') { $where .= " and kindergarten_name like '%$str%' or state like '%$str%' or ANP_tel like '%$str%' or operator_name like '%$str%' or operator_contact_no like '%$str%' or principle_name like '%$str%' or principle_contact_no like '%$str%' or personal_tel like '%$str%' or ANP_email like '%$str%' or name like '%$str%' or company_email like '%$str%' or country like '%$str%'  or company_name like '%$str%' or centre.centre_code like '%$str%'"; }

	$sIndexColumn = "rowNumber";
	$sTable = "
		(
			SELECT
				(SELECT @rownum := @rownum + 1 FROM ( SELECT @rownum := 0 ) AS `rowtable`) AS `rowNumber`,
				`centre`.*
				FROM
				(
					SELECT 
						`centre`.`centre_code`,
						`cs`.`name`,
                        `cnm`.`franchisee_company_name`,
                        `centre`.`kindergarten_name`,
                        `centre`.`assistant_name`,
                        `centre`.`id`,
                        CONCAT(`centre`.`company_name`,'<br>',`centre`.`centre_code`) AS `centre_detail`,

                        CONCAT(`centre`.`operator_name`,'<br>',`centre`.`operator_contact_no`) AS `operator_detail`,

                        CONCAT(`centre`.`principle_name`,'<br>',`centre`.`principle_contact_no`) AS `principle_detail`,

                        CONCAT(`centre`.`ANP_tel`,'<br>',`centre`.`ANP_email`) AS `ANP_contact`,

                        CONCAT(CASE 
                            WHEN `centre`.`status`='C' THEN 'Closed'
                            WHEN `centre`.`status`='A' THEN 'Active'
                            WHEN `centre`.`status`='S' THEN 'Sell Off'
                            WHEN `centre`.`status`='T' THEN 'Transferred'
                            WHEN `centre`.`status`='O' THEN 'Others'
                            WHEN `centre`.`status`='Pending' THEN 'Pending'
                            ELSE `centre`.`status`
                        END ) AS `status`,
                        
                        CONCAT('<div></div>') AS `action`
                        
						FROM `centre`
						  LEFT JOIN centre_status cs ON cs.id = `centre`.`centre_status_id`
                          LEFT JOIN (select * from `centre_franchisee_company`) cnm ON cnm.centre_code = `centre`.`centre_code`
						WHERE
                            created_date <= '".$year_end_date." 23:59:59' and `status`='A' ".$where."
                        ORDER BY `kindergarten_name`
				) AS `centre`
		) AS `centre`";
		
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

                    $action .= '<a href="index.php?p='.$p.'&m=&id='.sha1($aRow['id']).'&mode=EDIT"><img src="images/edit.png"></a>';
                    
                    if (($_SESSION["UserType"]=="S") & (hasRightGroupXOR($_SESSION["UserName"], "CentreEdit"))) 
                    {
                        $action .= '<a onclick=\'doDeleteRecord("'.sha1($aRow['id']).'")\' href="#" id="btnDelete"><img src="images/delete.png"></a>';
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
