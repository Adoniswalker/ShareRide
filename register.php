<?php
include "dependant/layout.php";
//require_once 'sesion_file.php';
//require_once 'db.connect.php';
include_once 'dependant/pro.functions.php';
// define variables and set to empty values
$first_name_err = $last_name_err = $email_err = $password1_err = $password2_err = NULL;
$first_name = $last_name = $email = $Password1 = $Password2 = $marked = NULL;

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (empty($_POST["first_name"])) {
        $first_name_err = "First name is required";
    } else {
        $first_name = test_input($_POST["first_name"]);
        if (!preg_match("/^[A-Za-z]+(((\'|\-|\.)?([A-Za-z])+))?$/", $first_name)) {
            $first_name_err = "Invalid first name";
            $first_name = NULL;
        }
    }
    if (empty($_POST["last_name"])) {
        $last_name_err = "Last name is required";
    } else {
        $last_name = test_input($_POST["last_name"]);
        if (!preg_match("/^[A-Za-z]+(((\'|\-|\.)?([A-Za-z])+))?$/", $last_name)) {
            $last_name_err = "Invalid last name";
            $last_name = NULL;
        }
    }

    if (empty($_POST["user_email"])) {
        $email_err = "Email is required";
    } else {
        $email = test_input($_POST["user_email"]);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $email_err = "Invalid email format";
            $email = NULL;
        }
    }
    if (!empty($_POST["pass_word1"] && !empty($_POST["pass_word2"]))) {
        $Password1 = $_POST["pass_word1"];
        $Password2 = $_POST["pass_word2"];
        if ($Password2 == $Password1) {
            $Password1 = test_input($_POST["pass_word1"]);
            if (!preg_match("/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/", $Password1)) {
                $password1_err = $password2_err = "Minimum eight characters, at least one letter and one number";
                $Password1 = $Password2 = NULL;
            }
        } else {
            $password2_err = "Passwords do not match";
            $Password1 = $Password2 = NULL;
        }
    } else {
        $password1_err = $password2_err = "Please input the password";
        $Password1 = $Password2 = NULL;

    }
    if ($last_name && $first_name && $Password1 && $Password2 && $email) {
//        echo $Email;
        $stmt = $conn->prepare("SELECT email FROM register WHERE email = '$email'");
        $stmt->execute();
        if (!$stmt->rowCount()) {
            try {
                $Password = md5($Password1);
                $stmt = $conn->prepare("INSERT INTO register(first_name, last_name,email, password) 
			    VALUES (:first_name, :last_name, :Email, :password)");
                $stmt->bindParam(':first_name', $first_name);
                $stmt->bindParam(':last_name', $last_name);
                $stmt->bindParam(':Email', $email);
                $stmt->bindParam(':password', $Password);
                // insert a
                $stmt->execute();
                $last_id = $conn->LastInsertId();
                header('LOCATION: login_page.php');
//                echo "Success Signup" . $last_id;
            } catch (PDOException $e) {
                echo "There was an error login " . "<br>" . $e->getMessage();
            }
        } else {
            $email_err = "Email has already been used";
        }


    } else {
        $marked = "Please correct the fields";
    }

}

?>
    <div class="container">
        <div class="col-md-6">
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <div class="form-group row">
                    <label for="first_name" class="col-sm-2 col-form-label">First Name</label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control" name="first_name" placeholder="Jane"
                               value="<?php echo $first_name ?>">
                    </div>
                    <div class="col-sm-3 text-danger">
                        <small class="text-danger">
                            <span> <?php echo $first_name_err; ?></span>
                        </small>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="last_name" class="col-sm-2 col-form-label">Last Name</label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control" name="last_name" placeholder="Kamuu"
                               value="<?php echo $last_name ?>">
                    </div>
                    <div class="col-sm-3">
                        <small class="text-danger">
                            <span class="error"> <?php echo $last_name_err; ?></span>
                        </small>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="user_email" class="col-sm-2 col-form-label" ">Email</label>
                    <div class="col-sm-7">
                        <input type="email" class="form-control" name="user_email" value="<?php echo $email ?>"
                               placeholder="jane.doe@example.com">
                    </div>
                    <div class="col-sm-3">
                        <small class="text-danger">
                            <span class="error"> <?php echo $email_err; ?></span>
                        </small>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="pass_word1" class="col-sm-2 col-form-label">Password</label>
                    <div class="col-sm-7">
                        <input type="password" class="form-control" name="pass_word1" placeholder="password">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="pass_word2" class="col-sm-2 col-form-label">Password</label>
                    <div class="col-sm-7">
                        <input type="password" class="form-control" name="pass_word2" placeholder="password">
                    </div>
                    <div class="col-sm-3">
                        <small class="text-danger">
                            <span class="text-danger"> <?php echo $password2_err; ?></span>
                        </small>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-3 text-danger">
                        <small class="text-danger">
                            <span> <?php echo $marked; ?></span>
                        </small>
                    </div>
                    <button type="submit" class="btn btn-default col-sm-2">Sign Up</button>
                </div>
            </form>
        </div>
    </div>
<?php include "dependant/footer_file.php" ?>