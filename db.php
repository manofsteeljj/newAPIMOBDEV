<?php
$host = 'localhost';
$dbname = 'dormdb_sasa';
$username = 'root'; 
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

function getDatabaseConnection() {
    global $host, $dbname, $username, $password; // Make sure to use the global variables
    try {
        // Create a new PDO instance using the provided details
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Enable exceptions on error
        return $pdo; // Return the PDO instance
    } catch (PDOException $e) {
        // Handle connection error by displaying the message
        die("Connection failed: " . $e->getMessage());
    }
}
?>
