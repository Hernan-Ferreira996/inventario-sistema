-- MySQL dump 10.13  Distrib 8.4.3, for Win64 (x86_64)
--
-- Host: localhost    Database: inventario_sistema
-- ------------------------------------------------------
-- Server version	5.5.5-10.4.11-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `activity_log`
--

DROP TABLE IF EXISTS `activity_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `activity_log` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `log_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `event` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject_id` bigint(20) unsigned DEFAULT NULL,
  `causer_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `causer_id` bigint(20) unsigned DEFAULT NULL,
  `properties` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`properties`)),
  `batch_uuid` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `subject` (`subject_type`,`subject_id`),
  KEY `causer` (`causer_type`,`causer_id`),
  KEY `activity_log_log_name_index` (`log_name`)
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `activity_log`
--

LOCK TABLES `activity_log` WRITE;
/*!40000 ALTER TABLE `activity_log` DISABLE KEYS */;
INSERT INTO `activity_log` VALUES (1,'productos','updated','App\\Models\\Producto','updated',1,'App\\Models\\User',1,'{\"attributes\":{\"precio_venta_minorista\":\"751.00\"},\"old\":{\"precio_venta_minorista\":\"750.00\"}}',NULL,'2026-06-30 13:29:23','2026-06-30 13:29:23'),(3,'facturas','created','App\\Models\\Factura','created',2,'App\\Models\\User',1,'{\"attributes\":{\"numero_factura\":\"0000001\",\"total\":\"0.00\",\"estado\":\"pendiente\",\"monto_pagado\":\"0.00\"}}',NULL,'2026-06-30 13:58:03','2026-06-30 13:58:03'),(4,'pedidos_venta','updated','App\\Models\\PedidoVenta','updated',1,'App\\Models\\User',1,'{\"attributes\":{\"estado_factura\":\"completado\"},\"old\":{\"estado_factura\":\"pendiente\"}}',NULL,'2026-06-30 13:58:03','2026-06-30 13:58:03'),(5,'notas_remision','created','App\\Models\\NotaRemision','created',1,'App\\Models\\User',1,'{\"attributes\":{\"numero_documento\":\"0000001\",\"motivo\":\"venta\"}}',NULL,'2026-06-30 13:58:53','2026-06-30 13:58:53'),(6,'notas_credito','created','App\\Models\\NotaCredito','created',1,'App\\Models\\User',1,'{\"attributes\":{\"numero_documento\":\"0000001\",\"total\":\"750.00\",\"motivo\":\"devolucion_parcial\"}}',NULL,'2026-06-30 13:59:22','2026-06-30 13:59:22'),(7,'presupuestos','created','App\\Models\\Presupuesto','created',1,'App\\Models\\User',1,'{\"attributes\":{\"numero_documento\":\"PRE-000001\",\"total\":\"54.00\",\"estado\":\"pendiente\"}}',NULL,'2026-06-30 14:59:28','2026-06-30 14:59:28'),(8,'presupuestos','updated','App\\Models\\Presupuesto','updated',1,'App\\Models\\User',1,'{\"attributes\":{\"estado\":\"aprobado\"},\"old\":{\"estado\":\"pendiente\"}}',NULL,'2026-06-30 14:59:54','2026-06-30 14:59:54'),(9,'pedidos_venta','created','App\\Models\\PedidoVenta','created',3,'App\\Models\\User',1,'{\"attributes\":{\"numero_referencia\":\"PV-000003\",\"total\":\"54.00\",\"monto_pagado\":\"0.00\",\"estado\":\"activo\",\"estado_factura\":\"pendiente\"}}',NULL,'2026-06-30 14:59:55','2026-06-30 14:59:55'),(10,'presupuestos','updated','App\\Models\\Presupuesto','updated',1,'App\\Models\\User',1,'{\"attributes\":{\"estado\":\"convertido\"},\"old\":{\"estado\":\"aprobado\"}}',NULL,'2026-06-30 14:59:55','2026-06-30 14:59:55'),(11,'traslados','created','App\\Models\\TrasladoStock','created',1,'App\\Models\\User',1,'{\"attributes\":{\"referencia\":\"Test traslado\"}}',NULL,'2026-06-30 15:54:48','2026-06-30 15:54:48'),(12,'notas_remision','created','App\\Models\\NotaRemision','created',2,'App\\Models\\User',1,'{\"attributes\":{\"numero_documento\":\"0000002\",\"motivo\":\"venta\"}}',NULL,'2026-06-30 15:55:42','2026-06-30 15:55:42'),(13,'facturas','created','App\\Models\\Factura','created',3,'App\\Models\\User',1,'{\"attributes\":{\"numero_factura\":\"0000003\",\"total\":\"0.00\",\"estado\":\"pendiente\",\"monto_pagado\":\"0.00\"}}',NULL,'2026-06-30 15:56:33','2026-06-30 15:56:33'),(14,'facturas','created','App\\Models\\Factura','created',4,'App\\Models\\User',1,'{\"attributes\":{\"numero_factura\":\"0000001\",\"total\":\"31.26\",\"estado\":\"pendiente\",\"monto_pagado\":\"0.00\"}}',NULL,'2026-06-30 16:01:36','2026-06-30 16:01:36'),(15,'pedidos_venta','updated','App\\Models\\PedidoVenta','updated',2,'App\\Models\\User',1,'{\"attributes\":{\"estado_factura\":\"completado\"},\"old\":{\"estado_factura\":\"pendiente\"}}',NULL,'2026-06-30 16:01:36','2026-06-30 16:01:36'),(16,'pagos','created','App\\Models\\Pago','created',1,'App\\Models\\User',1,'{\"attributes\":{\"monto\":\"15.00\"}}',NULL,'2026-06-30 16:03:27','2026-06-30 16:03:27'),(17,'facturas','updated','App\\Models\\Factura','updated',4,'App\\Models\\User',1,'{\"attributes\":{\"monto_pagado\":\"15.00\"},\"old\":{\"monto_pagado\":\"0.00\"}}',NULL,'2026-06-30 16:03:27','2026-06-30 16:03:27'),(18,'facturas','updated','App\\Models\\Factura','updated',4,'App\\Models\\User',1,'{\"attributes\":{\"estado\":\"parcial\"},\"old\":{\"estado\":\"pendiente\"}}',NULL,'2026-06-30 16:03:27','2026-06-30 16:03:27'),(19,'pedidos_venta','updated','App\\Models\\PedidoVenta','updated',2,'App\\Models\\User',1,'{\"attributes\":{\"monto_pagado\":\"15.00\"},\"old\":{\"monto_pagado\":\"0.00\"}}',NULL,'2026-06-30 16:03:27','2026-06-30 16:03:27'),(20,'envios','created','App\\Models\\Envio','created',1,'App\\Models\\User',1,'{\"attributes\":{\"numero_envio\":\"ENV-000001\",\"estado\":\"preparando\"}}',NULL,'2026-06-30 16:04:37','2026-06-30 16:04:37'),(21,'productos','created','App\\Models\\Producto','created',6,NULL,NULL,'{\"attributes\":{\"codigo\":\"EMP2-001\",\"nombre\":\"Producto exclusivo Empresa 2\",\"precio_compra\":\"10.00\",\"precio_venta_minorista\":\"15.00\",\"precio_venta_mayorista\":\"12.00\",\"activo\":true}}',NULL,'2026-06-30 17:51:05','2026-06-30 17:51:05'),(22,'productos','created','App\\Models\\Producto','created',7,'App\\Models\\User',5,'{\"attributes\":{\"codigo\":\"EMP2-REAL\",\"nombre\":\"Producto Real Empresa 2\",\"precio_compra\":\"5.00\",\"precio_venta_minorista\":\"8.00\",\"precio_venta_mayorista\":\"7.00\",\"activo\":true}}',NULL,'2026-06-30 17:52:41','2026-06-30 17:52:41'),(23,'pedidos_venta','created','App\\Models\\PedidoVenta','created',4,'App\\Models\\User',1,'{\"attributes\":{\"numero_referencia\":\"PV-000001\",\"total\":\"150000.00\",\"monto_pagado\":\"0.00\",\"estado\":\"activo\",\"estado_factura\":\"pendiente\"}}',NULL,'2026-06-30 18:28:49','2026-06-30 18:28:49'),(24,'facturas','created','App\\Models\\Factura','created',11,'App\\Models\\User',1,'{\"attributes\":{\"numero_factura\":\"0000001\",\"total\":\"168000.00\",\"estado\":\"pendiente\",\"monto_pagado\":\"0.00\"}}',NULL,'2026-06-30 18:28:50','2026-06-30 18:28:50'),(25,'pedidos_venta','updated','App\\Models\\PedidoVenta','updated',4,'App\\Models\\User',1,'{\"attributes\":{\"estado_factura\":\"completado\"},\"old\":{\"estado_factura\":\"pendiente\"}}',NULL,'2026-06-30 18:28:50','2026-06-30 18:28:50'),(26,'clientes','created','App\\Models\\Cliente','created',5,'App\\Models\\User',1,'{\"attributes\":{\"nombre\":\"Francisco Ramirez\",\"email\":\"framirez@gmail.com\",\"telefono\":\"0981-234-567\",\"tipo_precio\":\"minorista\",\"activo\":true}}',NULL,'2026-06-30 19:09:15','2026-06-30 19:09:15'),(27,'clientes','created','App\\Models\\Cliente','created',6,'App\\Models\\User',1,'{\"attributes\":{\"nombre\":\"Distribuidora Central S.A.\",\"email\":\"compras@distcentral.com.py\",\"telefono\":\"021-555-0100\",\"tipo_precio\":\"mayorista\",\"activo\":true}}',NULL,'2026-06-30 19:09:15','2026-06-30 19:09:15'),(28,'clientes','created','App\\Models\\Cliente','created',7,'App\\Models\\User',1,'{\"attributes\":{\"nombre\":\"Supermercado El Sol\",\"email\":\"pedidos@elsol.com.py\",\"telefono\":\"0991-876-543\",\"tipo_precio\":\"mayorista\",\"activo\":true}}',NULL,'2026-06-30 19:09:15','2026-06-30 19:09:15'),(29,'clientes','created','App\\Models\\Cliente','created',8,'App\\Models\\User',1,'{\"attributes\":{\"nombre\":\"Maria Benitez\",\"email\":\"mbenitez@yahoo.com\",\"telefono\":\"0976-123-456\",\"tipo_precio\":\"minorista\",\"activo\":true}}',NULL,'2026-06-30 19:09:15','2026-06-30 19:09:15'),(30,'presupuestos','created','App\\Models\\Presupuesto','created',2,'App\\Models\\User',1,'{\"attributes\":{\"numero_documento\":\"PRE-000001\",\"total\":\"5275000.00\",\"estado\":\"pendiente\"}}',NULL,'2026-06-30 19:12:57','2026-06-30 19:12:57'),(31,'presupuestos','created','App\\Models\\Presupuesto','created',3,'App\\Models\\User',1,'{\"attributes\":{\"numero_documento\":\"PRE-000002\",\"total\":\"1225000.00\",\"estado\":\"aprobado\"}}',NULL,'2026-06-30 19:12:58','2026-06-30 19:12:58'),(32,'pedidos_venta','created','App\\Models\\PedidoVenta','created',5,'App\\Models\\User',1,'{\"attributes\":{\"numero_referencia\":\"PV-000001\",\"total\":\"150000.00\",\"monto_pagado\":\"150000.00\",\"estado\":\"completado\",\"estado_factura\":\"completado\"}}',NULL,'2026-06-30 19:12:58','2026-06-30 19:12:58'),(33,'pedidos_venta','created','App\\Models\\PedidoVenta','created',6,'App\\Models\\User',1,'{\"attributes\":{\"numero_referencia\":\"PV-000002\",\"total\":\"2364000.00\",\"monto_pagado\":\"0.00\",\"estado\":\"activo\",\"estado_factura\":\"pendiente\"}}',NULL,'2026-06-30 19:12:58','2026-06-30 19:12:58'),(34,'pedidos_venta','created','App\\Models\\PedidoVenta','created',7,'App\\Models\\User',1,'{\"attributes\":{\"numero_referencia\":\"PV-000003\",\"total\":\"5200000.00\",\"monto_pagado\":\"2000000.00\",\"estado\":\"activo\",\"estado_factura\":\"completado\"}}',NULL,'2026-06-30 19:12:58','2026-06-30 19:12:58'),(35,'pedidos_venta','created','App\\Models\\PedidoVenta','created',8,'App\\Models\\User',1,'{\"attributes\":{\"numero_referencia\":\"PV-000004\",\"total\":\"32500.00\",\"monto_pagado\":\"0.00\",\"estado\":\"activo\",\"estado_factura\":\"pendiente\"}}',NULL,'2026-06-30 19:12:58','2026-06-30 19:12:58'),(36,'pedidos_venta','created','App\\Models\\PedidoVenta','created',9,'App\\Models\\User',1,'{\"attributes\":{\"numero_referencia\":\"PV-000005\",\"total\":\"5575000.00\",\"monto_pagado\":\"0.00\",\"estado\":\"activo\",\"estado_factura\":\"completado\"}}',NULL,'2026-06-30 19:12:58','2026-06-30 19:12:58'),(37,'facturas','created','App\\Models\\Factura','created',12,'App\\Models\\User',1,'{\"attributes\":{\"numero_factura\":\"0000001\",\"total\":\"150000.00\",\"estado\":\"pagada\",\"monto_pagado\":\"150000.00\"}}',NULL,'2026-06-30 19:12:59','2026-06-30 19:12:59'),(38,'facturas','created','App\\Models\\Factura','created',13,'App\\Models\\User',1,'{\"attributes\":{\"numero_factura\":\"0000002\",\"total\":\"5200000.00\",\"estado\":\"parcial\",\"monto_pagado\":\"2000000.00\"}}',NULL,'2026-06-30 19:12:59','2026-06-30 19:12:59'),(39,'facturas','created','App\\Models\\Factura','created',14,'App\\Models\\User',1,'{\"attributes\":{\"numero_factura\":\"0000003\",\"total\":\"5575000.00\",\"estado\":\"pendiente\",\"monto_pagado\":\"0.00\"}}',NULL,'2026-06-30 19:12:59','2026-06-30 19:12:59'),(40,'pagos','created','App\\Models\\Pago','created',2,'App\\Models\\User',1,'{\"attributes\":{\"monto\":\"150000.00\"}}',NULL,'2026-06-30 19:12:59','2026-06-30 19:12:59'),(41,'pagos','created','App\\Models\\Pago','created',3,'App\\Models\\User',1,'{\"attributes\":{\"monto\":\"2000000.00\"}}',NULL,'2026-06-30 19:12:59','2026-06-30 19:12:59'),(42,'pedidos_compra','created','App\\Models\\PedidoCompra','created',2,'App\\Models\\User',1,'{\"attributes\":{\"numero_referencia\":\"PC-000001\",\"total\":\"42350000.00\",\"estado\":\"completado\"}}',NULL,'2026-06-30 19:12:59','2026-06-30 19:12:59'),(43,'pedidos_compra','created','App\\Models\\PedidoCompra','created',3,'App\\Models\\User',1,'{\"attributes\":{\"numero_referencia\":\"PC-000002\",\"total\":\"5437500.00\",\"estado\":\"parcial\"}}',NULL,'2026-06-30 19:13:00','2026-06-30 19:13:00'),(44,'traslados','created','App\\Models\\TrasladoStock','created',2,'App\\Models\\User',1,'{\"attributes\":{\"referencia\":\"Reposicion mensual Tienda\"}}',NULL,'2026-06-30 19:13:02','2026-06-30 19:13:02'),(45,'notas_remision','created','App\\Models\\NotaRemision','created',3,'App\\Models\\User',1,'{\"attributes\":{\"numero_documento\":\"0000001\",\"motivo\":\"venta\"}}',NULL,'2026-06-30 19:13:02','2026-06-30 19:13:02'),(46,'envios','created','App\\Models\\Envio','created',2,'App\\Models\\User',1,'{\"attributes\":{\"numero_envio\":\"ENV-000001\",\"estado\":\"entregado\"}}',NULL,'2026-06-30 19:13:03','2026-06-30 19:13:03'),(47,'facturas','created','App\\Models\\Factura','created',15,'App\\Models\\User',1,'{\"attributes\":{\"numero_factura\":\"0000015\",\"total\":\"148500.00\",\"estado\":\"pendiente\",\"monto_pagado\":\"0.00\"}}',NULL,'2026-06-30 19:55:57','2026-06-30 19:55:57');
/*!40000 ALTER TABLE `activity_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categorias`
--

DROP TABLE IF EXISTS `categorias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categorias` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `empresa_id` bigint(20) unsigned DEFAULT NULL,
  `nombre` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `categorias_empresa_id_foreign` (`empresa_id`),
  CONSTRAINT `categorias_empresa_id_foreign` FOREIGN KEY (`empresa_id`) REFERENCES `empresas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categorias`
--

LOCK TABLES `categorias` WRITE;
/*!40000 ALTER TABLE `categorias` DISABLE KEYS */;
INSERT INTO `categorias` VALUES (1,1,'Electrónica',NULL,1,'2026-06-28 22:21:26','2026-06-28 22:21:26'),(2,1,'Ropa y Calzado',NULL,1,'2026-06-28 22:21:26','2026-06-28 22:21:26'),(3,1,'Alimentos',NULL,1,'2026-06-28 22:21:26','2026-06-28 22:21:26'),(4,1,'Bebidas',NULL,1,'2026-06-28 22:21:26','2026-06-28 22:21:26'),(5,1,'Herramientas',NULL,1,'2026-06-28 22:21:26','2026-06-28 22:21:26'),(6,1,'Oficina',NULL,1,'2026-06-28 22:21:26','2026-06-28 22:21:26'),(7,1,'Limpieza',NULL,1,'2026-06-28 22:21:26','2026-06-28 22:21:26'),(8,1,'Medicamentos',NULL,1,'2026-06-28 22:21:27','2026-06-28 22:21:27'),(9,1,'Electrónica',NULL,1,'2026-06-30 13:22:47','2026-06-30 13:22:47'),(10,1,'Ropa y Calzado',NULL,1,'2026-06-30 13:22:47','2026-06-30 13:22:47'),(11,1,'Alimentos',NULL,1,'2026-06-30 13:22:47','2026-06-30 13:22:47'),(12,1,'Bebidas',NULL,1,'2026-06-30 13:22:47','2026-06-30 13:22:47'),(13,1,'Herramientas',NULL,1,'2026-06-30 13:22:47','2026-06-30 13:22:47'),(14,1,'Oficina',NULL,1,'2026-06-30 13:22:47','2026-06-30 13:22:47'),(15,1,'Limpieza',NULL,1,'2026-06-30 13:22:47','2026-06-30 13:22:47'),(16,1,'Medicamentos',NULL,1,'2026-06-30 13:22:47','2026-06-30 13:22:47');
/*!40000 ALTER TABLE `categorias` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `clientes`
--

DROP TABLE IF EXISTS `clientes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `clientes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `empresa_id` bigint(20) unsigned DEFAULT NULL,
  `nombre` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telefono` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `direccion` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ruc_nit` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tipo_precio` enum('minorista','mayorista') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'minorista',
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `clientes_email_unique` (`email`),
  KEY `clientes_empresa_id_foreign` (`empresa_id`),
  CONSTRAINT `clientes_empresa_id_foreign` FOREIGN KEY (`empresa_id`) REFERENCES `empresas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `clientes`
--

LOCK TABLES `clientes` WRITE;
/*!40000 ALTER TABLE `clientes` DISABLE KEYS */;
INSERT INTO `clientes` VALUES (1,1,'Juan Pérez','juan@email.com','0991234567',NULL,NULL,'minorista',1,NULL,NULL,NULL,'2026-06-28 22:21:29','2026-06-28 22:21:29'),(2,1,'Distribuidora XYZ','xyz@empresa.com','022345678',NULL,NULL,'mayorista',1,NULL,NULL,NULL,'2026-06-28 22:21:29','2026-06-28 22:21:29'),(3,1,'María González','maria@email.com','0987654321',NULL,NULL,'minorista',1,NULL,NULL,NULL,'2026-06-28 22:21:29','2026-06-28 22:21:29'),(4,1,'Comercial ABC S.A.','abc@comercial.com','023456789',NULL,NULL,'mayorista',1,NULL,NULL,NULL,'2026-06-28 22:21:29','2026-06-28 22:21:29'),(5,NULL,'Francisco Ramirez','framirez@gmail.com','0981-234-567',NULL,'3456789-0','minorista',1,NULL,NULL,NULL,'2026-06-30 19:09:15','2026-06-30 19:09:15'),(6,NULL,'Distribuidora Central S.A.','compras@distcentral.com.py','021-555-0100',NULL,'80065432-1','mayorista',1,NULL,NULL,NULL,'2026-06-30 19:09:15','2026-06-30 19:09:15'),(7,NULL,'Supermercado El Sol','pedidos@elsol.com.py','0991-876-543',NULL,'80079876-5','mayorista',1,NULL,NULL,NULL,'2026-06-30 19:09:15','2026-06-30 19:09:15'),(8,NULL,'Maria Benitez','mbenitez@yahoo.com','0976-123-456',NULL,'2345678-9','minorista',1,NULL,NULL,NULL,'2026-06-30 19:09:15','2026-06-30 19:09:15');
/*!40000 ALTER TABLE `clientes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `detalle_envios`
--

DROP TABLE IF EXISTS `detalle_envios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `detalle_envios` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `envio_id` bigint(20) unsigned NOT NULL,
  `producto_id` bigint(20) unsigned NOT NULL,
  `cantidad` decimal(12,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `detalle_envios_envio_id_foreign` (`envio_id`),
  KEY `detalle_envios_producto_id_foreign` (`producto_id`),
  CONSTRAINT `detalle_envios_envio_id_foreign` FOREIGN KEY (`envio_id`) REFERENCES `envios` (`id`) ON DELETE CASCADE,
  CONSTRAINT `detalle_envios_producto_id_foreign` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detalle_envios`
--

LOCK TABLES `detalle_envios` WRITE;
/*!40000 ALTER TABLE `detalle_envios` DISABLE KEYS */;
INSERT INTO `detalle_envios` VALUES (2,2,2,2.00,'2026-06-30 19:13:03','2026-06-30 19:13:03');
/*!40000 ALTER TABLE `detalle_envios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `detalle_notas_credito`
--

DROP TABLE IF EXISTS `detalle_notas_credito`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `detalle_notas_credito` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nota_credito_id` bigint(20) unsigned NOT NULL,
  `producto_id` bigint(20) unsigned NOT NULL,
  `cantidad` decimal(12,2) NOT NULL,
  `precio_unitario` decimal(12,2) NOT NULL,
  `subtotal` decimal(12,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `detalle_notas_credito_nota_credito_id_foreign` (`nota_credito_id`),
  KEY `detalle_notas_credito_producto_id_foreign` (`producto_id`),
  CONSTRAINT `detalle_notas_credito_nota_credito_id_foreign` FOREIGN KEY (`nota_credito_id`) REFERENCES `notas_credito` (`id`) ON DELETE CASCADE,
  CONSTRAINT `detalle_notas_credito_producto_id_foreign` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detalle_notas_credito`
--

LOCK TABLES `detalle_notas_credito` WRITE;
/*!40000 ALTER TABLE `detalle_notas_credito` DISABLE KEYS */;
/*!40000 ALTER TABLE `detalle_notas_credito` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `detalle_notas_remision`
--

DROP TABLE IF EXISTS `detalle_notas_remision`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `detalle_notas_remision` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nota_remision_id` bigint(20) unsigned NOT NULL,
  `producto_id` bigint(20) unsigned NOT NULL,
  `cantidad` decimal(12,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `detalle_notas_remision_nota_remision_id_foreign` (`nota_remision_id`),
  KEY `detalle_notas_remision_producto_id_foreign` (`producto_id`),
  CONSTRAINT `detalle_notas_remision_nota_remision_id_foreign` FOREIGN KEY (`nota_remision_id`) REFERENCES `notas_remision` (`id`) ON DELETE CASCADE,
  CONSTRAINT `detalle_notas_remision_producto_id_foreign` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detalle_notas_remision`
--

LOCK TABLES `detalle_notas_remision` WRITE;
/*!40000 ALTER TABLE `detalle_notas_remision` DISABLE KEYS */;
INSERT INTO `detalle_notas_remision` VALUES (3,3,2,2.00,'2026-06-30 19:13:02','2026-06-30 19:13:02');
/*!40000 ALTER TABLE `detalle_notas_remision` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `detalle_pedidos_compra`
--

DROP TABLE IF EXISTS `detalle_pedidos_compra`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `detalle_pedidos_compra` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `pedido_compra_id` bigint(20) unsigned NOT NULL,
  `producto_id` bigint(20) unsigned NOT NULL,
  `cantidad` decimal(12,2) NOT NULL,
  `precio_unitario` decimal(12,2) NOT NULL,
  `subtotal` decimal(12,2) NOT NULL,
  `cantidad_recibida` decimal(12,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `detalle_pedidos_compra_pedido_compra_id_foreign` (`pedido_compra_id`),
  KEY `detalle_pedidos_compra_producto_id_foreign` (`producto_id`),
  CONSTRAINT `detalle_pedidos_compra_pedido_compra_id_foreign` FOREIGN KEY (`pedido_compra_id`) REFERENCES `pedidos_compra` (`id`) ON DELETE CASCADE,
  CONSTRAINT `detalle_pedidos_compra_producto_id_foreign` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detalle_pedidos_compra`
--

LOCK TABLES `detalle_pedidos_compra` WRITE;
/*!40000 ALTER TABLE `detalle_pedidos_compra` DISABLE KEYS */;
INSERT INTO `detalle_pedidos_compra` VALUES (2,2,1,10.00,3800000.00,38000000.00,10.00,'2026-06-30 19:13:00','2026-06-30 19:13:00'),(3,2,2,30.00,45000.00,1350000.00,30.00,'2026-06-30 19:13:00','2026-06-30 19:13:00'),(4,2,5,25.00,28000.00,700000.00,25.00,'2026-06-30 19:13:00','2026-06-30 19:13:00'),(5,3,4,500.00,4500.00,2250000.00,200.00,'2026-06-30 19:13:01','2026-06-30 19:13:01'),(6,3,3,50.00,50000.00,2500000.00,50.00,'2026-06-30 19:13:01','2026-06-30 19:13:01'),(7,3,5,25.00,27500.00,687500.00,25.00,'2026-06-30 19:13:01','2026-06-30 19:13:01');
/*!40000 ALTER TABLE `detalle_pedidos_compra` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `detalle_pedidos_venta`
--

DROP TABLE IF EXISTS `detalle_pedidos_venta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `detalle_pedidos_venta` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `pedido_id` bigint(20) unsigned NOT NULL,
  `producto_id` bigint(20) unsigned NOT NULL,
  `cantidad` decimal(12,2) NOT NULL,
  `precio_unitario` decimal(12,2) NOT NULL,
  `descuento` decimal(5,2) NOT NULL DEFAULT 0.00,
  `impuesto` decimal(5,2) NOT NULL DEFAULT 0.00,
  `subtotal` decimal(12,2) NOT NULL,
  `cantidad_enviada` decimal(12,2) NOT NULL DEFAULT 0.00,
  `cantidad_facturada` decimal(12,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `detalle_pedidos_venta_pedido_id_foreign` (`pedido_id`),
  KEY `detalle_pedidos_venta_producto_id_foreign` (`producto_id`),
  CONSTRAINT `detalle_pedidos_venta_pedido_id_foreign` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos_venta` (`id`) ON DELETE CASCADE,
  CONSTRAINT `detalle_pedidos_venta_producto_id_foreign` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detalle_pedidos_venta`
--

LOCK TABLES `detalle_pedidos_venta` WRITE;
/*!40000 ALTER TABLE `detalle_pedidos_venta` DISABLE KEYS */;
INSERT INTO `detalle_pedidos_venta` VALUES (4,4,2,2.00,75000.00,0.00,12.00,150000.00,0.00,0.00,'2026-06-30 18:28:49','2026-06-30 18:28:49'),(5,5,2,2.00,75000.00,0.00,10.00,150000.00,0.00,0.00,'2026-06-30 19:12:58','2026-06-30 19:12:58'),(6,6,3,20.00,95000.00,0.00,0.00,1900000.00,0.00,0.00,'2026-06-30 19:12:58','2026-06-30 19:12:58'),(7,6,5,10.00,42000.00,5.00,10.00,399000.00,0.00,0.00,'2026-06-30 19:12:58','2026-06-30 19:12:58'),(8,6,4,10.00,6500.00,0.00,5.00,65000.00,0.00,0.00,'2026-06-30 19:12:58','2026-06-30 19:12:58'),(9,7,1,1.00,5200000.00,0.00,10.00,5200000.00,0.00,0.00,'2026-06-30 19:12:58','2026-06-30 19:12:58'),(10,8,4,5.00,6500.00,0.00,5.00,32500.00,0.00,0.00,'2026-06-30 19:12:58','2026-06-30 19:12:58'),(11,9,1,1.00,5200000.00,0.00,10.00,5200000.00,0.00,0.00,'2026-06-30 19:12:58','2026-06-30 19:12:58'),(12,9,2,5.00,75000.00,0.00,10.00,375000.00,0.00,0.00,'2026-06-30 19:12:58','2026-06-30 19:12:58');
/*!40000 ALTER TABLE `detalle_pedidos_venta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `detalle_presupuestos`
--

DROP TABLE IF EXISTS `detalle_presupuestos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `detalle_presupuestos` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `presupuesto_id` bigint(20) unsigned NOT NULL,
  `producto_id` bigint(20) unsigned NOT NULL,
  `cantidad` decimal(12,2) NOT NULL,
  `precio_unitario` decimal(12,2) NOT NULL,
  `descuento` decimal(5,2) NOT NULL DEFAULT 0.00,
  `impuesto` decimal(5,2) NOT NULL DEFAULT 0.00,
  `subtotal` decimal(12,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `detalle_presupuestos_presupuesto_id_foreign` (`presupuesto_id`),
  KEY `detalle_presupuestos_producto_id_foreign` (`producto_id`),
  CONSTRAINT `detalle_presupuestos_presupuesto_id_foreign` FOREIGN KEY (`presupuesto_id`) REFERENCES `presupuestos` (`id`) ON DELETE CASCADE,
  CONSTRAINT `detalle_presupuestos_producto_id_foreign` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detalle_presupuestos`
--

LOCK TABLES `detalle_presupuestos` WRITE;
/*!40000 ALTER TABLE `detalle_presupuestos` DISABLE KEYS */;
INSERT INTO `detalle_presupuestos` VALUES (3,2,1,1.00,5200000.00,0.00,10.00,5200000.00,'2026-06-30 19:12:57','2026-06-30 19:12:57'),(4,2,2,1.00,75000.00,0.00,10.00,75000.00,'2026-06-30 19:12:57','2026-06-30 19:12:57'),(5,3,2,10.00,65000.00,0.00,10.00,650000.00,'2026-06-30 19:12:58','2026-06-30 19:12:58'),(6,3,3,5.00,80000.00,5.00,0.00,380000.00,'2026-06-30 19:12:58','2026-06-30 19:12:58'),(7,3,5,5.00,39000.00,0.00,10.00,195000.00,'2026-06-30 19:12:58','2026-06-30 19:12:58');
/*!40000 ALTER TABLE `detalle_presupuestos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `detalle_recepciones`
--

DROP TABLE IF EXISTS `detalle_recepciones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `detalle_recepciones` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `recepcion_id` bigint(20) unsigned NOT NULL,
  `producto_id` bigint(20) unsigned NOT NULL,
  `ubicacion_id` bigint(20) unsigned NOT NULL,
  `cantidad` decimal(12,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `detalle_recepciones_recepcion_id_foreign` (`recepcion_id`),
  KEY `detalle_recepciones_producto_id_foreign` (`producto_id`),
  KEY `detalle_recepciones_ubicacion_id_foreign` (`ubicacion_id`),
  CONSTRAINT `detalle_recepciones_producto_id_foreign` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`) ON DELETE CASCADE,
  CONSTRAINT `detalle_recepciones_recepcion_id_foreign` FOREIGN KEY (`recepcion_id`) REFERENCES `recepciones_compra` (`id`) ON DELETE CASCADE,
  CONSTRAINT `detalle_recepciones_ubicacion_id_foreign` FOREIGN KEY (`ubicacion_id`) REFERENCES `ubicaciones` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detalle_recepciones`
--

LOCK TABLES `detalle_recepciones` WRITE;
/*!40000 ALTER TABLE `detalle_recepciones` DISABLE KEYS */;
INSERT INTO `detalle_recepciones` VALUES (1,1,1,1,10.00,'2026-06-30 19:13:00','2026-06-30 19:13:00'),(2,1,2,1,30.00,'2026-06-30 19:13:00','2026-06-30 19:13:00'),(3,1,5,1,25.00,'2026-06-30 19:13:00','2026-06-30 19:13:00'),(4,2,4,1,200.00,'2026-06-30 19:13:01','2026-06-30 19:13:01'),(5,2,3,1,50.00,'2026-06-30 19:13:01','2026-06-30 19:13:01'),(6,2,5,1,25.00,'2026-06-30 19:13:01','2026-06-30 19:13:01');
/*!40000 ALTER TABLE `detalle_recepciones` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `detalle_traslados`
--

DROP TABLE IF EXISTS `detalle_traslados`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `detalle_traslados` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `traslado_id` bigint(20) unsigned NOT NULL,
  `producto_id` bigint(20) unsigned NOT NULL,
  `cantidad` decimal(12,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `detalle_traslados_traslado_id_foreign` (`traslado_id`),
  KEY `detalle_traslados_producto_id_foreign` (`producto_id`),
  CONSTRAINT `detalle_traslados_producto_id_foreign` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`) ON DELETE CASCADE,
  CONSTRAINT `detalle_traslados_traslado_id_foreign` FOREIGN KEY (`traslado_id`) REFERENCES `traslados_stock` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detalle_traslados`
--

LOCK TABLES `detalle_traslados` WRITE;
/*!40000 ALTER TABLE `detalle_traslados` DISABLE KEYS */;
INSERT INTO `detalle_traslados` VALUES (2,2,2,8.00,'2026-06-30 19:13:02','2026-06-30 19:13:02'),(3,2,3,15.00,'2026-06-30 19:13:02','2026-06-30 19:13:02'),(4,2,4,50.00,'2026-06-30 19:13:02','2026-06-30 19:13:02');
/*!40000 ALTER TABLE `detalle_traslados` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `empresas`
--

DROP TABLE IF EXISTS `empresas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `empresas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre_fantasia` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ruc` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dv` varchar(2) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telefono` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `web` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `direccion` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ciudad` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pais` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Paraguay',
  `moneda` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'PYG',
  `simbolo` varchar(6) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Gs.',
  `fact_timbrado` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fact_fecha_inicio_vigencia` date DEFAULT NULL,
  `fact_establecimiento` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '001',
  `fact_punto_expedicion` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '001',
  `fact_modo` enum('local','electronico') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'local',
  `timezone` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'America/Asuncion',
  `decimales` tinyint(4) NOT NULL DEFAULT 0,
  `stock_minimo` int(11) NOT NULL DEFAULT 5,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `empresas`
--

LOCK TABLES `empresas` WRITE;
/*!40000 ALTER TABLE `empresas` DISABLE KEYS */;
INSERT INTO `empresas` VALUES (1,'Francisco Hernan Ferreira Avalos','Desa Tec','5054287','7','0974223003','','','Benjamin Aceval','Guarambare','Paraguay','PYG','Gs.','18174154','2025-07-17','001','001','local','America/Asuncion',0,5,1,'2026-06-30 17:41:03','2026-06-30 17:41:03'),(2,'Empresa Demo Dos S.A.',NULL,'9999999','1',NULL,NULL,NULL,NULL,NULL,'Paraguay','PYG','Gs.',NULL,NULL,'001','001','local','America/Asuncion',0,5,1,'2026-06-30 17:49:57','2026-06-30 17:49:57');
/*!40000 ALTER TABLE `empresas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `envios`
--

DROP TABLE IF EXISTS `envios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `envios` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `pedido_id` bigint(20) unsigned NOT NULL,
  `numero_envio` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha_empaque` date NOT NULL,
  `fecha_entrega` date DEFAULT NULL,
  `comentarios` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estado` enum('preparando','enviado','entregado','devuelto') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'preparando',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `envios_numero_envio_unique` (`numero_envio`),
  KEY `envios_pedido_id_foreign` (`pedido_id`),
  CONSTRAINT `envios_pedido_id_foreign` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos_venta` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `envios`
--

LOCK TABLES `envios` WRITE;
/*!40000 ALTER TABLE `envios` DISABLE KEYS */;
INSERT INTO `envios` VALUES (2,5,'ENV-000001','2026-06-22','2026-06-23','Entregado conforme. Firma del cliente.','entregado','2026-06-30 19:13:02','2026-06-30 19:13:02');
/*!40000 ALTER TABLE `envios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `facturas`
--

DROP TABLE IF EXISTS `facturas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `facturas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `empresa_id` bigint(20) unsigned DEFAULT NULL,
  `sucursal_id` bigint(20) unsigned DEFAULT NULL,
  `pedido_id` bigint(20) unsigned NOT NULL,
  `numero_factura` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `timbrado` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `establecimiento` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '001',
  `punto_expedicion` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '001',
  `cdc` varchar(44) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `modo` enum('local','electronico') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'local',
  `tipo_documento_cliente` varchar(5) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `numero_documento_cliente` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `condicion_venta` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'contado',
  `descuento_global` decimal(5,2) NOT NULL DEFAULT 0.00,
  `monto_descuento` decimal(12,2) NOT NULL DEFAULT 0.00,
  `fecha_factura` date NOT NULL,
  `subtotal` decimal(12,2) NOT NULL DEFAULT 0.00,
  `impuesto_total` decimal(12,2) NOT NULL DEFAULT 0.00,
  `total` decimal(12,2) NOT NULL DEFAULT 0.00,
  `monto_pagado` decimal(12,2) NOT NULL DEFAULT 0.00,
  `estado` enum('pendiente','parcial','pagada','anulada') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pendiente',
  `notas` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_negocio_facturas` (`empresa_id`,`establecimiento`,`punto_expedicion`,`numero_factura`),
  KEY `facturas_pedido_id_foreign` (`pedido_id`),
  KEY `facturas_sucursal_id_foreign` (`sucursal_id`),
  CONSTRAINT `facturas_empresa_id_foreign` FOREIGN KEY (`empresa_id`) REFERENCES `empresas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `facturas_pedido_id_foreign` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos_venta` (`id`) ON DELETE CASCADE,
  CONSTRAINT `facturas_sucursal_id_foreign` FOREIGN KEY (`sucursal_id`) REFERENCES `sucursales` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `facturas`
--

LOCK TABLES `facturas` WRITE;
/*!40000 ALTER TABLE `facturas` DISABLE KEYS */;
INSERT INTO `facturas` VALUES (11,NULL,NULL,4,'0000001','18174154','001','001',NULL,'local',NULL,NULL,'contado',0.00,0.00,'2026-06-30',150000.00,18000.00,168000.00,0.00,'pendiente',NULL,'2026-06-30 18:28:50','2026-06-30 18:28:50'),(12,NULL,NULL,5,'0000001','18174154','001','001',NULL,'local','CI','3456789','contado',0.00,0.00,'2026-06-22',150000.00,15000.00,150000.00,150000.00,'pagada',NULL,'2026-06-30 19:12:59','2026-06-30 19:12:59'),(13,NULL,NULL,7,'0000002','18174154','001','001',NULL,'local','RUC','80012345-1','credito',0.00,0.00,'2026-06-24',5200000.00,520000.00,5200000.00,2000000.00,'parcial','Credito 15 dias - saldo: Gs. 3.200.000','2026-06-30 19:12:59','2026-06-30 19:12:59'),(14,NULL,NULL,9,'0000003','18174154','001','001',NULL,'local','RUC','80065432-1','credito',0.00,0.00,'2026-06-28',5575000.00,557500.00,5575000.00,0.00,'pendiente',NULL,'2026-06-30 19:12:59','2026-06-30 19:12:59'),(15,NULL,NULL,5,'0000015','18174154','001','001',NULL,'local',NULL,NULL,'credito',10.00,15000.00,'2026-06-30',135000.00,13500.00,148500.00,0.00,'pendiente',NULL,'2026-06-30 19:55:57','2026-06-30 19:55:57');
/*!40000 ALTER TABLE `facturas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `impuestos`
--

DROP TABLE IF EXISTS `impuestos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `impuestos` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `empresa_id` bigint(20) unsigned DEFAULT NULL,
  `nombre` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `porcentaje` decimal(5,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `impuestos_empresa_id_foreign` (`empresa_id`),
  CONSTRAINT `impuestos_empresa_id_foreign` FOREIGN KEY (`empresa_id`) REFERENCES `empresas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `impuestos`
--

LOCK TABLES `impuestos` WRITE;
/*!40000 ALTER TABLE `impuestos` DISABLE KEYS */;
INSERT INTO `impuestos` VALUES (1,1,'Sin impuesto',0.00,'2026-06-28 22:21:27','2026-06-28 22:21:27'),(2,1,'IVA 12%',12.00,'2026-06-28 22:21:27','2026-06-28 22:21:27'),(3,1,'IVA 5%',5.00,'2026-06-28 22:21:27','2026-06-28 22:21:27'),(4,1,'IVA 19%',19.00,'2026-06-28 22:21:27','2026-06-28 22:21:27'),(5,1,'Sin impuesto',0.00,'2026-06-30 13:22:48','2026-06-30 13:22:48'),(6,1,'IVA 12%',12.00,'2026-06-30 13:22:48','2026-06-30 13:22:48'),(7,1,'IVA 5%',5.00,'2026-06-30 13:22:48','2026-06-30 13:22:48'),(8,1,'IVA 19%',19.00,'2026-06-30 13:22:48','2026-06-30 13:22:48');
/*!40000 ALTER TABLE `impuestos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `metodos_pago`
--

DROP TABLE IF EXISTS `metodos_pago`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `metodos_pago` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `empresa_id` bigint(20) unsigned DEFAULT NULL,
  `nombre` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `metodos_pago_empresa_id_foreign` (`empresa_id`),
  CONSTRAINT `metodos_pago_empresa_id_foreign` FOREIGN KEY (`empresa_id`) REFERENCES `empresas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `metodos_pago`
--

LOCK TABLES `metodos_pago` WRITE;
/*!40000 ALTER TABLE `metodos_pago` DISABLE KEYS */;
INSERT INTO `metodos_pago` VALUES (1,1,'Efectivo',1,'2026-06-30 15:53:21','2026-06-30 15:53:21'),(2,1,'Transferencia Bancaria',1,'2026-06-30 15:53:21','2026-06-30 15:53:21'),(3,1,'Tarjeta de Débito',1,'2026-06-30 15:53:21','2026-06-30 15:53:21'),(4,1,'Tarjeta de Crédito',1,'2026-06-30 15:53:21','2026-06-30 15:53:21'),(5,1,'Cheque',1,'2026-06-30 15:53:21','2026-06-30 15:53:21');
/*!40000 ALTER TABLE `metodos_pago` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2024_01_01_000001_crear_tabla_categorias',1),(5,'2024_01_01_000002_crear_tabla_unidades',1),(6,'2024_01_01_000003_crear_tabla_impuestos',1),(7,'2024_01_01_000004_crear_tabla_ubicaciones',1),(8,'2024_01_01_000005_crear_tabla_productos',1),(9,'2024_01_01_000006_crear_tabla_movimientos_stock',1),(10,'2024_01_01_000007_crear_tabla_clientes',1),(11,'2024_01_01_000008_crear_tabla_proveedores',1),(12,'2024_01_01_000009_crear_tabla_terminos_pago',1),(13,'2024_01_01_000010_crear_tabla_pedidos_venta',1),(14,'2024_01_01_000011_crear_tabla_detalle_pedidos_venta',1),(15,'2024_01_01_000012_crear_tabla_facturas',1),(16,'2024_01_01_000013_crear_tabla_pagos',1),(17,'2024_01_01_000014_crear_tabla_envios',1),(18,'2024_01_01_000015_crear_tabla_compras',1),(19,'2024_01_01_000016_crear_tabla_traslados_stock',1),(20,'2026_06_30_102005_create_permission_tables',2),(21,'2026_06_30_102006_create_activity_log_table',2),(22,'2026_06_30_102007_add_event_column_to_activity_log_table',2),(23,'2026_06_30_102008_add_batch_uuid_column_to_activity_log_table',3),(24,'2026_06_30_103000_agregar_campos_electronicos_facturas',4),(25,'2026_06_30_103001_crear_tabla_notas_credito',4),(26,'2026_06_30_103002_crear_tabla_notas_remision',4),(27,'2026_06_30_110000_crear_tabla_presupuestos',5),(28,'2026_07_01_000001_crear_tabla_empresas',6),(29,'2026_07_01_000002_crear_tabla_sucursales',6),(30,'2026_07_01_000003_agregar_empresa_a_usuarios',6),(31,'2026_07_01_000004_agregar_empresa_a_catalogos',6),(32,'2026_07_01_000005_agregar_empresa_a_documentos',6),(33,'2026_07_01_000006_corregir_unicidad_documentos',7),(34,'2026_07_01_000010_agregar_descuento_global_documentos',8);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `model_has_permissions`
--

DROP TABLE IF EXISTS `model_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `model_has_permissions`
--

LOCK TABLES `model_has_permissions` WRITE;
/*!40000 ALTER TABLE `model_has_permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `model_has_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `model_has_roles`
--

DROP TABLE IF EXISTS `model_has_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `model_has_roles`
--

LOCK TABLES `model_has_roles` WRITE;
/*!40000 ALTER TABLE `model_has_roles` DISABLE KEYS */;
INSERT INTO `model_has_roles` VALUES (1,'App\\Models\\User',1),(1,'App\\Models\\User',5),(2,'App\\Models\\User',2),(3,'App\\Models\\User',3),(4,'App\\Models\\User',4);
/*!40000 ALTER TABLE `model_has_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `movimientos_stock`
--

DROP TABLE IF EXISTS `movimientos_stock`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `movimientos_stock` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `empresa_id` bigint(20) unsigned DEFAULT NULL,
  `producto_id` bigint(20) unsigned NOT NULL,
  `ubicacion_id` bigint(20) unsigned NOT NULL,
  `usuario_id` bigint(20) unsigned NOT NULL,
  `cantidad` decimal(12,2) NOT NULL,
  `tipo` enum('entrada','salida','ajuste','traslado') COLLATE utf8mb4_unicode_ci NOT NULL,
  `referencia` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notas` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fecha_movimiento` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `movimientos_stock_producto_id_foreign` (`producto_id`),
  KEY `movimientos_stock_ubicacion_id_foreign` (`ubicacion_id`),
  KEY `movimientos_stock_usuario_id_foreign` (`usuario_id`),
  KEY `movimientos_stock_empresa_id_foreign` (`empresa_id`),
  CONSTRAINT `movimientos_stock_empresa_id_foreign` FOREIGN KEY (`empresa_id`) REFERENCES `empresas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `movimientos_stock_producto_id_foreign` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`) ON DELETE CASCADE,
  CONSTRAINT `movimientos_stock_ubicacion_id_foreign` FOREIGN KEY (`ubicacion_id`) REFERENCES `ubicaciones` (`id`) ON DELETE CASCADE,
  CONSTRAINT `movimientos_stock_usuario_id_foreign` FOREIGN KEY (`usuario_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `movimientos_stock`
--

LOCK TABLES `movimientos_stock` WRITE;
/*!40000 ALTER TABLE `movimientos_stock` DISABLE KEYS */;
INSERT INTO `movimientos_stock` VALUES (5,1,1,1,1,50.00,'entrada','Stock inicial','Carga inicial de inventario','2026-06-30 18:24:45','2026-06-30 18:24:45','2026-06-30 18:24:45'),(6,1,2,1,1,50.00,'entrada','Stock inicial','Carga inicial de inventario','2026-06-30 18:24:45','2026-06-30 18:24:45','2026-06-30 18:24:45'),(7,1,3,1,1,50.00,'entrada','Stock inicial','Carga inicial de inventario','2026-06-30 18:24:45','2026-06-30 18:24:45','2026-06-30 18:24:45'),(8,1,4,1,1,50.00,'entrada','Stock inicial','Carga inicial de inventario','2026-06-30 18:24:45','2026-06-30 18:24:45','2026-06-30 18:24:45'),(9,1,5,1,1,50.00,'entrada','Stock inicial','Carga inicial de inventario','2026-06-30 18:24:45','2026-06-30 18:24:45','2026-06-30 18:24:45'),(10,NULL,2,1,1,-2.00,'salida','PV-000001','Venta facturada','2026-06-27 19:12:59','2026-06-30 19:12:59','2026-06-30 19:12:59'),(11,NULL,1,1,1,-1.00,'salida','PV-000003','Venta facturada','2026-06-27 19:12:59','2026-06-30 19:12:59','2026-06-30 19:12:59'),(12,NULL,1,1,1,-1.00,'salida','PV-000005','Venta facturada','2026-06-27 19:12:59','2026-06-30 19:12:59','2026-06-30 19:12:59'),(13,NULL,2,1,1,-5.00,'salida','PV-000005','Venta facturada','2026-06-27 19:12:59','2026-06-30 19:12:59','2026-06-30 19:12:59'),(14,NULL,1,1,1,10.00,'entrada','PC-000001','Recepcion - Importadora TechPro','2026-06-25 19:13:00','2026-06-30 19:13:00','2026-06-30 19:13:00'),(15,NULL,2,1,1,30.00,'entrada','PC-000001','Recepcion - Importadora TechPro','2026-06-25 19:13:00','2026-06-30 19:13:00','2026-06-30 19:13:00'),(16,NULL,5,1,1,25.00,'entrada','PC-000001','Recepcion - Importadora TechPro','2026-06-25 19:13:00','2026-06-30 19:13:00','2026-06-30 19:13:00'),(17,NULL,4,1,1,200.00,'entrada','PC-000002','Recepcion parcial - Agroexport Sur','2026-06-27 19:13:01','2026-06-30 19:13:01','2026-06-30 19:13:01'),(18,NULL,3,1,1,50.00,'entrada','PC-000002','Recepcion parcial - Agroexport Sur','2026-06-27 19:13:01','2026-06-30 19:13:01','2026-06-30 19:13:01'),(19,NULL,5,1,1,25.00,'entrada','PC-000002','Recepcion parcial - Agroexport Sur','2026-06-27 19:13:02','2026-06-30 19:13:02','2026-06-30 19:13:02'),(20,NULL,2,1,1,-8.00,'traslado','Traslado #2','Salida Almacen Principal','2026-06-28 19:13:02','2026-06-30 19:13:02','2026-06-30 19:13:02'),(21,NULL,2,3,1,8.00,'traslado','Traslado #2','Entrada Tienda Principal','2026-06-28 19:13:02','2026-06-30 19:13:02','2026-06-30 19:13:02'),(22,NULL,3,1,1,-15.00,'traslado','Traslado #2','Salida Almacen Principal','2026-06-28 19:13:02','2026-06-30 19:13:02','2026-06-30 19:13:02'),(23,NULL,3,3,1,15.00,'traslado','Traslado #2','Entrada Tienda Principal','2026-06-28 19:13:02','2026-06-30 19:13:02','2026-06-30 19:13:02'),(24,NULL,4,1,1,-50.00,'traslado','Traslado #2','Salida Almacen Principal','2026-06-28 19:13:02','2026-06-30 19:13:02','2026-06-30 19:13:02'),(25,NULL,4,3,1,50.00,'traslado','Traslado #2','Entrada Tienda Principal','2026-06-28 19:13:02','2026-06-30 19:13:02','2026-06-30 19:13:02');
/*!40000 ALTER TABLE `movimientos_stock` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notas_credito`
--

DROP TABLE IF EXISTS `notas_credito`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notas_credito` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `empresa_id` bigint(20) unsigned DEFAULT NULL,
  `sucursal_id` bigint(20) unsigned DEFAULT NULL,
  `factura_id` bigint(20) unsigned NOT NULL,
  `usuario_id` bigint(20) unsigned NOT NULL,
  `numero_documento` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `timbrado` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `establecimiento` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '001',
  `punto_expedicion` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '001',
  `cdc` varchar(44) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `modo` enum('local','electronico') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'local',
  `fecha_emision` date NOT NULL,
  `motivo` enum('devolucion_total','devolucion_parcial','descuento','anulacion','otro') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'devolucion_parcial',
  `descripcion_motivo` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subtotal` decimal(12,2) NOT NULL DEFAULT 0.00,
  `impuesto_total` decimal(12,2) NOT NULL DEFAULT 0.00,
  `total` decimal(12,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_negocio_notas_credito` (`empresa_id`,`establecimiento`,`punto_expedicion`,`numero_documento`),
  KEY `notas_credito_factura_id_foreign` (`factura_id`),
  KEY `notas_credito_usuario_id_foreign` (`usuario_id`),
  KEY `notas_credito_sucursal_id_foreign` (`sucursal_id`),
  CONSTRAINT `notas_credito_empresa_id_foreign` FOREIGN KEY (`empresa_id`) REFERENCES `empresas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `notas_credito_factura_id_foreign` FOREIGN KEY (`factura_id`) REFERENCES `facturas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `notas_credito_sucursal_id_foreign` FOREIGN KEY (`sucursal_id`) REFERENCES `sucursales` (`id`) ON DELETE SET NULL,
  CONSTRAINT `notas_credito_usuario_id_foreign` FOREIGN KEY (`usuario_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notas_credito`
--

LOCK TABLES `notas_credito` WRITE;
/*!40000 ALTER TABLE `notas_credito` DISABLE KEYS */;
/*!40000 ALTER TABLE `notas_credito` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notas_remision`
--

DROP TABLE IF EXISTS `notas_remision`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notas_remision` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `empresa_id` bigint(20) unsigned DEFAULT NULL,
  `sucursal_id` bigint(20) unsigned DEFAULT NULL,
  `pedido_id` bigint(20) unsigned DEFAULT NULL,
  `envio_id` bigint(20) unsigned DEFAULT NULL,
  `usuario_id` bigint(20) unsigned NOT NULL,
  `ubicacion_origen_id` bigint(20) unsigned DEFAULT NULL,
  `numero_documento` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `timbrado` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `establecimiento` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '001',
  `punto_expedicion` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '001',
  `cdc` varchar(44) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `modo` enum('local','electronico') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'local',
  `fecha_emision` date NOT NULL,
  `motivo` enum('venta','consignacion','traslado','devolucion','otro') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'venta',
  `direccion_destino` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `transportista` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vehiculo_placa` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `observaciones` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_negocio_notas_remision` (`empresa_id`,`establecimiento`,`punto_expedicion`,`numero_documento`),
  KEY `notas_remision_pedido_id_foreign` (`pedido_id`),
  KEY `notas_remision_envio_id_foreign` (`envio_id`),
  KEY `notas_remision_usuario_id_foreign` (`usuario_id`),
  KEY `notas_remision_ubicacion_origen_id_foreign` (`ubicacion_origen_id`),
  KEY `notas_remision_sucursal_id_foreign` (`sucursal_id`),
  CONSTRAINT `notas_remision_empresa_id_foreign` FOREIGN KEY (`empresa_id`) REFERENCES `empresas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `notas_remision_envio_id_foreign` FOREIGN KEY (`envio_id`) REFERENCES `envios` (`id`) ON DELETE SET NULL,
  CONSTRAINT `notas_remision_pedido_id_foreign` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos_venta` (`id`) ON DELETE SET NULL,
  CONSTRAINT `notas_remision_sucursal_id_foreign` FOREIGN KEY (`sucursal_id`) REFERENCES `sucursales` (`id`) ON DELETE SET NULL,
  CONSTRAINT `notas_remision_ubicacion_origen_id_foreign` FOREIGN KEY (`ubicacion_origen_id`) REFERENCES `ubicaciones` (`id`) ON DELETE SET NULL,
  CONSTRAINT `notas_remision_usuario_id_foreign` FOREIGN KEY (`usuario_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notas_remision`
--

LOCK TABLES `notas_remision` WRITE;
/*!40000 ALTER TABLE `notas_remision` DISABLE KEYS */;
INSERT INTO `notas_remision` VALUES (3,NULL,NULL,5,NULL,1,1,'0000001','18174154','001','001',NULL,'local','2026-06-22','venta','Avda. Mariscal Lopez 1234, Asuncion','Transporte Propio',NULL,NULL,'2026-06-30 19:13:02','2026-06-30 19:13:02');
/*!40000 ALTER TABLE `notas_remision` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pagos`
--

DROP TABLE IF EXISTS `pagos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pagos` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `empresa_id` bigint(20) unsigned DEFAULT NULL,
  `pedido_id` bigint(20) unsigned NOT NULL,
  `factura_id` bigint(20) unsigned DEFAULT NULL,
  `usuario_id` bigint(20) unsigned NOT NULL,
  `metodo_pago_id` bigint(20) unsigned DEFAULT NULL,
  `monto` decimal(12,2) NOT NULL,
  `fecha_pago` date NOT NULL,
  `referencia` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notas` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pagos_pedido_id_foreign` (`pedido_id`),
  KEY `pagos_factura_id_foreign` (`factura_id`),
  KEY `pagos_usuario_id_foreign` (`usuario_id`),
  KEY `pagos_metodo_pago_id_foreign` (`metodo_pago_id`),
  KEY `pagos_empresa_id_foreign` (`empresa_id`),
  CONSTRAINT `pagos_empresa_id_foreign` FOREIGN KEY (`empresa_id`) REFERENCES `empresas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `pagos_factura_id_foreign` FOREIGN KEY (`factura_id`) REFERENCES `facturas` (`id`) ON DELETE SET NULL,
  CONSTRAINT `pagos_metodo_pago_id_foreign` FOREIGN KEY (`metodo_pago_id`) REFERENCES `metodos_pago` (`id`) ON DELETE SET NULL,
  CONSTRAINT `pagos_pedido_id_foreign` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos_venta` (`id`) ON DELETE CASCADE,
  CONSTRAINT `pagos_usuario_id_foreign` FOREIGN KEY (`usuario_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pagos`
--

LOCK TABLES `pagos` WRITE;
/*!40000 ALTER TABLE `pagos` DISABLE KEYS */;
INSERT INTO `pagos` VALUES (2,NULL,5,12,1,1,150000.00,'2026-06-22','Pago efectivo mostrador',NULL,'2026-06-30 19:12:59','2026-06-30 19:12:59'),(3,NULL,7,13,1,2,2000000.00,'2026-06-25','TRANSF-20260625-001','Anticipo 40%. Saldo Gs. 3.200.000 a 15 dias.','2026-06-30 19:12:59','2026-06-30 19:12:59');
/*!40000 ALTER TABLE `pagos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
INSERT INTO `password_reset_tokens` VALUES ('admin@inventario.com','$2y$12$LMOuJjdMQitdF3FhPoMUUetCVOBjYbG21AnvK2W.uqNVPsWxL/H.6','2026-06-30 20:17:07');
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pedidos_compra`
--

DROP TABLE IF EXISTS `pedidos_compra`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pedidos_compra` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `empresa_id` bigint(20) unsigned DEFAULT NULL,
  `sucursal_id` bigint(20) unsigned DEFAULT NULL,
  `proveedor_id` bigint(20) unsigned NOT NULL,
  `usuario_id` bigint(20) unsigned NOT NULL,
  `ubicacion_id` bigint(20) unsigned DEFAULT NULL,
  `numero_referencia` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `comentarios` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fecha_pedido` date NOT NULL,
  `fecha_esperada` date DEFAULT NULL,
  `total` decimal(12,2) NOT NULL DEFAULT 0.00,
  `impuesto_incluido` tinyint(1) NOT NULL DEFAULT 0,
  `estado` enum('pendiente','parcial','completado','cancelado') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pendiente',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_negocio_pedidos_compra` (`empresa_id`,`sucursal_id`,`numero_referencia`),
  KEY `pedidos_compra_proveedor_id_foreign` (`proveedor_id`),
  KEY `pedidos_compra_usuario_id_foreign` (`usuario_id`),
  KEY `pedidos_compra_ubicacion_id_foreign` (`ubicacion_id`),
  KEY `pedidos_compra_sucursal_id_foreign` (`sucursal_id`),
  CONSTRAINT `pedidos_compra_empresa_id_foreign` FOREIGN KEY (`empresa_id`) REFERENCES `empresas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `pedidos_compra_proveedor_id_foreign` FOREIGN KEY (`proveedor_id`) REFERENCES `proveedores` (`id`) ON DELETE CASCADE,
  CONSTRAINT `pedidos_compra_sucursal_id_foreign` FOREIGN KEY (`sucursal_id`) REFERENCES `sucursales` (`id`) ON DELETE SET NULL,
  CONSTRAINT `pedidos_compra_ubicacion_id_foreign` FOREIGN KEY (`ubicacion_id`) REFERENCES `ubicaciones` (`id`) ON DELETE SET NULL,
  CONSTRAINT `pedidos_compra_usuario_id_foreign` FOREIGN KEY (`usuario_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pedidos_compra`
--

LOCK TABLES `pedidos_compra` WRITE;
/*!40000 ALTER TABLE `pedidos_compra` DISABLE KEYS */;
INSERT INTO `pedidos_compra` VALUES (2,NULL,NULL,1,1,1,'PC-000001','Reposicion mensual equipos','2026-06-15','2026-06-25',42350000.00,0,'completado','2026-06-30 19:12:59','2026-06-30 19:12:59'),(3,NULL,NULL,3,1,1,'PC-000002',NULL,'2026-06-23','2026-07-03',5437500.00,0,'parcial','2026-06-30 19:13:00','2026-06-30 19:13:00');
/*!40000 ALTER TABLE `pedidos_compra` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pedidos_venta`
--

DROP TABLE IF EXISTS `pedidos_venta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pedidos_venta` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `empresa_id` bigint(20) unsigned DEFAULT NULL,
  `sucursal_id` bigint(20) unsigned DEFAULT NULL,
  `cliente_id` bigint(20) unsigned NOT NULL,
  `usuario_id` bigint(20) unsigned NOT NULL,
  `ubicacion_id` bigint(20) unsigned DEFAULT NULL,
  `termino_pago_id` bigint(20) unsigned DEFAULT NULL,
  `numero_referencia` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `referencia_cliente` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `comentarios` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fecha_pedido` date NOT NULL,
  `fecha_entrega` date DEFAULT NULL,
  `direccion_entrega` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telefono_contacto` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_contacto` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total` decimal(12,2) NOT NULL DEFAULT 0.00,
  `descuento_global` decimal(5,2) NOT NULL DEFAULT 0.00,
  `monto_descuento` decimal(12,2) NOT NULL DEFAULT 0.00,
  `monto_pagado` decimal(12,2) NOT NULL DEFAULT 0.00,
  `estado_factura` enum('pendiente','parcial','completado') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pendiente',
  `estado` enum('activo','cancelado','completado') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'activo',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_negocio_pedidos_venta` (`empresa_id`,`sucursal_id`,`numero_referencia`),
  KEY `pedidos_venta_cliente_id_foreign` (`cliente_id`),
  KEY `pedidos_venta_usuario_id_foreign` (`usuario_id`),
  KEY `pedidos_venta_ubicacion_id_foreign` (`ubicacion_id`),
  KEY `pedidos_venta_termino_pago_id_foreign` (`termino_pago_id`),
  KEY `pedidos_venta_sucursal_id_foreign` (`sucursal_id`),
  CONSTRAINT `pedidos_venta_cliente_id_foreign` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `pedidos_venta_empresa_id_foreign` FOREIGN KEY (`empresa_id`) REFERENCES `empresas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `pedidos_venta_sucursal_id_foreign` FOREIGN KEY (`sucursal_id`) REFERENCES `sucursales` (`id`) ON DELETE SET NULL,
  CONSTRAINT `pedidos_venta_termino_pago_id_foreign` FOREIGN KEY (`termino_pago_id`) REFERENCES `terminos_pago` (`id`) ON DELETE SET NULL,
  CONSTRAINT `pedidos_venta_ubicacion_id_foreign` FOREIGN KEY (`ubicacion_id`) REFERENCES `ubicaciones` (`id`) ON DELETE SET NULL,
  CONSTRAINT `pedidos_venta_usuario_id_foreign` FOREIGN KEY (`usuario_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pedidos_venta`
--

LOCK TABLES `pedidos_venta` WRITE;
/*!40000 ALTER TABLE `pedidos_venta` DISABLE KEYS */;
INSERT INTO `pedidos_venta` VALUES (4,NULL,NULL,1,1,NULL,NULL,'PV-000001',NULL,NULL,'2026-06-30',NULL,NULL,NULL,NULL,150000.00,0.00,0.00,0.00,'completado','activo','2026-06-30 18:28:49','2026-06-30 18:28:50'),(5,NULL,NULL,1,1,1,1,'PV-000001',NULL,NULL,'2026-06-22',NULL,NULL,NULL,NULL,150000.00,0.00,0.00,150000.00,'completado','completado','2026-06-30 19:12:58','2026-06-30 19:12:58'),(6,NULL,NULL,2,1,1,2,'PV-000002',NULL,'Entrega en domicilio','2026-06-27','2026-07-05',NULL,NULL,NULL,2364000.00,0.00,0.00,0.00,'pendiente','activo','2026-06-30 19:12:58','2026-06-30 19:12:58'),(7,NULL,NULL,3,1,1,2,'PV-000003',NULL,NULL,'2026-06-24',NULL,NULL,NULL,NULL,5200000.00,0.00,0.00,2000000.00,'completado','activo','2026-06-30 19:12:58','2026-06-30 19:12:58'),(8,NULL,NULL,4,1,1,1,'PV-000004',NULL,NULL,'2026-06-30',NULL,NULL,NULL,NULL,32500.00,0.00,0.00,0.00,'pendiente','activo','2026-06-30 19:12:58','2026-06-30 19:12:58'),(9,NULL,NULL,6,1,1,2,'PV-000005',NULL,NULL,'2026-06-28',NULL,NULL,NULL,NULL,5575000.00,0.00,0.00,0.00,'completado','activo','2026-06-30 19:12:58','2026-06-30 19:12:58');
/*!40000 ALTER TABLE `pedidos_venta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `permissions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permissions`
--

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
INSERT INTO `permissions` VALUES (1,'productos.ver','web','2026-06-30 13:22:44','2026-06-30 13:22:44'),(2,'productos.crear','web','2026-06-30 13:22:44','2026-06-30 13:22:44'),(3,'productos.editar','web','2026-06-30 13:22:44','2026-06-30 13:22:44'),(4,'productos.eliminar','web','2026-06-30 13:22:44','2026-06-30 13:22:44'),(5,'categorias.ver','web','2026-06-30 13:22:44','2026-06-30 13:22:44'),(6,'categorias.crear','web','2026-06-30 13:22:44','2026-06-30 13:22:44'),(7,'categorias.editar','web','2026-06-30 13:22:44','2026-06-30 13:22:44'),(8,'categorias.eliminar','web','2026-06-30 13:22:44','2026-06-30 13:22:44'),(9,'clientes.ver','web','2026-06-30 13:22:44','2026-06-30 13:22:44'),(10,'clientes.crear','web','2026-06-30 13:22:44','2026-06-30 13:22:44'),(11,'clientes.editar','web','2026-06-30 13:22:44','2026-06-30 13:22:44'),(12,'clientes.eliminar','web','2026-06-30 13:22:44','2026-06-30 13:22:44'),(13,'proveedores.ver','web','2026-06-30 13:22:44','2026-06-30 13:22:44'),(14,'proveedores.crear','web','2026-06-30 13:22:44','2026-06-30 13:22:44'),(15,'proveedores.editar','web','2026-06-30 13:22:44','2026-06-30 13:22:44'),(16,'proveedores.eliminar','web','2026-06-30 13:22:44','2026-06-30 13:22:44'),(17,'pedidos.ver','web','2026-06-30 13:22:44','2026-06-30 13:22:44'),(18,'pedidos.crear','web','2026-06-30 13:22:44','2026-06-30 13:22:44'),(19,'pedidos.editar','web','2026-06-30 13:22:44','2026-06-30 13:22:44'),(20,'pedidos.eliminar','web','2026-06-30 13:22:44','2026-06-30 13:22:44'),(21,'compras.ver','web','2026-06-30 13:22:44','2026-06-30 13:22:44'),(22,'compras.crear','web','2026-06-30 13:22:44','2026-06-30 13:22:44'),(23,'compras.editar','web','2026-06-30 13:22:44','2026-06-30 13:22:44'),(24,'compras.eliminar','web','2026-06-30 13:22:45','2026-06-30 13:22:45'),(25,'facturas.ver','web','2026-06-30 13:22:45','2026-06-30 13:22:45'),(26,'facturas.crear','web','2026-06-30 13:22:45','2026-06-30 13:22:45'),(27,'facturas.editar','web','2026-06-30 13:22:45','2026-06-30 13:22:45'),(28,'facturas.eliminar','web','2026-06-30 13:22:45','2026-06-30 13:22:45'),(29,'pagos.ver','web','2026-06-30 13:22:45','2026-06-30 13:22:45'),(30,'pagos.crear','web','2026-06-30 13:22:45','2026-06-30 13:22:45'),(31,'pagos.editar','web','2026-06-30 13:22:45','2026-06-30 13:22:45'),(32,'pagos.eliminar','web','2026-06-30 13:22:45','2026-06-30 13:22:45'),(33,'envios.ver','web','2026-06-30 13:22:45','2026-06-30 13:22:45'),(34,'envios.crear','web','2026-06-30 13:22:45','2026-06-30 13:22:45'),(35,'envios.editar','web','2026-06-30 13:22:45','2026-06-30 13:22:45'),(36,'envios.eliminar','web','2026-06-30 13:22:45','2026-06-30 13:22:45'),(37,'reportes.ver','web','2026-06-30 13:22:45','2026-06-30 13:22:45'),(38,'reportes.crear','web','2026-06-30 13:22:45','2026-06-30 13:22:45'),(39,'reportes.editar','web','2026-06-30 13:22:45','2026-06-30 13:22:45'),(40,'reportes.eliminar','web','2026-06-30 13:22:45','2026-06-30 13:22:45'),(41,'usuarios.ver','web','2026-06-30 13:22:45','2026-06-30 13:22:45'),(42,'usuarios.crear','web','2026-06-30 13:22:45','2026-06-30 13:22:45'),(43,'usuarios.editar','web','2026-06-30 13:22:45','2026-06-30 13:22:45'),(44,'usuarios.eliminar','web','2026-06-30 13:22:45','2026-06-30 13:22:45'),(45,'configuracion.ver','web','2026-06-30 13:22:45','2026-06-30 13:22:45'),(46,'configuracion.crear','web','2026-06-30 13:22:45','2026-06-30 13:22:45'),(47,'configuracion.editar','web','2026-06-30 13:22:45','2026-06-30 13:22:45'),(48,'configuracion.eliminar','web','2026-06-30 13:22:45','2026-06-30 13:22:45'),(49,'productos.ver_costos','web','2026-06-30 13:22:45','2026-06-30 13:22:45'),(50,'reportes.compras','web','2026-06-30 13:22:45','2026-06-30 13:22:45'),(51,'reportes.exportar','web','2026-06-30 13:22:45','2026-06-30 13:22:45');
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `presupuestos`
--

DROP TABLE IF EXISTS `presupuestos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `presupuestos` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `empresa_id` bigint(20) unsigned DEFAULT NULL,
  `sucursal_id` bigint(20) unsigned DEFAULT NULL,
  `cliente_id` bigint(20) unsigned NOT NULL,
  `usuario_id` bigint(20) unsigned NOT NULL,
  `pedido_id` bigint(20) unsigned DEFAULT NULL,
  `numero_documento` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha_emision` date NOT NULL,
  `fecha_validez` date DEFAULT NULL,
  `comentarios` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subtotal` decimal(12,2) NOT NULL DEFAULT 0.00,
  `impuesto_total` decimal(12,2) NOT NULL DEFAULT 0.00,
  `total` decimal(12,2) NOT NULL DEFAULT 0.00,
  `descuento_global` decimal(5,2) NOT NULL DEFAULT 0.00,
  `monto_descuento` decimal(12,2) NOT NULL DEFAULT 0.00,
  `estado` enum('pendiente','aprobado','rechazado','vencido','convertido') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pendiente',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pk_negocio_presupuestos` (`empresa_id`,`sucursal_id`,`numero_documento`),
  KEY `presupuestos_cliente_id_foreign` (`cliente_id`),
  KEY `presupuestos_usuario_id_foreign` (`usuario_id`),
  KEY `presupuestos_pedido_id_foreign` (`pedido_id`),
  KEY `presupuestos_sucursal_id_foreign` (`sucursal_id`),
  CONSTRAINT `presupuestos_cliente_id_foreign` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `presupuestos_empresa_id_foreign` FOREIGN KEY (`empresa_id`) REFERENCES `empresas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `presupuestos_pedido_id_foreign` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos_venta` (`id`) ON DELETE SET NULL,
  CONSTRAINT `presupuestos_sucursal_id_foreign` FOREIGN KEY (`sucursal_id`) REFERENCES `sucursales` (`id`) ON DELETE SET NULL,
  CONSTRAINT `presupuestos_usuario_id_foreign` FOREIGN KEY (`usuario_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `presupuestos`
--

LOCK TABLES `presupuestos` WRITE;
/*!40000 ALTER TABLE `presupuestos` DISABLE KEYS */;
INSERT INTO `presupuestos` VALUES (2,NULL,NULL,1,1,NULL,'PRE-000001','2026-06-20','2026-07-20','Cotizacion equipo informatico',5275000.00,0.00,5275000.00,0.00,0.00,'pendiente','2026-06-30 19:12:57','2026-06-30 19:12:57'),(3,NULL,NULL,2,1,NULL,'PRE-000002','2026-06-25','2026-07-25','Pedido mensual accesorios y ropa',1225000.00,0.00,1225000.00,0.00,0.00,'aprobado','2026-06-30 19:12:57','2026-06-30 19:12:57');
/*!40000 ALTER TABLE `presupuestos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `productos`
--

DROP TABLE IF EXISTS `productos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `productos` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `empresa_id` bigint(20) unsigned DEFAULT NULL,
  `codigo` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `categoria_id` bigint(20) unsigned DEFAULT NULL,
  `unidad_id` bigint(20) unsigned DEFAULT NULL,
  `impuesto_id` bigint(20) unsigned DEFAULT NULL,
  `imagen` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `precio_compra` decimal(12,2) NOT NULL DEFAULT 0.00,
  `precio_venta_minorista` decimal(12,2) NOT NULL DEFAULT 0.00,
  `precio_venta_mayorista` decimal(12,2) NOT NULL DEFAULT 0.00,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `productos_codigo_unique` (`codigo`),
  KEY `productos_categoria_id_foreign` (`categoria_id`),
  KEY `productos_unidad_id_foreign` (`unidad_id`),
  KEY `productos_impuesto_id_foreign` (`impuesto_id`),
  KEY `productos_empresa_id_foreign` (`empresa_id`),
  CONSTRAINT `productos_categoria_id_foreign` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`) ON DELETE SET NULL,
  CONSTRAINT `productos_empresa_id_foreign` FOREIGN KEY (`empresa_id`) REFERENCES `empresas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `productos_impuesto_id_foreign` FOREIGN KEY (`impuesto_id`) REFERENCES `impuestos` (`id`) ON DELETE SET NULL,
  CONSTRAINT `productos_unidad_id_foreign` FOREIGN KEY (`unidad_id`) REFERENCES `unidades` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `productos`
--

LOCK TABLES `productos` WRITE;
/*!40000 ALTER TABLE `productos` DISABLE KEYS */;
INSERT INTO `productos` VALUES (1,1,'LAPTOP-001','Laptop HP 14\"',NULL,1,1,2,NULL,3800000.00,5200000.00,4800000.00,1,NULL,'2026-06-28 22:21:28','2026-06-30 13:29:23'),(2,1,'MOUSE-001','Mouse Inalámbrico',NULL,1,1,2,NULL,45000.00,75000.00,65000.00,1,NULL,'2026-06-28 22:21:28','2026-06-28 22:21:28'),(3,1,'CAMISA-001','Camisa Polo Talla M',NULL,2,1,1,NULL,50000.00,95000.00,80000.00,1,NULL,'2026-06-28 22:21:28','2026-06-28 22:21:28'),(4,1,'ARROZ-001','Arroz 1kg',NULL,3,2,3,NULL,4500.00,6500.00,5800.00,1,NULL,'2026-06-28 22:21:28','2026-06-28 22:21:28'),(5,1,'PAPEL-001','Resma Papel A4',NULL,6,4,2,NULL,28000.00,42000.00,38000.00,1,NULL,'2026-06-28 22:21:29','2026-06-28 22:21:29'),(6,NULL,'EMP2-001','Producto exclusivo Empresa 2',NULL,NULL,NULL,NULL,NULL,10.00,15.00,12.00,1,'2026-06-30 17:53:35','2026-06-30 17:51:05','2026-06-30 17:53:35'),(7,2,'EMP2-REAL','Producto Real Empresa 2',NULL,NULL,NULL,NULL,NULL,5.00,8.00,7.00,1,NULL,'2026-06-30 17:52:41','2026-06-30 17:52:41');
/*!40000 ALTER TABLE `productos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `proveedores`
--

DROP TABLE IF EXISTS `proveedores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `proveedores` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `empresa_id` bigint(20) unsigned DEFAULT NULL,
  `nombre` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telefono` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `direccion` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ruc_nit` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contacto` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `proveedores_empresa_id_foreign` (`empresa_id`),
  CONSTRAINT `proveedores_empresa_id_foreign` FOREIGN KEY (`empresa_id`) REFERENCES `empresas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `proveedores`
--

LOCK TABLES `proveedores` WRITE;
/*!40000 ALTER TABLE `proveedores` DISABLE KEYS */;
INSERT INTO `proveedores` VALUES (1,1,'Importadora TechPro','ventas@techpro.com','022111222',NULL,NULL,NULL,1,NULL,'2026-06-28 22:21:29','2026-06-28 22:21:29'),(2,1,'Distribuidora Nacional','info@distnac.com','023333444',NULL,NULL,NULL,1,NULL,'2026-06-28 22:21:29','2026-06-28 22:21:29'),(3,1,'Agroexport Sur','compras@agroexport.com','075556677',NULL,NULL,NULL,1,NULL,'2026-06-28 22:21:29','2026-06-28 22:21:29');
/*!40000 ALTER TABLE `proveedores` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `recepciones_compra`
--

DROP TABLE IF EXISTS `recepciones_compra`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `recepciones_compra` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `pedido_compra_id` bigint(20) unsigned NOT NULL,
  `usuario_id` bigint(20) unsigned NOT NULL,
  `fecha_recepcion` date NOT NULL,
  `numero_referencia` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notas` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `recepciones_compra_pedido_compra_id_foreign` (`pedido_compra_id`),
  KEY `recepciones_compra_usuario_id_foreign` (`usuario_id`),
  CONSTRAINT `recepciones_compra_pedido_compra_id_foreign` FOREIGN KEY (`pedido_compra_id`) REFERENCES `pedidos_compra` (`id`) ON DELETE CASCADE,
  CONSTRAINT `recepciones_compra_usuario_id_foreign` FOREIGN KEY (`usuario_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `recepciones_compra`
--

LOCK TABLES `recepciones_compra` WRITE;
/*!40000 ALTER TABLE `recepciones_compra` DISABLE KEYS */;
INSERT INTO `recepciones_compra` VALUES (1,2,1,'2026-06-25','FAC-PROV-2026-0541','Recibido conforme.','2026-06-30 19:13:00','2026-06-30 19:13:00'),(2,3,1,'2026-06-27',NULL,'Primera entrega parcial. Arroz pendiente 300kg.','2026-06-30 19:13:01','2026-06-30 19:13:01');
/*!40000 ALTER TABLE `recepciones_compra` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role_has_permissions`
--

DROP TABLE IF EXISTS `role_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) unsigned NOT NULL,
  `role_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role_has_permissions`
--

LOCK TABLES `role_has_permissions` WRITE;
/*!40000 ALTER TABLE `role_has_permissions` DISABLE KEYS */;
INSERT INTO `role_has_permissions` VALUES (1,1),(1,2),(1,3),(1,4),(2,1),(2,3),(3,1),(3,3),(4,1),(5,1),(5,2),(5,3),(6,1),(6,3),(7,1),(7,3),(8,1),(9,1),(9,2),(9,4),(10,1),(10,2),(11,1),(11,2),(12,1),(13,1),(13,3),(13,4),(14,1),(14,3),(15,1),(15,3),(16,1),(17,1),(17,2),(17,3),(17,4),(18,1),(18,2),(19,1),(19,2),(20,1),(21,1),(21,3),(21,4),(22,1),(22,3),(23,1),(23,3),(24,1),(25,1),(25,2),(25,4),(26,1),(26,2),(26,4),(27,1),(28,1),(29,1),(29,2),(29,4),(30,1),(30,2),(30,4),(31,1),(31,4),(32,1),(33,1),(33,2),(33,3),(34,1),(34,2),(34,3),(35,1),(35,3),(36,1),(37,1),(37,2),(37,3),(37,4),(38,1),(39,1),(40,1),(41,1),(42,1),(43,1),(44,1),(45,1),(45,4),(46,1),(47,1),(48,1),(49,1),(49,4),(50,1),(50,3),(50,4),(51,1),(51,4);
/*!40000 ALTER TABLE `role_has_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'admin','web','2026-06-30 13:22:45','2026-06-30 13:22:45'),(2,'vendedor','web','2026-06-30 13:22:46','2026-06-30 13:22:46'),(3,'bodeguero','web','2026-06-30 13:22:46','2026-06-30 13:22:46'),(4,'contador','web','2026-06-30 13:22:46','2026-06-30 13:22:46'),(5,'cajero','web','2026-06-30 17:28:03','2026-06-30 17:28:03');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sucursales`
--

DROP TABLE IF EXISTS `sucursales`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sucursales` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `empresa_id` bigint(20) unsigned NOT NULL,
  `codigo` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Código de establecimiento SIFEN (001, 002...)',
  `nombre` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `direccion` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ciudad` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telefono` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `principal` tinyint(1) NOT NULL DEFAULT 0,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `sucursales_empresa_id_codigo_unique` (`empresa_id`,`codigo`),
  CONSTRAINT `sucursales_empresa_id_foreign` FOREIGN KEY (`empresa_id`) REFERENCES `empresas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sucursales`
--

LOCK TABLES `sucursales` WRITE;
/*!40000 ALTER TABLE `sucursales` DISABLE KEYS */;
INSERT INTO `sucursales` VALUES (1,1,'001','Casa Matriz','Benjamin Aceval','Guarambare','0974223003',1,1,'2026-06-30 17:41:03','2026-06-30 17:41:03'),(2,2,'001','Casa Matriz',NULL,NULL,NULL,1,1,'2026-06-30 17:49:57','2026-06-30 17:49:57');
/*!40000 ALTER TABLE `sucursales` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `terminos_pago`
--

DROP TABLE IF EXISTS `terminos_pago`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `terminos_pago` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `empresa_id` bigint(20) unsigned DEFAULT NULL,
  `nombre` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dias` int(11) NOT NULL DEFAULT 0,
  `descripcion` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `terminos_pago_empresa_id_foreign` (`empresa_id`),
  CONSTRAINT `terminos_pago_empresa_id_foreign` FOREIGN KEY (`empresa_id`) REFERENCES `empresas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `terminos_pago`
--

LOCK TABLES `terminos_pago` WRITE;
/*!40000 ALTER TABLE `terminos_pago` DISABLE KEYS */;
INSERT INTO `terminos_pago` VALUES (1,1,'Contado',0,NULL,'2026-06-28 22:21:28','2026-06-28 22:21:28'),(2,1,'Crédito 15d',15,NULL,'2026-06-28 22:21:28','2026-06-28 22:21:28'),(3,1,'Crédito 30d',30,NULL,'2026-06-28 22:21:28','2026-06-28 22:21:28'),(4,1,'Crédito 60d',60,NULL,'2026-06-28 22:21:28','2026-06-28 22:21:28');
/*!40000 ALTER TABLE `terminos_pago` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `traslados_stock`
--

DROP TABLE IF EXISTS `traslados_stock`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `traslados_stock` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `empresa_id` bigint(20) unsigned DEFAULT NULL,
  `usuario_id` bigint(20) unsigned NOT NULL,
  `ubicacion_origen_id` bigint(20) unsigned NOT NULL,
  `ubicacion_destino_id` bigint(20) unsigned NOT NULL,
  `referencia` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notas` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fecha_traslado` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `traslados_stock_usuario_id_foreign` (`usuario_id`),
  KEY `traslados_stock_ubicacion_origen_id_foreign` (`ubicacion_origen_id`),
  KEY `traslados_stock_ubicacion_destino_id_foreign` (`ubicacion_destino_id`),
  KEY `traslados_stock_empresa_id_foreign` (`empresa_id`),
  CONSTRAINT `traslados_stock_empresa_id_foreign` FOREIGN KEY (`empresa_id`) REFERENCES `empresas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `traslados_stock_ubicacion_destino_id_foreign` FOREIGN KEY (`ubicacion_destino_id`) REFERENCES `ubicaciones` (`id`) ON DELETE CASCADE,
  CONSTRAINT `traslados_stock_ubicacion_origen_id_foreign` FOREIGN KEY (`ubicacion_origen_id`) REFERENCES `ubicaciones` (`id`) ON DELETE CASCADE,
  CONSTRAINT `traslados_stock_usuario_id_foreign` FOREIGN KEY (`usuario_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `traslados_stock`
--

LOCK TABLES `traslados_stock` WRITE;
/*!40000 ALTER TABLE `traslados_stock` DISABLE KEYS */;
INSERT INTO `traslados_stock` VALUES (2,NULL,1,1,3,'Reposicion mensual Tienda','Para punto de venta al publico','2026-06-28','2026-06-30 19:13:02','2026-06-30 19:13:02');
/*!40000 ALTER TABLE `traslados_stock` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ubicaciones`
--

DROP TABLE IF EXISTS `ubicaciones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ubicaciones` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `empresa_id` bigint(20) unsigned DEFAULT NULL,
  `sucursal_id` bigint(20) unsigned DEFAULT NULL,
  `codigo` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `direccion` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ubicaciones_codigo_unique` (`codigo`),
  KEY `ubicaciones_empresa_id_foreign` (`empresa_id`),
  KEY `ubicaciones_sucursal_id_foreign` (`sucursal_id`),
  CONSTRAINT `ubicaciones_empresa_id_foreign` FOREIGN KEY (`empresa_id`) REFERENCES `empresas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `ubicaciones_sucursal_id_foreign` FOREIGN KEY (`sucursal_id`) REFERENCES `sucursales` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ubicaciones`
--

LOCK TABLES `ubicaciones` WRITE;
/*!40000 ALTER TABLE `ubicaciones` DISABLE KEYS */;
INSERT INTO `ubicaciones` VALUES (1,1,1,'ALMACEN-01','Almacén Principal',NULL,1,'2026-06-28 22:21:27','2026-06-28 22:21:27'),(2,1,1,'ALMACEN-02','Almacén Secundario',NULL,1,'2026-06-28 22:21:28','2026-06-28 22:21:28'),(3,1,1,'TIENDA-01','Tienda Principal',NULL,1,'2026-06-28 22:21:28','2026-06-28 22:21:28'),(4,1,1,'BODEGA-01','Bodega Norte',NULL,1,'2026-06-28 22:21:28','2026-06-28 22:21:28');
/*!40000 ALTER TABLE `ubicaciones` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `unidades`
--

DROP TABLE IF EXISTS `unidades`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `unidades` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `empresa_id` bigint(20) unsigned DEFAULT NULL,
  `nombre` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abreviatura` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `unidades_empresa_id_foreign` (`empresa_id`),
  CONSTRAINT `unidades_empresa_id_foreign` FOREIGN KEY (`empresa_id`) REFERENCES `empresas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `unidades`
--

LOCK TABLES `unidades` WRITE;
/*!40000 ALTER TABLE `unidades` DISABLE KEYS */;
INSERT INTO `unidades` VALUES (1,1,'Unidad','und','2026-06-28 22:21:27','2026-06-28 22:21:27'),(2,1,'Kilogramo','kg','2026-06-28 22:21:27','2026-06-28 22:21:27'),(3,1,'Litro','lt','2026-06-28 22:21:27','2026-06-28 22:21:27'),(4,1,'Caja','cja','2026-06-28 22:21:27','2026-06-28 22:21:27'),(5,1,'Par','par','2026-06-28 22:21:27','2026-06-28 22:21:27'),(6,1,'Metro','m','2026-06-28 22:21:27','2026-06-28 22:21:27'),(7,1,'Docena','doc','2026-06-28 22:21:27','2026-06-28 22:21:27'),(8,1,'Unidad','und','2026-06-30 13:22:47','2026-06-30 13:22:47'),(9,1,'Kilogramo','kg','2026-06-30 13:22:47','2026-06-30 13:22:47'),(10,1,'Litro','lt','2026-06-30 13:22:47','2026-06-30 13:22:47'),(11,1,'Caja','cja','2026-06-30 13:22:47','2026-06-30 13:22:47'),(12,1,'Par','par','2026-06-30 13:22:48','2026-06-30 13:22:48'),(13,1,'Metro','m','2026-06-30 13:22:48','2026-06-30 13:22:48'),(14,1,'Docena','doc','2026-06-30 13:22:48','2026-06-30 13:22:48');
/*!40000 ALTER TABLE `unidades` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `empresa_id` bigint(20) unsigned DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_empresa_id_foreign` (`empresa_id`),
  CONSTRAINT `users_empresa_id_foreign` FOREIGN KEY (`empresa_id`) REFERENCES `empresas` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,NULL,'Administrador','admin@inventario.com',NULL,'$2y$12$Vw5/Q1ookVMFBoms6QJIF.Y3udCKg3EEa0PXZvYejGQoBOYBt4nEO',NULL,'2026-06-28 22:21:26','2026-06-28 22:21:26'),(2,1,'Carla Vendedora','vendedor@inventario.com',NULL,'$2y$12$0WZEBzOC16VM/Tx/fV/9ZuEtZXXuM1YYSuvMExuVBUMV2RYvOy.Yy',NULL,'2026-06-30 13:22:46','2026-06-30 13:22:46'),(3,1,'Luis Bodeguero','bodeguero@inventario.com',NULL,'$2y$12$H2rzreAmAuy7ORgGxF0HTui6ns7O52DUVU3pitxS6EQ3Xwk0vLsyG',NULL,'2026-06-30 13:22:46','2026-06-30 13:22:46'),(4,1,'Ana Contadora','contador@inventario.com',NULL,'$2y$12$JyNFzKlriaEbHB2aLwIKE.R3yfkcdCTZi1UuCV3u5iD4jyiz4Y4xe',NULL,'2026-06-30 13:22:47','2026-06-30 13:22:47'),(5,2,'Usuario Empresa 2','usuario2@empresa2.com',NULL,'$2y$12$gg09ggl9BJB14lAV3ZLJIOi0edJFYxJOd5NYDFpdnWNhC6bZrIMtW',NULL,'2026-06-30 17:51:05','2026-06-30 17:51:05');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping events for database 'inventario_sistema'
--

--
-- Dumping routines for database 'inventario_sistema'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-07-03 21:11:37
