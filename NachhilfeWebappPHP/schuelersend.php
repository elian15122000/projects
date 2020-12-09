<?php
session_start();
require("mysql.php");
$username = $_SESSION["username"];

$sql = "SELECT id FROM users WHERE username = '$username'";
foreach ($mysql->query($sql) as $row) {
    $schuelerid = $row["id"];
}

$i = $_SESSION["i"];
$nachricht = $_POST["nachricht"];
$lehrer = $_SESSION["lehrer"];

$sql = "INSERT INTO chat (lehrerid, schuelerid, chat, von) VALUES (?, ?, ?, ?)";
$stmt = $mysql->prepare($sql);
$stmt->execute([$lehrer, $schuelerid, $nachricht, $schuelerid]);

echo "<script>$('#wrapper$i').animate({
    scrollTop: $('#wrapper$i')[0].scrollHeight
}, 500);</script>";
?>