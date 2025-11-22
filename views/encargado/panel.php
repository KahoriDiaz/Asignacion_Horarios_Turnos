<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Panel del Encargado</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>

<div class="container">
    <h2>Panel del Encargado</h2>
    <p><strong>Bienvenido, <?php echo htmlspecialchars($encargado['ApellidosNombres']); ?></strong> - Piso <?php echo (int)$nro_piso; ?></p>

    <div style="display:flex; gap:20px; margin:20px 0; flex-wrap:wrap;">
        <div style="min-width:220px; max-width:260px;">
            <h3>Menú</h3>
            <ul style="list-style:none; padding-left:0;">
                <li><a href="index.php?controller=encargado&action=seleccionarSala">Distribución Anual por Salas</a></li>
                <li><a href="index.php?controller=encargado&action=programacionMensual">Programación Mensual de Turnos</a></li>
                <li><a href="index.php?controller=encargado&action=verProgramacionTurnos">Ver Programación Mensual</a></li>
                <li><a href="index.php?controller=auth&action=logout">Cerrar sesión</a>
            </ul>
        </div>

        <div style="flex:1;">
            <h3>Resumen de Piso</h3>
            <div style="display:flex; flex-wrap:wrap; gap:16px;">

                <div style="flex:1 1 150px; padding:12px 14px; border-radius:8px; background:#f9fafb; border:1px solid #e5e7eb;">
                    <h4>Empleados del piso</h4>
                    <p style="font-size:1.4rem; font-weight:600; margin-top:4px;">
                        <?php echo (int)$cantidad_empleados; ?>
                    </p>
                </div>

                <div style="flex:1 1 150px; padding:12px 14px; border-radius:8px; background:#f9fafb; border:1px solid #e5e7eb;">
                    <h4>Salas del piso</h4>
                    <p style="font-size:1.4rem; font-weight:600; margin-top:4px;">
                        <?php echo (int)$cantidad_salas; ?>
                    </p>
                </div>

                <div style="flex:1 1 180px; padding:12px 14px; border-radius:8px; background:#f9fafb; border:1px solid #e5e7eb;">
                    <h4>Personal asignado a sala</h4>
                    <p style="font-size:1.4rem; font-weight:600; margin-top:4px;">
                        <?php echo (int)$cantidad_personal_asignado; ?>
                    </p>
                </div>

                <?php if (!empty($sala_max)): ?>
                    <div style="flex:1 1 220px; padding:12px 14px; border-radius:8px; background:#f9fafb; border:1px solid #e5e7eb;">
                        <h4>Sala con mayor capacidad</h4>
                        <p style="margin-top:4px;">
                            <strong><?php echo (int)$sala_max['NumeroSala']; ?> - <?php echo htmlspecialchars($sala_max['NombreSala']); ?></strong><br>
                            Capacidad: <?php echo (int)$sala_max['Capacidad']; ?>
                        </p>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </div>

    <p style="margin-top:10px; color:#4b5563;">
        Utiliza el menú para gestionar la distribución anual y la programación de turnos de tu piso.
    </p>
</div>

</body>
</html>
