<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("HTTP/1.1 200 OK");

include '../../db/rb-db-config.php';
//  ADD NEW DEFIB
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (file_get_contents('php://input')) {
        $data = json_decode(file_get_contents('php://input'), true);
        /*    $name = $_POST['name'];
          $description = $_POST['description'];
          $address = $_POST['address'];
          $city = $_POST['city'];
          $country = $_POST['country'];
          $latitude = $_POST['latitude'];
          $longitude = $_POST['longitude']; */
        $name = $data["name"];
        $description = $data["description"];
        $address = $data["address"];
        $city = $data["city"];
        $country = $data["country"];
        $latitude = $data["latitude"];
        $longitude = $data["longitude"];



        $defib = R::dispense('defibrillator');
        $defib->name = $name;
        $defib->description = $description;
        $defib->address = $address;
        $defib->city = $city;
        $defib->country = $country;
        $defib->latitude = $latitude;
        $defib->longitude = $longitude;
        if ($defib->name != null) {
            $id = R::store($defib);
            echo json_encode(array("code" => 200, "msg" => "Saved"));
        } else {
            echo json_encode(array("code" => 500, "msg" => "Error when parsing/inserting"));
        }
    }
} else {
    echo json_encode(array("code" => 400, "msg" => $_SERVER['REQUEST_METHOD'] . " method now allowed!"));
}

