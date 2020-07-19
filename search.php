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
            if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['query'])) 
            {
                $query = htmlspecialchars($_POST['query']); 
                $sqlquery = "%".$query."%";
                switch($_POST['queryfor'])
                {
                    case "Groups":
                        $stmt = $conn->prepare("SELECT id, name FROM `groups` WHERE name LIKE ?");
                        $queryfor = "Group";
                        break;
                    
                    case "Blogs":
                        $stmt = $conn->prepare("SELECT id, title FROM `blogs` WHERE title LIKE ?");
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
            <?php 
                while($row = $result->fetch_assoc())
                {
                    switch($_POST['queryfor'])
                    {
                        case "Groups":
                            echo "<a href='viewgroup.php?id=".$row['id']."'>".$row['name']."</a><br>";
                            break;
                        
                        case "Blogs":
                            echo "<a href='viewblog.php?id=".$row['id']."'>".$row['title']."</a><br>";
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