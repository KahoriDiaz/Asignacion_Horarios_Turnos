<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Agregar Condición Laboral</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
<div class="container">
    <h2>Nueva Condición Laboral</h2>
    <?php if (!empty($mensaje)): ?>
        <p><?php echo htmlspecialchars($mensaje); ?></p>
    <?php endif; ?>
    <form method="post" action="">
        <label>Nombre de la condición:</label>
        <input type="text" name="NombreCondicion" required>
        <button type="submit">Guardar</button>
    </form>
    <br><a href="index.php?controller=admin&action=listarCondicionesLaborales">Volver al listado</a>
</div>
</body>
</html>
