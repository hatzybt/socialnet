<?php
require '../socialnet/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $fullname = $_POST['fullname'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO account (username, fullname, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $fullname, $password);
    $stmt->execute();
    echo "User created!";
}
?>
<form method="POST">
  Username: <input name="username"><br>
  Full Name: <input name="fullname"><br>
  Password: <input type="password" name="password"><br>
  <button type="submit">Create User</button>
</form>
