<?php
include 'config.php';
// Redirect to signin if not logged in [cite: 384, 451]
if (!isset($_SESSION['username'])) {
    header("Location: signin.php");
    exit();
}

$current_user = $_SESSION['username'];

// Handle "Make Friend" action [cite: 424]
if (isset($_GET['add_friend'])) {
    $friend_id = (int)$_GET['add_friend'];
    // Vulnerable to CSRF: No token check for making friends
    $sql_get_my_id = "SELECT Id FROM account WHERE username='$current_user'";
    $my_id = $conn->query($sql_get_my_id)->fetch_assoc()['Id'];
    $conn->query("INSERT INTO friend (account_id, friend_id) VALUES ($my_id, $friend_id)");
    header("Location: index.php");
    exit();
}
// Handle "Unfriend" action
if (isset($_GET['unfriend'])) {
    $friend_id = (int)$_GET['unfriend'];
    $sql_get_my_id = "SELECT Id FROM account WHERE username='$current_user'";
    $my_id = $conn->query($sql_get_my_id)->fetch_assoc()['Id'];
    
    // Delete the friendship from the database
    $conn->query("DELETE FROM friend WHERE account_id = $my_id AND friend_id = $friend_id");
    
    header("Location: index.php");
    exit();
}
// Fetch current user details [cite: 452]
$sql = "SELECT * FROM account WHERE username='$current_user'";
$user_info = $conn->query($sql)->fetch_assoc();
$my_id = $user_info['Id'];
?>

<!DOCTYPE html>
<html>
<head><title>Home - SocialNet</title></head>
<body>
    <?php include 'menubar.php'; ?>
    
    <h2>Welcome, <?php echo $user_info['fullname']; ?> (<?php echo $user_info['username']; ?>)</h2>

    <h3>Your Friends</h3>
    <ul>
    <?php
    // Fetch Friends
    $friend_sql = "SELECT account.Id, account.username FROM account 
                   JOIN friend ON account.Id = friend.friend_id 
                   WHERE friend.account_id = $my_id";
    $friends = $conn->query($friend_sql);
    while($f = $friends->fetch_assoc()) {
        echo "<li>
                <a href='profile.php?owner=" . $f['username'] . "'>" . $f['username'] . "</a> 
                - <a href='index.php?unfriend=" . $f['Id'] . "'><button style='color:red;'>Unfriend</button></a>
              </li>";
    }
    ?>
    </ul>

    <h3>Strangers</h3>
    <ul>
    <?php
    // Fetch Strangers [cite: 422]
    $stranger_sql = "SELECT Id, username FROM account 
                     WHERE Id != $my_id AND Id NOT IN (SELECT friend_id FROM friend WHERE account_id = $my_id)";
    $strangers = $conn->query($stranger_sql);
    while($s = $strangers->fetch_assoc()) {
        // Make friend button/link [cite: 424]
        echo "<li>" . $s['username'] . " - <a href='index.php?add_friend=" . $s['Id'] . "'><button>Make Friend</button></a></li>";
    }
    ?>
    </ul>
</body>
</html>
