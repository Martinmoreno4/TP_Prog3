SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Base de datos: `comanda`
--
CREATE DATABASE IF NOT EXISTS `comanda` DEFAULT CHARACTER SET utf8 COLLATE utf8_spanish2_ci;
USE `comanda`;

-- --------------------------------------------------------

--AREA--

DROP TABLE IF EXISTS `area`;
CREATE TABLE IF NOT EXISTS `area` (
  `area_id` int(11) NOT NULL AUTO_INCREMENT,
  `area_descripcion` varchar(20) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`area_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;


INSERT INTO `area` (`area_id`, `area_descripcion`) VALUES
(1, 'Patio'),
(2, 'Cocina'),
(3, 'Barra'),
(4, 'CandyBar');

-- --------------------------------------------------------

--COMIDA

DROP TABLE IF EXISTS `comida`; 
CREATE TABLE IF NOT EXISTS `comida` (
  `comida_id` int(11) NOT NULL AUTO_INCREMENT, Mesa_estado
  `comida_area` int(11) NOT NULL,
  `comidaPedidoAsociado` int(11) DEFAULT NULL,
  `comida_estado` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `comida_descripcion` varchar(50) COLLATE utf8_spanish2_ci NOT NULL,
  `comida_precio` float NOT NULL,
  `tiempoDeInicio` datetime NOT NULL,
  `tiempoDeFinal` datetime DEFAULT NULL,
  `tiempoParaTerminar` int(11) DEFAULT NULL,
  PRIMARY KEY (`comida_id`),
  KEY `FK_comida_pedido_asoc` (`comidaPedidoAsociado`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;


INSERT INTO `comida` (`comida_id`, `comida_area`, `comidaPedidoAsociado`, `comida_estado`, `comida_descripcion`, `comida_precio`, `tiempoDeInicio`, `tiempoDeFinal`, `tiempoParaTerminar`) VALUES
(9, 2, 8, 'Listo Para Servir', 'encuestao Al Champignon', 550, '2021-11-27 02:50:33', '2021-11-27 03:20:33', 30),
(10, 3, 8, 'Listo Para Servir', 'Gaseosa Linea Pepsi 2lt.', 300, '2021-11-27 02:51:24', '2021-11-27 02:56:24', 35),
(11, 3, 8, 'Listo Para Servir', 'Gaseosa Linea Pepsi 2lt.', 300, '2021-11-27 03:05:14', '2021-11-27 03:10:14', 5),
(12, 3, 8, 'Listo Para Servir', 'Gaseosa Linea Pepsi 2lt.', 300, '2021-11-27 03:05:51', '2021-11-27 03:10:51', 5),
(13, 2, 8, 'Listo Para Servir', 'Hamburguesa con Bacon', 550, '2021-11-27 03:06:59', '2021-11-27 03:26:59', 20),
(14, 2, 8, 'Listo Para Servir', 'Hamburguesa con Cheddar y Guarnicion', 550, '2021-11-27 03:09:14', '2021-11-27 03:27:14', 18),
(15, 2, 8, 'Listo Para Servir', 'Ensalada Waldorf', 550, '2021-11-27 03:10:27', '2021-11-27 03:17:27', 7),
(16, 2, 9, 'Listo Para Servir', 'Ensalada Waldorf', 350, '2021-11-27 11:54:41', '2021-11-27 12:01:41', 7),
(17, 2, 9, 'Listo Para Servir', 'Ensalada Rusa', 250, '2021-11-27 11:55:24', '2021-11-27 12:03:24', 8),
(18, 2, 10, 'Listo Para Servir', 'encuestao al Champignon', 450, '2021-11-28 00:16:04', '2021-11-28 00:36:04', 0),
(19, 2, 10, 'Listo Para Servir', 'encuestao al Verdeo', 400, '2021-11-28 00:16:29', '2021-11-28 00:38:29', 0),
(20, 3, 10, 'Listo Para Servir', 'Cerveza Stella Artois 1lt.', 300, '2021-11-28 00:17:06', '2021-11-28 00:22:06', 0),
(21, 3, 11, 'Listo Para Servir', 'Cerveza Stella Artois 1lt.', 300, '2021-11-28 20:01:14', '2021-11-28 20:06:14', 0),
(22, 3, 11, 'Listo Para Servir', 'Cerveza Rabieta Irish Ale 750ml.', 300, '2021-11-28 20:01:46', '2021-11-28 20:08:46', 0),
(23, 2, 11, 'Listo Para Servir', 'Papas bravas', 450, '2021-11-28 20:02:07', '2021-11-28 20:27:07', 0),
(24, 2, 11, 'Listo Para Servir', 'Papas con Cheddar & Bacon', 500, '2021-11-28 20:02:29', '2021-11-28 20:32:29', 0);

-- --------------------------------------------------------

--EMPLEADO

DROP TABLE IF EXISTS `Empleados`;
CREATE TABLE IF NOT EXISTS `Empleados` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idDeUsuario` int(11) DEFAULT NULL,
  `empleado_area_id` int(11) DEFAULT NULL,
  `nombre` varchar(40) COLLATE utf8_spanish2_ci NOT NULL,
  `fechaDeInicio` datetime NOT NULL,
  `fechaDeFin` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_empleado_area` (`empleado_area_id`), 
  KEY `FK_empleado_usuario` (`idDeUsuario`) 
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci comentario='Empleados table';




INSERT INTO `Empleados` (`id`, `idDeUsuario`, `empleado_area_id`, `nombre`, `fechaDeInicio`, `fechaDeFin`) VALUES
(11, 15, 1, 'Athena', '2021-11-27 01:54:58', NULL),
(12, 16, 1, 'Persefone', '2021-11-27 01:55:33', NULL),
(13, 17, 1, 'Hera', '2021-11-27 01:55:44', NULL),
(14, 18, 2, 'Hades', '2021-11-27 01:56:01', NULL),
(15, 19, 2, 'Zeus', '2021-11-27 01:56:28', NULL),
(16, 20, 2, 'Odin', '2021-11-27 01:56:36', NULL),
(17, 21, 3, 'Poseidon', '2021-11-27 01:58:02', NULL),
(18, 22, 3, 'Wukong', '2021-11-27 01:58:24', NULL),
(19, 10, 4, 'Facu Falcone', '2021-11-27 01:59:42', NULL),
(20, 30, 3, 'Lilith', '2021-11-28 19:38:09', NULL);
-- --------------------------------------------------------

-- HISTORIAL INGRESOS --

DROP TABLE IF EXISTS `HistorialIngresos`;
CREATE TABLE IF NOT EXISTS `HistorialIngresos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idDeUsuario` int(11) NOT NULL,
  `nombreUsuario` varchar(40) COLLATE utf8_spanish2_ci NOT NULL, 
  `fechaDeIngreso` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_ingreso_de_Usuario` (`idDeUsuario`)
) ENGINE=InnoDB AUTO_INCREMENT=136 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci; 



