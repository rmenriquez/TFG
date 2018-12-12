SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Base de datos: `tfg`
--
DROP DATABASE IF EXISTS `tfg`;
CREATE DATABASE IF NOT EXISTS `tfg` DEFAULT CHARACTER SET utf8 COLLATE utf8_spanish_ci;
USE `tfg`;

-- ----------------------
--
-- Estructura de tabla para la tabla `usuario`
--
DROP TABLE IF EXISTS `usuario`;
CREATE TABLE IF NOT EXISTS `usuario` (
`id_usuario` int(11) NOT NULL,
  `nombre` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `usuario` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `n_cli_bodas` int(3) NULL,
  `n_cli_bautizos` int(3) NULL,
  `n_cli_comuniones` int(3) NULL,
  `n_cli_otros` int(3) NULL,
  PRIMARY KEY (id_usuario)
);

-- ----------------------
--
-- Estructura de tabla para la tabla `comida`
--
DROP TABLE IF EXISTS `comida`;
CREATE TABLE IF NOT EXISTS `comida` (
  `id_comida` int(11) NOT NULL,
  `titulo` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `descripcion` text COLLATE utf8_spanish_ci NOT NULL,
  `imagen` varchar(255) NULL,
  `restaurante` int(11) NOT NULL,
  PRIMARY KEY (id_comida),
  FOREIGN KEY (restaurante) REFERENCES usuario(id_usuario) ON DELETE CASCADE
);

-- ----------------------
--
-- Estructura de tabla para la tabla `alergeno`
--
DROP TABLE IF EXISTS `alergeno`;
CREATE TABLE IF NOT EXISTS `alergeno` (
	`id_alergeno` int(11) NOT NULL,
	`nombre_alergeno` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
	PRIMARY KEY (id_alergeno)
);

-- ----------------------
--
-- Estructura de tabla para la tabla `comida_con_alergeno`
--
DROP TABLE IF EXISTS `comida_alergeno`;
CREATE TABLE IF NOT EXISTS `comida_alergeno` (
	id_comida int(11) NOT NULL,
	id_alergeno int(11) NOT NULL,
	PRIMARY KEY (id_comida, id_alergeno),
	FOREIGN KEY (id_comida) REFERENCES comida(id_comida) ON DELETE CASCADE,
	FOREIGN KEY (id_alergeno) REFERENCES alergeno(id_alergeno) ON DELETE CASCADE
);

-- ----------------------
--
-- Estructura de tabla para la tabla `personal`
--
DROP TABLE IF EXISTS `personal`;
CREATE TABLE IF NOT EXISTS `personal` (
	`id_personal` varchar(9) NOT NULL,
	`nombre` varchar(60) COLLATE utf8_spanish_ci NOT NULL,
	`apellidos` varchar(150) COLLATE utf8_spanish_ci NOT NULL,
	`fecha_nacimiento` date NULL,
	`restaurante` int(11) NOT NULL,
	PRIMARY KEY (id_personal),
	FOREIGN KEY (restaurante) REFERENCES usuario(id_usuario) ON DELETE CASCADE
);

-- ----------------------
--
-- Estructura de tabla para la tabla `evento`
--
DROP TABLE IF EXISTS `evento`;
CREATE TABLE IF NOT EXISTS `evento` (
	`id_evento` int(10) NOT NULL,
	`tipo` varchar(8) NOT NULL,
	`nombre` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
	`fecha` date NOT NULL,
	`num_invitados` int(3) NOT NULL,
	`num_ninos` int(3) NOT NULL,
	`mesa_dulce_propia` boolean NULL,
	`observaciones` text NOT NULL,
	`restaurante` int(11) NOT NULL,
	`telefono` int(13) NOT NULL,	
	PRIMARY KEY (id_evento),
	FOREIGN KEY (restaurante) REFERENCES usuario(id_usuario) ON DELETE CASCADE
);

-- ----------------------
--
-- Estructura de tabla para la tabla `personal_evento`
--
DROP TABLE IF EXISTS `personal_evento`;
CREATE TABLE IF NOT EXISTS `personal_evento` (
	`personal` varchar(9) NOT NULL,
	`evento` int(10) NOT NULL,
	PRIMARY KEY (personal, evento),
	FOREIGN KEY (personal) REFERENCES personal(id_personal) ON DELETE CASCADE,
	FOREIGN KEY (evento) REFERENCES evento(id_evento) ON DELETE CASCADE
);


-- ----------------------
--
-- Estructura de tabla para la tabla `comida_evento`
--
DROP TABLE IF EXISTS `comida_evento`;
CREATE TABLE IF NOT EXISTS `comida_evento` (
	`comida` int(11) NOT NULL,
	`evento` int(10) NOT NULL,
	`pinzar` boolean NOT NULL,
	PRIMARY KEY (comida, evento),
	FOREIGN KEY (comida) REFERENCES comida(id_comida) ON DELETE CASCADE,
	FOREIGN KEY (evento) REFERENCES evento(id_evento) ON DELETE CASCADE
);



--
-- Volcado de datos para la tabla `usuario`
-- Por cada 20 personas, atienden n_cli_X camareros 
INSERT INTO `usuario` (`id_usuario`, `nombre`, `usuario`, `password`, `n_cli_bodas`, `n_cli_bautizos`, `n_cli_comuniones`, `n_cli_otros`) VALUES
(1, 'Raquel', 'Novaiño', 'novaiño', 1, 1, 1, 1),
(2, 'Sandra', 'PazoMonterrey', 'monterrey', 2, 1, 1, 1),
(3, 'Fabio', 'Galileo', 'galileo', 3, 2, 2, 2);

