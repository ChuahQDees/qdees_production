
<!-- <li class="nav-item">
   <a class="nav-link" href="index.php?p=dashboard">
      <i class="fa fa-home menu-icon"></i>
      <span class="menu-title">Home</span>
   </a>
</li> -->

<style>
.sidebar p, .sidebar h3 {
    color: #000!important;

}
    .q-dees-dropdown-menu {
        list-style-type: none;
        padding-left: 0px!important;
        margin-left: 0px!important;
        margin-top: 10px !important;
        margin-bottom: 10px;
    }

   .sidebar .nav .nav-item.active > .nav-link{
	  	color:#000!important;
   }

    span.nav-link {padding: 1rem 1.875rem!important;}
    
    .q-dees-dropdown-menu .nav-item {}
    
    .sidebar .nav .nav-item .nav-link .menu-icon img {
          margin-right: 0.1rem;
          width:40px;
          max-width: none;
    }

    .q-dees-dropdown-menu .nav-item:hover{
        background: #3399ff;
		color:#000!important;
    }
    .sidebar .nav:not(.sub-menu) > .nav-item:hover:not(.nav-category):not(.nav-profile) > .nav-link {
    background: rgb(51 153 255 / 58%);
    color: #ffffff;
}
    
    .sidebar .nav .nav-item .nav-link .menu-title{
        font-weight: bold;
    }
        li.active {
        background: #3399ff;
        color: #000!important;
    }
    .sidebar .nav .nav-item .nav-link .menu-title {
    color: #000!important;
    }
</style>
<script>
$(document).ready(function(){
    var selectedClass = "<?php echo $_GET["p"] ?>";
    $("."+selectedClass).addClass("active");

})
</script>

<!----<div class="d-flex justify-content-center" style="margin: 2em 0">
    <a href="/"><span style="padding: 8px 10px!important; border-radius: 20em;" class="btn-qdees">Home</span></a>
</div>
<br>
<br>------>
<div >
    <li class="nav-item d-flex justify-content-center home">
                <a class="nav-link" href="/">
                    <span class="menu-icon"><img src="images/menu_icons/Visitor Reg.png"></span>
                    <span class="menu-title">Home</span>
                </a>
    </li>
</div>
<br>

<div class="qdees-drop">
    <li class="nav-item  nav-category" ><span class="nav-link">Administration</span></li>

    <ul class="q-dees-dropdown-menu ">

        <?php
        if ((($_SESSION["UserType"]=="A") || ($_SESSION["UserType"]=="O")) &
            (hasRightGroupXOR($_SESSION["UserName"], "VisitorEdit|VisitorView"))) {
            ?>
            <li class="nav-item visitor_hq">
                <a class="nav-link" href="index.php?p=visitor_hq">
                    <span class="menu-icon"><img src="images/menu_icons/Visitor Reg.png"></span>
                    <span class="menu-title">Visitor Registration</span>
                </a>
            </li>
            <?php
        }
        ?>
        <?php
        if ((($_SESSION["UserType"]=="A") || ($_SESSION["UserType"]=="O")) &
            (hasRightGroupXOR($_SESSION["UserName"], "StudentEdit|StudentView"))) {
            ?>
        <li class="nav-item student">
            <a class="nav-link" href="index.php?p=student">
                <span class="menu-icon"><img src="images/menu_icons/Stud Reg.png"></span>
                <span class="menu-title">Student Registration</span>
            </a>
        </li>
		
<?php
}
?>

<?php
if ((($_SESSION["UserType"]=="A") || ($_SESSION["UserType"]=="O")) &
   (hasRightGroupXOR($_SESSION["UserName"], "AllocationEdit|AllocationView"))) {
?>
        <li class="nav-item allocation_hq">
            <a class="nav-link" href="index.php?p=allocation_hq">
                <span class="menu-icon"><img src="images/menu_icons/Classroom Allocation.png"></span>
                <span class="menu-title">Classroom Allocation</span>
            </a>
        </li>
<?php
}
?>

        <li class="nav-item student_population">
            <a class="nav-link" href="index.php?p=student_population">
                <span class="menu-icon"><img src="images/menu_icons/Student Population.png"></span>
                <span class="menu-title">Student Population</span>
            </a>
        </li>

    </ul>
</div>

