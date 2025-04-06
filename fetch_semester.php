<?php
include('db_connect.php'); // Ensure database connection

if (isset($_POST['branch'])) {
    $branch = $_POST['branch'];

    // Fetch semesters based on the branch
    $stmt = $dbh->prepare("SELECT DISTINCT Semester FROM tblclasses WHERE Branch = ?");
    $stmt->execute([$branch]);
    $semesters = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Generate and send the options for the semester dropdown
    if ($semesters) {
        foreach ($semesters as $semester) {
            echo "<option value='{$semester['Semester']}'>{$semester['Semester']}</option>";
        }
    } else {
        echo "<option value=''>No semesters available</option>";
    }
}
?>
