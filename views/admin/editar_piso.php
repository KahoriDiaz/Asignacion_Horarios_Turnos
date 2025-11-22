<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Editar Piso</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
<div class="container">
    <h2>Editar Piso <?php echo (int)$piso['Nro_Piso']; ?></h2>
    <?php if (!empty($mensaje)): ?>
        <p><?php echo htmlspecialchars($mensaje); ?></p>
    <?php endif; ?>
    <form method="post" action="">
        <label>Nombre Piso:</label>
        <input type="text" name="NombrePiso" value="<?php echo htmlspecialchars($piso['NombrePiso']); ?>" required>
        <button type="submit">Guardar cambios</button>
    </form>
    <br><a href="index.php?controller=admin&action=listarPisos">Volver al listado</a>
</div>
</body>
</html>
