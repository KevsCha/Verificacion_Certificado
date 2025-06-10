<?php
require_once __DIR__ . '/../exceptions/ValidationException.php';
require_once __DIR__ . '/../exceptions/ExpiredException.php';
class Certifica_EmitidoService{
    private $repository;
    public function __construct($repository){
        $this->repository = $repository;
    }
    public function validationData($nameForm, $num_certificado){
        $certificadoData = $this->repository->findByNumRegisCertificado($num_certificado);
        $nameForm = preg_split('/\s+/', $this->removeAccents(strtolower($nameForm)));

        $nameDDBB = $this->removeAccents(strtolower($certificadoData->getName()));
        $last_nameDDBB = $this->removeAccents(strtolower($certificadoData->getLastName()));
        

        if($nameForm[0] != $nameDDBB || $nameForm[1] != $last_nameDDBB){
            throw new ValidationException("Los datos del certificado no coinciden. Por favor, verifique el nombre, apellido y número de certificado.");
        }
        if ($this->validateDate_Expired($certificadoData) === false){
            throw new ExpiredException("El certificado ha caducado. Por favor, verifique la fecha de validez del certificado.");
        }
        return true;
    }
    private function validateDate_Expired($certificadoData){
        if (date('Y-m-d') > $certificadoData->getFechaValidez()){
            return false;
        }
        return true;
    }
    private function removeAccents($str){
        $str = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
        $str = preg_replace('/[^A-Za-z0-9 ]/', '', $str);
        return $str;
    }
}