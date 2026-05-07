<?php
session_start();
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $stmt = $conn->prepare("SELECT * FROM account WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();

    if ($result && password_verify($_POST['password'], $result['password'])) {
        $_SESSION['username'] = $result['username'];
        header("Location: index.php"); exit();
    } else {
        echo "Wrong username or password.";
    }
}
?>
<form method="POST">
  Username: <input name="username"><br>
  Password: <input type="password" name="password"><br>
  <button type="submit">Sign In</button>
</form>
