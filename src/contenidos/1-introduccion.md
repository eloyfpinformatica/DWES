Unidad 1. Introducción a los lenguajes de marcas

# Unidad Didáctica 1. Introducción a los lenguajes de marcas

**Duració**n: 4 sesiones de 1 hora  
**Resultado de aprendizaje**:  
RA1. Reconoce las características de lenguajes de marcas analizando e interpretando fragmentos de código.

**Criterios de evaluación asociados**:

a) Se han identificado las características generales de los lenguajes de marcas.

b) Se han reconocido las ventajas que proporcionan en el tratamiento de la información.

c) Se han clasificado los lenguajes de marcas e identificado los más relevantes.

d) Se han diferenciado sus ámbitos de aplicación.

e) Se han reconocido la necesidad y los ámbitos específicos de aplicación de un lenguaje de marcas de propósito general.

f) Se han analizado las características propias de diferentes lenguajes de marcas.

g) Se ha identificado la estructura de un documento y sus reglas sintácticas.

h) Se ha contrastado la necesidad de crear documentos bien formados y la influencia en su procesamiento.

i) Se han identificado las ventajas que aportan los espacios de nombres.

---

## Índice

1. ¿Qué es un lenguaje de marcas?
2. Clasificación
3. Estructura y sintaxis: HTML, JSON y XML
4. El propósito de cada lenguaje
5. Documentos bien formardos vs mal formados
6. Ampliación XML - Cómo funcionan los espacios de nombres
7. Lenguajes de marcas en el mundo real
8. Revisión de errores frecuentes

## 1. ¿Qué es un lenguaje de marcas?

**¿Qué tienen en común una página web, una factura electrónica y una app meteorológica?**

Todas estas situaciones (una página web, una factura electrónica, una app del tiempo...) implican lo mismo: la **transmisión estructurada de información**.

Los lenguajes de marcas **permiten**:

- **Describir** la información (qué representa).
- **Estructurarla** para que sea comprensible.
- **Transmitirla** entre personas o entre sistemas informáticos.
- **Automatizar** su tratamiento (leer, transformar, visualizar, almacenar).

Son **esenciales** para el** intercambio de datos** en el mundo digital.

::: tip **📄 Factura electrónica: caso real de uso de XML**
Una factura electrónica no es simplemente un PDF. Es un documento estructurado que contiene la misma información que una factura tradicional, pero en un formato estándar y legible por ordenadores.

En España y en la Unión Europea, el formato más habitual es:

- 🧾 Formato: Facturae (formato XML)
- 📥 Finalidad: ser enviado a organismos públicos o empresas y leído automáticamente por programas de gestión.
- 🛠️ Contenido típico:
  - Datos del emisor y receptor
  - Detalles de productos o servicios
  - Impuestos
  - Totales
  - Firma electrónica

Ejemplo (simplificado):

```XML
<Factura>
  <Emisor>
    <Nombre>Distribuciones Pérez</Nombre>
    <NIF>B12345678</NIF>
  </Emisor>
  <Receptor>
    <Nombre>Ayuntamiento de Valencia</Nombre>
    <NIF>Q2816002D</NIF>
  </Receptor>
  <Total>452.60</Total>
</Factura>

```

:::

—

**¿Qué es un lenguaje de marcas?**

Un lenguaje de marcas es un sistema de codificación que permite estructurar, describir y presentar información.

Características generales:

- Utiliza etiquetas para marcar partes del contenido.
- Tiene una estructura jerárquica (anidamiento).
- Separa el contenido de su presentación visual.
- Es legible tanto por humanos como por máquinas.

—

**¿Por qué se utilizan?**

Ventajas de los lenguajes de marcas:

✅ Estructuran la información  
✅ Son fáciles de procesar automáticamente  
✅ Permiten reutilizar contenido  
✅ Separan contenido de formato  
✅ Se adaptan a diferentes contextos (web, móvil, escritorio, datos)

—

## 2. Clasificación

Tipos de lenguajes de marcas:

