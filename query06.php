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
        <title>Change Author Status at Institution</title>
        <link rel="stylesheet" href="style.css">
        <link rel="stylesheet" href="http://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
        <script src="http://code.jquery.com/jquery-1.10.2.js"></script>
        <script src="http://code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
        <script>
            $(function() {
                $( 'input[type="date"]' ).datepicker({
                    dateFormat: 'yy-mm-dd'
                });
            });
        </script>
    </head>

    <body>

        <form action="query06a.php" method="post">
            <fieldset>
                <?php
                    if(!($stmt = $mysqli->prepare("SELECT fname, mname, lname FROM author WHERE id=?"))){
                        echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
                    }
                    if(!($stmt->bind_param("i",$_POST['author']))){
                        echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
                    }
                                        if(!$stmt->execute()){
                        echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
                    }
                    if(!$stmt->bind_result($fname, $mname, $lname)){
                        echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
                    }
                    $stmt->fetch();
                    echo "<legend>Change Institution Status for " . $fname . " " . $mname . " " . $lname . "</legend>\n";
                    $stmt->close();
                    echo "<br>\n";
                    
                    echo "<label>Author's Current Institutions: \n";
                        echo '<select name="institution">' . "\n";
                            if(!($stmt = $mysqli->prepare("SELECT id, name FROM authors_inst  
                                    INNER JOIN institutions ON instid = id
                                    WHERE authid = ? AND currinst = 1 
                                    ORDER BY name"))){
                                echo "Prepare failed: " . $stmt->errno . " " . $stmt->error;
                            }
                            if(!($stmt->bind_param("d",$_POST['author']))){
                                echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
                            }
                            if(!$stmt->execute()){
                                echo "Execute failed: " . $mysqli->connect_errno . " " . $mysqli->connect_error;
                            }
                            if(!$stmt->bind_result($id, $name)){
                                echo "Bind failed: " . $mysqli->connect_errno . " " . $mysqli->connect_error;
                            }
                            while($stmt->fetch()){
                                echo '<option value="' . $id . '">' . $name. "</option>\n";
                            }
                            $stmt->close();
                    echo "</select>\n";
                    echo "</label>\n";
                    
                    echo '<input type="hidden" name="author" value=' . $_POST['author'] . '></input>' . "\n";
                ?>
                
                <label>End Date: 
                    <input type="date" name="enddate"></input>
                </label>
                
                <input type="submit" value="Submit"></input>
            </fieldset> 
        </form>
        
    </body>
</html>