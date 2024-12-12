<?php
header("Access-Control-Allow-Origin: *"); // Allow all origins or restrict to a specific domain
header("Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");

header('Content-Type: application/json');
require 'db.php';  // Include database connection

// Get the POST data
$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['room_number'], $data['room_type'], $data['total_slots'])) {
    $room_number = $data['room_number'];
    $room_type = $data['room_type'];
    $total_slots = (int) $data['total_slots'];
    $remaining_slots = $total_slots; 

    try {
        $stmt = $pdo->prepare("INSERT INTO rooms (room_number, room_type, total_slots, remaining_slots) 
                               VALUES (:room_number, :room_type, :total_slots, :remaining_slots)");
        $stmt->execute([
            ':room_number' => $room_number,
            ':room_type' => $room_type,
            ':total_slots' => $total_slots,
            ':remaining_slots' => $remaining_slots
        ]);
        echo json_encode(['status' => 'success', 'message' => 'Room added successfully']);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Missing required fields']);
}
