<?php
session_start();
error_reporting(E_ALL);
include_once("../mysql.php");
include_once("functions.php");
foreach ($_GET as $key => $value) {
   $$key = mysqli_real_escape_string($connection, $value); //$centre_code; $month
}

if(isset($session_year))
{
   $year_data = mysqli_fetch_array(mysqli_query($connection,"SELECT MIN(`term_start`) AS `start_date`, MAX(`term_end`) AS `end_date` FROM `schedule_term` WHERE `year` = '".$session_year."' AND `centre_code` = '".$centre_code."' GROUP BY `year`"));

   $year_start_date = $year_data['start_date'];
   $year_end_date = $year_data['end_date'];
}

if($month != '13') {
   $month_end_date = date("Y-m-t", strtotime($year.'-'.$month));
}

$monthList = getMonthList($year_start_date, $year_end_date);

?>
<link rel="stylesheet" href="../lib/node_modules/mdi/css/materialdesignicons.min.css">
<link rel="stylesheet" href="../lib/node_modules/flag-icon-css/css/flag-icon.min.css">
<link rel="stylesheet" href="../lib/node_modules/font-awesome/css/font-awesome.min.css">
<link rel="stylesheet" href="../css/style.css">

<link rel="stylesheet" type="text/css" href="../lib/uikit/css/uikit.gradient.min6.css">
<link rel="stylesheet" type="text/css" href="../lib/uikit/css/components/autocomplete.gradient.min.css">
<link rel="stylesheet" type="text/css" href="../lib/uikit/css/components/form-advanced.gradient.min.css">
<link rel="stylesheet" type="text/css" href="../lib/uikit/css/components/form-file.gradient.min.css">
<link rel="stylesheet" type="text/css" href="../lib/uikit/css/components/form-select.gradient.min.css">
<link rel="stylesheet" type="text/css" href="../lib/uikit/css/components/placeholder.gradient.min.css">
<link rel="stylesheet" type="text/css" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

<style>

.no-close .ui-dialog-titlebar-close {
  display: none;
}

.boxone, .boxtwo, .boxthree, .boxfour, .boxfive {
    box-shadow: 0px 13px 21px -10px rgba(0, 0, 0, 0.15)!important;
    height: 180px;
    max-height: 180px;
}

.bordered-main {
    width: 2.3rem;
    height: 2.3rem;
    color: white;
    margin-left: 5px;
    background: transparent !important;
    border-radius: 100%;
    border: 2px solid #8b5ae2;
    -webkit-transition: all .1s ease-in;
    -moz-transition: all .1s ease-in;
    -ms-transition: all .1s ease-in;
    -o-transition: all .1s ease-in;
    transition: all .1s ease-in;
}

