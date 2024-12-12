<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');
require_once('db.php'); // Include the database connection

header('Content-Type: application/json');

// Fetch available rooms
try {
    $pdo = getDatabaseConnection();

    // SQL query to get rooms with available slots
    $query = "SELECT id, room_number, room_type, total_slots, remaining_slots FROM rooms";
    $stmt = $pdo->prepare($query);
    $stmt->execute();

    // Fetch rooms data
    $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($rooms) {
        echo json_encode([
            'status' => 'success',
            'data' => $rooms
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'No rooms found.'
        ]);
    }

} catch (PDOException $e) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}
?>
