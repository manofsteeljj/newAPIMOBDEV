<?php
require_once('db.php');

// Set headers to allow CORS (if necessary for cross-origin requests)
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

// Get the input data from the request body
$data = json_decode(file_get_contents("php://input"));

// Check if all required fields are provided
if (!isset($data->equipment_type) || !isset($data->description) || !isset($data->status)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Missing required fields.'
    ]);
    exit;
}

$equipmentType = $data->equipment_type;
$description = $data->description;
$status = $data->status;

try {
    $pdo = getDatabaseConnection(); // Assuming getDatabaseConnection() returns a PDO instance

    // Prepare SQL query to insert data
    $query = "INSERT INTO facilities (equipment_type, description, status) VALUES (:equipment_type, :description, :status)";
    $stmt = $pdo->prepare($query);
    $stmt->execute([
        'equipment_type' => $equipmentType,
        'description' => $description,
        'status' => $status
    ]);

    echo json_encode([
        'status' => 'success',
        'message' => 'Facility added successfully'
    ]);
} catch (PDOException $e) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}
?>
