
<link rel="icon" type="image/ico" href="images/title.png" />
<?php
//include_once("uikit.php");
include_once("bootstrap.php");
?>
<script>
$(document).ready(function (){

  $('#password').keypress(function(event) {
    if (event.keyCode == 13) {
        event.preventDefault();
    }
});

	
   $("#show-pass").click(function (e) {
	   x = $('#password');
		if (x.attr('type') == "password") {
			x.attr('type', "text");
		} else {
			x.attr('type', "password");
		}
		e.preventDefault();
   });
   $("#btnLogin").click(function () {
      var user_name=$("#user_name").val();
      var password=$("#password").val();
      var eula=$("#eula").val();

      if ($("#eula").prop("checked")==true) {
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
               UIkit.notify("Error:"+error);
            }
         });
      } else {
         UIkit.notify("Please accept End User License Agreement to proceed");
      }
   });
});
</script>

<style>
.login-content {
    width: 70vw;
    min-height: 350px;
    padding-right: 15px;
    border-radius: 40px;
    box-shadow: 0px 13px 21px -10px rgba(0, 0, 0, 0.15)!important;
    position: relative;
    background: white;
}
.login-container {
    width: 100%;
    height: 100vh;
}
body {background: none!important;}

.login_input_img, .login_input_eye {
    padding: 10px 0px 10px 10px;
    width: 100%;
    position: relative;
    border: 1px solid rgba(0,0,0,.3);
}

// .login_input_img {
    // background: white url(images/001-man-user.svg) right no-repeat;
    // background-size: 1rem;
    // background-position-x: 97%;
// }
#show-pass {
    background: white url(images/002-eye.svg) right no-repeat;
    background-size: 1rem;
    background-position-x: 97%;
	width: 1rem;
	height: 1rem;
	border: none;
	margin-left: -2rem;
	cursor: pointer;
	position: relative;
}

.login-content::after {
    content: '';
    position: absolute;
    top: -0.8vw;
    left: -2.0vw;
    width: 52%;
    min-height: 102%;
    background: #ef66d8;
    z-index: 0;
    border-top-left-radius: 40px;
    border-bottom-left-radius: 40px;
}

.login-content::before {
    content: '';
    position: absolute;
    bottom: -0.8vw;
    right: -1.0vw;
    width: 52%;
    min-height: 102%;
    background: #FDD05C;
    z-index: 0;
    border-bottom-right-radius: 40px;
    border-top-right-radius: 40px;
}

input:focus {
    border: 1px solid rgba(0,0,0,.3)!important;
}

input {
    border-radius: 3px;
}

#btn_reset_email {
    box-shadow: none!important;
    background: #69A5F7!important;
    font-size: 1.4em;
    border: none!important;
    color: white;
    border-radius: 3px;
}

@media (max-width: 414px) {
    .login-content {
        padding-right: 0px;
    }
}

.login-container {
    background-image: url(/images/background.png);
    background-size: cover;
    background-repeat: no-repeat;
}

.login-content img {
    border-top-left-radius: 40px;
    border-bottom-left-radius: 40px;
}

.divider {
    width: 65%;
    display: block;
    margin: 0 auto;
    height: 1px;
    background: #3399ff;
}

.row.position-relative {
    z-index: 1;
    border-radius: 40px;
    box-shadow:  0px 2px 6px 1px rgba(0, 0, 0, 0.3)!important;
    background: white;
}

.no-padding {
    padding: 0px!important;
}

.eng_and_maths{
   display:block;
}
.eng_and_maths img{
   border-top-left-radius: 0px;
   border-bottom-left-radius: 0px;
}

.img_bg{
   position: relative;
    top: 0;
    left: 0;

}

.img_logo{
   position: absolute;
   top: 30%;
   width:380px;
}
</style>

