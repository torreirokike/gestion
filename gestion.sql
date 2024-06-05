-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 05-06-2024 a las 13:01:53
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `gestion`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `caja`
--

CREATE TABLE `caja` (
  `ID_Operacion` int(11) NOT NULL,
  `Tipo` enum('Cobro','Pago') DEFAULT NULL,
  `ID_Proveedor` int(11) DEFAULT NULL,
  `ID_Cliente` int(11) DEFAULT NULL,
  `Fecha` date DEFAULT NULL,
  `Total` decimal(10,2) DEFAULT NULL,
  `Saldo` decimal(10,2) DEFAULT NULL,
  `Observaciones` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `ID_Cliente` int(11) NOT NULL,
  `Nombre` varchar(255) NOT NULL,
  `Razon_Social` varchar(255) NOT NULL,
  `Tipo_Documento` enum('CUIT','DNI') NOT NULL,
  `Documento` varchar(50) NOT NULL,
  `Telefono` varchar(50) DEFAULT NULL,
  `Direccion` varchar(255) DEFAULT NULL,
  `Observaciones` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`ID_Cliente`, `Nombre`, `Razon_Social`, `Tipo_Documento`, `Documento`, `Telefono`, `Direccion`, `Observaciones`) VALUES
(2, 'Flavio Ferretot', 'Ferretot', 'DNI', '30301', '11', 'Direccion', 'Observaciones2'),
(3, 'Alejandro', 'Corachieli', 'DNI', '111', '111', '111', '111');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `movimientos`
--

