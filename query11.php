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
        <title>List of Articles that Cite Chosen Article</title>
        <link rel="stylesheet" href="style.css">
    </head>

    <body>
        <?php
            if(!($stmt = $mysqli->prepare('SELECT title FROM articles WHERE doi=?'))) {
                echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
            }
            if(!($stmt->bind_param("s",$_POST['article']))){
                echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
            }
            if(!$stmt->execute()){
                echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
            }
            if(!$stmt->bind_result($artTitle)){
                echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
            }
            $stmt->fetch();
            echo "<h2>List of Articles that Cite " . $artTitle . "</h2>\n";
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
                    if(!($stmt = $mysqli->prepare('SELECT title, pubdate, doi, j.name FROM citations c
                                                        INNER JOIN articles a ON c.citingid = a.doi
                                                        LEFT JOIN journals j ON a.jid = j.id
                                                        WHERE c.citedid=?
                                                        ORDER BY pubdate DESC'))) {
                        echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
                    }
                    if(!($stmt->bind_param("s",$_POST['article']))){
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