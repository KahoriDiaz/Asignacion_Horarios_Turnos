<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Solicitar permiso especial</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>

<div class="container">
    <h2>Solicitar permiso especial</h2>
    <p><strong><?php echo htmlspecialchars($empleado['ApellidosNombres']); ?></strong></p>

    <?php if (!empty($mensaje_error)): ?>
        <p class="msg-error"><?php echo htmlspecialchars($mensaje_error); ?></p>
    <?php endif; ?>
    <?php if (!empty($mensaje_ok)): ?>
        <p class="msg-ok"><?php echo htmlspecialchars($mensaje_ok); ?></p>
    <?php endif; ?>

    <form method="post" action="index.php?controller=empleado&action=solicitarPermiso">
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

        <label for="Dia">Día:</label>
        <select name="Dia" id="Dia">
            <?php for ($d = 1; $d <= $dias_mes; $d++): ?>
                <option value="<?php echo $d; ?>" <?php if ($d == $dia) echo "selected"; ?>>
                    <?php echo $d; ?>
                </option>
            <?php endfor; ?>
        </select>

        <label for="TipoTurno">Tipo de turno:</label>
        <select name="TipoTurno" id="TipoTurno">
            <option value="M">Matutino (M)</option>
            <option value="T">Tarde (T)</option>
            <option value="N">Noche (N)</option>
        </select>

        <button type="submit" name="guardar_permiso" value="1">Registrar permiso</button>
    </form>

    <br><a href="index.php?controller=empleado&action=panel">Volver al panel</a>
</div>

</body>
</html>
