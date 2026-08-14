# GUÍA PARA PROBAR LA APLICACIÓN

## PASO 1: Preparar la Base de Datos

### Opción A: Usando MySQL desde PowerShell

```powershell
# Navega a la carpeta del proyecto
cd C:\Users\Jair Mina\Desktop\PruebaTecnica

# Importa el archivo SQL
mysql -u root < world.sql
```

### Opción B: Usando phpMyAdmin

1. Abre tu navegador y ve a `http://localhost/phpmyadmin`
2. Selecciona "Importar"
3. Carga el archivo `world.sql`
4. Haz clic en "Continuar"

### Opción C: Usando MySQL Workbench

1. Abre MySQL Workbench
2. File → Open SQL Script
3. Selecciona `world.sql`
4. Ejecuta (Ctrl + Shift + Enter)

---

## PASO 2: Verificar la Conexión a la Base de Datos

Verifica que el archivo `config/database.php` tenga las credenciales correctas:

```php
return [
    'host' => '127.0.0.1',      // Tu servidor MySQL
    'dbname' => 'world',         // Nombre de la BD
    'user' => 'root',            // Tu usuario MySQL
    'pass' => '',                // Tu contraseña (vacío por defecto)
];
```

Si usas una contraseña diferente, actualiza el archivo:
```php
'pass' => 'tu_contraseña',
```

---

## PASO 3: Iniciar un Servidor PHP Local

### Opción A: Usar PHP Built-in Server (MÁS FÁCIL)

```powershell
# Desde la carpeta del proyecto
cd C:\Users\Jair Mina\Desktop\PruebaTecnica\public

# Inicia el servidor en el puerto 8000
php -S localhost:8000
```

Luego abre tu navegador en: **http://localhost:8000**

### Opción B: Usar XAMPP/WAMP

1. Copia la carpeta `PruebaTecnica` a `C:\xampp\htdocs\` (o `C:\wamp\www\`)
2. Abre el navegador en: `http://localhost/PruebaTecnica/public/`

### Opción C: Usar LARAGON

1. Coloca la carpeta en la ruta de LARAGON
2. Accede por: `http://pruebatecnica.local`

---

## PASO 4: Probar la Aplicación

### 1️ **Página Principal**
- Se muestra un dropdown con todos los países
- Selecciona un país y haz clic en "Ver Ciudades"

### 2️ **Ver Ciudades**
- Aparece una tabla con todas las ciudades del país
- Muestra: Nombre, Población y una barra visual
- La barra representa el porcentaje de población

### 3️ **Pruebas Recomendadas**

**Prueba 1: Seleccionar Argentina**
```
 Debe mostrar: Buenos Aires, Córdoba, Rosario, etc.
 Buenos Aires debe tener la barra más grande (2,960,976 habitantes)
```

**Prueba 2: Seleccionar España**
```
Debe mostrar: Madrid, Barcelona, Valencia, etc.
Madrid es la más grande (2,938,723 habitantes)
```

**Prueba 3: Seleccionar Brasil**
```
Ciudades como São Paulo, Rio de Janeiro, Salvador
São Paulo es la más poblada (9,968,485 habitantes)
```

**Prueba 4: Seleccionar México**
```
México City es la más grande (8,591,309 habitantes)
Aparecen todas sus ciudades principales
```

---

## PASO 5: Probar desde POSTMAN (Pregunta 4)

### API de Clima: OpenWeatherMap

**URL:** `https://api.openweathermap.org/data/2.5/weather`

**Parámetros:**
```
q = Madrid
appid = (obtén una clave gratis en openweathermap.org)
units = metric
```

**Ejemplo completo:**
```
https://api.openweathermap.org/data/2.5/weather?q=Madrid&appid=TU_API_KEY&units=metric
```

### API de Cambio de Moneda: ExchangeRate-API

**URL:** `https://api.exchangerate-api.com/v4/latest/USD`

**Parámetros:**
- No requiere autenticación
- USD = moneda de base

**Ejemplo:**
```
https://api.exchangerate-api.com/v4/latest/ARS
```

---

## PASO 6: Verificar Respuestas de BD (Pregunta 2)

Ejecuta estas consultas en phpMyAdmin o MySQL CLI:

### Consulta 1: Ciudades con población ≤ 100,000
```sql
SELECT Name, Population FROM city WHERE Population <= 100000 ORDER BY Population DESC;
```

### Consulta 2: Contar ciudades que comienzan con "R"
```sql
SELECT COUNT(*) as total FROM city WHERE Name LIKE 'R%';
```

### Consulta 3: Top 10 ciudades de países hispanohablantes
```sql
SELECT c.Name, c.Population, c.CountryCode
FROM city c
INNER JOIN country cnt ON c.CountryCode = cnt.Code
WHERE cnt.Code IN ('ARG', 'BRA', 'CHL', 'COL', 'ECU', 'MEX', 'PER', 'VEN', 'ESP')
ORDER BY c.Population DESC
LIMIT 10;
```

---

##  SOLUCIÓN DE PROBLEMAS

###  Error: "Error DB: SQLSTATE[HY000]"
**Solución:** Verifica que MySQL esté ejecutándose
```powershell
# En Windows, abre Services y busca "MySQL"
# o ejecuta:
mysql -u root -p
# (si pide contraseña, introdúcela)
```

###  Error: "No countries found" 
**Solución:** La base de datos no se importó correctamente
```powershell
# Reintentar la importación
mysql -u root world < world.sql
```

###  Página en blanco
**Solución:** Habilita los errores en PHP
```php
// Agrega al inicio de public/index.php
ini_set('display_errors', 1);
error_reporting(E_ALL);
```

###  "Connection refused"
**Solución:** PHP no encuentra MySQL
```powershell
# Verifica que el servidor PHP está en el puerto correcto
# y que MySQL está activo
```

---

##  CHECKLIST DE PRUEBA

- [ ] Base de datos `world` creada
- [ ] Tabla `country` con 20 países
- [ ] Tabla `city` con ciudades
- [ ] Servidor PHP corriendo en `localhost:8000`
- [ ] Puedo seleccionar un país del dropdown
- [ ] Veo las ciudades del país seleccionado
- [ ] La población se muestra correctamente
- [ ] La barra visual funciona
- [ ] Consultas SQL ejecutadas correctamente
- [ ] APIs de clima y moneda probadas en Postman

---

## NOTAS

- La aplicación es **responsive** (funciona en móvil y tablet)
- Los datos se obtienen directamente de MySQL
- La barra de población es visual con CSS
- No hay dependencias externas (solo PHP vanilla)

¡Listo! Tu aplicación debería estar funcionando correctamente.
