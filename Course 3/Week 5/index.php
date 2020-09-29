<?php
    session_start();
    require_once 'pdo.php';

    $stmt = $pdo->query("SELECT make, model, year, mileage, autos_id FROM autos");
    $rows = $stmt->fetchall(PDO::FETCH_ASSOC);

    // $empty = $pdo->query("SELECT EXISTS(SELECT 1 FROM autos)");
    // $count = $empty->execute();
    // $empty = $pdo->query("SELECT COUNT(*) FROM autos");
    // $empty->execute();
    $empty = $pdo->query("SELECT autos_id FROM autos");
    $empty->execute();
    $count = $empty->rowCount();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Talha Ibne Mahmud</title>

    <?php require_once 'head.php'; ?>
    <style>
        table{
            margin: 0;
        }
        td{
            border: 1px solid black;
            margin: 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Welcome to the Automobiles Database</h1>

        <!-- <?php
            echo ($count);
        ?> -->

        <?php
            if(!isset($_SESSION['name'])){
                echo ('<p>
                    <a href="login.php">Please log in</a>
                </p>
                <p>
                    Attempt to <a href="add.php">add data</a> without logging in
                </p>');
            }
            
            if(isset($_SESSION['name'])){
                if ( isset($_SESSION['success']) ) {
                    echo('<p style="color: green;">'.htmlentities($_SESSION['success'])."</p>\n");
                    unset($_SESSION['success']);
                }
                if(isset($_SESSION['error'])) {
                    echo ('<p style = "color: red">').$_SESSION['error']."</p>\n";
                    unset($_SESSION['error']);
                }


                if(!$count) {
                    echo ('<p> No rows found </p>');
                } else {
                    echo('<table border = "1">
                        <thead>
                            <td>Make</td>
                            <td>Model</td>
                            <td>Year</td>
                            <td>Mileage</td>
                            <td>Action</td>
                        </thead>
                        <tbody>');
                    
                    foreach($rows as $row) {
                        echo '<tr>';
                        echo '<td>'.htmlentities($row['make']).'</td>';
                        echo '<td>'.htmlentities($row['model']).'</td>';
                        echo '<td>'.htmlentities($row['year']).'</td>';
                        echo '<td>'.htmlentities($row['mileage']).'</td>';
                        echo '<td><a href="edit.php?autos_id='.$row['autos_id'].'">Edit</a> / <a href="delete.php?autos_id='.$row['autos_id'].'">Delete</a></td>';
                        echo '</tr>';
                    }

                    echo('</tbody>
                    </table>');
                }

                echo('<p>
                    <a href="add.php">Add New Entry</a>
                </p>
                <p>
                    <a href="logout.php">Logout</a>
                </p>');
            }
        ?>

    </div>
</body>
</html>