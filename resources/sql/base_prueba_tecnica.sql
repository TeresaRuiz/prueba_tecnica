-- Eliminar base de datos si existe
DROP DATABASE IF EXISTS USUARIOS_API;
CREATE DATABASE USUARIOS_API;
USE USUARIOS_API;

CREATE TABLE TB_ROLES (
    id_rol INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    nombre_rol VARCHAR(50) NOT NULL,
    descripcion_rol VARCHAR(255),
    PRIMARY KEY (id_rol),
    CONSTRAINT uc_nombre_rol UNIQUE (nombre_rol) -- El nombre del rol debe ser único
);

CREATE TABLE TB_ESTADOS (
    id_estado INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    nombre_estado VARCHAR(50) NOT NULL,
    descripcion_estado VARCHAR(255),
    PRIMARY KEY (id_estado),
    CONSTRAINT uc_nombre_estado UNIQUE (nombre_estado) -- El nombre del estado debe ser único
);

CREATE TABLE TB_USUARIOS (
    id_usuario INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    nombre_usuario VARCHAR(100) NOT NULL,
    correo_usuario VARCHAR(255) NOT NULL,
    username_usuario VARCHAR(50) NOT NULL,
    password_usuario VARCHAR(255) NOT NULL,
    fecha_registro DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    fecha_nacimiento DATE,
    telefono_usuario VARCHAR(20),
    direccion_usuario VARCHAR(255),
    id_rol INT(10) UNSIGNED DEFAULT NULL,
    id_estado INT(10) UNSIGNED DEFAULT NULL,
    PRIMARY KEY (id_usuario),
    CONSTRAINT uc_correo_usuario UNIQUE (correo_usuario),      -- El correo debe ser único
    CONSTRAINT uc_username_usuario UNIQUE (username_usuario),   -- El nombre de usuario debe ser único
    CONSTRAINT fk_id_rol FOREIGN KEY (id_rol) REFERENCES TB_ROLES(id_rol) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT fk_id_estado FOREIGN KEY (id_estado) REFERENCES TB_ESTADOS(id_estado) ON DELETE SET NULL ON UPDATE CASCADE
);

-- Trigger para validar que la fecha de nacimiento sea en el pasado
DELIMITER //

CREATE TRIGGER before_insert_tb_usuarios
BEFORE INSERT ON TB_USUARIOS
FOR EACH ROW
BEGIN
    IF NEW.fecha_nacimiento >= CURDATE() THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'La fecha de nacimiento debe ser en el pasado';
    END IF;
END; //

CREATE TRIGGER before_update_tb_usuarios
BEFORE UPDATE ON TB_USUARIOS
FOR EACH ROW
BEGIN
    IF NEW.fecha_nacimiento >= CURDATE() THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'La fecha de nacimiento debe ser en el pasado';
    END IF;
END; //

DELIMITER ;