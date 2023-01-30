<?php
//print_r($_POST);
foreach ($_POST as $key=>$value) {
   $$key=$value;
}

foreach ($allocation_id as $aid) {
   echo $aid."|";
}
?>