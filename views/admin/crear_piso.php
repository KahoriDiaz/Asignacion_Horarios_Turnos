<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Agregar Piso</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
<div class="container">
    <h2>Nuevo Piso</h2>
    <?php if (!empty($mensaje)): ?>
        <p><?php echo htmlspecialchars($mensaje); ?></p>
    <?php endif; ?>
    <form method="post" action="">
        <label>Número Piso:</label>
        <input type="number" name="Nro_Piso" required>
        <label>Nombre Piso:</label>
        <input type="text" name="NombrePiso" required>
        <button type="submit">Guardar</button>
    </form>
    <br><a href="index.php?controller=admin&action=listarPisos">Volver al listado</a>
</div>
</body>
</html>
