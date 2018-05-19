<?php
//function Database($conn)
//{
//	try {
//		$sqldb = "CREATE DATABASE sales";
//		$conn -> exec($sqldb);
//		echo "succefully created the database";
//	} catch (PDOException $e) {
//		echo $sqldb ."error creating the database<br>".$e->getMessage();
//	}
//}
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
        foreach ($parameters as $key => $value){
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

?>