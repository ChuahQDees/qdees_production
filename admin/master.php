<!--<?php // if($_GET['mode']!=""){ ?>
<a href="/index.php?p=master">                 
             <span class="btn-qdees"><i class="fa fa-arrow-left" style="margin-right: 20px;"></i>Back</span>
</a>
<?php // }else { ?>
<a href="/">                 
             <span class="btn-qdees"><i class="fa fa-arrow-left" style="margin-right: 20px;"></i>Back</span>
</a>
<?php // } ?>-->
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
    <span class="page_title"><img src="/images/title_Icons/Visitor Reg.png">Master</span>
</span>
<?php
include_once("admin/functions.php");

function getState($master_code, $country) {
   global $connection;

   $sql="SELECT * from master_state where master_code='$master_code' and country='$country'";
   $result=mysqli_query($connection, $sql);
   $state="";
   $count=0;
   while ($row=mysqli_fetch_assoc($result)) {
      $count++;
      $state.=$row["state"].", "."<br> ";
   }

   $state=substr($state, 0, -2);
   if ($count>=4) {
      $state.="</div>";
   }

   return $state;
}

function getFranchiseeCompanyDetails($master_code, &$company_name) {
   global $connection;

   $sql="SELECT * from master_franchisee_company where master_code='$master_code'";

   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);
   $company_name=$row["franchisee_company_name"];
}

function getFranchiseeNameDetails($master_code, &$franchisee_name, &$franchisee_contact_no, &$franchisee_email) {
   global $connection;

   $sql="SELECT * from master_franchisee_name where master_code='$master_code'";
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);

   $franchisee_name=$row["franchisee_name"];
   $franchisee_contact_no=$row["franchisee_contact_no"];
   $franchisee_email=$row["franchisee_email"];
}

