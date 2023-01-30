<?php
include_once("../mysql.php");

foreach ($_POST as $key=>$value) {
   $$key=mysqli_real_escape_string($connection, $value);
}

$s="";
if ( $level=="EDP") {
	 $s.="<select name='level' id='level'>";
   $s.="   <option value=''>Select</option>";
   $s.="   <option value='En-Picasso1'>En-Picasso 1</option>";
   $s.="   <option value='En-Nightingale1'>En-Nightingale 1</option>";
   $s.="   <option value='En-Picasso2'>En-Picasso 2</option>";
   $s.="   <option value='En-Nightingale2'>En-Nightingale 2</option>";
    $s.="</select>";
}else if ( $level=="QF1") {
	 $s.="<select name='level' id='level'>";
   $s.="   <option value=''>Select</option>";
   $s.="   <option value='En-Shakespeare'>En-Shakespeare</option>";
   $s.="   <option value='En-Edison'>En-Edison</option>";
   $s.="   <option value='En-Beethoven'>En-Beethoven</option>";
   $s.="   <option value='En-DaVinci'>En-Da Vinci</option>";
   $s.="   <option value='En-Livingston'>En-Livingston</option>";
   $s.="   <option value='En-Michelangelo'>En-Michelangelo</option>";
    $s.="</select>";
}else if ( $level=="QF2") {
	 $s.="<select name='level' id='level'>";
   $s.="   <option value=''>Select</option>";
   $s.="   <option value='En-Einstein'>En-Einstein</option>";
   $s.="   <option value='En-Mozart'>En-Mozart</option>";
   $s.="   <option value='En-GrahamBell'>En-Graham Bell</option>";
   $s.="   <option value='En-Owens'>En-Owens</option>";
   $s.="   <option value='En-Strauss'>En-Strauss</option>";
   $s.="   <option value='En-Macmillan'>En-Macmillan</option>";
    $s.="</select>";
}else if ( $level=="QF3") {
	$s.="<select name='level' id='level'>";
   $s.="   <option value=''>Select</option>";
  $s.="   <option value='En-Baird'>En-Baird</option>";
   $s.="   <option value='En-Armstrong'>En-Armstrong</option>";
   $s.="   <option value='En-Newton'>En-Newton</option>";
   $s.="   <option value='En-Wright'>En-Wright</option>";
   $s.="   <option value='En-Bach'>En-Bach</option>";
   $s.="   <option value='En-Wordsworth'>En-Wordsworth</option>";
    $s.="</select>";
}else if (($level!="")) {
   $s.="<select name='level' id='level'>";
   $s.="   <option value=''>Select</option>";
   $sql="SELECT * from course where course_name like '$programme".$level."%' and deleted=0 order by course_name";
   $result=mysqli_query($connection, $sql);
   $current_module="";
   $modules = [];
   while ($row=mysqli_fetch_assoc($result)) {
      switch ($programme) {
         case "BIEP" : $pre_module=substr($row["course_name"], 4); break;
         case "BIMP" : $pre_module=substr($row["course_name"], 4); break;
         case "IE" : $pre_module=substr($row["course_name"], 2); break;
      }

      $pre_module_array=explode("-", $pre_module);
      $pre_module=$pre_module_array[0];

      $pre_module=trim($pre_module);

      $test=substr($pre_module, -3);

      if (substr($test, 0, 1)=="M") {
         $pre_module=substr($pre_module, -3);
      } else {
         $test=substr($pre_module, -2);

         if (substr($test, 0, 1)=="M") {
            $pre_module=substr($pre_module, -2);
         }
      }

      if ($current_module!=$pre_module) {
         if ($module==$pre_module) {
            $selected="selected";
         } else {
            $selected="";
         }
		 $modules[] = $pre_module;
         $current_module=$pre_module;
      }
   }
   natsort($modules);
   foreach($modules as $pre_module){
		$s.="<option value='$pre_module' $selected>$pre_module</option>";
   }
   $s.="</select>";
} else {
   $s.="<select name='level' id='level'>";
   $s.="   <option value=''>Select Programme and Level First</option>";
   $s.="</select>";
}

echo $s;
?>