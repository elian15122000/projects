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
$aktuellerschuelerid = $_SESSION["schueler"];
$sql = "SELECT username FROM users WHERE id = '$aktuellerschuelerid'";
foreach ($mysql->query($sql) as $row) {
    $aktuellerschuelername = $row["username"];
}
//echo $aktuellerschuelername;
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
    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> -->
    <link rel="stylesheet" href="chat.css">
    <!-- <script src="jquery-3.5.1.min.js"></script> -->
    <title>Chat</title>
</head>

<?php
/* onload="$('#<?php echo $_SESSION['schuelernamen'][0] ?>').show(); document.getElementById('<?php echo $_SESSION['schuelernamen'][0] ?>').style.overflowY = 'scroll'; document.getElementById('<?php echo $_SESSION['schuelernamen'][0] ?>').style.maxHeight = '500px';"      für Body */
?>


<body>
    <div id="topnav">
        <div class="d-flex flex-column flex-md-row align-items-center p-3 px-md-4 mb-3 fixed-top navi">

            <h5 class="my-0 mr-md-auto font-weight-normal">
                <img src="Logo2.png" width="100" height="20" class="d-inline-block align-top" alt="">
            </h5>
            <nav class="my-2 my-md-0 mr-md-3 listings">
                <a class="p-2 text-light" href="lehrerloggedIn.php">Home</a>
                <a class="p-2 text-light" href="lehrerchat.php">Chat</a>
                <a class="p-2 text-light" href="support.php">Support</a>
                <a class="btn btn-danger" href="logout.php">Logout</a>
            </nav>

        </div>
    </div>

    <div id="padding"></div>
    <div class="navigation" style="padding-top: 3.5%; background: #EF8E1C">
    </div>
    <div class="container-fluid">


        <div class="row">
            <div class="col-md-3 padding-0" id="chats" style="background: white">

            </div>
            <div class="col-md-9 padding-0" id="wrapper" style='height: 750px; overflow-Y: scroll; overflow-X: hidden; background-image: url(chathintergrund.png); padding-top: 1%'>

            </div>
        </div>

        <div id="visiblechatbar">
            <div class="row" style="background: white">
                <div class="col-3">

                </div>
                <div class="col-9" style="border: 1px solid #f7f7f7">
                    <div id="send">
                        <br>
                        <div class="chatbar" style="text-align: center">
                            <form method="post" id="formnachricht" action="send.php">
                                <div class="form-group pull-right row">
                                    <div class="col-11">
                                        <input type="text" autocomplete="off" class="form-control" id="nachricht" name="nachricht" required placeholder="Nachricht...">
                                    </div>
                                    <div class="col-1">
                                        <button class="btn btn-block btn-outline-dark" type="submit" id="sendbutton" name="">Senden</button>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>

    <!-- <div class="col-12">
            <div class="col-md-3">
                <h1>Deine Chats</h1>
                <input class="form-control" type="text" placeholder="Suche Schüler" id="suche" aria-label="Search"><br> 
            </div>
        </div> -->



    <?php
    require("mysql.php");
    $username = $_SESSION["username"];

    $sql = "SELECT id FROM users WHERE username = '$username'";
    foreach ($mysql->query($sql) as $row) {
        $lehrerid = $row["id"];
    }

    $sql = "SELECT schuelerid FROM calendar_zugriff WHERE lehrerid = '$lehrerid' AND anfrage = 1";
    foreach ($mysql->query($sql) as $row) {
        $schuelerid = $row["schuelerid"];
        $schuelerid;
        $schueleranfragen[] = $row["schuelerid"];
    }

    if ($schueleranfragen != NULL) {
        for ($i = 0; $i < count($schueleranfragen); $i++) {
            // echo $schueleranfragen[$i];
            $username = $_SESSION["username"];


            $sql = "SELECT username FROM users WHERE id = '$schueleranfragen[$i]'";
            foreach ($mysql->query($sql) as $row) {
                $schuelernamen[] = $row["username"];
                $_SESSION["schuelernamen"][] = $row["username"];
            }

            $sql = "SELECT chat FROM chat WHERE schuelerid = '$schueleranfragen[$i]' AND lehrerid = '$lehrerid'";
            foreach ($mysql->query($sql) as $row) {
                $letztenachricht = $row["chat"];
            }

            $sql = "SELECT von, chat, uhrzeit FROM chat WHERE lehrerid = '$lehrerid' AND schuelerid = '$schueleranfragen[$i]'";
            foreach ($mysql->query($sql) as $row) {
                $uhrzeit = $row["uhrzeit"];
                $splituhrzeit = explode(" ", $uhrzeit);
                $datum = $splituhrzeit[0];
                $uhr = $splituhrzeit[1];
            }
            //style="padding: 2%; border-bottom: 1px solid gray; background: #f7f7f7"

    ?>
            <div class="invisible">
                <div class="chatbutton">
                    <center>

                        <form method="post" class="form" id="form<?php echo $i ?>">
                            <button type="submit" style="padding: 5%; border: none; border-bottom: 1px solid gray; border-right: 1px solid gray; border-radius: 0px; background: white; color: black; font-size: 150%; " class="btn btn-secondary btn-block chatbuttons" id="chat<?php echo $schuelernamen[$i] ?>" name="submit<?php echo $schuelernamen[$i] ?>">
                                <div class="float-left" style="text-align: left"><b><?php echo $schuelernamen[$i] ?></b><br><small><i style="color: gray">
                                            <?php if (strlen($letztenachricht) <= 15) {
                                                echo $letztenachricht;
                                            } else {
                                                echo substr($letztenachricht, 0, 15) . "...";
                                            } ?></i></small> </div>
                                <div class="float-right" style="text-align: right"><small><?php echo substr($uhr, 0, -3); ?> Uhr</small><br><small><?php echo date('d.m.Y', strtotime($datum)) ?></small></div>
                            </button>
                        </form>

                    </center>
                </div>
                <!-- <div id="<?php echo $schuelernamen[$i] ?>" style="background-image: url(chathintergrund.png);">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="wrapper" id="wrapper<?php echo $i ?>" style=" max-height: 80vh; overflow-Y: scroll; overflow-X: hidden; -ms-overflow-style: none; scrollbar-width: none;">


                            </div>
                            <br>
                            <div class="chatbar" style="text-align: center">
                                <form method="post" id="form<?php echo $schuelernamen[$i] ?>">
                                    <div class="form-group pull-right row">
                                        <div class="col-9">
                                            <input type="text" autocomplete="off" class="form-control" name="nachricht" id="nachricht<?php echo $schuelernamen[$i] ?>" required placeholder="Nachricht...">

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
                </div> -->


            </div> <!-- #invisible schließt -->
            <!-- <div id="<?php echo $schuelernamen[$i] ?>" class="modal fade" role="dialog">
                        <div class="modal-dialog modal-dialog-centered">

                            
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">Chat mit <?php echo $schuelernamen[$i] ?></h4>
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>
                                <div class="modal-body" style="background-image: url(chathintergrund.png);">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="wrapper" id="wrapper<?php echo $i ?>" style=" max-height: 400px; overflow-Y: scroll; overflow-X: hidden; -ms-overflow-style: none; scrollbar-width: none;">
                                                
                                                <?php

                                                ?>


                                            </div> 
                                            <br>
                                            <div class="" style="text-align: center">
                                                <form method="post" id="form<?php echo $schuelernamen[$i] ?>" action="send.php">
                                                    <div class="form-group pull-right row">
                                                        <div class="col-10">
                                                            <input type="text" autocomplete="off" class="form-control" name="nachricht" id="nachricht<?php echo $schuelernamen[$i] ?>" required placeholder="Nachricht...">

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
                    </div> -->


            <script>
                $(document).ready(function() {
                    $(".chatbutton").appendTo("#chats");
                });
            </script>




            <?php
            if (isset($_POST["submit$schuelernamen[$i]"])) {
                $_SESSION["schueler"] = $schueleranfragen[$i];
                $_SESSION["i"] = $i;
            ?>
                <script>
                    document.getElementById("chat<?php echo $schuelernamen[$i] ?>").style.background = "#bfbfbf";
                    document.getElementById("chat<?php echo $schuelernamen[$i] ?>").style.color = "black";
                    document.getElementById("chat<?php echo $schuelernamen[$i] ?>").style.border = "none";

                    if ($(window).width() < 768) {
                        document.getElementById("chats").style.display = "none";
                        document.getElementById("topnav").style.display = "none";
                        document.getElementById("wrapper").style.display = "inline";
                        document.getElementById("wrapper").style.height = "550px";
                        document.getElementById("send").style.display = "inline";
                        document.getElementById("padding").style.display = "none";
                        document.getElementsByClassName("navi").style.opacity = "0";

                        function ReplaceContentInContainer(id, content) {
                            var container = document.getElementById(id);
                            container.innerHTML = content;
                        }

                        ReplaceContentInContainer("visiblecontainer", "<div class='row' style='background: white'><div class='col-12' style='border: 1px solid #f7f7f7'><div id='send'><br> <div class='chatbar' style='text-align: center'><form method='post' id='formnachricht' action='send.php'><div class='form-group pull-right row'><div class='col-10'><input type='text' autocomplete='off' class='form-control' id='nachricht' name='nachricht' required placeholder='Nachricht...'></div><div class='col-1'><button class='btn btn-block btn-outline-dark' type='submit' id='sendbutton' name=''>Senden</button></div></div></form></div></div></div></div>");
                    }
                </script>
            <?php
            }

            ?>

    <?php
            //echo "<script>var option=document.createElement(\"option\");option.text='$schuelernamen[$i]';option.name='$schuelernamen[$i]';document.getElementById('schuelerchat').add(option);</script>";

            // $sendchat = "sendchat".$i;
            // if (isset($_POST[$sendchat])) {
            //     $nachricht = $_POST["nachricht"];
            //     $sql = "INSERT INTO chat (lehrerid, schuelerid, chat, von) VALUES (?, ?, ?, ?)";
            //     $stmt = $mysql->prepare($sql);
            //     $stmt->execute([$schueleranfragen[$i], $schuelerid, $nachricht, $schuelerid]);
            //     echo $schueleranfragen[$i];
            // }

        } //schleife schließt
    }

    // if($_SESSION["i"] == null) {  // funktioniert beim ersten nicht
    //     ?>
         <script>
    //         document.getElementById("wrapper").style.display = "none";
    //         document.getElementById("send").style.display = "none";
    //     </script>
         <?php
    // }
    ?>

    </div> <!-- Container schließt-->

    <script>
        $('.invisible').hide();
    </script>

    <script>
        window.setInterval(function() {
            $.ajax({ //create an ajax request to display.php
                type: "GET",
                url: "loadchat.php",
                dataType: "html", //expect html to be returned                
                success: function(response) {
                    $("#wrapper").html(response);
                    //alert(response);
                }
            });

        }, 1000);


        $('#formnachricht').submit(function(e) {
            e.preventDefault();

            $.post(
                'send.php',

                {
                    nachricht: $('#nachricht').val(),
                },
                function(result) {
                    if (result == "success") {

                    } else {
                        $('#wrapper').val("Error");
                    }
                }
            );

            $('#nachricht').val("");
            $('#nachricht').focus();
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
    </script>

    <script>

    </script>



</body>

</html>