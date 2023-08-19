<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: origin, Content-type:Accept");
require_once "../../models/Seller.php";

// check if the method is GET

if ($_SERVER['REQUEST_METHOD'] === "GET") {
    echo json_encode(array("success" => true, "sellers" => $seller->get_all_sellers()));
} else {
    die(header("http/1.1 405 request method not allowed "));
}
