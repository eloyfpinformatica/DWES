# Unidad 3. Javascript (22horas)

## Índice

1.  Fundamentos de Javascript  
    1.1. Primeros pasos  
    1.2. Javascript básico

2.  Funciones  
    2.1. Funciones en JS  
    2.2. Expresión de funciones  
    2.3. Funciones callback  
    2.4. Funciones arrow

3.  Arrays  
    3.1. Arrays  
    3.2. Métodos

4.  Validación

5.  Manipulación del DOM  
    5.1. Documentos  
    5.2. Árbol DOM  
    5.3. getElemebt y querySelector  
    5.4. Modificar documento  
    5.5. HTML DOM API  

6.  Eventos  
    6.1. Introducción a eventos del navegador  
    6.2. Load  
    
7.  Objetos  

8.  localStorage  

9.  JSON  


## Resultado de aprendizaje y criterios de evaluación



El resultado de aprendizaje asociado, junto a los criterios de  evaluación son los siguientes:

**RA3. Accede y manipula documentos web utilizando lenguajes de script de  cliente.** 

Criterios de evaluación: 

a) Se han identificado y clasificado los lenguajes de script de cliente  relacionados con la web y sus diferentes versiones y estándares. 

b) Se ha identificado la sintaxis básica de los lenguajes de script de  cliente. 

c) Se han utilizado métodos para la selección y acceso de los diferentes elementos de un documento web. 

d) Se han creado y modificado elementos de documentos web. 

e) Se han eliminado elementos de documentos web. 

f) Se han realizado modificaciones sobre los estilos de un documento  web.


## Temporalización

| **Bloque Temático** | **Temas del Índice** | **Horas Estimadas** | **Criterios de Evaluación Cubiertos** |
| --- | --- | --- | --- |
| **I. Fundamentos** | 1.1. Primeros pasos <br/> 1.2. Javascript básico | 3 horas | a), b) |
| **II. Funciones** | 2.1. Funciones en JS  <br>2.2. Expresión de funciones  <br>2.3. Funciones callback  <br>2.4. Funciones arrow | 4 horas | b)  |
| **III. Arrays** | 3.1. Arrays  <br>3.2. Métodos | 2 horas | b)  |
| **IV. Validación** | 4\. Validación | 1 hora | b)  |
| **V. Manipulación del DOM** | 5.1. Documentos  <br>5.2. Árbol DOM  <br>5.3. getElement y querySelector  <br>5.4. Modificar documento  <br>5.5. HTML DOM API | 6 horas | c), d), e), f) |
| **VI. Eventos** | 6.1. Introducción a eventos del navegador   <br>6.2. Load | 2 horas | c), d), f) |
| **VII. Objetos y Almacenamiento** | 7\. Objetos  <br>8\. localStorage  <br>9\. JSON | 2 horas | b)  |
| **VIII. Evaluación** | Examen | 2 horas | a), b), c), d), e), f) |
| **TOTAL** |     | **22 horas** |     |




## Bloque I: Fundamentos de Javascript



### 1.1. Primeros pasos



#### 1.1.1. ¿Qué es Javascript?



- **Lenguaje de Script de Cliente:**
  - **Definición:** Es un lenguaje de programación ligero e interpretado.
  - **Rol:** Hace que las páginas web sean **interactivas**. Mientras **HTML** estructura el contenido y **CSS** lo estiliza, **Javascript (JS)** se encarga del comportamiento (animaciones, validaciones de formularios, actualización de contenido sin recargar la página).
  - **Ejemplo:** Cuando haces clic en un menú desplegable y este se abre, eso es Javascript.
- **Ejecución en el Navegador:** JS es interpretado y ejecutado por el motor de Javascript (como V8 en Chrome o SpiderMonkey en Firefox) directamente en el dispositivo del usuario.



#### 1.1.2. Tres formas de incluir JS



Para que el navegador ejecute nuestro código, debemos incluirlo en el documento HTML.

1. **En línea (Inline):** Directamente en una etiqueta HTML. **(EVITAR)**.

   - *Uso:* Solo para acciones muy puntuales o demostraciones.

   - *Sintaxis:*


     ```html
     <button onclick="alert('Hola Mundo!');">Haz clic</button>
     ```

2. **Interno (Embedded):** Dentro de la etiqueta `<script>` en el documento HTML (normalmente antes de la etiqueta de cierre `</body>`).

   - *Uso:* Para scripts cortos o cuando el código solo afecta a una única página.

   - *Sintaxis:*


     ```html
     <!DOCTYPE html>
     <html>
     <head>
       <title>Script Interno</title>
     </head>
     <body>
       <h1>Mi página interactiva</h1>
       <script>
         console.log("¡El script se está ejecutando!");
       </script>
     </body>
     </html>
     ```

3. **Externo (Linked - RECOMENDADO):** En un archivo separado (`.js`) enlazado desde el HTML.

   - *Ventajas:*

     - **Organización:** Separa la lógica (JS) del contenido (HTML).
     - **Reutilización:** El mismo archivo `.js` puede ser usado por múltiples páginas.
     - **Caché:** El navegador puede almacenar el archivo JS en caché, mejorando la velocidad.

   - *Sintaxis (archivo HTML):*


     ```html
     <script src="app.js"></script> 
     ```

   - *Sintaxis (archivo app.js):*

 
     ```js
     console.log("Archivo externo cargado correctamente.");
     ```

::: info  ✏️ **Para practicar:** 
Crear un proyecto simple con un `index.html` y un `app.js`. Enlazar el archivo externo y usar `console.log()` para verificar que el código JS se está ejecutando.
:::


### 1.2. Javascript básico



#### 1.2.1. Salida de datos y Comentarios



- **`console.log()`:** Muestra información en la **consola del navegador** (útil para *debugging*).


  ```js
  console.log("Este es un mensaje de la consola.");
  ```

- **`alert()`:** Muestra una ventana de alerta emergente al usuario (uso limitado en producción).


  ```js
  alert("¡Bienvenido a mi sitio web!");
  ```

- **Comentarios:**

  - **Línea única:** `// Esto es un comentario de una línea`
  - **Bloque:** `/* Esto es un comentario de bloque que ocupa varias líneas */`



#### 1.2.2. Variables y Constantes



Las variables almacenan valores que pueden cambiar, mientras que las constantes almacenan valores fijos.

| **Palabra Clave** | **Uso**                                           | **Ámbito**       | **¿Se puede reasignar?**    |
| ----------------- | ------------------------------------------------- | ---------------- | --------------------------- |
| **`var`**         | **(EVITAR)** Heredado, tiene problemas de ámbito. | Función (global) | Sí                          |
| **`let`**         | **(USAR)** Variable moderna, ámbito de bloque.    | Bloque `{}`      | Sí                          |
| **`const`**       | **(USAR)** Para valores que NO deben cambiar.     | Bloque `{}`      | **No** (debe inicializarse) |

- **Declaración y Asignación:**

  ```js
  // Constante (no cambia)
  const PI = 3.14159; 
  
  // Variable (puede cambiar)
  let nombre = "Laura";
  nombre = "Carlos"; // Reasignación válida
  
  // Esto causaría un error:
  // const EDAD = 30;
  // EDAD = 31; 
  ```



#### 1.2.3. Tipos de Datos Primitivos


En JS, las variables no tienen un tipo fijo; el tipo lo tiene el **valor** (tipado dinámico).

| **Tipo de Dato** | **Descripción**   | **Ejemplo**                                 |
| ---------------- | ----------------- | ------------------------------------------- |
| **`string`**     | Cadenas de texto. | `"Hola"`, `'Mundo'`, `Nombre: ${nombre}` |

- **Plantillas de cadena (Template Literals):** Usan la comilla invertida (\`) y permiten insertar variables directamente (`${variable}`).


  ```js
  const producto = "Laptop";
  const precio = 1200;
  
  let mensaje = `El ${producto} cuesta ${precio} euros.`;
  // El Laptop cuesta 1200 euros.
  ```



#### 1.2.4. Operadores


Similar a Java.

- **Aritméticos:** `+`, `-`, `*`, `/`, `%` (módulo), `**` (exponente).
- **Asignación:** `=`, `+=`, `-=`, `*=`, etc.
- **Comparación:**
  - `==` (Igualdad *permisiva*, compara valor).
  - `!=` (Desigualdad *permisiva*).
  - **`===` (Igualdad \*estricta\*, compara valor y tipo) -> RECOMENDADO.**
  - `!==` (Desigualdad *estricta*)
  - `>`, `<`, `>=`, `<=`
- **Lógicos:** `&&` (AND), `||` (OR), `!` (NOT).

::: tip **Diferencia clave JS vs Java:** 
El operador `==` es fuente de errores.

```
console.log(5 == '5');  // true (convierte '5' a número)
console.log(5 === '5'); // false (tipos diferentes: number vs string)
```
:::

::: info  ✏️ **Para practicar:**  
Crear un script que:
1. Declara una constante para el nombre de un alumno y una variable `nota` (con un número).
2. Usa Template Literals para imprimir un mensaje en la consola con el nombre y la nota.
3. Aplica una condición de control (`if/else`) para imprimir si el alumno está "Aprobado" (nota >= 5) o "Suspendido".
:::


#### 1.2.5. Estructuras de Control


La sintaxis es **idéntica a Java**.

- **Condicionales:**

  ```javascript
  let edad = 17;
  
  if (edad >= 18) {
      console.log("Mayor de edad.");
  } else if (edad >= 16) {
      console.log("Casi mayor, puede conducir con permiso.");
  } else {
      console.log("Menor de edad.");
  }
  ```

- **Bucles:**


  ```js
  // Bucle for estándar
  for (let i = 0; i < 3; i++) {
      console.log("Iteración número: " + i);
  }
  
  // Bucle while
  let contador = 0;
  while (contador < 2) {
      console.log("En while: " + contador);
      contador++;
  }
  ```


### Ejercicios: Fundamentos de Javascript (Bloque I)


#### Ejercicio 1: Configuración Inicial y Salida de Datos


1. Crea una carpeta llamada `ejercicios_js`.
2. Dentro de ella, crea un archivo **HTML** (`index.html`) y un archivo **Javascript** (`app.js`).
3. **Vincula** `app.js` a `index.html` usando la etiqueta `<script>` justo antes de `</body>`.
4. En el archivo `app.js`, escribe una línea que muestre un mensaje de bienvenida en la **consola** del navegador.
   - *Pista:* Abre `index.html` en el navegador y usa F12 para ver la Consola.
5. Añade una segunda línea en `app.js` que muestre una **alerta** con el texto: "¡Cuidado! Javascript en acción".


::: details Solución Ejercicio 1: Configuración Inicial y Salida de Datos


`index.html`

```js
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ejercicio 1</title>
</head>
<body>
    <h1>¡Mi Primer Script JS!</h1>
    
    <script src="app.js"></script> 
</body>
</html>
```

`app.js`

```js
// 4. Muestra un mensaje en la Consola
console.log("¡El script app.js ha sido cargado correctamente!");

// 5. Muestra una alerta emergente
alert("¡Cuidado! Javascript en acción"); 
```
:::

------


#### Ejercicio 2: Declaración de Variables y Tipos de Datos


1. Declara una **constante** llamada `nombreCiclo` y asígnale el valor `"DAW"`.

2. Declara una **constante** llamada `anyoActual` y asígnale el valor `2025` (debe ser un número).

3. Declara una **variable** usando `let` llamada `notaMedia` y asígnale un valor decimal, por ejemplo, `8.75`.

4. Utilizando **Template Literals** (comillas invertidas `), imprime el siguiente mensaje en la consola, usando las variables declaradas:

   ```
   El alumno del ciclo DAW en el año 2025 tiene una nota media de 8.75.
   ```

   - *Resultado esperado:* Debe aparecer el mensaje completo y correcto en la consola.

5. **Bonus:** Intenta reasignar el valor de `nombreCiclo`. ¿Qué sucede? Comenta la línea que causa error.

::: details Solución Ejercicio 2: Declaración de Variables y Tipos de Datos

`app.js`


```js
// 1. Constante para el ciclo
const nombreCiclo = "DAW";

// 2. Constante para el año (number)
const anyoActual = 2025; 

// 3. Variable para la nota (let)
let notaMedia = 8.75; 

// 4. Uso de Template Literals (con comillas invertidas `)
console.log(`El alumno del ciclo ${nombreCiclo} en el año ${anyoActual} tiene una nota media de ${notaMedia}.`);

// 5. Bonus: Intento de reasignar una constante
// nombreCiclo = "DAM"; 
// Si descomentas la línea anterior, el navegador mostrará un error de TypeError 
// (Assignment to constant variable) porque las constantes no pueden cambiar.
```

:::
------


#### Ejercicio 3: Operadores y Lógica


1. Declara dos variables:
   - `let numeroA = 10;`
   - `let numeroB = 3;`
2. Calcula y muestra en la consola el resultado de las siguientes operaciones, etiquetando cada salida:
   - Suma de A y B.
   - El módulo (resto de la división) de A entre B.
   - A elevado a la potencia B.
3. Determina el valor de las siguientes expresiones e imprímelo en la consola:
   - `console.log('3' == 3);`
   - `console.log('3' === 3);`
   - `console.log(numeroA > 5 && numeroB < 5);` (AND)
   - `console.log(numeroA === 10 || numeroB === 10);` (OR)


::: details Solución Ejercicio 3: Operadores y Lógica


`app.js`

```js
// 1. Declaración de variables
let numeroA = 10;
let numeroB = 3;

// 2. Operaciones Aritméticas
console.log(`Suma (A + B): ${numeroA + numeroB}`); // Salida: 13
console.log(`Módulo (A % B): ${numeroA % numeroB}`); // Salida: 1 (10 / 3 = 3 y resto 1)
console.log(`Potencia (A ** B): ${numeroA ** numeroB}`); // Salida: 1000 (10 * 10 * 10)

// 3. Operadores de Comparación y Lógicos
console.log(`'3' == 3 (Permisiva): ${'3' == 3}`); // Salida: true (compara solo el valor)
console.log(`'3' === 3 (Estricta): ${'3' === 3}`); // Salida: false (compara valor y tipo)
console.log(`AND (numeroA > 5 && numeroB < 5): ${numeroA > 5 && numeroB < 5}`); // Salida: true (True && True)
console.log(`OR (numeroA === 10 || numeroB === 10): ${numeroA === 10 || numeroB === 10}`); // Salida: true (True || False)
```


:::
------

#### Ejercicio 4: Estructuras de Control


##### Parte A: Condicional `if/else`


1. Declara una variable `edadUsuario` y asígnale un valor.
2. Crea una estructura `if/else if/else` que muestre en la consola lo siguiente:
   - Si la edad es menor de 18: "Acceso denegado. Eres menor de edad."
   - Si la edad está entre 18 y 65 (ambos inclusive): "Acceso permitido. Eres un usuario activo."
   - Si la edad es mayor de 65: "Acceso permitido. Tienes descuento de jubilado."



##### Parte B: Bucle `for`

1. Utiliza un bucle `for` para imprimir los números del **0 al 9** en la consola.
2. Modifica el bucle anterior para que solo imprima los **números pares** entre el 0 y el 10 (incluyendo el 10).
   - *Pista:* Utiliza el operador **módulo** (`%`) dentro de una condición `if` para verificar si el resto de dividir por 2 es 0.

::: details Solución Ejercicio 4: Estructuras de Control

##### Parte A: Condicional `if/else`


```js
let edadUsuario = 35; // Puedes probar con 17, 35 y 70

if (edadUsuario < 18) {
    console.log("Acceso denegado. Eres menor de edad.");
} else if (edadUsuario >= 18 && edadUsuario <= 65) {
    console.log("Acceso permitido. Eres un usuario activo.");
} else { // edadUsuario > 65
    console.log("Acceso permitido. Tienes descuento de jubilado.");
}
```

##### Parte B: Bucle `for`


```js
// 1. Imprimir del 0 al 9
console.log("--- Bucle 0 al 9 ---");
for (let i = 0; i < 10; i++) {
    console.log(i);
}

// 2. Imprimir números pares entre 0 y 10
console.log("--- Bucle Pares 0 al 10 ---");
for (let j = 0; j <= 10; j++) {
    if (j % 2 === 0) { // Si el resto de dividir por 2 es 0, es par.
        console.log(j);
    }
}
```
:::


------




## Bloque II: Funciones



En Javascript, una función es un bloque de código diseñado para realizar una tarea particular. Al igual que en Java, evitan la repetición de código y permiten estructurar el programa.



### 2.1. Funciones en JS (Declaración)



La forma más básica de definir una función se conoce como **Declaración de Función** (*Function Declaration*).



#### 2.1.1. Sintaxis Básica



Se utiliza la palabra clave `function`, seguida del nombre de la función, una lista de parámetros entre paréntesis y el cuerpo de la función entre llaves.


```js
// Similar a public static void saludar(String nombre) en Java
function saludar(nombre) {
    console.log(`Hola, ${nombre}. Bienvenido al curso de DAW.`);
}

// 1. Llamada a la función
saludar("Alejandro"); // Salida: "Hola, Alejandro. Bienvenido al curso de DAW."
saludar("María");    // Salida: "Hola, María. Bienvenido al curso de DAW."
```



#### 2.1.2. Parámetros y Valor de Retorno



| **Concepto**         | **Javascript**                             | **Notas**                                                    |
| -------------------- | ------------------------------------------ | ------------------------------------------------------------ |
| **Parámetros**       | Se definen sin indicar el tipo (`nombre`). | **Javascript es flexible:** no se exige un número o tipo específico de argumentos al llamar. |
| **Valor de Retorno** | Se utiliza la palabra clave `return`.      | Si no se usa `return` explícitamente, la función devuelve `undefined`. |

**Ejemplo de Retorno:**


```js
function sumar(num1, num2) {
    let resultado = num1 + num2;
    return resultado;
    // El código después de 'return' NO se ejecuta.
}

// 2. Usar el valor retornado
let total = sumar(5, 7); // total vale 12
console.log(`La suma es: ${total}`); // Salida: La suma es: 12
```

::: info 💡 Concepto Clave: Hoisting (Elevación)

 En Javascript, las Declaraciones de Función son "elevadas" (hoisted). Esto significa que se pueden llamar antes de que aparezcan en el código.


 ```js
 despedida("Lucas"); // Esto FUNCIONA a pesar de que la función está definida abajo.
 // La llamada funciona porque la Declaración de Función es 'elevada'
 
 function despedida(nombre) {
     console.log(`Adiós, ${nombre}.`);
 }
 ```
:::

------

### 2.2. Expresión de Funciones



En Javascript, las funciones también pueden ser asignadas como un valor a una variable. Esto se conoce como **Expresión de Función** (*Function Expression*).



#### 2.2.1. Sintaxis



Se usa `let` o `const` para declarar una variable y se le asigna una función (que puede ser **anónima**, es decir, sin nombre después de `function`).


```js
// La función es el valor asignado a la constante 'multiplicar'
const multiplicar = function(a, b) {
    return a * b;
};

let producto = multiplicar(4, 5); // producto vale 20
console.log(producto);

// Error si intentamos redefinir, ya que es const
// multiplicar = 10;
```

::: warning ⚠️ Diferencia Clave con Declaración:

 Las Expresiones de Función NO son elevadas (hoisted). Solo se pueden llamar después de su definición. Es la forma más recomendada de definir funciones en el desarrollo moderno, ya que obliga a un orden lógico del código.

:::

##### Ejemplo: Expresión de Función y Hoisting

Este ejemplo muestra por qué el orden es crucial con las Expresiones:

```js
// A. Intento de llamar ANTES de la definición
// intentarLlamarAntes(); // Esto produciría un error: ReferenceError: Cannot access 'intentarLlamarAntes' before initialization

// B. Definición de la Expresión de Función (usando const)
const intentarLlamarAntes = function() {
    console.log("Esta función solo existe DESPUÉS de esta línea.");
};

// C. Llamada DESPUÉS de la definición
intentarLlamarAntes(); // Salida: Esta función solo existe DESPUÉS de esta línea.
```



::: info 📝 **Actividad: Calculadora Simple**

 1. Crea una **Declaración de Función** llamada `restar` que acepte dos números y devuelva la resta.
 2. Crea una **Expresión de Función** llamada `dividir` que acepte dos números y devuelva la división.
 3. Llama a ambas funciones y usa `console.log` para mostrar los resultados.
 4. Intenta llamar a la función `dividir` antes de su definición. Observa el error en la consola y explica por qué ocurre (Relación con Hoisting).
:::

::: details **Solución Actividad: Calculadora Simple**


```js
// 1. Declaración de Función (restar)
function restar(a, b) {
    return a - b;
}

// 2. Expresión de Función (dividir)
const dividir = function(a, b) {
    return a / b;
};

// 3. Llamada y resultados
let resultadoResta = restar(10, 4);
let resultadoDivision = dividir(100, 20);

console.log(`Resultado de la resta: ${resultadoResta}`); // Salida: 6
console.log(`Resultado de la división: ${resultadoDivision}`); // Salida: 5

// 4. Intento de llamar a la expresión de función antes de su definición
// intentarDivision(50, 5); 
/* Si descomentas esta línea, obtendrás un error 
   (ReferenceError: Cannot access 'intentarDivision' before initialization).
   
   Explicación: Las Expresiones de Función (al usar 'const' o 'let') NO son elevadas (Hoisted). 
   Deben ser definidas antes de ser usadas. */

const intentarDivision = function(a, b) {
    return a / b;
};
```
:::
------



### 2.3. Funciones Callback

#### 2.3.1. Definición



Una **función callback** es simplemente una función que se pasa como **argumento** a otra función y que será ejecutada (*llamada de vuelta*) más tarde, dentro de esa función externa.

Este concepto es *fundamental* para la programación asíncrona y, sobre todo, para el manejo de **Eventos** en el navegador (Bloque VI).



#### 2.3.2. Ejemplo Práctico (Síncrono)


```js
// 1. Definición de la función Callback (la tarea que queremos hacer)
function mostrarResultado(resultado) {
    console.log(`El resultado de la operación es: ${resultado}`);
}

// 2. Definición de la función que ACEPTA el callback
function calculadora(num1, num2, operacionCallback) {
    let resultado = num1 + num2;
    // Llama al callback, pasándole el resultado
    operacionCallback(resultado); 
}

// 3. Llamada a la función externa, pasando 'mostrarResultado' como argumento
calculadora(10, 5, mostrarResultado); 
// Salida: El resultado de la operación es: 15
```



#### 2.3.3. Ejemplo Clásico (Asíncrono)



La función nativa `setTimeout()` acepta un *callback* para ejecutar una función después de un tiempo.

```js
// La función anónima (el callback) se ejecutará después de 2000 milisegundos (2 segundos).
console.log("Inicio de la espera...");

setTimeout(function() {
    console.log("¡El callback se ejecutó después de 2 segundos!");
}, 2000);

console.log("Fin de la espera (el programa continúa ejecutándose)");
```





### 2.4. Funciones Arrow (`=>`)



Las **Funciones Flecha** (*Arrow Functions*) son una sintaxis más corta y moderna para escribir expresiones de función. Se introdujeron en ECMAScript 2015 (ES6).



#### 2.4.1. Sintaxis Concisa



Reemplazan la palabra clave `function` y se usa la flecha `=>` antes de las llaves.

**1. Sin parámetros (o más de uno):**

```js
// Expresión de función tradicional
const saludoTradicional = function() {
    return "Hola a todos.";
};

// Función Arrow
const saludoArrow = () => {
    return "Hola a todos desde una arrow function.";
};
```

2. Con un solo parámetro:

Se pueden omitir los paréntesis del parámetro.

```js
// Tradicional
const cuadradoTradicional = function(x) { return x * x; };

// Arrow
const cuadradoArrow = x => {
    return x * x;
};
```



##### Ejemplo: Funciones Callback y Retraso

Este es un ejemplo crucial que ilustra el uso de *callbacks* en un contexto asíncrono.

```js
/**
 * Simula una descarga de datos que tarda un tiempo.
 * @param {string} url - La dirección de descarga.
 * @param {function} manejarResultado - El callback que se ejecutará al finalizar.
 */
function descargarDatos(url, manejarResultado) {
    console.log(`Descargando datos de: ${url}...`);

    // El proceso real (simulado con setTimeout) no es instantáneo.
    setTimeout(() => {
        const datos = `[Datos de ${url} listos]`;
        console.log("Descarga completa.");
        
        // AQUÍ se llama al callback cuando la tarea asíncrona termina
        manejarResultado(datos); 
    }, 1500); // Tarda 1.5 segundos
}

// El Callback (usado como argumento)
const procesar = function(datosRecibidos) {
    console.log(`Resultado procesado: ${datosRecibidos}`);
};

// Ejecución
console.log("Inicio del programa.");
descargarDatos("api/usuarios/1", procesar);
console.log("El programa continuó ejecutándose mientras se descargaban los datos.");

// Orden de salida:
// 1. Inicio del programa.
// 2. Descargando datos de: api/usuarios/1...
// 3. El programa continuó ejecutándose mientras se descargaban los datos.
// (1.5 segundos de pausa)
// 4. Descarga completa.
// 5. Resultado procesado: [Datos de api/usuarios/1 listos]
```



#### 2.4.2. Retorno Implícito (Cuerpo de una sola línea)

Si el cuerpo de la función consta de una sola expresión, se pueden omitir las llaves `{}` y la palabra clave `return`. Esto se conoce como **retorno implícito**.

```js
// Función Arrow con retorno implícito (la forma más corta)
const doble = numero => numero * 2;

console.log(doble(8)); // Salida: 16
```



##### Ejemplo: Funciones Arrow y Retorno de Objetos

Las funciones flecha son perfectas para retornar estructuras de datos concisas.

::: warning **¡Cuidado al retornar objetos con retorno implícito!** 
Debe encerrarse el objeto entre paréntesis para evitar ambigüedad con las llaves del cuerpo de la función.
:::

```js
// Función Arrow con retorno explícito de un objeto (más verboso)
const crearPunto1 = (x, y) => {
    return { x: x, y: y };
};

// Función Arrow con retorno implícito de un objeto (conciso y correcto)
const crearPunto2 = (x, y) => ({ x: x, y: y }); 

console.log(crearPunto2(10, 20)); // Salida: { x: 10, y: 20 }
```



::: info 📝 **Actividad: Refactorizando con Arrows**

 1. Reescribe el `cuadradoArrow` anterior usando la sintaxis de **retorno implícito**.
 2. Crea una función *callback* llamada `mostrarMayusculas` que reciba un texto y lo devuelva en mayúsculas (`texto.toUpperCase()`). Usa sintaxis de **función flecha y retorno implícito**.
 3. Crea una función llamada `procesarTexto` que reciba un texto y el callback `mostrarMayusculas`.
 4. Llama a `procesarTexto` y observa la salida.
:::

::: details Solución
##### `app.js`

```js
// 1. Reescribir 'cuadradoArrow' con retorno implícito
const cuadradoArrow = x => x * x;

console.log(`El cuadrado de 7 es: ${cuadradoArrow(7)}`); // Salida: 49

// 2. Función Callback para mostrar mayúsculas (Arrow y Retorno Implícito)
const mostrarMayusculas = texto => texto.toUpperCase();

// 3. Función que acepta el Callback
function procesarTexto(texto, callback) {
    // Llama al callback, pasándole el texto y guardando el resultado
    const textoProcesado = callback(texto); 
    
    console.log("Texto original:", texto);
    console.log("Texto procesado:", textoProcesado);
}

// 4. Llamada a la función, pasando la función flecha como argumento
procesarTexto("hola mundo javascript", mostrarMayusculas); 
/*
Salida:
Texto original: hola mundo javascript
Texto procesado: HOLA MUNDO JAVASCRIPT
*/
```
:::



### Ejercicios: Funciones de Javascript (Bloque II)


#### Ejercicio 1: Diferencia de Sintaxis y Hoisting

1. Crea una **Declaración de Función** llamada `presentarEdad` que acepte un nombre y una edad, e imprima un saludo completo en la consola.
2. Crea una **Expresión de Función** llamada `calcularIva` que reciba un precio y devuelva el precio con un 21% de IVA añadido.
3. Crea una **Función Flecha** (*Arrow Function*) llamada `esPar` que reciba un número y devuelva `true` si es par y `false` si es impar, utilizando la sintaxis de **retorno implícito** (una sola línea).
4. **Prueba de Hoisting:** Intenta llamar a `presentarEdad("Ana", 25)` antes de su definición. Luego, intenta llamar a `calcularIva(100)` antes de su definición. Comenta el error que ocurre.

::: details Solución

```js
// 4. Prueba de Hoisting (Descomenta para ver el error)
presentarEdad("Ana", 25); // Funciona (Hoisted)
// calcularIva(100);       // ⚠️ ReferenceError: Cannot access 'calcularIva' before initialization (No Hoisted)

// 1. Declaración de Función (Hoisted)
function presentarEdad(nombre, edad) {
    console.log(`Hola, mi nombre es ${nombre} y tengo ${edad} años.`);
}

