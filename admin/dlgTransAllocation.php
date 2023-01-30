<?php
session_start();
include_once(dirname(__FILE__)."/functions.php");
include_once(dirname(dirname(__FILE__))."/mysql.php");

foreach ($_POST as $key=>$value) {
   $$key=$value;//$group_id, $class_id
}

function getDOWString($dow) {
   switch ($dow) {
      case 0 : return "Sunday"; break;
      case 1 : return "Monday"; break;
      case 2 : return "Tuesday"; break;
      case 3 : return "Wednesday"; break;
      case 4 : return "Thursday"; break;
      case 5 : return "Friday"; break;
      case 6 : return "Saturday"; break;
      case 7 : return "Daily"; break;
   }
}

$centre_code=$_SESSION["CentreCode"];
$year=$_SESSION['Year'];
$sql="SELECT g.*, c.subject from `group` g, course c where g.course_id=c.id and g.id='$group_id' order by c.subject";
// var_dump($sql);
$result=mysqli_query($connection, $sql);

echo "<h2>Transfer From Class</h2>";
echo "<table class='uk-table'>";
echo "  <tr class='uk-text-bold'>";
echo "    <td>Programme</td>";
echo "    <td>Day of Week</td>";
echo "    <td>Period</td>";
echo "    <td>Time</td>";
echo "    <td>Group</td>";
echo "    <td>No of Students</td>";
echo "    <td>Selected</td>";
echo "  </tr>";

while ($row=mysqli_fetch_assoc($result)) {
   $row_group_id=$row["id"];
   $row_class_id=$row["class_id"];
   $row_course_id=$row["course_id"];

  $sql = "SELECT count(*) as no_of_student, c.course_name, c.subject, a.class_id, c.id, a.deleted from allocation a, course c, student s
   where a.course_id=c.id and a.student_id=s.id and s.student_status='A' and a.deleted=0 and a.`year`='$year' and a.group_id='$row_group_id' and a.class_id='$row_class_id' and a.course_id='$row_course_id' and s.centre_code='" . $_SESSION["CentreCode"] . "' group by course_id, class_id";

   $c_result=mysqli_query($connection, $sql);
   $c_num_row=mysqli_num_rows($c_result);
   $c_row=mysqli_fetch_assoc($c_result);

   if ($c_num_row>0) {
      $no_of_student=$c_row["no_of_student"];
   } else {
      $no_of_student=0;
   }

   $period=convertDate2British($row["start_date"])." - ".convertDate2British($row["end_date"]);
   $time=$row["start_time"]." - ".$row["end_time"];
   $param="\"$group_id\", \"$class_id\"";
   echo "<tr>";
   echo "  <td>".$row["level"]."</td>";
   echo "  <td>".getDOWString($row["dow"])."</td>";
   echo "  <td>$period</td>";
   echo "  <td>$time</td>";
   echo "  <td>$row_class_id</td>";
   echo "  <td>".$no_of_student."</td>";
   echo "  <td>";
   echo "    <input type='hidden' name='ori_group_id' id='ori_group_id' value='$group_id'>";
   echo "    <input type='hidden' name='ori_class_id' id='ori_class_id' value='$class_id'>";
   echo "    <input type='hidden' name='ori_course_id' id='ori_course_id' value='$course_id'>";
   echo "    <input type='checkbox' checked disabled>";
   echo "  </td>";
   echo "</tr>";
}
echo "</table>";


echo "<h2>To Class </h2>";
echo "<table class='uk-table'>";
echo "  <tr class='uk-text-bold'>";
echo "    <td>Programme</td>";
echo "    <td>Day of Week</td>";
echo "    <td>Period</td>";
echo "    <td>Time</td>";
echo "    <td>Group</td>";
echo "    <td>No of Students</td>";
echo "    <td>Select</td>";
echo "  </tr>";

$todayDate = date("Y-m-d");
$thisYear = date("Y");
$sql="SELECT g.*, c.course_name from `group` g, course c where g.course_id=c.id and centre_code='$centre_code' and end_date >= '$todayDate' and g.id<>'$group_id' and g.`year`='$thisYear' order by c.course_name";

// var_dump($sql);
$result=mysqli_query($connection, $sql);

while ($row=mysqli_fetch_assoc($result)) {
   $row_group_id=$row["id"];
   $row_class_id=$row["class_id"];
   $row_course_id=$row["course_id"];

    $sql = "SELECT count(*) as no_of_student, c.course_name, c.subject, a.class_id, c.id, a.deleted from allocation a, course c, student s
   where a.course_id=c.id and a.student_id=s.id and s.student_status='A' and a.deleted=0 and a.`year`='$year' and a.group_id='$row_group_id' and a.class_id='$row_class_id' and a.course_id='$row_course_id' and s.centre_code='" . $_SESSION["CentreCode"] . "' group by course_id, class_id";
//echo  $sql; die;
   $c_result=mysqli_query($connection, $sql);
   $c_num_row=mysqli_num_rows($c_result);
   $c_row=mysqli_fetch_assoc($c_result);

   if ($c_num_row>0) {
      $no_of_student=$c_row["no_of_student"];
   } else {
      $no_of_student=0;
   }

   $period=convertDate2British($row["start_date"])." - ".convertDate2British($row["end_date"]);
   $time=$row["start_time"]." - ".$row["end_time"];
   $param="$row_group_id|$row_class_id|$row_course_id";

   echo "<tr>";
  echo "  <td>".$row["level"]."</td>";
   echo "  <td>".getDOWString($row["dow"])."</td>";
   echo "  <td>$period</td>";
   echo "  <td>$time</td>";
   echo "  <td>$row_class_id</td>";
 echo "  <td>".$no_of_student."</td>";
   echo "  <td><input type='radio' name='select' id='select' value='$param'></td>";
   echo "</tr>";
}

echo "</table>";

$dialog='$("#dlgTransAllocation").dialog("close")';
echo "<div class='uk-align-right'>";
echo "  <button class='uk-button uk-button-small' onclick='$dialog'>Cancel</button>";
echo "  <button class='uk-button uk-button-small uk-button-primary' onclick='doTrans()'>Add</button>";
echo "</div>";
?>

<script>
function doTrans() {
   var params=$("input[name=select]:checked").val();
   var ori_group_id=$("#ori_group_id").val();
   var ori_class_id=$("#ori_class_id").val();
   var ori_course_id=$("#ori_course_id").val();

   if ((typeof params!=="undefined") & (ori_group_id!="") & (ori_class_id!="") & (ori_course_id!="")) {
      UIkit.modal.confirm("<h2>Are you sure to continue?</h2>", function () {
         $.ajax({
            url : "admin/doTransAllocation.php",
            type : "POST",
            data : "params="+params+"&ori_group_id="+ori_group_id+"&ori_class_id="+ori_class_id+"&ori_course_id="+ori_course_id,
            dataType : "text",
            beforeSend : function(http) {
            },
            success : function(response, status, http) {
               var s=response.split("|");

               if (s[0]=="0") {
                  UIkit.notify(s[1]);
               } else {
                  window.location.reload();
               }
            },
            error : function(http, status, error) {
               UIkit.notify("Error:"+error);
            }
         });
      });
   } else {
      UIkit.notify("Please select a group to transfer to");
   }     
}
</script>