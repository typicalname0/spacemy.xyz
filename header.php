<div class="header">
    <div class="headerTop">
        <a href="index.php"><b>spacemy.xyz</b></a>
        <form action="/search.php" method="post" class="search" style="display:inline;">
            <input placeholder="Search" size="50" type="text" name="query">
            <input type="submit" value="Search">
        </form>
    </div>
    <div class="headerBottom">
        <?php
            if(isset($_SESSION['user'])) {
                echo ' <small><a href="index.php">Your Account</a> &bull; <a href="groups.php">Groups</a> &bull; <a href="blogs.php">Blogs</a> &bull; <a href="jukebox.php">Jukebox</a> &bull; <a href="videos">Videos</a></small>';
            } else {
                echo ' <small><a href="register.php">Register</a> &bull; <a href="login.php">Login</a> &bull; <a href="groups.php">Groups</a> &bull; <a href="blogs.php">Blogs</a> &bull; <a href="jukebox.php">Jukebox</a> &bull; <a href="videos">Videos</a></small>';
            }
        ?>
        <?php
            if(isset($_SESSION['user'])) {
                $stmt = $conn->prepare("SELECT * FROM `friends` WHERE reciever = ? AND status='PENDING'");
                $stmt->bind_param("s", $_SESSION['user']);
                $stmt->execute();
                $result = $stmt->get_result();
                
                $pendingFriendRequests = 0;
                while($row = $result->fetch_assoc()) {
                    $pendingFriendRequests++;
                }

                echo "<small><span style='float: right;'>";

                if($pendingFriendRequests != 0) {
                    echo "<span style='color: yellow; text-decoration: none;'><a href='friends.php'>Pending Friend Requests</a></span> &nbsp; ";
                }

                echo "<a href='friends.php'>Friends</a> - <a href='manage.php'>Manage Account</a> - " . $_SESSION['user'] . "";
                echo "</span></small>";
            } else {
                echo "<small><span style='float: right;'>Not logged in</span></small>";
            }
        ?>
    </div>
</div>
<div style="border: 1px solid black;">
    <center>join the discord - https://discord.gg/jUbyMh9</center>
</div>
