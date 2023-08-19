<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: origin, Content-type:Accept");
require_once "../../models/Product.php";

// check if the method is post

if ($_SERVER['REQUEST_METHOD'] === "POST") {

    // validate the seller id
    if ($product->validate_params($_POST['seller_id'])) {
        $product->seller_id = $_POST['seller_id'];
    } else {
        echo json_encode(array("success" => false, "message" => "seller_id is required"));
        die();
    }
    // validate the name
    if ($product->validate_params($_POST['name'])) {
        $product->name = $_POST['name'];
    } else {
        echo json_encode(array("success" => false, "message" => "name is required"));
        die();
    }


    // save image of product

    // create folder for images
    $product_images_folder = "../../assets/product_images";

    // check if folder exists or not 
    if (!is_dir($product_images_folder)) mkdir($product_images_folder);

    if (isset($_FILES['image'])) {
        // get the file name
        $file_name =  $_FILES['image']['name'];

        $file_tmp =  $_FILES['image']['tmp_name'];
        // the image extension
        $file_extension = explode(
            ".",
            $file_name
        );
        $extension = end($file_extension);

        // the image name will save in the database and in the images folser
        $new_file_name = $product->seller_id . "_product_" . $product->name
            . "." . $extension;

        // upload the image to product_images folder
        move_uploaded_file(
            $file_tmp,
            $product_images_folder . "/" . $new_file_name
        );
        $product->image = "product_images/" . $new_file_name;
    } else {
        echo json_encode(array("success" => false, "message" => "product image is required"));
        die();
    }
    // validate the price per kg
    if ($product->validate_params($_POST['price_per_kg'])) {
        $product->price_per_kg = $_POST['price_per_kg'];
    } else {
        echo json_encode(array("success" => false, "message" => "price_per_kg is required"));
        die();
    }
    // validate the description
    if ($product->validate_params($_POST['description'])) {
        $product->description = $_POST['description'];
    } else {
        echo json_encode(array("success" => false, "message" => "description is required"));
        die();
    }

    if ($product->add_product()) {
        http_response_code(200);
        echo json_encode(array("success" => true, "message" => "product is added successfully"));
    } else {
        echo json_encode(array("success" => false, "message" => "internal server error "));
        http_response_code(500);
    }
} else {
    die(header("http/1.1 405 request method not allowed "));
}
