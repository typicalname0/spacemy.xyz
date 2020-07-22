<div class="header">
    <div class="headerTop">
        <a href="/"><b>spacemy.xyz</b></a>
        <form action="/search.php" method="get" class="search">
            <input placeholder="Search" type="text" name="query">
            <select name="queryfor">
                <option value="Users">Users</option>
                <option value="Groups">Groups</option>
                <option value="Blogs">Blogs</option>
            </select>
            <input type="submit" value="Search">
        </form>
    </div>
    <div class="headerBottom">
        <small>
            <?php
                if(isset($_SESSION['user'])) {
                    echo '<a href="/">Your Account</a> &bull; ';
                } else {
                    echo '<a href="/register.php">Register</a> &bull; <a href="/login.php">Login</a> &bull; ';
                }
            ?>
            <a href="/groups">Groups</a> &bull; <a href="/blogs">Blogs</a> &bull; <a href="/jukebox.php">Jukebox</a> &bull; <a href="/videos">Videos</a> &bull; <a href="random.php">Random</a> &bull; <a href="users.php">Users</a>
        </small>
        <small><span style="float:right">
            <?php
                if(isset($_SESSION['user'])) 
                {
                    $stmt = $conn->prepare("SELECT * FROM `friends` WHERE reciever = ? AND status='PENDING'");
                    $stmt->bind_param("s", $_SESSION['user']);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    
                    $pendingFriendRequests = 0;
                    while($row = $result->fetch_assoc()) { $pendingFriendRequests++; }

                    if($pendingFriendRequests) 
                    {
                        echo "<span style='color: yellow; text-decoration: none;'><a href='/friends.php'>".$pendingFriendRequests." pending friend request(s)</a></span> - ";
                    }

                    echo "<a href='/friends.php'>Friends</a> - <a href='/manage.php'>Manage Account</a> - <a href='/logout.php'>Log out</a> - " . $_SESSION['user'] . "";
                } else { echo "Not logged in. <a href='/login.php'>Log in</a>"; }
            ?>
        </span></small>
    </div>
</div>
<div style="border: 1px solid black;text-align: center;">
    join the discord - https://discord.gg/T9khjPX
</div>
