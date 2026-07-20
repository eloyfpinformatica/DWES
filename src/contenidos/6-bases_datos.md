# Unidad 6: Almacenamiento y formatos de intercambio de datos

## Resultado de aprendizaje

**RA6: Gestiona la información en formatos de intercambio de datos analizando y utilizando tecnologías de almacenamiento y lenguajes de consulta.**

## Criterios de Evaluación

Para superar esta unidad, evaluaremos si:

- **a)** Se han identificado los principales métodos de almacenamiento de la información.
- **b)** Se han identificado las ventajas e inconvenientes de almacenar información en formatos de intercambio de datos.
- **c)** Se han establecido tecnologías eficientes de almacenamiento en función de sus características.
- **d)** Se han identificado lenguajes y herramientas para el tratamiento y almacenamiento de información.
- **e)** Se han utilizado lenguajes de consulta y manipulación.
- **f)** Se han utilizado sistemas gestores de bases de datos relacionales (SGBDR).
- **g)** Se han utilizado técnicas para crear documentos de intercambio (JSON) a partir de bases de datos relacionales.
- **h)** Se han identificado las características de los sistemas gestores de bases de datos nativas (NoSQL).
- **i)** Se han utilizado herramientas para gestionar la información en bases de datos nativas.

## Temporalización y estructura (6 horas)

| **Sesión**   | **Título**                                                   | **Duración** | **Criterios** |
| ------------ | ------------------------------------------------------------ | ------------ | ------------- |
| **Sesión 1** | El problema del fichero plano y la necesidad de las BBDD     | 1h           | a, b, c       |
| **Sesión 2** | SQLite y DB Browser: Nuestra primera base de datos profesional | 1h           | d, f          |
| **Sesión 3** | El núcleo: SQL Avanzado y Funciones JSON                     | 2h           | e, g          |
| **Sesión 4** | Bases de Datos Documentales: El mundo de MongoDB Atlas       | 1h           | h, i          |
| **Sesión 5** | Proyecto Final: GameVault                                    | 1h           | Todos         |

## Índice de contenidos

1. **Introducción: El salto al mundo profesional**
   - De `db.json` a un Sistema Gestor de Base de Datos.
   - ¿Por qué JSON-Server ya no es suficiente?
2. **Bases de Datos Relacionales (SQLite)**
   - Instalación y uso de DB Browser for SQLite.
   - Creación de tablas y tipos de datos para videojuegos.
3. **Consultas y Extracción de Datos**
   - Repaso de SQL: Filtrando nuestro catálogo.
   - Generación de JSON desde SQL: `json_object` y `json_group_array`.
   - Importación y exportación de datos.
4. **Bases de Datos NoSQL (MongoDB)**
   - Concepto de base de datos documental.
   - Uso de MongoDB Atlas (Cloud).
   - Gestión de documentos JSON en la nube.
5. **Proyecto Final: GameVault**
   - Integración de SQLite y MongoDB.


> 💡 **Nota para el alumno:** A lo largo de esta unidad, recordad que aunque cambie la forma de guardar los datos, vuestro conocimiento de **JSON** y **fetch** sigue siendo la pieza clave. Solo vamos a cambiar el "almacén" para que vuestras aplicaciones sean mucho más potentes.

## 1. El problema del fichero plano 

### 1.1. De "juguetes" a herramientas profesionales

Hasta ahora habéis usado **JSON-Server** como si fuera un servidor real. En vuestros proyectos de videojuegos, simplemente hacíais un `fetch` a un puerto y los datos aparecían. Pero la realidad es que JSON-Server es solo un simulador que lee y escribe en un fichero llamado `db.json`.

En el mundo real, los datos no "viven" en un fichero de texto. Se guardan en **Sistemas Gestores de Bases de Datos (SGBD)**.

| **ANTES**                             | **AHORA (Lo que viene)**                               |
| ------------------------------------- | ------------------------------------------------------ |
| `fetch` → **JSON-Server** → `db.json` | `fetch` → **Servidor (Node/Java)** → **Base de Datos** |

> 💡 **Nota:** Fíjate que el `fetch` y el objeto JSON que recibes en JavaScript **no van a cambiar**. Lo que cambia es "la cocina": dónde y cómo se cocinan esos datos antes de enviarlos.

### 1.2. El problema de los ficheros planos (`db.json`)

