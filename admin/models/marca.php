<?php
require_once __DIR__ . '/../model.php';

class Marca extends Model
{
    function create()
    {

    }

    function update()
    {

    }

    function delete()
    {

    }

    function findOne($id)
    {
        $this->connect();
        $data = $this->conn->prepare("SELECT * FROM marca WHERE id_marca = :id_marca");
        $data->bindParam(':id_marca', $id, PDO::PARAM_INT);
        $data->execute();
        $result = $data->fetchAll();
        return $result;
    }

    function findAll()
    {
        $this->connect();
        // Get cantidad from producto
        $data = $this->conn->query("select m.*, count(p.id_producto) as cantidad 
        from marca m left join producto p on m.id_marca = p.id_marca group by m.id_marca");
        $data->execute();
        $marcas = $data->fetchAll();
        return $marcas;
    }
}