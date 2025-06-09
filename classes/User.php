<?php
// classes/User.php
class User {
    private $db;
    private $id;
    private $nombre;
    private $email;
    private $telefono;
    private $rol_id;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function loadById($id) {
        $stmt = $this->db->prepare("SELECT * FROM USUARIOS WHERE ID = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $this->setUserData($row);
            return true;
        }
        return false;
    }

    private function setUserData($data) {
        $this->id = $data['ID'];
        $this->nombre = $data['NOMBRE'];
        $this->email = $data['EMAIL'];
        $this->telefono = $data['TELEFONO'] ?? null;
        $this->rol_id = $data['ROL_ID'];
    }

    public function hasPermission($permissionName) {
        $sql = "SELECT 1 FROM ROL_PERMISOS rp
                JOIN PERMISOS p ON p.ID = rp.PERMISO_ID
                WHERE rp.ROL_ID = ? AND p.NOMBRE = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("is", $this->rol_id, $permissionName);
        $stmt->execute();
        return $stmt->get_result()->num_rows > 0;
    }

    public function emailExists($email) {
        $stmt = $this->db->prepare("SELECT ID FROM USUARIOS WHERE EMAIL = ? AND ID != ?");
        $stmt->bind_param("si", $email, $this->id);
        $stmt->execute();
        return $stmt->get_result()->num_rows > 0;
    }

    public function updateProfile($nombre, $email, $telefono) {
        $stmt = $this->db->prepare("UPDATE USUARIOS SET NOMBRE = ?, EMAIL = ?, TELEFONO = ? WHERE ID = ?");
        $stmt->bind_param("sssi", $nombre, $email, $telefono, $this->id);
        if ($stmt->execute()) {
            $this->nombre = $nombre;
            $this->email = $email;
            $this->telefono = $telefono;
            return true;
        }
        return false;
    }

    public function verifyPassword($password) {
        $stmt = $this->db->prepare("SELECT CONTRASENA FROM USUARIOS WHERE ID = ?");
        $stmt->bind_param("i", $this->id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $hashed = hash('sha256', $password);
            return $hashed === $row['CONTRASENA'] || password_verify($password, $row['CONTRASENA']);
        }
        return false;
    }

    public function updatePassword($password) {
        $hash = hash('sha256', $password);
        $stmt = $this->db->prepare("UPDATE USUARIOS SET CONTRASENA = ? WHERE ID = ?");
        $stmt->bind_param("si", $hash, $this->id);
        return $stmt->execute();
    }

    public function getActivityLog() {
        $stmt = $this->db->prepare("SELECT * FROM ACTIVIDAD_USUARIO WHERE USUARIO_ID = ? ORDER BY FECHA DESC");
        $stmt->bind_param("i", $this->id);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function logActivity($accion, $detalles) {
        $stmt = $this->db->prepare("INSERT INTO ACTIVIDAD_USUARIO (USUARIO_ID, ACCION, DETALLES) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $this->id, $accion, $detalles);
        $stmt->execute();
    }

    // Getters
    public function getId() { return $this->id; }
    public function getNombre() { return $this->nombre; }
    public function getEmail() { return $this->email; }
    public function getTelefono() { return $this->telefono; }
    public function getRolId() { return $this->rol_id; }
}

