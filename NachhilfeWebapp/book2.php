<?php
header('Cache-Control: no cache'); //no cache
session_cache_limiter('private_no_expire'); // works
//session_cache_limiter('public'); // works too
session_start();
if(!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit;
}
require("mysql.php");
//$username = $_SESSION["lehrerusername"];
$email = $_SESSION['email'];


$username = $_SESSION['lehrerusername'];


$sql = "SELECT duration FROM lehrereinstellungen WHERE lehrerusername = '$username'";
foreach($mysql->query($sql) as $row) {
    $duration = $row["duration"];
}

$sql = "SELECT cleanup FROM lehrereinstellungen WHERE lehrerusername = '$username'";
foreach($mysql->query($sql) as $row) {
    $cleanup = $row["cleanup"];
}

$sql = "SELECT start FROM lehrereinstellungen WHERE lehrerusername = '$username'";
foreach($mysql->query($sql) as $row) {
    $start = $row["start"];
}

$sql = "SELECT end FROM lehrereinstellungen WHERE lehrerusername = '$username'";
foreach($mysql->query($sql) as $row) {
    $end = $row["end"];
}


$mysqli = new mysqli('localhost', 'dhbwweb_root', 'Elian1512', 'dhbwweb_tutoplantest');
if(isset($_GET['date'])){
    $date = $_GET['date'];
    $_SESSION['date'] = $date;
    $stmt = $mysqli->prepare("SELECT calendar.* FROM calendar INNER JOIN booking ON booking.calendarid = calendar.id INNER JOIN users ON booking.userid = users.id WHERE users.username = '$username' AND date = ?");
    $stmt->bind_param('s', $date);
    $bookings = array();
    if($stmt->execute()){
        $result = $stmt->get_result();
        if($result->num_rows>0){
            while($row = $result->fetch_assoc()){
                $bookings[] = $row['timeslot'];
            }
            $stmt->close();
        }
    }
}




if(isset($_POST['submit'])){
    require('mysql.php');                     
    $name = $_SESSION["username"];
    $email = $_SESSION["email"];
    $timeslot = $_POST['timeslot'];
    $beschreibung = $_POST['beschreibung'];
    $stmt = $mysqli->prepare("SELECT calendar.* FROM calendar INNER JOIN booking ON booking.calendarid = calendar.id INNER JOIN users ON booking.userid = users.id WHERE users.username = '$username' AND date = ? AND timeslot = ?");
    
    $stmt->bind_param('ss', $date, $timeslot);
    //echo "Test";
    if($stmt->execute()){
        $result = $stmt->get_result();
        if($result->num_rows > 0){
            $msg = "<div class='alert alert-danger'>Already booked!</div>";
        } else {
            $stmt = $mysqli->prepare("INSERT INTO calendar (name, email, date, timeslot, beschreibung) VALUES (?,?,?,?,?)");
            $stmt->bind_param('sssss', $name, $email, $date, $timeslot, $beschreibung);
            $stmt->execute();

            $sql = "SELECT id FROM users WHERE username = '$username'";
            foreach ($mysql->query($sql) as $row) {
                //echo "UserId ".$row["id"];
                $userid = $row["id"];
            }

            $sql = "SELECT id FROM calendar WHERE name = '$name'";
            foreach ($mysql->query($sql) as $row) {
                //echo "CalendarId ".$row["id"];
                $calendarid = $row["id"];
            }
            
            $stmt = $mysqli->prepare("INSERT INTO booking (userid, calendarid) VALUES (?,?)");
            $stmt->bind_param('ss', $userid, $calendarid);
            $stmt->execute();

            $msg = "<div class='alert alert-success'>Booking Successfull</div>";
            $bookings[] = $timeslot;
            $stmt->close();
            $mysqli->close();
        }
    }

    echo "<script>alert('Termin wurde gebucht.');
        window.location.href='schuelerloggedIn.php';
        </script>";
    
}


function timeslots($duration, $cleanup, $start, $end) {
    $start = new DateTime($start);
    $end = new DateTime($end);
    $interval = new DateInterval("PT".$duration."M");
    $cleanupInterval = new DateInterval("PT".$cleanup."M");
    $slots = array();

    for($intStart = $start; $intStart < $end; $intStart -> add($interval) -> add($cleanupInterval)) {
        $endPeriod = clone $intStart;
        $endPeriod -> add($interval);
        if($endPeriod > $end) {
            break;
        }

        $slots[] = $intStart -> format("H:i")."-".$endPeriod -> format("H:i")." Uhr"; 
    }

    return $slots;
}


?>

<!doctype html>
<html lang="de">
<script>var geklickterkalender = sessionStorage.getItem('geklickterkalender');</script>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Buchen</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <link rel="stylesheet" href="/css/main.css">
</head>

<body>
    <div class="container">
        <h1 class="text-center">Book for Date: <?php echo date('d.m.Y', strtotime($date)); ?></h1>
        <hr>
        <div class="row">
            <div class="col-md-12">
                <?php echo isset($msg)?$msg:""; ?>
            </div>
            <?php 
                    $timeslots = timeslots($duration, $cleanup, $start, $end);
                    foreach($timeslots as $ts) {
                ?>
            <div class="col-md-2">
                <div class="form-group">
                    <?php if(in_array($ts, $bookings)){ ?>
                    <button class="btn btn-danger"><?php echo $ts; ?></button>
                    <?php } else { ?>
                    <button class="btn btn-success book" data-timeslot="<?php echo $ts; ?>"><?php echo $ts; ?></button>
                    <?php } ?>

                </div>
            </div>
            <?php } ?>
        </div>
    </div>





    <div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Booking: <span id="slot"></span></h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <form action="" method="post">
                                <div class="form-group">
                                    <label for="">Timeslot</label>
                                    <input type="text" readonly name="timeslot" id="timeslot" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="">Name</label>
                                    <input disabled type="text" name="name" class="form-control" value="<?php echo $_SESSION["username"] ?>">
                                </div>
                                <div class="form-group">
                                    <label for="">E-Mail</label>
                                    <input disabled type="email" name="email" class="form-control" value="<?php echo $email ?>">
                                </div>
                                <div class="form-group">
                                    <label for="">Beschreibung</label>
                                    <input type="text" name="beschreibung" class="form-control">
                                </div>
                                <div class="form-group pull-right">
                                    <button class="btn btn-primary" type="submit" name="submit">Termin buchen</button>
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
    $(".book").click(function() {
        var timeslot = $(this).attr('data-timeslot');
        $("#slot").html(timeslot);
        $("#timeslot").val(timeslot);
        $("#myModal").modal("show");
    })
    </script>
</body>

</html>
