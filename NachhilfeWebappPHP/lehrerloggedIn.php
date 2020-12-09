<?php
// header('Cache-Control: no cache'); //no cache
// session_cache_limiter('private_no_expire');
session_start();
require("mysql.php");
//session_cache_limiter('public'); // works too
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit;
}
$username = $_SESSION["username"];

$sql = "SELECT ROLE FROM users WHERE username = '$username'";
foreach ($mysql->query($sql) as $row) {
    //echo "Angemeldet als: ".$row["ROLE"];
    $result = $row["ROLE"];
    $_SESSION["role"] = $result;
}

// $previous = "javascript:history.go(-1)";
// if(isset($_SERVER['HTTP_REFERER'])) {
//     $previous = $_SERVER['HTTP_REFERER'];
// }

function build_calendar($month, $year)
{
    $username = $_SESSION["username"];
    $mysqli = new mysqli('localhost', 'dhbwweb_root', 'Elian1512', 'dhbwweb_tutoplantest');


    // Create array containing abbreviations of days of week.
    $daysOfWeek = array('Mo', 'Di', 'Mi', 'Do', 'Fr', 'Sa', 'So');
    // What is the first day of the month in question?
    $firstDayOfMonth = mktime(0, 0, 0, $month, 1, $year);
    // How many days does this month contain?
    $numberDays = date('t', $firstDayOfMonth);
    // Retrieve some information about the first day of the
    // month in question.
    $dateComponents = getdate($firstDayOfMonth);
    // What is the name of the month in question?
    $monthName = $dateComponents['month'];
    // What is the index value (0-6) of the first day of the
    // month in question.
    $dayOfWeek = $dateComponents['wday'];
    // Create the table tag opener and day headers
    if ($dayOfWeek == 0) {
        $dayOfWeek = 6;
    } else {
        $dayOfWeek = $dayOfWeek - 1;
    }
    $datetoday = date('Y-m-d');
    $monat = date('m');
    if ($monat == 1) {
        $monat = "Januar";
    } elseif ($monat == 2) {
        $monat = "Februar";
    } elseif ($monat == 3) {
        $monat = "März";
    } elseif ($monat == 4) {
        $monat = "April";
    } elseif ($monat == 5) {
        $monat = "Mai";
    } elseif ($monat == 6) {
        $monat = "Juni";
    } elseif ($monat == 7) {
        $monat = "Juli";
    } elseif ($monat == 8) {
        $monat = "August";
    } elseif ($monat == 9) {
        $monat = "September";
    } elseif ($monat == 10) {
        $monat = "Oktober";
    } elseif ($monat == 11) {
        $monat = "November";
    } elseif ($monat == 12) {
        $monat = "Dezember";
    }

    $calendar = "<div class='tablewrapper'>";
    $calendar .= "<table class='table table-bordered table-responsive-sm tabelle'>";
    $calendar .= "<center><h1>Dein Kalender</h1>";
    $calendar .= "<a class='btn btn-xs' href='?month=" . date('m', mktime(0, 0, 0, $month - 1, 1, $year)) . "&year=" . date('Y', mktime(0, 0, 0, $month - 1, 1, $year)) . "'><svg width='1em' height='1em' viewBox='0 0 16 16' class='bi bi-chevron-double-left' fill='currentColor' xmlns='http://www.w3.org/2000/svg'>
    <path fill-rule='evenodd' d='M8.354 1.646a.5.5 0 0 1 0 .708L2.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z'/>
    <path fill-rule='evenodd' d='M12.354 1.646a.5.5 0 0 1 0 .708L6.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z'/>
  </svg></a> ";
    $calendar .= " <a class='btn btn-xs btn-outline-dark' href='?month=" . date('m') . "&year=" . date('Y') . "'>$monat <!-- $monthName --> $year</a> ";

    $calendar .= "<a class='btn btn-xs' href='?month=" . date('m', mktime(0, 0, 0, $month + 1, 1, $year)) . "&year=" . date('Y', mktime(0, 0, 0, $month + 1, 1, $year)) . "'><svg width='1em' height='1em' viewBox='0 0 16 16' class='bi bi-chevron-double-right' fill='currentColor' xmlns='http://www.w3.org/2000/svg'>
    <path fill-rule='evenodd' d='M3.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L9.293 8 3.646 2.354a.5.5 0 0 1 0-.708z'/>
    <path fill-rule='evenodd' d='M7.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L13.293 8 7.646 2.354a.5.5 0 0 1 0-.708z'/>
  </svg></a></center><br>";

    $calendar .= "<tr>";
    // Create the calendar headers
    foreach ($daysOfWeek as $day) {
        $calendar .= "<th  class='header'>$day</th>";
    }
    // Create the rest of the calendar
    // Initiate the day counter, starting with the 1st.

    $currentDay = 1;
    $calendar .= "</tr><tr>";

    // The variable $dayOfWeek is used to
    // ensure that the calendar
    // display consists of exactly 7 columns.

    if ($dayOfWeek > 0) {
        for ($k = 0; $k < $dayOfWeek; $k++) {
            $calendar .= "<td  class='empty'></td>";
        }
    }

    $month = str_pad($month, 2, "0", STR_PAD_LEFT);

    while ($currentDay <= $numberDays) {

        // Seventh column (Saturday) reached. Start a new row.

        if ($dayOfWeek == 7) {

            $dayOfWeek = 0;
            $calendar .= "</tr><tr>";
        }

        $currentDayRel = str_pad($currentDay, 2, "0", STR_PAD_LEFT);
        $date = "$year-$month-$currentDayRel";

        $dayname = strtolower(date('l', strtotime($date)));
        $eventNum = 0;
        $today = $date == date('Y-m-d') ? "today" : "";
        if ($dayname == "" || $dayname == "") {
            $calendar .= "<td><h6>$currentDay</h6> <button class='btn btn-danger btn-xs'></button>"; //Holiday
        } elseif ($date < date('Y-m-d')) {
            $calendar .= "<td><h6>$currentDay</h6> <button class='btn btn-danger btn-xs'></button>";  //abgelaufener Tag
        } else {
            //grade 9 slots
            $totalbookings = checkSlots($mysqli, $date);
            if ($totalbookings == 9) {
                $calendar .= "<td class='$today'><h6>$currentDay</h6> <a href='#' class='btn btn-danger btn-xs'>-</a>"; //voller Tag
            } else {
                $availableslots = 9 - $totalbookings;
                $calendar .= "<td class='$today'><h6>$currentDay</h6> <a href='book.php?date=" . $date . "' onclick=reload() class='btn btn-success btn-xs' onclick='location.reload()'></a><!-- <small><i>$availableslots Stunden übrig</i></small> -->";
                $calendar .= "<script>function reload() { location.href = 'http://localhost/book2.php?date=$date';location.reload();}</script>";
            }
        }

        $calendar .= "</td>";
        // Increment counters
        $currentDay++;
        $dayOfWeek++;
    }
    // Complete the row of the last week in month, if necessary

    if ($dayOfWeek != 7) {
        $remainingDays = 7 - $dayOfWeek;
        for ($l = 0; $l < $remainingDays; $l++) {
            $calendar .= "<td class='empty'></td>";
        }
    }
    $calendar .= "</tr>";
    $calendar .= "</table>";
    $calendar .= "</div>";
    echo $calendar;
}

