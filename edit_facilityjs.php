<?php
header('Access-Control-Allow-Origin: http://localhost:3000'); // Allow requests from localhost:3000
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS'); // Specify allowed HTTP methods
header('Access-Control-Allow-Headers: Content-Type, Authorization'); // Allow specific headers
header('Access-Control-Allow-Credentials: true'); // Allow cookies if needed

require_once('db.php'); // Include database connection

header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);

if (isset($input['id'], $input['equipment_type'], $input['description'], $input['status'])) {
    $facilityId = $input['id'];
    $equipmentType = $input['equipment_type'];
    $description = $input['description'];
    $status = $input['status'];

    try {
        $query = "UPDATE facilities 
                  SET equipment_type = :equipment_type, description = :description, status = :status 
                  WHERE id = :facility_id";
        $stmt = $pdo->prepare($query);
        $stmt->execute([
            'equipment_type' => $equipmentType,
            'description' => $description,
            'status' => $status,
            'facility_id' => $facilityId
        ]);

        if ($stmt->rowCount() > 0) {
            echo json_encode([
                'status' => 'success',
                'message' => 'Facility updated successfully'
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'No changes made or facility not found'
            ]);
        }
    } catch (PDOException $e) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Database error: ' . $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid input data'
    ]);
}
?>
