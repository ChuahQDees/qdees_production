<script src="lib/uikit/js/jquery.js"></script>
<?php
echo 'Current PHP version: ' . phpversion();
?>
<?php
//require 'sendgrid_chs/vendor/autoload.php';
require 'sendgrid-php/vendor/autoload.php';

$email = new \SendGrid\Mail\Mail();
$email->setFrom("chuah@mindspectrum.com.my", "Q-Dees IT Support");
$email->setSubject("Reset Password");
$email->addTo("chuah@mindspectrum.com.my", "Chuah HS");
$body="<html><table>";
$body.='<tr><td><img src="http://starters.q-dees.com.my/images/Qdees.png" style="width: 30%;">';
$body.='</td></tr>';
$body.='<tr style="height: 8px;"><td>';
$body.='</td></tr>';
$body.='<tr><td>This is a test message,';
$body.='</td></tr>';
$body.='<tr style="height: 8px;"><td>';
$body.='</td></tr>';
$body.='<tr><td>Please jump off a lake.';
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
//echo $body;
$email->addContent(
    "text/html", $body
);
echo "Sssasd";
$sendgrid = new \SendGrid('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');

try {
    $response = $sendgrid->send($email);
    print $response->statusCode() . "\n";
    print_r($response->headers());
    print $response->body() . "\n";
	echo "Success!";
} catch (Exception $e) {
    echo $e->getMessage();
}

?>