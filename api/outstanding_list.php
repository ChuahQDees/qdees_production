<?php
include_once("../mysql.php");
include_once("basic_auth.php");

global $connection;

try {
  $centre_code = $_GET['centre_code'];
  $page = (int)$_GET['page'];
  $order_by = $_GET['order_by'];
  $order_dir = !isset($_GET['order_dir']) ? 'asc' : $_GET['order_dir'];

  $limit = '';
  if(isset($_GET['page'])){
    $page = (int)$_GET['page'];
    if ($page > 1) {
      $offset = (($page-1)*25) + 1;
    }else{
      $page = 0;
      $offset = 0;
    }
    $limit = "LIMIT $offset, 25";
  }


  if ($centre_code) {

    $sql="SELECT * from allocation a
      inner join student s ON a.student_id=s.id
      INNER JOIN course c ON a.course_id=c.id
      left JOIN `group` g ON a.group_id=g.id
      LEFT JOIN collection cl ON (cl.course_id=c.id and cl.centre_code=s.centre_code AND cl.allocation_id=a.id AND cl.student_id=s.id )
      where cl.centre_code='$centre_code' and s.student_status='A' and c.deleted=0 and s.deleted=0 and a.deleted=0 and cl.collection_type='tuition' ORDER BY `cl`.`collection_date_time` DESC $limit ";

  }else{

    $sql="SELECT * from allocation a
      inner join student s ON a.student_id=s.id
      INNER JOIN course c ON a.course_id=c.id
      left JOIN `group` g ON a.group_id=g.id
      LEFT JOIN collection cl ON (cl.course_id=c.id and cl.centre_code=s.centre_code AND cl.allocation_id=a.id AND cl.student_id=s.id )
      where s.student_status='A' and c.deleted=0 and s.deleted=0 and a.deleted=0 and cl.collection_type='tuition' ORDER BY `cl`.`collection_date_time` DESC $limit ";
  }
   
  // var_dump($sql);


$result=mysqli_query($connection, $sql);
$totalOutstanding = mysqli_num_rows($result);
 

$count=0;
$branch_total = 0;

  if ($totalOutstanding) {
while ($c_row=mysqli_fetch_assoc($result)) {


  $data[] = $c_row;

  // var_dump($c_row);

  $o = array(
      'status' => 'OK',
      'message' => 'Outstanding found',
      'total' => $totalOutstanding,
      'data' => $data
    );
     
      
}
}else{
    throw new Exception("Student list empty");
  }
}catch (\Exception $e) {
  $o = array(
    'status' => 'ERROR',
    'message' => $e->getMessage(),
  );
}

echo json_encode($o, JSON_PRETTY_PRINT);

?>