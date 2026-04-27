<?php
$this->request = \Config\Services::request();
$this->db = \Config\Database::connect();
$recid = $this->request->getPostGet('recid');
$month = $this->request->getPostGet('month');
$year = $this->request->getPostGet('year');
$action = $this->request->getPostGet('action');
$this->session = session();
$this->cuser = $this->session->get('__xsys_myuserzicas__');
require APPPATH . 'ThirdParty/fpdf/fpdf.php';
$currentDate = date("Y-m-d");
$formattedDate = date("F j, Y", strtotime($currentDate));

$currentYear = date('Y');
$lastYear = $currentYear - 1;
// $query = $this->db->query("
// SELECT
//     `recid`,
//     `serialno`,
//     `particulars`,
//     `funding_source`,
//     `payee_name`,
//     `payee_office`,
//     `payee_address`,
//     `certified_a`,
//     `position_a`,
//     `certified_b`,
//     `position_b`,
//     `is_pending`,
//     `is_approved_certa`,
//     `is_disapproved_certa`,
//     `is_approved_certb`,
//     `is_disapproved_certb`,
//     `certa_remarks`,
//     `certb_remarks`,
//     `certa_approver`,
//     `certb_approver`,
//     `added_at`,
//     `added_by`,
//     `disbursement_date`,
//     `dvno`,
//     `fund_cluster_code`
// FROM
//     `tbl_disbursement_hd`
// WHERE 
//     `recid` = '$recid'"
// );

// $data = $query->getRowArray();
// $serialno = $data['serialno'];


$pdf = new FPDF('P', 'mm', 'A4');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetTitle('LDDAP-ADA Print');
$pdf->SetFont('Arial', 'B', 16);

$pdf->SetXY(0, 8);

$Y = 8;

// Draw the cell first
$pdf->SetXY(10, $Y);
$pdf->Cell(190, 4, '' , 'LTR', 1, 'L');

$Y = $pdf->GetY();
$pdf->SetFont('Arial', '', 7);
$pdf->Cell(20, 4, 'Department:' , 'L', 1, 'L');
$pdf->SetXY(30, $Y);
$pdf->Cell(100, 4, 'Department of Science and Technology' , 0, 1, 'L');
$pdf->SetXY(130, $Y);
$pdf->Cell(25, 4, 'LDDAP-ADA No.' , 0, 1, 'R');
$pdf->SetXY(155, $Y);
$pdf->Cell(45, 4, '184-04-145-2026' , 'R', 1, 'L');

$Y = $pdf->GetY();
$pdf->Cell(20, 4, 'Entity Name:' , 'L', 1, 'L');
$pdf->SetXY(30, $Y);
$pdf->Cell(100, 4, 'Food and Nutrition Research Institute' , 0, 1, 'L');
$pdf->SetXY(130, $Y);
$pdf->Cell(25, 4, 'Date:' , 0, 1, 'R');
$pdf->SetXY(155, $Y);
$pdf->Cell(45, 4, 'April 20, 2026' , 'R', 1, 'L');

$Y = $pdf->GetY();
$pdf->Cell(20, 4, '' , 'L', 1, 'L');
$pdf->SetXY(30, $Y);
$pdf->Cell(100, 4, '' , 0, 1, 'L');
$pdf->SetXY(130, $Y);
$pdf->Cell(25, 4, 'Fund Cluster:' , 0, 1, 'R');
$pdf->SetXY(155, $Y);
$pdf->Cell(45, 4, '07' , 'R', 1, 'L');

$Y = $pdf->GetY();
$pdf->Cell(20, 4, '' , 'L', 1, 'L');
$pdf->SetXY(30, $Y);
$pdf->Cell(100, 4, '' , 0, 1, 'L');
$pdf->SetXY(130, $Y);
$pdf->Cell(25, 4, '' , 0, 1, 'R');
$pdf->SetXY(155, $Y);
$pdf->Cell(45, 4, '' , 'R', 1, 'L');

