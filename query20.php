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
        <title>Funders List - Count of Articles with Certain Keywords</title>
        <link rel="stylesheet" href="style.css">
    </head>

    <body>
        <?php
            echo '<h2>How Many Funders\' Articles have the Keyword "' . $_POST['keyword'] . '"' . "</h2>\n";
        ?>
        
        <table>
            <thead>
                <tr>
                    <th>Funder Name</th>
                    <th>Type</th>
                    <th>Country</th>
                    <th># Articles</th>
                </tr>
            </thead>
            
            <tbody>
                <?php
                    if(!($stmt = $mysqli->prepare('SELECT name, type, country, COUNT(doi) numArt
	                                                   FROM funders f
	                                                   INNER JOIN articles_funders artfund ON f.id = artfund.fundid
	                                                   INNER JOIN articles a ON artfund.artid = a.doi
	                                                   INNER JOIN articles_keywords akw ON akw.artid = a.doi
	                                                   WHERE kwrd = ?
	                                                   GROUP BY f.id
	                                                   ORDER BY numArt DESC'))) {
                        echo "Prepare failed: " . $stmt->errno . " " . $stmt->error;
                    }
                    if(!($stmt->bind_param("s", $_POST['keyword']))){
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