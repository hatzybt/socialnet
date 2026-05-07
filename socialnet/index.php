<?php
session_start();
if (!isset($_SESSION['username'])) { header("Location: signin.php"); exit(); }
require 'config.php';

$me = $_SESSION['username'];
$result = $conn->query("SELECT username, fullname FROM account WHERE username != '$me'");
?>
<?php require 'navbar.php'; ?>
<h2>Welcome, <?= htmlspecialchars($me) ?></h2>
<h3>Other Users:</h3>
<?php while ($row = $result->fetch_assoc()): ?>
  <p>
    <?= htmlspecialchars($row['fullname']) ?>
    (<a href="profile.php?owner=<?= urlencode($row['username']) ?>">View Profile</a>)
  </p>
<?php endwhile; ?>
