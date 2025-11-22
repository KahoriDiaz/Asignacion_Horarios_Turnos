<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Distribución Anual por Sala</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <h2>Distribución Anual por Salas (Piso <?php echo (int)$piso; ?>)</h2>

    <!-- Selección de año y sala -->
    <form method="get" action="index.php">
        <input type="hidden" name="controller" value="encargado">
        <input type="hidden" name="action" value="seleccionarSala">

        <label for="Anio">Año:</label>
        <select name="Anio" id="Anio" onchange="this.form.submit()">
            <?php
            $anio_actual = date('Y');
            $anio_mostrar = isset($anio) ? (int)$anio : (int)$anio_actual;
            for ($a = $anio_actual - 1; $a <= $anio_actual + 1; $a++): ?>
                <option value="<?php echo $a; ?>" <?php if ($a == $anio_mostrar) echo 'selected'; ?>>
                    <?php echo $a; ?>
                </option>
            <?php endfor; ?>
        </select>

        <label for="sala">Seleccione una sala:</label>
        <select name="sala" id="sala" onchange="this.form.submit()">
            <option value="">--Seleccione--</option>
            <?php while ($fila = $res_salas->fetch_assoc()): ?>
                <option value="<?php echo $fila['NumeroSala']; ?>"
                    <?php if ($fila['NumeroSala'] == $sala_seleccionada) echo "selected"; ?>>
                    <?php echo htmlspecialchars($fila['NombreSala']); ?>
                </option>
            <?php endwhile; ?>
        </select>
    </form>

    <?php if ($sala_seleccionada > 0): ?>
        <h3>Asignar empleados a la sala (Año <?php echo (int)$anio_mostrar; ?>)</h3>
        <?php if (count($empleados) > 0): ?>
            <form method="post" action="index.php?controller=encargado&action=guardarDistribucion">
                <input type="hidden" name="sala" value="<?php echo (int)$sala_seleccionada; ?>">
                <input type="hidden" name="Anio" value="<?php echo (int)$anio_mostrar; ?>">
                <ul>
                    <?php foreach ($empleados as $emp): ?>
                        <li>
                            <label>
                                <input type="checkbox" name="empleados[]" value="<?php echo $emp['DNI_Personal']; ?>"
                                    <?php if (in_array($emp['DNI_Personal'], $asignados)) echo "checked"; ?>>
                                <?php echo htmlspecialchars($emp['ApellidosNombres']); ?>
                            </label>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <button type="submit">Guardar Asignaciones</button>
            </form>
        <?php else: ?>
            <p>No hay empleados disponibles para esta sala (verifica subespecialidad y piso).</p>
        <?php endif; ?>
    <?php else: ?>
        <p>Selecciona un año y una sala para asignar empleados.</p>
    <?php endif; ?>

    <br><a href="index.php?controller=encargado&action=panel">Volver al panel del encargado</a>
</body>
</html>
