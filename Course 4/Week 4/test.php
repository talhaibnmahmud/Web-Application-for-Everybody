<?php
    session_start();
    require_once 'pdo.php';

    $school = 'Duke University';

    $stmt = $pdo->prepare('SELECT institution_id FROM Institution WHERE name LIKE :school');
    $stmt->execute(array(':school' => $school));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if($row){
        echo $row['institution_id'];
    } else {
        echo '0';
    }