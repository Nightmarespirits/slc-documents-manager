<?php
// classes/User.php
class User {
    private $db;
    private $id;
    private $nombre;
    private $email;
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

    // Getters
    public function getId() { return $this->id; }
    public function getNombre() { return $this->nombre; }
    public function getEmail() { return $this->email; }
    public function getRolId() { return $this->rol_id; }
}

