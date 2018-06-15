<?php
    //Turn on error reporting
    ini_set('display_errors', 'On');
    include 'dbConnection.php';
    //open connection to database
    $mysqli = new mysqli($url, $user, $pswrd, $dbName, $port);
    if(!$mysqli || $mysqli->connect_errno) {
        echo "Connection error " . $mysqli->connect_errno . " " . $mysqli->connect_error;
    }
?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <title>Add References to Article</title>
        <link rel="stylesheet" href="style.css">
    </head>

    <body>

        <form action="query08a.php" method="post">
            <fieldset>
                <?php
                    if(!($stmt = $mysqli->prepare('SELECT title FROM articles WHERE doi=?'))){
                        echo "Prepare failed: " . $stmt->errno . " " . $stmt->error;
                    }
                    if(!($stmt->bind_param("s",$_POST['article']))){
                        echo "Bind failed: " . $stmt->errno . " " . $stmt->error;
                    }
                    if(!$stmt->execute()){
                        echo "Execute failed: " . $mysqli->connect_errno . " " . $mysqli->connect_error;
                    }
                    if(!$stmt->bind_result($title)){
                        echo "Bind failed: " . $mysqli->connect_errno . " " . $mysqli->connect_error;
                    }
                    $stmt->fetch();
                    echo "<legend>Add References for " . $title . "</legend>\n";
                    $stmt->close();
                    echo "<br>\n";
                
                    $totRefs = $_POST['numReferences'];
                    
                    for($n = 1; $n <= $totRefs; $n++) {
                        echo "<label>Reference #" . $n . ": \n";
                        $rname = "reference" . $n;
                        echo '<select name="' . $rname . '">' . "\n";
                            if(!($stmt = $mysqli->prepare('SELECT doi, title FROM articles 
                                                                WHERE doi <> ? AND
                                                                doi NOT IN (SELECT citedid FROM citations
                                                                    WHERE citingid = ?)
                                                                ORDER BY title'))) {
                                echo "Prepare failed: " . $stmt->errno . " " . $stmt->error;
                            }
                        if(!($stmt->bind_param("ss",$_POST['article'], $_POST['article']))){
                            echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
                        }
                            if(!$stmt->execute()){
                                echo "Execute failed: " . $mysqli->connect_errno . " " . $mysqli->connect_error;
                            }
                            if(!$stmt->bind_result($doi, $refTitle)){
                                echo "Bind failed: " . $mysqli->connect_errno . " " . $mysqli->connect_error;
                            }
                            while($stmt->fetch()){
                                echo '<option value="' . $doi . '">' . $refTitle . "</option>\n";
                            }
                            $stmt->close();
                        echo "</select>\n";
                        echo "</label>\n";
                        
                        echo "<br><br>\n";
                    }
                    
                    echo '<input type="hidden" name="article" value="' . $_POST['article'] . '"></input>' . "\n";
                    echo '<input type="hidden" name="title" value="' . $title . '"></input>' . "\n";
                    echo '<input type="hidden" name="numReferences" value=' . $totRefs . '></input>' . "\n";
                ?>
                
                <input type="submit" value="Submit"></input>
            </fieldset> 
        </form>
        
    </body>
</html>