<div class="qdees-drop">
    <li class="nav-item  nav-category"><span class="nav-link">Point of Sales</span></li>
    <ul class="q-dees-dropdown-menu ">
        <?php
        if ((($_SESSION["UserType"]=="A") || ($_SESSION["UserType"]=="O")) &
            (hasRightGroupXOR($_SESSION["UserName"], "PointOfSalesEdit|PointOfSalesView"))) {
            ?>
            <li class="nav-item collection_pg1">
                <a class="nav-link" href="index.php?p=collection_pg1">
                    <span class="menu-icon"><img src="images/menu_icons/Payment.png"></span>
                    <span class="menu-title">Payment</span>
                </a>
            </li>
            <?php
        }
        ?>

        <?php
        if (($_SESSION["UserType"]=="A") &
            (hasRightGroupXOR($_SESSION["UserName"], "SalesEdit|SalesView"))) {
            ?>
            <li class="nav-item sales">
                <a class="nav-link" href="index.php?p=sales">
                    <span class="menu-icon"><img src="images/menu_icons/Payment History.png"></span>
                    <span class="menu-title">Payment History</span>
                </a>
            </li>
            <?php
        }
        ?>

        <?php
        if (($_SESSION["UserType"]=="A") &
            (hasRightGroupXOR($_SESSION["UserName"], "SalesEdit|SalesView"))) {
            ?>
            <li class="nav-item addon">
                <a class="nav-link" href="index.php?p=addon">
                    <span class="menu-icon"><img src="images/menu_icons/Student Population.png"></span>
                    <span class="menu-title">Addon Products</span>
                </a>
            </li>
			
            <?php
        }
        ?>
		<?php
        if (($_SESSION["UserType"]=="A") &
            (hasRightGroupXOR($_SESSION["UserName"], "SalesEdit|SalesView"))) {
            ?>
		<li class="nav-item fee_structure_setting">
				<a class="nav-link" href="index.php?p=fee_structure_setting">
					<span class="menu-icon"><img src="images/menu_icons/Payment.png"></span>
					<span class="menu-title">Fees Structure Setting</span>
				</a>
			</li>
			  <?php
        }
        ?>

    </ul>
</div>

<div class="qdees-drop">
    <?php
    if ((($_SESSION["UserType"]=="A") || ($_SESSION["UserType"]=="O")) &
        (hasRightGroupXOR($_SESSION["UserName"], "OrderView|OrderEdit|DefectiveProductView|DefectiveProductEdit|OrderStatusEdit|OrderStatusView"))) {
        ?>
        <li class="nav-item nav-category ">
            <span class="nav-link">Ordering</span>
        </li>
        <?php
    }
    ?>
    <ul class="q-dees-dropdown-menu ">

        <?php
        if ((($_SESSION["UserType"]=="A") || ($_SESSION["UserType"]=="O")) &
            (hasRightGroupXOR($_SESSION["UserName"], "OrderEdit"))) {
            ?>
            <li class="nav-item purchasing">
            <a class="nav-link" href="index.php?p=subject_category">
                    <span class="menu-icon"><img src="images/menu_icons/Order.png"></span>
                    <span class="menu-title">Order</span>
                </a>
            </li>
            <?php
        }
        ?>


        <?php
        if ((($_SESSION["UserType"]=="A") || ($_SESSION["UserType"]=="O")) &
            (hasRightGroupXOR($_SESSION["UserName"], "OrderEdit"))) {
            ?>
            <li class="nav-item">
                <a class="nav-link" onclick="viewCart();">
                    <span class="menu-icon"><img src="images/menu_icons/View Cart.png"></span>
                    <span class="menu-title">View Cart</span>
                    <span class="badge badge-pill badge-danger" id="no_in_cart_mnu"><?php echo noInCart();?></span>
                </a>
            </li>
            <?php
        }
        ?>

        <?php
        if ((($_SESSION["UserType"]=="A") || ($_SESSION["UserType"]=="O")) &
            (hasRightGroupXOR($_SESSION["UserName"], "OrderEdit"))) {
            ?>
            <li class="nav-item order_status_pg1" data-toggle="tooltip" data-placement="right" title="">
                <a class="nav-link" href="index.php?p=order_status_pg1">
                    <span class="menu-icon"><img src="images/menu_icons/Current Status Order.png"></span>
                    <span class="menu-title">Current Order Status</span>
                </a>
            </li>
            <?php
        }
        ?>

        <?php
            if (($_SESSION["UserType"]=="A") & (hasRightGroupXOR($_SESSION["UserName"], "KIVView"))) {
            ?>
                <li class="nav-item kiv" data-toggle="tooltip" data-placement="right" title="">
                    <a class="nav-link" href="index.php?p=kiv">
                        <span class="menu-icon"><img src="images/menu_icons/Defective Status.png"></span>
                        <span class="menu-title">KIV</span>
                    </a>
                </li>
        <?php
            }
        ?>

    </ul>
