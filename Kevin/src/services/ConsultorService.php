<?php
class ConsultorService
{
    private $repository;
    public function __construct($repository){
        $this->repository = $repository;
    }
    //! Colocar Exception para el caso de que no se encuentre el consultor
    public function validationData($name, $email, $empresa){
        $consultorData = $this->repository->findByEmail($email);
        $name = preg_split('/\s+/', $this->removeAccents(strtolower($name)));
       
        if ($name[0] == $this->removeAccents(strtolower($consultorData->getName())) && $name[1]== $this->removeAccents(strtolower($consultorData->getLastName())) && $email == $consultorData->getEmail()) {
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
