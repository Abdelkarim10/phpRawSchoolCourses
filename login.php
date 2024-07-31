<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once 'connection.php';

if (
    isset($_POST['username']) && !empty($_POST['username'])
    && isset($_POST['password']) && !empty($_POST['password'])
) {
    $username = $conn->real_escape_string($_POST['username']);
    $password = $_POST['password']; 

    
    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows == 0) {
        // Username not found
        $message = "User not found";
       
       
    } else {
        $row = $result->fetch_assoc();
       
        
        if (password_verify($password, $row['password'])) {
           
            if ($row['is_pending'] == 1) {
             
                $message = "Your account is pending approval. Please wait for the admin to approve your registration.";

            
            } else {
                // User is approved, start the session
                $_SESSION['user'] = $row['username'];
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['role_id'] = $row['role_id'];

                header("Location: index1.php");
                exit;
            }
        } else {
            // Incorrect password
            $message = "Your password was incorrect";
           
         
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <br>   
    <br><br>
    <?php if (isset($message)) { echo "<p style='font-size:20px;text-align: center;'>$message</p>"; } ?>
    <br><br><br>
    <div style="width:30vw;margin: auto;">

        <h1 style="margin:0 0 5% 32%;">Login</h1>
        <form method="POST">
            <div class="form-group">
                <label for="username">Username :</label>
                <input type="text" class="form-control" name="username" id="username" required placeholder="Enter username">
                <small class="form-text text-muted">This is required</small>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" required placeholder="Password">
                <small class="form-text text-muted">and this buddy</small>
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
        </form>
        <br>
        <a href="register.php">Don't have an account? Register</a>
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
