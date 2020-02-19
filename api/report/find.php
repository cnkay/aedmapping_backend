<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("HTTP/1.1 200 OK");

include '../../db/rb-db-config.php';

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['id'])) {
        $requested_id = $_GET['id'];
        if (is_numeric($requested_id)) {
            $id = R::findOne('report', 'id=?', [$requested_id]);
            if ($id != null) {
                $json_string = json_encode($id);
                echo $json_string;
            } else {
                echo json_encode(array("code" => 404, "msg" => "'id' not found!"));
            }
        } else {
            echo json_encode(array("code" => 400, "msg" => "'id' is not a valid number!"));
        }
    } else {
        echo json_encode(array("code" => 400, "msg" => "Wrong url syntax!"));
    }
} else {
    echo json_encode(array("code" => 400, "msg" => $_SERVER['REQUEST_METHOD'] . " method now allowed!"));
}
