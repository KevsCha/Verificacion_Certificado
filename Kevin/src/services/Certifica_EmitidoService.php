<?php
class Certifica_EmitidoService{
    private $repository;
    public function __construct($repository){
        $this->repository = $repository;
    }
    //! Colocar Exception para el caso de que no se encuentre el certificado
    public function validationData($nameForm, $num_certificado){
        //TODO: Lanza error si el certificado no existe
        //TODO: Validar que el numero de certificado sea correcto
        $certificadoData = $this->repository->findByNumRegisCertificado($num_certificado);
        $nameForm = preg_split('/\s+/', $this->removeAccents(strtolower($nameForm)));

        echo "--------------VALIDANDO CERTIFICADO----------------\n";
        echo "\n";
        var_dump($certificadoData);
        echo "\n";
        echo "Nombre Formulario: " . $nameForm[0] . "\n";
        echo "Apellido Formulario: ". $nameForm[1]."\n";
        echo "Numero de certificado Formulario: " . $num_certificado . "\n";
        echo "-----Conversion de nombre y apellido a minusculas y sin acentos-----\n";
        $nameDDBB = $this->removeAccents(strtolower($certificadoData->getName()));
        $last_nameDDBB = $this->removeAccents(strtolower($certificadoData->getLastName()));
        echo "Nombre Certificado DDBB: " . $nameDDBB . "\n";
        echo "Apellido Certificado DDBB: " . $last_nameDDBB . "\n";

        if($nameForm[0] == $nameDDBB && $nameForm[1] == $last_nameDDBB && $num_certificado == $certificadoData->getNumCertificado()){
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