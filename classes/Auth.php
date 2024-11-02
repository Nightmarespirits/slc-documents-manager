<?php
class Auth {
    private $db;
    private static $instance = null;

    private function __construct() {
        $this->db = Database::getInstance();
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function login($email, $password) {
        $stmt = $this->db->prepare("SELECT * FROM USUARIOS WHERE EMAIL = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        

        // Obtener el resultado
        if ($result->num_rows > 0) {
            echo "Usuario encontrado.";
        } else {
            echo "Usuario no encontrado.";
        }
        if ($user = $result->fetch_assoc()) {

            if (password_verify($password, $user['CONTRASENA'])) {
                // Actualizar último login
                $updateStmt = $this->db->prepare("UPDATE USUARIOS SET ULTIMO_LOGIN = CURRENT_TIMESTAMP WHERE ID = ?");
                $updateStmt->bind_param("i", $user['ID']);
                $updateStmt->execute();

                // Iniciar sesión
                $_SESSION['user_id'] = $user['ID'];
                $_SESSION['user_name'] = $user['NOMBRE'];
                $_SESSION['user_email'] = $user['EMAIL'];
                $_SESSION['user_rol'] = $user['ROL_ID'];
                
                return true;
            }
        }
        return false;
    }

    public function logout() {
        session_destroy();
        return true;
    }

    public function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }

    public function getCurrentUser() {
        if ($this->isLoggedIn()) {
            $user = new User();
            $user->loadById($_SESSION['user_id']);
            return $user;
        }
        return null;
    }
}