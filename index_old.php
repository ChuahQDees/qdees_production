<?php
session_start();
include_once("mysql.php");

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
         UIkit.notification("Error:"+error);
      }
   });
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
         $("#dlgViewCart").html(response);
         $("#dlgViewCart").dialog({
            width:900,
            height:600,
            modal:true,
            position: {my: "center top", at: "center top", of: $("#theBody")}
         });
      },
      error : function(http, status, error) {
         UIkit.notification("Error:"+error);
      }
   });
}
</script>
<style>
body {
   background: url(images/bg.png) center center;
   height:1500;
   -webkit-background-size: cover;
   -moz-background-size: cover;
   -o-background-size: cover;
   background-size: cover;
   background-attachment:fixed;
}
</style>

<html>
<head>
   <link rel="stylesheet" type="text/css" href="css/my.css">
   <?php include_once("uikit.php");?>
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <title>Qdees</title>
</head>
<body id="theBody">
   <div class="uk-grid">
<?php
if ($_SESSION["isLogin"]==1) {
?>
      <div class="uk-width-2-10">
         <div class="sidenav" style="background:transparent">
            <div class="uk-margin-left">
               <img src="images/logo.png"><br><br>
<?php
if ($_SESSION["isLogin"]==1) {
if (($_SESSION["UserType"]=="A") || ($_SESSION["UserType"]=="O")) {
?>
               <div class="uk-text-center uk-text-bold"><?php echo "Centre Name : ".getCentreNameIndex($_SESSION["CentreCode"])?></div><br>
<?php
} else {
?>
               <div class="uk-text-center uk-text-bold"><?php echo "Centre Name : Franchisor"?></div><br>
<?php
}
}
?>
<?php
if ($_SESSION["isLogin"]==1) {
?>
               <div class="uk-button-dropdown" data-uk-dropdown>
                  <button class="uk-button"><?php echo $_SESSION["Year"]?></button>
                  <div class="uk-dropdown">
                     <ul class="uk-nav uk-nav-dropdown">
<?php
$current_year=date("Y");
$back_year=date("Y", strtotime("-5 year"));
$forward_year=date("Y", strtotime("+2 year"));
for ($i=$back_year; $i<=$forward_year; $i++) {
?>
                        <li><a href="javascript:doYear('<?php echo $i?>')"><?php echo $i?></a></li>
<?php
}
?>
                     </ul>
                  </div>
               </div>
<?php
}
?>
<?php
if (($_SESSION["isLogin"]==1) & ($_SESSION["UserType"]=="S")) {
?>
               <div class="uk-button-dropdown" data-uk-dropdown>
<?php
if (($_GET["p"]=="") & ($_GET["master"]==1)) {
?>
                  <button class="uk-button">Masters</button>
<?php
} else {
   if ($_GET["master"]==1) {
?>
                  <button class="uk-button">Masters - <?php echo ucwords($_GET["p"])?></button>
<?php
   } else {
?>
                  <button class="uk-button">Masters</button>
<?php
   }
}
?>
                  <div class="uk-dropdown">
                     <ul class="uk-nav uk-nav-dropdown">
                        <li><a href="index.php?p=mastertype&master=1">Master Type</a></li>
                        <li><a href="index.php?p=region&master=1">Region</a></li>
                        <li><a href="index.php?p=country&master=1">Country</a></li>
                        <li><a href="index.php?p=state&master=1">State</a></li>
                        <li><a href="index.php?p=race&master=1">Race</a></li>
                        <li><a href="index.php?p=occupation&master=1">Occupation</a></li>
                        <li><a href="index.php?p=education&master=1">Education</a></li>
                        <li><a href="index.php?p=religion&master=1">Religion</a></li>
                        <li><a href="index.php?p=nationality&master=1">Nationality</a></li>
                        <li><a href="index.php?p=category&master=1">Category</a></li>
                        <li><a href="index.php?p=courier&master=1">Courier</a></li>
                     </ul>
                  </div>
               </div>
<?php
}
?>
               <br><br>
<?php
if (($_SESSION["isLogin"]==1) & (($_SESSION["UserType"]=="A") || ($_SESSION["UserType"]=="O"))) {
   include_once("mnu_franchisee.php");
}

if (($_SESSION["isLogin"]==1) & ($_SESSION["UserType"]=="S")) {
   include_once("mnu_HQ.php");
}
?>
            </div>
         </div>
      </div>
<?php
}
?>
<?php
if ($_SESSION["isLogin"]==1) {
?>
      <div class="uk-width-8-10">
<?php
} else {
?>
      <div class="uk-width-10-10">
<?php
}
?>
<?php
$p=$_GET["p"];
if (isset($_GET["p"])) {
   $p="login";
}
switch ($_GET["p"]) {
   case "master" : include_once("admin/master.php"); break;
   case "centre" : include_once("admin/centre.php"); break;
   case "student" : include_once("admin/student.php"); break;
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
   case "stkadj" : include_once("admin/stock_adjustment.php"); break;
   case "order_status_pg1" : include_once("admin/order_status_pg1.php"); break;
   case "order_status" : include_once("admin/order_status.php"); break;
   case "user" : include_once("admin/user.php"); break;
   case "userright" : include_once("admin/user_right.php"); break;
   case "vendor" : include_once("admin/vendor.php"); break;
   case "product" : include_once("admin/product.php"); break;
   case "visitor" : include_once("admin/visitor.php"); break;
   case "parent" : include_once("admin/parent.php"); break;
   case "visitor_qr_list" : include_once("admin/visitor_qr_list.php"); break;
   case "student_qr_list" : include_once("admin/student_qr_list.php"); break;
   case "exp_contact" : include_once("admin/export_contact.php"); break;
   case "chgpass" : include_once("admin/change_password.php"); break;
   case "login" : header("Location: index.php"); break;

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
   case "" : include_once("home.php"); break;
}
?>
      </div>
   </div>
<div id="dlgViewCart" title="Cart Content"></div>

<?php
if ($_REQUEST["msg"]!="") {
   echo "<script>UIkit.notify('".$_REQUEST["msg"]."')</script>";
}
?>
</body>
</html>