<?php
include('db_connect.php'); // Include the database connection

$student = null; // Initialize student variable
$previousMarks = []; // Array to hold previously entered marks

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['search'])) {
    $rollid = $_POST['rollid'] ?? ''; // Get the roll ID from the POST data

    if ($rollid) {
        try {
            // Fetch student details using the entered roll ID
            $stmt = $dbh->prepare("SELECT * FROM tblstudents WHERE RollId = :rollid");
            $stmt->execute([':rollid' => $rollid]);
            $student = $stmt->fetch(PDO::FETCH_ASSOC); // Get student details
            
            if (!$student) {
                echo "<script>alert('Student not found!');</script>";
            }

            // Fetch previously entered marks with subject name
            $stmt = $dbh->prepare("SELECT SubName, SubCode, Mark FROM tblmark WHERE RollId = :rollid");
            $stmt->execute([':rollid' => $rollid]);
            $previousMarks = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Database Error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Student Marks</title>
    <link rel="icon" type="image/png" href="eatm_logo.png">
    <link rel="stylesheet" href="marks.css">
</head>
<body>
    <div class="dashboard-container">
        <header>
            <h2>Welcome</h2>
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
    </div>
    <div class="container">
        <h2>Enter Student Registration Number</h2>
        <form method="post">
            <input type="text" name="rollid" placeholder="Enter Roll Number" required>
            <button type="submit" name="search">Search</button>
        </form>

        <?php if ($student) { ?>
            <h2>Student Details</h2>
            <p><strong>Name:</strong> <?php echo htmlspecialchars($student['StudentName']); ?></p>
            <p><strong>Roll ID:</strong> <?php echo htmlspecialchars($student['RollId']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($student['StudentEmail']); ?></p>
            <p><strong>Gender:</strong> <?php echo htmlspecialchars($student['Gender']); ?></p>
            <p><strong>DOB:</strong> <?php echo htmlspecialchars($student['DOB']); ?></p>
            <p><strong>Branch:</strong> <?php echo htmlspecialchars($student['Branch']); ?></p>
            <p><strong>Semester:</strong> <?php echo htmlspecialchars($student['Semester']); ?></p>
            <p><strong>Section:</strong> <?php echo htmlspecialchars($student['Section']); ?></p>

            <h2>Enter Marks</h2>
            <form method="post" action="add_mark.php">
                <input type="hidden" name="rollid" value="<?php echo htmlspecialchars($student['RollId']); ?>">
                <input type="hidden" name="semester" value="<?php echo htmlspecialchars($student['Semester']); ?>">

                <table border="1">
                    <thead>
                        <tr>
                            <th>Subject Name</th>
                            <th>Subject Code</th>
                            <th>Full Marks</th>
                            <th>Enter Marks</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $stmt = $dbh->prepare("SELECT * FROM tblsubject WHERE Branch = :branch AND Sem = :semester");
                        $stmt->execute([':branch' => $student['Branch'], ':semester' => $student['Semester']]);
                        $subjects = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        
                        foreach ($subjects as $subject) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($subject['SubName']) . "</td>";
                            echo "<td>" . htmlspecialchars($subject['SubCode']) . "</td>";
                            echo "<td>50</td>";  // Assuming full marks are always 50
                            echo "<td><input type='number' name='marks[{$subject['SubCode']}]' min='0' max='50' required></td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>

                <button type="submit" name="submit">Submit Marks</button>
            </form>

            <!-- Display previous marks -->
            <h2>Previous Marks</h2>
            <?php if (!empty($previousMarks)) { ?>
                <table border="1">
                    <thead>
                        <tr>
                            <th>Subject Name</th>
                            <th>Subject Code</th>
                            <th>Marks Obtained</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($previousMarks as $mark) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($mark['SubName']) . "</td>";
                            echo "<td>" . htmlspecialchars($mark['SubCode']) . "</td>";
                            echo "<td>" . htmlspecialchars($mark['Mark']) . "</td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            <?php } else { ?>
                <p>No previous marks found.</p>
            <?php } ?>
        <?php } ?>
    </div>
    <!-- footer -->
    <footer>
        <p>&copy; Bug Hunter. All rights reserved.</p> 
    </footer>
</body>
</html>
