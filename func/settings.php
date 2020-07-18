<?php
//dont delete this
define("DEBUG", true);
session_start();

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

function checkIfFriended($friend1, $friend2, $connection)
{
	$stmt = $connection->prepare("SELECT * FROM `friends` WHERE reciever = ? AND sender = ? OR reciever = ? AND sender = ?");
	$stmt->bind_param("ssss", $friend1, $friend2, $friend2, $friend1);
	$stmt->execute();
	$result = $stmt->get_result();
	if($result->num_rows === 1){ return true; }
	return false;
}

//thanks dzhaugasharov https://gist.github.com/afsalrahim/bc8caf497a4b54c5d75d
function replaceBBcodes($text) {
	$text = htmlspecialchars($text);
	// BBcode array
	$find = array(
		'~\[b\](.*?)\[/b\]~s',
		'~\[i\](.*?)\[/i\]~s',
		'~\[u\](.*?)\[/u\]~s',
		'~\[quote\]([^"><]*?)\[/quote\]~s',
		'~\[size=([^"><]*?)\](.*?)\[/size\]~s',
		'~\[color=([^"><]*?)\](.*?)\[/color\]~s',
		'~\[url\]((?:ftp|https?)://[^"><]*?)\[/url\]~s',
		'~\[img\](https?://[^"><]*?\.(?:jpg|jpeg|gif|png|bmp))\[/img\]~s'
	);
	// HTML tags to replace BBcode
	$replace = array(
		'<b>$1</b>',
		'<i>$1</i>',
		'<span style="text-decoration:underline;">$1</span>',
		'<pre>$1</'.'pre>',
		'<span style="font-size:$1px;">$2</span>',
		'<span style="color:$1;">$2</span>',
		'<a href="$1">$1</a>',
		'<img src="$1" alt="" />'
	);
	// Replacing the BBcodes with corresponding HTML tags
	return preg_replace($find, $replace, $text);
}
?>