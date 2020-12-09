<?php
session_start();
require("mysql.php");
$username = $_SESSION["username"];

$sql = "SELECT id FROM users WHERE username = '$username'";
foreach ($mysql->query($sql) as $row) {
    $lehrerid = $row["id"];
}

$i = $_SESSION["i"];
$nachricht = $_POST["nachricht"];
$schuelerid = $_SESSION["schueler"];


$sql = "INSERT INTO chat (lehrerid, schuelerid, chat, von) VALUES (?, ?, ?, ?)";
$stmt = $mysql->prepare($sql);
$stmt->execute([$lehrerid, $schuelerid, $nachricht, $lehrerid]);

echo "<script>$('#wrapper$i').animate({
    scrollTop: $('#wrapper$i')[0].scrollHeight
}, 500);</script>";

?>