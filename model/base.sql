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
  `coordenadas` varchar(255) NOT NULL,
  `partido_id` int(11) NOT NULL,
  PRIMARY KEY (id),
  CONSTRAINT FK_localidad_partido_id FOREIGN KEY (partido_id) REFERENCES partido(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `institucion`
--

CREATE TABLE `institucion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `director` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `direccion` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `telefono` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `localidad_id` int(11) NOT NULL,
  `tipo_institucion_id` int(11) NOT NULL,
  PRIMARY KEY (id),
  CONSTRAINT FK_institucion_localidad_id FOREIGN KEY (localidad_id) REFERENCES localidad(id),
  CONSTRAINT FK_tipo_institucion_id FOREIGN KEY (tipo_institucion_id) REFERENCES tipo_institucion(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `partido`
--



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
  `localidad_id` int(11) NULL,
  `partido_id` int(11) NULL,
  `region_sanitaria_id` int(11) NULL,
  `domicilio` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `genero_id` int(11) NULL,
  `tiene_documento` tinyint(1) NOT NULL DEFAULT '1',
  `tipo_doc_id` int(11) NULL,
  `numero` int(11) NOT NULL,
  `tel` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `nro_historia_clinica` int(11) DEFAULT NULL,
  `nro_carpeta` int(11) DEFAULT NULL,
  `obra_social_id` int(11) NULL,
  `borrado` tinyint(1) not null,
  PRIMARY KEY (id),
  CONSTRAINT FK_region_sanitaria_id FOREIGN KEY (region_sanitaria_id) REFERENCES region_sanitaria(id),
  CONSTRAINT FK_obra_social_id FOREIGN KEY (obra_social_id) REFERENCES obra_social(id),
  CONSTRAINT FK_tipo_doc_id FOREIGN KEY (tipo_doc_id) REFERENCES tipo_documento(id),
  CONSTRAINT FK_localidad_id FOREIGN KEY (localidad_id) REFERENCES localidad(id),
  CONSTRAINT FK_partido_id FOREIGN KEY (partido_id) REFERENCES partido(id),
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
  `tratamiento_farmacologico_id` int(11) NULL,
  `acompanamiento_id` int(11) NULL,
  `borrado` tinyint(1) NOT NULL DEFAULT '0',
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
  `admin` tinyint(1) NOT NULL,
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


INSERT INTO `configuracion` (`id`,`variable`,`valor`) values (1,"titulo","Hospital Dr.Alejandro Korn");
INSERT INTO `configuracion` (`id`,`variable`,`valor`) values (2,"descripcion","descripcion inicial");
INSERT INTO `configuracion` (`id`,`variable`,`valor`) values (3,"email","email@inicial.com");
INSERT INTO `configuracion` (`id`,`variable`,`valor`) values (4,"limite","20");
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

INSERT INTO `partido` (`id`,`nombre`,`region_sanitaria_id`) VALUES (1,"Bahía Blanca",1);
INSERT INTO `partido` (`id`,`nombre`,`region_sanitaria_id`) VALUES (2,"Pehuajó",2);
INSERT INTO `partido` (`id`,`nombre`,`region_sanitaria_id`) VALUES (3,"Junín",3);
INSERT INTO `partido` (`id`,`nombre`,`region_sanitaria_id`) VALUES (4,"Pergamino",4);
INSERT INTO `partido` (`id`,`nombre`,`region_sanitaria_id`) VALUES (5,"General San Martín",5);
INSERT INTO `partido` (`id`,`nombre`,`region_sanitaria_id`) VALUES (6,"Lomas de Zamora",6);
INSERT INTO `partido` (`id`,`nombre`,`region_sanitaria_id`) VALUES (7,"General Rodríguez",7);
INSERT INTO `partido` (`id`,`nombre`,`region_sanitaria_id`) VALUES (8,"Mar de Plata",8);
INSERT INTO `partido` (`id`,`nombre`,`region_sanitaria_id`) VALUES (9,"Azul",9);
INSERT INTO `partido` (`id`,`nombre`,`region_sanitaria_id`) VALUES (10,"Chivilcoy",10);
INSERT INTO `partido` (`id`,`nombre`,`region_sanitaria_id`) VALUES (11,"Ensenada",11);
INSERT INTO `partido` (`id`,`nombre`,`region_sanitaria_id`) VALUES (12,"La Matanza",12);

INSERT INTO `localidad` (`id`,`nombre`,`coordenadas`,`partido_id`) VALUES (1,"Bahía Blanca","-38.737580,-62.261030",1);
INSERT INTO `localidad` (`id`,`nombre`,`coordenadas`,`partido_id`) VALUES (2,"Pehuajó","-35.824986,-61.894783",2);
INSERT INTO `localidad` (`id`,`nombre`,`coordenadas`,`partido_id`) VALUES (3,"Junín","-34.592618,-60.950547",3);
INSERT INTO `localidad` (`id`,`nombre`,`coordenadas`,`partido_id`) VALUES (4,"Pergamino","-33.895489,-60.577274",4);
INSERT INTO `localidad` (`id`,`nombre`,`coordenadas`,`partido_id`) VALUES (5,"General San Martín","-34.580379,-58.538680",5);
INSERT INTO `localidad` (`id`,`nombre`,`coordenadas`,`partido_id`) VALUES (6,"Lomas de Zamora","-34.763051,-58.433061",6);
INSERT INTO `localidad` (`id`,`nombre`,`coordenadas`,`partido_id`) VALUES (7,"General Rodríguez","-34.616907,-58.940653",7);
INSERT INTO `localidad` (`id`,`nombre`,`coordenadas`,`partido_id`) VALUES (8,"Mar de Plata","-38.028766,-57.604634",8);
INSERT INTO `localidad` (`id`,`nombre`,`coordenadas`,`partido_id`) VALUES (9,"Azul","-36.779428,-59.854858",9);
INSERT INTO `localidad` (`id`,`nombre`,`coordenadas`,`partido_id`) VALUES (10,"Chivilcoy","-34.891956,-60.037713",10);
INSERT INTO `localidad` (`id`,`nombre`,`coordenadas`,`partido_id`) VALUES (11,"Ensenada","-34.843834,-57.941167",11);
INSERT INTO `localidad` (`id`,`nombre`,`coordenadas`,`partido_id`) VALUES (12,"La Matanza","-34.773338,-58.644947",12);


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


INSERT INTO `permiso` (`id`,`nombre`,`admin`) VALUES ( 1,"consulta_index",0);
INSERT INTO `permiso` (`id`,`nombre`,`admin`) VALUES ( 2,"consulta_new",0);
INSERT INTO `permiso` (`id`,`nombre`,`admin`) VALUES ( 3,"consulta_update",0);
INSERT INTO `permiso` (`id`,`nombre`,`admin`) VALUES ( 4,"consulta_show",0);
INSERT INTO `permiso` (`id`,`nombre`,`admin`) VALUES ( 5,"consulta_destroy",0);
INSERT INTO `permiso` (`id`,`nombre`,`admin`) VALUES ( 6,"paciente_index",0);
INSERT INTO `permiso` (`id`,`nombre`,`admin`) VALUES ( 7,"paciente_new",0);
INSERT INTO `permiso` (`id`,`nombre`,`admin`) VALUES ( 8,"paciente_update",0);
INSERT INTO `permiso` (`id`,`nombre`,`admin`) VALUES ( 9,"paciente_show",0);
INSERT INTO `permiso` (`id`,`nombre`,`admin`) VALUES ( 10,"paciente_destroy",0);
INSERT INTO `permiso` (`id`,`nombre`,`admin`) VALUES ( 11,"usuario_index",1);
INSERT INTO `permiso` (`id`,`nombre`,`admin`) VALUES ( 12,"usuario_new",1);
INSERT INTO `permiso` (`id`,`nombre`,`admin`) VALUES ( 13,"usuario_update",1);
INSERT INTO `permiso` (`id`,`nombre`,`admin`) VALUES ( 14,"usuario_administrarRoles",1);
INSERT INTO `permiso` (`id`,`nombre`,`admin`) VALUES ( 15,"usuario_activarBloquear",1);
INSERT INTO `permiso` (`id`,`nombre`,`admin`) VALUES ( 16,"rol_index",1);
INSERT INTO `permiso` (`id`,`nombre`,`admin`) VALUES ( 17,"rol_new",1);
INSERT INTO `permiso` (`id`,`nombre`,`admin`) VALUES ( 18,"rol_update",1);
INSERT INTO `permiso` (`id`,`nombre`,`admin`) VALUES ( 19,"rol_destroy",1);
INSERT INTO `permiso` (`id`,`nombre`,`admin`) VALUES ( 20,"permiso_index",1);
INSERT INTO `permiso` (`id`,`nombre`,`admin`) VALUES ( 21,"permiso_new",1);
INSERT INTO `permiso` (`id`,`nombre`,`admin`) VALUES ( 22,"permiso_update",1);
INSERT INTO `permiso` (`id`,`nombre`,`admin`) VALUES ( 23,"permiso_destroy",1);
INSERT INTO `permiso` (`id`,`nombre`,`admin`) VALUES ( 24,"reporte_index",1);
INSERT INTO `permiso` (`id`,`nombre`,`admin`) VALUES ( 25,"configuracion_index",1);
INSERT INTO `permiso` (`id`,`nombre`,`admin`) VALUES ( 26,"configuracion_update",1);

INSERT INTO `usuario` (`id`,`email`,`username`,`password`, `activo`, `first_name`,`last_name`)
VALUES ( 1,"admin@admin.com","admin","$2y$10$7hB/B/cLnnDf0e2Bk2tnC.oUDrb1LDYd.ctTXsW6Tu0yQo5sIKA7i","1","admin","admin");

INSERT INTO `usuario` (`id`,`email`,`username`,`password`, `activo`, `first_name`,`last_name`)
VALUES ( 2,"guardia@guardia.com","equipodeguardia","$2y$10$H1jXRhl8CuCGmmLODtubSuR8RpHna17GqjbnBfJAw91LNUFkTVS0q","1","guardia","guardia");

INSERT INTO `usuario` (`id`,`email`,`username`,`password`, `activo`, `first_name`,`last_name`)
VALUES ( 3,"superAdmin@admin.com","superadmin","$2y$10$H1jXRhl8CuCGmmLODtubSuR8RpHna17GqjbnBfJAw91LNUFkTVS0q","1","superadmin","superadmin");

INSERT INTO `rol` VALUES(1,"Administrador");
INSERT INTO `rol` VALUES(2,"EquipoDeGuardia");

INSERT INTO `usuario_tiene_rol` VALUES(1,1,0);
INSERT INTO `usuario_tiene_rol` VALUES(2,2,0);
INSERT INTO `usuario_tiene_rol` VALUES(3,1,0);
INSERT INTO `usuario_tiene_rol` VALUES(3,2,0);

INSERT INTO `rol_tiene_permiso` VALUES(1,1);
INSERT INTO `rol_tiene_permiso` VALUES(1,5);
INSERT INTO `rol_tiene_permiso` VALUES(1,6);
INSERT INTO `rol_tiene_permiso` VALUES(1,10);
INSERT INTO `rol_tiene_permiso` VALUES(1,11);
INSERT INTO `rol_tiene_permiso` VALUES(1,12);
INSERT INTO `rol_tiene_permiso` VALUES(1,13);
INSERT INTO `rol_tiene_permiso` VALUES(1,14);
INSERT INTO `rol_tiene_permiso` VALUES(1,15);
INSERT INTO `rol_tiene_permiso` VALUES(1,16);
INSERT INTO `rol_tiene_permiso` VALUES(1,17);
INSERT INTO `rol_tiene_permiso` VALUES(1,18);
INSERT INTO `rol_tiene_permiso` VALUES(1,19);
INSERT INTO `rol_tiene_permiso` VALUES(1,20);
INSERT INTO `rol_tiene_permiso` VALUES(1,21);
INSERT INTO `rol_tiene_permiso` VALUES(1,22);
INSERT INTO `rol_tiene_permiso` VALUES(1,23);
INSERT INTO `rol_tiene_permiso` VALUES(1,24);
INSERT INTO `rol_tiene_permiso` VALUES(1,25);
INSERT INTO `rol_tiene_permiso` VALUES(1,26);
INSERT INTO `rol_tiene_permiso` VALUES(2,1);
INSERT INTO `rol_tiene_permiso` VALUES(2,2);
INSERT INTO `rol_tiene_permiso` VALUES(2,3);
INSERT INTO `rol_tiene_permiso` VALUES(2,4);
INSERT INTO `rol_tiene_permiso` VALUES(2,6);
INSERT INTO `rol_tiene_permiso` VALUES(2,7);
INSERT INTO `rol_tiene_permiso` VALUES(2,8);
INSERT INTO `rol_tiene_permiso` VALUES(2,9);

INSERT INTO `institucion`(`nombre`,`director`,`direccion`,`telefono`,`localidad_id`,`tipo_institucion_id`) VALUES ("Bahía blanca inst 1","director Bahía1","calle 1 y 1","123457891",1,1);
INSERT INTO `institucion`(`nombre`,`director`,`direccion`,`telefono`,`localidad_id`,`tipo_institucion_id`) VALUES ("Bahía blanca inst 2","director Bahía2","calle 1 y 2","123457892",1,2);
INSERT INTO `institucion`(`nombre`,`director`,`direccion`,`telefono`,`localidad_id`,`tipo_institucion_id`) VALUES ("Bahía blanca inst 3","director Bahía3","calle 1 y 3","123457893",1,1);
INSERT INTO `institucion`(`nombre`,`director`,`direccion`,`telefono`,`localidad_id`,`tipo_institucion_id`) VALUES ("Bahía blanca inst 4","director Bahía4","calle 1 y 4","123457894",1,2);
INSERT INTO `institucion`(`nombre`,`director`,`direccion`,`telefono`,`localidad_id`,`tipo_institucion_id`) VALUES ("Bahía blanca inst 5","director Bahía5","calle 1 y 5","123457895",1,1);
INSERT INTO `institucion`(`nombre`,`director`,`direccion`,`telefono`,`localidad_id`,`tipo_institucion_id`) VALUES ("Pehuajó inst 1","director Pehua1","calle 2 y 1","123457891",2,1);
INSERT INTO `institucion`(`nombre`,`director`,`direccion`,`telefono`,`localidad_id`,`tipo_institucion_id`) VALUES ("Pehuajó inst 2","director Pehua2","calle 2 y 2","123457892",2,2);
INSERT INTO `institucion`(`nombre`,`director`,`direccion`,`telefono`,`localidad_id`,`tipo_institucion_id`) VALUES ("Pehuajó inst 3","director Pehua3","calle 2 y 3","123457893",2,1);
INSERT INTO `institucion`(`nombre`,`director`,`direccion`,`telefono`,`localidad_id`,`tipo_institucion_id`) VALUES ("Pehuajó inst 4","director Pehua4","calle 2 y 4","123457894",2,2);
INSERT INTO `institucion`(`nombre`,`director`,`direccion`,`telefono`,`localidad_id`,`tipo_institucion_id`) VALUES ("Pehuajó inst 5","director Pehua5","calle 2 y 5","123457895",2,1);
INSERT INTO `institucion`(`nombre`,`director`,`direccion`,`telefono`,`localidad_id`,`tipo_institucion_id`) VALUES ("Junín inst 1","director Ju1","calle 3 y 1","123457891",3,1);
INSERT INTO `institucion`(`nombre`,`director`,`direccion`,`telefono`,`localidad_id`,`tipo_institucion_id`) VALUES ("Junín inst 2","director Ju2","calle 3 y 2","123457892",3,2);
INSERT INTO `institucion`(`nombre`,`director`,`direccion`,`telefono`,`localidad_id`,`tipo_institucion_id`) VALUES ("Junín inst 3","director Ju3","calle 3 y 3","123457893",3,1);
INSERT INTO `institucion`(`nombre`,`director`,`direccion`,`telefono`,`localidad_id`,`tipo_institucion_id`) VALUES ("Junín inst 4","director Ju4","calle 3 y 4","123457894",3,2);
INSERT INTO `institucion`(`nombre`,`director`,`direccion`,`telefono`,`localidad_id`,`tipo_institucion_id`) VALUES ("Junín inst 5","director Ju5","calle 3 y 5","123457895",3,1);
INSERT INTO `institucion`(`nombre`,`director`,`direccion`,`telefono`,`localidad_id`,`tipo_institucion_id`) VALUES ("Pergamino inst 1","director Per1","calle 4 y 1","123457891",4,1);
INSERT INTO `institucion`(`nombre`,`director`,`direccion`,`telefono`,`localidad_id`,`tipo_institucion_id`) VALUES ("Pergamino inst 2","director Per2","calle 4 y 2","123457892",4,2);
INSERT INTO `institucion`(`nombre`,`director`,`direccion`,`telefono`,`localidad_id`,`tipo_institucion_id`) VALUES ("Pergamino inst 3","director Per3","calle 4 y 3","123457893",4,1);
INSERT INTO `institucion`(`nombre`,`director`,`direccion`,`telefono`,`localidad_id`,`tipo_institucion_id`) VALUES ("Pergamino inst 4","director Per4","calle 4 y 4","123457894",4,2);
INSERT INTO `institucion`(`nombre`,`director`,`direccion`,`telefono`,`localidad_id`,`tipo_institucion_id`) VALUES ("Pergamino inst 5","director Per5","calle 4 y 5","123457895",4,1);
INSERT INTO `institucion`(`nombre`,`director`,`direccion`,`telefono`,`localidad_id`,`tipo_institucion_id`) VALUES ("General San Martín inst 1","director Martín1","calle 5 y 1","123457891",5,1);
INSERT INTO `institucion`(`nombre`,`director`,`direccion`,`telefono`,`localidad_id`,`tipo_institucion_id`) VALUES ("General San Martín inst 2","director Martín2","calle 5 y 2","123457892",5,2);
INSERT INTO `institucion`(`nombre`,`director`,`direccion`,`telefono`,`localidad_id`,`tipo_institucion_id`) VALUES ("General San Martín inst 3","director Martín3","calle 5 y 3","123457893",5,1);
INSERT INTO `institucion`(`nombre`,`director`,`direccion`,`telefono`,`localidad_id`,`tipo_institucion_id`) VALUES ("General San Martín inst 4","director Martín4","calle 5 y 4","123457894",5,2);
INSERT INTO `institucion`(`nombre`,`director`,`direccion`,`telefono`,`localidad_id`,`tipo_institucion_id`) VALUES ("General San Martín inst 5","director Martín5","calle 5 y 5","123457895",5,1);
INSERT INTO `institucion`(`nombre`,`director`,`direccion`,`telefono`,`localidad_id`,`tipo_institucion_id`) VALUES ("Lomas de Zamora inst 1","director Zamora1","calle 6 y 1","123457891",6,1);
INSERT INTO `institucion`(`nombre`,`director`,`direccion`,`telefono`,`localidad_id`,`tipo_institucion_id`) VALUES ("Lomas de Zamora inst 2","director Zamora2","calle 6 y 2","123457892",6,2);
INSERT INTO `institucion`(`nombre`,`director`,`direccion`,`telefono`,`localidad_id`,`tipo_institucion_id`) VALUES ("Lomas de Zamora inst 3","director Zamora3","calle 6 y 3","123457893",6,1);
INSERT INTO `institucion`(`nombre`,`director`,`direccion`,`telefono`,`localidad_id`,`tipo_institucion_id`) VALUES ("Lomas de Zamora inst 4","director Zamora4","calle 6 y 4","123457894",6,2);
INSERT INTO `institucion`(`nombre`,`director`,`direccion`,`telefono`,`localidad_id`,`tipo_institucion_id`) VALUES ("Lomas de Zamora inst 5","director Zamora5","calle 6 y 5","123457895",6,1);
INSERT INTO `institucion`(`nombre`,`director`,`direccion`,`telefono`,`localidad_id`,`tipo_institucion_id`) VALUES ("General Rodríguez inst 1","director Rodríguez1","calle 7 y 1","123457891",7,1);
INSERT INTO `institucion`(`nombre`,`director`,`direccion`,`telefono`,`localidad_id`,`tipo_institucion_id`) VALUES ("General Rodríguez inst 2","director Rodríguez2","calle 7 y 2","123457892",7,2);
INSERT INTO `institucion`(`nombre`,`director`,`direccion`,`telefono`,`localidad_id`,`tipo_institucion_id`) VALUES ("General Rodríguez inst 3","director Rodríguez3","calle 7 y 3","123457893",7,1);
INSERT INTO `institucion`(`nombre`,`director`,`direccion`,`telefono`,`localidad_id`,`tipo_institucion_id`) VALUES ("General Rodríguez inst 4","director Rodríguez4","calle 7 y 4","123457894",7,2);
INSERT INTO `institucion`(`nombre`,`director`,`direccion`,`telefono`,`localidad_id`,`tipo_institucion_id`) VALUES ("General Rodríguez inst 5","director Rodríguez5","calle 7 y 5","123457895",7,1);
INSERT INTO `institucion`(`nombre`,`director`,`direccion`,`telefono`,`localidad_id`,`tipo_institucion_id`) VALUES ("Mar de Plata inst 1","director Plata1","calle 8 y 1","123457891",8,1);
INSERT INTO `institucion`(`nombre`,`director`,`direccion`,`telefono`,`localidad_id`,`tipo_institucion_id`) VALUES ("Mar de Plata inst 2","director Plata2","calle 8 y 2","123457892",8,2);
INSERT INTO `institucion`(`nombre`,`director`,`direccion`,`telefono`,`localidad_id`,`tipo_institucion_id`) VALUES ("Mar de Plata inst 3","director Plata3","calle 8 y 3","123457893",8,1);
INSERT INTO `institucion`(`nombre`,`director`,`direccion`,`telefono`,`localidad_id`,`tipo_institucion_id`) VALUES ("Mar de Plata inst 4","director Plata4","calle 8 y 4","123457894",8,2);
INSERT INTO `institucion`(`nombre`,`director`,`direccion`,`telefono`,`localidad_id`,`tipo_institucion_id`) VALUES ("Mar de Plata inst 5","director Plata5","calle 8 y 5","123457895",8,1);
INSERT INTO `institucion`(`nombre`,`director`,`direccion`,`telefono`,`localidad_id`,`tipo_institucion_id`) VALUES ("Azul inst 1","director Azul1","calle 9 y 1","123457891",9,1);
INSERT INTO `institucion`(`nombre`,`director`,`direccion`,`telefono`,`localidad_id`,`tipo_institucion_id`) VALUES ("Azul inst 2","director Azul2","calle 9 y 2","123457892",9,2);
INSERT INTO `institucion`(`nombre`,`director`,`direccion`,`telefono`,`localidad_id`,`tipo_institucion_id`) VALUES ("Azul inst 3","director Azul3","calle 9 y 3","123457893",9,1);
INSERT INTO `institucion`(`nombre`,`director`,`direccion`,`telefono`,`localidad_id`,`tipo_institucion_id`) VALUES ("Azul inst 4","director Azul4","calle 9 y 4","123457894",9,2);
INSERT INTO `institucion`(`nombre`,`director`,`direccion`,`telefono`,`localidad_id`,`tipo_institucion_id`) VALUES ("Azul inst 5","director Azul5","calle 9 y 5","123457895",9,1);
INSERT INTO `institucion`(`nombre`,`director`,`direccion`,`telefono`,`localidad_id`,`tipo_institucion_id`) VALUES ("Chivilcoy inst 1","director Chi1","calle 10 y 1","123457891",10,1);
INSERT INTO `institucion`(`nombre`,`director`,`direccion`,`telefono`,`localidad_id`,`tipo_institucion_id`) VALUES ("Chivilcoy inst 2","director Chi2","calle 10 y 2","123457892",10,2);
INSERT INTO `institucion`(`nombre`,`director`,`direccion`,`telefono`,`localidad_id`,`tipo_institucion_id`) VALUES ("Chivilcoy inst 3","director Chi3","calle 10 y 3","123457893",10,1);
INSERT INTO `institucion`(`nombre`,`director`,`direccion`,`telefono`,`localidad_id`,`tipo_institucion_id`) VALUES ("Chivilcoy inst 4","director Chi4","calle 10 y 4","123457894",10,2);
INSERT INTO `institucion`(`nombre`,`director`,`direccion`,`telefono`,`localidad_id`,`tipo_institucion_id`) VALUES ("Chivilcoy inst 5","director Chi5","calle 10 y 5","123457895",10,1);
INSERT INTO `institucion`(`nombre`,`director`,`direccion`,`telefono`,`localidad_id`,`tipo_institucion_id`) VALUES ("Ensenada inst 1","director Sena1","calle 11 y 1","123457891",11,1);
INSERT INTO `institucion`(`nombre`,`director`,`direccion`,`telefono`,`localidad_id`,`tipo_institucion_id`) VALUES ("Ensenada inst 2","director Sena2","calle 11 y 2","123457892",11,2);
INSERT INTO `institucion`(`nombre`,`director`,`direccion`,`telefono`,`localidad_id`,`tipo_institucion_id`) VALUES ("Ensenada inst 3","director Sena3","calle 11 y 3","123457893",11,1);
INSERT INTO `institucion`(`nombre`,`director`,`direccion`,`telefono`,`localidad_id`,`tipo_institucion_id`) VALUES ("Ensenada inst 4","director Sena4","calle 11 y 4","123457894",11,2);
INSERT INTO `institucion`(`nombre`,`director`,`direccion`,`telefono`,`localidad_id`,`tipo_institucion_id`) VALUES ("Ensenada inst 5","director Sena5","calle 11 y 5","123457895",11,1);
INSERT INTO `institucion`(`nombre`,`director`,`direccion`,`telefono`,`localidad_id`,`tipo_institucion_id`) VALUES ("La Matanza inst 1","director Mata1","calle 12 y 1","123457891",12,1);
INSERT INTO `institucion`(`nombre`,`director`,`direccion`,`telefono`,`localidad_id`,`tipo_institucion_id`) VALUES ("La Matanza inst 2","director Mata2","calle 12 y 2","123457892",12,2);
INSERT INTO `institucion`(`nombre`,`director`,`direccion`,`telefono`,`localidad_id`,`tipo_institucion_id`) VALUES ("La Matanza inst 3","director Mata3","calle 12 y 3","123457893",12,1);
INSERT INTO `institucion`(`nombre`,`director`,`direccion`,`telefono`,`localidad_id`,`tipo_institucion_id`) VALUES ("La Matanza inst 4","director Mata4","calle 12 y 4","123457894",12,2);
INSERT INTO `institucion`(`nombre`,`director`,`direccion`,`telefono`,`localidad_id`,`tipo_institucion_id`) VALUES ("La Matanza inst 5","director Mata5","calle 12 y 5","123457895",12,1);