| Tipo                     | Ejemplo | Uso principal                           |
| ------------------------ | ------- | --------------------------------------- |
| Propósito general        | XML     | Intercambio estructurado entre sistemas |
| Propósito específico web | HTML    | Estructura y presentación web           |
| Datos / intercambio      | JSON    | Envío y recepción de datos (APIs)       |

—

### Ejemplos

Fragmentos de código representativos:

🔹 HTML

```html
<h1>Bienvenidos</h1>
```

🔹 XML

```xml
<libro><titulo>1984</titulo></libro>
```

🔹 JSON

```json
{ "titulo": "1984" }
```

**¿Qué tienen en común estos fragmentos? ¿Qué diferencias observas?**

—

::: info ✏️ **Para practicar:**
Actividad: Analiza fragmentos de código y rellena la siguiente tabla.

| Fragmento de código                                           | ¿Qué lenguaje es? | ¿Qué representa? | ¿Qué ventajas ves? | ¿Dónde lo usarías? |
| ------------------------------------------------------------- | ----------------- | ---------------- | ------------------ | ------------------ |
| &lt;title&gt;Batman&lt;/title&gt;                             |                   |                  |                    |                    |
| /{ "autor": "Orwell" /}                                       |                   |                  |                    |                    |
| &lt;alumno&gt;&lt;nombre&gt;Ana&lt;/nombre&gt;&lt;/alumno&gt; |                   |                  |                    |                    |

:::

### Comparativa actual – XML vs JSON

¿XML o JSON? ¿Cuál se usa más hoy en día? Depende del contexto:

| Aspecto                    | XML                                                 | JSON                                                  |
| -------------------------- | --------------------------------------------------- | ----------------------------------------------------- |
| Origen                     | 1998 – W3C                                          | 2001 – Derivado de JavaScript                         |
| Sintaxis                   | Verbosa, basada en etiquetas                        | Más ligera, basada en pares clave-valor               |
| Estructura jerárquica      | Sí (etiquetas anidadas)                             | Sí (objetos anidados y arrays)                        |
| Legibilidad para humanos   | Media                                               | Alta                                                  |
| Facilidad de procesamiento | Requiere parser XML                                 | Muy fácil en lenguajes modernos (JavaScript, Python…) |
| Uso actual                 | Documentación formal, estándares, interoperabilidad | APIs, aplicaciones web, apps móviles                  |
| Validación con esquemas    | Muy robusta (XSD, DTD)                              | Limitada (JSON Schema opcional)                       |
| Soporte para firma digital | Alto (XMLDSig)                                      | Bajo o no estándar                                    |
| Tamaño de los archivos     | Mayor                                               | Menor                                                 |

—

En resumen:

- XML se sigue usando en ámbitos formales o normativos (administración, facturación, estándares industriales).
- JSON es el formato más utilizado hoy en día para el intercambio de datos entre aplicaciones modernas.

En este módulo, aprenderás ambos:

- XML para comprender su estructura, validación y presencia en contextos formales.
- JSON para trabajar con APIs, aplicaciones web y manipulación de datos en JavaScript.

## 3. Estructura y sintaxis de HTML, JSON y XML

### Anatomía de un documento HTML5

Ejemplo:

```html
<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <title>Mi primera página</title>
  </head>
  <body>
    <h1>Hola mundo</h1>
    <p>Esta es una web sencilla.</p>
  </body>
</html>
```

Observa:

- Apertura y cierre de etiquetas
- Estructura jerárquica
- Separación entre estructura (), presentación () y configuración ()

### Estructura de un objeto JSON

JSON representa datos como objetos o arrays.

Características clave:

- Basado en pares clave:valor
- Utiliza llaves {} para objetos, corchetes \[\] para arrays
- No usa etiquetas, sino comillas y signos de puntuación

Ejemplo:

```json
{
  "titulo": "Batman Begins",
  "año": 2005,
  "géneros": ["acción", "superhéroes"],
  "director": {
    "nombre": "Christopher Nolan",
    "edad": 55
  }
}
```

Observa:

