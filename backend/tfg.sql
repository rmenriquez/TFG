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
-- Estructura de tabla para la tabla `user`
--
DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
`id_user` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `user` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `n_cli_wedding` int(3) NULL,
  `n_cli_christening` int(3) NULL,
  `n_cli_communion` int(3) NULL,
  `n_cli_others` int(3) NULL,
  PRIMARY KEY (id_user)
);

-- ----------------------
--
-- Estructura de tabla para la tabla `food`
--
DROP TABLE IF EXISTS `food`;
CREATE TABLE IF NOT EXISTS `food` (
  `id_food` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `description` text COLLATE utf8_spanish_ci NOT NULL,
  `image` varchar(255) NULL,
  `restaurant` int(11) NOT NULL,
  `price` DECIMAL (4,2) NULL,
  PRIMARY KEY (id_food),
  FOREIGN KEY (restaurant) REFERENCES user(id_user) ON DELETE CASCADE
);

-- ----------------------
--
-- Estructura de tabla para la tabla `allergen`
--
DROP TABLE IF EXISTS `allergen`;
CREATE TABLE IF NOT EXISTS `allergen` (
	`id_allergen` int(11) NOT NULL,
	`name_allergen` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
	PRIMARY KEY (id_allergen)
);

-- ----------------------
--
-- Estructura de tabla para la tabla `food_allergen`
--
DROP TABLE IF EXISTS `food_allergen`;
CREATE TABLE IF NOT EXISTS `food_allergen` (
	id_food int(11) NOT NULL,
	id_allergen int(11) NOT NULL,
	PRIMARY KEY (id_food, id_allergen),
	FOREIGN KEY (id_food) REFERENCES food(id_food) ON DELETE CASCADE,
	FOREIGN KEY (id_allergen) REFERENCES allergen(id_allergen) ON DELETE CASCADE
);

-- ----------------------
--
-- Estructura de tabla para la tabla `staff`
--
DROP TABLE IF EXISTS `staff`;
CREATE TABLE IF NOT EXISTS `staff` (
	`id_staff` varchar(9) NOT NULL,
	`name` varchar(60) COLLATE utf8_spanish_ci NOT NULL,
	`surnames` varchar(150) COLLATE utf8_spanish_ci NOT NULL,
	`birthdate` date NULL,
	`email` varchar(150) NOT NULL,
	`restaurant` int(11) NOT NULL,
	PRIMARY KEY (id_staff),
	FOREIGN KEY (restaurant) REFERENCES user(id_user) ON DELETE CASCADE
);

-- ----------------------
--
-- Estructura de tabla para la tabla `event`
--
DROP TABLE IF EXISTS `event`;
CREATE TABLE IF NOT EXISTS `event` (
	`id_event` int(10) NOT NULL AUTO_INCREMENT,
	`type` varchar(8) NOT NULL,
	`name` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
	`date` date NOT NULL,
	`guests` int(3) NOT NULL,
	`children` int(3) NOT NULL,
	`sweet_table` boolean NULL,
	`observations` text NOT NULL,
	`restaurant` int(11) NOT NULL,
	`phone` int(13) NOT NULL,
  `price` DECIMAL (4,2) NULL,
	PRIMARY KEY (id_event),
	FOREIGN KEY (restaurant) REFERENCES user(id_user) ON DELETE CASCADE
);

-- ----------------------
--
-- Estructura de tabla para la tabla `staff_event`
--
DROP TABLE IF EXISTS `staff_event`;
CREATE TABLE IF NOT EXISTS `staff_event` (
	`staff` varchar(9) NOT NULL,
	`event` int(10) NOT NULL,
	PRIMARY KEY (staff, event),
	FOREIGN KEY (staff) REFERENCES staff(id_staff) ON DELETE CASCADE,
	FOREIGN KEY (event) REFERENCES event(id_event) ON DELETE CASCADE
);


-- ----------------------
--
-- Estructura de tabla para la tabla `food_event`
--
DROP TABLE IF EXISTS `food_event`;
CREATE TABLE IF NOT EXISTS `food_event` (
	`food` int(11) NOT NULL,
	`event` int(10) NOT NULL,
	`clamp` boolean NOT NULL,
	PRIMARY KEY (food, event),
	FOREIGN KEY (food) REFERENCES food(id_food) ON DELETE CASCADE,
	FOREIGN KEY (event) REFERENCES event(id_event) ON DELETE CASCADE
);



--
-- Volcado de datos para la tabla `user`
-- Por cada 20 personas, atienden n_cli_X camareros 
INSERT INTO `user` (`id_user`, `name`, `user`, `password`, `n_cli_wedding`, `n_cli_christening`, `n_cli_communion`, `n_cli_others`) VALUES
(1, 'Raquel', 'Novaiño', 'novaiño', 1, 1, 1, 1),
(2, 'Sandra', 'PazoMonterrey', 'monterrey', 2, 1, 1, 1),
(3, 'Fabio', 'Galileo', 'galileo', 3, 2, 2, 2);

