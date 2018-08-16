<?php
    if(count(get_included_files()) === 1) {
        header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found");
        exit;
    }

    $url = YOUR_URL; // Enter the URL for your database here
    $user = YOUR_USER_NAME; // Enter the username for your database here
    $pswrd = YOUR_PASSWORD; // Enter the password for your database here
    $dbName = YOUR_DATABASE_NAME; // Enter the name for your database here
    $port = YOUR_PORT; // Enter the port for your database here
?>