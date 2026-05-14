<?php
require 'header.php';
if (!$logged_in_user) { header("Location: signin.php"); exit(); }
require 'config.php';

$success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $desc = $_POST['description'];
    $fullname = $_POST['fullname'];
    // VULNERABLE: raw query, no prepared statement
    $conn->query("UPDATE account SET description='$desc', fullname='$fullname' WHERE username='$logged_in_user'");
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
        <input type="text" name="fullname" value="<?= $me['fullname'] ?>" required>
      </div>
      <div class="form-group">
        <label>Username</label>
        <input type="text" value="@<?= $me['username'] ?>" disabled style="background:#f0f2f5;color:#606770">
      </div>
      <div class="form-group">
        <label>Bio / Description</label>
        <textarea name="description" placeholder="Tell others about yourself..."><?= $me['description'] ?? '' ?></textarea>
      </div>
      <button type="submit" class="btn btn-primary">Save Changes</button>
    </form>
  </div>
</div>

<?php require 'footer.php'; ?>
