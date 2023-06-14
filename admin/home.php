<?php
//session_start();
$year=$_SESSION['Year'];

include_once("mysql.php");
include_once("admin/functions.php");

if(date('Y-m-d') <= $year_end_date && date('Y-m-d') >= $year_start_date)
{
    $currentYear=$_SESSION['Year'];
}
else
{
    $currentYear=date("Y");
}

$currentMonth=date("m");
$strCurrentMonth=date("M");

function isDelivered($order_no) {
   global $connection;

   $sql="SELECT * from `order` where order_no='$order_no'";;
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);
   if ($row["delivered_to_logistic_by"]!="") {
      return true;
   } else {
      return false;
   }
}
//echo $_SESSION["UserType"]; //die;
// if ($_SESSION["UserType"]=="S" || $_SESSION["UserType"]=="A") {
  if ($_SESSION["UserType"]=="S" || $_SESSION["UserType"]=="H
  " || $_SESSION["UserType"]=="C" || $_SESSION["UserType"]=="R" || $_SESSION["UserType"]=="CM" || $_SESSION["UserType"]=="T") {
?>
<div class="row">

    <!-- <div class="col-sm-12 col-md mobile-margin-50">
    <?php
   // if (strtoupper($_SESSION["UserName"])=="SUPER") {
       //$sql="SELECT * from `master` where year(year_of_commencement) <= '$year' and year(expiry_date) >= '$year'";
  //  } else {
    //   $sql="SELECT * from `master` where upline='".$_SESSION["CentreCode"]."' and (year(year_of_commencement)<= '$year' and year(expiry_date) >= '$year')";
 //   }
	
   //  $result=mysqli_query($connection, $sql);
   //  $num_row=mysqli_num_rows($result);
    ?>
        <div class="main-box-content">
            <div class="box-head">
        <p>Number of Master</p>
            </div>
            <div class="box-content">
                <div class="row">
                    <div class="col-sm-12 col-md-6 d-flex align-items-center justify-content-center">
                        <div class="border-bg">
            <?php
            //if (hasRightGroupXOR($_SESSION["UserName"], "MasterView|MasterEdit")) {
            ?>
              <a href="index.php?p=master"><i class="mdi mdi-security"></i></a>
            <?php
           // } else {
            ?>
              <i class="mdi mdi-security"></i>
            <?php
           // }
            ?>
            </div>
                    </div>
                    <div class="col-sm-12 col-md-6 d-flex align-items-center justify-content-center">
                        <span class="q-text" ><?php //echo $num_row?></span>
                    </div>
                </div>
            </div>
        </div>
    </div> -->

    <div class="col-sm-12 col-md mobile-margin-50">
        <?php
    // if (strtoupper($_SESSION["UserName"])=="SUPER") {
        if ($_SESSION["UserType"]=="S" || $_SESSION["UserType"]=="H
        " || $_SESSION["UserType"]=="C" || $_SESSION["UserType"]=="R" || $_SESSION["UserType"]=="CM" || $_SESSION["UserType"]=="T") {
       $sql="SELECT * from centre where  year(created_date) <= '$year' and status='A'";
    } else {
       $sql="SELECT * from centre where upline='".$_SESSION["CentreCode"]."' and year(created_date) <= '$year' and status='A'";
    }
		   // echo $sql; die;

    $result=mysqli_query($connection, $sql);
    $num_row=mysqli_num_rows($result);
    ?>
        <div class="main-box-content">
            <div class="box-head background-red box-after-red">
                <p>Number of Centre</p>
            </div>
            <div class="box-content">
                <div class="row">
                    <div class="col-sm-12 col-md-5 d-flex align-items-center justify-content-center">
                        <!-- <div class="border-bg box-border-red">
                            <?php
            //if (hasRightGroupXOR($_SESSION["UserName"], "CentreEdit|CentreView")) {
            ?>
                            <a href="index.php?p=centre"><i class="fa fa-address-card"></i></a>
                            <?php
           // } else {
            ?>
                            <i class="fa fa-address-card"></i>
                            <?php
            // }
            ?></div> -->
                        <div class="border-bg box-border-red"><a href="index.php?p=centre"><img
                                    src="images/card_img.png" width="80px"></a>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-7 d-flex align-items-center justify-content-center">
                        <span class="text-color-red q-text"><?php echo $num_row; ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-md mobile-margin-50">
        <?php
        $sql="SELECT count(*) as count from visitor where year(date_created)='$year' and month(date_created)='$currentMonth'";
        //echo $sql;
        //$sql="SELECT count(*) from visitor where centre_code='".$_SESSION["CentreCode"];
        if($_SESSION["CentreCode"]!=""){
            $sql .= " and centre_code='".$_SESSION["CentreCode"]."' ";
        }
        $result=mysqli_query($connection, $sql);
        $num_row=mysqli_num_rows($result);
        $row=mysqli_fetch_assoc($result);
        ?>
        <div class="main-box-content">
            <div class="box-head">
                <p>Number of Visitor (<?php echo $strCurrentMonth?>)</p>
            </div>
            <div class="box-content">
                <div class="row">
                    <div class="col-sm-12 col-md-5 d-flex align-items-center justify-content-center">
                        <div class="border-bg"><a href="index.php?p=rpt_visitor_registration"><img
                                    src="images/Visitor.png" width="80px"></a></div>
                    </div>
                    <div class="col-sm-12 col-md-7 d-flex align-items-center justify-content-center">
                        <span class="q-text"><?php echo $row["count"]?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <div class="col-sm-12 col-md mobile-margin-50">
        <?php 
    // if (strtoupper($_SESSION["UserName"])=="SUPER") {
        if ($_SESSION["UserType"]=="S" || $_SESSION["UserType"]=="H
        " || $_SESSION["UserType"]=="C" || $_SESSION["UserType"]=="R" || $_SESSION["UserType"]=="CM" || $_SESSION["UserType"]=="T") {
            //$sql="select l.student_entry_level, s.level_count from (SELECT DISTINCT student_entry_level from student) l left join ( SELECT student_entry_level, count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id where year(fl.programme_date) = '$year' and ((MONTH(fl.programme_date) >= MONTH(CURRENT_DATE()) and MONTH(fl.programme_date) <= MONTH(CURRENT_DATE())) or (MONTH(fl.programme_date_end) >= MONTH(CURRENT_DATE()) and MONTH(fl.programme_date_end) >= MONTH(CURRENT_DATE()))) and ps.student_entry_level != '' and student_status = 'A' and deleted='0' group by ps.student_entry_level, s.id) ab group by student_entry_level ) s on s.student_entry_level = l.student_entry_level where l.student_entry_level !=''";
       //$sql="select l.student_entry_level, s.level_count from (SELECT DISTINCT student_entry_level from student) l left join (SELECT ps.student_entry_level, count(id) level_count from student s inner join programme_selection ps on ps.student_id=s.id where  student_status = 'A'and ps.student_entry_level != '' and year(start_date_at_centre) <= '$year' and extend_year >= '$year' and deleted='0' group by ps.student_entry_level) s on s.student_entry_level = l.student_entry_level where l.student_entry_level !=''";
      // $sql="SELECT * from student where student_status = 'A' and year(start_date_at_centre) <= '$year' and extend_year >= '$year' and deleted='0' ";
        // echo $sql; die;

        //CHS: SQL Fix
        $sql="SELECT student_entry_level , count(id) level_count 
        from (SELECT ps.student_entry_level, s.id 
        from student s inner join programme_selection ps on ps.student_id=s.id 
        inner join student_fee_list fl on fl.programme_selection_id = ps.id 
        inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date >= '$year_start_date' AND fl.programme_date <= '$year_end_date') 
        and ps.student_entry_level != '' and s.student_status = 'A' 
        and s.centre_code='".$_SESSION["CentreCode"]."' 
        and ((fl.programme_date >= '$year_start_date' and fl.programme_date <= '$year_end_date') 
        or (fl.programme_date_end >= '$year_start_date' and fl.programme_date_end <= '$year_end_date')) 
        and s.deleted='0' group by ps.student_entry_level, s.id) ab
        GROUP BY student_entry_level";


       //$sql2="SELECT count(id) level_count from( SELECT s.id from student s, programme_selection ps, student_fee_list fl, fee_structure f  where s.id=ps.student_id and ps.id = fl.programme_selection_id and f.id=fl.fee_id and fl.foundation_mandarin=1 and year(fl.programme_date) = '$year' and ((MONTH(fl.programme_date) >= MONTH(CURRENT_DATE()) and MONTH(fl.programme_date) <= MONTH(CURRENT_DATE())) or (MONTH(fl.programme_date_end) >= MONTH(CURRENT_DATE()) and MONTH(fl.programme_date_end) >= MONTH(CURRENT_DATE()))) and s.student_status = 'A' and s.deleted='0' group by s.id ) ab";

       $sql2="SELECT count(id) level_count from (SELECT s.id from  student s, programme_selection ps, student_fee_list fl, fee_structure f 
       where s.id=ps.student_id 
       and ps.id = fl.programme_selection_id 
       and f.id=fl.fee_id 
       and fl.foundation_int_mandarin=1 
       and s.centre_code='".$_SESSION["CentreCode"]."' 
       and year(fl.programme_date) = '$year' 
       and ((MONTH(fl.programme_date) >= MONTH(CURRENT_DATE()) 
       and MONTH(fl.programme_date) <= MONTH(CURRENT_DATE())) 
       or (MONTH(fl.programme_date_end) >= MONTH(CURRENT_DATE()) 
       and MONTH(fl.programme_date_end) >= MONTH(CURRENT_DATE()))) 
       and s.student_status = 'A' 
       and s.deleted='0' group by s.id ) ab";
       
       //$sql2="SELECT count(id) level_count from student where student_status = 'A' and year(start_date_at_centre) <= '$year' and extend_year >= '$year' and deleted='0' and foundation_mandarin = 'yes'";
        // echo $sql2;
    } 
    // else {
    //   $sql="SELECT * from student where student_status = 'A' and year(start_date_at_centre) <= '$year' and extend_year >= '$year' and deleted='0' ";
    // }
	// echo $sql;
    // var_dump($sql);
    $result=mysqli_query($connection, $sql);
    $num_row=mysqli_num_rows($result);
    // $row=mysqli_fetch_assoc($result);

    $result2=mysqli_query($connection, $sql2);
    $num_row2=mysqli_num_rows($result2);
    $row2=mysqli_fetch_assoc($result2);
    ?>
        <div class="main-box-content">
            <div class="box-head background-red box-after-red">
                <p>Foundatio a</p>
            </div>
            <div class="box-content">
                <div class="row">
                    <div class="col-sm-12 col-md-5 d-flex align-items-center justify-content-center">
                        <!-- <div class="border-bg box-border-red">
                            <?php
           // if (hasRightGroupXOR($_SESSION["UserName"], "OrderStatusEdit|OrderStatusView")) {
            ?>
                            <a href="index.php?p=order_status_pg1"><i class="fa fa-edit"></i></a>
                            <?php
           // } else {
            ?>
                            <i class="fa fa-address-card"></i>
                            <?php
            // }
            ?></div> -->
                        <div class="border-bg box-border-red"><a href="index.php?p=rpt_master_subject_enrl"><img
                                    src="images/edit1.png" width="80px"></a>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-7 d-flex  drs_dsa fwbold">
                        <?php if ($num_row>0) {
             while ($row=mysqli_fetch_assoc($result)) {
                ?>      <span class="gd_sb11">
                        <span
                            class="text-color-red q-text_w1"><?php echo $row["student_entry_level"] ?></span><span><?php echo (empty($row["level_count"]) ? "0" : $row["level_count"]); ?></span>
                            </span>
                        <?php
                     }
            }
            ?>          <span class="gd_sb11">
                        <span class="text-color-red q-text_w1"><?php echo "F.Mand " ?></span><span>  <?php echo $row2["level_count"]; ?></span></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-md mobile-margin-50">
        <?php 
    // if (strtoupper($_SESSION["UserName"])=="SUPER") {
        if ($_SESSION["UserType"]=="S" || $_SESSION["UserType"]=="H
        " || $_SESSION["UserType"]=="C" || $_SESSION["UserType"]=="R" || $_SESSION["UserType"]=="CM" || $_SESSION["UserType"]=="T") {

            $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s 
        inner join programme_selection ps on ps.student_id=s.id 
        inner join student_fee_list fl on fl.programme_selection_id = ps.id 
        inner join fee_structure f on f.id=fl.fee_id 
        where (fl.programme_date >= '$year_start_date' AND fl.programme_date <= '$year_end_date') 
        and ps.student_entry_level != '' and s.student_status = 'A' 
        and s.centre_code='".$_SESSION["CentreCode"]."' 
        and ((fl.programme_date >= '$year_start_date' and fl.programme_date <= '$year_end_date') 
        or (fl.programme_date_end >= '$year_start_date' and fl.programme_date_end <= '$year_end_date')) 
        and s.deleted='0'
        and fl.foundation_int_english=1 
        group by ps.student_entry_level, s.id) ab";

        $sql2="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s 
        inner join programme_selection ps on ps.student_id=s.id 
        inner join student_fee_list fl on fl.programme_selection_id = ps.id 
        inner join fee_structure f on f.id=fl.fee_id 
        where (fl.programme_date >= '$year_start_date' AND fl.programme_date <= '$year_end_date') 
        and ps.student_entry_level != '' and s.student_status = 'A' 
        and s.centre_code='".$_SESSION["CentreCode"]."' 
        and ((fl.programme_date >= '$year_start_date' and fl.programme_date <= '$year_end_date') 
        or (fl.programme_date_end >= '$year_start_date' and fl.programme_date_end <= '$year_end_date')) 
        and s.deleted='0'
        and fl.foundation_iq_math=1
        group by ps.student_entry_level, s.id) ab";

        $sql3="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s 
        inner join programme_selection ps on ps.student_id=s.id 
        inner join student_fee_list fl on fl.programme_selection_id = ps.id 
        inner join fee_structure f on f.id=fl.fee_id 
        where (fl.programme_date >= '$year_start_date' AND fl.programme_date <= '$year_end_date') 
        and ps.student_entry_level != '' and s.student_status = 'A' 
        and s.centre_code='".$_SESSION["CentreCode"]."' 
        and ((fl.programme_date >= '$year_start_date' and fl.programme_date <= '$year_end_date') 
        or (fl.programme_date_end >= '$year_start_date' and fl.programme_date_end <= '$year_end_date')) 
        and s.deleted='0'
        and fl.foundation_int_mandarin=1
        group by ps.student_entry_level, s.id) ab";

        $sql4="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s 
        inner join programme_selection ps on ps.student_id=s.id 
        inner join student_fee_list fl on fl.programme_selection_id = ps.id 
        inner join fee_structure f on f.id=fl.fee_id 
        where (fl.programme_date >= '$year_start_date' AND fl.programme_date <= '$year_end_date') 
        and ps.student_entry_level != '' and s.student_status = 'A' 
        and s.centre_code='".$_SESSION["CentreCode"]."' 
        and ((fl.programme_date >= '$year_start_date' and fl.programme_date <= '$year_end_date') 
        or (fl.programme_date_end >= '$year_start_date' and fl.programme_date_end <= '$year_end_date')) 
        and s.deleted='0'
        and fl.foundation_int_art=1
        group by ps.student_entry_level, s.id) ab";

        $sql5="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s 
        inner join programme_selection ps on ps.student_id=s.id 
        inner join student_fee_list fl on fl.programme_selection_id = ps.id 
        inner join fee_structure f on f.id=fl.fee_id 
        where (fl.programme_date >= '$year_start_date' AND fl.programme_date <= '$year_end_date') 
        and ps.student_entry_level != '' and s.student_status = 'A' 
        and s.centre_code='".$_SESSION["CentreCode"]."' 
        and ((fl.programme_date >= '$year_start_date' and fl.programme_date <= '$year_end_date') 
        or (fl.programme_date_end >= '$year_start_date' and fl.programme_date_end <= '$year_end_date')) 
        and s.deleted='0'
        and fl.pendidikan_islam=1
        group by ps.student_entry_level, s.id) ab";

        //$sql="SELECT count(id) level_count from( SELECT s.id from  student s, programme_selection ps, student_fee_list fl, fee_structure f  where s.id=ps.student_id and ps.id = fl.programme_selection_id and f.id=fl.fee_id and year(fl.programme_date) = '$year' and ((MONTH(fl.programme_date) >= MONTH(CURRENT_DATE()) and MONTH(fl.programme_date) <= MONTH(CURRENT_DATE())) or (MONTH(fl.programme_date_end) >= MONTH(CURRENT_DATE()) and MONTH(fl.programme_date_end) >= MONTH(CURRENT_DATE()))) and s.student_status = 'A' and s.deleted='0' and fl.foundation_int_english=1 group by s.id ) ab";
        //$sql="SELECT count(id) level_count from student where student_status = 'A' and year(start_date_at_centre) <= '$year' and extend_year >= '$year' and deleted='0' and foundation_int_english = '1'";
        //$sql2="SELECT count(id) level_count from student where student_status = 'A' and year(start_date_at_centre) <= '$year' and extend_year >= '$year' and deleted='0' and foundation_iq_math = '1'";
        //$sql2="SELECT count(id) level_count from( SELECT s.id from  student s, programme_selection ps, student_fee_list fl, fee_structure f  where s.id=ps.student_id and ps.id = fl.programme_selection_id and f.id=fl.fee_id and year(fl.programme_date) = '$year' and ((MONTH(fl.programme_date) >= MONTH(CURRENT_DATE()) and MONTH(fl.programme_date) <= MONTH(CURRENT_DATE())) or (MONTH(fl.programme_date_end) >= MONTH(CURRENT_DATE()) and MONTH(fl.programme_date_end) >= MONTH(CURRENT_DATE()))) and s.student_status = 'A' and s.deleted='0' and fl.foundation_iq_math=1 group by s.id ) ab";
        //$sql3="SELECT count(id) level_count from student where student_status = 'A' and year(start_date_at_centre) <= '$year' and extend_year >= '$year' and deleted='0' and foundation_int_mandarin = '1'";
        //$sql3="SELECT count(id) level_count from( SELECT s.id from  student s, programme_selection ps, student_fee_list fl, fee_structure f  where s.id=ps.student_id and ps.id = fl.programme_selection_id and f.id=fl.fee_id and year(fl.programme_date) = '$year' and ((MONTH(fl.programme_date) >= MONTH(CURRENT_DATE()) and MONTH(fl.programme_date) <= MONTH(CURRENT_DATE())) or (MONTH(fl.programme_date_end) >= MONTH(CURRENT_DATE()) and MONTH(fl.programme_date_end) >= MONTH(CURRENT_DATE()))) and s.student_status = 'A' and s.deleted='0' and fl.foundation_int_mandarin=1 group by s.id ) ab";
        //$sql4="SELECT count(id) level_count from student where student_status = 'A' and year(start_date_at_centre) <= '$year' and extend_year >= '$year' and deleted='0' and foundation_int_art = '1'";
        //$sql4="SELECT count(id) level_count from( SELECT s.id from  student s, programme_selection ps, student_fee_list fl, fee_structure f  where s.id=ps.student_id and ps.id = fl.programme_selection_id and f.id=fl.fee_id and year(fl.programme_date) = '$year' and ((MONTH(fl.programme_date) >= MONTH(CURRENT_DATE()) and MONTH(fl.programme_date) <= MONTH(CURRENT_DATE())) or (MONTH(fl.programme_date_end) >= MONTH(CURRENT_DATE()) and MONTH(fl.programme_date_end) >= MONTH(CURRENT_DATE()))) and s.student_status = 'A' and s.deleted='0' and fl.foundation_int_art=1 group by s.id ) ab";
        //$sql5="SELECT count(id) level_count from student where student_status = 'A' and year(start_date_at_centre) <= '$year' and extend_year >= '$year' and deleted='0' and pendidikan_islam = '1'";
        //$sql5="SELECT count(id) level_count from( SELECT s.id from  student s, programme_selection ps, student_fee_list fl, fee_structure f  where s.id=ps.student_id and ps.id = fl.programme_selection_id and f.id=fl.fee_id and year(fl.programme_date) = '$year' and ((MONTH(fl.programme_date) >= MONTH(CURRENT_DATE()) and MONTH(fl.programme_date) <= MONTH(CURRENT_DATE())) or (MONTH(fl.programme_date_end) >= MONTH(CURRENT_DATE()) and MONTH(fl.programme_date_end) >= MONTH(CURRENT_DATE()))) and s.student_status = 'A' and s.deleted='0' and fl.pendidikan_islam=1 group by s.id ) ab";
       //$sql="SELECT * from student where student_status = 'A' and year(start_date_at_centre) <= '$year' and extend_year >= '$year' and deleted='0' ";

    }
    //  else {
    //   $sql="SELECT * from student where student_status = 'A' and year(start_date_at_centre) <= '$year' and extend_year >= '$year' and deleted='0' ";
    // }
	// echo $sql;
    // var_dump($sql);
    $result=mysqli_query($connection, $sql);
    $num_row=mysqli_num_rows($result);
    $row=mysqli_fetch_assoc($result);

    $result2=mysqli_query($connection, $sql2);
    $num_row2=mysqli_num_rows($result2);
    $row2=mysqli_fetch_assoc($result2);

    $result3=mysqli_query($connection, $sql3);
    $num_row3=mysqli_num_rows($result3);
    $row3=mysqli_fetch_assoc($result3);

    $result4=mysqli_query($connection, $sql4);
    $num_row4=mysqli_num_rows($result4);
    $row4=mysqli_fetch_assoc($result4);

    $result5=mysqli_query($connection, $sql5);
    $num_row5=mysqli_num_rows($result5);
    $row5=mysqli_fetch_assoc($result5);

    $intEnglishCount = 0;
    $IQMathCount = 0;
    $MandarinCount = 0;
    $IntArtCount = 0;
    $PIslamCount = 0;

    if($row["level_count"] > 0) $intEnglishCount = $row["level_count"];
    if($row2["level_count"] > 0) $IQMathCount = $row2["level_count"];
    if($row3["level_count"] > 0) $MandarinCount = $row3["level_count"];
    if($row4["level_count"] > 0) $IntArtCount = $row4["level_count"];
    if($row5["level_count"] > 0) $PIslamCount = $row5["level_count"];
    ?>
        <div class="main-box-content">
            <div class="box-head">
                <p>Enhanced Foundationvasd</p>
            </div>
            <div class="box-content">
                <div class="row">
                    <div class="col-sm-12 col-md-5 d-flex align-items-center justify-content-center">
                        <div class="border-bg">
                            <?php
            if (hasRightGroupXOR($_SESSION["UserName"], "StudentEdit|StudentView")) {
            ?>
                            <a href="index.php?p=rpt_master_subject_enrl"><img src="images/Active Students.png"
                                    width="80px"></a>
                            <?php
            } else {
            ?>
                            <a href="index.php?p=rpt_master_subject_enrl"><img src="images/Active Students.png"
                                    width="80px"></a>
                            <?php
            }
            ?></div>
                    </div>
                    <div class="col-sm-12 col-md-7 d-flex drs_dsa fwbold">
                    <span class="gd_sb22"> 
                        <span class="text-color-red q-text_w2">Int. Enga </span><span><?php echo $intEnglishCount; ?></span>
                    </span>
                    <span class="gd_sb22">    
                        <span class="text-color-red q-text_w2">IQ Math </span><span><?php echo $IQMathCount; ?></span>
                    </span>
                    <span class="gd_sb22">    
                        <span class="text-color-red q-text_w2">Mandarin </span><span><?php echo $MandarinCount; ?></span>
                    </span>
                    <span class="gd_sb22">    
                        <span class="text-color-red q-text_w2">Int. Art </span><span><?php echo $IntArtCount; ?></span>
                    </span>
                    <span class="gd_sb22">    
                        <span class="text-color-red q-text_w2">P. Islam </span><span><?php echo $PIslamCount; ?></span>
                    </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-md mobile-margin-50">
        <?php
    // if (strtoupper($_SESSION["UserName"])=="SUPER") {
    if ($_SESSION["UserType"]=="S" || $_SESSION["UserType"]=="H
    " || $_SESSION["UserType"]=="C" || $_SESSION["UserType"]=="R" || $_SESSION["UserType"]=="CM" || $_SESSION["UserType"]=="T") {
       $sql="SELECT o.* from `order` o, centre c where month(ordered_on)='".$currentMonth."' and  year(ordered_on) = '$year' and o.centre_code=c.centre_code";
    } else {
       $sql="SELECT o.* from `order` o, centre c where month(ordered_on)='".$currentMonth."' and o.centre_code=c.centre_code
       and c.upline='".$_SESSION["CentreCode"]."' and  year(ordered_on) = '$year'";
    }
    $sql .= " and o.acknowledged_by IS NULL and o.logistic_approved_by IS NULL and o.finance_approved_by IS NULL and o.packed_by IS NULL 
    and o.delivered_to_logistic_by IS NULL and o.cancelled_by IS NULL group by order_no";
    //echo $sql;
    $result=mysqli_query($connection, $sql);
    $num_row=mysqli_num_rows($result);
    ?>
        <div class="main-box-content">
            <div class="box-head background-red box-after-red">
                <p>Pending Order (<?php echo $strCurrentMonth?>)</p>
            </div>
            <div class="box-content">
                <div class="row">
                    <div class="col-sm-12 col-md-5 d-flex align-items-center justify-content-center">
                        <!-- <div class="border-bg box-border-red">
                            <?php
            // if (hasRightGroupXOR($_SESSION["UserName"], "OrderStatusEdit|OrderStatusView")) {
            ?>
                            <a href="index.php?p=order_status_pg1"><i class="fa fa-edit"></i></a>
                            <?php
            // } else {
            ?>
                            <i class="fa fa-address-card"></i>
                            <?php
            // }
            ?></div> -->
                        <div class="border-bg box-border-red"><a href="index.php?p=order_status_pg1"><img
                                    src="images/edit1.png" width="80px"></a>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-7 d-flex align-items-center justify-content-center">
                        <span class="text-color-red q-text"><?php echo $num_row; ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="clear w-100"></div>
