<?php
session_start();
if (!isset($_SESSION['username'])) { header("Location: signin.php"); exit(); }
require 'config.php';

$logged_in_user = $_SESSION['username'];

$stmt = $conn->prepare("SELECT * FROM account WHERE username=?");
$stmt->bind_param("s", $logged_in_user);
$stmt->execute();
$me = $stmt->get_result()->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_friend'])) {
    $friend_username = $_POST['friend_username'];
    $stmt2 = $conn->prepare("SELECT id FROM account WHERE username=?");
    $stmt2->bind_param("s", $friend_username);
    $stmt2->execute();
    $friend = $stmt2->get_result()->fetch_assoc();
    if ($friend) {
        $fid = $friend['id']; $mid = $me['id'];
        $conn->query("INSERT IGNORE INTO friend (account_id, friend_id) VALUES ($mid, $fid)");
        $conn->query("INSERT IGNORE INTO friend (account_id, friend_id) VALUES ($fid, $mid)");
    }
    header("Location: index.php"); exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_friend'])) {
    $friend_username = $_POST['friend_username'];
    $stmt2 = $conn->prepare("SELECT id FROM account WHERE username=?");
    $stmt2->bind_param("s", $friend_username);
    $stmt2->execute();
    $friend = $stmt2->get_result()->fetch_assoc();
    if ($friend) {
        $fid = $friend['id']; $mid = $me['id'];
        $conn->query("DELETE FROM friend WHERE (account_id=$mid AND friend_id=$fid) OR (account_id=$fid AND friend_id=$mid)");
    }
    header("Location: index.php"); exit();
}
require 'header.php';
$friend_ids = [];
$fr = $conn->query("SELECT friend_id FROM friend WHERE account_id={$me['id']}");
while ($row = $fr->fetch_assoc()) $friend_ids[] = $row['friend_id'];

$others = $conn->query("SELECT id, username, fullname FROM account WHERE username != '{$me['username']}' ORDER BY fullname");
$friends = []; $strangers = [];
while ($row = $others->fetch_assoc()) {
    if (in_array($row['id'], $friend_ids)) $friends[] = $row;
    else $strangers[] = $row;
}
?>

<div class="container">
  <div class="layout">
    <div class="sidebar">
      <div class="card profile-card" style="margin-bottom:16px">
        <div class="avatar"><?= strtoupper(substr($me['fullname'],0,1)) ?></div>
        <div class="name"><?= htmlspecialchars($me['fullname']) ?></div>
        <div class="username">@<?= htmlspecialchars($me['username']) ?></div>
        <div style="margin-top:14px;display:flex;gap:8px;justify-content:center">
          <a href="profile.php" class="btn btn-secondary btn-sm">My Profile</a>
          <a href="setting.php" class="btn btn-primary btn-sm">Edit</a>
        </div>
      </div>
      <div class="card">
        <h3>👥 Friends (<?= count($friends) ?>)</h3>
        <?php if (empty($friends)): ?>
          <div class="empty">No friends yet.</div>
        <?php else: ?>
          <?php foreach ($friends as $f): ?>
          <div class="user-item">
            <div class="avatar sm"><?= strtoupper(substr($f['fullname'],0,1)) ?></div>
            <div class="info">
              <div class="name"><?= htmlspecialchars($f['fullname']) ?></div>
              <a href="profile.php?owner=<?= urlencode($f['username']) ?>" style="font-size:13px;color:#1877f2">View profile</a>
            </div>
          </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </div>

    <div>
      <div class="section-title">Your Friends</div>
      <?php if (empty($friends)): ?>
        <div class="card" style="margin-bottom:20px">
          <div class="empty">You haven't added any friends yet. Add some below!</div>
        </div>
      <?php else: ?>
      <div class="card" style="margin-bottom:20px">
        <?php foreach ($friends as $f): ?>
        <div class="user-item">
          <div class="avatar sm"><?= strtoupper(substr($f['fullname'],0,1)) ?></div>
          <div class="info">
            <div class="name"><?= htmlspecialchars($f['fullname']) ?></div>
            <div class="username">@<?= htmlspecialchars($f['username']) ?></div>
          </div>
          <a href="profile.php?owner=<?= urlencode($f['username']) ?>" class="btn btn-secondary btn-sm">Profile</a>
          <form method="POST" style="margin-left:6px">
            <input type="hidden" name="friend_username" value="<?= htmlspecialchars($f['username']) ?>">
            <button type="submit" name="remove_friend" class="btn btn-sm" style="background:#f8d7da;color:#721c24">Unfriend</button>
          </form>
        </div>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>

      <div class="section-title">People You May Know</div>
      <?php if (empty($strangers)): ?>
        <div class="card"><div class="empty">No new people to connect with.</div></div>
      <?php else: ?>
      <div class="card">
        <?php foreach ($strangers as $s): ?>
        <div class="user-item">
          <div class="avatar sm"><?= strtoupper(substr($s['fullname'],0,1)) ?></div>
          <div class="info">
            <div class="name"><?= htmlspecialchars($s['fullname']) ?></div>
            <div class="username">@<?= htmlspecialchars($s['username']) ?></div>
          </div>
          <a href="profile.php?owner=<?= urlencode($s['username']) ?>" class="btn btn-secondary btn-sm" style="margin-right:6px">Profile</a>
          <form method="POST">
            <input type="hidden" name="friend_username" value="<?= htmlspecialchars($s['username']) ?>">
            <button type="submit" name="add_friend" class="btn btn-friend btn-sm">+ Add Friend</button>
          </form>
        </div>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>
    </div>
  </div>
</div>

<?php require 'footer.php'; ?>