if ($_SESSION["isLogin"]==1) {
   if (($_SESSION["UserType"]=="S" || $_SESSION["UserType"]=="H" || $_SESSION["UserType"]=="C" || $_SESSION["UserType"]=="R" || $_SESSION["UserType"]=="CM" || $_SESSION["UserType"]=="T") & (hasRightGroupXOR($_SESSION["UserName"], "MasterEdit|MasterView"))) {
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

      include_once("master_func.php");
?>

<script>
$(document).ready(function () {
   $("#loadMoreLink").click(function () {
      $("#loadMoreLink").hide();
      $("#load").show();
   });
});

function ajaxUpdateName() {
   var master_code=$("#master_code").val();

   $.ajax({
      url : "admin/updateMasterName.php",
      type : "POST",
      data : "master_code="+master_code,
      dataType : "text",
      beforeSend : function(http) {
      },
      success : function(response, status, http) {
         $("#master_franchisee_name_id").html(response);
      },
      error : function(http, status, error) {
         UIkit.notify("Error:"+error);
      }
   });
}

function ajaxUpdateCompany() {
   var master_code=$("#master_code").val();

   $.ajax({
      url : "admin/updateMasterCompany.php",
      type : "POST",
      data : "master_code="+master_code,
      dataType : "text",
      beforeSend : function(http) {
      },
      success : function(response, status, http) {
         $("#master_franchisee_company_id").html(response);
      },
      error : function(http, status, error) {
         UIkit.notify("Error:"+error);
      }
   });
}

function ajaxGetMasterCode(country, mastertype) {
   $.ajax({
      url : "admin/getMasterCode.php",
      type : "POST",
      data : "country="+country+"&mastertype="+mastertype,
      dataType : "text",
      beforeSend : function(http) {
      },
      success : function(response, status, http) {
         $("#master_code").val(response);
      },
      error : function(http, status, error) {
         UIkit.notification("Error:"+error);
      }
   });
}

function getCode() {
   var master_code=$("#master_code").val();
   var country=$("#country").val();
   var mastertype=$("#mastertype").val();
   var tocontinue=0;

   if (master_code=="") {
      ajaxGetMasterCode(country, mastertype);
   } else {
      UIkit.modal.confirm("<h2>Master code has value, are you sure to overwrite it?</h2>", function () {
         ajaxGetMasterCode(country, mastertype);
      });
   }
}

function dlgMasterFranchiseeCompany(master_code, mode) {
   if (master_code=="") {
      master_code=$("#master_code").val();
   }

   if (master_code!="") {
      $.ajax({
         url : "admin/dlgMasterFranchiseeCompany.php",
         type : "POST",
         data : "master_code="+master_code+"&mode="+mode,
         dataType : "text",
         beforeSend : function(http) {
         },
         success : function(response, status, http) {
            $("#dlg").html(response);
            $("#dlg").dialog({
               dialogClass:"no-close",
               title:"Franchisee Company Details",
               modal:true,
               height:'auto',
               width:'auto',
            });
         },
         error : function(http, status, error) {
            UIkit.notification("Error:"+error);
         }
      });
   } else {
      UIkit.notify("Please select a Master");
   }
}

function dlgMasterFranchiseeName(master_code, mode) {
   if (master_code=="") {
      master_code=$("#master_code").val();
   }

   if (master_code!="") {
      $.ajax({
         url : "admin/dlgMasterFranchiseeName.php",
         type : "POST",
         data : "master_code="+master_code+"&mode="+mode,
         dataType : "text",
         beforeSend : function(http) {
         },
         success : function(response, status, http) {
            $("#dlg").html(response);
            $("#dlg").dialog({
               dialogClass:"no-close",
               title:"Franchisee Name Details",
               modal:true,
               height:'auto',
               width:'auto',
            });
         },
         error : function(http, status, error) {
            UIkit.notification("Error:"+error);
         }
      });
   } else {
      UIkit.notify("Please select a Master");
   }
}

function getMasterFranchiseDate(master_code, mode) {
   if (master_code=="") {
      master_code=$("#master_code").val();
   }

   if (master_code!="") {
      $.ajax({
         url : "admin/get_master_franchise_date.php",
         type : "POST",
         data : "master_code="+master_code+"&mode="+mode,
         dataType : "text",
         beforeSend : function(http) {
         },
         success : function(response, status, http) {
            $("#dlg").html(response);
            $("#dlg").dialog({
               dialogClass:"no-close",
               title:"Master Franchise Date",
               modal:true,
               height:'auto',
               width:'auto',
            });
         },
         error : function(http, status, error) {
            UIkit.notification("Error:"+error);
         }
      });
   } else {
      UIkit.notify("Please select a Master");
   }
}

function doDeleteRecord(id) {
   UIkit.modal.confirm("<h2>Are you sure to continue?</h2>", function () {
      $("#id").val(id);
      $("#frmDeleteRecord").submit();
   });
}



$(document).ready(function(){
  $("#frmCentre").submit(function(e){
    
    var country=$("#country").val();
    var master_code=$("#master_code").val();
    var mastertype=$("#mastertype").val();
    var franchise_fee=$("#franchise_fee").val();
    var year_of_commencement=$("#year_of_commencement").val();

    if (!country || !master_code || !mastertype || !franchise_fee || !year_of_commencement ) {

   e.preventDefault();
    alert("Please fill up mandatory fields marked *");

   if (!country) {
            $('#validationCountry').show();
        }else{
            $('#validationCountry').hide();
        }

         if (!master_code) {
            $('#validationMasterCode').show();
        }else{
            $('#validationMasterCode').hide();
        }

         if (!mastertype) {
            $('#validationMasterType').show();
        }else{
            $('#validationMasterType').hide();
        }

         if (!franchise_fee) {
            $('#validationFranchiseFee').show();
        }else{
            $('#validationFranchiseFee').hide();
        }

         if (!year_of_commencement) {
            $('#validationYearOfCommencement').show();
        }else{
            $('#validationYearOfCommencement').hide();
        }
        
  }
  });
});
</script>

<div class="uk-margin-right">
   <div class="uk-width-1-1 myheader">
      <h2 class="uk-text-center myheader-text-color">Master Information</h2>
   </div>
   <div class="uk-form uk-form-small">
<?php
if (($_SESSION["UserType"]=="S" || $_SESSION["UserType"]=="H" || $_SESSION["UserType"]=="C" || $_SESSION["UserType"]=="R" || $_SESSION["UserType"]=="CM" || $_SESSION["UserType"]=="T") & (hasRightGroupXOR($_SESSION["UserName"], "MasterEdit"))) {
?>
   <form name="frmMaster" id="frmMaster" method="post" action="index.php?p=master&pg=<?php echo $pg?>&mode=SAVE">
<?php
}
?>
      <div id="the_grid" class="uk-grid">
         <div class="uk-width-medium-5-10">
            <table class="uk-table uk-table-small">
				<tr class="uk-text-small">
                  <td class="uk-width-4-10 uk-text-bold">Master Type : </td>
                  <td class="uk-width-6-10">
                     <select name="mastertype" id="mastertype" class="uk-width-1-1" onchange="doChangeMasterType()">
                        <option value="">Select</option>
<?php
$sql="SELECT * from codes where module='MASTERTYPE' order by code";
$result=mysqli_query($connection, $sql);
while ($row=mysqli_fetch_assoc($result)) {
?>
                        <option value="<?php echo $row['code']?>" <?php if ($row['code']==$edit_row['mastertype']) {echo 'selected';}?>><?php echo $row["code"]?></option>
<?php
}
?>
                     </select>
                     <span id="validationMasterType"  style="color: red; display: none;">Please select Master Type</span>
                  </td>
               </tr>
               <tr class="uk-text-small">
                  <td class="uk-width-3-10 uk-text-bold"><span id='lblCountry'>Country : </span></td>
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
                     <span id="validationCountry"  style="color: red; display: none;">Please select  Country</span>
                  </td>
               </tr>
               <tr class="uk-text-small">
                  <td class="uk-width-4-10 uk-text-bold">Master Code : </td>
                  <td class="uk-width-6-10">
                     <div class="uk-grid uk-grid-collapse">
                        <div class="uk-width-7-10">
                           <input class="uk-width-1-1" type="text" name="master_code" id="master_code" value="<?php echo $edit_row['master_code']?>" readonly>
                           <span id="validationMasterCode"  style="color: red; display: none;">Please insert Master Code</span>
                        </div>
                        <div class="uk-width-3-10">
                           <a style="display:none;" onclick="getCode()" class="uk-button hide_2">Get Code</a>
                           <a style="white-space: nowrap;" class="uk-button show_2">Get Code</a>
                        </div>
                        <input type="hidden" name="hidden_master_code" id="hidden_master_code" value="<?php echo $edit_row['master_code']?>">
                        Please select country, then click Get Code button
                     </div>
                  </td>
               </tr>
<script>
function doChangeMasterType() {
   var mastertype=$("#mastertype").val();

   $.ajax({
      url : "admin/doChangeMasterType.php",
      type : "POST",
      data : "mastertype="+mastertype,
      dataType : "text",
      beforeSend : function(http) {
      },
      success : function(response, status, http) {
         if (response!="") {
            var mastertype=$("#mastertype").val();
            var s=response.split("|");
            if (s[0]=="1") {
               $("#country").html(response);
               if (mastertype=="Region") {
                  $("#lblCountry").html("Region : ");
               }

               if (mastertype=="Country") {
                  $("#lblCountry").html("Country : ");
               }
            }

            if (s[0]=="0") {
               $("#country").html(response);
            }
         }
      },
      error : function(http, status, error) {
         UIkit.notify("Error:"+error);
      }
   });
}
</script>
               
<script>
$(document).ready(function (){
	$("#mastertype,#country").change(function () {
		var country=$("#country").val();
		var mastertype=$("#mastertype").val();
		if (country !="" && mastertype !=""){
	   $('.hide_2').show();
		$('.show_2').hide();
		}
	});
	

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
            UIkit.notification("Error:"+error);
         }
      });
   });
});

