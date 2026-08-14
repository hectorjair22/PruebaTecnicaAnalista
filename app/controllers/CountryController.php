<?php
// Controlador de País - Maneja la lógica de presentación de países y ciudades

class CountryController
{
    // Muestra el formulario con lista de países
    public function index()
    {
        try {
            $countries = Country::all();
            $error = null;
        } catch (Exception $e) {
            $countries = [];
            $error = $e->getMessage();
        }
        view('countries/select.php', compact('countries', 'error'));
    }

    // Muestra las ciudades de un país seleccionado
    public function cities($countryCode)
    {
        try {
            // Obtenemos datos del país
            $country = Country::getByCode($countryCode);
            if (!$country) {
                throw new Exception('País no encontrado');
            }

            // Obtenemos las ciudades del país
            $cities = City::byCountry($countryCode);
            
            // Calculamos la población máxima para hacer la escala de 10
            $maxPop = City::maxPopulation($countryCode);
            
            $error = null;
        } catch (Exception $e) {
            $cities = [];
            $error = $e->getMessage();
            $maxPop = 0;
            $country = null;
        }
        
        view('countries/list.php', compact('cities', 'maxPop', 'error', 'country'));
    }
}
