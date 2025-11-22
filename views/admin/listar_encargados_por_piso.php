<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Encargados por piso</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
<div class="container">
    <h2>Encargados por piso</h2>
    <table>
        <tr>
            <th>Piso</th>
            <th>Encargado</th>
            <th>DNI</th>
        </tr>
        <?php foreach ($encargados as $e): ?>
            <tr>
                <td><?php echo htmlspecialchars($e['NombrePiso']); ?></td>
                <td><?php echo htmlspecialchars($e['ApellidosNombres']); ?></td>
                <td><?php echo htmlspecialchars($e['DNI_Encargado']); ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
    <br><a href="index.php?controller=admin&action=panel">Volver al panel admin</a>
</div>
</body>
</html>
