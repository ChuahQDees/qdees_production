<?php
session_start();
/*
Fee Structure Multi-Add
Owner: Chuah

v1.0: Launch
*/
if (($_SESSION["isLogin"] == 1) & (($_SESSION["UserType"] == "A") || ($_SESSION["UserType"] == "O")) &
(hasRightGroupXOR($_SESSION["UserName"], "PointOfSalesEdit|PointOfSalesView"))
) {
include_once("../mysql.php");
include_once("functions.php");

if ($_GET['status'] == "completed"){
    echo "<script>UIkit.notify('Record saved!')</script>";
}

if ($_GET['status'] == "enerror"){
    echo "<script>UIkit.notify('Must choose at least one Enhanced Foundation')</script>";
}

//Get array of students who already has fee structure
$studentFeeStructureArray = array();

$entry_get = mysqli_query($connection, "SELECT student_code, student_entry_level, fees_structure
        FROM (
            SELECT DISTINCT ps.student_entry_level, s.id, s.student_code, f.fees_structure
            FROM student s
            INNER JOIN programme_selection ps ON ps.student_id = s.id
            INNER JOIN student_fee_list fl ON fl.programme_selection_id = ps.id
            INNER JOIN fee_structure f ON f.id = fl.fee_id
            WHERE (fl.programme_date >= '$year_start_date' AND fl.programme_date <= '$year_end_date') 
            AND ps.student_entry_level != '' AND s.student_status = 'A' 
            AND s.centre_code='".$_SESSION["CentreCode"]."' 
            AND ((fl.programme_date >= '$year_start_date' AND fl.programme_date <= '$year_end_date') OR (fl.programme_date_end >= '$year_start_date' 
            AND fl.programme_date_end >= '$year_end_date')) AND s.deleted = '0' 
        ) ab ");

while ($row = mysqli_fetch_assoc($entry_get)) {
    //Write the students who already have fee structure into the array
    array_push($studentFeeStructureArray,$row['student_code']);
}

global $connection;
?>
<span>
    <span class="page_title"><img src="/images/title_Icons/Payment.png">Fees Structure Allocation</span>
</span>
<br />
<div class="uk-width-1-1 myheader">
    <h2 class="uk-text-center myheader-text-color myheader-text-style">PROGRAMME SELECTION</h2>
</div>
<!--<form name="" id="entryLevel" method="post" class="uk-form uk-form-small" action="index.php?p=student_list_func&ssid=<?php echo $ssid ?>">-->
<form name="" id="entryLevel" method="post" class="uk-form uk-form-small" action="index.php?p=fee_str_allocate_func">
    <div style="margin-left: 0;" class="uk-overflow-container uk-grid uk-grid-small">
<!--
        <input type="hidden" name="form_mode" id="form_mode" value="ADD">
        <input type="hidden" name="student_code" id="student_code" value="<?php echo $student_code; ?>">
        <input type="hidden" name="name" id="name" value="<?php echo $name; ?>">
        <input type="hidden" name="programme_selection_id" id="programme_selection_id" value="<?php echo $row3["id"]; ?>">
-->

	<?php
	$enh_chkValue = "N";
	$sqlenhchk="SELECT allowed FROM enh_foundation_whitelist 
	WHERE centre_code = '".$_SESSION['CentreCode']."'"; 
	$resultenhchk=mysqli_query($connection, $sqlenhchk);
	//$num_row=mysqli_num_rows($result);

	//echo $sql;
	
	if ($rowenhchk=mysqli_fetch_assoc($resultenhchk)) {
		$enh_chkValue = $rowenhchk['allowed'];
	}
		?>
		
	<input name="enh_whitelist" id="enh_whitelist" hidden value="<?php echo $enh_chkValue ?>">
    <script>
    function enhFoundationDefault(){ //By default, need to have at least one enhanced foundation
        d = document.getElementById("student_entry_level").value;
		e = document.getElementById("enh_whitelist").value;
		
		if (e == "N"){
			if (d != "EDP"){
				document.getElementById("foundation_int_english").value = "1";
			}else{
				document.getElementById("foundation_int_english").value = "0";
			}
		}
    }
    </script>
        <input type="text" id="studentIDArray" name="studentIDArray" hidden>
        <div class="uk-width-medium-10-10">
            <table class="uk-table uk-table-small">
                <tr class="uk-text-small">
                    <td class="uk-width-3-10 uk-text-bold">Student Entry Level <span class="text-danger">*</span>:
                    </td>
                </tr>
                <tr class="uk-text-small">
                    <td class="uk-width-3-10">
                        <select name="student_entry_level" id="student_entry_level" class="uk-width-1-1" style="width: 100px;" onChange="enhFoundationDefault()">
                            <option value="">Select</option>
                            <option value="EDP">EDP</option>
                            <option value="QF1">QF1</option>
                            <option value="QF2">QF2</option>
                            <option value="QF3">QF3</option>
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
                    <td class="uk-width-1-10 uk-text-bold">Fees Setting<span class="text-danger">*</span>: <sup title="If fee structure is unable to be selected, please check 'Fees Structure Setting' to see if fee structure has been approved.">?</sup></td>
                    <td class="uk-width-1-10 uk-text-bold">Foundation Mandarin:</td>
                    <td class="uk-width-1-10 uk-text-bold">Afternoon Programme:</td>
                    <td class="uk-width-3-10 uk-text-bold">Enhanced Foundation:</td>
                    <td class="uk-width-1-10 uk-text-bold">Commencement Date <span class="text-danger">*</span>:</td>
                    <td class="uk-width-1-10 uk-text-bold">End Date <span class="text-danger">*</span>:</td>
                </tr>
                    <tr class="tIdRow_c" style="margin-bottom: 10px;">
                        <td class="uk-width-1-10">
                            <select name="programme_duration[]" class="uk-width-1-1 subject_3 programme_duration" id="programme_duration" onchange="change_programme_package(this)" style="width: 90px">
                                <option value="">Select</option>
                                <option value="Full Day">Full Day</option>
                                <option value="Half Day">Half Day</option>
                                <option value="3/4 Day">3/4 Day</option>
                            </select>
                            <span id="validationDuration" style="color: red; display: none;">Please select Programme package</span>
                        </td>
                        <td class="uk-width-1-10">
                            <select name="fee_id[]" id="fee_id" onChange="getDateFromDB()" class="uk-width-1-1 fee_id" style="width: 150px" />
                            <span id="validationfee_name" style="color: red; display: none;">Please select Fees Setting</span>

                        </td>
                        <td class="uk-width-1-10">
                            <select name="foundation_mandarin[]" id="foundation_mandarin" class="uk-width-1-1">
                                <option value="0">No</option>
                                <option value="1">Yes</option>
                            </select>
                            <span id="validationFoundationMandarin" style="color: red; display: none;">Please select Foundation Mandarin</span>
                        </td>
                        <td class="uk-width-1-10">
                            <select name="afternoon_programme[]" id="afternoon_programme" class="uk-width-1-1 afternoon_programme">
                                <option value="0">No</option>
                                <option value="1">Yes</option>
                            </select>
                            <span id="validationAfternoon" style="color: red; display: none;">Please select Afternoon Programme</span>
                        </td>

                        <td class="uk-width-5-10">
                            Int. Eng:&nbsp;<select type="checkbox" name="foundation_int_english[]" id="foundation_int_english" class="uk-width-1-1" style="width: 61px;">
                                <option value="0">No</option>
                                <option value="1">Yes</option>
                            </select>&nbsp;&nbsp;
                            EF IQ Maths:&nbsp;<select type="checkbox" name="foundation_iq_math[]" id="foundation_iq_math" class="uk-width-1-1" style="width: 61px;">
                                <option value="0">No</option>
                                <option value="1">Yes</option>
                            </select>
                            &nbsp;&nbsp;
                            EF Mandarin:&nbsp;<select type="checkbox" name="foundation_int_mandarin[]" id="foundation_int_mandarin" class="uk-width-1-1" style="width: 61px;">
                                <option value="0">No</option>
                                <option value="1">Yes</option>
                            </select>
                            <br>
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
                            <span class="robotic_plus" style="display:none;">Robotics Plus:&nbsp;&nbsp;</span><select type="checkbox" name="robotic_plus[]" id="robotic_plus" class="uk-width-1-1 robotic_plus" style="width: 61px;display:none;">
                                <option value="0">No</option>
                                <option value="1">Yes</option>
                            </select>
                            &nbsp;&nbsp; <br>
                            <span id="validationCheckbox3" style="color: red; display: none;">Please tick any checkbox</span>
                        </td>
                        <td class="uk-width-1-10">
                            <input class="uk-width-1-1 programme_date" onChange="compareDateBefore(this.value, 'before')" type="text" data-uk-datepicker="{format: 'YYYY/MM/DD'}" name="programme_date[]" id="programme_date" value=""><br>
                            <span id="validationCommencement" style="color: red; display: none;">Please select Commencement Date</span>
                            <input hidden id="programme_date_original">
                        </td>
                        <td class="uk-width-1-10">
                            <input class="uk-width-1-1 programme_date_end" onChange="compareDateBefore(this.value, 'after')" type="text" data-uk-datepicker="{format: 'YYYY/MM/DD'}" name="programme_date_end[]" id="programme_date_end" value="" style="width: 90px"><br>
                            <span id="validationCommencementEndDate" style="color: red; display: none;">Please select Commencement Date</span>
                            <input hidden id="programme_date_end_original">
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <script>
            function compareDateBefore(value, type){
                var fee_id = document.getElementById("fee_id").value;

                var selectedDate = new Date(value);

                if (type == "before"){
                    var original_StartDate = new Date(document.getElementById("programme_date_original").value);

                    if (original_StartDate > selectedDate){
                        alert("Date chosen cannot be earlier than Fee Structure's Commencement Date!");

                        var today = new Date();
                        var programme_date = "";

                        var dd = String(today.getDate()).padStart(2, '0');
                        var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
                        var yyyy = today.getFullYear();

                        programme_date = yyyy + '/' + mm + '/' + dd;

                        document.getElementById("programme_date").value = programme_date;
                    }
                }else{
                    var original_EndDate = new Date(document.getElementById("programme_date_end_original").value);
                    if (original_EndDate < selectedDate){
                        alert("Date chosen cannot be later than Fee Structure's End Date!");

                        document.getElementById("programme_date_end").value = document.getElementById("programme_date_end_original").value;
                    }
                }
            }
        </script>
        <div class="uk-width-medium-10-10 uk-text-center">
            <br>
            <button type="submit" id="submit" name="submit" class="uk-button uk-button-primary form_btn uk-text-center" disabled>Save</button>
        </div>

    </div>
    <!-- -->
    <div id="thetop" class="uk-margin-right uk-margin-top">
   <div class="uk-width-1-1 myheader">
      <h2 class="uk-text-center myheader-text-color myheader-text-style">Student Selection</h2>
   </div>

   <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">

   <style>
    .d_none{
        display:none!important;   
    }

    .page_title {
        position: absolute;
        right: 34px;
    }

    .highlight{
        background: #FFFFB3;
    }

    #myInput {
        width: 100%; /* Full-width */
        font-size: 16px; /* Increase font-size */
        padding: 12px 20px 12px 40px; /* Add some padding */
        border: 1px solid #ddd; /* Add a grey border */
        margin-bottom: 12px; /* Add some space below the input */
    }

    #mydatatable {
        border-collapse: collapse; /* Collapse borders */
        width: 100%; /* Full-width */
        border: 1px solid #ddd; /* Add a grey border */
        font-size: 12px; /* Increase font-size */
    }

    #mydatatable th, #mydatatable td {
        text-align: left; /* Left-align text */
        padding: 12px; /* Add padding */
    }

    #mydatatable tr {
    /* Add a bottom border to all table rows */
        border-bottom: 1px solid #ddd;
    }

  /*  #mydatatable tr.header, #mydatatable tr:hover {*/
    /* Add a grey background color to the table header and on hover */
    #mydatatable tr.header {
        background-color: #E3E3E3;
    }

    /* Strikout table */
    table {
        border-collapse: collapse;
    }

    td {
        position: relative;
        padding: 5px 10px;
    }

    tr.strikeout td:before {
        content: " ";
        position: absolute;
        top: 50%;
        left: 0;
        border-bottom: 1px solid #9E9E9E;
        width: 100%;
    }
    
   </style>

