<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Pisos</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
<div class="container">
    <h2>Pisos</h2>
    <a href="index.php?controller=admin&action=crearPiso">Agregar nuevo piso</a>
    <table>
        <tr>
            <th>Nro Piso</th>
            <th>Nombre</th>
            <th>Acciones</th>
        </tr>
        <?php foreach ($pisos as $p): ?>
            <tr>
                <td><?php echo (int)$p['Nro_Piso']; ?></td>
                <td><?php echo htmlspecialchars($p['NombrePiso']); ?></td>
                <td>
                    <a href="index.php?controller=admin&action=editarPiso&Nro_Piso=<?php echo (int)$p['Nro_Piso']; ?>">Editar</a> |
                    <a href="index.php?controller=admin&action=borrarPiso&Nro_Piso=<?php echo (int)$p['Nro_Piso']; ?>" onclick="return confirm('¿Borrar?');">Borrar</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    <br><a href="index.php?controller=admin&action=panel">Volver al panel admin</a>
</div>
</body>
</html>
