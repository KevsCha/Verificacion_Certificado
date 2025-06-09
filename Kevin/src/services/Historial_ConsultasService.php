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
        //echo "<br> Certificado: $resultado <br>";
        echo date('Y-m-d H:i:s') . "<br>";
        $historico = new Historial_Consultas(null, $idConsultor, $idCertificado, date('Y-m-d H:i:s'), $resultado);
        $this->repository->save($historico);
        return true;
    }

    public function getConsultasByConsultorId($consultor_id){
        return $this->repository->findByConsultorId($consultor_id);
    }
}