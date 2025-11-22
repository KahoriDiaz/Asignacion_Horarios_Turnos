<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Agregar empleado</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
<div class="container">
    <h2>Nuevo empleado</h2>
    <?php if (!empty($mensaje)): ?>
        <p><?php echo htmlspecialchars($mensaje); ?></p>
    <?php endif; ?>
    <form method="post" action="">
        <label>DNI:</label>
        <input type="text" name="DNI_Personal" maxlength="8" pattern="\d{8}" required>
        <label>Nombre:</label>
        <input type="text" name="ApellidosNombres" required>
        <label>Celular:</label>
        <input type="text" name="Celular" maxlength="9">
        <label>Fecha nacimiento:</label>
        <input type="date" name="FechaNacimiento" required>
        <label>Estado:</label>
        <input type="text" name="Estado">
        <label>¿Es encargado de piso?</label>
        <input type="checkbox" name="Encargado" value="1">
        <label>Piso:</label>
        <select name="Nro_Piso" required>
            <option value="">Seleccione</option>
            <?php foreach ($pisos as $p): ?>
                <option value="<?php echo (int)$p['Nro_Piso']; ?>"><?php echo htmlspecialchars($p['NombrePiso']); ?></option>
            <?php endforeach; ?>
        </select>
        <label>Subespecialidad:</label>
        <select name="ID_Subespecialidad" required>
            <option value="">Seleccione</option>
            <?php foreach ($subs as $s): ?>
                <option value="<?php echo (int)$s['ID_Subespecialidad']; ?>"><?php echo htmlspecialchars($s['NombreSubespecialidad']); ?></option>
            <?php endforeach; ?>
        </select>
        <label>Condición laboral:</label>
        <select name="ID_Condicion" required>
            <option value="">Seleccione</option>
            <?php foreach ($condiciones as $c): ?>
                <option value="<?php echo (int)$c['ID_Condicion']; ?>"><?php echo htmlspecialchars($c['NombreCondicion']); ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Guardar</button>
    </form>
    <br><a href="index.php?controller=admin&action=listarEmpleados">Volver al listado</a>
</div>
</body>
</html>
