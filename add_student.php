<?php
include('db_connect.php'); // Ensure database connection

if (isset($_POST['submit'])) {
    $StudentName = $_POST['fullname'];
    $RollId = $_POST['rollid'];
    $StudentEmail = $_POST['emailid'];
    $Gender = $_POST['gender'];
    $DOB = $_POST['dob'];
    $Branch = $_POST['branch'];
    $Semester = $_POST['semester'];
    $Section = trim($_POST['section']); // Ensure input is clean

    try {
        // Insert Query
        $sql = "INSERT INTO tblstudents (StudentName, RollId, StudentEmail, Gender, DOB, Branch, Semester, Section, RegDate) 
        VALUES (:StudentName, :RollId, :StudentEmail, :Gender, :DOB, :Branch, :Semester, :Section, NOW())";

        $query = $dbh->prepare($sql);
        $query->bindParam(':StudentName', $StudentName, PDO::PARAM_STR);
        $query->bindParam(':RollId', $RollId, PDO::PARAM_STR);
        $query->bindParam(':StudentEmail', $StudentEmail, PDO::PARAM_STR);
        $query->bindParam(':Gender', $Gender, PDO::PARAM_STR);
        $query->bindParam(':DOB', $DOB, PDO::PARAM_STR);
        $query->bindParam(':Branch', $Branch, PDO::PARAM_STR);
        $query->bindParam(':Semester', $Semester, PDO::PARAM_STR);
        $query->bindParam(':Section', $Section, PDO::PARAM_STR);

        if ($query->execute()) {
            header("Location: student.php?message=success");
            exit();
        } else {
            echo "Error: Could not insert data!";
        }
    } catch (PDOException $e) {
        echo "Database Error: " . $e->getMessage();
    }
}

?>
