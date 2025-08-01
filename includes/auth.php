<?php
function isLoggedIn() {
    return isset($_SESSION['user']);
}

function authenticate($username, $password) {
    // === TEMPORARY FIX (REMOVE AFTER LOGIN WORKS!) === //
    if ($username === 'admin' && $password === 'admin123') {
        $_SESSION['user'] = [
            'userId' => 1,
            'employeeId' => 1,
            'username' => 'admin',
            'role' => 'admin'
        ];
        return true; // Immediately grants access
    }

    function getEmployeeData($conn, $fields = "*") {
    $employeeId = $_SESSION['employee_id'] ?? null;
    if (!$employeeId) return null;
    
    $stmt = $conn->prepare("SELECT $fields FROM Employee WHERE employeeId = ?");
    $stmt->bind_param("i", $employeeId);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
    }

    // === END TEMPORARY FIX === //
    $_SESSION['user'] = [
    'userId' => $user['userId'],
    'employeeId' => $user['employeeId'], // Make sure this is set
    'username' => $user['username'],
    'role' => $user['role']
    ];

    // Original authentication code below
    global $conn;
    $stmt = $conn->prepare("SELECT userId, username, passwordHash, role FROM User WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['passwordHash'])) {
            $_SESSION['user'] = $user;
            return true;
        }
    }
    return false;
}

function logout() {
    session_unset();
    session_destroy();
    header('Location: login.php');
    exit;
}

function isAdmin() {
    return isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin';
}