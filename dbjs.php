<?php
$host = 'localhost';
$dbname = 'dormdb_sasa';
$username = 'root';
$password = '';

function getDatabaseConnection() {
    try {
        // Create a new PDO instance with the database connection details
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Enable exception handling
        return $pdo;
    } catch (PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }
}
?>
