<?php
require_once("../src/config/connectDB.php");
require_once("./config/getCredentials.php");

require_once("../src/repository/ConsultorRepository.php");
require_once("../src/repository/Certifica_EmitidoRepository.php");
require_once("../src/services/ConsultorService.php");
require_once("../src/services/Certifica_EmitidoService.php");
require_once("./send_email.php");

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

    // INSTANCIAR REPOSITORIOS
    $repoConsultor = new ConsultorRepository($pdo);
    $repoCertificado = new Certifica_EmitidoRepository($pdo);

    //INSTANCIAR SERVICIOS
    $serviceConsultor = new ConsultorService($repoConsultor);
    $serviceCertificado = new Certifica_EmitidoService($repoCertificado);


    // Aquí realizar la lógica de la búsqueda del consultor y certificado en la base de datos
    if($serviceConsultor->validationData($inpConsul_name, $inpConsul_email, $inpConsul_empresa)){
        if ($serviceCertificado->validationData($inpCert_name, $inpCert_numberReg)){
            echo "Hemos recibido su solicitud con éxito, pronto recibirá un correo en la dirección facilitada con los resultados de su consulta<br>";
        }
        else {
            echo "Error: No se encontró el certificado";
        }
    }else{
        echo "Error: No se encontró el consultor";
    }
    
    
    
    
    try{
        sendEmail(
            $inpConsul_email, 
            $inpConsul_name, 
            'Tu certificado está disponible', 
            "<p>Hola <b>$inpConsul_name</b>, tu certificado de la empresa <b>$inpConsul_empresa</b> está disponible.</p>", 
            "Hola $inpCert_name, tu certificado de la empresa $inpConsul_empresa está disponible con numero de registro $inpCert_numberReg"
        );
        echo "El correo ha sido enviado correctamente a $inpConsul_email";
    }
    catch(Exception $e){
        echo "Error al enviar el correo: {$email->getMessage()}";
    };
}
