<?php
// classes/Document.php
class Document {
    private $db;
    private $id;
    private $nombre_original;
    private $nombre_generado;
    private $ruta;
    private $tipo_documento_id;
    private $area_id;
    private $tipo_archivo_id;
    private $usuario_id;
    private $version_actual;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function create($data, $file) {
        try {
            // Generar nombre único para el archivo
            $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $nombre_generado = uniqid() . '_' . time() . '.' . $extension;
            
            // Definir ruta de almacenamiento
            $upload_dir = '../assets/uploads/';
            $ruta_completa = $upload_dir . $nombre_generado;
            
            // Crear directorio si no existe
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            
            // Mover archivo
            if (!move_uploaded_file($file['tmp_name'], $ruta_completa)) {
                throw new Exception("Error al mover el archivo");
            }

            // Insertar en base de datos
            $sql = "INSERT INTO DOCUMENTOS (
                        NOMBRE_ORIGINAL, 
                        NOMBRE_GENERADO, 
                        RUTA, 
                        TIPO_DOCUMENTO_ID,
                        AREA_ID,
                        TIPO_ARCHIVO_ID,
                        USUARIO_ID,
                        EXTENSION
                    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt = $this->db->prepare($sql);
            $stmt->bind_param(
                "sssiiiis",
                $file['name'],
                $nombre_generado,
                $ruta_completa,
                $data['tipo_documento_id'],
                $data['area_id'],
                $data['tipo_archivo_id'],
                $data['usuario_id'],
                $extension
            );

            if (!$stmt->execute()) {
                // Si hay error en la BD, eliminar el archivo
                unlink($ruta_completa);
                throw new Exception("Error al guardar en la base de datos");
            }

            $this->id = $stmt->insert_id;
            return true;

        } catch (Exception $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM DOCUMENTOS WHERE ID = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($row = $result->fetch_assoc()) {
            $this->setDocumentData($row);
            return true;
        }
        return false;
    }

    private function setDocumentData($data) {
        $this->id = $data['ID'];
        $this->nombre_original = $data['NOMBRE_ORIGINAL'];
        $this->nombre_generado = $data['NOMBRE_GENERADO'];
        $this->ruta = $data['RUTA'];
        $this->tipo_documento_id = $data['TIPO_DOCUMENTO_ID'];
        $this->area_id = $data['AREA_ID'];
        $this->tipo_archivo_id = $data['TIPO_ARCHIVO_ID'];
        $this->usuario_id = $data['USUARIO_ID'];
        $this->version_actual = $data['VERSION_ACTUAL'];
    }

    public function getAllByUser($usuario_id) {
        $sql = "SELECT d.*, td.DESCRIPCION as TIPO_DOCUMENTO, a.NOMBRE as AREA, 
                ta.DESCRIPCION as TIPO_ARCHIVO, u.NOMBRE as USUARIO
                FROM DOCUMENTOS d
                JOIN TIPOS_DOCUMENTO td ON d.TIPO_DOCUMENTO_ID = td.ID
                JOIN AREAS a ON d.AREA_ID = a.ID
                JOIN TIPOS_ARCHIVO ta ON d.TIPO_ARCHIVO_ID = ta.ID
                JOIN USUARIOS u ON d.USUARIO_ID = u.ID
                WHERE d.USUARIO_ID = ?
                ORDER BY d.FECHA_INSERCION DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $usuario_id);
        $stmt->execute();
        return $stmt->get_result();
    }

    // Getters
    public function getId() { return $this->id; }
    public function getNombreOriginal() { return $this->nombre_original; }
    public function getNombreGenerado() { return $this->nombre_generado; }
    public function getRuta() { return $this->ruta; }
    public function getTipoDocumentoId() { return $this->tipo_documento_id; }
    public function getAreaId() { return $this->area_id; }
    public function getTipoArchivoId() { return $this->tipo_archivo_id; }
    public function getUsuarioId() { return $this->usuario_id; }
    public function getVersionActual() { return $this->version_actual; }
}