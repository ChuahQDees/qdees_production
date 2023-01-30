<?php
function splitCourse($course_name, &$programme, &$level, &$module) {
   $array=explode("-", $course_name);
   $course_name=trim($array[0]);

   $first2_char=substr($course_name, 0, 2);
   $first4_char=substr($course_name, 0, 4);
   $M_pos=strpos($course_name, "M");

   if ($first2_char=="IE") {
      $programme=$first2_char;
      $level=substr($course_name, 2, $M_pos-2);
      $module=substr($course_name, $M_pos, strlen($course_name)-$M_pos);
   }

   if ($first2_char=="BI") {
      $programme=$first4_char;
      $level=substr($course_name, 4, $M_pos-4);
      $module=substr($course_name, $M_pos, strlen($course_name)-$M_pos);
   }
   if ($first2_char=="IQ") {
      $programme=$first4_char;
      $level=substr($course_name, 4, $M_pos-4);
      $module=substr($course_name, $M_pos, strlen($course_name)-$M_pos);
   }
}

function getQtyInBasket($session_id, $student_code, $product_code) {
   global $connection;

   $sql="SELECT sum(qty) as qty FROM `tmp_busket` where session_id='$session_id' and student_code='$student_code' and product_code='$product_code'";
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);

   if ($row["qty"]!="") {
      return $row["qty"];
   } else {
      return 0;
   }
}

function writeLog($log) {
   global $connection;

   $sql=mysqli_real_escape_string($connection, $log);
   $isql="INSERT into log (`log`) values ('$sql')";
   $result=mysqli_query($connection, $isql);
}

function getIncomingQty($centre_code, $product_code, $cut_off_date) {
   global $connection;

   if ($centre_code == '') {
      $sql="SELECT sum(qty) as qty from `order` where product_code='$product_code' and delivered_to_logistic_on<='$cut_off_date 23:59:59'
      and delivered_to_logistic_by<>'' and (cancelled_by='' or cancelled_by IS NULL)";
   } else {
      $sql="SELECT sum(qty) as qty from `order` where product_code='$product_code' and delivered_to_logistic_on<='$cut_off_date 23:59:59'
      and delivered_to_logistic_by<>'' and (cancelled_by='' or cancelled_by IS NULL) and centre_code='$centre_code'";
   }
//   echo $sql;
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);
   if ($row["qty"]=="") {
      return 0;
   } else {
      return $row["qty"];
   }
}

function getOutgoingQty($centre_code, $product_code, $cut_off_date) {
   global $connection;

   if ($centre_code == '') {
      $sql="SELECT sum(qty) as qty from `collection` where product_code='$product_code' and collection_date_time<='$cut_off_date 23:59:59'
      and void=0";
   } else {
      $sql="SELECT sum(qty) as qty from `collection` where product_code='$product_code' and collection_date_time<='$cut_off_date 23:59:59'
      and centre_code='$centre_code' and void=0";      
   }
//   echo $sql;
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);
   if ($row["qty"]=="") {
      return 0;
   } else {
      return $row["qty"];
   }
}

function getStockAdjustment($centre_code, $product_code, $cut_off_date) {
   global $connection;

   if ($centre_code == '') {
      $sql="SELECT sum(adjust_qty) as total_qty from stock_adjustment where product_code='$product_code'
      and effective_date<='$cut_off_date'";
   } else {
      $sql="SELECT sum(adjust_qty) as total_qty from stock_adjustment where product_code='$product_code'
      and centre_code='$centre_code' and effective_date<='$cut_off_date'";
   }

   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);
   return $row["total_qty"];
}

function calcBal($centre_code, $product_code, $cut_off_date) {
   global $connection;

   return getIncomingQty($centre_code, $product_code, $cut_off_date)-
   getOutgoingQty($centre_code, $product_code, $cut_off_date)+
   getStockAdjustment($centre_code, $product_code, $cut_off_date);
}

function deleteAllTransaction($product_code) {
   global $connection, $session_id;

   if ($session_id!="") {
      $sql="DELETE from tmp_stock where session_id='$session_id'";
      $result=mysqli_query($connection, $sql);

      if ($result) {
         return true;
      } else {
         return false;
      }
   }
}

function hasRightGroupXOR($user_name, $group_right) {
   $right_array=explode("|", $group_right);

   $final_right=false;
   foreach ($right_array as $right) {
      $final_right=(($final_right) || (hasRight($user_name, $right)));
   }

   return $final_right;
}

function hasRight($user_name, $right) {
   global $connection;

   $sql="SELECT * from user_right where user_name='$user_name' and `right`='$right'";
//   echo $sql."<br>";
   $result=mysqli_query($connection, $sql);
   $num_row=mysqli_num_rows($result);
   if ($num_row>0) {
      return true;
   } else {
      return false;
   }
}

