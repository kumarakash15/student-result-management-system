<?php
session_start();
include('db_connect.php');

if (isset($_GET['id'])) {
    $subject_id = $_GET['id'];

    try {
        $sql = "DELETE FROM tblsubject WHERE id = :subject_id";
        $query = $dbh->prepare($sql);
        $query->bindParam(':subject_id', $subject_id, PDO::PARAM_INT);
        $query->execute();

        header("Location: subject.php?message=deleted");
        exit();
    } catch (PDOException $e) {
        echo "<script>alert('Error deleting subject: " . $e->getMessage() . "'); window.location.href='subject.php';</script>";
    }
} else {
    echo "<script>alert('Invalid request!'); window.location.href='subject.php';</script>";
}
?>
