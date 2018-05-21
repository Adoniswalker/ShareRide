<?php


require_once "dependant/layout.php";
require_once "dependant/pro.functions.php";
use Coduo\PHPHumanizer\DateTimeHumanizer;
//Load Composer's autoloader
require 'vendor/autoload.php';

function humanize_date($date){
    return DateTimeHumanizer::difference(new \DateTime(), new \DateTime($date));
}

if (loggedin()) {
    $user_name = get_user_data('first_name');
    if (isset($_GET['error'])) {
        $error = $_GET['error'];
        echo "<p class='badge-danger' >$error</p >";
    }
    if (isset($_GET['success'])) {
        $success = $_GET['success'];
        echo "<p class='badge-success'>$success</p>";
    }
}
?>
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <?php
            echo "<table class='table table-dark table-hover table-responsive'>";
            echo "<thead><th scope='col'>Id</th><th scope='col'>Origin</th><th scope='col'>Destination</th>
            <th scope='col'>Space</th><th scope='col'>Date</th><th scope='col'>Name</th><th scope='col'>Book</th></thead>";
            try {
                $available_rides = "SELECT rd.id, rd.origin, rd.destination, rd.space, rd.date, concat(rg.first_name, ' ', rg.last_name) FROM rides rd
LEFT JOIN register rg on rg.id = rd.driver WHERE rd.date >= now() AND rd.booked='0'";
                $stmt = $conn->prepare($available_rides);
                $stmt->execute();
                while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
                    echo '<tr>';
                    echo "<th scope='row'>$row[0]</th><td>$row[1]</td><td>$row[2]</td><td>$row[3]</td><td>".humanize_date($row[4])."</td>
                    <td>$row[5]</td><td><a href='dependant/book.php?id=$row[0]'>Book</a></td>";
                    echo '</tr>';
                }
            } catch
            (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
            //            $conn = null;
            echo "</table>";
            ?>


        </div>
        <div class="col-md-4">
            <?php
            if (loggedin()) {
                include_once "dependant/giving_ride.php";
                # rides given(You were given this ride by other drivers)
                $rides_given_sql = "select concat(rg.first_name, ' ',rg.last_name), r.origin, r.destination, br.book_time
from booked_rides br left join register rg on br.driver = rg.id left join rides r on r.id = br.ride
where br.passanger = :user_id";

#given out(Rides that you gave out to other drivers)
                $rides_givenout_sql = "select concat(rg.first_name, ' ',rg.last_name), r.origin, r.destination, br.book_time
from booked_rides br left join register rg on br.passanger = rg.id left join rides r on r.id = br.ride
where br.driver=:user_id";
                $param = array("user_id" => $_SESSION['user_id']);
                $rides_given = select_db($conn, $rides_given_sql, $param);
                $rides_givenout = select_db($conn, $rides_givenout_sql, $param);
                echo "<div class='row'>";
                echo "<h4>Rides you were given</h4>";
                echo '<table class="table table-hover">';
                echo "<thead><th>Driver</th><th>From</th><th>To</th><th>Date</th></thead>";
                while ($row = $rides_given->fetch(PDO::FETCH_NUM)) {
                    echo "<tr><td>$row[0]</td><td>$row[1]</td><td>$row[2]</td><td>".humanize_date($row[3])."</td></tr>";
                }
                echo "</div>";
                echo "</div class='row'>";
                echo '</table>';
                echo "<h4>Rides You gave Out</h4>";
                echo '<table class="table table-hover">';
                echo "<thead><th>Passanger</th><th>From</th><th>To</th><th>Date</th></thead>";
                while ($row = $rides_givenout->fetch(PDO::FETCH_NUM)) {
                    echo "<tr><td>$row[0]</td><td>$row[1]</td><td>$row[2]</td><td>".humanize_date($row[3])."</td></tr>";
                }
                echo '</table>';
                echo "</div>";
            } else {
                echo "<p class='text-dark'>This website is used for sharing rides. You can invite other users to the ride or book a ride</p>";
                echo "<p class='text-dark'>You have to log in or sign up to perform the tast</p>";
                echo "<p class='text-dark'>Future rides are only accepted</p>";
            }
            ?>
        </div>
    </div>
</div>
<?php include "dependant/footer_file.php"; ?>