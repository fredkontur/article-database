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
        <title>List of Institutions by Impact Factor</title>
        <link rel="stylesheet" href="style.css">
    </head>

    <body>
        <h2>List of Institutions in Order by the Average Impact Factor of the Journals in which Their Authors Have Published</h2>
        
        <table>
            <thead>
                <tr>
                    <th>Institution Name</th>
                    <th>Location</th>
                    <th>Average Impact Factor</th>
                </tr>
            </thead>
            
            <tbody>
                <?php
                    if(!($stmt = $mysqli->prepare('SELECT inst.name, city, state, country, AVG(impfact) impactFactor
	                                                   FROM institutions inst
	                                                   LEFT JOIN (SELECT DISTINCT doi, impfact, instid
                                                            FROM articles a
                                                            LEFT JOIN journals j ON a.jid=j.id
                                                            INNER JOIN authors_articles authart ON a.doi = authart.artid
                                                            INNER JOIN authors_inst authinst ON authart.authid = authinst.authid 
                                                            WHERE pubdate >= startdate AND (pubdate <= enddate OR enddate IS NULL))
                                                        AS tbl_1 ON inst.id = tbl_1.instid
                                                        GROUP BY inst.id
                                                        ORDER BY impactFactor DESC'))) {
                        echo "Prepare failed: " . $stmt->errno . " " . $stmt->error;
                    }
                    if(!$stmt->execute()){
                        echo "Execute failed: " . $mysqli->connect_errno . " " . $mysqli->connect_error;
                    }
                    if(!$stmt->bind_result($name, $city, $state, $country, $impFact)){
                        echo "Bind failed: " . $mysqli->connect_errno . " " . $mysqli->connect_error;
                    }
                    while($stmt->fetch()){
                        echo "<tr>\n";
                        echo "<td>" . $name . "</td>\n";
                        if($state == "" || $state == NULL)
                            echo "<td>" . $city . ", " . $country . "</td>\n";
                        else
                            echo "<td>" . $city . ", " . $state . ", " . $country . "</td>\n";
                        echo "<td>" . round($impFact, 3) . "</td>\n";
                        echo "</tr>\n";
                    }
                    $stmt->close();
                ?>
            </tbody>
            
        </table>
    </body>
</html>