</div>
















<div class="row" style="<?php if($_SESSION['UserName'] == 'management') { echo 'display:none;'; } ?>">
    <div class="col-md-12">
        <div class="header-top-part">
            <h3 style="color: white !important" class="sales-title">Current Order's</h3>
        </div>
        <div class="orderstatusmain header-bottom-part" style="padding: 20px 0;">
            <div class=" table-responsive-md">
                <table style="margin: 2rem 0px; width: 100%; margin: 0 auto;" class="">

                    <tr class="enlist-parent">
                        <td class="enlist">Centre Name</td>
                        <td class="enlist">Order No.</td>
                        <td class="enlist">Order date</td>
                        <td class="enlist">Status</td>
                        <td class="enlist">Action</td>
                    </tr>
                    <?php
           if ($_SESSION["UserType"]=="S" || $_SESSION["UserType"]=="H
           " || $_SESSION["UserType"]=="C" || $_SESSION["UserType"]=="R" || $_SESSION["UserType"]=="CM" || $_SESSION["UserType"]=="T") {
            // if (strtoupper($_SESSION["UserName"])=="SUPER") {
              $sql="SELECT distinct order_no, ordered_by, ordered_on, delivered_to_logistic_by, delivered_to_logistic_on, courier, kindergarten_name, c.company_name from `order` left join centre c on c.centre_code=`order`.centre_code  where year(ordered_on)='$year' group by order_no, CAST(ordered_on AS DATE), delivered_to_logistic_by,c.company_name ORDER BY `order`.`ordered_on` DESC   limit 10";
             
            //  } else {

            //  }
           } else {
             $sql="SELECT distinct order_no, ordered_on, delivered_to_logistic_by,company_name from `order` where centre_code='".$_SESSION["CentreCode"]."' and
             month(ordered_on)='$currentMonth' group by order_no, CAST(ordered_on AS DATE), delivered_to_logistic_by,company_name ORDER BY `order`.`ordered_on` DESC  limit 10";
           }

            //var_dump($sql);
           $result=mysqli_query($connection, $sql);
           $num_row=mysqli_num_rows($result);

           if ($num_row>0) {
             while ($row=mysqli_fetch_assoc($result)) {
               ?>

                    <tr>
                        <td class="">
                            <?php echo $row["company_name"]/* . ' (' . $row["centre_code"] . ')'*/?>
                        </td>
                        <td class="">
                            <a
                                href="../index.php?p=order_status&sOrderNo=<?php echo $row['order_no']?>"><?php echo $row["order_no"]?></a>
                        </td>
                        <td class="">
                            <?php echo $row["ordered_on"]?>
                        </td>

                        <td class="">
                            <?php echo getStatus($row["order_no"])?>
                        </td>
                        <td style="white-space: nowrap;">
                            <?php
                              if (($_SESSION["isLogin"] == 1) & (hasRightGroupXOR($_SESSION["UserName"], "OrderStatusEdit|OrderStatusView"))) {
                              ?>
                            <a data-uk-tooltip title="Generate Sales Order"
                                href="admin/generate_so.php?order_no=<?php echo sha1($row["order_no"]) ?>"
                                target="_blank"><img src="images/so.png" style="width:30px;"></a>
                            <?php
                             }
                              ?>
                            <?php
                              if (($row["delivered_to_logistic_by"] != "") & ($_SESSION["isLogin"] == 1) & (hasRightGroupXOR($_SESSION["UserName"], "OrderStatusEdit|OrderStatusView"))) {
                              ?>
                            <a data-uk-tooltip title="Generate Delivery Order"
                                href="admin/generate_do.php?order_no=<?php echo sha1($row["order_no"]) ?>"
                                target="_blank"><img src="images/do.png" style="width:30px;"></a>
                            <?php
                              }
                              ?>
                            <?php
							  $ordered_on = $row["ordered_on"];
							  $after_14_ex = date('Y-m-d', strtotime('+14 day', strtotime($ordered_on)));
								if (date('Y-m-d') <= $after_14_ex){
								//if (($row["delivered_to_logistic_by"] == "") & ($_SESSION["isLogin"] == 1)){
                             if (isDelivered($row["order_no"])) {							 
								 
                              ?>
                            <a data-uk-tooltip title="Report Defective"
                                href="index.php?p=order_status&sOrderNo=<?php echo $row['order_no'] ?>"><img
                                    src="images/def.png" style="width:30px;"></a>
                            <?php
							 }
							 }
                              ?>
                        </td>
                    </tr>

                    <?php
             }
           } else {
             ?>
                    <tr>
                        <td colspan="4">No Record Found</td>
                    </tr>
                    <?php
           }
           ?>

                </table>


            </div>
        </div>
    </div>
    <div class="clear"></div>
