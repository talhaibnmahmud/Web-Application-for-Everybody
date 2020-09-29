<?php
    session_start();
    require_once "pdo.php";

    if ( ! isset($_SESSION['name']) || strlen($_SESSION['name']) < 1  ) {
        die('Not Logged In');
    }

    if ( isset($_POST['cancel']) ) {
        header('Location: view.php');
        return;
    }

    $failure = FALSE;

    if (isset($_POST['make']) && strlen($_POST['make']) < 1) { 
        $failure = TRUE;

        $_SESSION['wrong'] = "Make is required";
        header("Location: add.php");
        return;
    } elseif (isset($_POST['year']) && isset($_POST['mileage']) && (!is_numeric($_POST['year']) || !is_numeric($_POST['mileage']))) {
        $failure = TRUE;

        $_SESSION['wrong'] = "Mileage and year must be numeric";
        header("Location: add.php");
        return;
    }

    if (isset($_POST['make']) && isset($_POST['year']) && isset($_POST['mileage']) && $failure === FALSE) {
        $sql = "INSERT INTO autos
        (make, year, mileage) VALUES ( :mk, :yr, :mi)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(
            ':mk' => $_POST['make'],
            ':yr' => $_POST['year'],
            ':mi' => $_POST['mileage']
        ));

        $_SESSION['success'] = "Record inserted";
        header("Location: view.php");
        return;
    }
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
            if ( isset($_SESSION['wrong']) ) {
                echo('<p style="color: red;">'.htmlentities($_SESSION['wrong'])."</p>\n");
                unset($_SESSION['wrong']);
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
            <input type="submit" name="cancel" value="Cancel">
        </form>
    </div>

    <script data-cfasync="false" src="/cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script>
</body>
</html>