CREATE TABLE `movimientos` (
  `ID_Movimiento` int(11) NOT NULL,
  `Fecha` date DEFAULT NULL,
  `Tipo` text NOT NULL,
  `Monto` decimal(10,2) DEFAULT NULL,
  `Observaciones` text DEFAULT NULL,
  `ID_Operacion` int(11) DEFAULT NULL,
  `Saldo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `movimientos`
--

INSERT INTO `movimientos` (`ID_Movimiento`, `Fecha`, `Tipo`, `Monto`, `Observaciones`, `ID_Operacion`, `Saldo`) VALUES
(44, '2024-06-04', 'Cobro', 2000.00, '', 59, 0),
(45, '2024-06-04', 'Pago', 1000.00, '', 60, 0),
(46, '2024-06-04', 'Pago', 1000.00, '', 61, 0),
(47, '2024-06-04', 'Cobro', 1000.00, '', NULL, 0),
(48, '2024-06-04', 'Pago', 1000.00, '', NULL, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `movimientos2`
--

CREATE TABLE `movimientos2` (
  `ID_Movimiento` int(11) NOT NULL,
  `ID_Operacion` int(11) DEFAULT NULL,
  `Fecha` date DEFAULT NULL,
  `Monto` decimal(10,2) DEFAULT NULL,
  `Observaciones` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `operaciones`
--

CREATE TABLE `operaciones` (
  `ID_Operacion` int(11) NOT NULL,
  `Tipo` enum('Compra','Venta') DEFAULT NULL,
  `ID_Proveedor` int(11) DEFAULT NULL,
  `ID_Cliente` int(11) DEFAULT NULL,
  `Fecha` date DEFAULT NULL,
  `Descuento_Porcentaje` decimal(5,2) DEFAULT NULL,
  `Observaciones` text NOT NULL,
  `Total` decimal(10,2) DEFAULT NULL,
  `Saldo` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `operaciones`
--

INSERT INTO `operaciones` (`ID_Operacion`, `Tipo`, `ID_Proveedor`, `ID_Cliente`, `Fecha`, `Descuento_Porcentaje`, `Observaciones`, `Total`, `Saldo`) VALUES
(59, 'Venta', NULL, 2, '2024-06-04', 0.00, '', 2000.00, 0.00),
(60, 'Compra', 1, NULL, '2024-06-04', 0.00, '', 1000.00, 0.00),
(61, 'Compra', 1, NULL, '2024-06-04', 0.00, '', 22500.00, 0.00),
(62, 'Compra', 1, NULL, '2024-06-04', 0.00, '', 50000.00, 0.00),
(65, 'Compra', 1, NULL, '2024-06-04', 20.00, 'nueva editable', 139100.00, 0.00),
(66, 'Compra', 2, NULL, '2024-06-05', 30.00, 'observaciones de 4 articulos', 21000.00, 0.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagos_cobros`
--

CREATE TABLE `pagos_cobros` (
  `ID_PagoCobro` int(11) NOT NULL,
  `ID_Cliente` int(11) DEFAULT NULL,
  `ID_Proveedor` int(11) DEFAULT NULL,
  `Fecha` date DEFAULT NULL,
  `Tipo` enum('Cobro','Pago') DEFAULT NULL,
  `Monto` decimal(10,2) DEFAULT NULL,
  `SaldoPendiente` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `presupuestos`
--

CREATE TABLE `presupuestos` (
  `ID_Presupuesto` int(11) NOT NULL,
  `ID_Cliente` int(11) DEFAULT NULL,
  `Fecha` date DEFAULT NULL,
  `Descuento_Porcentaje` decimal(5,2) DEFAULT NULL,
  `Observaciones` text DEFAULT NULL,
  `Total` decimal(10,2) DEFAULT NULL,
  `Tipo` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `presupuestos`
--

INSERT INTO `presupuestos` (`ID_Presupuesto`, `ID_Cliente`, `Fecha`, `Descuento_Porcentaje`, `Observaciones`, `Total`, `Tipo`) VALUES
(1, 2, '2024-06-04', 0.00, '', 1000.00, 'Presupuesto'),
(2, 2, '2024-06-04', 20.00, '', 200.00, 'Presupuesto');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `ID_Producto` int(11) NOT NULL,
  `Nombre` varchar(255) NOT NULL,
  `Precio_Costo` decimal(10,2) NOT NULL,
  `Precio_Venta` decimal(10,2) NOT NULL,
  `Marca` varchar(255) DEFAULT NULL,
  `Proveedor_ID` int(11) DEFAULT NULL,
  `Observaciones` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`ID_Producto`, `Nombre`, `Precio_Costo`, `Precio_Venta`, `Marca`, `Proveedor_ID`, `Observaciones`) VALUES
(5, 'Caja 25*25', 100.00, 200.00, 'sdf', 1, 'fghgdfghfgh'),
(6, 'Libre', 100.00, 1000.00, 'Generica', 2, '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos_operaciones`
--

CREATE TABLE `productos_operaciones` (
  `ID_Producto_Operacion` int(11) NOT NULL,
  `ID_Operacion` int(11) DEFAULT NULL,
  `ID_Producto` int(11) DEFAULT NULL,
  `Cantidad` int(11) DEFAULT NULL,
  `observaciones` text NOT NULL,
  `Precio_Unitario` decimal(10,2) DEFAULT NULL,
  `Subtotal` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos_operaciones`
--

INSERT INTO `productos_operaciones` (`ID_Producto_Operacion`, `ID_Operacion`, `ID_Producto`, `Cantidad`, `observaciones`, `Precio_Unitario`, `Subtotal`) VALUES
(146, 59, 5, 10, '', 200.00, 2000.00),
(147, 60, 5, 10, '', 100.00, 1000.00),
(148, 61, 6, 100, '', 100.00, 10000.00),
(149, 62, 5, 500, '', 100.00, 50000.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos_presupuestos`
--

CREATE TABLE `productos_presupuestos` (
  `ID_Producto_Presupuesto` int(11) NOT NULL,
  `ID_Presupuesto` int(11) DEFAULT NULL,
  `ID_Producto` int(11) DEFAULT NULL,
  `Cantidad` int(11) DEFAULT NULL,
  `observaciones` text NOT NULL,
  `Precio_Unitario` decimal(10,2) DEFAULT NULL,
  `Subtotal` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos_presupuestos`
--

INSERT INTO `productos_presupuestos` (`ID_Producto_Presupuesto`, `ID_Presupuesto`, `ID_Producto`, `Cantidad`, `observaciones`, `Precio_Unitario`, `Subtotal`) VALUES
(1, 1, 5, 10, '', 100.00, 1000.00),
(2, 2, 5, 10, '', 25.00, 250.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedores`
--

CREATE TABLE `proveedores` (
  `ID_Proveedor` int(11) NOT NULL,
  `Nombre` varchar(255) NOT NULL,
  `Razon_Social` varchar(255) NOT NULL,
  `Tipo_Documento` enum('CUIT','DNI') NOT NULL,
  `Documento` varchar(50) NOT NULL,
  `Telefono` varchar(50) DEFAULT NULL,
  `Direccion` varchar(255) DEFAULT NULL,
  `Observaciones` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `proveedores`
--

INSERT INTO `proveedores` (`ID_Proveedor`, `Nombre`, `Razon_Social`, `Tipo_Documento`, `Documento`, `Telefono`, `Direccion`, `Observaciones`) VALUES
(1, 'Diego Pelado', 'Distribuidora Mana', 'CUIT', '1111', '1111', 'Direccion Diego', 'Observaciones diego'),
(2, 'Otros', 'Otros', 'DNI', '1111', '1111', '1111', '1111');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `role` enum('Admin','User') DEFAULT 'User'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `name`, `role`) VALUES
(1, 'kikesoluciones@gmail.com', 'kike2404', 'Kike', 'Admin'),
(2, 'enriquetorreiro@hotmail.com', 'password', 'Enrique', 'User'),
(3, 'asdasd@asdasd', '$2y$10$qkI2tFZKb4nqF0/QLKU9QeVBO08ysYn6yYUITt3dvfs3yRWtAvc4W', 'juan', 'User');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `caja`
--
ALTER TABLE `caja`
  ADD PRIMARY KEY (`ID_Operacion`);

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`ID_Cliente`);

--
-- Indices de la tabla `movimientos`
--
ALTER TABLE `movimientos`
  ADD PRIMARY KEY (`ID_Movimiento`),
  ADD KEY `ID_Operacion` (`ID_Operacion`);

--
-- Indices de la tabla `movimientos2`
--
ALTER TABLE `movimientos2`
  ADD PRIMARY KEY (`ID_Movimiento`),
  ADD KEY `ID_Operacion` (`ID_Operacion`);

--
-- Indices de la tabla `operaciones`
--
ALTER TABLE `operaciones`
  ADD PRIMARY KEY (`ID_Operacion`),
  ADD KEY `ID_Proveedor` (`ID_Proveedor`),
  ADD KEY `ID_Cliente` (`ID_Cliente`);

--
-- Indices de la tabla `pagos_cobros`
--
ALTER TABLE `pagos_cobros`
  ADD PRIMARY KEY (`ID_PagoCobro`),
  ADD KEY `ID_Cliente` (`ID_Cliente`),
  ADD KEY `ID_Proveedor` (`ID_Proveedor`);

--
-- Indices de la tabla `presupuestos`
--
ALTER TABLE `presupuestos`
  ADD PRIMARY KEY (`ID_Presupuesto`),
  ADD KEY `fk_cliente_presupuesto` (`ID_Cliente`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`ID_Producto`),
  ADD KEY `Proveedor_ID` (`Proveedor_ID`);

--
-- Indices de la tabla `productos_operaciones`
--
ALTER TABLE `productos_operaciones`
  ADD PRIMARY KEY (`ID_Producto_Operacion`);

--
-- Indices de la tabla `productos_presupuestos`
--
ALTER TABLE `productos_presupuestos`
  ADD PRIMARY KEY (`ID_Producto_Presupuesto`),
  ADD KEY `fk_presupuesto_producto` (`ID_Presupuesto`),
  ADD KEY `fk_producto_presupuesto` (`ID_Producto`);

--
-- Indices de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  ADD PRIMARY KEY (`ID_Proveedor`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `caja`
--
ALTER TABLE `caja`
  MODIFY `ID_Operacion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `ID_Cliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `movimientos`
--
ALTER TABLE `movimientos`
  MODIFY `ID_Movimiento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT de la tabla `movimientos2`
--
ALTER TABLE `movimientos2`
  MODIFY `ID_Movimiento` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `operaciones`
--
ALTER TABLE `operaciones`
  MODIFY `ID_Operacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- AUTO_INCREMENT de la tabla `pagos_cobros`
--
ALTER TABLE `pagos_cobros`
  MODIFY `ID_PagoCobro` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `presupuestos`
--
ALTER TABLE `presupuestos`
  MODIFY `ID_Presupuesto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `ID_Producto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `productos_operaciones`
--
ALTER TABLE `productos_operaciones`
  MODIFY `ID_Producto_Operacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=159;

--
-- AUTO_INCREMENT de la tabla `productos_presupuestos`
--
ALTER TABLE `productos_presupuestos`
  MODIFY `ID_Producto_Presupuesto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  MODIFY `ID_Proveedor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `movimientos2`
--
ALTER TABLE `movimientos2`
  ADD CONSTRAINT `movimientos2_ibfk_1` FOREIGN KEY (`ID_Operacion`) REFERENCES `caja` (`ID_Operacion`);

--
-- Filtros para la tabla `operaciones`
--
ALTER TABLE `operaciones`
  ADD CONSTRAINT `operaciones_ibfk_1` FOREIGN KEY (`ID_Proveedor`) REFERENCES `proveedores` (`ID_Proveedor`),
  ADD CONSTRAINT `operaciones_ibfk_2` FOREIGN KEY (`ID_Cliente`) REFERENCES `clientes` (`ID_Cliente`);

--
-- Filtros para la tabla `pagos_cobros`
--
ALTER TABLE `pagos_cobros`
  ADD CONSTRAINT `pagos_cobros_ibfk_1` FOREIGN KEY (`ID_Cliente`) REFERENCES `clientes` (`ID_Cliente`),
  ADD CONSTRAINT `pagos_cobros_ibfk_2` FOREIGN KEY (`ID_Proveedor`) REFERENCES `proveedores` (`ID_Proveedor`);

--
-- Filtros para la tabla `presupuestos`
--
ALTER TABLE `presupuestos`
  ADD CONSTRAINT `fk_cliente_presupuesto` FOREIGN KEY (`ID_Cliente`) REFERENCES `clientes` (`ID_Cliente`);

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `productos_ibfk_1` FOREIGN KEY (`Proveedor_ID`) REFERENCES `proveedores` (`ID_Proveedor`);

--
-- Filtros para la tabla `productos_presupuestos`
--
ALTER TABLE `productos_presupuestos`
  ADD CONSTRAINT `fk_presupuesto_producto` FOREIGN KEY (`ID_Presupuesto`) REFERENCES `presupuestos` (`ID_Presupuesto`),
  ADD CONSTRAINT `fk_producto_presupuesto` FOREIGN KEY (`ID_Producto`) REFERENCES `productos` (`ID_Producto`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
