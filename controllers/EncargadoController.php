<?php
// controllers/EncargadoController.php
session_start();
require_once __DIR__ . '/../config/conexion.php';

class EncargadoController {

    private function requireEncargado() {
        if (!isset($_SESSION['dni']) || $_SESSION['tipo'] !== 'encargado') {
            header('Location: index.php');
            exit();
        }
    }

    public function panel() {
        $this->requireEncargado();
        global $conexion;

        $dni = $_SESSION['dni'];

        // Datos del encargado y piso
        $sql_encargado = "SELECT e.ApellidosNombres, p.Nro_Piso 
                            FROM empleado e 
                            JOIN personal_enfermeria p ON e.DNI_Personal = p.DNI_Encargado 
                           WHERE e.DNI_Personal = ?";
        $stmt = $conexion->prepare($sql_encargado);
        $stmt->bind_param("s", $dni);
        $stmt->execute();
        $res_encargado = $stmt->get_result();
        $encargado = $res_encargado->fetch_assoc();
        $nro_piso = $encargado['Nro_Piso'];

        // Métricas
        $sql_empleados = "SELECT COUNT(*) AS cantidad_empleados 
                            FROM personal_por_piso 
                           WHERE Nro_Piso = ?";
        $stmt_empleados = $conexion->prepare($sql_empleados);
        $stmt_empleados->bind_param("i", $nro_piso);
        $stmt_empleados->execute();
        $cantidad_empleados = $stmt_empleados->get_result()->fetch_assoc()['cantidad_empleados'];

        $sql_salas = "SELECT COUNT(*) AS cantidad_salas 
                        FROM sala 
                       WHERE NumeroSala IN (
                             SELECT NumeroSala 
                               FROM distribucion_anual_salas 
                              WHERE Nro_Piso = ?
                       )";
        $stmt_salas = $conexion->prepare($sql_salas);
        $stmt_salas->bind_param("i", $nro_piso);
        $stmt_salas->execute();
        $cantidad_salas = $stmt_salas->get_result()->fetch_assoc()['cantidad_salas'];

        $sql_asignados = "SELECT COUNT(DISTINCT pas.DNI_Personal) AS cantidad_personal_asignado
                            FROM personal_asignado_sala pas
                            JOIN distribucion_anual_salas das 
                              ON pas.NumeroSala = das.NumeroSala 
                             AND pas.Anio_Distribucion = das.Anio_Distribucion
                           WHERE das.Nro_Piso = ?";
        $stmt_asignados = $conexion->prepare($sql_asignados);
        $stmt_asignados->bind_param("i", $nro_piso);
        $stmt_asignados->execute();
        $cantidad_personal_asignado = $stmt_asignados->get_result()->fetch_assoc()['cantidad_personal_asignado'];

        $sql_max_sala = "SELECT s.NumeroSala, s.NombreSala, s.Capacidad
                           FROM sala s
                           JOIN distribucion_anual_salas das ON s.NumeroSala = das.NumeroSala
                          WHERE das.Nro_Piso = ?
                       ORDER BY s.Capacidad DESC
                          LIMIT 1";
        $stmt_max_sala = $conexion->prepare($sql_max_sala);
        $stmt_max_sala->bind_param("i", $nro_piso);
        $stmt_max_sala->execute();
        $sala_max = $stmt_max_sala->get_result()->fetch_assoc();

        require __DIR__ . '/../views/encargado/panel.php';
    }

public function seleccionarSala() {
    $this->requireEncargado();
    global $conexion;

    $dni_encargado = $_SESSION['dni'];

    // Piso del encargado
    $sql_piso = "SELECT Nro_Piso FROM personal_enfermeria WHERE DNI_Encargado = ?";
    $stmt_piso = $conexion->prepare($sql_piso);
    $stmt_piso->bind_param('s', $dni_encargado);
    $stmt_piso->execute();
    $piso = $stmt_piso->get_result()->fetch_assoc()['Nro_Piso'];

    // Año seleccionado (por GET), por defecto año actual
    $anio = isset($_GET['Anio']) ? intval($_GET['Anio']) : intval(date('Y'));

    $sala_seleccionada = isset($_GET['sala']) ? intval($_GET['sala']) : 0;

    // Salas del piso para el año seleccionado
    $sql_salas = "SELECT s.NumeroSala, s.NombreSala
                    FROM sala s
                    JOIN distribucion_anual_salas d ON s.NumeroSala = d.NumeroSala
                   WHERE d.Nro_Piso = ? AND d.Anio_Distribucion = ?
                ORDER BY s.NumeroSala";
    $stmt_salas = $conexion->prepare($sql_salas);
    $stmt_salas->bind_param('ii', $piso, $anio);
    $stmt_salas->execute();
    $res_salas = $stmt_salas->get_result();

    // Empleados ya asignados a esa sala en ese año
    $asignados = [];
    if ($sala_seleccionada > 0) {
        $sql_asignados = "SELECT DNI_Personal
                            FROM personal_asignado_sala
                           WHERE NumeroSala = ? AND Anio_Distribucion = ?";
        $stmt_asig = $conexion->prepare($sql_asignados);
        $stmt_asig->bind_param('ii', $sala_seleccionada, $anio);
        $stmt_asig->execute();
        $res_asignados = $stmt_asig->get_result();
        while ($fila = $res_asignados->fetch_assoc()) {
            $asignados[] = $fila['DNI_Personal'];
        }
    }

    // Empleados del piso cuya subespecialidad coincide con la sala
    $empleados = [];
    if ($sala_seleccionada > 0) {
        $sql_empleados = "SELECT e.DNI_Personal, e.ApellidosNombres
                            FROM empleado e
                            JOIN personal_por_piso p ON e.DNI_Personal = p.DNI_Personal
                            JOIN subespecialidad sub ON e.ID_Subespecialidad = sub.ID_Subespecialidad
                            JOIN sala s ON s.NumeroSala = ?
                           WHERE p.Nro_Piso = ?
                             AND sub.NombreSubespecialidad = s.NombreSala";
        $stmt_emp = $conexion->prepare($sql_empleados);
        $stmt_emp->bind_param('ii', $sala_seleccionada, $piso);
        $stmt_emp->execute();
        $res_empleados = $stmt_emp->get_result();
        while ($fila = $res_empleados->fetch_assoc()) {
            $empleados[] = $fila;
        }
    }

    require __DIR__ . '/../views/encargado/seleccionar_sala.php';
}


