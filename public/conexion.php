<?php
/**
  * Open a connection via PDO to create a
  * new database and table with structure.
  *
  */

require "config.php";
$connection = null;
    try {
        $connection = new PDO("mysql:host=$host;dbname=$dbname", $username, $password, $options);
        // set the PDO error mode to exception
        $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
    }
    catch(PDOException $e)
    {
        echo "Connection failed: " . $e->getMessage();
    }
?> 