CREATE DATABASE IF NOT EXISTS `store_web_dcake` DEFAULT CHARACTER SET latin1 COLLATE latin1_spanish_ci;
USE `store_web_dcake`;

CREATE TABLE `cliente` (
  `Correo electronico` varchar(30) NOT NULL,
  `Nombre` varchar(30) DEFAULT NULL,
  `Dirección` varchar(50) DEFAULT NULL,
  `Teléfono` int(12) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

CREATE TABLE `comentario` (
  `id_comentario` int(20) NOT NULL,
  `texto` text NOT NULL,
  `correo_electronico` varchar(30) DEFAULT NULL,
  `fecha_registro` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

INSERT INTO `comentario` (`id_comentario`, `texto`, `correo_electronico`, `fecha_registro`) VALUES
(70, 'nh', '', ''),
(71, 'Buen producto, excelete', '', '');

CREATE TABLE `producto` (
  `Codigo` int(20) NOT NULL,
  `Nombre` varchar(50) NOT NULL,
  `Precio` decimal(10,2) NOT NULL,
  `Descuento` tinyint(3) NOT NULL DEFAULT 0,
  `Descripción` text DEFAULT NULL,
  `id_categoria` int(11) NOT NULL,
  `Activo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

INSERT INTO `producto` (`Codigo`, `Nombre`, `Precio`, `Descuento`, `Descripción`, `id_categoria`, `Activo`) VALUES
(1, 'Bizcocho de naranja', 60000.00, 20, '<p> Bizcocho de Naranja, esponjoso con un rico sabor intenso a naranja.\r\n</p>\r\n', 1, 1),
(2, 'Bizcocho de banano', 40000.00, 0, 'Bizcocho de banano', 1, 1),
(4, 'Bizcocho de café', 70000.00, 0, 'Bizcocho de café', 1, 1),
(5, 'Bizcocho de chocolate', 60000.00, 0, 'Bizcocho de chocolate', 1, 1),
(6, 'Bizcocho de zanahoria', 65000.00, 0, 'Bizcocho de zanahoria', 1, 1),
(7, 'Bizcocho de fresa', 50000.00, 0, 'Bizcocho de fresa', 1, 1),
(9, 'Bizcocho de café con relleno de ganache de chocola', 80000.00, 0, 'Bizcocho de café con relleno de ganache de chocolate', 2, 1),
(10, 'Bizcocho de naranja con relleno de crema de limón', 65000.00, 0, 'Bizcocho de naranja con relleno de crema de limón', 2, 1),
(11, 'Bizcocho de guayaba', 55000.00, 0, 'Bizcocho de guayaba', 1, 1),
(22, 'Bizcocho de canela', 60000.00, 0, 'Bizcocho de canela, suave, esponjoso y humedo, con un sabor intenso a canela\r\n\r\nPorciones:\r\nMedia libra\r\n20 porciones', 1, 1),
(50, 'Bizcocho de chontaduro', 50000.00, 0, 'Bizcocho de chontaduro', 1, 1);

CREATE TABLE `vendedor` (
  `id_vendedor` int(11) NOT NULL,
  `nombre` varchar(30) NOT NULL,
  `usuario` varchar(30) NOT NULL,
  `contraseña` varchar(16) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

INSERT INTO `vendedor` (`id_vendedor`, `nombre`, `usuario`, `contraseña`) VALUES
(1, 'Daniel Hurtado Pino', 'Daniel26112003', '12345678');

ALTER TABLE `cliente`
  ADD PRIMARY KEY (`Correo electronico`);

ALTER TABLE `comentario`
  ADD PRIMARY KEY (`id_comentario`);

ALTER TABLE `producto`
  ADD PRIMARY KEY (`Codigo`);

ALTER TABLE `vendedor`
  ADD PRIMARY KEY (`id_vendedor`);

ALTER TABLE `comentario`
  MODIFY `id_comentario` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

ALTER TABLE `producto`
  MODIFY `Codigo` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=79;

ALTER TABLE `vendedor`
  MODIFY `id_vendedor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