$Y = $pdf->GetY();
$pdf->Cell(45, 4, 'MDS-GSB BRANCH:' , 'L', 1, 'L');
$pdf->SetXY(55, $Y);
$pdf->Cell(100, 4, 'Land Bank of the Philippines - DOST Branch' , 0, 1, 'L');
$pdf->SetXY(130, $Y);
$pdf->Cell(25, 4, '' , 0, 1, 'R');
$pdf->SetXY(155, $Y);
$pdf->Cell(45, 4, '' , 'R', 1, 'L');

$Y = $pdf->GetY();
$pdf->Cell(45, 4, 'MDS SUB ACCOUNT NO.:' , 'L', 1, 'L');
$pdf->SetXY(55, $Y);
$pdf->Cell(100, 4, '2182-9006-16' , 0, 1, 'L');
$pdf->SetXY(130, $Y);
$pdf->Cell(25, 4, '' , 0, 1, 'R');
$pdf->SetXY(155, $Y);
$pdf->Cell(45, 4, '' , 'R', 1, 'L');

$Y = $pdf->GetY();
$pdf->Cell(20, 4, '' , 'L', 1, 'L');
$pdf->SetXY(30, $Y);
$pdf->Cell(100, 4, '' , 0, 1, 'L');
$pdf->SetXY(130, $Y);
$pdf->Cell(25, 4, '' , 0, 1, 'R');
$pdf->SetXY(155, $Y);
$pdf->Cell(45, 4, '' , 'R', 1, 'L');

$Y = $pdf->GetY();
$pdf->Cell(190, 4, 'I. LIST OF DUE AND DEMANDABLE ACCOUNTS PAYABLE (LDDAP)' , 1, 1, 'C');
$pdf->SetFont('Arial', 'B', 4.5);
$Y = $pdf->GetY();
$pdf->Cell(40, 2.5, 'CREDITOR' , 1, 1, 'C');
$pdf->SetXY(50, $Y);
$pdf->Cell(25, 2.5, '' , 'B', 1, 'C');
$pdf->SetXY(75, $Y);
$pdf->Cell(30, 2.5, '' , 'L', 1, 'C');
$pdf->SetXY(105, $Y);
$pdf->Cell(15, 2.5, '' , 'LR', 1, 'C');
$pdf->SetXY(120, $Y);
$pdf->Cell(65, 2.5, '' , 'R', 1, 'C');
$pdf->SetXY(185, $Y);
$pdf->Cell(15, 2.5, '' , 'R', 1, 'C');

$Y = $pdf->GetY();
$pdf->Cell(40, 2.5, '' , 'L', 1, 'C');
$pdf->SetXY(50, $Y);
$pdf->Cell(25, 2.5, 'PREFERRED SERVICING' , 'L', 1, 'C');
$pdf->SetXY(75, $Y);
$pdf->Cell(30, 2.5, 'Obligation Request and' , 'L', 1, 'C');
$pdf->SetXY(105, $Y);
$pdf->Cell(15, 2.5, 'ALLOTMENT' , 'L', 1, 'C');
$pdf->SetXY(120, $Y);
$pdf->Cell(65, 2.5, 'In Pesos' , 'L', 1, 'C');
$pdf->SetXY(185, $Y);
$pdf->Cell(15, 2.5, '' , 'LR', 1, 'C');

$Y = $pdf->GetY();
$pdf->Cell(40, 2.5, 'NAME' , 'L', 1, 'C');
$pdf->SetXY(50, $Y);
$pdf->Cell(25, 2.5, 'BANKS/' , 'L', 1, 'C');
$pdf->SetXY(75, $Y);
$pdf->Cell(30, 2.5, 'Status No.' , 'L', 1, 'C');
$pdf->SetXY(105, $Y);
$pdf->Cell(15, 2.5, 'CLASS per' , 'L', 1, 'C');
$pdf->SetXY(120, $Y);
$pdf->Cell(25, 2.5, '' , 'LT', 1, 'C');
$pdf->SetXY(145, $Y);
$pdf->Cell(15, 2.5, '' , 'LT', 1, 'C');
$pdf->SetXY(160, $Y);
$pdf->Cell(25, 2.5, '' , 'LT', 1, 'C');
$pdf->SetXY(185, $Y);
$pdf->Cell(15, 2.5, 'REMARKS' , 'LR', 1, 'C');

