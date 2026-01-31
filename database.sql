-- Creación de la base de datos
SET NAMES utf8mb4;
DROP DATABASE IF EXISTS motogp_db;
CREATE DATABASE motogp_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE motogp_db;

-- Tabla de Usuarios
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    rol ENUM('admin', 'fan') NOT NULL DEFAULT 'fan',
    fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de Equipos
CREATE TABLE equipos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    marca VARCHAR(50) NOT NULL,
    pais VARCHAR(50),
    imagen_url VARCHAR(255)
);

-- Tabla de Pilotos
CREATE TABLE pilotos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    dorsal INT NOT NULL UNIQUE,
    pais VARCHAR(50),
    id_equipo INT,
    imagen_url VARCHAR(255),
    puntos INT DEFAULT 0,
    FOREIGN KEY (id_equipo) REFERENCES equipos(id) ON DELETE SET NULL
);

-- Tabla de Comentarios (4ª tabla interactiva)
CREATE TABLE comentarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    id_piloto INT NOT NULL,
    comentario TEXT NOT NULL,
    valoracion INT CHECK (valoracion >= 1 AND valoracion <= 5),
    fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (id_piloto) REFERENCES pilotos(id) ON DELETE CASCADE
);

-- INSERTS DE PRUEBA (SEED DATA)

-- Usuarios (Password por defecto '1234' hasheado para ejemplo, se debe generar con PHP)
-- Hash de '1234' es $2y$10$fW.P.y/../.. (ejemplo genérico, usaremos uno válido)
INSERT INTO usuarios (nombre, email, password, rol) VALUES 
('Admin', 'admin@motogp.com', '$2y$10$e.g./..HASH..PLACEHOLDER', 'admin'),
('Juan Fan', 'juan@gmail.com', '$2y$10$e.g./..HASH..PLACEHOLDER', 'fan');

-- Equipos
INSERT INTO equipos (nombre, marca, pais, imagen_url) VALUES
('Ducati Lenovo Team', 'Ducati', 'Italia', 'ducati_logo.png'),
('Repsol Honda Team', 'Honda', 'Japón', 'honda_logo.png'),
('Monster Energy Yamaha', 'Yamaha', 'Japón', 'yamaha_logo.png'),
('Aprilia Racing', 'Aprilia', 'Italia', 'aprilia_logo.png'),
('Red Bull KTM Factory', 'KTM', 'Austria', 'ktm_logo.png');

-- Pilotos
INSERT INTO pilotos (nombre, dorsal, pais, id_equipo, puntos) VALUES
('Pecco Bagnaia', 63, 'Italia', 1, 250),
('Enea Bastianini', 23, 'Italia', 1, 180),
('Marc Márquez', 93, 'España', 2, 100),
('Joan Mir', 36, 'España', 2, 50),
('Fabio Quartararo', 20, 'Francia', 3, 200),
('Aleix Espargaró', 41, 'España', 4, 160),
('Maverick Viñales', 12, 'España', 4, 140),
('Brad Binder', 33, 'Sudáfrica', 5, 170),
('Jack Miller', 43, 'Australia', 5, 120),
('Jorge Martín', 89, 'España', 1, 220); -- Asumiendo que pudiese estar en un satélite pero linkeado aqui por simplificar o crear otro equipo

-- Comentarios
INSERT INTO comentarios (id_usuario, id_piloto, comentario, valoracion) VALUES
(2, 1, 'Increíble carrera la última!', 5),
(2, 3, 'Siempre será el mejor.', 5),
(2, 5, 'Necesita mejorar la moto.', 3);
