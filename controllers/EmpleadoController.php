<?php
// controllers/EmpleadoController.php
session_start();
require_once __DIR__ . '/../config/conexion.php';

class EmpleadoController {

    private function requireEmpleado() {
        if (!isset($_SESSION['dni']) || $_SESSION['tipo'] !== 'empleado') {
            header('Location: index.php');
            exit();
        }
    }

    public function panel() {
        $this->requireEmpleado();
        global $conexion;

        $dni = $_SESSION['dni'];

        $sql = "SELECT DNI_Personal, ApellidosNombres
                  FROM empleado
                 WHERE DNI_Personal = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param('s', $dni);
        $stmt->execute();
        $empleado = $stmt->get_result()->fetch_assoc();

        require __DIR__ . '/../views/empleado/panel.php';
    }

    // 1) Ver horarios mensuales del propio empleado
    public function verHorariosMensuales() {
        $this->requireEmpleado();
        global $conexion;

        $dni  = $_SESSION['dni'];
        $anio = isset($_POST['Anio']) ? intval($_POST['Anio']) : intval(date('Y'));
        $mes  = isset($_POST['Mes'])  ? intval($_POST['Mes'])  : intval(date('n'));

        // Datos del empleado
        $sql_emp = "SELECT DNI_Personal, ApellidosNombres
                      FROM empleado
                     WHERE DNI_Personal = ?";
        $stmt_emp = $conexion->prepare($sql_emp);
        $stmt_emp->bind_param('s', $dni);
        $stmt_emp->execute();
        $empleado = $stmt_emp->get_result()->fetch_assoc();

        $dias_mes = cal_days_in_month(CAL_GREGORIAN, $mes, $anio);
        $turnos = array_fill(1, $dias_mes, null); // [dia] = 'M'/'T'/'N' o null

        $sql_turnos = "SELECT Dia, TipoTurno
                         FROM turno_empleado
                        WHERE DNI = ? AND Mes = ?
                        ORDER BY Dia";
        $stmt_t = $conexion->prepare($sql_turnos);
        $stmt_t->bind_param('si', $dni, $mes);
        $stmt_t->execute();
        $res_t = $stmt_t->get_result();
        while ($row = $res_t->fetch_assoc()) {
            $turnos[(int)$row['Dia']] = $row['TipoTurno'];
        }

        require __DIR__ . '/../views/empleado/ver_horarios_mensuales.php';
    }

    // 2) Formulario para solicitar permiso especial (día/mes/año actual por defecto)
    public function solicitarPermiso() {
        $this->requireEmpleado();
        global $conexion;

        $dni  = $_SESSION['dni'];
        $anio = isset($_POST['Anio']) ? intval($_POST['Anio']) : intval(date('Y'));
        $mes  = isset($_POST['Mes'])  ? intval($_POST['Mes'])  : intval(date('n'));
        $dia  = isset($_POST['Dia'])  ? intval($_POST['Dia'])  : intval(date('j'));

        $mensaje_error = '';
        $mensaje_ok    = '';

        // Datos del empleado
        $sql_emp = "SELECT DNI_Personal, ApellidosNombres
                      FROM empleado
                     WHERE DNI_Personal = ?";
        $stmt_emp = $conexion->prepare($sql_emp);
        $stmt_emp->bind_param('s', $dni);
        $stmt_emp->execute();
        $empleado = $stmt_emp->get_result()->fetch_assoc();

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['guardar_permiso'])) {
            $tipoTurno = $_POST['TipoTurno'] ?? 'M'; // M/T/N
            $esDescanso        = 0;
            $esPermisoEspecial = 1;

            try {
                $sql_call = "CALL sp_insert_turno(?, ?, ?, ?, ?, ?)";
                $stmt_ins = $conexion->prepare($sql_call);
                $stmt_ins->bind_param(
                    'iissii',
                    $mes,
                    $dia,
                    $dni,
                    $tipoTurno,
                    $esDescanso,
                    $esPermisoEspecial
                );
                $stmt_ins->execute();
                $mensaje_ok = "Permiso registrado correctamente para el día $dia/$mes/$anio.";
            } catch (mysqli_sql_exception $e) {
                $mensaje_error = $e->getMessage();
            }
        }

        $dias_mes = cal_days_in_month(CAL_GREGORIAN, $mes, $anio);

        require __DIR__ . '/../views/empleado/solicitar_permiso.php';
    }

    // 3) Ver permisos (y descansos) del empleado
    public function verPermisos() {
        $this->requireEmpleado();
        global $conexion;

        $dni  = $_SESSION['dni'];
        $anio = isset($_POST['Anio']) ? intval($_POST['Anio']) : intval(date('Y'));
        $mes  = isset($_POST['Mes'])  ? intval($_POST['Mes'])  : intval(date('n'));

        // Datos del empleado
        $sql_emp = "SELECT DNI_Personal, ApellidosNombres
                      FROM empleado
                     WHERE DNI_Personal = ?";
        $stmt_emp = $conexion->prepare($sql_emp);
        $stmt_emp->bind_param('s', $dni);
        $stmt_emp->execute();
        $empleado = $stmt_emp->get_result()->fetch_assoc();

        $permisos = [];

        $sql_perm = "SELECT Dia, TipoTurno, EsDescanso, EsPermisoEspecial
                       FROM turno_empleado
                      WHERE DNI = ?
                        AND Mes = ?
                        AND (EsDescanso = 1 OR EsPermisoEspecial = 1)
                      ORDER BY Dia, TipoTurno";
        $stmt_p = $conexion->prepare($sql_perm);
        $stmt_p->bind_param('si', $dni, $mes);
        $stmt_p->execute();
        $res_p = $stmt_p->get_result();
        while ($row = $res_p->fetch_assoc()) {
            $permisos[] = $row;
        }

        require __DIR__ . '/../views/empleado/ver_permisos.php';
    }
}
