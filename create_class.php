<?php 
session_start();
$con = new mysqli("localhost", "root", "", "rms");

// Check connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $branch = $_POST['branch'];
    $semester = intval($_POST['semester']); 

    $stmt = $con->prepare("INSERT INTO tblclasses (Branch, Semester) VALUES (?, ?)");
    $stmt->bind_param("si", $branch, $semester);

    if ($stmt->execute()) {
        $message = "Class added successfully";
    } else {
        $message = "Error adding class: " . $stmt->error;
    }

    $stmt->close();
    $con->close();

    header("Location: class.html?message=" . urlencode($message));
    exit();
}
?>
