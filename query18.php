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
        <title>Institution List - Count of Articles with Certain Keywords</title>
        <link rel="stylesheet" href="style.css">
    </head>

    <body>
        <?php
            echo '<h2>How Many Articles Have Institutions Published with the Keyword "' . $_POST['keyword'] . '"' . "</h2>\n";
        ?>
        
        <table>
            <thead>
                <tr>
                    <th>Institution Name</th>
                    <th>Location</th>
                    <th>&#35; Articles</th>
                </tr>
            </thead>
            
            <tbody>
                <?php
                    if(!($stmt = $mysqli->prepare('SELECT DISTINCT inst.name, city, state, country, COUNT(doi) numArt
	                                                   FROM institutions inst
	                                                   INNER JOIN (SELECT DISTINCT doi, instid
                                                            FROM articles a
                                                            LEFT JOIN journals j ON a.jid=j.id
                                                            INNER JOIN authors_articles authart ON a.doi = authart.artid
                                                            INNER JOIN authors_inst authinst ON authart.authid = authinst.authid 
                                                            WHERE pubdate >= startdate AND (pubdate <= enddate OR enddate IS NULL))
                                                        AS tbl_1 ON inst.id = tbl_1.instid
                                                        INNER JOIN articles_keywords akw ON tbl_1.doi = akw.artid
                                                        WHERE kwrd=?
                                                        GROUP BY inst.id
                                                        ORDER BY numArt DESC'))) {
                        echo "Prepare failed: " . $stmt->errno . " " . $stmt->error;
                    }
                    if(!($stmt->bind_param("s", $_POST['keyword']))){
                        echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
                    }
                    if(!$stmt->execute()){
                        echo "Execute failed: " . $mysqli->connect_errno . " " . $mysqli->connect_error;
                    }
                    if(!$stmt->bind_result($name, $city, $state, $country, $numArt)){
                        echo "Bind failed: " . $mysqli->connect_errno . " " . $mysqli->connect_error;
                    }
                    while($stmt->fetch()){
                        echo "<tr>\n";
                        echo "<td>" . $name . "</td>\n";
                        if($state == "" || $state == NULL)
                            echo "<td>" . $city . ", " . $country . "</td>\n";
                        else
                            echo "<td>" . $city . ", " . $state . ", " . $country . "</td>\n";
                        echo "<td>" . $numArt . "</td>\n";
                        echo "</tr>\n";
                    }
                    $stmt->close();
                ?>
            </tbody>
            
        </table>
    </body>
</html>