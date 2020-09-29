<?php
    session_start();
    require_once 'pdo.php';

    if (!isset($_GET['profile_id']) ) {
        $_SESSION['error'] = "Missing profile_id";
        header('Location: index.php');
        return;
    }


    $stmt = $pdo->prepare("SELECT * FROM profile where profile_id = :xyz");
    $stmt->execute(array(":xyz" => $_GET['profile_id']));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ( $row === false ) {
        $_SESSION['error'] = 'Bad value for profile_id';
        header( 'Location: index.php' ) ;
        return;
    }

    $fName = htmlentities($row['first_name']);
    $lName = htmlentities($row['last_name']);
    $email = htmlentities($row['email']);
    $headline = htmlentities($row['headline']);
    $summary = htmlentities($row['summary']);
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
        <h1>Profile Information</h1>

        <p>First Name: <?= $fName; ?></p>
        <p>Last Name: <?= $lName; ?></p>
        <p>Email: <?= $email; ?></p>
        <p>Headline: <br> <?= $headline; ?></p>
        <p>Summary: <br> <?= $summary; ?></p>
        <p><a href="index.php">Done</a></p>
    </div>
</body>
</html>