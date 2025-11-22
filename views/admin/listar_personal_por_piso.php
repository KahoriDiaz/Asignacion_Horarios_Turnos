<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Personal por piso</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
<div class="container">
    <h2>Personal asignado por piso</h2>
    <table>
        <tr>
            <th>Piso</th>
            <th>Empleado</th>
            <th>DNI</th>
        </tr>
        <?php foreach ($personal as $p): ?>
            <tr>
                <td><?php echo htmlspecialchars($p['NombrePiso']); ?></td>
                <td><?php echo htmlspecialchars($p['ApellidosNombres']); ?></td>
                <td><?php echo htmlspecialchars($p['DNI_Personal']); ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
    <br><a href="index.php?controller=admin&action=panel">Volver al panel admin</a>
</div>
</body>
</html>
