<?php
require_once __DIR__ . '/../model.php';

class Marca extends Model
{
    function create($data)
    {
        print_r($data);
        return $data;
    }

    function update()
    {

    }

    function delete($id)
    {
        $this->connect();
        $this->conn->beginTransaction();
        $count = 0;
        try {
            $sql = "SELECT COUNT(*) as count FROM producto WHERE id_marca = :id_marca";
            $data = $this->conn->prepare($sql);
            $data->bindParam(':id_marca', $id, PDO::PARAM_INT);
            $data->execute();
            $count = $data->fetch(PDO::FETCH_ASSOC);
            $count = $count['count'];

            if ($count > 0) {
                $this->conn->rollBack();
                return false;
            }

            $sql = "DELETE FROM marca WHERE id_marca = :id_marca";
            $data = $this->conn->prepare($sql);
            $data->bindParam(':id_marca', $id, PDO::PARAM_INT);
            $data->execute();
            $this->conn->commit();
            $count = $data->rowCount();
            return $count;
        } catch (PDOException $e) {
            $this->conn->rollBack();
            throw $e;
        }
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