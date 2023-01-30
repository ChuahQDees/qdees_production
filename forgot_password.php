<link rel="icon" type="image/ico" href="images/title.png" />
<?php
include_once("mysql.php");
include_once("uikit.php");
include_once("bootstrap.php");

?>



<div class="login-container d-flex justify-content-center align-items-center">
    <div class="login-content">
        <div class="row position-relative">
            <div class="col-md-6 col-sm-12 no-padding" style="background:#F7FCFF;border-radius: 50px 0px 0px 50px;">
                <div style="width: 100%; height:87%" class="pt-3 d-flex justify-content-center">
                    <img src="/images/Qdees_Globe.png" alt="" class="img_bg">
                    <img src="/images/Q-dees Starters Logo.png" alt="" class="img_logo">
                </div>
                <h2 style="text-align:center; padding-bottom:20px;"><a href="https://www.q-dees.com/" style="color:#707070;">www.q-dees.com</a></h2>
            </div>
            <div style="min-height: 375px;" class="col-md-6 col-sm-12 d-flex justify-content-center align-items-center">
                <div style="width: 100%">
                    <h2 class="mt-4" style="color: #3399ff; text-align:center">Let's get you into your account!</h2>
                    <div class="divider"></div>
                    <h4 class="mt-4" style="color: #3399ff; text-align:center">Enter your email to continue</h4>
                    <form class="pt-5" style="width: 90%" action="forgot_password.php" Method="POST">
                        <div class="form-group-login">
                            <label for="forgit_email">Email</label><br>
                            <input class="login_input_img" type="email" name="email" id="forgit_email">
                        </div>

                        <div class="mt-5">
                            <button type="submit" id="btn_forgit_email" class="btn btn-block btn-success btn-lg font-weight-medium">Continue</button>
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
<?php

function keygen($length = 16)
{
    $key = '';
    list($usec, $sec) = explode(' ', microtime());
    mt_srand((float) $sec + ((float) $usec * 100000));

    $inputs = array_merge(range('z', 'a'), range(0, 9), range('A', 'Z'));

    for ($i = 0; $i < $length; $i++) {
        $key .= $inputs{
        mt_rand(0, 61)};
    }
    return $key;
}
if ($_POST) {
    $email = $_POST['email'];
    $reset_key = keygen(20);
	//echo $reset_key;
    $is_active = 1;
    $expire_time = date("Y-m-d H:i:s", strtotime('+2 hours'));

    $sql = "SELECT * FROM user where email='$email'";
    $result = mysqli_query($connection, $sql);

    $count = mysqli_num_rows($result);
    $row = mysqli_fetch_assoc($result);
    $emailq = $row["email"];
    $user_name = $row["user_name"];
    if ($emailq == '') {
        //echo "Message sent successfully...";
        echo '<script type="text/javascript">			
					   UIkit.notify("The email is not registered")		
				   
					</script>';
    }
    if ($count > 0) {
        $sql_insert = "INSERT INTO reset_password_log (email, reset_key, is_active, expire_time) VALUES ('$email', '$reset_key', '$is_active', '$expire_time')";
        mysqli_query($connection, $sql_insert);
    }

    $sql = "SELECT * FROM reset_password_log where email='$email' and is_active = 1 ";
    //echo $sql;
    $result = mysqli_query($connection, $sql);
    $row = mysqli_fetch_assoc($result);
    //print_r($row);
    $is_active = $row["is_active"];
    $reset_key = $row["reset_key"];

    $to = $row["email"];
    if ($is_active > 0) {
        $to = $to;

       require 'sendgrid-php/vendor/autoload.php'; // If you're using Composer (recommended)

$email = new \SendGrid\Mail\Mail();
$email->setFrom("support@q-dees.com", "Q-dees");
$email->setSubject("Reset Password");
$email->addTo($to, $user_name);
$body="<html><table>";
$body.='<tr><td><img src="http://starters.q-dees.com.my/images/Qdees.png" style="width: 30%;">';
$body.='</td></tr>';
$body.='<tr style="height: 8px;"><td>';
$body.='</td></tr>';
$body.='<tr><td>Hi ' . $user_name .',';
$body.='</td></tr>';
$body.='<tr style="height: 8px;"><td>';
$body.='</td></tr>';
$body.='<tr><td>To reset your account password for Q-dees P.O.S system. Click on the link below:<br> http://starters.q-dees.com.my/reset_password.php?email=' .$to.'&reset_key=' . $reset_key.'';
$body.='</td></tr>';
$body.='<tr style="height: 26px;"><td>';
$body.='</td></tr>';
$body.='<tr><td>If you did not make this request, please ignore this email or let support@q-dees.com know.';
$body.='</td></tr>';
$body.='<tr style="height: 8px;"><td>';
$body.='</td></tr>';
$body.='<tr><td>This password link is only valid for the next 30 minutes.';
$body.='</td></tr>';
$body.='<tr style="height: 8px;"><td>';
$body.='</td></tr>';
$body.='<tr><td>Thanks.';
$body.='</td></tr>';
$body.='<tr style="height: 8px;"><td>';
$body.='</td></tr>';
$body.='<tr><td>Q-dees support team';
$body.='</td></tr>';
$body.='</table></html>';
// $email->addContent(
    // "text/plain", "you activation link is:http://scholars.q-dees.com.my/reset_password.php?email=$to&reset_key=$reset_key"
// );
// $email->addContent(
    // "text/html", "you activation link is:http://scholars.q-dees.com.my/reset_password.php?email=$to&reset_key=$reset_key"
// );
$email->addContent(
    "text/html", $body
);
$sendgrid = new \SendGrid('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');
try {
    $response = $sendgrid->send($email);
    print $response->statusCode() . "\n";
    print_r($response->headers());
    print $response->body() . "\n";
	echo '<script type="text/javascript">
		   UIkit.notify("Kindly check your email for a link to create new password.")
			setTimeout(function() { 
				window.location = "http://starters.q-dees.com.my/"
			}, 2000);
	   
		</script>';
} catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), "\n";
}
		
    }
}
?>


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

    body {
        background: none !important;
    }

    .login_input_img,
    .login_input_eye {
        padding: 10px 0px 10px 10px;
        width: 100%;
        position: relative;
        border: 1px solid rgba(0, 0, 0, .3);
    }

    
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
        border: 1px solid rgba(0, 0, 0, .3) !important;
    }

    input {
        border-radius: 3px;
    }

    #btn_forgit_email {
        box-shadow: none !important;
        background: #69A5F7 !important;
        font-size: 1.4em;
        border: none !important;
        color: white;
        border-radius: 3px;
    }

    @media (max-width: 414px) {
        .login-content {
            padding-right: 0px;
        }
    }

    // .login-container {
        // background-image: url(/images/backgroundLogin.png);
        // background-size: cover;
        // background-repeat: no-repeat;
    // }

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
        box-shadow: 0px 2px 6px 1px rgba(0, 0, 0, 0.3) !important;
        background: white;
    }

    .no-padding {
        padding: 0px !important;
    }

    .eng_and_maths {
        display: block;
    }

    .eng_and_maths img {
        border-top-left-radius: 0px;
        border-bottom-left-radius: 0px;
    }

    .img_bg {
        position: relative;
        top: 0;
        left: 0;

    }

    .img_logo {
        position: absolute;
        top: 30%;
        width: 380px;
    }
</style>

<!--  -->