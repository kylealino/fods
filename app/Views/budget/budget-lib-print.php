<?php
$this->request = \Config\Services::request();
$this->db = \Config\Database::connect();
$recid = $this->request->getPostGet('recid');
$realign_id = $this->request->getPostGet('realign_id');
$action = $this->request->getPostGet('action');
$this->session = session();
$this->cuser = $this->session->get('__xsys_myuserzicas__');
require APPPATH . 'ThirdParty/fpdf/fpdf.php';

$query = $this->db->query("
SELECT
    `project_title`,
    `project_leader`,
    `program_title`,
    `total_duration`,
    `duration_from`,
    `duration_to`,
    `program_leader`,
    `monitoring_agency`,
    `collaborating_agencies`,
    `implementing_agency`
FROM
    `tbl_budget_hd`
WHERE 
    `recid` = '$recid'"
);

$data = $query->getRowArray();
$project_title = $data['project_title'];
$project_leader = $data['project_leader'];
$program_title = $data['program_title'];
$total_duration = $data['total_duration'];
$duration_from = $data['duration_from'];
$duration_to = $data['duration_to'];
$program_leader = $data['program_leader'];
$monitoring_agency = $data['monitoring_agency'];
$collaborating_agencies = $data['collaborating_agencies'];
$implementing_agency = $data['implementing_agency'];

$pdf = new \FPDF();
$pdf->AddPage();
$pdf->SetTitle('Project Line-Item Budget Print');
$pdf->SetFont('Arial', 'B', 16);

$pdf->SetXY(8, 10);

// Add image
$x = 10; // X position
$y = 12;   // Y position
$width = 16; // Width of the image
$height = 16; // Height of the image (you can adjust this based on your needs)

$pdf->Image(ROOTPATH . 'public/assets/images/logos/fnrilogo.png', $x, $y, $width, $height);

$X = 0;
$Y = 8;
$pdf->SetFont('Arial', '', 7);
$pdf->Cell($X,$Y,'DOST Form 4',0,1,'C');

$pdf->SetFont('Arial', 'B', 7.5);
$Y = 4;
$pdf->Cell($X,$Y,'DEPARTMENT OF SCIENCE AND TECHNOLOGY',0,1,'C');
$pdf->Cell($X,$Y,'Project Line-Item Budget',0,1,'C');
$pdf->Cell($X,$Y,'CY _____',0,1,'C');

//spacer
$pdf->Cell($X,4,'',0,1,'L');

$pdf->SetFont('Arial', '', 7);

// Program Title (wraps if long, ':' separated)
$pdf->Cell(40, 3.5, 'Program Title', 0, 0, 'L'); // Label
$pdf->Cell(5, 3.5, ':', 0, 0, 'L');              // Colon

$X = $pdf->GetX(); // Save current X
$Y = $pdf->GetY(); // Save current Y

$pdf->SetXY($X, $Y); // Set cursor at value position
$pdf->MultiCell(145, 3.5, $program_title, 0, 'L');

// Optional: add a small line break or spacing after to separate rows
$pdf->Ln(1);

// Project Title (wraps if long)
$pdf->Cell(40, 3.5, 'Project Title', 0, 0, 'L');
$pdf->Cell(5, 3.5, ':', 0, 0, 'L');              // Colon

$X = $pdf->GetX(); // Save current X
$Y = $pdf->GetY(); // Save current Y

$pdf->SetXY($X, $Y); // Set cursor at value position
$pdf->MultiCell(145, 3.5, $project_title, 0, 'L');


$X = $pdf->GetX(); // Save current X
$Y = $pdf->GetY(); // Save current Y
// Implementing Agency (below the wrapped Project Title)
$pdf->Cell(40, 3.5, 'Implementing Agency', 0, 0, 'L');
$pdf->Cell(5, 3.5, ':', 0, 0, 'L');
$pdf->Cell(60, 3.5, $implementing_agency, 0, 1, 'L');

$pdf->Cell(40, 3.5, 'Total Duration', 0, 0, 'L');
$pdf->Cell(5, 3.5, ':', 0, 0, 'L');
$pdf->Cell(60, 3.5, $total_duration, 0, 1, 'L');

$pdf->Cell(40, 3.5, 'Current Duration', 0, 0, 'L');
$pdf->Cell(5, 3.5, ':', 0, 0, 'L');
$pdf->Cell(60, 3.5, $duration_from . ' - ' . $duration_to, 0, 1, 'L');

