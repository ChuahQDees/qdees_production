<?php
if ($_SESSION["isLogin"]==1) {
   if ($_SESSION["UserType"]=="S") {
      include_once("mysql.php");
      include_once("lib/pagination/pagination.php");
      $p=$_GET["p"];
      $m=$_GET["m"];
      $get_sha1_id=$_GET["id"];
      $pg=$_GET["pg"];
      $mode=$_GET["mode"];

      if ($mode=="") {
         $mode="ADD";
      }

      include_once("vendor_func.php");
?>

<script>
function doDeleteRecord(id) {
   UIkit.modal.confirm("<h2>Are you sure to continue?</h2>", function () {
      $("#id").val(id);
      $("#frmDeleteRecord").submit();
   });
}
</script>

<br>
<div class="uk-margin-right">
   <div class="uk-width-1-1 myheader">
      <h2 class="uk-text-center myheader-text-color">Vendor Information</h2>
   </div>
   <form name="frmVendor" id="frmVendor" method="post" class="uk-form uk-form-small" action="index.php?p=vendor&pg=<?php echo $pg?>&mode=SAVE">
      <div class="uk-grid">
         <div class="uk-width-5-10">
            <table class="uk-table uk-table-small">
               <tr class="uk-text-small">
                  <td class="uk-width-3-10 uk-text-bold">Vendor Name : </td>
                  <td class="uk-width-7-10">
                     <input class="uk-width-1-1" type="text" name="vendor_name" id="vendor_name" value="<?php echo $edit_row['vendor_name']?>">
                     <input type="hidden" name="hidden_id" id="hidden_id" value="<?php echo $edit_row['id']?>">
                  </td>
               </tr>
               <tr class="uk-text-small">
                  <td class="uk-width-3-10 uk-text-bold">Address : </td>
                  <td class="uk-width-7-10">
                     <input class="uk-width-1-1" type="text" name="address1" id="address1" value="<?php echo $edit_row['address1']?>">
                     <input class="uk-width-1-1" type="text" name="address2" id="address2" value="<?php echo $edit_row['address2']?>">
                     <input class="uk-width-1-1" type="text" name="address3" id="address3" value="<?php echo $edit_row['address3']?>">
                     <input class="uk-width-1-1" type="text" name="address4" id="address4" value="<?php echo $edit_row['address4']?>">
                     <input class="uk-width-1-1" type="text" name="address5" id="address5" value="<?php echo $edit_row['address5']?>">
                  </td>
               </tr>
            </table>
         </div>
         <div class="uk-width-5-10">
            <table class="uk-table uk-table-small">
               <tr class="uk-text-small">
                  <td class="uk-width-3-10 uk-text-bold">Tel 1 : </td>
                  <td class="uk-width-7-10">
                     <input class="uk-width-1-1" type="text" name="tel1" id="tel1" value="<?php echo $edit_row['tel1']?>">
                  </td>
               </tr>
               <tr class="uk-text-small">
                  <td class="uk-width-3-10 uk-text-bold">Tel 2 : </td>
                  <td class="uk-width-7-10">
                     <input class="uk-width-1-1" type="text" name="tel2" id="tel2" value="<?php echo $edit_row['tel2']?>">
                  </td>
               </tr>
               <tr class="uk-text-small">
                  <td class="uk-width-3-10 uk-text-bold">Fax : </td>
                  <td class="uk-width-7-10"><input class="uk-width-1-1" type="tel" name="fax" id="fax" value="<?php echo $edit_row['fax']?>"></td>
               </tr>
               <tr class="uk-text-small">
                  <td class="uk-width-3-10 uk-text-bold">Email : </td>
                  <td class="uk-width-7-10"><input class="uk-width-1-1" type="email" name="email" id="email" value="<?php echo $edit_row['email']?>"></td>
               </tr>
               <tr class="uk-text-small">
                  <td class="uk-width-3-10 uk-text-bold">PIC : </td>
                  <td class="uk-width-7-10"><input class="uk-width-1-1" type="text" name="pic" id="pic" value="<?php echo $edit_row['pic']?>"></td>
               </tr>
            </table>
         </div>
      </div>
      <br>
      <div class="uk-text-center">
         <button class="uk-button uk-button-primary">Save</button>
      </div>
   </form>

   <div class="uk-width-1-1 myheader">
      <h2 class="uk-text-center myheader-text-color">Searching</h2>
   </div>
<?php
$numperpage=20;
$query="p=$p&m=$m&s=$s";
$pagination=getPagination($pg, $numperpage, $query, $browse_sql, $start_record, $num_row);
$browse_sql.=" limit $start_record, $numperpage";
$browse_result=mysqli_query($connection, $browse_sql);
$browse_num_row=mysqli_num_rows($browse_result);
echo $pagination;
?>
   <form class="uk-form" name="frmVendorSearch" id="frmVendorSearch" method="get">
      <input type="hidden" name="mode" id="mode" value="BROWSE">
      <input type="hidden" name="p" id="p" value="<?php echo $p?>">
      <input type="hidden" name="m" id="m" value="<?php echo $m?>">
      <input type="hidden" name="pg" value="">

      <div class="uk-grid">
         <div class="uk-width-7-10 uk-text-small">
            <input class="uk-width-1-1" placeholder="Vendor Name/Tel/Fax" name="s" id="s" value="<?php echo $_GET['s']?>">
         </div>
         <div class="uk-width-3-10">
            <button class="uk-button uk-width-1-1">Search</button>
         </div>
      </div>
   </form>

   <div class="uk-width-1-1 myheader">
      <h2 class="uk-text-center myheader-text-color">Listing</h2>
   </div>

   <table class="uk-table uk-table-small uk-table-hover">
      <tr class="uk-text-bold uk-text-small">
         <td>Vendor Name</td>
         <td>Contact</td>
         <td>PIC</td>
         <td>Action</td>
      </tr>
<?php
if ($browse_num_row>0) {
   while ($browse_row=mysqli_fetch_assoc($browse_result)) {
      $sha1_id=sha1($browse_row["id"]);
?>
      <tr class="uk-text-small">
         <td><?php echo $browse_row["vendor_name"]?></td>
         <td>
            <?php echo $browse_row["tel1"]?><br>
            <?php echo $browse_row["tel2"]?><br>
            <?php echo $browse_row["fax"]?><br>
            <?php echo $browse_row["email"]?>
         </td>
         <td><?php echo $browse_row["pic"]?></td>
         <td>
            <a href="index.php?p=<?php echo $p?>&m=<?php echo $m?>&id=<?php echo $sha1_id?>&mode=EDIT"><img src="images/edit.png"></a>
            <a onclick="doDeleteRecord('<?php echo $sha1_id?>')" href="#" id="btnDelete"><img src="images/delete.png"></a>
         </td>
      </tr>
<?php 
   }
} else {
   echo "<tr><td colspan='4'>No Record Found</td></tr>";
}
?>
   </table>
</div>

<form name="frmDeleteRecord" id="frmDeleteRecord" method="get">
   <input type="hidden" name="p" value="<?php echo $p?>">
   <input type="hidden" name="m" value="<?php echo $m?>">
   <input type="hidden" name="id" id="id" value="">
   <input type="hidden" name="mode" value="DEL">
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