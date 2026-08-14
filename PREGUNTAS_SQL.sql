-- =====================================================
-- PREGUNTA 2: Consultas SQL a la base de datos World
-- =====================================================

-- 1. Obtener todas las ciudades con población menor o igual a 100,000 habitantes
-- Esta consulta devuelve todas las ciudades con población <= 100,000
SELECT 
    c.Name AS 'Ciudad',
    c.CountryCode AS 'Código País',
    co.Name AS 'País',
    c.Population AS 'Población'
FROM city c
INNER JOIN country co ON c.CountryCode = co.Code
WHERE c.Population <= 100000
ORDER BY c.Population DESC;


-- 2. Realizar un conteo de las ciudades cuyo nombre comienza con la letra "R"
-- Cuenta todas las ciudades cuyo nombre empieza con 'R' (mayúscula)
SELECT COUNT(*) AS 'Total de ciudades que comienzan con R'
FROM city
WHERE Name LIKE 'R%';


-- 3. Mostrar el Top 10 de las ciudades donde se hable español
-- Se consideran países de habla hispana: España, México, Colombia, Argentina, 
-- Chile, Perú, Ecuador, Venezuela (códigos: ESP, MEX, COL, ARG, CHL, PER, ECU, VEN)
-- Ordenadas por población descendente
SELECT 
    c.Name AS 'Ciudad',
    co.Name AS 'País',
    c.Population AS 'Población',
    c.District AS 'Región'
FROM city c
INNER JOIN country co ON c.CountryCode = co.Code
WHERE co.Code IN ('ESP', 'MEX', 'COL', 'ARG', 'CHL', 'PER', 'ECU', 'VEN')
ORDER BY c.Population DESC
LIMIT 10;
