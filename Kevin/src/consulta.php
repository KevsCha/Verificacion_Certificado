<?php
require_once("../src/connectDB.php");
require_once("../src/repository/Certifica_EmitidoRepository.php");


// Verifica si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtiene el valor del campo de entrada
    $inpConsul_name = $_POST['name_consultor'];
    $inpConsul_email = $_POST['email'];
    $inpConsul_empresa = $_POST['empresa'];

    $inpCert_name = $_POST['name'];
    $inpCert_numberReg = $_POST['certificado'];
    echo "<br>" . $inpConsul_name . " " . $inpConsul_email . " " . $inpConsul_empresa;
    echo "<br>" . "<h1>Certificado</h1>";
    echo $inpCert_name . " " . $inpCert_numberReg;

    // Aquí puedes realizar la lógica de búsqueda en la base de datos
    $repoCertificado = new Certifica_EmitidoRepository($pdo);
    $entityCertificado = $repoCertificado->getCertificadoEmitido($inpCert_numberReg);
    echo "<br>";
    var_dump ($entityCertificado->showDate());

    // // Prepara la consulta SQL
    // $stmt = $pdo->prepare("SELECT * FROM dv_vc WHERE nombre LIKE :inputValue");
    // $stmt->execute(['inputValue' => '%' . $inputValue . '%']);

    // // Obtiene los resultados
    // $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // // Muestra los resultados
    // if ($results) {
    //     foreach ($results as $row) {
    //         echo "<p>" . htmlspecialchars($row['nombre']) . "</p>";
    //     }
    // } else {
    //     echo "<p>No se encontraron resultados.</p>";
    // }
}
