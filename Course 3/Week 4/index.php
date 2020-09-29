<?php
    session_start();
    if(isset($_SESSION['name'])) {
        unset($_SESSION['name']);
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title>Talha Ibne Mahmud</title>
    <?php require_once "bootstrap.php"; ?>
</head>
<body>
    <div class="container">
        <h1>Welcome to Autos Database</h1>
        <!-- <p><strong>Note:</strong> This sample code is only
        partially done and serves only as a starting point for the assignment.
        </p> -->
        <p>
            <a href="login.php">Please Log In</a>
        </p>
        <p>
            Attempt to go to 
            <a href="view.php">view.php</a> without logging in - it should fail with an error message.
            <br>
            Attempt to go to 
            <a href="add.php">add.php</a> without logging in - it should fail with an error message.
        </p>
        <!-- <p>
        <a href="http://www.wa4e.com/code/rps.zip"
        target="_blank">Source Code for this Application</a>
        </p> -->
    </div>
</body>

