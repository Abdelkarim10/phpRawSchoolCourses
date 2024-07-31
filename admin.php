<?php
session_start();
require_once 'connection.php';

if ($_SESSION['role_id'] != 3) { // Only admins
    echo "Access denied.";
    exit;
}

// Fetch pending users
$sql = "SELECT id, name, username, role_id FROM users WHERE is_pending = 1";
$result = $conn->query($sql);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = (int)$_POST['user_id'];
    if (isset($_POST['approve'])) {
        $approveSql = "UPDATE users SET is_pending = 0 WHERE id = $user_id";
        $conn->query($approveSql);
    } elseif (isset($_POST['decline'])) {
        $declineSql = "DELETE FROM users WHERE id = $user_id";
        $conn->query($declineSql);
    }
    header("Location: admin.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Approval</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<a href="logout.php" class="btn btn-secondary" style="margin:15px 0 0 20px">Log Out</a>
<h1>Admin</h1><br><br>
<h3>Pending User Approvals</h3>
<br><br>
<table class="table">
    <thead>
        <tr>
            <th>Name</th>
            <th>Username</th>
            <th>Role</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($user = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $user['name']; ?></td>
            <td><?php echo $user['username']; ?></td>
            <td><?php echo $user['role_id'] == 1 ? 'Student' : 'Teacher'; ?></td>
            <td>
                <form method="POST">
                    <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                    <button type="submit" name="approve" class="btn btn-success" style="margin:0 15px 0 0">Approve</button>
                    <button type="submit" name="decline" class="btn btn-danger">Decline</button>
                </form>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>
</body>
</html>
<style>
    h1 {
        text-align: center;
        font-size: 45px;
        font-weight: 600;
        font-family: 'Courier New', Courier, monospace;
        color: black;
    }
    h3 {
        text-align: center;
        font-size: 30px;
        font-weight: 600;
        font-family: 'Courier New', Courier, monospace;
        color: black;
    }
    table {
        width: 60% !important;
        margin: auto !important;
    }
</style>
