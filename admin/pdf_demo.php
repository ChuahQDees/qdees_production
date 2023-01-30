<?php
require_once __DIR__ . '\..\vendor\autoload.php';
 $_GET['declaration_id'] = 4962;
 $_GET['monthyear'] = '2022-07';
 $_GET['CentreCode'] = 'MYQWESTC1C10231';
include 'declaration_pdf.php';
$content = ob_get_clean();

$mpdf = new \Mpdf\Mpdf();
$mpdf->SetDisplayMode('fullpage');
$mpdf->WriteHTML($content);
$mpdf->Output();
