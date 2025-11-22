<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Distribución Anual de Salas</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
<div class="container">
    <h2>Distribución Anual de Salas</h2>
    <a href="index.php?controller=admin&action=crearDistribucion">Agregar nueva distribución</a>
    <table>
        <tr>
            <th>Año</th>
            <th>Piso</th>
            <th>Sala</th>
            <th>Mínimos x Turno</th>
            <th>Acciones</th>
        </tr>
        <?php foreach ($distribuciones as $d): ?>
            <tr>
                <td><?php echo (int)$d['Anio_Distribucion']; ?></td>
                <td><?php echo htmlspecialchars($d['NombrePiso']); ?></td>
                <td><?php echo htmlspecialchars($d['NombreSala']); ?></td>
                <td><?php echo (int)$d['MinimosXTurno']; ?></td>
                <td>
                    <a href="index.php?controller=admin&action=borrarDistribucion&NumeroSala=<?php echo (int)$d['NumeroSala']; ?>&Anio_Distribucion=<?php echo (int)$d['Anio_Distribucion']; ?>" onclick="return confirm('¿Borrar distribución?');">Borrar</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    <br><a href="index.php?controller=admin&action=panel">Volver al panel admin</a>
</div>
</body>
</html>
