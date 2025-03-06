<?php
require_once __DIR__ . '/../model.php';

class Departamento extends Model
{
    function create($data)
    {
        if (isset($data['departamento'])) {
            if (strlen($data['departamento']) > 30) {
                return false;
            }
        }

        $this->connect();
        $this->conn->beginTransaction();
        try {
            $sql = "INSERT INTO departamento (departamento, id_departamento_depende) VALUES (:departamento, :id_departamento_depende)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':departamento', $data['departamento'], PDO::PARAM_STR);
            $stmt->bindParam(':id_departamento_depende', $data['id_departamento_depende'], PDO::PARAM_INT);
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
        $data['departamento'] = trim($data['departamento']);

        if (isset($data['departamento'])) {
            if (strlen($data['departamento']) > 30) {
                return false;
            }
        }

        $this->connect();
        $this->conn->beginTransaction();
        try {
            $sql = "UPDATE departamento SET departamento = :departamento WHERE id_departamento = :id_departamento";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':departamento', $data['departamento'], PDO::PARAM_STR);
            $stmt->bindParam(':id_departamento', $id, PDO::PARAM_INT);
            $stmt->execute();

            // Check if departamento already exists
            $sql = "SELECT departamento, count(*) as count FROM departamento WHERE departamento = :departamento group by departamento";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':departamento', $data['departamento'], PDO::PARAM_STR);
            $stmt->execute();

            $departamento = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($departamento && isset($departamento['departamento'])) {
                if ($departamento['count'] > 1) {
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
            $sql = "SELECT COUNT(*) as count FROM departamento WHERE id_departamento_depende = :id_departamento";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id_departamento', $id, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result['count'] > 0) {
                $this->conn->rollBack();
                return false;
            }

            $sql = "SELECT COUNT(*) as count FROM puesto WHERE id_departamento = :id_departamento";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id_departamento', $id, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result['count'] > 0) {
                $this->conn->rollBack();
                return false;
            }

            $sql = "DELETE FROM departamento WHERE id_departamento = :id_departamento";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id_departamento', $id, PDO::PARAM_INT);
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
        $stmt = $this->conn->prepare("SELECT * FROM departamento WHERE id_departamento = :id_departamento");
        $stmt->bindParam(':id_departamento', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    function findAll()
    {
        $this->connect();
        $stmt = $this->conn->query("SELECT * FROM departamento");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}