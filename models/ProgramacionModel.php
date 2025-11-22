<?php
require_once __DIR__ . '/../config/conexion.php';

class ProgramacionModel {

    private $db;

    public function __construct() {
        global $conexion;
        $this->db = $conexion;
    }

    public function obtenerProgramacionMensual($mes, $anio, $piso) {
        $sql = "SELECT * FROM programacion_mensual
                 WHERE Mes = ? AND Anio_Distribucion = ? AND Nro_Piso = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('iii', $mes, $anio, $piso);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function guardarTurnoEmpleado($mes, $dia, $dni, $tipoTurno) {
        $sql = "INSERT INTO turno_empleado (Mes, Dia, DNI, TipoTurno)
                VALUES (?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE TipoTurno = VALUES(TipoTurno)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('iiss', $mes, $dia, $dni, $tipoTurno);
        return $stmt->execute();
    }

    public function guardarAsignaciones(array $asignaciones) {
        foreach ($asignaciones as $asig) {
            $ok = $this->guardarTurnoEmpleado(
                $asig['Mes'],
                $asig['Dia'],
                $asig['DNI'],
                $asig['TipoTurno']
            );
            if (!$ok) {
                return false;
            }
        }
        return true;
    }

    public function obtenerPersonalSalaMes($sala, $anio) {
        $sql = "SELECT e.DNI_Personal, e.ApellidosNombres
                  FROM empleado e
                  JOIN personal_asignado_sala pas ON e.DNI_Personal = pas.DNI_Personal
                 WHERE pas.NumeroSala = ? AND pas.Anio_Distribucion = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('ii', $sala, $anio);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}
