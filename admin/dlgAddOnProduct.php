<?php
session_start();
include_once("../mysql.php");
include_once("functions.php");
foreach ($_POST as $key=>$value) {
	$$key=mysqli_real_escape_string($connection, $value);
}

function getSSIDByStudentCode($sha1_student_code) {
   global $connection;

   $sql="SELECT * from student where sha1(student_code)='$sha1_student_code'";
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);
   return sha1($row["id"]);
}

$ssid=getSSIDByStudentCode($student_code);
?>

<script>
function removeAOFromTempBusket(bid, product_code, student_code) {
   UIkit.modal.confirm("<h2>Are you sure to continue?</h2>", function () {
      var allocation_id=$("#allocation_id").val();

      $.ajax({
         url : "admin/removeFromTempBusket.php",
         type : "POST",
         data : "product_code="+product_code+"&student_code="+student_code+"&bid="+bid,
         dataType : "text",
         beforeSend : function(http) {
         },
         success : function(response, status, http) {
            var s=response.split("|");

            if (s[0]=="1") {
               getAOTempBusket();
            }

            if (s[0]=="0") {
               UIkit.notify(s[1]);
            }
         },
         error : function(http, status, error) {
            UIkit.notify("Error:"+error);
         }
      });
   })
}

function getAOTempBusket() {
   var ssid='<?php echo $ssid?>';

   $.ajax({
      url : "admin/getAOTempBusketContent.php",
      type : "POST",
      data : "ssid="+ssid,
      dataType : "text",
      beforeSend : function(http) {
      },
      success : function(response, status, http) {
         $("#lstAOBusket").html(response);
      },
      error : function(http, status, error) {
         UIkit.notify("Error:"+error);
      }
   });
}

function doAOSearch() {
   var category=$("#category").val();
   var s=$("#sao").val();
   var allocation_id=$("#allocation_id").val();
   var student_code='<?php echo $student_code?>';
   $.ajax({
      url : "admin/getAddOnProductList.php",
      type : "POST",
      data : "category="+category+"&s="+s+"&allocation_id="+allocation_id+"&student_code="+student_code,
      dataType : "text",
      beforeSend : function(http) {
      },
      success : function(response, status, http) {
         $("#lstAOProduct").html(response);
      },
      error : function(http, status, error) {
         UIkit.notify("Error:"+error);
      }
   });
}

function doAOPutInBusket() {
   UIkit.modal.confirm("<h2>Are you sure to continue?</h2>", function () {
      $.ajax({
         url : "admin/doAOPutInBusket.php",
         type : "POST",
         data : "",
         dataType : "text",
         beforeSend : function(http) {
         },
         success : function(response, status, http) {
            var s=response.split("|");
            if (s[0]=="1") {
               window.location.reload();
            }

            if (s[0]=="0") {
               UIkit.notify(s[1]);
            }
         },
         error : function(http, status, error) {
            UIkit.notify("Error:"+error);
         }
      });
   });
}

$(document).ready(function () {
   
   var currentYear = "<?php echo $_SESSION['Year']; ?>";
   setTimeout(function(){ 
      $("#year9").val(currentYear);
      $("#year9").change();
   }, 100); 
   
   doAOSearch();
   
   $("#btnAOSearch").click(function (event) {
      event.preventDefault();
      doAOSearch();
   });
   
   getAOTempBusket();   
});

</script>

<div class="uk-grid">
   <div class="uk-width-1-2 uk-form">
      <div class="uk-text-center myheader">
         <h2 class="myheader-text-color">Search Product</h2>
      </div>
      <select name="category" id="category" class="uk-form-small" hidden>
         <option value="">Select</option>
<?php
$sql="SELECT * from codes where module='CATEGORY' and parent='' order by code";
$result=mysqli_query($connection, $sql);
while ($row=mysqli_fetch_assoc($result)) {
?>
         <option value="<?php echo $row['code']?>"><?php echo $row["code"]?></option>
<?php
}
?>
      </select>
      <table class="uk-table uk-table-small uk-form uk-form-small">
   
   <tr style="background-color:lightgrey">
         <td colspan="2">Year Selection</td>
      </tr>
	<tr>
		<td style="padding-top: 6px;">
		<select name="year" id="year9">
			<option value="">Select Year</option>
			<?php echo getYearOptionList(); ?>
		</select>
		</td>
	</tr>
</table>
      <input name="s" id="sao" value="" placeholder="Product Code/Name" class="uk-form-small">
      <a class="uk-button uk-button-small" id="btnAOSearch">Search</a>
      <div id="lstAOProduct"></div>
   </div>
   <div class="uk-width-1-2 uk-form">
      <div class="uk-text-center myheader">
         <h2 class="myheader-text-color">Add-On Basket Content</h2>
      </div>
      <div id="lstAOBusket"></div>
      <a onclick="doAOPutInBusket()" class="uk-button uk-button-small uk-button-primary uk-align-right">Put in Basket</a>
      <a class="uk-button uk-button-small uk-align-right" onclick="$('#addon-product-dialog').dialog('close')">Cancel</a>
   </div>
</div>