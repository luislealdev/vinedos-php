<?php
require_once __DIR__ . '/../model.php';

class Usuario extends Model
{
    function create($data)
    {
        // Validaciones
        if (isset($data['correo']) && isset($data['password'])) {
            if (strlen($data['correo']) > 100 || strlen($data['password']) > 255) {
                return false;
            }
        }

        $this->connect();
        $this->conn->beginTransaction();
        try {
            $sql = "INSERT INTO usuario (correo, password, token) VALUES (:correo, :password, :token)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':correo', $data['correo'], PDO::PARAM_STR);
            $stmt->bindParam(':password', $data['password'], PDO::PARAM_STR);
            // Token es opcional, si no existe se guarda como NULL
            $token = isset($data['token']) ? $data['token'] : null;
            $stmt->bindParam(':token', $token, PDO::PARAM_STR);
            $stmt->execute();
            $this->conn->commit();
            return $stmt->rowCount();
        } catch (PDOException $e) {
            $this->conn->rollBack();
            throw $e;
        }
    }

    function update($data, $id)
    {
        // Trim a los campos
        if (isset($data['correo']))
            $data['correo'] = trim($data['correo']);
        if (isset($data['password']))
            $data['password'] = trim($data['password']);
        if (isset($data['token']))
            $data['token'] = trim($data['token']);

        // Validar longitud
        if (isset($data['correo']) && strlen($data['correo']) > 100) {
            return false;
        }
        if (isset($data['password']) && strlen($data['password']) > 255) {
            return false;
        }

        $this->connect();
        try {
            // Verificar si el correo ya existe (excluyendo el registro actual)
            if (isset($data['correo'])) {
                $sql = "SELECT COUNT(*) as count FROM usuario WHERE correo = :correo AND id_usuario != :id_usuario";
                $stmt = $this->conn->prepare($sql);
                $stmt->bindParam(':correo', $data['correo'], PDO::PARAM_STR);
                $stmt->bindParam(':id_usuario', $id, PDO::PARAM_INT);
                $stmt->execute();
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($result && $result['count'] > 0) {
                    return false; // El correo ya existe
                }
            }

            // Construir la consulta dinámicamente según los campos proporcionados
            $fields = [];
            $params = [':id_usuario' => $id];

            if (isset($data['correo'])) {
                $fields[] = "correo = :correo";
                $params[':correo'] = $data['correo'];
            }
            if (isset($data['password'])) {
                $fields[] = "password = :password";
                $params[':password'] = $data['password'];
            }
            if (array_key_exists('token', $data)) { // Usamos array_key_exists porque token puede ser null
                $fields[] = "token = :token";
                $params[':token'] = $data['token'];
            }

            if (empty($fields)) {
                return 0; // No hay campos para actualizar
            }

            $sql = "UPDATE usuario SET " . implode(', ', $fields) . " WHERE id_usuario = :id_usuario";
            $stmt = $this->conn->prepare($sql);
            foreach ($params as $key => &$val) {
                $stmt->bindParam($key, $val, is_int($val) ? PDO::PARAM_INT : PDO::PARAM_STR);
            }
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
            $sql = "DELETE FROM usuario WHERE id_usuario = :id_usuario";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id_usuario', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->rowCount();
        } catch (PDOException $e) {
            throw $e;
        }
    }

    function findOne($id)
    {
        $this->connect();
        $data = $this->conn->prepare("SELECT * FROM usuario WHERE id_usuario = :id_usuario");
        $data->bindParam(':id_usuario', $id, PDO::PARAM_INT);
        $data->execute();
        return $data->fetch(PDO::FETCH_ASSOC);
    }

    function findAll()
    {
        $this->connect();
        $data = $this->conn->query("SELECT * FROM usuario");
        $data->execute();
        return $data->fetchAll(PDO::FETCH_ASSOC);
    }
}