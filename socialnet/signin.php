<?php
session_start();
if (isset($_SESSION['username'])) { header("Location: index.php"); exit(); }
require 'config.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    // VULNERABLE: raw query, no prepared statement
    $result = $conn->query("SELECT * FROM account WHERE username='$username'")->fetch_assoc();

    if ($result && password_verify($_POST['password'], $result['password'])) {
        // VULNERABLE: no session_regenerate_id
        $_SESSION['username'] = $result['username'];
        $_SESSION['fullname'] = $result['fullname'];
        header("Location: index.php"); exit();
    } else {
        $error = "Wrong username or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>SocialNet – Sign In</title>
<style>
  * { margin: 0; padding: 0; box-sizing: border-box; }
  body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
         background: #f0f2f5; min-height: 100vh;
         display: flex; align-items: center; justify-content: center; }
  .auth-box { width: 100%; max-width: 400px; padding: 16px; }
  .logo { text-align: center; font-size: 44px; font-weight: 900; color: #1877f2;
          letter-spacing: -1px; margin-bottom: 8px; }
  .tagline { text-align: center; color: #606770; font-size: 16px; margin-bottom: 24px; }
  .card { background: #fff; border-radius: 16px; padding: 24px;
          box-shadow: 0 2px 12px rgba(0,0,0,.12); }
  .form-group { margin-bottom: 14px; }
  .form-group input {
    width: 100%; padding: 14px 16px; border: 1.5px solid #ccd0d5;
    border-radius: 10px; font-size: 16px; transition: border .2s;
  }
  .form-group input:focus { outline: none; border-color: #1877f2; }
  .btn-submit {
    width: 100%; padding: 14px; background: #1877f2; color: #fff;
    border: none; border-radius: 10px; font-size: 17px; font-weight: 700;
    cursor: pointer; transition: background .2s; margin-top: 4px;
  }
  .btn-submit:hover { background: #166fe5; }
  .alert-error { background: #f8d7da; color: #721c24; padding: 12px 16px;
                 border-radius: 8px; font-size: 14px; margin-bottom: 14px; }
</style>
</head>
<body>
<div class="auth-box">
  <div class="logo">SocialNet</div>
  <div class="tagline">Connect with friends and the world.</div>
  <div class="card">
    <?php if ($error): ?>
      <div class="alert-error">⚠️ <?= $error ?></div>
    <?php endif; ?>
    <form method="POST">
      <div class="form-group">
        <input type="text" name="username" placeholder="Username" required autofocus>
      </div>
      <div class="form-group">
        <input type="password" name="password" placeholder="Password" required>
      </div>
      <button type="submit" class="btn-submit">Log In</button>
    </form>
  </div>
</div>
</body>
</html>
