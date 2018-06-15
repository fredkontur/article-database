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
        <title>List of Articles that Had Chosen Funder</title>
        <link rel="stylesheet" href="style.css">
    </head>

    <body>
        <?php
            if(!($stmt = $mysqli->prepare('SELECT name FROM funders WHERE id=?'))) {
                echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
            }
            if(!($stmt->bind_param("s",$_POST['funder']))){
                echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
            }
            if(!$stmt->execute()){
                echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
            }
            if(!$stmt->bind_result($fundName)){
                echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
            }
            $stmt->fetch();
            echo "<h2>List of Articles that Were Funded by the " . $fundName . "</h2>\n";
            $stmt->close();
        ?>
        
        <table>
            <thead>
                <tr>
                    <th>Article Title</th>
                    <th>Publication Date</th>
                    <th>Digital Object Identifier</th>
                    <th>Journal</th>
                </tr>
            </thead>
            
            <tbody>
                <?php
                    if(!($stmt = $mysqli->prepare('SELECT title, pubdate, doi, j.name
                                                        FROM articles a
                                                        LEFT JOIN journals j ON a.jid=j.id
                                                        INNER JOIN articles_funders af ON af.artid = a.doi
                                                        WHERE fundid = ?'))) {
                        echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
                    }
                    if(!($stmt->bind_param("s",$_POST['funder']))){
                        echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
                    }
                    if(!$stmt->execute()){
                        echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
                    }
                    if(!$stmt->bind_result($title, $pubDate, $doi, $journal)){
                        echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
                    }
                    while($stmt->fetch()){
                        echo "<tr>\n";
                        echo "<td>" . $title . "</td>\n";
                        echo "<td>" . $pubDate . "</td>\n";
                        echo "<td>" . $doi . "</td>\n";
                        echo "<td>" . $journal . "</td>\n";
                        echo "</tr>\n";
                    }
                    $stmt->close();
                ?>
            </tbody>
            
        </table>
    </body>
</html>