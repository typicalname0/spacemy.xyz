<?php
    require("../func/conn.php");
    require("../func/settings.php");
?>
<!DOCTYPE html>
<html>
    <head>
        <title>New group - spacemy.xyz</title>
        <link rel="stylesheet" href="css/header.css">
        <link rel="stylesheet" href="css/base.css">
        <script src='https://www.google.com/recaptcha/api.js' async defer></script>
        <script>function onLogin(token){ document.getElementById('submitform').submit(); }</script>
    </head>
    <body>
        <?php
            require("../header.php");
            if($_SERVER['REQUEST_METHOD'] == 'POST') 
            {
                if(!$_POST['groupname']){ $error = "you must specify a group title"; goto skip; }
                if(!$_POST['desc']){ $error = "you must specify a group description"; goto skip; }
                if(strlen($_POST['groupname']) > 32){ $error = "group title must be shorter than 32 characters"; goto skip; }
                if(strlen($_POST['desc']) > 500){ $error = "group description must be shorter than 500 characters"; goto skip; }
                if(!$_POST['g-recaptcha-response']){ $error = "captcha validation failed"; goto skip; }
                if(!validateCaptcha(CAPTCHA_PRIVATEKEY, $_POST['g-recaptcha-response'])) { $error = "captcha validation failed"; goto skip; }

                $stmt = $conn->prepare("INSERT INTO `groups` (name, description, author, date) VALUES (?, ?, ?, now())");
                $stmt->bind_param("sss", $name, $text, $_SESSION['user']);
                $text = str_replace(PHP_EOL, "<br>", $_POST['desc']);
                $name = htmlspecialchars($_POST['groupname']);
                $stmt->execute();
                $stmt->close();            

                $stmt = $conn->prepare("UPDATE users SET currentgroup = ? WHERE username = ?");
                $stmt->bind_param("ss", $groupname, $_SESSION['user']);
                $groupname = htmlspecialchars($_POST['groupname']);
                $stmt->execute();
                $stmt->close();  
                header("Location: index.php");             
            }
            skip:
        ?>
        <div class="container">
            <?php if(isset($error)) { echo "<small style='color:red'>".$error."</small>"; } ?>
            <form method="post" enctype="multipart/form-data" id="submitform">
                <input required placeholder="Name [32 characters max]" size="90" type="text" name="groupname"><br>
				<textarea required rows="10" cols="68" placeholder="Description [500 characters max]" name="desc"></textarea><br>
				<input type="submit" value="Create" class="g-recaptcha" data-sitekey="<?php echo CAPTCHA_SITEKEY; ?>" data-callback="onLogin">
            </form>
        </div>
    </body>
</html>
