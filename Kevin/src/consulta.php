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

    try {

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
        $serviceConsultor->saveConsultor($inpConsul_name, $inpConsul_empresa, $inpConsul_email);
        
        
        //?--------------------OJO
        // $credentials = getCredentials();
        // $tokenData = getToken();
        
        // $mailClass = new SendEmail($credentials, $tokenData);

        
        // if($mailClass->sendCertificateEmail($inpConsul_email, $inpConsul_name, 
        //     'Tu certificado está disponible', 
        //     "<p>Hola <b>$inpConsul_name</b>, tu certificado de la empresa <b>$inpConsul_empresa</b> está disponible.</p>", 
        //     "Hola $inpConsul_name, tu certificado de la empresa $inpConsul_empresa está disponible con numero de registro $inpCert_numberReg")){
        //         echo "El correo ha sido enviado correctamente a $inpConsul_email";


        // }else
        //     echo "\n\n\nError al enviar el correo a $inpConsul_email\naaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa";

        //echo "\n\n--------------GUARDANDO CONSULTA EN HISTORIAL----------------\n\n";
      
      
      
        $repoHistorico = new Historial_ConsultasRepository($pdo);

        $serviceHistorico = new Historial_ConsultasService($repoHistorico, $repoConsultor, $repoCertificado);    
        $serviceHistorico->saveConsulta($inpConsul_email, $inpCert_numberReg);

    }catch (ValidationException $e) {
        echo "Error de validación: " . $e->getMessage();
    }catch (NotFoundException $e) {
        echo"Error de búsqueda: " . $e->getMessage();
    }catch (PDOException $e) {
        die("Error de conexión: " . $e->getMessage());
    }catch (Exception $e) {
        die("Error: " . $e->getMessage());
    }
}
 