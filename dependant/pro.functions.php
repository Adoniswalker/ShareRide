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
//    Todo enable chcking the object type and avoid interger 0
        $driver_result = select_db($conn, $driver_query, array(':id' => $booked_ride_id))->fetchAll();
        $passenger_result = select_db($conn, $passager_query, array(':id' => $booked_ride_id))->fetchAll();
        $driver_email = $driver_result[1];
        $passenger_email = $passenger_result[1];
        $driver_subject = "Request to join ride";
        $passenger_subject = "You ride was accepted";

        $driver_message = "
                <html>
                <head>
                <title>Dear $driver_result[0];</title>
                </head>
                <body>
                <p>$passenger_result[0] has requested to join you in the ride</p>
                <p>The passenger mail is $passenger_result[1]. The trip is from $driver_result[3] to $driver_result[4] </p>
                <p>Available space is $driver_result[5]</p>
                <p>The trip  will take place at $driver_result[2]</p>
                 </body>
                </html>
                ";
        $passanger_message = "
                <html>
                <head>
                <title>Dear $passenger_result[0]</title>
                </head>
                <body>
                <p>You got a ride with $driver_result[0]</p>
                <p>The driver mail is $driver_result[1]. The trip is from $driver_result[3] to $driver_result[4] </p>
                <p>Available space is $driver_result[5]</p>
                <p>The trip  will take place at $driver_result[2]</p>
                </body>
                </html>
                ";

// Always set content-type when sending HTML email
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        echo $driver_message;
        echo $passanger_message;
        echo $passenger_subject, $driver_subject, $passenger_email, $driver_email;
// More headers
//    $headers .= 'From: <dennisngeno7@gmail.com>' . "\r\n";
//    $headers .= 'Cc: myboss@example.com' . "\r\n";

        mail($driver_email, $driver_subject, $driver_message, $headers);
        mail($passenger_email, $passenger_subject, $passanger_message, $headers);
    }

}

?>