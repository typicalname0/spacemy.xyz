<!DOCTYPE html>
<html>
    <head>
        <?php
            require("require.php");
        ?>
    </head>
    <body>
        <?php
            require("../header.php");
        ?>
        <div class="container">
            <h1 style="margin: 0px;display: inline-block;">spacemyGames</h1> - <a href="upload.php">Upload a Game!</a><br>
            <h3 style="margin: 0px;">Hot New Games</h3>
            <br>
            <?php
                $stmt = $conn->prepare("SELECT * FROM `games`");
                $stmt->execute();
                $result = $stmt->get_result();

                while($row = $result->fetch_assoc()) { 
                    echo "<a href='view.php?id=" . $row['id'] . "'>" . $row['title'] . " by " . $row['author'] . "</a>
                    <small>[Uploaded at <b>" . $row['date'] . "</b> by <b>" . $row['author'] . "</b>]</small>
                    <br>" . $row['description'] . "<hr>";
                }
            ?>
        </div>
    </body>
</html>