$Y = $pdf->GetY();
$pdf->Cell(40, 2.5, '' , 'L', 1, 'C');
$pdf->SetXY(50, $Y);
$pdf->Cell(25, 2.5, 'SAVINGS/CURRENT' , 'L', 1, 'C');
$pdf->SetXY(75, $Y);
$pdf->Cell(30, 2.5, '' , 'L', 1, 'C');
$pdf->SetXY(105, $Y);
$pdf->Cell(15, 2.5, '(UACS)' , 'L', 1, 'C');
$pdf->SetXY(120, $Y);
$pdf->Cell(25, 2.5, 'GROSS AMOUNT' , 'L', 1, 'C');
$pdf->SetXY(145, $Y);
$pdf->Cell(15, 2.5, 'WITHOLDING TAX' , 'L', 1, 'C');
$pdf->SetXY(160, $Y);
$pdf->Cell(25, 2.5, 'NET AMOUNT' , 'L', 1, 'C');
$pdf->SetXY(185, $Y);
$pdf->Cell(15, 2.5, '' , 'LR', 1, 'C');

$Y = $pdf->GetY();
$pdf->Cell(40, 2.5, '' , 'LB', 1, 'C');
$pdf->SetXY(50, $Y);
$pdf->Cell(25, 2.5, 'ACCOUNT NO.' , 'LB', 1, 'C');
$pdf->SetXY(75, $Y);
$pdf->Cell(30, 2.5, '' , 'LB', 1, 'C');
$pdf->SetXY(105, $Y);
$pdf->Cell(15, 2.5, '' , 'LB', 1, 'C');
$pdf->SetXY(120, $Y);
$pdf->Cell(25, 2.5, '' , 'LB', 1, 'C');
$pdf->SetXY(145, $Y);
$pdf->Cell(15, 2.5, '' , 'LB', 1, 'C');
$pdf->SetXY(160, $Y);
$pdf->Cell(25, 2.5, '' , 'LB', 1, 'C');
$pdf->SetXY(185, $Y);
$pdf->Cell(15, 2.5, '' , 'LRB', 1, 'C');

$Y = $pdf->GetY();
$pdf->SetFont('Arial', '', 7);
// your row cells
$pdf->Cell(40, 4, 'I. Current Year A/Ps', 1, 0, 'L');
$pdf->Cell(25, 4, '', 1, 0, 'C');
$pdf->Cell(30, 4, '', 1, 0, 'C');
$pdf->Cell(15, 4, '', 1, 0, 'C');
$pdf->Cell(25, 4, '', 1, 0, 'C');
$pdf->Cell(15, 4, '', 1, 0, 'C');
$pdf->Cell(25, 4, '', 1, 0, 'C');

// save X before writing last column
$X = $pdf->GetX();

// move back to top Y
$pdf->SetXY($X, $Y);
$pdf->SetFont('Arial', '', 4.5);
// MultiCell for 2-line text with only top & bottom border
$pdf->MultiCell(15, 2, "FOR MDS-GSB\nUSE ONLY", 1, 'C');

$Y = $pdf->GetY();

