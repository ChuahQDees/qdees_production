<html lang="en">

<head>
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

    <script src="../lib/uikit/js/jquery.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="../lib/uikit/js/jquery.quickValidate.min.js"></script>
    <script src="../lib/uikit/js/prettify.js"></script>
    <script src="../lib/uikit/js/uikit.min.js"></script>
    <script src="../lib/uikit/js/core/core.min.js"></script>
    <script src="../lib/uikit/js/core/button.min.js"></script>
    <script src="../lib/uikit/js/core/alert.min.js"></script>
    <script src="../lib/uikit/js/core/nav.min.js"></script>
    <script src="../lib/uikit/js/components/form-select.min.js"></script>
    <script src="../lib/uikit/js/components/accordion.min.js"></script>
    <script src="../lib/uikit/js/components/autocomplete.min.js"></script>
    <script src="../lib/uikit/js/components/tooltip.min.js"></script>
    <script src="../lib/uikit/js/components/pagination.min.js"></script>
    <script src="../lib/uikit/js/components/lightbox.min.js"></script>
    <script src="../lib/uikit/js/components/sticky.min.js"></script>

    <script src="../lib/node_modules/popper.js/dist/umd/popper.min.js"></script>
    <script src="../lib/node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="../lib/node_modules/chart.js/dist/Chart.min.js"></script>
    <script src="../lib/node_modules/jquery-sparkline/jquery.sparkline.min.js"></script>
    <script src="../lib/node_modules/flot/jquery.flot.js"></script>
    <script src="../lib/node_modules/flot/jquery.flot.resize.js"></script>
    <script src="../lib/node_modules/flot.curvedlines/curvedLines.js"></script>
    <script src="../lib/js/off-canvas.js"></script>
    <script src="../lib/js/hoverable-collapse.js"></script>
    <script src="../lib/js/misc.js"></script>
    <script src="../lib/js/todolist.js"></script>
    <script src="../lib/js/tooltips.js"></script>
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

.kbw-signature {
    width: 400px;
    height: 150px;
    border: 1px solid #999
}

#frmdeclaration2 {}
</style>

</head>

<body style="height:auto;" id="thebody">

<?php 

    $current_date = date('Y-m-d');

    include_once("../mysql.php");
    
    $declaration_id = $_GET['declaration_id'];
    $centre_code = $_GET['CentreCode'];
    $monthyear = $_GET['monthyear'];
    $_GET['id'] = $_GET['declaration_id'];
    $mode = $_GET['mode'] = 'EDIT';

    include_once("functions.php");

    $sha_id=sha1($_GET["id"]);
    $sql="SELECT * from `declaration` where sha1(id)='$sha_id'";
    $result=mysqli_query($connection, $sql);
    $row_edit=mysqli_fetch_assoc($result);
    $master_id=$row_edit["id"];
    $year = $row_edit["year"];
    $month = $row_edit["month"];
    $submited_date = $row_edit["submited_date"];
    $timestamp = strtotime($submited_date);
    $new_date_format = date('Y-m-d', $timestamp);
    $submited_date = convertDate2British($new_date_format);
 
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
    $sql = "SELECT * from centre where centre_code='$centre_code' ";
    $result = mysqli_query($connection, $sql); 
    while ($row = mysqli_fetch_assoc($result)) {
        $operator_name = $row["operator_name"];
        $address1 = $row["address1"];
        $created_date = $row["created_date"];
        $today_date = date("Y-m-d h:i");
    }
    $sha_id = $row_edit['id'];
?>


