<?php
function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function select_db($conn, $sql, $parameters = null)
{
    try {
//        var_dump($parameters);
        $stmt = $conn->prepare($sql);
        foreach ($parameters as $key => $value) {
            $stmt->bindParam($key, $value);
        }
//        $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();
        return $stmt;

    } catch
    (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return 0;
    }
}

function send_mail_success($conn, $booked_ride_id)
{
    if (settype($booked_ride_id, "int") && is_object($conn)) {
        $driver_query = "select concat(rg.first_name,' ', rg.last_name), rg.email, r.date, r.origin, r.destination, r.space
                      from booked_rides b left join register rg on b.driver = rg.id
                      left join rides r on b.ride =r.id where b.id=:id;";

        $passager_query = "select concat(first_name, ' ',last_name),
 email from booked_rides b 
                        left join register r on b.passanger = r.id where b.id =:id;";
        $driver_result = select_db($conn, $driver_query, array(':id' => $booked_ride_id))->fetchAll()[0];
        $passenger_result = select_db($conn, $passager_query, array(':id' => $booked_ride_id))->fetchAll()[0];
        if (is_array($passenger_result ) && is_array($driver_result)) {
            var_dump($driver_result);
            var_dump($passenger_result);
            //drier details
            $driver_email = $driver_result['email'];
            var_dump( $driver_email);
            $driver_name = $driver_result['concat'];
            $driver_origin = $driver_result['origin'];
            $driver_destination = $driver_result['destination'];
            $driver_space = $driver_result['space'];
            $driver_date = $driver_result['date'];
            //passenger Details
            $passenger_email = $passenger_result['email'];
            $passenger_name = $passenger_result['concat'];
            $driver_subject = "Request to join ride";
            $passenger_subject = "You ride was accepted";

            $driver_message = "
                <html>
                <head>
                <title>Dear $driver_name;</title>
                </head>
                <body>
                <p>$passenger_name has requested to join you in the ride</p>
                <p>The passenger mail is $passenger_email. The trip is from $driver_origin to $driver_destination </p>
                <p>Available space is $driver_space</p>
                <p>The trip  will take place at $driver_date</p>
                 </body>
                </html>
                ";
            $passanger_message = "
                <html>
                <head>
                <title>Dear $passenger_name</title>
                </head>
                <body>
                <p>You got a ride with $driver_name</p>
                <p>The driver mail is $driver_email. The trip is from $driver_origin to $driver_destination </p>
                <p>Available space is $driver_space</p>
                <p>The trip  will take place at $driver_date</p>
                </body>
                </html>
                ";
            require_once "../send_mail.php";
            send_phpmailer($driver_subject, $driver_email, $driver_message);
            return send_phpmailer($passenger_subject, $passenger_email, $passanger_message);
        }else{return 1;}
    }
    return 2;
}

?>