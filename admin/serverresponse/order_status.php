<?php

    session_start();
    include_once("../../mysql.php");
	include_once("../functions.php");
	
    if (($_SESSION["UserType"] == "S") || ($_SESSION["UserType"] == "H") || ($_SESSION["UserType"] == "C") || ($_SESSION["UserType"] == "R") || ($_SESSION["UserType"] == "CM") || ($_SESSION["UserType"] == "T")) 
    {
        $aColumns = array("company_name","order_no","ordered_by","ordered_on","status","courier","action","id","delivered_to_logistic_by","order_number", "acknowledged_by", "finance_approved_by", "packed_by", "logistic_approved_by");

        $where = "";
    }
    else
    {
        $aColumns = array("order_no","ordered_by","ordered_on","status","courier","action","id","delivered_to_logistic_by","order_number", "acknowledged_by", "finance_approved_by", "packed_by", "logistic_approved_by");

        $where = " AND `order`.`centre_code` = '".$_SESSION["CentreCode"]."' ";
    }

    $centre_code = $_SESSION['CentreCode'];
    $year=$_SESSION['Year'];
    $sOrderNo=isset($_GET['sOrderNo']) ? $_GET['sOrderNo'] : '';
    $sCentreCode1=isset($_GET['sCentreCode1']) ? $_GET['sCentreCode1'] : '';
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

