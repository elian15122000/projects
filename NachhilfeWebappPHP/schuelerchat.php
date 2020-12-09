<?php
header('Cache-Control: no cache'); //no cache
session_cache_limiter('private_no_expire');
session_start();
require("mysql.php");
//session_cache_limiter('public'); // works too
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit;
}
$aktuellerlehrerid = $_SESSION["lehrer"];
$sql = "SELECT username FROM users WHERE id = '$aktuellerlehrerid'";
foreach ($mysql->query($sql) as $row) {
    $aktuellerlehrername = $row["username"];
}
?>
<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous">
    </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous">
    </script>
    <link rel="stylesheet" href="chat.css">
    <!-- <script src="jquery-3.5.1.min.js"></script> -->
    <title>Chat</title>
</head>

<body>

    <div class="d-flex flex-column flex-md-row align-items-center p-3 px-md-4 mb-3 border-bottom shadow-sm fixed-top navi">
        <h5 class="my-0 mr-md-auto font-weight-normal">
            <img src="Logo2.png" width="100" height="20" class="d-inline-block align-top" alt="">
        </h5>
        <nav class="my-2 my-md-0 mr-md-3 listings">
            <a class="p-2 text-light" href="schuelerloggedIn.php">Home</a>
            <a class="p-2 text-light" href="schuelerchat.php">Chat</a>
            <a class="p-2 text-light" href="support.php">Support</a>
            <a class="btn btn-danger" href="logout.php">Logout</a>
        </nav>

    </div>
    <div class="container">
        <div class="navigation">
        </div>
        <h1>Deine Chats</h1>
        <input class="form-control" type="text" placeholder="Suche Lehrer" id="suche" aria-label="Search"><br> <!-- Form-Group -->


        <?php
        require("mysql.php");
        $username = $_SESSION["username"];

        $sql = "SELECT id FROM users WHERE username = '$username'";
        foreach ($mysql->query($sql) as $row) {
            $schuelerid = $row["id"];
        }

        $sql = "SELECT lehrerid FROM calendar_zugriff WHERE schuelerid = '$schuelerid' AND anfrage = 1";
        foreach ($mysql->query($sql) as $row) {
            // $schuelerid = $row["schuelerid"];
            // $schueleranfragen[] = $row["schuelerid"];
            $lehrer[] = $row["lehrerid"];
        }



        if ($lehrer != NULL) {

            for ($i = 0; $i < count($lehrer); $i++) {
                // echo $schueleranfragen[$i];
                $username = $_SESSION["username"];


                $sql = "SELECT username FROM users WHERE id = '$lehrer[$i]'";
                foreach ($mysql->query($sql) as $row) {
                    $lehrernamen[] = $row["username"];
                }

                $sql = "SELECT chat FROM chat WHERE schuelerid = '$schuelerid' AND lehrerid = '$lehrer[$i]'";
                foreach ($mysql->query($sql) as $row) {
                    $letztenachricht = $row["chat"];
                }
                //style="padding: 2%; border-bottom: 1px solid gray; background: #f7f7f7"

        ?>
                <center>
                    <form method="post" class="form">
                        <button data-controls-modal="<?php echo $lehrernamen[$i] ?>" data-backdrop="static" data-keyboard="false" type="submit" style="padding: 2%; border-bottom: 1px solid gray; border-radius: 0px;" class="btn btn-secondary btn-block" id="chat<?php echo $lehrernamen[$i] ?>" name="submit<?php echo $lehrernamen[$i] ?>">
                            <div class="float-left namen"><?php echo $lehrernamen[$i] ?> </div>
                            <div class="float-right namen" style="color: white"><small><i><?php echo $letztenachricht  ?></i></small> </div>
                        </button>
                    </form>
                </center>
                <div id="<?php echo $lehrernamen[$i] ?>" class="modal fade" role="dialog">
                    <div class="modal-dialog modal-dialog-centered">

                        <!-- Modal content-->
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Chat mit <?php echo $lehrernamen[$i] ?></h4>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>
                            <div class="modal-body" style="background-image: url(chathintergrund.png);">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="wrapper" id="wrapper<?php echo $i ?>" style=" max-height: 400px; overflow-Y: scroll; overflow-X: hidden; -ms-overflow-style: none; scrollbar-width: none; ">
                                            <!-- Nachrichten mit entsprechender Schleife hier einblenden in eigenes Div -->
                                            <?php
                                            $sql = "SELECT von, chat, uhrzeit FROM chat WHERE lehrerid = '$lehrer[$i]' AND schuelerid = '$schuelerid'";
                                            foreach ($mysql->query($sql) as $row) {
                                                if ($row["von"] == $lehrer[$i]) {
                                                    echo "<div class='float-left nachrichtlinks speech-bubble-left' style='margin-left: 3%;'>" . $row["chat"] . "</div>";
                                                    echo "<br><br>";
                                                } else {
                                                    echo "<div class='float-right nachrichtrechts speech-bubble-right' style='margin-right: 3%;'><b>" . $row["chat"] . "</b></div>";
                                                    echo "<br><br>";
                                                }
                                            }
                                            // echo $lehrernachrichten[$i];
                                            ?>


                                        </div>
                                        <br>
                                        <div class="" style="text-align: center">
                                            <form method="post" id="form<?php echo $lehrernamen[$i] ?>" action="schuelersend.php">
                                                <div class="form-group pull-right row">
                                                    <div class="col-10">
                                                        <input type="text" id="nachricht<?php echo $lehrernamen[$i] ?>" autocomplete="off" class="form-control" name="nachricht" required placeholder="Nachricht...">

                                                    </div>
                                                    <div class="col-1">
                                                        <button class="btn" type="submit" name="sendchat<?php echo $i ?>" id="sendchat<?php echo $i ?>">
                                                            <svg width="1.5em" height="1.5em" viewBox="0 0 16 16" class="bi bi-play-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                                                <path d="M11.596 8.697l-6.363 3.692c-.54.313-1.233-.066-1.233-.697V4.308c0-.63.692-1.01 1.233-.696l6.363 3.692a.802.802 0 0 1 0 1.393z" />
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </div>

                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <script>
                    $('#form<?php echo $lehrernamen[$i] ?>').submit(function(e) {
                        e.preventDefault();

                        $.post(
                            'schuelersend.php',

                            {
                                nachricht: $('#nachricht<?php echo $lehrernamen[$i] ?>').val(),
                            },
                            function(result) {
                                if (result == "success") {

                                } else {
                                    $('#wrapper<?php echo $lehrernamen[$i] ?>').val("Error");
                                }
                            }

                            //$('#wrapper<?php echo $lehrernamen[$i] ?>').val(nachricht);
                        );

                        $('#nachricht<?php echo $lehrernamen[$i] ?>').val("");
                        $('#nachricht<?php echo $lehrernamen[$i] ?>').focus();
                        $.ajax({ //create an ajax request to display.php
                            type: "GET",
                            url: "loadchatschueler.php",
                            dataType: "html", //expect html to be returned                
                            success: function(response) {
                                $("#wrapper<?php echo $i ?>").html(response);
                                //alert(response);
                            }

                        });
                    });


                    window.setInterval(function() {
                        $.ajax({ //create an ajax request to display.php
                            type: "GET",
                            url: "loadchatschueler.php",
                            dataType: "html", //expect html to be returned                
                            success: function(response) {
                                $("#wrapper<?php echo $i ?>").html(response);
                            }
                        });

                    }, 1000);
                </script>

                <script>
                    $(document).on('DOMNodeInserted', function(e) {
                        if ($(e.target).hasClass('.nachricht')) {
                            $('#wrapper<?php $i ?>').animate({
                                scrollTop: $('#wrapper<?php $i ?>')[0].scrollHeight
                            }, 500);
                        }
                    });
                </script>



                <?php
                if (isset($_POST["submit$lehrernamen[$i]"])) {
                ?>
                    <script>
                        $("#<?php echo $lehrernamen[$i] ?>").modal("show");
                        // $('#wrapper<?php echo $i ?>').animate({ scrollTop: $('#wrapper<?php echo $i ?>').height() }, 500);
                        $('#nachricht<?php echo $lehrernamen[$i] ?>').focus()
                        header("Refresh:0");
                        <?php $_SESSION["lehrer"] = $lehrer[$i];
                        $_SESSION["i"] = $i ?>
                    </script>
                <?php
                }
                ?>

                <script>
                    $('#<?php echo $lehrernamen[$i] ?>').on('shown.bs.modal', function() {
                        $('#wrapper<?php echo $i ?>').animate({
                            scrollTop: $('#wrapper<?php echo $i ?>')[0].scrollHeight
                        }, 500);
                    });
                </script>


        <?php
                //echo "<script>var option=document.createElement(\"option\");option.text='$lehrernamen[$i]';option.name='$lehrernamen[$i]';document.getElementById('schuelerchat').add(option);</script>";

                // $sendchat = "sendchat".$i;
                // if (isset($_POST[$sendchat])) {
                //     $nachricht = $_POST["nachricht"];
                //     $sql = "INSERT INTO chat (lehrerid, schuelerid, chat, von) VALUES (?, ?, ?, ?)";
                //     $stmt = $mysql->prepare($sql);
                //     $stmt->execute([$lehrer[$i], $schuelerid, $nachricht, $schuelerid]);
                //     echo $lehrer[$i];
                // }

            } //schleife schließt

        }
        ?>
    </div> <!-- Container schließt -->
    <script>
        $(document).ready(function() {
            $("#suche").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $(".form *").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
        });
    </script>
</body>



</html>