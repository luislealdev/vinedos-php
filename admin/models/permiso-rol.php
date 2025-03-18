<?php
require_once __DIR__ . '/../model.php';

class PermisoRol extends Model
{
    function create($data)
    {
        if (empty($data['id_permiso']) || empty($data['id_rol'])) {
            return false;
        }
    
        $this->connect();
        try {
            $sql = "INSERT INTO permiso_rol (id_permiso, id_rol) VALUES (:id_permiso, :id_rol)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id_permiso', $data['id_permiso'], PDO::PARAM_INT); // Cambiado a INT
            $stmt->bindParam(':id_rol', $data['id_rol'], PDO::PARAM_INT);         // Cambiado a INT
            $stmt->execute();
            return $stmt->rowCount();
        } catch (PDOException $e) {
            throw $e;
        }
    }

    function update($data, $id)
    {
        $data['permiso-rol'] = trim($data['permiso-rol'] ?? '');
        if (strlen($data['permiso-rol']) > 30) {
            return false;
        }

        $this->connect();
        try {
            // Verificar duplicados excluyendo el registro actual
            $sql = "SELECT COUNT(*) as count FROM permiso-rol WHERE permiso-rol = :permiso-rol AND id_permiso-rol != :id_permiso-rol";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':permiso-rol', $data['permiso-rol'], PDO::PARAM_STR);
            $stmt->bindParam(':id_permiso-rol', $id, PDO::PARAM_INT);
            $stmt->execute();
            if ($stmt->fetchColumn() > 0) {
                return false; // Ya existe otro permiso-rol con ese nombre
            }

            $sql = "UPDATE permiso-rol SET permiso-rol = :permiso-rol WHERE id_permiso-rol = :id_permiso-rol";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':permiso-rol', $data['permiso-rol'], PDO::PARAM_STR);
            $stmt->bindParam(':id_permiso-rol', $id, PDO::PARAM_INT);
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
            $sql = "SELECT COUNT(*) FROM permiso-rol_rol WHERE id_permiso-rol = :id_permiso-rol";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id_permiso-rol', $id, PDO::PARAM_INT);
            $stmt->execute();
            if ($stmt->fetchColumn() > 0) {
                return false; // No se puede eliminar porque está en uso
            }

            $sql = "DELETE FROM permiso-rol WHERE id_permiso-rol = :id_permiso-rol";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id_permiso-rol', $id, PDO::PARAM_INT);
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
            $stmt = $this->conn->prepare("SELECT * FROM permiso-rol WHERE id_permiso-rol = :id_permiso-rol");
            $stmt->bindParam(':id_permiso-rol', $id, PDO::PARAM_INT);
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
            $stmt = $this->conn->query("SELECT * FROM permiso_rol");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw $e;
        }
    }
}