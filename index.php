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
            $stmt = $conn->prepare("SELECT * FROM `users` WHERE username = ?");
            $stmt->bind_param("s", $_SESSION['user']);
            $stmt->execute();
            $result = $stmt->get_result();
            
            while($row = $result->fetch_assoc()) {
                echo "<style>" . $row['css'] . "</style>";
            }
        ?>
    </head>
    <body>
        <?php
            require("header.php");
        ?>
        <div class="container">
            <div class="left">
                <?php 
                    if(!!!isset($_SESSION['user'])) {
                        header("Location: landing.php");
                        die();
                    }
                ?>
                <div class="LeftHandUserInfo">
                    <?php
                        $stmt = $conn->prepare("SELECT * FROM `users` WHERE username = ?");
                        $stmt->bind_param("s", $_SESSION['user']);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        
                        while($row = $result->fetch_assoc()) {
                            $bio = $row['bio'];
                            $interests = $row['interests'];
                            $user = $row['username'];
                            $id = $row['id'];
                            $status = $row['status'];

                            echo "<br><br><h1 style='margin: 0px;'>" . $row['username'] . "</h1><small>" . $status . "</small><br><br>";
                            echo "<img width='235px;' src='pfp/" . $row['pfp'] . "'><hr>";

                            echo ' 
                            <audio controls autoplay>
                                <source src="music/' . $row['music'] . '" type="audio/ogg">
                            </audio><br><br>';
                            echo '
                        <div class="contactInfo">
                            <div class="contactInfoTop">    
                                <center>Contact</center>
                            </div>
                        ';
                            echo "<a class='contactbuttons' href='add.php?id=" . $id . "'>Friend User</a>";
                            echo "<a style='float: right'class='contactbuttons' href='#?id=" . $id . "'>Report User</a><br><br>";
                            echo '<center>Current Group: <b>' . $row['currentgroup'] . "</b><br>";
                            echo '<small><a href="https://spacemy.xyz/profile.php?id=' . $id . '">' . 'https://spacemy.xyz/profile.php?id=' . $id . '</a></small></center></div>';

                        }
                        
                        echo '<br><div class="contactInfo">
                            <div class="contactInfoTop">    
                                <center>Blogs</center>
                            </div>';
                            $stmt = $conn->prepare("SELECT * FROM `blogs` WHERE author = ?");
                            $stmt->bind_param("s", $user);
                            $stmt->execute();
                            $result = $stmt->get_result();

                            while($row = $result->fetch_assoc()) {
                                echo "<a href='viewblog.php?id=" . $row['id'] . "'>" . $row['title'] . "</a><br>";
                            }
                        echo '</div><br>';

                        echo '
                        <div class="contactInfo">
                            <div class="contactInfoTop">    
                                <center>Friends</center>
                            </div>
                        ';

                        $stmt = $conn->prepare("SELECT * FROM `friends` WHERE reciever = ? AND status = 'ACCEPTED'");
                        $stmt->bind_param("s", $user);
                        $stmt->execute();
                        $result = $stmt->get_result();
                    
                        while($row = $result->fetch_assoc()) {
                            echo "<a href='profile.php?id=" . getID($row['sender'] , $conn) . "'><img width='40px;' src='pfp/" . getPFP($row['sender'], $conn) . "'></a>";
                        }

                        $stmt = $conn->prepare("SELECT * FROM `friends` WHERE sender = ? AND status = 'ACCEPTED'");
                        $stmt->bind_param("s", $user);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        
                        echo "<br>";
                        while($row = $result->fetch_assoc()) {
                            echo "<a href='profile.php?id=" . getID($row['reciever'] , $conn) . "'><img width='40px;' src='pfp/" . getPFP($row['reciever'], $conn) . "'></a>";
                        }


                        echo '</div>';

                        if(@$_POST["comment"]) {
                            $stmt = $conn->prepare("INSERT INTO `comments` (toid, author, text, date) VALUES (?, ?, ?, now())");
                            $stmt->bind_param("sss", $id, $_SESSION['user'], $text);
                        
                            $unprocessedText = replaceBBcodes($_POST['comment']);
                            $text = str_replace(PHP_EOL, "<br>", $unprocessedText);
                            $stmt->execute();
                        
                            $stmt->close();
                            
                        }
                    ?>
                </div>
            </div>
            <div class="right">
                <br><br>
                <div class="RightHandUserInfo">
                    <div class="info">
                        <center>Interests</center>
                    </div>
                    <?php  echo $interests . "<br><br>"; ?>
                    <div class="info">
                        <center>Bio</center>
                    </div>
                    <?php
                        echo $bio . "<br><br>";
                    ?>
                    <div class="info">
                        <center>Comments</center>
                    </div>
                    <form method="post" enctype="multipart/form-data">
                        
                    <textarea required cols="43" placeholder="Comment" name="comment"></textarea><br>
                        <input name="commentsubmit" type="submit" value="Post"> <small>max limit: 500 characters | bbcode supported</small>
                    </form>
                    <hr>
                    <div class="commentsList">
                        <?php
                            $stmt = $conn->prepare("SELECT * FROM `comments` WHERE toid = ? ORDER BY id DESC");
                            $stmt->bind_param("s", $id);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            
                            while($row = $result->fetch_assoc()) {
                                echo "<div class='commentRight'>";
                                echo "  <small>" . $row['date'] . " <a href='deletecomment.php?id=" . $row['id'] . "'>[delete]</a></small><br>" . $row['text'];
                                echo "  <a style='float: right;' href='profile.php?id=" . getID($row['author'], $conn) . "'>" . $row['author'] . "</a> <br>";
                                echo "  <img class='commentPictures' style='float: right;' height='80px;'width='80px;'src='pfp/" . getPFP($row['author'], $conn) . "'><br><br><br><br><br>";
                                echo "</div>";
                            }
                        ?>
                    </div>
                </div>
                <br>
                <div class="usersList">
                    <div class="info">
                        Users
                    </div>
                    <br>
                    <?php
                        $stmt = $conn->prepare("SELECT * FROM `users`");
                        $stmt->execute();
                        $result = $stmt->get_result();
                        
                        while($row = $result->fetch_assoc()) {
                            echo "<a href='profile.php?id=" . $row['id'] . "'>" . $row['username'] . "</a><br>";
                        }
                    ?>
                </div>
            </div>
        </div>
    </body>
</html>