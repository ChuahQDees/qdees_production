<div class="student-page">
    <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-4 col-sm-12 d-flex justify-content-center">
            <div>
                <div class="text-center student-block d-flex justify-content-center align-items-center"  style="cursor:pointer;">
                        <div class="genQR" data-toggle="modal" data-target=".modal">
                            <img src="images/Category/qr.png" style="width: 100px; height: 100px;">
                            <p>Visitor Registration QR Code</p>
                        </div>
                    <div class="modal" tabindex="-1" role="dialog">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header myheader">
                                    <h5 class="uk-text-center myheader-text-color myheader-text-style">QR Code</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
									<a href="admin/visitor_qrcode.php">
										<img src="admin/visitor_qrcode.php" alt=""><br/><br>
										<button class="uk-button uk-button-primary form_btn">Download</button>
									</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="toCopy" style="margin-top: 25px;" class="text-center"><span style="background-color: #3399ff;">(Copy link)</span></div>
                <a target="_blank" target="_blank" href="visitor_qr.php?centre_code=<?php echo $_SESSION['CentreCode']?>"><div style="margin-top: 25px;" class="text-center"><span style="font-size: 1rem; cursor: pointer; border-radius: 8px; padding: .5em 1.5em; background:transparent !important; border: 1px solid #6F757D; color: #6F757D ">Open in new tab</span></div></a>
            </div>
        </div>
		<div class="col-md-4 col-sm-12 d-flex justify-content-center">
            <a target="_self" href="index.php?p=visitor_qr_list">
                <div class="text-center student-block d-flex justify-content-center align-items-center">
                    <div>
                        <img src="images/Category/VisitorReg.png" style="width: 120px; height: 100px;">
                        <p>Visitor Registration Listing</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-2"></div>
    </div>
</div>

/////
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
                        <td class="uk-width-3-10">
                        <select name="student_entry_level" id="student_entry_level" class="uk-width-1-1" style="width: 100px;">
                                <option value="">Select</option>
                                <option <?php if($row3["student_entry_level"]=="EDP"){ echo "selected"; } ?> value="EDP">EDP</option>
                                <option <?php if($row3["student_entry_level"]=="QF1"){ echo "selected"; } ?> value="QF1">QF1</option>
                                <option <?php if($row3["student_entry_level"]=="QF2"){ echo "selected"; } ?> value="QF2">QF2</option>
                                <option <?php if($row3["student_entry_level"]=="QF3"){ echo "selected"; } ?> value="QF3">QF3</option>
                                </select>
                            <span id="validationStudentEntryLevel" style="color: red; display: none;">Please
                                select Student Entry Level</span>
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
                                            <option <?php echo ($row1['foundation_int_english']==1? 'selected' : ''); ?> value="1">Yes</option>
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