<div id="sctListing">
   <div class="uk-overflow-container">
      <input type="text" id="myInput" onkeyup="tableSearch()" placeholder="Student Name Search">
      <label>Please click on the name you wish to select:</label>
      <table id="mydatatable" class="uk-table" style="font-size:14px !important;">
         <thead>
            <tr class="uk-text-bold header" >
               <th style="width:5%"></th>
               <th style="width:25%">Student Name</th>
               <th style="width:15%">Student Code</th>
               <th style="width:5%">Age</th>
               <th style="width:10%">Centre Start Date</th>
               <th style="width:10%">NRIC</th>
               <th style="width:20%">Fee Structure</th>
            </tr>
         </thead>
        <?php 
        $sql="SELECT id, name, student_code, age, start_date_at_centre, nric_no, dob FROM student 
        WHERE student_status = 'A' 
        AND centre_code = '".$_SESSION['CentreCode']."' 
        AND deleted = '0'
        AND extend_year >= '".$_SESSION['Year']."'
        ORDER BY start_date_at_centre DESC"; 
        $result=mysqli_query($connection, $sql);
        //$num_row=mysqli_num_rows($result);

        //echo $sql;
        while ($row=mysqli_fetch_assoc($result)) {
            $not_disabled = "0";
            if (!in_array($row['student_code'], $studentFeeStructureArray)) {
                $not_disabled = "1";
            }
        ?>
        <tbody>
            <tr <?php if ($not_disabled == "1") { ?> class="checkBoxChecked" <?php }else{ ?> class="strikeout" style="background-color: #F0F0F0; color: #707070;" <?php } ?> id="stu_id<?php echo $row['id']?>">
                <td>
                    <input type="checkbox" 
                    specId="<?php echo $row['id']?>" 
                    class="Aps_checkbox"
                    id="stu_id<?php echo $row['id']?>" 
                    name="stu_id<?php echo $row['id']?>"
                    value="<?php echo $row['id']?>" 
                    aria-labelledBy="stu_id<?php echo $row['id']?>" 
                    disabled
                    hidden
                    />
                    
                    <div id="chgtext<?php echo $row['id']?>">
                        <?php if ($not_disabled == "1") { ?>
                            <i class="fa fa-square-o"></i>
                        <?php }else{ ?>
                            <i class="fa fa-check-square-o"></i>
                        <?php } ?>
                    </div> <!-- ''Fake'' Checkbox -->
                </td>
                <td><?php echo $row['name']?></td>
                <td><?php echo $row['student_code']?></td>
                <td><?php 
                    // Get current age
                    $date = new DateTime($row['dob']);
                    //$now = new DateTime();
                    $now = new DateTime('31st December This Year');
                    $interval = $now->diff($date);
                    echo $interval->y;
                ?>
                </td>
                <td><?php echo $row['start_date_at_centre']?></td>
                <td><?php echo $row['nric_no']?></td>
                <?php
                //Function to get Fee Structure from list

                $fullFeeName = "";
                $moreThan15 = "";
                $latest_fee_structure = ""; //Get the Latest Fee Structure

                if ($not_disabled == "0"){
                    $student_id = $row['id'];
                    
                    $sqlFeeCheck = "SELECT f.fees_structure
                    FROM `fee_structure` f
                    INNER JOIN `student_fee_list` fl
                    ON f.id = fl.fee_id
                    WHERE fl.student_id = '$student_id'
                    AND ((fl.programme_date >= '$year_start_date' AND fl.programme_date <= '$year_end_date') 
                    OR (fl.programme_date_end >= '$year_start_date' AND fl.programme_date_end >= '$year_end_date'))";

                    $result2=mysqli_query($connection, $sqlFeeCheck);

                    while ($row2=mysqli_fetch_assoc($result2)) {
                        $latest_fee_structure = $row2['fees_structure'];
                    }

                    if (strlen($latest_fee_structure) > 25)
                    {
                        $maxLength = 24;

                        $fullFeeName = $latest_fee_structure; //Saving the full fee structure name before cutting

                        $latest_fee_structure = substr($latest_fee_structure, 0, $maxLength);
                        $latest_fee_structure .= '...';
                        $moreThan15 = "1";
                    }
                } 
                ?>
                <td <?php if ($moreThan15 = "1"){ ?> title = "<?php echo $fullFeeName ?>" <?php } ?>><?php echo $latest_fee_structure; ?></td>
            </tr>
        </tbody>
        <?php 
        }?>
    </table>
   </div>
