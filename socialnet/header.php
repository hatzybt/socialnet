</body>
</html><?php
if (session_status() === PHP_SESSION_NONE) session_start();
$current_page = basename($_SERVER['PHP_SELF']);
$logged_in_user = $_SESSION['username'] ?? null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>SocialNet</title>
<style>
  * { margin: 0; padding: 0; box-sizing: border-box; }
  body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
         background: #f0f2f5; color: #1c1e21; min-height: 100vh; }

  nav {
    background: #fff;
    box-shadow: 0 2px 4px rgba(0,0,0,.1);
    position: sticky; top: 0; z-index: 100;
    display: flex; align-items: center; justify-content: space-between;
    padding: 0 24px; height: 56px;
  }
  .nav-logo { font-size: 24px; font-weight: 800; color: #1877f2; text-decoration: none; }
  .nav-links { display: flex; gap: 4px; }
  .nav-links a {
    padding: 8px 16px; border-radius: 8px; text-decoration: none;
    color: #606770; font-weight: 500; font-size: 15px; transition: all .2s;
  }
  .nav-links a:hover { background: #f0f2f5; color: #1877f2; }
  .nav-links a.active { background: #e7f3ff; color: #1877f2; }
  .nav-links a.signout { color: #fa3e3e; }
  .nav-links a.signout:hover { background: #fff0f0; }

  .container { max-width: 1100px; margin: 24px auto; padding: 0 16px; }
  .layout { display: grid; grid-template-columns: 280px 1fr; gap: 20px; }

  .card {
    background: #fff; border-radius: 12px;
    box-shadow: 0 1px 4px rgba(0,0,0,.1); padding: 20px;
  }
  .card h3 { font-size: 17px; color: #1c1e21; margin-bottom: 16px;
              padding-bottom: 12px; border-bottom: 1px solid #f0f2f5; }

  .profile-card { text-align: center; }
  .avatar {
    width: 80px; height: 80px; border-radius: 50%;
    background: linear-gradient(135deg, #1877f2, #42b0ff);
    display: flex; align-items: center; justify-content: center;
    font-size: 32px; color: #fff; font-weight: 700;
    margin: 0 auto 12px;
  }
  .avatar.sm { width: 44px; height: 44px; font-size: 18px; flex-shrink: 0; }
  .profile-card .name { font-size: 18px; font-weight: 700; }
  .profile-card .username { color: #606770; font-size: 14px; margin-top: 2px; }

  .user-item {
    display: flex; align-items: center; gap: 12px;
    padding: 10px 0; border-bottom: 1px solid #f0f2f5;
  }
  .user-item:last-child { border-bottom: none; padding-bottom: 0; }
  .user-item .info { flex: 1; }
  .user-item .info .name { font-weight: 600; font-size: 15px; }
  .user-item .info .username { font-size: 13px; color: #606770; }

  .btn {
    padding: 7px 16px; border: none; border-radius: 8px;
    font-size: 14px; font-weight: 600; cursor: pointer; transition: all .2s;
    text-decoration: none; display: inline-block;
  }
  .btn-primary { background: #1877f2; color: #fff; }
  .btn-primary:hover { background: #166fe5; }
  .btn-secondary { background: #e4e6eb; color: #1c1e21; }
  .btn-secondary:hover { background: #d8dadf; }
  .btn-sm { padding: 5px 12px; font-size: 13px; }
  .btn-friend { background: #e7f3ff; color: #1877f2; }
  .btn-friend:hover { background: #cce4ff; }

  .section-title { font-size: 20px; font-weight: 700; margin-bottom: 16px; color: #1c1e21; }

  .profile-header {
    background: #fff; border-radius: 12px; overflow: hidden;
    box-shadow: 0 1px 4px rgba(0,0,0,.1); margin-bottom: 20px;
  }
  .profile-cover {
    height: 200px;
    background: linear-gradient(135deg, #1877f2 0%, #42b0ff 100%);
  }
  .profile-info {
    padding: 0 24px 24px;
    display: flex; align-items: flex-end; gap: 20px;
    margin-top: -40px;
  }
  .profile-info .avatar-lg {
    width: 100px; height: 100px; border-radius: 50%;
    background: linear-gradient(135deg, #1877f2, #42b0ff);
    display: flex; align-items: center; justify-content: center;
    font-size: 42px; color: #fff; font-weight: 700;
    border: 4px solid #fff; flex-shrink: 0;
  }
  .profile-info .details { padding-bottom: 8px; }
  .profile-info .details h2 { font-size: 24px; }
  .profile-info .details p { color: #606770; }

  .form-group { margin-bottom: 16px; }
  .form-group label { display: block; font-weight: 600; margin-bottom: 6px; font-size: 14px; }
  .form-group input, .form-group textarea {
    width: 100%; padding: 10px 14px; border: 1.5px solid #ccd0d5;
    border-radius: 8px; font-size: 15px; transition: border .2s; font-family: inherit;
  }
  .form-group input:focus, .form-group textarea:focus { outline: none; border-color: #1877f2; }
  .form-group textarea { resize: vertical; min-height: 100px; }

  .alert { padding: 12px 16px; border-radius: 8px; margin-bottom: 16px; font-size: 14px; }
  .alert-success { background: #d4edda; color: #155724; }
  .alert-error { background: #f8d7da; color: #721c24; }

  .badge { display: inline-block; padding: 2px 8px; border-radius: 20px; font-size: 12px; font-weight: 600; }
  .badge-friend { background: #d4edda; color: #155724; }

  .empty { text-align: center; color: #606770; padding: 30px 0; font-size: 15px; }

  @media (max-width: 700px) {
    .layout { grid-template-columns: 1fr; }
    .sidebar { display: none; }
  }
</style>
</head>
<body>
<?php require 'navbar.php'; ?>
