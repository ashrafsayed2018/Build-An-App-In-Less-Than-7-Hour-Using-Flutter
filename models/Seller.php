<?php

$ds = DIRECTORY_SEPARATOR;
$base_dir = realpath(dirname(__FILE__) . $ds . "../") . $ds;

require_once $base_dir . "includes" . $ds . "database.php";
require_once $base_dir . "includes" . $ds . "bcrypt.php";
class Seller
{
    private $table = "sellers";
    public $id;
    public $name;
    public $email;
    public $password;
    public $image;
    public $address;
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


    // check if email is unique or not
    public function check_unique_email()
    {
        global $database;


        $this->email = trim(htmlspecialchars(strip_tags($this->email ?? '')));

        $sql = "SELECT id from $this->table where email = '" . $database->escape_value($this->email) . "' ";

        $result = $database->query($sql);

        $user_id = $database->fetch_row($result);
        return (empty($user_id));
    }
    // saving new data 

    public function register_seller()
    {
        global $database;

        $this->name = trim(htmlspecialchars(strip_tags($this->name ?? '')));
        $this->email = trim(htmlspecialchars(strip_tags($this->email ?? '')));
        $this->password = trim(htmlspecialchars(strip_tags($this->password ?? '')));
        $this->image = trim(htmlspecialchars(strip_tags($this->image ?? '')));
        $this->address = trim(htmlspecialchars(strip_tags($this->address ?? '')));
        $this->description = trim(htmlspecialchars(strip_tags($this->description ?? '')));


        // the query 

        $sql = "INSERT INTO $this->table(name,email,password,image,address,description) VALUES(
            '" . $database->escape_value($this->name) . "',
            '" . $database->escape_value($this->email) . "',
            '" . $database->escape_value(Bcrypt::hashPassword($this->password)) . "',
            '" . $database->escape_value($this->image) . "',
            '" . $database->escape_value($this->address) . "',
            '" . $database->escape_value($this->description) . "')
            ";
        $seller_saved = $database->query($sql);

        if ($seller_saved) {
            return $database->last_insert_id();
        } else {
            return false;
        };
    }

    // login 

    public function login()
    {
        global $database;
        $this->email = trim(htmlspecialchars(strip_tags($this->email ?? '')));
        $this->password = trim(htmlspecialchars(strip_tags($this->password ?? '')));

        $sql = "SELECT * FROM $this->table WHERE email='" . $database->escape_value($this->email) . "'";

        $result = $database->query($sql);
        $seller = $database->fetch_row($result);

        if (empty($seller)) {
            return "seller doesn't exists";
        } else {


            // check $password against the $hashedPassword => returns true/false
            if (Bcrypt::checkPassword($this->password, $seller['password'])) {
                unset($seller['password']);
                return $seller;
            } else {
                return "password does not match";
            }
        }
    }

    // get all sellers

    public function get_all_sellers()
    {

        global $database;
        $sql = "SELECT id,name,image,address FROM $this->table";
        $result = $database->query($sql);
        return $database->fetch_array($result);
    }
}

$seller = new Seller();
