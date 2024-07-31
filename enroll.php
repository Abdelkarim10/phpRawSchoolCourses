<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'connection.php';
session_start();

// Check if the user is logged in and has the correct role (student)
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role_id']) || $_SESSION['role_id'] != 1) {
    echo "Access denied.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['enroll'])) {
        $course_id = $_POST['course_id'];
        $student_id = $_SESSION['user_id'];

        // Check for double enrollment
        $check_sql = "SELECT * FROM enrollments WHERE user_id = $student_id AND course_id = $course_id";
        $check_result = $conn->query($check_sql);

        if ($check_result->num_rows > 0) {
            $message = "You are already enrolled in this course.";
        } else {
            // Insert the enrollment record
            $sql = "INSERT INTO enrollments (user_id, course_id) VALUES ($student_id, $course_id)";
            $result = $conn->query($sql);

            if (!$result) {
                echo "Error: " . $conn->error;
            } else {
                $message = "Enrollment successful!";
            }
        }
    } elseif (isset($_POST['drop'])) {
        $enrollment_id = $_POST['enrollment_id'];

        // Delete the enrollment record
        $sql = "DELETE FROM enrollments WHERE id = $enrollment_id";
        $result = $conn->query($sql);

        if (!$result) {
            echo "Error: " . $conn->error;
        } else {
            $message = "Course dropped successfully!";
        }
    }
}

// Fetch available courses with teacher names
$sql = "SELECT courses.id, courses.course_name, users.name AS teacher_name 
        FROM courses 
        JOIN users ON courses.user_id = users.id";
$result = $conn->query($sql);

if (!$result) {
    die("Error fetching courses: " . $conn->error);
}

echo '<br><a href="logout.php" class="btn btn-secondary">Log Out</a><br><br><br>';
if (isset($message)) {echo "<p style='text-align: center;font-size:45px;'>$message</p>";}
echo "<h1>Enroll in a Course</h1>";
echo "<form method='POST'><br>";
echo "<div class='selecting'><p>Course:</p> <select name='course_id' class='form-select' required>";

while ($row = $result->fetch_assoc()) {
    echo "<option class='parag btn-lg dropdown-toggle' value='{$row['id']}'>{$row['course_name']} (Teacher: {$row['teacher_name']})</option>";
}

echo "</select><br><br>";
echo "<button type='submit' name='enroll' class='student'>Enroll</button>";
echo "</form>";

// Fetch enrolled courses for the student
$student_id = $_SESSION['user_id'];
$sql = "SELECT enrollments.id AS enrollment_id, courses.course_name, users.name AS teacher_name 
        FROM enrollments 
        JOIN courses ON enrollments.course_id = courses.id 
        JOIN users ON courses.user_id = users.id 
        WHERE enrollments.user_id = $student_id";
$result = $conn->query($sql);

if (!$result) {
    die("Error fetching enrolled courses: " . $conn->error);
}

echo "<h1 style='margin:30% 0 0 0;'>Wanna Drop some?</h1>";
echo "<form method='POST'><br><br>";
echo "<div class='selecting'><p>Your Courses:</p>";

while ($row = $result->fetch_assoc()) {
    echo "<div class='enrolled-course'>";
    echo "<span class='parag'>{$row['course_name']} (Teacher: {$row['teacher_name']})</span>";
    echo "<button type='submit' name='drop' value='{$row['enrollment_id']}' class='drop-button'>Drop</button>";
    echo "<input type='hidden' name='enrollment_id' value='{$row['enrollment_id']}'>";
    echo "</div><br>";
}

echo "</div></form>";
echo "<br><br><br><br><br>";
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

</body>
</html>

<style>
    body {
        overflow-x: hidden;
        height: 100vh;
        width: 100vw;
        background-image: linear-gradient(to left, #f39c12, #FFBF00);
    }

    .selecting {
        width: 40%;
        margin: 0 0 0 28%;
    }

    h1 {
       text-align: center;
        font-size: 45px;
        font-weight: 600;
        font-family: 'Courier New', Courier, monospace;
        color: white;
    }

    .btn {
        margin: 0 0 0 2em;
    }

    .student {
        margin: 0 0 0 8%;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 35vw;
        height: max-content;
        font-size: 3rem;
        text-decoration: none;
        color: #f1c40f; /* Yellow color for text */
        background-color: #fff;
        padding: 1rem 2rem;
        border: 2px solid #f1c40f; /* Yellow border */
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .student:hover {
        text-decoration: none;
        color: #fff;
        background-color: #f1c40f; /* Yellow background on hover */
        border-color: #f39c12; /* Darker yellow border on hover */
        transform: rotate(5deg); /* Rotate effect on hover */
    }

    select.form-select {
        width: 100%;
        padding: 10px;
        font-size: 1.5rem; /* Make the select element bigger */
        border: 2px solid #ccc;
        border-radius: 5px;
        margin-bottom: 20px;
    }

    p {
        font-size: 30px;
    }

    .parag {
        font-size: 1.5rem; /* Make the font size bigger */
    }

    .enrolled-course {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }

    .drop-button {
        font-size: 1rem;
        color: #fff;
        background-color: #e74c3c; /* Red background */
        border: none;
        padding: 5px 10px;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .drop-button:hover {
        background-color: #c0392b; /* Darker red background on hover */
    }
</style>
