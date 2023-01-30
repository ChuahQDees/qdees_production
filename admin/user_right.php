<?php
//session_start();
include_once("../mysql.php");
?>

<script>
   $(function() {
      $("#btnGetRights").click(function() {
         var user_name = $("#user_name").val();

         if (user_name != "") {
            $.ajax({
               url: "admin/getUserRights.php",
               type: "POST",
               data: "user_name=" + user_name,
               dataType: "text",
               success: function(response, status, http) {
                  $("#sctRights").html(response);
               },
               error: function(http, status, error) {
                  UIkit.notify("Error:" + error);
               }
            });
         } else {
            UIkit.notify("Please select a user");
         }
      });
   });
</script>
<?php
if ((($_SESSION["UserType"] == "A") || ($_SESSION["UserType"] == "S") || ($_SESSION["UserType"] == "H") || ($_SESSION["UserType"] == "C") || ($_SESSION["UserType"] == "R") || ($_SESSION["UserType"] == "CM") || ($_SESSION["UserType"] == "T")) & (hasRightGroupXOR($_SESSION["UserName"], "UserRightsEdit|UserRightsView"))) {
?>
   <a href="index.php?p=user">
      <span class="btn-qdees"><i class="fa fa-arrow-left" style="margin-right: 20px;"></i>Back</span>
   </a>
   <span>
      <span class="page_title"><img src="/images/title_Icons/Visitor Reg.png">User Access Control</span>
   </span>

   <div class="uk-margin-right uk-margin-top uk-form">
      <div class="uk-width-1-1 myheader">
         <h2 class="uk-text-center myheader-text-color">User Right</h2>
      </div>
   </div><br>

   <div class="uk-form">
      <select style="display:none" name="user_name" id="user_name">
         <option value="">Select a User</option>
         <?php
         $user_name = $_GET["user_name"];
         if (($_SESSION["UserType"] == "S") || ($_SESSION["UserType"]=="H") || ($_SESSION["UserType"]=="C") || ($_SESSION["UserType"]=="R") || ($_SESSION["UserType"]=="CM") || ($_SESSION["UserType"]=="T")) {
            $sql = "SELECT * from user where is_active=1";
         } else {
            if ($_SESSION["UserType"] == "A") {
               $sql = "SELECT * from user where is_active=1 and centre_code='" . $_SESSION["CentreCode"] . "'";
            }
         }
         $result = mysqli_query($connection, $sql);
         while ($row = mysqli_fetch_assoc($result)) {
            if ($user_name == "") {
         ?>
               <option value="<?php echo $row['user_name'] ?>"><?php echo $row["user_name"] ?></option>
            <?php
            } else {
            ?>
               <option value="<?php echo $row['user_name'] ?>" <?php if ($user_name == $row['user_name']) {
                                                                  echo "selected";
                                                               } ?>><?php echo $row["name"] ?></option>
         <?php
            }
         }
         ?>
      </select>
      <a id="btnGetRights" style="display:none" class="uk-button">List Rights</a>
      <div id="sctRights"></div>
   </div>

   <script>
      $(function() {
         $("#btnGetRights").click();
      });
   </script>
<?php
} else {
   echo "<div class='uk-margin-top uk-margin-right'><div class='uk-alert uk-alert-danger uk-text-large uk-text-bold uk-text-center'>Unauthorised access denied</div></div>";
}
?>