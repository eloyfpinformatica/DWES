---
typora-copy-images-to: ./imgs
hide:
  - navigation
---

# Unidad 2. Lenguajes de marcas web. HTML y CSS

**Temporalización**: 20 sesiones

## Unidad 2. HTML y CSS

---

**Temporalización**: 20 sesiones

| Nº sesión | Contenido                                                                  |
| --------- | -------------------------------------------------------------------------- |
| 1         | [Introducción a HTML](#1-html-estructurando-la-web-con-html)                             |
| 2         | [Estructura de un documento HTML](#1-html-estructurando-la-web-con-html)     |
| 3         | [Etiquetas básicas](#13-fundamentos-de-texto)                                 |
| 4         | [Multimedia: enlaces, imágenes y vídeo](#19-imagenes) |
| 5         | [Formularios](#112-formularios)                                             |
| 6         | [Proyecto HTML (parte 1)](#proyecto-practico-de-html-tu-pagina-de-perfil-personal)                               |
| 7         | [Proyecto HTML (parte 2)](#proyecto-practico-de-html-tu-pagina-de-perfil-personal)                               |
| 8         | [Introducción a CSS y primeros selectores](#2-css-estilizando-la-web)         |
| 9         | [Propiedades básicas y herencia](#23-selectores-css)     |
| 10        | [Modelo de caja](#27-modelo-de-caja)                     |
| 11        | [Posicionamiento y display](#27-modelo-de-caja)          |
| 12        | [Proyecto CSS (parte 1)](#proyecto-practico-de-css-estilizando-tu-pagina-de-perfil-personal)                                 |
| 13        | [Proyecto CSS (parte 2)](#proyecto-practico-de-css-estilizando-tu-pagina-de-perfil-personal)                                 |
| 14        | [Flexbox](#2174-flexbox)                                                     |
| 15        | [Grid](#2175-cuadrículas-grid)                                                           |
| 16        | [Media Queries y diseño responsive](#2176-diseño-responsive) |
| 17        | [Proyecto CSS avanzado (parte 1)](#proyecto-practico-de-css-layout-organizando-tu-pagina-de-perfil-personal)               |
| 18        | [Proyecto CSS avanzado (parte 2)](#proyecto-practico-de-css-layout-organizando-tu-pagina-de-perfil-personal)               |
| 19        | [Sindicación de contenidos (RSS)](#3-rss)        |
| 20        | [Examen](#)                                                        |




**Resultado de aprendizaje**:

**RA2**. Utiliza lenguajes de marcas para la transmisión y presentación de información a través de la web analizando la estructura de los documentos e identificando sus elementos.

**Criterios de evaluación asociados**:

- **RA2.a**. Se han identificado y clasificado los lenguajes de marcas relacionados con la web y sus diferentes versiones y estándares.

- **RA2.b**. Se ha analizado la estructura de un documento HTML e identificado las secciones que lo componen.

- **RA2.c**. Se ha reconocido la funcionalidad de las principales etiquetas y los atributos del lenguaje HTML.

- **RA2.d**. Se han establecido las semejanzas y diferencias entre las diferentes versiones de HTML.

- **RA2.e**. Se han utilizado herramientas en la creación de documentos web.

- **RA2.f**. Se han identificado las ventajas que aporta la utilización de hojas de estilo.

- **RA2.g**. Se han aplicado hojas de estilo.

- **RA2.h**. Se han validado documentos HTML y CSS.

- **RA2.i**. Se han identificado las tecnologías en que se basa la sindicación de contenidos.

- **RA2.j**. Se han reconocido los ámbitos de aplicación de la sindicación de contenidos.


-----

## Índice

1.  **[HTML](#1-html-estructurando-la-web-con-html)**  
1.1. [Estructurando la web con HTML](#1-html-estructurando-la-web-con-html)  
1.2. [Primeros pasos](#11-primeros-pasos)  
1.3. [Metadatos](#12-metadatos)  
1.4. [Fundamentos de texto](#13-fundamentos-de-texto)  
1.5. [Énfasis](#14-enfasis)  
1.6. [Listas](#15-listas)  
1.7. [Estructura web y documentación](#16-estructura-web-y-documentacion)  
1.8. [Formateo de texto avanzado](#17-formateo-de-texto-avanzado)  
1.9. [Enlaces](#18-enlaces)  
1.10. [Imágenes](#19-imagenes)  
1.11. [Audio y vídeo](#110-audio-y-video)  
1.12. [Tablas](#111-tablas)  
1.13. [Formularios](#112-formularios)  
1.14. [Validación](#113-validacion)  

2.  **[CSS](#2-css-estilizando-la-web)**  
2.1. [Cómo funciona](#21-como-funciona-css)  
2.2. [Empezar con CSS](#22-empezar-con-css)  
2.3. [Selectores CSS](#23-selectores-css)  
2.4. [Selectores de atributos](#24-selectores-de-atributos)  
2.5. [Pseudo-clases y pseudo-elementos](#25-pseudo-clases-y-pseudo-elementos)  
2.6. [Combinadores](#26-combinadores)  
2.7. [Modelo de caja](#27-modelo-de-caja)  
2.8. [Cascada y herencia](#28-cascada-y-herencia)  
2.9. [Valores y unidades CSS](#29-valores-y-unidades-css)  
2.10. [Dimensionar elementos CSS](#210-dimensionar-elementos-css)  
2.11. [Fondos y bordes](#211-fondos-y-bordes)  
2.12. [Contenido desbordado](#212-contenido-desbordado)  
2.13. [Imágenes, medios y elementos de formularios](#213-imagenes-medios-y-elementos-de-formularios)  
2.14. [Tablas](#214-tablas)  
2.15. [Depurar CSS](#215-depurar-css)  
2.16. [CSS texto](#216-css-texto)  
  2.16.1. [Fuentes](#2161-fuentes)  
  2.16.2. [Listas](#2162-listas)  
  2.16.3. [Enlaces](#2163-enlaces)  
  2.16.4. [Fuentes web](#2164-fuentes-web-web-fonts)  
2.17. [Layout](#217-css-layout)  
  2.17.1. [Introducción](#2171-introduccion-a-css-layout)  
  2.17.2. [Float](#2172-float)  
  2.17.3. [Posicionamiento](#2173-posicionamiento)  
  2.17.4. [Flexbox](#2174-flexbox)  
  2.17.5. [Cuadrículas](#2175-cuadriculas-grid)  
  2.17.6. [Diseño responsive](#2176-diseno-responsive)  
  2.17.7. [Media queries](#2177-media-queries)  

3.  **[RSS](#3-rss)**  
    3.1. [¿Qué es?](#31-que-es)  
    3.2. [Ejemplos](#32-ejemplos)  

4.  **[Proyectos](#4-proyectos)**  
    4.1. [Proyecto HTML](#proyecto-práctico-de-html-tu-página-de-perfil-personal)  
    4.2. [Proyecto CSS](#proyecto-práctico-de-css-estilizando-tu-página-de-perfil-personal)  
    4.3. [Proyecto CSS avanzado](#proyecto-práctico-de-css-layout-organizando-tu-página-de-perfil-personal)  



## 1. HTML: Estructurando la web con HTML

### 1.1. Primeros Pasos

HTML (HyperText Markup Language) es el lenguaje estándar para crear páginas web. Se utiliza para estructurar el contenido, definiendo elementos como encabezados, párrafos, imágenes, enlaces, etc.

Un documento HTML básico tiene la siguiente estructura:

```html
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Primera Página HTML</title>
</head>
<body>
    <h1>¡Hola, mundo!</h1>
    <p>Esta es mi primera página web.</p>
</body>
</html>
```

- `<!DOCTYPE html>`: Declara el tipo de documento y la versión de HTML (HTML5 en este caso).
- `<html lang="es">`: El elemento raíz de la página, indicando el idioma español.
- `<head>`: Contiene metadatos sobre la página (no visibles en el navegador).
- `<body>`: Contiene todo el contenido visible de la página.

### 1.2. Metadatos

Los metadatos proporcionan información sobre la página, pero no se muestran directamente en el navegador. Se colocan dentro de la etiqueta `<head>`.

- `<meta charset="UTF-8">`: Especifica la codificación de caracteres para que el navegador interprete correctamente los caracteres especiales (como tildes o la "ñ").
- `<meta name="viewport" content="width=device-width, initial-scale=1.0">`: Es crucial para el diseño web adaptable (responsive design), ajustando el área de visualización al ancho del dispositivo.
- `<title>`: Define el título que aparece en la pestaña del navegador.

**Ejemplo:**

```html
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Curso básico de HTML para principiantes.">
    <meta name="keywords" content="HTML, curso, web, desarrollo">
    <title>Introducción a HTML</title>
</head>
```



::: info **Ampliación**

[Metadatos. Añadir favicon.](https://developer.mozilla.org/es/docs/Learn_web_development/Core/Structuring_content/Webpage_metadata)

:::
### 1.3. Fundamentos de Texto

HTML proporciona varias etiquetas para estructurar y presentar texto.

- **Encabezados (`<h1>` a `<h6>`):** Se utilizan para títulos y subtítulos, siendo `<h1>` el más importante y `<h6>` el menos importante.
  
    ```html
    <h1>Título Principal</h1>
    <h2>Subtítulo Importante</h2>
    <h3>Otro Subtítulo</h3>
    ```
    
- **Párrafos (`<p>`):** Contienen bloques de texto.
  
    ```html
    <p>Este es un párrafo de texto. Aquí puedes escribir contenido.</p>
    ```
    
- **Saltos de línea (`<br>`):** Inserta un salto de línea dentro de un párrafo. Es una etiqueta de cierre automático.
  
    ```html
    <p>Línea uno.<br>Línea dos.</p>
    ```
    
- **Regla horizontal (`<hr>`):** Dibuja una línea horizontal para separar contenido. También es una etiqueta de cierre automático.
  
    ```html
    <p>Contenido superior.</p>
    <hr>
    <p>Contenido inferior.</p>
    ```
    

### 1.4. Énfasis

HTML ofrece etiquetas para dar énfasis semántico y visual al texto.

- **Negrita:**
  
    - `<strong>`: Indica importancia o fuerte énfasis (semántico).
    - `<b>`: Solo aplica negrita visual (sin énfasis semántico).
- **Cursiva:**
  
    - `<em>`: Indica énfasis (semántico).
    - `<i>`: Solo aplica cursiva visual (sin énfasis semántico).

**Ejemplo:**

```html
<p>Este texto es <strong>muy importante</strong>.</p>
<p>Este es un texto en <b>negrita</b>.</p>
<p>La palabra <em>ejemplo</em> está enfatizada.</p>
<p>Este texto está en <i>cursiva</i>.</p>
```

### 1.5. Listas

Las listas son fundamentales para organizar información.

- **Listas desordenadas (`<ul>`):** Utilizan viñetas. Cada elemento de la lista se define con `<li>`.
  
    ```html
    <ul>
        <li>Elemento 1</li>
        <li>Elemento 2</li>
        <li>Elemento 3</li>
    </ul>
    ```
    
- **Listas ordenadas (`<ol>`):** Utilizan números o letras. Cada elemento también se define con `<li>`.
  
    ```html
    <ol>
        <li>Primer paso</li>
        <li>Segundo paso</li>
        <li>Tercer paso</li>
    </ol>
    ```
    
- **Listas de descripción (`<dl>`):** Se utilizan para listar términos y sus descripciones.
  
    - `<dt>`: Término a describir.
    - `<dd>`: Descripción del término.
    
    ```html
    <dl>
        <dt>HTML</dt>
        <dd>Lenguaje de marcado para la creación de páginas web.</dd>
        <dt>CSS</dt>
        <dd>Lenguaje de hojas de estilo para dar estilo a los documentos web.</dd>
    </dl>
    ```
    

### 1.6. Estructura Web y Documentación

Además de los elementos vistos, HTML5 introdujo etiquetas semánticas para mejorar la estructura y la legibilidad del código.

- `<header>`: Contenido introductorio o un grupo de enlaces de navegación.
- `<nav>`: Contiene enlaces de navegación.
- `<main>`: Contenido principal y único de la página.
- `<article>`: Contenido independiente y auto-contenido (como un post de blog).
- `<section>`: Agrupa contenido temáticamente relacionado.
- `<aside>`: Contenido relacionado pero secundario (como una barra lateral).
- `<footer>`: Contenido de pie de página (información de contacto, derechos de autor, etc.).

**Ejemplo de estructura:**

```html
<body>
    <header>
        <h1>Mi Sitio Web</h1>
        <nav>
            <ul>
                <li><a href="#inicio">Inicio</a></li>
                <li><a href="#acerca">Acerca de</a></li>
                <li><a href="#contacto">Contacto</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section id="inicio">
            <h2>Bienvenido</h2>
            <p>Este es el contenido principal de la sección de inicio.</p>
        </section>

        <article>
            <h2>Título del Artículo</h2>
            <p>Contenido del artículo...</p>
        </article>
    </main>

    <aside>
        <h3>Anuncios</h3>
        <p>Publicidad o enlaces relacionados.</p>
    </aside>

    <footer>
        <p>&copy; 2025 Mi Sitio Web</p>
    </footer>
</body>
```

### 1.7. Formateo de Texto Avanzado

Otras etiquetas útiles para formatear texto.

- **`<code>`:** Para bloques de código en línea.
  
    ```html
    <p>La función es <code>console.log()</code>.</p>
    ```
    
- **`<pre>`:** Para texto preformateado (conserva espacios y saltos de línea).
  
    ```html
    <pre>
        Esto es un texto
        preformateado.
        Respeta los espacios
        y saltos de línea.
    </pre>
    ```
    
- **`<blockquote>`:** Para citas largas.
  
    ```html
    <blockquote>
        <p>El único modo de hacer un gran trabajo es amar lo que haces.</p>
        <footer>— Steve Jobs</footer>
    </blockquote>
    ```
    
- **`<q>`:** Para citas cortas en línea.
  
    ```html
    <p>Como dijo Shakespeare: <q>Ser o no ser, esa es la cuestión.</q></p>
    ```
    
- **`<abbr>`:** Para abreviaturas. El atributo `title` muestra el texto completo al pasar el ratón.
  
    ```html
    <p>La <abbr title="Organización Mundial de la Salud">OMS</abbr> es un organismo internacional.</p>
    ```
    

### 1.8. Enlaces

Los enlaces (`<a>`) son la columna vertebral de la navegación web.

- **Atributo `href`:** Define la URL de destino.
- **Atributo `target="_blank"`:** Abre el enlace en una nueva pestaña.

**Ejemplos:**

- **Enlace externo:**
  
    ```html
    <a href="https://www.google.com" target="_blank">Visitar Google</a>
    ```
    
- **Enlace interno (a otra página de tu sitio):**
  
    ```html
    <a href="acerca.html">Acerca de nosotros</a>
    ```
    
- **Enlace a una sección dentro de la misma página (áncora):**  
    Primero, define un ID en el elemento de destino: `<h2 id="seccion-x">Sección X</h2>`  
    Luego, crea el enlace:
    
    ```html
    <a href="#seccion-x">Ir a Sección X</a>
    ```
    
- **Enlace de correo electrónico:**
  
    ```html
    <a href="mailto:tuemail@example.com">Enviar un correo</a>
    ```
    

### 1.9. Imágenes

Las imágenes (`<img>`) son esenciales para el atractivo visual de una página.

- **Atributo `src`:** Ruta de la imagen.
- **Atributo `alt`:** Texto alternativo para accesibilidad (se muestra si la imagen no carga o para lectores de pantalla).
- **Atributos `width` y `height`:** Dimensiones de la imagen (se recomienda usar CSS para el tamaño).

**Ejemplo:**

```html
<img src="ruta/a/mi-imagen.jpg" alt="Descripción de la imagen" width="300" height="200">
```

### 1.10. Audio y Video

HTML5 introdujo etiquetas nativas para incrustar audio y video sin necesidad de plugins.

- **`<audio>`:**
  
    - `src`: Ruta del archivo de audio.
    - `controls`: Muestra los controles de reproducción (play, pausa, volumen).
    - `autoplay`: Reproducción automática (no recomendado por usabilidad).
    - `loop`: Reproduce en bucle.
    - `muted`: Silencia el audio.
    
    ```html
    <audio controls>
        <source src="audio/ejemplo.mp3" type="audio/mpeg">
        <source src="audio/ejemplo.ogg" type="audio/ogg">
        Tu navegador no soporta el elemento de audio.
    </audio>
    ```
    
- **`<video>`:**
  
    - `src`: Ruta del archivo de video.
    - `controls`: Muestra los controles de reproducción.
    - `width` y `height`: Dimensiones del video.
    - `poster`: Imagen que se muestra antes de que el video se reproduzca.
    
    ```html
    <video controls width="640" height="360" poster="imagenes/portada-video.jpg">
        <source src="video/ejemplo.mp4" type="video/mp4">
        <source src="video/ejemplo.webm" type="video/webm">
        Tu navegador no soporta el elemento de video.
    </video>
    ```
    

### 1.11. Tablas

Las tablas (`<table>`) se utilizan para mostrar datos tabulares.

- `<thead>`: Contiene el encabezado de la tabla.
- `<tbody>`: Contiene el cuerpo de la tabla.
- `<tr>`: Fila de la tabla.
- `<th>`: Celda de encabezado (dentro de `<thead>` o `<tbody>`).
- `<td>`: Celda de datos.

**Ejemplo:**

```html
<table>
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Edad</th>
            <th>Ciudad</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Juan</td>
            <td>30</td>
            <td>Madrid</td>
        </tr>
        <tr>
            <td>María</td>
            <td>25</td>
            <td>Barcelona</td>
        </tr>
    </tbody>
</table>
```

### 1.12. Formularios

Los formularios (`<form>`) permiten a los usuarios introducir datos.

- **Atributo `action`:** URL a la que se envían los datos.
- **Atributo `method`:** Método HTTP para enviar los datos (`GET` o `POST`).

Elementos comunes de formulario:

- **`<input>`:** Elemento más versátil.
  
    - `type="text"`: Campo de texto simple.
    - `type="email"`: Campo para correo electrónico.
    - `type="password"`: Campo para contraseñas.
    - `type="number"`: Campo para números.
    - `type="checkbox"`: Casilla de verificación.
    - `type="radio"`: Botón de radio (solo uno puede ser seleccionado en un grupo).
    - `type="submit"`: Botón para enviar el formulario.
    - `name`: Nombre del campo (crucial para enviar datos).
    - `id`: Identificador único (para asociar con `<label>`).
    - `placeholder`: Texto de sugerencia.
    - `required`: Campo obligatorio.
- **`<label>`:** Asocia texto descriptivo con un control de formulario (mejora accesibilidad). Usa el atributo `for` para vincularlo al `id` del input.
  
- **`<textarea>`:** Campo de texto de varias líneas.
  
- **`<select>`:** Lista desplegable.
  
    - `<option>`: Opción dentro de la lista.
- **`<button>`:** Botón genérico (puede ser de tipo `submit`, `reset` o `button`).
  

**Ejemplo:**

```html
<form action="/procesar-formulario" method="post">
    <label for="nombre">Nombre:</label><br>
    <input type="text" id="nombre" name="nombre" placeholder="Introduce tu nombre" required><br><br>

    <label for="email">Email:</label><br>
    <input type="email" id="email" name="email" placeholder="tu@ejemplo.com"><br><br>

    <p>Género:</p>
    <input type="radio" id="masculino" name="genero" value="masculino">
    <label for="masculino">Masculino</label><br>
    <input type="radio" id="femenino" name="genero" value="femenino">
    <label for="femenino">Femenino</label><br><br>

    <label for="mensaje">Mensaje:</label><br>
    <textarea id="mensaje" name="mensaje" rows="5" cols="30"></textarea><br><br>

    <label for="pais">País:</label><br>
    <select id="pais" name="pais">
        <option value="es">España</option>
        <option value="mx">México</option>
        <option value="ar">Argentina</option>
    </select><br><br>

    <input type="checkbox" id="acepto" name="acepto_terminos" value="si">
    <label for="acepto">Acepto los términos y condiciones</label><br><br>

    <input type="submit" value="Enviar">
</form>
```

### 1.13. Validación

HTML5 ofrece validación de formularios integrada en el navegador, lo que simplifica la verificación de datos antes de enviarlos al servidor.

- **Atributo `required`:** Hace que un campo sea obligatorio. El navegador mostrará un mensaje de error si se intenta enviar el formulario sin rellenarlo.
  
    ```html
    <input type="text" id="usuario" name="usuario" required>
    ```
    
- **Atributo `minlength` y `maxlength`:** Establecen la longitud mínima y máxima para campos de texto.
  
    ```html
    <input type="text" id="contrasena" name="contrasena" minlength="8" maxlength="20">
    ```
    
- **Atributo `min` y `max`:** Establecen el valor mínimo y máximo para campos numéricos o de fecha.
  
    ```html
    <input type="number" id="edad" name="edad" min="18" max="99">
    ```
    
- **Atributo `pattern`:** Permite definir una expresión regular para validar el formato del input.
  
    ```html
    <input type="text" id="codigo_postal" name="codigo_postal" pattern="[0-9]{5}" title="Introduce un código postal de 5 dígitos">
    ```
    
    (Este ejemplo valida un código postal de 5 dígitos numéricos).
    
- **Tipos de input específicos:** Tipos como `email`, `url`, `number`, etc., tienen validación de formato incorporada.
  
    ```html
    <input type="email" id="correo" name="correo">
    ```
    
    El navegador verificará si el texto introducido tiene el formato de un correo electrónico.
    

## 2. CSS: Estilizando la Web

### 2.1. Cómo Funciona CSS

**CSS (Cascading Style Sheets)** es un lenguaje de hojas de estilo que se utiliza para describir la presentación de un documento escrito en HTML. Mientras que HTML estructura el contenido, CSS le da estilo: colores, fuentes, espaciado, diseño, etc.

CSS funciona aplicando reglas a los elementos HTML. Una regla CSS se compone de un **selector** y un **bloque de declaración**.

- **Selector:** Apunta a los elementos HTML a los que quieres dar estilo (por ejemplo, `<p>`, `<h1>`, `.mi-clase`, `#mi-id`).
- **Bloque de Declaración:** Contiene una o más declaraciones separadas por punto y coma. Cada declaración incluye una **propiedad** CSS (lo que quieres cambiar, como `color` o `font-size`) y un **valor** (cómo quieres cambiarla, como `blue` o `16px`).

**Sintaxis básica:**

```css
selector {
    propiedad: valor;
    propiedad-otra: valor-otro;
}
```

**Ejemplo:**

```css
/* Selector de tipo (todos los párrafos) */
p {
    color: blue; /* Propiedad: color, Valor: blue */
    font-size: 16px; /* Propiedad: font-size, Valor: 16px */
}
```

### 2.2. Empezar con CSS

Hay tres formas principales de incluir CSS en tus documentos HTML:

1.  **CSS en Línea (Inline CSS):** Aplicar estilos directamente a un elemento HTML usando el atributo `style`. No es recomendado para grandes proyectos debido a su falta de mantenibilidad.
    
    **Ejemplo:**
    
    ```html
    <p style="color: red; font-size: 18px;">Este es un párrafo con estilo en línea.</p>
    ```
    
2.  **CSS Interno (Internal CSS):** Colocar las reglas CSS dentro de la etiqueta `<style>` en la sección `<head>` de tu documento HTML. Útil para estilos específicos de una sola página.
    
    **Ejemplo:**
    
    ```html
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>CSS Interno</title>
        <style>
            h1 {
                color: green;
                text-align: center;
            }
            body {
                background-color: lightgray;
            }
        </style>
    </head>
    <body>
        <h1>Mi Título Estilizado</h1>
        <p>Este es un párrafo.</p>
    </body>
    </html>
    ```
    
3.  **CSS Externo (External CSS):** La forma más común y recomendada. Los estilos se definen en un archivo `.css` separado y se enlazan al documento HTML usando la etiqueta `<link>` en la sección `<head>`. Esto permite reutilizar el mismo archivo CSS en múltiples páginas.
    
    - **Archivo `styles.css`:**
      
        ```css
        body {
            font-family: Arial, sans-serif;
        }
        
        h2 {
            color: purple;
        }
        ```
        
    - **Archivo `index.html`:**
      
        ```html
        <!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <title>CSS Externo</title>
            <link rel="stylesheet" href="styles.css"> </head>
        <body>
            <h2>Mi subtítulo</h2>
            <p>Este es un párrafo.</p>
        </body>
        </html>
        ```
        

### 2.3. Selectores CSS

Los selectores son patrones que coinciden con elementos en el árbol de elementos de un documento, y a los cuales se les aplicarán las reglas CSS.

- **Selector de Tipo (Elemento):** Selecciona todos los elementos HTML de un tipo específico.
  
    ```css
    p {
        background-color: yellow;
    }
    a {
        text-decoration: none;
    }
    ```
    
- **Selector de Clase:** Selecciona elementos con un atributo `class` específico. Se prefiere para aplicar estilos a varios elementos. Se usa con un punto (`.`).
  
    **HTML:** `<p class="destacado">Texto importante</p>`  
    **CSS:**
    
    ```css
    .destacado {
        font-weight: bold;
        color: orange;
    }
    ```
    
- **Selector de ID:** Selecciona un único elemento con un atributo `id` específico. Los IDs deben ser únicos en una página. Se usa con una almohadilla (`#`).
  
    **HTML:** `<h1 id="titulo-principal">Mi Título</h1>`  
    **CSS:**
    
    ```css
    #titulo-principal {
        font-family: Georgia, serif;
        text-shadow: 2px 2px 5px gray;
    }
    ```
    
- **Selector Universal (`*`):** Selecciona todos los elementos de la página. Útil para restablecer estilos o aplicar estilos generales.
  
    ```css
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box; /* Muy común para el modelo de caja */
    }
    ```
    

### 2.4. Selectores de Atributos

Los selectores de atributos permiten seleccionar elementos basados en la presencia o el valor de sus atributos.

- **`[atributo]`:** Selecciona elementos que tienen un atributo específico.
  
    ```css
    /* Selecciona todos los elementos con el atributo 'title' */
    [title] {
        border: 1px dashed blue;
    }
    ```
    
- **`[atributo="valor"]`:** Selecciona elementos donde el atributo tiene un valor exacto.
  
    ```css
    /* Selecciona los inputs de tipo 'text' */
    input[type="text"] {
        background-color: lightblue;
    }
    ```
    
- **`[atributo~="valor"]`:** Selecciona elementos donde el valor del atributo contiene una palabra específica separada por espacios.
  
    ```css
    /* Selecciona elementos con la clase 'item' que también tienen la palabra 'especial' en su atributo 'data-info' */
    [data-info~="especial"] {
        font-style: italic;
    }
    ```
    
- **`[atributo^="valor"]`:** Selecciona elementos donde el valor del atributo comienza con un prefijo específico.
  
    ```css
    /* Selecciona enlaces que empiezan con "https" */
    a[href^="https"] {
        color: green;
    }
    ```
    
- **`[atributo$="valor"]`:** Selecciona elementos donde el valor del atributo termina con un sufijo específico.
  
    ```css
    /* Selecciona enlaces a archivos PDF */
    a[href$=".pdf"] {
        background-image: url('pdf-icon.png');
        background-repeat: no-repeat;
        padding-left: 20px;
    }
    ```
    
- **`[atributo*="valor"]`:** Selecciona elementos donde el valor del atributo contiene una subcadena específica en cualquier lugar.
  
    ```css
    /* Selecciona elementos cuyo 'alt' contenga la palabra 'logo' */
    img[alt*="logo"] {
        border: 3px solid gold;
    }
    ```
    

### 2.5. Pseudo-clases y Pseudo-elementos

Ambos se utilizan para aplicar estilos a un estado o una parte específica de un elemento, sin necesidad de modificar el HTML.

- **Pseudo-clases (`:`)**: Se utilizan para seleccionar elementos basándose en su estado, como cuando un usuario interactúa con ellos o en su posición en el documento.
  
    - `:hover`: Cuando el ratón pasa por encima.
    - `:active`: Cuando se hace clic.
    - `:focus`: Cuando un elemento de formulario está seleccionado.
    - `:visited`: Un enlace que ya ha sido visitado.
    - `:first-child`: El primer hijo de su padre.
    - `:last-child`: El último hijo de su padre.
    - `:nth-child(n)`: El enésimo hijo de su padre (n puede ser un número, `odd`, `even`, o una fórmula como `2n+1`).
    
    **Ejemplos:**
    
    ```css
    /* Cambia el color de un enlace al pasar el ratón */
    a:hover {
        color: orange;
    }
    
    /* Aplica un borde a un input cuando está enfocado */
    input:focus {
        border: 2px solid blue;
    }
    
    /* Estiliza el primer párrafo dentro de un div */
    div p:first-child {
        font-weight: bold;
    }
    ```
    
- **Pseudo-elementos (`::`)**: Se utilizan para estilizar partes específicas de un elemento que no son elementos HTML reales en sí mismos.
  
    - `::before`: Inserta contenido antes del contenido de un elemento.
    - `::after`: Inserta contenido después del contenido de un elemento.
    - `::first-line`: Estiliza la primera línea de un bloque de texto.
    - `::first-letter`: Estiliza la primera letra de un bloque de texto.
    - `::selection`: Estiliza el texto seleccionado por el usuario.
    
    **Ejemplos:**
    
    ```css
    /* Añade un icono antes de cada enlace externo */
    a[href^="http"]::before {
        content: "🔗 "; /* Unicode de un enlace */
    }
    
    /* Estiliza la primera letra de un párrafo */
    p::first-letter {
        font-size: 2em;
        font-weight: bold;
        color: #333;
    }
    
    /* Cambia el color del fondo cuando el usuario selecciona texto */
    ::selection {
        background-color: yellow;
        color: black;
    }
    ```
    

### 2.6. Combinadores

Los combinadores son caracteres especiales que se utilizan entre selectores para crear relaciones más complejas entre ellos.

- **Combinador Descendente (espacio):** Selecciona un elemento que es descendiente de otro (en cualquier nivel).
  
    ```css
    /* Selecciona todos los enlaces dentro de un div */
    div a {
        color: purple;
    }
    ```
    
- **Combinador de Hijo Directo (`>`):** Selecciona un elemento que es hijo directo de otro.
  
    ```css
    /* Selecciona solo los 'li' que son hijos directos de 'ul' */
    ul > li {
        list-style-type: square;
    }
    ```
    
- **Combinador de Hermano Adyacente (`+`):** Selecciona un elemento que es adyacente (justo después) de otro y comparte el mismo padre.
  
    ```css
    /* Selecciona el párrafo que viene inmediatamente después de un h1 */
    h1 + p {
        margin-top: 20px;
        font-style: italic;
    }
    ```
    
- **Combinador de Hermanos Generales (`~`):** Selecciona un elemento que es hermano (en cualquier posición posterior) de otro y comparte el mismo padre.
  
    ```css
    /* Selecciona todos los párrafos que aparecen después de un h2 en el mismo nivel */
    h2 ~ p {
        text-indent: 1em; /* Sangría */
    }
    ```
    

### 2.7. Modelo de Caja

Cada elemento HTML en una página web se considera una "caja". El **modelo de caja CSS** describe cómo estas cajas están estructuradas y cómo interactúan entre sí. Entender el modelo de caja es fundamental para el diseño y el layout.

Cada caja tiene las siguientes propiedades, de dentro hacia afuera:

1.  **`content` (Contenido):** El área donde se muestra el contenido real (texto, imágenes, video, etc.).
2.  **`padding` (Relleno):** Espacio transparente entre el contenido y el borde. Aumenta el tamaño visible del elemento.
3.  **`border` (Borde):** Una línea que rodea el padding y el contenido.
4.  **`margin` (Margen):** Espacio transparente fuera del borde. Separa el elemento de otros elementos.

**Propiedades para controlar el modelo de caja:**

- **`width` / `height`:** Dimensiones del área de contenido.
- **`padding`:** `padding-top`, `padding-right`, `padding-bottom`, `padding-left` (o shorthand `padding: top right bottom left;`).
- **`border`:** `border-width`, `border-style`, `border-color` (o shorthand `border: width style color;`).
- **`margin`:** `margin-top`, `margin-right`, `margin-bottom`, `margin-left` (o shorthand `margin: top right bottom left;`).

**Ejemplo:**

```html
<div class="caja-ejemplo">Contenido de la caja</div>
```

```css
.caja-ejemplo {
    width: 200px;
    height: 100px;
    background-color: lightgreen;
    padding: 20px; /* Relleno de 20px en todos los lados */
    border: 5px solid blue; /* Borde sólido azul de 5px */
    margin: 30px; /* Margen de 30px en todos los lados */
}
```

**`box-sizing`:** Una propiedad importante que cambia cómo se calcula el `width` y `height`.

- `content-box` (por defecto): `width/height` solo incluyen el contenido. Padding y borde se añaden al tamaño total.
  
- `border-box`: `width/height` incluyen el contenido, el padding y el borde. Es más intuitivo para el diseño. Es muy común usarlo globalmente:
  
    ```css
    html {
        box-sizing: border-box;
    }
    *, *::before, *::after {
        box-sizing: inherit;
    }
    ```
    

### 2.8. Cascada y Herencia

Estas dos características son fundamentales para entender cómo CSS aplica los estilos.

- **Cascada:** Determina qué reglas CSS se aplican cuando múltiples reglas apuntan al mismo elemento. El navegador sigue un proceso para resolver conflictos:
  
    1.  **Origen del Estilo:** Estilos del navegador, estilos del usuario, estilos del autor (tu CSS). Los tuyos tienen más peso.
    2.  **Especificidad:** Cuanto más específico sea un selector, mayor será su peso.
        - ID (`#id`) > Clase (`.clase`) > Elemento (`p`)
        - Estilos en línea (`style=""`) son los más específicos.
    3.  **Orden de Declaración:** Si la especificidad es la misma, la última regla declarada gana.
    4.  **`!important`:** Una palabra clave que anula la cascada, pero su uso **no es recomendado** ya que dificulta el mantenimiento del código.
    
    **Ejemplo de Cascada:**
    
    ```html
    <p class="texto" id="miParrafo">Hola, mundo!</p>
    ```
    
    ```css
    /* 1. Menos específico, pero primero en orden */
    p {
        color: red;
    }
    
    /* 2. Más específico que 'p', pero menos que '#miParrafo' */
    .texto {
        color: blue; /* Este ganaría al 'p' */
    }
    
    /* 3. El más específico, este color se aplicará */
    #miParrafo {
        color: green;
    }
    ```
    
    El párrafo será de color **verde**.
    
- **Herencia:** Algunas propiedades CSS (como `color`, `font-family`, `font-size`, `line-height`, `text-align`) se heredan automáticamente de un elemento padre a sus elementos hijos. Esto evita tener que aplicar las mismas propiedades a cada elemento hijo.
  
    **Ejemplo de Herencia:**
    
    ```html
    <div style="color: purple; font-family: sans-serif;">
        <p>Este párrafo heredará el color y la fuente del div.</p>
        <span>Y este span también.</span>
    </div>
    ```
    
    Ambos, el párrafo y el span, serán de color púrpura y usarán la fuente `sans-serif`.
    
    Puedes controlar la herencia con propiedades como:
    
    - `inherit`: Fuerza a un elemento a heredar el valor de su padre.
    - `initial`: Restablece la propiedad a su valor inicial por defecto del navegador.
    - `unset`: Si la propiedad es heredable, se comporta como `inherit`. Si no, se comporta como `initial`.

### 2.9. Valores y Unidades CSS

CSS utiliza diferentes tipos de valores y unidades para especificar tamaños, colores y otras propiedades.

- **Unidades de Longitud:**
  
    - **Unidades Absolutas (fijas):**
        - `px` (píxeles): La más común, pero no es adaptable a diferentes tamaños de pantalla.
        - `pt` (puntos): Para medios impresos.
        - `cm`, `mm`, `in` (centímetros, milímetros, pulgadas).
    - **Unidades Relativas (adaptables):**
        - `%` (porcentaje): Relativo al tamaño del padre.
        - `em`: Relativo al `font-size` del elemento padre.
        - `rem`: Relativo al `font-size` del elemento raíz (`<html>`). Preferido para tipografía adaptable.
        - `vw` (viewport width): Relativo al ancho del viewport (ventana del navegador). `1vw` es el 1% del ancho del viewport.
        - `vh` (viewport height): Relativo a la altura del viewport. `1vh` es el 1% de la altura del viewport.
        - `vmin`, `vmax`: Relativo al menor/mayor de `vw` o `vh`.
    
    **Ejemplo:**
    
    ```css
    .caja {
        width: 50%; /* 50% del ancho del padre */
        padding: 1em; /* 1 vez el font-size del elemento */
        font-size: 16px;
    }
    h1 {
        font-size: 3rem; /* 3 veces el font-size de <html> */
    }
    .hero-section {
        height: 100vh; /* Ocupa el 100% de la altura del viewport */
    }
    ```
    
- **Unidades de Ángulo:** `deg` (grados), `grad` (gradianes), `rad` (radianes), `turn` (giros). Usado en transformaciones.
  
    ```css
    .rotar {
        transform: rotate(45deg);
    }
    ```
    
- **Valores de Color:**
  
    - Nombres de color: `red`, `blue`, `green`, `black`, `white`, etc.
    - Hexadecimal: `#RRGGBB` o `#RGB` (ej: `#FF0000` para rojo, `#336699`).
    - RGB: `rgb(red, green, blue)` (ej: `rgb(255, 0, 0)`). También `rgba` para incluir transparencia (Alpha).
    - HSL: `hsl(hue, saturation, lightness)` (ej: `hsl(0, 100%, 50%)` para rojo). También `hsla`.
    
    **Ejemplo:**
    
    ```css
    h2 {
        color: #008000; /* Verde */
        background-color: rgba(255, 255, 0, 0.5); /* Amarillo con 50% de opacidad */
    }
    ```
    

### 2.10. Dimensionar Elementos CSS

Controlar el tamaño de los elementos es fundamental para el layout.

- **`width` / `height`:** Establece el ancho y alto del área de contenido.
  
    ```css
    .imagen-pequena {
        width: 150px;
        height: auto; /* Mantiene la proporción de la imagen */
    }
    ```
    
- **`max-width` / `max-height`:** Establece el ancho/alto máximo que un elemento puede tener. Es muy útil para el diseño adaptable.
  
    ```css
    img {
        max-width: 100%; /* La imagen nunca será más grande que su contenedor */
        height: auto;
    }
    ```
    
- **`min-width` / `min-height`:** Establece el ancho/alto mínimo que un elemento debe tener.
  
    ```css
    .contenedor {
        min-height: 200px; /* Asegura que el contenedor tenga al menos 200px de alto */
    }
    ```
    
- **`box-sizing`** (repaso): Ya visto en el modelo de caja, pero es clave aquí para cómo se calculan las dimensiones.
  

### 2.11. Fondos y Bordes

Estilizar el fondo y los bordes de los elementos es crucial para la apariencia visual.

- **Fondos (`background` shorthand o propiedades individuales):**
  
    - `background-color`: Color de fondo.
    - `background-image`: Imagen de fondo.
    - `background-repeat`: Cómo se repite la imagen (`repeat`, `no-repeat`, `repeat-x`, `repeat-y`).
    - `background-position`: Posición de la imagen (`center`, `top left`, `50% 50%`).
    - `background-size`: Tamaño de la imagen de fondo (`cover`, `contain`, `auto`, `longitud`).
    - `background-attachment`: Si la imagen de fondo se desplaza o es fija (`scroll`, `fixed`, `local`).
    
    **Ejemplo:**
    
    ```css
    body {
        background-color: #f0f0f0;
        background-image: url('pattern.png');
        background-repeat: repeat-x;
        background-position: center top;
    }
    
    .hero-banner {
        background: url('hero-bg.jpg') no-repeat center center / cover; /* Shorthand */
        color: white;
        padding: 50px;
    }
    ```
    
- **Bordes (`border` shorthand o propiedades individuales):**
  
    - `border-width`: Grosor del borde.
    - `border-style`: Estilo del borde (`solid`, `dashed`, `dotted`, `double`, `none`).
    - `border-color`: Color del borde.
    - `border-radius`: Esquinas redondeadas.
    
    **Ejemplo:**
    
    ```css
    .tarjeta {
        border: 2px solid #ccc; /* Shorthand */
        border-radius: 8px; /* Esquinas redondeadas */
        padding: 15px;
        margin: 10px;
    }
    
    .boton {
        border-top: 1px dashed blue; /* Borde solo arriba */
        border-bottom-left-radius: 10px; /* Solo una esquina redondeada */
    }
    ```
    

### 2.12. Contenido Desbordado

Cuando el contenido de un elemento es demasiado grande para su contenedor, se "desborda". La propiedad `overflow` controla cómo se maneja este desbordamiento.

- `visible` (por defecto): El contenido desbordado es visible y sobresale del contenedor.
- `hidden`: El contenido desbordado es recortado y no es visible.
- `scroll`: Se añaden barras de desplazamiento (scrollbars) para ver todo el contenido, incluso si no es necesario.
- `auto`: Se añaden barras de desplazamiento solo si el contenido se desborda. Es la opción más común y flexible.

**Ejemplo:**

```html
<div class="caja-desborde">
    <p>Este es un párrafo muy largo que intentará desbordar su contenedor.
    Si el contenedor es pequeño, parte de este texto podría no ser visible
    o aparecerán barras de desplazamiento, dependiendo de la propiedad overflow.</p>
</div>
```

```css
.caja-desborde {
    width: 200px;
    height: 100px;
    border: 1px solid black;
    overflow: auto; /* Las barras de desplazamiento aparecerán si el contenido es demasiado largo */
    /* overflow: hidden; */ /* El contenido se cortará */
}
```

### 2.13. Imágenes, Medios y Elementos de Formularios

Cómo estilizar estos elementos específicos.

- **Imágenes:**
  
    - Controlar tamaño con `width`, `height`, `max-width`.
    - Alineación con `float` (para texto alrededor) o `display: block; margin: 0 auto;` (para centrar).
    - `object-fit`: Controla cómo una imagen o video se ajusta a su contenedor. (`fill`, `contain`, `cover`, `none`, `scale-down`).
    
    **Ejemplo:**
    
    ```css
    img {
        max-width: 100%; /* Imagenes responsivas */
        height: auto;
        display: block; /* Elimina espacio extra debajo de las imágenes */
    }
    .perfil-avatar {
        width: 100px;
        height: 100px;
        border-radius: 50%; /* Hace la imagen circular */
        object-fit: cover; /* Asegura que la imagen cubra el área sin distorsionarse */
    }
    ```
    
- **Medios (`<audio>`, `<video>`):**
  
    - Son elementos reemplazados, el navegador suele darles controles por defecto.
    - Se pueden estilizar como cualquier otro bloque, pero sus controles son difíciles de personalizar solo con CSS. Se usa JavaScript para eso.
    - `width`, `height`, `max-width` son las más comunes.
    
    **Ejemplo:**
    
    ```css
    video {
        max-width: 100%;
        height: auto;
        display: block;
        border: 2px solid #333;
    }
    ```
    
- **Elementos de Formularios (`<input>`, `<textarea>`, `<select>`, `<button>`):**
  
    - Son notorios por ser inconsistentes entre navegadores y sistemas operativos.
    - Se pueden estilizar con propiedades como `border`, `background`, `padding`, `font`, `color`, `width`, `height`.
    - Para una consistencia total y diseños complejos, a menudo se necesitan librerías de componentes o técnicas avanzadas.
    - `appearance: none;` puede ayudar a quitar estilos predeterminados del navegador.
    
    **Ejemplo:**
    
    ```css
    input[type="text"],
    input[type="email"],
    textarea,
    select {
        width: 100%;
        padding: 10px;
        margin-bottom: 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box; /* Muy importante para inputs con width: 100% */
    }
    
    button[type="submit"] {
        background-color: #007bff;
        color: white;
        padding: 12px 20px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 16px;
    }
    
    button[type="submit"]:hover {
        background-color: #0056b3;
    }
    ```
    

### 2.14. Tablas

Las tablas HTML pueden ser difíciles de estilizar debido a su estructura.

- `border-collapse`: Colapsa los bordes de las celdas de la tabla.
- `text-align`: Alineación del texto en las celdas (`left`, `right`, `center`).
- `vertical-align`: Alineación vertical del contenido en las celdas (`top`, `middle`, `bottom`).
- `padding`: Espaciado dentro de las celdas.
- `width`: Ancho de la tabla o columnas.
- `nth-child(even)` / `nth-child(odd)`: Para aplicar estilos a filas alternas (cebreado).

**Ejemplo:**

```html
<table>
    <thead>
        <tr><th>Producto</th><th>Precio</th></tr>
    </thead>
    <tbody>
        <tr><td>Laptop</td><td>1200€</td></tr>
        <tr><td>Ratón</td><td>25€</td></tr>
        <tr><td>Teclado</td><td>75€</td></tr>
    </tbody>
</table>
```

```css
table {
    width: 100%;
    border-collapse: collapse; /* Colapsa los bordes */
    margin: 20px 0;
}

th, td {
    border: 1px solid #ddd;
    padding: 8px;
    text-align: left;
}

th {
    background-color: #f2f2f2;
    color: #333;
}

/* Filas alternas (cebreado) */
tr:nth-child(even) {
    background-color: #f9f9f9;
}

/* Efecto hover en filas */
tr:hover {
    background-color: #e2e2e2;
}
```

### 2.15. Depurar CSS

Depurar CSS es el proceso de encontrar y corregir errores en tus estilos. Las **herramientas de desarrollador del navegador** son tu mejor aliado.

- **Inspector de Elementos:**
  
    - Haz clic derecho en cualquier elemento de tu página y selecciona "Inspeccionar" (o "Inspeccionar elemento").
    - Verás el HTML a la izquierda y el CSS aplicado al elemento seleccionado a la derecha.
    - Puedes ver qué reglas CSS se están aplicando, de dónde vienen (archivo y línea), y si alguna propiedad está siendo anulada por otra (tachada).
    - **Modifica CSS en tiempo real:** Puedes cambiar valores, añadir o quitar propiedades directamente en el inspector para ver el efecto al instante, sin guardar cambios en tu archivo.
    - **Activar/Desactivar reglas:** Puedes marcar/desmarcar casillas para ver el efecto de reglas individuales.
    - **Calcular el Modelo de Caja:** En la pestaña "Computed" o "Calculado", puedes ver el modelo de caja de un elemento, incluyendo su contenido, padding, borde y margen calculados.
- **Consola:** Aunque más para JavaScript, puede mostrar errores relacionados con la carga de archivos CSS o problemas de sintaxis.
  
- **Pestaña "Network" (Red):** Verifica si tus archivos CSS se están cargando correctamente (código de estado 200 OK) y cuánto tardan en cargar.
  
- **Pestaña "Styles" (Estilos):** Muestra todas las reglas CSS que afectan al elemento seleccionado, ordenadas por especificidad.
  

**Ejemplo de flujo de depuración:**

1.  **Problema:** Un botón no tiene el color esperado.
2.  **Acción:** Inspecciona el botón.
3.  **Observación en el Inspector:**
    - ¿Hay alguna regla que le dé el color?
    - ¿Está la regla tachada? Esto indica que otra regla con mayor especificidad o posterior en la cascada la está anulando.
    - ¿Cuál es la especificidad del selector del botón? ¿Hay un selector de ID que tiene prioridad?
    - ¿Hay un `!important` en algún lado que esté causando conflicto?
    - ¿El archivo CSS está enlazado correctamente? (Pestaña "Network").

La práctica constante con estas herramientas te hará un depurador eficiente.

### 2.16. CSS Texto

Controlar la tipografía es crucial para la legibilidad y estética de tu sitio.

#### 2.16.1. Fuentes

- **`font-family`:** Especifica la fuente a usar. Se recomienda una lista de fuentes de reserva (fallback fonts) por si la primera no está disponible.
  
    ```css
    body {
        font-family: "Helvetica Neue", Arial, sans-serif;
    }
    h1 {
        font-family: Georgia, serif;
    }
    ```
    
- **`font-size`:** Tamaño de la fuente (en `px`, `em`, `rem`, `%`, etc.).
  
    ```css
    p {
        font-size: 16px;
    }
    h2 {
        font-size: 2.5rem;
    }
    ```
    
- **`font-weight`:** Grosor de la fuente (`normal`, `bold`, `lighter`, `bolder` o valores numéricos de 100 a 900).
  
    ```css
    .negrita {
        font-weight: bold;
    }
    .delgada {
        font-weight: 300;
    }
    ```
    
- **`font-style`:** Estilo de la fuente (`normal`, `italic`, `oblique`).
  
    ```css
    em {
        font-style: italic;
    }
    ```
    
- **`line-height`:** Espacio entre líneas (interlineado).
  
    ```css
    p {
        line-height: 1.5; /* 1.5 veces el font-size */
    }
    ```
    
- **`text-align`:** Alineación horizontal del texto (`left`, `right`, `center`, `justify`).
  
    ```css
    h1 {
        text-align: center;
    }
    .justificado {
        text-align: justify;
    }
    ```
    
- **`color`:** Color del texto.
  
    ```css
    body {
        color: #333;
    }
    ```
    
- **`text-decoration`:** Añade o quita líneas al texto (`none`, `underline`, `overline`, `line-through`).
  
    ```css
    a {
        text-decoration: none; /* Quita el subrayado de los enlaces */
    }
    .subrayado {
        text-decoration: underline;
    }
    ```
    
- **`text-transform`:** Transforma el texto (`none`, `uppercase`, `lowercase`, `capitalize`).
  
    ```css
    .mayusculas {
        text-transform: uppercase;
    }
    ```
    
- **`letter-spacing`:** Espacio entre caracteres.
  
- **`word-spacing`:** Espacio entre palabras.
  
    ```css
    h3 {
        letter-spacing: 2px;
        word-spacing: 5px;
    }
    ```
    

#### 2.16.2. Listas

Estilizar las viñetas y numeraciones de las listas.

- **`list-style-type`:** Tipo de marcador (`none`, `disc`, `circle`, `square`, `decimal`, `lower-alpha`, etc.).
  
- **`list-style-image`:** Usar una imagen como marcador.
  
- **`list-style-position`:** Posición del marcador (`inside`, `outside`).
  
- **`list-style` (shorthand):** Combina las propiedades anteriores.
  
    **Ejemplo:**
    
    ```css
    ul {
        list-style-type: none; /* Elimina las viñetas por defecto */
        padding-left: 0; /* Elimina el padding por defecto */
    }
    
    li {
        background-image: url('bullet.png');
        background-repeat: no-repeat;
        background-position: left center;
        padding-left: 20px; /* Espacio para la imagen de viñeta */
        margin-bottom: 5px;
    }
    
    ol {
        list-style-type: upper-roman; /* Números romanos en mayúscula */
    }
    ```
    

#### 2.16.3. Enlaces

Los enlaces tienen estados específicos que se estilizan con pseudo-clases.

- `a:link`: Enlace no visitado.
- `a:visited`: Enlace visitado.
- `a:hover`: Cuando el ratón pasa por encima.
- `a:active`: Cuando se hace clic en el enlace (mientras se presiona el botón del ratón).

Es una buena práctica definirlos en este orden (LoVe HAte): Link, Visited, Hover, Active.

**Ejemplo:**

```css
a {
    color: #007bff; /* Color por defecto */
    text-decoration: none; /* Sin subrayado por defecto */
}

a:hover {
    color: #0056b3;
    text-decoration: underline; /* Subrayado al pasar el ratón */
}

a:visited {
    color: #6610f2; /* Un color diferente para enlaces visitados */
}

a:active {
    color: red; /* Color al hacer click */
}
```

#### 2.16.4. Fuentes Web (Web Fonts)

Permiten usar fuentes personalizadas que no están instaladas en el sistema del usuario, cargándolas desde un servidor.

- **Google Fonts:** La forma más popular y sencilla.
  
    1.  Elige las fuentes en [fonts.google.com](https://fonts.google.com/).
    2.  Copia el código `<link>` que te proporcionan y pégalo en la sección `<head>` de tu HTML.
    3.  Usa la `font-family` en tu CSS como cualquier otra fuente.
    
    **Ejemplo (en `index.html`):**
    
    ```html
    <head>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    </head>
    ```
    
    **Ejemplo (en `styles.css`):**
    
    ```css
    body {
        font-family: 'Roboto', sans-serif;
    }
    h1 {
        font-family: 'Roboto', sans-serif;
        font-weight: 700; /* Usa el peso 700 que importaste */
    }
    ```
    
- **`@font-face` (para fuentes alojadas localmente o en tu propio servidor):** Permite importar archivos de fuentes (`.woff`, `.ttf`, `.otf`, etc.) directamente en tu CSS.
  
    **Ejemplo:**
    
    ```css
    @font-face {
        font-family: 'MiFuentePersonalizada';
        src: url('fonts/MiFuentePersonalizada-Regular.woff2') format('woff2'),
             url('fonts/MiFuentePersonalizada-Regular.woff') format('woff');
        font-weight: normal;
        font-style: normal;
    }
    
    h1 {
        font-family: 'MiFuentePersonalizada', Arial, sans-serif;
    }
    ```
    
    Asegúrate de que los archivos de la fuente estén en la ruta correcta (`fonts/` en este caso).
    

* * *

### 2.17\. CSS Layout

#### 2.17.1. Introducción a CSS Layout

CSS Layout se refiere a las técnicas y propiedades de CSS que te permiten controlar cómo se posicionan y distribuyen los elementos en una página web. Es una parte fundamental del diseño web, ya que define la estructura visual y la experiencia del usuario. Históricamente, el diseño web ha evolucionado, y lo que antes era un reto (como centrar un elemento) ahora es mucho más sencillo con herramientas modernas como Flexbox y CSS Grid.

A lo largo de esta sección, exploraremos las principales herramientas de diseño que CSS nos ofrece: desde métodos más antiguos pero aún relevantes como `float` y `positioning`, hasta los sistemas modernos y potentes como Flexbox y CSS Grid, culminando con el diseño responsivo usando Media Queries.

#### 2.17.2. Float

La propiedad `float` (flotar) fue originalmente diseñada para permitir que los elementos de texto "flotaran" alrededor de imágenes, como en una revista. Aunque ha sido ampliamente utilizada para crear diseños de columnas, no es su propósito principal y tiene algunas peculiaridades. Es importante conocerla, pero para diseños complejos, **Flexbox y Grid son las opciones preferidas**.

- **`float: left;`**: El elemento flota a la izquierda de su contenedor, y el contenido siguiente lo rodea por la derecha.
  
- **`float: right;`**: El elemento flota a la derecha de su contenedor, y el contenido siguiente lo rodea por la izquierda.
  
- **`float: none;`**: Valor por defecto, el elemento no flota.
  

**El problema del "clearing floats":** Cuando usas `float`, el contenedor padre no "ve" la altura de los elementos flotantes, lo que puede hacer que colapse. Para solucionarlo, se usa `clear`.

- **`clear: both;`**: Un elemento con `clear: both;` se colocará debajo de cualquier elemento flotante a su izquierda o derecha.

**Ejemplo:**

HTML

```html
<div class="contenedor-flotante">
    <img src="imagen.jpg" alt="Imagen flotante" class="imagen-flotante">
    <p>Este es un párrafo de texto que rodeará la imagen flotante. El texto fluirá alrededor de la imagen, ocupando el espacio disponible a su lado.</p>
    <p>Otro párrafo que también se verá afectado por el float.</p>
    <div class="clear"></div> </div>
<p>Este párrafo está fuera del contenedor flotante y después del clear, por lo que no será afectado por el float de la imagen anterior.</p>
```

CSS

```css
.contenedor-flotante {
    border: 1px solid #ccc;
    padding: 10px;
    /* overflow: auto;  Una alternativa para limpiar el float en el padre */
}

.imagen-flotante {
    float: left;
    margin-right: 15px;
    width: 150px;
    height: auto;
}

.clear {
    clear: both; /* Asegura que los elementos subsiguientes no floten al lado de la imagen */
}
```

#### 2.17.3. Posicionamiento

La propiedad `position` te permite controlar la colocación exacta de un elemento en el documento. A menudo se usa en combinación con las propiedades `top`, `right`, `bottom` y `left`.

- **`position: static;` (Por defecto):** El elemento se coloca según el flujo normal del documento. `top`, `right`, `bottom`, `left` no tienen efecto.
  
- **`position: relative;`**: El elemento se coloca según el flujo normal, pero luego puedes ajustarlo con `top`, `right`, `bottom`, `left` **con respecto a su posición original**. Otros elementos no se ven afectados por este desplazamiento.
  
    **Ejemplo `relative`:**
    
    HTML
    
    ```html
    <div class="caja-contenedor">
        <div class="caja-relativa">Caja Relativa</div>
    </div>
    ```
    
    CSS
    
    ```css
    .caja-contenedor {
        border: 1px solid gray;
        padding: 20px;
        height: 100px;
    }
    .caja-relativa {
        position: relative;
        top: 10px;
        left: 20px; /* Se desplaza 10px hacia abajo y 20px a la derecha de su posición normal */
        background-color: lightblue;
        width: 80px;
        height: 50px;
    }
    ```
    
- **`position: absolute;`**: El elemento es **removido del flujo normal** del documento. Se posiciona con `top`, `right`, `bottom`, `left` **con respecto a su ancestro posicionado más cercano** (cualquier padre con `position: relative`, `absolute`, `fixed`, o `sticky`). Si no hay un ancestro posicionado, se posiciona con respecto al `<html>` (o `<body>`).
  
    **Ejemplo `absolute`:**
    
    HTML
    
    ```html
    <div class="padre-relativo">
        Contenedor Padre
        <div class="caja-absoluta">Caja Absoluta</div>
    </div>
    <p>Este es un párrafo que no se ve afectado por la caja absoluta.</p>
    ```
    
    CSS
    
    ```css
    .padre-relativo {
        position: relative; /* Es importante que el padre tenga position (relative, absolute, fixed, sticky) */
        width: 300px;
        height: 200px;
        border: 2px solid green;
        padding: 10px;
    }
    .caja-absoluta {
        position: absolute;
        top: 20px;
        right: 20px; /* Se posiciona a 20px del top y 20px del right del padre relativo */
        background-color: lightcoral;
        width: 100px;
        height: 70px;
    }
    ```
    
- **`position: fixed;`**: El elemento es **removido del flujo normal** y se posiciona **con respecto a la ventana gráfica del navegador (viewport)**. Permanece en la misma posición incluso cuando el usuario se desplaza. Útil para barras de navegación fijas o botones de "volver arriba".
  
    **Ejemplo `fixed`:**
    
    HTML
    
    ```html
    <div class="barra-fija">Barra de Navegación Fija</div>
    <div style="height: 1500px; background-color: #f0f0f0; padding-top: 50px;">
        Desplázate hacia abajo para ver la barra fija.
    </div>
    ```
    
    CSS
    
    ```css
    .barra-fija {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        background-color: #333;
        color: white;
        padding: 10px 0;
        text-align: center;
        z-index: 1000; /* Para asegurar que esté por encima de otros elementos */
    }
    ```
    
- **`position: sticky;`**: Es un híbrido de `relative` y `fixed`. Se comporta como `relative` hasta que el elemento alcanza un cierto umbral de desplazamiento, momento en el que se comporta como `fixed` con respecto al viewport.
  
    **Ejemplo `sticky`:**
    
    HTML
    
    ```html
    <div style="height: 100px; background-color: #e0e0e0;">Espacio superior</div>
    <div class="barra-pegajosa">Barra Pegajosa</div>
    <div style="height: 800px; background-color: #f0f0f0;">Contenido principal para desplazamiento</div>
    ```
    
    CSS
    
    ```css
    .barra-pegajosa {
        position: sticky;
        top: 0; /* Se pegará en la parte superior del viewport cuando llegue a 0px de él */
        background-color: lightgreen;
        padding: 10px;
        border-bottom: 1px solid #ccc;
        z-index: 999;
    }
    ```
    

### 2.17.4. Flexbox

Flexbox es un módulo de diseño CSS unidimensional diseñado para organizar elementos en una sola dirección (fila o columna). Es ideal para distribuir espacio entre elementos de un contenedor y alinear ítems.

Para usar Flexbox, debes definir un **contenedor flex** y luego sus **ítems flex**.

**Propiedades del Contenedor Flex:**

- **`display: flex;`**: Convierte el elemento en un contenedor flex. Los hijos directos se convierten en ítems flex.
  
- **`flex-direction`**: Define la dirección del eje principal (`row` por defecto, `row-reverse`, `column`, `column-reverse`).
  
- **`justify-content`**: Alinea los ítems a lo largo del eje principal.
  
    - `flex-start`, `flex-end`, `center`, `space-between` (distribuye el espacio uniformemente entre ítems), `space-around`, `space-evenly`.
- **`align-items`**: Alinea los ítems a lo largo del eje cruzado (perpendicular al principal).
  
    - `flex-start`, `flex-end`, `center`, `baseline`, `stretch`.
- **`flex-wrap`**: Controla si los ítems se envuelven en múltiples líneas (`nowrap` por defecto, `wrap`, `wrap-reverse`).
  
- **`align-content`**: Alinea las líneas de ítems cuando hay múltiples líneas (similar a `justify-content` pero para el eje cruzado).
  
    - `flex-start`, `flex-end`, `center`, `space-between`, `space-around`, `stretch`.
- **`gap` (o `row-gap`, `column-gap`):** Espacio entre los ítems flex (antes se usaba `grid-gap`).
  

**Propiedades de los Ítems Flex:**

- **`flex-grow`**: Define la capacidad del ítem para crecer si hay espacio extra.
  
- **`flex-shrink`**: Define la capacidad del ítem para encogerse si no hay suficiente espacio.
  
- **`flex-basis`**: Define el tamaño inicial del ítem antes de que se distribuya el espacio.
  
- **`flex` (shorthand):** Combina `flex-grow`, `flex-shrink`, `flex-basis` (ej: `flex: 1 1 auto;`).
  
- **`order`**: Controla el orden visual de los ítems (por defecto 0).
  
- **`align-self`**: Anula `align-items` para un ítem individual.
  

**Ejemplo de Flexbox:**

HTML

```html
<div class="contenedor-flex">
    <div class="item-flex">1</div>
    <div class="item-flex">2</div>
    <div class="item-flex">3</div>
    <div class="item-flex">4</div>
</div>

<div class="contenedor-flex-vertical">
    <div class="item-flex">A</div>
    <div class="item-flex">B</div>
    <div class="item-flex">C</div>
</div>
```

CSS

```css
.contenedor-flex {
    display: flex; /* Convierte en contenedor flex */
    justify-content: space-around; /* Distribuye ítems con espacio alrededor */
    align-items: center; /* Centra verticalmente los ítems */
    border: 2px solid blue;
    padding: 10px;
    height: 100px;
    gap: 10px; /* Espacio entre los ítems */
}

.item-flex {
    background-color: lightgreen;
    padding: 15px;
    border: 1px solid darkgreen;
    flex-grow: 1; /* Permite que los ítems crezcan para llenar el espacio */
    text-align: center;
}

.contenedor-flex-vertical {
    display: flex;
    flex-direction: column; /* Organiza ítems en columna */
    justify-content: center; /* Centra verticalmente */
    align-items: flex-start; /* Alinea al inicio horizontalmente */
    border: 2px solid purple;
    padding: 10px;
    height: 200px;
    margin-top: 20px;
}
```

#### 2.17.5. Cuadrículas (Grid))

CSS Grid es un sistema de diseño CSS bidimensional que te permite organizar elementos en filas y columnas. Es ideal para crear estructuras de página complejas y rejillas completas.

Para usar Grid, defines un **contenedor de cuadrícula** y luego posicionas sus **ítems de cuadrícula**.

**Propiedades del Contenedor de Cuadrícula:**

- **`display: grid;`**: Convierte el elemento en un contenedor de cuadrícula.
  
- **`grid-template-columns`**: Define las columnas de la cuadrícula. Puedes usar:
  
    - Unidades absolutas o relativas (`100px 1fr 20%`).
      
    - `fr` (unidad fraccional): Distribuye el espacio disponible.
      
    - `repeat(cantidad, tamaño)`: Repite una definición de columna/fila.
      
    - `auto`: El navegador decide el tamaño.
      
    - `minmax(min, max)`: Rango de tamaño para una fila/columna.
    
- **`grid-template-rows`**: Define las filas de la cuadrícula (similar a `grid-template-columns`).
  
- **`grid-gap` (o `gap`, `row-gap`, `column-gap`):** Espacio entre las filas y columnas.
  
- **`justify-items`**: Alinea los ítems dentro de sus celdas a lo largo del eje de la fila (horizontalmente).
  
    - `start`, `end`, `center`, `stretch`.
- **`align-items`**: Alinea los ítems dentro de sus celdas a lo largo del eje de la columna (verticalmente).
  
    - `start`, `end`, `center`, `stretch`.
- **`justify-content`**: Alinea la cuadrícula entera dentro del contenedor si hay espacio extra.
  
- **`align-content`**: Alinea la cuadrícula entera dentro del contenedor si hay espacio extra.
  
- **`grid-template-areas`**: Permite nombrar áreas de la cuadrícula para un diseño más semántico.
  

**Propiedades de los Ítems de Cuadrícula:**

- **`grid-column-start` / `grid-column-end`**: Donde comienza y termina el ítem en las columnas.
  
- **`grid-row-start` / `grid-row-end`**: Donde comienza y termina el ítem en las filas.
  
- **`grid-column` (shorthand):** Combina start/end (ej: `grid-column: 1 / 3;`).
  
- **`grid-row` (shorthand):** Combina start/end (ej: `grid-row: 2 / span 2;`).
  
- **`grid-area`**: Si usas `grid-template-areas`, puedes nombrar el ítem para que ocupe un área específica.
  
- **`justify-self` / `align-self`**: Anula la alineación del contenedor para un ítem individual.
  

**Ejemplo de CSS Grid:**

HTML

```html
<div class="contenedor-grid">
    <div class="item-grid header">Header</div>
    <div class="item-grid sidebar">Sidebar</div>
    <div class="item-grid main">Main Content</div>
    <div class="item-grid footer">Footer</div>
</div>
```

CSS

```css
.contenedor-grid {
    display: grid;
    /* Define 3 columnas: 1 de 150px, 1 que toma 2 partes del espacio, 1 que toma 1 parte */
    grid-template-columns: 150px 2fr 1fr;
    /* Define 3 filas: 1 de auto, 1 de 300px, 1 de auto */
    grid-template-rows: auto 300px auto;
    gap: 15px; /* Espacio entre celdas */
    border: 2px solid orange;
    padding: 10px;

    /* Definición de áreas para un diseño más legible */
    grid-template-areas:
        "header header header"
        "sidebar main   main"
        "footer footer footer";
}

.item-grid {
    background-color: lightgoldenrodyellow;
    border: 1px solid gold;
    padding: 10px;
    text-align: center;
}

/* Asignar ítems a las áreas definidas */
.header {
    grid-area: header;
    background-color: lightgray;
}

.sidebar {
    grid-area: sidebar;
    background-color: lightblue;
}

.main {
    grid-area: main;
    background-color: lightcyan;
}

.footer {
    grid-area: footer;
    background-color: #eee;
}
```

#### 2.17.6. Diseño Responsive

El diseño responsive (adaptable) es una estrategia de diseño web que busca que las páginas web se vean y funcionen bien en una amplia variedad de dispositivos y tamaños de pantalla (escritorios, tablets, teléfonos móviles, etc.). En lugar de crear diseños separados para cada dispositivo, un diseño responsive se adapta de forma fluida.

Los principios clave son:

- **Rejillas fluidas (Fluid Grids):** Usar porcentajes (`%`) o unidades relativas (`em`, `rem`, `vw`, `vh`) para los anchos y alturas de los elementos en lugar de píxeles fijos. Esto permite que los elementos se ajusten automáticamente al tamaño del contenedor.
  
    - `img { max-width: 100%; height: auto; }` es fundamental para imágenes responsivas.
- **Imágenes flexibles (Flexible Images):** Las imágenes deben escalarse con el diseño.
  
- **Media Queries:** La herramienta principal para aplicar estilos condicionales basados en las características del dispositivo.
  

#### 2.17.7. Media Queries

Las Media Queries (consultas de medios) son una característica de CSS3 que te permite aplicar estilos diferentes a un documento basado en las características del dispositivo o la ventana de visualización (viewport) del usuario, como el ancho de la pantalla, la altura, la orientación o la resolución.

**Sintaxis básica:**

CSS

```css
@media media-type and (media-feature) {
    /* Reglas CSS para aplicar cuando la condición es verdadera */
}
```

- **`media-type`**: El tipo de medio (opcional, por defecto `all`).
  
    - `all`: Para todos los dispositivos.
      
    - `screen`: Para pantallas de computadora, tablets, smartphones, etc. (lo más común).
      
    - `print`: Para documentos que se van a imprimir.
    
- **`media-feature`**: La característica específica que estás consultando.
  
    - `width`, `height`: Ancho/altura del viewport.
      
    - `min-width`, `max-width`: Ancho mínimo/máximo del viewport.
      
    - `orientation`: Orientación del dispositivo (`portrait` o `landscape`).
      
    - `resolution`: Resolución de la pantalla.
      
    - `prefers-color-scheme`: Si el usuario prefiere un tema claro u oscuro.
      

**Puntos de ruptura (Breakpoints):** Son los anchos de pantalla específicos donde tu diseño cambia para adaptarse mejor. No hay un estándar universal, pero algunos comunes son:

- Pequeños dispositivos (móviles): hasta `576px`
  
- Tablets: `576px` a `768px`
  
- Escritorios pequeños: `768px` a `992px`
  
- Escritorios grandes: `992px` a `1200px`
  
- Escritorios extra grandes: más de `1200px`
  

**Ejemplo de Media Queries:**

CSS

```css
/* Estilos por defecto para móviles (Mobile First) */
body {
    background-color: lightblue;
    font-size: 16px;
}

.contenedor {
    width: 90%;
    margin: 0 auto;
    padding: 10px;
    background-color: white;
}

.columnas {
    display: flex; /* En móviles, las columnas pueden apilarse si el ancho es limitado */
    flex-direction: column;
}

.columna {
    padding: 10px;
    border: 1px solid #ddd;
    margin-bottom: 10px;
}


/* Media Query para pantallas más grandes (ej. tablets y monitores pequeños) */
@media screen and (min-width: 768px) {
    body {
        background-color: lightgreen;
        font-size: 18px;
    }

    .columnas {
        flex-direction: row; /* En tablets, las columnas se ponen en fila */
        justify-content: space-between;
    }

    .columna {
        width: 48%; /* Dos columnas con un pequeño espacio */
    }
}

/* Media Query para pantallas de escritorio grandes */
@media screen and (min-width: 1200px) {
    body {
        background-color: lightcoral;
        font-size: 20px;
    }

    .contenedor {
        max-width: 1100px; /* Ancho máximo para pantallas muy grandes */
    }

    .columnas {
        flex-wrap: nowrap; /* Asegura que no se envuelvan */
    }

    .columna {
        width: 32%; /* Tres columnas con espacios */
    }
}
```

**Mobile First (primero el móvil):** Es una estrategia recomendada para el diseño responsivo. Consiste en diseñar y estilizar primero para el tamaño de pantalla más pequeño (móviles) y luego usar Media Queries con `min-width` para añadir estilos progresivamente para pantallas más grandes. Esto asegura un rendimiento óptimo en dispositivos móviles y una base sólida para diseños complejos.

* * *
## 3. RSS

## 4. Proyectos
-----

### Proyecto Práctico de HTML: Tu Página de Perfil Personal

Este proyecto te permitirá aplicar todos los conceptos básicos de HTML que hemos cubierto, desde la estructuración de la página hasta la inserción de contenido multimedia y formularios. Al finalizar, tendrás una página web sencilla que muestra información sobre ti.

-----

#### Descripción del Proyecto

Crearás una página web llamada `index.html` que funcionará como tu **página de perfil personal o currículum vitae online simplificado**. Incluirá diferentes secciones para presentarte a ti mismo, tus habilidades, experiencia y formas de contacto.

-----

#### Requisitos y Elementos a Incluir

Tu página `index.html` debe contener y utilizar los siguientes elementos y conceptos HTML:

1.  **Estructura Básica del Documento:**

      * Un correcto `<!DOCTYPE html>`.
      * Las etiquetas `<html>`, `<head>` y `<body>`.
      * El atributo `lang="es"` en la etiqueta `<html>`.

2.  **Metadatos en `<head>`:**

      * `meta charset="UTF-8"`.
      * `meta name="viewport"`.
      * Un título significativo usando `<title>`.
      * Al menos una etiqueta `<meta name="description">` y `<meta name="keywords">`.

3.  **Encabezados y Párrafos:**

      * Un `<h1>` para tu nombre o el título principal de la página.
      * Al menos un `<h2>` para cada sección principal (ej. "Acerca de Mí", "Habilidades", "Experiencia", "Contacto").
      * Varios párrafos (`<p>`) para describir tus secciones.

4.  **Énfasis de Texto:**

      * Utiliza `<strong>` para destacar palabras clave o importantes.
      * Utiliza `<em>` para dar énfasis a frases o términos.

5.  **Listas:**

      * Una lista **desordenada (`<ul>`)** para tus habilidades o intereses.
      * Una lista **ordenada (`<ol>`)** para describir pasos o logros numerados en tu experiencia (si aplica).
      * Una lista de **descripción (`<dl>`)** para definir términos clave relacionados contigo (ej. "Rol actual: Desarrollador").

6.  **Estructura Semántica con HTML5:**

      * Utiliza `header` para la parte superior de tu página (donde está tu nombre y quizás un lema).
      * Utiliza `nav` para una navegación (aunque sea interna con anclas).
      * Utiliza `main` para el contenido principal de la página.
      * Utiliza `section` para agrupar contenido temáticamente relacionado (como las secciones "Acerca de Mí", "Habilidades").
      * Utiliza `article` si alguna sección puede considerarse un contenido independiente (ej. una entrada de blog sobre un proyecto específico si quisieras extenderlo).
      * Utiliza `aside` para contenido secundario, como citas o enlaces a redes sociales.
      * Utiliza `footer` para información de copyright o contacto al final de la página.

7.  **Enlaces (`<a>`):**

      * Un enlace externo a tu perfil de LinkedIn, GitHub, o cualquier otra red social relevante (usando `target="_blank"`).
      * Al menos un enlace interno (áncora) para navegar a una sección diferente dentro de la misma página.
      * Un enlace de correo electrónico (`mailto:`).

8.  **Imágenes (`<img>`):**

      * Una imagen tuya (o un avatar/logo) con un `alt` descriptivo y `width`/`height` (aunque luego los controlemos con CSS).
      * Opcionalmente, una imagen pequeña para un icono o adorno.

9.  **Audio y/o Video (Opcional, para nivel avanzado):**

      * Si te sientes aventurero, puedes incrustar un pequeño clip de audio (`<audio>`) o un video (`<video>`) de presentación. Recuerda los atributos `controls` y las etiquetas `<source>`.

10. **Tablas (Opcional, para nivel avanzado):**

      * Si tienes experiencia laboral o académica que se beneficie de un formato tabular, crea una pequeña tabla (`<table>`) con `<thead>`, `<tbody>`, `<th>` y `<td>`.

11. **Formulario de Contacto:**

      * Crea un formulario básico (`<form>`) para que los visitantes puedan enviarte un mensaje.
      * Incluye al menos un `input type="text"`, un `input type="email"`, un `textarea` para el mensaje.
      * Utiliza etiquetas `<label>` asociadas a cada `input` usando `for` y `id`.
      * Añade el atributo `placeholder` para dar pistas al usuario.
      * Utiliza el atributo `required` en al menos un campo.
      * Un `input type="submit"` o `<button type="submit">` para enviar el formulario.

-----

#### Estructura de Archivos Recomendada

Crea una carpeta para tu proyecto y dentro de ella:

```
mi-perfil-html/
├── index.html
├── img/
│   └── tu-foto.jpg
│   └── (otros-iconos.png)
├── audio/ (opcional)
│   └── tu-audio.mp3
├── video/ (opcional)
│   └── tu-video.mp4
```

-----

#### Pasos para el Desarrollo

1.  **Crea el archivo `index.html`** y la estructura básica.
2.  **Define tus secciones principales** con `h1`, `h2` y `p`.
3.  **Añade tus habilidades e intereses** usando listas.
4.  **Inserta tu imagen de perfil**.
5.  **Crea enlaces** a tus redes y secciones internas.
6.  **Construye tu formulario de contacto**.
7.  **Aplica las etiquetas semánticas** de HTML5 (`header`, `nav`, `main`, etc.) para estructurar el documento.
8.  **Verifica la validación de tu HTML** usando el W3C Markup Validation Service.

-----

### Proyecto Práctico de CSS: Estilizando Tu Página de Perfil Personal

Este proyecto te guiará para crear un archivo CSS (`styles.css`) que transformará visualmente tu página de perfil personal (`index.html`). Utilizaremos selectores, propiedades de texto, fondos, bordes y el modelo de caja para darle un diseño limpio y profesional.

-----

#### Descripción del Proyecto

Crearás un archivo `styles.css` y lo enlazarás a tu `index.html`. En este archivo CSS, aplicarás estilos para:

  * Definir una fuente y colores generales para el cuerpo de la página.
  * Estilizar los encabezados y párrafos.
  * Dar formato a las listas.
  * Mejorar la apariencia de la imagen de perfil.
  * Diseñar la barra de navegación.
  * Crear un estilo para el formulario de contacto y sus elementos.
  * Dar un toque visual a la tabla (si la incluiste).
  * Asegurar que el pie de página tenga un aspecto adecuado.

-----

#### Requisitos y Conceptos CSS a Aplicar

Tu archivo `styles.css` debe utilizar los siguientes conceptos y propiedades de CSS:

1.  **Enlace del CSS:**

      * Asegúrate de que tu `index.html` tenga la etiqueta `<link rel="stylesheet" href="styles.css">` dentro del `<head>`.

2.  **Selectores CSS:**

      * **Selector Universal (`*`):** Para un reinicio básico de márgenes y paddings (`margin: 0; padding: 0; box-sizing: border-box;`).
      * **Selectores de Tipo (Elemento):** Para `body`, `h1`, `h2`, `p`, `ul`, `ol`, `li`, `a`, `img`, `form`, `input`, `textarea`, `button`, `table`, `th`, `td`, etc.
      * **Selectores de ID (`#`):** Para estilizar secciones específicas (ej. `#acerca-de`, `#contacto`).
      * **Selectores de Clase (`.`):** Si decides añadir clases a elementos específicos para un estilo reutilizable (opcional, pero buena práctica).
      * **Pseudo-clases:** Para los estados de los enlaces (`a:hover`, `a:visited`, `a:active`).

3.  **Propiedades de Texto y Fuentes:**

      * `font-family`: Define una fuente legible (ej. `Arial, sans-serif` o una Google Font).
      * `font-size`: Tamaños de fuente adecuados para encabezados y texto.
      * `color`: Colores de texto para diferentes elementos.
      * `text-align`: Alineación de texto (ej. centrar encabezados).
      * `line-height`: Espaciado entre líneas para mejorar la legibilidad.
      * `text-decoration`: Quitar el subrayado por defecto de los enlaces y añadirlo en `hover`.
      * `font-weight`: Para negritas o grosores específicos.
      * `text-transform`: Para convertir texto a mayúsculas o minúsculas (ej. en la navegación).

4.  **Modelo de Caja:**

      * `margin`: Espacio exterior entre elementos.
      * `padding`: Espacio interior entre el contenido y el borde.
      * `border`: Añadir bordes a elementos como el formulario, la tabla o las imágenes.
      * `width` / `max-width`: Para controlar el ancho de los contenedores principales (ej. `main`, `section`).
      * `box-sizing: border-box;` (fundamental en el selector universal).

5.  **Fondos y Bordes:**

      * `background-color`: Colores de fondo para el `body`, `header`, `footer`, secciones, etc.
      * `border-radius`: Para redondear esquinas de imágenes, botones, inputs.

6.  **Estilo de Listas:**

      * `list-style-type`: Cambiar el tipo de viñeta o numeración.
      * `list-style-position`: Posición de la viñeta.
      * Eliminar estilos por defecto de las listas de navegación (`list-style-type: none; padding: 0;`).

7.  **Estilo de Imágenes:**

      * `max-width: 100%; height: auto;` para hacer la imagen de perfil responsiva.
      * `display: block; margin: 0 auto;` para centrar la imagen.
      * `border-radius: 50%;` para hacer la imagen circular.

8.  **Estilo de Formularios:**

      * Estilizar `input[type="text"]`, `input[type="email"]`, `textarea`, `button`.
      * `width: 100%;` y `box-sizing: border-box;` para que los inputs ocupen todo el ancho disponible.
      * `padding`, `border`, `border-radius` para los campos.
      * `background-color` y `color` para el botón de envío.
      * `cursor: pointer;` para el botón.
      * Estilos para `button:hover`.

9.  **Estilo de Tablas (si aplica):**

      * `border-collapse: collapse;` para unir los bordes de las celdas.
      * `padding` para las celdas.
      * `background-color` para encabezados de tabla y filas alternas (`tr:nth-child(even)`).

-----

#### Estructura de Archivos

Asegúrate de que tu `styles.css` esté en la misma carpeta que tu `index.html`, o ajusta la ruta en la etiqueta `<link>`.

```
mi-perfil-html/
├── index.html
├── styles.css  <-- ¡Aquí es donde trabajarás!
├── img/
│   └── tu-foto.jpg
```

-----

#### Pasos para el Desarrollo

1.  **Crea el archivo `styles.css`** en la misma carpeta que `index.html`.
2.  **Abre `index.html`** y añade la línea `<link rel="stylesheet" href="styles.css">` dentro de la etiqueta `<head>`.
3.  **Empieza con los estilos generales** en `styles.css`:
      * Un reinicio básico con `*`.
      * Estilos para `body` (fuente, color de fondo, color de texto general).
4.  **Estiliza el `header` y `footer`**: Colores de fondo, padding, alineación de texto.
5.  **Diseña la barra de navegación (`nav` y `ul`/`li`/`a` dentro de ella)**: Quita las viñetas, haz que los enlaces se vean como botones o ítems de menú.
6.  **Estiliza las secciones (`section`)**: Añade padding, quizás un margen inferior.
7.  **Da formato a los encabezados (`h1`, `h2`) y párrafos (`p`)**: Ajusta tamaños, márgenes, colores.
8.  **Mejora la apariencia de la imagen de perfil**: Hazla circular, céntrala.
9.  **Estiliza las listas**: Ajusta viñetas, espaciado.
10. **Diseña el formulario de contacto**: Dale un aspecto limpio a los campos de entrada y al botón.
11. **Estiliza la tabla** (si la incluiste): Bordes, padding, colores de fondo para encabezados y filas.
12. **Depura**: Usa las herramientas de desarrollador del navegador (F12) para inspeccionar elementos y ajustar tus estilos.

-----

### Proyecto Práctico de CSS Layout: Organizando Tu Página de Perfil Personal


#### Descripción del Proyecto

Modificaremos tu archivo `styles.css` (o crearemos un nuevo archivo `layout.css` si prefieres mantener los estilos separados, lo cual es una buena práctica a medida que los proyectos crecen) para aplicar técnicas de layout. Nos centraremos en:

  * Crear un **diseño de página general** usando Flexbox o Grid.
  * Organizar el contenido dentro de las secciones utilizando **Flexbox** para alineación y distribución.
  * Ajustar el diseño para diferentes tamaños de pantalla usando **Media Queries** y principios de **Diseño Responsive**.

-----

#### Requisitos y Conceptos CSS Layout a Aplicar

Tu archivo CSS deberá integrar los siguientes conceptos y propiedades:

1.  **Estructura de Página Global (Flexbox o Grid):**

      * Utiliza el `<body>` o un `div` contenedor principal para establecer un **contenedor Flexbox o Grid** que organice el `header`, `main` y `footer`.
      * **Si usas Flexbox:** Define la dirección (`flex-direction`), justificación (`justify-content`) y alineación (`align-items`) para organizar estas secciones principales.
      * **Si usas Grid:** Define `grid-template-rows` para las alturas relativas de `header`, `main` y `footer`.

2.  **Organización de Secciones Internas con Flexbox:**

      * Dentro de la sección `main`, identifica áreas donde el contenido podría beneficiarse de Flexbox (ej., la navegación, la sección de habilidades, la imagen de perfil con texto, o incluso la tabla).
      * Aplica `display: flex;` a los contenedores apropiados.
      * Utiliza `justify-content` y `align-items` para la alineación interna de los ítems.
      * Considera `flex-wrap: wrap;` para elementos que puedan necesitar envolverse en pantallas pequeñas.
      * Emplea `gap` para añadir espacio entre los ítems flexibles.

3.  **Implementación de CSS Grid (Opcional pero Recomendado):**

      * En la sección `main`, podrías usar **CSS Grid** para un diseño de 2 o 3 columnas, por ejemplo, si quisieras que el `aside` (citas) flote junto a parte del contenido principal en lugar de debajo.
      * Define `grid-template-columns` y `grid-gap`.
      * Usa `grid-column` y `grid-row` para posicionar ítems específicos si no usas `grid-template-areas`.
      * **Alternativa a Grid:** Si consideras que es demasiado complejo para el tiempo o nivel actual, puedes mantener Flexbox para todo o incluso un `float` simple si solo necesitas una columna lateral muy básica.

4.  **Diseño Responsive con Media Queries:**

      * Implementa al menos **dos puntos de ruptura** (`min-width`): uno para tabletas y otro para escritorios.
      * Utiliza `min-width` para aplicar un enfoque **Mobile First**: diseña primero para pantallas pequeñas y luego ajusta el layout a medida que la pantalla crece.
      * Dentro de las Media Queries, modifica las propiedades de Flexbox o Grid (ej., `flex-direction: row;`, `grid-template-columns: 1fr 1fr;`) para adaptar el diseño.
      * Asegúrate de que las imágenes (`img`) sigan siendo **fluidas** (`max-width: 100%; height: auto;`).

-----

#### Estructura de Archivos Recomendada

Puedes seguir utilizando `styles.css` o, si te sientes más organizado, crea un `layout.css` y asegúrate de que `index.html` lo cargue *después* de `styles.css` para que las reglas de layout sobrescriban correctamente los estilos básicos.

```
mi-perfil-html/
├── index.html
├── styles.css   <-- (Estilos de texto, colores, bordes, etc.)
├── layout.css   <-- (¡Nuevo! Aquí irá el CSS de layout)
├── img/
│   └── tu-foto.jpg
```

**Si creas `layout.css`, actualiza tu `index.html` así:**

```html
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil de [Tu Nombre] - Desarrollador Web</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="layout.css"> </head>
```

-----

#### Pasos para el Desarrollo

1.  **Planifica tu Layout:** Antes de codificar, dibuja en papel o mentalmente cómo quieres que se vea tu página en un móvil, una tableta y un escritorio. ¿Qué secciones deben apilarse, cuáles deben ir lado a lado?
2.  **Define el Layout Global:**
      * Decide si usarás Flexbox o Grid en el `<body>` o un `div` principal. Empieza con un layout simple (ej. una sola columna para móvil).
3.  **Organiza Secciones Internas con Flexbox:**
      * Considera la `nav` del `header`: ¿Ya está usando Flexbox? Si no, aplícalo para centrar los enlaces horizontalmente.
      * En la sección `acerca-de`, si quieres que la imagen y el texto principal estén uno al lado del otro en pantallas grandes, usa Flexbox en un contenedor que los envuelva.
4.  **Implementa CSS Grid (si lo usas):**
      * Si decides usar Grid para la sección `main` o una subsección, define las columnas y filas dentro de los puntos de ruptura adecuados.
5.  **Aplica Media Queries (Mobile First):**
      * En tu `styles.css` (o `layout.css`), comienza con los estilos que funcionan bien para pantallas pequeñas.
      * Luego, añade bloques `@media screen and (min-width: 768px)` y `@media screen and (min-width: 1024px)` (o los anchos que elijas).
      * Dentro de estas Media Queries, ajusta las propiedades de Flexbox y Grid (`flex-direction`, `grid-template-columns`, etc.) para el diseño de pantallas más grandes.
6.  **Prueba y Depura:**
      * Usa las **herramientas de desarrollador del navegador** (F12) para redimensionar la ventana y ver cómo tu diseño se adapta.
      * Presta especial atención a la pestaña "Layout" o "Grid" en las DevTools para visualizar tus cuadrículas y contenedores Flexbox.

-----