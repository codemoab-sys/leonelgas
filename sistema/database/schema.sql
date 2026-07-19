CREATE DATABASE IF NOT EXISTS miubicaciones
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE miubicaciones;

CREATE TABLE ubi_cliente (
    id INT AUTO_INCREMENT PRIMARY KEY,
    dni VARCHAR(20) NULL,
    nombre VARCHAR(255) NOT NULL,
    celular VARCHAR(20) NULL,
    detalle TEXT NULL,
    estado TINYINT NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE ubi_cliente_ubicaciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT NOT NULL,
    detalle TEXT NULL,
    latitud DECIMAL(10,8) NULL,
    longitud DECIMAL(11,8) NULL,
    precision_gps DECIMAL(10,2) NULL,
    foto VARCHAR(255) NULL,
    fecha_registro DATE NOT NULL DEFAULT (CURRENT_DATE),
    usuario_id INT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_ubicacion_cliente FOREIGN KEY (cliente_id)
        REFERENCES ubi_cliente(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
