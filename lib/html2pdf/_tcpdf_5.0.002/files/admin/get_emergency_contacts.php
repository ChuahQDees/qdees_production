<?php
session_start();
include_once("../mysql.php");

$form_mode = $_POST["form_mode"];
$student_code = $_POST["student_code"];

function generateRandomString($length)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

?>
<script>
    function doClose() {
        $("#dlgEmergencyContacts").dialog("close");
    }

    function deleteRow(id) {
        $("table#tblContact tr#" + id).remove();
    }

    function addRow() {
        var html = "";
        var row_id = "row" + Math.floor(Math.random() * 1000000);

        html = "";
        html = html + "<tr class=\"uk-text-small\" id='" + row_id + "'>";
        html = html + "   <td>";
        html = html + "      <select class=\"uk-form-small\" name=\"contact_type[]\" required>";
        html = html + "         <option value=\"\">Select</option>";
        html = html + "         <option value=\"Mother\">Mother</option>";
        html = html + "         <option value=\"Father\">Father</option>";
        html = html + "         <option value=\"Guardian\">Guardian</option>";
        html = html + "         <option value=\"Others\">Others</option>";
        html = html + "      </select>";
        html = html + "   </td>";
        html = html + "   <td><input type=\"text\" placeholder=\"Name as per IC/Passport\" name=\"full_name[]\" id=\"full_name_" + row_id + "\" value=\"\" class=\"uk-form-small\" required></td>";
        html = html + "   <td><input type=\"text\" placeholder=\"IC/Passport\" name=\"nric[]\" id=\"nric_" + row_id + "\" value=\"\" class=\"uk-form-small\" required></td>";
        html = html + "   <td><input type=\"email\" placeholder=\"Email\" name=\"email[]\" id=\"email_" + row_id + "\" value=\"\" class=\"uk-form-small\" required></td>";
        html = html + "   <td><input type=\"text\" placeholder=\"+60\" size=\"3\" name=\"mobile_country_code[]\" id=\"mobile_country_code_" + row_id + "\" value=\"\" class=\"uk-form-small\" maxlength=\"3\" required style=\"width:50px\">";
        html = html + "   <input type=\"tel\" placeholder=\"12345678\" size=\"10\" name=\"mobile[]\" id=\"mobile_" + row_id + "\" value=\"\" class=\"uk-form-small\" required></td>";
        html = html + "   <td>";
        html = html + "       <select name=\"occupation[]\" id=\"occupation_" + row_id + "\" class=\"uk-form-small uk-width-1-1\" required>";
        html = html + "          <option value=\"\">Select</option>";
        <?php
        $sql = "SELECT code from codes where module='OCCUPATION' order by code";
        $oresult = mysqli_query($connection, $sql);
        while ($orow = mysqli_fetch_assoc($oresult)) {
        ?>
        html = html + "          <option value=\"<?php echo $orow['code']?>\"><?php echo $orow['code']?></option>";
        <?php
        }
        ?>
        html = html + "       </select>";

//   html=html+"       <input type=\"text\" placeholder=\"Occupation\" name=\"occupation[]\" id=\"occupation\" value=\"\" class=\"uk-form-small\" required>";
        html = html + "   </td>";
        html = html + "   <td>";


        html = html + "       <select name=\"education_level[]\" id=\"education_" + row_id + "\" class=\"uk-form-small uk-width-1-1\">";
        html = html + "          <option value=\"\">Select</option>";
        <?php
        $sql = "SELECT code from codes where module='EDUCATION' order by code";
        $oresult = mysqli_query($connection, $sql);
        while ($orow = mysqli_fetch_assoc($oresult)) {
        ?>
        html = html + "          <option value=\"<?php echo $orow['code']?>\"><?php echo $orow['code']?></option>";
        <?php
        }
        ?>
        html = html + "       </select>";

        //html=html+"      <input type=\"text\" placeholder=\"Education Level\" name=\"education_level[]\" id=\"education_level\" value=\"\" class=\"uk-form-small\" required>";
        html = html + "   </td>";

        html = html + "   <td>";
        html = html + "      <select name=\"can_pick_up[]\" id=\"can_pick_up_" + row_id + "\" class=\"uk-form-small uk-width-1-1\" onchange=\"doChange('" + row_id + "');\" required>";
        html = html + "         <option value=\"\">Select</option>";
        html = html + "         <option value=\"1\">Yes</option>";
        html = html + "         <option value=\"0\">No</option>";
        html = html + "      </select>";
        html = html + "   </td>";
        html = html + "   <td><input type=\"text\" placeholder=\"Vehicle No.\" size=\"10\" name=\"vehicle_no[]\" id=\"vehicle_no_" + row_id + "\" value=\"\" class=\"uk-form-small\" required></td>";
        html = html + "   <td><input type=\"text\" placeholder=\"Remarks\" name=\"remarks[]\" id=\"remarks_" + row_id + "\" value=\"\" class=\"uk-form-small\"></td>";
        html = html + "   <td>";
        html = html + "      <a><img data-uk-tooltip=\"{pos:top}\" title=\"Remove Row\" onclick=\"deleteRow('" + row_id + "');\" src=\"images/delete.png\"></a>";
        html = html + "   </td>";
        html = html + "</tr>";

        $("#tblContact").append(html);
    }

    function doChange(id) {
        var can_pick_up = $("#can_pick_up_" + id).val();

        if (can_pick_up == "1") {
            $("#vehicle_no_" + id).prop("readonly", false)
        } else {
            $("#vehicle_no_" + id).val("");
            $("#vehicle_no_" + id).prop("readonly", true);
        }
    }

    function doSave() {
        var theform = $("#frmSaveContact")[0];
        var formdata = new FormData(theform);

        $.ajax({
            url: "admin/save_emergency_contacts.php",
            type: "POST",
            data: formdata,
            dataType: "text",
            enctype: 'multipart/form-data',
            processData: false,
            contentType: false,
            beforeSend: function (http) {
            },
            success: function (response, status, http) {
                UIkit.notify(response);
                $("#dlgEmergencyContacts").dialog("close");

                var primaryContactName = $('.contact-row:first input[name="full_name[]"]').val();
                if(primaryContactName){
                    $('#primary-contact').text(primaryContactName);
                }
            },
            error: function (http, status, error) {
                UIkit.notify("Error:" + error);
            }
        });
    }

    $(document).ready(function () {

        $('#frmSaveContact').on('keyup', 'input[type="tel"]', function (e) {
            if (/\D/g.test(this.value)) {
                // Filter non-digits from input value.
                this.value = this.value.replace(/\D/g, '');
            }
        });

        $('#frmSaveContact').submit(function(e){
          e.preventDefault();
          doSave();
        });
    });

