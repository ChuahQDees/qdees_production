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
};

//Get array of students who are already in 2023-2024
$studentFeeStructureArray = array();

$entry_get = mysqli_query($connection, "SELECT student_code
        FROM (
            SELECT DISTINCT s.id, s.student_code
            FROM student s
            WHERE s.student_status = 'A' 
            AND s.centre_code='".$_SESSION["CentreCode"]."' 
            AND s.extend_year = '2023-2024'
            AND s.deleted = '0' 
        ) ab ");

while ($row = mysqli_fetch_assoc($entry_get)) {
    //Write the students who already have fee structure into the array
    array_push($studentFeeStructureArray,$row['student_code']);
}

global $connection;

$enableAccess = true;

if ($_SESSION['Year'] == "2023-2024"){ //Hardcoding latest year :D
    $enableAccess = false; //No need to use this if the students are already in the latest year. At most, it'll be a glorified list of all students in one page.
}
?>
<a onclick="history.back();">                
    <span class="d_n btn-qdees"><i class="fa fa-arrow-left" style="margin-right: 20px;"></i>Back</span>
</a>
<span>
    <span class="page_title"><img src="/images/title_Icons/StudReg.png">Student Multi-Transfer</span>
</span>

<!--
<span>
    <span class="page_title"><img src="/images/title_Icons/Payment.png">Student Multi-Transfer</span>
</span>
<br />
-->
<!--<form name="" id="entryLevel" method="post" class="uk-form uk-form-small" action="index.php?p=student_list_func&ssid=<?php echo $ssid ?>">-->
<form name="" id="entryLevel" method="post" class="uk-form uk-form-small" action="index.php?p=fee_str_allocate_func">
    <div id="thetop" class="uk-margin-right uk-margin-top">
        <div class="uk-width-1-1 myheader">
            <h2 class="uk-text-center myheader-text-color myheader-text-style">Student Multi-Transfer</h2>
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
            <input type="text" id="studentIDArray" name="studentIDArray" hidden>
            <?php 
            if ($enableAccess == false){?>
                <h3>You can transfer multiple students from the previous year in this screen. <br /> Navigate to the <a href="javascript:doYear('2022-2023')">previous term</a> to get started.</h3>
            <?php }else{ ?>
                <h3><b>Currently in previous term.</b> <a href="javascript:doYear('2023-2024')">Click here to return to the latest year.</a></h3>
            <?php } ?>
            <input type="text" id="myInput" onkeyup="tableSearch()" placeholder="Student Name Search">
            <?php if ($enableAccess == true){?>
            <p>Click on the students' name to select for transfer.</p>
            <?php } ?>
            <table id="mydatatable" class="uk-table" style="font-size:14px !important;">
                <thead>
                    <tr class="uk-text-bold header" >
                    <th style="width:5%"></th>
                    <th style="width:25%">Student Name</th>
                    <th style="width:15%">Student Code</th>
                    <th style="width:5%">Age</th>
                    <th style="width:10%">Centre Start Date</th>
                    <th style="width:20%">NRIC</th>
                    <?php
                    if ($_SESSION['Year'] != "2023-2024"){ 
                        ?>
                    <th style="width:10%">Latest term</th> <!-- Maybe keep in mind about the start date where students join this term if it's latest term too? -->
                    <?php } ?>
                    </tr>
                </thead>
                <?php 
                $sql="SELECT id, name, student_code, start_date_at_centre, nric_no, dob, extend_year FROM student 
                WHERE student_status = 'A' 
                AND centre_code = '".$_SESSION['CentreCode']."' 
                AND deleted = '0'
                AND extend_year >= '".$_SESSION['Year']."'
                ORDER BY dob DESC"; 
                
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
                    <tr <?php if ($not_disabled == "1" || $enableAccess == false) { ?> class="checkBoxChecked" <?php }else{ ?> class="strikeout" style="background-color: #F0F0F0; color: #707070;" <?php } ?> id="stu_id<?php echo $row['id']?>">
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

                                <?php
                                if ($enableAccess == true){
                                    if ($not_disabled == "1") { ?>
                                        <i class="fa fa-square-o"></i>
                                    <?php }else{ ?>
                                        <i class="fa fa-check-square-o"></i>
                                    <?php } 
                                } ?>
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
                            <?php if ($_SESSION['Year'] != "2023-2024"){ ?>    
                            <td>                    
                                <?php
                                if ($row['extend_year'] == "2023-2024"){
                                    echo "Transferred";
                                }else{ 
                                    echo $row['extend_year'];
                                    ?>                
                            </td>
                            <?php } ?>
                        <?php }?>       
                    </tr>
                </tbody>
                <?php 
                }?>
            </table>
            <center>
                <?php if ($enableAccess == false){ ?>
                <button disabled class="uk-button form_btn uk-text-center" disabled>Currently in latest year</button>
                <?php }else{ ?>
                    <button type="submit" id="submit" name="submit" class="uk-button uk-button-primary form_btn uk-text-center" disabled>Transfer to latest year</button>
                <?php } ?>
            </center>
        </div>
    </div>
    </div>
</form>

<?php if ($enableAccess == true){ ?>
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
</script>
<?php } ?>
<script>    
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