Un fichero `db.json` es lo que llamamos un **fichero plano**. Imagina que tu aplicación de videojuegos tiene éxito y pasan tres cosas:

1. **El problema de la Concurrencia:** Dos jugadores intentan actualizar su puntuación a la vez. El sistema abre el fichero `db.json`, uno escribe, el otro escribe... y ¡PUM!, el fichero se corrompe o uno de los dos borra los datos del otro.
2. **El problema del Rendimiento:** Tienes 50.000 videojuegos en tu lista. Para buscar "Zelda", el ordenador tiene que leer el fichero desde la primera línea hasta encontrarlo. Es como buscar un nombre en un libro de 1.000 páginas leyendo cada palabra.
3. **El problema de la Integridad:** Si alguien abre el fichero y borra una coma `,` o una llave `}` sin querer, **toda la aplicación deja de funcionar**.

### 1.3. Ventajas e inconvenientes: ¿Cuándo usar cada uno?

No siempre vamos a usar una base de datos gigante. Hay que saber elegir.

#### **Formato de intercambio (JSON)**

- **Ventaja:** Es el lenguaje universal de la web. JavaScript lo entiende de forma nativa.
- **Inconveniente:** No sirve para gestionar grandes volúmenes de datos ni relaciones complejas de forma segura.

#### **Base de Datos (Relacional - SQL)**

- **Ventaja:** Extremadamente rápida (usa índices, como el índice de un libro), segura y permite que miles de usuarios entren a la vez.
- **Inconveniente:** Es más compleja de configurar y requiere aprender un lenguaje nuevo (SQL).

### 1.4. ¿Cómo elegimos la tecnología adecuada?

Para vuestro proyecto de videojuegos, vamos a ver dos tipos de tecnologías que dominan el mercado actual:

1. **SQLite:** Es una base de datos relacional. Todo se guarda en un archivo, pero ese archivo está optimizado para que el acceso sea instantáneo. Es perfecta para aplicaciones móviles o proyectos pequeños/medianos.
2. **MongoDB:** Es una base de datos "NoSQL". En lugar de tablas, guarda directamente "documentos" que se parecen muchísimo a vuestros objetos JSON. Es ideal cuando los datos cambian mucho o no tienen una estructura fija.

> ⚠️ **Cuidado:** Un error común es pensar que por usar una Base de Datos ya no necesitamos JSON. ¡Al revés! La base de datos **guarda** los videojuegos y el servidor los **convierte a JSON** para que tu código de JavaScript los pueda pintar en la web.

### Comparativa visual

Imagina que queremos guardar este videojuego:

**En `db.json` (Fichero plano):**

JSON

```
{
  "id": 1,
  "titulo": "Elden Ring",
  "genero": "Accion",
  "precio": 59.99
}
```

*Si el fichero mide 100MB, buscar este objeto es lento.*

**En Base de Datos (SQLite):**

| **id** | **titulo** | **genero** | **precio** |
| ------ | ---------- | ---------- | ---------- |
| 1      | Elden Ring | Accion     | 59.99      |

*Aunque la base de datos mida 10GB, encontrar el `id: 1` es casi instantáneo gracias a que el sistema sabe exactamente en qué "página" está.*


## 2. SQLite y DB Browser: crear la BD


### 2.1. La herramienta: ¿Por qué SQLite?

Para gestionar datos de forma profesional necesitamos un **SGBDR** (Sistema Gestor de Bases de Datos Relacionales). A diferencia de Oracle o MySQL, que requieren instalar servidores complejos, **SQLite** guarda toda la base de datos en un único archivo (con extensión `.db` o `.sqlite`).

Es la base de datos más usada del mundo: está en tu móvil Android/iPhone, en tu navegador Chrome y hasta en los coches.

Para ver y tocar esos datos, usaremos **DB Browser for SQLite**. Es como el "Explorador de Archivos" pero para bases de datos.

### 2.2. Tipos de datos en SQLite

Antes de crear nuestra tabla de videojuegos, debemos entender que las bases de datos son muy "especiales" con el tipo de información que guardan. No vale todo.

