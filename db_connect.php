<?php
$host = 'localhost'; // Your database host
$dbname = 'rms'; // Your database name
$username = 'root'; // Your database username
$password = ''; // Your database password

try {
    $dbh = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Declare global $dbh so it is accessible in other files
    global $dbh;
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
