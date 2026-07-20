# Unidad 4: Validación de documentos: JSON Schema y XML Schema

**Duración:** 4 sesiones (4 horas)

## Índice

1. Introducción a la validación de documentos

    1.1. ¿Por qué necesitamos validar documentos?

    1.2. Tecnologías de validación

2. JSON Schema: Fundamentos

    2.1. ¿Qué es JSON Schema?

    2.2. Tipos de datos básicos

    2.3. Propiedades de objetos


3. Validaciones básicas en JSON Schema

    3.1. Validaciones para strings

    3.2. Validaciones para números

    3.3. Validaciones para arrays

    3.4. Validaciones para objetos anidados

4. Asociación y uso práctico de schemas

    4.1. Asociar schemas con documentos JSON

    .2. Validación con Visual Studio Code

    4.3. Validación mediante JavaScript

5. Herramientas de validación JSON Schema

    5.1. Validadores online

    5.2. Generadores de schemas

    5.3. Documentación de schemas

6. XML Schema (XSD): Comparativa básica

    6.1. ¿Qué es XML Schema?

    6.2. Comparativa JSON Schema vs XML Schema

    6.3. Ejemplo comparativo

7. Casos de uso reales y mejores prácticas

    7.1. Dónde se usa JSON Schema

    7.2. Mejores prácticas



## Resultado de aprendizaje y criterios de evaluación



El resultado de aprendizaje asociado, junto a los criterios de  evaluación son los siguientes:

**RA4. Establece mecanismos de validación de documentos para el intercambio de información utilizando métodos para definir su sintaxis y estructura.** 

Criterios de evaluación: 

a) Se ha establecido la necesidad de describir la información transmitida en los documentos y sus reglas.

b) Se han identificado las tecnologías relacionadas con la definición de documentos.

c) Se ha analizado la estructura y sintaxis específica utilizada en la descripción.

d) Se han creado descripciones de documentos.

e) Se han utilizado descripciones en la elaboración y validación de documentos.

f) Se han asociado las descripciones con los documentos.

g) Se han utilizado herramientas específicas.



## 1. Introducción a la validación de documentos

### 1.1. ¿Por qué necesitamos validar documentos?

#### Ejemplo práctico: Formulario de registro de usuario

Imagina que estás desarrollando una aplicación móvil y necesitas implementar un formulario de registro. El usuario debe proporcionar la siguiente información:

- Nombre completo
- Email
- Contraseña
- Edad
- Teléfono (opcional)

El frontend envía estos datos al backend en formato JSON:

```json
{
  "nombre": "Ana García",
  "email": "ana@ejemplo.com",
  "password": "Abc123456",
  "edad": 25,
  "telefono": "+34612345678"
}
```

Ahora bien, ¿qué sucede si el usuario (o alguien malintencionado) envía datos como estos?

```json
{
  "nombre": "",
  "email": "esto-no-es-un-email",
  "password": "123",
  "edad": "veinticinco",
  "telefono": 612345678
}
```

O peor aún:

```json
{
  "nombre": "Carlos López",
  "email": "carlos@ejemplo.com"
}
```

#### Problemas reales: datos incorrectos, campos que faltan, tipos erróneos

Sin un mecanismo de validación adecuado, nos enfrentamos a múltiples problemas:

**1. Campos vacíos o ausentes**
- El nombre está vacío en el primer ejemplo
- La contraseña y edad faltan completamente en el segundo ejemplo
- ¿Cómo guardamos un usuario sin datos esenciales?

**2. Tipos de datos incorrectos**
- La edad llega como texto ("veinticinco") en lugar de número
- El teléfono llega como número cuando esperábamos texto
- Esto puede provocar errores al procesar o guardar en la base de datos

**3. Formatos inválidos**
- El email "esto-no-es-un-email" no tiene formato válido
- La contraseña "123" es demasiado corta e insegura
- No podríamos enviar un email de confirmación a una dirección inválida

**4. Valores fuera de rango**
- ¿Qué pasa si alguien introduce edad = -5 o edad = 250?
- ¿Y si el teléfono tiene 3 dígitos o 50 caracteres?

#### El coste de no validar: bugs, pérdida de datos, problemas de seguridad

Las consecuencias de no validar correctamente los documentos pueden ser graves:

**Bugs y errores en producción**
- La aplicación intenta procesar datos inválidos y se rompe
- Ejemplo: `usuario.edad.toFixed(2)` falla si edad es un string
- Los usuarios ven pantallas de error o la app se cierra inesperadamente

**Pérdida de datos**
- Se guardan registros incompletos o corruptos en la base de datos
- Imposible contactar con usuarios que tienen emails inválidos
- Datos inconsistentes que obligan a limpiezas manuales posteriores

**Problemas de seguridad**
- Contraseñas débiles que comprometen cuentas de usuario
- Inyección de código si no validamos contenido peligroso
- Ejemplo real: un campo de texto que acepta `<script>alert('hack')</script>`

**Pérdida de confianza y costes económicos**
- Usuarios frustrados que abandonan el proceso de registro
- Tiempo de desarrollo perdido solucionando bugs evitables
- Coste de soporte técnico atendiendo problemas de datos
- En e-commerce: pedidos con direcciones incorrectas, productos devueltos, clientes insatisfechos

**Caso real: Validación en pedidos online**

Una tienda online no validaba correctamente el campo "código postal". Resultado:
- Usuarios escribían códigos postales de otros países
- Códigos inventados como "12345" o "00000"
- Envíos fallidos, devoluciones, costes de logística duplicados
- Pérdida estimada: miles de euros al mes

La solución fue implementar validación tanto en frontend como backend para asegurar que los códigos postales fueran válidos antes de confirmar el pedido.

---

### 1.2. Tecnologías de validación

Para evitar estos problemas, necesitamos definir reglas claras sobre cómo deben ser nuestros documentos. Las dos tecnologías principales son:

#### JSON Schema para documentos JSON

**JSON Schema** es un estándar para describir y validar la estructura de documentos JSON. Nos permite especificar:

- Qué campos debe contener el documento
- Qué tipo de datos debe tener cada campo (string, número, booleano...)
- Qué valores son válidos (longitud mínima/máxima, formato, rangos numéricos...)
- Qué campos son obligatorios y cuáles opcionales

Ejemplo de JSON Schema para nuestro formulario de registro:

```json
{
  "$schema": "https://json-schema.org/draft/2020-12/schema",
  "type": "object",
  "properties": {
    "nombre": {
      "type": "string",
      "minLength": 2,
      "maxLength": 100
    },
    "email": {
      "type": "string",
      "format": "email"
    },
    "password": {
      "type": "string",
      "minLength": 8
    },
    "edad": {
      "type": "integer",
      "minimum": 18,
      "maximum": 120
    },
    "telefono": {
      "type": "string",
      "pattern": "^\\+?[0-9]{9,15}$"
    }
  },
  "required": ["nombre", "email", "password", "edad"]
}
```

Este schema garantiza que:
- El nombre tenga entre 2 y 100 caracteres
- El email tenga formato válido
- La contraseña tenga al menos 8 caracteres
- La edad sea un número entero entre 18 y 120
- El teléfono sea opcional pero, si se proporciona, tenga formato válido
- Los campos nombre, email, password y edad sean obligatorios

#### XML Schema (XSD) para documentos XML

**XML Schema** (también llamado XSD por XML Schema Definition) es el estándar para validar documentos XML. Funciona de manera similar a JSON Schema pero utiliza sintaxis XML.

Ejemplo del mismo caso en XML Schema:

```xml
<xs:schema xmlns:xs="http://www.w3.org/2001/XMLSchema">
  <xs:element name="usuario">
    <xs:complexType>
      <xs:sequence>
        <xs:element name="nombre" type="xs:string"/>
        <xs:element name="email" type="xs:string"/>
        <xs:element name="password" type="xs:string"/>
        <xs:element name="edad" type="xs:integer"/>
        <xs:element name="telefono" type="xs:string" minOccurs="0"/>
      </xs:sequence>
    </xs:complexType>
  </xs:element>
</xs:schema>
```

Y el documento XML que validaría:

```xml
<usuario>
  <nombre>Ana García</nombre>
  <email>ana@ejemplo.com</email>
  <password>Abc123456</password>
  <edad>25</edad>
  <telefono>+34612345678</telefono>
</usuario>
```



## 2. JSON Schema: Fundamentos

### 2.1. ¿Qué es JSON Schema?

**JSON Schema** es un vocabulario que nos permite anotar y validar documentos JSON. Es como un contrato que define:
- La estructura que debe tener un documento JSON
- Qué tipo de datos son válidos
- Qué restricciones deben cumplir los valores
- Qué campos son obligatorios u opcionales

#### Ejemplo del mundo real: Configuración de una aplicación

Imagina que estás desarrollando una aplicación móvil y necesitas un archivo de configuración. Este archivo controla aspectos como:
- El nombre de la aplicación
- El servidor API al que conectarse
- El timeout de las peticiones
- Si mostrar logs de debug
- El tema de colores

Un archivo de configuración típico (`config.json`) podría ser:

```json
{
  "appName": "Mi App Increíble",
  "apiUrl": "https://api.ejemplo.com",
  "timeout": 5000,
  "debug": false,
  "theme": {
    "primaryColor": "#3498db",
    "darkMode": true
  },
  "features": ["notifications", "analytics", "chat"]
}
```

Sin un schema, cualquiera podría crear una configuración inválida:

```json
{
  "appName": "",
  "apiUrl": "esto-no-es-una-url",
  "timeout": "cinco mil",
  "debug": "si",
  "theme": null,
  "features": "chat"
}
```

Con JSON Schema, definimos exactamente cómo debe ser el archivo de configuración y podemos validarlo automáticamente antes de que la aplicación intente usarlo.

#### Estructura básica de un schema

Todo JSON Schema es, en sí mismo, un documento JSON. La estructura básica incluye:

```json
{
  "$schema": "https://json-schema.org/draft/2020-12/schema",
  "$id": "https://ejemplo.com/schemas/config.json",
  "title": "Configuración de la aplicación",
  "description": "Schema que define la configuración válida de nuestra app",
  "type": "object",
  "properties": {
    ...
  },
  "required": [...]
}
```

**Elementos principales:**

- **`$schema`**: Indica qué versión de JSON Schema estamos usando. Es como decir "estas son las reglas del juego que seguimos"
- **`$id`**: Identificador único del schema (opcional pero recomendado). Permite referenciar este schema desde otros
- **`title`**: Título descriptivo del schema (para humanos)
- **`description`**: Explicación de qué valida este schema (para humanos)
- **`type`**: El tipo de dato raíz (normalmente "object")
- **`properties`**: Define las propiedades que puede tener el objeto
- **`required`**: Lista de propiedades obligatorias

#### Vocabulario oficial y versiones

JSON Schema es un estándar en evolución. Las versiones principales son:

| Versión | Año | URL del vocabulario | Uso |
|---------|-----|---------------------|-----|
| Draft 4 | 2013 | `http://json-schema.org/draft-04/schema#` | Ampliamente compatible, legacy |
| Draft 7 | 2018 | `http://json-schema.org/draft-07/schema#` | Muy usado actualmente |
| Draft 2019-09 | 2019 | `https://json-schema.org/draft/2019-09/schema` | Mejoras significativas |
| Draft 2020-12 | 2020 | `https://json-schema.org/draft/2020-12/schema` | **Versión actual recomendada** |

**¿Qué versión usar?**
- Para proyectos nuevos: **Draft 2020-12**
- Si necesitas máxima compatibilidad con herramientas antiguas: Draft 7
- Evita Draft 4 salvo que mantengas código antiguo

En este curso usaremos **Draft 2020-12**, que es la más moderna y la que encontrarás en proyectos actuales.

---

### 2.2. Tipos de datos básicos

JSON Schema define varios tipos de datos que corresponden a los tipos disponibles en JSON.

#### Tipos primitivos

**1. `string` - Cadenas de texto**

```json
{
  "type": "string"
}
```

Valores válidos: `"Hola"`, `"123"`, `""`, `"usuario@email.com"`
Valores inválidos: `123`, `true`, `null`

**2. `number` - Números (enteros o decimales)**

```json
{
  "type": "number"
}
```

Valores válidos: `42`, `3.14`, `-17`, `0`, `2.5e10`
Valores inválidos: `"42"`, `true`, `null`

**3. `integer` - Números enteros únicamente**

```json
{
  "type": "integer"
}
```

Valores válidos: `42`, `-17`, `0`
Valores inválidos: `3.14`, `"42"`, `true`

**4. `boolean` - Verdadero o falso**

```json
{
  "type": "boolean"
}
```

Valores válidos: `true`, `false`
Valores inválidos: `"true"`, `1`, `0`, `null`

**5. `null` - Valor nulo**

```json
{
  "type": "null"
}
```

Valor válido: `null`
Valores inválidos: `"null"`, `0`, `false`, `""`

#### Tipos complejos

**6. `object` - Objetos JSON**

```json
{
  "type": "object"
}
```

Valores válidos: `{}`, `{"nombre": "Ana"}`, `{"x": 1, "y": 2}`
Valores inválidos: `[]`, `"objeto"`, `123`

**7. `array` - Arreglos/listas**

```json
{
  "type": "array"
}
```

Valores válidos: `[]`, `[1, 2, 3]`, `["a", "b"]`, `[{"id": 1}]`
Valores inválidos: `{}`, `"array"`, `123`

#### Ejemplo práctico: Schema de un producto de e-commerce

Vamos a crear el schema completo para un producto de una tienda online:

**Documento JSON del producto:**

```json
{
  "id": 12345,
  "nombre": "Portátil Gaming XZ-2000",
  "descripcion": "Portátil de alto rendimiento con RTX 4070",
  "precio": 1299.99,
  "stock": 15,
  "disponible": true,
  "categorias": ["Informática", "Gaming", "Portátiles"],
  "especificaciones": {
    "peso": 2.3,
    "procesador": "Intel i7-13700H",
    "ram": 32
  },
  "descuento": null
}
```

**JSON Schema del producto:**

```json
{
  "$schema": "https://json-schema.org/draft/2020-12/schema",
  "$id": "https://mitienda.com/schemas/producto.json",
  "title": "Producto",
  "description": "Representa un producto en nuestro catálogo",
  "type": "object",
  "properties": {
    "id": {
      "type": "integer",
      "description": "Identificador único del producto"
    },
    "nombre": {
      "type": "string",
      "description": "Nombre comercial del producto"
    },
    "descripcion": {
      "type": "string",
      "description": "Descripción detallada"
    },
    "precio": {
      "type": "number",
      "description": "Precio en euros"
    },
    "stock": {
      "type": "integer",
      "description": "Unidades disponibles en almacén"
    },
    "disponible": {
      "type": "boolean",
      "description": "Si el producto está disponible para la venta"
    },
    "categorias": {
      "type": "array",
      "description": "Categorías a las que pertenece el producto",
      "items": {
        "type": "string"
      }
    },
    "especificaciones": {
      "type": "object",
      "description": "Detalles técnicos del producto"
    },
    "descuento": {
      "type": "null",
      "description": "Descuento aplicable (en esta versión simple, siempre null)"
    }
  }
}
```