- Las claves siempre entre comillas
- Los valores pueden ser texto, número, booleano, array u objeto
- Muy utilizado en aplicaciones web y móviles

—

### Sintaxis básica de XML

XML estructura datos mediante etiquetas personalizadas.

Características:

- Se definen etiquetas personalizadas según el contexto
- Requiere una estructura bien formada (cada apertura debe cerrarse)
- Puede tener atributos y anidamiento complejo

Ejemplo:

```xml
<videojuego plataforma="PS5">
  <titulo>Spider-Man 2</titulo>
  <genero>Acción</genero>
  <precio>59.99</precio>
</videojuego>
```

Observa:

- Las etiquetas tienen nombre libre pero coherente
- Se pueden usar atributos (como plataforma="PS5")
- No admite errores de anidamiento o cierre

🔸 En XML, puedes representar la misma información de varias formas. Por ejemplo:

Opción A – plataforma como atributo:

```xml
<videojuego plataforma="PS5">
  <titulo>Spider-Man 2</titulo>
  <genero>Acción</genero>
  <precio>59.99</precio>
</videojuego>
```

Opción B – plataforma como subelemento:

```xml
<videojuego>
  <plataforma>PS5</plataforma>
  <titulo>Spider-Man 2</titulo>
  <genero>Acción</genero>
  <precio>59.99</precio>
</videojuego>
```

Ambas son sintácticamente válidas. La diferencia está en lo semántico.

—

¿Cuándo se usa cada una?

| Caso                                        | Mejor como atributo | Mejor como subelemento |
| ------------------------------------------- | ------------------- | ---------------------- |
| Información que identifica o clasifica      | ✅                  |                        |
| Información que puede tener estructura      |                     | ✅                     |
| Información opcional pero con datos ricos   |                     | ✅                     |
| Información “meta” (de control, tipo, etc.) | ✅                  |                        |
| Información compleja o con unidades         |                     | ✅                     |

—

📌 En nuestro ejemplo:

- plataforma="PS5" es una información que clasifica el videojuego, que no necesita más estructura y no se espera que tenga subcampos (por ejemplo: fabricante, modelo, año…).
- Por eso, es habitual representarlo como atributo.
- Además, los atributos no pueden tener hijos, solo valores simples (texto sin etiquetas internas).

🔁 Pero si quisiéramos ampliar la plataforma, lo cambiaríamos a subelemento:

```xml
<plataforma>
  <nombre>PS5</nombre>
  <fabricante>Sony</fabricante>
</plataforma>
```

::: info ✏️ **Para practicar:**

En parejas, lee los siguientes tres fragmentos.

Por cada uno:

1.  ¿Qué representa el documento?
2.  ¿Cómo está estructurado?
3.  ¿Qué elementos o símbolos llaman la atención?

Fragmento 1 – HTML

```html
<body>
  <h2>Contacto</h2>
  <ul>
    <li>Email: info@ejemplo.com</li>
    <li>Teléfono: 123456789</li>
  </ul>
</body>
```

Fragmento 2 – JSON

```json
{
  "usuario": "juan87",
  "activo": true,
  "rol": "editor"
}
```

Fragmento 3 – XML

```xml
<pedido>
  <producto>Ratón inalámbrico</producto>
  <cantidad>2</cantidad>
</pedido>
```

:::

## 4. El propósito de cada lenguaje

### ¿Para qué sirve cada lenguaje?

📚 HTML  
→ Diseñado para presentar contenido en la web.  
Ej.: páginas web, estructuras de documentos visuales.  
🔗 Contiene enlaces, encabezados, listas, párrafos…

📦 JSON  
→ Diseñado para el intercambio de datos entre aplicaciones.  
Ej.: comunicación entre frontend y backend, APIs, apps móviles.  
🔗 Representa objetos, arrays, estructuras anidadas…

🧩 XML  
→ Diseñado como lenguaje de marcas de propósito general.  
Ej.: facturas electrónicas, configuraciones, interoperabilidad.  
🔗 Define estructura personalizable, con posibilidad de validación.

—

### Comparativa: ¿cuándo usar qué?

