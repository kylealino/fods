<?php
$this->request = \Config\Services::request();
$this->db = \Config\Database::connect();
$recid = '101';
$month = $this->request->getPostGet('month');
$year = $this->request->getPostGet('year');
$action = $this->request->getPostGet('action');
$this->session = session();
$this->cuser = $this->session->get('__xsys_myuserzicas__');
require APPPATH . 'ThirdParty/fpdf/fpdf.php';

$query = $this->db->query("
SELECT
    `project_title`,
    `project_leader`,
    `program_title`,
    `project_duration`,
    `duration_from`,
    `duration_to`,
    `program_leader`,
    `project_leader`,
    `monitoring_agency`,
    `funding_agency`,
    `collaborating_agencies`,
    `implementing_agency`,
    `with_extension`,
    `extended_from`,
    `extended_to`
FROM
    `tbl_budget_hd`
WHERE 
    `recid` = '$recid'"
);

$data = $query->getRowArray();
$project_title = $data['project_title'];
$project_leader = $data['project_leader'];
$program_title = $data['program_title'];
$project_duration = $data['project_duration'];
$duration_from = $data['duration_from'];
$duration_to = $data['duration_to'];
$program_leader = $data['program_leader'];
$project_leader = $data['project_leader'];
$monitoring_agency = $data['monitoring_agency'];
$funding_agency = $data['funding_agency'];
$collaborating_agencies = $data['collaborating_agencies'];
$implementing_agency = $data['implementing_agency'];
$with_extension = $data['with_extension'];
$extended_from = $data['extended_from'];
$extended_to = $data['extended_to'];

if ($with_extension == '1') {
    $toYear = date('Y', strtotime($data['extended_to']));
    $cy_range = "CY ". "{$toYear}";
}else{
    $toYear = date('Y', strtotime($data['duration_to']));
    $cy_range = "CY "."{$toYear}";
}

function DrawDottedLine($pdf, $x1, $y1, $x2, $y2, $dotLength = 1, $gap = 1) {
    $totalLength = sqrt(pow($x2 - $x1, 2) + pow($y2 - $y1, 2));
    $dx = ($x2 - $x1) / $totalLength;
    $dy = ($y2 - $y1) / $totalLength;

    $currentLength = 0;
    while ($currentLength < $totalLength) {
        $startX = $x1 + $dx * $currentLength;
        $startY = $y1 + $dy * $currentLength;
        $endX = $x1 + $dx * ($currentLength + $dotLength);
        $endY = $y1 + $dy * ($currentLength + $dotLength);

        $pdf->Line($startX, $startY, $endX, $endY);
        $currentLength += ($dotLength + $gap);
    }
}

class PDF extends \FPDF {
    function Footer() {
        // Position at 15 mm from bottom
        $this->SetY(-20);
        // Arial italic 8
        $this->SetFont('Arial', 'I', 8);
        // Page number in center
        $this->Cell(0, 10, 'Page ' . $this->PageNo() . ' of {nb}', 0, 0, 'C');
    }
}


$pdf = new PDF('P', 'mm', 'A4');
$pdf->AliasNbPages(); // Enables total page number
$pdf->AddPage();
$pdf->SetTitle('SAOB Print');
$pdf->SetFont('Arial', 'B', 16);



$pdf->SetXY(8, 10);

$X = 0;
$Y = 8;
$pdf->SetFont('Arial', 'B', 7.5);
$Y = 4;
$pdf->Cell($X,$Y,'STATEMENT OF ALLOTMENTS, OBLIGATIONS AND BALANCE',0,1,'C');
$pdf->Cell($X,$Y,'As of ' . $month . ' ' . $year,0,1,'C');
$pdf->Cell(0, $Y, '(In Pesos)', 0, 1,'C');

//spacer
$pdf->Cell($X,4,'',0,1,'L');

$pdf->SetFont('Arial', '', 7);

// Program Title (wraps if long, ':' separated)
$pdf->Cell(15, 3.5, 'Department', 0, 0, 'L'); // Label

$pdf->Cell(2, 3.5, ':', 0, 0, 'L');              // Colon

$X = $pdf->GetX(); // Save current X
$Y = $pdf->GetY(); // Save current Y

$pdf->SetXY($X, $Y); // Set cursor at value position
$pdf->SetFont('Arial', 'B', 7);
$pdf->MultiCell(105, 3.5, 'DEPARTMENT OF SCIENCE AND TECHNOLOGY', 0, 'L');

// Optional: add a small line break or spacing after to separate rows
$pdf->Ln(1);

