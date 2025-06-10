<?php
$host = '127.0.0.1';
$db   = 'db_vc';
$user = 'root';
$pass = 'contrasena';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
    // Conexión exitosa
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}
// CONEXIÓN A LA BASE DE DATOS EXITOS DE XAMPP
// Puedes usar $pdo para realizar consultas a la base de datos