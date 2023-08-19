<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: origin, Content-type:Accept");
require_once "../../models/Product.php";

// check if the method is GET

if ($_SERVER['REQUEST_METHOD'] === "GET") {

    if ($product->validate_params($_GET['seller_id'])) {
        $product->seller_id = $_GET['seller_id'];
    } else {
        json_encode(array('success' => false, "message" => "seller id is required "));
    }
    echo json_encode(array("success" => true, "products", $product->get_seller_products()));
} else {
    die(header("http/1.1 405 request method not allowed "));
}
