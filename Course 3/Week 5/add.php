<?php
    session_start();
    require_once 'pdo.php';

    if(!isset($_SESSION['name'])) {
        die('ACCESS DENIED');
    }

    if ( isset($_POST['cancel']) ) {
        header('Location: index.php');
        return;
    }

    $failure = FALSE;

    if ((isset($_POST['make']) && strlen($_POST['make']) < 1) || (isset($_POST['model']) && strlen($_POST['model']) < 1) || 
        (isset($_POST['year']) && strlen($_POST['year']) < 1) || (isset($_POST['mileage']) && strlen($_POST['mileage']) < 1)) { 
        $failure = TRUE;

        $_SESSION['wrong'] = "All fields are required";
        header("Location: add.php");
        return;
    }

    if (isset($_POST['year']) && (!is_numeric($_POST['year']))) {
        $failure = TRUE;

        $_SESSION['wrong'] = "Year must be numeric";
        header("Location: add.php");
        return;
    }

    if (isset($_POST['mileage']) && (!is_numeric($_POST['mileage']))) {
        $failure = TRUE;

        $_SESSION['wrong'] = "Mileage must be numeric";
        header("Location: add.php");
        return;
    }


    if (isset($_POST['make']) && isset($_POST['model']) && isset($_POST['year']) && isset($_POST['mileage']) && $failure === FALSE) {
        $sql = "INSERT INTO autos
        (make, model, year, mileage) VALUES ( :mk, :md, :yr, :mi)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(
            ':mk' => $_POST['make'],
            ':md' => $_POST['model'],
            ':yr' => $_POST['year'],
            ':mi' => $_POST['mileage']
        ));

        $_SESSION['success'] = "Record added";
        header("Location: index.php");
        return;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Talha Ibne Mahmud</title>

    <?php require_once 'head.php'; ?>
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
            <label for = "model"> Model </label>
            <input type="text" name="model" size="60" id = "model"/>
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
</body>
</html>