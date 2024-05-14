<?php
session_start();
include_once("mysql.php");

   $sql="SELECT `api_key` FROM `user` WHERE `user_name` = '".$_SESSION["UserName"]."'";
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);

   $user_api_key=$row["api_key"];
   
   //echo $user_api_key;
?>
<!--
<table class="uk-table">
   <tr class="uk-text-bold">
      <td>Student Code</td>
      <td>Name</td>
      <td>Gender</td>
      <td>Age</td>
      <td>Status</td>
   </tr>
   
</table>
-->

<style>
link:hover {
  text-decoration: underline;
}
</style>
<p style="font-size:15px;">Dear Franchisee,<br>
<b>Kindly disregard this message is you have completed your POS system submission for this current academic year</b>
<br><br>
Please note that your stock ordering function is currently locked due to pending submissions for the following items: -<br>
1. <a style="color:#1E90FF" target="_blank" href="index.php?p=fee_structure_setting"><b class="link">Creating New Fee Structures in the POS System (EDP/QF1/QF2/QF3)</b></a><br>
2. <a style="color:#1E90FF" target="_blank" href="index.php?p=student_multitransfer"><b class="link">Exporting Old Students from the old Academic year (2023-2024) to the current Academic year (2024-2025)</b></a><br>
3. <a style="color:#1E90FF" target="_blank" href="index.php?p=fee_str_allocate"><b class="link">Assigning Fee Structures for students for the new Academic Year</b></a><br>
<br>
The deadline for submission of all these items is <b>19 April 2024, Friday</b>. To unlock your ordering function, kindly complete your submissions at your earliest convenience.
<br><br>
Once done, please inform us via <b class="link"><a style="color:#1E90FF" href="http://helpdesk.q-dees.com/admin/auto-login/<?php echo $user_api_key ?>" target="_blank">
Helpdesk (Customer Service Department)</a></b>. 
<br><br>
If you require further assistance or guidance, please refer to either <a href="POS System Guide 2024-2025.pdf" target="_blank"><b class="link" style="color:#1E90FF">the POS System guide</b></a>, or contacting the POS System Hotline at <b>+60 12-393 1800</b>.
<br><br>
Thank you.
</p>
<div class="uk-text-right" >
<center>
    <a class="uk-width-1-2 uk-button uk-button-primary" onclick="$('#student-dialog').dialog('close');">Close</a>
	</center>
</div>