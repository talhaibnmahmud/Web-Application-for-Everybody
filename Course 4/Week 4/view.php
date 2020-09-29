<?php
    session_start();
    require_once 'pdo.php';
    require_once 'util.php';

    //Checking if the profile_id is missing on the GET request
    if (!isset($_GET['profile_id']) ) {
        $_SESSION['error'] = "Missing profile_id";
        header('Location: index.php');
        return;
    }


    //Checking for profile
    $row = profileFetch($pdo);
    if ( $row === false ) {
        //Redirect back if profile doesn't exist
        $_SESSION['error'] = 'Bad value for profile_id';
        header( 'Location: index.php' ) ;
        return;
    }

    //Assigning profile values to variables
    $fName = htmlentities($row['first_name']);
    $lName = htmlentities($row['last_name']);
    $email = htmlentities($row['email']);
    $headline = htmlentities($row['headline']);
    $summary = htmlentities($row['summary']);


    //Checking for position
    $count = rowCount($pdo);
    if($count) {
        //Getting position if exists
        $rows = positionFetch($pdo, $count);
    }

    //Checking for position
    $edu_count = eduCount($pdo);
    if($edu_count) {
        //Getting position if exists
        $edu = eduFetch($pdo, $edu_count);
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
        <h1>Profile Information</h1>

        <p>First Name: <?= $fName; ?></p>
        <p>Last Name: <?= $lName; ?></p>
        <p>Email: <?= $email; ?></p>
        <p>Headline: <br> <?= $headline; ?></p>
        <p>Summary: <br> <?= $summary; ?></p>
        <p>Position: </p>
        <ul>
            <?php
                if($count) {
                    foreach($rows as $ro) {
                        // Showing position
                        echo '<li> '.$ro['year'].': '.$ro['description'].'</li>';
                    }
                }
            ?>
        </ul>
        <p>Education: </p>
        <ul>
                <?php
                    if($edu_count) {
                        foreach($edu as $ed) {
                            //Getting institution
                            $institution_id = $ed['institution_id'];
                            $stmt = $pdo->prepare('SELECT name FROM institution WHERE institution_id LIKE :iid');
                            $stmt->execute(array(':iid' => $institution_id));
                            $ins = $stmt->fetch(PDO::FETCH_ASSOC);

                            // Showing institution
                            $institution = $ins['name'];
                            echo '<li> '.$ed['year'].': '.$institution.'</li>';
                        }
                    }
                ?>
        </ul>

        <p><a href="index.php">Done</a></p>
    </div>
</body>
</html>