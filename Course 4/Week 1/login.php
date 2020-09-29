<?php
    session_unset();
    session_start();
    require_once 'pdo.php';

    if(isset($_POST['cancel'])) {
        header('Location: index.php');
        return;
    }

    // Check to see if we have some POST data, if we do process it
    if ( isset($_POST['email']) && isset($_POST['pass']) ) {
        if ( strlen($_POST['email']) < 1 || strlen($_POST['pass']) < 1 ) {
            $_SESSION['error'] = "User name and password are required";
            header("Location: login.php");
            return;
        } elseif (strpos($_POST['email'], '@') == FALSE) {
            $_SESSION['error'] = "Email must have an at-sign (@)";
            header("Location: login.php");
            return;
        } else {
            $salt = 'XyZzy12*_';
            $check = hash('md5', $salt.$_POST['pass']);
            $stmt = $pdo->prepare('SELECT user_id, name FROM users
                WHERE email = :em AND password = :pw');
            $stmt->execute(array( ':em' => $_POST['email'], ':pw' => $check));
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ( $row !== false ) {
                $_SESSION['name'] = $row['name'];
                $_SESSION['user_id'] = $row['user_id'];
                error_log("Login success ".$_POST['email']);
                header("Location: index.php");
                return;
            } else {
                $_SESSION['error'] = "Incorrect user name or password";
                header("Location: login.php");
                error_log("Login fail ".$_POST['email']." $check");
                return;
            }
        }
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Talha Ibne Mahmud</title>

    <?php require_once 'head.php'; ?>
    <script src="validation.js"></script>
</head>
<body>
    <div class="container">
        <h1>Please Log In</h1>

        <?php
            if(isset($_SESSION['error'])) {
                echo ('<p style = "color: red">').$_SESSION['error']."</p>\n";
                unset($_SESSION['error']);
            }
        ?>

        <form action="login.php" method="post">
            <label for="email">Email</label>
            <input type="text" name="email" id="email"><br/>
            <label for="id_1723">Password</label>
            <input type="password" name="pass" id="id_1723"><br/>
            <input type="submit" onclick="return doValidate();" value="Log In">
            <input type="submit" name="cancel" value="Cancel">
        </form>

        <p>
            For a password hint, view source and find an account and password hint
            in the HTML comments.
            <!-- Hint: 
            The account is umsi@umich.edu
            The password is the three character name of the 
            programming language used in this class (all lower case) 
            followed by 123. -->
        </p>
    </div>
</body>
</html>