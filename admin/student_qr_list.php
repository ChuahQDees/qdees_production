<a href="/index.php?p=student">
         <span class="btn-qdees"><i class="fa fa-arrow-left" style="margin-right: 20px;"></i>Back</span>
</a>
<span>
    <span class="page_title"><img src="/images/title_Icons/Stud Reg.png">Student Registration</span>
</span>

<?php
include_once("mysql.php");
include_once("search_new.php");
include_once("admin/functions.php");

if( ! defined('STUDENT_PHOTO_URL') ){
  define('STUDENT_PHOTO_URL', '/admin/student_photo/');
}

if( ! defined('STUDENT_PHOTO_PATH') ){
  define('STUDENT_PHOTO_PATH', __DIR__ . '/student_photo/');
}

processFormDeleteTempStudent();

function calculateAge($dob){
  $dt = DateTime::createFromFormat("Y-m-d", $dob);
  $age = DateTime::createFromFormat('Y-m-d', $dt->format('Y-01-01'))
     ->diff(DateTime::createFromFormat('Y-m-d', date('Y-01-01')))
     ->y;

  return $age;
}

function getStudentPhotoSrc($file_name, $size = 'small'){
  $file_url = '';

  if( $file_name ){
    switch($size){
      case 'medium':
        $size = '300x400';
        break;
      default:
        $size = '60x80';
        break;
    }
    $file_path = STUDENT_PHOTO_PATH . $file_name . '_' . $size . '.jpg';

    if( file_exists($file_path) ):
      $file_url = STUDENT_PHOTO_URL . $file_name . '_' . $size . '.jpg';
    endif;
  }

  return $file_url;
}

function processFormDeleteTempStudent(){
  global $connection;

  if (($_GET["del_id"]!="") & ($_GET["action"]=="DEL")) {
    $del_id = mysqli_real_escape_string($connection, $_GET["del_id"]);
    $sql = "DELETE FROM tmp_student where sha1(id) ='$del_id'";
    $result = mysqli_query($connection, $sql);
  }
}

?>
<script>
function doDelete(id) {
   UIkit.modal.confirm("<h2>Are you sure to continue?</h2>", function () {
      $("#del_id").val(id);
      $("#frmDelete").submit();
   });
}

function doImport(id) {
   UIkit.modal.confirm("<h2>Are you sure to continue?</h2>", function () {
      $.ajax({
         url : "admin/import_student.php",
         type : "POST",
         data : "id="+id,
         dataType : "text",
         beforeSend : function(http) {
         },
         success : function(response, status, http) {
            var s=response.split("|");
            if (s[0]=="1") {
               location.reload();
            } else {
               UIkit.notify(s[1]);
            }
         },
         error : function(http, status, error) {
            UIkit.notification("Error:"+error);
         }
      });
   })
}
</script>

<div class="uk-margin-left uk-margin-top uk-margin-right">
    <div class="uk-width-1-1 myheader">
        <div class="uk-grid">
            <div class="uk-width-3-10">

            </div>
            <div class="uk-width-4-10 ">
                <h2 class="uk-text-center myheader-text-color myheader-text-style">Student Registration Listing</h2>
            </div>
            <div class="uk-width-3-10"></div>
        </div>
    </div>

    <div class="header-bottom-part orderstatusmain">

   <form class="uk-form" method="post" action="">

       <div class="row">
         <!--  <div class="col-sm-12 col-md-3">
               <input name="name" id="name" class="uk-width-1-1" placeholder="Student's Name" type="text" value="<?php // echo $_POST["name"]?>">
           </div>
           <div class="col-sm-12 col-md-3">
               <?php
               //$sql="SELECT * from codes where module='COUNTRY' order by code";
               //generateSelect($sql, "code", "code", "country", "class='uk-width-1-1'", $_POST["country"]);
               ?>
           </div>-->
           <div class="col-sm-12 col-md-5">
               <input name="name" id="name" class="uk-width-1-1" placeholder="Student's Name" type="text" value="<?php echo $_POST["name"]?>">
           </div>
		   <div class="col-sm-12 col-md-5">
               <input type="text" class="uk-width-1-1" name="start_date" id="start_date" placeholder="Select Date" data-uk-datepicker="{format: 'YYYY-MM-DD'}" value="" autocomplete="off">
           </div>
           <div class="col-sm-12 col-md-2">
               <button class="uk-button full-width-blue">Search</button>
           </div>
       </div>
   </form>
<?php
$year=$_SESSION['Year'];

