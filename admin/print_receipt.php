<?php
session_start();
include_once("../mysql.php");
include_once("../uikit1.php");
/* error_reporting(E_ALL);
ini_set('display_errors', 1); */
foreach ($_GET as $key=>$value) {
   $$key=mysqli_real_escape_string($connection, $value);
}

function getStudentInfoByStudentCode($student_code, &$student_name, &$classes, $year) {
   global $connection;

  $sql="SELECT * from student where student_code='$student_code'";
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);
   $student_name = $row["name"];
   //$sql="SELECT c.*, a.class_id as group_no from allocation a, course c where a.course_id=c.id and student_id='".$row["id"]."' and a.deleted=0 and c.deleted=0";
   $sql="SELECT c.*, a.class_id as group_no from allocation a, course c where a.course_id=c.id and student_id='".$row["id"]."' and a.deleted=0 and a.year = '".$year."' GROUP BY c.id";
   $result=mysqli_query($connection, $sql);
   $course_group = [];
   while($row=mysqli_fetch_assoc($result)){
      
      $course_name = isset($row["subject"]) ? $row["subject"] : '';
      $group = isset($row["group_no"]) ? $row["group_no"] : '';

      if($group != '' && $group != '0')
      {
         $course_group[] = $course_name.' ('.$group.')';
      }
      /* else 
      {
         $course_group[] = $course_name;
      } */
   }
   $classes = implode(', ', $course_group);
}

function getProductNameByProductCode($product_code) {
   global $connection;

   $sql="SELECT product_name from product where product_code='$product_code'";
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);

   return $row["product_name"];
}

function getAOCentreFranciseeCompanyNOByCenterCode($centre_code) {
   global $connection;

   $sql="SELECT franchisee_company_no from centre_franchisee_company where centre_code='$centre_code'";
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);

   return $row["franchisee_company_no"];
}

function getAOProductNameByProductCode($product_code, $centre_code) {
   global $connection;
   //$CentreCode = $_SESSION["CentreCode"];
   $sql="SELECT product_name from addon_product where product_code='$product_code' and centre_code='$centre_code'";
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);

   return $row["product_name"];
}

function getCourseNameByAllocationID($allocation_id) {
   global $connection;

   $sql="SELECT * from allocation where id='$allocation_id'";
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);
   $course_id=$row["course_id"];

   $sql="SELECT course_name from course where id='$course_id'";
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);

   return $row["course_name"];
}

function getCentreInfo($centre_code, &$centre_name, &$company_name, &$address1, &$address2, &$address3, &$address4, &$address5, &$principle_name, &$principle_contact_no,&$bank_detail) {
   global $connection;

   $sql="SELECT * from centre c inner join centre_franchisee_company cf on c.centre_code=cf.centre_code  where c.centre_code='$centre_code'";
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);
   $centre_name=$row["company_name"];
   $company_name=$row["franchisee_company_name"];
   $centre_code=$row["centre_code"];
   $address1=$row["address1"];
   $address2=$row["address2"];
   $address3=$row["address3"];
   $address4=$row["address4"];
   $address5=$row["address5"];
   $principle_name=$row["principle_name"];
   $principle_contact_no=$row["principle_contact_no"];
   $bank_detail=$row["bank_detail"];
}

function getCollectionInfo($batch_no, &$collection_date, &$centre_code, &$plain_batch_no, &$ref_no, &$cheque_no, &$payment_method, &$student_code, &$bank, &$remark, &$year ) {
   global $connection;

   $sql="SELECT * from collection where sha1(batch_no)='$batch_no'";
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);
   $collection_date=date("d/m/Y", strtotime($row["collection_date_time"]));
   $centre_code=$row["centre_code"];
   $plain_batch_no=$row["batch_no"];
   $payment_detail_id=$row["payment_detail_id"];
   $student_code=$row["student_code"];
   $year = $row["year"];
   $sql="SELECT * from payment_detail where id='$payment_detail_id'";
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);
   $ref_no = $row["ref_no"];
   $cheque_no = $row["cheque_no"];
   $bank = $row["bank"];
   $remark = $row["remark"];
   switch ($row["payment_method"]) {
    case "CC" : $payment_method="Credit Card"; break;
    case "CASH" : $payment_method="Cash"; break;
    case "BT" : $payment_method="Bank Transfer"; break;
    case "CHEQUE" : $payment_method="Cheque"; break;
    default : $payment_method=$row["payment_method"]; break;
   }
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

