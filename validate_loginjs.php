<?php

// Allow requests from your React app's URL
header("Access-Control-Allow-Origin: *");  // Allows requests from any origin
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Credentials: true");

// Handle preflight requests (OPTIONS)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Start session and include database configuration
session_start();
require 'db.php';

// Initialize the response array
$response = [];

// Ensure all output is sent as JSON
header('Content-Type: application/json');

// Decode JSON input data
$data = json_decode(file_get_contents("php://input"), true);

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ensure username and password are provided
    $username = isset($data['username']) ? trim($data['username']) : '';
    $password = isset($data['password']) ? trim($data['password']) : '';

    if (!empty($username) && !empty($password)) {
        try {
            // Prepare the SQL query to find the user by username
            $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
            $stmt->bindParam(':username', $username);
            $stmt->execute();

            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                // Check if the provided password matches the stored hashed password
                if (password_verify($password, $user['password'])) {
                    // Set session variables
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];

                    // Send a success response
                    $response = [
                        'status' => 'success',
                        'message' => 'Login successful',
                        'user_id' => $user['id'], // Include user_id in the response
                    ];
                } else {
                    // Invalid password
                    $response = [
                        'status' => 'error',
                        'message' => 'Invalid username or password.',
                    ];
                }
            } else {
                // User not found
                $response = [
                    'status' => 'error',
                    'message' => 'Invalid username or password.',
                ];
            }
        } catch (PDOException $e) {
            // Database error
            $response = [
                'status' => 'error',
                'message' => 'Database error: ' . $e->getMessage(),
            ];
        }
    } else {
        // Missing username or password
        $response = [
            'status' => 'error',
            'message' => 'Please fill in both fields.',
        ];
    }
} else {
    // Invalid request method
    $response = [
        'status' => 'error',
        'message' => 'Invalid request method.',
    ];
}

// Send the response as JSON
echo json_encode($response);
exit;
