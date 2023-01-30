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

      $module="CENTRE_CATEGORY";
//      echo $module;
      $str_module="Centre Category";
      $p_module="centre_category";

      if ($mode=="") {
         $mode="ADD";
      }

      include_once("$p_module"."_func.php");
?>

<script>
function doDeleteRecord(id) {
   UIkit.modal.confirm("<h2>Are you sure to continue?</h2>", function () {
      $("#id").val(id);
      $("#frmDeleteRecord").submit();
   });
}
</script>

<div class="uk-margin-right">
   <div class="uk-width-1-1 myheader">
      <h2 class="uk-text-center myheader-text-color"><?php echo $str_module?></h2>
   </div>
   <form name="frmCentre" id="frmCentre" method="post" class="uk-form uk-form-small" action="index.php?p=<?php echo $p_module?>&pg=<?php echo $pg?>&mode=SAVE">
      <div class="uk-grid">
         <div class="uk-width-5-10">
            <table class="uk-table uk-table-small">
               <tr class="uk-text-small">
                  <td class="uk-width-3-10 uk-text-bold">Country : </td>
                  <td class="uk-width-7-10">
                    <select name="country" id="country" class="uk-width-1-1">
                        <option value="">Select</option>
						<?php
						$sql="SELECT * from codes where module='COUNTRY' order by code";
						$result=mysqli_query($connection, $sql);
						while ($row=mysqli_fetch_assoc($result)) {
						?>
                        <option value="<?php echo $row["code"]?>" <?php if ($row["code"]==$edit_row["country"]) {echo "selected";}?>><?php echo $row["code"]?></option>
						<?php
						}
						?>
                     </select>
                  </td>
               </tr>
               <tr class="uk-text-small">
                  <td class="uk-width-3-10 uk-text-bold"><?php echo $str_module?> : </td>
                  <td class="uk-width-7-10">				  
					  <input class="uk-width-1-1" type="text" name="category" id="category" value="<?php echo $edit_row['category']?>">
                     <input  type="hidden" name="hidden_category" id="hidden_category" value="<?php echo $edit_row['category']?>">
                     <input type="hidden" name="hidden_<?php echo $p_module?>" id="hidden_<?php echo $p_module?>" value="<?php echo $edit_row['code']?>">
                     <input type="hidden" name="module" id="module" value="<?php echo $module?>">
                     <input type="hidden" name="code_id" id="code_id" value="<?php echo $edit_row['id']?>">
                  </td>
               </tr>
            </table>
         </div>
         <div class="uk-width-5-10">
            <table class="uk-table uk-table-small">
               </tr>
               <tr class="uk-text-small">
                  <td class="uk-width-3-10 uk-text-bold">Description : </td>
                  <td class="uk-width-7-10"><input class="uk-width-1-1" type="text" name="description" id="description" value="<?php echo $edit_row['description']?>"></td>
               </tr>
            </table>
         </div>
      </div>
      <br>
      <div class="uk-text-center">
         <button class="uk-button uk-button-primary">Save</button>
      </div>
   </form><br>

   <div class="uk-width-1-1 myheader">
      <h2 class="uk-text-center myheader-text-color">Searching</h2>
   </div>
   <form class="uk-form" name="frm<?php echo $str_module?>Search" id="frm<?php echo $str_module?>Search" method="get">
      <input type="hidden" name="mode" id="mode" value="BROWSE">
      <input type="hidden" name="p" id="p" value="<?php echo $p?>">
      <input type="hidden" name="m" id="m" value="<?php echo $m?>">
      <input type="hidden" name="pg" value="">

      <div class="uk-grid">
         <div class="uk-width-4-10 uk-text-small">
            <input class="uk-width-1-1" placeholder="<?php  echo $str_module?>" name="category" id="s" value="<?php echo $_GET['category']?>">
         </div>
		 
		 <div class="uk-width-4-10 uk-text-small">
			<select name="country" id="country" class="uk-width-1-1">
                        <option value="">Select</option>
						<option <?php if($_GET['country']=="Bangladesh"){echo "selected"; } ?> value="Bangladesh">Bangladesh</option>
						<option <?php if($_GET['country']=="Brunei"){echo "selected"; } ?> value="Brunei">Brunei</option>
						<option <?php if($_GET['country']=="Cambodia"){echo "selected"; } ?> value="Cambodia">Cambodia</option>
						<option <?php if($_GET['country']=="Indonesia"){echo "selected"; } ?> value="Indonesia">Indonesia</option>
						<option <?php if($_GET['country']=="Laos"){echo "selected"; } ?> value="Laos">Laos</option>
						<option <?php if($_GET['country']=="Malaysia"){echo "selected"; } ?> value="Malaysia">Malaysia</option>
						<option <?php if($_GET['country']=="Myanmar"){echo "selected"; } ?> value="Myanmar">Myanmar</option>
						<option <?php if($_GET['country']=="Philippines"){echo "selected"; } ?> value="Philippines">Philippines</option>
						<option <?php if($_GET['country']=="Singapore"){echo "selected"; } ?> value="Singapore">Singapore</option>
						<option <?php if($_GET['country']=="Thailand"){echo "selected"; } ?> value="Thailand">Thailand</option>
						<option <?php if($_GET['country']=="Timor-Leste"){echo "selected"; } ?> value="Timor-Leste">Timor-Leste</option>
						<option <?php if($_GET['country']=="Vietnam"){echo "selected"; } ?> value="Vietnam">Vietnam</option>

            </select>
         </div>
         <div class="uk-width-2-10">
            <button class="uk-button uk-width-1-1">Search</button>
         </div>
      </div>
   </form><br>

   <div class="uk-width-1-1 myheader">
      <h2 class="uk-text-center myheader-text-color">Listing</h2>
   </div>

<?php
$numperpage=20;
$query="p=$p&m=$m&s=$s";
$pagination=getPagination($pg, $numperpage, $query, $browse_sql, $start_record, $num_row);
$browse_sql.=" limit $start_record, $numperpage";
//echo $browse_sql;
$browse_result=mysqli_query($connection, $browse_sql);
$browse_num_row=mysqli_num_rows($browse_result);
?>

   <table class="uk-table uk-table-striped">
      <tr class="uk-text-bold uk-text-small">
         <!--<td> Code</td>-->
         <td>Centre Category</td>
         <td>Country</td>
         <td>Description</td>
         <td>Action</td>
      </tr>
<?php
if ($browse_num_row>0) {
   while ($browse_row=mysqli_fetch_assoc($browse_result)) {
      $sha1_id=sha1($browse_row["id"]);
?>
      <tr class="uk-text-small">
          <!--<td><?php echo $browse_row["code"]?></td>-->
        <td><?php echo $browse_row["category"]?></td>
         <td><?php echo $browse_row["country"]?></td>
         <td><?php echo $browse_row["description"]?></td>
         <td>
            <a href="index.php?p=<?php echo $p?>&m=<?php echo $m?>&id=<?php echo $sha1_id?>&mode=EDIT&master=1"><img src="images/edit.png"></a>
            <a onclick="doDeleteRecord('<?php echo $sha1_id?>')" href="#" id="btnDelete"><img src="images/delete.png"></a>
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
   <input type="hidden" name="mode" value="DEL">
   <input type="hidden" name="master" value="1">
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