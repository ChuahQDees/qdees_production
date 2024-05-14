<?php
    session_start();
    
    include_once("../mysql.php");
    include_once("functions.php");
    
    error_reporting(0);

/*     $centre_code = isset($_REQUEST['centre_code']) ? $_REQUEST['centre_code'] : '';
    $month = isset($_REQUEST['month']) ? $_REQUEST['month'] : '';
    $year = isset($_REQUEST['year']) ? $_REQUEST['year'] : '';
    $student = isset($_REQUEST['student']) ? $_REQUEST['student'] : '';
    $UserName = isset($_REQUEST['UserName']) ? $_REQUEST['UserName'] : $_SESSION['UserName'];
    $session_year = isset($_REQUEST['session_year']) ? $_REQUEST['session_year'] : $_SESSION['Year'];
     */
    $centre_code =  $argv[1];
    $month = $argv[2];
    $year = $argv[3];
    $student =$argv[4];
    $UserName = $argv[5];
    $session_year = $argv[6];
    $nm = "Outstanding-Report-".time().".pdf";

    $month_year = ($month == '13') ? $month : $year.'-'.$month;

    mysqli_query($connection,"INSERT INTO `outstanding_pdf` SET `pdf_name` = '".$nm."', `centre_code` = '".$centre_code."', `year` = '".$session_year."', `month` = '".$month_year."', `student` = '".$student."'") or die(mysqli_error($connection));

    $action_id = mysqli_insert_id($connection);

    $purl = "https://starters.q-dees.com/admin/a_rptOutstanding.php?centre_code=".$centre_code."&month=".$month."&year=".$year."&student=".$student."&UserName=".$UserName."&session_year=".$session_year."&action_id=".$action_id;
					
    exec("export PATH='/usr/bin:/bin' && wkhtmltopdf --enable-local-file-access -O Landscape '$purl' /var/www/html/starters.q-dees.com/admin/outstanding_report_pdf/$nm", $output);

    mysqli_query($connection,"UPDATE `outstanding_pdf` SET `is_generated` = '1' WHERE `id` = '".$action_id."'") or die(mysqli_error($connection));

    $n_data['action_id'] = $action_id;
    $n_data['send_to'] = $centre_code;
    $n_data['send_from'] = "";
    $n_data['is_center_read'] = 0;
    $n_data['is_hq_read'] = 1;
    $n_data['type'] = "outstanding_report_pdf";
    $n_data['subject'] = "Outstanding Report PDF generated."; 

    notification($n_data);
?>
