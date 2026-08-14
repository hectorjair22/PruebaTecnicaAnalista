<?php
// Carga mínima del framework MVC
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/controllers/CountryController.php';
require_once __DIR__ . '/models/Database.php';
require_once __DIR__ . '/models/Country.php';
require_once __DIR__ . '/models/City.php';

// Autoload simple para vistas
function view($path, $data = []) {
    extract($data);
    include __DIR__ . '/views/' . $path;
}
