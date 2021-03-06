<?php
require_once "sesion_file.php";
require_once "pro.functions.php";

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
#todo database intergrity
function find_driver($ride_id){
    global $conn;
    $query ="select driver from rides where id =:id";
    $driver_ans = select_db($conn,$query, array(':id'=>$ride_id));
    if($driver_ans) {
        $driver_id = $driver_ans->rowCount() ? $driver_ans->fetch()[0] : $driver_ans = 0;
        return $driver_id;
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
                    ((select driver from rides where id=:ride),:user_id,:space,:ride) RETURNING id;';
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':ride', $ride);
                $stmt->bindParam(':user_id', $user_id);
                $stmt->bindParam(':space', $space_available);
//            $stmt->bindParam(':id', $id);
                $stmt->execute();
                $last_id = $stmt->fetch(PDO::FETCH_ASSOC)['id'];
                $stmt= $conn->prepare('update rides set booked=1 where id=:ride;');
                $stmt->bindParam(':ride', $ride);
                $stmt->execute();
//                echo $last_id;
                $stmt->closeCursor();
                $mail = send_mail_success($conn,$last_id);
                $mail = $mail==1 ?'Mail sent' : "Mail not sent, error $mail";
//                $mail = send_mail_success($conn, $last_id);
                header("LOCATION: ../index.php?success=Go for ride!! $mail");
            } else {
                header('LOCATION: ../index.php?error=Ride already taken!!');
            }
        }else{
            $dr = find_driver($ride);
            header("LOCATION: ../index.php?error=You cant book your own ride!");

        }
//        echo "$ride, $user_id, $space_available";
    } else {
        header('LOCATION: ../index.php?error=Ride not found!!');
    }
//    header('LOCATION: index.php');

} else {
    header('LOCATION: ../login_page.php?next=book.php');
}
?>