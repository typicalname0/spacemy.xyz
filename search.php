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
            <?php if (@$_POST['query'] && $_POST['query'] !== "") { ?>
                <h1>Search results for <?php echo htmlspecialchars($_POST['query']); ?></h1>
                <h3>Users:</h3>
                <?php
                    $wc = "%" . htmlspecialchars($_POST['query']) . "%";
                    $stmt = $conn->prepare("SELECT * FROM `users` WHERE username LIKE ?");
                    $stmt->bind_param("s", $wc);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    
                    if ($result->num_rows !== 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<a href='/profiles.php?id=" . $row["id"] . "'>" . $row['username'] . "</a><br/>";
                        }
                    } else {
                        echo "<b>Nothing matched your search query.</b>";
                    }
                ?>
                <h3>Groups:</h3>
                <?php
                    $wc = "%" . htmlspecialchars($_POST['query']) . "%";
                    $stmt = $conn->prepare("SELECT * FROM `groups` WHERE name LIKE ?");
                    $stmt->bind_param("s", $wc);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    
                    if ($result->num_rows !== 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<a href='viewgroup.php?id=" . $row['id'] . "'>" . $row['name'] . "</a><br>";
                        }
                    } else {
                        echo "<b>Nothing matched your search query.</b>";
                    }
                ?>
                <h3>Blogs:</h3>
                <?php
                    $wc = "%" . htmlspecialchars($_POST['query']) . "%";
                    $stmt = $conn->prepare("SELECT * FROM `blogs` WHERE title LIKE ?");
                    $stmt->bind_param("s", $wc);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    
                    if ($result->num_rows !== 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<a href='viewblog.php?id=" . $row['id'] . "'>" . $row['title'] . "</a><br>";
                        }
                    } else {
                        echo "<b>Nothing matched your search query.</b>";
                    }
                ?>
            <?php } else {?>
                <h1>No search query submitted.</h1>
            <?php } ?>
        </div>
    </body>
</html>