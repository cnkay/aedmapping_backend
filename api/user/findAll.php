<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("HTTP/1.1 200 OK");

include '../../db/rb-db-config.php';

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $allUsers = R::findAll('user');
    $arr = array();
    foreach ($allUsers as $value) {
        array_push($arr, $value);
    }
    $json_string = json_encode($arr);
    echo $json_string;
}
else {
    echo json_encode(array("code" => 400, "msg" => $_SERVER['REQUEST_METHOD']." method now allowed!"));
}