if ($batch_no!="") {
   $year = '';
   getCollectionInfo($batch_no, $collection_date, $centre_code, $plain_batch_no, $ref_no, $cheque_no, $payment_method, $student_code, $bank, $remark, $year);
   getCentreInfo($centre_code, $centre_name, $company_name, $address1, $address2, $address3, $address4, $address5, $principle_name, $principle_contact_no,$bank_detail);
   getStudentInfoByStudentCode($student_code, $student_name, $classes, $year);
?>
<style>
table {
   width :100%;
   border-collapse: collapse;
}

.td-border {
   border: solid 1px #000000;
}

.td-no-border {
   border: 0px;
}

.hr-border {
   border-width: 1px;
   border-style: inset;
}

.font-30 {
   font-size: 30px;
}

.td-no-right-border {
   border-right:none;
}

.td-no-bottom-border {
   border-bottom:none;
}

.td-no-top-border {
   border-top:none;
}
@media print { 
   #btnPrint{
      display:none;
   }
}
</style>

<div class="uk-margin uk-margin-top uk-margin-left uk-margin-right">
   <table>
      <tr>
        <td class="td-no-border" style="text-align:right;">
        <img src="../images/Q-dees Starters Logo.png" alt="logo" style="width:150px;">
        </td>
      </tr>
   </table>
   <hr class="hr-border">
   <table>
      <!-- <tr>
        <td class="font-30"><center>OFFICIAL RECEIPT</center></td>
      </tr> -->
   </table>
   <table  >
      <tr>
        <td style="width: 50%"><br>
        <?php echo $company_name?>  - <?php echo getAOCentreFranciseeCompanyNOByCenterCode($centre_code)?><br>
          <!-- <?php //echo $company_name?><br> -->
          <!-- <?php echo $centre_code?><br> -->
          <?php echo $address1?><br>
          <?php echo $address2?><br>
          <?php echo $address3?><br>
          <?php echo $address4?><br> 
          <?php echo $address5?><br>
          <!--<b>ATTN :</b> <?php echo $principle_name?><br>
          <b>TEL. :</b> <?php echo $principle_contact_no?><br>
         <b>Student Name :</b> <?php echo $student_name?><br> -->
        

        </td>
        <td style="width: 50%" cellpadding="0">
         <span style="font-weight:bold; font-size: 20px;">OFFICIAL RECEIPT</span><br>
          <?php echo "<span style='margin-right: 125px;' >NO </span>" . ":".$plain_batch_no ?><br>
          <?php echo "<span style='margin-right: 107px;' >DATE </span>" . ":".$collection_date ?><br>
          <?php echo "<span style='margin-right: 0px;' >PAYMENT METHOD </span>" . ":".$payment_method ?><br>
          
          
          <?php if($payment_method=="Cheque" or $payment_method=="Bank Transfer"): ?>
            <?php echo "<span style='margin-right: 106px;' >BANK </span>" . ":". $bank ?><br>
            <?php echo "<span style='margin-right: 86px;' >REF. NO </span>" . ":". $ref_no ?><br>
          <?php if($payment_method=="Cheque"): ?>
            <?php echo "<span style='margin-right: 57px;' >CHEQUE NO</span>" . ":".$cheque_no ?><br>
          <?php endif ?>
          <?php endif ?>
          <?php if($remark!=""): ?>
          <?php echo "<span style='margin-right: 71px;' >REMARKS </span>" . ":".$remark ?><br>
          <?php endif ?>
          <?php echo "<span style='margin-right: 82px;' >PAGE NO</span>" . ": 1" ?><br>
        </td>
      </tr>
   </table>
   <p><b>Received from : </b><?php echo "$student_name - $student_code" ?></p>
   <?php if($classes != '') { ?><b>Class :</b> <?php echo $classes ?></p> <?php } ?>
    <!-- <?php echo "$centre_name - $centre_code"?> -->
   <!-- <p><b>Student Name :</b> <?php echo $student_name ?><br> -->
   <!-- <b>Class :</b> <?php echo $classes ?></p> -->
   <table>
      <tr>
         <td class="uk-text-bold td-border" style="text-align: center;">ITEM</td>
         <td class="uk-text-bold td-border" style="text-align: center;" colspan="2">PARTICULARS</td> 
         <!--<td class="uk-text-bold td-border"style="" ></td>
         <td class="uk-text-bold td-border">DISCOUNT</td> -->
         <td class="uk-text-right uk-text-bold td-border">AMOUNT</td>
      </tr>
