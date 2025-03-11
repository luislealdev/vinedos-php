<?php
require_once __DIR__ . '/config.php';
class Model
{
    var $conn;
    var $types;
    var $max_size;

    function __construct()
    {
        $this->types = [
            'image/jpeg',
            'image/png',
            'image/gif',
            'image/jpg'
        ];
        $this->max_size = 1024 * 1024 * 2;
    }

    function get_types()
    {
        return $this->types;
    }

    function get_max_size()
    {
        return $this->max_size;
    }


    function connect()
    {
        $this->conn = new PDO(SGBD . ':host=' . HOST . ';dbname=' . DB, USER, PASSWORD);
    }

    function alert($alert)
    {
        include './views/alert.php';
    }

    function load_image(){
        if (isset($_FILES)) {
            $images = $_FILES;
            foreach($images as $image){
                if($image['error'] == 0){
                    if($image['size'] <= $this->max_size +1){
                        if(in_array($image['type'], $this->types)){
                            $name = $image['name'];
                            $tmp = $image['tmp_name'];
                            $path = UPLOAD_DIR . $name;
                            move_uploaded_file($tmp, $path);
                            return $path;
                        }
                    }
                }
            }
        }
        return false;
    }
}