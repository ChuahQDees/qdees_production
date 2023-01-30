<?php
session_start();
include_once("../mysql.php");

if ($_SESSION["isLogin"]==1) {
   if ($_SESSION["UserType"]=="S") {
?>
<script>
function doAdd() {
   var user_name=$("#user_name").val();
   var rights=$("#rights").val();

   if ((user_name!="") & (rights!="")) {
      $.ajax({
         url : "admin/AddRights.php",
         type : "POST",
         data : "user_name="+user_name+"&user_right="+rights,
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
   }
}

function doSearch() {
   var user_name=$("#user_name").val();

   if (user_name!="") {
      $.ajax({
         url : "admin/get_user_right.php",
         type : "POST",
         data : "user_name="+user_name,
         dataType : "text",
         success : function(response, status, http) {
            $("#sctUserRight").html(response);
         },
         error : function(http, status, error) {
           UIkit.notify("Error:"+error);
         }
      });
   } else {
      UIkit.notify("Please fill in User Name");
   }
}
</script>

<div class="uk-margin-right uk-margin-top uk-form">
   <div class="uk-width-1-1 myheader">
      <h2 class="uk-text-center myheader-text-color">User Right</h2>
   </div>

<?php
$sql="SELECT * from user where user_type='S' order by user_name";
$result=mysqli_query($connection, $sql);
?>
   <select name="user_name" id="user_name">
      <option value="">Select</option>
<?php
while ($row=mysqli_fetch_assoc($result)) {
?>
      <option value="<?php echo $row["user_name"]?>"><?php echo $row["name"]?></option>
<?php
}
?>
   </select>

   <select name="rights" id="rights">
      <option value="">Select</option>
      <option value="CanAcknowledge">Can Acknowledge Order</option>
      <option value="CanLogisticApprove">Can Logistic Approve</option>
      <option value="CanFinanceApprove">Can Finance Approved</option>
      <option value="CanPacked">Can Packed</option>
      <option value="CanDeliveredToLogistic">Can Delivered to Logistic</option>
      <option value="CanFinancePaid">Can Update Payment Status</option>
   </select>
   <a onclick="doSearch()" id="btnSearch" class="uk-button">Search</a>
   <a onclick="doAdd()" id="btnAdd" class="uk-button">Add Right</a>

   <div id="sctUserRight"></div>
</div>
<?php
} else {
   echo "<div class='uk-margin-top uk-margin-right'><div class='uk-alert uk-alert-danger uk-text-large uk-text-bold uk-text-center'>Unauthorised access denied</div></div>";
}
} else {
   echo "<form method='post' id='frmLogin' action='index.php'></form><script>$('#frmLogin').submit();</script>";
}
?>