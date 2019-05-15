-- phpMyAdmin SQL Dump
-- version 4.6.6deb5
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 15-05-2019 a las 17:14:06
-- Versión del servidor: 10.1.34-MariaDB-0ubuntu0.18.04.1
-- Versión de PHP: 7.2.7-0ubuntu0.18.04.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `grupo12`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `acompanamiento`
--

CREATE TABLE `acompanamiento` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `acompanamiento`
--

INSERT INTO `acompanamiento` (`id`, `nombre`) VALUES
(1, 'Familiar Cercano'),
(2, 'Hermanos e hijos'),
(3, 'Pareja'),
(4, 'Referentes vinculares'),
(5, 'Policía'),
(6, 'SAME'),
(7, 'Por sus propios medios');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `configuracion`
--

CREATE TABLE `configuracion` (
  `id` int(11) NOT NULL,
  `variable` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `valor` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `configuracion`
--

INSERT INTO `configuracion` (`id`, `variable`, `valor`) VALUES
(1, 'titulo', 'Hospital Dr.Alejandro Korn'),
(2, 'descripcion', 'descripcion inicial'),
(3, 'email', 'email@inicial.com'),
(4, 'limite', '20'),
(5, 'habilitado', '1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `consulta`
--

CREATE TABLE `consulta` (
  `id` int(11) NOT NULL,
  `paciente_id` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `motivo_id` int(11) NOT NULL,
  `derivacion_id` int(11) DEFAULT NULL,
  `articulacion_con_instituciones` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `internacion` tinyint(1) NOT NULL DEFAULT '0',
  `diagnostico` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `observaciones` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tratamiento_farmacologico_id` int(11) DEFAULT NULL,
  `acompanamiento_id` int(11) DEFAULT NULL,
  `borrado` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `genero`
--

CREATE TABLE `genero` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `genero`
--

INSERT INTO `genero` (`id`, `nombre`) VALUES
(1, 'Masculino'),
(2, 'Femenino'),
(3, 'Otro');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `institucion`
--

CREATE TABLE `institucion` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `director` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `direccion` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `telefono` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `localidad_id` int(11) NOT NULL,
  `tipo_institucion_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `institucion`
--

INSERT INTO `institucion` (`id`, `nombre`, `director`, `direccion`, `telefono`, `localidad_id`, `tipo_institucion_id`) VALUES
(1, 'Bahía blanca inst 1', 'director Bahía1', 'calle 1 y 1', '123457891', 1, 1),
(2, 'Bahía blanca inst 2', 'director Bahía2', 'calle 1 y 2', '123457892', 1, 2),
(3, 'Bahía blanca inst 3', 'director Bahía3', 'calle 1 y 3', '123457893', 1, 1),
(4, 'Bahía blanca inst 4', 'director Bahía4', 'calle 1 y 4', '123457894', 1, 2),
(5, 'Bahía blanca inst 5', 'director Bahía5', 'calle 1 y 5', '123457895', 1, 1),
(6, 'Pehuajó inst 1', 'director Pehua1', 'calle 2 y 1', '123457891', 2, 1),
(7, 'Pehuajó inst 2', 'director Pehua2', 'calle 2 y 2', '123457892', 2, 2),
(8, 'Pehuajó inst 3', 'director Pehua3', 'calle 2 y 3', '123457893', 2, 1),
(9, 'Pehuajó inst 4', 'director Pehua4', 'calle 2 y 4', '123457894', 2, 2),
(10, 'Pehuajó inst 5', 'director Pehua5', 'calle 2 y 5', '123457895', 2, 1),
(11, 'Junín inst 1', 'director Ju1', 'calle 3 y 1', '123457891', 3, 1),
(12, 'Junín inst 2', 'director Ju2', 'calle 3 y 2', '123457892', 3, 2),
(13, 'Junín inst 3', 'director Ju3', 'calle 3 y 3', '123457893', 3, 1),
(14, 'Junín inst 4', 'director Ju4', 'calle 3 y 4', '123457894', 3, 2),
(15, 'Junín inst 5', 'director Ju5', 'calle 3 y 5', '123457895', 3, 1),
(16, 'Pergamino inst 1', 'director Per1', 'calle 4 y 1', '123457891', 4, 1),
(17, 'Pergamino inst 2', 'director Per2', 'calle 4 y 2', '123457892', 4, 2),
(18, 'Pergamino inst 3', 'director Per3', 'calle 4 y 3', '123457893', 4, 1),
(19, 'Pergamino inst 4', 'director Per4', 'calle 4 y 4', '123457894', 4, 2),
(20, 'Pergamino inst 5', 'director Per5', 'calle 4 y 5', '123457895', 4, 1),
(21, 'General San Martín inst 1', 'director Martín1', 'calle 5 y 1', '123457891', 5, 1),
(22, 'General San Martín inst 2', 'director Martín2', 'calle 5 y 2', '123457892', 5, 2),
(23, 'General San Martín inst 3', 'director Martín3', 'calle 5 y 3', '123457893', 5, 1),
(24, 'General San Martín inst 4', 'director Martín4', 'calle 5 y 4', '123457894', 5, 2),
(25, 'General San Martín inst 5', 'director Martín5', 'calle 5 y 5', '123457895', 5, 1),
(26, 'Lomas de Zamora inst 1', 'director Zamora1', 'calle 6 y 1', '123457891', 6, 1),
(27, 'Lomas de Zamora inst 2', 'director Zamora2', 'calle 6 y 2', '123457892', 6, 2),
(28, 'Lomas de Zamora inst 3', 'director Zamora3', 'calle 6 y 3', '123457893', 6, 1),
(29, 'Lomas de Zamora inst 4', 'director Zamora4', 'calle 6 y 4', '123457894', 6, 2),
(30, 'Lomas de Zamora inst 5', 'director Zamora5', 'calle 6 y 5', '123457895', 6, 1),
(31, 'General Rodríguez inst 1', 'director Rodríguez1', 'calle 7 y 1', '123457891', 7, 1),
(32, 'General Rodríguez inst 2', 'director Rodríguez2', 'calle 7 y 2', '123457892', 7, 2),
(33, 'General Rodríguez inst 3', 'director Rodríguez3', 'calle 7 y 3', '123457893', 7, 1),
(34, 'General Rodríguez inst 4', 'director Rodríguez4', 'calle 7 y 4', '123457894', 7, 2),
(35, 'General Rodríguez inst 5', 'director Rodríguez5', 'calle 7 y 5', '123457895', 7, 1),
(36, 'Mar de Plata inst 1', 'director Plata1', 'calle 8 y 1', '123457891', 8, 1),
(37, 'Mar de Plata inst 2', 'director Plata2', 'calle 8 y 2', '123457892', 8, 2),
(38, 'Mar de Plata inst 3', 'director Plata3', 'calle 8 y 3', '123457893', 8, 1),
(39, 'Mar de Plata inst 4', 'director Plata4', 'calle 8 y 4', '123457894', 8, 2),
(40, 'Mar de Plata inst 5', 'director Plata5', 'calle 8 y 5', '123457895', 8, 1),
(41, 'Azul inst 1', 'director Azul1', 'calle 9 y 1', '123457891', 9, 1),
(42, 'Azul inst 2', 'director Azul2', 'calle 9 y 2', '123457892', 9, 2),
(43, 'Azul inst 3', 'director Azul3', 'calle 9 y 3', '123457893', 9, 1),
(44, 'Azul inst 4', 'director Azul4', 'calle 9 y 4', '123457894', 9, 2),
(45, 'Azul inst 5', 'director Azul5', 'calle 9 y 5', '123457895', 9, 1),
(46, 'Chivilcoy inst 1', 'director Chi1', 'calle 10 y 1', '123457891', 10, 1),
(47, 'Chivilcoy inst 2', 'director Chi2', 'calle 10 y 2', '123457892', 10, 2),
(48, 'Chivilcoy inst 3', 'director Chi3', 'calle 10 y 3', '123457893', 10, 1),
(49, 'Chivilcoy inst 4', 'director Chi4', 'calle 10 y 4', '123457894', 10, 2),
(50, 'Chivilcoy inst 5', 'director Chi5', 'calle 10 y 5', '123457895', 10, 1),
(51, 'Ensenada inst 1', 'director Sena1', 'calle 11 y 1', '123457891', 11, 1),
(52, 'Ensenada inst 2', 'director Sena2', 'calle 11 y 2', '123457892', 11, 2),
(53, 'Ensenada inst 3', 'director Sena3', 'calle 11 y 3', '123457893', 11, 1),
(54, 'Ensenada inst 4', 'director Sena4', 'calle 11 y 4', '123457894', 11, 2),
(55, 'Ensenada inst 5', 'director Sena5', 'calle 11 y 5', '123457895', 11, 1),
(56, 'La Matanza inst 1', 'director Mata1', 'calle 12 y 1', '123457891', 12, 1),
(57, 'La Matanza inst 2', 'director Mata2', 'calle 12 y 2', '123457892', 12, 2),
(58, 'La Matanza inst 3', 'director Mata3', 'calle 12 y 3', '123457893', 12, 1),
(59, 'La Matanza inst 4', 'director Mata4', 'calle 12 y 4', '123457894', 12, 2),
(60, 'La Matanza inst 5', 'director Mata5', 'calle 12 y 5', '123457895', 12, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `localidad`
--

CREATE TABLE `localidad` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `coordenadas` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `partido_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `localidad`
--

INSERT INTO `localidad` (`id`, `nombre`, `coordenadas`, `partido_id`) VALUES
(1, 'Bahía Blanca', '-38.737580,-62.261030', 1),
(2, 'Pehuajó', '-35.824986,-61.894783', 2),
(3, 'Junín', '-34.592618,-60.950547', 3),
(4, 'Pergamino', '-33.895489,-60.577274', 4),
(5, 'General San Martín', '-34.580379,-58.538680', 5),
(6, 'Lomas de Zamora', '-34.763051,-58.433061', 6),
(7, 'General Rodríguez', '-34.616907,-58.940653', 7),
(8, 'Mar de Plata', '-38.028766,-57.604634', 8),
(9, 'Azul', '-36.779428,-59.854858', 9),
(10, 'Chivilcoy', '-34.891956,-60.037713', 10),
(11, 'Ensenada', '-34.843834,-57.941167', 11),
(12, 'La Matanza', '-34.773338,-58.644947', 12);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2016_06_01_000001_create_oauth_auth_codes_table', 1),
(4, '2016_06_01_000002_create_oauth_access_tokens_table', 1),
(5, '2016_06_01_000003_create_oauth_refresh_tokens_table', 1),
(6, '2016_06_01_000004_create_oauth_clients_table', 1),
(7, '2016_06_01_000005_create_oauth_personal_access_clients_table', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `motivo_consulta`
--

CREATE TABLE `motivo_consulta` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `motivo_consulta`
--

INSERT INTO `motivo_consulta` (`id`, `nombre`) VALUES
(1, 'Receta Médica'),
(2, 'Control por Guardia'),
(3, 'Consulta'),
(4, 'Intento de Suicidio'),
(5, 'Interconsulta'),
(6, 'Otras');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `oauth_access_tokens`
--

CREATE TABLE `oauth_access_tokens` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `client_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `scopes` text COLLATE utf8mb4_unicode_ci,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `oauth_access_tokens`
--

INSERT INTO `oauth_access_tokens` (`id`, `user_id`, `client_id`, `name`, `scopes`, `revoked`, `created_at`, `updated_at`, `expires_at`) VALUES
('8da11f47581a1413d958bbb7551e9ab3616f5c258cea08c77f5cd144a7ef3d3b9299e057ea0c8d20', 3, 2, NULL, '[]', 0, '2019-05-15 22:53:51', '2019-05-15 22:53:51', '2020-05-15 19:53:51'),
('de19aa19eb2922c4aa9d95627826239dce6905057bb60dbaf34639a24a6c8197949d2760d16698c9', 3, 2, NULL, '[]', 0, '2019-05-15 23:11:16', '2019-05-15 23:11:16', '2020-05-15 20:11:16');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `oauth_auth_codes`
--

CREATE TABLE `oauth_auth_codes` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(11) NOT NULL,
  `client_id` int(10) UNSIGNED NOT NULL,
  `scopes` text COLLATE utf8mb4_unicode_ci,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `oauth_clients`
--

CREATE TABLE `oauth_clients` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `secret` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `redirect` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `personal_access_client` tinyint(1) NOT NULL,
  `password_client` tinyint(1) NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `oauth_clients`
--

INSERT INTO `oauth_clients` (`id`, `user_id`, `name`, `secret`, `redirect`, `personal_access_client`, `password_client`, `revoked`, `created_at`, `updated_at`) VALUES
(1, NULL, 'Laravel Personal Access Client', 'S7pBPg3qAyrilrBz2zyh4um2piOsf4c8KPKq63xH', 'http://localhost', 1, 0, 0, '2019-05-15 20:44:21', '2019-05-15 20:44:21'),
(2, NULL, 'Laravel Password Grant Client', '8NerETlYAm7SwwXsaDQSW1YAsjli1MFkL6QdO9wl', 'http://localhost', 0, 1, 0, '2019-05-15 20:44:21', '2019-05-15 20:44:21');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `oauth_personal_access_clients`
--

CREATE TABLE `oauth_personal_access_clients` (
  `id` int(10) UNSIGNED NOT NULL,
  `client_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `oauth_personal_access_clients`
--

INSERT INTO `oauth_personal_access_clients` (`id`, `client_id`, `created_at`, `updated_at`) VALUES
(1, 1, '2019-05-15 20:44:21', '2019-05-15 20:44:21');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `oauth_refresh_tokens`
--

CREATE TABLE `oauth_refresh_tokens` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `access_token_id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `oauth_refresh_tokens`
--

INSERT INTO `oauth_refresh_tokens` (`id`, `access_token_id`, `revoked`, `expires_at`) VALUES
('303003711b2fcf71267a46a0c47228c8ebc3b7751d1459a692474b06bb27a7e3f5bedf23c16cb054', 'de19aa19eb2922c4aa9d95627826239dce6905057bb60dbaf34639a24a6c8197949d2760d16698c9', 0, '2020-05-15 20:11:16'),
('7daaba1d60dd4ef368d6cc2c3e89ef4f6b44590c6011ff540adffc4c70f21d129801e26d5fd8208c', '8da11f47581a1413d958bbb7551e9ab3616f5c258cea08c77f5cd144a7ef3d3b9299e057ea0c8d20', 0, '2020-05-15 19:53:51');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `obra_social`
--

CREATE TABLE `obra_social` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `obra_social`
--

INSERT INTO `obra_social` (`id`, `nombre`) VALUES
(1, 'OSDE'),
(2, 'Sancor Salud'),
(3, 'Medicus'),
(4, 'Medifu00e9'),
(5, 'Galeno'),
(6, 'Accord Salud'),
(7, 'OMINT'),
(8, 'Swiss Medical'),
(9, 'AcaSalud'),
(10, 'Bristol Medicine'),
(11, 'OSECAC'),
(12, 'Uniu00f3n Personal'),
(13, 'OSPACP'),
(14, 'OSDEPYM'),
(15, 'Luis Pasteur'),
(16, 'OSMEDICA'),
(17, 'IOMA'),
(18, 'OSPJN'),
(19, 'OSSSB'),
(20, 'OSPEPBA'),
(21, 'OSPE');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `paciente`
--

CREATE TABLE `paciente` (
  `id` int(11) NOT NULL,
  `apellido` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `nombre` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `fecha_nac` date NOT NULL,
  `lugar_nac` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `localidad_id` int(11) DEFAULT NULL,
  `partido_id` int(11) DEFAULT NULL,
  `region_sanitaria_id` int(11) DEFAULT NULL,
  `domicilio` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `genero_id` int(11) DEFAULT NULL,
  `tiene_documento` tinyint(1) NOT NULL DEFAULT '1',
  `tipo_doc_id` int(11) DEFAULT NULL,
  `numero` int(11) NOT NULL,
  `tel` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `nro_historia_clinica` int(11) DEFAULT NULL,
  `nro_carpeta` int(11) DEFAULT NULL,
  `obra_social_id` int(11) DEFAULT NULL,
  `borrado` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `partido`
--

CREATE TABLE `partido` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `region_sanitaria_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `partido`
--

INSERT INTO `partido` (`id`, `nombre`, `region_sanitaria_id`) VALUES
(1, 'Bahía Blanca', 1),
(2, 'Pehuajó', 2),
(3, 'Junín', 3),
(4, 'Pergamino', 4),
(5, 'General San Martín', 5),
(6, 'Lomas de Zamora', 6),
(7, 'General Rodríguez', 7),
(8, 'Mar de Plata', 8),
(9, 'Azul', 9),
(10, 'Chivilcoy', 10),
(11, 'Ensenada', 11),
(12, 'La Matanza', 12);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permiso`
--

CREATE TABLE `permiso` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `admin` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `permiso`
--

INSERT INTO `permiso` (`id`, `nombre`, `admin`) VALUES
(1, 'consulta_index', 0),
(2, 'consulta_new', 0),
(3, 'consulta_update', 0),
(4, 'consulta_show', 0),
(5, 'consulta_destroy', 0),
(6, 'paciente_index', 0),
(7, 'paciente_new', 0),
(8, 'paciente_update', 0),
(9, 'paciente_show', 0),
(10, 'paciente_destroy', 0),
(11, 'usuario_index', 1),
(12, 'usuario_new', 1),
(13, 'usuario_update', 1),
(14, 'usuario_administrarRoles', 1),
(15, 'usuario_activarBloquear', 1),
(16, 'rol_index', 1),
(17, 'rol_new', 1),
(18, 'rol_update', 1),
(19, 'rol_destroy', 1),
(20, 'permiso_index', 1),
(21, 'permiso_new', 1),
(22, 'permiso_update', 1),
(23, 'permiso_destroy', 1),
(24, 'reporte_index', 1),
(25, 'configuracion_index', 1),
(26, 'configuracion_update', 1),
(27, 'institucion_index', 1),
(28, 'institucion_new', 1),
(29, 'institucion_update', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `region_sanitaria`
--

CREATE TABLE `region_sanitaria` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `region_sanitaria`
--

INSERT INTO `region_sanitaria` (`id`, `nombre`) VALUES
(1, 'I'),
(2, 'II'),
(3, 'III'),
(4, 'IV'),
(5, 'V'),
(6, 'VI'),
(7, 'VII'),
(8, 'VIII'),
(9, 'IX'),
(10, 'X'),
(11, 'XI'),
(12, 'XII');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol`
--

CREATE TABLE `rol` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `rol`
--

INSERT INTO `rol` (`id`, `nombre`) VALUES
(1, 'Administrador'),
(2, 'EquipoDeGuardia');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol_tiene_permiso`
--

CREATE TABLE `rol_tiene_permiso` (
  `rol_id` int(11) NOT NULL,
  `permiso_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `rol_tiene_permiso`
--

INSERT INTO `rol_tiene_permiso` (`rol_id`, `permiso_id`) VALUES
(1, 1),
(1, 5),
(1, 6),
(1, 10),
(1, 11),
(1, 12),
(1, 13),
(1, 14),
(1, 15),
(1, 16),
(1, 17),
(1, 18),
(1, 19),
(1, 20),
(1, 21),
(1, 22),
(1, 23),
(1, 24),
(1, 25),
(1, 26),
(1, 27),
(1, 28),
(1, 29),
(2, 1),
(2, 2),
(2, 3),
(2, 4),
(2, 6),
(2, 7),
(2, 8),
(2, 9);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_documento`
--

CREATE TABLE `tipo_documento` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `tipo_documento`
--

INSERT INTO `tipo_documento` (`id`, `nombre`) VALUES
(1, 'DNI'),
(2, 'LC'),
(3, 'CI'),
(4, 'LE'),
(5, 'Pasaporte');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_institucion`
--

CREATE TABLE `tipo_institucion` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `tipo_institucion`
--

INSERT INTO `tipo_institucion` (`id`, `nombre`) VALUES
(1, 'Hospital'),
(2, 'Comisaría');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tratamiento_farmacologico`
--

CREATE TABLE `tratamiento_farmacologico` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `tratamiento_farmacologico`
--

INSERT INTO `tratamiento_farmacologico` (`id`, `nombre`) VALUES
(1, 'Mañana'),
(2, 'Tarde'),
(3, 'Noche');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id` int(11) NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '0',
  `updated_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `first_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `remember_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id`, `email`, `username`, `password`, `activo`, `updated_at`, `created_at`, `first_name`, `last_name`, `remember_token`) VALUES
(1, 'admin@admin.com', 'admin', '$2y$10$7hB/B/cLnnDf0e2Bk2tnC.oUDrb1LDYd.ctTXsW6Tu0yQo5sIKA7i', 1, NULL, NULL, 'admin', 'admin', NULL),
(2, 'guardia@guardia.com', 'equipodeguardia', '$2y$10$H1jXRhl8CuCGmmLODtubSuR8RpHna17GqjbnBfJAw91LNUFkTVS0q', 1, NULL, NULL, 'guardia', 'guardia', NULL),
(3, 'superAdmin@admin.com', 'superadmin', '$2y$10$H1jXRhl8CuCGmmLODtubSuR8RpHna17GqjbnBfJAw91LNUFkTVS0q', 1, NULL, NULL, 'superadmin', 'superadmin', 'JafukrBEy8u6FkKEyF7BSJXALncjq2UEtzNLkDoDQ3M35SegZBoBOW3KNOPb');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario_tiene_rol`
--

CREATE TABLE `usuario_tiene_rol` (
  `usuario_id` int(11) NOT NULL,
  `rol_id` int(11) NOT NULL,
  `eliminado` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `usuario_tiene_rol`
--

INSERT INTO `usuario_tiene_rol` (`usuario_id`, `rol_id`, `eliminado`) VALUES
(1, 1, 0),
(2, 2, 0),
(3, 1, 0),
(3, 2, 0);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `acompanamiento`
--
ALTER TABLE `acompanamiento`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `configuracion`
--
ALTER TABLE `configuracion`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `consulta`
--
ALTER TABLE `consulta`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_paciente_id` (`paciente_id`),
  ADD KEY `FK_motivo_id` (`motivo_id`),
  ADD KEY `FK_derivacion_id` (`derivacion_id`),
  ADD KEY `FK_tratamiento_farmacologico_id` (`tratamiento_farmacologico_id`),
  ADD KEY `FK_acompanamiento_id` (`acompanamiento_id`);

--
-- Indices de la tabla `genero`
--
ALTER TABLE `genero`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `institucion`
--
ALTER TABLE `institucion`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_institucion_localidad_id` (`localidad_id`),
  ADD KEY `FK_tipo_institucion_id` (`tipo_institucion_id`);

--
-- Indices de la tabla `localidad`
--
ALTER TABLE `localidad`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_localidad_partido_id` (`partido_id`);

--
-- Indices de la tabla `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `motivo_consulta`
--
ALTER TABLE `motivo_consulta`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `oauth_access_tokens`
--
ALTER TABLE `oauth_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_access_tokens_user_id_index` (`user_id`);

--
-- Indices de la tabla `oauth_auth_codes`
--
ALTER TABLE `oauth_auth_codes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `oauth_clients`
--
ALTER TABLE `oauth_clients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_clients_user_id_index` (`user_id`);

--
-- Indices de la tabla `oauth_personal_access_clients`
--
ALTER TABLE `oauth_personal_access_clients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_personal_access_clients_client_id_index` (`client_id`);

--
-- Indices de la tabla `oauth_refresh_tokens`
--
ALTER TABLE `oauth_refresh_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_refresh_tokens_access_token_id_index` (`access_token_id`);

--
-- Indices de la tabla `obra_social`
--
ALTER TABLE `obra_social`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `paciente`
--
ALTER TABLE `paciente`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_region_sanitaria_id` (`region_sanitaria_id`),
  ADD KEY `FK_obra_social_id` (`obra_social_id`),
  ADD KEY `FK_tipo_doc_id` (`tipo_doc_id`),
  ADD KEY `FK_localidad_id` (`localidad_id`),
  ADD KEY `FK_partido_id` (`partido_id`),
  ADD KEY `FK_genero_id` (`genero_id`);

--
-- Indices de la tabla `partido`
--
ALTER TABLE `partido`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_partido_region_sanitaria_id` (`region_sanitaria_id`);

--
-- Indices de la tabla `permiso`
--
ALTER TABLE `permiso`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `region_sanitaria`
--
ALTER TABLE `region_sanitaria`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `rol`
--
ALTER TABLE `rol`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `rol_tiene_permiso`
--
ALTER TABLE `rol_tiene_permiso`
  ADD PRIMARY KEY (`rol_id`,`permiso_id`),
  ADD KEY `FK_permiso_id` (`permiso_id`);

--
-- Indices de la tabla `tipo_documento`
--
ALTER TABLE `tipo_documento`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tipo_institucion`
--
ALTER TABLE `tipo_institucion`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tratamiento_farmacologico`
--
ALTER TABLE `tratamiento_farmacologico`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuario_tiene_rol`
--
ALTER TABLE `usuario_tiene_rol`
  ADD PRIMARY KEY (`usuario_id`,`rol_id`),
  ADD KEY `FK_rol_utp_id` (`rol_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `acompanamiento`
--
ALTER TABLE `acompanamiento`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT de la tabla `configuracion`
--
ALTER TABLE `configuracion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT de la tabla `consulta`
--
ALTER TABLE `consulta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `genero`
--
ALTER TABLE `genero`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT de la tabla `institucion`
--
ALTER TABLE `institucion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;
--
-- AUTO_INCREMENT de la tabla `localidad`
--
ALTER TABLE `localidad`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT de la tabla `motivo_consulta`
--
ALTER TABLE `motivo_consulta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT de la tabla `oauth_clients`
--
ALTER TABLE `oauth_clients`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT de la tabla `oauth_personal_access_clients`
--
ALTER TABLE `oauth_personal_access_clients`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de la tabla `obra_social`
--
ALTER TABLE `obra_social`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;
--
-- AUTO_INCREMENT de la tabla `paciente`
--
ALTER TABLE `paciente`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `partido`
--
ALTER TABLE `partido`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT de la tabla `permiso`
--
ALTER TABLE `permiso`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;
--
-- AUTO_INCREMENT de la tabla `region_sanitaria`
--
ALTER TABLE `region_sanitaria`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT de la tabla `rol`
--
ALTER TABLE `rol`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT de la tabla `tipo_documento`
--
ALTER TABLE `tipo_documento`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT de la tabla `tipo_institucion`
--
ALTER TABLE `tipo_institucion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT de la tabla `tratamiento_farmacologico`
--
ALTER TABLE `tratamiento_farmacologico`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `consulta`
--
ALTER TABLE `consulta`
  ADD CONSTRAINT `FK_acompanamiento_id` FOREIGN KEY (`acompanamiento_id`) REFERENCES `acompanamiento` (`id`),
  ADD CONSTRAINT `FK_derivacion_id` FOREIGN KEY (`derivacion_id`) REFERENCES `institucion` (`id`),
  ADD CONSTRAINT `FK_motivo_id` FOREIGN KEY (`motivo_id`) REFERENCES `motivo_consulta` (`id`),
  ADD CONSTRAINT `FK_paciente_id` FOREIGN KEY (`paciente_id`) REFERENCES `paciente` (`id`),
  ADD CONSTRAINT `FK_tratamiento_farmacologico_id` FOREIGN KEY (`tratamiento_farmacologico_id`) REFERENCES `tratamiento_farmacologico` (`id`);

--
-- Filtros para la tabla `institucion`
--
ALTER TABLE `institucion`
  ADD CONSTRAINT `FK_institucion_localidad_id` FOREIGN KEY (`localidad_id`) REFERENCES `localidad` (`id`),
  ADD CONSTRAINT `FK_tipo_institucion_id` FOREIGN KEY (`tipo_institucion_id`) REFERENCES `tipo_institucion` (`id`);

--
-- Filtros para la tabla `localidad`
--
ALTER TABLE `localidad`
  ADD CONSTRAINT `FK_localidad_partido_id` FOREIGN KEY (`partido_id`) REFERENCES `partido` (`id`);

--
-- Filtros para la tabla `paciente`
--
ALTER TABLE `paciente`
  ADD CONSTRAINT `FK_genero_id` FOREIGN KEY (`genero_id`) REFERENCES `genero` (`id`),
  ADD CONSTRAINT `FK_localidad_id` FOREIGN KEY (`localidad_id`) REFERENCES `localidad` (`id`),
  ADD CONSTRAINT `FK_obra_social_id` FOREIGN KEY (`obra_social_id`) REFERENCES `obra_social` (`id`),
  ADD CONSTRAINT `FK_partido_id` FOREIGN KEY (`partido_id`) REFERENCES `partido` (`id`),
  ADD CONSTRAINT `FK_region_sanitaria_id` FOREIGN KEY (`region_sanitaria_id`) REFERENCES `region_sanitaria` (`id`),
  ADD CONSTRAINT `FK_tipo_doc_id` FOREIGN KEY (`tipo_doc_id`) REFERENCES `tipo_documento` (`id`);

--
-- Filtros para la tabla `partido`
--
ALTER TABLE `partido`
  ADD CONSTRAINT `FK_partido_region_sanitaria_id` FOREIGN KEY (`region_sanitaria_id`) REFERENCES `region_sanitaria` (`id`);

--
-- Filtros para la tabla `rol_tiene_permiso`
--
ALTER TABLE `rol_tiene_permiso`
  ADD CONSTRAINT `FK_permiso_id` FOREIGN KEY (`permiso_id`) REFERENCES `permiso` (`id`),
  ADD CONSTRAINT `FK_rol_id` FOREIGN KEY (`rol_id`) REFERENCES `rol` (`id`);

--
-- Filtros para la tabla `usuario_tiene_rol`
--
ALTER TABLE `usuario_tiene_rol`
  ADD CONSTRAINT `FK_rol_utp_id` FOREIGN KEY (`rol_id`) REFERENCES `rol` (`id`),
  ADD CONSTRAINT `FK_usuario_utp_id` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
