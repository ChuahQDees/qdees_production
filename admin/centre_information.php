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
    <span class="page_title"><img src="/images/title_Icons/Visitor Reg.png">Centre Information</span>
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
    if(($_SESSION["UserType"]=="A") || ($_SESSION["UserType"]=="O")) {
      include_once("mysql.php");
      include_once("lib/pagination/pagination.php");

    $centre_sql = mysqli_fetch_array(mysqli_query($connection,"SELECT `id` FROM `centre` WHERE `centre_code` = '".$_SESSION['CentreCode']."'"));

    $_GET['id'] = sha1($centre_sql["id"]);
    $_GET['mode'] = isset($_GET['mode']) ? $_GET['mode'] : 'EDIT';
    $_GET['m'] = '';

    $p=$_GET["p"];
    $m=$_GET["m"];
    $get_sha1_id=$_GET["id"];
    $pg=$_GET["pg"];
    $mode=$_GET["mode"];
    $mode1=$_GET["mode1"];

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
 
  });
});

</script>

<div class="uk-margin-right">
   <div class="uk-width-1-1 myheader">
      <h2 class="uk-text-center myheader-text-color">Centre Information</h2>
   </div>
   <div class="uk-form uk-form-small">
<?php

if(($_SESSION["UserType"]=="A") || ($_SESSION["UserType"]=="O")) {
?>
   <form name="frmCentre" id="frmCentre" method="post" action="index.php?p=centre_information&pg=<?php echo $pg?>&mode1=SAVE_CENTRE" enctype="multipart/form-data">
<?php
}
?>  
      <div class="uk-grid">
         <div class="uk-width-medium-5-10">
            <table class="uk-table uk-table-small">
				 <tr class="uk-text-small">
                  <td class="uk-width-3-10 uk-text-bold">Master Code<span class="text-danger">*</span>: </td>
                  <td class="uk-width-7-10">
                     <select name="upline" disabled id="upline" class="uk-width-1-1">
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
                     <select name="country" disabled id="country" class="uk-width-1-1">
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
                     <select name="state" disabled id="state" class="uk-width-1-1">
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
                           <input class="uk-width-1-1" disabled type="text" name="centre_code" id="centre_code" value="<?php echo $edit_row['centre_code']?>" >
                           <span id="validationCentreCode"  style="color: red; display: none;">Please insert File No</span>
                        </div>
                        <div class="uk-width-3-10">
                           <button disabled class="uk-button">Get Code</button>
                        </div>
                     </div>
                     <input type="hidden" name="hidden_centre_code" id="hidden_centre_code" value="<?php echo $edit_row['centre_code']?>">
                     Please select a Master, then click Get Code button
                  </td>
               </tr>
			   
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
                  <td colspan="2"><button disabled class="uk-button uk-width-1-1" >Franchisee Company Name</button></td>
               </tr>
               <tr class="uk-text-small">
                  <td colspan="2"><button disabled class="uk-button uk-width-1-1">Franchisee Name</button></td>
               </tr>
               <tr class="uk-text-small">
                  <td class="uk-width-3-10 uk-text-bold">Centre Name<span class="text-danger">*</span>: </td>
                  <td class="uk-width-7-10">
                     <input class="uk-width-1-1" disabled type="text" name="company_name" id="company_name" value="<?php echo $edit_row['company_name']?>">
                     <span id="validationCompanyName"  style="color: red; display: none;">Please insert File No</span>
                  </td>
               </tr>
               <tr class="uk-text-small">
                  <td class="uk-width-3-10 uk-text-bold">Kindergarten Name<span class="text-danger">*</span>: </td>
                  <td class="uk-width-7-10">
                     <input class="uk-width-1-1" disabled type="text" name="kindergarten_name" id="kindergarten_name" value="<?php echo $edit_row['kindergarten_name']?>">
                     <span id="validationKindergartenName"  style="color: red; display: none;">Please insert Kindergarten Name</span>
                  </td>
               </tr>
               <tr class="uk-text-small">
                  <td class="uk-width-3-10 uk-text-bold">Centre Category : </td>
                  <td class="uk-width-7-10">
                     <select class="uk-width-1-1" disabled name="status_center" id="status_center">
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
                     <input class="uk-width-1-1" disabled type="text" step="0.01" name="pic" id="pic" value="<?php echo $edit_row['pic']?>">
                     <span id="validationPic"  style="color: red; display: none;">Please insert OC in Charge</span>
                  </td>
               </tr>
               <tr class="uk-text-small">
                  <td class="uk-width-3-10 uk-text-bold">Year of Commencement : </td>
                  <td class="uk-width-7-10">
                     <input class="uk-width-1-1" disabled data-uk-datepicker="{format: 'DD/MM/YYYY'}" type="text" name="year_of_commencement" id="year_of_commencement" value="<?php echo convertDate2British($edit_row['year_of_commencement'])?>" autocomplete="off" readonly>
                     <input type="hidden" id="dni_year_of_commencement" value="<?php echo $edit_row['year_of_commencement']?>">
                     <span id="validationYearOfCommencement"  style="color: red; display: none;">Please insert Year of Commencement</span>
                  </td>
               </tr>
               <tr class="uk-text-small">
                  <td class="uk-width-4-10 uk-text-bold">Year of Renewal : </td>
                  <td>
                     <div class="uk-grid uk-grid-collapse">
                        <div class="uk-width-6-10">
                           <input type="text" disabled name="year_of_renewal" class="uk-width-1-1" id="year_of_renewal" value="<?php echo $edit_row["year_of_renewal"];?>">
                        </div>
                        <div class="uk-width-4-10">
                           <button class="uk-button uk-width-1-1" disabled >Calc Expiry</button>
                        </div>
                     </div>
                  </td>
               </tr>
               <tr class="uk-text-small">
                  <td class="uk-width-3-10 uk-text-bold">Expiry Date : </td>
                  <td class="uk-width-7-10">
                     <input class="uk-width-1-1" disabled type="text" name="expiry_date" id="expiry_date" value="<?php echo convertDate2British($edit_row['expiry_date'])?>" readonly>
                  </td>
               </tr>
               <tr class="uk-text-small">
                  <td class="uk-width-3-10 uk-text-bold">SSM File : </td>
                  <td class="uk-width-7-10" id="dvSSM_file">
                     <input disabled class="uk-width-1-1" type="file" name="SSM_file" id="SSM_file">
                     
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
                     <input class="uk-width-1-1" disabled type="file" name="MOE_license_file" id="MOE_license_file">
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
                     <input class="uk-width-1-1" disabled type="text" name="operator_name" id="operator_name" value="<?php echo $edit_row['operator_name']?>">
                  </td>
               </tr>
               <tr class="uk-text-small">
                  <td class="uk-width-3-10 uk-text-bold">Operator NRIC : </td>
                  <td class="uk-width-7-10">
                     <input class="uk-width-1-1" disabled type="text" name="operator_nric" id="operator_nric" value="<?php echo $edit_row['operator_nric']?>">
                  </td>
               </tr>
            </table>
         </div>

         <div class="uk-width-medium-5-10">
            <table class="uk-table uk-table-small">
               <tr class="uk-text-small">
                  <td class="uk-width-3-10 uk-text-bold">Operator Contact No. : </td>
                  <td class="uk-width-7-10">
                     <input class="uk-width-1-1" disabled type="text" name="operator_contact_no" id="operator_contact_no" value="<?php echo $edit_row['operator_contact_no']?>">
                  </td>
               </tr>
               <tr class="uk-text-small">
                  <td class="uk-width-3-10 uk-text-bold">Principle Name : </td>
                  <td class="uk-width-7-10">
                     <input class="uk-width-1-1" disabled type="text" name="principle_name" id="principle_name" value="<?php echo $edit_row['principle_name']?>">
                  </td>
               </tr>
               <tr class="uk-text-small">
                  <td class="uk-width-3-10 uk-text-bold">Principle Contact No. : </td>
                  <td class="uk-width-7-10">
                     <input class="uk-width-1-1" disabled type="text" name="principle_contact_no" id="principle_contact_no" value="<?php echo $edit_row['principle_contact_no']?>">
                  </td>
               </tr>
               <tr class="uk-text-small">
                  <td class="uk-width-3-10 uk-text-bold">Assistant Name : </td>
                  <td class="uk-width-7-10">
                     <input class="uk-width-1-1" disabled type="text" name="assistant_name" id="assistant_name" value="<?php echo $edit_row['assistant_name']?>">
                  </td>
               </tr>
               <tr class="uk-text-small">
                  <td class="uk-width-3-10 uk-text-bold">A&P Tel : </td>
                  <td class="uk-width-7-10">
                     <input class="uk-width-1-1" disabled type="text" name="ANP_tel" id="ANP_tel" value="<?php echo $edit_row['ANP_tel']?>">
                  </td>
               </tr>
               <tr class="uk-text-small">
                  <td class="uk-width-3-10 uk-text-bold">Personal Tel : </td>
                  <td class="uk-width-7-10">
                     <input class="uk-width-1-1" disabled type="text" name="personal_tel" id="personal_tel" value="<?php echo $edit_row['personal_tel']?>">
                  </td>
               </tr>
               <tr class="uk-text-small">
                  <td class="uk-width-3-10 uk-text-bold">Status <span class="text-danger">*</span>: </td>
                  <td class="uk-width-7-10">
                     <select name="status" disabled id="status" class="uk-width-1-1">
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
                     <input class="uk-width-1-1" disabled type="text" name="ANP_email" id="ANP_email" value="<?php echo $edit_row['ANP_email']?>">
                  </td>
               </tr>
               <tr class="uk-text-small">
                  <td class="uk-width-3-10 uk-text-bold">Company Email : </td>
                  <td class="uk-width-7-10">
                     <input class="uk-width-1-1" disabled type="text" name="company_email" id="company_email" value="<?php echo $edit_row['company_email']?>">
                  </td>
               </tr>
              
               <tr class="uk-text-small">
                  <td class="uk-width-3-10 uk-text-bold">Address : </td>
                  <td class="uk-width-7-10">
                     <input class="uk-width-1-1" disabled type="text" name="address1" id="address1" value="<?php echo $edit_row['address1']?>">
                     <input class="uk-width-1-1" disabled type="text" name="address2" id="address2" value="<?php echo $edit_row['address2']?>">
                     <input class="uk-width-1-1" disabled type="text" name="address3" id="address3" value="<?php echo $edit_row['address3']?>">
                     <input class="uk-width-1-1" disabled type="text" name="address4" id="address4" value="<?php echo $edit_row['address4']?>">
                     <input class="uk-width-1-1" disabled type="text" name="address5" id="address5" value="<?php echo $edit_row['address5']?>">
                  </td>
               </tr>
               <tr class="uk-text-small">
                  <td class="uk-width-3-10 uk-text-bold">Registration Fee :</td>
                  <td class="uk-width-7-10">
                     <input class="uk-width-1-1" disabled type="number" step="0.01" name="registration_fee" id="registration_fee" value="<?php echo $edit_row['registration_fee']?>">
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
                        <input class="uk-width-1-1" disabled type="text" name="franchisor_company_name" id="franchisor_company_name" value="<?php echo $edit_row["franchisor_company_name"]?>">
                     </td>
                  </tr>
                  <tr class="uk-text-small">
                     <td class="width-4-10 uk-text-bold">Franchisee Company : </td>
                     <td class="uk-width-6-10">
                        <select class="uk-width-1-1" disabled name="centre_franchisee_company_id" id="centre_franchisee_company_id">
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
                        <select class="uk-width-1-1" disabled name="centre_franchisee_name_id" id="centre_franchisee_name_id">
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
                  <tr class="uk-text-small">
                     <td colspan="2">
                        <table class="uk-table uk-table-small">
                           <tr class="uk-text-small">
                              <td class="uk-width-4-10">
                                 <input type="file" disabled accept="application/pdf" name="attachment" id="attachment">
                              </td>
                           </tr>
                           <tr>
                              <td class="uk-width-4-10">
                                 <select class="uk-width-1-1" disabled name="doc_type" id="doc_type">
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
                                 <button disabled class="uk-button uk-button-small">Upload</button>
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
if(($_SESSION["UserType"]=="A") || ($_SESSION["UserType"]=="O")){
?>
         <button class="uk-button uk-button-primary">Save</button>
<?php
}
?>
      </div>
<?php
if(($_SESSION["UserType"]=="A") || ($_SESSION["UserType"]=="O")){
?>
   </form>
<?php
}
?>
   </div>
   <br>
</div>

<div id="dlgCentreFranchiseeCompany"></div>
<div id="dlgCentreFranchiseeName"></div>
<?php
if ($msg!="") {
   echo "<script>UIkit.notify('$msg');</script>";
}
} else {
   echo "<div class='uk-margin-top uk-margin-right'><div class='uk-alert uk-alert-danger uk-text-large uk-text-bold uk-text-center'>Unauthorised access denied</div></div>";
}
} else {
   echo "<form method='post' id='frmLogin' action='index.php'></form><script>$('#frmLogin').submit();</script>";
}

?>
