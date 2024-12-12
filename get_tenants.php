<?php
// Allow requests from localhost:3000 (React app)
header('Access-Control-Allow-Origin: http://localhost:3000');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Include the database connection
require_once('db.php');

// Query to fetch tenant information
$query = "SELECT t.id, t.full_name, t.gender, t.mobile_number, t.stay_from, t.stay_to, t.room_id, r.room_number, r.room_type 
          FROM tenants t
          LEFT JOIN rooms r ON t.room_id = r.id";

$stmt = $pdo->prepare($query);
$stmt->execute();
$tenants = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Check if tenants are available
if ($tenants) {
    echo json_encode([
        'status' => 'success',
        'tenants' => $tenants
    ]);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'No tenants found'
    ]);
}
?>
