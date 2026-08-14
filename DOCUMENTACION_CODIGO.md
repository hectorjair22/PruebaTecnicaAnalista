# Documentación Técnica del Código

## ¿Cómo está organizado todo?

Básicamente la aplicación está dividida en tres partes principales (MVC):

- **Models:** Aquí va todo lo que habla con la base de datos. Son como los "trabajadores" que traen la info.
- **Views:** Son las páginas HTML que ve el usuario. Lo que sale en pantalla.
- **Controllers:** Es el "jefe" que dice qué hacer. Recibe órdenes del usuario y le dice a los Models qué traer.

---

## Explicación de cada archivo

### 1. **app/bootstrap.php**

Este es el primer archivo que se carga. Básicamente incluye todos los otros archivos que necesita la app para funcionar.

```php
<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/controllers/CountryController.php';
require_once __DIR__ . '/models/Database.php';
require_once __DIR__ . '/models/Country.php';
require_once __DIR__ . '/models/City.php';
```

Lo que hace es:
- Carga la configuración de la base de datos
- Carga el controlador de países
- Carga todos los modelos (Database, Country, City)

También tiene una función que se llama `view()` que es para cargar las páginas HTML:

```php
function view($path, $data = []) {
    extract($data);  // Esto convierte el array en variables que puedo usar
    include __DIR__ . '/views/' . $path;  // Carga el archivo HTML
}
```

Por ejemplo, si hago `view('countries/select.php', ['countries' => $list])`, entonces dentro del select.php puedo usar `$countries` directamente.

---

### 2. **app/models/Database.php**

Este archivo es importante porque es lo que nos conecta a la base de datos MySQL. Aquí es donde ocurre la magia de hablar con la BD.

```php
class Database
{
    private static $instance = null;  // Solo va a haber una conexión

    public static function getConnection()
    {
        // Si ya tengo una conexión abierta, la reutilizo
        if (self::$instance) return self::$instance;

        // Si no, creo una nueva
        $cfg = require __DIR__ . '/../../config/database.php';
        try {
            $dsn = "mysql:host={$cfg['host']};dbname={$cfg['dbname']}";
            self::$instance = new PDO($dsn, $cfg['user'], $cfg['pass'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
            return self::$instance;
        } catch (PDOException $e) {
            throw new Exception('Error BD: ' . $e->getMessage());
        }
    }
}
```

Lo que pasa aquí:

1. Uso una técnica llamada "Singleton" - básicamente significa que solo voy a crear UNA conexión y la reutilizo siempre. Así no abro mil conexiones a la BD.

2. La primera vez que alguien llama `Database::getConnection()`, crea la conexión con MySQL.

3. La segunda vez que lo llaman, simplemente reutiliza la que ya existe.

4. Uso PDO (que es una forma segura de conectarse a BD) en lugar de mysqli porque PDO protege contra SQL Injection.

5. Si algo falla, atrapo el error con try/catch y muestro un mensaje.

---

### 3. **app/models/Country.php**

Este modelo se encarga de todo lo relacionado con países. Obtiene los datos de la tabla `country`.

```php
class Country
{
    // Obtiene TODOS los países
    public static function all()
    {
        $db = Database::getConnection();
        $stmt = $db->query('SELECT Code, Name FROM country ORDER BY Name');
        return $stmt->fetchAll();  // Retorna un array con todos
    }

    // Obtiene UN país específico por su código
    public static function getByCode($code)
    {
        $db = Database::getConnection();
        // El ? es un placeholder, previene SQL Injection
        $stmt = $db->prepare('SELECT Code, Name FROM country WHERE Code = ?');
        $stmt->execute([$code]);  // Aquí es donde entra el valor real
        return $stmt->fetch();  // Retorna solo un país
    }
}
```

Métodos:

- `all()`: Trae todos los países ordenados por nombre (A-Z). Útil para llenar el dropdown.
- `getByCode()`: Busca un país específico por su código (ARG, BRA, ESP, etc.). Devuelve UN país o nada.

El método `static` significa que no necesito crear una instancia de Country para llamarlo. Simplemente hago `Country::all()` y funciona.

---

### 4. **app/models/City.php**

**Propósito:** Operaciones con datos de ciudades

