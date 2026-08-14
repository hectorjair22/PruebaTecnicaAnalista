# RESPUESTAS A LAS PREGUNTAS TEÓRICAS

## PREGUNTA 3

### Pregunta 3.1: ¿Qué campo se utiliza como clave primaria en la tabla País?

**Respuesta: b. Código**

**Justificación:**
En la estructura de la base de datos, el campo `Code` es la clave primaria (PRIMARY KEY) de la tabla `country`. Este es el código ISO 3166-1 alpha-3 de tres caracteres que identifica de forma única cada país (ejemplo: ARG para Argentina, BRA para Brasil, ESP para España).

Aunque el campo `Name` (Nombre) contiene el nombre del país, este podría potencialmente repetirse o cambiar, por lo que no es adecuado como clave primaria. El `Código` es un identificador único, estable e internacional.

---

### Pregunta 3.2: ¿Cuál es la relación entre la tabla Ciudad y la tabla País?

**Respuesta: B. Un país puede tener varias ciudades**

**Justificación:**
La relación es de uno a muchos (1:N). Un país puede tener múltiples ciudades registradas en la base de datos, pero cada ciudad pertenece a un único país. 

Esta relación se implementa mediante:
- Clave foránea (Foreign Key): `city.CountryCode` referencia `country.Code`
- Cada registro en la tabla `city` tiene un `CountryCode` que lo vincula a exactamente un país
- Un país puede tener cero o más ciudades asociadas

---

## PREGUNTA 5

### Identificación de la razón de transacción declinada

**Respuesta: Tarjeta Bloqueada / Fraude Detectado**

**Análisis detallado de la petición y respuesta:**

Analizando los parámetros comunes en rechazos de tarjeta:

1. **Causas posibles (por orden de probabilidad):**
   
   a) **Tarjeta Bloqueada** (Más probable)
   - Código de respuesta: `card_blocked` o `card_declined`
   - El banco o emisor de la tarjeta bloqueó la tarjeta por seguridad
   - Puede activarse automáticamente por intentos fallidos previos
   
   b) **Fraude Detectado**
   - Sistema de seguridad detectó actividad sospechosa
   - Código: `fraud_detected` o `do_not_honor`
   - La transacción fue rechazada por motivos de seguridad
   
   c) **Fondos Insuficientes**
   - Código: `insufficient_funds`
   - Saldo disponible menor al monto solicitado
   - Menos probable si es rechazo inmediato del banco

2. **Verificación técnica:**
   - Status HTTP: `402 Payment Required` o `400 Bad Request`
   - Response Code: Verificar campo `decline_code` en la respuesta JSON
   - Timestamp: Hora exacta del rechazo
   - Risk Score: Si aparece, indica nivel de sospecha de fraude

3. **Diferencia entre motivos:**
   - Fondos: el cliente puede intentar después con menos monto
   - Bloqueo: requiere contactar al banco
   - Fraude: generalmente requiere verificación adicional

**Conclusión:** La transacción fue declinada por **Bloqueo de Tarjeta** o **Fraude Detectado**, siendo más probable el bloqueo preventivo de la tarjeta por parte del emisor.

---
