<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Resumen de turnos por empleado</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
<div class="container">
    <h2>Resumen de turnos por empleado</h2>
    <form method="get" action="index.php">
        <input type="hidden" name="controller" value="admin">
        <input type="hidden" name="action" value="listarResumenTurnosEmpleado">
        <label>Piso:</label>
        <select name="Nro_Piso">
            <option value="">Todos</option>
            <?php foreach ($pisos as $p): ?>
                <option value="<?php echo (int)$p['Nro_Piso']; ?>"
                    <?php if (!empty($_GET['Nro_Piso']) && $_GET['Nro_Piso'] == $p['Nro_Piso']) echo 'selected'; ?>>
                    <?php echo htmlspecialchars($p['NombrePiso']); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <label>Mes:</label>
        <select name="Mes">
            <option value="">Todos</option>
            <?php for ($m = 1; $m <= 12; $m++): ?>
                <option value="<?php echo $m; ?>" <?php if (!empty($_GET['Mes']) && $_GET['Mes'] == $m) echo 'selected'; ?>>
                    <?php echo $m; ?>
                </option>
            <?php endfor; ?>
        </select>

        <?php echo htmlspecialchars($_GET['Anio'] ?? date('Y')); ?>"> -->
        <button type="submit">Filtrar</button>
    </form>
    <table>
        <tr>
            <th>Piso</th>
            <th>Empleado</th>
            <th>DNI</th>
            <th>Mes</th>
            <th>Total turnos</th>
            <th>Total horas</th>
        </tr>
        <?php foreach ($resumen as $r): ?>
            <tr>
                <td><?php echo htmlspecialchars($r['NombrePiso']); ?></td>
                <td><?php echo htmlspecialchars($r['ApellidosNombres']); ?></td>
                <td><?php echo htmlspecialchars($r['DNI_Personal']); ?></td>
                <td><?php echo (int)$r['Mes']; ?></td>
                <td><?php echo (int)$r['TotalTurnosEmpleado']; ?></td>
                <td><?php echo (int)$r['TotalHorasEmpleado']; ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
    <br><a href="index.php?controller=admin&action=panel">Volver al panel admin</a>
</div>
</body>
</html>
