<?php
    //Turn on error reporting
    ini_set('display_errors', 'On');
    include 'dbConnection.php';
    //open connection to database
    $mysqli = new mysqli($url, $user, $pswrd, $dbName, $port);
    if(!$mysqli || $mysqli->connect_errno){
        echo "Connection error " . $mysqli->connect_errno . " " . $mysqli->connect_error;
	}

$totKwrds = $_POST['numKeywords'];
$article = $_POST['article'];
$check = 1;
                    
for($n = 1; $n <= $totKwrds; $n++) {
    $newkwd = "newkwd" . $n;
    $kwrd = "keyword" . $n;
    $k = ($_POST[$newkwd] == "") ? $_POST[$kwrd] : $_POST[$newkwd];
    if($_POST[$newkwd] != "") {
        if(!($stmt = $mysqli->prepare("INSERT INTO keywords (kwrd) VALUES (?)"))) {
            echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
        }
        if(!($stmt->bind_param("s", $k))){
            echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
        }
        if(!$stmt->execute()){
            echo "Execute failed: "  . $stmt->errno . " " . $stmt->error;
        } else {
            echo "Added " . $stmt->affected_rows . " row to keywords.<br>\n";
        }
        $stmt->close();
    }
    if(!($stmt = $mysqli->prepare("INSERT INTO articles_keywords (artid, kwrd) VALUES (?,?)"))) {
        echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
    }
    if(!($stmt->bind_param("ss", $article, $k))){
        echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
    }
    if(!$stmt->execute()){
        echo "Execute failed: "  . $stmt->errno . " " . $stmt->error;
        $check = 0;
    } 
    $stmt->close();
}
if($check = 1)
    echo "Added " . $totKwrds . " keywords to " . $_POST['title'] . ".";
?>