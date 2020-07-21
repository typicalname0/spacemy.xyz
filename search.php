<?php
    require("func/conn.php");
    require("func/settings.php");
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Search results for <?php echo $_GET['query'];?> - spacemy.xyz</title>
        <link rel="stylesheet" href="css/header.css">
        <link rel="stylesheet" href="css/base.css">
    </head>
    <body>
        <?php
            require("header.php");
            if(isset($_GET['query'])) 
            {
                $query = htmlspecialchars($_GET['query']); 
                $sqlquery = "%".$query."%";
                switch($_GET['queryfor'])
                {
                    case "Groups":
                        $stmt = $conn->prepare("SELECT id, name, description, author, date, (SELECT COUNT(*) FROM `users` WHERE currentgroup = name) FROM `groups` WHERE name LIKE ?");
                        $queryfor = "Group";
                        break;
                    
                    case "Blogs":
                        $stmt = $conn->prepare("SELECT id, title, date, author FROM `blogs` WHERE title LIKE ?");
                        $queryfor = "Blog";
                        break;

                    default:
                        $stmt = $conn->prepare("SELECT id, username FROM `users` WHERE username LIKE ?");
                        $queryfor = "User";
                        break;
                }

                $stmt->bind_param("s", $sqlquery);
                $stmt->execute();
                $result = $stmt->get_result();
            } else { header("Location: /"); }
        ?>
        <div class="container">
            <h1><?php echo $queryfor ?> results for <u><?php echo $query; ?></u> (<?php echo $result->num_rows; ?>)</h1>
            <hr>
            <?php 
                while($row = $result->fetch_assoc())
                {
                    switch($_GET['queryfor'])
                    {
                        case "Groups": 
                            $memberCount = $row['(SELECT COUNT(*) FROM `users` WHERE currentgroup = name)'];
                            if(!$memberCount){ $memberCount = "No"; }
                    ?>
                        <small><a href='viewgroup.php?id=<?php echo $row['id']; ?>'>[view group]</a></small> 
                        <b><?php echo $row['name']; ?></b> - <?php echo $row['description']; ?>
                        <a style='float: right;' href='joingroup.php?id=<?php echo $row['id']; ?>'><button>Join Group</button></a>
                        <br>
                        <small>created by <a href="/profile?id=<?php echo getID($row['author'], $conn); ?>"><?php echo $row['author']; ?></a> @ <?php echo substr($row['date'], 0, -3); ?> &bull; <?php echo $memberCount; ?> member(s)</small>
                        <hr>
                    <?php   break;
                        
                        case "Blogs":
                            echo "<b>".$row['title']."</b> - ".$row['author']."@".$row['date']." <a style='float: right;' href='viewblog.php?id=".$row['id']."'><small>[view]</small></a><hr>";
                            break;

                        default:
                            echo "<a href='/profile.php?id=".$row["id"]."'>".$row['username']."</a><br/>";
                            break;
                    }
                }
                if (!$result->num_rows) { echo "<b>No results matched your search query</a>"; }
            ?>
        </div>
    </body>
</html>