function echoCentreAttachment($centre_code) {
   global $connection;

   $sql="SELECT * from centre_agreement_file where centre_code='".$centre_code."'";
   $result=mysqli_query($connection, $sql);
   $num_row=mysqli_num_rows($result);

   if ($num_row>0) {
      $str="1|";
      while ($row=mysqli_fetch_assoc($result)) {
         $str.="<tr class=\"uk-width-1-1 uk-text-small\">";
         $str.="   <td class=\"uk-width-4-10\">".$row["attachment"]."</td>";
         $str.="   <td class=\"uk-width-4-10\">".$row["doc_type"]."</td>";
         $str.="   <td class=\"uk-width-2-10\">";
         $str.="      <a target=\"_blank\" href=\"admin/uploads/".$row["attachment"]."\"><img data-uk-tooltip=\"{pos:top}\" title=\"View PDF File\" src=\"images/pdf.png\"></a>";
         $str.="      <a onclick=\"deleteAttachment('".$row["id"]."')\"><img data-uk-tooltip=\"{pos:top}\" title=\"Delete PDF File\" src=\"images/delete.png\"></a>";
         $str.="   </td>";
         $str.="</tr>";
      }
   } else {
      $str="1|";
      $str.="<tr class=\"uk-width-1-1\">";
      $str.="   <td colspan=\"3\" class=\"uk-width-1-1 uk-text-small\">No record found</td>";
      $str.="</tr>";
   }

   echo $str;
}

function echoAttachment($master_code) {
   global $connection;

   $sql="SELECT * from master_agreement_file where master_code='".$master_code."'";
   $result=mysqli_query($connection, $sql);
   $num_row=mysqli_num_rows($result);

   if ($num_row>0) {
      $str="1|";
      while ($row=mysqli_fetch_assoc($result)) {
         $str.="<tr class=\"uk-width-1-1 uk-text-small\">";
         $str.="   <td class=\"uk-width-4-10\">".$row["attachment"]."</td>";
		 $str.="   <td class=\"uk-width-4-10\">".$row["doc_type"]."</td>";
         $str.="   <td class=\"uk-width-2-10\">";
         $str.="      <a target=\"_blank\" href=\"admin/uploads/".$row["attachment"]."\"><img data-uk-tooltip=\"{pos:top}\" title=\"View PDF File\" src=\"images/pdf.png\"></a>";
         $str.="      <a onclick=\"deleteAttachment('".$row["id"]."')\"><img data-uk-tooltip=\"{pos:top}\" title=\"Delete PDF File\" src=\"images/delete.png\"></a>";
         $str.="   </td>";
         $str.="</tr>";
      }
   } else {
      $str="1|";
      $str.="<tr class=\"uk-width-1-1\">";
      $str.="   <td colspan=\"2\" class=\"uk-width-1-1 uk-text-small\">No record found</td>";
      $str.="</tr>";
   }

   echo $str;
}

function convertDate2British($date) {
   if ($date!="") {
     if (strpos($date, '-') !== false) {
       list($year, $month, $day)=explode("-", $date);
       return "$day/$month/$year";
     } else if (strpos($date, '/') !== false){
       return $date;
     }
   } else {
      return "";
   }
}

function convertDate2ISO($date) {
   if ($date!="") {
     if (strpos($date, '/') !== false) {
       list($day, $month, $year)=explode("/", $date);

       return "$year-$month-$day";
     } else if (strpos($date, '-') !== false){
       return $date;
     }

   } else {
      return "";
   }
}

function getCountry($master_centre_code) {
   global $connection;

   $sql="SELECT * from master where master_code='$master_centre_code'";
   $result=mysqli_query($connection, $sql);
   $num_row=mysqli_num_rows($result);
   if ($num_row>0) {
      $row=mysqli_fetch_assoc($result);
      return $row["country"];
   } else {
      $sql="SELECT * from centre where centre_code='$master_centre_code'";
      $result=mysqli_query($connection, $sql);
      $num_row=mysqli_num_rows($result);
      if ($num_row>0) {
         $row=mysqli_fetch_assoc($result);
         return $row["country"];
      } else {
         return "None";
      }
   }
}

function appendZero($num) {
   $str_num=strval($num);

   switch (strlen($str_num)) {
      case 1 : return "000".$str_num; break;
      case 2 : return "00".$str_num; break;
      case 3 : return "0".$str_num; break;
      case 4 : return $str_num; break;
   }
}

function getNextMasterCode($country_code, $company, $master) {
   global $connection;

   $prefix=$country_code.$company.$master;

   $sql="SELECT max(master_code) as master_code from master where master_code like '$prefix%'";
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);
   $num_row=mysqli_num_rows($result);

   if ($num_row>0) {
      return appendZero(intval(substr($row["master_code"], -4))+1);
   } else {
      return "0001";
   }
}