// 2. Expresión de Función (No Hoisted)
const calcularIva = function(precio) {
    const IVA = 0.21;
    return precio + (precio * IVA);
};

// 3. Función Flecha con Retorno Implícito
const esPar = numero => numero % 2 === 0;

// Pruebas (Después de la definición):
console.log(`El precio con IVA es: ${calcularIva(100)}`); // Salida: 121
console.log(`¿El 4 es par? ${esPar(4)}`); // Salida: true
console.log(`¿El 7 es par? ${esPar(7)}`); // Salida: false
```

:::


#### Ejercicio 2: Trabajando con Callbacks (Funciones como Argumentos)

1. Crea una función llamada `aplicarOperacion` que acepte **tres** argumentos: dos números (`num1`, `num2`) y una función *callback* (`operacion`).
2. Dentro de `aplicarOperacion`, llama al *callback* pasándole `num1` y `num2`, y retorna el resultado.
3. Crea una **Función Flecha** con retorno implícito llamada `multiplicar` que reciba `a` y `b` y devuelva `a * b`.
4. Crea una **Función Flecha** con retorno implícito llamada `elevarAlCuadrado` que reciba `a` y `b` y devuelva `a ** b` (A elevado a B).
5. Llama a `aplicarOperacion` dos veces:
   - Una vez pasando `multiplicar` como argumento (con los números 5 y 8).
   - Una vez pasando `elevarAlCuadrado` como argumento (con los números 2 y 4).

::: details Solución

```js
// 3. Función Arrow para multiplicar (Callback 1)
const multiplicar = (a, b) => a * b;

// 4. Función Arrow para elevar a la potencia (Callback 2)
const elevarAlCuadrado = (a, b) => a ** b;

// 1 & 2. Función principal que acepta un callback
function aplicarOperacion(num1, num2, operacion) {
    // Llama al callback y retorna su resultado
    return operacion(num1, num2); 
}

// 5. Llamadas a la función
let resMultiplicacion = aplicarOperacion(5, 8, multiplicar);
let resPotencia = aplicarOperacion(2, 4, elevarAlCuadrado);

console.log(`5 multiplicado por 8 es: ${resMultiplicacion}`); // Salida: 40
console.log(`2 elevado a la 4 es: ${resPotencia}`); // Salida: 16
```

:::


#### Ejercicio 3: Retorno Implícito de Objeto (Arrow Avanzada)


1. Crea una **Función Flecha** llamada `crearUsuario` que acepte dos parámetros: `nombre` y `rol`.
2. La función debe retornar **implícitamente** un objeto con las propiedades `{ nombre: nombre, rol: rol }`.
   - *Pista:* Recuerda la sintaxis especial para retornar objetos implícitamente.
3. Llama a la función `crearUsuario` y guarda el resultado en una constante llamada `nuevoUsuario`.
4. Imprime el objeto `nuevoUsuario` en la consola.

::: details Solución

```js
// 1 & 2. Función Flecha con retorno implícito de un objeto
// NOTA: Se usan paréntesis () para que JS sepa que las llaves {} son de un objeto, no del cuerpo de la función.
const crearUsuario = (nombre, rol) => ({
    nombre: nombre,
    rol: rol
});

// 3 & 4. Llamada y resultado
const nuevoUsuario = crearUsuario("Elena Nito", "Administradora");
console.log(nuevoUsuario); 
// Salida: { nombre: 'Elena Nito', rol: 'Administradora' }
```

:::


## **Bloque III. Arrays**

### **3.1. Arrays**

#### **¿Qué es un array?**

Un **array** (o arreglo) es una estructura de datos que permite almacenar múltiples valores en una única variable. Los arrays son fundamentales en JavaScript y se utilizan constantemente en el desarrollo web para gestionar colecciones de datos como listas de productos, usuarios, tareas, etc.

```javascript
// Sin arrays (poco práctico)
let producto1 = "Portátil";
let producto2 = "Ratón";
let producto3 = "Teclado";

// Con arrays (mucho mejor)
let productos = ["Portátil", "Ratón", "Teclado"];
```

#### **Crear arrays**

Existen varias formas de crear arrays en JavaScript:

**1. Notación literal (la más común):**

```javascript
let frutas = ["Manzana", "Pera", "Naranja"];
let numeros = [1, 2, 3, 4, 5];
let vacio = [];
```

**2. Constructor Array():**

```javascript
let colores = new Array("Rojo", "Verde", "Azul");
let cinco = new Array(5); // Array vacío con 5 posiciones
```

**3. Arrays mixtos:**

JavaScript permite mezclar diferentes tipos de datos en un mismo array:

```javascript
let mixto = ["Texto", 42, true, null];
```

#### **Acceder a elementos**

Los elementos de un array se acceden mediante su **índice** (posición). Los índices empiezan en **0**.

```javascript
let navegadores = ["Chrome", "Firefox", "Safari", "Edge"];

console.log(navegadores[0]); // "Chrome"
console.log(navegadores[1]); // "Firefox"
console.log(navegadores[3]); // "Edge"
```

**Ejemplo práctico - Lista de tareas:**

```javascript
let tareas = ["Revisar emails", "Actualizar web", "Llamar cliente"];

console.log("Primera tarea: " + tareas[0]);
// Resultado: "Primera tarea: Revisar emails"
```

#### **Modificar elementos**

Podemos cambiar el valor de un elemento accediendo a su índice:

```javascript
let categorias = ["HTML", "CSS", "JavaScript"];

categorias[2] = "JS"; // Cambiamos "JavaScript" por "JS"
console.log(categorias); // ["HTML", "CSS", "JS"]
```

#### **Propiedad length**

La propiedad `length` nos indica cuántos elementos tiene un array:

```javascript
let usuarios = ["Ana", "Carlos", "María", "Pedro"];

console.log(usuarios.length); // 4
```

Esta propiedad es muy útil para recorrer arrays:

```javascript
let productos = ["Ordenador", "Monitor", "Teclado"];

for (let i = 0; i < productos.length; i++) {
    console.log(productos[i]);
}
// Resultado:
// Ordenador
// Monitor
// Teclado
```

**Ejemplo práctico - Última posición:**

```javascript
let paginas = ["inicio", "productos", "contacto", "sobre-nosotros"];

// Acceder al último elemento
let ultimaPagina = paginas[paginas.length - 1];
console.log(ultimaPagina); // "sobre-nosotros"
```

#### **Arrays y bucles**

Los arrays se recorren frecuentemente con bucles:

**Bucle for tradicional:**

```javascript
let precios = [19.99, 29.99, 39.99, 49.99];

for (let i = 0; i < precios.length; i++) {
    console.log("Precio " + (i+1) + ": " + precios[i] + "€");
}
```

**Bucle for...of (más moderno):**

```javascript
let secciones = ["Header", "Main", "Aside", "Footer"];

for (let seccion of secciones) {
    console.log(seccion);
}
```

------

### **3.2. Métodos de Arrays**

JavaScript proporciona muchos métodos integrados para trabajar con arrays de forma eficiente.

#### **Añadir elementos**

**push() - Añade al final:**

```javascript
let carrito = ["Libro", "Bolígrafo"];

carrito.push("Cuaderno");
console.log(carrito); // ["Libro", "Bolígrafo", "Cuaderno"]

// Se pueden añadir varios elementos a la vez
carrito.push("Goma", "Regla");
console.log(carrito); // ["Libro", "Bolígrafo", "Cuaderno", "Goma", "Regla"]
```

**unshift() - Añade al principio:**

```javascript
let menu = ["Productos", "Servicios"];

menu.unshift("Inicio");
console.log(menu); // ["Inicio", "Productos", "Servicios"]
```

#### **Eliminar elementos**

**pop() - Elimina el último:**

```javascript
let notificaciones = ["Mensaje 1", "Mensaje 2", "Mensaje 3"];

let eliminado = notificaciones.pop();
console.log(eliminado); // "Mensaje 3"
console.log(notificaciones); // ["Mensaje 1", "Mensaje 2"]
```

**shift() - Elimina el primero:**

```javascript
let cola = ["Usuario1", "Usuario2", "Usuario3"];

let atendido = cola.shift();
console.log(atendido); // "Usuario1"
console.log(cola); // ["Usuario2", "Usuario3"]
```

#### **splice() - Añadir, eliminar o reemplazar**

El método `splice()` es muy versátil. Sintaxis: `array.splice(inicio, cantidad, elementos...)`

**Eliminar elementos:**

```javascript
let tecnologias = ["HTML", "CSS", "jQuery", "JavaScript", "React"];

// Eliminar 1 elemento desde la posición 2
tecnologias.splice(2, 1);
console.log(tecnologias); // ["HTML", "CSS", "JavaScript", "React"]
```

**Añadir elementos:**

```javascript
let idiomas = ["Español", "Francés"];

// Añadir en posición 1, sin eliminar nada
idiomas.splice(1, 0, "Inglés");
console.log(idiomas); // ["Español", "Inglés", "Francés"]
```

**Reemplazar elementos:**

```javascript
let frameworks = ["Angular", "Vue", "Ember"];

// Reemplazar 1 elemento en posición 2
frameworks.splice(2, 1, "React");
console.log(frameworks); // ["Angular", "Vue", "React"]
```

#### **slice() - Copiar porciones**

Extrae una porción del array sin modificar el original:

```javascript
let meses = ["Ene", "Feb", "Mar", "Abr", "May", "Jun"];

let primerTrimestre = meses.slice(0, 3);
console.log(primerTrimestre); // ["Ene", "Feb", "Mar"]
console.log(meses); // Array original sin cambios
```

#### **Buscar elementos**

**indexOf() - Encuentra la posición:**

```javascript
let extensiones = [".html", ".css", ".js", ".php"];

let posicion = extensiones.indexOf(".js");
console.log(posicion); // 2

let noExiste = extensiones.indexOf(".py");
console.log(noExiste); // -1 (no encontrado)
```

**includes() - Verifica si existe:**

```javascript
let roles = ["admin", "editor", "usuario"];

console.log(roles.includes("admin")); // true
console.log(roles.includes("superadmin")); // false
```

**Ejemplo práctico - Verificar permisos:**

```javascript
let permisosUsuario = ["leer", "escribir"];

if (permisosUsuario.includes("escribir")) {
    console.log("Puede editar el documento");
} else {
    console.log("Solo lectura");
}
```

#### **Transformar arrays**

**join() - Convertir a string:**

```javascript
let rutas = ["home", "productos", "detalle"];

let url = rutas.join("/");
console.log(url); // "home/productos/detalle"

let lista = rutas.join(", ");
console.log(lista); // "home, productos, detalle"
```

**concat() - Unir arrays:**

```javascript
let frontend = ["HTML", "CSS", "JavaScript"];
let backend = ["PHP", "MySQL"];

let fullstack = frontend.concat(backend);
console.log(fullstack); 
// ["HTML", "CSS", "JavaScript", "PHP", "MySQL"]
```

#### **Ordenar arrays**

**reverse() - Invertir orden:**

```javascript
let prioridades = ["Baja", "Media", "Alta"];

prioridades.reverse();
console.log(prioridades); // ["Alta", "Media", "Baja"]
```

**sort() - Ordenar alfabéticamente:**

```javascript
let usuarios = ["Carlos", "Ana", "Beatriz"];

usuarios.sort();
console.log(usuarios); // ["Ana", "Beatriz", "Carlos"]
```

**Nota:** `sort()` ordena como texto. Para números necesita una función de comparación:

```javascript
let numeros = [10, 5, 40, 25];

// Orden incorrecto (como texto)
numeros.sort();
console.log(numeros); // [10, 25, 40, 5]

// Orden correcto (como números)
numeros.sort(function(a, b) {
    return a - b;
});
console.log(numeros); // [5, 10, 25, 40]
```

#### **Ejemplo completo - Gestión de productos**

```javascript
let productos = ["Ratón", "Teclado", "Monitor"];

// Añadir productos
productos.push("Webcam");
productos.unshift("Portátil");

console.log("Catálogo actual:");
console.log(productos);
// ["Portátil", "Ratón", "Teclado", "Monitor", "Webcam"]

// Buscar un producto
if (productos.includes("Monitor")) {
    console.log("Monitor disponible en stock");
}

// Eliminar producto agotado
let indice = productos.indexOf("Teclado");
if (indice !== -1) {
    productos.splice(indice, 1);
    console.log("Teclado eliminado del catálogo");
}

// Ordenar alfabéticamente
productos.sort();
console.log("Catálogo ordenado:");
console.log(productos);
```

------

### **Actividades**

#### **Actividad 3.1 - Lista de enlaces**

Crea un array llamado `enlaces` con al menos 5 URLs de páginas web. Muestra por consola:

- El número total de enlaces
- El primer enlace
- El último enlace
- Todos los enlaces usando un bucle

::: details Solución
```javascript
// Crear array de enlaces
let enlaces = [
    "https://www.google.com",
    "https://www.github.com",
    "https://www.stackoverflow.com",
    "https://www.mdn.mozilla.org",
    "https://www.w3schools.com"
];

// Número total de enlaces
console.log("Total de enlaces: " + enlaces.length);

// Primer enlace
console.log("Primer enlace: " + enlaces[0]);

// Último enlace
console.log("Último enlace: " + enlaces[enlaces.length - 1]);

// Todos los enlaces con bucle
console.log("\nTodos los enlaces:");
for (let i = 0; i < enlaces.length; i++) {
    console.log((i + 1) + ". " + enlaces[i]);
}

// Alternativa con for...of
console.log("\nCon for...of:");
for (let enlace of enlaces) {
    console.log(enlace);
}
```

**Salida esperada:**

```
Total de enlaces: 5
Primer enlace: https://www.google.com
Último enlace: https://www.w3schools.com

Todos los enlaces:
1. https://www.google.com
2. https://www.github.com
3. https://www.stackoverflow.com
4. https://www.mdn.mozilla.org
5. https://www.w3schools.com
```

:::
#### **Actividad 3.2 - Carrito de compra**

Crea un array vacío llamado `carrito`. Usando los métodos adecuados:

1. Añade 3 productos al carrito
2. Elimina el último producto añadido
3. Añade 2 productos más al principio
4. Muestra cuántos productos hay en total
5. Muestra todos los productos del carrito

::: details Solución

```javascript
// Crear array vacío
let carrito = [];

// 1. Añadir 3 productos
carrito.push("Ratón");
carrito.push("Teclado");
carrito.push("Monitor");
console.log("Carrito después de añadir 3 productos:");
console.log(carrito);

// 2. Eliminar el último producto
carrito.pop();
console.log("\nCarrito después de eliminar el último:");
console.log(carrito);

// 3. Añadir 2 productos al principio
carrito.unshift("Portátil");
carrito.unshift("Webcam");
console.log("\nCarrito después de añadir 2 al principio:");
console.log(carrito);

// 4. Mostrar total de productos
console.log("\nTotal de productos: " + carrito.length);

// 5. Mostrar todos los productos
console.log("\nProductos en el carrito:");
for (let i = 0; i < carrito.length; i++) {
    console.log("- " + carrito[i]);
}
```

**Salida esperada:**

```
Carrito después de añadir 3 productos:
["Ratón", "Teclado", "Monitor"]

Carrito después de eliminar el último:
["Ratón", "Teclado"]

Carrito después de añadir 2 al principio:
["Webcam", "Portátil", "Ratón", "Teclado"]

Total de productos: 4

Productos en el carrito:
- Webcam
- Portátil
- Ratón
- Teclado
```

:::
#### **Actividad 3.3 - Gestión de tareas**

Crea un array `tareas` con 5 tareas pendientes. Realiza las siguientes operaciones:

1. Marca la primera tarea como completada (elimínala del array)
2. Añade 2 nuevas tareas al final
3. Comprueba si existe una tarea específica usando `includes()`
4. Ordena las tareas alfabéticamente
5. Muestra el array final

::: details Solución

```javascript
// Crear array de tareas
let tareas = [
    "Revisar emails",
    "Actualizar web",
    "Llamar al cliente",
    "Preparar informe",
    "Reunión equipo"
];

console.log("Tareas iniciales:");
console.log(tareas);

// 1. Marcar primera tarea como completada (eliminarla)
tareas.shift();
console.log("\nDespués de completar la primera tarea:");
console.log(tareas);

// 2. Añadir 2 nuevas tareas al final
tareas.push("Responder tickets");
tareas.push("Actualizar documentación");
console.log("\nDespués de añadir 2 tareas nuevas:");
console.log(tareas);

// 3. Comprobar si existe una tarea específica
let tareaBuscada = "Llamar al cliente";
if (tareas.includes(tareaBuscada)) {
    console.log("\nLa tarea '" + tareaBuscada + "' está pendiente");
} else {
    console.log("\nLa tarea '" + tareaBuscada + "' no está en la lista");
}

// 4. Ordenar alfabéticamente
tareas.sort();
console.log("\nTareas ordenadas alfabéticamente:");
console.log(tareas);

// 5. Array final
console.log("\nArray final de tareas:");
for (let i = 0; i < tareas.length; i++) {
    console.log((i + 1) + ". " + tareas[i]);
}
```

**Salida esperada:**

```
Tareas iniciales:
["Revisar emails", "Actualizar web", "Llamar al cliente", "Preparar informe", "Reunión equipo"]

Después de completar la primera tarea:
["Actualizar web", "Llamar al cliente", "Preparar informe", "Reunión equipo"]

Después de añadir 2 tareas nuevas:
["Actualizar web", "Llamar al cliente", "Preparar informe", "Reunión equipo", "Responder tickets", "Actualizar documentación"]

La tarea 'Llamar al cliente' está pendiente

Tareas ordenadas alfabéticamente:
["Actualizar documentación", "Actualizar web", "Llamar al cliente", "Preparar informe", "Responder tickets", "Reunión equipo"]

Array final de tareas:
1. Actualizar documentación
2. Actualizar web
3. Llamar al cliente
4. Preparar informe
5. Responder tickets
6. Reunión equipo
```

:::

#### **Actividad 3.4 - Categorías de productos**

Crea dos arrays: `categoriasActuales` con ["Electrónica", "Ropa", "Hogar"] y `categoriasNuevas` con ["Deportes", "Juguetes"].

1. Une ambos arrays en uno llamado `todasCategorias`
2. Añade la categoría "Libros" en la posición 2
3. Elimina la categoría "Hogar"
4. Convierte el array en un string separado por comas
5. Muestra cuántas categorías hay en total

::: details Solución

```javascript
// Crear arrays iniciales
let categoriasActuales = ["Electrónica", "Ropa", "Hogar"];
let categoriasNuevas = ["Deportes", "Juguetes"];

console.log("Categorías actuales:");
console.log(categoriasActuales);
console.log("\nCategorías nuevas:");
console.log(categoriasNuevas);

// 1. Unir ambos arrays
let todasCategorias = categoriasActuales.concat(categoriasNuevas);
console.log("\nTodas las categorías unidas:");
console.log(todasCategorias);

// 2. Añadir "Libros" en la posición 2
todasCategorias.splice(2, 0, "Libros");
console.log("\nDespués de añadir 'Libros' en posición 2:");
console.log(todasCategorias);

// 3. Eliminar la categoría "Hogar"
let indiceHogar = todasCategorias.indexOf("Hogar");
if (indiceHogar !== -1) {
    todasCategorias.splice(indiceHogar, 1);
    console.log("\nDespués de eliminar 'Hogar':");
    console.log(todasCategorias);
}

// 4. Convertir a string separado por comas
let categoriasString = todasCategorias.join(", ");
console.log("\nCategorías como string:");
console.log(categoriasString);

// 5. Mostrar total de categorías
console.log("\nTotal de categorías: " + todasCategorias.length);
```

**Salida esperada:**

```
Categorías actuales:
["Electrónica", "Ropa", "Hogar"]

Categorías nuevas:
["Deportes", "Juguetes"]

Todas las categorías unidas:
["Electrónica", "Ropa", "Hogar", "Deportes", "Juguetes"]

Después de añadir 'Libros' en posición 2:
["Electrónica", "Ropa", "Libros", "Hogar", "Deportes", "Juguetes"]

Después de eliminar 'Hogar':
["Electrónica", "Ropa", "Libros", "Deportes", "Juguetes"]

Categorías como string:
Electrónica, Ropa, Libros, Deportes, Juguetes

Total de categorías: 5
```


:::


## **Bloque IV. Validación**

### **4. Validación de formularios**

#### **¿Por qué validar?**

La validación de formularios es fundamental en el desarrollo web para:

- **Mejorar la experiencia del usuario**: Informar inmediatamente de errores
- **Seguridad**: Prevenir datos maliciosos o incorrectos
- **Integridad de datos**: Asegurar que los datos cumplan el formato esperado
- **Reducir carga del servidor**: Validar en el cliente antes de enviar al servidor

Existen dos tipos de validación:

- **Validación del lado del cliente** (JavaScript): Rápida, mejora UX
- **Validación del lado del servidor**: Obligatoria, más segura

**Importante:** Nunca confíes únicamente en la validación del cliente. Siempre debe haber validación en el servidor.

### **Validación HTML5**

HTML5 proporciona atributos básicos de validación:

```html
<form>
    <input type="text" required>
    <input type="email" required>
    <input type="number" min="0" max="100">
    <input type="text" pattern="[0-9]{5}">
    <button type="submit">Enviar</button>
</form>
```

Atributos comunes:

- `required`: Campo obligatorio
- `type`: Define el tipo de dato (email, number, url, etc.)
- `min` / `max`: Valores mínimo y máximo
- `minlength` / `maxlength`: Longitud mínima y máxima
- `pattern`: Expresión regular para validar formato

### **Validación con JavaScript**

JavaScript nos permite crear validaciones personalizadas más complejas.

**Ejemplo básico - Validar campo vacío:**

```html
<!DOCTYPE html>
<html>
<body>
    <form id="miFormulario">
        <label>Nombre:</label>
        <input type="text" id="nombre">
        <button type="submit">Enviar</button>
    </form>
    <p id="error" style="color: red;"></p>

    <script>
        document.getElementById("miFormulario").addEventListener("submit", function(evento) {
            evento.preventDefault(); // Evitar envío del formulario
            
            let nombre = document.getElementById("nombre").value;
            let mensajeError = document.getElementById("error");
            
            if (nombre === "") {
                mensajeError.textContent = "El nombre es obligatorio";
            } else {
                mensajeError.textContent = "";
                alert("Formulario válido");
            }
        });
    </script>
</body>
</html>
```

### **Validar longitud de texto**

```html
<!DOCTYPE html>
<html>
<body>
    <form id="formularioUsuario">
        <label>Usuario:</label>
        <input type="text" id="usuario">
        <button type="submit">Registrar</button>
    </form>
    <p id="mensaje"></p>

    <script>
        document.getElementById("formularioUsuario").addEventListener("submit", function(e) {
            e.preventDefault();
            
            let usuario = document.getElementById("usuario").value;
            let mensaje = document.getElementById("mensaje");
            
            if (usuario.length < 3) {
                mensaje.textContent = "El usuario debe tener al menos 3 caracteres";
                mensaje.style.color = "red";
            } else if (usuario.length > 15) {
                mensaje.textContent = "El usuario no puede superar 15 caracteres";
                mensaje.style.color = "red";
            } else {
                mensaje.textContent = "Usuario válido";
                mensaje.style.color = "green";
            }
        });
    </script>
</body>
</html>
```

### **Validar formato de email**

```html
<!DOCTYPE html>
<html>
<body>
    <form id="formularioEmail">
        <label>Email:</label>
        <input type="text" id="email">
        <button type="submit">Validar</button>
    </form>
    <p id="resultadoEmail"></p>

    <script>
        function validarEmail(email) {
            // Expresión regular básica para email
            let patron = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return patron.test(email);
        }

        document.getElementById("formularioEmail").addEventListener("submit", function(e) {
            e.preventDefault();
            
            let email = document.getElementById("email").value;
            let resultado = document.getElementById("resultadoEmail");
            
            if (email === "") {
                resultado.textContent = "El email es obligatorio";
                resultado.style.color = "red";
            } else if (!validarEmail(email)) {
                resultado.textContent = "El formato del email no es válido";
                resultado.style.color = "red";
            } else {
                resultado.textContent = "Email válido";
                resultado.style.color = "green";
            }
        });
    </script>
</body>
</html>
```

### **Validar números**

```html
<!DOCTYPE html>
<html>
<body>
    <form id="formularioEdad">
        <label>Edad:</label>
        <input type="text" id="edad">
        <button type="submit">Comprobar</button>
    </form>
    <p id="resultadoEdad"></p>

    <script>
        document.getElementById("formularioEdad").addEventListener("submit", function(e) {
            e.preventDefault();
            
            let edad = document.getElementById("edad").value;
            let resultado = document.getElementById("resultadoEdad");
            
            // Verificar que sea un número
            if (isNaN(edad) || edad === "") {
                resultado.textContent = "Debes introducir un número";
                resultado.style.color = "red";
            } else if (edad < 0) {
                resultado.textContent = "La edad no puede ser negativa";
                resultado.style.color = "red";
            } else if (edad < 18) {
                resultado.textContent = "Debes ser mayor de edad";
                resultado.style.color = "orange";
            } else if (edad > 120) {
                resultado.textContent = "Edad no válida";
                resultado.style.color = "red";
            } else {
                resultado.textContent = "Edad válida";
                resultado.style.color = "green";
            }
        });
    </script>
</body>
</html>
```

### **Validar contraseñas**

```html
<!DOCTYPE html>
<html>
<body>
    <form id="formularioPassword">
        <label>Contraseña:</label>
        <input type="password" id="password">
        <br><br>
        <label>Repetir contraseña:</label>
        <input type="password" id="password2">
        <br><br>
        <button type="submit">Registrar</button>
    </form>
    <p id="resultadoPassword"></p>

    <script>
        document.getElementById("formularioPassword").addEventListener("submit", function(e) {
            e.preventDefault();
            
            let pass1 = document.getElementById("password").value;
            let pass2 = document.getElementById("password2").value;
            let resultado = document.getElementById("resultadoPassword");
            
            if (pass1 === "" || pass2 === "") {
                resultado.textContent = "Debes completar ambos campos";
                resultado.style.color = "red";
            } else if (pass1.length < 6) {
                resultado.textContent = "La contraseña debe tener al menos 6 caracteres";
                resultado.style.color = "red";
            } else if (pass1 !== pass2) {
                resultado.textContent = "Las contraseñas no coinciden";
                resultado.style.color = "red";
            } else {
                resultado.textContent = "Contraseña válida";
                resultado.style.color = "green";
            }
        });
    </script>
</body>
</html>
```

### **Validación completa de formulario**

Ejemplo integrando múltiples validaciones:

```html
<!DOCTYPE html>
<html>
<head>
    <style>
        .error { color: red; font-size: 0.9em; }
        .exito { color: green; font-weight: bold; }
        input.invalido { border: 2px solid red; }
        input.valido { border: 2px solid green; }
    </style>
</head>
<body>
    <h2>Formulario de Registro</h2>
    <form id="formularioRegistro">
        <div>
            <label>Nombre:</label>
            <input type="text" id="nombre">
            <span id="errorNombre" class="error"></span>
        </div>
        <br>
        <div>
            <label>Email:</label>
            <input type="text" id="email">
            <span id="errorEmail" class="error"></span>
        </div>
        <br>
        <div>
            <label>Edad:</label>
            <input type="text" id="edad">
            <span id="errorEdad" class="error"></span>
        </div>
        <br>
        <button type="submit">Registrarse</button>
    </form>
    <p id="mensajeFinal"></p>

    <script>
        function validarEmail(email) {
            let patron = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return patron.test(email);
        }

        document.getElementById("formularioRegistro").addEventListener("submit", function(e) {
            e.preventDefault();
            
            // Obtener valores
            let nombre = document.getElementById("nombre").value.trim();
            let email = document.getElementById("email").value.trim();
            let edad = document.getElementById("edad").value.trim();
            
            // Limpiar mensajes anteriores
            document.getElementById("errorNombre").textContent = "";
            document.getElementById("errorEmail").textContent = "";
            document.getElementById("errorEdad").textContent = "";
            document.getElementById("mensajeFinal").textContent = "";
            
            // Quitar clases de validación
            document.getElementById("nombre").className = "";
            document.getElementById("email").className = "";
            document.getElementById("edad").className = "";
            
            let formularioValido = true;
            
            // Validar nombre
            if (nombre === "") {
                document.getElementById("errorNombre").textContent = "El nombre es obligatorio";
                document.getElementById("nombre").className = "invalido";
                formularioValido = false;
            } else if (nombre.length < 3) {
                document.getElementById("errorNombre").textContent = "Mínimo 3 caracteres";
                document.getElementById("nombre").className = "invalido";
                formularioValido = false;
            } else {
                document.getElementById("nombre").className = "valido";
            }
            
            // Validar email
            if (email === "") {
                document.getElementById("errorEmail").textContent = "El email es obligatorio";
                document.getElementById("email").className = "invalido";
                formularioValido = false;
            } else if (!validarEmail(email)) {
                document.getElementById("errorEmail").textContent = "Formato de email inválido";
                document.getElementById("email").className = "invalido";
                formularioValido = false;
            } else {
                document.getElementById("email").className = "valido";
            }
            
            // Validar edad
            if (edad === "") {
                document.getElementById("errorEdad").textContent = "La edad es obligatoria";
                document.getElementById("edad").className = "invalido";
                formularioValido = false;
            } else if (isNaN(edad)) {
                document.getElementById("errorEdad").textContent = "Debe ser un número";
                document.getElementById("edad").className = "invalido";
                formularioValido = false;
            } else if (edad < 18) {
                document.getElementById("errorEdad").textContent = "Debes ser mayor de edad";
                document.getElementById("edad").className = "invalido";
                formularioValido = false;
            } else {
                document.getElementById("edad").className = "valido";
            }
            
            // Mensaje final
            let mensajeFinal = document.getElementById("mensajeFinal");
            if (formularioValido) {
                mensajeFinal.textContent = "✓ Formulario enviado correctamente";
                mensajeFinal.className = "exito";
                // Aquí se enviaría al servidor
            } else {
                mensajeFinal.textContent = "✗ Por favor, corrige los errores";
                mensajeFinal.style.color = "red";
            }
        });
    </script>
