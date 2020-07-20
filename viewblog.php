<?php
    require("func/conn.php");
    require("func/settings.php");
?>
<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="css/header.css">
        <link rel="stylesheet" href="css/base.css">
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

            if(@$_POST["comment"]) {
                $stmt = $conn->prepare("INSERT INTO `blogcomments` (toid, author, text, date) VALUES (?, ?, ?, now())");
                $stmt->bind_param("sss", $_GET['id'], $_SESSION['user'], $text);
            
                $unprocessedText = replaceBBcodes($_POST['comment']);
                $text = str_replace(PHP_EOL, "<br>", $unprocessedText);
                $stmt->execute();
            
                $stmt->close();
            }
        ?>
    </head>
    <body>
        <?php
            require("header.php");
        ?>
        <div class="container">
            <h1><?php echo $name; ?></h1>
            <div class='commentRight' style='display: grid; grid-template-columns: 25% auto; padding:5px;'>
                <div>
                    <a style='float: left;' href='profile.php?id=<?php echo getID($author, $conn); ?>'><?php echo $author; ?></a>
                    <br>
                    <img class='commentPictures' style='float: left; height:160px; width:160px' src='pfp/<?php echo getPFP($author, $conn); ?>'>
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
                <div class='commentRight' style='display: grid; grid-template-columns: 75% auto; padding:5px;'>
                    <div style="word-wrap: break-word;">
                        <small><?php echo $row['date']; ?></small>
                        <br>
                        <?php echo $row['text']; ?>
                    </div>
                    <div>
                        <a style='float: right;' href='profile.php?id=<?php echo getID($row['author'], $conn); ?>'><?php echo $row['author']; ?></a>
                        <br>
                        <img class='commentPictures' style='float: right;' height='80px;'width='80px;'src='pfp/<?php echo getPFP($row['author'], $conn); ?>'>
                    </div>
                </div>
                <?php } ?>
            </div>
            <br>
            <form method="post" enctype="multipart/form-data" id="comment">
                <textarea required rows="5" cols="77" placeholder="Comment on this blog post..." name="comment"></textarea><br>
                <input name="submit" type="submit" value="Post"> <small>max limit: 500 characters</small>
            </form>
            <br>
        </div>
    </body>
</html>