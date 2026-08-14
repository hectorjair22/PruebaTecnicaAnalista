<?php
// Front Controller - Punto de entrada de la aplicación

// Cargamos la configuración y modelos
require_once __DIR__ . '/../app/bootstrap.php';

// Obtenemos la acción del formulario
$action = $_POST['country'] ?? $_GET['action'] ?? 'select';

// Creamos instancia del controlador
$controller = new CountryController();

// Ejecutamos la acción correspondiente
if (!empty($_POST['country'])) {
    // Si viene un código de país del POST, mostramos sus ciudades
    $controller->cities($_POST['country']);
} else {
    // De lo contrario, mostramos el formulario de selección
    $controller->index();
}
