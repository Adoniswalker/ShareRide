<!DOCTYPE html>
<html>
<head>
    <title>Share Ride</title>
    <link rel="shortcut icon" href="../images/favicon.jpeg" type="image/x-icon">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link type="text/css" rel="stylesheet" href="css_files/main.css" media="screen"/>
    <link type="text/css" rel="stylesheet" href="css/bootstrap.css">
    <link type="text/css" rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">Share Ride Inc</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText"
            aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarText">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
                <a class="nav-link" href="/">Home <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/">Share</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/">Ride</a>
            </li>
            <?php
            require "sesion_file.php";
            require_once "dependant/pro.functions.php";
            if (loggedin()) {
                $user_name = get_user_data('first_name');
                echo '<li class="nav-item"><a class="nav-link" href="log_out.php"><span class="glyphicon glyphicon-log-out">Log out</a></li>';
            } else {
                echo '<li class="nav-item"><a class="nav-link" href="register.php"><span class="glyphicon glyphicon-user"></span>Sign Up</a></li>';
                echo '<li class="nav-item"><a class="nav-link" href="login_page.php"><span class="glyphicon glyphicon-log-in"></span>Log in</a></li>';
            }
            ?>
        </ul>
        <span class="navbar-text">
            <?php
            if (loggedin()) {
                $first_name = get_user_data('first_name');
                $last_name = get_user_data('last_name');
                echo "$first_name $last_name";

            }
            ?>
    </span>
    </div>
</nav>
