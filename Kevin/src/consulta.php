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
require_once("./services/EmailResponseHandler.php");

require_once("./SendEmail.php");
require_once("./SendEmailHostinger.php");
require_once("./config/getToken.php");

require ('../vendor/autoload.php');
use PHPMailer\PHPMailer\Exception;

$repoCertificado = null;
$repoConsultor = null;
$emailHandler = null;

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
        $repoConsultor = new ConsultorRepository($pdo);
        $emailHandler = new EmailResponseHandler($messageFile, $pdo, $repoConsultor, $repoCertificado);
        
        $serviceCertificado = new Certifica_EmitidoService($repoCertificado);
        if ($serviceCertificado->validationData($inpCert_name, $inpCert_numberReg)){
            $stateMessage = $messageFile['State_OK'];
            $messageContent = str_replace(["{{:name}}","{{:num_regis}}"], [$inpCert_name, $inpCert_numberReg], $messageFile['EmailHtml_OK']);
            $textContent = str_replace(["{{:name}}","{{:num_regis}}"], [$inpCert_name, $inpCert_numberReg], $messageFile['EmailTxt_OK']);
            $htmlContent = str_replace("{{mensaje}}", $messageContent, file_get_contents('./view/plantillaEmail.html'));
        }
        
        $serviceConsultor = new ConsultorService($repoConsultor);
        $serviceConsultor->saveConsultor($inpConsul_name, $inpConsul_empresa, $inpConsul_email);
        
        // Proceder a enviar el correo
        $emailHandler->sendFound($inpCert_name, $inpCert_numberReg, $inpConsul_email, $inpConsul_name);
    }catch (ExpiredException $e) {
        $emailHandler->sendExpired($inpCert_name, $inpCert_numberReg, $inpConsul_email, $inpConsul_name);
    }catch (NotFoundException $e) {
        $emailHandler->sendNotFound($inpCert_name, $inpCert_numberReg, $inpConsul_email, $inpConsul_name);
    }catch (ValidationException $e) {
        echo $e->getMessage();
    }catch (PDOException $e) {
        die("Error de conexión: " . $e->getMessage());
    }catch (Exception $e) {
        die("Error: " . $e->getMessage());
    }
}