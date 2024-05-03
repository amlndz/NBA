<?php
// Verifica si se ha presionado el botón de PDF
if (isset($_POST['generate_pdf'])) {
    require_once './tcpdf/tcpdf.php';

    // Crear una nueva instancia de TCPDF
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    // Establecer el título del documento
    $pdf->SetTitle('Informe del Equipo');

    // Agregar una página
    $pdf->AddPage();

    // Establecer el logotipo
    $logo = '<img src="./assets/img/logoNBA.png" width="100" height="100" />';
    $pdf->writeHTMLCell(0, 0, '', '', $logo, 0, 1, 0, true, 'C', true);

    // Agregar cabecera
    $header = '<h1>Informe del Equipo</h1>';
    $pdf->writeHTMLCell(0, 0, '', 40, $header, 0, 1, 0, true, 'C', true);

    // Agregar contenido
    // Puedes agregar aquí cualquier información adicional que quieras en el PDF, utilizando $pdf->writeHTMLCell()

    // Agregar pie de página
    $footer = '<hr><p style="text-align:center;">Este documento fue generado automáticamente.</p>';
    $pdf->writeHTMLCell(0, 0, '', '', $footer, 0, 1, 0, true, 'C', true);

    // Salida del PDF
    $pdf->Output('InformeEquipo.pdf', 'D'); // 'D' para descargar el PDF directamente
    exit;
}
?>
