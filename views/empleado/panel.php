<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Panel de Empleado</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>

<div class="container">
    <h2>Panel de Empleado</h2>
    <p><strong>Bienvenido, <?php echo htmlspecialchars($empleado['ApellidosNombres']); ?></strong></p>

    <div style="display:flex; gap:20px; margin:20px 0; flex-wrap:wrap;">
        <!-- Menú lateral -->
        <div style="min-width:220px; max-width:260px;">
            <h3>Menú</h3>
            <ul style="list-style:none; padding-left:0;">
                <li>
                    <a href="index.php?controller=empleado&action=verHorariosMensuales">
                        Ver mis horarios mensuales
                    </a>
                </li>
                <li>
                    <a href="index.php?controller=empleado&action=solicitarPermiso">
                        Solicitar permiso especial
                    </a>
                </li>
                <li>
                    <a href="index.php?controller=empleado&action=verPermisos">
                        Ver permisos registrados
                    </a>
                </li>
                <li>
                    <a href="index.php?controller=auth&action=logout">Cerrar sesión</a>
                </li>
            </ul>
        </div>

        <div style="flex:1;">
            <h3>Resumen</h3>
            <p style="color:#4b5563;">
                Usa el menú para ver tus turnos mensuales o gestionar tus permisos.
            </p>
        </div>
    </div>
</div>

</body>
</html>