</div>

<div class="row">
    <?php
if (hasRightGroupXOR($_SESSION["UserName"], "SalesEdit|SalesView")) {
?>
    <div class="col-md-12">
        <div class="header-top-part">
            <?php
           if (hasRightGroupXOR($_SESSION["UserName"], "SalesView|SalesEdit")) {
               ?>
            <h2 class="d-inline-block text-main-color"><b>Sales</b>&nbsp;</h2>
            <div class="bordered-main d-inline-flex justify-content-center align-items-center"><a
                    href="index.php?p=sales"><i class="fa fa-external-link"></i></a></div>
            <?php
           } else {
               ?>
            <h3><b>Sales</b><i class="fa fa-external-link"></i></h3>
            <?php
           }
           ?>
        </div>
        <div class="chartmain header-bottom-part">

            <canvas id="myChartattach" style="position: relative; height:80vh; width:80vw"></canvas>
        </div>
    </div>
    <div class="clear"></div>
    <?php
}
?>
</div>
<?php
}
?>

<!-------------------------------------------- Franchisee Dashboard ----------------------------------------------------->

<?php
// echo $_SESSION["UserType"];
if (($_SESSION["UserType"]=="A") || ($_SESSION["UserType"]=="O")) {
   if (hasRightGroupXOR($_SESSION["UserName"], "DashboardView")) {
       
?>
<div class="row">
    <?php

if (hasRightGroupXOR($_SESSION["UserName"], "SalesEdit|SalesView")) {
   $sql="SELECT sum(amount) as total from collection where centre_code='".$_SESSION["CentreCode"]."' and
   month(collection_date_time)='$currentMonth' and void=0";
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);
   $total_sales=$row["total"];

?>

    <div class="col-sm-12 col-md mobile-margin-50">
        <?php
        $sql="SELECT count(*) as count from visitor where centre_code='".$_SESSION["CentreCode"]."' and year(date_created)='$year' and month(date_created)='$currentMonth'";
        //echo $sql;
        //$sql="SELECT count(*) from visitor where centre_code='".$_SESSION["CentreCode"];
        $result=mysqli_query($connection, $sql);
        $num_row=mysqli_num_rows($result);
        $row=mysqli_fetch_assoc($result);
        ?>
        <div class="main-box-content">
            <div class="box-head">
                <p>Visitor (<?php echo $strCurrentMonth?>)</p>
            </div>
            <div class="box-content">
                <div class="row">
                    <div class="col-sm-12 col-md-5 d-flex align-items-center justify-content-center">
                        <div class="border-bg"><a href="index.php?p=visitor_qr_list"><img src="images/Visitor.png"
                                    width="80px"></a></div>
                    </div>
                    <div class="col-sm-12 col-md-7 d-flex align-items-center justify-content-center">
                        <span class="q-text"><?php echo $row["count"]?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- <div class="col-sm-12 col-md mobile-margin-50">
        <?php
     //   $todayDate = date("Y-m-d");
        //old//$sql="SELECT DISTINCT a.group_id AS group_id FROM `allocation` a RIGHT join `group` g ON g.id=a.group_id WHERE a.deleted=0 AND g.year='".$current_year."' AND g.centre_code ='".$_SESSION['CentreCode']."'";
      //  $sql="SELECT g.*, c.course_name from `group` g, course c where g.course_id=c.id and centre_code='".$_SESSION['CentreCode']."' and end_date >= '$todayDate' and g.year='$year' order by c.course_name";
      //  $result=mysqli_query($connection, $sql);
     //   if ($result) {
     //     $total_active_class=mysqli_num_rows($result);
     //   }else{
       //   $total_active_class=0;
      //  }
        ?>
        <div class="main-box-content">
            <div class="box-head background-red box-after-red">
                <p>Active Classes</p>
            </div>
            <div class="box-content">
                <div class="row">
                    <div class="col-sm-12 col-md-6 d-flex align-items-center justify-content-center">
                        <div class="border-bg box-border-red"><a href="index.php?p=allocation"><img
                                    src="images/Active Classes.png" width="80px"></a></div>
                    </div>
                    <div class="col-sm-12 col-md-6 d-flex align-items-center justify-content-center">
                        <span class="text-color-red q-text"><?php //echo $total_active_class; ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div> -->

    <!-- <div class="col-sm-12 col-md mobile-margin-50">
        <?php
        // $sql="SELECT count(*) as count from student where centre_code='".$_SESSION["CentreCode"]."' and student_status='A'  and year(start_date_at_centre) <= '$year' and extend_year >= '$year' AND deleted=0";
        // $result=mysqli_query($connection, $sql);
        // $row=mysqli_fetch_assoc($result);
        ?>
        <div class="main-box-content">
            <div class="box-head ">
                <p>Active Student</p>
            </div>
            <div class="box-content">
                <div class="row">
                    <div class="col-sm-12 col-md-6 d-flex align-items-center justify-content-center">
                        <div class="border-bg "><a href="index.php?p=student_population"><img
                                    src="images/Active Students.png" width="80px"></a></div>
                    </div>
                    <div class="col-sm-12 col-md-6 d-flex align-items-center justify-content-center">
                        <span class="q-text"><?php// echo $row["count"]?></span>
                    </div>
                </div>
            </div>
        </div>
    </div> -->

    <div class="col-sm-12 col-md mobile-margin-50">
        <?php 
   
       //$sql="select l.student_entry_level, s.level_count from (SELECT DISTINCT student_entry_level from student) l left join ( SELECT student_entry_level, count(id) level_count from (SELECT ps.student_entry_level, s.id from student s inner join programme_selection ps on ps.student_id=s.id inner join student_fee_list fl on fl.programme_selection_id = ps.id where s.centre_code='".$_SESSION["CentreCode"]."' and year(fl.programme_date) = '$year' and ((MONTH(fl.programme_date) >= MONTH(CURRENT_DATE()) and MONTH(fl.programme_date) <= MONTH(CURRENT_DATE())) or (MONTH(fl.programme_date_end) >= MONTH(CURRENT_DATE()) and MONTH(fl.programme_date_end) >= MONTH(CURRENT_DATE()))) and ps.student_entry_level != '' and s.student_status = 'A' and s.deleted='0'  group by ps.student_entry_level, s.id) ab group by student_entry_level ) s on s.student_entry_level = l.student_entry_level where l.student_entry_level !=''";
        //echo $sql;

        //CHS: SQL Fix
        $sql="SELECT student_entry_level , count(id) level_count 
        from (SELECT ps.student_entry_level, s.id 
        from student s inner join programme_selection ps on ps.student_id=s.id 
        inner join student_fee_list fl on fl.programme_selection_id = ps.id 
        inner join fee_structure f on f.id=fl.fee_id where (fl.programme_date >= '$year_start_date' and fl.programme_date <= '$year_end_date') 
        and ps.student_entry_level != '' and s.student_status = 'A' 
        and s.centre_code='".$_SESSION["CentreCode"]."' 
        and ((fl.programme_date >= '$year_start_date' and fl.programme_date <= '$year_end_date') 
        or (fl.programme_date_end >= '$year_start_date' and fl.programme_date_end <= '$year_end_date')) 
        and s.deleted='0' group by ps.student_entry_level, s.id) ab
        GROUP BY student_entry_level";

       //$sql2="SELECT count(id) level_count from student where centre_code='".$_SESSION["CentreCode"]."'  and extend_year >= '$year' and student_status = 'A' and deleted='0' and foundation_mandarin = 'yes'";

       $sql2="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s 
            inner join programme_selection ps on ps.student_id=s.id 
            inner join student_fee_list fl on fl.programme_selection_id = ps.id 
            inner join fee_structure f on f.id=fl.fee_id 
            where (fl.programme_date >= '$year_start_date' AND fl.programme_date <= '$year_end_date') 
            and ps.student_entry_level != '' and s.student_status = 'A' 
            and s.centre_code='".$_SESSION["CentreCode"]."' 
            and ((fl.programme_date >= '$year_start_date' and fl.programme_date <= '$year_end_date') 
            or (fl.programme_date_end >= '$year_start_date' and fl.programme_date_end <= '$year_end_date')) 
            and s.deleted='0'
            and fl.foundation_mandarin =1 group by ps.student_entry_level, s.id) ab";
        
	 //echo $sql2;
    // var_dump($sql);
    $result=mysqli_query($connection, $sql);
    $num_row=mysqli_num_rows($result);
    //$row=mysqli_fetch_assoc($result);

    $result2=mysqli_query($connection, $sql2);
    $num_row2=mysqli_num_rows($result2);
    $row2=mysqli_fetch_assoc($result2);
    ?>
        <div class="main-box-content">
            <div class="box-head background-red box-after-red">
                <p>Foundation</p>
            </div>
            <div class="box-content">
                <div class="row">
                    <div class="col-sm-12 col-md-5 d-flex align-items-center justify-content-center">
                        <!-- <div class="border-bg box-border-red">
                            <?php
           // if (hasRightGroupXOR($_SESSION["UserName"], "OrderStatusEdit|OrderStatusView")) {
            ?>
                            <a href="index.php?p=order_status_pg1"><i class="fa fa-edit"></i></a>
                            <?php
           // } else {
            ?>
                            <i class="fa fa-address-card"></i>
                            <?php
            // }
            ?></div> -->
                        <div class="border-bg box-border-red"><a href="index.php?p=rpt_centre_subject_enrl"><img
                                    src="images/edit1.png" width="80px"></a>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-7 d-flex  drs_dsa fwbold">
                        <?php 
             while ($row=mysqli_fetch_assoc($result)) {
                ?>
                        <span class="gd_sb">
                        <span
                            class="text-color-red q-text_w1"><?php echo $row["student_entry_level"] ?></span><span><?php echo (empty($row["level_count"]) ? "0" : $row["level_count"]); ?></span>
                            </span>
                        <?php
                     }
            
            ?>
                        <span class="gd_sb">
                        <span class="text-color-red q-text_w1"><?php echo "F.Mand " ?></span><span>  <?php echo $row2["level_count"]; ?></span></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-md mobile-margin-50">
        <?php 
    // if (strtoupper($_SESSION["UserName"])=="SUPER") {
        //$sql="SELECT count(id) level_count from( SELECT s.id from  student s, programme_selection ps, student_fee_list fl, fee_structure f  where s.id=ps.student_id and ps.id = fl.programme_selection_id and f.id=fl.fee_id and s.centre_code='".$_SESSION["CentreCode"]."' and year(fl.programme_date) = '$year' and ((MONTH(fl.programme_date) >= MONTH(CURRENT_DATE()) and MONTH(fl.programme_date) <= MONTH(CURRENT_DATE())) or (MONTH(fl.programme_date_end) >= MONTH(CURRENT_DATE()) and MONTH(fl.programme_date_end) >= MONTH(CURRENT_DATE()))) and s.student_status = 'A' and s.deleted='0' and fl.foundation_int_english=1 group by s.id ) ab";
        //echo $sql;
        //$sql="SELECT count(id) level_count from student where centre_code='".$_SESSION["CentreCode"]."' and year(start_date_at_centre) <= '$year' and extend_year >= '$year' and student_status = 'A' and deleted='0' and foundation_int_english = '1'";
        $sql="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s 
        inner join programme_selection ps on ps.student_id=s.id 
        inner join student_fee_list fl on fl.programme_selection_id = ps.id 
        inner join fee_structure f on f.id=fl.fee_id 
        where (fl.programme_date >= '$year_start_date' AND fl.programme_date <= '$year_end_date') 
        and ps.student_entry_level != '' and s.student_status = 'A' 
        and s.centre_code='".$_SESSION["CentreCode"]."' 
        and ((fl.programme_date >= '$year_start_date' and fl.programme_date <= '$year_end_date') 
        or (fl.programme_date_end >= '$year_start_date' and fl.programme_date_end <= '$year_end_date')) 
        and s.deleted='0'
        and fl.foundation_int_english=1 
        group by ps.student_entry_level, s.id) ab";

        $sql2="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s 
        inner join programme_selection ps on ps.student_id=s.id 
        inner join student_fee_list fl on fl.programme_selection_id = ps.id 
        inner join fee_structure f on f.id=fl.fee_id 
        where (fl.programme_date >= '$year_start_date' AND fl.programme_date <= '$year_end_date') 
        and ps.student_entry_level != '' and s.student_status = 'A' 
        and s.centre_code='".$_SESSION["CentreCode"]."' 
        and ((fl.programme_date >= '$year_start_date' and fl.programme_date <= '$year_end_date') 
        or (fl.programme_date_end >= '$year_start_date' and fl.programme_date_end <= '$year_end_date')) 
        and s.deleted='0'
        and fl.foundation_iq_math=1
        group by ps.student_entry_level, s.id) ab";

        $sql3="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s 
        inner join programme_selection ps on ps.student_id=s.id 
        inner join student_fee_list fl on fl.programme_selection_id = ps.id 
        inner join fee_structure f on f.id=fl.fee_id 
        where (fl.programme_date >= '$year_start_date' AND fl.programme_date <= '$year_end_date') 
        and ps.student_entry_level != '' and s.student_status = 'A' 
        and s.centre_code='".$_SESSION["CentreCode"]."' 
        and ((fl.programme_date >= '$year_start_date' and fl.programme_date <= '$year_end_date') 
        or (fl.programme_date_end >= '$year_start_date' and fl.programme_date_end <= '$year_end_date')) 
        and s.deleted='0'
        and fl.foundation_int_mandarin=1
        group by ps.student_entry_level, s.id) ab";

        $sql4="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s 
        inner join programme_selection ps on ps.student_id=s.id 
        inner join student_fee_list fl on fl.programme_selection_id = ps.id 
        inner join fee_structure f on f.id=fl.fee_id 
        where (fl.programme_date >= '$year_start_date' AND fl.programme_date <= '$year_end_date') 
        and ps.student_entry_level != '' and s.student_status = 'A' 
        and s.centre_code='".$_SESSION["CentreCode"]."' 
        and ((fl.programme_date >= '$year_start_date' and fl.programme_date <= '$year_end_date') 
        or (fl.programme_date_end >= '$year_start_date' and fl.programme_date_end <= '$year_end_date')) 
        and s.deleted='0'
        and fl.foundation_int_art=1
        group by ps.student_entry_level, s.id) ab";

        $sql5="SELECT count(id) level_count from (SELECT ps.student_entry_level, s.id from student s 
        inner join programme_selection ps on ps.student_id=s.id 
        inner join student_fee_list fl on fl.programme_selection_id = ps.id 
        inner join fee_structure f on f.id=fl.fee_id 
        where (fl.programme_date >= '$year_start_date' AND fl.programme_date <= '$year_end_date') 
        and ps.student_entry_level != '' and s.student_status = 'A' 
        and s.centre_code='".$_SESSION["CentreCode"]."' 
        and ((fl.programme_date >= '$year_start_date' and fl.programme_date <= '$year_end_date') 
        or (fl.programme_date_end >= '$year_start_date' and fl.programme_date_end <= '$year_end_date')) 
        and s.deleted='0'
        and fl.pendidikan_islam=1
        group by ps.student_entry_level, s.id) ab";

        //$sql2="SELECT count(id) level_count from( SELECT s.id from  student s, programme_selection ps, student_fee_list fl, fee_structure f  where s.id=ps.student_id and ps.id = fl.programme_selection_id and f.id=fl.fee_id and s.centre_code='".$_SESSION["CentreCode"]."' and year(fl.programme_date) = '$year' and ((MONTH(fl.programme_date) >= MONTH(CURRENT_DATE()) and MONTH(fl.programme_date) <= MONTH(CURRENT_DATE())) or (MONTH(fl.programme_date_end) >= MONTH(CURRENT_DATE()) and MONTH(fl.programme_date_end) >= MONTH(CURRENT_DATE()))) and s.student_status = 'A' and s.deleted='0' and fl.foundation_iq_math=1 group by s.id ) ab";
        //$sql2="SELECT count(id) level_count from student where centre_code='".$_SESSION["CentreCode"]."' and year(start_date_at_centre) <= '$year' and extend_year >= '$year' and student_status = 'A' and deleted='0' and foundation_iq_math = '1'";
        
        
        //$sql3="SELECT count(id) level_count from( SELECT s.id from  student s, programme_selection ps, student_fee_list fl, fee_structure f  where s.id=ps.student_id and ps.id = fl.programme_selection_id and f.id=fl.fee_id and s.centre_code='".$_SESSION["CentreCode"]."' and year(fl.programme_date) = '$year' and ((MONTH(fl.programme_date) >= MONTH(CURRENT_DATE()) and MONTH(fl.programme_date) <= MONTH(CURRENT_DATE())) or (MONTH(fl.programme_date_end) >= MONTH(CURRENT_DATE()) and MONTH(fl.programme_date_end) >= MONTH(CURRENT_DATE()))) and s.student_status = 'A' and s.deleted='0' and fl.foundation_int_mandarin=1 group by s.id ) ab";
        //$sql3="SELECT count(id) level_count from student where centre_code='".$_SESSION["CentreCode"]."' and year(start_date_at_centre) <= '$year' and extend_year >= '$year' and student_status = 'A' and deleted='0' and foundation_int_mandarin = '1'";
        //$sql4="SELECT count(id) level_count from( SELECT s.id from  student s, programme_selection ps, student_fee_list fl, fee_structure f  where s.id=ps.student_id and ps.id = fl.programme_selection_id and f.id=fl.fee_id and s.centre_code='".$_SESSION["CentreCode"]."' and year(fl.programme_date) = '$year' and ((MONTH(fl.programme_date) >= MONTH(CURRENT_DATE()) and MONTH(fl.programme_date) <= MONTH(CURRENT_DATE())) or (MONTH(fl.programme_date_end) >= MONTH(CURRENT_DATE()) and MONTH(fl.programme_date_end) >= MONTH(CURRENT_DATE()))) and s.student_status = 'A' and s.deleted='0' and fl.foundation_int_art=1 group by s.id ) ab";
        //$sql4="SELECT count(id) level_count from student where centre_code='".$_SESSION["CentreCode"]."' and year(start_date_at_centre) <= '$year' and extend_year >= '$year' and student_status = 'A' and deleted='0' and foundation_int_art = '1'";
        //$sql5="SELECT count(id) level_count from( SELECT s.id from  student s, programme_selection ps, student_fee_list fl, fee_structure f  where s.id=ps.student_id and ps.id = fl.programme_selection_id and f.id=fl.fee_id and s.centre_code='".$_SESSION["CentreCode"]."' and year(fl.programme_date) = '$year' and ((MONTH(fl.programme_date) >= MONTH(CURRENT_DATE()) and MONTH(fl.programme_date) <= MONTH(CURRENT_DATE())) or (MONTH(fl.programme_date_end) >= MONTH(CURRENT_DATE()) and MONTH(fl.programme_date_end) >= MONTH(CURRENT_DATE()))) and s.student_status = 'A' and s.deleted='0' and fl.pendidikan_islam=1 group by s.id ) ab";
        //echo $sql5;
        //$sql5="SELECT count(id) level_count from student where centre_code='".$_SESSION["CentreCode"]."' and year(start_date_at_centre) <= '$year' and extend_year >= '$year' and student_status = 'A' and deleted='0' and pendidikan_islam = '1'";
       //$sql="SELECT * from student where student_status = 'A' and year(start_date_at_centre) <= '$year' and extend_year >= '$year' and deleted='0' ";

    // }
    //  else {
    //   $sql="SELECT * from student where student_status = 'A' and year(start_date_at_centre) <= '$year' and extend_year >= '$year' and deleted='0' ";
    // }
	// echo $sql;
    // var_dump($sql);
    $result=mysqli_query($connection, $sql);
    $num_row=mysqli_num_rows($result);
    $row=mysqli_fetch_assoc($result);

    $result2=mysqli_query($connection, $sql2);
    $num_row2=mysqli_num_rows($result2);
    $row2=mysqli_fetch_assoc($result2);

    $result3=mysqli_query($connection, $sql3);
    $num_row3=mysqli_num_rows($result3);
    $row3=mysqli_fetch_assoc($result3);

    $result4=mysqli_query($connection, $sql4);
    $num_row4=mysqli_num_rows($result4);
    $row4=mysqli_fetch_assoc($result4);

    $result5=mysqli_query($connection, $sql5);
    $num_row5=mysqli_num_rows($result5);
    $row5=mysqli_fetch_assoc($result5);

    $intEnglishCount = 0;
    $IQMathCount = 0;
    $MandarinCount = 0;
    $IntArtCount = 0;
    $PIslamCount = 0;

    if($row["level_count"] > 0) $intEnglishCount = $row["level_count"];
    if($row2["level_count"] > 0) $IQMathCount = $row2["level_count"];
    if($row3["level_count"] > 0) $MandarinCount = $row3["level_count"];
    if($row4["level_count"] > 0) $IntArtCount = $row4["level_count"];
    if($row5["level_count"] > 0) $PIslamCount = $row5["level_count"];
    ?>
        <div class="main-box-content">
            <div class="box-head">
                <p>Enhanced Foundation</p>
            </div>
            <div class="box-content">
                <div class="row">
                    <div class="col-sm-12 col-md-5 d-flex align-items-center justify-content-center">
                        <div class="border-bg">
                            <?php
            if (hasRightGroupXOR($_SESSION["UserName"], "StudentEdit|StudentView")) {
            ?>
                            <a href="index.php?p=rpt_centre_subject_enrl"><img src="images/Active Students.png"
                                    width="80px"></a>
                            <?php
            } else {
            ?>
                            <a href="index.php?p=rpt_centre_subject_enrl"><img src="images/Active Students.png"
                                    width="80px"></a>
                            <?php
            }
            ?></div>
                    </div>
                    <div class="col-sm-12 col-md-7 d-flex drs_dsa fwbold ">
                    <span class="gd_sb2"> 
                        <span class="text-color-red q-text_w2">Int. Eng </span><span><?php echo $intEnglishCount; ?></span>
                    </span>
                    <span class="gd_sb2">    
                        <span class="text-color-red q-text_w2">IQ Math </span><span><?php echo $IQMathCount; ?></span>
                    </span>
                    <span class="gd_sb2">    
                        <span class="text-color-red q-text_w2">Mandarin </span><span><?php echo $MandarinCount; ?></span>
                    </span>
                    <span class="gd_sb2">    
                        <span class="text-color-red q-text_w2">Int. Art </span><span><?php echo $IntArtCount; ?></span>
                    </span>
                    <span class="gd_sb2">    
                        <span class="text-color-red q-text_w2">P. Islam </span><span><?php echo $PIslamCount; ?></span>
                    </span>
                    </div>
                </div>
            </div>
        </div>
    </div>














    <div class="col-sm-12 col-md mobile-margin-50">
        <?php
        $today=date("Y-m-d");
        $strToday=date("d/m/Y");

        //CHS: This is the part which is lagging the home page
        $sql="SELECT DISTINCT o.product_code, p.product_name, p.sub_category from `order` o, product p Where o.centre_code='".$_SESSION["CentreCode"]."' AND o.product_code=p.product_code and cancelled_by=''";

        if ($year == $current_year) {
          $sql .=" and delivered_to_logistic_on<='$today 23:59:59' and delivered_to_logistic_by<>''";
        } else {
          $backDate = $year_end_date;
          $sql .=" and delivered_to_logistic_on<='$backDate 23:59:59' and delivered_to_logistic_by<>''";
        }

        $result=mysqli_query($connection, $sql);
        $count=0;
        while ($row=mysqli_fetch_assoc($result)) {
            $bal=calcBal($_SESSION["CentreCode"], $row["product_code"], $today);
            if ($bal<=5) {
                $count++;
            }
        }
        ?>
        <div class="main-box-content">
            <div class="box-head background-red box-after-red">
                <p>Low in Stock</p>
            </div>
            <div class="box-content">
                <div class="row">
                    <div class="col-sm-12 col-md-5 d-flex align-items-center justify-content-center">
                        <div class="border-bg box-border-red"><a href="index.php?p=stock_bal"><img
                                    src="images/Low in Stock.png" width="80px"></a></div>
                    </div>
                    <div class="col-sm-12 col-md-7 d-flex align-items-center justify-content-center">
                        <span class="text-color-red q-text"><?php echo $count?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <?php
}
?>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="header-top-part">
            <h3 style="color: white !important" class="sales-title">Latest Order</h3>
        </div>
        <div class="orderstatusmain header-bottom-part" style="padding: 20px 0;">
            <div class=" table-responsive-md">
                <table style="margin: 2rem 0px; width: 100%; margin: 0 auto;" class="">

                    <tr class="enlist-parent">
                        <td class="enlist">Order No.
                        </td>
                        <td class="enlist">Order date
                        </td>
                        <td class="enlist">Status
                        </td>
                        <td class="enlist">Action
                        </td>
                    </tr>
                    <?php
                            if ($currentYear == $year) {
                             if ($_SESSION["UserType"]=="S" || $_SESSION["UserType"]=="H
                             " || $_SESSION["UserType"]=="C" || $_SESSION["UserType"]=="R" || $_SESSION["UserType"]=="CM" || $_SESSION["UserType"]=="T") {
                                 if (strtoupper($_SESSION["UserName"])=="SUPER") {
                                     // $sql="SELECT distinct order_no, ordered_on from `order` where month(ordered_on)='$currentMonth' order by ordered_on desc limit 10"; 
									 $sql="SELECT distinct order_no, ordered_on from `order` group by order_no order by ordered_on desc limit 10";
                                 } else {

                                 }
                             } else {
                                 //$sql="SELECT distinct order_no, ordered_on, delivered_to_logistic_by from `order` where centre_code='".$_SESSION["CentreCode"]."' and month(ordered_on)='$currentMonth' order by ordered_on desc limit 10";
								 $sql="SELECT distinct order_no, ordered_on, delivered_to_logistic_by from `order` where centre_code='".$_SESSION["CentreCode"]."' group by order_no order by ordered_on desc limit 10";
                             }
                             //echo  $sql;
                             $result=mysqli_query($connection, $sql);
                             $num_row=mysqli_num_rows($result);
                            } else {
                              $num_row=0;
                            }

                           if ($num_row>0) {
                               while ($row=mysqli_fetch_assoc($result)) {
                                   ?>

                    <tr>
                        <td class="">
                            <a
                                href="../index.php?p=order_status&sOrderNo=<?php echo $row['order_no']?>"><?php echo $row["order_no"]?></a>
                        </td>
                        <td class="">
                            <?php echo $row["ordered_on"]?>
                        </td>

                        <td class="">
                            <?php echo getStatus($row["order_no"])?>
                        </td>
                        <td style="white-space: nowrap;">
                            <?php
                              if (($_SESSION["isLogin"] == 1) & (hasRightGroupXOR($_SESSION["UserName"], "OrderStatusEdit|OrderStatusView"))) {
                              ?>
                            <a data-uk-tooltip title="Generate Sales Order"
                                href="admin/generate_so.php?order_no=<?php echo sha1($row["order_no"]) ?>"
                                target="_blank"><img src="images/so.png" style="width:30px;"></a>
                            <?php
                             }
                              ?>
                            <?php
                              if (($row["delivered_to_logistic_by"] != "") & ($_SESSION["isLogin"] == 1) & (hasRightGroupXOR($_SESSION["UserName"], "OrderStatusEdit|OrderStatusView"))) {
                              ?>
                            <a data-uk-tooltip title="Generate Delivery Order"
                                href="admin/generate_do.php?order_no=<?php echo sha1($row["order_no"]) ?>"
                                target="_blank"><img src="images/do.png" style="width:30px;"></a>
                            <?php
                              }
                              ?>
                            <?php
							  $ordered_on = $row["ordered_on"];
							  $after_14_ex = date('Y-m-d', strtotime('+14 day', strtotime($ordered_on)));
								if (date('Y-m-d') <= $after_14_ex){
								//if (($row["delivered_to_logistic_by"] == "") & ($_SESSION["isLogin"] == 1)){
                             if (isDelivered($row["order_no"])) {							 
								 
                              ?>
                            <a data-uk-tooltip title="Report Defective"
                                href="index.php?p=order_status&sOrderNo=<?php echo $row['order_no'] ?>"><img
                                    src="images/def.png" style="width:30px;"></a>
                            <?php
							 }
							 }
                              ?>
                        </td>
                    </tr>

                    <?php
                               }
                           } else {
                               ?>
                    <tr>
                        <td colspan="4">No Record Found</td>
                    </tr>
                    <?php
                           }
                           ?>

                </table>


            </div>
        </div>
    </div>
    <div class="clear"></div>