| **Tipo en SQLite** | **¿Qué guarda?**                     | **Equivalente en JavaScript/JSON** |
| ------------------ | ------------------------------------ | ---------------------------------- |
| **INTEGER**        | Números enteros (sin decimales).     | `Number` (p. ej. 2024)             |
| **TEXT**           | Cadenas de texto.                    | `String` (p. ej. "Zelda")          |
| **REAL**           | Números con decimales.               | `Number` (p. ej. 59.99)            |
| **BLOB**           | Datos binarios (imágenes, archivos). | *No lo usaremos de momento*        |

> ⚠️ **Cuidado:** A diferencia de JavaScript, donde una variable puede ser un número y luego un texto, en una base de datos, si dices que la columna `precio` es **REAL**, no podrás meterle el texto "Gratis". Esto nos ayuda a evitar errores en nuestros datos.

### 2.3. Paso a paso: Creando "GameVault"

Vamos a crear nuestra infraestructura. Sigue estos pasos en DB Browser:

1. **Crear el archivo:** Haz clic en **"Nueva base de datos"** y guárdala como `gamevault.db` en tu carpeta de proyecto.
2. **Definir la tabla:** Se abrirá una ventana para crear una tabla. Llámala `videojuegos`.
3. **Añadir los campos (columnas):**
   - **id**: Tipo `INTEGER`. Marca las casillas **PK** (Primary Key) y **AI** (Autoincrement). Esto hará que cada juego tenga un número único automáticamente (1, 2, 3...).
   - **titulo**: Tipo `TEXT`.
   - **empresa**: Tipo `TEXT`.
   - **anio**: Tipo `INTEGER`.
   - **precio**: Tipo `REAL`.

#### Código SQL que genera la herramienta (automático):

Aunque lo hagas con clics, DB Browser está escribiendo este código por ti:

SQL

```
CREATE TABLE "videojuegos" (
	"id"	INTEGER NOT NULL,
	"titulo"	TEXT NOT NULL,
	"empresa"	TEXT,
	"anio"	INTEGER,
	"precio"	REAL,
	PRIMARY KEY("id" AUTOINCREMENT)
);
```

### 2.4. Insertando nuestros primeros datos

Ahora que tenemos la estructura (el "molde"), vamos a meter contenido. En DB Browser, ve a la pestaña **"Hoja de datos"**.

1. Haz clic en el icono de **"Añadir registro"** (un icono con un destello amarillo).
2. Rellena los campos. El `id` déjalo vacío, se pondrá solo.
3. Escribe: `Elden Ring`, `FromSoftware`, `2022`, `59.99`.
4. **¡IMPORTANTE!:** Para que los cambios se guarden en el archivo, debes dar al botón **"Escribir cambios"** en la barra superior. Si cierras sin darle, perderás el trabajo.

> 💡 **Nota:** Fíjate que esto es idéntico a lo que hacías en el `db.json`, pero aquí el programa te obliga a respetar los tipos de datos y no te deja dejar el `id` vacío.

### 2.5. Resumen de la sesión

- Hemos pasado de un fichero de texto (`.json`) a un fichero de base de datos (`.db`).
- Hemos usado **DB Browser** como interfaz profesional.
- Hemos definido **tipos de datos** para evitar errores humanos.

**Comparativa final:**

- **ANTES:** Abrías el Bloc de Notas y escribías `{ "titulo": "..." }`.
- **AHORA:** Usas una herramienta que valida que el año sea un número y el precio tenga decimales

Esta es la sesión central de la unidad. Aquí es donde ocurre la "magia": vamos a aprender a pedirle datos a la base de datos y, lo más importante, a **obligar a la base de datos a que nos responda en JSON**.

De esta forma, vuestro código de JavaScript (`fetch`) no notará la diferencia entre el antiguo `db.json` y la nueva base de datos profesional.

## 3. Consultas SQL y salida en JSON

### 3.1. Repaso: Hablando con la base de datos (SQL)

Antes de convertir a JSON, necesitamos recordar cómo filtrar datos. Imaginad que nuestra tabla `videojuegos` ya tiene 20 o 30 filas.

**Ejemplo 1: Filtrar por año y ordenar**

Queremos los juegos de 2023, ordenados del más barato al más caro.

SQL

```
SELECT titulo, precio 
FROM videojuegos 
WHERE anio = 2023 
ORDER BY precio ASC;
```

**Ejemplo 2: Búsqueda flexible (LIKE)**

Queremos buscar cualquier juego que contenga la palabra "Mario".

