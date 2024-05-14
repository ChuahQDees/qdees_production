<?php 
$get_current_path_to_front = str_replace('\\', '/', realpath(dirname(__FILE__))) . '/';
$set_new_path_to_front = str_replace('\\', '/', realpath($get_current_path_to_front . '../')) . '/';
define('FCPATH', $set_new_path_to_front);

include_once("../mysql.php"); 
error_reporting(0);     
set_time_limit(0); 
$centre_code = isset($_REQUEST['centre_code']) ? $_REQUEST['centre_code'] : '';
$month = isset($_REQUEST['month']) ? $_REQUEST['month'] : '';
$year = isset($_REQUEST['year']) ? $_REQUEST['year'] : '';
$student = isset($_REQUEST['student']) ? $_REQUEST['student'] : '';
$UserName = isset($_REQUEST['UserName']) ? $_REQUEST['UserName'] : $_SESSION['UserName'];
$session_year = isset($_REQUEST['session_year']) ? $_REQUEST['session_year'] : $_SESSION['Year'];
$submited_date= date("d-m-Y_H-i"); 

exec("php ".FCPATH."admin/generate_outstanding_pdf.php '$centre_code' '$month' '$year' '$student' '$UserName' '$session_year' > ".FCPATH."cron_log/output_$submited_date.php 2>&1 & echo $!", $output);
return $output;
 
 
?>