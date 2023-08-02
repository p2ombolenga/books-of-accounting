<?php
    $username = "root";
    $server = "localhost";
    $passowrd = "";
    $database = "financial-app";

    $conn = mysqli_connect($server, $username, $passowrd, $database);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
      }
?>