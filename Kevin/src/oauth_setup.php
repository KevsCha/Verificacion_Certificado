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
    ]    
);

$authUrl = $provider->getAuthorizationUrl();

echo "Visita esta URL y pega el código aquí:\n$authUrl\n";
echo "Código: ";
$code = trim(fgets(STDIN));
$code = urldecode($code);

$token = $provider->getAccessToken('authorization_code', ['code' => $code]);

echo "\nAccess Token: " . $token->getToken() . "\n";
echo "Refresh Token: " . $token->getRefreshToken() . "\n";
echo "Expires: " . date('Y-m-d H:i:s', $token->getExpires()) . "\n";