<?php
if(isset($_SESSION['user'])) 
{ 
	$_SESSION = []; 
	session_destroy();
}
header("Location: /");