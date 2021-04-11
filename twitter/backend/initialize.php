<?php
ob_start();

date_default_timezone_set("Europe/Berlin");

session_start();

include "classes/database.php";
include "classes/formSanitizer.php";
include "classes/accounts.php";
include "classes/konstanten.php";
include "classes/user.php";

// spl_autoload_register(function($class) {
//     require_once "classes/$class.php";
// });

define("DB_HOST", "localhost");
define("DB_NAME", "twitter");
define("DB_USER", "root");
define("DB_PASS", "");
//define("BASE_URL", "http://localhost/twitter/");


$public_end = strpos($_SERVER["SCRIPT_NAME"], "/frontend") + 9;
$doc_root = substr($_SERVER["SCRIPT_NAME"], 0, $public_end);
define("WWW_ROOT", $doc_root);

$loadUser = new User;

$account = new Account;

include "functions.php";

?>