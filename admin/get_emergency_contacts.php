<?php
session_start();
include_once("../mysql.php");

$mode = $_POST["mode"];
$form_mode = $_POST["form_mode"];
$student_code = $_POST["student_code"];
$sha1_visitor_id=$_POST["visitor"];

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

function getVisitor($sha1_id){
  global $connection;

  $sql="SELECT * from visitor v left join visitor_student vs on v.id=vs.visitor_id where sha1(v.id)='$sha1_id' LIMIT 1";
  //echo $sql;
  $result=mysqli_query($connection, $sql);

  if ($result) {
    $row = mysqli_fetch_assoc($result);
  }else{
    $row = array();
  }

  return $row;
}

?>
<script>
    function doClose() {
        $("#dlgEmergencyContacts").dialog("close");
    }

    function deleteRow(id) {
        $("table#tblContact tr#" + id).remove();
    }

    function addRow(count) {
        var html = "";
        var row_id = "row" + Math.floor(Math.random() * 1000000);


        html = "";
        html = html + "<tr class=\"uk-text-small contact-row\" id='" + row_id + "'>";
        html = html + "   <td>";
        html = html + "      <a><img data-uk-tooltip=\"{pos:top}\" title=\"Remove Row\" onclick=\"deleteRow('" + row_id + "');\" src=\"images/delete.png\"></a>";
        html = html + "   </td>";
        html = html + "   <td>";
        html = html + "      <select class=\"uk-form-small\" name=\"contact_type[]\" required>";
        html = html + "         <option value=\"\">Select</option>";
        html = html + "         <option value=\"Mother\">Mother</option>";
        html = html + "         <option value=\"Father\">Father</option>";
        html = html + "         <option value=\"Guardian\">Guardian</option>";
        html = html + "      </select>";
        html = html + "   </td>";
        html = html + "   <td><input type=\"text\" placeholder=\"Name as per IC/Passport\" name=\"full_name[]\" id=\"full_name_" + row_id + "\" value=\"\" class=\"uk-form-small\" required></td>";
        html = html + "   <td><input type=\"text\" placeholder=\"IC/Passport\" name=\"nric[]\" id=\"nric_"+count+ "\" class=\"uk-form-small\"  ></td>";
        html = html + "   <td><input type=\"email\" placeholder=\"Email\" name=\"email[]\" id=\"email_" + row_id + "\" value=\"\"  class=\"uk-form-small\" ></td>";
        html = html + "   <td>";
        // <input type=\"text\" placeholder=\"+60\" size=\"3\" name=\"mobile_country_code[]\" id=\"mobile_country_code_" + row_id + "\" value=\"\" class=\"uk-form-small\" maxlength=\"3\" required style=\"width:50px\">

    html = html + "      <select name=\"mobile_country_code[]\" id=\"mobile_country_code_" + row_id + "\" class=\"uk-form-small uk-width-1-1\" required>";
    html = html + "    <option data-countryCode=\"MY\" value=\"60\" Selected>Malaysia (+60)</option>\n";
    html = html + "    <optgroup label=\"Other countries\">";

    html = html + "<option data-countryCode=\"DZ\" value=\"213\">Algeria (+213)</option>\n";
    html = html + "        <option data-countryCode=\"AD\" value=\"376\">Andorra (+376)</option>\n";
    html = html + "        <option data-countryCode=\"AO\" value=\"244\">Angola (+244)</option>\n";
    html = html + "        <option data-countryCode=\"AI\" value=\"1264\">Anguilla (+1264)</option>\n";
    html = html + "        <option data-countryCode=\"AG\" value=\"1268\">Antigua & Barbuda (+1268)</option>\n";
    html = html + "        <option data-countryCode=\"AR\" value=\"54\">Argentina (+54)</option>\n";
    html = html + "        <option data-countryCode=\"AM\" value=\"374\">Armenia (+374)</option>\n";
    html = html + "        <option data-countryCode=\"AW\" value=\"297\">Aruba (+297)</option>\n";
    html = html + "        <option data-countryCode=\"AU\" value=\"61\">Australia (+61)</option>\n";
    html = html + "        <option data-countryCode=\"AT\" value=\"43\">Austria (+43)</option>\n";
    html = html + "        <option data-countryCode=\"AZ\" value=\"994\">Azerbaijan (+994)</option>\n";
    html = html + "        <option data-countryCode=\"BS\" value=\"1242\">Bahamas (+1242)</option>\n";
    html = html + "        <option data-countryCode=\"BH\" value=\"973\">Bahrain (+973)</option>\n";
    html = html + "        <option data-countryCode=\"BD\" value=\"880\">Bangladesh (+880)</option>\n";
    html = html + "        <option data-countryCode=\"BB\" value=\"1246\">Barbados (+1246)</option>\n";
    html = html + "        <option data-countryCode=\"BY\" value=\"375\">Belarus (+375)</option>\n";
    html = html + "        <option data-countryCode=\"BE\" value=\"32\">Belgium (+32)</option>\n";
    html = html + "        <option data-countryCode=\"BZ\" value=\"501\">Belize (+501)</option>\n";
    html = html + "        <option data-countryCode=\"BJ\" value=\"229\">Benin (+229)</option>\n";
    html = html + "        <option data-countryCode=\"BM\" value=\"1441\">Bermuda (+1441)</option>\n";
    html = html + "        <option data-countryCode=\"BT\" value=\"975\">Bhutan (+975)</option>\n";
    html = html + "        <option data-countryCode=\"BO\" value=\"591\">Bolivia (+591)</option>\n";
    html = html + "        <option data-countryCode=\"BA\" value=\"387\">Bosnia Herzegovina (+387)</option>\n";
    html = html + "        <option data-countryCode=\"BW\" value=\"267\">Botswana (+267)</option>\n";
    html = html + "        <option data-countryCode=\"BR\" value=\"55\">Brazil (+55)</option>\n";
    html = html + "        <option data-countryCode=\"BN\" value=\"673\">Brunei (+673)</option>\n";
    html = html + "        <option data-countryCode=\"BG\" value=\"359\">Bulgaria (+359)</option>\n";
    html = html + "        <option data-countryCode=\"BF\" value=\"226\">Burkina Faso (+226)</option>\n";
    html = html + "        <option data-countryCode=\"BI\" value=\"257\">Burundi (+257)</option>\n";
    html = html + "        <option data-countryCode=\"KH\" value=\"855\">Cambodia (+855)</option>\n";
    html = html + "        <option data-countryCode=\"CM\" value=\"237\">Cameroon (+237)</option>\n";
    html = html + "        <option data-countryCode=\"CA\" value=\"1\">Canada (+1)</option>\n";
    html = html + "        <option data-countryCode=\"CV\" value=\"238\">Cape Verde Islands (+238)</option>\n";
    html = html + "        <option data-countryCode=\"KY\" value=\"1345\">Cayman Islands (+1345)</option>\n";
    html = html + "        <option data-countryCode=\"CF\" value=\"236\">Central African Republic (+236)</option>\n";
    html = html + "        <option data-countryCode=\"CL\" value=\"56\">Chile (+56)</option>\n";
    html = html + "        <option data-countryCode=\"CN\" value=\"86\">China (+86)</option>\n";
    html = html + "        <option data-countryCode=\"CO\" value=\"57\">Colombia (+57)</option>\n";
    html = html + "        <option data-countryCode=\"KM\" value=\"269\">Comoros (+269)</option>\n";
    html = html + "        <option data-countryCode=\"CG\" value=\"242\">Congo (+242)</option>\n";
    html = html + "        <option data-countryCode=\"CK\" value=\"682\">Cook Islands (+682)</option>\n";
    html = html + "        <option data-countryCode=\"CR\" value=\"506\">Costa Rica (+506)</option>\n";
    html = html + "        <option data-countryCode=\"HR\" value=\"385\">Croatia (+385)</option>\n";
    html = html + "        <option data-countryCode=\"CU\" value=\"53\">Cuba (+53)</option>\n";
    html = html + "        <option data-countryCode=\"CY\" value=\"90392\">Cyprus North (+90392)</option>\n";
    html = html + "        <option data-countryCode=\"CY\" value=\"357\">Cyprus South (+357)</option>\n";
    html = html + "        <option data-countryCode=\"CZ\" value=\"42\">Czech Republic (+42)</option>\n";
    html = html + "        <option data-countryCode=\"DK\" value=\"45\">Denmark (+45)</option>\n";
    html = html + "        <option data-countryCode=\"DJ\" value=\"253\">Djibouti (+253)</option>\n";
    html = html + "        <option data-countryCode=\"DM\" value=\"1809\">Dominica (+1809)</option>\n";
    html = html + "        <option data-countryCode=\"DO\" value=\"1809\">Dominican Republic (+1809)</option>\n";
    html = html + "        <option data-countryCode=\"EC\" value=\"593\">Ecuador (+593)</option>\n";
    html = html + "        <option data-countryCode=\"EG\" value=\"20\">Egypt (+20)</option>\n";
    html = html + "        <option data-countryCode=\"SV\" value=\"503\">El Salvador (+503)</option>\n";
    html = html + "        <option data-countryCode=\"GQ\" value=\"240\">Equatorial Guinea (+240)</option>\n";
    html = html + "        <option data-countryCode=\"ER\" value=\"291\">Eritrea (+291)</option>\n";
    html = html + "        <option data-countryCode=\"EE\" value=\"372\">Estonia (+372)</option>\n";
    html = html + "        <option data-countryCode=\"ET\" value=\"251\">Ethiopia (+251)</option>\n";
    html = html + "        <option data-countryCode=\"FK\" value=\"500\">Falkland Islands (+500)</option>\n";
    html = html + "        <option data-countryCode=\"FO\" value=\"298\">Faroe Islands (+298)</option>\n";
    html = html + "        <option data-countryCode=\"FJ\" value=\"679\">Fiji (+679)</option>\n";
    html = html + "        <option data-countryCode=\"FI\" value=\"358\">Finland (+358)</option>\n";
    html = html + "        <option data-countryCode=\"FR\" value=\"33\">France (+33)</option>\n";
    html = html + "        <option data-countryCode=\"GF\" value=\"594\">French Guiana (+594)</option>\n";
    html = html + "        <option data-countryCode=\"PF\" value=\"689\">French Polynesia (+689)</option>\n";
    html = html + "        <option data-countryCode=\"GA\" value=\"241\">Gabon (+241)</option>\n";
    html = html + "        <option data-countryCode=\"GM\" value=\"220\">Gambia (+220)</option>\n";
    html = html + "        <option data-countryCode=\"GE\" value=\"7880\">Georgia (+7880)</option>\n";
    html = html + "        <option data-countryCode=\"DE\" value=\"49\">Germany (+49)</option>\n";
    html = html + "        <option data-countryCode=\"GH\" value=\"233\">Ghana (+233)</option>\n";
    html = html + "        <option data-countryCode=\"GI\" value=\"350\">Gibraltar (+350)</option>\n";
    html = html + "        <option data-countryCode=\"GR\" value=\"30\">Greece (+30)</option>\n";
    html = html + "        <option data-countryCode=\"GL\" value=\"299\">Greenland (+299)</option>\n";
    html = html + "        <option data-countryCode=\"GD\" value=\"1473\">Grenada (+1473)</option>\n";
    html = html + "        <option data-countryCode=\"GP\" value=\"590\">Guadeloupe (+590)</option>\n";
    html = html + "        <option data-countryCode=\"GU\" value=\"671\">Guam (+671)</option>\n";
    html = html + "        <option data-countryCode=\"GT\" value=\"502\">Guatemala (+502)</option>\n";
    html = html + "        <option data-countryCode=\"GN\" value=\"224\">Guinea (+224)</option>\n";
    html = html + "        <option data-countryCode=\"GW\" value=\"245\">Guinea - Bissau (+245)</option>\n";
    html = html + "        <option data-countryCode=\"GY\" value=\"592\">Guyana (+592)</option>\n";
    html = html + "        <option data-countryCode=\"HT\" value=\"509\">Haiti (+509)</option>\n";
    html = html + "        <option data-countryCode=\"HN\" value=\"504\">Honduras (+504)</option>\n";
    html = html + "        <option data-countryCode=\"HK\" value=\"852\">Hong Kong (+852)</option>\n";
    html = html + "        <option data-countryCode=\"HU\" value=\"36\">Hungary (+36)</option>\n";
    html = html + "        <option data-countryCode=\"IS\" value=\"354\">Iceland (+354)</option>\n";
    html = html + "        <option data-countryCode=\"IN\" value=\"91\">India (+91)</option>\n";
    html = html + "        <option data-countryCode=\"ID\" value=\"62\">Indonesia (+62)</option>\n";
    html = html + "        <option data-countryCode=\"IR\" value=\"98\">Iran (+98)</option>\n";
    html = html + "        <option data-countryCode=\"IQ\" value=\"964\">Iraq (+964)</option>\n";
    html = html + "        <option data-countryCode=\"IE\" value=\"353\">Ireland (+353)</option>\n";
    html = html + "        <option data-countryCode=\"IL\" value=\"972\">Israel (+972)</option>\n";
    html = html + "        <option data-countryCode=\"IT\" value=\"39\">Italy (+39)</option>\n";
    html = html + "        <option data-countryCode=\"JM\" value=\"1876\">Jamaica (+1876)</option>\n";
    html = html + "        <option data-countryCode=\"JP\" value=\"81\">Japan (+81)</option>\n";
    html = html + "        <option data-countryCode=\"JO\" value=\"962\">Jordan (+962)</option>\n";
    html = html + "        <option data-countryCode=\"KZ\" value=\"7\">Kazakhstan (+7)</option>\n";
    html = html + "        <option data-countryCode=\"KE\" value=\"254\">Kenya (+254)</option>\n";
    html = html + "        <option data-countryCode=\"KI\" value=\"686\">Kiribati (+686)</option>\n";
    html = html + "        <option data-countryCode=\"KP\" value=\"850\">Korea North (+850)</option>\n";
    html = html + "        <option data-countryCode=\"KR\" value=\"82\">Korea South (+82)</option>\n";
    html = html + "        <option data-countryCode=\"KW\" value=\"965\">Kuwait (+965)</option>\n";
    html = html + "        <option data-countryCode=\"KG\" value=\"996\">Kyrgyzstan (+996)</option>\n";
    html = html + "        <option data-countryCode=\"LA\" value=\"856\">Laos (+856)</option>\n";
    html = html + "        <option data-countryCode=\"LV\" value=\"371\">Latvia (+371)</option>\n";
    html = html + "        <option data-countryCode=\"LB\" value=\"961\">Lebanon (+961)</option>\n";
    html = html + "        <option data-countryCode=\"LS\" value=\"266\">Lesotho (+266)</option>\n";
    html = html + "        <option data-countryCode=\"LR\" value=\"231\">Liberia (+231)</option>\n";
    html = html + "        <option data-countryCode=\"LY\" value=\"218\">Libya (+218)</option>\n";
    html = html + "        <option data-countryCode=\"LI\" value=\"417\">Liechtenstein (+417)</option>\n";
    html = html + "        <option data-countryCode=\"LT\" value=\"370\">Lithuania (+370)</option>\n";
    html = html + "        <option data-countryCode=\"LU\" value=\"352\">Luxembourg (+352)</option>\n";
    html = html + "        <option data-countryCode=\"MO\" value=\"853\">Macao (+853)</option>\n";
    html = html + "        <option data-countryCode=\"MK\" value=\"389\">Macedonia (+389)</option>\n";
    html = html + "        <option data-countryCode=\"MG\" value=\"261\">Madagascar (+261)</option>\n";
    html = html + "        <option data-countryCode=\"MW\" value=\"265\">Malawi (+265)</option>\n";
    html = html + "        <option data-countryCode=\"MY\" value=\"60\">Malaysia (+60)</option>\n";
    html = html + "        <option data-countryCode=\"MV\" value=\"960\">Maldives (+960)</option>\n";
    html = html + "        <option data-countryCode=\"ML\" value=\"223\">Mali (+223)</option>\n";
    html = html + "        <option data-countryCode=\"MT\" value=\"356\">Malta (+356)</option>\n";
    html = html + "        <option data-countryCode=\"MH\" value=\"692\">Marshall Islands (+692)</option>\n";
    html = html + "        <option data-countryCode=\"MQ\" value=\"596\">Martinique (+596)</option>\n";
    html = html + "        <option data-countryCode=\"MR\" value=\"222\">Mauritania (+222)</option>\n";
    html = html + "        <option data-countryCode=\"YT\" value=\"269\">Mayotte (+269)</option>\n";
    html = html + "        <option data-countryCode=\"MX\" value=\"52\">Mexico (+52)</option>\n";
    html = html + "        <option data-countryCode=\"FM\" value=\"691\">Micronesia (+691)</option>\n";
    html = html + "        <option data-countryCode=\"MD\" value=\"373\">Moldova (+373)</option>\n";
    html = html + "        <option data-countryCode=\"MC\" value=\"377\">Monaco (+377)</option>\n";
    html = html + "        <option data-countryCode=\"MN\" value=\"976\">Mongolia (+976)</option>\n";
    html = html + "        <option data-countryCode=\"MS\" value=\"1664\">Montserrat (+1664)</option>\n";
    html = html + "        <option data-countryCode=\"MA\" value=\"212\">Morocco (+212)</option>\n";
    html = html + "        <option data-countryCode=\"MZ\" value=\"258\">Mozambique (+258)</option>\n";
    html = html + "        <option data-countryCode=\"MN\" value=\"95\">Myanmar (+95)</option>\n";
    html = html + "        <option data-countryCode=\"NA\" value=\"264\">Namibia (+264)</option>\n";
    html = html + "        <option data-countryCode=\"NR\" value=\"674\">Nauru (+674)</option>\n";
    html = html + "        <option data-countryCode=\"NP\" value=\"977\">Nepal (+977)</option>\n";
    html = html + "        <option data-countryCode=\"NL\" value=\"31\">Netherlands (+31)</option>\n";
    html = html + "        <option data-countryCode=\"NC\" value=\"687\">New Caledonia (+687)</option>\n";
    html = html + "        <option data-countryCode=\"NZ\" value=\"64\">New Zealand (+64)</option>\n";
    html = html + "        <option data-countryCode=\"NI\" value=\"505\">Nicaragua (+505)</option>\n";
    html = html + "        <option data-countryCode=\"NE\" value=\"227\">Niger (+227)</option>\n";
    html = html + "        <option data-countryCode=\"NG\" value=\"234\">Nigeria (+234)</option>\n";
    html = html + "        <option data-countryCode=\"NU\" value=\"683\">Niue (+683)</option>\n";
    html = html + "        <option data-countryCode=\"NF\" value=\"672\">Norfolk Islands (+672)</option>\n";
    html = html + "        <option data-countryCode=\"NP\" value=\"670\">Northern Marianas (+670)</option>\n";
    html = html + "        <option data-countryCode=\"NO\" value=\"47\">Norway (+47)</option>\n";
    html = html + "        <option data-countryCode=\"OM\" value=\"968\">Oman (+968)</option>\n";
    html = html + "        <option data-countryCode=\"PW\" value=\"680\">Palau (+680)</option>\n";
    html = html + "        <option data-countryCode=\"PA\" value=\"507\">Panama (+507)</option>\n";
    html = html + "        <option data-countryCode=\"PG\" value=\"675\">Papua New Guinea (+675)</option>\n";
    html = html + "        <option data-countryCode=\"PY\" value=\"595\">Paraguay (+595)</option>\n";
    html = html + "        <option data-countryCode=\"PE\" value=\"51\">Peru (+51)</option>\n";
    html = html + "        <option data-countryCode=\"PH\" value=\"63\">Philippines (+63)</option>\n";
    html = html + "        <option data-countryCode=\"PL\" value=\"48\">Poland (+48)</option>\n";
    html = html + "        <option data-countryCode=\"PT\" value=\"351\">Portugal (+351)</option>\n";
    html = html + "        <option data-countryCode=\"PR\" value=\"1787\">Puerto Rico (+1787)</option>\n";
    html = html + "        <option data-countryCode=\"QA\" value=\"974\">Qatar (+974)</option>\n";
    html = html + "        <option data-countryCode=\"RE\" value=\"262\">Reunion (+262)</option>\n";
    html = html + "        <option data-countryCode=\"RO\" value=\"40\">Romania (+40)</option>\n";
    html = html + "        <option data-countryCode=\"RU\" value=\"7\">Russia (+7)</option>\n";
    html = html + "        <option data-countryCode=\"RW\" value=\"250\">Rwanda (+250)</option>\n";
    html = html + "        <option data-countryCode=\"SM\" value=\"378\">San Marino (+378)</option>\n";
    html = html + "        <option data-countryCode=\"ST\" value=\"239\">Sao Tome & Principe (+239)</option>\n";
    html = html + "        <option data-countryCode=\"SA\" value=\"966\">Saudi Arabia (+966)</option>\n";
    html = html + "        <option data-countryCode=\"SN\" value=\"221\">Senegal (+221)</option>\n";
    html = html + "        <option data-countryCode=\"CS\" value=\"381\">Serbia (+381)</option>\n";
    html = html + "        <option data-countryCode=\"SC\" value=\"248\">Seychelles (+248)</option>\n";
    html = html + "        <option data-countryCode=\"SL\" value=\"232\">Sierra Leone (+232)</option>\n";
    html = html + "        <option data-countryCode=\"SG\" value=\"65\">Singapore (+65)</option>\n";
    html = html + "        <option data-countryCode=\"SK\" value=\"421\">Slovak Republic (+421)</option>\n";
    html = html + "        <option data-countryCode=\"SI\" value=\"386\">Slovenia (+386)</option>\n";
    html = html + "        <option data-countryCode=\"SB\" value=\"677\">Solomon Islands (+677)</option>\n";
    html = html + "        <option data-countryCode=\"SO\" value=\"252\">Somalia (+252)</option>\n";
    html = html + "        <option data-countryCode=\"ZA\" value=\"27\">South Africa (+27)</option>\n";
    html = html + "        <option data-countryCode=\"ES\" value=\"34\">Spain (+34)</option>\n";
    html = html + "        <option data-countryCode=\"LK\" value=\"94\">Sri Lanka (+94)</option>\n";
    html = html + "        <option data-countryCode=\"SH\" value=\"290\">St. Helena (+290)</option>\n";
    html = html + "        <option data-countryCode=\"KN\" value=\"1869\">St. Kitts (+1869)</option>\n";
    html = html + "        <option data-countryCode=\"SC\" value=\"1758\">St. Lucia (+1758)</option>\n";
    html = html + "        <option data-countryCode=\"SD\" value=\"249\">Sudan (+249)</option>\n";
    html = html + "        <option data-countryCode=\"SR\" value=\"597\">Suriname (+597)</option>\n";
    html = html + "        <option data-countryCode=\"SZ\" value=\"268\">Swaziland (+268)</option>\n";
    html = html + "        <option data-countryCode=\"SE\" value=\"46\">Sweden (+46)</option>\n";
    html = html + "        <option data-countryCode=\"CH\" value=\"41\">Switzerland (+41)</option>\n";
    html = html + "        <option data-countryCode=\"SI\" value=\"963\">Syria (+963)</option>\n";
    html = html + "        <option data-countryCode=\"TW\" value=\"886\">Taiwan (+886)</option>\n";
    html = html + "        <option data-countryCode=\"TJ\" value=\"7\">Tajikstan (+7)</option>\n";
    html = html + "        <option data-countryCode=\"TH\" value=\"66\">Thailand (+66)</option>\n";
    html = html + "        <option data-countryCode=\"TG\" value=\"228\">Togo (+228)</option>\n";
    html = html + "        <option data-countryCode=\"TO\" value=\"676\">Tonga (+676)</option>\n";
    html = html + "        <option data-countryCode=\"TT\" value=\"1868\">Trinidad & Tobago (+1868)</option>\n";
    html = html + "        <option data-countryCode=\"TN\" value=\"216\">Tunisia (+216)</option>\n";
    html = html + "        <option data-countryCode=\"TR\" value=\"90\">Turkey (+90)</option>\n";
    html = html + "        <option data-countryCode=\"TM\" value=\"7\">Turkmenistan (+7)</option>\n";
    html = html + "        <option data-countryCode=\"TM\" value=\"993\">Turkmenistan (+993)</option>\n";
    html = html + "        <option data-countryCode=\"TC\" value=\"1649\">Turks & Caicos Islands (+1649)</option>\n";
    html = html + "        <option data-countryCode=\"TV\" value=\"688\">Tuvalu (+688)</option>\n";
    html = html + "        <option data-countryCode=\"UG\" value=\"256\">Uganda (+256)</option>\n";
    html = html + "        <!-- <option data-countryCode=\"GB\" value=\"44\">UK (+44)</option> -->\n";
    html = html + "        <option data-countryCode=\"UA\" value=\"380\">Ukraine (+380)</option>\n";
    html = html + "        <option data-countryCode=\"AE\" value=\"971\">United Arab Emirates (+971)</option>\n";
    html = html + "        <option data-countryCode=\"UY\" value=\"598\">Uruguay (+598)</option>\n";
    html = html + "        <!-- <option data-countryCode=\"US\" value=\"1\">USA (+1)</option> -->\n";
    html = html + "        <option data-countryCode=\"UZ\" value=\"7\">Uzbekistan (+7)</option>\n";
    html = html + "        <option data-countryCode=\"VU\" value=\"678\">Vanuatu (+678)</option>\n";
    html = html + "        <option data-countryCode=\"VA\" value=\"379\">Vatican City (+379)</option>\n";
    html = html + "        <option data-countryCode=\"VE\" value=\"58\">Venezuela (+58)</option>\n";
    html = html + "        <option data-countryCode=\"VN\" value=\"84\">Vietnam (+84)</option>\n";
    html = html + "        <option data-countryCode=\"VG\" value=\"84\">Virgin Islands - British (+1284)</option>\n";
    html = html + "        <option data-countryCode=\"VI\" value=\"84\">Virgin Islands - US (+1340)</option>\n";
    html = html + "        <option data-countryCode=\"WF\" value=\"681\">Wallis & Futuna (+681)</option>\n";
    html = html + "        <option data-countryCode=\"YE\" value=\"969\">Yemen (North)(+969)</option>\n";
    html = html + "        <option data-countryCode=\"YE\" value=\"967\">Yemen (South)(+967)</option>\n";
    html = html + "        <option data-countryCode=\"ZM\" value=\"260\">Zambia (+260)</option>\n";
    html = html + "        <option data-countryCode=\"ZW\" value=\"263\">Zimbabwe (+263)</option>\n";
    html = html + "    </optgroup>";

        html = html + "      </select>";


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


        html = html + "       <select name=\"education_level[]\" id=\"education_" + row_id + "\" class=\"uk-form-small uk-width-1-1\" required>";
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
        html = html + "   <td><input type=\"text\" placeholder=\"Vehicle No.\" size=\"10\" name=\"vehicle_no[]\" id=\"vehicle_no_" + row_id + "\" value=\"\" class=\"uk-form-small\" ></td>";
        html = html + "   <td><input type=\"text\" placeholder=\"Remarks\" name=\"remarks[]\" id=\"remarks_" + row_id + "\" value=\"\" class=\"uk-form-small\"></td>";
        
        html = html + "</tr>";

        $("#tblContact").append(html);
    }

    function doChange(id) {
        var can_pick_up = $("#can_pick_up_" + id).val();

        if (can_pick_up == "1") {
            $("#vehicle_no_" + id).prop("readonly", false);
            // $("#vehicle_no_" + id).prop('required',true);
        } else {
            $("#vehicle_no_" + id).val("");
            $("#vehicle_no_" + id).prop("readonly", true);
            $("#vehicle_no_" + id).prop('required',false);
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
				console.log(response);
				if (response === 'Contact saved') {
					 UIkit.notify('Contact saved');
					var primaryContactName = $('.contact-row:first input[name="full_name[]"]:first').val();
                    //console.log(primaryContactName);
					$('#primary-contact1').text(primaryContactName);
					$('#primary-contact2').text(primaryContactName);
					$("#dlgEmergencyContacts").dialog("close");

				}else{
					 UIkit.notify('Parent IC/Passport already exists');
					//$('.contact-row:first input[name="full_name[]"]').val(response);
					//$('#primary-contact').text(response);
					//var primaryContactName = $('.contact-row:first input[name="full_name[]"]').val();
                    var primaryContactName = response;
					$('#primary-contact1').text(primaryContactName);
					$('#primary-contact2').text(primaryContactName);
					$("#dlgEmergencyContacts").dialog("close");
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

            var nric = $("input[name='nric[]']").map(function(){
                return $(this).val();
            }).get();

            var contact_type = $('select[name="contact_type[]"] option:selected').map(function(){
                return $(this).val();
            }).get();

            var full_name = $("input[name='full_name[]']").map(function(){
                return $(this).val();
            }).get();

            var email = $("input[name='email[]']").map(function(){
                return $(this).val();
            }).get();

            var mobile_country_code = $('select[name="mobile_country_code[]"] option:selected').map(function(){
                return $(this).val();
            }).get();

            var mobile = $("input[name='mobile[]']").map(function(){
                return $(this).val();
            }).get();

            var occupation = $('select[name="occupation[]"] option:selected').map(function(){
                return $(this).val();
            }).get();

            var education_level = $('select[name="education_level[]"] option:selected').map(function(){
                return $(this).val();
            }).get();

            var can_pick_up = $('select[name="can_pick_up[]"] option:selected').map(function(){
                return $(this).val();
            }).get();
            

            var formInvalid = false;
  $('#frmSaveContact input').each(function() {
    if ($(this).val() === '') {
      formInvalid = true;
    }
  });

  // alert(contact_type );

  if ( contact_type =="" || full_name =="" || mobile_country_code =="" || mobile =="" || occupation =="" || education_level =="" || can_pick_up =="" ){
      e.preventDefault(); 
    alert('Form are empty. Please fill up all fields');
} else{
  


            var values = $("input[name='nric[]']").map(function(){
                return $(this).val();
            }).get();

            var valuess = $("input[name='full_name[]']").map(function(){
                return $(this).val();
            }).get();

            
            function myFunc(arr){

        var x= arr[0];
        return arr.every(function(item){
            return item=== x;
        });
    };

        var validate = myFunc(values);
        var validate_name = myFunc(valuess);

        if (values.length > 1 || valuess.length > 1) {
            if (validate == true) {
        alert('Same IC/Passport number as primary contact, please use a different contact.'); 
        e.preventDefault();      
    }else if(validate_name == true){
        alert('Same Name as per IC/Passport'); 
        e.preventDefault();   
    }else{
        e.preventDefault();
        doSave();
    }
}else{
    e.preventDefault();
          doSave();
}
   
    
            
        }  
          
        });

        var count = 0;

  $("#addrowBtn").click(function() {
    count++;
    // alert(count);
    addRow(count);
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
	
	
	@media screen and (max-width:992px){

			
	::-webkit-scrollbar {
		-webkit-appearance: none;
	}

	::-webkit-scrollbar:vertical {
		width: 12px;
	}

	::-webkit-scrollbar:horizontal {
		height: 12px;
	}

	::-webkit-scrollbar-thumb {
		background-color: rgba(0, 0, 0, .5);
		border-radius: 10px;
		border: 2px solid #ffffff;
	}

	::-webkit-scrollbar-track {
		border-radius: 10px;  
		background-color: #ffffff; 
	}}
</style>
<form class="uk-form" name="frmSaveContact" id="frmSaveContact" method="post" style="width: 100%">
    <input type="hidden" name="mode" value="<?php echo $mode ?>">
    <input type="hidden" name="form_mode" value="<?php echo $form_mode ?>">
    <input type="hidden" name="student_code" id="student_code" value="<?php echo $student_code ?>">
    <div class="responsive-table">
        <table class="uk-table uk-form-small" id="tblContact">
            <tr class="uk-text-small uk-text-bold">
                <td>
                    <a style="display: inline-block; width:20px; height:20px">
                        <img data-uk-tooltip="{pos:top}" title="Add Contact" id="addrowBtn"  src="images/add.png">
                    </a>
                </td>
                <td>Contact Type<span class="required">*</span></td>
                <td>Name as per IC/Passport<span class="required">*</span></td>
                <td>IC/Passport<span class="required"></span></td>
                <td>Primary Email</td>
                <td>Mobile<span class="required">*</span></td>
                <td>Occupation<span class="required">*</span></td>
                <td>Education Level<span class="required">*</span></td>
                <td>Authorised To Pick Up<span class="required">*</span></td>
                <td>Vehicle No.<span class=""></span></td>
                <td>Remarks</td>
                
            </tr>
            <?php
            if ($_SESSION["isLogin"] == 1 && $form_mode!='qr') {
                $sql = "SELECT * from student_emergency_contacts where student_code='$student_code' order by id ASC";
            } else {
                $sql = "SELECT * from tmp_student_emergency_contacts where student_code='$student_code' order by id ASC";
            }
           
            $result = mysqli_query($connection, $sql);
            $num_row = mysqli_num_rows($result);
            while ($row = mysqli_fetch_assoc($result)) {
                $row_id = generateRandomString(8);
                ?>
                <tr class="uk-text-small contact-row" id="<?php echo $row_id ?>">

                    <td>
                        <a style="display: inline-block; width:20px; height:20px"><img data-uk-tooltip="{pos:top}"
                         title="Remove Row"onclick="deleteRow('<?php echo $row_id ?>');"
                        src="images/delete.png"></a>
                 </td>

                    <td>
                        <select class="uk-form-small" name="contact_type[]" required>
                            <option value="">Select</option>
                            <option value="Mother" <?php if ($row['contact_type'] == 'Mother') {
                                echo "Selected";
                            } ?>>Mother
                            </option>
                            <option value="Father" <?php if ($row['contact_type'] == 'Father') {
                                echo "Selected";
                            } ?>>Father
                            </option>
                            <option value="Guardian" <?php if ($row['contact_type'] == 'Guardian') {
                                echo "Selected";
                            } ?>>Guardian
                            </option>
                        </select>
                    </td>
                    <td><input type="text" placeholder="" name="full_name[]" id="full_name"
                               value="<?php echo $row['full_name'] ?>" class="uk-form-small" required></td>
                    <td><input type="text" placeholder="" name="nric[]" id="nric" value="<?php echo $row['nric'] ?>"
                               class="uk-form-small"  ></td>
                    <td><input type="email" placeholder="Email" name="email[]" id="email"
                               value="<?php echo $row['email'] ?>" class="uk-form-small" ></td>
                    <td>
                       <!--  <input type="text" placeholder="e.g +60" name="mobile_country_code[]" id="mobile_country"
                               value="<?php echo $row['mobile_country_code'] ?>" class="uk-form-small" maxlength="3"
                               required style="width: 50px"> -->
                               <?php $str = $row['mobile_country_code'];
                               // preg_replace('/[^0-9]/', '', $str);
                               // print_r( preg_replace('/[^0-9]/', '', $str)); ?>
                               <select class="uk-width-1-1" name="mobile_country_code[]" id="mobile_country">
    <option data-countryCode="MY" value="<?php echo preg_replace('/[^0-9]/', '', $str) ?>" >+<?php echo $row['mobile_country_code'] ?></option>
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
                                    echo "Selected";
                                } ?>><?php echo $orow['code'] ?></option>
                                <?php
                            }
                            ?>
                        </select>
                        <!-- <input type="text" placeholder="Occupation" name="occupation[]" id="occupation" value="<?php echo $row['occupation'] ?>" class="uk-form-small" required> -->
                    </td>
                    <td>
                        <select name="education_level[]" id="education" class="uk-form-small uk-width-1-1" required>
                            <option value="">Select</option>
                            <?php
                            $sql = "SELECT code from codes where module='EDUCATION' order by code";
                            $oresult = mysqli_query($connection, $sql);
                            while ($orow = mysqli_fetch_assoc($oresult)) {
                                ?>
                                <option value="<?php echo $orow['code'] ?>" <?php if ($orow['code'] == $row['education_level']) {
                                    echo "Selected";
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
                                echo "Selected";
                            } ?>>Yes
                            </option>
                            <option value="0" <?php if ($row["can_pick_up"] == 0) {
                                echo "Selected";
                            } ?>>No
                            </option>
                        </select>
                    </td>
                    <td><input type="text" placeholder="Vehicle No." name="vehicle_no[]" size="10"
                               id="vehicle_no_<?php echo $row_id ?>" value="<?php echo $row['vehicle_no'] ?>"
                               class="uk-form-small" ></td>
                    <td><input type="text" placeholder="Remarks" name="remarks[]" id="remarks_<?php echo $row_id ?>"
                               value="<?php echo $row['remarks'] ?>" class="uk-form-small"></td>
                              <!--  <td>
                        <a style="display: inline-block; width:20px; height:20px"><img data-uk-tooltip="{pos:top}"
                         title="Remove Row"onclick="deleteRow('<?php echo $row_id ?>');"
                        src="images/delete.png"></a>
                 </td> -->
                    
                </tr>
                <?php
            }
            ?>
            <?php
            if ($num_row == 0) {
                ?>
                <script>
                    addRow();
            <?php 
                $visitor = getVisitor($sha1_visitor_id);
                if($visitor){
                 ?>
                    setTimeout(function(){ 
                        $('input[name="full_name[]"]').val('<?php echo $visitor["name"]?>');
                        $('input[name="nric[]"]').val('<?php echo $visitor["nric"]?>');
                        $('input[name="email[]"]').val('<?php echo $visitor["email"]?>');
                        $('input[name="mobile[]"]').val('<?php echo $visitor["tel"]?>');
                        $('input[name="mobile_country_code[]"]').val('<?php echo $visitor["country_code"]?>');
                    }, 500);
                <?php
                }
                ?>
                </script>
            <?php
            }
            ?>
        </table>
    </div>
    <p style="color: red; font-weight: bold">NOTE: The first contact above is the Primary Contact.</p>
    <button type="submit" class="uk-button uk-button-small uk-button-primary">Save</button>
    <a onclick="doClose();" class="uk-button uk-button-small">Close</a>
</form>
