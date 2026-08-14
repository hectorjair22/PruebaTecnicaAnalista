-- =====================================================
-- Base de datos WORLD - Sistema de Consulta de Ciudades
-- Prueba Técnica Analista de Soporte Nivel 1 (Infodec)
-- =====================================================

-- Crear base de datos
CREATE DATABASE IF NOT EXISTS world;
USE world;

-- =====================================================
-- Tabla COUNTRY (Países)
-- =====================================================
DROP TABLE IF EXISTS country;
CREATE TABLE country (
  Code char(3) NOT NULL DEFAULT '',
  Name char(52) NOT NULL DEFAULT '',
  Population int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (Code)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- Tabla CITY (Ciudades)
-- =====================================================
DROP TABLE IF EXISTS city;
CREATE TABLE city (
  ID int(11) NOT NULL AUTO_INCREMENT,
  Name char(35) NOT NULL DEFAULT '',
  CountryCode char(3) NOT NULL DEFAULT '',
  District char(20) NOT NULL DEFAULT '',
  Population int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (ID),
  KEY CountryCode (CountryCode),
  CONSTRAINT city_ibfk_1 FOREIGN KEY (CountryCode) REFERENCES country (Code) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- DATOS: PAÍSES (10 países principales)
-- =====================================================

INSERT INTO country (Code, Name, Population) VALUES
('ARG', 'Argentina', 37032000),
('BRA', 'Brazil', 170115000),
('COL', 'Colombia', 42321000),
('ESP', 'Spain', 39441700),
('MEX', 'Mexico', 98881000),
('PER', 'Peru', 25661000),
('USA', 'United States', 278357000),
('CAN', 'Canada', 31147000),
('VEN', 'Venezuela', 24170000),
('CHL', 'Chile', 15211000);

-- =====================================================
-- DATOS: CIUDADES (2-3 ciudades principales por país)
-- =====================================================

-- ARGENTINA
INSERT INTO city (Name, CountryCode, District, Population) VALUES
('Buenos Aires', 'ARG', 'Buenos Aires', 2960976),
('Córdoba', 'ARG', 'Córdoba', 1457007),
('Rosario', 'ARG', 'Santa Fe', 907718);

-- BRASIL
INSERT INTO city (Name, CountryCode, District, Population) VALUES
('São Paulo', 'BRA', 'São Paulo', 9968485),
('Rio de Janeiro', 'BRA', 'Rio de Janeiro', 5857904),
('Brasília', 'BRA', 'Brasília', 1821946);

-- COLOMBIA
INSERT INTO city (Name, CountryCode, District, Population) VALUES
('Bogotá', 'COL', 'Bogotá', 6260862),
('Medellín', 'COL', 'Antioquia', 1627552),
('Cali', 'COL', 'Valle del Cauca', 2058358);

-- ESPAÑA
INSERT INTO city (Name, CountryCode, District, Population) VALUES
('Madrid', 'ESP', 'Madrid', 2938723),
('Barcelona', 'ESP', 'Catalonia', 1503451),
('Valencia', 'ESP', 'Valencia', 1129576);

-- MÉXICO
INSERT INTO city (Name, CountryCode, District, Population) VALUES
('Mexico City', 'MEX', 'Mexico City', 8591309),
('Guadalajara', 'MEX', 'Jalisco', 1646319),
('Monterrey', 'MEX', 'Nuevo León', 1108499);

-- PERÚ
INSERT INTO city (Name, CountryCode, District, Population) VALUES
('Lima', 'PER', 'Lima', 6464693),
('Arequipa', 'PER', 'Arequipa', 715237),
('Trujillo', 'PER', 'La Libertad', 506000);

-- ESTADOS UNIDOS
INSERT INTO city (Name, CountryCode, District, Population) VALUES
('New York', 'USA', 'New York', 8008278),
('Los Angeles', 'USA', 'California', 3694820),
('Chicago', 'USA', 'Illinois', 2896016);

-- CANADÁ
INSERT INTO city (Name, CountryCode, District, Population) VALUES
('Toronto', 'CAN', 'Ontario', 2481494),
('Montreal', 'CAN', 'Quebec', 1583659),
('Vancouver', 'CAN', 'British Columbia', 1831665);

-- VENEZUELA
INSERT INTO city (Name, CountryCode, District, Population) VALUES
('Caracas', 'VEN', 'Capital', 3276604),
('Maracaibo', 'VEN', 'Zulia', 2103404),
('Valencia', 'VEN', 'Carabobo', 1703034);

-- CHILE
INSERT INTO city (Name, CountryCode, District, Population) VALUES
('Santiago', 'CHL', 'Santiago', 4837590),
('Valparaíso', 'CHL', 'Valparaíso', 275982),
('Concepción', 'CHL', 'Bío Bío', 369352);

-- =====================================================
-- ÍNDICES
-- =====================================================
ALTER TABLE city ADD INDEX idx_country_code (CountryCode);

COMMIT;
