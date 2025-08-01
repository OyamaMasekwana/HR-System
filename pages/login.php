<?php
session_start();
require_once __DIR__ . '/../includes/config.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    // Determine login type from submitted button
    $loginType = isset($_POST['login_admin']) ? 'admin' : (isset($_POST['login_user']) ? 'user' : '');

    if (!$username || !$password || !$loginType) {
        $error = "Please enter all fields and choose a login type.";
    } else {
      // Fetch user from database
      $stmt = $conn->prepare("SELECT id, username, passwordHash, role, employeeId FROM users WHERE username = ?");
      $stmt->bind_param("s", $username);
      $stmt->execute();
      $result = $stmt->get_result();

      if ($user = $result->fetch_assoc()) {
        if ($user['role'] !== $loginType) {
          $error = "❌ You are not registered as a {$loginType}.";
        } elseif (password_verify($password, $user['passwordHash'])) {
          $_SESSION['user_id'] = $user['id'];
          $_SESSION['username'] = $user['username'];
          $_SESSION['role'] = $user['role'];
          $_SESSION['employee_id'] = $user['employeeId']; // Set for both admin and user

          if ($user['role'] === 'admin') {
            header('Location: /hr_system/pages/admin_dashboard.php');
            exit();
          } else {
            header('Location: /hr_system/pages/MyDashboardPage.php');
            exit();
          }
        } else {
          $error = "❌ Invalid password.";
        }
      } else {
        $error = "❌ User not found.";
      }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Login</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f4f4f4;
      padding: 20px;
    }
    .login-container {
      max-width: 400px;
      margin: 50px auto;
      padding: 30px;
      background: white;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    h2 {
      text-align: center;
      margin-bottom: 25px;
    }
    label {
      display: block;
      margin-bottom: 8px;
      font-weight: bold;
    }
    input[type=text], input[type=password] {
      width: 100%;
      padding: 10px;
      margin-bottom: 15px;
      border-radius: 5px;
      border: 1px solid #ddd;
    }
    .buttons {
      display: flex;
      justify-content: space-between;
      gap: 10px;
    }
    button {
      flex: 1;
      padding: 10px;
      background: #007bff;
      border: none;
      color: white;
      font-weight: bold;
      border-radius: 5px;
      cursor: pointer;
      font-size: 16px;
    }
    button:hover {
      background: #0056b3;
    }
    .error {
      background: #f8d7da;
      color: #842029;
      padding: 10px;
      margin-bottom: 15px;
      border-radius: 5px;
      border: 1px solid #f5c2c7;
    }
  </style>
</head>
<body>
  <div class="login-container">
    

    <?php if ($error): ?>
      <div class="error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
      <h2>Login</h2>
    <form method="post" action="">
      <label for="username">Username</label>
      <input id="username" name="username" type="text"  autofocus />

      <label for="password">Password</label>
      <input id="password" name="password" type="password"  />

      <div class="buttons">
        <button type="submit" name="login_admin">Login as Admin</button>
      </div>
    </form>
  </div>
</body>
</html>

<style>

  /* .error{
    background:pink;
  }
.login-container {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh;
  background-color: rgb(33, 33, 33);
  background-image: url(../assets/moderntech-bg.png);
  background-size: cover;
  max-width: 100%;
  background-position: cover;
  
} 
.login-container h2 {
  color: #fff;
  text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
}
.login-container label {
  color: #fff;
}
.login-container input[type="text"],
.login-container input[type="password"] {
  background-color: rgba(255, 255, 255, 0.8); /* Semi-transparent white */
/* }
// the login name must be on top//
.login-container input[type="text"]:focus,
.login-container input[type="password"]:focus {
  border-color: #007bff; 
} */
</style> */
