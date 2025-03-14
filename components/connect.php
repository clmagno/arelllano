<?php

$servername = "localhost";
$db_name = "coursedb"; // Your Database Name
$user_name = "root"; // Default user for XAMPP
$user_password = ""; // Default password for XAMPP is empty

try {
    // Create a PDO connection
    $conn = new PDO("mysql:host=$servername;dbname=$db_name;charset=utf8", $user_name, $user_password);
    
    // Set PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // echo "Connected successfully"; // Uncomment this to check if it's working
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage()); // Display error if connection fails
}

?>
