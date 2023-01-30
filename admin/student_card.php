<a href="/index.php?p=student_reg&id=<?php echo $_GET["id"] ?>">                 
             <span class="d_n btn-qdees"><i class="fa fa-arrow-left" style="margin-right: 20px;"></i>Back</span>
</a>
<span>
    <span class="page_title"><img src="/images/title_Icons/Stud Reg.png">Student Card</span>
</span>
<?php
if ($_SESSION["isLogin"]==1 && $_GET["id"] !=="") {
   if ($_SESSION["UserType"]=="A") {
      include_once("mysql.php");
      include_once("lib/pagination/pagination.php");
      $p=$_GET["p"];
      $m=$_GET["m"];
      $get_sha1_id=$_GET["id"];
      $pg=$_GET["pg"];
      $mode=$_GET["mode"];

      $module="Student Card";
      $str_module="Student Card";
      $p_module="Student Card";
      // if ($mode=="") {

      // }

      if ($mode=="") {
         $mode="Add";
      }

       include_once("student_card_func.php");
?>

<script>
$(document).ready(function() {

$("#frmCard").submit(function(e) {
    // alert($("#status").val())
    // return false;
    if ($("#unique_id").val() == "") {
        $("#error_unique_id").show();
        return false;
    }
    if ($("#status").val() == "") {
        $("#error_status").show();
        return false;
    }
    if ($("#status").val() == "2" && $("#remarks").val() == "") {
        $("#error_remarks").show();
        return false;
    }

    return true;
})
})
function doDeleteRecord(id, card_id) {
   UIkit.modal.confirm("<h2>Are you sure to continue?</h2>", function () {
      $("#id").val(id);
      $("#del_card_id").val(card_id);
      $("#frmDeleteRecord").submit();
   });
}
</script>

<div class="uk-margin-right">
   <div class="uk-width-1-1 myheader">
      <h2 class="uk-text-center myheader-text-color"><?php echo $str_module?></h2>
      <h5 class="uk-text-center myheader-text-color">Student Name:
            <?php
             $sha1_id = $_GET["id"];
             
              $browse_sql2 = "SELECT * FROM `student` WHERE  SHA1(ID) =  '$sha1_id'";
             $result = mysqli_query($connection, $browse_sql2);
              $row = mysqli_fetch_assoc($result);
              //print_r($row["name"]);
             echo $row["name"]
            // }
         ?>
        </h5>
   </div>
   <form name="" id="frmCard" method="post" class="uk-form uk-form-small" action="index.php?p=student_card&id=<?php echo $get_sha1_id ?>&pg=<?php echo $pg ?>">
      <div class="uk-grid">
         <div class="uk-width-5-10">
            <table class="uk-table uk-table-small">
               
               <tr class="uk-text-small">
                  <td class="uk-width-3-10 uk-text-bold">Student Card ID : </td>
                  <td class="uk-width-7-10">
                  <?php if($edit_row['unique_id']==""){  ?>
                  <select name="unique_id" id="unique_id" class="uk-width-1-1 chosen-select">
                  <option value="">Select Student Card ID</option>
                  <?php 
  $CentreCode =  $_SESSION['CentreCode'];
  $postRequest = array(
    'centre_code' => "$CentreCode",
    'session' => 'cb76fe897986563639f6983bfd33b57cd'
 );
//print_r($postRequest);
//$cURLConnection = curl_init('http://13.58.211.59/q-dees/api/qrcodes');
$cURLConnection = curl_init('http://13.67.72.102/api/qrcodes');
curl_setopt($cURLConnection, CURLOPT_POSTFIELDS, $postRequest);
curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);

$apiResponse = curl_exec($cURLConnection);
curl_close($cURLConnection);

// $apiResponse - available data from the API request
//echo $apiResponse;
$array = json_decode($apiResponse, true);
//$jsonArrayResponse = json_decode($apiResponse);
$status = $array['status'];
$message = $array['message'];
$TotalCard = $array['Total Card'];
$datas = $array['data'];
//print_r($array['status']);
//print_r($array['data']);

if($status==1){
   foreach($datas as $data){
    $sql="SELECT unique_id from `student` where unique_id='$data[qr_code]' UNION ALL select unique_id from `student_card` where unique_id='$data[qr_code]'";
    $result=mysqli_query($connection, $sql);
    if(mysqli_num_rows($result) == 0){
  ?>
 
  <option value="<?php echo $data[qr_code] ?>"><?php echo $data[qr_code] ?></option>
  
<?php
    }
}
}else{

  ?>
  <option><?php echo $message ?></option>
  <?php
}
?>
                  </select>
                  <?php 
                  } else {
                    ?>
                    <input type="text" class="uk-width-1-1" name="unique_id" readonly value="<?php echo $edit_row['unique_id'] ?>">
                     <?php 
                  }
                  ?>
                  <span style="color:red; display:none;" id="error_unique_id">Please select unique id</span>
                     <!-- <input class="uk-width-1-1" type="text" name="unique_id" id="unique_id" value="<?php echo $unique_id?>" readonly> -->
                     <input type="hidden" name="card_id" id="card_id" value="<?php echo $edit_row['id']?>"> 
                     <input type="hidden" name="form_mode" id="form_mode" value="<?php echo $mode; ?>"> 
                  </td>
               </tr>
               <tr class="uk-text-small">
                  <td class="uk-width-3-10 uk-text-bold">Status  : </td>
                  <td class="uk-width-7-10">
                     <select class="uk-width-1-1" type="text" name="status" id="status"">
                        <option value="">Select Status</option>
                        <option <?php if($edit_row['status']=="0"){ echo "selected"; } ?> value="0">Active</option>
                        <option <?php if($edit_row['status']=="1"){ echo "selected"; } ?> value="1">Lost</option>
                        <option <?php if($edit_row['status']=="2"){ echo "selected"; } ?> value="2">Others</option>
                     </select><br>
                     <span style="color:red; display:none;" id="error_status">Please select status</span>
                  </td>
               </tr>
            </table>
         </div>
         <div class="uk-width-5-10">
            <table class="uk-table uk-table-small">
               <tr class="uk-text-small">
                  <td class="uk-width-3-10 uk-text-bold">Remarks : </td>
                  <td class="uk-width-7-10"><input class="uk-width-1-1" type="text" name="remarks" id="remarks" value="<?php echo $edit_row['remarks']?>">
               </tr>
               
            </table>
         </div>
      </div>
      <br>
      <div class="uk-text-center">
         <button class="uk-button uk-button-primary">Save</button>
      </div>
   </form><br>

   <!-- <div class="uk-width-1-1 myheader">
      <h2 class="uk-text-center myheader-text-color">Searching</h2>
   </div>
   <form class="uk-form" name="frm<?php echo $str_module?>Search" id="frm<?php echo $str_module?>Search" method="get">
      <input type="hidden" name="mode" id="mode" value="BROWSE">
      <input type="hidden" name="p" id="p" value="<?php echo $p?>">
      <input type="hidden" name="m" id="m" value="<?php echo $m?>">
      <input type="hidden" name="pg" value="">

      <div class="uk-grid">
         <div class="uk-width-7-10 uk-text-small">
            <input class="uk-width-1-1" placeholder="Unique ID" name="s" id="s" value="<?php echo $_GET['s']?>">
         </div>
         <div class="uk-width-3-10">
            <button class="uk-button uk-width-1-1">Search</button>
         </div>
      </div>
   </form><br> -->

   <div class="uk-width-1-1 myheader">
      <h2 class="uk-text-center myheader-text-color">Listing</h2>
      <h5 class="uk-text-center myheader-text-color">Student Name:
            <?php
             $sha1_id = $_GET["id"];
             
              $browse_sql2 = "SELECT * FROM `student` WHERE  SHA1(ID) =  '$sha1_id'";
             $result = mysqli_query($connection, $browse_sql2);
              $row = mysqli_fetch_assoc($result);
              //print_r($row["name"]);
             echo $row["name"]
            // }
         ?>
        </h5>
   </div>

<?php
//where sha1(c.student_id) = '$get_sha1_id'
$browse_sql = "Select * from student_card where deleted=0 and sha1(student_id) = '$get_sha1_id'";
$numperpage=20;
$query="p=$p&m=$m&s=$s";
$pagination=getPagination($pg, $numperpage, $query, $browse_sql, $start_record, $num_row);
$browse_sql.=" order by id desc limit $start_record, $numperpage";
$browse_result=mysqli_query($connection, $browse_sql);
$browse_num_row=mysqli_num_rows($browse_result);

//echo $browse_sql;

?>

   <table class="uk-table">
      <tr class="uk-text-bold uk-text-small">
         <td>Student Card ID</td>
         <td>Status</td>
         <td>Remarks</td>
         <td>Action</td>
      </tr>
<?php
if ($browse_num_row>0) {
   while ($browse_row=mysqli_fetch_assoc($browse_result)) {
      $sha1_id=sha1($browse_row["id"]);
?>
      <tr class="uk-text-small">
         <td><?php echo $browse_row["unique_id"]?></td>
         <td><?php 
         if($browse_row["status"]=="0"){
            echo "Active";
         } else if($browse_row["status"]=="1"){
            echo "Lost";
         }
         else if($browse_row["status"]=="2"){
            echo "Others";
         }
         ?></td>
         <td><?php echo $browse_row["remarks"]?></td>
         <td>
         <?php
                if($browse_row["status"]=="1"){
                ?>
                        <a id="edit_btn" </a>
                            <?php
                } else {
                    ?>
                            <a id="edit_btn"
                                href="index.php?p=<?php echo $p?>&m=<?php echo $m?>&id=<?php echo $get_sha1_id?>&mode=EDIT&card_id=<?php echo sha1($browse_row["id"])?>"><img
                                    src="images/edit.png"></a>
                            <!-- <a onclick="doDeleteRecord('<?php echo $get_sha1_id; ?>', '<?php echo sha1($browse_row[id]); ?>')" href="#" id="btnDelete"><img src="images/delete.png"></a> -->
                            <?php
                } 
            ?>
         </td>
      </tr>
<?php 
   }
} else {
   echo "<tr><td colspan='6'>No Record Found</td></tr>";
}
?>
   </table>
<?php
echo $pagination;
?>
</div>

<form name="frmDeleteRecord" id="frmDeleteRecord" method="get">
   <input type="hidden" name="p" value="<?php echo $p?>">
   <input type="hidden" name="m" value="<?php echo $m?>">
   <input type="hidden" name="id" id="id" value="">
   <input type="hidden" name="card_id" id="del_card_id" value="">
   <input type="hidden" name="action" value="DEL">
</form>
<?php
if ($msg!="") {
   echo "<script>UIkit.notify('$msg')</script>";
}
} else {
   echo "<div class='uk-margin-top uk-margin-right'><div class='uk-alert uk-alert-danger uk-text-large uk-text-bold uk-text-center'>Unauthorised access denied</div></div>";
}
} else {
   header("Location: index.php");
}
?>
<script src="lib/sign/js/jquery.signature.js"></script>
<script type="text/javascript" src="lib/sign/js/jquery.ui.touch-punch.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.4.2/chosen.jquery.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.4.2/chosen.min.css">
<script>
$('.chosen-select').chosen({
    search_contains: true
}).change(function(obj, result) {
    // console.debug("changed: %o", arguments);
    // console.log("selected: " + result.selected);
});
</script>
<style>
.d_n {
    /* display: none; */
}
</style>