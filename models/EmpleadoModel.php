<?php
// models/EmpleadoModel.php
require_once __DIR__ . '/../config/conexion.php';

class EmpleadoModel {

    private $db;

    public function __construct() {
        global $conexion;
        $this->db = $conexion;
    }

    public function obtenerPorDni($dni) {
        $sql = "SELECT ApellidosNombres FROM Empleado WHERE DNI_Personal = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('s', $dni);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function obtenerEncargadoPorDni($dni) {
        $sql = "SELECT e.ApellidosNombres, p.Nro_Piso
                  FROM empleado e
                  JOIN personal_enfermeria p
                    ON e.DNI_Personal = p.DNI_Encargado
                 WHERE e.DNI_Personal = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('s', $dni);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
}