    public function guardarDistribucion() {
        $this->requireEncargado();
        global $conexion;

        if (!isset($_POST['sala']) || empty($_POST['sala'])) {
            $error = "No se seleccionó ninguna sala.";
            header('Location: index.php?controller=encargado&action=seleccionarSala');
            exit();
        }

        $sala      = (int)$_POST['sala'];
        $empleados = isset($_POST['empleados']) ? $_POST['empleados'] : [];
        $anio      = date('Y');

        $check_sql = "SELECT 1 FROM distribucion_anual_salas
                       WHERE NumeroSala = ? AND Anio_Distribucion = ?";
        $stmt_chk = $conexion->prepare($check_sql);
        $stmt_chk->bind_param('ii', $sala, $anio);
        $stmt_chk->execute();
        $res = $stmt_chk->get_result();

        if ($res->num_rows == 0) {
            $error = "La sala $sala para el año $anio no existe en distribucion_anual_salas.";
            header('Location: index.php?controller=encargado&action=seleccionarSala');
            exit();
        }

        $sql_delete = "DELETE FROM personal_asignado_sala
                        WHERE NumeroSala = ? AND Anio_Distribucion = ?";
        $stmt_del = $conexion->prepare($sql_delete);
        $stmt_del->bind_param('ii', $sala, $anio);
        $stmt_del->execute();

        $sql_insert = "INSERT INTO personal_asignado_sala
                          (Anio_Distribucion, NumeroSala, DNI_Personal, Rol)
                       VALUES (?, ?, ?, 'Enfermero')";
        $stmt_ins = $conexion->prepare($sql_insert);

        foreach ($empleados as $dniemp) {
            $stmt_ins->bind_param('iis', $anio, $sala, $dniemp);
            $stmt_ins->execute();
        }

        header('Location: index.php?controller=encargado&action=panel');
        exit();
    }

public function verProgramacionTurnos() {
    $this->requireEncargado();
    global $conexion;

    $dni  = $_SESSION['dni'];

    // Piso del encargado
    $sql_piso = "SELECT Nro_Piso FROM personal_enfermeria WHERE DNI_Encargado = ?";
    $stmt_piso = $conexion->prepare($sql_piso);
    $stmt_piso->bind_param('s', $dni);
    $stmt_piso->execute();
    $piso = $stmt_piso->get_result()->fetch_assoc()['Nro_Piso'];

    // Año y mes seleccionados
    $anio = isset($_POST['Anio']) ? intval($_POST['Anio']) : intval(date('Y'));
    $mes  = isset($_POST['Mes'])  ? intval($_POST['Mes'])  : intval(date('n'));
    $sala_seleccionada = isset($_POST['NumeroSala']) ? intval($_POST['NumeroSala']) : 0;

    // Salas del piso
    $sql_salas = "SELECT s.NumeroSala, s.NombreSala
                    FROM sala s
                    JOIN distribucion_anual_salas d ON s.NumeroSala = d.NumeroSala
                   WHERE d.Nro_Piso = ? AND d.Anio_Distribucion = ?
                ORDER BY s.NumeroSala";
    $stmt_salas = $conexion->prepare($sql_salas);
    $stmt_salas->bind_param('ii', $piso, $anio);
    $stmt_salas->execute();
    $res_salas = $stmt_salas->get_result();
    $salas = [];
    while ($row = $res_salas->fetch_assoc()) {
        $salas[] = $row;
    }

    $personal_sala = [];
    $asignaciones_por_emp = [];
    $dias_mes = cal_days_in_month(CAL_GREGORIAN, $mes, $anio);

    if ($sala_seleccionada > 0) {
        // Personal asignado a la sala (para nombres y filas)
        $sql_personal = "SELECT e.DNI_Personal, e.ApellidosNombres
                           FROM empleado e
                           JOIN personal_asignado_sala pas ON e.DNI_Personal = pas.DNI_Personal
                          WHERE pas.NumeroSala = ? AND pas.Anio_Distribucion = ?";
        $stmt_personal = $conexion->prepare($sql_personal);
        $stmt_personal->bind_param('ii', $sala_seleccionada, $anio);
        $stmt_personal->execute();
        $res_personal = $stmt_personal->get_result();
        while ($row = $res_personal->fetch_assoc()) {
            $personal_sala[] = $row;
            $asignaciones_por_emp[$row['DNI_Personal']] = [];
        }

        if (!empty($personal_sala)) {
            // Turnos reales desde la BD para ese mes/sala
            $sql_turnos = "SELECT te.Dia, te.DNI, te.TipoTurno
                             FROM turno_empleado te
                             JOIN personal_asignado_sala pas
                               ON te.DNI = pas.DNI_Personal
                              AND pas.Anio_Distribucion = ?
                              AND pas.NumeroSala = ?
                            WHERE te.Mes = ?
                            ORDER BY te.Dia, te.DNI";
            $stmt_turnos = $conexion->prepare($sql_turnos);
            $stmt_turnos->bind_param('iii', $anio, $sala_seleccionada, $mes);
            $stmt_turnos->execute();
            $res_turnos = $stmt_turnos->get_result();

            while ($row = $res_turnos->fetch_assoc()) {
                $dia  = (int)$row['Dia'];
                $dniE = $row['DNI'];
                $tipo = $row['TipoTurno']; // M/T/N

                if (!isset($asignaciones_por_emp[$dniE])) {
                    $asignaciones_por_emp[$dniE] = [];
                }
                $asignaciones_por_emp[$dniE][$dia] = $tipo;
            }
        }
    }

    require __DIR__ . '/../views/encargado/programacion_turnos.php';
}


public function programacionMensual() {
    $this->requireEncargado();
    global $conexion;

    $dni = $_SESSION['dni'];

    // Piso del encargado
    $sql_piso = "SELECT Nro_Piso FROM personal_enfermeria WHERE DNI_Encargado = ?";
    $stmt_piso = $conexion->prepare($sql_piso);
    $stmt_piso->bind_param('s', $dni);
    $stmt_piso->execute();
    $piso = $stmt_piso->get_result()->fetch_assoc()['Nro_Piso'];

    // Año y mes seleccionados
    $anio = isset($_POST['Anio']) ? intval($_POST['Anio']) : intval(date('Y'));
    $mes  = isset($_POST['Mes'])  ? intval($_POST['Mes'])  : intval(date('n'));

    // Salas del piso
    $sql_salas = "SELECT s.NumeroSala, s.NombreSala, s.Capacidad
                    FROM sala s
                    JOIN distribucion_anual_salas d ON s.NumeroSala = d.NumeroSala
                   WHERE d.Nro_Piso = ? AND d.Anio_Distribucion = ?";
    $stmt_salas = $conexion->prepare($sql_salas);
    $stmt_salas->bind_param('ii', $piso, $anio);
    $stmt_salas->execute();
    $res_salas = $stmt_salas->get_result();
    $salas = [];
    while ($row = $res_salas->fetch_assoc()) {
        $salas[] = $row;
    }

    $sala_seleccionada = isset($_POST['NumeroSala']) ? intval($_POST['NumeroSala']) : 0;

    // Personal asignado a la sala + ID_Condicion (para CAS / nombrado)
    $personal_sala = [];
    if ($sala_seleccionada > 0) {
        $sql_personal = "SELECT e.DNI_Personal, e.ApellidosNombres, e.ID_Condicion
                            FROM empleado e
                            JOIN personal_asignado_sala pas ON e.DNI_Personal = pas.DNI_Personal
                            WHERE pas.NumeroSala = ? AND pas.Anio_Distribucion = ?";
        $stmt_personal = $conexion->prepare($sql_personal);
        $stmt_personal->bind_param('ii', $sala_seleccionada, $anio);
        $stmt_personal->execute();
        $res_personal = $stmt_personal->get_result();
        while ($row = $res_personal->fetch_assoc()) {
            $personal_sala[] = $row;
        }
    }

    // Capacidad de la sala seleccionada
    $capacidad_sala = null;
    foreach ($salas as $sala) {
        if ($sala['NumeroSala'] == $sala_seleccionada) {
            $capacidad_sala = $sala['Capacidad'];
        }
    }

    $mensaje_error = '';
    $mensaje_ok    = '';

    $asignaciones_por_dia = [];    // [dia][tipoTurno][] = DNI
    $asignaciones_por_emp = [];    // [DNI][dia] = 'M'/'T'/'N' (para pintar empleados x días)

    // --- Consulta PERMISOS/DESCANSOS para el mes actual ---
    $sql_permisos = "SELECT t.DNI, e.ApellidosNombres, t.Dia, t.TipoTurno
        FROM turno_empleado t
        JOIN empleado e ON t.DNI = e.DNI_Personal
        WHERE t.Mes = ? AND t.EsPermisoEspecial = 1";
    $stmt_perm = $conexion->prepare($sql_permisos);
    $stmt_perm->bind_param('i', $mes);
    $stmt_perm->execute();
    $permisos = $stmt_perm->get_result()->fetch_all(MYSQLI_ASSOC);

    // --- Consulta CUMPLEAÑOS para el mes actual ---
    $sql_cumple = "SELECT e.DNI_Personal, e.ApellidosNombres, DAY(e.FechaNacimiento) AS Dia
                   FROM empleado e WHERE MONTH(e.FechaNacimiento) = ?";
    $stmt_cumple = $conexion->prepare($sql_cumple);
    $stmt_cumple->bind_param('i', $mes);
    $stmt_cumple->execute();
    $cumples = $stmt_cumple->get_result()->fetch_all(MYSQLI_ASSOC);

    // Mapea para formato fácil
    $mapa_cumple = [];
    foreach($cumples as $c) $mapa_cumple[$c['DNI_Personal']][$c['Dia']] = true;
    $mapa_permiso = [];
    foreach($permisos as $p) $mapa_permiso[$p['DNI']][$p['Dia']] = $p['TipoTurno'];

    // BORRAR PROGRAMACIÓN MENSUAL
    if (isset($_POST['borrar_mes']) && !empty($personal_sala)) {
        foreach ($personal_sala as $emp) {
            $dni = $emp['DNI_Personal'];
            $stmt = $conexion->prepare("DELETE FROM turno_empleado WHERE Mes = ? AND DNI = ?");
            $stmt->bind_param('is', $mes, $dni);
            $stmt->execute();
        }
        $mensaje_ok = "Programación del mes borrada.";
    }

    // PASO 1: generar al azar (solo vista previa, NO guarda)
    if (isset($_POST['asignar_azar']) && !empty($personal_sala) && $sala_seleccionada > 0) {
        $dias_mes = cal_days_in_month(CAL_GREGORIAN, $mes, $anio);
        $turnos_por_empleado = [];
        foreach ($personal_sala as $emp) {
            $turnos_por_empleado[$emp['DNI_Personal']] = 0;
            $asignaciones_por_emp[$emp['DNI_Personal']] = [];
        }
        $minimos_ok = true;
        for ($dia = 1; $dia <= $dias_mes; $dia++) {
            $asignaciones_por_dia[$dia] = ['M' => [], 'T' => [], 'N' => []];
            $empleados_shuffle = $personal_sala;
            shuffle($empleados_shuffle);
            $cantTurnosDia = 0;
            $tiposAsignados = [];
            $usadosEnDia = [];
            foreach (['M','T'] as $tipoTurno) {
                foreach ($empleados_shuffle as $emp) {
                    $dniEmp = $emp['DNI_Personal'];
                    if (in_array($dniEmp, $usadosEnDia, true)) continue;
                    if ($turnos_por_empleado[$dniEmp] < 25 &&
                        ($capacidad_sala === null || $cantTurnosDia < $capacidad_sala)) {
                        $asignaciones_por_dia[$dia][$tipoTurno][] = $dniEmp;
                        $turnos_por_empleado[$dniEmp]++;
                        $tiposAsignados[$tipoTurno] = true;
                        $cantTurnosDia++;
                        $usadosEnDia[] = $dniEmp;
                        $asignaciones_por_emp[$dniEmp][$dia] = $tipoTurno;
                        break;
                    }
                }
            }
            // Ahora N, solo no CAS
            foreach ($empleados_shuffle as $emp) {
                $dniEmp = $emp['DNI_Personal'];
                if (in_array($dniEmp, $usadosEnDia, true)) continue;
                if ($emp['ID_Condicion'] == 1) continue;
                if ($turnos_por_empleado[$dniEmp] < 25 &&
                    ($capacidad_sala === null || $cantTurnosDia < $capacidad_sala)) {
                    $asignaciones_por_dia[$dia]['N'][] = $dniEmp;
                    $turnos_por_empleado[$dniEmp]++;
                    $cantTurnosDia++;
                    $usadosEnDia[] = $dniEmp;
                    $asignaciones_por_emp[$dniEmp][$dia] = 'N';
                    break;
                }
            }
            if (empty($tiposAsignados['M']) || empty($tiposAsignados['T'])) {
                $minimos_ok = false;
            }
        }
        if (!$minimos_ok) {
            $mensaje_error = "No se pudo garantizar al menos un turno M y T por día con el personal disponible.";
            $asignaciones_por_dia = [];
            $asignaciones_por_emp = [];
        } else {
            $_SESSION['asignaciones_previas'] = [
                'anio'         => $anio,
                'mes'          => $mes,
                'sala'         => $sala_seleccionada,
                'asignaciones_dia' => $asignaciones_por_dia,
                'asignaciones_emp' => $asignaciones_por_emp
            ];
            $mensaje_ok = "Asignación generada. Revise el calendario y confirme para guardar.";
        }
    }

    // PASO 2: confirmar y guardar usando SP
    if (isset($_POST['confirmar']) && !empty($_SESSION['asignaciones_previas']) && $sala_seleccionada > 0) {
        $data = $_SESSION['asignaciones_previas'];
        if ($data['anio'] == $anio && $data['mes'] == $mes && $data['sala'] == $sala_seleccionada) {
            $asignaciones_por_dia = $data['asignaciones_dia'];
            $asignaciones_por_emp = $data['asignaciones_emp'];
            $sql_call = "CALL sp_insert_turno(?, ?, ?, ?, ?, ?)";
            $stmt_ins = $conexion->prepare($sql_call);
            try {
                foreach ($asignaciones_por_dia as $dia => $turnosDia) {
                    foreach ($turnosDia as $tipoTurno => $listaDnis) {
                        foreach ($listaDnis as $dniEmp) {
                            $esDescanso        = 0;
                            $esPermisoEspecial = 0;
                            $stmt_ins->bind_param(
                                'iissii',
                                $mes,
                                $dia,
                                $dniEmp,
                                $tipoTurno,
                                $esDescanso,
                                $esPermisoEspecial
                            );
                            $stmt_ins->execute();
                        }
                    }
                }
                unset($_SESSION['asignaciones_previas']);
                $mensaje_ok = "Asignaciones guardadas correctamente.";
            } catch (mysqli_sql_exception $e) {
                $mensaje_error = $e->getMessage();
            }
        } else {
            $mensaje_error = "La propuesta de asignación no coincide con el año, mes o sala seleccionados.";
        }
    }

    $dias_mes = cal_days_in_month(CAL_GREGORIAN, $mes, $anio);

    require __DIR__ . '/../views/encargado/programacion_mensual.php';
}


public function imprimirProgramacionPDF() {
    $this->requireEncargado();
    global $conexion;

    $dni = $_SESSION['dni'];

    // Piso del encargado
    $sql_piso = "SELECT Nro_Piso FROM personal_enfermeria WHERE DNI_Encargado = ?";
    $stmt_piso = $conexion->prepare($sql_piso);
    $stmt_piso->bind_param('s', $dni);
    $stmt_piso->execute();
    $piso = $stmt_piso->get_result()->fetch_assoc()['Nro_Piso'];

    $anio = isset($_GET['Anio']) ? intval($_GET['Anio']) : intval(date('Y'));
    $mes  = isset($_GET['Mes']) ? intval($_GET['Mes']) : intval(date('n'));
    $sala_seleccionada = isset($_GET['NumeroSala']) ? intval($_GET['NumeroSala']) : 0;

    if ($sala_seleccionada == 0) {
        die("Debe seleccionar una sala.");
    }

    // Nombre de la sala
    $sql_sala = "SELECT NombreSala FROM sala WHERE NumeroSala = ?";
    $stmt_sala = $conexion->prepare($sql_sala);
    $stmt_sala->bind_param('i', $sala_seleccionada);
    $stmt_sala->execute();
    $nombre_sala = $stmt_sala->get_result()->fetch_assoc()['NombreSala'];

    // Personal asignado
    $sql_personal = "SELECT e.DNI_Personal, e.ApellidosNombres
                       FROM empleado e
                       JOIN personal_asignado_sala pas ON e.DNI_Personal = pas.DNI_Personal
                      WHERE pas.NumeroSala = ? AND pas.Anio_Distribucion = ?
                   ORDER BY e.ApellidosNombres";
    $stmt_personal = $conexion->prepare($sql_personal);
    $stmt_personal->bind_param('ii', $sala_seleccionada, $anio);
    $stmt_personal->execute();
    $res_personal = $stmt_personal->get_result();
    $personal_sala = [];
    while ($row = $res_personal->fetch_assoc()) {
        $personal_sala[] = $row;
    }

    // Turnos guardados del mes
    $asignaciones_por_emp = [];
    foreach ($personal_sala as $emp) {
        $asignaciones_por_emp[$emp['DNI_Personal']] = [];
    }
    $sql_turnos = "SELECT Dia, DNI, TipoTurno FROM turno_empleado WHERE Mes = ?";
    $stmt_turnos = $conexion->prepare($sql_turnos);
    $stmt_turnos->bind_param('i', $mes);
    $stmt_turnos->execute();
    $res_turnos = $stmt_turnos->get_result();
    while ($row = $res_turnos->fetch_assoc()) {
        $dia  = (int)$row['Dia'];
        $dniE = $row['DNI'];
        $tipo = $row['TipoTurno'];
        if (in_array($tipo, ['M', 'T', 'N'])) {
            $asignaciones_por_emp[$dniE][$dia] = $tipo;
        }
    }

    $dias_mes = cal_days_in_month(CAL_GREGORIAN, $mes, $anio);
    $meses_nombre = ['','Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
    $dias_semana = ['D','L','M','M','J','V','S'];

    // Generar HTML para PDF
    $html = '
    <style>
        body { font-family: Arial, sans-serif; font-size: 9px; }
        h2, h3 { text-align: center; margin: 5px 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 3px; text-align: center; font-size: 8px; }
        th { background-color: #ddd; font-weight: bold; }
        .nombres { text-align: left; font-size: 7px; }
    </style>
    <h2>DEPARTAMENTO ENFERMERIA - SERVICIO '.strtoupper($nombre_sala).'</h2>
    <h3>PROGRAMACION DE TURNOS, GUARDIAS Y HORARIOS DEL SERVICIO ASISTENCIAL</h3>
    <h3>CORRESPONDIENTE AL MES DE '.strtoupper($meses_nombre[$mes]).' DEL '.$anio.'</h3>
    <table>
        <thead>
            <tr>
                <th rowspan="2" style="width:20%;">APELLIDOS Y NOMBRES</th>';
    for ($d = 1; $d <= $dias_mes; $d++) {
        $html .= '<th>'.$d.'</th>';
    }
    $html .= '<th rowspan="2">Total</th></tr><tr>';
    for ($d = 1; $d <= $dias_mes; $d++) {
        $fecha = mktime(0, 0, 0, $mes, $d, $anio);
        $dia_semana = $dias_semana[date('w', $fecha)];
        $html .= '<th>'.$dia_semana.'</th>';
    }
    $html .= '</tr></thead><tbody>';

    foreach ($personal_sala as $pers) {
        $dniEmp = $pers['DNI_Personal'];
        $total = 0;
        $html .= '<tr><td class="nombres">'.htmlspecialchars($pers['ApellidosNombres']).'</td>';
        for ($d = 1; $d <= $dias_mes; $d++) {
            $val = isset($asignaciones_por_emp[$dniEmp][$d]) ? $asignaciones_por_emp[$dniEmp][$d] : '';
            if (in_array($val, ['M','T','N'])) $total++;
            $html .= '<td>'.$val.'</td>';
        }
        $html .= '<td><strong>'.$total.'</strong></td></tr>';
    }
    $html .= '</tbody></table>';

    require_once __DIR__ . '/../lib/mpdf/autoload.php';
    $mpdf = new \Mpdf\Mpdf(['orientation' => 'L', 'format' => 'A4']);
    $mpdf->WriteHTML($html);
    $mpdf->Output('Programacion_'.$meses_nombre[$mes].'_'.$anio.'.pdf', 'I');
    exit();
}


}