</div>

<div class="row">
    <?php
if (hasRightGroupXOR($_SESSION["UserName"], "SalesView|SalesEdit")) {
?>
    <div class="col-md-12">
        <div class="header-top-part">
            <?php
           if (hasRightGroupXOR($_SESSION["UserName"], "SalesView|SalesEdit")) {
               ?>
            <h3 class="sales-title"><a style="color: white !important" href="index.php?p=sales">Sales</a></h3>
            <?php
           } else {
               ?>
            <h3 style="color: white !important" class="sales-title">Sales</h3>
            <?php
           }
           ?>
        </div>
        <div class="chartmain header-bottom-part">
            <hr>

            <canvas id="myChartattach" style="position: relative; height:80vh; width:80vw"></canvas>
        </div>
    </div>
    <div class="clear"></div>
    <?php
}
?>
</div>
<div id="student-dialog"></div>
<?php
   }
}
?>



<?php
function getStatus($order_no) {
   global $connection;

   $sql="SELECT * from `order` where order_no='$order_no' or id='$order_no'";

   $result=mysqli_query($connection, $sql);
   if (strtoupper($_SESSION["UserName"])=="SUPER") {
	   if ($result) {
      $row=mysqli_fetch_assoc($result);

      if ($row["cancelled_by"]!="") {
         return "Cancelled";
      } else {
         if ($row["delivered_to_logistic_by"]!="") {
            if ($row["finance_payment_paid_by"]!="") {
               return "Delivered (Paid)";
            } else {
               return "Delivered (Paid)";
            }

         } else {
            if ($row["packed_by"]==1) {
               if ($row["finance_payment_paid_by"]!="") {
                  return "Packing";
               } else {
                  return "Packing";
               }
            } else {
               if ($row["finance_approved_by"]!="") {
                  if ($row["finance_payment_paid_by"]!="") {
                     return "Ready to collect";
                  } else {
                     return "Ready to collect";
                  }
               } else {
                  if ($row["logistic_approved_by"]!="") {
                     return "Packing";
                  } else {
                     if ($row["acknowledged_by"]!="") {
                        return "Acknowledged";
                     } else {
                        return "Pending";
                     }
                  }
               }
            }
         }
      }
   }
	}else{   
   if ($result) {
      $row=mysqli_fetch_assoc($result);

      if ($row["cancelled_by"]!="") {
         return "Cancelled";
      } else {
         if ($row["delivered_to_logistic_by"]!="") {
            if ($row["finance_payment_paid_by"]!="") {
               return "Delivered";
            } else {
               return "Delivered";
            }

         } else {
            if ($row["packed_by"]==1) {
               if ($row["finance_payment_paid_by"]!="") {
                  return "Packing";
               } else {
                  return "Packing";
               }
            } else {
               if ($row["finance_approved_by"]!="") {
                  if ($row["finance_payment_paid_by"]!="") {
                     return "Ready to collect";
                  } else {
                     return "Ready to collect";
                  }
               } else {
                  if ($row["logistic_approved_by"]!="") {
                     return "Packing";
                  } else {
                     if ($row["acknowledged_by"]!="") {
                        return "Acknowledged";
                     } else {
                        return "Pending";
                     }
                  }
               }
            }
         }
      }
   }
}
}
function orderPaid($order_no) {
   global $connection;

   $sql="SELECT * from `order` where order_no='$order_no' or id='$order_no'";

   $result=mysqli_query($connection, $sql);
   if ($result) {
      $row=mysqli_fetch_assoc($result);

      if ($row["finance_payment_paid_by"]!="") {
         return true;
      } else {
         return false;
      }
   }
}

