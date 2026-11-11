-- ============================================================================
-- PROYECTO INTEGRADOR: ODONTOAGENDA CRM
-- Base de Datos: odontoagenda_crm
-- Plataforma Web de Gestión Operativa, Agendamiento Inteligente y Control de Inasistencias
-- Desarrollado para Clínica Odontológica DentalCare
-- ============================================================================

CREATE DATABASE IF NOT EXISTS `odontoagenda_crm` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `odontoagenda_crm`;

-- Desactivar restricciones temporales de claves foráneas para recreación limpia
SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS `notificaciones`;
DROP TABLE IF EXISTS `servicio_insumo`;
DROP TABLE IF EXISTS `insumos`;
DROP TABLE IF EXISTS `historial_atencion`;
DROP TABLE IF EXISTS `cita_servicio`;
DROP TABLE IF EXISTS `citas`;
DROP TABLE IF EXISTS `horarios`;
DROP TABLE IF EXISTS `servicios`;
DROP TABLE IF EXISTS `odontologos`;
DROP TABLE IF EXISTS `usuarios`;
SET FOREIGN_KEY_CHECKS = 1;

-- ----------------------------------------------------------------------------
-- 1. TABLA: usuarios
-- Pacientes, Odontólogos, Recepcionistas y Administradores
-- Incluye campo 'faltas_acumuladas' para control de inasistencias y bloqueo web
-- ----------------------------------------------------------------------------
CREATE TABLE `usuarios` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `nombre` VARCHAR(150) NOT NULL,
    `correo` VARCHAR(150) NOT NULL UNIQUE,
    `contrasena` VARCHAR(255) NOT NULL,
    `telefono` VARCHAR(20) NOT NULL,
    `documento_identidad` VARCHAR(20) NULL,
    `rol` ENUM('paciente', 'odontologo', 'recepcionista', 'admin') NOT NULL DEFAULT 'paciente',
    `faltas_acumuladas` INT UNSIGNED NOT NULL DEFAULT 0,
    `activo` TINYINT(1) NOT NULL DEFAULT 1,
    `remember_token` VARCHAR(100) NULL,
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_usuarios_rol` (`rol`),
    INDEX `idx_usuarios_correo` (`correo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- 2. TABLA: odontologos
-- Perfil profesional de los especialistas vinculados a la tabla usuarios
-- ----------------------------------------------------------------------------
CREATE TABLE `odontologos` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `usuario_id` BIGINT UNSIGNED NOT NULL UNIQUE,
    `especialidad` VARCHAR(120) NOT NULL,
    `registro_medico` VARCHAR(50) NULL,
    `estado` ENUM('activo', 'inactivo') NOT NULL DEFAULT 'activo',
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT `fk_odontologos_usuario` FOREIGN KEY (`usuario_id`) 
        REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- 3. TABLA: servicios
-- Catálogo de tratamientos odontológicos con duración en minutos y precio COP
-- ----------------------------------------------------------------------------
CREATE TABLE `servicios` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `nombre` VARCHAR(120) NOT NULL,
    `descripcion` TEXT NULL,
    `duracion_min` INT UNSIGNED NOT NULL COMMENT 'Duración estimada en minutos',
    `precio` DECIMAL(12, 2) NOT NULL COMMENT 'Precio en pesos colombianos (COP)',
    `activo` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- 4. TABLA: horarios
