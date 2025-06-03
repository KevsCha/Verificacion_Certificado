<?php
require_once __DIR__ . '/../entities/Consultores.php';
class ConsultorRepository{
    private $pdo;

    public function __construct($pdo){
        $this->pdo = $pdo;
    }
    public function findAll(){
        $stmt = $this->pdo->query("SELECT * FROM consultores");
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if ($result) {
            $consultores = [];
            foreach ($result as $row) {
                $consultores[] = new Consultores($row['id'], $row['nombre'], $row['apellido'], $row['empresa'], $row['email']);
            }
            return $consultores;
        }
        return [];
    }
    public function findById($id){
        $stmt = $this->pdo->prepare("SELECT * FROM consultores WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return new Consultores($row['id'], $row['nombre'], $row['apellido'], $row['empresa'], $row['email']);
        }
        return null;
    }
    public function findByName($name){
        $stmt = $this->pdo->prepare("SELECT * FROM consultores WHERE nombre LIKE :nombre");
        $stmt->execute(['nombre' => $name]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return new Consultores($row['id'], $row['nombre'], $row['apellido'], $row['empresa'], $row['email']);
        }
        return null;
    }
    public function findByEmail($email){
        $stmt = $this->pdo->prepare("SELECT * FROM consultores WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return new Consultores($row['id'], $row['nombre'], $row['apellido'], $row['empresa'], $row['email']);
        }
        return null;
    }
    public function findIdByEmail($email){
        $stmt = $this->pdo->prepare("SELECT id FROM consultores WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return $row['id'];
        }
        return null;
    }
}