INSERT INTO `HistorialIngresos` (`id`, `idDeUsuario`, `nombreUsuario`, `fechaDeIngreso`) VALUES
(119, 10, 'Facu', '2021-11-27 00:54:03'),
(120, 10, 'Facu', '2021-11-27 00:58:51'),
(121, 10, 'Facu', '2021-11-27 01:00:10'),
(122, 10, 'Facu', '2021-11-27 01:11:00'),
(123, 10, 'Facu', '2021-11-27 01:16:53'),
(124, 10, 'Facu', '2021-11-27 01:18:08'),
(125, 10, 'Facu', '2021-11-27 01:49:22'),
(126, 15, 'C1', '2021-11-27 02:25:33'),
(127, 15, 'C1', '2021-11-27 02:26:44'),
(128, 16, 'C2', '2021-11-27 03:12:04'),
(129, 17, 'C3', '2021-11-27 03:12:33'),
(130, 21, 'Bar1', '2021-11-27 03:12:56'),
(131, 22, 'Bar2', '2021-11-27 03:13:16'),
(132, 18, 'Co1', '2021-11-27 03:13:35'),
(133, 19, 'Co2', '2021-11-27 03:13:49'),
(134, 20, 'Co3', '2021-11-27 03:14:07'),
(135, 20, 'Co3', '2021-11-28 19:33:22');