function get6MonthsSalesLabel() {
   $month1=date("M Y", strtotime("-5 months"));
   $month2=date("M Y", strtotime("-4 months"));
   $month3=date("M Y", strtotime("-3 months"));
   $month4=date("M Y", strtotime("-2 months"));
   $month5=date("M Y", strtotime("-1 months"));
   $month6=date("M Y", strtotime("-0 months"));

   return "['$month1', '$month2', '$month3', '$month4', '$month5', '$month6']";
}

function userIsMaster($centre_code) {
   global $connection;

   $sql="SELECT * from master where master_code='$centre_code'";
   $result=mysqli_query($connection, $sql);
   $num_row=mysqli_num_rows($result);
   if ($num_row>0) {
      return true;
   } else {
      return false;
   }
}

function getTotalSalesByMonth($centre_code, $month) {
   global $connection;

   if (userIsMaster($centre_code)) {
      $sql="SELECT sum(c.amount) as total from collection c, centre cen where c.centre_code=cen.centre_code
      and cen.upline='$centre_code' and month(c.collection_date_time)='$month' and void='0'";
   } else {
      if (strtoupper($_SESSION["UserName"])=="SUPER") {
         $sql="SELECT sum(amount) as total from collection where month(collection_date_time)='$month' and void='0'";
      } else {
         $sql="SELECT sum(amount) as total from collection where month(collection_date_time)='$month' and centre_code='$centre_code'
         and void='0'";
      } 
   }
  //  echo $sql; die;
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);
   if ($row["total"]!="") {
      return $row["total"];
   } else {
      return 0;
   }
}


