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

    /* $fee_list = mysqli_query($connection,"SELECT `id`,`fee_id` FROM `student_fee_list`");
    
    while($row = mysqli_fetch_array($fee_list))
    {
        $fee_structure = mysqli_fetch_array(mysqli_query($connection,"SELECT * FROM `fee_structure` WHERE `id` = '".$row['fee_id']."'"));

        if(mysqli_query($connection,"UPDATE `student_fee_list` SET `registration_default` = '".$fee_structure['registration_default']."', `registration_adjust` = '".$fee_structure['registration_adjust']."', `insurance_default` = '".$fee_structure['insurance_default']."', `insurance_adjust` = '".$fee_structure['insurance_adjust']."', `uniform_default` = '".$fee_structure['uniform_default']."', `uniform_adjust` = '".$fee_structure['uniform_adjust']."', `gymwear_default` = '".$fee_structure['gymwear_default']."', `gymwear_adjust` = '".$fee_structure['gymwear_adjust']."', `q_dees_default` = '".$fee_structure['q_dees_default']."', `q_dees_adjust` = '".$fee_structure['q_dees_adjust']."', `q_bag_default` = '".$fee_structure['q_bag_default']."', `q_bag_adjust` = '".$fee_structure['q_bag_adjust']."'  WHERE `id` = '".$row['id']."'")) 
        {
            echo $row['id']." = success<br>";
        }
    } */

    $i = 1;

    $data = mysqli_query($connection,"SELECT fl.id as fl_id, s.start_date_at_centre, s.centre_code, ps.id, ps.student_id, s.student_code, f.mobile_adjust, f.subject, f.mobile_collection, f.registration_adjust, f.insurance_adjust, f.q_dees_adjust, fl.uniform_adjust, fl.gymwear_adjust, f.q_bag_adjust, f.extend_year, f.basic_adjust, fl.fee_id, fl.programme_date, fl.programme_date_end from student s, programme_selection ps, student_fee_list fl, fee_structure f  where s.id=ps.student_id and ps.id = fl.programme_selection_id and fl.gymwear_adjust = 37 and fl.uniform_adjust != 98 and f.id=fl.fee_id group by ps.id");

    while($row=mysqli_fetch_array($data))
    {
        $year = date('Y',strtotime($row['start_date_at_centre']));
        $month = date('m',strtotime($row['start_date_at_centre']));

        $year_data = mysqli_fetch_array(mysqli_query($connection,"SELECT `year` FROM `schedule_term` WHERE ('".date('Y-m-d',strtotime($year.'-'.$month))."' BETWEEN `term_start` AND `term_end`) AND `centre_code` = '".$row['centre_code']."' AND `deleted` = 0"));

        $start_year = $year_data['year'];

        $flag = 1;
        
        if($start_year != $row['extend_year']) { $flag = 0; }

        if($flag == 1) 
        { 
            $sql = "select sum(amount) + sum(discount) as collection,collection_month, fee_structure_id from ( SELECT c.amount, c.discount, c.`collection_month`, f.id as fee_structure_id FROM `collection` c inner join programme_selection p on p.id = c.`allocation_id` and p.student_id = c.`student_id` inner join student_fee_list fl on p.id = fl.programme_selection_id inner join fee_structure f on fl.fee_id where c.void=0 and c.student_id = '".$row['student_id']."' and f.id = '".$row['fee_id']."' and  c.year = f.extend_year and c.void = 0 and c.product_code = 'Uniform (2 sets)' group by c.id ) ab group by collection_month, fee_structure_id";
						
            $result1=mysqli_query($connection, $sql);
            $IsRow=mysqli_num_rows($result1);
            $row_collection=mysqli_fetch_assoc($result1);

            if($IsRow<1){

                mysqli_query($connection,"UPDATE `student_fee_list` SET `uniform_default` = 98, `uniform_adjust` = 98 WHERE `id` = '".$row['fl_id']."'");

                echo $i.' = '.$row['fl_id'].' = '.$row['centre_code'].'<br>';
                $i++;
            } 
        } 
    }
?>