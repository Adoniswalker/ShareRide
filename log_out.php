<?php
	require "dependant/sesion_file.php";
	SESSION_DESTROY();
	echo $referer;
	header('location:'.$referer);
?>