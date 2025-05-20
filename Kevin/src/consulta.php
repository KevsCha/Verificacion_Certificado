<?php
require_once("../src/connectDB.php");

// Verifica si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtiene el valor del campo de entrada
    $inputValue = $_POST['name_consultor'];

    echo $inputValue;
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
