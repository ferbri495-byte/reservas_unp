-- 1. CREAR LA BASE DE DATOS
DROP DATABASE IF EXISTS sistema_reservas_unp;
CREATE DATABASE sistema_reservas_unp CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE sistema_reservas_unp;

-- 2. TABLA DE DEPENDENCIAS (Generalización de Facultades y Oficinas Administrativas)
CREATE TABLE dependencias (
    id_dependencia INT AUTO_INCREMENT PRIMARY KEY,
    nombre_dependencia VARCHAR(150) NOT NULL, -- Ej: 'Facultad de Economía', 'Biblioteca Central'
    abreviatura VARCHAR(20) NOT NULL       -- Ej: 'FECO', 'BIBLIO'
);

-- Insertar Facultades y Áreas Administrativas bajo el mismo concepto de "Dependencia"
INSERT INTO dependencias (nombre_dependencia, abreviatura) VALUES 
('Centro Productivo Rectorado', 'RECT'),
('Facultad de Economía', 'FECO'),
('Facultad de Ingeniería de Minas', 'FIM'),
('Facultad de Ingeniería Pesquera', 'FIP'),
('Facultad de Arquitectura y Urbanismo', 'FAU'),
('Facultad de Ciencias Contables y Financieras', 'FCCF'),
('Biblioteca Central', 'BIBLIO'); -- Nueva dependencia administrativa añadida limpiamente

-- 3. TABLA DE USUARIOS (Con roles para el sistema)
CREATE TABLE usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nombre_completo VARCHAR(150) NOT NULL,
    correo VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    rol ENUM('Alumno', 'Docente', 'Externo', 'Admin_Facultad', 'SuperAdmin') NOT NULL,
    id_dependencia INT NULL, -- Enlazado a dependencias (facultades u oficinas)
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_dependencia) REFERENCES dependencias(id_dependencia) ON DELETE SET NULL
);

-- 4. TABLA DE AUDITORIOS (El Catálogo oficial del TUSNE + Aforo)
CREATE TABLE auditorios (
    id_auditorio INT AUTO_INCREMENT PRIMARY KEY,
    id_dependencia INT NOT NULL, -- Quién gestiona el auditorio
    nombre_ambiente VARCHAR(200) NOT NULL,
    capacidad INT NOT NULL,
    precio_interno DECIMAL(10,2) NOT NULL,
    precio_externo DECIMAL(10,2) NOT NULL,
    tipo_cobro ENUM('tarifa_plana', 'por_tiempo', 'por_equipamiento') NOT NULL,
    FOREIGN KEY (id_dependencia) REFERENCES dependencias(id_dependencia) ON DELETE CASCADE
);

-- Insertar el Catálogo oficial mapeado con las Dependencias correspondientes
INSERT INTO auditorios (id_dependencia, nombre_ambiente, capacidad, precio_interno, precio_externo, tipo_cobro) VALUES 
(1, 'Auditorio Central (Congresos/Académico)', 500, 800.00, 1800.00, 'por_tiempo'),
(1, 'Auditorio Central (Espectáculos Artísticos)', 500, 2000.00, 2000.00, 'tarifa_plana'),
(1, 'Sala de Conferencias Rectorado', 100, 400.00, 600.00, 'tarifa_plana'),
(2, 'Sala de Conferencias CEEYS', 80, 1000.00, 1000.00, 'tarifa_plana'),
(2, 'Auditorio del CEEYS "Luis Antonio Rosales"', 150, 2200.00, 2200.00, 'tarifa_plana'),
(2, 'Terraza del CEEYS', 200, 1500.00, 1500.00, 'tarifa_plana'),
(3, 'Sala de Conferencias FIM (con equipos)', 60, 1000.00, 1000.00, 'tarifa_plana'),
(3, 'Auditorio FIM (con equipos)', 250, 2200.00, 2200.00, 'tarifa_plana'),
(4, 'Auditorio Pesquera (Solo Ambiente)', 120, 120.00, 120.00, 'por_equipamiento'),
(4, 'Auditorio Pesquera (Con Multimedia)', 120, 170.00, 170.00, 'por_equipamiento'),
(4, 'Auditorio Pesquera (Con Multimedia y Sonido)', 120, 200.00, 200.00, 'por_equipamiento'),
(5, 'Auditorio FAU', 100, 400.00, 600.00, 'tarifa_plana'),
(6, 'Auditorio FCCF', 150, 400.00, 600.00, 'tarifa_plana'),
(7, 'Auditorio de la Biblioteca Central', 200, 400.00, 600.00, 'tarifa_plana'); -- Auditorio de Biblioteca añadido

-- 5. TABLA DE SOLICITUDES DE RESERVA (Flujo 1: Separar el espacio)
CREATE TABLE solicitudes_reserva (
    id_reserva INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    id_auditorio INT NOT NULL,
    titulo_evento VARCHAR(200) NOT NULL,
    descripcion TEXT NULL,
    fecha_evento DATE NOT NULL,
    hora_inicio TIME NOT NULL,
    hora_fin TIME NOT NULL,
    duracion ENUM('completo', 'medio') NOT NULL DEFAULT 'completo',
    tipo_evento ENUM('Academico_Gratuito', 'Pago_Ordinario') NOT NULL,
    monto_total DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    estado ENUM('Pendiente', 'Esperando_Pago', 'Por_Verificar', 'Confirmada', 'Rechazada', 'Expirada') NOT NULL DEFAULT 'Pendiente',
    numero_comprobante VARCHAR(100) NULL,
    foto_voucher VARCHAR(255) NULL,
    documento_resolucion VARCHAR(255) NULL,
    fecha_solicitud TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE CASCADE,
    FOREIGN KEY (id_auditorio) REFERENCES auditorios(id_auditorio) ON DELETE CASCADE
);

-- 6. TABLA DE INSCRIPCIONES / AFORO (Flujo 2: Alumnos reservan cupo)
CREATE TABLE  inscripciones_evento (
    id_inscripcion INT AUTO_INCREMENT PRIMARY KEY,
    id_reserva INT NOT NULL,
    id_alumno INT NOT NULL,
    fecha_inscripcion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_reserva) REFERENCES solicitudes_reserva(id_reserva) ON DELETE CASCADE,
    FOREIGN KEY (id_alumno) REFERENCES usuarios(id_usuario) ON DELETE CASCADE,
    UNIQUE KEY (id_reserva, id_alumno)
);
