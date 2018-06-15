<?php
    //Turn on error reporting
    ini_set('display_errors', 'On');
    include 'dbConnection.php';
    //open connection to database
    $mysqli = new mysqli($url, $user, $pswrd, $dbName, $port);
    if(!$mysqli || $mysqli->connect_errno){
        echo "Connection error " . $mysqli->connect_errno . " " . $mysqli->connect_error;
        }

    $currInst = ($_POST['currInst'] == "yes") ? 1 : 0; 

    if(!($stmt = $mysqli->prepare("INSERT INTO authors_inst (authid, instid, currinst, startdate) VALUES (?,?,?,?)"))) {
        echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
    }
    if(!($stmt->bind_param("iiis",$_POST['author'],$_POST['institution'],$currInst,$_POST['startDate']))){
        echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
    }
    if(!$stmt->execute()){
        echo "Execute failed: "  . $stmt->errno . " " . $stmt->error;
    } else {
        echo "Added " . $stmt->affected_rows . " row to authors_inst.";
    }
?>