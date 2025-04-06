<?php
session_start();
$con = new mysqli("localhost", "root", "", "rms");

// Check connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// Fetch all classes
$sql = "SELECT * FROM tblclasses";
$result = $con->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['id'] . "</td>";  
        echo "<td>" . $row['Branch'] . "</td>";  
        echo "<td>" . $row['Semester'] . "</td>"; 
        echo "<td><button onclick=\"showUpdateForm('" . $row['id'] . "', '" . addslashes($row['Branch']) . "', '" . $row['Semester'] . "')\">Update</button></td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='4'>No classes found</td></tr>";
}

$con->close();
?>