</body>
</html>
```

### **Métodos útiles para validación**

**trim()** - Elimina espacios al inicio y final:

```javascript
let usuario = "  admin  ";
usuario = usuario.trim(); // "admin"
```

**isNaN()** - Verifica si NO es un número:

```javascript
console.log(isNaN("123")); // false (es número)
console.log(isNaN("abc")); // true (no es número)
```

**test()** - Verifica si cumple una expresión regular:

```javascript
let patron = /^[0-9]+$/; // Solo números
console.log(patron.test("123")); // true
console.log(patron.test("12a")); // false
```

------

### **Actividades**

#### **Actividad 4.1 - Validar formulario de contacto**

Crea un formulario HTML con los siguientes campos:

- Nombre (mínimo 3 caracteres)
- Teléfono (debe ser numérico y tener 9 dígitos)
- Mensaje (mínimo 10 caracteres)

Valida el formulario con JavaScript y muestra mensajes de error específicos para cada campo.

::: details Solución

```html
<!DOCTYPE html>
<html>
<head>
    <style>
        .error {
            color: red;
            font-size: 0.9em;
            display: block;
            margin-bottom: 10px;
        }
        .exito {
            color: green;
            font-weight: bold;
        }
        input, textarea {
            display: block;
            margin-bottom: 5px;
            padding: 5px;
            width: 300px;
        }
        textarea {
            height: 100px;
        }
    </style>
</head>
<body>
    <h2>Formulario de Contacto</h2>
    <form id="formularioContacto">
        <label>Nombre:</label>
        <input type="text" id="nombre">
        <span id="errorNombre" class="error"></span>

        <label>Teléfono:</label>
        <input type="text" id="telefono">
        <span id="errorTelefono" class="error"></span>

        <label>Mensaje:</label>
        <textarea id="mensaje"></textarea>
        <span id="errorMensaje" class="error"></span>

        <button type="submit">Enviar</button>
    </form>
    <p id="resultado"></p>

    <script>
        document.getElementById("formularioContacto").addEventListener("submit", function(e) {
            e.preventDefault();
            
            // Obtener valores
            let nombre = document.getElementById("nombre").value.trim();
            let telefono = document.getElementById("telefono").value.trim();
            let mensaje = document.getElementById("mensaje").value.trim();
            
            // Limpiar mensajes de error
            document.getElementById("errorNombre").textContent = "";
            document.getElementById("errorTelefono").textContent = "";
            document.getElementById("errorMensaje").textContent = "";
            document.getElementById("resultado").textContent = "";
            
            let formularioValido = true;
            
            // Validar nombre
            if (nombre === "") {
                document.getElementById("errorNombre").textContent = "El nombre es obligatorio";
                formularioValido = false;
            } else if (nombre.length < 3) {
                document.getElementById("errorNombre").textContent = "El nombre debe tener al menos 3 caracteres";
                formularioValido = false;
            }
            
            // Validar teléfono
            if (telefono === "") {
                document.getElementById("errorTelefono").textContent = "El teléfono es obligatorio";
                formularioValido = false;
            } else if (isNaN(telefono)) {
                document.getElementById("errorTelefono").textContent = "El teléfono debe ser numérico";
                formularioValido = false;
            } else if (telefono.length !== 9) {
                document.getElementById("errorTelefono").textContent = "El teléfono debe tener 9 dígitos";
                formularioValido = false;
            }
            
            // Validar mensaje
            if (mensaje === "") {
                document.getElementById("errorMensaje").textContent = "El mensaje es obligatorio";
                formularioValido = false;
            } else if (mensaje.length < 10) {
                document.getElementById("errorMensaje").textContent = "El mensaje debe tener al menos 10 caracteres";
                formularioValido = false;
            }
            
            // Resultado final
            let resultado = document.getElementById("resultado");
            if (formularioValido) {
                resultado.textContent = "✓ Formulario enviado correctamente";
                resultado.className = "exito";
                // Aquí se enviaría el formulario
            } else {
                resultado.textContent = "✗ Por favor, corrige los errores";
                resultado.style.color = "red";
            }
        });
    </script>
</body>
</html>
```

:::
---
#### **Actividad 4.2 - Formulario de login**

Crea un formulario de login con:

- Usuario (mínimo 4 caracteres, sin espacios)
- Contraseña (mínimo 6 caracteres)

Valida que ambos campos cumplan los requisitos y muestra mensajes apropiados. Si todo es correcto, muestra "Login exitoso".


::: details Solución

```html
<!DOCTYPE html>
<html>
<head>
    <style>
        .error {
            color: red;
            font-size: 0.9em;
            display: block;
            margin-bottom: 10px;
        }
        .exito {
            color: green;
            font-weight: bold;
            font-size: 1.2em;
        }
        input {
            display: block;
            margin-bottom: 5px;
            padding: 8px;
            width: 250px;
        }
        button {
            padding: 10px 20px;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <h2>Login</h2>
    <form id="formularioLogin">
        <label>Usuario:</label>
        <input type="text" id="usuario">
        <span id="errorUsuario" class="error"></span>

        <label>Contraseña:</label>
        <input type="password" id="password">
        <span id="errorPassword" class="error"></span>

        <button type="submit">Iniciar sesión</button>
    </form>
    <p id="mensajeLogin"></p>

    <script>
        document.getElementById("formularioLogin").addEventListener("submit", function(e) {
            e.preventDefault();
            
            // Obtener valores
            let usuario = document.getElementById("usuario").value;
            let password = document.getElementById("password").value;
            
            // Limpiar mensajes
            document.getElementById("errorUsuario").textContent = "";
            document.getElementById("errorPassword").textContent = "";
            document.getElementById("mensajeLogin").textContent = "";
            
            let loginValido = true;
            
            // Validar usuario
            if (usuario === "") {
                document.getElementById("errorUsuario").textContent = "El usuario es obligatorio";
                loginValido = false;
            } else if (usuario.length < 4) {
                document.getElementById("errorUsuario").textContent = "El usuario debe tener al menos 4 caracteres";
                loginValido = false;
            } else if (usuario.includes(" ")) {
                document.getElementById("errorUsuario").textContent = "El usuario no puede contener espacios";
                loginValido = false;
            }
            
            // Validar contraseña
            if (password === "") {
                document.getElementById("errorPassword").textContent = "La contraseña es obligatoria";
                loginValido = false;
            } else if (password.length < 6) {
                document.getElementById("errorPassword").textContent = "La contraseña debe tener al menos 6 caracteres";
                loginValido = false;
            }
            
            // Mensaje final
            let mensaje = document.getElementById("mensajeLogin");
            if (loginValido) {
                mensaje.textContent = "✓ Login exitoso";
                mensaje.className = "exito";
            } else {
                mensaje.textContent = "✗ Credenciales inválidas";
                mensaje.style.color = "red";
            }
        });
    </script>
</body>
</html>
```

:::
---

#### **Actividad 4.3 - Validar código postal**

Crea un formulario con un campo para código postal. El código postal debe:

- Tener exactamente 5 dígitos
- Ser numérico

Muestra mensajes de error si no cumple las condiciones y un mensaje de éxito si es válido.


::: details Solución

```html
<!DOCTYPE html>
<html>
<head>
    <style>
        .error {
            color: red;
            font-size: 0.9em;
            display: block;
            margin: 5px 0;
        }
        .exito {
            color: green;
            font-weight: bold;
        }
        input {
            padding: 8px;
            width: 200px;
            margin: 10px 0;
        }
        button {
            padding: 10px 20px;
        }
        #resultado {
            margin-top: 15px;
            font-size: 1.1em;
        }
    </style>
</head>
<body>
    <h2>Validar Código Postal</h2>
    <form id="formularioCP">
        <label>Código Postal:</label>
        <input type="text" id="codigoPostal" maxlength="5" placeholder="Ej: 28001">
        <span id="errorCP" class="error"></span>
        <button type="submit">Validar</button>
    </form>
    <p id="resultado"></p>

    <script>
        document.getElementById("formularioCP").addEventListener("submit", function(e) {
            e.preventDefault();
            
            let codigoPostal = document.getElementById("codigoPostal").value.trim();
            let errorCP = document.getElementById("errorCP");
            let resultado = document.getElementById("resultado");
            
            // Limpiar mensajes
            errorCP.textContent = "";
            resultado.textContent = "";
            
            // Validar código postal
            if (codigoPostal === "") {
                errorCP.textContent = "El código postal es obligatorio";
            } else if (isNaN(codigoPostal)) {
                errorCP.textContent = "El código postal debe ser numérico";
            } else if (codigoPostal.length !== 5) {
                errorCP.textContent = "El código postal debe tener exactamente 5 dígitos";
            } else {
                resultado.textContent = "✓ Código postal válido: " + codigoPostal;
                resultado.className = "exito";
            }
        });
    </script>
