<?php
    require("func/conn.php");
    require("func/settings.php");
    requireLogin();

    // https://stackoverflow.com/questions/4356289/php-random-string-generator
    function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Invites - spacemy.xyz</title>
        <link rel="stylesheet" href="css/header.css">
        <link rel="stylesheet" href="css/base.css">
        <style type="text/css">
            table, th, td {border: 1px solid black;border-collapse: collapse;}
        </style>
        <?php
            if (@$_POST && $_POST['create'] === "1") {
                $stmt = $conn->prepare("SELECT `createdOn` FROM `invites` WHERE createdBy = ?");
                $id = getID($_SESSION['user'], $conn);
                $stmt->bind_param("i", $id);
                $stmt->execute();
                $result = $stmt->get_result();

                $waitPeriod = false;
                while($row = $result->fetch_assoc()) {
                    // dumb php time date stuff
                    $d = DateTime::createFromFormat('Y-m-d H:i:s', $row['createdOn']);
                    if ($d === false) {$err = "Fatal error.";goto skip;}
                    if (time()-86400 <= $d->getTimestamp()) {
                        $waitPeriod = true;
                    }
                }
                if ($waitPeriod) {$err = "Wait a while before making a new key.";goto skip;}

                $stmt = $conn->prepare("INSERT INTO `invites` (`invitekey`, `createdBy`, `createdOn`, `usedBy`) VALUES (?,?,current_timestamp(),NULL)");
                $key = generateRandomString(32);
                $stmt->bind_param("si", $key, $id);
                $stmt->execute();
            }
            skip:
        ?>
    </head>
    <body>
        <?php
            require("header.php");
            $stmt = $conn->prepare("SELECT * FROM `invites` WHERE createdBy = ?");
            $id = getID($_SESSION['user'], $conn);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
        ?>
        <div class="container">
            <h1>Invite keys</h1>
            <?php
                if (isset($err)) {echo "<span style='color:red;'>" . $err . "</span>";}
                echo "<table><tr><th>Invite key</th><th>Used by</th></tr>";
                while($row = $result->fetch_assoc()) {
                    if ($row["usedBy"]) {
                        $usedBy = "<a href='profile.php?id=" . $row["usedBy"] . "'>" . getName($row["usedBy"], $conn) . "</a>";
                    } else {
                        $usedBy = "Unused";
                    }
                    echo "<tr>";
                    echo "<td>" . $row["invitekey"] . "</td>";
                    echo "<td>" . $usedBy . "</td>";
                    echo "</tr>";
                }
                echo "</table>";
            ?><br/>
            <form method="post">
                <input type="hidden" name="create" value="1">
                <input type="submit" value="Create new key">
            </form>
        </div>
    </body>
</html>