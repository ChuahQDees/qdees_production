<?php
$table="schedule_term";
$key_field="code|module";
$msg="";

function isRecordFound($table, $key_field, $key_value) {
   global $connection;

   $key_field_array=explode("|", $key_field);
   $key_value_array=explode("|", $key_value);

   $condition="";
   for ($i=0; $i<count($array_key_field); $i++) {
      $condition.=$key_field_array[$i]."="."'".$value."',";
   }
   
   $condition=substr($condition, 0, -1);

   $sql="SELECT $key_field from `$table` where $condition";
   $result=mysqli_query($connection, $sql);
   $num_row=mysqli_num_rows($result);

   if ($num_row>0) {
      return true;
   } else {
      return false;
   }
}
if ($mode=="EDIT") {
    if ($get_sha1_id!="") {
        $edit_sql="SELECT * from `$table` where year='".$_GET['year']."'";
        $result=mysqli_query($connection, $edit_sql);
    }
}

if ($mode=="DEL") {
    if ($get_sha1_id!="") {
        $del_sql="DELETE from `$table` where sha1(id)='$get_sha1_id'";
        $result=mysqli_query($connection, $del_sql);
        $msg="Record deleted";
    }
}

if ($mode=="SAVE") {

    $start_year = date('Y',strtotime($_POST['term1_from_date'])); 

    $end_year = ($_POST['term5_to_date'] == '') ? date('Y',strtotime($_POST['term4_to_date'])) : date('Y',strtotime($_POST['term5_to_date']));

    $year = ($start_year != $end_year) ? $start_year.'-'.$end_year : $start_year;
    
    for($i = 1; $i <= 5; $i++)
    {
        if($i == 5 && $_POST['term5_from_date'] == '') { 
            if(isset($_POST['id'.$i]) && $_POST['id'.$i] > 0) {
                mysqli_query($connection,"DELETE FROM `$table` WHERE `id` = '".$_POST['id'.$i]."'");
            }
            break;  
        }

        $term = 'Term '.$i;
        $term_start = isset($_POST['term'.$i.'_from_date']) ? date('Y-m-01',strtotime($_POST['term'.$i.'_from_date'])) : '';
        $term_end = isset($_POST['term'.$i.'_to_date']) ? date('Y-m-t',strtotime($_POST['term'.$i.'_to_date'])) : '';
        $term_num = $i;

        if($term != '' && $year != '' && $term_start != '' && $term_end != '')
        {
            if(isset($_POST['id'.$i]) && $_POST['id'.$i] > 0)
            {
                if($i == 1) {
                    $prev_year = mysqli_fetch_array(mysqli_query($connection,"SELECT `year` FROM `schedule_term` WHERE `id` = '".$_POST['id'.$i]."'"));

                    if($year != $prev_year['year']) {

                        $where = " AND `centre_code` = '".$_SESSION["CentreCode"]."'";

                        $query = "UPDATE `busket` SET `year` = '".$year."' WHERE `year` = '".$prev_year['year']."' ".$where.";";
                        $query .= "UPDATE `centre_statement_account` SET `year` = '".$year."' WHERE `year` = '".$prev_year['year']."' ".$where.";";
                        $query .= "UPDATE `class` SET `year` = '".$year."' WHERE `year` = '".$prev_year['year']."' ".$where.";";
                        $query .= "UPDATE `collection` SET `year` = '".$year."' WHERE `year` = '".$prev_year['year']."' ".$where.";";
                        $query .= "UPDATE `declaration` SET `year` = '".$year."' WHERE `year` = '".$prev_year['year']."' ".$where.";";
                        $query .= "UPDATE `group` SET `year` = '".$year."' WHERE `year` = '".$prev_year['year']."' ".$where.";";
                        $query .= "UPDATE `slot_collection` SET `year` = '".$year."' WHERE `year` = '".$prev_year['year']."' ".$where.";";
                        $query .= "UPDATE `student` SET `extend_year` = '".$year."' WHERE `extend_year` = '".$prev_year['year']."' ".$where.";";
                        $query .= "UPDATE `tmp_busket` SET `year` = '".$year."' WHERE `year` = '".$prev_year['year']."' ".$where.";";
                        $query .= "UPDATE `fee_structure` SET `extend_year` = '".$year."' WHERE `extend_year` = '".$prev_year['year']."' ".$where.";";

                        $query_array = explode(";",$query);

                        foreach($query_array as $key => $value)
                        {
                            $q = mysqli_query($connection,$value);

                            if($q) {
                                //echo "success<br>";
                            } else {
                                //echo "failed = " .$value. "<br>";
                            }
                        }

                        $student = mysqli_query($connection,"SELECT `id` FROM `student` WHERE 1=1 ".$where."");

                        while($row = mysqli_fetch_array($student))
                        {
                            if(mysqli_query($connection,"UPDATE `allocation` SET `year` = '".$year."' WHERE `student_id` = '".$row['id']."' AND `year` = '".$prev_year['year']."'")) 
                            {
                                //echo $row['id']." = success<br>";
                            }
                        }

                        if($_SESSION['Year'] == $prev_year['year']) { $_SESSION['Year'] = $year; }
                    }
                }

                $update_sql="UPDATE `$table` SET `term` = '$term', `term_start` = '$term_start', `term_end` = '$term_end', `year` = '$year', `term_num` = '".$term_num."' WHERE `id` = '".$_POST['id'.$i]."'";
                
                $result=mysqli_query($connection, $update_sql);
            }
            else
            {
                $insert_sql="INSERT into `$table` (`centre_code`,`term`, `term_start`, `term_end`, `year`, `term_num`) values ('".$_SESSION["CentreCode"]."','$term', '$term_start', '$term_end', '$year','".$term_num."')";
                
                $result=mysqli_query($connection, $insert_sql);
            }
        }
    }

    $msg="Record saved";
}

   $browse_sql="SELECT * from `$table` WHERE `deleted` = 0 AND `centre_code` = '".$_SESSION['CentreCode']."' order by `year` desc, `term_num` asc";
?>
