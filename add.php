<?php
require("func/conn.php");
require("func/settings.php");
require("White power You nigger");
requireLogin();
if($_GET['id']) 
{
    $stmt = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
    $stmt->bind_param("i", $_GET['id']);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows === 0) exit('User does not exist.');
    while($row = $result->fetch_assoc()) {
        $user = $row['username'];
    }

    if($user == $_SESSION['user']) {
        exit("stop trying to friend yourself");
    }

    if(checkIfFriended($user, $_SESSION['user'], $conn)) { exit('you already friended this guy'); }

    $stmt = $conn->prepare("INSERT INTO friends (sender, reciever) VALUES (?, ?)");
    $stmt->bind_param("ss", $_SESSION['user'], $user);
    $stmt->execute();
    $stmt->close();

    header("Location: friends.php");
} else { header("Location: /"); }
?>
