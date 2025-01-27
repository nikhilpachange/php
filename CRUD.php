<?php
// Database Configuration
$host = 'localhost';
$dbname = 'example_db';
$username = 'root';
$password = '';

try {
    // Create a PDO connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Utility Function: Sanitize Input
function sanitizeInput($input)
{
    return htmlspecialchars(strip_tags(trim($input)));
}

// Handle HTTP Requests
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action'])) {
        $action = sanitizeInput($_POST['action']);

        switch ($action) {
            case 'create':
                createRecord($pdo, $_POST);
                break;
            case 'update':
                updateRecord($pdo, $_POST);
                break;
            case 'delete':
                deleteRecord($pdo, $_POST);
                break;
        }
    }
}

// Create Record
function createRecord($pdo, $data)
{
    try {
        $name = sanitizeInput($data['name']);
        $email = sanitizeInput($data['email']);

        $stmt = $pdo->prepare("INSERT INTO users (name, email) VALUES (:name, :email)");
        $stmt->execute([':name' => $name, ':email' => $email]);
        echo "Record created successfully.";
    } catch (PDOException $e) {
        echo "Error creating record: " . $e->getMessage();
    }
}

// Update Record
function updateRecord($pdo, $data)
{
    try {
        $id = (int)$data['id'];
        $name = sanitizeInput($data['name']);
        $email = sanitizeInput($data['email']);

        $stmt = $pdo->prepare("UPDATE users SET name = :name, email = :email WHERE id = :id");
        $stmt->execute([':name' => $name, ':email' => $email, ':id' => $id]);
        echo "Record updated successfully.";
    } catch (PDOException $e) {
        echo "Error updating record: " . $e->getMessage();
    }
}

// Delete Record
function deleteRecord($pdo, $data)
{
    try {
        $id = (int)$data['id'];

        $stmt = $pdo->prepare("DELETE FROM users WHERE id = :id");
        $stmt->execute([':id' => $id]);
        echo "Record deleted successfully.";
    } catch (PDOException $e) {
        echo "Error deleting record: " . $e->getMessage();
    }
}

// Fetch All Records (for Display)
function fetchAllRecords($pdo)
{
    try {
        $stmt = $pdo->query("SELECT * FROM users");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error fetching records: " . $e->getMessage();
        return [];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP CRUD Application</title>
</head>
<body>
    <h1>PHP CRUD Application</h1>

    <!-- Create Form -->
    <form method="POST">
        <h3>Create Record</h3>
        <input type="hidden" name="action" value="create">
        <label for="name">Name:</label>
        <input type="text" name="name" required>
        <label for="email">Email:</label>
        <input type="email" name="email" required>
        <button type="submit">Create</button>
    </form>

    <!-- Display Records -->
    <h3>Records:</h3>
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach (fetchAllRecords($pdo) as $record): ?>
                <tr>
                    <td><?= $record['id'] ?></td>
                    <td><?= $record['name'] ?></td>
                    <td><?= $record['email'] ?></td>
                    <td>
                        <!-- Update and Delete Buttons -->
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="<?= $record['id'] ?>">
                            <button type="submit">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
