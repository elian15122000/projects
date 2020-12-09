<?php
session_start();
require("mysql.php");
$username = $_SESSION["username"];

$sql = "SELECT id FROM users WHERE username = '$username'";
foreach ($mysql->query($sql) as $row) {
    $lehrerid = $row["id"];
}

$i = $_SESSION["i"];
$schuelerid = $_SESSION["schueler"];

$sql = "SELECT username FROM users WHERE id = '$schuelerid'";
foreach ($mysql->query($sql) as $row) {
    $schuelername = $row["username"];
}

// echo "<div class='position-fixed' style='text-align: center'>$schuelername</div>";
$sql = "SELECT von, chat, uhrzeit FROM chat WHERE lehrerid = '$lehrerid' AND schuelerid = '$schuelerid'";
echo "<div class='position-fixed'><a href='lehrerchat.php'>Back</a></div>";
foreach ($mysql->query($sql) as $row) {
    if ($row["chat"] != "") {
        if ($row["von"] == $schuelerid) {
            echo "<div class='float-left nachrichtlinks speech-bubble-left speech-bubble col-11' style='margin-left: 3%; margin-top: 0%; margin-bottom: 0%'><b>" . $row["chat"] . "</b></div>";
            echo "<br><br><br><br><br>";
        } else {
            echo "<div class='float-right nachrichtrechts speech-bubble-right speech-bubble col-11' style='margin-right: 3%; margin-top: 0%; margin-bottom: 0%'><b>" . $row["chat"] . "</b></div>";
            echo "<br><br><br><br><br>";
        }
    }
}

echo "<script>$('#wrapper').animate({
    scrollTop: $('#wrapper')[0].scrollHeight
}, 500);</script>";

?>