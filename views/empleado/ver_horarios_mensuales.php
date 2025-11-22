<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Mis horarios mensuales</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>

<div class="container">
    <h2>Mis horarios mensuales</h2>
    <p><strong><?php echo htmlspecialchars($empleado['ApellidosNombres']); ?></strong></p>

    <form method="post" action="index.php?controller=empleado&action=verHorariosMensuales">
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

    <h3>Calendario de turnos</h3>
    <div style="overflow-x:auto;">
        <table class="calendario">
            <tr>
                <th>Día</th>
                <?php for ($d = 1; $d <= $dias_mes; $d++): ?>
                    <th><?php echo $d; ?></th>
                <?php endfor; ?>
            </tr>
            <tr>
                <td>Turno</td>
                <?php for ($d = 1; $d <= $dias_mes; $d++): ?>
                    <td style="text-align:center;">
                        <?php
                        if (!empty($turnos[$d])) {
                            $tipo = $turnos[$d]; // M/T/N
                            $clase = 'turno-' . $tipo;
                            echo '<span class="turno-chip ' . $clase . '">' . htmlspecialchars($tipo) . '</span>';
                        } else {
                            echo '&nbsp;';
                        }
                        ?>
                    </td>
                <?php endfor; ?>
            </tr>
        </table>
    </div>

    <br><a href="index.php?controller=empleado&action=panel">Volver al panel</a>
</div>

</body>
</html>
