<?php
header("Access-Control-Allow-Origin: *"); // Allow all origins or restrict to a specific domain
header("Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");

header('Content-Type: application/json');
require 'db.php';  // Include database connection using PDO

// Get the POST data
$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['full_name'], $data['gender'], $data['mobile_number'])) {
    $full_name = $data['full_name'];
    $gender = $data['gender'];
    $mobile_number = $data['mobile_number'];

    try {
        $stmt = $pdo->prepare("INSERT INTO tenants (full_name, gender, mobile_number) 
                               VALUES (:full_name, :gender, :mobile_number)");
        $stmt->execute([
            ':full_name' => $full_name,
            ':gender' => $gender,
            ':mobile_number' => $mobile_number
        ]);
        echo json_encode(['status' => 'success', 'message' => 'Tenant added successfully']);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Missing required fields']);
}
