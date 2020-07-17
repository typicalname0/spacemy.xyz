<?php
    require("func/conn.php");
    require("func/settings.php");
?>
<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="css/header.css">
        <link rel="stylesheet" href="css/base.css">
    </head>
    <body>
        <?php
            require("header.php");
        ?>
        <div class="container">
            <div class="left">
                <div class="topBarWithItemsThing">
                    <a href="blogs.php">Blogs</a> &nbsp;<a href="groups.php">Groups</a> &nbsp;<a href="register.php">Register</a> &nbsp;<a href="login.php">Login</a>
                </div>
                <div class="info">
                    Log in
                </div>
                <br>
                <form method="post" action="/login.php">
                    <input required placeholder="Username" type="text" name="username"><br>
                    <input required placeholder="Password" type="password" name="password"><br><br>
                    <input type="submit" value="Login">
                </form>
                <br>
                <div class="info">
                    Latest Blog Posts
                </div>
                <br>
                <?php
                    $result = $conn->query("SELECT id, title, date FROM blogs LIMIT 5");
                    while($row = $result->fetch_assoc()) 
                    {
                        //substring here is used to remove the seconds from the date cause its not really necessary
                        echo "<a href='/viewblog.php?id=".$row['id']."'>".$row['title']."</a> - ".substr($row['date'], 0, -3)."<br>";
                    }
                ?>
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
                        echo "<a href='profile.php?id=" . $row['id'] . "'>" . $row['username'] . "</a><br>";
                    }
                ?>
            </div>
        </div>
    </body>
</html>