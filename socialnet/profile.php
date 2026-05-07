<?php
session_start();
if (!isset($_SESSION['username'])) { header("Location: signin.php"); exit(); }
require 'config.php';

$owner = isset($_GET['owner']) ? $_GET['owner'] : $_SESSION['username'];
$stmt = $conn->prepare("SELECT username, fullname, description FROM account WHERE username=?");
$stmt->bind_param("s", $owner);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();
?>
<?php require 'navbar.php'; ?>
<h2>Profile: <?= htmlspecialchars($row['fullname']) ?></h2>
<p><?= htmlspecialchars($row['description']) ?></p>
