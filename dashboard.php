<?php
session_start();
require 'db_connect.php'; // Database connection

if (!isset($_SESSION['username'])) {
    header("Location: index.html");
    exit();
}

// Fetch counts from the correct tables
try {
    // Count total registered students from tblstudents
    $stmt = $dbh->query("SELECT COUNT(*) AS studentCount FROM tblstudents");
    $studentCount = $stmt->fetch(PDO::FETCH_ASSOC)['studentCount'];

    // Count total subjects from tblsubject
    $stmt = $dbh->query("SELECT COUNT(*) AS subjectCount FROM tblsubject");
    $subjectCount = $stmt->fetch(PDO::FETCH_ASSOC)['subjectCount'];

    // Count total departments from tblclasses
    $stmt = $dbh->query("SELECT COUNT(*) AS departmentCount FROM tblclasses");
    $departmentCount = $stmt->fetch(PDO::FETCH_ASSOC)['departmentCount'];

    // Count total results declared from tblmark
    $stmt = $dbh->query("SELECT COUNT(DISTINCT RollId) AS resultCount FROM tblmark WHERE Mark IS NOT NULL");
    $resultCount = $stmt->fetch(PDO::FETCH_ASSOC)['resultCount'];

} catch (Exception $e) {
    echo "Database Error: " . $e->getMessage();
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="icon" type="image/png" href="eatm_logo.png">
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>
    <div class="dashboard-container">
        <header>
            <h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
            <form action="logout.php" method="post">
                <button type="submit" class="logout-btn">Logout</button>
            </form>
        </header>
        <nav>
            <ul>
                <li><a href="home.html">Home</a></li>
                <li><a href="class.html">Classes</a></li>
                <li><a href="student.php">Students</a></li>
                <li><a href="subject.php">Subjects</a></li>
                <li><a href="mark.php">Marks</a></li>
            </ul>
        </nav>

        <main>
            <div class="dashboard-stats">
                <div class="stat-box blue">
                    <p>Registered Student</p>
                    <p><strong><?php echo $studentCount; ?></strong></p>
                </div>
                <div class="stat-box red">
                    <p>Subjects Listed</p>
                    <p><strong><?php echo $subjectCount; ?></strong></p>
                </div>
                <div class="stat-box yellow">
                    <p>Total Classes Listed</p>
                    <p><strong><?php echo $departmentCount; ?></strong></p>
                </div>
                <div class="stat-box green">
                    <p>Results Declared</p>
                    <p><strong><?php echo $resultCount; ?></strong></p>
                </div>
            </div>
        </main>

        <footer>
            <p>&copy; Bug Hunter. All rights reserved.</p> 
        </footer>
    </div>
</body>
</html>