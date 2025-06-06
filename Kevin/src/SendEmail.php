<?php
require '../vendor/autoload.php';
require_once './config/getCredentials.php';
require_once './config/getToken.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\OAuth;
use League\OAuth2\Client\Provider\Google;


class SendEmail{

    private $oauthUserEmail;
    private $oauthClientId;
    private $oauthClientSecret;
    private $oauthClientUri;
    private $oauthRefreshToken;
    private $oauthAccessToken;
    private $oauthAccessTokenExpires;
    private $mailer;


    public function __construct($credentials, $tokenData) {
        
        $this->oauthUserEmail = 'kevsneos@gmail.com';
        $this->oauthClientId = $credentials['client_id'];
        $this->oauthClientSecret = $credentials['client_secret'];
        $this->oauthClientUri = $credentials['redirect_uris'][0];
        $this->oauthAccessToken = $tokenData['access_token'] ?? null;
        $this->oauthRefreshToken = $tokenData['refresh_token'] ?? null;
        $this->oauthAccessTokenExpires = $tokenData['expires'] ?? null;

        $this->mailer = new PHPMailer(true);
        $this->setUpEmail();
    }
    public function setUpEmail(){
        
        $this->mailer->isSMTP();
        $this->mailer->Host = 'smtp.gmail.com';
        $this->mailer->Port = 587;
        $this->mailer->SMTPSecure = 'tls';
        $this->mailer->SMTPAuth = true;
        $this->mailer->AuthType = 'XOAUTH2';


        $provider = new Google([
            'clientId' => $this->oauthClientId,
            'clientSecret' => $this->oauthClientSecret,
            'redirectUri' => $this->oauthClientUri,
            'accessType' => 'offline',
            'scope' => ['https://mail.google.com/']
        ]);



        if (tokenInvalid($this->oauthAccessTokenExpires))
            $this->saveNewToken($provider);
        else
            echo "\nToken válido, continuando con el envío de correo...\n";

        $this->mailer->setOAuth(new OAuth([
                'provider' => $provider,
                'clientId' => $this->oauthClientId,
                'clientSecret' => $this->oauthClientSecret,
                'refreshToken' => $this->oauthRefreshToken,
                'userName' => $this->oauthUserEmail,
                'accessToken' => $this->oauthAccessToken
            ]));
            
        
    }
    public function sendCertificateEmail($toMail, $name, $subject, $htmlContent, $textContent) {
        try{
            $this->mailer->setFrom($this->oauthUserEmail,'Kevin David Quispe');
            $this->mailer->addAddress("quispekevin49@gmail.com", $name.' DESTINY');

            $this->mailer->isHTML(true);
            $this->mailer->Subject = $subject;
            $this->mailer->Body = $textContent;
            $this->mailer->AltBody = $textContent;



            //echo "\n\n\nMas detalles del debug:\n\n";
            $this->mailer->SMTPDebug = 3; // O 3 para más detalles 
            $this->mailer->Debugoutput = 'html'; // O 'echo' si estás en CLI


            $test = $this->buildOAuthString($this->oauthUserEmail, $this->oauthAccessToken);
            echo "\n\n\n------------Test de OAuth String:". base64_decode($test) ."\n\n";

            $this->mailer->send();
            return true;
        }catch(Exception $e){
            echo "\n\n\nError al enviar el correo: {$this->mailer->ErrorInfo}";
            echo "\nError: " . $e->getMessage();
            return false;
        }
    }
    private function buildOAuthString($email, $accessToken) {
        return base64_encode("user=$email\x01auth=Bearer $accessToken\x01\x01");
    }
    private function saveNewToken($provider){
        echo "\nToken inválido o no encontrado, se requiere autenticación.\n";

        echo "-------------Access Token Expirado:\n" . $this->oauthAccessToken . "\n";
        $accessTokenObject = $provider->getAccessToken('refresh_token', [
            'refresh_token' => $this->oauthRefreshToken
        ]);
        echo "\n------------Nuevo access_token obtenido desde refresh_token:\n" . $accessTokenObject->getToken() . "\n";
        $this->oauthAccessToken = $accessTokenObject->getToken(); 
        $this->oauthAccessTokenExpires = $accessTokenObject->getExpires();
        echo "\n------------Nuevo access_token EXPIRA:\n" . $this->oauthAccessTokenExpires . "\n";
        file_put_contents('./config/token.json', json_encode([
            'access_token' => $this->oauthAccessToken,
            'refresh_token' => $this->oauthRefreshToken,
            'expires' => $this->oauthAccessTokenExpires
        ]));
    }
}
