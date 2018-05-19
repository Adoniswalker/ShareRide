<?php
ob_start();
session_start();
//$current_file = $_SERVER['SCRIPT_NAME']
if (isset($_SERVER['HTTP_REFERER'])) {
    $referer = $_SERVER['HTTP_REFERER'];
}

/*if(!$referer){
	$referer = NULL;
}*/
require_once 'db.connect.php';
function loggedin()
{
    if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
        return TRUE;
    } else {
        return FALSE;
    }
}

function get_user_data($field)
{
    global $conn;
    $id = $_SESSION['user_id'];
    $ud = $conn->prepare("SELECT `$field` FROM register WHERE id = $id ");
    try {
        $ud->execute();
        if ($ud->rowcount()) {
            $data = $ud->fetch();
            $data = $data[0];
            return $data;

        }
    } catch (PDOException $e) {
        echo "Not found" . $e->getMessage();
    }
}

?>