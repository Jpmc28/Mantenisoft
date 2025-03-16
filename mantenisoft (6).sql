-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 06-03-2025 a las 00:03:57
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `mantenisoft`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `activos`
--

CREATE TABLE `activos` (
  `id_activo` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `tipo` enum('teléfono','computador','portátil','impresora','timbre') NOT NULL,
  `estado` enum('activo','dado de baja') DEFAULT 'activo',
  `id_area` int(11) NOT NULL,
  `imagen` longblob DEFAULT NULL,
  `fecha_ingreso` datetime DEFAULT current_timestamp(),
  `id_usuario` int(11) NOT NULL,
  `NPlaca` varchar(8) NOT NULL,
  `id_areas_especificas` int(11) DEFAULT NULL,
  `fecha_baja` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `areas`
--

CREATE TABLE `areas` (
  `id_area` int(11) NOT NULL,
  `nombre_area` varchar(100) NOT NULL,
  `id_piso` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `areas`
--

INSERT INTO `areas` (`id_area`, `nombre_area`, `id_piso`) VALUES
(1002, 'IDIME S2', 1002),
(10010, 'FARMACIA PRINCIPAL S1', 1001),
(10011, 'ALMACEN S1', 1001),
(10012, 'NUTRICION S1', 1001),
(10013, 'MANTENIMIENTO S1', 1001),
(10014, 'BIOMEDICOS S1', 1001),
(10100, 'URGENCIAS PISO 1', 1010),
(10101, 'CONSULTA EXTERNA PISO 1', 1010),
(10102, 'ADMISION URGENCIAS PISO 1', 1010),
(10103, 'RECEPCION LOBBY PISO 1', 1010),
(10104, 'MONITOREO PISO 1', 1010),
(10200, 'OBSERVACION URGENCIAS PISO 2', 1020),
(10201, 'PROGRAMACION DE CIRUGIA PISO 2', 1020),
(10202, 'ARCHIVO PISO 2', 1020),
(10203, 'RECEPCION PISO 2', 1020),
(10300, 'UNIDAD DE CUIDADOS INTENCIVOS INTERMEDI0S PISO 3', 1030),
(10301, 'RECEPCION PISO 3', 1030),
(10302, 'SISTEMAS PISO 3', 1030),
(10400, 'SALAS DE CIRUGIA PISO 4', 1040),
(10500, 'UCI PISO 5', 1050),
(10501, 'RECEPCION PISO 5', 1050),
(10600, 'HOSPITALIZACION PISO 6', 1060),
(10601, 'CONSULTA EXTERNA PISO 6', 1060),
(10700, 'HOSPITALIZACION PISO 7', 1070),
(10701, 'CONSULTA EXTERNA PISO 7', 1070),
(10800, 'HOSPITALIZACION PISO 8', 1080),
(10801, 'CONSULTA EXTERNA PISO 8', 1080),
(10900, 'HOSPITALIZACION PISO 9', 1090),
(10901, 'CONSULTA EXTERNA PISO 9', 1090),
(11000, 'AREA ADMINISTRATIVA PISO 10', 1100);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `areas_especificas`
--

CREATE TABLE `areas_especificas` (
  `id_area_especifica` int(11) NOT NULL,
  `area_especifica_nombre` varchar(100) NOT NULL,
  `id_area` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `areas_especificas`
--

INSERT INTO `areas_especificas` (`id_area_especifica`, `area_especifica_nombre`, `id_area`) VALUES
(100010, 'FASE 1 URGENCIAS PISO 1', 10100),
(100011, 'FASE 2 URGENCIAS PISO 1', 10100),
(100012, 'REANIMACION URGENCIAS PISO 1', 10100),
(100013, 'PROCEDIMIENTOS URGENCIAS PISO 1', 10100),
(100014, 'PASILLOS URGENCIAS PISO 1', 10100),
(100015, 'CONSULTORIO 1 URGENCIAS PISO 1', 10100),
(100016, 'CONSULTORIO 2 URGENCIAS PISO 1', 10100),
(100017, 'CONSULTORIO 1 CONSULTA EXTERNA PISO 1', 10101),
(100018, 'CONSULTORIO 2 CONSULTA EXTERNA PISO 1', 10101),
(100019, 'CONSULTORIO 3 CONSULTA EXTERNA PISO 1', 10101),
(100020, 'CONSULTORIO 4 CONSULTA EXTERNA PISO 1', 10101),
(100021, 'CONSULTORIO 5 CONSULTA EXTERNA PISO 1', 10101),
(100022, 'LINEA DE FRENTE CONSULTA EXTERNA PISO 1', 10101),
(100023, 'LINEA DE FRENTE ADMISION URGENCIAS PISO 1', 10102),
(100024, 'TRIAGE 1 ADMISION URGENCIAS PISO 1', 10102),
(100025, 'TRIAGE 2 ADMISION URGENCIAS PISO 1', 10102),
(100026, 'FASE 1 OBSERVACION URGENCIAS PISO 2', 10200),
(100027, 'FASE 2 OBSERVACION URGENCIAS PISO 2', 10200),
(100028, 'ESTAR MEDICO OBSERVACION URGENCIAS PISO 2', 10200),
(100029, 'FASE 1 UCIN PISO 3', 10300),
(100030, 'FASE 2 UCIN PISO 3', 10300),
(100031, 'ESTAR MEDICO UCIN PISO 3', 10300),
(100032, 'RECEPCION SALAS DE CIRUGIA PISO 4', 10400),
(100033, 'RECUPERACION SALAS DE CIRUGIA PISO 4', 10400),
(100034, 'CENTRAL DE ESTERILIZACION SALAS DE CIRUGIA PISO 4', 10400),
(100035, 'FARMACIA SALAS DE CIRUGIA PISO 4', 10400),
(100036, 'PASILLOS SALAS DE CIRUGIA PISO 4', 10400),
(100037, 'QUIROFANOS SALAS DE CIRUGIA PISO 4', 10400),
(100038, 'FASE 1 UCI PISO 5', 10500),
(100039, 'FASE 2 UCI PISO 5', 10500),
(100040, 'CUARTO LIMPIO UCI PISO 5', 10500),
(100041, 'FASE 1 HOSPITALIZACION PISO 6', 10600),
(100042, 'FASE 2 HOSPITALIZACION PISO 6', 10600),
(100043, 'CONSULTORIO 1 CONSULTA EXTERNA PISO 6', 10601),
(100044, 'CONSULTORIO 2 CONSULTA EXTERNA PISO 6', 10601),
(100045, 'CONSULTORIO 3 CONSULTA EXTERNA PISO 6', 10601),
(100046, 'CONSULTORIO 4 CONSULTA EXTERNA PISO 6', 10601),
(100047, 'RECEPCION CONSULTA EXTERNA PISO 6', 10601),
(100048, 'FASE 1 HOSPITALIZACION PISO 7', 10700),
(100049, 'FASE 2 HOSPITALIZACION PISO 7', 10700),
(100050, 'CONSULTORIO 1 CONSULTA EXTERNA PISO 7', 10701),
(100051, 'CONSULTORIO 2 CONSULTA EXTERNA PISO 7', 10701),
(100052, 'CONSULTORIO 3 CONSULTA EXTERNA PISO 7', 10701),
(100053, 'CONSULTORIO 4 CONSULTA EXTERNA PISO 7', 10701),
(100054, 'RECEPCION CONSULTA EXTERNA PISO 7', 10701),
(100055, 'FASE 1 HOSPITALIZACION PISO 8', 10800),
(100056, 'FASE 2 HOSPITALIZACION PISO 8', 10800),
(100057, 'CONSULTORIO 1 CONSULTA EXTERNA PISO 8', 10801),
(100058, 'CONSULTORIO 2 CONSULTA EXTERNA PISO 8', 10801),
(100059, 'CONSULTORIO 3 CONSULTA EXTERNA PISO 8', 10801),
(100060, 'CONSULTORIO 4 CONSULTA EXTERNA PISO 8', 10801),
(100061, 'RECEPCION CONSULTA EXTERNA PISO 8', 10801),
(100062, 'FASE 1 HOSPITALIZACION PISO 9', 10900),
(100063, 'FASE 2 HOSPITALIZACION PISO 9', 10900),
(100064, 'CONSULTORIO 1 CONSULTA EXTERNA PISO 9', 10901),
(100065, 'CONSULTORIO 2 CONSULTA EXTERNA PISO 9', 10901),
(100066, 'CONSULTORIO 3 CONSULTA EXTERNA PISO 9', 10901),
(100067, 'RECEPCION CONSULTA EXTERNA PISO 9', 10901),
(100068, 'SISTEMAS PISO 10', 11000),
(100069, 'CONTABILIDAD PISO 10', 11000),
(100070, 'COOR CONTABILIDAD PISO 10', 11000),
(100071, 'COOR TH PISO 10', 11000),
(100072, 'COOR FACTURACION PISO 10', 11000),
(100073, 'COOR SIAU PISO 10', 11000),
(100074, 'GESTION HUMANA 2 PISO 10', 11000),
(100075, 'CALIDAD PISO 10', 11000),
(100076, 'TESORERIA PISO 10', 11000),
(100077, 'COOR MEDICA PISO 10', 11000),
(100078, 'GERENCIA PISO 10', 11000),
(100079, 'RECEPCION GERENCIA PISO 10', 11000),
(100080, 'DIRECCION CX PISO 10', 11000),
(100081, 'NUEVA EPS PISO 10', 11000),
(100082, 'PATOLOGIA PISO 10', 11000);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cambios_impresoras`
--

CREATE TABLE `cambios_impresoras` (
  `id_cambio` int(11) NOT NULL,
  `id_activo` int(11) NOT NULL,
  `tipo_cambio` enum('tóner','drum') NOT NULL,
  `fecha_cambio` datetime DEFAULT current_timestamp(),
  `id_usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `especificaciones`
--

CREATE TABLE `especificaciones` (
  `id_especificacion` int(11) NOT NULL,
  `id_activo` int(11) NOT NULL,
  `procesador` varchar(100) DEFAULT NULL,
  `ram` varchar(50) DEFAULT NULL,
  `almacenamiento` varchar(100) DEFAULT NULL,
  `sistema_operativo` varchar(100) DEFAULT NULL,
  `software_instalado` text DEFAULT NULL,
  `nombre_dominio` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historial`
--

CREATE TABLE `historial` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `accion` varchar(255) NOT NULL,
  `detalles` text NULL,
  `fecha` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mantenimientos`
--

CREATE TABLE `mantenimientos` (
  `id_mantenimiento` int(11) NOT NULL,
  `id_activo` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `fecha_mantenimiento` datetime DEFAULT current_timestamp(),
  `descripcion` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`descripcion`)),
  `nombre_responsable` varchar(255) NOT NULL,
  `cargo_responsable` varchar(255) NOT NULL,
  `firma_tecnico` text NOT NULL,
  `estado` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mantenimientos_telefonos`
--

CREATE TABLE `mantenimientos_telefonos` (
  `id_mantenimiento_telefono` int(11) NOT NULL,
  `id_activo` int(11) NOT NULL,
  `tipo_cambio` enum('cable energía','cable voz','bocina') NOT NULL,
  `imagen_mantenimiento` longblob DEFAULT NULL,
  `fecha_cambio` datetime DEFAULT current_timestamp(),
  `id_usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `perifericos`
--

CREATE TABLE `perifericos` (
  `id_periferico` int(11) NOT NULL,
  `tipo` enum('monitor','teclado','mouse') NOT NULL,
  `placa` varchar(8) DEFAULT NULL,
  `id_activo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pisos`
--

CREATE TABLE `pisos` (
  `id_piso` int(11) NOT NULL,
  `nombre_piso` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pisos`
--

INSERT INTO `pisos` (`id_piso`, `nombre_piso`) VALUES
(1001, 'S1'),
(1002, 'S2'),
(1010, 'P1'),
(1020, 'P2'),
(1030, 'P3'),
(1040, 'P4'),
(1050, 'P5'),
(1060, 'P6'),
(1070, 'P7'),
(1080, 'P8'),
(1090, 'P9'),
(1100, 'P10');


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `contraseña` varchar(255) NOT NULL,
  `Cedula` int(11) NOT NULL,
  `Rol` varchar(100) NOT NULL,
  `tipo_usuario` enum('admin','activos','visualizador','super_usuario') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `nombre`, `correo`, `contraseña`, `Cedula`, `Rol`, `tipo_usuario`) VALUES
(101, 'David Eduardo Salazar Romero', 'david.salazar@clinicanuevaellago.com', 'S0P0RT3', 123456789, 'Supervidor de sistemas', 'super_usuario');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `activos`
--
ALTER TABLE `activos`
  ADD PRIMARY KEY (`id_activo`),
  ADD KEY `id_area` (`id_area`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `fk_activos_especificos` (`id_areas_especificas`);

--
-- Indices de la tabla `areas`
--
ALTER TABLE `areas`
  ADD PRIMARY KEY (`id_area`),
  ADD KEY `id_piso` (`id_piso`);

--
-- Indices de la tabla `areas_especificas`
--
ALTER TABLE `areas_especificas`
  ADD PRIMARY KEY (`id_area_especifica`),
  ADD KEY `id_area` (`id_area`);

--
-- Indices de la tabla `cambios_impresoras`
--
ALTER TABLE `cambios_impresoras`
  ADD PRIMARY KEY (`id_cambio`),
  ADD KEY `id_activo` (`id_activo`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `especificaciones`
--
ALTER TABLE `especificaciones`
  ADD PRIMARY KEY (`id_especificacion`),
  ADD KEY `id_activo` (`id_activo`);

--
-- Indices de la tabla `historial`
--
ALTER TABLE `historial`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `mantenimientos`
--
ALTER TABLE `mantenimientos`
  ADD PRIMARY KEY (`id_mantenimiento`),
  ADD KEY `id_activo` (`id_activo`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `mantenimientos_telefonos`
--
ALTER TABLE `mantenimientos_telefonos`
  ADD PRIMARY KEY (`id_mantenimiento_telefono`),
  ADD KEY `id_activo` (`id_activo`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `perifericos`
--
ALTER TABLE `perifericos`
  ADD PRIMARY KEY (`id_periferico`),
  ADD KEY `id_activo` (`id_activo`);

--
-- Indices de la tabla `pisos`
--
ALTER TABLE `pisos`
  ADD PRIMARY KEY (`id_piso`);


--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `correo` (`correo`);

--
-- AUTO_INCREMENT de la tabla `activos`
--
ALTER TABLE `activos`
  MODIFY `id_activo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `areas`
--
ALTER TABLE `areas`
  MODIFY `id_area` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11001;

--
-- AUTO_INCREMENT de la tabla `areas_especificas`
--
ALTER TABLE `areas_especificas`
  MODIFY `id_area_especifica` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=100083;

--
-- AUTO_INCREMENT de la tabla `cambios_impresoras`
--
ALTER TABLE `cambios_impresoras`
  MODIFY `id_cambio` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `especificaciones`
--
ALTER TABLE `especificaciones`
  MODIFY `id_especificacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `historial`
--
ALTER TABLE `historial`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT de la tabla `mantenimientos`
--
ALTER TABLE `mantenimientos`
  MODIFY `id_mantenimiento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `mantenimientos_telefonos`
--
ALTER TABLE `mantenimientos_telefonos`
  MODIFY `id_mantenimiento_telefono` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `perifericos`
--
ALTER TABLE `perifericos`
  MODIFY `id_periferico` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `pisos`
--
ALTER TABLE `pisos`
  MODIFY `id_piso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1101;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=302;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `activos`
--
ALTER TABLE `activos`
  ADD CONSTRAINT `activos_ibfk_1` FOREIGN KEY (`id_area`) REFERENCES `areas` (`id_area`) ON DELETE CASCADE,
  ADD CONSTRAINT `activos_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_activos_especificos` FOREIGN KEY (`id_areas_especificas`) REFERENCES `areas_especificas` (`id_area_especifica`);

--
-- Filtros para la tabla `areas`
--
ALTER TABLE `areas`
  ADD CONSTRAINT `areas_ibfk_1` FOREIGN KEY (`id_piso`) REFERENCES `pisos` (`id_piso`) ON DELETE CASCADE;

--
-- Filtros para la tabla `areas_especificas`
--
ALTER TABLE `areas_especificas`
  ADD CONSTRAINT `areas_especificas_ibfk_1` FOREIGN KEY (`id_area`) REFERENCES `areas` (`id_area`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `cambios_impresoras`
--
ALTER TABLE `cambios_impresoras`
  ADD CONSTRAINT `cambios_impresoras_ibfk_1` FOREIGN KEY (`id_activo`) REFERENCES `activos` (`id_activo`) ON DELETE CASCADE,
  ADD CONSTRAINT `cambios_impresoras_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE;

--
-- Filtros para la tabla `especificaciones`
--
ALTER TABLE `especificaciones`
  ADD CONSTRAINT `especificaciones_ibfk_1` FOREIGN KEY (`id_activo`) REFERENCES `activos` (`id_activo`) ON DELETE CASCADE;

--
-- Filtros para la tabla `historial`
--
ALTER TABLE `historial`
  ADD CONSTRAINT `historial_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`);

--
-- Filtros para la tabla `mantenimientos`
--
ALTER TABLE `mantenimientos`
  ADD CONSTRAINT `mantenimientos_ibfk_1` FOREIGN KEY (`id_activo`) REFERENCES `activos` (`id_activo`) ON DELETE CASCADE,
  ADD CONSTRAINT `mantenimientos_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE;

--
-- Filtros para la tabla `mantenimientos_telefonos`
--
ALTER TABLE `mantenimientos_telefonos`
  ADD CONSTRAINT `mantenimientos_telefonos_ibfk_1` FOREIGN KEY (`id_activo`) REFERENCES `activos` (`id_activo`) ON DELETE CASCADE,
  ADD CONSTRAINT `mantenimientos_telefonos_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE;

--
-- Filtros para la tabla `perifericos`
--
ALTER TABLE `perifericos`
  ADD CONSTRAINT `perifericos_ibfk_1` FOREIGN KEY (`id_activo`) REFERENCES `activos` (`id_activo`) ON DELETE CASCADE;

DELIMITER $$
--
-- Eventos
--
CREATE DEFINER=`root`@`localhost` EVENT `eliminar_activos_inactivos` ON SCHEDULE EVERY 1 DAY STARTS '2025-03-02 17:18:27' ON COMPLETION NOT PRESERVE ENABLE DO BEGIN
  DELETE FROM `activos` 
  WHERE `estado` = 'dado de baja' 
  AND `fecha_baja` <= NOW() - INTERVAL 2 DAY;
END$$

DELIMITER ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
