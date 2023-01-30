<?php
session_start();
include_once("../mysql.php");
include_once("functions.php");

foreach ($_POST as $key=>$value) {
   $$key=mysqli_real_escape_string($connection, $value);//group_id, class_id
}

$year=$_SESSION['Year'];

// function getCourseID($pre_course) {
//    global $connection;
//    $sql="SELECT * from course where course_name like '$pre_course%'";
//    $result=mysqli_query($connection, $sql);
//    $row=mysqli_fetch_assoc($result);
//    return $row["id"];
// }
?>
<script>
<?php
if (hasRightGroupXOR($_SESSION["UserName"], "AllocationEdit")) {
?>
function removeAllocation(id, group_id, class_id) {
   UIkit.modal.confirm("<h2>Are you sure to continue?</h2>", function () {
      $.ajax({
         url : "admin/remove_allocation.php",
         type : "POST",
         data : "id="+id,
         dataType : "text",
         beforeSend : function(http) {
         },
         success : function(response, status, http) {
            var s=response.split("|");
            if (s[0]=="1") {
               UIkit.notify(s[1]);
               window.location.reload();
            } else {
               UIkit.notify(s[1]);
            }
         },
         error : function(http, status, error) {
            UIkit.notify("Error:"+error);
         }
      });
   });
}
<?php
}
?>
</script>
<br>
<h2>Selected Students</h2>
<?php
$sql="SELECT a.*, s.name, s.student_code from allocation a, student s, course c where a.group_id='$group_id' and a.course_id='$course_id'
      and a.course_id=c.id and a.class_id='$class_id' and a.year='$year' and s.id=a.student_id and a.deleted=0 and s.student_status='A' and s.centre_code='".$_SESSION["CentreCode"]."'";

$result=mysqli_query($connection, $sql);
$num_row=mysqli_num_rows($result);
?>
<div class="uk-form">
<table class="uk-table uk-table-small uk-table-hover">
   <tr class="uk-text-small uk-text-bold">
      <td>Student Code</td>
      <td>Name</td>
      <td>Action</td>
   </tr>
<?php
if ($num_row>0) {
while ($row=mysqli_fetch_assoc($result)) {
?>
   <tr class="uk-text-small">
      <td><?php echo $row["student_code"]?></td>
      <td><?php echo $row["name"]?></td>
      <td>
<?php
if (hasRightGroupXOR($_SESSION["UserName"], "AllocationEdit")) {
?>
         <a class="mdi mdi-delete text-danger mdi-24px" data-uk-tooltip title="Remove allocation" onclick="removeAllocation('<?php echo $row['id']?>', '<?php echo $group_id?>', '<?php echo $class_id?>')"  ></a>
<?php
}
?>
      </td>
   </tr>
<?php
}
} else {
?>
   <tr class="uk-text-small"><td colspan="3">No record found</td></tr>
<?php
}
?>
</table>
</div>
<div id="dlgTransferAllocation"></div>