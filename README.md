# 🌍 Explorador de Ciudades y Países

Aplicación web en PHP con patrón MVC para consultar información de países y sus ciudades. Desarrollado como prueba técnica.

## 📋 Descripción

Aplicación que permite:
- Seleccionar un país de la base de datos
- Ver todas sus ciudades
- Visualizar población de cada ciudad con escala gráfica 0-10

Funciona en **Desktop, Tablet y Móviles**.

---

## 🏗️ Estructura del Proyecto

```
PruebaTecnica/
├── app/
│   ├── bootstrap.php              # Configuración inicial
│   ├── controllers/
│   │   └── CountryController.php  # Lógica de negocio
│   ├── models/
│   │   ├── Database.php           # Conexión BD
│   │   ├── Country.php            # Datos de países
│   │   └── City.php               # Datos de ciudades
│   └── views/
│       └── countries/
│           ├── select.php         # Seleccionar país
│           └── list.php           # Listar ciudades
├── config/
│   └── database.php               # Configuración BD
├── public/
│   └── index.php                  # Punto de entrada
├── world.sql                      # Base de datos
└── README.md                      # Este archivo
```

---

## 🔧 Instalación

### Requisitos
- PHP 8.x
- MySQL 5.7+
- Navegador moderno

### Pasos

#### 1. Configurar Base de Datos

Editar `config/database.php`:
```php
return [
    'host' => '127.0.0.1',   // Tu servidor MySQL
    'dbname' => 'world',     // Nombre de BD
    'user' => 'root',        // Usuario MySQL
    'pass' => '',            // Contraseña
];
```

#### 2. Importar Base de Datos

```bash
# Opción 1: Línea de comandos
mysql -u root < world.sql

# Opción 2: MySQL CLI
mysql -u root -p
mysql> source C:\ruta\world.sql;
```

#### 3. Ejecutar Aplicación

```bash
# Desde la raíz del proyecto
php -S localhost:8000 -t public

# Luego abrir en navegador
http://localhost:8000
```

---

## 💾 Base de Datos

### Tablas

**country** - Almacena países
```
Code (PK)      : char(3)    - Código ISO
Name           : char(52)   - Nombre país
Continent      : enum       - Continente
Region         : char(26)   - Región
Population     : int        - Población
```

**city** - Almacena ciudades
```
ID (PK)        : int        - ID único
Name           : char(35)   - Nombre ciudad
CountryCode (FK): char(3)   - Referencia a país
District       : char(20)   - Provincia
Population     : int        - Población ciudad
```

### Relación
- 1 país → Muchas ciudades
- 1 ciudad → 1 país

---

## 📖 Cómo Funciona

### Paso 1: Seleccionar País
- Usuario accede a la aplicación
- Ve dropdown con lista de países
- Selecciona un país

### Paso 2: Ver Ciudades
- Se envía formulario automáticamente
- Controlador obtiene ciudades del país
- Se calcula población máxima
- Se genera escala 0-10 para cada ciudad
- Se muestra tabla con resultados

### Paso 3: Escala Visual
- Base: ciudad con mayor población = 10
- Fórmula: (población_ciudad / población_máxima) * 10

---

## 🎨 Tecnologías Usadas

- **PHP 8.x** - Backend
- **MySQL** - Base de datos
- **HTML5** - Estructura
- **CSS3** - Estilos responsive
- **PDO** - Acceso a BD

---

## 📊 Consultas SQL Incluidas

Ver archivo `PREGUNTAS_SQL.sql` para:
1. Ciudades con población ≤ 100,000
2. Contar ciudades que comienzan con "R"
3. Top 10 ciudades hispanohablantes

---

## 🤔 Preguntas Teóricas

**Pregunta 3.1:** ¿Clave primaria de country?  
**Respuesta:** Code

**Pregunta 3.2:** Relación city → country?  
**Respuesta:** Un país puede tener varias ciudades

Ver `RESPUESTAS_PREGUNTAS.md` para detalles.

---

## 📡 APIs (Postman)

Colección incluida en `postman/collection-climate-currency.json`

APIs utilizadas:
- **OpenWeatherMap:** Clima actual
- **Exchange Rate API:** Cambio de moneda
- **Open-Meteo:** Clima sin autenticación

---

## 🐛 Errores Comunes

| Error | Solución |
|-------|----------|
| "Error DB" | Verificar MySQL activo y credenciales |
| "No se encontraron ciudades" | BD importada correctamente |
| Página en blanco | Revisar `config/database.php` |

---

## 🚀 Git y Versionamiento

```bash
git init
git add .
git commit -m "Prueba técnica - Explorador de ciudades"
git log --oneline
```

---

## 👨‍💻 Desarrollado por

Analista de Soporte Nivel 1  
Prueba Técnica - Infodec

---


5. Abra http://localhost:8000 en su navegador.

Siguientes pasos que completaré cuando tengas `world.sql`:
- Implementar consultas específicas de la Pregunta 2 (queries SQL y pruebas).
- Crear colección Postman para APIs de clima y cambio.
- Diagnosticar la transacción declinada y documentar evidencias.