.text-main-color {color: #8b5ae2!important;}

.nostyle {background: transparent!important; box-shadow: none!important;}

.bordered-main:hover {background-color: #8b5ae2!important;}

.bordered-main:hover a {
    color: white !important;
}

.bordered-main a {
    color: #8b5ae2 !important;
}

.nav-item p, .nav-item div {color: rgba(0,0,0,.7);}

.menu-title {
    color: rgba(0,0,0,.4);
    font-size: 1.15em!important;
   line-height: 1.1 !important;
   font-weight:bold;
}

.nav-item.nav-category {
    background: white!important;
    display: flex;
    justify-content: center;
    font-size: 1rem!important;
   font-weight: 800 ;
}

.nav-item.nav-category span {
   color: black!important;
   padding: 0.7rem 1.875rem !important;
}

.nav-item .nav-link i.fa {color: #f97c71!important;}

.sales-title a {
    font-size: 24px;
    color: teal !important;
}

.content-wrapper{
   font-family: "open sans", sans-serif;
}

ul.nav {
    word-break: break-all;
    -webkit-hyphens: auto;
    -moz-hyphens: auto;
    hyphens: auto;
}

.progress-qd {
    padding: 20px 20px 20px 60px;
    background: #f97c71;
    min-width: 180px;
    position: relative;
    display: inline-block;
    color: white;
}

.progress-qd::after,
.progress-qd::before {
    content: " ";
    position: absolute;
    top: 0;
    right: -34px;
    width: 0;
    height: 0;
    border-top: 32px solid transparent;
    border-bottom: 28px solid transparent;
    border-left: 34px solid #f97c71;
    z-index: 2;
    transition: border-color 0.2s ease;
}

.progress-content {
    margin: .5rem 0;
}

.progress-qd::before {
    right: auto;
    left: 0;
    border-left: 34px solid #fff;
    z-index: 0;
}

.enlist {
    text-align: center;
    position: relative;
    margin-bottom: 1.3rem;
}

.header-bottom-part table td { text-align: center; padding: }

.header-bottom-part table tr:not(:first-child):nth-of-type(even) {
    background: #f5f6FF;
}

.header-bottom-part table tr td {
    padding: 10px 0;
}

.enlist-parent {
    display: flex: !important;
    justify-content: center;
}

.main-box-content {
    background: white;
    box-shadow: 0px 2px 8px 0px rgba(0, 0, 0, 0.15)!important;
    padding: 30px 0px;
    height: 225px;
    border-radius: 5%;
}


.main-box-content p {margin-left: 10px}
/*  red: #f97c71 blue: #3399ff */

.main-box-content .box-head {
    width: 93%;
    padding: 4px 0px;
    color: white;
    position: relative;
    background: #69A5F7;
}
.main-box-content .box-head p{
   font-size: 1.3rem;
}
.main-box-content .box-head::after {
    content: '';
    position: absolute;
    width: 0;
    height: 0;
    border-color: #69a5f7 transparent;
    border-style: solid;
    border-width: 15px 0px 0px 10px;
    right: 0;
    bottom: -13px;
}

.ui-dialog .ui-dialog-content {
    padding: 0px;
}

.box-after-red::after {
    border-color: #f97c71 transparent!important;
}

.box-border-red {border-color: #E26464 !important;box-shadow: inset 0px 0px 0px 2px #FF6E6E !important;}
.background-red {background: #FF6E6E!important;}
.text-color-red {color: #f97c71!important;}

.text-color-blue {color: #3399ff!important;}

.main-box-content .box-head:nth-of-type(even) {
    background: #f97c71!important;
}

.main-box-content:nth-child(odd) .box-content i.fa {
    color: #3399ff!important;
}

.main-box-content:nth-child(even) .box-content p.q-text {
    color: #f97c71!important;
}

.q-text {
    color: #3399ff;
    font-size: 2.5em;
}

.box-content .row, .box-content {
    height: 100%;
    max-width: 85%;
    margin: 0 auto;
}

.main-box-content i {
    font-size: 2.4em!important;
}

.box-head p {
    margin-bottom: 0px!important;
    padding-bottom: 0px!important;
}

.border-bg {
    padding: 2px;
    border-radius: 6%;
    border: 2px solid #6594CD;
    transform: scale(1.3);
    box-shadow: inset 0px 0px 0px 2px #69A5F7;
}

.main-box-content:nth-child(even) .border-bg {
    border-color: #f97c71;
}

@media (max-width: 414px) {
    .mobile-margin-50 {
        margin: 25px 0;
    }

    .main-box-content {height: auto}

}

.student-page {
    padding: 50px 15px;
    background: white;
    border-radius: 35px;
    border: 0px solid grey;
   box-shadow: 0px 0px 6px #80808045;
   font-family: "open Sans", sans-serif;
}

.student-page i {
    font-size: 7em;
    color: #f97c71;
}

.student-page p {
    font-size: 1.8em;
    color: #3399ff;
}

.student-page .student-block{
    width: 250px;
    height: 250px;
    padding: 10px;
    border-radius: 30%;
    box-shadow: 0px 0px 0px 4px #e26464;
    border: 6px solid #FF6E6E;

}

.student-page .student-block p {
    color: grey!important;
   font-weight:bold;
   line-height: 1.2;
   margin-bottom:0px;

}
.student-page .student-block p: hover{
   text-decoration:none;
   }

.uk-width-1-1.myheader {
    border-top-right-radius: 15px;
    border-top-left-radius: 15px;
    padding: 15px 0;
}

.uk-width-1-1.myheader> * {
    margin: 0px!important;
    padding: 0!important;
}

.uk-overflow-container,
form[id^="frm"],
#sctActiveClasses,
#sctBal,
.nice-form{
    background: white!important;
    box-shadow: 0px 13px 21px -10px rgba(0, 0, 0, 0.15)!important;
    padding: 1.5em;
    border-bottom-right-radius: 15px;
    border-bottom-left-radius: 15px;
}


form[id^="frmDelete"], #frmPurchasing, #frmPlaceOrder {background:none!important;box-shadow:none!important;}

.sidebar {
    background: #f1e33f; !important;
    position: fixed;
    height:  100vh;
    min-height: auto!important;
}

.navbar .navbar-menu-wrapper {
    background: #ef5350; !important;
}

.qdees-button {
    padding: 8px 15px;
    background: #fdba0e;
    border-radius: 8px;
    margin-left: 35%;
   color: white !important;
    font-weight: bold;
}

.qdees-button span{
   text-shadow: 0px 2px 2px #00000059;
    letter-spacing: 1px;
}

.uk-button.uk-button-primary {
    background: #fdba0c !important;
   color: white !important;
    padding: .3em 2em;
}

.qdees-main-button {
    padding: 10% 25%;
    border: none;
    border-radius: 8px;
    background: #fdba0c;
    font-size: 1.3em;
    color: white;
    font-weight: 700;
    box-shadow: 0px 13px 21px -10px rgba(0, 0, 0, 0.15)!important;
    cursor: pointer;
    margin: .5em 0;
}

.qdees-product .product-desc {
    background: white;
    padding: 15px;
    box-shadow: 0px 13px 21px -10px rgba(0, 0, 0, 0.15)!important;
}

.qdees-4-element {
    margin-bottom: 30px;
}

.sidebar p, .sidebar h3{
	color:black;
	white-space: normal;
    word-break: break-word;

}

.header-top-part {
    width: 100%;
    padding: 1.5em 0;
    text-align: center;
    text-transform: uppercase!important;
    background: #69a6f7;
    color: white!important;
    border-top-right-radius: 15px;
    border-top-left-radius: 15px;
    margin-bottom: 0px!important;
    margin-top: 2em;
}

.header-top-part > * {margin: 0!important; text-transform: uppercase; font-weight: bold; font-size:24px;}

.header-bottom-part {
    border-bottom-right-radius: 15px;
    border-bottom-left-radius: 15px;
    margin-top: 0px!important;
}

.sidebar .nav .nav-item.nav-profile {
      font-family: "open Sans", sans-serif; }
.sidebar {
      font-family: "open Sans", sans-serif; }
.sidebar .nav .nav-item .nav-link{
   padding:1rem;}
.sidebar .nav .nav-item.nav-category{font-family: "open Sans", sans-serif; }

.page_title{
   font-size: 1.5rem;
   font-weight:bold;
   color:#707070;
   margin-right: 10px;
}

.page_title img {
   width: 40px;
    margin-right: 10px;
    margin-top: -5px;
}

.myheader-text-style{
   text-transform: uppercase;
    font-weight: bold;
}

.myheader {
   background-color:#69a6f7;
 }

#toCopy span {
   font-size: 1.1rem;
    cursor: pointer;
    border-radius: 8px;
    padding: .6em 1.5em;
    background: #FDBA0C;
    color: white;
    font-weight: bold;
    text-shadow: 0px 2px 2px #00000059;
    letter-spacing: 1px;
}
.navbar .navbar-brand-wrapper .navbar-brand img{
   height:50px;
       width: calc(180px - 40px);
}

.q-table tr:first-child {
    background: #6594CD;
    color: white;
    font-size: 1em;
}

.q-table {
    -webkit-box-shadow: 0px 0px 5px 0px rgba(122,122,122,.4);
    -moz-box-shadow: 0px 0px 5px 0px rgba(122,122,122,.4);
    box-shadow: 0px 0px 5px 0px rgba(122,122,122,.4);
}

.full-width-blue {
    width: 100%;
    background: #69a6f7;
    border: none;
    border-radius: 8px;
    color: white;
    font-weight: 300;
}

.full-width-blue:hover {
    background: #69a6f7;
    color: white;
    border: 1px solid rgba(33, 142, 145, 0.4);
}

.q-table tr:first-child td {
    padding: 15px 0;
}

.q-table td {text-align: center!important; width: 8%; border: none;}

.q-table.q-table-light tr:first-child {background: white!important; color: darkgrey;}

@media (max-width: 414px) {
    .uk-form select, .uk-form textarea, .uk-form input:not([type]), .uk-form input[type="text"], .uk-form input[type="password"], .uk-form input[type="datetime"], .uk-form input[type="datetime-local"], .uk-form input[type="date"], .uk-form input[type="month"], .uk-form input[type="time"], .uk-form input[type="week"], .uk-form input[type="number"], .uk-form input[type="email"], .uk-form input[type="url"], .uk-form input[type="search"], .uk-form input[type="tel"], .uk-form input[type="color"] {
        max-width: 60%;
    }

    form[name="frmStudent"] .uk-grid {
        max-width: 82%;
    }
}

i.far.fa-trash-alt {
    text-align: center;
    color: #ff6e6e;
    font-size: 1.4em;
    cursor: pointer;
}

.q-table tr:not(:first-child):nth-of-type(even) {
    background: #f5f6ff;
}

.q-table tr:not(:first-child) {color: grey; font-size: 1em;}


.form-style{
   background:white;
   padding:20px;
}

.product-row-color{background: #F5F6FF;}

.payment-details-table{
   float: right;
   margin: 25px 20px
}
.payment-details-table th, .payment-details-table td {
   padding: 5px 0px 5px 100px;
}
.payment-details-table th{
   text-decoration:underline;
}

.box-style{
   width:250px;
   border-color: #707070a1 !important;
}

a:hover{text-decoration:none !important;}

#student-info h2, #student-info .uk-form{padding:20px;}
#student-info h2{margin-bottom:0px;margin-top:0px;padding-top:10px !important; border-bottom:1px solid #d6d6d6;}
#student-info #AllStudent, #student-info #SelectedStudent {margin-top: 50px; border: 1px solid #d6d6d69c; border-radius:20px;background: white;box-shadow: 0px 13px 21px -10px rgba(0, 0, 0, 0.15); }

.uk-grid > * {padding-right:25px;}

.termsCon h4{color:gray;}

.sidebar .nav .nav-item .nav-link{white-space:nowrap;}

.square-button {padding-bottom:20px;padding-right: 5px;}

.square-button a{
   padding: 40px 2px;
    font-size: 15px;
    background: #ffffff;
}
.box-style-pos{
   width:180px;
   border-color: #707070a1 !important;
}

.ui-dialog .ui-dialog-titlebar {
    background: #69a6f7;
    color: white;
    text-align: center;
    font-size: 24px;
}
.ui-dialog .ui-dialog-title{float:none;}
.blue_button{
   background:#69a6f7; !important;
   color:white!important;
   border:0;
   text-shadow:none;
}
.blue_button:hover{
   background:#69a6f7; !important;
   color:white!important;
   border:0;
   text-shadow:none;
}
.uk-modal-close{
   padding: .3em 2em;
}

.footer{
	background: #fff;
  color: #333;
}


.sidebar .nav .nav-item .nav-link .menu-icon img {
    margin-right: 0.1rem;
    width: 40px;
    max-width: none;
}

.ui-dialog-content .uk-button-small{
	 padding: .3em 2em;
}
.badge-danger {
    border: 1px solid transparent;
    color: #ffffff;
    background-color: rgb(220, 163, 4) !important;
}
.uk-badge-danger {
    background-color: rgb(220, 163, 4) !important;
    background-image: none !important;
    margin-right: 15px;
    margin-left: 5px;
    border-radius: 10rem;
    font-size: .625rem;
    font-weight: initial;
    text-transform: uppercase;
    padding: .45rem .45rem;
    position: relative;
    top: -5px;
    font-family: "titillium-web-semibold", sans-serif;
    line-height: 10.5px;
    border: 0;
}
@media screen and (max-width: 1060px) {
    #primary { width:67%; }
    #secondary { width:30%; margin-left:3%;}  
}
/* Tabled Portrait */
@media screen and (max-width: 768px) {
    #primary { width:100%; }
    #secondary { width:100%; margin:0; border:none; }
}
.notification__action {
    cursor: pointer;
}
.flag_action{
	cursor: pointer;
	font: normal normal normal 14px/1 FontAwesome !important;
}
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.8.2/css/all.min.css">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">

    <style>
    .page_title {
        position: absolute;
        right: 34px;
    }

    .form_1 {
        padding: 9px 15px;
        border-radius: 10px;
        box-shadow: 0px 2px 3px 0px #00000021 !important;
        font-size: 1.1rem;
        font-weight: bold;
        background: #fff;
        cursor: pointer;
    }

    .form_2 {
        padding: 9px 15px;
        border-radius: 10px;
        box-shadow: 0px 2px 3px 0px #00000021 !important;
        font-size: 1.1rem;
        font-weight: bold;
        background: #fff;
        margin-right: 82%;
        cursor: pointer;
    }

    .mobile_app_fee {
        display:none;
    }

</style>

<style type="text/css">

/* Chrome, Safari, Edge, Opera */
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

/* Firefox */
input[type=number] {
    -moz-appearance: textfield;
}

.default_1 {
    margin-left: -21%;
}

.de_d {
    border: none;
    text-align: center;
    position: absolute;
    width: 240px;
}

.edp_tsd {
    max-width: 90% !important;
}

.edp_tsd {
    max-width: 90% !important;
    text-align: left;
    float: left;
}

.courier {
    max-width: 90% !important;
    text-align: left;
    float: left;
}

span.edp_eq {
    font-size: 18px;
    position: absolute;
    padding-top: 4px;
}

span.edp_eq2 {
    margin-left: 10px;
    font-size: 23px;
    position: absolute;
}

#total_b::-webkit-input-placeholder {
    /* Chrome/Opera/Safari */
    color: #000;
}

#total_b::-moz-placeholder {
    /* Firefox 19+ */
    color: #000;
}

#total_b:-ms-input-placeholder {
    /* IE 10+ */
    color: #000;
}

#total_b:-moz-placeholder {
    /* Firefox 18- */
    color: #000;
}

#total_b {
    text-align: center;
    border: none;
}

#total_a::-webkit-input-placeholder {
    /* Chrome/Opera/Safari */
    color: #000;
}

#total_a::-moz-placeholder {
    /* Firefox 19+ */
    color: #000;
}

