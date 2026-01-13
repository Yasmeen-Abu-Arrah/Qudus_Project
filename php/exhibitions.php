<?php include '../.gitignore/db.php'; ?>
<?php
/*
    $stmt = $conn->query("select * from exhibitions order by start_date desc");
    while ($row = $stmt->fetch()) { // rows 
        // $row['title'], $row['start_date'], $row['end_date'],....
        echo "<div class='exhibition-card'>";
        echo "<h3>" . htmlspecialchars($row['title']) . " <span class='status-tag {$row['status']}'>{$row['status']}</span></h3>";
        // htmlspecialchars -- Prevents XSS attacks (special characters)
        // {$row['status']}  to add class based on status == as value :)
        echo "<p>{$row['description']}</p>";
        echo "<small> {$row['start_date']} to {$row['end_date']} </small>";
        echo "</div>";
 } */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exhibitions - Qudus</title>
    <link href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&family=Cairo:wght@300;400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">
    <link href="../css/exhibitions_style.css" rel="stylesheet">
    
</head>
<body>
    <nav class="navbar">
        <div class="logo">
            <img src="../images/logo1.png" alt="Qudus Logo">
            <span class="logo-text">Qudus</span>
        </div>
        <ul class="nav-links">
            <li><a href="../index.html">About Us</a></li>
            <li><a href="prints.php">3D Printing</a></li>
            <li><a href="exhibitions.php">Exhibitions</a></li>
            <li><a href="visit.php">Visit Us</a></li>
        </ul>
    </nav>

    <div class="container">
        <div class="page-header">
            <h1 class="page-title">Our Exhibitions</h1>
            <p class="page-subtitle">Explore our curated collection of historical artifacts and cultural treasures from Jerusalem's rich heritage</p>
        </div>

        <div class="exhibitions-container">
            <div class="exhibitions-grid">
                <?php
                $stmt = $conn->query("SELECT * FROM exhibitions ORDER BY start_date DESC");
                
                while ($row = $stmt->fetch()) {
                    echo "<div class='exhibition-card'>";
                    echo "<h3>" . htmlspecialchars($row['title']) . " <span class='status-tag {$row['status']}'>{$row['status']}</span></h3>";
                    echo "<p>{$row['description']}</p>";
                    echo "<small>{$row['start_date']} to {$row['end_date']}</small>";
                    
                    // reg. button --> Ongoing or Upcoming exhibitions
                    if ($row['status'] == 'Ongoing' || $row['status'] == 'Upcoming') {
                        echo "<a href='visit.php?exhibition_id={$row['id']}' class='register-btn'>Register for Visit</a>";
                    }
                    echo "</div>";
                }
                ?>
            </div>
        </div>

        <a href="../index.html" class="back-link">
            <span class="material-symbols-outlined">arrow_back</span>
            Back to Home
        </a>
    </div>
</body>
</html>