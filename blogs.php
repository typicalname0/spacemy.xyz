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
            <form action="/search.php" method="get" class="search">
                <input placeholder="Search for blogs..." size="59" type="text" name="query">
                <input type="hidden" name="queryfor" value="Blogs">
                <input type="submit" value="Search">
                <span><a href="newblog.php">make a new blog</a></span>
            </form>
            <hr>
            <?php
                $stmt1 = $conn->prepare("SELECT COUNT(*) FROM `blogs`");
                $stmt1->execute();
                $results = $stmt1->get_result();
                $pgs = ceil($results->fetch_assoc()["COUNT(*)"] / 20);
                $pgOffset = 0;
                if (isset($_GET["pg"])) {
                    $pgOffset = -20 + (intval($_GET["pg"]) * 20);
                }
                $stmt = $conn->prepare("SELECT * FROM `blogs` LIMIT 20 OFFSET $pgOffset");
                $stmt->execute();
                $result = $stmt->get_result();
                for ($i=1; $i < $pgs && $i < 9; $i++) {
                    echo "<a href='blogs.php?pg=$i'>$i</a> &bull;";
                }
                if ($i !== $pgs) {
                    if ($i === 1) {
                        echo "<a href='blogs.php?pg=$pgs'>$pgs</a>";
                    } else {
                        echo "<a href='blogs.php?pg=$pgs'>..$pgs</a>";
                    }
                }
                echo "<br><br>";
                while($row = $result->fetch_assoc()) {
                    echo "<b>" . $row['title'] . "</b> - " . $row['author'] . "@" . $row['date'] . " <a style='float: right;' href='viewblog.php?id=" . $row['id'] . "'><small>[view]</small></a><hr>";
                }
            ?>
        </div>
    </body>
</html>
