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
        <title>Add Keywords to Article</title>
        <link rel="stylesheet" href="style.css">
    </head>

    <body>

        <form action="query02a.php" method="post">
            <fieldset>
                <?php
                    if(!($stmt = $mysqli->prepare('SELECT title FROM articles WHERE doi=?'))){
                        echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
                    }
                    if(!($stmt->bind_param("s",$_POST['article']))){
                        echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
                    }
                    if(!$stmt->execute()){
                        echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
                    }
                    if(!$stmt->bind_result($title)){
                        echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
                    }
                    $stmt->fetch();
                    echo "<legend>Add Keywords for " . $title . "</legend>\n";
                    $stmt->close();
                    echo "<br>\n";
                
                    $totKwrds = $_POST['numKeywords'];
                    
                    for($n = 1; $n <= $totKwrds; $n++) {
                        echo "<b>Keyword #" . $n . "</b><br>\n";
                        echo "<label>Choose Existing Keyword: \n";
                        echo '<select name="keyword' . $n . '">' . "\n";
                        if(!($stmt = $mysqli->prepare("SELECT kwrd FROM keywords 
                                                            WHERE kwrd NOT IN (SELECT kwrd 
                                                                FROM articles_keywords
                                                                WHERE artid = ?) 
                                                            ORDER BY kwrd"))) {
                            echo "Prepare failed: " . $stmt->errno . " " . $stmt->error;
                        }
                        if(!($stmt->bind_param("s", $_POST['article']))){
                            echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
                        }
                        if(!$stmt->execute()){
                            echo "Execute failed: " . $mysqli->connect_errno . " " . $mysqli->connect_error;
                        }
                        if(!$stmt->bind_result($kwrd)){
                            echo "Bind failed: " . $mysqli->connect_errno . " " . $mysqli->connect_error;
                        }
                        while($stmt->fetch()){
                            echo '<option value="' . $kwrd . '"> ' . $kwrd . "</option>\n";
                        }
                        $stmt->close();
                        echo "</select>\n";
                        echo "</label>\n";
                        echo "<label>Or Create New Keyword: \n";
                        echo '<input type="text" name="newkwd' . $n . '"></input>' . "\n";
                        echo "</label>";
                        echo "<br><br>";
                    }
                    
                    echo '<input type="hidden" name="article" value="' . $_POST['article'] . '"></input>' . "\n";
                    echo '<input type="hidden" name="title" value="' . $title . '"></input>' . "\n";
                    echo '<input type="hidden" name="numKeywords" value=' . $totKwrds . '></input>' . "\n";
                ?>
                
                <input type="submit" value="Submit"></input>
            </fieldset> 
        </form>
        
    </body>
</html>