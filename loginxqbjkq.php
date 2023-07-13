<?php
if ($_SESSION["isLogin"]==1) {
   session_destroy();
}
?>
<head>
  <title>Q-Dees Login Alter</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>

<style>
.form-container {
  margin: 15% auto;
  display: block;
  width:300px;
  text-align:center;
}

body{
  margin: 0 auto;
  font-family: Arial;
  background-color: dimgrey;
  color: white;
}

.form-container input{
  margin-bottom:10px;
}

#btnLogin {
    box-shadow: none!important;
    background: #69A5F7!important;
    font-size: 1.4em;
    border: none!important;
    color: white;
    border-radius: 3px;
}
</style>

<script>
    // this is the id of the form
$(document).ready(function () {
   $("#btnLogin").click(function () {

    var user_name=$("#user_name").val();
    var password=$("#password").val();

    $.ajax({
        url : "a_loginxqbjkq.php",
        type : "POST",
        data : "user_name="+user_name+"&password="+password,
        dataType : "text",
        beforeSend : function(http) {
        },
        success : function(response, status, http) {
            var s=response.split("|");

            if (s[0]=="1") {
                window.location.href = "/";
                //location.reload();
                //alert("It's a 1");
            }else{
                document.getElementById("demo").innerHTML = "Invalid Username/Password";
            }
        },
        error : function(http, status, error) {
            document.getElementById("demo").innerHTML = "Error";
        }
        });

    });
});
</script>
<?php 
$param = $_GET['pw'];

if ($param == "internetyamero") {
?>
<form id="idForm" method="post">
    <div class="form-container">
    <section>
        <input class="form-control" id="user_name" name="user_name" type="text" placeholder="Username">
        <input class="form-control" id="password" name="password" type="password" placeholder="Password">
        <!--<input id="btnLogin" class="btn btn-block btn-success btn-lg font-weight-medium" type="submit" value="Login">-->
        <a id="btnLogin" class="btn btn-block btn-success btn-lg font-weight-medium">Login</a>
        <p id="demo" style="color:yellow"></p>
    </section>
    </div>
</form>
<?php 
} 
?>