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
        $this->oauthRefreshToken = $tokenData['refresh_token'] ?? null;
        $this->oauthAccessToken = $tokenData['access_token'] ?? null;

        $this->mailer = new PHPMailer(true);
        $this->setUpEmail();
    }
    public function setUpEmail(){
        
        $this->mailer->isSMTP();
        $this->mailer->Host = 'smtp.gmail.com';
        $this->mailer->Port = 465;
        $this->mailer->SMTPSecure = 'ssl';
        $this->mailer->SMTPAuth = true;
        $this->mailer->AuthType = 'XOAUTH2';


        $provider = new Google([
            'clientId' => $this->oauthClientId,
            'clientSecret' => $this->oauthClientSecret,
            'redirectUri' => $this->oauthClientUri,
            'accessType' => 'offline',
            'scope' => ['https://mail.google.com/']
        ]);

        // try {
        //     $accessToken = $provider->getAccessToken('refresh_token', [
        //         'refresh_token' => $this->oauthRefreshToken
        //     ]);
        //     echo "Access token válido: " . $accessToken->getToken();
        // } catch (Exception $e) {
        //     echo "Error al refrescar el token: " . $e->getMessage();
        // }

        $this->mailer->setOAuth(new OAuth([
                'provider' => $provider,
                'clientId' => $this->oauthClientId,
                'clientSecret' => $this->oauthClientSecret,
                'refreshToken' => $this->oauthRefreshToken,
                'userName' => $this->oauthUserEmail
            ]));

    }
    public function sendCertificateEmail($toMail, $name, $subject, $htmlContent, $textContent) {
        try{
            $this->mailer->setFrom('kevsneos@gmail.com','Kevin David Quispe');
            $this->mailer->addAddress('kevsneos@gmail.com', 'Kevin Quispe DESTINY');

            $this->mailer->isHTML(true);
            $this->mailer->Subject = $subject;
            $this->mailer->Body = $htmlContent;
            $this->mailer->AltBody = $textContent;

            $this->mailer->send();
            return true;
        }catch(Exception $e){
            echo "Error al enviar el correo: {$this->mailer->ErrorInfo}";
            echo "\nError: " . $e->getMessage();
            return false;
        }
    }
}

// function sendEmail($toMail, $name, $subject, $htmlContent, $textContent) {
//     $credentials = getCredentials();
//     $clientId = $credentials['client_id'];
//     $clientSecret = $credentials['client_secret'];
//     $token = getToken();


//     $email = new PHPMailer(true);



//     $email->isSMTP();
//     $email->Host = 'smtp.gmail.com';
//     $email->Port = 587;
//     $email->SMTPSecure = 'tls';
//     $email->SMTPAuth = true;
//     $email->AuthType = 'XOAUTH2';

//     $email->oauthUserEmail = 'kevsneos@gmail.com';
//     $email->oauthClientId = $clientId;
//     $email->oauthClientSecret = $clientSecret;
//     $email->oauthRefreshToken = 'tu-refresh-token'; // si lo tienes, si no puedes probar con Access Token
//     $email->oauthAccessToken = $token;

    
//     $email->setFrom('kevsneos@gmail.com','Kevin David Quispe');
//     $email->addAddress($toMail, $name);

//     $email->isHTML(true);
//     $email->Subject = $subject;
//     $email->Body = $htmlContent;
//     $email->AltBody = $textContent;

//     $email->send();
  
// }