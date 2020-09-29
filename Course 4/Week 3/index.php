<?php
    session_start();
    require_once 'pdo.php';
    require_once 'util.php';

    //Model Code
    //Checking if any data exists on Profile    
    $empty = $pdo->query("SELECT profile_id FROM profile");
    $empty->execute();
    $count = $empty->rowCount();

    //Getting profile data if exists
    if($count !== 0) {
        $stmt = $pdo->query("SELECT first_name, last_name, headline, profile_id FROM profile");
        $rows = $stmt->fetchall(PDO::FETCH_ASSOC);
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Talha Ibne Mahmud</title>

    <?php require_once 'head.php'; ?>

    <style>
        table, th, td{
            border: 1px solid black;
            padding: 0.25em;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Talha Ibne Mahmud's Resume Registry</h1>

        <!-- <?= $count; ?> -->

        <?php
            //Link to login if user is not logged in
            if(!isset($_SESSION['name'])) {
                echo('<p><a href="login.php">Please log in</a></p>');
            } else {
                flashMessages();

                echo('<p><a href="logout.php">Logout</a></p>');
            }

            if($count) {
                //Showing Profile data if exists
                echo ('<table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Headline</th>');
                if(isset($_SESSION['name'])) {
                    echo ('<th>Action</th>');
                }
                echo ('</tr>
                    </thead>
                    <tbody>');
                
                foreach($rows as $row) {
                    echo ('<tr>');
                    echo ('<td><a href="view.php?profile_id='.$row['profile_id'].'">'.htmlentities($row['first_name']).' '.htmlentities($row['last_name']).'</a></td>');
                    echo '<td>'.htmlentities($row['headline']).'</td>';
                    if(isset($_SESSION['name'])) {
                        echo '<td><a href="edit.php?profile_id='.$row['profile_id'].'">Edit</a> <a href="delete.php?profile_id='.$row['profile_id'].'">Delete</a></td>';
                    }
                    echo '</tr>';
                }
                        
                echo('</tbody>
                </table>');
            } else {
                //Message if no profile exists
                echo('<p> No rows found </p>');
            }

            //Link to add profile if user is logged in
            if(isset($_SESSION['name'])) {
                echo('<p><a href="add.php">Add New Entry</a></p>');
            }
        ?>

    </div>
    
</body>
</html>