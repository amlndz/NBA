<?php
if (isset($_POST['generate_pdf'])) {
    session_start();
    require_once './tcpdf/tcpdf.php';
    $teams = $_SESSION['teams'];

    // Crear una nueva instancia de TCPDF
    $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

    // Establecer el título del documento
    $pdf->SetTitle('Equipos NBA');

    // Agregar una página
    $pdf->AddPage();

    // Agregar logotipo y cabecera
    $pdf->Image('./assets/img/logoNBA2.png', 10, 10, 20, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false); // Reducir el tamaño del logotipo
    $pdf->SetFont('helvetica', 'B', 20);
    $pdf->Cell(0, 25, 'Equipos NBA', 0, true, 'C');

    // Agregar contenido
    $pdf->SetFont('helvetica', '', 12);
    $htmlContent = '<table border="1"> <!-- Aumentar el valor del margen superior -->
                        <thead>
                            <tr style="background-color: #D3D3D3;"> <!-- Añadir color de fondo a la primera fila -->
                                <th>Nombre Completo</th>
                                <th>Abreviatura</th>
                                <th>Ciudad</th>
                                <th>Conferencia</th>
                                <th>División</th>
                            </tr>
                        </thead>
                        <tbody>';

    // Iterar sobre los equipos
    foreach ($teams as $row) {
        $htmlContent .= '<tr>
                            <td>'.$row['full_name'].'</td>
                            <td>'.$row['abbreviation'].'</td>
                            <td>'.$row['city'].'</td>
                            <td>'.$row['conference'].'</td>
                            <td>'.$row['division'].'</td>
                        </tr>';
    }

    // Cerrar la tabla
    $htmlContent .= '</tbody></table>';

    // Escribe el contenido HTML
    $pdf->writeHTML($htmlContent, true, false, true, false, '');

    // Salida del PDF
    $pdf->Output('EquiposNBA.pdf', 'D'); // 'D' para descargar el PDF directamente
    exit;
}
