<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

// For preflight requests (OPTIONS)
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit;
}

require 'db.php'; // Include the database connection

try {
    // Check if the request method is GET
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        // Fetch rooms from the database
        $stmt = $pdo->query("SELECT * FROM rooms");
        $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Send a successful JSON response
        echo json_encode([
            'status' => 'success',
            'data' => $rooms
        ]);
    } elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Get the request body
        $requestData = json_decode(file_get_contents('php://input'), true);

        // Check if the id is present in the request body
        if (!isset($requestData['id'])) {
            // If the id is not present, send an error response
            echo json_encode([
                'status' => 'error',
                'message' => 'Room not found'
            ]);
            exit;
        }

        // Get the room id
        $roomId = $requestData['id'];

        // Update the room
        $stmt = $pdo->prepare("UPDATE rooms SET room_number = :room_number, room_type = :room_type, total_slots = :total_slots, remaining_slots = :remaining_slots WHERE id = :id");
        $stmt->bindParam(':id', $roomId);
        $stmt->bindParam(':room_number', $requestData['room_number']);
        $stmt->bindParam(':room_type', $requestData['room_type']);
        $stmt->bindParam(':total_slots', $requestData['total_slots']);
        $stmt->bindParam(':remaining_slots', $requestData['remaining_slots']);
        $stmt->execute();

        // Send a successful JSON response
        echo json_encode([
            'status' => 'success',
            'message' => 'Room updated successfully'
        ]);
    } else {
        // If the request method is not GET or POST, send an error response
        echo json_encode([
            'status' => 'error',
            'message' => 'Invalid request method'
        ]);
    }
} catch (PDOException $e) {
    // If there's an error, send it as JSON
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}
exit;