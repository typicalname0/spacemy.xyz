<?php
    require("../func/conn.php");
    require("../func/settings.php");
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Groups - spacemy.xyz</title>
        <link rel="stylesheet" href="/css/header.css">
        <link rel="stylesheet" href="/css/base.css">
    </head>
    <body>
        <?php
            require("../header.php");
        ?>
        <div class="container">
            <h1>Groups [wip]</h1>
            <form action="/search.php" method="post" class="search">
                <input placeholder="Search for groups..." size="57" type="text" name="query">
                <input type="hidden" name="queryfor" value="Groups">
                <input type="submit" value="Search">
                <span><a href="new.php">make a new group</a></span>
            </form>
            <hr>
            <?php
                $stmt = $conn->prepare("SELECT id, description, author, date, name FROM `groups`");
                $stmt->execute();
                $result = $stmt->get_result();
                $stmt->close();
                
                while($row = $result->fetch_assoc()) 
                {
                    $stmt = $conn->prepare("SELECT COUNT(*) FROM `users` WHERE currentgroup = ?");
                    $stmt->bind_param("s", $row['id']);
                    $stmt->execute();
                    $memberCount = $stmt->get_result()->fetch_assoc()['COUNT(*)'];;
                    if(!$memberCount){ $memberCount = "No"; }
            ?>
            <small><a href='view.php?id=<?php echo $row['id']; ?>'>[view group]</a></small> 
            <b><?php echo $row['name']; ?></b> - <?php echo $row['description']; ?>
            <a style='float: right;' href='join.php?id=<?php echo $row['id']; ?>'><button>Join Group</button></a>
            <br>
            <small>created by <a href="/profile.php?id=<?php echo getID($row['author'], $conn); ?>"><?php echo $row['author']; ?></a> @ <?php echo substr($row['date'], 0, -3); ?> &bull; <?php echo $memberCount; ?> member(s)</small>
            <hr>
            <?php } ?>
        </div>
    </body>
</html>
