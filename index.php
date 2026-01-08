<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title> Qudus </title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Qudus</h1>
        <p> Qudus Project ..... </p>
    </header>
    <nav>
        <a href="index.php">Main</a>
        <a href="visit.php">visit</a>
        <a href="login.php">staff</a>
    </nav>
    <div class="container">
        <h2>Available Exhibitions</h2>
        <?php
        $stmt = $conn->query("select * from exhibitions order by start_date desc");
        while ($row = $stmt->fetch()) { // rows 
            // $row['title'], $row['start_date'], $row['end_date'],....
            echo "<div class='exhibition-card'>";
            echo "<h3>" . htmlspecialchars($row['title']) . " <span class='status-tag {$row['status']}'>{$row['status']}</span></h3>";
            // htmlspecialchars --> Prevents XSS attacks (special characters)
            // {$row['status']}  to add class based on status == as value :)
            echo "<p>{$row['description']}</p>";
            echo "<small> {$row['start_date']} to {$row['end_date']} </small>";
            echo "</div>";
        }
        ?>
    </div>
</body>
</html>