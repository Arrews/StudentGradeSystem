<?php
session_start(); // Start the session

// Check if user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // Redirect to the login page if not logged in
    header("Location: index.php");
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

// Fetch courses taught by the teacher
$teacher_id = $_SESSION['user_id']; // Get the teacher ID from session
$sql = "SELECT course_id, course_name FROM courses WHERE teacher_id = $teacher_id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Teacher Courses</title>
        <link rel="stylesheet" href="styles.css">
    </head>
    <body>
        <div class="container">
            <h2>Your Courses</h2>
            <ul>
                <?php
                while ($row = $result->fetch_assoc()) {
                    echo "<li><a href='grades.php?course_id={$row['course_id']}'>{$row['course_name']}</a></li>";
                }
                ?>
            </ul>
            <a href="?logout" class="logout-btn">Logout</a>
        </div>
    </body>
    </html>
    <?php
} else {
    echo "No courses found.";
}

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit;
}

$conn->close();
?>
