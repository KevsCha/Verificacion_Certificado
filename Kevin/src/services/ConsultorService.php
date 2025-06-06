<?php
require_once __DIR__ . '/../exceptions/ValidationException.php';
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
        
        $nameDDBB = $this->removeAccents(strtolower($consultorData->getName()));
        $last_nameDDBB = $this->removeAccents(strtolower($consultorData->getLastName()));

        if ($nameForm[0] != $nameDDBB || $nameForm[1] != $last_nameDDBB)
            throw new ValidationException("Los datos del consultor no coinciden con los de la base de datos. Por favor, verifique el nombre, apellido y correo electrónico.");
        return true;
    }
    public function saveConsultor($name, $empresa, $email){
        $consultorDDBB = $this->repository->findByEmail($email);
        echo $consultorDDBB." resultado de la busqueda <br>";

        if  (!$consultorDDBB) {
            $name = $this->removeAccents(strtolower($name));
            $name = preg_split('/\s+/', $name);
            $consultorDDBB = new Consultores(null, $name[0], $name[1], $empresa, $email);
            $this->repository->save($consultorDDBB);
            echo "<br>Consultor no encontrado, creando nuevo consultor: " . $consultorDDBB."<br>";
        }else if ($consultorDDBB->getEmpresa() != $empresa) {
            echo "<br>Consultor encontrado, actualizando empresa: " . $consultorDDBB."<br>";
            $consultorDDBB->setEmpresa($empresa);
            $this->repository->update($consultorDDBB);
        }
        return true;

    }
    private function removeAccents($str){
        $str = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
        $str = preg_replace('/[^A-Za-z0-9 ]/', '', $str);
        return $str;
    }
}
