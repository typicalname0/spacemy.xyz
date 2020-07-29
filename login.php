<?php
require("func/conn.php");
require("func/settings.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['password'] && $_POST['username']) {
    $stmt = $conn->prepare("SELECT password FROM `users` WHERE username=?");
    $stmt->bind_param("s", $_POST['username']);
    $stmt->execute();
    $result = $stmt->get_result();
    if (!mysqli_num_rows($result)) { {
            $error = "incorrect username or password";
            goto skip;
        }
    }

    $row = $result->fetch_assoc();
    $hash = $row['password'];

    if (!password_verify($_POST['password'], $hash)) {
        $error = "incorrect username or password";
        goto skip;
    }

    session_set_cookie_params(69420000);
    $_SESSION['user'] = htmlspecialchars($_POST["username"]);
    header("Location: /manage.php");
}
skip:
?>
<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/base.css">
</head>

<body>
    <?php require("header.php"); ?>
    <div class="container">
        <div class="left">
            <div class="info">Log in</div>
            <br>
            <?php if (isset($error)) {
                echo "<small style='color:red'>" . $error . "</small>";
            } ?>
            <form method="post">
                <input required placeholder="Username" type="text" name="username"><br>
                <input required placeholder="Password" type="password" name="password"><br><br>
                <input type="submit" value="Login">
            </form>
        </div>
        <div class="right">Don't have an NIGGER YOUR A NIGGER HARRY FUCK NIGGERS? <a href="/register.php">Register</a></div>
    </div>
</body>

</html>
