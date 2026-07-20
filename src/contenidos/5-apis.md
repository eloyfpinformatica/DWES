# Unidad 5: Intercambio de información mediante APIs



## Índice de Contenidos

**1.** [Introducción: Intercambio de información en aplicaciones web](#_1-introduccion-el-intercambio-de-informacion-en-la-web-moderna)  
**2.** [Conceptos fundamentales de APIs](#_2-conceptos-fundamentales-de-apis)  
**3.** [Programación asíncrona en JavaScript](#_3-programacion-asincrona-en-javascript)  
**4.** [Consumo de APIs con Fetch](#_4-consumo-de-apis-con-fetch)  
**5.** [Trabajo con datos JSON](#_5-trabajo-con-datos-json)  
**6.** [Herramientas de desarrollo](#_6-herramientas-de-desarrollo)  
**7.** [Proyectos Prácticos](#_7-proyectos-practicos)
  - [Proyecto 1: Buscador de Películas](#🎬-proyecto-1-buscador-de-peliculas)
  - [Proyecto 2: Dashboard Meteorológico](#🌤%EF%B8%8F-proyecto-2-dashboard-meteorologico)
  - [Proyecto 3: Explorador de Museos y Arte](#🎨-proyecto-3-explorador-de-museos-y-arte)


## 1. Introducción: El Intercambio de Información en la Web Moderna

### 1.1. Por qué necesitamos intercambiar información

La web moderna no consiste solo en páginas estáticas. Las aplicaciones actuales necesitan comunicarse constantemente con servidores y otros servicios para:

- **Obtener datos actualizados** sin recargar la página (Single Page Applications)
- **Integrar servicios externos** (mapas, pagos, redes sociales)
- **Sincronizar datos** entre diferentes dispositivos
- **Construir aplicaciones modulares** y escalables

#### Ejemplo

Cuando abres Instagram, tu teléfono no descarga toda la aplicación de nuevo. Hace peticiones a los servidores de Instagram para obtener las nuevas publicaciones, comentarios y notificaciones. Esto es **intercambio de información mediante APIs**.

### 1.2. Ámbitos de aplicación en el desarrollo web

**1. Integración de sistemas**

- Conectar una tienda online con sistemas de pago (Stripe, PayPal)
- Integrar servicios de envío y tracking
- Sincronizar inventario con proveedores

**2. Exposición de datos**
- Permitir que otras aplicaciones accedan a tus datos
- Crear APIs para aplicaciones móviles
- Ofrecer servicios B2B (Business to Business)

**3. Consumo de servicios**

- Mostrar mapas de Google en tu sitio
- Obtener datos meteorológicos actualizados
- Consumir bases de datos públicas (películas, libros, etc.)

### 1.3. Formatos de intercambio: JSON vs XML

#### JSON (JavaScript Object Notation)

- ✅ Formato ligero y fácil de leer
- ✅ Nativo en JavaScript
- ✅ Dominante en APIs REST modernas (>95%)
- ✅ Menos verboso que XML

```json
{
  "nombre": "Ana García",
  "edad": 25,
  "ciudad": "Madrid",
  "intereses": ["programación", "diseño", "música"]
}
```

#### XML (eXtensible Markup Language)

- Más estructurado y estricto
- Usado en sistemas legacy y SOAP
- Soporta validación mediante esquemas (XSD)
- Común en RSS/Atom feeds

```xml
<persona>
  <nombre>Ana García</nombre>
  <edad>25</edad>
  <ciudad>Madrid</ciudad>
  <intereses>
    <interes>programación</interes>
    <interes>diseño</interes>
    <interes>música</interes>
  </intereses>
</persona>
```

### 📣 Ejemplo
🌍 [RestCountries](5.1_introduccion.md)



## 2. Conceptos Fundamentales de APIs

### 2.1. ¿Qué es una API?

**API** significa **Application Programming Interface** (Interfaz de Programación de Aplicaciones). Es un conjunto de reglas y protocolos que permite que dos aplicaciones se comuniquen entre sí.

#### 🍽️ Analogía del restaurante

- **Tú** → el cliente (tu aplicación web)
- **La cocina** → el servidor (donde están los datos)
- **El camarero** → la API (el intermediario)
- **El menú** → los endpoints disponibles

Tú no entras directamente a la cocina a preparar tu comida. Le dices al camarero (API) lo que quieres, él se lo comunica a la cocina (servidor), y te trae lo que pediste. La API funciona exactamente igual.

### 2.2. Tipos de APIs

| Tipo | Descripción | Uso habitual |
|------|-------------|--------------|
| **REST** | Más común, usa HTTP, devuelve JSON | APIs modernas (GitHub, Twitter, TMDb) |
| **SOAP** | Protocolo formal, usa XML | Banca, sistemas legacy |
| **GraphQL** | El cliente define qué datos quiere | Aplicaciones con datos complejos |

::: tip **REST** 
**Nos centraremos en REST** por ser el más utilizado y el mejor punto de partida.
:::

### 2.3. Principios REST: Recursos, URIs y Verbos HTTP

**1. Recursos** — Un recurso es cualquier información que pueda ser nombrada: un usuario, una película, un producto...

**2. URIs** — Cada recurso tiene una URI única y descriptiva:

```
https://api.ejemplo.com/usuarios              → Todos los usuarios
https://api.ejemplo.com/usuarios/123          → Usuario con ID 123
https://api.ejemplo.com/usuarios/123/pedidos  → Pedidos del usuario 123
https://api.ejemplo.com/productos?categoria=libros
```

**Buenas prácticas:**

- Usar sustantivos en plural (`/usuarios`, no `/usuario`)
- Minúsculas y guiones (`/mis-pedidos`)
- No incluir verbos en la URI (la acción la indica el método HTTP)

**3. Verbos HTTP**

| Verbo | Propósito | Ejemplo |
|-------|-----------|---------|
| **GET** | Obtener/leer datos (no modifica nada) | Ver perfil |
| **POST** | Crear un nuevo recurso | Crear usuario |
| **PUT** | Actualizar/reemplazar un recurso completo | Editar perfil completo |
| **PATCH** | Actualizar parcialmente un recurso | Cambiar solo el email |
| **DELETE** | Eliminar un recurso | Borrar cuenta |

### 2.4. Códigos de estado HTTP

| Código | Significado | Descripción |
|--------|-------------|-------------|
| **200** | OK | La petición fue exitosa |
| **201** | Created | Recurso creado exitosamente (POST) |
| **204** | No Content | Éxito, sin datos que devolver (DELETE) |
| **400** | Bad Request | Petición mal formada o datos inválidos |
| **401** | Unauthorized | Falta autenticación (API key, token) |
| **403** | Forbidden | Sin permiso para acceder al recurso |
| **404** | Not Found | El recurso solicitado no existe |
| **429** | Too Many Requests | Excediste el límite de peticiones |
| **500** | Internal Server Error | Error en el servidor (no es culpa tuya) |
| **503** | Service Unavailable | Servidor temporalmente caído |

**Familias de códigos:**
- 🟢 **2xx** — Éxito
- 🟡 **4xx** — Error del cliente (tú hiciste algo mal)
- 🔴 **5xx** — Error del servidor

---

### 📣 Ejemplo final
[Conceptos APIS](5.2_conceptos_apis.md)



## 3. Programación Asíncrona en JavaScript

::: info **Programación asíncrona** 
Esta es la parte más desafiante de la unidad. La programación asíncrona es fundamental para trabajar con APIs, ya que las peticiones HTTP toman tiempo en completarse.
:::

### 3.1. El problema de la asincronía

**JavaScript es de un solo hilo (single-threaded):** solo puede hacer una cosa a la vez. Si una operación tarda mucho (como pedir datos a un servidor), bloquearía toda la aplicación hasta que termine.

Si JavaScript esperara bloqueando durante una petición de red:
- ❌ La página se congelaría
- ❌ No podrías hacer clic en nada
- ❌ Las animaciones se detendrían
- ❌ El usuario pensaría que la web está rota

**La solución:** JavaScript *inicia* la operación y *sigue ejecutando* el resto del código. Cuando los datos están listos, ejecuta una función de retorno.

### 3.2. Callbacks y el 'callback hell'

Un **callback** es una función que se pasa como argumento a otra función para ser ejecutada después.

```javascript
console.log("1. Inicio");

setTimeout(function() {
  console.log("2. Esto se ejecuta después de 2 segundos");
}, 2000);

console.log("3. Esto se ejecuta inmediatamente");

// Output:
// 1. Inicio
// 3. Esto se ejecuta inmediatamente
// (2 segundos después...)
// 2. Esto se ejecuta después de 2 segundos
```

#### El problema: Callback Hell 🔥

Cuando necesitas hacer varias operaciones en secuencia, los callbacks se anidan y crean una **"pirámide del doom"**:

```javascript
obtenerUsuario(userId, function(usuario) {
  obtenerPedidos(usuario.id, function(pedidos) {
    obtenerDetalles(pedidos[0].id, function(detalles) {
      procesarPago(detalles.precio, function(resultado) {
        enviarConfirmacion(resultado, function(confirmacion) {
          console.log("Todo listo"); // 5 niveles de profundidad
        });
      });
    });
  });
});
```

**Problemas:** difícil de leer, difícil manejar errores, inmanejable a escala.

### 3.3. Promesas: estados y métodos

Las **Promesas** (Promises) son la solución al callback hell. Representan un valor que puede estar disponible ahora, en el futuro, o nunca.

**Estados de una Promesa:**
- **Pending (pendiente):** Estado inicial, aún no se ha completado
- **Fulfilled (cumplida):** La operación terminó exitosamente
- **Rejected (rechazada):** La operación falló

```javascript
const miPromesa = new Promise((resolve, reject) => {
  setTimeout(() => {
    const exito = true;
    if (exito) {
      resolve("¡Operación exitosa!");
    } else {
      reject("Algo salió mal");
    }
  }, 2000);
});

miPromesa
  .then(resultado => console.log(resultado)) // "¡Operación exitosa!"
  .catch(error => console.error(error))
  .finally(() => console.log("Proceso finalizado"));
```

**Métodos principales:**
- `.then(cb)` — Se ejecuta cuando la promesa se cumple
- `.catch(cb)` — Se ejecuta cuando la promesa es rechazada
- `.finally(cb)` — Se ejecuta siempre, sin importar el resultado

#### Encadenamiento de promesas

```javascript
obtenerUsuario(userId)
  .then(usuario => {
    console.log("Usuario:", usuario.nombre);
    return obtenerPedidos(usuario.id); // cada .then puede devolver otra promesa
  })
  .then(pedidos => {
    console.log("Pedidos:", pedidos.length);
    return obtenerDetalles(pedidos[0].id);
  })
  .then(detalles => {
    console.log("Detalles:", detalles);
  })
  .catch(error => {
    console.error("Error en algún punto:", error); // captura cualquier error
  });
```

### 3.4. async/await: sintaxis moderna

**async/await** es azúcar sintáctico sobre las promesas que hace que el código asíncrono *parezca* síncrono, siendo mucho más legible.

**Reglas básicas:**
- `async` se coloca antes de la declaración de una función
- `await` solo funciona dentro de funciones `async`
- `await` pausa la ejecución hasta que la promesa se resuelva

#### Comparación directa

**Con promesas:**
```javascript
function obtenerDatosUsuario(userId) {
  obtenerUsuario(userId)
    .then(usuario => obtenerPedidos(usuario.id))
    .then(pedidos => console.log("Pedidos:", pedidos))
    .catch(error => console.error("Error:", error));
}
```

**Con async/await (equivalente, mucho más legible):**
```javascript
async function obtenerDatosUsuario(userId) {
  try {
    const usuario = await obtenerUsuario(userId);
    const pedidos = await obtenerPedidos(usuario.id);
    console.log("Pedidos:", pedidos);
  } catch (error) {
    console.error("Error:", error);
  }
}
```

#### Ejemplo completo

```javascript
function esperarSegundos(segundos) {
  return new Promise(resolve => {
    setTimeout(() => resolve(`Esperé ${segundos} segundos`), segundos * 1000);
  });
}

async function demostracionAsync() {
  console.log("Inicio");
  
  const resultado1 = await esperarSegundos(1);
  console.log(resultado1); // "Esperé 1 segundos"
  
  const resultado2 = await esperarSegundos(2);
  console.log(resultado2); // "Esperé 2 segundos"
  
  console.log("Fin");
}

demostracionAsync();
```

### 3.5. Manejo de errores con try/catch

Con async/await usamos bloques **try/catch** para manejar errores, igual que con código síncrono.

```javascript
async function miFuncion() {
  try {
    const response = await fetch('https://api.ejemplo.com/datos');
    
    if (!response.ok) {
      throw new Error(`Error HTTP: ${response.status}`); // lanzar error manualmente
    }
    
    const json = await response.json();
    console.log(json);
  } catch (error) {
    // Captura errores de red Y el error lanzado manualmente
    console.error("Ocurrió un error:", error.message);
  } finally {
    console.log("Proceso finalizado (siempre se ejecuta)");
  }
}
```

#### Patrón completo: feedback al usuario

```javascript
async function cargarDatos() {
  const loadingEl = document.getElementById('loading');
  const errorEl = document.getElementById('error');
  
  try {
    loadingEl.style.display = 'block';
    errorEl.style.display = 'none';
    
    const response = await fetch(url);
    if (!response.ok) throw new Error(`Error ${response.status}`);
    
    const data = await response.json();
    mostrarDatos(data);
    
  } catch (error) {
    errorEl.textContent = "No se pudieron cargar los datos. Inténtalo de nuevo.";
    errorEl.style.display = 'block';
  } finally {
    loadingEl.style.display = 'none'; // siempre ocultar el spinner
  }
}
```

---

### 📣 Ejemplo final

[Asicronía: El problema](5.3A_asincronia_el_problema.md)

[Asicronía: La solución](5.3B_asincronia_la_solucion.md)


## 4. Consumo de APIs con Fetch

### 4.1. La API Fetch: sintaxis básica

**`fetch()`** es la forma moderna de hacer peticiones HTTP en JavaScript. Reemplaza al antiguo `XMLHttpRequest`.

```javascript
fetch(url, opciones)
  .then(response => response.json())
  .then(data => console.log(data))
  .catch(error => console.error(error));
```

**Características clave:**
- Retorna una Promesa
- Por defecto hace peticiones GET
- ⚠️ La promesa se *cumple* incluso con códigos 404 o 500 — hay que comprobarlo manualmente
- Solo se *rechaza* con errores de red

### 4.2. Peticiones GET: obtener datos

#### Ejemplo 1: GET simple con comprobación de estado

```javascript
async function obtenerUsuarios() {
  try {
    const response = await fetch('https://jsonplaceholder.typicode.com/users');
    
    if (!response.ok) {
      throw new Error(`Error HTTP: ${response.status}`);
    }
    
    const usuarios = await response.json();
    console.log(usuarios);
    return usuarios;
  } catch (error) {
    console.error('Error al obtener usuarios:', error);
  }
}
```

#### Ejemplo 2: GET con parámetros en la URL

```javascript
async function buscarPosts(userId) {
  const url = `https://jsonplaceholder.typicode.com/posts?userId=${userId}`;
  
  try {
    const response = await fetch(url);
    if (!response.ok) throw new Error(`Error: ${response.status}`);
    
    const posts = await response.json();
    console.log(`Posts del usuario ${userId}:`, posts);
    return posts;
  } catch (error) {
    console.error(error);
  }
}
```


::: info **REST** **⚠️ IMPORTANTE - Atención a los datos que pasamos por la URL**
En ocasiones los valores que enviamos a través de la URL pueden contener acentos, caracteres en blanco, etc. En estos casos hay que utilizar la función nativa de Javascript `encodeURIComponent()`:

```javascript
const nombreSeguro = encodeURIComponent(nombre);
const url = `https://jsonplaceholder.typicode.com/posts?nombreUsuario=${nombreSeguro}`;
```
:::

#### Ejemplo 3: Múltiples parámetros con URLSearchParams

```javascript
async function buscarAvanzado(params) {
  const queryParams = new URLSearchParams({
    userId: params.userId,
    _limit: params.limite || 10,
    _sort: params.ordenar || 'id'
  });
  
  const url = `https://jsonplaceholder.typicode.com/posts?${queryParams}`;
  
  const response = await fetch(url);
  if (!response.ok) throw new Error(`Error: ${response.status}`);
  return response.json();
}

buscarAvanzado({ userId: 1, limite: 5, ordenar: 'title' });
```

#### Ejemplo 4: Mostrar datos en el DOM

```javascript
async function mostrarUsuarios() {
  const contenedor = document.getElementById('usuarios');
  contenedor.innerHTML = '<p>Cargando...</p>';
  
  try {
    const response = await fetch('https://jsonplaceholder.typicode.com/users');
    if (!response.ok) throw new Error(`Error: ${response.status}`);
    
    const usuarios = await response.json();
    contenedor.innerHTML = '';
    
    usuarios.forEach(usuario => {
      const div = document.createElement('div');
      div.className = 'usuario-card';
      div.innerHTML = `
        <h3>${usuario.name}</h3>
        <p>Email: ${usuario.email}</p>
        <p>Ciudad: ${usuario.address.city}</p>
      `;
      contenedor.appendChild(div);
    });
  } catch (error) {
    contenedor.innerHTML = '<p class="error">Error al cargar usuarios</p>';
    console.error(error);
  }
}

document.addEventListener('DOMContentLoaded', mostrarUsuarios);
```

### 4.3. Peticiones POST, PUT, PATCH y DELETE

Para modificar datos en el servidor, especificamos el método y enviamos datos en el cuerpo de la petición.

#### POST — Crear un nuevo recurso

```javascript
async function crearPost(titulo, contenido, userId) {
  const response = await fetch('https://jsonplaceholder.typicode.com/posts', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({
      title: titulo,
      body: contenido,
      userId: userId
    })
  });
  
  if (!response.ok) throw new Error(`Error: ${response.status}`);
  
  const nuevoPost = await response.json();
  console.log('Post creado con ID:', nuevoPost.id);
  return nuevoPost;
}
```

#### PUT — Reemplazar un recurso completo

```javascript
async function actualizarPost(postId, datosCompletos) {
  const response = await fetch(`https://jsonplaceholder.typicode.com/posts/${postId}`, {
    method: 'PUT',                                 // reemplaza TODO el recurso
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(datosCompletos)
  });
  
  if (!response.ok) throw new Error(`Error: ${response.status}`);
  return response.json();
}
```

#### PATCH — Actualizar solo algunos campos

```javascript
async function actualizarTitulo(postId, nuevoTitulo) {
  const response = await fetch(`https://jsonplaceholder.typicode.com/posts/${postId}`, {
    method: 'PATCH',                               // modifica solo los campos enviados
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ title: nuevoTitulo })
  });
  
  if (!response.ok) throw new Error(`Error: ${response.status}`);
  return response.json();
}
```

#### DELETE — Eliminar un recurso

```javascript
async function eliminarPost(postId) {
  const response = await fetch(`https://jsonplaceholder.typicode.com/posts/${postId}`, {
    method: 'DELETE'
  });
  
  if (!response.ok) throw new Error(`Error: ${response.status}`);
  console.log('Post eliminado correctamente');
  return true;
}
```

### 4.4. Headers y autenticación

Los **headers** son metadatos que enviamos con la petición.

#### Headers comunes

| Header | Propósito | Ejemplo de valor |
|--------|-----------|-----------------|
| `Content-Type` | Tipo de datos que enviamos | `application/json` |
| `Accept` | Tipo de datos que aceptamos | `application/json` |
| `Authorization` | Credenciales de autenticación | `Bearer mi_token` |

#### API Key en la URL (patrón más común)

```javascript
const API_KEY = 'tu_api_key';
const url = `https://api.openweathermap.org/data/2.5/weather?q=Madrid&appid=${API_KEY}&units=metric&lang=es`;

const response = await fetch(url);
```

#### API Key en header

```javascript
const response = await fetch('https://api.ejemplo.com/datos', {
  headers: {
    'X-API-Key': 'tu_api_key',
    'Accept': 'application/json'
  }
});
```

#### Bearer Token (OAuth, JWT)

```javascript
const TOKEN = 'tu_token_jwt';

const response = await fetch('https://api.ejemplo.com/privado', {
  headers: {
    'Authorization': `Bearer ${TOKEN}`,
    'Content-Type': 'application/json'
  }
});

if (response.status === 401) {
  throw new Error('Token inválido o expirado — vuelve a iniciar sesión');
}
```

::: tip **⚠️ IMPORTANTE — Seguridad de API Keys:**
 - ❌ NUNCA subas tu API key a GitHub o repositorios públicos
 - ✅ Para proyectos de clase, usa APIs gratuitas con keys de prueba
 - ✅ Guarda las keys en un archivo `config.js` excluido del repositorio (`.gitignore`)
:::

### 4.5. CORS: qué es y cómo afecta

**CORS** (Cross-Origin Resource Sharing) es un mecanismo de seguridad del navegador que bloquea peticiones a dominios diferentes al de tu página.

**Un "origen"** = protocolo + dominio + puerto

```
✅ Mismo origen:     https://ejemplo.com/a   →  https://ejemplo.com/b
❌ Origen diferente: https://ejemplo.com     →  https://api.ejemplo.com   (subdominio)
❌ Origen diferente: http://ejemplo.com      →  https://ejemplo.com       (protocolo)
❌ Origen diferente: ejemplo.com:3000        →  ejemplo.com:8080          (puerto)
```

**Error típico de CORS:**
```
Access to fetch at 'https://api.ejemplo.com' from origin 
'http://localhost:3000' has been blocked by CORS policy.
```

#### Soluciones

| Solución | Cuándo usarla |
|----------|---------------|
| La API permite CORS (ideal) | Mayoría de APIs públicas ya lo hacen |
| Proxy CORS | Solo para desarrollo/pruebas |
| Backend propio de intermediario | En producción |

**APIs sin problemas de CORS (ideales para aprender):**
- ✅ JSONPlaceholder — `https://jsonplaceholder.typicode.com`
- ✅ REST Countries — `https://restcountries.com`
- ✅ PokeAPI — `https://pokeapi.co`
- ✅ The Movie Database (TMDB)
- ✅ OpenWeatherMap

### 4.6. Rate limiting y buenas prácticas

**Rate limiting** = límite en el número de peticiones que puedes hacer a una API en un período de tiempo (p.ej. 1000/día, 10/segundo).

#### Caché de resultados

```javascript
const cache = new Map();

async function obtenerConCache(url) {
  if (cache.has(url)) {
    console.log('Usando cache para:', url);
    return cache.get(url);
  }
  
  const response = await fetch(url);
  const data = await response.json();
  cache.set(url, data);
  return data;
}
```

#### Debounce en buscadores (evitar peticiones innecesarias)

```javascript
let searchTimeout;

document.getElementById('busqueda').addEventListener('input', (e) => {
  clearTimeout(searchTimeout);
  // Solo hace la petición si el usuario para de escribir 500ms
  searchTimeout = setTimeout(() => {
    buscar(e.target.value);
  }, 500);
});
```

#### Cancelar peticiones obsoletas con AbortController

Este patrón es fundamental en el desarrollo moderno de interfaces (como en buscadores con autocompletado). Sirve para evitar una **condición de carrera** (*race condition*): cuando una petición vieja tarda más que una nueva y sobreescribe los datos correctos.

Cómo funciona paso a paso:

**1. El Director de Orquesta: `AbortController`**

Imagina que el `AbortController` es un interruptor. Este objeto tiene dos partes clave:

* **`controlador.signal`**: Es una "antena" que le pasas a la función `fetch`. El fetch se queda escuchando esa señal.
* **`controlador.abort()`**: Es el botón que, al presionarlo, envía una señal de "¡Detente!" a todos los fetch que tengan esa antena.


**2. El flujo de ejecución**

1. **Limpieza Previa:** Al inicio, el código revisa si ya existe un `controlador` de una búsqueda anterior. Si existe, llama a `.abort()`. Esto corta inmediatamente la conexión de red de la petición anterior.
2. **Nueva Identidad:** Se crea un `new AbortController()` para la búsqueda actual y se guarda en la variable global/superior `controlador`.
3. **Vinculación:** En el `fetch`, pasamos la propiedad `{ signal: controlador.signal }`. Ahora ese fetch está "bajo el control" de nuestro interruptor.
4. **Gestión del "Error":** Cuando abortas un fetch, JavaScript lo lanza hacia el `catch`. Pero no es un error real de servidor, es una cancelación manual. Por eso el código pregunta: `if (error.name === 'AbortError')`.


**¿Por qué es tan útil?**

Sin este código, si un usuario escribe muy rápido la palabra **"HOLA"**, se dispararían 4 peticiones:

1. `buscar?q=H`
2. `buscar?q=HO`
3. `buscar?q=HOL`
4. `buscar?q=HOLA`

Si por un problema de red la petición de **"H"** tarda 5 segundos y la de **"HOLA"** tarda 1 segundo, el usuario vería primero los resultados de "HOLA" y, de repente, la pantalla cambiaría a los resultados de "H". **Con este código, esto es imposible**, porque al dispararse la segunda, la primera muere.


```javascript
let controlador = null;

async function buscarConCancelacion(termino) {
  // Cancelar búsqueda anterior si sigue en curso
  if (controlador) controlador.abort();
  controlador = new AbortController();
  
  try {
    const response = await fetch(`https://api.ejemplo.com/buscar?q=${termino}`, {
      signal: controlador.signal
    });
    datos = await response.json();
    controlador = null;
    return datos;

  } catch (error) {
    if (error.name === 'AbortError') {
      console.log('Búsqueda cancelada');
    } else {
      throw error;
    }
  }
}
```

#### Peticiones paralelas con Promise.all

```javascript
async function cargarDatosDashboard() {
  try {
    // Lanzar todas las peticiones a la vez (no en serie)
    const [usuarios, productos, pedidos] = await Promise.all([
      fetch('/api/usuarios').then(r => r.json()),
      fetch('/api/productos').then(r => r.json()),
      fetch('/api/pedidos').then(r => r.json())
    ]);
    
    console.log('Todo cargado:', { usuarios, productos, pedidos });
  } catch (error) {
    console.error('Error en una o más peticiones:', error);
  }
}
```

---

### 📣 Ejemplo final

[Consumo de APIS Fetch: PokéApi](5.4_consumo_apis_fetch.md)



## 5. Trabajo con Datos JSON

### 5.1. Procesamiento de respuestas JSON

#### Conversión JSON ↔ JavaScript

```javascript
// String JSON → Objeto JavaScript
const jsonString = '{"nombre": "Ana", "edad": 25}';
const objeto = JSON.parse(jsonString);
console.log(objeto.nombre); // "Ana"

// Objeto JavaScript → String JSON
const persona = { nombre: "Carlos", edad: 30 };
const json = JSON.stringify(persona);
// '{"nombre":"Carlos","edad":30}'

// Con formato legible
const jsonFormateado = JSON.stringify(persona, null, 2);
// {
//   "nombre": "Carlos",
//   "edad": 30
// }
```

#### Acceder a datos anidados

```javascript
const pelicula = {
  titulo: "Inception",
  director: {
    nombre: "Christopher Nolan",
    nacionalidad: "Británico"
  },
  actores: [
    { nombre: "Leonardo DiCaprio", papel: "Dom Cobb" },
    { nombre: "Ellen Page", papel: "Ariadne" }
  ],
  puntuacion: { imdb: 8.8, rottenTomatoes: 87 }
};

// Acceso estándar
console.log(pelicula.director.nombre);   // "Christopher Nolan"
console.log(pelicula.actores[0].nombre); // "Leonardo DiCaprio"

// Optional chaining (?.) — evita errores si el campo no existe
console.log(pelicula.premios?.oscar);    // undefined (sin error)
console.log(pelicula.director?.nombre);  // "Christopher Nolan"
```

#### Destructuring para extraer datos

```javascript
const usuario = { id: 1, nombre: "Ana García", email: "ana@example.com", ciudad: "Madrid" };

// Extraer campos directamente
const { nombre, email } = usuario;
console.log(nombre); // "Ana García"

// Con alias
const { nombre: nombreUsuario, email: correo } = usuario;

// En parámetros de función (muy usado con datos de API)
function mostrarUsuario({ nombre, email, ciudad }) {
  console.log(`${nombre} | ${email} | ${ciudad}`);
}
mostrarUsuario(usuario);

// Destructuring de arrays
const [primera, segunda, ...resto] = ["Inception", "Interstellar", "The Prestige", "Dunkirk"];
console.log(primera);  // "Inception"
console.log(resto);    // ["The Prestige", "Dunkirk"]
```

### 5.2. Transformación de datos: map, filter, reduce

#### map() — Transformar cada elemento

`map` es un método de los arrays que **recorre cada elemento y devuelve un nuevo array** con el resultado de aplicar una función a cada uno. El array original no se modifica nunca.

La estructura básica es:

```js
const nuevoArray = array.map(elemento => transformación);
```

Sus principales usos son transformar datos (cambiar la forma o el valor de cada elemento), extraer una propiedad concreta de un array de objetos, y preparar datos para mostrarlos en el DOM o adaptarlos a otro formato.

Dos características clave a recordar:
- Siempre devuelve un array **del mismo tamaño** que el original.
- Si no devuelves nada en la función, obtienes un array lleno de `undefined`. Siempre hay que retornar algo.



##### Ejemplos

**Sencillo — transformar valores numéricos**

```js
const precios = [10, 20, 30, 40];

const preciosConIva = precios.map(precio => precio * 1.21);

console.log(preciosConIva); // [12.1, 24.2, 36.3, 48.4]
```



**Intermedio — extraer propiedades de objetos**

Tienes un array de usuarios y solo necesitas sus nombres para mostrarlos en una lista:

```js
const usuarios = [
    { id: 1, nombre: 'Ana', email: 'ana@email.com' },
    { id: 2, nombre: 'Carlos', email: 'carlos@email.com' },
    { id: 3, nombre: 'Lucía', email: 'lucia@email.com' }
];

const nombres = usuarios.map(usuario => usuario.nombre);

console.log(nombres); // ['Ana', 'Carlos', 'Lucía']
```

```javascript
const peliculas = [
  { id: 1, titulo: "Inception",     año: 2010, puntuacion: 8.8 },
  { id: 2, titulo: "Interstellar",  año: 2014, puntuacion: 8.6 },
  { id: 3, titulo: "The Prestige",  año: 2006, puntuacion: 8.5 }
];

// Extraer solo los títulos
const titulos = peliculas.map(p => p.titulo);
// ["Inception", "Interstellar", "The Prestige"]

// Crear una nueva estructura
const resumen = peliculas.map(p => ({
  nombre: p.titulo,
  info: `${p.año} · ⭐${p.puntuacion}`
}));
// [{ nombre: "Inception", info: "2010 · ⭐8.8" }, ...]
```


**Avanzado — adaptar respuesta de una API para el DOM**

Recibes datos de una API de películas y necesitas transformarlos en tarjetas HTML listas para insertar en la página:

```js
const peliculas = [
    { title: "Inception",    poster_path: "/abc.jpg", vote_average: 8.8 },
    { title: "Interstellar", poster_path: "/def.jpg", vote_average: 8.6 },
    { title: "The Prestige", poster_path: "/ghi.jpg", vote_average: 8.5 }
];

const BASE_IMG = 'https://image.tmdb.org/t/p/w500';

const tarjetas = peliculas.map(pelicula => `
    <div class="card">
        <img src="${BASE_IMG}${pelicula.poster_path}" alt="${pelicula.title}">
        <h3>${pelicula.title}</h3>
        <p>⭐ ${pelicula.vote_average}</p>
    </div>
`);

document.getElementById('contenedor').innerHTML = tarjetas.join('');
```

Aquí `map` convierte cada objeto de la API en un string HTML, y luego `join('')` une todo el array en un único string para insertarlo en el DOM de una sola vez.

#### filter() — Filtrar elementos

`filter` es un método de los arrays que **recorre cada elemento y devuelve un nuevo array solo con los elementos que cumplen una condición**. Al igual que `map`, el array original no se modifica.

La estructura básica es:

```js
const nuevoArray = array.filter(elemento => condición);
```

La función que le pasas debe devolver `true` o `false`. Si devuelve `true`, el elemento se incluye en el nuevo array; si devuelve `false`, se descarta.

Sus principales usos son filtrar listas según criterios del usuario (búsquedas, categorías), eliminar elementos no válidos o nulos de un array, y combinarlos con `map` para primero filtrar y luego transformar.

Dos características clave a recordar:
- El array resultante puede tener **menos elementos** que el original, o incluso estar vacío.
- A diferencia de `map`, no transforma los elementos, solo decide si los incluye o no.



##### Ejemplos

**Sencillo — filtrar números**

```js
const numeros = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];

const pares = numeros.filter(numero => numero % 2 === 0);

console.log(pares); // [2, 4, 6, 8, 10]
```


**Intermedio — filtrar objetos por propiedad**

Tienes un catálogo de productos y el usuario solo quiere ver los que están en stock:

```js
const productos = [
    { nombre: 'Camiseta', precio: 20, enStock: true },
    { nombre: 'Pantalón', precio: 45, enStock: false },
    { nombre: 'Zapatillas', precio: 80, enStock: true },
    { nombre: 'Gorra', precio: 15, enStock: false }
];

const disponibles = productos.filter(producto => producto.enStock);

console.log(disponibles);
// [
//   { nombre: 'Camiseta', precio: 20, enStock: true },
//   { nombre: 'Zapatillas', precio: 80, enStock: true }
// ]
```

```javascript
// Películas con puntuación >= 8.7
const peliculasTop = peliculas.filter(p => p.puntuacion >= 8.7);

// Películas después de 2010
const recientes = peliculas.filter(p => p.año > 2010);

// Combinar filtros
const topRecientes = peliculas
  .filter(p => p.año > 2010)
  .filter(p => p.puntuacion > 8.5);
```



**Avanzado — buscador en tiempo real combinando filter y map**

El usuario escribe en un input y la lista se filtra y renderiza dinámicamente:

```js
const peliculas = [
    { titulo: "Inception",    año: 2010, puntuacion: 8.8 },
    { titulo: "Interstellar", año: 2014, puntuacion: 8.6 },
    { titulo: "The Prestige", año: 2006, puntuacion: 8.5 },
    { titulo: "Dunkirk",      año: 2017, puntuacion: 7.9 }
];

function renderPeliculas(termino) {
    const resultado = peliculas
        .filter(p => p.titulo.toLowerCase().includes(termino.toLowerCase()))
        .map(p => `<li>${p.titulo} (${p.año}) — ⭐ ${p.puntuacion}</li>`);

    document.getElementById('lista').innerHTML = resultado.length
        ? resultado.join('')
        : '<li>No se encontraron resultados</li>';
}

document.getElementById('buscador').addEventListener('input', e => {
    renderPeliculas(e.target.value);
});
```

Aquí `filter` selecciona solo las películas cuyo título contiene el texto buscado, y `map` convierte cada una en un elemento `<li>` listo para el DOM. Es el patrón más habitual en buscadores reales.

#### reduce() — Reducir a un valor

`reduce` es el más potente pero también el más difícil de entender al principio. La idea central es: **recorre el array y va acumulando un resultado**, elemento a elemento, hasta obtener un único valor final.

Ese valor final puede ser cualquier cosa: un número, un string, un objeto, un array...

La estructura básica es:

```js
const resultado = array.reduce((acumulador, elemento) => {
    return /* nuevo valor del acumulador */;
}, valorInicial);
```

Los dos parámetros clave:
- **`acumulador`**: el resultado que se va construyendo en cada iteración.
- **`elemento`**: el elemento actual del array que se está procesando.
- **`valorInicial`**: el valor con el que empieza el acumulador (después de la función).


Antes de los ejemplos, es importante visualizar qué ocurre internamente. Con este array sencillo:

```js
const numeros = [1, 2, 3, 4];
const suma = numeros.reduce((acumulador, elemento) => acumulador + elemento, 0);
```

Así evoluciona el acumulador en cada paso:

| Iteración | Acumulador | Elemento | Resultado |
|-----------|-----------|---------|-----------|
| 1ª | 0 | 1 | 1 |
| 2ª | 1 | 2 | 3 |
| 3ª | 3 | 3 | 6 |
| 4ª | 6 | 4 | **10** |

El `0` es el valor inicial, y en cada paso el acumulador recibe el resultado anterior.


##### Ejemplos

**Sencillo — sumar valores de un array**

```js
const precios = [20, 45, 80, 15];

const total = precios.reduce((acumulador, precio) => acumulador + precio, 0);

console.log(total); // 160
```


**Intermedio — calcular el precio total de un carrito**

```js
const carrito = [
    { producto: 'Camiseta',  precio: 20, cantidad: 2 },
    { producto: 'Pantalón',  precio: 45, cantidad: 1 },
    { producto: 'Zapatillas', precio: 80, cantidad: 1 }
];

const total = carrito.reduce((acumulador, item) => {
    return acumulador + (item.precio * item.cantidad);
}, 0);

console.log(total); // 165
```

En cada iteración multiplica precio por cantidad y lo suma al acumulador.


**Avanzado — agrupar películas por década**

Aquí el acumulador ya no es un número sino un objeto, y vamos construyéndolo poco a poco:

```js
const peliculas = [
    { titulo: "Alien",        año: 1979 },
    { titulo: "Blade Runner", año: 1982 },
    { titulo: "Inception",    año: 2010 },
    { titulo: "The Prestige", año: 2006 },
    { titulo: "Interstellar", año: 2014 }
];

const porDecada = peliculas.reduce((acumulador, pelicula) => {
    const decada = Math.floor(pelicula.año / 10) * 10 + 's';

    if (!acumulador[decada]) {
        acumulador[decada] = [];
    }

    acumulador[decada].push(pelicula.titulo);
    return acumulador;
}, {});

console.log(porDecada);
// {
//   '1970s': ['Alien'],
//   '1980s': ['Blade Runner'],
//   '2000s': ['The Prestige'],
//   '2010s': ['Inception', 'Interstellar']
// }
```

El acumulador empieza como un objeto vacío `{}`, y en cada iteración comprueba si ya existe la clave de esa década. Si no existe, la crea con un array vacío, y luego añade el título.



#### Encadenar métodos

```javascript
// Títulos de películas con puntuación > 8.6, ordenadas por año
const resultado = peliculas
  .filter(p => p.puntuacion > 8.6)
  .sort((a, b) => a.año - b.año)
  .map(p => `${p.titulo} (${p.año})`);

console.log(resultado);
// ["Inception (2010)", "Interstellar (2014)"]
```

### 5.3. Adaptación de estructuras JSON

A menudo los datos de la API tienen una estructura diferente a la que necesita nuestra aplicación. Es buena práctica crear funciones adaptadoras.

#### Ejemplo: Adaptar respuesta de TMDB

```javascript
// Respuesta cruda de la API
const respuestaAPI = {
  page: 1,
  results: [
    {
      id: 27205,
      title: "Inception",
      release_date: "2010-07-16",
      vote_average: 8.8,
      poster_path: "/9gk7adHYeDvHkCSEqAvQNLV5Uge.jpg",
      overview: "Dom Cobb es un ladrón..."
    }
  ]
};

// Función adaptadora: convierte al formato de nuestra aplicación
function adaptarRespuestaTMDB(respuesta) {
  return {
    peliculas: respuesta.results.map(p => ({
      id: p.id,
      titulo: p.title,                                          // renombrar campo
      año: new Date(p.release_date).getFullYear(),              // transformar valor
      puntuacion: p.vote_average,
      imagen: `https://image.tmdb.org/t/p/w500${p.poster_path}`, // construir URL
      descripcion: p.overview,
      // Campos calculados que la API no devuelve
      esClasico: new Date().getFullYear() - new Date(p.release_date).getFullYear() > 20
    })),
    pagina: respuesta.page
  };
}

const datos = adaptarRespuestaTMDB(respuestaAPI);
console.log(datos.peliculas[0].titulo); // "Inception"
console.log(datos.peliculas[0].esClasico); // true
```

### 5.4. Validación básica de datos

```javascript
function validarPelicula(p) {
  const errores = [];
  
  if (!p.id || typeof p.id !== 'number')           errores.push('ID inválido');
  if (!p.titulo || typeof p.titulo !== 'string')   errores.push('Título inválido');
  if (!p.año || p.año < 1888 || p.año > 2100)     errores.push('Año inválido');
  if (p.puntuacion < 0 || p.puntuacion > 10)      errores.push('Puntuación fuera de rango');
  
  return { esValido: errores.length === 0, errores };
}

// Aplicar valores por defecto
function sanitizarPelicula(p) {
  return {
    id:          p.id         || 0,
    titulo:      p.titulo     || 'Sin título',
    año:         p.año        || new Date().getFullYear(),
    puntuacion:  typeof p.puntuacion === 'number' ? p.puntuacion : 0,
    descripcion: p.descripcion || 'Sin descripción',
    imagen:      p.imagen      || '/img/placeholder.jpg',
    actores:     Array.isArray(p.actores) ? p.actores : []
  };
}

// Uso en la llamada a la API
async function obtenerPeliculasSeguro(url) {
  const response = await fetch(url);
  if (!response.ok) throw new Error(`HTTP ${response.status}`);
  
  const data = await response.json();
  
  return data.results
    .map(sanitizarPelicula)
    .filter(p => {
      const { esValido, errores } = validarPelicula(p);
      if (!esValido) console.warn('Película ignorada:', errores);
      return esValido;
    });
}
```



### 📣 Ejemplo final

[ Rick and Morty API](5.5_rick_morty.md)



## 6. Herramientas de Desarrollo

### 6.1. Postman/Insomnia: testing de APIs

**Postman** e **Insomnia** permiten probar APIs sin necesidad de escribir código. Son esenciales para:

- 🧪 Explorar endpoints antes de integrarlos
- 🔍 Inspeccionar respuestas
- 💾 Guardar colecciones de peticiones
- 🔑 Gestionar variables de entorno (API keys, tokens, URLs base)

#### Flujo de trabajo recomendado

```
1. Obtener API Key y configurarla como variable de entorno
2. Explorar la documentación de la API
3. Crear peticiones en Postman y probarlas
4. Una vez que funciona, trasladar el código a tu aplicación
```

#### Pasos básicos en Postman

```
1. Nueva petición:
   Método: GET
   URL:    https://api.themoviedb.org/3/movie/popular?api_key={{API_KEY}}&language=es-ES

2. Variables de entorno:
   API_KEY = tu_clave_aqui
   BASE_URL = https://api.themoviedb.org/3

3. Ejecutar y revisar:
   - Código de estado esperado: 200 OK
   - Pestaña "Body" → ver JSON
   - Pestaña "Headers" → ver headers de respuesta
```

### 6.2. DevTools del navegador: Network y Console

#### Pestaña Network (Red)

Muestra todas las peticiones HTTP que hace tu página.

**Cómo usarla:**
1. Abrir DevTools: `F12` o click derecho → "Inspeccionar"
2. Ir a la pestaña **Network** o **Red**
3. Filtrar por `Fetch/XHR` para ver solo peticiones a APIs
4. Hacer clic en una petición para ver:
   - **Headers:** cabeceras enviadas y recibidas
   - **Preview:** JSON formateado
   - **Response:** respuesta en crudo
   - **Timing:** tiempo de cada fase

#### Pestaña Console

```javascript
// Herramientas de depuración más útiles

// Ver objeto completo de forma expandible
console.log('Datos recibidos:', data);

// Ver array como tabla
console.table(peliculas);

// Medir tiempo de una operación
console.time('carga-peliculas');
const datos = await cargarPeliculas();
console.timeEnd('carga-peliculas'); // "carga-peliculas: 342ms"

// Agrupar logs relacionados
console.group('Petición a la API');
console.log('URL:', url);
console.log('Status:', response.status);
console.log('Datos:', data);
console.groupEnd();

// Probar fetch directamente en la consola
fetch('https://jsonplaceholder.typicode.com/users/1')
  .then(r => r.json())
  .then(console.log);
```


---

### 📣 Ejemplo final

[ Herramientas](5.6_herramientas.md)

## 7. Proyectos Prácticos

Los tres proyectos son **independientes** pero aumentan progresivamente en complejidad. Cada uno trabaja con una API pública diferente y aplica todos los conceptos de la unidad.


## 🎬 Proyecto 1: Buscador de Películas

**API:** The Movie Database (TMDB) — `https://www.themoviedb.org/`  
Descripción

Aplicación web para buscar películas y ver las más populares. El usuario puede escribir en un buscador y ver resultados en tiempo real.

### Funcionalidades

- ✅ Mostrar películas populares al cargar la página
- ✅ Buscador con debounce (no hace petición en cada tecla)
- ✅ Tarjetas con póster, título, puntuación y descripción
- ✅ Indicador de carga y manejo de errores

### Estructura del proyecto

```
proyecto-1-peliculas/
├── index.html
├── style.css
└── js/
    ├── config.js   ← API Key y URLs base
    ├── api.js      ← Funciones de petición
    └── app.js      ← Lógica de la aplicación y DOM
```

### Código del proyecto

**`index.html`**
```html
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscador de Películas</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>🎬 Buscador de Películas</h1>
        <input type="text" id="searchInput" placeholder="Buscar películas...">
    </header>

    <main>
        <div id="loading" class="loading hidden">
            <div class="spinner"></div>
            <p>Cargando películas...</p>
        </div>

        <div id="error" class="error hidden">
            <p id="errorMessage"></p>
            <button id="retryBtn">🔄 Reintentar</button>
        </div>

        <div id="movies" class="movies-grid"></div>
    </main>

    <script type="module" src="js/app.js"></script>
</body>
</html>
```

**`style.css`**
```css
* { margin: 0; padding: 0; box-sizing: border-box; }

body {
    font-family: 'Segoe UI', sans-serif;
    background: linear-gradient(135deg, #1a1a2e, #16213e, #0f3460);
    min-height: 100vh;
    color: white;
    padding: 20px;
}

header {
    text-align: center;
    margin-bottom: 40px;
}

header h1 { font-size: 2.5rem; margin-bottom: 20px; }

#searchInput {
    width: 100%;
    max-width: 600px;
    padding: 15px 25px;
    font-size: 1rem;
    border: none;
    border-radius: 50px;
    background: rgba(255,255,255,0.1);
    color: white;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.2);
    outline: none;
    transition: all 0.3s;
}

#searchInput::placeholder { color: rgba(255,255,255,0.6); }
#searchInput:focus { background: rgba(255,255,255,0.2); border-color: rgba(255,255,255,0.5); }

.movies-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
    gap: 25px;
    max-width: 1200px;
    margin: 0 auto;
}

.movie-card {
    background: rgba(255,255,255,0.05);
    border-radius: 12px;
    overflow: hidden;
    cursor: pointer;
    transition: transform 0.3s, box-shadow 0.3s;
    border: 1px solid rgba(255,255,255,0.1);
}

.movie-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 30px rgba(0,0,0,0.5);
}

.movie-poster { width: 100%; height: 270px; object-fit: cover; display: block; }

.movie-info { padding: 12px; }

.movie-title { font-size: 0.9rem; font-weight: 600; margin-bottom: 8px; }

.movie-rating { color: #f39c12; font-size: 0.9rem; font-weight: bold; }

.loading, .error { text-align: center; padding: 60px 20px; }

.spinner {
    width: 50px; height: 50px;
    border: 4px solid rgba(255,255,255,0.2);
    border-top-color: white;
    border-radius: 50%;
    animation: spin 0.8s linear infinite;
    margin: 0 auto 20px;
}

@keyframes spin { to { transform: rotate(360deg); } }

.hidden { display: none; }

#retryBtn {
    margin-top: 15px;
    padding: 10px 25px;
    background: #e94560;
    color: white;
    border: none;
    border-radius: 25px;
    cursor: pointer;
    font-size: 1rem;
}
```

**`js/config.js`**
```javascript
// NOTA: En un proyecto real, esta key no estaría en el código fuente
// Obtén tu API key gratuita en: https://www.themoviedb.org/settings/api
export const API_KEY    = 'TU_API_KEY_AQUI';
export const BASE_URL   = 'https://api.themoviedb.org/3';
export const IMAGE_URL  = 'https://image.tmdb.org/t/p/w500';
export const NO_IMAGE   = 'https://placehold.co/500X750?text=Sin+imagen';
```

**`js/api.js`**
```javascript
import { API_KEY, BASE_URL, IMAGE_URL, NO_IMAGE } from './config.js';

function buildUrl(endpoint, extraParams = {}) {
    const params = new URLSearchParams({
        api_key: API_KEY,
        language: 'es-ES',
        ...extraParams
    });
    return `${BASE_URL}${endpoint}?${params}`;
}

export async function getPopularMovies() {
    const url = buildUrl('/movie/popular');
    const response = await fetch(url);
    if (!response.ok) throw new Error(`Error ${response.status}`);
    const data = await response.json();
    return data.results;
}

export async function searchMovies(query) {
    if (!query.trim()) return getPopularMovies();
    
    const url = buildUrl('/search/movie', { query });
    const response = await fetch(url);
    if (!response.ok) throw new Error(`Error ${response.status}`);
    const data = await response.json();
    return data.results;
}

export function getImageUrl(path) {
    return path ? `${IMAGE_URL}${path}` : NO_IMAGE;
}
```

**`js/app.js`**
```javascript
import { getPopularMovies, searchMovies, getImageUrl } from './api.js';

const moviesGrid   = document.getElementById('movies');
const searchInput  = document.getElementById('searchInput');
const loadingEl    = document.getElementById('loading');
const errorEl      = document.getElementById('error');
const errorMsgEl   = document.getElementById('errorMessage');
const retryBtn     = document.getElementById('retryBtn');

let searchTimeout = null;

// --- Utilidades UI ---
const show = el => el.classList.remove('hidden');
const hide = el => el.classList.add('hidden');

function showLoading() { show(loadingEl); hide(moviesGrid); hide(errorEl); }
function showError(msg) { errorMsgEl.textContent = msg; show(errorEl); hide(loadingEl); hide(moviesGrid); }
function showGrid() { show(moviesGrid); hide(loadingEl); hide(errorEl); }

// --- Renderizado ---
function createMovieCard(movie) {
    const card = document.createElement('div');
    card.className = 'movie-card';
    card.innerHTML = `
        <img
            src="${getImageUrl(movie.poster_path)}"
            alt="${movie.title}"
            class="movie-poster"
            loading="lazy"
        >
        <div class="movie-info">
            <p class="movie-title">${movie.title}</p>
            <p class="movie-rating">⭐ ${movie.vote_average?.toFixed(1) ?? 'N/D'}</p>
        </div>
    `;
    return card;
}

function renderMovies(movies) {
    moviesGrid.innerHTML = '';
    
    if (movies.length === 0) {
        moviesGrid.innerHTML = '<p style="grid-column:1/-1;text-align:center;opacity:0.6">No se encontraron películas</p>';
        showGrid();
        return;
    }
    
    movies.forEach(movie => moviesGrid.appendChild(createMovieCard(movie)));
    showGrid();
}

// --- Carga de datos ---
async function loadMovies(query = '') {
    showLoading();
    try {
        const movies = query ? await searchMovies(query) : await getPopularMovies();
        renderMovies(movies);
    } catch (err) {
        showError(err.message.includes('404')
            ? 'Película no encontrada'
            : 'Error al conectar con la API. Comprueba tu API key.');
    }
}

// --- Eventos ---
searchInput.addEventListener('input', e => {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => loadMovies(e.target.value), 500);
});

retryBtn.addEventListener('click', () => loadMovies(searchInput.value));

// --- Inicio ---
loadMovies();
```

### Tareas

1. Crear la estructura del proyecto y obtener API key de TMDB
2. Implementar `api.js` y probar las peticiones en consola
3. Mostrar las películas populares en la página
4. Implementar el buscador con debounce
5.  Añadir estados de carga y error 
6. **Ampliación:** Añadir un modal con detalles completos al hacer clic en una tarjeta

---

## 🌤️ Proyecto 2: Dashboard Meteorológico

**API:** OpenWeatherMap — `https://openweathermap.org/api`  

### Descripción

Dashboard que muestra el clima actual de cualquier ciudad y el pronóstico de los próximos 5 días. Incluye geolocalización y ciudades favoritas guardadas en `localStorage`.

### Funcionalidades

- ✅ Detectar ubicación del usuario automáticamente
- ✅ Buscador de ciudades
- ✅ Clima actual con icono, temperatura, humedad y viento
- ✅ Pronóstico de 5 días
- ✅ Conversión °C / °F
- ✅ Ciudades favoritas (localStorage)

### Endpoints de la API

```
# Clima actual por ciudad
GET https://api.openweathermap.org/data/2.5/weather
    ?q={ciudad}&appid={KEY}&units=metric&lang=es

# Clima actual por coordenadas GPS
GET https://api.openweathermap.org/data/2.5/weather
    ?lat={lat}&lon={lon}&appid={KEY}&units=metric&lang=es

# Pronóstico 5 días (cada 3 horas)
GET https://api.openweathermap.org/data/2.5/forecast
    ?q={ciudad}&appid={KEY}&units=metric&lang=es
```

### Estructura del proyecto

```
proyecto-2-clima/
├── index.html
├── style.css
└── js/
    ├── config.js     ← API Key
    ├── api.js        ← Peticiones y geolocalización
    ├── storage.js    ← Gestión de favoritos (localStorage)
    ├── ui.js         ← Funciones de renderizado y formato
    └── app.js        ← Lógica principal y eventos
```

### Código del proyecto

**`index.html`**
```html
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Meteorológico</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>🌤️ Dashboard Meteorológico</h1>
            <div class="controls">
                <input type="text" id="cityInput" placeholder="Buscar ciudad...">
                <button id="searchBtn">🔍</button>
                <button id="locationBtn">📍 Mi ubicación</button>
            </div>
        </header>

        <div id="loading" class="loading hidden">
            <div class="spinner"></div>
            <p>Obteniendo datos del clima...</p>
        </div>

        <div id="error" class="error hidden">
            <p id="errorMsg"></p>
            <button id="retryBtn">Reintentar</button>
        </div>

        <main id="dashboard" class="hidden">
            <!-- Clima actual -->
            <section class="current-weather card">
                <div class="location-info">
                    <h2 id="cityName"></h2>
                    <p id="currentDate" class="date"></p>
                </div>
                <div class="temp-main">
                    <img id="weatherIcon" src="" alt="">
                    <div>
                        <span id="currentTemp" class="big-temp"></span>
                        <button id="unitToggle" class="unit-btn">Cambiar a °F</button>
                    </div>
                    <p id="weatherDesc"></p>
                </div>
                <div class="details-grid">
                    <div>💧 Humedad: <strong id="humidity"></strong></div>
                    <div>🌬️ Viento: <strong id="wind"></strong></div>
                    <div>🌡️ Sensación: <strong id="feelsLike"></strong></div>
                    <div>📊 Presión: <strong id="pressure"></strong></div>
                </div>
                <button id="favBtn" class="fav-btn">☆ Añadir a favoritos</button>
            </section>

            <!-- Pronóstico -->
            <section class="card">
                <h3>📅 Pronóstico 5 días</h3>
                <div id="forecast" class="forecast-grid"></div>
            </section>

            <!-- Favoritos -->
            <section class="card">
                <h3>⭐ Ciudades favoritas</h3>
                <div id="favorites"></div>
            </section>
        </main>
    </div>
    <script type="module" src="js/app.js"></script>
</body>
</html>
```

**`js/config.js`**
```javascript
// Obtén tu API key gratuita en: https://openweathermap.org/api
export const API_KEY  = 'TU_API_KEY_AQUI';
export const BASE_URL = 'https://api.openweathermap.org/data/2.5';
export const ICON_URL = 'https://openweathermap.org/img/wn';
```

**`js/api.js`**
```javascript
import { API_KEY, BASE_URL, ICON_URL } from './config.js';

const commonParams = `&appid=${API_KEY}&units=metric&lang=es`;

export async function getWeatherByCity(city) {
    const response = await fetch(`${BASE_URL}/weather?q=${encodeURIComponent(city)}${commonParams}`);
    if (response.status === 404) throw new Error(`Ciudad "${city}" no encontrada`);
    if (!response.ok) throw new Error(`Error ${response.status}`);
    return response.json();
}

export async function getWeatherByCoords(lat, lon) {
    const response = await fetch(`${BASE_URL}/weather?lat=${lat}&lon=${lon}${commonParams}`);
    if (!response.ok) throw new Error(`Error ${response.status}`);
    return response.json();
}

export async function getForecast(city) {
    const response = await fetch(`${BASE_URL}/forecast?q=${encodeURIComponent(city)}${commonParams}`);
    if (!response.ok) throw new Error(`Error ${response.status}`);
    const data = await response.json();
    
    // Filtrar: quedarnos con un dato por día (el del mediodía)
    const daily = {};
    data.list.forEach(item => {
        const date = new Date(item.dt * 1000);
        const key  = date.toISOString().split('T')[0];
        if (date.getHours() === 12 && !daily[key]) daily[key] = item;
    });
    
    return Object.values(daily).slice(0, 5);
}

export function getIconUrl(code) {
    return `${ICON_URL}/${code}@2x.png`;
}

export function getUserLocation() {
    return new Promise((resolve, reject) => {
        if (!navigator.geolocation) {
            reject(new Error('Tu navegador no soporta geolocalización'));
            return;
        }
        navigator.geolocation.getCurrentPosition(
            pos => resolve({ lat: pos.coords.latitude, lon: pos.coords.longitude }),
            ()  => reject(new Error('No se pudo obtener tu ubicación. Comprueba los permisos.'))
        );
    });
}
```

**`js/storage.js`**
```javascript
const KEY = 'weather_favorites';

export const getFavorites  = ()         => JSON.parse(localStorage.getItem(KEY) || '[]');
export const isFavorite    = city       => getFavorites().includes(city);

export function toggleFavorite(city) {
    const favs = getFavorites();
    const idx  = favs.indexOf(city);
    if (idx === -1) favs.push(city);
    else favs.splice(idx, 1);
    localStorage.setItem(KEY, JSON.stringify(favs));
    return idx === -1; // true si se añadió, false si se eliminó
}
```

**`js/ui.js`**
```javascript
import { getIconUrl } from './api.js';

let isCelsius = true;
let currentData = null; // guardamos los datos para poder redibujar al cambiar unidad

export function storeCurrentData(data) { currentData = data; }
export function getCurrentData()      { return currentData; }
export function getIsCelsius()        { return isCelsius; }

export function toggleUnit() {
    isCelsius = !isCelsius;
    return isCelsius;
}

export function formatTemp(celsius) {
    if (isCelsius) return `${Math.round(celsius)} °C`;
    return `${Math.round(celsius * 9/5 + 32)} °F`;
}

export function formatDate(timestamp) {
    return new Date(timestamp * 1000).toLocaleDateString('es-ES', {
        weekday: 'long', day: 'numeric', month: 'long',
        hour: '2-digit', minute: '2-digit'
    });
}

export function formatDay(timestamp) {
    return new Date(timestamp * 1000).toLocaleDateString('es-ES', {
        weekday: 'short', day: 'numeric', month: 'short'
    });
}

export function renderCurrentWeather(data, isFav) {
    document.getElementById('cityName').textContent    = `${data.name}, ${data.sys.country}`;
    document.getElementById('currentDate').textContent = formatDate(data.dt);
    document.getElementById('weatherIcon').src         = getIconUrl(data.weather[0].icon);
    document.getElementById('weatherIcon').alt         = data.weather[0].description;
    document.getElementById('currentTemp').textContent = formatTemp(data.main.temp);
    document.getElementById('weatherDesc').textContent = data.weather[0].description;
    document.getElementById('feelsLike').textContent   = formatTemp(data.main.feels_like);
    document.getElementById('humidity').textContent    = `${data.main.humidity}%`;
    document.getElementById('wind').textContent        = `${data.wind.speed} m/s`;
    document.getElementById('pressure').textContent   = `${data.main.pressure} hPa`;

    const favBtn = document.getElementById('favBtn');
    favBtn.textContent = isFav ? '⭐ En favoritos' : '☆ Añadir a favoritos';
}

export function renderForecast(list) {
    const container = document.getElementById('forecast');
    container.innerHTML = list.map(day => `
        <div class="forecast-card">
            <p>${formatDay(day.dt)}</p>
            <img src="${getIconUrl(day.weather[0].icon)}" alt="${day.weather[0].description}">
            <p><strong>${formatTemp(day.main.temp)}</strong></p>
            <p class="forecast-desc">${day.weather[0].description}</p>
        </div>
    `).join('');
}

export function renderFavorites(favorites, onCityClick, onRemove) {
    const container = document.getElementById('favorites');
    if (favorites.length === 0) {
        container.innerHTML = '<p class="empty">Aún no hay ciudades favoritas</p>';
        return;
    }
    container.innerHTML = favorites.map(city => `
        <div class="fav-item">
            <span>${city}</span>
            <div>
                <button class="btn-ver"    data-city="${city}">Ver</button>
                <button class="btn-remove" data-city="${city}">✕</button>
            </div>
        </div>
    `).join('');

    container.querySelectorAll('.btn-ver').forEach(btn =>
        btn.addEventListener('click', () => onCityClick(btn.dataset.city)));
    container.querySelectorAll('.btn-remove').forEach(btn =>
        btn.addEventListener('click', () => onRemove(btn.dataset.city)));
}
```

**`js/app.js`**
```javascript
import { getWeatherByCity, getWeatherByCoords, getForecast, getUserLocation } from './api.js';
import { getFavorites, isFavorite, toggleFavorite } from './storage.js';
import {
    renderCurrentWeather, renderForecast, renderFavorites,
    toggleUnit, storeCurrentData, getCurrentData, getIsCelsius
} from './ui.js';

// Elementos del DOM
const cityInput   = document.getElementById('cityInput');
const searchBtn   = document.getElementById('searchBtn');
const locationBtn = document.getElementById('locationBtn');
const loadingEl   = document.getElementById('loading');
const errorEl     = document.getElementById('error');
const errorMsgEl  = document.getElementById('errorMsg');
const retryBtn    = document.getElementById('retryBtn');
const dashboard   = document.getElementById('dashboard');
const favBtn      = document.getElementById('favBtn');
const unitToggle  = document.getElementById('unitToggle');

let currentCity = '';
let lastForecast = [];

// --- Visibilidad ---
const show = el => el.classList.remove('hidden');
const hide = el => el.classList.add('hidden');

function setView(view) {
    hide(loadingEl); hide(errorEl); hide(dashboard);
    show(view);
}

// --- Carga principal ---
async function loadWeather(city) {
    setView(loadingEl);
    currentCity = city;
    cityInput.value = city;

    try {
        const [weather, forecast] = await Promise.all([
            getWeatherByCity(city),
            getForecast(city)
        ]);

        storeCurrentData(weather);
        lastForecast = forecast;

        renderCurrentWeather(weather, isFavorite(city));
        renderForecast(forecast);
        renderFavorites(getFavorites(), loadWeather, handleRemoveFavorite);
        setView(dashboard);
    } catch (err) {
        errorMsgEl.textContent = err.message;
        setView(errorEl);
    }
}

async function loadWeatherByLocation() {
    setView(loadingEl);
    try {
        const { lat, lon } = await getUserLocation();
        const weather = await getWeatherByCoords(lat, lon);
        loadWeather(weather.name); // una vez tenemos el nombre, cargamos normalmente
    } catch (err) {
        errorMsgEl.textContent = err.message;
        setView(errorEl);
    }
}

// --- Favoritos ---
function handleRemoveFavorite(city) {
    toggleFavorite(city);
    renderFavorites(getFavorites(), loadWeather, handleRemoveFavorite);
    if (city === currentCity) {
        renderCurrentWeather(getCurrentData(), false);
    }
}

// --- Eventos ---
searchBtn.addEventListener('click', () => {
    const city = cityInput.value.trim();
    if (city) loadWeather(city);
});

cityInput.addEventListener('keypress', e => {
    if (e.key === 'Enter') {
        const city = cityInput.value.trim();
        if (city) loadWeather(city);
    }
});

locationBtn.addEventListener('click', loadWeatherByLocation);
retryBtn.addEventListener('click', () => currentCity ? loadWeather(currentCity) : loadWeatherByLocation());

favBtn.addEventListener('click', () => {
    if (!currentCity) return;
    const added = toggleFavorite(currentCity);
    renderCurrentWeather(getCurrentData(), added);
    renderFavorites(getFavorites(), loadWeather, handleRemoveFavorite);
});

unitToggle.addEventListener('click', () => {
    const nowCelsius = toggleUnit();
    unitToggle.textContent = nowCelsius ? 'Cambiar a °F' : 'Cambiar a °C';
    if (getCurrentData()) {
        renderCurrentWeather(getCurrentData(), isFavorite(currentCity));
        renderForecast(lastForecast);
    }
});

// --- Inicio ---
loadWeatherByLocation().catch(() => loadWeather('Madrid'));
```

### Tareas

1. Crear el proyecto y obtener API key de OpenWeatherMap
2. Implementar la geolocalización y clima actual
3. Mostrar los detalles del tiempo en el DOM
4. Implementar el pronóstico de 5 días
5. Añadir la funcionalidad de favoritos con localStorage
6. **Ampliación:** Implementar la conversión °C/°F

---

## 🎨 Proyecto 3: Explorador de Museos y Arte

**API:** Metropolitan Museum of Art (gratuita, sin API Key) — `https://metmuseum.github.io/`  


### Descripción

Aplicación para explorar las obras de arte del Museo Metropolitano de Nueva York. Permite buscar obras, filtrar por departamento y ver los detalles de cada pieza.

### Por qué esta API es interesante

- ✅ **Sin API key** — cero configuración
- ✅ **Sin límite de peticiones** en uso razonable
- ✅ Datos ricos: imagen, título, artista, fecha, material, dimensiones...
- ✅ Más de 470.000 obras digitalizadas
- ✅ Excelente para practicar `Promise.all` y manejo de colecciones

### Endpoints principales

```
# Buscar obras por término
GET https://collectionapi.metmuseum.org/public/collection/v1/search
    ?q={termino}&hasImages=true

# Detalle de una obra
GET https://collectionapi.metmuseum.org/public/collection/v1/objects/{id}

# Listar departamentos
GET https://collectionapi.metmuseum.org/public/collection/v1/departments
```

### Nota sobre esta API

La API devuelve primero una lista de IDs, y después hay que hacer una petición por cada ID para obtener los detalles. Esto es una oportunidad perfecta para practicar `Promise.all`.

```javascript
// Patrón típico de esta API:
// 1. Buscar → obtenemos lista de IDs
const { objectIDs } = await search('monet');
// [436533, 437980, 436532, ...]

// 2. Limitar a los primeros 20 para no saturar la API
const idsToFetch = objectIDs.slice(0, 20);

// 3. Pedir detalles de todos en paralelo
const obras = await Promise.all(
    idsToFetch.map(id => getObject(id))
);
```

### Estructura del proyecto

```
proyecto-3-museos/
├── index.html
├── style.css
└── js/
    ├── api.js   ← Peticiones a la API del MET
    └── app.js   ← Lógica y renderizado
```

### Código del proyecto

**`index.html`**
```html
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Explorador del Metropolitan Museum</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <div class="header-content">
            <h1>🎨 Colección del MET</h1>
            <p>Metropolitan Museum of Art · Nueva York</p>
        </div>
        <div class="search-area">
            <input type="text" id="searchInput" placeholder="Buscar: Monet, Roman, Egyptian...">
            <button id="searchBtn">Buscar</button>
        </div>
    </header>

    <main>
        <div id="loading" class="loading hidden">
            <div class="spinner"></div>
            <p id="loadingMsg">Cargando obras...</p>
        </div>

        <div id="error" class="error hidden">
            <p id="errorMsg"></p>
            <button id="retryBtn">Reintentar</button>
        </div>

        <div id="info" class="info hidden">
            <p id="infoMsg"></p>
        </div>

        <div id="gallery" class="gallery hidden"></div>

        <!-- Modal para detalles de obra -->
        <div id="modal" class="modal hidden">
            <div class="modal-content">
                <button id="closeModal">✕</button>
                <div id="modalBody"></div>
            </div>
        </div>
    </main>

    <script type="module" src="js/app.js"></script>
</body>
</html>
```

**`style.css`**
```css
* { margin: 0; padding: 0; box-sizing: border-box; }

body {
    font-family: Georgia, 'Times New Roman', serif;
    background: #f5f0e8;
    color: #2c2c2c;
    min-height: 100vh;
}

header {
    background: #1a1a1a;
    color: white;
    padding: 30px 40px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 20px;
}

header h1 { font-size: 2rem; margin-bottom: 5px; }
header p  { opacity: 0.6; font-style: italic; }

.search-area { display: flex; gap: 10px; }

#searchInput {
    padding: 12px 20px;
    width: 320px;
    border: none;
    border-radius: 4px;
    font-size: 1rem;
    font-family: inherit;
}

#searchBtn {
    padding: 12px 25px;
    background: #c41e3a;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 1rem;
    font-weight: bold;
}

#searchBtn:hover { background: #a01730; }

main { padding: 40px; max-width: 1400px; margin: 0 auto; }

.info { text-align: center; padding: 20px; color: #666; font-style: italic; }

.gallery {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
    gap: 25px;
}

.artwork-card {
    background: white;
    border-radius: 4px;
    overflow: hidden;
    cursor: pointer;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transition: transform 0.2s, box-shadow 0.2s;
}

.artwork-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.15);
}

.artwork-img {
    width: 100%;
    height: 220px;
    object-fit: cover;
    background: #e8e0d0;
    display: block;
}

.artwork-info { padding: 15px; }
.artwork-title { font-size: 0.9rem; font-weight: bold; margin-bottom: 5px; }
.artwork-meta  { font-size: 0.8rem; color: #666; }

/* Modal */
.modal {
    position: fixed; inset: 0;
    background: rgba(0,0,0,0.8);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1000;
    padding: 20px;
}

.modal-content {
    background: white;
    border-radius: 8px;
    max-width: 900px;
    width: 100%;
    max-height: 90vh;
    overflow-y: auto;
    position: relative;
    padding: 40px;
}

#closeModal {
    position: absolute;
    top: 15px; right: 20px;
    background: none; border: none;
    font-size: 1.5rem;
    cursor: pointer;
    color: #666;
}

.modal-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 30px;
    align-items: start;
}

.modal-image { width: 100%; border-radius: 4px; }
.modal-title { font-size: 1.5rem; margin-bottom: 10px; }
.modal-detail { margin-bottom: 8px; font-size: 0.95rem; }
.modal-detail strong { color: #666; font-size: 0.85rem; display: block; margin-bottom: 2px; }
.modal-link { display: inline-block; margin-top: 15px; color: #c41e3a; }

.hidden { display: none; }

.loading, .error { text-align: center; padding: 60px; }

.spinner {
    width: 50px; height: 50px;
    border: 3px solid #e0d8c8;
    border-top-color: #c41e3a;
    border-radius: 50%;
    animation: spin 0.8s linear infinite;
    margin: 0 auto 20px;
}

@keyframes spin { to { transform: rotate(360deg); } }

#retryBtn {
    margin-top: 15px;
    padding: 10px 25px;
    background: #c41e3a;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

@media (max-width: 600px) {
    .modal-grid { grid-template-columns: 1fr; }
    header { flex-direction: column; }
    #searchInput { width: 100%; }
}
```

**`js/api.js`**
```javascript
const BASE = 'https://collectionapi.metmuseum.org/public/collection/v1';

export async function searchArtworks(query, limit = 20) {
    const url = `${BASE}/search?q=${encodeURIComponent(query)}&hasImages=true`;
    const response = await fetch(url);
    if (!response.ok) throw new Error(`Error ${response.status}`);
    
    const data = await response.json();
    if (!data.objectIDs || data.objectIDs.length === 0) return [];
    
    // Limitar el número de resultados para no saturar la API
    const ids = data.objectIDs.slice(0, limit);
    
    // Obtener detalles de todas las obras en paralelo
    const objects = await Promise.all(ids.map(id => getObject(id)));
    
    // Filtrar las que tienen imagen (algunas pueden fallar)
    return objects.filter(obj => obj && obj.primaryImageSmall);
}

export async function getObject(id) {
    try {
        const response = await fetch(`${BASE}/objects/${id}`);
        if (!response.ok) return null;
        return response.json();
    } catch {
        return null; // Si una obra falla, la ignoramos
    }
}

export async function getDepartments() {
    const response = await fetch(`${BASE}/departments`);
    if (!response.ok) throw new Error(`Error ${response.status}`);
    const data = await response.json();
    return data.departments;
}
```

**`js/app.js`**
```javascript
import { searchArtworks, getObject } from './api.js';

// Elementos
const searchInput = document.getElementById('searchInput');
const searchBtn   = document.getElementById('searchBtn');
const gallery     = document.getElementById('gallery');
const loadingEl   = document.getElementById('loading');
const loadingMsg  = document.getElementById('loadingMsg');
const errorEl     = document.getElementById('error');
const errorMsgEl  = document.getElementById('errorMsg');
const retryBtn    = document.getElementById('retryBtn');
const infoEl      = document.getElementById('info');
const infoMsgEl   = document.getElementById('infoMsg');
const modal       = document.getElementById('modal');
const modalBody   = document.getElementById('modalBody');
const closeModal  = document.getElementById('closeModal');

let lastQuery = '';

// --- Utilidades ---
const show = el => el.classList.remove('hidden');
const hide = el => el.classList.add('hidden');

// --- Renderizado ---
function renderGallery(artworks) {
    hide(loadingEl); hide(errorEl);
    gallery.innerHTML = '';

    if (artworks.length === 0) {
        infoMsgEl.textContent = 'No se encontraron obras para esta búsqueda';
        show(infoEl); hide(gallery);
        return;
    }

    infoMsgEl.textContent = `${artworks.length} obras encontradas`;
    show(infoEl); show(gallery);

    artworks.forEach(art => {
        const card = document.createElement('div');
        card.className = 'artwork-card';
        card.innerHTML = `
            <img
                class="artwork-img"
                src="${art.primaryImageSmall}"
                alt="${art.title}"
                loading="lazy"
                onerror="this.src='https://via.placeholder.com/220?text=Sin+imagen'"
            >
            <div class="artwork-info">
                <p class="artwork-title">${art.title || 'Sin título'}</p>
                <p class="artwork-meta">${art.artistDisplayName || 'Artista desconocido'}</p>
                <p class="artwork-meta">${art.objectDate || ''}</p>
            </div>
        `;
        card.addEventListener('click', () => openModal(art));
        gallery.appendChild(card);
    });
}

function openModal(art) {
    modalBody.innerHTML = `
        <div class="modal-grid">
            <img
                class="modal-image"
                src="${art.primaryImage || art.primaryImageSmall}"
                alt="${art.title}"
                onerror="this.src='https://via.placeholder.com/400?text=Sin+imagen'"
            >
            <div>
                <h2 class="modal-title">${art.title || 'Sin título'}</h2>
                <div class="modal-detail">
                    <strong>Artista</strong>
                    ${art.artistDisplayName || 'Desconocido'}
                    ${art.artistNationality ? `(${art.artistNationality})` : ''}
                </div>
                <div class="modal-detail">
                    <strong>Fecha</strong>
                    ${art.objectDate || 'Desconocida'}
                </div>
                <div class="modal-detail">
                    <strong>Tipo / Departamento</strong>
                    ${art.objectName || '—'} · ${art.department || '—'}
                </div>
                <div class="modal-detail">
                    <strong>Cultura</strong>
                    ${art.culture || '—'}
                </div>
                <div class="modal-detail">
                    <strong>Dimensiones</strong>
                    ${art.dimensions || '—'}
                </div>
                <div class="modal-detail">
                    <strong>Materiales</strong>
                    ${art.medium || '—'}
                </div>
                ${art.objectURL ? `<a class="modal-link" href="${art.objectURL}" target="_blank">Ver en el sitio del MET ↗</a>` : ''}
            </div>
        </div>
    `;
    show(modal);
    document.body.style.overflow = 'hidden';
}

// --- Búsqueda ---
async function search(query) {
    lastQuery = query;
    hide(gallery); hide(errorEl); hide(infoEl);
    show(loadingEl);
    loadingMsg.textContent = `Buscando "${query}"...`;

    try {
        const artworks = await searchArtworks(query);
        renderGallery(artworks);
    } catch (err) {
        errorMsgEl.textContent = `Error: ${err.message}`;
        show(errorEl); hide(loadingEl);
    }
}

// --- Eventos ---
searchBtn.addEventListener('click', () => {
    const q = searchInput.value.trim();
    if (q) search(q);
});

searchInput.addEventListener('keypress', e => {
    if (e.key === 'Enter') {
        const q = searchInput.value.trim();
        if (q) search(q);
    }
});

retryBtn.addEventListener('click', () => { if (lastQuery) search(lastQuery); });

closeModal.addEventListener('click', () => {
    hide(modal);
    document.body.style.overflow = '';
});

modal.addEventListener('click', e => {
    if (e.target === modal) {
        hide(modal);
        document.body.style.overflow = '';
    }
});

document.addEventListener('keydown', e => {
    if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
        hide(modal);
        document.body.style.overflow = '';
    }
});

// --- Inicio ---
search('impressionism'); // Búsqueda inicial para mostrar obras al cargar
```

### Tareas

1. Explorar la API del MET con Postman/DevTools
2. Implementar la búsqueda y mostrar las tarjetas de obras
3. Entender el patrón buscar-IDs → cargar-detalles con `Promise.all
4. Implementar el modal de detalles
5. Añadir feedback de carga y errores
6. **Ampliación:** Añadir filtro por departamento usando el endpoint `/departments`

---

## 📚 Referencias y recursos

### APIs utilizadas en los proyectos

| API | URL | Autenticación | Uso en el curso |
|-----|-----|---------------|-----------------|
| The Movie Database | https://www.themoviedb.org | API Key gratuita | Proyecto 1 |
| OpenWeatherMap | https://openweathermap.org | API Key gratuita | Proyecto 2 |
| Metropolitan Museum | https://metmuseum.github.io | Sin autenticación | Proyecto 3 |
| JSONPlaceholder | https://jsonplaceholder.typicode.com | Sin autenticación | Ejemplos teoría |
| REST Countries | https://restcountries.com | Sin autenticación | Ejercicios |
| PokeAPI | https://pokeapi.co | Sin autenticación | Ejercicios |

### Documentación

- [MDN - Fetch API](https://developer.mozilla.org/es/docs/Web/API/Fetch_API)
- [MDN - Promises](https://developer.mozilla.org/es/docs/Web/JavaScript/Reference/Global_Objects/Promise)
- [MDN - async/await](https://developer.mozilla.org/es/docs/Web/JavaScript/Reference/Statements/async_function)
- [MDN - Array methods](https://developer.mozilla.org/es/docs/Web/JavaScript/Reference/Global_Objects/Array)

