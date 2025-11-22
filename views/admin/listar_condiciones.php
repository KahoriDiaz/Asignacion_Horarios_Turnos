<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Condiciones Laborales</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
<div class="container">
    <h2>Condiciones Laborales</h2>
    <a href="index.php?controller=admin&action=crearCondicionLaboral">Agregar condición</a>
    <table>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Acciones</th>
        </tr>
        <?php foreach ($condiciones as $c): ?>
            <tr>
                <td><?php echo (int)$c['ID_Condicion']; ?></td>
                <td><?php echo htmlspecialchars($c['NombreCondicion']); ?></td>
                <td>
                    <a href="index.php?controller=admin&action=editarCondicionLaboral&ID_Condicion=<?php echo (int)$c['ID_Condicion']; ?>">Editar</a> |
                    <a href="index.php?controller=admin&action=borrarCondicionLaboral&ID_Condicion=<?php echo (int)$c['ID_Condicion']; ?>" onclick="return confirm('¿Borrar?');">Borrar</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    <br><a href="index.php?controller=admin&action=panel">Volver al panel admin</a>
</div>
</body>
</html>
