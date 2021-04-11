<?php
function h($string = "") {
    return htmlspecialchars($string);
}

function is_post_request() {
    return $_SERVER["REQUEST_METHOD"] === "POST";
}

function url_for($script) {
    return WWW_ROOT.$script;
}

function redirect_to($url) {
    header("Location:".$url);
    exit;
}
?>