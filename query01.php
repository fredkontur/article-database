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
        <title>New Article Information</title>
        <link rel="stylesheet" href="style.css">
    </head>

    <body>

        <form action="query01a.php" method="post">
            <fieldset>
                <?php
                    echo "<legend>Information for " . $_POST['artTitle'] . "</legend>\n";
                
                    if($_POST['newJrnl'] == "yes") {
                        echo "<br>\n";
                        echo "<fieldset>\n";
                        echo "<legend>Information for New Journal</legend>\n";
                        echo "<label>Journal Name:\n";
                        echo '<input type="text" name="journName" size="75" required></input>' . "\n";
                        echo "</label>\n";
                        echo "<br><br>\n";
                        echo "<label>Publisher:\n";
                        echo '<input type="text" name="publisher" required></input>' . "\n";
                        echo "</label>\n";
                        echo "<label>Abbreviation:\n";
                        echo '<input type="text" name="abbreviation" required></input>' . "\n";
                        echo "</label>";
                        echo "<label>Impact Factor:\n";
                        echo '<input type="number" name="impactFact" step="0.001"></input>' . "\n";
                        echo "</label>\n";
                        echo "</fieldset>\n";
                    }
                ?>
                
                <?php
                    $totAuthors = $_POST['numAuthors'];
                    
                    for($n = 1; $n <= $totAuthors; $n++) {
                        $sname = "author" . $n;
                        echo "<br>\n";
                        echo "<fieldset>\n";
                        echo "<legend>Author #" . $n . "</legend>\n";
                        echo "<label>Choose Existing Author: ";
                        
                        echo '<select name="' . $sname . '">' . "\n";
                            if(!($stmt = $mysqli->prepare("SELECT id, fname, mname, lname FROM author ORDER BY lname"))){
                                echo "Prepare failed: " . $stmt->errno . " " . $stmt->error;
                            }
                            if(!$stmt->execute()){
                                echo "Execute failed: " . $mysqli->connect_errno . " " . $mysqli->connect_error;
                            }
                            if(!$stmt->bind_result($id, $fname, $mname, $lname)){
                                echo "Bind failed: " . $mysqli->connect_errno . " " . $mysqli->connect_error;
                            }
                            while($stmt->fetch()){
                                echo '<option value="' . $id . '">' . $lname . ', ' . $fname . ' ' . $mname . "</option>\n";
                            }
                            $stmt->close();
                        echo "</select>\n";
                        echo "</label>\n";
                        echo "<br><br>\n";
                        echo "<p>Or Enter Information For New Author</p>\n";
                        echo "<label>First Name: \n";
                        echo '<input type="text" name="fName' . $n . '" value="Required"></input>' . "\n";
                        echo "</label>\n";
                        echo "<label>Middle Name: \n";
                        echo '<input type="text" name="mName' . $n . '"></input>' . "\n";
                        echo "</label>\n";
                        echo "<label>Last Name: \n";
                        echo '<input type="text" name="lName' . $n . '" value="Required"></input>' . "\n";
                        echo "</label>\n";
                        echo "</fieldset>\n";
                    }
                    
                    echo '<input type="hidden" name="artTitle" value="' . $_POST['artTitle'] . '"></input>' . "\n";
                    echo '<input type="hidden" name="pubDate" value="' . $_POST['pubDate'] . '"></input>' . "\n";
                    echo '<input type="hidden" name="doi" value="' . $_POST['doi'] . '"></input>' . "\n";
                    echo '<input type="hidden" name="journal" value="' . $_POST['journal'] . '"></input>' . "\n";
                    echo '<input type="hidden" name="newJrnl" value="' . $_POST['newJrnl'] . '"></input>' . "\n";
                    echo '<input type="hidden" name="numAuthors" value=' . $totAuthors . '></input>' . "\n";
                ?>
            <br>
            <input type="submit" value="Submit"></input>
        </form>
        
    </body>
</html>