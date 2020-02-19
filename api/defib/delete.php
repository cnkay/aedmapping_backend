<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("HTTP/1.1 200 OK");

include '../../db/rb-db-config.php';

if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    if (isset($_GET['id'])) {
        $requested_id = $_GET['id'];
        if (is_numeric($requested_id)) {
            $id = R::findOne('defibrillator', 'id=?', [$requested_id]);
            if ($id != null) {
                R::trash($id);
                echo json_encode(array("code" => 200, "msg" => "Deleted defibrillator with id : ".$requested_id));
            } else {
                echo json_encode(array("code" => 404, "msg" => "id : ".$requested_id." not found!"));
            }
        } else {
            echo json_encode(array("code" => 400, "msg" => $requested_id." is not a valid number!"));
        }
    } else {
        echo json_encode(array("code" => 400, "msg" => "Wrong url syntax!"));
    }
} else {
    echo json_encode(array("code" => 400, "msg" => $_SERVER['REQUEST_METHOD'] . " method now allowed!"));
}
