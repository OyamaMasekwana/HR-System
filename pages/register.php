<!-- register_php.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
</head>
<body>
    <h2>Register as User or Admin</h2>
    <form method="POST" action="hash.php">
        <label>Username:</label><br>
        <input type="text" name="username" required><br><br>

        <label>Password:</label><br>
        <input type="password" name="password" required><br><br>

        <label>Role:</label><br>
        <select name="role" required>
            <option value="user">User</option>
            <option value="admin">Admin</option>
        </select><br><br>

        <button type="submit">Register</button>
    </form>
</body>
</html>
