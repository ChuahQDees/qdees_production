<?php
session_start();
include_once("../mysql.php");
include_once("../lib/pagination/pagination.php");
include_once("functions.php");

//if (($_SESSION["isLogin"]==1) & (($_SESSION["UserType"]=="A") || ($_SESSION["UserType"]=="O")) & (hasRightGroupXOR($_SESSION["UserName"], "AllocationEdit|AllocationView")) & ($_SESSION['Year'] == date('Y'))) {
   if (($_SESSION["isLogin"]==1) & (($_SESSION["UserType"]=="A") || ($_SESSION["UserType"]=="O")) & (hasRightGroupXOR($_SESSION["UserName"], "AllocationEdit|AllocationView"))) {
function getStudentID($student_code) {
   global $connection;

   $sql="SELECT id from student where student_code='$student_code'";
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);

   return $row["id"];
}

function alreadyAllocated($student_code) {
   global $connection, $allocation_status;

   if ($allocation_status=="onlyunallocated") {
      $student_id=getStudentID($student_code);

      $sql="SELECT * from allocation where student_id='$student_id' and deleted=0";
      $result=mysqli_query($connection, $sql);
      $num_row=mysqli_num_rows($result);
      if ($num_row>0) {
         return true;
      } else {
         return false;
      }
   } else {
      return false;
   }
}
$student_name=$_POST["student_name"];
$pg=$_POST["pg"];
$centre_code=$_SESSION['CentreCode'];
$allocation_status=$_POST["allocation_status"];
$group_id=$_POST["group_id"];
$class_id=$_POST["class_id"];
$course_id=$_POST["course_id"];
$level=$_POST["student_entry_level"];
$year=$_SESSION['Year'];

if ($student_name!="") {
   //$sql="SELECT * from student where ((name like '%$student_name%') or (student_code like '%$student_name%')) and deleted=0 and
   //student_status='A' and centre_code='$centre_code'  and year(start_date_at_centre) <= '$year' and extend_year >= '$year' order by `name`";
   $sql="SELECT s.* from student s inner join programme_selection ps on s.id = ps.student_id inner join student_fee_list fl on ps.id = fl.programme_selection_id inner join fee_structure f on fl.fee_id = f.id  where ((s.name like '%$student_name%') or (s.student_code like '%$student_name%')) and ps.student_entry_level='$level' and s.deleted=0 and s.student_status='A' and s.centre_code='$centre_code'  and ((fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') or (fl.programme_date_end BETWEEN '".$year_start_date."' AND '".$year_end_date."')) group by s.id order by `name`";
} else {
  //$sql="SELECT * from student where deleted=0 and student_status='A' and centre_code='$centre_code'  and year(start_date_at_centre) <= '$year' and extend_year >= '$year' order by `name`";
  
  $sql="SELECT s.* from student s inner join programme_selection ps on s.id = ps.student_id inner join student_fee_list fl on ps.id = fl.programme_selection_id inner join fee_structure f on fl.fee_id = f.id  where ps.student_entry_level='$level' and s.deleted=0 and s.student_status='A' and s.centre_code='$centre_code'  and ((fl.programme_date BETWEEN '".$year_start_date."' AND '".$year_end_date."') or (fl.programme_date_end BETWEEN '".$year_start_date."' AND '".$year_end_date."')) group by s.id order by `name`";
}

//echo $sql;
$numperpage=20;
$query="p=$p&student_name=$student_name";
// $pagination=getPagination($pg, $numperpage, $query, $sql, $start_record, $num_row);
// $sql.=" limit $start_record, $numperpage";
$result=mysqli_query($connection, $sql);
$num_row=mysqli_num_rows($result);
// echo $pagination;
?>
<script>
<?php
if (hasRightGroupXOR($_SESSION["UserName"], "AllocationEdit")) {
?>
function addAllocation(id, group_id, class_id, course_id) {
   
   $.ajax({
      url : "admin/add_allocation.php",
      type : "POST",
      data : "id="+id+"&group_id="+group_id+"&the_class="+class_id+"&course_id="+course_id,
      dataType : "text",
      beforeSend : function(http) {
      },
      success : function(response, status, http) {
         var programme=$("#programme").val();
         var level=$("#level").val();
         var the_module=$("#module").val();
         var the_class=$("#class").val();
         var year='<?php echo $_SESSION['Year']; ?>';
         var course=programme+level+the_module;
         var s=response.split("|");

         if (s[0]=="1") {
            UIkit.notify(s[1]);
            window.location.reload();
//            getSelectedStudent(programme, level, the_module, the_class, year);
         } else {
            UIkit.notify(s[1]);
         }
      },
      error : function(http, status, error) {
         UIkit.notification("Error:"+error);
      }
   });
}
   var table = $('#datatable').DataTable({
	 'columnDefs': [ {
         'targets': [0,3 ], // column index (start from 0)
         'orderable': false, // set orderable false for selected columns
      }],
	'order':[ 1, "asc" ]
});
$("#allocate").click(function(event){
    event.preventDefault();


    var dataID = table.$("input:checkbox:checked").map(function(){
      return $(this).attr("data-id");
    }).get();

    var groupID = table.$("input:checkbox:checked").map(function(){
      return $(this).attr("data-group");
    }).get();

    var classID = table.$("input:checkbox:checked").map(function(){
      return $(this).attr("data-class");
    }).get();

    var courseID = table.$("input:checkbox:checked").map(function(){
      return $(this).attr("data-course");
    }).get();

    // alert(dataID);
     $.ajax({
      url : "admin/add_allocation.php",
      type : "POST",
      data : "id="+dataID+"&group_id="+groupID+"&the_class="+classID+"&course_id="+courseID,
      dataType : "text",
      cache: false,
      beforeSend : function(http) {
      },
      success : function(response, status, http) {
         var programme=$("#programme").val();
         var level=$("#level").val();
         var the_module=$("#module").val();
         var the_class=$("#class").val();
         var year='<?php echo $_SESSION['Year']; ?>';
         var course=programme+level+the_module;
         var s=response.split("|");

         if (s[0]=="1") {
            UIkit.notify(s[1]);
            window.location.reload();
//            getSelectedStudent(programme, level, the_module, the_class, year);
         } else {
            UIkit.notify(s[1]);
         }
      },
      error : function(http, status, error) {
         UIkit.notification("Error:"+error);
      }

   });
    
});

<?php
}
?>
function doSearch(pg) {
   var student_name=$("#student_name").val();
   var group_id = <?php echo  $group_id?>;
   var class_id = <?php echo  $class_id?>;
   var course_id = <?php echo  $course_id?>;
    var divWidth = $("#box").width();
   getAllStudent(pg, student_name, "onlyunallocated", group_id, class_id, course_id);
}
</script>
<style type="text/css">
   #datatable_length{
