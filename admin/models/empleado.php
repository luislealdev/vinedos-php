<?php
require_once __DIR__ . '/../model.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

class Empleado extends Model
{
    function create($data)
    {
        if (isset($_POST['submit'])) {
            // Basic field presence check
            if (
                isset($data['primer_apellido']) && isset($data['segundo_apellido']) &&
                isset($data['nombre']) && isset($data['nacimiento']) &&
                isset($data['rfc']) && isset($data['curp']) &&
                isset($data['correo']) && isset($data['password'])
            ) {
                // Validations
                if (strlen($data['primer_apellido']) > 50 || strlen($data['segundo_apellido']) > 50 || strlen($data['nombre']) > 50) {
                    return false; // Limit name fields to 50 characters
                }
                if (!preg_match('/^[A-Z]{4}[0-9]{6}[A-Z0-9]{3}$/', $data['rfc'])) {
                    return false; // Basic RFC format check (e.g., ABCD123456XYZ)
                }
                if (!preg_match('/^[A-Z]{4}[0-9]{6}[A-Z]{6}[0-9]{2}$/', $data['curp'])) {
                    return false; // Basic CURP format check (18 characters)
                }
                if (!filter_var($data['correo'], FILTER_VALIDATE_EMAIL)) {
                    return false; // Validate email format
                }
                if (strlen($data['password']) < 6) {
                    return false; // Minimum password length
                }
                if (!strtotime($data['nacimiento'])) {
                    return false; // Validate birth date is a valid date
                }
            } else {
                return false; // Missing required fields
            }


            $this->connect();
            $this->conn->beginTransaction();
            try {
                // Check if RFC or CURP already exists
                $sql = "SELECT rfc, curp, COUNT(*) as count FROM empleado WHERE rfc = :rfc OR curp = :curp GROUP BY rfc, curp";
                $stmt = $this->conn->prepare($sql);
                $stmt->bindParam(':rfc', $data['rfc'], PDO::PARAM_STR);
                $stmt->bindParam(':curp', $data['curp'], PDO::PARAM_STR);
                $stmt->execute();
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($result && $result['count'] > 0) {
                    $this->conn->rollBack();
                    return false; // Duplicate RFC or CURP
                }

                // Check if correo already exists in usuario
                $sql = "SELECT correo, COUNT(*) as count FROM usuario WHERE correo = :correo GROUP BY correo";
                $stmt = $this->conn->prepare($sql);
                $stmt->bindParam(':correo', $data['correo'], PDO::PARAM_STR);
                $stmt->execute();
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($result && $result['count'] > 0) {
                    $this->conn->rollBack();
                    return false; // Duplicate email
                }

                // Insert into usuario table first
                $hashedPassword = md5($data['password']); // Hash password for security
                $sql = "INSERT INTO usuario (correo, password) VALUES (:correo, :password)";
                $stmt = $this->conn->prepare($sql);
                $stmt->bindParam(':correo', $data['correo'], PDO::PARAM_STR);
                $stmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
                $stmt->execute();

                // Get the newly created id_usuario
                $id_usuario = $this->conn->lastInsertId();

                // Insert into empleado table
                $sql = "INSERT INTO empleado (primer_apellido, segundo_apellido, nombre, nacimiento, rfc, curp, id_usuario) 
                        VALUES (:primer_apellido, :segundo_apellido, :nombre, :nacimiento, :rfc, :curp, :id_usuario)";
                $stmt = $this->conn->prepare($sql);
                $stmt->bindParam(':primer_apellido', $data['primer_apellido'], PDO::PARAM_STR);
                $stmt->bindParam(':segundo_apellido', $data['segundo_apellido'], PDO::PARAM_STR);
                $stmt->bindParam(':nombre', $data['nombre'], PDO::PARAM_STR);
                $stmt->bindParam(':nacimiento', $data['nacimiento'], PDO::PARAM_STR);
                $stmt->bindParam(':rfc', $data['rfc'], PDO::PARAM_STR);
                $stmt->bindParam(':curp', $data['curp'], PDO::PARAM_STR);
                $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
                $stmt->execute();

                // Insert default roles into usuario_rol
                $sql = "INSERT INTO usuario_rol (id_usuario, id_rol) VALUES (:id_usuario, :id_rol)";
                $roles = array(1, 3); // Default roles
                foreach ($roles as $rol) {
                    $stmt = $this->conn->prepare($sql);
                    $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
                    $stmt->bindParam(':id_rol', $rol, PDO::PARAM_INT);
                    $stmt->execute();
                }

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
        // Trim inputs
        $data['primer_apellido'] = trim($data['primer_apellido']);
        $data['segundo_apellido'] = trim($data['segundo_apellido']);
        $data['nombre'] = trim($data['nombre']);
    
        // Validations (return 0 if invalid)
        if (strlen($data['primer_apellido']) > 50 || strlen($data['segundo_apellido']) > 50 || strlen($data['nombre']) > 50) {
            return 0; // Validation failure
        }
        if (!empty($data['rfc']) && !preg_match('/^[A-Z]{4}[0-9]{6}[A-Z0-9]{3}$/', $data['rfc'])) {
            return 0; // Validation failure
        }
        if (!empty($data['curp']) && !preg_match('/^[A-Z]{4}[0-9]{6}[A-Z]{6}[0-9]{2}$/', $data['curp'])) {
            return 0; // Validation failure
        }
        if (isset($data['correo']) && !filter_var($data['correo'], FILTER_VALIDATE_EMAIL)) {
            return 0; // Validation failure
        }
        if (isset($data['password']) && strlen($data['password']) < 6) {
            return 0; // Validation failure
        }
        if (isset($data['nacimiento']) && !strtotime($data['nacimiento'])) {
            return 0; // Validation failure
        }
    
        $this->connect();
        $this->conn->beginTransaction();
        try {
            // Get current id_usuario for this empleado
            $sql = "SELECT id_usuario FROM empleado WHERE id_empleado = :id_empleado";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id_empleado', $id, PDO::PARAM_INT);
            $stmt->execute();
            $current = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$current) {
                $this->conn->rollBack();
                return 0; // No empleado found
            }
            $id_usuario = $current['id_usuario'];
    
            $rowsAffected = 0; // Track total rows affected
    
            // Update usuario table if correo or password is provided
            if (!empty($data['correo']) || !empty($data['password'])) {
                $sql = "UPDATE usuario SET ";
                $params = [];
                if (!empty($data['correo'])) {
                    $sql .= "correo = :correo";
                    $params[':correo'] = $data['correo'];
                }
                if (!empty($data['password'])) {
                    $hashedPassword = md5($data['password']); // Use password_hash instead of md5
                    $sql .= (!empty($data['correo']) ? ", " : "") . "password = :password";
                    $params[':password'] = $hashedPassword;
                }
                $sql .= " WHERE id_usuario = :id_usuario";
                $params[':id_usuario'] = $id_usuario;
    
                $stmt = $this->conn->prepare($sql);
                foreach ($params as $key => $value) {
                    $stmt->bindValue($key, $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
                }
                $stmt->execute();
                $rowsAffected += $stmt->rowCount();
    
                // Check for duplicate correo excluding current user
                if (!empty($data['correo'])) {
                    $sql = "SELECT COUNT(*) as count FROM usuario WHERE correo = :correo AND id_usuario != :id_usuario";
                    $stmt = $this->conn->prepare($sql);
                    $stmt->bindParam(':correo', $data['correo'], PDO::PARAM_STR);
                    $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
                    $stmt->execute();
                    $result = $stmt->fetch(PDO::FETCH_ASSOC);
                    if ($result && $result['count'] > 0) {
                        $this->conn->rollBack();
                        return 0; // Duplicate correo found
                    }
                }
            }
    
            // Update empleado table
            $sql = "UPDATE empleado SET primer_apellido = :primer_apellido, segundo_apellido = :segundo_apellido, 
                    nombre = :nombre, nacimiento = :nacimiento, rfc = :rfc, curp = :curp 
                    WHERE id_empleado = :id_empleado";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':primer_apellido', $data['primer_apellido'], PDO::PARAM_STR);
            $stmt->bindParam(':segundo_apellido', $data['segundo_apellido'], PDO::PARAM_STR);
            $stmt->bindParam(':nombre', $data['nombre'], PDO::PARAM_STR);
            $stmt->bindParam(':nacimiento', $data['nacimiento'], PDO::PARAM_STR);
            $stmt->bindParam(':rfc', $data['rfc'], PDO::PARAM_STR);
            $stmt->bindParam(':curp', $data['curp'], PDO::PARAM_STR);
            $stmt->bindParam(':id_empleado', $id, PDO::PARAM_INT);
            $stmt->execute();
            $rowsAffected += $stmt->rowCount();
    
            // Check for duplicates excluding the current record
            $sql = "SELECT rfc, curp, COUNT(*) as count FROM empleado WHERE (rfc = :rfc OR curp = :curp) AND id_empleado != :id_empleado GROUP BY rfc, curp";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':rfc', $data['rfc'], PDO::PARAM_STR);
            $stmt->bindParam(':curp', $data['curp'], PDO::PARAM_STR);
            $stmt->bindParam(':id_empleado', $id, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($result && $result['count'] > 0) {
                $this->conn->rollBack();
                return 0; // Duplicate RFC or CURP found
            }
    
            $this->conn->commit();
            return $rowsAffected > 0 ? 1 : 0; // Return 1 if any rows were affected, 0 otherwise
        } catch (PDOException $e) {
            $this->conn->rollBack();
            throw $e;
        }
    }

    function delete($id)
    {
        if (!is_numeric($id) || $id < 0) {
            return false; // Validate id_empleado
        }

        $this->connect();
        $this->conn->beginTransaction();
        try {
            // Get id_usuario to delete related usuario record
            $sql = "SELECT id_usuario FROM empleado WHERE id_empleado = :id_empleado";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id_empleado', $id, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $id_usuario = $result['id_usuario'];

            // Delete from empleado
            $sql = "DELETE FROM empleado WHERE id_empleado = :id_empleado";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id_empleado', $id, PDO::PARAM_INT);
            $stmt->execute();

            // Delete from usuario_rol
            $sql = "DELETE FROM usuario_rol WHERE id_usuario = :id_usuario";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
            $stmt->execute();

            // Delete from usuario
            $sql = "DELETE FROM usuario WHERE id_usuario = :id_usuario";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
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
        $stmt = $this->conn->prepare("SELECT e.*, u.correo FROM empleado e 
                                      LEFT JOIN usuario u ON e.id_usuario = u.id_usuario 
                                      WHERE id_empleado = :id_empleado");
        $stmt->bindParam(':id_empleado', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    function findAll($id_usuario = null)
    {
        $this->connect();
        $sql = "SELECT e.*, u.correo FROM empleado e 
                LEFT JOIN usuario u ON e.id_usuario = u.id_usuario 
                ORDER BY nombre, primer_apellido, segundo_apellido";

        if (!is_null($id_usuario) && is_numeric($id_usuario)) {
            $sql = "SELECT e.*, u.correo FROM empleado e 
                    LEFT JOIN usuario u ON e.id_usuario = u.id_usuario 
                    WHERE e.id_usuario = :id_usuario 
                    ORDER BY nombre, primer_apellido, segundo_apellido";
        }

        $stmt = $this->conn->prepare($sql);
        if (!is_null($id_usuario) && is_numeric($id_usuario)) {
            $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}