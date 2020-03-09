<?php
$header = apache_request_headers();
$allowed = array('ooba');
$locked = array('users', 'admin');

$method = $_SERVER['REQUEST_METHOD'];
$key = $header['Authorization'] ?? $header['api_key'] ?? null;

if(!$key || !in_array($key, $allowed)) {
    echo '{"code": "401","error": "you don\'t have a key"}';
    die();
}

$dbhost = "localhost";
$dbname = "root";
$dbpass = "";
$db = "webshop";
$con = mysqli_connect($dbhost, $dbname, $dbpass, $db);

if(mysqli_error($con)) {
    echo '{"code": 409,"error": "couldn\'t lay connection with database"}';
    die();
}

if ($method == "GET") {
    // ------Variable-------
    $table = $_POST['table'] ?? $_GET['table'] ?? null;
    $row = $_POST['row'] ?? $_GET['p1'] ?? null;
    $limit = $_POST['limit'] ?? $_GET['p2'] ?? null;
    // ---------------------

    // ----Missing input----
    if(!$table) {
        echo '{"code": 422,"error": "please specify a table"}';
        die();
    } else if(in_array($table, $locked)) {
        echo '{"code": 403,"error": "this is a locked table"}';
        die();
    }
    // ---------------------
    
    // ---------SQL---------
    $sql = "SELECT";

    if($row && $row != "") {
        $sql .= " $row";
    } else {
        $sql .= " *";
    }

    $sql .= " FROM $table";

    if($limit && $limit != "") {
        $sql .= " LIMIT $limit";
    }
    // ---------------------

    $res = mysqli_query($con, $sql);

    if(!$res){
        echo '{"code": 404,"error": "couldn\'t get items out of database"}';
    } else {
        $data = array();
        while($row = mysqli_fetch_assoc($res)) {$data[] = $row;}
        $data = json_encode($data);
        echo "{\"code\": 200, \"data\": $data}";
    }
} elseif($method == "POST") {
    if(!isset($_GET['table'])) {
        echo '{"code": 422,"error": "please specify a table"}';
        die();
    } elseif(!isset($_POST['row'])) {
        echo '{"code": 422,"error": "please give specify the row"}';
        die();
    } elseif(!isset($_POST['value'])) {
        echo '{"code": 422,"error": "please give insert value"}';
        die();
    }
    $table = $_GET['table'];
    $insert = implode(",",  explode("-", $_POST['row']));
    $value = implode("','", explode("-", $_POST['value']));

    $sql = "INSERT INTO `$table` ($insert) VALUES ('$value')";

    $res = mysqli_query($con, $sql);

    if(!$res){
        $error = mysqli_error($con);
        echo "{'code': 409,'error': '$error'}";
    } else {
        echo '{"code": 201,"data": "insert complete"}';
    }
} elseif($method == "DELETE") {
    if(!isset($_GET['table'])) {
        echo '{"code": 422,"error": "please specify a table"}';
        die();
    } elseif(!isset($_GET['p1'])) {
        echo '{"code": 422,"error": "please give an id"}';
        die();
    }
    $table = $_GET['table'];
    $value = $_GET['p1'];

    $sql = "DELETE FROM $table WHERE id='$value'";

    $res = mysqli_query($con, $sql);

    if(!$res){
        $error = mysqli_error($con);
        echo "{'code': 409,'error': '$error'}";
    } else {
        echo '{"code": 201,"data": "delete row complete"}';
    }
} elseif($method == "PUT") {
    if(!isset($_GET['table'])) {
        echo '{"code": 422,"error": "please specify a table"}';
        die();
    } elseif(!isset($_GET['p1'])) {
        echo '{"code": 422,"error": "please give an id"}';
        die();
    } elseif(!isset($_GET['p2'])) {
        echo '{"code": 422,"error": "please give an row"}';
        die();
    } elseif(!isset($_GET['p3'])) {
        echo '{"code": 422,"error": "please give an value"}';
        die();
    }
    $table = $_GET['table'];
    $id = $_GET['p1'];
    $row = $_GET['p2'];
    $value = $_GET['p3'];

    $sql = "UPDATE $table SET $row='$value' WHERE id=$id";

    $res = mysqli_query($con, $sql);

    if(!$res){
        $error = mysqli_error($con);
        echo "{'code': 409,'error': '$error'}";
    } else {
        echo '{"code": 201,"data": "updated row complete"}';
    }
}
?>