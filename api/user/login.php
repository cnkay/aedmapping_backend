<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("HTTP/1.1 200 OK");

include '../../db/rb-db-config.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (file_get_contents('php://input')) {
        $data = json_decode(file_get_contents('php://input'), true);

        $mail = $data["mail"];
        $password = $data["password"];
        if ($mail != null && $password != null) {
            $user = R::findOne('user','mail=?',[$mail]);
            if($user==null) {
                echo json_encode(array("code" => 400,"msg" => "Email address not found!"));
            }
            else{
                $pass_from_db=$user->password;
                if($pass_from_db!=$password) {
                    echo json_encode(array("code" => 400,"msg" => "Wrong password!"));
                }
                else{
                    echo json_encode(array("code" => 200,"msg" => "Access granted!"));
                }
            }
            
            
        } else {
            echo json_encode(array("code" => 400, "msg" => "Missing credentials!"));
        }
    }
} else {
    echo json_encode(array("code" => 400, "msg" => $_SERVER['REQUEST_METHOD'] . " method now allowed!"));
}

