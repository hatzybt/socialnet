<?php
// Establish DB connection directly 
$conn = new mysqli("localhost", "hatzy", "098762", "socialnet");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$msg = "";

// VULNERABILITY: CSRF (Admin-1 / ATT-2)
// No token validation, no session check. Anyone can trigger this via a crafted URL.
if (isset($_GET['username']) && isset($_GET['password'])) {
    $u = $_GET['username'];
    $p = $_GET['password'];
    
    $sql = "INSERT INTO account (username, password, fullname, description) VALUES ('$u', '$p', 'New User', '')";
    
    if ($conn->query($sql) === TRUE) {
        $msg = "User '$u' successfully added!";
    } else {
        $msg = "Error adding user: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin - Add User</title>
</head>
<body style="font-family: sans-serif; max-width: 400px; margin: 50px auto;">
    <h2>Admin Panel: Add New User</h2>
    
    <?php if ($msg) echo "<p style='color:green;'>$msg</p>"; ?>
    
    <form method="GET" action="newuser.php" style="display: flex; flex-direction: column; gap: 10px;">
        <label>New Username:</label>
        <input type="text" name="username" required>
        
        <label>New Password:</label>
        <input type="password" name="password" required>
        
        <button type="submit" style="padding: 10px; cursor: pointer;">Add User</button>
    </form>
</body>
</html>
