<style>
.sidebar p,
.sidebar h3 {
    color: #000 !important;

}

li.active {
    background: #3399ff;
    color: #000 !important;
}

.sidebar .nav .nav-item.active>.nav-link {
    color: #000 !important;
}

.sidebar .nav:not(.sub-menu)>.nav-item:hover:not(.nav-category):not(.nav-profile)>.nav-link {
    background: rgb(51 153 255 / 58%);
    color: #ffffff;
}

.sidebar .nav .nav-item .nav-link .menu-title {
    color: #000 !important;
}
</style>
<script>
$(document).ready(function() {
    var selectedClass = "<?php echo $_GET["p"] ?>";
    $("." + selectedClass).addClass("active");

})
</script>
<div>

    <li class="nav-item home d-flex justify-content-center home">

        <a class="nav-link " href="/">

            <span class="menu-icon"><img src="/images/menu_icons/Visitor Reg.png"></span>

            <span class="menu-title">Home</span>

        </a>

    </li>

</div>

<br>

<li class="nav-item nav-category" style="margin-bottom:10px;">

    <span class="nav-link">Admin</span>

</li>

<!-- <li class="nav-item">

   <a class="nav-link" href="index.php?p=dashboard">

      <i class="fa fa-home menu-icon"></i>

      <span class="menu-title">Home</span>

   </a>

</li> -->

<?php

if ( (hasRightGroupXOR($_SESSION["UserName"], "MasterEdit|MasterView"))) {

?>

<li class="nav-item master">

    <a class="nav-link" href="index.php?p=master">

        <span class="menu-icon"><img src="/images/menu_icons/Stud Reg.png"></span>

        <span class="menu-title">Master</span>

    </a>

</li>

<?php

}

?>

<?php

if ( (hasRightGroupXOR($_SESSION["UserName"], "CentreEdit|CentreView"))) {

?>

<li class="nav-item centre">

    <a class="nav-link" href="index.php?p=centre">

        <span class="menu-icon"><img src="/images/menu_icons/Stud Reg.png"></span>

        <span class="menu-title">Centre</span>

    </a>

</li>

<?php

}

?>

<?php

if ( (hasRightGroupXOR($_SESSION["UserName"], "FeeEdit|FeeView"))) {

?>

<li class="nav-item fee">

    <a class="nav-link" href="index.php?p=fee">

        <span class="menu-icon"><img src="/images/menu_icons/Payment.png"></span>

        <span class="menu-title">Fees Setting</span>

    </a>

</li>

<?php

}

?>

<?php

//if ( (hasRightGroupXOR($_SESSION["UserName"], "ClassEdit|ClassView"))) {

?>

<!-- <li class="nav-item course">

      <a class="nav-link" href="index.php?p=course">

         <span class="menu-icon"><img src="/images/menu_icons/Classroom Allocation.png"></span>

         <span class="menu-title">Class (BOM)</span>

      </a>

   </li>-->

<?php

//}

?>

<?php

if ( (hasRightGroupXOR($_SESSION["UserName"], "ProductEdit|ProductView"))) {

?>
<!--<li class="nav-item">
		<a class="nav-link" href="index.php?p=product_category"> 
			<span class="menu-icon"><img src="/images/menu_icons/Order.png"></span> 
			<span class="menu-title">Product Category</span>
		</a>
	</li>-->
<li class="nav-item product">

    <a class="nav-link" href="index.php?p=product">

        <span class="menu-icon"><img src="/images/menu_icons/Order.png"></span>

        <span class="menu-title">Product (SKU)</span>

    </a>

</li>

<?php

}

?>

<?php

if ( (hasRightGroupXOR($_SESSION["UserName"], "UserEdit|UserView"))) {

?>

<li class="nav-item user">

    <a class="nav-link" href="index.php?p=user">

        <span class="menu-icon"><img src="/images/menu_icons/UAC.png"></span>

        <span class="menu-title">User Access<br>Control (UAC)</span>

    </a>

</li>

<?php

}

?>

