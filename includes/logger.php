<?php
// includes/logger.php
function registrar_log($accion, $detalle = '', $nivel = 'INFO') {
    $fecha = date('Y-m-d H:i:s');
    $usuario = $_SESSION['nombre'] ?? 'Invitado';
    $id_usuario = $_SESSION['id_usuario'] ?? '0';
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'Desconocida';
    
    // Formato del log: [Fecha] [Nivel] | Usuario (ID) | IP | Acción | Detalle
    $mensaje = "[$fecha] [$nivel] | Usuario: $usuario (ID: $id_usuario) | IP: $ip | Acción: $accion | Detalle: $detalle" . PHP_EOL;
    
    // Ruta hacia la carpeta logs
    $ruta = __DIR__ . '/../logs/sistema.log';
    
    // Escribir en el archivo (LOCK_EX evita que dos procesos escriban a la vez)
    file_put_contents($ruta, $mensaje, FILE_APPEND | LOCK_EX);
}