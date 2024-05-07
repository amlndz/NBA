<?php
if (isset($_POST['generate_pdf'])) {
    session_start();
    require_once './tcpdf/tcpdf.php';
    $games = $_SESSION['games'];

    // Crear una nueva instancia de TCPDF
    $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

    // Establecer el título del documento
    $pdf->SetTitle('Partidos NBA');

    // Agregar una página
    $pdf->AddPage();

    // Agregar logotipo y cabecera
    $pdf->Image('./assets/img/logoNBA2.png', 10, 10, 20, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false); // Reducir el tamaño del logotipo
    $pdf->SetFont('helvetica', 'B', 20);
    $pdf->Cell(0, 25, 'Partidos NBA', 0, true, 'C');

    // Agregar contenido
    $pdf->SetFont('helvetica', '', 12);
    $htmlContent = '<table border="1"> <!-- Aumentar el valor del margen superior -->
                        <thead>
                            <tr style="background-color: #D3D3D3;"> <!-- Añadir color de fondo a la primera fila -->
                                <th>Equipo Local</th>
                                <th>Puntos Local</th>
                                <th>Fecha</th>
                                <th>Estado</th>
                                <th>Periodo</th>
                                <th>Puntos Visitante</th>
                                <th>Equipo Visitante</th>
                            </tr>
                        </thead>
                        <tbody>';

    // Iterar sobre los partidos
    foreach ($games as $row) {
        $htmlContent .= '<tr>
                            <td>'.$row['home_team'].'</td>
                            <td>'.$row['home_team_score'].'</td>
                            <td>'.$row['date'].'</td>
                            <td>'.$row['status'].'</td>
                            <td>'.$row['period'].'</td>
                            <td>'.$row['visitor_team_score'].'</td>
                            <td>'.$row['visitor_team'].'</td>
                        </tr>';
    }

    // Cerrar la tabla
    $htmlContent .= '</tbody></table>';

    // Escribe el contenido HTML
    $pdf->writeHTML($htmlContent, true, false, true, false, '');

    // Salida del PDF
    $pdf->Output('PartidosNBA.pdf', 'D'); // 'D' para descargar el PDF directamente
    exit;
}
?>