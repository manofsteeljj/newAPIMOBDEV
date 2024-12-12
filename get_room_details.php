<?php
header('Access-Control-Allow-Origin: http://localhost:3000'); // Allow requests from localhost:3000
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS'); // Specify allowed HTTP methods
header('Access-Control-Allow-Headers: Content-Type, Authorization'); // Allow specific headers
header('Access-Control-Allow-Credentials: true'); // Allow cookies if needed
header('Content-Type: application/json');
include 'db.php'; // Include your database connection file

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $room_id = isset($_GET['id']) ? intval($_GET['id']) : null;

    if (!$room_id) {
        echo json_encode(['status' => 'error', 'message' => 'Room ID is required.']);
        exit;
    }

    $conn = getDatabaseConnection();

    // Query to fetch room details using PDO's prepared statements
    $room_query = $conn->prepare("SELECT * FROM rooms WHERE id = :id");
    $room_query->bindParam(':id', $room_id, PDO::PARAM_INT);  // PDO-specific binding

    $room_query->execute();
    $room_result = $room_query->fetch(PDO::FETCH_ASSOC);

    if ($room_result) {
        // Query to fetch tenants assigned to the room using PDO
        $tenants_query = $conn->prepare("SELECT id, full_name, gender, mobile_number FROM tenants WHERE room_id = :room_id");
        $tenants_query->bindParam(':room_id', $room_id, PDO::PARAM_INT); // PDO-specific binding
        $tenants_query->execute();
        
        $tenants = $tenants_query->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode([
            'status' => 'success',
            'room' => $room_result,
            'tenants' => $tenants
        ]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Room not found.']);
    }

    $conn = null; // Close the connection (No need to close statements manually)
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
?>
