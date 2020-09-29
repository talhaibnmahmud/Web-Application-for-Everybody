<?php
    session_start();
    require_once 'pdo.php';
    unset($_SESSION['name']);
    // unset($_SESSION['error']);
    // unset($_SESSION['wrong']);
    // unset($_SESSION['success']);

    $salt = 'XyZzy12*_';
    $stored_hash = '1a52e17fa899cf40fb04cfc42e6352f1';  // Pw is php123



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
            $check = hash('md5', $salt.$_POST['pass']);
            if ( $check == $stored_hash ) {
                $_SESSION['name'] = $_POST['email'];
                error_log("Login success ".$_POST['email']);
                header("Location: index.php");
                return;
            } else {
                $_SESSION['error'] = "Incorrect password";
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

        <form method="POST" action="login.php">
            <label for="name">User Name</label>
            <input type="text" name="email" id="name"><br/>
            <label for="password">Password</label>
            <input type="text" name="pass" id="password"><br/>
            <input type="submit" value="Log In">
            <a href="index.php">Cancel</a></p>
        </form>

        <p>
            For a password hint, view source and find a password hint
            in the HTML comments.
            <!-- Hint: The password is the three character name of the
            programming language used in this class (all lower case)
            followed by 123. -->
        </p>
    </div>
</body>
</html>