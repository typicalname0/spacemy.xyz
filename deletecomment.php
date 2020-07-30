<?php
require("func/conn.php");
require("func/settings.php");
requireLogin();

if(isset($_GET['id'])) {
    $stmt = $conn->prepare("SELECT * FROM `users` WHERE username = ?");
    $stmt->bind_param("s", $_SESSION['user']);
    $stmt->execute();
    $result = $stmt->get_result();
    
    while($row = $result->fetch_assoc()) {
        $id = $row['id'];
    }

    $stmt = $conn->prepare("SELECT * FROM `comments` WHERE toid = ? AND id = ?");
    $stmt->bind_param("ii", $id, $_GET['id']);
    $stmt->execute();
    $result = $stmt->get_result();
    
    while($row = $result->fetch_assoc()) {
        $check = 1;
    }

    if($check == 1) {
        $stmt = $conn->prepare("DELETE FROM comments WHERE id = ?");
        $stmt->bind_param("i", $_GET['id']);
        $stmt->execute();
        $stmt->close();
    }
    header("Location: index.php");
}
