<?php
include('db_connect.php');

if (isset($_POST['submit'])) {
    $rollid = $_POST['rollid'];
    $semester = $_POST['semester'];
    $marks = $_POST['marks'];

    try {
        // Fetch student details
        $stmt = $dbh->prepare("SELECT StudentName, Branch, Section, DOB, Semester, Gender, StudentEmail 
                               FROM tblstudents WHERE RollId = :rollid");
        $stmt->execute([':rollid' => $rollid]);
        $student = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($student) {
            foreach ($marks as $subCode => $mark) {
                // Fetch subject name
                $stmt = $dbh->prepare("SELECT SubName FROM tblsubject WHERE SubCode = :subcode");
                $stmt->execute([':subcode' => $subCode]);
                $subject = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($subject) {
                    // Insert or update marks with full marks set to 50
                    $stmt = $dbh->prepare("
                        INSERT INTO tblmark (StudentName, RollId, Branch, Section, DOB, Semester, Gender, StudentEmail, SubCode, SubName, FullMark, Mark) 
                        VALUES (:studentname, :rollid, :branch, :section, :dob, :semester, :gender, :email, :subcode, :subname, :fullmarks, :marks)
                        ON DUPLICATE KEY UPDATE Mark = :marks
                    ");
                    $stmt->execute([
                        ':studentname' => $student['StudentName'],
                        ':rollid' => $rollid,
                        ':branch' => $student['Branch'],
                        ':section' => $student['Section'],
                        ':dob' => $student['DOB'],
                        ':semester' => $student['Semester'],
                        ':gender' => $student['Gender'],
                        ':email' => $student['StudentEmail'],
                        ':subcode' => $subCode,
                        ':subname' => $subject['SubName'],
                        ':fullmarks' => 50,  // Hardcoded full marks (50)
                        ':marks' => $mark
                    ]);
                }
            }
            echo "<script>alert('Marks submitted successfully!');</script>";
            echo "<script>window.location.href = 'mark.php';</script>";
        }
    } catch (PDOException $e) {
        echo "Database Error: " . $e->getMessage();
    }
}
?>
