<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'connection.php';
session_start();


if (!isset($_SESSION['user_id']) || !isset($_SESSION['role_id']) || $_SESSION['role_id'] != 2) {
    echo "Access denied.";
    exit;
}

// Handle course creation
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['create'])) {
    $course_name = $conn->real_escape_string($_POST['course_name']);
    $user_id = $_SESSION['user_id'];

    // Check for duplicate course
    $check_sql = "SELECT * FROM courses WHERE course_name = '$course_name' AND user_id = $user_id";
    $check_result = $conn->query($check_sql);

    if ($check_result->num_rows > 0) {
        $message = "You have already created a course with this name.";
    } else {
        $sql = "INSERT INTO courses (course_name, user_id) VALUES ('$course_name', $user_id)";
        $result = $conn->query($sql);

        if ($result) {
            $message = "Course created successfully!";
        } else {
            echo "Error: " . $conn->error;
        }
    }
}


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete'])) {
    $course_id = (int)$_POST['course_id']; 

    $sql = "DELETE FROM courses WHERE id = $course_id";
    $result = $conn->query($sql);

    if ($result) {
        $message = "Course deleted successfully!";
    } else {
        echo "Error: " . $conn->error;
    }
}


$user_id = $_SESSION['user_id'];
$sql = "SELECT id, course_name FROM courses WHERE user_id = $user_id";
$result = $conn->query($sql);

if (!$result) {
    die("Error fetching courses: " . $conn->error);
}

echo '<br><a href="logout.php" class="btn btn-secondary" style="margin:0 0 0 2%" >Log Out</a><br>';
if (isset($message)) {echo "<p style='text-align:center;font-size:45px;'>$message</p>";}
echo "<h1>Create a Course</h1><br><br><br>";

// Course creation form

echo "<form method='POST'>";
echo "<div class='selecting'>Course Name: <input type='text' name='course_name' class='select' required></div><br>";
echo "<button type='submit' name='create' class='teacher'>Create</button><br><br><br>";
echo "</form>";

// List of courses with delete option
echo "<h2>Your Courses</h2><br><br>";
echo "<ul>";

while ($row = $result->fetch_assoc()) {
    echo "<li style='margin:0 0 0 33%;'>{$row['course_name']} <form method='POST' style='display:inline;'>
          <input type='hidden' name='course_id' value='{$row['id']}'>&nbsp;&nbsp;
          <button type='submit' name='delete' class='drop-button'>Delete</button>
          </form></li>";
}

echo "</ul>";
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

<style>
body{
    overflow-x: none !important;
  
    height: 100vh;
    width: 98vw;
    color: white;
    background-image: linear-gradient(to left,#2c67f2, #020c2b);
}
h1{
        margin: 0 0 0 32%;
        font-size:70px;
        font-weight: 900;
        font-family: 'Courier New', Courier, monospace;
        color: white;
    }
    h2 {
        margin: 0 0 0 38%;
        font-size: 35px;
        font-weight:600;
        font-family: 'Courier New', Courier, monospace;
        color: white;
    }
    ul{
        text-decoration: none;
        font-size: 25px;
        
    }
    .teacher  {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 35vw;
    height: max-content;
    margin: 0 0 0 30%;
    font-size: 3rem;
    text-decoration: none;
    color: #3498db;
    background-color: #fff;
    padding: 1rem 2rem;
    border: 2px solid #3498db;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.teacher:hover {
    text-decoration: none;
    color: #fff;
    background-color: #3498db;
    border-color: #2980b9;
    transform: scale(1.1);
   
}
.selecting {
        width: 40%;
        margin: 0 0 0 30%;
        font-size: 30px;
    }

.select {
    width:65%;
    padding: 6px;
    font-size: 1.5rem;
    border: 2px solid #ccc;
    border-radius: 5px;
    margin-bottom: 20px;
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
</html>