#total_a:-ms-input-placeholder {
    /* IE 10+ */
    color: #000;
}

#total_a:-moz-placeholder {
    /* Firefox 18- */
    color: #000;
}

#total_a {
    text-align: center;
    border: none;
}

#total_c2::-webkit-input-placeholder {
    /* Chrome/Opera/Safari */
    color: #000;
}

#total_c2::-moz-placeholder {
    /* Firefox 19+ */
    color: #000;
}

#total_c2:-ms-input-placeholder {
    /* IE 10+ */
    color: #000;
}

#total_c2:-moz-placeholder {
    /* Firefox 18- */
    color: #000;
}

#total_c2 {
    text-align: center;
    border: none;
}

#total_inter::-webkit-input-placeholder {
    /* Chrome/Opera/Safari */
    color: #000;
}

#total_inter::-moz-placeholder {
    /* Firefox 19+ */
    color: #000;
}

#total_inter:-ms-input-placeholder {
    /* IE 10+ */
    color: #000;
}

#total_inter:-moz-placeholder {
    /* Firefox 18- */
    color: #000;
}

#total_inter {
    text-align: center;
    border: none;
}

#total_art::-webkit-input-placeholder {
    /* Chrome/Opera/Safari */
    color: #000;
}

#total_art::-moz-placeholder {
    /* Firefox 19+ */
    color: #000;
}

