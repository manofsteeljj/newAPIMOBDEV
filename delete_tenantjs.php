<?php

require_once 'db.php';

// Set headers for CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Check if the request method is DELETE
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    if (isset($_GET['id'])) {
        $tenantId = $_GET['id'];

        // Connect to the database
        try {
            $pdo = getDatabaseConnection();

            // Prepare the DELETE query
            $query = "DELETE FROM tenants WHERE id = :tenant_id";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':tenant_id', $tenantId, PDO::PARAM_INT);

            // Execute the query and check if a row was deleted
            if ($stmt->execute()) {
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Tenant deleted successfully'
                ]);
            } else {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Failed to delete tenant'
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
            'message' => 'Tenant ID not provided'
        ]);
    }
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid request method'
    ]);
}

?>
