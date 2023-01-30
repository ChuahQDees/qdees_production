<li class="nav-item nav-category">
   <span class="nav-link">Admin</span>
</li>
<!-- <li class="nav-item">
   <a class="nav-link" href="index.php?p=dashboard">
      <i class="fa fa-home menu-icon"></i>
      <span class="menu-title">Home</span>
   </a>
</li> -->

<?php
if (($_SESSION["UserType"]=="S") & (hasRightGroupXOR($_SESSION["UserName"], "MasterEdit|MasterView"))) {
?>
<li class="nav-item">
   <a class="nav-link" href="index.php?p=master">
      <i class="fa fa-user-circle menu-icon"></i>
      <span class="menu-title">Master</span>
   </a>
</li>
<?php
}
?>
<?php
if (($_SESSION["UserType"]=="S") & (hasRightGroupXOR($_SESSION["UserName"], "CentreEdit|CentreView"))) {
?>
<li class="nav-item">
   <a class="nav-link" href="index.php?p=centre">
      <i class="fa fa-building menu-icon"></i>
      <span class="menu-title">Centre</span>
   </a>
</li>
<?php
}
?>
<?php
if (($_SESSION["UserType"]=="S") & (hasRightGroupXOR($_SESSION["UserName"], "ClassEdit|ClassView"))) {
?>
<li class="nav-item">
   <a class="nav-link" href="index.php?p=course">
      <i class="fa fa-university menu-icon"></i>
      <span class="menu-title">Class (BOM)</span>
   </a>
</li>
<?php
}
?>
<?php
if (($_SESSION["UserType"]=="S") & (hasRightGroupXOR($_SESSION["UserName"], "ProductEdit|ProductView"))) {
?>
<li class="nav-item">
   <a class="nav-link" href="index.php?p=product">
      <i class="fa fa-book menu-icon"></i>
      <span class="menu-title">Product (SKU)</span>
   </a>
</li>
<?php
}
?>
<?php
if (($_SESSION["UserType"]=="S") & (hasRightGroupXOR($_SESSION["UserName"], "UserEdit|UserView"))) {
?>
<li class="nav-item">
   <a class="nav-link" href="index.php?p=user">
      <i class="fa fa-user menu-icon"></i>
      <span class="menu-title">User Access Control (UAC)</span>
   </a>
</li>
<?php
}
?>
<!-- <?php
if (($_SESSION["UserType"]=="S") & (hasRightGroupXOR($_SESSION["UserName"], "UserRightsEdit|UserRightsView"))) {
?>
<li class="nav-item">
   <a class="nav-link" href="index.php?p=userright">
      <i class="fa fa-check-square-o menu-icon"></i>
      <span class="menu-title">User Right</span>
   </a>
</li>
<?php
}
?> -->
<?php
if (($_SESSION["UserType"]=="S") & (hasRightGroupXOR($_SESSION["UserName"], "OrderStatusEdit|OrderStatusView|DefectiveStatusView|DefectiveStatusEdit"))) {
?>
<li class="nav-item nav-category">
   <span class="nav-link">Order</span>
</li>
<li class="nav-item">
   <a class="nav-link" href="index.php?p=order_status_pg1">
      <i class="fa fa-star menu-icon"></i>
      <span class="menu-title">Order Status</span>
   </a>
</li>
<?php
if (($_SESSION["UserType"]=="S") & (hasRightGroupXOR($_SESSION["UserName"], "DefectiveStatusView|DefectiveStatusEdit"))) {
?>
<li class="nav-item">
   <a class="nav-link" href="index.php?p=defective_status_pg1">
      <i class="fa fa-certificate menu-icon"></i>
      <span class="menu-title">Defective Status</span>
   </a>
</li>
<?php
}
?>
<?php
if (($_SESSION["UserType"]=="S") & (hasRightGroupXOR($_SESSION["UserName"], "KIVView|KIVEdit"))) {
?>
<li class="nav-item">
   <a class="nav-link" href="index.php?p=kiv">
      <i class="fa fa-free-code-camp menu-icon"></i>
      <span class="menu-title">KIV</span>
   </a>
</li>
<?php
}
?>
<?php
}
?>
<li class="nav-item nav-category">
   <span class="nav-link">Settings</span>
