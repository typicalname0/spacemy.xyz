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
            <h1>Blogs [wip]</h1>
            <form action="/search.php" method="post" class="search">
                <input placeholder="Search for blogs..." size="59" type="text" name="query">
                <input type="hidden" name="queryfor" value="Blogs">
                <input type="submit" value="Search">
                <span><a href="newblog.php">make a new blog</a></span>
            </form>
            <hr>
            <?php
                $stmt = $conn->prepare("SELECT * FROM `blogs`");
                $stmt->execute();
                $result = $stmt->get_result();
                
                while($row = $result->fetch_assoc()) {
                    echo "<b>" . $row['title'] . "</b> - " . $row['author'] . "@" . $row['date'] . " <a style='float: right;' href='viewblog.php?id=" . $row['id'] . "'><small>[view]</small></a><hr>";
                }
            ?>
        </div>
    </body>
</html>