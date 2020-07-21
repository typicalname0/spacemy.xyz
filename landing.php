<?php
    require("func/conn.php");
    require("func/settings.php");
?>
<!DOCTYPE html>
<html>
    <head>
        <title>spacemy.xyz</title>
        <link rel="stylesheet" href="css/header.css">
        <link rel="stylesheet" href="css/base.css">
    </head>
    <body>
        <?php
            require("header.php");
        ?>
        <div class="container">
            <div class="left">
                <?php if(!isset($_SESSION['user'])){ ?>
                <div class="info">
                    Log in
                </div>
                <br>
                <form method="post" action="/login.php">
                    <input required placeholder="Username" type="text" name="username"><br>
                    <input required placeholder="Password" type="password" name="password"><br><br>
                    <input type="submit" value="Login"><span><small>   New user? <a href="/register.php">Register</a></small></span>
                </form>
                <br>
                <?php } ?>
                <div class="info">
                    Latest Blog Posts
                </div>
                <br>
                <?php
                    $result = $conn->query("SELECT id, title, author FROM blogs ORDER BY id DESC LIMIT 5");
                    while($row = $result->fetch_assoc()) 
                    {
                        echo "<a href='/blogs/view.php?id=".$row['id']."'>".$row['title']."</a> - by <a href='/profile.php?id=".getID($row['author'], $conn)."'>".$row['author']."</a><br><br>";
                    }
                ?>
                <hr>
                <a href="/blogs/">View more blog posts</a>
                <br>
                <br>
                <div class="topBarWithItemsThing">
                    <a href="/blogs/">Blogs</a> &nbsp;<a href="/groups/">Groups</a> &nbsp;<a href="/register.php">Register</a> &nbsp;<a href="/login.php">Login</a>
                </div>
                <br>
                THIS IS UNDER CONSTRUCTION!!
            </div>
            <div class="right">
                <div class="info">
                    Users
                </div>
                <br>
                <?php
                    //preparing a statement isn't necessary, you're not querying with php input
                    $result = $conn->query("SELECT id, username FROM `users`");
                    while($row = $result->fetch_assoc()) {
                        echo "<a href='/profile.php?id=" . $row['id'] . "'>" . $row['username'] . "</a><br>";
                    }
                ?>
            </div>
        </div>
    </body>
</html>
