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
        <title>Articles Listed in Sorted Order</title>
        <link rel="stylesheet" href="style.css">
    </head>

    <body>
        <?php
            if($_POST['artSort'] == "byTitle")
                echo "<h1>Articles Sorted by Title</h1>\n";
            else if($_POST['artSort'] == "byJournal")
                echo "<h1>Articles Sorted by Journal</h1>\n";
            else
                echo "<h1>Articles Sorted by Publication Date</h1>\n";
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
                    if($_POST['artSort'] == "byTitle") { 
                        if(!($stmt = $mysqli->prepare("SELECT title, pubdate, doi, j.name journal FROM articles a
                                                        LEFT JOIN journals j ON a.jid = j.id
                                                        ORDER BY title ASC"))) {
                                echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
                            }}
                    else if($_POST['artSort'] == "byJournal") { 
                        if(!($stmt = $mysqli->prepare("SELECT title, pubdate, doi, j.name journal FROM articles a
                                                        LEFT JOIN journals j ON a.jid = j.id
                                                        ORDER BY journal ASC"))) {
                                echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
                            }}
                    else { 
                        if(!($stmt = $mysqli->prepare("SELECT title, pubdate, doi, j.name journal FROM articles a
                                                        LEFT JOIN journals j ON a.jid = j.id
                                                        ORDER BY pubdate DESC"))) {
                                echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
                            }}

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