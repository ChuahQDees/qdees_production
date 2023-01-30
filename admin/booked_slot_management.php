<a href="/index.php?p=rpt_schedule">                 
         <span class="btn-qdees"><i class="fa fa-arrow-left" style="margin-right: 20px;"></i>Back</span>
</a>
<span style="position: absolute;right: 35px;">
    <span class="page_title"><img src="/images/title_Icons/Defective_Status.png">Booked Slot Management</span>
</span>

<?php
include_once("../mysql.php");
include_once("lib/pagination/pagination.php");

include_once("booked_slot_management_func.php");

if ($_SESSION["isLogin"]==1) {
   //if (($_SESSION["UserType"]=="S") & (hasRightGroupXOR($_SESSION["UserName"], "SlotCollectionManagementEdit"))) {
    if (($_SESSION["UserType"]=="S") & (hasRightGroupXOR($_SESSION["UserName"], "ReportingView"))) {
?>
<?php if($edit_row['is_booked']){ ?>
<div class="uk-margin-top uk-margin-right uk-form" style="margin-top:40px!important;">
    <div class="uk-width-1-1 myheader">
        <h2 class="uk-text-center myheader-text-color">Booked Slot Management</h2>
    </div>
    
    <form name="frmSlotCollectionManagement" id="frmSlotCollectionManagement" method="post" action="index.php?p=booked_slot_management&mode=SAVE" enctype="multipart/form-data">
        <div style="overflow: unset;" class="uk-overflow-container">
            <div class="uk-grid">
                <div class="uk-width-medium-5-10">
                    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.4.2/chosen.min.css">
                    <table class="uk-table uk-table-small">
                        <input type="hidden" name="hidden_id" id="hidden_id" value="<?php echo sha1($edit_row['id'])?>">
                        <input type="hidden" name="centre_code" id="centre_code" value="<?php echo $edit_row['centre_code']?>">
                        <input type="hidden" name="order_no" id="order_no" value="<?php echo $edit_row['order_no']?>">
                        <input type="hidden" name="booked_by" id="booked_by" value="<?php echo $edit_row['booked_by']?>">
                        <input type="hidden" name="order_no" id="order_no" value="<?php echo $edit_row['order_no']?>">
                        <tbody>
                            <!-- <tr class="uk-text-small">
                                <td class="uk-width-3-10 uk-text-bold">Centre Name</td>
                                <td class="uk-width-7-10">
                                    <select class="uk-width-1-1 chosen-select2" name="centre_code" id="centre_code">
                                        <option value="">Select</option>
                                        <?php
                        //$sql="SELECT * from centre order by company_name";
                       // $result=mysqli_query($connection, $sql);
                       // while ($row=mysqli_fetch_assoc($result)) {
                        ?>
                                        <option value="<?php //echo $row['centre_code']?>"
                                            <?php //if ($row["centre_code"]==$edit_row['centre_code']) {echo "selected";}?>>
                                            <?php //echo $row["company_name"]?></option>
                                        <?php
                        //}
                        ?>
                                    </select>
                                </td>
                            </tr> -->
                            <tr class="uk-text-small">
                                <td class="uk-width-3-10 uk-text-bold">Centre Name</td>
                                <td class="uk-width-7-10"> <input class="uk-width-1-1" name="company_name" id="company_name" placeholder="" value="<?php  echo $edit_row['company_name'];?>" readonly> </td>
                            </tr>
                            <tr class="uk-text-small">
                                <td class="uk-width-3-10 uk-text-bold">Select Date </td>
                                <td class="uk-width-7-10"> <input class="uk-width-1-1" data-uk-datepicker="{format:'YYYY-MM-DD'}" name="select_date" id="select_date"  placeholder="" value="<?php  echo $edit_row['select_date'];?>"> </td>
                            </tr>
                            <tr class="uk-text-small">
                                <td class="uk-width-3-10 uk-text-bold">Select Time </td>
                                <td class="uk-width-7-10"> 
                                <select class="uk-width-1-1 " name="select_time" id="select_time">
                                        <option value="">Select</option>
                                        <option <?php if($edit_row['select_time']=="9 AM"){  echo "selected"; }?> value="9 AM">9 AM</option>
                                        <option <?php if($edit_row['select_time']=="9:30 AM"){  echo "selected"; }?> value="9:30 AM">9:30 AM</option>
                                        <option <?php if($edit_row['select_time']=="10 AM"){  echo "selected"; }?> value="10 AM">10 AM</option>
                                        <option <?php if($edit_row['select_time']=="10:30 AM"){  echo "selected"; }?> value="10:30 AM">10:30 AM</option>
                                        <option <?php if($edit_row['select_time']=="11 AM"){  echo "selected"; }?> value="11 AM">11 AM</option>
                                        <option <?php if($edit_row['select_time']=="11:30 AM"){  echo "selected"; }?> value="11:30 AM">11:30 AM</option>
                                        <option <?php if($edit_row['select_time']=="12 PM"){  echo "selected"; }?> value="12 PM">12 PM</option>
                                        <option <?php if($edit_row['select_time']=="12:30 PM"){  echo "selected"; }?> value="12:30 PM">12:30 PM</option>
                                        <option <?php if($edit_row['select_time']=="1 PM"){  echo "selected"; }?> value="1 PM">1 PM</option>
                                        <option <?php if($edit_row['select_time']=="1:30 PM"){  echo "selected"; }?> value="1:30 PM">1:30 PM</option>
                                        <option <?php if($edit_row['select_time']=="2 PM"){  echo "selected"; }?> value="2 PM">2 PM</option>
                                        <option <?php if($edit_row['select_time']=="2:30 PM"){  echo "selected"; }?> value="2:30 PM">2:30 PM</option>
                                        <option <?php if($edit_row['select_time']=="3 PM"){  echo "selected"; }?> value="3 PM">3 PM</option>
                                        <option <?php if($edit_row['select_time']=="3:30 PM"){  echo "selected"; }?> value="3:30 PM">3:30 PM</option>
                                        <option <?php if($edit_row['select_time']=="4 PM"){  echo "selected"; }?> value="4 PM">4 PM</option>
                                        <option <?php if($edit_row['select_time']=="4:30 PM"){  echo "selected"; }?> value="4:30 PM">4:30 PM</option>
                                        <option <?php if($edit_row['select_time']=="5 PM"){  echo "selected"; }?> value="5 PM">5 PM</option>
                                        <option <?php if($edit_row['select_time']=="5:30 PM"){  echo "selected"; }?> value="5:30 PM">5:30 PM</option>
                                        <option <?php if($edit_row['select_time']=="6 PM"){  echo "selected"; }?> value="6 PM">6 PM</option>
                                    </select>
                                <!-- <input class="uk-width-1-1"
                                        data-uk-datepicker="{format:'YYYY-MM-DD'}" name="select_time" id="select_time"
                                        placeholder="" value="<?php  //echo $edit_row['select_time'];?>"> </td> -->
                            </tr>



                        </tbody>
                    </table>
                </div>
                <div class="uk-width-medium-5-10">
                    <table class="uk-table uk-table-small">

                        <tbody>
                        
                    
                            <tr class="uk-text-small">
                                <td class="uk-width-3-10 uk-text-bold">Select Slot</td>
                                <td class="uk-width-7-10">
                                    <select class="uk-width-1-1 " name="slot_child" id="slot_child">
                                        <option value="">Select</option>
                                        <option <?php if($edit_row['slot_child']=="1"){  echo "selected"; }?> value="1">1</option>
                                        <option <?php if($edit_row['slot_child']=="2"){  echo "selected"; }?> value="2">2</option>
                                        <option <?php if($edit_row['slot_child']=="3"){  echo "selected"; }?> value="3">3</option>
                                        <option <?php if($edit_row['slot_child']=="4"){  echo "selected"; }?> value="4">4</option>
                                        <option <?php if($edit_row['slot_child']=="5"){  echo "selected"; }?> value="5">5</option>
                                        <option <?php if($edit_row['slot_child']=="6"){  echo "selected"; }?> value="6">6</option>
                                        <option <?php if($edit_row['slot_child']=="7"){  echo "selected"; }?> value="7">7</option>
                                        <option <?php if($edit_row['slot_child']=="8"){  echo "selected"; }?> value="8">8</option>
                                        <option <?php if($edit_row['slot_child']=="9"){  echo "selected"; }?> value="9">9</option>
                                        <option <?php if($edit_row['slot_child']=="10"){  echo "selected"; }?> value="10">10</option>
                                        <option <?php if($edit_row['slot_child']=="11"){  echo "selected"; }?> value="11">11</option>
                                        <option <?php if($edit_row['slot_child']=="12"){  echo "selected"; }?> value="12">12</option>
                                        <option <?php if($edit_row['slot_child']=="13"){  echo "selected"; }?> value="13">13</option>
                                        <option <?php if($edit_row['slot_child']=="14"){  echo "selected"; }?> value="14">14</option>
                                        <option <?php if($edit_row['slot_child']=="15"){  echo "selected"; }?> value="15">15</option>
                                        <option <?php if($edit_row['slot_child']=="16"){  echo "selected"; }?> value="16">16</option>
                                        <option <?php if($edit_row['slot_child']=="17"){  echo "selected"; }?> value="17">17</option>
                                        <option <?php if($edit_row['slot_child']=="18"){  echo "selected"; }?> value="18">18</option>
                                        <option <?php if($edit_row['slot_child']=="19"){  echo "selected"; }?> value="19">19</option>
                                        <option <?php if($edit_row['slot_child']=="20"){  echo "selected"; }?> value="20">20</option>
                                    </select>
                                </td>
                            </tr>
                            <tr class="uk-text-small">
                                <td class="uk-width-3-10 uk-text-bold">Remarks</td>
                                <td>
                                    <input class="uk-width-1-1" placeholder="" type="text" name="remarks_master"
                                        id="remarks_master" value="<?php echo $edit_row['remarks_master']?>">
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
            <div class="uk-text-center">
                <!-- <button class="uk-button uk-button-primary">Save</button> -->
                <input type="submit" class="uk-button uk-button-primary aa" value="Save"/>
            </div>

        </div>
    </form>
    
</div>
<?php } ?>
<div class="uk-form uk-margin-top uk-margin-right">
    <div class="uk-width-1-1 myheader">
        <h2 class="uk-text-center myheader-text-color">Searching</h2>
    </div>
    <form class="uk-form" name="frmSlotCollectionManagement" id="frmSlotCollectionManagement" method="get">
            <input type="hidden" name="p" id="p" value="booked_slot_management">
            <input type="hidden" name="mode" id="mode" value="BROWSE">
            <input type="hidden" name="pg" value="">
    <div style="overflow: unset;" class="uk-overflow-container">
        <div class="uk-grid uk-grid-small">
            <!-- <div class="uk-width-medium-2-10">
                <input type="hidden" id="hfCenterCode" name="centre_code" value="<?php echo $_GET['centre_code']?>">
                <input list="centre_code1" id="company_name" name="company_name" value="<?php echo $_GET['company_name']?>"
                    placeholder="Select Centre Name" style="width:100%;">
                <datalist class="form-control" id="centre_code1" style="display:none;">
                   <option value="ALL" <?php // echo $centre_code == 'ALL' ? 'selected' : '' 
					?>>ALL</option>

                    <?php
						// 	$centre_code= $_GET["centre_code"];
						// 	$sql = "SELECT * from centre order by centre_code";
						// 	$result_centre = mysqli_query($connection, $sql);                        
					
						//    while ($row=mysqli_fetch_assoc($result_centre)) {
						  ?>
                    <option value="<?php // $row['company_name']?>" <?php //if($_GET["centre_code"]==$row["centre_code"]){ echo 'selected';}?>> <?php //echo $row["centre_code"]
																				?></option>
                    <?php
						 //  }
						  ?>
                </datalist> 
                <script>
                // var objCompanyName = document.getElementById('company_name');
                // $(document).on('change', objCompanyName, function() {
                //     // console.log("options[i].text")
                //     var options = $('datalist')[0].options;
                //     for (var i = 0; i < options.length; i++) {
                //         // console.log($(objCompanyName).val())
                //         if (options[i].value == $(objCompanyName).val()) {
                //             $("#hfCenterCode").val(options[i].text);
                //             break;
                //         }
                //     }
                // });
                </script>
            </div>-->  
            <div class="uk-width-medium-2-10" style="padding-right:0px;">
            <?php
                $sql = "SELECT * from centre order by centre_code";
                $result = mysqli_query($connection, $sql);
              ?>
                  <input list="centre_name" id="screens.screenid" name="centre_name" value="<?php echo $_GET["centre_name"] ?>" style="width: 100%;">
                  <datalist class="form-control" id="centre_name" style="display: none;" >
                    <option value="ALL" <?php echo $centreCode == 'ALL' ? 'selected' : '' ?>>All Centre</option>
                    <?php
                    while ($row = mysqli_fetch_assoc($result)) {
                    ?>
                      <option value="<?php echo $row['company_name'] ?>" <?php echo $row['centre_code'] == $centreCode ? 'selected' : '' ?>><?php echo $row["centre_code"] ?></option>
                    <?php
                    }
                    ?>
                  </datalist>
            </div>
            <div class="uk-width-medium-1-10" style="padding-right:0px;">
                <input class="uk-width-1-1" data-uk-datepicker="{format:'YYYY-MM-DD'}" name="date_from" id="date_from"
                    placeholder="Date From" value="<?php echo $_GET['date_from']?>">
            </div>
            <div class="uk-width-medium-1-10" style="padding-right:0px;">
                <input class="uk-width-1-1" data-uk-datepicker="{format:'YYYY-MM-DD'}" name="date_to" id="date_to"
                    placeholder="Date To" value="<?php echo $_GET['date_to']?>">
            </div>
            <div class="uk-width-medium-2-10">
                <select class="uk-width-1-1 " name="select_time" id="select_time">
                    <option value="">Select Time</option>
                    <option <?php if($_GET['select_time']=="9 AM"){  echo "selected"; }?> value="9 AM">9 AM</option>
                    <option <?php if($_GET['select_time']=="9:30 AM"){  echo "selected"; }?> value="9:30 AM">9:30 AM</option>
                    <option <?php if($_GET['select_time']=="10 AM"){  echo "selected"; }?> value="10 AM">10 AM</option>
                    <option <?php if($_GET['select_time']=="10:30 AM"){  echo "selected"; }?> value="10:30 AM">10:30 AM</option>
                    <option <?php if($_GET['select_time']=="11 AM"){  echo "selected"; }?> value="11 AM">11 AM</option>
                    <option <?php if($_GET['select_time']=="11:30 AM"){  echo "selected"; }?> value="11:30 AM">11:30 AM</option>
                    <option <?php if($_GET['select_time']=="12 PM"){  echo "selected"; }?> value="12 PM">12 PM</option>
                    <option <?php if($_GET['select_time']=="12:30 PM"){  echo "selected"; }?> value="12:30 PM">12:30 PM</option>
                    <option <?php if($_GET['select_time']=="1 PM"){  echo "selected"; }?> value="1 PM">1 PM</option>
                    <option <?php if($_GET['select_time']=="1:30 PM"){  echo "selected"; }?> value="1:30 PM">1:30 PM</option>
                    <option <?php if($_GET['select_time']=="2 PM"){  echo "selected"; }?> value="2 PM">2 PM</option>
                    <option <?php if($_GET['select_time']=="2:30 PM"){  echo "selected"; }?> value="2:30 PM">2:30 PM</option>
                    <option <?php if($_GET['select_time']=="3 PM"){  echo "selected"; }?> value="3 PM">3 PM</option>
                    <option <?php if($_GET['select_time']=="3:30 PM"){  echo "selected"; }?> value="3:30 PM">3:30 PM</option>
                    <option <?php if($_GET['select_time']=="4 PM"){  echo "selected"; }?> value="4 PM">4 PM</option>
                    <option <?php if($_GET['select_time']=="4:30 PM"){  echo "selected"; }?> value="4:30 PM">4:30 PM</option>
                    <option <?php if($_GET['select_time']=="5 PM"){  echo "selected"; }?> value="5 PM">5 PM</option>
                    <option <?php if($_GET['select_time']=="5:30 PM"){  echo "selected"; }?> value="5:30 PM">5:30 PM</option>
                    <option <?php if($_GET['select_time']=="6 PM"){  echo "selected"; }?> value="6 PM">6 PM</option>
                </select>
            </div>
            <div class="uk-width-medium-2-10">
            <select class="uk-width-1-1 " name="slot_child" id="slot_child">
                <option value="">Select Slot </option>
                <option <?php if($_GET['slot_child']=="1"){  echo "selected"; }?> value="1">1</option>
                <option <?php if($_GET['slot_child']=="2"){  echo "selected"; }?> value="2">2</option>
                <option <?php if($_GET['slot_child']=="3"){  echo "selected"; }?> value="3">3</option>
                <option <?php if($_GET['slot_child']=="4"){  echo "selected"; }?> value="4">4</option>
                <option <?php if($_GET['slot_child']=="5"){  echo "selected"; }?> value="5">5</option>
                <option <?php if($_GET['slot_child']=="6"){  echo "selected"; }?> value="6">6</option>
                <option <?php if($_GET['slot_child']=="7"){  echo "selected"; }?> value="7">7</option>
                <option <?php if($_GET['slot_child']=="8"){  echo "selected"; }?> value="8">8</option>
                <option <?php if($_GET['slot_child']=="9"){  echo "selected"; }?> value="9">9</option>
                <option <?php if($_GET['slot_child']=="10"){  echo "selected"; }?> value="10">10</option>
                <option <?php if($_GET['slot_child']=="11"){  echo "selected"; }?> value="11">11</option>
                <option <?php if($_GET['slot_child']=="12"){  echo "selected"; }?> value="12">12</option>
                <option <?php if($_GET['slot_child']=="13"){  echo "selected"; }?> value="13">13</option>
                <option <?php if($_GET['slot_child']=="14"){  echo "selected"; }?> value="14">14</option>
                <option <?php if($_GET['slot_child']=="15"){  echo "selected"; }?> value="15">15</option>
                <option <?php if($_GET['slot_child']=="16"){  echo "selected"; }?> value="16">16</option>
                <option <?php if($_GET['slot_child']=="17"){  echo "selected"; }?> value="17">17</option>
                <option <?php if($_GET['slot_child']=="18"){  echo "selected"; }?> value="18">18</option>
                <option <?php if($_GET['slot_child']=="19"){  echo "selected"; }?> value="19">19</option>
                <option <?php if($_GET['slot_child']=="20"){  echo "selected"; }?> value="20">20</option>
            </select>
            </div>
           
            <div class="uk-width-medium-2-10">
            <button class="uk-button uk-width-1-1">Search</button>
            </div><br><br>
            <!-- <div class="uk-text-muted">* List latest 50 records, newest first</div> -->
        </div>
    </div>
    </form>
</div>
<br>
<div class="uk-width-1-1 myheader">
    <h2 class="uk-text-center myheader-text-color">Listing</h2>
</div>
<div class="uk-overflow-container">
<?php    
$numperpage = 10;
         $query = "p=$p&m=$m&s=$s";
         $pagination = getPagination($pg, $numperpage, $query, $browse_sql, $start_record, $num_row);
         $browse_sql .= " limit $start_record, $numperpage";
         //echo $browse_sql; 
         //die;
         $browse_result = mysqli_query($connection, $browse_sql);
         $browse_num_row = mysqli_num_rows($browse_result);
         ?>

         <table class="uk-table uk-table-striped">
            <tr class="uk-text-bold uk-text-small">
               <td>Centre</td>
               <!-- <td>Booked by Centre</td> -->
               <td>Date</td>
               <td>Time</td>
               <td>Slot</td>
               <!-- <td>Remarks</td> -->
               <td>Master Remarks</td>
               <td>Action</td>
            </tr>
            <?php
            if ($browse_num_row > 0) {
               while ($browse_row = mysqli_fetch_assoc($browse_result)) {
                  $sha1_id = sha1($browse_row["id"]);
            ?>
                  <tr class="uk-text-small">
                     <td><?php echo $browse_row["centre_name"] ?></td>
                     <td><?php echo $browse_row["select_date"] ?></td>
                     <td><?php echo $browse_row["select_time"] ?></td>
                     <td><?php echo $browse_row["slot_child"] ?></td>
                     <!-- <td><?php //echo $browse_row["remarks"] ?></td> -->
                     <td><?php echo $browse_row["remarks_master"] ?></td>
                     <td>
                     <?php 
                     //$child_sql="SELECT * from `slot_collection_child` where sha1(slot_master_id)='$sha1_id' and is_booked=1";
                     //$result=mysqli_query($connection, $child_sql);
                     //$child_num_row=mysqli_fetch_assoc($result);
                     //if ($child_num_row == 0) {
                     //if($browse_row["is_booked"]==0){ 
                         ?>
                        <a href="index.php?p=booked_slot_management&id=<?php echo $sha1_id ?>&mode=EDIT"><img src="images/edit.png"></a>
                        <a onclick="doDeleteRecord('<?php echo $sha1_id ?>')" href="#" id="btnDelete"><img src="images/delete.png"></a>
                        <?php //}} ?>
                     </td>
                  </tr>
            <?php
               }
            } else {
               echo "<tr><td colspan='6'>No Record Found</td></tr>";
            }
            ?>
         </table>
         <?php
         echo $pagination;
         ?>
</div>
<form name="frmDeleteRecord" id="frmDeleteRecord" method="get">
         <input type="hidden" name="p" value="booked_slot_management">
         <input type="hidden" name="id" id="id" value="">
         <input type="hidden" name="mode" value="DEL">
      </form>
<script>
    function doDeleteRecord(id) {
    UIkit.modal.confirm("<h2>Are you sure to continue?</h2>", function() {
        $("#id").val(id);
        $("#frmDeleteRecord").submit();
    });
    }
</script>
<?php
   }
}
if ($msg != "") {
    echo "<script>UIkit.notify('$msg')</script>";
    echo "<script>setTimeout(
        function() 
        {
            window.location.replace('/index.php?p=booked_slot_management');
        }, 2000);</script>";
}
?>
<style>
.uk-button.uk-button-primary.aa {
    margin-top: 20px
}
* + .uk-table {
    margin-top: 6px;
}
</style>