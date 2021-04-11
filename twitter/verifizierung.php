<?php

include "backend/initialize.php";
// include "backend/classes/databse.php";
// include "backend/initialize.php";
$pageTitle = "Account Verifizierung";

include "backend/shared/header.php";

if(isset($_SESSION["userEingeloggt"])) {
    $userId = $_SESSION["userEingeloggt"];
    $user = $loadUser->userData($userId);
} else {
    redirect_to(url_for("index"));
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
    <h2>Verifizierung wurde per Mail an <?php echo $user->email ?> gesendet. Bitte überprüfen!</h2>
</div>


</body>

</html>