function getNextCentreCode($prefix) {
   global $connection;

   $sql="SELECT max(centre_code) as centre_code from centre where centre_code like '$prefix%'";
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);
   $num_row=mysqli_num_rows($result);

   if ($num_row>0) {
      return appendZero(intval(substr($row["centre_code"], -4))+1);
   } else {
      return "0001";
   }
}

function getNextStudentCode($centre_code) {
   global $connection;

   $sql="SELECT max(student_code) as student_code from student_emergency_contacts where student_code like '$centre_code%'";
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);
   $num_row=mysqli_num_rows($result);

   if ($num_row>0) {
      //echo intval(substr($row["student_code"], -4)); die;
      $student_code = appendZero(intval(substr($row["student_code"], -4))+1);
      $student_code_ = $centre_code . '-' . $student_code; 
      $sql1="SELECT * from student_code_draft where student_code = '$student_code_'";
      $result1=mysqli_query($connection, $sql1);
      //$row1=mysqli_fetch_assoc($result1);
      $num_row1=mysqli_num_rows($result1);
      if ($num_row1>0) {
         $sql="SELECT max(student_code) as student_code from student_code_draft where student_code like '$centre_code%'";
         $result=mysqli_query($connection, $sql);
         $row=mysqli_fetch_assoc($result);
         $num_row=mysqli_num_rows($result);
         
         $student_code_suff = appendZero(intval(substr($row["student_code"], -4))+1);
         $student_code = $centre_code . '-' . $student_code_suff; 
         $sql="INSERT into student_code_draft (`centre_code`, `student_code`)VALUES ('$centre_code','$student_code')";
         $result=mysqli_query($connection, $sql);
         return $student_code_suff;
      }else{
         $student_code = $centre_code . '-' . $student_code; 
         $sql="INSERT into student_code_draft (`centre_code`, `student_code`)VALUES ('$centre_code','$student_code')";
         $result=mysqli_query($connection, $sql);
         return appendZero(intval(substr($row["student_code"], -4))+1);
      }
   } else {
      return "0001";
   }
}

function getCountryUseCode($country) {
   global $connection;

   $sql="SELECT use_code from codes where module='COUNTRY' and code='$country'";
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);

   return $row["use_code"];
}

function getTerritoryUseCode($territory) {
   global $connection;

   $sql="SELECT use_code from codes where module='STATE' and code='$territory'";
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);
   //echo $sql;
   return $row["use_code"];
}

function getStudentCode($centre_code) {
   global $connection;

   return $centre_code."-".getNextStudentCode($centre_code);
}

function getCentreCompanyName($Centre_Code) {
   global $connection;

   $sql="SELECT company_name from `centre` where centre_code='$Centre_Code'";
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);
   return $row["company_name"];
}

function getCentreCode($master_code) {
   $middle="C1";
   if ($master_code!="") {
      $suffix=substr($master_code, 0, strlen($master_code)-4);
      $next_centre_code=getNextCentreCode($suffix);
      return $suffix.$middle.$next_centre_code;
   } else {
      return "Master Code is required";
   }
}

function getMasterCode($country, $master_type) {
   global $connection;
   $company="QWE";

   switch ($master_type) {
      case "Region" : $master="R1"; break;
      case "Country" : $master="C1"; break;
      case "Master/Territory" : $master="M1"; break;
      case "HQ" : $master="Q1"; break;
   }

   if ($master_type!="HQ") {
      $country_code=getCountryUseCode($country);
   } else {
      $country_code="AL";
   }

   if ($country_code=="") {
      $country_code="#";
   }

   if ($country_code!="#") {
      $next_master_code=getNextMasterCode($country_code, $company, $master);
      return $country_code.$company.$master.$next_master_code;
   } else {
      $next_master_code=getNextMasterCode($country_code, $company, $master);
      return $country_code.$company.$master.$next_master_code;
   }
}

