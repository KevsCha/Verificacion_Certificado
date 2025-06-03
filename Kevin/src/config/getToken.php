<?php 
function getToken() {
    return json_decode(file_get_contents(__DIR__ . '/token.json'), true);
}
function tokenInvalid($token):bool {
    $tiempoActual = time();
    $expiracion = (int)$token;
    echo "\nTiempo actual: $tiempoActual\n Expiración: $expiracion\n";

    return ($expiracion - 60) < $tiempoActual;
}