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
        <title>Display Table</title>
        <link rel="stylesheet" href="style.css">
    </head>

    <body>
        <?php
            echo "<h2>" . $_POST['tbl'] . " table</h2>\n";
        ?>
        
        <table>
            <thead>
                <tr>
                    <?php
                        if($_POST['tbl'] == "articles") {
                            echo "<th>doi</th>\n";
                            echo "<th>title</th>\n";
                            echo "<th>pubdate</th>\n";
                            echo "<th>jid</th>\n";
                            echo "</tr>\n";
                            echo "</thead>\n";
                            echo "<tbody>\n";
                            
                            if(!($stmt = $mysqli->prepare('SELECT * FROM articles'))) {
                                echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
                            }
                            if(!$stmt->execute()){
                                echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
                            }
                            if(!$stmt->bind_result($doi, $title, $pubdate, $jid)){
                                echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
                            }
                            while($stmt->fetch()){
                                echo "<tr>\n";
                                echo "<td>" . $doi . "</td>\n";
                                echo "<td>" . $title . "</td>\n";
                                echo "<td>" . $pubdate . "</td>\n";
                                echo "<td>" . $jid . "</td>\n";
                                echo "</tr>\n";
                            }
                            $stmt->close();
                        }
                        if($_POST['tbl'] == "journals") {
                            echo "<th>id</th>\n";
                            echo "<th>name</th>\n";
                            echo "<th>publisher</th>\n";
                            echo "<th>abbr</th>\n";
                            echo "<th>impfact</th>\n";
                            echo "</tr>\n";
                            echo "</thead>\n";
                            echo "<tbody>\n";
                            
                            if(!($stmt = $mysqli->prepare('SELECT * FROM journals'))) {
                                echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
                            }
                            if(!$stmt->execute()){
                                echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
                            }
                            if(!$stmt->bind_result($id, $name, $publisher, $abbr, $impfact)){
                                echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
                            }
                            while($stmt->fetch()){
                                echo "<tr>\n";
                                echo "<td>" . $id . "</td>\n";
                                echo "<td>" . $name . "</td>\n";
                                echo "<td>" . $publisher . "</td>\n";
                                echo "<td>" . $abbr . "</td>\n";
                                echo "<td>" . $impfact . "</td>\n";
                                echo "</tr>\n";
                            }
                            $stmt->close();
                        }
                        if($_POST['tbl'] == "author") {
                            echo "<th>id</th>\n";
                            echo "<th>fname</th>\n";
                            echo "<th>mname</th>\n";
                            echo "<th>lname</th>\n";
                            echo "</tr>\n";
                            echo "</thead>\n";
                            echo "<tbody>\n";
                            
                            if(!($stmt = $mysqli->prepare('SELECT * FROM author'))) {
                                echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
                            }
                            if(!$stmt->execute()){
                                echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
                            }
                            if(!$stmt->bind_result($id, $fname, $mname, $lname)){
                                echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
                            }
                            while($stmt->fetch()){
                                echo "<tr>\n";
                                echo "<td>" . $id . "</td>\n";
                                echo "<td>" . $fname . "</td>\n";
                                echo "<td>" . $mname . "</td>\n";
                                echo "<td>" . $lname . "</td>\n";
                                echo "</tr>\n";
                            }
                            $stmt->close();
                        }
                        if($_POST['tbl'] == "institutions") {
                            echo "<th>id</th>\n";
                            echo "<th>name</th>\n";
                            echo "<th>city</th>\n";
                            echo "<th>state</th>\n";
                            echo "<th>country</th>\n";
                            echo "</tr>\n";
                            echo "</thead>\n";
                            echo "<tbody>\n";
                            
                            if(!($stmt = $mysqli->prepare('SELECT * FROM institutions'))) {
                                echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
                            }
                            if(!$stmt->execute()){
                                echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
                            }
                            if(!$stmt->bind_result($id, $name, $city, $state, $country)){
                                echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
                            }
                            while($stmt->fetch()){
                                echo "<tr>\n";
                                echo "<td>" . $id . "</td>\n";
                                echo "<td>" . $name . "</td>\n";
                                echo "<td>" . $city . "</td>\n";
                                echo "<td>" . $state . "</td>\n";
                                echo "<td>" . $country . "</td>\n";
                                echo "</tr>\n";
                            }
                            $stmt->close();
                        }
                        if($_POST['tbl'] == "funders") {
                            echo "<th>id</th>\n";
                            echo "<th>name</th>\n";
                            echo "<th>type</th>\n";
                            echo "<th>country</th>\n";
                            echo "</tr>\n";
                            echo "</thead>\n";
                            echo "<tbody>\n";
                            
                            if(!($stmt = $mysqli->prepare('SELECT * FROM funders'))) {
                                echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
                            }
                            if(!$stmt->execute()){
                                echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
                            }
                            if(!$stmt->bind_result($id, $name, $type, $country)){
                                echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
                            }
                            while($stmt->fetch()){
                                echo "<tr>\n";
                                echo "<td>" . $id . "</td>\n";
                                echo "<td>" . $name . "</td>\n";
                                echo "<td>" . $type . "</td>\n";
                                echo "<td>" . $country . "</td>\n";
                                echo "</tr>\n";
                            }
                            $stmt->close();
                        }
                        if($_POST['tbl'] == "keywords") {
                            echo "<th>kwrd</th>\n";
                            echo "</tr>\n";
                            echo "</thead>\n";
                            echo "<tbody>\n";
                            
                            if(!($stmt = $mysqli->prepare('SELECT * FROM keywords'))) {
                                echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
                            }
                            if(!$stmt->execute()){
                                echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
                            }
                            if(!$stmt->bind_result($kwrd)){
                                echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
                            }
                            while($stmt->fetch()){
                                echo "<tr>\n";
                                echo "<td>" . $kwrd . "</td>\n";
                                echo "</tr>\n";
                            }
                            $stmt->close();
                        }
                        if($_POST['tbl'] == "authors_articles") {
                            echo "<th>artid</th>\n";
                            echo "<th>authid</th>\n";
                            echo "<th>ord_of_app</th>\n";
                            echo "</tr>\n";
                            echo "</thead>\n";
                            echo "<tbody>\n";
                            
                            if(!($stmt = $mysqli->prepare('SELECT * FROM authors_articles'))) {
                                echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
                            }
                            if(!$stmt->execute()){
                                echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
                            }
                            if(!$stmt->bind_result($artid, $authid, $ord_of_app)){
                                echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
                            }
                            while($stmt->fetch()){
                                echo "<tr>\n";
                                echo "<td>" . $artid . "</td>\n";
                                echo "<td>" . $authid . "</td>\n";
                                echo "<td>" . $ord_of_app . "</td>\n";
                                echo "</tr>\n";
                            }
                            $stmt->close();
                        }
                        if($_POST['tbl'] == "authors_inst") {
                            echo "<th>authid</th>\n";
                            echo "<th>instid</th>\n";
                            echo "<th>currinst</th>\n";
                            echo "<th>startdate</th>\n";
                            echo "<th>enddate</th>\n";
                            echo "</tr>\n";
                            echo "</thead>\n";
                            echo "<tbody>\n";
                            
                            if(!($stmt = $mysqli->prepare('SELECT * FROM authors_inst'))) {
                                echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
                            }
                            if(!$stmt->execute()){
                                echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
                            }
                            if(!$stmt->bind_result($authid, $instid, $currinst, $startdate, $enddate)){
                                echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
                            }
                            while($stmt->fetch()){
                                echo "<tr>\n";
                                echo "<td>" . $authid . "</td>\n";
                                echo "<td>" . $instid . "</td>\n";
                                echo "<td>" . $currinst . "</td>\n";
                                echo "<td>" . $startdate . "</td>\n";
                                echo "<td>" . $enddate . "</td>\n";
                                echo "</tr>\n";
                            }
                            $stmt->close();
                        }
                        if($_POST['tbl'] == "articles_funders") {
                            echo "<th>artid</th>\n";
                            echo "<th>fundid</th>\n";
                            echo "</tr>\n";
                            echo "</thead>\n";
                            echo "<tbody>\n";
                            
                            if(!($stmt = $mysqli->prepare('SELECT * FROM articles_funders'))) {
                                echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
                            }
                            if(!$stmt->execute()){
                                echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
                            }
                            if(!$stmt->bind_result($artid, $fundid)){
                                echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
                            }
                            while($stmt->fetch()){
                                echo "<tr>\n";
                                echo "<td>" . $artid . "</td>\n";
                                echo "<td>" . $fundid . "</td>\n";
                                echo "</tr>\n";
                            }
                            $stmt->close();
                        }
                        if($_POST['tbl'] == "citations") {
                            echo "<th>citingid</th>\n";
                            echo "<th>citedid</th>\n";
                            echo "</tr>\n";
                            echo "</thead>\n";
                            echo "<tbody>\n";
                            
                            if(!($stmt = $mysqli->prepare('SELECT * FROM citations'))) {
                                echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
                            }
                            if(!$stmt->execute()){
                                echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
                            }
                            if(!$stmt->bind_result($citingid, $citedid)){
                                echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
                            }
                            while($stmt->fetch()){
                                echo "<tr>\n";
                                echo "<td>" . $citingid . "</td>\n";
                                echo "<td>" . $citedid . "</td>\n";
                                echo "</tr>\n";
                            }
                            $stmt->close();
                        }
                        if($_POST['tbl'] == "articles_keywords") {
                            echo "<th>artid</th>\n";
                            echo "<th>kwrd</th>\n";
                            echo "</tr>\n";
                            echo "</thead>\n";
                            echo "<tbody>\n";
                            
                            if(!($stmt = $mysqli->prepare('SELECT * FROM articles_keywords'))) {
                                echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
                            }
                            if(!$stmt->execute()){
                                echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
                            }
                            if(!$stmt->bind_result($artid, $kwrd)){
                                echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
                            }
                            while($stmt->fetch()){
                                echo "<tr>\n";
                                echo "<td>" . $artid . "</td>\n";
                                echo "<td>" . $kwrd . "</td>\n";
                                echo "</tr>\n";
                            }
                            $stmt->close();
                        }
                ?>
            </tbody>
        </table>
    </body>
</html>