</div>
<?php
   $sql="SELECT * from collection where sha1(batch_no)='$batch_no' and `void`=0  order by id asc"; // new
   //$sql="SELECT * from collection where sha1(batch_no)='$batch_no' order by id asc"; // old
   $result=mysqli_query($connection, $sql);
   $num_row=mysqli_num_rows($result);

   if ($num_row>0) {
      $count=0;
      $total_amount=0;
      while ($row=mysqli_fetch_assoc($result)) {
         $count++;
         $total_amount=$total_amount+$row["amount"];
?>
      <tr>
         <td class="td-border" style="text-align: center;"><?php echo $count?></td>
         <td class="td-border" style="padding-left: 20px; padding-left: 20px;
    border-right: 0;">
            <?php
            if($row["void"] == 1){
               echo "<strike>";
            }
             //var_dump($row["collection_type"]);
            switch (strtolower($row["collection_type"])) {
               //case "tuition" : echo getCourseNameByAllocationID($row["allocation_id"]); break;
               case "tuition" : 
                  if($row["product_name"]!=""){
                     echo $row["product_name"]; 
                  }else if(getCourseNameByAllocationID($row["allocation_id"])==""){
                     echo $row["product_code"]; 
                  }else{ 
                     echo getCourseNameByAllocationID($row["allocation_id"]);
                  }
                  break;
               case "registration" : echo $row["subject"] ? "Registration - ".$row["subject"] : "Registration"; break;
               case "insurance" : echo $row["subject"] ? "Insurance (".$row['single_year'].") - ".$row["subject"] : "Insurance (".$row['single_year'].")"; break;
               case "deposit" : echo $row["subject"] ? "Deposit - ".$row["subject"] : "Deposit"; break;
               case "q-dees-level-kit" : echo $row["subject"] ? "Q-dees Level Kit - ".$row["subject"] : "Q-dees Level Kit"; break;
               case "uniform (2 sets)" : echo $row["subject"] ? "Uniform (2 sets) - ".$row["subject"] : "Uniform (2 sets)"; break;
               case "gymwear (1 set)" : echo $row["subject"] ? "Gymwear (1 set) - ".$row["subject"] : "Gymwear (1 set)"; break;
               case "q-dees bag" : echo $row["subject"] ? "Q-dees Bag - ".$row["subject"] : "Q-dees Bag"; break;
               case "placement" : echo $row["subject"] ? "Placement - ".$row["subject"] : "Placement"; break;
               case "math" : echo $row["subject"] ? "IQ Math - ".$row["subject"] : "IQ Math"; break;
               case "robotic-plus" : echo $row["subject"] ? "Robotic Plus - ".$row["subject"] : "Robotic Plus"; break;
               case "mandarin" : echo $row["subject"] ? "Enhanced Foundation Mandarin - ".$row["subject"] : "Enhanced Foundation Mandarin"; break;
               case "enhanced foundation mandarin" : echo $row["subject"] ? "Enhanced Foundation Mandarin - ".$row["subject"] : "Enhanced Foundation Mandarin"; break;
               case "international" : echo $row["subject"] ? "International Art - ".$row["subject"] : "International Art"; break;
               case "foundation" : echo $row["subject"] ? "International English - ".$row["subject"] : "International English"; break;
               case "pendidikan" : echo $row["subject"] ? "Pendidikan Islam - ".$row["subject"] : "Pendidikan Islam"; break;
               case "integrated" : echo $row["subject"] ? "Integrated Module - ".$row["subject"] : "Integrated Module"; break;
               case "link" : echo $row["subject"] ? "Link & Think - ".$row["subject"] : "Link & Think"; break;
               case "mandarinmodules" : echo $row["subject"] ? "Mandarin Modules - ".$row["subject"] : "Mandarin Modules"; break;
               case "uniform" : echo $row["subject"] ? "Uniform - ".$row["subject"] : "Uniform"; break;
               case "gymwear" : echo $row["subject"] ? "Gymwear - ".$row["subject"] : "Gymwear"; break;
               case "qdeesbag" : echo $row["subject"] ? "Q-dees Bag - ".$row["subject"] : "Q-dees Bag"; break;
               //case "product" : echo getProductNameByProductCode($row["product_code"]); break;
               case "product" : 
                  if($row["product_name"]!=""){
                     echo $row["product_name"]; 
                  }else if(getProductNameByProductCode($row["product_code"])==""){
                     echo $row["product_code"]; 
                  }else{
                     echo getProductNameByProductCode($row["product_code"]);
                  }
                  break;
               break;
               //case "addon-product" : echo getAOProductNameByProductCode($row["product_code"]); break;
               case "addon-product" : 

                  if($row["product_name"]!=""){
                     echo $row["product_name"]; 
                  }else if(getAOProductNameByProductCode($row["product_code"], $row["centre_code"])==""){
                     echo $row["product_code"]; 
                  }else{
                     echo getAOProductNameByProductCode($row["product_code"], $row["centre_code"]);
                  }
                break;
               case "mobile" : echo "Mobile App Fee"; break;
             case "outstanding" : echo $row["product_name"]; break;
             default : echo $row["product_name"]; 
            }
            if($row["void"] == 1){
               // echo "</strike>/(credit note)";
            }
            ?>
         </td>
         <td class="td-border"style="padding-right: 20px;text-align: right;  border-left: 0;">
<?php
// switch ($row["collection_type"]) {
//    case "tuition" : echo strtoupper($row["collection_month"])."/".$row["year"]; break;
//    case "registration" : echo $row["year"]; break;
//    case "deposit" : echo $row["year"]; break;
//    case "placement" : echo $row["year"]; break;
//    case "product" : echo number_format($row["qty"]); break;
//    case "addon-product" : echo number_format($row["qty"]); break;
//    case "mobile" : echo $row["year"]; break;
//    case "insurance" : echo $row["year"]; break;
//    case "q-dees-level-kit" : echo $row["year"]; break;
//    case "math" : echo $row["year"]; break;
//    case "mandarin" : echo $row["year"]; break;
//    case "international" : echo $row["year"]; break;
//    case "foundation" : echo $row["year"]; break;
//    case "integrated" : echo $row["year"]; break;
//    case "link" : echo $row["year"]; break;
//    case "mandarinmodules" : echo $row["year"]; break;
//    default: echo $row["year"];
switch ($row["collection_pattern"]) {
      case "Monthly" : echo strtoupper($row["collection_month"])."/".$row["year"]; break;
      case "Termly" : echo getStrTerm($row["collection_month"])."/" .$row["year"]; break;
      case "Half Year" : echo getStrHalfYearly($row["collection_month"])."/" .$row["year"]; break;
      case "Annually" : echo $row["year"]; break;
      default: echo $row["year"];
}
?>
         </td>
         <!-- <td class="uk-text-right td-border"><?php echo number_format($row["discount"], 2)?></td> -->
         <td class="uk-text-right td-border"><?php echo number_format(($row["amount"]), 2)?></td>
      </tr>
<?php
      }
?>
      <tr>
         <td colspan="3" class="uk-text-right uk-text-bold td-border">Total :&nbsp;</td>
         <td class="uk-text-right td-border uk-text-bold"><?php echo number_format($total_amount, 2)?></td>
      </tr>
<?php
?>
   </table>

   <?php
define("MAJOR", '');
define("MINOR", '');
class toWords
{
    var $pounds;
    var $pence;
    var $major;
    var $minor;
    var $words = '';
    var $number;
    var $magind;
    var $units = array('', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine');
    var $teens = array('ten', 'eleven', 'twelve', 'thirteen', 'fourteen', 'fifteen', 'sixteen', 'seventeen', 'eighteen', 'nineteen');
    var $tens = array('', 'ten', 'twenty', 'thirty', 'forty', 'fifty', 'sixty', 'seventy', 'eighty', 'ninety');
    var $mag = array('', 'thousand', 'million', 'billion', 'trillion');

    function toWords($amount, $major = MAJOR, $minor = MINOR)
    {
        $this->__toWords__((int)($amount), $major);
        $whole_number_part = $this->words;
        #$right_of_decimal = (int)(($amount-(int)$amount) * 100);
        $strform = number_format($amount,2);
        $right_of_decimal = (int)substr($strform, strpos($strform,'.')+1);
        //$this->__toWords__($right_of_decimal, $minor);
        //$this->words = $whole_number_part . ' ' . $this->words;
    }

    function __toWords__($amount, $major)
    {
        $this->major  = $major;
        #$this->minor  = $minor;
        $this->number = number_format($amount, 2);
        list($this->pounds, $this->pence) = explode('.', $this->number);
        $this->words = " $this->major";
        if ($this->pounds == 0)
            $this->words = "Zero $this->words";
        else {
            $groups = explode(',', $this->pounds);
            $groups = array_reverse($groups);
            for ($this->magind = 0; $this->magind < count($groups); $this->magind++) {
                if (($this->magind == 1) && (strpos($this->words, 'hundred') === false) && ($groups[0] != '000'))
                    $this->words = ' and ' . $this->words;
                $this->words = $this->_build($groups[$this->magind]) . $this->words;
            }
        }
    }

    function _build($n)
    {
        $res = '';
        $na  = str_pad("$n", 3, "0", STR_PAD_LEFT);
        if ($na == '000')
            return '';
        if ($na{0} != 0)
            $res = ' ' . $this->units[$na{0}] . ' hundred';
        if (($na{1} == '0') && ($na{2} == '0'))
            return $res . ' ' . $this->mag[$this->magind];
        $res .= $res == '' ? '' : ' and';
        $t = (int) $na{1};
        $u = (int) $na{2};
        switch ($t) {
            case 0:
                $res .= ' ' . $this->units[$u];
                break;
            case 1:
                $res .= ' ' . $this->teens[$u];
                break;
            default:
                $res .= ' ' . $this->tens[$t] . ' ' . $this->units[$u];
                break;
        }
        $res .= ' ' . $this->mag[$this->magind];
        return $res;
    }
}
?>

   <?php
function numberTowords($total_amount)
{

$ones = array(
0 =>"ZERO",
1 => "ONE",
2 => "TWO",
3 => "THREE",
4 => "FOUR",
5 => "FIVE",
6 => "SIX",
7 => "SEVEN",
8 => "EIGHT",
9 => "NINE",
10 => "TEN",
11 => "ELEVEN",
12 => "TWELVE",
13 => "THIRTEEN",
14 => "FOURTEEN",
15 => "FIFTEEN",
16 => "SIXTEEN",
17 => "SEVENTEEN",
18 => "EIGHTEEN",
19 => "NINETEEN",
"014" => "FOURTEEN"
);
$tens = array( 
0 => "ZERO",
1 => "TEN",
2 => "TWENTY",
3 => "THIRTY", 
4 => "FORTY", 
5 => "FIFTY", 
6 => "SIXTY", 
7 => "SEVENTY", 
8 => "EIGHTY", 
9 => "NINETY" 
); 
$hundreds = array( 
"HUNDRED", 
"THOUSAND", 
"MILLION", 
"BILLION", 
"TRILLION", 
"QUARDRILLION" 
); /*limit t quadrillion */
$total_amount = number_format($total_amount,2,".",","); 
$num_arr = explode(".",$total_amount); 
$wholenum = $num_arr[0]; 
$decnum = $num_arr[1]; 
$whole_arr = array_reverse(explode(",",$wholenum)); 
krsort($whole_arr,1); 
$rettxt = ""; 
foreach($whole_arr as $key => $i){
	
while(substr($i,0,1)=="0")
		$i=substr($i,1,5);
if($i < 20){ 
/* echo "getting:".$i; */
$j=19;
$rettxt .= $ones[$j]; 
}elseif($i < 100){ 
if(substr($i,0,1)!="0")  $rettxt .= $tens[substr($i,0,1)]; 
if(substr($i,1,1)!="0") $rettxt .= " ".$ones[substr($i,1,1)]; 
}else{ 
if(substr($i,0,1)!="0") $rettxt .= $ones[substr($i,0,1)]." ".$hundreds[0]; 
if(substr($i,1,1)!="0")$rettxt .= " ".$tens[substr($i,1,1)]; 
if(substr($i,2,1)!="0")$rettxt .= " ".$ones[substr($i,2,1)]; 
} 
if($key > 0){ 
$rettxt .= " ".$hundreds[$key]." "; 
}
} 
if($decnum > 0){
$rettxt .= " and ";
if($decnum < 20){
$rettxt .= $ones[$decnum];
}elseif($decnum < 100){
$rettxt .= $tens[substr($decnum,0,1)];
$rettxt .= " ".$ones[substr($decnum,1,1)];
}
}
return $rettxt;
}
extract($_POST);
// if(isset($convert))
// {
   $obj    = new toWords($total_amount);
echo "<p align='left' style='font-weight:bold;margin-bottom: 0;'>". strtoupper($obj->words) ." ONLY"."</p>";
//echo "<p align='left' style='font-weight:bold;'>".numberTowords("$total_amount")." ONLY"."</p>";
// ?s
?>
<br>
  <!-- This is computer generated receipt, no signature is required. -->
<tr><h3 class="uk-text" style="margin-bottom: 0;margin-top:0px;">Note :</h3>
  <td><p style="margin: 0;">1. To validate payment , kindly ensure you receive original receipts from the operating centre for all fees and payments paid. </p></td>
  <td><p style="margin: 0;">2. All fees paid to the centre are not refundable and non-transferable.</p></td>
  <td><p style="margin: 0;">3. All fees must be paid by the 5th of every month/term.</p></td>
  <td><p style="margin: 0;">4. All fees must be directly paid to the operating centre, not to Q-dees Group of Companies/related companies/other parties.</p></td>
  <td><p style="margin: 0;">5. This receipt is only valid upon clearance of the cheque.</p></td>
  <td><p style="margin: 0;">6. This receipt is auto generated therefore no signature is required.</p>
  <p class="uk-text-bold">ALL CASH AND CHEQUES MUST BE MADE PAYABLE TO :(<?php echo $company_name?>  - <?php echo getAOCentreFranciseeCompanyNOByCenterCode($centre_code)?>)</p></td>
  
</tr>
<h4>Bank Account Details</h4>
<?php echo $bank_detail; ?>
<script>
function printDialog() {
   //$("#btnPrint").hide();
   window.print();
}
<?php if(!isset($display)): ?>
$(document).ready(function () {
   printDialog();
   opener.location.reload();
});
<?php endif ?>
</script>
<div class="uk-margin-top">
   <button id="btnPrint" class="uk-button" onclick="printDialog();">Print</button>
</div>
<?php
   } else {
      echo "No record found";
   }
} else {
   echo "Something is wrong, cannot proceed";
}
?>