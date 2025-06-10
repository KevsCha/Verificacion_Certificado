<?php

class EmailResponseHandler{
    private $mailClass;
    private $messageFile;
    private $plantillaPath;
    private $pdo;
    private $repoConsultor;
    private $repoCertificado;
    public function __construct($messageFile, $pdo, $repoConsultor, $repoCertificado, $plantillaPath = './view/plantillaEmail.html') {
        $this->mailClass = new SendEmailHostinger();
        $this->messageFile = $messageFile;
        $this->plantillaPath = $plantillaPath;
        $this->pdo = $pdo;
        $this->repoConsultor = $repoConsultor;
        $this->repoCertificado = $repoCertificado;
    }
    private function buildContent($htmlMessage, $textMessage) {
        $htmlContent = str_replace("{{mensaje}}", $htmlMessage, file_get_contents($this->plantillaPath));
        return [$htmlContent, $textMessage];
    }

    private function saveConsulta($email, $numberRegis) {
        $repoHistorico = new Historial_ConsultasRepository($this->pdo);
        $serviceHistorico = new Historial_ConsultasService($repoHistorico, $this->repoConsultor, $this->repoCertificado);    
        $serviceHistorico->saveConsulta($email, $numberRegis);
    }

    public function sendFound($name, $numberRegis, $consultorEmail, $consultorName) {
        $stateMessage = $this->messageFile['State_OK'];
        $htmlMessage = str_replace(["{{:name}}", "{{:num_regis}}"], [$name, $numberRegis], $this->messageFile['EmailHtml_OK']);
        $textMessage = str_replace(["{{:name}}", "{{:num_regis}}"], [$name, $numberRegis], $this->messageFile['EmailTxt_OK']);
        list($htmlContent, $textContent) = $this->buildContent($htmlMessage, $textMessage);

        $this->mailClass->sendCertificateEmail($consultorEmail, $consultorName, $stateMessage, $htmlContent, $textContent);
        //$this->saveConsulta($consultorEmail, $numberRegis);
    }

    public function sendExpired($name, $numberRegis, $consultorEmail, $consultorName) {
        $stateMessage = $this->messageFile['State_Lapsed'];
        $htmlMessage = str_replace("{{:name}}", $name, $this->messageFile['EmailHtml_Lapsed']);
        $textMessage = str_replace("{{:name}}", $name, $this->messageFile['EmailTxt_Lapsed']);
        list($htmlContent, $textContent) = $this->buildContent($htmlMessage, $textMessage);

        $this->mailClass->sendCertificateEmail($consultorEmail, $consultorName, $stateMessage, $htmlContent, $textContent);
        $this->saveConsulta($consultorEmail, $numberRegis);
    }

    public function sendNotFound($name, $numberRegis, $consultorEmail, $consultorName) {
        $stateMessage = $this->messageFile['State_KO'];
        $htmlMessage = str_replace(["{{:name}}", "{{:num_regis}}"], [$name, $numberRegis], $this->messageFile['EmailHtml_KO']);
        $textMessage = str_replace(["{{:name}}", "{{:num_regis}}"], [$name, $numberRegis], $this->messageFile['EmailTxt_KO']);
        list($htmlContent, $textContent) = $this->buildContent($htmlMessage, $textMessage);

        $this->mailClass->sendCertificateEmail($consultorEmail, $consultorName, $stateMessage, $htmlContent, $textContent);
        $this->saveConsulta($consultorEmail, $numberRegis);
    }
}