</div>

<div class="qdees-drop">

    <?php
    if ((($_SESSION["UserType"]=="A") || ($_SESSION["UserType"]=="O")) &
        (hasRightGroupXOR($_SESSION["UserName"], "DefectiveProductView|DefectiveProductEdit|DefectiveStatusView|DefectiveStatusEdit"))) {
        ?>
        <li class="nav-item nav-category ">
            <span class="nav-link">Defective</span>
        </li>
        <?php
    }
    ?>

    <ul class="q-dees-dropdown-menu ">
        <?php
        /*
        if ((($_SESSION["UserType"]=="A") || ($_SESSION["UserType"]=="O")) &
            (hasRightGroupXOR($_SESSION["UserName"], "DefectiveProductView|DefectiveProductEdit"))) {
            ?>
            <li class="nav-item">
                <a class="nav-link" href="index.php?p=defective_product">
                    <span class="menu-icon"><img src="images/menu_icons/Defective Product.png"></span>
                    <span class="menu-title">Defective Product</span>
                </a>
            </li>
            <?php
        }*/
        ?>
    
        <?php
        
        if ((($_SESSION["UserType"]=="A") || ($_SESSION["UserType"]=="O")) &
            (hasRightGroupXOR($_SESSION["UserName"], "DefectiveStatusView|DefectiveStatusEdit"))) {
            ?>
            <li class="nav-item defective_status_pg1">
                <!-- <a class="nav-link" href="index.php?p=order_defective&mode=defective"> -->
                <a class="nav-link" href="index.php?p=defective_status_pg1">
                    <span class="menu-icon"><img src="images/menu_icons/Defective Status.png"></span>
                    <span class="menu-title">Defective Status</span>
                </a>
            </li>
            <?php
        }
        ?>
        <?php
        if ((hasRightGroupXOR($_SESSION["UserName"], "KIVView|KIVEdit"))) {
        ?>
        <li class="nav-item kiv">
           <a class="nav-link" href="index.php?p=kiv">
              <span class="menu-icon"><img src="images/menu_icons/Defective Status.png"></span>
              <span class="menu-title">KIV</span>
           </a>
        </li>
        <?php
        }
        ?>
    </ul>
</div>

<div class="qdees-drop">
    <li class="nav-item  nav-category"><span class="nav-link">Setting</span></li>

    <li class="nav-item centre_information">
        <a class="nav-link" href="index.php?p=centre_information">
            <span class="menu-icon"><img src="images/menu_icons/Student Population.png"></span>
            <span class="menu-title">Centre Information</span>
        </a>
    </li>

    <?php
    if ( (hasRightGroupXOR($_SESSION["UserName"], "CentreStatementAccountEdit|CentreStatementAccountView"))) {
?>

<li class="nav-item centre_statement_account">

    <a class="nav-link" href="index.php?p=centre_statement_account">

        <span class="menu-icon"><img src="images/menu_icons/Centre Stock Balance.png"></span>

        <span class="menu-title">Centre Statement<br>Account</span>

    </a>

</li>

<?php

}

?>  
    <li class="nav-item schedule_term">
        <a class="nav-link" href="index.php?p=schedule_term">
            <span class="menu-icon"><img src="images/menu_icons/Student Population.png"></span>
            <span class="menu-title">Schedule Term</span>
        </a>
    </li>

    <ul class="q-dees-dropdown-menu ">
        <?php
        if (($_SESSION["UserType"]=="A") &
            (hasRightGroupXOR($_SESSION["UserName"], "UserEdit|UserView"))) {
            ?>
            <li class="nav-item user">
                <a class="nav-link" href="index.php?p=user">
                    <span class="menu-icon"><img src="images/menu_icons/UAC.png"></span>
                    <span class="menu-title">User Access <br>Control (UAC)</span>
                </a>
            </li>
            <?php
        }
        ?>
    </ul>
</div>

