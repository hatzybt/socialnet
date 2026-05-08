<?php
if (session_status() === PHP_SESSION_NONE) session_start();
$current_page = basename($_SERVER['PHP_SELF']);
$logged_in_user = $_SESSION['username'] ?? null;
?>
<?php if ($logged_in_user): ?>
<nav>
  <a class="nav-logo" href="/socialnet/index.php">SocialNet</a>
  <div class="nav-links">
    <a href="/socialnet/index.php" class="<?= $current_page==='index.php'?'active':'' ?>">🏠 Home</a>
    <a href="/socialnet/profile.php" class="<?= $current_page==='profile.php'?'active':'' ?>">👤 Profile</a>
    <a href="/socialnet/setting.php" class="<?= $current_page==='setting.php'?'active':'' ?>">⚙️ Settings</a>
    <a href="/socialnet/about.php" class="<?= $current_page==='about.php'?'active':'' ?>">ℹ️ About</a>
    <a href="/socialnet/signout.php" class="signout">🚪 Sign Out</a>
  </div>
</nav>
<?php endif; ?>
