-- BASE DE DATOS DE PRUEBA PARA VERIFICAR SI UN CERTIFICADO EXISTE O NO

-- Crear la base de datos
CREATE DATABASE IF NOT EXISTS verificacion_certificados;
USE verificacion_certificados;

-- Tabla de Consultores
CREATE TABLE consultores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    apellido VARCHAR(50) NOT NULL,
    empresa VARCHAR(50) NOT NULL,
    email VARCHAR(50) NOT NULL
);

-- Tabla de Certificados
CREATE TABLE certificados (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    apellido VARCHAR(50) NOT NULL,
    num_regis_certificado INT NOT NULL UNIQUE
);

-- Tabla de Consultas
CREATE TABLE consultas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_consultor INT NOT NULL,
    id_certificado INT NOT NULL,
    fecha_consulta DATETIME DEFAULT CURRENT_TIMESTAMP,
    resultado ENUM('valido', 'caducado', 'no_encontrado') NOT NULL,

    FOREIGN KEY (id_consultor) REFERENCES Consultores(id),
    FOREIGN KEY (id_certificado) REFERENCES Certificados(id)
);

-- DATOS -----------------------------------------

-- CONSULTORES
INSERT INTO consultores (nombre, apellido, empresa, email) VALUES
('Laura', 'Gómez', 'SeguridadGlobal S.A.', 'laura.gomez@seguridadglobal.com'),
('Carlos', 'Mendoza', 'ProtecVisión Ltda.', 'carlos.mendoza@proteccion.com'),
('Ana', 'Ruiz', 'VigilanciaTech', 'ana.ruiz@vigilancia.com');

-- CERTIFICADOS
INSERT INTO certificados (nombre, apellido, num_regis_certificado) VALUES
('Juan', 'Pérez', 1001),
('Marta', 'López', 1002),
('Luis', 'Hernández', 1003);

-- CONSULTAS
INSERT INTO consultas (id_consultor, id_certificado, fecha_consulta, resultado) VALUES
(1, 1, '2025-05-01 10:30:00', 'valido');       -- Laura consulta a Juan 