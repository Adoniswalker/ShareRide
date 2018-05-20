<!DOCTYPE html>
<html>
<head>
    <title>Share Ride</title>
    <link rel="shortcut icon" href="../images/favicon.jpeg" type="image/x-icon">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link type="text/css" rel="stylesheet" href="css_files/main.css" media="screen"/>
    <link type="text/css" rel="stylesheet" href="css/bootstrap.css">
    <link type="text/css" rel="stylesheet" href="css/bootstrap.min.css">
<!--    <link rel="stylesheet" href="css_files/bootstarp.min.css">-->
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="collapse navbar-collapse" id="navbarNavDropdown">
        <ul class="navbar-nav">
            <li class="nav-item active">
                <a class="nav-link" href="index.php">Home <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="index.php">Give Ride</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="index.php">Get Ride</a>
            </li>
            <?php
            require "sesion_file.php";
            require_once "dependant/pro.functions.php";
            if (loggedin()) {
                $user_name = get_user_data('first_name');
                echo '<li class= "nav-item pull"><a class="nav-link" href="log_out.php"> Log out</a></li>';
            } else {
                echo '<li class="nav-item"><a class="nav-link"href="register.php">Sign Up</a></li>';
                echo '<li class="nav-item"><a class="nav-link" href="login_page.php">Log in</a></li>';
            }
            ?>
        </ul>
    </div>
</nav>
</body>
</html>