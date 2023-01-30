<?php
    session_start();
    include_once("../mysql.php");
    $centre_code = isset($_REQUEST['centre_code']) ? $_REQUEST['centre_code'] : '';
?>

    <option value="">Select a Student or leave empty for all</option>
    <?php
        $sql="SELECT * from student where deleted=0 AND `centre_code` = '".$centre_code."'";
        $result=mysqli_query($connection, $sql);
        while ($row=mysqli_fetch_assoc($result)) {
    ?>
            <option value="<?php echo $row['student_code']?>"><?php echo $row['name']." (".$row["student_code"].")"?></option>
    <?php
        }
    ?>