</div>
</div>
</form>

<script>
    var items = [];

    function addStudentIDtoArray(selectObject, addRemove){
        //alert("Hello! "+selectObject);
        //boxvalue = document.getElementById('selectObject').value;
        if (addRemove == "Add"){
            items.push(selectObject);  
        }else{
            const index = items.indexOf(selectObject);
            if (index > -1) { // only splice array when item is found
                items.splice(index, 1); // 2nd parameter means remove one item only
            }
        }
        //console.log(items);
        document.getElementById("studentIDArray").value = items;

        const button = document.getElementById('submit')

        if (document.getElementById("studentIDArray").value != ""){
            button.removeAttribute("disabled");
        }else{
            button.setAttribute("disabled", "disabled");
        }
    }

    function getFee(programme_package, fee_id_obj) {
        var subject = $("#student_entry_level").val();
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
                    document.getElementById("fee_id").disabled = false;
                } else {
                    $(fee_id_obj).html("<option value=''>Select</option>");
                    document.getElementById("fee_id").disabled = true;
                }
            },
            error: function(http, status, error) {

            }
        })
    }

    function getDateFromDB(){
        //CHS: Automatically puts Commencement Date and End Date into the fields
        var fee_id = document.getElementById("fee_id").value;
        
        $.ajax({
            url: "admin/get_feev_commenceEndDate.php",
            type: "POST",
            data: "fee_id=" + fee_id,
            dataType: "text",
            beforeSend: function(http) {},
            success: function(response) {
                //alert(response);
                var usefulResponse = JSON.parse(response); 

                //Convert Useful Response to date to compare
                var programme_date_compare = usefulResponse.programme_date.replace(/-/g, '');

                //var dateString  = "20120515";
                var year        = programme_date_compare.substring(0,4);
                var month       = programme_date_compare.substring(4,6);
                var day         = programme_date_compare.substring(6,8);

                var feeStructureDate        = new Date(year, month-1, day); //Convert date to fee structure format

                //Get Today Date
                var today = new Date();
                var programme_date = "";
                var orgDate = usefulResponse.programme_date.replace(/-/g, '/');

                if (feeStructureDate < today){
                    //alert("Today is later than Fee Structure Date. Use today's date instead.");
                    var dd = String(today.getDate()).padStart(2, '0');
                    var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
                    var yyyy = today.getFullYear();

                    programme_date = yyyy + '/' + mm + '/' + dd;
                }else{
                    //alert("Using Fee Structure Date");
                    programme_date = usefulResponse.programme_date.replace(/-/g, '/');
                }

                var programme_date_end = usefulResponse.programme_date_end.replace(/-/g, '/');

                document.getElementById("programme_date").value = programme_date;
                document.getElementById("programme_date_end").value = programme_date_end;
                
                document.getElementById("programme_date_original").value = orgDate;
                document.getElementById("programme_date_end_original").value = programme_date_end;
            },
            error: function(http, status, error) {

            }
        })
    }

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

    $(document).ready(function() {
        document.getElementById("fee_id").disabled = true;

        $(document).on("change", "#afternoon_programme", function() {
            if ($(this).val() == "yes") {
                $(".programme_duration").children("option[value^='Half Day']").hide();
                $(".programme_duration").children("option[value^='Full Day']").show();
                $(".programme_duration").children("option[value^='3/4 Day']").show();

            } else if ($(this).val() == "no") {
                $(".programme_duration").children("option[value^='Full Day']").hide();
                $(".programme_duration").children("option[value^='3/4 Day']").hide();
                $(".programme_duration").children("option[value^='Half Day']").show();
            } else {
                $(".programme_duration").children('option').show();
            }
        })
        $(document).on("change", "#student_entry_level", function() {
            //console.log($(this).val());
            var programme_durationObj = $(".programme_duration");
            var fee_id_obj = programme_durationObj.parent().next().find("select");
            getFee(programme_durationObj.val(), fee_id_obj);
        })
        $(document).on("change", ".programme_duration", function() {
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

            if (!student_entry_level === "" || foundation_mandarin === "" || 
            programme_duration === "" || programme_date === "" || 
            programme_date_end === "" || programme_date_end < programme_date || 
            fee_name === "" || $('#fee_id option').length == 0) {

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

    $('.checkBoxChecked').on('click', function(){
        var checkbox = $(this).find('.Aps_checkbox');
        checkbox.prop("checked", !checkbox.prop("checked"));

        //Highlight if checkbox is checked
        var specTr = $(checkbox).attr("specId");

        if($(checkbox).prop("checked")){
            $("#stu_id"+specTr).addClass("highlight");
            addStudentIDtoArray(specTr, "Add");
            document.getElementById('chgtext'+specTr).innerHTML='<i class="fa fa-check-square-o"></i>';
        }else{
            $("#stu_id"+specTr).removeClass("highlight");
            addStudentIDtoArray(specTr, "Remove");
            document.getElementById('chgtext'+specTr).innerHTML='<i class="fa fa-square-o"></i>';
        }
    });
    
    //Table Search Function
    function tableSearch() {
        // Declare variables
        var input, filter, table, tr, td, i, txtValue;
        input = document.getElementById("myInput");
        filter = input.value.toUpperCase();
        table = document.getElementById("mydatatable");
        tr = table.getElementsByTagName("tr");

        // Loop through all table rows, and hide those who don't match the search query
        for (i = 0; i < tr.length; i++) {
            td = tr[i].getElementsByTagName("td")[1];
            if (td) {
            txtValue = td.textContent || td.innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                tr[i].style.display = "";
            } else {
                tr[i].style.display = "none";
            }
            }
        }
    }
</script>
<br>
<?php
} else {
echo "<div class='uk-margin-top uk-margin-right'><div class='uk-alert uk-alert-danger uk-text-large uk-text-bold uk-text-center'>Unauthorised access denied</div></div>";
}
?>