function dlgMasterState() {
   var master_code=$("#master_code").val();
   var mastertype=$("#mastertype").val();
   var country=$("#country").val();

   if ((master_code!="") & (country!="")) {
      if ((mastertype!="HQ") & (mastertype!="Region")) {
         $.ajax({
            url : "admin/dlgState.php",
            type : "POST",
            data : "country="+country+"&master_code="+master_code,
            dataType : "text",
            beforeSend : function(http) {
            },
            success : function(response, status, http) {
               $("#dlg").html(response);
               $("#dlg").dialog({
                  dialogClass:"no-close",
                  title:"Master State",
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
         UIkit.notify("HQ or Region Master Type does not require a state");
      }
   } else {
      UIkit.notify("Please select a country and master code");
   }
}
</script>
               <tr class="uk-text-small">
<!--                   <td class="uk-width-3-10 uk-text-bold">State : </td> -->
                  <td colspan="2" class="uk-width-7-10">
                     <input type="hidden" name="state" id="state" value="">
                     <a id="btnState" class="uk-button uk-width-1-1" onclick="dlgMasterState()">State</a>
                  </td>
               </tr>
               <tr class="uk-text-small">
                  <td colspan="2"><a class="uk-button uk-width-1-1" onclick="dlgMasterFranchiseeCompany('<?php echo $edit_row["master_code"]?>', '<?php echo $_GET['mode']?>')">Master Franchisee Company</a></td>
               </tr>
               <!-- <tr class="uk-text-small">
                  <td class="uk-width-4-10 uk-text-bold">Status of Center : </td>
                  <td class="uk-width-6-10">
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
                  </td>
               </tr>
               <tr class="uk-text-small">
                  <td class="uk-width-4-10 uk-text-bold">Person Incharges : </td>
                  <td class="uk-width-6-10">
                     <input class="uk-width-1-1" type="text" step="0.01" name="pic" id="pic" value="<?php echo $edit_row['pic']?>">
                  </td>
               </tr> -->
               <tr class="uk-text-small">
                  <td colspan="2"><a class="uk-button uk-width-1-1" onclick="dlgMasterFranchiseeName('<?php echo $edit_row["master_code"]?>', '<?php echo $_GET['mode']?>')">Master Franchisee Name</a></td>
               </tr>
               <tr class="uk-text-small">
                  <td class="uk-width-4-10 uk-text-bold">Number of Master Franchisee : </td>
                  <td class="uk-width-6-10"><input class="uk-width-1-1" type="text" name="number_of_master_franchisee" id="number_of_master_franchisee" value="<?php echo $edit_row['number_of_master_franchisee']?>">
                     <span id="validationNumberMasterFranchisee"  style="color: red; display: none;">Please insert Number of Master Franchisee</span>
                  </td>
               </tr>
               <tr class="uk-text-small">
                  <td class="uk-width-4-10 uk-text-bold">Remarks</td>
                  <td class="uk-width-6-10">
                     <textarea class="uk-width-1-1" name="remarks" id="remarks"><?php echo $edit_row["remarks"]?></textarea>
                  </td>
               </tr>
               <tr class="uk-text-small">
                  <td colspan="2"><a onclick="getMasterFranchiseDate('<?php echo $edit_row['master_code']?>', '<?php echo $_GET['mode']?>')" class="uk-button uk-width-1-1">Master Franchise Date</a></td>
               </tr>
               <tr class="uk-text-small">
                  <td class="uk-width-4-10 uk-text-bold">Franchise Fee : </td>
                  <td class="uk-width-6-10">
                     <input class="uk-width-1-1" type="number" step="0.01" name="franchise_fee" id="franchise_fee" value="<?php echo $edit_row['franchise_fee']?>">
                     <span id="validationFranchiseFee"  style="color: red; display: none;">Please insert Franchise Fee</span>
                  </td>
               </tr>
               
            </table>
         </div>
         <div class="uk-width-medium-5-10">
            <table class="uk-table uk-table-small">
            <tr class="uk-text-small">
                  <td class="uk-width-4-10 uk-text-bold">Year of Commencement : </td>
                  <td class="uk-width-6-10">
                     <input class="uk-width-1-1" type="text" data-uk-datepicker="{format: 'DD/MM/YYYY'}" name="year_of_commencement" id="year_of_commencement" value="<?php echo convertDate2British($edit_row['year_of_commencement'])?>">
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
                           <span id="validationYearOfRenewal"  style="color: red; display: none;">Please insert Year of Renewal</span>
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
                  <td class="uk-width-4-10 uk-text-bold">Expiry Date : </td>
                  <td class="uk-width-6-10">
                     <input class="uk-width-1-1" type="text" name="expiry_date" id="expiry_date" value="<?php echo convertDate2British($edit_row['expiry_date'])?>" readonly>
                  </td>
               </tr>
               <tr class="uk-text-small">
                  <td class="uk-width-4-10 uk-text-bold">Company Number : </td>
                  <td class="uk-width-6-10">
                     <input class="uk-width-1-1" type="text" name="company_no" id="company_no" value="<?php echo $edit_row['company_no']?>">
                     <span id="validationCompanyNumber"  style="color: red; display: none;">Please insert Company Number</span>
                  </td>
               </tr>
			   <tr class="uk-text-small">
                  <td class="uk-width-4-10 uk-text-bold">Sign With : </td>
                  <td class="uk-width-6-10">
                     <input class="uk-width-1-1" type="text" name="sign_with" id="sign_with" value="<?php echo $edit_row['sign_with']?>">
                     <span id="validationSignWith"  style="color: red; display: none;">Please insert Sign With</span>
                  </td>
               </tr>
               <tr class="uk-text-small">
                  <td class="uk-width-4-10 uk-text-bold">Address : </td>
                  <td class="uk-width-6-10">
                     <input class="uk-width-1-1" type="text" name="add1" id="add1" value="<?php echo $edit_row['add1']?>">
                     <span id="validationAddress1"  style="color: red; display: none;">Please insert Address</span>
                     <input class="uk-width-1-1" type="text" name="add2" id="add2" value="<?php echo $edit_row['add2']?>">
                     <input class="uk-width-1-1" type="text" name="add3" id="add3" value="<?php echo $edit_row['add3']?>">
                     <input class="uk-width-1-1" type="text" name="add4" id="add4" value="<?php echo $edit_row['add4']?>">
                  </td>
               </tr>
               <tr class="uk-text-small">
                  <td class="uk-width-4-10 uk-text-bold">Tel 1 : </td>
                  <td class="uk-width-6-10"><input class="uk-width-1-1" type="text" name="tel1" id="tel1" value="<?php echo $edit_row['tel1']?>">
                     <span id="validationTel1"  style="color: red; display: none;">Please insert Tel 1</span>
                  </td>
               </tr>
               <tr class="uk-text-small">
                  <td class="uk-width-4-10 uk-text-bold">Tel 2 : </td>
                  <td class="uk-width-6-10"><input class="uk-width-1-1" type="text" name="tel2" id="tel2" value="<?php echo $edit_row['tel2']?>"></td>
               </tr>
               <tr class="uk-text-small">
                  <td class="uk-width-4-10 uk-text-bold">Fax : </td>
                  <td class="uk-width-6-10">
                     <input class="uk-width-1-1" type="text" name="fax" id="fax" value="<?php echo $edit_row['fax']?>">
                     <span id="validationFax"  style="color: red; display: none;">Please insert Fax</span>
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
                        <span id="validationFranchisorCompanyName"  style="color: red; display: none;">Please insert Franchisor Company Name</span>
                     </td>
                  </tr>
                  <tr class="uk-text-small">
                     <td class="width-4-10 uk-text-bold">Franchisor Registered Address </td>
                     <td class="uk-width-6-10">
                        <input class="uk-width-1-1" type="text" name="franchisor_registered_address1" id="franchisor_registered_address1" value="<?php echo $edit_row["franchisor_registered_address1"]?>">
                        <span id="validationFranchisorRegisteredAddress"  style="color: red; display: none;">Please insert Franchisor Registered Address</span>
                        <input class="uk-width-1-1" type="text" name="franchisor_registered_address2" id="franchisor_registered_address2" value="<?php echo $edit_row["franchisor_registered_address2"]?>">
                        <input class="uk-width-1-1" type="text" name="franchisor_registered_address3" id="franchisor_registered_address3" value="<?php echo $edit_row["franchisor_registered_address3"]?>">
                        <input class="uk-width-1-1" type="text" name="franchisor_registered_address4" id="franchisor_registered_address4" value="<?php echo $edit_row["franchisor_registered_address4"]?>">
                     </td>
                  </tr>
                  <tr class="uk-text-small">
                     <td class="width-4-10 uk-text-bold">Franchisee Company : </td>
                     <td class="uk-width-6-10">
                        <select class="uk-width-1-1" name="master_franchisee_company_id" id="master_franchisee_company_id">
                           <option value="">Select</option>
<?php
$sql="SELECT * from master_franchisee_company where master_code='".$edit_row["master_code"]."'";
$result=mysqli_query($connection, $sql);
while ($row=mysqli_fetch_assoc($result)) {
?>
                           <option value="<?php echo $row['id']?>" <?php if ($edit_row["master_franchisee_company_id"]==$row["id"]) {echo "selected";}?>><?php echo $row["franchisee_company_name"]?></option>
<?php
}
?>
                        </select>
                        <span id="validationFranchiseeCompany"  style="color: red; display: none;">Please insert Franchisee Company</span>
                     </td>
                  </tr>
                  <tr class="uk-text-small">
                     <td class="width-4-10 uk-text-bold">Franchisee Name : </td>
                     <td class="uk-width-6-10">
                        <select class="uk-width-1-1" name="master_franchisee_name_id" id="master_franchisee_name_id">
                           <option value="">Select</option>
<?php
$sql="SELECT * from master_franchisee_name where master_code='".$edit_row["master_code"]."'";
$result=mysqli_query($connection, $sql);
while ($row=mysqli_fetch_assoc($result)) {
?>
                           <option value="<?php echo $row['id']?>" <?php if ($edit_row["master_franchisee_name_id"]==$row["id"]) {echo "selected";}?>><?php echo $row["franchisee_name"]?></option>
<?php
}
?>
                        </select>
                        <span id="validationFranchiseeName"  style="color: red; display: none;">Please insert Franchisee Name</span>
                     </td>
                  </tr>
               </table>
            </div>
            <div class="uk-width-medium-5-10">
               <table class="uk-table uk-table-small">
                  <tr class="uk-text-small">
                     <td class="width-4-10 uk-text-bold">Franchisor Company No. : </td>
                  </tr>
                  <tr>
                     <td class="uk-width-6-10">
                        <input class="uk-width-1-1" type="text" name="franchisor_company_no" id="franchisor_company_no" value="<?php echo $edit_row["franchisor_company_no"]?>">
                        <span id="validationFranchisorCompanyNo"  style="color: red; display: none;">Please insert Franchisor Company No</span>
                     </td>
                  </tr>
<script>
function uploadAttachment() {
   //$("#upload_master").prop('disabled', true);
   $('#upload_master').attr("disabled","disabled");
   var master_code=$("#master_code").val();
	var doc_type=$("#doc_type").val();
   if (master_code!="" & (doc_type!="")) {
      var formdata=new FormData();
      var file=document.getElementById('attachment').files[0];
if(file.size<=26214400){
      formdata.append("attachment", file);
      formdata.append("master_code", master_code);
		formdata.append("doc_type", doc_type);
      $.ajax({
         url : "admin/upload_attachment.php",
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
	   if(master_code==""){
			UIkit.notify("Please provide a master code and a document type");
	   }else{
		   UIkit.notify("Please select an agreement type");
	   }
   }
}

function deleteAttachment(id) {
   UIkit.modal.confirm("<h2>Are you sure to continue?</h2>", function () {
      $.ajax({
         url : "admin/delete_attachment.php",
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
                              <td class="uk-width-8-10">
                                 <input type="file" accept="application/pdf" name="attachment" id="attachment">
                              </td>
                           </tr>
						    <tr>
                              <td class="uk-width-4-10">
                                 <select class="uk-width-1-1" name="doc_type" id="doc_type">
                                    <option value="">Select</option>
                                    <!--<option value="Franchisee Agreement">Franchisee Agreement</option>
                                    <option value="Software License Agreement">Software License Agreement</option>
                                    <option value="Product Supply Agreement">Product Supply Agreement</option>--> 
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
                                 <a id="upload_master" onclick="uploadAttachment()" class="uk-button uk-button-small">Upload</a>
                              </td>
                           </tr>
                        </table>
                        <table class="uk-table uk-table-small" id="tblAttachment">
<?php
$sql="SELECT * from master_agreement_file where master_code='".$edit_row["master_code"]."'";
$result=mysqli_query($connection, $sql);
$num_row=mysqli_num_rows($result);
if ($num_row>0) {
   while ($row=mysqli_fetch_assoc($result)) {
?>
                           <tr class="uk-width-1-1">
                              <td class="uk-width-4-10"><?php echo $row["attachment"]?></td>
							  <td class="uk-width-4-10"><?php echo $row["doc_type"]?></td>
                              <td class="uk-width-2-10">
                                 <a href="admin/uploads/<?php echo $row["attachment"]?>" target="_blank" rel="noopener"><img data-uk-tooltip="{pos:top}" title="View PDF File" src="images/pdf.png"></a>
                                 <a onclick="deleteAttachment('<?php echo $row["id"]?>')"><img data-uk-tooltip="{pos:top}" title="Delete PDF File" src="images/delete.png"></a>
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
if (($_SESSION["UserType"]=="S" || $_SESSION["UserType"]=="H" || $_SESSION["UserType"]=="C" || $_SESSION["UserType"]=="R" || $_SESSION["UserType"]=="CM" || $_SESSION["UserType"]=="T") & (hasRightGroupXOR($_SESSION["UserName"], "MasterEdit"))) {
 ?>
         <button class="uk-button uk-button-primary">Save</button>
<?php
}
?>
      </div>
<?php
if (($_SESSION["UserType"]=="S" || $_SESSION["UserType"]=="H" || $_SESSION["UserType"]=="C" || $_SESSION["UserType"]=="R" || $_SESSION["UserType"]=="CM" || $_SESSION["UserType"]=="T") & (hasRightGroupXOR($_SESSION["UserName"], "MasterEdit"))) {
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
$numperpage=20;
$query="p=$p&m=$m&s=$s";
$pagination=getPagination($pg, $numperpage, $query, $browse_sql, $start_record, $num_row);
$browse_sql.=" limit $start_record, $numperpage";
$browse_result=mysqli_query($connection, $browse_sql);
$browse_num_row=mysqli_num_rows($browse_result);
?>

   <form class="uk-form" name="frmMasterSearch" id="frmMasterSearch" method="get">
      <input type="hidden" name="mode" id="mode" value="BROWSE">
      <input type="hidden" name="p" id="p" value="<?php echo $p?>">
      <input type="hidden" name="m" id="m" value="<?php echo $m?>">
      <input type="hidden" name="pg" value="">

      <div class="uk-grid">
         <div class="uk-width-7-10 uk-text-small">
            <input class="uk-width-1-1" placeholder="Master Code/Company Name/Country" name="s" id="s" value="<?php echo $_GET['s']?>">
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
   <table class="uk-table" id="mydatatable" style="width: 100%;">
    <thead>
      <tr>
         <td data-uk-tooltip="{pos:top}" title="Master Code">Master Code</td>
         <td data-uk-tooltip="{pos:top}" title="Franchisee Company Name">Franchisee Company Name</td>
         <td data-uk-tooltip="{pos:top}" title="Master Type">Master Type</td>
         <td data-uk-tooltip="{pos:top}" title="Country">Country</td>
         <td>State</td>
         <td data-uk-tooltip="{pos:top}" title="Franchisee Name">Franchisee Name</td>
         <td data-uk-tooltip="{pos:top}" title="Franchisee Contact No.">Franchisee Contact No.</td>
         <td data-uk-tooltip="{pos:top}" title="Franchisee Email">Franchisee Email</td>
         <td  class="no_st">Action</td>
      </tr>
	   </thead>
	    <tbody>
<?php
if ($browse_num_row>0) {
   while ($browse_row=mysqli_fetch_assoc($browse_result)) {
      $sha1_id=sha1($browse_row["id"]);
      getFranchiseeCompanyDetails($browse_row["master_code"], $company_name);
      getFranchiseeNameDetails($browse_row["master_code"], $franchisee_name, $franchisee_contact_no, $franchisee_email);
?>
      <tr class="uk-text-small">
         <td><?php echo $browse_row["master_code"]?></td>
         <td><?php echo $company_name?></td>
         <td><?php echo $browse_row["mastertype"]?></td>
         <td><?php echo $browse_row["country"]?></td>
         <td><?php echo getState($browse_row["master_code"], $browse_row["country"])?></td>
         <td><?php echo $franchisee_name?></td>
         <td><?php echo $franchisee_contact_no?></td>
         <td><?php echo $franchisee_email?></td>
         <td>
            <a href="index.php?p=<?php echo $p?>&m=<?php echo $m?>&id=<?php echo $sha1_id?>&mode=EDIT"><img src="images/edit.png"></a>
<?php
if (($_SESSION["UserType"]=="S" || $_SESSION["UserType"]=="H" || $_SESSION["UserType"]=="C" || $_SESSION["UserType"]=="R" || $_SESSION["UserType"]=="CM" || $_SESSION["UserType"]=="T") & (hasRightGroupXOR($_SESSION["UserName"], "MasterEdit"))) {
?>
            <a onclick="doDeleteRecord('<?php echo $sha1_id?>')" href="#" id="btnDelete"><img src="images/delete.png"></a>
<?php
}
?>
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

<div id="dlg"></div>

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
   $(document).ready(function(){
   $('#mydatatable').DataTable({
     'columnDefs': [ {
        'targets': [8], // column index (start from 0)
        'orderable': false, // set orderable false for selected columns
     }]
   });
}); 
</script>
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