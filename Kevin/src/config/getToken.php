<?php 
function getToken() {
    return json_decode(file_get_contents(__DIR__ . '/token.json'), true);
}
function tokenInvalid($token):bool {
    $tiempoActual = time(); // Timestamp actual en segundos
    $expiracion = (int)$token['expires'];

    // Margen de 1 minuto por seguridad
    return ($expiracion - 60) < $tiempoActual;
}