$pdf->Cell(40, 3.5, 'Collaborating Agency', 0, 0, 'L');
$pdf->Cell(5, 3.5, ':', 0, 0, 'L');              // Colon

$X = $pdf->GetX(); // Save current X
$Y = $pdf->GetY(); // Save current Y

$pdf->SetXY($X, $Y); // Set cursor at value position
$pdf->MultiCell(145, 3.5, $collaborating_agencies, 0, 'L');

$X = $pdf->GetX(); // Save current X
$Y = $pdf->GetY(); // Save current Y
$pdf->Cell(40, 3.5, 'Program Leader', 0, 0, 'L');
$pdf->Cell(5, 3.5, ':', 0, 0, 'L');
$pdf->Cell(60, 3.5, $program_leader, 0, 1, 'L');

$pdf->Cell(40, 3.5, 'Monitoring Agency', 0, 0, 'L');
$pdf->Cell(5, 3.5, ':', 0, 0, 'L');
$pdf->Cell(60, 3.5, $monitoring_agency, 0, 1, 'L');

$X = $pdf->GetX(); // Save current X
$Y = $pdf->GetY(); // Save current Y

$pdf->SetXY(130, $Y);
$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell(70, 3.5, 'Counterpart Funding', 'B', 0, 'C');

$Y+= 3.5;

$pdf->SetXY(130, $Y);
$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell(35, 3.5, $implementing_agency, 'B', 0, 'C');

$pdf->SetXY(165, $Y);
$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell(35, 3.5, 'Cooperating Agency', 'B', 0, 'C');

//START OF PS LOGIC
$pdf->SetXY(10, $Y);
$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell(5, 3.5, 'I.', 0, 0, 'L');
$pdf->Cell(60, 3.5, 'PERSONAL SERVICES (PS)' , 0, 1, 'L');

$query = $this->db->query("
SELECT
    a.`particulars`,
    a.`approved_budget`,
    (SELECT `is_direct_cost` FROM tbl_uacs WHERE `object_of_expenditures` = a.particulars LIMIT 1) AS is_direct_cost,
    (SELECT `is_indirect_cost` FROM tbl_uacs WHERE `object_of_expenditures` = a.particulars LIMIT 1) AS is_indirect_cost,
    (SELECT cat.`expenditure_category`
     FROM tbl_uacs_category cat
     JOIN tbl_uacs uac ON cat.recid = uac.uacs_category_id
     WHERE uac.`object_of_expenditures` = a.particulars
     LIMIT 1) AS expenditure_category
FROM
    `tbl_budget_ps_dt` a
WHERE 
    `project_id` = '$recid'
ORDER BY
is_direct_cost DESC, is_indirect_cost ASC, expenditure_category DESC, particulars ASC"
);

$data = $query->getResultArray();
$total_ps = 0;
$last_cost_type = '';
$last_expenditure_category = '';

foreach ($data as $row) {
    $particulars = $row['particulars'];
    $approved_budget = $row['approved_budget'];
    $is_direct_cost = $row['is_direct_cost'];
    $is_indirect_cost = $row['is_indirect_cost'];
    $expenditure_category = $row['expenditure_category'];

    // Prioritize Direct over Indirect
    if ($is_direct_cost == '1') {
        $cost_type = 'Direct';
    } elseif ($is_indirect_cost == '1') {
        $cost_type = 'Indirect';
    } else {
        $cost_type = '';
    }

    // Print Cost Type Header if it changes
    if ($cost_type !== $last_cost_type && $cost_type !== '') {
        $Y += 3;
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->SetXY(10, $Y);
        $pdf->Cell(5, 3.5, '', 0, 0, 'L');
        $pdf->Cell(60, 3.5, $cost_type . ' Cost:', 0, 1, 'L');
        $Y += 3.5;
        $last_cost_type = $cost_type;
        $last_expenditure_category = ''; // Reset category heading
    }

    // Print Expenditure Category if it changes
    if ($expenditure_category !== $last_expenditure_category && $expenditure_category !== null) {
        $pdf->SetFont('Arial', '', 7);
        $pdf->SetXY(10, $Y);
        $pdf->Cell(5, 3.5, '', 0, 0, 'L');
        $pdf->Cell(60, 3.5, $expenditure_category, 0, 1, 'L');
        $Y += 3.5;
        $last_expenditure_category = $expenditure_category;
    }

    // Print Particulars
    $pdf->SetFont('Arial', 'I', 7);
    $pdf->SetXY(10, $Y);
    $pdf->Cell(5, 3.5, '', 0, 0, 'L');
    $pdf->Cell(60, 3.5, $particulars, 0, 1, 'L');

    // Print Budget
    $pdf->SetXY(130, $Y);
    $pdf->Cell(32, 3.5, number_format($approved_budget, 2), 0, 1, 'C');

    $pdf->SetXY(168, $Y);
    $pdf->Cell(32, 3.5, '------', 0, 1, 'C');

    $total_ps += $approved_budget;
    $Y += 3.5;
}


