<?php
require '../../vendor/autoload.php';
require '../config/getCredentials.php';

use League\OAuth2\Client\Provider\Google;

session_start();
// Cargar Credentials

if (!isset($_GET['state']) || $_GET['state'] !== $_SESSION['oauth2state']) {
    unset($_SESSION['oauth2state']);
    exit('State invalid, possible CSRF attack.');
}

$credentials = getCredentials();
$clientId = $credentials['client_id'];
$clientSecret = $credentials['client_secret'];
$redirectUri = $credentials['redirect_uris'][0];

$provider = new Google([
    'clientId' => $clientId,
    'clientSecret' => $clientSecret,
    'redirectUri' => $redirectUri,
    'accessType' => 'offline'
]);

if (isset($_GET['code'])){
    try{
        $token = $provider->getAccessToken('authorization_code', ['code' => $_GET['code']]);
        
        if (!$token->getRefreshToken()) {
            exit("No se recibió un refresh token. Asegúrate de autorizar completamente la app o borra el acceso desde tu cuenta de Google.");
        }

        //echo "Access Token: " . $token->getToken() . "\n";
        //echo "Refresh Token: " . $token->getRefreshToken() . "\n";
        //echo "Expires: " . date('Y-m-d H:i:s', $token->getExpires()) . "\n";

        $data = [
            'access_token' => $token->getToken(),
            'refresh_token' => $token->getRefreshToken(),
            'expires' => $token->getExpires()
        ];
        // Aquí puedes guardar el token en una base de datos o archivo
        // Por ejemplo, guardarlo en un archivo JSON
        file_put_contents('../config/token.json', json_encode($data, JSON_PRETTY_PRINT));
        echo "\nToken guardado correctamente en token.json\n";
    }catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
        exit("Error al obtener el token: " . $e->getMessage());
    }
}