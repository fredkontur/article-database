<?php
    //Turn on error reporting
    ini_set('display_errors', 'On');
    include 'dbConnection.php';
    //open connection to database
    $mysqli = new mysqli($url, $user, $pswrd, $dbName, $port);
    if(!$mysqli || $mysqli->connect_errno){
        echo "Connection error " . $mysqli->connect_errno . " " . $mysqli->connect_error;
        }
?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <title>Kontur Article Database</title>
        <link rel="stylesheet" href="style.css">
        <link rel="stylesheet" href="http://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
        <script src="http://code.jquery.com/jquery-1.10.2.js"></script>
        <script src="http://code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
        <script>
            $(function() {
                $( 'input[type="date"]' ).datepicker({
                    dateFormat: 'yy-mm-dd'
                });
            });
        </script>
    </head>

    <body>

        <h1>Article Database Queries</h1>
        
        <!-- Form To Display Tables -->
        <form action="query00.php" method="post">
            <fieldset>
                <legend>Display a Table</legend>
                
                <label>Which table do you want to see?
                    <input type="radio" name="tbl" value="articles" checked>articles
                    <input type="radio" name="tbl" value="journals">journals
                    <input type="radio" name="tbl" value="author">author
                    <input type="radio" name="tbl" value="institutions">institutions
                    <input type="radio" name="tbl" value="funders">funders
                    <input type="radio" name="tbl" value="keywords">keywords
                    <input type="radio" name="tbl" value="authors_articles">authors_articles
                    <input type="radio" name="tbl" value="authors_inst">authors_inst
                    <input type="radio" name="tbl" value="articles_funders">articles_funders
                    <input type="radio" name="tbl" value="citations">citations
                    <input type="radio" name="tbl" value="articles_keywords">articles_keywords
                </label>
                
                <br><br>
    
                <input type="submit" value="Submit"></input>
            </fieldset> 
        </form>
    
        
        <br><br>
    
        
        <!-- Form To Create New Article -->
        <form action="query01.php" method="post">
            <fieldset>
                <legend>Create New Article</legend>
                
                <label>Article Title:
                    <input type="text" name="artTitle" size="150" required></input>
                </label>
            
                <br><br>
        
                <label>Publication Date:
                    <input type="date" name="pubDate" required></input>
                </label>
    
                <label>Digital Object Identifier:
                    <input type="text" name="doi" size = "15" required></input>
                </label>

                 <!--   I'm using the instructor's sample code for guidance on the
                        dropdown menu -->
                <label>Journal:
                    <select name="journal">
                        <?php
                            if(!($stmt = $mysqli->prepare("SELECT id, name FROM journals ORDER BY name"))){
                                echo "Prepare failed: " . $stmt->errno . " " . $stmt->error;
                            }

                            if(!$stmt->execute()){
                                echo "Execute failed: " . $mysqli->connect_errno . " " . $mysqli->connect_error;
                            }
                            if(!$stmt->bind_result($id, $jname)){
                                echo "Bind failed: " . $mysqli->connect_errno . " " . $mysqli->connect_error;
                            }
                            while($stmt->fetch()){
                                echo '<option value="' . $id . '">' . $jname . "</option>\n";
                            }
                            $stmt->close();
                        ?>
                    </select>
                </label>

                <label> Do you need to create a new journal?
                    <input type="radio" name="newJrnl" value="yes">yes
                    <input type="radio" name="newJrnl" value="no" checked>no
                </label>

                <br><br>

                <label>How Many Authors Does the Article Have?
                    <input type="number" name="numAuthors" required></input>
                </label>

                <input type="submit" value="Submit"></input>

            </fieldset> 
        </form>


        <br><br>


        <!-- Form To Add Keyword to Article -->
        <form action="query02.php" method="post">
            <fieldset>
                <legend>Add Keyword(s) to Article</legend>

                <label>Article:
                    <select name="article">
                        <?php
                            if(!($stmt = $mysqli->prepare("SELECT doi, title FROM articles ORDER BY title"))){
                                echo "Prepare failed: " . $stmt->errno . " " . $stmt->error;
                            }

                            if(!$stmt->execute()){
                                echo "Execute failed: " . $mysqli->connect_errno . " " . $mysqli->connect_error;
                            }
                            if(!$stmt->bind_result($doi, $title)){
                                echo "Bind failed: " . $mysqli->connect_errno . " " . $mysqli->connect_error;
                            }
                            while($stmt->fetch()){
                                echo '<option value="' . $doi . '">' . $title . "</option>\n";
                            }
                            $stmt->close();
                        ?>
                    </select>
                </label>
        
                <label>How Many Keywords Will Be Added?
                    <input type="number" name="numKeywords" required></input>
                </label>
    
                <input type="submit" value="Start Adding Keywords"></input>
            </fieldset> 
        </form>


        <br><br>


        <!-- Form To Create New Institution -->
        <form action="query03.php" method="post">
            <fieldset>
                <legend>Create New Institution</legend>
                
                <label>Institution Name:
                    <input type="text" name="instName" required></input>
                </label>

                <label>Institution City:
                    <input type="text" name="instCity" required></input>
                </label>

                <label>Institution State (if applicable):
                    <input type="text" name="instState"></input>
                </label>

                <label>Institution Country:
                    <input type="text" name="instCountry" required></input>
                </label>
    
                <input type="submit" value="Submit"></input>
            </fieldset> 
        </form>


        <br><br>


        <!-- Form To Create New Funder -->
        <form action="query04.php" method="post">
            <fieldset>
                <legend>Create New Funder</legend>
                
                <label>Funder Name:
                    <input type="text" name="fundName" required></input>
                </label>

                <label>Funder Type:
                    <input type="text" name="fundType"></input>
                </label>

                <label>Funder Country:
                    <input type="text" name="fundCountry" required></input>
                </label>
    
                <input type="submit" value="Submit"></input>
            </fieldset> 
        </form>


        <br><br>


        <!-- Form To Add Author to Institution -->
        <form action="query05.php" method="post">
            <fieldset>
                <legend>Add Author to Institution</legend>
                
                <label>Author:
                    <select name="author">
                        <?php
                            if(!($stmt = $mysqli->prepare("SELECT id, fname, mname, lname FROM author ORDER BY lname"))){
                                echo "Prepare failed: " . $stmt->errno . " " . $stmt->error;
                            }

                            if(!$stmt->execute()){
                                echo "Execute failed: " . $mysqli->connect_errno . " " . $mysqli->connect_error;
                            }
                            if(!$stmt->bind_result($id, $fname, $mname, $lname)){
                                echo "Bind failed: " . $mysqli->connect_errno . " " . $mysqli->connect_error;
                            }
                            while($stmt->fetch()){
                                echo '<option value=' . $id . '> ' . $lname . ', ' . $fname . ' ' . $mname . "</option>\n";
                            }
                            $stmt->close();
                        ?>
                    </select>
                </label>

                <label>Institution:
                    <select name="institution">
                        <?php
                            if(!($stmt = $mysqli->prepare("SELECT id, name FROM institutions ORDER BY name"))){
                                echo "Prepare failed: " . $stmt->errno . " " . $stmt->error;
                            }

                            if(!$stmt->execute()){
                                echo "Execute failed: " . $mysqli->connect_errno . " " . $mysqli->connect_error;
                            }
                            if(!$stmt->bind_result($id, $name)){
                                echo "Bind failed: " . $mysqli->connect_errno . " " . $mysqli->connect_error;
                            }
                            while($stmt->fetch()){
                                echo '<option value=' . $id . '> ' . $name. "</option>\n";
                            }
                            $stmt->close();
                        ?>
                    </select>
                </label>
                
                <label>Start Date:
                    <input type="date" name="startDate" required></input>
                </label>
            
                <br><br>
            
                <label>Current Institution?
                    <input type="radio" name="currInst" value="yes" checked>Yes
                    <input type="radio" name="currInst" value="no">No
                </label>
    
                <input type="submit" value="Submit"></input>
            </fieldset> 
        </form>


        <br><br>


        <!-- Form To Change Author Status at Institution -->
        <form action="query06.php" method="post">
            <fieldset>
                <legend>Change Author Status at Institution</legend>
                
                <label>Author:
                    <select name="author">
                        <?php
                            if(!($stmt = $mysqli->prepare("SELECT id, fname, mname, lname FROM author ORDER BY lname"))){
                                echo "Prepare failed: " . $stmt->errno . " " . $stmt->error;
                            }

                            if(!$stmt->execute()){
                                echo "Execute failed: " . $mysqli->connect_errno . " " . $mysqli->connect_error;
                            }
                            if(!$stmt->bind_result($id, $fname, $mname, $lname)){
                                echo "Bind failed: " . $mysqli->connect_errno . " " . $mysqli->connect_error;
                            }
                            while($stmt->fetch()){
                                echo '<option value=' . $id . '>' . $lname . ', ' . $fname . ' ' . $mname . "</option>\n";
                            }
                            $stmt->close();
                        ?>
                    </select>
                </label>
    
                <input type="submit" value="Select Institution"></input>
            </fieldset> 
        </form>


        <br><br>


        <!-- Form To Add Funder to Article -->
        <form action="query07.php" method="post">
            <fieldset>
                <legend>Add Funder to Article</legend>
                
                <label>Funder:
                    <select name="funder">
                        <?php
                            if(!($stmt = $mysqli->prepare("SELECT id, name FROM funders ORDER BY name"))){
                                echo "Prepare failed: " . $stmt->errno . " " . $stmt->error;
                            }

                            if(!$stmt->execute()){
                                echo "Execute failed: " . $mysqli->connect_errno . " " . $mysqli->connect_error;
                            }
                            if(!$stmt->bind_result($id, $fundName)){
                                echo "Bind failed: " . $mysqli->connect_errno . " " . $mysqli->connect_error;
                            }
                            while($stmt->fetch()){
                                echo '<option value=' . $id . '>' . $fundName . "</option>\n";
                            }
                            $stmt->close();
                        ?>
                    </select>
                </label>

                <label>Article:
                    <select name="article">
                        <?php
                            if(!($stmt = $mysqli->prepare("SELECT doi, title FROM articles ORDER BY title"))){
                                echo "Prepare failed: " . $stmt->errno . " " . $stmt->error;
                            }

                            if(!$stmt->execute()){
                                echo "Execute failed: " . $mysqli->connect_errno . " " . $mysqli->connect_error;
                            }
                            if(!$stmt->bind_result($doi, $title)){
                                echo "Bind failed: " . $mysqli->connect_errno . " " . $mysqli->connect_error;
                            }
                            while($stmt->fetch()){
                                echo '<option value="' . $doi . '">' . $title . "</option>\n";
                            }
                            $stmt->close();
                        ?>
                    </select>
                </label>
    
                <input type="submit" value="Submit"></input>
            </fieldset> 
        </form>


        <br><br>


        <!-- Form To Add Reference Citation to Article -->
        <form action="query08.php" method="post">
            <fieldset>
                <legend>Add Reference Citation(s) to Article</legend>

                <label>Article:
                    <select name="article">
                        <?php
                            if(!($stmt = $mysqli->prepare("SELECT doi, title FROM articles ORDER BY title"))){
                                echo "Prepare failed: " . $stmt->errno . " " . $stmt->error;
                            }

                            if(!$stmt->execute()){
                                echo "Execute failed: " . $mysqli->connect_errno . " " . $mysqli->connect_error;
                            }
                            if(!$stmt->bind_result($doi, $title)){
                                echo "Bind failed: " . $mysqli->connect_errno . " " . $mysqli->connect_error;
                            }
                            while($stmt->fetch()){
                                echo '<option value="' . $doi . '">' . $title . "</option>\n";
                            }
                            $stmt->close();
                        ?>
                    </select>
                </label>

                <label>How Many References Will Be Added?
                    <input type="number" name="numReferences" required></input>
                </label>
    
                <input type="submit" value="Start Adding References"></input>
            </fieldset> 
        </form>


        <br><br>


        <!-- Form To List Articles in Sorted Order -->
        <form action="query09.php" method="post">
            <fieldset>
                <legend>List Articles in Sorted Order</legend>

                <label>How would you like the articles to be sorted?
                    <input type="radio" name="artSort" value="byTitle" checked>By Title
                    <input type="radio" name="artSort" value="byJournal">By Journal
                    <input type="radio" name="artSort" value="byPubDate">By Publication Date
                </label>
    
                <input type="submit" value="Submit"></input>
            </fieldset> 
        </form>


        <br><br>


        <!-- Form To List an Article's References -->
        <form action="query10.php" method="post">
            <fieldset>
                <legend>List Article's References</legend>

                <label>Article:
                    <select name="article">
                        <?php
                            if(!($stmt = $mysqli->prepare("SELECT doi, title FROM articles ORDER BY title"))){
                                echo "Prepare failed: " . $stmt->errno . " " . $stmt->error;
                            }

                            if(!$stmt->execute()){
                                echo "Execute failed: " . $mysqli->connect_errno . " " . $mysqli->connect_error;
                            }
                            if(!$stmt->bind_result($doi, $title)){
                                echo "Bind failed: " . $mysqli->connect_errno . " " . $mysqli->connect_error;
                            }
                            while($stmt->fetch()){
                                echo '<option value="' . $doi . '">' . $title . "</option>\n";
                            }
                            $stmt->close();
                        ?>
                    </select>
                </label>
    
                <input type="submit" value="Submit"></input>
            </fieldset> 
        </form>


        <br><br>


        <!-- Form To List the Articles that Cite an Article -->
        <form action="query11.php" method="post">
            <fieldset>
                <legend>List All Articles That Cite a Particular Article</legend>

                <label>Article:
                    <select name="article">
                        <?php
                            if(!($stmt = $mysqli->prepare("SELECT doi, title FROM articles ORDER BY title"))){
                                echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
                            }

                            if(!$stmt->execute()){
                                echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
                            }
                            if(!$stmt->bind_result($doi, $title)){
                                echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
                            }
                            while($stmt->fetch()){
                                echo '<option value="' . $doi . '">' . $title . "</option>\n";
                            }
                            $stmt->close();
                        ?>
                    </select>
                </label>
    
                <input type="submit" value="Submit"></input>
            </fieldset> 
        </form>


        <br><br>


        <!-- Form To Filter Articles by Impact Factor -->
        <form action="query12.php" method="post">
            <fieldset>
                <legend>Filter Articles by Impact Factor</legend>

                <label>See Articles Published in Journals with Impact Factor Greater Than:
                    <input type="number" name="impactFactor" step="0.001" required></input>
                </label>
    
                <input type="submit" value="Submit"></input>
            </fieldset> 
        </form>


        <br><br>


        <!-- Form To Filter Articles by Institution -->
        <form action="query13.php" method="post">
            <fieldset>
                <legend>List All Articles from a Particular Institution</legend>

                <label>Institution:
                    <select name="institution">
                        <?php
                            if(!($stmt = $mysqli->prepare("SELECT id, name FROM institutions ORDER BY name"))){
                                echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
                            }

                            if(!$stmt->execute()){
                                echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
                            }
                            if(!$stmt->bind_result($id, $name)){
                                echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
                            }
                            while($stmt->fetch()){
                                echo '<option value="' . $id . '">' . $name. "</option>\n";
                            }
                            $stmt->close();
                        ?>
                    </select>
                </label>
    
                <input type="submit" value="Submit"></input>
            </fieldset> 
        </form>


        <br><br>


        <!-- Form To Filter Articles by Funder -->
        <form action="query14.php" method="post">
            <fieldset>
                <legend>List All Articles Having a Particular Funder</legend>

                <label>Funder:
                    <select name="funder">
                        <?php
                            if(!($stmt = $mysqli->prepare("SELECT id, name FROM funders ORDER BY name"))){
                                echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
                            }

                            if(!$stmt->execute()){
                                echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
                            }
                            if(!$stmt->bind_result($id, $name)){
                                echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
                            }
                            while($stmt->fetch()){
                                echo '<option value="'. $id . '">' . $name. "</option>\n";
                            }
                            $stmt->close();
                        ?>
                    </select>
                </label>
    
                <input type="submit" value="Submit"></input>
            </fieldset> 
        </form>


        <br><br>


        <!-- Form To Filter Articles by Keyword -->
        <form action="query15.php" method="post">
            <fieldset>
                <legend>List All Articles Having a Particular Keyword</legend>

                <label>Keyword:
                    <select name="keyword">
                        <?php
                            if(!($stmt = $mysqli->prepare("SELECT DISTINCT kwrd FROM articles_keywords ORDER BY kwrd"))){
                                echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
                            }

                            if(!$stmt->execute()){
                                echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
                            }
                            if(!$stmt->bind_result($word)){
                                echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
                            }
                            while($stmt->fetch()){
                                echo '<option value="' . $word . '">' . $word . "</option>\n";
                            }
                            $stmt->close();
                        ?>
                    </select>
                </label>
    
                <input type="submit" value="Submit"></input>
            </fieldset> 
        </form>


        <br><br>


        <!-- Form To List Authors in Descending Order by the Average Impact Factor of the Journals in Which They Publish -->
        <form action="query16.php" method="post">
            <fieldset>
                <legend>List Authors in Descending Order by Avg Impact Factor of the Journals in Which They Publish</legend>
    
                <input type="submit" value="Submit"></input>
            </fieldset> 
        </form>


        <br><br>


        <!-- Form To List Institutions in Descending Order by the Average Impact Factor of the Journals in Which Their Authors Publish -->
        <form action="query17.php" method="post">
            <fieldset>
                <legend>List Institutions in Descending Order by Avg Impact Factor of the Journals in Which Their Authors Publish</legend>
    
                <input type="submit" value="Submit"></input>
            </fieldset> 
        </form>


        <br><br>


        <!-- Form To List the Counts of How Many Articles Institutions Have Published with a Certain Keyword -->
        <form action="query18.php" method="post">
            <fieldset>
                <legend>List the Counts of How Many Articles Institutions Have Published with a Certain Keyword</legend>
  
                <label>Keyword:
                    <select name="keyword">
                        <?php
                            if(!($stmt = $mysqli->prepare("SELECT DISTINCT kwrd FROM articles_keywords ORDER BY kwrd"))){
                                echo "Prepare failed: " . $stmt->errno . " " . $stmt->error;
                            }

                            if(!$stmt->execute()){
                                echo "Execute failed: " . $mysqli->connect_errno . " " . $mysqli->connect_error;
                            }
                            if(!$stmt->bind_result($word)){
                                echo "Bind failed: " . $mysqli->connect_errno . " " . $mysqli->connect_error;
                            }
                            while($stmt->fetch()){
                                echo '<option value="' . $word . '">' . $word . '</option>\n';
                            }
                            $stmt->close();
                        ?>
                    </select>
                </label>
    
                <input type="submit" value="List Institutions and Article Counts"></input>
            </fieldset> 
        </form>


        <br><br>


        <!-- Form To List the Counts of How Many Articles Institutions Have Published in a Certain Journal -->
        <form action="query19.php" method="post">
            <fieldset>
                <legend>List the Counts of How Many Articles Institutions Have Published in a Certain Journal</legend>
  
                <label>Journal:
                    <select name="journal">
                        <?php
                            if(!($stmt = $mysqli->prepare("SELECT id, name FROM journals ORDER BY name"))){
                                echo "Prepare failed: " . $stmt->errno . " " . $stmt->error;
                            }

                            if(!$stmt->execute()){
                                echo "Execute failed: " . $mysqli->connect_errno . " " . $mysqli->connect_error;
                            }
                            if(!$stmt->bind_result($id, $jname)){
                                echo "Bind failed: " . $mysqli->connect_errno . " " . $mysqli->connect_error;
                            }
                            while($stmt->fetch()){
                                echo '<option value="' . $id . '">' . $jname . '</option>\n';
                            }
                            $stmt->close();
                        ?>
                    </select>
                </label>
    
                <input type="submit" value="List Institutions and Article Counts"></input>
            </fieldset> 
        </form>


        <br><br>


        <!-- Form To List the Counts of How Many of Funders' Articles Have Published with a Certain Keyword -->
        <form action="query20.php" method="post">
            <fieldset>
                <legend>List the Counts of How Many of Funders' Articles Have Published with a Certain Keyword</legend>
  
                <label>Keyword:
                    <select name="keyword">
                        <?php
                            if(!($stmt = $mysqli->prepare("SELECT DISTINCT kwrd FROM articles_keywords ORDER BY kwrd"))){
                                echo "Prepare failed: " . $stmt->errno . " " . $stmt->error;
                            }

                            if(!$stmt->execute()){
                                echo "Execute failed: " . $mysqli->connect_errno . " " . $mysqli->connect_error;
                            }
                            if(!$stmt->bind_result($word)){
                                echo "Bind failed: " . $mysqli->connect_errno . " " . $mysqli->connect_error;
                            }
                            while($stmt->fetch()){
                                echo '<option value="' . $word . '">' . $word . '</option>\n';
                            }
                            $stmt->close();
                        ?>
                    </select>
                </label>
    
                <input type="submit" value="List Funders and Article Counts"></input>
            </fieldset> 
        </form>


        <br><br>


        <!-- Form To List the Counts of How Many of Funder's Articles Have Published in a Certain Journal -->
        <form action="query21.php" method="post">
            <fieldset>
                <legend>List the Counts of How Many of Funders' Articles Have Published in a Certain Journal</legend>
  
                <label>Journal:
                    <select name="journal">
                        <?php
                            if(!($stmt = $mysqli->prepare("SELECT id, name FROM journals ORDER BY name"))){
                                echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
                            }

                            if(!$stmt->execute()){
                                echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
                            }
                            if(!$stmt->bind_result($id, $jname)){
                                echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
                            }
                            while($stmt->fetch()){
                                echo '<option value=" '. $id . ' "> ' . $jname . '</option>\n';
                            }
                            $stmt->close();
                        ?>
                    </select>
                </label>
    
                <input type="submit" value="List Funders and Article Counts"></input>
            </fieldset> 
        </form>


        <br><br>

    </body>
</html>