<?php
include('db_connect.php'); // Ensure database connection

if (isset($_POST['update'])) {
    // Get form data
    $id = $_POST['id'];
    $fullname = $_POST['fullname'];
    $rollid = $_POST['rollid'];
    $emailid = $_POST['emailid'];
    $gender = $_POST['gender'];
    $branch = $_POST['branch'];
    $semester = $_POST['semester'];
    $section = $_POST['section'];
    $dob = $_POST['dob'];

    // Update the student details in the database
    $stmt = $dbh->prepare("UPDATE tblstudents SET StudentName = ?, RollId = ?, StudentEmail = ?, Gender = ?, Branch = ?, Semester = ?, Section = ?, DOB = ? WHERE id = ?");
    $stmt->execute([$fullname, $rollid, $emailid, $gender, $branch, $semester, $section, $dob, $id]);

    // Redirect to the student page with a success message
    header('Location: student.php?message=updated');
}
?>
