<?php
require_once "pdo.php";

// Demand a GET parameter
if ( ! isset($_GET['name']) || strlen($_GET['name']) < 1  ) {
    die('Name parameter missing');
}

// If the user requested logout go back to index.php
if ( isset($_POST['logout']) ) {
    header('Location: index.php');
    return;
}

$failure = false;  // If we have no POST data
$success = false;

if (strlen($_POST['make']) < 1) { 
    $failure = "Make is required";
} elseif (!is_numeric($_POST['year']) || !is_numeric($_POST['mileage'])) {
    $failure = "Mileage and year must be numeric";
}

if (isset($_POST['make']) && isset($_POST['year']) && isset($_POST['mileage']) && $failure === false) {
    $sql = "INSERT INTO autos
    (make, year, mileage) VALUES ( :mk, :yr, :mi)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
        ':mk' => $_POST['make'],
        ':yr' => $_POST['year'],
        ':mi' => $_POST['mileage']
    ));

    $success = "Record inserted";
}

$stmt = $pdo->query("SELECT make, year, mileage FROM autos");
$rows = $stmt->fetchall(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html>
<head>
    <title> Talha Ibne Mahmud </title>
    <?php require_once "bootstrap.php"; ?>
</head>
<body>
    <div class = "container">
        <h1> Tracking Autos for <?php echo htmlentities($_GET['name']) ?> </h1>

        <?php
        // Note triple not equals and think how badly double
        // not equals would work here...
        if ( $failure !== false ) {
        // Look closely at the use of single and double quotes
            echo('<p style="color: red;">'.htmlentities($failure)."</p>\n");
        }

        if($success !== false) {
            echo('<p style = "color: green;">'.htmlentities($success)."</p>\n");
        }
        ?>

        <form method = "post">
            <label for = "make"> Make </label>
            <input type="text" name="make" size="60" id = "make"/>
            <br><br>
            <label for = "year"> Year </label>
            <input type="text" name="year" id = "year"/>
            <br><br>
            <label for = "mileage"> Mileage </label>
            <input type="text" name="mileage" id = "mileage"/>
            <br><br>

            <input type="submit" value="Add">
            <input type="submit" name="logout" value="Logout">
        </form>

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
    </div>

    <script data-cfasync="false" src="/cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script>
</body>
</html>