<?php
class Historial_Consultas{
    private $id;
    private $consultor_id;
    private $certificado_id;
    private $fecha_consulta;
    private $resultado_consulta;

    public function __construct($id, $consultor_id, $certificado_id, $fecha_consulta, $resultado_consulta){
        $this->id = $id;
        $this->consultor_id = $consultor_id;
        $this->certificado_id = $certificado_id;
        $this->fecha_consulta = $fecha_consulta;
        $this->resultado_consulta = $resultado_consulta;
    }
    public function getId():int{
        return $this->id;
    }
    public function setId($id){
        $this->id = $id;
    }
    public function getConsultorId():int{
        return $this->consultor_id;
    }
    public function setConsultorId($consultor_id){
        $this->consultor_id = $consultor_id;
    }
    public function getCertificadoId():int{
        return $this->certificado_id;
    }
    public function setCertificadoId($certificado_id){
        $this->certificado_id = $certificado_id;
    }
    public function getFecha_consulta(){
        return $this->fecha_consulta;
    }
    public function setFecha_consulta($fecha_consulta){
        $this->fecha_consulta = $fecha_consulta;
    }
    public function getResultado_consulta(){
        return $this->resultado_consulta;
    }
    public function setResultado_consulta($resultado_consulta){
        $this->resultado_consulta = $resultado_consulta;
    }
    public function __toString(){
        return "ID: " . $this->id . ", Consultor ID: " . $this->consultor_id . ", Certificado ID: " . $this->certificado_id . 
               ", Fecha Consulta: " . $this->fecha_consulta . ", Resultado Consulta: " . $this->resultado_consulta;
    }
}