<?php
include_once("../mysql.php");

foreach($_POST as $key=>$value) {
   $$key=mysqli_real_escape_string($connection, $value); //$id
}

$sql="SELECT * from visitor v left join visitor_student vs on v.id=vs.visitor_id where v.id='$id'";
$result=mysqli_query($connection, $sql);
$num_row=mysqli_num_rows($result);
?>
<table class="uk-table">
   <tr class="uk-text-bold">
      <td>Children</td>
      <td>Year of Birth</td>
      <td>Status</td>
      <td>Action</td>
   </tr>
<?php
if ($num_row>0) {
	
   if ($row=mysqli_fetch_assoc($result)) {
	   for($count=1; $count <= 6; $count++){
			if(!$row["child_birth_year_".$count]) continue;
			$student_found=0;
			if($row["child_".$count."_student_id"]){
				$sql="SELECT * from student where id='".$row["child_".$count."_student_id"]."' and deleted=0";
				$result=mysqli_query($connection, $sql);
				$student_found=mysqli_num_rows($result);
			}
?>
   <tr>
      <td><?php echo $row["child_name_".$count] ?></td>
      <td><?php echo $row["child_birth_year_".$count]?></td>
      <td><?php echo $student_found > 0 ? 'Registered' : 'Not registered' ?></td>
      <td>
		<?php if($student_found > 0): ?>
         <a href="index.php?p=student_reg&mode=EDIT&id=<?php echo sha1($row["child_".$count."_student_id"]) ?>" data-uk-tooltip="{pos:top}" title="Edit Student" ><i class="fa fa-user-edit text-facebook"></i></a>
		 <?php else: ?>
         <a href="index.php?p=student_reg&visitor=<?php echo sha1($id) ?>&ch=<?php echo $count ?>" data-uk-tooltip="{pos:top}" title="Register Student" ><i class="fa fa-user-plus text-google"></i></a>
		 <?php endif ?>
      </td>
   </tr>
<?php
	   }
   }
} else {
?>
   <tr>
      <td colspan="6">No record found</td>
   </tr>
<?php
}
?>
</table>
<div class="uk-text-right" >
<a onclick="$('#dlgRegisteredChilds').dialog('close');" class="uk-button uk-button-small">Cancel</a>
</div>