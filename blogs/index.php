<?php
    require("../func/conn.php");
    require("../func/settings.php");
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Blogs - spacemy.xyz</title>
        <link rel="stylesheet" href="/css/header.css">
        <link rel="stylesheet" href="/css/base.css">
    </head>
    <body>
        <?php
            require("../header.php");
            if(isset($_GET['page'])){ $page = $_GET['page']; }
            else{ $page = 1; }
            if(!filter_var($page, FILTER_VALIDATE_INT)){ $page = 1; }

            $stmt = $conn->query("SELECT id FROM `blogs`");
            $numblogs = $stmt->num_rows;
            $pages = ceil($numblogs/20);
            $offset = ($page - 1)*20;

            if($page == 1){ $first_disabled = true; } else { $first_disabled = false; }
            if($page < 2){ $back_disabled = true; } else { $back_disabled = false; }
            if($page >= $pages){ $next_disabled = true; } else { $next_disabled = false; }
            if($page == $pages){ $last_disabled = true; } else { $last_disabled = false; }

            $stmt = $conn->prepare("SELECT * FROM `blogs` ORDER BY id DESC LIMIT 20 OFFSET ?");
            $stmt->bind_param("i", $offset);
            $stmt->execute();
            $result = $stmt->get_result();

            $stmt->close();
        ?>
        <div class="container">
            <h1>Blogs [wip]</h1>
            <div style="text-align:center;">
                <a href="?page=1"><button <?php if($first_disabled){ echo'disabled="disbled"'; }?>>&lt;&lt; First</button></a>
                <a href="?page=<?php echo $page-1; ?>"><button <?php if($back_disabled){ echo'disabled="disbled"'; }?>>&lt; Back</button></a>
                <?php echo "[ Page $page of $pages ]"; ?>
                <a href="?page=<?php echo $page+1; ?>"><button <?php if($next_disabled){ echo'disabled="disbled"'; }?>>Next &gt;</button></a>
                <a href="?page=<?php echo $pages; ?>"><button <?php if($last_disabled){ echo'disabled="disbled"'; }?>>Last &gt;&gt;</button></a>
            </div>
            <br>
            <form action="/search.php" method="post" class="search">
                <input placeholder="Search for blogs..." size="59" type="text" name="query">
                <input type="hidden" name="queryfor" value="Blogs">
                <input type="submit" value="Search">
                <span><a href="/blogs/new.php">make a new blog</a></span>
            </form>
            <hr>
            <?php
                while($row = $result->fetch_assoc()) 
                {
                    $title = $row['title'];
                    $id = $row['id'];
                    $author = $row['author'];
                    $authorid = getID($author, $conn);
                    $date = substr($row['date'], 0, -3);
                    echo "<b>$title</b> - by <a href='/profile.php?id=$authorid'>$author</a> <span style='float:right'>$date | <a href='view.php?id=$id'><small>[view]</small></a></span><hr>";
                }
                if(!mysqli_num_rows($result)){ echo "<b>No blogs found</b>"; }
            ?>
            <br>
            <div style="text-align:center;">
                <a href="?page=1"><button <?php if($first_disabled){ echo'disabled="disbled"'; }?>>&lt;&lt; First</button></a>
                <a href="?page=<?php echo $page-1; ?>"><button <?php if($back_disabled){ echo'disabled="disbled"'; }?>>&lt; Back</button></a>
                <?php echo "[ Page $page of $pages ]"; ?>
                <a href="?page=<?php echo $page+1; ?>"><button <?php if($next_disabled){ echo'disabled="disbled"'; }?>>Next &gt;</button></a>
                <a href="?page=<?php echo $pages; ?>"><button <?php if($last_disabled){ echo'disabled="disbled"'; }?>>Last &gt;&gt;</button></a>
            </div>
        </div>
    </body>
</html>