<?php
// Modelo de Ciudad - Funciones para obtener datos de ciudades

class City
{
    // Obtiene todas las ciudades de un país ordenadas por población (mayor a menor)
    public static function byCountry($countryCode)
    {
        $db = Database::getConnection();
        $stmt = $db->prepare('SELECT ID, Name, Population FROM city WHERE CountryCode = ? ORDER BY Population DESC');
        $stmt->execute([$countryCode]);
        return $stmt->fetchAll();
    }

    // Obtiene la ciudad con mayor población de un país
    public static function maxPopulation($countryCode)
    {
        $db = Database::getConnection();
        $stmt = $db->prepare('SELECT MAX(Population) as max_pop FROM city WHERE CountryCode = ?');
        $stmt->execute([$countryCode]);
        $result = $stmt->fetch();
        return $result['max_pop'];
    }
}
