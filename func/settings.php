<?php
//dont delete this
define("DEBUG", true);
session_start();

require_once('bbcode.php');

if(DEBUG == true) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

function validateCSS($validate) {
	$searchVal = array("<", ">", "<?php", "?>", "behavior: url"); 
	$replaceVal = array("", "", "", "", "", ""); 
	$validated = str_replace($searchVal, $replaceVal, $validate); 
    return $validated;
}

function getID($user, $connection) {
	$stmt = $connection->prepare("SELECT * FROM users WHERE username = ?");
	$stmt->bind_param("s", $user);
	$stmt->execute();
	$result = $stmt->get_result();
	if($result->num_rows === 0) return('error');
	while($row = $result->fetch_assoc()) {
		$id = $row['id'];
	} 
	$stmt->close();
	return $id;
}

function getName($id, $connection) {
	$stmt = $connection->prepare("SELECT * FROM users WHERE id = ?");
	$stmt->bind_param("s", $id);
	$stmt->execute();
	$result = $stmt->get_result();
	if($result->num_rows === 0) return('error');
	while($row = $result->fetch_assoc()) {
		$name = $row['username'];
	} 
	$stmt->close();
	return $name;
}

function getPFP($user, $connection) {
	$stmt = $connection->prepare("SELECT * FROM users WHERE username = ?");
	$stmt->bind_param("s", $user);
	$stmt->execute();
	$result = $stmt->get_result();
	if($result->num_rows === 0) return('error');
	while($row = $result->fetch_assoc()) {
		$pfp = $row['pfp'];
	} 
	$stmt->close();
	return $pfp;
}

//thanks dzhaugasharov https://gist.github.com/afsalrahim/bc8caf497a4b54c5d75d
function replaceBBcodes($text) {
	return bbcode_to_html($text);
}
?>