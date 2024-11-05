<?php
class Permission {
    private $db;
    private $id;
    private $nombre;
    private $descripcion;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function loadById($id) {
        $stmt = $this->db->prepare("SELECT * FROM PERMISOS WHERE ID = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($row = $result->fetch_assoc()) {
            $this->setPermissionData($row);
            return true;
        }
        return false;
    }

    private function setPermissionData($data) {
        $this->id = $data['ID'];
        $this->nombre = $data['NOMBRE'];
        $this->descripcion = $data['DESCRIPCION'];
    }

    // CRUD Operations
    public function create($nombre, $descripcion) {
        $stmt = $this->db->prepare("INSERT INTO PERMISOS (NOMBRE, DESCRIPCION) VALUES (?, ?)");
        $stmt->bind_param("ss", $nombre, $descripcion);
        return $stmt->execute();
    }

    public function update($id, $nombre, $descripcion) {
        $stmt = $this->db->prepare("UPDATE PERMISOS SET NOMBRE = ?, DESCRIPCION = ? WHERE ID = ?");
        $stmt->bind_param("ssi", $nombre, $descripcion, $id);
        return $stmt->execute();
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM PERMISOS WHERE ID = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    // Static methods for permission management
    public static function getAllPermissions() {
        $db = Database::getInstance();
        return $db->query("SELECT * FROM PERMISOS ORDER BY NOMBRE");
    }

    public static function getRolePermissions($rolId) {
        $db = Database::getInstance();
        $stmt = $db->prepare("
            SELECT p.* 
            FROM PERMISOS p
            JOIN ROL_PERMISOS rp ON p.ID = rp.PERMISO_ID
            WHERE rp.ROL_ID = ?
        ");
        $stmt->bind_param("i", $rolId);
        $stmt->execute();
        return $stmt->get_result();
    }

    public static function assignPermissionToRole($rolId, $permisoId) {
        $db = Database::getInstance();
        $stmt = $db->prepare("INSERT INTO ROL_PERMISOS (ROL_ID, PERMISO_ID) VALUES (?, ?)");
        $stmt->bind_param("ii", $rolId, $permisoId);
        return $stmt->execute();
    }

    public static function removePermissionFromRole($rolId, $permisoId) {
        $db = Database::getInstance();
        $stmt = $db->prepare("DELETE FROM ROL_PERMISOS WHERE ROL_ID = ? AND PERMISO_ID = ?");
        $stmt->bind_param("ii", $rolId, $permisoId);
        return $stmt->execute();
    }

    // Getters
    public function getId() { return $this->id; }
    public function getNombre() { return $this->nombre; }
    public function getDescripcion() { return $this->descripcion; }
}