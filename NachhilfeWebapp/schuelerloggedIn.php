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

    $calendar = "<div class='tablewrapper'>";
    $calendar .= "<table class='table table-bordered table-responsive-sm tabelle'>";
    $calendar .= "<center><h1><!--$monthName $year--> Dein Kalender</h1>";
    $calendar .= "<a class='btn btn-xs' href='?month=" . date('m', mktime(0, 0, 0, $month - 1, 1, $year)) . "&year=" . date('Y', mktime(0, 0, 0, $month - 1, 1, $year)) . "'><svg width='1em' height='1em' viewBox='0 0 16 16' class='bi bi-chevron-double-left' fill='currentColor' xmlns='http://www.w3.org/2000/svg'>
    <path fill-rule='evenodd' d='M8.354 1.646a.5.5 0 0 1 0 .708L2.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z'/>
    <path fill-rule='evenodd' d='M12.354 1.646a.5.5 0 0 1 0 .708L6.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z'/>
  </svg></a> ";
    $calendar .= " <a class='btn btn-xs btn-outline-dark' href='?month=" . date('m') . "&year=" . date('Y') . "'>Aktueller Monat</a> ";

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
                $calendar .= "<td class='$today'><h6>$currentDay</h6> <a href='#' class='btn btn-danger btn-xs'>All Booked</a>";
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

