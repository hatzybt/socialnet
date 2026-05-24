<?php
include 'config.php';
// Destroy session [cite: 403]
session_destroy();
// Redirect to Signin Page [cite: 480]
header("Location: signin.php");
exit();
?>
