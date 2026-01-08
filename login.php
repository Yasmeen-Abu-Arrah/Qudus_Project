<?php
session_start(); // save data across multiple pages 
include 'db.php';
$error = ""; // message to show if login fails

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = $_POST['username'];
    $pass = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM employees WHERE username = ?");
    $stmt->execute([$user]);
    $admin = $stmt->fetch(); // fetch --> one row = all user data as array

    if ($admin && password_verify($pass, $admin['password'])) { // verify password hash
        $_SESSION['admin_id'] = $admin['id']; 
        header("Location: admin.php");
    } else {
        $error = "Invalid credentials!";
    }
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Login - Qudus Admin</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container"> 
        <h2>Admin Login</h2>
        <?php if($error) echo "<p class='error'>$error</p>"; ?>
        <form method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
        <p><a href="index.php">Back to site</a></p>
    </div>
</body>
</html>