function get6MonthsSalesQty($centre_code, &$minValue, &$maxValue) {
   $month1=date("m", strtotime("-5 months"));
   $month2=date("m", strtotime("-4 months"));
   $month3=date("m", strtotime("-3 months"));
   $month4=date("m", strtotime("-2 months"));
   $month5=date("m", strtotime("-1 months"));
   $month6=date("m", strtotime("-0 months"));

   $sales1=getTotalSalesByMonth($centre_code, $month1);
   $sales2=getTotalSalesByMonth($centre_code, $month2);
   $sales3=getTotalSalesByMonth($centre_code, $month3);
   $sales4=getTotalSalesByMonth($centre_code, $month4);
   $sales5=getTotalSalesByMonth($centre_code, $month5);
   $sales6=getTotalSalesByMonth($centre_code, $month6);

   $maxValue=0;
   if ($sales1>$maxValue) {
      $maxValue=$sales1;
   }

   if ($sales2>$maxValue) {
      $maxValue=$sales2;
   }

   if ($sales3>$maxValue) {
      $maxValue=$sales3;
   }

   if ($sales4>$maxValue) {
      $maxValue=$sales4;
   }

   if ($sales5>$maxValue) {
      $maxValue=$sales5;
   }

   if ($sales6>$maxValue) {
      $maxValue=$sales6;
   }

   $minValue=$maxValue;
   if ($sales1>0) {
      if ($minValue>$sales1) {
         $minValue=$sales1;
      }
   }

   if ($sales2>0) {
      if ($minValue>$sales2) {
         $minValue=$sales2;
      }
   }

   if ($sales3>0) {
      if ($minValue>$sales3) {
         $minValue=$sales3;
      }
   }

   if ($sales4>0) {
      if ($minValue>$sales4) {
         $minValue=$sales4;
      }
   }

   if ($sales5>0) {
      if ($minValue>$sales5) {
         $minValue=$sales5;
      }
   }

   if ($sales6>0) {
      if ($minValue>$sales6) {
         $minValue=$sales6;
      }
   }

   return "[$sales1, $sales2, $sales3, $sales4, $sales5, $sales6]";
}

