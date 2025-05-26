<?php
function getCredentials() {
    // Cargar las credenciales desde el archivo JSON
    $credentialsPath = __DIR__ . '/credentials.json';
    
    if (!file_exists($credentialsPath)) {
        throw new Exception('El archivo de credenciales no existe.');
    }
    
    $credentials = json_decode(file_get_contents($credentialsPath), true);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception('Error al decodificar el archivo de credenciales: ' . json_last_error_msg());
    }
    
    return $credentials['web'];
}