
<?php 
include '../.gitignore/db.php';
$msg = ""; // message to show after form submission 

if ($_SERVER["REQUEST_METHOD"] == "POST") { // just when form is submitted by button (post method)
    try {
        $conn->beginTransaction(); //all or nothing
        $stmt = $conn->prepare("INSERT IGNORE INTO visitors (first_name, last_name, email, phone) VALUES (?, ?, ?, ?)");
        //add visitor / ignore --> if not exists (based on email)/ ? --> sql injection safe
        $stmt->execute([$_POST['fname'], $_POST['lname'], $_POST['email'], $_POST['phone']]);
        
        $visitor_id = $conn->lastInsertId();
        if (!$visitor_id) {
            $stmt = $conn->prepare("SELECT id from visitors where email=?");
            $stmt->execute([$_POST['email']]);
            $visitor_id = $stmt->fetchColumn();
        }

        $stmt = $conn->prepare("INSERT INTO visits (visitor_id, exhibition_id) VALUES (?, ?)");

        $stmt->execute([$visitor_id, $_POST['exhibition_id']]);
    
        /*
        feedback || how?
        $stmt = $conn->prepare("insert into visits (visitor_id, exhibition_id, feedback) values (?, ?, ?)");
        $stmt->execute([$visitor_id, $_POST['exhibition_id'], '']);
        */

         
        $conn->commit(); // to save changes
        $msg = "success"; // success 

    } catch (Exception $e) {
        $conn->rollBack(); // if error, undo all changes!
        $msg = "error";
    }
}
?>

<!--
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
        <?php // echo $msg; ?>
        <form method="POST">
            <input type="text" name="fname" placeholder="first name" required>
            <input type="text" name="lname" placeholder="last name" required>
            <input type="email" name="email" placeholder="email address" required>
            <input type="text" name="phone" placeholder="phone number">
            <select name="exhibition_id">
                <?php /*
                $exs = $conn->query("SELECT id, title FROM exhibitions WHERE status != 'Past'");
                while($ex = $exs->fetch()) echo "<option value='{$ex['id']}'>{$ex['title']}</option>";*/
                ?>
            </select>
            <button type="submit">sure?</button>
        </form>
    </div>
    <p><a href="../index.html">Back to site</a></p>
</body>
</html>
-->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Your Visit - Qudus</title>
    <link href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&family=Cairo:wght@300;400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">
    <link rel="stylesheet" href="../css/visit_style.css">
</head>
<body>
    <nav class="navbar">
        <div class="logo">
            <a href="../index.html"><img src="../images/logo1.png" alt="Qudus Logo"></a>
            <span class="logo-text">Qudus</span>
        </div>
    </nav>

    <div class="container">
        <div class="header-section">
            <h2>Book Your Visit</h2>
            <p class="subtitle">Join us on a journey through history</p>
        </div>

        <?php 
        if ($msg == "success") {
            echo '<div class="msg-box msg-success">✓ Your visit has been booked successfully! <br> We look forward to seeing you</div>';
        } elseif ($msg == "error") {
            echo '<div class="msg-box msg-error">✗ An error occurred, please try again</div>';
        }
        ?>

        <div class="form-section">
            <form method="POST">
                <div class="form-row">
                    <div>
                        <label>First Name</label>
                        <input type="text" name="fname" required>
                    </div>
                    <div>
                        <label>Last Name</label>
                        <input type="text" name="lname" required>
                    </div>
                </div>

                <div>
                    <label>Email Address</label>
                    <input type="email" name="email" required>
                </div>

                <div>
                    <label>Phone Number (Optional)</label>
                    <input type="text" name="phone">
                </div>

                <div>
                    <label>Select Exhibition</label>
                    <select name="exhibition_id" required>
                        <option value="">-- Choose an exhibition --</option>
                        <?php
                        $exs = $conn->query("SELECT id, title FROM exhibitions WHERE status != 'Past'");
                        while($ex = $exs->fetch()) {
                            echo "<option value='{$ex['id']}'>{$ex['title']}</option>";
                        }
                        ?>
                    </select>
                </div>

                <button type="submit">Confirm Booking</button>
            </form>
        </div>

        <a href="../index.html" class="back-link">
            <span class="material-symbols-outlined">arrow_back</span>
            Back to Site
        </a>
    </div>
</body>
</html>