</li>
<?php
if (($_SESSION["UserType"]=="S") &
   (hasRightGroupXOR($_SESSION["UserName"], "StockAdjustmentEdit"))) {
?>
<li class="nav-item">
   <a class="nav-link" href="index.php?p=stkadj">
      <i class="fa fa-adjust menu-icon"></i>
      <span class="menu-title">Stock Adjustment</span>
   </a>
</li>
<?php
}
?>
<?php
if (($_SESSION["UserType"]=="S") &
   (hasRightGroupXOR($_SESSION["UserName"], "ReportingView"))) {
?>
<li class="nav-item">
   <a class="nav-link" href="index.php?p=rpt_visitor_registration" data-toggle="tooltip" data-placement="right" title="Visitor Registration Report">
      <i class="fa fa-bar-chart-o menu-icon"></i>
      <span class="menu-title">Visitor Registration Report</span>
   </a>
</li>
<li class="nav-item">
   <a class="nav-link" href="index.php?p=rpt_student_dropout" data-toggle="tooltip" data-placement="right" title="Student Dropout Report">
      <i class="fa fa-bar-chart-o menu-icon"></i>
      <span class="menu-title">Student Dropout Report</span>
   </a>
</li>
<?php
}
?>
<?php
if (($_SESSION["UserType"]=="S") & (hasRightGroupXOR($_SESSION["UserName"], "MasterEdit|MasterView")))
{
?>

<li class="nav-item nav-category">
   <span class="nav-link">Reports</span>
</li>

<li class="nav-item">
   <a class="nav-link" href="index.php?p=rpt_centre_termly_stock" data-toggle="tooltip" data-placement="right" title="Centre Termly Stock Report">
      <i class="fa fa-bar-chart-o menu-icon"></i>
      <span class="menu-title">Centre Termly Stock Report</span>
   </a>
</li>

<li class="nav-item">
   <a class="nav-link" href="index.php?p=rpt_centre_subject_enrl" data-toggle="tooltip" data-placement="right" title="Centre Subject Enrollement Report">
      <i class="fa fa-bar-chart-o menu-icon"></i>
      <span class="menu-title">Centre Subject Enrollement Report</span>
   </a>
</li>

<li class="nav-item">
   <a class="nav-link" href="index.php?p=rpt_sales" data-toggle="tooltip" data-placement="right" title="Summary Sales Report">
      <i class="fa fa-bar-chart-o menu-icon"></i>
      <span class="menu-title">Summary Sales Report</span>
   </a>
</li>
<li class="nav-item">
   <a class="nav-link" href="index.php?p=rpt_detail_sales" data-toggle="tooltip" data-placement="right" title="Detail Sales Report">
      <i class="fa fa-bar-chart-o menu-icon"></i>
      <span class="menu-title">Detail Sales Report</span>
   </a>
</li>

<li class="nav-item">
   <a class="nav-link" href="index.php?p=rpt_void" data-toggle="tooltip" data-placement="right" title="Credit Note Listing Report">
      <i class="fa fa-bar-chart-o menu-icon"></i>
      <span class="menu-title">Credit Note Listing Report</span>
   </a>
</li>

<li class="nav-item">
   <a class="nav-link" href="index.php?p=rpt_master">
      <i class="fa fa-bar-chart-o menu-icon"></i>
      <span class="menu-title">Master Report</span>
   </a>
</li>

<li class="nav-item">
   <a class="nav-link" href="index.php?p=rpt_franchise">
      <i class="fa fa-bar-chart-o menu-icon"></i>
      <span class="menu-title">Franchise Report</span>
   </a>
</li>

<li class="nav-item">
   <a class="nav-link" href="index.php?p=rpt_accumulative_fee" data-toggle="tooltip" data-placement="right" title="Accumulative Fee Report">
      <i class="fa fa-bar-chart-o menu-icon"></i>
      <span class="menu-title">Accumulative Fee Report</span>
   </a>
</li>

<li class="nav-item">
   <a class="nav-link" href="index.php?p=rpt_outstanding" data-toggle="tooltip" data-placement="right" title="Outstanding Fee Report">
      <i class="fa fa-bar-chart-o menu-icon"></i>
      <span class="menu-title">Outstanding Fee Report</span>
   </a>
</li>

<?php
}
?>

<li class="nav-item">
   <a class="nav-link" href="logout.php">
      <i class="mdi mdi-logout menu-icon"></i>
      <span class="menu-title">Logout</span>
   </a>
</li>