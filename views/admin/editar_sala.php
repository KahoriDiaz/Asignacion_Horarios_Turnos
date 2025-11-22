<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Editar Sala</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
<div class="container">
    <h2>Editar Sala <?php echo (int)$sala['NumeroSala']; ?></h2>
    <?php if (!empty($mensaje)): ?>
        <p><?php echo htmlspecialchars($mensaje); ?></p>
    <?php endif; ?>
    <form method="post" action="">
        <label>Nombre Sala:</label>
        <input type="text" name="NombreSala" value="<?php echo htmlspecialchars($sala['NombreSala']); ?>" required>
        <label>Capacidad:</label>
        <input type="number" name="Capacidad" value="<?php echo (int)$sala['Capacidad']; ?>">
        <button type="submit">Guardar cambios</button>
    </form>
    <br><a href="index.php?controller=admin&action=listarSalas">Volver al listado</a>
</div>
</body>
</html>
