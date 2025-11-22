<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ver Programación Mensual de Turnos</title>
    <link rel="stylesheet" href="estilos.css">
    <style>
        /* Estilos para pantalla */
        .container { max-width: 1200px; margin: auto; padding: 20px; }
        .main-content { background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        h2, h3 { text-align: center; color: #333; margin: 10px 0; }
        form { margin: 20px 0; padding: 15px; background: #f9f9f9; border-radius: 5px; }
        form label { margin-right: 10px; font-weight: bold; }
        form select, form button { margin: 5px; padding: 8px 12px; }
        button { background: #337ab7; color: #fff; border: 0; border-radius: 4px; cursor: pointer; }
        button:hover { background: #2a5f99; }
        .btn-imprimir { background: #5cb85c; padding: 10px 20px; font-size: 14px; margin: 15px 0; }
        .btn-imprimir:hover { background: #4cae4c; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 20px; font-size: 11px; }
        th, td { border: 1px solid #999; padding: 6px 4px; text-align: center; }
        th { background: #e0e0e0; font-weight: bold; color: #222; }
        td { background: #fff; }
        .empleado-nombre { text-align: left; font-weight: 500; background: #f5f5f5; }

        /* Header para impresión */
        .header-impresion {
            display: none;
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header-impresion h1 { margin: 5px 0; font-size: 18px; }
        .header-impresion h2 { margin: 5px 0; font-size: 14px; font-weight: normal; }
        .header-impresion h3 { margin: 5px 0; font-size: 13px; font-weight: normal; }

        /* Estilos para impresión/PDF */
        @media print {
            /* Ocultar elementos de navegación */
            button, form, a, .btn-imprimir { display: none !important; }
            
            /* Mostrar header solo en impresión */
            .header-impresion { display: block !important; }
            
            /* Optimizar página */
            body { 
                background: #fff !important; 
                margin: 0; 
                padding: 10mm;
                font-family: Arial, sans-serif;
            }
            .container, .main-content { 
                max-width: 100% !important; 
                padding: 0 !important; 
                margin: 0 !important;
                box-shadow: none !important;
            }
            
            /* Tabla optimizada */
            table { 
                page-break-inside: auto;
                font-size: 9px;
                width: 100%;
            }
            tr { page-break-inside: avoid; page-break-after: auto; }
            thead { display: table-header-group; }
            th { 
                background: #d0d0d0 !important; 
                color: #000 !important;
                font-weight: bold;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            td { 
                color: #000 !important;
                background: #fff !important;
            }
            .empleado-nombre {
                background: #f0f0f0 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            
            /* Títulos */
            h2, h3 { color: #000 !important; }
            
            /* Orientación horizontal para mejor visualización */
            @page {
                size: landscape;
                margin: 10mm;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="main-content">
        
        <!-- Header que solo aparece al imprimir/PDF -->
        <div class="header-impresion">
            <h1>DEPARTAMENTO DE ENFERMERÍA</h1>
            <h2>SERVICIO: <?php 
                // Obtener nombre de sala para el header
                $nombre_sala_header = 'SALA SELECCIONADA';
                foreach ($salas as $sala) {
                    if ($sala['NumeroSala'] == $sala_seleccionada) {
                        $nombre_sala_header = strtoupper($sala['NombreSala']);
                        break;
                    }
                }
                echo $nombre_sala_header;
            ?></h2>
            <h3>PROGRAMACIÓN DE TURNOS, GUARDIAS Y HORARIOS DEL SERVICIO ASISTENCIAL</h3>
            <h3>CORRESPONDIENTE AL MES DE <?php 
                $meses = ['','ENERO','FEBRERO','MARZO','ABRIL','MAYO','JUNIO','JULIO','AGOSTO','SEPTIEMBRE','OCTUBRE','NOVIEMBRE','DICIEMBRE'];
                echo $meses[$mes] . ' DEL ' . $anio;
            ?></h3>
        </div>

        <h2>Programación de Turnos – Año <?php echo (int)$anio; ?>, Mes <?php echo (int)$mes; ?> (Piso <?php echo (int)$piso; ?>)</h2>

        <form method="post" action="index.php?controller=encargado&action=verProgramacionTurnos">
            <label for="Anio">Año:</label>
            <select name="Anio" id="Anio">
                <?php
                $anio_actual = date('Y');
                for ($a = $anio_actual - 1; $a <= $anio_actual + 1; $a++): ?>
                    <option value="<?php echo $a; ?>" <?php if ($a == $anio) echo "selected"; ?>>
                        <?php echo $a; ?>
                    </option>
                <?php endfor; ?>
            </select>

            <label for="Mes">Mes:</label>
            <select name="Mes" id="Mes">
                <?php for ($m = 1; $m <= 12; $m++): ?>
                    <option value="<?php echo $m; ?>" <?php if ($m == $mes) echo "selected"; ?>>
                        <?php echo $m; ?>
                    </option>
                <?php endfor; ?>
            </select>

            <label for="NumeroSala">Sala:</label>
            <select name="NumeroSala" id="NumeroSala">
                <option value="0">Selecciona sala</option>
                <?php foreach ($salas as $sala): ?>
                    <option value="<?php echo $sala['NumeroSala']; ?>"
                        <?php if ($sala['NumeroSala'] == $sala_seleccionada) echo "selected"; ?>>
                        <?php echo htmlspecialchars($sala['NombreSala']); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <button type="submit">Ver programación</button>
        </form>

        <?php if ($sala_seleccionada > 0): ?>
            <button class="btn-imprimir" onclick="window.print()">
                📄 Imprimir / Guardar como PDF
            </button>
        <?php endif; ?>

        <?php if ($sala_seleccionada > 0 && !empty($personal_sala)): ?>
            <h3>Calendario de turnos</h3>
            <div style="overflow-x:auto;">
                <table>
                    <thead>
                        <tr>
                            <th rowspan="2">APELLIDOS Y NOMBRES</th>
                            <?php for ($d = 1; $d <= $dias_mes; $d++): ?>
                                <th><?php echo $d; ?></th>
                            <?php endfor; ?>
                            <th rowspan="2">TOTAL</th>
                        </tr>
                        <tr>
                            <?php 
                            $dias_semana = ['D','L','M','M','J','V','S'];
                            for ($d = 1; $d <= $dias_mes; $d++): 
                                $fecha = mktime(0, 0, 0, $mes, $d, $anio);
                                $dia_semana = $dias_semana[date('w', $fecha)];
                            ?>
                                <th><?php echo $dia_semana; ?></th>
                            <?php endfor; ?>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($personal_sala as $pers): ?>
                        <?php
                        $dniEmp = $pers['DNI_Personal'];
                        $total = 0;
                        ?>
                        <tr>
                            <td class="empleado-nombre"><?php echo htmlspecialchars($pers['ApellidosNombres']); ?></td>
                            <?php for ($d = 1; $d <= $dias_mes; $d++): ?>
                                <td>
                                    <?php
                                    $val = isset($asignaciones_por_emp[$dniEmp][$d]) ? $asignaciones_por_emp[$dniEmp][$d] : '';
                                    if (in_array($val, ['M','T','N'])) {
                                        echo $val;
                                        $total++;
                                    } else {
                                        echo '';
                                    }
                                    ?>
                                </td>
                            <?php endfor; ?>
                            <td><strong><?php echo $total; ?></strong></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php elseif ($sala_seleccionada > 0): ?>
            <p>No hay programación registrada para este mes y sala.</p>
        <?php else: ?>
            <p>Seleccione un año, mes y sala para ver la programación.</p>
        <?php endif; ?>

        <br><a href="index.php?controller=encargado&action=panel">Volver al panel del encargado</a>
    </div>
</div>

</body>
</html>
