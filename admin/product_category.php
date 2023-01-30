<style type="text/css">
    #mydatatable_length{
display: none;
}

 #mydatatable_filter{
display: none;
}

/*#mydatatable_paginate{float:initial;text-align:center}*/
#mydatatable_paginate .paginate_button{display: inline-block; min-width: 16px; padding: 3px 5px; line-height: 20px; text-decoration: none; -moz-box-sizing: content-box; box-sizing: content-box; text-align: center; background: #f7f7f7; color: #444444; border: 1px solid rgba(0, 0, 0, 0.2); border-bottom-color: rgba(0, 0, 0, 0.3); background-origin: border-box; background-image: -webkit-linear-gradient(top, #ffffff, #eeeeee); background-image: linear-gradient(to bottom, #ffffff, #eeeeee); text-shadow: 0 1px 0 #ffffff;margin-left: 3px;
    margin-right: 3px}
#mydatatable_paginate .paginate_button.current{background: #009dd8; color: #ffffff!important; border: 1px solid rgba(0, 0, 0, 0.2); border-bottom-color: rgba(0, 0, 0, 0.4); background-origin: border-box; background-image: -webkit-linear-gradient(top, #00b4f5, #008dc5); background-image: linear-gradient(to bottom, #00b4f5, #008dc5); text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.2);}
#mydatatable_filter{width:100%}
#mydatatable_filter label{width:100%;display:inline-flex}
#mydatatable_filter label input{height: 30px;width: 100%; padding: 4px 6px; border: 1px solid #dddddd; background: #ffffff; color: #444444; -webkit-transition: all linear 0.2s; transition: all linear 0.2s; border-radius: 4px;}
</style>

<?php
session_start();

