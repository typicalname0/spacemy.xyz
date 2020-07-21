<?php
    require("../func/conn.php");
    require("../func/settings.php");
    requireLogin();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>New blog - spacemy.xyz</title>
        <link rel="stylesheet" href="/css/header.css">
        <link rel="stylesheet" href="/css/base.css">
        <script src='https://www.google.com/recaptcha/api.js' async defer></script>
        <script>function onLogin(token){ document.getElementById('submitform').submit(); }</script>
    </head>
    <body>
        <?php
            require("../header.php");
            if($_SERVER['REQUEST_METHOD'] == 'POST') 
            {
                if(!$_POST['groupname']){ $error = "you must specify a blog title"; goto skip; }
                if(!$_POST['desc']){ $error = "you must specify a blog body"; goto skip; }
                if(strlen($_POST['groupname']) > 32){ $error = "blog title must be shorter than 32 characters"; goto skip; }
                if(strlen($_POST['desc']) > 500){ $error = "blog body must be shorter than 500 characters"; goto skip; }
                if(!$_POST['g-recaptcha-response']){ $error = "captcha validation failed"; goto skip; }
                if(!validateCaptcha(CAPTCHA_PRIVATEKEY, $_POST['g-recaptcha-response'])) { $error = "captcha validation failed"; goto skip; }
              
                $stmt = $conn->prepare("INSERT INTO `blogs` (text, author, date, title) VALUES (?, ?, now(), ?)");
                $stmt->bind_param("sss", $text, $_SESSION['user'], $name);
                $unprocessedText = replaceBBcodes($_POST['desc']);
                $text = str_replace(PHP_EOL, "<br>", $unprocessedText);
                $name = htmlspecialchars($_POST['groupname']);
                $stmt->execute();
                $stmt->close();         
                header("Location: /blogs/");              
            }
            skip:
        ?>
        <div class="container">
            <?php if(isset($error)) { echo "<small style='color:red'>".$error."</small>"; } ?>
            <form method="post" enctype="multipart/form-data" id="submitform">
                <input required placeholder="Title" size="50" type="text" name="groupname"><br>
                <textarea required rows="10" cols="68" placeholder="Text" name="desc"></textarea><br>
                <input type="submit" value="Create" class="g-recaptcha" data-sitekey="<?php echo CAPTCHA_SITEKEY; ?>" data-callback="onLogin"> <small>max limit: 500 characters</small>
            </form>
        </div>
    </body>
</html>
