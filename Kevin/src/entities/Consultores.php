<?php
require_once 'Persona.php';
class Consultores extends Persona{
   private $empresa;
   private $email;

   public function __construct($id, $name, $last_name, $empresa, $email){
       parent::__construct($id, $name, $last_name);
       $this->empresa = $empresa;
       $this->email = $email;
   }
   public function getEmpresa(){
       return $this->empresa;
   }
    public function setEmpresa($empresa){
         $this->empresa = $empresa;
    }
    public function getEmail(){
        return $this->email;
    }
    public function setEmail($email){
        $this->email = $email;
    }
    public function __toString(){
        return parent::__toString() . ", Empresa: " . $this->empresa . ", Email: " . $this->email;
    }
    public function getConsultor(){
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'last_name' => $this->getLastName(),
            'empresa' => $this->empresa,
            'email' => $this->email
        ];
    }

}