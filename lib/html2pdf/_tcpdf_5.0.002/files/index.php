<?php
if ($_SERVER['REQUEST_URI']=="/") {
   header("location: index.php");
}

session_start();
include_once("mysql.php");
include_once("admin/functions.php");
//date_default_timezone_set("Asia/Kuala_Lumpur");

function isMobileDevice() {
   return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
}

function getCentreNameIndex($centre_code) {
  global $connection;

  $sql="SELECT company_name from centre where centre_code='$centre_code'";
  $result=mysqli_query($connection, $sql);
  $row=mysqli_fetch_assoc($result);
  return $row["company_name"];
}

function noInCart() {
  global $connection;

  $user_name=$_SESSION["UserName"];

  $sql="SELECT sum(qty) as qty from cart where user_name='$user_name' order by date_created";
  $result=mysqli_query($connection, $sql);
  $row=mysqli_fetch_assoc($result);
  $num_row=mysqli_num_rows($result);
  if ($num_row>0) {
     if ($row["qty"]!="") {
        return number_format($row["qty"], 0);
     } else {
        return "0";
     }
  } else {
     return "0";
  }
}

function getLink($p, $active_page, $str_p) {
  if ($p!=$active_page) {
     return "<a href=\"index.php?p=$p\">$str_p</a>";
  } else {
     return $str_p;
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Qdees</title>
<?php
  if (isset($_SESSION["isLogin"]) && $_SESSION["isLogin"]==1) {
    include_once("bootstrap.php");
    include_once("uikit.php");
  } else {
    include_once("uikit.php");
  }
?>
  <link rel="shortcut icon" href="images/favicon.png" />

    <!-- <link rel="stylesheet" href="https://unpkg.com/simplebar@latest/dist/simplebar.css" /> -->
    <link rel="stylesheet" href="lib/simplebar/simplebar.css" />


<script>
function doYear(year) {
   $.ajax({
      url : "admin/do_year.php",
      type : "POST",
      data : "year="+year,
      dataType : "text",
      beforeSend : function(http) {
      },
      success : function(response, status, http) {
         if (response=="1") {
            location.reload();
         }

         if (response=="0") {
            UIkit.notify("Switching year failed");
         }
      },
      error : function(http, status, error) {
         UIkit.notify("Error:"+error);
      }
   });
}

function openLink(link) {
    windows.location.href = link;
}

function viewCart() {
   $.ajax({
      url : "admin/dlg_view_cart.php",
      type : "POST",
      data : "1=1",
      dataType : "text",
      beforeSend : function(http) {
      },
      success : function(response, status, http) {
        var s=response.split("|");
        if (s[0]!="0") {
          $("#dlgViewCart").html(response);
          $("#dlgViewCart").dialog({
             dialogClass:"no-close",
             width:'1000',
             height:'auto',
             modal:true,
             title:"Cart Content",
          });
        } else {
          UIkit.notify("Nothing found in cart");
        }
      },
      error : function(http, status, error) {
         UIkit.notify("Error:"+error);
      }
   });
}

$(".navbar-toggler.navbar-toggler.align-self-center").click(()=> {
    if($("#thebody").hasClass("sidebar-icon-only")) {
        $(".navbar-brand.brand-logo").css("display", "none");
    }
})
</script>
<style>
.no-close .ui-dialog-titlebar-close {
  display: none;
}
body {
   height:1500;
   -webkit-background-size: cover;
   -moz-background-size: cover;
   -o-background-size: cover;
   background-size: cover;
   background-attachment:fixed;
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

.yrbtn {
    background: #3399ff!important;
    border-color: transparent!important;
}

.sales-title a {
    font-size: 24px;
    color: teal !important;
}

.btn-logout {
    background: white;
    color: grey!important;
    border-radius: 10px;
    width: fit-content;
    padding: 7px 15px!important;
}

.btn-logout  {color: grey!important;}

.btn-qdees {
    padding: 5px 10px;
    border-radius: 100%;
    background: white;
    box-shadow: 0px 13px 21px -10px rgba(0, 0, 0, 0.15)!important;
    margin-right: 15px;
    color: grey;
}
.content-wrapper .btn-qdees {
    padding: 9px 15px;
    border-radius: 10px;
    box-shadow: 0px 2px 3px 0px #00000021 !important;
    font-size: 1.2rem;
    font-weight: bold;

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
    background: #6594CD !important;
    position: fixed;
    height:  100vh;
    min-height: auto!important;
}

.navbar .navbar-menu-wrapper {
    background: #fdba0c !important;
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
	color: white;
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

.sidebar p, .sidebar h3{color:white;}

.header-top-part {
    width: 100%;
    padding: 1.5em 0;
    text-align: center;
    text-transform: uppercase!important;
    background: #30D3D6;
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
	background-color:#30D2D6;
}

#toCopy span, .form_btn, .modal_btn {
	font-size: 1.1rem;
    cursor: pointer;
    border-radius: 8px;
    padding: .6em 1.5em;
    background: #FDBA0C !important;
    color: white;
    font-weight: bold;
    text-shadow: 0px 2px 2px #00000059;
    letter-spacing: 1px;
}

.form_btn, .modal_btn{
	padding: .2em 1.5em;
}
.modal_btn:hover{color:white;}

.navbar .navbar-brand-wrapper .navbar-brand img{
	height:60px;
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
    background: #30D2D6;
    border: none;
    border-radius: 8px;
    color: white;
    font-weight: 300;
}

.full-width-blue:hover {
    background: #30D2D6;
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
    background: #30d2d6;
    color: white;
    text-align: center;
    font-size: 24px;
}
.ui-dialog .ui-dialog-title{float:none;}
.blue_button{
	background:#30d2d6 !important;
	color:white!important;
	border:0;
	text-shadow:none;
}
.uk-modal-close{
	padding: .3em 2em;
}
</style>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.8.2/css/all.min.css">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">

</head>
<body id="thebody">


<?php
if (isset($_SESSION["isLogin"]) && $_SESSION["isLogin"]==1) {
?>
  <div class="container-scroller">
    <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row navbar-info">
      <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
        <a class="navbar-brand brand-logo" href="index.php"><img src="/images/Logo Qdees Scholar.png" alt="logo"/></a>
        <a class="navbar-brand brand-logo-mini" href="index.php"><img src="images/logo-mini.png" alt="logo"/></a>
      </div>
      <div class="navbar-menu-wrapper d-flex align-items-stretch">
        <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
          <span class="mdi mdi-equal">
          </span>
        </button>
        <div class="align-self-center">
<?php
if (($_SESSION["isLogin"]==1) & (!isMobileDevice())) {
   echo "<div class='uk-text-medium'>Hello ".$_SESSION["UserName"].", welcome back!</div>";
}
?>
        </div>
        <form class="form-inline d-none d-lg-block search my-auto">
        </form>
        <ul class="navbar-nav navbar-nav-right" style="margin-top: 0px;">
          <li class="nav-item nav-item-highlight">
<?php
if (hasRightGroupXOR($_SESSION["UserName"], "OrderEdit")) {
?>
    <span class="btn-qdees" onclick="viewCart()" style="margin-right:0px;"><i class="fa fa-shopping-cart" data-uk-tooltip="{pos:top}" title="View Cart"></i></span>
    <span class="badge badge-pill badge-danger" id="no_in_cart" style="margin-right:15px;"><?php echo noInCart();?></span>
    <a href="/"><div class="btn-qdees"><i style="font-size: 1.2em" class="fa fa-home"></i></div></a>
<?php
}
?>
            <a class="nav-link btn btn-logout" href="logout.php"> Logout &nbsp;&nbsp;&nbsp;&nbsp; <i style="color: grey!important; font-size: 1.2rem" class="fa fa-arrow-right"></i></a>
          </li>
        </ul>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
          <span class="mdi mdi-equal"></span>
        </button>
      </div>
    </nav>

    <div class="container-fluid page-body-wrapper">
      <div class="row row-offcanvas row-offcanvas-right">
        <div id="right-sidebar" class="settings-panel">
          <i class="settings-close mdi mdi-close"></i>
        </div>
        <nav class="sidebar sidebar-offcanvas" id="sidebar" data-simplebar  style="padding-bottom:70px;">
          <ul class="nav">
            <li class="nav-item nav-profile">
              <span class="nav-link" href="#">
                <p><h3 class="uk-text-center uk-text-bold "><?php echo getCentreNameIndex($_SESSION["CentreCode"])?></h3></p>
                <p class="efxtv uk-text-center"><?php echo $_SESSION["UserName"]?></p>
              </span>
            </li>
            <li class="nav-item nav-profile">
              <div class="btn-group efcbtn">
<?php
if (($_SESSION["UserType"]=="S")) {
?>
                <div class="dropdown" style="width: 100%; margin-bottom: 15px;">
                  <button class="btn btn-secondary dropdown-toggle yrbtn " type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
<?php
if (($_GET["p"]=="") & ($_GET["master"]==1)) {
?>
                    Masters
<?php
} else {
  if ($_GET["master"]==1) {
?>
                    Masters - <?php echo ucwords($_GET["p"])?>
<?php
  } else {
?>
                    Masters
<?php
  }
}
?>
                  </button>
               </div>
<?php
if ($_SESSION["UserType"]=="S") {
?>
                  <div class="dropdown-menu setdropdown" aria-labelledby="dropdownMenu1">
                     <button class="dropdown-item" type="button"><a href="index.php?p=mastertype&master=1">Master Type</a></button>
                     <button class="dropdown-item" type="button"><a href="index.php?p=region&master=1">Region</a></button>
                     <button class="dropdown-item" type="button"><a href="index.php?p=country&master=1">Country</a></button>
                     <button class="dropdown-item" type="button"><a href="index.php?p=state&master=1">State</a></button>
                     <button class="dropdown-item" type="button"><a href="index.php?p=race&master=1">Race</a></button>
                     <button class="dropdown-item" type="button"><a href="index.php?p=occupation&master=1">Occupation</a></button>
                     <button class="dropdown-item" type="button"><a href="index.php?p=education&master=1">Education</a></button>
                     <button class="dropdown-item" type="button"><a href="index.php?p=religion&master=1">Religion</a></button>
                     <button class="dropdown-item" type="button"><a href="index.php?p=nationality&master=1">Nationality</a></button>
                     <button class="dropdown-item" type="button"><a href="index.php?p=category&master=1">Category</a></button>
                     <button class="dropdown-item" type="button"><a href="index.php?p=courier&master=1">Courier</a></button>
                     <button class="dropdown-item" type="button"><a href="index.php?p=visit_reason&master=1">Visit Reason</a></button>
                     <button class="dropdown-item" type="button"><a href="index.php?p=dropout_reason&master=1">Dropout Reason</a></button>
                     <button class="dropdown-item" type="button"><a href="index.php?p=defective_reason&master=1">Defective Reason</a></button>
                  </div>
<?php
} else {
?>
                  <div class="dropdown-menu setdropdown" aria-labelledby="dropdownMenu1">
                     <button class="dropdown-item" type="button"><a href="index.php?p=franchise_product&master=1">Product</a></button>
                  </div>
<?php
}
?>
                <!-- </div> -->
<?php
}
?>
                <div class="dropdown" style="width: 100%; margin-bottom: 15px;">
                  <button class="btn btn-secondary dropdown-toggle yrbtn" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <?php echo $_SESSION["Year"]?>
                  </button>
                    <div class="dropdown-menu setdropdown" aria-labelledby="dropdownMenu2">
                        <?php
                        $current_year=date("Y");
                        $back_year=date("Y", strtotime("-5 year"));
                        $forward_year=date("Y", strtotime("+2 year"));
                        for ($i=$back_year; $i<=$forward_year; $i++) {
                            ?>
                            <button class="dropdown-item" type="button"><a href="javascript:doYear('<?php echo $i?>')"><?php echo $i?></a></button>
                            <?php
                        }
                        ?>
                    </div>
                </div>
              </div>
            </li><!---<br>------->
<?php
if (($_SESSION["UserType"]=="A") || ($_SESSION["UserType"]=="O")) {
  include_once("mnu_franchisee.php");
}

if ($_SESSION["UserType"]=="S") {
  include_once("mnu_HQ.php");
}
?>
          </ul>
        </nav>
        <div class="content-wrapper">
          <p style="display:flex;justify-content:space-between; padding-bottom:10px">
<?php
  switch ($_GET["p"]) {
  case "master" : include_once("admin/master.php"); break;
  case "centre" : include_once("admin/centre.php"); break;
  case "student" : include_once("admin/student.php"); break;
  case "student_reg" : include_once("admin/student_reg.php"); break;
  case "student_approval" : include_once('admin/student_approval.php'); break;
  case "course" : include_once("admin/course.php"); break;
  case "class" : include_once("admin/class.php"); break;
  case "allocation" : include_once("admin/allocation.php"); break;
  case "collection_pg1" : include_once("admin/collection_pg1.php"); break;
  case "collection" : include_once("admin/collection.php"); break;
  case "purchasing" : include_once("admin/purchasing.php"); break;
  case "stock" : include_once("admin/stock.php"); break;
  case "stock_bal" : include_once("admin/stock_bal.php"); break;
  case "sales" : include_once("admin/sales.php"); break;
  case "cn" : include_once("admin/cn.php"); break;
  case "group_creation" : include_once('admin/group_creation.php'); break;
  case "allocation_hq" : include_once('admin/allocation_hq.php'); break;
  case "stkadj" : include_once("admin/stock_adjustment.php"); break;
  case "student_population" : include_once ("admin/student_population.php"); break;
  case "order_status_pg1" : include_once("admin/order_status_pg1.php"); break;
  case "order_status" : include_once("admin/order_status.php"); break;
  case "defective_product" : include_once("admin/defective_product.php"); break;
  case "defective_status_pg1" : include_once("admin/defective_status_pg1.php"); break;
  case "defective_status" : include_once("admin/defective_status.php"); break;
  case "user" : include_once("admin/user.php"); break;
  case "order_placed" : include_once("admin/order_placed.php"); break;
  case "userright" : include_once("admin/user_right.php"); break;
  case "vendor" : include_once("admin/vendor.php"); break;
  case "product" : include_once("admin/product.php"); break;
  case "addon" : include_once("admin/addon_product.php"); break;
  case "visitor" : include_once("admin/visitor.php"); break;
  case "parent" : include_once("admin/parent.php"); break;
  case "visitor_qr_list" : include_once("admin/visitor_qr_list.php"); break;
  case "student_qr_list" : include_once("admin/student_qr_list.php"); break;
  case "visitor_hq" : include_once("admin/visitor_hq.php"); break;
  case "exp_contact" : include_once("admin/export_contact.php"); break;
  case "chgpass" : include_once("admin/change_password.php"); break;
//  case "login" : header("Location: index.php"); break;

  case "mastertype" : include_once("admin/mastertype.php"); break;
  case "country" : include_once("admin/country.php"); break;
  case "region" : include_once("admin/region.php"); break;
  case "state" : include_once("admin/state.php"); break;
  case "race" : include_once("admin/race.php"); break;
  case "occupation" : include_once("admin/occupation.php"); break;
  case "education" : include_once("admin/education.php"); break;
  case "religion" : include_once("admin/religion.php"); break;
  case "nationality" : include_once("admin/nationality.php"); break;
  case "category" : include_once("admin/category.php"); break;
  case "courier" : include_once("admin/courier.php"); break;
  case "checkout" : include_once("admin/checkout.php"); break;
  case "dashboard" : include_once("home.php"); break;
  case "rpt_visitor_registration" : include_once("admin/rptVisitorRegistration.php"); break;
  case "rpt_student_dropout" : include_once("admin/rptStudentDropout.php"); break;
  case "visit_reason" : include_once("admin/visit_reason.php"); break;
  case "dropout_reason" : include_once("admin/dropout_reason.php"); break;
  case "defective_reason" : include_once("admin/defective_reason.php"); break;
  case "dropout" : include_once("admin/dropout.php"); break;
  case "kiv" : include_once("admin/kiv.php"); break;

  case "rpt_centre_termly_stock" : include_once("admin/rptCentreTermly.php"); break;
  case "rpt_centre_subject_enrl" : include_once("admin/rptCentreSubjectEnroll.php"); break;
  case "rpt_sales" : include_once("admin/rptSales.php"); break;
  case "rpt_detail_sales" : include_once("admin/rptDetailSales.php"); break;
  case "rpt_void" : include_once("admin/rptVoid.php"); break;
  case "rpt_master" : include_once("admin/rptMasterReport.php"); break;
  case "rpt_franchise" : include_once("admin/rptFranchise.php"); break;
  case "rpt_unit_franchise" : include_once("admin/rptUnitFranchise.php"); break;
  case "rpt_accumulative_fee" : include_once("admin/rptAccumulativeFee.php"); break;
  case "rpt_outstanding" : include_once("admin/rptOutstanding.php"); break;
  case "student_info" : include_once("admin/student_info.php"); break;

  case "" : include_once("home.php"); break;
}
?>
          </p>
        </div> 
      </div>
	  <footer class="footer">
          <div class="container-fluid clearfix">
            <!-- Copyright info comes here -->
          </div>
      </footer>
    </div>
  </div>
<?php
} else {
  include_once("login.php");
}
?>
<div id="dlgViewCart"></div>
<script src="lib/simplebar/simplebar.js"></script>
<!-- <script src="https://unpkg.com/simplebar@latest/dist/simplebar.min.js"></script> -->
<script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>
<script>
    //new SimpleBar($('#simplebar')[0], { autoHide: false });

    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })
</script>
<script>

    // Courier

    /*$('#courier').on("change", function () {
        let selected_option = $('#courier option:selected').text();
        if(selected_option == "Transporter") {
            $('#remark-input').removeClass('d-none');
        } else {
            $('#remark-input').addClass('d-none');
        }
    });*/

    let tools = $('.qdees-tools');
    let point = $('.qdees-point');
    let order = $('.qdees-order');
    let defective = $('.qdees-defective');
    let settings = $('.qdees-settings');
    let reporting = $('.qdees-reporting');
    let inventory = $('.qdees-inventory');

    sidebarSlide($('.qdees-toggle'), tools);
    sidebarSlide($('.qdees-toggle-1'), point);
    sidebarSlide($('.qdees-toggle-2'), order);
    sidebarSlide($('.qdees-toggle-3'), defective);
    sidebarSlide($('.qdees-toggle-4'), settings);
    sidebarSlide($('.qdees-toggle-5'), reporting);
    sidebarSlide($('.qdees-toggle-6'), inventory);

    function sidebarSlide(pointer, navigation) {
        pointer.on('click', function() {
            if(navigation.hasClass('q-activated')) {
                navigation.slideUp(400);
                navigation.removeClass('q-activated');
            } else {
                navigation.slideDown(400);
                navigation.addClass('q-activated');
            }
        });
    }
</script>
</body>
</html>
