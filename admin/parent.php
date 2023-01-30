<?php
if ($_SESSION["isLogin"]==1) {
   if ($_SESSION["UserType"]=="A") {
?>

<?php
   } else {
      echo "<div class='uk-margin-top uk-margin-right'><div class='uk-alert uk-alert-danger uk-text-large uk-text-bold uk-text-center'>Unauthorised access denied</div></div>";
   }
} else {
   header("Location: index.php");
}
?>