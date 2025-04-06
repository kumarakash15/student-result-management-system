<?php
require('fpdf.php');
require 'db_connect.php'; // Include database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $rollId = $_POST['rollId'];

    try {
        // Fetch student details
        $stmt = $dbh->prepare("SELECT StudentName, RollId, StudentEmail, DOB, Branch, Semester, Section, Gender FROM tblmark WHERE RollId = :rollId LIMIT 1");
        $stmt->execute([':rollId' => $rollId]);
        $student = $stmt->fetch(PDO::FETCH_ASSOC);

        // Fetch subject marks along with full marks
        $stmt = $dbh->prepare("SELECT SubName, SubCode, Mark, FullMark FROM tblmark WHERE RollId = :rollId");
        $stmt->execute([':rollId' => $rollId]);
        $marksResult = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Create a new PDF instance
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);

        // Title
        $pdf->Cell(200, 10, 'Student Result Report', 0, 1, 'C');
        $pdf->Ln(10);

        // Student details
        $pdf->SetFont('Arial', '', 12);
        foreach ($student as $key => $value) {
            $pdf->Cell(50, 10, ucfirst($key) . ':', 0, 0);
            $pdf->Cell(0, 10, $value, 0, 1);
        }

        $pdf->Ln(10);
        $pdf->Cell(0, 10, 'Marks Obtained:', 0, 1, 'L');
        $pdf->Ln(5);

        // Table for marks
        $pdf->Cell(60, 10, 'Subject Name', 1);
        $pdf->Cell(40, 10, 'Subject Code', 1);
        $pdf->Cell(40, 10, 'Full Marks', 1);
        $pdf->Cell(40, 10, 'Marks Obtained', 1);
        $pdf->Ln();

        $totalMarks = 0;
        $totalFullMarks = 0;
        foreach ($marksResult as $mark) {
            $pdf->Cell(60, 10, $mark['SubName'], 1);
            $pdf->Cell(40, 10, $mark['SubCode'], 1);
            $pdf->Cell(40, 10, $mark['FullMark'], 1);
            $pdf->Cell(40, 10, $mark['Mark'], 1);
            $pdf->Ln();
            $totalMarks += $mark['Mark'];
            $totalFullMarks += $mark['FullMark'];
        }

        $pdf->Ln(5);
        $pdf->Cell(100, 10, 'Total:', 1);
        $pdf->Cell(40, 10, $totalFullMarks, 1);
        $pdf->Cell(40, 10, $totalMarks, 1);

        // Output the PDF
        $pdf->Output('D', 'student_result_' . $rollId . '.pdf');
    } catch (Exception $e) {
        echo "Database Error: " . $e->getMessage();
    }
}
?>