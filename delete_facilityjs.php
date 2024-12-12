<?php
// Allow cross-origin requests from localhost:3000
header('Access-Control-Allow-Origin: http://localhost:3000');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Access-Control-Allow-Credentials: true');

// Handle OPTIONS requests (preflight)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0); // Preflight request; no further processing needed
}

require_once('db.php'); // Database connection file

header('Content-Type: application/json');

if (isset($_GET['id'])) {
    $facilityId = $_GET['id'];

    try {
        $query = "DELETE FROM facilities WHERE id = :facility_id";
        $stmt = $pdo->prepare($query);
        $stmt->execute(['facility_id' => $facilityId]);

        if ($stmt->rowCount() > 0) {
            echo json_encode([
                'status' => 'success',
                'message' => 'Facility deleted successfully'
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Facility not found'
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
        'message' => 'No facility ID provided'
    ]);
}
?>