// Project Title (wraps if long)
$pdf->SetFont('Arial', '', 7);
$pdf->Cell(15, 3.5, 'Agency', 0, 0, 'L');
$pdf->Cell(2, 3.5, ':',0, 0, 'L');              // Colon

$X = $pdf->GetX(); // Save current X
$Y = $pdf->GetY(); // Save current Y

$pdf->SetXY($X, $Y); // Set cursor at value position
$pdf->SetFont('Arial', 'B', 7);
$pdf->MultiCell(105, 3.5, 'FOOD AND NUTRITION RESEARCH INSTITUTE', 0, 'L');

$Y+= 7;

$pdf->SetXY(10, $Y);

$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell(64, 3.5, '', 'TRL', 0, 'C');//ROW 1
$pdf->Cell(22, 3.5, '', 'TRL', 0, 'C');//ROW 1
$pdf->Cell(22, 3.5, '', 'TRL', 0, 'C');//ROW 1
$pdf->Cell(44, 3.5, '', 'TRL', 0, 'C');//ROW 1
$pdf->Cell(22, 3.5, '', 'TRL', 0, 'C');//ROW 1
$pdf->Cell(17, 3.5, '', 'TRL', 0, 'C');//ROW 1
$Y+= 3.5;
$pdf->SetXY(10, $Y);
$pdf->Cell(64, 3.5, '', 'RL', 0, 'C');//ROW 2
$pdf->Cell(22, 3.5, '', 'RL', 0, 'C');//ROW 2
$pdf->Cell(22, 3.5, '', 'RL', 0, 'C');//ROW 2
$pdf->Cell(44, 3.5, 'Obligation Incurred', 'BRL', 0, 'C');//ROW 2
$pdf->Cell(22, 3.5, 'Unobligated', 'RL', 0, 'C');//ROW 2
$pdf->Cell(17, 3.5, '', 'RL', 0, 'C');//ROW 2
$Y+= 3.5;
$pdf->SetXY(10, $Y);
$pdf->Cell(64, 3.5, 'OBJECT OF EXPENDITURE', 'RL', 0, 'C');//ROW 3
$pdf->Cell(22, 3.5, '', 'RL', 0, 'C');//ROW 3
$pdf->Cell(22, 3.5, 'Revised', 'RL', 0, 'C');//ROW 3
$pdf->Cell(22, 3.5, 'This month', 'RL', 0, 'C');//ROW 3
$pdf->Cell(22, 3.5, 'Obligation', 'RL', 0, 'C');//ROW 3
$pdf->Cell(22, 3.5, 'Balance of', 'RL', 0, 'C');//ROW 3
$pdf->Cell(17, 3.5, 'Percent', 'RL', 0, 'C');//ROW 3

$Y+= 3.5;
$pdf->SetXY(10, $Y);
$pdf->Cell(64, 3.5, '', 'RL', 0, 'C');//ROW 4
$pdf->Cell(22, 3.5, 'UACS', 'RL', 0, 'C');//ROW 4
$pdf->Cell(22, 3.5, 'Allotment', 'RL', 0, 'C');//ROW 4
$pdf->Cell(22, 3.5, $month, 'RL', 0, 'C');//ROW 4
$pdf->Cell(22, 3.5, 'To Date', 'RL', 0, 'C');//ROW 4
$pdf->Cell(22, 3.5, 'Allotment', 'RL', 0, 'C');//ROW 4
$pdf->Cell(17, 3.5, 'Of', 'RL', 0, 'C');//ROW 4

$Y+= 3.5;
$pdf->SetXY(10, $Y);
$pdf->Cell(64, 3.5, '', 'BRL', 0, 'C');//ROW 5
$pdf->Cell(22, 3.5, '(1)', 'BRL', 0, 'C');//ROW 5
$pdf->Cell(22, 3.5, '(4)', 'BRL', 0, 'C');//ROW 5
$pdf->Cell(22, 3.5, '(5)', 'BRL', 0, 'C');//ROW 5
$pdf->Cell(22, 3.5, '(6)', 'BRL', 0, 'C');//ROW 5
$pdf->Cell(22, 3.5, '(7)=(4)-(6)', 'BRL', 0, 'C');//ROW 5
$pdf->Cell(17, 3.5, 'Utilization', 'BRL', 0, 'C');//ROW 5