```php
class City
{
    // Obtiene todas las ciudades de un país
    public static function byCountry($countryCode)
    {
        $db = Database::getConnection();
        $stmt = $db->prepare('
            SELECT ID, Name, Population 
            FROM city 
            WHERE CountryCode = ? 
            ORDER BY Population DESC  // Mayor población primero
        ');
        $stmt->execute([$countryCode]);
        return $stmt->fetchAll();
    }

    // Obtiene la población máxima de un país
    public static function maxPopulation($countryCode)
    {
        $db = Database::getConnection();
        $stmt = $db->prepare('
            SELECT MAX(Population) as max_pop 
            FROM city 
            WHERE CountryCode = ?
        ');
        $stmt->execute([$countryCode]);
        $result = $stmt->fetch();
        return $result['max_pop'];  // Retorna solo el número
    }
}
```

**Métodos:**
- `byCountry()`: Lista todas las ciudades de un país (ordenadas por población)
- `maxPopulation()`: Calcula la ciudad más poblada (para hacer la escala 0-10)

---

### 5. **app/controllers/CountryController.php**

**Propósito:** Lógica de negocio y coordinación

```php
class CountryController
{
    // Acción 1: Mostrar formulario de selección
    public function index()
    {
        try {
            // Obtiene todos los países del modelo
            $countries = Country::all();
            $error = null;
        } catch (Exception $e) {
            // Si hay error, muestra mensaje
            $countries = [];
            $error = $e->getMessage();
        }
        // Carga la vista con los datos
        view('countries/select.php', compact('countries', 'error'));
    }

    // Acción 2: Mostrar ciudades de un país
    public function cities($countryCode)
    {
        try {
            // Valida que el país existe
            $country = Country::getByCode($countryCode);
            if (!$country) {
                throw new Exception('País no encontrado');
            }

            // Obtiene ciudades del país
            $cities = City::byCountry($countryCode);
            
            // Calcula la población máxima (para escala 0-10)
            $maxPop = City::maxPopulation($countryCode);
            
            $error = null;
        } catch (Exception $e) {
            // Manejo de errores
            $cities = [];
            $error = $e->getMessage();
            $maxPop = 0;
            $country = null;
        }
        
        // Carga la vista con resultados
        view('countries/list.php', compact('cities', 'maxPop', 'error', 'country'));
    }
}
```

**Flujo:**
1. Usuario accede → `index()` → muestra dropdown
2. Usuario selecciona país → `cities()` → muestra ciudades

**Manejo de Errores:**
- Try/catch captura excepciones de BD
- Muestra mensajes amigables al usuario

---

### 6. **app/views/countries/select.php**

**Propósito:** Interfaz de selección de país

**Características:**
- Formulario HTML con select dropdown
- Estilos CSS modernos y responsivos
- JavaScript onchange para envío automático
- Validación de campos obligatorios

**Flujo HTML:**
```
Form POST → /ciudades
  └─ Select name="country"
      └─ Options (todos los países de $countries)
```

---

### 7. **app/views/countries/list.php**

**Propósito:** Mostrar ciudades y población

**Características:**
- Tabla con información de ciudades
- Escala visual (barra de color) 0-10
- Información del país seleccionado
- Botón para volver

**Cálculo de Escala:**
```php
$scale = ($maxPop > 0) ? round(($city['Population'] / $maxPop) * 10, 1) : 0;
```

Ejemplo:
- Ciudad máxima: 10,000,000 habitantes → Escala = 10
- Ciudad: 5,000,000 habitantes → Escala = 5
- Ciudad: 1,000,000 habitantes → Escala = 1

---

### 8. **public/index.php**

**Propósito:** Punto de entrada (Front Controller)

```php
<?php
// Cargamos todo
require_once __DIR__ . '/../app/bootstrap.php';

// Obtenemos el país seleccionado
$action = $_POST['country'] ?? $_GET['action'] ?? 'select';

// Creamos controlador
$controller = new CountryController();

// Ejecutamos acción
if ($action === 'select' || empty($action)) {
    $controller->index();  // Mostrar formulario
} elseif ($action !== 'select') {
    $controller->cities($action);  // Mostrar ciudades
}
```

**¿Por qué es importante?**
- Es la única entrada a la aplicación
- Enruta todas las peticiones al controlador
- Centraliza la lógica de navegación

---

### 9. **config/database.php**

**Propósito:** Configuración de conexión

