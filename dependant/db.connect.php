<?php
include 'pro.functions.php';
$servername = "127.0.0.1";
$username = "root";
$password = "";
global $conn;

try {
    $conn = new PDO("mysql:host=$servername; dbname=shareride", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//    echo "connected succefully";
    }
catch(PDOException $e)
    {
    echo "there was an error <br>". $e->getMessage();
    }

?>