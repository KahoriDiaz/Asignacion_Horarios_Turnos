<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Crear Subespecialidad</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
<h2>Crear Subespecialidad</h2>

<?php if (!empty($mensaje)): ?>
    <p><?php echo htmlspecialchars($mensaje); ?></p>
<?php endif; ?>

<form method="post" action="index.php?controller=admin&action=crearSubespecialidad">
    <label>Nombre de Subespecialidad:</label>
    <input type="text" name="NombreSubespecialidad" required><br><br>

    <button type="submit">Guardar</button>
</form>

<br><a href="index.php?controller=admin&action=panel">Volver al panel admin</a>
</body>
</html>
