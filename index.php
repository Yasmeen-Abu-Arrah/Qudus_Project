<?php include '.gitignore/db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Qudus</title>
    <link href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&family=Cairo:wght@300;400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <nav class="navbar">
        <div class="logo">
            <img src="images/logo1.png" alt="Qudus Logo">
            <span class="logo-text">Qudus</span>
        </div>
        <ul class="nav-links">
            <li><a href="index.php">About Us</a></li>
            <li><a href="index.php">3D Printing</a></li>
            <li><a href="exhibitions.php">Exhibitions</a></li>
            <li><a href="visit.php">Visit Us</a></li>
        </ul>
        <button class="sign-btn">Sign In</button>
    </nav>

    <section class="about">
        <div class="about-overlay"></div>
        <div class="about-contant">
            <h1> <span class="highlight">Qudus</span><br>“memory meets identity, and the place tells its story”</h1>
            <p>The intersection of 7th-century heritage and 21st-century additive manufacturing. Experience history through high-fidelity scans and museum-grade 3D prints.</p>
            <!-- edit caption -->
            <div class="about-btns">
                <button class="tour-btn"><a href="#" >Start Virtual Tour</a></button>
                <button class="view3d-btn"><a href="#" >View 3D Prints</a></button>
            </div>
        </div>
    </section>

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