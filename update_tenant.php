<?php
require_once 'db.php';

// Set headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: PUT, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Get the PUT data
$data = json_decode(file_get_contents("php://input"));

// Check if necessary data is provided
if (isset($data->id) && isset($data->full_name) && isset($data->gender) && isset($data->mobile_number)) {
    try {
        // Connect to the database
        $pdo = getDatabaseConnection();

        // Prepare update query
        $query = "UPDATE tenants SET full_name = :full_name, gender = :gender, mobile_number = :mobile_number WHERE id = :id";
        $stmt = $pdo->prepare($query);
        
        // Execute the query with data
        $stmt->execute([
            'id' => $data->id,
            'full_name' => $data->full_name,
            'gender' => $data->gender,
            'mobile_number' => $data->mobile_number,
        ]);

        // Check if any rows were updated
        if ($stmt->rowCount() > 0) {
            echo json_encode(['status' => 'success', 'message' => 'Tenant updated successfully!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'No changes made or tenant not found.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid data provided']);
}
?>