| Situación                                       | Lenguaje más adecuado | ¿Por qué?                                  |
| ----------------------------------------------- | --------------------- | ------------------------------------------ |
| Crear una página web                            | HTML                  | Se interpreta directamente por navegadores |
| Enviar datos de una app móvil al servidor       | JSON                  | Ligero, fácil de procesar por JavaScript   |
| Generar una factura estructurada para Hacienda  | XML                   | Estandarizado, validable, interoperable    |
| Consultar el tiempo en una app                  | JSON                  | Usado en APIs de datos meteorológicos      |
| Crear un RSS para suscribirse a noticias        | XML                   | Requiere estructura, estándar formal       |
| Mostrar un artículo con texto, imágenes y vídeo | HTML                  | Orientado a presentación multimedia        |

—
::: info ✏️ **Para practicar:**
Actividad 1 : ¿Qué lenguaje usarías para…?

1.  Mostrar una receta de cocina en una página web
2.  Sincronizar datos entre dos apps móviles
3.  Guardar configuraciones de una app para abrirlas más tarde
4.  Publicar un catálogo online de películas con fichas detalladas
5.  Enviar un formulario desde una web a un servidor

Actividad 2: Clasifica las situaciones

- Indicar qué lenguaje usaría en cada caso (HTML, XML, JSON).
- Justificar brevemente por qué ese lenguaje es el adecuado.

📎 Ejemplos de situaciones para la plantilla:

| Nº  | Situación                                                                      | Lenguaje | Justificación breve |
| --- | ------------------------------------------------------------------------------ | -------- | ------------------- |
| 1   | Un videojuego guarda tu partida y luego la recupera                            |          |                     |
| 2   | Una empresa factura a otra empresa pública                                     |          |                     |
| 3   | Una web muestra noticias con titulares, imágenes y enlaces                     |          |                     |
| 4   | Una app meteorológica consulta la temperatura y la lluvia esperada para mañana |          |                     |
| 5   | Una app necesita almacenar preferencias del usuario entre sesiones             |          |                     |
| 6   | Una API de películas devuelve título, director, año, géneros y puntuación      |          |                     |
| 7   | Una administración pública crea un modelo de documento interoperable           |          |                     |

:::

## 5. Documentos bien formados vs mal formados

### ¿Qué significa que un documento esté “bien formado”?

Un documento está bien formado cuando respeta las reglas sintácticas del lenguaje.  
 Si no lo hace, no podrá ser interpretado por programas o navegadores.

📍 En XML:

- Todas las etiquetas deben cerrarse.
- Las etiquetas deben estar correctamente anidadas.
- Solo puede haber un único elemento raíz.
- Los atributos deben estar entre comillas.

📍 En JSON:

- Las claves deben estar entre comillas dobles.
- Las comas deben colocarse correctamente (sin dejar una al final).
- No puede haber comentarios.
- Las estructuras deben estar cerradas correctamente con } o \].

—

Ejemplo de XML bien vs mal formado

❌ Mal formado:

```xml
<persona>
  <nombre>Elena</nombre>
  <edad>34
</persona>
```

Errores:

- Falta etiqueta de cierre para
- El contenido no se puede procesar automáticamente

✅ Bien formado:

```xml
<persona>
  <nombre>Elena</nombre>
  <edad>34</edad>
</persona>
```

Ejemplo de JSON bien vs mal formado

❌ Mal formado:

```json
{
  "usuario": "paco",
  "edad": 28,
  "activo": true
}
```

Errores:

- Coma final después del último elemento → no permitida

✅ Bien formado:

```json
{
  "usuario": "paco",
  "edad": 28,
  "activo": true
}
```

¿Por qué es tan importante?

Los lenguajes de marcas suelen ser procesados por máquinas.  
Si un documento está mal formado:

- No puede ser leído o procesado
- Se produce un error o fallo en la carga
- Puede corromper procesos automáticos (facturación, apps, APIs…)

📌 En la web:  
Una etiqueta mal cerrada puede romper toda la página.  
📌 En una factura electrónica:  
Un XML mal formado la invalida para su envío o firma.

