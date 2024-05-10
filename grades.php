<?php
session_start();
function myMessage() {
    echo "Hello world!";
  }
// Check if user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: index.php");
    exit;
}

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: " . ($_SESSION['user_type'] === 'student' ? 'student_login.html' : 'teacher_login.html'));
    exit;
}

// Database connection
$servername = "localhost";
$username = "root"; // MySQL username
$password = ""; // No password
$dbname = "student_grading_system";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Determine user type (student or teacher)
$user_type = $_SESSION['user_type'];
$user_id = $_SESSION['user_id'];

// Fetch user's name
if ($user_type === 'student') {
    $sql = "SELECT student_name FROM students WHERE student_id = $user_id";
} elseif ($user_type === 'teacher') {
    $sql = "SELECT teacher_name FROM teachers WHERE teacher_id = $user_id";
}

$name_result = $conn->query($sql);
if ($name_result->num_rows > 0) {
    $row = $name_result->fetch_assoc();
    $user_name = $row['student_name'] ?? $row['teacher_name'];
} else {
    $user_name = 'Unknown';
}

// Fetch and display grades based on user type
if ($user_type === 'student') {
    // Fetch and display grades for the logged-in student
    $sql = "SELECT courses.course_name, grades.midterm_grade, grades.final_grade, grades.calculated_grade
            FROM grades
            INNER JOIN courses ON grades.course_id = courses.course_id
            WHERE grades.student_id = $user_id";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Grades</title>
            <link rel="stylesheet" href="styles.css">
        </head>
        <body>
            <div class="container">
                <h2>Grades</h2>
                <div class="header">
                    <div class="left">
                        <span>Welcome, <?php echo $user_name; ?>!</span>
                    </div>
                    <div class="right">
                        <a href="?logout" class="logout-btn">Logout</a>
                    </div>
                </div>
                <table>
                    <tr>
                        <th>Course Name</th>
                        <th>Midterm Grade</th>
                        <th>Final Grade</th>
                        <th>Calculated Grade</th>
                        <th>Letter Grade</th>
                    </tr>
                    <?php
                    // Changing color depending on the calculated grade
                    while ($row = $result->fetch_assoc()) {
                        if ($row["calculated_grade"] >=88){
                            echo '<tr style="background-color: green;">';
                            echo '<td>'.$row["course_name"].'</td>';
                            echo '<td>'.$row["midterm_grade"].'</td>';
                            echo '<td>'.$row["final_grade"].'</td>';
                            echo '<td>'.$row["calculated_grade"].'</td>';
                            echo '<td>AA</td>
                            </tr>';
                            }
                        else if ($row["calculated_grade"] >=81){
                            echo '<tr style="background-color: #8bc34a;">';
                            echo '<td>'.$row["course_name"].'</td>';
                            echo '<td>'.$row["midterm_grade"].'</td>';
                            echo '<td>'.$row["final_grade"].'</td>';
                            echo '<td>'.$row["calculated_grade"].'</td>';
                            echo '<td>BA</td>
                            </tr>';
                            }
                        else if ($row["calculated_grade"] >=74){
                            echo '<tr style="background-color: #cddc39;">';
                            echo '<td>'.$row["course_name"].'</td>';
                            echo '<td>'.$row["midterm_grade"].'</td>';
                            echo '<td>'.$row["final_grade"].'</td>';
                            echo '<td>'.$row["calculated_grade"].'</td>';
                            echo '<td>BB</td>
                            </tr>';
                            }
                        else if ($row["calculated_grade"] >=67){
                            echo '<tr style="background-color: #969c54;">';
                            echo '<td>'.$row["course_name"].'</td>';
                            echo '<td>'.$row["midterm_grade"].'</td>';
                            echo '<td>'.$row["final_grade"].'</td>';
                            echo '<td>'.$row["calculated_grade"].'</td>';
                            echo '<td>CB</td>
                            </tr>';
                            }
                        else if ($row["calculated_grade"] >=60){
                            echo '<tr style="background-color: #ce5942;">';
                            echo '<td>'.$row["course_name"].'</td>';
                            echo '<td>'.$row["midterm_grade"].'</td>';
                            echo '<td>'.$row["final_grade"].'</td>';
                            echo '<td>'.$row["calculated_grade"].'</td>';
                            echo '<td>CC</td>
                            </tr>';
                            }
                        else if ($row["calculated_grade"] >=53){
                            echo '<tr style="background-color: red;">';
                            echo '<td>'.$row["course_name"].'</td>';
                            echo '<td>'.$row["midterm_grade"].'</td>';
                            echo '<td>'.$row["final_grade"].'</td>';
                            echo '<td>'.$row["calculated_grade"].'</td>';
                            echo '<td>DC</td>
                            </tr>';
                            }
                        else if ($row["calculated_grade"] >=46){
                            echo '<tr style="background-color: red;">';
                            echo '<td>'.$row["course_name"].'</td>';
                            echo '<td>'.$row["midterm_grade"].'</td>';
                            echo '<td>'.$row["final_grade"].'</td>';
                            echo '<td>'.$row["calculated_grade"].'</td>';
                            echo '<td>DD</td>
                            </tr>';
                            }
                        ?>
                        
                        
                        <?php
                    }
                    ?>
                </table>
            </div>
        </body>
        </html>
        <?php
    } else {
        echo "No grades found.";
    }
} elseif ($user_type === 'teacher') {
    // Fetch and display grades for the selected course taught by the logged-in teacher
    if(isset($_GET['course_id'])) {
        $course_id = $_GET['course_id'];
        $sql = "SELECT students.student_name, courses.course_name, grades.midterm_grade, grades.final_grade, grades.calculated_grade, grades.student_id
            FROM grades
            INNER JOIN students ON grades.student_id = students.student_id
            INNER JOIN courses ON grades.course_id = courses.course_id
            WHERE courses.teacher_id = $user_id AND grades.course_id = $course_id";

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            ?>
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Grades</title>
                <link rel="stylesheet" href="styles.css">
                <script>
                    function confirmUpdate(studentId, courseId) {
                        var confirmMsg = "Are you sure you want to update the grades for this student?";
                        if (confirm(confirmMsg)) {
                            var midtermGrade = document.querySelector('input[name="midterm_grade_' + studentId + '"]').value;
                            var finalGrade = document.querySelector('input[name="final_grade_' + studentId + '"]').value;

                            // AJAX request to update grades
                            var xhr = new XMLHttpRequest();
                            xhr.onreadystatechange = function() {
                                if (xhr.readyState === XMLHttpRequest.DONE) {
                                    if (xhr.status === 200) {
                                        // Handle success
                                        console.log('Grades updated successfully');
                                        location.reload(); // Reload the page after successful update
                                    } else {
                                        // Handle error
                                        console.error('Failed to update grades');
                                        alert('Failed to update grades');
                                    }
                                }
                            };
                            xhr.open("POST", "update_grades.php", true);
                            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                            xhr.send("student_id=" + studentId + "&course_id=" + courseId + "&midterm_grade=" + midtermGrade + "&final_grade=" + finalGrade);
                        }
                    }
                </script>
            </head>
            <body>
                <div class="container">
                    <h2>Grades</h2>
                    <div class="header">
                        <div class="left">
                            <span>Welcome, <?php echo $user_name; ?>!</span>
                        </div>
                        <div class="right">
                            <a href="?logout" class="logout-btn">Logout</a>
                        </div>
                    </div>
                    <table>
                        <tr>
                            <th>Student Name</th>
                            <th>Course Name</th>
                            <th>Midterm Grade</th>
                            <th>Final Grade</th>
                            <th>Calculated Grade</th>
                            <th>Update Grades</th>
                        </tr>
                        <?php
                        while ($row = $result->fetch_assoc()) {
                            ?>
                            <tr>
                                <td><?php echo $row["student_name"]; ?></td>
                                <td><?php echo $row["course_name"]; ?></td>
                                <td><input type="text" name="midterm_grade_<?php echo $row['student_id']; ?>" value="<?php echo $row["midterm_grade"]; ?>"></td>
                                <td><input type="text" name="final_grade_<?php echo $row['student_id']; ?>" value="<?php echo $row["final_grade"]; ?>"></td>
                                <td><?php echo $row["calculated_grade"]; ?></td>
                                <td><button onclick="confirmUpdate(<?php echo $row['student_id']; ?>, <?php echo $course_id; ?>)">Update</button></td>
                            </tr>
                            <?php
                        }
                        ?>
                    </table>
                </div>
            </body>
            </html>
            <?php
        } else {
            echo "No grades found for this course.";
        }
    } else {
        echo "No course selected.";
    }

    //"Return to Courses" button
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Grades</title>
        <link rel="stylesheet" href="styles.css">
    </head>
    <body>
        <div class="container">
            <a href="teacher_courses.php" class="return-btn">Return to Courses</a>
        </div>
    </body>
    </html>
    <?php
}

$conn->close();
?>
