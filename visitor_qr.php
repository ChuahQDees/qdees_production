<html>

<head>
    <link rel="stylesheet" type="text/css" href="css/my.css">
    <?php include_once("uikit.php"); ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <style>
        .myheader {
            background: #69a6f7;
            padding: 15px 0;
            color: white !important;
        }

        .myheader h1 {
            text-transform: uppercase;
            font-weight: 600;
        }

        .myheader-text-color {
            text-transform: uppercase;
            font-weight: 600;
        }

        .uk-margin-top {
            margin: 0 !important;
        }

        .uk-text-center.bg-form {
            padding: 20px;
            background-image: url(/images/backgroundLogin.png);
            background-repeat: no-repeat;
            background-size: cover;
        }

        .container-50 {
            max-width: 50%;
            margin: 1.5em auto
        }

        button[type="submit"] {
            font-size: 1.1rem;
            cursor: pointer;
            padding: 15px;
            border-radius: 8px;
            padding: .2em 1.5em;
            width: 100%;
            background: #FDBA0C !important;
            color: white;
            font-weight: bold;
            text-shadow: 0px 2px 2px #00000059;
            letter-spacing: 1px;
        }

        .text-white {
            color: white !important;
        }

        div[id^="row-child-birth"]:not(#row-child-birth-year-1) {
            display: none;
            margin-top: .6em;
        }

        form p {
            margin-top: 1em;
        }

        .text-danger {
            color: red
        }


        input:not([type="submit"]) {
            border-radius: 0px !important;
            border: 1px solid black !important;
            padding: 7px;
        }

        #row-child-birth-year-1 input,
        #row-child-birth-year-2 input,
        #row-child-birth-year-3 input,
        #row-child-birth-year-4 input,
        #row-child-birth-year-5 input,
        #row-child-birth-year-6 input {
            margin-bottom: 10px;
        }

        .email_kk {
            margin-top: 15px;
        }

        .uk-form select {
            border-color: #000;
            border-radius: 0;
        }

        #row-child-birth-year-1,
        #row-child-birth-year-2,
        #row-child-birth-year-3,
        #row-child-birth-year-4,
        #row-child-birth-year-5,
        #row-child-birth-year-6 {
            /* display: block; */
            height: 60px;
        }

        #validationBirth,
        #validationBirth1,
        #validationBirth2,
        #validationBirth3,
        #validationBirth4,
        #validationBirth5,
        #validationBirth6 {
            margin-right: 16%;
        }

        .aset {
            display: flex;
            width: 100%;
            margin-left: 0;
            margin-top: 15px;
        }

        .aset div select {
            margin-bottom: 10px;
        }

        @media only screen and (max-width: 1500px) {

            #validationBirth,
            #validationBirth1,
            #validationBirth2,
            #validationBirth3,
            #validationBirth4,
            #validationBirth5,
            #validationBirth6 {
                margin-right: 12%;
            }
        }

        @media only screen and (max-width: 767px) {

            #validationChildName,
            #validationChildName2,
            #validationChildName3,
            #validationChildName4,
            #validationChildName5,
            #validationChildName6,
            #validationBirth,
            #validationBirth2,
            #validationBirth3,
            #validationBirth4,
            #validationBirth5,
            #validationBirth6 {
                color: red;
                font-size: 10px;
                line-height: 1;
            }


            .container-50 {
                max-width: 80%;
                margin: 1.5em auto;
            }
        }
        .chosen-container-multi .chosen-choices {
				border: 1px solid #000!important;				
			}
    </style>
    <script>
        function doSave() {
            var name = $("#name").val();
            var tel = $("#tel").val();
            var age = $("#age").val();
            var email = $("#email").val();
            var find_out = $("#find_out").val();

            if ((name != "") & (tel != "") & (age != "") & (find_out != "")) {
                $("#frmVisitor").submit();

            } else {
                UIkit.notify("Please fill up mandatory fields marked *");
                $('#find_out').val("required", true);
            }
        }

        $(document).ready(function() {
            $('#select-number-of-children').on('change', function(e) {
                var value = $('#select-number-of-children').val();

                for (var i = 1; i <= 6; i++) {
                    $('#child_birth_year_' + i).removeAttr('required');
                    $('#row-child-birth-year-' + i).hide();
                }

                for (var i = 1; i <= value; i++) {
                    $('#child_birth_year_' + i).prop("required", false);
                    $('#row-child-birth-year-' + i).show();
                    $('#row-child-birth-year-' + i).css('display', 'flex');
                }

            });
        });
    </script>
    <title>Qdees</title>
</head>
<?php
$centre_code = $_GET["centre_code"];
?>

