<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include_once('./models/reporte.php');
require_once __DIR__ . '/../vendor/autoload.php';

$web = new Reporte();
// $web->check('Admin');
$action = isset($_GET['action']) ? $_GET['action'] : null;

switch ($action) {
    case 'hola':
        $mpdf = new \Mpdf\Mpdf(['format' => 'A4']);
        $mpdf->addPage();
        $mpdf->SetHeader($web->encabezado());
        $mpdf->WriteHTML('<h1>Hola Mundo</h1>');
        $mpdf->SetFooter($web->pie());
        break;
    case 'marca':
        require_once('./models/marca.php');
        $marca = new Marca();
        $marcas = $marca->findAll();
        $mpdf = new \Mpdf\Mpdf(['format' => 'A4']);
        $mpdf->addPage();
        $mpdf->SetHeader($web->encabezado());
        // Show logo 
        $mpdf->Image('../public/img/logo.png', 10, 10, 50, 30);
        $mpdf->SetFont('Arial', 'B', 20);
        $mpdf->WriteHTML('<h1>Reporte de Marcas</h1>');
        $mpdf->SetFont('Arial', '', 12);
        $mpdf->WriteHTML('<p>Fecha: ' . date('d/m/Y') . '</p>');
        $mpdf->WriteHTML('<p>Hora: ' . date('H:i:s') . '</p>');
        $mpdf->WriteHTML('<h1>Marcas</h1>');
        $mpdf->WriteHTML('<table border="1" cellpadding="5" cellspacing="0">');
        $mpdf->WriteHTML('<tr><th>ID</th><th>Marca</th><th>Cantidad</th></tr>');
        foreach ($marcas as $marca) {
            $mpdf->WriteHTML('<tr>');
            $mpdf->WriteHTML('<td>' . $marca['id_marca'] . '</td>');
            $mpdf->WriteHTML('<td>' . $marca['marca'] . '</td>');
            $mpdf->WriteHTML('<td>' . $marca['cantidad'] . '</td>');
            $mpdf->WriteHTML('</tr>');
        }
        $mpdf->WriteHTML('</table>');
        $mpdf->SetFooter($web->pie());
        break;

}
$mpdf->Output('reporte.pdf', 'I');
?>