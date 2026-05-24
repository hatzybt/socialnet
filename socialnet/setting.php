<?php
include 'config.php';
if (!isset($_SESSION['username'])) { header("Location: signin.php"); exit(); }

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $description = $_POST['description'];
    $current_user = $_SESSION['username'];
    
    // VULNERABILITY: SQL Injection (ATT-3) 
    // An attacker can input: test' WHERE username='admin' -- 
    $sql = "UPDATE account SET description='$description' WHERE username='$current_user'";
    $conn->query($sql);
    echo "<p>Profile updated successfully!</p>";
}
?>
<!DOCTYPE html>
<html>
<body>
    <?php include 'menubar.php'; ?>
    <h2>Edit Profile</h2>
    <form method="POST" action="setting.php">
        Description:<br>
        <textarea name="description" rows="5" cols="40"></textarea><br>
        <button type="submit">Update</button>
    </form>
</body>
</html>