<body>
    <?php
    if ($centre_code != "") {
    ?>
        <div class="uk-text-center bg-form"><img src="images/logo.png"></div>

        <div class="myheader myheader-text-color">
            <h1 class="uk-text-center text-white">Visitor Registration Form</h1>
            <h4 class="uk-text-center text-white">Please enter your information and click submit button</h4>
        </div>


        <form name="frmVisitor" id="frmVisitor" method="post" action="save_visitor.php" class="uk-form container-50">
            <input type="hidden" name="centre_code" id="centre_code" value="<?php echo $centre_code ?>">

            <div class="row">
                <div class="col-sm-12">
                    <p>Visitor's Name as per IC/Passport <span class="text-danger">*</span>:</p>
                    <input name="name" id="name" type="text" class="uk-width-1-1" placeholder="Visitor's Name" value="">
                    <span id="validationName" style="color: red; display: none;">Please insert your name</span>
                </div>

                <div class="col-sm-12">
                    <p>Visitor's IC/Passport <span class="text-danger">*</span>:</p>
                    <input name="nric" id="nric" type="text" class="uk-width-1-1" placeholder="Visitor's IC/Passport" value="">
                    <span id="validationIC" style="color: red; display: none;">Please insert your IC/Passport</span>
                </div>

                <div class="col-sm-12">
                    <p>Visitor's Country Code. <span class="text-danger">*</span>:</p>
                    <select class="uk-width-1-1" name="countryCode" id="countryCode">
                        <option data-countryCode="MY" value="60" Selected>Malaysia (+60)</option>
                        <optgroup label="Other countries">
                            <option data-countryCode="DZ" value="213">Algeria (+213)</option>
                            <option data-countryCode="AD" value="376">Andorra (+376)</option>
                            <option data-countryCode="AO" value="244">Angola (+244)</option>
                            <option data-countryCode="AI" value="1264">Anguilla (+1264)</option>
                            <option data-countryCode="AG" value="1268">Antigua &amp; Barbuda (+1268)</option>
                            <option data-countryCode="AR" value="54">Argentina (+54)</option>
                            <option data-countryCode="AM" value="374">Armenia (+374)</option>
                            <option data-countryCode="AW" value="297">Aruba (+297)</option>
                            <option data-countryCode="AU" value="61">Australia (+61)</option>
                            <option data-countryCode="AT" value="43">Austria (+43)</option>
                            <option data-countryCode="AZ" value="994">Azerbaijan (+994)</option>
                            <option data-countryCode="BS" value="1242">Bahamas (+1242)</option>
                            <option data-countryCode="BH" value="973">Bahrain (+973)</option>
                            <option data-countryCode="BD" value="880">Bangladesh (+880)</option>
                            <option data-countryCode="BB" value="1246">Barbados (+1246)</option>
                            <option data-countryCode="BY" value="375">Belarus (+375)</option>
                            <option data-countryCode="BE" value="32">Belgium (+32)</option>
                            <option data-countryCode="BZ" value="501">Belize (+501)</option>
                            <option data-countryCode="BJ" value="229">Benin (+229)</option>
                            <option data-countryCode="BM" value="1441">Bermuda (+1441)</option>
                            <option data-countryCode="BT" value="975">Bhutan (+975)</option>
                            <option data-countryCode="BO" value="591">Bolivia (+591)</option>
                            <option data-countryCode="BA" value="387">Bosnia Herzegovina (+387)</option>
                            <option data-countryCode="BW" value="267">Botswana (+267)</option>
                            <option data-countryCode="BR" value="55">Brazil (+55)</option>
                            <option data-countryCode="BN" value="673">Brunei (+673)</option>
                            <option data-countryCode="BG" value="359">Bulgaria (+359)</option>
                            <option data-countryCode="BF" value="226">Burkina Faso (+226)</option>
                            <option data-countryCode="BI" value="257">Burundi (+257)</option>
                            <option data-countryCode="KH" value="855">Cambodia (+855)</option>
                            <option data-countryCode="CM" value="237">Cameroon (+237)</option>
                            <option data-countryCode="CA" value="1">Canada (+1)</option>
                            <option data-countryCode="CV" value="238">Cape Verde Islands (+238)</option>
                            <option data-countryCode="KY" value="1345">Cayman Islands (+1345)</option>
                            <option data-countryCode="CF" value="236">Central African Republic (+236)</option>
                            <option data-countryCode="CL" value="56">Chile (+56)</option>
                            <option data-countryCode="CN" value="86">China (+86)</option>
                            <option data-countryCode="CO" value="57">Colombia (+57)</option>
                            <option data-countryCode="KM" value="269">Comoros (+269)</option>
                            <option data-countryCode="CG" value="242">Congo (+242)</option>
                            <option data-countryCode="CK" value="682">Cook Islands (+682)</option>
                            <option data-countryCode="CR" value="506">Costa Rica (+506)</option>
                            <option data-countryCode="HR" value="385">Croatia (+385)</option>
                            <option data-countryCode="CU" value="53">Cuba (+53)</option>
                            <option data-countryCode="CY" value="90392">Cyprus North (+90392)</option>
                            <option data-countryCode="CY" value="357">Cyprus South (+357)</option>
                            <option data-countryCode="CZ" value="42">Czech Republic (+42)</option>
                            <option data-countryCode="DK" value="45">Denmark (+45)</option>
                            <option data-countryCode="DJ" value="253">Djibouti (+253)</option>
                            <option data-countryCode="DM" value="1809">Dominica (+1809)</option>
                            <option data-countryCode="DO" value="1809">Dominican Republic (+1809)</option>
                            <option data-countryCode="EC" value="593">Ecuador (+593)</option>
                            <option data-countryCode="EG" value="20">Egypt (+20)</option>
                            <option data-countryCode="SV" value="503">El Salvador (+503)</option>
                            <option data-countryCode="GQ" value="240">Equatorial Guinea (+240)</option>
                            <option data-countryCode="ER" value="291">Eritrea (+291)</option>
                            <option data-countryCode="EE" value="372">Estonia (+372)</option>
                            <option data-countryCode="ET" value="251">Ethiopia (+251)</option>
                            <option data-countryCode="FK" value="500">Falkland Islands (+500)</option>
                            <option data-countryCode="FO" value="298">Faroe Islands (+298)</option>
                            <option data-countryCode="FJ" value="679">Fiji (+679)</option>
                            <option data-countryCode="FI" value="358">Finland (+358)</option>
                            <option data-countryCode="FR" value="33">France (+33)</option>
                            <option data-countryCode="GF" value="594">French Guiana (+594)</option>
                            <option data-countryCode="PF" value="689">French Polynesia (+689)</option>
                            <option data-countryCode="GA" value="241">Gabon (+241)</option>
                            <option data-countryCode="GM" value="220">Gambia (+220)</option>
                            <option data-countryCode="GE" value="7880">Georgia (+7880)</option>
                            <option data-countryCode="DE" value="49">Germany (+49)</option>
                            <option data-countryCode="GH" value="233">Ghana (+233)</option>
                            <option data-countryCode="GI" value="350">Gibraltar (+350)</option>
                            <option data-countryCode="GR" value="30">Greece (+30)</option>
                            <option data-countryCode="GL" value="299">Greenland (+299)</option>
                            <option data-countryCode="GD" value="1473">Grenada (+1473)</option>
                            <option data-countryCode="GP" value="590">Guadeloupe (+590)</option>
                            <option data-countryCode="GU" value="671">Guam (+671)</option>
                            <option data-countryCode="GT" value="502">Guatemala (+502)</option>
                            <option data-countryCode="GN" value="224">Guinea (+224)</option>
                            <option data-countryCode="GW" value="245">Guinea - Bissau (+245)</option>
                            <option data-countryCode="GY" value="592">Guyana (+592)</option>
                            <option data-countryCode="HT" value="509">Haiti (+509)</option>
                            <option data-countryCode="HN" value="504">Honduras (+504)</option>
                            <option data-countryCode="HK" value="852">Hong Kong (+852)</option>
                            <option data-countryCode="HU" value="36">Hungary (+36)</option>
                            <option data-countryCode="IS" value="354">Iceland (+354)</option>
                            <option data-countryCode="IN" value="91">India (+91)</option>
                            <option data-countryCode="ID" value="62">Indonesia (+62)</option>
                            <option data-countryCode="IR" value="98">Iran (+98)</option>
                            <option data-countryCode="IQ" value="964">Iraq (+964)</option>
                            <option data-countryCode="IE" value="353">Ireland (+353)</option>
                            <option data-countryCode="IL" value="972">Israel (+972)</option>
                            <option data-countryCode="IT" value="39">Italy (+39)</option>
                            <option data-countryCode="JM" value="1876">Jamaica (+1876)</option>
                            <option data-countryCode="JP" value="81">Japan (+81)</option>
                            <option data-countryCode="JO" value="962">Jordan (+962)</option>
                            <option data-countryCode="KZ" value="7">Kazakhstan (+7)</option>
                            <option data-countryCode="KE" value="254">Kenya (+254)</option>
                            <option data-countryCode="KI" value="686">Kiribati (+686)</option>
                            <option data-countryCode="KP" value="850">Korea North (+850)</option>
                            <option data-countryCode="KR" value="82">Korea South (+82)</option>
                            <option data-countryCode="KW" value="965">Kuwait (+965)</option>
                            <option data-countryCode="KG" value="996">Kyrgyzstan (+996)</option>
                            <option data-countryCode="LA" value="856">Laos (+856)</option>
                            <option data-countryCode="LV" value="371">Latvia (+371)</option>
                            <option data-countryCode="LB" value="961">Lebanon (+961)</option>
                            <option data-countryCode="LS" value="266">Lesotho (+266)</option>
                            <option data-countryCode="LR" value="231">Liberia (+231)</option>
                            <option data-countryCode="LY" value="218">Libya (+218)</option>
                            <option data-countryCode="LI" value="417">Liechtenstein (+417)</option>
                            <option data-countryCode="LT" value="370">Lithuania (+370)</option>
                            <option data-countryCode="LU" value="352">Luxembourg (+352)</option>
                            <option data-countryCode="MO" value="853">Macao (+853)</option>
                            <option data-countryCode="MK" value="389">Macedonia (+389)</option>
                            <option data-countryCode="MG" value="261">Madagascar (+261)</option>
                            <option data-countryCode="MW" value="265">Malawi (+265)</option>
                            <option data-countryCode="MY" value="60">Malaysia (+60)</option>
                            <option data-countryCode="MV" value="960">Maldives (+960)</option>
                            <option data-countryCode="ML" value="223">Mali (+223)</option>
                            <option data-countryCode="MT" value="356">Malta (+356)</option>
                            <option data-countryCode="MH" value="692">Marshall Islands (+692)</option>
                            <option data-countryCode="MQ" value="596">Martinique (+596)</option>
                            <option data-countryCode="MR" value="222">Mauritania (+222)</option>
                            <option data-countryCode="YT" value="269">Mayotte (+269)</option>
                            <option data-countryCode="MX" value="52">Mexico (+52)</option>
                            <option data-countryCode="FM" value="691">Micronesia (+691)</option>
                            <option data-countryCode="MD" value="373">Moldova (+373)</option>
                            <option data-countryCode="MC" value="377">Monaco (+377)</option>
                            <option data-countryCode="MN" value="976">Mongolia (+976)</option>
                            <option data-countryCode="MS" value="1664">Montserrat (+1664)</option>
                            <option data-countryCode="MA" value="212">Morocco (+212)</option>
                            <option data-countryCode="MZ" value="258">Mozambique (+258)</option>
                            <option data-countryCode="MN" value="95">Myanmar (+95)</option>
                            <option data-countryCode="NA" value="264">Namibia (+264)</option>
                            <option data-countryCode="NR" value="674">Nauru (+674)</option>
                            <option data-countryCode="NP" value="977">Nepal (+977)</option>
                            <option data-countryCode="NL" value="31">Netherlands (+31)</option>
                            <option data-countryCode="NC" value="687">New Caledonia (+687)</option>
                            <option data-countryCode="NZ" value="64">New Zealand (+64)</option>
                            <option data-countryCode="NI" value="505">Nicaragua (+505)</option>
                            <option data-countryCode="NE" value="227">Niger (+227)</option>
                            <option data-countryCode="NG" value="234">Nigeria (+234)</option>
                            <option data-countryCode="NU" value="683">Niue (+683)</option>
                            <option data-countryCode="NF" value="672">Norfolk Islands (+672)</option>
                            <option data-countryCode="NP" value="670">Northern Marianas (+670)</option>
                            <option data-countryCode="NO" value="47">Norway (+47)</option>
                            <option data-countryCode="OM" value="968">Oman (+968)</option>
                            <option data-countryCode="PW" value="680">Palau (+680)</option>
                            <option data-countryCode="PA" value="507">Panama (+507)</option>
                            <option data-countryCode="PG" value="675">Papua New Guinea (+675)</option>
                            <option data-countryCode="PY" value="595">Paraguay (+595)</option>
                            <option data-countryCode="PE" value="51">Peru (+51)</option>
                            <option data-countryCode="PH" value="63">Philippines (+63)</option>
                            <option data-countryCode="PL" value="48">Poland (+48)</option>
                            <option data-countryCode="PT" value="351">Portugal (+351)</option>
                            <option data-countryCode="PR" value="1787">Puerto Rico (+1787)</option>
                            <option data-countryCode="QA" value="974">Qatar (+974)</option>
                            <option data-countryCode="RE" value="262">Reunion (+262)</option>
                            <option data-countryCode="RO" value="40">Romania (+40)</option>
                            <option data-countryCode="RU" value="7">Russia (+7)</option>
                            <option data-countryCode="RW" value="250">Rwanda (+250)</option>
                            <option data-countryCode="SM" value="378">San Marino (+378)</option>
                            <option data-countryCode="ST" value="239">Sao Tome &amp; Principe (+239)</option>
                            <option data-countryCode="SA" value="966">Saudi Arabia (+966)</option>
                            <option data-countryCode="SN" value="221">Senegal (+221)</option>
                            <option data-countryCode="CS" value="381">Serbia (+381)</option>
                            <option data-countryCode="SC" value="248">Seychelles (+248)</option>
                            <option data-countryCode="SL" value="232">Sierra Leone (+232)</option>
                            <option data-countryCode="SG" value="65">Singapore (+65)</option>
                            <option data-countryCode="SK" value="421">Slovak Republic (+421)</option>
                            <option data-countryCode="SI" value="386">Slovenia (+386)</option>
                            <option data-countryCode="SB" value="677">Solomon Islands (+677)</option>
                            <option data-countryCode="SO" value="252">Somalia (+252)</option>
                            <option data-countryCode="ZA" value="27">South Africa (+27)</option>
                            <option data-countryCode="ES" value="34">Spain (+34)</option>
                            <option data-countryCode="LK" value="94">Sri Lanka (+94)</option>
                            <option data-countryCode="SH" value="290">St. Helena (+290)</option>
                            <option data-countryCode="KN" value="1869">St. Kitts (+1869)</option>
                            <option data-countryCode="SC" value="1758">St. Lucia (+1758)</option>
                            <option data-countryCode="SD" value="249">Sudan (+249)</option>
                            <option data-countryCode="SR" value="597">Suriname (+597)</option>
                            <option data-countryCode="SZ" value="268">Swaziland (+268)</option>
                            <option data-countryCode="SE" value="46">Sweden (+46)</option>
                            <option data-countryCode="CH" value="41">Switzerland (+41)</option>
                            <option data-countryCode="SI" value="963">Syria (+963)</option>
                            <option data-countryCode="TW" value="886">Taiwan (+886)</option>
                            <option data-countryCode="TJ" value="7">Tajikstan (+7)</option>
                            <option data-countryCode="TH" value="66">Thailand (+66)</option>
                            <option data-countryCode="TG" value="228">Togo (+228)</option>
                            <option data-countryCode="TO" value="676">Tonga (+676)</option>
                            <option data-countryCode="TT" value="1868">Trinidad &amp; Tobago (+1868)</option>
                            <option data-countryCode="TN" value="216">Tunisia (+216)</option>
                            <option data-countryCode="TR" value="90">Turkey (+90)</option>
                            <option data-countryCode="TM" value="7">Turkmenistan (+7)</option>
                            <option data-countryCode="TM" value="993">Turkmenistan (+993)</option>
                            <option data-countryCode="TC" value="1649">Turks &amp; Caicos Islands (+1649)</option>
                            <option data-countryCode="TV" value="688">Tuvalu (+688)</option>
                            <option data-countryCode="UG" value="256">Uganda (+256)</option>
                            <!-- <option data-countryCode="GB" value="44">UK (+44)</option> -->
                            <option data-countryCode="UA" value="380">Ukraine (+380)</option>
                            <option data-countryCode="AE" value="971">United Arab Emirates (+971)</option>
                            <option data-countryCode="UY" value="598">Uruguay (+598)</option>
                            <!-- <option data-countryCode="US" value="1">USA (+1)</option> -->
                            <option data-countryCode="UZ" value="7">Uzbekistan (+7)</option>
                            <option data-countryCode="VU" value="678">Vanuatu (+678)</option>
                            <option data-countryCode="VA" value="379">Vatican City (+379)</option>
                            <option data-countryCode="VE" value="58">Venezuela (+58)</option>
                            <option data-countryCode="VN" value="84">Vietnam (+84)</option>
                            <option data-countryCode="VG" value="84">Virgin Islands - British (+1284)</option>
                            <option data-countryCode="VI" value="84">Virgin Islands - US (+1340)</option>
                            <option data-countryCode="WF" value="681">Wallis &amp; Futuna (+681)</option>
                            <option data-countryCode="YE" value="969">Yemen (North)(+969)</option>
                            <option data-countryCode="YE" value="967">Yemen (South)(+967)</option>
                            <option data-countryCode="ZM" value="260">Zambia (+260)</option>
                            <option data-countryCode="ZW" value="263">Zimbabwe (+263)</option>
                        </optgroup>
                    </select>
                </div>

                <div class="col-sm-12">
                    <p>Visitor's Phone No. <span class="text-danger">*</span>:</p>
                    <input name="tel" id="tel" type="text" class="uk-width-1-1" placeholder="Visitor's Phone No." value="">
                    <span id="validationPhone" style="color: red; display: none;">Please insert your Phone No</span>
                </div>

                <div class="col-sm-12">
                    <p>Number of Children <span class="text-danger">*</span>: <small>(0-12 years old only)</small></p>
                    <select name="number_of_children" class="uk-width-1-1" id="select-number-of-children">
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                        <option value="6">6</option>
                    </select>
                    <span id="validationChildren" style="color: red; display: none;">Please select your Number of Children</span>
                </div>
                <br>
                <div class="row aset" id="row-child-birth-year-1">
                    <div class="col-sm-6 col-md-6 col-lg-6 col-6 ">
                        <input name="child_name_1" id="child_name_1" type="text" class="uk-width-1-1" placeholder="Child Name" value="">
                        <span id="validationChildName" style="color: red; display: none;">Please insert your Children's Name</span>
                    </div>

                    <!--<input name="child_birth_year_1" id="child_birth_year_1" type="number" class="uk-width-1-1" placeholder="Birth Year" value="" style="width:48%; float: left; margin-left:4%;">-->
                    <div class="col-sm-6 col-md-6 col-lg-6 col-6 ">
                        <select id="child_birth_year_1" name="child_birth_year_1" class="uk-width-1-1">
							<option value="">Birth Year</option>                        
                            <option value="2021">2021</option>
                            <option value="2020">2020</option>
                            <option value="2019">2019</option>
                            <option value="2018">2018</option>
                            <option value="2017">2017</option>
                            <option value="2016">2016</option>
                            <option value="2015">2015</option>
                            <option value="2014">2014</option>
                            <option value="2013">2013</option>
                            <option value="2012">2012</option>
                            <option value="2011">2011</option>
                            <option value="2010">2010</option>
                            <option value="2009">2009</option>
                            <option value="2008">2008</option>
                            <option value="2007">2007</option>
                            <option value="2006">2006</option>
                            <option value="2005">2005</option>
                            <option value="2004">2004</option>
                            <option value="2003">2003</option>
                            <option value="2002">2002</option>
                            <option value="2001">2001</option>
                            <option value="2000">2000</option>
                            <option value="1999">1999</option>
                            <option value="1998">1998</option>
                            <option value="1997">1997</option>
                            <option value="1996">1996</option>
                            <option value="1995">1995</option>
                            <option value="1994">1994</option>
                            <option value="1993">1993</option>
                            <option value="1992">1992</option>
                            <option value="1991">1991</option>
                            <option value="1990">1990</option>
                            <option value="1989">1989</option>
                            <option value="1988">1988</option>
                            <option value="1987">1987</option>
                            <option value="1986">1986</option>
                            <option value="1985">1985</option>
                            <option value="1984">1984</option>
                            <option value="1983">1983</option>
                            <option value="1982">1982</option>
                            <option value="1981">1981</option>
                            <option value="1980">1980</option>
                            <option value="1979">1979</option>
                            <option value="1978">1978</option>
                            <option value="1977">1977</option>
                            <option value="1976">1976</option>
                            <option value="1975">1975</option>
                            <option value="1974">1974</option>
                            <option value="1973">1973</option>
                            <option value="1972">1972</option>
                            <option value="1971">1971</option>
                            <option value="1970">1970</option>
                        </select>

                        <span id="validationBirth" style="color: red; display: none; ">Please insert your Children's Birth Year</span>

                    </div>
                </div>
                <div class="row aset" id="row-child-birth-year-2">
                    <div class="col-sm-6 col-md-6 col-lg-6 col-6">
                        <input name="child_name_2" id="child_name_2" type="text" class="uk-width-1-1" placeholder="Child Name" value="">
                        <span id="validationChildName2" style="color: red; display: none;">Please insert your Children's Name</span>
                    </div>
                    <!--<input name="child_birth_year_2" id="child_birth_year_2" type="number" class="uk-width-1-1" placeholder="Birth Year" value="" style="width:48%; float: left; margin-left:4%;">-->
                    <div class="col-sm-6 col-md-6 col-lg-6 col-6">
                        <select id="child_birth_year_2" name="child_birth_year_2" class="uk-width-1-1 ">
                            <option value="">Birth Year</option>
                            <option value="2021">2021</option>
                            <option value="2020">2020</option>
                            <option value="2019">2019</option>
                            <option value="2018">2018</option>
                            <option value="2017">2017</option>
                            <option value="2016">2016</option>
                            <option value="2015">2015</option>
                            <option value="2014">2014</option>
                            <option value="2013">2013</option>
                            <option value="2012">2012</option>
                            <option value="2011">2011</option>
                            <option value="2010">2010</option>
                            <option value="2009">2009</option>
                            <option value="2008">2008</option>
                            <option value="2007">2007</option>
                            <option value="2006">2006</option>
                            <option value="2005">2005</option>
                            <option value="2004">2004</option>
                            <option value="2003">2003</option>
                            <option value="2002">2002</option>
                            <option value="2001">2001</option>
                            <option value="2000">2000</option>
                            <option value="1999">1999</option>
                            <option value="1998">1998</option>
                            <option value="1997">1997</option>
                            <option value="1996">1996</option>
                            <option value="1995">1995</option>
                            <option value="1994">1994</option>
                            <option value="1993">1993</option>
                            <option value="1992">1992</option>
                            <option value="1991">1991</option>
                            <option value="1990">1990</option>
                            <option value="1989">1989</option>
                            <option value="1988">1988</option>
                            <option value="1987">1987</option>
                            <option value="1986">1986</option>
                            <option value="1985">1985</option>
                            <option value="1984">1984</option>
                            <option value="1983">1983</option>
                            <option value="1982">1982</option>
                            <option value="1981">1981</option>
                            <option value="1980">1980</option>
                            <option value="1979">1979</option>
                            <option value="1978">1978</option>
                            <option value="1977">1977</option>
                            <option value="1976">1976</option>
                            <option value="1975">1975</option>
                            <option value="1974">1974</option>
                            <option value="1973">1973</option>
                            <option value="1972">1972</option>
                            <option value="1971">1971</option>
                            <option value="1970">1970</option>
                        </select>

                        <span id="validationBirth2" style="color: red; display: none; ">Please insert your Children's Birth Year</span>
                    </div>
                </div>
                <div class="row aset" id="row-child-birth-year-3">
                    <div class="col-sm-6 col-md-6 col-lg-6 col-6">
                        <input name="child_name_3" id="child_name_3" type="text" class="uk-width-1-1" placeholder="Child Name" value="">
                        <span id="validationChildName3" style="color: red; display: none;">Please insert your Children's Name</span>
                        <!--<input name="child_birth_year_3" id="child_birth_year_3" type="number" class="uk-width-1-1" placeholder="Birth Year" value="" style="width:48%; float: left; margin-left:4%;">-->
                    </div>
                    <div class="col-sm-6 col-md-6 col-lg-6 col-6">
                        <select id="child_birth_year_3" name="child_birth_year_3" class="uk-width-1-1 ">
                            <option value="">Birth Year</option>
                            <option value="2021">2021</option>
                            <option value="2020">2020</option>
                            <option value="2019">2019</option>
                            <option value="2018">2018</option>
                            <option value="2017">2017</option>
                            <option value="2016">2016</option>
                            <option value="2015">2015</option>
                            <option value="2014">2014</option>
                            <option value="2013">2013</option>
                            <option value="2012">2012</option>
                            <option value="2011">2011</option>
                            <option value="2010">2010</option>
                            <option value="2009">2009</option>
                            <option value="2008">2008</option>
                            <option value="2007">2007</option>
                            <option value="2006">2006</option>
                            <option value="2005">2005</option>
                            <option value="2004">2004</option>
                            <option value="2003">2003</option>
                            <option value="2002">2002</option>
                            <option value="2001">2001</option>
                            <option value="2000">2000</option>
                            <option value="1999">1999</option>
                            <option value="1998">1998</option>
                            <option value="1997">1997</option>
                            <option value="1996">1996</option>
                            <option value="1995">1995</option>
                            <option value="1994">1994</option>
                            <option value="1993">1993</option>
                            <option value="1992">1992</option>
                            <option value="1991">1991</option>
                            <option value="1990">1990</option>
                            <option value="1989">1989</option>
                            <option value="1988">1988</option>
                            <option value="1987">1987</option>
                            <option value="1986">1986</option>
                            <option value="1985">1985</option>
                            <option value="1984">1984</option>
                            <option value="1983">1983</option>
                            <option value="1982">1982</option>
                            <option value="1981">1981</option>
                            <option value="1980">1980</option>
                            <option value="1979">1979</option>
                            <option value="1978">1978</option>
                            <option value="1977">1977</option>
                            <option value="1976">1976</option>
                            <option value="1975">1975</option>
                            <option value="1974">1974</option>
                            <option value="1973">1973</option>
                            <option value="1972">1972</option>
                            <option value="1971">1971</option>
                            <option value="1970">1970</option>
                        </select>

                        <span id="validationBirth3" style="color: red; display: none;">Please insert your Children's Birth Year</span>
                    </div>
                </div>
                <div class="row aset" id="row-child-birth-year-4">
                    <div class="col-sm-6 col-md-6 col-lg-6 col-6">
                        <input name="child_name_4" id="child_name_4" type="text" class="uk-width-1-1" placeholder="Child Name" value="">
                        <span id="validationChildName4" style="color: red; display: none;">Please insert your Children's Name</span>
                        <!--<input name="child_birth_year_4" id="child_birth_year_4" type="number" class="uk-width-1-1" placeholder="Birth Year" value="" style="width:48%; float: left; margin-left:4%;">-->
                    </div>
                    <div class="col-sm-6 col-md-6 col-lg-6 col-6">
                        <select id="child_birth_year_4" name="child_birth_year_4" class="uk-width-1-1 ">
                            <option value="">Birth Year</option>
                            <option value="2021">2021</option>
                            <option value="2020">2020</option>
                            <option value="2019">2019</option>
                            <option value="2018">2018</option>
                            <option value="2017">2017</option>
                            <option value="2016">2016</option>
                            <option value="2015">2015</option>
                            <option value="2014">2014</option>
                            <option value="2013">2013</option>
                            <option value="2012">2012</option>
                            <option value="2011">2011</option>
                            <option value="2010">2010</option>
                            <option value="2009">2009</option>
                            <option value="2008">2008</option>
                            <option value="2007">2007</option>
                            <option value="2006">2006</option>
                            <option value="2005">2005</option>
                            <option value="2004">2004</option>
                            <option value="2003">2003</option>
                            <option value="2002">2002</option>
                            <option value="2001">2001</option>
                            <option value="2000">2000</option>
                            <option value="1999">1999</option>
                            <option value="1998">1998</option>
                            <option value="1997">1997</option>
                            <option value="1996">1996</option>
                            <option value="1995">1995</option>
                            <option value="1994">1994</option>
                            <option value="1993">1993</option>
                            <option value="1992">1992</option>
                            <option value="1991">1991</option>
                            <option value="1990">1990</option>
                            <option value="1989">1989</option>
                            <option value="1988">1988</option>
                            <option value="1987">1987</option>
                            <option value="1986">1986</option>
                            <option value="1985">1985</option>
                            <option value="1984">1984</option>
                            <option value="1983">1983</option>
                            <option value="1982">1982</option>
                            <option value="1981">1981</option>
                            <option value="1980">1980</option>
                            <option value="1979">1979</option>
                            <option value="1978">1978</option>
                            <option value="1977">1977</option>
                            <option value="1976">1976</option>
                            <option value="1975">1975</option>
                            <option value="1974">1974</option>
                            <option value="1973">1973</option>
                            <option value="1972">1972</option>
                            <option value="1971">1971</option>
                            <option value="1970">1970</option>
                        </select>

                        <span id="validationBirth4" style="color: red; display: none; ">Please insert your Children's Birth Year</span>
                    </div>
                </div>

                <div class="row aset" id="row-child-birth-year-5">
                    <div class="col-sm-6 col-md-6 col-lg-6 col-6">
                        <input name="child_name_5" id="child_name_5" type="text" class="uk-width-1-1" placeholder="Child Name" value="">
                        <span id="validationChildName5" style="color: red; display: none;">Please insert your Children's Name</span>
                        <!--<input name="child_birth_year_5" id="child_birth_year_5" type="number" class="uk-width-1-1" placeholder="Birth Year" value="" style="width:48%; float: left; margin-left:4%;">-->
                    </div>
                    <div class="col-sm-6 col-md-6 col-lg-6 col-6">
                        <select id="child_birth_year_5" name="child_birth_year_5" class="uk-width-1-1 ">
                            <option value="">Birth Year</option>
                            <option value="2021">2021</option>
                            <option value="2020">2020</option>
                            <option value="2019">2019</option>
                            <option value="2018">2018</option>
                            <option value="2017">2017</option>
                            <option value="2016">2016</option>
                            <option value="2015">2015</option>
                            <option value="2014">2014</option>
                            <option value="2013">2013</option>
                            <option value="2012">2012</option>
                            <option value="2011">2011</option>
                            <option value="2010">2010</option>
                            <option value="2009">2009</option>
                            <option value="2008">2008</option>
                            <option value="2007">2007</option>
                            <option value="2006">2006</option>
                            <option value="2005">2005</option>
                            <option value="2004">2004</option>
                            <option value="2003">2003</option>
                            <option value="2002">2002</option>
                            <option value="2001">2001</option>
                            <option value="2000">2000</option>
                            <option value="1999">1999</option>
                            <option value="1998">1998</option>
                            <option value="1997">1997</option>
                            <option value="1996">1996</option>
                            <option value="1995">1995</option>
                            <option value="1994">1994</option>
                            <option value="1993">1993</option>
                            <option value="1992">1992</option>
                            <option value="1991">1991</option>
                            <option value="1990">1990</option>
                            <option value="1989">1989</option>
                            <option value="1988">1988</option>
                            <option value="1987">1987</option>
                            <option value="1986">1986</option>
                            <option value="1985">1985</option>
                            <option value="1984">1984</option>
                            <option value="1983">1983</option>
                            <option value="1982">1982</option>
                            <option value="1981">1981</option>
                            <option value="1980">1980</option>
                            <option value="1979">1979</option>
                            <option value="1978">1978</option>
                            <option value="1977">1977</option>
                            <option value="1976">1976</option>
                            <option value="1975">1975</option>
                            <option value="1974">1974</option>
                            <option value="1973">1973</option>
                            <option value="1972">1972</option>
                            <option value="1971">1971</option>
                            <option value="1970">1970</option>
                        </select>

                        <span id="validationBirth5" style="color: red; display: none; ">Please insert your Children's Birth Year</span>
                    </div>
                </div>

                <div class="row aset" id="row-child-birth-year-6">
                    <div class="col-sm-6 col-md-6 col-lg-6 col-6">
                        <input name="child_name_6" id="child_name_6" type="text" class="uk-width-1-1" placeholder="Child Name" value="">
                        <!--<input name="child_birth_year_6" id="child_birth_year_6" type="number" class="uk-width-1-1" placeholder="Birth Year" value="" style="width:48%; float: left; margin-left:4%;">-->
                        <span id="validationChildName6" style="color: red; display: none;">Please insert your Children's Name</span>
                    </div>
                    <div class="col-sm-6 col-md-6 col-lg-6 col-6">
                        <select id="child_birth_year_6" name="child_birth_year_6" class="uk-width-1-1 ">
                            <option value="">Birth Year</option>
                            <option value="2021">2021</option>
                            <option value="2020">2020</option>
                            <option value="2019">2019</option>
                            <option value="2018">2018</option>
                            <option value="2017">2017</option>
                            <option value="2016">2016</option>
                            <option value="2015">2015</option>
                            <option value="2014">2014</option>
                            <option value="2013">2013</option>
                            <option value="2012">2012</option>
                            <option value="2011">2011</option>
                            <option value="2010">2010</option>
                            <option value="2009">2009</option>
                            <option value="2008">2008</option>
                            <option value="2007">2007</option>
                            <option value="2006">2006</option>
                            <option value="2005">2005</option>
                            <option value="2004">2004</option>
                            <option value="2003">2003</option>
                            <option value="2002">2002</option>
                            <option value="2001">2001</option>
                            <option value="2000">2000</option>
                            <option value="1999">1999</option>
                            <option value="1998">1998</option>
                            <option value="1997">1997</option>
                            <option value="1996">1996</option>
                            <option value="1995">1995</option>
                            <option value="1994">1994</option>
                            <option value="1993">1993</option>
                            <option value="1992">1992</option>
                            <option value="1991">1991</option>
                            <option value="1990">1990</option>
                            <option value="1989">1989</option>
                            <option value="1988">1988</option>
                            <option value="1987">1987</option>
                            <option value="1986">1986</option>
                            <option value="1985">1985</option>
                            <option value="1984">1984</option>
                            <option value="1983">1983</option>
                            <option value="1982">1982</option>
                            <option value="1981">1981</option>
                            <option value="1980">1980</option>
                            <option value="1979">1979</option>
                            <option value="1978">1978</option>
                            <option value="1977">1977</option>
                            <option value="1976">1976</option>
                            <option value="1975">1975</option>
                            <option value="1974">1974</option>
                            <option value="1973">1973</option>
                            <option value="1972">1972</option>
                            <option value="1971">1971</option>
                            <option value="1970">1970</option>
                        </select>

                        <span id="validationBirth6" style="color: red; display: none;">Please insert your Children's Birth Year</span>
                    </div>
                </div>

                <div class="col-sm-12">
                    <p class="email_kk">Email <span class="text-danger">*</span></p>
                    <input name="email" id="email" type="email" class="uk-width-1-1" placeholder="Visitor's Email" value="">
                    <span id="validationEmail" style="color: red; display: none;">Please insert your Email</span>
                </div>

                <div class="col-sm-12">
                    <p>How did you find about Q-dees? <span class="text-danger">*</span></p>
                    <select name="find_out[]"  class="uk-width-1-1 chosen-select" id="find_out" data-placeholder="Please Select" multiple>
                        <option value="" disabled></option>
						
						<?php
						include_once("mysql.php");
						include_once("../admin/functions.php");
						$sql="SELECT * from codes where module='VISITREASON' order by code";
						$result=mysqli_query($connection, $sql);
						while ($row=mysqli_fetch_assoc($result)) {
						?>
						<option value="<?php echo $row["code"]?>" <?php if ($row["code"]==$edit_row["find_out"]) {echo "selected";}?>><?php echo $row["code"]?></option>
						<?php
						}
						?>
                        <!--<option value="Banner/Bunting">Banner/Bunting</option>
                        <option value="Exhibition">Exhibition</option>
                        <option value="Fun Fair">Fun Fair</option>
                        <option value="Flyer">Flyer</option>
                        <option value="Magazine">Magazine</option>
                        <option value="Neighbourhood">Neighbourhood</option>
                        <option value="Newspaper">Newspaper</option>
                        <option value="Radio">Radio</option>
                        <option value="Sibling">Sibling</option>
                        <option value="Social Media">Social Media</option>
                        <option value="Television">Television</option>
                        <option value="Website">Website</option>
                        <option value="Word of Mouth">Word of Mouth</option>-->
                    </select>
                    <small>You may select one or more options for this field.</small><br>
                    <span id="validationFind" style="color: red; display: none;">Please select this field</span>
                </div>
                <small>(<span class="text-danger">*</span>) Mandatory fields</small>
                <script src="lib/sign/js/jquery.signature.js"></script>
                <script type="text/javascript" src="lib/sign/js/jquery.ui.touch-punch.min.js"></script>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.4.2/chosen.jquery.min.js"></script>
                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.4.2/chosen.min.css">
                <script>
                     $('.chosen-select').chosen({
                        search_contains: true
                    }).change(function(obj, result) {
                        // console.debug("changed: %o", arguments);
                        // console.log("selected: " + result.selected);
                    });
                </script>


                <br>
                <hr>
                <div class="col-md-12">
                    <p>The protection of personal data is an important concern to the Q-dees Group of Companies ("Q-dees") and any personal data collected on this form will be treated in accordance with Q-dees Personal Data Protection notice at <a href="http://www.q-dees.com/privacy" target="_blank">http://www.q-dees.com/privacy</a></p>
                    <p>By providing your details above, you hereby consent to Q-dees contacting you to follow up on your interest in our services.</p>
                    <input type="checkbox" name="accept_terms" id="accept-terms" value="1"> Accept <a href="/doc/qdees_general_pdp_notice.pdf" target="_blank">Terms and Conditions&nbsp;<span class="text-danger">*</span></a>
                    <br>
                    <span id="validationCheck" style="color: red; display: none;">Please tick this checkbox</span>
                </div>
                <br>

                <button type="submit" class="uk-button uk-button-primary">Submit</button>
            </div>

        </form>
        </div>
    <?php
        if (!empty($_GET["msg"])) {
            echo "<script>UIkit.notify('" . $_GET["msg"] . "')</script>";
        }
    } else {
        echo "<div class='uk-margin-left uk-margin-top uk-margin-right'><div class='uk-alert uk-alert-danger'>Something is wrong, please try again later.</div></div>";
    }
    ?>

    <script type="text/javascript">
        $('#frmVisitor').submit(function() {
            // alert();
            var error = false;
            if ($.trim($("#name").val()) === "" || $.trim($("#nric").val()) === "" || $.trim($("#tel").val()) === "" || $.trim($("#child_birth_year_1").val()) === "" || $.trim($("#child_name_1").val()) === "" || $.trim($("#email").val()) === "" || $('#find_out').val() == null || $('#find_out').val().toString() === "" || !$('#frmVisitor').find('input[name="accept_terms"]').is(':checked') || $('#select-number-of-children').val() === "") {
                UIkit.notify("Please fill up mandatory fields marked *");

                var name = $('#frmVisitor').find('input[name="name"]').val();
                if (!name) {
                    $('#validationName').show();
                } else {
                    $('#validationName').hide();
                }

                var nric = $('#frmVisitor').find('input[name="nric"]').val();
                if (!nric) {
                    $('#validationIC').show();
                } else {
                    $('#validationIC').hide();
                }

                var tel = $('#frmVisitor').find('input[name="tel"]').val();
                if (!tel) {
                    $('#validationPhone').show();
                } else {
                    $('#validationPhone').hide();
                }

                if ($('#select-number-of-children').val() === "") {
                    $('#validationChildren').show();
                } else {
                    $('#validationChildren').hide();
                }

                var child_birth_year_1 = $('#frmVisitor').find('select[name="child_birth_year_1"]').val();
                if (!child_birth_year_1) {
                    $('#validationBirth').show();
                } else {
                    $('#validationBirth').hide();
                }



                var child_name_1 = $('#frmVisitor').find('input[name="child_name_1"]').val();
                if (child_name_1 == "") {
                    $('#validationChildName').show();
                } else {
                    $('#validationChildName').hide();
                }
				var child_name_2 = $('#frmVisitor').find('input[name="child_name_2"]').val();
			
            if ($('input[name="child_name_2"]').is(":visible")) {
                if (child_name_2 == "") {
                    $('#validationChildName2').show();
                    error = true;
                } else {
                    $('#validationChildName2').hide();
                }
            }
			var child_birth_year_2 = $('#frmVisitor').find('select[name="child_birth_year_2"]').val();
            if ($('input[name="child_name_2"]').is(":visible")) {
                if (!child_birth_year_2) {
                    $('#validationBirth2').show();
                    error = true;
                } else {
                    $('#validationBirth2').hide();
                }
            }
            var child_name_3 = $('#frmVisitor').find('input[name="child_name_3"]').val();
            if ($('input[name="child_name_3"]').is(":visible")) {
                if (child_name_3 == "") {
                    $('#validationChildName3').show();
                    error = true;
                } else {
                    $('#validationChildName3').hide();
                }
            }
			var child_birth_year_3 = $('#frmVisitor').find('select[name="child_birth_year_3"]').val();
            if ($('input[name="child_name_3"]').is(":visible")) {
                if (!child_birth_year_3) {
                    $('#validationBirth3').show();
                    error = true;
                } else {
                    $('#validationBirth3').hide();
                }
            }
            var child_name_4 = $('#frmVisitor').find('input[name="child_name_4"]').val();
            if ($('input[name="child_name_4"]').is(":visible")) {
                if (child_name_4 == "") {
                    $('#validationChildName4').show();
                    error = true;
                } else {
                    $('#validationChildName4').hide();
                }
            }
			var child_birth_year_4 = $('#frmVisitor').find('select[name="child_birth_year_4"]').val();
            if ($('input[name="child_name_4"]').is(":visible")) {
                if (!child_birth_year_4) {
                    $('#validationBirth4').show();
                    error = true;
                } else {
                    $('#validationBirth4').hide();
                }
            }
			
            var child_name_5 = $('#frmVisitor').find('input[name="child_name_5"]').val();
            if ($('input[name="child_name_5"]').is(":visible")) {
                if (child_name_5 == "") {
                    $('#validationChildName5').show();
                    error = true;
                } else {
                    $('#validationChildName5').hide();
                }
            }
			var child_birth_year_5 = $('#frmVisitor').find('select[name="child_birth_year_5"]').val();
            if ($('input[name="child_name_5"]').is(":visible")) {
                if (!child_birth_year_5) {
                    $('#validationBirth5').show();
                    error = true;
                } else {
                    $('#validationBirth5').hide();
                }
            }
            var child_name_6 = $('#frmVisitor').find('input[name="child_name_6"]').val();
            if ($('input[name="child_name_6"]').is(":visible")) {
                if (child_name_6 == "") {
                    $('#validationChildName6').show();
                    error = true;
                } else {
                    $('#validationChildName6').hide();
                }
            }
			var child_birth_year_6 = $('#frmVisitor').find('select[name="child_birth_year_6"]').val();
            if ($('input[name="child_name_6"]').is(":visible")) {
                if (!child_birth_year_6) {
                    $('#validationBirth6').show();
                    error = true;
                } else {
                    $('#validationBirth6').hide();
                }
            }

                var email = $('#frmVisitor').find('input[name="email"]').val();
                if (!email) {
                    $('#validationEmail').show();
                } else {
                    $('#validationEmail').hide();
                }
                //console.log($('#find_out').val());
                if ($('#find_out').val() === null) {
                    $('#validationFind').show();
                }else if ($('#find_out').val()[0] === "" ) { 
                    
                    $('#validationFind').show();
                }
                else {
                    $('#validationFind').hide();
                }

                var accept_terms = $('#frmVisitor').find('input[name="accept_terms"]').is(':checked');

                if (!accept_terms) {
                    $('#validationCheck').show();
                } else {
                    $('#validationCheck').hide();
                }


                error = true;
                return false;
            }
            if (error) {
                return false;
            }
        });
    </script>
</body>

</html>