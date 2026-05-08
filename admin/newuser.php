<?php
require '../socialnet/config.php';

$success = $error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $fullname = trim($_POST['fullname']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO account (username, fullname, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $fullname, $password);
    if ($stmt->execute()) {
        $success = "User '$username' created successfully!";
    } else {
        $error = "Username already exists or an error occurred.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin – Create User</title>
<style>
  * { margin:0;padding:0;box-sizing:border-box; }
  body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
         background:#f0f2f5; min-height:100vh; display:flex; align-items:center; justify-content:center; }
  .box { background:#fff; border-radius:16px; padding:32px; width:100%; max-width:420px;
         box-shadow: 0 2px 12px rgba(0,0,0,.12); }
  h2 { margin-bottom:6px; font-size:22px; }
  .sub { color:#606770; font-size:14px; margin-bottom:24px; }
  .form-group { margin-bottom:14px; }
  .form-group label { display:block; font-weight:600; margin-bottom:6px; font-size:14px; }
  .form-group input { width:100%; padding:12px 14px; border:1.5px solid #ccd0d5;
                      border-radius:8px; font-size:15px; }
  .form-group input:focus { outline:none; border-color:#1877f2; }
  .btn { width:100%; padding:13px; background:#1877f2; color:#fff; border:none;
         border-radius:8px; font-size:16px; font-weight:700; cursor:pointer; margin-top:4px; }
  .btn:hover { background:#166fe5; }
  .alert { padding:12px 14px; border-radius:8px; margin-bottom:16px; font-size:14px; }
  .alert-success { background:#d4edda; color:#155724; }
  .alert-error { background:#f8d7da; color:#721c24; }
  .badge { display:inline-block; background:#e7f3ff; color:#1877f2; padding:3px 10px;
           border-radius:20px; font-size:12px; font-weight:600; margin-bottom:16px; }
</style>
</head>
<body>
<div class="box">
  <span class="badge">🔧 Admin Panel</span>
  <h2>Create New User</h2>
  <p class="sub">Add a new account to SocialNet</p>

  <?php if ($success): ?>
    <div class="alert alert-success">✓ <?= htmlspecialchars($success) ?></div>
  <?php endif; ?>
  <?php if ($error): ?>
    <div class="alert alert-error">⚠️ <?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <form method="POST">
    <div class="form-group">
      <label>Username</label>
      <input type="text" name="username" placeholder="e.g. john_doe" required>
    </div>
    <div class="form-group">
      <label>Full Name</label>
      <input type="text" name="fullname" placeholder="e.g. John Doe" required>
    </div>
    <div class="form-group">
      <label>Password</label>
      <input type="password" name="password" placeholder="Set a password" required>
    </div>
    <button type="submit" class="btn">Create User</button>
  </form>
</div>
</body>
</html>
