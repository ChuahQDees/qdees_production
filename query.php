<?php
    include_once("mysql.php");

error_reporting(E_ALL);

    /* $where = "";

    $query = "";

    $query_array = explode(";",$query);

    foreach($query_array as $key => $value)
    {
        $q = mysqli_query($connection,$value);

        if($q) {
            echo "success<br>";
        } else {
            echo "failed = " .$value. "<br>";
        }
    } */

    $fee_list = mysqli_query($connection,"SELECT `id`,`fee_id` FROM `student_fee_list`");
    
    while($row = mysqli_fetch_array($fee_list))
    {
        $fee_structure = mysqli_fetch_array(mysqli_query($connection,"SELECT * FROM `fee_structure` WHERE `id` = '".$row['fee_id']."'"));

        if(mysqli_query($connection,"UPDATE `student_fee_list` SET `registration_default` = '".$fee_structure['registration_default']."', `registration_adjust` = '".$fee_structure['registration_adjust']."', `insurance_default` = '".$fee_structure['insurance_default']."', `insurance_adjust` = '".$fee_structure['insurance_adjust']."', `uniform_default` = '".$fee_structure['uniform_default']."', `uniform_adjust` = '".$fee_structure['uniform_adjust']."', `gymwear_default` = '".$fee_structure['gymwear_default']."', `gymwear_adjust` = '".$fee_structure['gymwear_adjust']."', `q_dees_default` = '".$fee_structure['q_dees_default']."', `q_dees_adjust` = '".$fee_structure['q_dees_adjust']."', `q_bag_default` = '".$fee_structure['q_bag_default']."', `q_bag_adjust` = '".$fee_structure['q_bag_adjust']."'  WHERE `id` = '".$row['id']."'")) 
        {
            echo $row['id']." = success<br>";
        }
    }

?>