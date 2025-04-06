<?php
include('db_connect.php'); // Ensure database connection

if (isset($_GET['id'])) {
    // Get the student ID
    $studentId = $_GET['id'];

    // Fetch the student details from the database
    $stmt = $dbh->prepare("SELECT * FROM tblstudents WHERE id = ?");
    $stmt->execute([$studentId]);
    $student = $stmt->fetch(PDO::FETCH_ASSOC);
    
    $selectedBranch = $student['Branch'];
    $selectedSemester = $student['Semester'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Student Details</title>
    <link rel="stylesheet" href="student.css">
    <link rel="icon" type="image/png" href="eatm_logo.png">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<div class="dashboard-container">
        <header>
            <h2>Welcome to student details update page</h2>
            <form action="logout.php" method="post">
                <button type="submit" class="logout-btn">Logout</button>
            </form>
        </header>
        <nav>
            <ul>
                <li><a href="class.html">Classes</a></li>
                <li><a href="student.php">Students</a></li>
                <li><a href="subject.php">Subjects</a></li>
                <li><a href="mark.php">Marks</a></li>
            </ul>
        </nav>
    </div>
    <div class="container">
        <h2 class="title">Update Student Details</h2>
        <form action="update_student_process.php" method="POST">
            <input type="hidden" name="id" value="<?php echo $student['id']; ?>">
            <div class="form-group">
                <label>Full Name:</label>
                <input type="text" name="fullname" class="form-control" value="<?php echo $student['StudentName']; ?>" required>
            </div>
            <div class="form-group">
                <label>Roll ID:</label>
                <input type="text" name="rollid" class="form-control" value="<?php echo $student['RollId']; ?>" required>
            </div>
            <div class="form-group">
                <label>Email ID:</label>
                <input type="email" name="emailid" class="form-control" value="<?php echo $student['StudentEmail']; ?>" required>
            </div>
            <div class="form-group">
                <label>Gender:</label><br>
                <input type="radio" name="gender" value="Male" <?php if($student['Gender'] == 'Male') echo 'checked'; ?> required> Male
                <input type="radio" name="gender" value="Female" <?php if($student['Gender'] == 'Female') echo 'checked'; ?> required> Female
                <input type="radio" name="gender" value="Other" <?php if($student['Gender'] == 'Other') echo 'checked'; ?> required> Other
            </div>
            <div class="form-group">
                <label>Branch:</label>
                <select name="branch" id="branch" class="form-control" required>
                    <option value="">Select Branch</option>
                    <?php
                    $branches = $dbh->query("SELECT DISTINCT Branch FROM tblclasses")->fetchAll(PDO::FETCH_COLUMN);
                    foreach ($branches as $branch) {
                        echo "<option value='$branch' ".($student['Branch'] == $branch ? 'selected' : '').">$branch</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label>Semester:</label>
                <select name="semester" id="semester" class="form-control" required>
                    <option value="">Select Semester</option>
                    <!-- Populate semesters dynamically here -->
                </select>
            </div>
            <div class="form-group">
                <label>Section:</label>
                <input type="text" name="section" class="form-control" value="<?php echo $student['Section']; ?>" required>
            </div>
            <div class="form-group">
                <label>Date of Birth:</label>
                <input type="date" name="dob" class="form-control" value="<?php echo $student['DOB']; ?>" required>
            </div>
            <button type="submit" name="update" class="btn btn-primary">Update Student</button>
        </form>
    </div>

    <script>
        // Fetch semesters based on selected branch on page load
        $(document).ready(function() {
            // Set the selected branch and trigger semester load
            var selectedBranch = "<?php echo $selectedBranch; ?>";
            var selectedSemester = "<?php echo $selectedSemester; ?>";

            // Load semesters when page loads
            loadSemesters(selectedBranch, selectedSemester);
            
            // Event listener for branch selection
            $('#branch').change(function() {
                var branch = $(this).val(); // Get selected branch
                loadSemesters(branch, ''); // Reload semester dropdown based on branch
            });

            // Function to load semesters for the selected branch
            function loadSemesters(branch, selectedSemester) {
                $.ajax({
                    url: 'fetch_semester.php', // Server script to fetch semesters
                    type: 'POST',
                    data: {branch: branch},
                    success: function(response) {
                        $('#semester').html(response); // Populate semesters dropdown
                        
                        // Set the selected semester (if any)
                        if (selectedSemester) {
                            $('#semester').val(selectedSemester);
                        }
                    },
                    error: function() {
                        alert('Error fetching semesters. Please try again.');
                    }
                });
            }
        });
    </script>
</body>
</html>
