<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
require_once 'connection.php';


if (!isset($_SESSION['user_id']) || !isset($_SESSION['role_id'])) {
    header("Location: login.php");
    exit;
}


if ($_SESSION['role_id'] == 1) {
    echo '<div class="yellow">
    <br><a href="logout.php" class="btn btn-secondary">Log Out</a> <br><br><br><br>
    <h1>Welcome to the School Management System</h1> <br><br><br><br><br><br>
    <a href="enroll.php" class="student">Enroll in a Course</a>
    
    
    </div>';
} else if ($_SESSION['role_id'] == 2) { 
    echo '<div class="blue">
    <br><a href="logout.php" class="btn btn-secondary">Log Out</a><br><br><br><br>
    <h1>Welcome to the School Management System</h1> <br><br><br><br><br><br>

    <a href="manage_courses.php" class="teacher">Manage Courses</a>
    
    </div>';
} else if ($_SESSION['role_id'] == 3) {
    header("Location: admin.php");
}

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
    .blue{
        height: 100vh;
        width: 100vw;
        background-image: linear-gradient(to left,#2c67f2, #020c2b);
    }
    .yellow{
        height: 100vh;
        width: 100vw;
        background-image: linear-gradient(to left,#f39c12 ,#f1c40f );
    }
    .btn{
        margin: 0 0 0 2em ;

    }
   h1{
    
    text-align: center;
    font-size: 45px;
    font-weight: 600;
    font-family: 'Courier New', Courier, monospace;
    color: white;



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


.student {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 35vw;
    height: max-content;
    margin: 0 0 0 30%;
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


</style>

</html>


