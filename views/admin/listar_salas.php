<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Salas</title>
    <link rel="stylesheet" href="/public/estilos.css">
</head>
<body>
<div class="container">
    <h2>Salas</h2>
    <a href="index.php?controller=admin&action=crearSala">Agregar nueva sala</a>
    <table>
        <tr>
            <th>Número Sala</th>
            <th>Nombre Sala</th>
            <th>Capacidad</th>
            <th>Acciones</th>
        </tr>
        <?php foreach ($salas as $s): ?>
            <tr>
                <td><?php echo (int)$s['NumeroSala']; ?></td>
                <td><?php echo htmlspecialchars($s['NombreSala']); ?></td>
                <td><?php echo (int)$s['Capacidad']; ?></td>
                <td>
                    <a href="index.php?controller=admin&action=editarSala&NumeroSala=<?php echo (int)$s['NumeroSala']; ?>">Editar</a> |
                    <a href="index.php?controller=admin&action=borrarSala&NumeroSala=<?php echo (int)$s['NumeroSala']; ?>" onclick="return confirm('¿Borrar?');">Borrar</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    <br><a href="index.php?controller=admin&action=panel">Volver al panel admin</a>
</div>
</body>
</html>
