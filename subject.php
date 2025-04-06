<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Subject Creation</title>
    <link rel="icon" type="image/png" href="eatm_logo.png">
    <link rel="stylesheet" href="subject.css">
    <script>
        function confirmDelete(id) {
            if (confirm("Are you sure you want to delete this subject?")) {
                window.location.href = "delete_subject.php?id=" + id;
            }
        }
    </script>
</head>
<body>
    <div class="dashboard-container">
        <header>
            <h2>Welcome to subject Creation page</h2>
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
        <h2>Subject Creation</h2>

        <form method="post" action="create_subject.php">
            <label for="class">Select Class</label>
            <select name="class_id" id="class" required>
                <option value="">Select Class</option>
                <?php
                include('db_connect.php');
                try {
                    $sql = "SELECT id, Branch, Semester FROM tblclasses";
                    $query = $dbh->prepare($sql);
                    $query->execute();
                    $results = $query->fetchAll(PDO::FETCH_OBJ);

                    if ($query->rowCount() > 0) {
                        foreach ($results as $result) {
                            echo "<option value='" . htmlspecialchars($result->id) . "'>" . 
                                 htmlspecialchars($result->Branch) . " - Semester " . 
                                 htmlspecialchars($result->Semester) . 
                                 "</option>";
                        }
                    }
                } catch (PDOException $e) {
                    echo "<option value=''>Error fetching classes: " . $e->getMessage() . "</option>";
                }
                ?>
            </select>

            <label for="subjectname">Subject Name</label>
            <input type="text" name="subjectname" id="subjectname" placeholder="Enter Subject Name" required>

            <label for="subjectcode">Subject Code</label>
            <input type="text" name="subjectcode" id="subjectcode" placeholder="Enter Subject Code" required>

            <button type="submit" name="submit">Submit</button>
        </form>

        <!-- Success message -->
        <?php if (isset($_GET['message']) && $_GET['message'] == 'success') { ?>
            <script>alert("Data inserted successfully!");</script>
        <?php } ?>

        <h2>Subjects List</h2>
        <table border="1">
            <thead>
                <tr>
                    <th>Branch</th>
                    <th>Semester</th>
                    <th>Subject Name</th>
                    <th>Subject Code</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                try {
                    $sql = "SELECT id, Branch, Sem AS Semester, SubName, SubCode FROM tblsubject";               
                    $query = $dbh->prepare($sql);
                    $query->execute();
                    $subjects = $query->fetchAll(PDO::FETCH_OBJ);
                    
                    if ($query->rowCount() > 0) {
                        foreach ($subjects as $subject) {
                            echo "<tr>
                                    <td>" . htmlspecialchars($subject->Branch) . "</td>
                                    <td>" . htmlspecialchars($subject->Semester) . "</td>
                                    <td>" . htmlspecialchars($subject->SubName) . "</td>
                                    <td>" . htmlspecialchars($subject->SubCode) . "</td>
                                    <td>
                                        <button onclick='confirmDelete(" . $subject->id . ")'>Delete</button>
                                    </td>
                                </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5'>No subjects found</td></tr>";
                    }
                } catch (PDOException $e) {
                    echo "<tr><td colspan='5'>Error fetching subjects: " . $e->getMessage() . "</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    <footer>
        <p>&copy; Bug Hunter. All rights reserved.</p> 
    </footer>
</body>
</html>
