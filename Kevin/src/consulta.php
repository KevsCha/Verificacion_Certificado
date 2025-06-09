<?php
require_once("../src/config/connectDB.php");
require_once("./config/getCredentials.php");

require_once("../src/repository/ConsultorRepository.php");
require_once("../src/repository/Certifica_EmitidoRepository.php");
require_once("../src/repository/Historial_ConsultasRepository.php");
require_once("../src/services/ConsultorService.php");
require_once("../src/services/Certifica_EmitidoService.php");
require_once("../src/services/Historial_ConsultasService.php");
require_once("./exceptions/ValidationException.php");
require_once("./exceptions/NotFoundException.php");
require_once("./exceptions/ExpiredException.php");

require_once("./SendEmail.php");
require_once("./SendEmailHostinger.php");
require_once("./config/getToken.php");

require ('../vendor/autoload.php');
use PHPMailer\PHPMailer\Exception;

// Verifica si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // DATOS DEL FORMULARIO
    $inpConsul_name = $_POST['name_consultor'];
    $inpConsul_email = $_POST['email'];
    $inpConsul_empresa = $_POST['empresa'];

    $inpCert_name = $_POST['name'];
    $inpCert_numberReg = $_POST['certificado'];
    

    // Validación de los datos del formulario
    try {
        $messageFile = require './message.php';
        
        // Instanciar las clases de repositorio y servicio
        $repoCertificado = new Certifica_EmitidoRepository($pdo);
        $serviceCertificado = new Certifica_EmitidoService($repoCertificado);
        if ($serviceCertificado->validationData($inpCert_name, $inpCert_numberReg)){
            $stateMessage = $messageFile['State_OK'];
            $messageContent = str_replace(["{{:name}}","{{:num_regis}}"], [$inpCert_name, $inpCert_numberReg], $messageFile['EmailHtml_OK']);
            $textContent = str_replace(["{{:name}}","{{:num_regis}}"], [$inpCert_name, $inpCert_numberReg], $messageFile['EmailTxt_OK']);
            $htmlContent = str_replace("{{mensaje}}", $messageContent, file_get_contents('./view/plantillaEmail.html'));
        }
        
        $repoConsultor = new ConsultorRepository($pdo);
        $serviceConsultor = new ConsultorService($repoConsultor);
        //$serviceConsultor->saveConsultor($inpConsul_name, $inpConsul_empresa, $inpConsul_email);
        
        // Proceder a enviar el correo
        $mailClass = new SendEmailHostinger();

        $mailClass->sendCertificateEmail(
            $inpConsul_email,
            $inpConsul_name,
            $stateMessage,
            $htmlContent,
            $textContent    
        );
        
        // Guardar la consulta en el historial
        $repoHistorico = new Historial_ConsultasRepository($pdo);

        $serviceHistorico = new Historial_ConsultasService($repoHistorico, $repoConsultor, $repoCertificado);    
        $serviceHistorico->saveConsulta($inpConsul_email, $inpCert_numberReg);
    }catch (ExpiredException $e) {
        
        $stateMessage = $messageFile['State_Lapsed'];
        $messageContent = str_replace("{{:name}}", $inpCert_name, $messageFile['EmailHtml_Lapsed']);
        $textContent = str_replace("{{:name}}", $inpCert_name, $messageFile['EmailTxt_Lapsed']);
        $htmlContent = str_replace("{{mensaje}}", $messageContent, file_get_contents('./view/plantillaEmail.html'));
        $mailClass = new SendEmailHostinger();
        
        $mailClass->sendCertificateEmail(
            $inpConsul_email, 
            $inpConsul_name, 
            $stateMessage, 
            $htmlContent, 
            $textContent);
    }catch (NotFoundException $e) {
        
        $mailClass = new SendEmailHostinger();

        $stateMessage = $messageFile['State_KO'];
        $messageContent = str_replace(["{{:name}}","{{:num_regis}}"], [$inpCert_name, $inpCert_numberReg], $messageFile['EmailHtml_KO']); 
        $textContent =  str_replace(["{{:name}}","{{:num_regis}}"], [$inpCert_name, $inpCert_numberReg], $messageFile['EmailTxt_KO']); 
        $htmlContent = str_replace("{{mensaje}}", $messageContent, file_get_contents('./view/plantillaEmail.html'));

        $mailClass->sendCertificateEmail(
            $inpConsul_email, 
            $inpConsul_name, 
            $stateMessage, 
            $htmlContent,
            $textContent
        );
    }catch (ValidationException $e) {
        echo "Error de validación: " . $e->getMessage();
    }catch (PDOException $e) {
        die("Error de conexión: " . $e->getMessage());
    }catch (Exception $e) {
        die("Error: " . $e->getMessage());
    }
}
 