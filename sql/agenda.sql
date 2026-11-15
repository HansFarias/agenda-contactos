-- ============================================================
-- Agenda personal de contactos (PHP + MySQL)
-- Ejecutar en phpMyAdmin (pestaña SQL o Importar)
-- ============================================================

CREATE DATABASE IF NOT EXISTS agenda_db
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE agenda_db;

-- Tabla de usuarios
CREATE TABLE IF NOT EXISTS usuarios (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  nombre VARCHAR(100) NOT NULL,
  correo VARCHAR(150) NOT NULL,
  password VARCHAR(255) NOT NULL,          -- aquí se guarda el HASH, nunca la clave real
  fecha_registro DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_usuarios_correo (correo)    -- impide correos repetidos
);

-- Tabla de contactos (cada contacto pertenece a un usuario)
CREATE TABLE IF NOT EXISTS contactos (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  id_usuario INT UNSIGNED NOT NULL,
  nombre VARCHAR(100) NOT NULL,
  telefono VARCHAR(20) NOT NULL,
  correo VARCHAR(150) NOT NULL,
  ciudad VARCHAR(80) NOT NULL,
  PRIMARY KEY (id),
  KEY idx_contactos_usuario (id_usuario),
  CONSTRAINT fk_contactos_usuario
    FOREIGN KEY (id_usuario) REFERENCES usuarios (id)
    ON DELETE CASCADE
);