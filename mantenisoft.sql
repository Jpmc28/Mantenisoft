-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 28-01-2025 a las 22:29:55
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
-- Estructura de tabla para la tabla `administratoruser`
--

CREATE TABLE `administratoruser` (
  `IdCedula` int(13) NOT NULL,
  `Nombre` varchar(50) DEFAULT NULL,
  `lastName` varchar(50) DEFAULT NULL,
  `InstitucionalEmail` varchar(50) DEFAULT NULL,
  `Rol` varchar(50) DEFAULT NULL,
  `idDevice1` int(11) DEFAULT NULL,
  `idDevice2` int(11) DEFAULT NULL,
  `idDevice3` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `administratoruser`
--

INSERT INTO `administratoruser` (`IdCedula`, `Nombre`, `lastName`, `InstitucionalEmail`, `Rol`, `idDevice1`, `idDevice2`, `idDevice3`) VALUES
(1028861751, 'Juan Pablo', 'Martin Corredor', 'aprendizsistemas@clinicanuevaellago.com', 'auxiliar de sistemas', 1, 2, 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `computers`
--

CREATE TABLE `computers` (
  `idPC` int(11) NOT NULL,
  `license_plate_number` varchar(15) DEFAULT NULL,
  `domain` varchar(30) DEFAULT NULL,
  `ram` varchar(7) DEFAULT NULL,
  `processor` varchar(30) DEFAULT NULL,
  `internal_storage` varchar(20) DEFAULT NULL,
  `staff_in_charge` varchar(40) DEFAULT NULL,
  `picture_device` longblob DEFAULT NULL,
  `type_deviceid` int(11) DEFAULT NULL,
  `device_brand` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `landline_phone`
--

CREATE TABLE `landline_phone` (
  `Idlandline_phone` int(11) NOT NULL,
  `place_in_charge` varchar(50) DEFAULT NULL,
  `idDevice` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `maintencecomputer`
--

CREATE TABLE `maintencecomputer` (
  `idMaintence` int(11) NOT NULL,
  `detail` varchar(500) DEFAULT NULL,
  `idcomputer` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mantenisoftdevices`
--

CREATE TABLE `mantenisoftdevices` (
  `idDevices` int(11) NOT NULL,
  `NameDevices` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `mantenisoftdevices`
--

INSERT INTO `mantenisoftdevices` (`idDevices`, `NameDevices`) VALUES
(1, 'computers'),
(2, 'landline_phone'),
(3, 'printers');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `printer`
--

CREATE TABLE `printer` (
  `idPrinter` int(11) NOT NULL,
  `last_toner_date_change` date NOT NULL DEFAULT curdate(),
  `last_drum_date_change` date NOT NULL DEFAULT curdate(),
  `machine_model` varchar(20) DEFAULT NULL,
  `serial_machine` varchar(20) DEFAULT NULL,
  `place_in_charge` varchar(30) DEFAULT NULL,
  `last_maintenance_date` varchar(10) DEFAULT NULL,
  `idDevice` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `administratoruser`
--
ALTER TABLE `administratoruser`
  ADD PRIMARY KEY (`IdCedula`),
  ADD KEY `idDevice1` (`idDevice1`),
  ADD KEY `idDevice2` (`idDevice2`),
  ADD KEY `idDevice3` (`idDevice3`);

--
-- Indices de la tabla `computers`
--
ALTER TABLE `computers`
  ADD PRIMARY KEY (`idPC`),
  ADD KEY `type_deviceid` (`type_deviceid`);

--
-- Indices de la tabla `landline_phone`
--
ALTER TABLE `landline_phone`
  ADD PRIMARY KEY (`Idlandline_phone`),
  ADD KEY `idDevice` (`idDevice`);

--
-- Indices de la tabla `maintencecomputer`
--
ALTER TABLE `maintencecomputer`
  ADD PRIMARY KEY (`idMaintence`),
  ADD KEY `idcomputer` (`idcomputer`);

--
-- Indices de la tabla `mantenisoftdevices`
--
ALTER TABLE `mantenisoftdevices`
  ADD PRIMARY KEY (`idDevices`);

--
-- Indices de la tabla `printer`
--
ALTER TABLE `printer`
  ADD PRIMARY KEY (`idPrinter`),
  ADD KEY `idDevice` (`idDevice`);

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `administratoruser`
--
ALTER TABLE `administratoruser`
  ADD CONSTRAINT `administratoruser_ibfk_1` FOREIGN KEY (`idDevice1`) REFERENCES `mantenisoftdevices` (`idDevices`),
  ADD CONSTRAINT `administratoruser_ibfk_2` FOREIGN KEY (`idDevice2`) REFERENCES `mantenisoftdevices` (`idDevices`),
  ADD CONSTRAINT `administratoruser_ibfk_3` FOREIGN KEY (`idDevice3`) REFERENCES `mantenisoftdevices` (`idDevices`);

--
-- Filtros para la tabla `computers`
--
ALTER TABLE `computers`
  ADD CONSTRAINT `computers_ibfk_1` FOREIGN KEY (`type_deviceid`) REFERENCES `mantenisoftdevices` (`idDevices`);

--
-- Filtros para la tabla `landline_phone`
--
ALTER TABLE `landline_phone`
  ADD CONSTRAINT `landline_phone_ibfk_1` FOREIGN KEY (`idDevice`) REFERENCES `mantenisoftdevices` (`idDevices`);

--
-- Filtros para la tabla `maintencecomputer`
--
ALTER TABLE `maintencecomputer`
  ADD CONSTRAINT `maintencecomputer_ibfk_1` FOREIGN KEY (`idcomputer`) REFERENCES `computers` (`idPC`);

--
-- Filtros para la tabla `printer`
--
ALTER TABLE `printer`
  ADD CONSTRAINT `printer_ibfk_1` FOREIGN KEY (`idDevice`) REFERENCES `mantenisoftdevices` (`idDevices`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
