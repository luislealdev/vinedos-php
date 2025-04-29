<?php
require_once __DIR__ . '/../model.php';

class Permiso extends Model
{
    function create($data)
    {
        $data['permiso'] = trim($data['permiso'] ?? '');
        if (strlen($data['permiso']) > 30) {
            return false;
        }

        $this->connect();
        try {
            $sql = "INSERT INTO permiso (permiso) VALUES (:permiso)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':permiso', $data['permiso'], PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->rowCount();
        } catch (PDOException $e) {
            throw $e;
        }
    }

    function update($data, $id)
    {
        $data['permiso'] = trim($data['permiso'] ?? '');
        if (strlen($data['permiso']) > 30) {
            return false;
        }

        $this->connect();
        try {
            // Verificar duplicados excluyendo el registro actual
            $sql = "SELECT COUNT(*) as count FROM permiso WHERE permiso = :permiso AND id_permiso != :id_permiso";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':permiso', $data['permiso'], PDO::PARAM_STR);
            $stmt->bindParam(':id_permiso', $id, PDO::PARAM_INT);
            $stmt->execute();
            if ($stmt->fetchColumn() > 0) {
                return false; // Ya existe otro permiso con ese nombre
            }

            $sql = "UPDATE permiso SET permiso = :permiso WHERE id_permiso = :id_permiso";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':permiso', $data['permiso'], PDO::PARAM_STR);
            $stmt->bindParam(':id_permiso', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->rowCount();
        } catch (PDOException $e) {
            throw $e;
        }
    }

    function delete($id)
    {
        $this->connect();
        try {
            // Verificar referencias
            $sql = "SELECT COUNT(*) FROM permiso_rol WHERE id_permiso = :id_permiso";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id_permiso', $id, PDO::PARAM_INT);
            $stmt->execute();
            if ($stmt->fetchColumn() > 0) {
                return false; // No se puede eliminar porque está en uso
            }

            $sql = "DELETE FROM permiso WHERE id_permiso = :id_permiso";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id_permiso', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->rowCount();
        } catch (PDOException $e) {
            throw $e;
        }
    }

    function findOne($id)
    {
        $this->connect();
        try {
            $stmt = $this->conn->prepare("SELECT * FROM permiso WHERE id_permiso = :id_permiso");
            $stmt->bindParam(':id_permiso', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw $e;
        }
    }

    function findAll()
    {
        $this->connect();
        try {
            $stmt = $this->conn->query("SELECT * FROM permiso");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw $e;
        }
    }
}