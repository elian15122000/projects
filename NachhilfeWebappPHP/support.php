<?php
header('Cache-Control: no cache'); //no cache
session_cache_limiter('private_no_expire'); // works
//session_cache_limiter('public'); // works too
session_start();
require("mysql.php");

$username = $_SESSION["username"];
$useremail = $_SESSION['email'];
$userrole = $_SESSION['role'];

if (isset($_POST["submit"])) {
    $name = $username;
    $email = $useremail;
    $nachricht = $_POST["nachricht"];

    $an = "tutoplande@gmail.com";
    $von = "Von: " . $email;
    $txt = $nachricht;
    $betreff = "User Support von User: " . $name . " mit der Mail: " . $email . " (" . $userrole . ")";

    mail($an, $betreff, $txt, $von);
    // 
    if($userrole == "Lehrer") {
        echo "<script>
        alert('E-Mail wurde versendet.');
        window.location.href='lehrerloggedIn.php';
        </script>";
    } elseif($userrole == "Schueler") {
        echo "<script>
        alert('E-Mail wurde versendet.');
        window.location.href='schuelerloggedIn.php';
        </script>";
    } else {
        echo "<script>
        alert('E-Mail wurde versendet.');
        </script>";
    }
}


?>

<html lang="de">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Support / Kontakt</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous">
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous">
    </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous">
    </script>
</head>

<style>
    body {
        display: -ms-flexbox;
        display: flex;
        -ms-flex-align: center;
        align-items: center;
        padding-top: 40px;
        padding-bottom: 40px;
        /* background-color: #f5f5f5; */
        background-color: #f7f7f7;
    }

    .btn-primary,
    .btn-primary:hover,
    .btn-primary:active,
    .btn-primary:visited {
        background-color: #EF8E1C !important;
        border-color: #EF8E1C !important;
    }

    .btn-outline-primary {
        color: #EF8E1C !important;
        border-color: #EF8E1C !important;
    }

    .btn-outline-primary:hover {
        color: white !important;
        background-color: #EF8E1C !important;
    }

    /* .form-rounded {
    border-radius: 1rem;
} */
    /* .form-control::placeholder {
    color: #ffd8a6;
    font-weight: bolder; 
    opacity: 1;
} */
    input:focus {
        transform: scale(1.04);
    }

    textarea:focus {
        transform: scale(1.04);
    }
</style>

<body>
    <div class="container">
        <div class="row">
            <div class="col-md-3">

            </div>
            <div class="col-md-6">
                <form action="support.php" method="post" id="supportform" class="needs-validation" novalidate>
                    <div style="text-align: center;"><img src="/Logo1.png" alt="" width="300" height="60"></div>
                    <!-- <h2 class="display-5" style="text-align: center; opacity: 0.9;"><b>SENDE UNS DEIN PROBLEM</b></h2> -->
                    <p class="lead" style="text-align: center; opacity: 0.9;">Wir kümmern uns drum - zuverlässig und schnell.</p>
                    <fieldset disabled>
                        <div class="form-group">
                            <input type="text" name="name" required value="<?php echo $username ?>" class="form-control form-rounded">
                            <small id="usernameHelp" class="form-text text-muted">Gebe deinen Namen an, damit wir dich anschreiben können.</small>
                            <div class="invalid-feedback">
                                Bitte deinen Usernamen eingeben.
                            </div>
                        </div>
                        <div class="form-group">
                            <input type="email" class="form-control form-rounded" id="email" name="email" value="<?php echo $useremail ?>" aria-describedby="emailHelp" required>
                            <small id="emailHelp" class="form-text text-muted">Deine E-Mail wird benötigt, um dir antworten zu können.</small>
                            <div class="invalid-feedback">
                                Bitte deine E-Mail eingeben.
                            </div>
                        </div>
                        </fieldset>
                        <div class="form-group">
                            <textarea class="form-control form-rounded" id="nachricht" name="nachricht" rows="5" required placeholder="Deine Nachricht"></textarea>
                            <small id="emailHelp" class="form-text text-muted">Bitte gebe deine Nachricht ein und beschreibe dein Problem / Anliegen so genau wie möglich, um längere Wartezeiten zu verhindern.</small>
                            <div class="invalid-feedback">
                                Bitte eine Nachricht eingeben.
                            </div>
                        </div>
                    
                    <button type="submit" name="submit" class="btn btn-primary btn-block">Absenden</button>
                    <small id="absendenHelp" class="form-text text-muted">Deine Nachricht wird schnellstmöglich bearbeitet. In der Regel benötigen wir ca. 1 bis 2 Tage, um dir zu antworten.</small>
                </form>
            </div>

            <div class="col-md-3">

            </div>
        </div>
    </div>
    <?php echo $msg; ?>

    <script>
        (function() {
            'use strict';
            window.addEventListener('load', function() {
                // Fetch all the forms we want to apply custom Bootstrap validation styles to
                var forms = document.getElementsByClassName('needs-validation');
                // Loop over them and prevent submission
                var validation = Array.prototype.filter.call(forms, function(form) {
                    form.addEventListener('submit', function(event) {
                        if (form.checkValidity() === false) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                        form.classList.add('was-validated');
                    }, false);
                });
            }, false);
        })();
    </script>
</body>

</html>