<?php

    session_start();
    include_once("../../mysql.php");
	include_once("../functions.php");
	
    if (($_SESSION["UserType"] == "S") || ($_SESSION["UserType"] == "H") || ($_SESSION["UserType"] == "C") || ($_SESSION["UserType"] == "R") || ($_SESSION["UserType"] == "CM") || ($_SESSION["UserType"] == "T")) 
    {
        $aColumns = array("company_name","sales_order_no","order_no","ordered_by","ordered_on","status","courier","action","id","delivered_to_logistic_by","order_number");

        $where = "";
    }
    else
    {
        $aColumns = array("sales_order_no","order_no","ordered_by","ordered_on","status","courier","action","id","delivered_to_logistic_by","order_number");

        $where = " AND `defective`.`centre_code` = '".$_SESSION["CentreCode"]."' ";
    }

    $centre_code = $_SESSION['CentreCode'];
    $year=$_SESSION['Year'];
    $sOrderNo=isset($_GET['sOrderNo']) ? $_GET['sOrderNo'] : '';
    $sCentreCode=isset($_GET['sCentreCode']) ? $_GET['sCentreCode'] : '';
    $status=isset($_GET['status']) ? $_GET['status'] : '';
    $company_name=isset($_GET['company_name']) ? $_GET['company_name'] : '';
    $date_from=isset($_GET['date_from']) ? $_GET['date_from'] : '';
    $date_to=isset($_GET['date_to']) ? $_GET['date_to'] : '';

    if ($date_from != "") {
        $y_date_from = $date_from;
        list($day, $month, $year) = explode("/", $date_from);
        $date_from = "$year-$month-$day"; 
    }
    if ($date_to != "") {
        $y_date_to = $date_to;
        list($day, $month, $year) = explode("/", $date_to);
        $date_to = "$year-$month-$day";
    }

    if($sOrderNo != '') { $where .= " AND (`defective`.`order_no` like '%$sOrderNo%')"; }

    if($sCentreCode != '') { $where .= " AND `defective`.`centre_code` = '".$sCentreCode."'"; }

	if ($date_from != '' && $date_to == '') {
		
		$where .= " AND `defective`.`ordered_on` >= '".$date_from." 00:00:00' AND `defective`.`ordered_on` <= '".date("Y/m/d")." 23:59:59' ";

	} elseif ($date_from == '' && $date_to != '') {
		
		$where .= " AND `defective`.`ordered_on` >= '2019-01-01 00:00:00' AND `defective`.`ordered_on` <= '".$date_to." 23:59:59' ";

	} elseif ($date_from != '' && $date_to != '') {

		$where .= " AND `defective`.`ordered_on` >= '".$date_from." 00:00:00' AND `defective`.`ordered_on` <= '".$date_to." 23:59:59' ";
	}

	if($status == "Rejected") {

		$where .= " AND `defective`.`reject_status` = 1 ";

	} else if($status == "Cancelled") {

		$where .= " AND (`defective`.`reject_status` = 2 OR IFNULL(`defective`.`cancelled_by`, '') != '')";

	} else if($status == "Delivered") {
		
		$where .= " AND (`defective`.`reject_status` != 1 OR ISNULL(`defective`.`reject_status`)) AND IFNULL(`defective`.`cancelled_by`, '') ='' AND IFNULL(`defective`.`delivered_to_logistic_by`, '') != '' ";
		
	}   
	else if($status == "Ready for Collection")
	{
		$where .= " AND (`defective`.`reject_status` != 1 OR ISNULL(`defective`.`reject_status`)) AND IFNULL(`defective`.`cancelled_by`, '') = '' AND IFNULL(`defective`.`delivered_to_logistic_by`, '') = '' AND IFNULL(`defective`.`packed_by`, '') != '' ";
	}
	else if($status == "Packing")
	{
		$where .= " AND (`defective`.`reject_status` != 1 OR ISNULL(`defective`.`reject_status`)) AND IFNULL(`defective`.`cancelled_by`, '') = '' AND IFNULL(`defective`.`delivered_to_logistic_by`, '') = '' AND IFNULL(`defective`.`packed_by`, '') = ''  AND IFNULL(`defective`.`logistic_approved_by`, '') != '' ";
	}
	else if($status == "Acknowledged")
	{
		$where .= " AND (`defective`.`reject_status` != 1 OR ISNULL(`defective`.`reject_status`)) AND IFNULL(`defective`.`cancelled_by`, '') = '' AND IFNULL(`defective`.`delivered_to_logistic_by`, '') = '' AND IFNULL(`defective`.`packed_by`, '') = ''  AND IFNULL(`defective`.`logistic_approved_by`, '') = '' AND IFNULL(`defective`.`acknowledged_by`, '') != '' ";
	}
	else if($status == "Pending")
	{
		$where .= " AND (`defective`.`reject_status` != 1 OR ISNULL(`defective`.`reject_status`)) AND IFNULL(`defective`.`cancelled_by`, '') = '' AND IFNULL(`defective`.`delivered_to_logistic_by`, '') = '' AND IFNULL(`defective`.`packed_by`, '') = ''  AND IFNULL(`defective`.`logistic_approved_by`, '') = '' AND IFNULL(`defective`.`acknowledged_by`, '') = '' ";
	}

	$sIndexColumn = "rowNumber";
	$sTable = "
		(
			SELECT
				(SELECT @rownum := @rownum + 1 FROM ( SELECT @rownum := 0 ) AS `rowtable`) AS `rowNumber`,
				`defective`.*
				FROM
				(
					SELECT distinct 
                        `defective`.`id`,
                        `defective`.`ordered_by`,
                        `defective`.`ordered_on`,
						`defective`.`sales_order_no`,
                        `centre`.`company_name`,
						`defective`.`delivered_to_logistic_by`,
						`defective`.`delivered_to_logistic_on`,
						`defective`.`defective_reason`,
						`defective`.`doc`,
						`defective`.`remarks`,
						`defective`.`order_no` AS `order_number`,
						`defective`.`courier`,
						CONCAT('<a href=\"index.php?p=defective_status&sOrderNo=',`defective`.`order_no`,'\">',`defective`.`order_no`,'</a>') AS `order_no`,

                        CONCAT('<div></div>') AS `status`,
                        CONCAT('<div></div>') AS `action`

						FROM `defective` 
                            LEFT JOIN `centre` ON `centre`.`centre_code` = `defective`.`centre_code` WHERE
                        	(CAST(`defective`.`ordered_on` AS DATE) BETWEEN '".$year_start_date."' AND '".$year_end_date."') ".$where."
                        
				) AS `defective`
		) AS `defective`";
		
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

					if (($_SESSION["isLogin"]==1) & (hasRightGroupXOR($_SESSION["UserName"], "DefectiveStatusEdit|DefectiveStatusView"))) 
					{
						$action .= '<center>';
						
						if (($aRow["delivered_to_logistic_by"] != "") & ($_SESSION["isLogin"] == 1) & (hasRightGroupXOR($_SESSION["UserName"], "OrderStatusEdit|OrderStatusView"))) 
						{	  
							if (getStatus($aRow["order_number"]) != "Rejected") 
							{ 
								$action .= '<a data-uk-tooltip title="Generate Delivery Order" href="admin/generate_defective_do.php?order_no='.sha1($aRow["order_number"]).'" target="_blank"><i class="fa fa-truck text-info" style="font-size: 1.3rem;"></i></a>';
							}
						}
								
						$action .= '<a data-uk-tooltip title="Cancel" onclick="doCancelOrder(\''.sha1($aRow["id"]).'\', \''.sha1($aRow["order_number"]).'\')" href="#"><i class="fa fa-times text-danger" style="font-size: 1.3rem;"></i></a></center>';
					}

					$aRow[ $aColumns[$i] ] = $action;
				}
				else if($aColumns[$i] == 'status')
				{
					$status = getStatus($aRow["order_number"]);

					$aRow[ $aColumns[$i] ] = $status;
				}
				else if($aColumns[$i] == 'courier')
				{
					$courier = '';

					if (getStatus($aRow["order_number"]) != "Rejected") {
						$courier = $aRow["courier"];
					} 

					$aRow[ $aColumns[$i] ] = $courier;
				}

				if($aColumns[$i] !='id' && $aColumns[$i] != 'delivered_to_logistic_by' && $aColumns[$i] != 'order_number')
				{
					$row[] = $aRow[ $aColumns[$i] ];
				}
			}
		}

		if(getStatus($aRow["order_number"]) == $status || $status == "")
		{
			$output['aaData'][] = $row;	
		}
	}
	echo json_encode($output);

	function getStatus($order_no) {
		global $connection;
	 
		$sql="SELECT * from `defective` where order_no='$order_no'";
	 
		$result=mysqli_query($connection, $sql);
		if ($result) {
		   	$row=mysqli_fetch_assoc($result);
	  
			if ($row["reject_status"] === '1') {
				return "Rejected";
			} else if ($row["reject_status"] === '2'){
			  return "Cancelled";
		   	}
		   	if ($row["cancelled_by"] != "") {
				return "Cancelled";
			} 
			else 
			{
				if ($row["delivered_to_logistic_by"] != "") 
				{
					if ($row["finance_payment_paid_by"] != "") {
						return ($_SESSION["UserType"] == "S") ? "Delivered" : "Delivered";
					} else {
						return ($_SESSION["UserType"] == "S") ? "Delivered" : "Delivered"; 
					}
				} 
				else 
				{
					if ($row["packed_by"] != "") 
					{
						if ($row["finance_payment_paid_by"] != "") {
							return ($_SESSION["UserType"] == "S") ? "Ready for Collection" : "Ready for Collection";
						} else {
							return ($_SESSION["UserType"] == "S") ? "Ready for Collection" : "Ready for Collection";  
						}
					} 
					else 
					{
						if ($row["finance_approved_by"] != "") 
						{
							if ($row["finance_payment_paid_by"] != "") {
								return ($_SESSION["UserType"] == "S") ? "Finance Approved (Paid)" : "Finance Approved";
							} else {
								return ($_SESSION["UserType"] == "S") ? "Finance Approved (Pending Payment)" : "Finance Approved";
						 	}
						} 
						else 
						{
							if ($row["logistic_approved_by"] != "") {
								return "Packing";
							} else {
								if ($row["acknowledged_by"] != "") {
								   return "Acknowledged";
								} else {
								   return "Pending";
								}
							}
						}
				 	}
			  	}
			}
		}
	}

?>
