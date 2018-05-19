<?php
require_once "sesion_file.php";
function is_booked($id, $conn)
{
    $sql = "SELECT booked FROM rides WHERE id =:id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
//    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    if ($stmt->rowCount()) {
        if ($stmt->fetch()['booked'] == 0) {
            return true;
        } else {
            return false;
        }
    } else {
        echo "nothing";
        return 0;
    }
}

send_mail();
function send_mail()
{
    $to = "dennisngeno7@hotmail.com";
    $subject = "Got a ride";

    $message = "
<html>
<head>
<title>HTML email</title>
</head>
<body>
<p>This email contains HTML Tags!</p>
<table>
<tr>
<th>Firstname</th>
<th>Lastname</th>
</tr>
<tr>
<td>John</td>
<td>Doe</td>
</tr>
</table>
</body>
</html>
";

// Always set content-type when sending HTML email
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

// More headers
    $headers .= 'From: <dennisngeno7@gmail.com>' . "\r\n";
//    $headers .= 'Cc: myboss@example.com' . "\r\n";

    mail($to, $subject, $message, $headers);
}
#todo work on you cant book your Ride
function find_driver($ride_id){
    global $conn;
    $query ="select driver from rides where id =:id";
    $driver_ans = select_db($conn,$query, array(':id'=>$ride_id));
    if($driver_ans) {
        return $driver_ans->rowCount() ? $driver_ans->fetch()[0] : $driver_ans = 0;
    }else {return 0;}
}
if (loggedin()) {
    if (isset($_GET['id'])) {
        $ride = $_GET['id'];
        $user_id = $_SESSION['user_id'];
        $space_available = isset($_GET['space']) ? $_GET['space'] : 1;
        if ($user_id != find_driver($ride) && find_driver($ride)) {
            if (is_booked($ride, $conn)) {
                $sql = 'insert into booked_rides (driver, passanger, space, ride) values
                    ((select driver from rides where id=:ride),:user_id,:space,:ride);
                     update rides set booked=1 where id=:ride;';
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':ride', $ride);
                $stmt->bindParam(':user_id', $user_id);
                $stmt->bindParam(':space', $space_available);
//            $stmt->bindParam(':id', $id);
                $stmt->execute();
                header('LOCATION: ../index.php?success=Go for ride!! Well send mail');
            } else {
                header('LOCATION: ../index.php?error=Ride already taken!!');
            }
        }else{
            $dr = find_driver($ride);
            header("LOCATION: ../index.php?error=You cant book your own ride!$dr");

        }
        echo "$ride, $user_id, $space_available";
    } else {
        header('LOCATION: ../index.php?error=Ride not found!!');
    }
//    header('LOCATION: index.php');

} else {
    header('LOCATION: login_page.php?next=book.php');
}
?>