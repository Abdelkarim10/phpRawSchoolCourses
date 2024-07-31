<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

require_once 'connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $conn->real_escape_string($_POST['name']);
    $username = $conn->real_escape_string($_POST['username']);
    $password = password_hash($conn->real_escape_string($_POST['password']), PASSWORD_DEFAULT);
    $role_id = (int)$_POST['role_id'];

    // Check if the username already exists
    $checkSql = "SELECT id FROM users WHERE username = '$username'";
    $checkResult = $conn->query($checkSql);

    if ($checkResult->num_rows > 0) {
        $message = "Username already taken. Please choose another one.";
    } else {
        // Insert new user with pending status
        $insertSql = "INSERT INTO users (name, username, password, role_id, is_pending) VALUES ('$name', '$username', '$password', $role_id, 1)";
        $insertResult = $conn->query($insertSql);

        if ($insertResult) {
            $message = "Registration successful! Your account is pending approval.";
        } else {
            $message = "Registration failed. Please try again.";
        }
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
 <br>   
 <?php if (isset($message)) { echo "<p style='margin:3% 0 0 37%;font-size:20px;'>$message</p>"; } ?>
 <br>
 <div style="width:30vw; margin:auto;">
     <h1 style="margin:0 0 5% 32%;">Register</h1>
     <form method="POST">
         <div class="form-group">
             <label for="name">Name:</label>
             <input type="text" class="form-control" name="name" id="name" required placeholder="Enter name">
             <small class="form-text text-muted">This is required</small>
         </div>
         <div class="form-group">
             <label for="username">Username:</label>
             <input type="text" class="form-control" name="username" id="username" required placeholder="Enter username">
             <small class="form-text text-muted">and this </small>
         </div>
         <div class="form-group">
             <label for="password">Password:</label>
             <input type="password" class="form-control" id="password" name="password" required placeholder="Password">
             <small class="form-text text-muted">even this required</small>
         </div>
         <div class="form-group">
             <label for="role_id">Role:</label>
             <select name="role_id" class="form-select" required style="padding:4px; border-radius:7px;">
                 <option value="1" selected>Student</option>
                 <option value="2">Teacher</option>
             </select>
             <small class="form-text text-muted">and this mate</small><br>
         </div>
         <button type="submit" class="btn btn-primary">Submit</button>
     </form><br>
     <a href="login.php">Already have an account? login</a>
 </div>
 <style>
     label {
         font-size: 17px;
     }
     h1 {
         font-weight: 600;
         font-family: 'Courier New', Courier, monospace;
     }
 </style>
</body>
</html>
