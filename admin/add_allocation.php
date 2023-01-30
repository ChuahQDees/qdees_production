<?php
session_start();
include_once("../mysql.php");
$year=$_SESSION['Year'];
$centre_code=$_SESSION["CentreCode"];

foreach ($_POST as $key=>$value) {
   $$key=mysqli_real_escape_string($connection, $value);//id, group_id, the_class
}

function getCourseID($course_name) {
   global $connection;

   $sql="SELECT * from course where course_name like '$course_name%'";
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);
   return $row["id"];
}

function getCourseFees($id) {
   global $connection;

   $sql="SELECT * from course where id like '$id'";
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);
   return $row["fees"];
}

function isDuplicate($student_id, $course_id, $class_id, $year, $group_id) {
   global $connection;

   $sql="SELECT * from allocation where student_id='$student_id' and course_id='$course_id' and class_id='$class_id' and group_id='$group_id'
   and `year`='$year' and deleted=0";
   $result=mysqli_query($connection, $sql);

   $num_row=mysqli_num_rows($result);

   if ($num_row>0) {
      return true;
   } else {
      return false;
   }
}

if ($_SESSION["isLogin"]==1) {
   $now=date("Y-m-d H:i:s");
   if (($id!="") & ($group_id!="") & ($the_class!="")) {
      $id = explode(',', $id);
      $course_id = explode(',', $course_id);
      $the_class = explode(',', $the_class);
      $group_id = explode(',', $group_id);
      $failedAllocation = $allocated = $successAllocated = 0;
      for ($i=0; $i < count($id) ; $i++) { 
         if (!isDuplicate($id[$i], $course_id[$i], $the_class[$i], $year, $group_id[$i])) {
            $courseFees = getCourseFees($course_id[$i]);
            $sql="INSERT into allocation (`year`, group_id, student_id, course_id, class_id, fees, allocated_date_time)
            values ('$year', '".$group_id[$i]."', '".$id[$i]."', '".$course_id[$i]."', '".$the_class[$i]."', '$courseFees', '$now')";
            $result=mysqli_query($connection, $sql);
            if ($result) {
               $successAllocated = 1;
            } else {
               $failedAllocation = 1;
            }
         } else {
            $allocated = 1;
         }
      }

      if ($successAllocated == 1 && $failedAllocation == 0 && $failedAllocation == 0) {
         echo "1|Student allocated";
      } elseif ($successAllocated == 1 && ($failedAllocation == 0 || $failedAllocation == 0)) {
         echo "1|Have student failed allocated or already allocated";
      } else {
         echo "0|This student is already in this class";
      }
   } else {
      echo "0|Invalid parameter";
   }
} else {
   echo "0|Unauthorised access";
}
?>