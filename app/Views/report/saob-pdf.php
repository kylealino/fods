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

$date_from = '';
$date_to = '';


if ($month == 'January') {
    $date_from = $year . '-01-01';
    $date_to = $year . '-02-01';
}elseif ($month == 'February') {
    $date_from = $year . '-02-01';
    $date_to = $year . '-03-01';
}elseif ($month == 'March') {
    $date_from = $year . '-03-01';
    $date_to = $year . '-04-01';
}elseif ($month == 'April') {
    $date_from = $year . '-04-01';
    $date_to = $year . '-05-01';
}elseif ($month == 'May') {
    $date_from = $year . '-05-01';
    $date_to = $year . '-06-01';
}elseif ($month == 'June') {
    $date_from = $year . '-06-01';
    $date_to = $year . '-07-01';
}elseif ($month == 'July') {
    $date_from = $year . '-07-01';
    $date_to = $year . '-08-01';
}elseif ($month == 'August') {
    $date_from = $year . '-08-01';
    $date_to = $year . '-09-01';
}elseif ($month == 'September') {
    $date_from = $year . '-09-01';
    $date_to = $year . '-10-01';
}elseif ($month == 'October') {
    $date_from = $year . '-10-01';
    $date_to = $year . '-11-01';
}elseif ($month == 'November') {
    $date_from = $year . '-11-01';
    $date_to = $year . '-12-01';
}elseif ($month == 'December') {
    $date_from = $year . '-12-01';
    $date_to = $year . '-12-31';
}else{
    $date_from = $year . '-01-01';
    $date_to = $year . '-02-01';
}

$query = $this->db->query("
SELECT
    `project_leader`,
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
$project_leader = $data['project_leader'];
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
        $this->SetY(-15);
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

function printTableHeader($pdf, $month) {
    $Y = $pdf->GetY() + 2; // adjust as needed

    // Reset Y if close to bottom of page
    if ($Y < 270) {

        $Y = 42.5;

    }else{
        $pdf->AddPage();
        $Y = $pdf->GetY()+4.5;
    }

    $pdf->SetXY(10, $Y);

    $pdf->SetFont('Arial', 'B', 7);
    $pdf->Cell(64, 3.5, '', 'TRL', 0, 'C');//ROW 1
    $pdf->Cell(22, 3.5, '', 'TRL', 0, 'C');
    $pdf->Cell(22, 3.5, '', 'TRL', 0, 'C');
    $pdf->Cell(44, 3.5, '', 'TRL', 0, 'C');
    $pdf->Cell(22, 3.5, '', 'TRL', 0, 'C');
    $pdf->Cell(17, 3.5, '', 'TRL', 0, 'C');
    $Y+= 3.5;

    $pdf->SetXY(10, $Y);
    $pdf->Cell(64, 3.5, '', 'RL', 0, 'C');
    $pdf->Cell(22, 3.5, '', 'RL', 0, 'C');
    $pdf->Cell(22, 3.5, '', 'RL', 0, 'C');
    $pdf->Cell(44, 3.5, 'Obligation Incurred', 'BRL', 0, 'C');
    $pdf->Cell(22, 3.5, 'Unobligated', 'RL', 0, 'C');
    $pdf->Cell(17, 3.5, '', 'RL', 0, 'C');
    $Y+= 3.5;

    $pdf->SetXY(10, $Y);
    $pdf->Cell(64, 3.5, 'OBJECT OF EXPENDITURE', 'RL', 0, 'C');
    $pdf->Cell(22, 3.5, '', 'RL', 0, 'C');
    $pdf->Cell(22, 3.5, 'Revised', 'RL', 0, 'C');
    $pdf->Cell(22, 3.5, 'This month', 'RL', 0, 'C');
    $pdf->Cell(22, 3.5, 'Obligation', 'RL', 0, 'C');
    $pdf->Cell(22, 3.5, 'Balance of', 'RL', 0, 'C');
    $pdf->Cell(17, 3.5, 'Percent', 'RL', 0, 'C');
    $Y+= 3.5;

    $pdf->SetXY(10, $Y);
    $pdf->Cell(64, 3.5, '', 'RL', 0, 'C');
    $pdf->Cell(22, 3.5, 'UACS', 'RL', 0, 'C');
    $pdf->Cell(22, 3.5, 'Allotment', 'RL', 0, 'C');
    $pdf->Cell(22, 3.5, $month, 'RL', 0, 'C');
    $pdf->Cell(22, 3.5, 'To Date', 'RL', 0, 'C');
    $pdf->Cell(22, 3.5, 'Allotment', 'RL', 0, 'C');
    $pdf->Cell(17, 3.5, 'Of', 'RL', 0, 'C');
    $Y+= 3.5;

    $pdf->SetXY(10, $Y);
    $pdf->Cell(64, 3.5, '', 'BRL', 0, 'C');
    $pdf->Cell(22, 3.5, '(1)', 'BRL', 0, 'C');
    $pdf->Cell(22, 3.5, '(4)', 'BRL', 0, 'C');
    $pdf->Cell(22, 3.5, '(5)', 'BRL', 0, 'C');
    $pdf->Cell(22, 3.5, '(6)', 'BRL', 0, 'C');
    $pdf->Cell(22, 3.5, '(7)=(4)-(6)', 'BRL', 0, 'C');
    $pdf->Cell(17, 3.5, 'Utilization', 'BRL', 0, 'C');
    $Y+= 3.5;

    // $pdf->SetXY(10, $Y);
    // $pdf->SetFont('Arial', 'B', 9);
    // $pdf->Cell(64, 7, 'CURRENT YEAR BUDGET', 'BRL', 0, 'L');
    // $pdf->Cell(22, 7, '', 'BRL', 0, 'C');
    // $pdf->Cell(22, 7, '', 'BRL', 0, 'C');
    // $pdf->Cell(22, 7, '', 'BRL', 0, 'C');
    // $pdf->Cell(22, 7, '', 'BRL', 0, 'C');
    // $pdf->Cell(22, 7, '', 'BRL', 0, 'C');
    // $pdf->Cell(17, 7, '', 'BRL', 0, 'C');

    


    return $Y + 10; // return new Y position
}


$Y = printTableHeader($pdf, $month);


$total_curryear_budget = 0;
$total_year_ps = 0;
$total_year_mooe = 0;
$total_year_co = 0;
$total_sub_month_amount = 0;
$total_sub_year_amount = 0;
$program_tagging = "";
$program_class = "";

$total_todate_month = 0;
$ps_total_unobligated = 0;
$mooe_total_unobligated = 0;
$co_total_unobligated = 0;
$ps_percentage_minus = 0;
$mooe_percentage_minus = 0;
$co_percentage_minus = 0;
$total_project_budget = 0;
$ps_grand_total = 0;
$mooe_grand_total = 0;
$co_grand_total = 0;
$thismonth_grand_total = 0;

$ps_todate_grand_total = 0;
$mooe_todate_grand_total = 0;
$co_todate_grand_total = 0;
$todate_grand_total = 0;

$grand_unobligated = 0;
$grand_percentage_minus = 0;
$printed_once = '';
$printed_after_mooe = '';
//CURRENT YEAR BUDGET

$pdf->SetXY(10, $Y -10);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(191, 5, 'CURRENT YEAR BUDGET', 1, 1, 'L');

$pdf->SetXY(10, $Y -5);
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(191, 5, 'A. Programs', 1, 1, 'L');

$query = $this->db->query("
    SELECT
        SUM(
            COALESCE((SELECT SUM(approved_budget) FROM tbl_saob_ps_dt WHERE project_id = a.`recid`), 0) +
            COALESCE((SELECT SUM(approved_budget) FROM tbl_saob_mooe_dt WHERE project_id = a.`recid`), 0) +
            COALESCE((SELECT SUM(approved_budget) FROM tbl_saob_co_dt WHERE project_id = a.`recid`), 0)
        ) AS total_approved_budget
    FROM
        `tbl_saob_hd` a
    WHERE 
        a.`program_title` like '%General Administration and%'
    ORDER BY a.`recid`
");
$rw = $query->getRowArray();
$total_project_budget = $rw['total_approved_budget'];

//CURRENT DATE GRAND TOTAL

//PS GRAND TOTAL
$query = $this->db->query("
    SELECT 
        SUM(total_sub_month) AS grand_total
    FROM (
        SELECT
            COALESCE((
                SELECT SUM(amount) 
                FROM tbl_ors_direct_ps_dt d 
                JOIN tbl_ors_hd hd ON d.`project_id` = hd.`recid`
                WHERE d.sub_object_code = b.particulars 
                AND hd.`ors_date` >= '$date_from' 
                AND hd.`ors_date` < '$date_to'
                AND d.`project_title` LIKE '%General Administration%'
            ), 0)
            +
            COALESCE((
                SELECT SUM(amount) 
                FROM tbl_ors_indirect_ps_dt i 
                JOIN tbl_ors_hd hd ON i.`project_id` = hd.`recid`
                WHERE i.sub_object_code = b.particulars 
                AND hd.`ors_date` >= '$date_from' 
                AND hd.`ors_date` < '$date_to'
                AND i.`project_title` LIKE '%General Administration%'
            ), 0) AS total_sub_month
        FROM tbl_saob_ps_dt AS b
        JOIN
        tbl_saob_hd saob
        on b.project_id = saob.recid
        WHERE
        saob.program_title LIKE '%General Administration and support%'
    ) AS t;
");
$rw = $query->getRowArray();
$ps_grand_total = $rw['grand_total'];

//MOOE GRAND TOTAL
$query = $this->db->query("
    SELECT 
        SUM(total_sub_month) AS grand_total
    FROM (
        SELECT
            COALESCE((
                SELECT SUM(amount) 
                FROM tbl_ors_direct_mooe_dt d 
                JOIN tbl_ors_hd hd ON d.`project_id` = hd.`recid`
                WHERE d.sub_object_code = b.particulars 
                AND hd.`ors_date` >= '$date_from' 
                AND hd.`ors_date` < '$date_to'
                AND d.`project_title` LIKE '%General Administration%'
            ), 0)
            +
            COALESCE((
                SELECT SUM(amount) 
                FROM tbl_ors_indirect_mooe_dt i 
                JOIN tbl_ors_hd hd ON i.`project_id` = hd.`recid`
                WHERE i.sub_object_code = b.particulars 
                AND hd.`ors_date` >= '$date_from' 
                AND hd.`ors_date` < '$date_to'
                AND i.`project_title` LIKE '%General Administration%'
            ), 0) AS total_sub_month
        FROM tbl_saob_mooe_dt AS b
        JOIN
        tbl_saob_hd saob
        on b.project_id = saob.recid
        WHERE
        saob.program_title LIKE '%General Administration and support%'
    ) AS t;
");
$rw = $query->getRowArray();
$mooe_grand_total = $rw['grand_total'];

//CO GRAND TOTAL
$query = $this->db->query("
    SELECT 
        SUM(total_sub_month) AS grand_total
    FROM (
        SELECT
            COALESCE((
                SELECT SUM(amount) 
                FROM tbl_ors_direct_co_dt d 
                JOIN tbl_ors_hd hd ON d.`project_id` = hd.`recid`
                WHERE d.sub_object_code = b.particulars 
                AND hd.`ors_date` >= '$date_from' 
                AND hd.`ors_date` < '$date_to'
                AND d.`project_title` LIKE '%General Administration%'
            ), 0)
            +
            COALESCE((
                SELECT SUM(amount) 
                FROM tbl_ors_indirect_co_dt i 
                JOIN tbl_ors_hd hd ON i.`project_id` = hd.`recid`
                WHERE i.sub_object_code = b.particulars 
                AND hd.`ors_date` >= '$date_from' 
                AND hd.`ors_date` < '$date_to'
                AND i.`project_title` LIKE '%General Administration%'
            ), 0) AS total_sub_month
        FROM tbl_saob_co_dt AS b
        JOIN
        tbl_saob_hd saob
        on b.project_id = saob.recid
        WHERE
        saob.program_title LIKE '%General Administration and support%'
    ) AS t;
");
$rw = $query->getRowArray();
$co_grand_total = $rw['grand_total'];

$thismonth_grand_total = $ps_grand_total + $mooe_grand_total + $co_grand_total;


//UP TO DATE GRAND TOTAL

//UP TO DATE PS GRAND TOTAL
$query = $this->db->query("
    SELECT 
        SUM(total_sub_month) AS grand_total
    FROM (
        SELECT
            COALESCE((
                SELECT SUM(amount) 
                FROM tbl_ors_direct_ps_dt d 
                JOIN tbl_ors_hd hd ON d.`project_id` = hd.`recid`
                WHERE d.sub_object_code = b.particulars 
                AND d.`project_title` LIKE '%General Administration and support%'
            ), 0)
            +
            COALESCE((
                SELECT SUM(amount) 
                FROM tbl_ors_indirect_ps_dt i 
                JOIN tbl_ors_hd hd ON i.`project_id` = hd.`recid`
                WHERE i.sub_object_code = b.particulars 
                AND i.`project_title` LIKE '%General Administration and support%'
            ), 0) AS total_sub_month
        FROM tbl_saob_ps_dt AS b
        JOIN
        tbl_saob_hd saob
        on b.project_id = saob.recid
        WHERE
        saob.program_title LIKE '%General Administration and support%'
    ) AS t;
");
$rw = $query->getRowArray();
$ps_todate_grand_total = $rw['grand_total'];

//UP TO DATE MOOE GRAND TOTAL
$query = $this->db->query("
    SELECT 
        SUM(total_sub_month) AS grand_total
    FROM (
        SELECT
            COALESCE((
                SELECT SUM(amount) 
                FROM tbl_ors_direct_mooe_dt d 
                JOIN tbl_ors_hd hd ON d.`project_id` = hd.`recid`
                WHERE d.sub_object_code = b.particulars 
                AND d.`project_title` LIKE '%General Administration%'
            ), 0)
            +
            COALESCE((
                SELECT SUM(amount) 
                FROM tbl_ors_indirect_mooe_dt i 
                JOIN tbl_ors_hd hd ON i.`project_id` = hd.`recid`
                WHERE i.sub_object_code = b.particulars 
                AND i.`project_title` LIKE '%General Administration%'
            ), 0) AS total_sub_month
        FROM tbl_saob_mooe_dt AS b
        JOIN
        tbl_saob_hd saob
        on b.project_id = saob.recid
        WHERE
        saob.program_title LIKE '%General Administration and support%'
    ) AS t;
");
$rw = $query->getRowArray();
$mooe_todate_grand_total = $rw['grand_total'];

//UP TO DATE CO GRAND TOTAL
$query = $this->db->query("
    SELECT 
        SUM(total_sub_month) AS grand_total
    FROM (
        SELECT
            COALESCE((
                SELECT SUM(amount) 
                FROM tbl_ors_direct_co_dt d 
                JOIN tbl_ors_hd hd ON d.`project_id` = hd.`recid`
                WHERE d.sub_object_code = b.particulars 
                AND d.`project_title` LIKE '%General Administration%'
            ), 0)
            +
            COALESCE((
                SELECT SUM(amount) 
                FROM tbl_ors_indirect_co_dt i 
                JOIN tbl_ors_hd hd ON i.`project_id` = hd.`recid`
                WHERE i.sub_object_code = b.particulars 
                AND i.`project_title` LIKE '%General Administration%'
            ), 0) AS total_sub_month
        FROM tbl_saob_co_dt AS b
        JOIN
        tbl_saob_hd saob
        on b.project_id = saob.recid
        WHERE
        saob.program_title LIKE '%General Administration and support%'
    ) AS t;
");
$rw = $query->getRowArray();
$co_todate_grand_total = $rw['grand_total'];

$todate_grand_total = $ps_todate_grand_total + $mooe_todate_grand_total + $co_todate_grand_total;
$grand_unobligated = $total_project_budget - $todate_grand_total;
if (!empty($total_project_budget) && $total_project_budget > 0) {
    $grand_percentage_minus = ($todate_grand_total / $total_project_budget) * 100;
}

$Y = $pdf->GetY();
$query = $this->db->query("
    SELECT
        a.`program_title`,
        a.`project_title`,
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
    WHERE 
        a.`program_title` like '%General Administration and%'
    ORDER BY a.`recid`
");
$hd_data = $query->getResultArray();
$total_all_sub_month = 0;
$total_all_todate_month = 0;
$ors_total_current_month = 0;

foreach ($hd_data as $hd_row) {
    $ps_current_month = 0;
    $mooe_current_month = 0;
    $co_current_month = 0;
    $ps_tolatest_month = 0;
    $mooe_tolatest_month = 0;
    $co_tolatest_month = 0;
    $total_unobligated = 0;
    $total_percentage_minus = 0;
    $program_title = $hd_row['program_title'];
    $project_title = $hd_row['project_title'];
    $recid = $hd_row['recid'];
    $total_ps = $hd_row['total_ps'];
    $total_mooe = $hd_row['total_mooe'];
    $total_co = $hd_row['total_co'];
    $total_approved_budget = $hd_row['total_approved_budget'];

    if ($project_title == 'General Administration and Support Services') {
        $program_tagging = 'General Administration and Support';
        $program_like = 'General Administration';
    }elseif ($project_title == 'Relocation and Construction of New DOST-FNRI') {
        $program_tagging = 'Relocation and Construction of New DOST-FNRI';
        $program_like = 'Relocation and Construction';
    }elseif($project_title == 'Administration of Personnel Benefits'){
        $program_tagging = 'Administration of Personnel Benefits';
        $program_like = 'Administration of Personnel';
    }


    //PS--------------------------------------------
    $query = $this->db->query("
        SELECT
            u.allotment_class,
            b.object_code,
            b.particulars AS sub_object_code,
            b.code AS uacs_code,
            b.approved_budget,
            COALESCE((
                SELECT SUM(amount) 
                FROM tbl_ors_direct_ps_dt d 
                JOIN
                tbl_ors_hd hd ON d.`project_id` = hd.`recid`
                WHERE d.sub_object_code = b.particulars AND hd.`ors_date` >= '$date_from' AND hd.`ors_date` < '$date_to' AND d.`project_title` like '%$program_tagging%'
            ), 0) + COALESCE((
                SELECT SUM(amount) 
                FROM tbl_ors_indirect_ps_dt i 
                JOIN
                tbl_ors_hd hd ON i.`project_id` = hd.`recid`
                WHERE i.sub_object_code = b.particulars AND hd.`ors_date` >= '$date_from' AND hd.`ors_date` < '$date_to' AND i.`project_title` like '%$program_tagging%'
            ), 0) AS total_sub_month,
            COALESCE((
                SELECT SUM(amount) 
                FROM tbl_ors_direct_ps_dt d 
                JOIN tbl_ors_hd hd ON d.project_id = hd.recid
                WHERE d.sub_object_code = b.particulars 
                AND d.project_title LIKE '%$program_tagging%'
            ), 0)
            + COALESCE((
                SELECT SUM(amount) 
                FROM tbl_ors_indirect_ps_dt i 
                JOIN tbl_ors_hd hd ON i.project_id = hd.recid
                WHERE i.sub_object_code = b.particulars
                AND i.project_title LIKE '%$program_tagging%'
            ), 0) AS total_sub_all
        FROM 
            tbl_saob_ps_dt AS b
        LEFT JOIN 
            mst_uacs AS u ON b.code = u.uacs_code
        WHERE 
            b.project_id = '$recid'
        ORDER BY 
            b.recid, b.particulars;

    ");
    $ps_data = $query->getResultArray();
    //total ps object code fetching
    $ps_object_code_totals = [];
    $ps_sub_month_totals = [];
    $ps_sub_year_totals = [];
    $last_object_code = '';
    
    $ps_total_current_month = 0;
    $ps_total_todate_month = 0;
    foreach ($ps_data as $ps_row) {
        $object_code = $ps_row['object_code'];
        $sub_object_code = $ps_row['sub_object_code'];
        $approved_budget = floatval($ps_row['approved_budget']);
        $total_sub_month = floatval($ps_row['total_sub_month']);
        $total_sub_all = floatval($ps_row['total_sub_all']);

        if (!isset($ps_object_code_totals[$object_code])) {
            $ps_object_code_totals[$object_code] = 0;
        }
        $ps_object_code_totals[$object_code] += $approved_budget;

        if (!isset($ps_sub_month_totals[$object_code])) {
            $ps_sub_month_totals[$object_code] = 0;
        }
        $ps_sub_month_totals[$object_code] += $total_sub_month;

        if (!isset($ps_sub_year_totals[$object_code])) {
            $ps_sub_year_totals[$object_code] = 0;
        }
        $ps_sub_year_totals[$object_code] += $total_sub_all;

        $ps_total_current_month += $total_sub_month;
        $ps_total_todate_month += $total_sub_all;

        $ps_total_unobligated = ($total_ps ?? 0) - ($ps_total_todate_month ?? 0);
        
        
        if (!empty($total_ps) && $total_ps > 0) {
            $ps_percentage_minus = ($ps_total_todate_month / $total_ps) * 100;   
        }


    }

    $ps_current_month += $ps_total_current_month;
    $ps_tolatest_month += $ps_total_todate_month;

    //MOOE------------------------------------------
    $query = $this->db->query("
        SELECT
            u.allotment_class,
            b.object_code,
            b.particulars AS sub_object_code,
            b.code AS uacs_code,
            b.approved_budget,
            COALESCE((
                SELECT SUM(amount) 
                FROM tbl_ors_direct_mooe_dt d 
                JOIN
                tbl_ors_hd hd ON d.`project_id` = hd.`recid`
                WHERE d.sub_object_code = b.particulars AND hd.`ors_date` >= '$date_from' AND hd.`ors_date` < '$date_to' AND d.`project_title` like '%$program_tagging%'
            ), 0) + COALESCE((
                SELECT SUM(amount) 
                FROM tbl_ors_indirect_mooe_dt i 
                JOIN
                tbl_ors_hd hd ON i.`project_id` = hd.`recid`
                WHERE i.sub_object_code = b.particulars AND hd.`ors_date` >= '$date_from' AND hd.`ors_date` < '$date_to' AND i.`project_title` like '%$program_tagging%'
            ), 0) AS total_sub_month,
            COALESCE((
                SELECT SUM(amount) 
                FROM tbl_ors_direct_mooe_dt d 
                JOIN tbl_ors_hd hd ON d.project_id = hd.recid
                WHERE d.sub_object_code = b.particulars
                AND d.project_title LIKE '%$program_tagging%'
            ), 0)
            + COALESCE((
                SELECT SUM(amount) 
                FROM tbl_ors_indirect_mooe_dt i 
                JOIN tbl_ors_hd hd ON i.project_id = hd.recid
                WHERE i.sub_object_code = b.particulars
                AND i.project_title LIKE '%$program_tagging%'
            ), 0) AS total_sub_all
        FROM 
            tbl_saob_mooe_dt AS b
        LEFT JOIN 
            mst_uacs AS u ON b.code = u.uacs_code
        WHERE 
            b.project_id = '$recid'
        ORDER BY 
            b.recid, b.particulars;

    ");
    $mooe_data = $query->getResultArray();
    //total mooe object code fetching
    $mooe_object_code_totals = [];
    $mooe_sub_month_totals = [];
    $mooe_sub_year_totals = [];
    $last_object_code = '';
    $mooe_total_current_month = 0;
    $mooe_total_todate_month = 0;
    foreach ($mooe_data as $mooe_row) {
        $object_code = $mooe_row['object_code'];
        $sub_object_code = $mooe_row['sub_object_code'];
        $approved_budget = floatval($mooe_row['approved_budget']);
        $total_sub_month = floatval($mooe_row['total_sub_month']);
        $total_sub_all = floatval($mooe_row['total_sub_all']);

        if (!isset($mooe_object_code_totals[$object_code])) {
            $mooe_object_code_totals[$object_code] = 0;
        }
        $mooe_object_code_totals[$object_code] += $approved_budget;

        if (!isset($mooe_sub_month_totals[$object_code])) {
            $mooe_sub_month_totals[$object_code] = 0;
        }
        $mooe_sub_month_totals[$object_code] += $total_sub_month;

        if (!isset($mooe_sub_year_totals[$object_code])) {
            $mooe_sub_year_totals[$object_code] = 0;
        }
        $mooe_sub_year_totals[$object_code] += $total_sub_all;

        $mooe_total_current_month += $total_sub_month;
        $mooe_total_todate_month += $total_sub_all;

        $mooe_total_unobligated = ($total_mooe ?? 0) - ($mooe_total_todate_month ?? 0);
        
        
        if (!empty($total_mooe) && $total_mooe > 0) {
            $mooe_percentage_minus = ($mooe_total_todate_month / $total_mooe) * 100;
        }
          
    }
    $mooe_current_month += $mooe_total_current_month;
    $mooe_tolatest_month += $mooe_total_todate_month;

    //CO--------------------------------------------
    $query = $this->db->query("
        SELECT
            u.allotment_class,
            b.object_code,
            b.particulars AS sub_object_code,
            b.code AS uacs_code,
            b.approved_budget,
            COALESCE((
                SELECT SUM(amount) 
                FROM tbl_ors_direct_co_dt d 
                JOIN
                tbl_ors_hd hd ON d.`project_id` = hd.`recid`
                WHERE d.sub_object_code = b.particulars AND hd.`ors_date` >= '$date_from' AND hd.`ors_date` < '$date_to' AND d.`project_title` like '%$program_tagging%'
            ), 0) + COALESCE((
                SELECT SUM(amount) 
                FROM tbl_ors_indirect_co_dt i 
                JOIN
                tbl_ors_hd hd ON i.`project_id` = hd.`recid`
                WHERE i.sub_object_code = b.particulars AND hd.`ors_date` >= '$date_from' AND hd.`ors_date` < '$date_to' AND i.`project_title` like '%$program_tagging%'
            ), 0) AS total_sub_month,
            COALESCE((
                SELECT SUM(amount) 
                FROM tbl_ors_direct_co_dt d 
                JOIN tbl_ors_hd hd ON d.project_id = hd.recid
                WHERE d.sub_object_code = b.particulars
                AND d.project_title LIKE '%$program_tagging%'
            ), 0)
            + COALESCE((
                SELECT SUM(amount) 
                FROM tbl_ors_indirect_co_dt i 
                JOIN tbl_ors_hd hd ON i.project_id = hd.recid
                WHERE i.sub_object_code = b.particulars
                AND i.project_title LIKE '%$program_tagging%'
            ), 0) AS total_sub_all
        FROM 
            tbl_saob_co_dt AS b
        LEFT JOIN 
            mst_uacs AS u ON b.code = u.uacs_code
        WHERE 
            b.project_id = '$recid'
        ORDER BY 
            b.recid, b.particulars;

    ");
    $co_data = $query->getResultArray();
    //total co object code fetching
    $co_object_code_totals = [];
    $co_sub_month_totals = [];
    $co_sub_year_totals = [];
    $last_object_code = '';
    $co_total_current_month = 0;
    $co_total_todate_month = 0;
    foreach ($co_data as $co_row) {
        $object_code = $co_row['object_code'];
        $sub_object_code = $co_row['sub_object_code'];
        $approved_budget = floatval($co_row['approved_budget']);
        $total_sub_month = floatval($co_row['total_sub_month']);
        $total_sub_all = floatval($co_row['total_sub_all']);

        if (!isset($co_object_code_totals[$object_code])) {
            $co_object_code_totals[$object_code] = 0;
        }
        $co_object_code_totals[$object_code] += $approved_budget;

        if (!isset($co_sub_month_totals[$object_code])) {
            $co_sub_month_totals[$object_code] = 0;
        }
        $co_sub_month_totals[$object_code] += $total_sub_month;

        if (!isset($co_sub_year_totals[$object_code])) {
            $co_sub_year_totals[$object_code] = 0;
        }
        $co_sub_year_totals[$object_code] += $total_sub_all;

        $co_total_current_month += $total_sub_month;
        $co_total_todate_month += $total_sub_all;

        $co_total_unobligated = ($total_co ?? 0) - ($co_total_todate_month ?? 0);
        
        
        if (!empty($total_co) && $total_co > 0) {
            $co_percentage_minus = ($co_total_todate_month / $total_co) * 100;
        }
          
    }

    $co_current_month += $co_total_current_month;
    $co_tolatest_month += $co_total_todate_month;

    $total_all_sub_month = $ps_current_month + $mooe_current_month + $co_current_month;
    $total_all_todate_month = $ps_tolatest_month + $mooe_tolatest_month + $co_tolatest_month;
    $total_unobligated = $total_approved_budget - $total_all_todate_month;
    if (!empty($total_approved_budget) && $total_approved_budget > 0) {
        $total_percentage_minus = ($total_all_todate_month / $total_approved_budget) * 100;
    }

    if ($program_tagging == 'General Administration and Support') {
        // First, measure the height needed for program_tagging
        $startY = $Y;
        $pdf->SetXY(15, $Y);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(52, 3.5, $program_tagging, 0, 'R'); // Measure without border
        $afterProgramY = $pdf->GetY();
        $programHeight = $afterProgramY - $startY;

        // Draw complete row with borders
        $pdf->SetXY(10, $startY);
        $pdf->Cell(5, $programHeight, '', 1, 0, 'C');
        $pdf->SetXY(15, $startY);
        $pdf->Cell(52, $programHeight, '', 1, 0, 'R');
        $pdf->SetXY(67, $startY);
        $pdf->Cell(29, $programHeight, '', 1, 0, 'C'); // Gap column
        $pdf->SetXY(96, $startY);
        $pdf->Cell(22, $programHeight, '', 1, 0, 'C');
        $pdf->SetXY(118, $startY);
        $pdf->Cell(22, $programHeight, '', 1, 0, 'C');
        $pdf->SetXY(140, $startY);
        $pdf->Cell(22, $programHeight, '', 1, 0, 'C');
        $pdf->SetXY(162, $startY);
        $pdf->Cell(22, $programHeight, '', 1, 0, 'C');
        $pdf->SetXY(184, $startY);
        $pdf->Cell(17, $programHeight, '', 1, 0, 'C');

        // Draw text and values (centered vertically)
        $middleY = $startY + ($programHeight / 2) - 1.5;
        $pdf->SetXY(15, $middleY);
        $pdf->Cell(52, 3, '', 0, 0, 'R');
        
        $pdf->SetXY(96, $middleY);
        $pdf->Cell(22, 3, number_format($total_project_budget, 2), 0, 0, 'C');
        $pdf->SetXY(118, $middleY);
        $pdf->Cell(22, 3, number_format($thismonth_grand_total, 2), 0, 0, 'C');
        $pdf->SetXY(140, $middleY);
        $pdf->Cell(22, 3, number_format($todate_grand_total, 2), 0, 0, 'C');
        $pdf->SetXY(162, $middleY);
        $pdf->Cell(22, 3, number_format($grand_unobligated, 2), 0, 0, 'C');
        $pdf->SetXY(184, $middleY);
        $pdf->Cell(17, 3, number_format($grand_percentage_minus, 2) . '%', 0, 0, 'C');

        $Y = $afterProgramY;

    } elseif ($project_title == 'Relocation and Construction of New DOST-FNRI') {
        // Measure height for this special case
        $startY = $Y;
        $pdf->SetXY(15, $Y);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(186, 3.5, 'Locally-Funder Project', 0, 'L');
        $afterTextY = $pdf->GetY();
        $textHeight = $afterTextY - $startY;

        // Draw borders
        $pdf->SetXY(10, $startY);
        $pdf->Cell(191, $textHeight, '', 1, 0, 'C');
        
        // Draw text centered
        $middleY = $startY + ($textHeight / 2) - 1.5;
        $pdf->SetXY(15, $middleY);
        $pdf->Cell(186, 3, 'Locally-Funder Project', 0, 0, 'L');
        
        $Y = $afterTextY;

    } else {
        // Measure height for program_tagging
        $startY = $Y;
        $pdf->SetXY(15, $Y);
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(59, 3.5, $program_tagging, 0, 'L');
        $afterProgramY = $pdf->GetY();
        $programHeight = $afterProgramY - $startY;

        // Draw borders
        $pdf->SetXY(10, $startY);
        $pdf->Cell(191, $programHeight, '', 1, 0, 'C');
        
        // Draw text centered
        $middleY = $startY + ($programHeight / 2) - 1.5;
        $pdf->SetXY(15, $middleY);
        $pdf->Cell(59, 3, '', 0, 0, 'L');
        
        $Y = $afterProgramY;
    }

    // PROJECT TITLE SECTION (for all cases)
    $startY = $Y;

    // Measure height for project_title
    $pdf->SetXY(15, $Y);
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->MultiCell(59, 3.5, $project_title, 0, 'L');
    $afterProjectY = $pdf->GetY();
    $projectHeight = $afterProjectY - $startY;

    // Draw complete row with borders
    $pdf->SetXY(10, $startY);
    $pdf->Cell(5, $projectHeight, '', 1, 0, 'C');
    $pdf->SetXY(15, $startY);
    $pdf->Cell(70, $projectHeight, '', 1, 0, 'L');
    $pdf->SetXY(85, $startY);
    $pdf->Cell(11, $projectHeight, '', 1, 0, 'C');
    $pdf->SetXY(96, $startY);
    $pdf->Cell(22, $projectHeight, '', 1, 0, 'C');
    $pdf->SetXY(118, $startY);
    $pdf->Cell(22, $projectHeight, '', 1, 0, 'C');
    $pdf->SetXY(140, $startY);
    $pdf->Cell(22, $projectHeight, '', 1, 0, 'C');
    $pdf->SetXY(162, $startY);
    $pdf->Cell(22, $projectHeight, '', 1, 0, 'C');
    $pdf->SetXY(184, $startY);
    $pdf->Cell(17, $projectHeight, '', 1, 0, 'C');

    // Draw text and values (centered vertically)
    $middleY = $startY + ($projectHeight / 2) - 1.5;
    $pdf->SetXY(15, $middleY);
    $pdf->Cell(70, 3, '', 0, 0, 'L');

    $pdf->SetXY(96, $middleY);
    $pdf->Cell(22, 3, number_format($total_approved_budget, 2), 0, 0, 'C');
    $pdf->SetXY(118, $middleY);
    $pdf->Cell(22, 3, number_format($total_all_sub_month, 2), 0, 0, 'C');
    $pdf->SetXY(140, $middleY);
    $pdf->Cell(22, 3, number_format($total_all_todate_month, 2), 0, 0, 'C');
    $pdf->SetXY(162, $middleY);
    $pdf->Cell(22, 3, number_format($total_unobligated, 2), 0, 0, 'C');
    $pdf->SetXY(184, $middleY);
    $pdf->Cell(17, 3, number_format($total_percentage_minus, 2) . '%', 0, 0, 'C');

    $Y = $afterProjectY;
    // PROJECT TITLE SECTION (for all cases)
    $last_allotment_class = '';
    $last_sub_object_code = '';
    $last_object_code = '';

    
    if ($program_tagging == 'Relocation and Construction of New DOST-FNRI ') {
        $Y = $pdf->GetY();
    }else{
        $Y = $pdf->GetY()+5;
    }
    foreach ($ps_data as $ps_row) {
        $allotment_class = $ps_row['allotment_class'];
        $object_code = $ps_row['object_code'];
        $sub_object_code = $ps_row['sub_object_code'];
        $uacs_code = $ps_row['uacs_code'];
        $approved_budget = $ps_row['approved_budget'];

        $query = $this->db->query("
            SELECT COALESCE(SUM(amount),0) AS total_amount  
            FROM (
                SELECT dps.`amount` FROM tbl_ors_direct_ps_dt dps JOIN tbl_ors_hd hd on dps.`project_id` = hd.`recid` WHERE dps.`sub_object_code` = '$sub_object_code' AND hd.`ors_date` >= '$date_from' AND hd.`ors_date` < '$date_to' AND dps.`program_title` like '%$program_like%'
                UNION ALL
                SELECT idps.`amount` FROM tbl_ors_indirect_ps_dt idps JOIN tbl_ors_hd hd on idps.`project_id` = hd.`recid` WHERE idps.`sub_object_code` = '$sub_object_code' AND hd.`ors_date` >= '$date_from' AND hd.`ors_date` < '$date_to' AND idps.`program_title` like '%$program_like%'
            ) AS combined
        ");

        $result = $query->getRowArray();
        $month_amount = $result['total_amount'];

        $query = $this->db->query("
            SELECT COALESCE(SUM(amount),0) AS total_amount  
            FROM (
                SELECT dps.`amount` FROM tbl_ors_direct_ps_dt dps JOIN tbl_ors_hd hd on dps.`project_id` = hd.`recid` WHERE dps.`sub_object_code` = '$sub_object_code'  AND dps.`program_title` like '%$program_like%'
                UNION ALL
                SELECT idps.`amount` FROM tbl_ors_indirect_ps_dt idps JOIN tbl_ors_hd hd on idps.`project_id` = hd.`recid` WHERE idps.`sub_object_code` = '$sub_object_code' AND idps.`program_title` like '%$program_like%'
            ) AS combined
        ");

        $result = $query->getRowArray();
        $year_amount = $result['total_amount'];

        if ($Y > 265) {
            $pdf->AddPage();
            $Y = $pdf->GetY();
            $pdf->SetXY(10, $Y);
            $pdf->SetFont('Arial', 'B', 7);
            $pdf->Cell(64, 3.5, '', 'TRL', 0, 'C');//ROW 1
            $pdf->Cell(22, 3.5, '', 'TRL', 0, 'C');
            $pdf->Cell(22, 3.5, '', 'TRL', 0, 'C');
            $pdf->Cell(44, 3.5, '', 'TRL', 0, 'C');
            $pdf->Cell(22, 3.5, '', 'TRL', 0, 'C');
            $pdf->Cell(17, 3.5, '', 'TRL', 0, 'C');
            $Y+= 3.5;

            $pdf->SetXY(10, $Y);
            $pdf->Cell(64, 3.5, '', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, '', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, '', 'RL', 0, 'C');
            $pdf->Cell(44, 3.5, 'Obligation Incurred', 'BRL', 0, 'C');
            $pdf->Cell(22, 3.5, 'Unobligated', 'RL', 0, 'C');
            $pdf->Cell(17, 3.5, '', 'RL', 0, 'C');
            $Y+= 3.5;

            $pdf->SetXY(10, $Y);
            $pdf->Cell(64, 3.5, 'OBJECT OF EXPENDITURE', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, '', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, 'Revised', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, 'This month', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, 'Obligation', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, 'Balance of', 'RL', 0, 'C');
            $pdf->Cell(17, 3.5, 'Percent', 'RL', 0, 'C');
            $Y+= 3.5;

            $pdf->SetXY(10, $Y);
            $pdf->Cell(64, 3.5, '', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, 'UACS', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, 'Allotment', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, $month, 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, 'To Date', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, 'Allotment', 'RL', 0, 'C');
            $pdf->Cell(17, 3.5, 'Of', 'RL', 0, 'C');
            $Y+= 3.5;

            $pdf->SetXY(10, $Y);
            $pdf->Cell(64, 3.5, '', 'BRL', 0, 'C');
            $pdf->Cell(22, 3.5, '(1)', 'BRL', 0, 'C');
            $pdf->Cell(22, 3.5, '(4)', 'BRL', 0, 'C');
            $pdf->Cell(22, 3.5, '(5)', 'BRL', 0, 'C');
            $pdf->Cell(22, 3.5, '(6)', 'BRL', 0, 'C');
            $pdf->Cell(22, 3.5, '(7)=(4)-(6)', 'BRL', 0, 'C');
            $pdf->Cell(17, 3.5, 'Utilization', 'BRL', 0, 'C');

            $Y = $pdf->GetY()+3.5;
            // $pdf->SetXY(10, $Y);
            // $pdf->Cell(64, 215.5, '', 1, 0); 
            // $pdf->SetXY(74, $Y);
            // $pdf->Cell(22, 215.5, '', 1, 0); 
            // $pdf->SetXY(96, $Y);
            // $pdf->Cell(22, 215.5, '', 1, 0); 
            // $pdf->SetXY(118, $Y);
            // $pdf->Cell(22, 215.5, '', 1, 0); 
            // $pdf->SetXY(140, $Y);
            // $pdf->Cell(22, 215.5, '', 1, 0); 
            // $pdf->SetXY(162, $Y);
            // $pdf->Cell(22, 215.5, '', 1, 0); 
            // $pdf->SetXY(184, $Y);
            // $pdf->Cell(17, 215.5, '', 1, 0); 
        }

        // ================= ALLOTMENT CLASS ===================
        if ($allotment_class !== $last_allotment_class && $allotment_class !== null) {
            $pdf->SetFont('Arial', '', 8);

            // Save startY
            $startY = $Y;

            // Print text with wrapping
            $pdf->SetXY(10, $Y);
            $pdf->MultiCell(64, 4.5, $allotment_class, 0, 'L');

            // Measure row height
            $endY = $pdf->GetY();
            $totalRowHeight = $endY - $startY;

            // Draw borders
            $pdf->SetXY(10, $startY); $pdf->Cell(64, $totalRowHeight, '', 'L', 0, 'L');
            $pdf->SetXY(74, $startY); $pdf->Cell(22, $totalRowHeight, '', 0, 0, 'C');
            $pdf->SetXY(96, $startY); $pdf->Cell(22, $totalRowHeight, '', 0, 0, 'C');
            $pdf->SetXY(118, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
            $pdf->SetXY(140, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
            $pdf->SetXY(162, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
            $pdf->SetXY(184, $startY); $pdf->Cell(17, $totalRowHeight, '', 1, 0, 'C');

            // Center values vertically
            $middleY = $startY + ($totalRowHeight / 2) - 2.5;
            $pdf->SetFont('Arial', 'B', 8);

            $pdf->SetXY(96, $middleY); $pdf->Cell(22, 5, number_format($total_ps, 2), 1, 0, 'C');
            $pdf->SetXY(118, $middleY); $pdf->Cell(22, 5, number_format($ps_total_current_month, 2), 0, 0, 'C');
            $pdf->SetXY(140, $middleY); $pdf->Cell(22, 5, number_format($ps_total_todate_month, 2), 0, 0, 'C');
            $pdf->SetXY(162, $middleY); $pdf->Cell(22, 5, number_format($ps_total_unobligated, 2), 0, 0, 'C');
            $pdf->SetXY(184, $middleY); $pdf->Cell(17, 5, number_format($ps_percentage_minus, 2) . '%', 0, 0, 'C');

            $Y = $endY;
            $last_allotment_class = $allotment_class;
        }

        // ================= OBJECT CODE ===================
        if ($object_code !== $last_object_code && $object_code !== null) {
            $pdf->SetFont('Arial', '', 8);

            // Object Code: Start at X=15 (no blank cell before)
            $pdf->SetXY(10, $Y);
            $pdf->Cell(5, 4.5, '', 'L', 1, 'C');
            $pdf->SetXY(15, $Y);
            $pdf->MultiCell(59, 4.5, $object_code, 0, 'L');

            // Totals
            $total_for_object_code = $ps_object_code_totals[$object_code] ?? 0;
            $total_sub_month_amount = $ps_sub_month_totals[$object_code] ?? 0;
            $total_sub_year_amount = $ps_sub_year_totals[$object_code] ?? 0;
            $total_sub_unobligated = ($total_for_object_code ?? 0) - ($total_sub_year_amount ?? 0);

            // Columns
            $pdf->SetXY(96, $Y);
            $pdf->Cell(21.8, 4.5, number_format($total_for_object_code, 2), 1, 1, 'C');
            $pdf->SetXY(118, $Y);
            $pdf->Cell(21.8, 4.5, number_format($total_sub_month_amount, 2), 1, 1, 'C');
            $pdf->SetXY(140, $Y);
            $pdf->Cell(21.8, 4.5, number_format($total_sub_year_amount, 2), 1, 1, 'C');
            $pdf->SetXY(162, $Y);
            $pdf->Cell(21.8, 4.5, number_format($total_sub_unobligated, 2), 1, 1, 'C');
            $pdf->SetXY(184, $Y);
            $pdf->Cell(17, 4.5, '', 'R', 1, 'C');

            // Move down
            $Y += 4.5;
            $last_object_code = $object_code;
        }

        // ================= SUB OBJECT CODE ===================
        if ($sub_object_code !== $last_sub_object_code && $sub_object_code !== null) {
            $pdf->SetFont('Arial', '', 8);

            $startY = $Y;
            $pdf->SetXY(20, $Y);
            $pdf->MultiCell(55, 4.5, $sub_object_code, 0, 'L');

            $endY = $pdf->GetY();
            $totalRowHeight = $endY - $startY;

            if ($Y >255) {
                // Borders
                $pdf->SetXY(10, $startY); $pdf->Cell(64, $totalRowHeight, '', 'LB', 0, 'L');
                $pdf->SetXY(74, $startY); $pdf->Cell(22, $totalRowHeight, '', 'B', 0, 'C');
                $pdf->SetXY(96, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
                $pdf->SetXY(118, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
                $pdf->SetXY(140, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
                $pdf->SetXY(162, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
                $pdf->SetXY(184, $startY); $pdf->Cell(17, $totalRowHeight, '', 'RB', 0, 'C');
            }else{
                $pdf->SetXY(10, $startY); $pdf->Cell(64, $totalRowHeight, '', 'L', 0, 'L');
                $pdf->SetXY(74, $startY); $pdf->Cell(22, $totalRowHeight, '', 0, 0, 'C');
                $pdf->SetXY(96, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
                $pdf->SetXY(118, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
                $pdf->SetXY(140, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
                $pdf->SetXY(162, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
                $pdf->SetXY(184, $startY); $pdf->Cell(17, $totalRowHeight, '', 'R', 0, 'C');
            }

            // Center values
            $middleY = $startY + ($totalRowHeight / 2) - 2.5;

            $pdf->SetXY(74, $middleY); $pdf->Cell(22, 5, $uacs_code, 0, 0, 'C');
            $pdf->SetXY(96, $middleY); $pdf->Cell(22, 5, number_format($approved_budget,2), 0, 0, 'C');
            $pdf->SetXY(118, $middleY); $pdf->Cell(22, 5, $month_amount, 0, 0, 'C');
            $pdf->SetXY(140, $middleY); $pdf->Cell(22, 5, $year_amount, 0, 0, 'C');

            $subobligated_amount = $approved_budget - $year_amount;
            $pdf->SetXY(162, $middleY); $pdf->Cell(22, 5, number_format($subobligated_amount,2), 0, 0, 'C');
            $pdf->SetXY(184, $middleY); $pdf->Cell(17, 5, '', 0, 0, 'C');

            $Y = $endY;
            $last_sub_object_code = $sub_object_code;
        }

    }

    $last_allotment_class = '';
    $last_sub_object_code = '';
    $last_object_code = '';

    
    if ($project_title == 'Relocation and Construction of New DOST-FNRI') {
        $Y = $pdf->GetY();
    }else{
        $Y = $pdf->GetY()+4.5;
    }
    foreach ($mooe_data as $mooe_row) {
        $allotment_class = $mooe_row['allotment_class'];
        $object_code = $mooe_row['object_code'];
        $sub_object_code = $mooe_row['sub_object_code'];
        $uacs_code = $mooe_row['uacs_code'];
        $approved_budget = $mooe_row['approved_budget'];

        $query = $this->db->query("
            SELECT COALESCE(SUM(amount),0) AS total_amount  
            FROM (
                SELECT dmooe.`amount` FROM tbl_ors_direct_mooe_dt dmooe JOIN tbl_ors_hd hd on dmooe.`project_id` = hd.`recid` WHERE dmooe.`sub_object_code` = '$sub_object_code' AND hd.`ors_date` >= '$date_from' AND hd.`ors_date` < '$date_to' AND dmooe.`program_title` like '%$program_like%'
                UNION ALL
                SELECT idmooe.`amount` FROM tbl_ors_indirect_mooe_dt idmooe JOIN tbl_ors_hd hd on idmooe.`project_id` = hd.`recid` WHERE idmooe.`sub_object_code` = '$sub_object_code' AND hd.`ors_date` >= '$date_from' AND hd.`ors_date` < '$date_to' AND idmooe.`program_title` like '%$program_like%'
            ) AS combined
        ");

        $result = $query->getRowArray();
        $month_amount = $result['total_amount'];

        $query = $this->db->query("
            SELECT COALESCE(SUM(amount),0) AS total_amount  
            FROM (
                SELECT dmooe.`amount` FROM tbl_ors_direct_mooe_dt dmooe JOIN tbl_ors_hd hd on dmooe.`project_id` = hd.`recid` WHERE dmooe.`sub_object_code` = '$sub_object_code' AND dmooe.`program_title` like '%$program_like%'
                UNION ALL
                SELECT idmooe.`amount` FROM tbl_ors_indirect_mooe_dt idmooe JOIN tbl_ors_hd hd on idmooe.`project_id` = hd.`recid` WHERE idmooe.`sub_object_code` = '$sub_object_code' AND idmooe.`program_title` like '%$program_like%'
            ) AS combined
        ");

        $result = $query->getRowArray();
        $year_amount = $result['total_amount'];

        if ($Y > 265) {
            $pdf->AddPage();
            $Y = $pdf->GetY();
            $pdf->SetXY(10, $Y);
            $pdf->SetFont('Arial', 'B', 7);
            $pdf->Cell(64, 3.5, '', 'TRL', 0, 'C');//ROW 1
            $pdf->Cell(22, 3.5, '', 'TRL', 0, 'C');
            $pdf->Cell(22, 3.5, '', 'TRL', 0, 'C');
            $pdf->Cell(44, 3.5, '', 'TRL', 0, 'C');
            $pdf->Cell(22, 3.5, '', 'TRL', 0, 'C');
            $pdf->Cell(17, 3.5, '', 'TRL', 0, 'C');
            $Y+= 3.5;

            $pdf->SetXY(10, $Y);
            $pdf->Cell(64, 3.5, '', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, '', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, '', 'RL', 0, 'C');
            $pdf->Cell(44, 3.5, 'Obligation Incurred', 'BRL', 0, 'C');
            $pdf->Cell(22, 3.5, 'Unobligated', 'RL', 0, 'C');
            $pdf->Cell(17, 3.5, '', 'RL', 0, 'C');
            $Y+= 3.5;

            $pdf->SetXY(10, $Y);
            $pdf->Cell(64, 3.5, 'OBJECT OF EXPENDITURE', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, '', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, 'Revised', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, 'This month', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, 'Obligation', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, 'Balance of', 'RL', 0, 'C');
            $pdf->Cell(17, 3.5, 'Percent', 'RL', 0, 'C');
            $Y+= 3.5;

            $pdf->SetXY(10, $Y);
            $pdf->Cell(64, 3.5, '', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, 'UACS', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, 'Allotment', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, $month, 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, 'To Date', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, 'Allotment', 'RL', 0, 'C');
            $pdf->Cell(17, 3.5, 'Of', 'RL', 0, 'C');
            $Y+= 3.5;

            $pdf->SetXY(10, $Y);
            $pdf->Cell(64, 3.5, '', 'BRL', 0, 'C');
            $pdf->Cell(22, 3.5, '(1)', 'BRL', 0, 'C');
            $pdf->Cell(22, 3.5, '(4)', 'BRL', 0, 'C');
            $pdf->Cell(22, 3.5, '(5)', 'BRL', 0, 'C');
            $pdf->Cell(22, 3.5, '(6)', 'BRL', 0, 'C');
            $pdf->Cell(22, 3.5, '(7)=(4)-(6)', 'BRL', 0, 'C');
            $pdf->Cell(17, 3.5, 'Utilization', 'BRL', 0, 'C');

            $Y = $pdf->GetY()+3.5;
            // $pdf->SetXY(10, $Y);
            // $pdf->Cell(64, 215.5, '', 1, 0); 
            // $pdf->SetXY(74, $Y);
            // $pdf->Cell(22, 215.5, '', 1, 0); 
            // $pdf->SetXY(96, $Y);
            // $pdf->Cell(22, 215.5, '', 1, 0); 
            // $pdf->SetXY(118, $Y);
            // $pdf->Cell(22, 215.5, '', 1, 0); 
            // $pdf->SetXY(140, $Y);
            // $pdf->Cell(22, 215.5, '', 1, 0); 
            // $pdf->SetXY(162, $Y);
            // $pdf->Cell(22, 215.5, '', 1, 0); 
            // $pdf->SetXY(184, $Y);
            // $pdf->Cell(17, 215.5, '', 1, 0); 
        }

        // ================= ALLOTMENT CLASS ===================
        if ($allotment_class !== $last_allotment_class && $allotment_class !== null) {
            $pdf->SetFont('Arial', '', 8);

            // Save startY
            $startY = $Y;

            // Print text with wrapping
            $pdf->SetXY(10, $Y);
            $pdf->MultiCell(64, 4.5, $allotment_class, 0, 'L');

            // Measure row height
            $endY = $pdf->GetY();
            $totalRowHeight = $endY - $startY;

            // Draw borders
            $pdf->SetXY(10, $startY); $pdf->Cell(64, $totalRowHeight, '', 'L', 0, 'L');
            $pdf->SetXY(74, $startY); $pdf->Cell(22, $totalRowHeight, '', 0, 0, 'C');
            $pdf->SetXY(96, $startY); $pdf->Cell(22, $totalRowHeight, '', 0, 0, 'C');
            $pdf->SetXY(118, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
            $pdf->SetXY(140, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
            $pdf->SetXY(162, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
            $pdf->SetXY(184, $startY); $pdf->Cell(17, $totalRowHeight, '', 1, 0, 'C');

            // Center values vertically
            $middleY = $startY + ($totalRowHeight / 2) - 2.5;
            $pdf->SetFont('Arial', 'B', 8);

            $pdf->SetXY(96, $middleY); $pdf->Cell(22, 5, number_format($total_mooe, 2), 1, 0, 'C');
            $pdf->SetXY(118, $middleY); $pdf->Cell(22, 5, number_format($mooe_total_current_month, 2), 0, 0, 'C');
            $pdf->SetXY(140, $middleY); $pdf->Cell(22, 5, number_format($mooe_total_todate_month, 2), 0, 0, 'C');
            $pdf->SetXY(162, $middleY); $pdf->Cell(22, 5, number_format($mooe_total_unobligated, 2), 0, 0, 'C');
            $pdf->SetXY(184, $middleY); $pdf->Cell(17, 5, number_format($mooe_percentage_minus, 2) . '%', 0, 0, 'C');

            $Y = $endY;
            $last_allotment_class = $allotment_class;
        }

        // ================= OBJECT CODE ===================
        if ($object_code !== $last_object_code && $object_code !== null) {
            $pdf->SetFont('Arial', '', 8);

            // Object Code: Start at X=15 (no blank cell before)
            $pdf->SetXY(10, $Y);
            $pdf->Cell(5, 4.5, '', 'L', 1, 'C');
            $pdf->SetXY(15, $Y);
            $pdf->MultiCell(59, 4.5, $object_code, 0, 'L');

            // Totals
            $total_for_object_code = $mooe_object_code_totals[$object_code] ?? 0;
            $total_sub_month_amount = $mooe_sub_month_totals[$object_code] ?? 0;
            $total_sub_year_amount = $mooe_sub_year_totals[$object_code] ?? 0;
            $total_sub_unobligated = ($total_for_object_code ?? 0) - ($total_sub_year_amount ?? 0);

            // Columns
            $pdf->SetXY(96, $Y);
            $pdf->Cell(21.8, 4.5, number_format($total_for_object_code, 2), 1, 1, 'C');
            $pdf->SetXY(118, $Y);
            $pdf->Cell(21.8, 4.5, number_format($total_sub_month_amount, 2), 1, 1, 'C');
            $pdf->SetXY(140, $Y);
            $pdf->Cell(21.8, 4.5, number_format($total_sub_year_amount, 2), 1, 1, 'C');
            $pdf->SetXY(162, $Y);
            $pdf->Cell(21.8, 4.5, number_format($total_sub_unobligated, 2), 1, 1, 'C');
            $pdf->SetXY(184, $Y);
            $pdf->Cell(17, 4.5, '', 'R', 1, 'C');

            // Move down
            $Y += 4.5;
            $last_object_code = $object_code;
        }

        // ================= SUB OBJECT CODE ===================
        if ($sub_object_code !== $last_sub_object_code && $sub_object_code !== null) {
            $pdf->SetFont('Arial', '', 8);

            $startY = $Y;
            $pdf->SetXY(20, $Y);
            $pdf->MultiCell(55, 4.5, $sub_object_code, 0, 'L');

            $endY = $pdf->GetY();
            $totalRowHeight = $endY - $startY;

            if ($Y >262) {
                // Borders
                $pdf->SetXY(10, $startY); $pdf->Cell(64, $totalRowHeight, '', 'LB', 0, 'L');
                $pdf->SetXY(74, $startY); $pdf->Cell(22, $totalRowHeight, '', 'B', 0, 'C');
                $pdf->SetXY(96, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
                $pdf->SetXY(118, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
                $pdf->SetXY(140, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
                $pdf->SetXY(162, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
                $pdf->SetXY(184, $startY); $pdf->Cell(17, $totalRowHeight, '', 'RB', 0, 'C');
            }else{
                $pdf->SetXY(10, $startY); $pdf->Cell(64, $totalRowHeight, '', 'L', 0, 'L');
                $pdf->SetXY(74, $startY); $pdf->Cell(22, $totalRowHeight, '', 0, 0, 'C');
                $pdf->SetXY(96, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
                $pdf->SetXY(118, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
                $pdf->SetXY(140, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
                $pdf->SetXY(162, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
                $pdf->SetXY(184, $startY); $pdf->Cell(17, $totalRowHeight, '', 'R', 0, 'C');
            }

            // Center values
            $middleY = $startY + ($totalRowHeight / 2) - 2.5;

            $pdf->SetXY(74, $middleY); $pdf->Cell(22, 5, $uacs_code, 0, 0, 'C');
            $pdf->SetXY(96, $middleY); $pdf->Cell(22, 5, number_format($approved_budget,2), 0, 0, 'C');
            $pdf->SetXY(118, $middleY); $pdf->Cell(22, 5, $month_amount, 0, 0, 'C');
            $pdf->SetXY(140, $middleY); $pdf->Cell(22, 5, $year_amount, 0, 0, 'C');

            $subobligated_amount = $approved_budget - $year_amount;
            $pdf->SetXY(162, $middleY); $pdf->Cell(22, 5, number_format($subobligated_amount,2), 0, 0, 'C');
            $pdf->SetXY(184, $middleY); $pdf->Cell(17, 5, '', 0, 0, 'C');

            $Y = $endY;
            $last_sub_object_code = $sub_object_code;
        }

    }

    $last_allotment_class = '';
    $last_sub_object_code = '';
    $last_object_code = '';

    if ($project_title == 'Relocation and Construction of New DOST-FNRI') {
        $Y = $pdf->GetY()+5;
    }else{
        $Y = $pdf->GetY()+7;
    }
    
    foreach ($co_data as $co_row) {
        $allotment_class = $co_row['allotment_class'];
        $object_code = $co_row['object_code'];
        $sub_object_code = $co_row['sub_object_code'];
        $uacs_code = $co_row['uacs_code'];
        $approved_budget = $co_row['approved_budget'];

        $query = $this->db->query("
            SELECT COALESCE(SUM(amount),0) AS total_amount  
            FROM (
                SELECT dco.`amount` FROM tbl_ors_direct_co_dt dco JOIN tbl_ors_hd hd on dco.`project_id` = hd.`recid` WHERE dco.`sub_object_code` = '$sub_object_code' AND hd.`ors_date` >= '$date_from' AND hd.`ors_date` < '$date_to' AND dco.`program_title` like '%$program_like%'
                UNION ALL
                SELECT idco.`amount` FROM tbl_ors_indirect_co_dt idco JOIN tbl_ors_hd hd on idco.`project_id` = hd.`recid` WHERE idco.`sub_object_code` = '$sub_object_code' AND hd.`ors_date` >= '$date_from' AND hd.`ors_date` < '$date_to' AND idco.`program_title` like '%$program_like%'
            ) AS combined
        ");

        $result = $query->getRowArray();
        $month_amount = $result['total_amount'];

        $query = $this->db->query("
            SELECT COALESCE(SUM(amount),0) AS total_amount  
            FROM (
                SELECT dco.`amount` FROM tbl_ors_direct_co_dt dco JOIN tbl_ors_hd hd on dco.`project_id` = hd.`recid` WHERE dco.`sub_object_code` = '$sub_object_code' AND dco.`program_title` like '%$program_like%'
                UNION ALL
                SELECT idco.`amount` FROM tbl_ors_indirect_co_dt idco JOIN tbl_ors_hd hd on idco.`project_id` = hd.`recid` WHERE idco.`sub_object_code` = '$sub_object_code' AND idco.`program_title` like '%$program_like%'
            ) AS combined
        ");

        $result = $query->getRowArray();
        $year_amount = $result['total_amount'];

        if ($Y > 265) {
            $pdf->AddPage();
            $Y = $pdf->GetY();
            $pdf->SetXY(10, $Y);
            $pdf->SetFont('Arial', 'B', 7);
            $pdf->Cell(64, 3.5, '', 'TRL', 0, 'C');//ROW 1
            $pdf->Cell(22, 3.5, '', 'TRL', 0, 'C');
            $pdf->Cell(22, 3.5, '', 'TRL', 0, 'C');
            $pdf->Cell(44, 3.5, '', 'TRL', 0, 'C');
            $pdf->Cell(22, 3.5, '', 'TRL', 0, 'C');
            $pdf->Cell(17, 3.5, '', 'TRL', 0, 'C');
            $Y+= 3.5;

            $pdf->SetXY(10, $Y);
            $pdf->Cell(64, 3.5, '', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, '', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, '', 'RL', 0, 'C');
            $pdf->Cell(44, 3.5, 'Obligation Incurred', 'BRL', 0, 'C');
            $pdf->Cell(22, 3.5, 'Unobligated', 'RL', 0, 'C');
            $pdf->Cell(17, 3.5, '', 'RL', 0, 'C');
            $Y+= 3.5;

            $pdf->SetXY(10, $Y);
            $pdf->Cell(64, 3.5, 'OBJECT OF EXPENDITURE', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, '', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, 'Revised', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, 'This month', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, 'Obligation', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, 'Balance of', 'RL', 0, 'C');
            $pdf->Cell(17, 3.5, 'Percent', 'RL', 0, 'C');
            $Y+= 3.5;

            $pdf->SetXY(10, $Y);
            $pdf->Cell(64, 3.5, '', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, 'UACS', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, 'Allotment', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, $month, 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, 'To Date', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, 'Allotment', 'RL', 0, 'C');
            $pdf->Cell(17, 3.5, 'Of', 'RL', 0, 'C');
            $Y+= 3.5;

            $pdf->SetXY(10, $Y);
            $pdf->Cell(64, 3.5, '', 'BRL', 0, 'C');
            $pdf->Cell(22, 3.5, '(1)', 'BRL', 0, 'C');
            $pdf->Cell(22, 3.5, '(4)', 'BRL', 0, 'C');
            $pdf->Cell(22, 3.5, '(5)', 'BRL', 0, 'C');
            $pdf->Cell(22, 3.5, '(6)', 'BRL', 0, 'C');
            $pdf->Cell(22, 3.5, '(7)=(4)-(6)', 'BRL', 0, 'C');
            $pdf->Cell(17, 3.5, 'Utilization', 'BRL', 0, 'C');

            $Y = $pdf->GetY()+3.5;
            // $pdf->SetXY(10, $Y);
            // $pdf->Cell(64, 215.5, '', 1, 0); 
            // $pdf->SetXY(74, $Y);
            // $pdf->Cell(22, 215.5, '', 1, 0); 
            // $pdf->SetXY(96, $Y);
            // $pdf->Cell(22, 215.5, '', 1, 0); 
            // $pdf->SetXY(118, $Y);
            // $pdf->Cell(22, 215.5, '', 1, 0); 
            // $pdf->SetXY(140, $Y);
            // $pdf->Cell(22, 215.5, '', 1, 0); 
            // $pdf->SetXY(162, $Y);
            // $pdf->Cell(22, 215.5, '', 1, 0); 
            // $pdf->SetXY(184, $Y);
            // $pdf->Cell(17, 215.5, '', 1, 0); 
        }

        // ================= ALLOTMENT CLASS ===================
        if ($allotment_class !== $last_allotment_class && $allotment_class !== null) {
            $pdf->SetFont('Arial', '', 8);

            // Save startY
            $startY = $Y;

            // Print text with wrapping
            $pdf->SetXY(10, $Y);
            $pdf->MultiCell(64, 4.5, $allotment_class, 0, 'L');

            // Measure row height
            $endY = $pdf->GetY();
            $totalRowHeight = $endY - $startY;

            // Draw borders
            $pdf->SetXY(10, $startY); $pdf->Cell(64, $totalRowHeight, '', 'L', 0, 'L');
            $pdf->SetXY(74, $startY); $pdf->Cell(22, $totalRowHeight, '', 0, 0, 'C');
            $pdf->SetXY(96, $startY); $pdf->Cell(22, $totalRowHeight, '', 0, 0, 'C');
            $pdf->SetXY(118, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
            $pdf->SetXY(140, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
            $pdf->SetXY(162, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
            $pdf->SetXY(184, $startY); $pdf->Cell(17, $totalRowHeight, '', 1, 0, 'C');

            // Center values vertically
            $middleY = $startY + ($totalRowHeight / 2) - 2.5;
            $pdf->SetFont('Arial', 'B', 8);

            $pdf->SetXY(96, $middleY); $pdf->Cell(22, 5, number_format($total_co, 2), 1, 0, 'C');
            $pdf->SetXY(118, $middleY); $pdf->Cell(22, 5, number_format($co_total_current_month, 2), 0, 0, 'C');
            $pdf->SetXY(140, $middleY); $pdf->Cell(22, 5, number_format($co_total_todate_month, 2), 0, 0, 'C');
            $pdf->SetXY(162, $middleY); $pdf->Cell(22, 5, number_format($co_total_unobligated, 2), 0, 0, 'C');
            $pdf->SetXY(184, $middleY); $pdf->Cell(17, 5, number_format($co_percentage_minus, 2) . '%', 0, 0, 'C');

            $Y = $endY;
            $last_allotment_class = $allotment_class;
        }

        // ================= OBJECT CODE ===================
        if ($object_code !== $last_object_code && $object_code !== null) {
            $pdf->SetFont('Arial', '', 8);

            // Object Code: Start at X=15 (no blank cell before)
            $pdf->SetXY(10, $Y);
            $pdf->Cell(5, 4.5, '', 'L', 1, 'C');
            $pdf->SetXY(15, $Y);
            $pdf->MultiCell(59, 4.5, $object_code, 0, 'L');

            // Totals
            $total_for_object_code = $co_object_code_totals[$object_code] ?? 0;
            $total_sub_month_amount = $co_sub_month_totals[$object_code] ?? 0;
            $total_sub_year_amount = $co_sub_year_totals[$object_code] ?? 0;
            $total_sub_unobligated = ($total_for_object_code ?? 0) - ($total_sub_year_amount ?? 0);

            // Columns
            $pdf->SetXY(96, $Y);
            $pdf->Cell(21.8, 4.5, number_format($total_for_object_code, 2), 1, 1, 'C');
            $pdf->SetXY(118, $Y);
            $pdf->Cell(21.8, 4.5, number_format($total_sub_month_amount, 2), 1, 1, 'C');
            $pdf->SetXY(140, $Y);
            $pdf->Cell(21.8, 4.5, number_format($total_sub_year_amount, 2), 1, 1, 'C');
            $pdf->SetXY(162, $Y);
            $pdf->Cell(21.8, 4.5, number_format($total_sub_unobligated, 2), 1, 1, 'C');
            $pdf->SetXY(184, $Y);
            $pdf->Cell(17, 4.5, '', 'R', 1, 'C');

            // Move down
            $Y += 4.5;
            $last_object_code = $object_code;
        }

        // ================= SUB OBJECT CODE ===================
        if ($sub_object_code !== $last_sub_object_code && $sub_object_code !== null) {
            $pdf->SetFont('Arial', '', 8);

            $startY = $Y;
            $pdf->SetXY(20, $Y);
            $pdf->MultiCell(55, 4.5, $sub_object_code, 0, 'L');

            $endY = $pdf->GetY();
            $totalRowHeight = $endY - $startY;

            if ($Y >260) {
                // Borders
                $pdf->SetXY(10, $startY); $pdf->Cell(64, $totalRowHeight, '', 'LB', 0, 'L');
                $pdf->SetXY(74, $startY); $pdf->Cell(22, $totalRowHeight, '', 'B', 0, 'C');
                $pdf->SetXY(96, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
                $pdf->SetXY(118, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
                $pdf->SetXY(140, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
                $pdf->SetXY(162, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
                $pdf->SetXY(184, $startY); $pdf->Cell(17, $totalRowHeight, '', 'RB', 0, 'C');
            }else{
                $pdf->SetXY(10, $startY); $pdf->Cell(64, $totalRowHeight, '', 'L', 0, 'L');
                $pdf->SetXY(74, $startY); $pdf->Cell(22, $totalRowHeight, '', 0, 0, 'C');
                $pdf->SetXY(96, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
                $pdf->SetXY(118, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
                $pdf->SetXY(140, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
                $pdf->SetXY(162, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
                $pdf->SetXY(184, $startY); $pdf->Cell(17, $totalRowHeight, '', 'R', 0, 'C');
            }

            // Center values
            $middleY = $startY + ($totalRowHeight / 2) - 2.5;

            $pdf->SetXY(74, $middleY); $pdf->Cell(22, 5, $uacs_code, 0, 0, 'C');
            $pdf->SetXY(96, $middleY); $pdf->Cell(22, 5, number_format($approved_budget,2), 0, 0, 'C');
            $pdf->SetXY(118, $middleY); $pdf->Cell(22, 5, $month_amount, 0, 0, 'C');
            $pdf->SetXY(140, $middleY); $pdf->Cell(22, 5, $year_amount, 0, 0, 'C');

            $subobligated_amount = $approved_budget - $year_amount;
            $pdf->SetXY(162, $middleY); $pdf->Cell(22, 5, number_format($subobligated_amount,2), 0, 0, 'C');
            $pdf->SetXY(184, $middleY); $pdf->Cell(17, 5, '', 0, 0, 'C');

            $Y = $endY;
            $last_sub_object_code = $sub_object_code;
        }

    }

    $total_year_ps += $total_ps;
    $total_year_mooe += $total_mooe;
    $total_year_co += $total_co;
    $ors_total_current_month += $total_all_sub_month;

    $Y = $pdf->GetY() + 5;    

}

// TOTAL CURRENT YEAR BUDGET -------------------------------------------- 
$Y = $pdf->GetY()+5;
$total_curryear_budget = $total_year_ps + $total_year_mooe + $total_year_co;

$pdf->SetFont('Arial', 'B', 8);
$pdf->SetXY(10, $Y);
$pdf->Cell(191, 3.5, 'Total, General Administration & Support', 1, 1, 'L');
$pdf->SetFont('Arial', '', 8);
$pdf->SetXY(96, $Y);
$pdf->Cell(22, 3.5, ($total_curryear_budget == 0.00 || !is_numeric($total_curryear_budget)) ? 0.00 : number_format((float)$total_curryear_budget, 2), 0, 1, 'C');
$pdf->SetXY(118, $Y);
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(21.8, 3.5, number_format($thismonth_grand_total, 2), 'B', 1, 'C'); // full width usage
$pdf->SetXY(140, $Y);
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(21.8, 3.5, number_format($todate_grand_total, 2), 'B', 1, 'C'); // full width usage
$pdf->SetXY(162, $Y);
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(21.8, 3.5, number_format($grand_unobligated, 2), 'B', 1, 'C'); // full width usage
$pdf->SetXY(184, $Y);
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(17, 3.5, number_format($grand_percentage_minus, 2) . '%', 'B', 1, 'C'); // full width usage


/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//---------------------------------------------------- SCIENTIFIC ---------------------------------------------------------------
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////




////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//---------------------------------------------------- Scientific research and development services on basic ---------------------------------------------------------------
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

$Y = $pdf->GetY();
$pdf->SetXY(10, $Y);
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(191, 5, 'II. Operations', 1, 1, 'L');

$total_curryear_budget = 0;
$total_year_ps = 0;
$total_year_mooe = 0;
$total_year_co = 0;
$total_sub_month_amount = 0;
$total_sub_year_amount = 0;
$program_tagging = "";
$project_like = "";
$program_class = "";

$total_todate_month = 0;
$ps_total_unobligated = 0;
$mooe_total_unobligated = 0;
$co_total_unobligated = 0;
$ps_percentage_minus = 0;
$mooe_percentage_minus = 0;
$co_percentage_minus = 0;
$total_project_budget = 0;
$ps_grand_total = 0;
$mooe_grand_total = 0;
$co_grand_total = 0;
$thismonth_grand_total = 0;

$ps_todate_grand_total = 0;
$mooe_todate_grand_total = 0;
$co_todate_grand_total = 0;
$todate_grand_total = 0;

$grand_unobligated = 0;
$grand_percentage_minus = 0;
$printed_once = '';
$printed_after_mooe = '';
//CURRENT YEAR BUDGET

$Y = $pdf->GetY() +3.5;

$query = $this->db->query("
    SELECT
        SUM(
            COALESCE((SELECT SUM(approved_budget) FROM tbl_saob_ps_dt WHERE project_id = a.`recid`), 0) +
            COALESCE((SELECT SUM(approved_budget) FROM tbl_saob_mooe_dt WHERE project_id = a.`recid`), 0) +
            COALESCE((SELECT SUM(approved_budget) FROM tbl_saob_co_dt WHERE project_id = a.`recid`), 0)
        ) AS total_approved_budget
    FROM
        `tbl_saob_hd` a
    WHERE 
        a.`project_title` like '%Scientific Research and Development Services on Basic and Applied Researches on Food and Nutrition%'
    ORDER BY a.`recid`
");
$rw = $query->getRowArray();
$total_project_budget = $rw['total_approved_budget'];

//CURRENT DATE GRAND TOTAL

//PS GRAND TOTAL
$query = $this->db->query("
    SELECT 
        SUM(total_sub_month) AS grand_total
    FROM (
        SELECT
            COALESCE((
                SELECT SUM(amount) 
                FROM tbl_ors_direct_ps_dt d 
                JOIN tbl_ors_hd hd ON d.`project_id` = hd.`recid`
                WHERE d.sub_object_code = b.particulars 
                AND hd.`ors_date` >= '$date_from' 
                AND hd.`ors_date` < '$date_to'
                AND d.`program_title` LIKE '%Scientific Research and Development Services on Basic and Applied Researches on Food and Nutrition%'
            ), 0)
            +
            COALESCE((
                SELECT SUM(amount) 
                FROM tbl_ors_indirect_ps_dt i 
                JOIN tbl_ors_hd hd ON i.`project_id` = hd.`recid`
                WHERE i.sub_object_code = b.particulars 
                AND hd.`ors_date` >= '$date_from' 
                AND hd.`ors_date` < '$date_to'
                AND i.`program_title` LIKE '%Scientific Research and Development Services on Basic and Applied Researches on Food and Nutrition%'
            ), 0) AS total_sub_month
        FROM tbl_saob_ps_dt AS b
        JOIN
        tbl_saob_hd saob
        on b.project_id = saob.recid
        WHERE
        saob.project_title LIKE '%Scientific Research and Development Services on Basic and Applied Researches on Food and Nutrition%'
    ) AS t;
");
$rw = $query->getRowArray();
$ps_grand_total = $rw['grand_total'];

//MOOE GRAND TOTAL
$query = $this->db->query("
    SELECT 
        SUM(total_sub_month) AS grand_total
    FROM (
        SELECT
            COALESCE((
                SELECT SUM(amount) 
                FROM tbl_ors_direct_mooe_dt d 
                JOIN tbl_ors_hd hd ON d.`project_id` = hd.`recid`
                WHERE d.sub_object_code = b.particulars 
                AND hd.`ors_date` >= '$date_from' 
                AND hd.`ors_date` < '$date_to'
                AND d.`program_title` LIKE '%Scientific Research and Development Services on Basic and Applied Researches on Food and Nutrition%'
            ), 0)
            +
            COALESCE((
                SELECT SUM(amount) 
                FROM tbl_ors_indirect_mooe_dt i 
                JOIN tbl_ors_hd hd ON i.`project_id` = hd.`recid`
                WHERE i.sub_object_code = b.particulars 
                AND hd.`ors_date` >= '$date_from' 
                AND hd.`ors_date` < '$date_to'
                AND i.`program_title` LIKE '%Scientific Research and Development Services on Basic and Applied Researches on Food and Nutrition%'
            ), 0) AS total_sub_month
        FROM tbl_saob_mooe_dt AS b
        JOIN
        tbl_saob_hd saob
        on b.project_id = saob.recid
        WHERE
        saob.project_title LIKE '%Scientific Research and Development Services on Basic and Applied Researches on Food and Nutrition%'
    ) AS t;
");
$rw = $query->getRowArray();
$mooe_grand_total = $rw['grand_total'];

//CO GRAND TOTAL
$query = $this->db->query("
    SELECT 
        SUM(total_sub_month) AS grand_total
    FROM (
        SELECT
            COALESCE((
                SELECT SUM(amount) 
                FROM tbl_ors_direct_co_dt d 
                JOIN tbl_ors_hd hd ON d.`project_id` = hd.`recid`
                WHERE d.sub_object_code = b.particulars 
                AND hd.`ors_date` >= '$date_from' 
                AND hd.`ors_date` < '$date_to'
                AND d.`program_title` LIKE '%Scientific Research and Development Services on Basic and Applied Researches on Food and Nutrition%'
            ), 0)
            +
            COALESCE((
                SELECT SUM(amount) 
                FROM tbl_ors_indirect_co_dt i 
                JOIN tbl_ors_hd hd ON i.`project_id` = hd.`recid`
                WHERE i.sub_object_code = b.particulars 
                AND hd.`ors_date` >= '$date_from' 
                AND hd.`ors_date` < '$date_to'
                AND i.`program_title` LIKE '%Scientific Research and Development Services on Basic and Applied Researches on Food and Nutrition%'
            ), 0) AS total_sub_month
        FROM tbl_saob_co_dt AS b
        JOIN
        tbl_saob_hd saob
        on b.project_id = saob.recid
        WHERE
        saob.project_title LIKE '%Scientific Research and Development Services on Basic and Applied Researches on Food and Nutrition%'
    ) AS t;
");
$rw = $query->getRowArray();
$co_grand_total = $rw['grand_total'];

$thismonth_grand_total = $ps_grand_total + $mooe_grand_total + $co_grand_total;


//UP TO DATE GRAND TOTAL

//UP TO DATE PS GRAND TOTAL
$query = $this->db->query("
    SELECT 
        SUM(total_sub_month) AS grand_total
    FROM (
        SELECT
            COALESCE((
                SELECT SUM(amount) 
                FROM tbl_ors_direct_ps_dt d 
                JOIN tbl_ors_hd hd ON d.`project_id` = hd.`recid`
                WHERE d.sub_object_code = b.particulars 
                AND d.`program_title` LIKE '%Scientific Research and Development Services on Basic and Applied Researches on Food and Nutrition%'
            ), 0)
            +
            COALESCE((
                SELECT SUM(amount) 
                FROM tbl_ors_indirect_ps_dt i 
                JOIN tbl_ors_hd hd ON i.`project_id` = hd.`recid`
                WHERE i.sub_object_code = b.particulars 
                AND i.`program_title` LIKE '%Scientific Research and Development Services on Basic and Applied Researches on Food and Nutrition%'
            ), 0) AS total_sub_month
        FROM tbl_saob_ps_dt AS b
        JOIN
        tbl_saob_hd saob
        on b.project_id = saob.recid
        WHERE
        saob.project_title LIKE '%Scientific Research and Development Services on Basic and Applied Researches on Food and Nutrition%'
    ) AS t;
");
$rw = $query->getRowArray();
$ps_todate_grand_total = $rw['grand_total'];

//UP TO DATE MOOE GRAND TOTAL
$query = $this->db->query("
    SELECT 
        SUM(total_sub_month) AS grand_total
    FROM (
        SELECT
            COALESCE((
                SELECT SUM(amount) 
                FROM tbl_ors_direct_mooe_dt d 
                JOIN tbl_ors_hd hd ON d.`project_id` = hd.`recid`
                WHERE d.sub_object_code = b.particulars 
                AND d.`program_title` LIKE '%Scientific Research and Development Services on Basic and Applied Researches on Food and Nutrition%'
            ), 0)
            +
            COALESCE((
                SELECT SUM(amount) 
                FROM tbl_ors_indirect_mooe_dt i 
                JOIN tbl_ors_hd hd ON i.`project_id` = hd.`recid`
                WHERE i.sub_object_code = b.particulars 
                AND i.`program_title` LIKE '%Scientific Research and Development Services on Basic and Applied Researches on Food and Nutrition%'
            ), 0) AS total_sub_month
        FROM tbl_saob_mooe_dt AS b
        JOIN
        tbl_saob_hd saob
        on b.project_id = saob.recid
        WHERE
        saob.project_title LIKE '%Scientific Research and Development Services on Basic and Applied Researches on Food and Nutrition%'
    ) AS t;
");
$rw = $query->getRowArray();
$mooe_todate_grand_total = $rw['grand_total'];

//UP TO DATE CO GRAND TOTAL
$query = $this->db->query("
    SELECT 
        SUM(total_sub_month) AS grand_total
    FROM (
        SELECT
            COALESCE((
                SELECT SUM(amount) 
                FROM tbl_ors_direct_co_dt d 
                JOIN tbl_ors_hd hd ON d.`project_id` = hd.`recid`
                WHERE d.sub_object_code = b.particulars 
                AND d.`program_title` LIKE '%Scientific Research and Development Services on Basic and Applied Researches on Food and Nutrition%'
            ), 0)
            +
            COALESCE((
                SELECT SUM(amount) 
                FROM tbl_ors_indirect_co_dt i 
                JOIN tbl_ors_hd hd ON i.`project_id` = hd.`recid`
                WHERE i.sub_object_code = b.particulars 
                AND i.`program_title` LIKE '%Scientific Research and Development Services on Basic and Applied Researches on Food and Nutrition%'
            ), 0) AS total_sub_month
        FROM tbl_saob_co_dt AS b
        JOIN
        tbl_saob_hd saob
        on b.project_id = saob.recid
        WHERE
        saob.project_title LIKE '%Scientific Research and Development Services on Basic and Applied Researches on Food and Nutrition%'
    ) AS t;
");
$rw = $query->getRowArray();
$co_todate_grand_total = $rw['grand_total'];

$todate_grand_total = $ps_todate_grand_total + $mooe_todate_grand_total + $co_todate_grand_total;
$grand_unobligated = $total_project_budget - $todate_grand_total;
if (!empty($total_project_budget) && $total_project_budget > 0) {
    $grand_percentage_minus = ($todate_grand_total / $total_project_budget) * 100;
}

$Y = $pdf->GetY()+3.5;
$query = $this->db->query("
    SELECT
        a.`program_title`,
        a.`project_title`,
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
    WHERE 
        a.`project_title` like '%Scientific Research and Development Services on Basic and Applied Researches on Food and Nutrition%'
    ORDER BY a.`recid`
");
$hd_data = $query->getResultArray();
$total_all_sub_month = 0;
$total_all_todate_month = 0;
$ors_total_current_month = 0;

foreach ($hd_data as $hd_row) {
    $ps_current_month = 0;
    $mooe_current_month = 0;
    $co_current_month = 0;
    $ps_tolatest_month = 0;
    $mooe_tolatest_month = 0;
    $co_tolatest_month = 0;
    $total_unobligated = 0;
    $total_percentage_minus = 0;
    $program_title = $hd_row['program_title'];
    $project_title = $hd_row['project_title'];
    $recid = $hd_row['recid'];
    $total_ps = $hd_row['total_ps'];
    $total_mooe = $hd_row['total_mooe'];
    $total_co = $hd_row['total_co'];
    $total_approved_budget = $hd_row['total_approved_budget'];

    if ($project_title == "Scientific Research and Development Services on Basic and Applied Researches on Food and Nutrition") {
        $program_tagging = 'FOOD AND NUTRITION RESEARCH AND DEVELOPMENT PROGRAM';
        $program_like = 'Scientific Research and Development Services on Basic and Applied Researches on Food and Nutrition';
    }


    //PS--------------------------------------------
    $query = $this->db->query("
        SELECT
            u.allotment_class,
            b.object_code,
            b.particulars AS sub_object_code,
            b.code AS uacs_code,
            b.approved_budget,
            COALESCE((
                SELECT SUM(amount) 
                FROM tbl_ors_direct_ps_dt d 
                JOIN
                tbl_ors_hd hd ON d.`project_id` = hd.`recid`
                WHERE d.sub_object_code = b.particulars AND hd.`ors_date` >= '$date_from' AND hd.`ors_date` < '$date_to' AND d.`program_title` like '%$program_like%'
            ), 0) + COALESCE((
                SELECT SUM(amount) 
                FROM tbl_ors_indirect_ps_dt i 
                JOIN
                tbl_ors_hd hd ON i.`project_id` = hd.`recid`
                WHERE i.sub_object_code = b.particulars AND hd.`ors_date` >= '$date_from' AND hd.`ors_date` < '$date_to' AND i.`program_title` like '%$program_like%'
            ), 0) AS total_sub_month,
            COALESCE((
                SELECT SUM(amount) 
                FROM tbl_ors_direct_ps_dt d 
                JOIN tbl_ors_hd hd ON d.project_id = hd.recid
                WHERE d.sub_object_code = b.particulars 
                AND d.program_title LIKE '%$program_like%'
            ), 0)
            + COALESCE((
                SELECT SUM(amount) 
                FROM tbl_ors_indirect_ps_dt i 
                JOIN tbl_ors_hd hd ON i.project_id = hd.recid
                WHERE i.sub_object_code = b.particulars
                AND i.program_title LIKE '%$project_like%'
            ), 0) AS total_sub_all
        FROM 
            tbl_saob_ps_dt AS b
        LEFT JOIN 
            mst_uacs AS u ON b.code = u.uacs_code
        WHERE 
            b.project_id = '$recid'
        ORDER BY 
            b.recid, b.particulars;

    ");
    $ps_data = $query->getResultArray();
    //total ps object code fetching
    $ps_object_code_totals = [];
    $ps_sub_month_totals = [];
    $ps_sub_year_totals = [];
    $last_object_code = '';
    
    $ps_total_current_month = 0;
    $ps_total_todate_month = 0;
    foreach ($ps_data as $ps_row) {
        $object_code = $ps_row['object_code'];
        $sub_object_code = $ps_row['sub_object_code'];
        $approved_budget = floatval($ps_row['approved_budget']);
        $total_sub_month = floatval($ps_row['total_sub_month']);
        $total_sub_all = floatval($ps_row['total_sub_all']);

        if (!isset($ps_object_code_totals[$object_code])) {
            $ps_object_code_totals[$object_code] = 0;
        }
        $ps_object_code_totals[$object_code] += $approved_budget;

        if (!isset($ps_sub_month_totals[$object_code])) {
            $ps_sub_month_totals[$object_code] = 0;
        }
        $ps_sub_month_totals[$object_code] += $total_sub_month;

        if (!isset($ps_sub_year_totals[$object_code])) {
            $ps_sub_year_totals[$object_code] = 0;
        }
        $ps_sub_year_totals[$object_code] += $total_sub_all;

        $ps_total_current_month += $total_sub_month;
        $ps_total_todate_month += $total_sub_all;

        $ps_total_unobligated = ($total_ps ?? 0) - ($ps_total_todate_month ?? 0);
        
        
        if (!empty($total_ps) && $total_ps > 0) {
            $ps_percentage_minus = ($ps_total_todate_month / $total_ps) * 100;   
        }


    }

    $ps_current_month += $ps_total_current_month;
    $ps_tolatest_month += $ps_total_todate_month;

    //MOOE------------------------------------------
    $query = $this->db->query("
        SELECT
            u.allotment_class,
            b.object_code,
            b.particulars AS sub_object_code,
            b.code AS uacs_code,
            b.approved_budget,
            COALESCE((
                SELECT SUM(amount) 
                FROM tbl_ors_direct_mooe_dt d 
                JOIN
                tbl_ors_hd hd ON d.`project_id` = hd.`recid`
                WHERE d.sub_object_code = b.particulars AND hd.`ors_date` >= '$date_from' AND hd.`ors_date` < '$date_to' AND d.`program_title` like '%$program_like%'
            ), 0) + COALESCE((
                SELECT SUM(amount) 
                FROM tbl_ors_indirect_mooe_dt i 
                JOIN
                tbl_ors_hd hd ON i.`project_id` = hd.`recid`
                WHERE i.sub_object_code = b.particulars AND hd.`ors_date` >= '$date_from' AND hd.`ors_date` < '$date_to' AND i.`program_title` like '%$program_like%'
            ), 0) AS total_sub_month,
            COALESCE((
                SELECT SUM(amount) 
                FROM tbl_ors_direct_mooe_dt d 
                JOIN tbl_ors_hd hd ON d.project_id = hd.recid
                WHERE d.sub_object_code = b.particulars
                AND d.program_title LIKE '%$program_like%'
            ), 0)
            + COALESCE((
                SELECT SUM(amount) 
                FROM tbl_ors_indirect_mooe_dt i 
                JOIN tbl_ors_hd hd ON i.project_id = hd.recid
                WHERE i.sub_object_code = b.particulars
                AND i.program_title LIKE '%$program_like%'
            ), 0) AS total_sub_all
        FROM 
            tbl_saob_mooe_dt AS b
        LEFT JOIN 
            mst_uacs AS u ON b.code = u.uacs_code
        WHERE 
            b.project_id = '$recid'
        ORDER BY 
            b.recid, b.particulars;

    ");
    $mooe_data = $query->getResultArray();
    //total mooe object code fetching
    $mooe_object_code_totals = [];
    $mooe_sub_month_totals = [];
    $mooe_sub_year_totals = [];
    $last_object_code = '';
    $mooe_total_current_month = 0;
    $mooe_total_todate_month = 0;
    foreach ($mooe_data as $mooe_row) {
        $object_code = $mooe_row['object_code'];
        $sub_object_code = $mooe_row['sub_object_code'];
        $approved_budget = floatval($mooe_row['approved_budget']);
        $total_sub_month = floatval($mooe_row['total_sub_month']);
        $total_sub_all = floatval($mooe_row['total_sub_all']);

        if (!isset($mooe_object_code_totals[$object_code])) {
            $mooe_object_code_totals[$object_code] = 0;
        }
        $mooe_object_code_totals[$object_code] += $approved_budget;

        if (!isset($mooe_sub_month_totals[$object_code])) {
            $mooe_sub_month_totals[$object_code] = 0;
        }
        $mooe_sub_month_totals[$object_code] += $total_sub_month;

        if (!isset($mooe_sub_year_totals[$object_code])) {
            $mooe_sub_year_totals[$object_code] = 0;
        }
        $mooe_sub_year_totals[$object_code] += $total_sub_all;

        $mooe_total_current_month += $total_sub_month;
        $mooe_total_todate_month += $total_sub_all;

        $mooe_total_unobligated = ($total_mooe ?? 0) - ($mooe_total_todate_month ?? 0);
        
        
        if (!empty($total_mooe) && $total_mooe > 0) {
            $mooe_percentage_minus = ($mooe_total_todate_month / $total_mooe) * 100;
        }
          
    }
    $mooe_current_month += $mooe_total_current_month;
    $mooe_tolatest_month += $mooe_total_todate_month;

    //CO--------------------------------------------
    $query = $this->db->query("
        SELECT
            u.allotment_class,
            b.object_code,
            b.particulars AS sub_object_code,
            b.code AS uacs_code,
            b.approved_budget,
            COALESCE((
                SELECT SUM(amount) 
                FROM tbl_ors_direct_co_dt d 
                JOIN
                tbl_ors_hd hd ON d.`project_id` = hd.`recid`
                WHERE d.sub_object_code = b.particulars AND hd.`ors_date` >= '$date_from' AND hd.`ors_date` < '$date_to' AND d.`program_title` like '%$program_like%'
            ), 0) + COALESCE((
                SELECT SUM(amount) 
                FROM tbl_ors_indirect_co_dt i 
                JOIN
                tbl_ors_hd hd ON i.`project_id` = hd.`recid`
                WHERE i.sub_object_code = b.particulars AND hd.`ors_date` >= '$date_from' AND hd.`ors_date` < '$date_to' AND i.`program_title` like '%$program_like%'
            ), 0) AS total_sub_month,
            COALESCE((
                SELECT SUM(amount) 
                FROM tbl_ors_direct_co_dt d 
                JOIN tbl_ors_hd hd ON d.project_id = hd.recid
                WHERE d.sub_object_code = b.particulars
                AND d.program_title LIKE '%$program_like%'
            ), 0)
            + COALESCE((
                SELECT SUM(amount) 
                FROM tbl_ors_indirect_co_dt i 
                JOIN tbl_ors_hd hd ON i.project_id = hd.recid
                WHERE i.sub_object_code = b.particulars
                AND i.program_title LIKE '%$program_like%'
            ), 0) AS total_sub_all
        FROM 
            tbl_saob_co_dt AS b
        LEFT JOIN 
            mst_uacs AS u ON b.code = u.uacs_code
        WHERE 
            b.project_id = '$recid'
        ORDER BY 
            b.recid, b.particulars;

    ");
    $co_data = $query->getResultArray();
    //total co object code fetching
    $co_object_code_totals = [];
    $co_sub_month_totals = [];
    $co_sub_year_totals = [];
    $last_object_code = '';
    $co_total_current_month = 0;
    $co_total_todate_month = 0;
    foreach ($co_data as $co_row) {
        $object_code = $co_row['object_code'];
        $sub_object_code = $co_row['sub_object_code'];
        $approved_budget = floatval($co_row['approved_budget']);
        $total_sub_month = floatval($co_row['total_sub_month']);
        $total_sub_all = floatval($co_row['total_sub_all']);

        if (!isset($co_object_code_totals[$object_code])) {
            $co_object_code_totals[$object_code] = 0;
        }
        $co_object_code_totals[$object_code] += $approved_budget;

        if (!isset($co_sub_month_totals[$object_code])) {
            $co_sub_month_totals[$object_code] = 0;
        }
        $co_sub_month_totals[$object_code] += $total_sub_month;

        if (!isset($co_sub_year_totals[$object_code])) {
            $co_sub_year_totals[$object_code] = 0;
        }
        $co_sub_year_totals[$object_code] += $total_sub_all;

        $co_total_current_month += $total_sub_month;
        $co_total_todate_month += $total_sub_all;

        $co_total_unobligated = ($total_co ?? 0) - ($co_total_todate_month ?? 0);
        
        
        if (!empty($total_co) && $total_co > 0) {
            $co_percentage_minus = ($co_total_todate_month / $total_co) * 100;
        }
          
    }

    $co_current_month += $co_total_current_month;
    $co_tolatest_month += $co_total_todate_month;

    $total_all_sub_month = $ps_current_month + $mooe_current_month + $co_current_month;
    $total_all_todate_month = $ps_tolatest_month + $mooe_tolatest_month + $co_tolatest_month;
    $total_unobligated = $total_approved_budget - $total_all_todate_month;
    if (!empty($total_approved_budget) && $total_approved_budget > 0) {
        $total_percentage_minus = ($total_all_todate_month / $total_approved_budget) * 100;
    }

    $Y = $pdf->GetY();
    // PROGRAM TAGGING LOGIC --------------------------------------------------------------------
    $startY = $Y; // Store the starting Y position

    // First, measure the height needed for program_tagging
    $pdf->SetXY(15, $Y);
    $pdf->SetFont('Arial', '', 8);
    $pdf->MultiCell(70, 3.5, $program_tagging, 0, 'L'); // Measure without border
    $afterProgramY = $pdf->GetY();
    $programHeight = $afterProgramY - $Y;

    // Draw complete PROGRAM TAGGING row with ALL borders
    $pdf->SetXY(10, $Y);
    $pdf->Cell(5, $programHeight, '', 'L', 0, 'C'); // First column

    $pdf->SetXY(15, $Y);
    $pdf->Cell(70, $programHeight, '', 0, 0, 'L'); // Program text column

    // Right-side columns for program tagging
    $pdf->SetXY(85, $Y);
    $pdf->Cell(11, $programHeight, '', 0, 0, 'C'); // UACS column

    $pdf->SetXY(96, $Y);
    $pdf->Cell(22, $programHeight, '', 1, 0, 'C'); // Budget column

    $pdf->SetXY(118, $Y);
    $pdf->Cell(22, $programHeight, '', 1, 0, 'C'); // This month column

    $pdf->SetXY(140, $Y);
    $pdf->Cell(22, $programHeight, '', 1, 0, 'C'); // To date column

    $pdf->SetXY(162, $Y);
    $pdf->Cell(22, $programHeight, '', 1, 0, 'C'); // Unobligated column

    $pdf->SetXY(184, $Y);
    $pdf->Cell(17, $programHeight, '', 1, 0, 'C'); // Percentage column

    // Draw program tagging totals (centered vertically)
    $programMiddleY = $Y + ($programHeight / 2) - 1.5;
    $pdf->SetXY(96, $programMiddleY);
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(22, 3, number_format($total_project_budget, 2), 0, 0, 'C');

    $pdf->SetXY(118, $programMiddleY);
    $pdf->Cell(22, 3, number_format($thismonth_grand_total, 2), 0, 0, 'C');

    $pdf->SetXY(140, $programMiddleY);
    $pdf->Cell(22, 3, number_format($todate_grand_total, 2), 0, 0, 'C');

    $pdf->SetXY(162, $programMiddleY);
    $pdf->Cell(22, 3, number_format($grand_unobligated, 2), 0, 0, 'C');

    $pdf->SetXY(184, $programMiddleY);
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(17, 3, number_format($grand_percentage_minus, 2) . '%', 0, 0, 'C');

    // Draw program tagging text
    $pdf->SetXY(15, $Y + 1);
    $pdf->SetFont('Arial', '', 8);
    $pdf->MultiCell(70, 3.5, '', 0, 'L');

    // PROJECT TITLE LOGIC --------------------------------------------------------------------
    $Y = $afterProgramY; // Move Y to after program tagging
    $projectStartY = $Y;

    // Measure the height needed for project_title
    $pdf->SetXY(15, $Y);
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->MultiCell(70, 3.5, $project_title, 0, 'L'); // Measure without border
    $afterProjectY = $pdf->GetY();
    $projectHeight = $afterProjectY - $Y;

    // Draw complete PROJECT TITLE row with ALL borders
    $pdf->SetXY(10, $Y);
    $pdf->Cell(5, $projectHeight, '', 'L', 0, 'C'); // First column

    $pdf->SetXY(15, $Y);
    $pdf->Cell(70, $projectHeight, '', 0, 0, 'L'); // Project text column

    // Right-side columns for project title
    $pdf->SetXY(85, $Y);
    $pdf->Cell(11, $projectHeight, '', 0, 0, 'C'); // UACS column

    $pdf->SetXY(96, $Y);
    $pdf->Cell(22, $projectHeight, '', 1, 0, 'C'); // Budget column

    $pdf->SetXY(118, $Y);
    $pdf->Cell(22, $projectHeight, '', 1, 0, 'C'); // This month column

    $pdf->SetXY(140, $Y);
    $pdf->Cell(22, $projectHeight, '', 1, 0, 'C'); // To date column

    $pdf->SetXY(162, $Y);
    $pdf->Cell(22, $projectHeight, '', 1, 0, 'C'); // Unobligated column

    $pdf->SetXY(184, $Y);
    $pdf->Cell(17, $projectHeight, '', 1, 0, 'C'); // Percentage column

    // Draw project title totals (centered vertically)
    $projectMiddleY = $Y + ($projectHeight / 2) - 1.5;
    $pdf->SetXY(96, $projectMiddleY);
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(22, 3, number_format($total_approved_budget, 2), 0, 0, 'C');

    $pdf->SetXY(118, $projectMiddleY);
    $pdf->Cell(22, 3, number_format($total_all_sub_month, 2), 0, 0, 'C');

    $pdf->SetXY(140, $projectMiddleY);
    $pdf->Cell(22, 3, number_format($total_all_todate_month, 2), 0, 0, 'C');

    $pdf->SetXY(162, $projectMiddleY);
    $pdf->Cell(22, 3, number_format($total_unobligated, 2), 0, 0, 'C');

    $pdf->SetXY(184, $projectMiddleY);
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(17, 3, number_format($total_percentage_minus, 2) . '%', 0, 0, 'C');

    // Draw project title text
    $pdf->SetXY(15, $Y + 1);
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->MultiCell(70, 3.5, '', 0, 'L');

    // Update Y position for next content
    $Y = $afterProjectY;

    // Clean up and prepare for next section
    $last_allotment_class = '';
    $last_sub_object_code = '';
    $last_object_code = '';

    $Y = $pdf->GetY()+6.3; // Add some spacing before the next section

    foreach ($ps_data as $ps_row) {
        $allotment_class = $ps_row['allotment_class'];
        $object_code = $ps_row['object_code'];
        $sub_object_code = $ps_row['sub_object_code'];
        $uacs_code = $ps_row['uacs_code'];
        $approved_budget = $ps_row['approved_budget'];

        $query = $this->db->query("
            SELECT COALESCE(SUM(amount),0) AS total_amount  
            FROM (
                SELECT dps.`amount` FROM tbl_ors_direct_ps_dt dps JOIN tbl_ors_hd hd on dps.`project_id` = hd.`recid` WHERE dps.`sub_object_code` = '$sub_object_code' AND hd.`ors_date` >= '$date_from' AND hd.`ors_date` < '$date_to' AND dps.`program_title` like '%$program_like%'
                UNION ALL
                SELECT idps.`amount` FROM tbl_ors_indirect_ps_dt idps JOIN tbl_ors_hd hd on idps.`project_id` = hd.`recid` WHERE idps.`sub_object_code` = '$sub_object_code' AND hd.`ors_date` >= '$date_from' AND hd.`ors_date` < '$date_to' AND idps.`program_title` like '%$program_like%'
            ) AS combined
        ");

        $result = $query->getRowArray();
        $month_amount = $result['total_amount'];

        $query = $this->db->query("
            SELECT COALESCE(SUM(amount),0) AS total_amount  
            FROM (
                SELECT dps.`amount` FROM tbl_ors_direct_ps_dt dps JOIN tbl_ors_hd hd on dps.`project_id` = hd.`recid` WHERE dps.`sub_object_code` = '$sub_object_code'  AND dps.`program_title` like '%$program_like%'
                UNION ALL
                SELECT idps.`amount` FROM tbl_ors_indirect_ps_dt idps JOIN tbl_ors_hd hd on idps.`project_id` = hd.`recid` WHERE idps.`sub_object_code` = '$sub_object_code' AND idps.`program_title` like '%$program_like%'
            ) AS combined
        ");

        $result = $query->getRowArray();
        $year_amount = $result['total_amount'];

        if ($Y > 265) {
            $pdf->AddPage();
            $Y = $pdf->GetY();
            $pdf->SetXY(10, $Y);
            $pdf->SetFont('Arial', 'B', 7);
            $pdf->Cell(64, 3.5, '', 'TRL', 0, 'C');//ROW 1
            $pdf->Cell(22, 3.5, '', 'TRL', 0, 'C');
            $pdf->Cell(22, 3.5, '', 'TRL', 0, 'C');
            $pdf->Cell(44, 3.5, '', 'TRL', 0, 'C');
            $pdf->Cell(22, 3.5, '', 'TRL', 0, 'C');
            $pdf->Cell(17, 3.5, '', 'TRL', 0, 'C');
            $Y+= 3.5;

            $pdf->SetXY(10, $Y);
            $pdf->Cell(64, 3.5, '', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, '', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, '', 'RL', 0, 'C');
            $pdf->Cell(44, 3.5, 'Obligation Incurred', 'BRL', 0, 'C');
            $pdf->Cell(22, 3.5, 'Unobligated', 'RL', 0, 'C');
            $pdf->Cell(17, 3.5, '', 'RL', 0, 'C');
            $Y+= 3.5;

            $pdf->SetXY(10, $Y);
            $pdf->Cell(64, 3.5, 'OBJECT OF EXPENDITURE', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, '', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, 'Revised', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, 'This month', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, 'Obligation', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, 'Balance of', 'RL', 0, 'C');
            $pdf->Cell(17, 3.5, 'Percent', 'RL', 0, 'C');
            $Y+= 3.5;

            $pdf->SetXY(10, $Y);
            $pdf->Cell(64, 3.5, '', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, 'UACS', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, 'Allotment', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, $month, 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, 'To Date', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, 'Allotment', 'RL', 0, 'C');
            $pdf->Cell(17, 3.5, 'Of', 'RL', 0, 'C');
            $Y+= 3.5;

            $pdf->SetXY(10, $Y);
            $pdf->Cell(64, 3.5, '', 'BRL', 0, 'C');
            $pdf->Cell(22, 3.5, '(1)', 'BRL', 0, 'C');
            $pdf->Cell(22, 3.5, '(4)', 'BRL', 0, 'C');
            $pdf->Cell(22, 3.5, '(5)', 'BRL', 0, 'C');
            $pdf->Cell(22, 3.5, '(6)', 'BRL', 0, 'C');
            $pdf->Cell(22, 3.5, '(7)=(4)-(6)', 'BRL', 0, 'C');
            $pdf->Cell(17, 3.5, 'Utilization', 'BRL', 0, 'C');

            $Y = $pdf->GetY()+3.5;
            // $pdf->SetXY(10, $Y);
            // $pdf->Cell(64, 215.5, '', 1, 0); 
            // $pdf->SetXY(74, $Y);
            // $pdf->Cell(22, 215.5, '', 1, 0); 
            // $pdf->SetXY(96, $Y);
            // $pdf->Cell(22, 215.5, '', 1, 0); 
            // $pdf->SetXY(118, $Y);
            // $pdf->Cell(22, 215.5, '', 1, 0); 
            // $pdf->SetXY(140, $Y);
            // $pdf->Cell(22, 215.5, '', 1, 0); 
            // $pdf->SetXY(162, $Y);
            // $pdf->Cell(22, 215.5, '', 1, 0); 
            // $pdf->SetXY(184, $Y);
            // $pdf->Cell(17, 215.5, '', 1, 0); 
        }

        // ================= ALLOTMENT CLASS ===================
        if ($allotment_class !== $last_allotment_class && $allotment_class !== null) {
            $pdf->SetFont('Arial', '', 8);

            // Save startY
            $startY = $Y;

            // Print text with wrapping
            $pdf->SetXY(10, $Y);
            $pdf->MultiCell(64, 4.5, $allotment_class, 0, 'L');

            // Measure row height
            $endY = $pdf->GetY();
            $totalRowHeight = $endY - $startY;

            // Draw borders
            $pdf->SetXY(10, $startY); $pdf->Cell(64, $totalRowHeight, '', 'L', 0, 'L');
            $pdf->SetXY(74, $startY); $pdf->Cell(22, $totalRowHeight, '', 0, 0, 'C');
            $pdf->SetXY(96, $startY); $pdf->Cell(22, $totalRowHeight, '', 0, 0, 'C');
            $pdf->SetXY(118, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
            $pdf->SetXY(140, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
            $pdf->SetXY(162, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
            $pdf->SetXY(184, $startY); $pdf->Cell(17, $totalRowHeight, '', 1, 0, 'C');

            // Center values vertically
            $middleY = $startY + ($totalRowHeight / 2) - 2.5;
            $pdf->SetFont('Arial', 'B', 8);

            $pdf->SetXY(96, $middleY); $pdf->Cell(22, 5, number_format($total_ps, 2), 1, 0, 'C');
            $pdf->SetXY(118, $middleY); $pdf->Cell(22, 5, number_format($ps_total_current_month, 2), 0, 0, 'C');
            $pdf->SetXY(140, $middleY); $pdf->Cell(22, 5, number_format($ps_total_todate_month, 2), 0, 0, 'C');
            $pdf->SetXY(162, $middleY); $pdf->Cell(22, 5, number_format($ps_total_unobligated, 2), 0, 0, 'C');
            $pdf->SetXY(184, $middleY); $pdf->Cell(17, 5, number_format($ps_percentage_minus, 2) . '%', 0, 0, 'C');

            $Y = $endY;
            $last_allotment_class = $allotment_class;
        }

        // ================= OBJECT CODE ===================
        if ($object_code !== $last_object_code && $object_code !== null) {
            $pdf->SetFont('Arial', '', 8);

            // Object Code: Start at X=15 (no blank cell before)
            $pdf->SetXY(10, $Y);
            $pdf->Cell(5, 4.5, '', 'L', 1, 'C');
            $pdf->SetXY(15, $Y);
            $pdf->MultiCell(59, 4.5, $object_code, 0, 'L');

            // Totals
            $total_for_object_code = $ps_object_code_totals[$object_code] ?? 0;
            $total_sub_month_amount = $ps_sub_month_totals[$object_code] ?? 0;
            $total_sub_year_amount = $ps_sub_year_totals[$object_code] ?? 0;
            $total_sub_unobligated = ($total_for_object_code ?? 0) - ($total_sub_year_amount ?? 0);

            // Columns
            $pdf->SetXY(96, $Y);
            $pdf->Cell(21.8, 4.5, number_format($total_for_object_code, 2), 1, 1, 'C');
            $pdf->SetXY(118, $Y);
            $pdf->Cell(21.8, 4.5, number_format($total_sub_month_amount, 2), 1, 1, 'C');
            $pdf->SetXY(140, $Y);
            $pdf->Cell(21.8, 4.5, number_format($total_sub_year_amount, 2), 1, 1, 'C');
            $pdf->SetXY(162, $Y);
            $pdf->Cell(21.8, 4.5, number_format($total_sub_unobligated, 2), 1, 1, 'C');
            $pdf->SetXY(184, $Y);
            $pdf->Cell(17, 4.5, '', 'R', 1, 'C');

            // Move down
            $Y += 4.5;
            $last_object_code = $object_code;
        }

        // ================= SUB OBJECT CODE ===================
        if ($sub_object_code !== $last_sub_object_code && $sub_object_code !== null) {
            $pdf->SetFont('Arial', '', 8);

            $startY = $Y;
            $pdf->SetXY(20, $Y);
            $pdf->MultiCell(55, 4.5, $sub_object_code, 0, 'L');

            $endY = $pdf->GetY();
            $totalRowHeight = $endY - $startY;

            if ($Y >255) {
                // Borders
                $pdf->SetXY(10, $startY); $pdf->Cell(64, $totalRowHeight, '', 'LB', 0, 'L');
                $pdf->SetXY(74, $startY); $pdf->Cell(22, $totalRowHeight, '', 'B', 0, 'C');
                $pdf->SetXY(96, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
                $pdf->SetXY(118, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
                $pdf->SetXY(140, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
                $pdf->SetXY(162, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
                $pdf->SetXY(184, $startY); $pdf->Cell(17, $totalRowHeight, '', 'RB', 0, 'C');
            }else{
                $pdf->SetXY(10, $startY); $pdf->Cell(64, $totalRowHeight, '', 'L', 0, 'L');
                $pdf->SetXY(74, $startY); $pdf->Cell(22, $totalRowHeight, '', 0, 0, 'C');
                $pdf->SetXY(96, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
                $pdf->SetXY(118, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
                $pdf->SetXY(140, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
                $pdf->SetXY(162, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
                $pdf->SetXY(184, $startY); $pdf->Cell(17, $totalRowHeight, '', 'R', 0, 'C');
            }

            // Center values
            $middleY = $startY + ($totalRowHeight / 2) - 2.5;

            $pdf->SetXY(74, $middleY); $pdf->Cell(22, 5, $uacs_code, 0, 0, 'C');
            $pdf->SetXY(96, $middleY); $pdf->Cell(22, 5, number_format($approved_budget,2), 0, 0, 'C');
            $pdf->SetXY(118, $middleY); $pdf->Cell(22, 5, $month_amount, 0, 0, 'C');
            $pdf->SetXY(140, $middleY); $pdf->Cell(22, 5, $year_amount, 0, 0, 'C');

            $subobligated_amount = $approved_budget - $year_amount;
            $pdf->SetXY(162, $middleY); $pdf->Cell(22, 5, number_format($subobligated_amount,2), 0, 0, 'C');
            $pdf->SetXY(184, $middleY); $pdf->Cell(17, 5, '', 0, 0, 'C');

            $Y = $endY;
            $last_sub_object_code = $sub_object_code;
        }

    }

    $last_allotment_class = '';
    $last_sub_object_code = '';
    $last_object_code = '';

    $Y = $pdf->GetY()+4.5;
    foreach ($mooe_data as $mooe_row) {
        $allotment_class = $mooe_row['allotment_class'];
        $object_code = $mooe_row['object_code'];
        $sub_object_code = $mooe_row['sub_object_code'];
        $uacs_code = $mooe_row['uacs_code'];
        $approved_budget = $mooe_row['approved_budget'];

        $query = $this->db->query("
            SELECT COALESCE(SUM(amount),0) AS total_amount  
            FROM (
                SELECT dmooe.`amount` FROM tbl_ors_direct_mooe_dt dmooe JOIN tbl_ors_hd hd on dmooe.`project_id` = hd.`recid` WHERE dmooe.`sub_object_code` = '$sub_object_code' AND hd.`ors_date` >= '$date_from' AND hd.`ors_date` < '$date_to' AND dmooe.`program_title` like '%$program_like%'
                UNION ALL
                SELECT idmooe.`amount` FROM tbl_ors_indirect_mooe_dt idmooe JOIN tbl_ors_hd hd on idmooe.`project_id` = hd.`recid` WHERE idmooe.`sub_object_code` = '$sub_object_code' AND hd.`ors_date` >= '$date_from' AND hd.`ors_date` < '$date_to' AND idmooe.`program_title` like '%$program_like%'
            ) AS combined
        ");

        $result = $query->getRowArray();
        $month_amount = $result['total_amount'];

        $query = $this->db->query("
            SELECT COALESCE(SUM(amount),0) AS total_amount  
            FROM (
                SELECT dmooe.`amount` FROM tbl_ors_direct_mooe_dt dmooe JOIN tbl_ors_hd hd on dmooe.`project_id` = hd.`recid` WHERE dmooe.`sub_object_code` = '$sub_object_code' AND dmooe.`program_title` like '%$program_like%'
                UNION ALL
                SELECT idmooe.`amount` FROM tbl_ors_indirect_mooe_dt idmooe JOIN tbl_ors_hd hd on idmooe.`project_id` = hd.`recid` WHERE idmooe.`sub_object_code` = '$sub_object_code' AND idmooe.`program_title` like '%$program_like%'
            ) AS combined
        ");

        $result = $query->getRowArray();
        $year_amount = $result['total_amount'];

        if ($Y > 265) {
            $pdf->AddPage();
            $Y = $pdf->GetY();
            $pdf->SetXY(10, $Y);
            $pdf->SetFont('Arial', 'B', 7);
            $pdf->Cell(64, 3.5, '', 'TRL', 0, 'C');//ROW 1
            $pdf->Cell(22, 3.5, '', 'TRL', 0, 'C');
            $pdf->Cell(22, 3.5, '', 'TRL', 0, 'C');
            $pdf->Cell(44, 3.5, '', 'TRL', 0, 'C');
            $pdf->Cell(22, 3.5, '', 'TRL', 0, 'C');
            $pdf->Cell(17, 3.5, '', 'TRL', 0, 'C');
            $Y+= 3.5;

            $pdf->SetXY(10, $Y);
            $pdf->Cell(64, 3.5, '', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, '', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, '', 'RL', 0, 'C');
            $pdf->Cell(44, 3.5, 'Obligation Incurred', 'BRL', 0, 'C');
            $pdf->Cell(22, 3.5, 'Unobligated', 'RL', 0, 'C');
            $pdf->Cell(17, 3.5, '', 'RL', 0, 'C');
            $Y+= 3.5;

            $pdf->SetXY(10, $Y);
            $pdf->Cell(64, 3.5, 'OBJECT OF EXPENDITURE', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, '', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, 'Revised', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, 'This month', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, 'Obligation', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, 'Balance of', 'RL', 0, 'C');
            $pdf->Cell(17, 3.5, 'Percent', 'RL', 0, 'C');
            $Y+= 3.5;

            $pdf->SetXY(10, $Y);
            $pdf->Cell(64, 3.5, '', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, 'UACS', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, 'Allotment', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, $month, 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, 'To Date', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, 'Allotment', 'RL', 0, 'C');
            $pdf->Cell(17, 3.5, 'Of', 'RL', 0, 'C');
            $Y+= 3.5;

            $pdf->SetXY(10, $Y);
            $pdf->Cell(64, 3.5, '', 'BRL', 0, 'C');
            $pdf->Cell(22, 3.5, '(1)', 'BRL', 0, 'C');
            $pdf->Cell(22, 3.5, '(4)', 'BRL', 0, 'C');
            $pdf->Cell(22, 3.5, '(5)', 'BRL', 0, 'C');
            $pdf->Cell(22, 3.5, '(6)', 'BRL', 0, 'C');
            $pdf->Cell(22, 3.5, '(7)=(4)-(6)', 'BRL', 0, 'C');
            $pdf->Cell(17, 3.5, 'Utilization', 'BRL', 0, 'C');

            $Y = $pdf->GetY()+3.5;
            // $pdf->SetXY(10, $Y);
            // $pdf->Cell(64, 215.5, '', 1, 0); 
            // $pdf->SetXY(74, $Y);
            // $pdf->Cell(22, 215.5, '', 1, 0); 
            // $pdf->SetXY(96, $Y);
            // $pdf->Cell(22, 215.5, '', 1, 0); 
            // $pdf->SetXY(118, $Y);
            // $pdf->Cell(22, 215.5, '', 1, 0); 
            // $pdf->SetXY(140, $Y);
            // $pdf->Cell(22, 215.5, '', 1, 0); 
            // $pdf->SetXY(162, $Y);
            // $pdf->Cell(22, 215.5, '', 1, 0); 
            // $pdf->SetXY(184, $Y);
            // $pdf->Cell(17, 215.5, '', 1, 0); 
        }

        // ================= ALLOTMENT CLASS ===================
        if ($allotment_class !== $last_allotment_class && $allotment_class !== null) {
            $pdf->SetFont('Arial', '', 8);

            // Save startY
            $startY = $Y;

            // Print text with wrapping
            $pdf->SetXY(10, $Y);
            $pdf->MultiCell(64, 4.5, $allotment_class, 0, 'L');

            // Measure row height
            $endY = $pdf->GetY();
            $totalRowHeight = $endY - $startY;

            // Draw borders
            $pdf->SetXY(10, $startY); $pdf->Cell(64, $totalRowHeight, '', 'L', 0, 'L');
            $pdf->SetXY(74, $startY); $pdf->Cell(22, $totalRowHeight, '', 0, 0, 'C');
            $pdf->SetXY(96, $startY); $pdf->Cell(22, $totalRowHeight, '', 0, 0, 'C');
            $pdf->SetXY(118, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
            $pdf->SetXY(140, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
            $pdf->SetXY(162, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
            $pdf->SetXY(184, $startY); $pdf->Cell(17, $totalRowHeight, '', 1, 0, 'C');

            // Center values vertically
            $middleY = $startY + ($totalRowHeight / 2) - 2.5;
            $pdf->SetFont('Arial', 'B', 8);

            $pdf->SetXY(96, $middleY); $pdf->Cell(22, 5, number_format($total_mooe, 2), 1, 0, 'C');
            $pdf->SetXY(118, $middleY); $pdf->Cell(22, 5, number_format($mooe_total_current_month, 2), 0, 0, 'C');
            $pdf->SetXY(140, $middleY); $pdf->Cell(22, 5, number_format($mooe_total_todate_month, 2), 0, 0, 'C');
            $pdf->SetXY(162, $middleY); $pdf->Cell(22, 5, number_format($mooe_total_unobligated, 2), 0, 0, 'C');
            $pdf->SetXY(184, $middleY); $pdf->Cell(17, 5, number_format($mooe_percentage_minus, 2) . '%', 0, 0, 'C');

            $Y = $endY;
            $last_allotment_class = $allotment_class;
        }

        // ================= OBJECT CODE ===================
        if ($object_code !== $last_object_code && $object_code !== null) {
            $pdf->SetFont('Arial', '', 8);

            // Object Code: Start at X=15 (no blank cell before)
            $pdf->SetXY(10, $Y);
            $pdf->Cell(5, 4.5, '', 'L', 1, 'C');
            $pdf->SetXY(15, $Y);
            $pdf->MultiCell(59, 4.5, $object_code, 0, 'L');

            // Totals
            $total_for_object_code = $mooe_object_code_totals[$object_code] ?? 0;
            $total_sub_month_amount = $mooe_sub_month_totals[$object_code] ?? 0;
            $total_sub_year_amount = $mooe_sub_year_totals[$object_code] ?? 0;
            $total_sub_unobligated = ($total_for_object_code ?? 0) - ($total_sub_year_amount ?? 0);

            // Columns
            $pdf->SetXY(96, $Y);
            $pdf->Cell(21.8, 4.5, number_format($total_for_object_code, 2), 1, 1, 'C');
            $pdf->SetXY(118, $Y);
            $pdf->Cell(21.8, 4.5, number_format($total_sub_month_amount, 2), 1, 1, 'C');
            $pdf->SetXY(140, $Y);
            $pdf->Cell(21.8, 4.5, number_format($total_sub_year_amount, 2), 1, 1, 'C');
            $pdf->SetXY(162, $Y);
            $pdf->Cell(21.8, 4.5, number_format($total_sub_unobligated, 2), 1, 1, 'C');
            $pdf->SetXY(184, $Y);
            $pdf->Cell(17, 4.5, '', 'R', 1, 'C');

            // Move down
            $Y += 4.5;
            $last_object_code = $object_code;
        }

        // ================= SUB OBJECT CODE ===================
        if ($sub_object_code !== $last_sub_object_code && $sub_object_code !== null) {
            $pdf->SetFont('Arial', '', 8);

            $startY = $Y;
            $pdf->SetXY(20, $Y);
            $pdf->MultiCell(55, 4.5, $sub_object_code, 0, 'L');

            $endY = $pdf->GetY();
            $totalRowHeight = $endY - $startY;

            if ($Y >260) {
                // Borders
                $pdf->SetXY(10, $startY); $pdf->Cell(64, $totalRowHeight, '', 'LB', 0, 'L');
                $pdf->SetXY(74, $startY); $pdf->Cell(22, $totalRowHeight, '', 'B', 0, 'C');
                $pdf->SetXY(96, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
                $pdf->SetXY(118, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
                $pdf->SetXY(140, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
                $pdf->SetXY(162, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
                $pdf->SetXY(184, $startY); $pdf->Cell(17, $totalRowHeight, '', 'RB', 0, 'C');
            }else{
                $pdf->SetXY(10, $startY); $pdf->Cell(64, $totalRowHeight, '', 'L', 0, 'L');
                $pdf->SetXY(74, $startY); $pdf->Cell(22, $totalRowHeight, '', 0, 0, 'C');
                $pdf->SetXY(96, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
                $pdf->SetXY(118, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
                $pdf->SetXY(140, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
                $pdf->SetXY(162, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
                $pdf->SetXY(184, $startY); $pdf->Cell(17, $totalRowHeight, '', 'R', 0, 'C');
            }

            // Center values
            $middleY = $startY + ($totalRowHeight / 2) - 2.5;

            $pdf->SetXY(74, $middleY); $pdf->Cell(22, 5, $uacs_code, 0, 0, 'C');
            $pdf->SetXY(96, $middleY); $pdf->Cell(22, 5, number_format($approved_budget,2), 0, 0, 'C');
            $pdf->SetXY(118, $middleY); $pdf->Cell(22, 5, $month_amount, 0, 0, 'C');
            $pdf->SetXY(140, $middleY); $pdf->Cell(22, 5, $year_amount, 0, 0, 'C');

            $subobligated_amount = $approved_budget - $year_amount;
            $pdf->SetXY(162, $middleY); $pdf->Cell(22, 5, number_format($subobligated_amount,2), 0, 0, 'C');
            $pdf->SetXY(184, $middleY); $pdf->Cell(17, 5, '', 0, 0, 'C');

            $Y = $endY;
            $last_sub_object_code = $sub_object_code;
        }

    }

    $last_allotment_class = '';
    $last_sub_object_code = '';
    $last_object_code = '';

    $Y = $pdf->GetY();
    foreach ($co_data as $co_row) {
        $allotment_class = $co_row['allotment_class'];
        $object_code = $co_row['object_code'];
        $sub_object_code = $co_row['sub_object_code'];
        $uacs_code = $co_row['uacs_code'];
        $approved_budget = $co_row['approved_budget'];

        $query = $this->db->query("
            SELECT COALESCE(SUM(amount),0) AS total_amount  
            FROM (
                SELECT dco.`amount` FROM tbl_ors_direct_co_dt dco JOIN tbl_ors_hd hd on dco.`project_id` = hd.`recid` WHERE dco.`sub_object_code` = '$sub_object_code' AND hd.`ors_date` >= '$date_from' AND hd.`ors_date` < '$date_to' AND dco.`program_title` like '%$program_like%'
                UNION ALL
                SELECT idco.`amount` FROM tbl_ors_indirect_co_dt idco JOIN tbl_ors_hd hd on idco.`project_id` = hd.`recid` WHERE idco.`sub_object_code` = '$sub_object_code' AND hd.`ors_date` >= '$date_from' AND hd.`ors_date` < '$date_to' AND idco.`program_title` like '%$program_like%'
            ) AS combined
        ");

        $result = $query->getRowArray();
        $month_amount = $result['total_amount'];

        $query = $this->db->query("
            SELECT COALESCE(SUM(amount),0) AS total_amount  
            FROM (
                SELECT dco.`amount` FROM tbl_ors_direct_co_dt dco JOIN tbl_ors_hd hd on dco.`project_id` = hd.`recid` WHERE dco.`sub_object_code` = '$sub_object_code' AND dco.`program_title` like '%$program_like%'
                UNION ALL
                SELECT idco.`amount` FROM tbl_ors_indirect_co_dt idco JOIN tbl_ors_hd hd on idco.`project_id` = hd.`recid` WHERE idco.`sub_object_code` = '$sub_object_code' AND idco.`program_title` like '%$program_like%'
            ) AS combined
        ");

        $result = $query->getRowArray();
        $year_amount = $result['total_amount'];

        if ($Y > 265) {
            $pdf->AddPage();
            $Y = $pdf->GetY();
            $pdf->SetXY(10, $Y);
            $pdf->SetFont('Arial', 'B', 7);
            $pdf->Cell(64, 3.5, '', 'TRL', 0, 'C');//ROW 1
            $pdf->Cell(22, 3.5, '', 'TRL', 0, 'C');
            $pdf->Cell(22, 3.5, '', 'TRL', 0, 'C');
            $pdf->Cell(44, 3.5, '', 'TRL', 0, 'C');
            $pdf->Cell(22, 3.5, '', 'TRL', 0, 'C');
            $pdf->Cell(17, 3.5, '', 'TRL', 0, 'C');
            $Y+= 3.5;

            $pdf->SetXY(10, $Y);
            $pdf->Cell(64, 3.5, '', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, '', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, '', 'RL', 0, 'C');
            $pdf->Cell(44, 3.5, 'Obligation Incurred', 'BRL', 0, 'C');
            $pdf->Cell(22, 3.5, 'Unobligated', 'RL', 0, 'C');
            $pdf->Cell(17, 3.5, '', 'RL', 0, 'C');
            $Y+= 3.5;

            $pdf->SetXY(10, $Y);
            $pdf->Cell(64, 3.5, 'OBJECT OF EXPENDITURE', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, '', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, 'Revised', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, 'This month', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, 'Obligation', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, 'Balance of', 'RL', 0, 'C');
            $pdf->Cell(17, 3.5, 'Percent', 'RL', 0, 'C');
            $Y+= 3.5;

            $pdf->SetXY(10, $Y);
            $pdf->Cell(64, 3.5, '', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, 'UACS', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, 'Allotment', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, $month, 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, 'To Date', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, 'Allotment', 'RL', 0, 'C');
            $pdf->Cell(17, 3.5, 'Of', 'RL', 0, 'C');
            $Y+= 3.5;

            $pdf->SetXY(10, $Y);
            $pdf->Cell(64, 3.5, '', 'BRL', 0, 'C');
            $pdf->Cell(22, 3.5, '(1)', 'BRL', 0, 'C');
            $pdf->Cell(22, 3.5, '(4)', 'BRL', 0, 'C');
            $pdf->Cell(22, 3.5, '(5)', 'BRL', 0, 'C');
            $pdf->Cell(22, 3.5, '(6)', 'BRL', 0, 'C');
            $pdf->Cell(22, 3.5, '(7)=(4)-(6)', 'BRL', 0, 'C');
            $pdf->Cell(17, 3.5, 'Utilization', 'BRL', 0, 'C');

            $Y = $pdf->GetY()+3.5;
            // $pdf->SetXY(10, $Y);
            // $pdf->Cell(64, 215.5, '', 1, 0); 
            // $pdf->SetXY(74, $Y);
            // $pdf->Cell(22, 215.5, '', 1, 0); 
            // $pdf->SetXY(96, $Y);
            // $pdf->Cell(22, 215.5, '', 1, 0); 
            // $pdf->SetXY(118, $Y);
            // $pdf->Cell(22, 215.5, '', 1, 0); 
            // $pdf->SetXY(140, $Y);
            // $pdf->Cell(22, 215.5, '', 1, 0); 
            // $pdf->SetXY(162, $Y);
            // $pdf->Cell(22, 215.5, '', 1, 0); 
            // $pdf->SetXY(184, $Y);
            // $pdf->Cell(17, 215.5, '', 1, 0); 
        }

        // ================= ALLOTMENT CLASS ===================
        if ($allotment_class !== $last_allotment_class && $allotment_class !== null) {
            $pdf->SetFont('Arial', '', 8);

            // Save startY
            $startY = $Y;

            // Print text with wrapping
            $pdf->SetXY(10, $Y);
            $pdf->MultiCell(64, 4.5, $allotment_class, 0, 'L');

            // Measure row height
            $endY = $pdf->GetY();
            $totalRowHeight = $endY - $startY;

            // Draw borders
            $pdf->SetXY(10, $startY); $pdf->Cell(64, $totalRowHeight, '', 'L', 0, 'L');
            $pdf->SetXY(74, $startY); $pdf->Cell(22, $totalRowHeight, '', 0, 0, 'C');
            $pdf->SetXY(96, $startY); $pdf->Cell(22, $totalRowHeight, '', 0, 0, 'C');
            $pdf->SetXY(118, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
            $pdf->SetXY(140, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
            $pdf->SetXY(162, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
            $pdf->SetXY(184, $startY); $pdf->Cell(17, $totalRowHeight, '', 1, 0, 'C');

            // Center values vertically
            $middleY = $startY + ($totalRowHeight / 2) - 2.5;
            $pdf->SetFont('Arial', 'B', 8);

            $pdf->SetXY(96, $middleY); $pdf->Cell(22, 5, number_format($total_co, 2), 1, 0, 'C');
            $pdf->SetXY(118, $middleY); $pdf->Cell(22, 5, number_format($co_total_current_month, 2), 0, 0, 'C');
            $pdf->SetXY(140, $middleY); $pdf->Cell(22, 5, number_format($co_total_todate_month, 2), 0, 0, 'C');
            $pdf->SetXY(162, $middleY); $pdf->Cell(22, 5, number_format($co_total_unobligated, 2), 0, 0, 'C');
            $pdf->SetXY(184, $middleY); $pdf->Cell(17, 5, number_format($co_percentage_minus, 2) . '%', 0, 0, 'C');

            $Y = $endY;
            $last_allotment_class = $allotment_class;
        }

        // ================= OBJECT CODE ===================
        if ($object_code !== $last_object_code && $object_code !== null) {
            $pdf->SetFont('Arial', '', 8);

            // Object Code: Start at X=15 (no blank cell before)
            $pdf->SetXY(10, $Y);
            $pdf->Cell(5, 4.5, '', 'L', 1, 'C');
            $pdf->SetXY(15, $Y);
            $pdf->MultiCell(59, 4.5, $object_code, 0, 'L');

            // Totals
            $total_for_object_code = $co_object_code_totals[$object_code] ?? 0;
            $total_sub_month_amount = $co_sub_month_totals[$object_code] ?? 0;
            $total_sub_year_amount = $co_sub_year_totals[$object_code] ?? 0;
            $total_sub_unobligated = ($total_for_object_code ?? 0) - ($total_sub_year_amount ?? 0);

            // Columns
            $pdf->SetXY(96, $Y);
            $pdf->Cell(21.8, 4.5, number_format($total_for_object_code, 2), 1, 1, 'C');
            $pdf->SetXY(118, $Y);
            $pdf->Cell(21.8, 4.5, number_format($total_sub_month_amount, 2), 1, 1, 'C');
            $pdf->SetXY(140, $Y);
            $pdf->Cell(21.8, 4.5, number_format($total_sub_year_amount, 2), 1, 1, 'C');
            $pdf->SetXY(162, $Y);
            $pdf->Cell(21.8, 4.5, number_format($total_sub_unobligated, 2), 1, 1, 'C');
            $pdf->SetXY(184, $Y);
            $pdf->Cell(17, 4.5, '', 'R', 1, 'C');

            // Move down
            $Y += 4.5;
            $last_object_code = $object_code;
        }

        // ================= SUB OBJECT CODE ===================
        if ($sub_object_code !== $last_sub_object_code && $sub_object_code !== null) {
            $pdf->SetFont('Arial', '', 8);

            $startY = $Y;
            $pdf->SetXY(20, $Y);
            $pdf->MultiCell(55, 4.5, $sub_object_code, 0, 'L');

            $endY = $pdf->GetY();
            $totalRowHeight = $endY - $startY;

            if ($Y >260) {
                // Borders
                $pdf->SetXY(10, $startY); $pdf->Cell(64, $totalRowHeight, '', 'LB', 0, 'L');
                $pdf->SetXY(74, $startY); $pdf->Cell(22, $totalRowHeight, '', 'B', 0, 'C');
                $pdf->SetXY(96, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
                $pdf->SetXY(118, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
                $pdf->SetXY(140, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
                $pdf->SetXY(162, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
                $pdf->SetXY(184, $startY); $pdf->Cell(17, $totalRowHeight, '', 'RB', 0, 'C');
            }else{
                $pdf->SetXY(10, $startY); $pdf->Cell(64, $totalRowHeight, '', 'L', 0, 'L');
                $pdf->SetXY(74, $startY); $pdf->Cell(22, $totalRowHeight, '', 0, 0, 'C');
                $pdf->SetXY(96, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
                $pdf->SetXY(118, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
                $pdf->SetXY(140, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
                $pdf->SetXY(162, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
                $pdf->SetXY(184, $startY); $pdf->Cell(17, $totalRowHeight, '', 'R', 0, 'C');
            }

            // Center values
            $middleY = $startY + ($totalRowHeight / 2) - 2.5;

            $pdf->SetXY(74, $middleY); $pdf->Cell(22, 5, $uacs_code, 0, 0, 'C');
            $pdf->SetXY(96, $middleY); $pdf->Cell(22, 5, number_format($approved_budget,2), 0, 0, 'C');
            $pdf->SetXY(118, $middleY); $pdf->Cell(22, 5, $month_amount, 0, 0, 'C');
            $pdf->SetXY(140, $middleY); $pdf->Cell(22, 5, $year_amount, 0, 0, 'C');

            $subobligated_amount = $approved_budget - $year_amount;
            $pdf->SetXY(162, $middleY); $pdf->Cell(22, 5, number_format($subobligated_amount,2), 0, 0, 'C');
            $pdf->SetXY(184, $middleY); $pdf->Cell(17, 5, '', 0, 0, 'C');

            $Y = $endY;
            $last_sub_object_code = $sub_object_code;
        }

    }

    $total_year_ps += $total_ps;
    $total_year_mooe += $total_mooe;
    $total_year_co += $total_co;
    $ors_total_current_month += $total_all_sub_month;

    $Y = $pdf->GetY() + 3.5;    

}
$Y = $pdf->GetY() + 7.5;  
$pdf->SetXY(10, $Y);
$pdf->Cell(191, 3.5, '', 'TRL', 0, 'C');

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//-------------------------- Expanding the FNRI's Nutrigenomics Laboratory: Towards Establishment of a World Class Philippines Nutrigenomics Center ---------------------------
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


$total_curryear_budget = 0;
$total_year_ps = 0;
$total_year_mooe = 0;
$total_year_co = 0;
$total_sub_month_amount = 0;
$total_sub_year_amount = 0;
$program_tagging = "";
$project_like = "";
$program_class = "";

$total_todate_month = 0;
$ps_total_unobligated = 0;
$mooe_total_unobligated = 0;
$co_total_unobligated = 0;
$ps_percentage_minus = 0;
$mooe_percentage_minus = 0;
$co_percentage_minus = 0;
$total_project_budget = 0;
$ps_grand_total = 0;
$mooe_grand_total = 0;
$co_grand_total = 0;
$thismonth_grand_total = 0;

$ps_todate_grand_total = 0;
$mooe_todate_grand_total = 0;
$co_todate_grand_total = 0;
$todate_grand_total = 0;

$grand_unobligated = 0;
$grand_percentage_minus = 0;
$printed_once = '';
$printed_after_mooe = '';
//CURRENT YEAR BUDGET

$Y = $pdf->GetY() +3.5;

$query = $this->db->query("
    SELECT
        SUM(
            COALESCE((SELECT SUM(approved_budget) FROM tbl_saob_ps_dt WHERE project_id = a.`recid`), 0) +
            COALESCE((SELECT SUM(approved_budget) FROM tbl_saob_mooe_dt WHERE project_id = a.`recid`), 0) +
            COALESCE((SELECT SUM(approved_budget) FROM tbl_saob_co_dt WHERE project_id = a.`recid`), 0)
        ) AS total_approved_budget
    FROM
        `tbl_saob_hd` a
    WHERE 
        a.`project_title` like '%Expanding the FNRI%'
    ORDER BY a.`recid`
");
$rw = $query->getRowArray();
$total_project_budget = $rw['total_approved_budget'];

//CURRENT DATE GRAND TOTAL

//PS GRAND TOTAL
$query = $this->db->query("
    SELECT 
        SUM(total_sub_month) AS grand_total
    FROM (
        SELECT
            COALESCE((
                SELECT SUM(amount) 
                FROM tbl_ors_direct_ps_dt d 
                JOIN tbl_ors_hd hd ON d.`project_id` = hd.`recid`
                WHERE d.sub_object_code = b.particulars 
                AND hd.`ors_date` >= '$date_from' 
                AND hd.`ors_date` < '$date_to'
                AND d.`program_title` LIKE '%Expanding the FNRI%'
            ), 0)
            +
            COALESCE((
                SELECT SUM(amount) 
                FROM tbl_ors_indirect_ps_dt i 
                JOIN tbl_ors_hd hd ON i.`project_id` = hd.`recid`
                WHERE i.sub_object_code = b.particulars 
                AND hd.`ors_date` >= '$date_from' 
                AND hd.`ors_date` < '$date_to'
                AND i.`program_title` LIKE '%Expanding the FNRI%'
            ), 0) AS total_sub_month
        FROM tbl_saob_ps_dt AS b
        JOIN
        tbl_saob_hd saob
        on b.project_id = saob.recid
        WHERE
        saob.project_title LIKE '%Expanding the FNRI%'
    ) AS t;
");
$rw = $query->getRowArray();
$ps_grand_total = $rw['grand_total'];

//MOOE GRAND TOTAL
$query = $this->db->query("
    SELECT 
        SUM(total_sub_month) AS grand_total
    FROM (
        SELECT
            COALESCE((
                SELECT SUM(amount) 
                FROM tbl_ors_direct_mooe_dt d 
                JOIN tbl_ors_hd hd ON d.`project_id` = hd.`recid`
                WHERE d.sub_object_code = b.particulars 
                AND hd.`ors_date` >= '$date_from' 
                AND hd.`ors_date` < '$date_to'
                AND d.`program_title` LIKE '%Expanding the FNRI%'
            ), 0)
            +
            COALESCE((
                SELECT SUM(amount) 
                FROM tbl_ors_indirect_mooe_dt i 
                JOIN tbl_ors_hd hd ON i.`project_id` = hd.`recid`
                WHERE i.sub_object_code = b.particulars 
                AND hd.`ors_date` >= '$date_from' 
                AND hd.`ors_date` < '$date_to'
                AND i.`program_title` LIKE '%Expanding the FNRI%'
            ), 0) AS total_sub_month
        FROM tbl_saob_mooe_dt AS b
        JOIN
        tbl_saob_hd saob
        on b.project_id = saob.recid
        WHERE
        saob.project_title LIKE '%Expanding the FNRI%'
    ) AS t;
");
$rw = $query->getRowArray();
$mooe_grand_total = $rw['grand_total'];

//CO GRAND TOTAL
$query = $this->db->query("
    SELECT 
        SUM(total_sub_month) AS grand_total
    FROM (
        SELECT
            COALESCE((
                SELECT SUM(amount) 
                FROM tbl_ors_direct_co_dt d 
                JOIN tbl_ors_hd hd ON d.`project_id` = hd.`recid`
                WHERE d.sub_object_code = b.particulars 
                AND hd.`ors_date` >= '$date_from' 
                AND hd.`ors_date` < '$date_to'
                AND d.`program_title` LIKE '%Expanding the FNRI%'
            ), 0)
            +
            COALESCE((
                SELECT SUM(amount) 
                FROM tbl_ors_indirect_co_dt i 
                JOIN tbl_ors_hd hd ON i.`project_id` = hd.`recid`
                WHERE i.sub_object_code = b.particulars 
                AND hd.`ors_date` >= '$date_from' 
                AND hd.`ors_date` < '$date_to'
                AND i.`program_title` LIKE '%Expanding the FNRI%'
            ), 0) AS total_sub_month
        FROM tbl_saob_co_dt AS b
        JOIN
        tbl_saob_hd saob
        on b.project_id = saob.recid
        WHERE
        saob.project_title LIKE '%Expanding the FNRI%'
    ) AS t;
");
$rw = $query->getRowArray();
$co_grand_total = $rw['grand_total'];

$thismonth_grand_total = $ps_grand_total + $mooe_grand_total + $co_grand_total;


//UP TO DATE GRAND TOTAL

//UP TO DATE PS GRAND TOTAL
$query = $this->db->query("
    SELECT 
        SUM(total_sub_month) AS grand_total
    FROM (
        SELECT
            COALESCE((
                SELECT SUM(amount) 
                FROM tbl_ors_direct_ps_dt d 
                JOIN tbl_ors_hd hd ON d.`project_id` = hd.`recid`
                WHERE d.sub_object_code = b.particulars 
                AND d.`program_title` LIKE '%Expanding the FNRI%'
            ), 0)
            +
            COALESCE((
                SELECT SUM(amount) 
                FROM tbl_ors_indirect_ps_dt i 
                JOIN tbl_ors_hd hd ON i.`project_id` = hd.`recid`
                WHERE i.sub_object_code = b.particulars 
                AND i.`program_title` LIKE '%Expanding the FNRI%'
            ), 0) AS total_sub_month
        FROM tbl_saob_ps_dt AS b
        JOIN
        tbl_saob_hd saob
        on b.project_id = saob.recid
        WHERE
        saob.project_title LIKE '%Expanding the FNRI%'
    ) AS t;
");
$rw = $query->getRowArray();
$ps_todate_grand_total = $rw['grand_total'];

//UP TO DATE MOOE GRAND TOTAL
$query = $this->db->query("
    SELECT 
        SUM(total_sub_month) AS grand_total
    FROM (
        SELECT
            COALESCE((
                SELECT SUM(amount) 
                FROM tbl_ors_direct_mooe_dt d 
                JOIN tbl_ors_hd hd ON d.`project_id` = hd.`recid`
                WHERE d.sub_object_code = b.particulars 
                AND d.`program_title` LIKE '%Expanding the FNRI%'
            ), 0)
            +
            COALESCE((
                SELECT SUM(amount) 
                FROM tbl_ors_indirect_mooe_dt i 
                JOIN tbl_ors_hd hd ON i.`project_id` = hd.`recid`
                WHERE i.sub_object_code = b.particulars 
                AND i.`program_title` LIKE '%Expanding the FNRI%'
            ), 0) AS total_sub_month
        FROM tbl_saob_mooe_dt AS b
        JOIN
        tbl_saob_hd saob
        on b.project_id = saob.recid
        WHERE
        saob.project_title LIKE '%Expanding the FNRI%'
    ) AS t;
");
$rw = $query->getRowArray();
$mooe_todate_grand_total = $rw['grand_total'];

//UP TO DATE CO GRAND TOTAL
$query = $this->db->query("
    SELECT 
        SUM(total_sub_month) AS grand_total
    FROM (
        SELECT
            COALESCE((
                SELECT SUM(amount) 
                FROM tbl_ors_direct_co_dt d 
                JOIN tbl_ors_hd hd ON d.`project_id` = hd.`recid`
                WHERE d.sub_object_code = b.particulars 
                AND d.`program_title` LIKE '%Expanding the FNRI%'
            ), 0)
            +
            COALESCE((
                SELECT SUM(amount) 
                FROM tbl_ors_indirect_co_dt i 
                JOIN tbl_ors_hd hd ON i.`project_id` = hd.`recid`
                WHERE i.sub_object_code = b.particulars 
                AND i.`program_title` LIKE '%Expanding the FNRI%'
            ), 0) AS total_sub_month
        FROM tbl_saob_co_dt AS b
        JOIN
        tbl_saob_hd saob
        on b.project_id = saob.recid
        WHERE
        saob.project_title LIKE '%Expanding the FNRI%'
    ) AS t;
");
$rw = $query->getRowArray();
$co_todate_grand_total = $rw['grand_total'];

$todate_grand_total = $ps_todate_grand_total + $mooe_todate_grand_total + $co_todate_grand_total;
$grand_unobligated = $total_project_budget - $todate_grand_total;
if (!empty($total_project_budget) && $total_project_budget > 0) {
    $grand_percentage_minus = ($todate_grand_total / $total_project_budget) * 100;
}

$Y = $pdf->GetY()+3.5;
$query = $this->db->query("
    SELECT
        a.`program_title`,
        a.`project_title`,
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
    WHERE 
        a.`project_title` like '%Expanding the FNRI%'
    ORDER BY a.`recid`
");
$hd_data = $query->getResultArray();
$total_all_sub_month = 0;
$total_all_todate_month = 0;
$ors_total_current_month = 0;

foreach ($hd_data as $hd_row) {
    $ps_current_month = 0;
    $mooe_current_month = 0;
    $co_current_month = 0;
    $ps_tolatest_month = 0;
    $mooe_tolatest_month = 0;
    $co_tolatest_month = 0;
    $total_unobligated = 0;
    $total_percentage_minus = 0;
    $program_title = $hd_row['program_title'];
    $project_title = $hd_row['project_title'];
    $recid = $hd_row['recid'];
    $total_ps = $hd_row['total_ps'];
    $total_mooe = $hd_row['total_mooe'];
    $total_co = $hd_row['total_co'];
    $total_approved_budget = $hd_row['total_approved_budget'];

    if ($project_title == "Expanding the FNRI's Nutrigenomics Laboratory: Towards Establishment of a World Class Philippines Nutrigenomics Center") {
        $program_tagging = 'Locally-Funded Project';
        $program_like = 'Expanding the FNRI';
    }


    //PS--------------------------------------------
    $query = $this->db->query("
        SELECT
            u.allotment_class,
            b.object_code,
            b.particulars AS sub_object_code,
            b.code AS uacs_code,
            b.approved_budget,
            COALESCE((
                SELECT SUM(amount) 
                FROM tbl_ors_direct_ps_dt d 
                JOIN
                tbl_ors_hd hd ON d.`project_id` = hd.`recid`
                WHERE d.sub_object_code = b.particulars AND hd.`ors_date` >= '$date_from' AND hd.`ors_date` < '$date_to' AND d.`program_title` like '%$program_like%'
            ), 0) + COALESCE((
                SELECT SUM(amount) 
                FROM tbl_ors_indirect_ps_dt i 
                JOIN
                tbl_ors_hd hd ON i.`project_id` = hd.`recid`
                WHERE i.sub_object_code = b.particulars AND hd.`ors_date` >= '$date_from' AND hd.`ors_date` < '$date_to' AND i.`program_title` like '%$program_like%'
            ), 0) AS total_sub_month,
            COALESCE((
                SELECT SUM(amount) 
                FROM tbl_ors_direct_ps_dt d 
                JOIN tbl_ors_hd hd ON d.project_id = hd.recid
                WHERE d.sub_object_code = b.particulars 
                AND d.program_title LIKE '%$program_like%'
            ), 0)
            + COALESCE((
                SELECT SUM(amount) 
                FROM tbl_ors_indirect_ps_dt i 
                JOIN tbl_ors_hd hd ON i.project_id = hd.recid
                WHERE i.sub_object_code = b.particulars
                AND i.program_title LIKE '%$project_like%'
            ), 0) AS total_sub_all
        FROM 
            tbl_saob_ps_dt AS b
        LEFT JOIN 
            mst_uacs AS u ON b.code = u.uacs_code
        WHERE 
            b.project_id = '$recid'
        ORDER BY 
            b.recid, b.particulars;

    ");
    $ps_data = $query->getResultArray();
    //total ps object code fetching
    $ps_object_code_totals = [];
    $ps_sub_month_totals = [];
    $ps_sub_year_totals = [];
    $last_object_code = '';
    
    $ps_total_current_month = 0;
    $ps_total_todate_month = 0;
    foreach ($ps_data as $ps_row) {
        $object_code = $ps_row['object_code'];
        $sub_object_code = $ps_row['sub_object_code'];
        $approved_budget = floatval($ps_row['approved_budget']);
        $total_sub_month = floatval($ps_row['total_sub_month']);
        $total_sub_all = floatval($ps_row['total_sub_all']);

        if (!isset($ps_object_code_totals[$object_code])) {
            $ps_object_code_totals[$object_code] = 0;
        }
        $ps_object_code_totals[$object_code] += $approved_budget;

        if (!isset($ps_sub_month_totals[$object_code])) {
            $ps_sub_month_totals[$object_code] = 0;
        }
        $ps_sub_month_totals[$object_code] += $total_sub_month;

        if (!isset($ps_sub_year_totals[$object_code])) {
            $ps_sub_year_totals[$object_code] = 0;
        }
        $ps_sub_year_totals[$object_code] += $total_sub_all;

        $ps_total_current_month += $total_sub_month;
        $ps_total_todate_month += $total_sub_all;

        $ps_total_unobligated = ($total_ps ?? 0) - ($ps_total_todate_month ?? 0);
        
        
        if (!empty($total_ps) && $total_ps > 0) {
            $ps_percentage_minus = ($ps_total_todate_month / $total_ps) * 100;   
        }


    }

    $ps_current_month += $ps_total_current_month;
    $ps_tolatest_month += $ps_total_todate_month;

    //MOOE------------------------------------------
    $query = $this->db->query("
        SELECT
            u.allotment_class,
            b.object_code,
            b.particulars AS sub_object_code,
            b.code AS uacs_code,
            b.approved_budget,
            COALESCE((
                SELECT SUM(amount) 
                FROM tbl_ors_direct_mooe_dt d 
                JOIN
                tbl_ors_hd hd ON d.`project_id` = hd.`recid`
                WHERE d.sub_object_code = b.particulars AND hd.`ors_date` >= '$date_from' AND hd.`ors_date` < '$date_to' AND d.`program_title` like '%$program_like%'
            ), 0) + COALESCE((
                SELECT SUM(amount) 
                FROM tbl_ors_indirect_mooe_dt i 
                JOIN
                tbl_ors_hd hd ON i.`project_id` = hd.`recid`
                WHERE i.sub_object_code = b.particulars AND hd.`ors_date` >= '$date_from' AND hd.`ors_date` < '$date_to' AND i.`program_title` like '%$program_like%'
            ), 0) AS total_sub_month,
            COALESCE((
                SELECT SUM(amount) 
                FROM tbl_ors_direct_mooe_dt d 
                JOIN tbl_ors_hd hd ON d.project_id = hd.recid
                WHERE d.sub_object_code = b.particulars
                AND d.program_title LIKE '%$program_like%'
            ), 0)
            + COALESCE((
                SELECT SUM(amount) 
                FROM tbl_ors_indirect_mooe_dt i 
                JOIN tbl_ors_hd hd ON i.project_id = hd.recid
                WHERE i.sub_object_code = b.particulars
                AND i.program_title LIKE '%$program_like%'
            ), 0) AS total_sub_all
        FROM 
            tbl_saob_mooe_dt AS b
        LEFT JOIN 
            mst_uacs AS u ON b.code = u.uacs_code
        WHERE 
            b.project_id = '$recid'
        ORDER BY 
            b.recid, b.particulars;

    ");
    $mooe_data = $query->getResultArray();
    //total mooe object code fetching
    $mooe_object_code_totals = [];
    $mooe_sub_month_totals = [];
    $mooe_sub_year_totals = [];
    $last_object_code = '';
    $mooe_total_current_month = 0;
    $mooe_total_todate_month = 0;
    foreach ($mooe_data as $mooe_row) {
        $object_code = $mooe_row['object_code'];
        $sub_object_code = $mooe_row['sub_object_code'];
        $approved_budget = floatval($mooe_row['approved_budget']);
        $total_sub_month = floatval($mooe_row['total_sub_month']);
        $total_sub_all = floatval($mooe_row['total_sub_all']);

        if (!isset($mooe_object_code_totals[$object_code])) {
            $mooe_object_code_totals[$object_code] = 0;
        }
        $mooe_object_code_totals[$object_code] += $approved_budget;

        if (!isset($mooe_sub_month_totals[$object_code])) {
            $mooe_sub_month_totals[$object_code] = 0;
        }
        $mooe_sub_month_totals[$object_code] += $total_sub_month;

        if (!isset($mooe_sub_year_totals[$object_code])) {
            $mooe_sub_year_totals[$object_code] = 0;
        }
        $mooe_sub_year_totals[$object_code] += $total_sub_all;

        $mooe_total_current_month += $total_sub_month;
        $mooe_total_todate_month += $total_sub_all;

        $mooe_total_unobligated = ($total_mooe ?? 0) - ($mooe_total_todate_month ?? 0);
        
        
        if (!empty($total_mooe) && $total_mooe > 0) {
            $mooe_percentage_minus = ($mooe_total_todate_month / $total_mooe) * 100;
        }
          
    }
    $mooe_current_month += $mooe_total_current_month;
    $mooe_tolatest_month += $mooe_total_todate_month;

    //CO--------------------------------------------
    $query = $this->db->query("
        SELECT
            u.allotment_class,
            b.object_code,
            b.particulars AS sub_object_code,
            b.code AS uacs_code,
            b.approved_budget,
            COALESCE((
                SELECT SUM(amount) 
                FROM tbl_ors_direct_co_dt d 
                JOIN
                tbl_ors_hd hd ON d.`project_id` = hd.`recid`
                WHERE d.sub_object_code = b.particulars AND hd.`ors_date` >= '$date_from' AND hd.`ors_date` < '$date_to' AND d.`program_title` like '%$program_like%'
            ), 0) + COALESCE((
                SELECT SUM(amount) 
                FROM tbl_ors_indirect_co_dt i 
                JOIN
                tbl_ors_hd hd ON i.`project_id` = hd.`recid`
                WHERE i.sub_object_code = b.particulars AND hd.`ors_date` >= '$date_from' AND hd.`ors_date` < '$date_to' AND i.`program_title` like '%$program_like%'
            ), 0) AS total_sub_month,
            COALESCE((
                SELECT SUM(amount) 
                FROM tbl_ors_direct_co_dt d 
                JOIN tbl_ors_hd hd ON d.project_id = hd.recid
                WHERE d.sub_object_code = b.particulars
                AND d.program_title LIKE '%$program_like%'
            ), 0)
            + COALESCE((
                SELECT SUM(amount) 
                FROM tbl_ors_indirect_co_dt i 
                JOIN tbl_ors_hd hd ON i.project_id = hd.recid
                WHERE i.sub_object_code = b.particulars
                AND i.program_title LIKE '%$program_like%'
            ), 0) AS total_sub_all
        FROM 
            tbl_saob_co_dt AS b
        LEFT JOIN 
            mst_uacs AS u ON b.code = u.uacs_code
        WHERE 
            b.project_id = '$recid'
        ORDER BY 
            b.recid, b.particulars;

    ");
    $co_data = $query->getResultArray();
    //total co object code fetching
    $co_object_code_totals = [];
    $co_sub_month_totals = [];
    $co_sub_year_totals = [];
    $last_object_code = '';
    $co_total_current_month = 0;
    $co_total_todate_month = 0;
    foreach ($co_data as $co_row) {
        $object_code = $co_row['object_code'];
        $sub_object_code = $co_row['sub_object_code'];
        $approved_budget = floatval($co_row['approved_budget']);
        $total_sub_month = floatval($co_row['total_sub_month']);
        $total_sub_all = floatval($co_row['total_sub_all']);

        if (!isset($co_object_code_totals[$object_code])) {
            $co_object_code_totals[$object_code] = 0;
        }
        $co_object_code_totals[$object_code] += $approved_budget;

        if (!isset($co_sub_month_totals[$object_code])) {
            $co_sub_month_totals[$object_code] = 0;
        }
        $co_sub_month_totals[$object_code] += $total_sub_month;

        if (!isset($co_sub_year_totals[$object_code])) {
            $co_sub_year_totals[$object_code] = 0;
        }
        $co_sub_year_totals[$object_code] += $total_sub_all;

        $co_total_current_month += $total_sub_month;
        $co_total_todate_month += $total_sub_all;

        $co_total_unobligated = ($total_co ?? 0) - ($co_total_todate_month ?? 0);
        
        
        if (!empty($total_co) && $total_co > 0) {
            $co_percentage_minus = ($co_total_todate_month / $total_co) * 100;
        }
          
    }

    $co_current_month += $co_total_current_month;
    $co_tolatest_month += $co_total_todate_month;

    $total_all_sub_month = $ps_current_month + $mooe_current_month + $co_current_month;
    $total_all_todate_month = $ps_tolatest_month + $mooe_tolatest_month + $co_tolatest_month;
    $total_unobligated = $total_approved_budget - $total_all_todate_month;
    if (!empty($total_approved_budget) && $total_approved_budget > 0) {
        $total_percentage_minus = ($total_all_todate_month / $total_approved_budget) * 100;
    }

    $Y = $pdf->GetY();
    // PROGRAM TAGGING LOGIC --------------------------------------------------------------------
    $startY = $Y; // Store the starting Y position

    // First, measure the height needed for program_tagging
    $pdf->SetXY(15, $Y);
    $pdf->SetFont('Arial', '', 8);
    $pdf->MultiCell(70, 3.5, $program_tagging, 0, 'L'); // Measure without border
    $afterProgramY = $pdf->GetY();
    $programHeight = $afterProgramY - $Y;

    // Draw complete PROGRAM TAGGING row with ALL borders
    $pdf->SetXY(10, $Y);
    $pdf->Cell(5, $programHeight, '', 'L', 0, 'C'); // First column

    $pdf->SetXY(15, $Y);
    $pdf->Cell(70, $programHeight, '', 0, 0, 'L'); // Program text column

    // Right-side columns for program tagging
    $pdf->SetXY(85, $Y);
    $pdf->Cell(11, $programHeight, '', 0, 0, 'C'); // UACS column

    $pdf->SetXY(96, $Y);
    $pdf->Cell(22, $programHeight, '', 1, 0, 'C'); // Budget column

    $pdf->SetXY(118, $Y);
    $pdf->Cell(22, $programHeight, '', 1, 0, 'C'); // This month column

    $pdf->SetXY(140, $Y);
    $pdf->Cell(22, $programHeight, '', 1, 0, 'C'); // To date column

    $pdf->SetXY(162, $Y);
    $pdf->Cell(22, $programHeight, '', 1, 0, 'C'); // Unobligated column

    $pdf->SetXY(184, $Y);
    $pdf->Cell(17, $programHeight, '', 1, 0, 'C'); // Percentage column

    // Draw program tagging totals (centered vertically)
    $programMiddleY = $Y + ($programHeight / 2) - 1.5;
    $pdf->SetXY(96, $programMiddleY);
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(22, 3, number_format($total_project_budget, 2), 0, 0, 'C');

    $pdf->SetXY(118, $programMiddleY);
    $pdf->Cell(22, 3, number_format($thismonth_grand_total, 2), 0, 0, 'C');

    $pdf->SetXY(140, $programMiddleY);
    $pdf->Cell(22, 3, number_format($todate_grand_total, 2), 0, 0, 'C');

    $pdf->SetXY(162, $programMiddleY);
    $pdf->Cell(22, 3, number_format($grand_unobligated, 2), 0, 0, 'C');

    $pdf->SetXY(184, $programMiddleY);
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(17, 3, number_format($grand_percentage_minus, 2) . '%', 0, 0, 'C');

    // Draw program tagging text
    $pdf->SetXY(15, $Y + 1);
    $pdf->SetFont('Arial', '', 8);
    $pdf->MultiCell(70, 3.5, '', 0, 'L');

    // PROJECT TITLE LOGIC --------------------------------------------------------------------
    $Y = $afterProgramY; // Move Y to after program tagging
    $projectStartY = $Y;

    // Measure the height needed for project_title
    $pdf->SetXY(15, $Y);
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->MultiCell(70, 3.5, $project_title, 0, 'L'); // Measure without border
    $afterProjectY = $pdf->GetY();
    $projectHeight = $afterProjectY - $Y;

    // Draw complete PROJECT TITLE row with ALL borders
    $pdf->SetXY(10, $Y);
    $pdf->Cell(5, $projectHeight, '', 'L', 0, 'C'); // First column

    $pdf->SetXY(15, $Y);
    $pdf->Cell(70, $projectHeight, '', 0, 0, 'L'); // Project text column

    // Right-side columns for project title
    $pdf->SetXY(85, $Y);
    $pdf->Cell(11, $projectHeight, '', 0, 0, 'C'); // UACS column

    $pdf->SetXY(96, $Y);
    $pdf->Cell(22, $projectHeight, '', 1, 0, 'C'); // Budget column

    $pdf->SetXY(118, $Y);
    $pdf->Cell(22, $projectHeight, '', 1, 0, 'C'); // This month column

    $pdf->SetXY(140, $Y);
    $pdf->Cell(22, $projectHeight, '', 1, 0, 'C'); // To date column

    $pdf->SetXY(162, $Y);
    $pdf->Cell(22, $projectHeight, '', 1, 0, 'C'); // Unobligated column

    $pdf->SetXY(184, $Y);
    $pdf->Cell(17, $projectHeight, '', 1, 0, 'C'); // Percentage column

    // Draw project title totals (centered vertically)
    $projectMiddleY = $Y + ($projectHeight / 2) - 1.5;
    $pdf->SetXY(96, $projectMiddleY);
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(22, 3, number_format($total_approved_budget, 2), 0, 0, 'C');

    $pdf->SetXY(118, $projectMiddleY);
    $pdf->Cell(22, 3, number_format($total_all_sub_month, 2), 0, 0, 'C');

    $pdf->SetXY(140, $projectMiddleY);
    $pdf->Cell(22, 3, number_format($total_all_todate_month, 2), 0, 0, 'C');

    $pdf->SetXY(162, $projectMiddleY);
    $pdf->Cell(22, 3, number_format($total_unobligated, 2), 0, 0, 'C');

    $pdf->SetXY(184, $projectMiddleY);
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(17, 3, number_format($total_percentage_minus, 2) . '%', 0, 0, 'C');

    // Draw project title text
    $pdf->SetXY(15, $Y + 1);
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->MultiCell(70, 3.5, '', 0, 'L');

    // Update Y position for next content
    $Y = $afterProjectY;

    // Clean up and prepare for next section
    $last_allotment_class = '';
    $last_sub_object_code = '';
    $last_object_code = '';

    $Y = $pdf->GetY(); // Add some spacing before the next section

    foreach ($ps_data as $ps_row) {
        $allotment_class = $ps_row['allotment_class'];
        $object_code = $ps_row['object_code'];
        $sub_object_code = $ps_row['sub_object_code'];
        $uacs_code = $ps_row['uacs_code'];
        $approved_budget = $ps_row['approved_budget'];

        $query = $this->db->query("
            SELECT COALESCE(SUM(amount),0) AS total_amount  
            FROM (
                SELECT dps.`amount` FROM tbl_ors_direct_ps_dt dps JOIN tbl_ors_hd hd on dps.`project_id` = hd.`recid` WHERE dps.`sub_object_code` = '$sub_object_code' AND hd.`ors_date` >= '$date_from' AND hd.`ors_date` < '$date_to' AND dps.`program_title` like '%$program_like%'
                UNION ALL
                SELECT idps.`amount` FROM tbl_ors_indirect_ps_dt idps JOIN tbl_ors_hd hd on idps.`project_id` = hd.`recid` WHERE idps.`sub_object_code` = '$sub_object_code' AND hd.`ors_date` >= '$date_from' AND hd.`ors_date` < '$date_to' AND idps.`program_title` like '%$program_like%'
            ) AS combined
        ");

        $result = $query->getRowArray();
        $month_amount = $result['total_amount'];

        $query = $this->db->query("
            SELECT COALESCE(SUM(amount),0) AS total_amount  
            FROM (
                SELECT dps.`amount` FROM tbl_ors_direct_ps_dt dps JOIN tbl_ors_hd hd on dps.`project_id` = hd.`recid` WHERE dps.`sub_object_code` = '$sub_object_code'  AND dps.`program_title` like '%$program_like%'
                UNION ALL
                SELECT idps.`amount` FROM tbl_ors_indirect_ps_dt idps JOIN tbl_ors_hd hd on idps.`project_id` = hd.`recid` WHERE idps.`sub_object_code` = '$sub_object_code' AND idps.`program_title` like '%$program_like%'
            ) AS combined
        ");

        $result = $query->getRowArray();
        $year_amount = $result['total_amount'];

        if ($Y > 265) {
            $pdf->AddPage();
            $Y = $pdf->GetY();
            $pdf->SetXY(10, $Y);
            $pdf->SetFont('Arial', 'B', 7);
            $pdf->Cell(64, 3.5, '', 'TRL', 0, 'C');//ROW 1
            $pdf->Cell(22, 3.5, '', 'TRL', 0, 'C');
            $pdf->Cell(22, 3.5, '', 'TRL', 0, 'C');
            $pdf->Cell(44, 3.5, '', 'TRL', 0, 'C');
            $pdf->Cell(22, 3.5, '', 'TRL', 0, 'C');
            $pdf->Cell(17, 3.5, '', 'TRL', 0, 'C');
            $Y+= 3.5;

            $pdf->SetXY(10, $Y);
            $pdf->Cell(64, 3.5, '', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, '', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, '', 'RL', 0, 'C');
            $pdf->Cell(44, 3.5, 'Obligation Incurred', 'BRL', 0, 'C');
            $pdf->Cell(22, 3.5, 'Unobligated', 'RL', 0, 'C');
            $pdf->Cell(17, 3.5, '', 'RL', 0, 'C');
            $Y+= 3.5;

            $pdf->SetXY(10, $Y);
            $pdf->Cell(64, 3.5, 'OBJECT OF EXPENDITURE', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, '', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, 'Revised', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, 'This month', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, 'Obligation', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, 'Balance of', 'RL', 0, 'C');
            $pdf->Cell(17, 3.5, 'Percent', 'RL', 0, 'C');
            $Y+= 3.5;

            $pdf->SetXY(10, $Y);
            $pdf->Cell(64, 3.5, '', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, 'UACS', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, 'Allotment', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, $month, 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, 'To Date', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, 'Allotment', 'RL', 0, 'C');
            $pdf->Cell(17, 3.5, 'Of', 'RL', 0, 'C');
            $Y+= 3.5;

            $pdf->SetXY(10, $Y);
            $pdf->Cell(64, 3.5, '', 'BRL', 0, 'C');
            $pdf->Cell(22, 3.5, '(1)', 'BRL', 0, 'C');
            $pdf->Cell(22, 3.5, '(4)', 'BRL', 0, 'C');
            $pdf->Cell(22, 3.5, '(5)', 'BRL', 0, 'C');
            $pdf->Cell(22, 3.5, '(6)', 'BRL', 0, 'C');
            $pdf->Cell(22, 3.5, '(7)=(4)-(6)', 'BRL', 0, 'C');
            $pdf->Cell(17, 3.5, 'Utilization', 'BRL', 0, 'C');

            $Y = $pdf->GetY()+3.5;
            // $pdf->SetXY(10, $Y);
            // $pdf->Cell(64, 215.5, '', 1, 0); 
            // $pdf->SetXY(74, $Y);
            // $pdf->Cell(22, 215.5, '', 1, 0); 
            // $pdf->SetXY(96, $Y);
            // $pdf->Cell(22, 215.5, '', 1, 0); 
            // $pdf->SetXY(118, $Y);
            // $pdf->Cell(22, 215.5, '', 1, 0); 
            // $pdf->SetXY(140, $Y);
            // $pdf->Cell(22, 215.5, '', 1, 0); 
            // $pdf->SetXY(162, $Y);
            // $pdf->Cell(22, 215.5, '', 1, 0); 
            // $pdf->SetXY(184, $Y);
            // $pdf->Cell(17, 215.5, '', 1, 0); 
        }

        // ================= ALLOTMENT CLASS ===================
        if ($allotment_class !== $last_allotment_class && $allotment_class !== null) {
            $pdf->SetFont('Arial', '', 8);

            // Save startY
            $startY = $Y;

            // Print text with wrapping
            $pdf->SetXY(10, $Y);
            $pdf->MultiCell(64, 4.5, $allotment_class, 0, 'L');

            // Measure row height
            $endY = $pdf->GetY();
            $totalRowHeight = $endY - $startY;

            // Draw borders
            $pdf->SetXY(10, $startY); $pdf->Cell(64, $totalRowHeight, '', 'L', 0, 'L');
            $pdf->SetXY(74, $startY); $pdf->Cell(22, $totalRowHeight, '', 0, 0, 'C');
            $pdf->SetXY(96, $startY); $pdf->Cell(22, $totalRowHeight, '', 0, 0, 'C');
            $pdf->SetXY(118, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
            $pdf->SetXY(140, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
            $pdf->SetXY(162, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
            $pdf->SetXY(184, $startY); $pdf->Cell(17, $totalRowHeight, '', 1, 0, 'C');

            // Center values vertically
            $middleY = $startY + ($totalRowHeight / 2) - 2.5;
            $pdf->SetFont('Arial', 'B', 8);

            $pdf->SetXY(96, $middleY); $pdf->Cell(22, 5, number_format($total_ps, 2), 1, 0, 'C');
            $pdf->SetXY(118, $middleY); $pdf->Cell(22, 5, number_format($ps_total_current_month, 2), 0, 0, 'C');
            $pdf->SetXY(140, $middleY); $pdf->Cell(22, 5, number_format($ps_total_todate_month, 2), 0, 0, 'C');
            $pdf->SetXY(162, $middleY); $pdf->Cell(22, 5, number_format($ps_total_unobligated, 2), 0, 0, 'C');
            $pdf->SetXY(184, $middleY); $pdf->Cell(17, 5, number_format($ps_percentage_minus, 2) . '%', 0, 0, 'C');

            $Y = $endY;
            $last_allotment_class = $allotment_class;
        }

        // ================= OBJECT CODE ===================
        if ($object_code !== $last_object_code && $object_code !== null) {
            $pdf->SetFont('Arial', '', 8);

            // Object Code: Start at X=15 (no blank cell before)
            $pdf->SetXY(10, $Y);
            $pdf->Cell(5, 4.5, '', 'L', 1, 'C');
            $pdf->SetXY(15, $Y);
            $pdf->MultiCell(59, 4.5, $object_code, 0, 'L');

            // Totals
            $total_for_object_code = $ps_object_code_totals[$object_code] ?? 0;
            $total_sub_month_amount = $ps_sub_month_totals[$object_code] ?? 0;
            $total_sub_year_amount = $ps_sub_year_totals[$object_code] ?? 0;
            $total_sub_unobligated = ($total_for_object_code ?? 0) - ($total_sub_year_amount ?? 0);

            // Columns
            $pdf->SetXY(96, $Y);
            $pdf->Cell(21.8, 4.5, number_format($total_for_object_code, 2), 1, 1, 'C');
            $pdf->SetXY(118, $Y);
            $pdf->Cell(21.8, 4.5, number_format($total_sub_month_amount, 2), 1, 1, 'C');
            $pdf->SetXY(140, $Y);
            $pdf->Cell(21.8, 4.5, number_format($total_sub_year_amount, 2), 1, 1, 'C');
            $pdf->SetXY(162, $Y);
            $pdf->Cell(21.8, 4.5, number_format($total_sub_unobligated, 2), 1, 1, 'C');
            $pdf->SetXY(184, $Y);
            $pdf->Cell(17, 4.5, '', 'R', 1, 'C');

            // Move down
            $Y += 4.5;
            $last_object_code = $object_code;
        }

        // ================= SUB OBJECT CODE ===================
        if ($sub_object_code !== $last_sub_object_code && $sub_object_code !== null) {
            $pdf->SetFont('Arial', '', 8);

            $startY = $Y;
            $pdf->SetXY(20, $Y);
            $pdf->MultiCell(55, 4.5, $sub_object_code, 0, 'L');

            $endY = $pdf->GetY();
            $totalRowHeight = $endY - $startY;

            if ($Y >255) {
                // Borders
                $pdf->SetXY(10, $startY); $pdf->Cell(64, $totalRowHeight, '', 'LB', 0, 'L');
                $pdf->SetXY(74, $startY); $pdf->Cell(22, $totalRowHeight, '', 'B', 0, 'C');
                $pdf->SetXY(96, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
                $pdf->SetXY(118, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
                $pdf->SetXY(140, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
                $pdf->SetXY(162, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
                $pdf->SetXY(184, $startY); $pdf->Cell(17, $totalRowHeight, '', 'RB', 0, 'C');
            }else{
                $pdf->SetXY(10, $startY); $pdf->Cell(64, $totalRowHeight, '', 'L', 0, 'L');
                $pdf->SetXY(74, $startY); $pdf->Cell(22, $totalRowHeight, '', 0, 0, 'C');
                $pdf->SetXY(96, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
                $pdf->SetXY(118, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
                $pdf->SetXY(140, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
                $pdf->SetXY(162, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
                $pdf->SetXY(184, $startY); $pdf->Cell(17, $totalRowHeight, '', 'R', 0, 'C');
            }

            // Center values
            $middleY = $startY + ($totalRowHeight / 2) - 2.5;

            $pdf->SetXY(74, $middleY); $pdf->Cell(22, 5, $uacs_code, 0, 0, 'C');
            $pdf->SetXY(96, $middleY); $pdf->Cell(22, 5, number_format($approved_budget,2), 0, 0, 'C');
            $pdf->SetXY(118, $middleY); $pdf->Cell(22, 5, $month_amount, 0, 0, 'C');
            $pdf->SetXY(140, $middleY); $pdf->Cell(22, 5, $year_amount, 0, 0, 'C');

            $subobligated_amount = $approved_budget - $year_amount;
            $pdf->SetXY(162, $middleY); $pdf->Cell(22, 5, number_format($subobligated_amount,2), 0, 0, 'C');
            $pdf->SetXY(184, $middleY); $pdf->Cell(17, 5, '', 0, 0, 'C');

            $Y = $endY;
            $last_sub_object_code = $sub_object_code;
        }

    }

    $last_allotment_class = '';
    $last_sub_object_code = '';
    $last_object_code = '';

    $Y = $pdf->GetY()+6.5;
    foreach ($mooe_data as $mooe_row) {
        $allotment_class = $mooe_row['allotment_class'];
        $object_code = $mooe_row['object_code'];
        $sub_object_code = $mooe_row['sub_object_code'];
        $uacs_code = $mooe_row['uacs_code'];
        $approved_budget = $mooe_row['approved_budget'];

        $query = $this->db->query("
            SELECT COALESCE(SUM(amount),0) AS total_amount  
            FROM (
                SELECT dmooe.`amount` FROM tbl_ors_direct_mooe_dt dmooe JOIN tbl_ors_hd hd on dmooe.`project_id` = hd.`recid` WHERE dmooe.`sub_object_code` = '$sub_object_code' AND hd.`ors_date` >= '$date_from' AND hd.`ors_date` < '$date_to' AND dmooe.`program_title` like '%$program_like%'
                UNION ALL
                SELECT idmooe.`amount` FROM tbl_ors_indirect_mooe_dt idmooe JOIN tbl_ors_hd hd on idmooe.`project_id` = hd.`recid` WHERE idmooe.`sub_object_code` = '$sub_object_code' AND hd.`ors_date` >= '$date_from' AND hd.`ors_date` < '$date_to' AND idmooe.`program_title` like '%$program_like%'
            ) AS combined
        ");

        $result = $query->getRowArray();
        $month_amount = $result['total_amount'];

        $query = $this->db->query("
            SELECT COALESCE(SUM(amount),0) AS total_amount  
            FROM (
                SELECT dmooe.`amount` FROM tbl_ors_direct_mooe_dt dmooe JOIN tbl_ors_hd hd on dmooe.`project_id` = hd.`recid` WHERE dmooe.`sub_object_code` = '$sub_object_code' AND dmooe.`program_title` like '%$program_like%'
                UNION ALL
                SELECT idmooe.`amount` FROM tbl_ors_indirect_mooe_dt idmooe JOIN tbl_ors_hd hd on idmooe.`project_id` = hd.`recid` WHERE idmooe.`sub_object_code` = '$sub_object_code' AND idmooe.`program_title` like '%$program_like%'
            ) AS combined
        ");

        $result = $query->getRowArray();
        $year_amount = $result['total_amount'];

        if ($Y > 265) {
            $pdf->AddPage();
            $Y = $pdf->GetY();
            $pdf->SetXY(10, $Y);
            $pdf->SetFont('Arial', 'B', 7);
            $pdf->Cell(64, 3.5, '', 'TRL', 0, 'C');//ROW 1
            $pdf->Cell(22, 3.5, '', 'TRL', 0, 'C');
            $pdf->Cell(22, 3.5, '', 'TRL', 0, 'C');
            $pdf->Cell(44, 3.5, '', 'TRL', 0, 'C');
            $pdf->Cell(22, 3.5, '', 'TRL', 0, 'C');
            $pdf->Cell(17, 3.5, '', 'TRL', 0, 'C');
            $Y+= 3.5;

            $pdf->SetXY(10, $Y);
            $pdf->Cell(64, 3.5, '', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, '', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, '', 'RL', 0, 'C');
            $pdf->Cell(44, 3.5, 'Obligation Incurred', 'BRL', 0, 'C');
            $pdf->Cell(22, 3.5, 'Unobligated', 'RL', 0, 'C');
            $pdf->Cell(17, 3.5, '', 'RL', 0, 'C');
            $Y+= 3.5;

            $pdf->SetXY(10, $Y);
            $pdf->Cell(64, 3.5, 'OBJECT OF EXPENDITURE', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, '', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, 'Revised', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, 'This month', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, 'Obligation', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, 'Balance of', 'RL', 0, 'C');
            $pdf->Cell(17, 3.5, 'Percent', 'RL', 0, 'C');
            $Y+= 3.5;

            $pdf->SetXY(10, $Y);
            $pdf->Cell(64, 3.5, '', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, 'UACS', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, 'Allotment', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, $month, 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, 'To Date', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, 'Allotment', 'RL', 0, 'C');
            $pdf->Cell(17, 3.5, 'Of', 'RL', 0, 'C');
            $Y+= 3.5;

            $pdf->SetXY(10, $Y);
            $pdf->Cell(64, 3.5, '', 'BRL', 0, 'C');
            $pdf->Cell(22, 3.5, '(1)', 'BRL', 0, 'C');
            $pdf->Cell(22, 3.5, '(4)', 'BRL', 0, 'C');
            $pdf->Cell(22, 3.5, '(5)', 'BRL', 0, 'C');
            $pdf->Cell(22, 3.5, '(6)', 'BRL', 0, 'C');
            $pdf->Cell(22, 3.5, '(7)=(4)-(6)', 'BRL', 0, 'C');
            $pdf->Cell(17, 3.5, 'Utilization', 'BRL', 0, 'C');

            $Y = $pdf->GetY()+3.5;
            // $pdf->SetXY(10, $Y);
            // $pdf->Cell(64, 215.5, '', 1, 0); 
            // $pdf->SetXY(74, $Y);
            // $pdf->Cell(22, 215.5, '', 1, 0); 
            // $pdf->SetXY(96, $Y);
            // $pdf->Cell(22, 215.5, '', 1, 0); 
            // $pdf->SetXY(118, $Y);
            // $pdf->Cell(22, 215.5, '', 1, 0); 
            // $pdf->SetXY(140, $Y);
            // $pdf->Cell(22, 215.5, '', 1, 0); 
            // $pdf->SetXY(162, $Y);
            // $pdf->Cell(22, 215.5, '', 1, 0); 
            // $pdf->SetXY(184, $Y);
            // $pdf->Cell(17, 215.5, '', 1, 0); 
        }

        // ================= ALLOTMENT CLASS ===================
        if ($allotment_class !== $last_allotment_class && $allotment_class !== null) {
            $pdf->SetFont('Arial', '', 8);

            // Save startY
            $startY = $Y;

            // Print text with wrapping
            $pdf->SetXY(10, $Y);
            $pdf->MultiCell(64, 4.5, $allotment_class, 0, 'L');

            // Measure row height
            $endY = $pdf->GetY();
            $totalRowHeight = $endY - $startY;

            // Draw borders
            $pdf->SetXY(10, $startY); $pdf->Cell(64, $totalRowHeight, '', 'L', 0, 'L');
            $pdf->SetXY(74, $startY); $pdf->Cell(22, $totalRowHeight, '', 0, 0, 'C');
            $pdf->SetXY(96, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
            $pdf->SetXY(118, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
            $pdf->SetXY(140, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
            $pdf->SetXY(162, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
            $pdf->SetXY(184, $startY); $pdf->Cell(17, $totalRowHeight, '', 'R', 0, 'C');

            // Center values vertically
            $middleY = $startY + ($totalRowHeight / 2) - 2.5;
            $pdf->SetFont('Arial', 'B', 8);

            $pdf->SetXY(96, $middleY); $pdf->Cell(22, 5, number_format($total_mooe, 2), 'L', 0, 'C');
            $pdf->SetXY(118, $middleY); $pdf->Cell(22, 5, number_format($mooe_total_current_month, 2), 0, 0, 'C');
            $pdf->SetXY(140, $middleY); $pdf->Cell(22, 5, number_format($mooe_total_todate_month, 2), 0, 0, 'C');
            $pdf->SetXY(162, $middleY); $pdf->Cell(22, 5, number_format($mooe_total_unobligated, 2), 0, 0, 'C');
            $pdf->SetXY(184, $middleY); $pdf->Cell(17, 5, number_format($mooe_percentage_minus, 2) . '%', 0, 0, 'C');

            $Y = $endY;
            $last_allotment_class = $allotment_class;
        }

        // ================= OBJECT CODE ===================
        if ($object_code !== $last_object_code && $object_code !== null) {
            $pdf->SetFont('Arial', '', 8);

            // Object Code: Start at X=15 (no blank cell before)
            $pdf->SetXY(10, $Y);
            $pdf->Cell(5, 4.5, '', 'L', 1, 'C');
            $pdf->SetXY(15, $Y);
            $pdf->MultiCell(59, 4.5, $object_code, 0, 'L');

            // Totals
            $total_for_object_code = $mooe_object_code_totals[$object_code] ?? 0;
            $total_sub_month_amount = $mooe_sub_month_totals[$object_code] ?? 0;
            $total_sub_year_amount = $mooe_sub_year_totals[$object_code] ?? 0;
            $total_sub_unobligated = ($total_for_object_code ?? 0) - ($total_sub_year_amount ?? 0);

            // Columns
            $pdf->SetXY(96, $Y);
            $pdf->Cell(21.8, 4.5, number_format($total_for_object_code, 2), 1, 1, 'C');
            $pdf->SetXY(118, $Y);
            $pdf->Cell(21.8, 4.5, number_format($total_sub_month_amount, 2), 1, 1, 'C');
            $pdf->SetXY(140, $Y);
            $pdf->Cell(21.8, 4.5, number_format($total_sub_year_amount, 2), 1, 1, 'C');
            $pdf->SetXY(162, $Y);
            $pdf->Cell(21.8, 4.5, number_format($total_sub_unobligated, 2), 1, 1, 'C');
            $pdf->SetXY(184, $Y);
            $pdf->Cell(17, 4.5, '', 'R', 1, 'C');

            // Move down
            $Y += 4.5;
            $last_object_code = $object_code;
        }

        // ================= SUB OBJECT CODE ===================
        if ($sub_object_code !== $last_sub_object_code && $sub_object_code !== null) {
            $pdf->SetFont('Arial', '', 8);

            $startY = $Y;
            $pdf->SetXY(20, $Y);
            $pdf->MultiCell(55, 4.5, $sub_object_code, 0, 'L');

            $endY = $pdf->GetY();
            $totalRowHeight = $endY - $startY;

            if ($Y >255) {
                // Borders
                $pdf->SetXY(10, $startY); $pdf->Cell(64, $totalRowHeight, '', 'LB', 0, 'L');
                $pdf->SetXY(74, $startY); $pdf->Cell(22, $totalRowHeight, '', 'B', 0, 'C');
                $pdf->SetXY(96, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
                $pdf->SetXY(118, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
                $pdf->SetXY(140, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
                $pdf->SetXY(162, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
                $pdf->SetXY(184, $startY); $pdf->Cell(17, $totalRowHeight, '', 'RB', 0, 'C');
            }else{
                $pdf->SetXY(10, $startY); $pdf->Cell(64, $totalRowHeight, '', 'L', 0, 'L');
                $pdf->SetXY(74, $startY); $pdf->Cell(22, $totalRowHeight, '', 0, 0, 'C');
                $pdf->SetXY(96, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
                $pdf->SetXY(118, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
                $pdf->SetXY(140, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
                $pdf->SetXY(162, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
                $pdf->SetXY(184, $startY); $pdf->Cell(17, $totalRowHeight, '', 'R', 0, 'C');
            }

            // Center values
            $middleY = $startY + ($totalRowHeight / 2) - 2.5;

            $pdf->SetXY(74, $middleY); $pdf->Cell(22, 5, $uacs_code, 0, 0, 'C');
            $pdf->SetXY(96, $middleY); $pdf->Cell(22, 5, number_format($approved_budget,2), 0, 0, 'C');
            $pdf->SetXY(118, $middleY); $pdf->Cell(22, 5, $month_amount, 0, 0, 'C');
            $pdf->SetXY(140, $middleY); $pdf->Cell(22, 5, $year_amount, 0, 0, 'C');

            $subobligated_amount = $approved_budget - $year_amount;
            $pdf->SetXY(162, $middleY); $pdf->Cell(22, 5, number_format($subobligated_amount,2), 0, 0, 'C');
            $pdf->SetXY(184, $middleY); $pdf->Cell(17, 5, '', 0, 0, 'C');

            $Y = $endY;
            $last_sub_object_code = $sub_object_code;
        }

    }

    $last_allotment_class = '';
    $last_sub_object_code = '';
    $last_object_code = '';

    $Y = $pdf->GetY();
    foreach ($co_data as $co_row) {
        $allotment_class = $co_row['allotment_class'];
        $object_code = $co_row['object_code'];
        $sub_object_code = $co_row['sub_object_code'];
        $uacs_code = $co_row['uacs_code'];
        $approved_budget = $co_row['approved_budget'];

        $query = $this->db->query("
            SELECT COALESCE(SUM(amount),0) AS total_amount  
            FROM (
                SELECT dco.`amount` FROM tbl_ors_direct_co_dt dco JOIN tbl_ors_hd hd on dco.`project_id` = hd.`recid` WHERE dco.`sub_object_code` = '$sub_object_code' AND hd.`ors_date` >= '$date_from' AND hd.`ors_date` < '$date_to' AND dco.`program_title` like '%$program_like%'
                UNION ALL
                SELECT idco.`amount` FROM tbl_ors_indirect_co_dt idco JOIN tbl_ors_hd hd on idco.`project_id` = hd.`recid` WHERE idco.`sub_object_code` = '$sub_object_code' AND hd.`ors_date` >= '$date_from' AND hd.`ors_date` < '$date_to' AND idco.`program_title` like '%$program_like%'
            ) AS combined
        ");

        $result = $query->getRowArray();
        $month_amount = $result['total_amount'];

        $query = $this->db->query("
            SELECT COALESCE(SUM(amount),0) AS total_amount  
            FROM (
                SELECT dco.`amount` FROM tbl_ors_direct_co_dt dco JOIN tbl_ors_hd hd on dco.`project_id` = hd.`recid` WHERE dco.`sub_object_code` = '$sub_object_code' AND dco.`program_title` like '%$program_like%'
                UNION ALL
                SELECT idco.`amount` FROM tbl_ors_indirect_co_dt idco JOIN tbl_ors_hd hd on idco.`project_id` = hd.`recid` WHERE idco.`sub_object_code` = '$sub_object_code' AND idco.`program_title` like '%$program_like%'
            ) AS combined
        ");

        $result = $query->getRowArray();
        $year_amount = $result['total_amount'];

        if ($Y > 265) {
            $pdf->AddPage();
            $Y = $pdf->GetY();
            $pdf->SetXY(10, $Y);
            $pdf->SetFont('Arial', 'B', 7);
            $pdf->Cell(64, 3.5, '', 'TRL', 0, 'C');//ROW 1
            $pdf->Cell(22, 3.5, '', 'TRL', 0, 'C');
            $pdf->Cell(22, 3.5, '', 'TRL', 0, 'C');
            $pdf->Cell(44, 3.5, '', 'TRL', 0, 'C');
            $pdf->Cell(22, 3.5, '', 'TRL', 0, 'C');
            $pdf->Cell(17, 3.5, '', 'TRL', 0, 'C');
            $Y+= 3.5;

            $pdf->SetXY(10, $Y);
            $pdf->Cell(64, 3.5, '', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, '', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, '', 'RL', 0, 'C');
            $pdf->Cell(44, 3.5, 'Obligation Incurred', 'BRL', 0, 'C');
            $pdf->Cell(22, 3.5, 'Unobligated', 'RL', 0, 'C');
            $pdf->Cell(17, 3.5, '', 'RL', 0, 'C');
            $Y+= 3.5;

            $pdf->SetXY(10, $Y);
            $pdf->Cell(64, 3.5, 'OBJECT OF EXPENDITURE', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, '', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, 'Revised', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, 'This month', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, 'Obligation', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, 'Balance of', 'RL', 0, 'C');
            $pdf->Cell(17, 3.5, 'Percent', 'RL', 0, 'C');
            $Y+= 3.5;

            $pdf->SetXY(10, $Y);
            $pdf->Cell(64, 3.5, '', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, 'UACS', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, 'Allotment', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, $month, 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, 'To Date', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, 'Allotment', 'RL', 0, 'C');
            $pdf->Cell(17, 3.5, 'Of', 'RL', 0, 'C');
            $Y+= 3.5;

            $pdf->SetXY(10, $Y);
            $pdf->Cell(64, 3.5, '', 'BRL', 0, 'C');
            $pdf->Cell(22, 3.5, '(1)', 'BRL', 0, 'C');
            $pdf->Cell(22, 3.5, '(4)', 'BRL', 0, 'C');
            $pdf->Cell(22, 3.5, '(5)', 'BRL', 0, 'C');
            $pdf->Cell(22, 3.5, '(6)', 'BRL', 0, 'C');
            $pdf->Cell(22, 3.5, '(7)=(4)-(6)', 'BRL', 0, 'C');
            $pdf->Cell(17, 3.5, 'Utilization', 'BRL', 0, 'C');

            $Y = $pdf->GetY()+3.5;
            // $pdf->SetXY(10, $Y);
            // $pdf->Cell(64, 215.5, '', 1, 0); 
            // $pdf->SetXY(74, $Y);
            // $pdf->Cell(22, 215.5, '', 1, 0); 
            // $pdf->SetXY(96, $Y);
            // $pdf->Cell(22, 215.5, '', 1, 0); 
            // $pdf->SetXY(118, $Y);
            // $pdf->Cell(22, 215.5, '', 1, 0); 
            // $pdf->SetXY(140, $Y);
            // $pdf->Cell(22, 215.5, '', 1, 0); 
            // $pdf->SetXY(162, $Y);
            // $pdf->Cell(22, 215.5, '', 1, 0); 
            // $pdf->SetXY(184, $Y);
            // $pdf->Cell(17, 215.5, '', 1, 0); 
        }

        // ================= ALLOTMENT CLASS ===================
        if ($allotment_class !== $last_allotment_class && $allotment_class !== null) {
            $pdf->SetFont('Arial', '', 8);

            // Save startY
            $startY = $Y;

            // Print text with wrapping
            $pdf->SetXY(10, $Y);
            $pdf->MultiCell(64, 4.5, $allotment_class, 0, 'L');

            // Measure row height
            $endY = $pdf->GetY();
            $totalRowHeight = $endY - $startY;

            // Draw borders
            $pdf->SetXY(10, $startY); $pdf->Cell(64, $totalRowHeight, '', 'L', 0, 'L');
            $pdf->SetXY(74, $startY); $pdf->Cell(22, $totalRowHeight, '', 0, 0, 'C');
            $pdf->SetXY(96, $startY); $pdf->Cell(22, $totalRowHeight, '', 0, 0, 'C');
            $pdf->SetXY(118, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
            $pdf->SetXY(140, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
            $pdf->SetXY(162, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
            $pdf->SetXY(184, $startY); $pdf->Cell(17, $totalRowHeight, '', 1, 0, 'C');

            // Center values vertically
            $middleY = $startY + ($totalRowHeight / 2) - 2.5;
            $pdf->SetFont('Arial', 'B', 8);

            $pdf->SetXY(96, $middleY); $pdf->Cell(22, 5, number_format($total_co, 2), 1, 0, 'C');
            $pdf->SetXY(118, $middleY); $pdf->Cell(22, 5, number_format($co_total_current_month, 2), 0, 0, 'C');
            $pdf->SetXY(140, $middleY); $pdf->Cell(22, 5, number_format($co_total_todate_month, 2), 0, 0, 'C');
            $pdf->SetXY(162, $middleY); $pdf->Cell(22, 5, number_format($co_total_unobligated, 2), 0, 0, 'C');
            $pdf->SetXY(184, $middleY); $pdf->Cell(17, 5, number_format($co_percentage_minus, 2) . '%', 0, 0, 'C');

            $Y = $endY;
            $last_allotment_class = $allotment_class;
        }

        // ================= OBJECT CODE ===================
        if ($object_code !== $last_object_code && $object_code !== null) {
            $pdf->SetFont('Arial', '', 8);

            // Object Code: Start at X=15 (no blank cell before)
            $pdf->SetXY(10, $Y);
            $pdf->Cell(5, 4.5, '', 'L', 1, 'C');
            $pdf->SetXY(15, $Y);
            $pdf->MultiCell(59, 4.5, $object_code, 0, 'L');

            // Totals
            $total_for_object_code = $co_object_code_totals[$object_code] ?? 0;
            $total_sub_month_amount = $co_sub_month_totals[$object_code] ?? 0;
            $total_sub_year_amount = $co_sub_year_totals[$object_code] ?? 0;
            $total_sub_unobligated = ($total_for_object_code ?? 0) - ($total_sub_year_amount ?? 0);

            // Columns
            $pdf->SetXY(96, $Y);
            $pdf->Cell(21.8, 4.5, number_format($total_for_object_code, 2), 1, 1, 'C');
            $pdf->SetXY(118, $Y);
            $pdf->Cell(21.8, 4.5, number_format($total_sub_month_amount, 2), 1, 1, 'C');
            $pdf->SetXY(140, $Y);
            $pdf->Cell(21.8, 4.5, number_format($total_sub_year_amount, 2), 1, 1, 'C');
            $pdf->SetXY(162, $Y);
            $pdf->Cell(21.8, 4.5, number_format($total_sub_unobligated, 2), 1, 1, 'C');
            $pdf->SetXY(184, $Y);
            $pdf->Cell(17, 4.5, '', 'R', 1, 'C');

            // Move down
            $Y += 4.5;
            $last_object_code = $object_code;
        }

        // ================= SUB OBJECT CODE ===================
        if ($sub_object_code !== $last_sub_object_code && $sub_object_code !== null) {
            $pdf->SetFont('Arial', '', 8);

            $startY = $Y;
            $pdf->SetXY(20, $Y);
            $pdf->MultiCell(55, 4.5, $sub_object_code, 0, 'L');

            $endY = $pdf->GetY();
            $totalRowHeight = $endY - $startY;

            if ($Y >260) {
                // Borders
                $pdf->SetXY(10, $startY); $pdf->Cell(64, $totalRowHeight, '', 'LB', 0, 'L');
                $pdf->SetXY(74, $startY); $pdf->Cell(22, $totalRowHeight, '', 'B', 0, 'C');
                $pdf->SetXY(96, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
                $pdf->SetXY(118, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
                $pdf->SetXY(140, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
                $pdf->SetXY(162, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
                $pdf->SetXY(184, $startY); $pdf->Cell(17, $totalRowHeight, '', 'RB', 0, 'C');
            }else{
                $pdf->SetXY(10, $startY); $pdf->Cell(64, $totalRowHeight, '', 'L', 0, 'L');
                $pdf->SetXY(74, $startY); $pdf->Cell(22, $totalRowHeight, '', 0, 0, 'C');
                $pdf->SetXY(96, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
                $pdf->SetXY(118, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
                $pdf->SetXY(140, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
                $pdf->SetXY(162, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
                $pdf->SetXY(184, $startY); $pdf->Cell(17, $totalRowHeight, '', 'R', 0, 'C');
            }

            // Center values
            $middleY = $startY + ($totalRowHeight / 2) - 2.5;

            $pdf->SetXY(74, $middleY); $pdf->Cell(22, 5, $uacs_code, 0, 0, 'C');
            $pdf->SetXY(96, $middleY); $pdf->Cell(22, 5, number_format($approved_budget,2), 0, 0, 'C');
            $pdf->SetXY(118, $middleY); $pdf->Cell(22, 5, $month_amount, 0, 0, 'C');
            $pdf->SetXY(140, $middleY); $pdf->Cell(22, 5, $year_amount, 0, 0, 'C');

            $subobligated_amount = $approved_budget - $year_amount;
            $pdf->SetXY(162, $middleY); $pdf->Cell(22, 5, number_format($subobligated_amount,2), 0, 0, 'C');
            $pdf->SetXY(184, $middleY); $pdf->Cell(17, 5, '', 0, 0, 'C');

            $Y = $endY;
            $last_sub_object_code = $sub_object_code;
        }

    }

    $total_year_ps += $total_ps;
    $total_year_mooe += $total_mooe;
    $total_year_co += $total_co;
    $ors_total_current_month += $total_all_sub_month;

    $Y = $pdf->GetY() + 3.5;    

}
$Y = $pdf->GetY() + 7.5;  
$pdf->SetXY(10, $Y);
$pdf->Cell(191, 3.5, '', 'TRL', 0, 'C');

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//------------------------------------------------------------ NUTRITIONAL ASSESSMENT AND MONITORING ON FOOD AND NUTRITION ----------------------------------------------------
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

$total_curryear_budget = 0;
$total_year_ps = 0;
$total_year_mooe = 0;
$total_year_co = 0;
$total_sub_month_amount = 0;
$total_sub_year_amount = 0;
$program_tagging = "";
$project_like = "";
$program_class = "";

$total_todate_month = 0;
$ps_total_unobligated = 0;
$mooe_total_unobligated = 0;
$co_total_unobligated = 0;
$ps_percentage_minus = 0;
$mooe_percentage_minus = 0;
$co_percentage_minus = 0;
$total_project_budget = 0;
$ps_grand_total = 0;
$mooe_grand_total = 0;
$co_grand_total = 0;
$thismonth_grand_total = 0;

$ps_todate_grand_total = 0;
$mooe_todate_grand_total = 0;
$co_todate_grand_total = 0;
$todate_grand_total = 0;

$grand_unobligated = 0;
$grand_percentage_minus = 0;
$printed_once = '';
$printed_after_mooe = '';
//CURRENT YEAR BUDGET

$Y = $pdf->GetY() +3.5;


$query = $this->db->query("
    SELECT
        SUM(
            COALESCE((SELECT SUM(approved_budget) FROM tbl_saob_ps_dt WHERE project_id = a.`recid`), 0) +
            COALESCE((SELECT SUM(approved_budget) FROM tbl_saob_mooe_dt WHERE project_id = a.`recid`), 0) +
            COALESCE((SELECT SUM(approved_budget) FROM tbl_saob_co_dt WHERE project_id = a.`recid`), 0)
        ) AS total_approved_budget
    FROM
        `tbl_saob_hd` a
    WHERE 
        a.`project_title` like '%Nutritional Assessment and Monitoring on Food and Nutrition%'
    ORDER BY a.`recid`
");
$rw = $query->getRowArray();
$total_project_budget = $rw['total_approved_budget'];

//CURRENT DATE GRAND TOTAL

//PS GRAND TOTAL
$query = $this->db->query("
    SELECT 
        SUM(total_sub_month) AS grand_total
    FROM (
        SELECT
            COALESCE((
                SELECT SUM(amount) 
                FROM tbl_ors_direct_ps_dt d 
                JOIN tbl_ors_hd hd ON d.`project_id` = hd.`recid`
                WHERE d.sub_object_code = b.particulars 
                AND hd.`ors_date` >= '$date_from' 
                AND hd.`ors_date` < '$date_to'
                AND d.`program_title` LIKE '%NUTRITIONAL ASSESSMENT AND MONITORING ON FOOD AND NUTRITION%'
            ), 0)
            +
            COALESCE((
                SELECT SUM(amount) 
                FROM tbl_ors_indirect_ps_dt i 
                JOIN tbl_ors_hd hd ON i.`project_id` = hd.`recid`
                WHERE i.sub_object_code = b.particulars 
                AND hd.`ors_date` >= '$date_from' 
                AND hd.`ors_date` < '$date_to'
                AND i.`program_title` LIKE '%NUTRITIONAL ASSESSMENT AND MONITORING ON FOOD AND NUTRITION%'
            ), 0) AS total_sub_month
        FROM tbl_saob_ps_dt AS b
        JOIN
        tbl_saob_hd saob
        on b.project_id = saob.recid
        WHERE
        saob.project_title LIKE '%NUTRITIONAL ASSESSMENT AND MONITORING ON FOOD AND NUTRITION%'
    ) AS t;
");
$rw = $query->getRowArray();
$ps_grand_total = $rw['grand_total'];

//MOOE GRAND TOTAL
$query = $this->db->query("
    SELECT 
        SUM(total_sub_month) AS grand_total
    FROM (
        SELECT
            COALESCE((
                SELECT SUM(amount) 
                FROM tbl_ors_direct_mooe_dt d 
                JOIN tbl_ors_hd hd ON d.`project_id` = hd.`recid`
                WHERE d.sub_object_code = b.particulars 
                AND hd.`ors_date` >= '$date_from' 
                AND hd.`ors_date` < '$date_to'
                AND d.`program_title` LIKE '%NUTRITIONAL ASSESSMENT AND MONITORING ON FOOD AND NUTRITION%'
            ), 0)
            +
            COALESCE((
                SELECT SUM(amount) 
                FROM tbl_ors_indirect_mooe_dt i 
                JOIN tbl_ors_hd hd ON i.`project_id` = hd.`recid`
                WHERE i.sub_object_code = b.particulars 
                AND hd.`ors_date` >= '$date_from' 
                AND hd.`ors_date` < '$date_to'
                AND i.`program_title` LIKE '%NUTRITIONAL ASSESSMENT AND MONITORING ON FOOD AND NUTRITION%'
            ), 0) AS total_sub_month
        FROM tbl_saob_mooe_dt AS b
        JOIN
        tbl_saob_hd saob
        on b.project_id = saob.recid
        WHERE
        saob.project_title LIKE '%NUTRITIONAL ASSESSMENT AND MONITORING ON FOOD AND NUTRITION%'
    ) AS t;
");
$rw = $query->getRowArray();
$mooe_grand_total = $rw['grand_total'];

//CO GRAND TOTAL
$query = $this->db->query("
    SELECT 
        SUM(total_sub_month) AS grand_total
    FROM (
        SELECT
            COALESCE((
                SELECT SUM(amount) 
                FROM tbl_ors_direct_co_dt d 
                JOIN tbl_ors_hd hd ON d.`project_id` = hd.`recid`
                WHERE d.sub_object_code = b.particulars 
                AND hd.`ors_date` >= '$date_from' 
                AND hd.`ors_date` < '$date_to'
                AND d.`program_title` LIKE '%NUTRITIONAL ASSESSMENT AND MONITORING ON FOOD AND NUTRITION%'
            ), 0)
            +
            COALESCE((
                SELECT SUM(amount) 
                FROM tbl_ors_indirect_co_dt i 
                JOIN tbl_ors_hd hd ON i.`project_id` = hd.`recid`
                WHERE i.sub_object_code = b.particulars 
                AND hd.`ors_date` >= '$date_from' 
                AND hd.`ors_date` < '$date_to'
                AND i.`program_title` LIKE '%NUTRITIONAL ASSESSMENT AND MONITORING ON FOOD AND NUTRITION%'
            ), 0) AS total_sub_month
        FROM tbl_saob_co_dt AS b
        JOIN
        tbl_saob_hd saob
        on b.project_id = saob.recid
        WHERE
        saob.project_title LIKE '%NUTRITIONAL ASSESSMENT AND MONITORING ON FOOD AND NUTRITION%'
    ) AS t;
");
$rw = $query->getRowArray();
$co_grand_total = $rw['grand_total'];

$thismonth_grand_total = $ps_grand_total + $mooe_grand_total + $co_grand_total;


//UP TO DATE GRAND TOTAL

//UP TO DATE PS GRAND TOTAL
$query = $this->db->query("
    SELECT 
        SUM(total_sub_month) AS grand_total
    FROM (
        SELECT
            COALESCE((
                SELECT SUM(amount) 
                FROM tbl_ors_direct_ps_dt d 
                JOIN tbl_ors_hd hd ON d.`project_id` = hd.`recid`
                WHERE d.sub_object_code = b.particulars 
                AND d.`program_title` LIKE '%NUTRITIONAL ASSESSMENT AND MONITORING ON FOOD AND NUTRITION%'
            ), 0)
            +
            COALESCE((
                SELECT SUM(amount) 
                FROM tbl_ors_indirect_ps_dt i 
                JOIN tbl_ors_hd hd ON i.`project_id` = hd.`recid`
                WHERE i.sub_object_code = b.particulars 
                AND i.`program_title` LIKE '%NUTRITIONAL ASSESSMENT AND MONITORING ON FOOD AND NUTRITION%'
            ), 0) AS total_sub_month
        FROM tbl_saob_ps_dt AS b
        JOIN
        tbl_saob_hd saob
        on b.project_id = saob.recid
        WHERE
        saob.project_title LIKE '%NUTRITIONAL ASSESSMENT AND MONITORING ON FOOD AND NUTRITION%'
    ) AS t;
");
$rw = $query->getRowArray();
$ps_todate_grand_total = $rw['grand_total'];

//UP TO DATE MOOE GRAND TOTAL
$query = $this->db->query("
    SELECT 
        SUM(total_sub_month) AS grand_total
    FROM (
        SELECT
            COALESCE((
                SELECT SUM(amount) 
                FROM tbl_ors_direct_mooe_dt d 
                JOIN tbl_ors_hd hd ON d.`project_id` = hd.`recid`
                WHERE d.sub_object_code = b.particulars 
                AND d.`program_title` LIKE '%NUTRITIONAL ASSESSMENT AND MONITORING ON FOOD AND NUTRITION%'
            ), 0)
            +
            COALESCE((
                SELECT SUM(amount) 
                FROM tbl_ors_indirect_mooe_dt i 
                JOIN tbl_ors_hd hd ON i.`project_id` = hd.`recid`
                WHERE i.sub_object_code = b.particulars 
                AND i.`program_title` LIKE '%NUTRITIONAL ASSESSMENT AND MONITORING ON FOOD AND NUTRITION%'
            ), 0) AS total_sub_month
        FROM tbl_saob_mooe_dt AS b
        JOIN
        tbl_saob_hd saob
        on b.project_id = saob.recid
        WHERE
        saob.project_title LIKE '%NUTRITIONAL ASSESSMENT AND MONITORING ON FOOD AND NUTRITION%'
    ) AS t;
");
$rw = $query->getRowArray();
$mooe_todate_grand_total = $rw['grand_total'];

//UP TO DATE CO GRAND TOTAL
$query = $this->db->query("
    SELECT 
        SUM(total_sub_month) AS grand_total
    FROM (
        SELECT
            COALESCE((
                SELECT SUM(amount) 
                FROM tbl_ors_direct_co_dt d 
                JOIN tbl_ors_hd hd ON d.`project_id` = hd.`recid`
                WHERE d.sub_object_code = b.particulars 
                AND d.`program_title` LIKE '%NUTRITIONAL ASSESSMENT AND MONITORING ON FOOD AND NUTRITION%'
            ), 0)
            +
            COALESCE((
                SELECT SUM(amount) 
                FROM tbl_ors_indirect_co_dt i 
                JOIN tbl_ors_hd hd ON i.`project_id` = hd.`recid`
                WHERE i.sub_object_code = b.particulars 
                AND i.`program_title` LIKE '%NUTRITIONAL ASSESSMENT AND MONITORING ON FOOD AND NUTRITION%'
            ), 0) AS total_sub_month
        FROM tbl_saob_co_dt AS b
        JOIN
        tbl_saob_hd saob
        on b.project_id = saob.recid
        WHERE
        saob.project_title LIKE '%NUTRITIONAL ASSESSMENT AND MONITORING ON FOOD AND NUTRITION%'
    ) AS t;
");
$rw = $query->getRowArray();
$co_todate_grand_total = $rw['grand_total'];

$todate_grand_total = $ps_todate_grand_total + $mooe_todate_grand_total + $co_todate_grand_total;
$grand_unobligated = $total_project_budget - $todate_grand_total;
if (!empty($total_project_budget) && $total_project_budget > 0) {
    $grand_percentage_minus = ($todate_grand_total / $total_project_budget) * 100;
}

$Y = $pdf->GetY()+3.5;
$query = $this->db->query("
    SELECT
        a.`program_title`,
        a.`project_title`,
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
    WHERE 
        a.`project_title` like '%NUTRITIONAL ASSESSMENT AND MONITORING ON FOOD AND NUTRITION%'
    ORDER BY a.`recid`
");
$hd_data = $query->getResultArray();
$total_all_sub_month = 0;
$total_all_todate_month = 0;
$ors_total_current_month = 0;

foreach ($hd_data as $hd_row) {
    $ps_current_month = 0;
    $mooe_current_month = 0;
    $co_current_month = 0;
    $ps_tolatest_month = 0;
    $mooe_tolatest_month = 0;
    $co_tolatest_month = 0;
    $total_unobligated = 0;
    $total_percentage_minus = 0;
    $program_title = $hd_row['program_title'];
    $project_title = $hd_row['project_title'];
    $recid = $hd_row['recid'];
    $total_ps = $hd_row['total_ps'];
    $total_mooe = $hd_row['total_mooe'];
    $total_co = $hd_row['total_co'];
    $total_approved_budget = $hd_row['total_approved_budget'];

    if ($project_title == "Nutritional Assessment and Monitoring on Food and Nutrition") {
        $program_tagging = 'NUTRIONAL ASSESSMENT AND MONITORING PROGRAM';
        $program_like = 'Nutritional Assessment and Monitoring on Food and Nutrition';
    }


    //PS--------------------------------------------
    $query = $this->db->query("
        SELECT
            u.allotment_class,
            b.object_code,
            b.particulars AS sub_object_code,
            b.code AS uacs_code,
            b.approved_budget,
            COALESCE((
                SELECT SUM(amount) 
                FROM tbl_ors_direct_ps_dt d 
                JOIN
                tbl_ors_hd hd ON d.`project_id` = hd.`recid`
                WHERE d.sub_object_code = b.particulars AND hd.`ors_date` >= '$date_from' AND hd.`ors_date` < '$date_to' AND d.`program_title` like '%$program_like%'
            ), 0) + COALESCE((
                SELECT SUM(amount) 
                FROM tbl_ors_indirect_ps_dt i 
                JOIN
                tbl_ors_hd hd ON i.`project_id` = hd.`recid`
                WHERE i.sub_object_code = b.particulars AND hd.`ors_date` >= '$date_from' AND hd.`ors_date` < '$date_to' AND i.`program_title` like '%$program_like%'
            ), 0) AS total_sub_month,
            COALESCE((
                SELECT SUM(amount) 
                FROM tbl_ors_direct_ps_dt d 
                JOIN tbl_ors_hd hd ON d.project_id = hd.recid
                WHERE d.sub_object_code = b.particulars 
                AND d.program_title LIKE '%$program_like%'
            ), 0)
            + COALESCE((
                SELECT SUM(amount) 
                FROM tbl_ors_indirect_ps_dt i 
                JOIN tbl_ors_hd hd ON i.project_id = hd.recid
                WHERE i.sub_object_code = b.particulars
                AND i.program_title LIKE '%$project_like%'
            ), 0) AS total_sub_all
        FROM 
            tbl_saob_ps_dt AS b
        LEFT JOIN 
            mst_uacs AS u ON b.code = u.uacs_code
        WHERE 
            b.project_id = '$recid'
        ORDER BY 
            b.recid, b.particulars;

    ");
    $ps_data = $query->getResultArray();
    //total ps object code fetching
    $ps_object_code_totals = [];
    $ps_sub_month_totals = [];
    $ps_sub_year_totals = [];
    $last_object_code = '';
    
    $ps_total_current_month = 0;
    $ps_total_todate_month = 0;
    foreach ($ps_data as $ps_row) {
        $object_code = $ps_row['object_code'];
        $sub_object_code = $ps_row['sub_object_code'];
        $approved_budget = floatval($ps_row['approved_budget']);
        $total_sub_month = floatval($ps_row['total_sub_month']);
        $total_sub_all = floatval($ps_row['total_sub_all']);

        if (!isset($ps_object_code_totals[$object_code])) {
            $ps_object_code_totals[$object_code] = 0;
        }
        $ps_object_code_totals[$object_code] += $approved_budget;

        if (!isset($ps_sub_month_totals[$object_code])) {
            $ps_sub_month_totals[$object_code] = 0;
        }
        $ps_sub_month_totals[$object_code] += $total_sub_month;

        if (!isset($ps_sub_year_totals[$object_code])) {
            $ps_sub_year_totals[$object_code] = 0;
        }
        $ps_sub_year_totals[$object_code] += $total_sub_all;

        $ps_total_current_month += $total_sub_month;
        $ps_total_todate_month += $total_sub_all;

        $ps_total_unobligated = ($total_ps ?? 0) - ($ps_total_todate_month ?? 0);
        
        
        if (!empty($total_ps) && $total_ps > 0) {
            $ps_percentage_minus = ($ps_total_todate_month / $total_ps) * 100;   
        }


    }

    $ps_current_month += $ps_total_current_month;
    $ps_tolatest_month += $ps_total_todate_month;

    //MOOE------------------------------------------
    $query = $this->db->query("
        SELECT
            u.allotment_class,
            b.object_code,
            b.particulars AS sub_object_code,
            b.code AS uacs_code,
            b.approved_budget,
            COALESCE((
                SELECT SUM(amount) 
                FROM tbl_ors_direct_mooe_dt d 
                JOIN
                tbl_ors_hd hd ON d.`project_id` = hd.`recid`
                WHERE d.sub_object_code = b.particulars AND hd.`ors_date` >= '$date_from' AND hd.`ors_date` < '$date_to' AND d.`program_title` like '%$program_like%'
            ), 0) + COALESCE((
                SELECT SUM(amount) 
                FROM tbl_ors_indirect_mooe_dt i 
                JOIN
                tbl_ors_hd hd ON i.`project_id` = hd.`recid`
                WHERE i.sub_object_code = b.particulars AND hd.`ors_date` >= '$date_from' AND hd.`ors_date` < '$date_to' AND i.`program_title` like '%$program_like%'
            ), 0) AS total_sub_month,
            COALESCE((
                SELECT SUM(amount) 
                FROM tbl_ors_direct_mooe_dt d 
                JOIN tbl_ors_hd hd ON d.project_id = hd.recid
                WHERE d.sub_object_code = b.particulars
                AND d.program_title LIKE '%$program_like%'
            ), 0)
            + COALESCE((
                SELECT SUM(amount) 
                FROM tbl_ors_indirect_mooe_dt i 
                JOIN tbl_ors_hd hd ON i.project_id = hd.recid
                WHERE i.sub_object_code = b.particulars
                AND i.program_title LIKE '%$program_like%'
            ), 0) AS total_sub_all
        FROM 
            tbl_saob_mooe_dt AS b
        LEFT JOIN 
            mst_uacs AS u ON b.code = u.uacs_code
        WHERE 
            b.project_id = '$recid'
        ORDER BY 
            b.recid, b.particulars;

    ");
    $mooe_data = $query->getResultArray();
    //total mooe object code fetching
    $mooe_object_code_totals = [];
    $mooe_sub_month_totals = [];
    $mooe_sub_year_totals = [];
    $last_object_code = '';
    $mooe_total_current_month = 0;
    $mooe_total_todate_month = 0;
    foreach ($mooe_data as $mooe_row) {
        $object_code = $mooe_row['object_code'];
        $sub_object_code = $mooe_row['sub_object_code'];
        $approved_budget = floatval($mooe_row['approved_budget']);
        $total_sub_month = floatval($mooe_row['total_sub_month']);
        $total_sub_all = floatval($mooe_row['total_sub_all']);

        if (!isset($mooe_object_code_totals[$object_code])) {
            $mooe_object_code_totals[$object_code] = 0;
        }
        $mooe_object_code_totals[$object_code] += $approved_budget;

        if (!isset($mooe_sub_month_totals[$object_code])) {
            $mooe_sub_month_totals[$object_code] = 0;
        }
        $mooe_sub_month_totals[$object_code] += $total_sub_month;

        if (!isset($mooe_sub_year_totals[$object_code])) {
            $mooe_sub_year_totals[$object_code] = 0;
        }
        $mooe_sub_year_totals[$object_code] += $total_sub_all;

        $mooe_total_current_month += $total_sub_month;
        $mooe_total_todate_month += $total_sub_all;

        $mooe_total_unobligated = ($total_mooe ?? 0) - ($mooe_total_todate_month ?? 0);
        
        
        if (!empty($total_mooe) && $total_mooe > 0) {
            $mooe_percentage_minus = ($mooe_total_todate_month / $total_mooe) * 100;
        }
          
    }
    $mooe_current_month += $mooe_total_current_month;
    $mooe_tolatest_month += $mooe_total_todate_month;

    //CO--------------------------------------------
    $query = $this->db->query("
        SELECT
            u.allotment_class,
            b.object_code,
            b.particulars AS sub_object_code,
            b.code AS uacs_code,
            b.approved_budget,
            COALESCE((
                SELECT SUM(amount) 
                FROM tbl_ors_direct_co_dt d 
                JOIN
                tbl_ors_hd hd ON d.`project_id` = hd.`recid`
                WHERE d.sub_object_code = b.particulars AND hd.`ors_date` >= '$date_from' AND hd.`ors_date` < '$date_to' AND d.`program_title` like '%$program_like%'
            ), 0) + COALESCE((
                SELECT SUM(amount) 
                FROM tbl_ors_indirect_co_dt i 
                JOIN
                tbl_ors_hd hd ON i.`project_id` = hd.`recid`
                WHERE i.sub_object_code = b.particulars AND hd.`ors_date` >= '$date_from' AND hd.`ors_date` < '$date_to' AND i.`program_title` like '%$program_like%'
            ), 0) AS total_sub_month,
            COALESCE((
                SELECT SUM(amount) 
                FROM tbl_ors_direct_co_dt d 
                JOIN tbl_ors_hd hd ON d.project_id = hd.recid
                WHERE d.sub_object_code = b.particulars
                AND d.program_title LIKE '%$program_like%'
            ), 0)
            + COALESCE((
                SELECT SUM(amount) 
                FROM tbl_ors_indirect_co_dt i 
                JOIN tbl_ors_hd hd ON i.project_id = hd.recid
                WHERE i.sub_object_code = b.particulars
                AND i.program_title LIKE '%$program_like%'
            ), 0) AS total_sub_all
        FROM 
            tbl_saob_co_dt AS b
        LEFT JOIN 
            mst_uacs AS u ON b.code = u.uacs_code
        WHERE 
            b.project_id = '$recid'
        ORDER BY 
            b.recid, b.particulars;

    ");
    $co_data = $query->getResultArray();
    //total co object code fetching
    $co_object_code_totals = [];
    $co_sub_month_totals = [];
    $co_sub_year_totals = [];
    $last_object_code = '';
    $co_total_current_month = 0;
    $co_total_todate_month = 0;
    foreach ($co_data as $co_row) {
        $object_code = $co_row['object_code'];
        $sub_object_code = $co_row['sub_object_code'];
        $approved_budget = floatval($co_row['approved_budget']);
        $total_sub_month = floatval($co_row['total_sub_month']);
        $total_sub_all = floatval($co_row['total_sub_all']);

        if (!isset($co_object_code_totals[$object_code])) {
            $co_object_code_totals[$object_code] = 0;
        }
        $co_object_code_totals[$object_code] += $approved_budget;

        if (!isset($co_sub_month_totals[$object_code])) {
            $co_sub_month_totals[$object_code] = 0;
        }
        $co_sub_month_totals[$object_code] += $total_sub_month;

        if (!isset($co_sub_year_totals[$object_code])) {
            $co_sub_year_totals[$object_code] = 0;
        }
        $co_sub_year_totals[$object_code] += $total_sub_all;

        $co_total_current_month += $total_sub_month;
        $co_total_todate_month += $total_sub_all;

        $co_total_unobligated = ($total_co ?? 0) - ($co_total_todate_month ?? 0);
        
        
        if (!empty($total_co) && $total_co > 0) {
            $co_percentage_minus = ($co_total_todate_month / $total_co) * 100;
        }
          
    }

    $co_current_month += $co_total_current_month;
    $co_tolatest_month += $co_total_todate_month;

    $total_all_sub_month = $ps_current_month + $mooe_current_month + $co_current_month;
    $total_all_todate_month = $ps_tolatest_month + $mooe_tolatest_month + $co_tolatest_month;
    $total_unobligated = $total_approved_budget - $total_all_todate_month;
    if (!empty($total_approved_budget) && $total_approved_budget > 0) {
        $total_percentage_minus = ($total_all_todate_month / $total_approved_budget) * 100;
    }

    $Y = $pdf->GetY();
    // PROGRAM TAGGING LOGIC --------------------------------------------------------------------
    $startY = $Y; // Store the starting Y position

    // First, measure the height needed for program_tagging
    $pdf->SetXY(15, $Y);
    $pdf->SetFont('Arial', '', 8);
    $pdf->MultiCell(70, 3.5, $program_tagging, 0, 'L'); // Measure without border
    $afterProgramY = $pdf->GetY();
    $programHeight = $afterProgramY - $Y;

    // Draw complete PROGRAM TAGGING row with ALL borders
    $pdf->SetXY(10, $Y);
    $pdf->Cell(5, $programHeight, '', 'L', 0, 'C'); // First column

    $pdf->SetXY(15, $Y);
    $pdf->Cell(70, $programHeight, '', 0, 0, 'L'); // Program text column

    // Right-side columns for program tagging
    $pdf->SetXY(85, $Y);
    $pdf->Cell(11, $programHeight, '', 0, 0, 'C'); // UACS column

    $pdf->SetXY(96, $Y);
    $pdf->Cell(22, $programHeight, '', 1, 0, 'C'); // Budget column

    $pdf->SetXY(118, $Y);
    $pdf->Cell(22, $programHeight, '', 1, 0, 'C'); // This month column

    $pdf->SetXY(140, $Y);
    $pdf->Cell(22, $programHeight, '', 1, 0, 'C'); // To date column

    $pdf->SetXY(162, $Y);
    $pdf->Cell(22, $programHeight, '', 1, 0, 'C'); // Unobligated column

    $pdf->SetXY(184, $Y);
    $pdf->Cell(17, $programHeight, '', 1, 0, 'C'); // Percentage column

    // Draw program tagging totals (centered vertically)
    $programMiddleY = $Y + ($programHeight / 2) - 1.5;
    $pdf->SetXY(96, $programMiddleY);
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(22, 3, number_format($total_project_budget, 2), 0, 0, 'C');

    $pdf->SetXY(118, $programMiddleY);
    $pdf->Cell(22, 3, number_format($thismonth_grand_total, 2), 0, 0, 'C');

    $pdf->SetXY(140, $programMiddleY);
    $pdf->Cell(22, 3, number_format($todate_grand_total, 2), 0, 0, 'C');

    $pdf->SetXY(162, $programMiddleY);
    $pdf->Cell(22, 3, number_format($grand_unobligated, 2), 0, 0, 'C');

    $pdf->SetXY(184, $programMiddleY);
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(17, 3, number_format($grand_percentage_minus, 2) . '%', 0, 0, 'C');

    // Draw program tagging text
    $pdf->SetXY(15, $Y + 1);
    $pdf->SetFont('Arial', '', 8);
    $pdf->MultiCell(70, 3.5, '', 0, 'L');

    // PROJECT TITLE LOGIC --------------------------------------------------------------------
    $Y = $afterProgramY; // Move Y to after program tagging
    $projectStartY = $Y;

    // Measure the height needed for project_title
    $pdf->SetXY(15, $Y);
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->MultiCell(70, 3.5, $project_title, 0, 'L'); // Measure without border
    $afterProjectY = $pdf->GetY();
    $projectHeight = $afterProjectY - $Y;

    // Draw complete PROJECT TITLE row with ALL borders
    $pdf->SetXY(10, $Y);
    $pdf->Cell(5, $projectHeight, '', 'L', 0, 'C'); // First column

    $pdf->SetXY(15, $Y);
    $pdf->Cell(70, $projectHeight, '', 0, 0, 'L'); // Project text column

    // Right-side columns for project title
    $pdf->SetXY(85, $Y);
    $pdf->Cell(11, $projectHeight, '', 0, 0, 'C'); // UACS column

    $pdf->SetXY(96, $Y);
    $pdf->Cell(22, $projectHeight, '', 1, 0, 'C'); // Budget column

    $pdf->SetXY(118, $Y);
    $pdf->Cell(22, $projectHeight, '', 1, 0, 'C'); // This month column

    $pdf->SetXY(140, $Y);
    $pdf->Cell(22, $projectHeight, '', 1, 0, 'C'); // To date column

    $pdf->SetXY(162, $Y);
    $pdf->Cell(22, $projectHeight, '', 1, 0, 'C'); // Unobligated column

    $pdf->SetXY(184, $Y);
    $pdf->Cell(17, $projectHeight, '', 1, 0, 'C'); // Percentage column

    // Draw project title totals (centered vertically)
    $projectMiddleY = $Y + ($projectHeight / 2) - 1.5;
    $pdf->SetXY(96, $projectMiddleY);
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(22, 3, number_format($total_approved_budget, 2), 0, 0, 'C');

    $pdf->SetXY(118, $projectMiddleY);
    $pdf->Cell(22, 3, number_format($total_all_sub_month, 2), 0, 0, 'C');

    $pdf->SetXY(140, $projectMiddleY);
    $pdf->Cell(22, 3, number_format($total_all_todate_month, 2), 0, 0, 'C');

    $pdf->SetXY(162, $projectMiddleY);
    $pdf->Cell(22, 3, number_format($total_unobligated, 2), 0, 0, 'C');

    $pdf->SetXY(184, $projectMiddleY);
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(17, 3, number_format($total_percentage_minus, 2) . '%', 0, 0, 'C');

    // Draw project title text
    $pdf->SetXY(15, $Y + 1);
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->MultiCell(70, 3.5, '', 0, 'L');

    // Update Y position for next content
    $Y = $afterProjectY;

    // Clean up and prepare for next section
    $last_allotment_class = '';
    $last_sub_object_code = '';
    $last_object_code = '';

    $Y = $pdf->GetY()+2.5; // Add some spacing before the next section

    foreach ($ps_data as $ps_row) {
        $allotment_class = $ps_row['allotment_class'];
        $object_code = $ps_row['object_code'];
        $sub_object_code = $ps_row['sub_object_code'];
        $uacs_code = $ps_row['uacs_code'];
        $approved_budget = $ps_row['approved_budget'];

        $query = $this->db->query("
            SELECT COALESCE(SUM(amount),0) AS total_amount  
            FROM (
                SELECT dps.`amount` FROM tbl_ors_direct_ps_dt dps JOIN tbl_ors_hd hd on dps.`project_id` = hd.`recid` WHERE dps.`sub_object_code` = '$sub_object_code' AND hd.`ors_date` >= '$date_from' AND hd.`ors_date` < '$date_to' AND dps.`program_title` like '%$program_like%'
                UNION ALL
                SELECT idps.`amount` FROM tbl_ors_indirect_ps_dt idps JOIN tbl_ors_hd hd on idps.`project_id` = hd.`recid` WHERE idps.`sub_object_code` = '$sub_object_code' AND hd.`ors_date` >= '$date_from' AND hd.`ors_date` < '$date_to' AND idps.`program_title` like '%$program_like%'
            ) AS combined
        ");

        $result = $query->getRowArray();
        $month_amount = $result['total_amount'];

        $query = $this->db->query("
            SELECT COALESCE(SUM(amount),0) AS total_amount  
            FROM (
                SELECT dps.`amount` FROM tbl_ors_direct_ps_dt dps JOIN tbl_ors_hd hd on dps.`project_id` = hd.`recid` WHERE dps.`sub_object_code` = '$sub_object_code'  AND dps.`program_title` like '%$program_like%'
                UNION ALL
                SELECT idps.`amount` FROM tbl_ors_indirect_ps_dt idps JOIN tbl_ors_hd hd on idps.`project_id` = hd.`recid` WHERE idps.`sub_object_code` = '$sub_object_code' AND idps.`program_title` like '%$program_like%'
            ) AS combined
        ");

        $result = $query->getRowArray();
        $year_amount = $result['total_amount'];

        if ($Y > 265) {
            $pdf->AddPage();
            $Y = $pdf->GetY();
            $pdf->SetXY(10, $Y);
            $pdf->SetFont('Arial', 'B', 7);
            $pdf->Cell(64, 3.5, '', 'TRL', 0, 'C');//ROW 1
            $pdf->Cell(22, 3.5, '', 'TRL', 0, 'C');
            $pdf->Cell(22, 3.5, '', 'TRL', 0, 'C');
            $pdf->Cell(44, 3.5, '', 'TRL', 0, 'C');
            $pdf->Cell(22, 3.5, '', 'TRL', 0, 'C');
            $pdf->Cell(17, 3.5, '', 'TRL', 0, 'C');
            $Y+= 3.5;

            $pdf->SetXY(10, $Y);
            $pdf->Cell(64, 3.5, '', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, '', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, '', 'RL', 0, 'C');
            $pdf->Cell(44, 3.5, 'Obligation Incurred', 'BRL', 0, 'C');
            $pdf->Cell(22, 3.5, 'Unobligated', 'RL', 0, 'C');
            $pdf->Cell(17, 3.5, '', 'RL', 0, 'C');
            $Y+= 3.5;

            $pdf->SetXY(10, $Y);
            $pdf->Cell(64, 3.5, 'OBJECT OF EXPENDITURE', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, '', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, 'Revised', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, 'This month', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, 'Obligation', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, 'Balance of', 'RL', 0, 'C');
            $pdf->Cell(17, 3.5, 'Percent', 'RL', 0, 'C');
            $Y+= 3.5;

            $pdf->SetXY(10, $Y);
            $pdf->Cell(64, 3.5, '', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, 'UACS', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, 'Allotment', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, $month, 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, 'To Date', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, 'Allotment', 'RL', 0, 'C');
            $pdf->Cell(17, 3.5, 'Of', 'RL', 0, 'C');
            $Y+= 3.5;

            $pdf->SetXY(10, $Y);
            $pdf->Cell(64, 3.5, '', 'BRL', 0, 'C');
            $pdf->Cell(22, 3.5, '(1)', 'BRL', 0, 'C');
            $pdf->Cell(22, 3.5, '(4)', 'BRL', 0, 'C');
            $pdf->Cell(22, 3.5, '(5)', 'BRL', 0, 'C');
            $pdf->Cell(22, 3.5, '(6)', 'BRL', 0, 'C');
            $pdf->Cell(22, 3.5, '(7)=(4)-(6)', 'BRL', 0, 'C');
            $pdf->Cell(17, 3.5, 'Utilization', 'BRL', 0, 'C');

            $Y = $pdf->GetY()+3.5;
            // $pdf->SetXY(10, $Y);
            // $pdf->Cell(64, 215.5, '', 1, 0); 
            // $pdf->SetXY(74, $Y);
            // $pdf->Cell(22, 215.5, '', 1, 0); 
            // $pdf->SetXY(96, $Y);
            // $pdf->Cell(22, 215.5, '', 1, 0); 
            // $pdf->SetXY(118, $Y);
            // $pdf->Cell(22, 215.5, '', 1, 0); 
            // $pdf->SetXY(140, $Y);
            // $pdf->Cell(22, 215.5, '', 1, 0); 
            // $pdf->SetXY(162, $Y);
            // $pdf->Cell(22, 215.5, '', 1, 0); 
            // $pdf->SetXY(184, $Y);
            // $pdf->Cell(17, 215.5, '', 1, 0); 
        }

        // ================= ALLOTMENT CLASS ===================
        if ($allotment_class !== $last_allotment_class && $allotment_class !== null) {
            $pdf->SetFont('Arial', '', 8);

            // Save startY
            $startY = $Y;

            // Print text with wrapping
            $pdf->SetXY(10, $Y);
            $pdf->MultiCell(64, 4.5, $allotment_class, 0, 'L');

            // Measure row height
            $endY = $pdf->GetY();
            $totalRowHeight = $endY - $startY;

            // Draw borders
            $pdf->SetXY(10, $startY); $pdf->Cell(64, $totalRowHeight, '', 'L', 0, 'L');
            $pdf->SetXY(74, $startY); $pdf->Cell(22, $totalRowHeight, '', 0, 0, 'C');
            $pdf->SetXY(96, $startY); $pdf->Cell(22, $totalRowHeight, '', 0, 0, 'C');
            $pdf->SetXY(118, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
            $pdf->SetXY(140, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
            $pdf->SetXY(162, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
            $pdf->SetXY(184, $startY); $pdf->Cell(17, $totalRowHeight, '', 1, 0, 'C');

            // Center values vertically
            $middleY = $startY + ($totalRowHeight / 2) - 2.5;
            $pdf->SetFont('Arial', 'B', 8);

            $pdf->SetXY(96, $middleY); $pdf->Cell(22, 5, number_format($total_ps, 2), 1, 0, 'C');
            $pdf->SetXY(118, $middleY); $pdf->Cell(22, 5, number_format($ps_total_current_month, 2), 0, 0, 'C');
            $pdf->SetXY(140, $middleY); $pdf->Cell(22, 5, number_format($ps_total_todate_month, 2), 0, 0, 'C');
            $pdf->SetXY(162, $middleY); $pdf->Cell(22, 5, number_format($ps_total_unobligated, 2), 0, 0, 'C');
            $pdf->SetXY(184, $middleY); $pdf->Cell(17, 5, number_format($ps_percentage_minus, 2) . '%', 0, 0, 'C');

            $Y = $endY;
            $last_allotment_class = $allotment_class;
        }

        // ================= OBJECT CODE ===================
        if ($object_code !== $last_object_code && $object_code !== null) {
            $pdf->SetFont('Arial', '', 8);

            // Object Code: Start at X=15 (no blank cell before)
            $pdf->SetXY(10, $Y);
            $pdf->Cell(5, 4.5, '', 'L', 1, 'C');
            $pdf->SetXY(15, $Y);
            $pdf->MultiCell(59, 4.5, $object_code, 0, 'L');

            // Totals
            $total_for_object_code = $ps_object_code_totals[$object_code] ?? 0;
            $total_sub_month_amount = $ps_sub_month_totals[$object_code] ?? 0;
            $total_sub_year_amount = $ps_sub_year_totals[$object_code] ?? 0;
            $total_sub_unobligated = ($total_for_object_code ?? 0) - ($total_sub_year_amount ?? 0);

            // Columns
            $pdf->SetXY(96, $Y);
            $pdf->Cell(21.8, 4.5, number_format($total_for_object_code, 2), 1, 1, 'C');
            $pdf->SetXY(118, $Y);
            $pdf->Cell(21.8, 4.5, number_format($total_sub_month_amount, 2), 1, 1, 'C');
            $pdf->SetXY(140, $Y);
            $pdf->Cell(21.8, 4.5, number_format($total_sub_year_amount, 2), 1, 1, 'C');
            $pdf->SetXY(162, $Y);
            $pdf->Cell(21.8, 4.5, number_format($total_sub_unobligated, 2), 1, 1, 'C');
            $pdf->SetXY(184, $Y);
            $pdf->Cell(17, 4.5, '', 'R', 1, 'C');

            // Move down
            $Y += 4.5;
            $last_object_code = $object_code;
        }

        // ================= SUB OBJECT CODE ===================
        if ($sub_object_code !== $last_sub_object_code && $sub_object_code !== null) {
            $pdf->SetFont('Arial', '', 8);

            $startY = $Y;
            $pdf->SetXY(20, $Y);
            $pdf->MultiCell(55, 4.5, $sub_object_code, 0, 'L');

            $endY = $pdf->GetY();
            $totalRowHeight = $endY - $startY;

            if ($Y >260) {
                // Borders
                $pdf->SetXY(10, $startY); $pdf->Cell(64, $totalRowHeight, '', 'LB', 0, 'L');
                $pdf->SetXY(74, $startY); $pdf->Cell(22, $totalRowHeight, '', 'B', 0, 'C');
                $pdf->SetXY(96, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
                $pdf->SetXY(118, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
                $pdf->SetXY(140, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
                $pdf->SetXY(162, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
                $pdf->SetXY(184, $startY); $pdf->Cell(17, $totalRowHeight, '', 'RB', 0, 'C');
            }else{
                $pdf->SetXY(10, $startY); $pdf->Cell(64, $totalRowHeight, '', 'L', 0, 'L');
                $pdf->SetXY(74, $startY); $pdf->Cell(22, $totalRowHeight, '', 0, 0, 'C');
                $pdf->SetXY(96, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
                $pdf->SetXY(118, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
                $pdf->SetXY(140, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
                $pdf->SetXY(162, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
                $pdf->SetXY(184, $startY); $pdf->Cell(17, $totalRowHeight, '', 'R', 0, 'C');
            }

            // Center values
            $middleY = $startY + ($totalRowHeight / 2) - 2.5;

            $pdf->SetXY(74, $middleY); $pdf->Cell(22, 5, $uacs_code, 0, 0, 'C');
            $pdf->SetXY(96, $middleY); $pdf->Cell(22, 5, number_format($approved_budget,2), 0, 0, 'C');
            $pdf->SetXY(118, $middleY); $pdf->Cell(22, 5, $month_amount, 0, 0, 'C');
            $pdf->SetXY(140, $middleY); $pdf->Cell(22, 5, $year_amount, 0, 0, 'C');

            $subobligated_amount = $approved_budget - $year_amount;
            $pdf->SetXY(162, $middleY); $pdf->Cell(22, 5, number_format($subobligated_amount,2), 0, 0, 'C');
            $pdf->SetXY(184, $middleY); $pdf->Cell(17, 5, '', 0, 0, 'C');

            $Y = $endY;
            $last_sub_object_code = $sub_object_code;
        }

    }

    $last_allotment_class = '';
    $last_sub_object_code = '';
    $last_object_code = '';

    $Y = $pdf->GetY() +4.5;
    foreach ($mooe_data as $mooe_row) {
        $allotment_class = $mooe_row['allotment_class'];
        $object_code = $mooe_row['object_code'];
        $sub_object_code = $mooe_row['sub_object_code'];
        $uacs_code = $mooe_row['uacs_code'];
        $approved_budget = $mooe_row['approved_budget'];

        $query = $this->db->query("
            SELECT COALESCE(SUM(amount),0) AS total_amount  
            FROM (
                SELECT dmooe.`amount` FROM tbl_ors_direct_mooe_dt dmooe JOIN tbl_ors_hd hd on dmooe.`project_id` = hd.`recid` WHERE dmooe.`sub_object_code` = '$sub_object_code' AND hd.`ors_date` >= '$date_from' AND hd.`ors_date` < '$date_to' AND dmooe.`program_title` like '%$program_like%'
                UNION ALL
                SELECT idmooe.`amount` FROM tbl_ors_indirect_mooe_dt idmooe JOIN tbl_ors_hd hd on idmooe.`project_id` = hd.`recid` WHERE idmooe.`sub_object_code` = '$sub_object_code' AND hd.`ors_date` >= '$date_from' AND hd.`ors_date` < '$date_to' AND idmooe.`program_title` like '%$program_like%'
            ) AS combined
        ");

        $result = $query->getRowArray();
        $month_amount = $result['total_amount'];

        $query = $this->db->query("
            SELECT COALESCE(SUM(amount),0) AS total_amount  
            FROM (
                SELECT dmooe.`amount` FROM tbl_ors_direct_mooe_dt dmooe JOIN tbl_ors_hd hd on dmooe.`project_id` = hd.`recid` WHERE dmooe.`sub_object_code` = '$sub_object_code' AND dmooe.`program_title` like '%$program_like%'
                UNION ALL
                SELECT idmooe.`amount` FROM tbl_ors_indirect_mooe_dt idmooe JOIN tbl_ors_hd hd on idmooe.`project_id` = hd.`recid` WHERE idmooe.`sub_object_code` = '$sub_object_code' AND idmooe.`program_title` like '%$program_like%'
            ) AS combined
        ");

        $result = $query->getRowArray();
        $year_amount = $result['total_amount'];

        if ($Y > 265) {
            $pdf->AddPage();
            $Y = $pdf->GetY();
            $pdf->SetXY(10, $Y);
            $pdf->SetFont('Arial', 'B', 7);
            $pdf->Cell(64, 3.5, '', 'TRL', 0, 'C');//ROW 1
            $pdf->Cell(22, 3.5, '', 'TRL', 0, 'C');
            $pdf->Cell(22, 3.5, '', 'TRL', 0, 'C');
            $pdf->Cell(44, 3.5, '', 'TRL', 0, 'C');
            $pdf->Cell(22, 3.5, '', 'TRL', 0, 'C');
            $pdf->Cell(17, 3.5, '', 'TRL', 0, 'C');
            $Y+= 3.5;

            $pdf->SetXY(10, $Y);
            $pdf->Cell(64, 3.5, '', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, '', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, '', 'RL', 0, 'C');
            $pdf->Cell(44, 3.5, 'Obligation Incurred', 'BRL', 0, 'C');
            $pdf->Cell(22, 3.5, 'Unobligated', 'RL', 0, 'C');
            $pdf->Cell(17, 3.5, '', 'RL', 0, 'C');
            $Y+= 3.5;

            $pdf->SetXY(10, $Y);
            $pdf->Cell(64, 3.5, 'OBJECT OF EXPENDITURE', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, '', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, 'Revised', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, 'This month', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, 'Obligation', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, 'Balance of', 'RL', 0, 'C');
            $pdf->Cell(17, 3.5, 'Percent', 'RL', 0, 'C');
            $Y+= 3.5;

            $pdf->SetXY(10, $Y);
            $pdf->Cell(64, 3.5, '', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, 'UACS', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, 'Allotment', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, $month, 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, 'To Date', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, 'Allotment', 'RL', 0, 'C');
            $pdf->Cell(17, 3.5, 'Of', 'RL', 0, 'C');
            $Y+= 3.5;

            $pdf->SetXY(10, $Y);
            $pdf->Cell(64, 3.5, '', 'BRL', 0, 'C');
            $pdf->Cell(22, 3.5, '(1)', 'BRL', 0, 'C');
            $pdf->Cell(22, 3.5, '(4)', 'BRL', 0, 'C');
            $pdf->Cell(22, 3.5, '(5)', 'BRL', 0, 'C');
            $pdf->Cell(22, 3.5, '(6)', 'BRL', 0, 'C');
            $pdf->Cell(22, 3.5, '(7)=(4)-(6)', 'BRL', 0, 'C');
            $pdf->Cell(17, 3.5, 'Utilization', 'BRL', 0, 'C');

            $Y = $pdf->GetY()+3.5;
            // $pdf->SetXY(10, $Y);
            // $pdf->Cell(64, 215.5, '', 1, 0); 
            // $pdf->SetXY(74, $Y);
            // $pdf->Cell(22, 215.5, '', 1, 0); 
            // $pdf->SetXY(96, $Y);
            // $pdf->Cell(22, 215.5, '', 1, 0); 
            // $pdf->SetXY(118, $Y);
            // $pdf->Cell(22, 215.5, '', 1, 0); 
            // $pdf->SetXY(140, $Y);
            // $pdf->Cell(22, 215.5, '', 1, 0); 
            // $pdf->SetXY(162, $Y);
            // $pdf->Cell(22, 215.5, '', 1, 0); 
            // $pdf->SetXY(184, $Y);
            // $pdf->Cell(17, 215.5, '', 1, 0); 
        }

        // ================= ALLOTMENT CLASS ===================
        if ($allotment_class !== $last_allotment_class && $allotment_class !== null) {
            $pdf->SetFont('Arial', '', 8);

            // Save startY
            $startY = $Y;

            // Print text with wrapping
            $pdf->SetXY(10, $Y);
            $pdf->MultiCell(64, 4.5, $allotment_class, 0, 'L');

            // Measure row height
            $endY = $pdf->GetY();
            $totalRowHeight = $endY - $startY;

            // Draw borders
            $pdf->SetXY(10, $startY); $pdf->Cell(64, $totalRowHeight, '', 'L', 0, 'L');
            $pdf->SetXY(74, $startY); $pdf->Cell(22, $totalRowHeight, '', 0, 0, 'C');
            $pdf->SetXY(96, $startY); $pdf->Cell(22, $totalRowHeight, '', 0, 0, 'C');
            $pdf->SetXY(118, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
            $pdf->SetXY(140, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
            $pdf->SetXY(162, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
            $pdf->SetXY(184, $startY); $pdf->Cell(17, $totalRowHeight, '', 1, 0, 'C');

            // Center values vertically
            $middleY = $startY + ($totalRowHeight / 2) - 2.5;
            $pdf->SetFont('Arial', 'B', 8);

            $pdf->SetXY(96, $middleY); $pdf->Cell(22, 5, number_format($total_mooe, 2), 1, 0, 'C');
            $pdf->SetXY(118, $middleY); $pdf->Cell(22, 5, number_format($mooe_total_current_month, 2), 0, 0, 'C');
            $pdf->SetXY(140, $middleY); $pdf->Cell(22, 5, number_format($mooe_total_todate_month, 2), 0, 0, 'C');
            $pdf->SetXY(162, $middleY); $pdf->Cell(22, 5, number_format($mooe_total_unobligated, 2), 0, 0, 'C');
            $pdf->SetXY(184, $middleY); $pdf->Cell(17, 5, number_format($mooe_percentage_minus, 2) . '%', 0, 0, 'C');

            $Y = $endY;
            $last_allotment_class = $allotment_class;
        }

        // ================= OBJECT CODE ===================
        if ($object_code !== $last_object_code && $object_code !== null) {
            $pdf->SetFont('Arial', '', 8);

            // Object Code: Start at X=15 (no blank cell before)
            $pdf->SetXY(10, $Y);
            $pdf->Cell(5, 4.5, '', 'L', 1, 'C');
            $pdf->SetXY(15, $Y);
            $pdf->MultiCell(59, 4.5, $object_code, 0, 'L');

            // Totals
            $total_for_object_code = $mooe_object_code_totals[$object_code] ?? 0;
            $total_sub_month_amount = $mooe_sub_month_totals[$object_code] ?? 0;
            $total_sub_year_amount = $mooe_sub_year_totals[$object_code] ?? 0;
            $total_sub_unobligated = ($total_for_object_code ?? 0) - ($total_sub_year_amount ?? 0);

            // Columns
            $pdf->SetXY(96, $Y);
            $pdf->Cell(21.8, 4.5, number_format($total_for_object_code, 2), 1, 1, 'C');
            $pdf->SetXY(118, $Y);
            $pdf->Cell(21.8, 4.5, number_format($total_sub_month_amount, 2), 1, 1, 'C');
            $pdf->SetXY(140, $Y);
            $pdf->Cell(21.8, 4.5, number_format($total_sub_year_amount, 2), 1, 1, 'C');
            $pdf->SetXY(162, $Y);
            $pdf->Cell(21.8, 4.5, number_format($total_sub_unobligated, 2), 1, 1, 'C');
            $pdf->SetXY(184, $Y);
            $pdf->Cell(17, 4.5, '', 'R', 1, 'C');

            // Move down
            $Y += 4.5;
            $last_object_code = $object_code;
        }

        // ================= SUB OBJECT CODE ===================
        if ($sub_object_code !== $last_sub_object_code && $sub_object_code !== null) {
            $pdf->SetFont('Arial', '', 8);

            $startY = $Y;
            $pdf->SetXY(20, $Y);
            $pdf->MultiCell(55, 4.5, $sub_object_code, 0, 'L');

            $endY = $pdf->GetY();
            $totalRowHeight = $endY - $startY;

            if ($Y >255) {
                // Borders
                $pdf->SetXY(10, $startY); $pdf->Cell(64, $totalRowHeight, '', 'LB', 0, 'L');
                $pdf->SetXY(74, $startY); $pdf->Cell(22, $totalRowHeight, '', 'B', 0, 'C');
                $pdf->SetXY(96, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
                $pdf->SetXY(118, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
                $pdf->SetXY(140, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
                $pdf->SetXY(162, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
                $pdf->SetXY(184, $startY); $pdf->Cell(17, $totalRowHeight, '', 'RB', 0, 'C');
            }else{
                $pdf->SetXY(10, $startY); $pdf->Cell(64, $totalRowHeight, '', 'L', 0, 'L');
                $pdf->SetXY(74, $startY); $pdf->Cell(22, $totalRowHeight, '', 0, 0, 'C');
                $pdf->SetXY(96, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
                $pdf->SetXY(118, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
                $pdf->SetXY(140, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
                $pdf->SetXY(162, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
                $pdf->SetXY(184, $startY); $pdf->Cell(17, $totalRowHeight, '', 'R', 0, 'C');
            }

            // Center values
            $middleY = $startY + ($totalRowHeight / 2) - 2.5;

            $pdf->SetXY(74, $middleY); $pdf->Cell(22, 5, $uacs_code, 0, 0, 'C');
            $pdf->SetXY(96, $middleY); $pdf->Cell(22, 5, number_format($approved_budget,2), 0, 0, 'C');
            $pdf->SetXY(118, $middleY); $pdf->Cell(22, 5, $month_amount, 0, 0, 'C');
            $pdf->SetXY(140, $middleY); $pdf->Cell(22, 5, $year_amount, 0, 0, 'C');

            $subobligated_amount = $approved_budget - $year_amount;
            $pdf->SetXY(162, $middleY); $pdf->Cell(22, 5, number_format($subobligated_amount,2), 0, 0, 'C');
            $pdf->SetXY(184, $middleY); $pdf->Cell(17, 5, '', 0, 0, 'C');

            $Y = $endY;
            $last_sub_object_code = $sub_object_code;
        }

    }

    $last_allotment_class = '';
    $last_sub_object_code = '';
    $last_object_code = '';

    $Y = $pdf->GetY();
    foreach ($co_data as $co_row) {
        $allotment_class = $co_row['allotment_class'];
        $object_code = $co_row['object_code'];
        $sub_object_code = $co_row['sub_object_code'];
        $uacs_code = $co_row['uacs_code'];
        $approved_budget = $co_row['approved_budget'];

        $query = $this->db->query("
            SELECT COALESCE(SUM(amount),0) AS total_amount  
            FROM (
                SELECT dco.`amount` FROM tbl_ors_direct_co_dt dco JOIN tbl_ors_hd hd on dco.`project_id` = hd.`recid` WHERE dco.`sub_object_code` = '$sub_object_code' AND hd.`ors_date` >= '$date_from' AND hd.`ors_date` < '$date_to' AND dco.`program_title` like '%$program_like%'
                UNION ALL
                SELECT idco.`amount` FROM tbl_ors_indirect_co_dt idco JOIN tbl_ors_hd hd on idco.`project_id` = hd.`recid` WHERE idco.`sub_object_code` = '$sub_object_code' AND hd.`ors_date` >= '$date_from' AND hd.`ors_date` < '$date_to' AND idco.`program_title` like '%$program_like%'
            ) AS combined
        ");

        $result = $query->getRowArray();
        $month_amount = $result['total_amount'];

        $query = $this->db->query("
            SELECT COALESCE(SUM(amount),0) AS total_amount  
            FROM (
                SELECT dco.`amount` FROM tbl_ors_direct_co_dt dco JOIN tbl_ors_hd hd on dco.`project_id` = hd.`recid` WHERE dco.`sub_object_code` = '$sub_object_code' AND dco.`program_title` like '%$program_like%'
                UNION ALL
                SELECT idco.`amount` FROM tbl_ors_indirect_co_dt idco JOIN tbl_ors_hd hd on idco.`project_id` = hd.`recid` WHERE idco.`sub_object_code` = '$sub_object_code' AND idco.`program_title` like '%$program_like%'
            ) AS combined
        ");

        $result = $query->getRowArray();
        $year_amount = $result['total_amount'];

        if ($Y > 265) {
            $pdf->AddPage();
            $Y = $pdf->GetY();
            $pdf->SetXY(10, $Y);
            $pdf->SetFont('Arial', 'B', 7);
            $pdf->Cell(64, 3.5, '', 'TRL', 0, 'C');//ROW 1
            $pdf->Cell(22, 3.5, '', 'TRL', 0, 'C');
            $pdf->Cell(22, 3.5, '', 'TRL', 0, 'C');
            $pdf->Cell(44, 3.5, '', 'TRL', 0, 'C');
            $pdf->Cell(22, 3.5, '', 'TRL', 0, 'C');
            $pdf->Cell(17, 3.5, '', 'TRL', 0, 'C');
            $Y+= 3.5;

            $pdf->SetXY(10, $Y);
            $pdf->Cell(64, 3.5, '', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, '', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, '', 'RL', 0, 'C');
            $pdf->Cell(44, 3.5, 'Obligation Incurred', 'BRL', 0, 'C');
            $pdf->Cell(22, 3.5, 'Unobligated', 'RL', 0, 'C');
            $pdf->Cell(17, 3.5, '', 'RL', 0, 'C');
            $Y+= 3.5;

            $pdf->SetXY(10, $Y);
            $pdf->Cell(64, 3.5, 'OBJECT OF EXPENDITURE', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, '', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, 'Revised', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, 'This month', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, 'Obligation', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, 'Balance of', 'RL', 0, 'C');
            $pdf->Cell(17, 3.5, 'Percent', 'RL', 0, 'C');
            $Y+= 3.5;

            $pdf->SetXY(10, $Y);
            $pdf->Cell(64, 3.5, '', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, 'UACS', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, 'Allotment', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, $month, 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, 'To Date', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, 'Allotment', 'RL', 0, 'C');
            $pdf->Cell(17, 3.5, 'Of', 'RL', 0, 'C');
            $Y+= 3.5;

            $pdf->SetXY(10, $Y);
            $pdf->Cell(64, 3.5, '', 'BRL', 0, 'C');
            $pdf->Cell(22, 3.5, '(1)', 'BRL', 0, 'C');
            $pdf->Cell(22, 3.5, '(4)', 'BRL', 0, 'C');
            $pdf->Cell(22, 3.5, '(5)', 'BRL', 0, 'C');
            $pdf->Cell(22, 3.5, '(6)', 'BRL', 0, 'C');
            $pdf->Cell(22, 3.5, '(7)=(4)-(6)', 'BRL', 0, 'C');
            $pdf->Cell(17, 3.5, 'Utilization', 'BRL', 0, 'C');

            $Y = $pdf->GetY()+3.5;
            // $pdf->SetXY(10, $Y);
            // $pdf->Cell(64, 215.5, '', 1, 0); 
            // $pdf->SetXY(74, $Y);
            // $pdf->Cell(22, 215.5, '', 1, 0); 
            // $pdf->SetXY(96, $Y);
            // $pdf->Cell(22, 215.5, '', 1, 0); 
            // $pdf->SetXY(118, $Y);
            // $pdf->Cell(22, 215.5, '', 1, 0); 
            // $pdf->SetXY(140, $Y);
            // $pdf->Cell(22, 215.5, '', 1, 0); 
            // $pdf->SetXY(162, $Y);
            // $pdf->Cell(22, 215.5, '', 1, 0); 
            // $pdf->SetXY(184, $Y);
            // $pdf->Cell(17, 215.5, '', 1, 0); 
        }

        // ================= ALLOTMENT CLASS ===================
        if ($allotment_class !== $last_allotment_class && $allotment_class !== null) {
            $pdf->SetFont('Arial', '', 8);

            // Save startY
            $startY = $Y;

            // Print text with wrapping
            $pdf->SetXY(10, $Y);
            $pdf->MultiCell(64, 4.5, $allotment_class, 0, 'L');

            // Measure row height
            $endY = $pdf->GetY();
            $totalRowHeight = $endY - $startY;

            // Draw borders
            $pdf->SetXY(10, $startY); $pdf->Cell(64, $totalRowHeight, '', 'L', 0, 'L');
            $pdf->SetXY(74, $startY); $pdf->Cell(22, $totalRowHeight, '', 0, 0, 'C');
            $pdf->SetXY(96, $startY); $pdf->Cell(22, $totalRowHeight, '', 0, 0, 'C');
            $pdf->SetXY(118, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
            $pdf->SetXY(140, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
            $pdf->SetXY(162, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
            $pdf->SetXY(184, $startY); $pdf->Cell(17, $totalRowHeight, '', 1, 0, 'C');

            // Center values vertically
            $middleY = $startY + ($totalRowHeight / 2) - 2.5;
            $pdf->SetFont('Arial', 'B', 8);

            $pdf->SetXY(96, $middleY); $pdf->Cell(22, 5, number_format($total_co, 2), 1, 0, 'C');
            $pdf->SetXY(118, $middleY); $pdf->Cell(22, 5, number_format($co_total_current_month, 2), 0, 0, 'C');
            $pdf->SetXY(140, $middleY); $pdf->Cell(22, 5, number_format($co_total_todate_month, 2), 0, 0, 'C');
            $pdf->SetXY(162, $middleY); $pdf->Cell(22, 5, number_format($co_total_unobligated, 2), 0, 0, 'C');
            $pdf->SetXY(184, $middleY); $pdf->Cell(17, 5, number_format($co_percentage_minus, 2) . '%', 0, 0, 'C');

            $Y = $endY;
            $last_allotment_class = $allotment_class;
        }

        // ================= OBJECT CODE ===================
        if ($object_code !== $last_object_code && $object_code !== null) {
            $pdf->SetFont('Arial', '', 8);

            // Object Code: Start at X=15 (no blank cell before)
            $pdf->SetXY(10, $Y);
            $pdf->Cell(5, 4.5, '', 'L', 1, 'C');
            $pdf->SetXY(15, $Y);
            $pdf->MultiCell(59, 4.5, $object_code, 0, 'L');

            // Totals
            $total_for_object_code = $co_object_code_totals[$object_code] ?? 0;
            $total_sub_month_amount = $co_sub_month_totals[$object_code] ?? 0;
            $total_sub_year_amount = $co_sub_year_totals[$object_code] ?? 0;
            $total_sub_unobligated = ($total_for_object_code ?? 0) - ($total_sub_year_amount ?? 0);

            // Columns
            $pdf->SetXY(96, $Y);
            $pdf->Cell(21.8, 4.5, number_format($total_for_object_code, 2), 1, 1, 'C');
            $pdf->SetXY(118, $Y);
            $pdf->Cell(21.8, 4.5, number_format($total_sub_month_amount, 2), 1, 1, 'C');
            $pdf->SetXY(140, $Y);
            $pdf->Cell(21.8, 4.5, number_format($total_sub_year_amount, 2), 1, 1, 'C');
            $pdf->SetXY(162, $Y);
            $pdf->Cell(21.8, 4.5, number_format($total_sub_unobligated, 2), 1, 1, 'C');
            $pdf->SetXY(184, $Y);
            $pdf->Cell(17, 4.5, '', 'R', 1, 'C');

            // Move down
            $Y += 4.5;
            $last_object_code = $object_code;
        }

        // ================= SUB OBJECT CODE ===================
        if ($sub_object_code !== $last_sub_object_code && $sub_object_code !== null) {
            $pdf->SetFont('Arial', '', 8);

            $startY = $Y;
            $pdf->SetXY(20, $Y);
            $pdf->MultiCell(55, 4.5, $sub_object_code, 0, 'L');

            $endY = $pdf->GetY();
            $totalRowHeight = $endY - $startY;

            if ($Y >260) {
                // Borders
                $pdf->SetXY(10, $startY); $pdf->Cell(64, $totalRowHeight, '', 'LB', 0, 'L');
                $pdf->SetXY(74, $startY); $pdf->Cell(22, $totalRowHeight, '', 'B', 0, 'C');
                $pdf->SetXY(96, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
                $pdf->SetXY(118, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
                $pdf->SetXY(140, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
                $pdf->SetXY(162, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
                $pdf->SetXY(184, $startY); $pdf->Cell(17, $totalRowHeight, '', 'RB', 0, 'C');
            }else{
                $pdf->SetXY(10, $startY); $pdf->Cell(64, $totalRowHeight, '', 'L', 0, 'L');
                $pdf->SetXY(74, $startY); $pdf->Cell(22, $totalRowHeight, '', 0, 0, 'C');
                $pdf->SetXY(96, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
                $pdf->SetXY(118, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
                $pdf->SetXY(140, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
                $pdf->SetXY(162, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
                $pdf->SetXY(184, $startY); $pdf->Cell(17, $totalRowHeight, '', 'R', 0, 'C');
            }

            // Center values
            $middleY = $startY + ($totalRowHeight / 2) - 2.5;

            $pdf->SetXY(74, $middleY); $pdf->Cell(22, 5, $uacs_code, 0, 0, 'C');
            $pdf->SetXY(96, $middleY); $pdf->Cell(22, 5, number_format($approved_budget,2), 0, 0, 'C');
            $pdf->SetXY(118, $middleY); $pdf->Cell(22, 5, $month_amount, 0, 0, 'C');
            $pdf->SetXY(140, $middleY); $pdf->Cell(22, 5, $year_amount, 0, 0, 'C');

            $subobligated_amount = $approved_budget - $year_amount;
            $pdf->SetXY(162, $middleY); $pdf->Cell(22, 5, number_format($subobligated_amount,2), 0, 0, 'C');
            $pdf->SetXY(184, $middleY); $pdf->Cell(17, 5, '', 0, 0, 'C');

            $Y = $endY;
            $last_sub_object_code = $sub_object_code;
        }

    }

    $total_year_ps += $total_ps;
    $total_year_mooe += $total_mooe;
    $total_year_co += $total_co;
    $ors_total_current_month += $total_all_sub_month;

    $Y = $pdf->GetY() + 3.5;    

}
$Y = $pdf->GetY() + 7.5;  
$pdf->SetXY(10, $Y);
$pdf->Cell(191, 3.5, '', 'TRL', 0, 'C');

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//------------------------------------------------------------ NUTRITIONAL ASSESSMENT AND MONITORING ON FOOD AND NUTRITION ----------------------------------------------------
//---------------------------------------------------------------------- Expanded National Nutrition Survey -------------------------------------------------------------------------
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

$total_curryear_budget = 0;
$total_year_ps = 0;
$total_year_mooe = 0;
$total_year_co = 0;
$total_sub_month_amount = 0;
$total_sub_year_amount = 0;
$program_tagging = "";
$project_like = "";
$program_class = "";

$total_todate_month = 0;
$ps_total_unobligated = 0;
$mooe_total_unobligated = 0;
$co_total_unobligated = 0;
$ps_percentage_minus = 0;
$mooe_percentage_minus = 0;
$co_percentage_minus = 0;
$total_project_budget = 0;
$ps_grand_total = 0;
$mooe_grand_total = 0;
$co_grand_total = 0;
$thismonth_grand_total = 0;

$ps_todate_grand_total = 0;
$mooe_todate_grand_total = 0;
$co_todate_grand_total = 0;
$todate_grand_total = 0;

$grand_unobligated = 0;
$grand_percentage_minus = 0;
$printed_once = '';
$printed_after_mooe = '';
//CURRENT YEAR BUDGET

$Y = $pdf->GetY() +3.5;

$query = $this->db->query("
    SELECT
        SUM(
            COALESCE((SELECT SUM(approved_budget) FROM tbl_saob_ps_dt WHERE project_id = a.`recid`), 0) +
            COALESCE((SELECT SUM(approved_budget) FROM tbl_saob_mooe_dt WHERE project_id = a.`recid`), 0) +
            COALESCE((SELECT SUM(approved_budget) FROM tbl_saob_co_dt WHERE project_id = a.`recid`), 0)
        ) AS total_approved_budget
    FROM
        `tbl_saob_hd` a
    WHERE 
        a.`project_title` like '%Expanded National Nutrition Survey%'
    ORDER BY a.`recid`
");
$rw = $query->getRowArray();
$total_project_budget = $rw['total_approved_budget'];

//CURRENT DATE GRAND TOTAL

//PS GRAND TOTAL
$query = $this->db->query("
    SELECT 
        SUM(total_sub_month) AS grand_total
    FROM (
        SELECT
            COALESCE((
                SELECT SUM(amount) 
                FROM tbl_ors_direct_ps_dt d 
                JOIN tbl_ors_hd hd ON d.`project_id` = hd.`recid`
                WHERE d.sub_object_code = b.particulars 
                AND hd.`ors_date` >= '$date_from' 
                AND hd.`ors_date` < '$date_to'
                AND d.`program_title` LIKE '%Expanded National Nutrition Survey%'
            ), 0)
            +
            COALESCE((
                SELECT SUM(amount) 
                FROM tbl_ors_indirect_ps_dt i 
                JOIN tbl_ors_hd hd ON i.`project_id` = hd.`recid`
                WHERE i.sub_object_code = b.particulars 
                AND hd.`ors_date` >= '$date_from' 
                AND hd.`ors_date` < '$date_to'
                AND i.`program_title` LIKE '%Expanded National Nutrition Survey%'
            ), 0) AS total_sub_month
        FROM tbl_saob_ps_dt AS b
        JOIN
        tbl_saob_hd saob
        on b.project_id = saob.recid
        WHERE
        saob.project_title LIKE '%Expanded National Nutrition Survey%'
    ) AS t;
");
$rw = $query->getRowArray();
$ps_grand_total = $rw['grand_total'];

//MOOE GRAND TOTAL
$query = $this->db->query("
    SELECT 
        SUM(total_sub_month) AS grand_total
    FROM (
        SELECT
            COALESCE((
                SELECT SUM(amount) 
                FROM tbl_ors_direct_mooe_dt d 
                JOIN tbl_ors_hd hd ON d.`project_id` = hd.`recid`
                WHERE d.sub_object_code = b.particulars 
                AND hd.`ors_date` >= '$date_from' 
                AND hd.`ors_date` < '$date_to'
                AND d.`program_title` LIKE '%Expanded National Nutrition Survey%'
            ), 0)
            +
            COALESCE((
                SELECT SUM(amount) 
                FROM tbl_ors_indirect_mooe_dt i 
                JOIN tbl_ors_hd hd ON i.`project_id` = hd.`recid`
                WHERE i.sub_object_code = b.particulars 
                AND hd.`ors_date` >= '$date_from' 
                AND hd.`ors_date` < '$date_to'
                AND i.`program_title` LIKE '%Expanded National Nutrition Survey%'
            ), 0) AS total_sub_month
        FROM tbl_saob_mooe_dt AS b
        JOIN
        tbl_saob_hd saob
        on b.project_id = saob.recid
        WHERE
        saob.project_title LIKE '%Expanded National Nutrition Survey%'
    ) AS t;
");
$rw = $query->getRowArray();
$mooe_grand_total = $rw['grand_total'];

//CO GRAND TOTAL
$query = $this->db->query("
    SELECT 
        SUM(total_sub_month) AS grand_total
    FROM (
        SELECT
            COALESCE((
                SELECT SUM(amount) 
                FROM tbl_ors_direct_co_dt d 
                JOIN tbl_ors_hd hd ON d.`project_id` = hd.`recid`
                WHERE d.sub_object_code = b.particulars 
                AND hd.`ors_date` >= '$date_from' 
                AND hd.`ors_date` < '$date_to'
                AND d.`program_title` LIKE '%Expanded National Nutrition Survey%'
            ), 0)
            +
            COALESCE((
                SELECT SUM(amount) 
                FROM tbl_ors_indirect_co_dt i 
                JOIN tbl_ors_hd hd ON i.`project_id` = hd.`recid`
                WHERE i.sub_object_code = b.particulars 
                AND hd.`ors_date` >= '$date_from' 
                AND hd.`ors_date` < '$date_to'
                AND i.`program_title` LIKE '%Expanded National Nutrition Survey%'
            ), 0) AS total_sub_month
        FROM tbl_saob_co_dt AS b
        JOIN
        tbl_saob_hd saob
        on b.project_id = saob.recid
        WHERE
        saob.project_title LIKE '%Expanded National Nutrition Survey%'
    ) AS t;
");
$rw = $query->getRowArray();
$co_grand_total = $rw['grand_total'];

$thismonth_grand_total = $ps_grand_total + $mooe_grand_total + $co_grand_total;


//UP TO DATE GRAND TOTAL

//UP TO DATE PS GRAND TOTAL
$query = $this->db->query("
    SELECT 
        SUM(total_sub_month) AS grand_total
    FROM (
        SELECT
            COALESCE((
                SELECT SUM(amount) 
                FROM tbl_ors_direct_ps_dt d 
                JOIN tbl_ors_hd hd ON d.`project_id` = hd.`recid`
                WHERE d.sub_object_code = b.particulars 
                AND d.`program_title` LIKE '%Expanded National Nutrition Survey%'
            ), 0)
            +
            COALESCE((
                SELECT SUM(amount) 
                FROM tbl_ors_indirect_ps_dt i 
                JOIN tbl_ors_hd hd ON i.`project_id` = hd.`recid`
                WHERE i.sub_object_code = b.particulars 
                AND i.`program_title` LIKE '%Expanded National Nutrition Survey%'
            ), 0) AS total_sub_month
        FROM tbl_saob_ps_dt AS b
        JOIN
        tbl_saob_hd saob
        on b.project_id = saob.recid
        WHERE
        saob.project_title LIKE '%Expanded National Nutrition Survey%'
    ) AS t;
");
$rw = $query->getRowArray();
$ps_todate_grand_total = $rw['grand_total'];

//UP TO DATE MOOE GRAND TOTAL
$query = $this->db->query("
    SELECT 
        SUM(total_sub_month) AS grand_total
    FROM (
        SELECT
            COALESCE((
                SELECT SUM(amount) 
                FROM tbl_ors_direct_mooe_dt d 
                JOIN tbl_ors_hd hd ON d.`project_id` = hd.`recid`
                WHERE d.sub_object_code = b.particulars 
                AND d.`program_title` LIKE '%Expanded National Nutrition Survey%'
            ), 0)
            +
            COALESCE((
                SELECT SUM(amount) 
                FROM tbl_ors_indirect_mooe_dt i 
                JOIN tbl_ors_hd hd ON i.`project_id` = hd.`recid`
                WHERE i.sub_object_code = b.particulars 
                AND i.`program_title` LIKE '%Expanded National Nutrition Survey%'
            ), 0) AS total_sub_month
        FROM tbl_saob_mooe_dt AS b
        JOIN
        tbl_saob_hd saob
        on b.project_id = saob.recid
        WHERE
        saob.project_title LIKE '%Expanded National Nutrition Survey%'
    ) AS t;
");
$rw = $query->getRowArray();
$mooe_todate_grand_total = $rw['grand_total'];

//UP TO DATE CO GRAND TOTAL
$query = $this->db->query("
    SELECT 
        SUM(total_sub_month) AS grand_total
    FROM (
        SELECT
            COALESCE((
                SELECT SUM(amount) 
                FROM tbl_ors_direct_co_dt d 
                JOIN tbl_ors_hd hd ON d.`project_id` = hd.`recid`
                WHERE d.sub_object_code = b.particulars 
                AND d.`program_title` LIKE '%Expanded National Nutrition Survey%'
            ), 0)
            +
            COALESCE((
                SELECT SUM(amount) 
                FROM tbl_ors_indirect_co_dt i 
                JOIN tbl_ors_hd hd ON i.`project_id` = hd.`recid`
                WHERE i.sub_object_code = b.particulars 
                AND i.`program_title` LIKE '%Expanded National Nutrition Survey%'
            ), 0) AS total_sub_month
        FROM tbl_saob_co_dt AS b
        JOIN
        tbl_saob_hd saob
        on b.project_id = saob.recid
        WHERE
        saob.project_title LIKE '%Expanded National Nutrition Survey%'
    ) AS t;
");
$rw = $query->getRowArray();
$co_todate_grand_total = $rw['grand_total'];

$todate_grand_total = $ps_todate_grand_total + $mooe_todate_grand_total + $co_todate_grand_total;
$grand_unobligated = $total_project_budget - $todate_grand_total;
if (!empty($total_project_budget) && $total_project_budget > 0) {
    $grand_percentage_minus = ($todate_grand_total / $total_project_budget) * 100;
}

$Y = $pdf->GetY()+3.5;
$query = $this->db->query("
    SELECT
        a.`program_title`,
        a.`project_title`,
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
    WHERE 
        a.`project_title` like '%Expanded National Nutrition Survey%'
    ORDER BY a.`recid`
");
$hd_data = $query->getResultArray();
$total_all_sub_month = 0;
$total_all_todate_month = 0;
$ors_total_current_month = 0;

foreach ($hd_data as $hd_row) {
    $ps_current_month = 0;
    $mooe_current_month = 0;
    $co_current_month = 0;
    $ps_tolatest_month = 0;
    $mooe_tolatest_month = 0;
    $co_tolatest_month = 0;
    $total_unobligated = 0;
    $total_percentage_minus = 0;
    $program_title = $hd_row['program_title'];
    $project_title = $hd_row['project_title'];
    $recid = $hd_row['recid'];
    $total_ps = $hd_row['total_ps'];
    $total_mooe = $hd_row['total_mooe'];
    $total_co = $hd_row['total_co'];
    $total_approved_budget = $hd_row['total_approved_budget'];

    if ($project_title == "Expanded National Nutrition Survey") {
        $program_tagging = 'Locally-Funded Project';
        $program_like = 'Expanded National Nutrition Survey';
    }


    //PS--------------------------------------------
    $query = $this->db->query("
        SELECT
            u.allotment_class,
            b.object_code,
            b.particulars AS sub_object_code,
            b.code AS uacs_code,
            b.approved_budget,
            COALESCE((
                SELECT SUM(amount) 
                FROM tbl_ors_direct_ps_dt d 
                JOIN
                tbl_ors_hd hd ON d.`project_id` = hd.`recid`
                WHERE d.sub_object_code = b.particulars AND hd.`ors_date` >= '$date_from' AND hd.`ors_date` < '$date_to' AND d.`program_title` like '%$program_like%'
            ), 0) + COALESCE((
                SELECT SUM(amount) 
                FROM tbl_ors_indirect_ps_dt i 
                JOIN
                tbl_ors_hd hd ON i.`project_id` = hd.`recid`
                WHERE i.sub_object_code = b.particulars AND hd.`ors_date` >= '$date_from' AND hd.`ors_date` < '$date_to' AND i.`program_title` like '%$program_like%'
            ), 0) AS total_sub_month,
            COALESCE((
                SELECT SUM(amount) 
                FROM tbl_ors_direct_ps_dt d 
                JOIN tbl_ors_hd hd ON d.project_id = hd.recid
                WHERE d.sub_object_code = b.particulars 
                AND d.program_title LIKE '%$program_like%'
            ), 0)
            + COALESCE((
                SELECT SUM(amount) 
                FROM tbl_ors_indirect_ps_dt i 
                JOIN tbl_ors_hd hd ON i.project_id = hd.recid
                WHERE i.sub_object_code = b.particulars
                AND i.program_title LIKE '%$project_like%'
            ), 0) AS total_sub_all
        FROM 
            tbl_saob_ps_dt AS b
        LEFT JOIN 
            mst_uacs AS u ON b.code = u.uacs_code
        WHERE 
            b.project_id = '$recid'
        ORDER BY 
            b.recid, b.particulars;

    ");
    $ps_data = $query->getResultArray();
    //total ps object code fetching
    $ps_object_code_totals = [];
    $ps_sub_month_totals = [];
    $ps_sub_year_totals = [];
    $last_object_code = '';
    
    $ps_total_current_month = 0;
    $ps_total_todate_month = 0;
    foreach ($ps_data as $ps_row) {
        $object_code = $ps_row['object_code'];
        $sub_object_code = $ps_row['sub_object_code'];
        $approved_budget = floatval($ps_row['approved_budget']);
        $total_sub_month = floatval($ps_row['total_sub_month']);
        $total_sub_all = floatval($ps_row['total_sub_all']);

        if (!isset($ps_object_code_totals[$object_code])) {
            $ps_object_code_totals[$object_code] = 0;
        }
        $ps_object_code_totals[$object_code] += $approved_budget;

        if (!isset($ps_sub_month_totals[$object_code])) {
            $ps_sub_month_totals[$object_code] = 0;
        }
        $ps_sub_month_totals[$object_code] += $total_sub_month;

        if (!isset($ps_sub_year_totals[$object_code])) {
            $ps_sub_year_totals[$object_code] = 0;
        }
        $ps_sub_year_totals[$object_code] += $total_sub_all;

        $ps_total_current_month += $total_sub_month;
        $ps_total_todate_month += $total_sub_all;

        $ps_total_unobligated = ($total_ps ?? 0) - ($ps_total_todate_month ?? 0);
        
        
        if (!empty($total_ps) && $total_ps > 0) {
            $ps_percentage_minus = ($ps_total_todate_month / $total_ps) * 100;   
        }


    }

    $ps_current_month += $ps_total_current_month;
    $ps_tolatest_month += $ps_total_todate_month;

    //MOOE------------------------------------------
    $query = $this->db->query("
        SELECT
            u.allotment_class,
            b.object_code,
            b.particulars AS sub_object_code,
            b.code AS uacs_code,
            b.approved_budget,
            COALESCE((
                SELECT SUM(amount) 
                FROM tbl_ors_direct_mooe_dt d 
                JOIN
                tbl_ors_hd hd ON d.`project_id` = hd.`recid`
                WHERE d.sub_object_code = b.particulars AND hd.`ors_date` >= '$date_from' AND hd.`ors_date` < '$date_to' AND d.`program_title` like '%$program_like%'
            ), 0) + COALESCE((
                SELECT SUM(amount) 
                FROM tbl_ors_indirect_mooe_dt i 
                JOIN
                tbl_ors_hd hd ON i.`project_id` = hd.`recid`
                WHERE i.sub_object_code = b.particulars AND hd.`ors_date` >= '$date_from' AND hd.`ors_date` < '$date_to' AND i.`program_title` like '%$program_like%'
            ), 0) AS total_sub_month,
            COALESCE((
                SELECT SUM(amount) 
                FROM tbl_ors_direct_mooe_dt d 
                JOIN tbl_ors_hd hd ON d.project_id = hd.recid
                WHERE d.sub_object_code = b.particulars
                AND d.program_title LIKE '%$program_like%'
            ), 0)
            + COALESCE((
                SELECT SUM(amount) 
                FROM tbl_ors_indirect_mooe_dt i 
                JOIN tbl_ors_hd hd ON i.project_id = hd.recid
                WHERE i.sub_object_code = b.particulars
                AND i.program_title LIKE '%$program_like%'
            ), 0) AS total_sub_all
        FROM 
            tbl_saob_mooe_dt AS b
        LEFT JOIN 
            mst_uacs AS u ON b.code = u.uacs_code
        WHERE 
            b.project_id = '$recid'
        ORDER BY 
            b.recid, b.particulars;

    ");
    $mooe_data = $query->getResultArray();
    //total mooe object code fetching
    $mooe_object_code_totals = [];
    $mooe_sub_month_totals = [];
    $mooe_sub_year_totals = [];
    $last_object_code = '';
    $mooe_total_current_month = 0;
    $mooe_total_todate_month = 0;
    foreach ($mooe_data as $mooe_row) {
        $object_code = $mooe_row['object_code'];
        $sub_object_code = $mooe_row['sub_object_code'];
        $approved_budget = floatval($mooe_row['approved_budget']);
        $total_sub_month = floatval($mooe_row['total_sub_month']);
        $total_sub_all = floatval($mooe_row['total_sub_all']);

        if (!isset($mooe_object_code_totals[$object_code])) {
            $mooe_object_code_totals[$object_code] = 0;
        }
        $mooe_object_code_totals[$object_code] += $approved_budget;

        if (!isset($mooe_sub_month_totals[$object_code])) {
            $mooe_sub_month_totals[$object_code] = 0;
        }
        $mooe_sub_month_totals[$object_code] += $total_sub_month;

        if (!isset($mooe_sub_year_totals[$object_code])) {
            $mooe_sub_year_totals[$object_code] = 0;
        }
        $mooe_sub_year_totals[$object_code] += $total_sub_all;

        $mooe_total_current_month += $total_sub_month;
        $mooe_total_todate_month += $total_sub_all;

        $mooe_total_unobligated = ($total_mooe ?? 0) - ($mooe_total_todate_month ?? 0);
        
        
        if (!empty($total_mooe) && $total_mooe > 0) {
            $mooe_percentage_minus = ($mooe_total_todate_month / $total_mooe) * 100;
        }
          
    }
    $mooe_current_month += $mooe_total_current_month;
    $mooe_tolatest_month += $mooe_total_todate_month;

    //CO--------------------------------------------
    $query = $this->db->query("
        SELECT
            u.allotment_class,
            b.object_code,
            b.particulars AS sub_object_code,
            b.code AS uacs_code,
            b.approved_budget,
            COALESCE((
                SELECT SUM(amount) 
                FROM tbl_ors_direct_co_dt d 
                JOIN
                tbl_ors_hd hd ON d.`project_id` = hd.`recid`
                WHERE d.sub_object_code = b.particulars AND hd.`ors_date` >= '$date_from' AND hd.`ors_date` < '$date_to' AND d.`program_title` like '%$program_like%'
            ), 0) + COALESCE((
                SELECT SUM(amount) 
                FROM tbl_ors_indirect_co_dt i 
                JOIN
                tbl_ors_hd hd ON i.`project_id` = hd.`recid`
                WHERE i.sub_object_code = b.particulars AND hd.`ors_date` >= '$date_from' AND hd.`ors_date` < '$date_to' AND i.`program_title` like '%$program_like%'
            ), 0) AS total_sub_month,
            COALESCE((
                SELECT SUM(amount) 
                FROM tbl_ors_direct_co_dt d 
                JOIN tbl_ors_hd hd ON d.project_id = hd.recid
                WHERE d.sub_object_code = b.particulars
                AND d.program_title LIKE '%$program_like%'
            ), 0)
            + COALESCE((
                SELECT SUM(amount) 
                FROM tbl_ors_indirect_co_dt i 
                JOIN tbl_ors_hd hd ON i.project_id = hd.recid
                WHERE i.sub_object_code = b.particulars
                AND i.program_title LIKE '%$program_like%'
            ), 0) AS total_sub_all
        FROM 
            tbl_saob_co_dt AS b
        LEFT JOIN 
            mst_uacs AS u ON b.code = u.uacs_code
        WHERE 
            b.project_id = '$recid'
        ORDER BY 
            b.recid, b.particulars;

    ");
    $co_data = $query->getResultArray();
    //total co object code fetching
    $co_object_code_totals = [];
    $co_sub_month_totals = [];
    $co_sub_year_totals = [];
    $last_object_code = '';
    $co_total_current_month = 0;
    $co_total_todate_month = 0;
    foreach ($co_data as $co_row) {
        $object_code = $co_row['object_code'];
        $sub_object_code = $co_row['sub_object_code'];
        $approved_budget = floatval($co_row['approved_budget']);
        $total_sub_month = floatval($co_row['total_sub_month']);
        $total_sub_all = floatval($co_row['total_sub_all']);

        if (!isset($co_object_code_totals[$object_code])) {
            $co_object_code_totals[$object_code] = 0;
        }
        $co_object_code_totals[$object_code] += $approved_budget;

        if (!isset($co_sub_month_totals[$object_code])) {
            $co_sub_month_totals[$object_code] = 0;
        }
        $co_sub_month_totals[$object_code] += $total_sub_month;

        if (!isset($co_sub_year_totals[$object_code])) {
            $co_sub_year_totals[$object_code] = 0;
        }
        $co_sub_year_totals[$object_code] += $total_sub_all;

        $co_total_current_month += $total_sub_month;
        $co_total_todate_month += $total_sub_all;

        $co_total_unobligated = ($total_co ?? 0) - ($co_total_todate_month ?? 0);
        
        
        if (!empty($total_co) && $total_co > 0) {
            $co_percentage_minus = ($co_total_todate_month / $total_co) * 100;
        }
          
    }

    $co_current_month += $co_total_current_month;
    $co_tolatest_month += $co_total_todate_month;

    $total_all_sub_month = $ps_current_month + $mooe_current_month + $co_current_month;
    $total_all_todate_month = $ps_tolatest_month + $mooe_tolatest_month + $co_tolatest_month;
    $total_unobligated = $total_approved_budget - $total_all_todate_month;
    if (!empty($total_approved_budget) && $total_approved_budget > 0) {
        $total_percentage_minus = ($total_all_todate_month / $total_approved_budget) * 100;
    }

    $Y = $pdf->GetY();
    // PROGRAM TAGGING LOGIC --------------------------------------------------------------------
    $startY = $Y; // Store the starting Y position

    // First, measure the height needed for program_tagging
    $pdf->SetXY(15, $Y);
    $pdf->SetFont('Arial', '', 8);
    $pdf->MultiCell(70, 3.5, $program_tagging, 0, 'L'); // Measure without border
    $afterProgramY = $pdf->GetY();
    $programHeight = $afterProgramY - $Y;

    // Draw complete PROGRAM TAGGING row with ALL borders
    $pdf->SetXY(10, $Y);
    $pdf->Cell(5, $programHeight, '', 'L', 0, 'C'); // First column

    $pdf->SetXY(15, $Y);
    $pdf->Cell(70, $programHeight, '', 0, 0, 'L'); // Program text column

    // Right-side columns for program tagging
    $pdf->SetXY(85, $Y);
    $pdf->Cell(11, $programHeight, '', 0, 0, 'C'); // UACS column

    $pdf->SetXY(96, $Y);
    $pdf->Cell(22, $programHeight, '', 1, 0, 'C'); // Budget column

    $pdf->SetXY(118, $Y);
    $pdf->Cell(22, $programHeight, '', 1, 0, 'C'); // This month column

    $pdf->SetXY(140, $Y);
    $pdf->Cell(22, $programHeight, '', 1, 0, 'C'); // To date column

    $pdf->SetXY(162, $Y);
    $pdf->Cell(22, $programHeight, '', 1, 0, 'C'); // Unobligated column

    $pdf->SetXY(184, $Y);
    $pdf->Cell(17, $programHeight, '', 1, 0, 'C'); // Percentage column

    // Draw program tagging totals (centered vertically)
    $programMiddleY = $Y + ($programHeight / 2) - 1.5;
    $pdf->SetXY(96, $programMiddleY);
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(22, 3, number_format($total_project_budget, 2), 0, 0, 'C');

    $pdf->SetXY(118, $programMiddleY);
    $pdf->Cell(22, 3, number_format($thismonth_grand_total, 2), 0, 0, 'C');

    $pdf->SetXY(140, $programMiddleY);
    $pdf->Cell(22, 3, number_format($todate_grand_total, 2), 0, 0, 'C');

    $pdf->SetXY(162, $programMiddleY);
    $pdf->Cell(22, 3, number_format($grand_unobligated, 2), 0, 0, 'C');

    $pdf->SetXY(184, $programMiddleY);
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(17, 3, number_format($grand_percentage_minus, 2) . '%', 0, 0, 'C');

    // Draw program tagging text
    $pdf->SetXY(15, $Y + 1);
    $pdf->SetFont('Arial', '', 8);
    $pdf->MultiCell(70, 3.5, '', 0, 'L');

    // PROJECT TITLE LOGIC --------------------------------------------------------------------
    $Y = $afterProgramY; // Move Y to after program tagging
    $projectStartY = $Y;

    // Measure the height needed for project_title
    $pdf->SetXY(15, $Y);
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->MultiCell(70, 3.5, $project_title, 0, 'L'); // Measure without border
    $afterProjectY = $pdf->GetY();
    $projectHeight = $afterProjectY - $Y;

    // Draw complete PROJECT TITLE row with ALL borders
    $pdf->SetXY(10, $Y);
    $pdf->Cell(5, $projectHeight, '', 'L', 0, 'C'); // First column

    $pdf->SetXY(15, $Y);
    $pdf->Cell(70, $projectHeight, '', 0, 0, 'L'); // Project text column

    // Right-side columns for project title
    $pdf->SetXY(85, $Y);
    $pdf->Cell(11, $projectHeight, '', 0, 0, 'C'); // UACS column

    $pdf->SetXY(96, $Y);
    $pdf->Cell(22, $projectHeight, '', 1, 0, 'C'); // Budget column

    $pdf->SetXY(118, $Y);
    $pdf->Cell(22, $projectHeight, '', 1, 0, 'C'); // This month column

    $pdf->SetXY(140, $Y);
    $pdf->Cell(22, $projectHeight, '', 1, 0, 'C'); // To date column

    $pdf->SetXY(162, $Y);
    $pdf->Cell(22, $projectHeight, '', 1, 0, 'C'); // Unobligated column

    $pdf->SetXY(184, $Y);
    $pdf->Cell(17, $projectHeight, '', 1, 0, 'C'); // Percentage column

    // Draw project title totals (centered vertically)
    $projectMiddleY = $Y + ($projectHeight / 2) - 1.5;
    $pdf->SetXY(96, $projectMiddleY);
    $pdf->SetFont('Arial', '', 8);
    $pdf->Cell(22, 3, number_format($total_approved_budget, 2), 0, 0, 'C');

    $pdf->SetXY(118, $projectMiddleY);
    $pdf->Cell(22, 3, number_format($total_all_sub_month, 2), 0, 0, 'C');

    $pdf->SetXY(140, $projectMiddleY);
    $pdf->Cell(22, 3, number_format($total_all_todate_month, 2), 0, 0, 'C');

    $pdf->SetXY(162, $projectMiddleY);
    $pdf->Cell(22, 3, number_format($total_unobligated, 2), 0, 0, 'C');

    $pdf->SetXY(184, $projectMiddleY);
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(17, 3, number_format($total_percentage_minus, 2) . '%', 0, 0, 'C');

    // Draw project title text
    $pdf->SetXY(15, $Y + 1);
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->MultiCell(70, 3.5, '', 0, 'L');

    // Update Y position for next content
    $Y = $afterProjectY;

    // Clean up and prepare for next section
    $last_allotment_class = '';
    $last_sub_object_code = '';
    $last_object_code = '';

    $Y = $pdf->GetY(); // Add some spacing before the next section

    foreach ($ps_data as $ps_row) {
        $allotment_class = $ps_row['allotment_class'];
        $object_code = $ps_row['object_code'];
        $sub_object_code = $ps_row['sub_object_code'];
        $uacs_code = $ps_row['uacs_code'];
        $approved_budget = $ps_row['approved_budget'];

        $query = $this->db->query("
            SELECT COALESCE(SUM(amount),0) AS total_amount  
            FROM (
                SELECT dps.`amount` FROM tbl_ors_direct_ps_dt dps JOIN tbl_ors_hd hd on dps.`project_id` = hd.`recid` WHERE dps.`sub_object_code` = '$sub_object_code' AND hd.`ors_date` >= '$date_from' AND hd.`ors_date` < '$date_to' AND dps.`program_title` like '%$program_like%'
                UNION ALL
                SELECT idps.`amount` FROM tbl_ors_indirect_ps_dt idps JOIN tbl_ors_hd hd on idps.`project_id` = hd.`recid` WHERE idps.`sub_object_code` = '$sub_object_code' AND hd.`ors_date` >= '$date_from' AND hd.`ors_date` < '$date_to' AND idps.`program_title` like '%$program_like%'
            ) AS combined
        ");

        $result = $query->getRowArray();
        $month_amount = $result['total_amount'];

        $query = $this->db->query("
            SELECT COALESCE(SUM(amount),0) AS total_amount  
            FROM (
                SELECT dps.`amount` FROM tbl_ors_direct_ps_dt dps JOIN tbl_ors_hd hd on dps.`project_id` = hd.`recid` WHERE dps.`sub_object_code` = '$sub_object_code'  AND dps.`program_title` like '%$program_like%'
                UNION ALL
                SELECT idps.`amount` FROM tbl_ors_indirect_ps_dt idps JOIN tbl_ors_hd hd on idps.`project_id` = hd.`recid` WHERE idps.`sub_object_code` = '$sub_object_code' AND idps.`program_title` like '%$program_like%'
            ) AS combined
        ");

        $result = $query->getRowArray();
        $year_amount = $result['total_amount'];

        if ($Y > 265) {
            $pdf->AddPage();
            $Y = $pdf->GetY();
            $pdf->SetXY(10, $Y);
            $pdf->SetFont('Arial', 'B', 7);
            $pdf->Cell(64, 3.5, '', 'TRL', 0, 'C');//ROW 1
            $pdf->Cell(22, 3.5, '', 'TRL', 0, 'C');
            $pdf->Cell(22, 3.5, '', 'TRL', 0, 'C');
            $pdf->Cell(44, 3.5, '', 'TRL', 0, 'C');
            $pdf->Cell(22, 3.5, '', 'TRL', 0, 'C');
            $pdf->Cell(17, 3.5, '', 'TRL', 0, 'C');
            $Y+= 3.5;

            $pdf->SetXY(10, $Y);
            $pdf->Cell(64, 3.5, '', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, '', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, '', 'RL', 0, 'C');
            $pdf->Cell(44, 3.5, 'Obligation Incurred', 'BRL', 0, 'C');
            $pdf->Cell(22, 3.5, 'Unobligated', 'RL', 0, 'C');
            $pdf->Cell(17, 3.5, '', 'RL', 0, 'C');
            $Y+= 3.5;

            $pdf->SetXY(10, $Y);
            $pdf->Cell(64, 3.5, 'OBJECT OF EXPENDITURE', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, '', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, 'Revised', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, 'This month', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, 'Obligation', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, 'Balance of', 'RL', 0, 'C');
            $pdf->Cell(17, 3.5, 'Percent', 'RL', 0, 'C');
            $Y+= 3.5;

            $pdf->SetXY(10, $Y);
            $pdf->Cell(64, 3.5, '', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, 'UACS', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, 'Allotment', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, $month, 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, 'To Date', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, 'Allotment', 'RL', 0, 'C');
            $pdf->Cell(17, 3.5, 'Of', 'RL', 0, 'C');
            $Y+= 3.5;

            $pdf->SetXY(10, $Y);
            $pdf->Cell(64, 3.5, '', 'BRL', 0, 'C');
            $pdf->Cell(22, 3.5, '(1)', 'BRL', 0, 'C');
            $pdf->Cell(22, 3.5, '(4)', 'BRL', 0, 'C');
            $pdf->Cell(22, 3.5, '(5)', 'BRL', 0, 'C');
            $pdf->Cell(22, 3.5, '(6)', 'BRL', 0, 'C');
            $pdf->Cell(22, 3.5, '(7)=(4)-(6)', 'BRL', 0, 'C');
            $pdf->Cell(17, 3.5, 'Utilization', 'BRL', 0, 'C');

            $Y = $pdf->GetY()+3.5;
            // $pdf->SetXY(10, $Y);
            // $pdf->Cell(64, 215.5, '', 1, 0); 
            // $pdf->SetXY(74, $Y);
            // $pdf->Cell(22, 215.5, '', 1, 0); 
            // $pdf->SetXY(96, $Y);
            // $pdf->Cell(22, 215.5, '', 1, 0); 
            // $pdf->SetXY(118, $Y);
            // $pdf->Cell(22, 215.5, '', 1, 0); 
            // $pdf->SetXY(140, $Y);
            // $pdf->Cell(22, 215.5, '', 1, 0); 
            // $pdf->SetXY(162, $Y);
            // $pdf->Cell(22, 215.5, '', 1, 0); 
            // $pdf->SetXY(184, $Y);
            // $pdf->Cell(17, 215.5, '', 1, 0); 
        }

        // ================= ALLOTMENT CLASS ===================
        if ($allotment_class !== $last_allotment_class && $allotment_class !== null) {
            $pdf->SetFont('Arial', '', 8);

            // Save startY
            $startY = $Y;

            // Print text with wrapping
            $pdf->SetXY(10, $Y);
            $pdf->MultiCell(64, 4.5, $allotment_class, 0, 'L');

            // Measure row height
            $endY = $pdf->GetY();
            $totalRowHeight = $endY - $startY;

            // Draw borders
            $pdf->SetXY(10, $startY); $pdf->Cell(64, $totalRowHeight, '', 'L', 0, 'L');
            $pdf->SetXY(74, $startY); $pdf->Cell(22, $totalRowHeight, '', 0, 0, 'C');
            $pdf->SetXY(96, $startY); $pdf->Cell(22, $totalRowHeight, '', 0, 0, 'C');
            $pdf->SetXY(118, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
            $pdf->SetXY(140, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
            $pdf->SetXY(162, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
            $pdf->SetXY(184, $startY); $pdf->Cell(17, $totalRowHeight, '', 1, 0, 'C');

            // Center values vertically
            $middleY = $startY + ($totalRowHeight / 2) - 2.5;
            $pdf->SetFont('Arial', 'B', 8);

            $pdf->SetXY(96, $middleY); $pdf->Cell(22, 5, number_format($total_ps, 2), 1, 0, 'C');
            $pdf->SetXY(118, $middleY); $pdf->Cell(22, 5, number_format($ps_total_current_month, 2), 0, 0, 'C');
            $pdf->SetXY(140, $middleY); $pdf->Cell(22, 5, number_format($ps_total_todate_month, 2), 0, 0, 'C');
            $pdf->SetXY(162, $middleY); $pdf->Cell(22, 5, number_format($ps_total_unobligated, 2), 0, 0, 'C');
            $pdf->SetXY(184, $middleY); $pdf->Cell(17, 5, number_format($ps_percentage_minus, 2) . '%', 0, 0, 'C');

            $Y = $endY;
            $last_allotment_class = $allotment_class;
        }

        // ================= OBJECT CODE ===================
        if ($object_code !== $last_object_code && $object_code !== null) {
            $pdf->SetFont('Arial', '', 8);

            // Object Code: Start at X=15 (no blank cell before)
            $pdf->SetXY(10, $Y);
            $pdf->Cell(5, 4.5, '', 'L', 1, 'C');
            $pdf->SetXY(15, $Y);
            $pdf->MultiCell(59, 4.5, $object_code, 0, 'L');

            // Totals
            $total_for_object_code = $ps_object_code_totals[$object_code] ?? 0;
            $total_sub_month_amount = $ps_sub_month_totals[$object_code] ?? 0;
            $total_sub_year_amount = $ps_sub_year_totals[$object_code] ?? 0;
            $total_sub_unobligated = ($total_for_object_code ?? 0) - ($total_sub_year_amount ?? 0);

            // Columns
            $pdf->SetXY(96, $Y);
            $pdf->Cell(21.8, 4.5, number_format($total_for_object_code, 2), 1, 1, 'C');
            $pdf->SetXY(118, $Y);
            $pdf->Cell(21.8, 4.5, number_format($total_sub_month_amount, 2), 1, 1, 'C');
            $pdf->SetXY(140, $Y);
            $pdf->Cell(21.8, 4.5, number_format($total_sub_year_amount, 2), 1, 1, 'C');
            $pdf->SetXY(162, $Y);
            $pdf->Cell(21.8, 4.5, number_format($total_sub_unobligated, 2), 1, 1, 'C');
            $pdf->SetXY(184, $Y);
            $pdf->Cell(17, 4.5, '', 'R', 1, 'C');

            // Move down
            $Y += 4.5;
            $last_object_code = $object_code;
        }

        // ================= SUB OBJECT CODE ===================
        if ($sub_object_code !== $last_sub_object_code && $sub_object_code !== null) {
            $pdf->SetFont('Arial', '', 8);

            $startY = $Y;
            $pdf->SetXY(20, $Y);
            $pdf->MultiCell(55, 4.5, $sub_object_code, 0, 'L');

            $endY = $pdf->GetY();
            $totalRowHeight = $endY - $startY;

            if ($Y >255) {
                // Borders
                $pdf->SetXY(10, $startY); $pdf->Cell(64, $totalRowHeight, '', 'LB', 0, 'L');
                $pdf->SetXY(74, $startY); $pdf->Cell(22, $totalRowHeight, '', 'B', 0, 'C');
                $pdf->SetXY(96, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
                $pdf->SetXY(118, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
                $pdf->SetXY(140, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
                $pdf->SetXY(162, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
                $pdf->SetXY(184, $startY); $pdf->Cell(17, $totalRowHeight, '', 'RB', 0, 'C');
            }else{
                $pdf->SetXY(10, $startY); $pdf->Cell(64, $totalRowHeight, '', 'L', 0, 'L');
                $pdf->SetXY(74, $startY); $pdf->Cell(22, $totalRowHeight, '', 0, 0, 'C');
                $pdf->SetXY(96, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
                $pdf->SetXY(118, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
                $pdf->SetXY(140, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
                $pdf->SetXY(162, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
                $pdf->SetXY(184, $startY); $pdf->Cell(17, $totalRowHeight, '', 'R', 0, 'C');
            }

            // Center values
            $middleY = $startY + ($totalRowHeight / 2) - 2.5;

            $pdf->SetXY(74, $middleY); $pdf->Cell(22, 5, $uacs_code, 0, 0, 'C');
            $pdf->SetXY(96, $middleY); $pdf->Cell(22, 5, number_format($approved_budget,2), 0, 0, 'C');
            $pdf->SetXY(118, $middleY); $pdf->Cell(22, 5, $month_amount, 0, 0, 'C');
            $pdf->SetXY(140, $middleY); $pdf->Cell(22, 5, $year_amount, 0, 0, 'C');

            $subobligated_amount = $approved_budget - $year_amount;
            $pdf->SetXY(162, $middleY); $pdf->Cell(22, 5, number_format($subobligated_amount,2), 0, 0, 'C');
            $pdf->SetXY(184, $middleY); $pdf->Cell(17, 5, '', 0, 0, 'C');

            $Y = $endY;
            $last_sub_object_code = $sub_object_code;
        }

    }

    $last_allotment_class = '';
    $last_sub_object_code = '';
    $last_object_code = '';

    $Y = $pdf->GetY();
    foreach ($mooe_data as $mooe_row) {
        $allotment_class = $mooe_row['allotment_class'];
        $object_code = $mooe_row['object_code'];
        $sub_object_code = $mooe_row['sub_object_code'];
        $uacs_code = $mooe_row['uacs_code'];
        $approved_budget = $mooe_row['approved_budget'];

        $query = $this->db->query("
            SELECT COALESCE(SUM(amount),0) AS total_amount  
            FROM (
                SELECT dmooe.`amount` FROM tbl_ors_direct_mooe_dt dmooe JOIN tbl_ors_hd hd on dmooe.`project_id` = hd.`recid` WHERE dmooe.`sub_object_code` = '$sub_object_code' AND hd.`ors_date` >= '$date_from' AND hd.`ors_date` < '$date_to' AND dmooe.`program_title` like '%$program_like%'
                UNION ALL
                SELECT idmooe.`amount` FROM tbl_ors_indirect_mooe_dt idmooe JOIN tbl_ors_hd hd on idmooe.`project_id` = hd.`recid` WHERE idmooe.`sub_object_code` = '$sub_object_code' AND hd.`ors_date` >= '$date_from' AND hd.`ors_date` < '$date_to' AND idmooe.`program_title` like '%$program_like%'
            ) AS combined
        ");

        $result = $query->getRowArray();
        $month_amount = $result['total_amount'];

        $query = $this->db->query("
            SELECT COALESCE(SUM(amount),0) AS total_amount  
            FROM (
                SELECT dmooe.`amount` FROM tbl_ors_direct_mooe_dt dmooe JOIN tbl_ors_hd hd on dmooe.`project_id` = hd.`recid` WHERE dmooe.`sub_object_code` = '$sub_object_code' AND dmooe.`program_title` like '%$program_like%'
                UNION ALL
                SELECT idmooe.`amount` FROM tbl_ors_indirect_mooe_dt idmooe JOIN tbl_ors_hd hd on idmooe.`project_id` = hd.`recid` WHERE idmooe.`sub_object_code` = '$sub_object_code' AND idmooe.`program_title` like '%$program_like%'
            ) AS combined
        ");

        $result = $query->getRowArray();
        $year_amount = $result['total_amount'];

        if ($Y > 265) {
            $pdf->AddPage();
            $Y = $pdf->GetY();
            $pdf->SetXY(10, $Y);
            $pdf->SetFont('Arial', 'B', 7);
            $pdf->Cell(64, 3.5, '', 'TRL', 0, 'C');//ROW 1
            $pdf->Cell(22, 3.5, '', 'TRL', 0, 'C');
            $pdf->Cell(22, 3.5, '', 'TRL', 0, 'C');
            $pdf->Cell(44, 3.5, '', 'TRL', 0, 'C');
            $pdf->Cell(22, 3.5, '', 'TRL', 0, 'C');
            $pdf->Cell(17, 3.5, '', 'TRL', 0, 'C');
            $Y+= 3.5;

            $pdf->SetXY(10, $Y);
            $pdf->Cell(64, 3.5, '', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, '', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, '', 'RL', 0, 'C');
            $pdf->Cell(44, 3.5, 'Obligation Incurred', 'BRL', 0, 'C');
            $pdf->Cell(22, 3.5, 'Unobligated', 'RL', 0, 'C');
            $pdf->Cell(17, 3.5, '', 'RL', 0, 'C');
            $Y+= 3.5;

            $pdf->SetXY(10, $Y);
            $pdf->Cell(64, 3.5, 'OBJECT OF EXPENDITURE', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, '', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, 'Revised', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, 'This month', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, 'Obligation', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, 'Balance of', 'RL', 0, 'C');
            $pdf->Cell(17, 3.5, 'Percent', 'RL', 0, 'C');
            $Y+= 3.5;

            $pdf->SetXY(10, $Y);
            $pdf->Cell(64, 3.5, '', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, 'UACS', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, 'Allotment', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, $month, 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, 'To Date', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, 'Allotment', 'RL', 0, 'C');
            $pdf->Cell(17, 3.5, 'Of', 'RL', 0, 'C');
            $Y+= 3.5;

            $pdf->SetXY(10, $Y);
            $pdf->Cell(64, 3.5, '', 'BRL', 0, 'C');
            $pdf->Cell(22, 3.5, '(1)', 'BRL', 0, 'C');
            $pdf->Cell(22, 3.5, '(4)', 'BRL', 0, 'C');
            $pdf->Cell(22, 3.5, '(5)', 'BRL', 0, 'C');
            $pdf->Cell(22, 3.5, '(6)', 'BRL', 0, 'C');
            $pdf->Cell(22, 3.5, '(7)=(4)-(6)', 'BRL', 0, 'C');
            $pdf->Cell(17, 3.5, 'Utilization', 'BRL', 0, 'C');

            $Y = $pdf->GetY()+3.5;
            // $pdf->SetXY(10, $Y);
            // $pdf->Cell(64, 215.5, '', 1, 0); 
            // $pdf->SetXY(74, $Y);
            // $pdf->Cell(22, 215.5, '', 1, 0); 
            // $pdf->SetXY(96, $Y);
            // $pdf->Cell(22, 215.5, '', 1, 0); 
            // $pdf->SetXY(118, $Y);
            // $pdf->Cell(22, 215.5, '', 1, 0); 
            // $pdf->SetXY(140, $Y);
            // $pdf->Cell(22, 215.5, '', 1, 0); 
            // $pdf->SetXY(162, $Y);
            // $pdf->Cell(22, 215.5, '', 1, 0); 
            // $pdf->SetXY(184, $Y);
            // $pdf->Cell(17, 215.5, '', 1, 0); 
        }

        // ================= ALLOTMENT CLASS ===================
        if ($allotment_class !== $last_allotment_class && $allotment_class !== null) {
            $pdf->SetFont('Arial', '', 8);

            // Save startY
            $startY = $Y;

            // Print text with wrapping
            $pdf->SetXY(10, $Y);
            $pdf->MultiCell(64, 4.5, $allotment_class, 0, 'L');

            // Measure row height
            $endY = $pdf->GetY();
            $totalRowHeight = $endY - $startY;

            // Draw borders
            $pdf->SetXY(10, $startY); $pdf->Cell(64, $totalRowHeight, '', 'L', 0, 'L');
            $pdf->SetXY(74, $startY); $pdf->Cell(22, $totalRowHeight, '', 0, 0, 'C');
            $pdf->SetXY(96, $startY); $pdf->Cell(22, $totalRowHeight, '', 0, 0, 'C');
            $pdf->SetXY(118, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
            $pdf->SetXY(140, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
            $pdf->SetXY(162, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
            $pdf->SetXY(184, $startY); $pdf->Cell(17, $totalRowHeight, '', 1, 0, 'C');

            // Center values vertically
            $middleY = $startY + ($totalRowHeight / 2) - 2.5;
            $pdf->SetFont('Arial', 'B', 8);

            $pdf->SetXY(96, $middleY); $pdf->Cell(22, 5, number_format($total_mooe, 2), 1, 0, 'C');
            $pdf->SetXY(118, $middleY); $pdf->Cell(22, 5, number_format($mooe_total_current_month, 2), 0, 0, 'C');
            $pdf->SetXY(140, $middleY); $pdf->Cell(22, 5, number_format($mooe_total_todate_month, 2), 0, 0, 'C');
            $pdf->SetXY(162, $middleY); $pdf->Cell(22, 5, number_format($mooe_total_unobligated, 2), 0, 0, 'C');
            $pdf->SetXY(184, $middleY); $pdf->Cell(17, 5, number_format($mooe_percentage_minus, 2) . '%', 0, 0, 'C');

            $Y = $endY;
            $last_allotment_class = $allotment_class;
        }

        // ================= OBJECT CODE ===================
        if ($object_code !== $last_object_code && $object_code !== null) {
            $pdf->SetFont('Arial', '', 8);

            // Object Code: Start at X=15 (no blank cell before)
            $pdf->SetXY(10, $Y);
            $pdf->Cell(5, 4.5, '', 'L', 1, 'C');
            $pdf->SetXY(15, $Y);
            $pdf->MultiCell(59, 4.5, $object_code, 0, 'L');

            // Totals
            $total_for_object_code = $mooe_object_code_totals[$object_code] ?? 0;
            $total_sub_month_amount = $mooe_sub_month_totals[$object_code] ?? 0;
            $total_sub_year_amount = $mooe_sub_year_totals[$object_code] ?? 0;
            $total_sub_unobligated = ($total_for_object_code ?? 0) - ($total_sub_year_amount ?? 0);

            // Columns
            $pdf->SetXY(96, $Y);
            $pdf->Cell(21.8, 4.5, number_format($total_for_object_code, 2), 1, 1, 'C');
            $pdf->SetXY(118, $Y);
            $pdf->Cell(21.8, 4.5, number_format($total_sub_month_amount, 2), 1, 1, 'C');
            $pdf->SetXY(140, $Y);
            $pdf->Cell(21.8, 4.5, number_format($total_sub_year_amount, 2), 1, 1, 'C');
            $pdf->SetXY(162, $Y);
            $pdf->Cell(21.8, 4.5, number_format($total_sub_unobligated, 2), 1, 1, 'C');
            $pdf->SetXY(184, $Y);
            $pdf->Cell(17, 4.5, '', 'R', 1, 'C');

            // Move down
            $Y += 4.5;
            $last_object_code = $object_code;
        }

        // ================= SUB OBJECT CODE ===================
        if ($sub_object_code !== $last_sub_object_code && $sub_object_code !== null) {
            $pdf->SetFont('Arial', '', 8);

            $startY = $Y;
            $pdf->SetXY(20, $Y);
            $pdf->MultiCell(55, 4.5, $sub_object_code, 0, 'L');

            $endY = $pdf->GetY();
            $totalRowHeight = $endY - $startY;

            if ($Y >260) {
                // Borders
                $pdf->SetXY(10, $startY); $pdf->Cell(64, $totalRowHeight, '', 'LB', 0, 'L');
                $pdf->SetXY(74, $startY); $pdf->Cell(22, $totalRowHeight, '', 'B', 0, 'C');
                $pdf->SetXY(96, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
                $pdf->SetXY(118, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
                $pdf->SetXY(140, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
                $pdf->SetXY(162, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
                $pdf->SetXY(184, $startY); $pdf->Cell(17, $totalRowHeight, '', 'RB', 0, 'C');
            }else{
                $pdf->SetXY(10, $startY); $pdf->Cell(64, $totalRowHeight, '', 'L', 0, 'L');
                $pdf->SetXY(74, $startY); $pdf->Cell(22, $totalRowHeight, '', 0, 0, 'C');
                $pdf->SetXY(96, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
                $pdf->SetXY(118, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
                $pdf->SetXY(140, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
                $pdf->SetXY(162, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
                $pdf->SetXY(184, $startY); $pdf->Cell(17, $totalRowHeight, '', 'R', 0, 'C');
            }

            // Center values
            $middleY = $startY + ($totalRowHeight / 2) - 2.5;

            $pdf->SetXY(74, $middleY); $pdf->Cell(22, 5, $uacs_code, 0, 0, 'C');
            $pdf->SetXY(96, $middleY); $pdf->Cell(22, 5, number_format($approved_budget,2), 0, 0, 'C');
            $pdf->SetXY(118, $middleY); $pdf->Cell(22, 5, $month_amount, 0, 0, 'C');
            $pdf->SetXY(140, $middleY); $pdf->Cell(22, 5, $year_amount, 0, 0, 'C');

            $subobligated_amount = $approved_budget - $year_amount;
            $pdf->SetXY(162, $middleY); $pdf->Cell(22, 5, number_format($subobligated_amount,2), 0, 0, 'C');
            $pdf->SetXY(184, $middleY); $pdf->Cell(17, 5, '', 0, 0, 'C');

            $Y = $endY;
            $last_sub_object_code = $sub_object_code;
        }

    }

    $last_allotment_class = '';
    $last_sub_object_code = '';
    $last_object_code = '';

    $Y = $pdf->GetY();
    foreach ($co_data as $co_row) {
        $allotment_class = $co_row['allotment_class'];
        $object_code = $co_row['object_code'];
        $sub_object_code = $co_row['sub_object_code'];
        $uacs_code = $co_row['uacs_code'];
        $approved_budget = $co_row['approved_budget'];

        $query = $this->db->query("
            SELECT COALESCE(SUM(amount),0) AS total_amount  
            FROM (
                SELECT dco.`amount` FROM tbl_ors_direct_co_dt dco JOIN tbl_ors_hd hd on dco.`project_id` = hd.`recid` WHERE dco.`sub_object_code` = '$sub_object_code' AND hd.`ors_date` >= '$date_from' AND hd.`ors_date` < '$date_to' AND dco.`program_title` like '%$program_like%'
                UNION ALL
                SELECT idco.`amount` FROM tbl_ors_indirect_co_dt idco JOIN tbl_ors_hd hd on idco.`project_id` = hd.`recid` WHERE idco.`sub_object_code` = '$sub_object_code' AND hd.`ors_date` >= '$date_from' AND hd.`ors_date` < '$date_to' AND idco.`program_title` like '%$program_like%'
            ) AS combined
        ");

        $result = $query->getRowArray();
        $month_amount = $result['total_amount'];

        $query = $this->db->query("
            SELECT COALESCE(SUM(amount),0) AS total_amount  
            FROM (
                SELECT dco.`amount` FROM tbl_ors_direct_co_dt dco JOIN tbl_ors_hd hd on dco.`project_id` = hd.`recid` WHERE dco.`sub_object_code` = '$sub_object_code' AND dco.`program_title` like '%$program_like%'
                UNION ALL
                SELECT idco.`amount` FROM tbl_ors_indirect_co_dt idco JOIN tbl_ors_hd hd on idco.`project_id` = hd.`recid` WHERE idco.`sub_object_code` = '$sub_object_code' AND idco.`program_title` like '%$program_like%'
            ) AS combined
        ");

        $result = $query->getRowArray();
        $year_amount = $result['total_amount'];

        if ($Y > 265) {
            $pdf->AddPage();
            $Y = $pdf->GetY();
            $pdf->SetXY(10, $Y);
            $pdf->SetFont('Arial', 'B', 7);
            $pdf->Cell(64, 3.5, '', 'TRL', 0, 'C');//ROW 1
            $pdf->Cell(22, 3.5, '', 'TRL', 0, 'C');
            $pdf->Cell(22, 3.5, '', 'TRL', 0, 'C');
            $pdf->Cell(44, 3.5, '', 'TRL', 0, 'C');
            $pdf->Cell(22, 3.5, '', 'TRL', 0, 'C');
            $pdf->Cell(17, 3.5, '', 'TRL', 0, 'C');
            $Y+= 3.5;

            $pdf->SetXY(10, $Y);
            $pdf->Cell(64, 3.5, '', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, '', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, '', 'RL', 0, 'C');
            $pdf->Cell(44, 3.5, 'Obligation Incurred', 'BRL', 0, 'C');
            $pdf->Cell(22, 3.5, 'Unobligated', 'RL', 0, 'C');
            $pdf->Cell(17, 3.5, '', 'RL', 0, 'C');
            $Y+= 3.5;

            $pdf->SetXY(10, $Y);
            $pdf->Cell(64, 3.5, 'OBJECT OF EXPENDITURE', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, '', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, 'Revised', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, 'This month', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, 'Obligation', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, 'Balance of', 'RL', 0, 'C');
            $pdf->Cell(17, 3.5, 'Percent', 'RL', 0, 'C');
            $Y+= 3.5;

            $pdf->SetXY(10, $Y);
            $pdf->Cell(64, 3.5, '', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, 'UACS', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, 'Allotment', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, $month, 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, 'To Date', 'RL', 0, 'C');
            $pdf->Cell(22, 3.5, 'Allotment', 'RL', 0, 'C');
            $pdf->Cell(17, 3.5, 'Of', 'RL', 0, 'C');
            $Y+= 3.5;

            $pdf->SetXY(10, $Y);
            $pdf->Cell(64, 3.5, '', 'BRL', 0, 'C');
            $pdf->Cell(22, 3.5, '(1)', 'BRL', 0, 'C');
            $pdf->Cell(22, 3.5, '(4)', 'BRL', 0, 'C');
            $pdf->Cell(22, 3.5, '(5)', 'BRL', 0, 'C');
            $pdf->Cell(22, 3.5, '(6)', 'BRL', 0, 'C');
            $pdf->Cell(22, 3.5, '(7)=(4)-(6)', 'BRL', 0, 'C');
            $pdf->Cell(17, 3.5, 'Utilization', 'BRL', 0, 'C');

            $Y = $pdf->GetY()+3.5;
            // $pdf->SetXY(10, $Y);
            // $pdf->Cell(64, 215.5, '', 1, 0); 
            // $pdf->SetXY(74, $Y);
            // $pdf->Cell(22, 215.5, '', 1, 0); 
            // $pdf->SetXY(96, $Y);
            // $pdf->Cell(22, 215.5, '', 1, 0); 
            // $pdf->SetXY(118, $Y);
            // $pdf->Cell(22, 215.5, '', 1, 0); 
            // $pdf->SetXY(140, $Y);
            // $pdf->Cell(22, 215.5, '', 1, 0); 
            // $pdf->SetXY(162, $Y);
            // $pdf->Cell(22, 215.5, '', 1, 0); 
            // $pdf->SetXY(184, $Y);
            // $pdf->Cell(17, 215.5, '', 1, 0); 
        }

        // ================= ALLOTMENT CLASS ===================
        if ($allotment_class !== $last_allotment_class && $allotment_class !== null) {
            $pdf->SetFont('Arial', '', 8);

            // Save startY
            $startY = $Y;

            // Print text with wrapping
            $pdf->SetXY(10, $Y);
            $pdf->MultiCell(64, 4.5, $allotment_class, 0, 'L');

            // Measure row height
            $endY = $pdf->GetY();
            $totalRowHeight = $endY - $startY;

            // Draw borders
            $pdf->SetXY(10, $startY); $pdf->Cell(64, $totalRowHeight, '', 'L', 0, 'L');
            $pdf->SetXY(74, $startY); $pdf->Cell(22, $totalRowHeight, '', 0, 0, 'C');
            $pdf->SetXY(96, $startY); $pdf->Cell(22, $totalRowHeight, '', 0, 0, 'C');
            $pdf->SetXY(118, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
            $pdf->SetXY(140, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
            $pdf->SetXY(162, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
            $pdf->SetXY(184, $startY); $pdf->Cell(17, $totalRowHeight, '', 1, 0, 'C');

            // Center values vertically
            $middleY = $startY + ($totalRowHeight / 2) - 2.5;
            $pdf->SetFont('Arial', 'B', 8);

            $pdf->SetXY(96, $middleY); $pdf->Cell(22, 5, number_format($total_co, 2), 1, 0, 'C');
            $pdf->SetXY(118, $middleY); $pdf->Cell(22, 5, number_format($co_total_current_month, 2), 0, 0, 'C');
            $pdf->SetXY(140, $middleY); $pdf->Cell(22, 5, number_format($co_total_todate_month, 2), 0, 0, 'C');
            $pdf->SetXY(162, $middleY); $pdf->Cell(22, 5, number_format($co_total_unobligated, 2), 0, 0, 'C');
            $pdf->SetXY(184, $middleY); $pdf->Cell(17, 5, number_format($co_percentage_minus, 2) . '%', 0, 0, 'C');

            $Y = $endY;
            $last_allotment_class = $allotment_class;
        }

        // ================= OBJECT CODE ===================
        if ($object_code !== $last_object_code && $object_code !== null) {
            $pdf->SetFont('Arial', '', 8);

            // Object Code: Start at X=15 (no blank cell before)
            $pdf->SetXY(10, $Y);
            $pdf->Cell(5, 4.5, '', 'L', 1, 'C');
            $pdf->SetXY(15, $Y);
            $pdf->MultiCell(59, 4.5, $object_code, 0, 'L');

            // Totals
            $total_for_object_code = $co_object_code_totals[$object_code] ?? 0;
            $total_sub_month_amount = $co_sub_month_totals[$object_code] ?? 0;
            $total_sub_year_amount = $co_sub_year_totals[$object_code] ?? 0;
            $total_sub_unobligated = ($total_for_object_code ?? 0) - ($total_sub_year_amount ?? 0);

            // Columns
            $pdf->SetXY(96, $Y);
            $pdf->Cell(21.8, 4.5, number_format($total_for_object_code, 2), 1, 1, 'C');
            $pdf->SetXY(118, $Y);
            $pdf->Cell(21.8, 4.5, number_format($total_sub_month_amount, 2), 1, 1, 'C');
            $pdf->SetXY(140, $Y);
            $pdf->Cell(21.8, 4.5, number_format($total_sub_year_amount, 2), 1, 1, 'C');
            $pdf->SetXY(162, $Y);
            $pdf->Cell(21.8, 4.5, number_format($total_sub_unobligated, 2), 1, 1, 'C');
            $pdf->SetXY(184, $Y);
            $pdf->Cell(17, 4.5, '', 'R', 1, 'C');

            // Move down
            $Y += 4.5;
            $last_object_code = $object_code;
        }

        // ================= SUB OBJECT CODE ===================
        if ($sub_object_code !== $last_sub_object_code && $sub_object_code !== null) {
            $pdf->SetFont('Arial', '', 8);

            $startY = $Y;
            $pdf->SetXY(20, $Y);
            $pdf->MultiCell(55, 4.5, $sub_object_code, 0, 'L');

            $endY = $pdf->GetY();
            $totalRowHeight = $endY - $startY;

            if ($Y >260) {
                // Borders
                $pdf->SetXY(10, $startY); $pdf->Cell(64, $totalRowHeight, '', 'LB', 0, 'L');
                $pdf->SetXY(74, $startY); $pdf->Cell(22, $totalRowHeight, '', 'B', 0, 'C');
                $pdf->SetXY(96, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
                $pdf->SetXY(118, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
                $pdf->SetXY(140, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
                $pdf->SetXY(162, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
                $pdf->SetXY(184, $startY); $pdf->Cell(17, $totalRowHeight, '', 'RB', 0, 'C');
            }else{
                $pdf->SetXY(10, $startY); $pdf->Cell(64, $totalRowHeight, '', 'L', 0, 'L');
                $pdf->SetXY(74, $startY); $pdf->Cell(22, $totalRowHeight, '', 0, 0, 'C');
                $pdf->SetXY(96, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
                $pdf->SetXY(118, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
                $pdf->SetXY(140, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
                $pdf->SetXY(162, $startY); $pdf->Cell(22, $totalRowHeight, '', 1, 0, 'C');
                $pdf->SetXY(184, $startY); $pdf->Cell(17, $totalRowHeight, '', 'R', 0, 'C');
            }

            // Center values
            $middleY = $startY + ($totalRowHeight / 2) - 2.5;

            $pdf->SetXY(74, $middleY); $pdf->Cell(22, 5, $uacs_code, 0, 0, 'C');
            $pdf->SetXY(96, $middleY); $pdf->Cell(22, 5, number_format($approved_budget,2), 0, 0, 'C');
            $pdf->SetXY(118, $middleY); $pdf->Cell(22, 5, $month_amount, 0, 0, 'C');
            $pdf->SetXY(140, $middleY); $pdf->Cell(22, 5, $year_amount, 0, 0, 'C');

            $subobligated_amount = $approved_budget - $year_amount;
            $pdf->SetXY(162, $middleY); $pdf->Cell(22, 5, number_format($subobligated_amount,2), 0, 0, 'C');
            $pdf->SetXY(184, $middleY); $pdf->Cell(17, 5, '', 0, 0, 'C');

            $Y = $endY;
            $last_sub_object_code = $sub_object_code;
        }

    }

    $total_year_ps += $total_ps;
    $total_year_mooe += $total_mooe;
    $total_year_co += $total_co;
    $ors_total_current_month += $total_all_sub_month;

    $Y = $pdf->GetY() + 3.5;    

}
$Y = $pdf->GetY() + 7.5;  
$pdf->SetXY(10, $Y);
$pdf->Cell(191, 3.5, '', 'TRL', 0, 'C');


$pdf->Output();
exit;
?>