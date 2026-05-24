<?php
// 1. Include config first (handles DB connection and session_start())
include 'config.php';

// Redirect to signin if not logged in
if (!isset($_SESSION['username'])) {
    header("Location: signin.php");
    exit();
}

// Get the owner from the query string, default to the logged-in user
$owner = isset($_GET['owner']) ? $_GET['owner'] : $_SESSION['username'];

// VULNERABILITY: SQL Injection (Profile-3)
// The $owner parameter is directly concatenated into the query string, allowing UNION attacks.
$sql = "SELECT * FROM account WHERE username='$owner'";
$result = $conn->query($sql);

// VULNERABILITY: Broken Access Control / CSRF (Profile-2)
// The mock requirements state "View profile page (friend only)", but this code 
// intentionally fails to check the `friend` table. Anyone can view anyone's profile.
?>
<!DOCTYPE html>
<html>
<head>
    <title>Profile - SocialNet</title>
</head>
<body>
    <?php include 'menubar.php'; ?>

    <?php
    if ($result && $result->num_rows > 0) {
        // Fetch the user's data
        $row = $result->fetch_assoc();
        
        echo "<h2>Profile of: " . $row['username'] . "</h2>";
        echo "<p><strong>Full Name:</strong> " . $row['fullname'] . "</p>";
        
        echo "<h3>About Me:</h3>";
        echo "<div style='border:1px solid #ccc; padding:15px; margin-top:10px; background:#f9f9f9;'>";
        
        // VULNERABILITY: Stored XSS / Session Hijacking (Profile-1 & Session-1)
        // The description is output directly into the HTML DOM without sanitization (like htmlspecialchars).
        // If an attacker puts `<script>` tags in their description via setting.php, it will execute here.
        echo $row['description']; 
        
        echo "</div>";
    } else {
        echo "<p>User not found.</p>";
    }
    ?>
</body>
</html>
