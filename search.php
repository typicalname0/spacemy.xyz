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
            if($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['query'])) 
            {
                $pgOffset = 0
                if ($isset($_GET["pg"])){
                    $pgOffset = -20+(intval($pg)*20)
                }
                $query = htmlspecialchars($_GET['query']); 
                $sqlquery = "%".$query."%";
                switch($_GET['queryfor'])
                {
                    case "Groups":
                        $stmt1 = $conn->prepare("SELECT id, name, description, author, date, (SELECT COUNT(*) FROM `users` WHERE currentgroup = name) FROM `groups` WHERE name LIKE ?");
                        $stmt = $conn->prepare("SELECT id, name, description, author, date, (SELECT COUNT(*) FROM `users` WHERE currentgroup = name) FROM `groups` WHERE name LIKE ? LIMIT 20 OFFSET $pgOffset");
                        $queryfor = "Group";
                        break;
                    
                    case "Blogs":
                        $stmt1 = $conn->prepare("SELECT id, title, date, author FROM `blogs` WHERE title LIKE ?");
                        $stmt = $conn->prepare("SELECT id, title, date, author FROM `blogs` WHERE title LIKE ? LIMIT 20 OFFSET $pgOffset");
                        $queryfor = "Blog";
                        break;

                    default:
                        $stmt1 = $conn->prepare("SELECT id, username FROM `users` WHERE username LIKE ?");
                        $stmt = $conn->prepare("SELECT id, username FROM `users` WHERE username LIKE ? LIMIT 20 OFFSET $pgOffset");
                        $queryfor = "User";
                        break;
                }

                $stmt->bind_param("s", $sqlquery);
                $stmt1->bind_param("s", $sqlquery);
                $stmt->execute();
                $stmt1->execute();
                $result = $stmt->get_result();
                $pgs = mysqli_num_rows($stmt1->execute());
            } else { header("Location: /"); }
        ?>
        <div class="container">
            <h1><?php echo $queryfor ?> results for <u><?php echo $query; ?></u> (<?php echo $result->num_rows; ?>)</h1>
            <hr>
            <?php
                for ($i=1; $i<$pgs or $i<9; $i++) {
                    echo "<a href='search.php?queryfor=$queryfor&query=$query&pg=$i'>$i</a>\" \"";
                }
                if ($i !== $pgs){
                    echo "<a href='search.php?queryfor=$queryfor&query=$query&pg=$pgs'> ..$pgs</a>";
                }
                echo "<br>";
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
