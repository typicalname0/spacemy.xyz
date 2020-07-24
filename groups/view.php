<?php
    require("../func/conn.php");
    require("../func/settings.php");
?>
<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="/css/header.css">
        <link rel="stylesheet" href="/css/base.css">
        <style type="text/css">
            #left {float: left;width:59%;}
            #right {float: right;width:39%;}
        </style>
        <?php
            $stmt = $conn->prepare("SELECT * FROM `groups` WHERE id = ?");
            $stmt->bind_param("i", $_GET['id']);
            $stmt->execute();
            $result = $stmt->get_result();
            if(!mysqli_num_rows($result)){ header("Location: /groups.php"); die(); }
            
            while($row = $result->fetch_assoc()) {
                $name = $row['name'];
                $desc = $row['description'];
                $author = $row['author'];
                $date = $row['date'];
                $shout = $row['shout'];
            }

            if(@$_POST["comment"]) {
                $stmt = $conn->prepare("INSERT INTO `groupcomments` (toid, author, text, date) VALUES (?, ?, ?, now())");
                $stmt->bind_param("sss", $_GET['id'], $_SESSION['user'], $text);
            
                $unprocessedText = replaceBBcodes($_POST['comment']);
                $text = str_replace(PHP_EOL, "<br>", $unprocessedText);
                $stmt->execute();
            
                $stmt->close();
            }
            if(@$_POST['shout']) {
                $stmt = $conn->prepare("UPDATE `groups` SET shout = ? WHERE id = ?");
                $stmt->bind_param("si", $_POST['shout'], $_GET['id']);
                $stmt->execute();
                $stmt->close();
            }
        ?>
        <title><?php echo $name;?> - spacemy.xyz</title>
    </head>
    <body>
        <?php
            require("../header.php");
        ?>
        <div class="container">
            <h1><?php echo $name;?></h1>
            <pre><?php echo $desc;?></pre>
            <hr/>
            <div id="left">
                <b id="shout"><?php echo $shout;?></b><br/><br/>
                <div class="info">
                    <center>Comments</center>
                </div>
                <form method="post" enctype="multipart/form-data">
                    <textarea required rows="5" cols="44" placeholder="Comment" name="comment"></textarea><br>
                    <input name="submit" type="submit" value="Post"> <small>max limit: 500 characters</small>
                </form>
                <br>
                <?php
                $stmt = $conn->prepare("SELECT * FROM `groupcomments` WHERE toid = ?");
                $stmt->bind_param("s", $_GET['id']);
                $stmt->execute();
                $result = $stmt->get_result();
                    
                while($row = $result->fetch_assoc()) { ?>
                <div class='commentRight'>
                    <div><small><?php echo $row['date']; ?></small><br><?php echo $row['text']; ?></div>
                    <div>
                        <a style='float: right;' href='/profile.php?id=<?php echo getID($row['author'], $conn); ?>'><?php echo $row['author']; ?></a> <br>
                        <img class='commentPictures' style='float: right;' width='80px;'src='/pfp/<?php echo getPFP($row['author'], $conn); ?>'>
                        <br><br><br><br><br>
                    </div>
                </div>
                <?php } ?>
            </div>
            <div id="right">
                <?php
                    if (isset($_SESSION['user'])) {
                        $stmt = $conn->prepare("SELECT * FROM `users` WHERE username = ?");
                        $stmt->bind_param("s", $_SESSION['user']);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        while ($row = $result->fetch_assoc()) {
                            if($row['currentgroup'] == $_GET['id']) {
                                echo "<a href='leave.php'><button>Leave Group</button></a>";
                            } else {
                                echo "<a href='join.php?id=" . $_GET['id'] . "'><button>Join Group</button></a>";
                            }
                            if($author === $_SESSION['user']) {?>
                                <br/><br/>
                                <form method="post" enctype="multipart/form-data">
                                    <textarea required rows="5" cols="20" placeholder="Shout" name="shout"></textarea><br/>
                                    <input name="submit" type="submit" value="Change Group Shout">
                                    <small>max limit: 50 characters</small>
                                </form>
                            <?php
                            }
                            if ($row['currentgroup'] === $_GET['id'] || $author === $_SESSION['user']) {echo "<br/>";}
                        }
                    }
                ?>
                <?php
                    echo "Owner: <a href='profile.php?id=" . getID($author, $conn) . "'>" . $author . "</a><br/><br/>";
                    echo "Members:<br/>";
                    $stmt = $conn->prepare("SELECT * FROM `users` WHERE currentgroup = ?");
                    $stmt->bind_param("s", $_GET['id']);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    while($row = $result->fetch_assoc()) {
                        echo "<a href='profile.php?id=" . $row['id'] . "'>" . $row['username'] . "</a><br/>";
                    }
                ?>
            </div>
        </div>
    </body>
</html>