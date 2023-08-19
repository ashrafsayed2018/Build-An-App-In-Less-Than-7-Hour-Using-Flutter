<?php

$ds = DIRECTORY_SEPARATOR;
$base_dir = realpath(dirname(__FILE__) . $ds . "../") . $ds;

require_once $base_dir . "includes" . $ds . "database.php";

class Product
{
    private $table = "products";

    public $name;
    public $seller_id;
    public $image;
    public $price_per_kg;
    public $interaction_count;
    public $description;
    public function __construct()
    {
    }

    // validate the parameter used in store the value in db
    public function validate_params($value)
    {
        if (!empty($value)) return true;
        return false;
    }

    public function add_product()
    {
        global $database;


        $this->seller_id = trim(htmlspecialchars(strip_tags($this->seller_id ?? '')));
        $this->name = trim(htmlspecialchars(strip_tags($this->name ?? '')));
        $this->image = trim(htmlspecialchars(strip_tags($this->image ?? '')));
        $this->price_per_kg = trim(htmlspecialchars(strip_tags($this->price_per_kg ?? '')));
        $this->interaction_count = trim(htmlspecialchars(strip_tags($this->interaction_count ?? '')));
        $this->description = trim(htmlspecialchars(strip_tags($this->description ?? '')));

        $sql = "INSERT INTO $this->table (seller_id,name,image,price_per_kg,description) VALUES(
             '" . $database->escape_value($this->seller_id) . "',
             '" . $database->escape_value($this->name) . "',
             '" . $database->escape_value($this->image) . "',
             '" . $database->escape_value($this->price_per_kg) . "',
             '" . $database->escape_value($this->description) . "'
        ) ";

        $result = $database->query($sql);

        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    // gel list of products per seller 
    public function get_seller_products()
    {

        global $database;


        $this->seller_id = trim(htmlspecialchars(strip_tags($this->seller_id ?? '')));

        $sql = "SELECT * FROM $this->table where seller_id = '" . $database->escape_value($this->seller_id) . "'";
        $result = $database->query($sql);
        return $database->fetch_array($result);
    }
}


$product = new Product();
