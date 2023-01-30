<?php

    session_start();

    include_once("../../mysql.php");
    include_once("../functions.php");

	$aColumns = array("user_name","user_type","user_role","centre_name","action","id");

    $year=$_SESSION['Year'];

    $str=isset($_GET["s"]) ? $_GET["s"] : '';
    $m=isset($_GET["m"]) ? $_GET["m"] : '';
    $p=isset($_GET["p"]) ? $_GET["p"] : '';

    $centre_code=$_SESSION["CentreCode"];
    
    $where = '';

	if($str!="") {
        if (($_SESSION["UserType"]=="A") || ($_SESSION["UserType"]=="O")) {

            $centre = mysqli_fetch_array(mysqli_query($connection,"SELECT `company_name` FROM `centre` WHERE `centre_code` = '".$centre_code."'"));

            $centre_name = $centre['company_name'];

            $where .= " where (`user_name` like '%$str%' or `centre_name` like '%$str%') and `centre_name`='$centre_name' ";
        }
        else if (($_SESSION["UserType"]=="S")  || ($_SESSION["UserType"]=="H") || ($_SESSION["UserType"]=="C") || ($_SESSION["UserType"]=="R") || ($_SESSION["UserType"]=="CM") || ($_SESSION["UserType"]=="T")) {
           $where .= " where (user_name like '%$str%' or centre_name like '%$str%') ";
        }
    } else {
        if (($_SESSION["UserType"]=="A") || ($_SESSION["UserType"]=="O")) {
           $where .= " where centre_code='$centre_code' ";
        }
        else if (($_SESSION["UserType"]=="S")  || ($_SESSION["UserType"]=="H") || ($_SESSION["UserType"]=="C") || ($_SESSION["UserType"]=="R") || ($_SESSION["UserType"]=="CM") || ($_SESSION["UserType"]=="T")) {
           $where .= "";
        }
    }

	$sIndexColumn = "rowNumber";
	$sTable = "
		(
			SELECT
				(SELECT @rownum := @rownum + 1 FROM ( SELECT @rownum := 0 ) AS `rowtable`) AS `rowNumber`,
				`user`.*
				FROM
				(
					SELECT DISTINCT
						`user`.`user_name`,
                        `user`.`user_role`,
                        `user`.`centre_name`,
						`user`.`id`,
						
                        CONCAT(CASE 
                            WHEN `user`.`user_type`='S' THEN 'Super admin'
                            WHEN `user`.`user_type`='A' THEN 'Centre Admin'
                            WHEN `user`.`user_type`='C' THEN 'Corporate'
                            WHEN `user`.`user_type`='T' THEN 'Territory master'
                            WHEN `user`.`user_type`='R' THEN 'Regional master'
                            WHEN `user`.`user_type`='CM' THEN 'Country master'
                            WHEN `user`.`user_type`='H' THEN 'HQ'
                            WHEN `user`.`user_type`='O' THEN 'Centre Staff'
                            ELSE `user`.`user_type`
                        END ) AS `user_type`,
                        
                        CONCAT('<div></div>') AS `action`
                        
						FROM `user` ".$where."
                        ORDER BY `user`.`user_name` 
				) AS `user`
		) AS `user`";
		
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

                    $action .= '<a data-uk-tooltip title="Edit user" href="index.php?p='.$p.'&m='.$m.'&id='.sha1($aRow['id']).'&mode=EDIT"><i class="fa fa-edit" style="font-size: 1.1rem;margin-right:5px;"></i></a>';

                    $action .= '<a  data-uk-tooltip title="Delete user"onclick=\'doDeleteRecord("'.sha1($aRow['id']).'")\' href="#" id="btnDelete"><i class="fa fa-trash-alt text-danger" style="font-size: 1.1rem;margin-right:5px;"></i></a>';

                    if ((($_SESSION["UserType"]=="A") || ($_SESSION["UserType"]=="O") || ($_SESSION["UserType"]=="S")  || ($_SESSION["UserType"]=="H") || ($_SESSION["UserType"]=="C") || ($_SESSION["UserType"]=="R") || ($_SESSION["UserType"]=="CM") || ($_SESSION["UserType"]=="T")) & (hasRightGroupXOR($_SESSION["UserName"], "UserRightsEdit|UserRightsView"))) {
                       
                        $action .= '<a  data-uk-tooltip title="User Control" href="index.php?p=userright&user_name='.$aRow['user_name'].'"><img title="User Right" src="images/fingerprint.png" style="font-size: 1.1rem;"></a>';
                       
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
