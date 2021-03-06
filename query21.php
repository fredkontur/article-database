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
        <title>Funder List - Count of Articles from a Certain Journal</title>
        <link rel="stylesheet" href="style.css">
    </head>

    <body>
        <?php
            if(!($stmt = $mysqli->prepare('SELECT name FROM journals WHERE id = ?'))) {
                echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
            }
            if(!($stmt->bind_param("s",$_POST['journal']))){
                echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
            }
            if(!$stmt->execute()){
                echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
            }
            if(!$stmt->bind_result($journal)){
                echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
            }
            $stmt->fetch();
            echo '<h2>How Many Funders\' Articles Have Been Published in ' . $journal . "</h2>\n";
            $stmt->close();
        ?>
        
        <table>
            <thead>
                <tr>
                    <th>Funder Name</th>
                    <th>Type</th>
                    <th>Country</th>
                    <th>&#35; Articles</th>
                </tr>
            </thead>
            
            <tbody>
                <?php
                    if(!($stmt = $mysqli->prepare('SELECT name, type, country, COUNT(doi) numArt
	                                                   FROM funders f
	                                                   INNER JOIN articles_funders artfund ON f.id = artfund.fundid
	                                                   INNER JOIN articles a ON artfund.artid = a.doi
	                                                   WHERE a.jid = ?
	                                                   GROUP BY f.id
	                                                   ORDER BY numArt DESC'))) {
                        echo "Prepare failed: " . $stmt->errno . " " . $stmt->error;
                    }
                    if(!($stmt->bind_param("s", $_POST['journal']))){
                        echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
                    }
                    if(!$stmt->execute()){
                        echo "Execute failed: " . $mysqli->connect_errno . " " . $mysqli->connect_error;
                    }
                    if(!$stmt->bind_result($name, $type, $country, $numArt)){
                        echo "Bind failed: " . $mysqli->connect_errno . " " . $mysqli->connect_error;
                    }
                    while($stmt->fetch()){
                        echo "<tr>\n";
                        echo "<td>" . $name . "</td>\n";
                        echo "<td>" . $type . "</td>\n";
                        echo "<td>" . $country . "</td>\n";
                        echo "<td>" . $numArt . "</td>\n";
                        echo "</tr>\n";
                    }
                    $stmt->close();
                ?>
            </tbody>
            
        </table>
    </body>
</html>