<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');
require 'db.php'; // Include the database connection

header('Content-Type: application/json');

// Fetch facilities from the database
try {
    $pdo = getDatabaseConnection(); // Assuming getDatabaseConnection() returns a PDO instance

    // Query to fetch facility data
    $stmt = $pdo->prepare("SELECT * FROM facilities");
    $stmt->execute();
    $facilities = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($facilities) {
        echo json_encode([
            'status' => 'success',
            'facilities' => $facilities  // Change from 'tenants' to 'facilities'
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'No facilities found'
        ]);
    }
} catch (PDOException $e) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}
?>
