<?php
if (isset($_POST['generate_pdf'])) {
    session_start();
    require_once './tcpdf/tcpdf.php';
    $players = $_SESSION['players'];

    // Crear una nueva instancia de TCPDF
    $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

    // Establecer el título del documento
    $pdf->SetTitle('Jugadores NBA');

    // Agregar una página
    $pdf->AddPage();

    // Agregar logotipo y cabecera
    $pdf->Image('./assets/img/logoNBA2.png', 10, 10, 20, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false); // Reducir el tamaño del logotipo
    $pdf->SetFont('helvetica', 'B', 20);
    $pdf->Cell(0, 25, 'Jugadores NBA', 0, true, 'C');

    // Agregar contenido
    $pdf->SetFont('helvetica', '', 12);
    $htmlContent = '<table border="1"> <!-- Aumentar el valor del margen superior -->
                        <thead>
                            <tr style="background-color: #D3D3D3;"> <!-- Añadir color de fondo a la primera fila -->
                                <th>Nombre Completo</th>
                                <th>Dorsal</th>
                                <th>Posicion</th>
                                <th>Draft</th>
                                <th>Pais</th>
                                <th>Equipo</th>
                            </tr>
                        </thead>
                        <tbody>';

    // Iterar sobre los equipos
    foreach ($players as $row) {
        $htmlContent .= '<tr>
                            <td>'.$row['first_name'].' '.$row['last_name'].'</td>
                            <td>'.$row['number'].'</td>
                            <td>'.$row['position'].'</td>
                            <td>'.$row['draft'].'</td>
                            <td>'.$row['country'].'</td>
                            <td>'.$row['team_name'].'</td>
                        </tr>';
    }

    // Cerrar la tabla
    $htmlContent .= '</tbody></table>';

    // Escribe el contenido HTML
    $pdf->writeHTML($htmlContent, true, false, true, false, '');

    // Salida del PDF
    $pdf->Output('JugadoresNBA.pdf', 'D'); // 'D' para descargar el PDF directamente
    exit;
}
