<?php
header("Access-Control-Allow-Origin: http://localhost:3000"); // Allow requests from React app
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json"); // Ensure JSON response for fetch requests

// Handle preflight requests (OPTIONS)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}
?>

$error = isset($_GET['error']) ? $_GET['error'] : null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dormitory Login</title>
    <style>
        /* Basic Reset */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

/* Body Styling */
body {
    animation: background-gradient 6s infinite alternate;
    background: linear-gradient(135deg, #1e3c72, #2a5298);
    font-family: 'Arial', sans-serif;
    color: #fff;
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
}

@keyframes background-gradient {
    0% { background: linear-gradient(135deg, #1e3c72, #2a5298); }
    100% { background: linear-gradient(135deg, #2a5298, #4e73df); }
}

/* Login Container */
.login-container {
    background-color: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    padding: 20px 40px;
    border-radius: 10px;
    box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
    animation: slide-in 1s ease-out forwards;
    opacity: 0;
}

@keyframes slide-in {
    0% { opacity: 0; transform: translateY(-30px); }
    100% { opacity: 1; transform: translateY(0); }
}

@keyframes shake {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-5px); }
    50% { transform: translateX(5px); }
}

/* Header Styling */
h1 {
    margin-bottom: 20px;
    font-size: 2.5rem;
    font-weight: 600;
    letter-spacing: 1px;
    color: #f4f4f9; /* Soft white for header text */
}

/* Input Fields */
input[type="text"],
input[type="password"] {
    width: 100%;
    padding: 12px;
    margin: 15px 0;
    border-radius: 5px;
    border: none;
    background-color: #f9f9f9;
    color: #333;
    font-size: 16px;
    transition: all 0.3s ease-in-out;
}

/* Input Focus State */
input[type="text"]:focus,
input[type="password"]:focus {
    outline: none;
    border: 2px solid #4e73df;
    background-color: #f0f0f0;
}

button {
    background-color: #4e73df;
    border: none;
    padding: 10px 20px;
    border-radius: 20px;
    color: #fff;
    font-size: 16px;
    cursor: pointer;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

button:hover {
    transform: scale(1.1);
    box-shadow: 0 0 20px 5px rgba(78, 115, 223, 0.7);
}

.error-message {
    color: #ff6b6b;
    animation: shake 0.5s ease-in-out;
}


/* Register Section */
.register-container {
    margin-top: 20px;
}

.register-container p {
    font-size: 14px;
    color: #ddd;
}

.register-container button {
    margin-top: 10px;
    padding: 10px 20px;
    background-color: #28a745;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
    font-size: 16px;
}

/* Register Button Hover */
.register-container button:hover {
    background-color: #218838;
}

/* Responsive Design */
@media (max-width: 600px) {
    .login-container {
        width: 85%;
        padding: 30px 20px;
    }

    h1 {
        font-size: 2rem;
    }

    input[type="text"],
    input[type="password"] {
        font-size: 14px;
    }

    button {
        font-size: 14px;
        padding: 10px;
    }
}

    </style>
</head>
<body>
    <div class="login-container">
        <h1>Login</h1>
        <?php if ($error): ?>
            <p class="error-message"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        <form action="validate_login.php" method="POST">
            <input type="text" name="username" placeholder="Username" >
            <input type="password" name="password" placeholder="Password" >
            <button type="submit">Login</button>
        </form>
        <div class="register-container">
            <p>Don't have an account?</p>
            <button onclick="window.location.href='register.php'">Register</button>
        </div>
    </div>
</body>
</html>
