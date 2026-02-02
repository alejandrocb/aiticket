SET FOREIGN_KEY_CHECKS=0;

-- qajj678.clientes definition

CREATE TABLE `clientes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `telefono` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `direccion` text COLLATE utf8mb4_general_ci,
  `escenario` int NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email_unique` (`nombre`) USING BTREE,
  KEY `clientes_ibfk_1` (`escenario`),
  CONSTRAINT `clientes_ibfk_1` FOREIGN KEY (`escenario`) REFERENCES `escenarios` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- qajj678.contactos definition

CREATE TABLE `contactos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `cliente_id` int DEFAULT NULL,
  `email` varchar(255) COLLATE latin1_spanish_ci DEFAULT NULL,
  `telefono` varchar(50) COLLATE latin1_spanish_ci DEFAULT NULL,
  `nombre` varchar(255) COLLATE latin1_spanish_ci DEFAULT NULL,
  `cargo` varchar(100) COLLATE latin1_spanish_ci DEFAULT NULL,
  `creado_en` datetime DEFAULT CURRENT_TIMESTAMP,
  `actualizado_en` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `cliente_id` (`cliente_id`),
  CONSTRAINT `contactos_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

-- qajj678.escenarios definition

CREATE TABLE `escenarios` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- qajj678.estados_ticket definition

CREATE TABLE `estados_ticket` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `estilo` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `icono` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- qajj678.prioridades_ticket definition

CREATE TABLE `prioridades_ticket` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `estilo` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `icono` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `estilolista` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- qajj678.ticket_movimientos definition

CREATE TABLE `ticket_movimientos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `ticket_id` int NOT NULL,
  `tipo_movimiento` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `descripcion` text COLLATE utf8mb4_general_ci NOT NULL,
  `fecha_movimiento` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `usuario_id` int NOT NULL,
  `tipo_ticket_id` int DEFAULT '1',
  `imagen` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `media` json DEFAULT NULL,
  `auto` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ticket_id_fk` (`ticket_id`),
  KEY `usuario_id_fk` (`usuario_id`),
  KEY `fk_tipo_ticket` (`tipo_ticket_id`),
  CONSTRAINT `fk_tipo_ticket` FOREIGN KEY (`tipo_ticket_id`) REFERENCES `tipos_ticket` (`id`),
  CONSTRAINT `ticket_movimientos_ibfk_1` FOREIGN KEY (`ticket_id`) REFERENCES `tickets` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `ticket_movimientos_ibfk_2` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2321 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- qajj678.tickets definition

CREATE TABLE `tickets` (
  `id` int NOT NULL AUTO_INCREMENT,
  `cliente_id` int NOT NULL,
  `usuario_id` int NOT NULL,
  `tipo_ticket_id` int DEFAULT NULL,
  `prioridad_ticket_id` int DEFAULT NULL,
  `estado` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `descripcion` text COLLATE utf8mb4_general_ci,
  `fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `estado_ticket_id` int NOT NULL DEFAULT '1',
  `responsable_id` int DEFAULT NULL,
  `escenario_id` int NOT NULL DEFAULT '1',
  `media` json DEFAULT NULL,
  `fecha_inicio_publicacion` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cliente_id` (`cliente_id`),
  KEY `usuario_id` (`usuario_id`),
  KEY `tipo_ticket_id` (`tipo_ticket_id`),
  KEY `prioridad_ticket_id` (`prioridad_ticket_id`),
  KEY `tickets_tickets_FK` (`estado_ticket_id`),
  KEY `tickets_usuarios_FK` (`responsable_id`),
  KEY `escenario_id` (`escenario_id`),
  CONSTRAINT `tickets_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`),
  CONSTRAINT `tickets_ibfk_2` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`),
  CONSTRAINT `tickets_ibfk_3` FOREIGN KEY (`tipo_ticket_id`) REFERENCES `tipos_ticket` (`id`),
  CONSTRAINT `tickets_ibfk_4` FOREIGN KEY (`prioridad_ticket_id`) REFERENCES `prioridades_ticket` (`id`),
  CONSTRAINT `tickets_ibfk_5` FOREIGN KEY (`estado_ticket_id`) REFERENCES `estados_ticket` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `tickets_ibfk_6` FOREIGN KEY (`escenario_id`) REFERENCES `escenarios` (`id`),
  CONSTRAINT `tickets_usuarios_FK` FOREIGN KEY (`responsable_id`) REFERENCES `usuarios` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=893 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- qajj678.tickets_recurrentes definition

CREATE TABLE `tickets_recurrentes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `cliente_id` int NOT NULL,
  `tipo_ticket_id` int NOT NULL,
  `prioridad_ticket_id` int NOT NULL,
  `estado_ticket_id` int NOT NULL,
  `descripcion` text COLLATE latin1_spanish_ci,
  `responsable_id` int DEFAULT NULL,
  `escenario_id` int NOT NULL,
  `frecuencia` enum('diaria','semanal','mensual','anual') COLLATE latin1_spanish_ci NOT NULL,
  `dia_mes` int DEFAULT NULL,
  `dia_semana` int DEFAULT NULL,
  `mes` int DEFAULT NULL,
  `ultima_ejecucion` datetime DEFAULT NULL,
  `usuario_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `cliente_id` (`cliente_id`),
  KEY `tipo_ticket_id` (`tipo_ticket_id`),
  KEY `prioridad_ticket_id` (`prioridad_ticket_id`),
  KEY `estado_ticket_id` (`estado_ticket_id`),
  KEY `responsable_id` (`responsable_id`),
  KEY `escenario_id` (`escenario_id`),
  KEY `tickets_recurrentes_usuarios_FK` (`usuario_id`),
  CONSTRAINT `tickets_recurrentes_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`),
  CONSTRAINT `tickets_recurrentes_ibfk_2` FOREIGN KEY (`tipo_ticket_id`) REFERENCES `tipos_ticket` (`id`),
  CONSTRAINT `tickets_recurrentes_ibfk_3` FOREIGN KEY (`prioridad_ticket_id`) REFERENCES `prioridades_ticket` (`id`),
  CONSTRAINT `tickets_recurrentes_ibfk_4` FOREIGN KEY (`estado_ticket_id`) REFERENCES `estados_ticket` (`id`),
  CONSTRAINT `tickets_recurrentes_ibfk_5` FOREIGN KEY (`responsable_id`) REFERENCES `usuarios` (`id`),
  CONSTRAINT `tickets_recurrentes_ibfk_6` FOREIGN KEY (`escenario_id`) REFERENCES `escenarios` (`id`),
  CONSTRAINT `tickets_recurrentes_usuarios_FK` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;


-- qajj678.tipos_ticket definition

CREATE TABLE `tipos_ticket` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `estilo` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `icono` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- qajj678.tipos_usuario definition

CREATE TABLE `tipos_usuario` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `descripcion` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- qajj678.usuario_escenario definition

CREATE TABLE `usuario_escenario` (
  `usuario_id` int NOT NULL,
  `escenario_id` int NOT NULL,
  `activo` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`usuario_id`,`escenario_id`),
  KEY `usuario_escenario_ibfk_2` (`escenario_id`),
  CONSTRAINT `usuario_escenario_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `usuario_escenario_ibfk_2` FOREIGN KEY (`escenario_id`) REFERENCES `escenarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- qajj678.usuarios definition

CREATE TABLE `usuarios` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `tipo_usuario_id` int DEFAULT NULL,
  `imagen` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email_unique` (`email`),
  KEY `tipo_usuario_id` (`tipo_usuario_id`),
  CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`tipo_usuario_id`) REFERENCES `tipos_usuario` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