**Observaciones importantes:**

1. Cada propiedad tiene su `type` correspondiente
2. Las `description` ayudan a documentar para qué sirve cada campo
3. Para `categorias` (array), usamos `items` para indicar que los elementos son strings
4. `especificaciones` es un objeto, pero aún no definimos su estructura interna (lo haremos en el siguiente apartado)
5. `descuento` está definido como `null`, aunque esto es limitado (más adelante veremos cómo permitir múltiples tipos)

---

### 2.3. Propiedades de objetos

Cuando trabajamos con objetos JSON, necesitamos definir exactamente qué propiedades puede tener, cuáles son obligatorias y si se permiten propiedades adicionales.

#### `properties`: definir las propiedades de un objeto

La palabra clave `properties` nos permite definir cada propiedad del objeto y su schema correspondiente:

```json
{
  "type": "object",
  "properties": {
    "nombre": {
      "type": "string"
    },
    "edad": {
      "type": "integer"
    },
    "email": {
      "type": "string"
    }
  }
}
```

Este schema valida objetos como:

```json
{
  "nombre": "Juan",
  "edad": 30,
  "email": "juan@ejemplo.com"
}
```

**Importante:** Por defecto, todas las propiedades son **opcionales**. Este JSON también sería válido:

```json
{
  "nombre": "María"
}
```

O incluso un objeto vacío:

```json
{}
```

#### `required`: campos obligatorios

Para hacer que ciertas propiedades sean obligatorias, usamos la palabra clave `required`, que es un array con los nombres de las propiedades obligatorias:

```json
{
  "type": "object",
  "properties": {
    "nombre": {
      "type": "string"
    },
    "edad": {
      "type": "integer"
    },
    "email": {
      "type": "string"
    }
  },
  "required": ["nombre", "email"]
}
```

Ahora:
- ✅ Válido: `{"nombre": "Juan", "email": "juan@ejemplo.com"}`
- ✅ Válido: `{"nombre": "Juan", "edad": 30, "email": "juan@ejemplo.com"}`
- ❌ Inválido: `{"nombre": "Juan"}` (falta email)
- ❌ Inválido: `{"edad": 25}` (faltan nombre y email)

#### `additionalProperties`: controlar propiedades extras

Por defecto, JSON Schema permite propiedades que no están definidas en `properties`. Si queremos controlarlo, usamos `additionalProperties`:

**Permitir cualquier propiedad adicional (comportamiento por defecto):**

```json
{
  "type": "object",
  "properties": {
    "nombre": { "type": "string" }
  },
  "additionalProperties": true
}
```

✅ Válido: `{"nombre": "Ana", "apellido": "García", "edad": 28, "ciudad": "Valencia"}`

**No permitir ninguna propiedad adicional:**

```json
{
  "type": "object",
  "properties": {
    "nombre": { "type": "string" },
    "edad": { "type": "integer" }
  },
  "additionalProperties": false
}
```

- ✅ Válido: `{"nombre": "Ana", "edad": 28}`
- ✅ Válido: `{"nombre": "Ana"}`
- ❌ Inválido: `{"nombre": "Ana", "apellido": "García"}` (apellido no está permitido)

**Permitir propiedades adicionales de un tipo específico:**

```json
{
  "type": "object",
  "properties": {
    "nombre": { "type": "string" }
  },
  "additionalProperties": { "type": "string" }
}
```

- ✅ Válido: `{"nombre": "Ana", "apellido": "García", "ciudad": "Madrid"}`
- ❌ Inválido: `{"nombre": "Ana", "edad": 28}` (edad es número, no string)

#### Ejercicio guiado: Crear schema de datos de contacto

Vamos a crear un schema completo para los datos de contacto de un usuario. Estos datos incluirán:

**Campos obligatorios:**
- nombre (string)
- email (string)
- telefono (string)

**Campos opcionales:**
- direccion (objeto con calle, ciudad, codigoPostal)
- notas (string)

**No se permiten propiedades adicionales en el nivel superior**

**Paso 1: Estructura básica**

```json
{
  "$schema": "https://json-schema.org/draft/2020-12/schema",
  "$id": "https://ejemplo.com/schemas/contacto.json",
  "title": "Contacto",
  "description": "Datos de contacto de un usuario",
  "type": "object"
}
```

**Paso 2: Definir las propiedades simples**

```json
{
  "$schema": "https://json-schema.org/draft/2020-12/schema",
  "$id": "https://ejemplo.com/schemas/contacto.json",
  "title": "Contacto",
  "description": "Datos de contacto de un usuario",
  "type": "object",
  "properties": {
    "nombre": {
      "type": "string",
      "description": "Nombre completo del contacto"
    },
    "email": {
      "type": "string",
      "description": "Dirección de correo electrónico"
    },
    "telefono": {
      "type": "string",
      "description": "Número de teléfono"
    },
    "notas": {
      "type": "string",
      "description": "Notas adicionales sobre el contacto"
    }
  }
}
```

**Paso 3: Añadir el objeto dirección**

```json
{
  "$schema": "https://json-schema.org/draft/2020-12/schema",
  "$id": "https://ejemplo.com/schemas/contacto.json",
  "title": "Contacto",
  "description": "Datos de contacto de un usuario",
  "type": "object",
  "properties": {
    "nombre": {
      "type": "string",
      "description": "Nombre completo del contacto"
    },
    "email": {
      "type": "string",
      "description": "Dirección de correo electrónico"
    },
    "telefono": {
      "type": "string",
      "description": "Número de teléfono"
    },
    "direccion": {
      "type": "object",
      "description": "Dirección postal del contacto",
      "properties": {
        "calle": {
          "type": "string",
          "description": "Calle y número"
        },
        "ciudad": {
          "type": "string",
          "description": "Ciudad"
        },
        "codigoPostal": {
          "type": "string",
          "description": "Código postal"
        }
      },
      "required": ["calle", "ciudad", "codigoPostal"]
    },
    "notas": {
      "type": "string",
      "description": "Notas adicionales sobre el contacto"
    }
  }
}
```

**Paso 4: Añadir campos requeridos y bloquear propiedades adicionales**

```json
{
  "$schema": "https://json-schema.org/draft/2020-12/schema",
  "$id": "https://ejemplo.com/schemas/contacto.json",
  "title": "Contacto",
  "description": "Datos de contacto de un usuario",
  "type": "object",
  "properties": {
    "nombre": {
      "type": "string",
      "description": "Nombre completo del contacto"
    },
    "email": {
      "type": "string",
      "description": "Dirección de correo electrónico"
    },
    "telefono": {
      "type": "string",
      "description": "Número de teléfono"
    },
    "direccion": {
      "type": "object",
      "description": "Dirección postal del contacto",
      "properties": {
        "calle": {
          "type": "string",
          "description": "Calle y número"
        },
        "ciudad": {
          "type": "string",
          "description": "Ciudad"
        },
        "codigoPostal": {
          "type": "string",
          "description": "Código postal"
        }
      },
      "required": ["calle", "ciudad", "codigoPostal"],
      "additionalProperties": false
    },
    "notas": {
      "type": "string",
      "description": "Notas adicionales sobre el contacto"
    }
  },
  "required": ["nombre", "email", "telefono"],
  "additionalProperties": false
}
```

**Ejemplos de datos válidos:**

```json
{
  "nombre": "Carlos Martínez",
  "email": "carlos@ejemplo.com",
  "telefono": "+34612345678"
}
```

```json
{
  "nombre": "Ana López",
  "email": "ana@ejemplo.com",
  "telefono": "+34698765432",
  "direccion": {
    "calle": "Calle Mayor 15",
    "ciudad": "Valencia",
    "codigoPostal": "46001"
  },
  "notas": "Cliente VIP"
}
```

**Ejemplos de datos inválidos:**

```json
{
  "nombre": "Pedro García",
  "email": "pedro@ejemplo.com"
}
```
❌ Falta el campo obligatorio `telefono`

```json
{
  "nombre": "Laura Ruiz",
  "email": "laura@ejemplo.com",
  "telefono": "+34655443322",
  "empresa": "Acme Corp"
}
```
❌ `empresa` no está permitido (additionalProperties: false)

```json
{
  "nombre": "Miguel Sánchez",
  "email": "miguel@ejemplo.com",
  "telefono": "+34677889900",
  "direccion": {
    "calle": "Avenida del Puerto 8",
    "ciudad": "Valencia"
  }
}
```
❌ En `direccion` falta `codigoPostal` que es obligatorio

---


## 3. Validaciones básicas en JSON Schema

### 3.1. Validaciones para strings

Los strings son uno de los tipos de datos más comunes y JSON Schema nos ofrece varias formas de validar su contenido.

#### `minLength` y `maxLength`

Estas propiedades controlan la longitud mínima y máxima de una cadena de texto.

```json
{
  "type": "string",
  "minLength": 3,
  "maxLength": 50
}
```

- ✅ Válido: `"Ana"` (3 caracteres)
- ✅ Válido: `"Carlos"` (6 caracteres)
- ✅ Válido: `"Un nombre muy largo pero dentro del límite"` (42 caracteres)
- ❌ Inválido: `"Ab"` (2 caracteres, menos del mínimo)
- ❌ Inválido: `""` (0 caracteres)
- ❌ Inválido: Una cadena de 51 caracteres

**Casos de uso típicos:**
- Nombres de usuario: `minLength: 3, maxLength: 20`
- Contraseñas: `minLength: 8, maxLength: 128`
- Códigos postales: `minLength: 5, maxLength: 5`
- Tweets: `maxLength: 280`

#### `pattern` (expresiones regulares básicas)

El campo `pattern` permite validar que el string cumpla con una expresión regular. Es muy útil para formatos específicos.

**Ejemplo: Validar código postal español**

```json
{
  "type": "string",
  "pattern": "^[0-9]{5}$"
}
```

- ✅ Válido: `"46001"`, `"28080"`, `"08001"`
- ❌ Inválido: `"4600"` (solo 4 dígitos)
- ❌ Inválido: `"460012"` (6 dígitos)
- ❌ Inválido: `"ABCDE"` (letras en lugar de números)

**Ejemplo: Validar matrícula de vehículo española (formato 1234 ABC)**

```json
{
  "type": "string",
  "pattern": "^[0-9]{4}[A-Z]{3}$"
}
```

- ✅ Válido: `"1234ABC"`, `"9876XYZ"`
- ❌ Inválido: `"123ABC"` (solo 3 dígitos)
- ❌ Inválido: `"1234abc"` (letras en minúscula)
- ❌ Inválido: `"1234 ABC"` (contiene espacio)

**Ejemplo: Validar número de teléfono español**

```json
{
  "type": "string",
  "pattern": "^(\\+34)?[6-9][0-9]{8}$"
}
```

- ✅ Válido: `"612345678"`, `"+34612345678"`, `"912345678"`
- ❌ Inválido: `"512345678"` (no empieza por 6-9)
- ❌ Inválido: `"61234567"` (solo 8 dígitos)

**Expresiones regulares comunes:**

