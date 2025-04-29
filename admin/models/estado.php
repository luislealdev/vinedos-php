<?php
require_once __DIR__ . '/../model.php';

class Estado extends Model
{
    function create($data)
    {
        if (isset($data['estado'])) {
            if (strlen($data['estado']) > 30) {
                return false;
            }
        }

        $this->connect();
        $this->conn->beginTransaction();
        try {
            $sql = "INSERT INTO estado (estado) VALUES (:estado)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':estado', $data['estado'], PDO::PARAM_STR);
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
        $data['estado'] = trim($data['estado']);

        if (isset($data['estado'])) {
            if (strlen($data['estado']) > 30) {
                return false;
            }
        }

        $this->connect();
        $this->conn->beginTransaction();
        try {
            $sql = "UPDATE estado SET estado = :estado WHERE id_estado = :id_estado";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':estado', $data['estado'], PDO::PARAM_STR);
            $stmt->bindParam(':id_estado', $id, PDO::PARAM_INT);
            $stmt->execute();

            // Check if estado already exists
            $sql = "SELECT estado, count(*) as count FROM estado WHERE estado = :estado group by estado";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':estado', $data['estado'], PDO::PARAM_STR);
            $stmt->execute();

            $estado = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($estado && isset($estado['estado'])) {
                if ($estado['count'] > 1) {
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
        try {
            // Check if there are products associated with this estado
            $sql = "SELECT COUNT(*) as count FROM municipio WHERE id_estado = :id_estado";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id_estado', $id, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result && $result['count'] > 0) {
                $this->conn->rollBack();
                return false;
            }

            // Delete the estado
            $sql = "DELETE FROM estado WHERE id_estado = :id_estado";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id_estado', $id, PDO::PARAM_INT);
            $stmt->execute();
            $this->conn->commit();
            return $stmt->rowCount();
        } catch (PDOException $e) {
            $this->conn->rollBack();
            throw $e;
        }
    }

    function findOne($id)
    {
        $this->connect();
        $stmt = $this->conn->prepare("SELECT * FROM estado WHERE id_estado = :id_estado");
        $stmt->bindParam(':id_estado', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    function findAll()
    {
        $this->connect();
        $stmt = $this->conn->query("SELECT * FROM estado");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}