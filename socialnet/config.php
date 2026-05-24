<?php
$conn = new mysqli("localhost", "hatzy", "098762", "socialnet");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);
session_start();
?>