-- Franjas de atención laboral de cada especialista por día de la semana
-- dia_semana: 1=Lunes, 2=Martes, 3=Miércoles, 4=Jueves, 5=Viernes, 6=Sábado
-- ----------------------------------------------------------------------------
CREATE TABLE `horarios` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `odontologo_id` BIGINT UNSIGNED NOT NULL,
    `dia_semana` TINYINT UNSIGNED NOT NULL COMMENT '1=Lunes .. 6=Sabado',
    `hora_inicio` TIME NOT NULL,
    `hora_fin` TIME NOT NULL,
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT `fk_horarios_odontologo` FOREIGN KEY (`odontologo_id`) 
        REFERENCES `odontologos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    INDEX `idx_horarios_odontologo_dia` (`odontologo_id`, `dia_semana`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- 5. TABLA: citas
-- Entidad central del servicio médico y control de ciclo de vida
-- Estados: Solicitado -> Asignado -> En Proceso -> Atendido / Cancelado / No Asistio
-- ----------------------------------------------------------------------------
CREATE TABLE `citas` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `paciente_id` BIGINT UNSIGNED NOT NULL,
    `odontologo_id` BIGINT UNSIGNED NOT NULL,
    `fecha` DATE NOT NULL,
    `hora_inicio` TIME NOT NULL,
    `hora_fin` TIME NOT NULL,
    `estado` ENUM('Solicitado', 'Asignado', 'En Proceso', 'Atendido', 'Cancelado', 'No Asistio') 
        NOT NULL DEFAULT 'Solicitado',
    `duracion_total_min` INT UNSIGNED NOT NULL DEFAULT 30,
    `costo_total` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    `observaciones` TEXT NULL COMMENT 'Notas clínicas o motivo de consulta',
    `motivo_inasistencia` VARCHAR(255) NULL COMMENT 'Causa de la falta o justificación',
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT `fk_citas_paciente` FOREIGN KEY (`paciente_id`) 
        REFERENCES `usuarios` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT `fk_citas_odontologo` FOREIGN KEY (`odontologo_id`) 
        REFERENCES `odontologos` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
    INDEX `idx_citas_agenda` (`odontologo_id`, `fecha`, `hora_inicio`, `hora_fin`),
    INDEX `idx_citas_paciente` (`paciente_id`, `fecha`),
    INDEX `idx_citas_estado` (`estado`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- 6. TABLA: cita_servicio
-- Relación muchos a muchos para citas con selección aditiva de servicios
-- ----------------------------------------------------------------------------
CREATE TABLE `cita_servicio` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `cita_id` BIGINT UNSIGNED NOT NULL,
    `servicio_id` BIGINT UNSIGNED NOT NULL,
    `precio_historico` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
    CONSTRAINT `fk_cs_cita` FOREIGN KEY (`cita_id`) 
        REFERENCES `citas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_cs_servicio` FOREIGN KEY (`servicio_id`) 
        REFERENCES `servicios` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
    UNIQUE KEY `uk_cita_servicio` (`cita_id`, `servicio_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- 7. TABLA: historial_atencion
