<?php
    //Turn on error reporting
    ini_set('display_errors', 'On');
    include 'dbConnection.php';
    //open connection to database
    $mysqli = new mysqli($url, $user, $pswrd, $dbName, $port);
    if(!$mysqli || $mysqli->connect_errno){
        echo "Connection error " . $mysqli->connect_errno . " " . $mysqli->connect_error;
        }
    $type = $_POST['fundType'] == "" ? NULL : $_POST['fundType'];
    if(!($stmt = $mysqli->prepare("INSERT INTO funders (name, type, country) VALUES (?,?,?)"))) {
        echo "Prepare failed: " . $stmt->errno . " " . $stmt->error;
    }
    if(!($stmt->bind_param("sss", $_POST['fundName'], $type, $_POST['fundCountry']))){
        echo "Bind failed: " . $stmt->errno . " " . $stmt->error;
    }
    if(!$stmt->execute()){
        echo "Execute failed: " . $stmt->errno . " " . $stmt->error;
    } else {
        echo "Added " . $stmt->affected_rows . " row to funders.";
    }
?>