--
-- Volcado de datos para la tabla `food`
--
INSERT INTO `food` (`id_food`, `title`, `description`, `image`, `restaurant`, `price`) VALUES
(1, 'Huevos fritos', '- Huevos de gallina\r\n- Aceite\r\n', 'https://www.iglesiaenaragon.com/wp-content/uploads/2018/02/huevo_frito_con_puntillas.jpg', 1, 3.50),
(2, 'Salpicón de marisco', '- Buey de mar o Centolla\r\n- Langostinos\r\n- Huevo\r\n- Cebolleta\r\n- Pimiento verde italiano \r\n- Pimiento rojo \r\n- Aceite de oliva virgen extra \r\n- Vinagre', 'http://estoyhechouncocinillas.com/wp-content/uploads/2017/10/salpicon-de-pescado-y-marisco.jpg', 1, 5.50),
(3, 'Ravioli formaggi','Pasta fresca del día rellena de queso formaggi, adornada con nueces y perejil', 'https://www.gustissimo.it/articoli/ricette/gnocchi/ravioli-con-salsa-di-formaggio-e-noci.jpg', 3, 4.25);

--
-- Volcado de datos para la tabla `allergen`
--
INSERT INTO `allergen` (`id_allergen`,`name_allergen`) VALUES
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
-- Volcado de datos para la tabla `food_allergen`
--
INSERT INTO `food_allergen` (`id_food`, `id_allergen`) VALUES
(1, 3),
(2, 2),
(2, 4),
(2, 14),
(3, 3),
(3, 7),
(3, 8);

--
-- Volcado de datos para la tabla `staff`
--
INSERT INTO `staff` (`id_staff`,`name`,`surnames`,`email`, `birthdate`,`restaurant`) VALUES
('44489093D','Raquel','Marcos Enríquez','b14raquel@gmail.com','1992-02-20',1),
('44489094D','Javier','Martinez Suarez','b14raquel@gmail.com','1981-10-06',1),
('44489095D','Hugo','Marquez Da Silva','b14raquel@gmail.com','1981-10-09',1),
('44489096D','Luis','Álvarez Pérez','b14raquel@gmail.com','1965-12-23',2),
('44489097D','Omar','Sánchez Rois','b14raquel@gmail.com',' 1988-08-06',2),
('44489098D','María','Jiménez Ortega','b14raquel@gmail.com','1975-04-04',2),
('44489099D','Laura','González González','b14raquel@gmail.com','1995-03-19',2),
('44489100D','Pablo','López Oubiña','b14raquel@gmail.com','1990-11-12',2),
('44489101D','Elsa','Cid Álvarez','b14raquel@gmail.com','1994-12-06',3),
('44489102D','Miguel','Crespo Peiteado','b14raquel@gmail.com','1991-07.15',3),
('44489103D','Joaquina','Moranda Fernández','b14raquel@gmail.com','1977-05-09',3);

--
-- Volcado de datos para la tabla `event`
--
INSERT INTO `event` (`id_event`,`type`,`name`,`date`,`guests`,`children`,`sweet_table`,`observations`, `phone`, `restaurant`) VALUES
(1,'Boda','Raúl y Sofía','2019-06-15',110,10,true,'Boda de tarde, previsión de llegada al restaurant a las 8 de la tarde.','678889764',1),
(2,'Bautizo','Juan','2019-06-16',90,3,false,'Hay un alérgico a la lactosa, dos al marisco y uno al gluten. Preguntar a cada uno qué quieren.','654673421',1),
(3,'Comunión','Ainara','2019-07-27',53,15,true,'Hora prevista de llegada: 14:00.','655643101',1),
(4,'Otro','Cena de empresa - Mesidana','2018-02-11',26,0,false,'Quieren barra libre con reservas, 3 horas máximo desde el café.','988345444',2),
(5,'Boda','Estefanía y Hugo','2019-05-25',35,7,true,'Boda de mañana. Hora prevista de llegada: 16:00. \r\n Los novios brindarán a la llegada con vino rosado.','677453400',2),
(6,'Boda','Paco y Miguel','2019-08-14',88,7,false,'Boda de mañana. Por favor, llamar al autobús a las 01:30.','680009989',3);

--
-- Volcado de datos para la tabla `staff_event`
--
INSERT INTO `staff_event`(`staff`,`event`) VALUES
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
-- Volcado de datos para la tabla `food_event`
--
INSERT INTO `food_event` (`food`,`event`,`clamp`) VALUES
(2,1,false),
(3,1,true),
(2,2,false),
(3,2,true),
(3,6,false);