function build_calendar2($month, $year, $username)
{

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
    //$_SESSION['geklickt'] = "<script>document.write(sessionStorage.getItem('geklickterkalender'))</script>";

    //onclick=sessionStorage.setItem('geklickterkalender','$username');
    $calendar = "<div id='$username'>";
    //$calendar.= "<form method=get><input id=input type=text name=geklickt  /></form>";
    //$calendar.= "<script>document.getElementById('input').value = sessionStorage.getItem('geklickterkalender'); document.getElementById('input').style.display='none';</script>";
    $calendar .= "<h1 id='name'>$username</h1>";

    $calendar .= "<table class='table table-bordered'>";
    $calendar .= "<center><h2>$monthName $year</h2>";
    $calendar .= "<a class='btn btn-xs btn-primary' href='?month=" . date('m', mktime(0, 0, 0, $month - 1, 1, $year)) . "&year=" . date('Y', mktime(0, 0, 0, $month - 1, 1, $year)) . "'>Previous Month</a> ";
    $calendar .= " <a class='btn btn-xs btn-primary' href='?month=" . date('m') . "&year=" . date('Y') . "'>$monthName</a> ";

    $calendar .= "<a class='btn btn-xs btn-primary' href='?month=" . date('m', mktime(0, 0, 0, $month + 1, 1, $year)) . "&year=" . date('Y', mktime(0, 0, 0, $month + 1, 1, $year)) . "'>Next Month</a></center><br>";

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
            $totalbookings = checkSlots2($mysqli, $date);
            if ($totalbookings == 9) {
                $calendar .= "<td class='$today'><h6>$currentDay</h6> <a href='#' class='btn btn-danger btn-xs'>All Booked</a>";
            } else {
                $availableslots = 9 - $totalbookings;
                $calendar .= "<td class='$today'><h6>$currentDay</h6><a href='book2.php?date=" . $date . "' class='btn btn-success btn-xs'></a><!-- <small><i>$availableslots Stunden übrig</i></small> -->";
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
    //$calendar.="<script>function clicked() { if($username[$i] == document.getElementById('name').innerText) { console.log(document.getElementById('name').innerText)}; } </script>";
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


<body>
    <div class="d-flex flex-column flex-md-row align-items-center p-3 px-md-4 mb-3 border-bottom shadow-sm fixed-top navi">
        <h5 class="my-0 mr-md-auto font-weight-normal">
            <img src="Logo2.png" width="100" height="20" class="d-inline-block align-top" alt="">
        </h5>
        <nav class="my-2 my-md-0 mr-md-3 listings">
            <a class="p-2 text-light" href="schuelerchat.php">Chat</a>
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

    $sql = "SELECT date FROM calendar WHERE name = '$username'";
    foreach ($mysql->query($sql) as $row) {
        $gebuchtetermine[] = $row["date"];
    }

    $sql = "SELECT timeslot FROM calendar WHERE name = '$username'";
    foreach ($mysql->query($sql) as $row) {
        $gebuchtetimeslot[] = $row["timeslot"];
    }

    $sql = "SELECT id FROM calendar WHERE name = '$username'";
    foreach ($mysql->query($sql) as $row) {
        $gebuchteid[] = $row["id"];
    }
    ?>
    <h2>Buchungen</h2>

    <!-- <button class="btn btn-success buchungen">
        <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-book" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" d="M1 2.828v9.923c.918-.35 2.107-.692 3.287-.81 1.094-.111 2.278-.039 3.213.492V2.687c-.654-.689-1.782-.886-3.112-.752-1.234.124-2.503.523-3.388.893zm7.5-.141v9.746c.935-.53 2.12-.603 3.213-.493 1.18.12 2.37.461 3.287.811V2.828c-.885-.37-2.154-.769-3.388-.893-1.33-.134-2.458.063-3.112.752zM8 1.783C7.015.936 5.587.81 4.287.94c-1.514.153-3.042.672-3.994 1.105A.5.5 0 0 0 0 2.5v11a.5.5 0 0 0 .707.455c.882-.4 2.303-.881 3.68-1.02 1.409-.142 2.59.087 3.223.877a.5.5 0 0 0 .78 0c.633-.79 1.814-1.019 3.222-.877 1.378.139 2.8.62 3.681 1.02A.5.5 0 0 0 16 13.5v-11a.5.5 0 0 0-.293-.455c-.952-.433-2.48-.952-3.994-1.105C10.413.809 8.985.936 8 1.783z" />
        </svg>
    </button>

    <div id="myBuchungen" class="modal fade" role="dialog">
        <div class="modal-dialog">

            
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Buchungen</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <ul class="list-group">

                                <?php
                                for ($i = 0; $i < count($gebuchteid); $i++) {
                                    $sql = "SELECT userid FROM booking WHERE calendarid = '$gebuchteid[$i]'";
                                    foreach ($mysql->query($sql) as $row) {
                                        $gebuchtelehrerid[] = $row["userid"];
                                    }

                                    $sql = "SELECT username FROM users WHERE id = '$gebuchtelehrerid[$i]'";
                                    foreach ($mysql->query($sql) as $row) {
                                        $gebuchtelehrerusername[] = $row["username"];
                                    } ?>
                                    <li class="list-group-item"><?php
                                                                echo "Am " . $gebuchtetermine[$i] . " von ";
                                                                echo $gebuchtetimeslot[$i] . " bei ";
                                                                echo $gebuchtelehrerusername[$i];
                                                                ?></li><br><?php
                                                                        }
                                                                            ?>

                            </ul>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div> -->


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous">
    </script>
    <script>
        $(".einstellungen").click(function() {
            $("#myModal").modal("show");
        })

        $(".buchungen").click(function() {
            $("#myBuchungen").modal("show");
        })
    </script>



    <form method="post">
        <div class="form-group">
            <label for="calendars">Kalender auswähen:</label>
            <select class="form-control" name="calendars" id="calendars">
            </select>
        </div>
        <button type="submit" name="geklickterkalender" class="btn btn-primary">Anzeigen</button>
    </form>


    <form method="post">
        <div class="form-group">
            <label for="lehrerusername">Einsichtsanfrage an Nachhilfelehrer</label>
            <input type="text" class="form-control" id="lehrerusername" name="zugriff" aria-describedby="lehrerusername" placeholder="Username von Nachhilfelehrer">
            <small id="lehrerHelp" class="form-text text-muted">Gebe den Namen deines Lehrers ein, um seinen
                Kalender nach seiner Bestätigung ansehen zu können.</small>
        </div>

        <button type="submit" name="submit" class="btn btn-primary">Einsichtsanfrage senden</button>
    </form>

    <?php

    if (isset($_POST['submit'])) {
        $lehrerzugriff = $_POST['zugriff'];
        $_SESSION["lehrerzugriff"] = $lehrerzugriff;

        $sql = "SELECT ROLE FROM users WHERE username = '$lehrerzugriff'";
        foreach ($mysql->query($sql) as $row) {
            //echo $row["ROLE"];
            $result = $row["ROLE"];
        }
        $sql = "SELECT id FROM users WHERE username = '$lehrerzugriff'";
        foreach ($mysql->query($sql) as $row) {
            //echo $row["ROLE"];
            $lehrerid = $row["id"];
        }

        if ($result == "Lehrer") {
            echo "Lehrer";
        } else {
            echo "Kein Lehrer!";
        }
    }

    $sql = "SELECT id FROM users WHERE username = '$username'";
    foreach ($mysql->query($sql) as $row) {
        //echo $row["ROLE"];
        $usernameid = $row["id"];
    }

    $sql = "SELECT EMAIL FROM users WHERE username = '$username'";
    foreach ($mysql->query($sql) as $row) {
        //echo $row["ROLE"];
        $_SESSION['email'] = $row["EMAIL"];
    }

    $anfrage = NULL;
    $sql = "SELECT anfrage FROM calendar_zugriff WHERE schuelerid = '$usernameid'";
    foreach ($mysql->query($sql) as $row) {
        //echo $row["ROLE"];
        $anfrage[] = $row["anfrage"];
    }


    if (isset($_POST['submit']) && $result == "Lehrer") {
        // $stmt = "INSERT INTO calendar_zugriff (schuelerid, lehrerid, anfrage) VALUES ($usernameid, $lehrerid, false)";
        // if($mysql->query($stmt)) {
        //     echo "true";
        // } else {
        //     echo "false";
        // }    

        $sql = "INSERT INTO calendar_zugriff (schuelerid, lehrerid, anfrage) VALUES (?, ?, ?)";
        $stmt = $mysql->prepare($sql);
        $stmt->execute([$usernameid, $lehrerid, false]);

        $sql = "INSERT INTO chat (lehrerid, schuelerid) VALUES (?, ?)";
        $stmt = $mysql->prepare($sql);
        $stmt->execute([$lehrerid, $usernameid]);
    }

    for ($i = 0; $i < count($_SESSION["lehrernamen"]); $i++) {
        //echo $_SESSION["lehrernamen"][$i];
    }


    function checkSlots2($mysqli, $date)
    {

        $username = $_SESSION["lehrerusername"];
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

    $_SESSION["lehrernamen"] = array();

    if ($anfrage != NULL) {

        for ($i = 0; $i < count($anfrage); $i++) {
            if ($anfrage[$i] == true) {
                $username = $_SESSION["username"];
                $sql = "SELECT id FROM users WHERE username = '$username'";
                foreach ($mysql->query($sql) as $row) {
                    $usernameid = $row["id"];
                }

                $sql = "SELECT lehrerid FROM calendar_zugriff WHERE schuelerid = '$usernameid'";
                foreach ($mysql->query($sql) as $row) {
                    //echo $row["ROLE"];
                    $lehrerkalenderid[] = $row["lehrerid"];
                }



                $sql = "SELECT username FROM users WHERE id = '$lehrerkalenderid[$i]'";
                foreach ($mysql->query($sql) as $row) {
                    //echo $row["ROLE"];
                    $lehrerusername[] = $row["username"];
                    $_SESSION["lehrerusername"] = $lehrerusername[$i];
                    $_SESSION["lehrernamen"][] = $row["username"];
                }

                $username = $_SESSION["username"];
                $dateComponents = getdate();
                if (isset($_GET['month']) && isset($_GET['year'])) {
                    $month = $_GET['month'];
                    $year = $_GET['year'];
                } else {
                    $month = $dateComponents['mon'];
                    $year = $dateComponents['year'];
                }
                echo "<script>var option=document.createElement(\"option\");option.text='$lehrerusername[$i]';option.name='$lehrerusername[$i]';document.getElementById('calendars').add(option);</script>";

                //echo build_calendar2($month,$year, $lehrerusername[$i]);
            }
        } //schleife schließt

        if (isset($_POST["geklickterkalender"])) {
            $selected = $_POST["calendars"];
            $_SESSION['lehrerusername'] = $selected;
            //echo $selected;

            $dateComponents = getdate();

            if (isset($_GET['month']) && isset($_GET['year'])) {
                $month = $_GET['month'];
                $year = $_GET['year'];
            } else {
                $month = $dateComponents['mon'];
                $year = $dateComponents['year'];
            }
            echo build_calendar2($month, $year, $_SESSION['lehrerusername']);
        }
    } // if null schließt

    //pw reset einbauen
    ?>

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