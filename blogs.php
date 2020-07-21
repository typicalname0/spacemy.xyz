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
                if(isset($_GET['page'])){ $page = $_GET['page']; }
                else{ $page = 1; }
                if(!filter_var($page, FILTER_VALIDATE_INT)){ $page = 1; }

                $stmt = $conn->query("SELECT id FROM `blogs`");
                $numblogs = $stmt->num_rows;
                $pages = ceil($numblogs/20);
                $offset = ($page - 1)*20;

                $stmt = $conn->prepare("SELECT * FROM `blogs` LIMIT 20 OFFSET ?");
                $stmt->bind_param("i", $offset);
                $stmt->execute();
                $result = $stmt->get_result();

                $stmt->close();
        ?>
        <div class="container">
            <h1>Blogs [wip]</h1>
            <div style="text-align:center;">
                <a <?php if($page != 1){ echo 'href="?page='.($page-1).'"'; } ?>>&lt;&lt; Back</a>
                <?php echo "[ Page $page out of $pages ]"; ?>
                <a <?php if ($page < $pages) { echo 'href="?page='.($page+1).'"'; } ?>>Next &gt;&gt;</a>
            </div>
            <br>
            <form action="/search.php" method="post" class="search">
                <input placeholder="Search for blogs..." size="59" type="text" name="query">
                <input type="hidden" name="queryfor" value="Blogs">
                <input type="submit" value="Search">
                <span><a href="newblog.php">make a new blog</a></span>
            </form>
            <hr>
            <?php
                while($row = $result->fetch_assoc()) 
                {
                    echo "<b>" . $row['title'] . "</b> - " . $row['author'] . "@" . $row['date'] . " <a style='float: right;' href='viewblog.php?id=" . $row['id'] . "'><small>[view]</small></a><hr>";
                }
                if(!mysqli_num_rows($result)){ echo "<b>No blogs found</b>"; }
            ?>
            <br>
            <div style="text-align:center;">
                <a <?php if($page != 1){ echo 'href="?page='.($page-1).'"'; } ?>>&lt;&lt; Back</a>
                <?php echo "[ Page $page out of $pages ]"; ?>
                <a <?php if ($page < $pages) { echo 'href="?page='.($page+1).'"'; } ?>>Next &gt;&gt;</a>
            </div>
        </div>
    </body>
</html>