if ($_SESSION["isLogin"]==1) {
   if (($_SESSION["UserType"]=="S") & (hasRightGroupXOR($_SESSION["UserName"], "ProductEdit|ProductView"))) {
      include_once("mysql.php");
      foreach ($_GET as $key=>$value) {
         $key=$value;
      }
$mode=$_GET["mode"];
$get_sha1_fee_id=$_POST['fee_id'];
$company_name=$_POST['company_name'];
if($company_name=="Q-dees Holdings Sdn. Bhd."){
	$company_id="Q001";
}else if($company_name=="Tenations Global Sdn. Bhd."){
	$company_id="Q002";
}else{
	$company_id="Q003";
}
$state= $_POST['state'];
//echo $_POST['fees_structure'];
$state = implode (", ", $state); 
      if (isset($mode) and $mode=="EDIT" && $get_sha1_fee_id!="") {
		  if(isset($_POST['fee_id'],$_POST['category_name'],$_POST['company_name'],$_POST['category_description'])) {
         $category_name = addslashes($_POST['category_name']);
         $category_description = addslashes($_POST['category_description']);
        $save_sql="update product_category set 
			country='".$_POST['country']."', 
			state='$state', 
			category_name='".$category_name."', 
			company_name='".$_POST['company_name']."', 
			company_id='$company_id', 
			category_description='".$category_description."',
			initialtermly='".$_POST['initialtermly']."'";
		$save_sql .= "where sha1(id)='$get_sha1_fee_id'";
		$result=mysqli_query($connection, $save_sql);
		if($result){
			//echo "<script type='text/javascript'>window.top.location='index.php?p=fee&msg=Record Updated';</script>";
			$msg="Record Updated";
		}else{
			$msg="Failed to save data";
		}
      
		  }
	  }
	  if (isset($mode) and $mode=="SAVE") {
      $category_name = addslashes($_POST['category_name']);
      $category_description = addslashes($_POST['category_description']);

		   if(isset($_POST['country'],$_POST['category_name'],$_POST['company_name'],$_POST['category_description'])) {
		   $save_sql="INSERT INTO product_category (country,state,category_name,company_name,company_id,category_description,initialtermly) VALUES ('".$_POST['country']."','$state','".$category_name."','".$_POST['company_name']."','$company_id','".$category_description."','".$_POST['initialtermly']."')";
		 //echo $save_sql;
			$result=mysqli_query($connection, $save_sql);
			 //print_r($result);
		if($result){
			//echo "<script type='text/javascript'>window.top.location='index.php?p=fee&msg=Record saved';</script>";
			//$msg="Record saved";
			$msg="Your respond has been submitted successfully";
		}else{
			$msg="Failed to save data";
		}
		   }
	  }
	  
	  $get_sha1_id=$_GET['id'];
	  if (isset($mode) and $mode=="DEL") {
		  $save_sql="DELETE from `product_category` where sha1(id)='$get_sha1_id'";
			$result=mysqli_query($connection, $save_sql);
		if($result){
			//echo "<script type='text/javascript'>window.top.location='index.php?p=fee&msg=Record saved';</script>";
			$msg="Record deleted";
		}else{
			$msg="Failed to save data";
		}
		   
	  }
	  
	  $edit_sql="SELECT * from `product_category` where sha1(id)='$get_sha1_id'";
	 
      $result=mysqli_query($connection, $edit_sql);
      $edit_row=mysqli_fetch_assoc($result);
?>
<script>
function doDeleteRecord(id) {
   UIkit.modal.confirm("<h2>Are you sure to continue?</h2>", function () {
      $("#id").val(id);
      $("#frmDeleteRecord").submit();
   });
}
</script>
<!--<a href="index.php?p=order_status_pg1">                 
             <span class="btn-qdees"><i class="fa fa-arrow-left" style="margin-right: 20px;"></i>Back</span>
</a>-->
<style>
.page_title {
    position: absolute;
    right: 34px;
}
.uk-margin-right {
    margin-top: 40px;
}
.form_1{
	padding: 9px 15px;
	border-radius: 10px;
	box-shadow: 0px 2px 3px 0px #00000021 !important;
	font-size: 1.2rem;
	font-weight: bold;
	background: #fff;
	cursor: pointer;
}
.form_2{
	padding: 9px 15px;
	border-radius: 10px;
	box-shadow: 0px 2px 3px 0px #00000021 !important;
	font-size: 1.2rem;
	font-weight: bold;
	background: #fff;
}
</style>      
	<a href="./index.php?p=product_category"><span  id="form_1" class="form_1">Form</span></a>            
	<a style="margin-right: 82%;" href="./index.php?p=product"><span id="form_2" class="form_2">Product</span></a>
<span>
    <span class="page_title"><img src="/images/title_Icons/Visitor Reg.png">Product Form</span>
</span>
<div class="uk-margin-right">
   <div class="uk-width-1-1 myheader">
      <h2 class="uk-text-center myheader-text-color">Product Form</h2>
   </div>
   <div class="uk-form uk-form-small">
   
   <?php
if (($_SESSION["UserType"]=="S") & (hasRightGroupXOR($_SESSION["UserName"], "ProductEdit"))) {
	if($_GET["mode"]=="EDIT" && isset($_GET["id"])){
?>
   <form name="frmProductcategory" id="frmProductcategory" method="post" action="index.php?p=product_category&mode=EDIT">
<?php
	}else{
?>
<form name="frmProductcategory" id="frmProductcategory" method="post" action="index.php?p=product_category&mode=SAVE">
<?php
	}
}
?>
      <div class="uk-grid">
         <div class="uk-width-medium-5-10">
            <table class="uk-table uk-table-small">
				<tr class="uk-text-small">
				   <td class="uk-width-3-10 uk-text-bold">Country<span class="text-danger">*</span>:</td>
				   <td class="uk-width-7-10">
					  <select name="country" id="country" class="uk-width-1-1">
						 <option value="">Select</option>
						 <?php
						 $sql = "SELECT * from codes where module='COUNTRY' order by code";
						 $result = mysqli_query($connection, $sql);
						 while ($row = mysqli_fetch_assoc($result)) {
						 ?>
							<option value="<?php echo $row["code"] ?>" <?php if ($row["code"] == $edit_row["country"]) {
																			echo "selected";
																		 } ?>><?php echo $row["code"] ?></option>
						 <?php
						 }
						 ?>
					  </select>

					  <span id="validationCountry" style="color: red; display: none;">Please select Country</span>
				   </td>
				</tr>
				<tr class="uk-text-small">
				   <td class="uk-width-3-10 uk-text-bold">State<span class="text-danger">*</span>:</td>
				   <td class="uk-width-7-10">
					  <select name="state[]" id="state" class="uk-width-1-1" multiple>
						 <?php
						 $stateArray = explode(', ', $edit_row['state']);
						 if ($edit_row["country"] != "") {
						 ?>
							<?php
							$sql = "SELECT * from codes where country='" . $edit_row["country"] . "' and module='STATE' order by code";
							$result = mysqli_query($connection, $sql);
							while ($row = mysqli_fetch_assoc($result)) {
							?>
							   <option value="<?php echo $row['code'] ?>" <?php if (in_array($row["code"], $stateArray)) {
																			   echo "selected";
																			} ?>><?php echo $row["code"] ?></option>
							<?php
							}
						 } else {
							?>
							<option value="">Please Select Country First</option>
						 <?php
						 }
						 ?>

					  </select>

					  <span id="validationState" style="color: red; display: none;">Please select State</span>
				   </td>
				</tr>
				<script>
                           $(document).ready(function() {
                              $("#country").change(function() {
                                 var country = $("#country").val();
                                 $.ajax({
                                    url: "admin/get_state_not_select.php",
                                    type: "POST",
                                    data: "country=" + country,
                                    dataType: "text",
                                    beforeSend: function(http) {},
                                    success: function(response, status, http) {
                                       if (response != "") {
                                          $("#state").html(response);
                                       } else {
                                          $("#state").html("<select name='state' id='state' class='uk-width-1-1'><option value=''>Please select a country</option></select>");
                                          UIkit.notify("No state found in " + country);
                                       }
                                    },
                                    error: function(http, status, error) {
                                       UIkit.notify("Error:" + error);
                                    }
                                 });
                              });
                           });
                        </script>
               <tr class="uk-text-small">
                  <td class="uk-width-3-10 uk-text-bold">Form Name : </td>
                  <td class="uk-width-7-10">
                     <input class="uk-width-1-1" type="text" name="category_name" id="category_name" value="<?php echo $edit_row['category_name']?>">
                     <input type="hidden" id="fee_id" name="fee_id" value="<?php echo $_GET["id"]; ?>"/>
					  <span id="validationCategoryName"  style="color: red; display: none;">Please select Form Name</span>
                  </td>
               </tr>
               
            </table>
         </div>
         <div class="uk-width-medium-5-10">
            <table class="uk-table uk-table-small">
				<tr class="uk-text-small">
                  <td class="uk-width-3-10 uk-text-bold">Company Name : </td>
                  <td class="uk-width-7-10">
                     <select name="company_name" id="company_name" class="uk-form-small uk-width-1-1">
						 <option value="">Select</option>
                                 <?php
                                $sql = "SELECT * from codes where module='CATEGORY' and parent='' and code !=''";
                                 $result = mysqli_query($connection, $sql);
                                 while ($row = mysqli_fetch_assoc($result)) {
                                 ?>
                                    <option value="<?php echo $row['code'] ?>" <?php if ($row['code'] == $edit_row['company_name']) {
                                                                                    echo "selected";
                                                                                 } ?>><?php echo $row['code'] ?></option>
                                 <?php
                                 }
                                 ?>
						<?php
                                 $sql = "SELECT * from codes where module='CATEGORY' and company != ''";
                                 $result = mysqli_query($connection, $sql);
                                 while ($row = mysqli_fetch_assoc($result)) {
                                 ?>
                                    <option value="<?php echo $row['company'] ?>" <?php if ($row['company'] == $edit_row['category']) {
                                                                                       echo "selected";
                                                                                    } ?>><?php  echo $row['company'] ?></option>
                                 <?php
                                 }
                                 ?>
								<!-- <option value="Q-dees Holdings Sdn. Bhd." <?php// if($edit_row['company_name']=='Q-dees Holdings Sdn. Bhd.') {echo 'selected';}?>>Q-dees Holdings Sdn. Bhd.</option>
                        <option value="Tenations Global Sdn. Bhd." <?php// if($edit_row['company_name']=='Tenations Global Sdn. Bhd.') {echo 'selected';}?>>Tenations Global Sdn. Bhd.</option>
                        <option value="Mind Spectrum" <?php// if($edit_row['company_name']=='Mind Spectrum') {echo 'selected';}?>>Mind Spectrum</option>-->
			
                     </select>
					  <span id="validationCompanyName"  style="color: red; display: none;">Please select Company Name</span>
                  </td>
               </tr>  
                <tr class="uk-text-small">
                  <td class="uk-width-3-10 uk-text-bold">Category Description : </td>
                  <td class="uk-width-7-10">
                     <input class="uk-width-1-1" type="text" name="category_description" id="category_description" value="<?php echo $edit_row['category_description']?>">
					 <span id="validationcatdescription"  style="color: red; display: none;">Please input Category Description</span>
                  </td>
				  
               </tr>
			   <tr class="uk-text-small">
                  <td class="uk-width-3-10 uk-text-bold">Initial/Termly : </td>
                  <td class="uk-width-7-10">
                     <select name="initialtermly" id="initialtermly" class="uk-form-small uk-width-1-1">
						 <option value="">Select</option>        
						 <option <?php if($edit_row['initialtermly']=='initial') {echo 'selected';}?> value="initial">Initial</option>        
						 <option <?php if($edit_row['initialtermly']=='termly') {echo 'selected';}?> value="termly">Termly</option>        
			
                     </select>
					  <span id="validationCompanyName"  style="color: red; display: none;">Please select Company Name</span>
                  </td>
               </tr>
            </table>
         </div>
      </div>
      <br>
            <div class="uk-text-center">
<?php
if (($_SESSION["UserType"]=="S") & (hasRightGroupXOR($_SESSION["UserName"], "ProductEdit"))) {
?>
         <button class="uk-button uk-button-primary">Save</button>
<?php
}
?>
      </div>
<?php
if (($_SESSION["UserType"]=="S") & (hasRightGroupXOR($_SESSION["UserName"], "ProductEdit"))) {
?>
   </form>
<?php
}
?>
   </div>
   <br>

   <div class="uk-width-1-1 myheader">
      <h2 class="uk-text-center myheader-text-color">Searching</h2>
   </div>

   <form class="uk-form" name="frmCentreSearch" id="frmCentreSearch" method="get">
      <input type="hidden" name="mode" id="mode" value="BROWSE">
      <input type="hidden" name="p" id="p" value="product_category">


      <div class="uk-grid">
         <div style="padding-right: 6px;" class="uk-width-2-10 uk-text-small">
			<input style="width: 100%;" type="text" name="country" id="country" value="<?php echo $_GET['country']?>" placeholder="Country"> 
         </div>
		 <div style="padding-left: 6px;padding-right: 6px;" class="uk-width-2-10 uk-text-small">
			<input style="width: 100%;" type="text" name="st" id="st" value="<?php echo $_GET['st']?>" placeholder="State"> 
         </div>
		<div style="padding-left: 6px;padding-right: 6px;" class="uk-width-2-10 uk-text-small">
			<input style="width: 100%;" type="text" name="sb" id="sb" value="<?php echo $_GET['sb']?>" placeholder="Category Name"> 
         </div>
		<div style="padding-left: 6px;padding-right: 6px;" class="uk-width-2-10 uk-text-small">
			<input style="width: 100%;" type="text" name="pp" id="pp" value="<?php echo $_GET['pp']?>" placeholder="Category Description"> 
         </div> 
         <div class="uk-width-2-10">
            <button class="uk-button uk-width-1-1">Search</button>
         </div>
      </div>
   </form><br>
<div class="uk-margin-right">
   <div class="uk-width-1-1 myheader">
      <h2 class="uk-text-center myheader-text-color">LISTING</h2>
   </div>
 <div class="uk-overflow-container">
                <table class="uk-table" id='mydatatable'>
                  <thead>
                    <tr class="uk-text-bold uk-text-small">
                        <td>Country</td>
                        <td>State</td>
                        <td>Category Name</td>
                        <td>Company Name</td>
                        <td>Category Description</td>
                        <td style="width: 120px;">Action</td>
                    </tr>
                  </thead>

                  <tbody>
				  <?php
				 $country=$_GET['country'];
				 $st=$_GET['st'];
				 $sb=$_GET['sb'];
				 $pp=$_GET['pp'];
             $sql = "SELECT * from product_category where 1=1 ";
			 if($country!=""){
				  $sql=$sql."and country like '%$country%' "; 
			  }
			  if($st!=""){
				  $sql=$sql."and state like '%$st%' ";
			  }
			  if($sb!=""){
				  $sql=$sql."and category_name like '%$sb%' "; 
			  }
			  if($pp!=""){
				  $sql=$sql."and category_description like '%$pp%' "; 
			  }
	   //echo $sql; 
            $result = mysqli_query($connection, $sql);
						
            $num_row = mysqli_num_rows($result);
			//$m_row = mysqli_fetch_assoc($result) 
			//print_r($num_row); die;
               
                ?>
				
				
                    <?php

                    if ($num_row>0) {
                        while ($browse_row=mysqli_fetch_assoc($result)) {
                            $sha1_id=sha1($browse_row["id"]);
                            ?>
                            <tr class="uk-text-small">
                                <td><?php echo $browse_row["country"]?></td>
                                <td><?php echo $browse_row["state"]?></td>
                                <td><?php echo $browse_row["category_name"]?></td>
                                <td><?php echo $browse_row["company_name"]?></td>
                               
                                <td><?php echo $browse_row["category_description"]?></td>
                                <td style="width:120px">
                                    <?php
                                   
                                          ?>
                                          <a href="index.php?p=product_category&id=<?php echo $sha1_id?>&mode=EDIT" data-uk-tooltip title="Edit"><img src="images/edit.png"></a>
                                          &nbsp; &nbsp; 
                                          <a onclick="doDeleteRecord('<?php echo $sha1_id?>')" href="#" id="btnDelete"><img src="images/delete.png"></a>
                                      
                                </td>  
                            </tr>
                            <?php
                        }
                    } else {
                        echo "<tr><td colspan='8'>No Record Found</td></tr>";
                    }
                    ?>
                  </tbody>
                </table>
            </div>
</div>
<form name="frmDeleteRecord" id="frmDeleteRecord" method="get">
   <input type="hidden" name="p" value="product_category">
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

<script type="text/javascript">
$("#frmProductcategory").submit(function(e){
    var country=$("#country").val();
    var state=$("#state").val();
    var category_name=$("#category_name").val();
    var company_name=$("#company_name").val();
    var category_description=$("#category_description").val();

    if (!country || !state || !category_name || company_name=="" || !category_description ) {
	 e.preventDefault();
		if (!country) {
            $('#validationCountry').show();
        }else{
            $('#validationCountry').hide();
        }

         if (!state) {
            $('#validationState').show();
        }else{
            $('#validationState').hide();
        }
		if (!category_name) {
            $('#validationCategoryName').show();
        }else{
            $('#validationCategoryName').hide();
        }

         if (company_name=="") {
            $('#validationCompanyName').show();
        }else{
            $(validationCompanyName).hide();
        }
         if (!category_description) {
            $('#validationcatdescription').show();
        }else{
            $('#validationcatdescription').hide();
        }
         
        return false;
  }
})
  $(document).ready(function(){
   $('#mydatatable').DataTable( {
    language: {
       // searchPlaceholder: "Search Class Status",
      //  search: ""
    }, "bInfo" : false,
} );
}); 
</script>