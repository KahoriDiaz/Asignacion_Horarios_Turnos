<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Editar Subespecialidad</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
<div class="container">
    <h2>Editar Subespecialidad <?php echo (int)$sub['ID_Subespecialidad']; ?></h2>
    <?php if (!empty($mensaje)): ?>
        <p><?php echo htmlspecialchars($mensaje); ?></p>
    <?php endif; ?>
    <form method="post" action="">
        <label>Nombre de la Subespecialidad:</label>
        <input type="text" name="NombreSubespecialidad" value="<?php echo htmlspecialchars($sub['NombreSubespecialidad']); ?>" required>
        <button type="submit">Guardar cambios</button>
    </form>
    <br><a href="index.php?controller=admin&action=listarSubespecialidades">Volver al listado</a>
</div>
</body>
</html>
