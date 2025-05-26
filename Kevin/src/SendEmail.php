<?php
require '../vendor/autoload.php';
require './config/getCredentials.php';
require './config/getToken.php';

use PHPMailer\PHPMailer\PHPMailer;

/**
 * @property string $oauthUserEmail
 * @property string $oauthClientId
 * @property string $oauthClientSecret
 * @property string $oauthRefreshToken
 * @property string $oauthAccessToken
 */

class SendEmail{
    private $mailer;


    public function __construct() {
        $this->mailer = new PHPMailer(true);

    }
}
function sendEmail($toMail, $name, $subject, $htmlContent, $textContent) {
    $credentials = getCredentials();
    $clientId = $credentials['client_id'];
    $clientSecret = $credentials['client_secret'];
    $token = getToken();


    $email = new PHPMailer(true);



    $email->isSMTP();
    $email->Host = 'smtp.gmail.com';
    $email->Port = 587;
    $email->SMTPSecure = 'tls';
    $email->SMTPAuth = true;

    $email->AuthType = 'XOAUTH2';
    $email->oauthUserEmail = 'kevsneos@gmail.com';
    $email->oauthClientId = $clientId;
    $email->oauthClientSecret = $clientSecret;
    $email->oauthRefreshToken = 'tu-refresh-token'; // si lo tienes, si no puedes probar con Access Token
    $email->oauthAccessToken = $token;

    $email->setFrom('kevsneos@gmail.com','Kevin David Quispe');
    $email->addAddress($toMail, $name);

    $email->isHTML(true);
    $email->Subject = $subject;
    $email->Body = $htmlContent;
    $email->AltBody = $textContent;

    $email->send();
  
}