<?php
// models/SalaModel.php
require_once __DIR__ . '/../config/conexion.php';

class SalaModel {

    private $db;

    public function __construct() {
        global $conexion;
        $this->db = $conexion;
    }

    public function obtenerSalasPorPisoYAnio($piso, $anio) {
        $sql = "SELECT s.NumeroSala, s.NombreSala, s.Capacidad
                  FROM sala s
                  JOIN distribucion_anual_salas d ON s.NumeroSala = d.NumeroSala
                 WHERE d.Nro_Piso = ? AND d.Anio_Distribucion = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('ii', $piso, $anio);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function obtenerSalasPisoAnioResult($piso, $anio) {
        $sql = "SELECT s.NumeroSala, s.NombreSala 
                  FROM Sala s
                  JOIN Distribucion_Anual_Salas d ON s.NumeroSala = d.NumeroSala
                 WHERE d.Nro_Piso = ? AND d.Anio_Distribucion = ?
              ORDER BY s.NumeroSala";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('ii', $piso, $anio);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function obtenerAsignadosSala($sala, $anio) {
        $sql = "SELECT DNI_Personal
                  FROM Personal_Asignado_Sala
                 WHERE NumeroSala = ? AND Anio_Distribucion = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('ii', $sala, $anio);
        $stmt->execute();
        $res = $stmt->get_result();
        $asignados = [];
        while ($fila = $res->fetch_assoc()) {
            $asignados[] = $fila['DNI_Personal'];
        }
        return $asignados;
    }

    public function obtenerEmpleadosParaSala($sala, $piso) {
        $sql = "SELECT e.DNI_Personal, e.ApellidosNombres 
                  FROM Empleado e
                  JOIN Personal_Por_Piso p ON e.DNI_Personal = p.DNI_Personal
                  JOIN Sala s ON s.NumeroSala = ?
                 WHERE p.Nro_Piso = ? AND e.Subespecialidad = s.NombreSala";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('ii', $sala, $piso);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function existeSalaDistribucion($sala, $anio) {
        $sql = "SELECT 1
                  FROM distribucion_anual_salas
                 WHERE NumeroSala = ? AND Anio_Distribucion = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('ii', $sala, $anio);
        $stmt->execute();
        return $stmt->get_result()->num_rows > 0;
    }

    public function borrarAsignacionesSala($sala, $anio) {
        $sql = "DELETE FROM personal_asignado_sala
                 WHERE NumeroSala = ? AND Anio_Distribucion = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('ii', $sala, $anio);
        return $stmt->execute();
    }

    public function insertarAsignacionSala($sala, $anio, $dniPersonal, $rol = 'Enfermero') {
        $sql = "INSERT INTO personal_asignado_sala
                    (Anio_Distribucion, NumeroSala, DNI_Personal, Rol)
                VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('iiss', $anio, $sala, $dniPersonal, $rol);
        return $stmt->execute();
    }
}
