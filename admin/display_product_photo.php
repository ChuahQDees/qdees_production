<?php
include_once("../mysq.php");

$sha1_id=$_POST["id"];

$sql="SELECT * from product where sha1($id)='$sha1_id'";
$result=mysqli_query($connection, $sql);

$row=mysqli_fetch_assoc($result);

if ($row["product_photo"]!="") {
   $filename="admin/uploads/".$row["product_photo"];

   $source = imagecreatefromjpeg($filename);
   list($width, $height) = getimagesize($filename);
   
   $newwidth = $width/5;
   $newheight = $height/5;

   $destination = imagecreatetruecolor($newwidth, $newheight);
   imagecopyresampled($destination, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

   header("Content-type: image/jpg");

   imagejpeg($destination);
}
?>