$Y+= 3.5;

//P IN HONORARIA
$pdf->SetFont('Arial', 'B', 7);
$pdf->SetXY(90, $Y);
$pdf->Cell(20, 3.5, 'TOTAL PS' , 0, 1, 'L');

$pdf->SetFont('Arial', 'B', 7);
$pdf->SetXY(126, $Y);
$pdf->Cell(5, 3.5, 'P' , 0, 1, 'L');
$pdf->SetXY(130, $Y);
$pdf->Cell(32, 3.5, number_format($total_ps,2) , 'T', 1, 'C');
$pdf->SetXY(163, $Y);
$pdf->Cell(5, 3.5, 'P' , 0, 1, 'L');
$pdf->SetXY(168, $Y);
$pdf->Cell(32, 3.5, '' , 'T', 1, 'L');

$Y+= 3.5;

// MOOE LOGIC START -------------------------------------------------------------------------------------------------------------------
$pdf->SetXY(10, $Y);
$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell(5, 3.5, 'II.', 0, 0, 'L');
$pdf->Cell(60, 3.5, 'MAINTENANCE AND OTHER OPERATING EXPENSES (MOOE)' , 0, 1, 'L');
$query = $this->db->query("
    SELECT
        b.`particulars`,
        b.`approved_budget`,
        (SELECT `is_direct_cost` FROM tbl_uacs WHERE `object_of_expenditures` = b.particulars LIMIT 1) AS is_direct_cost,
        (SELECT `is_indirect_cost` FROM tbl_uacs WHERE `object_of_expenditures` = b.particulars LIMIT 1) AS is_indirect_cost,
        (SELECT expenditure_category 
         FROM tbl_uacs_category uc 
         JOIN tbl_uacs u ON uc.recid = u.uacs_category_id
         WHERE u.object_of_expenditures = b.particulars 
         LIMIT 1) AS expenditure_category
    FROM
        `tbl_budget_mooe_dt` b
    WHERE 
        `project_id` = '$recid'
    ORDER BY
        is_direct_cost DESC, is_indirect_cost ASC, expenditure_category ASC, particulars ASC
");

$data = $query->getResultArray();
$total_mooe = 0;
$total_mooe = 0;

$last_cost_type = '';
$printed_categories = [];

foreach ($data as $row) {
    $particulars = $row['particulars'];
    $approved_budget = $row['approved_budget'];
    $expenditure_category = $row['expenditure_category'];
    $is_direct_cost = $row['is_direct_cost'];
    $is_indirect_cost = $row['is_indirect_cost'];

    // Determine cost type
    $cost_type = $is_direct_cost == '1' ? 'Direct' : ($is_indirect_cost == '1' ? 'Indirect' : '');

    // Show cost type heading only once
    if ($cost_type !== $last_cost_type) {
        $Y += 3;
        $pdf->SetFont('Arial', 'B', 7);
        $pdf->SetXY(10, $Y);
        $pdf->Cell(5, 3.5, '', 0, 0, 'L');
        $pdf->Cell(60, 3.5, $cost_type . ' Cost:', 0, 1, 'L');
        $Y += 3.5;

        $last_cost_type = $cost_type;
        $printed_categories = []; // reset category tracker on cost type change
    }

    // Show category only if it hasn't been printed under current cost type
    if (!in_array($expenditure_category, $printed_categories)) {
        $pdf->SetFont('Arial', '', 7);
        $pdf->SetXY(10, $Y);
        $pdf->Cell(5, 3.5, '', 0, 0, 'L');
        $pdf->Cell(60, 3.5, $expenditure_category, 0, 1, 'L');
        $Y += 3.5;

        $printed_categories[] = $expenditure_category;
    }

    // Print particulars
    $pdf->SetXY(15, $Y);
    $pdf->SetFont('Arial', 'I', 7);
    $pdf->Cell(5, 3.5, '', 0, 0, 'L');
    $pdf->Cell(100, 3.5, $particulars , 0, 0, 'L');

    $pdf->SetXY(130, $Y);
    $pdf->Cell(32, 3.5, number_format($approved_budget, 2), 0, 0, 'C');

    $pdf->SetXY(168, $Y);
    $pdf->Cell(32, 3.5, '------', 0, 1, 'C');

    $total_mooe += $approved_budget;
    $Y += 3.5;
}




//P IN HONORARIA
$pdf->SetFont('Arial', 'B', 7);
$pdf->SetXY(90, $Y);
$pdf->Cell(20, 3.5, 'TOTAL FOR MOOE' , 0, 1, 'L');

$pdf->SetFont('Arial', 'B', 7);
$pdf->SetXY(126, $Y);
$pdf->Cell(5, 3.5, 'P' , 0, 1, 'L');
$pdf->SetXY(130, $Y);
$pdf->Cell(32, 3.5, number_format($total_mooe,2) , 'T', 1, 'C');
$pdf->SetXY(163, $Y);
$pdf->Cell(5, 3.5, 'P' , 0, 1, 'L');
$pdf->SetXY(168, $Y);
$pdf->Cell(32, 3.5, '' , 'T', 1, 'L');

//CO LOGIC START ------------------------------------------------------------------------------------------------------------------------------------------------

$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);

