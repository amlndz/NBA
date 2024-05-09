<?php
if (isset($_POST['generate_pdf'])) {
    session_start();
    require_once './tcpdf/tcpdf.php';
    $players = $_SESSION['players'];

    class MYPDF extends TCPDF {
        public function Footer() {
            $this->SetY(-15);
            $this->SetFont('helvetica', 'I', 8);
            $this->Cell(0, 10, 'Información tomada de API balldontlie', 0, false, 'C', 0, '', 0, false, 'T', 'M');
        }
    }

    $pdf = new MYPDF('P', 'mm', 'A4', true, 'UTF-8', false);
    $pdf->SetTitle('Jugadores NBA');
    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    $pdf->AddPage();
    $pdf->Image('./assets/img/logoNBA2.png', 10, 10, 20, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
    $pdf->SetFont('helvetica', 'B', 20);
    $pdf->Cell(0, 25, 'Jugadores NBA', 0, true, 'C');

    $pdf->SetFont('helvetica', '', 12);
    $htmlContent = '<table border="1">
                        <thead>
                            <tr style="background-color: #D3D3D3;">
                                <th>Nombre Completo</th>
                                <th>Dorsal</th>
                                <th>Posicion</th>
                                <th>Draft</th>
                                <th>Pais</th>
                                <th>Equipo</th>
                            </tr>
                        </thead>
                        <tbody>';

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

    $htmlContent .= '</tbody></table>';

    $pdf->writeHTML($htmlContent, true, false, true, false, '');
    $pdf->Output('JugadoresNBA.pdf', 'D');
    exit;
}