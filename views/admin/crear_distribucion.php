<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Agregar Distribución Anual</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
<div class="container">
    <h2>Nueva Distribución Anual de Sala</h2>
    <?php if (!empty($mensaje)): ?>
        <p><?php echo htmlspecialchars($mensaje); ?></p>
    <?php endif; ?>
    <form method="post" action="">
        <label>Piso:</label>
        <select name="Nro_Piso" required>
            <option value="">Seleccione</option>
            <?php foreach ($pisos as $p): ?>
                <option value="<?php echo (int)$p['Nro_Piso']; ?>"><?php echo htmlspecialchars($p['NombrePiso']); ?></option>
            <?php endforeach; ?>
        </select>
        <label>Sala:</label>
        <select name="NumeroSala" required>
            <option value="">Seleccione</option>
            <?php foreach ($salas as $s): ?>
                <option value="<?php echo (int)$s['NumeroSala']; ?>"><?php echo htmlspecialchars($s['NombreSala']); ?></option>
            <?php endforeach; ?>
        </select>
        <label>Año:</label>
        <input type="number" name="Anio_Distribucion" value="<?php echo date('Y'); ?>" required>
        <label>Mínimos por turno:</label>
        <input type="number" name="MinimosXTurno" value="1">
        <button type="submit">Guardar</button>
    </form>
    <br><a href="index.php?controller=admin&action=listarDistribuciones">Volver al listado</a>
</div>
</body>
</html>
