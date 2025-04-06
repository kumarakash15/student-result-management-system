<?php 
include('db_connect.php'); // Ensure database connection

// Fetch all students from the database
$students = $dbh->query("SELECT * FROM tblstudents ORDER BY RegDate DESC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Student Registration</title>
    <link rel="icon" type="image/png" href="eatm_logo.png">
    <link rel="stylesheet" href="student.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="dashboard-container">
        <header>
            <h2>Welcome to student registration page</h2>
            <form action="logout.php" method="POST">
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
        <?php
        if (isset($_GET['message']) && $_GET['message'] == 'success') {
            echo "<div class='alert-success'>Student added successfully!</div>";
        }
        ?>

        <h2 class="title">Student Registration Form</h2>
        <form action="add_student.php" method="post">
            <div class="form-group">
                <label>Full Name:</label>
                <input type="text" name="fullname" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Roll ID:</label>
                <input type="text" name="rollid" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Email ID:</label>
                <input type="email" name="emailid" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Gender:</label><br>
                <input type="radio" name="gender" value="Male" required> Male
                <input type="radio" name="gender" value="Female" required> Female
                <input type="radio" name="gender" value="Other" required> Other
            </div>
            <div class="form-group">
                <label>Branch:</label>
                <select name="branch" id="branch" class="form-control" required>
                    <option value="">Select Branch</option>
                    <?php
                    $branches = $dbh->query("SELECT DISTINCT Branch FROM tblclasses")->fetchAll(PDO::FETCH_COLUMN);
                    foreach ($branches as $branch) {
                        echo "<option value='$branch'>$branch</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label>Semester:</label>
                <select name="semester" id="semester" class="form-control" required>
                    <option value="">Select Semester</option>
                </select>
            </div>
            <div class="form-group">
                <label>Section:</label>
                <input type="text" name="section" id="section" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Date of Birth:</label>
                <input type="date" name="dob" class="form-control" required>
            </div>
            <button type="submit" name="submit" class="btn btn-primary">Add Student</button>
        </form>
    </div>

    <h2 class="title">Student Records</h2>
    <div class="table-container">
        <table class="student-table">
            <thead>
                <tr>
                    <th>Full Name</th>
                    <th>Roll ID</th>
                    <th>Email</th>
                    <th>Gender</th>
                    <th>DOB</th>
                    <th>Branch</th>
                    <th>Semester</th>
                    <th>Section</th>
                    <th>Registration Date</th>
                    <th>Update</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($students as $student): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($student['StudentName']); ?></td>
                        <td><?php echo htmlspecialchars($student['RollId']); ?></td>
                        <td><?php echo htmlspecialchars($student['StudentEmail']); ?></td>
                        <td><?php echo htmlspecialchars($student['Gender']); ?></td>
                        <td><?php echo htmlspecialchars($student['DOB']); ?></td>
                        <td><?php echo htmlspecialchars($student['Branch']); ?></td>
                        <td><?php echo htmlspecialchars($student['Semester']); ?></td>
                        <td><?php echo htmlspecialchars($student['Section']); ?></td>
                        <td><?php echo htmlspecialchars($student['RegDate']); ?></td>
                        <td><a href="update_student.php?id=<?php echo $student['id']; ?>" class="btn-update">Update</a></td>
                        <td><a href="delete_student.php?id=<?php echo $student['id']; ?>" class="btn-delete">Delete</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Footer now placed at the end of the table -->
    <footer>
        <p>&copy; Bug Hunter. All rights reserved.</p> 
    </footer>

    <script>
        // Event listener for branch selection
        $('#branch').change(function() {
            var branch = $(this).val();

            // AJAX request to fetch semesters based on branch
            $.ajax({
                url: 'fetch_semester.php',
                type: 'POST',
                data: {branch: branch},
                success: function(response) {
                    $('#semester').html(response);
                },
                error: function() {
                    alert('Error fetching semesters. Please try again.');
                }
            });
        });
    </script>

</body>
</html>
