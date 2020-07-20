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
            if(!isset($_SESSION['user'])){ header("Location: /landing.php"); die(); }
            $stmt = $conn->prepare("SELECT * FROM `users` WHERE username = ?");
            $stmt->bind_param("s", $_SESSION['user']);
            $stmt->execute();
            $result = $stmt->get_result();
            if(!mysqli_num_rows($result)){ die("An unexpected error occured involving your account. <a href='/logout.php'>Logout</a>"); }
            $row = $result->fetch_assoc();
            
            $badges = strpos($row['ranks'], "dev");
            $id = $row['id'];
            $bio = $row['bio'];
            $interests = $row['interests'];
            $user = $row['username'];
            $status = $row['status'];
            $css = $row['css'];
            $pfp = $row['pfp'];
            $music = $row['music'];
            $group = $row['currentgroup'];
            $url = "https://".$_SERVER['HTTP_HOST']."/profile.php?id=".$id;

            $stmt->close();

            if($group !== "") { // let this serve as a reminder that Typical fucked up
                $stmt = $conn->prepare("SELECT * FROM `groups` WHERE id = ?");
                $stmt->bind_param("i", $group);
                $stmt->execute();

                $row = $stmt->get_result()->fetch_assoc();
                $groupname = $row['name'];
            } else {$groupname = "None";}
            

            if($badges !== false) {
                $badge = "<img src='badges/dev.png'>";
            } else {
                $badge = "";
            }
        ?>
        <style><?php echo $css; ?></style>
    </head>
    <body>
        <?php require("header.php");?>
        <div class="container">
            <button style="position:fixed;left:0;display:none;" id="show-welcome" onclick="document.getElementById('welcome').style.display = ''; document.getElementById('show-welcome').style.display = 'none';">Show Panel</button>
            <div class="left" style="position:fixed;left:0;width:15%;padding:10px;" id="welcome">
                Welcome, <?php echo $user; ?>! <button onclick="document.getElementById('welcome').style.display = 'none'; document.getElementById('show-welcome').style.display = '';">Hide Panel</button>
                <hr>
                <div class="info">
                    Latest Blog Posts
                </div>
                <br>
                <?php
                    $result = $conn->query("SELECT id, title, author FROM `blogs` ORDER BY id DESC LIMIT 5");
                    while($row = $result->fetch_assoc()) 
                    {
                        echo "<a href='/viewblog.php?id=".$row['id']."'>".$row['title']."</a> - by <a href='/profile.php?id=".getID($row['author'], $conn)."'>".$row['author']."</a><br><br>";
                    }
                ?>
                <a href="/blogs.php">[ View more blog posts ]</a>
                <br>
                <br>
                <hr>
                <div class="info">
                    Check these groups out
                </div>
                <br>
                <?php
                    $stmt = $conn->prepare("SELECT id, name, (SELECT COUNT(*) FROM `users` WHERE currentgroup = name) FROM `groups` WHERE NOT author = ? ORDER BY RAND() LIMIT 5");
                    $stmt->bind_param("s", $user);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    while($row = $result->fetch_assoc()) 
                    {
                        $memberCount = $row['(SELECT COUNT(*) FROM `users` WHERE currentgroup = name)']; //cant really do this any other way lol
                        if(!$memberCount){ $memberCount = "No"; }
                        echo "<a href='/viewgroup.php?id=".$row['id']."'>".$row['name']."</a> - ".$memberCount." member(s)<br><br>";
                    }
                ?>
                <a href="/groups.php">[ View more groups ]</a>
                <br>
                <br>
            </div>
            <div class="left">
                <div class="LeftHandUserInfo">
                    <br>
                    <br>
                    <h1 class='username' style='margin: 0px;'><?php echo $user; ?></h1>
                    <small class='status'><?php echo $status; ?></small>
                    <br>
                    <br>
                    <img class='pfp' width='235px;' src='/pfp/<?php echo $pfp; ?>'>
                    <hr>
                    <audio controls autoplay>
                        <source src="/music/<?php echo $music; ?>" type="audio/ogg">
                    </audio>
                    <br>
                    <br>
                    <div class="contactInfo" id="group">
                        <div style="text-align:center;">
                            Current Group: <b><a href="/viewgroup.php?id=<?php echo $group;?>"><?php echo $groupname; ?></a></b>
                            <br>
                            <small><a href="<?php echo $url; ?>"><?php echo $url; ?></a></small>
                        </div>
                    </div><br>
                    <div class="contactInfo" id="badges">
                        <div class="contactInfoTop" style="text-align:center;">Badges</div>
                        <?php echo $badge; ?>
                    </div>
                    <br>
                    <br>
                    <div class="contactInfo" id="blogs">
                        <div class="contactInfoTop" style="text-align:center;">Blogs</div>
                    <?php
                        //specify only the columns you need to conserve performace because you dont need to fetch the entire blog post body for the profile
                        $stmt = $conn->prepare("SELECT id, title FROM `blogs` WHERE author = ?");
                        $stmt->bind_param("s", $user);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        while($row = $result->fetch_assoc()) { ?>
                        <a href='/viewblog.php?id=<?php echo $row['id']; ?>'><?php echo $row['title']; ?></a>
                        <br>
                    <?php } ?>
                    </div>
                    <br>
                    <div class="contactInfo" id="friends">
                        <div class="contactInfoTop" style="text-align: center;">Friends</div>
                        <?php
                            $stmt = $conn->prepare("SELECT * FROM `friends` WHERE (sender = ? OR reciever = ?) AND status = 'ACCEPTED'");
                            $stmt->bind_param("ss", $user, $user);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            
                            while($row = $result->fetch_assoc()) 
                            { 
                                if($row['sender'] == $user){ $friend = $row['reciever']; } else{ $friend = $row['sender']; }
                                echo "<a href='/profile.php?id=".getID($friend, $conn)."'><img width='40px;' src='pfp/".getPFP($friend, $conn)."'></a>";
                            }
                        ?>
                    </div>
                </div>
            </div>
            <div class="right">
                <br>
                <br>
                <div class="RightHandUserInfo">
                    <div id="interests">
                        <div class="info" style="text-align: center;">Interests</div>
                        <?php echo $interests; ?>
                    </div>
                        <br>
                        <br>
                    <div id="bio">
                        <div class="info" style="text-align: center;">Bio</div>
                        <?php echo $bio; ?>
                    </div>
                    <br>
                    <br>
                    <div id="comments">
                        <div class="info" style="text-align: center;">Comments</div>
                        <form action="<?php echo $url; ?>" method="post" enctype="multipart/form-data">
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
                            
                            while($row = $result->fetch_assoc()) { ?>
                                <div class='commentRight' style='display: grid; grid-template-columns: 75% auto; padding:5px;'>
                                    <div style="word-wrap: break-word;">
                                        <small><?php echo $row['date']; ?> <a href="deletecomment.php?id=<?php echo $row['id']; ?>">[delete]</a></small>
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
                    </div>
                </div>
                <br>
                <div class="usersList">
                    <div class="info">
                        Users
                    </div>
                    <div class="usersListInner">
                        <?php
                            $stmt = $conn->prepare("SELECT * FROM `users`");
                            $stmt->execute();
                            $result = $stmt->get_result();
                            while($row = $result->fetch_assoc()) { ?>
                            <a href='profile.php?id=<?php echo $row['id']; ?>'><?php echo $row['username']; ?></a><br>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