$query = $this->db->query("
SELECT
    a.`dvno`,
    a.`payee_name`,
    a.`payee_account_num`,
    a.`serialno`,
    a.`uacs_code`,
    a.`gross_amount`,
    a.`total_deduction`,
    a.`net_amount`,
    b.`disbursement_date`
FROM
    `tbl_lddapada_dt` a
LEFT JOIN
    `tbl_disbursement_hd` b
ON
    a.`dvno` = b.`dvno`
WHERE 
    `lddapada_id` = '$recid' AND  YEAR(b.disbursement_date)  = '$currentYear'
ORDER BY
    a.`recid` ASC
");

$data = $query->getResultArray();

$pdf->SetFont('Arial', '', 5);

foreach ($data as $row) {

    $payee_name = str_replace(["\r","\n"], '', $row['payee_name']);
    $account_no = $row['payee_account_num'];
    $dvno       = $row['dvno'];
    $uacs       = $row['uacs_code'];
    $gross      = $row['gross_amount'];
    $deduction  = $row['total_deduction'];
    $net        = $row['net_amount'];
    $remarks    = ''; // or actual

    // =========================
    // 🔹 STEP 1: GET HEIGHT (PAYEE ONLY)
    // =========================
    $startY = $pdf->GetY();

    $pdf->SetXY(10, $startY);
    $pdf->MultiCell(40, 4, $payee_name, 0, 'L');

    $endY = $pdf->GetY();
    $rowHeight = $endY - $startY;

    // minimum height
    if ($rowHeight < 4) {
        $rowHeight = 4;
    }

    // =========================
    // 🔥 DRAW ROW
    // =========================

    // PAYEE NAME (MULTICELL)
    $pdf->SetXY(10, $startY);
    $pdf->MultiCell(40, 4, $payee_name, 1, 'L');

    // ACCOUNT NO
    $pdf->SetXY(50, $startY);
    $pdf->Cell(25, $rowHeight, $account_no, 1, 0, 'C');

    // DV NO
    $pdf->SetXY(75, $startY);
    $pdf->Cell(30, $rowHeight, $dvno, 1, 0, 'C');

    // UACS
    $pdf->SetXY(105, $startY);
    $pdf->Cell(15, $rowHeight, $uacs, 1, 0, 'C');

    // GROSS
    $pdf->SetXY(120, $startY);
    $pdf->Cell(25, $rowHeight, number_format($gross,2), 1, 0, 'C');

    // DEDUCTION
    $pdf->SetXY(145, $startY);
    $pdf->Cell(15, $rowHeight, number_format($deduction,2), 1, 0, 'C');

    // NET
    $pdf->SetXY(160, $startY);
    $pdf->Cell(25, $rowHeight, number_format($net,2), 1, 0, 'C');

    // REMARKS (FIXED HEIGHT, NO HEIGHT CONTROL)
    $pdf->SetXY(185, $startY);
    $pdf->Cell(15, $rowHeight, '', 1, 0, 'C');

    // optional text
    $pdf->SetXY(185, $startY);
    $pdf->MultiCell(15, 4, $remarks, 0, 'C');

    // =========================
    // 🔹 NEXT ROW
    // =========================
    $pdf->SetY($startY + $rowHeight);
}

$targetY = 90;

// keep adding empty rows
while ($pdf->GetY() < $targetY) {

    $startY = $pdf->GetY();
    $rowHeight = 4; // same as your minimum row height

    // PAYEE NAME
    $pdf->SetXY(10, $startY);
    $pdf->MultiCell(40, 4, '', 1, 'L');

    // ACCOUNT NO
    $pdf->SetXY(50, $startY);
    $pdf->Cell(25, $rowHeight, '', 1, 0, 'C');

    // DV NO
    $pdf->SetXY(75, $startY);
    $pdf->Cell(30, $rowHeight, '', 1, 0, 'C');

    // UACS
    $pdf->SetXY(105, $startY);
    $pdf->Cell(15, $rowHeight, '', 1, 0, 'C');

    // GROSS
    $pdf->SetXY(120, $startY);
    $pdf->Cell(25, $rowHeight, '', 1, 0, 'C');

    // DEDUCTION
    $pdf->SetXY(145, $startY);
    $pdf->Cell(15, $rowHeight, '', 1, 0, 'C');

    // NET
    $pdf->SetXY(160, $startY);
    $pdf->Cell(25, $rowHeight, '', 1, 0, 'C');

    // REMARKS
    $pdf->SetXY(185, $startY);
    $pdf->Cell(15, $rowHeight, '', 1, 0, 'C');

    // move next row
    $pdf->SetY($startY + $rowHeight);
}

$Y = $pdf->GetY();
$X = 10;
$pdf->SetXY(10, $Y);
$pdf->Cell(40, 4, 'Sub-total' , 1, 1, 'C');
$pdf->SetXY(50, $Y);
$pdf->Cell(25, 4, '' , 1, 1, 'C');
$pdf->SetXY(75, $Y);
$pdf->Cell(30, 4, '' , 1, 1, 'C');
$pdf->SetXY(105, $Y);
$pdf->Cell(15, 4, '' , 1, 1, 'C');
$pdf->SetXY(120, $Y);
$pdf->Cell(25, 4, 'GROSS TOTAL' , 1, 1, 'C');
$pdf->SetXY(145, $Y);
$pdf->Cell(15, 4, 'DEDUCTION' , 1, 1, 'C');
$pdf->SetXY(160, $Y);
$pdf->Cell(25, 4, 'NET' , 1, 1, 'C');
$pdf->SetXY(185, $Y);
$pdf->Cell(15, 4, '' , 1, 1, 'C');

$Y = $pdf->GetY($Y);
$pdf->SetFont('Arial', '', 7);
$pdf->SetXY(10, $Y);
$pdf->Cell(40, 4, "II. Prior Year's A/Ps", 1, 0, 'L');
$pdf->Cell(25, 4, '', 1, 0, 'C');
$pdf->Cell(30, 4, '', 1, 0, 'C');
$pdf->Cell(15, 4, '', 1, 0, 'C');
$pdf->Cell(25, 4, '', 1, 0, 'C');
$pdf->Cell(15, 4, '', 1, 0, 'C');
$pdf->Cell(25, 4, '', 1, 0, 'C');
$pdf->Cell(15, 4, '', 1, 0, 'C');

$Y = $pdf->GetY()+4;
$pdf->SetXY(10, $Y);
$query = $this->db->query("
SELECT
    a.`dvno`,
    a.`payee_name`,
    a.`payee_account_num`,
    a.`serialno`,
    a.`uacs_code`,
    a.`gross_amount`,
    a.`total_deduction`,
    a.`net_amount`,
    b.`disbursement_date`
FROM
    `tbl_lddapada_dt` a
LEFT JOIN
    `tbl_disbursement_hd` b
ON
    a.`dvno` = b.`dvno`
WHERE 
    `lddapada_id` = '$recid' AND  YEAR(b.disbursement_date)  = '$lastYear'
ORDER BY
    a.`recid` ASC
");

$data = $query->getResultArray();

$pdf->SetFont('Arial', '', 5);

foreach ($data as $row) {

    $payee_name = str_replace(["\r","\n"], '', $row['payee_name']);
    $account_no = $row['payee_account_num'];
    $dvno       = $row['dvno'];
    $uacs       = $row['uacs_code'];
    $gross      = $row['gross_amount'];
    $deduction  = $row['total_deduction'];
    $net        = $row['net_amount'];
    $remarks    = ''; // or actual

    // =========================
    // 🔹 STEP 1: GET HEIGHT (PAYEE ONLY)
    // =========================
    $startY = $pdf->GetY();

    $pdf->SetXY(10, $startY);
    $pdf->MultiCell(40, 4, $payee_name, 0, 'L');

    $endY = $pdf->GetY();
    $rowHeight = $endY - $startY;

    // minimum height
    if ($rowHeight < 4) {
        $rowHeight = 4;
    }

    // =========================
    // 🔥 DRAW ROW
    // =========================

    // PAYEE NAME (MULTICELL)
    $pdf->SetXY(10, $startY);
    $pdf->MultiCell(40, 4, $payee_name, 1, 'L');

    // ACCOUNT NO
    $pdf->SetXY(50, $startY);
    $pdf->Cell(25, $rowHeight, $account_no, 1, 0, 'C');

    // DV NO
    $pdf->SetXY(75, $startY);
    $pdf->Cell(30, $rowHeight, $dvno, 1, 0, 'C');

    // UACS
    $pdf->SetXY(105, $startY);
    $pdf->Cell(15, $rowHeight, $uacs, 1, 0, 'C');

    // GROSS
    $pdf->SetXY(120, $startY);
    $pdf->Cell(25, $rowHeight, number_format($gross,2), 1, 0, 'C');

    // DEDUCTION
    $pdf->SetXY(145, $startY);
    $pdf->Cell(15, $rowHeight, number_format($deduction,2), 1, 0, 'C');

    // NET
    $pdf->SetXY(160, $startY);
    $pdf->Cell(25, $rowHeight, number_format($net,2), 1, 0, 'C');

    // REMARKS (FIXED HEIGHT, NO HEIGHT CONTROL)
    $pdf->SetXY(185, $startY);
    $pdf->Cell(15, $rowHeight, '', 1, 0, 'C');

    // optional text
    $pdf->SetXY(185, $startY);
    $pdf->MultiCell(15, 4, $remarks, 0, 'C');

    // =========================
    // 🔹 NEXT ROW
    // =========================
    $pdf->SetY($startY + $rowHeight);
}

$targetYY = 130;

// keep adding empty rows
while ($pdf->GetY() < $targetYY) {

    $startY = $pdf->GetY();
    $rowHeight = 4; // same as your minimum row height

    // PAYEE NAME
    $pdf->SetXY(10, $startY);
    $pdf->MultiCell(40, 4, '', 1, 'L');

    // ACCOUNT NO
    $pdf->SetXY(50, $startY);
    $pdf->Cell(25, $rowHeight, '', 1, 0, 'C');

    // DV NO
    $pdf->SetXY(75, $startY);
    $pdf->Cell(30, $rowHeight, '', 1, 0, 'C');

    // UACS
    $pdf->SetXY(105, $startY);
    $pdf->Cell(15, $rowHeight, '', 1, 0, 'C');

    // GROSS
    $pdf->SetXY(120, $startY);
    $pdf->Cell(25, $rowHeight, '', 1, 0, 'C');

    // DEDUCTION
    $pdf->SetXY(145, $startY);
    $pdf->Cell(15, $rowHeight, '', 1, 0, 'C');

    // NET
    $pdf->SetXY(160, $startY);
    $pdf->Cell(25, $rowHeight, '', 1, 0, 'C');

    // REMARKS
    $pdf->SetXY(185, $startY);
    $pdf->Cell(15, $rowHeight, '', 1, 0, 'C');

    // move next row
    $pdf->SetY($startY + $rowHeight);
}
//SUB TOTAL PRIOR YEAR ----------------------
$Y = $pdf->GetY();
$X = 10;
$pdf->SetXY(10, $Y);
$pdf->Cell(40, 4, 'Sub-total' , 1, 1, 'C');
$pdf->SetXY(50, $Y);
$pdf->Cell(25, 4, '' , 1, 1, 'C');
$pdf->SetXY(75, $Y);
$pdf->Cell(30, 4, '' , 1, 1, 'C');
$pdf->SetXY(105, $Y);
$pdf->Cell(15, 4, '' , 1, 1, 'C');
$pdf->SetXY(120, $Y);
$pdf->Cell(25, 4, 'GROSS TOTAL' , 1, 1, 'C');
$pdf->SetXY(145, $Y);
$pdf->Cell(15, 4, 'DEDUCTION' , 1, 1, 'C');
$pdf->SetXY(160, $Y);
$pdf->Cell(25, 4, 'NET' , 1, 1, 'C');
$pdf->SetXY(185, $Y);
$pdf->Cell(15, 4, '' , 1, 1, 'C');


$pdf->Output();
exit;
?>