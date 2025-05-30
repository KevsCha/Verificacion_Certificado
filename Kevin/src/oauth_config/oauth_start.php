<?php
require '../../vendor/autoload.php';
require '../config/getCredentials.php';

use League\OAuth2\Client\Provider\Google;
session_start();
// Cargar Credentials
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

$options = [
    'access_type' => 'offline',
    'prompt' => 'consent',
    'scope' => ['https://mail.google.com/']
];

$authUrl = $provider->getAuthorizationUrl($options);
$_SESSION['oauth2state'] = $provider->getState();

header("Location: $authUrl");
exit;