-- Trazabilidad de citas atendidas e inasistencias registradas
-- ----------------------------------------------------------------------------
CREATE TABLE `historial_atencion` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `cita_id` BIGINT UNSIGNED NOT NULL,
    `paciente_id` BIGINT UNSIGNED NOT NULL,
    `fecha` DATE NOT NULL,
    `tipo_registro` ENUM('Atencion Realizada', 'Inasistencia Registrada') NOT NULL,
    `resumen` TEXT NOT NULL,
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT `fk_historial_cita` FOREIGN KEY (`cita_id`) 
        REFERENCES `citas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_historial_paciente` FOREIGN KEY (`paciente_id`) 
        REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    INDEX `idx_historial_paciente_fecha` (`paciente_id`, `fecha`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- 8. TABLA: insumos
-- Inventario físico de suministros clínicos
-- ----------------------------------------------------------------------------
CREATE TABLE `insumos` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `nombre` VARCHAR(120) NOT NULL,
    `stock` INT NOT NULL DEFAULT 0,
    `stock_minimo` INT NOT NULL DEFAULT 10,
    `unidad_medida` VARCHAR(30) NOT NULL DEFAULT 'unidades',
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- 9. TABLA: servicio_insumo
-- Consumo estándar de insumos por cada procedimiento odontológico
-- ----------------------------------------------------------------------------
CREATE TABLE `servicio_insumo` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `servicio_id` BIGINT UNSIGNED NOT NULL,
    `insumo_id` BIGINT UNSIGNED NOT NULL,
    `cantidad_consumo` INT UNSIGNED NOT NULL DEFAULT 1,
    CONSTRAINT `fk_si_servicio` FOREIGN KEY (`servicio_id`) 
        REFERENCES `servicios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_si_insumo` FOREIGN KEY (`insumo_id`) 
        REFERENCES `insumos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    UNIQUE KEY `uk_servicio_insumo` (`servicio_id`, `insumo_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- 10. TABLA: notificaciones
-- Cola y bitácora de mensajes de correo transaccional
-- ----------------------------------------------------------------------------
CREATE TABLE `notificaciones` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `cita_id` BIGINT UNSIGNED NOT NULL,
    `tipo` ENUM('confirmacion', 'recordatorio', 'inasistencia', 'cambio_estado') NOT NULL,
    `fecha_envio` DATETIME NOT NULL,
    `estado_envio` ENUM('pendiente', 'enviado', 'fallido') NOT NULL DEFAULT 'pendiente',
    `mensaje` TEXT NULL,
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT `fk_notif_cita` FOREIGN KEY (`cita_id`) 
        REFERENCES `citas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- TRIGGERS DE BASE DE DATOS AVANZADA
-- ============================================================================

DELIMITER $$

-- ----------------------------------------------------------------------------
-- TRIGGER 1: trg_citas_before_insert (Prevención de Solapamiento de Horarios)
-- Regla 1: Un odontólogo no puede tener dos citas que compartan la misma franja
-- ----------------------------------------------------------------------------
DROP TRIGGER IF EXISTS `trg_citas_before_insert`$$
CREATE TRIGGER `trg_citas_before_insert`
BEFORE INSERT ON `citas`
FOR EACH ROW
BEGIN
    DECLARE v_solapados INT DEFAULT 0;

    -- Validar solapamiento solo para citas activas (no canceladas ni marcadas como No Asistio)
    IF NEW.estado NOT IN ('Cancelado', 'No Asistio') THEN
        SELECT COUNT(*) INTO v_solapados
        FROM `citas`
        WHERE `odontologo_id` = NEW.odontologo_id
          AND `fecha` = NEW.fecha
          AND `estado` NOT IN ('Cancelado', 'No Asistio')
          AND (
              (NEW.hora_inicio >= `hora_inicio` AND NEW.hora_inicio < `hora_fin`) OR
              (NEW.hora_fin > `hora_inicio` AND NEW.hora_fin <= `hora_fin`) OR
              (NEW.hora_inicio <= `hora_inicio` AND NEW.hora_fin >= `hora_fin`)
          );

        IF v_solapados > 0 THEN
            SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Error RF-06: Conflicto de horario. El odontólogo ya cuenta con una cita en ese rango de tiempo.';
        END IF;
    END IF;
END$$

-- ----------------------------------------------------------------------------
-- TRIGGER 2: trg_citas_before_update (Prevención de Solapamiento al Reagendar)
-- ----------------------------------------------------------------------------
DROP TRIGGER IF EXISTS `trg_citas_before_update`$$
CREATE TRIGGER `trg_citas_before_update`
BEFORE UPDATE ON `citas`
FOR EACH ROW
BEGIN
    DECLARE v_solapados INT DEFAULT 0;

    IF NEW.estado NOT IN ('Cancelado', 'No Asistio') THEN
        SELECT COUNT(*) INTO v_solapados
        FROM `citas`
        WHERE `odontologo_id` = NEW.odontologo_id
          AND `fecha` = NEW.fecha
          AND `id` != NEW.id
          AND `estado` NOT IN ('Cancelado', 'No Asistio')
          AND (
              (NEW.hora_inicio >= `hora_inicio` AND NEW.hora_inicio < `hora_fin`) OR
              (NEW.hora_fin > `hora_inicio` AND NEW.hora_fin <= `hora_fin`) OR
              (NEW.hora_inicio <= `hora_inicio` AND NEW.hora_fin >= `hora_fin`)
          );

        IF v_solapados > 0 THEN
            SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Error RF-06: Conflicto de horario al modificar cita. Espacio ocupado.';
        END IF;
    END IF;
END$$

-- ----------------------------------------------------------------------------
-- TRIGGER 3: trg_citas_after_update (Control de Stock e Inasistencias)
-- Regla 5: Descuento de insumos ÚNICAMENTE en estado Atendido.
-- Regla 8: Si pasa a 'No Asistio', suma falta en 'usuarios', NO descuenta stock
-- ----------------------------------------------------------------------------
DROP TRIGGER IF EXISTS `trg_citas_after_update`$$
CREATE TRIGGER `trg_citas_after_update`
AFTER UPDATE ON `citas`
FOR EACH ROW
BEGIN
    -- CASO 1: Cita culmina exitosamente en ATENDIDO
    IF NEW.estado = 'Atendido' AND OLD.estado != 'Atendido' THEN
        -- Registrar en historial clínico / operativo
        INSERT INTO `historial_atencion` (`cita_id`, `paciente_id`, `fecha`, `tipo_registro`, `resumen`)
        VALUES (
            NEW.id, 
            NEW.paciente_id, 
            NEW.fecha, 
            'Atencion Realizada', 
            CONCAT('Procedimiento finalizado exitosamente. Obs: ', COALESCE(NEW.observaciones, 'Sin observaciones adicionales'))
        );

        -- Descontar insumos requeridos por los procedimientos de la cita
        UPDATE `insumos` i
        INNER JOIN (
            SELECT si.insumo_id, SUM(si.cantidad_consumo) AS total_consumo
            FROM `cita_servicio` cs
            INNER JOIN `servicio_insumo` si ON cs.servicio_id = si.servicio_id
            WHERE cs.cita_id = NEW.id
            GROUP BY si.insumo_id
        ) consumo ON i.id = consumo.insumo_id
        SET i.stock = GREATEST(0, i.stock - consumo.total_consumo);

    -- CASO 2: Cita registrada como NO ASISTIO tras tolerancia de 15 minutos
    ELSEIF NEW.estado = 'No Asistio' AND OLD.estado != 'No Asistio' THEN
        -- Registrar falta en historial
        INSERT INTO `historial_atencion` (`cita_id`, `paciente_id`, `fecha`, `tipo_registro`, `resumen`)
        VALUES (
            NEW.id, 
            NEW.paciente_id, 
            NEW.fecha, 
            'Inasistencia Registrada', 
            CONCAT('Paciente no se presentó a la cita tras 15 min de tolerancia. Motivo: ', COALESCE(NEW.motivo_inasistencia, 'Sin justificación reportada'))
        );

        -- Incrementar contador de faltas acumuladas del paciente
        UPDATE `usuarios` 
        SET `faltas_acumuladas` = `faltas_acumuladas` + 1
        WHERE `id` = NEW.paciente_id;

        -- Registrar notificación pendiente por inasistencia para reprogramar
        INSERT INTO `notificaciones` (`cita_id`, `tipo`, `fecha_envio`, `estado_envio`, `mensaje`)
        VALUES (
            NEW.id,
            'inasistencia',
            NOW(),
            'pendiente',
            CONCAT('Aviso de inasistencia para cita #', NEW.id, '. Lo invitamos a ingresar al portal y reprogramar su cita.')
        );
        -- ATENCIÓN: NO SE DESCUENTA NINGÚN INSUMO (Regla de negocio 5)
    END IF;
END$$

DELIMITER ;

-- ============================================================================
-- PROCEDIMIENTOS ALMACENADOS
-- ============================================================================

DELIMITER $$

-- ----------------------------------------------------------------------------
-- PROCEDIMIENTO 1: sp_marcar_inasistencia
-- Valida ventana de tolerancia (15 min) y actualiza estado de forma segura
-- ----------------------------------------------------------------------------
DROP PROCEDURE IF EXISTS `sp_marcar_inasistencia`$$
CREATE PROCEDURE `sp_marcar_inasistencia`(
    IN p_cita_id BIGINT UNSIGNED,
    IN p_motivo VARCHAR(255)
)
BEGIN
    DECLARE v_fecha DATE;
    DECLARE v_hora_inicio TIME;
    DECLARE v_estado VARCHAR(30);
    DECLARE v_minutos_diferencia INT;

    SELECT `fecha`, `hora_inicio`, `estado`
    INTO v_fecha, v_hora_inicio, v_estado
    FROM `citas`
    WHERE `id` = p_cita_id;

    IF v_estado IS NULL THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Error: La cita especificada no existe.';
    END IF;

    IF v_estado != 'Asignado' AND v_estado != 'Solicitado' THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Error: Solo citas en estado Solicitado o Asignado pueden ser marcadas como No Asistió.';
    END IF;

    -- Validar transcurso de 15 minutos de tolerancia
    -- TIMESTAMPDIFF en minutos entre fecha/hora de la cita y el momento actual
    SET v_minutos_diferencia = TIMESTAMPDIFF(MINUTE, TIMESTAMP(v_fecha, v_hora_inicio), NOW());

    -- Si estamos en el mismo día y han pasado al menos 15 minutos, o si la fecha es pasada
    IF v_fecha > CURDATE() OR (v_fecha = CURDATE() AND v_minutos_diferencia < 15) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Error Regla 4: No se puede registrar inasistencia antes de que transcurran los 15 minutos de tolerancia.';
    END IF;

    -- Actualizar cita a 'No Asistio' (activará trg_citas_after_update)
    UPDATE `citas`
    SET `estado` = 'No Asistio',
        `motivo_inasistencia` = COALESCE(p_motivo, 'Paciente no asistió tras tiempo reglamentario de tolerancia (15 min)')
    WHERE `id` = p_cita_id;

    SELECT 'Inasistencia registrada correctamente y consultorio liberado' AS resultado;
END$$

DELIMITER ;

-- ============================================================================
-- DATOS INICIALES (SEED DATA)
-- ============================================================================

-- Inserción de Usuarios del Sistema (Contraseña por defecto: Password123! con hash de demostración)
INSERT INTO `usuarios` (`id`, `nombre`, `correo`, `contrasena`, `telefono`, `documento_identidad`, `rol`, `faltas_acumuladas`) VALUES
(1, 'Administradora General', 'admin@dentalcare.com', '$2y$12$eX8m3X88/1LhW7b5V0qgxe6YjZlHkdK3Uu0VjP6jBfGgS4kS4w1uy', '3104567890', '1144001001', 'admin', 0),
(2, 'María Recepcionista', 'recepcion@dentalcare.com', '$2y$12$eX8m3X88/1LhW7b5V0qgxe6YjZlHkdK3Uu0VjP6jBfGgS4kS4w1uy', '3157891234', '1144002002', 'recepcionista', 0),
(3, 'Dra. Camila Morales', 'dra.morales@dentalcare.com', '$2y$12$eX8m3X88/1LhW7b5V0qgxe6YjZlHkdK3Uu0VjP6jBfGgS4kS4w1uy', '3209876543', '1144003003', 'odontologo', 0),
(4, 'Dr. Carlos Mendoza', 'dr.mendoza@dentalcare.com', '$2y$12$eX8m3X88/1LhW7b5V0qgxe6YjZlHkdK3Uu0VjP6jBfGgS4kS4w1uy', '3187654321', '1144004004', 'odontologo', 0),
(5, 'Dra. Valeria Gómez', 'dra.gomez@dentalcare.com', '$2y$12$eX8m3X88/1LhW7b5V0qgxe6YjZlHkdK3Uu0VjP6jBfGgS4kS4w1uy', '3112233445', '1144005005', 'odontologo', 0),
(6, 'Juan Carlos Pérez', 'juan.perez@gmail.com', '$2y$12$eX8m3X88/1LhW7b5V0qgxe6YjZlHkdK3Uu0VjP6jBfGgS4kS4w1uy', '3001234567', '1144006006', 'paciente', 0),
(7, 'Laura Sofía Restrepo', 'laura.restrepo@hotmail.com', '$2y$12$eX8m3X88/1LhW7b5V0qgxe6YjZlHkdK3Uu0VjP6jBfGgS4kS4w1uy', '3012345678', '1144007007', 'paciente', 1),
(8, 'Andrés Felipe Murillo', 'andres.murillo@gmail.com', '$2y$12$eX8m3X88/1LhW7b5V0qgxe6YjZlHkdK3Uu0VjP6jBfGgS4kS4w1uy', '3023456789', '1144008008', 'paciente', 2); -- PACIENTE BLOQUEADO POR REINCIDENCIA DE FALTAS

-- Inserción de Odontólogos Especialistas
INSERT INTO `odontologos` (`id`, `usuario_id`, `especialidad`, `registro_medico`, `estado`) VALUES
(1, 3, 'Ortodoncia y Ortopedia Maxilar', 'RM-ORT-4491', 'activo'),
(2, 4, 'Endodoncia y Rehabilitación Oral', 'RM-END-8210', 'activo'),
(3, 5, 'Odontología General y Estética Dental', 'RM-ODG-3315', 'activo');

-- Inserción de Horarios Laborales (Lunes a Viernes 08:00 a 12:00 y 14:00 a 18:00, Sábados 08:00 a 13:00)
INSERT INTO `horarios` (`odontologo_id`, `dia_semana`, `hora_inicio`, `hora_fin`) VALUES
(1, 1, '08:00:00', '12:00:00'), (1, 1, '14:00:00', '18:00:00'),
(1, 2, '08:00:00', '12:00:00'), (1, 2, '14:00:00', '18:00:00'),
(1, 3, '08:00:00', '12:00:00'), (1, 3, '14:00:00', '18:00:00'),
(1, 4, '08:00:00', '12:00:00'), (1, 4, '14:00:00', '18:00:00'),
(1, 5, '08:00:00', '12:00:00'), (1, 5, '14:00:00', '18:00:00'),
(1, 6, '08:00:00', '13:00:00'),
(2, 1, '08:00:00', '12:00:00'), (2, 1, '14:00:00', '18:00:00'),
(2, 2, '08:00:00', '12:00:00'), (2, 2, '14:00:00', '18:00:00'),
(2, 3, '08:00:00', '12:00:00'), (2, 3, '14:00:00', '18:00:00'),
(2, 4, '08:00:00', '12:00:00'), (2, 4, '14:00:00', '18:00:00'),
(2, 5, '08:00:00', '12:00:00'), (2, 5, '14:00:00', '18:00:00'),
(3, 1, '08:00:00', '12:00:00'), (3, 1, '14:00:00', '18:00:00'),
(3, 2, '08:00:00', '12:00:00'), (3, 2, '14:00:00', '18:00:00'),
(3, 3, '08:00:00', '12:00:00'), (3, 3, '14:00:00', '18:00:00'),
(3, 4, '08:00:00', '12:00:00'), (3, 4, '14:00:00', '18:00:00'),
(3, 5, '08:00:00', '12:00:00'), (3, 5, '14:00:00', '18:00:00'),
(3, 6, '08:00:00', '13:00:00');

-- Inserción de Catálogo Oficial de Servicios (Tabla 7.4 de la documentación)
INSERT INTO `servicios` (`id`, `nombre`, `descripcion`, `duracion_min`, `precio`, `activo`) VALUES
(1, 'Valoración general', 'Diagnóstico inicial completo, revisión de salud bucal y formulación de plan de tratamiento.', 30, 30000.00, 1),
(2, 'Profilaxis (limpieza)', 'Limpieza profunda con ultrasonido, remoción de placa bacteriana, cálculo y pulido coronal.', 45, 100000.00, 1),
(3, 'Blanqueamiento dental', 'Tratamiento estético de aclaramiento dental profesional con peróxido de alta pureza.', 60, 500000.00, 1),
(4, 'Resina / calza dental', 'Restauración estética de cavidades por caries usando resinas compuestas de última generación.', 45, 80000.00, 1),
(5, 'Endodoncia', 'Tratamiento de conductos radiculares para salvar piezas dentales con compromiso pulpar.', 90, 400000.00, 1),
(6, 'Extracción simple', 'Exodoncia de pieza dental no restaurable bajo anestesia local de infiltración.', 30, 150000.00, 1),
(7, 'Control de ortodoncia', 'Revisión y ajuste periódico de brackets, cambio de arcos, ligaduras y evaluación de avance.', 30, 120000.00, 1);

-- Inserción de Inventario de Insumos Clínicos
INSERT INTO `insumos` (`id`, `nombre`, `stock`, `stock_minimo`, `unidad_medida`) VALUES
(1, 'Guantes de nitrilo descartables', 450, 100, 'pares'),
(2, 'Eyectores de saliva desechables', 320, 80, 'unidades'),
(3, 'Baberos odontológicos impermeables', 280, 50, 'unidades'),
(4, 'Carpules de Anestesia Lidocaína 2%', 110, 30, 'ampollas'),
(5, 'Agujas cortas para anestesia 30G', 140, 40, 'unidades'),
(6, 'Jeringa de resina fotocurable nanohíbrida', 22, 10, 'jeringas'),
(7, 'Kit gel blanqueador peróxido de hidrógeno', 14, 5, 'kits'),
(8, 'Pasta profiláctica abrasiva con flúor', 35, 10, 'tubos'),
(9, 'Conos de gutapercha estériles', 80, 20, 'cajas'),
(10, 'Ligaduras elastoméricas para ortodoncia', 500, 150, 'unidades');

-- Relación de Insumos consumidos por Servicio (servicio_insumo)
INSERT INTO `servicio_insumo` (`servicio_id`, `insumo_id`, `cantidad_consumo`) VALUES
-- Valoración: Guantes (1), Eyector (1), Babero (1)
(1, 1, 1), (1, 2, 1), (1, 3, 1),
-- Profilaxis: Guantes (1), Eyector (1), Babero (1), Pasta (1)
(2, 1, 1), (2, 2, 1), (2, 3, 1), (2, 8, 1),
-- Blanqueamiento: Guantes (2), Eyector (2), Babero (1), Gel (1)
(3, 1, 2), (3, 2, 2), (3, 3, 1), (3, 7, 1),
-- Resina: Guantes (1), Eyector (1), Babero (1), Resina (1), Anestesia (1), Aguja (1)
(4, 1, 1), (4, 2, 1), (4, 3, 1), (4, 6, 1), (4, 4, 1), (4, 5, 1),
-- Endodoncia: Guantes (2), Eyector (2), Babero (1), Anestesia (2), Aguja (2), Gutapercha (1)
(5, 1, 2), (5, 2, 2), (5, 3, 1), (5, 4, 2), (5, 5, 2), (5, 9, 1),
-- Extracción simple: Guantes (2), Eyector (1), Babero (1), Anestesia (2), Aguja (2)
(6, 1, 2), (6, 2, 1), (6, 3, 1), (6, 4, 2), (6, 5, 2),
-- Control Ortodoncia: Guantes (1), Eyector (1), Babero (1), Ligaduras (1)
(7, 1, 1), (7, 2, 1), (7, 3, 1), (7, 10, 1);

-- Inserción de Citas de Demostración con diferentes estados
-- Cita 1: Confirmada / Asignada para hoy
INSERT INTO `citas` (`id`, `paciente_id`, `odontologo_id`, `fecha`, `hora_inicio`, `hora_fin`, `estado`, `duracion_total_min`, `costo_total`, `observaciones`) VALUES
(1, 6, 1, CURDATE(), '09:00:00', '09:30:00', 'Asignado', 30, 120000.00, 'Control mensual de ortodoncia arco superior');
INSERT INTO `cita_servicio` (`cita_id`, `servicio_id`, `precio_historico`) VALUES (1, 7, 120000.00);

-- Cita 2: En Proceso en este momento
INSERT INTO `citas` (`id`, `paciente_id`, `odontologo_id`, `fecha`, `hora_inicio`, `hora_fin`, `estado`, `duracion_total_min`, `costo_total`, `observaciones`) VALUES
(2, 7, 3, CURDATE(), '10:00:00', '10:45:00', 'En Proceso', 45, 100000.00, 'Profilaxis rutinaria y valoración gingival');
INSERT INTO `cita_servicio` (`cita_id`, `servicio_id`, `precio_historico`) VALUES (2, 2, 100000.00);

-- Cita 3: Atendida en el pasado (con historial)
INSERT INTO `citas` (`id`, `paciente_id`, `odontologo_id`, `fecha`, `hora_inicio`, `hora_fin`, `estado`, `duracion_total_min`, `costo_total`, `observaciones`) VALUES
(3, 6, 3, DATE_SUB(CURDATE(), INTERVAL 7 DAY), '14:00:00', '14:45:00', 'Atendido', 45, 80000.00, 'Restauración en resina pieza 24 con éxito');
INSERT INTO `cita_servicio` (`cita_id`, `servicio_id`, `precio_historico`) VALUES (3, 4, 80000.00);
INSERT INTO `historial_atencion` (`cita_id`, `paciente_id`, `fecha`, `tipo_registro`, `resumen`) VALUES
(3, 6, DATE_SUB(CURDATE(), INTERVAL 7 DAY), 'Atencion Realizada', 'Procedimiento finalizado exitosamente. Obs: Restauración en resina pieza 24 con éxito');

-- Cita 4: No Asistió registrada (con historial de inasistencia)
INSERT INTO `citas` (`id`, `paciente_id`, `odontologo_id`, `fecha`, `hora_inicio`, `hora_fin`, `estado`, `duracion_total_min`, `costo_total`, `observaciones`, `motivo_inasistencia`) VALUES
(4, 7, 2, DATE_SUB(CURDATE(), INTERVAL 3 DAY), '11:00:00', '12:30:00', 'No Asistio', 90, 400000.00, 'Endodoncia molar inferior', 'Paciente no acudió tras 15 minutos de tolerancia y no respondió llamadas');
INSERT INTO `cita_servicio` (`cita_id`, `servicio_id`, `precio_historico`) VALUES (4, 5, 400000.00);
INSERT INTO `historial_atencion` (`cita_id`, `paciente_id`, `fecha`, `tipo_registro`, `resumen`) VALUES
(4, 7, DATE_SUB(CURDATE(), INTERVAL 3 DAY), 'Inasistencia Registrada', 'Paciente no se presentó a la cita tras 15 min de tolerancia. Motivo: Paciente no acudió tras 15 minutos de tolerancia y no respondió llamadas');

-- Cita 5: Solicitada pendiente por confirmar por recepción
INSERT INTO `citas` (`id`, `paciente_id`, `odontologo_id`, `fecha`, `hora_inicio`, `hora_fin`, `estado`, `duracion_total_min`, `costo_total`, `observaciones`) VALUES
(5, 6, 2, DATE_ADD(CURDATE(), INTERVAL 2 DAY), '15:00:00', '15:30:00', 'Solicitado', 30, 30000.00, 'Consulta de valoración por dolor difuso');
INSERT INTO `cita_servicio` (`cita_id`, `servicio_id`, `precio_historico`) VALUES (5, 1, 30000.00);

-- Cita 6: Cancelada oportunamente por el paciente
INSERT INTO `citas` (`id`, `paciente_id`, `odontologo_id`, `fecha`, `hora_inicio`, `hora_fin`, `estado`, `duracion_total_min`, `costo_total`, `observaciones`) VALUES
(6, 6, 1, DATE_SUB(CURDATE(), INTERVAL 10 DAY), '16:00:00', '16:30:00', 'Cancelado', 30, 120000.00, 'Cancelada por el paciente con 6 horas de anticipación por motivos laborales');
INSERT INTO `cita_servicio` (`cita_id`, `servicio_id`, `precio_historico`) VALUES (6, 7, 120000.00);

-- Inserción de Notificaciones de ejemplo
INSERT INTO `notificaciones` (`cita_id`, `tipo`, `fecha_envio`, `estado_envio`, `mensaje`) VALUES
(1, 'recordatorio', NOW(), 'enviado', 'Recordatorio: Su cita de Control de ortodoncia está programada para hoy a las 09:00.'),
(4, 'inasistencia', DATE_SUB(NOW(), INTERVAL 3 DAY), 'enviado', 'Aviso formal de inasistencia a su cita de Endodoncia. Puede reprogramar desde su portal web.');
