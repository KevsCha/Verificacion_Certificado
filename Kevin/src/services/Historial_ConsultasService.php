<?php
class Historial_ConsultasService{
    private $repository;
    private $consultorRepository;
    private $certificadoRepository;

    public function __construct($repository, $consultorRepository, $certificadoRepository){
        $this->repository = $repository;
        $this->consultorRepository = $consultorRepository;
        $this->certificadoRepository = $certificadoRepository;
    }

    public function saveConsulta($consultor_email, $certificado_num_regis){

        $idConsultor = $this->consultorRepository->findIdByEmail($consultor_email);
        $idCertificado = $this->certificadoRepository->findIdByNumRegisCertificado($certificado_num_regis);
        $certficadoValidez = $this->certificadoRepository->findById($idCertificado);
        $resultado = false;

        if(!$idConsultor || !$idCertificado){
            $resultado = 'no_encontrado';
            throw new Exception("Consultor o certificado no encontrado");
        }
        else
            $resultado = $certficadoValidez->getFechaValidez() > date('Y-m-d') ? 'valido' : 'caducado';
        echo "<br> Certificado: $resultado <br>";
        $this->repository->save($idConsultor, $idCertificado, $resultado);
        return true;
    }

    public function getConsultasByConsultorId($consultor_id){
        return $this->repository->findByConsultorId($consultor_id);
    }
}