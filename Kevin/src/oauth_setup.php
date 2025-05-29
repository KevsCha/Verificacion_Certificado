<?php
require '../vendor/autoload.php';
require './config/getCredentials.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use League\OAuth2\Client\Provider\Google;


// Cargar Credentials
$credentials = getCredentials();

$clientId = $credentials['client_id'];
$clientSecret = $credentials['client_secret'];
$clientUri = $credentials['redirect_uris'][0];

// Usar Google provider
$provider = new Google(
    [
        'clientId' => $clientId,
        'clientSecret' => $clientSecret,
        'redirectUri' => $clientUri,
        'accessType' => 'offline'
    ]    
);

$options = [
    'access_type' => 'offline',
    'prompt' => 'consent',
    'scope' => ['https://mail.google.com/']
];

$authUrl = $provider->getAuthorizationUrl($options);

echo "Visita esta URL y pega el código aquí:\n$authUrl\n";
echo "Código: ";
$code = urldecode(trim(fgets(STDIN)));

//$token = $provider->getAccessToken('authorization_code', ['code' => $code]);

try {
    $token = $provider->getAccessToken('authorization_code', ['code' => $code]);
    
    //$owner = $provider->getResourceOwner($token);
    //echo "-----------------Email autorizado por el token: " . $owner->toArray()['email'] . "\n";
    if (!$token->getRefreshToken()) {
        echo "No se recibió un refresh token. Asegúrate de autorizar completamente la app o borra el acceso desde tu cuenta de Google.\n";
    }
} catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
    exit("----------00000------Error al obtener el token: " . $e->getMessage());
}


echo "\nAccess Token: " . $token->getToken() . "\n";
echo "Refresh Token: " . $token->getRefreshToken() . "\n";
echo "Expires: " . date('Y-m-d H:i:s', $token->getExpires()) . "\n";

$data = [
    'access_token' => $token->getToken(),
    'refresh_token' => $token->getRefreshToken(),
    'expires' => $token->getExpires(),
];

file_put_contents(__DIR__ . '/config/token.json', json_encode($data, JSON_PRETTY_PRINT));

echo "\nToken guardado correctamente en token.json\n";