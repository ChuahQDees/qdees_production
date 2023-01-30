<?php
session_start();
include_once("../mysql.php");

foreach ($_POST as $key=>$value) {
	$$key=mysqli_real_escape_string($connection, $value);//$ori_group_id, $ori_class_id, $params
}

function isDuplicate($ori_group_id, $ori_class_id, $ori_course_id, $to_group_id, $to_class_id, $to_course_id) {
   global $connection;

   $sql="SELECT student.* from allocation, student where allocation.student_id=student.id 
	and student_id in (select student_id from allocation a where group_id='$ori_group_id' and class_id='$ori_class_id' and course_id='$ori_course_id' and deleted=0
	and exists(select * from student s where s.id=a.student_id and s.deleted=0 and s.student_status='A')) 
	and group_id='$to_group_id' and class_id='$to_class_id' and course_id='$to_course_id'";
   $result=mysqli_query($connection, $sql);

   $num_row=mysqli_num_rows($result);

   if ($num_row>0) {
      return $result;
   } else {
      return false;
   }
}

if (($ori_group_id!="") & ($ori_class_id!="") & ($ori_course_id!="") & ($params!="")) {
   $to=explode("|", $params);
   $to_group_id=$to[0];
   $to_class_id=$to[1];
   $to_course_id=$to[2];

   if (($to_group_id!="") & ($to_class_id!="") & ($to_course_id!="")) {
	   $duplicate = isDuplicate($ori_group_id, $ori_class_id, $ori_course_id, $to_group_id, $to_class_id, $to_course_id);
      if (!$duplicate) {
		  $sql="UPDATE allocation a set group_id='$to_group_id', class_id='$to_class_id', course_id='$to_course_id' 
		  where group_id='$ori_group_id' and course_id='$ori_course_id' and class_id='$ori_class_id' and deleted=0 
		  and exists(select * from student s where s.id=a.student_id and s.deleted=0 and s.student_status='A')";
		  $result=mysqli_query($connection, $sql);

		  if ($result) {
			 echo "1|All student has been transfer";
		  } else {
			echo "0|Transfer failed";
		  }
      } else {
		  echo "0|Student with following code already allocated:<br> <ul>";
		  while ($row=mysqli_fetch_assoc($duplicate)) {
			  echo "<li>".$row['student_code']."</li>";
		  }
		  echo "<ul>";
         //echo "0|Student already allocated";
      }
   } else {
   	echo "0|Something is wrong, cannot proceed";
   }
} else {
	echo "0|Something is wrong";
}
?>