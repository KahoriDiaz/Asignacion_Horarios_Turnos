SISTEMA DE PROGRAMACIÓN DE TURNOS HOSPITALARIOS

REQUISITOS PARA EJECUTAR
- XAMPP instalado (solo necesitas el servicio Apache y MySQL)
- MySQL Workbench (para importar la base de datos)
- Un navegador moderno (Chrome, Edge, Firefox, etc.)
- PHP 7.x/8.x (incluido en XAMPP)

ARCHIVOS INCLUIDOS
- Carpeta `Asignacion_Horarios_Turnos/` : contiene todo el código fuente PHP, modelos, controladores y vistas
- Archivo `config.php` : define los parámetros de conexión a la base de datos
- Archivo `turnos_db.sql` : backup/exportación de la base de datos para importar en MySQL Workbench
- Carpeta `public/` : contiene los archivos estáticos y hoja de estilos
- Archivo `README.txt` : este documento

INSTALACIÓN Y USO
1. INICIA XAMPP
   - Asegúrate de que **Apache** esté en ejecución.

2. COPIA CARPETA
   - Copia la carpeta `Asignacion_Horarios_Turnos/` dentro de la carpeta `htdocs` de tu instalación XAMPP.

3. IMPORTA BASE DE DATOS
   - Abre MySQL Workbench
   - Conéctate al servidor local.
   - En “File → Open SQL Script”, elige `turnos_db.sql` y ejecuta el script para crear la base de datos y tablas.
   - Si ya tienes creada la bd “turnos_db”, puedes solo importar las tablas.

4. CONFIGURA CONEXIÓN
   - Verifica la configuración de tu `config.php`:
     - Servidor: localhost
     - Usuario: <root>
     - Contraseña: <contraseña>
     - Base de datos: turnos_db

     Ajusta estos parámetros si tu servidor/usuario es diferente.

5. ACCESO AL SISTEMA
   - Abre tu navegador y entra en:  
     `http://localhost/Asignacion_Horarios_Turnos/public/index.php`  
   - Accede al login con el DNI de encargado(11111111) , empleado, o directamente como administrado, 

