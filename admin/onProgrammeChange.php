<?php
include_once("../mysql.php");

foreach ($_POST as $key=>$value) {
   $$key=mysqli_real_escape_string($connection, $value);
}

$s="";
if ($programme!="") {
   $s.="<select name='level' id='level'>";
   $s.="   <option value=''>Select</option>";
   $sql="SELECT * from course where course_name like '$programme%' and deleted=0 order by course_name";
   $result=mysqli_query($connection, $sql);
   $current_level="";
   while ($row=mysqli_fetch_assoc($result)) {
      switch ($programme) {
         case "EDP" : $pre_level=substr($row["course_name"], 4); break;
         case "QF 1" : $pre_level=substr($row["course_name"], 4); break;
         case "QF 2" : $pre_level=substr($row["course_name"], 2); break;
         case "QF 3" : $pre_level=substr($row["course_name"], 2); break;
      }

      $pre_level_array=explode("-", $pre_level);
      $pre_level=$pre_level_array[0];

      $pre_level=substr($pre_level, 0, 3);
      $pre_level=str_replace("M", "", $pre_level);

      if ($current_level!=$pre_level) {
         if ($level==$pre_level) {
            $selected="selected";
         } else {
            $selected="";
         }

         $s.="<option value='$pre_level' $selected>$pre_level</option>";
         $current_level=$pre_level;
      }
   }
   $s.="</select>";
} else {
   $s.="<select name='level' id='level'>";
   $s.="   <option value=''>Select Programme First</option>";
   $s.="</select>";
}

echo $s;
?>