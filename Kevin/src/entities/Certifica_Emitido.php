<?php
require_once 'Persona.php';
class Certifica_Emitido extends Persona{
    private $num_certificado;
    private $fecha_emision;
    private $fecha_vencimiento;

    // Constructors
    public function __construct($id, $name, $last_name, $num_certificado, $fecha_emision = null, $fecha_vencimiento = null){
        parent::__construct($id, $name, $last_name);
        $this->num_certificado = $num_certificado;
        $this->fecha_emision = $fecha_emision;
        $this->fecha_vencimiento = $fecha_vencimiento;
    }
    public static function simpleCertificado($id, $name, $last_name, $num_certificado){
        return new self($id, $name, $last_name, $num_certificado);
    }
    public static function fullCertificado($id, $name, $last_name, $num_certificado, $fecha_emision, $fecha_vencimiento){
        return new self($id, $name, $last_name, $num_certificado, $fecha_emision, $fecha_vencimiento);
    }


    // Getters and Setters
    public function getNumCertificado(){
        return $this->num_certificado;
    }
    public function setNumCertificado($num_certificado){
        $this->num_certificado = $num_certificado;
    }

    public function getFechaEmision(){
        return $this->fecha_emision;
    }
    public function setFechaEmision($fecha_emision){
        $this->fecha_emision = $fecha_emision;
    }

    public function getFechaVencimiento(){
        return $this->fecha_vencimiento;
    }
    public function setFechaVencimiento($fecha_vencimiento){
        $this->fecha_vencimiento = $fecha_vencimiento;
    }
    // Methods
    public function __toString(){
        return parent::__toString() . ", Num Certificado: " . $this->num_certificado . ", Fecha Emision: " . $this->fecha_emision . ", Fecha Vencimiento: " . $this->fecha_vencimiento;
    }
    public function showDate(){
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'last_name' => $this->getLastName(),
            'num_certificado' => $this->num_certificado,
            'fecha_emision' => $this->fecha_emision,
            'fecha_vencimiento' => $this->fecha_vencimiento
        ];
    }
}