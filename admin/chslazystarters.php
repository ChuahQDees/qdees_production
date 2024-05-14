<style>
	body{
		font-family: "Courier New"
	}
</style>
<body>
<?php
//Because Chuah can't honestly bother adding everything one by one

include_once("../mysql.php");

echo "testabs";
$sql="UPDATE `centre`
SET company_name = REPLACE(company_name, 'Starters ', '')
WHERE company_name LIKE 'STARTERS%'";
   
$result=mysqli_query($connection, $sql);

echo $result;
?>
</body>