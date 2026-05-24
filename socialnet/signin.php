<?php
include 'config.php';

// If already logged in, redirect to Home Page immediately
if (isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

$error_msg = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // VULNERABILITY: SQL Injection (SignIn-1 / ATT-5)
    // Directly concatenating POST variables into the query
    $sql = "SELECT * FROM account WHERE username='$username' AND password='$password'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        // Login successful
        $_SESSION['username'] = $username;
        header("Location: index.php");
        exit();
    } else {
        $error_msg = "Invalid username or password!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Sign In - SocialNet</title>
</head>
<body style="font-family: sans-serif; max-width: 400px; margin: 50px auto;">
    <h2>Sign In to SocialNet</h2>
    
    <?php if ($error_msg) echo "<p style='color:red;'>$error_msg</p>"; ?>
    
    <form method="POST" action="signin.php" style="display: flex; flex-direction: column; gap: 10px;">
        <label>Username:</label>
        <input type="text" name="username" required>
        
        <label>Password:</label>
        <input type="password" name="password" required>
        
        <button type="submit" style="padding: 10px; cursor: pointer;">Login</button>
    </form>
</body>
</html>
