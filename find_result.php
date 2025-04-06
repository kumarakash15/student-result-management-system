<?php
session_start();
require 'db_connect.php'; // Database connection
require('fpdf.php'); // Include FPDF library

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Fetch Roll ID from the form
    $rollId = $_POST['regd'];

    try {
        // Check if RollId exists in tblmark
        $stmt = $dbh->prepare("SELECT StudentName, RollId, StudentEmail, DOB, Branch, Semester, Section, Gender FROM tblmark WHERE RollId = :rollId LIMIT 1");
        $stmt->execute([':rollId' => $rollId]);
        $student = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($student) {
            // Display student details
            echo "<h2>Student Details</h2>";
            echo "<p><strong>Name:</strong> " . htmlspecialchars($student['StudentName']) . "</p>";
            echo "<p><strong>Roll ID:</strong> " . htmlspecialchars($student['RollId']) . "</p>";
            echo "<p><strong>Email:</strong> " . htmlspecialchars($student['StudentEmail']) . "</p>";
            echo "<p><strong>DOB:</strong> " . htmlspecialchars($student['DOB']) . "</p>";
            echo "<p><strong>Branch:</strong> " . htmlspecialchars($student['Branch']) . "</p>";
            echo "<p><strong>Semester:</strong> " . htmlspecialchars($student['Semester']) . "</p>";
            echo "<p><strong>Section:</strong> " . htmlspecialchars($student['Section']) . "</p>";
            echo "<p><strong>Gender:</strong> " . htmlspecialchars($student['Gender']) . "</p>";

            // Fetch subject marks and full marks
            $stmt = $dbh->prepare("SELECT SubName, SubCode, Mark, FullMark FROM tblmark WHERE RollId = :rollId");
            $stmt->execute([':rollId' => $rollId]);
            $marksResult = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($marksResult) {
                echo "<h2>Marks</h2>";
                echo "<table border='1' cellpadding='5' cellspacing='0' style='border-collapse: collapse;'>";
                echo "<thead><tr><th>Subject Name</th><th>Subject Code</th><th>Full Marks</th><th>Marks Obtained</th></tr></thead>";
                echo "<tbody>";

                $totalMarks = 0;
                $totalFullMarks = 0;

                foreach ($marksResult as $mark) {
                    // Display subject marks and full marks
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($mark['SubName']) . "</td>";
                    echo "<td>" . htmlspecialchars($mark['SubCode']) . "</td>";
                    echo "<td>" . htmlspecialchars($mark['FullMark']) . "</td>";
                    echo "<td>" . htmlspecialchars($mark['Mark']) . "</td>";
                    echo "</tr>";
                    // Accumulate total marks and full marks
                    $totalMarks += $mark['Mark'];
                    $totalFullMarks += $mark['FullMark'];
                }

                // Display total marks and total full marks
                echo "<tr><td colspan='2'><strong>Total</strong></td><td><strong>$totalFullMarks</strong></td></td><td><strong>$totalMarks</strong></td></tr>";
                echo "</tbody></table>";

                // Button to generate PDF
                echo "<form method='POST' action='generate_pdf.php'>";
                echo "<input type='hidden' name='rollId' value='" . htmlspecialchars($rollId) . "'>";
                echo "<button type='submit'>Generate PDF</button>";
                echo "</form>";
            } else {
                echo "<p>No marks found for this student.</p>";
            }
        } else {
            echo "<p style='color: red;'>Roll ID not found in the system.</p>";
        }
    } catch (Exception $e) {
        echo "Database Error: " . $e->getMessage();
    }
}
?>
