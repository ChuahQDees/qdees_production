<?php
session_start();
include_once("../mysql.php");
include_once("functions.php");
include_once("../search_new.php");
$year = $_SESSION['Year'];
foreach ($_POST as $key => $value) {
   $$key = mysqli_real_escape_string($connection, $value); //$centre_code, $df, $dt, $student_id
}

$o_df = $df;
$o_dt = $dt;

$df = convertDate2ISO($df);
$dt = convertDate2ISO($dt);

if ($collection_type == "ALL") {
   $collection_type = "";
}
function getStrTerm($term) {
   switch ($term) {
     case 1 : return "Term 1"; break;
     case 2 : return "Term 2"; break;
     case 3 : return "Term 3"; break;
     case 4 : return "Term 4"; break;
     
  }
 
}
function getStrHalfYearly($HalfYearly) {
   switch ($HalfYearly) {
     case 1 : return "First Half"; break;
     case 2 : return "Second Half"; break;
     
  }
 
}
function getCollectionType($collection_type)
{
   switch ($collection_type) {
      case "tuition":
         return "Tuition Fee";
         break;
      case "placement":
         return "Placement Fee";
         break;
      case "registration":
         return "Registration Fee";
         break;
      case "deposit":
         return "Deposit";
         break;
      case "product":
         return "Product";
         break;
   }
}

function getStudentCodeFromAllocationID($allocation_id, &$student_name)
{
   global $connection;

   $sql = "SELECT * from allocation where id='$allocation_id'";
   $result = mysqli_query($connection, $sql);
   $row = mysqli_fetch_assoc($result);

   $student_id = $row["student_id"];

   $sql = "SELECT * from student where id='$student_id'";
   $result = mysqli_query($connection, $sql);
   $row = mysqli_fetch_assoc($result);

   $student_name = $row["name"];
   return $row["student_code"];
}

function getCentreName($centre_code)
{
   global $connection;

   $sql = "SELECT * from centre where centre_code='$centre_code'";
   $result = mysqli_query($connection, $sql);
   $row = mysqli_fetch_assoc($result);
   $num_row = mysqli_num_rows($result);

   if ($num_row > 0) {
      return $row["company_name"];
   } else {
      if ($centre_code == "ALL") {
         return "All Centres";
      } else {
         return "";
      }
   }
}

function getStudentName($student, $centre_code)
{
   global $connection;
   $student_sql = "SELECT name from student where centre_code='$centre_code' and name like '%$student%' or student_code like '%$student%'";
   $result = mysqli_query($connection, $student_sql);
   $row = mysqli_fetch_assoc($result);

   return $row['name'];
}

