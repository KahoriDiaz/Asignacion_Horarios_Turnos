<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Empleados</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
<div class="container">
    <h2>Empleados</h2>
    <a href="index.php?controller=admin&action=crearEmpleado">Agregar empleado</a>
    <table>
        <tr>
            <th>DNI</th>
            <th>Nombre</th>
            <th>Celular</th>
            <th>F. Nac.</th>
            <th>Estado</th>
            <th>Encargado</th>
            <th>Piso</th>
            <th>Subespecialidad</th>
            <th>Condición</th>
            <th>Acciones</th>
        </tr>
        <?php foreach ($empleados as $e): ?>
            <tr>
                <td><?php echo htmlspecialchars($e['DNI_Personal']); ?></td>
                <td><?php echo htmlspecialchars($e['ApellidosNombres']); ?></td>
                <td><?php echo htmlspecialchars($e['Celular']); ?></td>
                <td><?php echo htmlspecialchars($e['FechaNacimiento']); ?></td>
                <td><?php echo htmlspecialchars($e['Estado']); ?></td>
                <td><?php echo $e['Encargado'] ? 'Sí' : 'No'; ?></td>
                <td><?php echo htmlspecialchars($e['NombrePiso']); ?></td>
                <td><?php echo htmlspecialchars($e['NombreSubespecialidad']); ?></td>
                <td><?php echo htmlspecialchars($e['NombreCondicion']); ?></td>
                <td>
                    <a href="index.php?controller=admin&action=editarEmpleado&DNI_Personal=<?php echo htmlspecialchars($e['DNI_Personal']); ?>">Editar</a> |
                    <a href="index.php?controller=admin&action=borrarEmpleado&DNI_Personal=<?php echo htmlspecialchars($e['DNI_Personal']); ?>" onclick="return confirm('¿Borrar?');">Borrar</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    <br><a href="index.php?controller=admin&action=panel">Volver al panel admin</a>
</div>
</body>
</html>
