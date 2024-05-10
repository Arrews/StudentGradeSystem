<?php
session_start();

// Database connection parameters
$servername = "localhost";
$username = "root"; // Username Root
$password = ""; // No Password
$dbname = "student_grading_system";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Create a database connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check the database connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Fetch login credentials from the form
    $student_id = $_POST["student_id"];
    $password = $_POST["password"];

    // Perform SQL query to check if the student exists and the password is correct
    $sql = "SELECT * FROM Students WHERE student_id = '$student_id' AND login_pw = '$password'";
    $result = $conn->query($sql);

    // Check if the query returned exactly one row
    if ($result->num_rows == 1) {
        // Student exists and password is correct
        $_SESSION["loggedin"] = true;
        $_SESSION["user_type"] = "student";
        $_SESSION["user_id"] = $student_id;
        header("Location: grades.php");
        exit;
    } else {
        // Student does not exist or password is incorrect
        echo '<script>alert("Invalid student ID or password.");</script>';
        echo '<script>window.location.href = "student_login.html";</script>'; // Redirect back to student_login.html
    }

    // Close the database connection
    $conn->close();
} else {
    // If the request method is not POST, redirect back to the login page
    header("Location: student_login.html");
    exit;
}
?>
