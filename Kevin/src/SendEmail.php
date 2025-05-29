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
    private $mailer;


    public function __construct($credentials, $tokenData) {
        
        $this->oauthUserEmail = 'kevsneos@gmail.com';
        $this->oauthClientId = $credentials['client_id'];
        $this->oauthClientSecret = $credentials['client_secret'];
        $this->oauthClientUri = $credentials['redirect_uris'][0];
        $this->oauthAccessToken = $tokenData['access_token'] ?? null;
        $this->oauthRefreshToken = $tokenData['refresh_token'] ?? null;

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

        $accessTokenObject = $provider->getAccessToken('refresh_token', [
            'refresh_token' => $this->oauthRefreshToken
        ]);

        $this->oauthAccessToken = $accessTokenObject->getToken(); // lo guardas para usarlo más abajo (como en buildOAuthString)
        echo "------------Nuevo access_token obtenido desde refresh_token: " . $this->oauthAccessToken . "\n";

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
            $this->mailer->addAddress($this->oauthUserEmail, 'Kevin Quispe DESTINY');

            $this->mailer->isHTML(true);
            $this->mailer->Subject = $subject;
            $this->mailer->Body = $htmlContent;
            $this->mailer->AltBody = $textContent;



            echo "\n\n\nMas detalles del debug:\n\n";
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
    public function buildOAuthString($email, $accessToken) {
        return base64_encode("user=$email\x01auth=Bearer $accessToken\x01\x01");
    }
}
