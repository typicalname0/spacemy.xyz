
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
            <?php
                if(@$_POST) {
                    if($_POST['password'] && $_POST['username']) {
                        if($_POST['password'] != $_POST['confirm'] || strlen($_POST['username']) > 21) {
                            echo("<small>passwords do not match up or username is too long.</small>");
                        } else {
                            $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
                            $stmt->bind_param("s", $_POST['username']);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            if($result->num_rows === 1) {
                                echo "<small>there's already a user with that same name!</small><br>";
                                $namecheck = 0;
                            } else {
                                $namecheck = 1;
                            }

                            $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
                            $stmt->bind_param("s", $_POST['email']);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            if($result->num_rows === 1) {
                                echo "<small>there's already a user with that same email!</small><br>";
                                $emailcheck = 0;
                            } else {
                                $emailcheck = 1;
                            }
        
                            $stmt->close();
        
                            if($namecheck == 1 && $emailcheck == 1) {
                                //TODO: add cloudflare ip thing 
                                $stmt = $conn->prepare("INSERT INTO `users` (`username`, `email`, `password`, `date`) VALUES (?, ?, ?, now())");
                                $stmt->bind_param("sss", $username, $email, $password);
                            
                                $email = htmlspecialchars(@$_POST['email']);
                                $username = htmlspecialchars(@$_POST['username']);
                                $password = password_hash(@$_POST['password'], PASSWORD_DEFAULT);
                                $stmt->execute();
                            
                                $stmt->close();
                                $conn->close();
                                session_set_cookie_params(69420000)
                                $_SESSION['user'] = htmlspecialchars($username);
                                header("Location: manage.php");
                            }
                        }
                    }
                }
            ?>
            <h1>Register!</h1>
            <div class="contactInfo">
                <div class="contactInfoTop">    
                    <center>Benifits</center>
                </div>
                - Make new friends!<br>
                - Talk to people!<br>
                - Over 50 members!
            </div>
            <br>
            <form action="" method="post">
                <input required placeholder="Username" type="text" name="username"><br>
                <input required placeholder="E-Mail" type="email" name="email">
                <hr>
                <input required placeholder="Password" type="password" name="password"><br>
                <input required placeholder="Confirm Password" type="password" name="confirm"><br><br>
                <input type="submit" value="Register">
            </form>
        </div>
    </body>
</html>