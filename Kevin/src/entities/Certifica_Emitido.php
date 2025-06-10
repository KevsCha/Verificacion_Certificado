<?php
require_once 'Persona.php';
class Certifica_Emitido extends Persona{
    private $num_certificado;
    private $fecha_emision;
    private $fecha_validez;

    // Constructors
    public function __construct($id, $name, $last_name, $num_certificado, $fecha_emision = null, $fecha_validez = null){
        parent::__construct($id, $name, $last_name);
        $this->num_certificado = $num_certificado;
        $this->fecha_emision = $fecha_emision;
        $this->fecha_validez = $fecha_validez;
    }
    public static function simpleCertificado($id, $name, $last_name, $num_certificado){
        return new self($id, $name, $last_name, $num_certificado);
    }
    // public static function simpleCertificadoWithDates($id, $name, $last_name, $num_certificado, $fecha_validez){
    //     return new self($id, $name, $last_name, $num_certificado, , $fecha_validez);
    // }
    public static function fullCertificado($id, $name, $last_name, $num_certificado, $fecha_emision, $fecha_validez){
        return new self($id, $name, $last_name, $num_certificado, $fecha_emision, $fecha_validez);
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

    public function getFechaValidez(){
        return $this->fecha_validez;
    }
    public function setFechaValidez($fecha_validez){
        $this->fecha_validez = $fecha_validez;
    }
    // Methods
    public function __toString(){
        return parent::__toString() . ", Num Certificado: " . $this->num_certificado . ", Fecha Emision: " . $this->fecha_emision . ", Fecha validez: " . $this->fecha_validez;
    }
    public function showDate(){
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'last_name' => $this->getLastName(),
            'num_certificado' => $this->num_certificado,
            'fecha_emision' => $this->fecha_emision,
            'fecha_validez' => $this->fecha_validez
        ];
    }
}