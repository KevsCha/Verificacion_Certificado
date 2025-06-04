<?php
class ConsultorService
{
    private $repository;
    public function __construct($repository){
        $this->repository = $repository;
    }
    //! Colocar Exception para el caso de que no se encuentre el consultor
    public function validationData($nameForm, $email, $empresa){
        //TODO: Lanza error si el consultor no existe
        //TODO: Validar que el email sea correcto
        $consultorData = $this->repository->findByEmail($email);
        $nameForm = preg_split('/\s+/', $this->removeAccents(strtolower($nameForm)));
        echo "--------------VALIDANDO CONSULTOR----------------\n";
        echo "\n";
        var_dump($consultorData);
        echo "\n";
        echo "Nombre Formulario: " . $nameForm[0] . "\n";
        echo "Apellido Formulario: ". $nameForm[1]."\n";
        echo "Email Formulario: " . $email . "\n";
        echo "-----Conversion de nombre y apellido a minusculas y sin acentos-----\n";
        $nameDDBB = $this->removeAccents(strtolower($consultorData->getName()));
        $last_nameDDBB = $this->removeAccents(strtolower($consultorData->getLastName()));
        echo "Nombre consultor DDBB: " . $nameDDBB . "\n";
        echo "Apellido consultor DDBB: " . $last_nameDDBB . "\n";
        

        if ($nameForm[0] == $nameDDBB && $nameForm[1]== $last_nameDDBB && $email == $consultorData->getEmail()) {
            return true;
        }
        // if ($nameForm[0] == $this->removeAccents(strtolower($consultorData->getName())) && $nameForm[1]== $this->removeAccents(strtolower($consultorData->getLastName())) && $email == $consultorData->getEmail()) {
        //     return true;
        // }  
        return false;
    }
    private function removeAccents($str){
        $str = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
        $str = preg_replace('/[^A-Za-z0-9 ]/', '', $str);
        return $str;
    }
}
