<?php
// controllers/AdminController.php
session_start();
require_once __DIR__ . '/../config/conexion.php';

class AdminController {

    private function requireAdmin() {
        if (!isset($_SESSION['tipo']) || $_SESSION['tipo'] !== 'admin') {
            header('Location: index.php');
            exit();
        }
    }

    public function panel() {
        $this->requireAdmin();
        require __DIR__ . '/../views/admin/panel.php';
    }

    // ====== PISOS ======
    public function listarPisos() {
        $this->requireAdmin();
        global $conexion;
        $res = $conexion->query("SELECT Nro_Piso, NombrePiso FROM piso ORDER BY Nro_Piso");
        $pisos = [];
        while ($row = $res->fetch_assoc()) {
            $pisos[] = $row;
        }
        require __DIR__ . '/../views/admin/listar_pisos.php';
    }

    public function crearPiso() {
        $this->requireAdmin();
        global $conexion;
        $mensaje = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nro   = (int)($_POST['Nro_Piso'] ?? 0);
            $nombre = trim($_POST['NombrePiso'] ?? '');
            if ($nro > 0 && $nombre !== '') {
                $sql = "INSERT INTO piso (Nro_Piso, NombrePiso) VALUES (?, ?)";
                $stmt = $conexion->prepare($sql);
                $stmt->bind_param('is', $nro, $nombre);
                if ($stmt->execute()) {
                    $mensaje = "Piso creado correctamente.";
                } else {
                    $mensaje = "Error al crear piso: " . $conexion->error;
                }
            } else {
                $mensaje = "Complete todos los datos.";
            }
        }
        require __DIR__ . '/../views/admin/crear_piso.php';
    }

    public function editarPiso() {
        $this->requireAdmin();
        global $conexion;
        $nro = isset($_GET['Nro_Piso']) ? (int)$_GET['Nro_Piso'] : 0;
        if ($nro <= 0) {
            header('Location: index.php?controller=admin&action=listarPisos');
            exit();
        }
        $mensaje = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = trim($_POST['NombrePiso'] ?? '');
            if ($nombre !== '') {
                $sql = "UPDATE piso SET NombrePiso = ? WHERE Nro_Piso = ?";
                $stmt = $conexion->prepare($sql);
                $stmt->bind_param('si', $nombre, $nro);
                if ($stmt->execute()) {
                    $mensaje = "Piso actualizado correctamente.";
                } else {
                    $mensaje = "Error al actualizar piso: " . $conexion->error;
                }
            } else {
                $mensaje = "El nombre no puede estar vacío.";
            }
        }
        $stmt = $conexion->prepare("SELECT Nro_Piso, NombrePiso FROM piso WHERE Nro_Piso = ?");
        $stmt->bind_param('i', $nro);
        $stmt->execute();
        $piso = $stmt->get_result()->fetch_assoc();
        require __DIR__ . '/../views/admin/editar_piso.php';
    }

    public function borrarPiso() {
        $this->requireAdmin();
        global $conexion;
        $nro = isset($_GET['Nro_Piso']) ? (int)$_GET['Nro_Piso'] : 0;
        if ($nro <= 0) {
            header('Location: index.php?controller=admin&action=listarPisos');
            exit();
        }
        $stmt = $conexion->prepare("DELETE FROM piso WHERE Nro_Piso = ?");
        $stmt->bind_param('i', $nro);
        $stmt->execute();
        header('Location: index.php?controller=admin&action=listarPisos');
        exit();
    }

    // ====== SALAS ======
    public function listarSalas() {
        $this->requireAdmin();
        global $conexion;
        $res = $conexion->query("SELECT NumeroSala, NombreSala, Capacidad FROM sala ORDER BY NumeroSala");
        $salas = [];
        while ($row = $res->fetch_assoc()) {
            $salas[] = $row;
        }
        require __DIR__ . '/../views/admin/listar_salas.php';
    }

    public function crearSala() {
        $this->requireAdmin();
        global $conexion;
        $mensaje = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $numero   = (int)($_POST['NumeroSala'] ?? 0);
            $nombre   = trim($_POST['NombreSala'] ?? '');
            $capacidad = (int)($_POST['Capacidad'] ?? 0);
            if ($numero > 0 && $nombre !== '') {
                $sql = "INSERT INTO sala (NumeroSala, NombreSala, Capacidad) VALUES (?, ?, ?)";
                $stmt = $conexion->prepare($sql);
                $stmt->bind_param('isi', $numero, $nombre, $capacidad);
                if ($stmt->execute()) {
                    $mensaje = "Sala creada correctamente.";
                } else {
                    $mensaje = "Error al crear sala: " . $conexion->error;
                }
            } else {
                $mensaje = "Complete todos los datos.";
            }
        }
        require __DIR__ . '/../views/admin/crear_sala.php';
    }

    public function editarSala() {
        $this->requireAdmin();
        global $conexion;
        $numero = isset($_GET['NumeroSala']) ? (int)$_GET['NumeroSala'] : 0;
        if ($numero <= 0) {
            header('Location: index.php?controller=admin&action=listarSalas');
            exit();
        }
        $mensaje = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = trim($_POST['NombreSala'] ?? '');
            $capacidad = (int)($_POST['Capacidad'] ?? 0);
            if ($nombre !== '') {
                $sql = "UPDATE sala SET NombreSala = ?, Capacidad = ? WHERE NumeroSala = ?";
                $stmt = $conexion->prepare($sql);
                $stmt->bind_param('sii', $nombre, $capacidad, $numero);
                if ($stmt->execute()) {
                    $mensaje = "Sala actualizada correctamente.";
                } else {
                    $mensaje = "Error al actualizar sala: " . $conexion->error;
                }
            } else {
                $mensaje = "El nombre no puede estar vacío.";
            }
        }
        $stmt = $conexion->prepare("SELECT NumeroSala, NombreSala, Capacidad FROM sala WHERE NumeroSala = ?");
        $stmt->bind_param('i', $numero);
        $stmt->execute();
        $sala = $stmt->get_result()->fetch_assoc();
        require __DIR__ . '/../views/admin/editar_sala.php';
    }

    public function borrarSala() {
        $this->requireAdmin();
        global $conexion;
        $numero = isset($_GET['NumeroSala']) ? (int)$_GET['NumeroSala'] : 0;
        if ($numero <= 0) {
            header('Location: index.php?controller=admin&action=listarSalas');
            exit();
        }
        $stmt = $conexion->prepare("DELETE FROM sala WHERE NumeroSala = ?");
        $stmt->bind_param('i', $numero);
        $stmt->execute();
        header('Location: index.php?controller=admin&action=listarSalas');
        exit();
    }

    // ====== SUBESPECIALIDAD (solo crear/listar/editar/borrar, no asociar manualmente a sala) ======
    public function listarSubespecialidades() {
        $this->requireAdmin();
        global $conexion;
        $res = $conexion->query("SELECT ID_Subespecialidad, NombreSubespecialidad FROM subespecialidad ORDER BY NombreSubespecialidad");
        $subs = [];
        while ($row = $res->fetch_assoc()) {
            $subs[] = $row;
        }
        require __DIR__ . '/../views/admin/listar_subespecialidades.php';
    }

    public function editarSubespecialidad() {
        $this->requireAdmin();
        global $conexion;
        $id = isset($_GET['ID_Subespecialidad']) ? (int)$_GET['ID_Subespecialidad'] : 0;
        if ($id <= 0) {
            header('Location: index.php?controller=admin&action=listarSubespecialidades');
            exit();
        }
        $mensaje = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = trim($_POST['NombreSubespecialidad'] ?? '');
            if ($nombre !== '') {
                $sql = "UPDATE subespecialidad SET NombreSubespecialidad = ? WHERE ID_Subespecialidad = ?";
                $stmt = $conexion->prepare($sql);
                $stmt->bind_param('si', $nombre, $id);
                if ($stmt->execute()) {
                    $mensaje = "Subespecialidad actualizada.";
                } else {
                    $mensaje = "Error al actualizar: " . $conexion->error;
                }
            } else {
                $mensaje = "El nombre no puede estar vacío.";
            }
        }
        $stmt = $conexion->prepare("SELECT ID_Subespecialidad, NombreSubespecialidad FROM subespecialidad WHERE ID_Subespecialidad = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $sub = $stmt->get_result()->fetch_assoc();
        require __DIR__ . '/../views/admin/editar_subespecialidad.php';
    }

    public function borrarSubespecialidad() {
        $this->requireAdmin();
        global $conexion;
        $id = isset($_GET['ID_Subespecialidad']) ? (int)$_GET['ID_Subespecialidad'] : 0;
        if ($id <= 0) {
            header('Location: index.php?controller=admin&action=listarSubespecialidades');
            exit();
        }
        $stmt = $conexion->prepare("DELETE FROM subespecialidad WHERE ID_Subespecialidad = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        header('Location: index.php?controller=admin&action=listarSubespecialidades');
        exit();
    }

    public function crearSubespecialidad() {
        $this->requireAdmin();
        global $conexion;
        $mensaje = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = trim($_POST['NombreSubespecialidad'] ?? '');
            if ($nombre !== '') {
                $stmt = $conexion->prepare("INSERT INTO subespecialidad (NombreSubespecialidad) VALUES (?)");
                $stmt->bind_param('s', $nombre);
                if ($stmt->execute()) {
                    $mensaje = "Subespecialidad creada correctamente.";
                } else {
                    $mensaje = "Error al crear subespecialidad: " . $conexion->error;
                }
            } else {
                $mensaje = "Complete el nombre.";
            }
        }
        require __DIR__ . '/../views/admin/crear_subespecialidad.php';
    }




    public function listarCondicionesLaborales() {
        $this->requireAdmin();
        global $conexion;
        $res = $conexion->query("SELECT ID_Condicion, NombreCondicion FROM condicion_laboral ORDER BY ID_Condicion");
        $condiciones = [];
        while ($row = $res->fetch_assoc()) {
            $condiciones[] = $row;
        }
        require __DIR__ . '/../views/admin/listar_condiciones.php';
    }

    public function crearCondicionLaboral() {
        $this->requireAdmin();
        global $conexion;
        $mensaje = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = trim($_POST['NombreCondicion'] ?? '');
            if ($nombre !== '') {
                $stmt = $conexion->prepare("INSERT INTO condicion_laboral (NombreCondicion) VALUES (?)");
                $stmt->bind_param('s', $nombre);
                if ($stmt->execute()) {
                    $mensaje = "Condición creada correctamente.";
                } else {
                    $mensaje = "Error al crear condición: " . $conexion->error;
                }
            } else {
                $mensaje = "Complete el nombre.";
            }
        }
        require __DIR__ . '/../views/admin/crear_condicion_laboral.php';
    }

    public function editarCondicionLaboral() {
        $this->requireAdmin();
        global $conexion;
        $id = isset($_GET['ID_Condicion']) ? (int)$_GET['ID_Condicion'] : 0;
        if ($id <= 0) {
            header('Location: index.php?controller=admin&action=listarCondicionesLaborales');
            exit();
        }
        $mensaje = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = trim($_POST['NombreCondicion'] ?? '');
            if ($nombre !== '') {
                $stmt = $conexion->prepare("UPDATE condicion_laboral SET NombreCondicion = ? WHERE ID_Condicion = ?");
                $stmt->bind_param('si', $nombre, $id);
                if ($stmt->execute()) {
                    $mensaje = "Condición actualizada.";
                } else {
                    $mensaje = "Error al actualizar condición: " . $conexion->error;
                }
            } else {
                $mensaje = "El nombre no puede estar vacío.";
            }
        }
        $stmt = $conexion->prepare("SELECT ID_Condicion, NombreCondicion FROM condicion_laboral WHERE ID_Condicion = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $condicion = $stmt->get_result()->fetch_assoc();
        require __DIR__ . '/../views/admin/editar_condicion_laboral.php';
    }

    public function borrarCondicionLaboral() {
        $this->requireAdmin();
        global $conexion;
        $id = isset($_GET['ID_Condicion']) ? (int)$_GET['ID_Condicion'] : 0;
        if ($id <= 0) {
            header('Location: index.php?controller=admin&action=listarCondicionesLaborales');
            exit();
        }
        $stmt = $conexion->prepare("DELETE FROM condicion_laboral WHERE ID_Condicion = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        header('Location: index.php?controller=admin&action=listarCondicionesLaborales');
        exit();
    }

// ====== EMPLEADO ======
    public function listarEmpleados() {
        $this->requireAdmin();
        global $conexion;
        $res = $conexion->query("SELECT e.DNI_Personal, e.ApellidosNombres, e.Celular, e.FechaNacimiento, e.Estado, e.Encargado, 
                                        p.NombrePiso, s.NombreSubespecialidad, c.NombreCondicion
                                FROM empleado e
                                LEFT JOIN piso p ON e.Nro_Piso = p.Nro_Piso
                                LEFT JOIN subespecialidad s ON e.ID_Subespecialidad = s.ID_Subespecialidad
                                LEFT JOIN condicion_laboral c ON e.ID_Condicion = c.ID_Condicion
                                ORDER BY e.ApellidosNombres");
        $empleados = [];
        while ($row = $res->fetch_assoc()) {
            $empleados[] = $row;
        }
        require __DIR__ . '/../views/admin/listar_empleados.php';
    }

    public function crearEmpleado() {
        $this->requireAdmin();
        global $conexion;
        $mensaje = '';

        // Cargar selects dinámicos
        $pisos = $conexion->query("SELECT Nro_Piso, NombrePiso FROM piso")->fetch_all(MYSQLI_ASSOC);
        $subs = $conexion->query("SELECT ID_Subespecialidad, NombreSubespecialidad FROM subespecialidad")->fetch_all(MYSQLI_ASSOC);
        $condiciones = $conexion->query("SELECT ID_Condicion, NombreCondicion FROM condicion_laboral")->fetch_all(MYSQLI_ASSOC);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $dni = trim($_POST['DNI_Personal'] ?? '');
            $nombre = trim($_POST['ApellidosNombres'] ?? '');
            $cel = trim($_POST['Celular'] ?? '');
            $nac = $_POST['FechaNacimiento'] ?? '';
            $estado = trim($_POST['Estado'] ?? '');
            $encargado = isset($_POST['Encargado']) ? 1 : 0;
            $piso = (int)($_POST['Nro_Piso'] ?? 0);
            $id_sub = (int)($_POST['ID_Subespecialidad'] ?? 0);
            $id_cond = (int)($_POST['ID_Condicion'] ?? 0);

            if ($dni && $nombre && $nac && $id_sub && $id_cond) {
                try {
                    $stmt = $conexion->prepare("CALL sp_insert_empleado(?, ?, ?, ?, ?, ?, ?, ?, ?)");
                    $stmt->bind_param('ssiisssii',
                        $dni, $nombre, $id_cond, $id_sub, $cel, $nac, $estado, $encargado, $piso
                    );
                    $stmt->execute();
                    $mensaje = "Empleado registrado correctamente.";
                } catch (mysqli_sql_exception $e) {
                    $mensaje = "Error: " . $e->getMessage();
                }
            } else {
                $mensaje = "Faltan datos obligatorios.";
            }
        }

        require __DIR__ . '/../views/admin/crear_empleado.php';
    }

    public function editarEmpleado() {
        $this->requireAdmin();
        global $conexion;
        $dni = $_GET['DNI_Personal'] ?? '';
        if (!$dni) {
            header('Location: index.php?controller=admin&action=listarEmpleados');
            exit();
        }
        $mensaje = '';

        // Cargar selects dinámicos
        $pisos = $conexion->query("SELECT Nro_Piso, NombrePiso FROM piso")->fetch_all(MYSQLI_ASSOC);
        $subs = $conexion->query("SELECT ID_Subespecialidad, NombreSubespecialidad FROM subespecialidad")->fetch_all(MYSQLI_ASSOC);
        $condiciones = $conexion->query("SELECT ID_Condicion, NombreCondicion FROM condicion_laboral")->fetch_all(MYSQLI_ASSOC);

        // Cargar datos actuales del empleado
        $stmt = $conexion->prepare("SELECT * FROM empleado WHERE DNI_Personal = ?");
        $stmt->bind_param('s', $dni);
        $stmt->execute();
        $empleado = $stmt->get_result()->fetch_assoc();

        if (!$empleado) {
            header('Location: index.php?controller=admin&action=listarEmpleados');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = trim($_POST['ApellidosNombres'] ?? '');
            $cel = trim($_POST['Celular'] ?? '');
            $nac = $_POST['FechaNacimiento'] ?? '';
            $estado = trim($_POST['Estado'] ?? '');
            $encargado = isset($_POST['Encargado']) ? 1 : 0;
            $piso = (int)($_POST['Nro_Piso'] ?? 0);
            $id_sub = (int)($_POST['ID_Subespecialidad'] ?? 0);
            $id_cond = (int)($_POST['ID_Condicion'] ?? 0);

            if ($nombre && $nac && $id_sub && $id_cond) {
                try {
                    $stmt = $conexion->prepare("CALL sp_update_empleado(?, ?, ?, ?, ?, ?, ?, ?, ?)");
                    $stmt->bind_param('ssiisssii',
                        $dni, $nombre, $id_cond, $id_sub, $cel, $nac, $estado, $encargado, $piso
                    );
                    $stmt->execute();
                    $mensaje = "Empleado actualizado correctamente.";
                } catch (mysqli_sql_exception $e) {
                    $mensaje = "Error: " . $e->getMessage();
                }
            } else {
                $mensaje = "Faltan datos obligatorios.";
            }
            // recargar datos para mostrar cambios
            $stmt = $conexion->prepare("SELECT * FROM empleado WHERE DNI_Personal = ?");
            $stmt->bind_param('s', $dni);
            $stmt->execute();
            $empleado = $stmt->get_result()->fetch_assoc();
        }

        require __DIR__ . '/../views/admin/editar_empleado.php';
    }

    public function borrarEmpleado() {
        $this->requireAdmin();
        global $conexion;
        $dni = $_GET['DNI_Personal'] ?? '';
        if (!$dni) {
            header('Location: index.php?controller=admin&action=listarEmpleados');
            exit();
        }
        try {
            $stmt = $conexion->prepare("CALL sp_delete_empleado(?)");
            $stmt->bind_param('s', $dni);
            $stmt->execute();
        } catch (mysqli_sql_exception $e) {
            // Maneja fallo de FK
        }
        header('Location: index.php?controller=admin&action=listarEmpleados');
        exit();
    }

// ====== Distribución Anual de Salas ======

    public function listarDistribuciones() {
        $this->requireAdmin();
        global $conexion;
        $sql = "SELECT d.NumeroSala, s.NombreSala, d.Anio_Distribucion, d.MinimosXTurno, d.Nro_Piso, p.NombrePiso
                FROM distribucion_anual_salas d
                JOIN sala s ON d.NumeroSala = s.NumeroSala
                JOIN piso p ON d.Nro_Piso = p.Nro_Piso
                ORDER BY d.Anio_Distribucion DESC, d.Nro_Piso, d.NumeroSala";
        $res = $conexion->query($sql);
        $distribuciones = [];
        while ($row = $res->fetch_assoc()) {
            $distribuciones[] = $row;
        }
        require __DIR__ . '/../views/admin/listar_distribuciones.php';
    }

    public function crearDistribucion() {
        $this->requireAdmin();
        global $conexion;
        $mensaje = '';

        $pisos = $conexion->query("SELECT Nro_Piso, NombrePiso FROM piso")->fetch_all(MYSQLI_ASSOC);
        $salas = $conexion->query("SELECT NumeroSala, NombreSala FROM sala")->fetch_all(MYSQLI_ASSOC);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $sala = (int)($_POST['NumeroSala'] ?? 0);
            $anio = (int)($_POST['Anio_Distribucion'] ?? date('Y'));
            $minimos = (int)($_POST['MinimosXTurno'] ?? 1);
            $piso = (int)($_POST['Nro_Piso'] ?? 0);

            if ($sala && $anio && $piso) {
                try {
                    $sql = "INSERT INTO distribucion_anual_salas (NumeroSala, Anio_Distribucion, MinimosXTurno, Nro_Piso)
                            VALUES (?, ?, ?, ?)";
                    $stmt = $conexion->prepare($sql);
                    $stmt->bind_param('iiii', $sala, $anio, $minimos, $piso);
                    $stmt->execute();
                    $mensaje = "Distribución creada.";
                } catch (mysqli_sql_exception $e) {
                    $mensaje = "Error: " . $e->getMessage();
                }
            } else {
                $mensaje = "Falta algún dato.";
            }
        }
        require __DIR__ . '/../views/admin/crear_distribucion.php';
    }

    public function borrarDistribucion() {
        $this->requireAdmin();
        global $conexion;
        $sala = isset($_GET['NumeroSala']) ? (int)$_GET['NumeroSala'] : 0;
        $anio = isset($_GET['Anio_Distribucion']) ? (int)$_GET['Anio_Distribucion'] : 0;
        if (!$sala || !$anio) {
            header('Location: index.php?controller=admin&action=listarDistribuciones');
            exit();
        }
        $stmt = $conexion->prepare("DELETE FROM distribucion_anual_salas WHERE NumeroSala = ? AND Anio_Distribucion = ?");
        $stmt->bind_param('ii', $sala, $anio);
        $stmt->execute();
        header('Location: index.php?controller=admin&action=listarDistribuciones');
        exit();
    }


    // ====== AsignacionesSala ======

    public function listarAsignacionesSala() {
        $this->requireAdmin();
        global $conexion;
        $sql = "SELECT a.Anio_Distribucion, a.NumeroSala, s.NombreSala, a.DNI_Personal, e.ApellidosNombres, a.Rol
                FROM personal_asignado_sala a
                JOIN sala s ON a.NumeroSala = s.NumeroSala
                JOIN empleado e ON a.DNI_Personal = e.DNI_Personal
                ORDER BY a.Anio_Distribucion DESC, a.NumeroSala, e.ApellidosNombres";
        $asignaciones = [];
        $res = $conexion->query($sql);
        while ($row = $res->fetch_assoc()) {
            $asignaciones[] = $row;
        }
        require __DIR__ . '/../views/admin/listar_asignaciones.php';
    }

    public function borrarAsignacionSala() {
        $this->requireAdmin();
        global $conexion;
        $anio = isset($_GET['Anio_Distribucion']) ? (int)$_GET['Anio_Distribucion'] : 0;
        $sala = isset($_GET['NumeroSala']) ? (int)$_GET['NumeroSala'] : 0;
        $dni = $_GET['DNI_Personal'] ?? '';
        if (!$anio || !$sala || !$dni) {
            header('Location: index.php?controller=admin&action=listarAsignacionesSala');
            exit();
        }
        $stmt = $conexion->prepare("DELETE FROM personal_asignado_sala WHERE Anio_Distribucion = ? AND NumeroSala = ? AND DNI_Personal = ?");
        $stmt->bind_param('iis', $anio, $sala, $dni);
        $stmt->execute();
        header('Location: index.php?controller=admin&action=listarAsignacionesSala');
        exit();
    }

    // ====== listarPersonalPorPiso ======
    public function listarPersonalPorPiso() {
        $this->requireAdmin();
        global $conexion;
        $sql = "SELECT p.Nro_Piso, piso.NombrePiso, p.DNI_Personal, e.ApellidosNombres
                FROM personal_por_piso p
                JOIN empleado e ON p.DNI_Personal = e.DNI_Personal
                JOIN piso ON p.Nro_Piso = piso.Nro_Piso
                ORDER BY p.Nro_Piso, e.ApellidosNombres";
        $personal = $conexion->query($sql)->fetch_all(MYSQLI_ASSOC);
        require __DIR__ . '/../views/admin/listar_personal_por_piso.php';
    }

    // ====== listarEncargadosPorPiso ======
    public function listarEncargadosPorPiso() {
        $this->requireAdmin();
        global $conexion;
        $sql = "SELECT pe.Nro_Piso, piso.NombrePiso, pe.DNI_Encargado, e.ApellidosNombres
                FROM personal_enfermeria pe
                JOIN piso ON pe.Nro_Piso = piso.Nro_Piso
                JOIN empleado e ON pe.DNI_Encargado = e.DNI_Personal
                ORDER BY pe.Nro_Piso";
        $encargados = $conexion->query($sql)->fetch_all(MYSQLI_ASSOC);
        require __DIR__ . '/../views/admin/listar_encargados_por_piso.php';
    }

    // ====== listarResumenTurnosEmpleado ======

    public function listarResumenTurnosEmpleado() {
        $this->requireAdmin();
        global $conexion;

        // Cargar pisos para filtro
        $pisos = $conexion->query("SELECT Nro_Piso, NombrePiso FROM piso")->fetch_all(MYSQLI_ASSOC);

        // Parámetros de filtro
        $mes = isset($_GET['Mes']) ? (int)$_GET['Mes'] : 0;
        $anio = isset($_GET['Anio']) ? (int)$_GET['Anio'] : 0;
        $piso = isset($_GET['Nro_Piso']) ? (int)$_GET['Nro_Piso'] : 0;

        // Construir consulta dinámica
        $sql = "SELECT r.Mes, r.DNI_Personal, e.ApellidosNombres, r.TotalTurnosEmpleado, r.TotalHorasEmpleado, e.Nro_Piso, p.NombrePiso
                FROM resumen_turnos_empleado r
                JOIN empleado e ON r.DNI_Personal = e.DNI_Personal
                JOIN piso p ON e.Nro_Piso = p.Nro_Piso
                WHERE 1 ";
        $params = [];
        $types = "";

        if ($mes)  { $sql .= "AND r.Mes = ? "; $types .= "i"; $params[] = $mes; }
        if ($anio) { $sql .= "AND YEAR(r.Mes) = ? "; $types .= "i"; $params[] = $anio; } // Si tienes campo año explícito, ajústalo aquí
        if ($piso) { $sql .= "AND e.Nro_Piso = ? "; $types .= "i"; $params[] = $piso; }

        $sql .= "ORDER BY p.NombrePiso ASC, e.ApellidosNombres, r.Mes DESC";

        $stmt = $conexion->prepare($sql);
        if ($params) {
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        $res = $stmt->get_result();
        $resumen = [];
        while ($r = $res->fetch_assoc()) $resumen[] = $r;

        require __DIR__ . '/../views/admin/listar_resumen_turnos.php';
    }


}
