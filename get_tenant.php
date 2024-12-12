<?php

// CORS Headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Handle Preflight Request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once 'db.php';

// Main Logic
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $tenantId = $_GET['id'] ?? null;

    if (!$tenantId) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Tenant ID is required.'
        ]);
        exit();
    }

    try {
        $pdo = getDatabaseConnection();
        $query = "SELECT * FROM tenants WHERE id = :tenant_id";
        $stmt = $pdo->prepare($query);
        $stmt->execute(['tenant_id' => $tenantId]);

        $tenant = $stmt->fetch();

        if ($tenant) {
            echo json_encode([
                'status' => 'success',
                'tenant' => $tenant
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Tenant not found.'
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
        'message' => 'Invalid request method.'
    ]);
}
