<?php
require_once __DIR__ . '/../entities/Certifica_Emitido.php';
class Certifica_EmitidoRepository{
    private $pdo;

    public function __construct($pdo){
        $this->pdo = $pdo;
    }

    public function findAll(){
        $stmt = $this->pdo->query("SELECT * FROM certificados");
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if ($result) {
            echo "Datos encontrados";
            $certificados = [];
            foreach ($result as $row) {
                $certificados[] = Certifica_Emitido::simpleCertificado($row['id'], $row['nombre'], $row['apellido'], $row['num_regis_certificado']);
            }
            return $certificados;
        }
        return [];
    }
    public function findByNumRegisCertificado($num_regis_certificado){
        $stmt = $this->pdo->prepare("SELECT * FROM certificados WHERE num_regis_certificado = :num_regis_certificado");
        $stmt->execute(['num_regis_certificado' => $num_regis_certificado]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return Certifica_Emitido::simpleCertificado($row['id'], $row['nombre'], $row['apellido'], $row['num_regis_certificado']);
        }
        return null;
    }

}