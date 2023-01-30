<?php
include_once("../mysql.php");
foreach ($_POST as $key=>$value) {
   $$key=mysqli_real_escape_string($connection, $value); //$mastertype
}

if ($mastertype!="") {
   switch ($mastertype) {
      case "HQ" : $sql="SELECT * from codes where module='COUNTRY' order by code"; break;
      case "Country" : $sql="SELECT * from codes where module='COUNTRY' order by code"; break;
      case "Region" : $sql="SELECT * from codes where module='REGION' order by code"; break;
      case "Master/Territory" : $sql="SELECT * from codes where module='COUNTRY' order by code"; break;
   }

   $s="";
   if ($sql!="") {
      $result=mysqli_query($connection, $sql);
      $s.="1|<select name='mastertype' id='mastertype class='uk-width-1-1' onchange='doChangeMasterType()'>";
      $s.="   <option value=''>Select</option>";
      while ($row=mysqli_fetch_assoc($result)) {
         $s.="   <option value='".$row["code"]."'>".$row["code"]."</option>";
      }
      $s.="</select>";
   } else {
      $s.="0|<select name='mastertype' id='mastertype class='uk-width-1-1'><option value=''>NONE</option></select>";
   }
} else {
   $s.="0|<select name='mastertype' id='mastertype class='uk-width-1-1'><option value=''>NONE</option></select>";
}
//echo "this is message";
echo $s;
?>