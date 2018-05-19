<?php
require_once 'dependant/layout.php';
#todo the user can log in when looged in; will ask user if s/he want to log out
$user_email = $user_email_err = $marked = NULL;
$user_password = $user_password_err = NULL;
#todo check on security of password
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["user_email"])) {
        $user_email_err = "Email is required";
    } else {
        $user_email = test_input($_POST["user_email"]);
//        $username . "you are username<br>";
    }
    if (empty($_POST["user_password"])) {
        $user_password_err = "Password is required";
    } else {
        $user_password = test_input($_POST["user_password"]);
        $user_password = md5($user_password);
    }
    if ($username && $user_password) {
        $stmt = $conn->prepare("SELECT email FROM register WHERE email = '$user_email'");
        $stmt->execute();
        if ($stmt->rowCount()) {
            $li = $conn->prepare("SELECT id FROM register WHERE email = '$user_email' && password = '$user_password'");
            $li->execute();
            if ($li->rowCount()) {
                $row = $li->fetch();
                $_SESSION['user_id'] = $row[0];
                if (isset($_GET['next'])){
                    $redirect_url = $_GET['next'];
                    header("LOCATION: $redirect_url");
                }
                header('LOCATION: index.php');
            } else {
                $user_password_err = 'Wrong password, dd you forget your password';

            }
        } else {
            $user_email_err = "No such mail, would you like to register";
        }
    } else {
        $marked = "please fill the highlighted areas";
    }
}
?>
<div class="container">
    <div class="col-md-6">
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="form-group row">
                <label for="user_email" class="col-sm-2 col-form-label">Email</label>
                <div class="col-sm-7">
                    <input type="text" class="form-control" name="user_email"  placeholder="Jane.joe@gmail.com"
                           value="<?php echo $user_email ?>">
                </div>
                <div class="col-sm-3 text-danger">
                    <small class="text-danger">
                        <span> <?php echo $user_email_err; ?></span>
                    </small>
                </div>
            </div>
            <div class="form-group row">
                <label for="user_password" class="col-sm-2 col-form-label">Password</label>
                <div class="col-sm-7">
                    <input type="password" class="form-control" name="user_password">
                </div>
                <div class="col-sm-3 text-danger">
                    <small class="text-danger">
                        <span> <?php echo $user_password_err; ?></span>
                    </small>
                </div>
            </div>
            <button>Log in</button>
            <div class="col-sm-3 text-danger">
                <small class="text-danger">
                    <span> <?php echo $marked; ?></span>
                </small>
            </div>
        </form>
    </div>
</div>
<?php include "dependant/footer_file.php"?>