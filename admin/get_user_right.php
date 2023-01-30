<?php
include_once("../mysql.php");

foreach ($_POST as $key=>$value) {
   $$key=mysqli_real_escape_string($connection, $value);
}

function getRightDesc($right) {
   switch ($right) {
      case "CanAcknowledge" : return "Can Acknowledge Order"; break;
      case "CanLogisticApprove" : return "Can Logistic Approve"; break;
      case "CanFinanceApprove" : return "Can Approve as Finance"; break;
      case "CanPacked" : return "Can Packed Product as Store"; break;
      case "CanDeliveredToLogistic" : return "Can Delivered To Logistic"; break;
      case "CanFinancePaid" : return "Can Update Payment Status"; break;
   }
}

function getName($user_name) {
   global $connection;
   $sql="SELECT name from `user` where user_name='$user_name'";
   $result=mysqli_query($connection, $sql);
   $row=mysqli_fetch_assoc($result);

   return $row["name"];
}
$sql="SELECT * from user_right where user_name='$user_name'";
//echo $sql;
$result=mysqli_query($connection, $sql);
$num_row=mysqli_num_rows($result);
?>

<script>
function doDelete(user_name, sha_id) {
   $("#btnDelete").data("user_name", user_name);
   $("#btnDelete").data("sha_id", sha_id);

//   alert($("#btnDelete").data("user_name"));
   UIkit.modal.confirm("<h2>Are you sure to continue?</h2>", function () {
      var user_name=$("#btnDelete").data("user_name");
      var sha_id=$("#btnDelete").data("sha_id");

      $.ajax({
         url : "admin/DeleteUserRight.php",
         type : "POST",
         data : "user_name="+user_name+"&id="+sha_id,
         dataType : "text",
         success : function(response, status, http) {
            var s=response.split("|");
            if (s[0]=="1") {
               UIkit.notify(s[1]);
               doSearch();
            }

            if (s[0]=="0") {
               UIkit.notify(s[1]);
            }
         },
         error : function(http, status, error) {
           UIkit.notify("Error:"+error);
         }
      });
   });  
}

</script>
<table class="uk-table">
   <tr class="uk-text-small uk-text-bold">
      <td>User Name</td>
      <td>Name</td>
      <td>Right</td>
      <td>Action</td>
   </tr>
<?php
if ($num_row>0) {
   while ($row=mysqli_fetch_assoc($result)) {
      $sha_id=sha1($row["id"]);
?>
   <tr class="uk-text-small">
      <td><?php echo $row["user_name"]?></td>
      <td><?php echo getName($row["user_name"])?></td>
      <td><?php echo getRightDesc($row["right"])?></td>
      <td>
         <a onclick="doDelete('<?php echo $row['user_name']?>', '<?php echo $sha_id?>')" id="btnDelete" data-uk-tooltip title="Delete Right"><img src="images/delete.png"></a>
      </td>
   </tr>
<?php
   }
} else {
   echo "<tr class='uk-text-small uk-text-bold'><td colspan='4'>No record found</td></tr>";
}
?>
</table>