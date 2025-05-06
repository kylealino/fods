<?php

require APPPATH . 'ThirdParty/fpdf/fpdf.php';

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
$pdf->Cell(60, 3.5, ':', 0, 1, 'L');

$pdf->Cell(40, 3.5, 'Project Title', 0, 0, 'L');
$pdf->Cell(60, 3.5, ':', 0, 1, 'L');

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
$pdf->Cell(60, 3.5, ':', 0, 1, 'L');

$pdf->SetXY(130, 62.5);
$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell(70, 3.5, 'Counterpart Funding', 'B', 0, 'C');

$pdf->SetXY(77, 63.5);
$pdf->Cell(70, 3.5, 'DOST', 0, 0, 'C');

$pdf->SetXY(130, 66);
$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell(35, 3.5, 'Implementing Agency', 0, 0, 'C');

$pdf->SetXY(165, 66);
$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell(35, 3.5, 'Cooperating Agency', 0, 0, 'C');



$pdf->Output();
exit;
?>