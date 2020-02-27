<?php
/**
  * Open a connection via PDO to create a
  * new database and table with structure.
  *
  */

require "config.php";

    try {
        $connection = new PDO("mysql:host=$host", $username, $password, $options);
        // set the PDO error mode to exception
        $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        echo "Connected successfully"; 
    }
    catch(PDOException $e)
    {
        echo "Connection failed: " . $e->getMessage();
    }
?> 