$Y+= 3.5;
$pdf->SetXY(10, $Y);
$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell(64, 7, 'CURRENT YEAR BUDGET', 'BRL', 0, 'L');//ROW 5
$pdf->Cell(22, 7, '', 'BRL', 0, 'C');//ROW 5
$pdf->Cell(22, 7, '', 'BRL', 0, 'C');//ROW 5
$pdf->Cell(22, 7, '', 'BRL', 0, 'C');//ROW 5
$pdf->Cell(22, 7, '', 'BRL', 0, 'C');//ROW 5
$pdf->Cell(22, 7, '', 'BRL', 0, 'C');//ROW 5
$pdf->Cell(17, 7, '', 'BRL', 0, 'C');//ROW 5

$pdf->SetXY(10, 62);
$pdf->Cell(64, 211, '', 1, 1); //col 1 border
$pdf->SetXY(74, 62);
$pdf->Cell(22, 211, '', 1, 1); //col 1 border
$pdf->SetXY(96, 62);
$pdf->Cell(22, 211, '', 1, 1); //col 1 border
$pdf->SetXY(118, 62);
$pdf->Cell(22, 211, '', 1, 1); //col 1 border
$pdf->SetXY(140, 62);
$pdf->Cell(22, 211, '', 1, 1); //col 1 border
$pdf->SetXY(162, 62);
$pdf->Cell(22, 211, '', 1, 1); //col 1 border
$pdf->SetXY(184, 62);
$pdf->Cell(17, 211, '', 1, 1); //col 1 border



$Y += 7;
$pdf->SetXY(10, $Y);
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(5, 5, 'Personnel Services', 0, 0, 'L');
$Y += 7;
$query = $this->db->query("
    SELECT
        b.`object_code`,
        b.`sub_object_code`,
        b.`uacs_code`
    FROM
        `mst_uacs` b
    WHERE allotment_class = 'Personnel Services'

");

$data = $query->getResultArray();
$last_sub_object_code = '';
$last_object_code = '';

$Y = $pdf->SetY(65);
foreach ($data as $row) {
    $object_code = $row['object_code'];
    $sub_object_code = $row['sub_object_code'];
    $uacs_code = $row['uacs_code'];

    $Y = $pdf->GetY() + 2.5;
    if ($Y > 270) {
        $pdf->AddPage();
        $Y = $pdf->GetY(); // or set manually, e.g., $Y = 10;
    }
    if ($object_code !== $last_object_code && $object_code !== null) {
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetXY(10, $Y);
        $pdf->Cell(5, 3.5, '', 0, 0, 'L');
        $pdf->MultiCell(59, 3.5, $object_code, 0, 'L'); // full width usage
        $pdf->SetXY(184, $Y);
        $pdf->Cell(17, 3.5, '%', 0, 1, 'C');
        $Y += 5.5;
        $last_object_code = $object_code;

   
    }
    // Print Expenditure Category if it changes
    if ($sub_object_code !== $last_sub_object_code && $sub_object_code !== null) {
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetXY(15, $Y);
        $pdf->Cell(5, 3.5, '', 0, 0, 'L');
        $pdf->MultiCell(54, 3.5, $sub_object_code, 0, 'L'); // full width usage
        $Y += 5.5;
        $last_sub_object_code = $sub_object_code;
    }

    $Y = $pdf->GetY() - 3.5;
    $pdf->SetXY(76, $Y);
    $pdf->Cell(20, 3.5, $uacs_code, 0, 1, 'C');

    $pdf->SetXY(97, $Y);
    $pdf->Cell(20, 3.5, '111111', 0, 1, 'C');

    $pdf->SetXY(119, $Y);
    $pdf->Cell(20, 3.5, '111111', 0, 1, 'C');
    
    $pdf->SetXY(142, $Y);
    $pdf->Cell(20, 3.5, '111111', 0, 1, 'C');
    
    $pdf->SetXY(164, $Y);
    $pdf->Cell(20, 3.5, '111111', 0, 1, 'C');

    $Y = $pdf->GetY() + 3.5;
    
}

$pdf->SetXY(10, 10);
$pdf->Cell(64, 211, '', 1, 1); //col 1 border
$pdf->SetXY(74, 10);
$pdf->Cell(22, 211, '', 1, 1); //col 1 border
$pdf->SetXY(96, 10);
$pdf->Cell(22, 211, '', 1, 1); //col 1 border
$pdf->SetXY(118, 10);
$pdf->Cell(22, 211, '', 1, 1); //col 1 border
$pdf->SetXY(140, 10);
$pdf->Cell(22, 211, '', 1, 1); //col 1 border
$pdf->SetXY(162, 10);
$pdf->Cell(22, 211, '', 1, 1); //col 1 border
$pdf->SetXY(184, 10);
$pdf->Cell(17, 211, '', 1, 1); //col 1 border




$pdf->Output();
exit;
?>