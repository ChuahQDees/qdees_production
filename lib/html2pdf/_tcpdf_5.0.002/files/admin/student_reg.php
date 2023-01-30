<a href="/index.php?p=student">
                 <span class="btn-qdees"><i class="fa fa-arrow-left" style="margin-right: 20px;"></i>Back</span>
</a>
<span>
    <span class="page_title"><img src="/images/title_Icons/Stud Reg.png">Student Registration</span>
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
        $m=$_GET["m"];
        $pg=$_GET["pg"];

        include_once("student_func.php");
        include_once("admin/student_form.php");

        echo $pagination;
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
        }
    } else {
        echo "<div class='uk-margin-top uk-margin-right'><div class='uk-alert uk-alert-danger uk-text-large uk-text-bold uk-text-center'>Unauthorised access denied</div></div>";
    }
} else {
    header("Location: index.php");
}
?>
