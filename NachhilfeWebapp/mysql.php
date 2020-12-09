<?php
    $host = "localhost";
    $name = "dhbwweb_tutoplantest";
    $user = "dhbwweb_root";
    $passwort = "Elian1512";

    try {
        $mysql = new PDO("mysql:host=$host;dbname=$name", $user, $passwort);
    } catch (PDOException $e) {
        echo "SQL Error: ".$e->getMessage();
    }
?>
