<?php
session_start();
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
        $sql = "SELECT username FROM users WHERE id = '$schueleranfragen[$i]'";
        foreach ($mysql->query($sql) as $row) {
            $schuelernamen[] = $row["username"];
            $_SESSION["schuelernamen"][] = $row["username"];
        }

        $sql = "SELECT chat FROM chat WHERE schuelerid = '$schueleranfragen[$i]' AND lehrerid = '$lehrerid'";
        foreach ($mysql->query($sql) as $row) {
            $letztenachricht = $row["chat"];
        }

        echo "<button type='submit' style='padding: 2%; border-bottom: 1px solid gray; border-radius: 0px;' class='btn btn-secondary btn-block' id='chat$schuelernamen[$i]' name='submit$schuelernamen[$i]'>
        <div class='text-align: left'>$schuelernamen[$i]<br><small><i>$letztenachricht</i></small> </div>
        </button>";
    }
}

?>