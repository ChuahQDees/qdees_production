<?php
include_once("mysql.php");
?>
<html>
<head>
   <link rel="stylesheet" type="text/css" href="css/my.css">
   <?php include_once("uikit.php");?>
   <meta name="viewport" content="width=device-width, initial-scale=1">
<style>
body {
   background: url(images/bg.png) no-repeat center center fixed;
   -webkit-background-size: cover;
   -moz-background-size: cover;
   -o-background-size: cover;
   background-size: cover;
}
</style>
<script>
function doSave() {
   var name=$("#name").val();
   var dob=$("#dob").val();
   var country=$("#country").val();
   var registered_on=$("#registered_on").val();
   var race=$("#race").val();
   var ecp=$("#ecp").val();
   var emn=$("#emn").val();
   var eon=$("#eon").val();

   if ((name!="") & (dob!="") & (country!="") & (registered_on!="") & (race!="") & (ecp!="") & (emn!="") & (eon!="")) {
      $("#frmStudent").submit();
   } else {
      UIkit.notify("Please fill up mandatory fields marked *");
   }
}
</script>
   <title>Qdees</title>
</head>
<?php
$centre_code=$_GET["centre_code"];
?>
<body>
<?php
if ($centre_code!="") {
?>
   <div class="uk-text-center"><img src="images/logo.png"></div>
   <div class="uk-margin-top uk-margin-right uk-margin-left">
      <div class="uk-width-medium-6-10 uk-container-center">
         <div class="uk-panel uk-panel-box uk-panel-box-primary uk-width-1-1">
            <form name="frmStudent" id="frmStudent" method="post" action="save_student.php" class="uk-form">
               <input type="hidden" name="centre_code" id="centre_code" value="<?php echo $centre_code?>">
               <h1 class="uk-text-center">Student Registration Form</h1>
               <h2 class="uk-text-center">Please enter your information and click submit button</h2>
               <table class="uk-table">
                  <tr>
                     <td class="uk-text-bold"><span class="uk-text-danger">*</span> Student :</span>:</td>
                     <td><input name="name" id="name" type="text" class="uk-width-1-1" placeholder="Student Full Name" value=""></td>
                  </tr>
                  <tr>
                     <td class="uk-text-bold"><span class="uk-text-danger">*</span> Date of Birth :</td>
                     <td><input name="dob" id="dob" type="text" class="uk-width-1-1" data-uk-datepicker="{format: 'YYYY-MM-DD'}" placeholder="DOB" value=""></td>
                  </tr>
                  <tr>
                     <td class="uk-text-bold"><span class="uk-text-danger">*</span> Country :</td>
                     <td>
                        <select name="country" id="country" class="uk-width-1-1">
                           <option value="">Select</option>
<?php
$sql="SELECT * from codes where module='COUNTRY' order by code";
$result=mysqli_query($connection, $sql);
while ($row=mysqli_fetch_assoc($result)) {
?>
                           <option value="<?php echo $row["code"]?>"><?php echo $row["code"]?></option>
<?php
}
?>
                        </select>
                     </td>
                  </tr>
                  <tr>
                     <td class="uk-text-bold"><span class="uk-text-danger">*</span> Registered On :</td>
                     <td><input name="registered_on" id="registered_on" type="text" data-uk-datepicker="{format: 'YYYY-MM-DD'}" class="uk-width-1-1" placeholder="Registered On" value=""></td>
                  </tr>
                  <tr>
                     <td class="uk-text-bold"><span class="uk-text-danger">*</span> Race :</td>
                     <td>
                        <select name="race" id="race" class="uk-width-1-1">
                           <option value="">Select</option>
<?php
$sql="SELECT * from codes where module='RACE' order by code";
$result=mysqli_query($connection, $sql);
while ($row=mysqli_fetch_assoc($result)) {
?>
                           <option value="<?php echo $row["code"]?>"><?php echo $row["code"]?></option>
<?php
}
?>
                        </select>
                     </td>
                  </tr>
                  <tr>
                     <td class="uk-text-bold"><span class="uk-text-danger">*</span> Emergency Contact Person :</td>
                     <td><input name="ecp" id="ecp" type="text" class="uk-width-1-1" placeholder="Emergency Contact Person" value=""></td>
                  </tr>
                  <tr>
                     <td class="uk-text-bold"><span class="uk-text-danger">*</span> Emergency Mobile No. :</td>
                     <td><input name="emn" id="emn" type="text" class="uk-width-1-1" placeholder="Emergency Mobile No." value=""></td>
                  </tr>
                  <tr>
                     <td class="uk-text-bold"><span class="uk-text-danger">*</span> Emergency Office No. :</td>
                     <td><input name="eon" id="eon" type="text" class="uk-width-1-1" placeholder="Emergency Office No." value=""></td>
                  </tr>
                  <tr>
                     <td colspan="2"><span class="uk-text-danger uk-text-bold">*</span><span class="uk-text-danger uk-text-small"> Mandatory fields</span></td>
                  </tr>
                  <tr>
                     <td colspan="2" class="uk-text-center">
                        <a onclick="doSave()" class="uk-button uk-button-primary">Submit</a>
                     </td>
                  </tr>
               </table>
            </form>
         </div>
      </div>
   </div><br><br>
<?php
if ($_GET["msg"]!="") {
   echo "<script>UIkit.notify('".$_GET["msg"]."')</script>";
}
} else {
   echo "<div class='uk-margin-left uk-margin-top uk-margin-right'><div class='uk-alert uk-alert-danger'>Something is wrong, please try again later.</div></div>";
}
?>
</script>
</body>
</html>