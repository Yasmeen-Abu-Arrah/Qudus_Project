<?php include '../.gitignore/db.php'; ?>
<?php
    $stmt = $conn->query("select * from prints order by start_date desc");
    while ($row = $stmt->fetch()) { // rows 
        // $row['title'], $row['start_date'], $row['end_date'],....
        echo "<div class='exhibition-card'>";
        echo "<h3>" . htmlspecialchars($row['title']) . " <span class='status-tag {$row['status']}'>{$row['status']}</span></h3>";
        // htmlspecialchars -- Prevents XSS attacks (special characters)
        // {$row['status']}  to add class based on status == as value :)
        echo "<p>{$row['description']}</p>";
        echo "<small> {$row['start_date']} to {$row['end_date']} </small>";
        echo "</div>";
 }      
?>