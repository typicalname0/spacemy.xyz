<?php
    require("func/conn.php");
    require("func/settings.php");
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Jukebox - spacemy.xyz</title>
        <link rel="stylesheet" href="css/header.css">
        <link rel="stylesheet" href="css/base.css">
    </head>
    <body>
        <?php
            require("header.php");
        ?>
        <div class="container">
            <?php
                if(isset($_GET['id'])) {
                    $stmt = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
                    $stmt->bind_param("i", $_GET['id']);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    if(!mysqli_num_rows($result)){ header("Location: /jukebox.php"); die(); }
                    
                    while($row = $result->fetch_assoc()) {
                        echo '<h1 style="margin: 0px;display: inline-block;">' . $row['username'] . '</h1> - ' . $row['music'] . '<br>
                        <audio style="width: 640px;" controls autoplay>
                            <source src="music/' . $row['music'] . '" type="audio/ogg">
                        </audio><hr>';
                    }
                } 

                $stmt = $conn->prepare("SELECT * FROM `users`");
                $stmt->execute();
                $result = $stmt->get_result();
                
                while($row = $result->fetch_assoc()) {
                    echo '<a href="?id=' . $row['id'] . '">' . $row['username'] . '</a><br>';
                }

            ?>
        </div>
    </body>
</html>