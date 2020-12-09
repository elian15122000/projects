<?php
session_start();
if (isset($_POST["submit"])) {
  require("mysql.php");
  $stmt = $mysql->prepare("SELECT * FROM users WHERE USERNAME = :user"); //Username überprüfen
  $stmt->bindParam(":user", $_POST["username"]);
  $stmt->execute();
  $count = $stmt->rowCount();
  if ($count == 1) {
    //Username ist frei
    $row = $stmt->fetch();
    if (password_verify($_POST["pw"], $row["PASSWORD"])) {
      $_SESSION["username"] = $row["USERNAME"];
      if ($row["ROLE"] == "Lehrer") {
        header("Location: lehrerloggedIn.php");
      } elseif ($row["ROLE"] == "Schueler") {
        header("Location: schuelerloggedIn.php");
      }
    } else {
      echo "<script>alert('Der Login ist fehlgeschlagen')</script>";
    }
  } else {
    echo "<script>alert('Der Login ist fehlgeschlagen')</script>";
  }
}
?>
<!-- <!DOCTYPE html>
<html lang="de" dir="ltr">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
  </head>
  <body>

  <div class="container">
        <div class="row">
            <div class="col-md">
                
            </div>

            <div class="col-md">
                <h1>Anmelden</h1>
                <form action="login.php" method="post">
                  <input type="text" name="username" placeholder="Username" required><br>
                  <input type="password" name="pw" placeholder="Passwort" required><br>
                  <button type="submit" name="submit" >Einloggen</button>
                </form>

                <form action="login.php" method="post">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" required class="form-control" id="usernamelogin" name="username" aria-describedby="usernameEingabe" placeholder="Username">
                        <small id="usernameLoginHelp" class="form-text text-muted">Gebe deinen Username ein, um dich anzumelden.</small>
                    </div>
                    <div class="form-group">
                        <label for="pw">Passwort</label>
                        <input type="password" required name="pw" id="pw" class="form-control" placeholder="Passwort">
                    </div>
                    <button type="submit" name="submit" class="btn btn-primary">Registrieren</button>
                </form>

                <br>
                <a href="index.php">Noch keinen Account?</a><br>
                <a href="passwordreset.php">Hast du dein Passwor vergessen?</a>
            </div>

            <div class="col-md">
                
            </div>
        </div>

      </div>
  </body>
</html> -->


<!doctype html>
<html lang="de">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="Tutoplan Login">
  <meta name="author" content="Elian Yildirim">
  <link rel="shortcut icon" href="/Favicon1.png">
  <!-- <meta name="generator" content="Jekyll v4.1.1"> -->
  <title>Login</title>


  <!-- Bootstrap core CSS -->
  <link href="/docs/4.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>

  <!-- Favicons -->


  <style>
    .bd-placeholder-img {
      font-size: 1.125rem;
      text-anchor: middle;
      -webkit-user-select: none;
      -moz-user-select: none;
      -ms-user-select: none;
      user-select: none;
    }


    @media (min-width: 768px) {
      .bd-placeholder-img-lg {
        font-size: 3.5rem;
      }
    }

  </style>
  <!-- Custom styles for this template -->
  <link href="signin.css" rel="stylesheet">
</head>

<body class="text-center" style="background-color: #f7f7f7;">
  <form class="form-signin" method="post">
    <img class="mb-4" src="/Logo1.png" alt="" width="300" height="60">
    <h1 class="h3 mb-3 font-weight-normal">Login</h1>
    <label for="inputEmail" class="sr-only">Username</label>
    <input type="text" id="inputEmail" class="form-control" placeholder="Username" name="username" required autofocus>
    <label for="inputPassword" class="sr-only">Passwort</label>
    <input type="password" id="inputPassword" class="form-control" placeholder="Password" name="pw" required>
    <div class="checkbox mb-3">
      <label>
        <input type="checkbox" value="remember-me"> Passwort merken
      </label>
    </div>
    <button class="btn btn-lg btn-primary btn-block" name="submit" type="submit">Einloggen</button>
    <p class="mt-5 mb-3 text-muted">&copy; Tutoplan 2020</p>
    <a href="index.php#register">Noch keinen Account?</a><br>
    <a href="passwordreset.php">Hast du dein Passwor vergessen?</a>
  </form>
</body>

</html>