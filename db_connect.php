<?php

    // Connect to the database
    $servername   = 'localhost'; // 127.0.0.1 // local machine
    $username     = 'root';
    $password     = '';
    $dbname       = 'shopping_web';

    // create the connection
    $db = new mysqli($servername, $username, $password, $dbname);

    // check if connection has any issues
    if($db->connect_error){
        die('Something went wrong... please try again later...');
    }
    else{
        echo(' connection sucessful');
    }
?>