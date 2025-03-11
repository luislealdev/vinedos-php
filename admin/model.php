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

    function load_image()
    {
        if (isset($_FILES)) {
            $images = $_FILES;
            foreach ($images as $image) {
                if ($image['error'] == 0) {
                    if ($image['size'] <= $this->max_size + 1) {
                        if (in_array($image['type'], $this->get_types())) {
                            $ext = explode('.', $image['name']);
                            $ext = $ext[count($ext) - 1];
                            $name = md5($image['name']) . random_int(1, 1000000) . '.' . $ext;
                            if (!file_exists(UPLOAD_DIR . $name)) {
                                if (move_uploaded_file($image['tmp_name'], UPLOAD_DIR . $name)) {
                                    return $name;
                                } else {
                                    return false;
                                }
                            }
                        }
                    }
                }
            }
        }
        return false;
    }
}