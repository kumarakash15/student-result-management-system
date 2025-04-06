<?php
// delete_student.php
include('db_connect.php'); // Ensure database connection

if (isset($_GET['id'])) {
    // Get the student ID
    $studentId = $_GET['id'];

    // Delete the student record from the database
    $stmt = $dbh->prepare("DELETE FROM tblstudents WHERE id = ?");
    $stmt->execute([$studentId]);

    // Redirect to the student page with a success message
    header('Location: student.php?message=deleted');
}
?>