</body>
</html>
```

:::
---
#### **Actividad 4.4 - Formulario de producto**

Crea un formulario para añadir un producto con:

- Nombre del producto (obligatorio, mínimo 3 caracteres)
- Precio (debe ser un número positivo mayor que 0)
- Cantidad (número entero entre 1 y 100)

Valida todos los campos y muestra mensajes de error individuales. Si todo es correcto, muestra un resumen: "Producto: [nombre], Precio: [precio]€, Cantidad: [cantidad]".

::: details Solución

```html
<!DOCTYPE html>
<html>
<head>
    <style>
        .error {
            color: red;
            font-size: 0.9em;
            display: block;
            margin-bottom: 10px;
        }
        .exito {
            color: green;
            font-weight: bold;
            padding: 15px;
            background-color: #e8f5e9;
            border-radius: 5px;
            margin-top: 15px;
        }
        input {
            display: block;
            margin-bottom: 5px;
            padding: 8px;
            width: 300px;
        }
        button {
            padding: 10px 20px;
            margin-top: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
        label {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h2>Añadir Producto</h2>
    <form id="formularioProducto">
        <label>Nombre del producto:</label>
        <input type="text" id="nombreProducto">
        <span id="errorNombre" class="error"></span>

        <label>Precio (€):</label>
        <input type="text" id="precio">
        <span id="errorPrecio" class="error"></span>

        <label>Cantidad:</label>
        <input type="text" id="cantidad">
        <span id="errorCantidad" class="error"></span>

        <button type="submit">Añadir Producto</button>
    </form>
    <div id="resumen"></div>

    <script>
        document.getElementById("formularioProducto").addEventListener("submit", function(e) {
            e.preventDefault();
            
            // Obtener valores
            let nombreProducto = document.getElementById("nombreProducto").value.trim();
            let precio = document.getElementById("precio").value.trim();
            let cantidad = document.getElementById("cantidad").value.trim();
            
            // Limpiar mensajes de error
            document.getElementById("errorNombre").textContent = "";
            document.getElementById("errorPrecio").textContent = "";
            document.getElementById("errorCantidad").textContent = "";
            document.getElementById("resumen").textContent = "";
            
            let formularioValido = true;
            
            // Validar nombre
            if (nombreProducto === "") {
                document.getElementById("errorNombre").textContent = "El nombre del producto es obligatorio";
                formularioValido = false;
            } else if (nombreProducto.length < 3) {
                document.getElementById("errorNombre").textContent = "El nombre debe tener al menos 3 caracteres";
                formularioValido = false;
            }
            
            // Validar precio
            if (precio === "") {
                document.getElementById("errorPrecio").textContent = "El precio es obligatorio";
                formularioValido = false;
            } else if (isNaN(precio)) {
                document.getElementById("errorPrecio").textContent = "El precio debe ser un número";
                formularioValido = false;
            } else if (parseFloat(precio) <= 0) {
                document.getElementById("errorPrecio").textContent = "El precio debe ser mayor que 0";
                formularioValido = false;
            }
            
            // Validar cantidad
            if (cantidad === "") {
                document.getElementById("errorCantidad").textContent = "La cantidad es obligatoria";
                formularioValido = false;
            } else if (isNaN(cantidad)) {
                document.getElementById("errorCantidad").textContent = "La cantidad debe ser un número";
                formularioValido = false;
            } else if (!Number.isInteger(parseFloat(cantidad))) {
                document.getElementById("errorCantidad").textContent = "La cantidad debe ser un número entero";
                formularioValido = false;
            } else if (parseInt(cantidad) < 1 || parseInt(cantidad) > 100) {
                document.getElementById("errorCantidad").textContent = "La cantidad debe estar entre 1 y 100";
                formularioValido = false;
            }
            
            // Mostrar resumen si es válido
            let resumen = document.getElementById("resumen");
            if (formularioValido) {
                resumen.innerHTML = "✓ Producto añadido correctamente<br><br>" +
                                   "<strong>Producto:</strong> " + nombreProducto + "<br>" +
                                   "<strong>Precio:</strong> " + parseFloat(precio).toFixed(2) + "€<br>" +
                                   "<strong>Cantidad:</strong> " + cantidad;
                resumen.className = "exito";
            } else {
                resumen.textContent = "✗ Por favor, corrige los errores antes de continuar";
                resumen.style.color = "red";
            }
        });
    </script>
</body>
</html>
```

:::
---
## **Bloque V. Manipulación del DOM**

### **5.1. Documentos**

#### **¿Qué es un documento web?**

Un documento web es la página HTML que el navegador carga y muestra. Cuando escribimos código HTML, creamos un documento estructurado con elementos como encabezados, párrafos, enlaces, imágenes, etc.

```html
<!DOCTYPE html>
<html>
<head>
    <title>Mi Página</title>
</head>
<body>
    <h1>Bienvenido</h1>
    <p>Este es un párrafo</p>
</body>
</html>
```

#### **El objeto document**

JavaScript proporciona el objeto `document` que representa la página web completa. A través de este objeto podemos:

- Acceder a elementos HTML
- Modificar contenido
- Cambiar estilos
- Crear nuevos elementos
- Eliminar elementos

```javascript
console.log(document); // Muestra todo el documento
console.log(document.title); // Muestra el título de la página
console.log(document.URL); // Muestra la URL actual
```

**Ejemplo - Información del documento:**

```html
<!DOCTYPE html>
<html>
<head>
    <title>Mi Sitio Web</title>
</head>
<body>
    <h1>Información del Documento</h1>
    <div id="info"></div>

    <script>
        let info = document.getElementById("info");
        info.innerHTML = "<p><strong>Título:</strong> " + document.title + "</p>";
        info.innerHTML += "<p><strong>URL:</strong> " + document.URL + "</p>";
        info.innerHTML += "<p><strong>Dominio:</strong> " + document.domain + "</p>";
    </script>
</body>
</html>
```

#### **Propiedades comunes del objeto document**

```javascript
document.title          // Título de la página
document.URL            // URL completa
document.domain         // Dominio
document.head           // Elemento <head>
document.body           // Elemento <body>
document.forms          // Todos los formularios
document.images         // Todas las imágenes
document.links          // Todos los enlaces
```

------

### **5.2. Árbol DOM**

#### **¿Qué es el DOM?**

**DOM** significa **Document Object Model** (Modelo de Objetos del Documento). Es una representación en forma de árbol de todos los elementos HTML de una página.

El navegador convierte el HTML en una estructura de árbol donde:

- Cada elemento HTML es un **nodo**
- Los elementos pueden tener **nodos hijos**
- Existe una jerarquía padre-hijo

**Ejemplo de documento HTML:**

```html
<!DOCTYPE html>
<html>
<head>
    <title>Ejemplo DOM</title>
</head>
<body>
    <div id="contenedor">
        <h1>Título</h1>
        <p>Párrafo de texto</p>
    </div>
</body>
</html>
```

**Representación en árbol DOM:**

```
document
  └── html
      ├── head
      │   └── title
      │       └── "Ejemplo DOM"
      └── body
          └── div (id="contenedor")
              ├── h1
              │   └── "Título"
              └── p
                  └── "Párrafo de texto"
```

#### **Tipos de nodos**

- **Nodo elemento**: Etiquetas HTML (`<div>`, `<p>`, `<h1>`, etc.)
- **Nodo texto**: El contenido de texto dentro de los elementos
- **Nodo atributo**: Los atributos de las etiquetas (id, class, src, etc.)

#### **Relaciones entre nodos**

Los nodos tienen relaciones familiares:

```javascript
// Dado este HTML:
// <div id="padre">
//     <p id="hijo1">Primer párrafo</p>
//     <p id="hijo2">Segundo párrafo</p>
// </div>

let padre = document.getElementById("padre");
let hijo1 = document.getElementById("hijo1");

// Nodo padre
console.log(hijo1.parentNode); // <div id="padre">

// Nodos hijos
console.log(padre.childNodes); // Lista de todos los hijos
console.log(padre.children); // Solo elementos HTML (sin nodos de texto)
console.log(padre.firstChild); // Primer hijo
console.log(padre.lastChild); // Último hijo

// Nodos hermanos
console.log(hijo1.nextSibling); // Siguiente hermano
console.log(hijo1.previousSibling); // Hermano anterior
```

**Ejemplo práctico:**

```html
<!DOCTYPE html>
<html>
<body>
    <ul id="lista">
        <li>Elemento 1</li>
        <li>Elemento 2</li>
        <li>Elemento 3</li>
    </ul>

    <script>
        let lista = document.getElementById("lista");
        
        console.log("Número de hijos: " + lista.children.length);
        console.log("Primer elemento: " + lista.firstElementChild.textContent);
        console.log("Último elemento: " + lista.lastElementChild.textContent);
    </script>
</body>
</html>
```

#### **Navegación por el DOM**

```html
<!DOCTYPE html>
<html>
<body>
    <div id="contenedor">
        <h2>Productos</h2>
        <ul>
            <li>Producto 1</li>
            <li>Producto 2</li>
            <li>Producto 3</li>
        </ul>
    </div>

    <script>
        let contenedor = document.getElementById("contenedor");
        
        // Acceder al título
        let titulo = contenedor.firstElementChild;
        console.log(titulo.textContent); // "Productos"
        
        // Acceder a la lista
        let lista = titulo.nextElementSibling;
        console.log(lista.children.length); // 3
        
        // Recorrer todos los elementos de la lista
        for (let item of lista.children) {
            console.log(item.textContent);
        }
    </script>
</body>
</html>
```

------

### **5.3. getElementById y querySelector**

#### **getElementById()**

Selecciona un elemento por su atributo `id`. Es el método más rápido y directo.

```html
<!DOCTYPE html>
<html>
<body>
    <h1 id="titulo">Mi Título</h1>
    <p id="parrafo">Este es un párrafo</p>

    <script>
        let titulo = document.getElementById("titulo");
        console.log(titulo.textContent); // "Mi Título"
        
        let parrafo = document.getElementById("parrafo");
        console.log(parrafo.textContent); // "Este es un párrafo"
    </script>
</body>
</html>
```

**Importante:**

- El `id` debe ser único en la página
- Si no existe, devuelve `null`
- No lleva `#` delante (a diferencia de CSS)

```javascript
let elemento = document.getElementById("miId"); // ✓ Correcto
let elemento = document.getElementById("#miId"); // ✗ Incorrecto
```

#### **getElementsByClassName()**

Selecciona **todos** los elementos con una clase específica. Devuelve una colección (similar a un array).

```html
<!DOCTYPE html>
<html>
<body>
    <p class="destacado">Párrafo 1</p>
    <p class="destacado">Párrafo 2</p>
    <p class="normal">Párrafo 3</p>

    <script>
        let destacados = document.getElementsByClassName("destacado");
        
        console.log(destacados.length); // 2
        console.log(destacados[0].textContent); // "Párrafo 1"
        console.log(destacados[1].textContent); // "Párrafo 2"
        
        // Recorrer todos
        for (let elem of destacados) {
            console.log(elem.textContent);
        }
    </script>
</body>
</html>
```

#### **getElementsByTagName()**

Selecciona **todos** los elementos de una etiqueta específica.

```html
<!DOCTYPE html>
<html>
<body>
    <p>Primer párrafo</p>
    <p>Segundo párrafo</p>
    <p>Tercer párrafo</p>

    <script>
        let parrafos = document.getElementsByTagName("p");
        
        console.log("Total de párrafos: " + parrafos.length);
        
        for (let i = 0; i < parrafos.length; i++) {
            console.log("Párrafo " + (i+1) + ": " + parrafos[i].textContent);
        }
    </script>
</body>
</html>
```

#### **querySelector()**

Selecciona el **primer elemento** que coincida con un selector CSS. Es muy versátil.

```html
<!DOCTYPE html>
<html>
<body>
    <div id="contenedor">
        <p class="texto">Párrafo 1</p>
        <p class="texto">Párrafo 2</p>
    </div>

    <script>
        // Por ID
        let contenedor = document.querySelector("#contenedor");
        
        // Por clase (solo el primero)
        let primerTexto = document.querySelector(".texto");
        console.log(primerTexto.textContent); // "Párrafo 1"
        
        // Por etiqueta
        let primerParrafo = document.querySelector("p");
        
        // Selectores complejos
        let parrafoEnDiv = document.querySelector("div p");
        let parrafoClase = document.querySelector("p.texto");
    </script>
</body>
</html>
```

#### **querySelectorAll()**

Selecciona **todos** los elementos que coincidan con un selector CSS.

```html
<!DOCTYPE html>
<html>
<body>
    <ul>
        <li class="activo">Inicio</li>
        <li>Productos</li>
        <li class="activo">Contacto</li>
    </ul>

    <script>
        // Seleccionar todos los li
        let todosLi = document.querySelectorAll("li");
        console.log("Total items: " + todosLi.length); // 3
        
        // Seleccionar solo los activos
        let activos = document.querySelectorAll("li.activo");
        console.log("Items activos: " + activos.length); // 2
        
        // Recorrer con forEach
        todosLi.forEach(function(item) {
            console.log(item.textContent);
        });
    </script>
</body>
</html>
```

#### **Comparación de métodos**

| Método                     | Qué selecciona                    | Devuelve              | Selector               |
| -------------------------- | --------------------------------- | --------------------- | ---------------------- |
| `getElementById()`         | Un elemento por ID                | Elemento único o null | "miId"                 |
| `getElementsByClassName()` | Elementos por clase               | Colección             | "miClase"              |
| `getElementsByTagName()`   | Elementos por etiqueta            | Colección             | "p"                    |
| `querySelector()`          | Primer elemento que coincida      | Elemento único o null | Cualquier selector CSS |
| `querySelectorAll()`       | Todos los elementos que coincidan | NodeList              | Cualquier selector CSS |

**Ejemplo completo - Menú de navegación:**

```html
<!DOCTYPE html>
<html>
<head>
    <style>
        .activo { font-weight: bold; color: blue; }
    </style>
</head>
<body>
    <nav id="menu">
        <a href="#" class="enlace">Inicio</a>
        <a href="#" class="enlace activo">Productos</a>
        <a href="#" class="enlace">Servicios</a>
        <a href="#" class="enlace">Contacto</a>
    </nav>

    <script>
        // Método 1: getElementById
        let menu = document.getElementById("menu");
        console.log("Menú encontrado:", menu);
        
        // Método 2: getElementsByClassName
        let enlaces = document.getElementsByClassName("enlace");
        console.log("Total enlaces:", enlaces.length);
        
        // Método 3: querySelector (primer enlace activo)
        let enlaceActivo = document.querySelector(".activo");
        console.log("Enlace activo:", enlaceActivo.textContent);
        
        // Método 4: querySelectorAll (todos los enlaces)
        let todosEnlaces = document.querySelectorAll("nav a");
        console.log("Navegación tiene", todosEnlaces.length, "enlaces");
        
        // Recorrer y mostrar
        todosEnlaces.forEach(function(enlace, index) {
            console.log("Enlace " + (index+1) + ":", enlace.textContent);
        });
    </script>
</body>
</html>
```

------

### **5.4. Modificar documento**

#### **Modificar contenido de texto**

**textContent** - Modifica solo el texto:

```html
<!DOCTYPE html>
<html>
<body>
    <h1 id="titulo">Título Original</h1>
    <p id="descripcion">Descripción original</p>

    <button onclick="cambiarTexto()">Cambiar Texto</button>

    <script>
        function cambiarTexto() {
            document.getElementById("titulo").textContent = "Título Modificado";
            document.getElementById("descripcion").textContent = "Nueva descripción";
        }
    </script>
</body>
</html>
```

**innerHTML** - Modifica el contenido HTML:

```html
<!DOCTYPE html>
<html>
<body>
    <div id="contenido">
        <p>Contenido original</p>
    </div>

    <button onclick="cambiarHTML()">Cambiar HTML</button>

    <script>
        function cambiarHTML() {
            let div = document.getElementById("contenido");
            div.innerHTML = "<h2>Nuevo título</h2><p>Nuevo párrafo con <strong>negrita</strong></p>";
        }
    </script>
</body>
</html>
```

**Diferencia entre textContent e innerHTML:**

```javascript
let elemento = document.getElementById("prueba");

// textContent: Solo texto, ignora HTML
elemento.textContent = "<strong>Hola</strong>";
// Resultado visible: <strong>Hola</strong> (muestra las etiquetas como texto)

// innerHTML: Interpreta HTML
elemento.innerHTML = "<strong>Hola</strong>";
// Resultado visible: Hola (en negrita)
```

#### **Modificar atributos**

**getAttribute()** y **setAttribute():**

```html
<!DOCTYPE html>
<html>
<body>
    <img id="imagen" src="logo.png" alt="Logo">
    <a id="enlace" href="https://example.com">Enlace</a>

    <button onclick="modificarAtributos()">Modificar</button>

    <script>
        function modificarAtributos() {
            let imagen = document.getElementById("imagen");
            
            // Obtener atributo
            console.log(imagen.getAttribute("src")); // "logo.png"
            
            // Cambiar atributo
            imagen.setAttribute("src", "nuevo-logo.png");
            imagen.setAttribute("alt", "Nuevo Logo");
            
            // Modificar enlace
            let enlace = document.getElementById("enlace");
            enlace.setAttribute("href", "https://google.com");
            enlace.textContent = "Ir a Google";
        }
    </script>
</body>
</html>
```

**Acceso directo a atributos:**

```javascript
let imagen = document.getElementById("miImagen");

// Forma directa (más común)
imagen.src = "foto.jpg";
imagen.alt = "Descripción";

let enlace = document.getElementById("miEnlace");
enlace.href = "https://nueva-url.com";
enlace.target = "_blank";

let input = document.getElementById("miInput");
input.value = "Nuevo valor";
input.placeholder = "Escribe aquí";
```

#### **Modificar estilos CSS**

**Propiedad style:**

```html
<!DOCTYPE html>
<html>
<body>
    <div id="caja" style="width: 100px; height: 100px; background-color: red;">
        Caja
    </div>

    <button onclick="cambiarEstilo()">Cambiar Estilo</button>

    <script>
        function cambiarEstilo() {
            let caja = document.getElementById("caja");
            
            caja.style.backgroundColor = "blue";
            caja.style.width = "200px";
            caja.style.height = "200px";
            caja.style.color = "white";
            caja.style.fontSize = "24px";
            caja.style.border = "5px solid black";
        }
    </script>
</body>
</html>
```

**Nota:** Las propiedades CSS con guiones se escriben en camelCase:

- `background-color` → `backgroundColor`
- `font-size` → `fontSize`
- `margin-top` → `marginTop`

**Ejemplo - Mostrar/Ocultar elemento:**

```html
<!DOCTYPE html>
<html>
<body>
    <div id="mensaje">Este es un mensaje importante</div>
    <button onclick="mostrar()">Mostrar</button>
    <button onclick="ocultar()">Ocultar</button>

    <script>
        function mostrar() {
            document.getElementById("mensaje").style.display = "block";
        }
        
        function ocultar() {
            document.getElementById("mensaje").style.display = "none";
        }
    </script>
</body>
</html>
```

#### **Modificar clases CSS**

**className** - Reemplaza todas las clases:

```html
<!DOCTYPE html>
<html>
<head>
    <style>
        .normal { color: black; font-size: 16px; }
        .destacado { color: red; font-size: 24px; font-weight: bold; }
        .importante { background-color: yellow; padding: 10px; }
    </style>
</head>
<body>
    <p id="texto" class="normal">Texto de ejemplo</p>
    <button onclick="cambiarClase()">Destacar</button>

    <script>
        function cambiarClase() {
            let texto = document.getElementById("texto");
            texto.className = "destacado importante";
        }
    </script>
</body>
</html>
```

**classList** - Manipular clases individualmente (más recomendado):

```html
<!DOCTYPE html>
<html>
<head>
    <style>
        .oculto { display: none; }
        .destacado { background-color: yellow; }
        .borde { border: 2px solid red; }
    </style>
</head>
<body>
    <div id="panel" class="destacado">Panel de control</div>
    
    <button onclick="agregarClase()">Agregar borde</button>
    <button onclick="quitarClase()">Quitar destacado</button>
    <button onclick="alternarClase()">Mostrar/Ocultar</button>
    <button onclick="verificarClase()">¿Tiene borde?</button>

    <script>
        let panel = document.getElementById("panel");
        
        function agregarClase() {
            panel.classList.add("borde");
        }
        
        function quitarClase() {
            panel.classList.remove("destacado");
        }
        
        function alternarClase() {
            panel.classList.toggle("oculto");
        }
        
        function verificarClase() {
            if (panel.classList.contains("borde")) {
                alert("Sí, tiene la clase 'borde'");
            } else {
                alert("No tiene la clase 'borde'");
            }
        }
    </script>
</body>
</html>
```

**Métodos de classList:**

```javascript
let elemento = document.getElementById("miElemento");

elemento.classList.add("nueva-clase");      // Añadir clase
elemento.classList.remove("clase-vieja");   // Eliminar clase
elemento.classList.toggle("activo");        // Alternar (si existe la quita, si no existe la añade)
elemento.classList.contains("activo");      // Verificar si tiene la clase (devuelve true/false)
elemento.classList.replace("vieja", "nueva"); // Reemplazar clase
```

#### **Crear nuevos elementos**

**createElement()** y **appendChild():**

```html
<!DOCTYPE html>
<html>
<body>
    <div id="contenedor"></div>
    <button onclick="agregarElemento()">Agregar Párrafo</button>

    <script>
        function agregarElemento() {
            // 1. Crear el elemento
            let nuevoParrafo = document.createElement("p");
            
            // 2. Añadir contenido
            nuevoParrafo.textContent = "Este es un nuevo párrafo";
            
            // 3. Añadir al documento
            let contenedor = document.getElementById("contenedor");
            contenedor.appendChild(nuevoParrafo);
        }
    </script>
</body>
</html>
```

**Ejemplo - Agregar elementos a una lista:**

```html
<!DOCTYPE html>
<html>
<body>
    <h2>Lista de Tareas</h2>
    <ul id="listaTareas"></ul>
    
    <input type="text" id="nuevaTarea" placeholder="Nueva tarea">
    <button onclick="agregarTarea()">Agregar</button>

    <script>
        function agregarTarea() {
            let texto = document.getElementById("nuevaTarea").value;
            
            if (texto !== "") {
                // Crear nuevo elemento li
                let nuevoItem = document.createElement("li");
                nuevoItem.textContent = texto;
                
                // Añadir a la lista
                document.getElementById("listaTareas").appendChild(nuevoItem);
                
                // Limpiar input
                document.getElementById("nuevaTarea").value = "";
            }
        }
    </script>
</body>
</html>
```

**Crear elementos con atributos:**

```html
<!DOCTYPE html>
<html>
<body>
    <div id="galeria"></div>
    <button onclick="agregarImagen()">Agregar Imagen</button>

    <script>
        function agregarImagen() {
            // Crear elemento img
            let nuevaImagen = document.createElement("img");
            
            // Establecer atributos
            nuevaImagen.src = "foto.jpg";
            nuevaImagen.alt = "Descripción de la foto";
            nuevaImagen.width = 200;
            nuevaImagen.style.margin = "10px";
            
            // Añadir al contenedor
            document.getElementById("galeria").appendChild(nuevaImagen);
        }
    </script>
</body>
</html>
```

#### **Eliminar elementos**

**remove()** - Eliminar el propio elemento:

```html
<!DOCTYPE html>
<html>
<body>
    <div id="aviso">
        <p>Este es un aviso importante</p>
        <button onclick="cerrarAviso()">Cerrar</button>
    </div>

    <script>
        function cerrarAviso() {
            document.getElementById("aviso").remove();
        }
    </script>
</body>
</html>
```

**removeChild()** - Eliminar un hijo específico:

```html
<!DOCTYPE html>
<html>
<body>
    <ul id="lista">
        <li>Elemento 1</li>
        <li>Elemento 2</li>
        <li>Elemento 3</li>
    </ul>
    <button onclick="eliminarPrimero()">Eliminar Primero</button>
    <button onclick="eliminarUltimo()">Eliminar Último</button>

    <script>
        function eliminarPrimero() {
            let lista = document.getElementById("lista");
            if (lista.firstElementChild) {
                lista.removeChild(lista.firstElementChild);
            }
        }
        
        function eliminarUltimo() {
            let lista = document.getElementById("lista");
            if (lista.lastElementChild) {
                lista.removeChild(lista.lastElementChild);
            }
        }
    </script>
</body>
</html>
```

**Ejemplo completo - Lista dinámica con eliminar:**

```html
<!DOCTYPE html>
<html>
<head>
    <style>
        li { margin: 10px 0; }
        .btn-eliminar {
            margin-left: 10px;
            color: red;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <h2>Productos</h2>
    <input type="text" id="nombreProducto" placeholder="Nombre del producto">
    <button onclick="agregarProducto()">Agregar</button>
    
    <ul id="listaProductos"></ul>

    <script>
        function agregarProducto() {
            let nombre = document.getElementById("nombreProducto").value;
            
            if (nombre !== "") {
                // Crear elemento li
                let item = document.createElement("li");
                item.textContent = nombre;
                
                // Crear botón eliminar
                let btnEliminar = document.createElement("span");
                btnEliminar.textContent = "✖";
                btnEliminar.className = "btn-eliminar";
                btnEliminar.onclick = function() {
                    item.remove();
                };
                
                // Añadir botón al item
                item.appendChild(btnEliminar);
                
                // Añadir item a la lista
                document.getElementById("listaProductos").appendChild(item);
                
                // Limpiar input
                document.getElementById("nombreProducto").value = "";
            }
        }
    </script>
</body>
</html>
```

------

### **5.5. HTML DOM API**

#### **¿Qué es la HTML DOM API?**

La **HTML DOM API** es el conjunto de métodos y propiedades específicos para trabajar con elementos HTML desde JavaScript. Cada tipo de elemento HTML tiene propiedades y métodos particulares.

#### **Elementos de formulario**

**Input:**

```html
<!DOCTYPE html>
<html>
<body>
    <input type="text" id="nombre" value="Juan">
    <input type="number" id="edad" value="25">
    <input type="checkbox" id="acepto">
    <label for="acepto">Acepto términos</label>
    
    <button onclick="leerInputs()">Leer Valores</button>

    <script>
        function leerInputs() {
            let nombre = document.getElementById("nombre").value;
            let edad = document.getElementById("edad").value;
            let acepto = document.getElementById("acepto").checked;
            
            console.log("Nombre:", nombre);
            console.log("Edad:", edad);
            console.log("Acepta:", acepto);
        }
    </script>
</body>
</html>
```

**Select:**

```html
<!DOCTYPE html>
<html>
<body>
    <select id="pais">
        <option value="">Seleccione un país</option>
        <option value="es">España</option>
        <option value="fr">Francia</option>
        <option value="it">Italia</option>
    </select>
    
    <button onclick="leerSeleccion()">Ver selección</button>
    <p id="resultado"></p>

    <script>
        function leerSeleccion() {
            let select = document.getElementById("pais");
            let valorSeleccionado = select.value;
            let textoSeleccionado = select.options[select.selectedIndex].text;
            
            document.getElementById("resultado").textContent = 
                "Valor: " + valorSeleccionado + ", Texto: " + textoSeleccionado;
        }
    </script>
</body>
</html>
```

**Textarea:**

```html
<!DOCTYPE html>
<html>
<body>
    <textarea id="comentario" rows="5" cols="40"></textarea>
    <br>
    <button onclick="contarCaracteres()">Contar caracteres</button>
    <p id="contador"></p>

    <script>
        function contarCaracteres() {
            let texto = document.getElementById("comentario").value;
            let cantidad = texto.length;
            
            document.getElementById("contador").textContent = 
                "Caracteres: " + cantidad;
        }
    </script>
</body>
</html>
```

#### **Trabajar con tablas**

```html
<!DOCTYPE html>
<html>
<head>
    <style>
        table { border-collapse: collapse; }
        td, th { border: 1px solid black; padding: 8px; }
    </style>
</head>
<body>
    <table id="tablaProductos">
        <thead>
            <tr>
                <th>Producto</th>
                <th>Precio</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Ratón</td>
                <td>15€</td>
            </tr>
            <tr>
                <td>Teclado</td>
                <td>45€</td>
            </tr>
        </tbody>
    </table>
    
    <button onclick="agregarFila()">Agregar producto</button>

    <script>
        function agregarFila() {
            let tabla = document.getElementById("tablaProductos");
            let tbody = tabla.getElementsByTagName("tbody")[0];
            
            // Crear nueva fila
            let nuevaFila = tbody.insertRow();
            
            // Crear celdas
            let celda1 = nuevaFila.insertCell(0);
            let celda2 = nuevaFila.insertCell(1);
            
            // Añadir contenido
            celda1.textContent = "Monitor";
            celda2.textContent = "120€";
        }
    </script>
</body>
</html>
```

#### **Propiedades de elementos comunes**

**Imágenes:**

```javascript
let img = document.getElementById("miImagen");
img.src = "nueva-foto.jpg";
img.alt = "Descripción";
img.width = 300;
img.height = 200;
```

**Enlaces:**

```javascript
let enlace = document.getElementById("miEnlace");
enlace.href = "https://ejemplo.com";
enlace.target = "_blank";
enlace.title = "Ir a ejemplo";
```

**Elementos de lista:**

```javascript
let lista = document.getElementById("miLista");
console.log(lista.children.length); // Número de items

let primerItem = lista.firstElementChild;
let ultimoItem = lista.lastElementChild;
```

#### **Ejemplo completo - Carrito de compra:**

```html
<!DOCTYPE html>
<html>
<head>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        
        td, th {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        .btn-eliminar {
            background-color: #f44336;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
        }
        .total {
            font-size: 1.2em;
            font-weight: bold;
            margin-top: 10px;
        }
        input {
            padding: 5px;
            margin: 5px;
        }
        button {
            padding: 10px 15px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <h2>Carrito de Compra</h2>
    
    <div>
        <input type="text" id="nombreProducto" placeholder="Nombre del producto">
        <input type="number" id="precioProducto" placeholder="Precio" min="0" step="0.01">
        <input type="number" id="cantidadProducto" placeholder="Cantidad" min="1" value="1">
        <button onclick="agregarAlCarrito()">Agregar al Carrito</button>
    </div>

    <table id="tablaCarrito">
        <thead>
            <tr>
                <th>Producto</th>
                <th>Precio</th>
                <th>Cantidad</th>
                <th>Subtotal</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>

    <div class="total">
        Total: <span id="totalCarrito">0.00</span>€
    </div>

    <script>
        function agregarAlCarrito() {
            let nombre = document.getElementById("nombreProducto").value;
            let precio = parseFloat(document.getElementById("precioProducto").value);
            let cantidad = parseInt(document.getElementById("cantidadProducto").value);

            // Validar campos
            if (nombre === "" || isNaN(precio) || isNaN(cantidad)) {
                alert("Por favor, completa todos los campos correctamente");
                return;
            }

            // Calcular subtotal
            let subtotal = precio * cantidad;

            // Obtener tbody de la tabla
            let tabla = document.getElementById("tablaCarrito");
            let tbody = tabla.getElementsByTagName("tbody")[0];

            // Crear nueva fila
            let fila = tbody.insertRow();

            // Crear celdas
            let celdaNombre = fila.insertCell(0);
            let celdaPrecio = fila.insertCell(1);
            let celdaCantidad = fila.insertCell(2);
            let celdaSubtotal = fila.insertCell(3);
            let celdaAccion = fila.insertCell(4);

            // Añadir contenido a las celdas
            celdaNombre.textContent = nombre;
            celdaPrecio.textContent = precio.toFixed(2) + "€";
            celdaCantidad.textContent = cantidad;
            celdaSubtotal.textContent = subtotal.toFixed(2) + "€";

            // Crear botón eliminar
            let btnEliminar = document.createElement("button");
            btnEliminar.textContent = "Eliminar";
            btnEliminar.className = "btn-eliminar";
            btnEliminar.onclick = function() {
                tbody.removeChild(fila);
                actualizarTotal();
            };
            celdaAccion.appendChild(btnEliminar);

            // Limpiar campos
            document.getElementById("nombreProducto").value = "";
            document.getElementById("precioProducto").value = "";
            document.getElementById("cantidadProducto").value = "1";

            // Actualizar total
            actualizarTotal();
        }

        function actualizarTotal() {
            let tabla = document.getElementById("tablaCarrito");
            let tbody = tabla.getElementsByTagName("tbody")[0];
            let filas = tbody.getElementsByTagName("tr");

            let total = 0;

            // Recorrer todas las filas
            for (let i = 0; i < filas.length; i++) {
                // Obtener el subtotal de la celda 3 (índice 3)
                let subtotalTexto = filas[i].cells[3].textContent;
                // Quitar el símbolo € y convertir a número
                let subtotal = parseFloat(subtotalTexto.replace("€", ""));
                total += subtotal;
            }

            // Mostrar total
            document.getElementById("totalCarrito").textContent = total.toFixed(2);
        }
    </script>
</body>
</html>
```

------

### **Actividades**

#### **Actividad 5.1 - Cambiar contenido y estilos**

Crea una página HTML con:

- Un título `<h1>` con id "titulo"
- Un párrafo `<p>` con id "descripcion"
- Dos botones: uno para cambiar el texto y otro para cambiar los estilos

Al hacer clic en el primer botón, cambia el contenido del título y del párrafo. Al hacer clic en el segundo botón, cambia el color del título a azul, el tamaño de fuente a 32px, y el color del párrafo a verde.

::: details Solución


```html
<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }
        button {
            padding: 10px 20px;
            margin: 10px 5px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <h1 id="titulo">Título Original</h1>
    <p id="descripcion">Esta es la descripción original del contenido.</p>
    
    <button onclick="cambiarTexto()">Cambiar Texto</button>
    <button onclick="cambiarEstilos()">Cambiar Estilos</button>

    <script>
        function cambiarTexto() {
            // Cambiar el contenido del título
            document.getElementById("titulo").textContent = "Título Modificado con JavaScript";
            
            // Cambiar el contenido del párrafo
            document.getElementById("descripcion").textContent = "Esta es la nueva descripción que hemos cambiado dinámicamente.";
        }

        function cambiarEstilos() {
            // Obtener elementos
            let titulo = document.getElementById("titulo");
            let descripcion = document.getElementById("descripcion");
            
            // Cambiar estilos del título
            titulo.style.color = "blue";
            titulo.style.fontSize = "32px";
            
            // Cambiar estilo del párrafo
            descripcion.style.color = "green";
        }
    </script>
</body>
</html>
```

:::
---

#### **Actividad 5.2 - Lista de navegación**

Crea una página con un menú de navegación (`<nav>`) que contenga 4 enlaces (`<a>`). Todos los enlaces deben tener la clase "enlace".

Usando JavaScript:

1. Selecciona todos los enlaces usando `querySelectorAll()`
2. Recórrelos con un bucle y añade la clase "menu-item" a cada uno
3. Cambia el color de todos los enlaces a azul
4. Muestra en consola cuántos enlaces hay

::: details Solución

```html
<!DOCTYPE html>
<html>
<head>
    <style>
        nav {
            background-color: #f0f0f0;
            padding: 20px;
        }
        .enlace {
            text-decoration: none;
            margin: 0 15px;
        }
        .menu-item {
            font-weight: bold;
            padding: 10px;
        }
    </style>
</head>
<body>
    <nav id="menuNavegacion">
        <a href="#inicio" class="enlace">Inicio</a>
        <a href="#productos" class="enlace">Productos</a>
        <a href="#servicios" class="enlace">Servicios</a>
        <a href="#contacto" class="enlace">Contacto</a>
    </nav>

    <script>
        // 1. Seleccionar todos los enlaces
        let enlaces = document.querySelectorAll(".enlace");
        
        // 2. Recorrer y añadir clase "menu-item"
        for (let enlace of enlaces) {
            enlace.classList.add("menu-item");
        }
        
        // Alternativa con forEach:
        // enlaces.forEach(function(enlace) {
        //     enlace.classList.add("menu-item");
        // });
        
        // 3. Cambiar el color de todos los enlaces
        for (let enlace of enlaces) {
            enlace.style.color = "blue";
        }
        
        // 4. Mostrar en consola cuántos enlaces hay
        console.log("Total de enlaces en el menú: " + enlaces.length);
    </script>
</body>
</html>
```
:::
---

#### **Actividad 5.3 - Galería de imágenes**

Crea una página con:

- Un contenedor `<div>` vacío con id "galeria"
- Un botón "Agregar Imagen"

Cada vez que se haga clic en el botón, debe:

1. Crear un nuevo elemento `<img>`
2. Asignarle un src (puede ser una URL de placeholder como "https://via.placeholder.com/200")
3. Establecer un ancho de 200px
4. Añadir un margen de 10px
5. Añadir la imagen al contenedor

**Bonus:** Añade un contador que muestre cuántas imágenes hay en la galería.

::: details Solución

```html
<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }
        #galeria {
            border: 2px solid #ccc;
            padding: 20px;
            min-height: 200px;
            margin-top: 20px;
        }
        #galeria img {
            border: 2px solid #ddd;
            border-radius: 5px;
        }
        button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #45a049;
        }
        #contador {
            margin-top: 20px;
            font-size: 18px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h2>Galería de Imágenes</h2>
    <button onclick="agregarImagen()">Agregar Imagen</button>
    
    <div id="galeria"></div>
    
    <p id="contador">Imágenes en la galería: 0</p>

    <script>
        function agregarImagen() {
            // 1. Crear nuevo elemento img
            let nuevaImagen = document.createElement("img");
            
            // 2. Asignar src (placeholder)
            nuevaImagen.src = "https://via.placeholder.com/200";
            
            // 3. Establecer ancho
            nuevaImagen.width = 200;
            
            // 4. Añadir margen
            nuevaImagen.style.margin = "10px";
            
            // 5. Añadir la imagen al contenedor
            let galeria = document.getElementById("galeria");
            galeria.appendChild(nuevaImagen);
            
            // BONUS: Actualizar contador
            actualizarContador();
        }
        
        function actualizarContador() {
            let galeria = document.getElementById("galeria");
            let totalImagenes = galeria.getElementsByTagName("img").length;
            
            document.getElementById("contador").textContent = 
                "Imágenes en la galería: " + totalImagenes;
        }
    </script>
</body>
</html>
```
:::
---

#### **Actividad 5.4 - Lista de tareas avanzada**

Crea una aplicación de lista de tareas con:

- Un input de texto con id "inputTarea"
- Un botón "Agregar Tarea"
- Una lista `<ul>` vacía con id "listaTareas"
- Un párrafo que muestre el total de tareas

Funcionalidad:

1. Al agregar una tarea, debe aparecer en la lista
2. Cada tarea debe tener un botón "Completar" que añada una clase CSS "completada" (con texto tachado)
3. Cada tarea debe tener un botón "Eliminar" que la quite de la lista
4. El contador de tareas debe actualizarse automáticamente

**CSS sugerido para la clase completada:**

```css
.completada {
    text-decoration: line-through;
    color: gray;
}
```

::: details Solución

```html
<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
        }
        h2 {
            color: #333;
        }
        #inputContainer {
            margin-bottom: 20px;
        }
        #inputTarea {
            padding: 10px;
            width: 70%;
            font-size: 16px;
            border: 2px solid #ddd;
            border-radius: 4px;
        }
        button {
            padding: 10px 20px;
            margin-left: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 16px;
            border-radius: 4px;
        }
        button:hover {
            background-color: #45a049;
        }
        ul {
            list-style-type: none;
            padding: 0;
        }
        li {
            background-color: #f9f9f9;
            padding: 15px;
            margin-bottom: 10px;
            border-left: 4px solid #4CAF50;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .completada {
            text-decoration: line-through;
            color: gray;
            border-left-color: gray;
        }
        .btn-tarea {
            padding: 5px 10px;
            margin-left: 5px;
            font-size: 14px;
        }
        .btn-completar {
            background-color: #2196F3;
        }
        .btn-completar:hover {
            background-color: #0b7dda;
        }
        .btn-eliminar {
            background-color: #f44336;
        }
        .btn-eliminar:hover {
            background-color: #da190b;
        }
        #totalTareas {
            margin-top: 20px;
            font-size: 18px;
            font-weight: bold;
            color: #666;
        }
        .texto-tarea {
            flex-grow: 1;
        }
    </style>
</head>
<body>
    <h2>Lista de Tareas</h2>
    
    <div id="inputContainer">
        <input type="text" id="inputTarea" placeholder="Escribe una nueva tarea...">
        <button onclick="agregarTarea()">Agregar Tarea</button>
    </div>

    <ul id="listaTareas"></ul>
    
    <p id="totalTareas">Total de tareas: 0</p>

    <script>
        function agregarTarea() {
            let inputTarea = document.getElementById("inputTarea");
            let textoTarea = inputTarea.value.trim();
            
            // Validar que no esté vacío
            if (textoTarea === "") {
                alert("Por favor, escribe una tarea");
                return;
            }
            
            // Crear elemento li
            let li = document.createElement("li");
            
            // Crear span para el texto de la tarea
            let spanTexto = document.createElement("span");
            spanTexto.className = "texto-tarea";
            spanTexto.textContent = textoTarea;
            
            // Crear div para los botones
            let divBotones = document.createElement("div");
            
            // Crear botón completar
            let btnCompletar = document.createElement("button");
            btnCompletar.textContent = "Completar";
            btnCompletar.className = "btn-tarea btn-completar";
            btnCompletar.onclick = function() {
                li.classList.toggle("completada");
                // Cambiar texto del botón
                if (li.classList.contains("completada")) {
                    btnCompletar.textContent = "Deshacer";
                } else {
                    btnCompletar.textContent = "Completar";
                }
            };
            
            // Crear botón eliminar
            let btnEliminar = document.createElement("button");
            btnEliminar.textContent = "Eliminar";
            btnEliminar.className = "btn-tarea btn-eliminar";
            btnEliminar.onclick = function() {
                li.remove();
                actualizarContador();
            };
            
            // Añadir botones al div
            divBotones.appendChild(btnCompletar);
            divBotones.appendChild(btnEliminar);
            
            // Añadir elementos al li
            li.appendChild(spanTexto);
            li.appendChild(divBotones);
            
            // Añadir li a la lista
            document.getElementById("listaTareas").appendChild(li);
            
            // Limpiar input
            inputTarea.value = "";
            
            // Actualizar contador
            actualizarContador();
        }
        
        function actualizarContador() {
            let lista = document.getElementById("listaTareas");
            let totalTareas = lista.children.length;
            
            document.getElementById("totalTareas").textContent = 
                "Total de tareas: " + totalTareas;
        }
        
        // Permitir agregar tarea con Enter
        document.getElementById("inputTarea").addEventListener("keypress", function(e) {
            if (e.key === "Enter") {
                agregarTarea();
            }
        });
    </script>
</body>
</html>
```

:::
---

#### **Actividad 5.5 - Formulario de registro**

Crea un formulario de registro con:

- Campo nombre (input text)
- Campo email (input email)
- Campo edad (input number)
- Select de país con al menos 3 opciones
- Checkbox "Acepto términos y condiciones"
- Botón "Registrarse"

Al hacer clic en "Registrarse":

1. Lee todos los valores del formulario
2. Valida que todos los campos estén completos
3. Verifica que el checkbox esté marcado
4. Si todo es correcto, muestra un resumen de los datos en un `<div>` debajo del formulario
5. Si hay errores, muestra mensajes de error específicos

::: details Solución

```html
<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
        }
        h2 {
            color: #333;
            text-align: center;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #555;
        }
        input, select {
            width: 100%;
            padding: 10px;
            border: 2px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            box-sizing: border-box;
        }
        .checkbox-group {
            display: flex;
            align-items: center;
        }
        .checkbox-group input {
            width: auto;
            margin-right: 10px;
        }
        button {
            width: 100%;
            padding: 12px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            margin-top: 10px;
        }
        button:hover {
            background-color: #45a049;
        }
        .error {
            color: red;
            font-size: 14px;
            margin-top: 5px;
            display: none;
        }
        .error.mostrar {
            display: block;
        }
        #resumen {
            margin-top: 30px;
            padding: 20px;
            background-color: #e8f5e9;
            border-left: 4px solid #4CAF50;
            border-radius: 4px;
            display: none;
        }
        #resumen.mostrar {
            display: block;
        }
        #resumen h3 {
            margin-top: 0;
            color: #2e7d32;
        }
        #resumen p {
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <h2>Formulario de Registro</h2>
    
    <form id="formularioRegistro">
        <div class="form-group">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" placeholder="Tu nombre completo">
            <span class="error" id="errorNombre"></span>
        </div>

        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" placeholder="tu@email.com">
            <span class="error" id="errorEmail"></span>
        </div>

        <div class="form-group">
            <label for="edad">Edad:</label>
            <input type="number" id="edad" placeholder="Tu edad" min="1">
            <span class="error" id="errorEdad"></span>
        </div>

        <div class="form-group">
            <label for="pais">País:</label>
            <select id="pais">
                <option value="">Selecciona un país</option>
                <option value="España">España</option>
                <option value="Francia">Francia</option>
                <option value="Italia">Italia</option>
                <option value="Portugal">Portugal</option>
                <option value="Alemania">Alemania</option>
            </select>
            <span class="error" id="errorPais"></span>
        </div>

        <div class="form-group checkbox-group">
            <input type="checkbox" id="terminos">
            <label for="terminos">Acepto los términos y condiciones</label>
        </div>
        <span class="error" id="errorTerminos"></span>

        <button type="submit">Registrarse</button>
    </form>

    <div id="resumen">
        <h3>✓ Registro Exitoso</h3>
        <p><strong>Nombre:</strong> <span id="resumenNombre"></span></p>
        <p><strong>Email:</strong> <span id="resumenEmail"></span></p>
        <p><strong>Edad:</strong> <span id="resumenEdad"></span></p>
        <p><strong>País:</strong> <span id="resumenPais"></span></p>
    </div>

    <script>
        document.getElementById("formularioRegistro").addEventListener("submit", function(e) {
            e.preventDefault();
            
            // Obtener valores
            let nombre = document.getElementById("nombre").value.trim();
            let email = document.getElementById("email").value.trim();
            let edad = document.getElementById("edad").value.trim();
            let pais = document.getElementById("pais").value;
            let terminos = document.getElementById("terminos").checked;
            
            // Limpiar mensajes de error previos
            let errores = document.querySelectorAll(".error");
            errores.forEach(function(error) {
                error.classList.remove("mostrar");
                error.textContent = "";
            });
            
            // Ocultar resumen
            document.getElementById("resumen").classList.remove("mostrar");
            
            let formularioValido = true;
            
            // Validar nombre
            if (nombre === "") {
                document.getElementById("errorNombre").textContent = "El nombre es obligatorio";
                document.getElementById("errorNombre").classList.add("mostrar");
                formularioValido = false;
            } else if (nombre.length < 3) {
                document.getElementById("errorNombre").textContent = "El nombre debe tener al menos 3 caracteres";
                document.getElementById("errorNombre").classList.add("mostrar");
                formularioValido = false;
            }
            
            // Validar email
            if (email === "") {
                document.getElementById("errorEmail").textContent = "El email es obligatorio";
                document.getElementById("errorEmail").classList.add("mostrar");
                formularioValido = false;
            } else if (!email.includes("@") || !email.includes(".")) {
                document.getElementById("errorEmail").textContent = "El formato del email no es válido";
                document.getElementById("errorEmail").classList.add("mostrar");
                formularioValido = false;
            }
            
            // Validar edad
            if (edad === "") {
                document.getElementById("errorEdad").textContent = "La edad es obligatoria";
                document.getElementById("errorEdad").classList.add("mostrar");
                formularioValido = false;
            } else if (isNaN(edad) || parseInt(edad) < 1) {
                document.getElementById("errorEdad").textContent = "La edad debe ser un número válido";
                document.getElementById("errorEdad").classList.add("mostrar");
                formularioValido = false;
            } else if (parseInt(edad) < 18) {
                document.getElementById("errorEdad").textContent = "Debes ser mayor de 18 años";
                document.getElementById("errorEdad").classList.add("mostrar");
                formularioValido = false;
            }
            
            // Validar país
            if (pais === "") {
                document.getElementById("errorPais").textContent = "Debes seleccionar un país";
                document.getElementById("errorPais").classList.add("mostrar");
                formularioValido = false;
            }
            
            // Validar términos
            if (!terminos) {
                document.getElementById("errorTerminos").textContent = "Debes aceptar los términos y condiciones";
                document.getElementById("errorTerminos").classList.add("mostrar");
                formularioValido = false;
            }
            
            // Si todo es válido, mostrar resumen
            if (formularioValido) {
                document.getElementById("resumenNombre").textContent = nombre;
                document.getElementById("resumenEmail").textContent = email;
                document.getElementById("resumenEdad").textContent = edad + " años";
                document.getElementById("resumenPais").textContent = pais;
                
                document.getElementById("resumen").classList.add("mostrar");
                
                // Opcional: Limpiar formulario
                // document.getElementById("formularioRegistro").reset();
            }
        });
    </script>
</body>
</html>
```

:::

---

## **Bloque VI. Eventos**

### **6.1. Introducción a eventos del navegador**

#### **¿Qué son los eventos?**

Los **eventos** son acciones que ocurren en el navegador, generalmente provocadas por el usuario o por el propio sistema. JavaScript puede "escuchar" estos eventos y ejecutar código en respuesta.

Ejemplos de eventos comunes:

- El usuario hace clic en un botón
- El usuario mueve el ratón sobre un elemento
- El usuario escribe en un campo de texto
- La página termina de cargar
- El usuario envía un formulario

#### **Tipos de eventos más comunes**

**Eventos de ratón:**

- `click` - Clic en un elemento
- `dblclick` - Doble clic
- `mouseenter` - El ratón entra en un elemento
- `mouseleave` - El ratón sale de un elemento
- `mouseover` - El ratón pasa por encima
- `mouseout` - El ratón sale del elemento

**Eventos de teclado:**

- `keydown` - Se presiona una tecla
- `keyup` - Se suelta una tecla
- `keypress` - Se presiona y suelta una tecla

**Eventos de formulario:**

- `submit` - Se envía un formulario
- `change` - Cambia el valor de un input
- `focus` - Un elemento recibe el foco
- `blur` - Un elemento pierde el foco
- `input` - Se escribe en un input

**Eventos de ventana:**

- `load` - La página termina de cargar
- `resize` - Se redimensiona la ventana
- `scroll` - Se hace scroll en la página

#### **Formas de manejar eventos**

**1. Atributos HTML (no recomendado):**

```html
<!DOCTYPE html>
<html>
<body>
    <button onclick="mostrarMensaje()">Hacer clic</button>

    <script>
        function mostrarMensaje() {
            alert("Has hecho clic en el botón");
        }
    </script>
</body>
</html>
```

**2. Propiedades del DOM (mejor):**

```html
<!DOCTYPE html>
<html>
<body>
    <button id="miBoton">Hacer clic</button>

    <script>
        let boton = document.getElementById("miBoton");
        
        boton.onclick = function() {
            alert("Has hecho clic en el botón");
        };
    </script>
</body>
</html>
```

**3. addEventListener (más recomendado):**

```html
<!DOCTYPE html>
<html>
<body>
    <button id="miBoton">Hacer clic</button>

    <script>
        let boton = document.getElementById("miBoton");
        
        boton.addEventListener("click", function() {
            alert("Has hecho clic en el botón");
        });
    </script>
</body>
</html>
```

**Ventajas de addEventListener:**

- Permite añadir múltiples eventos al mismo elemento
- Permite eliminar eventos
- Mejor control y flexibilidad

#### **Sintaxis de addEventListener**

```javascript
elemento.addEventListener(tipo_evento, función_manejadora);
```

**Ejemplo básico:**

```html
<!DOCTYPE html>
<html>
<body>
    <button id="boton1">Botón 1</button>
    <button id="boton2">Botón 2</button>
    <p id="mensaje"></p>

    <script>
        let mensaje = document.getElementById("mensaje");
        
        document.getElementById("boton1").addEventListener("click", function() {
            mensaje.textContent = "Has hecho clic en el Botón 1";
        });
        
        document.getElementById("boton2").addEventListener("click", function() {
            mensaje.textContent = "Has hecho clic en el Botón 2";
        });
    </script>
</body>
</html>
```

#### **Eventos de ratón**

**Evento click:**

```html
<!DOCTYPE html>
<html>
<head>
    <style>
        #caja {
            width: 200px;
            height: 200px;
            background-color: lightblue;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            margin: 20px;
        }
    </style>
</head>
<body>
    <div id="caja">Haz clic aquí</div>
    <p id="contador">Clics: 0</p>

    <script>
        let caja = document.getElementById("caja");
        let contador = document.getElementById("contador");
        let clics = 0;
        
        caja.addEventListener("click", function() {
            clics++;
            contador.textContent = "Clics: " + clics;
            
            // Cambiar color aleatorio
            let colores = ["lightblue", "lightgreen", "lightcoral", "lightyellow", "lightpink"];
            let colorAleatorio = colores[Math.floor(Math.random() * colores.length)];
            caja.style.backgroundColor = colorAleatorio;
        });
    </script>
</body>
</html>
```

**Evento mouseenter y mouseleave:**

```html
<!DOCTYPE html>
<html>
<head>
    <style>
        .tarjeta {
            width: 250px;
            padding: 20px;
            margin: 20px;
            border: 2px solid #ddd;
            border-radius: 8px;
            transition: all 0.3s;
            cursor: pointer;
        }
        .tarjeta h3 {
            margin-top: 0;
        }
    </style>
</head>
<body>
    <div class="tarjeta" id="tarjeta1">
        <h3>Producto 1</h3>
        <p>Descripción del producto</p>
        <p><strong>Precio: 29.99€</strong></p>
    </div>

    <script>
        let tarjeta = document.getElementById("tarjeta1");
        
        tarjeta.addEventListener("mouseenter", function() {
            tarjeta.style.backgroundColor = "#f0f0f0";
            tarjeta.style.transform = "scale(1.05)";
            tarjeta.style.boxShadow = "0 4px 8px rgba(0,0,0,0.2)";
        });
        
        tarjeta.addEventListener("mouseleave", function() {
            tarjeta.style.backgroundColor = "white";
            tarjeta.style.transform = "scale(1)";
            tarjeta.style.boxShadow = "none";
        });
    </script>
</body>
</html>
```

**Evento dblclick (doble clic):**

```html
<!DOCTYPE html>
<html>
<body>
    <h2 id="titulo">Haz doble clic en este título</h2>
    <p id="info"></p>

    <script>
        let titulo = document.getElementById("titulo");
        let info = document.getElementById("info");
        
        titulo.addEventListener("dblclick", function() {
            titulo.style.color = "red";
            titulo.style.fontSize = "32px";
            info.textContent = "¡Has hecho doble clic!";
        });
    </script>
</body>
</html>
```

#### **Eventos de teclado**

**Evento keydown y keyup:**

```html
<!DOCTYPE html>
<html>
<body>
    <h2>Escribe algo:</h2>
    <input type="text" id="campoTexto" placeholder="Escribe aquí...">
    <p id="info"></p>

    <script>
        let campo = document.getElementById("campoTexto");
        let info = document.getElementById("info");
        
        campo.addEventListener("keydown", function(evento) {
            info.textContent = "Tecla presionada: " + evento.key;
        });
        
        campo.addEventListener("keyup", function(evento) {
            info.textContent = "Tecla soltada: " + evento.key;
        });
    </script>
</body>
</html>
```

**Detectar teclas específicas:**

```html
<!DOCTYPE html>
<html>
<head>
    <style>
        #cuadrado {
            width: 50px;
            height: 50px;
            background-color: blue;
            position: absolute;
            top: 100px;
            left: 100px;
        }
    </style>
</head>
<body>
    <h2>Usa las flechas del teclado para mover el cuadrado</h2>
    <div id="cuadrado"></div>

    <script>
        let cuadrado = document.getElementById("cuadrado");
        let posicionX = 100;
        let posicionY = 100;
        
        document.addEventListener("keydown", function(evento) {
            let paso = 10;
            
            if (evento.key === "ArrowUp") {
                posicionY -= paso;
            } else if (evento.key === "ArrowDown") {
                posicionY += paso;
            } else if (evento.key === "ArrowLeft") {
                posicionX -= paso;
            } else if (evento.key === "ArrowRight") {
                posicionX += paso;
            }
            
            cuadrado.style.top = posicionY + "px";
            cuadrado.style.left = posicionX + "px";
        });
    </script>
</body>
</html>
```

#### **Eventos de formulario**

**Evento submit:**

```html
<!DOCTYPE html>
<html>
<body>
    <h2>Formulario de Búsqueda</h2>
    <form id="formularioBusqueda">
        <input type="text" id="busqueda" placeholder="Buscar...">
        <button type="submit">Buscar</button>
    </form>
    <p id="resultado"></p>

    <script>
        let formulario = document.getElementById("formularioBusqueda");
        let resultado = document.getElementById("resultado");
        
        formulario.addEventListener("submit", function(evento) {
            evento.preventDefault(); // Evitar que se recargue la página
            
            let termino = document.getElementById("busqueda").value;
            resultado.textContent = "Buscando: " + termino;
        });
    </script>
</body>
</html>
```

**Evento change:**

```html
<!DOCTYPE html>
<html>
<body>
    <h2>Selecciona un producto</h2>
    <select id="producto">
        <option value="">Selecciona...</option>
        <option value="portatil">Portátil - 899€</option>
        <option value="raton">Ratón - 29€</option>
        <option value="teclado">Teclado - 59€</option>
    </select>
    <p id="seleccion"></p>

    <script>
        let select = document.getElementById("producto");
        let seleccion = document.getElementById("seleccion");
        
        select.addEventListener("change", function() {
            if (select.value !== "") {
                let textoSeleccionado = select.options[select.selectedIndex].text;
                seleccion.textContent = "Has seleccionado: " + textoSeleccionado;
            } else {
                seleccion.textContent = "";
            }
        });
    </script>
</body>
</html>
```

**Evento input (tiempo real):**

```html
<!DOCTYPE html>
<html>
<body>
    <h2>Contador de caracteres</h2>
    <textarea id="comentario" rows="5" cols="40" maxlength="200" 
              placeholder="Escribe tu comentario (máximo 200 caracteres)"></textarea>
    <p>Caracteres: <span id="contador">0</span> / 200</p>

    <script>
        let textarea = document.getElementById("comentario");
        let contador = document.getElementById("contador");
        
        textarea.addEventListener("input", function() {
            contador.textContent = textarea.value.length;
        });
    </script>
</body>
</html>
```

**Eventos focus y blur:**

```html
<!DOCTYPE html>
<html>
<head>
    <style>
        input {
            padding: 10px;
            margin: 10px 0;
            border: 2px solid #ddd;
            width: 300px;
        }
        input.enfocado {
            border-color: blue;
            background-color: #f0f8ff;
        }
        .ayuda {
            color: #666;
            font-size: 14px;
            display: none;
        }
        .ayuda.mostrar {
            display: block;
        }
    </style>
</head>
<body>
    <h2>Formulario con ayuda</h2>
    
    <label>Nombre de usuario:</label>
    <input type="text" id="usuario">
    <p class="ayuda" id="ayudaUsuario">Mínimo 4 caracteres, sin espacios</p>
    
    <label>Email:</label>
    <input type="email" id="email">
    <p class="ayuda" id="ayudaEmail">Introduce un email válido</p>

    <script>
        let inputUsuario = document.getElementById("usuario");
        let inputEmail = document.getElementById("email");
        let ayudaUsuario = document.getElementById("ayudaUsuario");
        let ayudaEmail = document.getElementById("ayudaEmail");
        
        // Evento focus - cuando el campo recibe el foco
        inputUsuario.addEventListener("focus", function() {
            inputUsuario.classList.add("enfocado");
            ayudaUsuario.classList.add("mostrar");
        });
        
        // Evento blur - cuando el campo pierde el foco
        inputUsuario.addEventListener("blur", function() {
            inputUsuario.classList.remove("enfocado");
            ayudaUsuario.classList.remove("mostrar");
        });
        
        inputEmail.addEventListener("focus", function() {
            inputEmail.classList.add("enfocado");
            ayudaEmail.classList.add("mostrar");
        });
        
        inputEmail.addEventListener("blur", function() {
            inputEmail.classList.remove("enfocado");
            ayudaEmail.classList.remove("mostrar");
        });
    </script>
</body>
</html>
```

#### **El objeto evento**

Cuando ocurre un evento, JavaScript crea un objeto con información sobre ese evento:

```html
<!DOCTYPE html>
<html>
<body>
    <button id="boton">Haz clic</button>
    <p id="info"></p>

    <script>
        let boton = document.getElementById("boton");
        let info = document.getElementById("info");
        
        boton.addEventListener("click", function(evento) {
            console.log(evento); // Objeto completo del evento
            
            info.innerHTML = 
                "Tipo de evento: " + evento.type + "<br>" +
                "Elemento objetivo: " + evento.target.tagName + "<br>" +
                "Posición X: " + evento.clientX + "<br>" +
                "Posición Y: " + evento.clientY;
        });
    </script>
</body>
</html>
```

**Propiedades útiles del objeto evento:**

- `evento.type` - Tipo de evento (click, keydown, etc.)
- `evento.target` - Elemento que disparó el evento
- `evento.clientX / evento.clientY` - Coordenadas del ratón
- `evento.key` - Tecla presionada
- `evento.preventDefault()` - Prevenir comportamiento por defecto

#### **Ejemplo completo - Menú interactivo:**

```html
<!DOCTYPE html>
<html>
<head>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
        }
        nav {
            background-color: #333;
            padding: 0;
        }
        nav ul {
            list-style: none;
            display: flex;
        }
        nav li {
            position: relative;
        }
        nav a {
            display: block;
            padding: 15px 20px;
            color: white;
            text-decoration: none;
            transition: background-color 0.3s;
        }
        nav a:hover {
            background-color: #555;
        }
        .submenu {
            display: none;
            position: absolute;
            background-color: #444;
            min-width: 200px;
            top: 100%;
            left: 0;
        }
        .submenu li {
            width: 100%;
        }
        .submenu a {
            padding: 10px 20px;
        }
        .submenu.mostrar {
            display: block;
        }
        .contenido {
            padding: 40px;
        }
        .seccion {
            display: none;
            animation: aparecer 0.5s;
        }
        .seccion.activa {
            display: block;
        }
        @keyframes aparecer {
            from { opacity: 0; }
            to { opacity: 1; }
        }
    </style>
</head>
<body>
    <nav>
        <ul>
            <li>
                <a href="#" data-seccion="inicio">Inicio</a>
            </li>
            <li id="menuProductos">
                <a href="#" data-seccion="productos">Productos</a>
                <ul class="submenu" id="submenuProductos">
                    <li><a href="#" data-seccion="electronica">Electrónica</a></li>
                    <li><a href="#" data-seccion="ropa">Ropa</a></li>
                    <li><a href="#" data-seccion="hogar">Hogar</a></li>
                </ul>
            </li>
            <li>
                <a href="#" data-seccion="servicios">Servicios</a>
            </li>
            <li>
                <a href="#" data-seccion="contacto">Contacto</a>
            </li>
        </ul>
    </nav>

    <div class="contenido">
        <div id="inicio" class="seccion activa">
            <h1>Bienvenido</h1>
            <p>Esta es la página de inicio.</p>
        </div>
        <div id="productos" class="seccion">
            <h1>Productos</h1>
            <p>Catálogo de productos.</p>
        </div>
        <div id="electronica" class="seccion">
            <h1>Electrónica</h1>
            <p>Productos de electrónica.</p>
        </div>
        <div id="ropa" class="seccion">
            <h1>Ropa</h1>
            <p>Productos de ropa.</p>
        </div>
        <div id="hogar" class="seccion">
            <h1>Hogar</h1>
            <p>Productos para el hogar.</p>
        </div>
        <div id="servicios" class="seccion">
            <h1>Servicios</h1>
            <p>Nuestros servicios.</p>
        </div>
        <div id="contacto" class="seccion">
            <h1>Contacto</h1>
            <p>Información de contacto.</p>
        </div>
    </div>

    <script>
        // Mostrar/ocultar submenú
        let menuProductos = document.getElementById("menuProductos");
        let submenu = document.getElementById("submenuProductos");
        
        menuProductos.addEventListener("mouseenter", function() {
            submenu.classList.add("mostrar");
        });
        
        menuProductos.addEventListener("mouseleave", function() {
            submenu.classList.remove("mostrar");
        });
        
        // Cambiar de sección
        let enlaces = document.querySelectorAll("nav a");
        
        enlaces.forEach(function(enlace) {
            enlace.addEventListener("click", function(evento) {
                evento.preventDefault();
                
                let seccionId = enlace.getAttribute("data-seccion");
                
                // Ocultar todas las secciones
                let secciones = document.querySelectorAll(".seccion");
                secciones.forEach(function(seccion) {
                    seccion.classList.remove("activa");
                });
                
                // Mostrar la sección seleccionada
                document.getElementById(seccionId).classList.add("activa");
                
                // Ocultar submenú
                submenu.classList.remove("mostrar");
            });
        });
    </script>
</body>
</html>
```

------

### **6.2. Evento Load**

#### **¿Qué es el evento load?**

El evento `load` se dispara cuando la página web ha terminado de cargar completamente, incluyendo:

- Todo el HTML
- Todas las imágenes
- Todos los estilos CSS
- Todos los scripts

Es útil cuando necesitamos asegurarnos de que todos los elementos estén disponibles antes de ejecutar nuestro código JavaScript.

#### **Usar el evento load**

**Forma 1: En el objeto window:**

```html
<!DOCTYPE html>
<html>
<head>
    <script>
        window.addEventListener("load", function() {
            console.log("La página ha cargado completamente");
            
            // Ahora podemos acceder a cualquier elemento
            let titulo = document.getElementById("titulo");
            titulo.textContent = "Página Cargada";
            titulo.style.color = "green";
        });
    </script>
</head>
<body>
    <h1 id="titulo">Cargando...</h1>
    <p>Contenido de la página</p>
</body>
</html>
```

**Forma 2: Atributo onload (menos recomendado):**

```html
<!DOCTYPE html>
<html>
<head>
    <script>
        function inicializar() {
            console.log("Página cargada");
            document.getElementById("mensaje").textContent = "¡Bienvenido!";
        }
    </script>
</head>
<body onload="inicializar()">
    <h1>Mi Sitio Web</h1>
    <p id="mensaje"></p>
</body>
</html>
```

#### **DOMContentLoaded vs load**

- **DOMContentLoaded**: Se dispara cuando el HTML está cargado y parseado (sin esperar imágenes y estilos)
- **load**: Se dispara cuando TODO está cargado (incluyendo imágenes y estilos)

```html
<!DOCTYPE html>
<html>
<head>
    <script>
        // Se ejecuta cuando el DOM está listo (más rápido)
        document.addEventListener("DOMContentLoaded", function() {
            console.log("DOM listo");
            document.getElementById("dom").textContent = "✓ DOM Cargado";
        });
        
        // Se ejecuta cuando TODO está cargado (más lento)
        window.addEventListener("load", function() {
            console.log("TODO cargado");
            document.getElementById("completo").textContent = "✓ Página Completa";
        });
    </script>
</head>
<body>
    <h1>Eventos de Carga</h1>
    <p id="dom">Esperando DOM...</p>
    <p id="completo">Esperando carga completa...</p>
    <img src="https://via.placeholder.com/800x400" alt="Imagen grande">
</body>
</html>
```

#### **Ejemplo práctico - Splash screen (pantalla de carga):**

```html
<!DOCTYPE html>
<html>
<head>
    <style>
        #pantallaCarga {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            z-index: 9999;
        }
        #pantallaCarga h1 {
            color: white;
            font-family: Arial, sans-serif;
        }
        .spinner {
            border: 5px solid #f3f3f3;
            border-top: 5px solid #3498db;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: girar 1s linear infinite;
        }
        @keyframes girar {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        #pantallaCarga.ocultar {
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
        }

        #contenido {
            padding: 40px;
            font-family: Arial, sans-serif;
        }
    </style>
</head>
<body>
    <div id="pantallaCarga">
        <h1>Cargando...</h1>
        <div class="spinner"></div>
    </div>

    <div id="contenido">
        <h1>Bienvenido a Mi Sitio Web</h1>
        <p>Este contenido se muestra después de cargar la página.</p>
        <img src="https://picsum.photos/600/300?random=1" alt="Imagen de ejemplo">
        <img src="https://picsum.photos/600/300?random=2" alt="Imagen de ejemplo">
        <img src="https://picsum.photos/600/300?random=3" alt="Imagen de ejemplo">
    </div>

    <script>
        window.addEventListener("load", function() {
            // Simular un pequeño retraso adicional
            setTimeout(function() {
                document.getElementById("pantallaCarga").classList.add("ocultar");
            }, 500);
        });
    </script>
</body>
</html>
```

#### **Ejemplo - Inicialización de aplicación:**

```html
<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }
        .estadistica {
            display: inline-block;
            margin: 10px;
            padding: 20px;
            background-color: #f0f0f0;
            border-radius: 8px;
            min-width: 150px;
            text-align: center;
        }
        .estadistica h3 {
            margin: 0 0 10px 0;
            color: #333;
        }
        .estadistica p {
            font-size: 32px;
            font-weight: bold;
            color: #4CAF50;
            margin: 0;
        }
        #ultimaActualizacion {
            margin-top: 20px;
            color: #666;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <h1>Panel de Control</h1>
    <p id="estado">Inicializando...</p>
    
    <div id="estadisticas"></div>
    
    <p id="ultimaActualizacion"></p>

    <script>
        window.addEventListener("load", function() {
            inicializarAplicacion();
        });
        
        function inicializarAplicacion() {
            // Cambiar estado
            document.getElementById("estado").textContent = "Sistema inicializado correctamente";
            document.getElementById("estado").style.color = "green";
            
            // Cargar estadísticas
            cargarEstadisticas();
            
            // Mostrar fecha de actualización
            mostrarFecha();
            
            // Configurar actualizaciones automáticas
            setInterval(actualizarEstadisticas, 5000); // Actualizar cada 5 segundos
        }
        
        function cargarEstadisticas() {
            let contenedor = document.getElementById("estadisticas");
            
            let estadisticas = [
                { titulo: "Usuarios", valor: Math.floor(Math.random() * 1000) },
                { titulo: "Ventas", valor: Math.floor(Math.random() * 500) },
                { titulo: "Productos", valor: Math.floor(Math.random() * 200) },
                { titulo: "Visitas", valor: Math.floor(Math.random() * 5000) }
            ];
            
            estadisticas.forEach(function(stat) {
                let div = document.createElement("div");
                div.className = "estadistica";
                div.innerHTML = "<h3>" + stat.titulo + "</h3><p>" + stat.valor + "</p>";
                contenedor.appendChild(div);
            });
        }
        
        function actualizarEstadisticas() {
            let estadisticas = document.querySelectorAll(".estadistica p");
            
            estadisticas.forEach(function(stat) {
                let nuevoValor = Math.floor(Math.random() * 5000);
                stat.textContent = nuevoValor;
            });
            
            mostrarFecha();
        }
        
        function mostrarFecha() {
            let ahora = new Date();
            let fecha = ahora.toLocaleString();
            document.getElementById("ultimaActualizacion").textContent = 
                "Última actualización: " + fecha;
        }
    </script>
</body>
</html>
```

#### **Ejemplo - Validación de imágenes cargadas:**

```html
<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }
        .galeria {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 15px;
            margin: 20px 0;
        }
        .imagen-container {
            position: relative;
            background-color: #f0f0f0;
            padding: 10px;
            border-radius: 8px;
        }
        .imagen-container img {
            width: 100%;
            border-radius: 5px;
        }
        .cargando {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: #999;
        }
        .estado {
            margin: 20px 0;
            padding: 15px;
            background-color: #e3f2fd;
            border-left: 4px solid #2196F3;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <h1>Galería de Imágenes</h1>
    <div class="estado" id="estado">Cargando galería...</div>
    <div class="galeria" id="galeria"></div>

    <script>
        let imagenesParaCargar = [
            "https://picsum.photos/600/300?random=1?text=Imagen+1",
            "https://picsum.photos/600/300?random=2?text=Imagen+2",
            "https://picsum.photos/600/300?random=3?text=Imagen+3",
            "https://picsum.photos/600/300?random=4?text=Imagen+4",
            "https://picsum.photos/600/300?random=5?text=Imagen+5",
            "https://picsum.photos/600/300?random=6?text=Imagen+6",
        ];
        
        let imagenesCargadas = 0;
        let totalImagenes = imagenesParaCargar.length;
        
        window.addEventListener("load", function() {
            cargarGaleria();
        });
        
        function cargarGaleria() {
            let galeria = document.getElementById("galeria");
            
            imagenesParaCargar.forEach(function(urlImagen, index) {
                // Crear contenedor
                let contenedor = document.createElement("div");
                contenedor.className = "imagen-container";
                contenedor.innerHTML = '<span class="cargando">Cargando...</span>';
                
                // Crear imagen
                let img = document.createElement("img");
                img.src = urlImagen;
                img.alt = "Imagen " + (index + 1);
                
                // Evento cuando la imagen se carga
                img.addEventListener("load", function() {
                    imagenesCargadas++;
                    contenedor.querySelector(".cargando").style.display = "none";
                    actualizarEstado();
                });
                
                // Evento si la imagen falla al cargar
                img.addEventListener("error", function() {
                    contenedor.innerHTML = '<p style="color: red;">Error al cargar</p>';
                    imagenesCargadas++;
                    actualizarEstado();
                });
                
                contenedor.appendChild(img);
                galeria.appendChild(contenedor);
            });
        }
        
        function actualizarEstado() {
            let estado = document.getElementById("estado");
            let porcentaje = Math.round((imagenesCargadas / totalImagenes) * 100);
            
            if (imagenesCargadas < totalImagenes) {
                estado.textContent = "Cargando imágenes: " + imagenesCargadas + 
                                    "/" + totalImagenes + " (" + porcentaje + "%)";
            } else {
                estado.textContent = "✓ Todas las imágenes han sido cargadas";
                estado.style.backgroundColor = "#e8f5e9";
                estado.style.borderLeftColor = "#4CAF50";
            }
        }
    </script>
</body>
</html>
```

------

### **Actividades**

#### **Actividad 6.1 - Cambio de tema**

Crea una página web con:

- Un botón "Cambiar Tema"
- Contenido de ejemplo (título, párrafos, etc.)

Al hacer clic en el botón, debe alternar entre tema claro y tema oscuro:

- **Tema claro**: fondo blanco, texto negro
- **Tema oscuro**: fondo negro (#222), texto blanco

**Pista:** Usa `addEventListener` con el evento `click` y modifica los estilos con JavaScript.

::: details Solución

```html
<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 40px;
            transition: all 0.3s;
        }
        .tema-claro {
            background-color: white;
            color: black;
        }
        .tema-oscuro {
            background-color: #222;
            color: white;
        }
        button {
            padding: 12px 24px;
            font-size: 16px;
            cursor: pointer;
            border: none;
            border-radius: 5px;
            margin-bottom: 30px;
        }
        .tema-claro button {
            background-color: #333;
            color: white;
        }
        .tema-oscuro button {
            background-color: #fff;
            color: #333;
        }
        button:hover {
            opacity: 0.8;
        }
        h1 {
            margin-bottom: 20px;
        }
        p {
            line-height: 1.6;
            margin-bottom: 15px;
        }
    </style>
</head>
<body class="tema-claro">
    <button id="btnTema">Cambiar a Tema Oscuro</button>
    
    <h1>Mi Sitio Web</h1>
    <p>Este es un ejemplo de cambio de tema con JavaScript.</p>
    <p>Haz clic en el botón para alternar entre el tema claro y oscuro.</p>
    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>

    <script>
        let boton = document.getElementById("btnTema");
        let body = document.body;
        let temaOscuro = false;
        
        boton.addEventListener("click", function() {
            if (temaOscuro) {
                // Cambiar a tema claro
                body.classList.remove("tema-oscuro");
                body.classList.add("tema-claro");
                boton.textContent = "Cambiar a Tema Oscuro";
                temaOscuro = false;
            } else {
                // Cambiar a tema oscuro
                body.classList.remove("tema-claro");
                body.classList.add("tema-oscuro");
                boton.textContent = "Cambiar a Tema Claro";
                temaOscuro = true;
            }
        });
    </script>
</body>
</html>
```

:::
---

#### **Actividad 6.2 - Formulario interactivo**

Crea un formulario de contacto con:

- Campo nombre (input text)
- Campo email (input email)
- Campo mensaje (textarea)
- Botón enviar

Implementa las siguientes funcionalidades con eventos:

1. Al hacer **focus** en cada campo, cambia su borde a azul
2. Al hacer **blur** en cada campo, vuelve el borde a gris
3. En el campo mensaje, muestra en tiempo real (**evento input**) el número de caracteres escritos (máximo 200)
4. Al enviar el formulario (**evento submit**), previene el envío y muestra un resumen de los datos introducidos

::: details Solución

```html
<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
        }
        h2 {
            color: #333;
            margin-bottom: 30px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #555;
        }
        input, textarea {
            width: 100%;
            padding: 10px;
            border: 2px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            box-sizing: border-box;
            transition: border-color 0.3s;
        }
        input:focus, textarea:focus {
            outline: none;
        }
        textarea {
            resize: vertical;
            min-height: 100px;
        }
        .contador {
            text-align: right;
            font-size: 14px;
            color: #666;
            margin-top: 5px;
        }
        button {
            width: 100%;
            padding: 12px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
        #resumen {
            margin-top: 30px;
            padding: 20px;
            background-color: #e8f5e9;
            border-left: 4px solid #4CAF50;
            border-radius: 4px;
            display: none;
        }
        #resumen.mostrar {
            display: block;
        }
        #resumen h3 {
            margin-top: 0;
            color: #2e7d32;
        }
        #resumen p {
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <h2>Formulario de Contacto</h2>
    
    <form id="formularioContacto">
        <div class="form-group">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" required>
        </div>

        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" required>
        </div>

        <div class="form-group">
            <label for="mensaje">Mensaje:</label>
            <textarea id="mensaje" maxlength="200" required></textarea>
            <div class="contador">
                <span id="caracteres">0</span> / 200 caracteres
            </div>
        </div>

        <button type="submit">Enviar</button>
    </form>

    <div id="resumen">
        <h3>✓ Datos del formulario</h3>
        <p><strong>Nombre:</strong> <span id="resumenNombre"></span></p>
        <p><strong>Email:</strong> <span id="resumenEmail"></span></p>
        <p><strong>Mensaje:</strong> <span id="resumenMensaje"></span></p>
    </div>

    <script>
        let inputNombre = document.getElementById("nombre");
        let inputEmail = document.getElementById("email");
        let textareaMensaje = document.getElementById("mensaje");
        let contadorCaracteres = document.getElementById("caracteres");
        let formulario = document.getElementById("formularioContacto");
        let resumen = document.getElementById("resumen");

        // Evento focus - cambiar borde a azul
        inputNombre.addEventListener("focus", function() {
            inputNombre.style.borderColor = "blue";
        });

        inputEmail.addEventListener("focus", function() {
            inputEmail.style.borderColor = "blue";
        });

        textareaMensaje.addEventListener("focus", function() {
            textareaMensaje.style.borderColor = "blue";
        });

        // Evento blur - volver borde a gris
        inputNombre.addEventListener("blur", function() {
            inputNombre.style.borderColor = "#ddd";
        });

        inputEmail.addEventListener("blur", function() {
            inputEmail.style.borderColor = "#ddd";
        });

        textareaMensaje.addEventListener("blur", function() {
            textareaMensaje.style.borderColor = "#ddd";
        });

        // Evento input - contador de caracteres en tiempo real
        textareaMensaje.addEventListener("input", function() {
            let cantidad = textareaMensaje.value.length;
            contadorCaracteres.textContent = cantidad;
            
            // Cambiar color si se acerca al límite
            if (cantidad > 180) {
                contadorCaracteres.style.color = "red";
            } else if (cantidad > 150) {
                contadorCaracteres.style.color = "orange";
            } else {
                contadorCaracteres.style.color = "#666";
            }
        });

        // Evento submit - mostrar resumen
        formulario.addEventListener("submit", function(evento) {
            evento.preventDefault(); // Prevenir envío del formulario
            
            // Obtener valores
            let nombre = inputNombre.value;
            let email = inputEmail.value;
            let mensaje = textareaMensaje.value;
            
            // Mostrar en el resumen
            document.getElementById("resumenNombre").textContent = nombre;
            document.getElementById("resumenEmail").textContent = email;
            document.getElementById("resumenMensaje").textContent = mensaje;
            
            // Mostrar el resumen
            resumen.classList.add("mostrar");
            
            // Scroll suave hacia el resumen
            resumen.scrollIntoView({ behavior: "smooth" });
        });
    </script>
</body>
</html>
```

:::
---

#### **Actividad 6.3 - Galería de productos con hover**

Crea una galería con 4 productos (divs). Cada producto debe mostrar:

- Imagen (puedes usar placeholders)
- Nombre del producto
- Precio

Implementa con eventos:

1. Al pasar el ratón sobre un producto (**mouseenter**), debe:
   - Aumentar ligeramente su tamaño (scale 1.05)
   - Cambiar el color de fondo
   - Mostrar un botón "Ver detalles"
2. Al quitar el ratón (**mouseleave**), debe volver a su estado original

**CSS sugerido:**

```css
.producto {
    transition: all 0.3s;
}
```

::: details Solución

```html
<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 40px;
            background-color: #f5f5f5;
        }
        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 40px;
        }
        .galeria {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
            max-width: 1200px;
            margin: 0 auto;
        }
        .producto {
            background-color: white;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            transition: all 0.3s;
            cursor: pointer;
        }
        .producto img {
            width: 100%;
            border-radius: 8px;
            margin-bottom: 15px;
        }
        .producto h3 {
            margin: 10px 0;
            color: #333;
        }
        .producto .precio {
            font-size: 24px;
            color: #4CAF50;
            font-weight: bold;
            margin: 10px 0;
        }
        .btn-detalles {
            display: none;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            margin-top: 10px;
        }
        .btn-detalles:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <h1>Catálogo de Productos</h1>
    
    <div class="galeria">
        <div class="producto">
            <img src="https://via.placeholder.com/300x200/FF6B6B/FFFFFF?text=Portátil" alt="Portátil">
            <h3>Portátil Gaming</h3>
            <p class="precio">899€</p>
            <button class="btn-detalles">Ver detalles</button>
        </div>

        <div class="producto">
            <img src="https://via.placeholder.com/300x200/4ECDC4/FFFFFF?text=Ratón" alt="Ratón">
            <h3>Ratón Inalámbrico</h3>
            <p class="precio">29€</p>
            <button class="btn-detalles">Ver detalles</button>
        </div>

        <div class="producto">
            <img src="https://via.placeholder.com/300x200/45B7D1/FFFFFF?text=Teclado" alt="Teclado">
            <h3>Teclado Mecánico</h3>
            <p class="precio">79€</p>
            <button class="btn-detalles">Ver detalles</button>
        </div>

        <div class="producto">
            <img src="https://via.placeholder.com/300x200/FFA07A/FFFFFF?text=Monitor" alt="Monitor">
            <h3>Monitor 27" 4K</h3>
            <p class="precio">349€</p>
            <button class="btn-detalles">Ver detalles</button>
        </div>
    </div>

    <script>
        // Seleccionar todos los productos
        let productos = document.querySelectorAll(".producto");

        // Añadir eventos a cada producto
        productos.forEach(function(producto) {
            let boton = producto.querySelector(".btn-detalles");

            // Evento mouseenter - al pasar el ratón
            producto.addEventListener("mouseenter", function() {
                // Aumentar tamaño
                producto.style.transform = "scale(1.05)";
                
                // Cambiar color de fondo
                producto.style.backgroundColor = "#f0f8ff";
                
                // Aumentar sombra
                producto.style.boxShadow = "0 8px 16px rgba(0,0,0,0.2)";
                
                // Mostrar botón
                boton.style.display = "inline-block";
            });

            // Evento mouseleave - al quitar el ratón
            producto.addEventListener("mouseleave", function() {
                // Volver al tamaño original
                producto.style.transform = "scale(1)";
                
                // Volver al color original
                producto.style.backgroundColor = "white";
                
                // Volver a la sombra original
                producto.style.boxShadow = "0 2px 5px rgba(0,0,0,0.1)";
                
                // Ocultar botón
                boton.style.display = "none";
            });

            // Evento click en el botón
            boton.addEventListener("click", function(evento) {
                evento.stopPropagation(); // Evitar que se propague al div padre
                let nombreProducto = producto.querySelector("h3").textContent;
                alert("Ver detalles de: " + nombreProducto);
            });
        });
    </script>
</body>
</html>
```

:::
---

#### **Actividad 6.4 - Teclado interactivo**

Crea una página con:

- Un área de texto grande (div con borde)
- Instrucciones de uso

Implementa la siguiente funcionalidad:

1. Al presionar cualquier tecla (**keydown**), muestra en el área cuál tecla se ha presionado
2. Si se presiona la tecla **Enter**, añade un salto de línea
3. Si se presiona la tecla **Escape**, limpia todo el contenido
4. Si se presionan las teclas de números (0-9), muestra "Has escrito el número: X"

::: details Solución

```html
<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 40px;
            max-width: 800px;
            margin: 0 auto;
        }
        h1 {
            color: #333;
            margin-bottom: 20px;
        }
        .instrucciones {
            background-color: #e3f2fd;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
            border-left: 4px solid #2196F3;
        }
        .instrucciones h3 {
            margin-top: 0;
            color: #1976D2;
        }
        .instrucciones ul {
            margin: 10px 0;
            padding-left: 20px;
        }
        .instrucciones li {
            margin: 5px 0;
        }
        #areaTexto {
            width: 100%;
            min-height: 300px;
            padding: 20px;
            border: 3px solid #ddd;
            border-radius: 8px;
            font-size: 18px;
            line-height: 1.6;
            background-color: #fafafa;
            box-sizing: border-box;
            overflow-y: auto;
        }
        #areaTexto:focus {
            outline: none;
            border-color: #2196F3;
            background-color: white;
        }
        .info {
            margin-top: 20px;
            padding: 15px;
            background-color: #fff3cd;
            border-radius: 8px;
            border-left: 4px solid #ffc107;
        }
        .tecla-actual {
            font-weight: bold;
            color: #2196F3;
            font-size: 20px;
        }
    </style>
</head>
<body>
    <h1>Teclado Interactivo</h1>
    
    <div class="instrucciones">
        <h3>Instrucciones:</h3>
        <ul>
            <li>Haz clic en el área de texto y empieza a escribir</li>
            <li>Presiona <strong>Enter</strong> para añadir un salto de línea</li>
            <li>Presiona <strong>Escape</strong> para limpiar todo el contenido</li>
            <li>Presiona números (0-9) para ver un mensaje especial</li>
        </ul>
    </div>

    <div id="areaTexto" contenteditable="true" tabindex="0">
        Comienza a escribir aquí...
    </div>

    <div class="info">
        <p>Última tecla presionada: <span class="tecla-actual" id="teclaPre sionada">Ninguna</span></p>
    </div>

    <script>
        let areaTexto = document.getElementById("areaTexto");
        let teclaPresionada = document.getElementById("teclaPresionada");

        // Limpiar texto inicial al hacer clic por primera vez
        let primerClick = true;
        areaTexto.addEventListener("click", function() {
            if (primerClick) {
                areaTexto.textContent = "";
                primerClick = false;
            }
        });

        // Evento keydown - detectar teclas presionadas
        areaTexto.addEventListener("keydown", function(evento) {
            // Mostrar qué tecla se ha presionado
            teclaPresionada.textContent = evento.key;

            // Si es Enter
            if (evento.key === "Enter") {
                // No prevenir comportamiento por defecto (permite salto de línea)
                teclaPresionada.textContent = "Enter (salto de línea)";
            }
            
            // Si es Escape
            else if (evento.key === "Escape") {
                evento.preventDefault();
                areaTexto.textContent = "";
                teclaPresionada.textContent = "Escape (contenido limpiado)";
            }
            
            // Si es un número (0-9)
            else if (evento.key >= "0" && evento.key <= "9") {
                teclaPresionada.textContent = "Número: " + evento.key;
                
                // Opcional: mostrar alerta
                setTimeout(function() {
                    alert("Has escrito el número: " + evento.key);
                }, 100);
            }
            
            // Otras teclas especiales
            else if (evento.key === "Backspace") {
                teclaPresionada.textContent = "Backspace (borrar)";
            }
            else if (evento.key === " ") {
                teclaPresionada.textContent = "Espacio";
            }
        });

        // Dar foco automático al área de texto al cargar
        window.addEventListener("load", function() {
            areaTexto.focus();
        });
    </script>
</body>
</html>
```


:::
---

### **Actividad 6.5 - Aplicación con pantalla de carga**

Crea una aplicación web que simule la carga de datos con:

- Una pantalla de carga con un mensaje "Cargando aplicación..."
- Un spinner o indicador de carga
- Contenido principal (puede ser un panel con información)

Funcionalidad:

1. Al cargar la página (**evento load**), muestra la pantalla de carga
2. Después de 2 segundos, oculta la pantalla de carga y muestra el contenido
3. En el contenido principal, muestra:
   - Mensaje de bienvenida
   - Fecha y hora actual de carga
   - Al menos 3 "tarjetas" con información (puedes inventar estadísticas)

**Bonus:** Añade un botón "Recargar datos" que vuelva a mostrar la pantalla de carga durante 2 segundos.

::: details Solución

```html
<!DOCTYPE html>
<html>
<head>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
        }
        
        /* Pantalla de carga */
        #pantallaCarga {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            z-index: 9999;
            transition: opacity 0.5s;
        }
        #pantallaCarga.ocultar {
            opacity: 0;
            pointer-events: none;
        }
        #pantallaCarga h1 {
            color: white;
            font-size: 32px;
            margin-bottom: 30px;
        }
        .spinner {
            border: 6px solid rgba(255, 255, 255, 0.3);
            border-top: 6px solid white;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            animation: girar 1s linear infinite;
        }
        @keyframes girar {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        /* Contenido principal */
        #contenido {
            padding: 40px;
            max-width: 1200px;
            margin: 0 auto;
        }
        h1 {
            color: #333;
            margin-bottom: 10px;
        }
        .fecha {
            color: #666;
            margin-bottom: 40px;
            font-size: 14px;
        }
        .tarjetas {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            margin-bottom: 30px;
        }
        .tarjeta {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .tarjeta h3 {
            font-size: 18px;
            margin-bottom: 15px;
            opacity: 0.9;
        }
        .tarjeta .valor {
            font-size: 48px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .tarjeta .descripcion {
            font-size: 14px;
            opacity: 0.8;
        }
        .tarjeta:nth-child(2) {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }
        .tarjeta:nth-child(3) {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }
        button {
            padding: 12px 24px;
            background-color: #667eea;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #5568d3;
        }
    </style>
</head>
<body>
    <!-- Pantalla de carga -->
    <div id="pantallaCarga">
        <h1>Cargando aplicación...</h1>
        <div class="spinner"></div>
    </div>

    <!-- Contenido principal -->
    <div id="contenido">
        <h1>¡Bienvenido al Panel de Control!</h1>
        <p class="fecha" id="fechaCarga"></p>

        <div class="tarjetas">
            <div class="tarjeta">
                <h3>Usuarios Activos</h3>
                <div class="valor" id="usuarios">1,247</div>
                <p class="descripcion">↑ 12% desde ayer</p>
            </div>

            <div class="tarjeta">
                <h3>Ventas del Día</h3>
                <div class="valor" id="ventas">3,891€</div>
                <p class="descripcion">↑ 8% desde ayer</p>
            </div>

            <div class="tarjeta">
                <h3>Productos Vendidos</h3>
                <div class="valor" id="productos">127</div>
                <p class="descripcion">↑ 15% desde ayer</p>
            </div>
        </div>

        <button id="btnRecargar">Recargar Datos</button>
    </div>

    <script>
        let pantallaCarga = document.getElementById("pantallaCarga");
        let fechaCarga = document.getElementById("fechaCarga");
        let btnRecargar = document.getElementById("btnRecargar");

        // Evento load - ejecutar cuando la página carga
        window.addEventListener("load", function() {
            inicializarAplicacion();
        });

        function inicializarAplicacion() {
            // Simular tiempo de carga (2 segundos)
            setTimeout(function() {
                // Ocultar pantalla de carga
                pantallaCarga.classList.add("ocultar");
                
                // Mostrar fecha y hora de carga
                mostrarFechaCarga();
                
            }, 2000);
        }

        function mostrarFechaCarga() {
            let ahora = new Date();
            let opciones = { 
                weekday: 'long', 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            };
            let fechaFormateada = ahora.toLocaleDateString('es-ES', opciones);
            fechaCarga.textContent = "Datos cargados el " + fechaFormateada;
        }

        // Botón recargar datos
        btnRecargar.addEventListener("click", function() {
            // Mostrar pantalla de carga
            pantallaCarga.classList.remove("ocultar");
            
            // Simular recarga de datos
            setTimeout(function() {
                // Ocultar pantalla de carga
                pantallaCarga.classList.add("ocultar");
                
                // Actualizar datos con valores aleatorios
                actualizarDatos();
                
                // Actualizar fecha
                mostrarFechaCarga();
                
            }, 2000);
        });

        function actualizarDatos() {
            // Generar valores aleatorios
            let usuarios = Math.floor(Math.random() * 2000) + 1000;
            let ventas = Math.floor(Math.random() * 5000) + 2000;
            let productos = Math.floor(Math.random() * 200) + 50;
            
            // Actualizar en la interfaz
            document.getElementById("usuarios").textContent = usuarios.toLocaleString('es-ES');
            document.getElementById("ventas").textContent = ventas.toLocaleString('es-ES') + "€";
            document.getElementById("productos").textContent = productos;
        }
    </script>
</body>
</html>
```

:::
---


## **Bloque VII. Objetos y Almacenamiento**

### **7. Objetos**

#### **¿Qué es un objeto?**

Un **objeto** es una estructura de datos que permite agrupar información relacionada. Mientras que las variables almacenan un solo valor y los arrays almacenan listas de valores, los objetos nos permiten almacenar datos con **nombre y valor** (propiedades).

Imagina que necesitas almacenar información de un producto en una tienda online. Sin objetos necesitarías múltiples variables:

```javascript
let productoNombre = "Portátil";
let productoPrecio = 899;
let productoStock = 15;
let productoCategoria = "Electrónica";
```

Con objetos, podemos agrupar toda esta información de forma organizada:

```javascript
let producto = {
    nombre: "Portátil",
    precio: 899,
    stock: 15,
    categoria: "Electrónica"
};
```

#### **Crear objetos**

Vamos a ver cómo crear un objeto para representar un usuario de nuestra aplicación web. El objeto tendrá propiedades como nombre, email y edad:

```javascript
// Crear un objeto literal
let usuario = {
    nombre: "Ana García",
    email: "ana@example.com",
    edad: 28
};

console.log(usuario);
```

**Otro ejemplo - Producto de una tienda:**

```javascript
let producto = {
    id: 1,
    nombre: "Ratón inalámbrico",
    precio: 29.99,
    disponible: true,
    categorias: ["Hardware", "Periféricos"]
};
```

#### **Acceder a las propiedades**

Supongamos que tenemos un objeto que representa un artículo de blog y queremos mostrar su información. Hay dos formas de acceder a las propiedades:

**Notación de punto (más común):**

```javascript
let articulo = {
    titulo: "Introducción a JavaScript",
    autor: "Carlos López",
    fecha: "2024-11-08",
    visitas: 1523
};

// Acceder con punto
console.log(articulo.titulo);      // "Introducción a JavaScript"
console.log(articulo.autor);       // "Carlos López"
console.log(articulo.visitas);     // 1523
```

**Notación de corchetes:**

```javascript
// Acceder con corchetes
console.log(articulo["titulo"]);   // "Introducción a JavaScript"
console.log(articulo["fecha"]);    // "2024-11-08"

// Útil cuando el nombre de la propiedad está en una variable
let propiedad = "autor";
console.log(articulo[propiedad]);  // "Carlos López"
```

**Ejemplo práctico - Mostrar información de un producto en HTML:**

```html
<!DOCTYPE html>
<html>
<body>
    <h2>Información del Producto</h2>
    <div id="infoProducto"></div>

    <script>
        let producto = {
            nombre: "Teclado mecánico",
            marca: "Logitech",
            precio: 79.99,
            color: "Negro",
            garantia: "2 años"
        };

        // Mostrar información en la página
        let info = document.getElementById("infoProducto");
        info.innerHTML = 
            "<p><strong>Producto:</strong> " + producto.nombre + "</p>" +
            "<p><strong>Marca:</strong> " + producto.marca + "</p>" +
            "<p><strong>Precio:</strong> " + producto.precio + "€</p>" +
            "<p><strong>Color:</strong> " + producto.color + "</p>" +
            "<p><strong>Garantía:</strong> " + producto.garantia + "</p>";
    </script>
</body>
</html>
```

#### **Modificar propiedades**

Imaginemos que tenemos una aplicación de perfil de usuario y queremos actualizar los datos cuando el usuario los modifica. Para cambiar el valor de una propiedad, simplemente le asignamos un nuevo valor:

```javascript
let usuario = {
    nombre: "María Sánchez",
    email: "maria@example.com",
    premium: false
};

console.log("Antes:", usuario);

// Modificar propiedades existentes
usuario.nombre = "María S. García";
usuario.premium = true;

console.log("Después:", usuario);
```

**Añadir nuevas propiedades:**

```javascript
let producto = {
    nombre: "Monitor",
    precio: 199
};

// Añadir nuevas propiedades
producto.marca = "Samsung";
producto.pulgadas = 27;
producto.stock = 8;

console.log(producto);
```

**Eliminar propiedades:**

```javascript
let configuracion = {
    tema: "oscuro",
    idioma: "es",
    notificaciones: true,
    sonido: false
};

// Eliminar una propiedad
delete configuracion.sonido;

console.log(configuracion); // Ya no tiene la propiedad 'sonido'
```

#### **Objetos con métodos**

Los objetos no solo pueden contener datos, también pueden contener **funciones** (llamadas métodos). Por ejemplo, vamos a crear un objeto calculadora que tenga funciones para realizar operaciones:

```javascript
let calculadora = {
    valor: 0,
    
    sumar: function(numero) {
        this.valor += numero;
        return this.valor;
    },
    
    restar: function(numero) {
        this.valor -= numero;
        return this.valor;
    },
    
    reiniciar: function() {
        this.valor = 0;
        return this.valor;
    }
};

// Usar los métodos
console.log(calculadora.sumar(10));    // 10
console.log(calculadora.sumar(5));     // 15
console.log(calculadora.restar(3));    // 12
console.log(calculadora.reiniciar());  // 0
```

**Nota sobre `this`:** La palabra clave `this` hace referencia al propio objeto. En el ejemplo anterior, `this.valor` se refiere a la propiedad `valor` del objeto `calculadora`.

**Ejemplo práctico - Carrito de compra con métodos:**

```html
<!DOCTYPE html>
<html>
<body>
    <h2>Carrito de Compra</h2>
    <button onclick="carrito.agregarProducto('Ratón', 29.99)">Agregar Ratón (29.99€)</button>
    <button onclick="carrito.agregarProducto('Teclado', 59.99)">Agregar Teclado (59.99€)</button>
    <button onclick="carrito.mostrar()">Ver Carrito</button>
    <button onclick="carrito.vaciar()">Vaciar Carrito</button>
    
    <div id="resultado"></div>

    <script>
        let carrito = {
            productos: [],
            total: 0,
            
            agregarProducto: function(nombre, precio) {
                this.productos.push({
                    nombre: nombre,
                    precio: precio
                });
                this.total += precio;
                alert("Producto añadido: " + nombre);
            },
            
            mostrar: function() {
                let resultado = document.getElementById("resultado");
                
                if (this.productos.length === 0) {
                    resultado.innerHTML = "<p>El carrito está vacío</p>";
                    return;
                }
                
                let html = "<h3>Productos en el carrito:</h3><ul>";
                
                for (let i = 0; i < this.productos.length; i++) {
                    html += "<li>" + this.productos[i].nombre + 
                           " - " + this.productos[i].precio.toFixed(2) + "€</li>";
                }
                
                html += "</ul>";
                html += "<p><strong>Total: " + this.total.toFixed(2) + "€</strong></p>";
                
                resultado.innerHTML = html;
            },
            
            vaciar: function() {
                this.productos = [];
                this.total = 0;
                document.getElementById("resultado").innerHTML = "<p>Carrito vaciado</p>";
            }
        };
    </script>
</body>
</html>
```

#### **Arrays de objetos**

En aplicaciones web reales, frecuentemente trabajamos con listas de elementos similares. Por ejemplo, una lista de productos, usuarios, o pedidos. Para esto combinamos arrays y objetos:

```javascript
let productos = [
    {
        id: 1,
        nombre: "Portátil",
        precio: 899,
        categoria: "Electrónica"
    },
    {
        id: 2,
        nombre: "Ratón",
        precio: 29,
        categoria: "Periféricos"
    },
    {
        id: 3,
        nombre: "Teclado",
        precio: 59,
        categoria: "Periféricos"
    }
];

// Acceder a un producto específico
console.log(productos[0].nombre);  // "Portátil"
console.log(productos[1].precio);  // 29

// Recorrer todos los productos
for (let i = 0; i < productos.length; i++) {
    console.log(productos[i].nombre + " - " + productos[i].precio + "€");
}
```

**Ejemplo completo - Catálogo de productos:**

```html
<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }
        .productos {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        .producto {
            border: 2px solid #ddd;
            padding: 20px;
            border-radius: 8px;
            background-color: #f9f9f9;
        }
        .producto h3 {
            margin-top: 0;
            color: #333;
        }
        .precio {
            font-size: 24px;
            color: #4CAF50;
            font-weight: bold;
            margin: 10px 0;
        }
        .categoria {
            display: inline-block;
            background-color: #2196F3;
            color: white;
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 12px;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 10px;
        }
        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <h1>Catálogo de Productos</h1>
    <div class="productos" id="catalogo"></div>

    <script>
        // Array de objetos con productos
        let productos = [
            {
                id: 1,
                nombre: "Portátil Gaming",
                precio: 1299,
                categoria: "Electrónica",
                stock: 5
            },
            {
                id: 2,
                nombre: "Ratón Inalámbrico",
                precio: 39,
                categoria: "Periféricos",
                stock: 25
            },
            {
                id: 3,
                nombre: "Teclado Mecánico",
                precio: 89,
                categoria: "Periféricos",
                stock: 15
            },
            {
                id: 4,
                nombre: "Monitor 4K 27\"",
                precio: 449,
                categoria: "Pantallas",
                stock: 8
            },
            {
                id: 5,
                nombre: "Webcam HD",
                precio: 69,
                categoria: "Accesorios",
                stock: 20
            },
            {
                id: 6,
                nombre: "Auriculares Gaming",
                precio: 99,
                categoria: "Audio",
                stock: 12
            }
        ];

        // Función para mostrar productos
        function mostrarProductos() {
            let catalogo = document.getElementById("catalogo");
            
            // Recorrer array de productos
            for (let i = 0; i < productos.length; i++) {
                let producto = productos[i];
                
                // Crear div para cada producto
                let div = document.createElement("div");
                div.className = "producto";
                
                // Añadir contenido
                div.innerHTML = 
                    "<h3>" + producto.nombre + "</h3>" +
                    "<span class='categoria'>" + producto.categoria + "</span>" +
                    "<div class='precio'>" + producto.precio + "€</div>" +
                    "<p>Stock: " + producto.stock + " unidades</p>" +
                    "<button onclick='agregarAlCarrito(" + producto.id + ")'>Añadir al carrito</button>";
                
                catalogo.appendChild(div);
            }
        }

        // Función para agregar al carrito
        function agregarAlCarrito(idProducto) {
            // Buscar el producto por id
            let productoEncontrado = null;
            
            for (let i = 0; i < productos.length; i++) {
                if (productos[i].id === idProducto) {
                    productoEncontrado = productos[i];
                    break;
                }
            }
            
            if (productoEncontrado) {
                alert("Producto añadido: " + productoEncontrado.nombre + 
                      " - " + productoEncontrado.precio + "€");
            }
        }

        // Cargar productos al iniciar
        window.addEventListener("load", function() {
            mostrarProductos();
        });
    </script>
</body>
</html>
```

------

### **8. localStorage**

#### **¿Qué es localStorage?**

**localStorage** es una funcionalidad del navegador que nos permite guardar datos en el ordenador del usuario de forma permanente. Los datos se mantienen incluso después de cerrar el navegador o apagar el ordenador.

Es útil para:

- Guardar preferencias del usuario (tema claro/oscuro, idioma)
- Recordar datos de formularios
- Mantener un carrito de compra
- Guardar configuraciones de la aplicación

**Características importantes:**

- Los datos se guardan como texto (strings)
- Cada sitio web tiene su propio localStorage (no se comparte entre sitios)
- Tiene un límite aproximado de 5-10 MB
- Los datos persisten indefinidamente hasta que se borren

#### **Métodos básicos de localStorage**

Imaginemos que queremos crear una aplicación que recuerde el nombre del usuario. Vamos a ver cómo guardar y recuperar este dato:

**Guardar datos - setItem():**

```javascript
// Guardar el nombre del usuario
localStorage.setItem("nombreUsuario", "Ana García");

// Guardar más datos
localStorage.setItem("email", "ana@example.com");
localStorage.setItem("tema", "oscuro");
```

**Recuperar datos - getItem():**

```javascript
// Recuperar el nombre guardado
let nombre = localStorage.getItem("nombreUsuario");
console.log(nombre); // "Ana García"

// Si el dato no existe, devuelve null
let telefono = localStorage.getItem("telefono");
console.log(telefono); // null
```

**Eliminar un dato - removeItem():**

```javascript
// Eliminar un dato específico
localStorage.removeItem("email");
```

**Eliminar todo - clear():**

```javascript
// Borrar todos los datos del localStorage
localStorage.clear();
```

**Ejemplo práctico - Recordar el nombre del usuario:**

```html
<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 40px;
            max-width: 500px;
            margin: 0 auto;
        }
        input {
            padding: 10px;
            width: 100%;
            margin: 10px 0;
            border: 2px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin: 5px;
        }
        button:hover {
            background-color: #45a049;
        }
        .btn-borrar {
            background-color: #f44336;
        }
        .btn-borrar:hover {
            background-color: #da190b;
        }
        #bienvenida {
            padding: 20px;
            background-color: #e8f5e9;
            border-radius: 8px;
            margin: 20px 0;
            display: none;
        }
        #bienvenida.mostrar {
            display: block;
        }
    </style>
</head>
<body>
    <h2>Configuración de Usuario</h2>
    
    <div id="bienvenida">
        <h3>¡Bienvenido de nuevo!</h3>
        <p id="mensajeBienvenida"></p>
    </div>

    <label>Tu nombre:</label>
    <input type="text" id="nombre" placeholder="Escribe tu nombre">
    
    <button onclick="guardarNombre()">Guardar Nombre</button>
    <button onclick="borrarNombre()" class="btn-borrar">Borrar Datos</button>

    <script>
        // Al cargar la página, verificar si hay un nombre guardado
        window.addEventListener("load", function() {
            let nombreGuardado = localStorage.getItem("nombreUsuario");
            
            if (nombreGuardado) {
                // Si existe, mostrar bienvenida
                document.getElementById("mensajeBienvenida").textContent = 
                    "Tu nombre guardado es: " + nombreGuardado;
                document.getElementById("bienvenida").classList.add("mostrar");
                document.getElementById("nombre").value = nombreGuardado;
            }
        });

        function guardarNombre() {
            let nombre = document.getElementById("nombre").value;
            
            if (nombre.trim() === "") {
                alert("Por favor, escribe un nombre");
                return;
            }
            
            // Guardar en localStorage
            localStorage.setItem("nombreUsuario", nombre);
            
            alert("Nombre guardado correctamente");
            
            // Actualizar mensaje de bienvenida
            document.getElementById("mensajeBienvenida").textContent = 
                "Tu nombre guardado es: " + nombre;
            document.getElementById("bienvenida").classList.add("mostrar");
        }

        function borrarNombre() {
            // Eliminar del localStorage
            localStorage.removeItem("nombreUsuario");
            
            // Limpiar interfaz
            document.getElementById("nombre").value = "";
            document.getElementById("bienvenida").classList.remove("mostrar");
            
            alert("Datos borrados");
        }
    </script>
</body>
</html>
```

#### **Guardar números y booleanos**

localStorage solo guarda texto (strings), por lo que si queremos guardar números o booleanos, debemos convertirlos:

```javascript
// Guardar un número
let edad = 25;
localStorage.setItem("edad", edad.toString());
// o simplemente:
localStorage.setItem("edad", edad); // Se convierte automáticamente

// Recuperar y convertir de nuevo a número
let edadGuardada = localStorage.getItem("edad");
let edadNumero = parseInt(edadGuardada);
console.log(edadNumero + 5); // 30

// Guardar un booleano
let premium = true;
localStorage.setItem("premium", premium);

// Recuperar y convertir a booleano
let premiumGuardado = localStorage.getItem("premium");
let premiumBooleano = (premiumGuardado === "true");
console.log(premiumBooleano); // true
```

**Ejemplo práctico - Contador persistente:**

```html
<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            padding: 50px;
        }
        #contador {
            font-size: 72px;
            color: #4CAF50;
            margin: 30px 0;
            font-weight: bold;
        }
        button {
            padding: 15px 30px;
            font-size: 18px;
            margin: 10px;
            cursor: pointer;
            border: none;
            border-radius: 5px;
            color: white;
        }
        .btn-incrementar {
            background-color: #4CAF50;
        }
        .btn-decrementar {
            background-color: #2196F3;
        }
        .btn-reiniciar {
            background-color: #f44336;
        }
        button:hover {
            opacity: 0.8;
        }
        .info {
            margin-top: 30px;
            color: #666;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <h1>Contador Persistente</h1>
    <p>Este contador se mantiene aunque cierres la página</p>
    
    <div id="contador">0</div>
    
    <button class="btn-incrementar" onclick="incrementar()">+ Incrementar</button>
    <button class="btn-decrementar" onclick="decrementar()">- Decrementar</button>
    <button class="btn-reiniciar" onclick="reiniciar()">Reiniciar</button>
    
    <p class="info">El valor se guarda automáticamente en tu navegador</p>

    <script>
        let contadorElemento = document.getElementById("contador");
        let valorContador = 0;

        // Al cargar la página, recuperar el valor guardado
        window.addEventListener("load", function() {
            let valorGuardado = localStorage.getItem("contador");
            
            if (valorGuardado !== null) {
                valorContador = parseInt(valorGuardado);
                contadorElemento.textContent = valorContador;
            }
        });

        function incrementar() {
            valorContador++;
            actualizarContador();
        }

        function decrementar() {
            valorContador--;
            actualizarContador();
        }

        function reiniciar() {
            valorContador = 0;
            actualizarContador();
        }

        function actualizarContador() {
            // Actualizar en la pantalla
            contadorElemento.textContent = valorContador;
            
            // Guardar en localStorage
            localStorage.setItem("contador", valorContador);
        }
    </script>
</body>
</html>
```

------

### **9. JSON**

#### **¿Qué es JSON?**

**JSON** (JavaScript Object Notation) es un formato de texto para representar datos estructurados. Es como escribir objetos JavaScript pero en formato de texto, lo que permite:

- Guardar objetos complejos en localStorage
- Enviar datos entre el navegador y un servidor
- Intercambiar información entre diferentes aplicaciones

**Diferencia entre un objeto JavaScript y JSON:**

```javascript
// Objeto JavaScript
let producto = {
    nombre: "Portátil",
    precio: 899
};

// Mismo objeto en formato JSON (texto)
let productoJSON = '{"nombre":"Portátil","precio":899}';
```

### **Convertir objetos a JSON y viceversa**

Para trabajar con localStorage necesitamos convertir nuestros objetos a texto (JSON) para guardarlos, y luego convertirlos de nuevo a objetos para usarlos.

**JSON.stringify() - Convertir objeto a texto:**

Imagina que quieres guardar la configuración completa de un usuario. Usamos `JSON.stringify()` para convertir el objeto en texto:

```javascript
let configuracion = {
    tema: "oscuro",
    idioma: "es",
    notificaciones: true,
    volumen: 75
};

// Convertir a JSON (texto)
let configuracionJSON = JSON.stringify(configuracion);
console.log(configuracionJSON);
// Resultado: '{"tema":"oscuro","idioma":"es","notificaciones":true,"volumen":75}'

// Ahora podemos guardarlo en localStorage
localStorage.setItem("configuracion", configuracionJSON);
```

**JSON.parse() - Convertir texto a objeto:**

Cuando recuperamos datos del localStorage, necesitamos `JSON.parse()` para convertir el texto de nuevo en un objeto que podamos usar:

```javascript
// Recuperar el texto del localStorage
let configuracionGuardada = localStorage.getItem("configuracion");

// Convertir de JSON a objeto
let configuracionObjeto = JSON.parse(configuracionGuardada);

// Ahora podemos acceder a las propiedades
console.log(configuracionObjeto.tema);    // "oscuro"
console.log(configuracionObjeto.idioma);  // "es"
```

**Ejemplo práctico - Guardar y recuperar un objeto completo:**

```html
<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 40px;
            max-width: 600px;
            margin: 0 auto;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input, select {
            padding: 8px;
            width: 100%;
            border: 2px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        button {
            padding: 10px 20px;
            margin: 5px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }
        .btn-guardar {
            background-color: #4CAF50;
            color: white;
        }
        .btn-cargar {
            background-color: #2196F3;
            color: white;
        }
        .btn-borrar {
            background-color: #f44336;
            color: white;
        }
        #mensaje {
            padding: 15px;
            margin-top: 20px;
            border-radius: 4px;
            display: none;
        }
        #mensaje.exito {
            background-color: #e8f5e9;
            color: #2e7d32;
            display: block;
        }
        #mensaje.info {
            background-color: #e3f2fd;
            color: #1976d2;
            display: block;
        }
    </style>
</head>
<body>
    <h2>Configuración de Perfil</h2>
    
    <div class="form-group">
        <label>Nombre:</label>
        <input type="text" id="nombre">
    </div>

    <div class="form-group">
        <label>Email:</label>
        <input type="email" id="email">
    </div>

    <div class="form-group">
        <label>Tema:</label>
        <select id="tema">
            <option value="claro">Claro</option>
            <option value="oscuro">Oscuro</option>
        </select>
    </div>

    <div class="form-group">
        <label>Idioma:</label>
        <select id="idioma">
            <option value="es">Español</option>
            <option value="en">English</option>
            <option value="fr">Français</option>
        </select>
    </div>

    <button class="btn-guardar" onclick="guardarPerfil()">Guardar Perfil</button>
    <button class="btn-cargar" onclick="cargarPerfil()">Cargar Perfil</button>
    <button class="btn-borrar" onclick="borrarPerfil()">Borrar Perfil</button>

    <div id="mensaje"></div>

    <script>
        function guardarPerfil() {
            // Crear objeto con los datos del formulario
            let perfil = {
                nombre: document.getElementById("nombre").value,
                email: document.getElementById("email").value,
                tema: document.getElementById("tema").value,
                idioma: document.getElementById("idioma").value,
                fechaGuardado: new Date().toLocaleDateString()
            };

            // Convertir objeto a JSON
            let perfilJSON = JSON.stringify(perfil);

            // Guardar en localStorage
            localStorage.setItem("perfilUsuario", perfilJSON);

            // Mostrar mensaje
            mostrarMensaje("Perfil guardado correctamente", "exito");
        }

        function cargarPerfil() {
            // Obtener JSON del localStorage
            let perfilJSON = localStorage.getItem("perfilUsuario");

            if (perfilJSON === null) {
                mostrarMensaje("No hay ningún perfil guardado", "info");
                return;
            }

            // Convertir JSON a objeto
            let perfil = JSON.parse(perfilJSON);

            // Rellenar formulario con los datos guardados
            document.getElementById("nombre").value = perfil.nombre;
            document.getElementById("email").value = perfil.email;
            document.getElementById("tema").value = perfil.tema;
            document.getElementById("idioma").value = perfil.idioma;

            mostrarMensaje("Perfil cargado (guardado el " + perfil.fechaGuardado + ")", "exito");
        }

        function borrarPerfil() {
            localStorage.removeItem("perfilUsuario");
            
            // Limpiar formulario
            document.getElementById("nombre").value = "";
            document.getElementById("email").value = "";
            document.getElementById("tema").value = "claro";
            document.getElementById("idioma").value = "es";

            mostrarMensaje("Perfil borrado", "info");
        }

        function mostrarMensaje(texto, tipo) {
            let mensaje = document.getElementById("mensaje");
            mensaje.textContent = texto;
            mensaje.className = tipo;

            // Ocultar después de 3 segundos
            setTimeout(function() {
                mensaje.className = "";
                mensaje.style.display = "none";
            }, 3000);
        }

        // Al cargar la página, intentar cargar el perfil automáticamente
        window.addEventListener("load", function() {
            let perfilJSON = localStorage.getItem("perfilUsuario");
            if (perfilJSON !== null) {
                cargarPerfil();
            }
        });
    </script>
</body>
</html>
```

#### **Guardar arrays en localStorage**

Vamos a crear una aplicación de lista de tareas que persista los datos. Para guardar un array de objetos, primero lo convertimos a JSON:

```html
<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
        }
        h2 {
            color: #333;
        }
        .input-container {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }
        input {
            flex: 1;
            padding: 10px;
            border: 2px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
        button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #45a049;
        }
        .btn-limpiar {
            background-color: #f44336;
        }
        .btn-limpiar:hover {
            background-color: #da190b;
        }
        ul {
            list-style: none;
            padding: 0;
        }
        li {
            background-color: #f9f9f9;
            padding: 15px;
            margin-bottom: 10px;
            border-left: 4px solid #4CAF50;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-radius: 4px;
        }
        .tarea-info {
            flex: 1;
        }
        .tarea-fecha {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
        }
        .btn-eliminar {
            padding: 5px 10px;
            background-color: #f44336;
            color: white;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            font-size: 14px;
        }
        .btn-eliminar:hover {
            background-color: #da190b;
        }
        .vacio {
            text-align: center;
            color: #999;
            padding: 40px;
        }
        .contador {
            background-color: #e3f2fd;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 20px;
            text-align: center;
            color: #1976d2;
        }
    </style>
</head>
<body>
    <h2>Lista de Tareas Persistente</h2>
    
    <div class="contador">
        Total de tareas: <strong><span id="contador">0</span></strong>
    </div>

    <div class="input-container">
        <input type="text" id="inputTarea" placeholder="Escribe una nueva tarea...">
        <button onclick="agregarTarea()">Agregar</button>
        <button class="btn-limpiar" onclick="limpiarTodas()">Limpiar Todas</button>
    </div>

    <ul id="listaTareas"></ul>

    <script>
        let tareas = [];

        // Al cargar la página, recuperar tareas guardadas
        window.addEventListener("load", function() {
            cargarTareas();
            mostrarTareas();
        });

        function cargarTareas() {
            // Obtener el JSON del localStorage
            let tareasJSON = localStorage.getItem("tareas");
            
            if (tareasJSON !== null) {
                // Convertir JSON a array de objetos
                tareas = JSON.parse(tareasJSON);
            }
        }

        function guardarTareas() {
            // Convertir array a JSON
            let tareasJSON = JSON.stringify(tareas);
            
            // Guardar en localStorage
            localStorage.setItem("tareas", tareasJSON);
        }

        function agregarTarea() {
            let input = document.getElementById("inputTarea");
            let textoTarea = input.value.trim();
            
            if (textoTarea === "") {
                alert("Por favor, escribe una tarea");
                return;
            }
            
            // Crear objeto tarea
            let nuevaTarea = {
                id: Date.now(), // ID único basado en timestamp
                texto: textoTarea,
                fecha: new Date().toLocaleDateString()
            };
            
            // Añadir al array
            tareas.push(nuevaTarea);
            
            // Guardar en localStorage
            guardarTareas();
            
            // Actualizar interfaz
            mostrarTareas();
            
            // Limpiar input
            input.value = "";
        }

        function eliminarTarea(id) {
            // Filtrar el array para eliminar la tarea con ese id
            tareas = tareas.filter(function(tarea) {
                return tarea.id !== id;
            });
            
            // Guardar cambios en localStorage
            guardarTareas();
            
            // Actualizar interfaz
            mostrarTareas();
        }

        function limpiarTodas() {
            if (tareas.length === 0) {
                alert("No hay tareas para eliminar");
                return;
            }
            
            if (confirm("¿Estás seguro de que quieres eliminar todas las tareas?")) {
                tareas = [];
                guardarTareas();
                mostrarTareas();
            }
        }

        function mostrarTareas() {
            let lista = document.getElementById("listaTareas");
            let contador = document.getElementById("contador");
            
            // Actualizar contador
            contador.textContent = tareas.length;
            
            // Limpiar lista
            lista.innerHTML = "";
            
            if (tareas.length === 0) {
                lista.innerHTML = '<li class="vacio">No hay tareas. ¡Añade una!</li>';
                return;
            }
            
            // Mostrar cada tarea
            for (let i = 0; i < tareas.length; i++) {
                let tarea = tareas[i];
                
                let li = document.createElement("li");
                li.innerHTML = 
                    '<div class="tarea-info">' +
                    '    <div>' + tarea.texto + '</div>' +
                    '    <div class="tarea-fecha">Creada el: ' + tarea.fecha + '</div>' +
                    '</div>' +
                    '<button class="btn-eliminar" onclick="eliminarTarea(' + tarea.id + ')">Eliminar</button>';
                
                lista.appendChild(li);
            }
        }

        // Permitir agregar tarea con Enter
        document.getElementById("inputTarea").addEventListener("keypress", function(e) {
            if (e.key === "Enter") {
                agregarTarea();
            }
        });
    </script>
</body>
</html>
```

#### **Ejemplo completo integrado - Carrito de compra persistente:**

Vamos a crear un ejemplo final que integre todo lo aprendido: objetos, arrays de objetos, localStorage y JSON. Haremos un carrito de compra que mantenga los productos incluso al cerrar el navegador:

```html
<!DOCTYPE html>
<html>
<head>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        h1 {
            color: #333;
            margin-bottom: 30px;
            text-align: center;
        }
        .productos-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }
        .producto-card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            text-align: center;
        }
        .producto-card img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            border-radius: 4px;
            margin-bottom: 15px;
            background-color: #e0e0e0;
        }
        .producto-card h3 {
            color: #333;
            margin-bottom: 10px;
            font-size: 18px;
        }
        .precio {
            font-size: 24px;
            color: #4CAF50;
            font-weight: bold;
            margin: 10px 0;
        }
        .btn-agregar {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        .btn-agregar:hover {
            background-color: #45a049;
        }
        .carrito-section {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .carrito-section h2 {
            color: #333;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .badge {
            background-color: #f44336;
            color: white;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 14px;
        }
        .carrito-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            border-bottom: 1px solid #eee;
        }
        .carrito-item:last-child {
            border-bottom: none;
        }
        .item-info {
            flex: 1;
        }
        .item-nombre {
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
        }
        .item-precio {
            color: #4CAF50;
            font-size: 18px;
        }
        .item-cantidad {
            color: #666;
            font-size: 14px;
        }
        .btn-eliminar {
            padding: 8px 15px;
            background-color: #f44336;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .btn-eliminar:hover {
            background-color: #da190b;
        }
        .carrito-total {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 2px solid #4CAF50;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .total-label {
            font-size: 24px;
            font-weight: bold;
            color: #333;
        }
        .total-precio {
            font-size: 32px;
            font-weight: bold;
            color: #4CAF50;
        }
        .carrito-vacio {
            text-align: center;
            padding: 40px;
            color: #999;
        }
        .botones-carrito {
            margin-top: 20px;
            display: flex;
            gap: 10px;
        }
        .btn-vaciar {
            flex: 1;
            padding: 12px;
            background-color: #f44336;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        .btn-vaciar:hover {
            background-color: #da190b;
        }
        .btn-comprar {
            flex: 1;
            padding: 12px;
            background-color: #2196F3;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        .btn-comprar:hover {
            background-color: #0b7dda;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🛒 Tienda Online con Carrito Persistente</h1>

        <div class="productos-grid" id="productosGrid"></div>

        <div class="carrito-section">
            <h2>
                Tu Carrito
                <span class="badge" id="badgeCarrito">0</span>
            </h2>
            <div id="carritoContenido"></div>
            <div class="carrito-total" id="carritoTotal" style="display: none;">
                <span class="total-label">Total:</span>
                <span class="total-precio" id="totalPrecio">0.00€</span>
            </div>
            <div class="botones-carrito" id="botonesCarrito" style="display: none;">
                <button class="btn-vaciar" onclick="vaciarCarrito()">Vaciar Carrito</button>
                <button class="btn-comprar" onclick="finalizarCompra()">Finalizar Compra</button>
            </div>
        </div>
    </div>

    <script>
        // Catálogo de productos
        let catalogoProductos = [
            {
                id: 1,
                nombre: "Portátil Gaming",
                precio: 1299,
                imagen: "https://via.placeholder.com/300x200/667eea/ffffff?text=Portátil"
            },
            {
                id: 2,
                nombre: "Ratón Inalámbrico",
                precio: 39,
                imagen: "https://via.placeholder.com/300x200/f093fb/ffffff?text=Ratón"
            },
            {
                id: 3,
                nombre: "Teclado Mecánico",
                precio: 89,
                imagen: "https://via.placeholder.com/300x200/4facfe/ffffff?text=Teclado"
            },
            {
                id: 4,
                nombre: "Monitor 4K",
                precio: 449,
                imagen: "https://via.placeholder.com/300x200/43e97b/ffffff?text=Monitor"
            },
            {
                id: 5,
                nombre: "Webcam HD",
                precio: 69,
                imagen: "https://via.placeholder.com/300x200/fa709a/ffffff?text=Webcam"
            },
            {
                id: 6,
                nombre: "Auriculares",
                precio: 99,
                imagen: "https://via.placeholder.com/300x200/fee140/ffffff?text=Auriculares"
            }
        ];

        // Carrito (se cargará del localStorage)
        let carrito = [];

        // Al cargar la página
        window.addEventListener("load", function() {
            cargarCarrito();
            mostrarProductos();
            actualizarCarrito();
        });

        function cargarCarrito() {
            let carritoJSON = localStorage.getItem("carrito");
            
            if (carritoJSON !== null) {
                carrito = JSON.parse(carritoJSON);
            }
        }

        function guardarCarrito() {
            let carritoJSON = JSON.stringify(carrito);
            localStorage.setItem("carrito", carritoJSON);
        }

        function mostrarProductos() {
            let grid = document.getElementById("productosGrid");
            
            catalogoProductos.forEach(function(producto) {
                let card = document.createElement("div");
                card.className = "producto-card";
                card.innerHTML = 
                    '<img src="' + producto.imagen + '" alt="' + producto.nombre + '">' +
                    '<h3>' + producto.nombre + '</h3>' +
                    '<div class="precio">' + producto.precio + '€</div>' +
                    '<button class="btn-agregar" onclick="agregarAlCarrito(' + producto.id + ')">Agregar al Carrito</button>';
                
                grid.appendChild(card);
            });
        }

        function agregarAlCarrito(idProducto) {
            // Buscar el producto en el catálogo
            let producto = catalogoProductos.find(function(p) {
                return p.id === idProducto;
            });

            if (!producto) return;

            // Verificar si ya existe en el carrito
            let itemExistente = carrito.find(function(item) {
                return item.id === idProducto;
            });

            if (itemExistente) {
                // Si existe, incrementar cantidad
                itemExistente.cantidad++;
            } else {
                // Si no existe, añadir nuevo item
                carrito.push({
                    id: producto.id,
                    nombre: producto.nombre,
                    precio: producto.precio,
                    cantidad: 1
                });
            }

            // Guardar y actualizar
            guardarCarrito();
            actualizarCarrito();

            // Feedback visual
            mostrarNotificacion("Producto añadido al carrito");
        }

        function eliminarDelCarrito(idProducto) {
            carrito = carrito.filter(function(item) {
                return item.id !== idProducto;
            });

            guardarCarrito();
            actualizarCarrito();
        }

        function vaciarCarrito() {
            if (carrito.length === 0) return;

            if (confirm("¿Estás seguro de que quieres vaciar el carrito?")) {
                carrito = [];
                guardarCarrito();
                actualizarCarrito();
            }
        }

        function actualizarCarrito() {
            let contenido = document.getElementById("carritoContenido");
            let badge = document.getElementById("badgeCarrito");
            let totalElement = document.getElementById("totalPrecio");
            let totalSection = document.getElementById("carritoTotal");
            let botonesSection = document.getElementById("botonesCarrito");

            // Actualizar badge
            let totalItems = carrito.reduce(function(sum, item) {
                return sum + item.cantidad;
            }, 0);
            badge.textContent = totalItems;

            // Si el carrito está vacío
            if (carrito.length === 0) {
                contenido.innerHTML = '<div class="carrito-vacio">El carrito está vacío</div>';
                totalSection.style.display = "none";
                botonesSection.style.display = "none";
                return;
            }

            // Mostrar items del carrito
            contenido.innerHTML = "";
            let total = 0;

            carrito.forEach(function(item) {
                let subtotal = item.precio * item.cantidad;
                total += subtotal;

                let div = document.createElement("div");
                div.className = "carrito-item";
                div.innerHTML = 
                    '<div class="item-info">' +
                    '    <div class="item-nombre">' + item.nombre + '</div>' +
                    '    <div class="item-precio">' + item.precio + '€</div>' +
                    '    <div class="item-cantidad">Cantidad: ' + item.cantidad + ' | Subtotal: ' + subtotal.toFixed(2) + '€</div>' +
                    '</div>' +
                    '<button class="btn-eliminar" onclick="eliminarDelCarrito(' + item.id + ')">Eliminar</button>';

                contenido.appendChild(div);
            });

            // Mostrar total
            totalElement.textContent = total.toFixed(2) + "€";
            totalSection.style.display = "flex";
            botonesSection.style.display = "flex";
        }

        function finalizarCompra() {
            if (carrito.length === 0) return;

            let total = carrito.reduce(function(sum, item) {
                return sum + (item.precio * item.cantidad);
            }, 0);

            alert("Compra finalizada\nTotal: " + total.toFixed(2) + "€\n\nGracias por tu compra!");

            carrito = [];
            guardarCarrito();
            actualizarCarrito();
        }

        function mostrarNotificacion(mensaje) {
            // Crear elemento de notificación
            let notif = document.createElement("div");
            notif.textContent = mensaje;
            notif.style.cssText = 
                "position: fixed; top: 20px; right: 20px; " +
                "background: #4CAF50; color: white; padding: 15px 20px; " +
                "border-radius: 4px; box-shadow: 0 2px 5px rgba(0,0,0,0.2); " +
                "z-index: 10000; animation: slideIn 0.3s;";

            document.body.appendChild(notif);

            // Eliminar después de 2 segundos
            setTimeout(function() {
                notif.style.animation = "slideOut 0.3s";
                setTimeout(function() {
                    document.body.removeChild(notif);
                }, 300);
            }, 2000);
        }

        // Agregar estilos para animaciones
        let style = document.createElement("style");
        style.textContent = `
            @keyframes slideIn {
                from { transform: translateX(100%); opacity: 0; }
                to { transform: translateX(0); opacity: 1; }
            }
            @keyframes slideOut {
                from { transform: translateX(0); opacity: 1; }
                to { transform: translateX(100%); opacity: 0; }
            }
        `;
        document.head.appendChild(style);
    </script>
</body>
</html>
```

------

### **Actividades**

#### **Actividad 7.1 - Ficha de producto**

Crea una página que muestre la información de un producto usando un objeto. El objeto debe tener las siguientes propiedades:

- nombre
- descripción
- precio
- stock
- categoría

Muestra toda esta información en la página HTML de forma organizada. Añade un botón que permita modificar el precio del producto.

::: details Solución


```html
<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .producto-card {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            margin-top: 0;
            border-bottom: 3px solid #4CAF50;
            padding-bottom: 10px;
        }
        .info-row {
            margin: 20px 0;
            padding: 15px;
            background-color: #f9f9f9;
            border-radius: 5px;
        }
        .info-label {
            font-weight: bold;
            color: #666;
            display: block;
            margin-bottom: 5px;
            font-size: 14px;
        }
        .info-value {
            font-size: 18px;
            color: #333;
        }
        .precio {
            font-size: 32px;
            color: #4CAF50;
            font-weight: bold;
        }
        .stock {
            display: inline-block;
            padding: 5px 15px;
            background-color: #4CAF50;
            color: white;
            border-radius: 20px;
            font-size: 14px;
        }
        .categoria {
            display: inline-block;
            padding: 5px 15px;
            background-color: #2196F3;
            color: white;
            border-radius: 20px;
            font-size: 14px;
        }
        .modificar-precio {
            margin-top: 30px;
            padding: 20px;
            background-color: #fff3cd;
            border-radius: 8px;
            border-left: 4px solid #ffc107;
        }
        .modificar-precio h3 {
            margin-top: 0;
            color: #856404;
        }
        input[type="number"] {
            padding: 10px;
            width: 200px;
            border: 2px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
            margin-right: 10px;
        }
        button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="producto-card">
        <h1 id="nombre"></h1>
        
        <div class="info-row">
            <span class="info-label">Descripción</span>
            <div class="info-value" id="descripcion"></div>
        </div>

        <div class="info-row">
            <span class="info-label">Precio</span>
            <div class="precio" id="precio"></div>
        </div>

        <div class="info-row">
            <span class="info-label">Stock disponible</span>
            <div class="info-value">
                <span class="stock" id="stock"></span>
            </div>
        </div>

        <div class="info-row">
            <span class="info-label">Categoría</span>
            <div class="info-value">
                <span class="categoria" id="categoria"></span>
            </div>
        </div>

        <div class="modificar-precio">
            <h3>Modificar Precio</h3>
            <input type="number" id="nuevoPrecio" placeholder="Nuevo precio" step="0.01" min="0">
            <button onclick="modificarPrecio()">Actualizar Precio</button>
        </div>
    </div>

    <script>
        // Crear objeto producto
        let producto = {
            nombre: "Portátil Gaming MSI",
            descripcion: "Portátil de alto rendimiento con procesador Intel Core i7, 16GB RAM, SSD 512GB y tarjeta gráfica NVIDIA RTX 3060. Ideal para gaming y diseño.",
            precio: 1299.99,
            stock: 15,
            categoria: "Electrónica"
        };

        // Función para mostrar el producto
        function mostrarProducto() {
            document.getElementById("nombre").textContent = producto.nombre;
            document.getElementById("descripcion").textContent = producto.descripcion;
            document.getElementById("precio").textContent = producto.precio.toFixed(2) + "€";
            document.getElementById("stock").textContent = producto.stock + " unidades";
            document.getElementById("categoria").textContent = producto.categoria;
        }

        // Función para modificar el precio
        function modificarPrecio() {
            let nuevoPrecio = document.getElementById("nuevoPrecio").value;
            
            // Validar que sea un número válido
            if (nuevoPrecio === "" || isNaN(nuevoPrecio) || parseFloat(nuevoPrecio) <= 0) {
                alert("Por favor, introduce un precio válido");
                return;
            }
            
            // Actualizar el precio del objeto
            producto.precio = parseFloat(nuevoPrecio);
            
            // Actualizar la visualización
            document.getElementById("precio").textContent = producto.precio.toFixed(2) + "€";
            
            // Limpiar input
            document.getElementById("nuevoPrecio").value = "";
            
            // Mostrar confirmación
            alert("Precio actualizado correctamente a " + producto.precio.toFixed(2) + "€");
        }

        // Mostrar producto al cargar la página
        window.addEventListener("load", function() {
            mostrarProducto();
        });
    </script>
</body>
</html>
```

:::

---
#### **Actividad 7.2 - Preferencias de usuario con localStorage**

Crea una página de configuración donde el usuario pueda:

- Seleccionar un tema (claro/oscuro)
- Elegir el tamaño de fuente (pequeño/mediano/grande)
- Activar/desactivar notificaciones (checkbox)

Los cambios deben:

1. Aplicarse inmediatamente en la página
2. Guardarse en localStorage
3. Mantenerse al recargar la página

::: details Solución

```html
<!DOCTYPE html>
<html>
<head>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            padding: 40px;
            transition: all 0.3s;
        }
        body.tema-claro {
            background-color: #ffffff;
            color: #333333;
        }
        body.tema-oscuro {
            background-color: #1a1a1a;
            color: #ffffff;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
        }
        h1 {
            margin-bottom: 10px;
        }
        .subtitulo {
            margin-bottom: 40px;
            opacity: 0.7;
        }
        .configuracion {
            background-color: rgba(100, 100, 100, 0.1);
            padding: 30px;
            border-radius: 10px;
            margin-bottom: 30px;
        }
        .tema-oscuro .configuracion {
            background-color: rgba(255, 255, 255, 0.1);
        }
        .opcion {
            margin-bottom: 25px;
            padding-bottom: 25px;
            border-bottom: 1px solid rgba(100, 100, 100, 0.2);
        }
        .opcion:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }
        .opcion-label {
            font-weight: bold;
            font-size: 18px;
            display: block;
            margin-bottom: 10px;
        }
        .opcion-descripcion {
            font-size: 14px;
            opacity: 0.7;
            margin-bottom: 15px;
        }
        select {
            padding: 10px 15px;
            font-size: 16px;
            border: 2px solid #4CAF50;
            border-radius: 5px;
            background-color: white;
            cursor: pointer;
            min-width: 200px;
        }
        .tema-oscuro select {
            background-color: #333;
            color: white;
        }
        .checkbox-container {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
        }
        input[type="checkbox"] {
            width: 20px;
            height: 20px;
            cursor: pointer;
        }
        .contenido-ejemplo {
            padding: 30px;
            background-color: rgba(100, 100, 100, 0.05);
            border-radius: 10px;
            transition: font-size 0.3s;
        }
        .tema-oscuro .contenido-ejemplo {
            background-color: rgba(255, 255, 255, 0.05);
        }
        .contenido-ejemplo h2 {
            margin-bottom: 15px;
        }
        .contenido-ejemplo p {
            line-height: 1.6;
            margin-bottom: 15px;
        }
        .notificacion {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 15px 25px;
            background-color: #4CAF50;
            color: white;
            border-radius: 5px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.2);
            display: none;
            animation: slideIn 0.3s;
        }
        .notificacion.mostrar {
            display: block;
        }
        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        .fuente-pequena { font-size: 14px; }
        .fuente-mediana { font-size: 16px; }
        .fuente-grande { font-size: 20px; }
        .estado-guardado {
            margin-top: 20px;
            padding: 15px;
            background-color: #e8f5e9;
            border-left: 4px solid #4CAF50;
            border-radius: 4px;
        }
        .tema-oscuro .estado-guardado {
            background-color: rgba(76, 175, 80, 0.2);
        }
    </style>
</head>
<body class="tema-claro">
    <div id="notificacion" class="notificacion"></div>

    <div class="container">
        <h1>⚙️ Configuración de Preferencias</h1>
        <p class="subtitulo">Personaliza tu experiencia. Los cambios se guardan automáticamente.</p>

        <div class="configuracion">
            <div class="opcion">
                <label class="opcion-label">Tema de color</label>
                <p class="opcion-descripcion">Elige entre tema claro u oscuro</p>
                <select id="selectTema" onchange="cambiarTema()">
                    <option value="claro">Claro</option>
                    <option value="oscuro">Oscuro</option>
                </select>
            </div>

            <div class="opcion">
                <label class="opcion-label">Tamaño de fuente</label>
                <p class="opcion-descripcion">Ajusta el tamaño del texto según tu preferencia</p>
                <select id="selectFuente" onchange="cambiarFuente()">
                    <option value="pequena">Pequeño</option>
                    <option value="mediana">Mediano</option>
                    <option value="grande">Grande</option>
                </select>
            </div>

            <div class="opcion">
                <label class="opcion-label">Notificaciones</label>
                <p class="opcion-descripcion">Recibe avisos de nuevas actualizaciones</p>
                <label class="checkbox-container">
                    <input type="checkbox" id="checkNotificaciones" onchange="cambiarNotificaciones()">
                    <span>Activar notificaciones</span>
                </label>
            </div>
        </div>

        <div class="estado-guardado">
            <strong>✓ Estado:</strong> Todas las preferencias se guardan automáticamente en tu navegador
        </div>

        <div class="contenido-ejemplo" id="contenidoEjemplo">
            <h2>Contenido de Ejemplo</h2>
            <p>Este es un texto de ejemplo que cambiará según tus preferencias. Puedes ver cómo afecta el tema de color y el tamaño de fuente que hayas seleccionado.</p>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris.</p>
            <p>Las preferencias se mantienen incluso si cierras esta página y vuelves más tarde. Todo se guarda en el localStorage de tu navegador.</p>
        </div>
    </div>

    <script>
        // Objeto para almacenar las preferencias
        let preferencias = {
            tema: "claro",
            fuente: "mediana",
            notificaciones: false
        };

        // Al cargar la página, recuperar preferencias guardadas
        window.addEventListener("load", function() {
            cargarPreferencias();
            aplicarPreferencias();
        });

        function cargarPreferencias() {
            let preferenciasJSON = localStorage.getItem("preferenciasUsuario");
            
            if (preferenciasJSON !== null) {
                preferencias = JSON.parse(preferenciasJSON);
            }
        }

        function guardarPreferencias() {
            let preferenciasJSON = JSON.stringify(preferencias);
            localStorage.setItem("preferenciasUsuario", preferenciasJSON);
        }

        function aplicarPreferencias() {
            // Aplicar tema
            document.body.className = "tema-" + preferencias.tema;
            document.getElementById("selectTema").value = preferencias.tema;
            
            // Aplicar fuente
            let contenido = document.getElementById("contenidoEjemplo");
            contenido.className = "contenido-ejemplo fuente-" + preferencias.fuente;
            document.getElementById("selectFuente").value = preferencias.fuente;
            
            // Aplicar notificaciones
            document.getElementById("checkNotificaciones").checked = preferencias.notificaciones;
            
            // Si las notificaciones están activas, mostrar mensaje de bienvenida
            if (preferencias.notificaciones) {
                mostrarNotificacion("¡Bienvenido de nuevo! Tus preferencias se han cargado.");
            }
        }

        function cambiarTema() {
            preferencias.tema = document.getElementById("selectTema").value;
            document.body.className = "tema-" + preferencias.tema;
            guardarPreferencias();
            mostrarNotificacion("Tema cambiado a " + preferencias.tema);
        }

        function cambiarFuente() {
            preferencias.fuente = document.getElementById("selectFuente").value;
            let contenido = document.getElementById("contenidoEjemplo");
            contenido.className = "contenido-ejemplo fuente-" + preferencias.fuente;
            guardarPreferencias();
            mostrarNotificacion("Tamaño de fuente cambiado");
        }

        function cambiarNotificaciones() {
            preferencias.notificaciones = document.getElementById("checkNotificaciones").checked;
            guardarPreferencias();
            
            if (preferencias.notificaciones) {
                mostrarNotificacion("Notificaciones activadas");
            } else {
                mostrarNotificacion("Notificaciones desactivadas");
            }
        }

        function mostrarNotificacion(mensaje) {
            let notif = document.getElementById("notificacion");
            notif.textContent = mensaje;
            notif.classList.add("mostrar");
            
            setTimeout(function() {
                notif.classList.remove("mostrar");
            }, 3000);
        }
    </script>
</body>
</html>
```

:::

---
#### **Actividad 7.3 - Directorio de contactos**

Crea una aplicación de directorio de contactos con:

- Formulario para añadir contactos (nombre, teléfono, email)
- Lista que muestre todos los contactos
- Botón para eliminar contactos individuales
- Los contactos deben guardarse en localStorage como array de objetos JSON

Debe mantener los contactos al cerrar y abrir el navegador.

::: details Solución

```html
<!DOCTYPE html>
<html>
<head>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin-bottom: 10px;
        }
        .header p {
            opacity: 0.9;
        }
        .formulario {
            padding: 30px;
            background-color: #f9f9f9;
            border-bottom: 2px solid #eee;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #333;
        }
        input {
            width: 100%;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            transition: border-color 0.3s;
        }
        input:focus {
            outline: none;
            border-color: #667eea;
        }
        .btn-agregar {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: transform 0.2s;
        }
        .btn-agregar:hover {
            transform: translateY(-2px);
        }
        .lista-contactos {
            padding: 30px;
        }
        .lista-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .lista-header h2 {
            color: #333;
        }
        .contador {
            background-color: #667eea;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 14px;
        }
        .contacto {
            background-color: #f9f9f9;
            padding: 20px;
            margin-bottom: 15px;
            border-radius: 8px;
            border-left: 4px solid #667eea;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: transform 0.2s;
        }
        .contacto:hover {
            transform: translateX(5px);
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .contacto-info {
            flex: 1;
        }
        .contacto-nombre {
            font-size: 20px;
            font-weight: bold;
            color: #333;
            margin-bottom: 8px;
        }
        .contacto-detalle {
            display: flex;
            align-items: center;
            gap: 5px;
            margin: 5px 0;
            color: #666;
            font-size: 14px;
        }
        .icono {
            font-size: 16px;
        }
        .btn-eliminar {
            padding: 10px 20px;
            background-color: #f44336;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .btn-eliminar:hover {
            background-color: #da190b;
        }
        .vacio {
            text-align: center;
            padding: 60px 20px;
            color: #999;
        }
        .vacio-icono {
            font-size: 64px;
            margin-bottom: 20px;
        }
        .btn-limpiar-todo {
            width: 100%;
            padding: 12px;
            background-color: #f44336;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 20px;
            font-size: 16px;
        }
        .btn-limpiar-todo:hover {
            background-color: #da190b;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📇 Directorio de Contactos</h1>
            <p>Guarda y organiza tus contactos</p>
        </div>

        <div class="formulario">
            <div class="form-group">
                <label>👤 Nombre completo</label>
                <input type="text" id="nombre" placeholder="Ej: Juan Pérez">
            </div>

            <div class="form-group">
                <label>📞 Teléfono</label>
                <input type="tel" id="telefono" placeholder="Ej: 612345678">
            </div>

            <div class="form-group">
                <label>📧 Email</label>
                <input type="email" id="email" placeholder="Ej: juan@example.com">
            </div>

            <button class="btn-agregar" onclick="agregarContacto()">Agregar Contacto</button>
        </div>

        <div class="lista-contactos">
            <div class="lista-header">
                <h2>Mis Contactos</h2>
                <span class="contador" id="contador">0 contactos</span>
            </div>

            <div id="listaContactos"></div>

            <button class="btn-limpiar-todo" id="btnLimpiar" onclick="limpiarTodos()" style="display: none;">
                Eliminar Todos los Contactos
            </button>
        </div>
    </div>

    <script>
        let contactos = [];

        // Al cargar la página
        window.addEventListener("load", function() {
            cargarContactos();
            mostrarContactos();
        });

        function cargarContactos() {
            let contactosJSON = localStorage.getItem("contactos");
            
            if (contactosJSON !== null) {
                contactos = JSON.parse(contactosJSON);
            }
        }

        function guardarContactos() {
            let contactosJSON = JSON.stringify(contactos);
            localStorage.setItem("contactos", contactosJSON);
        }

        function agregarContacto() {
            let nombre = document.getElementById("nombre").value.trim();
            let telefono = document.getElementById("telefono").value.trim();
            let email = document.getElementById("email").value.trim();

            // Validaciones
            if (nombre === "") {
                alert("Por favor, introduce un nombre");
                return;
            }

            if (telefono === "") {
                alert("Por favor, introduce un teléfono");
                return;
            }

            if (email === "") {
                alert("Por favor, introduce un email");
                return;
            }

            // Validar formato de email básico
            if (!email.includes("@") || !email.includes(".")) {
                alert("Por favor, introduce un email válido");
                return;
            }

            // Crear objeto contacto
            let nuevoContacto = {
                id: Date.now(),
                nombre: nombre,
                telefono: telefono,
                email: email,
                fechaCreacion: new Date().toLocaleDateString()
            };

            // Agregar al array
            contactos.push(nuevoContacto);

            // Guardar en localStorage
            guardarContactos();

            // Actualizar interfaz
            mostrarContactos();

            // Limpiar formulario
            document.getElementById("nombre").value = "";
            document.getElementById("telefono").value = "";
            document.getElementById("email").value = "";

            // Focus en nombre para agregar otro
            document.getElementById("nombre").focus();
        }

        function eliminarContacto(id) {
            if (confirm("¿Estás seguro de que quieres eliminar este contacto?")) {
                contactos = contactos.filter(function(contacto) {
                    return contacto.id !== id;
                });

                guardarContactos();
                mostrarContactos();
            }
        }

        function limpiarTodos() {
            if (confirm("¿Estás seguro de que quieres eliminar TODOS los contactos? Esta acción no se puede deshacer.")) {
                contactos = [];
                guardarContactos();
                mostrarContactos();
            }
        }

        function mostrarContactos() {
            let lista = document.getElementById("listaContactos");
            let contador = document.getElementById("contador");
            let btnLimpiar = document.getElementById("btnLimpiar");

            // Actualizar contador
            let total = contactos.length;
            contador.textContent = total + (total === 1 ? " contacto" : " contactos");

            // Si no hay contactos
            if (contactos.length === 0) {
                lista.innerHTML = 
                    '<div class="vacio">' +
                    '    <div class="vacio-icono">📭</div>' +
                    '    <h3>No hay contactos</h3>' +
                    '    <p>Añade tu primer contacto usando el formulario</p>' +
                    '</div>';
                btnLimpiar.style.display = "none";
                return;
            }

            // Mostrar botón limpiar
            btnLimpiar.style.display = "block";

            // Mostrar contactos
            lista.innerHTML = "";

            // Ordenar contactos por nombre
            let contactosOrdenados = contactos.sort(function(a, b) {
                return a.nombre.localeCompare(b.nombre);
            });

            contactosOrdenados.forEach(function(contacto) {
                let div = document.createElement("div");
                div.className = "contacto";
                div.innerHTML = 
                    '<div class="contacto-info">' +
                    '    <div class="contacto-nombre">' + contacto.nombre + '</div>' +
                    '    <div class="contacto-detalle">' +
                    '        <span class="icono">📞</span>' +
                    '        <span>' + contacto.telefono + '</span>' +
                    '    </div>' +
                    '    <div class="contacto-detalle">' +
                    '        <span class="icono">📧</span>' +
                    '        <span>' + contacto.email + '</span>' +
                    '    </div>' +
                    '    <div class="contacto-detalle">' +
                    '        <span class="icono">📅</span>' +
                    '        <span>Añadido el ' + contacto.fechaCreacion + '</span>' +
                    '    </div>' +
                    '</div>' +
                    '<button class="btn-eliminar" onclick="eliminarContacto(' + contacto.id + ')">Eliminar</button>';

                lista.appendChild(div);
            });
        }

        // Permitir agregar contacto con Enter en cualquier campo
        document.getElementById("nombre").addEventListener("keypress", function(e) {
            if (e.key === "Enter") agregarContacto();
        });
        document.getElementById("telefono").addEventListener("keypress", function(e) {
            if (e.key === "Enter") agregarContacto();
        });
        document.getElementById("email").addEventListener("keypress", function(e) {
            if (e.key === "Enter") agregarContacto();
        });
    </script>
</body>
</html>
```

:::

---

#### **Actividad 7.4 - Calculadora de gastos**

Crea una calculadora de gastos mensuales que:

- Permita añadir gastos (descripción, cantidad, categoría)
- Muestre una lista de todos los gastos
- Calcule y muestre el total gastado
- Permita eliminar gastos individuales
- Guarde todos los datos en localStorage usando JSON

**Bonus:** Añade un botón para "Nuevo mes" que limpie los gastos anteriores.

::: details Solución

```html
<!DOCTYPE html>
<html>
<head>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            padding: 40px 30px;
            text-align: center;
        }
        .header h1 {
            font-size: 32px;
            margin-bottom: 10px;
        }
        .header p {
            opacity: 0.9;
            font-size: 16px;
        }
        .resumen {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            padding: 30px;
            background-color: #f8f9fa;
        }
        .resumen-card {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            text-align: center;
        }
        .resumen-label {
            font-size: 14px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 10px;
        }
        .resumen-valor {
            font-size: 36px;
            font-weight: bold;
            color: #667eea;
        }
        .resumen-card.total .resumen-valor {
            color: #f5576c;
        }
        .formulario {
            padding: 30px;
            background-color: #f8f9fa;
            border-bottom: 2px solid #eee;
        }
        .formulario h2 {
            color: #333;
            margin-bottom: 20px;
        }
        .form-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr;
            gap: 15px;
            margin-bottom: 15px;
        }
        .form-group {
            display: flex;
            flex-direction: column;
        }
        label {
            margin-bottom: 5px;
            font-weight: bold;
            color: #555;
            font-size: 14px;
        }
        input, select {
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s;
        }
        input:focus, select:focus {
            outline: none;
            border-color: #667eea;
        }
        .btn-agregar {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: transform 0.2s;
        }
        .btn-agregar:hover {
            transform: translateY(-2px);
        }
        .lista-gastos {
            padding: 30px;
        }
        .lista-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }
        .lista-header h2 {
            color: #333;
        }
        .acciones {
            display: flex;
            gap: 10px;
        }
        .btn-secundario {
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
            transition: all 0.3s;
        }
        .btn-nuevo-mes {
            background-color: #4CAF50;
            color: white;
        }
        .btn-nuevo-mes:hover {
            background-color: #45a049;
        }
        .btn-exportar {
            background-color: #2196F3;
            color: white;
        }
        .btn-exportar:hover {
            background-color: #0b7dda;
        }
        .gasto {
            background-color: #f9f9f9;
            padding: 20px;
            margin-bottom: 15px;
            border-radius: 10px;
            border-left: 5px solid #667eea;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: all 0.3s;
        }
        .gasto:hover {
            transform: translateX(5px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .gasto-info {
            flex: 1;
        }
        .gasto-header {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 8px;
        }
        .gasto-descripcion {
            font-size: 18px;
            font-weight: bold;
            color: #333;
        }
        .gasto-categoria {
            display: inline-block;
            padding: 4px 12px;
            background-color: #667eea;
            color: white;
            border-radius: 15px;
            font-size: 12px;
            font-weight: bold;
        }
        .gasto-detalles {
            display: flex;
            gap: 20px;
            color: #666;
            font-size: 14px;
        }
        .gasto-cantidad {
            font-size: 24px;
            font-weight: bold;
            color: #f5576c;
            margin-right: 20px;
        }
        .btn-eliminar {
            padding: 10px 20px;
            background-color: #f44336;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s;
        }
        .btn-eliminar:hover {
            background-color: #da190b;
        }
        .vacio {
            text-align: center;
            padding: 80px 20px;
            color: #999;
        }
        .vacio-icono {
            font-size: 80px;
            margin-bottom: 20px;
        }
        .vacio h3 {
            margin-bottom: 10px;
            color: #666;
        }
        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
            .resumen {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>💰 Calculadora de Gastos Mensuales</h1>
            <p>Controla tus finanzas personales</p>
        </div>

        <div class="resumen">
            <div class="resumen-card">
                <div class="resumen-label">Total de Gastos</div>
                <div class="resumen-valor" id="cantidadGastos">0</div>
            </div>
            <div class="resumen-card total">
                <div class="resumen-label">Gasto Total</div>
                <div class="resumen-valor" id="gastoTotal">0.00€</div>
            </div>
        </div>

        <div class="formulario">
            <h2>➕ Añadir Nuevo Gasto</h2>
            <div class="form-grid">
                <div class="form-group">
                    <label>Descripción</label>
                    <input type="text" id="descripcion" placeholder="Ej: Compra supermercado">
                </div>
                <div class="form-group">
                    <label>Cantidad (€)</label>
                    <input type="number" id="cantidad" placeholder="0.00" step="0.01" min="0">
                </div>
                <div class="form-group">
                    <label>Categoría</label>
                    <select id="categoria">
                        <option value="Alimentación">🍔 Alimentación</option>
                        <option value="Transporte">🚗 Transporte</option>
                        <option value="Vivienda">🏠 Vivienda</option>
                        <option value="Ocio">🎮 Ocio</option>
                        <option value="Salud">💊 Salud</option>
                        <option value="Educación">📚 Educación</option>
                        <option value="Ropa">👔 Ropa</option>
                        <option value="Otros">📦 Otros</option>
                    </select>
                </div>
            </div>
            <button class="btn-agregar" onclick="agregarGasto()">Agregar Gasto</button>
        </div>

        <div class="lista-gastos">
            <div class="lista-header">
                <h2>📋 Mis Gastos</h2>
                <div class="acciones">
                    <button class="btn-secundario btn-exportar" onclick="exportarGastos()">Exportar</button>
                    <button class="btn-secundario btn-nuevo-mes" onclick="nuevoMes()">Nuevo Mes</button>
                </div>
            </div>
            <div id="listaGastos"></div>
        </div>
    </div>

    <script>
        let gastos = [];
        let mesActual = obtenerMesActual();

        // Al cargar la página
        window.addEventListener("load", function() {
            cargarGastos();
            mostrarGastos();
            actualizarResumen();
        });

        function obtenerMesActual() {
            let fecha = new Date();
            return fecha.getFullYear() + "-" + (fecha.getMonth() + 1);
        }

        function cargarGastos() {
            // Cargar gastos del mes actual
            let claveMes = "gastos_" + mesActual;
            let gastosJSON = localStorage.getItem(claveMes);
            
            if (gastosJSON !== null) {
                gastos = JSON.parse(gastosJSON);
            }
        }

        function guardarGastos() {
            let claveMes = "gastos_" + mesActual;
            let gastosJSON = JSON.stringify(gastos);
            localStorage.setItem(claveMes, gastosJSON);
        }

        function agregarGasto() {
            let descripcion = document.getElementById("descripcion").value.trim();
            let cantidad = document.getElementById("cantidad").value;
            let categoria = document.getElementById("categoria").value;

            // Validaciones
            if (descripcion === "") {
                alert("Por favor, introduce una descripción");
                return;
            }

            if (cantidad === "" || isNaN(cantidad) || parseFloat(cantidad) <= 0) {
                alert("Por favor, introduce una cantidad válida");
                return;
            }

            // Crear objeto gasto
            let nuevoGasto = {
                id: Date.now(),
                descripcion: descripcion,
                cantidad: parseFloat(cantidad),
                categoria: categoria,
                fecha: new Date().toLocaleDateString(),
                timestamp: new Date().toISOString()
            };

            // Agregar al array
            gastos.push(nuevoGasto);

            // Guardar en localStorage
            guardarGastos();

            // Actualizar interfaz
            mostrarGastos();
            actualizarResumen();

            // Limpiar formulario
            document.getElementById("descripcion").value = "";
            document.getElementById("cantidad").value = "";
            document.getElementById("categoria").selectedIndex = 0;

            // Focus en descripción
            document.getElementById("descripcion").focus();
        }

        function eliminarGasto(id) {
            if (confirm("¿Estás seguro de que quieres eliminar este gasto?")) {
                gastos = gastos.filter(function(gasto) {
                    return gasto.id !== id;
                });

                guardarGastos();
                mostrarGastos();
                actualizarResumen();
            }
        }

        function nuevoMes() {
            let mensaje = "¿Quieres empezar un nuevo mes?\n\n";
            mensaje += "Se limpiarán todos los gastos actuales.\n";
            mensaje += "Los datos del mes anterior se guardarán en el historial.";

            if (confirm(mensaje)) {
                gastos = [];
                mesActual = obtenerMesActual();
                guardarGastos();
                mostrarGastos();
                actualizarResumen();
                alert("¡Nuevo mes iniciado! Los gastos anteriores están guardados.");
            }
        }

        function exportarGastos() {
            if (gastos.length === 0) {
                alert("No hay gastos para exportar");
                return;
            }

            let texto = "RESUMEN DE GASTOS\n";
            texto += "==================\n\n";

            let total = 0;

            gastos.forEach(function(gasto) {
                texto += gasto.fecha + " - " + gasto.descripcion + "\n";
                texto += "Categoría: " + gasto.categoria + "\n";
                texto += "Cantidad: " + gasto.cantidad.toFixed(2) + "€\n";
                texto += "---\n";
                total += gasto.cantidad;
            });

            texto += "\nTOTAL: " + total.toFixed(2) + "€\n";

            // Crear archivo de texto y descargarlo
            let blob = new Blob([texto], { type: 'text/plain' });
            let url = window.URL.createObjectURL(blob);
            let a = document.createElement('a');
            a.href = url;
            a.download = 'gastos_' + mesActual + '.txt';
            a.click();
            window.URL.revokeObjectURL(url);

            alert("Gastos exportados correctamente");
        }

        function mostrarGastos() {
            let lista = document.getElementById("listaGastos");

            if (gastos.length === 0) {
                lista.innerHTML = 
                    '<div class="vacio">' +
                    '    <div class="vacio-icono">📭</div>' +
                    '    <h3>No hay gastos registrados</h3>' +
                    '    <p>Añade tu primer gasto usando el formulario</p>' +
                    '</div>';
                return;
            }

            lista.innerHTML = "";

            // Ordenar gastos por fecha (más recientes primero)
            let gastosOrdenados = gastos.sort(function(a, b) {
                return new Date(b.timestamp) - new Date(a.timestamp);
            });

            gastosOrdenados.forEach(function(gasto) {
                let div = document.createElement("div");
                div.className = "gasto";
                div.innerHTML = 
                    '<div class="gasto-info">' +
                    '    <div class="gasto-header">' +
                    '        <div class="gasto-descripcion">' + gasto.descripcion + '</div>' +
                    '        <span class="gasto-categoria">' + gasto.categoria + '</span>' +
                    '    </div>' +
                    '    <div class="gasto-detalles">' +
                    '        <span>📅 ' + gasto.fecha + '</span>' +
                    '    </div>' +
                    '</div>' +
                    '<div style="display: flex; align-items: center; gap: 15px;">' +
                    '    <div class="gasto-cantidad">' + gasto.cantidad.toFixed(2) + '€</div>' +
                    '    <button class="btn-eliminar" onclick="eliminarGasto(' + gasto.id + ')">Eliminar</button>' +
                    '</div>';

                lista.appendChild(div);
            });
        }

        function actualizarResumen() {
            let cantidadGastos = document.getElementById("cantidadGastos");
            let gastoTotal = document.getElementById("gastoTotal");

            cantidadGastos.textContent = gastos.length;

            let total = gastos.reduce(function(sum, gasto) {
                return sum + gasto.cantidad;
            }, 0);

            gastoTotal.textContent = total.toFixed(2) + "€";
        }

        // Permitir agregar gasto con Enter
        document.getElementById("descripcion").addEventListener("keypress", function(e) {
            if (e.key === "Enter") agregarGasto();
        });
        document.getElementById("cantidad").addEventListener("keypress", function(e) {
            if (e.key === "Enter") agregarGasto();
        });
    </script>
</body>
</html>
```

:::