```php
return [
    'host' => '127.0.0.1',    // Servidor MySQL
    'dbname' => 'world',       // Nombre de BD
    'user' => 'root',          // Usuario
    'pass' => '',              // Contraseña
];
```

**Nota de Seguridad:**
- En producción, usar variables de entorno
- Nunca guardar credenciales en código público
- Usar `.env` file (incluido en .gitignore)

---

##  Flujo de Datos

### Petición 1: Cargar página inicial

```
1. Usuario abre http://localhost:8000
   ↓
2. public/index.php recibe petición
   ↓
3. Bootstrap.php carga clases
   ↓
4. CountryController->index()
   ↓
5. Country::all() → Consulta BD
   ↓
6. view('countries/select.php') → Muestra HTML con dropdown
```

### Petición 2: Seleccionar país

```
1. Usuario selecciona "Argentina" (Code: ARG)
   ↓
2. Formulario POST a /ciudades con country=ARG
   ↓
3. public/index.php recibe $action = "ARG"
   ↓
4. CountryController->cities("ARG")
   ↓
5. Country::getByCode("ARG") → Valida país
   ↓
6. City::byCountry("ARG") → Obtiene ciudades
   ↓
7. City::maxPopulation("ARG") → Obtiene máxima
   ↓
8. view('countries/list.php') → Muestra tabla con ciudades y escala
```

---

##  Conceptos Usados

### 1. **Patrón Singleton**
Solo una conexión a BD activa a la vez
```php
private static $instance = null;
if (self::$instance) return self::$instance;  // Reutiliza
```

### 2. **Prepared Statements**
Previene SQL Injection:
```php
$stmt = $db->prepare('SELECT * FROM city WHERE CountryCode = ?');
$stmt->execute([$countryCode]);  // Valor separado de SQL
```

### 3. **Extract en Vistas**
Convierte array en variables:
```php
extract(['countries' => [...], 'error' => null]);
// Ahora puedo usar $countries y $error directamente
```

### 4. **Compact en Controladores**
Crea array desde variables:
```php
compact('countries', 'error');
// Genera: ['countries' => ..., 'error' => ...]
```

---

##  Testing Manual

### Test 1: Listar países
1. Abrir app
2. Verificar que dropdown tiene múltiples países
3. Verificar que están alfabéticamente ordenados

### Test 2: Seleccionar Argentina
1. Seleccionar "Argentina"
2. Debe mostrar ciudades argentinas
3. Buenos Aires debe tener escala = 10 (es la mayor)
4. Debe haber gráfica visual

### Test 3: Seleccionar México
1. Seleccionar "Mexico"
2. Debe mostrar ciudades mexicanas
3. Mexico City debe tener escala = 10

---

##  Seguridad Implementada

 **Prepared Statements** - Evita SQL Injection  
 **htmlspecialchars()** - Evita XSS en vistas  
 **Conexión PDO** - Acceso seguro a BD  
 **Manejo de excepciones** - No expone detalles de BD  

**Pendiente para Producción:**
- Validar entrada del usuario
- Agregar CSRF token
- Rate limiting
- Logging de errores

---

##  Notas de Desarrollo

- Código sin frameworks para aprendizaje
- Fácil de mantener y extender
- PDO es portable (funciona con MySQL, PostgreSQL, etc.)
- Vistas usan PHP nativo (sin templating engine)

---

##  Cómo Extender

### Agregar nueva funcionalidad

1. Crear método en modelo (`City.php`, `Country.php`)
2. Crear método en controlador (`CountryController.php`)
3. Crear vista (`app/views/nueva-vista.php`)
4. Agregar ruta en `public/index.php`

Ejemplo: Agregar búsqueda de ciudades
```php
// En City.php
public static function search($term) {
    $db = Database::getConnection();
    $stmt = $db->prepare('SELECT * FROM city WHERE Name LIKE ?');
    $stmt->execute(['%' . $term . '%']);
    return $stmt->fetchAll();
}
```

---

##  Dudas Comunes

**P: ¿Por qué usar static?**  
R: Para no crear instancias innecesarias. Los modelos son utilidades.

**P: ¿Por qué PDO y no mysqli?**  
R: PDO es más moderno, portable y seguro.

**P: ¿Por qué compact() es útil?**  
R: Evita pasar variables una por una. Es más legible.

**P: ¿Cómo agregar más países?**  
R: Insertar en tabla `country` del archivo `world.sql`

---
