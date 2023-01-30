
<!--<a href="/">                 
         <span class="btn-qdees"><i class="fa fa-arrow-left" style="margin-right: 20px;"></i>Back</span>
</a>-->
<span style="position: absolute;right: 35px;">
    <span class="page_title"><img src="/images/title_Icons/Defective_Status.png">Stock Adjustment</span>
</span>

<?php
include_once("../mysql.php");

if ($_SESSION["isLogin"]==1) {
   if (($_SESSION["UserType"]=="S") & (hasRightGroupXOR($_SESSION["UserName"], "StockAdjustmentEdit"))) {
?>
<script>
$(document).ready(function () {
   searchAdjustment();
});

function doStockAdjustment() {
   var product_code=$("#product_code").val();
   var effective_date=$("#effective_date").val();
   var adjust_qty=$("#adjust_qty").val();
   var centre_code=$("#centre_code").val();
    var file=$("#file").val();

    //alert(file);

   if ((product_code!="") & (effective_date!="") & (adjust_qty!="") & (centre_code!="")) {
      $.ajax({
         url : "admin/doStockAdjustment.php",
         type : "POST",
         data : "product_code="+product_code+"&effective_date="+effective_date+"&adjust_qty="+adjust_qty+"&centre_code="+centre_code+"&file="+file,
         dataType : "text",
         beforeSend : function(http) {
         },
         success : function(response, status, http) {
             var s=response.split("|");
             //location.reload();
             UIkit.notify(s[1]);
         },
         error : function(http, status, error) {
            UIkit.notify("Error:"+error);
         }
      });
   } else {
      UIkit.notify("Please fill in all fields");
   }
}

function searchAdjustment() {
   var centre_code=$("#centre_code").val();
   var product_code=$("#product_code").val();
   var date_from=$("#date_from").val();
   var date_to=$("#date_to").val();

   $.ajax({
      url : "admin/SearchAdjustment.php",
      type : "POST",
      data : "product_code="+product_code+"&centre_code="+centre_code,
      dataType : "text",
      beforeSend : function(http) {
      },
      success : function(response, status, http) {
         $("#sctAdjustment").html(response);
      },
      error : function(http, status, error) {
         UIkit.notify("Error:"+error);
      }
   });
}
</script>
<div class="uk-margin-top uk-margin-right uk-form" style="margin-top:40px!important;">
   <div class="uk-width-1-1 myheader">
      <h2 class="uk-text-center myheader-text-color">Stock Adjustment</h2>
   </div>
   <div style="overflow: unset;" class="uk-overflow-container">
   <div class="uk-grid">
    <div class="uk-width-medium-5-10">
       <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.4.2/chosen.min.css">
       <table class="uk-table uk-table-small">
          <input type="hidden" name="id" id="id" value="">
          <tbody>
            <tr class="uk-text-small">
             <td class="uk-width-3-10 uk-text-bold">Centre Name</td>
             <td class="uk-width-7-10">
            <select class="uk-width-1-1 chosen-select2" name="centre_code" id="centre_code">
                <option value="">Select</option>
					<?php
					$sql="SELECT * from centre order by kindergarten_name";
					$result=mysqli_query($connection, $sql);
					while ($row=mysqli_fetch_assoc($result)) {
					?>
											<option value="<?php echo $row['centre_code']?>" <?php if ($row["centre_code"]==$edit_row['centre_code']) {echo "selected";}?>><?php echo $row["company_name"]?></option>
					<?php
					}
					?>
										 </select>
             </td>
          </tr>
          <tr class="uk-text-small">
             <td class="uk-width-3-10 uk-text-bold">Product Name:</td>
             <td class="uk-width-7-10"> 
                <select name="product_code" id="product_code" class="uk-width-1-1 chosen-select2">
                <option value="">Select product</option>
               <?php
               $sql="SELECT * from product order by product_code";
               $result=mysqli_query($connection, $sql);
               while ($row=mysqli_fetch_assoc($result)) {
               ?>
               <option value="<?php echo $row['product_code']?>"><?php echo $row['product_name']?></option>
               <?php
               }
               ?>
         </select>
       </td>
          </tr>
          <tr class="uk-text-small">
             <td class="uk-width-3-10 uk-text-bold">Current Qty</td>
             <td>
             <input class="uk-width-1-1" placeholder="" type="text" name="current_qty" id="current_qty" value="" readonly>
             </td>
          </tr>
         
       </tbody>
    </table>
    </div>
    <div class="uk-width-medium-5-10">
            <table class="uk-table uk-table-small">
              
               <tbody>
                 <tr class="uk-text-small">
                  <td class="uk-width-3-10 uk-text-bold">Update Qty</td>
                  <td class="uk-width-7-10">
                  <input class="uk-width-1-1" placeholder="" type="number" name="adjust_qty" id="adjust_qty" value="" onkeyup="if(this.value<0){this.value= this.value * -1}" min="0">
         <!-- <div class="uk-text-muted uk-text-small">Can enter positive or negative number</div> -->
                  </td>
               </tr>
               <tr class="uk-text-small">
                  <td class="uk-width-3-10 uk-text-bold">Effective Date</td>
                  <td class="uk-width-7-10">  <input class="uk-width-1-1" data-uk-datepicker="{format:'YYYY-MM-DD'}" name="effective_date" id="effective_date" placeholder="" value=""> </td>
               </tr>
               <tr class="uk-text-small">
                  <td class="uk-width-3-10 uk-text-bold">Upload file</td>
                  <td>
                  <form class="md-form">
					  <div class="file-field">
						<div class="float-left">
						  <input type="file" name="file" id="file" class="uk-width-1-1"> 
						</div>
						 <!--<div class="uk-text-muted uk-text-small">Upload file here</div>-->
						<div class="file-path-wrapper">
						  
						</div>
					  </div>
					</form>
					<script>
						$('#file').bind('change', function() {
						var file_path = $(this).val();
						if (file_path != "") {
							if (this.files[0].size > 10485760) {
								UIkit.notify("Attachment file size too big.");
							}

							var file = file_path.split(".");
							var count = file.length;
							var index = count > 0 ? (count - 1) : 0;
							var file_ext = file[index].toLowerCase();

							if ((file_ext !="pdf") && (file_ext !="jpg") && (file_ext != "png") && (file_ext != "jpeg")) {
								$('#file').val('');
								UIkit.notify("Please select PDF, JPG, JPEG or PNG file only");
							}
						}
					});
					</script>
                  </td>
               </tr>
              
            </tbody>
         </table>
         </div>
 </div>
 <div class="uk-text-center">
         <!-- <button class="uk-button uk-button-primary">Save</button> -->
         <a class="uk-button uk-button-primary aa" onclick="doStockAdjustment()">Adjust</a>
      </div>

</div>
</div>
<div class="uk-form uk-margin-top uk-margin-right">
   <div class="uk-width-1-1 myheader">
      <h2 class="uk-text-center myheader-text-color">Searching</h2>
   </div>
   <div style="overflow: unset;" class="uk-overflow-container">
   <div class="uk-grid uk-grid-small">
		<div class="uk-width-medium-3-10">
		   <input type="hidden"  id="hfCenterCode" name="centre_code">
		   <input list="centre_code1" id="company_name" name="company_name" value="" placeholder="Select Centre Name" style="width:100%;">
			<datalist class="form-control" id="centre_code1" style="display:none;">				
			  <!--<option value="ALL" <?php // echo $centre_code == 'ALL' ? 'selected' : '' 
					?>>ALL</option>-->
						
						<?php
							$centre_code= $_GET["centre_code"];
							$sql = "SELECT * from centre order by centre_code";
							$result_centre = mysqli_query($connection, $sql);                        
					
						   while ($row=mysqli_fetch_assoc($result_centre)) {
						  ?>
	<option value="<?php echo $row['company_name']?>" <?php if($_GET["centre_code"]==$row["centre_code"]){ echo 'selected';
	}?>> <?php echo $row["centre_code"]
																				?></option>
						<?php
						   }
						  ?>
			</datalist>
			 <script>
				  var objCompanyName = document.getElementById('company_name');
				  $(document).on('change', objCompanyName, function(){
				   // console.log("options[i].text")
					  var options = $('datalist')[0].options;
					  for (var i=0;i<options.length;i++){
					 // console.log($(objCompanyName).val())
						if (options[i].value == $(objCompanyName).val()) 
						  {
							$("#hfCenterCode").val(options[i].text);
							break;
						  }
					  }
				  });
			 
				</script>  
		</div>
      <div class="uk-width-medium-3-10">
         <input type="hidden"  id="hfProductCode" name="product_code">
		   <input list="product_code2" id="product_name" name="product_name" value="" placeholder="Select Product Name" style="width:100%;"> 
			<datalist class="form-control" id="product_code2" style="display:none;">				
			  <!--<option value="ALL" <?php // echo $centre_code == 'ALL' ? 'selected' : '' 
					?>>ALL</option>-->
						
						<?php
							$sql="SELECT * from product order by product_code";
							$result=mysqli_query($connection, $sql);
							while ($row=mysqli_fetch_assoc($result)) {
							?>
													<option <?php if ($row['product_code']==$edit_row['product_code']) {echo "selected";}?> value="<?php echo $row["product_name"]?>"><?php $product_code = explode("((--",$row['product_code'])[0]; echo $product_code;?></option>
							<?php
							}
							?>
						 
			</datalist>	
			<script>
				  var objProductName = document.getElementById('product_name');
				  $(document).on('change', objProductName, function(){
					  var options = $('datalist')[0].options;
					  for (var i=0;i<options.length;i++){
						if (options[i].value == $(objProductName).val()) 
						  {
							$("#hfProductCode").val(options[i].text);
							break;
						  }
					  }
				  });
			 
			</script>	
			   
  <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.4.2/chosen.jquery.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.4.2/chosen.min.css">
    <script>
    $('.chosen-select2').chosen({
        search_contains: true
    }).change(function(obj, result) {
        // console.debug("changed: %o", arguments);
        // console.log("selected: " + result.selected);
    });
	</script>
      </div>
      <div class="uk-width-medium-1-10">
         <input class="uk-width-1-1" data-uk-datepicker="{format:'YYYY-MM-DD'}" name="date_from" id="date_from" placeholder="Date From" value="">
      </div>
      <div class="uk-width-medium-1-10">
         <input class="uk-width-1-1" data-uk-datepicker="{format:'YYYY-MM-DD'}" name="date_to" id="date_to" placeholder="Date To" value="">
      </div>
      <div class="uk-width-medium-2-10">
         <a onclick="searchAdjustment();" class="uk-button uk-width-1-1">Search</a>
      </div><br><br>
      <div class="uk-text-muted">* List latest 50 records, newest first</div>
   </div>










</div>
</div>
<br>
<div class="uk-width-1-1 myheader">
      <h2 class="uk-text-center myheader-text-color">Listing</h2>
   </div>
   <div class="uk-overflow-container">
<div id="sctAdjustment"></div>
</div>
<?php
   }
}
?>
<style>
.uk-button.uk-button-primary.aa{
   margin-top: 20px
}
</style>