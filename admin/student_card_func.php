<?php

function getStudentCard($card_id){
  global $connection;

  $sql="SELECT * from student_card where sha1(id)='$card_id' LIMIT 1";
  $result=mysqli_query($connection, $sql);

  if ($result) {
    $edit_row=mysqli_fetch_assoc($result);
  }else{
    $edit_row = array();
  }
//print_r($edit_row);
  return $edit_row;
}

function getStudentId($get_sha1_id){
  global $connection;

  $sql="SELECT * from student where sha1(id)='$get_sha1_id' LIMIT 1";
  $result=mysqli_query($connection, $sql);
  //print_r($sql); die;
  if ($result) {
    $row=mysqli_fetch_assoc($result);
    $id = $row['id'];
    
  }else{
    $id = "";
  }

  return $id;
}

function getStudentUniqueId($get_sha1_id){
  global $connection;

  $sql="SELECT * from student where sha1(id)='$get_sha1_id' LIMIT 1";
  $result=mysqli_query($connection, $sql);
  //print_r($sql); die;
  if ($result) {
    $row=mysqli_fetch_assoc($result);
    $id = $row['unique_id'];
    
  }else{
    $id = "";
  }

  return $id;
}

function deleteStudent($get_sha1_id){
  global $connection;
  $del_sql="UPDATE student_card set deleted=1 where sha1(id)='$get_sha1_id'";
  $result=mysqli_query($connection, $del_sql);
  if ($result) {
     return true;
  } else {
     throw new Exception("Cannot delete student card");
  }
}

function saveStudentCard(){
  global $connection;

  foreach ($_POST as $key=>$value) {
     $$key=mysqli_real_escape_string($connection, $value);
  }

  $get_sha1_id=$_GET["id"];
  $student_id = getStudentId($get_sha1_id);
   if (($student_id!="") && ($status!="")){
  
      $sql = "UPDATE `student_card` SET `status`='$status',`remarks`='$remarks' WHERE `id`='$card_id' " ;

    //echo $sql; die;
     $result = mysqli_query($connection, $sql);

   }
  
}

function createStudent(){
  global $connection;

  foreach ($_POST as $key=>$value) {
    $$key=mysqli_real_escape_string($connection, $value);
  }
  $get_sha1_id=$_GET["id"];
  $student_id = getStudentId($get_sha1_id);

     $insert_sql="INSERT into student_card (
       student_id,
       status,
       remarks,
       unique_id
     ) values (
       '$student_id',
       '$status',
       '$remarks',
       '$unique_id'
     )";

    //echo $insert_sql; die;

     $result=mysqli_query($connection, $insert_sql);
     $card_id = mysqli_insert_id($connection);

     $sql = "UPDATE `student` SET `unique_id`='$unique_id' WHERE `id`='$student_id' " ;

   // echo $sql; die;
     $result = mysqli_query($connection, $sql);
          //api code
          $sql = "select id from student where id='$student_id'";
          $result1=mysqli_query($connection, $sql);
          $row=mysqli_fetch_assoc($result1);
          $student_code = $row['student_code'];
     
     
          $postRequest = array(
           'student_type' => 'Starter',
           'student_id' => '$student_code',
           'qr_code' => '$unique_id',
           'session' => 'cb76fe897986563639f6983bfd33b57cd'
        );
     
        // $cURLConnection = curl_init('http://13.58.211.59/q-dees/api/StudentQRassignment');
        $cURLConnection = curl_init('http://13.58.211.59/api/StudentQRassignment');
        curl_setopt($cURLConnection, CURLOPT_POSTFIELDS, $postRequest);
        curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);
     
        $apiResponse = curl_exec($cURLConnection);
        curl_close($cURLConnection);
     
        //end api code
   
}



$msg="";
// $_SESSION["student_code"] = $_POST['student_code'];
// // var_dump($_SESSION["student_code"]);


// if (defined('IS_ADMIN_STUDENT_FORM') && IS_ADMIN_STUDENT_FORM ) {
 
  $get_sha1_id=$_GET["id"];

 // $form_post_url = "index.php?p=student_reg&id=$get_sha1_id&m=$m";

  if ($_SESSION["isLogin"]) {

    if($mode=="EDIT"){
      $card_id=$_GET["card_id"];
      $edit_row = getStudentCard($card_id);
    }
    

   $unique_id = getStudentUniqueId($get_sha1_id);

    try {
      $form_mode = $_POST["form_mode"];; 
      //echo $form_mode; 
      if ($form_mode=="EDIT") {

        //process form
        if (!empty($_POST)) {
          saveStudentCard();
          $msg = "Card status successfully updated.";
          echo "<script> alert('Card status successfully updated.'); window.location = 'index.php?p=student_card&id=$get_sha1_id&pg=$pg'</script>";
        }

      }else if ($form_mode=="Add") {
        //new student
       // echo $form_mode; die;
        //process form
        if (!empty($_POST)) {
          $student_id = getStudentId($get_sha1_id);
          $sql2 = "select id from student_card where student_id='$student_id' and status=0";
          $result2=mysqli_query($connection, $sql2);
          $num_row=mysqli_fetch_assoc($result2);
          if ($num_row == 0) {

              $new_student_id = createStudent();
              $msg = "New card successfully created.";
              //header("Location: index.php?p=student_card&id=$get_sha1_id&pg=$pg");
              // echo '<script>window.location = "http://www.google.com/" </script>';
              echo "<script> alert('New card successfully created.'); window.location = 'index.php?p=student_card&id=$get_sha1_id&pg=$pg'</script>";

          }else{
            echo "<script> alert('A card already active.'); window.location = 'index.php?p=student_card&id=$get_sha1_id&pg=$pg'</script>";
          }
        }
      }
    } catch (\Exception $e) {
      if (!empty($_POST)) {
        setStudentRegistrationSession();
        $data_array=$_SESSION["StudentReg"];
      }

      $msg = $e->getMessage();
    }
  }//isLogin

// }

//}//IS_ADMIN_STUDENT_FORM
if ($_SESSION['isLogin']) {
  
  if (!empty($_GET["action"]) &&  $_GET["action"] == 'DEL') {
    echo $_GET["card_id"]; 
    try {
      deleteStudent($_GET['card_id']);
      echo "<script> alert('Deleted Successfuly.'); window.location = 'index.php?p=student_card&id=$get_sha1_id&pg=$pg'</script>";
    } catch (\Exception $e) {
      $msg = $e->getMessage();
    }
 }//del
}
?>
