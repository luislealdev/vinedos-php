<?php

class Model
{
    var $conn;

    function connect()
    {
        $this->conn = new PDO('mysql:host=localhost;dbname=vinedo', 'vinedo', '123');
    }

    function alert($alert)
    {
        include './views/alert.php';
    }
}