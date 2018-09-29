-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 17-07-2018 a las 18:56:33
-- Versión del servidor: 5.7.19-0ubuntu0.16.04.1-log
-- Versión de PHP: 7.0.18-0ubuntu0.16.04.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `trabajo-proyecto-2018`
--
-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tratamiento`
--

CREATE TABLE `tratamiento_farmacologico` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


--
-- Estructura de tabla para la tabla `acompanamiento`
--

CREATE TABLE `acompanamiento` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Estructura de tabla para la tabla `obra_social`
--

CREATE TABLE `obra_social` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `tipo_documento`
--

CREATE TABLE `tipo_documento` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `tipo_documento`
--

CREATE TABLE `genero` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `region_sanitaria`
--

CREATE TABLE `region_sanitaria` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `tipo_institucion`
--

CREATE TABLE `tipo_institucion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `institucion`
--

CREATE TABLE `institucion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `director` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `telefono` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `region_sanitaria_id` int(11) NOT NULL,
  `tipo_institucion_id` int(11) NOT NULL,
  PRIMARY KEY (id),
  CONSTRAINT FK_institucion_region_sanitaria_id FOREIGN KEY (region_sanitaria_id) REFERENCES region_sanitaria(id),
  CONSTRAINT FK_tipo_institucion_id FOREIGN KEY (tipo_institucion_id) REFERENCES tipo_institucion(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `partido`
--

CREATE TABLE `partido` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `region_sanitaria_id` int(11) NOT NULL,
  PRIMARY KEY (id),
  CONSTRAINT FK_partido_region_sanitaria_id FOREIGN KEY (region_sanitaria_id) REFERENCES region_sanitaria(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `localidad`
--

CREATE TABLE `localidad` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `partido_id` int(11) NOT NULL,
  PRIMARY KEY (id),
  CONSTRAINT FK_partido_id FOREIGN KEY (partido_id) REFERENCES partido(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `motivo`
--

CREATE TABLE `motivo_consulta` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `paciente`
--

CREATE TABLE `paciente` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `apellido` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `nombre` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `fecha_nac` date NOT NULL,
  `lugar_nac` varchar(255) DEFAULT NULL,
  `localidad_id` int(11) NOT NULL,
  `region_sanitaria_id` int(11) NOT NULL,
  `domicilio` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `genero_id` int(11) NOT NULL,
  `tiene_documento` tinyint(1) NOT NULL DEFAULT '1',
  `tipo_doc_id` int(11) NOT NULL,
  `numero` int(11) NOT NULL,
  `tel` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `nro_historia_clinica` int(11) DEFAULT NULL,
  `nro_carpeta` int(11) DEFAULT NULL,
  `obra_social_id` int(11) NOT NULL,
  `borrado` tinyint(1) not null,
  PRIMARY KEY (id),
  CONSTRAINT FK_region_sanitaria_id FOREIGN KEY (region_sanitaria_id) REFERENCES region_sanitaria(id),
  CONSTRAINT FK_obra_social_id FOREIGN KEY (obra_social_id) REFERENCES obra_social(id),
  CONSTRAINT FK_tipo_doc_id FOREIGN KEY (tipo_doc_id) REFERENCES tipo_documento(id),
  CONSTRAINT FK_localidad_id FOREIGN KEY (localidad_id) REFERENCES localidad(id),
  CONSTRAINT FK_genero_id FOREIGN KEY (genero_id) REFERENCES genero(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `consulta`
--

CREATE TABLE `consulta` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `paciente_id` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `motivo_id`  int(11) NOT NULL,
  `derivacion_id`  int(11) DEFAULT NULL,
  `articulacion_con_instituciones` varchar(255) NULL,
  `internacion` tinyint(1) NOT NULL DEFAULT '0',
  `diagnostico` varchar(255) NOT NULL NULL,
  `observaciones` varchar(255) DEFAULT NULL,
  `tratamiento_farmacologico_id` int(11) NOT NULL,
  `acompanamiento_id` int(11) NOT NULL,
  PRIMARY KEY (id),
  CONSTRAINT FK_paciente_id FOREIGN KEY (paciente_id) REFERENCES paciente(id),
  CONSTRAINT FK_motivo_id FOREIGN KEY (motivo_id) REFERENCES motivo_consulta(id),
  CONSTRAINT FK_derivacion_id FOREIGN KEY (derivacion_id) REFERENCES institucion(id),
  CONSTRAINT FK_tratamiento_farmacologico_id FOREIGN KEY (tratamiento_farmacologico_id) REFERENCES tratamiento_farmacologico(id),
  CONSTRAINT FK_acompanamiento_id FOREIGN KEY (acompanamiento_id) REFERENCES acompanamiento(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '0',
  `updated_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `first_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol`