<div id="print_1" class="uk-margin-right uk-margin-left">

    <div class="uk-width-1-1 myheader">
        <h2 class="uk-text-center myheader-text-color">DECLARATION OF Q-DEES AND PAYMENTS REPORT</h2>
    </div>
    <div class="uk-form uk-form-small">

        <form name="frmdeclaration" id="frmdeclaration_id" method="post"
            action="" enctype="multipart/form-data">
            <div id="frmdeclaration">
                <div class="uk-grid">
                    <div class="uk-width-medium-4-10">
                        
                        <table class="uk-table">
                            <tbody>
                                <tr>
                                    <td class="uk-text-bold">Centre Name:</td>
                                    <td><?php echo getCentreName($centre_code); ?></td>

                                </tr>
                                <tr>
                                    <td class="uk-text-bold">Centre Address:</td>
                                    <td><?php echo $address1; ?></td>
                                </tr>
                                <tr>
                                    <td colspan="5" class="uk-text-bold">Form: 1</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="uk-width-medium-4-10">
                        <table class="uk-table">
                            <tr>
                                <td class="uk-text-bold">Month/Year:</td>
                                <td><?php echo date("F", mktime(0, 0, 0, $month, 10)); ?>/<?php echo $year; ?></td>
                            </tr>
                            <tr>
                                <td class="uk-text-bold">Date of Submission:</td>
                                <td> <?php echo $submited_date; ?></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div style="padding-left: 0px; padding-right: 0px;" class="uk-width-medium-2-10">
                     
                    </div>
                </div>
                <div class="uk-grid">
                    <div class="uk-width-medium-10-10">
                        <table class="uk-table uk-table-small">
                            <tr>
                                <td colspan="5" style="font-size:18px; font-weight:bold;">CALCULATION OF GROSS TURNOVER
                                </td>
                            </tr>
                            <tr>
                                <td class="uk-width-6-10" style="font-size:16px; font-weight:bold; border:none;">A:
                                    School Fees <br>Foundation Programme</td>
                                <td style="border:none;text-align: center;" class="uk-width-1-10">
                                    <span>Active student</span>
                                </td>

                                <td style="border:none;text-align: center;" class="uk-width-1-10">
                                    <span>Centre Adjusted Fee<br class="bru">RM</span>
                                </td>
                                
                                <td style="border:none;text-align: center;" class="uk-width-1-10">
                                    <span>Total <br class="bru">RM</span>
                                </td>
                            </tr>

                            <tr class="">
                                <td style=" margin-top:50px;border:none; font-size:18px;"
                                    class="uk-width-6-10 uk-text-bold">(i) School Fees</td>
                            </tr>
                            <tr class="">
                                <td style=" margin-top:20px;border:none; font-size:16px;"
                                    class="uk-width-6-10 uk-text-bold">EDP:</td>
                            </tr>
                            <?php
                                $sql="SELECT programme_package as fees_structure, active_student, fee_rate as school_adjust, amount from `declaration_child` where master_id=$master_id and form='Form1' and fee_structure_mame='School Fees' and subject='EDP'";
                            
                                $result=mysqli_query($connection, $sql);
                                $num_row = mysqli_num_rows($result);
                                $total_EDP_student = 0;
                                $total_EDP_school_adjust=0;
							    if ($num_row>0) {
								    while ($row=mysqli_fetch_assoc($result)) {
						    ?>
                                        <tr class="">
                                            <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">
                                                <?php echo $row['fees_structure']?><span class="text-danger"></span>:</td>
                                            </td>
                                            <td style="border:none; text-align:center; white-space:nowrap;" class="uk-width-1-10">
                                                <?php 
                                                    $level_count = $row['active_student'];
                                                    $total_EDP_school_adjust += $level_count * $row['school_adjust'];
                                                    $total_EDP_student += $level_count;
                                                ?>
                                                <input class="edp_tsd edp_tsdt" type="number" step="0.01" name="active_student[]"
                                                    id="active_student" value="<?php echo $level_count ?>" readonly> <span
                                                    class="edp_eq">✕ </span>
                                            </td>
                                            <td style="border:none; white-space:nowrap" class="uk-width-1-10">
                                                <input class="edp_tsd edp_tsdt" type="number" name="fee_rate[]" id="fee_rate"
                                                    value="<?php echo $row['school_adjust']?>" readonly> <span class="edp_eq2">=
                                                </span>
                                            </td>
                                            <td style="border:none; text-align:center;" class="uk-width-1-10">
                                                <input class="school_adjust" type="number" step="0.01" name="amount[]" id="amount"
                                                    value="<?php echo number_format((float)$level_count * $row['school_adjust'], 2, '.', '');  ?>" readonly><br>
                                            </td>
                                        </tr>
                            <?php 
							        }
								}
						    ?>
                                <tr class="">
                                    <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                        class="uk-width-1-10">Total Student</td>
                                    <td class="uk-width-1-10"><input class="total_s" type="number" step="0.01"
                                            name="school_total_f" id="school_total_f"
                                            value="<?php echo $total_EDP_student;  ?>" readonly></td>
                                    <td style="text-align:center;" class="uk-width-1-10">
                                        <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                            placeholder="Total EDP" readonly> <span style="font-size:14px;"
                                            class="edp_eq">RM</span>
                                    </td>
                                    <td style="text-align:center;" class="uk-width-1-10">
                                        <input class="total_a" type="number" step="0.01" name="school_total_f"
                                            id="school_total_f" value="<?php echo number_format((float)$total_EDP_school_adjust, 2, '.', '');  ?>" readonly>
                                    </td>
                                </tr>
                                <tr class="">
                                    <td style=" margin-top:20px;border:none; font-size:16px;"
                                        class="uk-width-6-10 uk-text-bold">QF1:</td>
                                </tr>
                            <?php
                                $sql="SELECT programme_package as fees_structure, active_student, fee_rate as school_adjust, amount from `declaration_child` where master_id=$master_id and form='Form1' and fee_structure_mame='School Fees' and subject='QF1'";

                                $result1=mysqli_query($connection, $sql);
                                $num_row = mysqli_num_rows($result1);
                                $total_QF1_school_adjust = 0;
                                $total_QF1_student = 0;
                                if ($num_row>0) {
                                    while ($row=mysqli_fetch_assoc($result1)) {
						    ?>
                                        <tr class="">
                                            <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">
                                                <?php echo $row['fees_structure']?><span class="text-danger"></span>:</td>
                                            </td>
                                            <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                            <?php 
                                                $level_count = $row['active_student'];
                                                $total_QF1_school_adjust +=  $row['amount'];
                                                $total_QF1_student += $level_count;
                                            ?>
                                                <input class="edp_tsd edp_tsdq1" type="number" step="0.01" name="active_student[]"
                                                    id="active_student" value="<?php echo $level_count ?>" readonly> <span
                                                    class="edp_eq">✕ </span>
                                            </td>
                                            <td style="border:none; white-space:nowrap;" class="uk-width-1-10">
                                                <input class="edp_tsd edp_tsdq1" type="number" name="fee_rate[]" id="fee_rate"
                                                    value="<?php echo $row['school_adjust']?>" readonly> <span class="edp_eq2">=
                                                </span>
                                            </td>
                                            <td style="border:none; text-align:center;" class="uk-width-1-10">
                                                <input class="school_adjust" type="number" step="0.01" name="amount[]" id="amount"
                                                    value="<?php echo number_format((float)$level_count * $row['school_adjust'], 2, '.', '');  ?>" readonly><br>
                                            </td>
                                        </tr>
                            <?php 
								    }
							    }
						    ?>

                            <tr class="">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total Student</td>
                                <td class="uk-width-1-10"><input class="total_s" type="number" step="0.01"
                                        name="school_total_f" id="school_total_f"
                                        value="<?php echo $total_QF1_student;  ?>" readonly></td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total QF1" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo number_format((float)$total_QF1_school_adjust, 2, '.', '');  ?>" readonly>
                                </td>
                            </tr>
                            <tr class="">
                                <td style=" margin-top:20px;border:none; font-size:16px;"
                                    class="uk-width-6-10 uk-text-bold">QF2:</td>
                            </tr>
                            <?php
                                $sql="SELECT programme_package as fees_structure, active_student, fee_rate as school_adjust, amount from `declaration_child` where master_id=$master_id and form='Form1' and fee_structure_mame='School Fees' and subject='QF2'";
						
                                $result2=mysqli_query($connection, $sql);
                                $num_row = mysqli_num_rows($result2);
                                $total_QF2_school_adjust = 0;
                                $total_QF2_student = 0;
                                if ($num_row>0) {
                                    while ($row=mysqli_fetch_assoc($result2)) {
						    ?>
                                        <tr class="">
                                            <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">
                                                <?php echo $row['fees_structure']?><span class="text-danger"></span>:</td>
                                            </td>
                                            <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                                <?php 
                                                    $level_count = $row['active_student'];
                                                    $total_QF2_school_adjust +=  $row['amount'];
                                                    $total_QF2_student += $level_count;
                                                ?>
                                                <input class="edp_tsd edp_tsdq2" type="number" step="0.01" name="active_student[]" id="active_student" value="<?php echo $level_count ?>" readonly> <span class="edp_eq">✕ </span>
                                            </td>
                                            <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                                <input class="edp_tsd edp_tsdq2" type="number" name="fee_rate[]" id="fee_rate"
                                                    value="<?php echo $row['school_adjust']?>" readonly> <span class="edp_eq2">=
                                                </span>
                                            </td>
                                            <td style="border:none; text-align:center;" class="uk-width-1-10">
                                                <input class="school_adjust" type="number" step="0.01" name="amount[]" id="amount"
                                                    value="<?php echo number_format((float)$level_count * $row['school_adjust'], 2, '.', '');  ?>" readonly><br>
                                            </td>
                                        </tr>
                            <?php 
                                    }
                                }
                            ?>
                            <tr class="">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total Student</td>
                                <td class="uk-width-1-10"><input class="total_s" type="number" step="0.01" name="school_total_f" id="school_total_f" value="<?php echo $total_QF2_student;  ?>" readonly></td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total QF2" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo number_format((float)$total_QF2_school_adjust, 2, '.', '');  ?>" readonly>
                                </td>
                            </tr>
                            <tr class="">
                                <td style=" margin-top:20px;border:none; font-size:16px;"
                                    class="uk-width-6-10 uk-text-bold">QF3:</td>
                            </tr>
                            <?php
                                $sql="SELECT programme_package as fees_structure, active_student, fee_rate as school_adjust, amount from `declaration_child` where master_id=$master_id and form='Form1' and fee_structure_mame='School Fees' and subject='QF3'";
				
                                $result3=mysqli_query($connection, $sql);
                                $num_row = mysqli_num_rows($result3);
                                $total_QF3_school_adjust = 0;
                                $total_QF3_student = 0;

                                if ($num_row>0) {
                                    while ($row=mysqli_fetch_assoc($result3)) {
						    ?>
                                        <tr class="">
                                            <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">
                                                <?php echo $row['fees_structure']?><span class="text-danger"></span>:</td>
                                            </td>
                                            <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                            <?php 
                                                $level_count = $row['active_student'];
                                                $total_QF3_school_adjust +=  $row['amount'];
                                                $total_QF3_student += $level_count;
                                            ?>
                                                <input class="edp_tsd edp_tsdq3" type="number" step="0.01" name="active_student[]" id="active_student" value="<?php echo $level_count ?>" readonly> <span class="edp_eq">✕ </span>
                                            </td>
                                            <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                                <input class="edp_tsd edp_tsdq3" type="number" name="fee_rate[]" id="fee_rate"
                                                    value="<?php echo $row['school_adjust']?>" readonly> <span class="edp_eq2">=
                                                </span>
                                            </td>
                                            <td style="border:none; text-align:center;" class="uk-width-1-10">
                                                <input class="school_adjust" type="number" step="0.01" name="amount[]" id="amount"
                                                    value="<?php echo number_format((float)$level_count * $row['school_adjust'], 2, '.', '');  ?>" readonly><br>
                                            </td>
                                        </tr>
                            <?php 
                                    }
                                }
                            ?>
                            <tr class="">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total Student</td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo $total_QF3_student;  ?>" readonly>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total QF3" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo number_format((float)$total_QF3_school_adjust, 2, '.', '');  ?>" readonly>
                                </td>
                            </tr>

                            <tr class="">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total Student (i)</td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" type="number" step="0.01" name="school_total_f" id="school_total_f" value="<?php echo $total_student_school += $total_EDP_student+$total_QF1_student+$total_QF2_student+$total_QF3_student; $total_student_a += $total_student_school; ?>"
                                        readonly>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total (i)" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f"
                                        value="<?php  $total_school_adjust = $total_EDP_school_adjust + $total_QF1_school_adjust  + $total_QF2_school_adjust + $total_QF3_school_adjust; 
                                        echo number_format((float)$total_school_adjust, 2, '.', ''); ?>"
                                        readonly>
                                </td>
                            </tr>
                            <?php $total_a += $total_school_adjust; ?>

                            <tr class="">
                                <td style=" margin-top:50px;border:none; font-size:18px;"
                                    class="uk-width-6-10 uk-text-bold">(ii) Multimedia Fees</td>
                            </tr>
                            <tr class="">
                                <td style=" margin-top:20px;border:none; font-size:16px;"
                                    class="uk-width-6-10 uk-text-bold">EDP:</td>
                            </tr>
                            <?php
                                $sql="SELECT programme_package as fees_structure, active_student, fee_rate as multimedia_adjust, amount
                                from `declaration_child` where master_id=$master_id and form='Form1' and fee_structure_mame='Multimedia' and subject='EDP'";
					
                                $result=mysqli_query($connection, $sql);
                                $num_row = mysqli_num_rows($result);
                                $total_EDP_multimedia_adjust = 0;
                                $total_EDP_multimedia_student = 0;
                                if ($num_row>0) {
                                    while ($row=mysqli_fetch_assoc($result)) {
						    ?>
                                        <tr class="">
                                            <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">
                                                <?php echo $row['fees_structure']?><span class="text-danger"></span>:</td>
                                            <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                            <?php
                                                $level_count = $row['active_student'];
                                                $total_EDP_multimedia_adjust +=  $row['amount'];
                                                $total_EDP_multimedia_student += $level_count;
                                            ?>
                                                <input class="edp_tsd multimedia" type="number" step="0.01" name="active_student[]"
                                                    id="active_student" value="<?php echo $level_count ?>" readonly> <span
                                                    class="edp_eq">✕ </span>
                                            </td>
                                            <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                                <input class="edp_tsd multimedia" type="number" name="fee_rate[]" id="fee_rate"
                                                    value="<?php echo $row['multimedia_adjust']?>" readonly> <span class="edp_eq2">=
                                                </span>
                                            </td>
                                            <td style="border:none; text-align:center;" class="uk-width-1-10">
                                                <input class="school_adjust" type="number" step="0.01" name="amount[]" id="amount"
                                                    value="<?php echo number_format((float)$level_count * $row['multimedia_adjust'], 2, '.', '');  ?>" readonly><br>
                                            </td>
                                        </tr>
                            <?php
							        }
							    }
						    ?>
                            <tr class="">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total Student</td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo $total_EDP_multimedia_student;  ?>"
                                        readonly>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total EDP" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo number_format((float)$total_EDP_multimedia_adjust, 2, '.', '');  ?>"
                                        readonly>
                                </td>
                            </tr>

                            <tr class="">
                                <td style=" margin-top:20px;border:none; font-size:16px;"
                                    class="uk-width-6-10 uk-text-bold">QF1:</td>
                            </tr>
                            <?php
                                $sql="SELECT programme_package as fees_structure, active_student, fee_rate as multimedia_adjust, amount from `declaration_child` where master_id=$master_id and form='Form1' and fee_structure_mame='Multimedia' and subject='QF1'";
							
                                $result=mysqli_query($connection, $sql);
                                $num_row = mysqli_num_rows($result);
                                $total_QF1_multimedia_adjust = 0;
                                $total_QF1_multimedia_student = 0;
                                if ($num_row>0) {
                                    while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                                        <tr class="">
                                            <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">
                                                <?php echo $row['fees_structure']?><span class="text-danger"></span>:</td>
                                            
                                            <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                            <?php
                                                $level_count = $row['active_student'];
                                                $total_QF1_multimedia_adjust +=  $row['amount'];
                                                $total_QF1_multimedia_student += $level_count;
                                            ?>
                                                <input class="edp_tsd multimedia" type="number" step="0.01" name="active_student[]"
                                                    id="active_student" value="<?php echo $level_count ?>" readonly> <span
                                                    class="edp_eq">✕ </span>
                                            </td>
                                            <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                                <input class="edp_tsd multimedia" type="number" name="fee_rate[]" id="fee_rate"
                                                    value="<?php echo $row['multimedia_adjust']?>" readonly> <span class="edp_eq2">=
                                                </span>
                                            </td>
                                            <td style="border:none; text-align:center;" class="uk-width-1-10">
                                                <input class="school_adjust" type="number" step="0.01" name="amount[]" id="amount"
                                                    value="<?php echo number_format((float)$level_count * $row['multimedia_adjust'], 2, '.', '');  ?>" readonly><br>
                                            </td>
                                        </tr>
                            <?php
							        }
							    }
						    ?>
                            <tr class="">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total Student</td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo $total_QF1_multimedia_student;  ?>"
                                        readonly>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total QF1" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo number_format((float)$total_QF1_multimedia_adjust, 2, '.', '');  ?>"
                                        readonly>
                                </td>
                            </tr>

                            <tr class="">
                                <td style=" margin-top:20px;border:none; font-size:16px;"
                                    class="uk-width-6-10 uk-text-bold">QF2:</td>
                            </tr>
                            <?php
                                $sql="SELECT programme_package as fees_structure, active_student, fee_rate as multimedia_adjust, amount from `declaration_child` where master_id=$master_id and form='Form1' and fee_structure_mame='Multimedia' and subject='QF2'";
							
                                $result=mysqli_query($connection, $sql);
                                $num_row = mysqli_num_rows($result);
                        
                                $total_QF2_multimedia_adjust = 0; 
                                $total_QF2_multimedia_student = 0;
                                if ($num_row>0) {
                                    while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                                        <tr class="">
                                            <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">
                                                <?php echo $row['fees_structure']?><span class="text-danger"></span>:</td>
                                            
                                            <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                            <?php
                                                $level_count = $row['active_student'];
                                                $total_QF2_multimedia_adjust +=  $row['amount'];
                                                $total_QF2_multimedia_student += $level_count;
                                            ?>
                                                <input class="edp_tsd multimedia" type="number" step="0.01" name="active_student[]" id="active_student" value="<?php echo $level_count ?>" readonly> <span class="edp_eq">✕ </span>
                                            </td>
                                            <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                                <input class="edp_tsd multimedia" type="number" name="fee_rate[]" id="fee_rate"
                                                    value="<?php echo $row['multimedia_adjust']?>" readonly> <span class="edp_eq2">=
                                                </span>
                                            </td>
                                            <td style="border:none; text-align:center;" class="uk-width-1-10">
                                                <input class="school_adjust" type="number" step="0.01" name="amount[]" id="amount"
                                                    value="<?php echo number_format((float)$level_count * $row['multimedia_adjust'], 2, '.', '');  ?>" readonly><br>
                                            </td>
                                        </tr>
                            <?php
							        }
							    }
						    ?>
                            <tr class="">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total Student</td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo $total_QF2_multimedia_student;  ?>"
                                        readonly>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total QF2" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo number_format((float)$total_QF2_multimedia_adjust, 2, '.', '');  ?>"
                                        readonly>
                                </td>
                            </tr>
                           
                            <tr class="">
                                <td style=" margin-top:20px;border:none; font-size:16px;"
                                    class="uk-width-6-10 uk-text-bold">QF3:</td>
                            </tr>
                            <?php
                                $sql="SELECT programme_package as fees_structure, active_student, fee_rate as multimedia_adjust, amount from `declaration_child` where master_id=$master_id and form='Form1' and fee_structure_mame='Multimedia' and subject='QF3'";
							
                                $result=mysqli_query($connection, $sql);
                                $num_row = mysqli_num_rows($result);
                                $total_QF3_multimedia_adjust = 0;
                                $total_QF3_multimedia_student = 0;

                                if ($num_row>0) {
                                    while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                                        <tr class="">
                                            <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">
                                                <?php echo $row['fees_structure']?><span class="text-danger"></span>:</td>
                                            
                                            <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                            <?php
                                                $level_count = $row['active_student'];
                                                $total_QF3_multimedia_adjust +=  $row['amount'];
                                                $total_QF3_multimedia_student += $level_count;
                                            ?>
                                                <input class="edp_tsd multimedia" type="number" step="0.01" name="active_student[]"
                                                    id="active_student" value="<?php echo $level_count ?>" readonly> <span
                                                    class="edp_eq">✕ </span>
                                            </td>
                                            <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                                <input class="edp_tsd multimedia" type="number" name="fee_rate[]" id="fee_rate"
                                                    value="<?php echo $row['multimedia_adjust']?>" readonly> <span class="edp_eq2">=
                                                </span>
                                            </td>
                                            <td style="border:none; text-align:center;" class="uk-width-1-10">
                                                <input class="school_adjust" type="number" step="0.01" name="amount[]" id="amount"
                                                    value="<?php echo number_format((float)$level_count * $row['multimedia_adjust'], 2, '.', '');  ?>" readonly><br>
                                            </td>
                                        </tr>
                            <?php
							        }
							    }
						    ?>
                            <tr class="">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total Student</td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo $total_QF3_multimedia_student;  ?>"
                                        readonly>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total QF3" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo number_format((float)$total_QF3_multimedia_adjust, 2, '.', '');  ?>"
                                        readonly>
                                </td>
                            </tr>

                            <tr class="">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total Student (ii)</td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo $total_student_multimedia += $total_EDP_multimedia_student + $total_QF1_multimedia_student + $total_QF2_multimedia_student + $total_QF3_multimedia_student;  
                                $total_student_a += $total_student_multimedia;?>" readonly>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total (ii)" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f"
                                        value="<?php  $total_multimedia_adjust = $total_EDP_multimedia_adjust + $total_QF1_multimedia_adjust  + $total_QF2_multimedia_adjust + $total_QF3_multimedia_adjust; 
                                        echo number_format((float)$total_multimedia_adjust, 2, '.', '') ?>"
                                        readonly>
                                </td>
                                <?php $total_a += $total_multimedia_adjust; ?>
                            </tr>
                            <tr class="">
                                <td style=" margin-top:50px;border:none;"></td>
                            </tr>
                            <tr class="">
                                <td style=" margin-top:50px;border:none; font-size:18px;"
                                    class="uk-width-6-10 uk-text-bold">(iii) Afternoon Programme Fees</td>
                            </tr>

                            <tr class="">
                                <td style=" margin-top:20px;border:none; font-size:16px;"
                                    class="uk-width-6-10 uk-text-bold">EDP:</td>
                            </tr>
                            <?php
                                $sql="SELECT programme_package as fees_structure, active_student, fee_rate as basic_adjust, amount from `declaration_child` where master_id=$master_id and form='Form1' and fee_structure_mame='Afternoon Programme Fees' and subject='EDP'";
							
							$result=mysqli_query($connection, $sql);
							$num_row = mysqli_num_rows($result);
                           
                            $total_EDP_basic_adjust = 0;
                            $total_EDP_basic_student = 0;
							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                            <tr class="">
                                <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">
                                    <?php echo $row['fees_structure']?><span class="text-danger"></span>:</td>
                                <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                <?php
                                    $level_count = $row['active_student'];
                                    $total_EDP_basic_adjust +=  $row['amount'];
                                    $total_EDP_basic_student += $level_count;
                                ?>
                                    <input class="edp_tsd multimedia" type="number" step="0.01" name="active_student[]"
                                        id="active_student" value="<?php echo $level_count ?>" readonly> <span
                                        class="edp_eq">✕ </span>
                                </td>
                                <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd multimedia" type="number" name="fee_rate[]" id="fee_rate"
                                        value="<?php echo $row['basic_adjust']?>" readonly> <span class="edp_eq2">=
                                    </span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust" type="number" step="0.01" name="amount[]" id="amount"
                                        value="<?php echo number_format((float)$level_count * $row['basic_adjust'], 2, '.', '');  ?>" readonly><br>
                                </td>
                            </tr>
                            <?php
							}
							}
						?>
                            <tr class="">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total Student</td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo $total_EDP_basic_student;  ?>" readonly>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total EDP" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo number_format((float)$total_EDP_basic_adjust, 2, '.', '');  ?>" readonly>
                                </td>
                            </tr>

                            <tr class="">
                                <td style=" margin-top:20px;border:none; font-size:16px;"
                                    class="uk-width-6-10 uk-text-bold">QF1:</td>
                            </tr>
                            <?php
                                $sql="SELECT programme_package as fees_structure, active_student, fee_rate as basic_adjust, amount from `declaration_child` where master_id=$master_id and form='Form1' and fee_structure_mame='Afternoon Programme Fees' and subject='QF1'";
							
							$result=mysqli_query($connection, $sql);
							$num_row = mysqli_num_rows($result);
                          
							$total_QF1_basic_adjust = 0;
                            $total_QF1_basic_student = 0;
							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                            <tr class="">
                                <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">
                                    <?php echo $row['fees_structure']?><span class="text-danger"></span>:</td>
                                
                                <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                    <?php
                                        $level_count = $row['active_student'];
                                        $total_QF1_basic_adjust +=  $row['amount'];
                                        $total_QF1_basic_student += $level_count;
                                    ?>
                                    <input class="edp_tsd multimedia" type="number" step="0.01" name="active_student[]"
                                        id="active_student" value="<?php echo $level_count ?>" readonly> <span
                                        class="edp_eq">✕ </span>
                                </td>
                                <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd multimedia" type="number" name="fee_rate[]" id="fee_rate"
                                        value="<?php echo $row['basic_adjust']?>" readonly> <span class="edp_eq2">=
                                    </span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust" type="number" step="0.01" name="amount[]" id="amount"
                                        value="<?php echo number_format((float)$level_count * $row['basic_adjust'], 2, '.', '');  ?>" readonly><br>
                                </td>
                            </tr>
                            <?php
							}
							}
						?>
                            <tr class="">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total Student</td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo $total_QF1_basic_student;  ?>" readonly>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total QF1" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo number_format((float)$total_QF1_basic_adjust, 2, '.', '') ;  ?>" readonly>
                                </td>
                            </tr>
                            <tr class="">
                                <td style=" margin-top:20px;border:none; font-size:16px;"
                                    class="uk-width-6-10 uk-text-bold">QF2:</td>
                            </tr>
                            <?php
                                $sql="SELECT programme_package as fees_structure, active_student, fee_rate as basic_adjust, amount from `declaration_child` where master_id=$master_id and form='Form1' and fee_structure_mame='Afternoon Programme Fees' and subject='QF2'";
							
							$result=mysqli_query($connection, $sql);
							$num_row = mysqli_num_rows($result);
                            //$total_student = 0;
							$total_QF2_basic_adjust = 0;
                            $total_QF2_basic_student = 0;
							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                            <tr class="">
                                <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">
                                    <?php echo $row['fees_structure']?><span class="text-danger"></span>:</td>
                                
                                <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                    <?php
                                        $level_count = $row['active_student'];
                                        $total_QF2_basic_adjust +=  $row['amount'];
                                        $total_QF2_basic_student += $level_count;
                                    ?>
                                    <input class="edp_tsd multimedia" type="number" step="0.01" name="active_student[]"
                                        id="active_student" value="<?php echo $level_count ?>" readonly> <span
                                        class="edp_eq">✕ </span>
                                </td>
                                <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd multimedia" type="number" name="fee_rate[]" id="fee_rate"
                                        value="<?php echo $row['basic_adjust']?>" readonly> <span class="edp_eq2">=
                                    </span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust" type="number" step="0.01" name="amount[]" id="amount"
                                        value="<?php echo number_format((float)$level_count * $row['basic_adjust'], 2, '.', '');  ?>" readonly><br>
                                </td>
                            </tr>
                            <?php
							}
							}
						?>
                            <tr class="">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total Student</td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo $total_QF2_basic_student;  ?>" readonly>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total QF2" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo number_format((float)$total_QF2_basic_adjust, 2, '.', '');  ?>" readonly>
                                </td>
                            </tr>

                            <tr class="">
                                <td style=" margin-top:20px;border:none; font-size:16px;"
                                    class="uk-width-6-10 uk-text-bold">QF3:</td>
                            </tr>
                            <?php
                                $sql="SELECT programme_package as fees_structure, active_student, fee_rate as basic_adjust, amount from `declaration_child` where master_id=$master_id and form='Form1' and fee_structure_mame='Afternoon Programme Fees' and subject='QF3'";
							
							$result=mysqli_query($connection, $sql);
							$num_row = mysqli_num_rows($result);
                           
							$total_QF3_basic_adjust = 0;
                            $total_QF3_basic_student = 0;
							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                            <tr class="">
                                <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">
                                    <?php echo $row['fees_structure']?><span class="text-danger"></span>:</td>
                                
                                <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                    <?php
                                        $level_count = $row['active_student'];
                                        $total_QF3_basic_adjust +=  $row['amount'];
                                        $total_QF3_basic_student += $level_count;
                                    ?>
                                    <input class="edp_tsd multimedia" type="number" step="0.01" name="active_student[]"
                                        id="active_student" value="<?php echo $level_count ?>" readonly> <span
                                        class="edp_eq">✕ </span>
                                </td>
                                <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd multimedia" type="number" name="fee_rate[]" id="fee_rate"
                                        value="<?php echo $row['basic_adjust']?>" readonly> <span class="edp_eq2">=
                                    </span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust" type="number" step="0.01" name="amount[]" id="amount"
                                        value="<?php echo number_format((float)$level_count * $row['basic_adjust'], 2, '.', '');  ?>" readonly><br>
                                </td>
                            </tr>
                            <?php
							}
							}
						?>
                            <tr class="">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total Student</td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo $total_QF3_basic_student;  ?>" readonly>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total QF3" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo number_format((float)$total_QF3_basic_adjust, 2, '.', '') ;  ?>" readonly>
                                </td>
                            </tr>

                            <tr class="">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total Student (iii)</td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo $total_student_basic += $total_EDP_basic_student + $total_QF1_basic_student+ $total_QF2_basic_student+ $total_QF3_basic_student;  
                                $total_student_a += $total_student_basic;?>" readonly>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total (iii)" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f"
                                        value="<?php  $total_basic_adjust = $total_EDP_basic_adjust + $total_QF1_basic_adjust  + $total_QF2_basic_adjust + $total_QF3_basic_adjust;
                                        echo number_format((float)$total_basic_adjust, 2, '.', '')  ?>"
                                        readonly>
                                    <?php $total_a += $total_basic_adjust; ?>
                                </td>
                            </tr>
                            <tr class="mobile_app_fee">
                                <td style=" margin-top:50px;border:none; font-size:18px;"
                                    class="uk-width-6-10 uk-text-bold">(iv) Mobile App Fees</td>
                            </tr>

                            <tr class="mobile_app_fee" >
                                <td style=" margin-top:20px;border:none; font-size:16px;"
                                    class="uk-width-6-10 uk-text-bold">EDP:</td>
                            </tr>
                            <?php
                                $sql="SELECT programme_package as fees_structure, active_student, fee_rate as mobile_adjust, amount from `declaration_child` where master_id=$master_id and form='Form1' and fee_structure_mame='Mobile App Fees' and subject='EDP'";
							
							$result=mysqli_query($connection, $sql);
							$num_row = mysqli_num_rows($result);
                            
                            $total_EDP_mobile_adjust = 0;
                            $total_EDP_mobile_student = 0;
							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                            <tr class="mobile_app_fee">
                                <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">
                                    <?php echo $row['fees_structure']?><span class="text-danger"></span>:</td>
                                
                                <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                    <?php
                                        $level_count = $row['active_student'];
                                        $total_EDP_mobile_adjust +=  $row['amount'];
                                        $total_EDP_mobile_student += $level_count;
                                    ?>
                                    <input class="edp_tsd multimedia" type="number" step="0.01" name="active_student[]"
                                        id="active_student" value="<?php echo $level_count ?>" readonly> <span
                                        class="edp_eq">✕ </span>
                                </td>
                                <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd multimedia" type="number" name="fee_rate[]" id="fee_rate"
                                        value="<?php echo $row['mobile_adjust']?>" readonly> <span class="edp_eq2">=
                                    </span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust" type="number" step="0.01" name="amount[]" id="amount"
                                        value="<?php echo number_format((float)$level_count * $row['mobile_adjust'], 2, '.', '') ;  ?>" readonly><br>
                                </td>
                            </tr>
                            <?php } }?>
                            <tr class="mobile_app_fee">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total Student</td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo $total_EDP_mobile_student;  ?>" readonly>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total EDP" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo number_format((float)$total_EDP_mobile_adjust, 2, '.', '');  ?>" readonly>
                                </td>
                            </tr>

                            <tr class="mobile_app_fee">
                                <td style=" margin-top:20px;border:none; font-size:16px;"
                                    class="uk-width-6-10 uk-text-bold">QF1:</td>
                            </tr>
                            <?php
                                $sql="SELECT programme_package as fees_structure, active_student, fee_rate as mobile_adjust, amount from `declaration_child` where master_id=$master_id and form='Form1' and fee_structure_mame='Mobile App Fees' and subject='QF1'";
							
							$result=mysqli_query($connection, $sql);
							$num_row = mysqli_num_rows($result);
                            
							$total_QF1_mobile_adjust = 0;
                            $total_QF1_mobile_student = 0;
							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                            <tr class="mobile_app_fee">
                                <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">
                                    <?php echo $row['fees_structure']?><span class="text-danger"></span>:</td>
                                
                                <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                    <?php
                                        $level_count = $row['active_student'];
                                        $total_QF1_mobile_adjust +=  $row['amount'];
                                        $total_QF1_mobile_student += $level_count;
                                ?>
                                    <input class="edp_tsd multimedia" type="number" step="0.01" name="active_student[]"
                                        id="active_student" value="<?php echo $level_count ?>" readonly> <span
                                        class="edp_eq">✕ </span>
                                </td>
                                <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd multimedia" type="number" name="fee_rate[]" id="fee_rate"
                                        value="<?php echo $row['mobile_adjust']?>" readonly> <span class="edp_eq2">=
                                    </span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust" type="number" step="0.01" name="amount[]" id="amount"
                                        value="<?php echo number_format((float)$level_count * $row['mobile_adjust'], 2, '.', '');  ?>" readonly><br>
                                </td>
                            </tr>
                            <?php } }?>
                            <tr class="mobile_app_fee">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total Student</td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo $total_QF1_mobile_student;  ?>" readonly>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total QF1" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo number_format((float)$total_QF1_mobile_adjust, 2, '.', '');  ?>" readonly>
                                </td>
                            </tr>

                            <tr class="mobile_app_fee">
                                <td style=" margin-top:20px;border:none; font-size:16px;"
                                    class="uk-width-6-10 uk-text-bold">QF2:</td>
                            </tr>
                            <?php
                                $sql="SELECT programme_package as fees_structure, active_student, fee_rate as mobile_adjust, amount from `declaration_child` where master_id=$master_id and form='Form1' and fee_structure_mame='Mobile App Fees' and subject='QF2'";
							
							$result=mysqli_query($connection, $sql);
							$num_row = mysqli_num_rows($result);
                            //$total_student = 0;
							$total_QF2_mobile_adjust = 0;
                            $total_QF2_mobile_student = 0;
							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                            <tr class="mobile_app_fee">
                                <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">
                                    <?php echo $row['fees_structure']?><span class="text-danger"></span>:</td>
                                
                                <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                    <?php
                                        $level_count = $row['active_student'];
                                        $total_QF2_mobile_adjust +=  $row['amount'];
                                        $total_QF2_mobile_student += $level_count;
                                ?>
                                    <input class="edp_tsd multimedia" type="number" step="0.01" name="active_student[]"
                                        id="active_student" value="<?php echo $level_count ?>" readonly> <span
                                        class="edp_eq">✕ </span>
                                </td>
                                <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd multimedia" type="number" name="fee_rate[]" id="fee_rate"
                                        value="<?php echo $row['mobile_adjust']?>" readonly> <span class="edp_eq2">=
                                    </span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust" type="number" step="0.01" name="amount[]" id="amount"
                                        value="<?php echo number_format((float)$level_count * $row['mobile_adjust'], 2, '.', '');  ?>" readonly><br>
                                </td>
                            </tr>
                            <?php } }?>
                            <tr class="mobile_app_fee">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total Student</td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo $total_QF2_mobile_student;  ?>" readonly>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total QF2" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo number_format((float)$total_QF2_mobile_adjust, 2, '.', '');  ?>" readonly>
                                </td>
                            </tr>

                            <tr class="mobile_app_fee">
                                <td style=" margin-top:20px;border:none; font-size:16px;"
                                    class="uk-width-6-10 uk-text-bold">QF3:</td>
                            </tr>
                            <?php
                                $sql="SELECT programme_package as fees_structure, active_student, fee_rate as mobile_adjust, amount from `declaration_child` where master_id=$master_id and form='Form1' and fee_structure_mame='Mobile App Fees' and subject='QF3'";
							
							$result=mysqli_query($connection, $sql);
							$num_row = mysqli_num_rows($result);
                            //$total_student = 0;
							$total_QF3_mobile_adjust = 0;
                            $total_QF3_mobile_student = 0;
							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                            <tr class="mobile_app_fee">
                                <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">
                                    <?php echo $row['fees_structure']?><span class="text-danger"></span>:</td>
                                
                                <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                    <?php
                                        $level_count = $row['active_student'];
                                        $total_QF3_mobile_adjust +=  $row['amount'];
                                        $total_QF3_mobile_student += $level_count;
                                ?>
                                    <input class="edp_tsd multimedia" type="number" step="0.01" name="active_student[]"
                                        id="active_student" value="<?php echo $level_count ?>" readonly> <span
                                        class="edp_eq">✕ </span>
                                </td>
                                <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd multimedia" type="number" name="fee_rate[]" id="fee_rate"
                                        value="<?php echo $row['mobile_adjust']?>" readonly> <span class="edp_eq2">=
                                    </span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust" type="number" step="0.01" name="amount[]" id="amount"
                                        value="<?php echo number_format((float)$level_count * $row['mobile_adjust'], 2, '.', '');  ?>" readonly><br>
                                </td>
                            </tr>
                            <?php } }?>
                            <tr class="mobile_app_fee">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total Student</td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo $total_QF3_mobile_student;  ?>" readonly>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total QF3" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo number_format((float)$total_QF3_mobile_adjust, 2, '.', '');  ?>" readonly>
                                </td>
                            </tr>

                            <tr class="mobile_app_fee">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total Student (iv)</td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f"
                                        value="<?php echo $total_student_mobile += $total_EDP_mobile_student + $total_QF1_mobile_student + $total_QF2_mobile_student + $total_QF3_mobile_student; //$total_student_a += $total_student_mobile; ?>"
                                        readonly>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total (iv)" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f"
                                        value="<?php  $total_mobile_adjust = $total_EDP_mobile_adjust + $total_QF1_mobile_adjust  + $total_QF2_mobile_adjust + $total_QF3_mobile_adjust; 
                                        echo number_format((float)$total_mobile_adjust, 2, '.', ''); ?>"
                                        readonly>
                                    <?php $total_mobile_adjust = 0; //$total_a += $total_mobile_adjust; ?>
                                </td>
                            </tr>

                            <tr class="">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total A</td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo $total_student_a;  ?>" readonly>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total A" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo number_format((float)$total_a, 2, '.', ''); ?>" readonly>
                                </td>
                            </tr>

                            <tr class="">
                                <td class="uk-width-6-10" style="font-size:16px; font-weight:bold; border:none;">B:
                                    Materials
                                    Fees</td>
                            </tr>
                            <tr class="">
                                <td style=" margin-top:50px;border:none; font-size:18px;"
                                    class="uk-width-6-10 uk-text-bold">(i) Q-dess Foundation Materials</td>
                            </tr>

                            <tr class="">
                                <td style=" margin-top:200px;border:none; font-size:16px;"
                                    class="uk-width-6-10 uk-text-bold">EDP</td>
                            </tr>
                            <?php
                                $sql="SELECT programme_package as fees_structure, active_student, fee_rate as integrated_adjust, amount from `declaration_child` where master_id=$master_id and form='Form1' and fee_structure_mame='Q-dess Foundation Materials' and subject='EDP'";
							
							$result=mysqli_query($connection, $sql);
							$num_row = mysqli_num_rows($result);
                            //$total_student = 0;
                            $total_EDP_integrated_adjust = 0;
                            $total_EDP_integrated_student = 0;
							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                            <tr class="">
                                <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">
                                    <?php echo $row['fees_structure']?><span class="text-danger"></span>:</td>
                                
                                <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                    <?php
                                        $level_count = $row['active_student'];
                                        $total_EDP_integrated_adjust +=  $row['amount'];
                                        $total_EDP_integrated_student += $level_count;
                                ?>
                                    <input class="edp_tsd multimedia" type="number" step="0.01" name="active_student[]"
                                        id="active_student" value="<?php echo $level_count ?>" readonly> <span
                                        class="edp_eq">✕ </span>
                                </td>
                                <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd multimedia" type="number" name="fee_rate[]" id="fee_rate"
                                        value="<?php echo number_format((float)$row['integrated_adjust'], 2, '.', ''); ?>" readonly> <span class="edp_eq2">=
                                    </span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust" type="number" step="0.01" name="amount[]" id="amount"
                                        value="<?php echo number_format((float)$level_count * $row['integrated_adjust'], 2, '.', '');  ?>" readonly><br>
                                </td>
                            </tr>
                            <?php } }?>
                            <tr class="">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total Student</td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo $total_EDP_integrated_student;  ?>"
                                        readonly>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total EDP" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo number_format((float)$total_EDP_integrated_adjust, 2, '.', '');  ?>"
                                        readonly>
                                </td>
                            </tr>

                            <tr class="">
                                <td style=" margin-top:200px;border:none; font-size:16px;"
                                    class="uk-width-6-10 uk-text-bold">QF1</td>
                            </tr>
                            <?php
                                $sql="SELECT programme_package as fees_structure, active_student, fee_rate as integrated_adjust, amount from `declaration_child` where master_id=$master_id and form='Form1' and fee_structure_mame='Q-dess Foundation Materials' and subject='QF1'";
							
							$result=mysqli_query($connection, $sql);
							$num_row = mysqli_num_rows($result);
                            $total_QF1_integrated_student = 0;
							$total_QF1_integrated_adjust = 0;
							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                            <tr class="">
                                <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">
                                    <?php echo $row['fees_structure']?><span class="text-danger"></span>:</td>
                                
                                <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                    <?php
                                        $level_count = $row['active_student'];
                                        $total_QF1_integrated_adjust +=  $row['amount'];
                                        $total_QF1_integrated_student += $level_count;
                                ?>
                                    <input class="edp_tsd multimedia" type="number" step="0.01" name="active_student[]"
                                        id="active_student" value="<?php echo $level_count ?>" readonly> <span
                                        class="edp_eq">✕ </span>
                                </td>
                                <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd multimedia" type="number" name="fee_rate[]" id="fee_rate"
                                        value="<?php echo number_format((float)$row['integrated_adjust'], 2, '.', ''); ?>" readonly> <span class="edp_eq2">=
                                    </span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust" type="number" step="0.01" name="amount[]" id="amount"
                                        value="<?php echo number_format((float)$level_count * $row['integrated_adjust'], 2, '.', '');  ?>" readonly><br>
                                </td>
                            </tr>
                            <?php } }?>
                            <tr class="">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total Student</td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo $total_QF1_integrated_student;  ?>"
                                        readonly>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total QF1" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo number_format((float)$total_QF1_integrated_adjust, 2, '.', '');  ?>"
                                        readonly>
                                </td>
                            </tr>

                            <tr class="">
                                <td style=" margin-top:200px;border:none; font-size:16px;"
                                    class="uk-width-6-10 uk-text-bold">QF2</td>
                            </tr>
                            <?php
                                $sql="SELECT programme_package as fees_structure, active_student, fee_rate as integrated_adjust, amount from `declaration_child` where master_id=$master_id and form='Form1' and fee_structure_mame='Q-dess Foundation Materials' and subject='QF2'";
							
							$result=mysqli_query($connection, $sql);
							$num_row = mysqli_num_rows($result);
                            $total_QF2_integrated_student = 0;
							$total_QF2_integrated_adjust = 0;
							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                            <tr class="">
                                <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">
                                    <?php echo $row['fees_structure']?><span class="text-danger"></span>:</td>
                                
                                <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                    <?php
                                        $level_count = $row['active_student'];
                                        $total_QF2_integrated_adjust +=  $row['amount'];
                                        $total_QF2_integrated_student += $level_count;
                                ?>
                                    <input class="edp_tsd multimedia" type="number" step="0.01" name="active_student[]"
                                        id="active_student" value="<?php echo $level_count ?>" readonly> <span
                                        class="edp_eq">✕ </span>
                                </td>
                                <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd multimedia" type="number" name="fee_rate[]" id="fee_rate"
                                        value="<?php echo number_format((float)$row['integrated_adjust'], 2, '.', ''); ?>" readonly> <span class="edp_eq2">=
                                    </span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust" type="number" step="0.01" name="amount[]" id="amount"
                                        value="<?php echo  number_format((float)$level_count * $row['integrated_adjust'], 2, '.', '');  ?>" readonly><br>
                                </td>
                            </tr>
                            <?php } }?>
                            <tr class="">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total Student</td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo $total_QF2_integrated_student;  ?>"
                                        readonly>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total QF2" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo number_format((float)$total_QF2_integrated_adjust, 2, '.', '');  ?>"
                                        readonly>
                                </td>
                            </tr>

                            <tr class="">
                                <td style=" margin-top:200px;border:none; font-size:16px;"
                                    class="uk-width-6-10 uk-text-bold">QF3</td>
                            </tr>
                            <?php
                                $sql="SELECT programme_package as fees_structure, active_student, fee_rate as integrated_adjust, amount from `declaration_child` where master_id=$master_id and form='Form1' and fee_structure_mame='Q-dess Foundation Materials' and subject='QF3'";
							
							$result=mysqli_query($connection, $sql);
							$num_row = mysqli_num_rows($result);
                            $total_QF3_integrated_student = 0;
							$total_QF3_integrated_adjust = 0;
							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                            <tr class="">
                                <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">
                                    <?php echo $row['fees_structure']?><span class="text-danger"></span>:</td>
                                
                                <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                    <?php
                                        $level_count = $row['active_student'];
                                        $total_QF3_integrated_adjust +=  $row['amount'];
                                        $total_QF3_integrated_student += $level_count;
                                ?>
                                    <input class="edp_tsd multimedia" type="number" step="0.01" name="active_student[]"
                                        id="active_student" value="<?php echo $level_count ?>" readonly> <span
                                        class="edp_eq">✕ </span>
                                </td>
                                <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd multimedia" type="number" name="fee_rate[]" id="fee_rate"
                                        value="<?php echo number_format((float)$row['integrated_adjust'], 2, '.', ''); ?>" readonly> <span class="edp_eq2">=
                                    </span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust" type="number" step="0.01" name="amount[]" id="amount"
                                        value="<?php echo number_format((float)$level_count * $row['integrated_adjust'], 2, '.', '');  ?>" readonly><br>
                                </td>
                            </tr>
                            <?php } }?>
                            <tr class="">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total Student</td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo $total_QF3_integrated_student;  ?>"
                                        readonly>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total QF3" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo number_format((float)$total_QF3_integrated_adjust, 2, '.', '');  ?>"
                                        readonly>
                                </td>
                            </tr>
                            </tr>

                            <tr class="">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total Student (i)</td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f"
                                        value="<?php echo $total_integrated_student = $total_EDP_integrated_student + $total_QF1_integrated_student + $total_QF2_integrated_student + $total_QF3_integrated_student; $total_student_B = $total_integrated_student; ?>"
                                        readonly>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total (i)" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f"
                                        value="<?php  $total_integrated_adjust = $total_EDP_integrated_adjust + $total_QF1_integrated_adjust  + $total_QF2_integrated_adjust + $total_QF3_integrated_adjust; 
                                        echo  number_format((float)$total_integrated_adjust, 2, '.', '')?>"
                                        readonly>
                                    <?php $total_b = $total_integrated_adjust; ?>
                                </td>
                            </tr>
                            <tr class="">
                                <td style=" margin-top:50px;border:none; font-size:18px;"
                                    class="uk-width-6-10 uk-text-bold">(ii) Q-dees Foundation Mandarin Modules Materials
                                </td>
                            </tr>

                            <tr class="">
                                <td style=" margin-top:200px;border:none; font-size:16px;"
                                    class="uk-width-6-10 uk-text-bold">EDP</td>
                            </tr>
                            <?php
                                $sql="SELECT programme_package as fees_structure, active_student, fee_rate as mandarin_m_adjust, amount from `declaration_child` where master_id=$master_id and form='Form1' and fee_structure_mame='Q-dees Foundation Mandarin Modules Materials' and subject='EDP'";
							
							$result=mysqli_query($connection, $sql);
							$num_row = mysqli_num_rows($result);
                            $total_EDP_mandarin_student = 0;
                            $total_EDP_mandarin_m_adjust = 0;
							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                            <tr class="">
                                <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">
                                    <?php echo $row['fees_structure']?><span class="text-danger"></span>:</td>
                              
                                <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                    <?php
                                    $level_count = $row['active_student'];
                                    $total_EDP_mandarin_m_adjust +=  $row['amount'];
                                    $total_EDP_mandarin_student += $level_count;
                                ?>
                                    <input class="edp_tsd multimedia" type="number" step="0.01" name="active_student[]"
                                        id="active_student" value="<?php echo $level_count ?>" readonly> <span
                                        class="edp_eq">✕ </span>
                                </td>
                                <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd multimedia" type="number" name="fee_rate[]" id="fee_rate"
                                        value="<?php echo number_format($row['mandarin_m_adjust'], 2, '.', ''); ?>" readonly> <span class="edp_eq2">=
                                    </span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust" type="number" step="0.01" name="amount[]" id="amount"
                                        value="<?php echo number_format((float)$level_count * $row['mandarin_m_adjust'], 2, '.', '');  ?>" readonly><br>
                                </td>
                            </tr>
                            <?php } }?>
                            <tr class="">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total Student</td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo $total_EDP_mandarin_student;  ?>"
                                        readonly>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total EDP" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo number_format((float)$total_EDP_mandarin_m_adjust, 2, '.', '');  ?>"
                                        readonly>
                                </td>
                            </tr>

                            <tr class="">
                                <td style=" margin-top:200px;border:none; font-size:16px;"
                                    class="uk-width-6-10 uk-text-bold">QF1</td>
                            </tr>
                            <?php
                                $sql="SELECT programme_package as fees_structure, active_student, fee_rate as mandarin_m_adjust, amount from `declaration_child` where master_id=$master_id and form='Form1' and fee_structure_mame='Q-dees Foundation Mandarin Modules Materials' and subject='QF1'";
							
							$result=mysqli_query($connection, $sql);
							$num_row = mysqli_num_rows($result);
                            $total_QF1_mandarin_student = 0;
							$total_QF1_mandarin_m_adjust = 0;
							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                            <tr class="">
                                <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">
                                    <?php echo $row['fees_structure']?><span class="text-danger"></span>:</td>
                               
                                <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                    <?php
                                        $level_count = $row['active_student'];
                                        $total_QF1_mandarin_m_adjust +=  $row['amount'];
                                        $total_QF1_mandarin_student += $level_count;
                            
                                ?>
                                    <input class="edp_tsd multimedia" type="number" step="0.01" name="active_student[]"
                                        id="active_student" value="<?php echo $level_count ?>" readonly> <span
                                        class="edp_eq">✕ </span>
                                </td>
                                <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd multimedia" type="number" name="fee_rate[]" id="fee_rate"
                                        value="<?php echo number_format($row['mandarin_m_adjust'], 2, '.', ''); ?>" readonly> <span class="edp_eq2">=
                                    </span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust" type="number" step="0.01" name="amount[]" id="amount"
                                        value="<?php echo number_format((float)$level_count * $row['mandarin_m_adjust'], 2, '.', '');  ?>" readonly><br>
                                </td>
                            </tr>
                            <?php } }?>
                            <tr class="">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total Student</td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo $total_QF1_mandarin_student;  ?>"
                                        readonly>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total QF1" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo number_format((float)$total_QF1_mandarin_m_adjust, 2, '.', '');  ?>"
                                        readonly>
                                </td>
                            </tr>

                            <tr class="">
                                <td style=" margin-top:200px;border:none; font-size:16px;"
                                    class="uk-width-6-10 uk-text-bold">QF2</td>
                            </tr>
                            <?php
                        $sql="SELECT programme_package as fees_structure, active_student, fee_rate as mandarin_m_adjust, amount from `declaration_child` where master_id=$master_id and form='Form1' and fee_structure_mame='Q-dees Foundation Mandarin Modules Materials' and subject='QF2'";
							
							$result=mysqli_query($connection, $sql);
							$num_row = mysqli_num_rows($result);
                            $total_QF2_mandarin_student = 0;
							$total_QF2_mandarin_m_adjust = 0;
							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                            <tr class="">
                                <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">
                                    <?php echo $row['fees_structure']?><span class="text-danger"></span>:</td>
                                
                                <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                    <?php
                                        $level_count = $row['active_student'];
                                        $total_QF2_mandarin_m_adjust +=  $row['amount'];
                                        $total_QF2_mandarin_student += $level_count;
                                ?>
                                    <input class="edp_tsd multimedia" type="number" step="0.01" name="active_student[]"
                                        id="active_student" value="<?php echo $level_count ?>" readonly> <span
                                        class="edp_eq">✕ </span>
                                </td>
                                <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd multimedia" type="number" name="fee_rate[]" id="fee_rate"
                                        value="<?php echo number_format($row['mandarin_m_adjust'], 2, '.', ''); ?>" readonly> <span class="edp_eq2">=
                                    </span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust" type="number" step="0.01" name="amount[]" id="amount"
                                        value="<?php echo number_format((float)$level_count * $row['mandarin_m_adjust'], 2, '.', '');  ?>" readonly><br>
                                </td>
                            </tr>
                            <?php } }?>
                            <tr class="">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total Student</td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo $total_QF2_mandarin_student;  ?>"
                                        readonly>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total QF2" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo  number_format((float)$total_QF2_mandarin_m_adjust, 2, '.', '');  ?>"
                                        readonly>
                                </td>
                            </tr>

                            <tr class="">
                                <td style=" margin-top:200px;border:none; font-size:16px;"
                                    class="uk-width-6-10 uk-text-bold">QF3</td>
                            </tr>
                            <?php
                                $sql="SELECT programme_package as fees_structure, active_student, fee_rate as mandarin_m_adjust, amount from `declaration_child` where master_id=$master_id and form='Form1' and fee_structure_mame='Q-dees Foundation Mandarin Modules Materials' and subject='QF3'";
							
							$result=mysqli_query($connection, $sql);
							$num_row = mysqli_num_rows($result);
                            $total_QF3_mandarin_student = 0;
							$total_QF3_mandarin_m_adjust = 0;
							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                            <tr class="">
                                <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">
                                    <?php echo $row['fees_structure']?><span class="text-danger"></span>:</td>
                                
                                <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                    <?php
                                        $level_count = $row['active_student'];
                                        $total_QF3_mandarin_m_adjust +=  $row['amount'];
                                        $total_QF3_mandarin_student += $level_count;
                                ?>
                                    <input class="edp_tsd multimedia" type="number" step="0.01" name="active_student[]"
                                        id="active_student" value="<?php echo $level_count ?>" readonly> <span
                                        class="edp_eq">✕ </span>
                                </td>
                                <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd multimedia" type="number" name="fee_rate[]" id="fee_rate"
                                        value="<?php echo number_format($row['mandarin_m_adjust'], 2, '.', ''); ?>" readonly> <span class="edp_eq2">=
                                    </span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust" type="number" step="0.01" name="amount[]" id="amount"
                                        value="<?php echo number_format((float)$level_count * $row['mandarin_m_adjust'], 2, '.', '');  ?>" readonly><br>
                                </td>
                            </tr>
                            <?php } }?>
                            <tr class="">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total Student</td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo $total_QF3_mandarin_student;  ?>"
                                        readonly>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total QF3" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo number_format((float)$total_QF3_mandarin_m_adjust, 2, '.', '');  ?>"
                                        readonly>
                                </td>
                            </tr>
                            <tr class="">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total Student (ii)</td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f"
                                        value="<?php echo $total_mandarin_student = $total_EDP_mandarin_student + $total_QF1_mandarin_student + $total_QF2_mandarin_student + $total_QF3_mandarin_student; $total_student_B =+ $total_mandarin_student; ?>"
                                        readonly>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total (ii)" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f"
                                        value="<?php  $total_mandarin_m_adjust = $total_EDP_mandarin_m_adjust + $total_QF1_imandarin_m_adjust  + $total_QF2_mandarin_m_adjust + $total_QF3_mandarin_m_adjust;  
                                        echo number_format((float)$total_mandarin_m_adjust, 2, '.', '')?>"
                                        readonly>
                                    <?php $total_b += $total_mandarin_m_adjust; ?>
                                </td>
                            </tr>
                            <tr class="">
                                <td style=" margin-top:50px;border:none; font-size:18px;"
                                    class="uk-width-6-10 uk-text-bold">(iii) Registration Pack</td>
                            </tr>

                            <tr class="">
                                <td style=" margin-top:200px;border:none; font-size:16px;"
                                    class="uk-width-6-10 uk-text-bold">EDP</td>
                            </tr>
                            <?php
                                $sql="SELECT programme_package as fees_structure, active_student, fee_rate as registration_adjust, amount from `declaration_child` where master_id=$master_id and form='Form1' and fee_structure_mame='Registration Pack' and subject='EDP'";
							
							$result=mysqli_query($connection, $sql);
							$num_row = mysqli_num_rows($result);
                            $total_EDP_registration_student = 0;
                            $total_EDP_registration_adjust = 0;
							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                            <tr class="">
                                <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">
                                    <?php echo $row['fees_structure']?><span class="text-danger"></span>:</td>
                                
                                <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                    <?php
                                        $level_count = $row['active_student'];
                                        $total_EDP_registration_adjust +=  $row['amount'];
                                        $total_EDP_registration_student += $level_count;
                                ?>
                                    <input class="edp_tsd multimedia" type="number" step="0.01" name="active_student[]"
                                        id="active_student" value="<?php echo $level_count ?>" readonly> <span
                                        class="edp_eq">✕ </span>
                                </td>
                                <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd multimedia" type="number" name="fee_rate[]" id="fee_rate"
                                        value="<?php echo $row['registration_adjust']?>" readonly> <span
                                        class="edp_eq2">= </span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust" type="number" step="0.01" name="amount[]" id="amount"
                                        value="<?php echo number_format((float)$level_count * $row['registration_adjust'], 2, '.', '');  ?>" readonly><br>
                                </td>
                            </tr>
                            <?php } }?>
                            <tr class="">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total Student</td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo $total_EDP_registration_student;  ?>"
                                        readonly>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total EDP" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo number_format((float)$total_EDP_registration_adjust, 2, '.', '');  ?>"
                                        readonly>
                                </td>
                            </tr>

                            <tr class="">
                                <td style=" margin-top:200px;border:none; font-size:16px;"
                                    class="uk-width-6-10 uk-text-bold">QF1</td>
                            </tr>
                            <?php
                        $sql="SELECT programme_package as fees_structure, active_student, fee_rate as registration_adjust, amount from `declaration_child` where master_id=$master_id and form='Form1' and fee_structure_mame='Registration Pack' and subject='QF1'";
							
							$result=mysqli_query($connection, $sql);
							$num_row = mysqli_num_rows($result);
                            $total_QF1_registration_student = 0;
							$total_QF1_registration_adjust = 0;
							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                            <tr class="">
                                <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">
                                    <?php echo $row['fees_structure']?><span class="text-danger"></span>:</td>
                                
                                <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                    <?php
                            $level_count = $row['active_student'];
                            $total_QF1_registration_adjust +=  $row['amount'];
                            $total_QF1_registration_student += $level_count;
                                ?>
                                    <input class="edp_tsd multimedia" type="number" step="0.01" name="active_student[]"
                                        id="active_student" value="<?php echo $level_count ?>" readonly> <span
                                        class="edp_eq">✕ </span>
                                </td>
                                <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd multimedia" type="number" name="fee_rate[]" id="fee_rate"
                                        value="<?php echo $row['registration_adjust']?>" readonly> <span
                                        class="edp_eq2">= </span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust" type="number" step="0.01" name="amount[]" id="amount"
                                        value="<?php echo number_format((float)$level_count * $row['registration_adjust'], 2, '.', '');  ?>" readonly><br>
                                </td>
                            </tr>
                            <?php } }?>
                            <tr class="">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total Student</td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo $total_QF1_registration_student;  ?>"
                                        readonly>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total QF1" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo number_format((float)$total_QF1_registration_adjust, 2, '.', '');  ?>"
                                        readonly>
                                </td>
                            </tr>

                            <tr class="">
                                <td style=" margin-top:200px;border:none; font-size:16px;"
                                    class="uk-width-6-10 uk-text-bold">QF2</td>
                            </tr>
                            <?php
                                $sql="SELECT programme_package as fees_structure, active_student, fee_rate as registration_adjust, amount from `declaration_child` where master_id=$master_id and form='Form1' and fee_structure_mame='Registration Pack' and subject='QF2'";
							
							$result=mysqli_query($connection, $sql);
							$num_row = mysqli_num_rows($result);
                            $total_QF2_registration_student = 0;
							$total_QF2_registration_adjust = 0;
							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                            <tr class="">
                                <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">
                                    <?php echo $row['fees_structure']?><span class="text-danger"></span>:</td>
                                
                                <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                    <?php
                                    $level_count = $row['active_student'];
                                    $total_QF2_registration_adjust +=  $row['amount'];
                                    $total_QF2_registration_student += $level_count;
                                ?>
                                    <input class="edp_tsd multimedia" type="number" step="0.01" name="active_student[]"
                                        id="active_student" value="<?php echo $level_count ?>" readonly> <span
                                        class="edp_eq">✕ </span>
                                </td>
                                <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd multimedia" type="number" name="fee_rate[]" id="fee_rate"
                                        value="<?php echo $row['registration_adjust']?>" readonly> <span
                                        class="edp_eq2">= </span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust" type="number" step="0.01" name="amount[]" id="amount"
                                        value="<?php echo number_format((float)$level_count * $row['registration_adjust'], 2, '.', '');  ?>" readonly><br>
                                </td>
                            </tr>
                            <?php } }?>
                            <tr class="">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total Student</td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo $total_QF2_registration_student;  ?>"
                                        readonly>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total QF2" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo number_format((float)$total_QF2_registration_adjust, 2, '.', '');  ?>"
                                        readonly>
                                </td>
                            </tr>

                            <tr class="">
                                <td style=" margin-top:200px;border:none; font-size:16px;"
                                    class="uk-width-6-10 uk-text-bold">QF3</td>
                            </tr>
                            <?php
                        $sql="SELECT programme_package as fees_structure, active_student, fee_rate as registration_adjust, amount from `declaration_child` where master_id=$master_id and form='Form1' and fee_structure_mame='Registration Pack' and subject='QF3'";
							
							$result=mysqli_query($connection, $sql);
							$num_row = mysqli_num_rows($result);
                            $total_QF3_registration_student = 0;
							$total_QF3_registration_adjust = 0;
							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                            <tr class="">
                                <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">
                                    <?php echo $row['fees_structure']?><span class="text-danger"></span>:</td>
                               
                                <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                    <?php
                                        $level_count = $row['active_student'];
                                        $total_QF3_registration_adjust +=  $row['amount'];
                                        $total_QF3_registration_student += $level_count;
                                ?>
                                    <input class="edp_tsd multimedia" type="number" step="0.01" name="active_student[]"
                                        id="active_student" value="<?php echo $level_count ?>" readonly> <span
                                        class="edp_eq">✕ </span>
                                </td>
                                <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd multimedia" type="number" name="fee_rate[]" id="fee_rate"
                                        value="<?php echo $row['registration_adjust']?>" readonly> <span
                                        class="edp_eq2">= </span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust" type="number" step="0.01" name="amount[]" id="amount"
                                        value="<?php echo number_format((float)$level_count * $row['registration_adjust'], 2, '.', '');  ?>" readonly><br>
                                </td>
                            </tr>
                            <?php } }?>
                            <tr class="">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total Student</td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo $total_QF3_registration_student;  ?>"
                                        readonly>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total QF3" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo number_format((float)$total_QF3_registration_adjust, 2, '.', '');  ?>"
                                        readonly>
                                </td>
                            </tr>
                            <tr class="">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total Student (iii)</td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f"
                                        value="<?php echo $total_registration_student = $total_EDP_registration_student + $total_QF1_registration_student + $total_QF2_registration_student + $total_QF3_registration_student; $total_student_B += $total_registration_student; ?>"
                                        readonly>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total (iii)" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f"
                                        value="<?php  $total_registration_adjust = $total_EDP_registration_adjust + $total_QF1_registration_adjust  + $total_QF2_registration_adjust + $total_QF3_registration_adjust;  
                                        echo number_format((float)$total_registration_adjust, 2, '.', '');?>"
                                        readonly>
                                    <?php $total_b += $total_registration_adjust; ?>
                                </td>
                            </tr>

                            <tr class="">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10"></td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" style="display:none;" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo $total_student_B; ?>" readonly>
                                </td>
                                <td style="text-align:center; border:none;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_b" id="total_b"
                                        placeholder="Total B"> <span style="font-size:14px;" class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center; border:none;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="totalb_g" id="totalb_g"
                                        value="<?php echo number_format((float)$total_b, 2, '.', ''); ?>" readonly>
                                </td>
                            </tr>
                            <tr class="">
                                <td colspan="5">Note: as per Student Fee List for each Academic Year as prescribed by
                                    the
                                    Franchisor<br>*as stated in the Operations e-Manual</td>
                            </tr>
                            <tr class="">
                                <td style=" margin-top:50px;border:none; font-size:16px;"
                                    class="uk-width-6-10 uk-text-bold">C: Products</td>
                            </tr>

                            <tr class="">
                                <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">
                                    (i)Pre-school Kits<span class="text-danger"></span>:</td>
                                
                                <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                    <?php
                                        $sql="SELECT programme_package as fees_structure, active_student, fee_rate, amount
                                        from `declaration_child` where master_id=$master_id and form='Form1' and fee_structure_mame='Pre-school Kits'";
                                        $result=mysqli_query($connection, $sql);
                                        $num_row = mysqli_num_rows($result);
                                        if ($num_row>0) {
                                            while ($row=mysqli_fetch_assoc($result)) {
                                                $level_count = $row['active_student'];
                                                $fees = $row["fee_rate"]; 
                                                $total_pre_school_kits =  $row['amount'];
                                                $total_student_C += $level_count;
                                            }
                                        }
                                ?>
                                    <input class="edp_tsd pre_school" type="number" step="0.01" name="active_student[]"
                                        id="active_student" value="<?php echo $level_count ?>" readonly> <span
                                        class="edp_eq">✕ </span>
                                </td>
                                <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd pre_school" type="number" name="fee_rate[]" id="fee_rate"
                                        value="<?php echo number_format((float)$fees, 2, '.', '') ?>" readonly> <span class="edp_eq2">= </span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust2" type="number" step="0.01" name="amount[]" id="amount"
                                        value="<?php echo number_format((float)$total_pre_school_kits, 2, '.', ''); ?>" readonly><br>
                                </td>
                            </tr>
                            <tr class="">
                                <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">
                                    (ii)Memories to Cherish<span class="text-danger"></span>:</td>
                                
                                <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                <?php
                                    $sql="SELECT programme_package as fees_structure, active_student, fee_rate, amount
                                    from `declaration_child` where master_id=$master_id and form='Form1' and fee_structure_mame='Memories to Cherish'";
                                    $result=mysqli_query($connection, $sql);
                                    $num_row = mysqli_num_rows($result);
                                    if ($num_row>0) {
                                        while ($row=mysqli_fetch_assoc($result)) {
                                            $level_count = $row['active_student'];
                                            $fees = $row["fee_rate"]; 
                                            $total_memories_to_cherish =  $row['amount'];
                                            $total_student_C += $level_count;
                                        }
                                    }
                                ?>
                                    <input class="edp_tsd pre_school" type="number" step="0.01" name="active_student[]"
                                        id="active_student" value="<?php echo $level_count ?>" readonly> <span
                                        class="edp_eq">✕ </span>
                                </td>
                                <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd pre_school" type="number" name="fee_rate[]" id="fee_rate"
                                        value="<?php echo number_format((float)$fees, 2, '.', '') ?>" readonly> <span class="edp_eq2">= </span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust2" type="number" step="0.01" name="amount[]" id="amount"
                                        value="<?php echo number_format((float)$total_memories_to_cherish, 2, '.', ''); ?>" readonly><br>
                                </td>
                            </tr>

                            <tr class="">
                                <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">(iii)Q-dees
                                    Bag<span class="text-danger"></span>:</td>
                                
                                <?php
                                    $sql="SELECT programme_package as fees_structure, active_student, fee_rate, amount
                                    from `declaration_child` where master_id=$master_id and form='Form1' and fee_structure_mame='Q-dees Bag'";
                                    $result=mysqli_query($connection, $sql);
                                    $num_row = mysqli_num_rows($result);
                                    if ($num_row>0) {
                                        while ($row=mysqli_fetch_assoc($result)) {
                                            $level_count = $row['active_student'];
                                            $fees = $row["fee_rate"]; 
                                            $total_q_bag_adjust =  $row['amount'];
                                            $total_student_C += $level_count;
                                        }
                                    }
                                ?>
                                <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd q_dees" type="number" step="0.01" name="active_student[]"
                                        id="active_student" value="<?php echo $level_count ?>" readonly> <span
                                        class="edp_eq" readonly>✕ </span>
                                </td>
                                <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd q_dees" type="number" name="fee_rate[]" id="fee_rate"
                                        value="<?php echo number_format((float)$fees, 2, '.', '') ?>" readonly>
                                    <span class="edp_eq2">= </span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust2" type="number" step="0.01" name="amount[]" id="amount"
                                        value="<?php echo number_format((float)$total_q_bag_adjust, 2, '.', ''); ?>" readonly><br>
                                </td>
                            </tr>

                            <tr class="">
                                <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">
                                    (iv)Uniform <?php $new_price_condition = ''; if(($monthyear == '2022-10' && $_GET['mode'] != 'EDIT') || ($_GET['mode'] == 'EDIT' && $month == '10' && ($year == '2022' || $year == '2022-2023'))) { 

                                        $new_price_condition = "and fl.`uniform_adjust` = '98'";

                                        $student_id_list=mysqli_query($connection,"SELECT s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and fl.`uniform_adjust` != '98' and ps.student_entry_level != '' and s.student_status = 'A' and month(s.start_date_at_centre) = '".date('m')."' and s.centre_code='$centre_code' and s.deleted='0' and '$monthyear' BETWEEN DATE_FORMAT(fl.programme_date, '%Y-%m') AND DATE_FORMAT(fl.programme_date_end, '%Y-%m') group by ps.student_entry_level, s.id");

                                        if(mysqli_num_rows($student_id_list) > 0) 
                                        {
                                            $student_id_array = array();

                                            while($student_id_row = mysqli_fetch_array($student_id_list))
                                            {
                                                $student_id_array[] = $student_id_row['id'];
                                            }
                                            
                                            $new_price_condition .= " and s.id NOT IN (" . implode(", ", $student_id_array) . ")";
                                        }

                                    ?> (New Price) <?php } ?><span class="text-danger"></span>:</td>
                                
                                <?php
                                    $sql="SELECT programme_package as fees_structure, active_student, fee_rate, amount
                                    from `declaration_child` where master_id=$master_id and form='Form1' and fee_structure_mame='Uniform'";
                                    $result=mysqli_query($connection, $sql);
                                    $num_row = mysqli_num_rows($result);
                                    if ($num_row>0) {
                                        while ($row=mysqli_fetch_assoc($result)) {
                                            $level_count = $row['active_student'];
                                            $fees = $row["fee_rate"]; 
                                            $total_uniform_adjust =  $row['amount'];
                                            $total_student_C += $level_count;
                                        }
                                    }
                                ?>
                                <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd q_dees" type="number" step="0.01" name="active_student[]"
                                        id="active_student" value="<?php echo $level_count ?>" readonly> <span
                                        class="edp_eq" readonly>✕ </span>
                                </td>
                                <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd q_dees" type="number" name="fee_rate[]" id="fee_rate"
                                        value="<?php echo number_format((float)$fees, 2, '.', '') ?>" readonly>
                                    <span class="edp_eq2">= </span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust2" type="number" step="0.01" name="amount[]" id="amount"
                                        value="<?php echo number_format((float)$total_uniform_adjust, 2, '.', '') ?>" readonly><br>
                                </td>

                            </tr>
                            <?php if(($monthyear == '2022-10' && $_GET['mode'] != 'EDIT') || ($_GET['mode'] == 'EDIT' && $month == '10' && ($year == '2022' || $year == '2022-2023'))) { ?>
                                <tr class="">
                                    <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">
                                        (iv)Uniform (Old Price) <span class="text-danger"></span>:</td>
                                    
                                <?php
                                    $sql="SELECT programme_package as fees_structure, active_student, fee_rate, amount
                                    from `declaration_child` where master_id=$master_id and form='Form1' and fee_structure_mame='Uniform (Old Price)'";
                                    $result=mysqli_query($connection, $sql);
                                    $num_row = mysqli_num_rows($result);
                                    if ($num_row>0) {
                                        while ($row=mysqli_fetch_assoc($result)) {
                                            $level_count = $row['active_student'];
                                            $fees = $row["fee_rate"]; 
                                            $total_uniform_adjust_old =  $row['amount'];
                                            $total_uniform_adjust +=  $row['amount'];
                                            $total_student_C += $level_count;
                                        }
                                    }
                                    ?>
                                    <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                        <input class="edp_tsd q_dees" type="number" step="0.01" name="active_student[]"
                                            id="active_student" value="<?php echo $level_count ?>" readonly> <span
                                            class="edp_eq" readonly>✕ </span>
                                    </td>
                                    <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                        <input class="edp_tsd q_dees" type="number" name="fee_rate[]" id="fee_rate"
                                            value="<?php echo number_format((float)$fees, 2, '.', '') ?>" readonly>
                                        <span class="edp_eq2">= </span>
                                    </td>
                                    <td style="border:none; text-align:center;" class="uk-width-1-10">
                                        <input class="school_adjust2" type="number" step="0.01" name="amount[]" id="amount"
                                            value="<?php echo number_format((float)$total_uniform_adjust_old, 2, '.', '') ?>" readonly><br>
                                    </td>

                                </tr>
                            <?php } ?>
                            <?php
                                $sql="SELECT programme_package as fees_structure, fee_structure_mame as product_name, active_student as level_count, fee_rate as fees, amount from `declaration_child` where master_id=$master_id and form='Form1' and (fee_structure_mame like 'Q-dees Male Uniform%' or fee_structure_mame like 'Q-dees Long Pants%' or fee_structure_mame like 'Q-dees Female Uniform%' or fee_structure_mame like 'Q-dees Kelantan Male Uniform%' or fee_structure_mame like 'Q-dees Kelantan Female Uniform%') ORDER BY FIELD(product_name,'Q-dees Male Uniform - XS(short)','Q-dees Male Uniform - S(short)','Q-dees Male Uniform - M(short)','Q-dees Male Uniform - L(short)','Q-dees Male Uniform - XL(short)','Q-dees Male Uniform - XXL(short)','Q-dees Male Uniform - XXXL(short)','Q-dees Male Uniform - Special Make(short)','Q-dees Male Uniform - XS(long)','Q-dees Male Uniform - S(long)','Q-dees Male Uniform - M(long)','Q-dees Male Uniform - L(long)','Q-dees Male Uniform - XL(long)', 'Q-dees Long Pants - XS', 'Q-dees Long Pants - S', 'Q-dees Long Pants - M', 'Q-dees Long Pants - L', 'Q-dees Long Pants - XL', 'Q-dees Female Uniform - XS', 'Q-dees Female Uniform - S', 'Q-dees Female Uniform - M', 'Q-dees Female Uniform - L', 'Q-dees Female Uniform - XL', 'Q-dees Female Uniform - XXL', 'Q-dees Female Uniform - XXXL', 'Q-dees Female Uniform - Special Make', 'Q-dees Kelantan Male Uniform - XS', 'Q-dees Kelantan Male Uniform - S', 'Q-dees Kelantan Male Uniform - M', 'Q-dees Kelantan Male Uniform - L', 'Q-dees Kelantan Male Uniform - XL', 'Q-dees Kelantan Male Uniform - XXL', 'Q-dees Kelantan Male Uniform - XXXL', 'Q-dees Kelantan Female Uniform - XS', 'Q-dees Kelantan Female Uniform - S', 'Q-dees Kelantan Female Uniform - M', 'Q-dees Kelantan Female Uniform - L', 'Q-dees Kelantan Female Uniform - XL', 'Q-dees Kelantan Female Uniform - XXL', 'Q-dees Kelantan Female Uniform - XXXL') ";
                                $resul=mysqli_query($connection, $sql);
                            
                            $level_count = 0;
                            $fees = 0;
                            while ($ro=mysqli_fetch_assoc($resul)) {
                                $product_name = $ro['product_name'];
                                $level_count = (empty($ro["level_count"]) ? "0" : $ro["level_count"]); 
                                $fees = (empty($ro["fees"]) ? "0" : $ro["fees"]); 
                           
                                $total_q_bag_male_uniform = $level_count * $fees;
                                $total_q_bag_male_uniforms += $total_q_bag_male_uniform;
                                $total_student_C += $level_count;
                            ?>

                            <tr class="">
                                <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold"><?php echo $product_name ?><span class="text-danger"></span>:</td>
                                
                                <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd q_dees" type="number" step="0.01" name="active_student[]"
                                        id="active_student" value="<?php echo $level_count ?>" readonly> <span
                                        class="edp_eq" readonly>✕ </span>
                                </td>
                                <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd q_dees" type="number" name="fee_rate[]" id="fee_rate"
                                        value="<?php echo number_format((float)$fees, 2, '.', '') ?>" readonly>
                                    <span class="edp_eq2">= </span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust2" type="number" step="0.01" name="amount[]" id="amount"
                                        value="<?php echo number_format((float)$total_q_bag_male_uniform, 2, '.', ''); ?>" readonly><br>
                                </td>
                            </tr>                
                            <?php
                                }
                            ?>

                            <tr class="">
                                <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">
                                    (v)Gymwear <?php $new_price_condition = ''; if(($monthyear == '2022-10' && $_GET['mode'] != 'EDIT') || ($_GET['mode'] == 'EDIT' && $month == '10' && ($year == '2022' || $year == '2022-2023'))) { 
                                        
                                        $new_price_condition = "and fl.`gymwear_adjust` = '37' "; 

                                        $student_id_list=mysqli_query($connection,"SELECT s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') and fl.`gymwear_adjust` != '37' and ps.student_entry_level != '' and s.student_status = 'A' and month(s.start_date_at_centre) = '".date('m')."' and s.centre_code='$centre_code' and s.deleted='0' and '$monthyear' BETWEEN DATE_FORMAT(fl.programme_date, '%Y-%m') AND DATE_FORMAT(fl.programme_date_end, '%Y-%m') group by ps.student_entry_level, s.id");
                                        
                                        if(mysqli_num_rows($student_id_list) > 0) 
                                        {
                                            $student_id_array = array();

                                            while($student_id_row = mysqli_fetch_array($student_id_list))
                                            {
                                                $student_id_array[] = $student_id_row['id'];
                                            }
                                            
                                            $new_price_condition .= " and s.id NOT IN (" . implode(", ", $student_id_array) . ")";
                                        }

                                    ?> (New Price) <?php } ?> <span class="text-danger"></span>:</td>
                                
                                <?php
                                    $sql="SELECT programme_package as fees_structure, active_student, fee_rate, amount
                                    from `declaration_child` where master_id=$master_id and form='Form1' and fee_structure_mame='Gymwear'";
                                    $result=mysqli_query($connection, $sql);
                                    $num_row = mysqli_num_rows($result);
                                    if ($num_row>0) {
                                        while ($row=mysqli_fetch_assoc($result)) {
                                            $level_count = $row['active_student'];
                                            $fees = $row["fee_rate"]; 
                                            $total_gymwear_adjust =  $row['amount'];
                                            $total_student_C += $level_count;
                                        }
                                    }
                                ?>
                                <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd q_dees" type="number" step="0.01" name="active_student[]"
                                        id="active_student" value="<?php echo $level_count ?>" readonly> <span
                                        class="edp_eq">✕ </span>
                                </td>
                                <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd q_dees" type="number" name="fee_rate[]" id="fee_rate"
                                        value="<?php echo $fees ?>" readonly>
                                    <span class="edp_eq2">= </span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust2" type="number" step="0.01" name="amount[]" id="amount"
                                        value="<?php echo number_format((float)$total_gymwear_adjust, 2, '.', ''); ?>" readonly><br>
                                </td>
                            </tr>
                            <?php if(($monthyear == '2022-10' && $_GET['mode'] != 'EDIT') || ($_GET['mode'] == 'EDIT' && $month == '10' && ($year == '2022' || $year == '2022-2023'))) { ?>

                                <tr class="">
                                    <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">
                                        (v)Gymwear (Old Price) <span class="text-danger"></span>:</td>
                                    
                                    <?php
                                        $sql="SELECT programme_package as fees_structure, active_student, fee_rate, amount
                                        from `declaration_child` where master_id=$master_id and form='Form1' and fee_structure_mame='Gymwear (Old Price)'";
                                        $result=mysqli_query($connection, $sql);
                                        $num_row = mysqli_num_rows($result);
                                        if ($num_row>0) {
                                            while ($row=mysqli_fetch_assoc($result)) {
                                                $level_count = $row['active_student'];
                                                $fees = $row["fee_rate"]; 
                                                $total_gymwear_adjust_old =  $row['amount'];
                                                $total_gymwear_adjust +=  $row['amount'];
                                                $total_student_C += $level_count;
                                            }
                                        }
                                    ?>
                                    <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                        <input class="edp_tsd q_dees" type="number" step="0.01" name="active_student[]"
                                            id="active_student" value="<?php echo $level_count ?>" readonly> <span
                                            class="edp_eq">✕ </span>
                                    </td>
                                    <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                        <input class="edp_tsd q_dees" type="number" name="fee_rate[]" id="fee_rate"
                                            value="<?php echo $fees ?>" readonly>
                                        <span class="edp_eq2">= </span>
                                    </td>
                                    <td style="border:none; text-align:center;" class="uk-width-1-10">
                                        <input class="school_adjust2" type="number" step="0.01" name="amount[]" id="amount"
                                            value="<?php echo number_format((float)$total_gymwear_adjust_old, 2, '.', ''); ?>" readonly><br>
                                    </td>
                                </tr>
                                <?php } ?>
                            <?php
                            $sql="SELECT programme_package as fees_structure, fee_structure_mame as product_name, active_student as level_count, fee_rate as fees, amount from `declaration_child` where master_id=$master_id and form='Form1' and fee_structure_mame like 'Q-dees Gymwear%' ORDER BY FIELD(product_name,'Q-dees Gymwear - XS','Q-dees Gymwear - S','Q-dees Gymwear - M','Q-dees Gymwear - L','Q-dees Gymwear - XL','Q-dees Gymwear - XXL','Q-dees Gymwear - XXXL','Q-dees Gymwear - Special Make','Q-dees Gymwear T-Shirt - XS','Q-dees Gymwear T-Shirt - S','Q-dees Gymwear T-Shirt - M','Q-dees Gymwear T-Shirt - L','Q-dees Gymwear T-Shirt - XL')";
                            $resul=mysqli_query($connection, $sql);
                            
                            //$num_row=mysqli_num_rows($resul);
                            $level_count = 0;
                            $fees = 0;
                            while ($ro=mysqli_fetch_assoc($resul)) {
                                $product_name = $ro['product_name'];
                                $level_count = (empty($ro["level_count"]) ? "0" : $ro["level_count"]); 
                                $fees = (empty($ro["fees"]) ? "0" : $ro["fees"]); 
                           
                                $total_q_bag_gymwear = $level_count * $fees;
                                $total_q_bag_gymwears += $total_q_bag_gymwear;
                                $total_student_C += $level_count;
                            ?>

                            <tr class="">
                                <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold"><?php echo $product_name ?><span class="text-danger"></span>:</td>
                                
                                <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd q_dees" type="number" step="0.01" name="active_student[]"
                                        id="active_student" value="<?php echo $level_count ?>" readonly> <span
                                        class="edp_eq" readonly>✕ </span>
                                </td>
                                <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd q_dees" type="number" name="fee_rate[]" id="fee_rate"
                                        value="<?php echo number_format((float)$fees, 2, '.', '') ?>" readonly>
                                    <span class="edp_eq2">= </span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust2" type="number" step="0.01" name="amount[]" id="amount"
                                        value="<?php echo number_format((float)$total_q_bag_gymwear, 2, '.', ''); ?>" readonly><br>
                                </td>
                            </tr>                
                            <?php
                                }
                            ?>


                            <tr class="">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10"></td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" style="display:none;" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo $total_student_C;  ?>" readonly>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="school_default" id="total_c2"
                                        placeholder="Total C"> <span style="font-size:14px;" class="edp_eq"
                                        readonly>RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="" type="number" step="0.01" name="total_c" id="total_c"
                                        value="<?php  $total_c = $total_pre_school_kits + $total_memories_to_cherish + $total_q_bag_adjust  + $total_uniform_adjust + $total_gymwear_adjust + $total_q_bag_gymwears + 
										$total_q_bag_male_uniforms; 
                                        echo number_format((float)$total_c, 2, '.', ''); ?>"
                                        readonly>
                                    <?php //$total_c += $total_uniform_adjust; ?>
                                </td>
                            </tr>
                        </table>
                        <table class="uk-table uk-table-small">
                            <tr>
                                <td class="uk-width-6-10" style="font-size:18px; font-weight:bold; border:none;">(1)
                                    ROYALTY
                                    FEE PAYABLE</td>
                                <td style="border:none;text-align: center;" class="uk-width-1-10">
                                    <span>Percentage</span>
                                </td>

                                <td style="border:none;text-align: center;" class="uk-width-1-10">
                                    <span>Gross Turnover</span>
                                </td>
                                <td style="border:none;text-align: center;" class="uk-width-1-10">
                                    <span>Total(1)</span>
                                </td>
                            </tr>
                            <tr class="">
                                <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">Royalty
                                    Fee(5% on Gross Tumover of A. + B.+ C.)<span class="text-danger"></span>:</td>
                                
                                <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                    <input style="position:relative;" class="edp_tsd royalty" type="number" step="0.01"
                                        name="active_student[]" id="active_student" value="5" readonly> <span
                                        style="margin-left: -30px;font-size: 20px;position: absolute;">%</span> <span
                                        style="" class="edp_eq">✕ </span>
                                </td>
                                <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd royalty" type="number" name="fee_rate[]" id="fee_rate"
                                        value="<?php  $gross_tumover = $total_a + $total_b + $total_c; 
                                       echo number_format((float)$gross_tumover, 2, '.', ''); ?>" readonly>
                                    <span class="edp_eq2">= </span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust" type="number" step="0.01" name="amount[]" id="amount"
                                        value="<?php  $royalty_fee = ($gross_tumover/100)*5; $total_1_2_3 += $royalty_fee;
                                        echo number_format((float)$royalty_fee, 2, '.', ''); ?>"
                                        readonly><br>
                                </td>
                            </tr>
                        </table>
                        <table class="uk-table uk-table-small">
                            <tr>
                                <td colspan="5" style="font-size:18px; font-weight:bold;"></td>
                            </tr>
                            <tr>
                                <td class="uk-width-6-10" style="font-size:18px; font-weight:bold; border:none;">(2) A&P
                                    FEE
                                    PAYABLE</td>
                                <td style="border:none;text-align: center;" class="uk-width-1-10">
                                    <span>Percentage</span>
                                </td>

                                <td style="border:none;text-align: center;" class="uk-width-1-10">
                                    <span>Total A</span>
                                </td>
                                <td style="border:none;text-align: center;" class="uk-width-1-10">
                                    <span>Total(2)</span>
                                </td>
                            </tr>
                            <tr class="">
                                <td style=" margin-top:50px;" class="uk-width-6-10 uk-text-bold">Advertising & Promotion
                                    Fee(3% on A. only)<span class="text-danger"></span>:</td>
                                
                                <td style="text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                    <input style="position:relative;" class="edp_tsd advertising" type="number"
                                        name="active_student[]" id="active_student" value="3" readonly> <span
                                        style="margin-left: -30px;font-size: 20px;position: absolute;">%</span> <span
                                        style="" class="edp_eq">✕ </span>
                                </td>
                                <td style="white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd advertising" type="number" name="fee_rate[]" id="fee_rate"
                                        value="<?php echo  number_format((float)$total_a - $total_mobile_adjust, 2, '.', ''); ?>" readonly> <span
                                        class="edp_eq2">= </span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust" type="number" step="0.01" name="amount[]" id="amount"
                                        value="<?php  $advertising_promotion_fee = (($total_a - $total_mobile_adjust)/100)*3; $total_1_2_3 += $advertising_promotion_fee;
                                        echo number_format((float)$advertising_promotion_fee, 2, '.', ''); ?>"
                                        readonly><br>
                                </td>
                            </tr>
                        </table>
                        <table class="uk-table uk-table-small">
                            <tr>
                                <td class="uk-width-6-10" style="font-size:18px; font-weight:bold; border:none;">(3)
                                    (Q-DEES
                                    PROGRAMME)<br> SOFTWARE LICENCE FEE PAYABLE</td>
                                <td style="border:none;text-align: center;" class="uk-width-1-10">
                                    <span>Prescriber<br>student</span>
                                </td>

                                <td style="border:none;text-align: center;" class="uk-width-1-10">
                                    <span>Prescriber<br>Rate per</span>
                                </td>
                                <td style="border:none;text-align: center;" class="uk-width-1-10">
                                    <span>Total(3)</span>
                                </td>
                            </tr>
                            <tr class="">
                                <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">Software
                                    License Fee-EDP<span class="text-danger"></span>:</td>
                                
                                <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                    <?php 
                           
                                $sql="SELECT programme_package as fees_structure, active_student, fee_rate, amount
                                from `declaration_child` where master_id=$master_id and form='Form1' and fee_structure_mame='Software License Fee-EDP'";
                                $result=mysqli_query($connection, $sql);
                                $num_row = mysqli_num_rows($result);
                                if ($num_row>0) {
                                    while ($row=mysqli_fetch_assoc($result)) {
                                        $total_EDP_student = $row['active_student'];
                                        // $fees = $row["fee_rate"]; 
                                        // $total_pre_school_kits =  $row['amount'];
                                        // $total_student_C += $level_count;
                                    }
                                }
                           
                                ?>
                                    <input class="edp_tsd software" type="number" step="0.01" name="active_student[]"
                                        id="active_student" value="<?php echo $total_EDP_student;?>" readonly> <span
                                        class="edp_eq">✕ </span>
                                </td>
                                <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd software" type="number" name="fee_rate[]" id="fee_rate"
                                        value="33.00" readonly> <span class="edp_eq2" readonly>= </span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust5" type="number" step="0.01" name="amount[]" id="amount"
                                        value="<?php  $total_EDP_student*33; $total_1_2_3 += $total_EDP_student*33;
                                        echo number_format((float)$total_EDP_student*33, 2, '.', ''); ?>"
                                        readonly><br>
                                </td>
                            </tr>
                            <tr class="">
                                <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">Software License Fee-QF1, QF2, QF3<span class="text-danger"></span>:</td>
                                
                                <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                    <?php 
                                        $sql="SELECT programme_package as fees_structure, active_student, fee_rate, amount
                                        from `declaration_child` where master_id=$master_id and form='Form1' and fee_structure_mame='Software License Fee-QF1, QF2, QF3'";
                                        $result=mysqli_query($connection, $sql);
                                        $num_row = mysqli_num_rows($result);
                                        if ($num_row>0) {
                                            while ($row=mysqli_fetch_assoc($result)) {
                                                $total_QF1_QF2_QF2_student = $row['active_student'];
                                                // $fees = $row["fee_rate"]; 
                                                // $total_pre_school_kits =  $row['amount'];
                                                // $total_student_C += $level_count;
                                            }
                                        }
                                ?>
                                    <input class="edp_tsd softwarefee" type="number" step="0.01" name="active_student[]"
                                        id="active_student" value="<?php echo $total_QF1_QF2_QF2_student;?>" readonly>
                                    <span class="edp_eq">✕ </span>
                                </td>
                                <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd softwarefee" type="number" name="fee_rate[]" id="fee_rate"
                                        value="33.00" readonly> <span class="edp_eq2">= </span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust5" type="number" step="0.01" name="amount[]" id="amount"
                                        value="<?php  $total_QF1_QF2_QF2_student*33; $total_1_2_3 += $total_QF1_QF2_QF2_student*33;
                                        echo number_format((float)$total_QF1_QF2_QF2_student*33, 2, '.', '');?>"
                                        readonly><br>
                                </td>
                            </tr>
                            <tr class="">
                                <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">Q-dees Mobile Apps–EDP, QF1, QF2, QF3<span class="text-danger"></span>:</td>
                                
                                <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                    <?php 
                            $sql="SELECT programme_package as fees_structure, active_student, fee_rate, amount
                            from `declaration_child` where master_id=$master_id and form='Form1' and fee_structure_mame='Q-dees Mobile Apps–EDP, QF1, QF2, QF3'";
                            $result=mysqli_query($connection, $sql);
                            $num_row = mysqli_num_rows($result);
                            if ($num_row>0) {
                                while ($row=mysqli_fetch_assoc($result)) {
                                    $total_EDP_QF1_QF2_QF2_student = $row['active_student'];
                                    // $fees = $row["fee_rate"]; 
                                    // $total_pre_school_kits =  $row['amount'];
                                    // $total_student_C += $level_count;
                                }
                            }
                            $total_EDP_QF1_QF2_QF2_student = 0;
                                ?>
                                    <input class="edp_tsd softwarefee" type="number" step="0.01" name="active_student[]"
                                        id="active_student" value="<?php echo $total_EDP_QF1_QF2_QF2_student;?>" readonly>
                                    <span class="edp_eq">✕ </span>
                                </td>
                                <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd softwarefee" type="number" name="fee_rate[]" id="fee_rate"
                                        value="11.25" readonly> <span class="edp_eq2">= </span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust5" type="number" step="0.01" name="amount[]" id="amount"
                                        value="<?php  $total_EDP_QF1_QF2_QF2_student*11.25; $total_1_2_3 += $total_EDP_QF1_QF2_QF2_student*11.25;
                                        echo number_format((float)$total_EDP_QF1_QF2_QF2_student*11.25, 2, '.', '');?>"
                                        readonly><br>
                                </td>
                            </tr>
                            <tr class="">
                                <td colspan="5">*Prescribed Rate multiplied by the Prescribed Student Number and payable
                                    for
                                    11 full months in a calendar year, irrespective of public holidays and
                                    weekends.<br>*The
                                    highest number of Students at the Approved Centre in a month, irrespective of public
                                    holidays and weekends.</td>

                            </tr>

                        </table>
                        <table class="uk-table uk-table-small">
                            <tr class="">
                                <td style=" margin-top:50px; border:none;" class="uk-width-6-10 uk-text-bold">Subtotal
                                    (Form 1)<span class="text-danger"></span>:</td>
                                <td style="border:none;" class="uk-width-1-10"></td>
                                <td style="border:none;" class="uk-width-1-10"></td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust56" type="number" step="0.01" name="grand_total"
                                        id="grand_total" value="<?php echo sprintf('%0.2f', $total_1_2_3); ?>"
                                        readonly><br>
                                </td>
                            </tr>
                            <tr class="">
                                <td style=" margin-top:50px;" class="uk-width-6-10 uk-text-bold">PAYABLE TO Q-DEES
                                    WORLDWIDE
                                    EDUSYSTEMS (M) SDN BHD (MBB 514196314454)<span class="text-danger"></span>:</td>
                                <td style="" class="uk-width-1-10"></td>
                                <td style="" class="uk-width-1-10"></td>
                                <td style=" text-align:center;" class="uk-width-1-10"></td>
                            </tr>
                        </table>
                        <table class="uk-table uk-table-small">
                            <tr>
                                <td colspan="5" style="font-size:18px; font-weight:bold;"></td>
                            </tr>
                            <tr>
                                <td style="margin-top:50px;border:none;" class="uk-width-5-10 uk-text-bold">Payment
                                    Status</td>
                                <td style="border:none;" class="uk-width-5-10 uk-text-bold">
                                    <?php  echo $row_edit['form_1_status']; ?>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>


            <div style="margin-top:50px;" id="frmdeclaration2">
                <div class="uk-grid">
                    <div class="uk-width-medium-4-10">
                        <table class="uk-table">
                            <tbody>
                                <tr>
                                    <td class="uk-text-bold">Centre Name:</td>
                                    <td><?php echo getCentreName($centre_code); ?></td>

                                </tr>
                                <tr>
                                    <td class="uk-text-bold">Centre Address:</td>
                                    <td><?php echo $address1; ?></td>
                                </tr>
                                <tr>
                                    <td colspan="5" class="uk-text-bold">Form: 2</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="uk-width-medium-4-10">
                        <table class="uk-table">
                            <tbody>
                            <tr>
                                <td class="uk-text-bold">Month/Year:</td>
                                <td><?php echo date("F", mktime(0, 0, 0, $month, 10)); //$month; //date("F"); ?>/<?php echo $year; //date("Y"); ?></td>
                            </tr>
                            <tr>
                                <td class="uk-text-bold">Date of Submission:</td>
                                <td> <?php echo $submited_date; //date("d/m/Y"); ?></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div style="padding-left: 0px; padding-right: 0px;" class="uk-width-medium-2-10">
                        
                    </div>
                </div>
                <div class="uk-grid">
                    <div class="uk-width-medium-10-10">
                        <table class="uk-table uk-table-small">
                            <tr>
                                <td colspan="5" style="font-size:18px; font-weight:bold;">CALCULATION OF GROSS TURNOVER
                                </td>
                            </tr>
                            <tr>
                                <td class="uk-width-6-10" style="font-size:16px; font-weight:bold; border:none;">A:
                                    School Fees <br>Foundation Programme</td>
                                <td style="border:none;text-align: center;" class="uk-width-1-10">
                                    <span>Active student</span>
                                </td>

                                <td style="border:none;text-align: center;" class="uk-width-1-10">
                                    <span>Centre Adjusted Fee<br class="bru">RM</span>
                                </td>
                                <td style="border:none;text-align: center;" class="uk-width-1-10">
                                    <span>Total <br class="bru">RM</span>
                                </td>
                            </tr>

                            <tr class="">
                                <td style=" margin-top:50px;border:none; font-size:18px;"
                                    class="uk-width-6-10 uk-text-bold">(i) International English</td>
                            </tr>
                            <tr class="">
                                <td style=" margin-top:20px;border:none; font-size:16px;"
                                    class="uk-width-6-10 uk-text-bold">EDP:</td>
                            </tr>
                            <?php
                            $sql="SELECT programme_package as fees_structure, active_student, fee_rate as enhanced_adjust, amount from `declaration_child` where master_id=$master_id and form='Form2' and fee_structure_mame='International English' and subject='EDP'";
							
							$result=mysqli_query($connection, $sql);
							$num_row = mysqli_num_rows($result);
                            $total_EDP_enhanced_student2 = 0;
                            $total_EDP_school_adjust2=0;
							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                            <tr class="">
                                <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">
                                    <?php echo $row['fees_structure']?><span class="text-danger"></span>:</td>
                                
                                <td style="border:none; text-align:center; white-space:nowrap;" class="uk-width-1-10">
                                <?php 
                                    $level_count = $row['active_student'];
                                    $total_EDP_enhanced_adjust += $row['amount'];
                                    $total_EDP_enhanced_student2 += $level_count;
                                ?>
                                    <input class="edp_tsd edp_tsdt" type="number" step="0.01" name="active_student[]"
                                        id="active_student" value="<?php echo $level_count ?>" readonly> <span
                                        class="edp_eq">✕ </span>
                                </td>
                                <td style="border:none; white-space:nowrap" class="uk-width-1-10">
                                    <input class="edp_tsd edp_tsdt" type="number" name="fee_rate[]" id="fee_rate"
                                        value="<?php echo $row['enhanced_adjust']?>" readonly> <span class="edp_eq2">=
                                    </span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust" type="number" step="0.01" name="amount[]" id="amount"
                                        value="<?php echo number_format((float)$level_count * $row['enhanced_adjust'], 2, '.', '');  ?>" readonly><br>
                                </td>
                            </tr>
                            <?php } } ?>
                            <tr class="">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total Student</td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo $total_EDP_enhanced_student2;  ?>"
                                        readonly>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total EDP" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo number_format((float)$total_EDP_enhanced_adjust, 2, '.', '');  ?>" readonly>
                                </td>
                            </tr>
                            <tr class="">
                                <td style=" margin-top:20px;border:none; font-size:16px;"
                                    class="uk-width-6-10 uk-text-bold">QF1:</td>
                            </tr>
                            <?php
                        $sql="SELECT programme_package as fees_structure, active_student, fee_rate as enhanced_adjust, amount
                        from `declaration_child` where master_id=$master_id and form='Form2' and fee_structure_mame='International English' and subject='QF1'";
							
							$result=mysqli_query($connection, $sql);
							$num_row = mysqli_num_rows($result);
                            $total_QF1_enhanced_student2 = 0;
							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                            <tr class="">
                                <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">
                                    <?php echo $row['fees_structure']?><span class="text-danger"></span>:</td>
                               
                                <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                    <?php 
                                $level_count = $row['active_student'];
                                $total_QF1_enhanced_adjust += $row['amount'];
                                $total_QF1_enhanced_student2 += $level_count;
                                ?>
                                    <input class="edp_tsd edp_tsdt" type="number" step="0.01" name="active_student[]"
                                        id="active_student" value="<?php echo $level_count ?>" readonly> <span
                                        class="edp_eq">✕ </span>
                                </td>
                                <td style="border:none; white-space:nowrap" class="uk-width-1-10">
                                    <input class="edp_tsd edp_tsdt" type="number" name="fee_rate[]" id="fee_rate"
                                        value="<?php echo $row['enhanced_adjust']?>" readonly> <span class="edp_eq2">=
                                    </span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust" type="number" step="0.01" name="amount[]" id="amount"
                                        value="<?php echo number_format((float)$level_count * $row['enhanced_adjust'], 2, '.', '');  ?>" readonly><br>
                                </td>
                            </tr>
                            <?php } } ?>
                            <tr class="">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total Student</td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo $total_QF1_enhanced_student2;  ?>"
                                        readonly>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total QF1" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo number_format((float)$total_QF1_enhanced_adjust, 2, '.', '');  ?>" readonly>
                                </td>
                            </tr>
                            <tr class="">
                                <td style=" margin-top:20px;border:none; font-size:16px;"
                                    class="uk-width-6-10 uk-text-bold">QF2:</td>
                            </tr>
                            <?php
                        $total_QF2_enhanced_student2 = 0;
                        $sql="SELECT programme_package as fees_structure, active_student, fee_rate as enhanced_adjust, amount
                            from `declaration_child` where master_id=$master_id and form='Form2' and fee_structure_mame='International English' and subject='QF2'";
							
							$result=mysqli_query($connection, $sql);
							$num_row = mysqli_num_rows($result);
                            $total_QF2_enhanced_student2 = 0;
							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                            <tr class="">
                                <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">
                                    <?php echo $row['fees_structure']?><span class="text-danger"></span>:</td>
                                
                                <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                    <?php 
                                        $level_count = $row['active_student'];
                                        $total_QF2_enhanced_adjust += $row['amount'];
                                        $total_QF2_enhanced_student2 += $level_count;
                                ?>
                                    <input class="edp_tsd edp_tsdt" type="number" step="0.01" name="active_student[]"
                                        id="active_student" value="<?php echo $level_count ?>"> <span class="edp_eq">✕
                                    </span>
                                </td>
                                <td style="border:none; white-space:nowrap" class="uk-width-1-10">
                                    <input class="edp_tsd edp_tsdt" type="number" name="fee_rate[]" id="fee_rate"
                                        value="<?php echo $row['enhanced_adjust']?>" readonly> <span class="edp_eq2">=
                                    </span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust" type="number" step="0.01" name="amount[]" id="amount"
                                        value="<?php echo number_format((float)$level_count * $row['enhanced_adjust'], 2, '.', '');  ?>" readonly><br>
                                </td>
                            </tr>
                            <?php } } ?>
                            <tr class="">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total Student</td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo $total_QF2_enhanced_student2;  ?>"
                                        readonly>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total QF2" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo number_format((float)$total_QF2_enhanced_adjust, 2, '.', '') ;  ?>" readonly>
                                </td>
                            </tr>
                            <tr class="">
                                <td style=" margin-top:20px;border:none; font-size:16px;"
                                    class="uk-width-6-10 uk-text-bold">QF3:</td>
                            </tr>
                        <?php
                            $total_QF3_enhanced_adjust = 0;
                            
                            $sql="SELECT programme_package as fees_structure, active_student, fee_rate as enhanced_adjust, amount from `declaration_child` where master_id=$master_id and form='Form2' and fee_structure_mame='International English' and subject='QF3'";
							
							$result=mysqli_query($connection, $sql);
							$num_row = mysqli_num_rows($result);
                            $total_QF3_enhanced_student2 = 0;
							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result)) {
						?>
                            <tr class="">
                                <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">
                                    <?php echo $row['fees_structure']?><span class="text-danger"></span>:</td>
                                
                                <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                    <?php 
                            
                                    $level_count = $row['active_student'];
                                    $total_QF3_enhanced_adjust += $row['amount'];
                                    $total_QF3_enhanced_student2 += $level_count;
                                ?>
                                    <input class="edp_tsd edp_tsdt" type="number" step="0.01" name="active_student[]"
                                        id="active_student" value="<?php echo $level_count ?>" readonly> <span
                                        class="edp_eq">✕ </span>
                                </td>
                                <td style="border:none; white-space:nowrap" class="uk-width-1-10">
                                    <input class="edp_tsd edp_tsdt" type="number" name="fee_rate[]" id="fee_rate"
                                        value="<?php echo $row['enhanced_adjust']?>" readonly> <span class="edp_eq2">=
                                    </span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust" type="number" step="0.01" name="amount[]" id="amount"
                                        value="<?php echo number_format((float)$level_count * $row['enhanced_adjust'], 2, '.', '');  ?>" readonly><br>
                                </td>
                            </tr>
                            <?php } } ?>
                            <tr class="">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total Student</td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo $total_QF3_enhanced_student2;  ?>"
                                        readonly>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total QF3" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo number_format((float)$total_QF3_enhanced_adjust, 2, '.', '');  ?>" readonly>
                                </td>
                            </tr>

                            <tr class="">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total Student (i)</td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f"
                                        value="<?php echo $total_enhanced_student2 = $total_EDP_enhanced_student2 + $total_QF1_enhanced_student2 + $total_QF2_enhanced_student2 + $total_QF3_enhanced_student2; $total_student_A2 += $total_enhanced_student2; ?>"
                                        readonly>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total (i)" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f"
                                        value="<?php  $total_enhanced_adjust = $total_EDP_enhanced_adjust + $total_QF1_enhanced_adjust  + $total_QF2_enhanced_adjust + $total_QF3_enhanced_adjust;  
                                        echo number_format((float)$total_enhanced_adjust, 2, '.', ''); ?>"
                                        readonly>
                                </td>
                            </tr>

                            <tr class="">
                                <td style=" margin-top:50px;border:none; font-size:18px;"
                                    class="uk-width-6-10 uk-text-bold">(ii) International Art</td>
                            </tr>
                            <tr class="">
                                <td style=" margin-top:20px;border:none; font-size:16px;"
                                    class="uk-width-6-10 uk-text-bold">EDP:</td>
                            </tr>
                            <?php
                        $sql="SELECT programme_package as fees_structure, active_student, fee_rate as international_adjust, amount from `declaration_child` where master_id=$master_id and form='Form2' and fee_structure_mame='International Art' and subject='EDP'";
							
							$result=mysqli_query($connection, $sql);
							$num_row = mysqli_num_rows($result);
                            $total_EDP_international_student2 = 0;
							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                            <tr class="">
                                <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">
                                    <?php echo $row['fees_structure']?><span class="text-danger"></span>:</td>
                              
                                <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                    <?php
                                    $level_count = $row['active_student'];
                                    $total_EDP_international_adjust += $row['amount'];
                                    $total_EDP_international_student2 += $level_count;
                                ?>
                                    <input class="edp_tsd multimedia" type="number" step="0.01" name="active_student[]"
                                        id="active_student" value="<?php echo $level_count ?>" readonly> <span
                                        class="edp_eq">✕ </span>
                                </td>
                                <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd multimedia" type="number" name="fee_rate[]" id="fee_rate"
                                        value="<?php echo $row['international_adjust']?>" readonly> <span
                                        class="edp_eq2">= </span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust" type="number" step="0.01" name="amount[]" id="amount"
                                        value="<?php echo number_format((float)$level_count * $row['international_adjust'], 2, '.', '');  ?>"
                                        readonly><br>
                                </td>
                            </tr>
                            <?php } } ?>
                            <tr class="">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total Student</td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo $total_EDP_international_student2;  ?>"
                                        readonly>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total EDP" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo number_format((float)$total_EDP_international_adjust, 2, '.', '');  ?>"
                                        readonly>
                                </td>
                            </tr>

                            <tr class="">
                                <td style=" margin-top:20px;border:none; font-size:16px;"
                                    class="uk-width-6-10 uk-text-bold">QF1:</td>
                            </tr>
                            <?php
                                $sql="SELECT programme_package as fees_structure, active_student, fee_rate as international_adjust, amount from `declaration_child` where master_id=$master_id and form='Form2' and fee_structure_mame='International Art' and subject='QF1'";
							
							$result=mysqli_query($connection, $sql);
							$num_row = mysqli_num_rows($result);
                            $total_QF1_international_student2 = 0;
							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                            <tr class="">
                                <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">
                                    <?php echo $row['fees_structure']?><span class="text-danger"></span>:</td>
                                
                                <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                    <?php
                                        $level_count = $row['active_student'];
                                        $total_QF1_international_adjust += $row['amount'];
                                        $total_QF1_international_student2 += $level_count;
                                ?>
                                    <input class="edp_tsd multimedia" type="number" step="0.01" name="active_student[]"
                                        id="active_student" value="<?php echo $level_count ?>" readonly> <span
                                        class="edp_eq">✕ </span>
                                </td>
                                <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd multimedia" type="number" name="fee_rate[]" id="fee_rate"
                                        value="<?php echo $row['international_adjust']?>" readonly> <span
                                        class="edp_eq2">= </span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust" type="number" step="0.01" name="amount[]" id="amount"
                                        value="<?php echo number_format((float)$level_count * $row['international_adjust'], 2, '.', '');  ?>"
                                        readonly><br>
                                </td>
                            </tr>
                            <?php } } ?>
                            <tr class="">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total Student</td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo $total_QF1_international_student2;  ?>"
                                        readonly>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total QF1" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo number_format((float)$total_QF1_international_adjust, 2, '.', '');  ?>"
                                        readonly>
                                </td>
                            </tr>

                            <tr class="">
                                <td style=" margin-top:20px;border:none; font-size:16px;"
                                    class="uk-width-6-10 uk-text-bold">QF2:</td>
                            </tr>
                            <?php
                            $sql="SELECT programme_package as fees_structure, active_student, fee_rate as international_adjust, amount from `declaration_child` where master_id=$master_id and form='Form2' and fee_structure_mame='International Art' and subject='QF2'";
							
							$result=mysqli_query($connection, $sql);
							$num_row = mysqli_num_rows($result);
                            $total_QF2_international_student2 = 0;
							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                            <tr class="">
                                <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">
                                    <?php echo $row['fees_structure']?><span class="text-danger"></span>:</td>
                                
                                <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                    <?php
                                        $level_count = $row['active_student'];
                                        $total_QF2_international_adjust += $row['amount'];
                                        $total_QF2_international_student2 += $level_count;
                                ?>
                                    <input class="edp_tsd multimedia" type="number" step="0.01" name="active_student[]"
                                        id="active_student" value="<?php echo $level_count ?>" readonly> <span
                                        class="edp_eq">✕ </span>
                                </td>
                                <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd multimedia" type="number" name="fee_rate[]" id="fee_rate"
                                        value="<?php echo $row['international_adjust']?>" readonly> <span
                                        class="edp_eq2">= </span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust" type="number" step="0.01" name="amount[]" id="amount"
                                        value="<?php echo number_format((float)$level_count * $row['international_adjust'], 2, '.', '');  ?>"
                                        readonly><br>
                                </td>
                            </tr>
                            <?php } } ?>
                            <tr class="">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total Student</td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo $total_QF2_international_student2;  ?>"
                                        readonly>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total QF2" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo number_format((float)$total_QF2_international_adjust, 2, '.', '');  ?>"
                                        readonly>
                                </td>
                            </tr>
                            <tr class="">
                                <td style=" margin-top:20px;border:none; font-size:16px;"
                                    class="uk-width-6-10 uk-text-bold">QF3:</td>
                            </tr>
                            <?php
                            $total_QF3_international_adjust = 0;
                            $sql="SELECT programme_package as fees_structure, active_student, fee_rate as international_adjust, amount from `declaration_child` where master_id=$master_id and form='Form2' and fee_structure_mame='International Art' and subject='QF3'";
							
							$result=mysqli_query($connection, $sql);
							$num_row = mysqli_num_rows($result);
                            $total_QF3_international_student2 = 0;
							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                            <tr class="">
                                <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">
                                    <?php echo $row['fees_structure']?><span class="text-danger"></span>:</td>
                                
                                <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                <?php
                                    $level_count = $row['active_student'];
                                    $total_QF3_international_adjust += $row['amount'];
                                    $total_QF3_international_student2 += $level_count;
                                ?>
                                    <input class="edp_tsd multimedia" type="number" step="0.01" name="active_student[]"
                                        id="active_student" value="<?php echo $level_count ?>" readonly> <span
                                        class="edp_eq">✕ </span>
                                </td>
                                <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd multimedia" type="number" name="fee_rate[]" id="fee_rate"
                                        value="<?php echo $row['international_adjust']?>" readonly> <span
                                        class="edp_eq2">= </span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust" type="number" step="0.01" name="amount[]" id="amount"
                                        value="<?php echo number_format((float)$level_count * $row['international_adjust'], 2, '.', '');  ?>"
                                        readonly><br>
                                </td>
                            </tr>
                            <?php } } ?>
                            <tr class="">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total Student</td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo $total_QF3_international_student2;  ?>"
                                        readonly>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total QF3" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo number_format((float)$total_QF3_international_adjust, 2, '.', '');  ?>"
                                        readonly>
                                </td>
                            </tr>

                            <tr class="">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total Student (ii)</td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f"
                                        value="<?php echo $total_international_student2 = $total_EDP_international_student2 + $total_QF1_international_student2 + $total_QF2_international_student2 + $total_QF3_international_student2; $total_student_A2 += $total_international_student2; ?>"
                                        readonly>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total (ii)" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f"
                                        value="<?php  $total_international_adjust = $total_EDP_international_adjust + $total_QF1_international_adjust  + $total_QF2_international_adjust + $total_QF3_international_adjust; 
                                        echo number_format((float)$total_international_adjust, 2, '.', '') ?>"
                                        readonly>
                                </td>
                            </tr>
                            <tr class="">
                                <td style=" margin-top:50px;border:none;"></td>
                            </tr>
                            <tr class="">
                                <td style=" margin-top:50px;border:none; font-size:18px;"
                                    class="uk-width-6-10 uk-text-bold">(iii) Mandarin</td>
                            </tr>

                            <tr class="">
                                <td style=" margin-top:20px;border:none; font-size:16px;"
                                    class="uk-width-6-10 uk-text-bold">EDP:</td>
                            </tr>
                            <?php
                            $sql="SELECT programme_package as fees_structure, active_student, fee_rate as mandarin_adjust, amount from `declaration_child` where master_id=$master_id and form='Form2' and fee_structure_mame='Mandarin' and subject='EDP'";
							
							$result=mysqli_query($connection, $sql);
							$num_row = mysqli_num_rows($result);
                            $total_EDP_mandarin_student2 = 0;
							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                            <tr class="">
                                <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold"> <span class="text-danger"></span><?php echo $row['fees_structure']?></td>
                                
                                <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                    <?php
                                        $level_count = $row['active_student'];
                                        $total_EDP_mandarin_adjust += $row['amount'];
                                        $total_EDP_mandarin_student2 += $level_count;
                                ?>
                                    <input class="edp_tsd afternoon" type="number" step="0.01" name="active_student[]"
                                        id="afternoon_s" value="<?php echo $level_count ?>" readonly> <span
                                        class="edp_eq">✕ </span>
                                </td>
                                <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd afternoon" type="number" name="fee_rate[]" id="fee_rate"
                                        value="<?php echo $row['mandarin_adjust']?>" readonly> <span class="edp_eq2">=
                                    </span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust" type="number" step="0.01" name="amount[]" id="amount"
                                        value="<?php echo number_format((float)$level_count * $row['mandarin_adjust'], 2, '.', '');  ?>" readonly><br>
                                </td>
                            </tr>
                            <?php } } ?>
                            <tr class="">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total Student</td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo $total_EDP_mandarin_student2;  ?>"
                                        readonly>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total EDP" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo number_format((float)$total_EDP_mandarin_adjust, 2, '.', '');  ?>" readonly>
                                </td>
                            </tr>

                            <tr class="">
                                <td style=" margin-top:20px;border:none; font-size:16px;"
                                    class="uk-width-6-10 uk-text-bold">QF1:</td>
                            </tr>
                            <?php
                            $sql="SELECT programme_package as fees_structure, active_student, fee_rate as mandarin_adjust, amount from `declaration_child` where master_id=$master_id and form='Form2' and fee_structure_mame='Mandarin' and subject='QF1'";
							
							$result=mysqli_query($connection, $sql);
							$num_row = mysqli_num_rows($result);
                            $total_QF1_mandarin_student2 = 0;
							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                            <tr class="">
                                <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold"> <span class="text-danger"></span><?php echo $row['fees_structure']?></td>
                                
                                <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                <?php
                                    $level_count = $row['active_student'];
                                    $total_QF1_mandarin_adjust += $row['amount'];
                                    $total_QF1_mandarin_student2 += $level_count;
                                ?>
                                    <input class="edp_tsd afternoon" type="number" step="0.01" name="active_student[]"
                                        id="active_student" value="<?php echo $level_count ?>" readonly> <span
                                        class="edp_eq">✕ </span>
                                </td>
                                <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd afternoon" type="number" name="fee_rate[]" id="fee_rate"
                                        value="<?php echo $row['mandarin_adjust']?>" readonly> <span class="edp_eq2">=
                                    </span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust" type="number" step="0.01" name="amount[]" id="amount"
                                        value="<?php echo number_format((float)$level_count * $row['mandarin_adjust'], 2, '.', '');  ?>" readonly><br>
                                </td>
                            </tr>
                            <?php } } ?>
                            <tr class="">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total Student</td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo $total_QF1_mandarin_student2;  ?>"
                                        readonly>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total QF1" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo number_format((float)$total_QF1_mandarin_adjust, 2, '.', '');  ?>" readonly>
                                </td>
                            </tr>

                            <tr class="">
                                <td style=" margin-top:20px;border:none; font-size:16px;"
                                    class="uk-width-6-10 uk-text-bold">QF2:</td>
                            </tr>

                            <?php
                                $sql="SELECT programme_package as fees_structure, active_student, fee_rate as mandarin_adjust, amount from `declaration_child` where master_id=$master_id and form='Form2' and fee_structure_mame='Mandarin' and subject='QF2'";
							
							$result=mysqli_query($connection, $sql);
							$num_row = mysqli_num_rows($result);
                            $total_QF2_mandarin_student2 = 0;
							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                            <tr class="">
                                <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold"> <span class="text-danger"></span><?php echo $row['fees_structure']?></td>
                               
                                <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                    <?php
                                    $level_count = $row['active_student'];
                                    $total_QF2_mandarin_adjust += $row['amount'];
                                    $total_QF2_mandarin_student2 += $level_count;
                                ?>
                                    <input class="edp_tsd afternoon" type="number" step="0.01" name="active_student[]"
                                        id="active_student" value="<?php echo $level_count ?>" readonly> <span
                                        class="edp_eq">✕ </span>
                                </td>
                                <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd afternoon" type="number" name="fee_rate[]" id="fee_rate"
                                        value="<?php echo $row['mandarin_adjust']?>" readonly> <span class="edp_eq2">=
                                    </span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust" type="number" step="0.01" name="amount[]" id="amount"
                                        value="<?php echo number_format((float)$level_count * $row['mandarin_adjust'], 2, '.', '');  ?>" readonly><br>
                                </td>
                            </tr>
                            <?php } } ?>
                            <tr class="">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total Student</td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo $total_QF2_mandarin_student2;  ?>"
                                        readonly>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total QF2" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo number_format((float)$total_QF2_mandarin_adjust, 2, '.', '');  ?>" readonly>
                                </td>
                            </tr>

                            <tr class="">
                                <td style=" margin-top:20px;border:none; font-size:16px;"
                                    class="uk-width-6-10 uk-text-bold">QF3:</td>
                            </tr>

                            <?php
                        $total_QF3_mandarin_adjust = 0;
                        $sql="SELECT programme_package as fees_structure, active_student, fee_rate as mandarin_adjust, amount from `declaration_child` where master_id=$master_id and form='Form2' and fee_structure_mame='Mandarin' and subject='QF3'";
							
							$result=mysqli_query($connection, $sql);
							$num_row = mysqli_num_rows($result);
                            $total_QF3_mandarin_student2 = 0;
							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                            <tr class="">
                                <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold"> <span
                                        class="text-danger"></span><?php echo $row['fees_structure']?></td>
                                
                                <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                    <?php
                                $level_count = $row['active_student'];
                                $total_QF3_mandarin_adjust += $row['amount'];
                                $total_QF3_mandarin_student2 += $level_count;
                                ?>
                                    <input class="edp_tsd afternoon" type="number" step="0.01" name="active_student[]"
                                        id="active_student" value="<?php echo $level_count ?>" readonly> <span
                                        class="edp_eq">✕ </span>
                                </td>
                                <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd afternoon" type="number" name="fee_rate[]" id="fee_rate"
                                        value="<?php echo $row['mandarin_adjust']?>" readonly> <span class="edp_eq2">=
                                    </span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust" type="number" step="0.01" name="amount[]" id="amount"
                                        value="<?php echo number_format((float)$level_count * $row['mandarin_adjust'], 2, '.', '');  ?>" readonly><br>
                                </td>
                            </tr>
                            <?php } } ?>
                            <tr class="">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total Student</td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo $total_QF3_mandarin_student2;  ?>"
                                        readonly>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total QF3" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo number_format((float)$total_QF3_mandarin_adjust, 2, '.', '');  ?>" readonly>
                                </td>
                            </tr>

                            <tr class="">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total Student</td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f"
                                        value="<?php echo $total_mandarin_student2 = $total_EDP_mandarin_student2 + $total_QF1_mandarin_student2 + $total_QF2_mandarin_student2 + $total_QF3_mandarin_student2; $total_student_A2 += $total_mandarin_student2; ?>"
                                        readonly>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total (iii)" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f"
                                        value="<?php  $total_mandarin_adjust = $total_EDP_mandarin_adjust + $total_QF1_mandarin_adjust  + $total_QF2_mandarin_adjust + $total_QF3_mandarin_adjust; 
                                        echo number_format((float)$total_mandarin_adjust, 2, '.', '') ?>"
                                        readonly>
                                </td>
                            </tr>
                            <tr class="">
                                <td style=" margin-top:50px;border:none; font-size:18px;"
                                    class="uk-width-6-10 uk-text-bold">(iv) IQ Math</td>
                            </tr>

                            <tr class="">
                                <td style=" margin-top:20px;border:none; font-size:16px;"
                                    class="uk-width-6-10 uk-text-bold">EDP:</td>
                            </tr>
                            <?php
                                $sql="SELECT programme_package as fees_structure, active_student, fee_rate as iq_math_adjust, amount from `declaration_child` where master_id=$master_id and form='Form2' and fee_structure_mame='IQ Math' and subject='EDP'";
							
							$result=mysqli_query($connection, $sql);
							$num_row = mysqli_num_rows($result);
                            $total_EDP_iq_math_student2 = 0;
							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                            <tr class="">
                                <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold"> <span
                                        class="text-danger"></span><?php echo $row['fees_structure']?></td>
                               
                                <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                    <?php
                                    $level_count = $row['active_student'];
                                    $total_EDP_iq_math_adjust += $row['amount'];
                                    $total_EDP_iq_math_student2 += $level_count;
                                ?>
                                    <input class="edp_tsd afternoon" type="number" step="0.01" name="active_student[]"
                                        id="active_student" value="<?php echo $level_count ?>" readonly> <span
                                        class="edp_eq">✕ </span>
                                </td>
                                <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd afternoon" type="number" name="fee_rate[]" id="fee_rate"
                                        value="<?php echo $row['iq_math_adjust']?>" readonly> <span class="edp_eq2">=
                                    </span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust" type="number" step="0.01" name="amount[]" id="amount"
                                        value="<?php echo number_format((float)$level_count * $row['iq_math_adjust'], 2, '.', '');  ?>" readonly><br>
                                </td>
                            </tr>
                            <?php } } ?>
                            <tr class="">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total Student</td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo $total_EDP_iq_math_student2;  ?>"
                                        readonly>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total EDP" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo number_format((float)$total_EDP_iq_math_adjust, 2, '.', '');  ?>" readonly>
                                </td>
                            </tr>

                            <tr class="">
                                <td style=" margin-top:20px;border:none; font-size:16px;"
                                    class="uk-width-6-10 uk-text-bold">QF1:</td>
                            </tr>
                            <?php
                                $sql="SELECT programme_package as fees_structure, active_student, fee_rate as iq_math_adjust, amount from `declaration_child` where master_id=$master_id and form='Form2' and fee_structure_mame='IQ Math' and subject='QF1'";
							
							$result=mysqli_query($connection, $sql);
							$num_row = mysqli_num_rows($result);
                            $total_QF1_iq_math_student2 = 0;
							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                            <tr class="">
                                <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold"> <span
                                        class="text-danger"></span><?php echo $row['fees_structure']?></td>
                               
                                <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                    <?php
                                    $level_count = $row['active_student'];
                                    $total_QF1_iq_math_adjust += $row['amount'];
                                    $total_QF1_iq_math_student2 += $level_count;
                                ?>
                                    <input class="edp_tsd afternoon" type="number" step="0.01" name="active_student[]"
                                        id="active_student" value="<?php echo $level_count ?>" readonly> <span
                                        class="edp_eq">✕ </span>
                                </td>
                                <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd afternoon" type="number" name="fee_rate[]" id="fee_rate"
                                        value="<?php echo $row['iq_math_adjust']?>" readonly> <span class="edp_eq2">=
                                    </span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust" type="number" step="0.01" name="amount[]" id="amount"
                                        value="<?php echo number_format((float)$level_count * $row['iq_math_adjust'], 2, '.', '');  ?>" readonly><br>
                                </td>
                            </tr>
                            <?php } } ?>
                            <tr class="">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total Student</td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo $total_QF1_iq_math_student2;  ?>"
                                        readonly>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total QF1" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo number_format((float)$total_QF1_iq_math_adjust, 2, '.', '');  ?>" readonly>
                                </td>
                            </tr>

                            <tr class="">
                                <td style=" margin-top:20px;border:none; font-size:16px;"
                                    class="uk-width-6-10 uk-text-bold">QF2:</td>
                            </tr>

                            <?php
                            $sql="SELECT programme_package as fees_structure, active_student, fee_rate as iq_math_adjust, amount from `declaration_child` where master_id=$master_id and form='Form2' and fee_structure_mame='IQ Math' and subject='QF2'";
							
							$result=mysqli_query($connection, $sql);
							$num_row = mysqli_num_rows($result);
                            $total_QF2_iq_math_student2 = 0;
							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                            <tr class="">
                                <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold"> <span
                                        class="text-danger"></span><?php echo $row['fees_structure']?></td>
                                
                                <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                    <?php
                            $level_count = $row['active_student'];
                            $total_QF2_iq_math_adjust += $row['amount'];
                            $total_QF2_iq_math_student2 += $level_count;
                                ?>
                                    <input class="edp_tsd afternoon" type="number" step="0.01" name="active_student[]"
                                        id="active_student" value="<?php echo $level_count ?>" readonly> <span
                                        class="edp_eq">✕ </span>
                                </td>
                                <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd afternoon" type="number" name="fee_rate[]" id="fee_rate"
                                        value="<?php echo $row['iq_math_adjust']?>" readonly> <span class="edp_eq2">=
                                    </span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust" type="number" step="0.01" name="amount[]" id="amount"
                                        value="<?php echo number_format((float)$level_count * $row['iq_math_adjust'], 2, '.', '');  ?>" readonly><br>
                                </td>
                            </tr>
                            <?php } } ?>
                            <tr class="">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total Student</td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo $total_QF2_iq_math_student2;  ?>"
                                        readonly>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total QF2" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo number_format((float)$total_QF2_iq_math_adjust, 2, '.', '');  ?>" readonly>
                                </td>
                            </tr>

                            <tr class="">
                                <td style=" margin-top:20px;border:none; font-size:16px;"
                                    class="uk-width-6-10 uk-text-bold">QF3:</td>
                            </tr>

                            <?php
                        $total_QF3_iq_math_adjust = 0;
                        $sql="SELECT programme_package as fees_structure, active_student, fee_rate as iq_math_adjust, amount from `declaration_child` where master_id=$master_id and form='Form2' and fee_structure_mame='IQ Math' and subject='QF3'";
							
							$result=mysqli_query($connection, $sql);
							$num_row = mysqli_num_rows($result);
                            $total_QF3_iq_math_student2 = 0;
							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                            <tr class="">
                                <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold"> <span
                                        class="text-danger"></span><?php echo $row['fees_structure']?></td>
                                
                                <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                    <?php
                            
                                $level_count = $row['active_student'];
                                $total_QF3_iq_math_adjust += $row['amount'];
                                $total_QF3_iq_math_student2 += $level_count;
                                ?>
                                    <input class="edp_tsd afternoon" type="number" step="0.01" name="active_student[]"
                                        id="active_student" value="<?php echo $level_count ?>" readonly> <span
                                        class="edp_eq">✕ </span>
                                </td>
                                <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd afternoon" type="number" name="fee_rate[]" id="fee_rate"
                                        value="<?php echo $row['iq_math_adjust']?>" readonly> <span class="edp_eq2">=
                                    </span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust" type="number" step="0.01" name="amount[]" id="amount"
                                        value="<?php echo number_format((float)$level_count * $row['iq_math_adjust'], 2, '.', '');  ?>" readonly><br>
                                </td>
                            </tr>
                            <?php } } ?>
                            <tr class="">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total Student</td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo $total_QF3_iq_math_student2;  ?>"
                                        readonly>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total QF3" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo number_format((float)$total_QF3_iq_math_adjust, 2, '.', '');  ?>" readonly>
                                </td>
                            </tr>

                            <tr class="">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total Student (iv)</td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f"
                                        value="<?php echo $total_iq_math_student2 = $total_EDP_iq_math_student2 + $total_QF1_iq_math_student2 + $total_QF2_iq_math_student2 + $total_QF3_iq_math_student2; $total_student_A2 += $total_iq_math_student2; ?>"
                                        readonly>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total (iv)" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f"
                                        value="<?php  $total_iq_math_adjust = $total_EDP_iq_math_adjust + $total_QF1_iq_math_adjust  + $total_QF2_iq_math_adjust + $total_QF3_iq_math_adjust; 
                                        echo number_format((float)$total_iq_math_adjust, 2, '.', '') ?>"
                                        readonly>
                                </td>
                            </tr>


                            <tr class="">
                                <td style=" margin-top:50px;border:none; font-size:18px;"
                                    class="uk-width-6-10 uk-text-bold">(v) Robotics Plus</td>
                            </tr>
                            <!-- Robotic Plus edp start -->

                            <tr class="">
                                <td style=" margin-top:20px;border:none; font-size:16px;"
                                    class="uk-width-6-10 uk-text-bold">EDP:</td>
                            </tr>
                            <?php
                                $sql="SELECT programme_package as fees_structure, active_student, fee_rate as robotic_plus_adjust, amount from `declaration_child` where master_id=$master_id and form='Form2' and fee_structure_mame='Robotic Plus' and subject='EDP'";
                        
                                $result=mysqli_query($connection, $sql);
                                $num_row = mysqli_num_rows($result);
                                $total_EDP_robotic_plus_student2 = 0;
                                if ($num_row>0) {
                                    while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                                        <tr class="">
                                            <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold"> <span
                                                    class="text-danger"></span><?php echo $row['fees_structure']?></td>
                                            
                                            <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                                <?php
                                                    $level_count = $row['active_student'];
                                                    $total_EDP_robotic_plus_adjust += $row['amount'];
                                                    $total_EDP_robotic_plus_student2 += $level_count;
                                                ?>
                                                <input class="edp_tsd afternoon" type="number" step="0.01" name="active_student[]"
                                                    id="active_student" value="<?php echo $level_count ?>" readonly> <span
                                                    class="edp_eq">✕ </span>
                                            </td>
                                            <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                                <input class="edp_tsd afternoon" type="number" name="fee_rate[]" id="fee_rate"
                                                    value="<?php echo $row['robotic_plus_adjust']?>" readonly> <span class="edp_eq2">=
                                                </span>
                                            </td>
                                            <td style="border:none; text-align:center;" class="uk-width-1-10">
                                                <input class="school_adjust" type="number" step="0.01" name="amount[]" id="amount"
                                                    value="<?php echo number_format((float)$level_count * $row['robotic_plus_adjust'], 2, '.', '');  ?>" readonly><br>
                                            </td>
                                        </tr>
                            <?php } } ?>
                            <tr class="">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total Student</td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo $total_EDP_robotic_plus_student2;  ?>"
                                        readonly>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total EDP" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo number_format((float)$total_EDP_robotic_plus_adjust, 2, '.', '');  ?>" readonly>
                                </td>
                            </tr>

                            <!-- Mobile App edp end -->
                            <!-- Mobile App QF1 start -->

                            <tr class="">
                                <td style=" margin-top:20px;border:none; font-size:16px;"
                                    class="uk-width-6-10 uk-text-bold">QF1:</td>
                            </tr>
                            <?php
                                $sql="SELECT programme_package as fees_structure, active_student, fee_rate as robotic_plus_adjust, amount from `declaration_child` where master_id=$master_id and form='Form2' and fee_structure_mame='Robotic Plus' and subject='QF1'";
							
                                $result=mysqli_query($connection, $sql);
                                $num_row = mysqli_num_rows($result);
                                $total_QF1_robotic_plus_student2 = 0;

                                if ($num_row>0) {
                                    while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                            <tr class="">
                                <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold"> <span
                                        class="text-danger"></span><?php echo $row['fees_structure']?></td>
                                
                                <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                    <?php
                                        $level_count = $row['active_student'];
                                        $total_QF1_robotic_plus_adjust += $row['amount'];
                                        $total_QF1_robotic_plus_student2 += $level_count;
                                    ?>
                                    <input class="edp_tsd afternoon" type="number" step="0.01" name="active_student[]"
                                        id="active_student" value="<?php echo $level_count ?>" readonly> <span
                                        class="edp_eq">✕ </span>
                                </td>
                                <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd afternoon" type="number" name="fee_rate[]" id="fee_rate"
                                        value="<?php echo $row['robotic_plus_adjust']?>" readonly> <span class="edp_eq2">=
                                    </span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust" type="number" step="0.01" name="amount[]" id="amount"
                                        value="<?php echo number_format((float)$level_count * $row['robotic_plus_adjust'], 2, '.', '');  ?>" readonly><br>
                                </td>
                            </tr>
                            <?php } } ?>
                            <tr class="">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total Student</td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo $total_QF1_robotic_plus_student2;  ?>"
                                        readonly>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total QF1" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo number_format((float)$total_QF1_robotic_plus_adjust, 2, '.', '');  ?>" readonly>
                                </td>
                            </tr>
                            <!-- Robotic Plus QF1 end -->
                            <!-- Robotic Plus QF2 start -->

                            <tr class="">
                                <td style=" margin-top:20px;border:none; font-size:16px;"
                                    class="uk-width-6-10 uk-text-bold">QF2:</td>
                            </tr>

                            <?php
                                $sql="SELECT programme_package as fees_structure, active_student, fee_rate as robotic_plus_adjust, amount from `declaration_child` where master_id=$master_id and form='Form2' and fee_structure_mame='Robotic Plus' and subject='QF2'";
							
                                $result=mysqli_query($connection, $sql);
                                $num_row = mysqli_num_rows($result);
                                $total_QF2_robotic_plus_student2 = 0;

							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                            <tr class="">
                                <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold"> <span
                                        class="text-danger"></span><?php echo $row['fees_structure']?></td>
                                <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                    <?php
                                        $level_count = $row['active_student'];
                                        $total_QF2_robotic_plus_adjust += $row['amount'];
                                        $total_QF2_robotic_plus_student2 += $level_count;
                                    ?>
                                    <input class="edp_tsd afternoon" type="number" step="0.01" name="active_student[]"
                                        id="active_student" value="<?php echo $level_count ?>" readonly> <span
                                        class="edp_eq">✕ </span>
                                </td>
                                <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd afternoon" type="number" name="fee_rate[]" id="fee_rate"
                                        value="<?php echo $row['robotic_plus_adjust']?>" readonly> <span class="edp_eq2">=
                                    </span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust" type="number" step="0.01" name="amount[]" id="amount"
                                        value="<?php echo number_format((float)$level_count * $row['robotic_plus_adjust'], 2, '.', '');  ?>" readonly><br>
                                </td>
                            </tr>
                            <?php } } ?>
                            <tr class="">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total Student</td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo $total_QF2_robotic_plus_student2;  ?>"
                                        readonly>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total QF2" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo number_format((float)$total_QF2_robotic_plus_adjust, 2, '.', '');  ?>" readonly>
                                </td>
                            </tr>
                            <!-- Robotic Plus QF2 end -->
                            <!-- Robotic Plus QF3 start -->

                            <tr class="">
                                <td style=" margin-top:20px;border:none; font-size:16px;"
                                    class="uk-width-6-10 uk-text-bold">QF3:</td>
                            </tr>

                            <?php
                                $total_QF3_robotic_plus_adjust = 0;
                            
                                $sql="SELECT programme_package as fees_structure, active_student, fee_rate as robotic_plus_adjust, amount from `declaration_child` where master_id=$master_id and form='Form2' and fee_structure_mame='Robotic Plus' and subject='QF3'";
							
                                $result=mysqli_query($connection, $sql);
                                $num_row = mysqli_num_rows($result);
                                $total_QF3_robotic_plus_student2 = 0;

                                if ($num_row>0) {
                                    while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                            <tr class="">
                                <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold"> <span
                                        class="text-danger"></span><?php echo $row['fees_structure']?></td>
                                
                                <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                    <?php
                                        $level_count = $row['active_student'];
                                        $total_QF3_robotic_plus_adjust += $row['amount'];
                                        $total_QF3_robotic_plus_student2 += $level_count;
                                    ?>
                                    <input class="edp_tsd afternoon" type="number" step="0.01" name="active_student[]"
                                        id="active_student" value="<?php echo $level_count ?>" readonly> <span
                                        class="edp_eq">✕ </span>
                                </td>
                                <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd afternoon" type="number" name="fee_rate[]" id="fee_rate"
                                        value="<?php echo $row['robotic_plus_adjust']?>" readonly> <span class="edp_eq2">=
                                    </span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust" type="number" step="0.01" name="amount[]" id="amount"
                                        value="<?php echo number_format((float)$level_count * $row['robotic_plus_adjust'], 2, '.', '');  ?>" readonly><br>
                                </td>
                            </tr>
                            <?php } } ?>
                            <tr class="">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total Student</td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo $total_QF3_robotic_plus_student2;  ?>"
                                        readonly>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total QF3" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo number_format((float)$total_QF3_robotic_plus_adjust, 2, '.', '');  ?>" readonly>
                                </td>
                            </tr>
                            <!-- Robotic Plus QF3 end -->

                            <tr class="">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total Student (v)</td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f"
                                        value="<?php echo $total_robotic_plus_student2 = $total_EDP_robotic_plus_student2 + $total_QF1_robotic_plus_student2 + $total_QF2_robotic_plus_student2 + $total_QF3_robotic_plus_student2; $total_student_A2 += $total_robotic_plus_student2; ?>"
                                        readonly>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total (v)" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f"
                                        value="<?php  $total_robotic_plus_adjust = $total_EDP_robotic_plus_adjust + $total_QF1_robotic_plus_adjust  + $total_QF2_robotic_plus_adjust + $total_QF3_robotic_plus_adjust; 
                                        echo number_format((float)$total_robotic_plus_adjust, 2, '.', '') ?>"
                                        readonly>
                                </td>
                            </tr>
                            <!-- Robotic Plus end -->

                            <tr class="">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total A</td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo $total_student_A2; ?>" readonly>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total A" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f"
                                        value="<?php  $total_a2 = $total_iq_math_adjust+$total_mandarin_adjust+$total_international_adjust+$total_enhanced_adjust+$total_robotic_plus_adjust; 
                                        echo number_format((float)$total_a2, 2, '.', '')  ?>"
                                        readonly>
                                </td>
                            </tr>

                        </table>
                        <table class="uk-table uk-table-small">
                            <tr>
                                <td class="uk-width-6-10" style="font-size:18px; font-weight:bold; border:none;">B:
                                    Readers
                                </td>
                                <td style="border:none;text-align: center;" class="uk-width-1-10">
                                    <span>Prescriber<br>student</span>
                                </td>

                                <td style="border:none;text-align: center;" class="uk-width-1-10">
                                    <span>Prescriber<br>Rate per</span>
                                </td>
                                <td style="border:none;text-align: center;" class="uk-width-1-10">
                                    <span>Total(3)</span>
                                </td>
                            </tr>

                            <tr class="">
                                <td style=" margin-top:50px;border:none; font-size:18px;"
                                    class="uk-width-6-10 uk-text-bold">(i) Link & Think Series:</td>
                            </tr>
                            <tr class="">
                                <td style=" margin-top:20px;border:none; font-size:16px;"
                                    class="uk-width-6-10 uk-text-bold">EDP:</td>
                            </tr>
                            <?php
                                $sql="SELECT programme_package as fees_structure, active_student, fee_rate as link_adjust, amount from `declaration_child` where master_id=$master_id and form='Form2' and fee_structure_mame='Link & Think Series' and subject='EDP'";

                                $result=mysqli_query($connection, $sql);
                                $num_row = mysqli_num_rows($result);
                                $total_EDP_link_student2 = 0;
                                $total_EDP_link_adjust2=0;
							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                            <tr class="">
                                <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">
                                    <?php echo $row['fees_structure']?><span class="text-danger"></span>:</td>
                                
                                <td style="border:none; text-align:center; white-space:nowrap;" class="uk-width-1-10">
                                    <?php 
                                        $level_count = $row['active_student'];
                                        $total_EDP_link_adjust += $row['amount'];
                                        $total_EDP_link_student2 += $level_count;
                                    ?>
                                    <input class="edp_tsd edp_tsdt" type="number" step="0.01" name="active_student[]"
                                        id="active_student" value="<?php echo $level_count ?>" readonly> <span
                                        class="edp_eq">✕ </span>
                                </td>
                                <td style="border:none; white-space:nowrap" class="uk-width-1-10">
                                    <input class="edp_tsd edp_tsdt" type="number" name="fee_rate[]" id="fee_rate"
                                        value="<?php echo number_format($row['link_adjust'], 2, '.', ''); ?>" readonly> <span class="edp_eq2">=
                                    </span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust" type="number" step="0.01" name="amount[]" id="amount"
                                        value="<?php echo number_format((float)$level_count * $row['link_adjust'], 2, '.', '');  ?>" readonly><br>
                                </td>
                            </tr>
                            <?php } } ?>
                            <tr class="">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total Student</td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo $total_EDP_link_student2;  ?>" readonly>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total EDP" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo number_format((float)$total_EDP_link_adjust, 2, '.', '');  ?>" readonly>
                                </td>
                            </tr>
                            <tr class="">
                                <td style=" margin-top:20px;border:none; font-size:16px;"
                                    class="uk-width-6-10 uk-text-bold">QF1:</td>
                            </tr>
                            <?php
                                $sql="SELECT programme_package as fees_structure, active_student, fee_rate as link_adjust, amount from `declaration_child` where master_id=$master_id and form='Form2' and fee_structure_mame='Link & Think Series' and subject='QF1'";
							
							$result=mysqli_query($connection, $sql);
							$num_row = mysqli_num_rows($result);
                            $total_QF1_link_student2 = 0;
							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                            <tr class="">
                                <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">
                                    <?php echo $row['fees_structure']?><span class="text-danger"></span>:</td>
                                
                                <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                    <?php 
                                        $level_count = $row['active_student'];
                                        $total_QF1_link_adjust += $row['amount'];
                                        $total_QF1_link_student2 += $level_count;
                                ?>
                                    <input class="edp_tsd edp_tsdt" type="number" step="0.01" name="active_student[]"
                                        id="active_student" value="<?php echo $level_count ?>" readonly> <span
                                        class="edp_eq">✕ </span>
                                </td>
                                <td style="border:none; white-space:nowrap" class="uk-width-1-10">
                                    <input class="edp_tsd edp_tsdt" type="number" name="fee_rate[]" id="fee_rate"
                                        value="<?php echo number_format($row['link_adjust'], 2, '.', ''); ?>" readonly> <span class="edp_eq2">=
                                    </span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust" type="number" step="0.01" name="amount[]" id="amount"
                                        value="<?php echo number_format((float)$level_count * $row['link_adjust'], 2, '.', '');  ?>" readonly><br>
                                </td>
                            </tr>
                            <?php } } ?>
                            <tr class="">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total Student</td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo $total_QF1_link_student2;  ?>" readonly>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total QF1" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo number_format((float)$total_QF1_link_adjust, 2, '.', '');  ?>" readonly>
                                </td>
                            </tr>
                            <tr class="">
                                <td style=" margin-top:20px;border:none; font-size:16px;"
                                    class="uk-width-6-10 uk-text-bold">QF2:</td>
                            </tr>
                            <?php
                            $sql="SELECT programme_package as fees_structure, active_student, fee_rate as link_adjust, amount from `declaration_child` where master_id=$master_id and form='Form2' and fee_structure_mame='Link & Think Series' and subject='QF2'";
							
							$result=mysqli_query($connection, $sql);
							$num_row = mysqli_num_rows($result);
                            $total_QF2_link_student2 = 0;
							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                            <tr class="">
                                <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">
                                    <?php echo $row['fees_structure']?><span class="text-danger"></span>:</td>
                                
                                <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                    <?php 
                                    $level_count = $row['active_student'];
                                    $total_QF2_link_adjust += $row['amount'];
                                    $total_QF2_link_student2 += $level_count;
                                ?>
                                    <input class="edp_tsd edp_tsdt" type="number" step="0.01" name="active_student[]"
                                        id="active_student" value="<?php echo $level_count ?>" readonly> <span
                                        class="edp_eq">✕ </span>
                                </td>
                                <td style="border:none; white-space:nowrap" class="uk-width-1-10">
                                    <input class="edp_tsd edp_tsdt" type="number" name="fee_rate[]" id="fee_rate"
                                        value="<?php echo number_format($row['link_adjust'], 2, '.', ''); ?>" readonly> <span class="edp_eq2">=
                                    </span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust" type="number" step="0.01" name="amount[]" id="amount"
                                        value="<?php echo number_format((float)$level_count * $row['link_adjust'], 2, '.', '');  ?>" readonly><br>
                                </td>
                            </tr>
                            <?php } } ?>
                            <tr class="">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total Student</td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo $total_QF2_link_student2;  ?>" readonly>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total QF2" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo number_format((float)$total_QF2_link_adjust, 2, '.', '') ;  ?>" readonly>
                                </td>
                            </tr>
                            <tr class="">
                                <td style=" margin-top:20px;border:none; font-size:16px;"
                                    class="uk-width-6-10 uk-text-bold">QF3:</td>
                            </tr>
                            <?php
                        $total_QF3_link_adjust = 0;
                            $sql="SELECT programme_package as fees_structure, active_student, fee_rate as link_adjust, amount from `declaration_child` where master_id=$master_id and form='Form2' and fee_structure_mame='Link & Think Series' and subject='QF3'";
							
							$result=mysqli_query($connection, $sql);
							$num_row = mysqli_num_rows($result);
                            $total_QF3_link_student2 = 0;
							if ($num_row>0) {
								while ($row=mysqli_fetch_assoc($result)) {
						  ?>
                            <tr class="">
                                <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">
                                    <?php echo $row['fees_structure']?><span class="text-danger"></span>:</td>
                                
                                <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                    <?php 
                                        $level_count = $row['active_student'];
                                        $total_QF3_link_adjust += $row['amount'];
                                        $total_QF3_link_student2 += $level_count;
                                ?>
                                    <input class="edp_tsd edp_tsdt" type="number" step="0.01" name="active_student[]"
                                        id="active_student" value="<?php echo $level_count ?>" readonly> <span
                                        class="edp_eq">✕ </span>
                                </td>
                                <td style="border:none; white-space:nowrap" class="uk-width-1-10">
                                    <input class="edp_tsd edp_tsdt" type="number" name="fee_rate[]" id="fee_rate"
                                        value="<?php echo number_format($row['link_adjust'], 2, '.', ''); ?>" readonly> <span class="edp_eq2">=
                                    </span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust" type="number" step="0.01" name="amount[]" id="amount"
                                        value="<?php echo number_format((float)$level_count * $row['link_adjust'], 2, '.', '');  ?>" readonly><br>
                                </td>
                            </tr>
                            <?php } } ?>
                            <tr class="">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total Student</td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo $total_QF3_link_student2;  ?>" readonly>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total QF3" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f" value="<?php echo number_format((float)$total_QF3_link_adjust, 2, '.', '');  ?>" readonly>
                                </td>
                            </tr>

                            <tr class="">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10">Total Student (i)</td>
                                <td class="uk-width-1-10">
                                    <input class="total_s" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f"
                                        value="<?php echo $total_link_student2 = $total_EDP_link_student2 + $total_QF1_link_student2 + $total_QF2_link_student2 + $total_QF3_link_student2; $total_student_A2 += $total_link_student2; ?>"
                                        readonly>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_a" id="total_a"
                                        placeholder="Total (i)" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_a" type="number" step="0.01" name="school_total_f"
                                        id="school_total_f"
                                        value="<?php  $total_link_adjust = $total_EDP_link_adjust + $total_QF1_link_adjust  + $total_QF2_link_adjust + $total_QF3_link_adjust; 
                                        echo number_format((float)$total_link_adjust, 2, '.', '') ?>"
                                        readonly>
                                </td>
                            </tr>
                            <tr class="">
                                <td style="margin-top:50px; padding-top: 11px; text-align: right;"
                                    class="uk-width-1-10"></td>
                                <td style="margin-top:50px;border:none;" class="uk-width-1-10"> <input class="total_s"
                                        type="number" style="display:none;" step="0.01" name="school_total_f" id="school_total_f"
                                        value="<?php echo $total_link_student2; ?>" readonly></td>
                                <td style="text-align:center;border:none;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_usage" id="total_usage"
                                        placeholder="Total B" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;border:none;" class="uk-width-1-10">

                                    <input class="total_artt" type="number" step="0.01" name="total_usaget"
                                        id="total_usaget" value="<?php  $total_b2 = $total_link_adjust; 
                                        echo number_format((float)$total_b2, 2, '.', '') ?>"
                                        readonly>
                                </td>
                            </tr>
                            <tr class="">
                                <td colspan="5">Note: as per Student Fee List for each Academic Year as prescribed by
                                    the
                                    Franchisor<br>*as stated in the Operations e-Manual</td>

                            </tr>
                            <tr class="">
                                <td style=" margin-top:50px;border:none; font-size:18px;"
                                    class="uk-width-6-10 uk-text-bold">C: Products</td>
                            </tr>
                            <tr class="">
                                <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">(i)
                                    Foundation e-Reader (*Optional for Home Usage)<span class="text-danger"></span>:
                                </td>
                                
                                <td style=" text-align:center;border:none;white-space:nowrap;" class="uk-width-1-10">
                                    <?php
                                        $sql="SELECT programme_package as fees_structure, active_student, fee_rate, amount
                                        from `declaration_child` where master_id=$master_id and form='Form2' and fee_structure_mame='Foundation e-Reader'";
                                        $result=mysqli_query($connection, $sql);
                                        $num_row = mysqli_num_rows($result);
                                        if ($num_row>0) {
                                            while ($row=mysqli_fetch_assoc($result)) {
                                                $level_count = $row['active_student'];
                                                $fees = $row["fee_rate"]; 
                                                $total_foundation_e_reader =  $row['amount'];
                                            }
                                        }
                                ?>



                                    <input class="edp_tsd pre_school" type="number" step="0.01" name="active_student[]"
                                        id="active_student" value="<?php echo $level_count ?>" readonly> <span
                                        class="edp_eq">✕ </span>
                                </td>
                                <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd pre_school" type="number" name="fee_rate[]" id="fee_rate"
                                        value="<?php echo number_format((float)$fees, 2, '.', '') ?>" readonly> <span class="edp_eq2">= </span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust2" type="number" step="0.01" name="amount[]" id="amount"
                                        value="<?php echo number_format((float)$total_foundation_e_reader, 2, '.', ''); ?>" readonly><br>
                                </td>
                            </tr>
                            <tr class="">
                                <td style="margin-top:50px;" class="uk-width-1-10 uk-text-bold"></td>
                                <td style="margin-top:50px;" class="uk-width-1-10"></td>
                                <td style="text-align:center;" class="uk-width-1-10">
                                    <input class="edp_tsd" type="number" step="0.01" name="total_usage" id="total_usage"
                                        placeholder="Total C" readonly> <span style="font-size:14px;"
                                        class="edp_eq">RM</span>
                                </td>
                                <td style="text-align:center;" class="uk-width-1-10">

                                    <input class="total_foun_school" type="number" step="0.01" name="total_foun_school"
                                        id="total_foun_school"
                                        value="<?php  $total_c2 = $total_foundation_e_reader; 
                                        echo number_format((float)$total_c2, 2, '.', ''); ?>" readonly>
                                </td>
                            </tr>
                        </table>
                        <table class="uk-table uk-table-small">
                            <tr>
                                <td class="uk-width-6-10" style="font-size:18px; font-weight:bold; border:none;">(1)
                                    ROYALTY
                                    FEE PAYABLE</td>
                                <td style="border:none;text-align: center;" class="uk-width-1-10">
                                    <span>Percentage</span>
                                </td>

                                <td style="border:none;text-align: center;" class="uk-width-1-10">
                                    <span>Gross Turnover</span>
                                </td>
                                <td style="border:none;text-align: center;" class="uk-width-1-10">
                                    <span>Total(1)</span>
                                </td>
                            </tr>
                            <tr class="">
                                <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">Royalty Fee
                                    (5% on Gross Tumover of A(i), A(ii), B(i))<span class="text-danger"></span>:</td>
                                <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                    <input style="position:relative;" class="edp_tsd royaltyi" type="number" step="0.01"
                                        name="royalty_si" id="royalty_si" value="5" readonly><span
                                        style="margin-left: -30px;font-size: 20px;position: absolute;">%</span> <span
                                        style="" class="edp_eq">✕ </span>
                                </td>
                                <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd royaltyi" type="number" name="royalty_sti" id="royalty_sti"
                                        value="<?php echo number_format((float)$total_enhanced_adjust + $total_international_adjust + $total_link_adjust, 2, '.', ''); ?>"
                                        readonly> <span class="edp_eq2">= </span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="royalty_tii_r" type="number" step="0.01" name="royalty_ti"
                                        id="royalty_ti"
                                        value="<?php  $royalty_fee1 = (($total_enhanced_adjust + $total_international_adjust + $total_link_adjust)/100)*5; $total_royalty_fee2 +=$royalty_fee1; 
                                        echo number_format((float)$royalty_fee1, 2, '.', ''); ?>"
                                        readonly><br>
                                </td>
                            </tr>
                            <tr class="">
                                <td style=" margin-top:50px;border:none;" class="uk-width-6-10 uk-text-bold">Royalty Fee
                                    (5%
                                    on Gross Tumover of A(iii), A(iv), C(i))<span class="text-danger"></span>:</td>
                                <td style="border:none; text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                    <input style="position:relative;" class="edp_tsd royaltyii" type="number"
                                        step="0.01" name="royalty_sii" id="royalty_sii" value="5" readonly> <span
                                        style="margin-left: -30px;font-size: 20px;position: absolute;">%</span> <span
                                        style="" class="edp_eq">✕ </span>
                                </td>
                                <td style="border:none;white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd royaltyii" type="number" name="royalty_stii" id="royalty_stii"
                                        value="<?php echo number_format((float)$total_mandarin_adjust + $total_iq_math_adjust + $total_c2, 2, '.', ''); ?>"
                                        readonly> <span class="edp_eq2">= </span>
                                </td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="royalty_tii_r" type="number" step="0.01" name="royalty_tii"
                                        id="royalty_tii"
                                        value="<?php  $royalty_fee2 = (($total_mandarin_adjust + $total_iq_math_adjust + $total_c2)/100)*5; $total_royalty_fee2 +=$royalty_fee2; 
                                        echo number_format((float)$royalty_fee2, 2, '.', '') ?>"
                                        readonly><br>
                                </td>
                            </tr>
                        </table>
                        <table class="uk-table uk-table-small">
                            <tr>
                                <td colspan="5" style="font-size:18px; font-weight:bold;"></td>
                            </tr>
                            <tr>
                                <td class="uk-width-6-10" style="font-size:18px; font-weight:bold; border:none;">(2) A&P
                                    FEE
                                    PAYABLE</td>
                                <td style="border:none;text-align: center;" class="uk-width-1-10">
                                    <span>Percentage</span>
                                </td>

                                <td style="border:none;text-align: center;" class="uk-width-1-10">
                                    <span>Total A</span>
                                </td>
                                <td style="border:none;text-align: center;" class="uk-width-1-10">
                                    <span>Total(2)</span>
                                </td>
                            </tr>
                            <tr class="">
                                <td style=" margin-top:50px;" class="uk-width-6-10 uk-text-bold">Advertising & Promotion
                                    Fee(3% on A. only)<span class="text-danger"></span>:</td>
                                <td style=" text-align:center;white-space:nowrap;" class="uk-width-1-10">
                                    <input style="position:relative;" class="edp_tsd advertisingi" type="number"
                                        name="advertising_si" id="advertising_si" value="3" readonly> <span
                                        style="margin-left: -30px;font-size: 20px;position: absolute;">%</span> <span
                                        style="" class="edp_eq">✕ </span>
                                </td>
                                <td style="white-space:nowrap;" class="uk-width-1-10">
                                    <input class="edp_tsd advertisingi" type="number" name="advertising_sti"
                                        id="advertising_sti" value="<?php echo number_format((float)$total_a2, 2, '.', ''); ?>" readonly> <span
                                        class="edp_eq2">= </span>
                                </td>
                                <td style=" text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust" type="number" step="0.01" name="advertising_ti"
                                        id="advertising_ti"
                                        value="<?php  $advertising_promotion_fee2 = ($total_a2/100)*3; 
                                        echo number_format((float)$advertising_promotion_fee2, 2, '.', ''); ?>"
                                        readonly><br>
                                </td>
                            </tr>
                        </table>

                        <table class="uk-table uk-table-small">
                            <tr class="">
                                <td style=" margin-top:50px; border:none;" class="uk-width-6-10 uk-text-bold">Subtotal
                                    (Form 1)<span class="text-danger"></span>:</td>
                                <td style="border:none;" class="uk-width-1-10"></td>
                                <td style="border:none;" class="uk-width-1-10"></td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust56" type="number" step="0.01" name="grand_totali"
                                        id="grand_totali"
                                        value="<?php echo $sub_total_1 = sprintf('%0.2f', $total_1_2_3); ?>"
                                        readonly><br>
                                </td>
                            </tr>
                            <tr class="">
                                <td style=" margin-top:50px; border:none;" class="uk-width-6-10 uk-text-bold">Subtotal
                                    (Form 2)<span class="text-danger"></span>:</td>
                                <td style="border:none;" class="uk-width-1-10"></td>
                                <td style="border:none;" class="uk-width-1-10"></td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust56" type="number" step="0.01" name="grand_totali"
                                        id="grand_totali"
                                        value="<?php $sub_total_2 = $total_royalty_fee2 + $advertising_promotion_fee2; echo sprintf('%0.2f', $sub_total_2); ?>"
                                        readonly><br>
                                </td>
                            </tr>
                            <tr class="">
                                <td style=" margin-top:50px; border:none;" class="uk-width-6-10 uk-text-bold">Grand
                                    Total (Form 1 + Form 2)<span class="text-danger"></span>:</td>
                                <td style="border:none;" class="uk-width-1-10"></td>
                                <td style="border:none;" class="uk-width-1-10"></td>
                                <td style="border:none; text-align:center;" class="uk-width-1-10">
                                    <input class="school_adjust56" type="number" step="0.01" name="grand_totali"
                                        id="grand_totali"
                                        value="<?php $grand_total2 = $sub_total_1 + $sub_total_2; echo sprintf('%0.2f', $grand_total2); ?>"
                                        readonly><br>
                                </td>
                            </tr>
                            <tr class="">
                                <td style=" margin-top:50px;" class="uk-width-6-10 uk-text-bold">PAYABLE TO Q-DEES
                                    WORLDWIDE
                                    EDUSYSTEMS (M) SDN BHD (MBB 514196314454)<span class="text-danger"></span>:</td>
                                <td style="" class="uk-width-1-10"></td>
                                <td style="" class="uk-width-1-10"></td>
                                <td style=" text-align:center;" class="uk-width-1-10"></td>
                            </tr>
                        </table>
                        <table class="uk-table uk-table-small">
                            <tr>
                                <td class="uk-width-6-10" style="font-size:18px; font-weight:bold; border:none;">TERMS
                                    AND
                                    CONDITIONS</td>
                            </tr>
                            <tr class="">
                                <td style="border:none;" colspan="5"><i>The Franchisee shall pay to Franchisor Royalty
                                        Fee,
                                        Advertising & Promotion Fee and Software License Fee, according to the terms and
                                        conditions stated in this declaration, in Ringgit Malaysia on a monthly basis,
                                        on
                                        the 1st day but not later than the 5th day of each month. Late payments shall be
                                        levied with an additional 1.5% charge per month. <b>(This Declaration Form shall
                                            constitute as a legally binding contract between the parties and shall be
                                            read
                                            and construed as if the Declaration Form were inserted in the Franchise
                                            Agreement as an integral part of the Franchise Agreement.)</b><i></td>
                            </tr>
                            <tr class="">
                                <td style="border:none; " colspan="5"><i>I <?php echo $operator_name;?>, hereby
                                        acknowledge
                                        and agree to the above terms and I hereby declare the information above to be
                                        accurate, complete and in compliance with the law of Malaysia. I also
                                        acknowledge
                                        that I have taken all reasonable steps to ensure the same.<i></td>
                            </tr>
                            
                            
                            <tr style="display:none1;">
                                <td style="margin-top:50px;border:none;" class="uk-width-5-10 uk-text-bold">Centre's
                                    Remarks:</td>
                                <td style="border:none;" class="uk-width-5-10 uk-text-bold"><textarea
                                        style="font-weight: normal;" id="remarks_centre_2" name="remarks_centre_2"
                                        rows="5" cols="65"><?php echo $row_edit['remarks_centre_2'] ?> </textarea></td>
                            </tr>
                            
                            <tr style="display:none1;">
                                <td style="margin-top:50px;border:none;" class="uk-width-5-10 uk-text-bold">Master's
                                    Remarks:</td>
                                <td style="border:none;" class="uk-width-5-10"><textarea style="font-weight: normal;"
                                        id="remarks_master_2" name="remarks_master_2" rows="5" cols="65"
                                        readonly><?php echo $row_edit['remarks_master_2'] ?> </textarea></td>
                            </tr>
                            <tr>
                                <td style="margin-top:50px;border:none;" class="uk-width-5-10 uk-text-bold">Payment
                                    Status</td>
                                <td style="border:none;" class="uk-width-5-10 uk-text-bold">
                                    <?php  echo $row_edit['form_2_status']; ?>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="uk-width-medium-10-10 uk-text-center">
                    
                </div>
            </div>
        </form>

    </div>
</div>

</body>
</html>