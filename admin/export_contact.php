<?php
include_once("../mysql.php");
if ($_SESSION["isLogin"] == 1) {
   if ($_SESSION["UserType"] == "A" && $_SESSION["CentreCode"] == "MYQWESTC1C10231") {
     
?>
      <br>
      <form name="frmExport" id="frmExport" method="post" target="_blank" action="admin/do_export_contact.php" class="uk-form">

         <select name="centre_code" onchange="get_student(this.value)" style="width:40%;" id="centre_code" class="uk-width-1-1">
            <?php
               $sql="SELECT centre_code,company_name from centre order by company_name ";
               $result=mysqli_query($connection, $sql);
               while ($row=mysqli_fetch_assoc($result)) {
            ?>
                  <option value="<?php echo $row["centre_code"]?>" <?php if ($row["centre_code"]== $_SESSION["CentreCode"] ) { echo "selected"; }?>><?php echo $row["company_name"]?></option>
            <?php
               }
            ?>
         </select>

         <select name="student_code" id="student">
            <option value="">Select a Student or leave empty for all</option>
            <?php
               $sql="SELECT * from student where deleted=0 AND `centre_code` = '".$_SESSION["CentreCode"]."'";
               
               $result=mysqli_query($connection, $sql);
               while ($row=mysqli_fetch_assoc($result)) {
            ?>
                  <option value="<?php echo $row['student_code']?>"><?php echo $row['name']." (".$row["student_code"].")"?></option>
            <?php
               }
            ?>
         </select>
         <button id="btnExport" class="uk-button">Export</button>
      </form>
<?php
   }
}
?>

<script>
   function get_student(centre_code)
   {
      $.ajax({
      url : "admin/get_centre_student.php",
      type : "POST",
      data : "centre_code="+centre_code,
      dataType : "text",
      beforeSend : function(http) {
      },
      success : function(response, status, http) {
         $("#student").html(response);
      },
      error : function(http, status, error) {
         UIkit.notification("Error:"+error);
      }
   });
   }
</script>
