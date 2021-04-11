<?php 
    include "backend/initialize.php";

    $db = Database::instance();
    $db->prepare("Select * from users");
    
    include "backend/shared/header.php";
?>

    
    <div class="container-fluid text-center">
        <div class="row">
            <div class="col-sm-7 wallpaper">
                Willkommen
            </div>
            <div class="col-5">
                <a href="signUp" class="btn btn-info btn-rounded">Registrieren</a>
                <a href="login" class="btn btn-light btn-rounded">Login</a>
            </div>
        </div>


        <footer class="fixed-bottom">
            Footer
        </footer>
    </div>


</body>

</html>