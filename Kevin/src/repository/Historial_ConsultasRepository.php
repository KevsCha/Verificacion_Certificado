<?php
require_once __DIR__.'/../entities/Historial_Consultas.php';
class Historial_ConsultasRepository{
    private $pdo;

    public function __construct($pdo){
        $this->pdo = $pdo;
    }
    public function save($historico){
        //TODO: Cambiar argumentos a un objeto Historial_Consultas
        $stmt = $this->pdo->prepare("INSERT INTO historial_consultas 
                                        (id_consultor, id_certificado, fecha_consulta, resultado) 
                                        VALUES (:consultor_id, :certificado_id, NOW(), :resultado_consulta)");
        $stmt->execute([
            'consultor_id' => $historico->getConsultorId(),
            'certificado_id' => $historico->getCertificadoId(),
            'resultado_consulta' => $historico->getResultado_Consulta()
        ]);
    }
}