<?php
session_start();
// Require the FPDF library from the fpdf folder
require 'fpdf/fpdf.php'; 

// Check if user is logged in and has a result
if (!isset($_SESSION['user_id']) || !isset($_SESSION['career_result'])) {
    header('Location: login.php');
    exit;
}

// Get data from session
$result_data = $_SESSION['career_result'];
$username = $_SESSION['username'];

// --- Create PDF ---
class PDF extends FPDF
{
    // Page header
    function Header()
    {
        // FPDF uses latin1 by default, we need to handle UTF-8 characters
        // This is a simple conversion for the title.
        // For full UTF-8 support, you would need to use a font with tFPDF or similar
        $title = 'Your Personalized Career Blueprint';
        $title = iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $title);
        
        $this->SetFont('Arial', 'B', 15);
        $this->Cell(0, 10, $title, 0, 1, 'C'); // Centered
        $this->Ln(10); // Line break
    }

    // Page footer
    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Page ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }

    // Helper function for titles
    function ChapterTitle($num, $label)
    {
        // Convert UTF-8
        $label = iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $label);

        $this->SetFont('Arial', 'B', 12);
        $this->SetFillColor(240, 240, 240); // Light grey background
        $this->Cell(0, 8, "$num. $label", 0, 1, 'L', true);
        $this->Ln(4);
    }

    // Helper function for body text
    function ChapterBody($body)
    {
         // Convert UTF-8
        $body = iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $body);
        
        $this->SetFont('Arial', '', 11);
        $this->MultiCell(0, 6, $body); // 0 width = full width
        $this->Ln();
    }

    // Helper for list items
    function ListItem($title, $description)
    {
        $title = iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $title);
        $description = iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $description);

        $this->SetFont('Arial', 'B', 11);
        $this->MultiCell(0, 6, "- " . $title);
        $this->SetFont('Arial', '', 11);
        $this->MultiCell(0, 6, $description);
        $this->Ln(3);
    }
}

// --- Create and Build the PDF ---
$pdf = new PDF();
$pdf->AliasNbPages(); // Allows for page numbering
$pdf->AddPage();

// 1. Blueprint Title
if (isset($result_data['personalizedBlueprint'])) {
    $title = iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $result_data['personalizedBlueprint']['title']);
    $intro = iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $result_data['personalizedBlueprint']['intro']);

    $pdf->SetFont('Arial', 'B', 14);
    $pdf->Cell(0, 8, $title, 0, 1, 'C');
    $pdf->SetFont('Arial', 'I', 11);
    $pdf->MultiCell(0, 6, $intro, 0, 'C');
    $pdf->Ln(8);
}

// 2. Career Path
if (isset($result_data['careerPath'])) {
    $pdf->ChapterTitle(1, $result_data['careerPath']['title']);
    $pdf->ChapterBody($result_data['careerPath']['description']);
}

// 3. Why It's a Good Fit
if (isset($result_data['whyFit'])) {
    $pdf->ChapterTitle(2, $result_data['whyFit']['title']);
    $pdf->ChapterBody($result_data['whyFit']['explanation']);
}

// 4. Actionable Steps
if (isset($result_data['actionableSteps'])) {
    $pdf->ChapterTitle(3, $result_data['actionableSteps']['title']);
    foreach ($result_data['actionableSteps']['steps'] as $step) {
        $pdf->ListItem($step['title'], $step['description']);
    }
    $pdf->Ln();
}

// 5. Alternative Path
if (isset($result_data['alternativePath'])) {
    $pdf->ChapterTitle(4, $result_data['alternativePath']['title']);
    $pdf->ChapterBody($result_data['alternativePath']['description']);
}

// 6. Location Advice
if (isset($result_data['locationAdvice'])) {
    $pdf->ChapterTitle(5, $result_data['locationAdvice']['title']);
    $pdf->ChapterBody($result_data['locationAdvice']['advice']);
}

// --- Output the PDF ---
// Note: FPDF has issues with non-ASCII filenames. We'll use a generic name.
$pdf->Output('D', 'career_blueprint.pdf'); // 'D' means force download
exit;
?>