<div class="qdees-drop">
    <?php
    if ((($_SESSION["UserType"]=="A") || ($_SESSION["UserType"]=="O") & ($_SESSION["UserType"]=="S")) &
        (hasRightGroupXOR($_SESSION["UserName"], "ExportContactView|ReportingView"))) {
        ?>
        <li class="nav-item nav-category ">
            <span class="nav-link">Reports Setting</span>
        </li>
        <?php
    }
    ?>

    <ul class="q-dees-dropdown-menu ">
        <?php
        if ((($_SESSION["UserType"]=="A") || ($_SESSION["UserType"]=="O")) &
            (hasRightGroupXOR($_SESSION["UserName"], "ReportingView"))) {
            ?>
            <h3 class="text-center text-white font-weight-bold" style="margin-bottom: 0; padding-bottom: 0;">Student Population Report</h3>
            <li class="nav-item rpt_visitor_registration">
                <a class="nav-link" href="index.php?p=rpt_visitor_registration" >
                    <span class="menu-icon"><img src="images/menu_icons/Report.png"></span>
                    <span class="menu-title">Visitor Registration <br>Report</span>
                </a>
            </li>
            <li class="nav-item rpt_student_population">
                <a class="nav-link" href="index.php?p=rpt_student_population" >
                    <span class="menu-icon"><img src="images/menu_icons/Report.png"></span>
                    <span class="menu-title">Student Population <br>Report</span>
                </a>
            </li>
            <li class="nav-item rpt_class_listing">
                <a class="nav-link" href="index.php?p=rpt_class_listing" >
                    <span class="menu-icon"><img src="images/menu_icons/Report.png"></span>
                    <span class="menu-title">Class Listing <br>Report</span>
                </a>
            </li>
            <li class="nav-item rpt_student_dropout">
                <a class="nav-link" href="index.php?p=rpt_student_dropout" >
                    <span class="menu-icon"><img src="images/menu_icons/Report.png"></span>
                    <span class="menu-title">Student Dropout <br>Report</span>
                </a>
            </li>
            <li class="nav-item rpt_centre_subject_enrl">
                <a class="nav-link" href="index.php?p=rpt_centre_subject_enrl" >
                    <span class="menu-icon"><img src="images/menu_icons/Report.png"></span>
                    <span class="menu-title">Centre Subject <br>Enrolment Report</span>
                </a>
            </li>

            <h3 class="text-center text-white font-weight-bold" style="margin-bottom: 0; padding-bottom: 0;">Account Report</h3>
            <li class="nav-item rpt_sales">
                <a class="nav-link" href="index.php?p=rpt_sales" >
                    <span class="menu-icon"><img src="images/menu_icons/Report.png"></span>
                    <span class="menu-title">Centre Collection<br>Summary Report</span>
                </a>
            </li>
            <li class="nav-item rpt_detail_sales">
                <a class="nav-link" href="index.php?p=rpt_detail_sales" >
                    <span class="menu-icon"><img src="images/menu_icons/Report.png"></span>
                    <span class="menu-title">Individual Student<br>Collection Details</span>
                </a>
            </li>
            <li class="nav-item rpt_void">
                <a class="nav-link" href="index.php?p=rpt_void" >
                    <span class="menu-icon"><img src="images/menu_icons/Report.png"></span>
                    <span class="menu-title">Credit Note Listing <br>Report</span>
                </a>
            </li>
            <li class="nav-item rpt_discount">
                <a class="nav-link" href="index.php?p=rpt_discount" >
                    <span class="menu-icon"><img src="images/menu_icons/Report.png"></span>
                    <span class="menu-title">Discount Listing <br>Report</span>
                </a>
            </li>
            <li class="nav-item rpt_accumulative_fee">
                <a class="nav-link" href="index.php?p=rpt_accumulative_fee" >
                    <span class="menu-icon"><img src="images/menu_icons/Report.png"></span>
                    <span class="menu-title">Accumulative Fee <br>Report</span>
                </a>
            </li>
            <li class="nav-item rpt_outstanding">
                <a class="nav-link" href="index.php?p=rpt_outstanding" >
                    <span class="menu-icon"><img src="images/menu_icons/Report.png"></span>
                    <span class="menu-title">Outstanding Fee <br>Report</span>
                </a>
            </li>
            <li class="nav-item rpt_summary_category">
                <a class="nav-link" href="index.php?p=rpt_summary_category" >
                    <span class="menu-icon"><img src="images/menu_icons/Report.png"></span>
                    <span class="menu-title">Centre Monthly<br>Fees Report</span>
                </a>
            </li>
            <h3 class="text-center text-white font-weight-bold" style="margin-bottom: 0; padding-bottom: 0;">QR Code Report</h3>
            <li class="nav-item rptStudentTag">
                <a class="nav-link" href="index.php?p=rptStudentTag">
                    <span class="menu-icon"><img src="images/menu_icons/Report.png"></span>
                    <span class="menu-title">Student Card <br>Report</span>
                </a>
            </li>
            <h3 class="text-center text-white font-weight-bold" style="margin-bottom: 0; padding-bottom: 0;">Centre Stock Report</h3>
            <!-- <li class="nav-item rpt_centre_termly_stock">
                <a class="nav-link" href="index.php?p=rpt_centre_termly_stock" >
                    <span class="menu-icon"><img src="images/menu_icons/Report.png"></span>
                    <span class="menu-title">Centre Termly Stock <br>Report</span>
                </a>
            </li> -->
            <li class="nav-item rpt_stock_balance">
                <a class="nav-link" href="index.php?p=rpt_stock_balance" >
                    <span class="menu-icon"><img src="images/menu_icons/Report.png"></span>
                    <span class="menu-title">Stock Balance <br>Report</span>
                </a>
            </li>
            <li class="nav-item stock_bal">
                <a class="nav-link" href="index.php?p=stock_bal">
                    <span class="menu-icon"><img src="images/menu_icons/Centre Stock Balance.png"></span>
                    <span class="menu-title">Centre Stock Balance</span>
                </a>
            </li>
        <?php
        }
        ?>
    </ul>
