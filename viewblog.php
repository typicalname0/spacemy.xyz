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
            <?php
                echo $author . "@" . $date . "<hr>";
                echo $desc;
            ?>
            <br><hr>
            <?php if ($author === $_SESSION['user']) {
                echo "<a href='/deleteblog.php?id=" . $_GET['id'] . "'><button>Delete blog</button></a><br/><br/>";
            }?>
            <form method="post" enctype="multipart/form-data">
				<textarea required rows="5" cols="77" placeholder="Comment" name="comment"></textarea><br>
				<input name="submit" type="submit" value="Post"> <small>max limit: 500 characters</small>
            </form>
            <br>
            <?php
                $stmt = $conn->prepare("SELECT * FROM `blogcomments` WHERE toid = ?");
                $stmt->bind_param("s", $_GET['id']);
                $stmt->execute();
                $result = $stmt->get_result();
                
                while($row = $result->fetch_assoc()) {
                    echo "<div class='commentRight'>";
                    echo "  <small>" . $row['date'] . "</small><br>" . $row['text'];
                    echo "  <a style='float: right;' href='profile.php?id=" . getID($row['author'], $conn) . "'>" . $row['author'] . "</a> <br>";
                    echo "  <img class='commentPictures' style='float: right;' height='80px' width='80px;'src='pfp/" . getPFP($row['author'], $conn) . "'><br><br><br><br><br>";
                    echo "</div>";
                }
            ?>
        </div>
    </body>
</html>