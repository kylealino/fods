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
    `project_leader`
FROM
    `tbl_budget_hd`
WHERE 
    `recid` = '$recid'"
);

$data = $query->getRowArray();
$project_title = $data['project_title'];
$project_leader = $data['project_leader'];

$pdf = new \FPDF();
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

$pdf->Cell(40, 3.5, 'Program Title', 0, 0, 'L');
$pdf->Cell(60, 3.5, ':' , 0, 1, 'L');

// Project Title (wraps if long)
$pdf->Cell(40, 3.5, 'Project Title', 0, 0, 'L');
$X = $pdf->GetX(); // Save current X position
$Y = $pdf->GetY(); // Save current Y position

$pdf->SetXY($X, $Y); // Go to value position
$pdf->MultiCell(150, 3.5, ': ' . $project_title, 0, 'L');

// Implementing Agency (below the wrapped Project Title)
$pdf->Cell(40, 3.5, 'Implementing Agency', 0, 0, 'L');
$pdf->Cell(60, 3.5, ':', 0, 1, 'L');


$pdf->Cell(40, 3.5, 'Total Duration', 0, 0, 'L');
$pdf->Cell(60, 3.5, ':', 0, 1, 'L');

$pdf->Cell(40, 3.5, 'Current Duration', 0, 0, 'L');
$pdf->Cell(60, 3.5, ':', 0, 1, 'L');

$pdf->Cell(40, 3.5, 'Cooperating Agency', 0, 0, 'L');
$pdf->Cell(60, 3.5, ':', 0, 1, 'L');

$pdf->Cell(40, 3.5, 'Program Leader', 0, 0, 'L');
$pdf->Cell(60, 3.5, ':', 0, 1, 'L');

$pdf->Cell(40, 3.5, 'Project Leader', 0, 0, 'L');
$pdf->Cell(60, 3.5, ': ' . $project_leader, 0, 1, 'L');

$pdf->SetXY(130, 62.5);
$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell(70, 3.5, 'Counterpart Funding', 'B', 0, 'C');

$pdf->SetXY(80, 63.5);
$pdf->Cell(70, 3.5, 'DOST', 0, 0, 'C');

$pdf->SetXY(130, 66);
$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell(35, 3.5, 'Implementing Agency', 0, 0, 'C');

$pdf->SetXY(165, 66);
$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell(35, 3.5, 'Cooperating Agency', 0, 0, 'C');

$pdf->SetFont('Arial', '', 7);
$pdf->SetXY(100, 70);
$pdf->Cell(5, 3.5, 'P' , 0, 1, 'L');

$pdf->SetXY(130, 70);
$pdf->Cell(5, 3.5, 'P' , 0, 1, 'L');

$pdf->SetXY(160, 70);
$pdf->Cell(5, 3.5, 'P' , 0, 1, 'L');

$pdf->SetXY(10, 70);
$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell(5, 3.5, 'I.', 0, 0, 'L');
$pdf->Cell(60, 3.5, 'Personal Services' , 0, 1, 'L');

$pdf->SetFont('Arial', '', 7);
$pdf->SetXY(10, 73.5);
$pdf->Cell(5, 3.5, '', 0, 0, 'L');
$pdf->Cell(15, 3.5, 'Direct Cost' , 'B', 1, 'L');

$pdf->SetFont('Arial', 'I', 7);
$pdf->Cell(5, 3.5, '', 0, 0, 'L');
$pdf->Cell(15, 3.5, 'Salaries' , 0, 1, 'L');
$pdf->Cell(5, 3.5, '', 0, 0, 'L');
$pdf->Cell(15, 3.5, 'Honoraria' , 0, 1, 'L');

$pdf->SetFont('Arial', '', 7);
$pdf->Cell(5, 3.5, '', 0, 0, 'L');
$pdf->Cell(15, 3.5, 'Indirect Cost' , 'B', 1, 'L');

$pdf->SetFont('Arial', 'IB', 7);
$pdf->Cell(5, 3.5, '', 0, 0, 'L');
$pdf->Cell(15, 3.5, '(Implementing Agency)' , 0, 1, 'L');

$pdf->SetFont('Arial', 'I', 7);
$pdf->Cell(5, 3.5, '', 0, 0, 'L');
$pdf->Cell(15, 3.5, 'Salaries' , 0, 1, 'L');
$pdf->Cell(5, 3.5, '', 0, 0, 'L');
$pdf->Cell(15, 3.5, 'Honoraria' , 0, 1, 'L');

$pdf->SetFont('Arial', 'IB', 7);
$pdf->Cell(5, 3.5, '', 0, 0, 'L');
$pdf->Cell(15, 3.5, '(Monitoring Agency)' , 0, 1, 'L');

$pdf->SetFont('Arial', 'I', 7);
$pdf->Cell(5, 3.5, '', 0, 0, 'L');
$pdf->Cell(15, 3.5, 'Salaries' , 0, 1, 'L');
$pdf->Cell(5, 3.5, '', 0, 0, 'L');
$pdf->Cell(15, 3.5, 'Honoraria' , 0, 1, 'L');

//LINE IN HONORARIA
$pdf->SetFont('Arial', '', 7);
$pdf->SetXY(105, 107);
$pdf->Cell(25, 3.5, '' , 'B', 1, 'L');

$pdf->SetXY(135, 107);
$pdf->Cell(25, 3.5, '' , 'B', 1, 'L');

$pdf->SetXY(165, 107);
$pdf->Cell(25, 3.5, '' , 'B', 1, 'L');

//P IN HONORARIA
$pdf->SetFont('Arial', 'B', 7);
$pdf->SetXY(40, 110);
$pdf->Cell(20, 3.5, 'Sub-total for PS' , 0, 1, 'L');

//P IN HONORARIA
$pdf->SetFont('Arial', '', 7);
$pdf->SetXY(100, 110);
$pdf->Cell(5, 3.5, 'P' , 0, 1, 'L');

$pdf->SetXY(130, 110);
$pdf->Cell(5, 3.5, 'P' , 0, 1, 'L');

$pdf->SetXY(160, 110);
$pdf->Cell(5, 3.5, 'P' , 0, 1, 'L');

//II.
$pdf->SetXY(10, 115);
$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell(5, 3.5, 'II.', 0, 0, 'L');
$pdf->Cell(60, 3.5, 'Maintenance and Other Operating Expenses' , 0, 1, 'L');

$pdf->SetFont('Arial', '', 7);
$pdf->SetXY(10, 118.5);
$pdf->Cell(5, 3.5, '', 0, 0, 'L');
$pdf->Cell(15, 3.5, 'Direct Cost' , 'B', 1, 'L');

$pdf->Cell(5, 3.5, '', 0, 0, 'L');
$pdf->Cell(15, 3.5, 'Traveling Expenses' , 0, 1, 'L');



$pdf->Output();
exit;
?>