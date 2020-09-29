<?php

    //Flash Message function
    function flashMessages() {
        //Success Message
        if ( isset($_SESSION['success']) ) {
            echo('<p style="color: green;">'.htmlentities($_SESSION['success'])."</p>\n");
            unset($_SESSION['success']);
        }
        //Login error message
        if ( isset($_SESSION['error']) ) {
            echo('<p style="color: red;">'.htmlentities($_SESSION['error'])."</p>\n");
            unset($_SESSION['error']);
        }
        //Data entry error message
        if(isset($_SESSION['wrong'])) {
            echo ('<p style = "color: red">').$_SESSION['wrong']."</p>\n";
            unset($_SESSION['wrong']);
        }
    }

    //Profile Validation Function
    function validateProfile() {
        //Checking all the fields are set & non-empty
        if ((isset($_POST['first_name']) && strlen($_POST['first_name']) < 1) || 
            (isset($_POST['last_name']) && strlen($_POST['last_name']) < 1) || 
            (isset($_POST['email']) && strlen($_POST['email']) < 1) || 
            (isset($_POST['headline']) && strlen($_POST['headline']) < 1) || 
            (isset($_POST['summary']) && strlen($_POST['summary']) < 1)) {

                return "All fields are required";
        }
        //Checking the correct email format
        if (isset($_POST['email']) && strpos($_POST['email'], '@') === FALSE) {
            return "Email must have an at-sign (@)";
        }
        return TRUE;
    }

    //Position Validation function
    function validatePos() {
        for($i=1; $i<=9; $i++) {
            //Skipping empty positions
            if ( ! isset($_POST['year'.$i]) ) continue;
            if ( ! isset($_POST['desc'.$i]) ) continue;

            $year = $_POST['year'.$i];
            $desc = $_POST['desc'.$i];

            //Checking all the fields are set
            if ( strlen($year) == 0 || strlen($desc) == 0 ) {
                return "All fields are required";
            }
    
            //Checking for numeric year
            if ( ! is_numeric($year) ) {
                return "Position year must be numeric";
            }
        }
        return TRUE;
    }

    //Position insert function
    function insertPos(PDO $pdo, $profile_id) {
        //Setting initial rank
        $rank = 1;
        for($i=1; $i<=9; $i++) {
            //Skipping empty positions
            if ( ! isset($_POST['year'.$i]) ) continue;
            if ( ! isset($_POST['desc'.$i]) ) continue;
            $year = $_POST['year'.$i];
            $desc = $_POST['desc'.$i];

            //Prepare statement & insert
            $stmt = $pdo->prepare('INSERT INTO Position
                (profile_id, rank, year, description) 
            VALUES ( :pid, :rank, :year, :desc)');
            $stmt->execute(array(
                ':pid' => $profile_id,
                ':rank' => $rank,
                ':year' => $year,
                ':desc' => $desc)
            );
            $rank++;
        }
    }

    //position update function (Simple implement)
    function updatePos(PDO $pdo, $profile_id) {
        //Delete all the old position data 
        $stmt = $pdo->prepare('DELETE FROM Position
            WHERE profile_id=:pid');
        $stmt->execute(array( ':pid' => $_REQUEST['profile_id']));

        // Insert the new position entries
        insertPos($pdo, $profile_id);
    }

    //Checking for empty rows
    function rowCount($pdo) {
        $empty = $pdo->query("SELECT profile_id FROM position");
        $empty->execute();
        $count = $empty->rowCount();
        return $count;
    }

    //Fetch position function
    function positionFetch(PDO $pdo, $count) {
        if($count !== 0) {
            $stmt = $pdo->prepare("SELECT year, description FROM position WHERE profile_id = :xyz ORDER BY rank");
            $stmt->execute(array(":xyz" => $_GET['profile_id']));
            $rows = $stmt->fetchall(PDO::FETCH_ASSOC);

            return $rows;
        }
    }

    //Fetch profile function
    function profileFetch(PDO $pdo) {
        $stmt = $pdo->prepare("SELECT * FROM profile where profile_id = :xyz");
        $stmt->execute(array(":xyz" => $_GET['profile_id']));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row;
    }