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
        <title>List of Articles Filtered by Impact Factor</title>
        <link rel="stylesheet" href="style.css">
    </head>

    <body>
        <?php
        echo "<h2>Articles Published in Journals with an Impact Factor Greater Than " . $_POST['impactFactor'] . "</h2>";
        ?>
        
        <table>
            <thead>
                <tr>
                    <th>Article Title</th>
                    <th>Publication Date</th>
                    <th>Digital Object Identifier</th>
                    <th>Journal</th>
                    <th>Impact Factor</th>
                </tr>
            </thead>
            
            <tbody>
                <?php
                    if(!($stmt = $mysqli->prepare('SELECT title, pubdate, doi, j.name, impfact
                                                        FROM articles a
                                                        INNER JOIN journals j ON a.jid=j.id
                                                        WHERE impfact > ?
                                                        ORDER BY impfact DESC'))) {
                        echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
                    }
                    if(!($stmt->bind_param("d",$_POST['impactFactor']))){
                        echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
                    }
                    if(!$stmt->execute()){
                        echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
                    }
                    if(!$stmt->bind_result($title, $pubDate, $doi, $journal, $impFact)){
                        echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
                    }
                    while($stmt->fetch()){
                        echo "<tr>\n";
                        echo "<td>" . $title . "</td>\n";
                        echo "<td>" . $pubDate . "</td>\n";
                        echo "<td>" . $doi . "</td>\n";
                        echo "<td>" . $journal . "</td>\n";
                        echo "<td>" . $impFact . "</td>\n";
                        echo "</tr>\n";
                    }
                    $stmt->close();
                ?>
            </tbody>
            
        </table>
    </body>
</html>