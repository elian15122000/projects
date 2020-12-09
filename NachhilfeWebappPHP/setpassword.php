<html lang="de">
<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Passwort zurücksetzen</title>
</head>
<body>
    <?php
    if(isset($_GET["token"])){
        require("mysql.php");
        $stmt = $mysql->prepare("SELECT * FROM users WHERE TOKEN = :token"); //Username überprüfen
        $stmt->bindParam(":token", $_GET["token"]);
        $stmt->execute();
        $count = $stmt->rowCount();
        if($count != 0){
            if(isset($_POST["submit"])){
                if($_POST["pw1"] == $_POST["pw2"]){
                    $hash = password_hash($_POST["pw1"], PASSWORD_BCRYPT);
                    $stmt = $mysql->prepare("UPDATE users SET PASSWORD = :pw, TOKEN = null WHERE TOKEN = :token");
                    $stmt->bindParam(":pw", $hash);
                    $stmt->bindParam(":token", $_GET["token"]);
                    $stmt->execute();
                    echo 'Das Passwort wurde geändert <br>
                    <a href="login.php">Login</a>';
                } else {
                    echo "Die Passwörter stimmen nicht überein";
                }
            }
            ?>

    <div class="container">
        <div class="row">
            <div class="col-md">
                
            </div>

            <div class="col-md">
                <h1>Neues Passwort setzen</h1>
                <!-- <form action="setpassword.php?token=<?php echo $_GET["token"] ?>" method="POST">
                    <input type="password" name="pw1" placeholder="Password" required><br>
                    <input type="password" name="pw2" placeholder="Password wiederholen" required><br>
                    <button type="submit" name="submit">Passwort setzen</button>
                </form> -->

                <form action="setpassword.php?token=<?php echo $_GET["token"] ?>" method="POST">
                    <div class="form-group">
                        <label for="pw">Neues Passwort</label>
                        <input type="password" required name="pw1" id="pw1" class="form-control" placeholder="Neues Passwort">
                    </div>
                    <div class="form-group">
                        <label for="pw">Neues Passwort wiederholen</label>
                        <input type="password" required name="pw2" id="pw2" class="form-control" placeholder="Neues Passwort wiederholen">
                    </div>
                    <button type="submit" name="submit" class="btn btn-primary">Zurücksetzen</button>
                </form>
            </div>

            <div class="col-md">
                
            </div>
        </div>
    </div>

            <?php
        } else {
            echo "Der Token ist ungültig";
        }
    } else {
        echo "Kein gültiger Token gesendet";
    }
    ?>
</body>
</html>