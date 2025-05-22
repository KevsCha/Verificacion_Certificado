<?php
require_once __DIR__ . '/../entities/Consultores.php';
class ConsultorRepository{
    private $pdo;

    public function __construct($pdo){
        $this->pdo = $pdo;
    }

    public function findByName($name){
        $stmt = $this->pdo->prepare("SELECT * FROM consultores WHERE nombre LIKE :nombre");
        $stmt->execute(['nombre' => $name]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            echo "Datos encontrados";
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
}