--

CREATE TABLE `rol` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permiso`
--

CREATE TABLE `permiso` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol_tiene_permiso`
--

CREATE TABLE `rol_tiene_permiso` (
  `rol_id` int(11) NOT NULL,
  `permiso_id` int(11) NOT NULL,
  PRIMARY KEY (rol_id, permiso_id),
  CONSTRAINT FK_rol_id FOREIGN KEY (rol_id) REFERENCES rol(id),
  CONSTRAINT FK_permiso_id FOREIGN KEY (permiso_id) REFERENCES permiso(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol_tiene_permiso`
--

CREATE TABLE `usuario_tiene_rol` (
  `usuario_id` int(11) NOT NULL,
  `rol_id` int(11) NOT NULL,
  `eliminado` tinyint(1) not null,
  PRIMARY KEY (usuario_id, rol_id),
  CONSTRAINT FK_usuario_utp_id FOREIGN KEY (usuario_id) REFERENCES usuario(id),
  CONSTRAINT FK_rol_utp_id FOREIGN KEY (rol_id) REFERENCES rol(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `configuracion`
--

CREATE TABLE `configuracion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `variable` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `valor` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;


-- TODO

-- revisar campos que pueden ser nulos (NO obligatorios)
-- armar tabla para mantener la historia de derivaciones de los pacientes
INSERT INTO `obra_social` (`id`,`nombre`) values (1,"OSDE");
INSERT INTO `obra_social` (`id`,`nombre`) values (2,"Sancor Salud");
INSERT INTO `obra_social` (`id`,`nombre`) values (3,"Medicus");
INSERT INTO `obra_social` (`id`,`nombre`) values (4,"Medif\u00e9");
INSERT INTO `obra_social` (`id`,`nombre`) values (5,"Galeno");
INSERT INTO `obra_social` (`id`,`nombre`) values (6,"Accord Salud");
INSERT INTO `obra_social` (`id`,`nombre`) values (7,"OMINT");
INSERT INTO `obra_social` (`id`,`nombre`) values (8,"Swiss Medical");
INSERT INTO `obra_social` (`id`,`nombre`) values (9,"AcaSalud");
INSERT INTO `obra_social` (`id`,`nombre`) values (10,"Bristol Medicine");
INSERT INTO `obra_social` (`id`,`nombre`) values (11,"OSECAC");
INSERT INTO `obra_social` (`id`,`nombre`) values (12,"Uni\u00f3n Personal");
INSERT INTO `obra_social` (`id`,`nombre`) values (13,"OSPACP");
INSERT INTO `obra_social` (`id`,`nombre`) values (14,"OSDEPYM");
INSERT INTO `obra_social` (`id`,`nombre`) values (15,"Luis Pasteur");
INSERT INTO `obra_social` (`id`,`nombre`) values (16,"OSMEDICA");
INSERT INTO `obra_social` (`id`,`nombre`) values (17,"IOMA");
INSERT INTO `obra_social` (`id`,`nombre`) values (18,"OSPJN");
INSERT INTO `obra_social` (`id`,`nombre`) values (19,"OSSSB");
INSERT INTO `obra_social` (`id`,`nombre`) values (20,"OSPEPBA");
INSERT INTO `obra_social` (`id`,`nombre`) values (21,"OSPE");


INSERT INTO `configuracion` (`id`,`variable`,`valor`) values (1,"titulo","titulo inicial");
INSERT INTO `configuracion` (`id`,`variable`,`valor`) values (2,"descripcion","descripcion inicial");
INSERT INTO `configuracion` (`id`,`variable`,`valor`) values (3,"email","email@inicial");
INSERT INTO `configuracion` (`id`,`variable`,`valor`) values (4,"limite","1000");
INSERT INTO `configuracion` (`id`,`variable`,`valor`) values (5,"habilitado","1");


INSERT INTO `genero` (`id`, `nombre`) VALUES (1, 'Masculino');
INSERT INTO `genero` (`id`, `nombre`) VALUES (2, 'Femenino');
INSERT INTO `genero` (`id`, `nombre`) VALUES (3, 'Otro');

INSERT INTO `tipo_documento` (`id`, `nombre`) VALUES (1, 'DNI');
INSERT INTO `tipo_documento` (`id`, `nombre`) VALUES (2, 'LC');
INSERT INTO `tipo_documento` (`id`, `nombre`) VALUES (3, 'CI');
INSERT INTO `tipo_documento` (`id`, `nombre`) VALUES (4, 'LE');
INSERT INTO `tipo_documento` (`id`, `nombre`) VALUES (5, 'Pasaporte');

INSERT INTO `tipo_institucion` (`id`, `nombre`) VALUES (1, 'Hospital');
INSERT INTO `tipo_institucion` (`id`, `nombre`) VALUES (2, 'Comisaría');

INSERT INTO `region_sanitaria` (`id`, `nombre`) VALUES (1, 'I');
INSERT INTO `region_sanitaria` (`id`, `nombre`) VALUES (2, 'II');
INSERT INTO `region_sanitaria` (`id`, `nombre`) VALUES (3, 'III');
INSERT INTO `region_sanitaria` (`id`, `nombre`) VALUES (4, 'IV');
INSERT INTO `region_sanitaria` (`id`, `nombre`) VALUES (5, 'V');
INSERT INTO `region_sanitaria` (`id`, `nombre`) VALUES (6, 'VI');
INSERT INTO `region_sanitaria` (`id`, `nombre`) VALUES (7, 'VII');
INSERT INTO `region_sanitaria` (`id`, `nombre`) VALUES (8, 'VIII');
INSERT INTO `region_sanitaria` (`id`, `nombre`) VALUES (9, 'IX');
INSERT INTO `region_sanitaria` (`id`, `nombre`) VALUES (10, 'X');
INSERT INTO `region_sanitaria` (`id`, `nombre`) VALUES (11, 'XI');
INSERT INTO `region_sanitaria` (`id`, `nombre`) VALUES (12, 'XII');

INSERT INTO `partido` (`id`,`nombre`,`region_sanitaria_id`) VALUES (1,"partido pruebas",1);

INSERT INTO `localidad`(`id`,`nombre`,`partido_id`) VALUES (1,"localidad prueba",1);


INSERT INTO `motivo_consulta` (`id`, `nombre`) VALUES (1, 'Receta Médica');
INSERT INTO `motivo_consulta` (`id`, `nombre`) VALUES (2, 'Control por Guardia');
INSERT INTO `motivo_consulta` (`id`, `nombre`) VALUES (3, 'Consulta');
INSERT INTO `motivo_consulta` (`id`, `nombre`) VALUES (4, 'Intento de Suicidio');
INSERT INTO `motivo_consulta` (`id`, `nombre`) VALUES (5, 'Interconsulta');
INSERT INTO `motivo_consulta` (`id`, `nombre`) VALUES (6, 'Otras');


INSERT INTO `tratamiento_farmacologico` (`id`, `nombre`) VALUES (1, 'Mañana');
INSERT INTO `tratamiento_farmacologico` (`id`, `nombre`) VALUES (2, 'Tarde');
INSERT INTO `tratamiento_farmacologico` (`id`, `nombre`) VALUES (3, 'Noche');

INSERT INTO `acompanamiento` (`id`, `nombre`) VALUES (1, 'Familiar Cercano');
INSERT INTO `acompanamiento` (`id`, `nombre`) VALUES (2, 'Hermanos e hijos');
INSERT INTO `acompanamiento` (`id`, `nombre`) VALUES (3, 'Pareja');
INSERT INTO `acompanamiento` (`id`, `nombre`) VALUES (4, 'Referentes vinculares');
INSERT INTO `acompanamiento` (`id`, `nombre`) VALUES (5, 'Policía');
INSERT INTO `acompanamiento` (`id`, `nombre`) VALUES (6, 'SAME');
INSERT INTO `acompanamiento` (`id`, `nombre`) VALUES (7, 'Por sus propios medios');