<?php
require 'header.php';
if (!$logged_in_user) { header("Location: signin.php"); exit(); }
require 'config.php';

$owner = isset($_GET['owner']) ? trim($_GET['owner']) : $logged_in_user;
$stmt = $conn->prepare("SELECT * FROM account WHERE username=?");
$stmt->bind_param("s", $owner);
$stmt->execute();
$profile = $stmt->get_result()->fetch_assoc();

if (!$profile) { echo "<div class='container'><div class='card'>User not found.</div></div>"; require 'footer.php'; exit(); }

$stmt2 = $conn->prepare("SELECT id FROM account WHERE username=?");
$stmt2->bind_param("s", $logged_in_user);
$stmt2->execute();
$me = $stmt2->get_result()->fetch_assoc();

$is_own_profile = ($owner === $logged_in_user);

$is_friend = false;
if (!$is_own_profile) {
    $chk = $conn->query("SELECT 1 FROM friend WHERE account_id={$me['id']} AND friend_id={$profile['id']}");
    $is_friend = $chk->num_rows > 0;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$is_own_profile) {
    $fid = $profile['id']; $mid = $me['id'];
    if (isset($_POST['add_friend'])) {
        $conn->query("INSERT IGNORE INTO friend (account_id, friend_id) VALUES ($mid, $fid)");
        $conn->query("INSERT IGNORE INTO friend (account_id, friend_id) VALUES ($fid, $mid)");
    } elseif (isset($_POST['remove_friend'])) {
        $conn->query("DELETE FROM friend WHERE (account_id=$mid AND friend_id=$fid) OR (account_id=$fid AND friend_id=$mid)");
    }
    header("Location: profile.php?owner=" . urlencode($owner)); exit();
}

$fc = $conn->query("SELECT COUNT(*) as c FROM friend WHERE account_id={$profile['id']}")->fetch_assoc()['c'];
$initials = strtoupper(substr($profile['fullname'], 0, 1));
?>

<div class="container" style="max-width:860px">
  <div class="profile-header">
    <div class="profile-cover"></div>
    <div class="profile-info">
      <div class="avatar-lg"><?= $initials ?></div>
      <div class="details" style="flex:1">
        <h2><?= htmlspecialchars($profile['fullname']) ?></h2>
        <p>@<?= htmlspecialchars($profile['username']) ?> · <?= $fc ?> friend<?= $fc!=1?'s':'' ?></p>
      </div>
      <div style="padding-bottom:8px;display:flex;gap:8px">
        <?php if ($is_own_profile): ?>
          <a href="setting.php" class="btn btn-secondary">✏️ Edit Profile</a>
        <?php elseif ($is_friend): ?>
          <span class="badge badge-friend" style="padding:7px 14px">✓ Friends</span>
          <form method="POST">
            <button name="remove_friend" class="btn btn-secondary">Unfriend</button>
          </form>
        <?php else: ?>
          <form method="POST">
            <button name="add_friend" class="btn btn-primary">+ Add Friend</button>
          </form>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <div style="display:grid;grid-template-columns:1fr 2fr;gap:20px">
    <div class="card">
      <h3>About</h3>
      <?php if (!empty($profile['description'])): ?>
        <p style="font-size:15px;line-height:1.6"><?= nl2br(htmlspecialchars($profile['description'])) ?></p>
      <?php else: ?>
        <p style="color:#606770;font-size:14px">
          <?= $is_own_profile ? 'Add a bio in Settings.' : 'No bio yet.' ?>
        </p>
      <?php endif; ?>
    </div>

    <div class="card">
      <h3>👤 Profile Info</h3>
      <div style="display:flex;flex-direction:column;gap:12px;margin-top:4px">
        <div>
          <div style="font-size:12px;color:#606770;text-transform:uppercase;letter-spacing:.5px">Full Name</div>
          <div style="font-size:16px;font-weight:600"><?= htmlspecialchars($profile['fullname']) ?></div>
        </div>
        <div>
          <div style="font-size:12px;color:#606770;text-transform:uppercase;letter-spacing:.5px">Username</div>
          <div style="font-size:16px;font-weight:600">@<?= htmlspecialchars($profile['username']) ?></div>
        </div>
        <div>
          <div style="font-size:12px;color:#606770;text-transform:uppercase;letter-spacing:.5px">Friends</div>
          <div style="font-size:16px;font-weight:600"><?= $fc ?></div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php require 'footer.php'; ?>