<!--  -->
<?php

	include_once("mysql.php");
	
	$email_address = $_GET['email'];
	$reset_key = $_GET['reset_key'];
	$current_time = date('Y-m-d H:i:s');
	
	$sql = "SELECT * FROM `reset_password_log` WHERE email='$email_address' and reset_key='$reset_key' and expire_time >='$current_time' and is_active = 1 ";
	$result = mysqli_query($connection, $sql);
	if(!mysqli_num_rows($result)){
		echo '<script type="text/javascript">
           window.location = "http://starters.q-dees.com.my/"
      </script>';
	}
?>
<div class="login-container d-flex justify-content-center align-items-center">
    <div class="login-content">
        <div class="row position-relative">
            <div class="col-md-6 col-sm-12 no-padding" style="background:#F7FCFF;border-radius: 50px 0px 0px 50px;">
            <div style="width: 100%; height:87%" class="pt-3 d-flex justify-content-center">
               <img src="/images/Qdees_Globe.png" alt="" class="img_bg" style="max-width: 100%;">
               <img src="/images/Q-dees-Starters-Logo_o.png" alt="" class="img_logo">
            </div>
            <h2 style="text-align:center; padding-bottom:20px;"><a href="https://www.q-dees.com/" style="color:#707070;">www.q-dees.com</a></h2>
            </div>
            <div style="min-height: 375px;" class="col-md-6 col-sm-12 d-flex justify-content-center align-items-center">
                <div style="width: 100%">
                    <h2 class="mt-4" style="color: #3399ff; text-align:center">Reset Password</h2>
                    <div class="divider"></div>
					<h4 class="mt-4" style="color: #3399ff; text-align:center">Enter a new password to continue</h4>
                    <form id="frmVisitor"  class="pt-5" style="width: 90%" action="reset_password_func.php" Method="POST">
                        <div class="form-group-login">
                            <label for="password">Password</label><br>
                            <input class="login_input_img" type="password" id="password" name="password">
							 <span id="validationPass"  style="color: red; display: none;">Please insert Password</span>
							<input class="login_input_img" type="hidden" id="email" name="email_" value="<?php echo $email_address; ?>" >
                        </div>
						<div class="form-group-login">
                            <label for="confirm_password">Confirm Password</label><br>
                            <input class="login_input_img" type="password" id="confirm_password" name="confirm_password" aria-describedby="emailHelp" >
							<span id="validationcon_pass"  style="color: red; display: none;">Please insert Confirm Password</span>
							<span id="validationcon_pass_match"  style="color: red; display: none;">Password is not matching</span>
                        </div>

                        <div class="mt-5">
                            <button type="submit" id="btn_reset_email" class="btn btn-block btn-success btn-lg font-weight-medium">Reset</button>
                        </div>
                    </form>

                    <div style="padding-bottom: 20px;padding-top: 20px;" class="mt-3 eng_and_maths">
                        <img src="/images/Artboard – 1.png" alt="" style="width: 43%;">
                        <img src="/images/Artboard – 3.png" alt="" style="margin-left: -20px; margin-right: -20px; position: relative;width:50px; ">
                        <img src="/images/Artboard – 2.png" alt="" style="width: 43%;">
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<script src="lib/uikit/js/jquery.js"></script>
<script>
	  $('#frmVisitor').submit(function() {
			  
		  var password = $('#frmVisitor').find('input[name="password"]').val();
		  var confirm_password = $('#frmVisitor').find('input[name="confirm_password"]').val();
		  if (!password || !confirm_password) {
			if (!password) {
				$('#validationPass').show();
			}else{
				$('#validationPass').hide();
			}
			var confirm_password = $('#frmVisitor').find('input[name="confirm_password"]').val();
			if (!confirm_password) {
				$('#validationcon_pass').show();
				$('#validationcon_pass_match').hide();
			}else{
				$('#validationcon_pass').hide();
			}
			return false;
		  }
			if(password != confirm_password){	
				$('#validationcon_pass').hide();				
				$('#validationcon_pass_match').show();
				return false;
			}else{
				$('#validationcon_pass_match').hide();
			}

	  });
</script>