-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: biblioteca_db
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `autores`
--

DROP TABLE IF EXISTS `autores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `autores` (
  `id_autor` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nombres` varchar(150) NOT NULL,
  `apellidos` varchar(150) DEFAULT NULL,
  `nacionalidad` varchar(100) DEFAULT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `biografia` text DEFAULT NULL,
  `estado` enum('activo','inactivo') NOT NULL DEFAULT 'activo',
  PRIMARY KEY (`id_autor`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `autores`
--

LOCK TABLES `autores` WRITE;
/*!40000 ALTER TABLE `autores` DISABLE KEYS */;
INSERT INTO `autores` VALUES (1,'Gabriel','García Márquez','Colombiano','1927-03-06','Premio Nobel de Literatura','activo'),(2,'Isabel','Allende','Chilena','1942-08-02','Escritora chilena','activo'),(3,'Stephen','King','Estadounidense','1947-09-21','Maestro del terror','activo'),(4,'J.K.','Rowling','Británica','1965-07-31','Autora de Harry Potter','activo'),(5,'J.R.R. Tolkien',NULL,'Sudafricano','1983-01-03',NULL,'activo');
/*!40000 ALTER TABLE `autores` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categorias`
--

DROP TABLE IF EXISTS `categorias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categorias` (
  `id_categoria` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `estado` enum('activa','inactiva') NOT NULL DEFAULT 'activa',
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_categoria`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categorias`
--

LOCK TABLES `categorias` WRITE;
/*!40000 ALTER TABLE `categorias` DISABLE KEYS */;
INSERT INTO `categorias` VALUES (2,'No Ficción','Libros de no ficción','activa','2026-03-08 13:03:58'),(3,'Ciencia','Libros científicos','activa','2026-03-08 13:03:58'),(4,'Historia','Libros de historia','activa','2026-03-08 13:03:58'),(5,'Ficción','Libros de terror','activa','2026-03-08 17:52:13'),(6,'Terror','Libros de terror','activa','2026-03-08 17:55:32');
/*!40000 ALTER TABLE `categorias` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `descargas`
--

DROP TABLE IF EXISTS `descargas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `descargas` (
  `id_descarga` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_usuario` int(10) unsigned NOT NULL,
  `id_libro` int(10) unsigned NOT NULL,
  `fecha_descarga` timestamp NOT NULL DEFAULT current_timestamp(),
  `ip_usuario` varchar(45) DEFAULT NULL,
  `dispositivo` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_descarga`),
  KEY `descargas_id_usuario_foreign` (`id_usuario`),
  KEY `descargas_id_libro_foreign` (`id_libro`),
  CONSTRAINT `descargas_id_libro_foreign` FOREIGN KEY (`id_libro`) REFERENCES `libros` (`id_libro`),
  CONSTRAINT `descargas_id_usuario_foreign` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `descargas`
--

LOCK TABLES `descargas` WRITE;
/*!40000 ALTER TABLE `descargas` DISABLE KEYS */;
INSERT INTO `descargas` VALUES (1,4,1,'2026-03-08 13:04:57','192.168.1.1','Desktop');
/*!40000 ALTER TABLE `descargas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `editorial`
--

DROP TABLE IF EXISTS `editorial`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `editorial` (
  `id_editorial` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(150) NOT NULL,
  `pais` varchar(100) DEFAULT NULL,
  `ciudad` varchar(100) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `sitio_web` varchar(200) DEFAULT NULL,
  `estado` enum('activa','inactiva') NOT NULL DEFAULT 'activa',
  PRIMARY KEY (`id_editorial`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `editorial`
--

LOCK TABLES `editorial` WRITE;
/*!40000 ALTER TABLE `editorial` DISABLE KEYS */;
INSERT INTO `editorial` VALUES (1,'Editorial Planeta','España','Barcelona','123456789','info@planeta.es','www.planeta.es','activa'),(2,'Penguin Random House','Estados Unidos','Nueva York','987654321','info@penguin.com','www.penguin.com','activa'),(3,'Santillana','México','México DF','555123456','info@santillana.com','www.santillana.com','activa'),(4,'HarperCollins','EE.UU',NULL,'+591 68028886','harpercollins@gmail.com','https://harpercollins.com','activa');
/*!40000 ALTER TABLE `editorial` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `favoritos`
--

DROP TABLE IF EXISTS `favoritos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `favoritos` (
  `id_favorito` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_usuario` int(10) unsigned NOT NULL,
  `id_libro` int(10) unsigned NOT NULL,
  `fecha_agregado` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_favorito`),
  UNIQUE KEY `favoritos_id_usuario_id_libro_unique` (`id_usuario`,`id_libro`),
  KEY `favoritos_id_libro_foreign` (`id_libro`),
  CONSTRAINT `favoritos_id_libro_foreign` FOREIGN KEY (`id_libro`) REFERENCES `libros` (`id_libro`) ON DELETE CASCADE,
  CONSTRAINT `favoritos_id_usuario_foreign` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `favoritos`
--

LOCK TABLES `favoritos` WRITE;
/*!40000 ALTER TABLE `favoritos` DISABLE KEYS */;
INSERT INTO `favoritos` VALUES (1,7,3,'2026-03-08 16:25:01'),(2,8,3,'2026-03-08 17:07:27');
/*!40000 ALTER TABLE `favoritos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `libro_autor`
--

DROP TABLE IF EXISTS `libro_autor`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `libro_autor` (
  `id_libro_autor` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_libro` int(10) unsigned NOT NULL,
  `id_autor` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id_libro_autor`),
  KEY `libro_autor_id_libro_foreign` (`id_libro`),
  KEY `libro_autor_id_autor_foreign` (`id_autor`),
  CONSTRAINT `libro_autor_id_autor_foreign` FOREIGN KEY (`id_autor`) REFERENCES `autores` (`id_autor`) ON DELETE CASCADE,
  CONSTRAINT `libro_autor_id_libro_foreign` FOREIGN KEY (`id_libro`) REFERENCES `libros` (`id_libro`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `libro_autor`
--

LOCK TABLES `libro_autor` WRITE;
/*!40000 ALTER TABLE `libro_autor` DISABLE KEYS */;
INSERT INTO `libro_autor` VALUES (5,4,2),(6,3,3),(7,2,4),(10,1,1),(11,5,1);
/*!40000 ALTER TABLE `libro_autor` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `libro_categoria`
--

DROP TABLE IF EXISTS `libro_categoria`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `libro_categoria` (
  `id_libro_categoria` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_libro` int(10) unsigned NOT NULL,
  `id_categoria` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id_libro_categoria`),
  KEY `libro_categoria_id_libro_foreign` (`id_libro`),
  KEY `libro_categoria_id_categoria_foreign` (`id_categoria`),
  CONSTRAINT `libro_categoria_id_categoria_foreign` FOREIGN KEY (`id_categoria`) REFERENCES `categorias` (`id_categoria`) ON DELETE CASCADE,
  CONSTRAINT `libro_categoria_id_libro_foreign` FOREIGN KEY (`id_libro`) REFERENCES `libros` (`id_libro`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `libro_categoria`
--

LOCK TABLES `libro_categoria` WRITE;
/*!40000 ALTER TABLE `libro_categoria` DISABLE KEYS */;
INSERT INTO `libro_categoria` VALUES (14,1,4);
/*!40000 ALTER TABLE `libro_categoria` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `libros`
--

DROP TABLE IF EXISTS `libros`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `libros` (
  `id_libro` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_editorial` int(10) unsigned DEFAULT NULL,
  `titulo` varchar(200) NOT NULL,
  `isbn` varchar(20) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `anio_publicacion` year(4) DEFAULT NULL,
  `portada_url` varchar(255) DEFAULT NULL,
  `archivo_url` varchar(255) DEFAULT NULL,
  `cantidad_disponible` int(11) NOT NULL DEFAULT 0,
  `estado` enum('disponible','no_disponible') NOT NULL DEFAULT 'disponible',
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_libro`),
  UNIQUE KEY `libros_isbn_unique` (`isbn`),
  KEY `libros_id_editorial_foreign` (`id_editorial`),
  CONSTRAINT `libros_id_editorial_foreign` FOREIGN KEY (`id_editorial`) REFERENCES `editorial` (`id_editorial`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `libros`
--

LOCK TABLES `libros` WRITE;
/*!40000 ALTER TABLE `libros` DISABLE KEYS */;
INSERT INTO `libros` VALUES (1,1,'Cien años de soledad','978-84-339-6153-5','Novela del realismo mágico',1967,'/biblio/public/uploads/covers/cover_1772982350_2e22c92c.webp','/uploads/cien-anos.pdf',5,'disponible','2026-03-08 13:03:58'),(2,2,'Harry Potter y la piedra filosofal','978-84-9838-001-2','Primer libro de la saga Harry Potter',1997,'/biblio/public/uploads/covers/cover_1772982333_17bf3f8d.jpg','/uploads/harry-potter.pdf',3,'disponible','2026-03-08 13:03:58'),(3,3,'It','978-84-450-7474-8','Novela de terror de Stephen King',1986,'/biblio/public/uploads/covers/cover_1772982272_3fe1f0e8.webp','/uploads/it.pdf',2,'disponible','2026-03-08 13:03:58'),(4,1,'La casa de los espíritus','978-84-339-6154-2','Novela de Isabel Allende',1982,'/biblio/public/uploads/covers/cover_1772982216_5aaa7975.jpg','/uploads/casa-espiritus.pdf',4,'disponible','2026-03-08 13:03:58'),(5,3,'El señor de los Anillos','9788845292613','El Señor de los Anillos es una novela de alta fantasía épica escrita por J.R.R. Tolkien, ambientada en la Tercera Edad de la Tierra Media. La trama narra el peligroso viaje de Frodo Bolsón para destruir el Anillo Único, creado por el señor oscuro Sauron, en los fuegos del Monte del Destino.',1954,'/biblio/public/uploads/covers/cover_1772991324_e50d01a8.jpg','/biblio/public/uploads/books/book_1772991324_f873edcb.pdf',1,'disponible','2026-03-08 17:35:24');
/*!40000 ALTER TABLE `libros` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `logs`
--

DROP TABLE IF EXISTS `logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `logs` (
  `id_log` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_usuario` int(10) unsigned DEFAULT NULL,
  `accion` varchar(150) NOT NULL,
  `tabla_afectada` varchar(100) NOT NULL,
  `id_registro_afectado` int(11) DEFAULT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp(),
  `ip` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id_log`),
  KEY `logs_id_usuario_foreign` (`id_usuario`),
  CONSTRAINT `logs_id_usuario_foreign` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `logs`
--

LOCK TABLES `logs` WRITE;
/*!40000 ALTER TABLE `logs` DISABLE KEYS */;
INSERT INTO `logs` VALUES (1,3,'Login','usuarios',3,'2026-03-08 13:04:57','192.168.1.1'),(2,7,'Registro de usuario','usuarios',7,'2026-03-08 14:58:00','::1'),(3,7,'Inicio de sesión','usuarios',7,'2026-03-08 14:58:06','::1'),(4,7,'Cierre de sesión','usuarios',7,'2026-03-08 14:58:19','::1'),(5,6,'Inicio de sesión','usuarios',6,'2026-03-08 15:02:23','::1'),(6,6,'Actualización de libro ID: 4','libros',4,'2026-03-08 15:03:36','::1'),(7,6,'Actualización de libro ID: 3','libros',3,'2026-03-08 15:04:32','::1'),(8,6,'Actualización de libro ID: 2','libros',2,'2026-03-08 15:05:34','::1'),(9,6,'Actualización de libro ID: 1','libros',1,'2026-03-08 15:05:50','::1'),(10,6,'Inicio de sesión','usuarios',6,'2026-03-08 15:15:55','::1'),(11,6,'Inicio de sesión','usuarios',6,'2026-03-08 15:18:23','::1'),(12,6,'Actualización de libro ID: 1','libros',1,'2026-03-08 15:31:37','::1'),(13,6,'Inicio de sesión','usuarios',6,'2026-03-08 15:35:35','::1'),(14,6,'Actualización de libro ID: 1','libros',1,'2026-03-08 15:35:49','::1'),(15,7,'Inicio de sesión','usuarios',7,'2026-03-08 16:21:57','::1'),(16,7,'Inicio de sesión','usuarios',7,'2026-03-08 16:22:08','::1'),(17,8,'Registro de usuario','usuarios',8,'2026-03-08 16:59:19','::1'),(18,8,'Inicio de sesión','usuarios',8,'2026-03-08 17:01:43','::1'),(19,8,'Reseña creada para libro ID: 3','resenas',7,'2026-03-08 17:05:18','::1'),(20,8,'Cierre de sesión','usuarios',8,'2026-03-08 17:09:41','::1'),(21,6,'Inicio de sesión','usuarios',6,'2026-03-08 17:11:45','::1'),(22,6,'Creación de libro: El señor de los Anillos','libros',5,'2026-03-08 17:35:24','::1'),(23,6,'Inicio de sesión','usuarios',6,'2026-03-08 17:46:48','::1'),(24,6,'CREAR_AUTOR: Autor creado ID: 5','autores',5,'2026-03-08 17:47:18','::1'),(25,6,'ACTUALIZAR_AUTOR: Autor actualizado ID: 5','autores',5,'2026-03-08 17:50:15','::1'),(26,6,'CREAR_CATEGORIA: Categoría creada ID: 5','categorias',5,'2026-03-08 17:52:13','::1'),(27,6,'ELIMINAR_CATEGORIA: Categoría eliminada ID: 1','categorias',1,'2026-03-08 17:54:47','::1'),(28,6,'CREAR_CATEGORIA: Categoría creada ID: 6','categorias',6,'2026-03-08 17:55:32','::1'),(29,6,'ACTUALIZAR_CATEGORIA: Categoría actualizada ID: 6','categorias',6,'2026-03-08 17:57:03','::1'),(30,6,'CREAR_EDITORIAL: Editorial creada ID: 4','editoriales',4,'2026-03-08 18:02:14','::1');
/*!40000 ALTER TABLE `logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2026_03_08_084302_create_personal_access_tokens_table',1),(2,'2026_03_08_084306_create_roles_table',1),(3,'2026_03_08_084312_create_usuarios_table',1),(4,'2026_03_08_084316_create_categorias_table',1),(5,'2026_03_08_084317_create_autores_table',1),(6,'2026_03_08_084317_create_editorial_table',1),(7,'2026_03_08_084318_create_libros_table',1),(8,'2026_03_08_084553_create_libro_autor_table',1),(9,'2026_03_08_084554_create_descargas_table',1),(10,'2026_03_08_084554_create_libro_categoria_table',1),(11,'2026_03_08_084556_create_logs_table',1),(12,'2026_03_08_084556_create_resenas_table',1),(13,'2026_03_08_084557_create_recomendaciones_table',1),(14,'2026_03_08_084999_create_password_resets_table',2),(15,'2026_03_08_085000_create_favoritos_table',2);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_resets` (
  `email` varchar(150) NOT NULL,
  `token` varchar(255) NOT NULL,
  `expires_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_resets`
--

LOCK TABLES `password_resets` WRITE;
/*!40000 ALTER TABLE `password_resets` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_resets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) unsigned NOT NULL,
  `name` text NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  KEY `personal_access_tokens_expires_at_index` (`expires_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_access_tokens`
--

LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `recomendaciones`
--

DROP TABLE IF EXISTS `recomendaciones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `recomendaciones` (
  `id_recomendacion` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_libro` int(10) unsigned NOT NULL,
  `detalle` text NOT NULL,
  PRIMARY KEY (`id_recomendacion`),
  KEY `recomendaciones_id_libro_foreign` (`id_libro`),
  CONSTRAINT `recomendaciones_id_libro_foreign` FOREIGN KEY (`id_libro`) REFERENCES `libros` (`id_libro`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `recomendaciones`
--

LOCK TABLES `recomendaciones` WRITE;
/*!40000 ALTER TABLE `recomendaciones` DISABLE KEYS */;
INSERT INTO `recomendaciones` VALUES (1,1,'Recomendado para amantes de la literatura latinoamericana');
/*!40000 ALTER TABLE `recomendaciones` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `resenas`
--

DROP TABLE IF EXISTS `resenas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `resenas` (
  `id_resena` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_usuario` int(10) unsigned NOT NULL,
  `id_libro` int(10) unsigned NOT NULL,
  `calificacion` tinyint(3) unsigned NOT NULL,
  `comentario` text DEFAULT NULL,
  `fecha_resena` timestamp NOT NULL DEFAULT current_timestamp(),
  `estado` enum('visible','oculta') NOT NULL DEFAULT 'visible',
  PRIMARY KEY (`id_resena`),
  KEY `resenas_id_usuario_foreign` (`id_usuario`),
  KEY `resenas_id_libro_foreign` (`id_libro`),
  CONSTRAINT `resenas_id_libro_foreign` FOREIGN KEY (`id_libro`) REFERENCES `libros` (`id_libro`),
  CONSTRAINT `resenas_id_usuario_foreign` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `resenas`
--

LOCK TABLES `resenas` WRITE;
/*!40000 ALTER TABLE `resenas` DISABLE KEYS */;
INSERT INTO `resenas` VALUES (5,4,1,5,'Una obra maestra del realismo mágico','2026-03-08 13:04:57','visible'),(6,4,2,4,'Excelente libro para todas las edades','2026-03-08 13:04:57','visible'),(7,8,3,5,'Excelente libro','2026-03-08 17:05:18','visible');
/*!40000 ALTER TABLE `resenas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `roles` (
  `id_rol` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_rol`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'Administrador','Control total del sistema','2026-03-08 09:03:54'),(2,'Usuario','Usuario regular del sistema','2026-03-08 09:03:54');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usuarios` (
  `id_usuario` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_rol` int(10) unsigned NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellidoP` varchar(100) DEFAULT NULL,
  `apellidoM` varchar(100) DEFAULT NULL,
  `email` varchar(150) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `estado` enum('activo','inactivo') NOT NULL DEFAULT 'activo',
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `ultimo_acceso` datetime DEFAULT NULL,
  PRIMARY KEY (`id_usuario`),
  UNIQUE KEY `usuarios_email_unique` (`email`),
  KEY `usuarios_id_rol_foreign` (`id_rol`),
  CONSTRAINT `usuarios_id_rol_foreign` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`id_rol`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuarios`
--

LOCK TABLES `usuarios` WRITE;
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
INSERT INTO `usuarios` VALUES (3,1,'Admin','Principal',NULL,'admin@bibliodigital.com','$2y$10$BoIJocq1y8E6TPZ.9TugOuIKzt5dJyqJLOh9x4Y73gjYW79VJaJ4W','123456789','activo','2026-03-08 13:03:58','2026-03-08 09:03:58'),(4,2,'Juan','Pérez','García','juan@example.com','$2y$10$7VAszpY/LhdwCh6OO3wQ6.bt1fN9rv2aUEXtkOMv6acK7V.EGkG8C','987654321','activo','2026-03-08 13:03:58',NULL),(6,1,'Administrador','','','administrador@gmail.com','$2y$10$gsq6lmSZWCPA.qF38UMan.KFBHnLQ7w3i/qT2AAa1J09fiuXdUU1u','','activo','2026-03-08 14:57:31','2026-03-08 13:46:48'),(7,2,'Jhosmar','','','jhosmar@gmail.com','$2y$10$rvJwqAeKtM2YvYtS3dxu3Op6T3jImlSLW6EmIKG9TRHTcQeIvtAbW','','activo','2026-03-08 14:58:00','2026-03-08 12:22:08'),(8,2,'Prueba','','','prueba@gmail.com','$2y$10$i1JABvJRxLtHORro0Eb4iO.AbuDgluDIfScPfwYbd7gvE0jyXE//m','','activo','2026-03-08 16:59:19','2026-03-08 13:01:43');
/*!40000 ALTER TABLE `usuarios` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-03-08 19:45:24
