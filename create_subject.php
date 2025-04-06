<?php
session_start();
include('db_connect.php');

if (isset($_POST['submit'])) {
    $class_id = $_POST['class_id'];
    $subjectname = $_POST['subjectname'];
    $subjectcode = $_POST['subjectcode'];

    try {
        // Get Branch and Semester based on selected class_id
        $sql = "SELECT Branch, Semester FROM tblclasses WHERE id = :class_id";
        $query = $dbh->prepare($sql);
        $query->bindParam(':class_id', $class_id, PDO::PARAM_INT);
        $query->execute();
        $class = $query->fetch(PDO::FETCH_OBJ);

        if ($class) {
            $branch = $class->Branch;
            $sem = $class->Semester;

            // Insert into tblsubject (Fixed table name)
            $sql = "INSERT INTO tblsubject (Branch, Sem, SubName, SubCode) 
                    VALUES (:branch, :sem, :subjectname, :subjectcode)";
            $query = $dbh->prepare($sql);
            $query->bindParam(':branch', $branch, PDO::PARAM_STR);
            $query->bindParam(':sem', $sem, PDO::PARAM_INT);
            $query->bindParam(':subjectname', $subjectname, PDO::PARAM_STR);
            $query->bindParam(':subjectcode', $subjectcode, PDO::PARAM_STR);
            $query->execute();

            header("Location: subject.php?message=success");
            exit();
        } else {
            echo "<script>alert('Invalid class selection!'); window.location.href='subject.php';</script>";
        }
    } catch (PDOException $e) {
        echo "<script>alert('Error: " . $e->getMessage() . "'); window.location.href='subject.php';</script>";
    }
}
?>