function generateRandomNumber($length) {
    $characters = '0123456789';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function generateRandomString($length) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function generateSelectA($sql, $key_field, $value_field, $control_name, $class, $selected_value, $empty_display) {
   global $connection;
   $result=mysqli_query($connection, $sql);

   echo "<select name='$control_name' id='$control_name' $class>\n";
   echo "   <option value=''>$empty_display</option>\n";
   while ($row=mysqli_fetch_assoc($result)) {
      if ($row[$key_field]==$selected_value) {
         $selected="selected";
      } else {
         $selected="";
      }

      echo "   <option value='".$row[$key_field]."' $selected>".$row[$value_field]."</option>\n";
   }
   echo "</select>\n";
}

function generateSelect($sql, $key_field, $value_field, $control_name, $class, $selected_value) {
   global $connection;
   $result=mysqli_query($connection, $sql);

   echo "<select name='$control_name' id='$control_name' $class>\n";
   echo "   <option value=''>Select</option>\n";
   while ($row=mysqli_fetch_assoc($result)) {
      if ($row[$key_field]==$selected_value) {
         $selected="selected";
      } else {
         $selected="";
      }

      echo "   <option value='".$row[$key_field]."' $selected>".$row[$value_field]."</option>\n";
   }
   echo "</select>\n";
}

function generateSelectArray($fields, $control_name, $class, $selected_value) {
   echo "<select name='$control_name' id='$control_name' $class>\n";
   echo "   <option value=''>Status</option>\n";

   foreach ($fields as $key=>$value) {
      echo "$value\n";
      if ($key==$selected_value) {
         $selected="selected\n";
      } else {
         $selected="";
      }

      echo "   <option value='".$key."' $selected>".$value."</option>\n";
   }
   echo "</select>\n";
}
function generateSelectArrayDay($fields, $control_name, $class, $selected_value) {
   echo "<select name='$control_name' id='$control_name' $class>\n";
   echo "   <option value=''>Select Day</option>\n";

   foreach ($fields as $key=>$value) {
      echo "$value\n";
      if ($key==$selected_value) {
         $selected="selected\n";
      } else {
         $selected="";
      }

      echo "   <option value='".$key."' $selected>".$value."</option>\n";
   }
   echo "</select>\n";
}
function generateSelectArrayActive($fields, $control_name, $class, $selected_value) {
   echo "<select name='$control_name' id='$control_name' $class>\n";
   echo "   <option value=''>Select Status</option>\n";

   foreach ($fields as $key=>$value) {
      echo "$value\n";
      if ($key==$selected_value) {
         $selected="selected\n";
      } else {
         $selected="";
      }

      echo "   <option value='".$key."' $selected>".$value."</option>\n";
   }
   echo "</select>\n";
}
 
function getNextYear() {
   global $connection;

   $next_year = mysqli_query($connection,"SELECT `year` FROM `schedule_term` WHERE `year` > '".$_SESSION['Year']."' AND `centre_code` = '".$_SESSION['CentreCode']."' ORDER BY `year` asc LIMIT 1");

   if(mysqli_num_rows($next_year) > 0)
   {
      $next_year_data = mysqli_fetch_array($next_year);
      return isset($next_year_data['year']) ? $next_year_data['year'] : '';
   }
   else
   {
      return '';
   }   
}

function getMonthList($start_date, $end_date)
{
   $start    = (new DateTime($start_date))->modify('first day of this month');
   $end      = (new DateTime($end_date))->modify('first day of next month');
   $interval = DateInterval::createFromDateString('1 month');
   $period   = new DatePeriod($start, $interval, $end);

   return $period;
}

function getYearFromMonth($year, $month)
{
   global $connection;

   $year_data = mysqli_fetch_array(mysqli_query($connection,"SELECT `year` FROM `schedule_term` WHERE ('".date('Y-m-d',strtotime($year.'-'.$month))."' BETWEEN `term_start` AND `term_end`) AND `centre_code` = '".$_SESSION['CentreCode']."' AND `deleted` = 0"));

   return $year_data['year'];
}

function getTermFromDate($date, $centre_code = '')
{
   global $connection;

   if($centre_code == '')
   {
      $centre_code = $_SESSION['CentreCode'];
   }
   $term_num = mysqli_fetch_array(mysqli_query($connection,"SELECT `term_num` FROM `schedule_term` WHERE ('".$date."' BETWEEN `term_start` AND `term_end`) AND `centre_code` = '".$centre_code."' AND `deleted` = 0"));

   return $term_num['term_num'];
}

function getYearOptionList()
{
   global $connection;

   $year_list = mysqli_query($connection,"SELECT `year` FROM `schedule_term` WHERE `year` >= '2019' AND `centre_code` = '".$_SESSION['CentreCode']."' AND `deleted` = '0' GROUP BY `year` ORDER BY `year`");

   $option = '';

   while($year_row = mysqli_fetch_array($year_list))
   {
      $option .= '<option value="'.$year_row["year"].'" >'.$year_row["year"].'</option>';
   }

   return $option;
}

function getHalfYearDate($year, $centre_code = '')
{
   global $connection;

   if($centre_code == '')
   {
      $centre_code = $_SESSION['CentreCode'];
   }

   $halfDate = mysqli_fetch_array(mysqli_query($connection,"SELECT `term_start` FROM `schedule_term` WHERE `year` >= '".$year."' AND `centre_code` = '".$centre_code."' AND `deleted` = '0' AND `term_num` = '3'"));

   return $halfDate['term_start'];
}

?>