$current_year=date("Y");
//$base_sql="SELECT s.*, ec.email, ec.mobile, ec.mobile_country_code from tmp_student s RIGHT JOIN tmp_student_emergency_contacts ec on s.student_code=ec.student_code ";
$base_sql="SELECT s.*, ec.email, ec.mobile, ec.mobile_country_code from tmp_student s RIGHT JOIN tmp_student_emergency_contacts ec on s.student_code=ec.student_code and start_date_at_centre <= '".$year_end_date."' and extend_year >= '$year'";
//echo $base_sql;
$centre_token=ConstructToken("s.centre_code", $_SESSION["CentreCode"], "=");
//$name_token=ConstructToken("s.name", "%".$_POST["name"]."%", "like");
$name_token=ConstructToken("s.name", "%".$_POST["name"]."%", "like");
$name_start_date=ConstructToken("s.start_date_at_centre", "%".$_POST["start_date"]."%", "like");
//$mobile_token=ConstructToken("CONCAT(ec.mobile_country_code, ec.mobile)", "%".$_POST["mobile"]."%", "like");
//$year_token=ConstructToken("year(s.start_date_at_centre)", "$year", "=");

$final_token=ConcatToken($final_token, $centre_token, "and");
$final_token=ConcatToken($final_token, $name_token, "and");
$final_token=ConcatToken($final_token, $name_start_date, "and");
//$final_token=ConcatToken($final_token, $mobile_token, "and");
//$final_token=ConcatToken($final_token, $year_token, "and");

$final_sql=ConcatWhere($base_sql, $final_token);
//$final_sql.= " ORDER BY s.start_date_at_centre DESC, s.name ASC";
$final_sql.= " ORDER BY s.start_date_at_centre DESC, s.name DESC";
$result=mysqli_query($connection, $final_sql);
$num_row=mysqli_num_rows($result);
?>
   <table class="uk-table q-table">
      <tr class="uk-text-small uk-text-bold">
         <td>Photo</td>
         <td>Student Name</td>
         <td>Age</td>
         <td>Nationality</td>
         <td>Start Date</td>
         <td>Contact</td>
         <td>Action</td>
      </tr>
<?php
if ($num_row>0) {
    $students = array();
    while ($row=mysqli_fetch_assoc($result)) {
     if(empty($students[$row['student_code']])) {
       $students[$row["student_code"]] = $row;
     }
    }//endwhile

    foreach ($students as $student) {
?>
      <tr class="uk-text-small">
        <td><?php
          $student_photo_src = getStudentPhotoSrc($student['photo_file_name']);
          if( $student_photo_src ){
            echo '<img src="' . $student_photo_src . '" width="60px" height="80px">';
          }
        ?></td>
         <td><?php echo $student["name"]?></td>
         <td><?php echo calculateAge($student["dob"])?></td>
         <td><?php echo $student["nationality"]?></td>
         <td><?php echo $student["start_date_at_centre"]?></td>
         <td><?php echo "+".$student["mobile_country_code"].$student["mobile"]?><br><?php echo $student["email"]?></td>
         <td>
            <?php
            //if ($current_year == $year) {
            ?>
              <a href="/student_qr.php?centre_code=<?php echo $student["centre_code"]; ?>&id=<?php echo sha1($student["id"]); ?>"  data-uk-tooltip title="Edit" style="display: inline-block; margin-top: 5px; width: 30px; height: 30px;"><i class="fas fa-user-edit" style="width: 30px; height: 30px; vertical-align: top"></i></a>
              <a style="display: inline-block; width: 30px; height: 30px;"><img data-uk-tooltip="{pos:top}" title="Import Student" onclick="doImport('<?php echo sha1($student["id"])?>')" src="images/approve.jpeg" style="width: 30px;"></a>
              <?php
              //}
              ?>
            <a style="display: inline-block; width: 30px; height: 30px;"><img data-uk-tooltip="{pos:top}" title="Delete Student" onclick="doDelete('<?php echo sha1($student["id"])?>')" src="images/delete.jpeg" style="width: 30px;"></a>
         </td>
      </tr>
<?php
    }//foreach
} else {
?>
      <tr>
         <td colspan="99">No Record Found</td>
      </tr>
<?php
}
?>

    </div>
   </table>
</div>

<form id="frmDelete" method="get" action="">
   <input type="hidden" name="p" id="p" value="student_qr_list">
   <input type="hidden" name="del_id" id="del_id" value="">
   <input type="hidden" name="action" id="action" value="DEL">
</form>
