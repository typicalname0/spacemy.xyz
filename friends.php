<?php
    require("func/conn.php");
    require("func/settings.php");
    requireLogin();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Friends - spacemy.xyz</title>
        <link rel="stylesheet" href="css/header.css">
        <link rel="stylesheet" href="css/base.css">
    </head>
    <body>
        <?php
            require("header.php");
        ?>
        <div class="container">
            <div class="contactInfo">
                <div class="contactInfoTop">    
                    <center>Pending Friend Requests</center>
                </div>
            </div>
            <div class="friendsGrid">
                <?php
                    $stmt = $conn->prepare("SELECT * FROM `friends` WHERE reciever = ? AND status='PENDING'");
                    $stmt->bind_param("s", $_SESSION['user']);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    $id = 0;
                    while($row = $result->fetch_assoc()) {
                        echo "<div class='friendsGridItem'><a href='profile.php?id=" . getID($row['sender'], $conn) . "'><center><b>" . $row['sender'] . "</b></center><br><img width='125px' height='125px' src='pfp/" . getPFP($row['sender'], $conn) . "'></a><br>
                        <a href='accept.php?id=" . $row['id'] . "'><button>Accept</button></a> <a href='revoke.php?id=" . $row['id'] . "'><button>Revoke</button></a></div>";
                        $id++;
                    }

                    echo "</div>";
                    if($id == 0) {
                        echo "You have no pending friend requests.";
                    } else {
                        echo "<b>" . $id . "</b> pending friend requests.";
                    }
                ?>
            <!--</div>-->

            <div class="contactInfo">
                <div class="contactInfoTop">    
                    <center>Sent Friend Requests</center>
                </div>
            </div>
            <div class="friendsGrid">
                <?php
                    $stmt = $conn->prepare("SELECT * FROM `friends` WHERE sender = ? AND status='PENDING'");
                    $stmt->bind_param("s", $_SESSION['user']);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    $id = 0;
                    while($row = $result->fetch_assoc()) {
                        echo "<div class='friendsGridItem'><a href='profile.php?id=" . getID($row['reciever'], $conn) . "'><center><b>" . $row['reciever'] . "</b></center><br><img width='125px'src='pfp/" . getPFP($row['reciever'], $conn) . "'></a><br>
                        </div>";
                        $id++;
                    }

                    echo "</div>";
                ?>
            <hr>
            <div class="contactInfo">
                <div class="contactInfoTop">    
                    <center>Friends</center>
                </div>
            </div>
            <div class="friendsGrid">
                <?php
                    $stmt = $conn->prepare("SELECT * FROM `friends` WHERE reciever = ? AND status='ACCEPTED'");
                    $stmt->bind_param("s", $_SESSION['user']);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    while($row = $result->fetch_assoc()) {
                        echo "<div class='friendsGridItem'><a href='profile.php?id=" . getID($row['sender'], $conn) . "'><center><b>" . $row['sender'] . "</b></center><br><img width='90px'src='pfp/" . getPFP($row['sender'], $conn) . "'></a><br>
                        <a href='remove.php?id=" . $row['id'] . "'><button>Remove friend</button></a></div>";
                    }

                    $stmt = $conn->prepare("SELECT * FROM `friends` WHERE sender = ? AND status='ACCEPTED'");
                    $stmt->bind_param("s", $_SESSION['user']);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    while($row = $result->fetch_assoc()) {
                        echo "<div class='friendsGridItem'><a href='profile.php?id=" . getID($row['reciever'], $conn) . "'><center><b>" . $row['reciever'] . "</b></center><br><img width='90px'src='pfp/" . getPFP($row['reciever'], $conn) . "'></a><br>
                        <a href='remove.php?id=" . $row['id'] . "'><button>Remove friend</button></a></div>";
                    }


                    echo "</div>";
                ?>
            <!--</div>-->
        </div>
    </body>
</html>