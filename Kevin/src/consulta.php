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

require_once("./SendEmail.php");
require_once("./SendEmailHostinger.php");
require_once("./config/getToken.php");

require ('../vendor/autoload.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Verifica si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // DATOS DEL FORMULARIO
    $inpConsul_name = $_POST['name_consultor'];
    $inpConsul_email = $_POST['email'];
    $inpConsul_empresa = $_POST['empresa'];

    $inpCert_name = $_POST['name'];
    $inpCert_numberReg = $_POST['certificado'];
    
    $credentials = getCredentials();
    $tokenData = getToken();
    $htmlContent = "";
        
    try {
        $messageFile = require './message.php';
        
        // INSTANCIAR REPOSITORIOS
        $repoCertificado = new Certifica_EmitidoRepository($pdo);
        //INSTANCIAR SERVICIOS
        $serviceCertificado = new Certifica_EmitidoService($repoCertificado);
        // VALIDAR DATOS
        //$serviceConsultor->validationData($inpConsul_name, $inpConsul_email, $inpConsul_empresa);
        $serviceCertificado->validationData($inpCert_name, $inpCert_numberReg);
        
        echo "Validación exitosa: certificado encontrado.\n";

        $repoConsultor = new ConsultorRepository($pdo);
        $serviceConsultor = new ConsultorService($repoConsultor);
        //$serviceConsultor->saveConsultor($inpConsul_name, $inpConsul_empresa, $inpConsul_email);
        
        // $mailClass = new SendEmail($credentials, $tokenData);
        $mailClass = new SendEmailHostinger();

        if ($mailClass->sendCertificateEmail(
            'k-e-v-i-n_13_@hotmail.com',
            $inpConsul_name,
            "Estado del Certitificado, tu certificado está activo y válido",
            "Confirmamos que <strong>$inpCert_name</strong> posee un certificado con número <strong>$inpCert_numberReg</strong> activo y válido otorgado por Vaelsys.<br><br>Gracias por su consulta.",
            "Confirmamos que $inpCert_name posee un certificado con número $inpCert_numberReg activo y válido otorgado por Vaelsys. Gracias por su consulta"
        ))
            echo "El correo ha sido enviado correctamente a $inpConsul_email";
        // if($mailClass->sendCertificateEmail(
        //          $inpConsul_email, 
        //          $inpConsul_name, 
        //          'Tu certificado está disponible', 
        //          "<p>Hola <b>$inpConsul_name</b>, tu certificado de la empresa <b>$inpConsul_empresa</b> está disponible.</p>", 
        //          "Hola $inpConsul_name, tu certificado de la empresa $inpConsul_empresa está disponible con numero de registro $inpCert_numberReg")){
        //      echo "El correo ha sido enviado correctamente a $inpConsul_email";
        // }else
        //      echo "\n\n\nError al enviar el correo a $inpConsul_email\naaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa";

        echo "\n\n--------------GUARDANDO CONSULTA EN HISTORIAL----------------\n\n";
      
      
      
        $repoHistorico = new Historial_ConsultasRepository($pdo);

        $serviceHistorico = new Historial_ConsultasService($repoHistorico, $repoConsultor, $repoCertificado);    
        $serviceHistorico->saveConsulta($inpConsul_email, $inpCert_numberReg);

    }catch (ValidationException $e) {
        //$mailClass = new SendEmail($credentials, $tokenData);
        $mailClass = new SendEmailHostinger();
        echo "Error de validación: " . $e->getMessage();
        
        $stateMessage = "Estado del Certitificado, error en la validación de tu certificado";
        $messageContent = "<p>Hola <b>$inpConsul_name</b>, ha ocurrido un error al validar tu certificado. Por favor, verifica los datos ingresados.</p>";
        $textContent = "Hola $inpConsul_name, ha ocurrido un error al validar tu certificado. Por favor, verifica los datos ingresados.";
        $htmlContent = str_replace("{{mensaje}}", $messageContent, file_get_contents('./view/plantillaEmail.html'));
        
        $mailClass->sendCertificateEmail(
            'k-e-v-i-n_13_@hotmail.com', 
            $inpConsul_name, 
            $stateMessage, 
            $htmlContent, 
            $textContent);
    }catch (NotFoundException $e) {
  
        //$mailClass = new SendEmail($credentials, $tokenData);
        
        $mailClass = new SendEmailHostinger();
        $stateMessage = $messageFile['State_KO'];
        //echo"Error de búsqueda: " . $e->getMessage();
        //$stateMessage = "Estado del Certitificado, no se encontró el certificado";
        $messageContent = str_replace(["{{:name}}","{{:num_regis}}"], [$inpCert_name, $inpCert_numberReg], $messageFile['EmailHtml_KO']); 
        //$messageContent = "No se ha encontrado ningún certificado activo en nuestros registros a nombre de <strong>$inpCert_name</strong> y número de certificado <strong>$inpCert_numberReg</strong>.<br><br>Por favor, verifica que la información ingresada sea correcta.<br><br>Gracias por su consulta.";
        $textContent =  str_replace(["{{:name}}","{{:num_regis}}"], [$inpCert_name, $inpCert_numberReg], $messageFile['EmailTxt_KO']); 
        //$textContent = "No se ha encontrado ningún certificado activo en nuestros registros a nombre de $inpCert_name y número de certificado $inpCert_numberReg. Por favor, verifica que la información ingresada sea correcta. Gracias por su consulta.";
        $htmlContent = str_replace("{{mensaje}}", $messageContent, file_get_contents('./view/plantillaEmail.html'));

        $mailClass->sendCertificateEmail(
            'k-e-v-i-n_13_@hotmail.com', 
            $inpConsul_name, 
            $stateMessage, 
            $htmlContent,
            $textContent
        );
    }catch (PDOException $e) {
        die("Error de conexión: " . $e->getMessage());
    }catch (Exception $e) {
        die("Error: " . $e->getMessage());
    }
}
 