<?php
require_once 'db.connect.php';
$origin = $origin_err = $destination = $destination_err = $space = $space_err = $time = $time_err = null;
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (empty($_POST["origin"])) {
        $origin_err = "Origin is required";
    } else {
        $origin = test_input($_POST["origin"]);
        # todo foud out that pregmatch is not greedy
        if (!preg_match("/^[A-Za-z]+(((\'|\-|\.)?([A-Za-z])+))?$/", $origin)) {
            $origin_err = "Invalid place name";
            $origin = NULL;
        }
    }
    if (empty($_POST["destination"])) {
        $desination_err = "Destination is required";
    } else {
        $destination = test_input($_POST["destination"]);
        # todo foud out that pregmatch is not greedy
        if (!preg_match("/^[A-Za-z]+(((\'|\-|\.)?([A-Za-z])+))?$/", $destination)) {
            $destination_err = "Invalid destination name";
            $destination = NULL;
        }
    }
    if (empty($_POST["space"])) {
        $space = 1;
    } else {
        $space = test_input($_POST["space"]);
        # todo foud out that pregmatch is not greedy
        if (!preg_match("/^[1-9]+$/", $space)) {
            $space = "Invalid first name";
            $space_err = NULL;
        }
    }
    if ($destination && $origin && loggedin()) {
//        echo $Email;
//        $stmt = $conn->prepare("SELECT email FROM register WHERE email = '$email'");
//        $stmt->execute();
//        if (!$stmt->rowCount()) {
        $driver = $_SESSION['user_id'];
        try {
            $stmt = $conn->prepare("INSERT INTO rides(origin, destination,space, driver) 
			    VALUES (:origin, :destination, :space_to, :driver)");
            $stmt->bindParam(':origin', $origin);
            $stmt->bindParam(':destination', $destination);
            $stmt->bindParam(':space_to', $space);
            $stmt->bindParam(':driver', $driver);
            $stmt->execute();
            header('LOCATION: index.php?success=You invited others!! wait for them to pick');
        } catch (PDOException $e) {
            echo "There was an error" . "<br>" . $e->getMessage();
            header('LOCATION: index.php?error=Could not invite othes for ride');

        }

    } else {
        $marked = "Please correct the marked fields";
    }

}
?>

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <div class="form-group row">
        <label for="first_name" class="col-sm-2 col-form-label">Origin</label>
        <div class="col-sm-7">
            <input type="text" class="form-control" name="origin" placeholder="Githurai"
                   value="<?php echo $origin ?>">
        </div>
        <div class="col-sm-3 text-danger">
            <small class="text-danger">
                <span> <?php echo $origin_err; ?></span>
            </small>
        </div>
    </div>
    <div class="form-group row">
        <label for="destination" class="col-sm-2 col-form-label">Destination</label>
        <div class="col-sm-7">
            <input type="text" class="form-control" name="destination" placeholder="CBD"
                   value="<?php echo $destination ?>">
        </div>
        <div class="col-sm-3 text-danger">
            <small class="text-danger">
                <span> <?php echo $destination_err; ?></span>
            </small>
        </div>
    </div>
    <div class="form-group row">
        <label for="space" class="col-sm-2 col-form-label">Space</label>
        <div class="col-sm-7">
            <input type="number" class="form-control" name="space" placeholder="2"
                   value="<?php echo $space ?>">
        </div>
        <div class="col-sm-3 text-danger">
            <small class="text-danger">
                <span> <?php echo $space_err; ?></span>
            </small>
        </div>
    </div>
    <button type="submit" class="btn btn-default">Give Ride</button>
</form>