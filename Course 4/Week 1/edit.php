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

    if (!isset($_GET['profile_id']) ) {
        $_SESSION['error'] = "Missing profile_id";
        header('Location: index.php');
        return;
    }

    $failure = FALSE;
    if ((isset($_POST['first_name']) && strlen($_POST['first_name']) < 1) || 
        (isset($_POST['last_name']) && strlen($_POST['last_name']) < 1) || 
        (isset($_POST['email']) && strlen($_POST['email']) < 1) || 
        (isset($_POST['headline']) && strlen($_POST['headline']) < 1) || 
        (isset($_POST['summary']) && strlen($_POST['summary']) < 1)) {

            $failure = TRUE;

            $_SESSION['wrong'] = "All fields are required";
            header("Location: edit.php?profile_id=".$_POST['profile_id']);
            return;
    }

    if (isset($_POST['email']) && strpos($_POST['email'], '@') === FALSE) {
        $failure = TRUE;
        $_SESSION['wrong'] = "Email must have an at-sign (@)";
        header("Location: edit.php?profile_id=".$_POST['profile_id']);
        return;
    }


    if (isset($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST['email']) && 
        isset($_POST['headline']) && isset($_POST['summary']) && $failure === FALSE) {
            $sql = "UPDATE profile SET first_name = :fn,
                last_name = :ln, email = :em, headline = :he, summary = :su 
                WHERE profile_id = :pid";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array(
                ':fn' => $_POST['first_name'],
                ':ln' => $_POST['last_name'],
                ':em' => $_POST['email'],
                ':he' => $_POST['headline'],
                ':su' => $_POST['summary'],
                ':pid' => $_POST['profile_id']));

            $_SESSION['success'] = 'Profile updated';
            header( 'Location: index.php' ) ;
            return;
    }

    $stmt = $pdo->prepare("SELECT * FROM profile WHERE profile_id = :xyz");
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
    $profile_id = $row['profile_id'];
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
        <h1>Editing Profile for <?= htmlentities($_SESSION['name']); ?></h1>

        <?php
            if ( isset($_SESSION['wrong']) ) {
                echo('<p style="color: red;">'.htmlentities($_SESSION['wrong'])."</p>\n");
                unset($_SESSION['wrong']);
            }
        ?>

        <form method="post">
            <label for="first_name">First Name: </label>
            <input type="text" name="first_name" id="fName" size="60" value="<?= $fName ?>"><br/>
            <label for="last_name">Last Name: </label>
            <input type="text" name="last_name" id="lName" size="60" value="<?= $lName ?>"><br/>
            <label for="email">Email: </label>
            <input type="text" name="email" id="email" size="30" value="<?= $email ?>"><br/><br/>
            <label for="headline">Headline: </label><br/>
            <input type="text" name="headline" id="headline" size="80" value="<?= $headline ?>"><br/>
            <label for="summary">Summary: </label><br/>
            <textarea name="summary" id="summary" cols="80" rows="8"><?= $summary ?></textarea><br/><br/>
            <input type="hidden" name="profile_id" value="<?= $profile_id ?>">
            <input type="submit" value="Save">
            <input type="submit" name="cancel" value="Cancel">
        </form>
    </div>
</body>
</html>