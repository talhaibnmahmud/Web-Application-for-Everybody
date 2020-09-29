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

    if (!isset($_GET['autos_id']) ) {
        $_SESSION['error'] = "Missing autos_id";
        header('Location: index.php');
        return;
    }

    $failure = FALSE;

    if ((isset($_POST['make']) && strlen($_POST['make']) < 1) || (isset($_POST['model']) && strlen($_POST['model']) < 1) || 
        (isset($_POST['year']) && strlen($_POST['year']) < 1) || (isset($_POST['mileage']) && strlen($_POST['mileage']) < 1)) { 
        $failure = TRUE;

        $_SESSION['wrong'] = "All fields are required";
        header("Location: edit.php?autos_id=".$_POST['autos_id']);
        return;
    }

    if (isset($_POST['year']) && (!is_numeric($_POST['year']))) {
        $failure = TRUE;

        $_SESSION['wrong'] = "Year must be numeric";
        header("Location: edit.php?autos_id=".$_POST['autos_id']);
        return;
    }

    if (isset($_POST['mileage']) && (!is_numeric($_POST['mileage']))) {
        $failure = TRUE;

        $_SESSION['wrong'] = "Mileage must be numeric";
        header("Location: edit.php?autos_id=".$_POST['autos_id']);
        return;
    }

    if (isset($_POST['make']) && isset($_POST['model']) && isset($_POST['year']) && isset($_POST['mileage']) && $failure === FALSE) {
        $sql = "UPDATE autos SET make = :make,
            model = :model, year = :year, mileage = :mileage 
            WHERE autos_id = :autos_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(
            ':make' => $_POST['make'],
            ':model' => $_POST['model'],
            ':year' => $_POST['year'],
            ':mileage' => $_POST['mileage'],
            ':autos_id' => $_POST['autos_id']));

        $_SESSION['success'] = 'Record updated';
        header( 'Location: index.php' ) ;
        return;
    }

    $stmt = $pdo->prepare("SELECT * FROM autos where autos_id = :xyz");
    $stmt->execute(array(":xyz" => $_GET['autos_id']));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ( $row === false ) {
        $_SESSION['error'] = 'Bad value for autos_id';
        header( 'Location: index.php' ) ;
        return;
    }

    $make = htmlentities($row['make']);
    $model = htmlentities($row['model']);
    $year = htmlentities($row['year']);
    $mileage = htmlentities($row['mileage']);
    $autos_id = $row['autos_id'];
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
        <h1> Editing Automobile </h1>

        <?php
            if ( isset($_SESSION['wrong']) ) {
                echo('<p style="color: red;">'.htmlentities($_SESSION['wrong'])."</p>\n");
                unset($_SESSION['wrong']);
            }
        ?>

        <form method = "post">
                <label for = "make"> Make </label>
                <input type="text" name="make" size="60" id = "make" value="<?= $make ?>"/>
                <br><br>
                <label for = "model"> Model </label>
                <input type="text" name="model" size="60" id = "model" value="<?= $model ?>"/>
                <br><br>
                <label for = "year"> Year </label>
                <input type="text" name="year" id = "year" value="<?= $year ?>"/>
                <br><br>
                <label for = "mileage"> Mileage </label>
                <input type="text" name="mileage" id = "mileage" value="<?= $mileage ?>"/>
                <br><br>
                <input type="hidden" name="autos_id" value="<?=$autos_id ?>">
                <br><br>

                <input type="submit" value="Save">
                <input type="submit" name="cancel" value="Cancel">
            </form>
    </div>
</body>
</html>