::: info ✏️ **Para practicar:**

Detectar y corregir errores en fragmentos de XML y JSON.

1.  Leer el fragmento
2.  Detectar errores sintácticos
3.  Reescribir el fragmento corregido
4.  Justificar el error y su corrección

🧩 Ejemplo de actividad:

❌ XML con errores:

```xml
<curso>
  <nombre>LMISGI</nombre>
  <horas>70
  <modulo>0373</modulo>
</curso>
```

✅ Corrección esperada:

```xml
<curso>
  <nombre>LMISGI</nombre>
  <horas>70</horas>
  <modulo>0373</modulo>
</curso>
```

:::

## 6. Ampliación - Introducción a los nombres de espacio (namespaces) en XML

### ¿Qué son los nombres de espacio en XML?

Los **nombres de espacio (namespaces)** son un mecanismo que permite evitar **conflictos de nombres** cuando un documento XML mezcla etiquetas que pueden tener el mismo nombre pero distinto significado o procedencia.

Por ejemplo, imagina que tienes dos vocabularios XML diferentes: uno para datos bibliográficos y otro para datos técnicos, ambos usan la etiqueta `<titulo>`, pero con significados distintos. Sin namespaces, el parser no puede diferenciar entre ambos.

### ¿Cómo funcionan?

Un namespace se define asociando un **prefijo** con una **URI** (Identificador Uniforme de Recursos, que actúa como un identificador único, no tiene que ser una URL real). Luego se usan esos prefijos para calificar las etiquetas.

### Ejemplo simple sin namespace (problema):

```xml
<pelicula>
  <titulo>Matrix</titulo>
  <titulo>Título técnico</titulo>
</pelicula>
```

Aquí hay dos `<titulo>`, pero no sabemos cuál es cuál.

### Ejemplo con namespaces (solución):

```xml
<pelicula xmlns:info="http://www.ejemplo.com/info" xmlns:tecn="http://www.ejemplo.com/tecnico">
  <info:titulo>Matrix</info:titulo>
  <tecn:titulo>Título técnico</tecn:titulo>
</pelicula>
```

- `xmlns:info="..."` define el espacio de nombres con prefijo `info`.
- `xmlns:tecn="..."` define otro espacio con prefijo `tecn`.
- Cada etiqueta `<info:titulo>` y `<tecn:titulo>` pertenece a un espacio distinto.

### Ventajas

- Evita ambigüedades al combinar vocabularios o esquemas diferentes.
- Permite que un documento sea extensible y interoperable.
- Es fundamental en estándares XML complejos (SOAP, XHTML, SVG, etc.).

### ¿Cuándo usar namespaces?

- Cuando trabajas con documentos XML que combinan varios esquemas o estándares.
- Cuando necesitas evitar colisiones en nombres de etiquetas.
- En documentos simples, no siempre es obligatorio, pero es buena práctica conocerlo.

### ¿Cómo se declara un namespace?

- Se declara en la etiqueta raíz (o en cualquier etiqueta) con `xmlns:prefijo="URI"`
- Luego se usa el prefijo para calificar las etiquetas que pertenecen a ese espacio.

### Ejemplo extendido de un fragmento con namespace:

```xml
<peliculas xmlns:info="http://www.ejemplo.com/info" xmlns:desc="http://www.ejemplo.com/desc">
  <pelicula>
    <info:titulo>Matrix</info:titulo>
    <desc:sinopsis>Una realidad simulada...</desc:sinopsis>
  </pelicula>
</peliculas>
```

https://es.wikipedia.org/wiki/Espacio_de_nombres_XML

&nbsp;

## 7. Lenguajes de marcas en el mundo real

### XML en RSS, SVG y XSL

- **RSS** (Really Simple Syndication): feed de noticias basado en XML.
- **SVG** (Scalable Vector Graphics): imágenes vectoriales definidas con XML.
- **XSL/XSLT** (Extensible Stylesheet Language): transformación de XML a otros formatos (HTML, texto,…).

**Ejemplo breve de RSS:**

