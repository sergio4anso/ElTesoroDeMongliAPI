-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 04-04-2023 a las 23:42:10
-- Versión del servidor: 10.4.27-MariaDB
-- Versión de PHP: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `eltesorodemongli`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `access_tokens`
--

CREATE TABLE `access_tokens` (
  `user_id` int(11) NOT NULL,
  `token` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `access_tokens`
--

INSERT INTO `access_tokens` (`user_id`, `token`) VALUES
(2, 'a5df42c11ada1214300be6eace059496a8b95fb557f2922026378dac4b70adf5');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nickname` varchar(255) NOT NULL,
  `mail` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `equipment` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `active` tinyint(1) NOT NULL DEFAULT 0,
  `last_login` timestamp NOT NULL DEFAULT current_timestamp(),
  `transform` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`transform`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `nickname`, `mail`, `password`, `equipment`, `created_at`, `active`, `last_login`, `transform`) VALUES
(2, 'Alejarkor', 'alelangaapa@gmail.com', '$2y$10$QnnJ./A3XIcoTgipJ6hor.67IKGRZ67YrpiFvXogVbg4xXHRBYsJK', NULL, '2023-03-31 18:38:29', 0, '2023-04-04 20:36:38', NULL),
(3, 'Skayamus', 'sergio4anso.ezcaray@gmail.com', '$2y$10$DO03fTwPFSJY1d5/eP/SLejGeZGYlHxi9VdKoN6fYnf4f.JnNv11i', NULL, '2023-03-31 18:47:02', 1, '2023-04-04 20:37:36', NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `access_tokens`
--
ALTER TABLE `access_tokens`
  ADD PRIMARY KEY (`user_id`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `mail` (`mail`),
  ADD UNIQUE KEY `nick` (`nickname`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
