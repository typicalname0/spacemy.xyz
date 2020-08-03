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
            <h2>Upload a Game</h2>
            <?php
                if(@$_POST['submit']) {
                    if(isset($_SESSION['user'])) {
                        $target_dir = "gamefiles/";
                        $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
                        $uploadOk = 1;
                        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    
                        if (file_exists($target_file)) {
                            echo 'file with the same name already exists<hr>';
                            $uploadOk = 0;
                        }
                        if($imageFileType != "swf") {
                            echo 'unsupported file type. must be swf<hr>';
                            $uploadOk = 0;
                        }
                        if ($uploadOk == 0) { } else {
                            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                                $stmt = $conn->prepare("INSERT INTO games (filename, title, description, author) VALUES (?, ?, ?, ?)");
                                $stmt->bind_param("ssss", $filename, $title, $description, $_SESSION['user']);
    
                                $filename = htmlspecialchars(basename($_FILES["fileToUpload"]["name"]));
                                $title = htmlspecialchars($_POST['title']);
                                $description = htmlspecialchars($_POST['description']);
                                $description = str_replace(PHP_EOL, "<br>", $description);
    
                                $stmt->execute();
                                $stmt->close();
                            } else {
                                echo 'fatal error<hr>';
                            }
                        }
                    } else {
                        echo "You aren't logged in.";
                    }
                }
            ?>
            <form method="post" enctype="multipart/form-data">
				<small>Select a SWF file:</small>
				<input type="file" name="fileToUpload" id="fileToUpload"><br>
                <input type="checkbox" name="remember"><small>This game will not infringe any copyright laws AND is not NSFW</small>
                <hr>
                <input size="69" type="text" placeholder="Game Title" name="title"><br><br>
                <textarea required cols="81" placeholder="Information about your game" name="description"></textarea><br><br>
                <input type="submit" value="Upload Game" name="submit">  <small>Note: Games are manually approved.</small>
            </form>
        </div>
    </body>
</html>