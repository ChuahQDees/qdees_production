<?php

    session_start();
    include_once("../../mysql.php");

	$aColumns = array("product_code","product_name","uom","unit_price","monthly","status","remarks","remarks_master","doc_remarks","action","id");

    $year=$_SESSION['Year'];
    $centre_code=$_SESSION["CentreCode"];
    $p=isset($_GET["p"]) ? $_GET["p"] : '';
    $product_name_code=isset($_GET["product_name_code"]) ? $_GET["product_name_code"] : '';
    $start_date=isset($_GET["start_date"]) ? $_GET["start_date"] : '';
    $end_date=isset($_GET["end_date"]) ? $_GET["end_date"] : '';
    $status=isset($_GET["status"]) ? $_GET["status"] : '';

    $where = '';

	if($product_name_code != '') { $where .= " and (`addon_product`.`product_code` like '%$product_name_code%' or `addon_product`.`product_name` like '%$product_name_code%') "; }

    if($status != '') { $where .= " and `addon_product`.`status` = '$status' "; }
    if($start_date != '') { $where .= " and `addon_product`.`submission_date` >= '$start_date' "; }
    if($end_date != '') { $where .= " and `addon_product`.`submission_date` <= '$end_date' "; }

	$sIndexColumn = "rowNumber";
	$sTable = "
		(
			SELECT
				(SELECT @rownum := @rownum + 1 FROM ( SELECT @rownum := 0 ) AS `rowtable`) AS `rowNumber`,
				`addon_product`.*
				FROM
				(
					SELECT 
                        `addon_product`.`id`,
						`addon_product`.`product_code`,
                        `addon_product`.`product_name`,
                        `addon_product`.`uom`,
                        `addon_product`.`unit_price`,
                        `addon_product`.`monthly`,
                        `addon_product`.`status`,
                        `addon_product`.`remarks`,
                        `addon_product`.`remarks_master`,
                        
                        CONCAT('<a href=\"admin/uploads/',`addon_product`.`doc_remarks`,'\" target=\"_blank\">',`addon_product`.`doc_remarks`,'</a>') AS `doc_remarks`,
                        CONCAT('<div></div>') AS `action`
                        
						FROM `addon_product`
						WHERE
                            `addon_product`.`centre_code` = '$centre_code' ".$where."
                       
				) AS `addon_product`
		) AS `addon_product`";
		
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
					$action = '<a href="index.php?p='.$p.'&m=&id='.sha1($aRow['id']).'&mode=EDIT"  data-uk-tooltip title="Edit"><img src="images/edit.png"></a> &nbsp; &nbsp;';

                    $action .= '<a onclick=\'doDeleteRecord("'.sha1($aRow['id']).'")\' href="#" id="btnDelete" data-uk-tooltip title="Delete"><img src="images/delete.png"></a>';

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
