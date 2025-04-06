<?php
session_start();
$con = new mysqli("localhost", "root", "", "rms");

// Check connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $id = intval($_POST['id']);
    $branch = $_POST['branch'];
    $semester = intval($_POST['semester']);

    $stmt = $con->prepare("UPDATE tblclasses SET Branch=?, Semester=? WHERE id=?");
    $stmt->bind_param("sii", $branch, $semester, $id);

    if ($stmt->execute()) {
        $message = "Class updated successfully";
    } else {
        $message = "Error updating class: " . $stmt->error;
    }

    $stmt->close();
    $con->close();

    header("Location: class.html?message=" . urlencode($message));
    exit();
}
?>
