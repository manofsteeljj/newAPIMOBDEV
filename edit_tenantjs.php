<?php
require_once 'db.php';

// Set headers
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

// Check if tenant ID and room assignment data are provided
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tenantId = $_POST['id'];
    $fullName = $_POST['full_name'];
    $gender = $_POST['gender'];
    $mobileNumber = $_POST['mobile_number'];

    // Connect to the database
    try {
        $pdo = getDatabaseConnection();

        // Update tenant details
        $query = "UPDATE tenants SET full_name = :full_name, gender = :gender, mobile_number = :mobile_number WHERE id = :tenant_id";
        $stmt = $pdo->prepare($query);
        $stmt->execute([
            'full_name' => $fullName,
            'gender' => $gender,
            'mobile_number' => $mobileNumber,
            'tenant_id' => $tenantId
        ]);

        echo json_encode([
            'status' => 'success',
            'message' => 'Tenant successfully updated.'
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