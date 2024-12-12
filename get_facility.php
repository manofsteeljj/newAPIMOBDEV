<?php
header('Access-Control-Allow-Origin: http://localhost:3000'); // Allow requests from localhost:3000
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS'); // Specify allowed HTTP methods
header('Access-Control-Allow-Headers: Content-Type, Authorization'); // Allow specific headers
header('Access-Control-Allow-Credentials: true'); // Allow cookies if needed
require_once('db.php'); // Include database connection

header('Content-Type: application/json');

if (isset($_GET['id'])) {
    $facilityId = $_GET['id'];

    try {
        $query = "SELECT * FROM facilities WHERE id = :facility_id";
        $stmt = $pdo->prepare($query);
        $stmt->execute(['facility_id' => $facilityId]);
        $facility = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($facility) {
            echo json_encode([
                'status' => 'success',
                'facility' => $facility
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