//Super Admin status = S

    if($sOrderNo != '') { $where .= " AND (`order`.`order_no` like '%$sOrderNo%')"; }

    //if($sCentreCode1 != '') { $where .= " AND `order`.`centre_code` = '".$sCentreCode1."'"; }
	
	if($sCentreCode1 != '') { $where .= " AND `order`.`centre_code` = '".$sCentreCode1."'"; }

	if ($date_from != '' && $date_to == '') {
		
		$where .= " AND `order`.`ordered_on` >= '".$date_from." 00:00:00' AND `order`.`ordered_on` <= '".date("Y/m/d")." 23:59:59' ";

	} elseif ($date_from == '' && $date_to != '') {
		
		$where .= " AND `order`.`ordered_on` >= '".$date_to." 00:00:00' AND `order`.`ordered_on` <= '".$date_to." 23:59:59' ";

	} elseif ($date_from != '' && $date_to != '') {

		$where .= " AND `order`.`ordered_on` >= '".$date_from." 00:00:00' AND `order`.`ordered_on` <= '".$date_to." 23:59:59' ";
	}

	if($status == "Cancelled") {
		$where .= " AND IFNULL(`order`.`cancelled_by`, '') != '' ";
	} else if($status == "Delivered") {
		$where .= " AND IFNULL(`order`.`finance_payment_paid_by`, '') !='' AND IFNULL(`order`.`cancelled_by`, '') = '' AND IFNULL(`order`.`acknowledged_by`, '') !='' ";
	} else if($status == "Ready for Collection") {
		$where .= " AND IFNULL(`order`.`cancelled_by`, '') ='' AND IFNULL(`order`.`delivered_to_logistic_by`, '') ='' AND IFNULL(`order`.`packed_by`, '') !='' ";
	} else if($status == "Ready for Collection (F)") {
		$where .= " AND IFNULL(`order`.`cancelled_by`, '') ='' AND IFNULL(`order`.`acknowledged_by`, '') !='' AND IFNULL(`order`.`logistic_approved_by`, '') !='' AND IFNULL(`order`.`packed_by`, '') !='' AND IFNULL(`order`.`finance_approved_by`, '') ='' ";
		//if (($aRow["acknowledged_by"]!="") & ($aRow["logistic_approved_by"]!="") & ($aRow["finance_approved_by"]=="") & ($aRow["packed_by"]!="")){
	} else if($status == "Ready for Collection (S)") {
		$where .= " AND IFNULL(`order`.`cancelled_by`, '') ='' AND IFNULL(`order`.`acknowledged_by`, '') !='' AND IFNULL(`order`.`finance_approved_by`, '') !='' AND IFNULL(`order`.`packed_by`, '') !='' AND IFNULL(`order`.`delivered_to_logistic_by`, '') ='' ";
		//($aRow["acknowledged_by"]!="") & ($aRow["finance_approved_by"]!="") & ($aRow["delivered_to_logistic_by"]=="") & ($aRow["packed_by"]!="")){
	} else if($status == "Logistics Pending (L)") {
		$where .= " AND IFNULL(`order`.`cancelled_by`, '') ='' AND IFNULL(`order`.`acknowledged_by`, '') !='' 
		AND IFNULL(`order`.`packed_by`, '') =''";
		//($aRow["acknowledged_by"]!="") & ($aRow["finance_approved_by"]!="") & ($aRow["delivered_to_logistic_by"]=="") & ($aRow["packed_by"]!="")){
	} else if($status == "Finance Pending (F)") {
		$where .= " AND IFNULL(`order`.`cancelled_by`, '') ='' AND IFNULL(`order`.`finance_approved_by`, '') = ''
		AND IFNULL(`order`.`acknowledged_by`, '') != ''";
		//($aRow["acknowledged_by"]!="") & ($aRow["finance_approved_by"]!="") & ($aRow["delivered_to_logistic_by"]=="") & ($aRow["packed_by"]!="")){
	} else if($status == "Packing") {
		$where .= " AND IFNULL(`order`.`packed_by`, '') = '' AND IFNULL(`order`.`logistic_approved_by`, '') != '' AND IFNULL(`order`.`finance_approved_by`, '') ='' AND IFNULL(`order`.`cancelled_by`, '') = '' ";
	} else if($status == "Acknowledged") {
		$where .= " AND IFNULL(`order`.`acknowledged_by`, '') !='' AND IFNULL(`order`.`cancelled_by`, '') = '' AND IFNULL(`order`.`logistic_approved_by`, '') ='' ";
	} else if($status == "Pending") {
		$where .= " AND IFNULL(`order`.`acknowledged_by`, '') ='' AND IFNULL(`order`.`cancelled_by`, '') = '' ";
	}  

	$sIndexColumn = "rowNumber";
	$sTable = "
		(
			SELECT
				(SELECT @rownum := @rownum + 1 FROM ( SELECT @rownum := 0 ) AS `rowtable`) AS `rowNumber`,
				`order`.*
				FROM
				(
					SELECT distinct 
                        `order`.`id`,
                        `order`.`ordered_by`,
                        `order`.`ordered_on`,
                        `order`.`courier`,
                        `centre`.`company_name`,
						`order`.`delivered_to_logistic_by`,
						`order`.`acknowledged_by`,
						`order`.`finance_approved_by`,
						`order`.`logistic_approved_by`,
						`order`.`packed_by`,

						`order`.`order_no` AS `order_number`,

						CONCAT('<a href=\"index.php?p=order_status&sOrderNo=',`order`.`order_no`,'&company_name=',`centre`.`company_name`,'\">',`order`.`order_no`,'</a>') AS `order_no`,

                        CONCAT('<div></div>') AS `status`,
                        CONCAT('<div></div>') AS `action`

						FROM `order` 
                            LEFT JOIN `centre` ON `centre`.`centre_code` = `order`.`centre_code`
                        WHERE
							(CAST(`order`.`ordered_on` AS DATE) BETWEEN '".$year_start_date."' AND '".$year_end_date."') ".$where."
                        GROUP BY `order`.`order_no`

                        ORDER BY `order`.`id` DESC, `order`.`acknowledged_on`, `order`.`ordered_on` DESC
                        
				) AS `order`
		) AS `order`";
		
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

					if (($_SESSION["isLogin"] == 1) & (hasRightGroupXOR($_SESSION["UserName"], "OrderStatusEdit|OrderStatusView"))) 
					{	
						$action .= '<a data-uk-tooltip title="Generate Sales Order" href="admin/generate_so.php?order_no='.sha1($aRow["order_number"]).'" target="_blank"><img src="images/so.png" style="width:30px;"></a>';
					}
						
					 if (($aRow["delivered_to_logistic_by"] != "") & ($_SESSION["isLogin"] == 1) & (hasRightGroupXOR($_SESSION["UserName"], "OrderStatusEdit|OrderStatusView"))) 
					{
						
						   $action .= '<a data-uk-tooltip title="Generate Delivery Order" href="admin/generate_do.php?order_no='.sha1($aRow["order_number"]).'" target="_blank"><img src="images/do.png" style="width:30px;"></a>';
					}

					$ordered_on = $aRow["ordered_on"];
					$after_14_ex = date('Y-m-d', strtotime('+14 day', strtotime($ordered_on)));
					
					if (date('Y-m-d') <= $after_14_ex)
					{
					   if (isDelivered($aRow["order_number"])) 
					   {   
						   $action .= '<a data-uk-tooltip title="Report Defective" href="index.php?p=order_status&sOrderNo='.$aRow["order_number"].'"><img src="images/def.png" style="width:30px;"></a>';
					   }
					}

					$aRow[ $aColumns[$i] ] = $action;
				}
				else if($aColumns[$i] == 'status')
				{
					$status = getStatus($aRow["order_number"]);

					//if (($aRow["acknowledged_by"]!="") & ($aRow["finance_approved_by"]!="") & ($aRow["packed_by"]!="") & ($aRow["delivered_to_logistic_by"]=="")){
					//if (($aRow["acknowledged_by"]!="") & ($aRow["logistic_approved_by"]!="") & ($aRow["finance_approved_by"]=="") & ($aRow["packed_by"]!="" & $_SESSION["UserType"] != "A")){
						
					/*Status Rewrite*/
					
					$statusString = $status;
					
					if (($aRow["acknowledged_by"]!="") &  ($aRow["finance_approved_by"]=="") & ($_SESSION["UserType"] != "A")){
						$status .= " (F)";
					}
					
					if (($aRow["acknowledged_by"]!="") & ($aRow["finance_approved_by"]!="") & ($aRow["delivered_to_logistic_by"]=="") & ($aRow["packed_by"]!="" & $_SESSION["UserType"] != "A")){
						$status .= " (S)";
					}
					
					if (($aRow["acknowledged_by"]!="") & ($aRow["packed_by"]=="" & $_SESSION["UserType"] != "A")){
						$status .= " (L)";
					}
					
					$aRow[ $aColumns[$i] ] = $status;
					//
					/*
					if (($aRow["acknowledged_by"]!="") &  ($aRow["finance_approved_by"]=="") & ($_SESSION["UserType"] != "A")){
						$aRow[ $aColumns[$i] ] = $status . " (F)";
					}
					else if (($aRow["acknowledged_by"]!="") & ($aRow["finance_approved_by"]!="") & ($aRow["delivered_to_logistic_by"]=="") & ($aRow["packed_by"]!="" & $_SESSION["UserType"] != "A")){
						//if (($row["acknowledged_by"]!="") & ($row["finance_approved_by"]!="") & ($row["packed_by"]!="") & ($row["delivered_to_logistic_by"]=="")
						$aRow[ $aColumns[$i] ] = $status . " (S)";
					}else{
						$aRow[ $aColumns[$i] ] = $status;
					}
					*/
					
					/*
					if (($aRow["acknowledged_by"]!="") &  ($aRow["finance_approved_by"]=="") & ($_SESSION["UserType"] != "A")){
						$aRow[ $aColumns[$i] ] = $status . " (F)";
					}
					else if (($aRow["acknowledged_by"]!="") & ($aRow["finance_approved_by"]!="") & ($aRow["delivered_to_logistic_by"]=="") & ($aRow["packed_by"]!="" & $_SESSION["UserType"] != "A")){
						//if (($row["acknowledged_by"]!="") & ($row["finance_approved_by"]!="") & ($row["packed_by"]!="") & ($row["delivered_to_logistic_by"]=="")
						$aRow[ $aColumns[$i] ] = $status . " (S)";
					}else{
						$aRow[ $aColumns[$i] ] = $status;
					}
					*/
				}

				if($aColumns[$i] !='id' && $aColumns[$i] != 'delivered_to_logistic_by' && $aColumns[$i] != 'order_number')
				{
					$row[] = $aRow[ $aColumns[$i] ];
				}
			}
		}
		$output['aaData'][] = $row;
	}
	echo json_encode($output);

	function isDelivered($order_no)
	{
		global $connection;

		$sql = "SELECT * from `order` where order_no='$order_no'";;
		$result = mysqli_query($connection, $sql);
		$row = mysqli_fetch_assoc($result);
		if ($row["delivered_to_logistic_by"] != "") {
		return true;
		} else {
		return false;
		}
	}

	function getStatus($order_no)
	{
	   	global $connection;

	   	$sql = "SELECT * from `order` where order_no='$order_no'";
	
		
		//CHS: Code rewrite because the table only takes the first status in the row for some reason
	   	$result = mysqli_query($connection, $sql);
		$cancelledFlag = true;

		while ($row = mysqli_fetch_assoc($result)) {
			if (!$row["cancelled_by"] != "" && $cancelledFlag == true) {
				//Confirmed there is at least one order which isn't cancelled. Do not mark the order as Cancelled.
				$cancelledFlag = false;
			}
			
			//Begin checking to see what status to put
			if ($cancelledFlag == false){
				if ($row["delivered_to_logistic_by"] != "") {
					if ($row["finance_payment_paid_by"] != "") {
						return ($_SESSION["UserType"] == "S") ? "Delivered" : "Delivered";
					} else {
						return ($_SESSION["UserType"] == "S") ? "Delivered" : "Delivered"; 
					}
				} else {
					if ($row["packed_by"] != "") {
						if ($row["finance_payment_paid_by"] != "") {
							return ($_SESSION["UserType"] == "S") ? "Ready for Collection" : "Ready for Collection";
						} else {
							return ($_SESSION["UserType"] == "S") ? "Ready for Collection" : "Ready for Collection";  
						}
					} else {
						if ($row["finance_approved_by"] != "") {
							if ($row["finance_payment_paid_by"] != "") {
								return ($_SESSION["UserType"] == "S") ? "Finance Approved (Paid)" : "Finance Approved";
							} else {
								return ($_SESSION["UserType"] == "S") ? "Finance Approved (Pending Payment)" : "Finance Approved";
							}
						} else {
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
		
		if ($cancelledFlag == true){
			return "Cancelled";
		}
		
		/*
	   	$result = mysqli_query($connection, $sql);
	   	if ($result) {
		  	$row = mysqli_fetch_assoc($result);

		  	if ($row["cancelled_by"] != "") {
			 	return "Cancelled";
		  	} else {
			 	if ($row["delivered_to_logistic_by"] != "") {
					if ($row["finance_payment_paid_by"] != "") {
				   		return ($_SESSION["UserType"] == "S") ? "Delivered" : "Delivered";
					} else {
				   		return ($_SESSION["UserType"] == "S") ? "Delivered" : "Delivered"; 
					}
			 	} else {
					if ($row["packed_by"] != "") {
				   		if ($row["finance_payment_paid_by"] != "") {
					  		return ($_SESSION["UserType"] == "S") ? "Ready for Collection" : "Ready for Collection";
				   		} else {
					  		return ($_SESSION["UserType"] == "S") ? "Ready for Collection" : "Ready for Collection";  
				   		}
					} else {
						if ($row["finance_approved_by"] != "") {
							if ($row["finance_payment_paid_by"] != "") {
								return ($_SESSION["UserType"] == "S") ? "Finance Approved (Paid)" : "Finance Approved";
							} else {
								return ($_SESSION["UserType"] == "S") ? "Finance Approved (Pending Payment)" : "Finance Approved";
							}
						} else {
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
		*/
	}

?>