#total_art:-ms-input-placeholder {
    /* IE 10+ */
    color: #000;
}

#total_art:-moz-placeholder {
    /* Firefox 18- */
    color: #000;
}

#total_art {
    text-align: center;
    border: none;
}

#total_usage::-webkit-input-placeholder {
    /* Chrome/Opera/Safari */
    color: #000;
}

#total_usage::-moz-placeholder {
    /* Firefox 19+ */
    color: #000;
}

#total_usage:-ms-input-placeholder {
    /* IE 10+ */
    color: #000;
}

#total_usage:-moz-placeholder {
    /* Firefox 18- */
    color: #000;
}

#total_usage {
    text-align: center;
    border: none;
}

#total_iq::-webkit-input-placeholder {
    /* Chrome/Opera/Safari */
    color: #000;
}

#total_iq::-moz-placeholder {
    /* Firefox 19+ */
    color: #000;
}

#total_iq:-ms-input-placeholder {
    /* IE 10+ */
    color: #000;
}

#total_iq:-moz-placeholder {
    /* Firefox 18- */
    color: #000;
}

#total_iq {
    text-align: center;
    border: none;
}

#total_man::-webkit-input-placeholder {
    /* Chrome/Opera/Safari */
    color: #000;
}

#total_man::-moz-placeholder {
    /* Firefox 19+ */
    color: #000;
}

#total_iq:-ms-input-placeholder {
    /* IE 10+ */
    color: #000;
}

#total_man:-moz-placeholder {
    /* Firefox 18- */
    color: #000;
}

#total_man {
    text-align: center;
    border: none;
}

#total_iq2::-webkit-input-placeholder {
    /* Chrome/Opera/Safari */
    color: #000;
}

#total_iq2::-moz-placeholder {
    /* Firefox 19+ */
    color: #000;
}

#total_iq2:-ms-input-placeholder {
    /* IE 10+ */
    color: #000;
}

#total_iq2:-moz-placeholder {
    /* Firefox 18- */
    color: #000;
}

#total_iq2 {
    text-align: center;
    border: none;
}


</style>

<?php
if (isset($method) && $method == "print") {
   include_once("../uikit1.php");
}

$center_name = getCentreName($centre_code);

function echoReportHeader($month, $year)
{
   global $centre_code, $center_name;
   echo "<div class='uk-width-1-1 myheader text-center mt-5' style='text-align:center;color:white;'>";
   echo "<h2 class='uk-text-center myheader-text-color myheader-text-style'>CENTRE MONTHLY STUDENT FEES OUTSTANDING REPORT (DETAIL)</h2>";
   if ($month == '13') {
      echo "Selected Month :All Month<br>";
   } else {
      $dateObj   = DateTime::createFromFormat('!m', $month);
      $monthName = $dateObj->format('F');
      echo "Selected Month : $monthName $year<br>";
   }

   if ($centre_code == "") {
      echo "Centre ALL";
   } else {
      echo "Centre $center_name";
   }
   echo "</div>";
}
function getProductNameByCode($product_name, $centre_code)
{
   global $connection;

   $sql = "SELECT product_code from addon_product where product_name='$product_name' and centre_code = '$centre_code'";
   $result = mysqli_query($connection, $sql);
   $row = mysqli_fetch_assoc($result);
   $num_row = mysqli_num_rows($result);

   return $row["product_code"];
   
}

