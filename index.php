<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Grading System</title>
    <link rel="stylesheet" href="styles.css?v=1.11">
    <script src="index.js?v=1.11"></script>
</head>
<body>
    <div class="container">
        <h2>Select Your Role</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <input type="radio" id="student" name="role" value="student" checked>
            <label for="student">Student</label><br>
            <input type="radio" id="teacher" name="role" value="teacher">
            <label for="teacher">Teacher</label><br><br>
            <input type="submit" value="Next">
        </form>
    </div>
    
    <?php
    session_start();
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $role = $_POST["role"];
        if ($role === "student") {
            header("Location: student_login.html");
            exit;
        } elseif ($role === "teacher") {
            header("Location: teacher_login.html");
            exit;
        }
    }
    ?>
    <div class="slider">
        <input type="button" onclick="left()" value="⇐">
        <img src="images/1.jpg" class="image" id="image">
        <input type="button" onclick="right()" value="⇒">
    </div>
</body>
</html>