<!-- <?php

      if ( (hasRightGroupXOR($_SESSION["UserName"], "UserRightsEdit|UserRightsView"))) {

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

if ( (hasRightGroupXOR($_SESSION["UserName"], "OrderStatusEdit|OrderStatusView|DefectiveStatusView|DefectiveStatusEdit"))) {

?>

<li class="nav-item nav-category" style="margin-top:10px;margin-bottom:10px;">

    <span class="nav-link">Order</span>

</li>

<li class="nav-item order_status_pg1">

    <a class="nav-link" href="index.php?p=order_status_pg1">

        <span class="menu-icon"><img src="/images/menu_icons/Current Status Order.png"></span>

        <span class="menu-title">Order Status</span>

    </a>

</li>

<?php

   if ( (hasRightGroupXOR($_SESSION["UserName"], "DefectiveStatusView|DefectiveStatusEdit"))) {

   ?>

<li class="nav-item defective_status_pg1">

    <a class="nav-link" href="index.php?p=defective_status_pg1">

        <span class="menu-icon"><img src="/images/menu_icons/Defective Status.png"></span>

        <span class="menu-title">Defective Status</span>

    </a>

</li>

<?php

   }

   ?>

<?php

   if ( (hasRightGroupXOR($_SESSION["UserName"], "KIVView|KIVEdit"))) {

   ?>

<li class="nav-item kiv">

    <a class="nav-link" href="index.php?p=kiv">

        <span class="menu-icon"><img src="/images/menu_icons/Defective Status.png"></span>

        <span class="menu-title">KIV</span>

    </a>

</li>

<?php

   }

   ?>

<?php

}

?>



<?php

if ( (hasRightGroupXOR($_SESSION["UserName"], "StockBalancesView"))) {

?>

<!-- <li class="nav-item nav-category" style="margin-top:10px;margin-bottom:10px;">

    <span class="nav-link">Inventory</span>

</li> -->

<?php

}

if ( (hasRightGroupXOR($_SESSION["UserName"], "StockBalancesView|OrderStatusView"))) {

?>

<!-- <li class="nav-item stock_bal">

    <a class="nav-link" href="index.php?p=stock_bal">

        <span class="menu-icon"><img src="/images/menu_icons/Centre Stock Balance.png"></span>

        <span class="menu-title">Centre Stock Balance</span>

    </a>

</li> -->

<!-- <li class="nav-item order_status_pg1">

      <a class="nav-link" href="index.php?p=order_status_pg1">

         <span class="menu-icon"><img src="/images/menu_icons/Current Status Order.png"></span>

         <span class="menu-title">Current Order Status</span>

      </a>

   </li> -->

<?php

}

?>

<li class="nav-item nav-category" style="margin-top:10px;margin-bottom:10px;">

    <span class="nav-link">Settings</span>

</li>

<?php

if ( (hasRightGroupXOR($_SESSION["UserName"], "StockAdjustmentEdit"))) {

?>

<li class="nav-item stkadj">

    <a class="nav-link" href="index.php?p=stkadj">

        <span class="menu-icon"><img src="/images/menu_icons/Centre Stock Balance.png"></span>

        <span class="menu-title">Stock Adjustment</span>

    </a>

</li>

<?php

}

?>

<?php

if ( (hasRightGroupXOR($_SESSION["UserName"], "StockAdjustmentEdit"))) {

?>

<li class="nav-item qr_code_list">

    <a class="nav-link" href="index.php?p=qr_code_list">

        <span class="menu-icon"><img src="/images/menu_icons/Centre Stock Balance.png"></span>

        <span class="menu-title">Student Card Listing</span>

    </a>

</li>

<?php

}

?>

<?php

if ( (hasRightGroupXOR($_SESSION["UserName"], "CentreStatementAccountEdit"))) {

?>

<li class="nav-item centre_statement_account">

    <a class="nav-link" href="index.php?p=centre_statement_account">

        <span class="menu-icon"><img src="/images/menu_icons/Centre Stock Balance.png"></span>

        <span class="menu-title">Centre Statement<br>Account</span>

    </a>

</li>

<?php

}

?>

<?php

if ( (hasRightGroupXOR($_SESSION["UserName"], "SlotCollectionManagementEdit"))) {

?>

<li class="nav-item slot_collection_management">

    <a class="nav-link" href="index.php?p=slot_collection_management">

        <span class="menu-icon"><img src="/images/menu_icons/Centre Stock Balance.png"></span>

        <span class="menu-title">Slot Collection<br>Management</span>

    </a>

</li>

<?php

}

?>
<li class="nav-item schedule_term">

    <a class="nav-link" href="index.php?p=schedule_term">

        <span class="menu-icon"><img src="/images/menu_icons/Centre Stock Balance.png"></span>

        <span class="menu-title">Schedule Term</span>

    </a>

</li>
<?php

//if ( (hasRightGroupXOR($_SESSION["UserName"], "BufferStockEdit"))) {

?>
<!-- 
<li class="nav-item buffer_stock">

    <a class="nav-link" href="index.php?p=buffer_stock">

        <span class="menu-icon"><img src="/images/menu_icons/Centre Stock Balance.png"></span>

        <span class="menu-title">Buffer Stock</span>

    </a>

</li> -->

<?php

//}

?>





<?php

if ( (hasRightGroupXOR($_SESSION["UserName"], "ReportingView"))) {  

?>




<li class="nav-item nav-category" style="margin-top:10px;margin-bottom:10px;">

    <span class="nav-link">Reports</span>

</li>

<li class="nav-item rpt_summary_students_population">

    <a class="nav-link" href="index.php?p=rpt_summary_students_population" data-toggle="tooltip" data-placement="right">

        <span class="menu-icon"><img src="/images/menu_icons/Report.png"></span>

        <span class="menu-title">Summary Students<br>Population Report</span>

    </a>

</li>

<li class="nav-item rpt_student_population">

    <a class="nav-link" href="index.php?p=rpt_student_population" data-toggle="tooltip" data-placement="right">

        <span class="menu-icon"><img src="/images/menu_icons/Report.png"></span>

        <span class="menu-title">Student Population <br>Report</span>

    </a>

</li>
<li class="nav-item rptFeeSettings">
    <a class="nav-link" href="index.php?p=rptFeeSettings">
        <span class="menu-icon"><img src="/images/menu_icons/Report.png"></span>
        <span class="menu-title">Fee Settings <br>Report</span>
    </a>
</li>

<li class="nav-item rpt_visitor_registration">

    <a class="nav-link" href="index.php?p=rpt_visitor_registration" data-toggle="tooltip" data-placement="right">

        <span class="menu-icon"><img src="/images/menu_icons/Report.png"></span>

        <span class="menu-title">Visitor Registration<br>Report</span>

    </a>

</li>

<li class="nav-item rpt_class_listing">

    <a class="nav-link" href="index.php?p=rpt_class_listing" data-toggle="tooltip" data-placement="right">

        <span class="menu-icon"><img src="/images/menu_icons/Report.png"></span>

        <span class="menu-title">Class Listing <br>Report</span>

    </a>

</li>

<li class="nav-item rpt_student_dropout">

    <a class="nav-link" href="index.php?p=rpt_student_dropout" data-toggle="tooltip" data-placement="right">

        <span class="menu-icon"><img src="/images/menu_icons/Report.png"></span>

        <span class="menu-title">Student Dropout<br>Report</span>

    </a>

</li>
<li class="nav-item rpt_master_subject_enrl">

    <a class="nav-link" href="index.php?p=rpt_master_subject_enrl" data-toggle="tooltip" data-placement="right">

        <span class="menu-icon"><img src="/images/menu_icons/Report.png"></span>

        <span class="menu-title">Master Subject<br>Enrollment Report</span>

    </a>

</li>

<li class="nav-item rpt_centre_subject_enrl">

    <a class="nav-link" href="index.php?p=rpt_centre_subject_enrl" data-toggle="tooltip" data-placement="right">

        <span class="menu-icon"><img src="/images/menu_icons/Report.png"></span>

        <span class="menu-title">Centre Subject<br>Enrollment Report</span>

    </a>

</li>



<h3 class="text-center text-white font-weight-bold" style="margin-bottom: 0; padding-bottom: 0;">Master
    Franchise<br>Report</h3>
<li class="nav-item rpt_master">

    <a class="nav-link" href="index.php?p=rpt_master">

        <span class="menu-icon"><img src="/images/menu_icons/Report.png"></span>

        <span class="menu-title">Master Report</span>

    </a>

</li>
<li class="nav-item rpt_franchise">

    <a class="nav-link" href="index.php?p=rpt_franchise">

        <span class="menu-icon"><img src="/images/menu_icons/Report.png"></span>

        <span class="menu-title"> Franchise Report</span>

    </a>

</li>



<h3 class="text-center text-white font-weight-bold" style="margin-bottom: 0; padding-bottom: 0;">Account Report</h3>

<li class="nav-item rpt_sales">

    <a class="nav-link" href="index.php?p=rpt_sales" data-toggle="tooltip" data-placement="right">

        <span class="menu-icon"><img src="/images/menu_icons/Report.png"></span>

        <span class="menu-title">Centre Collection<br>Summary Report</span>

    </a>

</li>

<li class="nav-item rpt_detail_sales">

    <a class="nav-link" href="index.php?p=rpt_detail_sales" data-toggle="tooltip" data-placement="right">

        <span class="menu-icon"><img src="/images/menu_icons/Report.png"></span>

        <span class="menu-title">Individual Student<br>Collection Details</span>

    </a>

</li>

<li class="nav-item rpt_void">

    <a class="nav-link" href="index.php?p=rpt_void" data-toggle="tooltip" data-placement="right">

        <span class="menu-icon"><img src="/images/menu_icons/Report.png"></span>

        <span class="menu-title">Credit Note Listing<br>Report</span>

    </a>

</li>

<li class="nav-item rpt_discount">

    <a class="nav-link" href="index.php?p=rpt_discount" data-toggle="tooltip" data-placement="right">

        <span class="menu-icon"><img src="/images/menu_icons/Report.png"></span>

        <span class="menu-title">Discount Listing <br>Report</span>

    </a>

</li>

<li class="nav-item rpt_accumulative_fee">

    <a class="nav-link" href="index.php?p=rpt_accumulative_fee" data-toggle="tooltip" data-placement="right">

        <span class="menu-icon"><img src="/images/menu_icons/Report.png"></span>

        <span class="menu-title">Accumulative Fee<br>Report</span>

    </a>

</li>

<li class="nav-item rpt_outstanding">

    <a class="nav-link" href="index.php?p=rpt_outstanding" data-toggle="tooltip" data-placement="right">

        <span class="menu-icon"><img src="/images/menu_icons/Report.png"></span>

        <span class="menu-title">Outstanding Fee<br>Report</span>

    </a>

</li>

<li class="nav-item rpt_summary_category">

    <a class="nav-link" href="index.php?p=rpt_summary_category" data-toggle="tooltip" data-placement="right">

        <span class="menu-icon"><img src="/images/menu_icons/Report.png"></span>

        <span class="menu-title">Centre Monthly<br>Fees Report</span>

    </a>

</li>


<h3 class="text-center text-white font-weight-bold" style="margin-bottom: 0; padding-bottom: 0;">Student Card Report
</h3>
<li class="nav-item rptStudentTag">
    <a class="nav-link" href="index.php?p=rptStudentTag">
        <span class="menu-icon"><img src="/images/menu_icons/Report.png"></span>
        <span class="menu-title">Student Card <br>Report</span>
    </a>
</li>
<h3 class="text-center text-white font-weight-bold" style="margin-bottom: 0; padding-bottom: 0;">Centre Monthly Stock Report</h3>

   

   <!-- <li class="nav-item rpt_centre_termly_stock">

      <a class="nav-link" href="index.php?p=rpt_centre_termly_stock" data-toggle="tooltip" data-placement="right">

         <span class="menu-icon"><img src="/images/menu_icons/Report.png"></span>

         <span class="menu-title">Centre Termly Stock <br>Report</span>

      </a>

   </li> -->

   <li class="nav-item rpt_stock_balance_master">

      <a class="nav-link" href="index.php?p=rpt_stock_balance_master">

         <span class="menu-icon"><img src="/images/menu_icons/Report.png"></span>

         <span class="menu-title">Stock Balance <br>Report</span>

      </a>

   </li>
   <li class="nav-item stock_bal">
        <a class="nav-link" href="index.php?p=stock_bal">
            <span class="menu-icon"><img src="/images/menu_icons/Centre Stock Balance.png"></span>
            <span class="menu-title">Centre Stock Balance</span>
        </a>
    </li>
   <li class="nav-item rpt_schedule">

    <a class="nav-link" href="index.php?p=rpt_schedule" data-toggle="tooltip" data-placement="right">

        <span class="menu-icon"><img src="/images/menu_icons/Report.png"></span>

        <span class="menu-title">Schedule Report</span>

    </a>

</li>



<?php

}

?>

<?php

if ( (hasRightGroupXOR($_SESSION["UserName"], "DelcarationEdit|DeclarationView"))) {

?>
<li class="nav-item nav-category">
    <span class="nav-link">Declaration</span>
</li>
<li class="nav-item declaration_master">
   <a class="nav-link" href="index.php?p=declaration_master">
      <i class="mdi mdi-compass-outline menu-icon"></i>
      <span class="menu-title">Declaration</span>
   </a>
</li>

<?php

}

?>
<!-- <li class="nav-item nav-category">
   <span class="nav-link">Declaration</span>
</li>
<li class="nav-item declaration">
   <a class="nav-link" href="index.php?p=declaration">
      <i class="mdi mdi-compass-outline menu-icon"></i>
      <span class="menu-title">Declaration</span>
   </a>
</li> -->


<li class="nav-item">

    <a class="nav-link" href="logout.php">

        <span class="menu-icon"><img src="/images/menu_icons/Report.png"></span>

        <span class="menu-title">Logout</span>

    </a>

</li>