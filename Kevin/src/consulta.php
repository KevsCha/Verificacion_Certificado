<?php
require_once("../src/connectDB.php");
require_once("../src/repository/ConsultorRepository.php");
require_once("../src/repository/Certifica_EmitidoRepository.php");
require_once("../src/services/ConsultorService.php");
require_once("../src/services/Certifica_EmitidoService.php");


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


    // Aquí puedes realizar la lógica de búsqueda del consultor en la base de datos
    if($serviceConsultor->validationData($inpConsul_name, $inpConsul_email, $inpConsul_empresa)){
        echo "<br>" . "entro al IF del CONSULTOR";
    }
    if ($serviceCertificado->validationData($inpCert_name, $inpCert_numberReg)){
        echo "<br>" . "entro al IF del CERTIFICADO";

    }

    // Aquí puedes realizar la lógica de búsqueda  del certificado en la base de datos
    $entityCertificado = $repoCertificado->findByNumRegisCertificado($inpCert_numberReg);
}
