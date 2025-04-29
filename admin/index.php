<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once './models/marca.php';

$web = new Marca();

$result = $web->findAll();

echo "<pre>";
print_r($result);
echo "</pre>";
