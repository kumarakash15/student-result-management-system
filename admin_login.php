<?php
session_start();
$con = new mysqli("localhost", "root", "", "rms");

if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST['username']) && !empty($_POST['password'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Prepare the statement to fetch the password from the database
        $stmt = $con->prepare("SELECT Password FROM admin WHERE UserName = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->bind_result($db_password);
        $stmt->fetch();
        $stmt->close();

        // Check if the password matches
        if ($db_password) {
            $hashed_password = md5($password); // Hash the entered password using MD5

            if ($hashed_password === $db_password) {  // Compare the hashed passwords
                $_SESSION['username'] = $username; // Set session variable
                header("Location: dashboard.php"); // Redirect to dashboard
                exit();
            } else {
                echo "<script>alert('Invalid Username or Password'); window.location.href='index.html';</script>";
            }
        } else {
            echo "<script>alert('Invalid Username or Password'); window.location.href='index.html';</script>";
        }
    } else {
        echo "<script>alert('Please enter Username and Password');</script>";
    }
}

$con->close();
?>
