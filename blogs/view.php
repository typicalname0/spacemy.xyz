<?php
    require("../func/conn.php");
    require("../func/settings.php");
?>
<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="/css/header.css">
        <link rel="stylesheet" href="/css/base.css">
        <script src='https://www.google.com/recaptcha/api.js' async defer></script>
        <script>function onLogin(token){ document.getElementById('submitform').submit(); }</script>
        <?php
            $stmt = $conn->prepare("SELECT * FROM `blogs` WHERE id = ?");
            $stmt->bind_param("i", $_GET['id']);
            $stmt->execute();
            $result = $stmt->get_result();
            if(!mysqli_num_rows($result)){ header("Location: /blogs.php"); die(); }
            
            while($row = $result->fetch_assoc()) {
                $name = $row['title'];
                $desc = $row['text'];
                $author = $row['author'];
                $date = $row['date'];
            }

            $stmt = $conn->prepare("SELECT css FROM `users` WHERE username = ?");
            $stmt->bind_param("s", $author);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            if(mysqli_num_rows($result)){ echo "<style>".$row['css']."</style>"; }

            if($_SERVER['REQUEST_METHOD'] == 'POST') 
            {
                if(!isset($_SESSION['user'])){ $error = "you are not logged in"; goto skipcomment; }
                if(!$_POST['comment']){ $error = "your comment cannot be blank"; goto skipcomment; }
                if(strlen($_POST['comment']) > 500){ $error = "your comment must be shorter than 500 characters"; goto skipcomment; }
                if(!isset($_POST['g-recaptcha-response'])){ $error = "captcha validation failed"; goto skipcomment; }
                if(!validateCaptcha(CAPTCHA_PRIVATEKEY, $_POST['g-recaptcha-response'])) { $error = "captcha validation failed"; goto skipcomment; }

                $stmt = $conn->prepare("INSERT INTO `blogcomments` (toid, author, text, date) VALUES (?, ?, ?, now())");
                $stmt->bind_param("sss", $_GET['id'], $_SESSION['user'], $text);
                $unprocessedText = replaceBBcodes($_POST['comment']);
                $text = str_replace(PHP_EOL, "<br>", $unprocessedText);
                $stmt->execute();
                $stmt->close();
            }
            skipcomment:
        ?>
        <title><?php echo $name;?> - spacemy.xyz</title>
    </head>
    <body>
        <?php
            require("../header.php");
        ?>
        <div class="container">
            <h1><?php echo $name; ?></h1>
            <div class='commentRight' style='display: grid; grid-template-columns: 25% auto; padding:5px;'>
                <div>
                    <a style='float: left;' href='/profile.php?id=<?php echo getID($author, $conn); ?>'><?php echo $author; ?></a>
                    <br>
                    <img class='commentPictures' style='float: left; height:160px; width:160px' src='/pfp/<?php echo getPFP($author, $conn); ?>'>
                </div>
                <div style="word-wrap: break-word; padding-left:20px;">
                    <small><?php echo $date; ?> | <a href="#comment"><button>Comment</button></a></small>
                    <br>
                    <?php echo $desc; ?>
                </div>
            </div>
            <hr>
            <?php
                $stmt = $conn->prepare("SELECT * FROM `blogcomments` WHERE toid = ?");
                $stmt->bind_param("s", $_GET['id']);
                $stmt->execute();
                $result = $stmt->get_result();
            ?>
            <div class="commentsList">
                <?php while($row = $result->fetch_assoc()) { ?>
                <div class='commentRight' style='display: grid; grid-template-columns: auto 85%; padding:5px;'>
                    <div>
                        <a style='float: left;' href='/profile.php?id=<?php echo getID($row['author'], $conn); ?>'><?php echo $row['author']; ?></a>
                        <br>
                        <img class='commentPictures' style='float: left;' height='80px;'width='80px;'src='/pfp/<?php echo getPFP($row['author'], $conn); ?>'>
                    </div>
                    <div style="word-wrap: break-word;">
                        <small><?php echo $row['date']; ?></small>
                        <br>
                        <?php echo $row['text']; ?>
                    </div>
                </div>
                <?php } ?>
            </div>
            <br>
            <?php if(isset($_SESSION['user'])){ ?>
            <?php if(isset($error)) { echo "<small style='color:red'>".$error."</small>"; } ?>
            <form method="post" enctype="multipart/form-data" id="submitform">
                <textarea required rows="5" cols="77" placeholder="Comment on this blog post..." name="comment"></textarea><br>
                <input type="submit" value="Post" class="g-recaptcha" data-sitekey="<?php echo CAPTCHA_SITEKEY; ?>" data-callback="onLogin"> <small>max limit: 500 characters</small>
            </form>
            <?php } else {?>
            <a href="/login.php">Log in</a> to post comments
            <?php } ?>
            <br>
        </div>
    </body>
</html>