-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 09-03-2026 a las 00:51:57
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
-- Base de datos: `biblioteca_db`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `autores`
--

CREATE TABLE `autores` (
  `id_autor` int(10) UNSIGNED NOT NULL,
  `nombres` varchar(150) NOT NULL,
  `apellidos` varchar(150) DEFAULT NULL,
  `nacionalidad` varchar(100) DEFAULT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `biografia` text DEFAULT NULL,
  `estado` enum('activo','inactivo') NOT NULL DEFAULT 'activo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `autores`
--

INSERT INTO `autores` (`id_autor`, `nombres`, `apellidos`, `nacionalidad`, `fecha_nacimiento`, `biografia`, `estado`) VALUES
(1, 'Gabriel', 'García Márquez', 'Colombiano', '1927-03-06', 'Premio Nobel de Literatura', 'activo'),
(2, 'Isabel', 'Allende', 'Chilena', '1942-08-02', 'Escritora chilena', 'activo'),
(3, 'Stephen', 'King', 'Estadounidense', '1947-09-21', 'Maestro del terror', 'activo'),
(4, 'J.K.', 'Rowling', 'Británica', '1965-07-31', 'Autora de Harry Potter', 'activo'),
(5, 'J.R.R. Tolkien', NULL, 'Sudafricano', '1983-01-03', NULL, 'activo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `id_categoria` int(10) UNSIGNED NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `estado` enum('activa','inactiva') NOT NULL DEFAULT 'activa',
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`id_categoria`, `nombre`, `descripcion`, `estado`, `fecha_creacion`) VALUES
(2, 'No Ficción', 'Libros de no ficción', 'activa', '2026-03-08 13:03:58'),
(3, 'Ciencia', 'Libros científicos', 'activa', '2026-03-08 13:03:58'),
(4, 'Historia', 'Libros de historia', 'activa', '2026-03-08 13:03:58'),
(5, 'Ficción', 'Libros de terror', 'activa', '2026-03-08 17:52:13'),
(6, 'Terror', 'Libros de terror', 'activa', '2026-03-08 17:55:32');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `descargas`
--

CREATE TABLE `descargas` (
  `id_descarga` int(10) UNSIGNED NOT NULL,
  `id_usuario` int(10) UNSIGNED NOT NULL,
  `id_libro` int(10) UNSIGNED NOT NULL,
  `fecha_descarga` timestamp NOT NULL DEFAULT current_timestamp(),
  `ip_usuario` varchar(45) DEFAULT NULL,
  `dispositivo` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `descargas`
--

INSERT INTO `descargas` (`id_descarga`, `id_usuario`, `id_libro`, `fecha_descarga`, `ip_usuario`, `dispositivo`) VALUES
(1, 4, 1, '2026-03-08 13:04:57', '192.168.1.1', 'Desktop');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `editorial`
--

CREATE TABLE `editorial` (
  `id_editorial` int(10) UNSIGNED NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `pais` varchar(100) DEFAULT NULL,
  `ciudad` varchar(100) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `sitio_web` varchar(200) DEFAULT NULL,
  `estado` enum('activa','inactiva') NOT NULL DEFAULT 'activa'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `editorial`
--

INSERT INTO `editorial` (`id_editorial`, `nombre`, `pais`, `ciudad`, `telefono`, `email`, `sitio_web`, `estado`) VALUES
(1, 'Editorial Planeta', 'España', 'Barcelona', '123456789', 'info@planeta.es', 'www.planeta.es', 'activa'),
(2, 'Penguin Random House', 'Estados Unidos', 'Nueva York', '987654321', 'info@penguin.com', 'www.penguin.com', 'activa'),
(3, 'Santillana', 'México', 'México DF', '555123456', 'info@santillana.com', 'www.santillana.com', 'activa'),
(4, 'HarperCollins', 'EE.UU', NULL, '+591 68028886', 'harpercollins@gmail.com', 'https://harpercollins.com', 'activa');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `favoritos`
--

CREATE TABLE `favoritos` (
  `id_favorito` int(10) UNSIGNED NOT NULL,
  `id_usuario` int(10) UNSIGNED NOT NULL,
  `id_libro` int(10) UNSIGNED NOT NULL,
  `fecha_agregado` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `favoritos`
--

INSERT INTO `favoritos` (`id_favorito`, `id_usuario`, `id_libro`, `fecha_agregado`) VALUES
(1, 7, 3, '2026-03-08 16:25:01'),
(2, 8, 3, '2026-03-08 17:07:27');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `libros`
--

CREATE TABLE `libros` (
  `id_libro` int(10) UNSIGNED NOT NULL,
  `id_editorial` int(10) UNSIGNED DEFAULT NULL,
  `titulo` varchar(200) NOT NULL,
  `isbn` varchar(20) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `anio_publicacion` year(4) DEFAULT NULL,
  `portada_url` varchar(255) DEFAULT NULL,
  `archivo_url` varchar(255) DEFAULT NULL,
  `cantidad_disponible` int(11) NOT NULL DEFAULT 0,
  `estado` enum('disponible','no_disponible') NOT NULL DEFAULT 'disponible',
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `libros`
--

INSERT INTO `libros` (`id_libro`, `id_editorial`, `titulo`, `isbn`, `descripcion`, `anio_publicacion`, `portada_url`, `archivo_url`, `cantidad_disponible`, `estado`, `fecha_registro`) VALUES
(1, 1, 'Cien años de soledad', '978-84-339-6153-5', 'Novela del realismo mágico', '1967', '/biblio/public/uploads/covers/cover_1772982350_2e22c92c.webp', '/uploads/cien-anos.pdf', 5, 'disponible', '2026-03-08 13:03:58'),
(2, 2, 'Harry Potter y la piedra filosofal', '978-84-9838-001-2', 'Primer libro de la saga Harry Potter', '1997', '/biblio/public/uploads/covers/cover_1772982333_17bf3f8d.jpg', '/uploads/harry-potter.pdf', 3, 'disponible', '2026-03-08 13:03:58'),
(3, 3, 'It', '978-84-450-7474-8', 'Novela de terror de Stephen King', '1986', '/biblio/public/uploads/covers/cover_1772982272_3fe1f0e8.webp', '/uploads/it.pdf', 2, 'disponible', '2026-03-08 13:03:58'),
(4, 1, 'La casa de los espíritus', '978-84-339-6154-2', 'Novela de Isabel Allende', '1982', '/biblio/public/uploads/covers/cover_1772982216_5aaa7975.jpg', '/uploads/casa-espiritus.pdf', 4, 'disponible', '2026-03-08 13:03:58'),
(5, 3, 'El señor de los Anillos', '9788845292613', 'El Señor de los Anillos es una novela de alta fantasía épica escrita por J.R.R. Tolkien, ambientada en la Tercera Edad de la Tierra Media. La trama narra el peligroso viaje de Frodo Bolsón para destruir el Anillo Único, creado por el señor oscuro Sauron, en los fuegos del Monte del Destino.', '1954', '/biblio/public/uploads/covers/cover_1772991324_e50d01a8.jpg', '/biblio/public/uploads/books/book_1772991324_f873edcb.pdf', 1, 'disponible', '2026-03-08 17:35:24');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `libro_autor`
--

CREATE TABLE `libro_autor` (
  `id_libro_autor` int(10) UNSIGNED NOT NULL,
  `id_libro` int(10) UNSIGNED NOT NULL,
  `id_autor` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `libro_autor`
--

INSERT INTO `libro_autor` (`id_libro_autor`, `id_libro`, `id_autor`) VALUES
(5, 4, 2),
(6, 3, 3),
(7, 2, 4),
(10, 1, 1),
(11, 5, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `libro_categoria`
--

CREATE TABLE `libro_categoria` (
  `id_libro_categoria` int(10) UNSIGNED NOT NULL,
  `id_libro` int(10) UNSIGNED NOT NULL,
  `id_categoria` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `libro_categoria`
--

INSERT INTO `libro_categoria` (`id_libro_categoria`, `id_libro`, `id_categoria`) VALUES
(14, 1, 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `logs`
--

CREATE TABLE `logs` (
  `id_log` int(10) UNSIGNED NOT NULL,
  `id_usuario` int(10) UNSIGNED DEFAULT NULL,
  `accion` varchar(150) NOT NULL,
  `tabla_afectada` varchar(100) NOT NULL,
  `id_registro_afectado` int(11) DEFAULT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp(),
  `ip` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `logs`
--

INSERT INTO `logs` (`id_log`, `id_usuario`, `accion`, `tabla_afectada`, `id_registro_afectado`, `fecha`, `ip`) VALUES
(1, 3, 'Login', 'usuarios', 3, '2026-03-08 13:04:57', '192.168.1.1'),
(2, 7, 'Registro de usuario', 'usuarios', 7, '2026-03-08 14:58:00', '::1'),
(3, 7, 'Inicio de sesión', 'usuarios', 7, '2026-03-08 14:58:06', '::1'),
(4, 7, 'Cierre de sesión', 'usuarios', 7, '2026-03-08 14:58:19', '::1'),
(5, 6, 'Inicio de sesión', 'usuarios', 6, '2026-03-08 15:02:23', '::1'),
(6, 6, 'Actualización de libro ID: 4', 'libros', 4, '2026-03-08 15:03:36', '::1'),
(7, 6, 'Actualización de libro ID: 3', 'libros', 3, '2026-03-08 15:04:32', '::1'),
(8, 6, 'Actualización de libro ID: 2', 'libros', 2, '2026-03-08 15:05:34', '::1'),
(9, 6, 'Actualización de libro ID: 1', 'libros', 1, '2026-03-08 15:05:50', '::1'),
(10, 6, 'Inicio de sesión', 'usuarios', 6, '2026-03-08 15:15:55', '::1'),
(11, 6, 'Inicio de sesión', 'usuarios', 6, '2026-03-08 15:18:23', '::1'),
(12, 6, 'Actualización de libro ID: 1', 'libros', 1, '2026-03-08 15:31:37', '::1'),
(13, 6, 'Inicio de sesión', 'usuarios', 6, '2026-03-08 15:35:35', '::1'),
(14, 6, 'Actualización de libro ID: 1', 'libros', 1, '2026-03-08 15:35:49', '::1'),
(15, 7, 'Inicio de sesión', 'usuarios', 7, '2026-03-08 16:21:57', '::1'),
(16, 7, 'Inicio de sesión', 'usuarios', 7, '2026-03-08 16:22:08', '::1'),
(17, 8, 'Registro de usuario', 'usuarios', 8, '2026-03-08 16:59:19', '::1'),
(18, 8, 'Inicio de sesión', 'usuarios', 8, '2026-03-08 17:01:43', '::1'),
(19, 8, 'Reseña creada para libro ID: 3', 'resenas', 7, '2026-03-08 17:05:18', '::1'),
(20, 8, 'Cierre de sesión', 'usuarios', 8, '2026-03-08 17:09:41', '::1'),
(21, 6, 'Inicio de sesión', 'usuarios', 6, '2026-03-08 17:11:45', '::1'),
(22, 6, 'Creación de libro: El señor de los Anillos', 'libros', 5, '2026-03-08 17:35:24', '::1'),
(23, 6, 'Inicio de sesión', 'usuarios', 6, '2026-03-08 17:46:48', '::1'),
(24, 6, 'CREAR_AUTOR: Autor creado ID: 5', 'autores', 5, '2026-03-08 17:47:18', '::1'),
(25, 6, 'ACTUALIZAR_AUTOR: Autor actualizado ID: 5', 'autores', 5, '2026-03-08 17:50:15', '::1'),
(26, 6, 'CREAR_CATEGORIA: Categoría creada ID: 5', 'categorias', 5, '2026-03-08 17:52:13', '::1'),
(27, 6, 'ELIMINAR_CATEGORIA: Categoría eliminada ID: 1', 'categorias', 1, '2026-03-08 17:54:47', '::1'),
(28, 6, 'CREAR_CATEGORIA: Categoría creada ID: 6', 'categorias', 6, '2026-03-08 17:55:32', '::1'),
(29, 6, 'ACTUALIZAR_CATEGORIA: Categoría actualizada ID: 6', 'categorias', 6, '2026-03-08 17:57:03', '::1'),
(30, 6, 'CREAR_EDITORIAL: Editorial creada ID: 4', 'editoriales', 4, '2026-03-08 18:02:14', '::1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2026_03_08_084302_create_personal_access_tokens_table', 1),
(2, '2026_03_08_084306_create_roles_table', 1),
(3, '2026_03_08_084312_create_usuarios_table', 1),
(4, '2026_03_08_084316_create_categorias_table', 1),
(5, '2026_03_08_084317_create_autores_table', 1),
(6, '2026_03_08_084317_create_editorial_table', 1),
(7, '2026_03_08_084318_create_libros_table', 1),
(8, '2026_03_08_084553_create_libro_autor_table', 1),
(9, '2026_03_08_084554_create_descargas_table', 1),
(10, '2026_03_08_084554_create_libro_categoria_table', 1),
(11, '2026_03_08_084556_create_logs_table', 1),
(12, '2026_03_08_084556_create_resenas_table', 1),
(13, '2026_03_08_084557_create_recomendaciones_table', 1),
(14, '2026_03_08_084999_create_password_resets_table', 2),
(15, '2026_03_08_085000_create_favoritos_table', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(150) NOT NULL,
  `token` varchar(255) NOT NULL,
  `expires_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` text NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `recomendaciones`
--

CREATE TABLE `recomendaciones` (
  `id_recomendacion` int(10) UNSIGNED NOT NULL,
  `id_libro` int(10) UNSIGNED NOT NULL,
  `detalle` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `recomendaciones`
--

INSERT INTO `recomendaciones` (`id_recomendacion`, `id_libro`, `detalle`) VALUES
(1, 1, 'Recomendado para amantes de la literatura latinoamericana');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `resenas`
--

CREATE TABLE `resenas` (
  `id_resena` int(10) UNSIGNED NOT NULL,
  `id_usuario` int(10) UNSIGNED NOT NULL,
  `id_libro` int(10) UNSIGNED NOT NULL,
  `calificacion` tinyint(3) UNSIGNED NOT NULL,
  `comentario` text DEFAULT NULL,
  `fecha_resena` timestamp NOT NULL DEFAULT current_timestamp(),
  `estado` enum('visible','oculta') NOT NULL DEFAULT 'visible'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `resenas`
--

INSERT INTO `resenas` (`id_resena`, `id_usuario`, `id_libro`, `calificacion`, `comentario`, `fecha_resena`, `estado`) VALUES
(5, 4, 1, 5, 'Una obra maestra del realismo mágico', '2026-03-08 13:04:57', 'visible'),
(6, 4, 2, 4, 'Excelente libro para todas las edades', '2026-03-08 13:04:57', 'visible'),
(7, 8, 3, 5, 'Excelente libro', '2026-03-08 17:05:18', 'visible');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id_rol` int(10) UNSIGNED NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id_rol`, `nombre`, `descripcion`, `fecha_creacion`) VALUES
(1, 'Administrador', 'Control total del sistema', '2026-03-08 09:03:54'),
(2, 'Usuario', 'Usuario regular del sistema', '2026-03-08 09:03:54');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(10) UNSIGNED NOT NULL,
  `id_rol` int(10) UNSIGNED NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellidoP` varchar(100) DEFAULT NULL,
  `apellidoM` varchar(100) DEFAULT NULL,
  `email` varchar(150) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `estado` enum('activo','inactivo') NOT NULL DEFAULT 'activo',
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `ultimo_acceso` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `id_rol`, `nombre`, `apellidoP`, `apellidoM`, `email`, `contrasena`, `telefono`, `estado`, `fecha_registro`, `ultimo_acceso`) VALUES
(3, 1, 'Admin', 'Principal', NULL, 'admin@bibliodigital.com', '$2y$10$BoIJocq1y8E6TPZ.9TugOuIKzt5dJyqJLOh9x4Y73gjYW79VJaJ4W', '123456789', 'activo', '2026-03-08 13:03:58', '2026-03-08 09:03:58'),
(4, 2, 'Juan', 'Pérez', 'García', 'juan@example.com', '$2y$10$7VAszpY/LhdwCh6OO3wQ6.bt1fN9rv2aUEXtkOMv6acK7V.EGkG8C', '987654321', 'activo', '2026-03-08 13:03:58', NULL),
(6, 1, 'Administrador', '', '', 'administrador@gmail.com', '$2y$10$gsq6lmSZWCPA.qF38UMan.KFBHnLQ7w3i/qT2AAa1J09fiuXdUU1u', '', 'activo', '2026-03-08 14:57:31', '2026-03-08 13:46:48'),
(7, 2, 'Jhosmar', '', '', 'jhosmar@gmail.com', '$2y$10$rvJwqAeKtM2YvYtS3dxu3Op6T3jImlSLW6EmIKG9TRHTcQeIvtAbW', '', 'activo', '2026-03-08 14:58:00', '2026-03-08 12:22:08'),
(8, 2, 'Prueba', '', '', 'prueba@gmail.com', '$2y$10$i1JABvJRxLtHORro0Eb4iO.AbuDgluDIfScPfwYbd7gvE0jyXE//m', '', 'activo', '2026-03-08 16:59:19', '2026-03-08 13:01:43');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `autores`
--
ALTER TABLE `autores`
  ADD PRIMARY KEY (`id_autor`);

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id_categoria`);

--
-- Indices de la tabla `descargas`
--
ALTER TABLE `descargas`
  ADD PRIMARY KEY (`id_descarga`),
  ADD KEY `descargas_id_usuario_foreign` (`id_usuario`),
  ADD KEY `descargas_id_libro_foreign` (`id_libro`);

--
-- Indices de la tabla `editorial`
--
ALTER TABLE `editorial`
  ADD PRIMARY KEY (`id_editorial`);

--
-- Indices de la tabla `favoritos`
--
ALTER TABLE `favoritos`
  ADD PRIMARY KEY (`id_favorito`),
  ADD UNIQUE KEY `favoritos_id_usuario_id_libro_unique` (`id_usuario`,`id_libro`),
  ADD KEY `favoritos_id_libro_foreign` (`id_libro`);

--
-- Indices de la tabla `libros`
--
ALTER TABLE `libros`
  ADD PRIMARY KEY (`id_libro`),
  ADD UNIQUE KEY `libros_isbn_unique` (`isbn`),
  ADD KEY `libros_id_editorial_foreign` (`id_editorial`);

--
-- Indices de la tabla `libro_autor`
--
ALTER TABLE `libro_autor`
  ADD PRIMARY KEY (`id_libro_autor`),
  ADD KEY `libro_autor_id_libro_foreign` (`id_libro`),
  ADD KEY `libro_autor_id_autor_foreign` (`id_autor`);

--
-- Indices de la tabla `libro_categoria`
--
ALTER TABLE `libro_categoria`
  ADD PRIMARY KEY (`id_libro_categoria`),
  ADD KEY `libro_categoria_id_libro_foreign` (`id_libro`),
  ADD KEY `libro_categoria_id_categoria_foreign` (`id_categoria`);

--
-- Indices de la tabla `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id_log`),
  ADD KEY `logs_id_usuario_foreign` (`id_usuario`);

--
-- Indices de la tabla `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indices de la tabla `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  ADD KEY `personal_access_tokens_expires_at_index` (`expires_at`);

--
-- Indices de la tabla `recomendaciones`
--
ALTER TABLE `recomendaciones`
  ADD PRIMARY KEY (`id_recomendacion`),
  ADD KEY `recomendaciones_id_libro_foreign` (`id_libro`);

--
-- Indices de la tabla `resenas`
--
ALTER TABLE `resenas`
  ADD PRIMARY KEY (`id_resena`),
  ADD KEY `resenas_id_usuario_foreign` (`id_usuario`),
  ADD KEY `resenas_id_libro_foreign` (`id_libro`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id_rol`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `usuarios_email_unique` (`email`),
  ADD KEY `usuarios_id_rol_foreign` (`id_rol`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `autores`
--
ALTER TABLE `autores`
  MODIFY `id_autor` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id_categoria` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `descargas`
--
ALTER TABLE `descargas`
  MODIFY `id_descarga` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `editorial`
--
ALTER TABLE `editorial`
  MODIFY `id_editorial` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `favoritos`
--
ALTER TABLE `favoritos`
  MODIFY `id_favorito` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `libros`
--
ALTER TABLE `libros`
  MODIFY `id_libro` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `libro_autor`
--
ALTER TABLE `libro_autor`
  MODIFY `id_libro_autor` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `libro_categoria`
--
ALTER TABLE `libro_categoria`
  MODIFY `id_libro_categoria` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `logs`
--
ALTER TABLE `logs`
  MODIFY `id_log` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `recomendaciones`
--
ALTER TABLE `recomendaciones`
  MODIFY `id_recomendacion` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `resenas`
--
ALTER TABLE `resenas`
  MODIFY `id_resena` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id_rol` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `descargas`
--
ALTER TABLE `descargas`
  ADD CONSTRAINT `descargas_id_libro_foreign` FOREIGN KEY (`id_libro`) REFERENCES `libros` (`id_libro`),
  ADD CONSTRAINT `descargas_id_usuario_foreign` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`);

--
-- Filtros para la tabla `favoritos`
--
ALTER TABLE `favoritos`
  ADD CONSTRAINT `favoritos_id_libro_foreign` FOREIGN KEY (`id_libro`) REFERENCES `libros` (`id_libro`) ON DELETE CASCADE,
  ADD CONSTRAINT `favoritos_id_usuario_foreign` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE;

--
-- Filtros para la tabla `libros`
--
ALTER TABLE `libros`
  ADD CONSTRAINT `libros_id_editorial_foreign` FOREIGN KEY (`id_editorial`) REFERENCES `editorial` (`id_editorial`);

--
-- Filtros para la tabla `libro_autor`
--
ALTER TABLE `libro_autor`
  ADD CONSTRAINT `libro_autor_id_autor_foreign` FOREIGN KEY (`id_autor`) REFERENCES `autores` (`id_autor`) ON DELETE CASCADE,
  ADD CONSTRAINT `libro_autor_id_libro_foreign` FOREIGN KEY (`id_libro`) REFERENCES `libros` (`id_libro`) ON DELETE CASCADE;

--
-- Filtros para la tabla `libro_categoria`
--
ALTER TABLE `libro_categoria`
  ADD CONSTRAINT `libro_categoria_id_categoria_foreign` FOREIGN KEY (`id_categoria`) REFERENCES `categorias` (`id_categoria`) ON DELETE CASCADE,
  ADD CONSTRAINT `libro_categoria_id_libro_foreign` FOREIGN KEY (`id_libro`) REFERENCES `libros` (`id_libro`) ON DELETE CASCADE;

--
-- Filtros para la tabla `logs`
--
ALTER TABLE `logs`
  ADD CONSTRAINT `logs_id_usuario_foreign` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`);

--
-- Filtros para la tabla `recomendaciones`
--
ALTER TABLE `recomendaciones`
  ADD CONSTRAINT `recomendaciones_id_libro_foreign` FOREIGN KEY (`id_libro`) REFERENCES `libros` (`id_libro`);

--
-- Filtros para la tabla `resenas`
--
ALTER TABLE `resenas`
  ADD CONSTRAINT `resenas_id_libro_foreign` FOREIGN KEY (`id_libro`) REFERENCES `libros` (`id_libro`),
  ADD CONSTRAINT `resenas_id_usuario_foreign` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`);

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_id_rol_foreign` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`id_rol`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
