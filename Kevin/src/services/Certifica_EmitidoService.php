<?php
class Certifica_EmitidoService{
    private $repository;
    public function __construct($repository){
        $this->repository = $repository;
    }
    //! Colocar Exception para el caso de que no se encuentre el certificado
    public function validationData($name, $num_certificado){
        $certificadoData = $this->repository->findByNumRegisCertificado($num_certificado);
        $name = preg_split('/\s+/', $this->removeAccents(strtolower($name)));

        if($name[0] == $this->removeAccents(strtolower($certificadoData->getName())) && $name[1]== $this->removeAccents(strtolower($certificadoData->getLastName())) && $num_certificado == $certificadoData->getNumCertificado()){
            return true;
        }
        return false;
    }
    private function removeAccents($str){
        $str = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
        $str = preg_replace('/[^A-Za-z0-9 ]/', '', $str);
        return $str;
    }
}