<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Agregar Sala</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
<div class="container">
    <h2>Nueva Sala</h2>
    <?php if (!empty($mensaje)): ?>
        <p><?php echo htmlspecialchars($mensaje); ?></p>
    <?php endif; ?>
    <form method="post" action="">
        <label>Número Sala:</label>
        <input type="number" name="NumeroSala" required>
        <label>Nombre Sala:</label>
        <input type="text" name="NombreSala" required>
        <label>Capacidad:</label>
        <input type="number" name="Capacidad">
        <button type="submit">Guardar</button>
    </form>
    <br><a href="index.php?controller=admin&action=listarSalas">Volver al listado</a>
</div>
</body>
</html>
