<!DOCTYPE html>
<html>
    <head>
        <script src='https://www.google.com/recaptcha/api.js' async defer></script>
        <script>function onLogin(token){ document.getElementById('submitform').submit(); }</script>
        <?php
            require("require.php");
        ?>
    </head>
    <body>
        <?php
            if(isset($_GET['id'])) {
                $stmt = $conn->prepare("SELECT * FROM `games` WHERE id = ?");
                $stmt->bind_param("i", $_GET['id']);
                $stmt->execute();
                $result = $stmt->get_result();
                if(!mysqli_num_rows($result)){ header("Location: /index.php"); die(); }
                
                while($row = $result->fetch_assoc()) {
                    $filename = $row['filename'];
                    $date = $row['date'];
                    $title = $row['title'];
                    $description = $row['description'];
                    $author = $row['author'];
                }    
            } else {
                header("Location: index.php");
            }
            require("../header.php");
            
        ?>
        <div class="container">
            <?php
                if($_SERVER['REQUEST_METHOD'] == 'POST') 
                {
                    if(!isset($_SESSION['user'])){ $error = "you are not logged in"; goto skipcomment; }
                    if(!$_POST['comment']){ $error = "your comment cannot be blank"; goto skipcomment; }
                    if(strlen($_POST['comment']) > 500){ $error = "your comment must be shorter than 500 characters"; goto skipcomment; }

                    $stmt = $conn->prepare("INSERT INTO `gamecomments` (toid, author, text, date) VALUES (?, ?, ?, now())");
                    $stmt->bind_param("sss", $_GET['id'], $_SESSION['user'], $text);
                    $unprocessedText = replaceBBcodes($_POST['comment']);
                    $text = str_replace(PHP_EOL, "<br>", $unprocessedText);
                    $stmt->execute();
                    $stmt->close();
                }
                skipcomment:
                if(isset($error)) {
                    echo "<span style='color: red;'><small>" . $error . "</small></span><br>";
                }
            ?>
            <h1 style="display: inline-block; margin-bottom: 0px;"><?php echo $title; ?></h1><br><small>[Uploaded at <b><?php echo $date?></b> by <b><?php echo $author; ?></b>]</small><br>
            <?php echo $description; ?><br><br>
            <embed src="gamefiles/<?php echo $filename; ?>"  height="300px" width="500px"> </embed>
            <h2>User Submitted Comments</h2>
            <form method="post" enctype="multipart/form-data" id="submitform">
                <textarea required cols="59" placeholder="Comment" name="comment"></textarea><br>
                <input type="submit" value="Post" class="g-recaptcha" data-sitekey="<?php echo CAPTCHA_SITEKEY; ?>" data-callback="onLogin"> <small>max limit: 500 characters | bbcode supported</small>
            </form>
            <?php
                $stmt = $conn->prepare("SELECT * FROM `gamecomments` WHERE toid = ? ORDER BY id DESC");
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
            <a href="index.php"><< Go Back</a>
        </div>
    </body>
</html>