<?php
session_start();
include_once("mysql.php");
include_once("admin/functions.php");

$order_no=$_POST["orderID"];
$modeType=$_POST["modeType"]; //Get ModeType

$order_no=str_replace("'", "", $order_no);
$order_no=str_replace('"', '', $order_no);
$order_no=str_replace('	', '', $order_no);

if ($modeType == 'searchID'){
    //Search if the order ID is valid
    $sql="SELECT * from `order` where order_no='$order_no'"; 

    $result=mysqli_query($connection, $sql);
    $num_row=mysqli_num_rows($result);
    $row=mysqli_fetch_assoc($result);

    if ($num_row>0) {
        echo "1|".$order_no;
    }else{
        echo $sql;
    }
}else if ($modeType == 'revertPending'){
    //Search if the order ID is valid
    $sql = "UPDATE `order` SET acknowledged_by = NULL, acknowledged_on = NULL WHERE order_no = '$order_no'";

    $result=mysqli_query($connection, $sql);
    $msg="Record inserted";
}else if ($modeType == 'updateOrder'){
    $curProductCode=$_POST["curProductCode"];
    $currentUnitPrice=$_POST["currentUnitPrice"];
    $currentQty=$_POST["currentQty"];
    $currentTotal=$_POST["currentTotal"];

    $sql = "UPDATE `order` SET product_code = '$curProductCode', unit_price = '$currentUnitPrice', qty = '$currentQty', total = '$currentTotal' WHERE id = '$order_no'";

    $result=mysqli_query($connection, $sql);
  //echo $curProductCode;
  echo "Record updated!";
    // $msg="Record updated";
}else if ($modeType == 'normalizeSTEM'){
    //Check for existing 
    $x = 1;
    $stringCheck = "MY-STEM-MOD 0".$x."((--MSSB_112";
    $stringCheckKit = "MY-STEM-S.KIT-MOD 0".$x."((--MSSB_112";

    while ($x != 16){
        //Get information accordingly via loop
        if ($x >= 10){
            //Remove 0 at the back
            $stringCheck = "MY-STEM-MOD ".$x."((--MSSB_112";
            $stringCheckKit = "MY-STEM-S.KIT-MOD ".$x."((--MSSB_112";
        }else{
            $stringCheck = "MY-STEM-MOD 0".$x."((--MSSB_112";
            $stringCheckKit = "MY-STEM-S.KIT-MOD 0".$x."((--MSSB_112";
        }

        //First, checking for STEM-MOD
        $sql2 = "SELECT * FROM `order` WHERE order_no='$order_no' AND (product_code = '$stringCheck' OR product_code = '$stringCheckKit')";
        $result2=mysqli_query($connection, $sql2);
        $num_row2=mysqli_num_rows($result2);

        if ($num_row2==2 || $num_row2 == 0) {
            //If it's exactly 0 or 2, then everything's OK
        }else if ($num_row2 == 1){
            //Something's missing. Time to add! But first we need to know what's the item in question.

            //First we check the product_code of what's inside the DB, then we get everything from the existing record
            if ($row = mysqli_fetch_assoc($result2)) {
                $order_no = $row["order_no"];
                $centre_code = $row["centre_code"];
                $company_name = $row["company_name"];
                $user_name = $row["user_name"];
                $product_code = $row["product_code"];
                $qty = $row["qty"];
                $unit_price = $row["unit_price"];
                $total = $row["total"];
                $ordered_by = $row["ordered_by"];
                $ordered_on = $row["ordered_on"];
                $acknowledged_by = $row["acknowledged_by"];
                $acknowledged_on = $row["acknowledged_on"];
                $logistic_approved_by = $row["logistic_approved_by"];
                $logistic_approved_on = $row["logistic_approved_on"];
                $finance_approved_by = $row["finance_approved_by"];
                $finance_approved_on = $row["finance_approved_on"];

                $finance_payment_paid_by = $row["finance_payment_paid_by"];
                $finance_payment_paid_on = $row["finance_payment_paid_on"];
                $payment_document = $row["payment_document"];
                $packed_by = $row["packed_by"];
                $packed_on = $row["packed_on"];
                $assigned_to_by = $row["assigned_to_by"];
                $assigned_to_on = $row["assigned_to_on"];
                $assigned_to = $row["assigned_to"];
                $tracking_no = $row["tracking_no"];

                $delivered_to_logistic_by = $row["delivered_to_logistic_by"];
                $delivered_to_logistic_on = $row["delivered_to_logistic_on"];
                $name = $row["name"];
                $ic_no = $row["ic_no"];
                $signature = $row["signature"];

                $courier = $row["courier"];
                $cancelled_by = $row["cancelled_by"];
                $cancelled_on = $row["cancelled_on"];
                $cancel_reason = $row["cancel_reason"];

                $approve_cancellation = $row["approve_cancellation"];
                $cancel_request_reason = $row["cancel_request_reason"];
                $request_by = $row["request_by"];
                $request_reject_reason = $row["request_reject_reason"];
                $remarks = $row["remarks"];

                $preferred_collection_date = $row["preferred_collection_date"];
                $preferredtime = $row["preferredtime"];
                $slot = $row["slot"];
                $checked_by = $row["checked_by"];
                $remarks_delivery = $row["remarks_delivery"];
                $finance_approved = $row["finance_approved"];

                //Now we check if it's a module or a normal kit
                if ($product_code == $stringCheck){
                    //It's a STEM MOD, make a new Kit entry
                    $product_code = $stringCheckKit;
                    $unit_price = '40.00';

                }else if($product_code == $stringCheckKit){
                    //It's a STEM Kit, make a new Mod entry
                    $product_code = $stringCheck;
                    $unit_price = '30.00';
                }
                $total = $unit_price * $qty;

                //Wake up honey, it's time to insert stuff

                $sqlString = "INSERT INTO `order` (order_no, centre_code, company_name, user_name, product_code, qty, unit_price, total, ordered_by, ordered_on, acknowledged_by, acknowledged_on, logistic_approved_by, logistic_approved_on,
                finance_approved_by, finance_approved_on, finance_payment_paid_by, finance_payment_paid_on, payment_document, packed_by, packed_on, assigned_to_by, assigned_to_on, assigned_to, tracking_no, delivered_to_logistic_by,
                delivered_to_logistic_on, name, ic_no, signature, courier, cancelled_by, cancelled_on, cancel_reason, approve_cancellation, cancel_request_reason, request_by, request_reject_reason, remarks, preferred_collection_date,
                preferredtime, slot, checked_by, remarks_delivery, finance_approved) 
                VALUES ('$order_no', '$centre_code', '$company_name', '$user_name', '$product_code', '$qty', '$unit_price', '$total', '$ordered_by', '$ordered_on', ";

                if ($acknowledged_by != ''){
                    $sqlString .= "'$acknowledged_by',";
                }else{
                    $sqlString .= "NULL, ";
                }

                if ($acknowledged_on != ''){
                    $sqlString .= "'$acknowledged_on',";
                }else{
                    $sqlString .= "NULL, ";
                }

                if ($logistic_approved_by != ''){
                    $sqlString .= "'$logistic_approved_by',";
                }else{
                    $sqlString .= "NULL, ";
                }

                if ($logistic_approved_on != ''){
                    $sqlString .= "'$logistic_approved_on',";
                }else{
                    $sqlString .= "NULL, ";
                }

                if ($finance_approved_by != ''){
                    $sqlString .= "'$finance_approved_by',";
                }else{
                    $sqlString .= "NULL, ";
                }

                if ($finance_approved_on != ''){
                    $sqlString .= "'$finance_approved_on',";
                }else{
                    $sqlString .= "NULL, ";
                }

                if ($finance_payment_paid_by != ''){
                    $sqlString .= "'$finance_payment_paid_by',";
                }else{
                    $sqlString .= "NULL, ";
                }

                if ($finance_payment_paid_on != ''){
                    $sqlString .= "'$finance_payment_paid_on',";
                }else{
                    $sqlString .= "NULL, ";
                }

                if ($payment_document != ''){
                    $sqlString .= "'$payment_document',";
                }else{
                    $sqlString .= "NULL, ";
                }

                if ($packed_by != ''){
                    $sqlString .= "'$packed_by',";
                }else{
                    $sqlString .= "NULL, ";
                }

                if ($packed_on != ''){
                    $sqlString .= "'$packed_on',";
                }else{
                    $sqlString .= "NULL, ";
                }

                if ($assigned_to_by != ''){
                    $sqlString .= "'$assigned_to_by',";
                }else{
                    $sqlString .= "NULL, ";
                }

                if ($assigned_to_on != ''){
                    $sqlString .= "'$assigned_to_on',";
                }else{
                    $sqlString .= "NULL, ";
                }

                if ($assigned_to != ''){
                    $sqlString .= "'$assigned_to',";
                }else{
                    $sqlString .= "NULL, ";
                }

                if ($tracking_no != ''){
                    $sqlString .= "'$tracking_no',";
                }else{
                    $sqlString .= "NULL, ";
                }

                if ($delivered_to_logistic_by != ''){
                    $sqlString .= "'$delivered_to_logistic_by',";
                }else{
                    $sqlString .= "NULL, ";
                }

                if ($delivered_to_logistic_on != ''){
                    $sqlString .= "'$delivered_to_logistic_on',";
                }else{
                    $sqlString .= "NULL, ";
                }

                if ($name != ''){
                    $sqlString .= "'$name',";
                }else{
                    $sqlString .= "NULL, ";
                }

                if ($ic_no != ''){
                    $sqlString .= "'$ic_no',";
                }else{
                    $sqlString .= "NULL, ";
                }

                if ($signature != ''){
                    $sqlString .= "'$signature',";
                }else{
                    $sqlString .= "NULL, ";
                }

                if ($courier != ''){
                    $sqlString .= "'$courier',";
                }else{
                    $sqlString .= "NULL, ";
                }

                if ($cancelled_by != ''){
                    $sqlString .= "'$cancelled_by',";
                }else{
                    $sqlString .= "NULL, ";
                }

                if ($cancelled_on != ''){
                    $sqlString .= "'$cancelled_on',";
                }else{
                    $sqlString .= "NULL, ";
                }

                if ($cancel_reason != ''){
                    $sqlString .= "'$cancel_reason',";
                }else{
                    $sqlString .= "NULL, ";
                }

                if ($approve_cancellation != ''){
                    $sqlString .= "'$approve_cancellation',";
                }else{
                    $sqlString .= "NULL, ";
                }

                if ($cancel_request_reason != ''){
                    $sqlString .= "'$cancel_request_reason',";
                }else{
                    $sqlString .= "NULL, ";
                }

                if ($request_by != ''){
                    $sqlString .= "'$request_by',";
                }else{
                    $sqlString .= "NULL, ";
                }

                if ($request_reject_reason != ''){
                    $sqlString .= "'$request_reject_reason',";
                }else{
                    $sqlString .= "NULL, ";
                }

                if ($remarks != ''){
                    $sqlString .= "'$remarks',";
                }else{
                    //$sqlString .= "NULL, ";
                    $sqlString .= "'', ";
                }

                if ($preferred_collection_date != ''){
                    $sqlString .= "'$preferred_collection_date',";
                }else{
                    $sqlString .= "NULL, ";
                }

                if ($preferredtime != ''){
                    $sqlString .= "'$preferredtime',";
                }else{
                    //$sqlString .= "NULL, ";
                    $sqlString .= "'', ";
                }

                if ($slot != ''){
                    $sqlString .= "'$slot',";
                }else{
                    //$sqlString .= "NULL, ";
                    $sqlString .= "'', ";
                }

                if ($checked_by != ''){
                    $sqlString .= "'$checked_by',";
                }else{
                    $sqlString .= "NULL, ";
                }

                if ($remarks_delivery != ''){
                    $sqlString .= "'$remarks_delivery',";
                }else{
                    $sqlString .= "NULL, ";
                }

                if ($finance_approved != ''){
                    $sqlString .= "'$finance_approved')";
                }else{
                    $sqlString .= "NULL)";
                }


                /*
                $sql3 = "INSERT INTO `order` (order_no, centre_code, company_name, user_name, product_code, qty, unit_price, total, ordered_by, ordered_on, acknowledged_by, acknowledged_on, logistic_approved_by, logistic_approved_on,
                finance_approved_by, finance_approved_on, finance_payment_paid_by, finance_payment_paid_on, payment_document, packed_by, packed_on, assigned_to_by, assigned_to_on, assigned_to, tracking_no, delivered_to_logistic_by,
                delivered_to_logistic_on, name, ic_no, signature, courier, cancelled_by, cancelled_on, cancel_reason, approve_cancellation, cancel_request_reason, request_by, request_reject_reason, remarks, preferred_collection_date,
                preferredtime, slot, checked_by, remarks_delivery, finance_approved) 
                VALUES ('$order_no', '$centre_code', '$company_name', '$user_name', '$product_code', '$qty', '$unit_price', '$total', '$ordered_by', '$ordered_on', '$acknowledged_by', '$acknowledged_on', '$logistic_approved_by', '$logistic_approved_on',
                '$finance_approved_by', '$finance_approved_on', '$finance_payment_paid_by', '$finance_payment_paid_on', '$payment_document', '$packed_by', '$packed_on', '$assigned_to_by', '$assigned_to_on', '$assigned_to', '$tracking_no', '$delivered_to_logistic_by',
                '$delivered_to_logistic_on', '$name', '$ic_no', '$signature', '$courier', '$cancelled_by', '$cancelled_on', '$cancel_reason', '$approve_cancellation', '$cancel_request_reason', '$request_by', '$request_reject_reason', '$remarks', '$preferred_collection_date',
                '$preferredtime', '$slot', '$checked_by', '$remarks_delivery', '$finance_approved')";
*/
                $resultFinal=mysqli_query($connection, $sqlString);
            }
        }

        //Increase counter by 1
        $x++;
    }

    
}
?>