function getCentreName($centre_code)
{
   global $connection;

   $sql = "SELECT company_name from centre where centre_code='$centre_code'";
   $result = mysqli_query($connection, $sql);
   $row = mysqli_fetch_assoc($result);
   $num_row = mysqli_num_rows($result);

   if ($num_row > 0) {
      return $row["company_name"];
   } else {
      if ($centre_code == "ALL") {
         return "All Centres";
      } else {
         return "";
      }
   }
}
function echoReportHeader1($centre_code, $user_name, $date, $term)
{
   global $center_name;

   echo "<div class='nice-form'>";
   echo "<div class='uk-grid'>";
   echo "<div class='uk-width-medium-5-10'>";
   echo "<table class='uk-table'>";
   echo "<tr>";
   echo "<td class='uk-text-bold'>Centre Name</td>";
   echo "<td>" . $center_name . "</td>";
   echo "</tr>";
   echo "<tr>";
   echo "<td class='uk-text-bold'>Prepare By</td>";
   echo "<td>" . $user_name . "</td>";
   echo "<tr>";
   echo "<td colspan='2' class='uk-text-bold'>NOTE: To be submitted termly with the order form</td>";
   echo "</tr>";
   echo "</table>";
   echo "</div>";
   echo "<div class='uk-width-medium-5-10'>";
   echo "<table class='uk-table'>";
   echo "<tr>";
   echo "<td class='uk-text-bold'>Academic Year</td>";
   echo "<td>" . $date . "</td>";
   echo "</tr>";
   echo "<tr>";
   echo "<td class='uk-text-bold'>School Term</td>";
   echo "<td>" .  rtrim($term, ", ") . "</td>";
   echo "</tr>";
   echo "<tr>";
   echo "<td class='uk-text-bold'>Date of submission</td>";
   echo " <td>" . date('Y-m-d H:i:s') . "</td>";
   echo "</tr>";
   echo "</tr>";
   echo " </table>";
   echo "</div>";
   echo "</div>";
}

function echoTable()
{
   echo "<table class='uk-table'>";
}
function echoTableHeader()
{
   echo "<table class='uk-table'>";
   echo "<tr class='uk-text-bold'>";
   echo "  <td style='width: 5%;'>No.</td>";
   echo "  <td style='width: 25%;' >Student </td>";
   echo "  <td style='width: 10%;'>Class Name</td>";
   echo "  <td style='width: 25%;'>Fee Description</td>";
   echo "  <td style='width: 8%;' class='uk-text-right'>Amount</td>";
   echo "</tr>";
   echo "</table>";
}
function echoClassHeader($class)
{
   echo "<tr>";
   echo "  <td colspan='6'><h3 class='myheader'>$class</h3></td>";
   echo "</tr>";
}

function echoStudentHeader($student_name, $student_code)
{
   echo "<tr>";
   echo "  <td colspan='6'><h3 class='myheader'>$student_code $student_name</h3></td>";
   echo "</tr>";
}

function echoRowWithClassInfo($no, $subject, $student_name, $fee_description, $amount)
{
   echo "<tr>";
   echo "  <td style='width: 5%;'>$no</td>";
   echo "  <td style='width: 25%;'>$student_name</td>";
   echo "  <td style='width: 10%;'>$subject</td>";
   echo "  <td style='width: 25%;'>$fee_description</td>";
   echo "  <td class='uk-text-right' style='width: 8%;'>" . number_format($amount, 2) . "</td>";
   echo "</tr>";
}

function echoStudentTotal($total)
{
   echo "<tr>";
   echo "  <td colspan='4' class='uk-text-left uk-text-bold'>Total Outstanding</td>";
   echo "  <td class='uk-text-right'>" . number_format($total, 2) . "</td>";
   echo "</tr>";
}

function echoGrandTotal($total)
{
   echo "<tr>";
   echo "  <td colspan='4' class='uk-text-right uk-text-bold'>TOTAL:</td>";
   echo "  <td class='uk-text-right'>" . number_format($total, 2) . "</td>";
   echo "</tr>";
}

function echoNotFound()
{
   echo "<table class='uk-table'>";
   echo "  <tr class='uk-text-bold'>";
   echo "    <td colspan='4'>No record found</td>";
   echo "  </tr>";
   echo "</table>";
}

function echoTableFooter()
{
   echo "</table>";
   echo "</div>";
}

function getStrTerm($term) {
	 switch ($term) {
      case 1 : return "Term 1"; break;
      case 2 : return "Term 2"; break;
      case 3 : return "Term 3"; break;
      case 4 : return "Term 4"; break;
      case 5 : return "Term 5"; break;
   }
}

$halfDate = getHalfYearDate($session_year, $centre_code);

function getCurrentHalfYearly($date) {

   global $halfDate;

	if($date < $halfDate){
		return 1;
	}
	else if($date >= $halfDate){
		return 2;
	}
}

function getStrHalfYearly($HalfYearly) {
	 switch ($HalfYearly) {
      case 1 : return "First Half"; break;
      case 2 : return "Second Half"; break;
      
   }
}

foreach ($monthList as $dt) {
   $m = $dt->format("MY");
   $$m = 0;
} 

$Total = 0;

$sql="SELECT ps.id, ps.student_id, fl.afternoon_programme, s.name as student_name, s.student_code, f.school_adjust, f.subject, f.school_collection, f.multimedia_collection, f.facility_collection, f.basic_collection, f.multimedia_adjust, f.facility_adjust, f.extend_year, f.basic_adjust, fl.fee_id, month(fl.programme_date) programme_start_month, fl.programme_date, fl.programme_date_end, fl.foundation_iq_math, fl.foundation_int_mandarin, fl.foundation_int_art, fl.foundation_mandarin, fl.foundation_int_english, fl.pendidikan_islam from student s, programme_selection ps, student_fee_list fl, fee_structure f  where s.id=ps.student_id and ps.id = fl.programme_selection_id and f.id=fl.fee_id and s.centre_code='$centre_code' and s.`student_status`='A' ";
if ($month != '13') {
   $sql .= " and fl.programme_date >= '".$year_start_date."' and fl.programme_date <='".$month_end_date."'";
} else {
   $sql .= " and fl.programme_date >= '".$year_start_date."' and fl.programme_date <='".$year_end_date."'";
}

if ($student) {
   $sql .= " and (s.name like '%$student%' or s.student_code like '%$student%')";
}
if ($student) {
   $sql .= " and (s.name like '%$student%' or s.student_code like '%$student%')";
}
$sql .= " group by ps.student_id";

