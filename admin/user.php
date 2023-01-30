<!--<?php // if($_GET['mode']!=""){ ?>
<a href="/index.php?p=user">                 
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
    <span class="page_title"><img src="/images/title_Icons/Visitor Reg.png">User Access Control (UAC)</span>
</span>

<?php
if ($_SESSION["isLogin"]==1) {
   if ((($_SESSION["UserType"]=="A") || ($_SESSION["UserType"]=="O") || ($_SESSION["UserType"]=="S") || ($_SESSION["UserType"]=="H") || ($_SESSION["UserType"]=="C") || ($_SESSION["UserType"]=="R") || ($_SESSION["UserType"]=="CM") || ($_SESSION["UserType"]=="T")) & (hasRightGroupXOR($_SESSION["UserName"], "UserEdit|UserView"))) {
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

      function getUserType($user_type) {
         switch ($user_type) {
            case "S" : return "Super Admin"; break;
            case "A" : return "Centre Admin"; break;
             case "O" : return "Centre Staff"; break;
            // case "R" : return "Regional Office"; break;
            // case "C" : return "Country Office"; break;
            // case "T" : return "Territory Office"; break;
            // case "P" : return "Province Office"; break;
         }
      }

      function getCentreNameUser($centre_code) {
         global $connection;

         $sql="SELECT * from centre where centre_code='$centre_code'";
         $result=mysqli_query($connection, $sql);
         $row=mysqli_fetch_assoc($result);

         return $row["centre_name"];
      }

      include_once("user_func.php");
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
        <h2 class="uk-text-center myheader-text-color myheader-text-style">User Information</h2>
    </div>
    <div class="uk-form uk-form-small">
        <?php
        if ((($_SESSION["UserType"]=="A") || ($_SESSION["UserType"]=="O") || ($_SESSION["UserType"]=="S")  || ($_SESSION["UserType"]=="H") || ($_SESSION["UserType"]=="C") || ($_SESSION["UserType"]=="R") || ($_SESSION["UserType"]=="CM") || ($_SESSION["UserType"]=="T")) & (hasRightGroupXOR($_SESSION["UserName"], "UserEdit"))) {
        ?>
        <form name="frmUser" id="frmUser" method="post" action="index.php?p=user&pg=<?php echo $pg?>&mode=SAVE">
            <?php
            }
            ?>
			
            <div class="uk-grid">
                <div class="uk-width-medium-5-10">
                    <table class="uk-table uk-table-small">
						<tr class="uk-text-small">
							<td class="uk-width-4-10 uk-text-bold">Master Type <span class="text-danger">*</span>: </td>
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
                        <td style="border:none;" class="uk-width-3-10 uk-text-bold">Country<span class="text-danger">*</span>:</td>
                        <td style="border:none;" class="uk-width-7-10">
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
                        <td style="border:none;" class="uk-width-3-10 uk-text-bold">State<span class="text-danger">*</span>:</td>
                        <td style="border:none;" class="uk-width-7-10">
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
                        <tr class="uk-text-small">
                            <td class="uk-width-3-10 uk-text-bold">User Name <span class="text-danger">*</span>: </td>
                            <td class="uk-width-7-10">
                                <input class="uk-width-1-1" type="text" name="user_name" id="user_name" value="<?php echo $edit_row['user_name']?>">
                                <input type="hidden" name="hidden_user_name" id="hidden_user_name" value="<?php echo $edit_row['user_name']?>">
								 <span id="validationusername"  style="color: red; display: none;">Please insert User Name</span>
                            </td>
                        </tr>
                        <tr class="uk-text-small">
                            <td class="uk-width-3-10 uk-text-bold">User Type <span class="text-danger">*</span>: </td>
                            <td class="uk-width-7-10">
                                <select name="user_type" id="user_type" class="uk-width-1-1">
                                    <option value="">Select</option>
                                    <?php
                                    if (($_SESSION["UserType"]=="S")  || ($_SESSION["UserType"]=="H") || ($_SESSION["UserType"]=="C") || ($_SESSION["UserType"]=="R") || ($_SESSION["UserType"]=="CM") || ($_SESSION["UserType"]=="T")) {
                                        ?>
                                        <option value="H" <?php if ($edit_row['user_type']=='H') {echo "selected";}?>>HQ</option>
                                        <option value="S" <?php if ($edit_row['user_type']=='S') {echo "selected";}?>>Super Admin</option>
										<option value="A" <?php if ($edit_row['user_type']=='A') {echo "selected";}?>>Centre Admin</option>
                                        <option value="C" <?php if ($edit_row['user_type']=='C') {echo "selected";}?>>Corporate</option>
                                        <option value="R" <?php if ($edit_row['user_type']=='R') {echo "selected";}?>>Regional master</option>
                                        <option value="CM" <?php if ($edit_row['user_type']=='CM') {echo "selected";}?>>Country master</option>
                                        <option value="T" <?php if ($edit_row['user_type']=='T') {echo "selected";}?>>Territory master</option>
                                        <?php
                                    }
                                    ?>
                                    <?php
                                    if (($_SESSION["UserType"]=="A") || ($_SESSION["UserType"]=="O"))  {
                                        ?>
                                        <option value="O" <?php if ($edit_row['user_type']=='O') {echo "selected";}?>>Centre Staff</option>
                                        <?php
                                    }
                                    ?>
                                </select>
								<span id="validationusertype"  style="color: red; display: none;">Please select User Type</span>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="uk-width-medium-5-10">
                    <table class="uk-table uk-table-small">
                       
                            <tr class="uk-text-small">
                                <td class="uk-width-3-10 uk-text-bold">User Role <span class="text-danger">*</span>: </td>
                                <td class="uk-width-7-10">
                                    <select name="user_role" id="user_role" class="uk-width-1-1">
                                        <option value="">Select</option>
                        <?php
                        if (($_SESSION["UserType"]=="S") & (strtoupper($_SESSION["UserName"])=="SUPER")) {
                            ?>
                                        <option disabled>------------------Super Admin--------------------</option>
                                        <option value="IT Admin" <?php if ($edit_row["user_role"]=="IT Admin") {echo "selected";}?>>IT Admin</option>
                                        <option value="Logistics Admin" <?php if ($edit_row["user_role"]=="Logistics Admin") {echo "selected";}?>>Logistics Admin</option>
                                        <option value="Finance Admin" <?php if ($edit_row["user_role"]=="Finance Admin") {echo "selected";}?>>Finance Admin</option>
                                        <option value="Legal Admin" <?php if ($edit_row["user_role"]=="Legal Admin") {echo "selected";}?>>Legal Admin</option>
                                        <option value="CGO Admin" <?php if ($edit_row["user_role"]=="CGO Admin") {echo "selected";}?>>CGO Admin</option>
                                        <option value="FLD Admin" <?php if ($edit_row["user_role"]=="FLD Admin") {echo "selected";}?>>FLD Admin</option>
                                        <option value="Super Admin" <?php if ($edit_row["user_role"]=="Super Admin") {echo "selected";}?>>Super Admin</option>
                                        <option value="Master Admin" <?php if ($edit_row["user_role"]=="Master Admin") {echo "selected";}?>>Master Admin</option>
                                        <option value="Region Admin" <?php if ($edit_row["user_role"]=="Region Admin") {echo "selected";}?>>Region Admin</option>
                                        <option value="Country Admin" <?php if ($edit_row["user_role"]=="Country Admin") {echo "selected";}?>>Country Admin</option>
                                        <option value="State/Province Admin" <?php if ($edit_row["user_role"]=="State/Province Admin") {echo "selected";}?>>State/Province Admin</option>
                                        <option disabled>------------------Centre Admin--------------------</option>
                            <?php
                        }
                        ?>
                                        <option value="Operator" <?php if ($edit_row["user_role"]=="Operator") {echo "selected";}?>>Operator</option>
                                        <option value="Admin/Principle" <?php if ($edit_row["user_role"]=="Admin/Principle") {echo "selected";}?>>Admin/Principle</option>
                                        <option value="Teacher" <?php if ($edit_row["user_role"]=="Teacher") {echo "selected";}?>>Teacher</option>
                                    </select>
									<span id="validationuserrole"  style="color: red; display: none;">Please select User Role</span>
                                </td>
                            </tr>
							  <tr class="uk-text-small">
								<td class="uk-width-3-10 uk-text-bold">Email : </td>
								<td class="uk-width-7-10">
									<input class="uk-width-1-1" type="email" name="email" id="email" value="<?php echo $edit_row['email']?>">
									<span id="validationemail"  style="color: red; display: none;">Please insert Email</span>
									
								</td>
							</tr>
							<tr class="uk-text-small">
								<td class="uk-width-3-10 uk-text-bold">Password <span class="text-danger">*</span>: </td>
								<td class="uk-width-7-10">
									<input class="uk-width-1-1" type="password" name="password" id="password" value="<?php echo $edit_row['password']?>">
									<input type="hidden" name="hidden_password" id="hidden_password" value="<?php echo $edit_row['password']?>">
									<span id="validationpassword"  style="color: red; display: none;">Please insert Password</span>
								</td>
							</tr>
						
						<tr class="uk-text-small" id="centre_me">
                            <td class="uk-width-3-10 uk-text-bold">Centre Name <span class="text-danger">*</span>: </td>
                            <td class="uk-width-7-10">
                            <?php if ($_SESSION["UserType"]=="S") { ?>
                                    <select name="centre_name" id="centre_name" class="uk-width-1-1">
                                        <option value="">Select</option>
                                        <?php
										//$sql="SELECT * from centre order by company_name";
                                        $sql="SELECT * from centre order by company_name ";
                                        $result=mysqli_query($connection, $sql);
                                        while ($row=mysqli_fetch_assoc($result)) {
                                            ?>
                                            <option value="<?php echo $row["company_name"]?>" <?php if ($row["company_name"]==$edit_row['centre_name']) {echo "selected";}?>><?php echo $row["company_name"]?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                            <?php }else { ?>
                                <select name="centre_name" id="centre_name" class="uk-width-1-1">
                                        <option value="">Select</option>
                                        <?php
										//$sql="SELECT * from centre order by company_name";
                                        $sql="SELECT * from centre where centre_code = '".$_SESSION["CentreCode"]."'  order by company_name";
                                        $result=mysqli_query($connection, $sql);
                                        while ($row=mysqli_fetch_assoc($result)) {
                                            ?>
                                            <option value="<?php echo $row["company_name"]?>" <?php if ($row["company_name"]==$edit_row['centre_name']) {echo "selected";}?>><?php echo $row["company_name"]?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                    <?php } ?>
                                    <span id="validationcentrename"  style="color: red; display: none;">Please select Centre Name</span>
                            </td>
                            
                        </tr>
                        <tr class="uk-text-small" id="centre_me">
                        <td class="uk-width-3-10 uk-text-bold">Helpdesk Role <span class="text-danger">*</span>: </td>
                            <td class="uk-width-7-10">
                            
                                <select name="helpdesk_role" id="helpdesk_role" <?php if(isset($_GET['mode']) && $_GET['mode'] == 'EDIT') { echo 'disabled'; } ?> class="uk-width-1-1">
                                    <option value="">Select</option>

                                    <?php
                                        $buffer = curlCall("admin/get-role-list/");

                                        if($buffer != '')
                                        {
                                            $roleArray = json_decode($buffer,true);

                                            foreach($roleArray as $key => $value)
                                            {
                                    ?>
                                                <option value="<?php echo $value['name']; ?>" <?php if ($edit_row["helpdesk_role"]==$value['name']) { echo "selected"; }?> ><?php echo $value['name']; ?></option>
                                    <?php
                                            }
                                        }
                                    ?>
                                </select>
                                
                                <span id="validationhelpdeskrole"  style="color: red; display: none;">Please select helpdesk role</span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <br>
            <div class="uk-text-center">
                <?php
                if ((($_SESSION["UserType"]=="A") || ($_SESSION["UserType"]=="O") || ($_SESSION["UserType"]=="S")  || ($_SESSION["UserType"]=="H") || ($_SESSION["UserType"]=="C") || ($_SESSION["UserType"]=="R") || ($_SESSION["UserType"]=="CM") || ($_SESSION["UserType"]=="T")) & (hasRightGroupXOR($_SESSION["UserName"], "UserEdit"))) {
                    ?>
                    <button class="uk-button uk-button-primary">Save</button>
                    <?php
                }
                ?>
            </div>
            <?php
            if ((($_SESSION["UserType"]=="A") || ($_SESSION["UserType"]=="O") || ($_SESSION["UserType"]=="S")  || ($_SESSION["UserType"]=="H") || ($_SESSION["UserType"]=="C") || ($_SESSION["UserType"]=="R") || ($_SESSION["UserType"]=="CM") || ($_SESSION["UserType"]=="T")) & (hasRightGroupXOR($_SESSION["UserName"], "UserEdit"))) {
            ?>
        </form>
    <?php
    }
    ?>

    </div>


<?php
echo $pagination;
?>

<!-- USER INFORMATION SECTION ENDS HERE-->

<br>

<!--USER LISTING STARTS-->

    <div class="uk-width-1-1 myheader">
        <h2 class="uk-text-center myheader-text-color myheader-text-style">Searching</h2>
    </div>

    <form class="uk-form" name="frmCentreSearch" id="frmCentreSearch" method="get">
        <input type="hidden" name="mode" id="mode" value="BROWSE">
        <input type="hidden" name="p" id="p" value="<?php echo $p?>">
        <input type="hidden" name="m" id="m" value="<?php echo $m?>">
        <input type="hidden" name="pg" value="">

        <div class="uk-grid">
            <div class="uk-width-7-10 uk-text-small">
                <input class="uk-width-1-1" placeholder="Username/Centre Name" name="s" id="s" value="<?php echo $_GET['s']?>">
            </div>
            <div class="uk-width-3-10">
                <button class="uk-button uk-width-1-1 blue_button">Search</button>
            </div>
        </div>
    </form>

   <br>

    <div class="uk-width-1-1 myheader">
        <h2 class="uk-text-center myheader-text-color myheader-text-style">Listing</h2>
    </div>
    <?php
   /*  $numperpage=20;
    $query="p=$p&m=$m&s=$s";
    $pagination=getPagination($pg, $numperpage, $query, $browse_sql, $start_record, $num_row);
    // $browse_sql.=" limit $start_record, $numperpage";
    $browse_result=mysqli_query($connection, $browse_sql);
    $browse_num_row=mysqli_num_rows($browse_result);
    // echo $browse_sql; */
    ?>
    <div class="uk-overflow-container">
        <table class="uk-table" id="mydatatable" style="width: 100%;font-size:12px;">
            <thead>
                <tr class="uk-text-bold uk-text-small">
                    <td>User Name</td>
                    <td>User Type</td>
                    <td>User Role</td>
                    <td>Centre name</td>
                    <td>Action</td>
                </tr>
            </thead>
            <tbody>
                <?php
                /* if ($browse_num_row>0) {
                    while ($browse_row=mysqli_fetch_assoc($browse_result)) {
                        $sha1_id=sha1($browse_row["id"]);
                        ?>
                        <tr class="uk-text-small">
                            <td><?php echo $browse_row["user_name"]?></td>
                            <td data-uk-tooltip="{pos:top}" title="<?php echo getUserType($browse_row['user_type']);?>">
                            
                            
                            <?php $user_type = $browse_row["user_type"];
                            
                                if($user_type =="S"){
                                    echo "Super admin";
                                }elseif($user_type =="A"){
                                    echo "Centre Admin";
                                }elseif($user_type =="C"){
                                    echo "Corporate";
                                }elseif($user_type =="T"){
                                    echo "Territory master";
                                }elseif($user_type =="R"){
                                    echo "Regional master";
                                }elseif($user_type =="CM"){
                                    echo "Country master";
                                }elseif($user_type =="H"){
                                    echo "HQ";
                                }elseif($user_type =="O"){
                                    echo "Centre Staff";
                                }else{
                                    echo $user_type;
                                }
                            ?>
                            
                            
                            </td>
                            <td><?php echo $browse_row["user_role"]?></td>
                            <td><?php echo $browse_row["centre_name"]?></td>
                            <?php 
                                $centrecode = $browse_row["centre_code"];
                                $sql="SELECT * from centre where centre_code='$centrecode'";
                                $result=mysqli_query($connection, $sql);
                                $row=mysqli_fetch_assoc($result);
                            ?>
                            <td style="width: 10%;">
                                <a data-uk-tooltip title="Edit user" href="index.php?p=<?php echo $p?>&m=<?php echo $m?>&id=<?php echo $sha1_id?>&mode=EDIT"><i class="fa fa-edit" style="font-size: 1.1rem;"></i></a>
                                
                                <a  data-uk-tooltip title="Delete user"onclick="doDeleteRecord('<?php echo $sha1_id?>')" href="#" id="btnDelete"><i class="fa fa-trash-alt text-danger" style="font-size: 1.1rem;"></i></a>
                            
                                <?php
                                if ((($_SESSION["UserType"]=="A") || ($_SESSION["UserType"]=="O") || ($_SESSION["UserType"]=="S")  || ($_SESSION["UserType"]=="H") || ($_SESSION["UserType"]=="C") || ($_SESSION["UserType"]=="R") || ($_SESSION["UserType"]=="CM") || ($_SESSION["UserType"]=="T")) & (hasRightGroupXOR($_SESSION["UserName"], "UserRightsEdit|UserRightsView"))) {
                                    ?>
                                    <a  data-uk-tooltip title="User Control" href="index.php?p=userright&user_name=<?php echo $browse_row['user_name']?>"><img title="User Right" src="images/fingerprint.png" style="font-size: 1.1rem;"></a>
                                    <?php
                                }
                                ?>
                            </td>
                        </tr>
                        <?php
                    }
                } else {
                    echo "<tr><td colspan='6'>No Record Found</td></tr>";
                } */
                ?>
            </tbody>
        </table>
    </div>

    <br>




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

$s = isset($_GET['s']) ? $_GET['s'] : '';

?>
<script>

$(document).ready(function(){
    $('#mydatatable').DataTable({
      'columnDefs': [ { 
          'targets': [0,4], // column index (start from 0)
          'orderable': false, // set orderable false for selected columns
      }],
    "order": [[ 0, "asc" ]],
      "bProcessing": true,
      "bServerSide": true,
      "sAjaxSource": "admin/serverresponse/user.php?p=<?php echo $_GET['p']; ?>&s=<?php echo $s; ?>"
    });
}); 

$(document).ready(function(){
	$('#user_type').change(function(){
	var user_type= $('#user_type').val(); 
	$("#centre_me").show();
	if(user_type =='S'){
		$("#centre_me").hide();
	}else{
		$("#centre_me").show();
	}
	})

  $("#frmUser").submit(function(e){
    
    var country=$("#country").val();
    var state=$("#state").val();
    var mastertype=$("#mastertype").val();
    var user_name=$("#user_name").val();
    var user_type=$("#user_type").val();
    var user_role=$("#user_role").val();
    var email=$("#email").val();
    var centre_name=$("#centre_name").val();
    var password=$("#password").val();
    var helpdesk_role=$("#helpdesk_role").val();

    console.log(state);

    if (country =="" || state ==null || mastertype =="" || user_name =="" || user_type =="" || user_role =="" || password =="" || helpdesk_role=="") {

   e.preventDefault();
    //alert("Please fill up mandatory fields marked *");

        if (country =="" ) {
            $('#validationCountry').show();
        }else{
            $('#validationCountry').hide();
        }

         if (state ==null) {
            $('#validationState').show();
        }else{
            $('#validationState').hide();
        }

         if (mastertype =="") {
            $('#validationMasterType').show();
        }else{
            $('#validationMasterType').hide();
        }

         if (user_name =="") {
            $('#validationusername').show();
        }else{
            $('#validationusername').hide();
        }

         if (user_type =="") {
            $('#validationusertype').show();
        }else{
            $('#validationusertype').hide();
        }
		if (user_role =="") {
            $('#validationuserrole').show();
        }else{
            $('#validationuserrole').hide();
        }
		// if (email =="") {
        //     $('#validationemail').show();
        // }else{
        //     $('#validationemail').hide();
        // }
		if (password =="") {
            $('#validationpassword').show();
        }else{
            $('#validationpassword').hide();
        }

        if (helpdesk_role =="") {
            $('#validationhelpdeskrole').show();
        }else{
            $('#validationhelpdeskrole').hide();
        }
		
        
        
  }
    if(user_type !='S'){
        if (centre_name =="") {
            $('#validationcentrename').show();
            e.preventDefault();
            return false;
        }else{
            $('#validationcentrename').hide();
        }
    }
  });
});
</script>