```xml
<rss version="2.0">
  <channel>
    <title>Noticias DAM</title>
    <item>
      <title>Convocatoria examen</title>
      <link>https://centro.edu/examen</link>
      <pubDate>Thu, 10 Jul 2025 12:00:00 +0200</pubDate>
    </item>
  </channel>
</rss>
```

### JSON en APIs y Open Data

**Contenido teórico:**

- **APIs RESTful:** intercambio de datos con JSON.
- **Portales Open Data:** catálogos de datos públicos en JSON.

**Ejemplo breve de respuesta API (curl en consola):**

```bash
curl -s https://api.ejemplo.com/peliculas/123 | jq .
```

```json
{
  "id": 123,
  "titulo": "Amélie",
  "director": "Jean-Pierre Jeunet",
  "año": 2001
}
```

### HTML como base de páginas web

**Contenido teórico:**

- **Estructura semántica** con etiquetas `<header>`, `<nav>`, `<main>`, `<footer>`.
- **Accesibilidad** y SEO: uso correcto de `<h1>…<h6>`, `<alt>` en imágenes.

**Ejemplo de fragmento real de GitHub Pages:**

```html
<header>
  <h1>Mi portafolio</h1>
  <nav>
    <ul>
      <li><a href="#sobre-mi">Sobre mí</a></li>
      <li><a href="#proyectos">Proyectos</a></li>
    </ul>
  </nav>
</header>
```

::: info ✏️ **Para practicar:**
Actividad: Exploración guiada

**Instrucciones:**

1.  **HTML real**: Abrir la consola de desarrollador en una web (ej. https://ejemplo.com) y localizar la sección `<nav>` o el `<header>`.
2.  **JSON en consola**: Ejecutar un `curl` o usar las DevTools → pestaña “Network” para ver una respuesta JSON de una API pública (p. ej. https://pokeapi.co).
3.  **XML en un feed**: Suscribirse al RSS de un blog (p. ej. https://blog.ejemplo.com/rss) y abrirlo en un navegador o editor.

:::

## 8. Revisión de errores frecuentes (con ejemplos)

1.  **Etiqueta sin cierre en HTML**

    ```html
    <!-- Error: falta cierre de <p> -->
    <section>
      <h2>Películas favoritas</h2>
      <p>Lista de películas más vistas</p>
      <ul>
        <li>El viaje de Chihiro</li>
        <li>Amélie</li>
      </ul>
    </section>
    ```

    _Corrección:_ añadir `</p>` justo antes de `<ul>`.

2.  **Coma de más / falta en JSON**

    ```json
    {
      "titulo": "Matrix",
      "director": "Wachowski",    <-- aquí sobra la coma
      "año": 1999
    }
    ```

    _Corrección:_ eliminar la coma tras `"Wachowski"`.

    ```json
    {
      "titulo": "Matrix"
      "director": "Wachowski",    <-- aquí falta la coma
      "año": 1999
    }
    ```

    _Corrección:_ añadir la coma después de `"Matrix"`.

3.  **Namespace mal declarado en XML**

    ```xml
    <!-- Error: xmlns:info mal escrito como xmlsn -->
    <peliculas xmlsn:info="http://ejemplo.com/info">
      <info:pelicula>
        <info:titulo>Matrix</info:titulo>
      </info:pelicula>
    </peliculas>
    ```

    _Corrección:_

    ```xml
    <peliculas xmlns:info="http://ejemplo.com/info">
      <info:pelicula>
        <info:titulo>Matrix</info:titulo>
      </info:pelicula>
    </peliculas>
    ```

4.  **Etiqueta mal anidada en XML**

    ```xml
    <!-- Error: <director> se cierra antes de cerrar <pelicula> -->
    <pelicula>
      <titulo>Amélie</titulo>
      <director>Jean-Pierre Jeunet</pelicula>
    </director>
    ```

    _Corrección:_

    ```xml
    <pelicula>
      <titulo>Amélie</titulo>
      <director>Jean-Pierre Jeunet</director>
    </pelicula>
    ```

&nbsp;
