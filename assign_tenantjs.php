<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');
require_once('db.php'); // Include the database connection

header('Content-Type: application/json');

// Check if tenant ID and room assignment data are provided
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tenantId = $_POST['tenant_id'];
    $roomId = $_POST['room_id'];
    $stayFrom = $_POST['stay_from'];
    $stayTo = $_POST['stay_to'];

    // Connect to the database
    try {
        $pdo = getDatabaseConnection();

        // Check if the room exists and has available slots
        $query = "SELECT id, room_type, room_id  FROM rooms WHERE id = :room_id AND remaining_slots > 0";
        $stmt = $pdo->prepare($query);
        $stmt->execute(['room_id' => $roomId]);
        $room = $stmt->fetch();

        if (!$room) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Room is not available or does not exist.'
            ]);
            exit;
        }

        // Assign the tenant to the room
        $query = "UPDATE tenants SET room_id = :room_id, stay_from = :stay_from, stay_to = :stay_to WHERE id = :tenant_id";
        $stmt = $pdo->prepare($query);
        $stmt->execute([
            'room_id' => $roomId,
            'stay_from' => $stayFrom,
            'stay_to' => $stayTo,
            'tenant_id' => $tenantId
        ]);

        // Update the remaining slots for the room
        $query = "UPDATE rooms SET remaining_slots = remaining_slots - 1 WHERE id = :room_id";
        $stmt = $pdo->prepare($query);
        $stmt->execute(['room_id' => $roomId]);

        echo json_encode([
            'status' => 'success',
            'message' => 'Tenant successfully assigned to the room.'
        ]);
    } catch (PDOException $e) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Database error: ' . $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid request method.'
    ]);
}
?>
