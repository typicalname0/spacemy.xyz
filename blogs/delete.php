<?php
require("../func/conn.php");
require("../func/settings.php");
requireLogin();

if(isset($_GET['id'])) {
    $stmt = $conn->prepare("SELECT * FROM `blogs` WHERE id = ?");
    $stmt->bind_param("i", $_GET['id']);
    $stmt->execute();
    $result = $stmt->get_result();

    $author = $row['author'];

    if ($author === $_SESSION['user']) {
        $stmt = $conn->prepare("DELETE FROM `blogs` WHERE id = ?");
        $stmt->bind_param("i", $_GET['id']);
        $stmt->execute();
        $stmt->close();
    }
    header("Location: /blogs/");
}
?>