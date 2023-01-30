<a href="/index.php?p=visitor_hq">
				 <span class="btn-qdees"><i class="fa fa-arrow-left" style="margin-right: 20px;"></i>Back</span>
</a>
<span>
    <span class="page_title"><img src="/images/title_Icons/Visitor Reg.png">Visitor Registration</span>
</span>

 <?php
session_start();
include_once("mysql.php");
include_once("search_new.php");
?>
<script>
function doDelete(id) {
   UIkit.modal.confirm("<h2>Are you sure to continue?</h2>", function () {
      $("#del_id").val(id);
      $("#frmDelete").submit();
   });
}

function generateQR() {
   $("#dlgQRCode").html("");
//   $("#dlgQRCode").qrcode("<?php echo $_SERVER['HTTP_HOST']."/qdees/visitor_qr.php?centre_code=".$_SESSION["CentreCode"]?>")
   $("#dlgQRCode").qrcode("http://www.webhyper.com");
   $("#dlgQRCode").dialog({
      title:"QRCode",
      modal:true,
      height:'auto',
      width:'auto',
      // position: {my: "center top", at: "center top", of: $("#theBody")}
   });
}

</script>

<?php
if (($_GET["del_id"]!="") & ($_GET["action"]=="DEL")) {
   $sql="DELETE from visitor where id='".$_GET["del_id"]."'";
   $result=mysqli_query($connection, $sql);
}
?>

<div class="uk-margin-left uk-margin-top uk-margin-right">
   <div class="uk-width-1-1 myheader">
      <div class="uk-grid">
		 <div class="uk-width-3-10"></div>
         <div class="uk-width-4-10">
            <h2 class="uk-text-center myheader-text-color myheader-text-style">Visitor QR Listing</h2>
         </div>
         <div class="uk-width-3-10 uk-text-left">

         </div>
      </div>
   </div>
    <div class="uk-overflow-container">
   <form class="uk-form" method="post" action="">
       <div class="row">
           <div class="col-sm-12 col-md-5">
               <input name="name" id="name" class="uk-width-1-1" placeholder="Visitor's Name" type="text" value="<?php echo $_POST["name"]?>">
           </div>
           <div class="col-sm-12 col-md-5">
               <input name="tel" id="tel" class="uk-width-1-1" placeholder="Visitor's Tel" type="text" value="<?php echo $_POST["tel"]?>">
           </div>
           <div class="col-sm-12 col-md-2">
               <button class="uk-button full-width-blue">Search</button>
           </div>
       </div>
   </form>
<?php
$name=$_POST["name"];
$tel=$_POST["tel"];

$base_sql="SELECT * from visitor";
$name_token=ConstructToken("name", "%".$_POST["name"]."%", "like");
$centre_token=ConstructToken("centre_code", $_SESSION["CentreCode"], "=");
$tel_token=ConstructToken("tel", "%".$_POST["tel"]."%", "like");
$final_token=ConcatToken($name_token, $tel_token, "and");
$final_token=ConcatToken($final_token, $centre_token, "and");
$final_sql=ConcatWhere($base_sql, $final_token);
$final_sql=ConcatOrder($final_sql, "date_created desc");
$result=mysqli_query($connection, $final_sql);
$num_row=mysqli_num_rows($result);
?>

   <table class="uk-table q-table">
      <tr class="uk-text-small uk-text-bold">
        <td>Date</td>
         <td>Visitor's Name</td>
         <td>IC / Passport</td>
         <td>Phone</td>
         <td>Email</td>
         <td>No. of Children</td>
         <td>Birth Year</td>
         <td data-uk-tooltip="{pos:top}" title="How do you find out of Q-dees">Find Out</td>
         <td>Action</td>
      </tr>
<?php
if ($num_row>0) {
   while ($row=mysqli_fetch_assoc($result)) {
?>
      <tr class="uk-text-small">
        <td><?php echo $row["date_created"]?></td>
         <td><?php echo $row["name"]?></td>
         <td><?php echo $row["nric"]?></td>
         <td><?php echo $row["tel"]?></td>
         <td><?php echo $row["email"]?></td>
         <td><?php echo $row["number_of_children"]?></td>
         <td><?php
         echo ($row["child_birth_year_1"] ? $row["child_birth_year_1"] : '')
         .  ($row["child_birth_year_2"] ? ', ' . $row["child_birth_year_2"] : '')
         .  ($row["child_birth_year_3"] ? ', ' . $row["child_birth_year_3"] : '')
         .  ($row["child_birth_year_4"] ? ', ' . $row["child_birth_year_4"] : '')
         .  ($row["child_birth_year_5"] ? ', ' . $row["child_birth_year_5"] : '')
         .  ($row["child_birth_year_6"] ? ', ' . $row["child_birth_year_6"] : '')
         ?>
         </td>
         <td><?php echo $row["find_out"]?></td>
         <td>
<?php
if (hasRightGroupXOR($_SESSION["UserName"], "VisitorEdit")) {
?>
				<a href="index.php?p=visitor_update&id=<?php echo sha1($row["id"]) ?>" data-uk-tooltip title="Edit Visitor"><i class="fas fa-edit" style="font-size: 1.3em;"></i></a>
        <i class="far fa-trash-alt text-red" onclick="doDelete('<?php echo $row["id"]?>')" data-uk-tooltip title="Delete <?php echo $row['name']; ?>" style="font-size: 1.3em;"></i>
				<a href="#" data-uk-tooltip title="Coming Soon"><i class="fas fa-share" style="font-size: 1.3em;"></i></a>
<?php
}
?>
         </td>
      </tr>
<?php
   }
} else {
?>
      <tr>
         <td colspan="6">No Record Found</td>
      </tr>
<?php
}
?>
   </table>
   </div>
</div>

<form id="frmDelete" method="get" action="">
   <input type="hidden" name="p" id="p" value="visitor_qr_list">
   <input type="hidden" name="del_id" id="del_id" value="">
   <input type="hidden" name="action" id="action" value="DEL">
</form>

<div id="dlgQRCode"></div>
