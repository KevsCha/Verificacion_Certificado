<?php
require_once '../vendor/autoload.php';

use League\OAuth2\Client\Provider\Google;
echo "Introducir code para generado de la url\n";

$code = urldecode(trim(fgets(STDIN)));
$provider = new Google(
    [
        'clientId' => "4321921381-b63topoi7bq0fnnnrlruih0pmaki7uht.apps.googleusercontent.com",
        'clientSecret' => "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",
        'redirectUri' => "http://localhost/oauth2callback",
        'accessType' => 'offline',
        'scope' => ['https://mail.google.com/']
    ]);
    $token = $provider->getAccessToken('authorization_code', ['code' => $code]);

echo "Access Token: " . $token->getToken() . "\n";
echo "Refresh Token: " . $token->getRefreshToken() . "\n";

$accessToken = $token->getToken();

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://www.googleapis.com/oauth2/v1/tokeninfo?access_token=' . $accessToken);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
curl_close($ch);

echo "<pre>";
print_r(json_decode($response, true));
echo "</pre>";