| Patrón | Expresión regular | Descripción |
|--------|-------------------|-------------|
| Solo letras | `^[a-zA-Z]+$` | Una o más letras |
| Solo números | `^[0-9]+$` | Uno o más dígitos |
| Alfanumérico | `^[a-zA-Z0-9]+$` | Letras y números |
| Hexadecimal | `^#[0-9A-Fa-f]{6}$` | Color hex (#FF0000) |
| URL slug | `^[a-z0-9-]+$` | Para URLs amigables |

#### `format` (email, date, uri, etc.)

JSON Schema incluye validadores de formato predefinidos que facilitan la validación de tipos comunes sin necesidad de expresiones regulares.

**Formatos disponibles:**

```json
{
  "type": "string",
  "format": "email"
}
```

**Lista de formatos más usados:**

| Format | Descripción | Ejemplo válido |
|--------|-------------|----------------|
| `email` | Dirección de correo | `"usuario@ejemplo.com"` |
| `uri` | URI completa | `"https://ejemplo.com/path"` |
| `date` | Fecha (ISO 8601) | `"2025-01-04"` |
| `time` | Hora (ISO 8601) | `"14:30:00"` |
| `date-time` | Fecha y hora | `"2025-01-04T14:30:00Z"` |
| `ipv4` | Dirección IPv4 | `"192.168.1.1"` |
| `ipv6` | Dirección IPv6 | `"2001:0db8::1"` |
| `uuid` | Identificador UUID | `"550e8400-e29b-41d4-a716-446655440000"` |

**Ejemplos:**

```json
{
  "correo": {
    "type": "string",
    "format": "email"
  },
  "sitioWeb": {
    "type": "string",
    "format": "uri"
  },
  "fechaNacimiento": {
    "type": "string",
    "format": "date"
  }
}
```

- ✅ Válido: `{"correo": "ana@ejemplo.com", "sitioWeb": "https://ejemplo.com", "fechaNacimiento": "1995-03-15"}`
- ❌ Inválido: `{"correo": "ana@", "sitioWeb": "no-es-url", "fechaNacimiento": "15/03/1995"}`

#### Ejemplo: Validar campos de un formulario

Vamos a crear un schema completo para un formulario de registro con todas las validaciones de strings:

```json
{
  "$schema": "https://json-schema.org/draft/2020-12/schema",
  "title": "Formulario de Registro",
  "type": "object",
  "properties": {
    "nombreUsuario": {
      "type": "string",
      "minLength": 3,
      "maxLength": 20,
      "pattern": "^[a-zA-Z0-9_]+$",
      "description": "Solo letras, números y guión bajo"
    },
    "email": {
      "type": "string",
      "format": "email",
      "description": "Dirección de correo electrónico válida"
    },
    "password": {
      "type": "string",
      "minLength": 8,
      "maxLength": 128,
      "description": "Mínimo 8 caracteres"
    },
    "telefono": {
      "type": "string",
      "pattern": "^(\\+34)?[6-9][0-9]{8}$",
      "description": "Teléfono español válido"
    },
    "codigoPostal": {
      "type": "string",
      "pattern": "^[0-9]{5}$",
      "description": "Código postal de 5 dígitos"
    },
    "sitioWeb": {
      "type": "string",
      "format": "uri",
      "description": "URL del sitio web (opcional)"
    },
    "fechaNacimiento": {
      "type": "string",
      "format": "date",
      "description": "Fecha en formato YYYY-MM-DD"
    }
  },
  "required": ["nombreUsuario", "email", "password", "telefono"]
}
```

**Datos válidos:**

```json
{
  "nombreUsuario": "ana_garcia",
  "email": "ana@ejemplo.com",
  "password": "MiPassword123",
  "telefono": "612345678",
  "codigoPostal": "46001",
  "sitioWeb": "https://ana-garcia.com",
  "fechaNacimiento": "1995-06-20"
}
```

**Datos inválidos:**

```json
{
  "nombreUsuario": "ag",  // ❌ Muy corto (mínimo 3)
  "email": "ana@",  // ❌ Email inválido
  "password": "1234",  // ❌ Muy corta (mínimo 8)
  "telefono": "512345678"  // ❌ No empieza por 6-9
}
```

---

### 3.2. Validaciones para números

JSON Schema ofrece validaciones específicas para controlar valores numéricos.

#### `minimum` y `maximum`

Definen el rango válido para un número.

```json
{
  "type": "number",
  "minimum": 0,
  "maximum": 100
}
```

- ✅ Válido: `0`, `50`, `99.99`, `100`
- ❌ Inválido: `-1`, `100.01`, `150`

**Variantes exclusivas:**

```json
{
  "type": "number",
  "exclusiveMinimum": 0,
  "exclusiveMaximum": 100
}
```

- ✅ Válido: `0.01`, `50`, `99.99`
- ❌ Inválido: `0` (el mínimo es exclusivo), `100` (el máximo es exclusivo)

**Diferencia:**
- `minimum: 0` → permite el 0
- `exclusiveMinimum: 0` → **no** permite el 0 (debe ser mayor que 0)

#### `multipleOf`

Valida que el número sea múltiplo de un valor específico.

```json
{
  "type": "number",
  "multipleOf": 5
}
```

- ✅ Válido: `0`, `5`, `10`, `15`, `100`
- ❌ Inválido: `3`, `7`, `12.5`

**Casos de uso:**

**Incrementos de 0.5 (medias unidades):**

```json
{
  "type": "number",
  "multipleOf": 0.5
}
```

- ✅ Válido: `1`, `1.5`, `2`, `2.5`, `10.5`
- ❌ Inválido: `1.3`, `2.7`

**Múltiplos de 10 (cantidades redondas):**

```json
{
  "type": "integer",
  "multipleOf": 10
}
```

- ✅ Válido: `10`, `20`, `100`
- ❌ Inválido: `5`, `15`, `25`

#### Ejemplo: Validar precio y stock de productos

Vamos a crear validaciones realistas para productos de e-commerce:

```json
{
  "$schema": "https://json-schema.org/draft/2020-12/schema",
  "title": "Producto E-commerce",
  "type": "object",
  "properties": {
    "id": {
      "type": "integer",
      "minimum": 1,
      "description": "ID del producto (entero positivo)"
    },
    "nombre": {
      "type": "string",
      "minLength": 3,
      "maxLength": 100
    },
    "precio": {
      "type": "number",
      "minimum": 0.01,
      "maximum": 999999.99,
      "multipleOf": 0.01,
      "description": "Precio en euros, mínimo 1 céntimo, máximo 999.999,99€"
    },
    "precioAnterior": {
      "type": "number",
      "minimum": 0.01,
      "multipleOf": 0.01,
      "description": "Precio antes del descuento (opcional)"
    },
    "stock": {
      "type": "integer",
      "minimum": 0,
      "maximum": 10000,
      "description": "Unidades en stock (0 = agotado)"
    },
    "peso": {
      "type": "number",
      "minimum": 0.001,
      "maximum": 1000,
      "description": "Peso en kilogramos"
    },
    "puntuacion": {
      "type": "number",
      "minimum": 0,
      "maximum": 5,
      "multipleOf": 0.5,
      "description": "Puntuación de 0 a 5, en incrementos de 0.5"
    },
    "descuento": {
      "type": "integer",
      "minimum": 0,
      "maximum": 100,
      "multipleOf": 5,
      "description": "Porcentaje de descuento, en múltiplos de 5"
    }
  },
  "required": ["id", "nombre", "precio", "stock"]
}
```

**Producto válido:**

```json
{
  "id": 12345,
  "nombre": "Teclado Mecánico RGB",
  "precio": 89.99,
  "precioAnterior": 119.99,
  "stock": 25,
  "peso": 1.2,
  "puntuacion": 4.5,
  "descuento": 25
}
```

**Explicación de las validaciones:**

1. **precio**: `multipleOf: 0.01` → Solo admite hasta 2 decimales (céntimos)
2. **stock**: `minimum: 0` → Permite 0 (agotado) pero no negativos
3. **peso**: `minimum: 0.001` → Productos muy ligeros (1 gramo mínimo)
4. **puntuacion**: `multipleOf: 0.5` → Solo 0, 0.5, 1, 1.5, 2, 2.5... 5
5. **descuento**: `multipleOf: 5` → Descuentos en 5%, 10%, 15%, etc.

**Ejemplos de datos inválidos:**

```json
{
  "id": 12345,
  "nombre": "Ratón Gaming",
  "precio": 45.999,  // ❌ No es múltiplo de 0.01 (3 decimales)
  "stock": -5,  // ❌ Stock no puede ser negativo
  "puntuacion": 4.3,  // ❌ No es múltiplo de 0.5
  "descuento": 23  // ❌ No es múltiplo de 5
}
```

---

### 3.3. Validaciones para arrays

Los arrays requieren validaciones específicas para controlar tanto su tamaño como el tipo de elementos que contienen.

#### `items`: tipo de elementos

Define el schema que deben cumplir todos los elementos del array.

**Array de strings:**

```json
{
  "type": "array",
  "items": {
    "type": "string"
  }
}
```

- ✅ Válido: `[]`, `["manzana"]`, `["manzana", "pera", "naranja"]`
- ❌ Inválido: `[1, 2, 3]`, `["texto", 123]`, `[true, false]`

**Array de objetos:**

```json
{
  "type": "array",
  "items": {
    "type": "object",
    "properties": {
      "nombre": { "type": "string" },
      "precio": { "type": "number" }
    },
    "required": ["nombre", "precio"]
  }
}
```

- ✅ Válido: `[{"nombre": "Café", "precio": 1.5}, {"nombre": "Té", "precio": 1.2}]`
- ❌ Inválido: `[{"nombre": "Café"}]` (falta precio)

#### `minItems` y `maxItems`

Controlan el número de elementos en el array.

```json
{
  "type": "array",
  "items": { "type": "string" },
  "minItems": 1,
  "maxItems": 5
}
```

- ✅ Válido: `["uno"]`, `["uno", "dos"]`, `["uno", "dos", "tres", "cuatro", "cinco"]`
- ❌ Inválido: `[]` (vacío, mínimo 1)
- ❌ Inválido: `["a", "b", "c", "d", "e", "f"]` (6 elementos, máximo 5)

**Casos de uso:**
- Categorías de producto: `minItems: 1, maxItems: 5` (al menos 1, máximo 5)
- Imágenes de producto: `minItems: 1, maxItems: 10` (al menos 1 imagen)
- Opciones de respuesta: `minItems: 2, maxItems: 4` (mínimo 2 opciones)

#### `uniqueItems`

Requiere que todos los elementos del array sean únicos (sin duplicados).

```json
{
  "type": "array",
  "items": { "type": "string" },
  "uniqueItems": true
}
```

- ✅ Válido: `["manzana", "pera", "naranja"]`
- ✅ Válido: `[]`, `["único"]`
- ❌ Inválido: `["manzana", "pera", "manzana"]` (manzana está duplicada)

**Ejemplo con números:**

```json
{
  "type": "array",
  "items": { "type": "integer" },
  "uniqueItems": true
}
```

- ✅ Válido: `[1, 2, 3, 4, 5]`
- ❌ Inválido: `[1, 2, 3, 2, 5]` (el 2 está duplicado)

#### Ejemplo: Lista de tags o categorías

Schema completo para tags/categorías de un artículo de blog:

```json
{
  "$schema": "https://json-schema.org/draft/2020-12/schema",
  "title": "Artículo de Blog",
  "type": "object",
  "properties": {
    "titulo": {
      "type": "string",
      "minLength": 10,
      "maxLength": 100
    },
    "contenido": {
      "type": "string",
      "minLength": 100
    },
    "tags": {
      "type": "array",
      "items": {
        "type": "string",
        "minLength": 2,
        "maxLength": 20,
        "pattern": "^[a-z0-9-]+$"
      },
      "minItems": 1,
      "maxItems": 10,
      "uniqueItems": true,
      "description": "Tags del artículo (1-10, únicos, formato kebab-case)"
    },
    "categorias": {
      "type": "array",
      "items": {
        "type": "string",
        "enum": ["tecnologia", "ciencia", "deportes", "cultura", "economia"]
      },
      "minItems": 1,
      "maxItems": 3,
      "uniqueItems": true,
      "description": "Categorías principales (1-3, de la lista predefinida)"
    },
    "imagenes": {
      "type": "array",
      "items": {
        "type": "string",
        "format": "uri"
      },
      "maxItems": 5,
      "uniqueItems": true,
      "description": "URLs de imágenes (máximo 5)"
    }
  },
  "required": ["titulo", "contenido", "tags", "categorias"]
}
```

**Artículo válido:**

```json
{
  "titulo": "Introducción a JSON Schema",
  "contenido": "JSON Schema es una herramienta poderosa para validar documentos JSON. En este artículo exploraremos sus características principales...",
  "tags": ["json", "validacion", "api", "desarrollo-web"],
  "categorias": ["tecnologia", "ciencia"],
  "imagenes": [
    "https://ejemplo.com/img1.jpg",
    "https://ejemplo.com/img2.jpg"
  ]
}
```

**Artículos inválidos:**

```json
{
  "titulo": "Mi artículo",
  "contenido": "Contenido del artículo...",
  "tags": ["json", "json", "api"],  // ❌ "json" duplicado
  "categorias": ["tecnologia"]
}
```

```json
{
  "titulo": "Otro artículo",
  "contenido": "Más contenido...",
  "tags": [],  // ❌ Mínimo 1 tag requerido
  "categorias": ["tecnologia", "musica"]  // ❌ "musica" no está en el enum
}
```

---

### 3.4. Validaciones para objetos anidados

Los objetos pueden contener otros objetos, creando estructuras jerárquicas. JSON Schema permite validar cada nivel de esta jerarquía.

#### Ejemplo práctico: Dirección de envío dentro de un pedido

Vamos a crear un schema para un pedido que incluye una dirección de envío completa:

```json
{
  "$schema": "https://json-schema.org/draft/2020-12/schema",
  "title": "Pedido Online",
  "type": "object",
  "properties": {
    "numeroPedido": {
      "type": "string",
      "pattern": "^PED-[0-9]{8}$",
      "description": "Formato: PED-12345678"
    },
    "cliente": {
      "type": "object",
      "properties": {
        "nombre": {
          "type": "string",
          "minLength": 2,
          "maxLength": 100
        },
        "email": {
          "type": "string",
          "format": "email"
        },
        "telefono": {
          "type": "string",
          "pattern": "^(\\+34)?[6-9][0-9]{8}$"
        }
      },
      "required": ["nombre", "email"],
      "additionalProperties": false
    },
    "direccionEnvio": {
      "type": "object",
      "properties": {
        "calle": {
          "type": "string",
          "minLength": 5,
          "maxLength": 100
        },
        "numero": {
          "type": "string",
          "maxLength": 10,
          "description": "Número, piso, puerta"
        },
        "codigoPostal": {
          "type": "string",
          "pattern": "^[0-9]{5}$"
        },
        "ciudad": {
          "type": "string",
          "minLength": 2,
          "maxLength": 50
        },
        "provincia": {
          "type": "string",
          "minLength": 2,
          "maxLength": 50
        },
        "pais": {
          "type": "string",
          "enum": ["España", "Francia", "Portugal", "Italia"],
          "description": "Países donde realizamos envíos"
        },
        "instrucciones": {
          "type": "string",
          "maxLength": 500,
          "description": "Instrucciones especiales de entrega (opcional)"
        }
      },
      "required": ["calle", "numero", "codigoPostal", "ciudad", "provincia", "pais"],
      "additionalProperties": false
    },
    "productos": {
      "type": "array",
      "items": {
        "type": "object",
        "properties": {
          "id": {
            "type": "integer",
            "minimum": 1
          },
          "nombre": {
            "type": "string",
            "minLength": 3
          },
          "cantidad": {
            "type": "integer",
            "minimum": 1,
            "maximum": 99
          },
          "precio": {
            "type": "number",
            "minimum": 0.01,
            "multipleOf": 0.01
          }
        },
        "required": ["id", "nombre", "cantidad", "precio"],
        "additionalProperties": false
      },
      "minItems": 1,
      "maxItems": 50
    },
    "total": {
      "type": "number",
      "minimum": 0.01,
      "multipleOf": 0.01,
      "description": "Total del pedido en euros"
    },
    "fechaPedido": {
      "type": "string",
      "format": "date-time"
    }
  },
  "required": ["numeroPedido", "cliente", "direccionEnvio", "productos", "total", "fechaPedido"],
  "additionalProperties": false
}
```

**Pedido válido:**

```json
{
  "numeroPedido": "PED-20250104",
  "cliente": {
    "nombre": "Ana García López",
    "email": "ana.garcia@ejemplo.com",
    "telefono": "+34612345678"
  },
  "direccionEnvio": {
    "calle": "Calle Mayor",
    "numero": "15, 3º B",
    "codigoPostal": "46001",
    "ciudad": "Valencia",
    "provincia": "Valencia",
    "pais": "España",
    "instrucciones": "Dejar en conserjería si no hay nadie"
  },
  "productos": [
    {
      "id": 101,
      "nombre": "Teclado Mecánico",
      "cantidad": 1,
      "precio": 89.99
    },
    {
      "id": 205,
      "nombre": "Ratón Gaming",
      "cantidad": 2,
      "precio": 45.50
    }
  ],
  "total": 180.99,
  "fechaPedido": "2025-01-04T14:30:00Z"
}
```

#### Ejercicio: Schema completo de un pedido online

Ahora vamos a completar el schema anterior añadiendo más campos opcionales y validaciones adicionales:

**Requisitos del ejercicio:**

1. Añadir un campo `direccionFacturacion` (opcional) con la misma estructura que `direccionEnvio`
2. Añadir un campo `metodoPago` que solo acepte: "tarjeta", "paypal", "transferencia"
3. Añadir un campo `estado` que sea uno de: "pendiente", "procesando", "enviado", "entregado", "cancelado"
4. Añadir validación para que el campo `descuento` sea opcional, pero si existe sea un número entre 0 y 100 (porcentaje)
5. Añadir un campo `notasInternas` (opcional) para el personal, máximo 1000 caracteres

**Solución del ejercicio:**

```json
{
  "$schema": "https://json-schema.org/draft/2020-12/schema",
  "title": "Pedido Online Completo",
  "type": "object",
  "properties": {
    "numeroPedido": {
      "type": "string",
      "pattern": "^PED-[0-9]{8}$",
      "description": "Formato: PED-12345678"
    },
    "estado": {
      "type": "string",
      "enum": ["pendiente", "procesando", "enviado", "entregado", "cancelado"],
      "description": "Estado actual del pedido"
    },
    "cliente": {
      "type": "object",
      "properties": {
        "nombre": {
          "type": "string",
          "minLength": 2,
          "maxLength": 100
        },
        "email": {
          "type": "string",
          "format": "email"
        },
        "telefono": {
          "type": "string",
          "pattern": "^(\\+34)?[6-9][0-9]{8}$"
        }
      },
      "required": ["nombre", "email"],
      "additionalProperties": false
    },
    "direccionEnvio": {
      "type": "object",
      "properties": {
        "calle": {
          "type": "string",
          "minLength": 5,
          "maxLength": 100
        },
        "numero": {
          "type": "string",
          "maxLength": 10
        },
        "codigoPostal": {
          "type": "string",
          "pattern": "^[0-9]{5}$"
        },
        "ciudad": {
          "type": "string",
          "minLength": 2,
          "maxLength": 50
        },
        "provincia": {
          "type": "string",
          "minLength": 2,
          "maxLength": 50
        },
        "pais": {
          "type": "string",
          "enum": ["España", "Francia", "Portugal", "Italia"]
        },
        "instrucciones": {
          "type": "string",
          "maxLength": 500
        }
      },
      "required": ["calle", "numero", "codigoPostal", "ciudad", "provincia", "pais"],
      "additionalProperties": false
    },
    "direccionFacturacion": {
      "type": "object",
      "properties": {
        "calle": {
          "type": "string",
          "minLength": 5,
          "maxLength": 100
        },
        "numero": {
          "type": "string",
          "maxLength": 10
        },
        "codigoPostal": {
          "type": "string",
          "pattern": "^[0-9]{5}$"
        },
        "ciudad": {
          "type": "string",
          "minLength": 2,
          "maxLength": 50
        },
        "provincia": {
          "type": "string",
          "minLength": 2,
          "maxLength": 50
        },
        "pais": {
          "type": "string",
          "enum": ["España", "Francia", "Portugal", "Italia"]
        }
      },
      "required": ["calle", "numero", "codigoPostal", "ciudad", "provincia", "pais"],
      "additionalProperties": false,
      "description": "Dirección de facturación (opcional, si es diferente a la de envío)"
    },
    "productos": {
      "type": "array",
      "items": {
        "type": "object",
        "properties": {
          "id": {
            "type": "integer",
            "minimum": 1
          },
          "nombre": {
            "type": "string",
            "minLength": 3
          },
          "cantidad": {
            "type": "integer",
            "minimum": 1,
            "maximum": 99
          },
          "precio": {
            "type": "number",
            "minimum": 0.01,
            "multipleOf": 0.01
          }
        },
        "required": ["id", "nombre", "cantidad", "precio"],
        "additionalProperties": false
      },
      "minItems": 1,
      "maxItems": 50
    },
    "subtotal": {
      "type": "number",
      "minimum": 0.01,
      "multipleOf": 0.01,
      "description": "Suma de productos antes de descuentos"
    },
    "descuento": {
      "type": "number",
      "minimum": 0,
      "maximum": 100,
      "description": "Porcentaje de descuento (0-100%)"
    },
    "total": {
      "type": "number",
      "minimum": 0.01,
      "multipleOf": 0.01,
      "description": "Total del pedido en euros"
    },
    "metodoPago": {
      "type": "string",
      "enum": ["tarjeta", "paypal", "transferencia"],
      "description": "Método de pago seleccionado"
    },
    "fechaPedido": {
      "type": "string",
      "format": "date-time"
    },
    "notasInternas": {
      "type": "string",
      "maxLength": 1000,
      "description": "Notas internas para el personal (opcional)"
    }
  },
  "required": [
    "numeroPedido",
    "estado",
    "cliente",
    "direccionEnvio",
    "productos",
    "subtotal",
    "total",
    "metodoPago",
    "fechaPedido"
  ],
  "additionalProperties": false
}
```

**Ejemplo de pedido válido completo:**

```json
{
  "numeroPedido": "PED-20250104",
  "estado": "procesando",
  "cliente": {
    "nombre": "Ana García López",
    "email": "ana.garcia@ejemplo.com",
    "telefono": "+34612345678"
  },
  "direccionEnvio": {
    "calle": "Calle Mayor",
    "numero": "15, 3º B",
    "codigoPostal": "46001",
    "ciudad": "Valencia",
    "provincia": "Valencia",
    "pais": "España",
    "instrucciones": "Dejar en conserjería si no hay nadie"
  },
  "direccionFacturacion": {
    "calle": "Avenida del Puerto",
    "numero": "25",
    "codigoPostal": "46021",
    "ciudad": "Valencia",
    "provincia": "Valencia",
    "pais": "España"
  },
  "productos": [
    {
      "id": 101,
      "nombre": "Teclado Mecánico",
      "cantidad": 1,
      "precio": 89.99
    },
    {
      "id": 205,
      "nombre": "Ratón Gaming",
      "cantidad": 2,
      "precio": 45.50
    }
  ],
  "subtotal": 180.99,
  "descuento": 10,
  "total": 162.89,
  "metodoPago": "tarjeta",
  "fechaPedido": "2025-01-04T14:30:00Z",
  "notasInternas": "Cliente VIP - Envío prioritario"
}
```

## 4. Asociación y uso práctico de schemas

### 4.1. Asociar schemas con documentos JSON

Una vez que hemos creado un JSON Schema, necesitamos asociarlo con nuestros documentos JSON para que las herramientas puedan validarlos automáticamente. Existen varias formas de hacer esta asociación.

#### Mediante propiedad `$schema`

La forma más directa de asociar un schema con un documento JSON es incluir la propiedad `$schema` directamente en el documento.

**Ejemplo básico:**

```json
{
  "$schema": "https://json-schema.org/draft/2020-12/schema",
  "nombre": "Ana García",
  "email": "ana@ejemplo.com",
  "edad": 25
}
```

La propiedad `$schema` indica qué versión del estándar JSON Schema estamos usando. Sin embargo, esto solo valida que el documento es un JSON válido, no que cumpla con reglas específicas.

**Asociar con un schema personalizado:**

Imaginemos que tenemos un schema de usuario guardado en `usuario.schema.json`. Podemos referenciar este schema directamente:

```json
{
  "$schema": "./schemas/usuario.schema.json",
  "nombre": "Carlos López",
  "email": "carlos@ejemplo.com",
  "edad": 30,
  "telefono": "612345678"
}
```

**Rutas relativas vs absolutas:**

Puedes usar diferentes tipos de rutas para referenciar el schema:

**1. Ruta relativa (archivo local):**
```json
{
  "$schema": "./usuario.schema.json"
}
```

**2. Ruta relativa con subcarpeta:**
```json
{
  "$schema": "./schemas/usuario.schema.json"
}
```

**3. URL absoluta (schema público):**
```json
{
  "$schema": "https://miempresa.com/schemas/usuario.schema.json"
}
```

**Ventajas de usar `$schema` en el documento:**
- ✅ Autovalidación: Las herramientas detectan automáticamente qué schema usar
- ✅ Documentación: Queda claro qué estructura debe tener el documento
- ✅ Portabilidad: El documento lleva su validación consigo

**Desventajas:**
- ❌ Modifica el documento original (añade una propiedad extra)
- ❌ No siempre es posible (por ejemplo, si recibes el JSON de un API externa)

#### Referencias externas a ficheros `.schema.json`

En muchos casos, no queremos o no podemos modificar el documento JSON. La solución es mantener el schema en un archivo separado y configurar las herramientas para que lo utilicen.

**Estructura de proyecto típica:**

```
mi-proyecto/
├── datos/
│   ├── usuarios.json
│   ├── productos.json
│   └── pedidos.json
└── schemas/
    ├── usuario.schema.json
    ├── producto.schema.json
    └── pedido.schema.json
```

**Ejemplo de archivo `usuario.schema.json`:**

```json
{
  "$schema": "https://json-schema.org/draft/2020-12/schema",
  "$id": "https://miempresa.com/schemas/usuario.schema.json",
  "title": "Usuario",
  "type": "object",
  "properties": {
    "nombre": {
      "type": "string",
      "minLength": 2,
      "maxLength": 100
    },
    "email": {
      "type": "string",
      "format": "email"
    },
    "edad": {
      "type": "integer",
      "minimum": 18,
      "maximum": 120
    }
  },
  "required": ["nombre", "email"]
}
```

**Archivo de datos `usuarios.json` (sin `$schema`):**

```json
[
  {
    "nombre": "Ana García",
    "email": "ana@ejemplo.com",
    "edad": 25
  },
  {
    "nombre": "Carlos López",
    "email": "carlos@ejemplo.com",
    "edad": 30
  }
]
```

**Configuración en VS Code:**

Para asociar automáticamente schemas con archivos JSON en Visual Studio Code, puedes usar el archivo `.vscode/settings.json`:

```json
{
  "json.schemas": [
    {
      "fileMatch": ["datos/usuarios.json"],
      "url": "./schemas/usuario.schema.json"
    },
    {
      "fileMatch": ["datos/productos.json"],
      "url": "./schemas/producto.schema.json"
    },
    {
      "fileMatch": ["datos/pedidos.json"],
      "url": "./schemas/pedido.schema.json"
    }
  ]
}
```

**También puedes usar patrones con comodines:**

```json
{
  "json.schemas": [
    {
      "fileMatch": ["datos/usuario-*.json"],
      "url": "./schemas/usuario.schema.json"
    },
    {
      "fileMatch": ["**/config/*.json"],
      "url": "./schemas/config.schema.json"
    }
  ]
}
```

Esto permite que:
- `datos/usuario-2024.json` → use `usuario.schema.json`
- `datos/usuario-backup.json` → use `usuario.schema.json`
- `config/app.json` → use `config.schema.json`
- `config/dev/app.json` → use `config.schema.json`

**Ventajas de schemas externos:**
- ✅ No modifica los documentos originales
- ✅ Un schema puede validar múltiples documentos
- ✅ Separación de responsabilidades
- ✅ Facilita el mantenimiento

#### Buenas prácticas de nomenclatura

Seguir convenciones de nomenclatura consistentes facilita el mantenimiento y la colaboración en equipo.

**1. Nombres de archivos schema:**

**Recomendado:**
```
usuario.schema.json          ✅ Claro y descriptivo
producto.schema.json         ✅ Sigue un patrón consistente
pedido.schema.json           ✅ Fácil de identificar
```

**No recomendado:**
```
usuario.json                 ❌ Confuso (¿es un schema o datos?)
schema-usuario.json          ❌ Inconsistente si otros usan sufijo
usuarioSchema.json           ❌ CamelCase menos común en archivos
```

**Patrón recomendado:** `nombre-descriptivo.schema.json`

**2. Propiedad `$id` en el schema:**

La propiedad `$id` debe ser única y seguir formato URI:

```json
{
  "$id": "https://miempresa.com/schemas/usuario.schema.json"
}
```

**Buenas prácticas para `$id`:**

✅ **Usar dominio de tu empresa/proyecto:**
```json
"$id": "https://miempresa.com/schemas/usuario.schema.json"
"$id": "https://api.miapp.com/schemas/v1/producto.schema.json"
```

✅ **Incluir versión si es relevante:**
```json
"$id": "https://miempresa.com/schemas/v2/usuario.schema.json"
```

✅ **Estructura jerárquica clara:**
```json
"$id": "https://miempresa.com/schemas/ecommerce/pedido.schema.json"
"$id": "https://miempresa.com/schemas/ecommerce/producto.schema.json"
```

❌ **Evitar:**
```json
"$id": "usuario"                    // Demasiado genérico
"$id": "file:///C:/schemas/..."     // Ruta local absoluta
"$id": "schema1"                    // No descriptivo
```

**3. Estructura de carpetas:**

**Proyecto pequeño:**
```
proyecto/
├── datos/
│   └── usuarios.json
└── schemas/
    └── usuario.schema.json
```

**Proyecto mediano:**
```
proyecto/
├── datos/
│   ├── usuarios.json
│   ├── productos.json
│   └── pedidos.json
├── schemas/
│   ├── usuario.schema.json
│   ├── producto.schema.json
│   └── pedido.schema.json
└── .vscode/
    └── settings.json
```

**Proyecto grande (con módulos):**
```
proyecto/
├── modulos/
│   ├── usuarios/
│   │   ├── datos/
│   │   │   └── usuarios.json
│   │   └── schemas/
│   │       └── usuario.schema.json
│   ├── productos/
│   │   ├── datos/
│   │   │   └── productos.json
│   │   └── schemas/
│   │       ├── producto.schema.json
│   │       └── categoria.schema.json
│   └── pedidos/
│       ├── datos/
│       │   └── pedidos.json
│       └── schemas/
│           ├── pedido.schema.json
│           └── direccion.schema.json
└── schemas/
    └── common/
        ├── email.schema.json
        └── telefono.schema.json
```

**4. Convenciones de nombres dentro del schema:**

**Para `title` y `description`:**
```json
{
  "title": "Usuario",
  "description": "Representa un usuario del sistema",
  "type": "object"
}
```

**Para nombres de propiedades:**
```json
{
  "properties": {
    "nombreCompleto": {
      "type": "string"
    },
    "fechaNacimiento": {
      "type": "string",
      "format": "date"
    }
  }
}
```

**Alternativa con snake_case (según tu proyecto):**
```json
{
  "properties": {
    "nombre_completo": {
      "type": "string"
    },
    "fecha_nacimiento": {
      "type": "string",
      "format": "date"
    }
  }
}
```

**Lo importante es ser consistente en todo el proyecto.**

**5. Ejemplo completo con buenas prácticas:**

**Archivo: `schemas/usuario.schema.json`**
```json
{
  "$schema": "https://json-schema.org/draft/2020-12/schema",
  "$id": "https://miempresa.com/schemas/usuario.schema.json",
  "title": "Usuario",
  "description": "Schema para validar datos de usuarios del sistema",
  "type": "object",
  "properties": {
    "id": {
      "type": "integer",
      "minimum": 1,
      "description": "Identificador único del usuario"
    },
    "nombreCompleto": {
      "type": "string",
      "minLength": 2,
      "maxLength": 100,
      "description": "Nombre completo del usuario"
    },
    "email": {
      "type": "string",
      "format": "email",
      "description": "Dirección de correo electrónico"
    },
    "fechaRegistro": {
      "type": "string",
      "format": "date-time",
      "description": "Fecha y hora de registro en el sistema"
    }
  },
  "required": ["id", "nombreCompleto", "email"],
  "additionalProperties": false
}
```

**Archivo: `datos/usuarios.json`**
```json
[
  {
    "id": 1,
    "nombreCompleto": "Ana García López",
    "email": "ana.garcia@ejemplo.com",
    "fechaRegistro": "2025-01-04T10:30:00Z"
  },
  {
    "id": 2,
    "nombreCompleto": "Carlos Martínez Ruiz",
    "email": "carlos.martinez@ejemplo.com",
    "fechaRegistro": "2025-01-04T11:15:00Z"
  }
]
```

**Archivo: `.vscode/settings.json`**
```json
{
  "json.schemas": [
    {
      "fileMatch": ["datos/usuarios.json"],
      "url": "./schemas/usuario.schema.json"
    }
  ]
}
```

**Resumen de buenas prácticas:**

✅ Usa el sufijo `.schema.json` para archivos de schema  
✅ Define siempre `$id` con una URI única y descriptiva  
✅ Organiza schemas en carpeta separada (`schemas/`)  
✅ Usa `camelCase` o `snake_case` de forma consistente  
✅ Incluye `title` y `description` en todos los schemas  
✅ Documenta cada propiedad con `description`  
✅ Configura VS Code para asociación automática  
✅ Versiona tus schemas si evolucionan con el tiempo

### 4.2. Validación con Visual Studio Code

Visual Studio Code tiene soporte nativo para JSON Schema, lo que lo convierte en una herramienta excelente para trabajar con validación de documentos JSON. Vamos a ver cómo aprovechar todas sus funcionalidades.

#### Extensión JSON Schema validation

VS Code incluye validación de JSON Schema de forma nativa, pero podemos mejorar la experiencia instalando extensiones adicionales.

**Extensiones recomendadas:**

1. **JSON (incluida por defecto)** - Proporciona:
   - Validación automática contra schemas
   - Autocompletado de propiedades
   - Detección de errores en tiempo real
   - Documentación al pasar el ratón

2. **YAML (opcional, si trabajas con YAML)** - `redhat.vscode-yaml`
   - Validación de YAML con JSON Schema
   - Útil para archivos de configuración

Para instalar extensiones:
1. Abre VS Code
2. Presiona `Ctrl+Shift+X` (o `Cmd+Shift+X` en Mac)
3. Busca el nombre de la extensión
4. Haz clic en "Instalar"

#### Configuración de asociaciones automáticas

Ya vimos en el punto anterior cómo configurar asociaciones. Ahora veremos todas las opciones disponibles.

**Método 1: Configuración del workspace (`.vscode/settings.json`)**

Esta es la forma recomendada para proyectos. Crea el archivo `.vscode/settings.json` en la raíz de tu proyecto:

```json
{
  "json.schemas": [
    {
      "fileMatch": ["config.json"],
      "url": "./schemas/config.schema.json"
    },
    {
      "fileMatch": ["data/usuarios/*.json"],
      "url": "./schemas/usuario.schema.json"
    },
    {
      "fileMatch": ["**/*-producto.json"],
      "url": "./schemas/producto.schema.json"
    }
  ]
}
```

**Patrones de `fileMatch`:**

| Patrón | Descripción | Ejemplo de archivo que coincide |
|--------|-------------|--------------------------------|
| `"config.json"` | Archivo específico | `config.json` |
| `"*.json"` | Todos los JSON en la raíz | `datos.json`, `config.json` |
| `"data/*.json"` | JSON en carpeta específica | `data/usuarios.json` |
| `"**/*.json"` | Todos los JSON en cualquier nivel | `a/b/c/datos.json` |
| `"usuario-*.json"` | Con prefijo específico | `usuario-2024.json` |
| `"*-config.json"` | Con sufijo específico | `app-config.json` |

**Método 2: Configuración global del usuario**

Para aplicar schemas a todos tus proyectos, usa la configuración de usuario:

1. Presiona `Ctrl+Shift+P` (o `Cmd+Shift+P` en Mac)
2. Escribe "Preferences: Open User Settings (JSON)"
3. Añade la configuración:

```json
{
  "json.schemas": [
    {
      "fileMatch": ["**/package.json"],
      "url": "https://json.schemastore.org/package.json"
    },
    {
      "fileMatch": ["**/tsconfig.json"],
      "url": "https://json.schemastore.org/tsconfig.json"
    }
  ]
}
```

**Método 3: Schema Store (automático)**

VS Code se conecta automáticamente a [Schema Store](https://www.schemastore.org/), un repositorio público de schemas para archivos comunes como:

- `package.json` (Node.js)
- `tsconfig.json` (TypeScript)
- `composer.json` (PHP)
- `.eslintrc.json` (ESLint)
- Y muchos más...

Estos se detectan automáticamente sin configuración adicional.

#### Demostración práctica: Autocompletado y detección de errores

Vamos a crear un ejemplo práctico para ver todas las funcionalidades en acción.

**Paso 1: Crear la estructura del proyecto**

```
mi-proyecto/
├── .vscode/
│   └── settings.json
├── schemas/
│   └── producto.schema.json
└── datos/
    └── productos.json
```

**Paso 2: Crear el schema (`schemas/producto.schema.json`)**

```json
{
  "$schema": "https://json-schema.org/draft/2020-12/schema",
  "$id": "https://miempresa.com/schemas/producto.schema.json",
  "title": "Producto",
  "description": "Schema para productos del catálogo",
  "type": "object",
  "properties": {
    "id": {
      "type": "integer",
      "minimum": 1,
      "description": "Identificador único del producto"
    },
    "nombre": {
      "type": "string",
      "minLength": 3,
      "maxLength": 100,
      "description": "Nombre del producto"
    },
    "precio": {
      "type": "number",
      "minimum": 0.01,
      "maximum": 999999.99,
      "multipleOf": 0.01,
      "description": "Precio en euros (hasta 2 decimales)"
    },
    "stock": {
      "type": "integer",
      "minimum": 0,
      "description": "Unidades disponibles en almacén"
    },
    "categoria": {
      "type": "string",
      "enum": ["electronica", "hogar", "deporte", "ropa", "libros"],
      "description": "Categoría del producto"
    },
    "disponible": {
      "type": "boolean",
      "description": "Si el producto está disponible para la venta"
    },
    "etiquetas": {
      "type": "array",
      "items": {
        "type": "string",
        "minLength": 2,
        "maxLength": 20
      },
      "minItems": 1,
      "maxItems": 10,
      "uniqueItems": true,
      "description": "Etiquetas descriptivas del producto"
    }
  },
  "required": ["id", "nombre", "precio", "stock", "categoria", "disponible"],
  "additionalProperties": false
}
```

**Paso 3: Configurar VS Code (`.vscode/settings.json`)**

```json
{
  "json.schemas": [
    {
      "fileMatch": ["datos/productos.json"],
      "url": "./schemas/producto.schema.json"
    }
  ]
}
```

**Paso 4: Crear el archivo de datos (`datos/productos.json`)**

Ahora abre el archivo `datos/productos.json` en VS Code y empieza a escribir:

```json
{
  
}
```

**🎯 Funcionalidad 1: Autocompletado**

Cuando escribas `"` dentro del objeto, VS Code te mostrará automáticamente las propiedades disponibles:



- `id`
- `nombre`
- `precio`
- `stock`
- `categoria`
- `disponible`
- `etiquetas`

Al seleccionar una, VS Code:
- Completa el nombre de la propiedad
- Añade el tipo de dato apropiado
- Posiciona el cursor para que empieces a escribir el valor

**🎯 Funcionalidad 2: Sugerencias de valores**

Si escribes `"categoria": `, VS Code te sugerirá los valores del `enum`:

```json
{
  "categoria": "█"
}
```

Sugerencias:
- `electronica`
- `hogar`
- `deporte`
- `ropa`
- `libros`

**🎯Funcionalidad 3: Detección de errores en tiempo real**

Escribe un producto con errores:

```json
{
  "id": "ABC",
  "nombre": "TV",
  "precio": 299.999,
  "stock": -5,
  "categoria": "tecnologia",
  "disponible": "si"
}
```

VS Code mostrará subrayados rojos y amarillos:

1. **`"id": "ABC"`** 
   - ❌ Error: "Incorrect type. Expected integer."
   
2. **`"nombre": "TV"`**
   - ⚠️ Advertencia: "String is shorter than the minimum length of 3."
   
3. **`"precio": 299.999"`**
   - ⚠️ Advertencia: "Value is not a multiple of 0.01."
   
4. **`"stock": -5"`**
   - ❌ Error: "Value is below the minimum of 0."
   
5. **`"categoria": "tecnologia"`**
   - ❌ Error: "Value is not accepted. Valid values: 'electronica', 'hogar', 'deporte', 'ropa', 'libros'."
   
6. **`"disponible": "si"`**
   - ❌ Error: "Incorrect type. Expected boolean."

**🎯 Funcionalidad 4: Documentación al pasar el ratón**

Si pasas el ratón sobre cualquier propiedad, VS Code muestra:

```
precio: number
Precio en euros (hasta 2 decimales)

Constraints:
• minimum: 0.01
• maximum: 999999.99
• multipleOf: 0.01
```

**🎯 Funcionalidad 5: Detección de campos faltantes**

Si guardas el archivo sin campos obligatorios:

```json
{
  "nombre": "Portátil Gaming"
}
```

VS Code mostrará:
- ❌ "Missing property 'id'."
- ❌ "Missing property 'precio'."
- ❌ "Missing property 'stock'."
- ❌ "Missing property 'categoria'."
- ❌ "Missing property 'disponible'."

**🎯 Funcionalidad 6: Detección de propiedades adicionales**

Si `additionalProperties: false` está configurado y añades una propiedad no definida:

```json
{
  "id": 1,
  "nombre": "Teclado Mecánico",
  "precio": 89.99,
  "stock": 25,
  "categoria": "electronica",
  "disponible": true,
  "fabricante": "Logitech"
}
```

VS Code marcará:
- ❌ "Property 'fabricante' is not allowed."

**Ejemplo completo correcto:**

```json
{
  "id": 1,
  "nombre": "Teclado Mecánico RGB",
  "precio": 89.99,
  "stock": 25,
  "categoria": "electronica",
  "disponible": true,
  "etiquetas": ["gaming", "rgb", "mecanico", "logitech"]
}
```

Con este archivo, VS Code:
- ✅ No muestra errores
- ✅ Marca con un check verde en la barra de estado
- ✅ Permite navegar con autocompletado
- ✅ Muestra documentación contextual

**Atajos de teclado útiles en VS Code:**

| Atajo | Función |
|-------|---------|
| `Ctrl+Space` | Mostrar autocompletado |
| `Ctrl+Shift+M` | Ver lista de problemas/errores |
| `F8` | Ir al siguiente error |
| `Shift+F8` | Ir al error anterior |
| `Ctrl+K Ctrl+I` | Mostrar información al hover |
| `Alt+Shift+F` | Formatear documento |

**Configuración adicional recomendada para JSON:**

Añade esto a tu `.vscode/settings.json` para mejorar la experiencia:

```json
{
  "json.schemas": [
    {
      "fileMatch": ["datos/productos.json"],
      "url": "./schemas/producto.schema.json"
    }
  ],
  "editor.formatOnSave": true,
  "editor.quickSuggestions": {
    "strings": true
  },
  "json.validate.enable": true,
  "files.autoSave": "afterDelay",
  "files.autoSaveDelay": 1000
}
```

Estas configuraciones:
- Formatean automáticamente al guardar
- Muestran sugerencias dentro de strings (para valores enum)
- Activan la validación JSON
- Guardan automáticamente después de 1 segundo

**Solución de problemas comunes:**

**Problema 1: VS Code no detecta mi schema**

✅ Soluciones:
1. Verifica que la ruta en `url` sea correcta (relativa al workspace)
2. Recarga la ventana: `Ctrl+Shift+P` → "Developer: Reload Window"
3. Verifica que el archivo schema sea JSON válido
4. Comprueba que `fileMatch` coincida con tu archivo

**Problema 2: No aparece autocompletado**

✅ Soluciones:
1. Presiona `Ctrl+Space` manualmente
2. Verifica que `json.validate.enable` esté en `true`
3. Comprueba que el archivo tenga extensión `.json`
4. Asegúrate de que el schema esté bien formado

**Problema 3: El schema no se actualiza tras cambios**

✅ Soluciones:
1. Guarda el archivo schema
2. Cierra y reabre el archivo JSON
3. Recarga VS Code si persiste el problema

**Ejercicio práctico:**

Crea un proyecto con la siguiente estructura y verifica que VS Code valide correctamente:

1. Crea un schema para un archivo de configuración de aplicación con:
   - `appName` (string, 3-50 caracteres)
   - `version` (string, patrón semver: "1.0.0")
   - `port` (integer, 1000-65535)
   - `environment` (enum: "development", "staging", "production")
   - `debug` (boolean)

2. Configura VS Code para que valide `config.json` con este schema

3. Crea un archivo `config.json` y verifica:
   - El autocompletado funciona
   - Los errores se detectan
   - Las sugerencias de enum aparecen
   - La documentación se muestra al pasar el ratón

   ### 4.3. Validación mediante JavaScript

Hasta ahora hemos visto cómo validar JSON con VS Code, pero en aplicaciones reales necesitamos validar datos programáticamente. JavaScript nos permite validar JSON Schema tanto en el navegador como en Node.js.

#### Librería Ajv (Another JSON Schema Validator)

**Ajv** es la librería más popular y rápida para validar JSON Schema en JavaScript. Soporta todas las versiones de JSON Schema y es muy eficiente.

**Características principales:**
- ✅ Muy rápida (compila schemas para mayor rendimiento)
- ✅ Soporta JSON Schema Draft 2020-12, 2019-09, 7, 6 y 4
- ✅ Funciona en navegador y Node.js
- ✅ Validación de formatos (email, uri, date, etc.)
- ✅ Mensajes de error detallados
- ✅ Ampliamente usada y mantenida

**Alternativas a Ajv:**
- `jsonschema` - Más simple pero menos performante
- `joi` - Validación con sintaxis diferente (no usa JSON Schema estándar)
- `zod` - Popular en TypeScript, pero usa su propio formato

**En este curso usaremos Ajv por ser el estándar de la industria.**

#### Instalación y uso básico

**Instalación en Node.js:**

```bash
npm install ajv
```

Para soportar formatos como `email`, `uri`, `date`, etc., también necesitamos:

```bash
npm install ajv-formats
```

**Uso básico en Node.js:**

Crea un archivo `validar.js`:

```javascript
// Importar Ajv
const Ajv = require('ajv');
const addFormats = require('ajv-formats');

// Crear instancia de Ajv
const ajv = new Ajv();
addFormats(ajv); // Añadir soporte para formatos

// Definir el schema
const schema = {
  type: "object",
  properties: {
    nombre: { type: "string", minLength: 2 },
    email: { type: "string", format: "email" },
    edad: { type: "integer", minimum: 18 }
  },
  required: ["nombre", "email"],
  additionalProperties: false
};

// Compilar el schema (esto mejora el rendimiento)
const validate = ajv.compile(schema);

// Datos a validar
const datos = {
  nombre: "Ana García",
  email: "ana@ejemplo.com",
  edad: 25
};

// Validar
const valido = validate(datos);

if (valido) {
  console.log("✅ Datos válidos");
} else {
  console.log("❌ Datos inválidos:");
  console.log(validate.errors);
}
```

Ejecutar:
```bash
node validar.js
```

Salida:
```
✅ Datos válidos
```

**Probemos con datos inválidos:**

```javascript
const datosInvalidos = {
  nombre: "A",  // Muy corto
  email: "no-es-email",  // Formato inválido
  edad: 15,  // Menor de 18
  extra: "no permitido"  // Propiedad adicional
};

const valido = validate(datosInvalidos);

if (!valido) {
  console.log("❌ Errores encontrados:");
  validate.errors.forEach(error => {
    console.log(`  - ${error.instancePath || 'raíz'}: ${error.message}`);
  });
}
```

Salida:
```
❌ Errores encontrados:
  - /nombre: must NOT have fewer than 2 characters
  - /email: must match format "email"
  - /edad: must be >= 18
  - raíz: must NOT have additional properties
```

#### Ejemplo completo: Validar formulario antes de enviarlo

Vamos a crear un ejemplo realista: validar un formulario de registro antes de enviar los datos al servidor.

**Paso 1: Crear el schema (`schemas/registro.schema.json`)**

```json
{
  "$schema": "https://json-schema.org/draft/2020-12/schema",
  "title": "Registro de Usuario",
  "type": "object",
  "properties": {
    "nombreUsuario": {
      "type": "string",
      "minLength": 3,
      "maxLength": 20,
      "pattern": "^[a-zA-Z0-9_]+$",
      "description": "Solo letras, números y guión bajo"
    },
    "email": {
      "type": "string",
      "format": "email"
    },
    "password": {
      "type": "string",
      "minLength": 8,
      "maxLength": 128
    },
    "edad": {
      "type": "integer",
      "minimum": 18,
      "maximum": 120
    },
    "telefono": {
      "type": "string",
      "pattern": "^(\\+34)?[6-9][0-9]{8}$"
    },
    "aceptaTerminos": {
      "type": "boolean",
      "const": true,
      "description": "Debe aceptar los términos"
    }
  },
  "required": ["nombreUsuario", "email", "password", "edad", "aceptaTerminos"],
  "additionalProperties": false
}
```

**Paso 2: Crear el validador (`validador.js`)**

```javascript
const Ajv = require("ajv/dist/2020");
const addFormats = require('ajv-formats');
const fs = require('fs');

// Crear instancia de Ajv con opciones
const ajv = new Ajv({
  allErrors: true,  // Reportar todos los errores, no solo el primero
  verbose: true     // Información detallada en errores
});
addFormats(ajv);

// Cargar el schema desde archivo
const schema = JSON.parse(
  fs.readFileSync('./schemas/registro.schema.json', 'utf-8')
);

// Compilar el schema
const validate = ajv.compile(schema);

// Función para validar datos de registro
function validarRegistro(datos) {
  const valido = validate(datos);
  
  if (valido) {
    return {
      valido: true,
      datos: datos
    };
  } else {
    return {
      valido: false,
      errores: validate.errors.map(error => ({
        campo: error.instancePath.replace('/', '') || 'general',
        mensaje: obtenerMensajeAmigable(error)
      }))
    };
  }
}

// Función para convertir errores técnicos en mensajes amigables
function obtenerMensajeAmigable(error) {
  const mensajes = {
    'minLength': `debe tener al menos ${error.params.limit} caracteres`,
    'maxLength': `debe tener como máximo ${error.params.limit} caracteres`,
    'pattern': 'tiene un formato inválido',
    'format': `debe ser un ${error.params.format} válido`,
    'minimum': `debe ser mayor o igual a ${error.params.limit}`,
    'maximum': `debe ser menor o igual a ${error.params.limit}`,
    'type': `debe ser de tipo ${error.params.type}`,
    'required': `el campo ${error.params.missingProperty} es obligatorio`,
    'const': 'debes aceptar los términos y condiciones',
    'additionalProperties': `la propiedad ${error.params.additionalProperty} no está permitida`
  };
  
  return mensajes[error.keyword] || error.message;
}

// Exportar la función
module.exports = { validarRegistro };
```

**Paso 3: Usar el validador (`app.js`)**

```javascript
const { validarRegistro } = require('./validador');

// Simular datos de un formulario
console.log('\n=== Ejemplo 1: Datos válidos ===');
const datosValidos = {
  nombreUsuario: "ana_garcia",
  email: "ana@ejemplo.com",
  password: "MiPassword123",
  edad: 25,
  telefono: "612345678",
  aceptaTerminos: true
};

const resultado1 = validarRegistro(datosValidos);
if (resultado1.valido) {
  console.log("✅ Registro válido. Enviando al servidor...");
  console.log(resultado1.datos);
} else {
  console.log("❌ Errores en el registro:");
  resultado1.errores.forEach(error => {
    console.log(`  - ${error.campo}: ${error.mensaje}`);
  });
}

// Ejemplo con errores
console.log('\n=== Ejemplo 2: Datos con errores ===');
const datosInvalidos = {
  nombreUsuario: "ab",  // Muy corto
  email: "ana@",  // Email inválido
  password: "123",  // Muy corta
  edad: 15,  // Menor de edad
  telefono: "512345678",  // No empieza por 6-9
  aceptaTerminos: false,  // No acepta términos
  extra: "campo no permitido"  // Propiedad adicional
};

const resultado2 = validarRegistro(datosInvalidos);
if (!resultado2.valido) {
  console.log("❌ Errores encontrados:");
  resultado2.errores.forEach(error => {
    console.log(`  - ${error.campo}: ${error.mensaje}`);
  });
}

// Ejemplo con campos faltantes
console.log('\n=== Ejemplo 3: Campos obligatorios faltantes ===');
const datosIncompletos = {
  nombreUsuario: "carlos_lopez",
  email: "carlos@ejemplo.com"
  // Faltan: password, edad, aceptaTerminos
};

const resultado3 = validarRegistro(datosIncompletos);
if (!resultado3.valido) {
  console.log("❌ Errores encontrados:");
  resultado3.errores.forEach(error => {
    console.log(`  - ${error.campo}: ${error.mensaje}`);
  });
}
```

Ejecutar:
```bash
node app.js
```

Salida:
```
=== Ejemplo 1: Datos válidos ===
✅ Registro válido. Enviando al servidor...
{
  nombreUsuario: 'ana_garcia',
  email: 'ana@ejemplo.com',
  password: 'MiPassword123',
  edad: 25,
  telefono: '612345678',
  aceptaTerminos: true
}

=== Ejemplo 2: Datos con errores ===
❌ Errores encontrados:
  - nombreUsuario: debe tener al menos 3 caracteres
  - email: debe ser un email válido
  - password: debe tener al menos 8 caracteres
  - edad: debe ser mayor o igual a 18
  - telefono: tiene un formato inválido
  - aceptaTerminos: debes aceptar los términos y condiciones
  - general: la propiedad extra no está permitida

=== Ejemplo 3: Campos obligatorios faltantes ===
❌ Errores encontrados:
  - general: el campo password es obligatorio
  - general: el campo edad es obligatorio
  - general: el campo aceptaTerminos es obligatorio
```

#### Ejemplo en el navegador (HTML + JavaScript) [OPCIONAL]

Ahora vamos a ver cómo validar en el frontend usando Ajv directamente en el navegador.

**Archivo `index.html`:**

```html
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Validación con JSON Schema</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      max-width: 500px;
      margin: 50px auto;
      padding: 20px;
    }
    .form-group {
      margin-bottom: 15px;
    }
    label {
      display: block;
      margin-bottom: 5px;
      font-weight: bold;
    }
    input {
      width: 100%;
      padding: 8px;
      border: 1px solid #ccc;
      border-radius: 4px;
      box-sizing: border-box;
    }
    input.error {
      border-color: #dc3545;
    }
    input.valid {
      border-color: #28a745;
    }
    .error-message {
      color: #dc3545;
      font-size: 14px;
      margin-top: 5px;
    }
    button {
      background-color: #007bff;
      color: white;
      padding: 10px 20px;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      font-size: 16px;
    }
    button:hover {
      background-color: #0056b3;
    }
    button:disabled {
      background-color: #ccc;
      cursor: not-allowed;
    }
    .success {
      background-color: #d4edda;
      color: #155724;
      padding: 15px;
      border: 1px solid #c3e6cb;
      border-radius: 4px;
      margin-top: 20px;
    }
  </style>
</head>
<body>
  <h1>Formulario de Registro</h1>
  
  <form id="registroForm">
    <div class="form-group">
      <label for="nombreUsuario">Nombre de usuario:</label>
      <input type="text" id="nombreUsuario" name="nombreUsuario">
      <div class="error-message" id="error-nombreUsuario"></div>
    </div>

    <div class="form-group">
      <label for="email">Email:</label>
      <input type="email" id="email" name="email">
      <div class="error-message" id="error-email"></div>
    </div>

    <div class="form-group">
      <label for="password">Contraseña:</label>
      <input type="password" id="password" name="password">
      <div class="error-message" id="error-password"></div>
    </div>

    <div class="form-group">
      <label for="edad">Edad:</label>
      <input type="number" id="edad" name="edad">
      <div class="error-message" id="error-edad"></div>
    </div>

    <div class="form-group">
      <label for="telefono">Teléfono:</label>
      <input type="tel" id="telefono" name="telefono" placeholder="+34612345678">
      <div class="error-message" id="error-telefono"></div>
    </div>

    <div class="form-group">
      <label>
        <input type="checkbox" id="aceptaTerminos" name="aceptaTerminos">
        Acepto los términos y condiciones
      </label>
      <div class="error-message" id="error-aceptaTerminos"></div>
    </div>

    <button type="submit">Registrarse</button>
  </form>

  <div id="resultado"></div>

  <!-- Cargar Ajv desde CDN -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/ajv/8.12.0/ajv7.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/ajv-formats/2.1.1/ajv-formats.min.js"></script>
  
  <script src="validacion.js"></script>
</body>
</html>
```

**Archivo `validacion.js`:**

```javascript
// Crear instancia de Ajv
const ajv = new window.ajv7({
  allErrors: true,
  verbose: true
});

// Añadir formatos
window.ajvFormats(ajv);

// Definir el schema
const schema = {
  type: "object",
  properties: {
    nombreUsuario: {
      type: "string",
      minLength: 3,
      maxLength: 20,
      pattern: "^[a-zA-Z0-9_]+$"
    },
    email: {
      type: "string",
      format: "email"
    },
    password: {
      type: "string",
      minLength: 8
    },
    edad: {
      type: "integer",
      minimum: 18,
      maximum: 120
    },
    telefono: {
      type: "string",
      pattern: "^(\\+34)?[6-9][0-9]{8}$"
    },
    aceptaTerminos: {
      type: "boolean",
      const: true
    }
  },
  required: ["nombreUsuario", "email", "password", "edad", "aceptaTerminos"],
  additionalProperties: false
};

// Compilar el schema
const validate = ajv.compile(schema);

// Mensajes amigables en español
const mensajesError = {
  minLength: (params) => `Debe tener al menos ${params.limit} caracteres`,
  maxLength: (params) => `Debe tener como máximo ${params.limit} caracteres`,
  pattern: () => 'Formato inválido',
  format: (params) => `Debe ser un ${params.format} válido`,
  minimum: (params) => `Debe ser mayor o igual a ${params.limit}`,
  maximum: (params) => `Debe ser menor o igual a ${params.limit}`,
  type: (params) => `Debe ser de tipo ${params.type}`,
  required: (params) => `El campo es obligatorio`,
  const: () => 'Debes aceptar los términos y condiciones'
};

// Limpiar errores visuales
function limpiarErrores() {
  document.querySelectorAll('.error-message').forEach(el => el.textContent = '');
  document.querySelectorAll('input').forEach(el => {
    el.classList.remove('error', 'valid');
  });
  document.getElementById('resultado').innerHTML = '';
}

// Mostrar errores en el formulario
function mostrarErrores(errores) {
  errores.forEach(error => {
    const campo = error.instancePath.replace('/', '') || 
                  error.params.missingProperty;
    
    if (campo) {
      const input = document.getElementById(campo);
      const errorDiv = document.getElementById(`error-${campo}`);
      
      if (input && errorDiv) {
        input.classList.add('error');
        
        const mensajeFn = mensajesError[error.keyword];
        const mensaje = mensajeFn ? mensajeFn(error.params) : error.message;
        errorDiv.textContent = mensaje;
      }
    }
  });
}

// Marcar campos válidos
function marcarCamposValidos(datos) {
  Object.keys(datos).forEach(campo => {
    const input = document.getElementById(campo);
    if (input && !input.classList.contains('error')) {
      input.classList.add('valid');
    }
  });
}

// Manejar envío del formulario
document.getElementById('registroForm').addEventListener('submit', function(e) {
  e.preventDefault();
  
  limpiarErrores();
  
  // Recoger datos del formulario
  const formData = new FormData(this);
  const datos = {
    nombreUsuario: formData.get('nombreUsuario'),
    email: formData.get('email'),
    password: formData.get('password'),
    edad: parseInt(formData.get('edad')),
    telefono: formData.get('telefono'),
    aceptaTerminos: formData.get('aceptaTerminos') === 'on'
  };
  
  // Validar
  const valido = validate(datos);
  
  if (valido) {
    marcarCamposValidos(datos);
    document.getElementById('resultado').innerHTML = `
      <div class="success">
        <strong>✅ Registro exitoso</strong><br>
        Bienvenido, ${datos.nombreUsuario}!
      </div>
    `;
    
    // Aquí enviarías los datos al servidor
    console.log('Datos a enviar:', datos);
  } else {
    mostrarErrores(validate.errors);
    document.getElementById('resultado').innerHTML = `
      <div class="error-message">
        ❌ Por favor, corrige los errores en el formulario
      </div>
    `;
  }
});

// Validación en tiempo real (opcional)
document.querySelectorAll('input').forEach(input => {
  input.addEventListener('blur', function() {
    const formData = new FormData(document.getElementById('registroForm'));
    const datos = {
      nombreUsuario: formData.get('nombreUsuario'),
      email: formData.get('email'),
      password: formData.get('password'),
      edad: parseInt(formData.get('edad')) || undefined,
      telefono: formData.get('telefono'),
      aceptaTerminos: formData.get('aceptaTerminos') === 'on'
    };
    
    validate(datos);
    
    const campo = this.id;
    const errorDiv = document.getElementById(`error-${campo}`);
    
    // Buscar errores para este campo específico
    const erroresCampo = validate.errors?.filter(e => 
      e.instancePath === `/${campo}` || 
      e.params.missingProperty === campo
    ) || [];
    
    if (erroresCampo.length > 0) {
      this.classList.remove('valid');
      this.classList.add('error');
      const error = erroresCampo[0];
      const mensajeFn = mensajesError[error.keyword];
      errorDiv.textContent = mensajeFn ? mensajeFn(error.params) : error.message;
    } else if (this.value) {
      this.classList.remove('error');
      this.classList.add('valid');
      errorDiv.textContent = '';
    }
  });
});
```

Este ejemplo muestra:
- ✅ Validación al enviar el formulario
- ✅ Validación en tiempo real (al perder el foco)
- ✅ Mensajes de error personalizados en español
- ✅ Indicadores visuales (bordes rojos/verdes)
- ✅ Feedback inmediato al usuario

**Para probarlo:**
1. Guarda ambos archivos en la misma carpeta
2. Abre `index.html` en tu navegador
3. Prueba a introducir datos válidos e inválidos

## 5. Herramientas de validación JSON Schema

### 5.1. Validadores online

Los validadores online son útiles para probar schemas rápidamente sin necesidad de configurar nada. Son especialmente prácticos durante el aprendizaje o para validaciones puntuales.

#### JSONSchemaValidator.net

**URL:** https://www.jsonschemavalidator.net/

**Características:**
- Interfaz dividida: schema a la izquierda, datos a la derecha
- Validación en tiempo real
- Muestra errores claros y específicos
- Soporta todas las versiones de JSON Schema
- No requiere registro

**Cuándo usarlo:**
- ✅ Probar un schema rápidamente
- ✅ Verificar si un JSON cumple un schema
- ✅ Aprender cómo funcionan las validaciones
- ✅ Compartir ejemplos con compañeros (genera URLs)

**Ejemplo de uso:**
1. Pega tu schema en el panel izquierdo
2. Pega tus datos JSON en el panel derecho
3. Ve los resultados inmediatamente (verde = válido, rojo = errores)

#### JSON Schema Lint

**URL:** https://jsonschemalint.com/

**Características:**
- Similar a JSONSchemaValidator.net
- Interfaz más simple
- Permite seleccionar la versión del schema
- Mensajes de error detallados

**Otros validadores útiles:**
- **Hyperjump** (https://json-schema.hyperjump.io/) - Con explorador interactivo
- **JSON Schema Validator** de ExtendsClass (https://extendsclass.com/json-schema-validator.html)

#### Cuándo usar validadores online

✅ **Usar cuando:**
- Estás aprendiendo JSON Schema
- Necesitas validar algo rápido
- Quieres probar una idea antes de implementarla
- Necesitas compartir un ejemplo con alguien

❌ **No usar cuando:**
- Trabajas con datos sensibles o privados
- Necesitas validación automatizada
- Estás en producción (usar librerías como Ajv)

---

### 5.2. Generadores de schemas

En lugar de escribir schemas desde cero, podemos generarlos automáticamente a partir de datos JSON de ejemplo.

#### Herramientas recomendadas

**1. JSONSchema.net**

**URL:** https://www.jsonschema.net/

**Cómo funciona:**
1. Pegas un JSON de ejemplo
2. La herramienta genera el schema automáticamente
3. Puedes ajustar el schema generado

**Ejemplo:**

Datos de entrada:
```json
{
  "nombre": "Ana García",
  "edad": 25,
  "email": "ana@ejemplo.com"
}
```

Schema generado:
```json
{
  "$schema": "http://json-schema.org/draft-07/schema#",
  "type": "object",
  "properties": {
    "nombre": {
      "type": "string"
    },
    "edad": {
      "type": "integer"
    },
    "email": {
      "type": "string"
    }
  },
  "required": ["nombre", "edad", "email"]
}
```

Luego puedes añadir validaciones manualmente:
```json
{
  "$schema": "http://json-schema.org/draft-07/schema#",
  "type": "object",
  "properties": {
    "nombre": {
      "type": "string",
      "minLength": 2,
      "maxLength": 100
    },
    "edad": {
      "type": "integer",
      "minimum": 18,
      "maximum": 120
    },
    "email": {
      "type": "string",
      "format": "email"
    }
  },
  "required": ["nombre", "edad", "email"]
}
```

**2. Quicktype**

**URL:** https://quicktype.io/

Genera schemas y además código en múltiples lenguajes (TypeScript, Java, Python, etc.)

**3. Transform Tools**

**URL:** https://transform.tools/json-to-json-schema

Generador simple y rápido en el navegador.

#### Ventajas e inconvenientes

**✅ Ventajas:**
- Ahorra tiempo al comenzar
- Detecta automáticamente los tipos
- Útil para datos complejos con muchos campos
- Punto de partida rápido

**❌ Inconvenientes:**
- El schema generado es básico (solo tipos)
- No añade validaciones específicas (minLength, pattern, etc.)
- No puede inferir reglas de negocio
- Siempre necesita revisión y ajustes manuales

**Flujo de trabajo recomendado:**
1. Generar schema automáticamente desde JSON de ejemplo
2. Revisar el schema generado
3. Añadir validaciones específicas (minLength, pattern, format, etc.)
4. Añadir descripciones
5. Definir campos obligatorios según las necesidades
6. Probar con datos válidos e inválidos

---

### 5.3. Documentación de schemas

Un schema bien documentado es más fácil de mantener y usar.

#### Anotaciones: `title`, `description`, `examples`

JSON Schema permite añadir metadatos que no afectan a la validación pero documentan el schema:

```json
{
  "$schema": "https://json-schema.org/draft/2020-12/schema",
  "title": "Usuario",
  "description": "Representa un usuario del sistema de gestión",
  "type": "object",
  "properties": {
    "id": {
      "type": "integer",
      "description": "Identificador único del usuario",
      "examples": [1, 42, 1001]
    },
    "nombre": {
      "type": "string",
      "description": "Nombre completo del usuario",
      "minLength": 2,
      "maxLength": 100,
      "examples": ["Ana García", "Carlos López"]
    },
    "email": {
      "type": "string",
      "format": "email",
      "description": "Dirección de correo electrónico para comunicaciones",
      "examples": ["ana@ejemplo.com", "usuario@dominio.es"]
    },
    "rol": {
      "type": "string",
      "enum": ["admin", "usuario", "invitado"],
      "description": "Rol del usuario en el sistema",
      "default": "usuario"
    }
  },
  "required": ["id", "nombre", "email"],
  "examples": [
    {
      "id": 1,
      "nombre": "Ana García",
      "email": "ana@ejemplo.com",
      "rol": "admin"
    },
    {
      "id": 2,
      "nombre": "Carlos López",
      "email": "carlos@ejemplo.com",
      "rol": "usuario"
    }
  ]
}
```

**Anotaciones útiles:**
- **`title`**: Título corto del schema o propiedad
- **`description`**: Descripción detallada de qué representa
- **`examples`**: Ejemplos de valores válidos
- **`default`**: Valor por defecto si no se proporciona
- **`$comment`**: Comentarios internos (no se muestran al usuario)

#### Generar documentación legible

**JSON Schema for Humans**

Herramienta que genera documentación HTML desde schemas.

**Instalación:**
```bash
sudo apt install pipx
pipx ensurepath
pipx install json-schema-for-humans
```

**Uso:**
```bash
generate-schema-doc schema.json output.html
```

Genera una página HTML con:
- Tabla de todas las propiedades
- Tipos y restricciones
- Descripciones y ejemplos
- Jerarquía clara de objetos anidados

**Alternativa simple:** Muchos validadores online muestran la documentación del schema automáticamente cuando incluyes `title`, `description` y `examples`.

---

### 5.4. Ejercicio: Documentar un schema existente

Toma este schema sin documentar:

```json
{
  "$schema": "https://json-schema.org/draft/2020-12/schema",
  "type": "object",
  "properties": {
    "codigo": {
      "type": "string",
      "pattern": "^PRD-[0-9]{6}$"
    },
    "nombre": {
      "type": "string",
      "minLength": 3,
      "maxLength": 100
    },
    "precio": {
      "type": "number",
      "minimum": 0.01,
      "multipleOf": 0.01
    },
    "stock": {
      "type": "integer",
      "minimum": 0
    },
    "activo": {
      "type": "boolean"
    }
  },
  "required": ["codigo", "nombre", "precio"]
}
```

**Tarea:** Añade documentación completa con:
1. `title` y `description` general
2. `description` en cada propiedad
3. `examples` donde sea útil
4. Un ejemplo completo al final

**Solución:**

```json
{
  "$schema": "https://json-schema.org/draft/2020-12/schema",
  "title": "Producto",
  "description": "Schema para productos del catálogo de la tienda",
  "type": "object",
  "properties": {
    "codigo": {
      "type": "string",
      "pattern": "^PRD-[0-9]{6}$",
      "description": "Código único del producto (formato: PRD-XXXXXX)",
      "examples": ["PRD-000001", "PRD-123456"]
    },
    "nombre": {
      "type": "string",
      "minLength": 3,
      "maxLength": 100,
      "description": "Nombre comercial del producto",
      "examples": ["Teclado Mecánico RGB", "Ratón Gaming"]
    },
    "precio": {
      "type": "number",
      "minimum": 0.01,
      "multipleOf": 0.01,
      "description": "Precio de venta en euros (máximo 2 decimales)",
      "examples": [29.99, 149.50, 1299.00]
    },
    "stock": {
      "type": "integer",
      "minimum": 0,
      "description": "Cantidad disponible en almacén",
      "examples": [0, 25, 150],
      "default": 0
    },
    "activo": {
      "type": "boolean",
      "description": "Indica si el producto está activo para la venta",
      "default": true
    }
  },
  "required": ["codigo", "nombre", "precio"],
  "examples": [
    {
      "codigo": "PRD-000123",
      "nombre": "Teclado Mecánico RGB",
      "precio": 89.99,
      "stock": 25,
      "activo": true
    },
    {
      "codigo": "PRD-000456",
      "nombre": "Ratón Inalámbrico",
      "precio": 29.99,
      "stock": 0,
      "activo": false
    }
  ]
}
```

**Beneficios de un schema bien documentado:**
- Otros desarrolladores entienden su propósito rápidamente
- Los ejemplos ayudan a entender qué datos son válidos
- Herramientas de generación de documentación producen mejores resultados
- Facilita el mantenimiento futuro

## 6. XML Schema (XSD): Comparativa básica

### 6.1. ¿Qué es XML Schema?

**XML Schema (XSD - XML Schema Definition)** es el estándar para definir la estructura y validar documentos XML, igual que JSON Schema lo es para JSON.

**Características principales:**
- Usa sintaxis XML para definir schemas
- Valida la estructura de documentos XML
- Define tipos de datos, elementos y atributos
- Permite validación estricta de documentos

**Archivo XML de ejemplo (`usuario.xml`):**
```xml
<?xml version="1.0" encoding="UTF-8"?>
<usuario>
  <nombre>Ana García</nombre>
  <email>ana@ejemplo.com</email>
  <edad>25</edad>
  <activo>true</activo>
</usuario>
```

**Schema XSD correspondiente (`usuario.xsd`):**
```xml
<?xml version="1.0" encoding="UTF-8"?>
<xs:schema xmlns:xs="http://www.w3.org/2001/XMLSchema">
  
  <xs:element name="usuario">
    <xs:complexType>
      <xs:sequence>
        <xs:element name="nombre" type="xs:string"/>
        <xs:element name="email" type="xs:string"/>
        <xs:element name="edad" type="xs:integer"/>
        <xs:element name="activo" type="xs:boolean"/>
      </xs:sequence>
    </xs:complexType>
  </xs:element>
  
</xs:schema>
```

---

### 6.2. Comparativa JSON Schema vs XML Schema

#### Sintaxis y legibilidad

**JSON Schema (conciso y legible):**
```json
{
  "type": "object",
  "properties": {
    "nombre": { "type": "string" },
    "edad": { "type": "integer" }
  },
  "required": ["nombre"]
}
```

**XML Schema (verboso):**
```xml
<xs:element name="persona">
  <xs:complexType>
    <xs:sequence>
      <xs:element name="nombre" type="xs:string"/>
      <xs:element name="edad" type="xs:integer" minOccurs="0"/>
    </xs:sequence>
  </xs:complexType>
</xs:element>
```

**Comparación:**
- JSON Schema: ~5 líneas, fácil de leer
- XML Schema: ~9 líneas, más etiquetas y verbosidad

#### Validaciones básicas comparadas

**Validar longitud de texto:**

JSON Schema:
```json
{
  "type": "string",
  "minLength": 3,
  "maxLength": 50
}
```

XML Schema:
```xml
<xs:simpleType>
  <xs:restriction base="xs:string">
    <xs:minLength value="3"/>
    <xs:maxLength value="50"/>
  </xs:restriction>
</xs:simpleType>
```

**Validar rango numérico:**

JSON Schema:
```json
{
  "type": "integer",
  "minimum": 18,
  "maximum": 120
}
```

XML Schema:
```xml
<xs:simpleType>
  <xs:restriction base="xs:integer">
    <xs:minInclusive value="18"/>
    <xs:maxInclusive value="120"/>
  </xs:restriction>
</xs:simpleType>
```

**Validar valores enumerados:**

JSON Schema:
```json
{
  "type": "string",
  "enum": ["admin", "usuario", "invitado"]
}
```

XML Schema:
```xml
<xs:simpleType>
  <xs:restriction base="xs:string">
    <xs:enumeration value="admin"/>
    <xs:enumeration value="usuario"/>
    <xs:enumeration value="invitado"/>
  </xs:restriction>
</xs:simpleType>
```

#### Tabla comparativa general

| Aspecto | JSON Schema | XML Schema (XSD) |
|---------|-------------|------------------|
| **Sintaxis** | JSON (ligero) | XML (verboso) |
| **Legibilidad** | ⭐⭐⭐⭐⭐ Alta | ⭐⭐⭐ Media |
| **Curva de aprendizaje** | Fácil | Moderada |
| **Soporte navegadores** | Nativo en JavaScript | Requiere librerías |
| **Tamaño del schema** | Pequeño | Grande |
| **Uso actual** | ⭐⭐⭐⭐⭐ Muy extendido | ⭐⭐ En declive |
| **APIs modernas** | REST (estándar) | SOAP (legacy) |
| **Ecosistema** | Activo y creciente | Mantenimiento |
| **Validación** | Ajv, jsonschema | libxml, xerces |

---

### 6.3. Cuándo se sigue usando XML Schema

A pesar del dominio de JSON, XML Schema todavía se utiliza en contextos específicos:

#### 1. Servicios SOAP (legacy)

Muchos sistemas empresariales antiguos usan SOAP, que requiere XML:

```xml
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/">
  <soapenv:Body>
    <obtenerUsuario>
      <id>123</id>
    </obtenerUsuario>
  </soapenv:Body>
</soapenv:Envelope>
```

**Sectores que aún usan SOAP:**
- Banca y finanzas
- Seguros
- Grandes corporaciones con sistemas legacy
- Algunos servicios gubernamentales

#### 2. Documentos oficiales y legales

**Ejemplos:**
- Facturas electrónicas (Facturae en España)
- Documentos fiscales y tributarios
- Intercambio de datos con la administración pública
- Expedientes electrónicos

**Factura electrónica (simplificado):**
```xml
<?xml version="1.0" encoding="UTF-8"?>
<Factura xmlns="http://www.facturae.es/Facturae/2014/v3.2.1">
  <Cabecera>
    <NumeroFactura>2025-001</NumeroFactura>
    <FechaExpedicion>2025-01-04</FechaExpedicion>
  </Cabecera>
  <Emisor>
    <NIF>B12345678</NIF>
    <NombreRazon>Mi Empresa SL</NombreRazon>
  </Emisor>
  <!-- ... -->
</Factura>
```

Estos formatos están regulados por ley y requieren XSD específicos.

#### 3. Configuración de aplicaciones empresariales

Algunas aplicaciones Java o .NET utilizan XML para configuración:

```xml
<!-- web.xml en Java -->
<web-app xmlns="http://java.sun.com/xml/ns/javaee">
  <servlet>
    <servlet-name>dispatcher</servlet-name>
    <servlet-class>org.example.DispatcherServlet</servlet-class>
  </servlet>
</web-app>
```

#### 4. Estándares específicos de industria

- **RSS/Atom**: Feeds de noticias
- **SVG**: Gráficos vectoriales
- **XHTML**: Variante de HTML
- **Office Open XML**: Documentos de Office (.docx, .xlsx)

---

### 6.4. Ejemplo comparativo completo

Vamos a ver el mismo caso de uso en ambos formatos.

**Caso:** Validar datos de un producto

#### Versión JSON

**Datos (`producto.json`):**
```json
{
  "codigo": "PRD-001",
  "nombre": "Teclado Mecánico",
  "precio": 89.99,
  "stock": 25,
  "categorias": ["electronica", "gaming"]
}
```

**Schema (`producto.schema.json`):**
```json
{
  "$schema": "https://json-schema.org/draft/2020-12/schema",
  "type": "object",
  "properties": {
    "codigo": {
      "type": "string",
      "pattern": "^PRD-[0-9]{3}$"
    },
    "nombre": {
      "type": "string",
      "minLength": 3,
      "maxLength": 100
    },
    "precio": {
      "type": "number",
      "minimum": 0.01
    },
    "stock": {
      "type": "integer",
      "minimum": 0
    },
    "categorias": {
      "type": "array",
      "items": { "type": "string" },
      "minItems": 1
    }
  },
  "required": ["codigo", "nombre", "precio"]
}
```

#### Versión XML

**Datos (`producto.xml`):**
```xml
<?xml version="1.0" encoding="UTF-8"?>
<producto>
  <codigo>PRD-001</codigo>
  <nombre>Teclado Mecánico</nombre>
  <precio>89.99</precio>
  <stock>25</stock>
  <categorias>
    <categoria>electronica</categoria>
    <categoria>gaming</categoria>
  </categorias>
</producto>
```

**Schema (`producto.xsd`):**
```xml
<?xml version="1.0" encoding="UTF-8"?>
<xs:schema xmlns:xs="http://www.w3.org/2001/XMLSchema">
  
  <xs:element name="producto">
    <xs:complexType>
      <xs:sequence>
        <xs:element name="codigo">
          <xs:simpleType>
            <xs:restriction base="xs:string">
              <xs:pattern value="PRD-[0-9]{3}"/>
            </xs:restriction>
          </xs:simpleType>
        </xs:element>
        
        <xs:element name="nombre">
          <xs:simpleType>
            <xs:restriction base="xs:string">
              <xs:minLength value="3"/>
              <xs:maxLength value="100"/>
            </xs:restriction>
          </xs:simpleType>
        </xs:element>
        
        <xs:element name="precio">
          <xs:simpleType>
            <xs:restriction base="xs:decimal">
              <xs:minInclusive value="0.01"/>
            </xs:restriction>
          </xs:simpleType>
        </xs:element>
        
        <xs:element name="stock" type="xs:nonNegativeInteger" minOccurs="0"/>
        
        <xs:element name="categorias" minOccurs="0">
          <xs:complexType>
            <xs:sequence>
              <xs:element name="categoria" type="xs:string" maxOccurs="unbounded"/>
            </xs:sequence>
          </xs:complexType>
        </xs:element>
        
      </xs:sequence>
    </xs:complexType>
  </xs:element>
  
</xs:schema>
```

#### Análisis de la comparación

**Tamaño:**
- JSON Schema: ~20 líneas
- XML Schema: ~40 líneas (el doble)

**Complejidad:**
- JSON Schema: Directo, fácil de leer
- XML Schema: Muchas etiquetas anidadas, más difícil de seguir

**Expresividad:**
- Ambos pueden expresar las mismas validaciones
- JSON Schema lo hace de forma más concisa

---

### 6.5. Conclusión

**Usa JSON Schema cuando:**
- ✅ Desarrolles aplicaciones web modernas
- ✅ Trabajes con APIs REST
- ✅ Necesites validación rápida y ligera
- ✅ Valores la simplicidad y legibilidad
- ✅ Tu equipo trabaje principalmente con JavaScript

**Usa XML Schema cuando:**
- ✅ Mantengas sistemas legacy basados en SOAP
- ✅ Trabajes con documentos oficiales (facturas, tributación)
- ✅ Necesites cumplir estándares específicos en XML
- ✅ Integres con sistemas empresariales antiguos
- ✅ Debas usar formatos regulados por ley

**Tendencia del mercado:**
- JSON está dominando el desarrollo de nuevas aplicaciones
- XML se mantiene en sistemas legacy y documentos oficiales
- Es probable que trabajes principalmente con JSON Schema
- Conocer XML Schema es útil para mantenimiento de sistemas antiguos

## 7. Casos de uso reales y mejores prácticas

### 7.1. Dónde se usa JSON Schema

#### 1. Configuración de aplicaciones

JSON Schema valida archivos de configuración en proyectos reales.

**Ejemplos cotidianos:**

**`package.json` (Node.js):**
```json
{
  "name": "mi-aplicacion",
  "version": "1.0.0",
  "description": "Mi aplicación Node.js",
  "main": "index.js",
  "scripts": {
    "start": "node index.js",
    "test": "jest"
  },
  "dependencies": {
    "express": "^4.18.0"
  }
}
```

VS Code valida automáticamente este archivo usando el JSON Schema oficial de npm.

**`tsconfig.json` (TypeScript):**
```json
{
  "compilerOptions": {
    "target": "ES2020",
    "module": "commonjs",
    "strict": true,
    "esModuleInterop": true
  }
}
```

También validado automáticamente con su schema oficial.

**Configuración de tu app:**
```json
{
  "appName": "Mi App",
  "database": {
    "host": "localhost",
    "port": 5432,
    "name": "midb"
  },
  "features": {
    "enableNotifications": true,
    "maxUploadSize": 10485760
  }
}
```

Puedes crear un schema para validar que la configuración sea correcta antes de arrancar la app.

#### 2. Validación de ficheros de configuración

**`.eslintrc.json` (ESLint):**
```json
{
  "env": {
    "browser": true,
    "es2021": true
  },
  "extends": "eslint:recommended",
  "rules": {
    "indent": ["error", 2],
    "quotes": ["error", "single"]
  }
}
```

**`.prettierrc.json` (Prettier):**
```json
{
  "semi": true,
  "trailingComma": "all",
  "singleQuote": true,
  "printWidth": 80
}
```

Todas estas herramientas usan JSON Schema para validar su configuración.

#### 3. Intercambio de datos entre sistemas

**Ejemplo: Sistema de pedidos entre frontend y backend**

El frontend envía:
```json
{
  "clienteId": 123,
  "productos": [
    {"id": 1, "cantidad": 2},
    {"id": 5, "cantidad": 1}
  ],
  "direccionEnvio": {
    "calle": "Calle Mayor 15",
    "ciudad": "Valencia",
    "codigoPostal": "46001"
  }
}
```

El backend valida con JSON Schema antes de procesar el pedido, asegurando:
- Los IDs son números válidos
- Las cantidades son positivas
- La dirección está completa

#### 4. Preparación para APIs REST

En el próximo tema veremos APIs REST. JSON Schema es fundamental para:

**Validar peticiones (request):**
```javascript
// POST /api/usuarios
{
  "nombre": "Ana García",
  "email": "ana@ejemplo.com",
  "password": "MiPassword123"
}
```

El servidor valida estos datos con un schema antes de crear el usuario.

**Documentar respuestas (response):**
```javascript
// GET /api/usuarios/123
{
  "id": 123,
  "nombre": "Ana García",
  "email": "ana@ejemplo.com",
  "fechaRegistro": "2025-01-04T10:30:00Z"
}
```

El schema documenta qué estructura devolverá la API.

**Herramientas que usan JSON Schema para APIs:**
- **OpenAPI/Swagger**: Define APIs REST usando JSON Schema
- **Postman**: Valida respuestas con schemas
- **API Blueprint**: Documentación de APIs

---

### 7.2. Mejores prácticas

#### 1. Mantener schemas simples y legibles

❌ **Evitar:**
```json
{
  "type": "object",
  "properties": {
    "a": {"type": "string"},
    "b": {"type": "integer"},
    "c": {"type": "boolean"}
  }
}
```

✅ **Preferir:**
```json
{
  "$schema": "https://json-schema.org/draft/2020-12/schema",
  "title": "Usuario",
  "description": "Datos básicos de un usuario",
  "type": "object",
  "properties": {
    "nombre": {
      "type": "string",
      "description": "Nombre completo del usuario",
      "minLength": 2,
      "maxLength": 100
    },
    "edad": {
      "type": "integer",
      "description": "Edad del usuario",
      "minimum": 18
    },
    "activo": {
      "type": "boolean",
      "description": "Si el usuario está activo en el sistema"
    }
  },
  "required": ["nombre", "edad"]
}
```

**Claves:**
- Usa `title` y `description`
- Nombres de propiedades descriptivos
- Incluye validaciones relevantes
- Documenta cada campo

#### 2. Reutilización mediante definiciones

Cuando varios schemas comparten estructuras, usa `$defs` (definiciones):

```json
{
  "$schema": "https://json-schema.org/draft/2020-12/schema",
  "title": "Pedido",
  "$defs": {
    "direccion": {
      "type": "object",
      "properties": {
        "calle": {"type": "string"},
        "ciudad": {"type": "string"},
        "codigoPostal": {"type": "string", "pattern": "^[0-9]{5}$"}
      },
      "required": ["calle", "ciudad", "codigoPostal"]
    }
  },
  "type": "object",
  "properties": {
    "direccionEnvio": {
      "$ref": "#/$defs/direccion"
    },
    "direccionFacturacion": {
      "$ref": "#/$defs/direccion"
    }
  }
}
```

Esto evita duplicación y facilita el mantenimiento.

#### 3. Versionado de schemas

Cuando un schema evoluciona, versiona para mantener compatibilidad:

**Versión 1:**
```json
{
  "$id": "https://miapp.com/schemas/v1/usuario.schema.json",
  "properties": {
    "nombre": {"type": "string"},
    "email": {"type": "string"}
  }
}
```

**Versión 2 (añade campo):**
```json
{
  "$id": "https://miapp.com/schemas/v2/usuario.schema.json",
  "properties": {
    "nombre": {"type": "string"},
    "email": {"type": "string"},
    "telefono": {"type": "string"}
  }
}
```

Los sistemas antiguos pueden seguir usando v1, los nuevos usan v2.

#### 4. Testing de schemas

Los schemas también necesitan tests para verificar que funcionan correctamente.

**Ejemplo de test (con Jest):**

```javascript
const Ajv = require('ajv');
const schema = require('./schemas/usuario.schema.json');

const ajv = new Ajv();
const validate = ajv.compile(schema);

describe('Schema de Usuario', () => {
  test('acepta usuario válido', () => {
    const usuario = {
      nombre: "Ana García",
      email: "ana@ejemplo.com",
      edad: 25
    };
    expect(validate(usuario)).toBe(true);
  });

  test('rechaza email inválido', () => {
    const usuario = {
      nombre: "Carlos López",
      email: "no-es-email",
      edad: 30
    };
    expect(validate(usuario)).toBe(false);
    expect(validate.errors[0].instancePath).toBe('/email');
  });

  test('requiere campos obligatorios', () => {
    const usuario = {
      nombre: "Pedro Ruiz"
      // Falta email
    };
    expect(validate(usuario)).toBe(false);
  });
});
```

**Beneficios:**
- Detecta errores en el schema antes de usarlo en producción
- Documenta qué casos debe validar
- Facilita refactorización

#### 5. Estructura de proyecto recomendada

```
mi-proyecto/
├── src/
│   ├── index.js
│   ├── routes/
│   └── controllers/
├── schemas/
│   ├── common/
│   │   ├── email.schema.json
│   │   └── telefono.schema.json
│   ├── usuario.schema.json
│   ├── producto.schema.json
│   └── pedido.schema.json
├── tests/
│   └── schemas/
│       ├── usuario.test.js
│       └── producto.test.js
├── .vscode/
│   └── settings.json
└── package.json
```

**Ventajas:**
- Schemas centralizados en un directorio
- Fácil localización y mantenimiento
- Tests específicos para schemas
- Configuración de VS Code para el proyecto

#### 6. Validar siempre en el servidor

⚠️ **Importante:** Aunque valides en el frontend (navegador), **siempre valida en el backend**.

**¿Por qué?**
- El usuario puede desactivar JavaScript
- Puede manipular el código del navegador
- Las peticiones pueden venir de otras fuentes (Postman, curl, otros servicios)

**Flujo correcto:**
```
1. Usuario rellena formulario
2. Frontend valida (UX, feedback inmediato)
3. Usuario envía datos
4. Backend valida (SEGURIDAD)
5. Si válido → procesa
   Si inválido → rechaza con error 400
```

#### 7. Mensajes de error claros

Convierte errores técnicos en mensajes amigables:

❌ **Error técnico:**
```
"must match pattern '^[0-9]{5}$'"
```

✅ **Mensaje amigable:**
```
"El código postal debe tener exactamente 5 dígitos"
```

Ya vimos cómo hacer esto en el punto 4.3 con la función `obtenerMensajeAmigable()`.

---

### 7.3. Checklist de mejores prácticas

Usa esta lista para revisar tus schemas:

**Documentación:**
- [ ] Incluye `$schema` con la versión correcta
- [ ] Define `$id` único
- [ ] Añade `title` y `description` generales
- [ ] Documenta cada propiedad con `description`
- [ ] Incluye `examples` donde sea útil

**Validaciones:**
- [ ] Define tipos correctamente (`type`)
- [ ] Especifica campos obligatorios (`required`)
- [ ] Añade restricciones apropiadas (min, max, pattern)
- [ ] Usa `additionalProperties: false` si corresponde
- [ ] Valida formatos con `format` cuando sea posible

**Estructura:**
- [ ] Usa nombres descriptivos en inglés o español consistente
- [ ] Reutiliza definiciones con `$defs`
- [ ] Versiona schemas cuando evolucionen
- [ ] Organiza schemas en carpeta dedicada

**Testing:**
- [ ] Crea tests para casos válidos
- [ ] Crea tests para casos inválidos
- [ ] Verifica campos obligatorios
- [ ] Prueba validaciones límite

**Implementación:**
- [ ] Valida en frontend (UX)
- [ ] Valida SIEMPRE en backend (seguridad)
- [ ] Convierte errores a mensajes amigables
- [ ] Maneja errores de validación apropiadamente

---

### 7.4. Preparación para APIs REST

En el próximo tema trabajaremos con APIs REST. JSON Schema será fundamental para:

**1. Definir contratos de API:**
Especificar exactamente qué datos acepta y devuelve cada endpoint.

**2. Generar documentación automática:**
Herramientas como Swagger generan documentación desde schemas.

**3. Validación automática:**
Frameworks como Express pueden validar peticiones automáticamente.

**Adelanto - Endpoint de API con validación:**

```javascript
const express = require('express');
const Ajv = require('ajv');

const app = express();
const ajv = new Ajv();

const crearUsuarioSchema = {
  type: "object",
  properties: {
    nombre: { type: "string", minLength: 2 },
    email: { type: "string", format: "email" }
  },
  required: ["nombre", "email"]
};

const validate = ajv.compile(crearUsuarioSchema);

app.post('/api/usuarios', (req, res) => {
  if (!validate(req.body)) {
    return res.status(400).json({
      error: "Datos inválidos",
      detalles: validate.errors
    });
  }
  
  // Datos válidos, crear usuario...
  res.status(201).json({ id: 123, ...req.body });
});
```
