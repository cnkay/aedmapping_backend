<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("HTTP/1.1 200 OK");
include '../../db/rb-db-config.php';
//  ADD NEW REPORT
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (file_get_contents('php://input')) {
        $data = json_decode(file_get_contents('php://input'), true);

        $type = $data["type"];
        $comment = $data["comment"];
        $mail = $data["mail"];
        $defibrillator_id = $data["defibrillator_id"];

        $defibrillator = R::findOne('defibrillator', 'id=?', [$defibrillator_id]);
        if ($defibrillator == null) {
            echo json_encode(array("code" => 404, "msg" => "Defibrillator not found"));
        } else {
            $report = R::dispense('report');
            $report->type = $type;
            $report->comment = $comment;
            $report->mail = $mail;
            $defibrillator->ownReportsList[] = $report;
            if ($data["type"] != null) {
                $id = R::store($defibrillator);
                echo json_encode(array("code" => 200, "msg" => "Saved"));
            } else {
                echo json_encode(array("code" => 500, "msg" => "Error when parsing/inserting"));
            }
        }
    }
} else {
    echo json_encode(array("code" => 400, "msg" => $_SERVER['REQUEST_METHOD'] . " method now allowed!"));
}