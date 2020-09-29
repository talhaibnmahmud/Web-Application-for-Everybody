<?php
    session_start();
    require_once "pdo.php";

    if ( ! isset($_SESSION['name']) || strlen($_SESSION['name']) < 1  ) {
        die('Not Logged In');
    }

    $stmt = $pdo->query("SELECT make, year, mileage FROM autos");
    $rows = $stmt->fetchall(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Talha Ibne Mahmud</title>

    <?php require_once "bootstrap.php"; ?>
</head>
<body>
    <div class="container">
        <h1> Tracking Autos for <?php echo htmlentities($_SESSION['name']) ?> </h1>

        <?php
            if ( isset($_SESSION['success']) ) {
                echo('<p style="color: green;">'.htmlentities($_SESSION['success'])."</p>\n");
                unset($_SESSION['success']);
            }
        ?>
        
        <h2>Automobiles</h2>
        <ul>
        <?php
            foreach ($rows as $row) {
                echo "<li>";
                echo (htmlentities($row['make'])." ".htmlentities($row['year'])." / ".htmlentities($row['mileage']));
                echo "</li>";
            }
        ?>
        </ul>

        <p>
            <a href="add.php">Add New</a> | <a href="logout.php">Logout</a>
        </p>
    </div>

    <script data-cfasync="false" src="/cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script>
</body>
</html>