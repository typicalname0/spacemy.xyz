<?php
require("func/settings.php");
if(isset($_SESSION['user'])) 
{ 
	$_SESSION = []; 
	session_destroy();
}
header("Location: /landing.php");