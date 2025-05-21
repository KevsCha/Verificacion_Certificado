<?php
require_once __DIR__ . '/../entities/Certifica_Emitido.php';
class Certifica_EmitidoRepository{
    private $pdo;

    public function __construct($pdo){
        $this->pdo = $pdo;
    }

    public function getCertificadoEmitido($num_certificado){
        $stmt = $this->pdo->prepare("SELECT * FROM certificados WHERE id = :num_certificado");
        $stmt->execute(['num_certificado' => $num_certificado]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            echo "Datos encontrados";
            
            return Certifica_Emitido::simpleCertificado($row['id'], $row['nombre'], $row['apellido'], $row['num_regis_certificado']);
        }
        return null;
    }
}