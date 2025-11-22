<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Ver permisos y descansos</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>

<div class="container">
    <h2>Permisos y descansos</h2>
    <p><strong><?php echo htmlspecialchars($empleado['ApellidosNombres']); ?></strong></p>

    <form method="post" action="index.php?controller=empleado&action=verPermisos">
        <label for="Anio">Año:</label>
        <select name="Anio" id="Anio">
            <?php
            $anio_actual = date('Y');
            for ($a = $anio_actual - 1; $a <= $anio_actual + 1; $a++): ?>
                <option value="<?php echo $a; ?>" <?php if ($a == $anio) echo "selected"; ?>>
                    <?php echo $a; ?>
                </option>
            <?php endfor; ?>
        </select>

        <label for="Mes">Mes:</label>
        <select name="Mes" id="Mes">
            <?php for ($m = 1; $m <= 12; $m++): ?>
                <option value="<?php echo $m; ?>" <?php if ($m == $mes) echo "selected"; ?>>
                    <?php echo $m; ?>
                </option>
            <?php endfor; ?>
        </select>

        <button type="submit">Ver</button>
    </form>

    <?php if (!empty($permisos)): ?>
        <table>
            <tr>
                <th>Día</th>
                <th>Turno</th>
                <th>Tipo</th>
            </tr>
            <?php foreach ($permisos as $p): ?>
                <tr>
                    <td><?php echo (int)$p['Dia']; ?></td>
                    <td><?php echo htmlspecialchars($p['TipoTurno']); ?></td>
                    <td>
                        <?php
                        if ($p['EsPermisoEspecial']) {
                            echo 'Permiso especial';
                        } elseif ($p['EsDescanso']) {
                            echo 'Descanso';
                        }
                        ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>No hay permisos ni descansos registrados para este mes.</p>
    <?php endif; ?>

    <br><a href="index.php?controller=empleado&action=panel">Volver al panel</a>
</div>

</body>
</html>
