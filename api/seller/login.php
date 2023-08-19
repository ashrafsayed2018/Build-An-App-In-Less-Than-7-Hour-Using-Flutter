<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: origin, Content-type:Accept");
require_once "../../models/Seller.php";

// check if the method is post

if ($_SERVER['REQUEST_METHOD'] === "POST") {

    // validate the email
    if ($seller->validate_params($_POST['email'])) {
        $seller->email = $_POST['email'];
    } else {
        echo json_encode(array("success" => false, "message" => "email is required"));
        die();
    }
    // validate password
    if ($seller->validate_params($_POST['password'])) {
        $seller->password = $_POST['password'];
    } else {
        echo json_encode(array("success" => false, "message" => "password is required"));
        die();
    }

    // 
    $s = $seller->login();
    if (gettype($s) == "string") {
        http_response_code(402);
        echo json_encode(array("success" => false, "message" => $s));
    } else {
        http_response_code(200);
        echo json_encode(array("success" => true, "message" => "login successful", "seller" => $s));
    }
} else {
    die(header("http/1.1 405 request method not allowed "));
}
