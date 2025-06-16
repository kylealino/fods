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
$pdf->Cell(64, 211.5, '', 1, 1); //col 1 border
$pdf->SetXY(74, 62);
$pdf->Cell(22, 211.5, '', 1, 1); //col 1 border
$pdf->SetXY(96, 62);
$pdf->Cell(22, 211.5, '', 1, 1); //col 1 border
$pdf->SetXY(118, 62);
$pdf->Cell(22, 211.5, '', 1, 1); //col 1 border
$pdf->SetXY(140, 62);
$pdf->Cell(22, 211.5, '', 1, 1); //col 1 border
$pdf->SetXY(162, 62);
$pdf->Cell(22, 211.5, '', 1, 1); //col 1 border
$pdf->SetXY(184, 62);
$pdf->Cell(17, 211.5, '', 1, 1); //col 1 border


$Y += 9;

$pdf->SetXY(10, $Y);
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(5, 5, 'A. PROGRAMS', 0, 0, 'L');

$total_curryear_budget = 0;
$total_year_ps = 0;
$total_year_mooe = 0;
$total_year_co = 0;
$Y += 7;
$query = $this->db->query("
    SELECT
        a.`program_title`,
        a.`recid`,
        (SELECT SUM(approved_budget) FROM tbl_saob_ps_dt WHERE project_id = a.`recid`) AS total_ps,
        (SELECT SUM(approved_budget) FROM tbl_saob_mooe_dt WHERE project_id = a.`recid`) AS total_mooe,
        (SELECT SUM(approved_budget) FROM tbl_saob_co_dt WHERE project_id = a.`recid`) AS total_co,
        (
            COALESCE((SELECT SUM(approved_budget) FROM tbl_saob_ps_dt WHERE project_id = a.`recid`), 0) +
            COALESCE((SELECT SUM(approved_budget) FROM tbl_saob_mooe_dt WHERE project_id = a.`recid`), 0) +
            COALESCE((SELECT SUM(approved_budget) FROM tbl_saob_co_dt WHERE project_id = a.`recid`), 0)
        ) AS total_approved_budget
    FROM
        `tbl_saob_hd` a
    ORDER BY a.`recid` DESC;
");
$hd_data = $query->getResultArray();
foreach ($hd_data as $hd_row) {
    $program_title = $hd_row['program_title'];
    $recid = $hd_row['recid'];
    $total_ps = $hd_row['total_ps'];
    $total_mooe = $hd_row['total_mooe'];
    $total_co = $hd_row['total_co'];
    $total_approved_budget = $hd_row['total_approved_budget'];

    

    $pdf->SetXY(15, $Y);
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->MultiCell(59, 3.5, $program_title, 0, 'L'); // full width usage
    $Y = $pdf->GetY() - 3.5;
    $pdf->SetXY(96, $Y);
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(21.8, 3.5, $total_approved_budget, 'B', 1, 'C');
    $Y = $pdf->GetY() + 3.5;


    $query = $this->db->query("
        SELECT
            (SELECT `allotment_class` FROM mst_uacs WHERE `uacs_code` = b.`code`) allotment_class,
            (SELECT `object_code` FROM mst_uacs WHERE `uacs_code` = b.`code`) object_code,
            b.`particulars` AS `sub_object_code`,
            b.`code` AS `uacs_code`,
            b.`approved_budget`
        FROM
            `tbl_saob_ps_dt` b
        WHERE
            b.`project_id` = '$recid'
        ORDER BY b.`recid`, b.`particulars`
    ");
    $ps_data = $query->getResultArray();
    //total ps object code fetching
    $ps_object_code_totals = [];
    foreach ($ps_data as $ps_row) {
        $object_code = $ps_row['object_code'];
        $approved_budget = floatval($ps_row['approved_budget']);

        if (!isset($ps_object_code_totals[$object_code])) {
            $ps_object_code_totals[$object_code] = 0;
        }
        $ps_object_code_totals[$object_code] += $approved_budget;
    }

    $last_allotment_class = '';
    $last_sub_object_code = '';
    $last_object_code = '';
    $Y = $pdf->GetY() + 9;;
    foreach ($ps_data as $ps_row) {
        $allotment_class = $ps_row['allotment_class'];
        $object_code = $ps_row['object_code'];
        $sub_object_code = $ps_row['sub_object_code'];
        $uacs_code = $ps_row['uacs_code'];
        $approved_budget = $ps_row['approved_budget'];

        $Y = $pdf->GetY() + 2.5;
        if ($Y > 270) {
            $pdf->AddPage();
            $Y = $pdf->GetY(); // or set manually, e.g., $Y = 10;
        }

        if ($allotment_class !== $last_allotment_class && $allotment_class !== null) {
            $pdf->SetFont('Arial', '', 8);
            $pdf->SetXY(5, $Y);
            $pdf->Cell(5, 3.5, '', 0, 0, 'L');
            $pdf->MultiCell(59, 3.5, $allotment_class, 0, 'L'); // full width usage
            $pdf->SetXY(96, $Y);
            $pdf->Cell(21.8, 3.5, $total_ps, 'B', 1, 'C');
            $pdf->SetXY(184, $Y);
            $pdf->Cell(17, 3.5, '%', 0, 1, 'C');
            $Y += 5.5;
            $last_allotment_class = $allotment_class;
        }

        if ($object_code !== $last_object_code && $object_code !== null) {
            $pdf->SetFont('Arial', '', 8);
            $pdf->SetXY(10, $Y);
            $pdf->Cell(5, 3.5, '', 0, 0, 'L');
            $pdf->MultiCell(59, 3.5, $object_code, 0, 'L'); // full width usage
            $total_for_object_code = $ps_object_code_totals[$object_code] ?? 0;
            $pdf->SetXY(96, $Y);
            $pdf->Cell(21.8, 3.5, number_format($total_for_object_code, 2), 'B', 1, 'C');
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
        $pdf->Cell(20, 3.5, ($approved_budget == 0.00 || !is_numeric($approved_budget)) ? '---' : number_format((float)$approved_budget, 2), 0, 1, 'C');

        $pdf->SetXY(119, $Y);
        $pdf->Cell(20, 3.5, '----', 0, 1, 'C');

        $pdf->SetXY(142, $Y);
        $pdf->Cell(20, 3.5, '----', 0, 1, 'C');

        $pdf->SetXY(164, $Y);
        $pdf->Cell(20, 3.5, '----', 0, 1, 'C');

        $Y = $pdf->GetY() + 3.5;

    }

    $query = $this->db->query("
        SELECT
            (SELECT `allotment_class` FROM mst_uacs WHERE `uacs_code` = b.`code`) allotment_class,
            (SELECT `object_code` FROM mst_uacs WHERE `uacs_code` = b.`code`) object_code,
            b.`particulars` AS `sub_object_code`,
            b.`code` AS `uacs_code`,
            b.`approved_budget`
        FROM
            `tbl_saob_mooe_dt` b
        WHERE
            b.`project_id` = '$recid'
        ORDER BY b.`recid`, b.`particulars`
    ");
    $mooe_data = $query->getResultArray();
    $mooe_object_code_totals = [];
    foreach ($mooe_data as $mooe_row) {
        $object_code = $mooe_row['object_code'];
        $approved_budget = floatval($mooe_row['approved_budget']);

        if (!isset($mooe_object_code_totals[$object_code])) {
            $mooe_object_code_totals[$object_code] = 0;
        }
        $mooe_object_code_totals[$object_code] += $approved_budget;
    }
    $last_allotment_class = '';
    $last_sub_object_code = '';
    $last_object_code = '';

    $Y = $pdf->GetY() + 9;;
    foreach ($mooe_data as $mooe_row) {
        $allotment_class = $mooe_row['allotment_class'];
        $object_code = $mooe_row['object_code'];
        $sub_object_code = $mooe_row['sub_object_code'];
        $uacs_code = $mooe_row['uacs_code'];
        $approved_budget = $mooe_row['approved_budget'];

        $Y = $pdf->GetY() + 2.5;

        if ($allotment_class !== $last_allotment_class && $allotment_class !== null) {
            $pdf->SetFont('Arial', '', 8);
            $pdf->SetXY(5, $Y);
            $pdf->Cell(5, 3.5, '', 0, 0, 'L');
            $pdf->MultiCell(59, 3.5, $allotment_class, 0, 'L'); // full width usage
            $pdf->SetXY(96, $Y);
            $pdf->Cell(21.8, 3.5, $total_mooe, 'B', 1, 'C');
            $pdf->SetXY(184, $Y);
            $pdf->Cell(17, 3.5, '%', 0, 1, 'C');
            $Y += 5.5;
            $last_allotment_class = $allotment_class;
        }

        if ($object_code !== $last_object_code && $object_code !== null) {
            $pdf->SetFont('Arial', '', 8);
            $pdf->SetXY(10, $Y);
            $pdf->Cell(5, 3.5, '', 0, 0, 'L');
            $pdf->MultiCell(59, 3.5, $object_code, 0, 'L'); // full width usage
            $total_for_object_code = $mooe_object_code_totals[$object_code] ?? 0;
            $pdf->SetXY(96, $Y);
            $pdf->Cell(21.8, 3.5, number_format($total_for_object_code, 2), 'B', 1, 'C');
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
        $pdf->Cell(20, 3.5, ($approved_budget == 0.00 || !is_numeric($approved_budget)) ? '---' : number_format((float)$approved_budget, 2), 0, 1, 'C');

        $pdf->SetXY(119, $Y);
        $pdf->Cell(20, 3.5, '----', 0, 1, 'C');

        $pdf->SetXY(142, $Y);
        $pdf->Cell(20, 3.5, '----', 0, 1, 'C');

        $pdf->SetXY(164, $Y);
        $pdf->Cell(20, 3.5, '----', 0, 1, 'C');

        $Y = $pdf->GetY() + 3.5;

    }

    $pdf->SetFont('Arial', '', 8);
    $pdf->SetXY(5, $Y);
    $pdf->Cell(5, 3.5, '', 0, 0, 'L');
    $pdf->MultiCell(59, 3.5, 'Capital Outlay', 0, 'L'); // full width usage
    $pdf->SetXY(96, $Y);
    $pdf->Cell(21.8, 3.5, $total_co, 'B', 1, 'C');
    $Y += 5.5;
    $query = $this->db->query("
        SELECT
            (SELECT `allotment_class` FROM mst_uacs WHERE `uacs_code` = b.`code`) allotment_class,
            (SELECT `object_code` FROM mst_uacs WHERE `uacs_code` = b.`code`) object_code,
            b.`particulars` AS `sub_object_code`,
            b.`code` AS `uacs_code`,
            b.`approved_budget`
        FROM
            `tbl_saob_co_dt` b
        WHERE
            b.`project_id` = '$recid'
        ORDER BY b.`recid`, b.`particulars`
    ");
    $co_data = $query->getResultArray();
    $co_object_code_totals = [];
    foreach ($co_data as $co_row) {
        $object_code = $co_row['object_code'];
        $approved_budget = floatval($co_row['approved_budget']);

        if (!isset($co_object_code_totals[$object_code])) {
            $co_object_code_totals[$object_code] = 0;
        }
        $co_object_code_totals[$object_code] += $approved_budget;
    }
    $last_allotment_class = '';
    $last_sub_object_code = '';
    $last_object_code = '';

    $Y = $pdf->GetY() + 9;;
    foreach ($co_data as $co_row) {
        $allotment_class = $co_row['allotment_class'];
        $object_code = $co_row['object_code'];
        $sub_object_code = $co_row['sub_object_code'];
        $uacs_code = $co_row['uacs_code'];
        $approved_budget = $co_row['approved_budget'];

        $Y = $pdf->GetY() + 2.5;

        if ($object_code !== $last_object_code && $object_code !== null) {
            $pdf->SetFont('Arial', '', 8);
            $pdf->SetXY(10, $Y);
            $pdf->Cell(5, 3.5, '', 0, 0, 'L');
            $pdf->MultiCell(59, 3.5, $object_code, 0, 'L'); // full width usage
            $total_for_object_code = $co_object_code_totals[$object_code] ?? 0;
            $pdf->SetXY(96, $Y);
            $pdf->Cell(21.8, 3.5, number_format($total_for_object_code, 2), 'B', 1, 'C');
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
        $pdf->Cell(20, 3.5, ($approved_budget == 0.00 || !is_numeric($approved_budget)) ? '---' : number_format((float)$approved_budget, 2), 0, 1, 'C');

        $pdf->SetXY(119, $Y);
        $pdf->Cell(20, 3.5, '----', 0, 1, 'C');

        $pdf->SetXY(142, $Y);
        $pdf->Cell(20, 3.5, '----', 0, 1, 'C');

        $pdf->SetXY(164, $Y);
        $pdf->Cell(20, 3.5, '----', 0, 1, 'C');

        $Y = $pdf->GetY() + 3.5;

    }

    //total current year budget

    $total_year_ps += $total_ps;
    $total_year_mooe += $total_mooe;
    $total_year_co += $total_co;
}

$total_curryear_budget = $total_year_ps + $total_year_mooe + $total_year_co;
$Y = $pdf->GetY() + 2.5;
$pdf->SetFont('Arial', 'B', 8);
$pdf->SetXY(10, $Y);
$pdf->Cell(191, 3.5, 'TOTAL CURRENT YEAR BUDGET', 1, 1, 'L');
$pdf->SetXY(96, $Y);
$pdf->Cell(22, 3.5, ($total_curryear_budget == 0.00 || !is_numeric($total_curryear_budget)) ? '---' : number_format((float)$total_curryear_budget, 2), 0, 1, 'C');

$Y = 10;
$YY = $pdf->GetY() +30;

$pdf->SetXY(10, $Y);
$pdf->Cell(64, $YY, '', 1, 1); //col 1 border
$pdf->SetXY(74, $Y);
$pdf->Cell(22, $YY, '', 1, 1); //col 1 border
$pdf->SetXY(96, $Y);
$pdf->Cell(22, $YY, '', 1, 1); //col 1 border
$pdf->SetXY(118, $Y);
$pdf->Cell(22, $YY, '', 1, 1); //col 1 border
$pdf->SetXY(140, $Y);
$pdf->Cell(22, $YY, '', 1, 1); //col 1 border
$pdf->SetXY(162, $Y);
$pdf->Cell(22, $YY, '', 1, 1); //col 1 border
$pdf->SetXY(184, $Y);
$pdf->Cell(17, $YY, '', 1, 1); //col 1 border


$Y = $pdf->GetY() - 3.5;
$pdf->SetFont('Arial', 'B', 8);
$pdf->SetXY(10, $Y);
$pdf->Cell(191, 3.5, 'GRAND TOTAL, CURRENT YEAR APPRO.', 1, 1, 'L');
$pdf->SetXY(96, $Y);
$pdf->Cell(22, 3.5, ($total_curryear_budget == 0.00 || !is_numeric($total_curryear_budget)) ? '---' : number_format((float)$total_curryear_budget, 2), 0, 1, 'C');


$Y = $pdf->GetY() +7;
$pdf->SetFont('Arial', '', 8);
$pdf->SetXY(10, $Y);
$pdf->Cell(64, 3.5, 'Certified Correct:', 0, 1, 'C');
$pdf->SetXY(96, $Y);
$pdf->Cell(22, 3.5, 'Approved By:', 0, 1, 'C');

$Y = $pdf->GetY() +14;
$pdf->SetFont('Arial', 'B', 9);
$pdf->SetXY(31, $Y);
$pdf->Cell(62, 3.5, 'ROMANA L. LLAMAS', 0, 1, 'L');
$pdf->SetXY(97, $Y);
$pdf->Cell(22, 3.5, 'ATTY. LUCIEDEN G. RAZ', 0, 1, 'L');

$Y = $pdf->GetY();
$pdf->SetFont('Arial', '', 8);
$pdf->SetXY(31, $Y);
$pdf->Cell(62, 3.5, 'Administrative Office V', 0, 1, 'L');
$pdf->SetXY(97, $Y);
$pdf->Cell(22, 3.5, 'Director III', 0, 1, 'L');



$pdf->Output();
exit;
?>