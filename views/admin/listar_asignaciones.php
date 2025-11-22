<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Personal asignado a sala</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
<div class="container">
    <h2>Personal asignado a sala</h2>
    <table>
        <tr>
            <th>Año</th>
            <th>Sala</th>
            <th>Empleado</th>
            <th>Rol</th>
            <th>Acciones</th>
        </tr>
        <?php foreach ($asignaciones as $a): ?>
            <tr>
                <td><?php echo (int)$a['Anio_Distribucion']; ?></td>
                <td><?php echo htmlspecialchars($a['NombreSala']); ?></td>
                <td><?php echo htmlspecialchars($a['ApellidosNombres']); ?> (<?php echo htmlspecialchars($a['DNI_Personal']); ?>)</td>
                <td><?php echo htmlspecialchars($a['Rol']); ?></td>
                <td>
                    <a href="index.php?controller=admin&action=borrarAsignacionSala
                       &Anio_Distribucion=<?php echo (int)$a['Anio_Distribucion']; ?>
                       &NumeroSala=<?php echo (int)$a['NumeroSala']; ?>
                       &DNI_Personal=<?php echo htmlspecialchars($a['DNI_Personal']); ?>"
                    onclick="return confirm('¿Eliminar asignación?');">Eliminar</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    <br><a href="index.php?controller=admin&action=panel">Volver al panel admin</a>
</div>
</body>
</html>
