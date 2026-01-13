<?php
session_start(); // save data across multiple pages 
include '../.gitignore/db.php'; 
$error = ""; // message to show if login fails

if (isset($_SESSION["staff_id"])) {
    header("Location: admin.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $pass = $_POST['password'];

    try {
        $stmt = $conn->prepare("SELECT * FROM staff WHERE username = ?");
        $stmt->execute([$username]);
        $staff = $stmt->fetch(); // fetch --> one row = all user data as array

    if ($staff && password_verify($pass, $staff['password'])) { // verify password hash
        $_SESSION['admin_id'] = $staff['id']; 
        $_SESSION['staff_name'] = $staff['first_name'] .' '. $staff['last_name'];
        $_SESSION['staff_role'] = $staff['role'];
        $_SESSION['username'] = $staff['username'];

       /* $updateStmt = $conn->prepare('UPDATE staff SET last_login = NOW() WHERE id = ?');
        $updateStmt->execute([$staff['id']]); */

        header("Location: admin.php");
        exit();

        } else { $error = "Incorrect username or password!"; }
    } catch (PDOException $e) { 
        $error = "An error occurred in the system. Please try again later."; 
        $error .= "Login error: ". $e->getMessage();
        }
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Login - Qudus Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&family=Cairo:wght@300;400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">
    <link rel="stylesheet" href="../css/login_style.css">
</head>
<body>
    <div class="container">
        <div class="login-header">
            <h2>Staff Login</h2>
            <p>Access the Qudus admin panel</p>
        </div>

        <div class="login-box">
            <?php if($error): ?>
                <p class='error'><?= htmlspecialchars($error) ?></p>
            <?php endif; ?>

            <form method="POST">
                <div class="input-group">
                    <input type="text" name="username" placeholder="Username" required>
                </div>
                <div class="input-group">
                    <input type="password" name="password" placeholder="Password" required>
                </div>
                <button type="submit">Login</button>
            </form>

            <a href="../index.html" class="back-link">
                <span class="material-symbols-outlined">arrow_back</span>
                Back to Site
            </a>
        </div>
    </div>
</body>
</html>