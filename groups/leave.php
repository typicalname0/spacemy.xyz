<?php
require("../func/conn.php");
require("../func/settings.php");
requireLogin();

$stmt = $conn->prepare("UPDATE users SET currentgroup = 0 WHERE username = ?");
$stmt->bind_param("s", $_SESSION['user']);
$stmt->execute();
$stmt->close();

header("Location: index.php");
