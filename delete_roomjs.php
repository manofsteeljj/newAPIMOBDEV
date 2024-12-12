<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: DELETE');
header('Access-Control-Allow-Headers: Content-Type');

header('Content-Type: application/json');

// Include your database connection
require_once 'db.php';  // Ensure this file contains the database connection logic

// Check if the room id is provided in the query string
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Room ID is required.'
    ]);
    exit;
}

$roomId = intval($_GET['id']); // Sanitize and get the room ID

try {
    $pdo = getDatabaseConnection(); // Ensure this function returns a valid PDO connection

    // Begin a transaction for safety
    $pdo->beginTransaction();

    // Delete the room's tenants first (optional, based on your requirements)
    $deleteTenantsStmt = $pdo->prepare("DELETE FROM tenants WHERE room_id = :room_id");
    $deleteTenantsStmt->execute([':room_id' => $roomId]);

    // Now delete the room
    $deleteRoomStmt = $pdo->prepare("DELETE FROM rooms WHERE id = :id");
    $deleteRoomStmt->execute([':id' => $roomId]);

    // Commit the transaction
    $pdo->commit();

    echo json_encode([
        'status' => 'success',
        'message' => 'Room deleted successfully.'
    ]);
} catch (PDOException $e) {
    // Rollback the transaction if there's an error
    $pdo->rollBack();
    echo json_encode([
        'status' => 'error',
        'message' => 'Error deleting room: ' . $e->getMessage()
    ]);
}
?>
