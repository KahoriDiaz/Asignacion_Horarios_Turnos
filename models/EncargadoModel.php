<?php
// models/EncargadoModel.php
require_once __DIR__ . '/../config/conexion.php';

class EncargadoModel {

    private $db;

    public function __construct() {
        global $conexion;
        $this->db = $conexion;
    }

    public function obtenerPisoPorDniEncargado($dni) {
        $sql = "SELECT Nro_Piso FROM Personal_Enfermeria WHERE DNI_Encargado = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('s', $dni);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();
        return $res ? $res['Nro_Piso'] : null;
    }

    public function contarEmpleadosPiso($piso) {
        $sql = "SELECT COUNT(*) AS cantidad_empleados FROM personal_por_piso WHERE Nro_Piso = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $piso);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc()['cantidad_empleados'];
    }

    public function contarSalasPiso($piso) {
        $sql = "SELECT COUNT(*) AS cantidad_salas
                  FROM sala
                 WHERE NumeroSala IN (
                       SELECT NumeroSala FROM distribucion_anual_salas WHERE Nro_Piso = ?
                 )";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $piso);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc()['cantidad_salas'];
    }

    public function contarPersonalAsignadoSalaPiso($piso) {
        $sql = "SELECT COUNT(DISTINCT pas.DNI_Personal) AS cantidad_personal_asignado
                  FROM personal_asignado_sala pas
                  JOIN distribucion_anual_salas das
                    ON pas.NumeroSala = das.NumeroSala
                   AND pas.Anio_Distribucion = das.Anio_Distribucion
                 WHERE das.Nro_Piso = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $piso);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc()['cantidad_personal_asignado'];
    }

    public function salaMayorCapacidadPiso($piso) {
        $sql = "SELECT s.NumeroSala, s.NombreSala, s.Capacidad
                  FROM sala s
                  JOIN distribucion_anual_salas das ON s.NumeroSala = das.NumeroSala
                 WHERE das.Nro_Piso = ?
              ORDER BY s.Capacidad DESC
                 LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $piso);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
}