</script>
<style>
    .ui-widget.ui-widget-content {
        right: 0;
    }

    .responsive-table {
        display: block;
        width: 100%;
        overflow-x: auto;
    }
</style>
<form class="uk-form" name="frmSaveContact" id="frmSaveContact" method="post" style="width: 100%">
    <input type="hidden" name="form_mode" value="<?php echo $form_mode ?>">
    <input type="hidden" name="student_code" id="student_code" value="<?php echo $student_code ?>">
    <div class="responsive-table">
        <table class="uk-table uk-form-small" id="tblContact">
            <tr class="uk-text-small uk-text-bold">
                <td>Contact Type*</td>
                <td>Name as per IC/Passport*</td>
                <td>IC/Passport*</td>
                <td>Primary Email*</td>
                <td>Mobile*</td>
                <td>Occupation*</td>
                <td>Education Level*</td>
                <td>Authorised To Pick Up*</td>
                <td>Vehicle No.*</td>
                <td>Remarks</td>
                <td>
                    <a style="display: inline-block; width:20px; height:20px">
                        <img data-uk-tooltip="{pos:top}" title="Add Contact" onclick="addRow();" src="images/add.png">
                    </a>
                </td>
            </tr>
            <?php
            if ($_SESSION["isLogin"] == 1 && $form_mode!='qr') {
                $sql = "SELECT * from student_emergency_contacts where student_code='$student_code' order by id";
            } else {
                $sql = "SELECT * from tmp_student_emergency_contacts where student_code='$student_code' order by id";
            }
            //echo $sql;
            $result = mysqli_query($connection, $sql);
            $num_row = mysqli_num_rows($result);
            while ($row = mysqli_fetch_assoc($result)) {
                $row_id = generateRandomString(8);
                ?>
                <tr class="uk-text-small contact-row" id="<?php echo $row_id ?>">
                    <td>
                        <select class="uk-form-small" name="contact_type[]" required>
                            <option value="">Select</option>
                            <option value="Mother" <?php if ($row['contact_type'] == 'Mother') {
                                echo "selected";
                            } ?>>Mother
                            </option>
                            <option value="Father" <?php if ($row['contact_type'] == 'Father') {
                                echo "selected";
                            } ?>>Father
                            </option>
                            <option value="Guardian" <?php if ($row['contact_type'] == 'Guardian') {
                                echo "selected";
                            } ?>>Guardian
                            </option>
                            <option value="Others" <?php if ($row['contact_type'] == 'Others') {
                                echo "selected";
                            } ?>>Others
                            </option>
                        </select>
                    </td>
                    <td><input type="text" placeholder="" name="full_name[]" id="full_name"
                               value="<?php echo $row['full_name'] ?>" class="uk-form-small" required></td>
                    <td><input type="text" placeholder="" name="nric[]" id="nric" value="<?php echo $row['nric'] ?>"
                               class="uk-form-small" required></td>
                    <td><input type="text" placeholder="Email" name="email[]" id="email"
                               value="<?php echo $row['email'] ?>" class="uk-form-small" required></td>
                    <td>
                        <input type="text" placeholder="e.g +60" name="mobile_country_code[]" id="mobile_country"
                               value="<?php echo $row['mobile_country_code'] ?>" class="uk-form-small" maxlength="3"
                               required style="width: 50px">
                        <input type="tel" placeholder="012345678" name="mobile[]" id="mobile"
                               value="<?php echo $row['mobile'] ?>" class="uk-form-small" required>
                    </td>

                    <td>
                        <select name="occupation[]" id="occupation" class="uk-form-small uk-width-1-1" required>
                            <option value="">Select</option>
                            <?php
                            $sql = "SELECT code from codes where module='OCCUPATION' order by code";
                            $oresult = mysqli_query($connection, $sql);
                            while ($orow = mysqli_fetch_assoc($oresult)) {
                                ?>
                                <option value="<?php echo $orow['code'] ?>" <?php if ($orow['code'] == $row['occupation']) {
                                    echo "selected";
                                } ?>><?php echo $orow['code'] ?></option>
                                <?php
                            }
                            ?>
                        </select>
                        <!-- <input type="text" placeholder="Occupation" name="occupation[]" id="occupation" value="<?php echo $row['occupation'] ?>" class="uk-form-small" required> -->
                    </td>
                    <td>
                        <select name="education[]" id="education" class="uk-form-small uk-width-1-1">
                            <option value="">Select</option>
                            <?php
                            $sql = "SELECT code from codes where module='EDUCATION' order by code";
                            $oresult = mysqli_query($connection, $sql);
                            while ($orow = mysqli_fetch_assoc($oresult)) {
                                ?>
                                <option value="<?php echo $orow['code'] ?>" <?php if ($orow['code'] == $row['education_level']) {
                                    echo "selected";
                                } ?>><?php echo $orow['code'] ?></option>
                                <?php
                            }
                            ?>
                        </select>
                        <!-- <input type="text" placeholder="Education Level" name="education_level[]" id="education_level" value="<?php echo $row['education_level'] ?>" class="uk-form-small" required> -->
                    </td>
                    <td>
                        <select name="can_pick_up[]" id="can_pick_up_<?php echo $row_id ?>"
                                class="uk-form-small uk-width-1-1" onchange="doChange('<?php echo $row_id ?>');"
                                required>
                            <option value="">Select</option>
                            <option value="1" <?php if ($row["can_pick_up"] == 1) {
                                echo "selected";
                            } ?>>Yes
                            </option>
                            <option value="0" <?php if ($row["can_pick_up"] == 0) {
                                echo "selected";
                            } ?>>No
                            </option>
                        </select>
                    </td>
                    <td><input type="text" placeholder="Vehicle No." name="vehicle_no[]" size="10"
                               id="vehicle_no_<?php echo $row_id ?>" value="<?php echo $row['vehicle_no'] ?>"
                               class="uk-form-small" required></td>
                    <td><input type="text" placeholder="Remarks" name="remarks[]" id="remarks_<?php echo $row_id ?>"
                               value="<?php echo $row['remarks'] ?>" class="uk-form-small"></td>
                    <td>
                        <a style="display: inline-block; width:20px; height:20px"><img data-uk-tooltip="{pos:top}"
                                                                                       title="Remove Row"
                                                                                       onclick="deleteRow('<?php echo $row_id ?>');"
                                                                                       src="images/delete.png"></a>
                    </td>
                </tr>
                <?php
            }
            ?>
            <?php
            if ($num_row == 0) {
                ?>
                <script>addRow();</script>
                <?php
            }
            ?>
        </table>
    </div>
    <p style="color: red; font-weight: bold">NOTE: The first contact above is the Primary Contact.</p>
    <button type="submit" class="uk-button uk-button-small uk-button-primary">Save</button>
    <a onclick="doClose();" class="uk-button uk-button-small">Close</a>
</form>
