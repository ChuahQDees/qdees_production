<!--<a href="/">                 
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
</style>
<span>
    <span class="page_title"><img src="/images/title_Icons/Visitor Reg.png">Centre</span>
</span>
<style type="text/css">
.check_sa input[type="checkbox"]{
	margin: 0 10px 5px 0;
}
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
include_once("admin/functions.php");

function getStatus($status) {
   if ($status!="") {
      switch ($status) {
         case "C" : return "Closed"; break;
         case "A" : return "Active"; break;
         case "S" : return "Sell Off"; break;
         case "T" : return "Transferred"; break;
         case "Pending" : return "Pending"; break;
         case "O" : return "Others"; break;
      }
   } else {
      return "";
   }
}

if ($_SESSION["isLogin"]==1) {
   if (($_SESSION["UserType"]=="S") & (hasRightGroupXOR($_SESSION["UserName"], "CentreEdit|CentreView"))) {
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

      include_once("centre_func.php");
?>

<script>
function dlgCentreFranchiseeCompany(centre_code, mode) {
   if (centre_code=="") {
      centre_code=$("#centre_code").val();
   }

   if (centre_code!="") {
      $.ajax({
         url : "admin/dlgCentreFranchiseeCompany.php",
         type : "POST",
         data : "centre_code="+centre_code+"&mode="+mode,
         dataType : "text",
         beforeSend : function(http) {
         },
         success : function(response, status, http) {
            $("#dlgCentreFranchiseeName").html("");

            $("#dlgCentreFranchiseeCompany").html(response);
            $("#dlgCentreFranchiseeCompany").dialog({
               dialogClass:"no-close",
               title:"Centre Company Details",
               modal:true,
               height:'auto',
               width:'auto',
            });
         },
         error : function(http, status, error) {
            UIkit.notify("Error:"+error);
         }
      });
   } else {
      UIkit.notify("Please select a Centre");
   }
}

function dlgCentreFranchiseeName(centre_code, mode) {
   if (centre_code=="") {
      centre_code=$("#centre_code").val();
   }

   if (centre_code!="") {
      $.ajax({
         url : "admin/dlgCentreFranchiseeName.php",
         type : "POST",
         data : "centre_code="+centre_code+"&mode="+mode,
         dataType : "text",
         beforeSend : function(http) {
         },
         success : function(response, status, http) {
            $("#dlgCentreFranchiseeCompany").html("");

            $("#dlgCentreFranchiseeName").html(response);
            $("#dlgCentreFranchiseeName").dialog({
               dialogClass:"no-close",
               title:"Name Details",
               modal:true,
               height:'auto',
               width:'auto',
            });
         },
         error : function(http, status, error) {
            UIkit.notify("Error:"+error);
         }
      });
   } else {
      UIkit.notify("Please select a Centre");
   }
}

function doDeleteRecord(id) {
   UIkit.modal.confirm("<h2>Are you sure to continue?</h2>", function () {
      $("#id").val(id);
      $("#frmDeleteRecord").submit();
   });
}

function ajaxUpdateName() {
   var centre_code=$("#centre_code").val();

   $.ajax({
      url : "admin/updateName.php",
      type : "POST",
      data : "centre_code="+centre_code,
      dataType : "text",
      beforeSend : function(http) {
      },
      success : function(response, status, http) {
         $("#centre_franchisee_name_id").html(response);
      },
      error : function(http, status, error) {
         UIkit.notify("Error:"+error);
      }
   });
}

function ajaxUpdateCompany() {
   var centre_code=$("#centre_code").val();

   $.ajax({
      url : "admin/updateCompany.php",
      type : "POST",
      data : "centre_code="+centre_code,
      dataType : "text",
      beforeSend : function(http) {
      },
      success : function(response, status, http) {
         $("#centre_franchisee_company_id").html(response);
      },
      error : function(http, status, error) {
         UIkit.notify("Error:"+error);
      }
   });
}

function ajaxGetCentreCode(master_code) {
   $.ajax({
      url : "admin/getCentreCode.php",
      type : "POST",
      data : "master_code="+master_code,
      dataType : "text",
      beforeSend : function(http) {
      },
      success : function(response, status, http) {
         $("#centre_code").val(response);
      },
      error : function(http, status, error) {
         UIkit.notify("Error:"+error);
      }
   });
}

function getCode() {
   var master_code=$("#upline").val();
   var centre_code=$("#centre_code").val();

   if (centre_code=="") {
      ajaxGetCentreCode(master_code);
   } else {
      UIkit.modal.confirm("<h2>Centre code has value, are you sure to overwrite it?</h2>", function () {
         ajaxGetCentreCode(master_code);
      });
   }
}

$(document).ready(function(){
  $("#frmCentre").submit(function(e){
    
    var centre_code=$("#centre_code").val();
    var company_name=$("#company_name").val();
    var kindergarten_name=$("#kindergarten_name").val();
    var upline=$("#upline").val();
    var country=$("#country").val();
    var state=$("#state").val();
    //var status_center=$("#status_center").val();
    var pic=$("#pic").val();
    var year_of_commencement=$("#year_of_commencement").val();
    var status=$("#status").val();
	// var subjectError=true;
  // $(".subject").each(function() {
		// if($(this).is(':checked')){
			// subjectError=false;
		// }
	// });
	
		
    if (!upline || !centre_code || !company_name || !kindergarten_name  || !country || !state || status=="") {

   e.preventDefault();
    alert("Please fill up mandatory fields marked *");

   if (!centre_code) {
            $('#validationCentreCode').show();
        }else{
            $('#validationCentreCode').hide();
        }

         if (!company_name) {
            $('#validationCompanyName').show();
        }else{
            $('#validationCompanyName').hide();
        }

         if (!kindergarten_name) {
            $('#validationKindergartenName').show();
        }else{
            $('#validationKindergartenName').hide();
        }

         if (!upline) {
            $('#validationUpline').show();
        }else{
            $('#validationUpline').hide();
        }

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
		
		if(subjectError){
		$('#validationSubject').show();
        }else{
            $('#validationSubject').hide();
        }
		
         
		if (status=="") {
            $('#validationStatus').show();
        }else{
            $('#validationStatus').hide();
        }
        return false;
  }
 
  });
});
</script>

<div class="uk-margin-right">
   <div class="uk-width-1-1 myheader">
      <h2 class="uk-text-center myheader-text-color">Centre Information</h2>
   </div>
   <div class="uk-form uk-form-small">
<?php
if (($_SESSION["UserType"]=="S") & (hasRightGroupXOR($_SESSION["UserName"], "CentreEdit"))) {
?>
   <form name="frmCentre" id="frmCentre" method="post" action="index.php?p=centre&pg=<?php echo $pg?>&mode=SAVE" enctype="multipart/form-data">
<?php
}
?>
      <div class="uk-grid">
         <div class="uk-width-medium-5-10">
            <table class="uk-table uk-table-small">
				 <tr class="uk-text-small">
                  <td class="uk-width-3-10 uk-text-bold">Master Code<span class="text-danger">*</span>: </td>
                  <td class="uk-width-7-10">
                     <select name="upline" id="upline" class="uk-width-1-1">
                        <option value="">Select</option>
<?php
$sql="SELECT * from `master` order by master_code";
$result=mysqli_query($connection, $sql);
while ($row=mysqli_fetch_assoc($result)) {
?>
                        <option value="<?php echo $row["master_code"]?>" <?php if ($row["master_code"]==$edit_row["upline"]) {echo "selected";}?>><?php echo $row["master_code"]?></option>
<?php
}
?>
                     </select>
                     <span id="validationUpline"  style="color: red; display: none;">Please select Master</span>
                  </td>
               </tr>
			   <tr class="uk-text-small">
                  <td class="uk-width-3-10 uk-text-bold">Country<span class="text-danger">*</span>:</td>
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

                     <span id="validationCountry"  style="color: red; display: none;">Please select Country</span>
                  </td>
               </tr>
               <tr class="uk-text-small">
                  <td class="uk-width-3-10 uk-text-bold">State<span class="text-danger">*</span>:</td>
                  <td class="uk-width-7-10">
                     <select name="state" id="state" class="uk-width-1-1">
<?php
if ($edit_row["country"]!="") {
?>
                        <option value="">Select</option>
<?php
   $sql="SELECT * from codes where country='".$edit_row["country"]."' and module='STATE' order by code";
   $result=mysqli_query($connection, $sql);
   while ($row=mysqli_fetch_assoc($result)) {
?>
                        <option value="<?php echo $row['code']?>" <?php if ($row["code"]==$edit_row['state']) {echo "selected";}?>><?php echo $row["code"]?></option>
<?php
   }
} else {
?>
                        <option value="">Please Select Country First</option>
<?php
}
?>

                     </select>

                     <span id="validationState"  style="color: red; display: none;">Please select State</span>
                  </td>
               </tr>
               <tr class="uk-text-small">
                  <td class="uk-width-3-10 uk-text-bold">Centre Code<span class="text-danger">*</span>: </td>
                  <td class="uk-width-7-10">
                     <div class="uk-grid uk-grid-collapse">
                        <div class="uk-width-7-10">
                           <input class="uk-width-1-1" type="text" name="centre_code" id="centre_code" value="<?php echo $edit_row['centre_code']?>" <?php //if($_GET['mode'] == "EDIT"){ echo "readonly"; } ?> >
                           <span id="validationCentreCode"  style="color: red; display: none;">Please insert File No</span>
                        </div>
                        <div class="uk-width-3-10">
                           <a onclick="getCode()" class="uk-button">Get Code</a>
                        </div>
                     </div>
                     <input type="hidden" name="hidden_centre_code" id="hidden_centre_code" value="<?php echo $edit_row['centre_code']?>">
                     Please select a Master, then click Get Code button
                  </td>
               </tr>
			   <!--<tr class="uk-text-small">
                  <td class="uk-width-3-10 uk-text-bold">Subject<span class="text-danger">*</span>: </td>
                  <td class="uk-width-7-10 check_sa">
                     <input type="checkbox" name="subject[]" value="IE" class="subject">IE<br>
                     <input type="checkbox" name="subject[]" value="BIMP" class="subject">BIMP<br>
                     <input type="checkbox" name="subject[]" value="BIEP" class="subject">BIEP<br>
                     <input type="checkbox" name="subject[]" value="IQ Math" class="subject">IQ Math<br>
                     <span id="validationSubject"  style="color: red; display: none;">Please check Subject</span>
                  </td>
               </tr>-->
			   <script>
				$(document).ready(function () {
					var subject = '<?php echo $edit_row['subject'] ?>';
					 subject = subject.split(', ');
					
					for(var i = 0; i < subject.length; i++)
					{
						$('.subject[value="'+subject[i]+'"]').prop("checked", true);
						
					}
				})
			   </script>
			   <tr class="uk-text-small">
                  <td colspan="2"><a class="uk-button uk-width-1-1" onclick="dlgCentreFranchiseeCompany('<?php echo $edit_row['centre_code']?>', '<?php echo $_GET['mode']?>')">Franchisee Company Name</a></td>
               </tr>
               <tr class="uk-text-small">
                  <td colspan="2"><a class="uk-button uk-width-1-1" onclick="dlgCentreFranchiseeName('<?php echo $edit_row['centre_code']?>', '<?php echo $_GET['mode']?>')">Franchisee Name</a></td>
               </tr>
               <tr class="uk-text-small">
                  <td class="uk-width-3-10 uk-text-bold">Centre Name<span class="text-danger">*</span>: </td>
                  <td class="uk-width-7-10">
                     <input class="uk-width-1-1" type="text" name="company_name" id="company_name" value="<?php echo $edit_row['company_name']?>">
                     <span id="validationCompanyName"  style="color: red; display: none;">Please insert File No</span>
                  </td>
               </tr>
               <tr class="uk-text-small">
                  <td class="uk-width-3-10 uk-text-bold">Kindergarten Name<span class="text-danger">*</span>: </td>
                  <td class="uk-width-7-10">
                     <input class="uk-width-1-1" type="text" name="kindergarten_name" id="kindergarten_name" value="<?php echo $edit_row['kindergarten_name']?>">
                     <span id="validationKindergartenName"  style="color: red; display: none;">Please insert Kindergarten Name</span>
                  </td>
               </tr>
              
               
               
               <tr class="uk-text-small">
                  <td class="uk-width-3-10 uk-text-bold">Centre Category : </td>
                  <td class="uk-width-7-10">
                     <select class="uk-width-1-1" name="status_center" id="status_center">
                        <option value="">Select</option>
                        <?php
                        $sql="SELECT * from centre_status";
                        $result=mysqli_query($connection, $sql);
                        while ($row=mysqli_fetch_assoc($result)) {
                        ?>
                           <option value="<?php echo $row['id'] ?>" <?php if ($row['id']==$edit_row["centre_status_id"]) {echo "selected";}?>><?php echo $row["name"]?></option>
                        <?php
                        }
                        ?>
                     </select>

                     <span id="validationStatusCenter"  style="color: red; display: none;">Please select Centre Category</span>
                  </td>
               </tr>
               <tr class="uk-text-small">
                  <td class="uk-width-3-10 uk-text-bold">OC in Charge : </td>
                  <td class="uk-width-7-10">
                     <input class="uk-width-1-1" type="text" step="0.01" name="pic" id="pic" value="<?php echo $edit_row['pic']?>">
                     <span id="validationPic"  style="color: red; display: none;">Please insert OC in Charge</span>
                  </td>
               </tr>
               <tr class="uk-text-small">
                  <td class="uk-width-3-10 uk-text-bold">Year of Commencement : </td>
                  <td class="uk-width-7-10">
                     <input class="uk-width-1-1" data-uk-datepicker="{format: 'DD/MM/YYYY'}" type="text" name="year_of_commencement" id="year_of_commencement" value="<?php echo convertDate2British($edit_row['year_of_commencement'])?>" autocomplete="off" readonly>
                     <input type="hidden" id="dni_year_of_commencement" value="<?php echo $edit_row['year_of_commencement']?>">
                     <span id="validationYearOfCommencement"  style="color: red; display: none;">Please insert Year of Commencement</span>
                  </td>
               </tr>
               <tr class="uk-text-small">
                  <td class="uk-width-4-10 uk-text-bold">Year of Renewal : </td>
                  <td>
                     <div class="uk-grid uk-grid-collapse">
                        <div class="uk-width-6-10">
                           <input type="text" name="year_of_renewal" class="uk-width-1-1" id="year_of_renewal" value="<?php echo $edit_row["year_of_renewal"];?>">
                        </div>
                        <div class="uk-width-4-10">
                           <a class="uk-button uk-width-1-1" onclick="calcExpiryDate()">Calc Expiry</a>
                        </div>
                     </div>
                  </td>
               </tr>
<script>
function calcExpiryDate() {
   var year_of_commencement=$("#year_of_commencement").val();
   var year_of_renewal=$("#year_of_renewal").val();

   if ((year_of_commencement!="") & (year_of_renewal!="")) {
      $.ajax({
         url : "admin/calcExpiryDate.php",
         type : "POST",
         data : "year_of_commencement="+year_of_commencement+"&year_of_renewal="+year_of_renewal,
         dataType : "text",
         beforeSend : function(http) {
         },
         success : function(response, status, http) {
            $("#expiry_date").val(response);
         },
         error : function(http, status, error) {
            UIkit.notify("Error:"+error);
         }
      });
   } else {
      UIkit.notify("Please fill in Year of Commencement and Year of Renewal");
   }
}
</script>
               <tr class="uk-text-small">
                  <td class="uk-width-3-10 uk-text-bold">Expiry Date : </td>
                  <td class="uk-width-7-10">
                     <input class="uk-width-1-1" type="text" name="expiry_date" id="expiry_date" value="<?php echo convertDate2British($edit_row['expiry_date'])?>" readonly>
                  </td>
               </tr>
               <tr class="uk-text-small">
                  <td class="uk-width-3-10 uk-text-bold">SSM File : </td>
                  <td class="uk-width-7-10" id="dvSSM_file">
                     <input class="uk-width-1-1" type="file" name="SSM_file" id="SSM_file">
                     
<?php
if ($edit_row["SSM_file"]!="") {
?>
<a href="admin/uploads/<?php echo $edit_row['SSM_file']?>" target="_blank">Click to view document</a>
<?php
}
?>
                  </td>
               </tr>
               <tr class="uk-text-small">
                  <td class="uk-width-3-10 uk-text-bold">MOE License File : </td>
                  <td class="uk-width-7-10" id="dvMOE_license_file">
                     <input class="uk-width-1-1" type="file" name="MOE_license_file" id="MOE_license_file">
                     <?php
if ($edit_row["MOE_license_file"]!="") {
?>
<a href="admin/uploads/<?php echo $edit_row['MOE_license_file']?>" target="_blank">Click to view document</a>
<?php
}
?>
                  </td>
               </tr>
               <tr class="uk-text-small">
                  <td class="uk-width-3-10 uk-text-bold">Operator Name : </td>
                  <td class="uk-width-7-10">
                     <input class="uk-width-1-1" type="text" name="operator_name" id="operator_name" value="<?php echo $edit_row['operator_name']?>">
                  </td>
               </tr>
               <tr class="uk-text-small">
                  <td class="uk-width-3-10 uk-text-bold">Operator NRIC : </td>
                  <td class="uk-width-7-10">
                     <input class="uk-width-1-1" type="text" name="operator_nric" id="operator_nric" value="<?php echo $edit_row['operator_nric']?>">
                  </td>
               </tr>
            </table>
         </div>
<script>
$(document).ready(function () {
   $("#country").change(function () {
      var country=$("#country").val();
      $.ajax({
         url : "admin/get_state.php",
         type : "POST",
         data : "country="+country,
         dataType : "text",
         beforeSend : function(http) {
         },
         success : function(response, status, http) {
            if (response!="") {
               $("#state").html(response);
            } else {
               $("#state").html("<select name='state' id='state' class='uk-width-1-1'><option value=''>Please select a country</option></select>");
               UIkit.notify("No state found in "+country);
            }
         },
         error : function(http, status, error) {
            UIkit.notify("Error:"+error);
         }
      });
   });
});
</script>
         <div class="uk-width-medium-5-10">
            <table class="uk-table uk-table-small">
               <tr class="uk-text-small">
                  <td class="uk-width-3-10 uk-text-bold">Operator Contact No. : </td>
                  <td class="uk-width-7-10">
                     <input class="uk-width-1-1" type="text" name="operator_contact_no" id="operator_contact_no" value="<?php echo $edit_row['operator_contact_no']?>">
                  </td>
               </tr>
               <tr class="uk-text-small">
                  <td class="uk-width-3-10 uk-text-bold">Principle Name : </td>
                  <td class="uk-width-7-10">
                     <input class="uk-width-1-1" type="text" name="principle_name" id="principle_name" value="<?php echo $edit_row['principle_name']?>">
                  </td>
               </tr>
               <tr class="uk-text-small">
                  <td class="uk-width-3-10 uk-text-bold">Principle Contact No. : </td>
                  <td class="uk-width-7-10">
                     <input class="uk-width-1-1" type="text" name="principle_contact_no" id="principle_contact_no" value="<?php echo $edit_row['principle_contact_no']?>">
                  </td>
               </tr>
               <tr class="uk-text-small">
                  <td class="uk-width-3-10 uk-text-bold">Assistant Name : </td>
                  <td class="uk-width-7-10">
                     <input class="uk-width-1-1" type="text" name="assistant_name" id="assistant_name" value="<?php echo $edit_row['assistant_name']?>">
                  </td>
               </tr>
               <tr class="uk-text-small">
                  <td class="uk-width-3-10 uk-text-bold">A&P Tel : </td>
                  <td class="uk-width-7-10">
                     <input class="uk-width-1-1" type="text" name="ANP_tel" id="ANP_tel" value="<?php echo $edit_row['ANP_tel']?>">
                  </td>
               </tr>
               <tr class="uk-text-small">
                  <td class="uk-width-3-10 uk-text-bold">Personal Tel : </td>
                  <td class="uk-width-7-10">
                     <input class="uk-width-1-1" type="text" name="personal_tel" id="personal_tel" value="<?php echo $edit_row['personal_tel']?>">
                  </td>
               </tr>
<script>
$(document).ready(function () {
   $("#status").change(function () {
      if ($("#status").val() == "O") {
         $("#other_status").show();
      } else {
         $("#other_status").val("");
         $("#other_status").hide();
      }
   });
});
</script>
               <tr class="uk-text-small">
                  <td class="uk-width-3-10 uk-text-bold">Status <span class="text-danger">*</span>: </td>
                  <td class="uk-width-7-10">
                     <select name="status" id="status" class="uk-width-1-1">
                        <option value="">Select</option>
                        <!--<option value="A" <?php // if ($edit_row["status"]=="A") {echo "selected";}?>>Active</option>
                        <option value="T" <?php //if ($edit_row["status"]=="T") {echo "selected";}?>>Transferred</option>
                        <option value="S" <?php //if ($edit_row["status"]=="S") {echo "selected";}?>>Sell Off</option>
                        <option value="C" <?php // if ($edit_row["status"]=="C") {echo "selected";}?>>Closed</option>-->
                        <!--<option value="O" <?php //if ($edit_row["status"]=="O") {echo "selected";}?>>Others</option>-->
<?php
$sql="SELECT * from codes where module='CENTRE_STATUS' order by code";
$result=mysqli_query($connection, $sql);
while ($row=mysqli_fetch_assoc($result)) {
?>
                        <option value="<?php echo $row['code']?>" <?php if ($row['code']==$edit_row['status']) {echo 'selected';}?>><?php echo getStatus($row["code"]);?></option>
<?php
}
?>
                     </select>
<?php
if ($edit_row["status"]=="O") {
?>
                     <input class="uk-width-1-1" type="text" name="other_status" id="other_status" placeholder="Other Status" value="<?php echo $edit_row['other_status']?>">
<?php
} else {
?>
                     <input class="uk-width-1-1" type="text" name="other_status" id="other_status" placeholder="Other Status" value="<?php echo $edit_row['other_status']?>" hidden>
<?php
}
?>
					<span id="validationStatus"  style="color: red; display: none;">Please select Status</span>
                  </td>
               </tr>
               <tr class="uk-text-small">
                  <td class="uk-width-3-10 uk-text-bold">A&P Email : </td>
                  <td class="uk-width-7-10">
                     <input class="uk-width-1-1" type="text" name="ANP_email" id="ANP_email" value="<?php echo $edit_row['ANP_email']?>">
                  </td>
               </tr>
               <tr class="uk-text-small">
                  <td class="uk-width-3-10 uk-text-bold">Company Email : </td>
                  <td class="uk-width-7-10">
                     <input class="uk-width-1-1" type="text" name="company_email" id="company_email" value="<?php echo $edit_row['company_email']?>">
                  </td>
               </tr>
               <!--<tr class="uk-text-small">
                  <td class="uk-width-3-10 uk-text-bold">Adjust Fee : </td>
                  <td class="uk-width-7-10">
                     <select name="can_adjust_fee" id="can_adjust_fee" class="uk-width-1-1">
                        <option value="">Select</option>
                        <option value="Y" <?php // if ($edit_row["can_adjust_fee"]=="Y") {echo "selected";}?>>Yes</option>
                        <option value="N" <?php // if ($edit_row["can_adjust_fee"]=="N") {echo "selected";}?>>No</option>
                     </select>
                  </td>
               </tr>
               <tr class="uk-text-small">
                  <td class="uk-width-3-10 uk-text-bold">Adjust Product : </td>
                  <td class="uk-width-7-10">
                     <select name="can_adjust_product" id="can_adjust_product" class="uk-width-1-1">
                        <option value="">Select</option>
                        <option value="Y" <?php // if ($edit_row["can_adjust_product"]=="Y") {echo "selected";}?>>Yes</option>
                        <option value="N" <?php // if ($edit_row["can_adjust_product"]=="N") {echo "selected";}?>>No</option>
                     </select>
                  </td>
               </tr>-->
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
               <tr class="uk-text-small">
                  <td class="uk-width-3-10 uk-text-bold">Registration Fee :</td>
                  <td class="uk-width-7-10">
                     <input class="uk-width-1-1" type="number" step="0.01" name="registration_fee" id="registration_fee" value="<?php echo $edit_row['registration_fee']?>">
                  </td>
               </tr>
               <tr class="uk-text-small">
                  <td class="uk-width-3-10 uk-text-bold">Bank Details :</td>
                  <td class="uk-width-7-10">
                     <textarea name="bank_detail" rows="7" id="bank_detail" class="uk-width-1-1"><?php echo $edit_row['bank_detail']; ?></textarea>
                  </td>
               </tr>
            </table>
         </div>
      </div>
      <br>
      <div class="uk-panel uk-panel-box">
         <h3 class="uk-panel-title">Sign Between Franchisor and Franchisee</h3>
         <div class="uk-grid">
            <div class="uk-width-medium-5-10">
               <table class="uk-table uk-table-small">
                  <tr class="uk-text-small">
                     <td class="width-4-10 uk-text-bold">Franchisor Company Name : </td>
                     <td class="uk-width-6-10">
                        <input class="uk-width-1-1" type="text" name="franchisor_company_name" id="franchisor_company_name" value="<?php echo $edit_row["franchisor_company_name"]?>">
                     </td>
                  </tr>
                  <tr class="uk-text-small">
                     <td class="width-4-10 uk-text-bold">Franchisee Company : </td>
                     <td class="uk-width-6-10">
                        <select class="uk-width-1-1" name="centre_franchisee_company_id" id="centre_franchisee_company_id">
                           <option value="">Select</option>
<?php
$sql="SELECT * from centre_franchisee_company where centre_code='".$edit_row["centre_code"]."'";
$result=mysqli_query($connection, $sql);
while ($row=mysqli_fetch_assoc($result)) {
?>
                           <option value="<?php echo $row['id']?>" <?php if ($edit_row["centre_franchisee_company_id"]==$row["id"]) {echo "selected";}?>><?php echo $row["franchisee_company_name"]?></option>
<?php
}
?>
                        </select>
                     </td>
                  </tr>
                  <tr class="uk-text-small">
                     <td class="width-4-10 uk-text-bold">Franchisee Name : </td>
                     <td class="uk-width-6-10">
                        <select class="uk-width-1-1" name="centre_franchisee_name_id" id="centre_franchisee_name_id">
                           <option value="">Select</option>
<?php
$sql="SELECT * from centre_franchisee_name where centre_code='".$edit_row["centre_code"]."'";
$result=mysqli_query($connection, $sql);
while ($row=mysqli_fetch_assoc($result)) {
?>
                           <option value="<?php echo $row['id']?>" <?php if ($edit_row["centre_franchisee_name_id"]==$row["id"]) {echo "selected";}?>><?php echo $row["franchisee_name"]?></option>
<?php
}
?>
                        </select>
                     </td>
                  </tr>
               </table>
            </div>
            <div class="uk-width-medium-5-10">
               <table class="uk-table uk-table-small">
<script>
$('#SSM_file').change(function (event) {
    var file = URL.createObjectURL(event.target.files[0]);
    $('#dvSSM_file').append('<a href="' + file + '" target="_blank">Click to view document</a><br>');
});
$('#MOE_license_file').change(function (event) {
    var file = URL.createObjectURL(event.target.files[0]);
    $('#dvMOE_license_file').append('<a href="' + file + '" target="_blank">Click to view document</a><br>');
});
function uploadAttachment() {
   var centre_code=$("#centre_code").val();
   var doc_type=$("#doc_type").val();

   if ((centre_code!="") & (doc_type!="")) {
      var formdata=new FormData();

      var file=document.getElementById('attachment').files[0];
		//console.log(file.size);
		//return false;
		if(file.size<=26214400){
      formdata.append("attachment", file);
      formdata.append("centre_code", centre_code);
      formdata.append("doc_type", doc_type);
//alert("in");
      $.ajax({
         url : "admin/upload_attachment_centre.php",
         type : "POST",
         data : formdata,
         dataType : "text",
         enctype: 'multipart/form-data',
         processData: false,
         contentType: false,
         success : function(response, status, http) {
            var s=response.split("|");

            if (s[0]=="0") {
               UIkit.notify(s[1]);
            } else {
               UIkit.notify("Successful");
               $("#tblAttachment").empty();
               $("#tblAttachment").append(s[1]);
            }
         },
         error : function(http, status, error) {
            UIkit.notify("Error:"+error);
         }
      });
   }else{
	   UIkit.notify("File size must be less then or equal 25MB");
   }
   } else {
      UIkit.notify("Please provide a centre code and a document type");
   }
}

function deleteAttachment(id) {
   UIkit.modal.confirm("<h2>Are you sure to continue?</h2>", function () {
      $.ajax({
         url : "admin/delete_attachment_centre.php",
         type : "POST",
         data : "id="+id,
         dataType : "text",
         beforeSend : function(http) {
         },
         success : function(response, status, http) {
            var s=response.split("|");

            if (s[0]=="0") {
               UIkit.notify(s[1]);
            } else {
               UIkit.notify("Successful");
               $("#tblAttachment").empty();
               $("#tblAttachment").append(s[1]);
            }
         },
         error : function(http, status, error) {
            UIkit.notification("Error:"+error);
         }
      });
   });
}
</script>
                  <tr class="uk-text-small">
                     <td colspan="2">
                        <table class="uk-table uk-table-small">
                           <tr class="uk-text-small">
                              <td class="uk-width-4-10">
                                 <input type="file" accept="application/pdf" name="attachment" id="attachment">
                              </td>
                           </tr>
                           <tr>
                              <td class="uk-width-4-10">
                                 <select class="uk-width-1-1" name="doc_type" id="doc_type">
                                    <option value="">Select</option>
                                    <?php
$sql="SELECT * from codes where module='LEGAL_AGREEMENT' order by code";
$result=mysqli_query($connection, $sql);
while ($row=mysqli_fetch_assoc($result)) {
?>
                        <option value="<?php echo $row['code']?>" <?php if ($row['code']==$edit_row['doc_type']) {echo 'selected';}?>><?php echo $row["code"]?></option>
<?php
}
?>
                                 </select>
                              </td>
                           </tr>
                           <tr>
                              <td class="uk-width-2-10 uk-text-right">
                                 <a onclick="uploadAttachment()" class="uk-button uk-button-small">Upload</a>
                              </td>
                           </tr>
                        </table>
                        <table class="uk-table uk-table-small" id="tblAttachment">
<?php
$sql="SELECT * from centre_agreement_file where centre_code='".$edit_row["centre_code"]."'";
$result=mysqli_query($connection, $sql);
$num_row=mysqli_num_rows($result);
if ($num_row>0) {
   while ($row=mysqli_fetch_assoc($result)) {
?>
                           <tr class="uk-width-1-1">
                              <td class="uk-width-4-10"><?php echo $row["attachment"]?></td>
                              <td class="uk-width-4-10"><?php echo $row["doc_type"]?></td>
                              <td class="uk-width-2-10">
                                 <a target="_blank" href="admin/uploads/<?php echo $row["attachment"]?>"><img data-uk-tooltip="{pos:top}" title="View PDF File" src="images/pdf.png"></a>
                                 <a onclick="deleteAttachment('<?php echo $row['id']?>')"><img data-uk-tooltip="{pos:top}" title="Delete PDF File" src="images/delete.png"></a>
                              </td>
                           </tr>
<?php
   }
} else {
?>
                           <tr class="uk-width-1-1">
                              <td colspan="2" class="uk-width-1-1 uk-text-small">No record found</td>
                           </tr>
<?php
}
?>
                        </table>
                     </td>
                  </tr>
               </table>
            </div>
         </div>
      </div>
      <br>
      <div class="uk-text-center">
<?php
if (($_SESSION["UserType"]=="S") & (hasRightGroupXOR($_SESSION["UserName"], "CentreEdit"))) {
?>
         <button class="uk-button uk-button-primary">Save</button>
<?php
}
?>
      </div>
<?php
if (($_SESSION["UserType"]=="S") & (hasRightGroupXOR($_SESSION["UserName"], "CentreEdit"))) {
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
<?php
// $numperpage=20;
$query="p=$p&m=$m&s=$s";
$pagination=getPagination($pg, $numperpage, $query, $browse_sql, $start_record, $num_row);
// $browse_sql.=" limit $start_record, $numperpage";
//$browse_result=mysqli_query($connection, $browse_sql);
//$browse_num_row=mysqli_num_rows($browse_result);
// echo $pagination;
?>
   <form class="uk-form" name="frmCentreSearch" id="frmCentreSearch" method="get">
      <input type="hidden" name="mode" id="mode" value="BROWSE">
      <input type="hidden" name="p" id="p" value="<?php echo $p?>">
      <input type="hidden" name="m" id="m" value="<?php echo $m?>">
      <input type="hidden" name="pg" value="">

      <div class="uk-grid">
         <div class="uk-width-7-10 uk-text-small">
            <input class="uk-width-1-1" placeholder="Centre Code/Centre Category/Kindergarten Name/A&P Tel/Personal Tel/A&P Email/Company Email" name="s" id="s" value="<?php echo $_GET['s']?>">
         </div>
         <div class="uk-width-3-10">
            <button class="uk-button uk-width-1-1">Search</button>
         </div>
      </div>
   </form><br>

   <div class="uk-width-1-1 myheader">
      <h2 class="uk-text-center myheader-text-color">Listing</h2>
   </div>
   <div class="uk-overflow-container">
   <table class="uk-table" id="mydatatable1" style="width: 100%;font-size:12px;">
      <thead>
      <tr class="uk-text-bold uk-text-small"> 
		 <td data-uk-tooltip="{pos:top}" title="Centre Category">Centre Category</td>
         <td data-uk-tooltip="{pos:top}" title="Centre Name + Centre code">Centre Name & Code</td>
        
         <td data-uk-tooltip="{pos:top}" title="Kindergarten Name">Kindergarten Name</td>
         <td data-uk-tooltip="{pos:top}" title="Company Name">Company Name</td>
         <td data-uk-tooltip="{pos:top}" title="Operator Name + Operator Contact No + Operator Email">Operator Name & Contact</td>
         <td data-uk-tooltip="{pos:top}" title="Principal Name + Principal Contact No + Principal Email">Principle Name & Contact</td>
         <td data-uk-tooltip="{pos:top}" title="Assistant Name">Assistant Name</td>
         <td>A&P Contact</td>
     
         <td >Status</td>
         <td class="no_st">Action</td>
      </tr>
   </thead>
   <tbody>
</tbody>
   </table>
   </div>
<?php
echo $pagination;
?>
</div>

<form name="frmDeleteRecord" id="frmDeleteRecord" method="get">
   <input type="hidden" name="p" value="<?php echo $p?>">
   <input type="hidden" name="m" value="<?php echo $m?>">
   <input type="hidden" name="id" id="id" value="">
   <input type="hidden" name="mode" value="DEL">
</form>
<div id="dlgCentreFranchiseeCompany"></div>
<div id="dlgCentreFranchiseeName"></div>
<?php
if ($msg!="") {
   echo "<script>UIkit.notify('$msg')</script>";
}
} else {
   echo "<div class='uk-margin-top uk-margin-right'><div class='uk-alert uk-alert-danger uk-text-large uk-text-bold uk-text-center'>Unauthorised access denied</div></div>";
}
} else {
   echo "<form method='post' id='frmLogin' action='index.php'></form><script>$('#frmLogin').submit();</script>";
}
?>

<script type="text/javascript">
   $(document).ready(function(){
   $('#mydatatable1').DataTable({
      'columnDefs': [ { 
        'targets': [0,9], // column index (start from 0)
        'orderable': false, // set orderable false for selected columns
     }],
	"order": [[ 8, "asc" ]],
    "bProcessing": true,
    "bServerSide": true,
    "sAjaxSource": "admin/serverresponse/centre.php?s=<?php echo $_GET['s']; ?>&p=<?php echo $_GET['p']; ?>"
   });
}); 


</script>
