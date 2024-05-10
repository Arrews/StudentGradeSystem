<?php
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

// Handle update of grades
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $studentId = $_POST['student_id'];
    $courseId = $_POST['course_id']; // Added course ID
    $midtermGrade = $_POST['midterm_grade'];
    $finalGrade = $_POST['final_grade'];

    // Update grades in the database for the specific course of the student
    $sql = "UPDATE grades SET midterm_grade = '$midtermGrade', final_grade = '$finalGrade', calculated_grade = (midterm_grade * 0.4 + final_grade * 0.6) WHERE student_id = $studentId AND course_id = $courseId";

    if ($conn->query($sql) === TRUE) {
        echo "Grades updated successfully";
    } else {
        echo "Error updating grades: " . $conn->error;
    }
}

$conn->close();
?>