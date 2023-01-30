<style>
.centre-container {
    position: absolute;
    top: 50%;
    left: 58%;
    transform: translateX(-50%) translateY(-50%);
}
</style>

<script>
$(document).ready(function () {
   $("#btnLogin").click(function () {
      var user_name=$("#user_name").val();
      var password=$("#password").val();

      $.ajax({
         url : "a_login.php",
         type : "POST",
         data : "user_name="+user_name+"&password="+password,
         dataType : "text",
         beforeSend : function(http) {
         },
         success : function(response, status, http) {
            var s=response.split("|");

            if (s[0]=="1") {
               location.reload();
            }

            if (s[0]=="0") {
               UIkit.notify(s[1]);
            }
         },
         error : function(http, status, error) {
            UIkit.notification("Error:"+error);
         }
      });
   });
});
</script>

<div class="uk-text-center">
   <div class="uk-text-center uk-height-1-1">
      <div class="uk-vertical-align-middle" style="width: 400px;">
         <img src="images/welcome.png">
         <div class="uk-panel uk-panel-box uk-form">
         Please login to the system to continue
            <div class="uk-form-row">
               <input class="uk-width-1-1 uk-form-large" type="text" id="user_name" placeholder="User Name" value="" autofocus name="user_name">
            </div>
            <div class="uk-form-row">
               <input class="uk-width-1-1 uk-form-large" type="password" id="password" placeholder="Password" value="" name="password">
            </div>
            <div class="uk-form-row">
               <button class="uk-width-1-1 uk-button uk-button-primary uk-button-large" id="btnLogin" name="btnLogin">Login</button>
            </div>
         </div>
      </div>
   </div>
</div>