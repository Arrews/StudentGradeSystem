<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Database connection
    $servername = "localhost";
    $username = "root"; // MYSQL username
    $password = ""; // No password
    $dbname = "student_grading_system";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Fetch login credentials from form
    $teacher_id = $_POST["teacher_id"];
    $password = $_POST["password"];

    // Perform SQL query to check if the teacher exists and password is correct
    $sql = "SELECT * FROM Teachers WHERE teacher_id = '$teacher_id' AND login_pw = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        // Teacher exists and password is correct
        $_SESSION["loggedin"] = true;
        $_SESSION["user_type"] = "teacher";
        $_SESSION["user_id"] = $teacher_id;
        header("Location: teacher_courses.php"); // Redirect to teacher_courses.php
        exit;
    } else {
        // Teacher does not exist or password is incorrect
        echo '<script>alert("Invalid teacher ID or password.");</script>';
        echo '<script>window.location.href = "teacher_login.html";</script>'; // Redirect back to teacher_login.html
    }

    $conn->close();
} else {
    // If the request method is not POST, redirect back to the login page
    header("Location: teacher_login.html");
    exit;
}
?>
