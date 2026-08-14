<?php
class Country
{
    public static function all()
    {
        $db = Database::getConnection();
        $stmt = $db->query('SELECT Code, Name FROM country ORDER BY Name');
        return $stmt->fetchAll();
    }

    public static function getByCode($code)
    {
        $db = Database::getConnection();
        $stmt = $db->prepare('SELECT Code, Name FROM country WHERE Code = ?');
        $stmt->execute([$code]);
        return $stmt->fetch();
    }
}
