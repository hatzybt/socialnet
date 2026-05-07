<?php
session_start();
if (!isset($_SESSION['username'])) { header("Location: signin.php"); exit(); }
require 'config.php';

$me = $_SESSION['username'];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $desc = $_POST['description'];
    $stmt = $conn->prepare("UPDATE account SET description=? WHERE username=?");
    $stmt->bind_param("ss", $desc, $me);
    $stmt->execute();
    echo "Saved!";
}
$row = $conn->query("SELECT description FROM account WHERE username='$me'")->fetch_assoc();
?>
<?php require 'navbar.php'; ?>
<form method="POST">
  <textarea name="description"><?= htmlspecialchars($row['description']) ?></textarea><br>
  <button type="submit">Save</button>
</form>
