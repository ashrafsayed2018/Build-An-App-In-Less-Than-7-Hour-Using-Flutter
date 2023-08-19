<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: origin, Content-type:Accept");
require_once "../../models/Seller.php";

// check if the method is post

if ($_SERVER['REQUEST_METHOD'] === "POST") {

    // validate the name
    if ($seller->validate_params($_POST['name'])) {
        $seller->name = $_POST['name'];
    } else {
        echo json_encode(array("success" => false, "message" => "name is required"));
        die();
    }


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
    // create folder for images
    $seller_images_folder = "../../assets/seller_images";

    // check if folder exists or not 
    if (!is_dir($seller_images_folder)) mkdir($seller_images_folder);

    if (isset($_FILES['image'])) {
        // get the file name
        $file_name =  $_FILES['image']['name'];
        $file_tmp =  $_FILES['image']['tmp_name'];
        // the image extension
        $extension = end(explode(".", $file_name));

        // the image name will save in the database and in the images folser
        $new_file_name = $seller->email . "_profile" . $extension;

        // upload the image to seller_images folder
        move_uploaded_file($file_tmp, $seller_images_folder . "/" . $new_file_name);
        $seller->image = "seller_images/" . $new_file_name;
    }

    // validate address
    if ($seller->validate_params($_POST['address'])) {
        $seller->address = $_POST['address'];
    } else {
        echo json_encode(array("success" => false, "message" => "address is required"));
        die();
    }

    // validate description

    if ($seller->validate_params($_POST['description'])) {
        $seller->description = $_POST['description'];
    } else {
        echo json_encode(array("success" => false, "message" => "description is required"));
        die();
    }
    if ($seller->check_unique_email()) {
        // if the register is complete 
        if ($id = $seller->register_seller()) {
            echo json_encode(array("success" => true, "message" => "seller is registered successfully"));
        } else {
            echo json_encode(array("success" => false, "message" => "500 internal sever error "));
        }
    } else {
        http_response_code(401);
        echo json_encode(array("success" => false, "message" => "the email is already exists"));
    }
} else {
    die(header("http/1.1 405 request method not allowed "));
}