$pdf->SetXY(8, 10);

// Add image
$x = 10; // X position
$y = 12;   // Y position
$width = 16; // Width of the image
$height = 16; // Height of the image (you can adjust this based on your needs)

$pdf->Image(ROOTPATH . 'public/assets/images/logos/fnrilogo.png', $x, $y, $width, $height);

$X = 0;
$Y = 8;
$pdf->SetFont('Arial', '', 7);
$pdf->Cell($X,$Y,'DOST Form 4',0,1,'C');

$pdf->SetFont('Arial', 'B', 7.5);
$Y = 4;
$pdf->Cell($X,$Y,'DEPARTMENT OF SCIENCE AND TECHNOLOGY',0,1,'C');
$pdf->Cell($X,$Y,'Project Line-Item Budget',0,1,'C');
$pdf->Cell($X,$Y,'CY _____',0,1,'C');

//spacer
$pdf->Cell($X,4,'',0,1,'L');

$pdf->SetFont('Arial', '', 7);

// Program Title (wraps if long, ':' separated)
$pdf->Cell(40, 3.5, 'Program Title', 0, 0, 'L'); // Label
$pdf->Cell(5, 3.5, ':', 0, 0, 'L');              // Colon

$X = $pdf->GetX(); // Save current X
$Y = $pdf->GetY(); // Save current Y

$pdf->SetXY($X, $Y); // Set cursor at value position
$pdf->MultiCell(145, 3.5, $program_title, 0, 'L');

// Optional: add a small line break or spacing after to separate rows
$pdf->Ln(1);

// Project Title (wraps if long)
$pdf->Cell(40, 3.5, 'Project Title', 0, 0, 'L');
$pdf->Cell(5, 3.5, ':', 0, 0, 'L');              // Colon

$X = $pdf->GetX(); // Save current X
$Y = $pdf->GetY(); // Save current Y

$pdf->SetXY($X, $Y); // Set cursor at value position
$pdf->MultiCell(145, 3.5, $project_title, 0, 'L');


$X = $pdf->GetX(); // Save current X
$Y = $pdf->GetY(); // Save current Y
// Implementing Agency (below the wrapped Project Title)
$pdf->Cell(40, 3.5, 'Implementing Agency', 0, 0, 'L');
$pdf->Cell(5, 3.5, ':', 0, 0, 'L');
$pdf->Cell(60, 3.5, $implementing_agency, 0, 1, 'L');

$pdf->Cell(40, 3.5, 'Total Duration', 0, 0, 'L');
$pdf->Cell(5, 3.5, ':', 0, 0, 'L');
$pdf->Cell(60, 3.5, $total_duration, 0, 1, 'L');

$pdf->Cell(40, 3.5, 'Current Duration', 0, 0, 'L');
$pdf->Cell(5, 3.5, ':', 0, 0, 'L');
$pdf->Cell(60, 3.5, $duration_from . ' - ' . $duration_to, 0, 1, 'L');

$pdf->Cell(40, 3.5, 'Collaborating Agency', 0, 0, 'L');
$pdf->Cell(5, 3.5, ':', 0, 0, 'L');              // Colon

