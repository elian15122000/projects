<?php
session_start();
require("mysql.php");
$username = $_SESSION["username"];

$sql = "SELECT id FROM users WHERE username = '$username'";
foreach ($mysql->query($sql) as $row) {
    $schuelerid = $row["id"];
}

$i = $_SESSION["i"];
$lehrerid = $_SESSION["lehrer"];

$sql = "SELECT von, chat, uhrzeit FROM chat WHERE lehrerid = '$lehrerid' AND schuelerid = '$schuelerid'";
foreach ($mysql->query($sql) as $row) {
    if ($row["von"] == $lehrerid) {
        echo "<div class='float-left nachrichtlinks speech-bubble-left nachricht' style='margin-left: 3%;'><b>" . $row["chat"] . "</b></div>";
        echo "<br><br>";
    } else {
        echo "<div class='float-right nachrichtrechts speech-bubble-right nachricht' style='margin-right: 3%;'><b>" . $row["chat"] . "</b></div>";
        echo "<br><br>";
    }
}

echo "<script>$('#wrapper$i').animate({
    scrollTop: $('#wrapper$i')[0].scrollHeight
}, 500);</script>";

?>
