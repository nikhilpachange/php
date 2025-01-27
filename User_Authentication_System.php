<?php
// Database Configuration
$host = 'localhost';
$dbname = 'user_auth';
$username = 'root';
$password = '';

try {
    // Establish PDO Connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Start Session
session_start();

// Utility Function: Sanitize Input
function sanitizeInput($input)
{
    return htmlspecialchars(strip_tags(trim($input)));
}

// User Registration
function registerUser($pdo, $data)
{
    try {
        $username = sanitizeInput($data['username']);
        $password = sanitizeInput($data['password']);
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Check if username already exists
        $checkStmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
        $checkStmt->execute([':username' => $username]);
        if ($checkStmt->rowCount() > 0) {
            echo "Username already exists.";
            return;
        }

        // Insert new user into the database
        $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
        $stmt->execute([':username' => $username, ':password' => $hashedPassword]);
        echo "Registration successful! You can now log in.";
    } catch (PDOException $e) {
        echo "Error registering user: " . $e->getMessage();
    }
}

// User Login
function loginUser($pdo, $data)
{
    try {
        $username = sanitizeInput($data['username']);
        $password = sanitizeInput($data['password']);

        // Fetch user from the database
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->execute([':username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header("Location: dashboard.php");
            exit;
        } else {
            echo "Invalid username or password.";
        }
    } catch (PDOException $e) {
        echo "Error logging in: " . $e->getMessage();
    }
}

// Handle Form Submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];
        if ($action === 'register') {
            registerUser($pdo, $_POST);
        } elseif ($action === 'login') {
            loginUser($pdo, $_POST);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Authentication System</title>
</head>
<body>
    <h1>User Authentication System</h1>

    <!-- Registration Form -->
    <form method="POST">
        <h3>Register</h3>
        <input type="hidden" name="action" value="register">
        <label for="username">Username:</label>
        <input type="text" name="username" required>
        <label for="password">Password:</label>
        <input type="password" name="password" required>
        <button type="submit">Register</button>
    </form>

    <!-- Login Form -->
    <form method="POST">
        <h3>Login</h3>
        <input type="hidden" name="action" value="login">
        <label for="username">Username:</label>
        <input type="text" name="username" required>
        <label for="password">Password:</label>
        <input type="password" name="password" required>
        <button type="submit">Login</button>
    </form>
</body>
</html>
