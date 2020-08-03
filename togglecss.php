<?php
require("func/conn.php");
require("func/settings.php");
requireLogin();

if(isset($_SESSION['toggleCSS'])) {
    if($_SESSION['toggleCSS'] == true) {
        $_SESSION['toggleCSS'] = false;
    } else {
        $_SESSION['toggleCSS'] = true;
    }
} else {
    $_SESSION['toggleCSS'] = true;
}

header("Location: manage.php");