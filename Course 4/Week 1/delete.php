<?php
    session_start();
    require_once 'pdo.php';

    if(!isset($_SESSION['name'])) {
        die('Not logged in');
    }
    if ( isset($_POST['cancel']) ) {
        header('Location: index.php');
        return;
    }

    if ( isset($_POST['delete']) && isset($_POST['profile_id']) ) {
        $sql = "DELETE FROM profile WHERE profile_id = :zip";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(':zip' => $_POST['profile_id']));
        $count = $stmt->rowCount();
        if($count) {
            $_SESSION['success'] = 'Profile deleted';
            header( 'Location: index.php' );
            return;
        }
    }

    if (!isset($_GET['profile_id']) ) {
        $_SESSION['error'] = "Missing profile_id";
        header('Location: index.php');
        return;
    }


    $stmt = $pdo->prepare("SELECT first_name, last_name, profile_id FROM profile WHERE profile_id = :xyz");
    $stmt->execute(array(":xyz" => $_GET['profile_id']));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ( $row === false ) {
        $_SESSION['error'] = 'Bad value for profile_id';
        header( 'Location: index.php' ) ;
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
        <h1>Deleting Profile</h1>

        <form action="delete.php" method="post">
            <p>First Name: <?= htmlentities($row['first_name']) ?></p>
            <p>Last Name: <?= htmlentities($row['last_name']) ?></p>
            <input type="hidden" name="profile_id" value="<?= $row['profile_id'] ?>">
            <input type="submit" name="delete" value="Delete">
            <input type="submit" name="cancel" value="Cancel">
        </form>
    </div>
</body>
</html>