SQL

```
SELECT * FROM videojuegos 
WHERE titulo LIKE '%Mario%';
```

> 💡 **Nota:** El símbolo `%` es un comodín. `%Mario%` significa "cualquier cosa que tenga Mario en medio, al principio o al final".

### 3.2. Transformando SQL a JSON

Aquí es donde conectamos este módulo con lo que ya sabéis. SQLite tiene funciones que crean el JSON por nosotros.

#### A. La función `json_object()`

Esta función crea un **objeto** `{ }`. Debemos pasarle parejas de "Clave", Valor.

SQL

```
SELECT json_object(
  'id', id,
  'nombre_juego', titulo,
  'precio_venta', precio
) 
FROM videojuegos;
```

**Resultado en la tabla:**

| **json_object(...)**                                        |
| ----------------------------------------------------------- |
| `{"id":1,"nombre_juego":"Elden Ring","precio_venta":59.99}` |
| `{"id":2,"nombre_juego":"Portal 2","precio_venta":9.99}`    |

#### B. La función `json_group_array()`

Vuestro JavaScript normalmente espera un **Array** `[ ]` de objetos. Esta función "empaqueta" todas las filas en una sola lista.

SQL

```
SELECT json_group_array(
  json_object(
    'id', id,
    'titulo', titulo
  )
)
FROM videojuegos;
```

**Resultado:**

```
[{"id":1,"titulo":"Elden Ring"},{"id":2,"titulo":"Portal 2"}]
```

> ⚠️ **Cuidado:** `json_group_array` es una función de agregado. Si la usáis, veréis que todas las filas de la tabla se comprimen en una sola celda que contiene el array completo. ¡Es justo lo que necesita un `fetch`!

#### C. La función `json_extract()`

A veces, dentro de una tabla de SQLite, guardamos un texto que ya es un JSON. Con `json_extract` podemos sacar solo una parte.

*Ejemplo: Si en la columna `detalles` guardamos `{"color": "rojo", "stock": 5}`, podemos hacer:*

SQL

```
SELECT json_extract(detalles, '$.stock') FROM videojuegos;
```



### 3.3. Importar datos de JSON-Server a SQLite

Si ya tenéis un archivo `db.json` de vuestros proyectos anteriores, no tenéis que escribirlo todo de nuevo.

**Pasos en DB Browser:**

1. En vuestro editor de código (VS Code), abrid el `db.json`.
2. Como DB Browser no importa JSON directamente de forma fácil, lo mejor es **convertirlo a CSV** (puedes usar un conversor online "JSON to CSV").
3. En DB Browser: **Archivo -> Importar -> Tabla desde archivo CSV...**
4. Seleccionáis vuestro archivo y ¡listo!, ya tenéis vuestra tabla creada con todos vuestros juegos.

### 3.4. Exportar el resultado a JSON

Una vez que hayáis hecho una consulta (Query) en la pestaña "Ejecutar SQL", podéis guardar ese resultado para usarlo en vuestro frontend:

1. Haced la consulta (por ejemplo, la del `json_group_array`).
2. Haced clic en el botón pequeño de la derecha que parece un disquete o pulsad botón derecho sobre el resultado.
3. Elegid **"Exportar a JSON"**.

------

### Ejemplo Completo Paso a Paso

Imagina que el jefe te pide: *"Dame un fichero JSON con los juegos de 'Nintendo' ordenados por año"*.

**Paso 1: Escribimos la consulta SQL normal**

SQL

```
SELECT * FROM videojuegos WHERE empresa = 'Nintendo' ORDER BY anio DESC;
```

**Paso 2: La envolvemos en funciones JSON**

SQL

```
SELECT json_group_array(
  json_object(
    'title', titulo,
    'year', anio
  )
)
FROM videojuegos 
WHERE empresa = 'Nintendo' 
ORDER BY anio DESC;
```

**Paso 3: Exportamos**

Guardamos el resultado como `nintendo_games.json`. ¡Ya tenemos el fichero listo para nuestra web!

¡Damos un giro de 180 grados! Hasta ahora hemos visto bases de datos que parecen hojas de Excel (filas y columnas). Pero, ¿qué pasa si os digo que existe una forma de guardar los datos **exactamente igual** a como los escribís en vuestro código JavaScript?

