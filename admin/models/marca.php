<?php
require_once __DIR__ . '/../model.php';

class Marca extends Model
{
    function create($data)
    {

        if (isset($data['marca'])) {
            if (strlen($data['marca']) > 30) {
                return false;
            }
        }

        $this->connect();
        $this->conn->beginTransaction();
        try {
            $sql = "INSERT INTO marca (marca) VALUES (:marca)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':marca', $data['marca'], PDO::PARAM_STR);
            $stmt->execute();
            $this->conn->commit();
            $count = $stmt->rowCount();
            return $count;
        } catch (PDOException $e) {
            $this->conn->rollBack();
            throw $e;
        }
    }

    function update($data, $id)
    {

        // Trim
        $data['marca'] = trim($data['marca']);

        if (isset($data['marca'])) {
            if (strlen($data['marca']) > 30) {
                return false;
            }
        }

        $this->connect();
        $this->conn->beginTransaction();
        try {
            $sql = "UPDATE marca SET marca = :marca WHERE id_marca = :id_marca";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':marca', $data['marca'], PDO::PARAM_STR);
            $stmt->bindParam(':id_marca', $id, PDO::PARAM_INT);
            $stmt->execute();

            // Check if marca already exists
            $sql = "SELECT marca, count(*) FROM marca WHERE marca = :marca group by marca";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':marca', $data['marca'], PDO::PARAM_STR);
            $stmt->execute();

            $marca = $stmt->fetch(PDO::FETCH_ASSOC);
            if (isset($stmt['marca'])) {
                if ($marca['count'] > 1) {
                    $this->conn->rollBack();
                    return false;
                }
            }

            $this->conn->commit();
            $count = $stmt->rowCount();
            return $count;
        } catch (PDOException $e) {
            $this->conn->rollBack();
            throw $e;
        }
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
        $result = $data->fetch(PDO::FETCH_ASSOC);
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

    function graficar()
    {
        $this->connect();
        $data = $this->conn->query("SELECT m.marca, COUNT(p.id_producto) as cantidad 
        FROM marca m LEFT JOIN producto p ON m.id_marca = p.id_marca GROUP BY m.marca");
        $data->execute();
        $result = $data->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
}