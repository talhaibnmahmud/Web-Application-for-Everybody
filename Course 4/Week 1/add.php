<?
    session_start();
    require_once 'pdo.php';

    if(!isset($_SESSION['name'])) {
        die('Not logged in');
    }
    if ( isset($_POST['cancel']) ) {
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
            header("Location: add.php");
            return;
    }

    if (isset($_POST['email']) && strpos($_POST['email'], '@') === FALSE) {
        $failure = TRUE;
        $_SESSION['wrong'] = "Email must have an at-sign (@)";
        header("Location: add.php");
        return;
    }


    if (isset($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST['email']) && 
        isset($_POST['headline']) && isset($_POST['summary']) && $failure === FALSE) {
            $stmt = $pdo->prepare('INSERT INTO profile
                (user_id, first_name, last_name, email, headline, summary)
                VALUES ( :uid, :fn, :ln, :em, :he, :su)');
            $stmt->execute(array(
                ':uid' => $_SESSION['user_id'],
                ':fn' => $_POST['first_name'],
                ':ln' => $_POST['last_name'],
                ':em' => $_POST['email'],
                ':he' => $_POST['headline'],
                ':su' => $_POST['summary'])
            );

            $_SESSION['success'] = "Profile added";
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
        <h1>Adding Profile for <?= htmlentities($_SESSION['name']); ?></h1>

        <?php
            if(isset($_SESSION['wrong'])) {
                echo ('<p style = "color: red">').$_SESSION['wrong']."</p>\n";
                unset($_SESSION['wrong']);
            }
        ?>

        <form method="post">
            <label for="first_name">First Name: </label>
            <input type="text" name="first_name" id="fName" size="60"><br/>
            <label for="last_name">Last Name: </label>
            <input type="text" name="last_name" id="lName" size="60"><br/>
            <label for="email">Email: </label>
            <input type="text" name="email" id="email" size="30"><br/><br/>
            <label for="headline">Headline: </label><br/>
            <input type="text" name="headline" id="headline" size="80"><br/>
            <label for="summary">Summary: </label><br/>
            <textarea name="summary" id="summary" cols="80" rows="8"></textarea><br/><br/>
            <input type="submit" value="Add">
            <input type="submit" name="cancel" value="Cancel">
        </form>
    </div>
</body>
</html>