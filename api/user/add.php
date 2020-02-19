<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("HTTP/1.1 200 OK");

include '../../db/rb-db-config.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (file_get_contents('php://input')) {
        $data = json_decode(file_get_contents('php://input'), true);

        $name = $data["name"];
        $mail = $data["mail"];
        $password = $data["password"];
        if ($name != null && $mail != null && $password != null) {
            $user = R::dispense('user');
            $user->name = $name;
            $user->mail = $mail;
            $user->password = $password;
            $id = R::store($user);
            if ($id) {
                echo json_encode(array("code" => 200, "msg" => "Saved"));
            } else {
                echo json_encode(array("code" => 500, "msg" => "Error when parsing or inserting"));
            }
        } else {
            echo json_encode(array("code" => 400, "msg" => "Missing credentials"));
        }
    }
} else {
    echo json_encode(array("code" => 400, "msg" => $_SERVER['REQUEST_METHOD'] . " method now allowed!"));
}

