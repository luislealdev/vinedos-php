<?php
require_once __DIR__ . '/../model.php';
class Reporte extends Model
{
    function encabezado()
    {
        $encabezado = 'Yo soy el encabezado';
        return $encabezado;
    }

    function a4()
    {
        $a4 = 'Yo soy el a4';
        return $a4;
    }

    function letter()
    {
        $letter = 'Yo soy el letter';
        return $letter;
    }

    function pie()
    {
        $pie = 'Yo soy el pie';
        return $pie;
    }
} ?>