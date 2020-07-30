<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header("Content-Type: application/json");

require("../func/conn.php");
$array = new stdClass();

if(isset($_GET['name'])) {
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $_GET['name']);
    $stmt->execute();
    $result = $stmt->get_result();
    while($row = $result->fetch_assoc()) {
        $array->id = $row['id'];
        $array->bio = $row['bio'];
        $array->interests = $row['interests'];
        $array->usercreationdate = $row['date'];
        $array->username = $row['username'];
        $array->pfp = $row['pfp'];
        $array->music = $row['music'];
        $array->currentgroup = $row['currentgroup'];
        $array->status = $row['status'];
        $array->ranks = $row['ranks'];
        $array->lastactive = $row['lastactive'];
        echo json_encode($array);
    }
} else {
    $array->response = "You did not specify a name.";
    echo json_encode($array);
}
