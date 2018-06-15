<?php
    //Turn on error reporting
    ini_set('display_errors', 'On');
    include 'dbConnection.php';
    //open connection to database
    $mysqli = new mysqli($url, $user, $pswrd, $dbName, $port);
    if(!$mysqli || $mysqli->connect_errno){
        echo "Connection error " . $mysqli->connect_errno . " " . $mysqli->connect_error;
        }

    $totRefs = $_POST['numReferences'];
    $article = $_POST['article'];
    $check = 1;

    for($n = 1; $n <= $totRefs; $n++) {
        $ref = "reference" . $n;
        if(!($stmt = $mysqli->prepare("INSERT INTO citations (citingid, citedid) VALUES (?,?)"))) {
            echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
        }
        if(!($stmt->bind_param("ss",$article,$_POST[$ref]))){
            echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
        }
        if(!$stmt->execute()){
            echo "Execute failed: "  . $stmt->errno . " " . $stmt->error;
            $check = 0;
        } 
    }

    if($check = 1)
        echo "Added " . $totRefs . " reference citations to " . $_POST['title'] . ".";
?>