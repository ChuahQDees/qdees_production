<a href="index.php?p=<?php echo $_GET["back"] ? $_GET["back"] : 'collection_pg1'?>">
    <span class="btn-qdees"><i class="fa fa-arrow-left" style="margin-right: 20px;"></i>Back</span>
</a>
<span>
    <span class="page_title"><img src="/images/title_Icons/Payment.png">Payment</span>
</span>

<?php
session_start();
if (($_SESSION["isLogin"]==1) & (($_SESSION["UserType"]=="A") || ($_SESSION["UserType"]=="O")) &
(hasRightGroupXOR($_SESSION["UserName"], "PointOfSalesEdit|PointOfSalesView"))) {
include_once("mysql.php");

$ssid=$_GET["ssid"];

function getDOWString($dow) {
   switch ($dow) {
      case 0 : return "Sunday"; break;
      case 1 : return "Monday"; break;
      case 2 : return "Tuesday"; break;
      case 3 : return "Wednesday"; break;
      case 4 : return "Thursday"; break;
      case 5 : return "Friday"; break;
      case 6 : return "Saturday"; break;
      case 7 : return "Daily"; break;
   }
}

if ($_GET['status'] == "enerror"){
    echo "<script>UIkit.notify('Must choose at least one Enhanced Foundation')</script>";
}
?>


<div id="thetop" class="uk-margin-right uk-margin-top">
    <div class="uk-width-1-1 myheader">
        <h2 class="uk-text-center myheader-text-color myheader-text-style">Transaction History</h2>
    </div>

    <div style="margin-left: 0;" class="uk-overflow-container uk-grid uk-grid-small">
        <div class="uk-width-medium-10-10 uk-text-center">
            <a href="index.php?p=collection&ssid=<?php echo $ssid ?><?php echo $_GET["back"] ? '&back='.$_GET["back"] : ''?>"
                class="uk-button blue_button" style="padding: .4em 3em;">Pay Fees</a>
            <button onclick="dlgProductBought()" class="blue_button_s1 blue_button" style="padding: .4em 3em;">View Product Bought</button>

            <table class="uk-table">
                <tr class="uk-text-bold">
                    <td>No.</td>
                    <td>Student Name</td>
                    <td>Student Code</td>
                    <td>Receipt Date</td>
                    <td>Receipt No.</td>
                    <td>Payment Method</td>
                    <td class="uk-text-right">Amount</td>
                </tr>
                <?php
$sql="SELECT s.name, s.student_code, c.collection_date_time, c.batch_no, pd.payment_method, sum(amount) as amount, sum(discount) as discount from student s, collection c 
  left join payment_detail pd on pd.id=c.payment_detail_id where s.id=c.student_id and sha1(student_id)='$ssid'  and c.void=0
  group by c.batch_no order by c.collection_date_time desc";
 //echo $sql;
$result=mysqli_query($connection, $sql);
$num_row=mysqli_num_rows($result);
if ($num_row>0) {
   $count=0;
   while ($row=mysqli_fetch_assoc($result)) {
	  
      $count++;
      switch ($row["payment_method"]) {
         case "CC" : $payment_method="Credit Card"; break;
         case "CASH" : $payment_method="Cash"; break;
         case "BT" : $payment_method="Bank Transfer"; break;
         case "CHEQUE" : $payment_method="Cheque"; break;
         default : $payment_method=$row["payment_method"]; break;
      }
?>
                <tr>
                    <td><?php echo $count?></td>
                    <td><?php echo $row["name"]?></td>
                    <td><?php echo $row["student_code"]?></td>
                    <td><?php echo date('d/m/Y', strtotime($row["collection_date_time"]))?></td>
                    <td><a target="_blank"
                            href="admin/print_receipt.php?batch_no=<?php echo sha1($row["batch_no"])?>&display=1"><?php echo $row["batch_no"]?></a>
                    </td>
                    <td><?php echo $payment_method?></td>
                    <td class="uk-text-right"><?php echo number_format($row["amount"], 2)?></td>
                </tr>
                <?php
				
   }
} else {
   echo "<tr class='uk-text-bold uk-text-small'><td colspan='7'>No Record Found</td></tr>";
}

?>
            </table>
        </div>
    </div>
    <hr>
<?php 
$sql1="SELECT s.name, s.student_code from student s where sha1(id)='$ssid'";
 
$result1=mysqli_query($connection, $sql1);
$num_row=mysqli_num_rows($result1);
    while ($row=mysqli_fetch_assoc($result1)) {
	   $student_code= $row["student_code"];
		$name= $row["name"];
    }

   $sql3="SELECT * from programme_selection where id = '".$_GET["psid"]."'";

$result3=mysqli_query($connection, $sql3);
$row3=mysqli_fetch_assoc($result3);

?>

    <!-- 2nd sec   -->
    <div class="uk-width-1-1 myheader">
        <h2 class="uk-text-center myheader-text-color myheader-text-style">PROGRAMME SELECTION</h2>
    </div>
    <form name="" id="entryLevel" method="post" class="uk-form uk-form-small"
        action="index.php?p=student_list_func&ssid=<?php echo $ssid?>">
        <div style="margin-left: 0;" class="uk-overflow-container uk-grid uk-grid-small"> 

        <input type="hidden" name="form_mode" id="form_mode" value="<?php if($_GET["mode"]=="EDIT"){ echo "EDIT"; }else{ echo "Add"; } ?>">
        <input type="hidden" name="student_code" id="student_code" value="<?php echo $student_code; ?>">
        <input type="hidden" name="name" id="name" value="<?php echo $name; ?>">
        <input type="hidden" name="programme_selection_id" id="programme_selection_id" value="<?php echo $row3["id"]; ?>">
            <div class="uk-width-medium-10-10">
                <table class="uk-table uk-table-small">
                    <tr class="uk-text-small">
                        <td class="uk-width-3-10 uk-text-bold">Student Entry Level <span class="text-danger">*</span>:
                        </td>
                    </tr>
                    <tr class="uk-text-small">
                        <script>
                        function enhFoundationDefault(){ //By default, need to have at least one enhanced foundation
                            d = document.getElementById("student_entry_level").value;

                            if (d != "EDP"){
                                document.getElementById("foundation_int_english").value = "1";
                            }else{
                                document.getElementById("foundation_int_english").value = "0";
                            }
                        }
                        </script>
                        <td class="uk-width-3-10">
                        <select <?php if ($_GET["mode"]!="EDIT"){ ?> onChange="enhFoundationDefault()" <?php } ?> name="student_entry_level" id="student_entry_level" class="uk-width-1-1" style="width: 100px;">
                                <option value="">Select</option>
                                <option <?php if($row3["student_entry_level"]=="EDP"){ echo "selected"; } ?> value="EDP">EDP</option>
                                <option <?php if($row3["student_entry_level"]=="QF1"){ echo "selected"; } ?> value="QF1">QF1</option>
                                <option <?php if($row3["student_entry_level"]=="QF2"){ echo "selected"; } ?> value="QF2">QF2</option>
                                <option <?php if($row3["student_entry_level"]=="QF3"){ echo "selected"; } ?> value="QF3">QF3</option>
                                </select>
                            <span id="validationStudentEntryLevel" style="color: red; display: none;">Please select Student Entry Level</span>
                        </td>
                       
                    </tr>
                </table>
            </div>
            <div class="uk-width-medium-10-10"><br>
                <table class="uk-table uk-table-small">
                    <tr class="uk-text-small">
                        <td class="uk-width-1-10 uk-text-bold">Programme package <span class="text-danger">*</span>:</td>
						<td class="uk-width-1-10 uk-text-bold">Fees Setting<span class="text-danger">*</span>:</td>
                        <td class="uk-width-1-10 uk-text-bold">Foundation Mandarin:</td>
                        <td class="uk-width-1-10 uk-text-bold">Afternoon Programme:</td>
						<td class="uk-width-3-10 uk-text-bold">Enhanced Foundation:</td>
                        <td class="uk-width-1-10 uk-text-bold">Commencement Date <span class="text-danger">*</span>:</td>
                        <td class="uk-width-1-10 uk-text-bold">End Date <span class="text-danger">*</span>:</td>       
                    </tr>
                    <?php 
                        $sql1="SELECT fl.*, fs.fees_structure from student_fee_list fl inner join fee_structure fs on fl.fee_id = fs.id  where sha1(fl.student_id) = '$ssid' and fl.programme_selection_id = '".$row3["id"]."'";
                    
                        $result1=mysqli_query($connection, $sql1);
                        $num_row1=mysqli_num_rows($result1);
                        if ($num_row1>0) {
                            while ($row1=mysqli_fetch_assoc($result1)) {
                        ?>
                                <tr class="tIdRow_c" style="margin-bottom: 10px;">
                                
                                    <input type="hidden" class="old_fee_id" name="old_fee_id[]" value="<?php echo $row1['fee_id']; ?>" >

                                    <input type="hidden" class="uniform_default" name="uniform_default[]" value="<?php echo $row1['uniform_default']; ?>" >

                                    <input type="hidden" class="uniform_default" name="uniform_default[]" value="<?php echo $row1['uniform_adjust']; ?>" >

                                    <input type="hidden" class="gymwear_default" name="gymwear_default[]" value="<?php echo $row1['gymwear_default']; ?>" >

                                    <input type="hidden" class="gymwear_adjust" name="gymwear_adjust[]" value="<?php echo $row1['gymwear_adjust']; ?>" >

                                    <td class="uk-width-1-10">
                                        <select name="programme_duration[]" class="uk-width-1-1 subject_3 programme_duration" id="programme_duration" onchange="change_programme_package(this)" style="width: 90px">
                                            <option value="">Select</option>
                                            <option <?php echo ($row1['programme_duration']== "Full Day"? 'selected' : ''); ?> value="Full Day">Full Day</option>
                                            <option <?php echo ($row1['programme_duration']== "Half Day"? 'selected' : ''); ?> value="Half Day">Half Day</option>
                                            <option <?php echo ($row1['programme_duration']== "3/4 Day"? 'selected' : ''); ?> value="3/4 Day">3/4 Day</option>
                                        </select>	
                                        <span id="validationDuration" style="color: red; display: none;">Please select Programme package</span>
                                    </td>
					                <td class="uk-width-1-10">
                                        <select name="fee_id[]" id="fee_id" class="uk-width-1-1 fee_id" style="width: 150px">
                                            <!-- <option value="<?php echo $row1['fees_structure']?>"><?php echo $row1['fees_structure']?></option> -->
                                            <?php
                                                $sql="SELECT * from fee_structure where subject='".$row3["student_entry_level"]."' and programme_package ='".$row1['programme_duration']."' and status='Approved' and centre_code = '".$_SESSION["CentreCode"]."' and extend_year =  '".$_SESSION['Year']."'";
                                                $result22=mysqli_query($connection, $sql);
                                                while ($row22=mysqli_fetch_assoc($result22)) {
                                                    ?>
                                            <option value="<?php echo $row22['id']?>"
                                                <?php if ($row22["id"]==$row1['fee_id']) {echo "selected";}?>>
                                                <?php echo $row22["fees_structure"]?></option>
                                            <?php } ?>
                                        
                                        </select>
                                        
                                        <span id="validationfee_name" style="color: red; display: none;">Please select Fees
                                            Setting</span>	
                                    </td>
                                    <td class="uk-width-1-10">
                                        <select name="foundation_mandarin[]" id="foundation_mandarin" class="uk-width-1-1">
                                            <option <?php if($row1["foundation_mandarin"]){ echo "selected"; } ?> value="0">No</option>
                                            <option <?php if($row1["foundation_mandarin"]){ echo "selected"; } ?> value="1">Yes</option>
                                        
                                        </select>
                                        <span id="validationFoundationMandarin" style="color: red; display: none;">Please
                                            select Foundation Mandarin</span>
                                    </td>
                                    <td class="uk-width-1-10 ">
                                        <select name="afternoon_programme[]" id="afternoon_programme" style="<?php echo ($row1['programme_duration'] == "Half Day" ? 'display:none;' : ''); ?>" class="uk-width-1-1 afternoon_programme">
                                            <option <?php if($row1["afternoon_programme"]){ echo "selected"; } ?> value="0">No</option>
                                            <option <?php if($row1["afternoon_programme"]){ echo "selected"; } ?> value="1">Yes</option>
                                        </select>
                                        <span id="validationAfternoon" style="color: red; display: none;">Please select Afternoon Programme</span>
                                    </td>

                                    <td class="uk-width-5-10">
                                        Int. Eng:&nbsp;<select type="checkbox" name="foundation_int_english[]"
                                            id="foundation_int_english" class="uk-width-1-1" style="width: 61px;">
                                            <option <?php echo ($row1['foundation_int_english']==0? 'selected' : ''); ?> value="0">No</option>
                                            <option <?php if ($_GET["mode"]!="EDIT"){ ?> selected <?php } ?><?php echo ($row1['foundation_int_english']==1? 'selected' : ''); ?> value="1">Yes</option>
                                            </select>&nbsp;&nbsp;
                                        EF IQ Maths:&nbsp;<select type="checkbox" name="foundation_iq_math[]" id="foundation_iq_math"
                                            class="uk-width-1-1" style="width: 61px;">
                                            <option <?php echo ($row1['foundation_iq_math']==0? 'selected' : ''); ?> value="0">No</option>
                                            <option <?php echo ($row1['foundation_iq_math']==1? 'selected' : ''); ?> value="1">Yes</option>
                                            </select>
                                        &nbsp;&nbsp;
                                        EF Mandarin:&nbsp;<select type="checkbox" name="foundation_int_mandarin[]" id="foundation_int_mandarin" class="uk-width-1-1" style="width: 61px;">
                                            <option <?php echo ($row1['foundation_int_mandarin']==0? 'selected' : ''); ?> value="0">No</option>
                                            <option <?php echo ($row1['foundation_int_mandarin']==1? 'selected' : ''); ?> value="1">Yes</option>
                                            </select>
                                        &nbsp;&nbsp;
                                        Int. Art:&nbsp;&nbsp;<select type="checkbox" name="foundation_int_art[]" id="foundation_int_art" class="uk-width-1-1" style="width: 61px;">
                                            <option <?php echo ($row1['foundation_int_art']==0? 'selected' : ''); ?> value="0">No</option>
                                            <option <?php echo ($row1['foundation_int_art']==1? 'selected' : ''); ?> value="1">Yes</option>
                                            </select>
                                        &nbsp;&nbsp;
                                        Pendidikan Islam/Jawi:&nbsp;&nbsp;<select type="checkbox" name="pendidikan_islam[]" value="1" id="pendidikan_islam" class="uk-width-1-1" style="width: 61px;">
                                            <option <?php echo ($row1['pendidikan_islam']==0? 'selected' : ''); ?> value="0">No</option>
                                            <option <?php echo ($row1['pendidikan_islam']==1? 'selected' : ''); ?> value="1">Yes</option>
                                            </select>
                                        &nbsp;&nbsp;
                                        <span class="robotic_plus" style="<?php echo ($row1['programme_duration'] != "Half Day" ? 'display:none;' : ''); ?>" >Robotics Plus:&nbsp;&nbsp;</span><select type="checkbox" name="robotic_plus[]" id="robotic_plus" class="uk-width-1-1 robotic_plus" style="width: 61px;<?php echo ($row1['programme_duration'] != "Half Day" ? 'display:none;' : ''); ?>">
                                            <option <?php echo ($row1['robotic_plus']==0? 'selected' : ''); ?> value="0">No</option>
                                            <option <?php echo ($row1['robotic_plus']==1? 'selected' : ''); ?> value="1">Yes</option>
                                            </select>
                                        &nbsp;&nbsp; <br>
                                        <span id="validationCheckbox3" style="color: red; display: none;">Please tick any
                                            checkbox</span>
                                    </td>
                                    <td class="uk-width-1-10">
                                        <input class="uk-width-1-1 programme_date" type="text" data-uk-datepicker="{format: 'YYYY/MM/DD'}"
                                            name="programme_date[]" id="programme_date"
                                            value="<?php echo date('Y/m/d',strtotime($row1['programme_date'])); //convertDate2British($row1['programme_date']); ?>"><br>
                                        <span id="validationCommencement" style="color: red; display: none;">Please select
                                            Commencement Date</span>
                                    </td>
                                    <td class="uk-width-1-10">
                                        <input class="uk-width-1-1 programme_date_end" type="text" data-uk-datepicker="{format: 'YYYY/MM/DD'}"
                                            name="programme_date_end[]" id="programme_date_end"
                                            value="<?php echo date('Y/m/d',strtotime($row1['programme_date_end'])); //convertDate2British($row1['programme_date_end']); ?>" style="width: 90px"><br>
                                        <span id="validationCommencementEndDate" style="color: red; display: none;">Please select
                                            Commencement End Date</span>
                                    </td>
                                </tr>
                    <?php
                            } 
                        }
                        else 
                        {
                    ?>
                    <tr class="tIdRow_c" style="margin-bottom: 10px;">   
                       <td class="uk-width-1-10">
							<select name="programme_duration[]" onchange="change_programme_package(this)" class="uk-width-1-1 subject_3 programme_duration" id="programme_duration" style="width: 90px">
                                <option value="">Select</option>
                                <option value="Full Day">Full Day</option>
                                <option value="Half Day">Half Day</option>
                                <option value="3/4 Day">3/4 Day</option>
							</select>	
                        <?php
							// $fields=array("Full Day"=>"Full Day", "Half Day"=>"Half Day", "3/4 Day"=>"3/4 Day");
							// generateSelectArray($fields, "programme_duration", "class='uk-width-1-1 subject_3' id='programme_duration' ", $data_array["programme_duration"]);
						 ?>
                            <span id="validationDuration" style="color: red; display: none;">Please select
                                Programme package</span>
                        </td>
					   <td class="uk-width-1-10">
                            <select name="fee_id[]" id="fee_id" class="uk-width-1-1 fee_id" style="width: 150px">
                                <?php
                                    $sql="SELECT * from fee_structure where subject='".$row3["student_entry_level"]."' and programme_package ='".$row1['programme_duration']."' and status='Approved' and centre_code = '".$_SESSION["CentreCode"]."' and extend_year =  '".$_SESSION['Year']."'";
									$result22=mysqli_query($connection, $sql);
									while ($row22=mysqli_fetch_assoc($result22)) {
										 ?>
                                <option value="<?php echo $row22['id']?>">
                                    <?php echo $row22["fees_structure"]?></option>
                                <?php } ?>
                              
                            </select>
                            <span id="validationfee_name" style="color: red; display: none;">Please select Fees
                                Setting</span>						
								
                        </td>
                        <td class="uk-width-1-10">
                            <select name="foundation_mandarin[]" id="foundation_mandarin" class="uk-width-1-1">
                                <option value="0">No</option>
                                <option value="1">Yes</option>
                            </select>
                            <span id="validationFoundationMandarin" style="color: red; display: none;">Please
                                select Foundation Mandarin</span>
                        </td>
                        <td class="uk-width-1-10">
                            <select name="afternoon_programme[]" id="afternoon_programme" class="uk-width-1-1 afternoon_programme">
                                <option value="0">No</option>
                                <option value="1">Yes</option>
                            </select>
                            <span id="validationAfternoon" style="color: red; display: none;">Please select Afternoon Programme</span>
                        </td>

                        <td class="uk-width-5-10">
                            Int. Eng:&nbsp;<select type="checkbox" name="foundation_int_english[]"
                                id="foundation_int_english" class="uk-width-1-1" style="width: 61px;">
                                <option value="0">No</option>
                                <option value="1">Yes</option>
                                </select>&nbsp;&nbsp;
                            EF IQ Maths:&nbsp;<select type="checkbox" name="foundation_iq_math[]" id="foundation_iq_math"
                                class="uk-width-1-1" style="width: 61px;">
                                <option value="0">No</option>
                                <option value="1">Yes</option>
                                </select>
                            &nbsp;&nbsp;
                            EF Mandarin:&nbsp;<select type="checkbox" name="foundation_int_mandarin[]" id="foundation_int_mandarin" class="uk-width-1-1" style="width: 61px;">
                                <option value="0">No</option>
                                <option value="1">Yes</option>
                                </select>
                            &nbsp;&nbsp;
                            Int. Art:&nbsp;&nbsp;<select type="checkbox" name="foundation_int_art[]" id="foundation_int_art" class="uk-width-1-1" style="width: 61px;">
                                <option value="0">No</option>
                                <option value="1">Yes</option>
                                </select>
                            &nbsp;&nbsp;
                            Pendidikan Islam/Jawi:&nbsp;&nbsp;<select type="checkbox" name="pendidikan_islam[]" value="1" id="pendidikan_islam" class="uk-width-1-1" style="width: 61px;">
                                <option value="0">No</option>
                                <option value="1">Yes</option>
                                </select>
                            &nbsp;&nbsp;
                            <span class="robotic_plus" style="display:none;" >Robotics Plus:&nbsp;&nbsp;</span><select type="checkbox" name="robotic_plus[]" id="robotic_plus" class="uk-width-1-1 robotic_plus" style="width: 61px;display:none;">
                                <option value="0">No</option>
                                <option value="1">Yes</option>
                                </select>
                            &nbsp;&nbsp; <br>
                            <span id="validationCheckbox3" style="color: red; display: none;">Please tick any
                                checkbox</span>
                        </td>
                        <td class="uk-width-1-10">
                            <input class="uk-width-1-1 programme_date" type="text" data-uk-datepicker="{format: 'YYYY/MM/DD'}"
                                name="programme_date[]" id="programme_date"
                                value=""><br>
                            <span id="validationCommencement" style="color: red; display: none;">Please select
                                Commencement Date</span>
                        </td>
                        <td class="uk-width-1-10">
                            <input class="uk-width-1-1 programme_date_end" type="text" data-uk-datepicker="{format: 'YYYY/MM/DD'}"
                                name="programme_date_end[]" id="programme_date_end"
                                value="" style="width: 90px"><br>
                            <span id="validationCommencementEndDate" style="color: red; display: none;">Please select
                                Commencement Date</span>
                        </td>
                        </tr>
                        <?php  } ?>
                        </tbody>
                        </table>
                        <?php if($_GET['mode']=="EDIT") { ?>
                       <div style="text-align: center;" >
                        <a class="uk-button uk-button-primary form_btn uk-text-center add-new sansd">Add New</a> </div>
                        <?php } ?>
                        <!-- <div class="uk-text-center uk-text-small">
                    <br>
                   
                </div> -->
                    </tr>
                    <!-- <tr class="uk-text-small ">
                        <td class="uk-width-10-10">
                            

                        </td>
                    </tr> -->
                 
                </table>
                
            </div>
            <div class="uk-width-medium-10-10 uk-text-center">
                <br>
                <button type="submit" id="submit" name="submit"
                    class="uk-button uk-button-primary form_btn uk-text-center">Save</button>
            </div>

        </div>
    </form>
    <script>
	function getFee(programme_package, fee_id_obj){
		var subject = $("#student_entry_level").val();
        //var programme_package = $("#programme_duration").val();
        $.ajax({
            url: "admin/get_feev_name.php",
            type: "POST",
            data: "subject=" + subject + "&programme_package=" + programme_package,
            dataType: "text",
            beforeSend: function(http) {},
            success: function(response, status, http) {
                //console.log(response)
                if (response != "") { 
                    $(fee_id_obj).html(response);
					$(fee_id_obj).val($("#fee_name").val());
                } else {
                    $(fee_id_obj).html("<option value=''>Select</option>");
                }
            },
            error: function(http, status, error) {

            }
	})
	}
	 $(document).ready(function() {
          $(document).on("change", "#afternoon_programme", function(){
                if($(this).val()=="yes"){
                    $(".programme_duration").children("option[value^='Half Day']").hide();
                    $(".programme_duration").children("option[value^='Full Day']").show();
                     $(".programme_duration").children("option[value^='3/4 Day']").show();
                    
                }else if($(this).val()=="no"){
                    $(".programme_duration").children("option[value^='Full Day']").hide();
                     $(".programme_duration").children("option[value^='3/4 Day']").hide();
                     $(".programme_duration").children("option[value^='Half Day']").show();
                }else{
                    $(".programme_duration").children('option').show();
                }
            })
	//getFee();
    $(document).on("change", "#student_entry_level", function(){
        //console.log($(this).val());
        var programme_durationObj = $(".programme_duration");
       var fee_id_obj = programme_durationObj.parent().next().find("select");
        getFee(programme_durationObj.val(), fee_id_obj);
    })
	$(document).on("change", ".programme_duration", function(){
        //console.log($(this).val());
       var fee_id_obj = $($(this)).parent().next().find("select");
        getFee($(this).val(), fee_id_obj);
    })
    // $(".programme_duration").change(function() {
    //     getFee($(this).val());
    //     });
	//  })
$("#entryLevel").submit(function(e) {
	  var student_entry_level = $("#student_entry_level").val();
        //alert(student_entry_level);
        var foundation_mandarin = $("#foundation_mandarin").val();
        var programme_duration = $("#programme_duration").val();
        var afternoon_programme = $("#afternoon_programme").val();
        var programme_date = $("#programme_date").val();
        var programme_date_end = $("#programme_date_end").val();
        //console.log(programme_date_end);
        var fee_name = $("#fee_id").val();
        //console.log(fee_name);
		
		if (!student_entry_level === "" || foundation_mandarin === "" || programme_duration === "" || programme_date === "" || programme_date_end === "" || programme_date_end < programme_date || fee_name === "" || $('#fee_id option').length == 0) {

        e.preventDefault();
		    if (student_entry_level === "") {
                $('#validationStudentEntryLevel').show();
            } else {
                $('#validationStudentEntryLevel').hide();
            }
            if (foundation_mandarin === "") {
                $('#validationFoundationMandarin').show();
            } else {
                $('#validationFoundationMandarin').hide();
            }
            if (programme_duration === "") {
                $('#validationDuration').show();
            } else {
                $('#validationDuration').hide();
            }
            if (!afternoon_programme) {
                $('#validationAfternoon').show();
            } else {
                $('#validationAfternoon').hide();
            }
            if (programme_date === "") {
                $('#validationCommencement').show();
            } else {
                $('#validationCommencement').hide();
            }
            if (programme_date_end === "") {
                $('#validationCommencementEndDate').text('Please select Commencement End Date');
                $('#validationCommencementEndDate').show();
            } else if (programme_date_end < programme_date) {
                $('#validationCommencementEndDate').text('End Date must be greater then Commencement Date');
                $('#validationCommencementEndDate').show();
            } else {
                $('#validationCommencementEndDate').hide();
            }
            //console.log(fee_name);
            if (fee_name === "" || $('#fee_id option').length == 0) {
                $('#validationfee_name').show();
                //console.log("in");
            } else {
                //console.log("inn");
                $('#validationfee_name').hide();
            }
		}
    });
});
   </script>     
    <br>
    <!-- 3rd  -->

    <div class="uk-width-1-1 myheader">
        <h2 class="uk-text-center myheader-text-color myheader-text-style">Fee Setting Allocated</h2>
    </div>
    <div style="margin-left: 0;" class="uk-overflow-container uk-grid uk-grid-small">
        <div class="uk-width-medium-10-10 uk-text-center">
            <table class="uk-table">
                <tr class="uk-text-bold">
                    <td>No.</td>
                    <td>Student Name</td>
                    <td>Student Entry Level</td>
                    <td style="width:160px">Programme Package</td>
                    <td style="width:200px">Name of Fee Structure</td>
                    <td style="width:100px">Start</td>
                    <td style="width:100px">End</td>
                    <td>Action</td>
                </tr>
                <?php
$year = $_SESSION['Year'];
//$sql="SELECT (select name from student where sha1(id) = '$ssid') as name, (select fee_name from student where sha1(id) = '$ssid') as fee_name, (select programme_duration from student where sha1(id) = '$ssid') as programme_duration, g.*, c.course_name, c.subject from `group` g, course c where g.course_id=c.id and Exists (Select * from allocation a where sha1(student_id) = '$ssid' and deleted=0 and g.id=a.group_id) order by c.course_name";

//$sql="SELECT (select name from student where sha1(id) = '$ssid') as name, (select fee_name from student where sha1(id) = '$ssid') as fee_name, (select programme_duration from student where sha1(id) = '$ssid') as programme_duration, g.*, c.course_name, fs.from_date, fs.to_date, c.subject from `group` g, course c, fee_structure fs where g.course_id=c.id and g.level=fs.subject and  Exists (Select * from allocation a where sha1(student_id) = '$ssid' and deleted=0 and g.id=a.group_id) order by c.subject";

//$sql="select * from student  where sha1(id)='$ssid' order by student_code ";
$sql="SELECT *, ps.id as psid from programme_selection ps left join fee_structure fs on fs.id = ps.fee_id where sha1(ps.student_id) = '$ssid'";
//echo $sql;
$result=mysqli_query($connection, $sql);
$num_row=mysqli_num_rows($result);
if ($num_row>0) {
   $count=0;
   while ($row=mysqli_fetch_assoc($result)) {
      $count++;
    $time=$row["start_time"]." - ".$row["end_time"];
?>
                <tr id='row-id-<?php echo $row["psid"] ?>'>
                    <td><?php echo $count?></td>
                    <td><?php echo $row["name"]?></td>
                    <td><?php echo $row["student_entry_level"]?></td>
                    <td colspan="4">
                    <?php 
                    $sql1="SELECT * from student_fee_list fl inner join fee_structure fs on fl.fee_id = fs.id  where sha1(fl.student_id) = '$ssid' and fl.programme_selection_id  = '".$row["psid"]."'";
                    ///echo $sql1;
                    $result1=mysqli_query($connection, $sql1);
                    //$num_row1=mysqli_num_rows($result1);
                    while ($row1=mysqli_fetch_assoc($result1)) {
                        ?>
                        <table>
                            <tbody>
                                <tr>
                                    <td style="width:160px"><?php echo $row1["programme_duration"]?></td>
                                    <td style="width:200px"><?php echo $row1["fees_structure"]?></td>
                                    <td style="width:100px"><?php echo $row1["programme_date"]?></td>
                                    <td style="width:100px"><?php echo $row1["programme_date_end"]?></td>
                                </tr>
                            </tbody>
                        </table>
                        <?php
                    }
                    ?>
                    </td> 
                    <!-- <td><?php echo $row["programme_duration"]?></td>
                    <td> <?php  echo $row["fees_structure"];?></td>
                    <td><?php echo date('d/m/Y', strtotime($row["from_date"]))?></td>
                    <td><?php echo date('d/m/Y', strtotime($row["to_date"]))?></td> -->
                  <td><a href='index.php?p=student_info&ssid=<?php echo $ssid?>&mode=EDIT&psid=<?php echo $row["psid"]?>'><i data-uk-tooltip title='Edit Fee Setting' class='fas fa-user-edit'></i></a>
                  <?php
                    $psid = $row["psid"];
                  $sql3="SELECT * from collection where sha1(student_id) = '$ssid' and allocation_id = '$psid'";
//echo $sql;
$result3=mysqli_query($connection, $sql3);
$num_row3=mysqli_num_rows($result3);
if ($num_row3==0) { ?>
                    <a onclick='dlgDeleteGroup(<?php echo $row["psid"]?>)'><i data-uk-tooltip title='Delete Class' class='fas fa-trash text-danger'></i></a>
                    <?php } ?>
					</td>
                </tr>
                <?php
   }
} else {
   echo "<tr class='uk-text-bold uk-text-small'><td colspan='4'>No Record Found</td></tr>";
}
?>
            </table>
        </div>
    </div>










</div>

<div id="dlgProductBought"></div>
<style>
.ui-widget-overlay {
    opacity: 0.5;
    filter: Alpha(Opacity=50);
    background-color: black;
}

.q-dialog .ui-dialog-titlebar button.ui-dialog-titlebar-close {
    background: red;
    border: none;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-size: 1.2rem;
}

.q-dialog .ui-dialog-titlebar button.ui-dialog-titlebar-close::before {
    font-family: "Font Awesome 5 Free";
    font-weight: 900;
    content: "\f00d";
}

.uk-button:active,
.uk-button.uk-active {
    background-color: #69a6f7 !important;
}

.blue_button_s1 {
    line-height: 28px;
    min-height: 30px;
    font-size: 1rem;
    border-radius: 4px;
    cursor: pointer;
}
.sansd{
   font-size: 14px;
    padding: 2px 5px!important;
    margin-left: 5px;
   
}
.uk-dropdown.uk-datepicker {
    left: 81%!important;
}
</style>

<script>
(function () {
    var previous;

    $("#programme_duration").on('focus', function () {
        // Store the current value on focus and on change
        previous = this.value;
    }).change(function() {
        // Do something with the previous value after the change
      	 
		if(this.value == "Full Day"){
			$('#afternoon_programme').val("1");
		}  
		if(this.value == "Half Day"){
			$('#afternoon_programme').val("0");
		}  
		if(previous == 'Half Day' && this.value == "Full Day"){
		 UIkit.modal.alert("<h2>Please change the end date.</h2>");
		 $('#programme_date_end').val('');
		}
    });
})(); 
function dlgProductBought() {
    $.ajax({
        url: "admin/dlgProductBought.php",
        type: "POST",
        data: "ssid=<?php echo $ssid?>",
        dataType: "text",
        beforeSend: function(http) {},
        success: function(response, status, http) {
            if (response != "") {
                $("#dlgProductBought").html(response);
                $("#dlgProductBought").dialog({
                    dialogClass: "q-dialog",
                    title: "Product Brought",
                    modal: true,
                    height: '400',
                    width: "60%",
                });
            }
        },
        error: function(http, status, error) {
            UIkit.notify("Error:" + error);
        }
    });
}

$(document).on("click", ".add-new", function() {

var tIdRow_c = $('.tIdRow_c').last();
var clone = tIdRow_c.clone();
clone.find('.programme_date').val("");
clone.find('.programme_date_end').val("");
clone.find('.fee_id').val("");
clone.find('#programme_duration').val("");
clone.find('.old_fee_id').val("");
clone.find('.uniform_default').val("");
clone.find('.uniform_adjust').val("");
clone.find('.gymwear_default').val("");
clone.find('.gymwear_adjust').val("");

clone.insertAfter(tIdRow_c);
 //rowIndex()
})
$(document).on("click", ".delete", function() {
if ($("#tblVoucher tbody").find("tr").length > 1) {
    $(this).parent().parent().remove();
   //  rowIndex()
}
})
function dlgDeleteGroup(programme_selection_id){
  UIkit.modal.confirm("<h2>Confirm delete?</h2>", function () {
    $.ajax({
       url : "admin/student_fee_delete.php",
       type : "POST",
       data : "programme_selection_id="+programme_selection_id+ "&mode=DEL",
       dataType : "text",
       beforeSend : function(http) {
       },
       success : function(response, status, http) {
        UIkit.notify(response);
          // console.log(response);
          $('#row-id-' + programme_selection_id).remove();
       },
       error : function(http, status, error) {
          UIkit.notify("Error:"+error);
       }
    });
  });
}

/* $(document).ready(function(){
    $("#programme_date").datepicker({
        numberOfMonths: 1,
        onSelect: function(selected) {
          $("#programme_date_end").datepicker("option","minDate", selected)
        }
    });
    $("#programme_date_end").datepicker({ 
        numberOfMonths: 1,
        onSelect: function(selected) {
           $("#programme_date").datepicker("option","maxDate", selected)
        }
    });  
}); */

/* $(document).ready(function(){
    $("#programme_date").change(function(){
        var date = $(this).val();
        UIkit.datepicker('#programme_date_end', {format:'YYYY/MM/DD', minDate: date });
    });

    $("#programme_date_end").change(function(){
        var date = $(this).val();
        UIkit.datepicker('#programme_date', {format:'YYYY/MM/DD', maxDate: date });
    });
}); */

function change_programme_package(thiss)
{
    if($(thiss).val() == "Half Day")
    {
        $(thiss).parents( "tr" ).find(".robotic_plus").show();
        $(thiss).parents( "tr" ).find(".afternoon_programme").val(0);
        $(thiss).parents( "tr" ).find(".afternoon_programme").hide();
    }
    else
    {
        $(thiss).parents( "tr" ).find(".robotic_plus").hide();
        $(thiss).parents( "tr" ).find(".afternoon_programme").val(0);
        $(thiss).parents( "tr" ).find(".afternoon_programme").show();
    }
}

</script>
<?php
} else {
   echo "<div class='uk-margin-top uk-margin-right'><div class='uk-alert uk-alert-danger uk-text-large uk-text-bold uk-text-center'>Unauthorised access denied</div></div>";
}
?>