
<?php
    require("func/conn.php");
    require("func/settings.php");
?>
<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="css/header.css">
        <link rel="stylesheet" href="css/base.css">
    </head>
    <body>
        <?php
            require("header.php");
        ?>
        <div class="container">
            <?php
				if(@$_POST) {
					if($_POST['password'] && $_POST['username']) {
						$stmt = $conn->prepare("SELECT password FROM `users` WHERE username=?");
						$stmt->bind_param("s", $_POST['username']);
						$stmt->execute();
						$result = $stmt->get_result();
						
						while($row = $result->fetch_assoc()) {
							$hash = $row['password'];
							if(password_verify($_POST['password'], $hash)){
								session_set_cookie_params(69420000);
								$_SESSION['user'] = htmlspecialchars($_POST["username"]);
								header("Location: manage.php");
							} else {
								echo 'login information dosent exist.<hr>';
							}
						}
					}
				} 
            ?>
            <form action="" method="post">
                <input required placeholder="Username" type="text" name="username"><br>
                <input required placeholder="Password" type="password" name="password"><br><br>
                <input type="submit" value="Login">
            </form>
        </div>
    </body>
</html>