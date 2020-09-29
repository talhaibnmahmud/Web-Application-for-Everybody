<?php 
    session_start();
    //Checking if the user is logged in
    if(!isset($_SESSION['name'])) {
        die('Not logged in');
    }

    // if(!isset($_REQUEST['term'])) {
    //     die('Missing required parameter');
    // }


    require_once 'pdo.php';
    
    if(isset($_REQUEST['term'])) {
        $stmt = $pdo->prepare('SELECT name FROM Institution WHERE name LIKE :prefix');
        $stmt->execute(array( ':prefix' => $_REQUEST['term']."%"));
        $retval = array();
        while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
            $retval[] = $row['name'];
        }
    } else {
        $stmt = $pdo->prepare('SELECT name FROM Institution');
        $stmt->execute();
        $retval = array();
        while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
            $retval[] = $row['name'];
        }
    }
    
    header('Content-Type: application/json; charset=utf-8');
    echo(json_encode($retval, JSON_PRETTY_PRINT));