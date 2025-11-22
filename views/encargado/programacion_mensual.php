<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Programación Mensual de Turnos</title>
    <link rel="stylesheet" href="estilos.css">
    <style>
        .permiso-celda { background: #fde68a; }
        .cumple-celda  { background: #fca5a5; }
    </style>
</head>
<body>
<div class="container">
    <h2>Programación Mensual de Turnos (Año <?php echo (int)$anio; ?>, Mes <?php echo (int)$mes; ?>)</h2>

    <?php if (!empty($mensaje_error)): ?>
        <p class="msg-error"><?php echo $mensaje_error; ?></p>
    <?php endif; ?>
    <?php if (!empty($mensaje_ok)): ?>
        <p class="msg-ok"><?php echo htmlspecialchars($mensaje_ok); ?></p>
    <?php endif; ?>

    <form method="post" action="index.php?controller=encargado&action=programacionMensual">
        <label>Año:</label>
        <select name="Anio">
            <?php
            $anio_actual = date('Y');
            for ($a = $anio_actual - 1; $a <= $anio_actual + 1; $a++): ?>
                <option value="<?php echo $a; ?>" <?php if ($a == $anio) echo "selected"; ?>><?php echo $a; ?></option>
            <?php endfor; ?>
        </select>
        <label>Mes:</label>
        <select name="Mes">
            <?php for ($m = 1; $m <= 12; $m++): ?>
                <option value="<?php echo $m; ?>" <?php if ($m == $mes) echo "selected"; ?>><?php echo $m; ?></option>
            <?php endfor; ?>
        </select>
        <label>Sala:</label>
        <select name="NumeroSala">
            <option value="0">Selecciona sala</option>
            <?php foreach ($salas as $sala): ?>
                <option value="<?php echo $sala['NumeroSala']; ?>" <?php if ($sala['NumeroSala'] == $sala_seleccionada) echo "selected"; ?>>
                    <?php echo htmlspecialchars($sala['NombreSala']); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Ver asignados</button>
        <?php if (!empty($personal_sala)): ?>
            <button type="submit" name="asignar_azar" value="1">Asignar turnos al azar</button>
        <?php endif; ?>
    </form>

    <?php if (!empty($personal_sala)): ?>
        <form method="post" action="index.php?controller=encargado&action=programacionMensual" style="margin-top:10px;">
            <input type="hidden" name="Anio" value="<?php echo (int)$anio; ?>">
            <input type="hidden" name="Mes" value="<?php echo (int)$mes; ?>">
            <input type="hidden" name="NumeroSala" value="<?php echo (int)$sala_seleccionada; ?>">
            <button type="submit" name="borrar_mes" onclick="return confirm('¿Borrar toda la programación del mes?');">Borrar programación del mes</button>
        </form>
    <?php endif; ?>

    <?php if (!empty($permisos)): ?>
        <div class="msg-ok">
            <strong>Permisos y descansos pedidos:</strong>
            <ul>
                <?php foreach ($permisos as $p): ?>
                    <li><?php echo htmlspecialchars($p['ApellidosNombres']); ?> - Día <?php echo (int)$p['Dia']; ?> (Turno <?php echo htmlspecialchars($p['TipoTurno']); ?>)</li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if (!empty($cumples)): ?>
        <div class="msg-ok">
            <strong>Cumpleaños del mes:</strong>
            <ul>
            <?php foreach ($cumples as $c): ?>
                <li><?php echo htmlspecialchars($c['ApellidosNombres']); ?> - Día <?php echo (int)$c['Dia']; ?></li>
            <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if (!empty($personal_sala)): ?>
        <h3>Personal asignado a la sala (Capacidad: <?php echo (int)$capacidad_sala; ?>)</h3>
        <ul>
            <?php foreach ($personal_sala as $pers): ?>
                <li><?php echo htmlspecialchars($pers['ApellidosNombres']); ?> (<?php echo htmlspecialchars($pers['DNI_Personal']); ?>)</li>
            <?php endforeach; ?>
        </ul>
        <form method="post" action="index.php?controller=encargado&action=programacionMensual">
            <input type="hidden" name="Anio" value="<?php echo (int)$anio; ?>">
            <input type="hidden" name="Mes" value="<?php echo (int)$mes; ?>">
            <input type="hidden" name="NumeroSala" value="<?php echo (int)$sala_seleccionada; ?>">
            <div style="overflow-x:auto;">
                <table border="1" cellpadding="3" cellspacing="0">
                    <tr>
                        <th>Empleado \ Día</th>
                        <?php for ($d = 1; $d <= $dias_mes; $d++): ?>
                            <th><?php echo $d; ?></th>
                        <?php endfor; ?>
                        <th>Total Turnos</th>
                    </tr>
                    <?php foreach ($personal_sala as $pers): ?>
                        <?php $dniEmp = $pers['DNI_Personal']; $total=0;?>
                        <tr>
                            <td><?php echo htmlspecialchars($pers['ApellidosNombres']); ?></td>
                            <?php for ($d = 1; $d <= $dias_mes; $d++): 
                                $val = isset($asignaciones_por_emp[$dniEmp][$d]) ? $asignaciones_por_emp[$dniEmp][$d] : '';
                                $isPermiso = isset($mapa_permiso[$dniEmp][$d]);
                                $isCumple = isset($mapa_cumple[$dniEmp][$d]);
                                $extraClass = $isPermiso ? 'permiso-celda' : ($isCumple ? 'cumple-celda' : '');
                                if (in_array($val, ['M','T','N'])) $total++;
                            ?>
                                <td class="<?php echo $extraClass; ?>">
                                    <select name="turno[<?php echo $dniEmp; ?>][<?php echo $d; ?>]" <?php if($isPermiso) echo 'disabled'; ?>>
                                        <option value=""></option>
                                        <option value="M" <?php if($val=='M') echo 'selected'; ?>>M</option>
                                        <option value="T" <?php if($val=='T') echo 'selected'; ?>>T</option>
                                        <option value="N" <?php if($val=='N') echo 'selected'; ?>>N</option>
                                    </select>
                                </td>
                            <?php endfor; ?>
                            <td><?php echo $total; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
            <button type="submit" name="confirmar" value="1">Confirmar y guardar programación</button>
        </form>
        <script>
        // Validación JS sencilla (opcional)
        document.querySelector('form[action*="programacionMensual"]').addEventListener('submit', function(e){
            var errores = [];
            var maxTurnos = 25;
            var filas = document.querySelectorAll('table tr');
            for(var i=1; i<filas.length; i++) {
                var selects = filas[i].querySelectorAll('select');
                var count = 0;
                selects.forEach(function(sel){
                    if(sel.value==='M'||sel.value==='T'||sel.value==='N') count++;
                });
                if(count > maxTurnos) {
                    errores.push("Un empleado tiene más de 25 turnos");
                }
            }
            if(errores.length>0) {
                alert(errores.join('\n')+"\nCorrige antes de guardar.");
                e.preventDefault();
            }
        });
        </script>
    <?php endif; ?>

    <br><a href="index.php?controller=encargado&action=panel">Volver al panel del encargado</a>
</div>
</body>
</html>