Bienvenidos al mundo de las bases de datos **NoSQL**.

## 4. Bases de datos documentales: MongoDB Atlas

### 4.1. ¿Qué es una base de datos documental?

Imagina que en tu catálogo de videojuegos tienes un "Simulador de Granja" y un "Shooter Espacial". El de granja tiene un campo llamado `numero_de_cultivos`, pero el shooter tiene `calibre_de_armas`.

En **SQLite (Relacional)**, estarías obligado a tener ambas columnas en la tabla, y muchas filas estarían vacías (`NULL`).

En **MongoDB (Documental)**, cada videojuego es un "documento" independiente. Si un juego necesita un campo extra, se lo pones y listo. No molestas a los demás.

#### Comparativa: Relacional vs. Documental

| **Concepto**        | **SQLite (Relacional)** | **MongoDB (Documental)**         |
| ------------------- | ----------------------- | -------------------------------- |
| **Donde se guarda** | Tabla                   | **Colección**                    |
| **Cada registro**   | Fila (Row)              | **Documento**                    |
| **Formato**         | Rígido (Columnas fijas) | **Flexible (JSON)**              |
| **Lenguaje**        | SQL                     | **MQL (MongoDB Query Language)** |

### 4.2. ¿Por qué usar MongoDB?

Se utiliza en aplicaciones donde los datos cambian rápido o no sabemos exactamente qué forma tendrán en el futuro.

- **Velocidad de desarrollo:** Guardas el objeto JSON tal cual sale de tu formulario web.
- **Escalabilidad:** Está pensada para vivir en la nube y crecer hasta manejar millones de datos fácilmente.

### 4.3. MongoDB Atlas: Tu base de datos en la nube

No vamos a instalar nada. Vamos a usar **MongoDB Atlas**, que es la versión "Cloud". Es como tener un servidor profesional gratis para vuestros proyectos.

**Paso 1: Crear la cuenta y el Cluster**

