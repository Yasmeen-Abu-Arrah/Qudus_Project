<?php
session_start();
include '.gitignore/db.php';

// Check if logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Handle Exhibition Deletion
if (isset($_GET['delete_ex'])) {
    $stmt = $pdo->prepare("DELETE FROM exhibitions WHERE id = ?");
    $stmt->execute([$_GET['delete_ex']]);
}

//  New Exhibition Submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_ex'])) {
    $stmt = $pdo->prepare("INSERT INTO exhibitions (title, description, schedule_info) VALUES (?, ?, ?)");
    $stmt->execute([$_POST['title'], $_POST['desc'], $_POST['schedule']]);
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard - Qudus</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header><h1>Admin Dashboard</h1></header>
    <nav>
        <a href="admin.php">Manage Content</a>
        <a href="logout.php">Logout</a>
    </nav>
    
    <div class="container">
        <h2>Add New Exhibition</h2>
        <form method="POST">
            <input type="text" name="title" placeholder="Exhibition Title" required>
            <textarea name="desc" placeholder="Description"></textarea>
            <input type="text" name="start_date" placeholder="Start Date (YYYY-MM-DD)">
            <input type="text" name="end_date" placeholder="End Date (YYYY-MM-DD)">
            <input type="hidden" name="status" value="Ongoing">
            <button type="submit" name="add_ex">Add Exhibition</button>
        </form>

        <h2>Existing Exhibitions</h2>
        <table>
            <tr>
                <th>Title</th>
                <th>Schedule</th>
                <th>Action</th>
            </tr>

            <?php
            $stmt = $conn->query("SELECT * FROM exhibitions");
            while ($row = $stmt->fetch()) {
                echo "<tr>
                        <td>{$row['title']}</td>
                        <td>{$row['schedule_info']}</td>
                        <td><a href='admin.php?delete_ex={$row['id']}' onclick='return confirm(\"Are you sure?\")'>
                        <button class='delete'>Delete</button></a></td>
                      </tr>";
            }
            ?>
        </table>

        <h2>Recent Visitor</h2>
        <table>
            <tr>
                <th>Visitor</th>
                <th>Email</th>
                <th>Exhibition ID</th>
                <th>Date</th>
            </tr>
            <?php
            $stmt = $conn->query("SELECT * FROM visitors ORDER BY visit_date DESC");
            while ($row = $stmt->fetch()) {
                echo "<tr>
                        <td>{$row['visitor_name']}</td>
                        <td>{$row['visitor_email']}</td>
                        <td>{$row['exhibition_id']}</td>
                        <td>{$row['visit_date']}</td>
                      </tr>";
            }
            ?>
        </table>
    </div>
</body>
</html>