display: none !important;
}
 #mydatatable_filter{
/*display: none !important;*/
}
.uk-pagination{
   display: none;
  
   
}

#datatable_paginate .paginate_button{display: inline-block; min-width: 16px; padding: 3px 5px; line-height: 20px; text-decoration: none; -moz-box-sizing: content-box; box-sizing: content-box; text-align: center; background: #f7f7f7; color: #444444; border: 1px solid rgba(0, 0, 0, 0.2); border-bottom-color: rgba(0, 0, 0, 0.3); background-origin: border-box; background-image: -webkit-linear-gradient(top, #ffffff, #eeeeee); background-image: linear-gradient(to bottom, #ffffff, #eeeeee); text-shadow: 0 1px 0 #ffffff;margin-left: 3px;
    margin-right: 3px}
#datatable_paginate .paginate_button.current{background: #009dd8; color: #ffffff!important; border: 1px solid rgba(0, 0, 0, 0.2); border-bottom-color: rgba(0, 0, 0, 0.4); background-origin: border-box; background-image: -webkit-linear-gradient(top, #00b4f5, #008dc5); background-image: linear-gradient(to bottom, #00b4f5, #008dc5); text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.2);}
#datatable_filter{width:100%}
#datatable_filter label{width:100%;display:inline-flex}
#datatable_filter label input{height: 30px;width: 100%; padding: 4px 6px; border: 1px solid #dddddd; background: #ffffff; color: #444444; -webkit-transition: all linear 0.2s; transition: all linear 0.2s; border-radius: 4px;}

#datatable {
    margin-top: 5%;
    margin-right: 37%;
}
</style>
<h2>All Available Students</h2>
<div class="uk-form">
<!-- <input type="text" class="uk-form-small" placeholder="Student Name" name="student_name" id="student_name" value="<?php// echo $student_name?>">
<a class="uk-button uk-button-small" onclick="doSearch('<?php echo $pg?>')">Search</a> -->

<table class="uk-table uk-table-small uk-table-hover" id='datatable'>

   <thead>
      <tr>
         <td colspan="2"></td>
         <td colspan="2">
           <button class="uk-button uk-button-medium" id="allocate" style="float: right;"> Allocate</button>
         </td>
      </tr>
   <tr class="uk-text-bold uk-text-small">
      <td style="padding: 8px 10px;"><input type="checkbox" class="checkboxAll"></td>
      <td>Student Code</td>
      <td>Name</td>
      <td>Action</td>
   </tr>
</thead>

<tbody>
<?php while ($row=mysqli_fetch_assoc($result)):
//   if (!alreadyAllocated($row["student_code"])) {
?>
   <tr class="uk-text-small">
      <td><input type="checkbox" class="checkbox" name="checkbox[]" data-id="<?=$row['id']?>" data-group="<?=$group_id?>"  data-class="<?=$class_id?>"  data-course="<?=$course_id?>"></td>
      <td><?php echo $row["student_code"]?></td>
      <td><?php echo $row["name"]?></td>
      <td>
<?php if (hasRightGroupXOR($_SESSION["UserName"], "AllocationEdit")):?>
      <a class="mdi mdi-plus mdi-24px font-weight-bold" data-uk-tooltip title="Allocate Student" onclick="addAllocation('<?php echo $row['id']?>', '<?php echo $group_id?>', '<?php echo $class_id?>', '<?php echo $course_id?>')" id="btnAdd" src="images/add.png"></a>
<?php endif;?> 
      </td>
   </tr>
<?php  endwhile; ?>
</tbody>
</table>
<br><br><br>
</div><br><br><br><br>

<?php
} else {
   echo "<div class='uk-margin-top uk-margin-right'><div class='uk-alert uk-alert-danger uk-text-large uk-text-bold uk-text-center'>Unauthorised access denied</div></div>";
}
?>
<br>
<script>
  $(document).ready(function (){
	  $(".checkboxAll").change(function() {
		if(this.checked) {
			$(".checkbox").prop("checked", true);
		}else{
			$(".checkbox").prop("checked", false);
		}
	});
	 
    // $('#datatable').dataTable({
	// 'columnDefs': [ {
        // 'targets': [0,3 ], // column index (start from 0)
        // 'orderable': false, // set orderable false for selected columns
     // }],
	// 'order':[ 1, "asc" ]
// }); 
  });
</script>