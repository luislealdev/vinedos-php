<?php
require_once __DIR__ . '/../model.php';

class Rol extends Model
{
    function create($data)
    {

        if (isset($data['rol'])) {
            if (strlen($data['rol']) > 30) {
                return false;
            }
        }

        $this->connect();
        $this->conn->beginTransaction();
        try {
            $sql = "INSERT INTO rol (rol) VALUES (:rol)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':rol', $data['rol'], PDO::PARAM_STR);
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
        $data['rol'] = trim($data['rol']);
    
        // Validar longitud
        if (strlen($data['rol']) > 30) {
            return false;
        }
    
        $this->connect();
        
        try {
            // Verificar si el rol ya existe (excluyendo el registro actual)
            $sql = "SELECT COUNT(*) as count FROM rol WHERE rol = :rol AND id_rol != :id_rol";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':rol', $data['rol'], PDO::PARAM_STR);
            $stmt->bindParam(':id_rol', $id, PDO::PARAM_INT);
            $stmt->execute();
            
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($result && $result['count'] > 0) {
                return false; // El rol ya existe en otro registro
            }
    
            // Actualizar
            $sql = "UPDATE rol SET rol = :rol WHERE id_rol = :id_rol";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':rol', $data['rol'], PDO::PARAM_STR);
            $stmt->bindParam(':id_rol', $id, PDO::PARAM_INT);
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
            $sql = "DELETE FROM rol WHERE id_rol = :id_rol";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id_rol', $id, PDO::PARAM_INT);
            $stmt->execute();
            
            $count = $stmt->rowCount();
            return $count;  // Devuelve el número de filas afectadas (0 si no existía, 1 si se eliminó)
            
        } catch (PDOException $e) {
            throw $e;
        }
    }

    function findOne($id)
    {
        $this->connect();
        $data = $this->conn->prepare("SELECT * FROM rol WHERE id_rol = :id_rol");
        $data->bindParam(':id_rol', $id, PDO::PARAM_INT);
        $data->execute();
        $result = $data->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    function findAll()
    {
        $this->connect();
        // Get cantidad from producto
        $data = $this->conn->query("select * from rol;");
        $data->execute();
        $rols = $data->fetchAll();
        return $rols;
    }
}