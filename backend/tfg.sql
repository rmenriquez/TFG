-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 23-08-2019 a las 10:26:47
-- Versión del servidor: 5.7.23
-- Versión de PHP: 7.2.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Base de datos: `tfg`
--
CREATE DATABASE IF NOT EXISTS `tfg` DEFAULT CHARACTER SET utf8 COLLATE utf8_spanish_ci;
USE `tfg`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `allergen`
--

CREATE TABLE `allergen` (
  `id_allergen` int(11) NOT NULL,
  `name_allergen` varchar(100) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `allergen`
--

INSERT INTO `allergen` (`id_allergen`, `name_allergen`) VALUES
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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `event`
--

CREATE TABLE `event` (
  `id_event` int(10) NOT NULL,
  `type` varchar(8) COLLATE utf8_spanish_ci NOT NULL,
  `name` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `moment` varchar(9) COLLATE utf8_spanish_ci NOT NULL,
  `date` date NOT NULL,
  `guests` int(3) NOT NULL,
  `children` int(3) NOT NULL,
  `sweet_table` tinyint(1) DEFAULT NULL,
  `observations` text COLLATE utf8_spanish_ci NOT NULL,
  `restaurant` int(11) NOT NULL,
  `phone` int(13) NOT NULL,
  `price` decimal(8,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `event`
--

INSERT INTO `event` (`id_event`, `type`, `name`, `moment`, `date`, `guests`, `children`, `sweet_table`, `observations`, `restaurant`, `phone`, `price`) VALUES
(1, 'Bautizo', 'Mario Suarez', 'Mañana', '2019-06-08', 179, 5, 1, 'Bautizo con menú de celíacos para los niños. 3 adultos intolerantes a la lactosa.', 1, 678888888, NULL),
(3, 'Comunión', 'Ainara', 'Mañana', '2019-07-27', 53, 15, 1, 'Hora prevista de llegada: 14:00.', 1, 677889900, NULL),
(4, 'Otros', 'Cena de empresa - Mesidana', 'Noche', '2018-02-11', 26, 0, 1, 'Quieren barra libre con reservas, 3 horas máximo desde el café.', 2, 678779900, NULL),
(5, 'Boda', 'Estefanía y Hugo', 'Noche', '2019-05-25', 35, 7, 1, 'Boda de mañana. Hora prevista de llegada: 16:00. \r\n Los novios brindarán a la llegada con vino rosado.', 2, 654332211, NULL),
(6, 'Boda', 'Paco y Miguel', 'Mañana', '2019-08-14', 88, 7, 0, 'Boda de mañana. Por favor, llamar al autobús a las 01:30.', 3, 612334455, NULL),
(8, 'Bautizo', 'Martina Peiteado', 'Mañana', '2019-08-05', 45, 9, 1, 'El bautizo es en Ourense a las 12.', 2, 666778899, '0.00'),
(9, 'Comunion', 'Hugo', 'Mañana', '2019-12-01', 12, 3, 1, 'Ceremonia en Sandias', 2, 678765654, '1.00'),
(21, 'Bautizo', 'Miguelez', 'Mañana', '2019-07-21', 12, 2, 0, 'dfsodfiouoeiuriwn', 1, 677556644, '2.00'),
(31, 'Boda', 'M&m', 'Mañana', '2019-07-28', 45, 5, 0, 'dfgdfgdfg', 1, 6773345, '5.00'),
(32, 'Comunión', 'vare', 'Mañana', '2019-07-28', 45, 3, 1, 'trtvyty', 1, 654112212, '4.00'),
(33, 'Boda', 'Juan y maria', 'Mañana', '2019-07-31', 34, 4, 0, 'dfgertert', 1, 987564321, '0.00'),
(34, 'Comunión', 'Juanillo', 'Mañana', '2019-07-28', 34, 4, 0, 'yjkyunyu', 1, 912345678, '9.00'),
(35, 'Boda', 'juanita', 'Mañana', '2019-07-28', 56, 6, 0, 'fghfghfgh', 1, 988789878, '9.00'),
(36, 'Comunión', 'Marcs', 'Mañana', '2019-07-29', 45, 4, 0, 'dfsdwerw', 1, 675446655, '0.00'),
(37, 'Comunión', 'Raquel', 'Mañana', '2019-07-27', 45, 3, 1, 'Pasjndaiuqnw', 1, 988877655, '4.00'),
(38, 'Bautizo', 'Tiara', 'Mañana', '2019-07-14', 45, 6, 1, 'dfsdfsdf', 1, 678887766, '4.00'),
(39, 'Comunión', 'Hugo', 'Mañana', '2019-07-28', 23, 3, 0, 'sdfwer', 1, 986543212, '3.00'),
(40, 'Bautizo', 'Juan', 'Mañana', '2019-07-14', 56, 6, 0, 'kfjwoeiruwoiejr', 1, 908654321, '5.00'),
(41, 'Boda', 'Pepe y pepa', 'Mañana', '2019-08-22', 67, 9, 1, 'dsfsdfkjoiwjeruwe', 1, 976543200, '6.00'),
(42, 'Bautizo', 'Luis', 'Mañana', '2019-07-28', 45, 2, 0, 'Llegarán a las 3 al restaurante.', 50, 981234321, '5.00'),
(43, 'Comunión', 'Martina', 'Mañana', '2019-10-17', 56, 3, 1, 'jksdauhduiqwheqjkn', 50, 677667766, '4.00'),
(44, 'Comunión', 'hdfsuydfg', 'Mañana', '2019-10-16', 45, 5, 0, 'kjsfhiuhdsuihf', 50, 988787766, '3.00'),
(45, 'Otros', 'Comida en casa', 'Mañana', '2019-07-23', 3, 0, 0, 'BlaBlaBla', 50, 688990089, '4.00'),
(48, 'Bautizo', 'Daniela', 'Mañana', '2019-09-23', 33, 2, 0, 'sdkfjwoiej', 50, 985234566, '0.00'),
(49, 'Bautizo', 'Pepe', 'Mañana', '2020-09-09', 55, 5, 1, 'dskjflksdjfkj', 50, 982786699, '0.00'),
(50, 'Comunión', 'Mario', 'Mañana', '2019-09-09', 90, 9, 0, 'spdifusdifu', 50, 981900900, '9.00'),
(51, 'Otros', 'Prueba nocturna', '', '2019-12-31', 34, 2, 0, 'sdkfjsdifwoej', 50, 983009898, '0.00'),
(52, 'Otros', 'Comida Familia Perez', 'Mañana', '2019-09-19', 34, 0, 0, 'Llegarán al restaurante a las 14.30.', 50, 986213454, '0.00'),
(53, 'Otros', 'Comida Familia Marcos ', 'Mañana', '2019-07-26', 45, 2, 0, 'Llegarán a las 3 al restaurante', 50, 982213455, '0.00'),
(54, 'Boda', 'Martina y Hugo', 'Noche', '2019-08-30', 65, 2, 1, 'LLEgarán al restaurante a las 8 de la tarde.', 50, 985345433, '0.00'),
(55, 'Otros', 'Comida Familiar Prieto', 'Mañana', '2019-08-24', 20, 1, 0, 'De postre traen ellos tarta.', 50, 980787777, '0.00'),
(56, 'Otros', 'Comida Familia Peiteado', 'Mañana', '2019-09-20', 40, 3, 0, 'Pasjdnqwuqi', 50, 988887766, '0.00'),
(58, 'Otros', 'Prueba tres', 'Noche', '2020-04-25', 23, 1, 0, 'sdfhwuiefnjk', 50, 988990099, '0.00'),
(59, 'Bautizo', 'Valeria', 'Mañana', '2019-08-23', 23, 3, 0, 'Blalalala', 50, 687654533, '0.00'),
(61, 'Boda', 'Perico y Paloma', 'Noche', '2019-12-31', 90, 9, 1, 'sdlnuwnwie', 50, 688787878, '0.00'),
(62, 'Comunión', 'Cansadito Perez', 'Mañana', '2019-07-29', 45, 2, 1, 'sdkjnfbwjebfwjk', 50, 688776655, '0.00'),
(66, 'Bautizo', 'Jimeno', 'mañana', '2019-09-29', 90, 9, 0, 'skdhfbywebf', 50, 688765431, '0.00'),
(67, 'Otros', 'Sera el definitivo?', 'Noche', '2019-12-31', 90, 7, 0, 'sdkjfnuwen', 50, 688777777, '0.00'),
(71, 'Otros', 'William', 'Mañana', '2020-12-01', 34, 2, 0, 'kdsfjsdkfjk', 50, 677656666, '0.00'),
(74, 'Bautizo', 'Rebeca', 'Mañana', '2019-09-07', 70, 0, 0, 'jhiuyiuy', 50, 688887766, '0.00'),
(75, 'Boda', 'Amantes de teruel', 'Noche', '2020-01-01', 8, 1, 0, 'sdkfjsldkfj', 50, 699876787, '0.00'),
(77, 'Comunión', 'Maria Jose', 'Mañana', '2019-08-10', 45, 2, 0, 'dfsjdkfslkj', 50, 688888888, '0.00'),
(78, 'Otros', 'Celebracion TFG', 'Noche', '2019-09-11', 20, 0, 0, 'sdfjsdkflj', 50, 688765432, '345.60'),
(79, 'Comunión', 'Laura Pérez', 'Mañana', '2019-09-18', 78, 3, 0, 'Llegarán a las 3 de la tarde al restaurante.\nLos niños son celíacos\nQuieren merienda para los niños a las 7 de la tarde en el jardín debajo del carballo', 1, 691213432, '8871.26'),
(80, 'Boda', 'Estivaliz y Mario', 'Noche', '2019-10-10', 90, 3, 1, 'Llegarán a las 7 de la tarde al restaurante.\nQuieren foguetes al empezar el baile.\nJack Daniels para la barra libre.', 1, 900234555, '5015.80'),
(81, 'Comunión', 'Martina ', 'Mañana', '2020-06-20', 34, 5, 1, 'Tendrán sólo 2 horas de aperitivos.\nViene Xoaniña animación y ponen ellos la merienda.', 1, 988009988, '3087.78'),
(82, 'Otros', 'Comida Vecinos Curros ', 'Mañana', '2019-08-24', 45, 0, 0, 'Blablabla', 1, 989765554, '4090.15'),
(83, 'Otros', 'Cena de empresa Propo', 'Noche', '2019-08-31', 31, 0, 0, 'Blablabla', 1, 987765432, '3309.95'),
(84, 'Otros', 'BLABLAABLAAA', 'Mañana', '2019-08-24', 45, 4, 0, 'asdjaiusdj', 1, 987765433, '4305.25'),
(85, 'Otros', 'Pruebas 2', 'Mañana', '2019-08-31', 21, 0, 0, 'sdkfnsdifuih', 1, 986765432, '1694.45'),
(86, 'Otros', 'Prueba 3', 'Mañana', '2019-08-23', 45, 0, 0, 'Blablabla', 1, 984765432, '3927.65'),
(87, 'Otros', 'Prueba 4', 'Mañana', '2019-08-10', 20, 0, 0, 'adjfhsuih', 1, 983990088, '1578.40'),
(88, 'Otros', 'Prueba f1', 'Mañana', '2019-08-24', 90, 0, 0, 'sdhjfsuidh', 1, 982765432, '7085.30'),
(89, 'Otros', 'Prueba f2', 'Mañana', '2019-08-25', 30, 0, 0, 'skfjdwoij', 1, 981098765, '2225.00'),
(90, 'Otros', 'Prueba f3', 'Mañana', '2019-08-24', 6, 0, 0, 'dskfsoij', 1, 918789988, '236.00'),
(91, 'Otros', 'Prueba f4', 'Mañana', '2020-01-01', 12, 0, 0, 'jdfhsjdfkh', 1, 9178789098, '1098.04'),
(92, 'Otros', 'Prueba f5', 'Mañana', '2019-08-24', 21, 0, 0, 'sdjfhskdhf', 1, 918554433, '1564.00'),
(93, 'Otros', 'Prueba f6', 'Mañana', '2019-08-24', 34, 0, 0, 'tyutyu', 1, 918212433, '2526.00'),
(94, 'Otros', 'Prueba f7', 'Noche', '2019-08-25', 23, 0, 0, 'sjdfhks', 1, 918786756, '1002.91'),
(95, 'Otros', 'Prueba f8', 'Mañana', '2019-08-22', 10, 0, 0, 'smdsfnkdjh', 1, 918776633, '796.70'),
(96, 'Otros', 'Pruba f9', 'Mañana', '2019-08-23', 10, 0, 0, 'sdjfhsk', 1, 918765432, '909.50'),
(97, 'Otros', 'Prueba f10', 'Mañana', '2019-08-29', 10, 0, 0, 'jsdhfsduiy', 1, 918765411, '641.70'),
(98, 'Otros', 'Comida Familiar Prieto', 'Mañana', '2019-12-21', 15, 0, 0, 'sdjfhskjdfhskjh', 51, 991214567, '62.50');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `food`
--

CREATE TABLE `food` (
  `id_food` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `description` text COLLATE utf8_spanish_ci NOT NULL,
  `image` varchar(255) COLLATE utf8_spanish_ci DEFAULT NULL,
  `restaurant` int(11) NOT NULL,
  `price` decimal(4,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `food`
--

INSERT INTO `food` (`id_food`, `title`, `description`, `image`, `restaurant`, `price`) VALUES
(1, 'Huevos fritos', '- Huevos de gallina\r\n- Aceite\r\n', 'https://www.iglesiaenaragon.com/wp-content/uploads/2018/02/huevo_frito_con_puntillas.jpg', 1, '3.50'),
(2, 'Salpicón de marisco', '- Buey de mar o Centolla\r\n- Langostinos\r\n- Huevo\r\n- Cebolleta\r\n- Pimiento verde italiano \r\n- Pimiento rojo \r\n- Aceite de oliva virgen extra \r\n- Vinagre', 'http://estoyhechouncocinillas.com/wp-content/uploads/2017/10/salpicon-de-pescado-y-marisco.jpg', 1, '5.50'),
(3, 'Ravioli formaggi', 'Pasta fresca del día rellena de queso formaggi, adornada con nueces y perejil', 'https://www.gustissimo.it/articoli/ricette/gnocchi/ravioli-con-salsa-di-formaggio-e-noci.jpg', 3, '4.25'),
(4, 'Chipirones podres', 'Chipirones de la ria que estaban frescos pero ya no', 'una foto de chipis', 1, '35.00'),
(5, 'Tortilla de la mala', 'tortilla que ni huevo lleva', 'una tortilla de papel', 1, '3.45'),
(7, 'Merluza en salsa verde', 'Merluza del Atlantico con guisantes podres', 'Una foto cualquiera', 1, '35.50'),
(8, 'Espagueti en salsa verde', 'Espagueti con merluza del Atlantico con guisantes podres', 'Una foto cualquiera', 1, '40.50'),
(12, 'Cremas FRESCas', 'Crema de espárragos y calabacín', 'sdfksldkfjsdi', 42, '50.20'),
(13, 'Churros con merinas', 'Postre de churros', 'skdjfslk', 42, '12.00'),
(37, 'Hamburguesa', 'dfsjdkj', 'sdkfjsklj', 42, '12.50'),
(38, 'Bocadillo', 'bdfgdoip', 'idfugi', 42, '0.00'),
(39, 'Sandwich', 'skfdjslkdj', 'lksjfgklsjfg', 42, '11.45'),
(43, 'Salpicón de marisco', 'Salpicón de marisco sobre piña con banderitas', '', 2, '3.50'),
(44, 'Patatas fritas', 'Patatas de la limia fritas con aceite de olive virgen extra', '', 2, '1.50'),
(45, 'Lentejas', 'Lentejas españolas con tomate, chorizo y patatas', '', 2, '12.00'),
(46, 'Mejillones tigre', 'Mejillones de la ría con cebolla y pimiento gratinados con pan rallado', '', 2, '5.50'),
(47, 'Solomillo de cerdo con menestra', 'Solomillo de cerdo ibérico con menestra de verduras. Puede contener huevo', '', 2, '25.00'),
(48, 'Pure', 'de pattaas', '', 2, '12.00'),
(49, 'Crema de verduras', 'Patata, nata, puerro, calabacín', '', 2, '11.00'),
(50, 'Crema de puerro', 'Patata, nata, puerro, calabacín', '', 2, '11.00'),
(51, 'Tortilla francesa con champiñones', 'Tortilla de huevos de corral con champiñones de casa', '', 2, '3.50'),
(52, 'Pechugas de pollo con patatas', 'sdfjsdkfjkj', '', 2, '4.00'),
(53, 'Pollo asado', 'pollo de corral', '', 2, '9.00'),
(54, 'Salsa de champo', 'sjkdfhsjh', '', 2, '2.00'),
(58, 'Merluza cocida', 'sjdaksjdjk', '', 2, '3.00'),
(64, 'Una comida cualquiera', 'Comida que te quita el hambre', 'blablabla', 1, '4.67'),
(68, 'Centollo cocido', 'centollo de la ria', '', 2, '12.00'),
(69, 'Chuletas de pavo', 'chuletas de pavo adobadas con ajo y perejil', '', 2, '1.50'),
(91, 'Hola que tal', 'dsjfhsdkjfh', '', 1, '1.00'),
(94, 'patatas fritas', 'hvjhgvjh', 'sdfsdfsd', 1, '12.00'),
(97, 'Pollo asado', 'pollo de corral', 'sdjnfweui', 1, '45.00'),
(98, 'Patatas fritas', 'Patatas frescas de la limia', 'sjdnfjsndfjsndfjksndjn', 50, '7.78'),
(99, 'Pollo a la voltereta', 'Pollo asado con ajo y limón', 'skdjfsidjfioj', 50, '9.00'),
(100, 'Sopa de marisco', 'Sopa de cangrejo, buey, centollas, congrio y merluza.\nContiene pimiento y tomate', 'jsdnfsjdnfn', 50, '6.00'),
(101, 'Tarta romana', 'Tarta de distintas frutas, hojaldre y crema', 'sdjfsjdfsjdhf', 50, '98.00'),
(102, 'Lentejas', 'Lentejas españolas con chorizo, tomate, patatas, apio, zanahorias.', 'sdjfsdlk', 50, '3.00'),
(103, 'Comida 1 para evento 1', 'Blablabla', 'skdfsiejwiejo', 50, '2.00'),
(104, 'Comida 2 para evento 1', 'Blablablax2', 'kdmfgdfkg', 50, '3.00'),
(105, 'Comida 3 para evento 1', 'Blablablax3', 'loopoper', 50, '4.00'),
(106, 'Comida 4 para evento 1', 'Blablablax4', 'sdkfnjiow', 50, '5.00'),
(107, 'Chipirones riiicos', 'sjdfhskjdhf', 'lsdfsdjflj', 50, '4.00'),
(108, 'Crema de champiñones', 'Crema de champiñones frescos, patata y nata', 'skfsdhfuiweh', 50, '2.00'),
(109, 'Churros con chocolate', 'Churros hechos a mano con chocolate chaparro', 'sdfjskldfjklsjf', 50, '1.00'),
(110, 'Pizza carbonara', 'Pizza basica', NULL, 1, '23.00'),
(111, 'Espagueti', 'Pasta fresca', NULL, 1, '12.00'),
(112, 'Pasta al pesto', 'Pasta fresca congelada con salsa de Pesto hecha cada día, con piñones y aceite de oliva', NULL, 51, '3.50');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `food_allergen`
--

CREATE TABLE `food_allergen` (
  `id_food` int(11) NOT NULL,
  `id_allergen` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `food_allergen`
--

INSERT INTO `food_allergen` (`id_food`, `id_allergen`) VALUES
(2, 1),
(4, 1),
(7, 1),
(91, 1),
(97, 1),
(101, 1),
(102, 1),
(109, 1),
(112, 1),
(2, 2),
(4, 2),
(7, 2),
(100, 2),
(102, 2),
(107, 2),
(108, 2),
(1, 3),
(3, 3),
(4, 3),
(7, 3),
(100, 3),
(101, 3),
(102, 3),
(109, 3),
(112, 3),
(2, 4),
(100, 4),
(107, 4),
(91, 5),
(98, 5),
(101, 5),
(102, 5),
(109, 5),
(112, 5),
(2, 6),
(99, 6),
(102, 6),
(3, 7),
(97, 7),
(101, 7),
(102, 7),
(108, 7),
(109, 7),
(3, 8),
(98, 8),
(99, 8),
(102, 8),
(2, 9),
(91, 9),
(98, 9),
(102, 9),
(98, 10),
(101, 10),
(2, 11),
(91, 11),
(97, 11),
(98, 11),
(102, 11),
(102, 12),
(2, 13),
(99, 13),
(2, 14),
(91, 14),
(100, 14),
(102, 14);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `food_event`
--

CREATE TABLE `food_event` (
  `food` int(11) NOT NULL,
  `event` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `food_event`
--

INSERT INTO `food_event` (`food`, `event`) VALUES
(2, 1),
(3, 1),
(3, 6),
(1, 31),
(2, 31),
(4, 31),
(1, 32),
(4, 32),
(8, 32),
(1, 33),
(4, 33),
(8, 33),
(1, 34),
(4, 34),
(1, 35),
(4, 35),
(1, 36),
(4, 36),
(1, 37),
(4, 37),
(1, 38),
(2, 38),
(4, 38),
(1, 39),
(4, 39),
(1, 40),
(4, 40),
(1, 41),
(4, 41),
(8, 41),
(98, 42),
(101, 42),
(103, 42),
(104, 42),
(106, 42),
(103, 43),
(104, 43),
(105, 43),
(107, 43),
(98, 44),
(100, 44),
(102, 44),
(98, 45),
(100, 45),
(102, 45),
(103, 50),
(104, 50),
(105, 50),
(98, 51),
(99, 51),
(100, 51),
(98, 52),
(99, 52),
(101, 52),
(103, 52),
(98, 53),
(100, 53),
(102, 53),
(104, 53),
(99, 54),
(101, 54),
(103, 54),
(105, 54),
(98, 55),
(100, 55),
(102, 55),
(98, 56),
(100, 56),
(102, 56),
(98, 58),
(99, 58),
(100, 58),
(101, 58),
(102, 58),
(103, 58),
(98, 59),
(100, 59),
(102, 59),
(104, 59),
(98, 61),
(100, 61),
(102, 61),
(102, 62),
(104, 62),
(106, 62),
(108, 62),
(98, 66),
(100, 66),
(102, 66),
(98, 67),
(100, 67),
(102, 67),
(104, 67),
(98, 71),
(100, 71),
(102, 71),
(98, 74),
(100, 74),
(102, 74),
(98, 75),
(100, 75),
(102, 75),
(98, 78),
(100, 78),
(102, 78),
(1, 79),
(4, 79),
(7, 79),
(64, 79),
(94, 79),
(110, 79),
(5, 80),
(7, 80),
(64, 80),
(111, 80),
(1, 81),
(4, 81),
(7, 81),
(64, 81),
(94, 81),
(1, 82),
(4, 82),
(7, 82),
(64, 82),
(111, 82),
(2, 83),
(5, 83),
(8, 83),
(97, 83),
(111, 83),
(2, 84),
(5, 84),
(8, 84),
(91, 84),
(97, 84),
(5, 85),
(7, 85),
(8, 85),
(91, 85),
(4, 86),
(7, 86),
(64, 86),
(94, 86),
(1, 87),
(4, 87),
(7, 87),
(64, 87),
(1, 88),
(4, 88),
(7, 88),
(64, 88),
(1, 89),
(4, 89),
(7, 89),
(1, 90),
(4, 90),
(1, 91),
(4, 91),
(7, 91),
(64, 91),
(94, 91),
(1, 92),
(4, 92),
(7, 92),
(1, 93),
(4, 93),
(7, 93),
(1, 94),
(4, 94),
(64, 94),
(1, 95),
(4, 95),
(7, 95),
(64, 95),
(5, 96),
(8, 96),
(91, 96),
(97, 96),
(7, 97),
(64, 97),
(110, 97),
(112, 98);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `staff`
--

CREATE TABLE `staff` (
  `id_staff` varchar(9) COLLATE utf8_spanish_ci NOT NULL,
  `name` varchar(60) COLLATE utf8_spanish_ci NOT NULL,
  `surnames` varchar(150) COLLATE utf8_spanish_ci NOT NULL,
  `birthdate` date DEFAULT NULL,
  `email` varchar(150) COLLATE utf8_spanish_ci NOT NULL,
  `restaurant` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `staff`
--

INSERT INTO `staff` (`id_staff`, `name`, `surnames`, `birthdate`, `email`, `restaurant`) VALUES
('33323234F', 'William', 'Turner Turnito 2', '2001-07-03', 'b14raquel@gmail.com', 2),
('33345434R', 'Paco', 'Paquito', '2001-06-03', 'b14raquel@gmail.com', 2),
('34944819E', 'Pily', 'Enriques', '1963-09-19', 'b14raquel@gmail.com', 50),
('34944819F', 'Juan', 'Perez', '2001-05-18', 'r.enriquez@campus.fct.unl.pt', 50),
('34944819P', 'María', 'Jimenez De los Santos', '1992-02-21', 'r.enriquez@campus.fct.unl.pt', 42),
('43566765R', 'Pedro', 'Marcos', '1960-12-21', 'r.enriquez@campus.fct.unl.pt', 50),
('44456543G', 'Maria de La O', 'Perez Suarez Sanchez', '1960-09-19', 'b14raquel@gmail.com', 42),
('44456543T', 'Patricia', 'MIAU MIAU', '1992-02-21', 'r.enriquez@campus.fct.unl.pt', 42),
('44456789T', 'Hugo', 'Gimenes Da Silva', '1979-12-01', 'raquelmarcosenr@gmail.com', 42),
('44489093D', 'Helena', 'Gonzalez González', '1992-02-21', 'rmenriquez@esei.uvigo.es', 1),
('44489093D', 'Raquel', 'Marcos', '1992-02-20', 'rmenriquez@esei.uvigo.es', 51),
('44489096D', 'Patricia', 'Álvarez Muñoz', '1992-06-14', 'r.enriquez@campus.fct.unl.pt', 2),
('44489099D', 'Raquel', 'Marcos', '1992-02-20', 'rmenriquez@esei.uvigo.es', 50),
('44489102D', 'José', 'Garcia Garcia', '1992-02-21', 'r.enriquez@campus.fct.unl.pt', 3),
('44489103D', 'Ivan', 'Guau Miau', '1992-02-21', 'r.enriquez@campus.fct.unl.pt', 3),
('54489093F', 'Patricia', 'Guau MIAU', '1992-02-21', 'r.enriquez@campus.fct.unl.pt', 1),
('71412875Z', 'Pedro', 'Marcos', '1960-12-21', 'rmenriquez@esei.uvigo.es', 50),
('71412875Z', 'Pedro', 'Marcos', '1960-12-21', 'rmenriquez@esei.uvigo.es', 51),
('77767898U', 'Sergio', 'Estevez', '1989-09-02', 'b14raquel@gmail.com', 50),
('87765654F', 'Maria de la Paz', 'Suares', '1979-12-01', 'rakel_markos92@hotmail.com', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `staff_event`
--

CREATE TABLE `staff_event` (
  `staff` varchar(9) COLLATE utf8_spanish_ci NOT NULL,
  `event` int(10) NOT NULL,
  `invited` int(1) NOT NULL,
  `rejected` int(1) DEFAULT NULL,
  `confirmed` int(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `staff_event`
--

INSERT INTO `staff_event` (`staff`, `event`, `invited`, `rejected`, `confirmed`) VALUES
('34944819E', 42, 1, NULL, NULL),
('34944819E', 43, 0, 0, NULL),
('34944819E', 50, 1, NULL, NULL),
('34944819E', 66, 1, NULL, NULL),
('34944819E', 67, 1, NULL, NULL),
('34944819E', 71, 1, NULL, NULL),
('34944819E', 74, 1, NULL, NULL),
('34944819E', 75, 1, NULL, NULL),
('34944819E', 78, 1, NULL, NULL),
('34944819F', 42, 1, NULL, NULL),
('34944819F', 66, 1, NULL, NULL),
('34944819F', 67, 1, NULL, NULL),
('34944819F', 71, 1, NULL, NULL),
('34944819F', 74, 1, NULL, NULL),
('34944819F', 75, 1, NULL, NULL),
('34944819F', 78, 1, NULL, NULL),
('43566765R', 43, 0, 0, NULL),
('44489093D', 1, 0, 0, NULL),
('44489093D', 35, 0, 0, NULL),
('44489093D', 36, 0, 0, NULL),
('44489093D', 37, 0, 0, NULL),
('44489093D', 39, 0, 0, NULL),
('44489093D', 40, 0, 0, NULL),
('44489093D', 41, 0, 0, NULL),
('44489093D', 79, 1, NULL, NULL),
('44489093D', 80, 1, NULL, NULL),
('44489093D', 81, 1, NULL, NULL),
('44489093D', 82, 1, NULL, NULL),
('44489093D', 83, 1, NULL, NULL),
('44489093D', 84, 1, NULL, NULL),
('44489093D', 85, 1, NULL, NULL),
('44489093D', 86, 1, NULL, NULL),
('44489093D', 87, 1, NULL, NULL),
('44489093D', 88, 1, NULL, NULL),
('44489093D', 89, 1, NULL, NULL),
('44489093D', 91, 1, NULL, NULL),
('44489093D', 92, 1, NULL, NULL),
('44489093D', 93, 1, NULL, NULL),
('44489093D', 94, 1, NULL, NULL),
('44489093D', 95, 1, NULL, NULL),
('44489093D', 96, 1, NULL, NULL),
('44489093D', 97, 1, NULL, NULL),
('44489093D', 98, 1, NULL, NULL),
('44489096D', 1, 0, 0, NULL),
('44489099D', 42, 1, NULL, NULL),
('44489099D', 45, 0, 0, NULL),
('44489099D', 51, 1, NULL, NULL),
('44489099D', 66, 1, NULL, NULL),
('44489099D', 67, 1, NULL, NULL),
('44489102D', 5, 0, 0, NULL),
('44489102D', 6, 0, 0, NULL),
('44489103D', 6, 0, 0, NULL),
('54489093F', 36, 0, 0, NULL),
('54489093F', 37, 0, 0, NULL),
('54489093F', 38, 0, 0, NULL),
('54489093F', 39, 0, 0, NULL),
('54489093F', 40, 0, 0, NULL),
('54489093F', 41, 0, 0, NULL),
('54489093F', 80, 1, NULL, NULL),
('54489093F', 82, 1, NULL, NULL),
('54489093F', 83, 1, NULL, NULL),
('54489093F', 84, 1, NULL, NULL),
('71412875Z', 98, 1, NULL, NULL),
('77767898U', 42, 1, NULL, NULL),
('77767898U', 43, 0, 0, NULL),
('77767898U', 66, 1, NULL, NULL),
('77767898U', 67, 1, NULL, NULL),
('87765654F', 90, 1, NULL, NULL),
('87765654F', 91, 1, NULL, NULL),
('87765654F', 92, 1, NULL, NULL),
('87765654F', 93, 1, NULL, NULL),
('87765654F', 94, 1, NULL, NULL),
('87765654F', 95, 1, NULL, NULL),
('87765654F', 96, 1, NULL, NULL),
('87765654F', 97, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user`
--

CREATE TABLE `user` (
  `id_user` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `user` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `n_cli_wedding` int(3) DEFAULT NULL,
  `n_cli_christening` int(3) DEFAULT NULL,
  `n_cli_communion` int(3) DEFAULT NULL,
  `n_cli_others` int(3) DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `user`
-- User: Novaino Pass: novaino
-- User: PazoMonterrey Pass: monterrey
-- User: Galileo Pass: galileo
-- User: SuriTheCat Pass: surielmejor
-- User: LagunasPower2 Pass: prueba
-- User: OLar Pass: prueba1
--

INSERT INTO `user` (`id_user`, `name`, `user`, `password`, `n_cli_wedding`, `n_cli_christening`, `n_cli_communion`, `n_cli_others`, `email`) VALUES
(1, 'Raquel', 'Novaino', '67228247c6e01c18f7b25506c0792aec', 20, 25, 20, 25, 'b14raquel@gmail.com'),
(2, 'Sandra', 'PazoMonterrey', '4e9eaa94e20007ec2e3a4cbba15ff974', 25, 25, 30, 20, ''),
(3, 'Fabio', 'Galileo', '638190bf025179ecebcc1b3d019a0230', 15, 18, 20, 20, 'b14raquel@gmail.com'),
(42, 'Suri', 'SuriTheCat', 'c893bad68927b457dbed39460e6afd62', 30, 31, 32, 33, 'b14raquel@gmail.com'),
(50, 'Prueba2', 'LagunasPower2', 'c893bad68927b457dbed39460e6afd62', 20, 20, 25, 17, 'rmenriquez@esei.uvigo.es'),
(51, 'Pilar', 'OLar', '3f1b7ccad63d40a7b4c27dda225bf941', 12, 10, 10, 10, 'b14raquel@gmail.com');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `allergen`
--
ALTER TABLE `allergen`
  ADD PRIMARY KEY (`id_allergen`);

--
-- Indices de la tabla `event`
--
ALTER TABLE `event`
  ADD PRIMARY KEY (`id_event`),
  ADD KEY `restaurant` (`restaurant`);

--
-- Indices de la tabla `food`
--
ALTER TABLE `food`
  ADD PRIMARY KEY (`id_food`),
  ADD KEY `restaurant` (`restaurant`);

--
-- Indices de la tabla `food_allergen`
--
ALTER TABLE `food_allergen`
  ADD PRIMARY KEY (`id_food`,`id_allergen`),
  ADD KEY `id_allergen` (`id_allergen`);

--
-- Indices de la tabla `food_event`
--
ALTER TABLE `food_event`
  ADD PRIMARY KEY (`food`,`event`),
  ADD KEY `event` (`event`);

--
-- Indices de la tabla `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`id_staff`,`restaurant`),
  ADD KEY `restaurant` (`restaurant`);

--
-- Indices de la tabla `staff_event`
--
ALTER TABLE `staff_event`
  ADD PRIMARY KEY (`staff`,`event`),
  ADD KEY `event` (`event`);

--
-- Indices de la tabla `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `event`
--
ALTER TABLE `event`
  MODIFY `id_event` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=99;

--
-- AUTO_INCREMENT de la tabla `food`
--
ALTER TABLE `food`
  MODIFY `id_food` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=113;

--
-- AUTO_INCREMENT de la tabla `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `event`
--
ALTER TABLE `event`
  ADD CONSTRAINT `event_ibfk_1` FOREIGN KEY (`restaurant`) REFERENCES `user` (`id_user`) ON DELETE CASCADE;

--
-- Filtros para la tabla `food`
--
ALTER TABLE `food`
  ADD CONSTRAINT `food_ibfk_1` FOREIGN KEY (`restaurant`) REFERENCES `user` (`id_user`) ON DELETE CASCADE;

--
-- Filtros para la tabla `food_allergen`
--
ALTER TABLE `food_allergen`
  ADD CONSTRAINT `food_allergen_ibfk_1` FOREIGN KEY (`id_food`) REFERENCES `food` (`id_food`) ON DELETE CASCADE,
  ADD CONSTRAINT `food_allergen_ibfk_2` FOREIGN KEY (`id_allergen`) REFERENCES `allergen` (`id_allergen`) ON DELETE CASCADE;

--
-- Filtros para la tabla `food_event`
--
ALTER TABLE `food_event`
  ADD CONSTRAINT `food_event_ibfk_1` FOREIGN KEY (`food`) REFERENCES `food` (`id_food`) ON DELETE CASCADE,
  ADD CONSTRAINT `food_event_ibfk_2` FOREIGN KEY (`event`) REFERENCES `event` (`id_event`) ON DELETE CASCADE;

--
-- Filtros para la tabla `staff`
--
ALTER TABLE `staff`
  ADD CONSTRAINT `staff_ibfk_1` FOREIGN KEY (`restaurant`) REFERENCES `user` (`id_user`) ON DELETE CASCADE;

--
-- Filtros para la tabla `staff_event`
--
ALTER TABLE `staff_event`
  ADD CONSTRAINT `staff_event_ibfk_1` FOREIGN KEY (`staff`) REFERENCES `staff` (`id_staff`) ON DELETE CASCADE,
  ADD CONSTRAINT `staff_event_ibfk_2` FOREIGN KEY (`event`) REFERENCES `event` (`id_event`) ON DELETE CASCADE;
