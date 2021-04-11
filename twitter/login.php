<?php 
    $pageTitle = "Login Twitter";
    include "backend/shared/header.php";
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
<form action="">
  <div class="form-group">
    <label for="username">Username or Email</label>
    <input type="text" class="form-control" id="username" aria-describedby="emailHelp" placeholder="Enter email">
    <small id="usernameHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
  </div>
  <div class="form-group">
    <label for="passwort">Passwort</label>
    <input type="password" class="form-control" id="passwort" placeholder="Passwort">
  </div>
  <div class="form-check">
    <input type="checkbox" class="form-check-input" id="zeigen" onclick="showPasswordLogin()">
    <label class="form-check-label" for="zeigen">Passwort zeigen</label>
  </div>
  <br>
  <button type="submit" class="btn btn-primary">Login</button>
  <a href="">Passwort veregessen?</a><br><br>
  <p>Du hast noch keinen Account? <a href="signUp">Registrieren</a></p>
</form>
</div>

<script src="frontend/assets/js/showPassword.js"></script>

</body>
</html>