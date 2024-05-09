<?php
session_start();
require_once './tcpdf/tcpdf.php';

if (isset($_POST['generate_pdf'])) {
    $games = isset($_SESSION['games']) ? $_SESSION['games'] : [];

    $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
    $pdf->SetTitle('Partidos NBA');
    $pdf->AddPage();
    $pdf->Image('./assets/img/logoNBA2.png', 10, 10, 20, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
    $pdf->SetFont('helvetica', 'B', 20);
    $pdf->Cell(0, 25, 'Partidos NBA', 0, true, 'C');

    $pdf->SetFont('helvetica', '', 12);
    $htmlContent = '<table border="1">
                        <thead>
                            <tr style="background-color: #D3D3D3;">
                                <th>Equipo Local</th>
                                <th>Equipo Visitante</th>
                                <th>Fecha</th>
                                <th>Puntos Visitante</th>
                                <th>Puntos Local</th>
                            </tr>
                        </thead>
                        <tbody>';

    foreach ($games as $game) {
        $date = date_create($game['date']);
        $formatted_date = date_format($date, 'Y-m-d');

        $htmlContent .= '<tr>
                            <td>'.$game['home_team_id'].'</td>
                            <td>'.$game['visitor_team_id'].'</td>
                            <td>'.$formatted_date.'</td>
                            <td>'.$game['visitor_team_score'].'</td>
                            <td>'.$game['home_team_score'].'</td>
                        </tr>';
    }

    $htmlContent .= '</tbody></table>';

    $pdf->writeHTML($htmlContent, true, false, true, false, '');
    $pdf->Output('PartidosNBA.pdf', 'D');
    exit;
}