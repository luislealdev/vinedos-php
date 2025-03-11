<?php
require_once __DIR__ . '/../model.php';

class Producto extends Model
{

    function create($data)
    {
        if (isset($_POST['submit'])) {
            if (isset($data['producto']) && isset($data['precio']) && isset($data['id_marca'])) {
                if (strlen($data['producto']) > 30) {
                    return false;
                }
                if ((!is_numeric($data['precio'])) || ($data['precio'] < 0) || (!is_numeric($data['id_marca']))) {
                    return false;
                }
            }

            $this->connect();
            $this->conn->beginTransaction();
            try {
                // Check if producto already exists
                $sql = "SELECT producto, count(*) as count FROM producto WHERE producto = :producto GROUP BY producto";
                $stmt = $this->conn->prepare($sql);
                $stmt->bindParam(':producto', $data['producto'], PDO::PARAM_STR);
                $stmt->execute();
                $producto = $stmt->fetch(PDO::FETCH_ASSOC);
                if (isset($producto['producto']) && $producto['count'] > 0) {
                    $this->conn->rollBack();
                    return false;
                }

                $image = $this->load_image();
                if ($image) {
                    $sql = "INSERT INTO producto (producto, precio, id_marca, fotografia, descripcion) VALUES (:producto, :precio, :id_marca, :fotografia, :descripcion)";
                    $stmt = $this->conn->prepare($sql);
                    $stmt->bindParam(':fotografia', $image, PDO::PARAM_STR);
                } else {
                    $sql = "INSERT INTO producto (producto, precio, id_marca, descripcion) VALUES (:producto, :precio, :id_marca, :descripcion)";
                    $stmt = $this->conn->prepare($sql);
                }

                $stmt->bindParam(':producto', $data['producto'], PDO::PARAM_STR);
                $stmt->bindParam(':precio', $data['precio'], PDO::PARAM_STR);
                $stmt->bindParam(':id_marca', $data['id_marca'], PDO::PARAM_INT);
                $stmt->bindParam(':descripcion', $data['descripcion'], PDO::PARAM_STR);
                $stmt->execute();

                $this->conn->commit();
                return $stmt->rowCount();
            } catch (PDOException $e) {
                $this->conn->rollBack();
                throw $e;
            }
        }

        return false;
    }
    function update($data, $id)
    {

        // Trim
        $data['producto'] = trim($data['producto']);

        if (isset($data['producto'])) {
            if (strlen($data['producto']) > 30) {
                return false;
            }
        }

        $this->connect();
        $this->conn->beginTransaction();
        try {
            $sql = "UPDATE producto SET producto = :producto, precio = :precio, id_marca = :id_marca WHERE id_producto = :id_producto";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':producto', $data['producto'], PDO::PARAM_STR);
            $stmt->bindParam(':precio', $data['precio'], PDO::PARAM_STR);
            $stmt->bindParam(':id_marca', $data['id_marca'], PDO::PARAM_INT);
            $stmt->bindParam(':id_producto', $id, PDO::PARAM_INT);
            $stmt->execute();

            // Check if producto already exists
            $sql = "SELECT producto, count(*) FROM producto WHERE producto = :producto group by producto";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':producto', $data['producto'], PDO::PARAM_STR);
            $stmt->execute();

            $producto = $stmt->fetch(PDO::FETCH_ASSOC);
            if (isset($stmt['producto'])) {
                if ($producto['count'] > 1) {
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

        if (is_numeric($id)) {
            return false;
        }

        $this->connect();
        $this->conn->beginTransaction();

        try {
            $sql = "DELETE FROM producto WHERE id_producto = :id_producto";
            $data = $this->conn->prepare($sql);
            $data->bindParam(':id_producto', $id, PDO::PARAM_INT);
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
        $data = $this->conn->prepare("SELECT p.*, m.marca FROM producto WHERE id_producto = :id_producto join marca m on p.id_marca = m.id_marca");
        $data->bindParam(':id_producto', $id, PDO::PARAM_INT);
        $data->execute();
        $result = $data->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    function findAll()
    {
        $this->connect();
        // Get cantidad from producto
        $data = $this->conn->query("select p.*, m.marca from producto p left join marca m on p.id_marca = m.id_marca order by producto");
        $data->execute();
        $marcas = $data->fetchAll(PDO::FETCH_ASSOC);
        return $marcas;
    }
}