-- --------------------------------------------------------

--PEDIDO

DROP TABLE IF EXISTS `Pedidos`;
CREATE TABLE IF NOT EXISTS `Pedidos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mesa_id` int(11) DEFAULT NULL,
  `estadoDePedido` varchar(30) COLLATE utf8_spanish2_ci NOT NULL DEFAULT 'Pendiente',
  `nombreCliente` varchar(50) COLLATE utf8_spanish2_ci NOT NULL,
  `imagenDePedido` varchar(100) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `precioPedido` float NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `FK_mesa_pedidos` (`mesa_id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci; 



INSERT INTO `Pedidos` (`id`, `mesa_id`, `estadoDePedido`, `nombreCliente`, `imagenDePedido`, `precioPedido`) VALUES
(8, 2, 'En Preparacion', 'Fulano_01', './OrderImages/8.png', 3100),
(9, 3, 'En Preparacion', 'Fulano_02', './OrderImages/9.png', 600),
(10, 3, 'Listo Para Servir', 'Fulano_03', './OrderImages/10.png', 1150),
(11, 3, 'Listo Para Servir', 'Fulano_04', './OrderImages/11.png', 1550),
(12, 2, 'Pendiente', 'Fulano_05', './OrderImages/Order_12.png', 0);

-- --------------------------------------------------------

--ENCUESTA

DROP TABLE IF EXISTS `encuesta`;
CREATE TABLE IF NOT EXISTS `encuesta` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pedido_id` int(11) NOT NULL,
  `puntuacion_mesa` int(11) NOT NULL,
  `puntuacion_restaurante` int(11) NOT NULL,
  `puntuacion_mozo` int(11) NOT NULL,
  `puntuacion_chef` int(11) NOT NULL,
  `puntuacionPromedio` float NOT NULL,
  `comentario` varchar(66) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fo_encuesta_pedido` (`pedido_id`) 
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;


INSERT INTO `encuesta` (`id`, `pedido_id`, `puntuacion_mesa`, `puntuacion_restaurante`, `puntuacion_mozo`, `puntuacion_chef`, `puntuacionPromedio`, `comentario`) VALUES
(1, 10, 8, 8, 10, 9, 8.75, 'La mesa parecia la de pepe argento, pero muy rico todo'),
(2, 11, 7, 8, 8, 4, 6.75, 'Perder mi perro fue muy duro pero no tanto como la milanesa de aca'),
(3, 9, 7, 8, 8, 10, 8.25, 'Tardo un poquito pero la comida de 10!');

-- --------------------------------------------------------

--MESA

DROP TABLE IF EXISTS `Mesas`;
CREATE TABLE IF NOT EXISTS `Mesas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigoDeMesa` varchar(5) COLLATE utf8_spanish2_ci NOT NULL,
  `idDeEmpleado` int(11) DEFAULT NULL,
  `estado` varchar(50) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `codigoDeMesa` (`codigoDeMesa`),
  KEY `FK_mesa_idDeEmpleado` (`idDeEmpleado`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci; 



INSERT INTO `Mesas` (`id`, `codigoDeMesa`, `idDeEmpleado`, `estado`) VALUES
(2, 'ME002', 20, 'Con Cliente Esperando Pedido'),
(3, 'ME003', 12, 'Con Cliente Pagando'),
(4, 'ME004', 20, 'Cerrada'),
(5, 'ME005', NULL, 'Cerrada'),
(6, 'ME006', NULL, 'Cerrada'),
(8, 'ME008', NULL, 'Cerrada'),
(9, 'ME009', NULL, 'Cerrada'),
(10, 'ME010', NULL, 'Cerrada'),
(11, 'ME011', NULL, 'Cerrada');

-- --------------------------------------------------------

--USUARIOS

DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombreUsuario` varchar(20) COLLATE utf8_spanish2_ci NOT NULL,
  `contrasena` text COLLATE utf8_spanish2_ci NOT NULL,
  `esAdmin` tinyint(1) NOT NULL,
  `tipoDeUsuario` varchar(20) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `estado` varchar(20) COLLATE utf8_spanish2_ci NOT NULL,
  `fechaDeInicio` datetime NOT NULL,
  `fechaDeFin` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombreUsuario` (`nombreUsuario`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

INSERT INTO `usuarios` (`id`, `nombreUsuario`, `contrasena`, `esAdmin`, `tipoDeUsuario`, `estado`, `fechaDeInicio`, `fechaDeFin`) VALUES
(10, 'Facu', '$2y$10$YC33xVvmADBUIczUmSShY.hkCB7pLh5ksH.COU7loi2Zd4V8h.0cy', 1, 'Admin', 'Active', '2021-11-27 00:32:31', NULL),
(15, 'C1', '$2y$10$3bG3uAu2AJ7VnMcM08IBcu3kLehg9t6YjWBAQ/j6FeLR7VgMzOWN2', 0, 'Camarera', 'Active', '2021-11-27 01:31:07', NULL),
(16, 'C2', '$2y$10$yT74CCOm7isBvu19UvpP.uXCyy3rTYqLw5mNH4AYb.uIzozX6hY3.', 0, 'Camarera', 'Active', '2021-11-27 01:31:15', NULL),
(17, 'C3', '$2y$10$izib3ooG60BV9VhfVWEpnuk67M3EzAz/yboSaGlq4tvFrjIKTFAl2', 0, 'Camarera', 'Active', '2021-11-27 01:31:19', NULL),
(18, 'Co1', '$2y$10$CnSjF.0SF2FHYhxXKzjrsuZbWZzS4CrQ.kin1GhgDpHmiDXv.kZQO', 0, 'Cocinero', 'Active', '2021-11-27 01:31:32', NULL),
(19, 'Co2', '$2y$10$QLr2gkRy4rB6rYkRT/lUye4WUv.iCkSr2Bm4gcDFrYF09R1MclHl.', 0, 'Cocinero', 'Active', '2021-11-27 01:31:41', NULL),
(20, 'Co3', '$2y$10$JxpnNeff2MzRNzrX/LfoRu7U/A8GzU7CEdrF3E8KCFyXHNccnLDo2', 0, 'Cocinero', 'Active', '2021-11-27 01:31:47', NULL),
(21, 'Bar1', '$2y$10$/jvBeHcBsJXiVqno25eAx.kePekvRQrqDjTOmY8Yd3w8rw3MCcXsq', 0, 'Barman', 'Active', '2021-11-27 01:32:01', NULL),
(22, 'Bar2', '$2y$10$M9DS08Vxs0MR2OdjL1OIxuYcDCOIOffGzdDk3AHS3cms6tGCGS9eO', 0, 'Barman', 'Active', '2021-11-27 01:32:05', NULL),
(30, 'Bar3', '$2y$10$6CYGTBDQ6a3migWWTYHqtuMWIIF7P4NHfOAddeaPvAliFHQUsWg2a', 0, 'Barman', 'Active', '2021-11-28 19:36:36', NULL);

ALTER TABLE `comida`
  ADD CONSTRAINT `FK_comida_pedido_asoc` FOREIGN KEY (`comidaPedidoAsociado`) REFERENCES `Pedidos` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION;

ALTER TABLE `Empleados`
  ADD CONSTRAINT `FK_empleado_area` FOREIGN KEY (`empleado_area_id`) REFERENCES `area` (`area_id`) ON DELETE SET NULL ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_empleado_usuario` FOREIGN KEY (`idDeUsuario`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

ALTER TABLE `HistorialIngresos`
  ADD CONSTRAINT `FK_ingreso_de_Usuario` FOREIGN KEY (`idDeUsuario`) REFERENCES `usuarios` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE `Pedidos`
  ADD CONSTRAINT `FK_mesa_pedidos` FOREIGN KEY (`mesa_id`) REFERENCES `Mesas` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION;

ALTER TABLE `encuesta`
  ADD CONSTRAINT `fo_encuesta_pedido` FOREIGN KEY (`pedido_id`) REFERENCES `Pedidos` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

ALTER TABLE `Mesas`
  ADD CONSTRAINT `FK_mesa_idDeEmpleado` FOREIGN KEY (`idDeEmpleado`) REFERENCES `Empleados` (`id`) ON DELETE SET NULL ON UPDATE SET NULL;
COMMIT; 
