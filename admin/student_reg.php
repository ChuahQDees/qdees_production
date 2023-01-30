<?php 
    /* ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL); */
?>

<?php if($_GET['p'] == "student_reg" && isset($_GET['visitor']) ){ ?>
<a href="/index.php?p=visitor_qr_list">                 
             <span class="d_n btn-qdees"><i class="fa fa-arrow-left" style="margin-right: 20px;"></i>Back</span>
</a>

<?php }else if($_GET['p'] == "student_reg" && isset($_GET['m']) ){ ?>
    <a href="/index.php?p=student_population">                 
             <span class="d_n btn-qdees"><i class="fa fa-arrow-left" style="margin-right: 20px;"></i>Back</span>
</a>

<?php }else { ?>
    <a  onclick="history.back();">                
             <span class="d_n btn-qdees"><i class="fa fa-arrow-left" style="margin-right: 20px;"></i>Back</span>
</a>
<?php } ?>
<span>
    <span class="page_title"><img src="images/title_Icons/StudReg.png">Student Registration</span>
</span>

<?php
include_once("admin/functions.php");
define('IS_ADMIN_STUDENT_FORM', true);

if ($_SESSION["isLogin"]==1) {
    if ((($_SESSION["UserType"]=="A") || ($_SESSION["UserType"]=="O")) &
        (hasRightGroupXOR($_SESSION["UserName"], "StudentEdit|StudentView"))) {
        include_once("mysql.php");
        include_once("lib/pagination/pagination.php");
        $p=$_GET["p"];
        $m=isset($_GET["m"]) ? $_GET["m"] : '';
        $pg=isset($_GET["pg"]) ? $_GET["pg"] : '';
        $child_no=isset($_GET["ch"]) ? $_GET["ch"] : '';
        $sha1_visitor_id=isset($_GET["visitor"]) ? $_GET["visitor"] : '';

        include_once("student_func.php");
        include_once("admin/student_form.php");
        if(isset($pagination))
        {
            echo $pagination;
        }
        ?>
        </div>
        <form name="frmDeleteRecord" id="frmDeleteRecord" method="get">
            <input type="hidden" name="p" value="<?php echo $p?>">
            <input type="hidden" name="m" value="<?php echo $m?>">
            <input type="hidden" name="id" id="id" value="">
            <input type="hidden" name="mode" value="DEL">
        </form>

        <?php
        if ($msg!="") {
            echo "<script>UIkit.notify('$msg')</script>";
            if ($msg=="Student registered successfully" || $msg=="Student successfully saved.") {
                echo "<script type='text/javascript'>setTimeout(
                    function() 
                    {window.top.location='index.php?p=student_reg';}, 1500);</script>";
            }
            if ($msg=="Student registered successfully") {
                unset($_SESSION["StudentCodeTaken"]);
            }
        }
        
        
    } else {
        echo "<div class='uk-margin-top uk-margin-right'><div class='uk-alert uk-alert-danger uk-text-large uk-text-bold uk-text-center'>Unauthorised access denied</div></div>";
    }
} else {
    header("Location: index.php");
}
?>