?>

<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous">
    </script>
    <script src="jquery-3.5.1.min.js"></script>
    <link rel="stylesheet" href="loggedin.css">

</head>


<body style="background: #f7f7f7">
    <div class="d-flex flex-column flex-md-row align-items-center p-3 px-md-4 mb-3 border-bottom shadow-sm fixed-top navi">
        <h5 class="my-0 mr-md-auto font-weight-normal">
            <img src="Logo2.png" width="100" height="20" class="d-inline-block align-top" alt="">
        </h5>
        <nav class="my-2 my-md-0 mr-md-3 listings">
            <a class="p-2 text-light" href="lehrerchat.php">Chat</a>
            <a class="p-2 text-light" href="support.php">Support</a>
            <a class="btn einstellungen">
                <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-gear-fill" fill="white" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M9.405 1.05c-.413-1.4-2.397-1.4-2.81 0l-.1.34a1.464 1.464 0 0 1-2.105.872l-.31-.17c-1.283-.698-2.686.705-1.987 1.987l.169.311c.446.82.023 1.841-.872 2.105l-.34.1c-1.4.413-1.4 2.397 0 2.81l.34.1a1.464 1.464 0 0 1 .872 2.105l-.17.31c-.698 1.283.705 2.686 1.987 1.987l.311-.169a1.464 1.464 0 0 1 2.105.872l.1.34c.413 1.4 2.397 1.4 2.81 0l.1-.34a1.464 1.464 0 0 1 2.105-.872l.31.17c1.283.698 2.686-.705 1.987-1.987l-.169-.311a1.464 1.464 0 0 1 .872-2.105l.34-.1c1.4-.413 1.4-2.397 0-2.81l-.34-.1a1.464 1.464 0 0 1-.872-2.105l.17-.31c.698-1.283-.705-2.686-1.987-1.987l-.311.169a1.464 1.464 0 0 1-2.105-.872l-.1-.34zM8 10.93a2.929 2.929 0 1 0 0-5.86 2.929 2.929 0 0 0 0 5.858z" />
                </svg>
            </a>
            <a class="btn btn-danger" href="logout.php">Logout</a>
        </nav>

    </div>
    <?php
    $username = $_SESSION["username"];
    $sql = "SELECT id FROM users WHERE username = '$username'";
    foreach ($mysql->query($sql) as $row) {
        $lehrerid = $row["id"];
    }

    $sql = "SELECT EMAIL FROM users WHERE username = '$username'";
    foreach ($mysql->query($sql) as $row) {
        //echo $row["ROLE"];
        $_SESSION['email'] = $row["EMAIL"];
    }

    $schueleranfragen = array();

    $sql = "SELECT schuelerid FROM calendar_zugriff WHERE lehrerid = '$lehrerid'";
    foreach ($mysql->query($sql) as $row) {
        $schuelerid = $row["schuelerid"];
        $schueleranfragen[] = $row["schuelerid"];
    }

    $sql = "SELECT anfrage FROM calendar_zugriff WHERE lehrerid = '$lehrerid'";
    foreach ($mysql->query($sql) as $row) {
        $anfragen[] = $row["anfrage"];
    }

    $sql = "SELECT duration FROM lehrereinstellungen WHERE lehrerusername = '$username'";
    foreach ($mysql->query($sql) as $row) {
        $aktuelleduration = $row["duration"];
    }

    $sql = "SELECT cleanup FROM lehrereinstellungen WHERE lehrerusername = '$username'";
    foreach ($mysql->query($sql) as $row) {
        $aktuellescleanup = $row["cleanup"];
    }

    $sql = "SELECT start FROM lehrereinstellungen WHERE lehrerusername = '$username'";
    foreach ($mysql->query($sql) as $row) {
        $aktuellerstart = $row["start"];
    }

    $sql = "SELECT end FROM lehrereinstellungen WHERE lehrerusername = '$username'";
    foreach ($mysql->query($sql) as $row) {
        $aktuellerend = $row["end"];
    }

    for ($i = 0; $i < count($schueleranfragen); $i++) {
        $sql = "SELECT username FROM users WHERE id = '$schueleranfragen[$i]'";
        foreach ($mysql->query($sql) as $row) {
            $schuelerusername[] = $row["username"];
        }
    }


    $sql = "SELECT calendarid FROM booking WHERE userid = '$lehrerid'";
    foreach ($mysql->query($sql) as $row) {
        $calendarid[] = $row["calendarid"];
    }

    if ($calendarid != null) {
        for ($i = 0; $i < count($calendarid); $i++) {
            $sql = "SELECT name FROM calendar WHERE id = '$calendarid[$i]'";
            foreach ($mysql->query($sql) as $row) {
                $gebuchtenamen[] = $row["name"];
            }

            $sql = "SELECT email FROM calendar WHERE id = '$calendarid[$i]'";
            foreach ($mysql->query($sql) as $row) {
                $gebuchteemails[] = $row["email"];
            }

            $sql = "SELECT beschreibung FROM calendar WHERE id = '$calendarid[$i]'";
            foreach ($mysql->query($sql) as $row) {
                $beschreibung[] = $row["beschreibung"];
            }

            $sql = "SELECT date FROM calendar WHERE id = '$calendarid[$i]'";
            foreach ($mysql->query($sql) as $row) {
                $gebuchtedaten[] = $row["date"];
            }

            $sql = "SELECT timeslot FROM calendar WHERE id = '$calendarid[$i]'";
            foreach ($mysql->query($sql) as $row) {
                $timeslot[] = $row["timeslot"];
            }
        }  // rolle noch automatisch einspeichern von user mit select 
    }
    ?>
    <div class="navigation">
    </div>
    <div class="container-fluid">
        <!-- <h1>Willkommen, <?php echo $_SESSION["username"] ?> </h1> -->

        <div class="row">
            <div class="col-md-3">
                <div class="col-md-12" style="background: white">
                    <br>
                    <h2>Anfragen</h2>
                    <?php
                    for ($i = 0; $i < count($schueleranfragen); $i++) {

                        if ($anfragen[$i] == 0) {
                            //echo $schueleranfragen[$i];
                            echo '<form id="anfrage" method="post">
                                        <label for="zugriffsbutton">' . $schuelerusername[$i] . ' annehmen?</label>
                                        <button class="btn" id="zugriffsbutton" type="submit" name="submit">
                                            <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-check-square" fill="green" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" d="M14 1H2a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z"/>
                                                <path fill-rule="evenodd" d="M10.97 4.97a.75.75 0 0 1 1.071 1.05l-3.992 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425a.236.236 0 0 1 .02-.022z"/>
                                            </svg>
                                          </button>
                                          <button class="btn" id="ablehnbutton" type="submit" name="ablehnen">
                                            <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-x-square" fill="red" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" d="M14 1H2a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z"/>
                                                <path fill-rule="evenodd" d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                                            </svg>
                                        </button>
                                        </form>';
                            if (isset($_POST['submit'])) {
                                for ($i = 0; $i < count($schueleranfragen); $i++) {
                                    $stmt = "UPDATE calendar_zugriff SET anfrage = true WHERE lehrerid = '$lehrerid' AND schuelerid = '$schueleranfragen[$i]'";
                                    if ($mysql->query($stmt)) {
                                        //echo "true";
                    ?>
                                        <script>
                                            document.getElementById("anfrage").remove();
                                            alert("Schüler Erlaubnis gegeben amk");
                                        </script>
                            <?php
                                    } else {
                                        echo "false";
                                    }
                                }
                            }
                        } else {
                            echo "<p>Du hast derzeit keine Anfragen zur Einsicht deines Kalenders.</p>";
                        }
                        if (isset($_POST['ablehnen'])) {
                            //in datenbank speichern ob abgelehnt wurde 
                            ?>
                            <script>
                                document.getElementById("anfrage").remove();
                                alert("Schüler keine Erlaubnis gegeben amk");
                            </script>
                    <?php
                        }
                    }


                    ?>
                    <br>
                </div>
            </div>
            <div class="col-md-6">
                <div class="col-md-12" style="background: white">
                    <br>
                    <?php

                    function checkSlots($mysqli, $date)
                    {
                        $username = $_SESSION["username"];
                        //$username .= "_booking";
                        $stmt = $mysqli->prepare("SELECT calendar.* FROM `calendar` INNER JOIN booking ON booking.calendarid = calendar.id INNER JOIN users ON booking.userid = users.id WHERE users.username = '$username' AND date = ?");
                        $stmt->bind_param('s', $date);
                        $totalbookings = 0;
                        if ($stmt->execute()) {
                            $result = $stmt->get_result();
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $totalbookings++;
                                }
                                $stmt->close();
                            }
                        }
                        return $totalbookings;
                    }

                    ?>


                    <div id="myModal" class="modal fade" role="dialog">
                        <div class="modal-dialog modal-dialog-centered">

                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">Einstellungen für <?php echo $username ?></h4>
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <form action="" method="post">
                                                <div class="form-group">
                                                    <label for="">Länge pro Nachhilfestunde</label>
                                                    <input type="text" value="<?php echo $aktuelleduration ?>" name="duration" id="duration" class="form-control">
                                                </div>

                                                <div class="form-group">
                                                    <label for="">Pause zwischen Nachhilfestunden</label>
                                                    <input type="text" value="<?php echo $aktuellescleanup ?>" name="cleanup" id="duration" class="form-control">
                                                </div>

                                                <div class="form-group">
                                                    <label for="">Beginn</label>
                                                    <input type="text" value="<?php echo $aktuellerstart ?>" name="start" id="duration" class="form-control">
                                                </div>

                                                <div class="form-group">
                                                    <label for="">Feierabend</label>
                                                    <input type="text" value="<?php echo $aktuellerend ?>" name="end" id="duration" class="form-control">
                                                </div>

                                                <div class="form-group">
                                                    <label for="">Freie Tage (noch nicht implementiert)</label>
                                                    <div class="form-check">

                                                        <div class="form-check form-check-inline">
                                                            <input type="checkbox" value="Montag" name="freiertag" id="montag" class="form-check-input">
                                                            <label class="form-check-label" for="montag">Montag</label>
                                                        </div>
                                                        <div class="form-check form-check-inline">
                                                            <input type="checkbox" value="Dienstag" name="freiertag" id="dienstag" class="form-check-input">
                                                            <label class="form-check-label" for="dienstag">Dienstag</label>
                                                        </div>
                                                        <div class="form-check form-check-inline">
                                                            <input type="checkbox" value="Mittwoch" name="freiertag" id="mittwoch" class="form-check-input">
                                                            <label class="form-check-label" for="mittwoch">Mittwoch</label>
                                                        </div>
                                                        <div class="form-check form-check-inline">
                                                            <input type="checkbox" value="Donnerstag" name="freiertag" id="donnerstag" class="form-check-input">
                                                            <label class="form-check-label" for="donnerstag">Donnerstag</label>
                                                        </div>
                                                        <div class="form-check form-check-inline">
                                                            <input type="checkbox" value="Freitag" name="freiertag" id="freitag" class="form-check-input">
                                                            <label class="form-check-label" for="freitag">Freitag</label>
                                                        </div>
                                                        <div class="form-check form-check-inline">
                                                            <input type="checkbox" value="Samstag" name="freiertag" id="samstag" class="form-check-input">
                                                            <label class="form-check-label" for="samstag">Samstag</label>
                                                        </div>
                                                        <div class="form-check form-check-inline">
                                                            <input type="checkbox" value="Sonntag" name="freiertag" id="sonntag" class="form-check-input">
                                                            <label class="form-check-label" for="sonntag">Sonntag</label>
                                                        </div>


                                                    </div>
                                                </div>

                                                <div class="form-group pull-right">
                                                    <button class="btn btn-primary btn-block" type="submit" name="einstellungensubmit">Speichern</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
                    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
                    <script>
                        $(".einstellungen").click(function() {
                            $("#myModal").modal("show");
                        })

                        $(".buchungen").click(function() {
                            $("#myBuchungen").modal("show");
                        })
                    </script>

                    <?php

                    if (isset($_POST['einstellungensubmit'])) {
                        $eingestellteduration = $_POST['duration'];
                        $eingestelltecleanup = $_POST['cleanup'];
                        $eingestelltestart = $_POST['start'];
                        $eingestellteend = $_POST['end'];

                        $statement = $mysql->prepare("UPDATE lehrereinstellungen SET duration = ?, cleanup = ?, start = ?, end = ? WHERE lehrerusername = ?");
                        $statement->execute(array($eingestellteduration, $eingestelltecleanup, $eingestelltestart, $eingestellteend, $username));
                    }

                    $dateComponents = getdate();
                    if (isset($_GET['month']) && isset($_GET['year'])) {
                        $month = $_GET['month'];
                        $year = $_GET['year'];
                    } else {
                        $month = $dateComponents['mon'];
                        $year = $dateComponents['year'];
                    }


                    echo build_calendar($month, $year);
                    ?>
                    <br>

                    <div class="row">
                        <div class="col-md-4">
                            <h4 style="text-align: center;">Heutige Termine</h4>
                            <ul class="list-group" style="margin-left: 5%;">

                                <?php
                                if ($gebuchtenamen != null) {
                                    for ($i = 0; $i < count($gebuchtenamen); $i++) {
                                        if ($gebuchtedaten[$i] == date('Y-m-d')) {
                                ?><br>

                                            <li>
                                                <?php
                                                $today = date("H:i:s");
                                                if ($timeslot[$i] <= $today) {
                                                    echo "<s>";
                                                    echo /*date('d.m.Y', strtotime($gebuchtedaten[$i])) .*/ " Uhrzeit: ";
                                                    echo $timeslot[$i] . " ";
                                                    echo "<br>";
                                                    if ($gebuchtenamen[$i] == $username) {
                                                        echo "selber geblockt!";
                                                    } else { 
                                                        echo "Schüler: <a href='#' class='$gebuchtenamen[$i]_click$i'>" . $gebuchtenamen[$i] . " </a>";
                                                        echo "<br>";
                                                        echo "E-Mail: " . $gebuchteemails[$i] . " ";
                                                    }
                                                    echo "</s>";
                                                    ?>
                                                        <div id="<?php echo $gebuchtenamen[$i]; ?><?php echo $i ?>" class="modal fade" role="dialog">
                                                            <div class="modal-dialog modal-dialog-centered">

                                                                <!-- Modal content-->
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h4 class="modal-title">Termindetails (vorbei <span style="color: red"> <?php echo $timeslot[$i] ?> </span>)</h4>
                                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <div class="row">
                                                                            <div class="col-md-12">
                                                                                <p>Schüler: <?php echo $gebuchtenamen[$i]; ?><br>
                                                                                    <?php echo "E-Mail: " . $gebuchteemails[$i] . " "; ?><br>
                                                                                    <?php echo "Beschreibung: " . $beschreibung[$i] . " "; ?><br>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </div>
                                                        <script>
                                                            $(".<?php echo $gebuchtenamen[$i] ?>_click<?php echo $i ?>").click(function() {
                                                                $("#<?php echo $gebuchtenamen[$i] ?><?php echo $i ?>").modal("show");
                                                            })
                                                        </script>
                                                <?php
                                                } else {
                                                    echo /*date('d.m.Y', strtotime($gebuchtedaten[$i])) .*/ " Uhrzeit: ";
                                                    echo $timeslot[$i] . " ";
                                                    echo "<br>";
                                                    if ($gebuchtenamen[$i] == $username) {
                                                        echo "selber geblockt!";
                                                    } else {
                                                        echo "Schüler: <b><a href='#' class='$gebuchtenamen[$i]_click$i'>" . $gebuchtenamen[$i] . " </b></a>";
                                                        echo "<br>";

                                                ?>
                                                        <div id="<?php echo $gebuchtenamen[$i]; ?><?php echo $i ?>" class="modal fade" role="dialog">
                                                            <div class="modal-dialog modal-dialog-centered">

                                                                <!-- Modal content-->
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h4 class="modal-title">Termindetails (heute <?php echo $timeslot[$i] ?>)</h4>
                                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <div class="row">
                                                                            <div class="col-md-12">
                                                                                <p>Schüler: <?php echo $gebuchtenamen[$i]; ?><br>
                                                                                    <?php echo "E-Mail: " . $gebuchteemails[$i] . " "; ?><br>
                                                                                    <?php echo "Beschreibung: " . $beschreibung[$i] . " "; ?><br>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </div>
                                                        <script>
                                                            $(".<?php echo $gebuchtenamen[$i] ?>_click<?php echo $i ?>").click(function() {
                                                                $("#<?php echo $gebuchtenamen[$i] ?><?php echo $i ?>").modal("show");
                                                            })
                                                        </script>
                                                <?php
                                                    }
                                                }



                                                ?>
                                                <br>
                                            </li>
                                <?php
                                        }
                                    }
                                }
                                ?>

                            </ul>
                            <br>
                        </div>

                        <div class="col-md-4">

                        </div>

                        <div class="col-md-4">
                            <h4 style="text-align: center;">Diese Woche Termine</h4>
                            <ul class="list-group" style="margin-left: 5%;">

                                <?php
                                if ($gebuchtenamen != null) {

                                    for ($i = 0; $i < count($gebuchtenamen); $i++) {
                                        $date1 = date_create(date("Y-m-d"));
                                        $date2 = date_create($gebuchtedaten[$i]);
                                        $diff = date_diff($date1, $date2);
                                        //echo $diff->format("%R%a");
                                        if ($diff->format("%R%a") == '+1' || $diff->format("%R%a") == '+2' || $diff->format("%R%a") == '+3' || $diff->format("%R%a") == '+4' || $diff->format("%R%a") == '+5' || $diff->format("%R%a") == '+6' || $diff->format("%R%a") == '+7') {
                                ?><br>

                                            <li>
                                                <?php
                                                echo date('d.m.Y', strtotime($gebuchtedaten[$i]));
                                                echo "<br>";
                                                if ($gebuchtenamen[$i] == $username) {
                                                    echo "selber geblockt!";
                                                } else {
                                                    echo "Schüler: <b><a id='$gebuchtenamen[$i]_woche$i' class='termininfo'>" . $gebuchtenamen[$i] . " </b></a>";
                                                    echo "<br>";
                                                    echo "E-Mail: " . $gebuchteemails[$i] . " ";
                                                }
                                                ?>
                                                <div id="<?php echo $gebuchtenamen[$i]; ?><?php echo $i ?>woche" class="modal fade" role="dialog">
                                                    <div class="modal-dialog modal-dialog-centered">

                                                        <!-- Modal content-->
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h4 class="modal-title">Termindetails (<?php echo date('d.m.Y', strtotime($gebuchtedaten[$i])) ?>)</h4>
                                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <p>Schüler: <?php echo $gebuchtenamen[$i]; ?><br>
                                                                            <?php echo "Uhrzeit: " .$timeslot[$i] ?><br>
                                                                            <?php echo "E-Mail: " . $gebuchteemails[$i] . " "; ?><br>
                                                                            <?php echo "Beschreibung: " . $beschreibung[$i] . " "; ?><br>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                                <script>
                                                    $("#<?php echo $gebuchtenamen[$i] ?>_woche<?php echo $i ?>").click(function() {
                                                        $("#<?php echo $gebuchtenamen[$i] ?><?php echo $i ?>woche").modal("show");
                                                    })
                                                </script>
                                                <br>
                                            </li>
                                <?php
                                        }
                                    }
                                }
                                ?>

                            </ul>
                            <br>
                        </div>







                    </div>
                </div>

            </div>

            <div class="col-md-3">
                <div class="col-md-12" style="background: white">
                    <br>
                    <h2>Nachrichten</h2>
                    <!-- <ul class="list-group"> -->

                    <!-- <?php
                            if ($gebuchtenamen != null) {
                                for ($i = 0; $i < count($gebuchtenamen); $i++) {
                                    if ($gebuchtedaten[$i] == date('Y-m-d')) {
                            ?><br>

                        <p>
                            <?php
                                        echo date('d.m.Y', strtotime($gebuchtedaten[$i])) . " von ";
                                        echo $timeslot[$i] . " ";
                                        echo "<br>";
                                        if ($gebuchtenamen[$i] == $username) {
                                            echo "selber geblockt!";
                                        } else {
                                            echo "Schüler: " . $gebuchtenamen[$i] . " ";
                                            echo "<br>";
                                            echo "E-Mail: " . $gebuchteemails[$i] . " ";
                                        }

                            ?>
                            <br>
                                    </p>
                        <?php
                                    }
                                }
                            }
                        ?> -->

                    <!-- </ul> -->
                    <br>
                </div>
            </div>

        </div> <!-- row schließt -->
    </div> <!-- container schließt -->

    </div>
    </div>
    </div>
    <script>
        var lastScrollTop = 0;
        $(window).scroll(function(event) {
            var st = $(this).scrollTop();
            if ($(document).width() <= 750) {
                if (st > lastScrollTop) {
                    $(".listings").hide();
                } else {
                    $(".listings").show();
                }
                if (st <= 0) {
                    $(".listings").show();
                } else if ($(window).scrollTop() + $(window).height() == $(document).height()) {
                    $(".listings").hide();
                }
                lastScrollTop = st;
            }
        });
    </script>
</body>

</html>