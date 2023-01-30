<?php

    session_start();
    include_once("../../mysql.php");
    include_once("../../search_new.php");
    include_once("../functions.php");

	$aColumns = array("product_photo","code","product_name","unit_price","action","product_code");

    $where = '';

    if($_GET['course_name'] != '') { $where .= " and `product`.`sub_sub_category` LIKE '%".$_GET['course_name']."%'"; }

    if($_GET['term'] != '') { $where .= " and `product`.`term` LIKE '%".$_GET['term']."%'"; }

    if($_GET['s'] != '') { $where .= " and ((`product`.`product_code` LIKE '%".$_GET['s']."%') OR (`product`.`product_name` LIKE '%".$_GET['s']."%'))"; }

    if($_GET['module'] != '') { $where .= " and `product`.`product_code` LIKE '%".$_GET['module']."%'"; }

    if($_GET['subject'] != '') { $where .= " and `product`.`product_name` LIKE '%".$_GET['subject']."%'"; }

	$sIndexColumn = "rowNumber";
	$sTable = "
		(
			SELECT
				(SELECT @rownum := @rownum + 1 FROM ( SELECT @rownum := 0 ) AS `rowtable`) AS `rowNumber`,
				`product`.*
				FROM
				(
					SELECT DISTINCT
						`product`.`product_name`,
						`product`.`unit_price`,
						`product`.`product_code`,
                        CONCAT('<a href=\"admin/uploads/',`product`.`product_photo`,'\" data-uk-lightbox><img src=\"admin/uploads/',`product`.`product_photo`,'\" width=\"50\"></a>') AS `product_photo`,

						CONCAT('<div></div>') AS `code`,
                        CONCAT('<div></div>') AS `action`

						FROM `product`
						WHERE
						   1=1 ".$where."
				) AS `product`
		) AS `product`";
		
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
				if($aColumns[$i] == 'code')
				{
                    $product_code = $aRow['product_code'];
                    $aRow[ $aColumns[$i] ] = explode("((--",$product_code)[0];
				}
				else if($aColumns[$i] == 'action')
				{
					$action = '<a data-uk-tooltip="{pos:top}" title="Add to Basket" onclick="add2TempBusket(\''.$aRow['product_code'].'\', \''.$_GET['student_code'].'\')"><i class="uk-icon-hover uk-icon-medium uk-icon-cart-plus"></i></a>';
					
					$aRow[ $aColumns[$i] ] = $action;
				}
				
				if($aColumns[$i] != 'product_code')
				{
					$row[] = $aRow[ $aColumns[$i] ];
				}
			}
		}
		$output['aaData'][] = $row;
	}
	echo json_encode($output);
?>