function getMonthlySalesBySubCategory($year){
  global $connection, $year_start_date, $year_end_date;

  $addWhere = '';
  if ($_SESSION["UserType"]!="S" || $_SESSION["UserType"]=="H
  " || $_SESSION["UserType"]=="C" || $_SESSION["UserType"]=="R" || $_SESSION["UserType"]=="CM" || $_SESSION["UserType"]=="T") {
  $centre_code = mysqli_real_escape_string($connection, $_SESSION["CentreCode"]);
  $addWhere = "and centre_code = '$centre_code'";
  }

  //$sql = "SELECT hh.sub_category, SUM(hh.total) as grand_total, hh.order_date FROM (SELECT p.sub_category, o.total, DATE_FORMAT(CAST(ordered_on AS DATE), '%Y-%m') AS order_date FROM `order` o LEFT JOIN product p ON o.product_code=p.product_code";
  //$sql .= " WHERE o.centre_code = '". $centre_code . "' AND DATE_FORMAT(CAST(ordered_on AS DATE), '%Y-%m') between '".$year."-01' AND '".$year."-10') as hh GROUP BY hh.sub_category, hh.order_date";
  //$sql = "select cd.code, sum(cl.amount) as total, DATE_FORMAT(collection_date_time, '%Y-%m') as collection_date from collection cl left join course co on co.id=cl.course_id left join (select code from codes where parent in (select code from codes where parent='' and module='CATEGORY') group by code) cd on co.course_name like CONCAT('% ',cd.code,' %') where  collection_type not in ('addon-product') AND cl.void=0 AND DATE_FORMAT(collection_date_time, '%Y-%m') between '$year-01' AND '$year-12' $addWhere group by cd.code, DATE_FORMAT(collection_date_time, '%Y-%m')";

  
    $sql = "SELECT 'School Fees' as product_code, sum(amount) as total, DATE_FORMAT(collection_date_time, '%Y-%m') as collection_date from collection where product_code in ('Multimedia Fees', 'School Fees', 'Facility Fees') AND DATE_FORMAT(collection_date_time, '%Y-%m') between '".date('Y-m',strtotime($year_start_date))."' AND '".date('Y-m',strtotime($year_end_date))."' $addWhere group by DATE_FORMAT(collection_date_time, '%Y-%m')";
    //  echo $sql;
    $sql .= " Union all ";
    $sql .= "SELECT 'Material Fees' as product_code, sum(amount) as total, DATE_FORMAT(collection_date_time, '%Y-%m') as collection_date from collection where product_code in ('Integrated Module', 'Link', 'Mandarin Modules') AND DATE_FORMAT(collection_date_time, '%Y-%m') between '".date('Y-m',strtotime($year_start_date))."' AND '".date('Y-m',strtotime($year_end_date))."' $addWhere group by DATE_FORMAT(collection_date_time, '%Y-%m')";
    $sql .= " Union all ";
    $sql .= "SELECT 'Enhanced Foundation Fees' as product_code, sum(amount) as total, DATE_FORMAT(collection_date_time, '%Y-%m') as collection_date from collection where product_code in ('International English', 'IQ Math', 'Mandarin', 'International Art', 'Pendidikan Islam') AND DATE_FORMAT(collection_date_time, '%Y-%m') between '".date('Y-m',strtotime($year_start_date))."' AND '".date('Y-m',strtotime($year_end_date))."' $addWhere group by DATE_FORMAT(collection_date_time, '%Y-%m')";
  $result = mysqli_query($connection, $sql);

  $o = array();
  if ($result){
    while($row=mysqli_fetch_assoc($result)){
      $o[$row['product_code']][$row['collection_date']] = $row['total'];
    }
  }

  return $o;
}

