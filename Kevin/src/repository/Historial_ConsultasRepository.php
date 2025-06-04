<?php
require_once __DIR__.'/../entities/Historial_Consultas.php';
class Historial_ConsultasRepository{
    private $pdo;

    public function __construct($pdo){
        $this->pdo = $pdo;
    }
    public function save($idConsultor, $idCertificado, $resultado){
        $stmt = $this->pdo->prepare("INSERT INTO historial_consultas (id_consultor, id_certificado, fecha_consulta, resultado) VALUES (:consultor_id, :certificado_id, NOW(), :resultado_consulta)");
        $stmt->execute([
            'consultor_id' => $idConsultor,
            'certificado_id' => $idCertificado,
            'resultado_consulta' => $resultado
        ]);
    }
}