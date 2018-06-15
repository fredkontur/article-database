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
        <title>List of Authors by Impact Factor</title>
        <link rel="stylesheet" href="style.css">
    </head>

    <body>
        <h2>List of Authors in Order by the Average Impact Factor of the Journals in which They've Published</h2>
        
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Average Impact Factor</th>
                </tr>
            </thead>
            
            <tbody>
                <?php
                    if(!($stmt = $mysqli->prepare('SELECT fname, mname, lname, AVG(impfact) impactFactor
                                                        FROM author a
                                                        LEFT JOIN authors_articles authart ON a.id = authart.authid
                                                        LEFT JOIN articles art ON authart.artid = art.doi
                                                        LEFT JOIN journals j ON art.jid = j.id
                                                        GROUP BY a.id
                                                        ORDER BY impactFactor DESC'))) {
                        echo "Prepare failed: " . $stmt->errno . " " . $stmt->error;
                    }
                    if(!$stmt->execute()){
                        echo "Execute failed: " . $mysqli->connect_errno . " " . $mysqli->connect_error;
                    }
                    if(!$stmt->bind_result($fName, $mName, $lName, $impFact)){
                        echo "Bind failed: " . $mysqli->connect_errno . " " . $mysqli->connect_error;
                    }
                    while($stmt->fetch()){
                        echo "<tr>\n";
                        echo "<td>" . $lName . ", " . $fName . " " . $mName . "</td>\n";
                        echo "<td>" . round($impFact, 3) . "</td>\n";
                        echo "</tr>\n";
                    }
                    $stmt->close();
                ?>
            </tbody>
            
        </table>
    </body>
</html>