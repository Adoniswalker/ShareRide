<?php
ob_start();
session_start();
//$current_file = $_SERVER['SCRIPT_NAME']
if (isset($_SERVER['HTTP_REFERER'])) {
    $referer = $_SERVER['HTTP_REFERER'];
}
/* This function is used to check if the php is production or not */
function check_environmet()
{
    $SandboxFile = dirname(__DIR__) . '/sandbox_server.txt';
    if (file_exists($SandboxFile)) {
        return 0;
    } else {
        return 1;
    }
}
/*if(!$referer){
	$referer = NULL;
}*/
//require_once 'db.connect.php';
function get_amazon(){
    $dbopts = parse_url(getenv('DATABASE_URL'));
    try {
        $user = $dbopts["user"];
        $password = $dbopts["pass"];
        $host =$dbopts["host"];
        $port = $dbopts["port"];
        $dbname = ltrim($dbopts["path"],'/');
        $conn = new PDO("pgsql:host=$host;dbname=$dbname;user=$user;port=$port;password=$password");
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    }
    catch(PDOException $e)
    {
        echo "there was an error <br>". $e->getMessage();
    }
}
function local(){
    $servername = "127.0.0.1";
    $username = "root";
    $password = "";
    global $conn;

    try {
        $conn = new PDO("mysql:host=$servername; dbname=shareride", $username, $password);
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
//    echo "connected succefully";
    }
    catch(PDOException $e)
    {
        echo "there was an error <br>". $e->getMessage();
    }
}
function local_postgres(){
    $dbopts = parse_url(getenv('DATABASE_URL'));
    try {
        $user = 'adoniswalker';
        $password = 'adonis254';
        $host ='127.0.0.1';
        $port = '5432';
        $dbname = 'shareride';
        $conn = new PDO("pgsql:host=$host;dbname=$dbname;user=$user;port=$port;password=$password");
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    }
    catch(PDOException $e)
    {
        echo "there was an error <br>". $e->getMessage();
    }
}
// used to switch to different database

//returns the database connection for the production or developnment
$conn = check_environmet() ? get_amazon():local_postgres();
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
    $ud = $conn->prepare("SELECT $field FROM register WHERE id = $id ");
    try {
        $ud->execute();
        if ($ud->rowcount()) {
            $data = $ud->fetch();
            $data = $data[0];
            return $data;

        }
    } catch (PDOException $e) {
        echo "Not found" . $e->getMessage();
        return 0;
    }
}

?>