--
-- Volcado de datos para la tabla `comida`
--
INSERT INTO `comida` (`id_comida`, `titulo`, `descripcion`, `imagen`, `restaurante`) VALUES
(1, 'Huevos fritos', '- Huevos de gallina\r\n- Aceite\r\n', 'https://www.iglesiaenaragon.com/wp-content/uploads/2018/02/huevo_frito_con_puntillas.jpg', 1),
(2, 'Salpicón de marisco', '- Buey de mar o Centolla\r\n- Langostinos\r\n- Huevo\r\n- Cebolleta\r\n- Pimiento verde italiano \r\n- Pimiento rojo \r\n- Aceite de oliva virgen extra \r\n- Vinagre', 'http://estoyhechouncocinillas.com/wp-content/uploads/2017/10/salpicon-de-pescado-y-marisco.jpg', 1),
(3, 'Ravioli formaggi','Pasta fresca del día rellena de queso formaggi, adornada con nueces y perejil', 'https://www.gustissimo.it/articoli/ricette/gnocchi/ravioli-con-salsa-di-formaggio-e-noci.jpg', 3);

--
-- Volcado de datos para la tabla `alergeno`
--
INSERT INTO `alergeno` (`id_alergeno`,`nombre_alergeno`) VALUES
(1, 'Gluten'),
(2, 'Crustáceos y productos a base de crustáceos'),
(3, 'Huevos y productos a base de huevos'),
(4, 'Pescados y productos a base de pescados'),
(5, 'Cacahuetes y productos a base de cacahuetes'),
(6, 'Soja y productos a base de soja'),
(7, 'Leche y sus derivados'),
(8, 'Frutos de cáscara'),
(9, 'Apio y productos derivados'),
(10, 'Mostaza y productos derivados'),
(11, 'Granos de sésamo y productos a base de granos de sésamo'),
(12, 'Dióxido de azufre y sulfitos'),
(13, 'Altramuces y productos a base de altramuces'),
(14, 'Moluscos y productos a base de moluscos');

--
-- Volcado de datos para la tabla `comida_alergeno`
--
INSERT INTO `comida_alergeno` (`id_comida`, `id_alergeno`) VALUES
(1, 3),
(2, 2),
(2, 4),
(2, 14),
(3, 3),
(3, 7),
(3, 8);

--
-- Volcado de datos para la tabla `personal`
--
INSERT INTO `personal` (`id_personal`,`nombre`,`apellidos`,`fecha_nacimiento`,`restaurante`) VALUES
('44489093D','Raquel','Marcos Enríquez','1992-02-20',1),
('44489094D','Javier','Martinez Suarez','1981-10-06',1),
('44489095D','Hugo','Marquez Da Silva','1981-10-09',1),
('44489096D','Luis','Álvarez Pérez','1965-12-23',2),
('44489097D','Omar','Sánchez Rois',' 1988-08-06',2),
('44489098D','María','Jiménez Ortega','1975-04-04',2),
('44489099D','Laura','González González','1995-03-19',2),
('44489100D','Pablo','López Oubiña','1990-11-12',2),
('44489101D','Elsa','Cid Álvarez','1994-12-06',3),
('44489102D','Miguel','Crespo Peiteado','1991-07.15',3),
('44489103D','Joaquina','Moranda Fernández','1977-05-09',3);

--
-- Volcado de datos para la tabla `evento`
--
INSERT INTO `evento` (`id_evento`,`tipo`,`nombre`,`fecha`,`num_invitados`,`num_ninos`,`mesa_dulce_propia`,`observaciones`, `telefono`, `restaurante`) VALUES
(1,'Boda','Raúl y Sofía','2019-06-15',110,10,true,'Boda de tarde, previsión de llegada al restaurante a las 8 de la tarde.','678889764',1),
(2,'Bautizo','Juan','2019-06-16',90,3,false,'Hay un alérgico a la lactosa, dos al marisco y uno al gluten. Preguntar a cada uno qué quieren.','654673421',1),
(3,'Comunión','Ainara','2019-07-27',53,15,true,'Hora prevista de llegada: 14:00.','655643101',1),
(4,'Otro','Cena de empresa - Mesidana','2018-02-11',26,0,false,'Quieren barra libre con reservas, 3 horas máximo desde el café.','988345444',2),
(5,'Boda','Estefanía y Hugo','2019-05-25',35,7,true,'Boda de mañana. Hora prevista de llegada: 16:00. \r\n Los novios brindarán a la llegada con vino rosado.','677453400',2),
(6,'Boda','Paco y Miguel','2019-08-14',88,7,false,'Boda de mañana. Por favor, llamar al autobús a las 01:30.','680009989',3);

--
-- Volcado de datos para la tabla `personal_evento`
--
INSERT INTO `personal_evento`(`personal`,`evento`) VALUES 
('44489093D',1),
('44489094D',1),
('44489095D',1),
('44489096D',1),
('44489097D',2),
('44489098D',2),
('44489100D',4),
('44489101D',5),
('44489102D',5),
('44489102D',6),
('44489103D',6);

--
-- Volcado de datos para la tabla `comida_evento`
--
INSERT INTO `comida_evento` (`comida`,`evento`,`pinzar`) VALUES
(2,1,false),
(3,1,true),
(2,2,false),
(3,2,true),
(3,6,false);