<?php 
 $get_current_path_to_front = str_replace('\\', '/', realpath(dirname(__FILE__))) . '/';
$set_new_path_to_front = str_replace('\\', '/', realpath($get_current_path_to_front . '../')) . '/';
define('FCPATH', $set_new_path_to_front);

include_once("../mysql.php");
echo "<pre>Loading process</pre>";
error_reporting(0);
function run_background_process()
{
	$submited_date= date("d-m-Y_H-i");
  //  echo "<pre>  foreground start time = " . time() . "</pre>";
    exec("php ".FCPATH."admin/declaration_auto_submit_cron.php > ".FCPATH."cron_log/output_$submited_date.php 2>&1 & echo $!", $output);

  // exec("php ".FCPATH."admin/generate_pdf.php > ".FCPATH."cron_log/pdf_output_$submited_date.php 2>&1 & echo $!", $output);
    //echo "<pre>  foreground end time = " . time() . "</pre>";
   // file_put_contents("".FCPATH."cron_log/testprocesses_$submited_date.php","foreground end time = " . time() . "\n", FILE_APPEND);
    return $output;
}

//echo "<pre>calling run_background_process</pre>";

$output = run_background_process();

//echo "<pre>output = "; print_r($output); echo "</pre>";
//echo "<pre>end of page</pre>";
?>