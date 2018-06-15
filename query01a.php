<?php
    //Turn on error reporting
    ini_set('display_errors', 'On');
    include 'dbConnection.php';
    //open connection to database
    $mysqli = new mysqli($url, $user, $pswrd, $dbName, $port);
    if(!$mysqli || $mysqli->connect_errno){
        echo "Connection error " . $mysqli->connect_errno . " " . $mysqli->connect_error;
        }
    if($_POST['newJrnl'] == "yes") {
        $impFact = $_POST['impactFact'] == "" ? NULL : $_POST['impactFact'];
        if(!($stmt1 = $mysqli->prepare("INSERT INTO journals (name, publisher, abbr, impfact) VALUES (?, ?, ?, ?)"))) {
            echo "Prepare failed: " . $stmt->errno . " " . $stmt->error;
        }
        if(!($stmt1->bind_param("sssd", $_POST['journName'], $_POST['publisher'], $_POST['abbreviation'], $impFact))){
            echo "Bind failed: " . $stmt1->errno . " " . $stmt1->error;
        }
        if(!$stmt1->execute()){
            echo "Execute failed: " . $stmt1->errno . " " . $stmt1->error;
        } else {
            echo "Added " . $stmt1->affected_rows . " row to journals.<br>\n";
        }
        if(!($stmt2 = $mysqli->prepare("SET @journalID = LAST_INSERT_ID()"))) {
            echo "Prepare failed: " . $stmt2->errno . " " . $stmt2->error;
        }
        if(!$stmt2->execute()){
            echo "Execute failed: " . $stmt2->errno . " " . $stmt2->error;
        }
        if(!($stmt3 = $mysqli->prepare("INSERT INTO articles (doi, title, pubdate, jid) VALUES (?, ?, ?, @journalID)"))) {
            echo "Prepare failed: " . $stmt3->errno . " " . $stmt3->error;
        }
        if(!($stmt3->bind_param("sss", $_POST['doi'], $_POST['artTitle'], $_POST['pubDate']))){
            echo "Bind failed: " . $stmt3->errno . " " . $stmt3->error;
        }
        if(!$stmt3->execute()){
            echo "Execute failed: " . $stmt3->errno . " " . $stmt3->error;
        } else {
            echo "Added " . $stmt3->affected_rows . " row to articles.<br>\n";
        }
        $stmt1->close();
        $stmt2->close();
        $stmt3->close();
    }
    else {
        if(!($stmt = $mysqli->prepare("INSERT INTO articles (doi, title, pubdate, jid) VALUES (?, ?, ?, ?)"))) {
            echo "Prepare failed: " . $stmt->errno . " " . $stmt->error;
        }
        if(!($stmt->bind_param("sssi",$_POST['doi'],$_POST['artTitle'],$_POST['pubDate'],$_POST['journal']))){
            echo "Bind failed: " . $stmt->errno . " " . $stmt->error;
        }
        if(!$stmt->execute()){
            echo "Execute failed: " . $stmt->errno . " " . $stmt->error;
        } else {
            echo "Added " . $stmt->affected_rows . " row to articles.<br>\n";
        }
        $stmt->close();
    }

    $totAuthors = $_POST['numAuthors'];
    $doi = $_POST['doi'];

    for($n = 1; $n <= $totAuthors; $n++) {
        $aut = "author" . $n;
        $fname = "fName" . $n;
        $mname = "mName" . $n;
        $lname = "lName" . $n;
        $r = "Required";
        if(($_POST[$fname] == $r || $_POST[$fname] == "") && $_POST[$mname] == "" && ($_POST[$lname] == $r || $_POSt[$lname] == "")) {
            if(!($stmt = $mysqli->prepare("INSERT INTO authors_articles (artid, authid, ord_of_app) VALUES (?,?,?)"))) {
                echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
            }
            if(!($stmt->bind_param("sii", $doi, $_POST[$aut], $n))){
                echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
            }
            if(!$stmt->execute()){
                echo "Execute failed: "  . $stmt->errno . " " . $stmt->error;
            } 
            else {
                echo "Added " . $stmt->affected_rows . " author to " . $_POST['artTitle'] . ".<br>\n";
            }
            $stmt->close();
        }
        else {
            $middle = $_POST[$mname] == "" ? NULL : $_POST[$mname];
            if(!($stmt1 = $mysqli->prepare("INSERT INTO author (fname, mname, lname) VALUES (?, ?, ?)"))) {
                echo "Prepare failed: "  . $stmt1->errno . " " . $stmt1->error;
            }
            if(!($stmt1->bind_param("sss", $_POST[$fname], $middle, $_POST[$lname]))){
                echo "Bind failed: "  . $stmt1->errno . " " . $stmt1->error;
            }
            if(!$stmt1->execute()){
                echo "Execute failed: "  . $stmt1->errno . " " . $stmt1->error;
            } else {
                echo "Added " . $stmt1->affected_rows . " row to authors.<br>\n";
            }
            if(!($stmt2 = $mysqli->prepare("SET @authorID = LAST_INSERT_ID()"))) {
                echo "Prepare failed: "  . $stmt2->errno . " " . $stmt2->error;
            }
            if(!$stmt2->execute()){
                echo "Execute failed: "  . $stmt1->errno . " " . $stmt1->error;
            }
            if(!($stmt3 = $mysqli->prepare("INSERT INTO authors_articles (artid, authid, ord_of_app) VALUES (?, @authorID, ?)"))) {
                echo "Prepare failed: "  . $stmt3->errno . " " . $stmt3->error;
            }
            if(!($stmt3->bind_param("si", $doi, $n))){
                echo "Bind failed: "  . $stmt3->errno . " " . $stmt3->error;
            }
            if(!$stmt3->execute()){
                echo "Execute failed: "  . $stmt3->errno . " " . $stmt3->error;
            } else {
                echo "Added " . $stmt3->affected_rows . " author to " . $_POST['artTitle'] . ".<br>\n";
            }
            $stmt1->close();
            $stmt2->close();
            $stmt3->close();
        }
    }
?>