<?php
    if(isset($_POST["reset"])) {
        require("mysql.php");
        $stmt = $mysql->prepare("SELECT * FROM users WHERE EMAIL = :email");
        $stmt->bindParam(":email", $_POST["email"]); 
        $stmt->execute();
        $count = $stmt->rowCount();
        if($count != 0) {
            $token = generateRandomString(25);
            $stmt = $mysql->prepare("UPDATE users SET TOKEN = :token WHERE EMAIL = :email");
            $stmt->bindParam(":token", $token);
            $stmt->bindParam(":email", $_POST["email"]);
            $stmt->execute();
            mail($_POST["email"], "Passwort zurücksetzen", "dhbwweb.bplaced.net/setpassword.php?token=".$token);
            echo "Die Email wurde versendet";
        } else {
            echo "Die angegebene E-Mail ist nicht registriert";
        }
    }
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
<html lang="de">
<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Passwort zurücksetzen</title>
</head>
<body>

    <!-- <form action="passwordreset.php" method="post">
        <input type="email" id="resetemail" name="email">
        <button type="submit" name="reset">Zurücksetzen</button>
    </form> -->

    <div class="container">
        <div class="row">
            <div class="col-md">
                
            </div>

            <div class="col-md">
                <h1>Passwort zurücksetzen</h1>
                <form action="passwordreset.php" method="post">
                    <div class="form-group">
                        <!-- <label for="resetemail">Passwort zurücksetzen</label> -->
                        <input type="text" required class="form-control" id="resetemail" name="email" aria-describedby="reset password" placeholder="E-Mail">
                        <small id="resetEmailHelp" class="form-text text-muted">Gebe deine registrierte E-Mail ein, um dein Passwort zurückzusetzen.</small>
                    </div>
                    <button type="submit" name="reset" class="btn btn-primary">Zurücksetzen</button>
                </form>
            </div>

            <div class="col-md">
                
            </div>
        </div>
    </div>
    

</body>
</html>