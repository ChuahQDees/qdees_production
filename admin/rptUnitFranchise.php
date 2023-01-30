<?php
session_start();
include_once("../mysql.php");
include_once("functions.php");

function isMaster($master_code) {
   global $connection;

   $sql="SELECT * from master where master_code='$master_code'";
   $result=mysqli_query($connection, $sql);
   $num_row=mysqli_num_rows($result);
   if ($num_row>0) {
      return true;
   } else {
      return false;
   }
}
?>

<script>
<?php
if ((($_SESSION["UserType"]=="A") || ($_SESSION["UserType"]=="O") || ($_SESSION["UserType"]=="S") ) & (hasRightGroupXOR($_SESSION["UserName"], "ReportingView"))) {
?>
$(document).ready(function () {
   generateReport();
});
<?php
}
?>
function generateReport() {
   var centre_code=$("#centre_code").val();

   if (centre_code!="") {
      $.ajax({
         url : "admin/a_rptUnitFranchise.php",
         type : "POST",
         data : "centre_code="+centre_code,
         dataType : "text",
         beforeSend : function(http) {
         },
         success : function(response, status, http) {
            $("#sctResult").html(response);
         },
         error : function(http, status, error) {
            UIkit.notify("Error:"+error);
         }
      });
   } else {
      UIkit.notify("Please select a centre");
   }

}
</script>

<div class="uk-form">
<?php
if (($_SESSION["UserType"]=="S") & (hasRightGroupXOR($_SESSION["UserName"], "ReportingView"))) {
   if (isMaster($_SESSION["CentreCode"])) {
      $sql="SELECT * from centre where upline='".$_SESSION["CentreCode"]."' order by centre_code";
   } else {
      $sql="SELECT * from centre order by centre_code";
   }
} else {
   if ((($_SESSION["UserType"]=="A") || ($_SESSION["UserType"]=="O")) & (hasRightGroupXOR($_SESSION["UserName"], "ReportingView"))) {
      $sql="";
   }
}

if ($sql=="") {
?>
Centre Code<br>
<input name="centre_code" id="centre_code" value="<?php echo $_SESSION['CentreCode']?>" readonly>
<button onclick="generateReport()" id="btnGenerate" class="uk-button">Generate</button>
<?php
} else {
   $result=mysqli_query($connection, $sql);
?>
   <select name="centre_code" id="centre_code">
      <option value="">Select</option>
      <option value="ALL">ALL</option>
<?php
while ($row=mysqli_fetch_assoc($result)) {
?>
      <option value="<?php echo $row['centre_code']?>"><?php echo $row["centre_code"]?></option>
<?php
}
?>
   </select>
   <button onclick="generateReport();" id="btnGenerate" class="uk-button">Generate</button>
<?php
}
?>
</div>
<div id="sctResult"></div>