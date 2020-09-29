<?php
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
    //Going back to Index page if profile_id is missing on the GET request
    if (!isset($_GET['profile_id']) ) {
        $_SESSION['error'] = "Missing profile_id";
        header('Location: index.php');
        return;
    }

    $failure = FALSE;
    $profile_id = 0;

    //Validating the position data
    $message = validatePos();
    if(is_string($message)) {
        $_SESSION['wrong'] = $message;
        header("Location: edit.php?profile_id=".$_POST['profile_id']);
        return;
    }

    //Validating profile data
    $msg = validateProfile();
    if(is_string($msg)) {
        $_SESSION['wrong'] = $msg;
        header("Location: edit.php?profile_id=".$_POST['profile_id']);
        return;
    }



    //Model Code
    //Checking all the data is present
    if (isset($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST['email']) && 
        isset($_POST['headline']) && isset($_POST['summary']) && $failure === FALSE) {
            //Prepare statement & update profile
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
                ':pid' => $_POST['profile_id'])
            );

            $profile_id = $_POST['profile_id'];
            //Updating position
            updatePos($pdo, $profile_id);

            //Redirect back to Index page
            $_SESSION['success'] = 'Profile updated';
            header( 'Location: index.php' ) ;
            return;
    }



    //View Code
    //Trying to get profile
    $row = profileFetch($pdo);
    //Checking if profile exists
    if ( $row === false ) {
        //Redirect back to Index page if profile doesn't exist
        $_SESSION['error'] = 'Bad value for profile_id';
        header( 'Location: index.php' ) ;
        return;
    }

    //Assigning profile datas to variables
    $fName = htmlentities($row['first_name']);
    $lName = htmlentities($row['last_name']);
    $email = htmlentities($row['email']);
    $headline = htmlentities($row['headline']);
    $summary = htmlentities($row['summary']);
    $profile_id = $row['profile_id'];


    //Checking for position data
    $count = rowCount($pdo);
    if($count) {
        //Getting position data if exists
        $rows = positionFetch($pdo, $count);
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
        <h1>Editing Profile for <?= htmlentities($_SESSION['name']); ?></h1>

        <?php flashMessages(); ?>

        <form method="post">
            <section>
                <label for="first_name">First Name: </label>
                <input type="text" name="first_name" id="fName" size="60" value="<?= $fName ?>">
            </section>
            <section>
                <label for="last_name">Last Name: </label>
                <input type="text" name="last_name" id="lName" size="60" value="<?= $lName ?>">
            </section>
            <section>
                <label for="email">Email: </label>
                <input type="text" name="email" id="email" size="30" value="<?= $email ?>">
            </section>
            <section>
                <label for="headline">Headline: </label><br/>
                <input type="text" name="headline" id="headline" size="80" value="<?= $headline ?>">
            </section>
            <section>
                <label for="summary">Summary: </label><br/>
                <textarea name="summary" id="summary" cols="80" rows="8"><?= $summary ?></textarea>
            </section>
            <section>
                <label for="position">Position: </label>
                <input type="submit" id="addPos" value="+">
            </section>
            <div id="position_fields">
                <?php
                    $countPos = 0;
                    if($count) {
                        foreach($rows as $ro) {
                            $countPos++;

                            echo '<div id = "position'.$countPos.'">';
                                echo '<p>Year: ';
                                    echo '<input type="text" name="year'.$countPos.'" value="'.$ro['year'].'" />';
                                    echo '<input type="button" value="-" onclick="$(\'#position'.$countPos.'\').remove();return false;" />';
                                echo '</p>';
                                echo '<textarea name="desc'.$countPos.'" rows="8" cols="80">'.$ro['description'].'</textarea>';
                            echo '</div>';
                        }
                    }
                ?>
            </div>
            <section>
                <input type="hidden" name="profile_id" value="<?= $profile_id ?>">
                <input type="submit" value="Save">
                <input type="submit" name="cancel" value="Cancel">
            </section>
        </form>
    </div>

    <script>
        // Getting the position count 
        countPos = <?= $countPos ?>;
    </script>
    <script src="./position.js"></script>
</body>
</html>