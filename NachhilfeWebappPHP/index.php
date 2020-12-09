<?php
session_start();
if (isset($_POST["submit"])) {
    // $request = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=6LdLodYZAAAAAM-8JH9tKpA6Iy2ULyXHQxJ-C931&response=".$_POST["token"]);
    // $request = json_decode($request);
    // if($request->success == true) {
    //     echo $request->score;
    // }
    if (isset($_POST['role'])) {
        if ($_POST['role'] == "Lehrer") {
            require("mysql.php");
            $stmt = $mysql->prepare("SELECT * FROM users WHERE USERNAME = :user");
            $stmt->bindParam(":user", $_POST["username"]);
            $stmt->execute();
            $count = $stmt->rowCount();
            if ($count == 0) {
                $stmt = $mysql->prepare("SELECT * FROM users WHERE EMAIL = :email");
                $stmt->bindParam(":email", $_POST["email"]);
                $stmt->execute();
                $count = $stmt->rowCount();
                if ($count == 0) {
                    if ($_POST["pw"] == $_POST["pw2"]) {
                        header("Location: login.php");
                        //User anlegen
                        $username = $_POST["username"];
                        $stmt = $mysql->prepare("INSERT INTO users (USERNAME, PASSWORD, ROLE, EMAIL, TOKEN) VALUES (:user, :pw, :role, :email, '')");
                        $stmt->bindParam(":user", $_POST["username"]);
                        $hash = password_hash($_POST["pw"], PASSWORD_BCRYPT);
                        $stmt->bindParam(":pw", $hash);
                        $stmt->bindParam(":role", $_POST['role']);
                        $stmt->bindParam(":email", $_POST['email']);
                        $stmt->execute();
                        //echo "lehrer angelegt";
                        $duration = 60;
                        $cleanup = 15;
                        $start = "07:00";
                        $end = "18:00";
                        $data = [
                            'username' => $username,
                            'duration' => $duration,
                            'cleanup' => $cleanup,
                            'start' => $start,
                            'end' => $end,

                        ];
                        $sql = "INSERT INTO lehrereinstellungen (lehrerusername, duration, cleanup, start, end) VALUES (:username, :duration, :cleanup, :start, :end)";
                        $mysql->prepare($sql)->execute($data);

                        $_SESSION["role"] = $row["ROLE"];
                        exit;
                    } else {
                        echo "Passwörter stimmen nicht überein!";
                    }
                } else {
                    echo "E-Mail vergeben";
                }
            } else {
                echo "Username vergeben";
            }
        } else if ($_POST['role'] == "Schueler") {
            require("mysql.php");
            $stmt = $mysql->prepare("SELECT * FROM users WHERE USERNAME = :user");
            $stmt->bindParam(":user", $_POST["username"]);
            $stmt->execute();
            $count = $stmt->rowCount();
            if ($count == 0) {
                $stmt = $mysql->prepare("SELECT * FROM users WHERE EMAIL = :email");
                $stmt->bindParam(":email", $_POST["email"]);
                $stmt->execute();
                $count = $stmt->rowCount();
                if ($count == 0) {
                    if ($_POST["pw"] == $_POST["pw2"]) {
                        header("Location: login.php");
                        //User anlegen
                        $username = $_POST["username"];
                        $stmt = $mysql->prepare("INSERT INTO users (USERNAME, PASSWORD, ROLE, EMAIL, TOKEN) VALUES (:user, :pw, :role, :email, '')");  //roles erstellen, um doppelte usernamen zu vermeiden
                        $stmt->bindParam(":user", $_POST["username"]);
                        $hash = password_hash($_POST["pw"], PASSWORD_BCRYPT);
                        $stmt->bindParam(":pw", $hash);
                        $stmt->bindParam(":role", $_POST['role']);
                        $stmt->bindParam(":email", $_POST['email']);
                        $stmt->execute();
                        if ($mysql->query($sql) === TRUE) {
                            echo "Table MyGuests created successfully";
                        } else {
                            echo "Error creating table: " . $mysql->error;
                        }
                        exit;
                    } else {
                        echo "Passwörter stimmen nicht überein!";
                    }
                } else {
                    echo "Email vergeben";
                }
            } else {
                echo "Username vergeben";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Startseite</title>
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
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="/Favicon1.png">


    <meta name="description" content="Tutoplan ist eine digitale Lösung für die Kommunikation und Terminplanung für Nachhilfelehrer
                    und dessen Schüler Kostenlos und auch als Vertrag - natürlich monatlich kündbar.">
    <meta name="keywords" content="Tutoplan, Nachhilfe, Lehrer, Nachhilfelehrer, Schüler, Nachhilfeschüler, Kalender">
    <meta name="author" content="Elian Yildirim">
    <script src="https://www.google.com/recaptcha/api.js"></script>
</head>


<body style="background-color: #f7f7f7">

    <div class="d-flex flex-column flex-md-row align-items-center p-3 px-md-4 mb-3 border-bottom shadow-sm fixed-top navi" style="background-color: #EF8E1C;">
        <h5 class="my-0 mr-md-auto font-weight-normal">
            <img src="Logo2.png" width="100" height="20" class="d-inline-block align-top" alt="">
        </h5>
        <nav class="my-2 my-md-0 mr-md-3 listings" style="text-align: center">
            <a class="p-2 text-light" href="#register">Registrieren</a>
            <a class="p-2 text-light disabled" title="Den Support kannst du leider nur mit einem existierenden Account anschreiben.">Support</a>
            <!-- <a class="p-2 text-light" href="#versionen">Versionen</a> -->
            <a class="btn btn-light" href="login.php">Login</a>
        </nav>

    </div>

    <div class="navigation">
    </div>
    <!-- style="margin-top: 2%;" -->

    <div class="container">

        <section id="versionen">

            <div class="pricing-header px-3 py-3 pt-md-5 pb-md-4 mx-auto text-center">
                <h1 class="display-4">Übersichtlich und schnell.
                    <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-calendar-date" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z" />
                        <path d="M6.445 11.688V6.354h-.633A12.6 12.6 0 0 0 4.5 7.16v.695c.375-.257.969-.62 1.258-.777h.012v4.61h.675zm1.188-1.305c.047.64.594 1.406 1.703 1.406 1.258 0 2-1.066 2-2.871 0-1.934-.781-2.668-1.953-2.668-.926 0-1.797.672-1.797 1.809 0 1.16.824 1.77 1.676 1.77.746 0 1.23-.376 1.383-.79h.027c-.004 1.316-.461 2.164-1.305 2.164-.664 0-1.008-.45-1.05-.82h-.684zm2.953-2.317c0 .696-.559 1.18-1.184 1.18-.601 0-1.144-.383-1.144-1.2 0-.823.582-1.21 1.168-1.21.633 0 1.16.398 1.16 1.23z" />
                    </svg>
                </h1>
                <p class="lead">Tutoplan ist eine digitale Lösung für die Kommunikation und Terminplanung für
                    Nachhilfelehrer
                    und dessen Schüler.<br>
                    Kostenlos und auch als Vertrag - natürlich monatlich kündbar.</p>
            </div>


            <div class="card-deck mb-3 text-center">
                <div class="card mb-4 shadow-sm">
                    <div class="card-header" style="background-color: white;">
                        <h4 class="my-0 font-weight-normal orange kostenlos"><b>Kostenlos</b>
                            <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-info-square bounce" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M14 1H2a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z" />
                                <path fill-rule="evenodd" d="M14 1H2a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z" />
                                <path d="M8.93 6.588l-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588z" />
                                <circle cx="8" cy="4.5" r="1" />
                            </svg>
                        </h4>
                    </div>
                    <div class="card-body">
                        <h1 class="card-title pricing-card-title">0€<small class="text-muted"> / Monat</small></h1>
                        <ul class="list-unstyled mt-3 mb-4">
                            <li>Kalenderfunktion enthalten</li>
                            <li>Chatfunktion enthalten</li>
                            <li>E-Mail Support enthalten</li>
                        </ul>
                        <button type="button" class="btn btn-lg btn-block btn-outline-primary" onclick="window.location.href='#register'">Kostenlos
                            registrieren</button>
                    </div>
                </div>
                <div class="card mb-4 shadow-sm">
                    <div class="card-header" style="background-color: white;">
                        <h4 class="my-0 font-weight-normal orange pro"><b>Pro</b>
                            <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-info-square bounce" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M14 1H2a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z" />
                                <path fill-rule="evenodd" d="M14 1H2a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z" />
                                <path d="M8.93 6.588l-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588z" />
                                <circle cx="8" cy="4.5" r="1" />
                            </svg>
                        </h4>
                    </div>
                    <div class="card-body">
                        <h1 class="card-title pricing-card-title">4,99€<small class="text-muted"> / Monat</small></h1>
                        <ul class="list-unstyled mt-3 mb-4">
                            <li>Kalenderfunktion enthalten</li>
                            <li>Chatfunktion enthalten</li>
                            <li>E-Mail Support enthalten</li>
                            <li class="orange">+ Telefon Support enthalten</li>
                            <li class="orange">+ Schülerverifizierung enthalten</li>
                            <li class="orange">+ Zahlungsabwicklung für Schüler enthalten</li>
                            <li class="orange">+ Kalenderdesign bearbeitbar</li>
                            <li class="orange">+ Werbefrei</li>
                        </ul>
                        <button type="button" class="btn btn-lg btn-block btn-primary" onclick="window.location.href='#register'">Jetzt registrieren &
                            kaufen</button>
                    </div>
                </div>
                <div class="card mb-4 shadow-sm">
                    <div class="card-header" style="background-color: white;">
                        <h4 class="my-0 font-weight-normal orange enterprise"><b>Enterprise</b>
                            <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-info-square bounce" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M14 1H2a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z" />
                                <path fill-rule="evenodd" d="M14 1H2a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z" />
                                <path d="M8.93 6.588l-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588z" />
                                <circle cx="8" cy="4.5" r="1" />
                            </svg>
                        </h4>
                    </div>
                    <div class="card-body">
                        <h1 class="card-title pricing-card-title">7,99€<small class="text-muted"> / Monat</small></h1>
                        <ul class="list-unstyled mt-3 mb-4">
                            <li>Kalenderfunktion enthalten</li>
                            <li>Chatfunktion enthalten</li>
                            <li>E-Mail Support enthalten</li>
                            <li>Telefon Support enthalten</li>
                            <li>Schülerverifizierung enthalten</li>
                            <li>Zahlungsabwicklung für Schüler enthalten</li>
                            <li>Kalenderdesign bearbeitbar</li>
                            <li>Werbefrei</li>
                            <li class="orange">+ Terminbenachrichtigung enthalten</li>
                        </ul>
                        <button type="button" class="btn btn-lg btn-block btn-primary" onclick="window.location.href='#register'">Jetzt registrieren &
                            kaufen</button>
                    </div>
                </div>
            </div>

        </section>
        <br><br>

        <div id="kostenlosesModal" class="modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Kostenlose Version</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <p>Die kostenlose Version beinhaltet ....</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div id="proModal" class="modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Pro Version</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <p>Die Pro Version beinhaltet ....</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>


        <div id="enterpriseModal" class="modal fade" role="dialog">
            <div class="modal-dialog modal-dialog-centered">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Enterprise Version</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <p>Die Enterprise Version beinhaltet ....</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>


        <hr id="register" />
        <br><br>
        <section id="registrierung">

            <div class="row">

                <div class="col-md-6 align-self-center">

                    <h1 style="text-align: left;">Registrierung</h1>
                    <p class="lead">Die Registrierung ist völlig kostenlos.<br>Du startest automatisch mit der
                        kostenlosen Version.<br>Die Support Funktion wird dann freigeschaltet.</p>

                </div>


                <div class="col-md-6">
                    <form action="index.php" method="post" id="login" class="needs-validation" novalidate>
                        <div class="form-group">
                            <label for="username"><b>Username *</b></label>
                            <input type="text" required class="form-control" id="username" name="username" aria-describedby="usernameEingabe">
                            <small id="usernameHelp" class="form-text text-muted">Gebe einen deutlichen Username ein,
                                damit
                                dein Lehrer weiß, wer du bist.</small>
                            <div class="invalid-feedback">
                                Bitte deinen Usernamen eingeben.
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1"><b>E-Mail *</b></label>
                            <input type="email" class="form-control" id="email" name="email" aria-describedby="emailHelp" required>
                            <small id="emailHelp" class="form-text text-muted">Deine E-Mail sieht nur dein
                                Nachhilfelehrer
                                und wird benötigt, falls du dein Passwort vergisst.</small>
                            <div class="invalid-feedback">
                                Bitte deine E-Mail eingeben.
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="pw"><b>Passwort *</b></label>
                                    <div class="input-group" id="show_hide_password">

                                        <input type="password" required name="pw" id="pw" class="form-control">
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <a href=""><i class="fa fa-eye-slash" aria-hidden="true"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="invalid-feedback">
                                        Bitte dein Passwort eingeben.
                                    </div>
                                </div>

                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="pw"><b>Passwort wdh. *</b></label>
                                    <div class="input-group" id="show_hide_password">
                                        <input type="password" required name="pw2" id="pw2" class="form-control">
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                <a href=""><i class="fa fa-eye-slash" aria-hidden="true"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="invalid-feedback">
                                        Bitte dein Passwort wiederholen.
                                    </div>
                                </div>

                            </div>

                        </div>

                        <div class="form-group">
                            <label><b>Bist du Schüler oder Nachhilfelehrer?</b></label>
                            <div class="custom-control custom-radio">
                                <input type="radio" class="custom-control-input" id="lehrer" name="role" value="Lehrer" required>
                                <label class="custom-control-label" for="lehrer">Nachhilfelehrer</label>
                            </div>
                            <div class="custom-control custom-radio mb-3">
                                <input type="radio" class="custom-control-input" id="schueler" name="role" value="Schueler" required>
                                <label class="custom-control-label" for="schueler">Schüler</label>
                                <!-- <div class="invalid-feedback">More example invalid feedback text</div> -->
                                <div class="invalid-feedback">
                                    Bitte deine Rolle auswählen.
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="invalidCheck" required>
                                <label class="form-check-label" for="invalidCheck">
                                    <b><a href="#">AGB</a> und <a href="#">Datenschutzerklärung</a> gelesen</b>
                                </label>
                                <div class="invalid-feedback">
                                    Bitte AGB bestätigen.
                                </div>
                            </div>

                        </div>
                        <input type="hidden" name="token" id="token">
                        <button style="display: none;" class="g-recaptcha" data-sitekey="6LdLodYZAAAAAM-_JyLYuUM58Hkc9bh2xMj9-W15" data-callback='onSubmit' data-action='submit'>Submit</button>
                        <button type="submit" name="submit" class="btn btn-outline-primary btn-block">Registrieren</button>
                        <small id="registerHelp" class="form-text text-muted">Du registrierst dich für die kostenlose
                            Version. Eine kostenpflichtige Version kannst du nach der Registrierung kaufen.</small>
                    </form>

                    <br />
                    <!-- <a href="login.php">Du hast bereits einen Account? Zum Login...</a> -->

                </div>

            </div>
            
            <script>
                $(".kostenlos").click(function() {
                    $("#kostenlosesModal").modal("show");
                })
                $(".pro").click(function() {
                    $("#proModal").modal("show");
                })
                $(".enterprise").click(function() {
                    $("#enterpriseModal").modal("show");
                })
                $(document).ready(function() {
                    $("#show_hide_password a").on('click', function(event) {
                        event.preventDefault();
                        if ($('#show_hide_password input').attr("type") == "text") {
                            $('#show_hide_password input').attr('type', 'password');
                            $('#show_hide_password i').addClass("fa-eye-slash");
                            $('#show_hide_password i').removeClass("fa-eye");
                        } else if ($('#show_hide_password input').attr("type") == "password") {
                            $('#show_hide_password input').attr('type', 'text');
                            $('#show_hide_password i').removeClass("fa-eye-slash");
                            $('#show_hide_password i').addClass("fa-eye");
                        }
                    });
                });

                /*$(window).scroll(function() {
                    if($( window ).width() <= 786) {
                        if($(window).scrollTop()) {
                            $(".listings").hide();
                        }
                        if($(window).scrollTop() <= 150) {
                            $(".listings").show();
                        }
                    }
                });*/

                var lastScrollTop = 0;
                $(window).scroll(function(event) {
                    var st = $(this).scrollTop();
                    if ($(document).width() <= 750) {
                        if (st > lastScrollTop) {
                            $(".listings").hide();
                        } else {
                            $(".listings").show();
                        }
                        if (st <= 0) {
                            $(".listings").show();
                        } else if ($(window).scrollTop() + $(window).height() == $(document).height()) {
                            $(".listings").hide();
                        }
                        lastScrollTop = st;
                    }
                });

                // Example starter JavaScript for disabling form submissions if there are invalid fields
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


        </section>
        <br><br>
        <footer class="pt-4 my-md-5 pt-md-5 border-top">
            <div class="row" style="text-align: center;">
                <div class="col-12 col-md">
                    <img class="mb-2" src="Favicon1.png" alt="" width="24" height="24">
                    <small class="d-block mb-3 text-muted">&copy; 2020</small>
                </div>
                <div class="col-6 col-md" style="text-align: left;">
                    <h5>Rechtliches</h5>
                    <ul class="list-unstyled text-small">
                        <li><a class="text-muted" href="#">Impressum</a></li>
                        <li><a class="text-muted" href="#">AGB</a></li>
                        <li><a class="text-muted" href="#">Datenschutzerklärung</a></li>
                        <li><a class="text-muted" href="#">Sponsoren</a></li>
                        <li><a class="text-muted" href="#">Werbung</a></li>
                    </ul>
                </div>
                <div class="col-6 col-md" style="text-align: left;">
                    <h5>Unternehmen</h5>
                    <ul class="list-unstyled text-small">
                        <li><a class="text-muted" href="#">Über uns</a></li>
                        <li><a class="text-muted" href="#">Karriere</a></li>
                        <li><a class="text-muted" href="#">Kontakt</a></li>
                        <li><a class="text-muted" href="#">Support</a></li>
                        <li><a class="text-muted" href="#">Anfrage für Zusammenarbeit</a></li>
                    </ul>
                </div>
                <div class="col-6 col-md" style="text-align: left;">
                    <h5>Über Tutoplan</h5>
                    <ul class="list-unstyled text-small">
                        <li><a class="text-muted" href="#">Preise</a></li>
                        <li><a class="text-muted" href="#">Vorteile</a></li>
                        <li><a class="text-muted" href="#">Blog</a></li>
                        <li><a class="text-muted" href="#">Dokumentation</a></li>
                    </ul>
                </div>
            </div>
        </footer>
    </div>
    <script src="https://www.google.com/recaptcha/api.js?render=6LdLodYZAAAAAM-_JyLYuUM58Hkc9bh2xMj9-W15"></script>
            <script>
                    grecaptcha.ready(function() {
                        grecaptcha.execute('6LdLodYZAAAAAM-_JyLYuUM58Hkc9bh2xMj9-W15', {
                            action: 'submit'
                        }).then(function(token) {
                            // Add your logic to submit to your backend server here.
                            // console.log(token);
                            document.getElementById("token").value = token;
                        });
                    });
            </script>
</body>

</html>

<!-- Bootstrap einbinden! -->