function get1YearLabel($year){

    global $year_start_date, $year_end_date;
    $period = getMonthList($year_start_date, $year_end_date);
    $months = array();

    foreach ($period as $dt) {
        $months[$dt->format("M Y")] = $dt->format("Y-m");
    }

  return $months;
}

function getChartSubCategories(){
  global $connection;

  $sql = "SELECT distinct sub_sub_category FROM product p";
  $result=mysqli_query($connection, $sql);
  $sub_categories = array();
  if ($result) {
    while($row=mysqli_fetch_assoc($result)){
      $sub_categories[] = $row['sub_sub_category'];
    }
  }

  return $sub_categories;
}

function getChartDataMonthlySales($year){
  global $connection;

  //$sql = "SELECT distinct sub_sub_category FROM product p";
  //$result=mysqli_query($connection, $sql);
  $sub_categories = array(
    "School Fees",
    "Material Fees",
    "Enhanced Foundation Fees"
  );
//   if ($result) {
//     while($row=mysqli_fetch_assoc($result)){
//       $sub_categories[] = $row['sub_sub_category'];
//     }
//   }

  $months = get1YearLabel($year);
  $sales = getMonthlySalesBySubCategory($year);

  $chart_data = array(
    'labels' => array_keys($months),
    'datasets' => array()
  );

  $colors = array(
    "#d7ccc8",
    "#ffccbc",
    "#ffecb3",
    "#81c784",
    "#64b5f6",
    "#e57373"
  );

  foreach($sub_categories as $sub_sub_category){
    $data = array();
    foreach($months as $month){
      if (isset($sales[$sub_sub_category][$month])) {
        $data[] = $sales[$sub_sub_category][$month];
      }else{
        $data[] = "0.00";
      }
    }

    $chart_data['datasets'][] = array(
      'label' => $sub_sub_category,
      'backgroundColor' => array_pop($colors),
      'data' => $data
    );
  }

  return json_encode($chart_data);
}
?>

<script src="lib/uikit/js/jquery.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
jQuery(document).ready(function() {

    if ($("#myChartattach").length) {
        var activityChartData = <?php echo getChartDataMonthlySales($_SESSION['Year']); ?> ;
         console.log(activityChartData);
        var activityChartCanvas = $("#myChartattach").get(0).getContext("2d");

        var activityChart = new Chart(activityChartCanvas, {
            type: 'bar',
            data: activityChartData,
            options: {
                tooltips: {
                    mode: 'index',
                    intersect: false
                },
                responsive: true,
                scales: {
                    xAxes: [{
                        stacked: true,
                    }],
                    yAxes: [{
                        stacked: true
                    }]
                }
            }
        });
    }
});
</script>

<?php 
$totalAmt = 0;
$sql = "SELECT count(id) total
			FROM (
SELECT DISTINCT ps.student_entry_level, s.id
				FROM student s
				INNER JOIN programme_selection ps ON ps.student_id = s.id
				INNER JOIN student_fee_list fl ON fl.programme_selection_id = ps.id
				INNER JOIN fee_structure f ON f.id = fl.fee_id
				WHERE (fl.programme_date >= '2023-03-01' AND fl.programme_date <= '2024-02-29') 
				AND ps.student_entry_level != '' AND s.student_status = 'A' 
				AND s.centre_code='".$_SESSION["CentreCode"]."'
				AND ((fl.programme_date >= '2023-04-01' AND fl.programme_date <= '2023-04-30') OR (fl.programme_date_end >= '2023-04-01' 
				AND fl.programme_date_end >= '2023-04-30')) AND s.deleted = '0' 
			) ab ";
  $result=mysqli_query($connection, $sql);
  $sub_categories = array();
  if ($result) {
    while($row=mysqli_fetch_assoc($result)){
		$totalAmt = $row['total'];
    }
  }
  
if ($totalAmt < 10){
?>
<script>
$(document).ready(function(){
    $.ajax({ url: "warningError.php",
        dataType : "text",
        beforeSend : function(http) {
      },
      async: false,
      success : function(response, status, http) {
         $("#student-dialog").html(response);
		 $("#student-dialog").dialog({
               dialogClass:"no-close",
               title:"ATTENTION",
               modal:true,
               height:'auto',
               width:'60%',
            });
      },
      error : function(http, status, error) {
         UIkit.notify("Error:"+error);
      }
   });
});
</script>
<?php } ?>

<style>
.home {
    background: #ffffff33;
}

.mobile-margin-50 {
    margin-bottom: 20px;
}

.drs_dsa {
    flex-direction: column;
    justify-content: center;
}

.q-text_w1 {
    white-space: nowrap;
}

.q-text_w2 {
    white-space: nowrap;
    color: #3399ff !important;
}

.main-box-content .box-head {
    width: 100%;
}

.main-box-content .box-head p {
    font-size: 1.2rem;
}
.fwbold{
    font-weight: bold;
}
.gd_sb{
    display: flex;
    justify-content: space-between;
    width: 90px;
    color: #f97c71;
}
.gd_sb11{
    display: flex;
    justify-content: space-between;
    width: 90px;
    color: #f97c71;
    font-size: 12px;
}
.gd_sb2 {
    display: flex;
    justify-content: space-between;
    width: 90px;
    color: #3399ff;
}
.gd_sb22 {
    display: flex;
    justify-content: space-between;
    width: 90px;
    color: #3399ff;
    font-size: 12px;
}
<?php if ($totalAmt < 10){ ?>
.ui-dialog .ui-dialog-titlebar {
	background: #ef5350;
}
<?php } ?>
</style>