<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Subespecialidades</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
<div class="container">
    <h2>Subespecialidades</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Acciones</th>
        </tr>
        <?php foreach ($subs as $s): ?>
            <tr>
                <td><?php echo (int)$s['ID_Subespecialidad']; ?></td>
                <td><?php echo htmlspecialchars($s['NombreSubespecialidad']); ?></td>
                <td>
                    <a href="index.php?controller=admin&action=editarSubespecialidad&ID_Subespecialidad=<?php echo (int)$s['ID_Subespecialidad']; ?>">Editar</a> |
                    <a href="index.php?controller=admin&action=borrarSubespecialidad&ID_Subespecialidad=<?php echo (int)$s['ID_Subespecialidad']; ?>" onclick="return confirm('¿Borrar?');">Borrar</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    <br><a href="index.php?controller=admin&action=panel">Volver al panel admin</a>
</div>
</body>
</html>
