<?php
require_once '../vendor/autoload.php';


use PHPMailer\PHPMailer\PHPMailer;
class SendEmailHostinger{
    private $email;
    private $password;

    private $mailer;

    public function __construct() {
        $this->email = 'certificaciones@pipote.es';
        $this->password = 'Arboleda18_';
        $this->mailer = new PHPMailer(true);
        $this->setUpEmail();
    }
    public function setUpEmail(){
        $this->mailer->isSMTP();
        $this->mailer->Host = 'smtp.hostinger.com';
        $this->mailer->SMTPAuth = true;
        $this->mailer->Username = $this->email;
        $this->mailer->Password = $this->password;
        $this->mailer->Port = 465;
        $this->mailer->SMTPSecure = 'ssl';
        $this->mailer->CharSet = 'UTF-8';
        $this->mailer->Encoding = 'base64';
    }
    public function sendCertificateEmail($toMail, $name, $subject, $htmlContent, $textContent, ){
        try{
            $this->mailer->setFrom($this->email, 'Pipote Certificaciones');
            $this->mailer->addAddress($toMail, $name);


            $this->mailer->isHTML(true);
            $this->mailer->Subject = $subject;
            $this->mailer->Body = $htmlContent;
            $this->mailer->AltBody = $textContent;
            $this->mailer->send();
            $message = require './message.php';
            echo  str_replace('{{:email}}', $toMail, $message['EmailSend_OK']);;
            return true;
        }catch(Exception $e){
            echo "Error al enviar el correo: " . $e->getMessage();
            return false;
        }
    }

}