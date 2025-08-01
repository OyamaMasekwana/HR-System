<?php
$host = 'localhost';
$db = 'company_hr';
$user = 'root';
$pass = 'OM1710004';
$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("❌ Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Check if username already exists
    $check = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $check->bind_param("s", $username);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        echo "❌ Username already taken. Try a different one.";
        $check->close();
        $conn->close();
        exit;
    }
    $check->close();

    // Hash the password
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    // Insert user
    $stmt = $conn->prepare("INSERT INTO users (username, passwordHash, role) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $passwordHash, $role);

    if ($stmt->execute()) {
        echo "✅ Registered successfully!";
    } else {
        echo "❌ Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
