<?php
    require("func/conn.php");
    require("func/settings.php");
    requireLogin();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Settings - spacemy.xyz</title>
        <link rel="stylesheet" href="css/header.css">
        <link rel="stylesheet" href="css/base.css">
    </head>
    <body>
        <?php
            require("header.php");
            $stmt = $conn->prepare("SELECT * FROM `users` WHERE username=?");
            $stmt->bind_param("s", $_SESSION['user']);
            $stmt->execute();
            $result = $stmt->get_result();
            
            while($row = $result->fetch_assoc()) {
                $bio = $row['bio'];
                $css = $row['css'];
                $id = $row['id'];
                $interests = $row['interests'];
            }
            if(@$_POST['interestset']) {
                $stmt = $conn->prepare("UPDATE users SET interests = ? WHERE `users`.`username` = ?;");
                $stmt->bind_param("ss", $text, $_SESSION['user']);
                $unprocessedText = replaceBBcodes($_POST['interests']);
                $text = str_replace(PHP_EOL, "<br>", $unprocessedText);
                $stmt->execute(); 
                $stmt->close();
                header("Location: manage.php");
            } else if(@$_POST['bioset']) {
                $stmt = $conn->prepare("UPDATE users SET bio = ? WHERE `users`.`username` = ?;");
                $stmt->bind_param("ss", $text, $_SESSION['user']);
                $unprocessedText = replaceBBcodes($_POST['bio']);
                $text = str_replace(PHP_EOL, "<br>", $unprocessedText);
                $stmt->execute(); 
                $stmt->close();
                header("Location: manage.php");
            } else if(@$_POST['statusset']) {
                $stmt = $conn->prepare("UPDATE users SET status = ? WHERE `users`.`username` = ?;");
                $stmt->bind_param("ss", $text, $_SESSION['user']);
                $text = htmlspecialchars($_POST['status']);
                $stmt->execute(); 
                $stmt->close();
                header("Location: manage.php");
            } else if(@$_POST['css']) {
                $stmt = $conn->prepare("UPDATE users SET css = ? WHERE `users`.`username` = ?;");
                $stmt->bind_param("ss", $validatedcss, $_SESSION['user']);
                $validatedcss = validateCSS($_POST['css']);
                $stmt->execute(); 
                $stmt->close();
                header("Location: manage.php");
            } else if(@$_POST['submit']) {
                $target_dir = "pfp/";
                $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
                $uploadOk = 1;
                $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
                if(isset($_POST["submit"])) {
                    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
                    if($check !== false) {
                        $uploadOk = 1;
                    } else {
                        $uploadOk = 0;
                    }
                }
                if (file_exists($target_file)) {
                    echo 'file with the same name already exists<hr>';
                    $uploadOk = 0;
                }
                if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
                && $imageFileType != "gif" ) {
                    echo 'unsupported file type. must be jpg, png, jpeg, or gif<hr>';
                    $uploadOk = 0;
                }
                if ($uploadOk == 0) { } else {
                    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                        $stmt = $conn->prepare("UPDATE users SET pfp = ? WHERE `users`.`username` = ?;");
                        $stmt->bind_param("ss", $filename, $_SESSION['user']);
                        $filename = basename($_FILES["fileToUpload"]["name"]);
                        $stmt->execute(); 
                        $stmt->close();
                    } else {
                        echo 'fatal error<hr>';
                    }
                }
            } else if(@$_POST['photoset']) {
                $target_dir = "music/";
                $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
                $uploadOk = 1;
                $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
                if(isset($_POST["submit"])) {
                    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
                    if($check !== false) {
                        $uploadOk = 1;
                    } else {
                        $uploadOk = 0;
                    }
                }
                if (file_exists($target_file)) {
                    echo 'file with the same name already exists<hr>';
                    $uploadOk = 0;
                }
                if($imageFileType != "ogg" && $imageFileType != "mp3") {
                    echo 'unsupported file type. must be mp3 or ogg<hr>';
                    $uploadOk = 0;
                }
                if ($uploadOk == 0) { } else {
                    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                        $stmt = $conn->prepare("UPDATE users SET music = ? WHERE `users`.`username` = ?;");
                        $stmt->bind_param("ss", $filename, $_SESSION['user']);
                        $filename = basename($_FILES["fileToUpload"]["name"]);
                        $stmt->execute(); 
                        $stmt->close();
                    } else {
                        echo 'fatal error' . $_FILES["fileToUpload"]["error"] . '<hr>';
                    }
                }
            }
            ?>
        <div class="container">
            <?php
                echo '<img src="pfp/' . getPFP($_SESSION['user'], $conn) . '"><h2>' . htmlspecialchars($_SESSION['user']) . '</h1>';
            ?>
            <form method="post" enctype="multipart/form-data">
				<small>Select photo:</small>
				<input type="file" name="fileToUpload" id="fileToUpload">
				<input type="submit" value="Upload Image" name="submit">
            </form>
            <form method="post" enctype="multipart/form-data">
				<small>Select song:</small>
				<input type="file" name="fileToUpload" id="fileToUpload">
				<input type="submit" value="Upload Song" name="photoset">
			</form>
            <br>
            <b>Bio</b>
			<form method="post" enctype="multipart/form-data">
				<textarea required cols="58" placeholder="Bio" name="bio"><?php echo $bio;?></textarea><br>
				<input name="bioset" type="submit" value="Set"> <small>max limit: 500 characters | supports bbcode</small>
            </form>
            <br>
            <b>Interests</b>
            <form method="post" enctype="multipart/form-data">
				<textarea required cols="58" placeholder="Interests" name="interests"><?php echo $interests;?></textarea><br>
				<input name="interestset" type="submit" value="Set"> <small>max limit: 500 characters | supports bbcode</small>
			</form>
            <br>
            <b>CSS</b>
			<form method="post" enctype="multipart/form-data">
				<textarea required rows="15" cols="58" placeholder="Your CSS" name="css"><?php echo $css;?></textarea><br>
				<input name="cssset" type="submit" value="Set"> <small>max limit: 5000 characters</small>
            </form>
            <br>
            <b>Status</b>
			<form method="post" enctype="multipart/form-data">
                <input size="77" type="text" name="status"><br>
				<input name="statusset" type="submit" value="Set"> <small>max limit: 255 characters</small>
            </form>
            <br>
        </div>
    </body>
</html>
