<?php
//include_once("uikit.php");
include_once("bootstrap.php");
?>
<script>
$(document).ready(function () {
    $(document).on('keypress',function(e) {
        if(e.which == 13) {
            if ($("input").is(":focus")) {
                //alert('You pressed enter!');
                loginPOS();
            }
        }
    });

   $("#btnLogin").click(function () {
        loginPOS();
   });
});

function getCookie(cname) {
  let name = cname + "=";
  let decodedCookie = decodeURIComponent(document.cookie);
  let ca = decodedCookie.split(';');
  for(let i = 0; i <ca.length; i++) {
    let c = ca[i];
    while (c.charAt(0) == ' ') {
      c = c.substring(1);
    }
    if (c.indexOf(name) == 0) {
      return c.substring(name.length, c.length);
    }
  }
  return "";
}

function checkCookie() {
  let username = getCookie("username");
  if (username != "") {
   //alert("Cookie found "+username);
   return "1";
  }else{
    //alert("Cookie not found");
    return "0";
  }
}

function loginPOS() {
    var user_name=$("#user_name").val();
    var password=$("#password").val();
    var eula=$("#eula").val();
    var saveUserPass=$("#saveUserPass").val();

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
                const d = new Date();

                if ($("#saveUserPass").prop("checked")==true) {
                    //Create the cookies

                    d.setTime(d.getTime() + (100 * 24 * 60 * 60 * 1000));
                    let expires = "expires="+d.toUTCString();
                    document.cookie = "username=" + user_name + ";" + expires + ";path=/";
                    document.cookie = "password=" + password + ";" + expires + ";path=/";
                }else{
                    document.cookie = "username=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
                    document.cookie = "password=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
                    ///alert("ooo");
                    //checkCookie();
                }
                //checkCookie();
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
}
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

.login_input_img {
    background: white url(/images/001-man-user.svg) right no-repeat;
    background-size: 1rem;
    background-position-x: 97%;
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

#btnLogin {
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
    width: 50%;
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
.eng_and_maths {
    white-space: nowrap;
}
</style>

<!--  -->

<div class="login-container d-flex justify-content-center align-items-center">
    <div class="login-content">
        <div class="row position-relative">
            <div class="col-md-6 col-sm-12 no-padding" style="background:#F7FCFF;border-radius: 50px 0px 0px 50px;">
            <div style="width: 100%; height:87%" class="pt-3 d-flex justify-content-center">
               <img src="/images/Qdees_Globe.png" alt="" class="img_bg">
               <img src="/images/Qdees-logo-n.png" alt="" class="img_logo">
            </div>
            <h2 style="text-align:center; padding-bottom:20px;"><a href="https://www.q-dees.com/" style="color:#707070;">www.q-dees.com</a></h2>
            </div>
            <div style="min-height: 375px;" class="col-md-6 col-sm-12 d-flex justify-content-center align-items-center">
                <div style="width: 100%">
                    <h2 class="mt-4" style="color: #3399ff; text-align:center">Welcome to POS system</h2>
                    <div class="divider"></div>
                    <form class="pt-5" style="width: 90%">
                        <div class="form-group-login">
                            <label for="user_name">Username</label><br>
                            <input class="login_input_img" type="text" id="user_name" aria-describedby="emailHelp" >
                        </div>

                        <br>
						<!--<div href="#" class="text-black" style="margin-top: 1rem;font-size:12px;">
                          <div class="form-group">
                           <input type="checkbox" value="1" name="eula" id="eula">&nbsp;I accept the terms in the <a href="doc/EULA.pdf" target="_blank">End User License Agreement</a>
                          </div>
                        </div>-->
						<div class="form-group">
                           <label for="password">Password</label><br>
                           <input class="login_input_eye" type="password" id="password" >
                           <!--<a href="./forgot_password.php" class="auth-link text-black" style="float:right;">Forgot password?</a>-->
                        </div>
						<div class="text-black mt-12" style="margin-top: 1rem;font-size:12px;">
                          <div class="form-group">
                            <label><input type="checkbox" name="saveUserPass" id="saveUserPass">&nbsp;Save username & password</label>
                          </div>
                          <div class="form-group">
                           <label><input type="checkbox" value="1" name="eula" id="eula" checked>&nbsp;I accept the terms in the <a href="doc/EULA.pdf" target="_blank">End User License Agreement</a></label>
                          </div>
                        </div>                       

                        <div class="mt-5">
                            <a id="btnLogin" class="btn btn-block btn-success btn-lg font-weight-medium">Login</a>
                        </div>
                        <!--
						 <div class="form-group">
                           <a href="./forgot_password.php" class="auth-link text-black" style="float:right;">Forgot password?</a>
                        </div>-->
                        
                    </form>

                    <div style="padding-bottom: 20px;padding-top: 20px;" class="mt-3 eng_and_maths">
                        <!-- <img src="/images/Artboard – 1.png" alt="" style="width: 43%;">
                        <img src="/images/Artboard – 3.png" alt="" style="margin-left: -20px; margin-right: -20px; position: relative;width:50px; ">
                        <img src="/images/Artboard – 2.png" alt="" style="width: 43%;"> -->
                    </div>
					<!--<div class="mt-3 eng_and_maths">
                        <img src="/images/Inter_English.png" alt="" style="width: 43%;">
                        <img src="/images/or_logo.png" alt="" style="margin-left: -20px; margin-right: -20px; position: relative;width:50px; ">
                        <img src="/images/Iq_Math.png" alt="" style="width: 43%;">
                    </div>-->
                </div>

            </div>
        </div>
    </div>
</div>

<script>
//Autofill fields if there's cookies inside
var cookieCheck = checkCookie();

if (cookieCheck == "1"){
    let username = getCookie("username");
    document.getElementById("user_name").value = username;
    let password = getCookie("password");
    document.getElementById("password").value = password;
    document.getElementById("saveUserPass").checked = true;
}
</script>
