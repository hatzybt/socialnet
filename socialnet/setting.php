<?php
require 'header.php';
if (!$logged_in_user) { header("Location: signin.php"); exit(); }
require 'config.php';

$success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $desc = trim($_POST['description']);
    $fullname = trim($_POST['fullname']);
    $stmt = $conn->prepare("UPDATE account SET description=?, fullname=? WHERE username=?");
    $stmt->bind_param("sss", $desc, $fullname, $logged_in_user);
    $stmt->execute();
    $_SESSION['fullname'] = $fullname;
    $success = "Profile updated successfully!";
}

$stmt2 = $conn->prepare("SELECT * FROM account WHERE username=?");
$stmt2->bind_param("s", $logged_in_user);
$stmt2->execute();
$me = $stmt2->get_result()->fetch_assoc();
?>

<div class="container" style="max-width:600px">
  <div class="section-title">⚙️ Settings</div>
  <div class="card">
    <h3>Edit Profile</h3>
    <?php if ($success): ?>
      <div class="alert alert-success">✓ <?= $success ?></div>
    <?php endif; ?>
    <form method="POST">
      <div class="form-group">
        <label>Full Name</label>
        <input type="text" name="fullname" value="<?= htmlspecialchars($me['fullname']) ?>" required>
      </div>
      <div class="form-group">
        <label>Username</label>
        <input type="text" value="@<?= htmlspecialchars($me['username']) ?>" disabled style="background:#f0f2f5;color:#606770">
      </div>
      <div class="form-group">
        <label>Bio / Description</label>
        <textarea name="description" placeholder="Tell others about yourself..."><?= htmlspecialchars($me['description'] ?? '') ?></textarea>
      </div>
      <button type="submit" class="btn btn-primary">Save Changes</button>
    </form>
  </div>
</div>

<?php require 'footer.php'; ?>