if (true) {

    $filename = 'student_collection_detail_'.date('Y-m-d').'.csv';

    header("Content-type: text/csv");
    header("Content-Disposition: attachment; filename=$filename");
    header("Pragma: no-cache");
    header("Expires: 0");

    $content = array();

   $base_sql = "SELECT c.*, s.name, pd.payment_method,  pd.transaction_date, pd.cheque_no, pd.ref_no from student s ,collection c LEFT JOIN payment_detail pd on pd.id=c.payment_detail_id
      where c.student_code=s.student_code and c.collection_date_time>='$df 00:00:00' and collection_date_time<'$dt 23:59:59' 
      and c.void=0 ";

   if ($centre_code != "ALL") {
      $base_sql .= " and c.centre_code='$centre_code'";
   }

   if ($filter_by != "") {
      $base_sql .= " and c.collection_type='$filter_by'";
   }

   $payment_token = ConstructToken("pd.payment_method", "%$collection_type%", "like");
 
   $student_token = ConstructTokenGroup("s.name", "%$student%", "like", "s.student_code", "%$student%", "like", "or");
   $order_token = ConstructOrderToken("`c`.`batch_no`", "DESC");
   $final_sql = ConcatWhere($base_sql, $payment_token);
   $final_sql = ConcatWhere($final_sql, $collection_token);
   $final_sql = ConcatWhere($final_sql, $student_token);
   $final_sql = ConcatOrder($final_sql, $order_token);

   $result = mysqli_query($connection, $final_sql);
   $num_row = mysqli_num_rows($result);
   $result_array = [];
   if ($num_row > 0) {
      while ($row = mysqli_fetch_assoc($result)) {
         $result_array[$row['student_code']][$row['batch_no']][] = $row;
      }
   }

        $content[] = array("","Centre Name : ",getCentreName($centre_code),"","","","","","","","");

        $content[] = array("","Prepare By : ",$_SESSION["UserName"],"","","","","","","","");

        $content[] = array("","Academic Year : ",(!empty($selected_month)) ? str_split($selected_month, 4)[0] : $year,"","","","","","","","");

        $content[] = array("","Date of submission : ",date("Y-m-d H:i:s"),"","","","","","","","");

        $content[] = array("","","","","","","","","","","");

        $row = array();

        $row[] = "No";
        $row[] = "Receipt No.";
        $row[] = "Student Name";
        $row[] = "Student Code";
        $row[] = "Collection Type";
        $row[] = "Collection Month";
        $row[] = "Bank In Date";
        $row[] = "Received Date";
        $row[] = "Payment Type";
        $row[] = "Ref No/Cheque No";
        $row[] = "Total Amount";

        $content[] = $row;

         if ($num_row > 0) {
            $count = 0;
            $grand_total = 0;

            foreach ($result_array as $key => $value) {
                $student_total = 0;
                foreach ($value as $key2 => $value2) {
                    $receipt_total = 0;
                    for ($i = 0; $i < count($value2); $i++) {
                        $grand_total += $value2[$i]["amount"];
                        $student_total += $value2[$i]["amount"];
                        $receipt_total += $value2[$i]["amount"];

                        $row = array();
       
                        if ($i == 0) {
                            $count++;

                            $row[] = $count;
                            $row[] = $value2[$i]["batch_no"];
                            $row[] = $value2[$i]["name"];
                            $row[] = $value2[$i]["student_code"];
                        } else {
                            $row[] = '';
                            $row[] = '';
                            $row[] = '';    
                            $row[] = '';
                        }
                            
                        switch ($value2[$i]["collection_type"]) {
                            case "product":
                                $row[] = $value2[$i]["product_name"] ? "Product Sale - " . $value2[$i]["product_name"] : "Product Sale";
                                break;
                            case "addon-product":
                                $row[] = $value2[$i]["product_name"] ? "Addon Product - " . $value2[$i]["product_name"] : "Addon Product"; 
                                break;
                            default:
                                $row[] = $value2[$i]["product_code"];
                        }
                        if ($value2[$i]["collection_month"] != "") {
                            if ($value2[$i]["collection_pattern"] == "Monthly") {
                                $row[] = date('F', mktime(0, 0, 0, $value2[$i]["collection_month"], 10));
                            } else if ($value2[$i]["collection_pattern"] == "Termly") {
                                $row[] = getStrTerm($value2[$i]["collection_month"]);
                            }else if ($value2[$i]["collection_pattern"] == "Half Year") {
                                $row[] = getStrHalfYearly($value2[$i]["collection_month"]);
                            }else if ($value2[$i]["collection_pattern"] == "Annually") {
                                $row[] = "Annually";
                            }
                        } else {
                            $row[] = "NA";
                        }

                        $row[] = $value2[$i]["collection_date_time"];
                        
                        $row[] = $value2[$i]["transaction_date"];
                        
                        if ($value2[$i]["payment_method"] == 'BT') {
                            $row[] = "BANK TRANSFER";
                        } elseif ($value2[$i]["payment_method"] == 'CC') {
                            $row[] = "CREDIT CARD";
                        } else { 
                            $row[] = $value2[$i]["payment_method"];
                        } 
                        
                        $row[] = ($value2[$i]["payment_method"] == 'CASH' || $value2[$i]["payment_method"] == 'CC' || $value2[$i]["payment_method"] == 'BT' ? $value2[$i]["ref_no"] : $value2[$i]["cheque_no"]); 
                        
                        $row[] = $value2[$i]["amount"];

                        $content[] = $row;
                    }
                    $row = array();
            
                    $row[] = "";
                    $row[] = "";
                    $row[] = "";
                    $row[] = "";
                    $row[] = "";
                    $row[] = "";
                    $row[] = "";
                    $row[] = "";
                    $row[] = "";
                    $row[] = "Receipt Total :";
                    $row[] = number_format($receipt_total, 2);

                    $content[] = $row;
                }
                
            }
            $row = array();
            
            $row[] = "";
            $row[] = "";
            $row[] = "";
            $row[] = "";
            $row[] = "";
            $row[] = "";
            $row[] = "";
            $row[] = "";
            $row[] = "";
            $row[] = "Grand Total :";
            $row[] = number_format($grand_total, 2);

            $content[] = $row;
        }

    $output = fopen('php://output', 'w');
    fputcsv($output, $title);
    foreach ($content as $con) {
        fputcsv($output, $con);
    }
    fclose($output);

} else {
   echo "Please enter a Centre, From Date and To Date";
}
?>
