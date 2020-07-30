<?php
require("../func/conn.php");
require("../func/settings.php");
requireLogin();

if((int)$_GET['id']) {
    $stmt = $conn->prepare("SELECT * FROM `groups` WHERE id = ?");
    $stmt->bind_param("i", $_GET['id']);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows === 0) { 
        exit('group dont exist'); 
    } else {
        $stmt = $conn->prepare("UPDATE users SET currentgroup = ? WHERE username = ?");
        $stmt->bind_param("is", $_GET['id'], $_SESSION['user']);
        $stmt->execute();
        $stmt->close();
    }
    header("Location: index.php");
}
