<?
    session_start();
    require_once 'pdo.php';
    require_once 'util.php';

    //Controller Code
    //Checking if the user is logged in
    if(!isset($_SESSION['name'])) {
        die('Not logged in');
    }
    //Going back to Index page if user pressed cancel
    if ( isset($_POST['cancel']) ) {
        header('Location: index.php');
        return;
    }

    $failure = FALSE;
    $profile_id = 0;


    //Validating the position data
    $message = validatePos();
    if(is_string($message)) {
        $_SESSION['wrong'] = $message;
        header('Location: add.php');
        return;
    }

    //Validating profile data
    $msg = validateProfile();
    if(is_string($msg)) {
        $_SESSION['wrong'] = $msg;
        header('Location: add.php');
        return;
    }



    //Model code
    //Checking all the data is present
    if (isset($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST['email']) && 
        isset($_POST['headline']) && isset($_POST['summary']) && $failure === FALSE) {
            //Prepare statement & insert into profile
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

            //Getting the last assigned profile_id(Primary KEY)
            $profile_id = $pdo->lastInsertId();
            //Inserting into position
            insertPos($pdo, $profile_id);

            //Redirect back to Index page
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

        <!-- Flash Message -->
        <?php flashMessages(); ?>

        <!-- <?= $profile_id; ?> -->

        <form method="post">
            <section>
                <label for="first_name">First Name: </label>
                <input type="text" name="first_name" id="fName" size="60">
            </section>
            <section>
                <label for="last_name">Last Name: </label>
                <input type="text" name="last_name" id="lName" size="60">
            </section>
            <section>
                <label for="email">Email: </label>
                <input type="text" name="email" id="email" size="30">
            </section>
            <section>
                <label for="headline">Headline: </label><br/>
                <input type="text" name="headline" id="headline" size="80">
            </section>
            <section>
                <label for="summary">Summary: </label><br/>
                <textarea name="summary" id="summary" cols="80" rows="8"></textarea>
            </section>
            <section>
                <label for="position">Position: </label>
                <input type="submit" id="addPos" value="+">
            </section>
            <div id="position_fields"></div>
            <section>
                <input type="submit" value="Add">
                <input type="submit" name="cancel" value="Cancel">
            </section>
        </form>
    </div>

    <script>      
        countPos = 0;
    </script>
    <script src="./position.js"></script>
</body>
</html>