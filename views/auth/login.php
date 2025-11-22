<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inicio - Hospital de Enfermería</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
<div class="container">
    <h2>Hospital de Enfermería</h2>
    <p>Ingrese su DNI para continuar</p>

    <?php if (!empty($error)): ?>
        <p class="error"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>

<form action="index.php?controller=auth&action=login" method="POST">
    <input type="text" name="dni" placeholder="Ingrese su DNI" maxlength="8"><br>
    <button type="submit" name="tipo" value="encargado">Ingresar como Encargado</button><br>
    <button type="submit" name="tipo" value="empleado">Ingresar como Empleado</button><br>
    <button type="submit" name="tipo" value="admin">Ingresar como Administrador</button>
</form>

    
</div>
</body>
</html>
