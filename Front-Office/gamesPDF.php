<?php
if (isset($_POST['generate_pdf'])) {
    session_start();
    require_once './tcpdf/tcpdf.php';
    require_once "autenticarUsuario.php";
    include "connection.php";
    $conn = connect();
    $query = "SELECT * FROM final_games";
    $result = mysqli_query($conn, $query);

    // Función para obtener los detalles completos del equipo
    function getTeamDetails($team_id) {
        global $conn;
        $query = "SELECT * FROM final_teams WHERE id = $team_id";
        $result = mysqli_query($conn, $query);
        return mysqli_fetch_assoc($result);
    }

    // Crear instancia de TCPDF
    $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

    // Establecer el título del documento
    $pdf->SetTitle('Partidos NBA');

    // Agregar una página
    $pdf->AddPage();

    // Agregar logotipo y cabecera
    $pdf->Image('./assets/img/logoNBA2.png', 10, 10, 20, '', 'PNG', '', 'T', false, round(300), '', false, false, 0, false, false, false);
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
    while ($row = mysqli_fetch_assoc($result)) {
        // Obtener detalles del equipo local y visitante
        $home_team = getTeamDetails($row['home_team_id']);
        $visitor_team = getTeamDetails($row['visitor_team_id']);
        
        // Convertir fecha de formato completo a solo fecha
        $date = date_create($row['date']);
        $formatted_date = date_format($date, 'Y-m-d');

        // Cambiar estado según el valor
        $status = ($row['status'] == 'Final') ? 'Terminado' : $row['status'];

        // Cambiar periodo
        $period = ($row['period'] == 'final') ? 'Cuartos' : $row['period'];

        // Agregar fila de la tabla
        $htmlContent .= '<tr>
                            <td>'.$home_team['name'].'</td>
                            <td>'.$row['home_team_score'].'</td>
                            <td>'.$formatted_date.'</td>
                            <td>'.$status.'</td>
                            <td>'.$period.'</td>
                            <td>'.$row['visitor_team_score'].'</td>
                            <td>'.$visitor_team['name'].'</td>
                        </tr>';
    }

    // Cerrar la tabla
    $htmlContent .= '</tbody></table>';

    // Escribe el contenido HTML
    $pdf->writeHTML($htmlContent, true, false, true, false, '');

    // Salida del PDF
    $pdf->Output('PartidosNBA.pdf', 'D'); // 'D' para descargar el PDF directamente
    exit;
}?>