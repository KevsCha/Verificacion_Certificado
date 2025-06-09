<?php
require_once __DIR__ . '/../entities/Certifica_Emitido.php';
class Certifica_EmitidoRepository{
    private $pdo;

    public function __construct($pdo){
        $this->pdo = $pdo;
    }

    public function findAll(){
        $stmt = $this->pdo->query("SELECT * FROM certificados_emitidos");
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if ($result) {
            $certificados = [];
            foreach ($result as $row) {
                $certificados[] = Certifica_Emitido::simpleCertificado($row['id'], $row['nombre'], $row['apellido'], $row['num_regis_certificado']);
            }
            return $certificados;
        }
        return [];
    }
    public function findByNumRegisCertificado($num_regis_certificado){
        $stmt = $this->pdo->prepare("SELECT * FROM certificados_emitidos WHERE num_regis_certificado = :num_regis_certificado");
        $stmt->execute(['num_regis_certificado' => $num_regis_certificado]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            throw new NotFoundException("Certificado con número de registro $num_regis_certificado no encontrado");
        }
        return new Certifica_Emitido($row['id'], $row['nombre'], $row['apellido'], $row['num_regis_certificado'], $row['fecha_emision'], $row['fecha_validez']);
    }
    public function findIdByNumRegisCertificado($num_regis_certificado){
        $stmt = $this->pdo->prepare("SELECT id FROM certificados_emitidos WHERE num_regis_certificado = :num_regis_certificado");
        $stmt->execute(['num_regis_certificado' => $num_regis_certificado]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$row) {
            throw new NotFoundException("Certificado con número de registro $num_regis_certificado no encontrado");
        }
        return $row['id'];
    }
    public function findById($id):Certifica_Emitido{
        $stmt = $this->pdo->prepare('SeLECT * FROM certificados_emitidos WHERE id = :id');
        $stmt->execute(['id'=> $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            throw new NotFoundException("Certificado con ID $id no encontrado");
        }
        return new Certifica_Emitido($row['id'], $row['nombre'], $row['apellido'], $row['num_regis_certificado'], $row['fecha_emision'], $row['fecha_validez']);
    }

}