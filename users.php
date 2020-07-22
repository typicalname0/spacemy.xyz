<?php
    require("func/conn.php");
    require("func/settings.php");
?>
<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="css/header.css">
        <link rel="stylesheet" href="css/base.css">
        <style>
            .usersGrid {
                display: grid;
                grid-template-columns: 126px 126px 126px 126px 126px;
                padding: 5px;
            }

            .usersGridItem {
                background-color: #f93;
                border: 1px solid black;
                padding: 10px;
            }
        </style>
    </head>
    <body>
        <?php require("header.php"); ?>
        <div class="container">
            <div class="usersList">
                <div class="info">
                    Users
                </div>
                <br>
                <div class="usersGrid">
                    <?php
                        $stmt = $conn->prepare("SELECT * FROM `users`");
                        $stmt->execute();
                        $result = $stmt->get_result();
                        while($row = $result->fetch_assoc()) { ?>
                            <div class="usersGridItem"><img style="width: 100px;height: 100px;" src="pfp/<?php echo getPFP($row['username'], $conn);?>"><br>
                            <a href="profile.php?id=<?php echo getID($row['username'], $conn); ?>"><?php echo $row['username']; ?></a></div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </body>
</html>
