-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versión del servidor:         8.0.30 - MySQL Community Server - GPL
-- SO del servidor:              Win64
-- HeidiSQL Versión:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Volcando estructura de base de datos para web_asistencias_app
CREATE DATABASE IF NOT EXISTS `web_asistencias_app` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `web_asistencias_app`;

-- Volcando estructura para tabla web_asistencias_app.alumnos
CREATE TABLE IF NOT EXISTS `alumnos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `apellido` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `dni` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `fecha_nacimiento` date NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `dni` (`dni`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando estructura para tabla web_asistencias_app.materias
CREATE TABLE IF NOT EXISTS `materias` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `instituciones_id` int NOT NULL,
  `departamento` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `curso` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_institucion` (`instituciones_id`) USING BTREE,
  CONSTRAINT `materia_ibfk_1` FOREIGN KEY (`instituciones_id`) REFERENCES `instituciones` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando estructura para tabla web_asistencias_app.asistencias
CREATE TABLE IF NOT EXISTS `asistencias` (
  `id` int NOT NULL AUTO_INCREMENT,
  `materia_id` int NOT NULL,
  `alumno_id` int NOT NULL,
  `fecha` date NOT NULL,
  `asistencia` float NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `materia_id` (`materia_id`),
  KEY `alumno_id` (`alumno_id`),
  CONSTRAINT `FK__alumnos` FOREIGN KEY (`alumno_id`) REFERENCES `alumnos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK__materias` FOREIGN KEY (`materia_id`) REFERENCES `materias` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla web_asistencias_app.asistencias: ~0 rows (aproximadamente)

-- Volcando estructura para tabla web_asistencias_app.instituciones
CREATE TABLE IF NOT EXISTS `instituciones` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `direccion` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `cue` int NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cue` (`cue`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando estructura para tabla web_asistencias_app.materia_alumno
CREATE TABLE IF NOT EXISTS `materia_alumno` (
  `id` int NOT NULL AUTO_INCREMENT,
  `materia_id` int NOT NULL,
  `alumno_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_materia_alumno_materias` (`materia_id`),
  KEY `FK_materia_alumno_alumnos` (`alumno_id`),
  CONSTRAINT `FK_materia_alumno_alumnos` FOREIGN KEY (`alumno_id`) REFERENCES `alumnos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_materia_alumno_materias` FOREIGN KEY (`materia_id`) REFERENCES `materias` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando estructura para tabla web_asistencias_app.notas
CREATE TABLE IF NOT EXISTS `notas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `materia_id` int NOT NULL,
  `alumno_id` int NOT NULL,
  `parcial_1` float DEFAULT NULL,
  `parcial_2` float DEFAULT NULL,
  `final` float DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_notas_materias` (`materia_id`),
  KEY `FK_notas_alumnos` (`alumno_id`),
  CONSTRAINT `FK_notas_alumnos` FOREIGN KEY (`alumno_id`) REFERENCES `alumnos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_notas_materias` FOREIGN KEY (`materia_id`) REFERENCES `materias` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando estructura para tabla web_asistencias_app.profesores
CREATE TABLE IF NOT EXISTS `profesores` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `apellido` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `dni` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `email` varchar(100) NOT NULL,
  `fecha_nacimiento` date NOT NULL,
  `legajo` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `dni` (`dni`),
  UNIQUE KEY `legajo` (`legajo`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla web_asistencias_app.profesores: ~0 rows (aproximadamente)

-- Volcando estructura para tabla web_asistencias_app.profesor_institucion
CREATE TABLE IF NOT EXISTS `profesor_institucion` (
  `profesor_institucion_id` int NOT NULL AUTO_INCREMENT,
  `profesor_id` int NOT NULL,
  `institucion_id` int NOT NULL,
  `materia_id` int DEFAULT NULL,
  PRIMARY KEY (`profesor_institucion_id`) USING BTREE,
  KEY `profesor_institucion_ibfk_1` (`profesor_id`),
  KEY `profesor_institucion_ibfk_2` (`institucion_id`),
  KEY `profesor_institucion_ibfk_3` (`materia_id`),
  CONSTRAINT `profesor_institucion_ibfk_1` FOREIGN KEY (`profesor_id`) REFERENCES `profesores` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `profesor_institucion_ibfk_2` FOREIGN KEY (`institucion_id`) REFERENCES `instituciones` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `profesor_institucion_ibfk_3` FOREIGN KEY (`materia_id`) REFERENCES `materias` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla web_asistencias_app.profesor_institucion: ~0 rows (aproximadamente)

-- Volcando estructura para tabla web_asistencias_app.ram
CREATE TABLE IF NOT EXISTS `ram` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nota_regular` float DEFAULT NULL,
  `nota_promocion` float DEFAULT NULL,
  `asistencia_regular` int DEFAULT NULL,
  `asistencia_promocion` int DEFAULT NULL,
  `institucion_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_ram_instituciones` (`institucion_id`),
  CONSTRAINT `FK_ram_instituciones` FOREIGN KEY (`institucion_id`) REFERENCES `instituciones` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla web_asistencias_app.ram: ~0 rows (aproximadamente)

-- Volcando estructura para tabla web_asistencias_app.usuarios
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `contrasena` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `condicion` enum('profesor','administrador') NOT NULL,
  `profesor_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `FK_usuarios_profesores` (`profesor_id`),
  CONSTRAINT `FK_usuarios_profesores` FOREIGN KEY (`profesor_id`) REFERENCES `profesores` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Volcando datos para la tabla web_asistencias_app.usuarios: ~1 rows (aproximadamente)
INSERT INTO `usuarios` (`id`, `nombre`, `apellido`, `email`, `contrasena`, `condicion`, `profesor_id`) VALUES
	(1, 'Administrador', 'Admin', 'administrador@gmail.com', '$2y$10$MHpIYLjCAATzQsUg3I1wQuI6vBWv/Q38dVt7C3593mhiXcuulKA4W', 'administrador', NULL);

-- Volcando datos para la tabla web_asistencias_app.alumnos: ~22 rows (aproximadamente)
INSERT INTO `alumnos` (`id`, `nombre`, `apellido`, `dni`, `fecha_nacimiento`) VALUES
	(1, 'Valentino', 'Andrade', '35123456', '1999-03-12'),
	(2, 'Lucas', 'Cedres', '34876543', '1998-09-07'),
	(3, 'Facundo', 'Figun', '40123789', '2000-11-25'),
	(4, 'Luca', 'Giordano', '32456789', '1997-06-02'),
	(5, 'Bruno', 'Godoy', '36789123', '1999-01-18'),
	(6, 'Agustin', 'Gomez', '33567890', '1996-04-30'),
	(7, 'Brian', 'Gonzalez', '35678901', '1997-12-05'),
	(8, 'Federico', 'Guigou Scottini', '37890123', '1998-08-15'),
	(9, 'Luna', 'Marrano', '38901234', '1999-03-10'),
	(10, 'Giuliana', 'Mercado Aviles', '33345678', '1995-10-22'),
	(11, 'Lucila', 'Mercado Ruiz', '32567890', '1996-12-08'),
	(12, 'Angel', 'Murillo', '34890123', '1998-02-27'),
	(13, 'Juan', 'Nissero', '36123456', '1999-07-17'),
	(14, 'Fausto', 'Parada', '35234567', '1997-11-06'),
	(15, 'Ignacio', 'Piter', '32789012', '1996-05-19'),
	(16, 'Tomas', 'Planchon', '40456789', '2000-09-03'),
	(17, 'Elisa', 'Ronconi', '31678123', '1995-01-24'),
	(18, 'Exequiel', 'Sanchez', '33234567', '1998-04-11'),
	(19, 'Melina', 'Schimpf Baldo', '33789456', '1996-10-09'),
	(20, 'Diego', 'Segovia', '34567890', '1997-02-13'),
	(21, 'Camila', 'Sittner', '36456789', '1999-08-20'),
	(22, 'Yamil', 'Villa', '35345678', '1998-06-28');

-- Volcando datos para la tabla web_asistencias_app.instituciones: ~1 rows (aproximadamente)
INSERT INTO `instituciones` (`id`, `nombre`, `direccion`, `cue`) VALUES
	(1, 'Instituto Sedes Sapientiae', 'Primera Junta 75', 300154700);

-- Volcando datos para la tabla web_asistencias_app.materias: ~1 rows (aproximadamente)
INSERT INTO `materias` (`id`, `nombre`, `instituciones_id`, `departamento`, `curso`) VALUES
	(1, 'Programación II', 1, 'Depto de Sistemas', '2do Año');

-- Volcando datos para la tabla web_asistencias_app.notas: ~22 rows (aproximadamente)
INSERT INTO `notas` (`id`, `materia_id`, `alumno_id`, `parcial_1`, `parcial_2`, `final`) VALUES
	(1, 1, 22, NULL, NULL, NULL),
	(2, 1, 21, NULL, NULL, NULL),
	(3, 1, 20, NULL, NULL, NULL),
	(4, 1, 19, NULL, NULL, NULL),
	(5, 1, 18, NULL, NULL, NULL),
	(6, 1, 17, NULL, NULL, NULL),
	(7, 1, 16, NULL, NULL, NULL),
	(8, 1, 15, NULL, NULL, NULL),
	(9, 1, 14, NULL, NULL, NULL),
	(10, 1, 13, NULL, NULL, NULL),
	(11, 1, 12, NULL, NULL, NULL),
	(12, 1, 11, NULL, NULL, NULL),
	(13, 1, 10, NULL, NULL, NULL),
	(14, 1, 9, NULL, NULL, NULL),
	(15, 1, 8, NULL, NULL, NULL),
	(16, 1, 7, NULL, NULL, NULL),
	(17, 1, 6, NULL, NULL, NULL),
	(18, 1, 5, NULL, NULL, NULL),
	(19, 1, 4, NULL, NULL, NULL),
	(20, 1, 3, NULL, NULL, NULL),
	(21, 1, 2, NULL, NULL, NULL),
	(22, 1, 1, NULL, NULL, NULL);

-- Volcando datos para la tabla web_asistencias_app.materia_alumno: ~22 rows (aproximadamente)
INSERT INTO `materia_alumno` (`id`, `materia_id`, `alumno_id`) VALUES
	(1, 1, 22),
	(2, 1, 21),
	(3, 1, 20),
	(4, 1, 19),
	(5, 1, 18),
	(6, 1, 17),
	(7, 1, 16),
	(8, 1, 15),
	(9, 1, 14),
	(10, 1, 13),
	(11, 1, 12),
	(12, 1, 11),
	(13, 1, 10),
	(14, 1, 9),
	(15, 1, 8),
	(16, 1, 7),
	(17, 1, 6),
	(18, 1, 5),
	(19, 1, 4),
	(20, 1, 3),
	(21, 1, 2),
	(22, 1, 1);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
