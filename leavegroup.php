<?php
require("func/conn.php");
require("func/settings.php");

$stmt = $conn->prepare("UPDATE users SET currentgroup = 'None' WHERE username = ?");
$stmt->bind_param("s", $_SESSION['user']);
$stmt->execute();
$stmt->close();

header("Location: groups.php");
?>