1. Entra en [mongodb.com/atlas](https://www.google.com/search?q=https://www.mongodb.com/cloud/atlas/lp/general/try) y regístrate gratis.
2. Crea un nuevo **Cluster** (elige el plan "M0", que es el gratuito).
3. Selecciona un proveedor (como AWS) y una región cercana (Frankfurt o Irlanda).

**Paso 2: Crear la base de datos y la colección**

1. Una vez creado el cluster, ve a **"Browse Collections"**.
2. Pulsa en **"Add My Own Data"**.
3. **Database Name:** `TiendaVideojuegos`
4. **Collection Name:** `Catalogo`


### 4.4. Insertar y Consultar desde la interfaz

Aquí no usaremos código, solo la interfaz visual de Atlas.

#### A. Insertar datos

Pulsa en **"Insert Document"**. Verás que puedes escribir directamente en formato JSON. Prueba a pegar esto:

JSON

```
{
  "titulo": "Cyberpunk 2077",
  "desarrollador": "CD Projekt Red",
  "tags": ["Mundo Abierto", "RPG", "Sci-Fi"],
  "lanzamiento": 2020,
  "metacritic": 86
}
```

> 💡 **Nota:** Fíjate que el campo `tags` es un **Array**. En SQLite esto sería muy difícil de hacer, ¡aquí es natural!

#### B. Filtrar (Consultar)

En la parte superior verás una barra que dice **"Filter"**. Para buscar, tienes que usar el formato de llaves `{ }`.

- **Para buscar por título:** `{ "titulo": "Cyberpunk 2077" }`
- **Para buscar juegos modernos:** `{ "lanzamiento": { "$gt": 2021 } }` (Ese `$gt` significa "Greater Than", es decir, mayor que).

> ⚠️ **Cuidado:** MongoDB diferencia entre mayúsculas y minúsculas. Si buscas `{ "titulo": "cyberpunk" }` (en minúscula), ¡no encontrará nada!

### 4.5. Resumen de la sesión

- Hemos aprendido que **MongoDB** no usa tablas, sino **colecciones de documentos JSON**.
- Es mucho más **flexible** que SQL para datos que cambian.
- Hemos configurado un servidor real en la **nube (Atlas)** y hemos insertado datos manualmente



## Proyecto Final: "GameVault"

**Misión:** Construir un sistema de almacenamiento robusto que permita gestionar videojuegos, clasificarlos por géneros y preparar los datos para ser consumidos por una aplicación web mediante JSON.

### Fase 1: La Base de Datos Relacional (SQLite)

Tu primera tarea es crear la estructura en **DB Browser for SQLite**. Para que los datos no estén "sueltos", vamos a usar dos tablas relacionadas.

1. Crea una base de datos llamada `GameVault.db`.
2. **Tabla `generos`**: Crea esta tabla con los campos `id` (PK, Autoincrement) y `nombre_genero` (TEXT).
3. **Tabla `videojuegos`**: Crea esta tabla con:
   - `id` (PK, Autoincrement).
   - `titulo` (TEXT).
   - `anio` (INTEGER).
   - `id_genero` (INTEGER).
4. **Inserción de datos**:
   - Introduce al menos **3 géneros** (ej: RPG, Acción, Aventuras).
   - Introduce al menos **10 videojuegos**. Asegúrate de que en la columna `id_genero` pones el número del género que le corresponde.

> 💡 **Nota:** Por ejemplo, si el género "RPG" tiene el `id: 1`, todos los juegos que sean RPG deberán tener un `1` en su columna `id_genero`. Esto es lo que llamamos una **Clave Foránea (FK)**.

### Fase 2: Consultas y Exportación JSON

Ahora que tienes los datos, vamos a extraerlos de forma que tu Frontend (JavaScript) los entienda. Debes realizar las siguientes consultas en la pestaña "Ejecutar SQL" y **exportar cada resultado como un fichero `.json` independiente**:

1. **Consulta Global:** Todos los juegos ordenados por título. (Archivo: `todos_los_juegos.json`)
2. **Filtro por Género:** Selecciona solo los juegos cuyo `id_genero` sea el de tu género favorito. (Archivo: `juegos_filtrados.json`)
3. **Búsqueda:** Usa `LIKE` para encontrar juegos que contengan una palabra específica (ej: "Zelda" o "Duty"). (Archivo: `busqueda.json`)
4. **La Súper Consulta JSON:** Usa `json_group_array` y `json_object` para generar un array de objetos que contenga el `titulo` y el `anio` de todos los juegos. (Archivo: `formato_profesional.json`)

### Fase 3: El Almacén en la Nube (MongoDB Atlas)

Para demostrar que sabes manejar tecnologías modernas **NoSQL**, vamos a subir parte de tu catálogo a la nube.

1. En tu cluster de **MongoDB Atlas**, crea una base de datos llamada `VaultCloud` y una colección llamada `Favoritos`.
2. Inserta manualmente **5 videojuegos** de tu lista.
   - **Reto:** Al menos dos de ellos deben tener un campo extra que no exista en los demás (ej: `"dlc": true` o `"plataformas": ["PC", "PS5"]`). ¡Aprovecha la flexibilidad de JSON!
3. Realiza una consulta desde la interfaz de Atlas para filtrar juegos por un año concreto y haz una **captura de pantalla**.

### Qué debes entregar

Debes comprimir en un `.zip` los siguientes archivos:

1. `GameVault.db` (El archivo de base de datos de SQLite).
2. Los **4 archivos .json** exportados en la Fase 2.
3. Un documento (PDF o Markdown) con la **captura de pantalla** de tu consulta en MongoDB Atlas y una breve explicación de por qué es más flexible que SQLite (Criterios a, b, c).

### Rúbrica de Evaluación (Total: 10 puntos)

| **Apartado**          | **Indicador de logro**                                       | **Puntos** |
| --------------------- | ------------------------------------------------------------ | ---------- |
| **Diseño DB**         | Tablas creadas con tipos correctos y relación `id_genero` funcional. | 2 pts      |
| **SQL Básico**        | Realiza correctamente los filtros, ordenación y el uso de `LIKE`. | 2 pts      |
| **Funciones JSON**    | Uso correcto de `json_object` y `json_group_array` en la consulta 4. | 2 pts      |
| **MongoDB Atlas**     | Colección creada en la nube con documentos flexibles y filtros aplicados. | 2 pts      |
| **Conceptos (a,b,c)** | Identifica correctamente ventajas/inconvenientes de cada sistema en la entrega. | 1 pts      |
| **Calidad Datos**     | Los datos son coherentes, sin errores de sintaxis y bien organizados. | 1 pts      |