$X = $pdf->GetX(); // Save current X
$Y = $pdf->GetY(); // Save current Y

$pdf->SetXY($X, $Y); // Set cursor at value position
$pdf->MultiCell(145, 3.5, $collaborating_agencies, 0, 'L');

$X = $pdf->GetX(); // Save current X
$Y = $pdf->GetY(); // Save current Y
$pdf->Cell(40, 3.5, 'Program Leader', 0, 0, 'L');
$pdf->Cell(5, 3.5, ':', 0, 0, 'L');
$pdf->Cell(60, 3.5, $program_leader, 0, 1, 'L');

$pdf->Cell(40, 3.5, 'Monitoring Agency', 0, 0, 'L');
$pdf->Cell(5, 3.5, ':', 0, 0, 'L');
$pdf->Cell(60, 3.5, $monitoring_agency, 0, 1, 'L');

$X = $pdf->GetX(); // Save current X
$Y = $pdf->GetY(); // Save current Y

$pdf->SetXY(130, $Y);
$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell(70, 3.5, 'Counterpart Funding', 'B', 0, 'C');

$Y+= 3.5;

$pdf->SetXY(130, $Y);
$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell(35, 3.5, $implementing_agency, 'B', 0, 'C');

$pdf->SetXY(165, $Y);
$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell(35, 3.5, 'Cooperating Agency', 'B', 0, 'C');

$pdf->SetXY(10, $Y);
$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell(5, 3.5, 'III.', 0, 0, 'L');
$pdf->Cell(60, 3.5, 'CAPITAL OUTLAY (CO)' , 0, 1, 'L');

$query = $this->db->query("
SELECT
    b.`particulars`,
    b.`approved_budget`
FROM
    `tbl_budget_co_dt` b
WHERE 
    `project_id` = '$recid'

");

$data = $query->getResultArray();
$total_co = 0;

foreach ($data as $row) {
    $particulars = $row['particulars'];
    $approved_budget = $row['approved_budget'];


    // Print the item line
    $Y += 3.5;
    $pdf->SetXY(10, $Y);
    $pdf->SetFont('Arial', 'I', 7);
    $pdf->Cell(5, 3.5, '', 0, 0, 'L');
    $pdf->Cell(15, 3.5, $particulars , 0, 1, 'L');

    $pdf->SetXY(130, $Y);
    $pdf->Cell(32, 3.5, number_format($approved_budget, 2) , 0, 1, 'C');

    $pdf->SetXY(168, $Y);
    $pdf->Cell(32, 3.5, '------' , 0, 1, 'C');

    $total_co += $approved_budget;
}

$Y+= 3.5;

//P IN HONORARIA
$pdf->SetFont('Arial', 'B', 7);
$pdf->SetXY(90, $Y);
$pdf->Cell(20, 3.5, 'TOTAL FOR CO' , 0, 1, 'L');

$pdf->SetFont('Arial', 'B', 7);
$pdf->SetXY(126, $Y);
$pdf->Cell(5, 3.5, 'P' , 0, 1, 'L');
$pdf->SetXY(130, $Y);
$pdf->Cell(32, 3.5, number_format($total_co,2) , 'T', 1, 'C');
$pdf->SetXY(163, $Y);
$pdf->Cell(5, 3.5, 'P' , 0, 1, 'L');
$pdf->SetXY(168, $Y);
$pdf->Cell(32, 3.5, '------' , 'T', 1, 'C');

$Y+= 7;

$grand_total = $total_ps + $total_mooe + $total_co;
//P IN HONORARIA
$pdf->SetFont('Arial', 'B', 7);
$pdf->SetXY(90, $Y);
$pdf->Cell(20, 3.5, 'GRAND TOTAL' , 0, 1, 'L');

$pdf->SetFont('Arial', 'B', 7);
$pdf->SetXY(126, $Y);
$pdf->Cell(5, 3.5, 'P' , 0, 1, 'L');
$pdf->SetXY(130, $Y);
$pdf->Cell(32, 3.5, number_format($grand_total,2) , 'B', 1, 'C');
$pdf->SetXY(163, $Y);
$pdf->Cell(5, 3.5, 'P' , 0, 1, 'L');
$pdf->SetXY(168, $Y);
$pdf->Cell(32, 3.5, '' , 'B', 1, 'L');



$pdf->Output();
exit;
?>