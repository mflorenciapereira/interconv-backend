CREATE DATABASE  IF NOT EXISTS `test` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `test`;
-- MySQL dump 10.13  Distrib 5.5.16, for Win32 (x86)
--
-- Host: localhost    Database: test
-- ------------------------------------------------------
-- Server version	5.5.25a

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `centro`
--

DROP TABLE IF EXISTS `centro`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `centro` (
  `id_centro` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(45) NOT NULL,
  `id_direccion` int(11) NOT NULL,
  PRIMARY KEY (`id_centro`),
  UNIQUE KEY `id_direccion_UNIQUE` (`id_direccion`),
  KEY `fk_centro_Direccion` (`id_direccion`),
  KEY `fk_centro_id_direccion` (`id_direccion`),
  CONSTRAINT `fk_centro_id_direccion` FOREIGN KEY (`id_direccion`) REFERENCES `direccion` (`id_direccion`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `centro`
--

LOCK TABLES `centro` WRITE;
/*!40000 ALTER TABLE `centro` DISABLE KEYS */;
INSERT INTO `centro` VALUES (11,'Esto es un centro',16),(12,'Centro en argentina',17),(13,'6to centro',18),(14,'7mo centro',19),(15,'8vo centro',20),(16,'Probando el mapa',21);
/*!40000 ALTER TABLE `centro` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `charla`
--

DROP TABLE IF EXISTS `charla`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `charla` (
  `id_charla` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(45) DEFAULT NULL,
  `id_evento` int(11) DEFAULT NULL,
  `descripcion` text,
  `fecha` date DEFAULT NULL,
  `hora_desde` time DEFAULT NULL,
  `hora_hasta` time DEFAULT NULL,
  `contiene_multimedia` varchar(2) DEFAULT NULL,
  `sala` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id_charla`),
  KEY `fk_Charla_Sala1_idx` (`sala`),
  KEY `fk_charla_Evento` (`id_evento`),
  KEY `fk_charla_id_evento` (`id_evento`),
  CONSTRAINT `fk_charla_id_evento` FOREIGN KEY (`id_evento`) REFERENCES `evento` (`id_evento`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `charla`
--

LOCK TABLES `charla` WRITE;
/*!40000 ALTER TABLE `charla` DISABLE KEYS */;
INSERT INTO `charla` VALUES (1,'charla 1',16,'lala',NULL,NULL,NULL,'1','Roja'),(2,'charla buscada',8,'descripcion','2012-03-10','10:00:00','12:00:00','0','Verde'),(3,'Ejemplo',8,'Descripcion',NULL,NULL,NULL,'0','Sala'),(4,'charla 4',10,NULL,NULL,'11:00:00',NULL,'1',NULL);
/*!40000 ALTER TABLE `charla` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `charla_orador`
--

DROP TABLE IF EXISTS `charla_orador`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `charla_orador` (
  `id_usuario` int(11) NOT NULL,
  `id_charla` int(11) NOT NULL,
  PRIMARY KEY (`id_usuario`,`id_charla`),
  KEY `fk_Charla_has_Orador_Orador1_idx` (`id_usuario`),
  KEY `fk_Charla_id_idx` (`id_charla`),
  KEY `fk_charla_orador_id_usuario` (`id_usuario`),
  KEY `fk_charla_orador_id_charla` (`id_charla`),
  CONSTRAINT `fk_charla_orador_id_charla` FOREIGN KEY (`id_charla`) REFERENCES `charla` (`id_charla`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_charla_orador_id_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `orador` (`id_usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `charla_orador`
--

LOCK TABLES `charla_orador` WRITE;
/*!40000 ALTER TABLE `charla_orador` DISABLE KEYS */;
INSERT INTO `charla_orador` VALUES (3265412,2),(3265412,3);
/*!40000 ALTER TABLE `charla_orador` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ciudades`
--

DROP TABLE IF EXISTS `ciudades`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ciudades` (
  `idciudades` int(11) NOT NULL AUTO_INCREMENT,
  `ciudad` varchar(45) NOT NULL,
  `cant_busquedas` int(11) NOT NULL DEFAULT '0',
  `idprovincia` int(11) NOT NULL,
  PRIMARY KEY (`idciudades`),
  KEY `idprovincia` (`idprovincia`),
  CONSTRAINT `idprovincia` FOREIGN KEY (`idprovincia`) REFERENCES `provincias` (`idprovincias`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=2384 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ciudades`
--

LOCK TABLES `ciudades` WRITE;
/*!40000 ALTER TABLE `ciudades` DISABLE KEYS */;
INSERT INTO `ciudades` VALUES (1,'25 de Mayo',0,2),(2,'3 de febrero',0,2),(3,'Adolfo Alsina',0,2),(4,'Adolfo Gonzáles Cháves',0,2),(5,'Aguas Verdes',0,2),(6,'Alberti',0,2),(7,'Arrecifes',0,2),(8,'Ayacucho',0,2),(9,'Azul',0,2),(10,'Bahía Blanca',0,2),(11,'Balcarce',0,2),(12,'Baradero',0,2),(13,'Benito Juárez',0,2),(14,'Berisso',0,2),(15,'Bolívar',0,2),(16,'Bragado',0,2),(17,'Brandsen',0,2),(18,'Campana',0,2),(19,'Cañuelas',0,2),(20,'Capilla del Señor',0,2),(21,'Capitán Sarmiento',0,2),(22,'Carapachay',0,2),(23,'Carhue',0,2),(24,'Cariló',0,2),(25,'Carlos Casares',0,2),(26,'Carlos Tejedor',0,2),(27,'Carmen de Areco',0,2),(28,'Carmen de Patagones',0,2),(29,'Castelli',0,2),(30,'Chacabuco',0,2),(31,'Chascomús',0,2),(32,'Chivilcoy',0,2),(33,'Colón',0,2),(34,'Coronel Dorrego',0,2),(35,'Coronel Pringles',0,2),(36,'Coronel Rosales',0,2),(37,'Coronel Suarez',0,2),(38,'Costa Azul',0,2),(39,'Costa Chica',0,2),(40,'Costa del Este',0,2),(41,'Costa Esmeralda',0,2),(42,'Daireaux',0,2),(43,'Darregueira',0,2),(44,'Del Viso',0,2),(45,'Dolores',0,2),(46,'Don Torcuato',0,2),(47,'Ensenada',0,2),(48,'Escobar',0,2),(49,'Exaltación de la Cruz',0,2),(50,'Florentino Ameghino',0,2),(51,'Garín',0,2),(52,'General Alvarado',0,2),(53,'General Alvear',0,2),(54,'General Arenales',0,2),(55,'General Belgrano',0,2),(56,'General Guido',0,2),(57,'General Lamadrid',0,2),(58,'General Las Heras',0,2),(59,'General Lavalle',0,2),(60,'General Madariaga',0,2),(61,'General Pacheco',0,2),(62,'General Paz',0,2),(63,'General Pinto',0,2),(64,'General Pueyrredón',0,2),(65,'General Rodríguez',0,2),(66,'General Viamonte',0,2),(67,'General Villegas',0,2),(68,'Guaminí',0,2),(69,'Guernica',0,2),(70,'Hipólito Yrigoyen',0,2),(71,'Ingeniero Maschwitz',0,2),(72,'Junín',0,2),(73,'La Plata',0,2),(74,'Laprida',0,2),(75,'Las Flores',0,2),(76,'Las Toninas',0,2),(77,'Leandro N. Alem',0,2),(78,'Lincoln',0,2),(79,'Loberia',0,2),(80,'Lobos',0,2),(81,'Los Cardales',0,2),(82,'Los Toldos',0,2),(83,'Lucila del Mar',0,2),(84,'Luján',0,2),(85,'Magdalena',0,2),(86,'Maipú',0,2),(87,'Mar Chiquita',0,2),(88,'Mar de Ajó',0,2),(89,'Mar de las Pampas',0,2),(90,'Mar del Plata',0,2),(91,'Mar del Tuyú',0,2),(92,'Marcos Paz',0,2),(93,'Mercedes',0,2),(94,'Miramar',0,2),(95,'Monte',0,2),(96,'Monte Hermoso',0,2),(97,'Munro',0,2),(98,'Navarro',0,2),(99,'Necochea',0,2),(100,'Olavarría',0,2),(101,'Partido de la Costa',0,2),(102,'Pehuajó',0,2),(103,'Pellegrini',0,2),(104,'Pergamino',0,2),(105,'Pigüé',0,2),(106,'Pila',0,2),(107,'Pilar',0,2),(108,'Pinamar',0,2),(109,'Pinar del Sol',0,2),(110,'Polvorines',0,2),(111,'Presidente Perón',0,2),(112,'Puán',0,2),(113,'Punta Indio',0,2),(114,'Ramallo',0,2),(115,'Rauch',0,2),(116,'Rivadavia',0,2),(117,'Rojas',0,2),(118,'Roque Pérez',0,2),(119,'Saavedra',0,2),(120,'Saladillo',0,2),(121,'Salliqueló',0,2),(122,'Salto',0,2),(123,'San Andrés de Giles',0,2),(124,'San Antonio de Areco',0,2),(125,'San Antonio de Padua',0,2),(126,'San Bernardo',0,2),(127,'San Cayetano',0,2),(128,'San Clemente del Tuyú',0,2),(129,'San Nicolás',0,2),(130,'San Pedro',0,2),(131,'San Vicente',0,2),(132,'Santa Teresita',0,2),(133,'Suipacha',0,2),(134,'Tandil',0,2),(135,'Tapalqué',0,2),(136,'Tordillo',0,2),(137,'Tornquist',0,2),(138,'Trenque Lauquen',0,2),(139,'Tres Lomas',0,2),(140,'Villa Gesell',0,2),(141,'Villarino',0,2),(142,'Zárate',0,2),(143,'11 de Septiembre',0,3),(144,'20 de Junio',0,3),(145,'25 de Mayo',0,3),(146,'Acassuso',0,3),(147,'Adrogué',0,3),(148,'Aldo Bonzi',0,3),(149,'Área Reserva Cinturón Ecológico',0,3),(150,'Avellaneda',0,3),(151,'Banfield',0,3),(152,'Barrio Parque',0,3),(153,'Barrio Santa Teresita',0,3),(154,'Beccar',0,3),(155,'Bella Vista',0,3),(156,'Berazategui',0,3),(157,'Bernal Este',0,3),(158,'Bernal Oeste',0,3),(159,'Billinghurst',0,3),(160,'Boulogne',0,3),(161,'Burzaco',0,3),(162,'Carapachay',0,3),(163,'Caseros',0,3),(164,'Castelar',0,3),(165,'Churruca',0,3),(166,'Ciudad Evita',0,3),(167,'Ciudad Madero',0,3),(168,'Ciudadela',0,3),(169,'Claypole',0,3),(170,'Crucecita',0,3),(171,'Dock Sud',0,3),(172,'Don Bosco',0,3),(173,'Don Orione',0,3),(174,'El Jagüel',0,3),(175,'El Libertador',0,3),(176,'El Palomar',0,3),(177,'El Tala',0,3),(178,'El Trébol',0,3),(179,'Ezeiza',0,3),(180,'Ezpeleta',0,3),(181,'Florencio Varela',0,3),(182,'Florida',0,3),(183,'Francisco Álvarez',0,3),(184,'Gerli',0,3),(185,'Glew',0,3),(186,'González Catán',0,3),(187,'General Lamadrid',0,3),(188,'Grand Bourg',0,3),(189,'Gregorio de Laferrere',0,3),(190,'Guillermo Enrique Hudson',0,3),(191,'Haedo',0,3),(192,'Hurlingham',0,3),(193,'Ingeniero Sourdeaux',0,3),(194,'Isidro Casanova',0,3),(195,'Ituzaingó',0,3),(196,'José C. Paz',0,3),(197,'José Ingenieros',0,3),(198,'José Marmol',0,3),(199,'La Lucila',0,3),(200,'La Reja',0,3),(201,'La Tablada',0,3),(202,'Lanús',0,3),(203,'Llavallol',0,3),(204,'Loma Hermosa',0,3),(205,'Lomas de Zamora',0,3),(206,'Lomas del Millón',0,3),(207,'Lomas del Mirador',0,3),(208,'Longchamps',0,3),(209,'Los Polvorines',0,3),(210,'Luis Guillón',0,3),(211,'Malvinas Argentinas',0,3),(212,'Martín Coronado',0,3),(213,'Martínez',0,3),(214,'Merlo',0,3),(215,'Ministro Rivadavia',0,3),(216,'Monte Chingolo',0,3),(217,'Monte Grande',0,3),(218,'Moreno',0,3),(219,'Morón',0,3),(220,'Muñiz',0,3),(221,'Olivos',0,3),(222,'Pablo Nogués',0,3),(223,'Pablo Podestá',0,3),(224,'Paso del Rey',0,3),(225,'Pereyra',0,3),(226,'Piñeiro',0,3),(227,'Plátanos',0,3),(228,'Pontevedra',0,3),(229,'Quilmes',0,3),(230,'Rafael Calzada',0,3),(231,'Rafael Castillo',0,3),(232,'Ramos Mejía',0,3),(233,'Ranelagh',0,3),(234,'Remedios de Escalada',0,3),(235,'Sáenz Peña',0,3),(236,'San Antonio de Padua',0,3),(237,'San Fernando',0,3),(238,'San Francisco Solano',0,3),(239,'San Isidro',0,3),(240,'San José',0,3),(241,'San Justo',0,3),(242,'San Martín',0,3),(243,'San Miguel',0,3),(244,'Santos Lugares',0,3),(245,'Sarandí',0,3),(246,'Sourigues',0,3),(247,'Tapiales',0,3),(248,'Temperley',0,3),(249,'Tigre',0,3),(250,'Tortuguitas',0,3),(251,'Tristán Suárez',0,3),(252,'Trujui',0,3),(253,'Turdera',0,3),(254,'Valentín Alsina',0,3),(255,'Vicente López',0,3),(256,'Villa Adelina',0,3),(257,'Villa Ballester',0,3),(258,'Villa Bosch',0,3),(259,'Villa Caraza',0,3),(260,'Villa Celina',0,3),(261,'Villa Centenario',0,3),(262,'Villa de Mayo',0,3),(263,'Villa Diamante',0,3),(264,'Villa Domínico',0,3),(265,'Villa España',0,3),(266,'Villa Fiorito',0,3),(267,'Villa Guillermina',0,3),(268,'Villa Insuperable',0,3),(269,'Villa José León Suárez',0,3),(270,'Villa La Florida',0,3),(271,'Villa Luzuriaga',0,3),(272,'Villa Martelli',0,3),(273,'Villa Obrera',0,3),(274,'Villa Progreso',0,3),(275,'Villa Raffo',0,3),(276,'Villa Sarmiento',0,3),(277,'Villa Tesei',0,3),(278,'Villa Udaondo',0,3),(279,'Virrey del Pino',0,3),(280,'Wilde',0,3),(281,'William Morris',0,3),(282,'Aconquija',0,5),(283,'Ancasti',0,5),(284,'Andalgalá',0,5),(285,'Antofagasta',0,5),(286,'Belén',0,5),(287,'Capayán',0,5),(288,'Capital',0,5),(289,'Catamarca',0,5),(290,'Corral Quemado',0,5),(291,'El Alto',0,5),(292,'El Rodeo',0,5),(293,'F.Mamerto Esquiú',0,5),(294,'Fiambalá',0,5),(295,'Hualfín',0,5),(296,'Huillapima',0,5),(297,'Icaño',0,5),(298,'La Puerta',0,5),(299,'Las Juntas',0,5),(300,'Londres',0,5),(301,'Los Altos',0,5),(302,'Los Varela',0,5),(303,'Mutquín',0,5),(304,'Paclín',0,5),(305,'Poman',0,5),(306,'Pozo de La Piedra',0,5),(307,'Puerta de Corral',0,5),(308,'Puerta San José',0,5),(309,'Recreo',0,5),(310,'San Fernando del Valle de Catamarca',0,5),(311,'San Fernando',0,5),(312,'San Fernando del Valle',0,5),(313,'San José',0,5),(314,'Santa María',0,5),(315,'Santa Rosa',0,5),(316,'Saujil',0,5),(317,'Tapso',0,5),(318,'Tinogasta',0,5),(319,'Valle Viejo',0,5),(320,'Villa Vil',0,5),(321,'Aviá Teraí',0,6),(322,'Barranqueras',0,6),(323,'Basail',0,6),(324,'Campo Largo',0,6),(325,'Capital',0,6),(326,'Capitán Solari',0,6),(327,'Charadai',0,6),(328,'Charata',0,6),(329,'Chorotis',0,6),(330,'Ciervo Petiso',0,6),(331,'Coronel Du Graty',0,6),(332,'Colonia Benítez',0,6),(333,'Colonia Elisa',0,6),(334,'Colonia Popular',0,6),(335,'Colonias Unidas',0,6),(336,'Concepción',0,6),(337,'Corzuela',0,6),(338,'Cote Lai',0,6),(339,'El Sauzalito',0,6),(340,'Enrique Urien',0,6),(341,'Fontana',0,6),(342,'Fte. Esperanza',0,6),(343,'Gancedo',0,6),(344,'General Capdevila',0,6),(345,'General Pinero',0,6),(346,'General San Martín',0,6),(347,'General Vedia',0,6),(348,'Hermoso Campo',0,6),(349,'I. del Cerrito',0,6),(350,'J.J. Castelli',0,6),(351,'La Clotilde',0,6),(352,'La Eduvigis',0,6),(353,'La Escondida',0,6),(354,'La Leonesa',0,6),(355,'La Tigra',0,6),(356,'La Verde',0,6),(357,'Laguna Blanca',0,6),(358,'Laguna Limpia',0,6),(359,'Lapachito',0,6),(360,'Las Breñas',0,6),(361,'Las Garcitas',0,6),(362,'Las Palmas',0,6),(363,'Los Frentones',0,6),(364,'Machagai',0,6),(365,'Makallé',0,6),(366,'Margarita Belén',0,6),(367,'Miraflores',0,6),(368,'Misión N. Pompeya',0,6),(369,'Napenay',0,6),(370,'Pampa Almirón',0,6),(371,'Pampa del Indio',0,6),(372,'Pampa del Infierno',0,6),(373,'Presidencia de La Plaza',0,6),(374,'Presidencia Roca',0,6),(375,'Presidencia Roque Sáenz Peña',0,6),(376,'Puerto Bermejo',0,6),(377,'Puerto Eva Perón',0,6),(378,'Puero Tirol',0,6),(379,'Puerto Vilelas',0,6),(380,'Quitilipi',0,6),(381,'Resistencia',0,6),(382,'Sáenz Peña',0,6),(383,'Samuhú',0,6),(384,'San Bernardo',0,6),(385,'Santa Sylvina',0,6),(386,'Taco Pozo',0,6),(387,'Tres Isletas',0,6),(388,'Villa Ángela',0,6),(389,'Villa Berthet',0,6),(390,'Villa R. Bermejito',0,6),(391,'Aldea Apeleg',0,7),(392,'Aldea Beleiro',0,7),(393,'Aldea Epulef',0,7),(394,'Alto Río Sengerr',0,7),(395,'Buen Pasto',0,7),(396,'Camarones',0,7),(397,'Carrenleufú',0,7),(398,'Cholila',0,7),(399,'Cerro Centinela',0,7),(400,'Colan Conhué',0,7),(401,'Comodoro Rivadavia',0,7),(402,'Corcovado',0,7),(403,'Cushamen',0,7),(404,'Dique F. Ameghino',0,7),(405,'Dolavón',0,7),(406,'Dr. R. Rojas',0,7),(407,'El Hoyo',0,7),(408,'El Maitén',0,7),(409,'Epuyén',0,7),(410,'Esquel',0,7),(411,'Facundo',0,7),(412,'Gaimán',0,7),(413,'Gan Gan',0,7),(414,'Gastre',0,7),(415,'Gobernador Costa',0,7),(416,'Gualjaina',0,7),(417,'José de San Martín',0,7),(418,'Lago Blanco',0,7),(419,'Lago Puelo',0,7),(420,'Lagunita Salada',0,7),(421,'Las Plumas',0,7),(422,'Los Altares',0,7),(423,'Paso de los Indios',0,7),(424,'Paso del Sapo',0,7),(425,'Puerto Madryn',0,7),(426,'Puerto Pirámides',0,7),(427,'Rada Tilly',0,7),(428,'Rawson',0,7),(429,'Río Mayo',0,7),(430,'Río Pico',0,7),(431,'Sarmiento',0,7),(432,'Tecka',0,7),(433,'Telsen',0,7),(434,'Trelew',0,7),(435,'Trevelin',0,7),(436,'Veintiocho de Julio',0,7),(437,'Achiras',0,8),(438,'Adelia Maria',0,8),(439,'Agua de Oro',0,8),(440,'Alcira Gigena',0,8),(441,'Aldea Santa Maria',0,8),(442,'Alejandro Roca',0,8),(443,'Alejo Ledesma',0,8),(444,'Alicia',0,8),(445,'Almafuerte',0,8),(446,'Alpa Corral',0,8),(447,'Alta Gracia',0,8),(448,'Alto Alegre',0,8),(449,'Alto de Los Quebrachos',0,8),(450,'Altos de Chipion',0,8),(451,'Amboy',0,8),(452,'Ambul',0,8),(453,'Ana Zumaran',0,8),(454,'Anisacate',0,8),(455,'Arguello',0,8),(456,'Arias',0,8),(457,'Arroyito',0,8),(458,'Arroyo Algodon',0,8),(459,'Arroyo Cabral',0,8),(460,'Arroyo Los Patos',0,8),(461,'Assunta',0,8),(462,'Atahona',0,8),(463,'Ausonia',0,8),(464,'Avellaneda',0,8),(465,'Ballesteros',0,8),(466,'Ballesteros Sud',0,8),(467,'Balnearia',0,8),(468,'Bañado de Soto',0,8),(469,'Bell Ville',0,8),(470,'Bengolea',0,8),(471,'Benjamin Gould',0,8),(472,'Berrotaran',0,8),(473,'Bialet Masse',0,8),(474,'Bouwer',0,8),(475,'Brinkmann',0,8),(476,'Buchardo',0,8),(477,'Bulnes',0,8),(478,'Cabalango',0,8),(479,'Calamuchita',0,8),(480,'Calchin',0,8),(481,'Calchin Oeste',0,8),(482,'Calmayo',0,8),(483,'Camilo Aldao',0,8),(484,'Caminiaga',0,8),(485,'Cañada de Luque',0,8),(486,'Cañada de Machado',0,8),(487,'Cañada de Rio Pinto',0,8),(488,'Cañada del Sauce',0,8),(489,'Canals',0,8),(490,'Candelaria Sud',0,8),(491,'Capilla de Remedios',0,8),(492,'Capilla de Siton',0,8),(493,'Capilla del Carmen',0,8),(494,'Capilla del Monte',0,8),(495,'Capital',0,8),(496,'Capitan Gral B. O´Higgins',0,8),(497,'Carnerillo',0,8),(498,'Carrilobo',0,8),(499,'Casa Grande',0,8),(500,'Cavanagh',0,8),(501,'Cerro Colorado',0,8),(502,'Chaján',0,8),(503,'Chalacea',0,8),(504,'Chañar Viejo',0,8),(505,'Chancaní',0,8),(506,'Charbonier',0,8),(507,'Charras',0,8),(508,'Chazón',0,8),(509,'Chilibroste',0,8),(510,'Chucul',0,8),(511,'Chuña',0,8),(512,'Chuña Huasi',0,8),(513,'Churqui Cañada',0,8),(514,'Cienaga Del Coro',0,8),(515,'Cintra',0,8),(516,'Colonia Almada',0,8),(517,'Colonia Anita',0,8),(518,'Colonia Barge',0,8),(519,'Colonia Bismark',0,8),(520,'Colonia Bremen',0,8),(521,'Colonia Caroya',0,8),(522,'Colonia Italiana',0,8),(523,'Colonia Iturraspe',0,8),(524,'Colonia Las Cuatro Esquinas',0,8),(525,'Colonia Las Pichanas',0,8),(526,'Colonia Marina',0,8),(527,'Colonia Prosperidad',0,8),(528,'Colonia San Bartolome',0,8),(529,'Colonia San Pedro',0,8),(530,'Colonia Tirolesa',0,8),(531,'Colonia Vicente Aguero',0,8),(532,'Colonia Videla',0,8),(533,'Colonia Vignaud',0,8),(534,'Colonia Waltelina',0,8),(535,'Colazo',0,8),(536,'Comechingones',0,8),(537,'Conlara',0,8),(538,'Copacabana',0,8),(539,'Córdoba',0,8),(540,'Coronel Baigorria',0,8),(541,'Coronel Moldes',0,8),(542,'Corral de Bustos',0,8),(543,'Corralito',0,8),(544,'Cosquín',0,8),(545,'Costa Sacate',0,8),(546,'Cruz Alta',0,8),(547,'Cruz de Caña',0,8),(548,'Cruz del Eje',0,8),(549,'Cuesta Blanca',0,8),(550,'Dean Funes',0,8),(551,'Del Campillo',0,8),(552,'Despeñaderos',0,8),(553,'Devoto',0,8),(554,'Diego de Rojas',0,8),(555,'Dique Chico',0,8),(556,'El Arañado',0,8),(557,'El Brete',0,8),(558,'El Chacho',0,8),(559,'El Crispín',0,8),(560,'El Fortín',0,8),(561,'El Manzano',0,8),(562,'El Rastreador',0,8),(563,'El Rodeo',0,8),(564,'El Tío',0,8),(565,'Elena',0,8),(566,'Embalse',0,8),(567,'Esquina',0,8),(568,'Estación General Paz',0,8),(569,'Estación Juárez Celman',0,8),(570,'Estancia de Guadalupe',0,8),(571,'Estancia Vieja',0,8),(572,'Etruria',0,8),(573,'Eufrasio Loza',0,8),(574,'Falda del Carmen',0,8),(575,'Freyre',0,8),(576,'General Baldissera',0,8),(577,'General Cabrera',0,8),(578,'General Deheza',0,8),(579,'General Fotheringham',0,8),(580,'General Levalle',0,8),(581,'General Roca',0,8),(582,'Guanaco Muerto',0,8),(583,'Guasapampa',0,8),(584,'Guatimozin',0,8),(585,'Gutenberg',0,8),(586,'Hernando',0,8),(587,'Huanchillas',0,8),(588,'Huerta Grande',0,8),(589,'Huinca Renanco',0,8),(590,'Idiazabal',0,8),(591,'Impira',0,8),(592,'Inriville',0,8),(593,'Isla Verde',0,8),(594,'Italó',0,8),(595,'James Craik',0,8),(596,'Jesús María',0,8),(597,'Jovita',0,8),(598,'Justiniano Posse',0,8),(599,'Km 658',0,8),(600,'Lucio V. Mansilla',0,8),(601,'La Batea',0,8),(602,'La Calera',0,8),(603,'La Carlota',0,8),(604,'La Carolina',0,8),(605,'La Cautiva',0,8),(606,'La Cesira',0,8),(607,'La Cruz',0,8),(608,'La Cumbre',0,8),(609,'La Cumbrecita',0,8),(610,'La Falda',0,8),(611,'La Francia',0,8),(612,'La Granja',0,8),(613,'La Higuera',0,8),(614,'La Laguna',0,8),(615,'La Paisanita',0,8),(616,'La Palestina',0,8),(617,'La Pampa',0,8),(618,'La Paquita',0,8),(619,'La Para',0,8),(620,'La Paz',0,8),(621,'La Playa',0,8),(622,'La Playosa',0,8),(623,'La Población',0,8),(624,'La Posta',0,8),(625,'La Puerta',0,8),(626,'La Quinta',0,8),(627,'La Rancherita',0,8),(628,'La Rinconada',0,8),(629,'La Serranita',0,8),(630,'La Tordilla',0,8),(631,'Laborde',0,8),(632,'Laboulaye',0,8),(633,'Laguna Larga',0,8),(634,'Las Acequias',0,8),(635,'Las Albahacas',0,8),(636,'Las Arrias',0,8),(637,'Las Bajadas',0,8),(638,'Las Caleras',0,8),(639,'Las Calles',0,8),(640,'Las Cañadas',0,8),(641,'Las Gramillas',0,8),(642,'Las Higueras',0,8),(643,'Las Isletillas',0,8),(644,'Las Junturas',0,8),(645,'Las Palmas',0,8),(646,'Las Peñas',0,8),(647,'Las Peñas Sud',0,8),(648,'Las Perdices',0,8),(649,'Las Playas',0,8),(650,'Las Rabonas',0,8),(651,'Las Saladas',0,8),(652,'Las Tapias',0,8),(653,'Las Varas',0,8),(654,'Las Varillas',0,8),(655,'Las Vertientes',0,8),(656,'Leguizamón',0,8),(657,'Leones',0,8),(658,'Los Cedros',0,8),(659,'Los Cerrillos',0,8),(660,'Los Chañaritos (C.E)',0,8),(661,'Los Chanaritos (R.S)',0,8),(662,'Los Cisnes',0,8),(663,'Los Cocos',0,8),(664,'Los Cóndores',0,8),(665,'Los Hornillos',0,8),(666,'Los Hoyos',0,8),(667,'Los Mistoles',0,8),(668,'Los Molinos',0,8),(669,'Los Pozos',0,8),(670,'Los Reartes',0,8),(671,'Los Surgentes',0,8),(672,'Los Talares',0,8),(673,'Los Zorros',0,8),(674,'Lozada',0,8),(675,'Luca',0,8),(676,'Luque',0,8),(677,'Luyaba',0,8),(678,'Malagueño',0,8),(679,'Malena',0,8),(680,'Malvinas Argentinas',0,8),(681,'Manfredi',0,8),(682,'Maquinista Gallini',0,8),(683,'Marcos Juárez',0,8),(684,'Marull',0,8),(685,'Matorrales',0,8),(686,'Mattaldi',0,8),(687,'Mayu Sumaj',0,8),(688,'Media Naranja',0,8),(689,'Melo',0,8),(690,'Mendiolaza',0,8),(691,'Mi Granja',0,8),(692,'Mina Clavero',0,8),(693,'Miramar',0,8),(694,'Morrison',0,8),(695,'Morteros',0,8),(696,'Monte Buey',0,8),(697,'Monte Cristo',0,8),(698,'Monte De Los Gauchos',0,8),(699,'Monte Leña',0,8),(700,'Monte Maíz',0,8),(701,'Monte Ralo',0,8),(702,'Nicolás Bruzone',0,8),(703,'Noetinger',0,8),(704,'Nono',0,8),(705,'Nueva Córdoba',0,8),(706,'Obispo Trejo',0,8),(707,'Olaeta',0,8),(708,'Oliva',0,8),(709,'Olivares San Nicolás',0,8),(710,'Onagolty',0,8),(711,'Oncativo',0,8),(712,'Ordoñez',0,8),(713,'Pacheco De Melo',0,8),(714,'Pampayasta Norte',0,8),(715,'Pampayasta Sur',0,8),(716,'Panaholma',0,8),(717,'Pascanas',0,8),(718,'Pasco',0,8),(719,'Paso del Durazno',0,8),(720,'Paso Viejo',0,8),(721,'Pilar',0,8),(722,'Pincén',0,8),(723,'Piquillín',0,8),(724,'Plaza de Mercedes',0,8),(725,'Plaza Luxardo',0,8),(726,'Porteña',0,8),(727,'Potrero de Garay',0,8),(728,'Pozo del Molle',0,8),(729,'Pozo Nuevo',0,8),(730,'Pueblo Italiano',0,8),(731,'Puesto de Castro',0,8),(732,'Punta del Agua',0,8),(733,'Quebracho Herrado',0,8),(734,'Quilino',0,8),(735,'Rafael García',0,8),(736,'Ranqueles',0,8),(737,'Rayo Cortado',0,8),(738,'Reducción',0,8),(739,'Rincón',0,8),(740,'Río Bamba',0,8),(741,'Río Ceballos',0,8),(742,'Río Cuarto',0,8),(743,'Río de Los Sauces',0,8),(744,'Río Primero',0,8),(745,'Río Segundo',0,8),(746,'Río Tercero',0,8),(747,'Rosales',0,8),(748,'Rosario del Saladillo',0,8),(749,'Sacanta',0,8),(750,'Sagrada Familia',0,8),(751,'Saira',0,8),(752,'Saladillo',0,8),(753,'Saldán',0,8),(754,'Salsacate',0,8),(755,'Salsipuedes',0,8),(756,'Sampacho',0,8),(757,'San Agustín',0,8),(758,'San Antonio de Arredondo',0,8),(759,'San Antonio de Litín',0,8),(760,'San Basilio',0,8),(761,'San Carlos Minas',0,8),(762,'San Clemente',0,8),(763,'San Esteban',0,8),(764,'San Francisco',0,8),(765,'San Ignacio',0,8),(766,'San Javier',0,8),(767,'San Jerónimo',0,8),(768,'San Joaquín',0,8),(769,'San José de La Dormida',0,8),(770,'San José de Las Salinas',0,8),(771,'San Lorenzo',0,8),(772,'San Marcos Sierras',0,8),(773,'San Marcos Sud',0,8),(774,'San Pedro',0,8),(775,'San Pedro Norte',0,8),(776,'San Roque',0,8),(777,'San Vicente',0,8),(778,'Santa Catalina',0,8),(779,'Santa Elena',0,8),(780,'Santa Eufemia',0,8),(781,'Santa Maria',0,8),(782,'Sarmiento',0,8),(783,'Saturnino M.Laspiur',0,8),(784,'Sauce Arriba',0,8),(785,'Sebastián Elcano',0,8),(786,'Seeber',0,8),(787,'Segunda Usina',0,8),(788,'Serrano',0,8),(789,'Serrezuela',0,8),(790,'Santiago Temple',0,8),(791,'Silvio Pellico',0,8),(792,'Simbolar',0,8),(793,'Sinsacate',0,8),(794,'Santa Rosa de Calamuchita',0,8),(795,'Santa Rosa de Río Primero',0,8),(796,'Suco',0,8),(797,'Tala Cañada',0,8),(798,'Tala Huasi',0,8),(799,'Talaini',0,8),(800,'Tancacha',0,8),(801,'Tanti',0,8),(802,'Ticino',0,8),(803,'Tinoco',0,8),(804,'Tío Pujio',0,8),(805,'Toledo',0,8),(806,'Toro Pujio',0,8),(807,'Tosno',0,8),(808,'Tosquita',0,8),(809,'Tránsito',0,8),(810,'Tuclame',0,8),(811,'Tutti',0,8),(812,'Ucacha',0,8),(813,'Unquillo',0,8),(814,'Valle de Anisacate',0,8),(815,'Valle Hermoso',0,8),(816,'Vélez Sarfield',0,8),(817,'Viamonte',0,8),(818,'Vicuña Mackenna',0,8),(819,'Villa Allende',0,8),(820,'Villa Amancay',0,8),(821,'Villa Ascasubi',0,8),(822,'Villa Candelaria N.',0,8),(823,'Villa Carlos Paz',0,8),(824,'Villa Cerro Azul',0,8),(825,'Villa Ciudad de América',0,8),(826,'Villa Ciudad Pque Los Reartes',0,8),(827,'Villa Concepción del Tío',0,8),(828,'Villa Cura Brochero',0,8),(829,'Villa de Las Rosas',0,8),(830,'Villa de María',0,8),(831,'Villa de Pocho',0,8),(832,'Villa de Soto',0,8),(833,'Villa del Dique',0,8),(834,'Villa del Prado',0,8),(835,'Villa del Rosario',0,8),(836,'Villa del Totoral',0,8),(837,'Villa Dolores',0,8),(838,'Villa El Chancay',0,8),(839,'Villa Elisa',0,8),(840,'Villa Flor Serrana',0,8),(841,'Villa Fontana',0,8),(842,'Villa Giardino',0,8),(843,'Villa General Belgrano',0,8),(844,'Villa Gutierrez',0,8),(845,'Villa Huidobro',0,8),(846,'Villa La Bolsa',0,8),(847,'Villa Los Aromos',0,8),(848,'Villa Los Patos',0,8),(849,'Villa María',0,8),(850,'Villa Nueva',0,8),(851,'Villa Pque. Santa Ana',0,8),(852,'Villa Pque. Siquiman',0,8),(853,'Villa Quillinzo',0,8),(854,'Villa Rossi',0,8),(855,'Villa Rumipal',0,8),(856,'Villa San Esteban',0,8),(857,'Villa San Isidro',0,8),(858,'Villa Santa Cruz',0,8),(859,'Villa Sarmiento (G.R)',0,8),(860,'Villa Sarmiento (S.A)',0,8),(861,'Villa Tulumba',0,8),(862,'Villa Valeria',0,8),(863,'Villa Yacanto',0,8),(864,'Washington',0,8),(865,'Wenceslao Escalante',0,8),(866,'Ycho Cruz Sierras',0,8),(867,'Alvear',0,9),(868,'Bella Vista',0,9),(869,'Berón de Astrada',0,9),(870,'Bonpland',0,9),(871,'Caá Cati',0,9),(872,'Capital',0,9),(873,'Chavarría',0,9),(874,'Colonia Carlos Pellegrini',0,9),(875,'Colonia Libertad',0,9),(876,'Colonia Liebig',0,9),(877,'Colonia Sta Rosa',0,9),(878,'Concepción',0,9),(879,'Cruz de Los Milagros',0,9),(880,'Curuzú-Cuatiá',0,9),(881,'Empedrado',0,9),(882,'Esquina',0,9),(883,'Estación Torrent',0,9),(884,'Felipe Yofré',0,9),(885,'Garruchos',0,9),(886,'Gobernador Agrónomo',0,9),(887,'Gobernador Martínez',0,9),(888,'Goya',0,9),(889,'Guaviravi',0,9),(890,'Herlitzka',0,9),(891,'Ita-Ibate',0,9),(892,'Itatí',0,9),(893,'Ituzaingó',0,9),(894,'José Rafael Gómez',0,9),(895,'Juan Pujol',0,9),(896,'La Cruz',0,9),(897,'Lavalle',0,9),(898,'Lomas de Vallejos',0,9),(899,'Loreto',0,9),(900,'Mariano I. Loza',0,9),(901,'Mburucuyá',0,9),(902,'Mercedes',0,9),(903,'Mocoretá',0,9),(904,'Monte Caseros',0,9),(905,'Nueve de Julio',0,9),(906,'Palmar Grande',0,9),(907,'Parada Pucheta',0,9),(908,'Paso de La Patria',0,9),(909,'Paso de Los Libres',0,9),(910,'Pedro R. Fernandez',0,9),(911,'Perugorría',0,9),(912,'Pueblo Libertador',0,9),(913,'Ramada Paso',0,9),(914,'Riachuelo',0,9),(915,'Saladas',0,9),(916,'San Antonio',0,9),(917,'San Carlos',0,9),(918,'San Cosme',0,9),(919,'San Lorenzo',0,9),(920,'San Luis del Palmar',0,9),(921,'San Miguel',0,9),(922,'San Roque',0,9),(923,'Santa Ana',0,9),(924,'Santa Lucía',0,9),(925,'Santo Tomé',0,9),(926,'Sauce',0,9),(927,'Tabay',0,9),(928,'Tapebicuá',0,9),(929,'Tatacua',0,9),(930,'Virasoro',0,9),(931,'Yapeyú',0,9),(932,'Yataití Calle',0,9),(933,'Alarcón',0,10),(934,'Alcaraz',0,10),(935,'Alcaraz Norte',0,10),(936,'Alcaraz Sur',0,10),(937,'Aldea Asunción',0,10),(938,'Aldea Brasilera',0,10),(939,'Aldea Elgenfeld',0,10),(940,'Aldea Grapschental',0,10),(941,'Aldea Ma. Luisa',0,10),(942,'Aldea Protestante',0,10),(943,'Aldea Salto',0,10),(944,'Aldea San Antonio (G)',0,10),(945,'Aldea San Antonio (P)',0,10),(946,'Aldea San Juan',0,10),(947,'Aldea San Miguel',0,10),(948,'Aldea San Rafael',0,10),(949,'Aldea Spatzenkutter',0,10),(950,'Aldea Santa María',0,10),(951,'Aldea Santa Rosa',0,10),(952,'Aldea Valle María',0,10),(953,'Altamirano Sur',0,10),(954,'Antelo',0,10),(955,'Antonio Tomás',0,10),(956,'Aranguren',0,10),(957,'Arroyo Barú',0,10),(958,'Arroyo Burgos',0,10),(959,'Arroyo Clé',0,10),(960,'Arroyo Corralito',0,10),(961,'Arroyo del Medio',0,10),(962,'Arroyo Maturrango',0,10),(963,'Arroyo Palo Seco',0,10),(964,'Banderas',0,10),(965,'Basavilbaso',0,10),(966,'Betbeder',0,10),(967,'Bovril',0,10),(968,'Caseros',0,10),(969,'Ceibas',0,10),(970,'Cerrito',0,10),(971,'Chajarí',0,10),(972,'Chilcas',0,10),(973,'Clodomiro Ledesma',0,10),(974,'Colonia Alemana',0,10),(975,'Colonia Avellaneda',0,10),(976,'Colonia Avigdor',0,10),(977,'Colonia Ayuí',0,10),(978,'Colonia Baylina',0,10),(979,'Colonia Carrasco',0,10),(980,'Colonia Celina',0,10),(981,'Colonia Cerrito',0,10),(982,'Colonia Crespo',0,10),(983,'Colonia Elia',0,10),(984,'Colonia Ensayo',0,10),(985,'Colonia General Roca',0,10),(986,'Colonia La Argentina',0,10),(987,'Colonia Merou',0,10),(988,'Colonia Oficial Nª3',0,10),(989,'Colonia Oficial Nº13',0,10),(990,'Colonia Oficial Nº14',0,10),(991,'Colonia Oficial Nº5',0,10),(992,'Colonia Reffino',0,10),(993,'Colonia Tunas',0,10),(994,'Colonia Viraró',0,10),(995,'Colón',0,10),(996,'Concepción del Uruguay',0,10),(997,'Concordia',0,10),(998,'Conscripto Bernardi',0,10),(999,'Costa Grande',0,10),(1000,'Costa San Antonio',0,10),(1001,'Costa Uruguay Norte',0,10),(1002,'Costa Uruguay Sur',0,10),(1003,'Crespo',0,10),(1004,'Crucecitas 3ª',0,10),(1005,'Crucecitas 7ª',0,10),(1006,'Crucecitas 8ª',0,10),(1007,'Cuchilla Redonda',0,10),(1008,'Curtiembre',0,10),(1009,'Diamante',0,10),(1010,'Distrito 6º',0,10),(1011,'Distrito Chañar',0,10),(1012,'Distrito Chiqueros',0,10),(1013,'Distrito Cuarto',0,10),(1014,'Distrito Diego López',0,10),(1015,'Distrito Pajonal',0,10),(1016,'Distrito Sauce',0,10),(1017,'Distrito Tala',0,10),(1018,'Distrito Talitas',0,10),(1019,'Don Cristóbal 1ª Sección',0,10),(1020,'Don Cristóbal 2ª Sección',0,10),(1021,'Durazno',0,10),(1022,'El Cimarrón',0,10),(1023,'El Gramillal',0,10),(1024,'El Palenque',0,10),(1025,'El Pingo',0,10),(1026,'El Quebracho',0,10),(1027,'El Redomón',0,10),(1028,'El Solar',0,10),(1029,'Enrique Carbo',0,10),(1030,'Entre Ríos',0,10),(1031,'Espinillo N.',0,10),(1032,'Estación Campos',0,10),(1033,'Estación Escriña',0,10),(1034,'Estación Lazo',0,10),(1035,'Estación Raíces',0,10),(1036,'Estación Yerúa',0,10),(1037,'Estancia Grande',0,10),(1038,'Estancia Líbaros',0,10),(1039,'Estancia Racedo',0,10),(1040,'Estancia Solá',0,10),(1041,'Estancia Yuquerí',0,10),(1042,'Estaquitas',0,10),(1043,'Faustino M. Parera',0,10),(1044,'Febre',0,10),(1045,'Federación',0,10),(1046,'Federal',0,10),(1047,'Gobernador Echagüe',0,10),(1048,'Gobernador Mansilla',0,10),(1049,'Gilbert',0,10),(1050,'González Calderón',0,10),(1051,'General Almada',0,10),(1052,'General Alvear',0,10),(1053,'General Campos',0,10),(1054,'General Galarza',0,10),(1055,'General Ramírez',0,10),(1056,'Gualeguay',0,10),(1057,'Gualeguaychú',0,10),(1058,'Gualeguaycito',0,10),(1059,'Guardamonte',0,10),(1060,'Hambis',0,10),(1061,'Hasenkamp',0,10),(1062,'Hernandarias',0,10),(1063,'Hernández',0,10),(1064,'Herrera',0,10),(1065,'Hinojal',0,10),(1066,'Hocker',0,10),(1067,'Ingeniero Sajaroff',0,10),(1068,'Irazusta',0,10),(1069,'Isletas',0,10),(1070,'J.J De Urquiza',0,10),(1071,'Jubileo',0,10),(1072,'La Clarita',0,10),(1073,'La Criolla',0,10),(1074,'La Esmeralda',0,10),(1075,'La Florida',0,10),(1076,'La Fraternidad',0,10),(1077,'La Hierra',0,10),(1078,'La Ollita',0,10),(1079,'La Paz',0,10),(1080,'La Picada',0,10),(1081,'La Providencia',0,10),(1082,'La Verbena',0,10),(1083,'Laguna Benítez',0,10),(1084,'Larroque',0,10),(1085,'Las Cuevas',0,10),(1086,'Las Garzas',0,10),(1087,'Las Guachas',0,10),(1088,'Las Mercedes',0,10),(1089,'Las Moscas',0,10),(1090,'Las Mulitas',0,10),(1091,'Las Toscas',0,10),(1092,'Laurencena',0,10),(1093,'Libertador San Martín',0,10),(1094,'Loma Limpia',0,10),(1095,'Los Ceibos',0,10),(1096,'Los Charruas',0,10),(1097,'Los Conquistadores',0,10),(1098,'Lucas González',0,10),(1099,'Lucas N.',0,10),(1100,'Lucas S. 1ª',0,10),(1101,'Lucas S. 2ª',0,10),(1102,'Maciá',0,10),(1103,'María Grande',0,10),(1104,'María Grande 2ª',0,10),(1105,'Médanos',0,10),(1106,'Mojones Norte',0,10),(1107,'Mojones Sur',0,10),(1108,'Molino Doll',0,10),(1109,'Monte Redondo',0,10),(1110,'Montoya',0,10),(1111,'Mulas Grandes',0,10),(1112,'Ñancay',0,10),(1113,'Nogoyá',0,10),(1114,'Nueva Escocia',0,10),(1115,'Nueva Vizcaya',0,10),(1116,'Ombú',0,10),(1117,'Oro Verde',0,10),(1118,'Paraná',0,10),(1119,'Pasaje Guayaquil',0,10),(1120,'Pasaje Las Tunas',0,10),(1121,'Paso de La Arena',0,10),(1122,'Paso de La Laguna',0,10),(1123,'Paso de Las Piedras',0,10),(1124,'Paso Duarte',0,10),(1125,'Pastor Britos',0,10),(1126,'Pedernal',0,10),(1127,'Perdices',0,10),(1128,'Picada Berón',0,10),(1129,'Piedras Blancas',0,10),(1130,'Primer Distrito Cuchilla',0,10),(1131,'Primero de Mayo',0,10),(1132,'Pronunciamiento',0,10),(1133,'Puerto Algarrobo',0,10),(1134,'Puerto Ibicuy',0,10),(1135,'Pueblo Brugo',0,10),(1136,'Pueblo Cazes',0,10),(1137,'Pueblo General Belgrano',0,10),(1138,'Pueblo Liebig',0,10),(1139,'Puerto Yeruá',0,10),(1140,'Punta del Monte',0,10),(1141,'Quebracho',0,10),(1142,'Quinto Distrito',0,10),(1143,'Raices Oeste',0,10),(1144,'Rincón de Nogoyá',0,10),(1145,'Rincón del Cinto',0,10),(1146,'Rincón del Doll',0,10),(1147,'Rincón del Gato',0,10),(1148,'Rocamora',0,10),(1149,'Rosario del Tala',0,10),(1150,'San Benito',0,10),(1151,'San Cipriano',0,10),(1152,'San Ernesto',0,10),(1153,'San Gustavo',0,10),(1154,'San Jaime',0,10),(1155,'San José',0,10),(1156,'San José de Feliciano',0,10),(1157,'San Justo',0,10),(1158,'San Marcial',0,10),(1159,'San Pedro',0,10),(1160,'San Ramírez',0,10),(1161,'San Ramón',0,10),(1162,'San Roque',0,10),(1163,'San Salvador',0,10),(1164,'San Víctor',0,10),(1165,'Santa Ana',0,10),(1166,'Santa Anita',0,10),(1167,'Santa Elena',0,10),(1168,'Santa Lucía',0,10),(1169,'Santa Luisa',0,10),(1170,'Sauce de Luna',0,10),(1171,'Sauce Montrull',0,10),(1172,'Sauce Pinto',0,10),(1173,'Sauce Sur',0,10),(1174,'Seguí',0,10),(1175,'Sir Leonard',0,10),(1176,'Sosa',0,10),(1177,'Tabossi',0,10),(1178,'Tezanos Pinto',0,10),(1179,'Ubajay',0,10),(1180,'Urdinarrain',0,10),(1181,'Veinte de Septiembre',0,10),(1182,'Viale',0,10),(1183,'Victoria',0,10),(1184,'Villa Clara',0,10),(1185,'Villa del Rosario',0,10),(1186,'Villa Domínguez',0,10),(1187,'Villa Elisa',0,10),(1188,'Villa Fontana',0,10),(1189,'Villa Gobernador Etchevehere',0,10),(1190,'Villa Mantero',0,10),(1191,'Villa Paranacito',0,10),(1192,'Villa Urquiza',0,10),(1193,'Villaguay',0,10),(1194,'Walter Moss',0,10),(1195,'Yacaré',0,10),(1196,'Yeso Oeste',0,10),(1197,'Buena Vista',0,11),(1198,'Clorinda',0,11),(1199,'Colonia Pastoril',0,11),(1200,'Comandante Fontana',0,11),(1201,'El Colorado',0,11),(1202,'El Espinillo',0,11),(1203,'Estanislao Del Campo',0,11),(1204,'Formosa',0,11),(1205,'Fortín Lugones',0,11),(1206,'General Lucio V. Mansilla',0,11),(1207,'General Manuel Belgrano',0,11),(1208,'General Mosconi',0,11),(1209,'Gran Guardia',0,11),(1210,'Herradura',0,11),(1211,'Ibarreta',0,11),(1212,'Ingeniero Juárez',0,11),(1213,'Laguna Blanca',0,11),(1214,'Laguna Naick Neck',0,11),(1215,'Laguna Yema',0,11),(1216,'Las Lomitas',0,11),(1217,'Los Chiriguanos',0,11),(1218,'Mayor V. Villafañe',0,11),(1219,'Misión San FCerro',0,11),(1220,'Palo Santo',0,11),(1221,'Pirané',0,11),(1222,'Pozo del Maza',0,11),(1223,'Riacho He-He',0,11),(1224,'San Hilario',0,11),(1225,'San Martín II',0,11),(1226,'Siete Palmas',0,11),(1227,'Subteniente Perín',0,11),(1228,'Tres Lagunas',0,11),(1229,'Villa Dos Trece',0,11),(1230,'Villa Escolar',0,11),(1231,'Villa General Güemes',0,11),(1232,'Abdon Castro Tolay',0,12),(1233,'Abra Pampa',0,12),(1234,'Abralaite',0,12),(1235,'Aguas Calientes',0,12),(1236,'Arrayanal',0,12),(1237,'Barrios',0,12),(1238,'Caimancito',0,12),(1239,'Calilegua',0,12),(1240,'Cangrejillos',0,12),(1241,'Caspala',0,12),(1242,'Catuá',0,12),(1243,'Cieneguillas',0,12),(1244,'Coranzulli',0,12),(1245,'Cusi-Cusi',0,12),(1246,'El Aguilar',0,12),(1247,'El Carmen',0,12),(1248,'El Cóndor',0,12),(1249,'El Fuerte',0,12),(1250,'El Piquete',0,12),(1251,'El Talar',0,12),(1252,'Fraile Pintado',0,12),(1253,'Hipólito Yrigoyen',0,12),(1254,'Huacalera',0,12),(1255,'Humahuaca',0,12),(1256,'La Esperanza',0,12),(1257,'La Mendieta',0,12),(1258,'La Quiaca',0,12),(1259,'Ledesma',0,12),(1260,'Libertador General San Martin',0,12),(1261,'Maimara',0,12),(1262,'Mina Pirquitas',0,12),(1263,'Monterrico',0,12),(1264,'Palma Sola',0,12),(1265,'Palpalá',0,12),(1266,'Pampa Blanca',0,12),(1267,'Pampichuela',0,12),(1268,'Perico',0,12),(1269,'Puesto del Marqués',0,12),(1270,'Puesto Viejo',0,12),(1271,'Pumahuasi',0,12),(1272,'Purmamarca',0,12),(1273,'Rinconada',0,12),(1274,'Rodeitos',0,12),(1275,'Rosario de Río Grande',0,12),(1276,'San Antonio',0,12),(1277,'San Francisco',0,12),(1278,'San Pedro',0,12),(1279,'San Rafael',0,12),(1280,'San Salvador',0,12),(1281,'Santa Ana',0,12),(1282,'Santa Catalina',0,12),(1283,'Santa Clara',0,12),(1284,'Susques',0,12),(1285,'Tilcara',0,12),(1286,'Tres Cruces',0,12),(1287,'Tumbaya',0,12),(1288,'Valle Grande',0,12),(1289,'Vinalito',0,12),(1290,'Volcán',0,12),(1291,'Yala',0,12),(1292,'Yaví',0,12),(1293,'Yuto',0,12),(1294,'Abramo',0,13),(1295,'Adolfo Van Praet',0,13),(1296,'Agustoni',0,13),(1297,'Algarrobo del Aguila',0,13),(1298,'Alpachiri',0,13),(1299,'Alta Italia',0,13),(1300,'Anguil',0,13),(1301,'Arata',0,13),(1302,'Ataliva Roca',0,13),(1303,'Bernardo Larroude',0,13),(1304,'Bernasconi',0,13),(1305,'Caleufú',0,13),(1306,'Carro Quemado',0,13),(1307,'Catriló',0,13),(1308,'Ceballos',0,13),(1309,'Chacharramendi',0,13),(1310,'Colonia Barón',0,13),(1311,'Colonia Santa María',0,13),(1312,'Conhelo',0,13),(1313,'Coronel Hilario Lagos',0,13),(1314,'Cuchillo-Có',0,13),(1315,'Doblas',0,13),(1316,'Dorila',0,13),(1317,'Eduardo Castex',0,13),(1318,'Embajador Martini',0,13),(1319,'Falucho',0,13),(1320,'General Acha',0,13),(1321,'General Manuel Campos',0,13),(1322,'General Pico',0,13),(1323,'Guatraché',0,13),(1324,'Ingeniero Luiggi',0,13),(1325,'Intendente Alvear',0,13),(1326,'Jacinto Arauz',0,13),(1327,'La Adela',0,13),(1328,'La Humada',0,13),(1329,'La Maruja',0,13),(1330,'La Pampa',0,13),(1331,'La Reforma',0,13),(1332,'Limay Mahuida',0,13),(1333,'Lonquimay',0,13),(1334,'Loventuel',0,13),(1335,'Luan Toro',0,13),(1336,'Macachín',0,13),(1337,'Maisonnave',0,13),(1338,'Mauricio Mayer',0,13),(1339,'Metileo',0,13),(1340,'Miguel Cané',0,13),(1341,'Miguel Riglos',0,13),(1342,'Monte Nievas',0,13),(1343,'Parera',0,13),(1344,'Perú',0,13),(1345,'Pichi-Huinca',0,13),(1346,'Puelches',0,13),(1347,'Puelén',0,13),(1348,'Quehue',0,13),(1349,'Quemú Quemú',0,13),(1350,'Quetrequén',0,13),(1351,'Rancul',0,13),(1352,'Realicó',0,13),(1353,'Relmo',0,13),(1354,'Rolón',0,13),(1355,'Rucanelo',0,13),(1356,'Sarah',0,13),(1357,'Speluzzi',0,13),(1358,'Santa Isabel',0,13),(1359,'Santa Rosa',0,13),(1360,'Santa Teresa',0,13),(1361,'Telén',0,13),(1362,'Toay',0,13),(1363,'Tomas Manuel de Anchorena',0,13),(1364,'Trenel',0,13),(1365,'Unanue',0,13),(1366,'Uriburu',0,13),(1367,'Veinticinco de Mayo',0,13),(1368,'Vertiz',0,13),(1369,'Victorica',0,13),(1370,'Villa Mirasol',0,13),(1371,'Winifreda',0,13),(1372,'Arauco',0,14),(1373,'Capital',0,14),(1374,'Castro Barros',0,14),(1375,'Chamical',0,14),(1376,'Chilecito',0,14),(1377,'Coronel Felipe Varela',0,14),(1378,'Famatina',0,14),(1379,'General A.V.Peñaloza',0,14),(1380,'General Belgrano',0,14),(1381,'General J.F. Quiroga',0,14),(1382,'General Lamadrid',0,14),(1383,'General Ocampo',0,14),(1384,'General San Martín',0,14),(1385,'Independencia',0,14),(1386,'Rosario Penaloza',0,14),(1387,'San Blas de Los Sauces',0,14),(1388,'Sanagasta',0,14),(1389,'Vinchina',0,14),(1390,'Capital',0,15),(1391,'Chacras de Coria',0,15),(1392,'Dorrego',0,15),(1393,'Gllen',0,15),(1394,'Godoy Cruz',0,15),(1395,'General Alvear',0,15),(1396,'Guaymallén',0,15),(1397,'Junín',0,15),(1398,'La Paz',0,15),(1399,'Las Heras',0,15),(1400,'Lavalle',0,15),(1401,'Luján',0,15),(1402,'Luján De Cuyo',0,15),(1403,'Maipú',0,15),(1404,'Malargüe',0,15),(1405,'Rivadavia',0,15),(1406,'San Carlos',0,15),(1407,'San Martín',0,15),(1408,'San Rafael',0,15),(1409,'Santa Rosa',0,15),(1410,'Tunuyán',0,15),(1411,'Tupungato',0,15),(1412,'Villa Nueva',0,15),(1413,'Alba Posse',0,16),(1414,'Almafuerte',0,16),(1415,'Apóstoles',0,16),(1416,'Aristóbulo Del Valle',0,16),(1417,'Arroyo Del Medio',0,16),(1418,'Azara',0,16),(1419,'Bernardo De Irigoyen',0,16),(1420,'Bonpland',0,16),(1421,'Caá Yari',0,16),(1422,'Campo Grande',0,16),(1423,'Campo Ramón',0,16),(1424,'Campo Viera',0,16),(1425,'Candelaria',0,16),(1426,'Capioví',0,16),(1427,'Caraguatay',0,16),(1428,'Comandante Andresito Guacurarí',0,16),(1429,'Cerro Azul',0,16),(1430,'Cerro Corá',0,16),(1431,'Colonia Alberdi',0,16),(1432,'Colonia Aurora',0,16),(1433,'Colonia Delicia',0,16),(1434,'Colonia Polana',0,16),(1435,'Colonia Victoria',0,16),(1436,'Colonia Wanda',0,16),(1437,'Concepción De La Sierra',0,16),(1438,'Corpus',0,16),(1439,'Dos Arroyos',0,16),(1440,'Dos de Mayo',0,16),(1441,'El Alcázar',0,16),(1442,'El Dorado',0,16),(1443,'El Soberbio',0,16),(1444,'Esperanza',0,16),(1445,'Florentino Ameghino',0,16),(1446,'Fachinal',0,16),(1447,'Garuhapé',0,16),(1448,'Garupá',0,16),(1449,'Gobernador López',0,16),(1450,'Gobernador Roca',0,16),(1451,'General Alvear',0,16),(1452,'General Urquiza',0,16),(1453,'Guaraní',0,16),(1454,'Hipolito Yrigoyen',0,16),(1455,'Iguazú',0,16),(1456,'Itacaruaré',0,16),(1457,'Jardín América',0,16),(1458,'Leandro N. Alem',0,16),(1459,'Libertad',0,16),(1460,'Loreto',0,16),(1461,'Los Helechos',0,16),(1462,'Mártires',0,16),(1463,'Misiones',0,16),(1464,'Mojón Grande',0,16),(1465,'Montecarlo',0,16),(1466,'Nueve de Julio',0,16),(1467,'Oberá',0,16),(1468,'Olegario V. Andrade',0,16),(1469,'Panambí',0,16),(1470,'Posadas',0,16),(1471,'Profundidad',0,16),(1472,'Puerto Iguazú',0,16),(1473,'Puerto Leoni',0,16),(1474,'Puerto Piray',0,16),(1475,'Puerto Rico',0,16),(1476,'Ruiz de Montoya',0,16),(1477,'San Antonio',0,16),(1478,'San Ignacio',0,16),(1479,'San Javier',0,16),(1480,'San José',0,16),(1481,'San Martín',0,16),(1482,'San Pedro',0,16),(1483,'San Vicente',0,16),(1484,'Santiago De Liniers',0,16),(1485,'Santo Pipo',0,16),(1486,'Santa Ana',0,16),(1487,'Santa María',0,16),(1488,'Tres Capones',0,16),(1489,'Veinticinco de Mayo',0,16),(1490,'Wanda',0,16),(1491,'Aguada San Roque',0,17),(1492,'Aluminé',0,17),(1493,'Andacollo',0,17),(1494,'Añelo',0,17),(1495,'Bajada del Agrio',0,17),(1496,'Barrancas',0,17),(1497,'Buta Ranquil',0,17),(1498,'Capital',0,17),(1499,'Caviahué',0,17),(1500,'Centenario',0,17),(1501,'Chorriaca',0,17),(1502,'Chos Malal',0,17),(1503,'Cipolletti',0,17),(1504,'Covunco Abajo',0,17),(1505,'Coyuco Cochico',0,17),(1506,'Cutral Có',0,17),(1507,'El Cholar',0,17),(1508,'El Huecú',0,17),(1509,'El Sauce',0,17),(1510,'Guañacos',0,17),(1511,'Huinganco',0,17),(1512,'Las Coloradas',0,17),(1513,'Las Lajas',0,17),(1514,'Las Ovejas',0,17),(1515,'Loncopué',0,17),(1516,'Los Catutos',0,17),(1517,'Los Chihuidos',0,17),(1518,'Los Miches',0,17),(1519,'Manzano Amargo',0,17),(1520,'Neuquén',0,17),(1521,'Octavio Pico',0,17),(1522,'Paso Aguerre',0,17),(1523,'Picún Leufú',0,17),(1524,'Piedra del Aguila',0,17),(1525,'Pilo Lil',0,17),(1526,'Plaza Huincul',0,17),(1527,'Plottier',0,17),(1528,'Quili Malal',0,17),(1529,'Ramón Castro',0,17),(1530,'Rincón de Los Sauces',0,17),(1531,'San Martín de Los Andes',0,17),(1532,'San Patricio del Chañar',0,17),(1533,'Santo Tomás',0,17),(1534,'Sauzal Bonito',0,17),(1535,'Senillosa',0,17),(1536,'Taquimilán',0,17),(1537,'Tricao Malal',0,17),(1538,'Varvarco',0,17),(1539,'Villa Curí Leuvu',0,17),(1540,'Villa del Nahueve',0,17),(1541,'Villa del Puente Picún Leuvú',0,17),(1542,'Villa El Chocón',0,17),(1543,'Villa La Angostura',0,17),(1544,'Villa Pehuenia',0,17),(1545,'Villa Traful',0,17),(1546,'Vista Alegre',0,17),(1547,'Zapala',0,17),(1548,'Aguada Cecilio',0,18),(1549,'Aguada de Guerra',0,18),(1550,'Allén',0,18),(1551,'Arroyo de La Ventana',0,18),(1552,'Arroyo Los Berros',0,18),(1553,'Bariloche',0,18),(1554,'Calte. Cordero',0,18),(1555,'Campo Grande',0,18),(1556,'Catriel',0,18),(1557,'Cerro Policía',0,18),(1558,'Cervantes',0,18),(1559,'Chelforo',0,18),(1560,'Chimpay',0,18),(1561,'Chinchinales',0,18),(1562,'Chipauquil',0,18),(1563,'Choele Choel',0,18),(1564,'Cinco Saltos',0,18),(1565,'Cipolletti',0,18),(1566,'Clemente Onelli',0,18),(1567,'Colán Conhue',0,18),(1568,'Comallo',0,18),(1569,'Comicó',0,18),(1570,'Cona Niyeu',0,18),(1571,'Coronel Belisle',0,18),(1572,'Cubanea',0,18),(1573,'Darwin',0,18),(1574,'Dina Huapi',0,18),(1575,'El Bolsón',0,18),(1576,'El Caín',0,18),(1577,'El Manso',0,18),(1578,'General Conesa',0,18),(1579,'General Enrique Godoy',0,18),(1580,'General Fernandez Oro',0,18),(1581,'General Roca',0,18),(1582,'Guardia Mitre',0,18),(1583,'Ingeniero Huergo',0,18),(1584,'Ingeniero Jacobacci',0,18),(1585,'Laguna Blanca',0,18),(1586,'Lamarque',0,18),(1587,'Las Grutas',0,18),(1588,'Los Menucos',0,18),(1589,'Luis Beltrán',0,18),(1590,'Mainqué',0,18),(1591,'Mamuel Choique',0,18),(1592,'Maquinchao',0,18),(1593,'Mencué',0,18),(1594,'Ministro Ramos Mexia',0,18),(1595,'Nahuel Niyeu',0,18),(1596,'Naupa Huen',0,18),(1597,'Ñorquinco',0,18),(1598,'Ojos de Agua',0,18),(1599,'Paso de Agua',0,18),(1600,'Paso Flores',0,18),(1601,'Peñas Blancas',0,18),(1602,'Pichi Mahuida',0,18),(1603,'Pilcaniyeu',0,18),(1604,'Pomona',0,18),(1605,'Prahuaniyeu',0,18),(1606,'Rincón Treneta',0,18),(1607,'Río Chico',0,18),(1608,'Río Colorado',0,18),(1609,'Roca',0,18),(1610,'San Antonio Oeste',0,18),(1611,'San Javier',0,18),(1612,'Sierra Colorada',0,18),(1613,'Sierra Grande',0,18),(1614,'Sierra Pailemán',0,18),(1615,'Valcheta',0,18),(1616,'Valle Azul',0,18),(1617,'Viedma',0,18),(1618,'Villa Llanquín',0,18),(1619,'Villa Mascardi',0,18),(1620,'Villa Regina',0,18),(1621,'Yaminué',0,18),(1622,'Apolinario Saravia',0,19),(1623,'Aguaray',0,19),(1624,'Angastaco',0,19),(1625,'Animaná',0,19),(1626,'Cachi',0,19),(1627,'Cafayate',0,19),(1628,'Campo Quijano',0,19),(1629,'Campo Santo',0,19),(1630,'Capital',0,19),(1631,'Cerrillos',0,19),(1632,'Chicoana',0,19),(1633,'Colonia Santa Rosa',0,19),(1634,'Coronel Moldes',0,19),(1635,'El Bordo',0,19),(1636,'El Carril',0,19),(1637,'El Galpón',0,19),(1638,'El Jardín',0,19),(1639,'El Potrero',0,19),(1640,'El Quebrachal',0,19),(1641,'El Tala',0,19),(1642,'Embarcación',0,19),(1643,'General Ballivian',0,19),(1644,'General Güemes',0,19),(1645,'General Mosconi',0,19),(1646,'General Pizarro',0,19),(1647,'Guachipas',0,19),(1648,'Hipólito Yrigoyen',0,19),(1649,'Iruyá',0,19),(1650,'Isla De Cañas',0,19),(1651,'Joaquín V. Gonzalez',0,19),(1652,'La Caldera',0,19),(1653,'La Candelaria',0,19),(1654,'La Merced',0,19),(1655,'La Poma',0,19),(1656,'La Viña',0,19),(1657,'Las Lajitas',0,19),(1658,'Los Toldos',0,19),(1659,'Metán',0,19),(1660,'Molinos',0,19),(1661,'Nazareno',0,19),(1662,'Orán',0,19),(1663,'Payogasta',0,19),(1664,'Pichanal',0,19),(1665,'Profesor Salvador Mazza',0,19),(1666,'Río Piedras',0,19),(1667,'Rivadavia Banda Norte',0,19),(1668,'Rivadavia Banda Sur',0,19),(1669,'Rosario de La Frontera',0,19),(1670,'Rosario de Lerma',0,19),(1671,'Saclantás',0,19),(1672,'Salta',0,19),(1673,'San Antonio',0,19),(1674,'San Carlos',0,19),(1675,'San José De Metán',0,19),(1676,'San Ramón',0,19),(1677,'Santa Victoria Este',0,19),(1678,'Santa Victoria Oeste',0,19),(1679,'Tartagal',0,19),(1680,'Tolar Grande',0,19),(1681,'Urundel',0,19),(1682,'Vaqueros',0,19),(1683,'Villa San Lorenzo',0,19),(1684,'Albardón',0,20),(1685,'Angaco',0,20),(1686,'Calingasta',0,20),(1687,'Capital',0,20),(1688,'Caucete',0,20),(1689,'Chimbas',0,20),(1690,'Iglesia',0,20),(1691,'Jachal',0,20),(1692,'Nueve de Julio',0,20),(1693,'Pocito',0,20),(1694,'Rawson',0,20),(1695,'Rivadavia',0,20),(1696,'San Juan',0,20),(1697,'San Martín',0,20),(1698,'Santa Lucía',0,20),(1699,'Sarmiento',0,20),(1700,'Ullum',0,20),(1701,'Valle Fértil',0,20),(1702,'Veinticinco de Mayo',0,20),(1703,'Zonda',0,20),(1704,'Alto Pelado',0,21),(1705,'Alto Pencoso',0,21),(1706,'Anchorena',0,21),(1707,'Arizona',0,21),(1708,'Bagual',0,21),(1709,'Balde',0,21),(1710,'Batavia',0,21),(1711,'Beazley',0,21),(1712,'Buena Esperanza',0,21),(1713,'Candelaria',0,21),(1714,'Capital',0,21),(1715,'Carolina',0,21),(1716,'Carpintería',0,21),(1717,'Concarán',0,21),(1718,'Cortaderas',0,21),(1719,'El Morro',0,21),(1720,'El Trapiche',0,21),(1721,'El Volcán',0,21),(1722,'Fortín El Patria',0,21),(1723,'Fortuna',0,21),(1724,'Fraga',0,21),(1725,'Juan Jorba',0,21),(1726,'Juan Llerena',0,21),(1727,'Juana Koslay',0,21),(1728,'Justo Daract',0,21),(1729,'La Calera',0,21),(1730,'La Florida',0,21),(1731,'La Punilla',0,21),(1732,'La Toma',0,21),(1733,'Lafinur',0,21),(1734,'Las Aguadas',0,21),(1735,'Las Chacras',0,21),(1736,'Las Lagunas',0,21),(1737,'Las Vertientes',0,21),(1738,'Lavaisse',0,21),(1739,'Leandro N. Alem',0,21),(1740,'Los Molles',0,21),(1741,'Luján',0,21),(1742,'Mercedes',0,21),(1743,'Merlo',0,21),(1744,'Naschel',0,21),(1745,'Navia',0,21),(1746,'Nogolí',0,21),(1747,'Nueva Galia',0,21),(1748,'Papagayos',0,21),(1749,'Paso Grande',0,21),(1750,'Potrero de Los Funes',0,21),(1751,'Quines',0,21),(1752,'Renca',0,21),(1753,'Saladillo',0,21),(1754,'San Francisco',0,21),(1755,'San Gerónimo',0,21),(1756,'San Martín',0,21),(1757,'San Pablo',0,21),(1758,'Santa Rosa de Conlara',0,21),(1759,'Talita',0,21),(1760,'Tilisarao',0,21),(1761,'Unión',0,21),(1762,'Villa de La Quebrada',0,21),(1763,'Villa de Praga',0,21),(1764,'Villa del Carmen',0,21),(1765,'Villa General Roca',0,21),(1766,'Villa Larca',0,21),(1767,'Villa Mercedes',0,21),(1768,'Zanjitas',0,21),(1769,'Calafate',0,22),(1770,'Caleta Olivia',0,22),(1771,'Cañadón Seco',0,22),(1772,'Comandante Piedrabuena',0,22),(1773,'El Calafate',0,22),(1774,'El Chaltén',0,22),(1775,'Gobernador Gregores',0,22),(1776,'Hipólito Yrigoyen',0,22),(1777,'Jaramillo',0,22),(1778,'Koluel Kaike',0,22),(1779,'Las Heras',0,22),(1780,'Los Antiguos',0,22),(1781,'Perito Moreno',0,22),(1782,'Pico Truncado',0,22),(1783,'Puerto Deseado',0,22),(1784,'Puerto San Julián',0,22),(1785,'Puerto Santa Cruz',0,22),(1786,'Río Cuarto',0,22),(1787,'Río Gallegos',0,22),(1788,'Río Turbio',0,22),(1789,'Tres Lagos',0,22),(1790,'Veintiocho De Noviembre',0,22),(1791,'Aarón Castellanos',0,23),(1792,'Acebal',0,23),(1793,'Aguará Grande',0,23),(1794,'Albarellos',0,23),(1795,'Alcorta',0,23),(1796,'Aldao',0,23),(1797,'Alejandra',0,23),(1798,'Álvarez',0,23),(1799,'Ambrosetti',0,23),(1800,'Amenábar',0,23),(1801,'Angélica',0,23),(1802,'Angeloni',0,23),(1803,'Arequito',0,23),(1804,'Arminda',0,23),(1805,'Armstrong',0,23),(1806,'Arocena',0,23),(1807,'Arroyo Aguiar',0,23),(1808,'Arroyo Ceibal',0,23),(1809,'Arroyo Leyes',0,23),(1810,'Arroyo Seco',0,23),(1811,'Arrufó',0,23),(1812,'Arteaga',0,23),(1813,'Ataliva',0,23),(1814,'Aurelia',0,23),(1815,'Avellaneda',0,23),(1816,'Barrancas',0,23),(1817,'Bauer Y Sigel',0,23),(1818,'Bella Italia',0,23),(1819,'Berabevú',0,23),(1820,'Berna',0,23),(1821,'Bernardo de Irigoyen',0,23),(1822,'Bigand',0,23),(1823,'Bombal',0,23),(1824,'Bouquet',0,23),(1825,'Bustinza',0,23),(1826,'Cabal',0,23),(1827,'Cacique Ariacaiquin',0,23),(1828,'Cafferata',0,23),(1829,'Calchaquí',0,23),(1830,'Campo Andino',0,23),(1831,'Campo Piaggio',0,23),(1832,'Cañada de Gómez',0,23),(1833,'Cañada del Ucle',0,23),(1834,'Cañada Rica',0,23),(1835,'Cañada Rosquín',0,23),(1836,'Candioti',0,23),(1837,'Capital',0,23),(1838,'Capitán Bermúdez',0,23),(1839,'Capivara',0,23),(1840,'Carcarañá',0,23),(1841,'Carlos Pellegrini',0,23),(1842,'Carmen',0,23),(1843,'Carmen Del Sauce',0,23),(1844,'Carreras',0,23),(1845,'Carrizales',0,23),(1846,'Casalegno',0,23),(1847,'Casas',0,23),(1848,'Casilda',0,23),(1849,'Castelar',0,23),(1850,'Castellanos',0,23),(1851,'Cayastá',0,23),(1852,'Cayastacito',0,23),(1853,'Centeno',0,23),(1854,'Cepeda',0,23),(1855,'Ceres',0,23),(1856,'Chabás',0,23),(1857,'Chañar Ladeado',0,23),(1858,'Chapuy',0,23),(1859,'Chovet',0,23),(1860,'Christophersen',0,23),(1861,'Classon',0,23),(1862,'Coronel Arnold',0,23),(1863,'Coronel Bogado',0,23),(1864,'Coronel Dominguez',0,23),(1865,'Coronel Fraga',0,23),(1866,'Colonia Aldao',0,23),(1867,'Colonia Ana',0,23),(1868,'Colonia Belgrano',0,23),(1869,'Colonia Bicha',0,23),(1870,'Colonia Bigand',0,23),(1871,'Colonia Bossi',0,23),(1872,'Colonia Cavour',0,23),(1873,'Colonia Cello',0,23),(1874,'Colonia Dolores',0,23),(1875,'Colonia Dos Rosas',0,23),(1876,'Colonia Durán',0,23),(1877,'Colonia Iturraspe',0,23),(1878,'Colonia Margarita',0,23),(1879,'Colonia Mascias',0,23),(1880,'Colonia Raquel',0,23),(1881,'Colonia Rosa',0,23),(1882,'Colonia San José',0,23),(1883,'Constanza',0,23),(1884,'Coronda',0,23),(1885,'Correa',0,23),(1886,'Crispi',0,23),(1887,'Cululú',0,23),(1888,'Curupayti',0,23),(1889,'Desvio Arijón',0,23),(1890,'Diaz',0,23),(1891,'Diego de Alvear',0,23),(1892,'Egusquiza',0,23),(1893,'El Arazá',0,23),(1894,'El Rabón',0,23),(1895,'El Sombrerito',0,23),(1896,'El Trébol',0,23),(1897,'Elisa',0,23),(1898,'Elortondo',0,23),(1899,'Emilia',0,23),(1900,'Empalme San Carlos',0,23),(1901,'Empalme Villa Constitucion',0,23),(1902,'Esmeralda',0,23),(1903,'Esperanza',0,23),(1904,'Estación Alvear',0,23),(1905,'Estacion Clucellas',0,23),(1906,'Esteban Rams',0,23),(1907,'Esther',0,23),(1908,'Esustolia',0,23),(1909,'Eusebia',0,23),(1910,'Felicia',0,23),(1911,'Fidela',0,23),(1912,'Fighiera',0,23),(1913,'Firmat',0,23),(1914,'Florencia',0,23),(1915,'Fortín Olmos',0,23),(1916,'Franck',0,23),(1917,'Fray Luis Beltrán',0,23),(1918,'Frontera',0,23),(1919,'Fuentes',0,23),(1920,'Funes',0,23),(1921,'Gaboto',0,23),(1922,'Galisteo',0,23),(1923,'Gálvez',0,23),(1924,'Garabalto',0,23),(1925,'Garibaldi',0,23),(1926,'Gato Colorado',0,23),(1927,'Gobernador Crespo',0,23),(1928,'Gessler',0,23),(1929,'Godoy',0,23),(1930,'Golondrina',0,23),(1931,'General Gelly',0,23),(1932,'General Lagos',0,23),(1933,'Granadero Baigorria',0,23),(1934,'Gregoria Perez De Denis',0,23),(1935,'Grutly',0,23),(1936,'Guadalupe Norte',0,23),(1937,'Gödeken',0,23),(1938,'Helvecia',0,23),(1939,'Hersilia',0,23),(1940,'Hipatía',0,23),(1941,'Huanqueros',0,23),(1942,'Hugentobler',0,23),(1943,'Hughes',0,23),(1944,'Humberto 1º',0,23),(1945,'Humboldt',0,23),(1946,'Ibarlucea',0,23),(1947,'Ingeniero Chanourdie',0,23),(1948,'Intiyaco',0,23),(1949,'Ituzaingó',0,23),(1950,'Jacinto L. Aráuz',0,23),(1951,'Josefina',0,23),(1952,'Juan B. Molina',0,23),(1953,'Juan de Garay',0,23),(1954,'Juncal',0,23),(1955,'La Brava',0,23),(1956,'La Cabral',0,23),(1957,'La Camila',0,23),(1958,'La Chispa',0,23),(1959,'La Clara',0,23),(1960,'La Criolla',0,23),(1961,'La Gallareta',0,23),(1962,'La Lucila',0,23),(1963,'La Pelada',0,23),(1964,'La Penca',0,23),(1965,'La Rubia',0,23),(1966,'La Sarita',0,23),(1967,'La Vanguardia',0,23),(1968,'Labordeboy',0,23),(1969,'Laguna Paiva',0,23),(1970,'Landeta',0,23),(1971,'Lanteri',0,23),(1972,'Larrechea',0,23),(1973,'Las Avispas',0,23),(1974,'Las Bandurrias',0,23),(1975,'Las Garzas',0,23),(1976,'Las Palmeras',0,23),(1977,'Las Parejas',0,23),(1978,'Las Petacas',0,23),(1979,'Las Rosas',0,23),(1980,'Las Toscas',0,23),(1981,'Las Tunas',0,23),(1982,'Lazzarino',0,23),(1983,'Lehmann',0,23),(1984,'Llambi Campbell',0,23),(1985,'Logroño',0,23),(1986,'Loma Alta',0,23),(1987,'López',0,23),(1988,'Los Amores',0,23),(1989,'Los Cardos',0,23),(1990,'Los Laureles',0,23),(1991,'Los Molinos',0,23),(1992,'Los Quirquinchos',0,23),(1993,'Lucio V. Lopez',0,23),(1994,'Luis Palacios',0,23),(1995,'María Juana',0,23),(1996,'María Luisa',0,23),(1997,'María Susana',0,23),(1998,'María Teresa',0,23),(1999,'Maciel',0,23),(2000,'Maggiolo',0,23),(2001,'Malabrigo',0,23),(2002,'Marcelino Escalada',0,23),(2003,'Margarita',0,23),(2004,'Matilde',0,23),(2005,'Mauá',0,23),(2006,'Máximo Paz',0,23),(2007,'Melincué',0,23),(2008,'Miguel Torres',0,23),(2009,'Moisés Ville',0,23),(2010,'Monigotes',0,23),(2011,'Monje',0,23),(2012,'Monte Obscuridad',0,23),(2013,'Monte Vera',0,23),(2014,'Montefiore',0,23),(2015,'Montes de Oca',0,23),(2016,'Murphy',0,23),(2017,'Ñanducita',0,23),(2018,'Naré',0,23),(2019,'Nelson',0,23),(2020,'Nicanor E. Molinas',0,23),(2021,'Nuevo Torino',0,23),(2022,'Oliveros',0,23),(2023,'Palacios',0,23),(2024,'Pavón',0,23),(2025,'Pavón Arriba',0,23),(2026,'Pedro Gómez Cello',0,23),(2027,'Pérez',0,23),(2028,'Peyrano',0,23),(2029,'Piamonte',0,23),(2030,'Pilar',0,23),(2031,'Piñero',0,23),(2032,'Plaza Clucellas',0,23),(2033,'Portugalete',0,23),(2034,'Pozo Borrado',0,23),(2035,'Progreso',0,23),(2036,'Providencia',0,23),(2037,'Presidente Roca',0,23),(2038,'Pueblo Andino',0,23),(2039,'Pueblo Esther',0,23),(2040,'Pueblo General San Martín',0,23),(2041,'Pueblo Irigoyen',0,23),(2042,'Pueblo Marini',0,23),(2043,'Pueblo Muñoz',0,23),(2044,'Pueblo Uranga',0,23),(2045,'Pujato',0,23),(2046,'Pujato Norte',0,23),(2047,'Rafaela',0,23),(2048,'Ramayón',0,23),(2049,'Ramona',0,23),(2050,'Reconquista',0,23),(2051,'Recreo',0,23),(2052,'Ricardone',0,23),(2053,'Rivadavia',0,23),(2054,'Roldán',0,23),(2055,'Romang',0,23),(2056,'Rosario',0,23),(2057,'Rueda',0,23),(2058,'Rufino',0,23),(2059,'Sa Pereira',0,23),(2060,'Saguier',0,23),(2061,'Saladero M. Cabal',0,23),(2062,'Salto Grande',0,23),(2063,'San Agustín',0,23),(2064,'San Antonio de Obligado',0,23),(2065,'San Bernardo (N.J.)',0,23),(2066,'San Bernardo (S.J.)',0,23),(2067,'San Carlos Centro',0,23),(2068,'San Carlos Norte',0,23),(2069,'San Carlos Sur',0,23),(2070,'San Cristóbal',0,23),(2071,'San Eduardo',0,23),(2072,'San Eugenio',0,23),(2073,'San Fabián',0,23),(2074,'San FCerro de Santa Fé',0,23),(2075,'San Genaro',0,23),(2076,'San Genaro Norte',0,23),(2077,'San Gregorio',0,23),(2078,'San Guillermo',0,23),(2079,'San Javier',0,23),(2080,'San Jerónimo del Sauce',0,23),(2081,'San Jerónimo Norte',0,23),(2082,'San Jerónimo Sur',0,23),(2083,'San Jorge',0,23),(2084,'San José de La Esquina',0,23),(2085,'San José del Rincón',0,23),(2086,'San Justo',0,23),(2087,'San Lorenzo',0,23),(2088,'San Mariano',0,23),(2089,'San Martín de Las Escobas',0,23),(2090,'San Martín Norte',0,23),(2091,'San Vicente',0,23),(2092,'Sancti Spititu',0,23),(2093,'Sanford',0,23),(2094,'Santo Domingo',0,23),(2095,'Santo Tomé',0,23),(2096,'Santurce',0,23),(2097,'Sargento Cabral',0,23),(2098,'Sarmiento',0,23),(2099,'Sastre',0,23),(2100,'Sauce Viejo',0,23),(2101,'Serodino',0,23),(2102,'Silva',0,23),(2103,'Soldini',0,23),(2104,'Soledad',0,23),(2105,'Soutomayor',0,23),(2106,'Santa Clara de Buena Vista',0,23),(2107,'Santa Clara de Saguier',0,23),(2108,'Santa Isabel',0,23),(2109,'Santa Margarita',0,23),(2110,'Santa Maria Centro',0,23),(2111,'Santa María Norte',0,23),(2112,'Santa Rosa',0,23),(2113,'Santa Teresa',0,23),(2114,'Suardi',0,23),(2115,'Sunchales',0,23),(2116,'Susana',0,23),(2117,'Tacuarendí',0,23),(2118,'Tacural',0,23),(2119,'Tartagal',0,23),(2120,'Teodelina',0,23),(2121,'Theobald',0,23),(2122,'Timbúes',0,23),(2123,'Toba',0,23),(2124,'Tortugas',0,23),(2125,'Tostado',0,23),(2126,'Totoras',0,23),(2127,'Traill',0,23),(2128,'Venado Tuerto',0,23),(2129,'Vera',0,23),(2130,'Vera y Pintado',0,23),(2131,'Videla',0,23),(2132,'Vila',0,23),(2133,'Villa Amelia',0,23),(2134,'Villa Ana',0,23),(2135,'Villa Cañas',0,23),(2136,'Villa Constitución',0,23),(2137,'Villa Eloísa',0,23),(2138,'Villa Gobernador Gálvez',0,23),(2139,'Villa Guillermina',0,23),(2140,'Villa Minetti',0,23),(2141,'Villa Mugueta',0,23),(2142,'Villa Ocampo',0,23),(2143,'Villa San José',0,23),(2144,'Villa Saralegui',0,23),(2145,'Villa Trinidad',0,23),(2146,'Villada',0,23),(2147,'Virginia',0,23),(2148,'Wheelwright',0,23),(2149,'Zavalla',0,23),(2150,'Zenón Pereira',0,23),(2151,'Añatuya',0,24),(2152,'Árraga',0,24),(2153,'Bandera',0,24),(2154,'Bandera Bajada',0,24),(2155,'Beltrán',0,24),(2156,'Brea Pozo',0,24),(2157,'Campo Gallo',0,24),(2158,'Capital',0,24),(2159,'Chilca Juliana',0,24),(2160,'Choya',0,24),(2161,'Clodomira',0,24),(2162,'Colonia Alpina',0,24),(2163,'Colonia Dora',0,24),(2164,'Colonia El Simbolar Robles',0,24),(2165,'El Bobadal',0,24),(2166,'El Charco',0,24),(2167,'El Mojón',0,24),(2168,'Estación Atamisqui',0,24),(2169,'Estación Simbolar',0,24),(2170,'Fernández',0,24),(2171,'Fortín Inca',0,24),(2172,'Frías',0,24),(2173,'Garza',0,24),(2174,'Gramilla',0,24),(2175,'Guardia Escolta',0,24),(2176,'Herrera',0,24),(2177,'Icaño',0,24),(2178,'Ingeniero Forres',0,24),(2179,'La Banda',0,24),(2180,'La Cañada',0,24),(2181,'Laprida',0,24),(2182,'Lavalle',0,24),(2183,'Loreto',0,24),(2184,'Los Juríes',0,24),(2185,'Los Núñez',0,24),(2186,'Los Pirpintos',0,24),(2187,'Los Quiroga',0,24),(2188,'Los Telares',0,24),(2189,'Lugones',0,24),(2190,'Malbrán',0,24),(2191,'Matara',0,24),(2192,'Medellín',0,24),(2193,'Monte Quemado',0,24),(2194,'Nueva Esperanza',0,24),(2195,'Nueva Francia',0,24),(2196,'Palo Negro',0,24),(2197,'Pampa de Los Guanacos',0,24),(2198,'Pinto',0,24),(2199,'Pozo Hondo',0,24),(2200,'Quimilí',0,24),(2201,'Real Sayana',0,24),(2202,'Sachayoj',0,24),(2203,'San Pedro de Guasayán',0,24),(2204,'Selva',0,24),(2205,'Sol de Julio',0,24),(2206,'Sumampa',0,24),(2207,'Suncho Corral',0,24),(2208,'Taboada',0,24),(2209,'Tapso',0,24),(2210,'Termas de Rio Hondo',0,24),(2211,'Tintina',0,24),(2212,'Tomas Young',0,24),(2213,'Vilelas',0,24),(2214,'Villa Atamisqui',0,24),(2215,'Villa La Punta',0,24),(2216,'Villa Ojo de Agua',0,24),(2217,'Villa Río Hondo',0,24),(2218,'Villa Salavina',0,24),(2219,'Villa Unión',0,24),(2220,'Vilmer',0,24),(2221,'Weisburd',0,24),(2222,'Río Grande',0,25),(2223,'Tolhuin',0,25),(2224,'Ushuaia',0,25),(2225,'Acheral',0,26),(2226,'Agua Dulce',0,26),(2227,'Aguilares',0,26),(2228,'Alderetes',0,26),(2229,'Alpachiri',0,26),(2230,'Alto Verde',0,26),(2231,'Amaicha del Valle',0,26),(2232,'Amberes',0,26),(2233,'Ancajuli',0,26),(2234,'Arcadia',0,26),(2235,'Atahona',0,26),(2236,'Banda del Río Sali',0,26),(2237,'Bella Vista',0,26),(2238,'Buena Vista',0,26),(2239,'Burruyacú',0,26),(2240,'Capitán Cáceres',0,26),(2241,'Cevil Redondo',0,26),(2242,'Choromoro',0,26),(2243,'Ciudacita',0,26),(2244,'Colalao del Valle',0,26),(2245,'Colombres',0,26),(2246,'Concepción',0,26),(2247,'Delfín Gallo',0,26),(2248,'El Bracho',0,26),(2249,'El Cadillal',0,26),(2250,'El Cercado',0,26),(2251,'El Chañar',0,26),(2252,'El Manantial',0,26),(2253,'El Mojón',0,26),(2254,'El Mollar',0,26),(2255,'El Naranjito',0,26),(2256,'El Naranjo',0,26),(2257,'El Polear',0,26),(2258,'El Puestito',0,26),(2259,'El Sacrificio',0,26),(2260,'El Timbó',0,26),(2261,'Escaba',0,26),(2262,'Esquina',0,26),(2263,'Estación Aráoz',0,26),(2264,'Famaillá',0,26),(2265,'Gastone',0,26),(2266,'Gobernador Garmendia',0,26),(2267,'Gobernador Piedrabuena',0,26),(2268,'Graneros',0,26),(2269,'Huasa Pampa',0,26),(2270,'Juan Bautista Alberdi',0,26),(2271,'La Cocha',0,26),(2272,'La Esperanza',0,26),(2273,'La Florida',0,26),(2274,'La Ramada',0,26),(2275,'La Trinidad',0,26),(2276,'Lamadrid',0,26),(2277,'Las Cejas',0,26),(2278,'Las Talas',0,26),(2279,'Las Talitas',0,26),(2280,'Los Bulacio',0,26),(2281,'Los Gómez',0,26),(2282,'Los Nogales',0,26),(2283,'Los Pereyra',0,26),(2284,'Los Pérez',0,26),(2285,'Los Puestos',0,26),(2286,'Los Ralos',0,26),(2287,'Los Sarmientos',0,26),(2288,'Los Sosa',0,26),(2289,'Lules',0,26),(2290,'Manuel García Fernández',0,26),(2291,'Manuela Pedraza',0,26),(2292,'Medinas',0,26),(2293,'Monte Bello',0,26),(2294,'Monteagudo',0,26),(2295,'Monteros',0,26),(2296,'Padre Monti',0,26),(2297,'Pampa Mayo',0,26),(2298,'Quilmes',0,26),(2299,'Raco',0,26),(2300,'Ranchillos',0,26),(2301,'Río Chico',0,26),(2302,'Río Colorado',0,26),(2303,'Río Seco',0,26),(2304,'Rumi Punco',0,26),(2305,'San Andrés',0,26),(2306,'San Felipe',0,26),(2307,'San Ignacio',0,26),(2308,'San Javier',0,26),(2309,'San José',0,26),(2310,'San Miguel de Tucumán',0,26),(2311,'San Pedro',0,26),(2312,'San Pedro de Colalao',0,26),(2313,'Santa Rosa de Leales',0,26),(2314,'Sargento Moya',0,26),(2315,'Siete de Abril',0,26),(2316,'Simoca',0,26),(2317,'Soldado Maldonado',0,26),(2318,'Santa Ana',0,26),(2319,'Santa Cruz',0,26),(2320,'Santa Lucía',0,26),(2321,'Taco Ralo',0,26),(2322,'Tafí del Valle',0,26),(2323,'Tafí Viejo',0,26),(2324,'Tapia',0,26),(2325,'Teniente Berdina',0,26),(2326,'Trancas',0,26),(2327,'Villa Belgrano',0,26),(2328,'Villa Benjamín Araoz',0,26),(2329,'Villa Chiligasta',0,26),(2330,'Villa de Leales',0,26),(2331,'Villa Quinteros',0,26),(2332,'Yánima',0,26),(2333,'Yerba Buena',0,26),(2334,'Yerba Buena (S)',0,26),(2335,'Agronomía',0,4),(2336,'Almagro',0,4),(2337,'Balvanera',0,4),(2338,'Barracas',0,4),(2339,'Belgrano',0,4),(2340,'Boedo',0,4),(2341,'Caballito',0,4),(2342,'Chacarita',0,4),(2343,'Coghlan',0,4),(2344,'Colegiales',0,4),(2345,'Constitución',0,4),(2346,'Flores',0,4),(2347,'Floresta',0,4),(2348,'La Boca',0,4),(2349,'La Paternal',0,4),(2350,'Liniers',0,4),(2351,'Mataderos',0,4),(2352,'Monte Castro',0,4),(2353,'Monserrat',0,4),(2354,'Nueva Pompeya',0,4),(2355,'Núñez',0,4),(2356,'Palermo',0,4),(2357,'Parque Avellaneda',0,4),(2358,'Parque Chacabuco',0,4),(2359,'Parque Chas',0,4),(2360,'Parque Patricios',0,4),(2361,'Puerto Madero',0,4),(2362,'Recoleta',0,4),(2363,'Retiro',0,4),(2364,'Saavedra',0,4),(2365,'San Cristóbal',0,4),(2366,'San Nicolás',0,4),(2367,'San Telmo',0,4),(2368,'Vélez Sársfield',0,4),(2369,'Versalles',0,4),(2370,'Villa Crespo',0,4),(2371,'Villa del Parque',0,4),(2372,'Villa Devoto',0,4),(2373,'Villa General Mitre',0,4),(2374,'Villa Lugano',0,4),(2375,'Villa Luro',0,4),(2376,'Villa Ortúzar',0,4),(2377,'Villa Pueyrredón',0,4),(2378,'Villa Real',0,4),(2379,'Villa Riachuelo',0,4),(2380,'Villa Santa Rita',0,4),(2381,'Villa Soldati',0,4),(2382,'Villa Urquiza',0,4),(2383,'Sartirt',0,27);
/*!40000 ALTER TABLE `ciudades` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `direccion`
--

DROP TABLE IF EXISTS `direccion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `direccion` (
  `id_direccion` int(11) NOT NULL AUTO_INCREMENT,
  `calle` text NOT NULL,
  `altura` int(11) NOT NULL,
  `codigo_postal` varchar(45) DEFAULT NULL,
  `latitud` geometry DEFAULT NULL,
  `longitud` geometry DEFAULT NULL,
  `id_ciudad` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_direccion`),
  KEY `fk_direccion_id_ciudad` (`id_ciudad`),
  CONSTRAINT `fk_direccion_id_ciudad` FOREIGN KEY (`id_ciudad`) REFERENCES `ciudades` (`idciudades`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `direccion`
--

LOCK TABLES `direccion` WRITE;
/*!40000 ALTER TABLE `direccion` DISABLE KEYS */;
INSERT INTO `direccion` VALUES (2,'Paso',123,'345',NULL,NULL,6),(3,'Av Astral',333,'123',NULL,NULL,3),(8,'',0,'0',NULL,NULL,NULL),(10,'',0,'0',NULL,NULL,NULL),(11,'',0,'0',NULL,NULL,NULL),(12,'',0,'0',NULL,NULL,NULL),(13,'',0,'0',NULL,NULL,NULL),(16,'Salaima',4578,'',NULL,NULL,2383),(17,'Paseo colon',850,'1234',NULL,NULL,2367),(18,'Plaza',123,'1212',NULL,NULL,20),(19,'Charlone',345,'1212',NULL,NULL,15),(20,'Av Forest',2345,'1212',NULL,NULL,18),(21,'Melo',28,'',NULL,NULL,205);
/*!40000 ALTER TABLE `direccion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `evento`
--

DROP TABLE IF EXISTS `evento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `evento` (
  `id_evento` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(45) NOT NULL,
  `descripcion` text,
  `fecha_desde` date NOT NULL,
  `fecha_hasta` date NOT NULL,
  `estado` varchar(45) NOT NULL,
  `id_centro` int(11) NOT NULL,
  PRIMARY KEY (`id_evento`),
  KEY `fk_Evento_Centro1_idx` (`id_centro`),
  KEY `fk_evento_Centro` (`id_centro`),
  KEY `fk_evento_id_centro` (`id_centro`),
  CONSTRAINT `fk_evento_id_centro` FOREIGN KEY (`id_centro`) REFERENCES `centro` (`id_centro`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `evento`
--

LOCK TABLES `evento` WRITE;
/*!40000 ALTER TABLE `evento` DISABLE KEYS */;
INSERT INTO `evento` VALUES (8,'A','Evento con fotos y una descripcion un poco mas larga de lo normal para ver como queda distribuida la pagina de inicio de eventos donde se muestran todos los datos principales del evento.','2012-10-01','2012-10-04','No comenzado',15),(10,'C','La descripcion del evento no es muy larga','2012-09-29','2012-11-02','No publicado',11),(16,'D','Una descripcion como la gente','2012-09-29','2012-11-10','No comenzado',11),(41,'Evanto con descrip muy larga','During the sprint review the project is assessed against the sprint goal determined during the Sprint planning meeting. Ideally the team has completed each product backlog item brought into the sprint, but it is more important that they achieve the overall goal of the sprint.','2012-09-29','2012-09-29','No publicado',13),(42,'Otro evento','Descripcion normal','2012-09-29','2012-09-29','No publicado',13),(43,'Otro evento','Descripcion normal','2012-09-29','2012-09-29','No publicado',13);
/*!40000 ALTER TABLE `evento` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orador`
--

DROP TABLE IF EXISTS `orador`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `orador` (
  `especialidad` varchar(45) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  PRIMARY KEY (`id_usuario`),
  KEY `fk_Orador_Usuario1_idx` (`id_usuario`),
  KEY `fk_orador_id_usuario` (`id_usuario`),
  CONSTRAINT `fk_orador_id_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orador`
--

LOCK TABLES `orador` WRITE;
/*!40000 ALTER TABLE `orador` DISABLE KEYS */;
INSERT INTO `orador` VALUES ('PHP',3265412),('Base de Datos',24567834),('Actor',32564789),('Discursos motivacionales',34567567);
/*!40000 ALTER TABLE `orador` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `paises`
--

DROP TABLE IF EXISTS `paises`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `paises` (
  `idpaises` int(11) NOT NULL AUTO_INCREMENT,
  `pais` varchar(60) CHARACTER SET latin1 NOT NULL DEFAULT '',
  PRIMARY KEY (`idpaises`)
) ENGINE=InnoDB AUTO_INCREMENT=197 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `paises`
--

LOCK TABLES `paises` WRITE;
/*!40000 ALTER TABLE `paises` DISABLE KEYS */;
INSERT INTO `paises` VALUES (4,'Argentina'),(5,'Afganistán'),(6,'Albania'),(7,'Argelia'),(8,'Andorra'),(9,'Angola'),(10,'Antigua y Barbuda'),(11,'Armenia'),(12,'Australia'),(13,'Austria'),(14,'Azerbaiyán'),(15,'Bahamas'),(16,'Bahrain'),(17,'Bangladesh'),(18,'Barbados'),(19,'Bielorrusia'),(20,'Bélgica'),(21,'Nicaragua'),(22,'Benín'),(23,'Bután'),(24,'Bolivia'),(25,'Bosnia y Herzegovina'),(26,'Botswana'),(27,'Brasil'),(28,'Brunei'),(29,'Bulgaria'),(30,'Burkina Faso'),(31,'Burundi'),(32,'Camboya'),(33,'Camerún'),(34,'Canadá'),(35,'Cape Verde'),(36,'República Centroafricana'),(37,'Chad'),(38,'Chile'),(39,'China'),(40,'Colombi'),(41,'Comoras'),(42,'Congo (Brazzaville)'),(43,'Congo'),(44,'Costa Rica'),(45,'Costa de Marfil'),(46,'Croacia'),(47,'Cuba'),(48,'Chipre'),(49,'República Checa'),(50,'Dinamarca'),(51,'Djibouti'),(52,'Dominica'),(53,'República Dominicana'),(54,'Timor Oriental (Timor Timur)'),(55,'Ecuador'),(56,'Egipto'),(57,'El Salvador'),(58,'Guinea Ecuatorial'),(59,'Eritrea'),(60,'Estonia'),(61,'Etiopía'),(62,'Fiji'),(63,'Finlandia'),(64,'Francia'),(65,'Gabón'),(66,'Gambia'),(67,'Georgia'),(68,'Alemania'),(69,'Ghana'),(70,'Grecia'),(71,'Granada'),(72,'Guatemala'),(73,'Guinea'),(74,'Guinea-Bissau'),(75,'Guyana'),(76,'Haití'),(77,'Honduras'),(78,'Hungría'),(79,'Islandia'),(80,'India'),(81,'Indonesia'),(82,'Irán'),(83,'Iraq'),(84,'Irlanda'),(85,'Israel'),(86,'Italia'),(87,'Jamaica'),(88,'Japón'),(89,'Jordan'),(90,'Kazajstán'),(91,'Kenia'),(92,'Kiribati'),(93,'Corea del Norte'),(94,'Corea del Sur'),(95,'Kuwait'),(96,'Kirguistán'),(97,'Laos'),(98,'Letonia'),(99,'Líbano'),(100,'Lesotho'),(101,'Liberia'),(102,'Libia'),(103,'Liechtenstein'),(104,'Lituania'),(105,'Luxembourg'),(106,'Macedonia'),(107,'Madagascar'),(108,'Malawi'),(109,'Malasia'),(110,'Maldivas'),(111,'Mali'),(112,'Malta'),(113,'Marshall Islands'),(114,'Mauritania'),(115,'Mauricio'),(116,'Mexico'),(117,'Micronesia'),(118,'Moldavia'),(119,'Monaco'),(120,'Mongolia'),(121,'Marruecos'),(122,'Mozambique'),(123,'Myanmar'),(124,'Namibia'),(125,'Nauru'),(126,'Nepa'),(127,'Holanda'),(128,'Nueva Zelanda'),(129,'Nicaragua'),(130,'Niger'),(131,'Nigeria'),(132,'Noruega'),(133,'Omán'),(134,'Pakistán'),(135,'Palau'),(136,'Panamá'),(137,'Papúa Nueva Guinea'),(138,'Paraguay'),(139,'Peru'),(140,'México'),(141,'Polonia'),(142,'Portugal'),(143,'Qatar'),(144,'Rumanía'),(145,'Rusia'),(146,'Ruanda'),(147,'San Cristóbal y Nieves'),(148,'Santa Lucía'),(149,'San Vicente'),(150,'Samoa'),(151,'San Marino'),(152,'Santo Tomé y Príncipe'),(153,'Arabia Saudí'),(154,'Senegal'),(155,'Serbia y Montenegro'),(156,'Seychelles'),(157,'Sierra Leona'),(158,'Singapore'),(159,'Eslovaquia'),(160,'Eslovenia'),(161,'Islas Salomón'),(162,'Somalia'),(163,'Sudáfrica'),(164,'España'),(165,'Sri Lanka'),(166,'Sudan'),(167,'Surinam'),(168,'Swazilandia'),(169,'Suecia'),(170,'Suiza'),(171,'Siria'),(172,'Taiwan'),(173,'Tayikistán'),(174,'Tanzania'),(175,'Tailandia'),(176,'Togo'),(177,'Tonga'),(178,'Trinidad y Tobago'),(179,'Túnez'),(180,'Turquía'),(181,'Turkmenistán'),(182,'Tuvalu'),(183,'Uganda'),(184,'Ucrania'),(185,'Emiratos Árabes'),(186,'United Kingdom'),(187,'Estados Unidos'),(188,'Uruguay'),(189,'Uzbekistán'),(190,'Vanuatu'),(191,'Ciudad del Vaticano'),(192,'Venezuela'),(193,'Vietnam'),(194,'Yemen'),(195,'Zambia'),(196,'Zimbabwe');
/*!40000 ALTER TABLE `paises` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `provincias`
--

DROP TABLE IF EXISTS `provincias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `provincias` (
  `idprovincias` int(11) NOT NULL AUTO_INCREMENT,
  `provincia` varchar(45) NOT NULL DEFAULT '',
  `cant_busquedas` int(11) NOT NULL DEFAULT '0',
  `idpais` int(11) NOT NULL,
  PRIMARY KEY (`idprovincias`),
  KEY `idpais` (`idpais`),
  CONSTRAINT `idpais` FOREIGN KEY (`idpais`) REFERENCES `paises` (`idpaises`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `provincias`
--

LOCK TABLES `provincias` WRITE;
/*!40000 ALTER TABLE `provincias` DISABLE KEYS */;
INSERT INTO `provincias` VALUES (2,'Buenos Aires',0,4),(3,'Buenos Aires-GBA',0,4),(4,'Capital Federal',0,4),(5,'Catamarca',0,4),(6,'Chaco',0,4),(7,'Chubut',0,4),(8,'Córdoba',0,4),(9,'Corrientes',0,4),(10,'Entre Ríos',0,4),(11,'Formosa',0,4),(12,'Jujuy',0,4),(13,'La Pampa',0,4),(14,'La Rioja',0,4),(15,'Mendoza',0,4),(16,'Misiones',0,4),(17,'Neuquén',0,4),(18,'Río Negro',0,4),(19,'Salta',0,4),(20,'San Juan',0,4),(21,'San Luis',0,4),(22,'Santa Cruz',0,4),(23,'Santa Fe',0,4),(24,'Santiago del Estero',0,4),(25,'Tierra del Fuego',0,4),(26,'Tucumán',0,4),(27,'Afgas',0,5);
/*!40000 ALTER TABLE `provincias` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reserva`
--

DROP TABLE IF EXISTS `reserva`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reserva` (
  `id_usuario` int(11) NOT NULL,
  `id_reserva_hotel` int(11) NOT NULL,
  `id_reserva_vuelo` int(11) NOT NULL,
  PRIMARY KEY (`id_usuario`,`id_reserva_hotel`,`id_reserva_vuelo`),
  KEY `fk_Reserva_Reserva_Hotel1_idx` (`id_reserva_hotel`),
  KEY `fk_Reserva_Vuelo1_idx` (`id_reserva_vuelo`),
  KEY `fk_Reserva_Orador1_idx` (`id_usuario`),
  KEY `fk_reserva_id_usuario` (`id_usuario`),
  KEY `fk_reserva_id_reserva_hotel` (`id_reserva_hotel`),
  KEY `fk_reserva_id_reserva_vuelo` (`id_reserva_vuelo`),
  CONSTRAINT `fk_reserva_id_reserva_hotel` FOREIGN KEY (`id_reserva_hotel`) REFERENCES `reserva_hotel` (`id_reserva_hotel`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_reserva_id_reserva_vuelo` FOREIGN KEY (`id_reserva_vuelo`) REFERENCES `reserva_vuelo` (`id_reserva_vuelo`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_reserva_id_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `orador` (`id_usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reserva`
--

LOCK TABLES `reserva` WRITE;
/*!40000 ALTER TABLE `reserva` DISABLE KEYS */;
/*!40000 ALTER TABLE `reserva` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reserva_hotel`
--

DROP TABLE IF EXISTS `reserva_hotel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reserva_hotel` (
  `id_reserva_hotel` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(45) NOT NULL,
  `estrellas` int(11) DEFAULT NULL,
  `regimen` text,
  `check_in` time DEFAULT NULL,
  `check_out` time DEFAULT NULL,
  `cantidad_personas` int(11) DEFAULT NULL,
  `fecha_desde` date NOT NULL,
  `fecha_hasta` date NOT NULL,
  `id_direccion` int(11) NOT NULL,
  PRIMARY KEY (`id_reserva_hotel`),
  KEY `fk_reserva_hotel_id_direccion` (`id_direccion`),
  CONSTRAINT `fk_reserva_hotel_id_direccion` FOREIGN KEY (`id_direccion`) REFERENCES `direccion` (`id_direccion`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reserva_hotel`
--

LOCK TABLES `reserva_hotel` WRITE;
/*!40000 ALTER TABLE `reserva_hotel` DISABLE KEYS */;
/*!40000 ALTER TABLE `reserva_hotel` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reserva_vuelo`
--

DROP TABLE IF EXISTS `reserva_vuelo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reserva_vuelo` (
  `id_reserva_vuelo` int(11) NOT NULL AUTO_INCREMENT,
  `numero` int(11) NOT NULL,
  `hora_salida` datetime NOT NULL,
  `hora_llegada` datetime NOT NULL,
  `aerop_salida` varchar(45) NOT NULL,
  `aerop_llegada` varchar(45) NOT NULL,
  `escalas` text,
  `clase` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id_reserva_vuelo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reserva_vuelo`
--

LOCK TABLES `reserva_vuelo` WRITE;
/*!40000 ALTER TABLE `reserva_vuelo` DISABLE KEYS */;
/*!40000 ALTER TABLE `reserva_vuelo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuario`
--

DROP TABLE IF EXISTS `usuario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usuario` (
  `id_usuario` int(11) NOT NULL,
  `nombre` text NOT NULL,
  `apellido` text NOT NULL,
  `sexo` char(1) NOT NULL,
  `fecha_nacimiento` date NOT NULL,
  `estado_civil` text,
  `telefono` text,
  `email` text NOT NULL,
  `id_direccion` int(11) DEFAULT NULL,
  `profesion` varchar(45) NOT NULL,
  PRIMARY KEY (`id_usuario`),
  KEY `fk_usuario_id_direccion_idx` (`id_direccion`),
  CONSTRAINT `fk_usuario_id_direccion` FOREIGN KEY (`id_direccion`) REFERENCES `direccion` (`id_direccion`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario`
--

LOCK TABLES `usuario` WRITE;
/*!40000 ALTER TABLE `usuario` DISABLE KEYS */;
INSERT INTO `usuario` VALUES (3265412,'Florencia','Lopez','F','2012-02-12','soltero','1568956985','llopez@gmail.com',11,'Actriz'),(24567834,'Roberto','Gomez','M','2012-02-12','soltero','1598756452','rgomez@gmail.com',13,'Licenciado en Sistemas'),(32564789,'Lorenzo','Lamas','M','2010-03-08','soltero','1555663322','llamas@gmail.com',8,''),(34567567,'Maravilla','Martinez','M','2012-02-12','soltero','156562323','mmartinez@gmail.com',10,'Boxeador');
/*!40000 ALTER TABLE `usuario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuario_charla`
--

DROP TABLE IF EXISTS `usuario_charla`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usuario_charla` (
  `id_usuario` int(11) NOT NULL,
  `id_charla` int(11) NOT NULL,
  `asistio` int(11) DEFAULT '0' COMMENT 'V (1) o F (0) que confirma si el asistente efectivamente fue a la charla',
  PRIMARY KEY (`id_usuario`,`id_charla`),
  KEY `fk_intencion` (`id_usuario`),
  KEY `fk_intencion_charla` (`id_charla`),
  KEY `fk_usuario_charla_id_usuario` (`id_usuario`),
  KEY `fk_usuario_charla_id_charla` (`id_charla`),
  CONSTRAINT `fk_usuario_charla_id_charla` FOREIGN KEY (`id_charla`) REFERENCES `charla` (`id_charla`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_usuario_charla_id_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Tabla que indica que el usuario tiene intención de asistir a';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario_charla`
--

LOCK TABLES `usuario_charla` WRITE;
/*!40000 ALTER TABLE `usuario_charla` DISABLE KEYS */;
INSERT INTO `usuario_charla` VALUES (24567834,4,0),(32564789,4,0),(34567567,4,1);
/*!40000 ALTER TABLE `usuario_charla` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuario_evento`
--

DROP TABLE IF EXISTS `usuario_evento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usuario_evento` (
  `id_usuario` int(11) NOT NULL,
  `id_evento` int(11) NOT NULL,
  PRIMARY KEY (`id_usuario`,`id_evento`),
  KEY `fk_usuario_evento_Usuario` (`id_usuario`),
  KEY `fk_usuario_evento_Evento` (`id_evento`),
  KEY `fk_usuario_evento_id_usuario` (`id_usuario`),
  KEY `fk_usuario_evento_id_evento` (`id_evento`),
  CONSTRAINT `fk_usuario_evento_id_evento` FOREIGN KEY (`id_evento`) REFERENCES `evento` (`id_evento`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_usuario_evento_id_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Tabla que relaciona los asistentes con los eventos a los que';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario_evento`
--

LOCK TABLES `usuario_evento` WRITE;
/*!40000 ALTER TABLE `usuario_evento` DISABLE KEYS */;
/*!40000 ALTER TABLE `usuario_evento` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2012-10-01 13:26:46
