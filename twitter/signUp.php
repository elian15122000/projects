<?php
$pageTitle = "Twitter Registrierung";
?>

<?php
include "backend/shared/header.php";
include "backend/initialize.php";
// include "backend/classes/databse.php";
// include "backend/initialize.php";


if(is_post_request()) {
  if(isset($_POST["vorname"]) && !empty($_POST["vorname"])) {
    $vorname = FormSanitizer::formSanitizerName($_POST["vorname"]);
    $nachname = FormSanitizer::formSanitizerName($_POST["nachname"]);
    $email = FormSanitizer::formSanitizerString($_POST["email"]); 
    $passwort = FormSanitizer::formSanitizerString($_POST["passwort"]);
    $passwort2 = FormSanitizer::formSanitizerString($_POST["passwortWdh"]);
    
    $username = $account->generateUsername($vorname, $nachname);

    $erfolgreich = $account->register($vorname, $nachname, $username, $email, $passwort, $passwort2);
    if($erfolgreich) {
      //echo "Daten in DB eingetragen";
      $_SESSION["userEingeloggt"] = $erfolgreich;
      if(isset($_POST["merken"])) {
        $_SESSION["merken"] = $_POST["merken"];
      }
      redirect_to(url_for("verifizierung"));
    }
  }
}

?>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <a class="navbar-brand" href="#">Navbar</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNav">
    <ul class="navbar-nav">
      <li class="nav-item active">
        <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">Über</a>
      </li>
    </ul>
  </div>
</nav>


<div class="container">

  <form action="<?php echo h($_SERVER['PHP_SELF']); ?>" method="POST">
    <div class="form-group">
      
      <label for="vorname">Vorname</label>
      <input type="text" class="form-control" name="vorname" id="vorname" aria-describedby="emailHelp" placeholder="Vorname" required>
      <?php echo $account->getError(Konstanten::$vornameZeichen) ?>
    </div>
    <div class="form-group">
      <label for="nachname">Nachname</label>
      <input type="text" class="form-control" name="nachname" id="nachname" aria-describedby="emailHelp" placeholder="Nachname" required>
      <?php echo $account->getError(Konstanten::$nachnameZeichen) ?>
    </div>
    <div class="form-group">
      <label for="email">Email</label>
      <input type="email" class="form-control" name="email" id="email" aria-describedby="emailHelp" placeholder="Email" required>
      <?php echo $account->getError(Konstanten::$emailBesetzt) ?>
      <?php echo $account->getError(Konstanten::$emailInvalid) ?>
    </div>
    <div class="form-group">
      <label for="passwort">Passwort</label>
      <input type="password" class="form-control" name="passwort" id="passwort" placeholder="Passwort" required>
    </div>
    <div class="form-group">
      <label for="passwortWdh">Passwort wiederholen</label>
      <input type="password" class="form-control" name="passwortWdh" id="passwortWdh" placeholder="Passwort wiederholen" required>
      <?php echo $account->getError(Konstanten::$passwortInvalid) ?>
      <?php echo $account->getError(Konstanten::$passwortKurz) ?>
      <?php echo $account->getError(Konstanten::$passwortFalsch) ?>
    </div>
    <div class="form-check">
      <input type="checkbox" class="form-check-input" id="merken" name="merken">
      <label class="form-check-label" for="merken">Merken</label>
    </div>
    <div class="form-check">
      <input type="checkbox" class="form-check-input" id="zeigen" onclick="showPassword()">
      <label class="form-check-label" for="zeigen">Passwort zeigen</label>
    </div>
    <br>
    <button type="submit" name="register" class="btn btn-primary">Registrieren</button>
    <a href="">Passwort veregessen?</a><br><br>
    <p>Du hast schon einen Account? <a href="login">Login</a></p>
  </form>
</div>


<script src="frontend/assets/js/showPassword.js"></script>

</body>

</html>