-- ==========================================
-- CREACIÓN DE BASE DE DATOS
-- ==========================================
CREATE DATABASE IF NOT EXISTS turnos_db
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_0900_ai_ci;

USE turnos_db;

-- ==========================================
-- TABLAS DE ESTRUCTURA
-- ==========================================

CREATE TABLE piso (
  Nro_Piso int NOT NULL,
  NombrePiso varchar(50) DEFAULT NULL,
  PRIMARY KEY (Nro_Piso)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE sala (
  NumeroSala int NOT NULL,
  NombreSala varchar(50) NOT NULL,
  Capacidad int DEFAULT NULL,
  PRIMARY KEY (NumeroSala)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE subespecialidad (
  ID_Subespecialidad int NOT NULL AUTO_INCREMENT,
  NombreSubespecialidad varchar(100) NOT NULL,
  PRIMARY KEY (ID_Subespecialidad)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE condicion_laboral (
  ID_Condicion int NOT NULL AUTO_INCREMENT,
  NombreCondicion varchar(50) NOT NULL,
  PRIMARY KEY (ID_Condicion)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE empleado (
  DNI_Personal char(8) NOT NULL,
  ApellidosNombres varchar(100) NOT NULL,
  Celular char(9) DEFAULT NULL,
  FechaNacimiento date NOT NULL,
  Estado varchar(20) DEFAULT NULL,
  Encargado tinyint(1) DEFAULT '0',
  Nro_Piso int DEFAULT NULL,
  ID_Subespecialidad int NOT NULL,
  ID_Condicion int NOT NULL,
  PRIMARY KEY (DNI_Personal),
  UNIQUE KEY Celular (Celular),
  CONSTRAINT empleado_fk_subespecialidad FOREIGN KEY (ID_Subespecialidad)
    REFERENCES subespecialidad (ID_Subespecialidad),
  CONSTRAINT empleado_fk_condicion FOREIGN KEY (ID_Condicion)
    REFERENCES condicion_laboral (ID_Condicion)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE distribucion_anual_salas (
  NumeroSala int NOT NULL,
  Anio_Distribucion int NOT NULL,
  MinimosXTurno int DEFAULT NULL,
  Nro_Piso int NOT NULL,
  PRIMARY KEY (NumeroSala, Anio_Distribucion),
  KEY Nro_Piso (Nro_Piso),
  CONSTRAINT das_fk_sala FOREIGN KEY (NumeroSala)
    REFERENCES sala (NumeroSala),
  CONSTRAINT das_fk_piso FOREIGN KEY (Nro_Piso)
    REFERENCES piso (Nro_Piso)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE personal_enfermeria (
  Nro_Piso int NOT NULL,
  DNI_Encargado char(8) NOT NULL,
  PRIMARY KEY (Nro_Piso),
  KEY DNI_Encargado (DNI_Encargado),
  CONSTRAINT pe_fk_empleado FOREIGN KEY (DNI_Encargado)
    REFERENCES empleado (DNI_Personal),
  CONSTRAINT pe_fk_piso FOREIGN KEY (Nro_Piso)
    REFERENCES piso (Nro_Piso)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE personal_por_piso (
  DNI_Personal char(8) NOT NULL,
  Nro_Piso int NOT NULL,
  PRIMARY KEY (DNI_Personal, Nro_Piso),
  KEY Nro_Piso (Nro_Piso),
  CONSTRAINT ppp_fk_personal FOREIGN KEY (DNI_Personal)
    REFERENCES empleado (DNI_Personal),
  CONSTRAINT ppp_fk_piso FOREIGN KEY (Nro_Piso)
    REFERENCES piso (Nro_Piso)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE programacion_mensual (
  Mes int NOT NULL,
  TotalTurnosGlobal int DEFAULT NULL,
  TotalesPorTipoM int DEFAULT NULL,
  TotalesPorTipoT int DEFAULT NULL,
  TotalesPorTipoD int DEFAULT NULL,
  TotalesPorTipoN int DEFAULT NULL,
  FechaPublicacion date DEFAULT NULL,
  NumeroSala int NOT NULL,
  Anio_Distribucion int NOT NULL,
  Nro_Piso int NOT NULL,
  PRIMARY KEY (Mes, NumeroSala, Anio_Distribucion, Nro_Piso),
  KEY NumeroSala (NumeroSala, Anio_Distribucion),
  KEY Nro_Piso (Nro_Piso),
  CONSTRAINT pm_fk_das FOREIGN KEY (NumeroSala, Anio_Distribucion)
    REFERENCES distribucion_anual_salas (NumeroSala, Anio_Distribucion),
  CONSTRAINT pm_fk_piso FOREIGN KEY (Nro_Piso)
    REFERENCES piso (Nro_Piso)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE personal_asignado_sala (
  Anio_Distribucion int NOT NULL,
  NumeroSala int NOT NULL,
  DNI_Personal char(8) NOT NULL,
  Rol varchar(50) DEFAULT NULL,
  PRIMARY KEY (Anio_Distribucion, NumeroSala, DNI_Personal),
  KEY NumeroSala (NumeroSala, Anio_Distribucion),
  KEY DNI_Personal (DNI_Personal),
  CONSTRAINT pas_fk_das FOREIGN KEY (NumeroSala, Anio_Distribucion)
    REFERENCES distribucion_anual_salas (NumeroSala, Anio_Distribucion),
  CONSTRAINT pas_fk_empleado FOREIGN KEY (DNI_Personal)
    REFERENCES empleado (DNI_Personal)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE resumen_turnos_empleado (
  Mes int NOT NULL,
  DNI_Personal char(8) NOT NULL,
  TotalTurnosEmpleado int DEFAULT NULL,
  TotalHorasEmpleado int DEFAULT NULL,
  PRIMARY KEY (Mes, DNI_Personal),
  KEY DNI_Personal (DNI_Personal),
  CONSTRAINT rte_fk_empleado FOREIGN KEY (DNI_Personal)
    REFERENCES empleado (DNI_Personal)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE turno_empleado (
  Mes int NOT NULL,
  Dia int NOT NULL,
  DNI char(8) NOT NULL,
  TipoTurno char(1) NOT NULL,
  EsDescanso tinyint(1) DEFAULT NULL,
  EsPermisoEspecial tinyint(1) DEFAULT NULL,
  PRIMARY KEY (Mes, Dia, DNI),
  KEY DNI (DNI),
  CONSTRAINT te_fk_empleado FOREIGN KEY (DNI)
    REFERENCES empleado (DNI_Personal)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- ==========================================
-- INSERTS DE DATOS DEMO COMPLETOS
-- ==========================================

INSERT INTO piso (Nro_Piso, NombrePiso) VALUES
(1, 'Primer piso'),
(2, 'Segundo piso');

INSERT INTO sala (NumeroSala, NombreSala, Capacidad) VALUES
(101, 'General', 10),
(102, 'Cirugía', 8),
(201, 'Urgencias', 12),
(202, 'UCI', 6);

INSERT INTO subespecialidad (NombreSubespecialidad) VALUES
('General'), ('Pediatría'), ('Cirugía'), ('UCI');

INSERT INTO condicion_laboral (NombreCondicion) VALUES
('Nombrado'), ('CAS');

-- General (Sala 101)
INSERT INTO empleado VALUES
('11111111', 'Carmen Vilca', '900000001', '1980-01-10', 'Activo', 1, 1, 1, 1),     -- Encargada, Nombrado
('10100001', 'Luis Ramos', '900000002', '1985-03-15', 'Activo', 0, 1, 1, 2),       -- CAS
('10100002', 'Ana Torres', '900000003', '1991-07-21', 'Activo', 0, 1, 2, 1),       -- Nombrado
('10100003', 'Pedro Díaz', '900000004', '1988-06-05', 'Activo', 0, 1, 3, 2),       -- CAS
('10100004', 'Lucía Romero', '900000005', '1992-12-23', 'Activo', 0, 1, 1, 1),     -- Nombrado
('10100005', 'Juan Paredes', '900000006', '1989-04-30', 'Activo', 0, 1, 2, 2);     -- CAS

-- Cirugía (Sala 102)
INSERT INTO empleado VALUES
('10211112', 'María Mendoza', '900000101', '1981-02-12', 'Activo', 1, 1, 2, 1),
('10200001', 'Diego Fernández', '900000102', '1992-01-02', 'Activo', 0, 1, 2, 2),
('10200002', 'Paula Gutiérrez', '900000103', '1987-06-13', 'Activo', 0, 1, 3, 1),
('10200003', 'Andrés Salazar', '900000104', '1985-08-14', 'Activo', 0, 1, 2, 2),
('10200004', 'Sandra López', '900000105', '1990-09-25', 'Activo', 0, 1, 2, 1),
('10200005', 'Rocío Vera', '900000106', '1984-11-16', 'Activo', 0, 1, 1, 2);

-- Urgencias (Sala 201)
INSERT INTO empleado VALUES
('20111113', 'José Quispe', '900001001', '1983-04-30', 'Activo', 1, 2, 3, 1),
('20100001', 'Valeria Chávez', '900001002', '1987-08-14', 'Activo', 0, 2, 3, 2),
('20100002', 'Carlos Sánchez', '900001003', '1990-02-28', 'Activo', 0, 2, 4, 1),
('20100003', 'Patricia Benites', '900001004', '1984-07-01', 'Activo', 0, 2, 1, 2),
('20100004', 'Miguel Rivas', '900001005', '1985-09-10', 'Activo', 0, 2, 3, 1),
('20100005', 'Paola Farfán', '900001006', '1983-10-19', 'Activo', 0, 2, 4, 2);

-- UCI (Sala 202)
INSERT INTO empleado VALUES
('20211114', 'Natalia Villegas', '900002001', '1983-11-11', 'Activo', 1, 2, 4, 1),
('20200001', 'Pedro Ruiz', '900002002', '1992-08-15', 'Activo', 0, 2, 4, 2),
('20200002', 'Lorena Silva', '900002003', '1986-12-18', 'Activo', 0, 2, 1, 1),
('20200003', 'Sergio Aguirre', '900002004', '1989-02-22', 'Activo', 0, 2, 3, 2),
('20200004', 'Gabriela Salinas', '900002005', '1988-01-25', 'Activo', 0, 2, 2, 1),
('20200005', 'Hugo Castro', '900002006', '1991-05-27', 'Activo', 0, 2, 4, 2);

-- Personal por piso
INSERT INTO personal_por_piso VALUES
('11111111', 1), ('10100001', 1), ('10100002', 1), ('10100003', 1), ('10100004', 1), ('10100005', 1),
('10211112', 1), ('10200001', 1), ('10200002', 1), ('10200003', 1), ('10200004', 1), ('10200005', 1),
('20111113', 2), ('20100001', 2), ('20100002', 2), ('20100003', 2), ('20100004', 2), ('20100005', 2),
('20211114', 2), ('20200001', 2), ('20200002', 2), ('20200003', 2), ('20200004', 2), ('20200005', 2);

-- Personal de enfermería encargado por piso
INSERT INTO personal_enfermeria VALUES
(1, '11111111'), (1, '10211112'), (2, '20111113'), (2, '20211114');

-- Distribución anual por sala
INSERT INTO distribucion_anual_salas VALUES
(101, 2025, 2, 1), (102, 2025, 2, 1), (201, 2025, 2, 2), (202, 2025, 2, 2);

-- Personal asignado a sala
INSERT INTO personal_asignado_sala VALUES
(2025, 101, '11111111',  'Encargada'),
(2025, 101, '10100001',  'Enfermero'),
(2025, 101, '10100002',  'Enfermera'),
(2025, 101, '10100003',  'Enfermero'),
(2025, 101, '10100004',  'Enfermera'),
(2025, 101, '10100005',  'Enfermero'),

(2025, 102, '10211112',  'Encargada'),
(2025, 102, '10200001',  'Enfermero'),
(2025, 102, '10200002',  'Enfermera'),
(2025, 102, '10200003',  'Enfermero'),
(2025, 102, '10200004',  'Enfermera'),
(2025, 102, '10200005',  'Enfermera'),

(2025, 201, '20111113',  'Encargado'),
(2025, 201, '20100001',  'Enfermera'),
(2025, 201, '20100002',  'Enfermero'),
(2025, 201, '20100003',  'Enfermera'),
(2025, 201, '20100004',  'Enfermero'),
(2025, 201, '20100005',  'Enfermera'),

(2025, 202, '20211114',  'Encargada'),
(2025, 202, '20200001',  'Enfermero'),
(2025, 202, '20200002',  'Enfermera'),
(2025, 202, '20200003',  'Enfermero'),
(2025, 202, '20200004',  'Enfermera'),
(2025, 202, '20200005',  'Enfermero');



-- ==========================================
-- PROCEDIMIENTOS - EMPLEADO
-- ==========================================

DELIMITER $$

CREATE PROCEDURE sp_insert_empleado(
  IN p_DNI CHAR(8),
  IN p_Nombre VARCHAR(100),
  IN p_ID_Condicion INT,
  IN p_ID_Sub INT,
  IN p_Cel CHAR(9),
  IN p_FechaNacimiento DATE,
  IN p_Estado VARCHAR(20),
  IN p_Encargado TINYINT,
  IN p_Nro_Piso INT
)
BEGIN
  DECLARE v_exists INT DEFAULT 0;

  SELECT COUNT(*) INTO v_exists
  FROM empleado
  WHERE DNI_Personal = p_DNI;

  IF v_exists = 0 THEN
    INSERT INTO empleado(
      DNI_Personal, ApellidosNombres,
      Celular, FechaNacimiento, Estado,
      Encargado, Nro_Piso,
      ID_Subespecialidad, ID_Condicion
    )
    VALUES (
      p_DNI, p_Nombre,
      p_Cel, p_FechaNacimiento, p_Estado,
      p_Encargado, p_Nro_Piso,
      p_ID_Sub, p_ID_Condicion
    );
  ELSE
    SIGNAL SQLSTATE '45000'
    SET MESSAGE_TEXT = 'Empleado ya existe';
  END IF;
END$$

CREATE PROCEDURE sp_update_empleado(
  IN p_DNI CHAR(8),
  IN p_Nombre VARCHAR(100),
  IN p_ID_Condicion INT,
  IN p_ID_Sub INT,
  IN p_Cel CHAR(9),
  IN p_FechaNacimiento DATE,
  IN p_Estado VARCHAR(20),
  IN p_Encargado TINYINT,
  IN p_Nro_Piso INT
)
BEGIN
  UPDATE empleado
  SET ApellidosNombres   = p_Nombre,
      Celular            = p_Cel,
      FechaNacimiento    = p_FechaNacimiento,
      Estado             = p_Estado,
      Encargado          = p_Encargado,
      Nro_Piso           = p_Nro_Piso,
      ID_Subespecialidad = p_ID_Sub,
      ID_Condicion       = p_ID_Condicion
  WHERE DNI_Personal = p_DNI;

  IF ROW_COUNT() = 0 THEN
    SIGNAL SQLSTATE '45000'
    SET MESSAGE_TEXT = 'Empleado no encontrado';
  END IF;
END$$

CREATE PROCEDURE sp_delete_empleado(IN p_DNI CHAR(8))
BEGIN
  IF EXISTS (
    SELECT 1 FROM turno_empleado WHERE DNI = p_DNI
  ) THEN
    SIGNAL SQLSTATE '45000'
    SET MESSAGE_TEXT = 'No se puede eliminar: empleado tiene turnos asignados';
  ELSE
    DELETE FROM personal_asignado_sala WHERE DNI_Personal = p_DNI;
    DELETE FROM empleado WHERE DNI_Personal = p_DNI;

    IF ROW_COUNT() = 0 THEN
      SIGNAL SQLSTATE '45000'
      SET MESSAGE_TEXT = 'Empleado no encontrado';
    END IF;
  END IF;
END$$

-- ==========================================
-- PROCEDIMIENTOS - TURNOS
-- ==========================================

CREATE PROCEDURE sp_insert_turno(
 IN p_Mes INT,
 IN p_Dia INT,
 IN p_DNI CHAR(8),
 IN p_Tipo CHAR(1),
 IN p_EsDescanso TINYINT,
 IN p_EsPermisoEspecial TINYINT
)
BEGIN
  DECLARE v_count INT DEFAULT 0;
  DECLARE v_horas_mes INT DEFAULT 0;
  DECLARE v_id_condicion INT;

  -- Tipo de contrato del empleado
  SELECT ID_Condicion INTO v_id_condicion
  FROM empleado
  WHERE DNI_Personal = p_DNI;

  IF v_id_condicion IS NULL THEN
    SIGNAL SQLSTATE '45000'
    SET MESSAGE_TEXT = 'Empleado no existe';
  END IF;

  -- Suposición: ID_Condicion = 1 -> CAS, 2 -> Nombrado (ajusta si usas otros IDs)
  IF v_id_condicion = 1 AND p_Tipo = 'N' THEN
    SIGNAL SQLSTATE '45000'
    SET MESSAGE_TEXT = 'Contratos CAS no pueden tener turno N';
  END IF;

  -- Límite de 25 turnos/mes
  SELECT COUNT(*) INTO v_count
  FROM turno_empleado
  WHERE Mes = p_Mes AND DNI = p_DNI;

  IF v_count >= 25 THEN
    SIGNAL SQLSTATE '45000'
    SET MESSAGE_TEXT = 'Se alcanzó el límite de 25 turnos mensuales';
  END IF;

  -- Límite de 250 horas/mes (10 horas por turno)
  SELECT COALESCE(COUNT(*) * 10,0) INTO v_horas_mes
  FROM turno_empleado
  WHERE Mes = p_Mes AND DNI = p_DNI;

  IF v_horas_mes + 10 > 250 THEN
    SIGNAL SQLSTATE '45000'
    SET MESSAGE_TEXT = 'Se alcanzó el límite de 250 horas mensuales';
  END IF;

  INSERT INTO turno_empleado (
    Mes, Dia, DNI, TipoTurno,
    EsDescanso, EsPermisoEspecial
  )
  VALUES (
    p_Mes, p_Dia, p_DNI, p_Tipo,
    p_EsDescanso, p_EsPermisoEspecial
  );

  INSERT INTO resumen_turnos_empleado (
    Mes, DNI_Personal, TotalTurnosEmpleado, TotalHorasEmpleado
  )
  VALUES (p_Mes, p_DNI, 1, 10)
  ON DUPLICATE KEY UPDATE
    TotalTurnosEmpleado = TotalTurnosEmpleado + 1,
    TotalHorasEmpleado  = TotalHorasEmpleado  + 10;
END$$

CREATE PROCEDURE sp_delete_turno(
 IN p_Mes INT,
 IN p_Dia INT,
 IN p_DNI CHAR(8)
)
BEGIN
  DELETE FROM turno_empleado
  WHERE Mes = p_Mes AND Dia = p_Dia AND DNI = p_DNI;

  IF ROW_COUNT() = 0 THEN
    SIGNAL SQLSTATE '45000'
    SET MESSAGE_TEXT = 'Turno no encontrado';
  END IF;

  UPDATE resumen_turnos_empleado
  SET TotalTurnosEmpleado = TotalTurnosEmpleado - 1,
      TotalHorasEmpleado  = TotalHorasEmpleado  - 10
  WHERE Mes = p_Mes AND DNI_Personal = p_DNI;
END$$

DELIMITER ;

-- ==========================================
-- TRIGGERS
-- ==========================================

DELIMITER $$

CREATE TABLE IF NOT EXISTS auditoria_empleado (
  id INT AUTO_INCREMENT PRIMARY KEY,
  accion VARCHAR(20),
  dni CHAR(8),
  usuario VARCHAR(50),
  fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  descripcion TEXT
) ENGINE=InnoDB;

CREATE TRIGGER trg_empleado_insert AFTER INSERT ON empleado
FOR EACH ROW
BEGIN
  INSERT INTO auditoria_empleado (accion, dni, usuario, descripcion)
  VALUES ('INSERT', NEW.DNI_Personal, USER(), CONCAT('Se insertó empleado ', NEW.DNI_Personal));
END$$

CREATE TRIGGER trg_empleado_update AFTER UPDATE ON empleado
FOR EACH ROW
BEGIN
  INSERT INTO auditoria_empleado (accion, dni, usuario, descripcion)
  VALUES ('UPDATE', NEW.DNI_Personal, USER(), CONCAT('Se actualizó empleado ', NEW.DNI_Personal));
END$$

CREATE TRIGGER trg_empleado_delete AFTER DELETE ON empleado
FOR EACH ROW
BEGIN
  INSERT INTO auditoria_empleado (accion, dni, usuario, descripcion)
  VALUES ('DELETE', OLD.DNI_Personal, USER(), CONCAT('Se eliminó empleado ', OLD.DNI_Personal));
END$$

CREATE TRIGGER trg_insert_encargado
AFTER INSERT ON empleado
FOR EACH ROW
BEGIN
    IF NEW.Encargado = TRUE THEN
        IF NOT EXISTS (
            SELECT 1 FROM personal_enfermeria WHERE Nro_Piso = NEW.Nro_Piso
        ) THEN
            INSERT INTO personal_enfermeria (Nro_Piso, DNI_Encargado)
            VALUES (NEW.Nro_Piso, NEW.DNI_Personal);
        ELSE
            SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Ya existe un encargado en este piso';
        END IF;
    END IF;
END$$

CREATE TRIGGER trg_update_encargado
AFTER UPDATE ON empleado
FOR EACH ROW
BEGIN
    IF NEW.Encargado = TRUE AND OLD.Encargado = FALSE THEN
        IF NOT EXISTS (
            SELECT 1 FROM personal_enfermeria WHERE Nro_Piso = NEW.Nro_Piso
        ) THEN
            INSERT INTO personal_enfermeria (Nro_Piso, DNI_Encargado)
            VALUES (NEW.Nro_Piso, NEW.DNI_Personal);
        ELSE
            SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Ya existe un encargado en este piso';
        END IF;
    END IF;

    IF NEW.Encargado = FALSE AND OLD.Encargado = TRUE THEN
        DELETE FROM personal_enfermeria WHERE Nro_Piso = OLD.Nro_Piso;
    END IF;
END$$

CREATE TRIGGER trg_sala_insert_subespecialidad
AFTER INSERT ON sala
FOR EACH ROW
BEGIN
    IF NOT EXISTS (
        SELECT 1 FROM subespecialidad
        WHERE NombreSubespecialidad = NEW.NombreSala
    ) THEN
        INSERT INTO subespecialidad (NombreSubespecialidad)
        VALUES (NEW.NombreSala);
    END IF;
END$$

CREATE TRIGGER trg_empleado_insert_porpiso
AFTER INSERT ON empleado
FOR EACH ROW
BEGIN
    IF NEW.Nro_Piso IS NOT NULL THEN
        IF NOT EXISTS (
            SELECT 1 FROM personal_por_piso
            WHERE DNI_Personal = NEW.DNI_Personal
              AND Nro_Piso     = NEW.Nro_Piso
        ) THEN
            INSERT INTO personal_por_piso (DNI_Personal, Nro_Piso)
            VALUES (NEW.DNI_Personal, NEW.Nro_Piso);
        END IF;
    END IF;
END$$

CREATE TRIGGER trg_empleado_update_porpiso
AFTER UPDATE ON empleado
FOR EACH ROW
BEGIN
    -- Si cambió de piso
    IF NEW.Nro_Piso <> OLD.Nro_Piso THEN
        -- Borrar la relación antigua
        DELETE FROM personal_por_piso
        WHERE DNI_Personal = OLD.DNI_Personal
          AND Nro_Piso     = OLD.Nro_Piso;

        -- Insertar la nueva
        IF NEW.Nro_Piso IS NOT NULL THEN
            INSERT INTO personal_por_piso (DNI_Personal, Nro_Piso)
            VALUES (NEW.DNI_Personal, NEW.Nro_Piso);
        END IF;
    END IF;
END$$

DELIMITER ;

