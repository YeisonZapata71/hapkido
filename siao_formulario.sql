-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 08-03-2026 a las 21:38:32
-- Versión del servidor: 5.7.23-23
-- Versión de PHP: 8.1.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `siao_formulario`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `afiliados_siao`
--

CREATE TABLE `afiliados_siao` (
  `id` int(11) NOT NULL,
  `nombre_completo` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `documento` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `sexo` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `celular` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `correo` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `direccion` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ciudad` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `eps` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tipo_sangre` varchar(5) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nombre_acudiente` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `horario` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `grado_cinturon` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fecha_inscripcion` date DEFAULT NULL,
  `foto_nombre` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `creado_en` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `rol` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'afiliado',
  `es_instructor` tinyint(1) NOT NULL DEFAULT '0',
  `usuario_id` int(11) DEFAULT NULL,
  `estado_pago` enum('pendiente','pagado','vencido') COLLATE utf8_unicode_ci DEFAULT 'pendiente',
  `observaciones` text COLLATE utf8_unicode_ci,
  `activo` tinyint(1) DEFAULT '1',
  `fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_actualizacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `afiliados_siao`
--

INSERT INTO `afiliados_siao` (`id`, `nombre_completo`, `documento`, `fecha_nacimiento`, `sexo`, `celular`, `correo`, `direccion`, `ciudad`, `eps`, `tipo_sangre`, `nombre_acudiente`, `horario`, `grado_cinturon`, `fecha_inscripcion`, `foto_nombre`, `creado_en`, `rol`, `es_instructor`, `usuario_id`, `estado_pago`, `observaciones`, `activo`, `fecha_creacion`, `fecha_actualizacion`) VALUES
(1, 'Yeison Dariel Zapata Monsalve', '1013536971', '1992-09-03', 'Masculino', '3232897785', 'aleyei.yz@gmail.com', 'Calle 41 SUR # 81 90 Torre 3 apto 512', 'Medellín', 'SURA', 'O+', '', 'Avanzados y Negros', '1th Dan', '2025-01-20', '1753039778_foto_carne.png', '2025-04-21 00:08:12', 'instructor', 1, 9, 'pendiente', NULL, 1, '2025-08-20 01:55:54', '2026-01-18 01:21:19'),
(59, '﻿Jorge Andres Acosta Franco', '71746583', '1974-06-20', 'M', '3113839556', 'andresacosta-personal@hotmail.com', 'CALLE 47 F No. 85 28', 'Medellín', 'Sura', 'A+', '', 'Miércoles y Viernes 07:30 p.m.;Avanzados y Negros', 'Marrón-Negro', '2025-01-20', 'foto_681eccba0b152.jpg', '2025-05-01 21:57:53', 'instructor', 0, 7, 'pendiente', NULL, 1, '2025-08-20 01:55:54', '2026-02-19 16:39:50'),
(60, '﻿Samuel Londoño Valencia', '1032029390', '2016-11-10', 'Masculino', '3217148057', 'karina27k21@hotmail.com', 'Cra 94#78A-82', 'Medellín', 'Salud Total', 'O+', 'Karina Leanis Valencia toro', 'Sede Robledo', 'Naranja', '2025-01-20', NULL, '2025-05-01 22:39:12', 'afiliado', 0, NULL, 'pendiente', NULL, 1, '2025-08-20 01:55:54', '2026-01-22 23:24:50'),
(61, 'Miguel Montoya Santamaría', '1020307622', '2010-07-11', 'Masculino', '3043192927', 'isabelsm31@gmail.com', 'Calle 84 53 49 apto 304', 'Itagui', 'Sura', 'O+', 'Isabel Cristina Santamaria Montoya', 'Sábados 12:00 m', 'Verde', '2025-01-20', NULL, '2025-05-01 22:39:12', 'afiliado', 0, NULL, 'pendiente', NULL, 1, '2025-08-20 01:55:54', '2025-08-20 01:55:54'),
(62, 'Juan Sebastian Marin Gil', '1033193939', '2012-11-15', 'M', '3152548675', 'juansebastianmarin454@gmail.com', 'Clle 51 #72-25', 'Medellín', 'Sura', 'O+', 'Rubiel Marin Marin', 'Miércoles y Viernes 07:30 p.m.', 'Azul', '2025-01-20', NULL, '2025-05-01 22:39:12', 'afiliado', 0, NULL, 'pendiente', NULL, 1, '2025-08-20 01:55:54', '2026-02-19 16:38:29'),
(63, 'Juan Jose Marin Gil', '1033193940', '2012-11-15', 'M', '3143335849', 'juanjosemaringil5@gmail.com', 'Calle 51 #72-25', 'Medellín', 'Sura', 'O+', 'Rubiel Marin ', 'Miércoles y Viernes 07:30 p.m.', 'Azul', '2025-01-20', NULL, '2025-05-01 22:39:12', 'afiliado', 0, NULL, 'pendiente', NULL, 1, '2025-08-20 01:55:54', '2026-02-19 16:38:20'),
(64, 'Mauricio López Jaramillo', '71748911', '1974-09-17', 'Masculino', '3148217793', 'aca.mlj@gmail.com', 'calle 28 sur # 43A - 70', 'Envigado', 'Sura', 'O+', '', 'Avanzados y Negros', '1th Dan', '2025-01-20', 'foto_681ecdaba000b.jpg', '2025-05-01 22:39:12', 'instructor', 0, 8, 'pendiente', NULL, 1, '2025-08-20 01:55:54', '2025-08-20 01:55:54'),
(65, 'Juan Diego Cock Ramírez', '71777460', '1976-03-02', 'Masculino', '3003204401', 'jcockram@hotmail.com', 'Carrera 47 #27 sur 59 apto 606', 'Envigado', 'Nueva', 'O+', '', 'Sábados 12:00 m', 'Marrón ', '2025-01-20', NULL, '2025-05-01 22:39:12', 'afiliado', 0, NULL, 'pendiente', NULL, 1, '2025-08-20 01:55:54', '2025-08-20 01:55:54'),
(66, 'Juan Camilo Cock', '1035000974', '2012-12-06', 'Masculino', '3003204401', 'jcockram@hotmail.com', 'Carrera 47 #27 Sur 59 Apto 606', 'Envigado', 'Nueva', 'O+', 'Juan Diego Cock Ramírez', 'Sábados 12:00 m', 'Naranja', '2025-01-20', NULL, '2025-05-01 22:39:12', 'afiliado', 0, NULL, 'pendiente', NULL, 1, '2025-08-20 01:55:54', '2025-08-20 01:55:54'),
(70, 'Elvis Joan Morales Quiroz', '1036629890', '1989-11-15', 'Masculino', '3244583951', 'morales.elvisjoan@mail.com', 'CR 95 N 78 a 59', 'Medellín', 'Sura', 'A+', '', 'Lunes, Miércoles y Viernes 06:00 a.m., Avanzados y Negros', 'Blanco', '2025-01-20', 'foto_681ece685ddd5.jpg', '2025-05-01 22:39:12', 'instructor', 0, 5, 'pendiente', NULL, 1, '2025-08-20 01:55:54', '2025-08-20 01:55:54'),
(71, 'Simón Echeverri Cifuentes ', '1020305691', '2009-12-20', 'Masculino', '3005280981', 'astridelenacifuentes772@gmail.com', 'Cra 83a 33 78 apartamento 701', 'Medellín ', 'Sura', 'O+', 'Astrid Elena Cifuentes Alvarez ', 'Sábados 12:00 m', 'Naranja', '2025-01-20', NULL, '2025-05-01 22:39:12', 'afiliado', 0, NULL, 'pendiente', NULL, 1, '2025-08-20 01:55:54', '2025-08-20 01:55:54'),
(72, 'Jaime Grisales Marín', '98551769', '1970-08-30', 'Masculino', '3004003277', 'jagrisalingdao@gmail.com', 'Calle 65 # 55 30', 'Medellín', 'SURA', 'O+', '', 'Avanzados y Negros', '5th Dan', '2025-01-20', 'foto_681ec5a2e8c5e.jpg', '2025-05-01 22:39:12', 'instructor', 0, 6, 'pendiente', NULL, 1, '2025-08-20 01:55:54', '2026-01-18 01:48:49'),
(73, 'Héctor Giraldo', '98573501', '1968-06-12', 'Masculino', '3146666240', 'hectorigami@hotmail.com', 'Cra 62 # 38A sur 12', 'Medellin', 'Sura', 'O+', '', 'Avanzados y Negros', '2th Dan', '2025-01-20', NULL, '2025-05-01 22:39:12', 'afiliado', 0, NULL, 'pendiente', NULL, 1, '2025-08-20 01:55:54', '2025-08-20 01:55:54'),
(74, 'Andres Felipe Arce Agudelo ', '1058079173', '2010-08-22', 'Masculino', '3193353284', 'jandreaagudelo@gmail.com', 'Cra 94 #77f34 interior 206', 'Medellín ', 'Sanidad militar', 'A+', 'Andrea Agudelo ', 'Martes y jueves - 6:00 a.m.', 'Amarillo', '2025-01-20', NULL, '2025-05-01 22:39:12', 'afiliado', 0, NULL, 'pendiente', NULL, 1, '2025-08-20 01:55:54', '2025-08-20 01:55:54'),
(75, 'Santiago Dávila Vásquez', '1152454286', '1995-07-13', 'M', '3044121506', 'sandavila2024@hotmail.com', 'Tr 15 #79-120', 'Medellín', 'Sura', 'O+', '', 'Martes y Jueves 05:00 p.m.;Avanzados y Negros', 'Marrón-Negro', '2025-01-20', 'foto_681ecf0917ccc.jpg', '2025-05-01 22:39:12', 'instructor', 0, 11, 'pendiente', NULL, 1, '2025-08-20 01:55:54', '2026-02-03 03:29:24'),
(76, 'Juan David Posada Rueda', '71633447', '1963-06-14', 'Masculino', '3122967731', 'jdposadar@gmail.com', 'Cll 71 sur # 43 B 15', 'Sabaneta', 'Sura', 'O+', '', 'Avanzados y Negros', '2th Dan', '2025-01-20', NULL, '2025-05-01 22:39:12', 'afiliado', 0, NULL, 'pendiente', NULL, 1, '2025-08-20 01:55:54', '2025-08-20 01:55:54'),
(77, 'Juan Carlos Prisco Duque', '3362459', '1977-10-10', 'Masculino', '3177809717', 'jpriscoduque@gmail.com', 'Calle 54 #53-36 Interior 203', 'Medellín', 'Sisbén', 'A+', '', 'Martes y Jueves 05:00 p.m.', 'Morado', '2025-01-20', NULL, '2025-05-01 22:39:12', 'afiliado', 0, NULL, 'pendiente', NULL, 1, '2025-08-20 01:55:54', '2025-08-20 01:55:54'),
(78, 'Luciana Castañeda Gallego', '1032029980', '2017-05-22', 'Femenino', '3146792357', 'gallegovanesa23@gmail.com', 'Calle 79 c # 95 a 45', 'Medellín', 'SURA', 'A+', 'Vanesa gallego', 'Sede Robledo', 'Amarillo', '2025-01-20', NULL, '2025-05-01 22:39:12', 'afiliado', 0, NULL, 'pendiente', NULL, 1, '2025-08-20 01:55:54', '2026-01-22 17:43:36'),
(79, 'Miguel Angel Meza Meza', '1100083056', '2006-07-21', 'Masculino', '3023966775', 'miguelangelmezamez5@gmail.com', 'cARRERA 88 A #68-135', 'Medellin', 'Sura', 'O+', '', 'Sede Robledo', 'Azul', '2025-01-20', NULL, '2025-05-01 22:39:12', 'afiliado', 0, NULL, 'pendiente', NULL, 1, '2025-08-20 01:55:54', '2025-08-20 01:55:54'),
(80, 'Nicolas Foronda Palacio', '1025909217', '2020-08-04', 'Masculino', '3013338123', 'merlynjoha28@gmail.com', 'Calle 62 # 123-29 Apto 201', 'Medellin', 'SURA', 'O+', 'Merlyn Johana Palacio Alzate', 'Martes y Jueves 05:00 p.m.', 'Blanco', '2025-01-20', NULL, '2025-05-01 22:39:12', 'afiliado', 0, NULL, 'pendiente', NULL, 1, '2025-08-20 01:55:54', '2025-08-20 01:55:54'),
(81, 'Yury Elena Alcaraz Arango ', '1001386299', '1990-09-02', 'Femenino', '3002716121', 'yuryalca@hotmail.com', 'CRA 98 #70d-90', 'Medellín ', 'Sura ', 'B+', '', 'Sede Robledo', 'Blanco', '2025-01-20', NULL, '2025-05-01 22:39:12', 'afiliado', 0, NULL, 'pendiente', NULL, 1, '2025-08-20 01:55:54', '2025-08-20 01:55:54'),
(82, 'Mauricio Urdely García Giraldo ', '71768300', '1973-06-23', 'Masculino', '3163203928', 'murgar7@gmail.com', 'Calle 49 # 69 09', 'Medellín ', 'Nueva EPS ', 'O+', '', 'Sábados 12:00 m', 'Verde', '2025-01-20', NULL, '2025-05-01 22:39:12', 'afiliado', 0, NULL, 'pendiente', NULL, 1, '2025-08-20 01:55:54', '2025-08-20 01:55:54'),
(83, 'Juan Jose Tabares Triviño', '1011393843', '2005-12-27', 'Masculino', '3242747801', 'juanjosetabarestrivino@gmail.com', 'Cra 47 #64-55', 'Medellin', 'Sura', 'O+', '', 'Sábados 12:00 m', 'Blanco', '2025-01-20', NULL, '2025-05-01 22:39:12', 'afiliado', 0, NULL, 'pendiente', NULL, 1, '2025-08-20 01:55:54', '2025-08-20 01:55:54'),
(84, 'Sebastián Guerra Sepúlveda ', '1034996138', '2010-04-10', 'Masculino', '3017817676', 'jorge.guerra000@gmail.com', 'Carrera 40B #17-188apartamento 612 urbanización bronce ', 'Medellín ', 'Sanitas', 'O+', 'Marcela Sepulveda ', 'Sábados 12:00 m', 'Verde', '2025-01-20', NULL, '2025-05-01 22:39:12', 'afiliado', 0, NULL, 'pendiente', NULL, 1, '2025-08-20 01:55:54', '2025-08-20 01:55:54'),
(85, 'Cesar Augusto Meneses Guzman', '1036931840', '1988-09-15', 'Masculino', '3005233781', 'camenesesg@gmail.com', 'Calle 37 # 45 - 200', 'Bello', 'Sura', 'O+', '', 'Sábados 12:00 m', 'Naranja', '2025-01-20', NULL, '2025-05-01 22:39:12', 'afiliado', 0, NULL, 'pendiente', NULL, 1, '2025-08-20 01:55:54', '2025-08-20 01:55:54'),
(86, 'Daniel Mauricio Rivas Ramírez', '1036624426', '1989-06-07', 'Masculino', '3166992531', 'rivasse7en@gmail.com', 'CL 22 #83b - 360', 'Medellín', 'Sura', 'O+', '', 'Avanzados y Negros', '1th Dan', '2025-01-20', 'foto_681ecd5b066e7.jpg', '2025-05-01 22:39:12', 'instructor', 0, 3, 'pendiente', NULL, 1, '2025-08-20 01:55:54', '2025-08-20 01:55:54'),
(87, 'Martín Lopez Correa', '1036458331', '2016-03-05', 'Masculino', '3174652009', 'yovannylo@hotmail.com', 'CLL 22 57-41-APT 301', 'Medellín', 'SURA', 'A+', 'YOVANNY LÓPEZ', 'Sábados 12:00 m', 'Naranja', '2025-01-20', NULL, '2025-05-01 22:39:12', 'afiliado', 0, NULL, 'pendiente', NULL, 1, '2025-08-20 01:55:54', '2025-08-20 01:55:54'),
(88, 'Juan Sebastián Grisales Villa', '1037671467', '1999-11-07', 'Masculino', '3054823739', 'juansebastiangrisalesvilla@gmail.com', 'calle 5 sur#. 25-130', 'Medellín', 'SURA', 'O+', '', 'Sábados 12:00 m', 'Amarillo', '2025-01-20', 'foto_1037671467_1769708166.jpg', '2025-05-01 22:39:12', 'afiliado', 0, NULL, 'pendiente', NULL, 1, '2025-08-20 01:55:54', '2026-01-29 17:36:06'),
(92, 'Mariangel Vergara Alcaraz ', '1020316633', '2013-11-16', 'Femenino', '3002716121', 'yuryalca@hotmail.com', 'CRA 98 # 70d- 90', 'Medellín ', 'Sura ', 'O+', 'Yury Elena Alcaraz Arango ', 'Sede Robledo', 'Amarillo', '2025-01-20', NULL, '2025-05-01 22:39:12', 'afiliado', 0, NULL, 'pendiente', NULL, 1, '2025-08-20 01:55:54', '2025-08-20 01:55:54'),
(93, 'Salomé Alcaraz Lescano ', '1023446949', '2013-03-19', 'Femenino', '3002716121', 'yuryalca@hotmail.com', 'CRA 98 #70d-90', 'Medellín ', 'Sura ', 'A+ ', 'Yury Elena Alcaraz Arango ', 'Sede Robledo', 'Amarillo', '2025-01-20', NULL, '2025-05-01 22:39:12', 'afiliado', 0, NULL, 'pendiente', NULL, 1, '2025-08-20 01:55:54', '2025-08-20 01:55:54'),
(94, 'Juanjose Quiroz Cano ', '1032026434', '2014-07-13', 'Masculino', '3243184165', 'emily13232805@gmail.com', 'Calle 76Da#9616', 'Medellín ', 'Sura ', 'O+', 'Michel cano ', 'Sede Robledo', 'Amarillo', '2025-01-20', NULL, '2025-05-01 22:39:12', 'afiliado', 0, NULL, 'pendiente', NULL, 1, '2025-08-20 01:55:54', '2025-08-20 01:55:54'),
(95, 'Ana Sofia Trujillo', '1020113287', '2007-06-05', 'Femenino', '3206874171', 'Anasophi.645@gmail.com', 'Carrera54 E #10 B SUR 26', 'Medellín', 'Sanitas', 'B+', 'Mari Luz Areiza', 'Sábados 07:00 a.m.', 'Naranja', '2025-01-20', NULL, '2025-05-01 22:39:12', 'afiliado', 0, NULL, 'pendiente', NULL, 1, '2025-08-20 01:55:54', '2026-01-19 17:37:21'),
(96, 'Sara Trujillo Areiza', '1034996593', '2010-07-03', 'Femenino', '3147684861', 'strujilloareizy2@gmail.com', 'Carrera 54 E #10B SUR 26', 'Medellín', 'Sanitas', 'B+', 'Mari Luz Areiza', 'Sábados 07:00 a.m.', 'Naranja', '2025-01-20', 'foto_1034996593_1769796109.jpg', '2025-05-01 22:39:12', 'afiliado', 0, NULL, 'pendiente', NULL, 1, '2025-08-20 01:55:54', '2026-01-30 18:01:49'),
(97, 'Sergio Andrés Muñoz Ruiz', '1019986309', '2005-08-30', 'Masculino', '3188225970', 'sergiomunozruiz3@gmail.com', 'Calle 34 # 64-45 colina de Asis - Etapa a apto 318', 'Itagüí', 'Salud Total', 'O-', '', 'Sábados 07:00 a.m., Sábados 12:00 m', 'Verde', '2025-01-20', NULL, '2025-05-01 22:39:12', 'afiliado', 0, NULL, 'pendiente', NULL, 1, '2025-08-20 01:55:54', '2026-02-03 20:41:38'),
(98, 'Nicol Sofia Herrera Gallego ', '1090096652', '2012-12-23', 'Femenino', '3217294596', 'gallegovanesa23@gmail.com', 'Calle 79 c #95a45', 'Medellín ', 'Sura', 'O+ ', 'Vanesa gallego ', 'Sede Robledo', 'Blanco', '2025-01-20', NULL, '2025-05-01 22:39:12', 'afiliado', 0, NULL, 'pendiente', NULL, 1, '2025-08-20 01:55:54', '2025-08-20 01:55:54'),
(99, 'David Stiven Giraldo Higuita ', '1152222222', '1998-08-11', 'Masculino', '3242581159', 'hstiven518@gmail.com', 'Carrera 96 calle 70D- 30  apto 9703 segundo piso. Robledo villas de santa fe', 'Medellín ', 'Savia salud ', 'A+', '', 'Sede Robledo', 'Amarillo', '2025-01-20', NULL, '2025-05-01 22:39:12', 'afiliado', 0, NULL, 'pendiente', NULL, 1, '2025-08-20 01:55:54', '2025-08-20 01:55:54'),
(100, 'Juan Miguel Suaza Molina ', '1029302499', '2016-10-27', 'Masculino', '3136053304', 'lisetmolina7@gmail.com', 'Cr 95 #78 b 87 int 301', 'Medellín ', 'Sura', 'B+', 'Anyi liset Molina Betancur ', 'Sede Robledo', 'Blanco', '2025-01-20', NULL, '2025-05-01 22:39:12', 'afiliado', 0, NULL, 'pendiente', NULL, 1, '2025-08-20 01:55:54', '2025-08-20 01:55:54'),
(101, 'Juan Pablo Trujillo Orrego', '1033264072', '2014-01-30', 'Masculino', '3014725705', 'cecilia22agosto@gmail.com', 'Carrera 97 79b19', 'Medellín ', 'Sura', 'O+', 'María Orrego franco', 'Miércoles y Viernes 07:30 p.m.', 'Blanco', '2025-01-20', NULL, '2025-05-01 22:39:12', 'afiliado', 0, NULL, 'pendiente', NULL, 1, '2025-08-20 01:55:54', '2025-08-20 01:55:54'),
(102, 'Diego Guarin Escudero ', '1068576875', '1986-08-11', 'Masculino', '3137612576', 'dguarinescudero@gmail.com', 'Cll 32 n 75 - 46', 'Medellín ', 'Sura', 'A+', '', 'Sábados 12:00 m ', 'Amarillo', '2025-01-20', NULL, '2025-05-01 22:39:12', 'afiliado', 0, NULL, 'pendiente', NULL, 1, '2025-08-20 01:55:54', '2025-08-20 01:55:54'),
(104, 'Samuel Higuita Gallego ', '1025887777', '2006-04-04', 'Masculino', '3017088934', 'samuelhiguitagallego@gmail.com', 'CRA 94A # 76 Da 14', 'Medellín', 'Sura ', 'O-', '', 'Miércoles y Viernes 07:30 p.m.', 'Azul', '2025-01-20', NULL, '2025-05-01 22:39:12', 'afiliado', 0, NULL, 'pendiente', NULL, 1, '2025-08-20 01:55:54', '2025-08-20 01:55:54'),
(107, 'Matías Chávarria Loaiza', '1021931320', '2011-12-26', 'Masculino', '3147340819', 'yorladys906@gmail.com', 'Cra 92 a # 88-38', 'Medellín', 'Sura', 'O+', 'Yorladis Loaiza', 'Sede Robledo', 'Blanco', NULL, '1747407231_Screenshot_20250515-211210.jpg', '2025-05-16 14:53:51', 'afiliado', 0, NULL, 'pendiente', NULL, 1, '2025-08-20 01:55:54', '2025-08-20 01:55:54'),
(108, 'Antonella Valentina Leal', '6382180', '2010-09-13', 'Femenino', '3043571596', 'juan.lealcarrera@gmail.com', 'Cra 95A # 78A-31 (101)', 'Medellín', 'Sura', 'O+', 'Juan Carlos Leal', 'Sede Robledo', 'Blanco', NULL, '1747434781_Merged_image.jpg', '2025-05-16 22:33:01', 'afiliado', 0, NULL, 'pendiente', NULL, 1, '2025-08-20 01:55:54', '2025-08-20 01:55:54'),
(109, 'María kamila Flórez Solano', '1018250128', '2010-10-31', 'Femenino', '3007776226', 'yelusalon@hotmail.com', 'Cll79bb#96_49', 'Medellín', 'Salud Total', 'A+', 'Yesica Lucia Solano londoño', 'Sede Robledo', 'Naranja', NULL, '', '2025-05-18 21:58:00', 'afiliado', 0, NULL, 'pendiente', NULL, 1, '2025-08-20 01:55:54', '2026-01-22 16:16:53'),
(110, 'Valery Castaño Chaverra', '1025902739', '2016-01-10', 'Femenino', '3117016517', 'andrescastal@hotmail.com', 'Cra 95 78 b 97', 'Medellín', 'Nueva EPS', 'O+', 'Andrés Felipe castaño lopez', 'Martes y Jueves 05:00 p.m.', NULL, NULL, '1747668608_IMG_0157.jpeg', '2025-05-19 15:30:08', 'afiliado', 0, NULL, 'pendiente', NULL, 1, '2025-08-20 01:55:54', '2025-08-20 01:55:54'),
(113, 'Luciana vitola ospin', '1021938787', '2015-12-15', 'Femenino', '3217576433', 'mariasara_2014@hotmail.com', 'Cr 95aa # 79-80', 'Medellín', 'Sura', 'O+', 'Eliana Idalia Ospina marin', 'Sede Robledo', 'Blanco', NULL, '', '2025-05-23 19:25:56', 'afiliado', 0, NULL, 'pendiente', NULL, 1, '2025-08-20 01:55:54', '2025-08-20 01:55:54'),
(114, 'Miguelangel Hernández Bonilla', '1025895473', '2010-05-13', 'Masculino', '3225429144', 'milena_15bonilla@hotmail.com', 'CR 94 CL 78 a 16', 'Medellín', 'SURA', 'O+', 'Sandy Milena Bonilla Londoño', 'Martes y Jueves 05:00 p.m.', 'Azul', NULL, 'foto_1025895473_1769905498.jpg', '2025-05-23 19:52:15', 'afiliado', 0, NULL, 'pendiente', NULL, 1, '2025-08-20 01:55:54', '2026-02-01 00:24:58'),
(116, 'Kelsey Andrea Perea Pérez', '1074007523', '2014-12-22', 'Femenino', '3043922122', 'mariaviellard927@gmail.com', 'Robledo aures', 'Medellín', 'Salud Total', 'A+', 'Maria Pérez viellard', 'Sede Robledo', 'Blanco', NULL, '1748035086_1000039385.jpg', '2025-05-23 21:18:06', 'afiliado', 0, NULL, 'pendiente', NULL, 1, '2025-08-20 01:55:54', '2025-08-20 01:55:54'),
(117, 'Thaliana Perea Pérez', '1074011561', '2016-10-20', 'Femenino', '3043922122', 'mariaviellard927@gmail.com', 'Robledo aures', 'Medellín', 'Salud Total', 'O+', 'Maria Pérez viellard', 'Sede Robledo', 'Blanco', NULL, '1748035250_1000045058.jpg', '2025-05-23 21:20:50', 'afiliado', 0, NULL, 'pendiente', NULL, 1, '2025-08-20 01:55:54', '2025-08-20 01:55:54'),
(118, 'Heider Deivid Perea Pérez', '1074014488', '2018-06-02', 'Masculino', '3043922122', 'mariaviellard927@gmail.com', 'Robledo aures', 'Medellín', 'Salud Total', 'A+', 'Maria Pérez viellard', 'Sede Robledo', 'Blanco', NULL, '1748035757_1000020219.jpg', '2025-05-23 21:29:17', 'afiliado', 0, NULL, 'pendiente', NULL, 1, '2025-08-20 01:55:54', '2025-08-20 01:55:54'),
(120, 'Emiliano Arias Vélez', '1109566244', '2018-10-12', 'Masculino', '3108934118', 'jaime.arias18600@gmail.com', 'Cra 93 # 78b - 105 Interior 151 Robledo Aures 1', 'Medellín', 'Sanidad Militar', 'A+', 'Jaime Arias Sabogal', 'Sede Robledo', 'Blanco', NULL, '1748055976_17480556324144621974648660641622.jpg', '2025-05-24 03:06:16', 'afiliado', 0, NULL, 'pendiente', NULL, 1, '2025-08-20 01:55:54', '2026-01-31 16:30:18'),
(121, 'Anthony rios Martinez', '1025903979', '2016-10-09', 'Masculino', '3193116042', 'deybyrios.lm3515@gmail.com', 'CR 95 AA # 80-36', 'Medellín', 'Nueva EPS', 'A+', 'Deyby rios Morales', 'Sede Robledo', 'Blanco', NULL, '', '2025-05-28 03:25:40', 'afiliado', 0, NULL, 'pendiente', NULL, 1, '2025-08-20 01:55:54', '2025-08-20 01:55:54'),
(124, 'Emmanuel Castañeda Castañeda ', '1021945394', '2018-12-27', 'Masculino', '3122224319', 'gabbyc0929@gmail.com', 'Cll 76d # 93a 29 ', 'Medellín', 'SURA', 'A+', 'Jennifer Castañeda Pérez ', 'Sede Robledo', NULL, NULL, '1748881595_1000139939.jpg', '2025-06-02 16:26:35', 'afiliado', 0, NULL, 'pendiente', NULL, 1, '2025-08-20 01:55:54', '2025-08-20 01:55:54'),
(127, 'Maximiliano Mayorga Grajales ', '1035980761', '2014-07-31', 'Masculino', '3217342828', 'claum_1703@hotmail.com', 'Calle 67 sur 45 76 int 311', 'Sabaneta', 'SURA', 'A+', 'Claudia Janeth Grajales Martinez ', 'Sábados 07:00 a.m.', NULL, NULL, '1750994454_IMG_20240427_090523.jpg', '2025-06-27 03:20:54', 'afiliado', 0, NULL, 'pendiente', NULL, 1, '2025-08-20 01:55:54', '2025-08-20 01:55:54'),
(128, 'Santiago Guarin Coy', '1062967625', '2009-03-16', 'Masculino', '3197600750', 'santiagog1690@gmail.com', 'Urbanización:Capri  barrio:chagualo', 'Medellín', 'SURA', 'A+', 'Diego Andrés Guarin Escudero', 'Miércoles y Viernes 07:30 p.m., Sábados 12:00 m', 'Naranja', '2025-08-02', '1751482030_IMG-20241011-WA0000.jpg', '2025-07-02 18:47:10', 'afiliado', 0, NULL, 'pendiente', NULL, 1, '2025-08-20 01:55:54', '2026-02-05 02:02:04'),
(131, 'Sebastian cifuentes garcia', '1000757231', '2001-11-23', 'M', '3233930189', 'sebastiancifuentesgarcia@gmail.com', 'Carrera 40 Numero 48 50 ', 'Copacabana', 'Sura', 'O+', '', 'Sábados 12:00 m', 'Blanco', '2025-07-20', '1751512980_img23.jpg', '2025-07-03 03:23:00', 'afiliado', 0, NULL, 'pendiente', NULL, 1, '2025-08-20 01:55:54', '2025-08-20 01:55:54'),
(141, 'Santiago Ramírez Gómez', '1036452070', '2008-02-28', 'Masculino', '3016611257', 'santiagoramirezgomez83@gmail.com', 'Cra 42 # 46 dd sur 21', 'Envigado', 'SURA', 'A+', 'Adriana Patricia Gómez Vélez', 'Miércoles y Viernes 07:30 p.m.', 'Rojo', '2025-08-06', '1754528327_6893fa4782a00.png', '2025-08-07 00:58:47', 'afiliado', 0, NULL, 'pendiente', NULL, 1, '2025-08-20 01:55:54', '2025-08-20 01:55:54'),
(142, 'Brandon Stiven Cañón Goez', '1020237862', '2018-10-19', 'Masculino', '3147195950', 'yesleidy045@gmail.com', 'Cra 93#78b-30', 'Medellín', 'SURA', 'O+', 'Yesleydy Goez Gomez', 'Sede Robledo', 'Blanco', '2025-07-22', '1755216129_689e790189a25.jpeg', '2025-08-15 00:02:09', 'afiliado', 0, NULL, 'pendiente', NULL, 1, '2025-08-20 01:55:54', '2025-08-20 01:55:54'),
(143, 'Nicolás Palacio Betancur', '1023546108', '2018-06-26', 'Masculino', '3205568670', 'susanabetancur0327@gmail.com', 'Calle 48 A 101 07 San Javier', 'Medellín', 'SURA', 'O+', 'Susana Betancur Sierra', 'Miércoles y Viernes 07:30 p.m.;Sábados 12:00 m', 'Blanco', '2025-08-23', '1756338576_68af999015cee.jpg', '2025-08-27 23:49:36', 'afiliado', 0, NULL, 'pendiente', NULL, 1, '2025-08-27 23:49:36', '2025-08-27 23:49:36'),
(144, 'Isabella Díaz Franco', '1014980437', '2006-10-13', 'Femenino', '3206558890', 'isadiazfr@gmail.com', 'Carrera 65 B #2 65', 'Medellín', 'Sanitas', 'O−', '', 'Sábados 07:00 a.m.', 'Blanco', '2025-08-27', '1756342514_68afa8f2870b7.jpeg', '2025-08-28 00:55:14', 'afiliado', 0, NULL, 'pendiente', NULL, 1, '2025-08-28 00:55:14', '2025-08-28 00:55:14'),
(145, 'Emmanuel Balbin Betancur', '1027816501', '2022-06-22', 'Masculino', '3124097964', 'linadi380@outlook.es', 'Calle 30A # 65B 24', 'Medellín', 'SURA', 'O+', 'Lina María Betancur Salazar', 'Sábados 12:00 m', 'Blanco', '2025-09-03', '1756956001_68b90561ec080.jpg', '2025-09-04 03:20:01', 'afiliado', 0, NULL, 'pendiente', NULL, 1, '2025-09-04 03:20:01', '2025-09-04 03:20:01'),
(146, 'Isaac Caro Suárez', '1036456813', '2013-07-01', 'M', '3145918414', 'jeniferbarberdancer@gmail.com', 'Cra 83a #29a 80 / 201', 'Medellín', 'Sura', 'O+', 'Jenifer Suarez Mesa', 'Sábados 07:00 a.m.', 'Amarillo', '2025-09-14', '1757882019_isaac.jpg', '2025-09-14 17:57:49', 'afiliado', 0, NULL, 'pendiente', NULL, 1, '2025-09-14 17:57:49', '2026-02-10 23:49:09'),
(147, 'Paulina Bedoya Velasquez', '1036453225', '2009-06-07', 'Femenino', '3195307881', 'paubedoya13@gmail.com', 'Cra 27f 34dd sur 52 , Vientos de Aragón ap 808, Envigado, Antioquia, Colombia', 'Envigado', 'SURA', 'A+', 'Henry Adrian Bedoya Castañeda', 'Sábados 07:00 a.m.', 'Amarillo', '2025-09-15', '1757949640_68c82ec804c58.jpg', '2025-09-15 15:20:40', 'afiliado', 0, NULL, 'pendiente', NULL, 1, '2025-09-15 15:20:40', '2025-09-15 15:20:40'),
(148, 'Gabriel Morales Orejuela', '1032033754', '2020-10-24', 'Masculino', '3225303425', 'angelica250130@gmail.com', 'cr953#78a-59', 'Medellín', 'Savia Salud', 'A+', 'María Angélica Orejuela', 'Sede Robledo', 'Blanco', '2025-12-16', '1765941963_694222cba6606.jpg', '2025-12-17 03:26:03', 'afiliado', 0, NULL, 'pendiente', NULL, 1, '2025-12-17 03:26:03', '2025-12-17 03:26:03'),
(149, 'Juan Esteban Suaza Castaño', '1000886773', '2003-03-28', 'Masculino', '3113783671', 'juansuazacas123@gmail.com', 'CL 79 # 95 - 41', 'Medellín', 'SURA', 'O+', '', 'Sábados 12:00 m', 'Amarillo', '2025-12-16', '1765944404_69422c54cbe65.jpg', '2025-12-17 04:06:44', 'afiliado', 0, NULL, 'pendiente', NULL, 1, '2025-12-17 04:06:44', '2026-01-31 14:27:54'),
(150, 'Anthony henao lopez', '1033199794', '2016-02-09', 'Masculino', '3207160226', 'jessys_1718@hotmail.com', 'Carrera94#77f69 apt 202', 'Medellín', 'SURA', 'O+', 'Jessica Alexandra Lopez arboleda', 'Sede Robledo', 'Amarillo', '2025-12-17', '1765986813_6942d1fd77c67.jpg', '2025-12-17 15:53:33', 'afiliado', 0, NULL, 'pendiente', NULL, 1, '2025-12-17 15:53:33', '2026-01-26 18:27:46'),
(152, 'María Angel Graciano Suárez', '1011401446', '2009-07-06', 'Femenino', '3232865854', 'angel0.0valenciana@gmail.com', 'Calle 105 #64e20', 'Medellín', 'SURA', 'O+', 'Silvia Elena Benítez Graciano', 'Miércoles y Viernes 07:30 p.m.;Sábados 12:00 m', 'Amarillo', '2026-01-17', '1768704595_696c4a5349e64.jpg', '2026-01-18 02:49:55', 'afiliado', 0, NULL, 'pendiente', NULL, 1, '2026-01-18 02:49:55', '2026-01-18 02:49:55'),
(153, 'Mariano Maelo Ruiz Flórez', '1033198765', '2015-05-29', 'Masculino', '3136732938', 'vane1856@hotmail.com', 'Cll79 93a 58', 'Medellín', 'Salud Total', 'O+', 'Vanessa Mayteh Flórez Narváez', 'Sede Robledo', 'Blanco', '2026-01-31', '1769862310_697df4a61c2c5.jpg', '2026-01-31 12:25:10', 'afiliado', 0, NULL, 'pendiente', NULL, 1, '2026-01-31 12:25:10', '2026-01-31 12:25:10'),
(154, 'Isabella Ruiz Flórez', '1033203141', '2017-11-26', 'Femenino', '3136732938', 'vane1856@hotmail.com', 'Cll79 93a 58', 'Medellín', 'Salud Total', 'O+', 'Vanessa Mayteh Flórez Narváez', 'Sede Robledo', 'Blanco', '2026-01-31', '1769862454_697df53641f98.jpg', '2026-01-31 12:27:34', 'afiliado', 0, NULL, 'pendiente', NULL, 1, '2026-01-31 12:27:34', '2026-01-31 12:27:34'),
(155, 'Carlos Fernando Restrepo Blandón', '70512795', '1962-03-21', 'Masculino', '3146166776', 'minegritolindo3@gmail.com', 'Calle 32 nro 47 17 (300)', 'Itagüí', 'Salud Total', 'B+', '', 'Avanzados y Negros', '3th Dan', '2026-01-31', '1769879845_697e3925bb407.jpg', '2026-01-31 17:17:25', 'instructor', 0, 12, 'pendiente', NULL, 1, '2026-01-31 17:17:25', '2026-02-03 01:27:08'),
(156, 'Antonella Valentina Leal Vásquez', '6382431', '2010-09-13', 'Femenino', '3023175660', 'antonella.lealvasquez@gmail.com', 'CRA 95A # 78A-31 (101)', 'Medellín', 'SURA', 'O+', 'Juan Carlos Leal Carrera', 'Sede Robledo', 'Naranja', '2026-01-31', '1769900937_697e8b89ea49d.jpg', '2026-01-31 23:08:57', 'afiliado', 0, NULL, 'pendiente', NULL, 1, '2026-01-31 23:08:57', '2026-01-31 23:08:57'),
(157, 'Anthony Giraldo Peña', '1021948013', '2020-03-03', 'Masculino', '3054694056', 'heidyjaller@hotmail.com', 'Cl 78 aa # 95a 49', 'Medellín', 'SURA', 'O+', 'Heidy Peña Jaller', 'Sede Robledo', 'Blanco', '2026-02-02', '1770040220_6980ab9c0b12f.jpeg', '2026-02-02 13:50:20', 'afiliado', 0, NULL, 'pendiente', NULL, 1, '2026-02-02 13:50:20', '2026-02-02 13:50:20'),
(158, 'Valeria Londoño Monsalve', '1020321911', '2015-10-22', 'F', '3122966566', 'eluecer.londono@gmail.com', 'Calle 42b #84a 63', 'Medellín', 'Sura', 'O+', 'Eliecer Londoño', 'Taekwondo', 'Blanco', '2026-02-05', '1770304517_6984b405b5c87.jpg', '2026-02-05 15:15:17', 'afiliado', 0, NULL, 'pendiente', NULL, 1, '2026-02-05 15:15:17', '2026-02-19 20:12:59'),
(159, 'Daniel Santiago Gómez Quiceno', '1021938224', '2015-09-23', 'Masculino', '3014460577', 'eyquiceno@misena.edu.co', 'Cr 94#77dd-26', 'Medellín', 'SURA', 'O+', 'Emili yurani quiceno peña', 'Sede Robledo', 'Blanco', '2026-02-06', '1770419039_6986735fa41a4.jpg', '2026-02-06 23:03:59', 'afiliado', 0, NULL, 'pendiente', NULL, 1, '2026-02-06 23:03:59', '2026-02-06 23:03:59'),
(160, 'José Miguel Gómez Quiceno', '1021946734', '2019-08-12', 'Masculino', '3014460577', 'eyquiceno@misena.edu.co', 'Cr 94#77dd-26', 'Medellín', 'SURA', 'O+', 'Emili yurani quiceno peña', 'Sede Robledo', 'Blanco', '2026-02-06', '1770425433_69868c597e377.jpg', '2026-02-07 00:50:33', 'afiliado', 0, NULL, 'pendiente', NULL, 1, '2026-02-07 00:50:33', '2026-02-07 00:50:33'),
(161, 'Santiago Otero Betancur', '1036674173', '1996-07-19', 'M', '3133630001', 'sobwd@hotmail.com', 'Carrera 92CC 53D40Apto. 2001', 'Medellín', 'Sura', 'O+', '', 'Lunes, Miércoles y Viernes 06:00 a.m.', 'Amarillo', '2026-02-08', '1770586544_698901b080d39.jpg', '2026-02-08 21:35:44', 'afiliado', 0, NULL, 'pendiente', NULL, 1, '2026-02-08 21:35:44', '2026-02-10 23:13:33'),
(162, 'Emmanuel Ocampo Romero', '1025902249', '2015-08-14', 'Masculino', '3136186139', 'tatis1626@hotmail.com', 'Crr 91b76da 25', 'Medellín', 'Salud Total', 'A+', 'Yenny Tatiana Romero Cortes', 'Sede Robledo', 'Blanco', '2026-02-10', 'foto_1025902249_1771604363.jpg', '2026-02-10 23:24:06', 'afiliado', 0, NULL, 'pendiente', NULL, 1, '2026-02-10 23:24:06', '2026-02-20 16:19:23'),
(163, 'Emmanuel lopez mesa', '1033201834', '2017-02-27', 'Masculino', '3242891654', 'jesusarleylopez1980@gmail.com', 'Carrera76 53-89', 'Medellín', 'SURA', 'A+', 'Arley lopez', 'Miércoles y Viernes 07:30 p.m.', 'Blanco', '2026-02-11', 'foto_1033201834_1771626320.jpg', '2026-02-12 00:36:08', 'afiliado', 0, NULL, 'pendiente', NULL, 1, '2026-02-12 00:36:08', '2026-02-20 22:25:20'),
(164, 'Melanny Dayana Gómez', '1021937686', '2015-06-05', 'Femenino', '3157682525', 'cossiohernandezk2@gmail.com', 'Crr 93 a #78 a 27', 'Medellín', 'SURA', 'A+', 'Katherin Hernández', 'Sede Robledo', 'Amarillo', '2026-02-21', '1771695594_6999edea42a1c.jpg', '2026-02-21 17:39:54', 'afiliado', 0, NULL, 'pendiente', NULL, 1, '2026-02-21 17:39:54', '2026-02-21 17:39:54'),
(165, 'Juan José adames alba', '1107989280', '2018-03-08', 'Masculino', '3133355775', 'ingridalbainpec@gmail.com', 'Cra 95 cll 64g-25 Atlantida 2 robledo la campiña', 'Medellín', 'Salud Total', 'O+', 'Ingrid Carolina alba Ortiz', 'Sede Robledo', 'Blanco', '2026-03-04', '1772640533_69a8591574afd.jpeg', '2026-03-04 16:08:53', 'afiliado', 0, NULL, 'pendiente', NULL, 1, '2026-03-04 16:08:53', '2026-03-04 16:08:53'),
(166, 'Luciana Solarte Pelaez', '1020125928', '2017-07-17', 'Femenino', '3113208667', 'mari7907@hotmail.com', 'Carrera 71A 53A-49', 'Medellín', 'SURA', 'O+', 'Marisol Pelaez Restrepo', 'Sábados 07:00 a.m.', 'Blanco', '2026-03-08', '1772977567_69ad7d9fb82c5.jpg', '2026-03-08 13:46:07', 'afiliado', 0, NULL, 'pendiente', NULL, 1, '2026-03-08 13:46:07', '2026-03-08 13:46:07');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asistencia`
--

CREATE TABLE `asistencia` (
  `id` int(11) NOT NULL,
  `afiliado_id` int(11) NOT NULL,
  `instructor_id` int(11) NOT NULL,
  `horario_id` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `hora_entrada` time DEFAULT NULL,
  `hora_salida` time DEFAULT NULL,
  `estado` enum('presente','ausente','tardanza') COLLATE utf8mb4_unicode_ci DEFAULT 'presente',
  `observaciones` text COLLATE utf8mb4_unicode_ci,
  `fecha_registro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `asistencia`
--

INSERT INTO `asistencia` (`id`, `afiliado_id`, `instructor_id`, `horario_id`, `fecha`, `hora_entrada`, `hora_salida`, `estado`, `observaciones`, `fecha_registro`) VALUES
(20, 95, 1, 10, '2025-08-26', NULL, NULL, 'presente', NULL, '2025-08-27 00:22:38'),
(21, 96, 1, 10, '2025-08-26', NULL, NULL, 'ausente', NULL, '2025-08-27 00:22:38'),
(22, 127, 1, 10, '2025-08-26', NULL, NULL, 'presente', NULL, '2025-08-27 00:22:38'),
(23, 73, 72, 1, '2025-08-30', NULL, NULL, 'ausente', NULL, '2025-09-03 00:18:23'),
(24, 76, 72, 1, '2025-08-30', NULL, NULL, 'ausente', NULL, '2025-09-03 00:18:23'),
(25, 95, 86, 10, '2025-08-02', NULL, NULL, 'presente', NULL, '2025-09-23 14:13:04'),
(26, 96, 86, 10, '2025-08-02', NULL, NULL, 'presente', NULL, '2025-09-23 14:13:04'),
(27, 127, 86, 10, '2025-08-02', NULL, NULL, 'presente', NULL, '2025-09-23 14:13:04'),
(28, 144, 86, 10, '2025-08-02', NULL, NULL, 'presente', NULL, '2025-09-23 14:13:04'),
(29, 146, 86, 10, '2025-08-02', NULL, NULL, 'ausente', NULL, '2025-09-23 14:13:04'),
(30, 147, 86, 10, '2025-08-02', NULL, NULL, 'presente', NULL, '2025-09-23 14:13:04'),
(31, 61, 86, 3, '2025-09-23', NULL, NULL, 'ausente', NULL, '2025-09-23 14:15:04'),
(32, 65, 86, 3, '2025-09-23', NULL, NULL, 'presente', NULL, '2025-09-23 14:15:04'),
(33, 66, 86, 3, '2025-09-23', NULL, NULL, 'presente', NULL, '2025-09-23 14:15:04'),
(34, 71, 86, 3, '2025-09-23', NULL, NULL, 'presente', NULL, '2025-09-23 14:15:04'),
(35, 82, 86, 3, '2025-09-23', NULL, NULL, 'presente', NULL, '2025-09-23 14:15:04'),
(36, 83, 86, 3, '2025-09-23', NULL, NULL, 'ausente', NULL, '2025-09-23 14:15:04'),
(37, 84, 86, 3, '2025-09-23', NULL, NULL, 'ausente', NULL, '2025-09-23 14:15:04'),
(38, 85, 86, 3, '2025-09-23', NULL, NULL, 'presente', NULL, '2025-09-23 14:15:04'),
(39, 87, 86, 3, '2025-09-23', NULL, NULL, 'ausente', NULL, '2025-09-23 14:15:04'),
(40, 88, 86, 3, '2025-09-23', NULL, NULL, 'presente', NULL, '2025-09-23 14:15:04'),
(41, 102, 86, 3, '2025-09-23', NULL, NULL, 'ausente', NULL, '2025-09-23 14:15:04'),
(42, 128, 86, 3, '2025-09-23', NULL, NULL, 'presente', NULL, '2025-09-23 14:15:04'),
(43, 131, 86, 3, '2025-09-23', NULL, NULL, 'ausente', NULL, '2025-09-23 14:15:04'),
(44, 143, 86, 3, '2025-09-23', NULL, NULL, 'ausente', NULL, '2025-09-23 14:15:04'),
(45, 145, 86, 3, '2025-09-23', NULL, NULL, 'presente', NULL, '2025-09-23 14:15:04'),
(46, 95, 86, 10, '2025-08-16', NULL, NULL, 'presente', NULL, '2025-09-23 15:27:42'),
(47, 96, 86, 10, '2025-08-16', NULL, NULL, 'presente', NULL, '2025-09-23 15:27:42'),
(48, 127, 86, 10, '2025-08-16', NULL, NULL, 'presente', NULL, '2025-09-23 15:27:42'),
(49, 144, 86, 10, '2025-08-16', NULL, NULL, 'presente', NULL, '2025-09-23 15:27:42'),
(50, 146, 86, 10, '2025-08-16', NULL, NULL, 'ausente', NULL, '2025-09-23 15:27:42'),
(51, 147, 86, 10, '2025-08-16', NULL, NULL, 'ausente', NULL, '2025-09-23 15:27:42'),
(52, 95, 86, 10, '2025-08-23', NULL, NULL, 'presente', NULL, '2025-09-23 15:33:34'),
(53, 96, 86, 10, '2025-08-23', NULL, NULL, 'presente', NULL, '2025-09-23 15:33:34'),
(54, 127, 86, 10, '2025-08-23', NULL, NULL, 'presente', NULL, '2025-09-23 15:33:34'),
(55, 144, 86, 10, '2025-08-23', NULL, NULL, 'presente', NULL, '2025-09-23 15:33:34'),
(56, 146, 86, 10, '2025-08-23', NULL, NULL, 'ausente', NULL, '2025-09-23 15:33:34'),
(57, 147, 86, 10, '2025-08-23', NULL, NULL, 'ausente', NULL, '2025-09-23 15:33:34'),
(58, 61, 86, 3, '2025-08-23', NULL, NULL, 'presente', NULL, '2025-09-23 15:37:20'),
(59, 65, 86, 3, '2025-08-23', NULL, NULL, 'ausente', NULL, '2025-09-23 15:37:20'),
(60, 66, 86, 3, '2025-08-23', NULL, NULL, 'ausente', NULL, '2025-09-23 15:37:20'),
(61, 71, 86, 3, '2025-08-23', NULL, NULL, 'presente', NULL, '2025-09-23 15:37:20'),
(62, 82, 86, 3, '2025-08-23', NULL, NULL, 'presente', NULL, '2025-09-23 15:37:20'),
(63, 83, 86, 3, '2025-08-23', NULL, NULL, 'ausente', NULL, '2025-09-23 15:37:20'),
(64, 84, 86, 3, '2025-08-23', NULL, NULL, 'ausente', NULL, '2025-09-23 15:37:20'),
(65, 85, 86, 3, '2025-08-23', NULL, NULL, 'presente', NULL, '2025-09-23 15:37:20'),
(66, 87, 86, 3, '2025-08-23', NULL, NULL, 'ausente', NULL, '2025-09-23 15:37:20'),
(67, 88, 86, 3, '2025-08-23', NULL, NULL, 'presente', NULL, '2025-09-23 15:37:20'),
(68, 102, 86, 3, '2025-08-23', NULL, NULL, 'presente', NULL, '2025-09-23 15:37:20'),
(69, 128, 86, 3, '2025-08-23', NULL, NULL, 'presente', NULL, '2025-09-23 15:37:20'),
(70, 131, 86, 3, '2025-08-23', NULL, NULL, 'ausente', NULL, '2025-09-23 15:37:20'),
(71, 143, 86, 3, '2025-08-23', NULL, NULL, 'presente', NULL, '2025-09-23 15:37:20'),
(72, 145, 86, 3, '2025-08-23', NULL, NULL, 'presente', NULL, '2025-09-23 15:37:20'),
(73, 61, 86, 3, '2025-08-30', NULL, NULL, 'ausente', NULL, '2025-09-23 15:40:50'),
(74, 65, 86, 3, '2025-08-30', NULL, NULL, 'presente', NULL, '2025-09-23 15:40:50'),
(75, 66, 86, 3, '2025-08-30', NULL, NULL, 'presente', NULL, '2025-09-23 15:40:50'),
(76, 71, 86, 3, '2025-08-30', NULL, NULL, 'presente', NULL, '2025-09-23 15:40:50'),
(77, 82, 86, 3, '2025-08-30', NULL, NULL, 'presente', NULL, '2025-09-23 15:40:50'),
(78, 83, 86, 3, '2025-08-30', NULL, NULL, 'ausente', NULL, '2025-09-23 15:40:50'),
(79, 84, 86, 3, '2025-08-30', NULL, NULL, 'ausente', NULL, '2025-09-23 15:40:50'),
(80, 85, 86, 3, '2025-08-30', NULL, NULL, 'presente', NULL, '2025-09-23 15:40:50'),
(81, 87, 86, 3, '2025-08-30', NULL, NULL, 'presente', NULL, '2025-09-23 15:40:50'),
(82, 88, 86, 3, '2025-08-30', NULL, NULL, 'presente', NULL, '2025-09-23 15:40:50'),
(83, 102, 86, 3, '2025-08-30', NULL, NULL, 'presente', NULL, '2025-09-23 15:40:50'),
(84, 128, 86, 3, '2025-08-30', NULL, NULL, 'presente', NULL, '2025-09-23 15:40:50'),
(85, 131, 86, 3, '2025-08-30', NULL, NULL, 'ausente', NULL, '2025-09-23 15:40:50'),
(86, 143, 86, 3, '2025-08-30', NULL, NULL, 'presente', NULL, '2025-09-23 15:40:50'),
(87, 145, 86, 3, '2025-08-30', NULL, NULL, 'presente', NULL, '2025-09-23 15:40:50'),
(88, 95, 86, 10, '2025-08-30', NULL, NULL, 'presente', NULL, '2025-09-23 15:41:51'),
(89, 96, 86, 10, '2025-08-30', NULL, NULL, 'presente', NULL, '2025-09-23 15:41:51'),
(90, 127, 86, 10, '2025-08-30', NULL, NULL, 'presente', NULL, '2025-09-23 15:41:51'),
(91, 144, 86, 10, '2025-08-30', NULL, NULL, 'presente', NULL, '2025-09-23 15:41:51'),
(92, 146, 86, 10, '2025-08-30', NULL, NULL, 'ausente', NULL, '2025-09-23 15:41:51'),
(93, 147, 86, 10, '2025-08-30', NULL, NULL, 'ausente', NULL, '2025-09-23 15:41:51'),
(94, 61, 86, 3, '2025-08-02', NULL, NULL, 'ausente', NULL, '2025-09-23 16:05:40'),
(95, 65, 86, 3, '2025-08-02', NULL, NULL, 'presente', NULL, '2025-09-23 16:05:40'),
(96, 66, 86, 3, '2025-08-02', NULL, NULL, 'presente', NULL, '2025-09-23 16:05:40'),
(97, 71, 86, 3, '2025-08-02', NULL, NULL, 'presente', NULL, '2025-09-23 16:05:40'),
(98, 82, 86, 3, '2025-08-02', NULL, NULL, 'presente', NULL, '2025-09-23 16:05:40'),
(99, 83, 86, 3, '2025-08-02', NULL, NULL, 'ausente', NULL, '2025-09-23 16:05:40'),
(100, 84, 86, 3, '2025-08-02', NULL, NULL, 'ausente', NULL, '2025-09-23 16:05:40'),
(101, 85, 86, 3, '2025-08-02', NULL, NULL, 'presente', NULL, '2025-09-23 16:05:40'),
(102, 87, 86, 3, '2025-08-02', NULL, NULL, 'ausente', NULL, '2025-09-23 16:05:40'),
(103, 88, 86, 3, '2025-08-02', NULL, NULL, 'presente', NULL, '2025-09-23 16:05:40'),
(104, 102, 86, 3, '2025-08-02', NULL, NULL, 'ausente', NULL, '2025-09-23 16:05:40'),
(105, 128, 86, 3, '2025-08-02', NULL, NULL, 'presente', NULL, '2025-09-23 16:05:40'),
(106, 131, 86, 3, '2025-08-02', NULL, NULL, 'ausente', NULL, '2025-09-23 16:05:40'),
(107, 143, 86, 3, '2025-08-02', NULL, NULL, 'ausente', NULL, '2025-09-23 16:05:40'),
(108, 145, 86, 3, '2025-08-02', NULL, NULL, 'ausente', NULL, '2025-09-23 16:05:40'),
(109, 61, 86, 3, '2025-08-16', NULL, NULL, 'presente', NULL, '2025-09-23 16:09:45'),
(110, 65, 86, 3, '2025-08-16', NULL, NULL, 'ausente', NULL, '2025-09-23 16:09:45'),
(111, 66, 86, 3, '2025-08-16', NULL, NULL, 'ausente', NULL, '2025-09-23 16:09:45'),
(112, 71, 86, 3, '2025-08-16', NULL, NULL, 'presente', NULL, '2025-09-23 16:09:45'),
(113, 82, 86, 3, '2025-08-16', NULL, NULL, 'presente', NULL, '2025-09-23 16:09:45'),
(114, 83, 86, 3, '2025-08-16', NULL, NULL, 'ausente', NULL, '2025-09-23 16:09:45'),
(115, 84, 86, 3, '2025-08-16', NULL, NULL, 'ausente', NULL, '2025-09-23 16:09:45'),
(116, 85, 86, 3, '2025-08-16', NULL, NULL, 'presente', NULL, '2025-09-23 16:09:45'),
(117, 87, 86, 3, '2025-08-16', NULL, NULL, 'ausente', NULL, '2025-09-23 16:09:45'),
(118, 88, 86, 3, '2025-08-16', NULL, NULL, 'presente', NULL, '2025-09-23 16:09:45'),
(119, 102, 86, 3, '2025-08-16', NULL, NULL, 'presente', NULL, '2025-09-23 16:09:45'),
(120, 128, 86, 3, '2025-08-16', NULL, NULL, 'presente', NULL, '2025-09-23 16:09:45'),
(121, 131, 86, 3, '2025-08-16', NULL, NULL, 'ausente', NULL, '2025-09-23 16:09:45'),
(122, 143, 86, 3, '2025-08-16', NULL, NULL, 'presente', NULL, '2025-09-23 16:09:45'),
(123, 145, 86, 3, '2025-08-16', NULL, NULL, 'presente', NULL, '2025-09-23 16:09:45'),
(124, 95, 86, 10, '2025-09-06', NULL, NULL, 'presente', NULL, '2025-09-23 16:13:44'),
(125, 96, 86, 10, '2025-09-06', NULL, NULL, 'presente', NULL, '2025-09-23 16:13:44'),
(126, 127, 86, 10, '2025-09-06', NULL, NULL, 'presente', NULL, '2025-09-23 16:13:44'),
(127, 144, 86, 10, '2025-09-06', NULL, NULL, 'presente', NULL, '2025-09-23 16:13:44'),
(128, 146, 86, 10, '2025-09-06', NULL, NULL, 'ausente', NULL, '2025-09-23 16:13:44'),
(129, 147, 86, 10, '2025-09-06', NULL, NULL, 'ausente', NULL, '2025-09-23 16:13:44'),
(130, 61, 86, 3, '2025-09-06', NULL, NULL, 'ausente', NULL, '2025-09-23 16:14:55'),
(131, 65, 86, 3, '2025-09-06', NULL, NULL, 'presente', NULL, '2025-09-23 16:14:55'),
(132, 66, 86, 3, '2025-09-06', NULL, NULL, 'presente', NULL, '2025-09-23 16:14:55'),
(133, 71, 86, 3, '2025-09-06', NULL, NULL, 'ausente', NULL, '2025-09-23 16:14:55'),
(134, 82, 86, 3, '2025-09-06', NULL, NULL, 'presente', NULL, '2025-09-23 16:14:55'),
(135, 83, 86, 3, '2025-09-06', NULL, NULL, 'ausente', NULL, '2025-09-23 16:14:55'),
(136, 84, 86, 3, '2025-09-06', NULL, NULL, 'ausente', NULL, '2025-09-23 16:14:55'),
(137, 85, 86, 3, '2025-09-06', NULL, NULL, 'presente', NULL, '2025-09-23 16:14:55'),
(138, 87, 86, 3, '2025-09-06', NULL, NULL, 'presente', NULL, '2025-09-23 16:14:55'),
(139, 88, 86, 3, '2025-09-06', NULL, NULL, 'presente', NULL, '2025-09-23 16:14:55'),
(140, 102, 86, 3, '2025-09-06', NULL, NULL, 'presente', NULL, '2025-09-23 16:14:55'),
(141, 128, 86, 3, '2025-09-06', NULL, NULL, 'presente', NULL, '2025-09-23 16:14:55'),
(142, 131, 86, 3, '2025-09-06', NULL, NULL, 'ausente', NULL, '2025-09-23 16:14:55'),
(143, 143, 86, 3, '2025-09-06', NULL, NULL, 'ausente', NULL, '2025-09-23 16:14:55'),
(144, 145, 86, 3, '2025-09-06', NULL, NULL, 'presente', NULL, '2025-09-23 16:14:55'),
(145, 95, 86, 10, '2025-09-13', NULL, NULL, 'presente', NULL, '2025-09-23 16:18:45'),
(146, 96, 86, 10, '2025-09-13', NULL, NULL, 'presente', NULL, '2025-09-23 16:18:45'),
(147, 127, 86, 10, '2025-09-13', NULL, NULL, 'presente', NULL, '2025-09-23 16:18:45'),
(148, 144, 86, 10, '2025-09-13', NULL, NULL, 'ausente', NULL, '2025-09-23 16:18:45'),
(149, 146, 86, 10, '2025-09-13', NULL, NULL, 'presente', NULL, '2025-09-23 16:18:45'),
(150, 147, 86, 10, '2025-09-13', NULL, NULL, 'ausente', NULL, '2025-09-23 16:18:45'),
(151, 61, 86, 3, '2025-09-13', NULL, NULL, 'presente', NULL, '2025-09-23 16:22:03'),
(152, 65, 86, 3, '2025-09-13', NULL, NULL, 'presente', NULL, '2025-09-23 16:22:03'),
(153, 66, 86, 3, '2025-09-13', NULL, NULL, 'presente', NULL, '2025-09-23 16:22:03'),
(154, 71, 86, 3, '2025-09-13', NULL, NULL, 'presente', NULL, '2025-09-23 16:22:03'),
(155, 82, 86, 3, '2025-09-13', NULL, NULL, 'presente', NULL, '2025-09-23 16:22:03'),
(156, 83, 86, 3, '2025-09-13', NULL, NULL, 'ausente', NULL, '2025-09-23 16:22:03'),
(157, 84, 86, 3, '2025-09-13', NULL, NULL, 'ausente', NULL, '2025-09-23 16:22:03'),
(158, 85, 86, 3, '2025-09-13', NULL, NULL, 'presente', NULL, '2025-09-23 16:22:03'),
(159, 87, 86, 3, '2025-09-13', NULL, NULL, 'presente', NULL, '2025-09-23 16:22:03'),
(160, 88, 86, 3, '2025-09-13', NULL, NULL, 'presente', NULL, '2025-09-23 16:22:03'),
(161, 102, 86, 3, '2025-09-13', NULL, NULL, 'presente', NULL, '2025-09-23 16:22:03'),
(162, 128, 86, 3, '2025-09-13', NULL, NULL, 'ausente', NULL, '2025-09-23 16:22:03'),
(163, 131, 86, 3, '2025-09-13', NULL, NULL, 'ausente', NULL, '2025-09-23 16:22:03'),
(164, 143, 86, 3, '2025-09-13', NULL, NULL, 'ausente', NULL, '2025-09-23 16:22:03'),
(165, 145, 86, 3, '2025-09-13', NULL, NULL, 'presente', NULL, '2025-09-23 16:22:03'),
(166, 95, 86, 10, '2025-09-20', NULL, NULL, 'presente', NULL, '2025-09-23 16:29:46'),
(167, 96, 86, 10, '2025-09-20', NULL, NULL, 'presente', NULL, '2025-09-23 16:29:46'),
(168, 127, 86, 10, '2025-09-20', NULL, NULL, 'presente', NULL, '2025-09-23 16:29:46'),
(169, 144, 86, 10, '2025-09-20', NULL, NULL, 'presente', NULL, '2025-09-23 16:29:46'),
(170, 146, 86, 10, '2025-09-20', NULL, NULL, 'presente', NULL, '2025-09-23 16:29:46'),
(171, 147, 86, 10, '2025-09-20', NULL, NULL, 'presente', NULL, '2025-09-23 16:29:46'),
(172, 61, 86, 3, '2025-09-20', NULL, NULL, 'presente', NULL, '2025-09-23 16:31:12'),
(173, 65, 86, 3, '2025-09-20', NULL, NULL, 'presente', NULL, '2025-09-23 16:31:12'),
(174, 66, 86, 3, '2025-09-20', NULL, NULL, 'presente', NULL, '2025-09-23 16:31:12'),
(175, 71, 86, 3, '2025-09-20', NULL, NULL, 'presente', NULL, '2025-09-23 16:31:12'),
(176, 82, 86, 3, '2025-09-20', NULL, NULL, 'presente', NULL, '2025-09-23 16:31:12'),
(177, 83, 86, 3, '2025-09-20', NULL, NULL, 'ausente', NULL, '2025-09-23 16:31:12'),
(178, 84, 86, 3, '2025-09-20', NULL, NULL, 'ausente', NULL, '2025-09-23 16:31:12'),
(179, 85, 86, 3, '2025-09-20', NULL, NULL, 'presente', NULL, '2025-09-23 16:31:12'),
(180, 87, 86, 3, '2025-09-20', NULL, NULL, 'presente', NULL, '2025-09-23 16:31:12'),
(181, 88, 86, 3, '2025-09-20', NULL, NULL, 'presente', NULL, '2025-09-23 16:31:12'),
(182, 102, 86, 3, '2025-09-20', NULL, NULL, 'presente', NULL, '2025-09-23 16:31:12'),
(183, 128, 86, 3, '2025-09-20', NULL, NULL, 'ausente', NULL, '2025-09-23 16:31:12'),
(184, 131, 86, 3, '2025-09-20', NULL, NULL, 'ausente', NULL, '2025-09-23 16:31:12'),
(185, 143, 86, 3, '2025-09-20', NULL, NULL, 'presente', NULL, '2025-09-23 16:31:12'),
(186, 145, 86, 3, '2025-09-20', NULL, NULL, 'ausente', NULL, '2025-09-23 16:31:12'),
(187, 95, 86, 10, '2025-09-27', NULL, NULL, 'presente', NULL, '2025-09-27 20:07:32'),
(188, 96, 86, 10, '2025-09-27', NULL, NULL, 'presente', NULL, '2025-09-27 20:07:32'),
(189, 127, 86, 10, '2025-09-27', NULL, NULL, 'presente', NULL, '2025-09-27 20:07:32'),
(190, 144, 86, 10, '2025-09-27', NULL, NULL, 'ausente', NULL, '2025-09-27 20:07:32'),
(191, 146, 86, 10, '2025-09-27', NULL, NULL, 'presente', NULL, '2025-09-27 20:07:32'),
(192, 147, 86, 10, '2025-09-27', NULL, NULL, 'presente', NULL, '2025-09-27 20:07:32'),
(193, 61, 86, 3, '2025-09-27', NULL, NULL, 'presente', NULL, '2025-09-27 20:09:37'),
(194, 65, 86, 3, '2025-09-27', NULL, NULL, 'presente', NULL, '2025-09-27 20:09:37'),
(195, 66, 86, 3, '2025-09-27', NULL, NULL, 'presente', NULL, '2025-09-27 20:09:37'),
(196, 71, 86, 3, '2025-09-27', NULL, NULL, 'presente', NULL, '2025-09-27 20:09:37'),
(197, 82, 86, 3, '2025-09-27', NULL, NULL, 'presente', NULL, '2025-09-27 20:09:37'),
(198, 83, 86, 3, '2025-09-27', NULL, NULL, 'ausente', NULL, '2025-09-27 20:09:37'),
(199, 84, 86, 3, '2025-09-27', NULL, NULL, 'ausente', NULL, '2025-09-27 20:09:37'),
(200, 85, 86, 3, '2025-09-27', NULL, NULL, 'presente', NULL, '2025-09-27 20:09:37'),
(201, 87, 86, 3, '2025-09-27', NULL, NULL, 'presente', NULL, '2025-09-27 20:09:37'),
(202, 88, 86, 3, '2025-09-27', NULL, NULL, 'presente', NULL, '2025-09-27 20:09:37'),
(203, 102, 86, 3, '2025-09-27', NULL, NULL, 'presente', NULL, '2025-09-27 20:09:37'),
(204, 128, 86, 3, '2025-09-27', NULL, NULL, 'presente', NULL, '2025-09-27 20:09:37'),
(205, 131, 86, 3, '2025-09-27', NULL, NULL, 'ausente', NULL, '2025-09-27 20:09:37'),
(206, 143, 86, 3, '2025-09-27', NULL, NULL, 'ausente', NULL, '2025-09-27 20:09:37'),
(207, 145, 86, 3, '2025-09-27', NULL, NULL, 'ausente', NULL, '2025-09-27 20:09:37'),
(208, 60, 75, 2, '2025-08-01', NULL, NULL, 'ausente', NULL, '2025-10-06 01:31:15'),
(209, 101, 75, 2, '2025-08-01', NULL, NULL, 'ausente', NULL, '2025-10-06 01:31:15'),
(211, 104, 75, 2, '2025-08-01', NULL, NULL, 'ausente', NULL, '2025-10-06 01:31:15'),
(212, 128, 75, 2, '2025-08-01', NULL, NULL, 'presente', NULL, '2025-10-06 01:31:15'),
(213, 141, 75, 2, '2025-08-01', NULL, NULL, 'ausente', NULL, '2025-10-06 01:31:15'),
(214, 143, 75, 2, '2025-08-01', NULL, NULL, 'presente', NULL, '2025-10-06 01:31:15'),
(215, 60, 75, 2, '2025-08-06', NULL, NULL, 'ausente', NULL, '2025-10-06 01:32:21'),
(216, 101, 75, 2, '2025-08-06', NULL, NULL, 'ausente', NULL, '2025-10-06 01:32:21'),
(218, 104, 75, 2, '2025-08-06', NULL, NULL, 'ausente', NULL, '2025-10-06 01:32:21'),
(219, 128, 75, 2, '2025-08-06', NULL, NULL, 'presente', NULL, '2025-10-06 01:32:21'),
(220, 141, 75, 2, '2025-08-06', NULL, NULL, 'ausente', NULL, '2025-10-06 01:32:21'),
(221, 143, 75, 2, '2025-08-06', NULL, NULL, 'presente', NULL, '2025-10-06 01:32:21'),
(222, 60, 75, 2, '2025-08-08', NULL, NULL, 'ausente', NULL, '2025-10-06 01:33:53'),
(223, 101, 75, 2, '2025-08-08', NULL, NULL, 'ausente', NULL, '2025-10-06 01:33:53'),
(225, 104, 75, 2, '2025-08-08', NULL, NULL, 'ausente', NULL, '2025-10-06 01:33:53'),
(226, 128, 75, 2, '2025-08-08', NULL, NULL, 'presente', NULL, '2025-10-06 01:33:53'),
(227, 141, 75, 2, '2025-08-08', NULL, NULL, 'ausente', NULL, '2025-10-06 01:33:53'),
(228, 143, 75, 2, '2025-08-08', NULL, NULL, 'ausente', NULL, '2025-10-06 01:33:53'),
(229, 60, 75, 2, '2025-08-13', NULL, NULL, 'ausente', NULL, '2025-10-06 01:36:21'),
(230, 101, 75, 2, '2025-08-13', NULL, NULL, 'ausente', NULL, '2025-10-06 01:36:21'),
(232, 104, 75, 2, '2025-08-13', NULL, NULL, 'ausente', NULL, '2025-10-06 01:36:21'),
(233, 128, 75, 2, '2025-08-13', NULL, NULL, 'ausente', NULL, '2025-10-06 01:36:21'),
(234, 141, 75, 2, '2025-08-13', NULL, NULL, 'ausente', NULL, '2025-10-06 01:36:21'),
(235, 143, 75, 2, '2025-08-13', NULL, NULL, 'presente', NULL, '2025-10-06 01:36:21'),
(236, 60, 75, 2, '2025-08-15', NULL, NULL, 'ausente', NULL, '2025-10-06 01:37:23'),
(237, 101, 75, 2, '2025-08-15', NULL, NULL, 'ausente', NULL, '2025-10-06 01:37:23'),
(239, 104, 75, 2, '2025-08-15', NULL, NULL, 'ausente', NULL, '2025-10-06 01:37:23'),
(240, 128, 75, 2, '2025-08-15', NULL, NULL, 'presente', NULL, '2025-10-06 01:37:23'),
(241, 141, 75, 2, '2025-08-15', NULL, NULL, 'ausente', NULL, '2025-10-06 01:37:23'),
(242, 143, 75, 2, '2025-08-15', NULL, NULL, 'ausente', NULL, '2025-10-06 01:37:23'),
(243, 60, 75, 2, '2025-08-20', NULL, NULL, 'ausente', NULL, '2025-10-06 01:38:00'),
(244, 101, 75, 2, '2025-08-20', NULL, NULL, 'ausente', NULL, '2025-10-06 01:38:00'),
(246, 104, 75, 2, '2025-08-20', NULL, NULL, 'ausente', NULL, '2025-10-06 01:38:00'),
(247, 128, 75, 2, '2025-08-20', NULL, NULL, 'ausente', NULL, '2025-10-06 01:38:00'),
(248, 141, 75, 2, '2025-08-20', NULL, NULL, 'ausente', NULL, '2025-10-06 01:38:00'),
(249, 143, 75, 2, '2025-08-20', NULL, NULL, 'ausente', NULL, '2025-10-06 01:38:00'),
(250, 60, 75, 2, '2025-08-22', NULL, NULL, 'ausente', NULL, '2025-10-06 01:39:00'),
(251, 101, 75, 2, '2025-08-22', NULL, NULL, 'ausente', NULL, '2025-10-06 01:39:00'),
(253, 104, 75, 2, '2025-08-22', NULL, NULL, 'ausente', NULL, '2025-10-06 01:39:00'),
(254, 128, 75, 2, '2025-08-22', NULL, NULL, 'ausente', NULL, '2025-10-06 01:39:00'),
(255, 141, 75, 2, '2025-08-22', NULL, NULL, 'ausente', NULL, '2025-10-06 01:39:00'),
(256, 143, 75, 2, '2025-08-22', NULL, NULL, 'presente', NULL, '2025-10-06 01:39:00'),
(257, 60, 75, 2, '2025-08-27', NULL, NULL, 'ausente', NULL, '2025-10-06 01:40:26'),
(258, 101, 75, 2, '2025-08-27', NULL, NULL, 'ausente', NULL, '2025-10-06 01:40:26'),
(260, 104, 75, 2, '2025-08-27', NULL, NULL, 'ausente', NULL, '2025-10-06 01:40:26'),
(261, 128, 75, 2, '2025-08-27', NULL, NULL, 'presente', NULL, '2025-10-06 01:40:26'),
(262, 141, 75, 2, '2025-08-27', NULL, NULL, 'ausente', NULL, '2025-10-06 01:40:26'),
(263, 143, 75, 2, '2025-08-27', NULL, NULL, 'presente', NULL, '2025-10-06 01:40:26'),
(264, 60, 75, 2, '2025-08-29', NULL, NULL, 'ausente', NULL, '2025-10-06 01:41:34'),
(265, 101, 75, 2, '2025-08-29', NULL, NULL, 'ausente', NULL, '2025-10-06 01:41:34'),
(267, 104, 75, 2, '2025-08-29', NULL, NULL, 'ausente', NULL, '2025-10-06 01:41:34'),
(268, 128, 75, 2, '2025-08-29', NULL, NULL, 'ausente', NULL, '2025-10-06 01:41:34'),
(269, 141, 75, 2, '2025-08-29', NULL, NULL, 'ausente', NULL, '2025-10-06 01:41:34'),
(270, 143, 75, 2, '2025-08-29', NULL, NULL, 'presente', NULL, '2025-10-06 01:41:34'),
(271, 95, 86, 10, '2025-10-04', NULL, NULL, 'presente', NULL, '2025-10-08 16:51:29'),
(272, 96, 86, 10, '2025-10-04', NULL, NULL, 'presente', NULL, '2025-10-08 16:51:29'),
(273, 127, 86, 10, '2025-10-04', NULL, NULL, 'presente', NULL, '2025-10-08 16:51:29'),
(274, 144, 86, 10, '2025-10-04', NULL, NULL, 'presente', NULL, '2025-10-08 16:51:29'),
(275, 146, 86, 10, '2025-10-04', NULL, NULL, 'presente', NULL, '2025-10-08 16:51:29'),
(276, 147, 86, 10, '2025-10-04', NULL, NULL, 'presente', NULL, '2025-10-08 16:51:29'),
(277, 61, 86, 3, '2025-10-04', NULL, NULL, 'presente', NULL, '2025-10-08 16:53:43'),
(278, 65, 86, 3, '2025-10-04', NULL, NULL, 'ausente', NULL, '2025-10-08 16:53:43'),
(279, 66, 86, 3, '2025-10-04', NULL, NULL, 'ausente', NULL, '2025-10-08 16:53:43'),
(280, 71, 86, 3, '2025-10-04', NULL, NULL, 'presente', NULL, '2025-10-08 16:53:43'),
(281, 82, 86, 3, '2025-10-04', NULL, NULL, 'presente', NULL, '2025-10-08 16:53:43'),
(282, 83, 86, 3, '2025-10-04', NULL, NULL, 'ausente', NULL, '2025-10-08 16:53:43'),
(283, 84, 86, 3, '2025-10-04', NULL, NULL, 'ausente', NULL, '2025-10-08 16:53:43'),
(284, 85, 86, 3, '2025-10-04', NULL, NULL, 'presente', NULL, '2025-10-08 16:53:43'),
(285, 87, 86, 3, '2025-10-04', NULL, NULL, 'presente', NULL, '2025-10-08 16:53:43'),
(286, 88, 86, 3, '2025-10-04', NULL, NULL, 'presente', NULL, '2025-10-08 16:53:43'),
(287, 102, 86, 3, '2025-10-04', NULL, NULL, 'presente', NULL, '2025-10-08 16:53:43'),
(288, 128, 86, 3, '2025-10-04', NULL, NULL, 'presente', NULL, '2025-10-08 16:53:43'),
(289, 131, 86, 3, '2025-10-04', NULL, NULL, 'ausente', NULL, '2025-10-08 16:53:43'),
(290, 143, 86, 3, '2025-10-04', NULL, NULL, 'presente', NULL, '2025-10-08 16:53:43'),
(291, 145, 86, 3, '2025-10-04', NULL, NULL, 'ausente', NULL, '2025-10-08 16:53:43'),
(292, 60, 75, 2, '2025-10-01', NULL, NULL, 'ausente', NULL, '2025-10-17 21:26:29'),
(293, 101, 75, 2, '2025-10-01', NULL, NULL, 'ausente', NULL, '2025-10-17 21:26:29'),
(295, 104, 75, 2, '2025-10-01', NULL, NULL, 'ausente', NULL, '2025-10-17 21:26:29'),
(296, 128, 75, 2, '2025-10-01', NULL, NULL, 'presente', NULL, '2025-10-17 21:26:29'),
(297, 141, 75, 2, '2025-10-01', NULL, NULL, 'ausente', NULL, '2025-10-17 21:26:29'),
(298, 143, 75, 2, '2025-10-01', NULL, NULL, 'ausente', NULL, '2025-10-17 21:26:29'),
(299, 60, 75, 2, '2025-10-03', NULL, NULL, 'ausente', NULL, '2025-10-17 21:26:51'),
(300, 101, 75, 2, '2025-10-03', NULL, NULL, 'ausente', NULL, '2025-10-17 21:26:51'),
(302, 104, 75, 2, '2025-10-03', NULL, NULL, 'ausente', NULL, '2025-10-17 21:26:51'),
(303, 128, 75, 2, '2025-10-03', NULL, NULL, 'ausente', NULL, '2025-10-17 21:26:51'),
(304, 141, 75, 2, '2025-10-03', NULL, NULL, 'ausente', NULL, '2025-10-17 21:26:51'),
(305, 143, 75, 2, '2025-10-03', NULL, NULL, 'ausente', NULL, '2025-10-17 21:26:51'),
(306, 60, 75, 2, '2025-10-08', NULL, NULL, 'ausente', NULL, '2025-10-17 21:27:20'),
(307, 101, 75, 2, '2025-10-08', NULL, NULL, 'ausente', NULL, '2025-10-17 21:27:20'),
(309, 104, 75, 2, '2025-10-08', NULL, NULL, 'ausente', NULL, '2025-10-17 21:27:20'),
(310, 128, 75, 2, '2025-10-08', NULL, NULL, 'presente', NULL, '2025-10-17 21:27:20'),
(311, 141, 75, 2, '2025-10-08', NULL, NULL, 'ausente', NULL, '2025-10-17 21:27:20'),
(312, 143, 75, 2, '2025-10-08', NULL, NULL, 'ausente', NULL, '2025-10-17 21:27:20'),
(313, 60, 75, 2, '2025-10-10', NULL, NULL, 'ausente', NULL, '2025-10-17 21:27:43'),
(314, 101, 75, 2, '2025-10-10', NULL, NULL, 'ausente', NULL, '2025-10-17 21:27:43'),
(316, 104, 75, 2, '2025-10-10', NULL, NULL, 'ausente', NULL, '2025-10-17 21:27:43'),
(317, 128, 75, 2, '2025-10-10', NULL, NULL, 'presente', NULL, '2025-10-17 21:27:43'),
(318, 141, 75, 2, '2025-10-10', NULL, NULL, 'ausente', NULL, '2025-10-17 21:27:43'),
(319, 143, 75, 2, '2025-10-10', NULL, NULL, 'ausente', NULL, '2025-10-17 21:27:43'),
(320, 60, 75, 2, '2025-10-15', NULL, NULL, 'ausente', NULL, '2025-10-17 21:28:14'),
(321, 101, 75, 2, '2025-10-15', NULL, NULL, 'ausente', NULL, '2025-10-17 21:28:14'),
(323, 104, 75, 2, '2025-10-15', NULL, NULL, 'ausente', NULL, '2025-10-17 21:28:14'),
(324, 128, 75, 2, '2025-10-15', NULL, NULL, 'presente', NULL, '2025-10-17 21:28:14'),
(325, 141, 75, 2, '2025-10-15', NULL, NULL, 'ausente', NULL, '2025-10-17 21:28:14'),
(326, 143, 75, 2, '2025-10-15', NULL, NULL, 'ausente', NULL, '2025-10-17 21:28:14'),
(327, 95, 86, 10, '2025-10-11', NULL, NULL, 'presente', NULL, '2025-11-11 14:22:42'),
(328, 96, 86, 10, '2025-10-11', NULL, NULL, 'presente', NULL, '2025-11-11 14:22:42'),
(329, 127, 86, 10, '2025-10-11', NULL, NULL, 'presente', NULL, '2025-11-11 14:22:42'),
(330, 144, 86, 10, '2025-10-11', NULL, NULL, 'presente', NULL, '2025-11-11 14:22:42'),
(331, 146, 86, 10, '2025-10-11', NULL, NULL, 'presente', NULL, '2025-11-11 14:22:42'),
(332, 147, 86, 10, '2025-10-11', NULL, NULL, 'ausente', NULL, '2025-11-11 14:22:42'),
(333, 61, 86, 3, '2025-10-11', NULL, NULL, 'ausente', NULL, '2025-11-11 14:24:00'),
(334, 65, 86, 3, '2025-10-11', NULL, NULL, 'ausente', NULL, '2025-11-11 14:24:00'),
(335, 66, 86, 3, '2025-10-11', NULL, NULL, 'ausente', NULL, '2025-11-11 14:24:00'),
(336, 71, 86, 3, '2025-10-11', NULL, NULL, 'presente', NULL, '2025-11-11 14:24:00'),
(337, 82, 86, 3, '2025-10-11', NULL, NULL, 'presente', NULL, '2025-11-11 14:24:00'),
(338, 83, 86, 3, '2025-10-11', NULL, NULL, 'ausente', NULL, '2025-11-11 14:24:00'),
(339, 84, 86, 3, '2025-10-11', NULL, NULL, 'ausente', NULL, '2025-11-11 14:24:00'),
(340, 85, 86, 3, '2025-10-11', NULL, NULL, 'presente', NULL, '2025-11-11 14:24:00'),
(341, 87, 86, 3, '2025-10-11', NULL, NULL, 'presente', NULL, '2025-11-11 14:24:00'),
(342, 88, 86, 3, '2025-10-11', NULL, NULL, 'presente', NULL, '2025-11-11 14:24:00'),
(343, 102, 86, 3, '2025-10-11', NULL, NULL, 'presente', NULL, '2025-11-11 14:24:00'),
(344, 128, 86, 3, '2025-10-11', NULL, NULL, 'presente', NULL, '2025-11-11 14:24:00'),
(345, 131, 86, 3, '2025-10-11', NULL, NULL, 'ausente', NULL, '2025-11-11 14:24:00'),
(346, 143, 86, 3, '2025-10-11', NULL, NULL, 'presente', NULL, '2025-11-11 14:24:00'),
(347, 145, 86, 3, '2025-10-11', NULL, NULL, 'presente', NULL, '2025-11-11 14:24:00'),
(348, 95, 86, 10, '2025-10-18', NULL, NULL, 'presente', NULL, '2025-11-11 14:27:24'),
(349, 96, 86, 10, '2025-10-18', NULL, NULL, 'presente', NULL, '2025-11-11 14:27:24'),
(350, 127, 86, 10, '2025-10-18', NULL, NULL, 'ausente', NULL, '2025-11-11 14:27:24'),
(351, 144, 86, 10, '2025-10-18', NULL, NULL, 'presente', NULL, '2025-11-11 14:27:24'),
(352, 146, 86, 10, '2025-10-18', NULL, NULL, 'presente', NULL, '2025-11-11 14:27:24'),
(353, 147, 86, 10, '2025-10-18', NULL, NULL, 'presente', NULL, '2025-11-11 14:27:24'),
(354, 61, 86, 3, '2025-10-18', NULL, NULL, 'ausente', NULL, '2025-11-11 14:28:52'),
(355, 65, 86, 3, '2025-10-18', NULL, NULL, 'ausente', NULL, '2025-11-11 14:28:52'),
(356, 66, 86, 3, '2025-10-18', NULL, NULL, 'ausente', NULL, '2025-11-11 14:28:52'),
(357, 71, 86, 3, '2025-10-18', NULL, NULL, 'ausente', NULL, '2025-11-11 14:28:52'),
(358, 82, 86, 3, '2025-10-18', NULL, NULL, 'presente', NULL, '2025-11-11 14:28:52'),
(359, 83, 86, 3, '2025-10-18', NULL, NULL, 'ausente', NULL, '2025-11-11 14:28:52'),
(360, 84, 86, 3, '2025-10-18', NULL, NULL, 'ausente', NULL, '2025-11-11 14:28:52'),
(361, 85, 86, 3, '2025-10-18', NULL, NULL, 'presente', NULL, '2025-11-11 14:28:52'),
(362, 87, 86, 3, '2025-10-18', NULL, NULL, 'presente', NULL, '2025-11-11 14:28:52'),
(363, 88, 86, 3, '2025-10-18', NULL, NULL, 'presente', NULL, '2025-11-11 14:28:52'),
(364, 102, 86, 3, '2025-10-18', NULL, NULL, 'ausente', NULL, '2025-11-11 14:28:52'),
(365, 128, 86, 3, '2025-10-18', NULL, NULL, 'presente', NULL, '2025-11-11 14:28:52'),
(366, 131, 86, 3, '2025-10-18', NULL, NULL, 'ausente', NULL, '2025-11-11 14:28:52'),
(367, 143, 86, 3, '2025-10-18', NULL, NULL, 'ausente', NULL, '2025-11-11 14:28:52'),
(368, 145, 86, 3, '2025-10-18', NULL, NULL, 'ausente', NULL, '2025-11-11 14:28:52'),
(369, 95, 86, 10, '2025-10-25', NULL, NULL, 'presente', NULL, '2025-11-11 14:43:05'),
(370, 96, 86, 10, '2025-10-25', NULL, NULL, 'presente', NULL, '2025-11-11 14:43:05'),
(371, 127, 86, 10, '2025-10-25', NULL, NULL, 'presente', NULL, '2025-11-11 14:43:05'),
(372, 144, 86, 10, '2025-10-25', NULL, NULL, 'presente', NULL, '2025-11-11 14:43:05'),
(373, 146, 86, 10, '2025-10-25', NULL, NULL, 'ausente', NULL, '2025-11-11 14:43:05'),
(374, 147, 86, 10, '2025-10-25', NULL, NULL, 'presente', NULL, '2025-11-11 14:43:05'),
(375, 61, 86, 3, '2025-10-25', NULL, NULL, 'ausente', NULL, '2025-11-11 14:48:43'),
(376, 65, 86, 3, '2025-10-25', NULL, NULL, 'ausente', NULL, '2025-11-11 14:48:43'),
(377, 66, 86, 3, '2025-10-25', NULL, NULL, 'ausente', NULL, '2025-11-11 14:48:43'),
(378, 71, 86, 3, '2025-10-25', NULL, NULL, 'presente', NULL, '2025-11-11 14:48:43'),
(379, 82, 86, 3, '2025-10-25', NULL, NULL, 'presente', NULL, '2025-11-11 14:48:43'),
(380, 83, 86, 3, '2025-10-25', NULL, NULL, 'ausente', NULL, '2025-11-11 14:48:43'),
(381, 84, 86, 3, '2025-10-25', NULL, NULL, 'ausente', NULL, '2025-11-11 14:48:43'),
(382, 85, 86, 3, '2025-10-25', NULL, NULL, 'ausente', NULL, '2025-11-11 14:48:43'),
(383, 87, 86, 3, '2025-10-25', NULL, NULL, 'presente', NULL, '2025-11-11 14:48:43'),
(384, 88, 86, 3, '2025-10-25', NULL, NULL, 'presente', NULL, '2025-11-11 14:48:43'),
(385, 102, 86, 3, '2025-10-25', NULL, NULL, 'presente', NULL, '2025-11-11 14:48:43'),
(386, 128, 86, 3, '2025-10-25', NULL, NULL, 'ausente', NULL, '2025-11-11 14:48:43'),
(387, 131, 86, 3, '2025-10-25', NULL, NULL, 'ausente', NULL, '2025-11-11 14:48:43'),
(388, 143, 86, 3, '2025-10-25', NULL, NULL, 'ausente', NULL, '2025-11-11 14:48:43'),
(389, 145, 86, 3, '2025-10-25', NULL, NULL, 'ausente', NULL, '2025-11-11 14:48:43'),
(390, 95, 86, 10, '2025-11-08', NULL, NULL, 'ausente', NULL, '2025-11-11 14:51:04'),
(391, 96, 86, 10, '2025-11-08', NULL, NULL, 'presente', NULL, '2025-11-11 14:51:04'),
(392, 127, 86, 10, '2025-11-08', NULL, NULL, 'presente', NULL, '2025-11-11 14:51:04'),
(393, 144, 86, 10, '2025-11-08', NULL, NULL, 'presente', NULL, '2025-11-11 14:51:04'),
(394, 146, 86, 10, '2025-11-08', NULL, NULL, 'presente', NULL, '2025-11-11 14:51:04'),
(395, 147, 86, 10, '2025-11-08', NULL, NULL, 'presente', NULL, '2025-11-11 14:51:04'),
(396, 61, 86, 3, '2025-11-08', NULL, NULL, 'presente', NULL, '2025-11-11 14:53:36'),
(397, 65, 86, 3, '2025-11-08', NULL, NULL, 'ausente', NULL, '2025-11-11 14:53:36'),
(398, 66, 86, 3, '2025-11-08', NULL, NULL, 'ausente', NULL, '2025-11-11 14:53:36'),
(399, 71, 86, 3, '2025-11-08', NULL, NULL, 'presente', NULL, '2025-11-11 14:53:36'),
(400, 82, 86, 3, '2025-11-08', NULL, NULL, 'presente', NULL, '2025-11-11 14:53:36'),
(401, 83, 86, 3, '2025-11-08', NULL, NULL, 'ausente', NULL, '2025-11-11 14:53:36'),
(402, 84, 86, 3, '2025-11-08', NULL, NULL, 'ausente', NULL, '2025-11-11 14:53:36'),
(403, 85, 86, 3, '2025-11-08', NULL, NULL, 'presente', NULL, '2025-11-11 14:53:36'),
(404, 87, 86, 3, '2025-11-08', NULL, NULL, 'presente', NULL, '2025-11-11 14:53:36'),
(405, 88, 86, 3, '2025-11-08', NULL, NULL, 'presente', NULL, '2025-11-11 14:53:36'),
(406, 102, 86, 3, '2025-11-08', NULL, NULL, 'ausente', NULL, '2025-11-11 14:53:36'),
(407, 128, 86, 3, '2025-11-08', NULL, NULL, 'presente', NULL, '2025-11-11 14:53:36'),
(408, 131, 86, 3, '2025-11-08', NULL, NULL, 'ausente', NULL, '2025-11-11 14:53:36'),
(409, 143, 86, 3, '2025-11-08', NULL, NULL, 'ausente', NULL, '2025-11-11 14:53:36'),
(410, 145, 86, 3, '2025-11-08', NULL, NULL, 'ausente', NULL, '2025-11-11 14:53:36'),
(411, 95, 86, 10, '2025-11-15', NULL, NULL, 'ausente', NULL, '2025-11-15 18:50:42'),
(412, 96, 86, 10, '2025-11-15', NULL, NULL, 'ausente', NULL, '2025-11-15 18:50:42'),
(413, 127, 86, 10, '2025-11-15', NULL, NULL, 'presente', NULL, '2025-11-15 18:50:42'),
(414, 144, 86, 10, '2025-11-15', NULL, NULL, 'presente', NULL, '2025-11-15 18:50:42'),
(415, 146, 86, 10, '2025-11-15', NULL, NULL, 'presente', NULL, '2025-11-15 18:50:42'),
(416, 147, 86, 10, '2025-11-15', NULL, NULL, 'ausente', NULL, '2025-11-15 18:50:42'),
(417, 61, 86, 3, '2025-11-15', NULL, NULL, 'presente', NULL, '2025-11-15 18:52:05'),
(418, 65, 86, 3, '2025-11-15', NULL, NULL, 'presente', NULL, '2025-11-15 18:52:05'),
(419, 66, 86, 3, '2025-11-15', NULL, NULL, 'presente', NULL, '2025-11-15 18:52:05'),
(420, 71, 86, 3, '2025-11-15', NULL, NULL, 'presente', NULL, '2025-11-15 18:52:05'),
(421, 82, 86, 3, '2025-11-15', NULL, NULL, 'presente', NULL, '2025-11-15 18:52:05'),
(422, 83, 86, 3, '2025-11-15', NULL, NULL, 'ausente', NULL, '2025-11-15 18:52:05'),
(423, 84, 86, 3, '2025-11-15', NULL, NULL, 'ausente', NULL, '2025-11-15 18:52:05'),
(424, 85, 86, 3, '2025-11-15', NULL, NULL, 'ausente', NULL, '2025-11-15 18:52:05'),
(425, 87, 86, 3, '2025-11-15', NULL, NULL, 'presente', NULL, '2025-11-15 18:52:05'),
(426, 88, 86, 3, '2025-11-15', NULL, NULL, 'ausente', NULL, '2025-11-15 18:52:05'),
(427, 102, 86, 3, '2025-11-15', NULL, NULL, 'presente', NULL, '2025-11-15 18:52:05'),
(428, 128, 86, 3, '2025-11-15', NULL, NULL, 'ausente', NULL, '2025-11-15 18:52:05'),
(429, 131, 86, 3, '2025-11-15', NULL, NULL, 'presente', NULL, '2025-11-15 18:52:05'),
(430, 143, 86, 3, '2025-11-15', NULL, NULL, 'ausente', NULL, '2025-11-15 18:52:05'),
(431, 145, 86, 3, '2025-11-15', NULL, NULL, 'ausente', NULL, '2025-11-15 18:52:05'),
(438, 95, 86, 10, '2025-11-22', NULL, NULL, 'presente', NULL, '2025-12-13 15:22:39'),
(439, 96, 86, 10, '2025-11-22', NULL, NULL, 'presente', NULL, '2025-12-13 15:22:39'),
(440, 127, 86, 10, '2025-11-22', NULL, NULL, 'ausente', NULL, '2025-12-13 15:22:39'),
(441, 144, 86, 10, '2025-11-22', NULL, NULL, 'presente', NULL, '2025-12-13 15:22:39'),
(442, 146, 86, 10, '2025-11-22', NULL, NULL, 'presente', NULL, '2025-12-13 15:22:39'),
(443, 147, 86, 10, '2025-11-22', NULL, NULL, 'ausente', NULL, '2025-12-13 15:22:39'),
(444, 61, 86, 3, '2025-11-22', NULL, NULL, 'presente', NULL, '2025-12-13 15:25:06'),
(445, 65, 86, 3, '2025-11-22', NULL, NULL, 'ausente', NULL, '2025-12-13 15:25:06'),
(446, 66, 86, 3, '2025-11-22', NULL, NULL, 'ausente', NULL, '2025-12-13 15:25:06'),
(447, 71, 86, 3, '2025-11-22', NULL, NULL, 'presente', NULL, '2025-12-13 15:25:06'),
(448, 82, 86, 3, '2025-11-22', NULL, NULL, 'presente', NULL, '2025-12-13 15:25:06'),
(449, 83, 86, 3, '2025-11-22', NULL, NULL, 'ausente', NULL, '2025-12-13 15:25:06'),
(450, 84, 86, 3, '2025-11-22', NULL, NULL, 'ausente', NULL, '2025-12-13 15:25:06'),
(451, 85, 86, 3, '2025-11-22', NULL, NULL, 'presente', NULL, '2025-12-13 15:25:06'),
(452, 87, 86, 3, '2025-11-22', NULL, NULL, 'presente', NULL, '2025-12-13 15:25:06'),
(453, 88, 86, 3, '2025-11-22', NULL, NULL, 'ausente', NULL, '2025-12-13 15:25:06'),
(454, 102, 86, 3, '2025-11-22', NULL, NULL, 'ausente', NULL, '2025-12-13 15:25:06'),
(455, 128, 86, 3, '2025-11-22', NULL, NULL, 'presente', NULL, '2025-12-13 15:25:06'),
(456, 131, 86, 3, '2025-11-22', NULL, NULL, 'presente', NULL, '2025-12-13 15:25:06'),
(457, 143, 86, 3, '2025-11-22', NULL, NULL, 'ausente', NULL, '2025-12-13 15:25:06'),
(458, 145, 86, 3, '2025-11-22', NULL, NULL, 'ausente', NULL, '2025-12-13 15:25:06'),
(459, 61, 86, 3, '2025-11-29', NULL, NULL, 'ausente', NULL, '2025-12-13 15:27:23'),
(460, 65, 86, 3, '2025-11-29', NULL, NULL, 'ausente', NULL, '2025-12-13 15:27:23'),
(461, 66, 86, 3, '2025-11-29', NULL, NULL, 'ausente', NULL, '2025-12-13 15:27:23'),
(462, 71, 86, 3, '2025-11-29', NULL, NULL, 'ausente', NULL, '2025-12-13 15:27:23'),
(463, 82, 86, 3, '2025-11-29', NULL, NULL, 'presente', NULL, '2025-12-13 15:27:23'),
(464, 83, 86, 3, '2025-11-29', NULL, NULL, 'ausente', NULL, '2025-12-13 15:27:23'),
(465, 84, 86, 3, '2025-11-29', NULL, NULL, 'ausente', NULL, '2025-12-13 15:27:23'),
(466, 85, 86, 3, '2025-11-29', NULL, NULL, 'presente', NULL, '2025-12-13 15:27:23'),
(467, 87, 86, 3, '2025-11-29', NULL, NULL, 'presente', NULL, '2025-12-13 15:27:23'),
(468, 88, 86, 3, '2025-11-29', NULL, NULL, 'ausente', NULL, '2025-12-13 15:27:23'),
(469, 102, 86, 3, '2025-11-29', NULL, NULL, 'ausente', NULL, '2025-12-13 15:27:23'),
(470, 128, 86, 3, '2025-11-29', NULL, NULL, 'presente', NULL, '2025-12-13 15:27:23'),
(471, 131, 86, 3, '2025-11-29', NULL, NULL, 'ausente', NULL, '2025-12-13 15:27:23'),
(472, 143, 86, 3, '2025-11-29', NULL, NULL, 'ausente', NULL, '2025-12-13 15:27:23'),
(473, 145, 86, 3, '2025-11-29', NULL, NULL, 'ausente', NULL, '2025-12-13 15:27:23'),
(474, 95, 86, 10, '2025-11-29', NULL, NULL, 'ausente', NULL, '2025-12-13 15:27:53'),
(475, 96, 86, 10, '2025-11-29', NULL, NULL, 'ausente', NULL, '2025-12-13 15:27:53'),
(476, 127, 86, 10, '2025-11-29', NULL, NULL, 'presente', NULL, '2025-12-13 15:27:53'),
(477, 144, 86, 10, '2025-11-29', NULL, NULL, 'ausente', NULL, '2025-12-13 15:27:53'),
(478, 146, 86, 10, '2025-11-29', NULL, NULL, 'presente', NULL, '2025-12-13 15:27:53'),
(479, 147, 86, 10, '2025-11-29', NULL, NULL, 'presente', NULL, '2025-12-13 15:27:53'),
(480, 95, 86, 10, '2025-12-06', NULL, NULL, 'ausente', NULL, '2025-12-13 15:28:56'),
(481, 96, 86, 10, '2025-12-06', NULL, NULL, 'ausente', NULL, '2025-12-13 15:28:56'),
(482, 127, 86, 10, '2025-12-06', NULL, NULL, 'presente', NULL, '2025-12-13 15:28:56'),
(483, 144, 86, 10, '2025-12-06', NULL, NULL, 'presente', NULL, '2025-12-13 15:28:56'),
(484, 146, 86, 10, '2025-12-06', NULL, NULL, 'presente', NULL, '2025-12-13 15:28:56'),
(485, 147, 86, 10, '2025-12-06', NULL, NULL, 'presente', NULL, '2025-12-13 15:28:56'),
(486, 61, 86, 3, '2025-12-06', NULL, NULL, 'presente', NULL, '2025-12-13 15:29:26'),
(487, 65, 86, 3, '2025-12-06', NULL, NULL, 'ausente', NULL, '2025-12-13 15:29:26'),
(488, 66, 86, 3, '2025-12-06', NULL, NULL, 'ausente', NULL, '2025-12-13 15:29:26'),
(489, 71, 86, 3, '2025-12-06', NULL, NULL, 'presente', NULL, '2025-12-13 15:29:26'),
(490, 82, 86, 3, '2025-12-06', NULL, NULL, 'presente', NULL, '2025-12-13 15:29:26'),
(491, 83, 86, 3, '2025-12-06', NULL, NULL, 'ausente', NULL, '2025-12-13 15:29:26'),
(492, 84, 86, 3, '2025-12-06', NULL, NULL, 'ausente', NULL, '2025-12-13 15:29:26'),
(493, 85, 86, 3, '2025-12-06', NULL, NULL, 'presente', NULL, '2025-12-13 15:29:26'),
(494, 87, 86, 3, '2025-12-06', NULL, NULL, 'presente', NULL, '2025-12-13 15:29:26'),
(495, 88, 86, 3, '2025-12-06', NULL, NULL, 'presente', NULL, '2025-12-13 15:29:26'),
(496, 102, 86, 3, '2025-12-06', NULL, NULL, 'presente', NULL, '2025-12-13 15:29:26'),
(497, 128, 86, 3, '2025-12-06', NULL, NULL, 'ausente', NULL, '2025-12-13 15:29:26'),
(498, 131, 86, 3, '2025-12-06', NULL, NULL, 'presente', NULL, '2025-12-13 15:29:26'),
(499, 143, 86, 3, '2025-12-06', NULL, NULL, 'ausente', NULL, '2025-12-13 15:29:26'),
(500, 145, 86, 3, '2025-12-06', NULL, NULL, 'ausente', NULL, '2025-12-13 15:29:26'),
(501, 95, 86, 10, '2025-12-13', NULL, NULL, 'presente', NULL, '2025-12-13 19:47:19'),
(502, 96, 86, 10, '2025-12-13', NULL, NULL, 'presente', NULL, '2025-12-13 19:47:19'),
(503, 127, 86, 10, '2025-12-13', NULL, NULL, 'presente', NULL, '2025-12-13 19:47:19'),
(504, 144, 86, 10, '2025-12-13', NULL, NULL, 'presente', NULL, '2025-12-13 19:47:19'),
(505, 146, 86, 10, '2025-12-13', NULL, NULL, 'ausente', NULL, '2025-12-13 19:47:19'),
(506, 147, 86, 10, '2025-12-13', NULL, NULL, 'presente', NULL, '2025-12-13 19:47:20'),
(507, 61, 86, 3, '2025-12-13', NULL, NULL, 'presente', NULL, '2025-12-13 19:48:06'),
(508, 65, 86, 3, '2025-12-13', NULL, NULL, 'ausente', NULL, '2025-12-13 19:48:06'),
(509, 66, 86, 3, '2025-12-13', NULL, NULL, 'ausente', NULL, '2025-12-13 19:48:06'),
(510, 71, 86, 3, '2025-12-13', NULL, NULL, 'presente', NULL, '2025-12-13 19:48:06'),
(511, 82, 86, 3, '2025-12-13', NULL, NULL, 'presente', NULL, '2025-12-13 19:48:06'),
(512, 83, 86, 3, '2025-12-13', NULL, NULL, 'ausente', NULL, '2025-12-13 19:48:06'),
(513, 84, 86, 3, '2025-12-13', NULL, NULL, 'ausente', NULL, '2025-12-13 19:48:06'),
(514, 85, 86, 3, '2025-12-13', NULL, NULL, 'presente', NULL, '2025-12-13 19:48:06'),
(515, 87, 86, 3, '2025-12-13', NULL, NULL, 'presente', NULL, '2025-12-13 19:48:06'),
(516, 88, 86, 3, '2025-12-13', NULL, NULL, 'presente', NULL, '2025-12-13 19:48:06'),
(517, 102, 86, 3, '2025-12-13', NULL, NULL, 'ausente', NULL, '2025-12-13 19:48:06'),
(518, 128, 86, 3, '2025-12-13', NULL, NULL, 'ausente', NULL, '2025-12-13 19:48:06'),
(519, 131, 86, 3, '2025-12-13', NULL, NULL, 'ausente', NULL, '2025-12-13 19:48:06'),
(520, 143, 86, 3, '2025-12-13', NULL, NULL, 'presente', NULL, '2025-12-13 19:48:06'),
(521, 145, 86, 3, '2025-12-13', NULL, NULL, 'ausente', NULL, '2025-12-13 19:48:06'),
(522, 78, 70, 9, '2025-12-16', NULL, NULL, 'ausente', NULL, '2025-12-17 03:04:57'),
(523, 79, 70, 9, '2025-12-16', NULL, NULL, 'ausente', NULL, '2025-12-17 03:04:57'),
(524, 81, 70, 9, '2025-12-16', NULL, NULL, 'ausente', NULL, '2025-12-17 03:04:57'),
(525, 92, 70, 9, '2025-12-16', NULL, NULL, 'ausente', NULL, '2025-12-17 03:04:57'),
(526, 93, 70, 9, '2025-12-16', NULL, NULL, 'ausente', NULL, '2025-12-17 03:04:57'),
(527, 94, 70, 9, '2025-12-16', NULL, NULL, 'ausente', NULL, '2025-12-17 03:04:57'),
(528, 98, 70, 9, '2025-12-16', NULL, NULL, 'ausente', NULL, '2025-12-17 03:04:57'),
(529, 99, 70, 9, '2025-12-16', NULL, NULL, 'ausente', NULL, '2025-12-17 03:04:57'),
(530, 100, 70, 9, '2025-12-16', NULL, NULL, 'ausente', NULL, '2025-12-17 03:04:57'),
(531, 107, 70, 9, '2025-12-16', NULL, NULL, 'ausente', NULL, '2025-12-17 03:04:57'),
(532, 108, 70, 9, '2025-12-16', NULL, NULL, 'ausente', NULL, '2025-12-17 03:04:57'),
(533, 109, 70, 9, '2025-12-16', NULL, NULL, 'ausente', NULL, '2025-12-17 03:04:57'),
(534, 113, 70, 9, '2025-12-16', NULL, NULL, 'ausente', NULL, '2025-12-17 03:04:57'),
(535, 116, 70, 9, '2025-12-16', NULL, NULL, 'ausente', NULL, '2025-12-17 03:04:57'),
(536, 117, 70, 9, '2025-12-16', NULL, NULL, 'ausente', NULL, '2025-12-17 03:04:57'),
(537, 118, 70, 9, '2025-12-16', NULL, NULL, 'ausente', NULL, '2025-12-17 03:04:57'),
(538, 120, 70, 9, '2025-12-16', NULL, NULL, 'ausente', NULL, '2025-12-17 03:04:57'),
(539, 121, 70, 9, '2025-12-16', NULL, NULL, 'ausente', NULL, '2025-12-17 03:04:57'),
(540, 124, 70, 9, '2025-12-16', NULL, NULL, 'ausente', NULL, '2025-12-17 03:04:57'),
(541, 142, 70, 9, '2025-12-16', NULL, NULL, 'ausente', NULL, '2025-12-17 03:04:57'),
(543, 1, 1, 1, '2026-02-02', NULL, NULL, 'ausente', NULL, '2026-02-03 03:55:45'),
(544, 59, 1, 1, '2026-02-02', NULL, NULL, 'presente', NULL, '2026-02-03 03:55:45'),
(545, 64, 1, 1, '2026-02-02', NULL, NULL, 'presente', NULL, '2026-02-03 03:55:45'),
(546, 72, 1, 1, '2026-02-02', NULL, NULL, 'ausente', NULL, '2026-02-03 03:55:45'),
(547, 73, 1, 1, '2026-02-02', NULL, NULL, 'ausente', NULL, '2026-02-03 03:55:45'),
(548, 75, 1, 1, '2026-02-02', NULL, NULL, 'ausente', NULL, '2026-02-03 03:55:45'),
(549, 76, 1, 1, '2026-02-02', NULL, NULL, 'ausente', NULL, '2026-02-03 03:55:45'),
(550, 86, 1, 1, '2026-02-02', NULL, NULL, 'ausente', NULL, '2026-02-03 03:55:45'),
(551, 155, 1, 1, '2026-02-02', NULL, NULL, 'ausente', NULL, '2026-02-03 03:55:45'),
(552, 95, 75, 10, '2026-02-07', NULL, NULL, 'ausente', NULL, '2026-02-07 13:55:20'),
(553, 96, 75, 10, '2026-02-07', NULL, NULL, 'ausente', NULL, '2026-02-07 13:55:20'),
(554, 127, 75, 10, '2026-02-07', NULL, NULL, 'ausente', NULL, '2026-02-07 13:55:20'),
(555, 144, 75, 10, '2026-02-07', NULL, NULL, 'ausente', NULL, '2026-02-07 13:55:20'),
(556, 146, 75, 10, '2026-02-07', NULL, NULL, 'presente', NULL, '2026-02-07 13:55:20'),
(557, 147, 75, 10, '2026-02-07', NULL, NULL, 'presente', NULL, '2026-02-07 13:55:20'),
(558, 158, 75, 10, '2026-02-07', NULL, NULL, 'ausente', NULL, '2026-02-07 13:55:20'),
(559, 62, 75, 4, '2026-02-06', NULL, NULL, 'presente', NULL, '2026-02-07 13:56:05'),
(560, 63, 75, 4, '2026-02-06', NULL, NULL, 'presente', NULL, '2026-02-07 13:56:05'),
(561, 75, 75, 4, '2026-02-06', NULL, NULL, 'presente', NULL, '2026-02-07 13:56:05'),
(562, 77, 75, 4, '2026-02-06', NULL, NULL, 'ausente', NULL, '2026-02-07 13:56:05'),
(563, 80, 75, 4, '2026-02-06', NULL, NULL, 'ausente', NULL, '2026-02-07 13:56:05'),
(564, 110, 75, 4, '2026-02-06', NULL, NULL, 'ausente', NULL, '2026-02-07 13:56:05'),
(565, 114, 75, 4, '2026-02-06', NULL, NULL, 'ausente', NULL, '2026-02-07 13:56:05'),
(566, 62, 75, 4, '2026-02-07', NULL, NULL, 'presente', NULL, '2026-02-07 13:56:22'),
(567, 63, 75, 4, '2026-02-07', NULL, NULL, 'presente', NULL, '2026-02-07 13:56:22'),
(568, 75, 75, 4, '2026-02-07', NULL, NULL, 'ausente', NULL, '2026-02-07 13:56:22'),
(569, 77, 75, 4, '2026-02-07', NULL, NULL, 'ausente', NULL, '2026-02-07 13:56:22'),
(570, 80, 75, 4, '2026-02-07', NULL, NULL, 'ausente', NULL, '2026-02-07 13:56:22'),
(571, 110, 75, 4, '2026-02-07', NULL, NULL, 'ausente', NULL, '2026-02-07 13:56:22'),
(572, 114, 75, 4, '2026-02-07', NULL, NULL, 'ausente', NULL, '2026-02-07 13:56:22'),
(573, 61, 75, 3, '2026-02-07', NULL, NULL, 'ausente', NULL, '2026-02-07 21:04:07'),
(574, 65, 75, 3, '2026-02-07', NULL, NULL, 'ausente', NULL, '2026-02-07 21:04:07'),
(575, 66, 75, 3, '2026-02-07', NULL, NULL, 'ausente', NULL, '2026-02-07 21:04:07'),
(576, 71, 75, 3, '2026-02-07', NULL, NULL, 'ausente', NULL, '2026-02-07 21:04:07'),
(577, 82, 75, 3, '2026-02-07', NULL, NULL, 'presente', NULL, '2026-02-07 21:04:07'),
(578, 83, 75, 3, '2026-02-07', NULL, NULL, 'ausente', NULL, '2026-02-07 21:04:07'),
(579, 84, 75, 3, '2026-02-07', NULL, NULL, 'ausente', NULL, '2026-02-07 21:04:07'),
(580, 85, 75, 3, '2026-02-07', NULL, NULL, 'presente', NULL, '2026-02-07 21:04:07'),
(581, 87, 75, 3, '2026-02-07', NULL, NULL, 'ausente', NULL, '2026-02-07 21:04:07'),
(582, 88, 75, 3, '2026-02-07', NULL, NULL, 'presente', NULL, '2026-02-07 21:04:07'),
(583, 102, 75, 3, '2026-02-07', NULL, NULL, 'ausente', NULL, '2026-02-07 21:04:07'),
(584, 131, 75, 3, '2026-02-07', NULL, NULL, 'ausente', NULL, '2026-02-07 21:04:07'),
(585, 143, 75, 3, '2026-02-07', NULL, NULL, 'ausente', NULL, '2026-02-07 21:04:07'),
(586, 145, 75, 3, '2026-02-07', NULL, NULL, 'ausente', NULL, '2026-02-07 21:04:07'),
(587, 149, 75, 3, '2026-02-07', NULL, NULL, 'ausente', NULL, '2026-02-07 21:04:07'),
(588, 152, 75, 3, '2026-02-07', NULL, NULL, 'ausente', NULL, '2026-02-07 21:04:07'),
(589, 161, 59, 5, '2026-02-09', NULL, NULL, 'presente', NULL, '2026-02-11 00:36:45'),
(590, 161, 59, 5, '2026-02-11', NULL, NULL, 'presente', NULL, '2026-02-11 12:41:04'),
(591, 62, 75, 2, '2026-02-11', NULL, NULL, 'presente', NULL, '2026-02-12 13:03:48'),
(592, 63, 75, 2, '2026-02-11', NULL, NULL, 'presente', NULL, '2026-02-12 13:03:48'),
(593, 101, 75, 2, '2026-02-11', NULL, NULL, 'ausente', NULL, '2026-02-12 13:03:48'),
(594, 104, 75, 2, '2026-02-11', NULL, NULL, 'ausente', NULL, '2026-02-12 13:03:48'),
(595, 141, 75, 2, '2026-02-11', NULL, NULL, 'ausente', NULL, '2026-02-12 13:03:48'),
(596, 143, 75, 2, '2026-02-11', NULL, NULL, 'ausente', NULL, '2026-02-12 13:03:48'),
(597, 152, 75, 2, '2026-02-11', NULL, NULL, 'ausente', NULL, '2026-02-12 13:03:48'),
(598, 163, 75, 2, '2026-02-11', NULL, NULL, 'ausente', NULL, '2026-02-12 13:03:48'),
(599, 161, 59, 5, '2026-02-13', NULL, NULL, 'presente', NULL, '2026-02-13 13:47:09'),
(600, 161, 59, 5, '2026-02-16', NULL, NULL, 'presente', NULL, '2026-02-16 18:16:48'),
(601, 95, 75, 10, '2026-02-14', NULL, NULL, 'ausente', NULL, '2026-02-16 22:17:14'),
(602, 96, 75, 10, '2026-02-14', NULL, NULL, 'ausente', NULL, '2026-02-16 22:17:14'),
(603, 127, 75, 10, '2026-02-14', NULL, NULL, 'ausente', NULL, '2026-02-16 22:17:14'),
(604, 144, 75, 10, '2026-02-14', NULL, NULL, 'ausente', NULL, '2026-02-16 22:17:14'),
(605, 146, 75, 10, '2026-02-14', NULL, NULL, 'presente', NULL, '2026-02-16 22:17:14'),
(606, 147, 75, 10, '2026-02-14', NULL, NULL, 'ausente', NULL, '2026-02-16 22:17:14'),
(607, 158, 75, 10, '2026-02-14', NULL, NULL, 'ausente', NULL, '2026-02-16 22:17:14'),
(608, 62, 75, 2, '2026-02-14', NULL, NULL, 'presente', NULL, '2026-02-16 22:17:31'),
(609, 63, 75, 2, '2026-02-14', NULL, NULL, 'presente', NULL, '2026-02-16 22:17:31'),
(610, 101, 75, 2, '2026-02-14', NULL, NULL, 'ausente', NULL, '2026-02-16 22:17:31'),
(611, 104, 75, 2, '2026-02-14', NULL, NULL, 'ausente', NULL, '2026-02-16 22:17:31'),
(612, 141, 75, 2, '2026-02-14', NULL, NULL, 'ausente', NULL, '2026-02-16 22:17:31'),
(613, 143, 75, 2, '2026-02-14', NULL, NULL, 'ausente', NULL, '2026-02-16 22:17:31'),
(614, 152, 75, 2, '2026-02-14', NULL, NULL, 'ausente', NULL, '2026-02-16 22:17:31'),
(615, 163, 75, 2, '2026-02-14', NULL, NULL, 'ausente', NULL, '2026-02-16 22:17:31'),
(616, 61, 75, 3, '2026-02-14', NULL, NULL, 'ausente', NULL, '2026-02-16 22:18:11'),
(617, 65, 75, 3, '2026-02-14', NULL, NULL, 'ausente', NULL, '2026-02-16 22:18:11'),
(618, 66, 75, 3, '2026-02-14', NULL, NULL, 'ausente', NULL, '2026-02-16 22:18:11'),
(619, 71, 75, 3, '2026-02-14', NULL, NULL, 'ausente', NULL, '2026-02-16 22:18:11'),
(620, 82, 75, 3, '2026-02-14', NULL, NULL, 'presente', NULL, '2026-02-16 22:18:11'),
(621, 83, 75, 3, '2026-02-14', NULL, NULL, 'ausente', NULL, '2026-02-16 22:18:11'),
(622, 84, 75, 3, '2026-02-14', NULL, NULL, 'ausente', NULL, '2026-02-16 22:18:11'),
(623, 85, 75, 3, '2026-02-14', NULL, NULL, 'ausente', NULL, '2026-02-16 22:18:11'),
(624, 87, 75, 3, '2026-02-14', NULL, NULL, 'ausente', NULL, '2026-02-16 22:18:11'),
(625, 88, 75, 3, '2026-02-14', NULL, NULL, 'presente', NULL, '2026-02-16 22:18:11'),
(626, 102, 75, 3, '2026-02-14', NULL, NULL, 'ausente', NULL, '2026-02-16 22:18:11'),
(627, 131, 75, 3, '2026-02-14', NULL, NULL, 'ausente', NULL, '2026-02-16 22:18:11'),
(628, 143, 75, 3, '2026-02-14', NULL, NULL, 'ausente', NULL, '2026-02-16 22:18:11'),
(629, 145, 75, 3, '2026-02-14', NULL, NULL, 'ausente', NULL, '2026-02-16 22:18:11'),
(630, 149, 75, 3, '2026-02-14', NULL, NULL, 'ausente', NULL, '2026-02-16 22:18:11'),
(631, 152, 75, 3, '2026-02-14', NULL, NULL, 'ausente', NULL, '2026-02-16 22:18:11'),
(632, 59, 75, 2, '2026-02-18', NULL, NULL, 'ausente', NULL, '2026-02-19 18:18:17'),
(633, 62, 75, 2, '2026-02-18', NULL, NULL, 'presente', NULL, '2026-02-19 18:18:17'),
(634, 63, 75, 2, '2026-02-18', NULL, NULL, 'presente', NULL, '2026-02-19 18:18:17'),
(635, 101, 75, 2, '2026-02-18', NULL, NULL, 'ausente', NULL, '2026-02-19 18:18:17'),
(636, 104, 75, 2, '2026-02-18', NULL, NULL, 'ausente', NULL, '2026-02-19 18:18:17'),
(637, 141, 75, 2, '2026-02-18', NULL, NULL, 'ausente', NULL, '2026-02-19 18:18:17'),
(638, 143, 75, 2, '2026-02-18', NULL, NULL, 'ausente', NULL, '2026-02-19 18:18:17'),
(639, 152, 75, 2, '2026-02-18', NULL, NULL, 'presente', NULL, '2026-02-19 18:18:17'),
(640, 163, 75, 2, '2026-02-18', NULL, NULL, 'ausente', NULL, '2026-02-19 18:18:17');
INSERT INTO `asistencia` (`id`, `afiliado_id`, `instructor_id`, `horario_id`, `fecha`, `hora_entrada`, `hora_salida`, `estado`, `observaciones`, `fecha_registro`) VALUES
(641, 161, 59, 5, '2026-02-20', NULL, NULL, 'presente', NULL, '2026-02-20 22:22:19'),
(642, 161, 59, 5, '2026-02-18', NULL, NULL, 'presente', NULL, '2026-02-20 22:23:48'),
(643, 95, 86, 10, '2026-02-21', NULL, NULL, 'presente', NULL, '2026-02-21 14:03:52'),
(644, 96, 86, 10, '2026-02-21', NULL, NULL, 'presente', NULL, '2026-02-21 14:03:52'),
(645, 127, 86, 10, '2026-02-21', NULL, NULL, 'ausente', NULL, '2026-02-21 14:03:52'),
(646, 144, 86, 10, '2026-02-21', NULL, NULL, 'ausente', NULL, '2026-02-21 14:03:52'),
(647, 146, 86, 10, '2026-02-21', NULL, NULL, 'presente', NULL, '2026-02-21 14:03:52'),
(648, 147, 86, 10, '2026-02-21', NULL, NULL, 'presente', NULL, '2026-02-21 14:03:52'),
(649, 61, 86, 3, '2026-02-21', NULL, NULL, 'ausente', NULL, '2026-02-21 22:41:53'),
(650, 65, 86, 3, '2026-02-21', NULL, NULL, 'ausente', NULL, '2026-02-21 22:41:53'),
(651, 66, 86, 3, '2026-02-21', NULL, NULL, 'ausente', NULL, '2026-02-21 22:41:53'),
(652, 71, 86, 3, '2026-02-21', NULL, NULL, 'ausente', NULL, '2026-02-21 22:41:53'),
(653, 82, 86, 3, '2026-02-21', NULL, NULL, 'presente', NULL, '2026-02-21 22:41:53'),
(654, 83, 86, 3, '2026-02-21', NULL, NULL, 'ausente', NULL, '2026-02-21 22:41:53'),
(655, 84, 86, 3, '2026-02-21', NULL, NULL, 'ausente', NULL, '2026-02-21 22:41:53'),
(656, 85, 86, 3, '2026-02-21', NULL, NULL, 'presente', NULL, '2026-02-21 22:41:53'),
(657, 87, 86, 3, '2026-02-21', NULL, NULL, 'ausente', NULL, '2026-02-21 22:41:53'),
(658, 88, 86, 3, '2026-02-21', NULL, NULL, 'presente', NULL, '2026-02-21 22:41:53'),
(659, 102, 86, 3, '2026-02-21', NULL, NULL, 'ausente', NULL, '2026-02-21 22:41:53'),
(660, 131, 86, 3, '2026-02-21', NULL, NULL, 'ausente', NULL, '2026-02-21 22:41:53'),
(661, 143, 86, 3, '2026-02-21', NULL, NULL, 'ausente', NULL, '2026-02-21 22:41:53'),
(662, 145, 86, 3, '2026-02-21', NULL, NULL, 'ausente', NULL, '2026-02-21 22:41:53'),
(663, 149, 86, 3, '2026-02-21', NULL, NULL, 'ausente', NULL, '2026-02-21 22:41:53'),
(664, 152, 86, 3, '2026-02-21', NULL, NULL, 'presente', NULL, '2026-02-21 22:41:53'),
(665, 59, 75, 2, '2026-02-20', NULL, NULL, 'presente', NULL, '2026-02-22 13:40:47'),
(666, 62, 75, 2, '2026-02-20', NULL, NULL, 'presente', NULL, '2026-02-22 13:40:47'),
(667, 63, 75, 2, '2026-02-20', NULL, NULL, 'presente', NULL, '2026-02-22 13:40:47'),
(668, 101, 75, 2, '2026-02-20', NULL, NULL, 'ausente', NULL, '2026-02-22 13:40:47'),
(669, 104, 75, 2, '2026-02-20', NULL, NULL, 'ausente', NULL, '2026-02-22 13:40:47'),
(670, 141, 75, 2, '2026-02-20', NULL, NULL, 'ausente', NULL, '2026-02-22 13:40:47'),
(671, 143, 75, 2, '2026-02-20', NULL, NULL, 'ausente', NULL, '2026-02-22 13:40:47'),
(672, 152, 75, 2, '2026-02-20', NULL, NULL, 'presente', NULL, '2026-02-22 13:40:47'),
(673, 163, 75, 2, '2026-02-20', NULL, NULL, 'presente', NULL, '2026-02-22 13:40:47'),
(674, 161, 59, 5, '2026-02-25', NULL, NULL, 'presente', NULL, '2026-02-27 19:33:41'),
(675, 161, 59, 5, '2026-02-27', NULL, NULL, 'presente', NULL, '2026-02-27 19:33:49'),
(676, 59, 75, 2, '2026-03-04', NULL, NULL, 'ausente', NULL, '2026-03-07 01:56:30'),
(677, 62, 75, 2, '2026-03-04', NULL, NULL, 'presente', NULL, '2026-03-07 01:56:30'),
(678, 63, 75, 2, '2026-03-04', NULL, NULL, 'presente', NULL, '2026-03-07 01:56:30'),
(679, 101, 75, 2, '2026-03-04', NULL, NULL, 'ausente', NULL, '2026-03-07 01:56:30'),
(680, 104, 75, 2, '2026-03-04', NULL, NULL, 'ausente', NULL, '2026-03-07 01:56:30'),
(681, 141, 75, 2, '2026-03-04', NULL, NULL, 'ausente', NULL, '2026-03-07 01:56:30'),
(682, 143, 75, 2, '2026-03-04', NULL, NULL, 'ausente', NULL, '2026-03-07 01:56:30'),
(683, 152, 75, 2, '2026-03-04', NULL, NULL, 'ausente', NULL, '2026-03-07 01:56:30'),
(684, 163, 75, 2, '2026-03-04', NULL, NULL, 'ausente', NULL, '2026-03-07 01:56:30'),
(685, 59, 75, 2, '2026-03-06', NULL, NULL, 'ausente', NULL, '2026-03-07 01:56:45'),
(686, 62, 75, 2, '2026-03-06', NULL, NULL, 'presente', NULL, '2026-03-07 01:56:45'),
(687, 63, 75, 2, '2026-03-06', NULL, NULL, 'presente', NULL, '2026-03-07 01:56:45'),
(688, 101, 75, 2, '2026-03-06', NULL, NULL, 'ausente', NULL, '2026-03-07 01:56:45'),
(689, 104, 75, 2, '2026-03-06', NULL, NULL, 'ausente', NULL, '2026-03-07 01:56:45'),
(690, 141, 75, 2, '2026-03-06', NULL, NULL, 'ausente', NULL, '2026-03-07 01:56:45'),
(691, 143, 75, 2, '2026-03-06', NULL, NULL, 'ausente', NULL, '2026-03-07 01:56:45'),
(692, 152, 75, 2, '2026-03-06', NULL, NULL, 'ausente', NULL, '2026-03-07 01:56:45'),
(693, 163, 75, 2, '2026-03-06', NULL, NULL, 'presente', NULL, '2026-03-07 01:56:45'),
(694, 95, 86, 10, '2026-03-07', NULL, NULL, 'ausente', NULL, '2026-03-07 13:39:55'),
(695, 96, 86, 10, '2026-03-07', NULL, NULL, 'ausente', NULL, '2026-03-07 13:39:55'),
(696, 127, 86, 10, '2026-03-07', NULL, NULL, 'ausente', NULL, '2026-03-07 13:39:55'),
(697, 144, 86, 10, '2026-03-07', NULL, NULL, 'ausente', NULL, '2026-03-07 13:39:55'),
(698, 146, 86, 10, '2026-03-07', NULL, NULL, 'ausente', NULL, '2026-03-07 13:39:55'),
(699, 147, 86, 10, '2026-03-07', NULL, NULL, 'presente', NULL, '2026-03-07 13:39:55'),
(700, 61, 86, 3, '2026-03-07', NULL, NULL, 'ausente', NULL, '2026-03-07 20:21:35'),
(701, 65, 86, 3, '2026-03-07', NULL, NULL, 'ausente', NULL, '2026-03-07 20:21:35'),
(702, 66, 86, 3, '2026-03-07', NULL, NULL, 'ausente', NULL, '2026-03-07 20:21:35'),
(703, 71, 86, 3, '2026-03-07', NULL, NULL, 'ausente', NULL, '2026-03-07 20:21:35'),
(704, 82, 86, 3, '2026-03-07', NULL, NULL, 'presente', NULL, '2026-03-07 20:21:35'),
(705, 83, 86, 3, '2026-03-07', NULL, NULL, 'ausente', NULL, '2026-03-07 20:21:35'),
(706, 84, 86, 3, '2026-03-07', NULL, NULL, 'ausente', NULL, '2026-03-07 20:21:35'),
(707, 85, 86, 3, '2026-03-07', NULL, NULL, 'presente', NULL, '2026-03-07 20:21:36'),
(708, 87, 86, 3, '2026-03-07', NULL, NULL, 'ausente', NULL, '2026-03-07 20:21:36'),
(709, 88, 86, 3, '2026-03-07', NULL, NULL, 'presente', NULL, '2026-03-07 20:21:36'),
(710, 102, 86, 3, '2026-03-07', NULL, NULL, 'ausente', NULL, '2026-03-07 20:21:36'),
(711, 131, 86, 3, '2026-03-07', NULL, NULL, 'ausente', NULL, '2026-03-07 20:21:36'),
(712, 143, 86, 3, '2026-03-07', NULL, NULL, 'ausente', NULL, '2026-03-07 20:21:36'),
(713, 145, 86, 3, '2026-03-07', NULL, NULL, 'ausente', NULL, '2026-03-07 20:21:36'),
(714, 149, 86, 3, '2026-03-07', NULL, NULL, 'ausente', NULL, '2026-03-07 20:21:36'),
(715, 152, 86, 3, '2026-03-07', NULL, NULL, 'ausente', NULL, '2026-03-07 20:21:36');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asistencias`
--

CREATE TABLE `asistencias` (
  `id` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `horario_id` int(11) NOT NULL,
  `instructor_id` int(11) NOT NULL,
  `deportista_id` int(11) NOT NULL,
  `presente` tinyint(1) NOT NULL DEFAULT '0',
  `evaluacion_franjas` json DEFAULT NULL,
  `observaciones` text COLLATE utf8_unicode_ci,
  `creado_en` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `configuracion`
--

CREATE TABLE `configuracion` (
  `id` int(11) NOT NULL,
  `clave` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nombre de la configuración',
  `valor` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Valor de la configuración',
  `descripcion` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tipo` enum('precio','texto','numero','json') COLLATE utf8mb4_unicode_ci DEFAULT 'texto',
  `creado_en` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `actualizado_en` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `configuracion`
--

INSERT INTO `configuracion` (`id`, `clave`, `valor`, `descripcion`, `tipo`, `creado_en`, `actualizado_en`) VALUES
(1, 'precio_matricula', '150000', 'Precio de matrícula anual en COP', 'precio', '2025-11-21 01:18:16', '2025-11-21 01:18:16'),
(2, 'precio_mensualidad', '80000', 'Precio de mensualidad en COP', 'precio', '2025-11-21 01:18:16', '2025-11-21 01:18:16'),
(3, 'precio_examen_blanco', '50000', 'Precio examen cinturón Blanco', 'precio', '2025-11-21 01:18:16', '2025-11-21 01:18:16'),
(4, 'precio_examen_amarillo', '60000', 'Precio examen cinturón Amarillo', 'precio', '2025-11-21 01:18:16', '2025-11-21 01:18:16'),
(5, 'precio_examen_naranja', '70000', 'Precio examen cinturón Naranja', 'precio', '2025-11-21 01:18:16', '2025-11-21 01:18:16'),
(6, 'precio_examen_verde', '80000', 'Precio examen cinturón Verde', 'precio', '2025-11-21 01:18:16', '2025-11-21 01:18:16'),
(7, 'precio_examen_azul', '90000', 'Precio examen cinturón Azul', 'precio', '2025-11-21 01:18:16', '2025-11-21 01:18:16'),
(8, 'precio_examen_purpura', '100000', 'Precio examen cinturón Púrpura', 'precio', '2025-11-21 01:18:16', '2025-11-21 01:18:16'),
(9, 'precio_examen_rojo', '110000', 'Precio examen cinturón Rojo', 'precio', '2025-11-21 01:18:16', '2025-11-21 01:18:16'),
(10, 'precio_examen_rojo_marron', '120000', 'Precio examen cinturón Rojo-Marrón', 'precio', '2025-11-21 01:18:16', '2025-11-21 01:18:16'),
(11, 'precio_examen_marron', '130000', 'Precio examen cinturón Marrón', 'precio', '2025-11-21 01:18:16', '2025-11-21 01:18:16'),
(12, 'precio_examen_marron_negro', '140000', 'Precio examen cinturón Marrón-Negro', 'precio', '2025-11-21 01:18:16', '2025-11-21 01:18:16'),
(13, 'precio_examen_negro_1dan', '200000', 'Precio examen 1er Dan', 'precio', '2025-11-21 01:18:16', '2025-11-21 01:18:16'),
(14, 'precio_examen_negro_2dan', '250000', 'Precio examen 2do Dan', 'precio', '2025-11-21 01:18:16', '2025-11-21 01:18:16'),
(15, 'precio_examen_negro_3dan', '300000', 'Precio examen 3er Dan', 'precio', '2025-11-21 01:18:16', '2025-11-21 01:18:16'),
(16, 'precio_examen_negro_4dan', '350000', 'Precio examen 4to Dan', 'precio', '2025-11-21 01:18:16', '2025-11-21 01:18:16'),
(17, 'precio_examen_negro_5dan', '400000', 'Precio examen 5to Dan', 'precio', '2025-11-21 01:18:16', '2025-11-21 01:18:16'),
(18, 'precio_examen_negro_6dan', '450000', 'Precio examen 6to Dan', 'precio', '2025-11-21 01:18:16', '2025-11-21 01:18:16'),
(19, 'nombre_club', 'Club de Artes Marciales', 'Nombre del club', 'texto', '2025-11-21 01:18:16', '2025-11-21 01:18:16'),
(20, 'moneda', 'COP', 'Moneda utilizada', 'texto', '2025-11-21 01:18:16', '2025-11-21 01:18:16'),
(21, 'grados', '[\"Blanco\",\"Amarillo\",\"Naranja\",\"Verde\",\"Azul\",\"Púrpura\",\"Rojo\",\"Rojo-Marrón\",\"Marrón\",\"Marrón-Negro\",\"Negro 1 Dan\",\"Negro 2 Dan\",\"Negro 3 Dan\",\"Negro 4 Dan\",\"Negro 5 Dan\",\"Negro 6 Dan\"]', 'Lista de grados del club', 'json', '2025-11-21 01:18:16', '2025-11-21 01:18:16');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contabilidad_conceptos`
--

CREATE TABLE `contabilidad_conceptos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `tipo` enum('ingreso','egreso') COLLATE utf8_unicode_ci NOT NULL,
  `regla_distribucion_json` json DEFAULT NULL,
  `activo` tinyint(4) DEFAULT '1',
  `rubro_predeterminado_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `contabilidad_conceptos`
--

INSERT INTO `contabilidad_conceptos` (`id`, `nombre`, `tipo`, `regla_distribucion_json`, `activo`, `rubro_predeterminado_id`) VALUES
(1, 'Mensualidad', 'ingreso', NULL, 1, NULL),
(2, 'Matrícula Anual', 'ingreso', NULL, 1, NULL),
(3, 'Examen de Ascenso', 'ingreso', NULL, 1, NULL),
(4, 'Compra de Uniforme', 'ingreso', NULL, 1, NULL),
(5, 'Pago Servicios Públicos', 'egreso', NULL, 1, NULL),
(6, 'Pago Instructores', 'egreso', NULL, 1, NULL),
(7, 'Aseo', 'egreso', NULL, 1, 1),
(8, 'Hosting, Dominio y servicios web', 'egreso', NULL, 1, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contabilidad_concepto_distribucion`
--

CREATE TABLE `contabilidad_concepto_distribucion` (
  `id` int(11) NOT NULL,
  `concepto_id` int(11) NOT NULL,
  `rubro_id` int(11) NOT NULL,
  `porcentaje` decimal(5,2) DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contabilidad_cuentas_bancarias`
--

CREATE TABLE `contabilidad_cuentas_bancarias` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `numero_cuenta` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tipo_cuenta` varchar(50) COLLATE utf8_unicode_ci DEFAULT 'Efectivo',
  `saldo_actual` decimal(15,2) DEFAULT '0.00',
  `activo` tinyint(4) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `contabilidad_cuentas_bancarias`
--

INSERT INTO `contabilidad_cuentas_bancarias` (`id`, `nombre`, `numero_cuenta`, `tipo_cuenta`, `saldo_actual`, `activo`) VALUES
(1, 'Caja General', NULL, 'Efectivo', 0.00, 1),
(2, 'Davivienda', '035500055278', 'Ahorros', 727500.00, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contabilidad_movimientos`
--

CREATE TABLE `contabilidad_movimientos` (
  `id` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `tipo` enum('ingreso','egreso') COLLATE utf8_unicode_ci NOT NULL,
  `concepto_id` int(11) DEFAULT NULL,
  `cuenta_id` int(11) DEFAULT NULL,
  `tercero_id` int(11) DEFAULT NULL,
  `tercero_nombre` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `monto` decimal(15,2) NOT NULL,
  `comprobante_banco` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `banco_origen` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `observaciones` text COLLATE utf8_unicode_ci,
  `detalle_distribucion_json` json DEFAULT NULL,
  `usuario_registro_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `contabilidad_movimientos`
--

INSERT INTO `contabilidad_movimientos` (`id`, `fecha`, `tipo`, `concepto_id`, `cuenta_id`, `tercero_id`, `tercero_nombre`, `monto`, `comprobante_banco`, `banco_origen`, `observaciones`, `detalle_distribucion_json`, `usuario_registro_id`, `created_at`) VALUES
(1, '2026-01-24', 'ingreso', 2, 2, 146, '', 60000.00, '3145918414', 'M17273625', 'actualizar', '[]', 10, '2026-02-12 02:25:50'),
(2, '2026-01-30', 'ingreso', 2, 2, 85, '', 60000.00, '', 'TR4TAvUJcfEC', 'actualizar', '[]', 10, '2026-02-12 02:27:14'),
(3, '2026-01-30', 'ingreso', 2, 2, 97, '', 60000.00, '', 'TRWD0eAuFfEC', 'actualizar', '[]', 10, '2026-02-12 02:29:14'),
(4, '2026-02-01', 'ingreso', 2, 2, 63, '', 60000.00, '', '253096', '', '[]', 10, '2026-02-12 02:30:21'),
(5, '2026-02-01', 'ingreso', 2, 2, 62, '', 60000.00, '', '253096', '', '[]', 10, '2026-02-12 02:30:56'),
(6, '2026-02-06', 'ingreso', 2, 2, 161, '', 60000.00, '', '629719', '', '[]', 10, '2026-02-12 02:31:58'),
(7, '2026-02-06', 'ingreso', 1, 2, 59, '', 150000.00, '559484', '808337', '', '[]', 10, '2026-02-12 02:34:16'),
(8, '2026-02-02', 'ingreso', 1, 2, 63, '', 100000.00, '', '263056', '', '[]', 10, '2026-02-12 02:34:59'),
(9, '2026-02-02', 'ingreso', 1, 2, 62, '', 100000.00, '', '263056', '', '[]', 10, '2026-02-12 02:35:22'),
(10, '2026-01-27', 'egreso', 8, 2, NULL, 'Hostgator MX', 107000.00, 'enero', '', '', '{\"1\": {\"nota\": \"Egreso directo\", \"monto\": 107000}}', 10, '2026-02-19 19:26:51'),
(11, '2026-01-27', 'egreso', 7, 2, NULL, 'SONIA GUZMAN', 55500.00, 'enero', '', '', '{\"1\": {\"nota\": \"Egreso directo\", \"monto\": 55500}}', 10, '2026-02-19 19:39:49'),
(12, '2026-01-19', 'ingreso', 2, 2, 95, '', 60000.00, '', 'TR5LU793jFEC', '', '[]', 10, '2026-02-19 19:47:32'),
(13, '2026-01-19', 'ingreso', 2, 2, 96, '', 60000.00, '', 'TROubO2HDVEC', '', '[]', 10, '2026-02-19 19:48:29'),
(14, '2026-01-29', 'ingreso', 2, 2, 88, '', 60000.00, '', 'M10461122', '', '[]', 10, '2026-02-19 19:49:18');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contabilidad_presupuesto_anual`
--

CREATE TABLE `contabilidad_presupuesto_anual` (
  `id` int(11) NOT NULL,
  `anio` int(11) NOT NULL,
  `meta_ingreso_total` decimal(15,2) DEFAULT '0.00',
  `saldo_anterior` decimal(15,2) DEFAULT '0.00',
  `observaciones` text COLLATE utf8_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `contabilidad_presupuesto_anual`
--

INSERT INTO `contabilidad_presupuesto_anual` (`id`, `anio`, `meta_ingreso_total`, `saldo_anterior`, `observaciones`) VALUES
(1, 2026, 45130000.00, 5114965.00, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contabilidad_rubros`
--

CREATE TABLE `contabilidad_rubros` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `porcentaje_defecto` decimal(5,2) DEFAULT '0.00',
  `descripcion` text COLLATE utf8_unicode_ci,
  `activo` tinyint(4) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `contabilidad_rubros`
--

INSERT INTO `contabilidad_rubros` (`id`, `nombre`, `porcentaje_defecto`, `descripcion`, `activo`) VALUES
(1, 'Gastos Administrativos/Funcionamiento', 25.00, NULL, 1),
(2, 'Reconocimiento Instructores Apoyo', 35.00, NULL, 1),
(3, 'Utilidad y reservas', 35.00, NULL, 1),
(4, 'Honorarios Maestro Director', 20.00, NULL, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `evaluaciones_franjas`
--

CREATE TABLE `evaluaciones_franjas` (
  `id` int(11) NOT NULL,
  `afiliado_id` int(11) NOT NULL,
  `color` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `estado` enum('aprobado','reprobado') COLLATE utf8_unicode_ci DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `evaluador_id` int(11) DEFAULT NULL,
  `grado` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `observaciones` text COLLATE utf8_unicode_ci,
  `fecha_creacion` datetime DEFAULT CURRENT_TIMESTAMP,
  `fecha_actualizacion` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `evaluaciones_franjas`
--

INSERT INTO `evaluaciones_franjas` (`id`, `afiliado_id`, `color`, `estado`, `fecha`, `evaluador_id`, `grado`, `observaciones`, `fecha_creacion`, `fecha_actualizacion`) VALUES
(1, 95, 'amarilla', 'aprobado', '2025-08-29', NULL, NULL, NULL, '2025-08-29 20:18:51', '2025-08-29 20:18:51'),
(2, 95, 'roja', 'aprobado', '2025-08-29', 1, 'Amarillo', '', '2025-08-29 21:29:31', '2025-08-29 21:29:31'),
(3, 95, 'verde', 'reprobado', '2025-08-29', 1, 'Amarillo', '', '2025-08-29 21:32:15', '2025-08-29 21:32:15'),
(4, 73, 'blanca', 'aprobado', '2025-09-05', 1, 'Negro', '', '2025-09-05 19:23:23', '2025-09-05 19:23:23'),
(5, 74, 'amarilla', 'aprobado', '2025-09-05', 1, 'Blanco', '', '2025-09-05 19:25:41', '2025-09-05 19:25:41'),
(6, 95, 'blanca', 'aprobado', '2025-09-06', 86, 'Amarillo', '', '2025-09-23 10:45:07', '2025-09-23 10:45:07'),
(7, 95, 'blanca', 'aprobado', '2025-10-04', 86, 'Naranja', '', '2025-10-08 11:55:03', '2025-10-08 11:55:03'),
(8, 102, 'blanca', 'aprobado', '2025-10-04', 86, 'Amarillo', '', '2025-10-08 11:56:05', '2025-10-08 11:56:05'),
(9, 96, 'blanca', 'aprobado', '2025-10-08', 86, 'Naranja', '', '2025-10-08 11:57:19', '2025-10-08 11:57:19'),
(10, 146, 'blanca', 'aprobado', '2025-10-04', 86, 'Blanco', '', '2025-10-08 11:57:49', '2025-10-08 11:57:49'),
(11, 143, 'blanca', 'aprobado', '2025-10-08', 86, 'Blanco', '', '2025-10-08 11:58:19', '2025-10-08 11:58:19'),
(12, 144, 'blanca', 'aprobado', '2025-10-04', 86, 'Blanco', '', '2025-10-08 12:00:07', '2025-10-08 12:00:07'),
(13, 147, 'blanca', 'aprobado', '2025-10-04', 86, 'Naranja', '', '2025-10-08 12:00:51', '2025-10-08 12:00:51'),
(14, 63, 'blanca', 'aprobado', '2025-10-04', 86, 'Azul', '', '2025-10-08 12:01:48', '2025-10-08 12:01:48'),
(15, 62, 'blanca', 'aprobado', '2025-10-04', 86, 'Azul', '', '2025-10-08 12:02:30', '2025-10-08 12:02:30'),
(16, 88, 'blanca', 'aprobado', '2025-10-04', 86, 'Blanco', '', '2025-10-08 12:03:39', '2025-10-08 12:03:39'),
(17, 128, 'blanca', 'aprobado', '2025-10-04', 86, 'Naranja', '', '2025-10-08 12:05:10', '2025-10-08 12:05:10'),
(18, 127, 'blanca', 'aprobado', '2025-10-04', 86, 'Naranja', '', '2025-10-08 12:05:49', '2025-10-08 12:05:49'),
(19, 61, 'blanca', 'aprobado', '2025-10-04', 86, 'Verde', '', '2025-10-08 12:06:57', '2025-10-08 12:06:57'),
(20, 71, 'blanca', 'aprobado', '2025-10-04', 86, 'Naranja', '', '2025-10-08 12:07:31', '2025-10-08 12:07:31'),
(21, 63, 'amarilla', 'aprobado', '2025-12-08', NULL, 'Azul', '', '2025-12-08 14:48:07', '2025-12-08 14:48:57'),
(22, 62, 'amarilla', 'aprobado', '2025-12-08', NULL, 'Azul', '', '2025-12-08 14:50:33', '2025-12-08 14:50:33'),
(23, 63, 'roja', 'aprobado', '2025-12-08', NULL, 'Azul', '', '2025-12-08 14:51:29', '2025-12-08 14:51:29'),
(24, 63, 'verde', 'aprobado', '2025-12-08', NULL, 'Azul', '', '2025-12-08 14:52:21', '2025-12-08 14:53:18'),
(25, 63, 'negra', 'aprobado', '2025-12-08', NULL, 'Azul', '', '2025-12-08 14:54:05', '2025-12-08 14:54:05'),
(26, 63, 'azul', 'aprobado', '2025-12-08', NULL, 'Azul', '', '2025-12-08 14:55:09', '2025-12-08 14:55:52'),
(27, 62, 'roja', 'aprobado', '2025-12-08', NULL, 'Azul', '', '2025-12-08 14:57:17', '2025-12-08 14:57:17'),
(28, 62, 'verde', 'aprobado', '2025-12-08', NULL, 'Azul', '', '2025-12-08 14:57:49', '2025-12-08 14:57:49'),
(29, 62, 'negra', 'aprobado', '2025-12-08', NULL, 'Azul', '', '2025-12-08 14:58:33', '2025-12-08 14:58:33'),
(30, 62, 'blanca', 'aprobado', '2025-12-08', NULL, 'Azul', '', '2025-12-08 14:59:00', '2025-12-08 14:59:00'),
(31, 62, 'azul', 'aprobado', '2025-12-08', NULL, 'Azul', '', '2025-12-08 14:59:30', '2025-12-08 14:59:30');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `examenes`
--

CREATE TABLE `examenes` (
  `id` int(11) NOT NULL,
  `afiliado_id` int(11) NOT NULL,
  `grado_actual` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `grado_siguiente` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha_examen` date NOT NULL,
  `aprobado` tinyint(1) DEFAULT NULL,
  `pago_id` int(11) DEFAULT NULL,
  `observaciones` text COLLATE utf8mb4_unicode_ci,
  `creado_en` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `actualizado_en` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Disparadores `examenes`
--
DELIMITER $$
CREATE TRIGGER `actualizar_grado_deportista` AFTER UPDATE ON `examenes` FOR EACH ROW BEGIN
        IF NEW.aprobado = 1 AND OLD.aprobado IS NULL THEN
            UPDATE afiliados_siao 
            SET grado_cinturon = NEW.grado_siguiente 
            WHERE id = NEW.afiliado_id;
        END IF;
    END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `horarios`
--

CREATE TABLE `horarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `horarios`
--

INSERT INTO `horarios` (`id`, `nombre`, `activo`) VALUES
(1, 'Avanzados y Negros', 1),
(2, 'Miércoles y Viernes 07:30 p.m.', 1),
(3, 'Sábados 12:00 m', 1),
(4, 'Martes y Jueves 05:00 p.m.', 1),
(5, 'Lunes- Miércoles y Viernes 06:00 a.m.', 1),
(9, 'Sede Robledo', 1),
(10, 'Sábados 07:00 a.m.', 1),
(11, 'Taekwondo', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `instructor_horario`
--

CREATE TABLE `instructor_horario` (
  `id` int(11) NOT NULL,
  `instructor_id` int(11) NOT NULL,
  `horario_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `instructor_horario`
--

INSERT INTO `instructor_horario` (`id`, `instructor_id`, `horario_id`) VALUES
(1, 1, 1),
(2, 1, 10),
(12, 59, 5),
(13, 64, 2),
(10, 70, 9),
(11, 72, 1),
(18, 75, 2),
(20, 75, 3),
(17, 75, 4),
(19, 75, 10),
(8, 86, 3),
(9, 86, 10),
(21, 155, 1),
(23, 155, 2),
(22, 155, 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagos`
--

CREATE TABLE `pagos` (
  `id` int(11) NOT NULL,
  `afiliado_id` int(11) NOT NULL,
  `tipo_pago` enum('matricula','mensualidad','examen') COLLATE utf8mb4_unicode_ci NOT NULL,
  `monto_esperado` decimal(10,2) NOT NULL,
  `monto_pagado` decimal(10,2) NOT NULL,
  `saldo_pendiente` decimal(10,2) GENERATED ALWAYS AS ((`monto_esperado` - `monto_pagado`)) STORED,
  `fecha_pago` date NOT NULL,
  `banco` enum('Bancolombia','Davivienda','Efectivo','Otro') COLLATE utf8mb4_unicode_ci DEFAULT 'Efectivo',
  `referencia` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `periodo` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `estado` enum('pendiente','pagado','parcial','vencido') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pendiente',
  `observaciones` text COLLATE utf8mb4_unicode_ci,
  `creado_en` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `actualizado_en` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `pagos`
--

INSERT INTO `pagos` (`id`, `afiliado_id`, `tipo_pago`, `monto_esperado`, `monto_pagado`, `fecha_pago`, `banco`, `referencia`, `periodo`, `estado`, `observaciones`, `creado_en`, `actualizado_en`) VALUES
(1, 1, 'mensualidad', 80000.00, 90000.00, '2025-11-21', 'Davivienda', '147895', '2025-11', 'pagado', '', '2025-11-21 02:38:41', '2025-11-21 02:38:41'),
(2, 1, 'mensualidad', 90000.00, 39000.00, '2025-11-22', 'Efectivo', '147895', '2025-12', 'parcial', '', '2025-11-21 02:43:24', '2025-11-21 02:43:24');

--
-- Disparadores `pagos`
--
DELIMITER $$
CREATE TRIGGER `actualizar_estado_pago` BEFORE INSERT ON `pagos` FOR EACH ROW BEGIN
    IF NEW.monto_pagado >= NEW.monto_esperado THEN
        SET NEW.estado = 'pagado';
    ELSEIF NEW.monto_pagado > 0 THEN
        SET NEW.estado = 'parcial';
    ELSE
        SET NEW.estado = 'pendiente';
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `actualizar_estado_pago_update` BEFORE UPDATE ON `pagos` FOR EACH ROW BEGIN
    IF NEW.monto_pagado >= NEW.monto_esperado THEN
        SET NEW.estado = 'pagado';
    ELSEIF NEW.monto_pagado > 0 THEN
        SET NEW.estado = 'parcial';
    ELSE
        SET NEW.estado = 'pendiente';
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `correo` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `rol` enum('admin','instructor') COLLATE utf8_unicode_ci NOT NULL,
  `activo` tinyint(1) DEFAULT '1',
  `creado_en` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `correo`, `password`, `rol`, `activo`, `creado_en`) VALUES
(1, 'Administrador General', 'joongdoryucolombia@gmail.com', '$2y$10$uvCglG3/ranEghDMM92mg.zvl0W4tQtfTBg5s05/wSJfxR7yKdncm', 'admin', 1, '2025-07-20 00:06:28'),
(2, 'Juan David Mazo Moreno', 'juandavidmazo@gmail.com', '$2y$10$fXP6KTqYldh2xyAv1Lj2p.C3GUFkq6Aqowk0lPU44W/fzIjVY75hW', 'instructor', 0, '2025-08-01 17:33:02'),
(3, 'Daniel Mauricio Rivas Ramírez', 'rivasse7en@gmail.com', '$2y$10$CES1sjGkQIv4j4hOMuvz/eN3CYFGNdttfbANgWvHai/krcXtLxu82', 'instructor', 1, '2025-08-01 22:48:33'),
(4, 'Carlos Fernando Restrepo Blandón', 'minegritolindo3@mail.com', '$2y$10$VsEaIccWzyG.MWM5NAw1eeoaSd4TQ/mFfX3rLTob.o.PfQLBQBM5a', 'instructor', 1, '2025-08-01 22:50:18'),
(5, 'Elvis Joan Morales Quiroz', 'morales.elvisjoan@mail.com', '$2y$10$ASusV1q3oPCD63rjJ0Q.XO.wivgQuawTwTkf1Ix3ONjiJtAmnENM6', 'instructor', 1, '2025-08-01 22:50:41'),
(6, 'Jaime Grisales Marín', 'jagrisalingdao@gmail.com', '$2y$10$j04Zzo9kEQeVJ9WoTmZ5FOlIWvN.kZBJauWGGUWRW1KuTdsj/z976', 'instructor', 1, '2025-08-01 22:51:02'),
(7, '﻿Jorge Andres Acosta Franco', 'consultoriasandresacosta@gmail.com', '$2y$10$QsrCnGjZvtUfeKbNSNYb0.GAosngdipXtD7rmIXfbjZEKXZcqxYLO', 'instructor', 1, '2025-08-01 22:51:43'),
(8, 'Mauricio López Jaramillo', 'aca.mlj@gmail.com', '$2y$10$KwU.VyN/sMWDWAJ0sL5fpuOx22ppGqO8Sf/R3RoEfN4HnvdoHGaaq', 'instructor', 0, '2025-08-01 22:52:00'),
(9, 'Yeison Dariel Zapata Monsalve', 'aleyei.yz@gmail.com', '$2y$10$ESDTjDVAfLi4LjmEuOxUbuPUyVA4T0Uws4k7aRsElOWEJHBVVynk.', 'instructor', 1, '2025-08-01 22:53:23'),
(10, 'Administrador SIAO', 'admin@siaooficial.com', '$2y$10$AlwuFnLVCUXs5W9O7Si9XO2nBL./OAxsMSWfYoj6InTmrEPunDwvC', 'admin', 1, '2025-08-20 01:55:55'),
(11, 'Santiago Dávila Vásquez', 'sandavila2024@hotmail.com', '$2y$10$i588kOqzZNuOUO6wgMIrJu/t6ncq3Dex0ZID4lGO5ZAEwdN4guGEW', 'instructor', 1, '2025-08-30 03:14:31'),
(12, 'Carlos Fernando Restrepo Blandón', 'minegritolindo3@gmail.com', '$2y$10$GoI4CLMvSvmReU/bLWtn1.weacID.37.MLPBCWYv/q46YKvMZH0sa', 'instructor', 1, '2026-02-03 01:27:08');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `afiliados_siao`
--
ALTER TABLE `afiliados_siao`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `documento` (`documento`);

--
-- Indices de la tabla `asistencia`
--
ALTER TABLE `asistencia`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_fecha` (`fecha`),
  ADD KEY `idx_afiliado_fecha` (`afiliado_id`,`fecha`),
  ADD KEY `instructor_id` (`instructor_id`),
  ADD KEY `horario_id` (`horario_id`);

--
-- Indices de la tabla `asistencias`
--
ALTER TABLE `asistencias`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `configuracion`
--
ALTER TABLE `configuracion`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `clave` (`clave`),
  ADD KEY `idx_clave` (`clave`);

--
-- Indices de la tabla `contabilidad_conceptos`
--
ALTER TABLE `contabilidad_conceptos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `contabilidad_concepto_distribucion`
--
ALTER TABLE `contabilidad_concepto_distribucion`
  ADD PRIMARY KEY (`id`),
  ADD KEY `concepto_id` (`concepto_id`),
  ADD KEY `rubro_id` (`rubro_id`);

--
-- Indices de la tabla `contabilidad_cuentas_bancarias`
--
ALTER TABLE `contabilidad_cuentas_bancarias`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `contabilidad_movimientos`
--
ALTER TABLE `contabilidad_movimientos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `concepto_id` (`concepto_id`),
  ADD KEY `fk_movimiento_cuenta` (`cuenta_id`);

--
-- Indices de la tabla `contabilidad_presupuesto_anual`
--
ALTER TABLE `contabilidad_presupuesto_anual`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `anio` (`anio`);

--
-- Indices de la tabla `contabilidad_rubros`
--
ALTER TABLE `contabilidad_rubros`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `evaluaciones_franjas`
--
ALTER TABLE `evaluaciones_franjas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_evaluaciones_afiliado_fecha` (`afiliado_id`,`fecha`),
  ADD KEY `idx_evaluaciones_evaluador` (`evaluador_id`),
  ADD KEY `idx_evaluaciones_estado` (`estado`),
  ADD KEY `idx_evaluaciones_color_grado` (`color`,`grado`);

--
-- Indices de la tabla `examenes`
--
ALTER TABLE `examenes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_afiliado` (`afiliado_id`),
  ADD KEY `idx_fecha` (`fecha_examen`),
  ADD KEY `idx_aprobado` (`aprobado`),
  ADD KEY `pago_id` (`pago_id`);

--
-- Indices de la tabla `horarios`
--
ALTER TABLE `horarios`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `instructor_horario`
--
ALTER TABLE `instructor_horario`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `instructor_id` (`instructor_id`,`horario_id`),
  ADD KEY `horario_id` (`horario_id`);

--
-- Indices de la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_afiliado` (`afiliado_id`),
  ADD KEY `idx_tipo_periodo` (`tipo_pago`,`periodo`),
  ADD KEY `idx_estado` (`estado`),
  ADD KEY `idx_fecha` (`fecha_pago`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `correo` (`correo`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `afiliados_siao`
--
ALTER TABLE `afiliados_siao`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=167;

--
-- AUTO_INCREMENT de la tabla `asistencia`
--
ALTER TABLE `asistencia`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=716;

--
-- AUTO_INCREMENT de la tabla `asistencias`
--
ALTER TABLE `asistencias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `configuracion`
--
ALTER TABLE `configuracion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de la tabla `contabilidad_conceptos`
--
ALTER TABLE `contabilidad_conceptos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `contabilidad_concepto_distribucion`
--
ALTER TABLE `contabilidad_concepto_distribucion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `contabilidad_cuentas_bancarias`
--
ALTER TABLE `contabilidad_cuentas_bancarias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `contabilidad_movimientos`
--
ALTER TABLE `contabilidad_movimientos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `contabilidad_presupuesto_anual`
--
ALTER TABLE `contabilidad_presupuesto_anual`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `contabilidad_rubros`
--
ALTER TABLE `contabilidad_rubros`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `evaluaciones_franjas`
--
ALTER TABLE `evaluaciones_franjas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT de la tabla `examenes`
--
ALTER TABLE `examenes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `horarios`
--
ALTER TABLE `horarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `instructor_horario`
--
ALTER TABLE `instructor_horario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT de la tabla `pagos`
--
ALTER TABLE `pagos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `asistencia`
--
ALTER TABLE `asistencia`
  ADD CONSTRAINT `asistencia_ibfk_1` FOREIGN KEY (`afiliado_id`) REFERENCES `afiliados_siao` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `asistencia_ibfk_2` FOREIGN KEY (`instructor_id`) REFERENCES `afiliados_siao` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `asistencia_ibfk_3` FOREIGN KEY (`horario_id`) REFERENCES `horarios` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `contabilidad_concepto_distribucion`
--
ALTER TABLE `contabilidad_concepto_distribucion`
  ADD CONSTRAINT `contabilidad_concepto_distribucion_ibfk_1` FOREIGN KEY (`concepto_id`) REFERENCES `contabilidad_conceptos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `contabilidad_concepto_distribucion_ibfk_2` FOREIGN KEY (`rubro_id`) REFERENCES `contabilidad_rubros` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `contabilidad_movimientos`
--
ALTER TABLE `contabilidad_movimientos`
  ADD CONSTRAINT `contabilidad_movimientos_ibfk_1` FOREIGN KEY (`concepto_id`) REFERENCES `contabilidad_conceptos` (`id`),
  ADD CONSTRAINT `fk_movimiento_cuenta` FOREIGN KEY (`cuenta_id`) REFERENCES `contabilidad_cuentas_bancarias` (`id`);

--
-- Filtros para la tabla `evaluaciones_franjas`
--
ALTER TABLE `evaluaciones_franjas`
  ADD CONSTRAINT `evaluaciones_franjas_ibfk_1` FOREIGN KEY (`afiliado_id`) REFERENCES `afiliados_siao` (`id`),
  ADD CONSTRAINT `fk_evaluaciones_evaluador` FOREIGN KEY (`evaluador_id`) REFERENCES `afiliados_siao` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `examenes`
--
ALTER TABLE `examenes`
  ADD CONSTRAINT `examenes_ibfk_1` FOREIGN KEY (`afiliado_id`) REFERENCES `afiliados_siao` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `examenes_ibfk_2` FOREIGN KEY (`pago_id`) REFERENCES `pagos` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `instructor_horario`
--
ALTER TABLE `instructor_horario`
  ADD CONSTRAINT `instructor_horario_ibfk_1` FOREIGN KEY (`instructor_id`) REFERENCES `afiliados_siao` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `instructor_horario_ibfk_2` FOREIGN KEY (`horario_id`) REFERENCES `horarios` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `instructor_horario_ibfk_3` FOREIGN KEY (`instructor_id`) REFERENCES `afiliados_siao` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `instructor_horario_ibfk_4` FOREIGN KEY (`horario_id`) REFERENCES `horarios` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD CONSTRAINT `pagos_ibfk_1` FOREIGN KEY (`afiliado_id`) REFERENCES `afiliados_siao` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
