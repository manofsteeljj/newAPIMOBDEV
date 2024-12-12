<?php
header('Access-Control-Allow-Origin: http://localhost:3000'); // Allow requests from localhost:3000
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS'); // Allow HTTP methods
header('Access-Control-Allow-Headers: Content-Type, Authorization'); // Allow specific headers
header('Access-Control-Allow-Credentials: true'); // Allow cookies if needed

header('Content-Type: application/json'); // Ensure response is JSON

include 'db.php'; // Include your database connection file

// If the request is OPTIONS (preflight request), send a 200 response
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $input = json_decode(file_get_contents('php://input'), true);

    if (isset($input['id'], $input['room_number'], $input['room_type'], $input['total_slots'], $input['remaining_slots'])) {
        $id = $input['id'];
        $room_number = $input['room_number'];
        $room_type = $input['room_type'];
        $total_slots = $input['total_slots'];
        $remaining_slots = $input['remaining_slots'];

        $conn = getDatabaseConnection();

        // Use PDO with bindParam or bindValue
        $update_query = $conn->prepare("UPDATE rooms SET room_number = :room_number, room_type = :room_type, total_slots = :total_slots, remaining_slots = :remaining_slots WHERE id = :id");

        // Bind parameters using bindParam
        $update_query->bindParam(':room_number', $room_number, PDO::PARAM_STR);
        $update_query->bindParam(':room_type', $room_type, PDO::PARAM_STR);
        $update_query->bindParam(':total_slots', $total_slots, PDO::PARAM_INT);
        $update_query->bindParam(':remaining_slots', $remaining_slots, PDO::PARAM_INT);
        $update_query->bindParam(':id', $id, PDO::PARAM_INT);

        // Execute the query and check if successful
        if ($update_query->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Room updated successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error updating room']);
        }

        // No need for close() here; PDO handles it automatically
        $conn = null; // Close the connection
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>
