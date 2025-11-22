<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Panel de Administrador</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
<div class="container">
<h2>Panel de Administrador</h2>

<ul>
    <li><a href="index.php?controller=admin&action=crearPiso">Agregar piso</a></li>
    <li><a href="index.php?controller=admin&action=listarPisos">Ver/editar pisos</a></li>
    <li><a href="index.php?controller=admin&action=crearSala">Agregar sala</a></li>
    <li><a href="index.php?controller=admin&action=listarSalas">Ver/editar salas</a></li>
    <li><a href="index.php?controller=admin&action=crearSubespecialidad">Agregar subespecialidad</a></li>
    <li><a href="index.php?controller=admin&action=listarSubespecialidades">Ver/editar subespecialidades</a></li>
    <li><a href="index.php?controller=admin&action=crearCondicionLaboral">Agregar condición laboral</a></li>
    <li><a href="index.php?controller=admin&action=listarCondicionesLaborales">Ver/editar condiciones laborales</a></li>
    <li><a href="index.php?controller=admin&action=crearEmpleado">Agregar empleado</a></li>
    <li><a href="index.php?controller=admin&action=listarEmpleados">Ver/editar empleados</a></li>
    <li><a href="index.php?controller=admin&action=crearDistribucion">Agregar distribución anual</a></li>
    <li><a href="index.php?controller=admin&action=listarDistribuciones">Ver distribuciones anuales</a></li>
    <li><a href="index.php?controller=admin&action=listarAsignacionesSala">Ver personal asignado a sala</a></li>
    <li><a href="index.php?controller=admin&action=listarPersonalPorPiso">Ver personal por piso</a></li>
    <li><a href="index.php?controller=admin&action=listarEncargadosPorPiso">Ver encargados por piso</a></li>
    <li><a href="index.php?controller=admin&action=listarResumenTurnosEmpleado">Resumen de turnos por empleado</a></li>
</ul>

<a href="index.php?controller=auth&action=logout">Cerrar sesión</a>
</div>
</body>
</html>
