<?php
    //Turn on error reporting
    ini_set('display_errors', 'On');
    include 'dbConnection.php';
    //open connection to database
    $mysqli = new mysqli($url, $user, $pswrd, $dbName, $port);
    if(!$mysqli || $mysqli->connect_errno){
        echo "Connection error " . $mysqli->connect_errno . " " . $mysqli->connect_error;
        }
    $state = $_POST['instState'] == "" ? NULL : $_POST['instState'];
    if(!($stmt = $mysqli->prepare("INSERT INTO institutions (name, city, state, country) VALUES (?,?,?,?)"))) {
        echo "Prepare failed: " . $stmt->errno . " " . $stmt->error;
    }
    if(!($stmt->bind_param("ssss", $_POST['instName'], $_POST['instCity'], $state, $_POST['instCountry']))){
        echo "Bind failed: " . $stmt->errno . " " . $stmt->error;
    }
    if(!$stmt->execute()){
        echo "Execute failed: " . $stmt->errno . " " . $stmt->error;
    } else {
        echo "Added " . $stmt->affected_rows . " row to institutions.";
    }
?>