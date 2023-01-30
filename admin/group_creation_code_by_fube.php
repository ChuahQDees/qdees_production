<?php
        sleep(1);
        $course_query = mysqli_query($connection, "
           SELECT * FROM `course` WHERE `course_name` like '$course_name%';
        ");

        if($course_query) {
            if($row = $course_query->fetch_assoc()) {
                sleep(1);
                $course_id = $row['id'];
                $check_query = mysqli_query($connection, "SELECT * FROM `allocation` WHERE class_id = '$class_id' AND course_id = '$course_id';");
                if($check_query && $check_query->num_rows > 0) {
                    echo "Class already added to subject!";
                } else {
                    $allocation_query = mysqli_query($connection, "
                    INSERT INTO `allocation` (year, student_id, course_id, class_id, deleted)
                    VALUES($year, -1, $course_id, $class_id, 0);
                ");
                    if($allocation_query) {
                        sleep(1);
                        $allocation_find = mysqli_query($connection, "
                        SELECT * FROM `allocation` WHERE `course_id` = '$course_id' AND
                        `class_id` = '$class_id' and `year`='$year';
                        ");

                        if($allocation_find->num_rows > 0 && $s_row = $allocation_find->fetch_assoc()) {
                            $allocation_id = $s_row['id'];

                            sleep(1);
                            $date_query = mysqli_query($connection, "
                            INSERT INTO `dates` VALUES('$allocation_id', '$startTime', '$endTime',
                            '$startDate', '$endDate', '$weekday');
                        ");

                            if($allocation_query) {
                                echo "Done!";
                            }
                        }
                    }
                }
            }
        }
//    }

?>