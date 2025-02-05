-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 05-02-2025 a las 02:40:31
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.2.4

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
  `NPlaca` varchar(8) NOT NULL
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
(1002, 'IDIME', 1002),
(10010, 'FARMACIA PRINCIPAL', 1001),
(10011, 'ALMACEN', 1001),
(10012, 'NUTRICION', 1001),
(10013, 'MANTENIMIENTO', 1001),
(10014, 'BIOMEDICOS', 1001),
(10100, 'URGENCIAS', 1010),
(10101, 'CONSULTA EXTERNA', 1010),
(10102, 'ADMISION URGENCIAS', 1010),
(10103, 'RECEPCION', 1010),
(10104, 'MONITOREO', 1010),
(10200, 'OBSERVACION URGENCIAS', 1020),
(10201, 'PROGRAMACION DE CIRUGIA', 1020),
(10202, 'ARCHIVO', 1020),
(10203, 'RECEPCION', 1020),
(10300, 'UNIDAD DE CUIDADOS INTENCIVOS INTERMEDI0S', 1030),
(10301, 'RECEPCION', 1030),
(10302, 'SISTEMAS', 1030),
(10400, 'SALAS DE CIRUGIA', 1040),
(10500, 'UCI', 1050),
(10501, 'RECEPCION', 1050),
(10600, 'HOSPITALIZACION', 1060),
(10601, 'CONSULTA EXTERNA', 1060),
(10700, 'HOSPITALIZACION', 1070),
(10701, 'CONSULTA EXTERNA', 1070),
(10800, 'HOSPITALIZACION', 1080),
(10801, 'CONSULTA EXTERNA', 1080),
(10900, 'HOSPITALIZACION', 1090),
(10901, 'CONSULTA EXTERNA', 1090),
(11000, 'AREA ADMINISTRATIVA', 1100);

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
(100010, 'FASE 1', 10100),
(100011, 'FASE 2', 10100),
(100012, 'REANIMACION', 10100),
(100013, 'PROCEDIMIENTOS', 10100),
(100014, 'PASILLOS', 10100),
(100015, 'CONSULTORIO 1', 10100),
(100016, 'CONSULTORIO 2', 10100),
(100017, 'CONSULTORIO 1', 10101),
(100018, 'CONSULTORIO 2', 10101),
(100019, 'CONSULTORIO 3', 10101),
(100020, 'CONSULTORIO 4', 10101),
(100021, 'CONSULTORIO 5', 10101),
(100022, 'LINEA DE FRENTE', 10101),
(100023, 'LINEA DE FRENTE', 10102),
(100024, 'TRIAGE 1', 10102),
(100025, 'TRIAGE 2', 10102),
(100026, 'FASE 1', 10200),
(100027, 'FASE 2', 10200),
(100028, 'ESTAR MEDICO', 10200),
(100029, 'FASE 1', 10300),
(100030, 'FASE 2', 10300),
(100031, 'ESTAR MEDICO', 10300);

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
-- Estructura de tabla para la tabla `mantenimientos`
--

CREATE TABLE `mantenimientos` (
  `id_mantenimiento` int(11) NOT NULL,
  `id_activo` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `fecha_mantenimiento` datetime DEFAULT current_timestamp(),
  `descripcion` text NOT NULL
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
-- Estructura de tabla para la tabla `reparaciones_timbres`
--

CREATE TABLE `reparaciones_timbres` (
  `id_reparacion` varchar(36) NOT NULL,
  `id_activo` int(11) NOT NULL,
  `foto_dano` longblob NOT NULL,
  `numero_solicitud` varchar(50) NOT NULL,
  `tipo_dano` enum('interno','externo') NOT NULL,
  `estado` enum('En curso','Completado') DEFAULT 'En curso',
  `diagnostico` text NOT NULL,
  `fecha_reporte` datetime DEFAULT current_timestamp(),
  `id_usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `tipo_usuario` enum('admin','activos','visualizador') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `nombre`, `correo`, `contraseña`, `Cedula`, `Rol`, `tipo_usuario`) VALUES
(101, 'Juan Pablo Martin Corredor', 'aprendizsistemas@clinicanuevaellago.com', '12345', 1028861751, 'Auxiliar de sistemas (sena)', 'admin');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `activos`
--
ALTER TABLE `activos`
  ADD PRIMARY KEY (`id_activo`),
  ADD KEY `id_area` (`id_area`),
  ADD KEY `id_usuario` (`id_usuario`);

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
-- Indices de la tabla `pisos`
--
ALTER TABLE `pisos`
  ADD PRIMARY KEY (`id_piso`);

--
-- Indices de la tabla `reparaciones_timbres`
--
ALTER TABLE `reparaciones_timbres`
  ADD PRIMARY KEY (`id_reparacion`),
  ADD KEY `id_activo` (`id_activo`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `correo` (`correo`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `activos`
--
ALTER TABLE `activos`
  MODIFY `id_activo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `areas`
--
ALTER TABLE `areas`
  MODIFY `id_area` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11001;

--
-- AUTO_INCREMENT de la tabla `areas_especificas`
--
ALTER TABLE `areas_especificas`
  MODIFY `id_area_especifica` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=100032;

--
-- AUTO_INCREMENT de la tabla `cambios_impresoras`
--
ALTER TABLE `cambios_impresoras`
  MODIFY `id_cambio` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `especificaciones`
--
ALTER TABLE `especificaciones`
  MODIFY `id_especificacion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `mantenimientos`
--
ALTER TABLE `mantenimientos`
  MODIFY `id_mantenimiento` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `mantenimientos_telefonos`
--
ALTER TABLE `mantenimientos_telefonos`
  MODIFY `id_mantenimiento_telefono` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `pisos`
--
ALTER TABLE `pisos`
  MODIFY `id_piso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1101;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=102;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `activos`
--
ALTER TABLE `activos`
  ADD CONSTRAINT `activos_ibfk_1` FOREIGN KEY (`id_area`) REFERENCES `areas` (`id_area`) ON DELETE CASCADE,
  ADD CONSTRAINT `activos_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE;

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
-- Filtros para la tabla `reparaciones_timbres`
--
ALTER TABLE `reparaciones_timbres`
  ADD CONSTRAINT `reparaciones_timbres_ibfk_1` FOREIGN KEY (`id_activo`) REFERENCES `activos` (`id_activo`) ON DELETE CASCADE,
  ADD CONSTRAINT `reparaciones_timbres_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
