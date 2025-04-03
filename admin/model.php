<?php
require_once __DIR__ . '/config.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

class Model
{
    var $conn;
    var $types;
    var $max_size;

    function __construct()
    {
        $this->types = [
            'image/jpeg',
            'image/png',
            'image/gif',
            'image/jpg'
        ];
        $this->max_size = 1024 * 1024 * 2;
    }

    function get_types()
    {
        return $this->types;
    }

    function get_max_size()
    {
        return $this->max_size;
    }


    function connect()
    {
        $this->conn = new PDO(SGBD . ':host=' . HOST . ';dbname=' . DB, USER, PASSWORD);
    }

    function alert($alert)
    {
        include './views/alert.php';
    }

    function load_image()
    {
        if (isset($_FILES)) {
            $images = $_FILES;
            foreach ($images as $image) {
                if ($image['error'] == 0) {
                    if ($image['size'] <= $this->max_size + 1) {
                        if (in_array($image['type'], $this->get_types())) {
                            $ext = explode('.', $image['name']);
                            $ext = $ext[count($ext) - 1];
                            $name = md5($image['name']) . random_int(1, 1000000) . '.' . $ext;
                            if (!file_exists(UPLOAD_DIR . $name)) {
                                if (move_uploaded_file($image['tmp_name'], UPLOAD_DIR . $name)) {
                                    return $name;
                                } else {
                                    return false;
                                }
                            }
                        }
                    }
                }
            }
        }
        return false;
    }

    function validateMail($mail)
    {
        $this->connect();
        $query = $this->conn->prepare('SELECT * FROM usuario WHERE correo = :mail');
        $query->bindParam(':mail', $mail, PDO::PARAM_STR);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    function login($mail, $password)
    {

        $roles = [];
        $permissions = [];
        $password = md5($password);
        if ($this->validateMail($mail)) {
            $this->connect();
            $query = 'SELECT * FROM usuario WHERE correo = :mail AND password = :password';
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':mail', $mail, PDO::PARAM_STR);
            $stmt->bindParam(':password', $password, PDO::PARAM_STR);
            $stmt->execute();
            $result = $stmt->fetch();
            if ($result) {
                $_SESSION['validated'] = true;
                $_SESSION['mail'] = $mail;
                // get roles
                $sql = "select r.rol from usuario u join usuario_rol ur on u.id_usuario = ur.id_usuario join rol r on ur.id_rol = r.id_rol where u.correo = :mail";
                $stmt = $this->conn->prepare($sql);
                $stmt->bindParam(':mail', $mail, PDO::PARAM_STR);
                $stmt->execute();
                $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach ($res as $rol) {
                    $roles[] = $rol['rol'];
                }
                $_SESSION['roles'] = $roles;
                // get permissions
                $sql = "select p.permiso from usuario u join usuario_rol ur on u.id_usuario = ur.id_usuario join rol r on ur.id_rol = r.id_rol join permiso_rol rp on r.id_rol = rp.id_rol join permiso p on rp.id_permiso = p.id_permiso where u.correo = :mail";
                $stmt = $this->conn->prepare($sql);
                $stmt->bindParam(':mail', $mail, PDO::PARAM_STR);
                $stmt->execute();
                $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach ($res as $permiso) {
                    $permissions[] = $permiso['permiso'];
                }
                $_SESSION['permissions'] = $permissions;
                return true;
            } else {
                return false;
            }
        }

    }

    function logout()
    {
        unset($_SESSION['validated']);
        unset($_SESSION['mail']);
        unset($_SESSION['roles']);
        unset($_SESSION['permissions']);
        session_destroy();
        return true;
    }

    function changePassword($mail)
    {
        if ($this->validateMail($mail)) {
            // generate token
            $blowFish = "$2y$10$";
            $token = md5($blowFish . $mail) . md5($blowFish . random_int(1, 1000000));
            $this->connect();
            $this->conn->beginTransaction();
            try {
                $sql = 'update usuario set token = :token where correo = :mail';
                $stmt = $this->conn->prepare($sql);
                $stmt->bindParam(':token', $token, PDO::PARAM_STR);
                $stmt->bindParam(':mail', $mail, PDO::PARAM_STR);
                $stmt->execute();
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                $this->conn->commit();
                return true;
            } catch (PDOException $e) {
                $this->conn->rollBack();
                throw new Exception($e->getMessage());
            }
        }
        return false;
    }

    function restorePassword($password, $mail, $token)
    {
        if ($this->restore($mail, $token)) {
            $this->connect();
            $this->conn->beginTransaction();
            try {
                $sql = 'update usuario set password = :password where correo = :mail and token = :token';
                $stmt = $this->conn->prepare($sql);
                $pass = md5($password);
                $stmt->bindParam(':password', $pass, PDO::PARAM_STR);
                $stmt->bindParam(':mail', $mail, PDO::PARAM_STR);
                $stmt->bindParam(':token', $token, PDO::PARAM_STR);
                $stmt->execute();
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($result) {
                    $sql = 'update usuario set token = null where correo = :mail';
                    $stmt = $this->conn->prepare($sql);
                    $stmt->bindParam(':mail', $mail, PDO::PARAM_STR);
                    $stmt->execute();
                }
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($result) {
                    $this->conn->commit();
                    $alerta = [];
                    $alerta['type'] = 'success';
                    $alerta['message'] = 'Contraseña restablecida correctamente.';
                    $this->alert($alerta);
                    return true;
                }
            } catch (PDOException $e) {
                $this->conn->rollBack();
                throw new Exception($e->getMessage());
            }
        }
        return false;
    }

    function check($rol)
    {
        if (isset($_SESSION['validated'])) {
            $roles = isset($_SESSION['roles']) ? $_SESSION['roles'] : [];
            if (in_array($rol, $roles)) {
                return true;
            }
        }
        ob_clean();
        $alerta = [];
        $alerta['type'] = 'danger';
        $alerta['message'] = 'No tienes permisos para acceder a esta página.';
        $this->alert($alerta);
        die();
    }

    function check_permission($permission)
    {
        if (isset($_SESSION['validated'])) {
            $permissions = isset($_SESSION['permissions']) ? $_SESSION['permissions'] : [];
            if (in_array($permission, $permissions)) {
                return true;
            }
        }
        return false;
    }

    function sendEmail($mail, $header, $body)
    {
        require __DIR__ . '/vendor/autoload.php';
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->SMTPDebug = SMTP::DEBUG_SERVER;
        $mail->Host = SMTP_HOST;
        $mail->SMTPAuth = true;
        $mail->Username = SMTP_USER;
        $mail->Password = SMTP_PASSWORD;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = SMTP_PORT;
        $mail->setFrom(SMTP_USER, 'Vinedos');
        $mail->addAddress($mail);
        $mail->isHTML(true);
        $mail->Subject = $header;
        $mail->Body = $body;
        $mail->AltBody = strip_tags($body);
        if (!$mail->send()) {
            return false;
        } else {
            return true;
        }
    }

    function restore($mail, $token)
    {
        $this->connect();
        $sql = 'SELECT * FROM usuario WHERE correo = :mail AND token = :token';
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':mail', $mail, PDO::PARAM_STR);
        $stmt->bindParam(':token', $token, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if (isset($result['mail']))
            return true;
        return false;
    }
}