<?php 
include '.gitignore/db.php';
$msg = ""; // message to show after form submission 

if ($_SERVER["REQUEST_METHOD"] == "POST") { // just when form is submitted by button (post method)
    try {
        $conn->beginTransaction(); //all or nothing
        $stmt = $conn->prepare("INSERT IGNORE INTO visitors (first_name, last_name, email, phone) VALUES (?, ?, ?, ?)");
        //add visitor / ignore --> if not exists (based on email)/ ? --> sql injection safe
        $stmt->execute([$_POST['fname'], $_POST['lname'], $_POST['email'], $_POST['phone']]);
        
        //$visitor_id = $conn->lastInsertId() ?: $conn->query("select id from visitors where email='{$_POST['email']}'")->fetchColumn();
        //SQL Injection 
        //edit above to be safe from SQL Injection :
        if (!$visitor_id = $conn->lastInsertId()) {
            $stmt = $conn->prepare("SELECT id from visitors where email=?");
            $stmt->execute([$_POST['email']]);
            $visitor_id = $stmt->fetchColumn();
        }

        /*
        feedback || how?
        $stmt = $conn->prepare("insert into visits (visitor_id, exhibition_id, feedback) values (?, ?, ?)");
        $stmt->execute([$visitor_id, $_POST['exhibition_id'], '']);
        */
        
        $conn->commit(); // to save changes
        $msg = "<p class='Ongoing'>DONE!</p>"; // success 

    } catch (Exception $e) {
        $conn->rollBack(); // if error, undo all changes!
        $msg = "<p class='Past'>error:(</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/style.css">
    <title>visits</title>
</head>
<body>
    <div class="container">
        <h2>Visits</h2>
        <?php echo $msg; ?>
        <form method="POST">
            <input type="text" name="fname" placeholder="first name" required>
            <input type="text" name="lname" placeholder="last name" required>
            <input type="email" name="email" placeholder="email address" required>
            <input type="text" name="phone" placeholder="phone number">
            <select name="exhibition_id">
                <?php
                $exs = $conn->query("SELECT id, title FROM exhibitions WHERE status != 'Past'");
                while($ex = $exs->fetch()) echo "<option value='{$ex['id']}'>{$ex['title']}</option>";
                ?>
            </select>
            <button type="submit">sure?</button>
        </form>
    </div>
</body>
</html>