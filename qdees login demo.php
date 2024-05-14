<?php
include_once("uikit_test.php");
include_once("bootstrap.php");
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<script src="https://code.jquery.com/jquery-1.12.0.min.js"></script>

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
    
<div>
    <div>
        <div>
            <div class="h-100 d-flex align-items-center justify-content-center">
                <div class="form-container">
                    <form class="form-horizontal">
                        <!--<h3 class="title">User Login</h3>-->
                        <center><img src="images/q-dees-logo.png" alt="Q-Dees Logo" width="280 px"></center>
                        <br />
                        <div class="form-group">
                            <span class="input-icon"><i class="fa fa-user" aria-hidden="true"></i></span>
                            <input class="form-control" type="text" id="user_name" placeholder="Username" />
                        </div>
                        <div class="form-group">
                            <span class="input-icon"><i class="fa fa-lock"></i></span>
                            <input class="form-control" type="password" id="password"  placeholder="Password" />
                        </div>
                        <!--<span class="forgot-pass"><a href="#">Lost password?</a></span>-->
                        
                        <span class="forgot-pass">
                            <center>
                                <label><input type="checkbox" name="saveUserPass" id="saveUserPass">&nbsp;Save username & password</label>
                                <label><input type="checkbox" value="1" name="eula" id="eula" checked>&nbsp;I accept the terms in the <a href="doc/EULA.pdf" target="_blank">End User License Agreement</a></label>
                                <button type="button" id="btnLogin"  class="btn-grad">
                                LOGIN
                                </button>
                            </center>
                        </span>
                    </form>
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


<style>
    @import url('https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.0.1/css/bootstrap.min.css');
html,
body {
     background: url(images/testbg2.jpg) no-repeat center center fixed; 
	  -webkit-background-size: cover;
	  -moz-background-size: cover;
	  -o-background-size: cover;
	  background-size: cover;
    margin: 20px;
    height: 90%;
  overflow: hidden; /* Hide scrollbars */

}

.form-container {
    font-family: "Overpass", sans-serif;
    top: 50%;
    
}
.form-container .form-horizontal {
    background-color: #fffffff0;
    width: 550px;
    height: 550px;
    padding: 85px 55px;
    margin: 0 auto;
    border-radius: 50%;
    
}
.form-container .title {
    color: #838585;
    font-family: "Teko", sans-serif;
    font-size: 35px;
    font-weight: 400;
    text-align: center;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin: 0 0 10px 0;
}
.form-horizontal .form-group {
    background-color: rgba(255, 255, 255, 0.15);
    font-size: 0;
    margin: 0 0 15px;
    border: 1px solid #838585;
    border-radius: 25px;
    position:relative;
}
.form-horizontal .input-icon {
    color: #838585;
    font-size: 16px;
    text-align: center;
    height: 30px;
    width: 40px;
    vertical-align: bottom;
    display: inline-block;
}
.form-horizontal .form-control {
    color: #2f2f2f;
    background-color: transparent;
    font-size: 14px;
    letter-spacing: 1px;
    width: calc(100% - 55px);
    height: 45px;
    padding: 2px 10px 0 0;
    box-shadow: none;
    border: none;
    border-radius: 0;
    display: inline-block;
    transition: all 0.3s;
}
.form-horizontal .form-control:focus {
    box-shadow: none;
    border: none;
}
.form-horizontal .form-control::placeholder {
    color: #bababa;
    font-size: 13px;
    text-transform: capitalize;
}

         
.btn-grad {
    background-image: linear-gradient(to right, #EB3349 0%, #F45C43  51%, #EB3349  100%);
    font-weight: bold;
    font-size: 20px;
    margin: 10px;
    padding: 15px 65px;
    text-align: center;
    text-transform: uppercase;
    transition: 0.5s;
    background-size: 200% auto;
    color: white;            
    box-shadow: 0 0 20px #eee;
    border-radius: 40px;
    display: block;
    outline: none;
    border: none;
}

    .btn-grad:hover {
    background-position: right center; /* change the direction of the change here */
    color: #fff;
    text-decoration: none;
}

/*
.form-horizontal .btn {
    color: rgba(255, 255, 255, 0.8);
    background: rgba(235, 74, 92, 0.95);
    font-size: 15px;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 1px;
    width: 120px;
    height: 120px;
    line-height: 120px;
    margin: 0 0 15px 0;
    border: none;
    border-radius: 50%;
    display: inline-block;
    transform: translateX(30px);
    transition: all 0.3s ease;
}
*/
.form-horizontal .btn:hover,
.form-horizontal .btn:focus {
    color: #fff;
    letter-spacing: 4px;
    box-shadow: 0 0 5px rgba(0, 0, 0, 0.5);
}
.form-horizontal .forgot-pass {
    font-size: 12px;
    text-align: left;
    /*width: calc(100% - 125px);*/
    display: inline-block;
    vertical-align: top;
}
.form-horizontal .forgot-pass a {
    color: #2f79e6;
    transition: all 0.3s ease;
}
.form-horizontal .forgot-pass a:hover {
    color: #555;
    text-decoration: underline;
}
@media only screen and (max-width: 379px) {
    .form-container .form-horizontal {
        width: 100%;
    }
}


</style>