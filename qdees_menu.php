               Admin
               <ul>
<?php
if (($_SESSION["isLogin"]==1) & ($_SESSION["UserType"]=="S")) {
?>
                  <li><?php echo getLink("centre", $_GET["p"], "Centre")?></li>
<?php
}
?>
<?php
if (($_SESSION["isLogin"]==1) & (($_SESSION["UserType"]=="A") || ($_SESSION["UserType"]=="O"))) {
?>
                  <li><?php echo getLink("student", $_GET["p"], "Student")?></li>
<?php
}
?>
<?php
if (($_SESSION["isLogin"]==1) & (($_SESSION["UserType"]=="A") || ($_SESSION["UserType"]=="O"))) {
?>
                  <li><?php echo getLink("course", $_GET["p"], "Course")?></li>
                  <li><?php echo getLink("class", $_GET["p"], "Class")?></li>
                  <li><?php echo getLink("allocation", $_GET["p"], "Allocation")?></li>
                  <li><?php echo getLink("collection", $_GET["p"], "Collection")?></li>
<?php
}
?>
<?php
if (($_SESSION["isLogin"]==1) & (($_SESSION["UserType"]=="A") || ($_SESSION["UserType"]=="O"))) {
?>
                  <li><?php echo getLink("purchasing", $_GET["p"], "Purchasing")?></li>
<?php
}
?>
<?php
if (($_SESSION["isLogin"]==1) & (($_SESSION["UserType"]=="A") || ($_SESSION["UserType"]=="S"))) {
?>
                  <li><?php echo getLink("user", $_GET["p"], "User")?></li>
<?php
}
?>
<!--                   <li><a href="index.php?p=visitor">Visitor</a></li>
                  <li><a href="index.php?p=parent">Parent</a></li>
 -->

<?php
if (($_SESSION["isLogin"]==1) & ($_SESSION["UserType"]=="S")) {
?>
                  <li><?php echo getLink("vendor", $_GET["p"], "Vendor")?></li>
                  <li><?php echo getLink("product", $_GET["p"], "Product")?></li>
<?php
}
?>

<?php
if ($_SESSION["isLogin"]==1) {
?>
                  <li><?php echo getLink("chgpass", $_GET["p"], "Change Password")?></li>
<?php
}
?>
<?php
if ($_SESSION["isLogin"]==1) {
?>
                  <li><a href="logout.php">Logout (<?php echo $_SESSION["UserName"]?>)</a></li>
<?php
} else {
?>
                  <li><a href="index.php?p=login">Login</a></li>
<?php
}
?>
               </ul>
<?php
if (($_SESSION["isLogin"]==1) & ($_SESSION["UserType"]=="A")) {
?>
               Cart
               <ul>
                  <li><a onclick="viewCart();">View Cart</a> <span id="no_in_cart" class="uk-badge uk-badge-danger uk-badge-notification"><?php echo noInCart()?></span></li>
               </ul>
<?php
}
?>