$c_result = mysqli_query($connection, $sql);
$branch_total = 0;
echoReportHeader($month, $year);

$term = '';
if ($month == "13") {
   
   $term_data = mysqli_query($connection,"SELECT `term_num` FROM `schedule_term` WHERE `year` = '".$session_year."' AND `deleted` = '0' AND `centre_code` = '".$centre_code."' ORDER BY `term_num` ASC");

   while($term_row = mysqli_fetch_array($term_data))
   {
      $term .= $term_row['term_num'].',';
   }

} else {
   $term = getTermFromDate(date('Y-m-d',strtotime($year.'-'.$month)),$centre_code);
}

echoReportHeader1($centre_code, $UserName, $session_year, $term);

echoTableHeader();

$count = 0;

$where = '';

$where .= ($centre_code != '') ? " AND `centre_code` = '".$centre_code."'" : "";

$where .= " AND `status` != 'Pending'";

while ($c_row = mysqli_fetch_assoc($c_result)) {

   $sql="SELECT ps.id as allocation_id, ps.student_id,  f.subject, f.fee_name, f.fees, f.payment_type, f.extend_year, fl.fee_id, fl.programme_date as start_date, fl.programme_date_end as end_date
   from student s, programme_selection ps, student_fee_list fl, 
   (
      select id, subject,'School Fees' as fee_name, school_adjust as fees, school_collection as payment_type, extend_year from fee_structure WHERE 1=1 ".$where."
      UNION ALL
      select id, subject,'Multimedia Fees' as fee_name, multimedia_adjust as fees, multimedia_collection as payment_type, extend_year from fee_structure WHERE 1=1 ".$where."
      UNION ALL
      select id, subject,'Facility Fees' as fee_name, facility_adjust as fees, facility_collection as payment_type, extend_year from fee_structure WHERE 1=1 ".$where."
      UNION ALL
      select id, subject,'Integrated Module' as fee_name, integrated_adjust as fees, integrated_collection as payment_type, extend_year from fee_structure WHERE 1=1 ".$where."
      UNION ALL
      select id, subject,'Link & Think' as fee_name, link_adjust as fees, link_collection as payment_type, extend_year from fee_structure WHERE 1=1 ".$where."
      UNION ALL
      select id, subject,'Mobile app' as fee_name, mobile_adjust as fees, mobile_collection as payment_type, extend_year from fee_structure WHERE 1=1 ".$where."
      UNION ALL
      select id, subject,'Registration' as fee_name, registration_adjust as fees, 'Annually' as payment_type, extend_year from fee_structure WHERE 1=1 ".$where."
      UNION ALL
      select id, subject,'Insurance' as fee_name, insurance_adjust as fees, 'Annually' as payment_type, extend_year from fee_structure WHERE 1=1 ".$where."
      UNION ALL
      select id, subject,'Q-dees Level Kit' as fee_name, q_dees_adjust as fees, 'Annually' as payment_type, extend_year from fee_structure WHERE 1=1 ".$where."
      UNION ALL
      select id, subject,'Uniform (2 sets)' as fee_name, uniform_adjust as fees, 'Annually' as payment_type, extend_year from fee_structure WHERE 1=1 ".$where."
      UNION ALL
      select id, subject,'Gymwear (1 set)' as fee_name, gymwear_adjust as fees, 'Annually' as payment_type, extend_year from fee_structure WHERE 1=1 ".$where."
      UNION ALL
      select id, subject,'Q-dees Bag' as fee_name, q_bag_adjust as fees, 'Annually' as payment_type, extend_year from fee_structure  WHERE 1=1 ".$where."";
      if ($c_row["foundation_mandarin"]){
         $sql .= " 
         UNION ALL
      select id, subject,'Mandarin Modules' as fee_name, mandarin_m_adjust as fees, mandarin_m_collection as payment_type, extend_year from fee_structure  WHERE 1=1 ".$where."";
      }
      if ($c_row["foundation_iq_math"]){
         $sql .= " 
         UNION ALL
         select id, subject,'IQ Math' as fee_name, iq_math_adjust as fees, iq_math_collection as payment_type, extend_year from fee_structure  WHERE 1=1 ".$where."";
      }
      if ($c_row["foundation_int_mandarin"]){
         $sql .= " 
         UNION ALL
         select id, subject,'Enhanced Foundation Mandarin' as fee_name, mandarin_adjust as fees, mandarin_collection as payment_type, extend_year from fee_structure  WHERE 1=1 ".$where."";
      }
      if ($c_row["foundation_int_art"]){
         $sql .= " 
         UNION ALL
         select id, subject,'International Art' as fee_name, international_adjust as fees, international_collection as payment_type, extend_year from fee_structure  WHERE 1=1 ".$where."";
       }
      if ($c_row["foundation_int_english"]){
         $sql .= " UNION ALL
         select id, subject,'International English' as fee_name, enhanced_adjust as fees, enhanced_collection as payment_type, extend_year from fee_structure  WHERE 1=1 ".$where."";
      }
      if ($c_row["pendidikan_islam"]){
         $sql .= " UNION ALL
         select id, subject, 'Pendidikan Islam' as fee_name, islam_adjust as fees, 'Annually' as payment_type, extend_year from fee_structure  WHERE 1=1 ".$where."";
      }
     if ($c_row["afternoon_programme"]){
         $sql .= " UNION ALL
         select id, subject,'Afternoon Programme' as fee_name, basic_adjust as fees, basic_collection as payment_type, extend_year from fee_structure  WHERE 1=1 ".$where."";
      }
      
      $sql .= " ) f  where s.id=ps.student_id and ps.id = fl.programme_selection_id and f.id=fl.fee_id and s.centre_code='$centre_code' and ps.student_id='" . $c_row["student_id"] . "' ";
      
      if ($month != '13') {
         $sql .= " and fl.programme_date >= '".$year_start_date."' and fl.programme_date <='".$month_end_date."'";
      } else {
         $sql .= " and fl.programme_date >= '".$year_start_date."' and fl.programme_date <='".$year_end_date."'";
      }

      $sql11="select  fl.programme_date, fl.programme_date_end from student s, programme_selection ps, student_fee_list fl where s.id=ps.student_id and ps.id = fl.programme_selection_id and s.`student_status`='A' and fl.programme_date >= '".$year_start_date."' and s.id = '" . $c_row["student_id"] . "' and fl.programme_date <= '".$month_end_date."'";

      $result11=mysqli_query($connection, $sql11);
      $num_row111=mysqli_num_rows($result11);

      if ($num_row111>0) {
         $end_date_addon= "";
         $z=0;
         while ($row111 = mysqli_fetch_assoc($result11)) {
            if($z==0){
               $programme_date = $row111['programme_date'];
            }
            $programme_date_end = $row111['programme_date_end'];
            $z++;
         }
      }
            
      $sql .= " UNION ALL 
      select '0' as allocation_id, '" . $c_row["student_id"] . "' as student_id, 'Add-On Products' as subject, `product_name` as fee_name, unit_price as fees, `monthly` as payment_type, '".$session_year."' as extend_year, '' as fee_id, '$programme_date' as start_date, '$programme_date_end' as end_date from addon_product where centre_code='$centre_code' and status='Approved'";
         
      $sql .= " order by fee_name ";

   $result = mysqli_query($connection, $sql);
   $num_row = mysqli_num_rows($result);
   $count++;
   if ($num_row > 0) {
      
      echoTable();
      $class_total = 0;
      $programme_date = $c_row["programme_start_month"];
      $check_item_array=array();
      
      while ($row = mysqli_fetch_assoc($result)) {
          if($row["fees"]!=0){
            
            if ($row["payment_type"] == 'Monthly') {
               $start_month = explode('-', ($row["start_date"]))[1];
               $end_month = explode('-', ($row["end_date"]))[1];
             
               if($row["start_date"] < $year_start_date) {
                  $start = $year_start_date;
               } else {
                  $start = $row["start_date"];
               }
               if($row["end_date"] > $year_end_date) {
                  $end = $year_end_date;
               } else {
                  $end = $row["end_date"];
               }

               $months = getMonthList($start,$end);

               foreach ($months as $dt) {
                  $var_name = $dt->format("MY");

                  $full_item_name = $row["fee_name"].' for ' . $dt->format("M Y");
                  if (!in_array($full_item_name, $check_item_array)) {
                     array_push($check_item_array, $full_item_name);

                     if (($month < 13 && $dt->format("Ym") <= date('Ym',strtotime($year.'-'.$month))) || ($month == 13 && $i+1 <= date('Ym',strtotime($row["end_date"])))) {
                        $allocation_id= $row["allocation_id"];
                        $fee_name= $row["fee_name"];
                        $product_code= $row["fee_name"];
                        if($row["subject"] =="Add-On Products"){
                           $product_code = getProductNameByCode($fee_name, $centre_code);
                        }
                        
                        $student_id= $row["student_id"];
                        $collection_month= $dt->format("m");

                        $sql22 = "SELECT sum(amount)+sum(discount) collection FROM `collection` c  where c.student_id = '$student_id' and  c.year = '".$session_year."' and c.void = 0 and c.`collection_month` = $collection_month and c.product_code = '$product_code' and collection_pattern ='Monthly'";
                       
                        $result22=mysqli_query($connection, $sql22);
                      
                        $row_collection=mysqli_fetch_assoc($result22);
                        
                        $collection =(($row_collection["collection"] == '') ? 0 : $row_collection["collection"]);
                        $balance = $row["fees"] - $collection;
                        if(($balance>0 && $row["subject"] !="Add-On Products") || ($row["subject"] =="Add-On Products" && $collection>0 && $balance>0)){ 
                      
                           echoRowWithClassInfo( ($class_total == 0) ? $count : "" , $row["subject"], ($class_total == 0) ? $c_row["student_name"] . '<br>' . $c_row["student_code"] : "", $row["fee_name"].' for ' . $dt->format("M Y"), $balance);
                              $class_total += $balance;
                              $Total += $balance;

                              $$var_name += $balance;
                        }
                     }
                  }
               }
            }
            else if ($row["payment_type"] == 'Termly') {
           
               $start_month = date("MY",strtotime($row["start_date"]));
                
               $term_start = getTermFromDate($row["start_date"],$centre_code);
               $term_end = getTermFromDate($row["end_date"],$centre_code);
               $i = (int)$term_start-1;
               $term_end_int = (int)$term_end;
               
               for ($i; $i < $term_end_int; $i++) { 
                  $full_item_name = $row["fee_name"].' for ' . getStrTerm($i+1);
                  if (!in_array($full_item_name, $check_item_array)) {
                     array_push($check_item_array, $full_item_name);

                     if (($month < 13 && $i+1 <= getTermFromDate(date('Y-m-d',strtotime($year.'-'.$month)),$centre_code)) || ($month == 13 && $i+1 <= $term_end)) {
                        $allocation_id= $row["allocation_id"];
                        $fee_name= $row["fee_name"];
                        $product_code= $row["fee_name"];
                        if($row["subject"] =="Add-On Products"){
                           $product_code = getProductNameByCode($fee_name, $centre_code);
                        }
                        $student_id= $row["student_id"];
                        $collection_month= $i+1;
              
                        $sql22 = "SELECT sum(amount)+sum(discount) collection FROM `collection` c  where c.student_id = '$student_id' and  c.year = '".$session_year."' and c.void = 0 and c.`collection_month` = $collection_month and c.product_code = '$product_code' and collection_pattern ='Termly' ";
                        
                        $result22=mysqli_query($connection, $sql22);
              
                        $row_collection=mysqli_fetch_assoc($result22);
              
                        $collection =(($row_collection["collection"] == '') ? 0 : $row_collection["collection"]);
                        
                        $balance = $row["fees"] - $collection;
                     
                        if(($balance>0 && $row["subject"] !="Add-On Products") || ($row["subject"] =="Add-On Products" && $collection>0 && $balance>0)){    
                           
                           echoRowWithClassInfo( ($class_total == 0) ? $count : "" , $row["subject"], ($class_total == 0) ? $c_row["student_name"] . '<br>' . $c_row["student_code"] : "", $row["fee_name"].' for ' . getStrTerm($i+1), $balance);
                           $class_total += $balance;
                           $Total += $balance;

                           $$start_month += $balance;
                        }
                     }
                  }
               }
            } else if ($row["payment_type"] == 'Half Year') {
               
               $start_month = date("MY",strtotime($row["start_date"]));

               $i = getCurrentHalfYearly($row["start_date"]);
               $HearfYearly = getCurrentHalfYearly($row["end_date"]);

               for ($i; $i < $HearfYearly; $i++) {

                  if (($month < 13 && $i+1 <= getCurrentHalfYearly(date('Y-m-d',strtotime($year.'-'.$month)))) || ($month == 13 && $i+1 <= $HearfYearly)) {
                     $full_item_name = $row["fee_name"].' for ' . getStrHalfYearly($i+1);
                     if (!in_array($full_item_name, $check_item_array)) {
                        array_push($check_item_array, $full_item_name);
                     
                        $allocation_id= $row["allocation_id"];
                        $fee_name= $row["fee_name"];
                        $product_code= $row["fee_name"];
                        if($row["subject"] =="Add-On Products"){
                           $product_code = getProductNameByCode($fee_name, $centre_code);
                        }
                        $student_id= $row["student_id"];
                        $collection_month= $i+1;

                        $sql22 = "SELECT sum(amount)+sum(discount) collection FROM `collection` c  where c.student_id = '$student_id' and  c.year = '".$session_year."' and c.void = 0 and c.`collection_month` = $collection_month and c.product_code = '$product_code' and collection_pattern ='Half Year'";

                        $result22=mysqli_query($connection, $sql22);
                        $IsRow22=mysqli_num_rows($result22);
                        $row_collection=mysqli_fetch_assoc($result22);
                        $collection =(($row_collection["collection"] == '') ? 0 : $row_collection["collection"]);
                        $balance = $row["fees"] - $collection;
                        if(($balance>0 && $row["subject"] !="Add-On Products") || ($row["subject"] =="Add-On Products" && $collection>0 && $balance>0)){ 

                        echoRowWithClassInfo( ($class_total == 0) ? $count : "" , $row["subject"], ($class_total == 0) ? $c_row["student_name"] . '<br>' . $c_row["student_code"] : "", $row["fee_name"].' for ' . getStrHalfYearly($i+1), $balance);
                        $class_total += $balance;
                        $Total += $balance;

                        $$start_month += $balance;
                      }
                     }
                  }
               }
         } else if ($row["payment_type"] == 'Annually') {

            $start_month = date("MY",strtotime($row["start_date"]));
            $strYear = $session_year;

            $full_item_name = $row["fee_name"].' for ' . $strYear;
            if (!in_array($full_item_name, $check_item_array)) {
               array_push($check_item_array, $full_item_name);

               $allocation_id= $row["allocation_id"];
               $fee_name= $row["fee_name"];
               $product_code= $row["fee_name"];
               if($row["subject"] =="Add-On Products"){
                  $product_code = getProductNameByCode($fee_name, $centre_code);
               }
               $student_id= $row["student_id"];
               $collection_month= 1;

               $sql22 = "SELECT sum(amount)+sum(discount) collection FROM `collection` c  where c.student_id = '$student_id' and  c.year = '".$session_year."' and c.void = 0 and c.`collection_month` = $collection_month and c.product_code = '$product_code' and collection_pattern ='Annually'";
               
               $result22=mysqli_query($connection, $sql22);
               $row_collection=mysqli_fetch_assoc($result22);
               $collection =(($row_collection["collection"] == '') ? 0 : $row_collection["collection"]);
               $balance = $row["fees"] - $collection;
               if(($balance>0 && $row["subject"] !="Add-On Products") || ($row["subject"] =="Add-On Products" && $collection>0 && $balance>0)){ 
               
                  echoRowWithClassInfo(($class_total == 0) ? $count : "", $row["subject"], ($class_total == 0) ? $c_row["student_name"] . '<br>' . $c_row["student_code"] : "", $row["fee_name"].' for ' . $strYear, $balance);
                  $class_total += $balance;
                  $Total += $balance;
               
                     $$start_month += $balance;
                  }
               }
            }
         }
      }
      $branch_total += $class_total;
      echoStudentTotal($class_total);
   } else {
      //echoNotFound();
   }
}

$current_month_year = date('MY',strtotime($year.'-'.$month));
echoGrandTotal($$current_month_year);

?>
<table class="uk-table sum-table">
   <thead>
      <tr>
         <th>Month/Year</th>
         <?php 
            foreach ($monthList as $dt) {
               echo '<th>'.$dt->format("M Y").'</th>';
            }        
         ?>
         <th>Total</th>
      </tr>
   </thead>
   <tbody>
      <tr>  
         <td style="width: 5%;"><?php echo $session_year; ?></td>  
         <?php 
            foreach ($monthList as $dt) {
               $m = $dt->format("MY");
               echo '<td class="uk-text-right">'.$$m.'</td>';
            }        
         ?>
         <td class="uk-text-right"><?php echo $Total; ?></td>
      </tr>
   </tbody>
</table>
<script>
   $(document).ready(function() {
      var method = '';
      if (method == "print") {
         window.print();
      }
   });
</script>
<style>
.nice-form table td.uk-text-right:last-child {
    text-align: right!important;  
}
.sum-table td, th {
    border: 1px solid #8080804f;
   }
</style></tbody></table>
<script>
   $(document).ready(function() {
      var method = '<?php echo $method ?>';
      if (method == "print") {
         window.print();
      }
   });
</script>
<style>
   .nice-form table td.uk-text-right:last-child {
    text-align: right!important;
}
</style>