</div>


<?php
if ((($_SESSION["UserType"]=="A") || ($_SESSION["UserType"]=="O")) &
   (hasRightGroupXOR($_SESSION["UserName"], "StudentEdit|StudentView"))) {
?>
<!-- <li class="nav-item">
   <a class="nav-link" href="index.php?p=dropout">
      <i class="fa fa-bullseye menu-icon"></i>
      <span class="menu-title">Dropout</span>
   </a>
</li> -->
<?php
}
?>
<?php
if ((($_SESSION["UserType"]=="A") || ($_SESSION["UserType"]=="O")) &
   (hasRightGroupXOR($_SESSION["UserName"], "SalesEdit|SalesView|PointOfSalesEdit|PointOfSalesView|CreditNoteEdit|CreditNoteView"))) {
?>
<?php
}
?>


<?php
if ((($_SESSION["UserType"]=="A") || ($_SESSION["UserType"]=="O")) &
   (hasRightGroupXOR($_SESSION["UserName"], "StocksEdit|StocksView"))) {
?>
<li class="nav-item">
   <a class="nav-link" href="index.php?p=stock">
      <i class="fa fa-truck menu-icon"></i>
      <span class="menu-title">Stocks</span>
   </a>
</li>
<?php
}
?>


<?php
if ((($_SESSION["UserType"]=="A") || ($_SESSION["UserType"]=="O")) &
   (hasRightGroupXOR($_SESSION["UserName"], "DelcarationEdit|DeclarationView"))) {
?>
<li class="nav-item nav-category">
   <span class="nav-link">Declaration</span>
</li>
<li class="nav-item declaration">
   <a class="nav-link" href="index.php?p=declaration">
      <i class="mdi mdi-compass-outline menu-icon"></i>
      <span class="menu-title">Declaration</span>
   </a>
</li> 
<?php
}
?>

<?php
if ((($_SESSION["UserType"]=="A") || ($_SESSION["UserType"]=="O")) &
   (hasRightGroupXOR($_SESSION["UserName"], "ExportContactView")) && $_SESSION["CentreCode"] == "MYQWESTC1C10231") {
?>
<li class="nav-item exp_contact">
   <a class="nav-link" href="index.php?p=exp_contact">
      <i class="fa fa-plane menu-icon"></i>
      <span class="menu-title">Export Contact</span>
   </a>
</li>
<?php
}
?> 

<li class="nav-item" style="display:none;">
   <a class="nav-link" href="logout.php">
      <i class="mdi mdi-logout menu-icon"></i>
      <span class="menu-title">Logout</span>
   </a>
</li>