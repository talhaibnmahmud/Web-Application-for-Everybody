<?php 
    session_start();
    if ( isset($_POST['cancel'] ) ) {
        header("Location: index.php");
        return;
    }

    $salt = 'XyZzy12*_';
    $stored_hash = '1a52e17fa899cf40fb04cfc42e6352f1';  // Pw is php123



    // Check to see if we have some POST data, if we do process it
    if ( isset($_POST['email']) && isset($_POST['pass']) ) {
        if ( strlen($_POST['email']) < 1 || strlen($_POST['pass']) < 1 ) {
            // $failure = "Email and password are required";
            $_SESSION['error'] = "Email and password are required";
            header("Location: login.php");
            return;
        } elseif (strpos($_POST['email'], '@') == FALSE) {
            // $failure = "Email must have an at-sign(@)";
            $_SESSION['error'] = "Email must have an at-sign (@)";
            header("Location: login.php");
            return;
        }
        else {
            $check = hash('md5', $salt.$_POST['pass']);
            if ( $check == $stored_hash ) {
                $_SESSION['name'] = $_POST['email'];
                header("Location: view.php");   //?name=".urlencode($_POST['email'])
                error_log("Login success ".$_POST['email']);
                return;
            } else {
                // $failure = "Incorrect password";
                $_SESSION['error'] = "Incorrect password";
                header("Location: login.php");
                error_log("Login fail ".$_POST['email']." $check");
                return;
            }
        }
    }
?>


<!DOCTYPE html>
<html>
<head>
    <?php require_once "bootstrap.php"; ?>
    <title>Talha Ibne Mahmud</title>
</head>
<body>
    <div class="container">
        <h1>Please Log In</h1>
        <?php
            if(isset($_SESSION['error'])) {
                echo ('<p style = "color: red;">'.htmlentities($_SESSION['error'])."</p>\n");
                unset($_SESSION['error']);
            }
        ?>
        <form method="POST">
            <label for="name">User Name</label>
            <input type="text" name="email" id="name"><br/>
            <label for="password">Password</label>
            <input type="text" name="pass" id="password"><br/>
            <input type="submit" value="Log In">
            <input type="submit" name="cancel" value="Cancel">
        </form>
        <p>
            For a password hint, view source and find a password hint
            in the HTML comments.
            <!-- Hint: The password is the name of a language taught in this
            class (all lower case) followed by 123. -->
        </p>
    </div>

    <script data-cfasync="false" src="/cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script>
</body>
</html>