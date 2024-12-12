<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');
require 'db.php'; // Include the database connection

header('Content-Type: application/json');

// Fetch tenants from the database where room_id is NULL
try {
    $pdo = getDatabaseConnection(); // Assuming getDatabaseConnection() returns a PDO instance

    // Query to fetch tenant data where room_id is NULL
    $stmt = $pdo->prepare("SELECT * FROM tenants WHERE room_id IS NULL");
    $stmt->execute();
    $tenants = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($tenants) {
        echo json_encode([
            'status' => 'success',
            'tenants' => $tenants
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'No tenants with no room found'
        ]);
    }
} catch (PDOException $e) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}
?>
