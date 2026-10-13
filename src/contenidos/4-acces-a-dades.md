# Unitat 4. Accés a dades


## Resultats d'aprenentatge i criteris d'avaluació
::: details 📋 Resultats d'aprenentatge i criteris d'avaluació

**RA 6. Desenvolupa aplicacions d'accés a magatzems de dades, aplicant mesures per a mantindre la seguretat i la integritat de la informació.**

* **Criteris d'avaluació:**
* **a)** S'han analitzat les tecnologies que permeten l'accés mitjançant programació a la informació disponible en magatzems de dades.
* **b)** S'han creat aplicacions que establisquen connexions amb bases de dades.
* **c)** S'ha recuperat informació emmagatzemada en bases de dades.
* **d)** S'ha publicat en aplicacions web la informació recuperada.
* **e)** S'han utilitzat conjunts de dades per a emmagatzemar la informació.
* **f)** S'han creat aplicacions web que permeten l'actualització i l'eliminació d'informació disponible en una base de dades.
* **g)** S'han provat i documentat les aplicacions.

:::


## 1. Entorn de desenvolupament amb Docker

Fins ara hem treballat amb PHP, però sense guardar res de manera permanent: els leads vivien a `$_SESSION` i es perdien en tancar el navegador. En esta unitat els guardarem en una **base de dades MySQL**. Per a això necessitem tres peces funcionant juntes:

| Servei | Per a què serveix |
| --- | --- |
| `web` | Apache + PHP 8.3, amb **Xdebug** per a depurar |
| `db` | El servidor de base de dades **MySQL** |
| `phpmyadmin` | Interfície web per a gestionar la base de dades |

::: info Nota
Ja has treballat amb Docker en unitats anteriors, així que ací no repassarem què és una imatge o un contenidor. Només prepararem els dos fitxers de configuració: `Dockerfile` i `docker-compose.yml`.
:::

Els col·locarem a l'arrel del projecte, al costat de `index.php`:

```
projecte/
├── Dockerfile
├── docker-compose.yml
├── index.php
├── processar_lead.php
├── dashboard/
└── pujades/
```

### 1.1. El Dockerfile

La imatge oficial de PHP amb Apache no porta ni el driver de MySQL per a PDO ni Xdebug. El `Dockerfile` parteix d'ella i els afig:

```dockerfile
FROM php:8.3-apache

# Driver de MySQL per a PDO (el veurem al punt 6)
RUN docker-php-ext-install pdo_mysql

# Xdebug
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

# Configuració de Xdebug
RUN { \
    echo "xdebug.mode=debug"; \
    echo "xdebug.start_with_request=yes"; \
    echo "xdebug.client_host=host.docker.internal"; \
    echo "xdebug.client_port=9003"; \
} > /usr/local/etc/php/conf.d/xdebug.ini
```

Què fa cada part:

- **`FROM php:8.3-apache`**: imatge base amb PHP 8.3 i el servidor Apache ja configurats.
- **`pdo_mysql`**: extensió que permetrà a PHP parlar amb MySQL mitjançant PDO.
- **`pecl install xdebug`**: instal·la Xdebug, que en la unitat 3 vam vore com a ferramenta de depuració.
- **`xdebug.client_host`**: adreça de la teua màquina, on escolta l'editor (VS Code). Des de dins del contenidor, `host.docker.internal` apunta a ella.
- **`xdebug.client_port`**: el port 9003 és el que utilitza Xdebug 3 per defecte.

::: tip Bona pràctica
`fileinfo` (la classe `finfo` que vam usar per a validar arxius pujats) ja ve habilitada en la imatge oficial de PHP. No cal instal·lar-la.
:::

### 1.2. El docker-compose.yml

El `Dockerfile` defineix **una** imatge. `docker-compose.yml` descriu els **tres serveis** i com es connecten entre ells:

```yaml
services:
  web:
    build: .
    container_name: techleads_web
    ports:
      - "8080:80"
    volumes:
      - ./:/var/www/html
    extra_hosts:
      - "host.docker.internal:host-gateway"
    depends_on:
      - db

  db:
    image: mysql:8.4
    container_name: techleads_db
    environment:
      MYSQL_ROOT_PASSWORD: root
      MYSQL_DATABASE: techleads
      MYSQL_USER: techleads
      MYSQL_PASSWORD: techleads
    volumes:
      - db_data:/var/lib/mysql

  phpmyadmin:
    image: phpmyadmin:5
    container_name: techleads_phpmyadmin
    environment:
      PMA_HOST: db
    ports:
      - "8081:80"
    depends_on:
      - db

volumes:
  db_data:
```

Les claus que cal entendre:

- **`build: .`**: el servei `web` es construeix amb el `Dockerfile` de la carpeta actual.
- **`ports: "8080:80"`**: el port 80 del contenidor és accessible des del navegador en `http://localhost:8080`.
- **`volumes: ./:/var/www/html`**: la carpeta del projecte es munta dins d'Apache. Quan guardes un fitxer, el canvi es veu al moment, sense reconstruir res.
- **`PMA_HOST: db`**: phpMyAdmin es connecta al servei `db`. Dins de Docker, cada servei és accessible pel seu nom.
- **Variables `MYSQL_*`**: MySQL crea en el primer arranc la base de dades `techleads` i l'usuari `techleads`. Les taules les crearem al punt 2.
- **`db_data`**: volum amb nom on MySQL guarda les dades, de manera que **no es perden** en parar o eliminar els contenidors.

::: info Nota
No cal escriure la línia `version:` a dalt de tot del fitxer. Les versions actuals de Docker Compose la consideren obsoleta.
:::

::: warning Atenció
En Linux, la carpeta `pujades/` pertany al teu usuari i Apache s'executa com a `www-data`, de manera que `move_uploaded_file()` pot fallar per permisos. Si passa, executa `chmod 777 pujades` (només en desenvolupament). En Docker Desktop (Windows i macOS) no cal.
:::

::: danger Error crític
Les contrasenyes (`root`, `techleads`) són vàlides només per a un entorn de desenvolupament local. Mai les utilitzes en un servidor real.
:::

### 1.3. Arrancar i comprovar l'entorn

Des de l'arrel del projecte:

```bash
docker compose up -d --build
```

- `--build` construeix la imatge `web` (la primera vegada tardarà uns minuts).
- `-d` deixa els contenidors en segon pla.

Comprova que tot funciona:

| Adreça | Què hauries de vore |
| --- | --- |
| `http://localhost:8080` | El formulari de TechLeads, tal com el teníem |
| `http://localhost:8081` | phpMyAdmin (usuari `root`, contrasenya `root`) |

I comprova que PHP té les extensions que necessitem:

```bash
docker compose exec web php -m | grep -i -E "pdo_mysql|xdebug|fileinfo"
```

Han d'aparéixer les tres. Per a parar l'entorn, `docker compose down` (les dades de MySQL es conserven gràcies al volum).

### 1.4. Xdebug amb Visual Studio Code

Amb l'extensió **PHP Debug** instal·lada (la vam usar al punt 9 de la unitat 3), crea el fitxer `.vscode/launch.json`:

```json
{
  "version": "0.2.0",
  "configurations": [
    {
      "name": "Escoltar Xdebug",
      "type": "php",
      "request": "launch",
      "port": 9003,
      "pathMappings": {
        "/var/www/html": "${workspaceFolder}"
      }
    }
  ]
}
```

`pathMappings` és imprescindible: li diu a VS Code que el fitxer `/var/www/html/index.php` del contenidor és el `index.php` de la teua carpeta. Sense això, els *breakpoints* no s'aturen.

Per a provar-ho: posa un *breakpoint* a la primera línia de `index.php`, prem **F5** (_Escoltar Xdebug_) i recarrega `http://localhost:8080`. L'execució s'ha d'aturar en eixa línia.

---

📌 **A recordar:** `Dockerfile` defineix la imatge de PHP (amb `pdo_mysql` i Xdebug); `docker-compose.yml` connecta els tres serveis (`web`, `db`, `phpmyadmin`). Dins de Docker, els serveis es troben pel seu nom (`db`), no per `localhost`. Les dades de MySQL viuen en un volum i sobreviuen als contenidors.

📖 Documentació oficial

[Imatge oficial de PHP](https://hub.docker.com/_/php) · [Imatge oficial de MySQL](https://hub.docker.com/_/mysql) · [Xdebug: configuració](https://xdebug.org/docs/step_debug)

## 2. Creació de les taules a phpMyAdmin

Al punt anterior, MySQL ha creat la base de dades `techleads` buida. Ara hi crearem les taules que substituiran el que teníem a la sessió i a l'array d'usuaris:

| Abans (unitat 3) | Ara (unitat 4) |
| --- | --- |
| Leads a `$_SESSION['llista_leads']` | Taula `leads` |
| Array `$usuaris` a `usuaris.php` | Taula `usuaris` |

### 2.1. Disseny de les taules

**Taula `leads`**: una fila per cada sol·licitud d'informació.

| Camp | Tipus | Observacions |
| --- | --- | --- |
| `id` | `INT UNSIGNED` | Clau primària, `AUTO_INCREMENT` |
| `nom` | `VARCHAR(100)` | Obligatori |
| `email` | `VARCHAR(150)` | Obligatori |
| `telefon` | `VARCHAR(20)` | Opcional (`NULL`) |
| `empresa` | `VARCHAR(100)` | Opcional (`NULL`) |
| `serveis` | `JSON` | Llista de serveis triats, p. ex. `["Web","SEO"]` |
| `pressupost` | `DECIMAL(10,2)` | Obligatori |
| `missatge` | `TEXT` | Opcional (`NULL`) |
| `document` | `VARCHAR(255)` | Nom de l'arxiu pujat, opcional (`NULL`) |
| `estat` | `ENUM('nou','contactat','tancat')` | Per defecte `nou` |
| `data_creacio` | `TIMESTAMP` | Per defecte, el moment de la inserció |

**Taula `usuaris`**: les persones que poden accedir al panell.

| Camp | Tipus | Observacions |
| --- | --- | --- |
| `id` | `INT UNSIGNED` | Clau primària, `AUTO_INCREMENT` |
| `usuari` | `VARCHAR(50)` | Nom d'accés, **únic** |
| `contrasenya` | `VARCHAR(255)` | El **hash** (`password_hash`), mai la contrasenya |
| `rol` | `ENUM('comercial','administrador')` | Per defecte `comercial` |

Algunes decisions de disseny, que convé entendre:

- **`id` amb `AUTO_INCREMENT`**: ja no cal calcular-lo nosaltres. Recorda `GestorLead::obtenerNuevoId()`: este mètode desapareixerà.
- **`serveis` com a `JSON`**: un lead pot tindre diversos serveis i una cel·la no pot guardar un array. MySQL permet guardar-lo com a text JSON i PHP el converteix amb `json_encode()` i `json_decode()` (ho farem al punt 8).
- **`pressupost` com a `DECIMAL`**: per als diners no s'utilitza `FLOAT`, que pot arrodonir malament. `DECIMAL(10,2)` guarda fins a 10 dígits, 2 d'ells decimals.
- **`document` guarda només el nom**: l'arxiu continua a la carpeta `pujades/`; la base de dades no guarda l'arxiu, sinó una referència a ell.
- **`estat` com a `ENUM`**: només admet els tres valors de les constants de `Lead`.

::: info Nota
Quan PHP llig una columna `DECIMAL` amb PDO, la rep com a **cadena** (`"4500.00"`), no com a `float`. Ho tindrem en compte al construir l'objecte `Lead`.
:::

::: tip Bona pràctica
`contrasenya` és `VARCHAR(255)` perquè la longitud del hash de `password_hash()` pot créixer en futures versions de PHP. Utilitzar-ne 255 evita haver de canviar la taula més endavant.
:::

### 2.2. Crear les taules

1. Obri `http://localhost:8081` i entra amb l'usuari `root` i la contrasenya `root`.
2. A la columna de l'esquerra, selecciona la base de dades **`techleads`**.
3. Ves a la pestanya **SQL**, enganxa el codi següent i prem **Executa**.

```sql
CREATE TABLE leads (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    nom VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    telefon VARCHAR(20) NULL,
    empresa VARCHAR(100) NULL,
    serveis JSON NOT NULL,
    pressupost DECIMAL(10,2) NOT NULL,
    missatge TEXT NULL,
    document VARCHAR(255) NULL,
    estat ENUM('nou','contactat','tancat') NOT NULL DEFAULT 'nou',
    data_creacio TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE usuaris (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    usuari VARCHAR(50) NOT NULL,
    contrasenya VARCHAR(255) NOT NULL,
    rol ENUM('comercial','administrador') NOT NULL DEFAULT 'comercial',
    PRIMARY KEY (id),
    UNIQUE KEY uq_usuari (usuari)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

::: info Nota
`DEFAULT CHARSET=utf8mb4` fa que la taula guarde correctament accents, ñ i emojis (p. ex. el servei «App mòbil»).
:::

Comprova que s'han creat: a la columna de l'esquerra han d'aparéixer `leads` i `usuaris` sota `techleads`. Si fas clic en una taula i vas a la pestanya **Estructura**, veuràs els camps tal com els hem definit.

### 2.3. Dades de prova

Inserim els dos usuaris que ja teníem a `usuaris.php`, **amb els mateixos hashes**, de manera que les contrasenyes continuen sent les d'abans (`contrasenya123` per a `ana` i `admin12345` per a `marc`), i tres leads d'exemple perquè el llistat no estiga buit:

```sql
INSERT INTO usuaris (usuari, contrasenya, rol) VALUES
('ana',  '$2y$10$jtyC89SwKBSRwUpjO.3Ot.Rql4v6QY05kZR7SFrkVLUyWHLNVnZK6', 'comercial'),
('marc', '$2y$10$74Dle4/UJiucMgWk8l4bMuOEhYAzKPGxIrGcDAkOmqivk5ocTez6C', 'administrador');

INSERT INTO leads (nom, email, telefon, empresa, serveis, pressupost, missatge, estat) VALUES
('Aina Soler',    'aina@textils.exemple',  '600123456', 'Tèxtils S.L.',   '["Web","SEO"]',    4500.00,  'Volem renovar la web corporativa.', 'nou'),
('Marc Climent',  'marc@econova.exemple',  NULL,        'Econova',        '["App mòbil"]',    800.00,   NULL,                                'contactat'),
('Laura Sanchis', 'laura@innovacio.exemple', '611987654', 'Innovació Tech', '["Cloud","Web"]', 12000.00, 'Necessitem migrar a la núvol.',     'tancat');
```

Per a comprovar-ho, executa a la pestanya **SQL**:

```sql
SELECT id, nom, serveis, pressupost, estat FROM leads;
```

Han d'eixir les tres files. Fixa't com phpMyAdmin mostra `serveis` com un text JSON.

::: warning Atenció
Si executes dos vegades l'`INSERT` dels usuaris, MySQL donarà un error de clau duplicada: la restricció `UNIQUE` sobre `usuari` impedix que existisquen dos usuaris amb el mateix nom. És el comportament esperat.
:::

::: tip Bona pràctica
Guarda el codi SQL en un fitxer `base-de-dades.sql` dins del projecte. Així qualsevol persona pot recrear la base de dades en una altra màquina. També pots generar-lo des de phpMyAdmin amb la pestanya **Exporta**.
:::

---

📌 **A recordar:** les dades dels leads passen de `$_SESSION` a la taula `leads` i els usuaris a la taula `usuaris`. `AUTO_INCREMENT` genera els identificadors, `ENUM` limita els estats, `JSON` guarda l'array de serveis i `DECIMAL` els imports. De les contrasenyes només es guarda el hash.

📖 Documentació oficial

[MySQL: tipus de dades](https://dev.mysql.com/doc/refman/8.4/en/data-types.html) · [MySQL: tipus JSON](https://dev.mysql.com/doc/refman/8.4/en/json.html) · [Documentació de phpMyAdmin](https://docs.phpmyadmin.net/)

## 3. Estructura del projecte i Composer

Abans de connectar res a la base de dades, reorganitzem el projecte. Fins ara les classes, les vistes i les pàgines estaven barrejades dins de `dashboard/app/` i havíem de fer un `require_once` per a cada arxiu. En este punt:

- Separarem el que és **públic** (el que el navegador pot demanar) del que és **codi intern**.
- Donarem un **espai de noms** (*namespace*) a les nostres classes.
- Deixarem que **Composer** les càrregue soles, sense cap `require_once` de classes.

::: info Nota
Al final d'este punt l'aplicació ha de funcionar **exactament igual** que a la unitat 3 (els leads continuen a la sessió). Només canvia l'organització. A partir del punt 4 anirem substituint peces.
:::

### 3.1. L'estructura nova

```
projecte/
├── Dockerfile
├── docker-compose.yml
├── composer.json
├── base-de-dades.sql
├── .gitignore
├── config/
│   └── usuaris.php
├── public/
│   ├── index.php
│   ├── processar_lead.php
│   ├── pujades/
│   └── dashboard/
│       ├── login.php
│       ├── logout.php
│       └── leads.php
├── src/
│   ├── helpers.php
│   └── Model/
│       ├── Lead.php
│       └── GestorLead.php
├── vistes/
│   ├── capcalera.php
│   └── peu.php
└── vendor/              ← el genera Composer
```

| Carpeta | Què conté |
| --- | --- |
| `public/` | **Només** el que s'ha d'obrir des del navegador: pàgines i arxius pujats. Apache servirà esta carpeta. |
| `src/` | Les classes de l'aplicació, organitzades en subcarpetes. |
| `vistes/` | Les parts comunes de HTML (capçalera i peu). |
| `config/` | Configuració. De moment, només l'array d'usuaris. |
| `vendor/` | Codi generat per Composer (l'autoload). No es toca a mà. |

**Què es mou i on:**

| Abans | Ara |
| --- | --- |
| `index.php`, `processar_lead.php` | `public/` |
| `dashboard/login.php`, `logout.php`, `leads.php` | `public/dashboard/` |
| `pujades/` | `public/pujades/` |
| `dashboard/app/lib/Lead.php` | `src/Model/Lead.php` |
| `dashboard/app/lib/GestorLead.php` | `src/Model/GestorLead.php` (desapareixerà al punt 8) |
| `dashboard/app/lib/functions.php` | `src/helpers.php` |
| `dashboard/app/lib/usuaris.php` | `config/usuaris.php` (desapareixerà al punt 11) |
| `dashboard/app/vistas/capcalera.php`, `peu.php` | `vistes/` |

Les carpetes `maquetes/` i `ENUNCIAT.md` ja no formen part del projecte.

::: tip Bona pràctica
Posar el codi fora de `public/` és una mesura de seguretat: si Apache només serveix `public/`, ningú pot demanar des del navegador un arxiu de `src/` o `config/`, ni encara que sàpia el seu nom.
:::

### 3.2. Docker: Apache servirà `public/` i Composer

Hem de fer dos canvis al `Dockerfile` del punt 1: dir-li a Apache que l'arrel del lloc és `public/`, i instal·lar Composer. Afig al final:

```dockerfile
# Utilitats per a Composer
RUN apt-get update && apt-get install -y unzip git \
    && rm -rf /var/lib/apt/lists/*

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Apache serveix la carpeta public/
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
```

Reconstruïx la imatge:

```bash
docker compose up -d --build
```

::: info Nota
El volum `./:/var/www/html` del `docker-compose.yml` no canvia: dins del contenidor continua apareixent tot el projecte, però Apache només exposa la subcarpeta `public/`.
:::

### 3.3. Composer i l'autoload PSR-4

Fins ara, per a usar una classe fèiem `require_once` de l'arxiu. **Composer** pot generar un *autoloader*: quan el codi utilitza una classe que encara no s'ha carregat, ell sap en quin arxiu està i la carrega automàticament.

Per a això segueix l'estàndard **PSR-4**, que associa un espai de noms a una carpeta. Crea `composer.json` a l'arrel del projecte:

```json
{
    "name": "techleads/projecte",
    "description": "TechLeads - Unitat 4",
    "type": "project",
    "require": {
        "php": ">=8.3"
    },
    "autoload": {
        "psr-4": {
            "App\\": "src/"
        },
        "files": [
            "src/helpers.php"
        ]
    }
}
```

- **`"App\\": "src/"`**: tot el que estiga a l'espai de noms `App\` es busca dins de `src/`. La resta del nom es tradueix a subcarpetes:

  | Classe | Arxiu |
  | --- | --- |
  | `App\Model\Lead` | `src/Model/Lead.php` |
  | `App\Model\GestorLead` | `src/Model/GestorLead.php` |

- **`"files"`**: les funcions soltes (com `e()`) no són classes i PSR-4 no les pot carregar. Esta clau fa que Composer incloga `src/helpers.php` sempre que s'active l'autoload.

Genera l'autoload dins del contenidor:

```bash
docker compose exec web composer dump-autoload
```

Això crea la carpeta `vendor/`. A partir d'ara, **un únic** `require_once` donarà accés a totes les nostres classes:

```php
require_once __DIR__ . '/../vendor/autoload.php';
```

::: warning Atenció
Si afegixes una classe nova **sense seguir la regla** (el namespace ha de coincidir amb la carpeta i el nom de la classe amb el del fitxer), l'autoload no la trobarà i PHP donarà l'error `Class "..." not found`.
:::

Crea també un fitxer `.gitignore` perquè la carpeta generada no es puge al repositori:

```
vendor/
```

### 3.4. Adaptar les classes

Les dos classes passen a l'espai de noms `App\Model`. Ja no necessiten cap `require_once`: l'autoload fa la feina.

**`src/Model/Lead.php`**: afig el *namespace* a dalt i **elimina** el `require_once` de `GestorLead`:

```php
<?php

namespace App\Model;

class Lead
{
    // ... la resta de la classe no canvia
```

**`src/Model/GestorLead.php`**: igual, afig el *namespace* i **elimina** el `require_once` de `Lead`:

```php
<?php

namespace App\Model;

class GestorLead
{
    // ... la resta de la classe no canvia
```

Com que les dos estan al mateix espai de noms, `Lead` pot cridar a `GestorLead` (i al revés) sense cap `use`. A més, **desapareix la referència circular** entre els dos arxius (`Lead.php` requeria `GestorLead.php` i viceversa).

### 3.5. Adaptar les pàgines

Cada pàgina canvia només la capçalera de l'arxiu. Fixa't en un detall important: l'`autoload` es carrega **abans** de `session_start()`.

::: info Nota
A la unitat 3 vam vore que cal carregar la classe `Lead` abans de `session_start()`, o els objectes de la sessió es recuperaven com a `__PHP_Incomplete_Class`. Amb l'autoload, aquell problema desapareix: PHP busca la classe pel seu compte quan deserialitza la sessió, sempre que l'autoloader ja estiga registrat.
:::

**`public/index.php`**: no usa cap classe, només la funció `e()`:

```php
<?php
require_once __DIR__ . '/../vendor/autoload.php';

session_start();

// ... (el codi intermedi no canvia)

require_once __DIR__ . '/../vistes/capcalera.php';
```

I al final de l'arxiu:

```php
include_once __DIR__ . '/../vistes/peu.php';
```

**`public/processar_lead.php`**: usa `Lead` i `GestorLead`, per això necessita els `use`:

```php
<?php

use App\Model\GestorLead;
use App\Model\Lead;

require_once __DIR__ . '/../vendor/autoload.php';

session_start();
```

**`public/dashboard/login.php`**:

```php
<?php
require_once __DIR__ . '/../../vendor/autoload.php';

session_start();
```

I, dins del bloc `POST`, la ruta de l'array d'usuaris:

```php
require_once __DIR__ . '/../../config/usuaris.php';
```

**`public/dashboard/logout.php`**:

```php
<?php
require_once __DIR__ . '/../../vendor/autoload.php';

session_start();
```

**`public/dashboard/leads.php`**: usa `Lead::ESTAT_TANCAT` i `GestorLead`:

```php
<?php

use App\Model\GestorLead;
use App\Model\Lead;

require_once __DIR__ . '/../../vendor/autoload.php';

session_start();
```

Fixa't que el nombre de `../` en cada `require_once` depén de la profunditat de la pàgina respecte a la carpeta `vendor/`: una pàgina de `public/` puja un nivell (`../`) i una de `public/dashboard/` en puja dos (`../../`).

::: warning Atenció
Els leads que ja tingues a la sessió d'abans de reorganitzar es van guardar amb la classe `Lead` **sense** espai de noms. Ara la classe es diu `App\Model\Lead`, per la qual cosa PHP no sabrà recuperar-los i `leads.php` donarà error. Abans de provar, **tanca el navegador** o esborra la cookie `PHPSESSID` perquè la sessió comence de zero.
:::

### 3.6. Comprovar que tot funciona igual

Obri `http://localhost:8080` i repassa el recorregut complet de la unitat 3:

1. Envia el formulari amb dades incorrectes: es mostren els errors i es conserven els valors.
2. Envia'l correctament, amb un document adjunt.
3. Entra a `http://localhost:8080/dashboard/login.php` amb `ana` / `contrasenya123`.
4. Comprova que el lead apareix al llistat, que el document s'obri i que **Avança estat** funciona.
5. Tanca la sessió.

Si algun pas falla, mira el missatge d'error de PHP: la majoria d'errors en este punt són una ruta mal escrita (`../` de més o de menys) o un `use` que falta.

### 3.7. L'estàndard PSR-12

Ja hem vist PSR-4 (carrega de classes). **PSR-12** és l'estàndard d'estil del codi PHP: no canvia el que fa el programa, però fa que tot el codi es vegi igual. En este projecte seguirem les regles bàsiques:

- Sagnat de **4 espais** (mai tabuladors).
- Una classe per arxiu, amb nom en `StudlyCaps` (`LeadDAO`) i mètodes en `camelCase` (`avancarEstat`).
- La clau `{` d'una **classe o d'un mètode** va en una línia nova; la d'un `if`, `foreach`... va a la mateixa línia.
- Línia en blanc després de `namespace` i després del bloc de `use`.
- Només `<?php` en els arxius de classes, sense etiqueta de tancament `?>`.

```php
<?php

namespace App\Model;

class Exemple
{
    public function metode(int $valor): bool
    {
        if ($valor > 0) {
            return true;
        }

        return false;
    }
}
```

::: tip Bona pràctica
Si el teu editor és VS Code, l'extensió **PHP CS Fixer** (o **Intelephense** amb formatador) aplica PSR-12 amb un clic.
:::

---

📌 **A recordar:** `public/` és l'única carpeta que Apache serveix; el codi de l'aplicació viu fora d'ella. Composer genera un autoload PSR-4 que associa l'espai de noms `App\` a la carpeta `src/`, de manera que no cal cap `require_once` de classes: n'hi ha prou amb `vendor/autoload.php` (abans de `session_start()`) i els `use` corresponents.

📖 Documentació oficial

[Composer: Autoloading](https://getcomposer.org/doc/04-schema.md#autoload) · [PSR-4](https://www.php-fig.org/psr/psr-4/) · [PSR-12](https://www.php-fig.org/psr/psr-12/) · [PHP: Espacios de nombres](https://www.php.net/manual/es/language.namespaces.php)

## 4. El model: `Lead` i `Usuari`

En una aplicació amb base de dades, el **model** és el conjunt de classes que representen les dades del negoci. Cada taula que vam crear al punt 2 té una classe equivalent: una fila de la taula es converteix en un objecte, i cada columna en una propietat.

| Taula | Classe | Un objecte representa... |
| --- | --- | --- |
| `leads` | `Lead` | Una sol·licitud d'informació |
| `usuaris` | `Usuari` | Una persona amb accés al panell |

Una regla fonamental: **el model no sap res de SQL ni de PDO**. Només guarda dades i les regles pròpies (per exemple, com avança l'estat d'un lead). De parlar amb la base de dades se n'encarregaran altres classes, que veurem als punts següents.

::: info Nota
Així ja funcionava a la unitat 3: `Lead` no guardava res, ho feia `GestorLead`. Ara mantenim eixa separació, però el lloc on es guarda canvia de la sessió a MySQL.
:::

### 4.1. Canvis a `Lead`

`Lead` ja té quasi tot el que necessitem, però hi ha dos problemes:

1. El constructor calcula el seu propi `id` cridant a `GestorLead::obtenerNuevoId()`, que comptava els leads de la sessió. Ara l'identificador el genera **MySQL** (`AUTO_INCREMENT`).
2. Als punts 6 i 8 deixarem que **PDO construïsca els objectes `Lead` directament** a partir de les files (`PDO::FETCH_CLASS`). Això imposa unes regles al disseny de la classe.

| Regla | Per què |
| --- | --- |
| Les propietats tenen **el mateix nom que les columnes** | PDO copia cada columna a la propietat amb el seu nom, fins i tot si és `private` |
| El constructor es pot cridar **sense arguments** | PDO crea l'objecte buit i després l'omple |
| `id` **no pot ser `readonly`** | El constructor ja l'inicialitza, i PDO no el podria assignar després |
| `serveis` guarda el **JSON com a text** | PDO no pot convertir el text de la columna en un array: ho fem nosaltres en `getServeis()` |
| Les propietats porten **tipus** | PDO converteix automàticament, p. ex. `"4500.00"` a `float` |

Substituïx les propietats i el constructor de `src/Model/Lead.php` per:

```php
class Lead
{
    public const ESTAT_NOU = 'nou';
    public const ESTAT_CONTACTAT = 'contactat';
    public const ESTAT_TANCAT = 'tancat';

    private ?int $id = null;
    private string $nom = '';
    private string $email = '';
    private ?string $telefon = null;
    private ?string $empresa = null;
    private string $serveis = '[]';          // JSON, tal com està a la base de dades
    private float $pressupost = 0.0;
    private ?string $missatge = null;
    private ?string $document = null;
    private string $estat = self::ESTAT_NOU;

    public function __construct(
        string $nom = '',
        string $email = '',
        ?string $telefon = null,
        ?string $empresa = null,
        array $serveis = [],
        float $pressupost = 0.0,
        ?string $missatge = null,
        ?string $document = null,
        ?int $id = null,
        string $estat = self::ESTAT_NOU
    ) {
        $this->nom = $nom;
        $this->email = $email;
        $this->telefon = $telefon;
        $this->empresa = $empresa;
        $this->serveis = json_encode($serveis, JSON_UNESCAPED_UNICODE);
        $this->pressupost = $pressupost;
        $this->missatge = $missatge;
        $this->document = $document;
        $this->id = $id;
        $this->estat = $estat;
    }
```

Dos canvis als *getters*:

```php
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getServeis(): array
    {
        return json_decode($this->serveis, true) ?? [];
    }
```

La resta de mètodes (`getNom()`, `avancarEstat()`, etc.) no canvien.

El constructor serveix per a dos usos:

- **Nosaltres**, quan creem un lead amb dades (per exemple, des del formulari):

```php
$lead = new Lead(
    nom: 'Aina Soler',
    email: 'aina@textils.exemple',
    empresa: 'Tèxtils S.L.',
    serveis: ['Web', 'SEO'],
    pressupost: 4500.00
);
```

- **PDO**, que el crida sense arguments i després omple les propietats amb les columnes.

::: tip Bona pràctica
Els **arguments amb nom** (`empresa: '...'`) permeten saltar-se els paràmetres opcionals que no necessitem i fan el codi molt més llegible.
:::

::: warning Atenció
Que tots els paràmetres siguen opcionals té un preu: `new Lead()` crea un lead **buit**, sense dades vàlides. Les dades correctes les garantim validant el formulari abans de construir l'objecte (punt 10). És el cost de deixar que PDO construïsca els objectes.
:::

::: info Nota
`id` ja no és `readonly`, però continua sense tindre cap mètode per a modificar-lo (no hi ha `setId()`). Només el pot assignar el constructor i PDO. Quan s'insereix un lead nou, MySQL assigna l'`id` i el DAO ens el **retorna** (punt 8).
:::

::: info Nota
La taula `leads` té un camp `data_creacio`, però **no el portem al model**: només l'utilitzarem per a ordenar el llistat (`ORDER BY data_creacio DESC`). Per això les consultes del punt 8 indiquen les columnes que volem i no usen `SELECT *`.
:::

### 4.2. La classe `Usuari`

Els usuaris segueixen les mateixes regles. Crea `src/Model/Usuari.php`:

```php
<?php

namespace App\Model;

class Usuari
{
    public const ROL_COMERCIAL = 'comercial';
    public const ROL_ADMINISTRADOR = 'administrador';

    private ?int $id = null;
    private string $usuari = '';
    private string $contrasenya = '';
    private string $rol = self::ROL_COMERCIAL;

    public function __construct(
        string $usuari = '',
        string $contrasenya = '',
        string $rol = self::ROL_COMERCIAL,
        ?int $id = null
    ) {
        $this->usuari = $usuari;
        $this->contrasenya = $contrasenya;
        $this->rol = $rol;
        $this->id = $id;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsuari(): string
    {
        return $this->usuari;
    }

    public function getRol(): string
    {
        return $this->rol;
    }

    public function verificarContrasenya(string $contrasenya): bool
    {
        return password_verify($contrasenya, $this->contrasenya);
    }
}
```

Observa dos decisions:

- **No hi ha `getContrasenya()`.** El hash és una dada sensible: la classe no el mostra a l'exterior. En lloc d'això, ofereix `verificarContrasenya()`, que fa la comparació internament amb `password_verify()`, com ja fèiem a `login.php`.
- **Les propietats es diuen com les columnes** de la taula `usuaris` (`usuari`, `contrasenya`, `rol`, `id`), perquè PDO les puga omplir.

::: warning Atenció
Per què la contrasenya és ja un hash? Perquè un `Usuari` llegit de la base de dades ja la porta així. El moment d'aplicar `password_hash()` és abans de guardar l'usuari, no ací.
:::

### 4.3. Comprovar el model

Com que ja no hi ha `obtenerNuevoId()`, **l'aplicació no funcionarà fins que connectem la base de dades** (punts 8 a 10). Això és normal: acabem de traure la peça que sostenia el sistema de la sessió.

Per a comprovar que les classes funcionen, crea un fitxer temporal `public/prova-model.php`:

```php
<?php

use App\Model\Lead;
use App\Model\Usuari;

require_once __DIR__ . '/../vendor/autoload.php';

$lead = new Lead('Aina Soler', 'aina@textils.exemple', null, 'Tèxtils S.L.', ['Web', 'SEO'], 4500.00, null, null);
var_dump($lead->getId());      // NULL
var_dump($lead->getEstat());   // "nou"

$lead->avancarEstat();
var_dump($lead->getEstat());   // "contactat"

$usuari = new Usuari('ana', password_hash('contrasenya123', PASSWORD_DEFAULT));
var_dump($usuari->verificarContrasenya('contrasenya123'));  // bool(true)
var_dump($usuari->verificarContrasenya('altra'));           // bool(false)
```

Obri `http://localhost:8080/prova-model.php`. Si els valors coincidixen amb els comentaris, el model està ben fet. **Esborra el fitxer** quan acabes.

---

📌 **A recordar:** el model són classes que representen les files de les taules (`Lead` ↔ `leads`, `Usuari` ↔ `usuaris`) i no saben res de SQL. L'`id` el genera MySQL, per això és opcional al constructor, igual que l'`estat`. La contrasenya d'`Usuari` només es manipula internament.

📖 Documentació oficial

[PHP: Promoció de propietats al constructor](https://www.php.net/manual/es/language.oop5.decon.php#language.oop5.decon.constructor.promotion) · [PHP: Propietats `readonly`](https://www.php.net/manual/es/language.oop5.properties.php#language.oop5.properties.readonly-properties) · [PHP: Arguments amb nom](https://www.php.net/manual/es/functions.arguments.php#functions.named-arguments)

## 5. La interfície `IDbAccess`

Ja tenim les classes del model. Ara necessitem decidir **com** guardarem i recuperarem les dades. Abans d'escriure ni una línia de PDO, definirem **què s'ha de poder fer** amb elles. Això és el que fa una **interfície**.

### 5.1. Què és una interfície

Una interfície és un **contracte**: llista els mètodes que una classe ha d'oferir, però **no diu com es fan**. Cada classe que "signe" el contracte (amb `implements`) està obligada a implementar-los tots.

Pensa en un endoll: no importa si darrere hi ha una central hidroelèctrica o una placa solar; l'aparell només necessita que l'endoll tinga la forma correcta.

```php
interface Exemple
{
    public function fesAlgo(): void;   // només la signatura, sense cos
}
```

Una interfície no té propietats ni codi dins dels mètodes, i no es pot instanciar amb `new`.

### 5.2. Per què en necessitem una

Mira com treballava la unitat 3: les pàgines cridaven directament a `GestorLead`, que guardava els leads a `$_SESSION`:

```php
GestorLead::anyadirLead($lead);   // la pàgina depén de la sessió
```

Si volguérem canviar a MySQL, hauríem de tocar **totes les pàgines**. Amb una interfície, les pàgines només coneixen el contracte:

```php
function guardar(IDbAccess $dao, object $entitat): int
{
    return $dao->insert($entitat);   // no sap si és MySQL, sessió o un fitxer
}
```

Així aconseguim:

- **Pàgines independents de la tecnologia**: poden canviar d'emmagatzematge sense modificar-se.
- **Intercanvi fàcil**: podríem tindre una implementació per a la sessió (com la unitat 3) i una altra per a MySQL.
- **Codi més fàcil de provar**: es pot substituir la base de dades real per una falsa.
- **Reutilització**: com que no depén de cap classe concreta, el mateix contracte servirà per a `Lead`, per a `Usuari` o per a qualsevol entitat que afegim en el futur.

::: info Nota
Esta és la base del que veurem al punt 7 amb la **injecció de dependències**: les pàgines rebran un objecte que compleix `IDbAccess`, sense decidir elles quina classe concreta és.
:::

### 5.3. Definir `IDbAccess`

Creem la carpeta `src/DAO/` i dins el fitxer `src/DAO/IDbAccess.php`:

```php
<?php

namespace App\DAO;

/**
 * Contracte d'accés a dades per a qualsevol entitat del model.
 *
 * @template T of object
 */
interface IDbAccess
{
    /**
     * Retorna totes les entitats.
     *
     * @return T[]
     */
    public function findAll(): array;

    /**
     * Retorna l'entitat amb l'id indicat, o null si no existeix.
     *
     * @return T|null
     */
    public function findById(int $id): ?object;

    /**
     * Guarda una entitat nova i retorna l'id que li ha assignat la base de dades.
     *
     * @param T $entitat
     */
    public function insert(object $entitat): int;

    /**
     * Actualitza una entitat existent (identificada pel seu id).
     *
     * @param T $entitat
     */
    public function update(object $entitat): void;

    /**
     * Elimina l'entitat amb l'id indicat.
     */
    public function delete(int $id): void;
}
```

Els cinc mètodes cobrixen el CRUD complet (*Create, Read, Update, Delete*) i es corresponen amb el que ja fèiem a `GestorLead`, més dues operacions noves:

| `GestorLead` (unitat 3) | `IDbAccess` (unitat 4) |
| --- | --- |
| `obtenerListaLeads()` | `findAll()` |
| *(no existia)* | `findById()` |
| `anyadirLead()` | `insert()` |
| `avancarEstat()` | `findById()` + `Lead::avancarEstat()` + `update()` |
| *(no existia)* | `update()` per a editar |
| *(no existia)* | `delete()` |

Observa els detalls:

- **Els paràmetres són de tipus `object`**, no `Lead`. Així la interfície no està lligada a cap entitat concreta.
- **`@template T of object`** és una anotació en un comentari: diu als editors i analitzadors (com Intelephense o PHPStan) que la interfície funciona amb "un tipus `T`", i que `findAll()` retorna una llista de `T`. PHP **no** la comprova en executar; és només una ajuda per a l'editor.
- **`insert()` retorna l'`id`** (`int`): com que ara el genera MySQL, és l'única manera de saber quin li ha tocat a l'entitat nova.
- **`findById()` retorna `?object`**: si no existeix, retorna `null` i la pàgina decidirà què fer.
- **L'estat s'avança al model, no al DAO.** La regla `nou` → `contactat` → `tancat` continua dins de `Lead::avancarEstat()`. El DAO només recupera el lead, el fa avançar i el guarda.

::: tip Bona pràctica
Els noms de mètodes en anglés (`findAll`, `insert`...) són convenció en l'accés a dades i els trobaràs igual en Laravel i en altres frameworks.
:::

### 5.4. Què passa amb les classes que la implementen

Al punt 8 crearem `LeadDAO`, que escriurà `class LeadDAO implements IDbAccess`. Des d'eixe moment, PHP **exigirà** que tinga els cinc mètodes amb estes signatures:

::: warning Atenció
Si una classe diu que implementa `IDbAccess` però li falta algun mètode, o el declara amb altres tipus, PHP donarà un error fatal: *Class ... contains 1 abstract method and must therefore be declared abstract or implement the remaining methods*. Això és precisament la utilitat de la interfície: **PHP comprova el contracte**.
:::

Hi ha una conseqüència important de triar `object` a la interfície. Una classe que implementa un contracte **no pot ser més restrictiva** que ell: si la interfície promet que `insert()` accepta qualsevol objecte, `LeadDAO` no pot declarar `insert(Lead $lead)`. PHP ho rebutjaria amb un error fatal.

Per això, dins de cada implementació farem nosaltres la comprovació amb `instanceof`. Este és el patró que seguirem al punt 8:

```php
/**
 * @implements IDbAccess<Lead>
 */
class LeadDAO implements IDbAccess
{
    // ...

    public function insert(object $entitat): int
    {
        if (!$entitat instanceof Lead) {
            throw new InvalidArgumentException('LeadDAO només accepta objectes Lead.');
        }

        // A partir d'ací, $entitat és un Lead segur
        // ...
    }

    // Els tipus de retorn SÍ poden ser més concrets que els de la interfície:
    public function findById(int $id): ?Lead
    {
        // ...
    }
}
```

- **`@implements IDbAccess<Lead>`** concreta el tipus `T` per a esta classe.
- **`instanceof`** comprova en temps d'execució que ens han passat un `Lead`. Si no, llança una excepció i l'error queda a la vista.
- **El tipus de retorn** sí que es pot fer més estricte: `?Lead` és un cas particular de `?object`, per tant és vàlid. Així, qui cride a `LeadDAO::findById()` sap que rep un `Lead`.

::: info Nota
Dins d'un espai de noms, les classes natives de PHP (`InvalidArgumentException`, `PDO`, `PDOException`...) s'han d'importar amb `use InvalidArgumentException;` o escriure's amb barra inicial (`\InvalidArgumentException`). Si no, PHP les buscarà dins de `App\DAO` i no les trobarà.
:::

::: warning Atenció
Fixa't en el que guanyem i en el que perdem. **Guanyem** una interfície reutilitzable per a qualsevol entitat. **Perdem** que PHP detecte en temps de compilació que passem un objecte equivocat: ara l'error eixirà en executar (una excepció). És un intercanvi habitual quan es fa codi genèric en PHP.
:::

### 5.5. Comprovar la interfície

Una interfície no fa res per si mateixa, però podem comprovar que l'autoload la troba. Afig al fitxer temporal `public/prova-model.php` del punt anterior:

```php
use App\DAO\IDbAccess;

var_dump(interface_exists(IDbAccess::class));   // bool(true)
```

Recorda que els `use` van a dalt de l'arxiu, junt amb els altres. Si obtens `bool(true)`, la interfície està ben ubicada (`src/DAO/IDbAccess.php`) i amb l'espai de noms correcte.

::: info Nota
Si proves a escriure `new IDbAccess()`, PHP et donarà l'error `Cannot instantiate interface`. És el comportament esperat: una interfície és només un contracte.
:::

---

📌 **A recordar:** una interfície defineix **què** es pot fer, no **com**. `IDbAccess` fixa el CRUD (`findAll`, `findById`, `insert`, `update`, `delete`) per a qualsevol entitat, usant `object` com a tipus. Les classes que la implementen (punt 8) comproven amb `instanceof` que reben l'entitat correcta i poden concretar el tipus de retorn.

📖 Documentació oficial

[PHP: Interfícies d'objectes](https://www.php.net/manual/es/language.oop5.interfaces.php) · [PHP: Declaracions de tipus](https://www.php.net/manual/es/language.types.declarations.php) · [PHP: `instanceof`](https://www.php.net/manual/es/language.operators.type.php)

## 6. PDO: accedir a MySQL des de PHP

Tenim les taules, el model i el contracte. Falta la peça que **parla amb MySQL**: PDO. En este punt aprendrem a usar-lo amb proves soltes; al punt 7 l'encapsularem en una classe i al punt 8 l'usarem dins dels DAO.

### 6.1. Què és PDO

**PDO** (*PHP Data Objects*) és l'extensió de PHP per a accedir a bases de dades. Instal·lem el seu controlador per a MySQL (`pdo_mysql`) al `Dockerfile` del punt 1.

Les seues avantatges front a l'extensió més antiga `mysqli`:

- Una **única API** per a molts sistemes de bases de dades (MySQL, PostgreSQL, SQLite...): canviar de SGBD afecta sobretot el *DSN*.
- Suport per a **consultes preparades** amb paràmetres amb nom, clau per a la seguretat.
- Gestió d'errors mitjançant **excepcions**.

### 6.2. Connectar-se a la base de dades

Per a connectar-se cal crear un objecte `PDO` amb tres coses: el **DSN**, l'usuari i la contrasenya.

```php
$dsn = 'mysql:host=db;dbname=techleads;charset=utf8mb4';

$pdo = new PDO($dsn, 'techleads', 'techleads');
```

El **DSN** (*Data Source Name*) és una cadena que diu on i a què connectar-se:

| Part | Valor | Significat |
| --- | --- | --- |
| `mysql:` | | Controlador a usar |
| `host` | `db` | Nom del servei MySQL al `docker-compose.yml` |
| `dbname` | `techleads` | Base de dades creada al punt 1 |
| `charset` | `utf8mb4` | Codificació de caràcters (accents, ñ, emojis) |

::: warning Atenció
El `host` és **`db`**, no `localhost`. Des de dins del contenidor `web`, `localhost` és el propi contenidor, on no hi ha cap MySQL. Dins de Docker Compose, els serveis es troben pel seu nom.
:::

### 6.3. Opcions de connexió

El tercer paràmetre (opcional) del constructor és un array d'opcions. Les tres següents són les que usarem sempre:

```php
$opcions = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

$pdo = new PDO($dsn, 'techleads', 'techleads', $opcions);
```

| Opció | Efecte |
| --- | --- |
| `ERRMODE_EXCEPTION` | Si una consulta falla, PDO llança una `PDOException`. Des de PHP 8 és el valor per defecte, però convé deixar-ho explícit. |
| `FETCH_ASSOC` | Cada fila es retorna com a array associatiu (`$fila['nom']`). Per defecte retorna cada dada dues vegades (per nom i per posició). |
| `EMULATE_PREPARES => false` | Les consultes preparades les fa el propi MySQL, no PHP: més segur i més fidel. |

### 6.4. Consultes sense paràmetres: `query()`

Si la consulta **no depén de cap dada externa**, es pot executar directament:

```php
$stmt = $pdo->query('SELECT id, nom, estat FROM leads ORDER BY data_creacio DESC');

foreach ($stmt as $fila) {
    echo $fila['id'] . ' - ' . $fila['nom'] . ' (' . $fila['estat'] . ')<br>';
}
```

`query()` retorna un objecte `PDOStatement`, que es pot recórrer amb un `foreach`. Per a obtindre totes les files d'una vegada com a array, s'usa `fetchAll()`:

```php
$files = $pdo->query('SELECT * FROM leads')->fetchAll();
```

### 6.5. Consultes preparades

Quan la consulta inclou **dades que venen de fora** (un `id` de la URL, un valor d'un formulari...), no s'ha de concatenar mai a la cadena SQL.

```php
// MAI AIXÒ
$id = $_GET['id'];
$stmt = $pdo->query("SELECT * FROM leads WHERE id = $id");
```

Si algú escriu `?id=1 OR 1=1`, la consulta es convertix en `SELECT * FROM leads WHERE id = 1 OR 1=1` i retorna **tots** els leads. Això és una **injecció SQL**, una de les vulnerabilitats més greus i freqüents.

La solució són les **consultes preparades**: l'SQL i les dades s'envien **per separat**, de manera que les dades mai s'interpreten com a codi.

```php
$stmt = $pdo->prepare('SELECT * FROM leads WHERE id = :id');
$stmt->execute(['id' => $id]);

$fila = $stmt->fetch();   // una sola fila, o false si no n'hi ha
```

El procés té tres passos:

1. **`prepare()`**: s'envia l'SQL amb *marcadors* (`:id`) en lloc de valors.
2. **`execute()`**: s'envien els valors reals.
3. **`fetch()` / `fetchAll()`**: es recullen els resultats.

| Mètode | Retorna |
| --- | --- |
| `fetch()` | La següent fila com a array, o `false` si no n'hi ha més |
| `fetchAll()` | Totes les files, com a array d'arrays (buit si no n'hi ha) |

::: info Nota
Els marcadors poden ser **amb nom** (`:id`, `:nom`), que és el que usarem, o **posicionals** (`?`). Els amb nom són més llegibles quan hi ha diversos paràmetres.
:::

::: warning Atenció
Els marcadors només poden substituir **valors**, no noms de taules, de columnes ni paraules clau com `ASC`/`DESC`. Si algun dia necessites un `ORDER BY` dinàmic, comprova el valor contra una llista de paraules permeses abans d'incloure'l a l'SQL.
:::

### 6.6. Inserir, actualitzar i eliminar

Les tres operacions s'executen igual: `prepare()` + `execute()`.

**INSERT**: cal saber quin `id` ha assignat MySQL:

```php
$stmt = $pdo->prepare(
    'INSERT INTO leads (nom, email, telefon, empresa, serveis, pressupost, missatge, document)
     VALUES (:nom, :email, :telefon, :empresa, :serveis, :pressupost, :missatge, :document)'
);

$stmt->execute([
    'nom'        => 'Pere Vidal',
    'email'      => 'pere@exemple.com',
    'telefon'    => null,
    'empresa'    => 'Vidal i Fills',
    'serveis'    => json_encode(['Web', 'Cloud'], JSON_UNESCAPED_UNICODE),
    'pressupost' => 3200.50,
    'missatge'   => null,
    'document'   => null,
]);

$idNou = (int) $pdo->lastInsertId();
```

- **`json_encode()`** converteix l'array de serveis en text JSON per a la columna `serveis`. `JSON_UNESCAPED_UNICODE` evita que «App mòbil» es guarde com `App m\u00f2bil`.
- **`lastInsertId()`** retorna l'`id` generat per `AUTO_INCREMENT`. Ho fa en forma de cadena, per això el convertim a `int`.
- Els valors `null` de PHP es guarden com a `NULL` de MySQL.

**UPDATE**:

```php
$stmt = $pdo->prepare('UPDATE leads SET estat = :estat WHERE id = :id');
$stmt->execute(['estat' => 'contactat', 'id' => 2]);
```

**DELETE**:

```php
$stmt = $pdo->prepare('DELETE FROM leads WHERE id = :id');
$stmt->execute(['id' => 4]);

echo $stmt->rowCount();   // files eliminades: 1 si existia, 0 si no
```

`rowCount()` indica quantes files ha afectat l'última consulta `INSERT`, `UPDATE` o `DELETE`.

::: danger Error crític
Un `UPDATE` o `DELETE` **sense `WHERE`** afecta **totes les files** de la taula. Comprova sempre la clàusula `WHERE` abans d'executar.
:::

### 6.7. Gestionar els errors: `PDOException`

Amb `ERRMODE_EXCEPTION`, qualsevol problema (connexió, SQL incorrecte, restricció violada) llança una `PDOException`. S'ha de capturar amb `try/catch`:

```php
try {
    $pdo = new PDO($dsn, 'techleads', 'techleads', $opcions);
    // ... consultes
} catch (PDOException $e) {
    echo 'Error de base de dades: ' . $e->getMessage();
}
```

Estos són els errors més comuns en este entorn i com interpretar-los:

| Missatge (resumit) | Causa probable |
| --- | --- |
| `SQLSTATE[HY000] [2002] php_network_getaddresses` o `Connection refused` | El `host` és incorrecte (no és `db`) o el contenidor MySQL encara no ha arrancat |
| `SQLSTATE[HY000] [1045] Access denied` | Usuari o contrasenya incorrectes |
| `SQLSTATE[HY000] [1049] Unknown database` | El nom de la base de dades al DSN és incorrecte |
| `SQLSTATE[42S02]: Table ... doesn't exist` | La taula no existeix: no s'ha executat l'SQL del punt 2 |
| `SQLSTATE[23000] ... Duplicate entry` | Es viola una clau única (p. ex. dos usuaris amb el mateix `usuari`) |

::: danger Error crític
`$e->getMessage()` pot revelar noms de taules, columnes o l'adreça del servidor. En desenvolupament és útil mostrar-lo; en un servidor real **mai** s'ensenya a l'usuari: es registra amb `error_log()` i es mostra un missatge genèric.
:::

### 6.8. De fila a objecte: `FETCH_CLASS`

Fins ara hem rebut les files com a arrays. Però la nostra aplicació treballa amb objectes `Lead`. PDO pot fer la conversió per nosaltres amb el mode `PDO::FETCH_CLASS`: crea un objecte de la classe indicada i copia cada columna a la propietat amb el mateix nom.

```php
$stmt = $pdo->query(
    'SELECT id, nom, email, telefon, empresa, serveis, pressupost, missatge, document, estat FROM leads'
);

$leads = $stmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, Lead::class);
```

Per a obtindre **un sol** objecte, s'indica el mode abans d'executar la consulta:

```php
$stmt = $pdo->prepare('SELECT id, nom, email, telefon, empresa, serveis, pressupost, missatge, document, estat FROM leads WHERE id = :id');
$stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, Lead::class);
$stmt->execute(['id' => 1]);

$lead = $stmt->fetch();   // un objecte Lead, o false si no existeix
```

Hi ha tres detalls importants:

- **`PDO::FETCH_PROPS_LATE`**: per defecte, PDO omple les propietats i **després** crida al constructor, que les sobreescriuria amb els valors per defecte. Amb este indicador l'ordre és el contrari: primer el constructor i després les dades de la base de dades. Sense ell, tots els leads eixirien buits.
- **Propietats `private`**: PDO les pot escriure directament, sense passar pels *setters*.
- **Conversió de tipus**: `pressupost` arriba de MySQL com a cadena (`"4500.00"`), però com que la propietat és `float`, PHP la converteix. La columna `serveis` arriba com a text JSON i es guarda tal qual: `getServeis()` s'encarrega de decodificar-la (punt 4).

::: warning Atenció
Si la consulta retorna una columna que **no té propietat** a la classe (per exemple, `data_creacio` amb un `SELECT *`), PHP 8.2 o superior mostra un avís de propietat dinàmica obsoleta. Per això indiquem les columnes una a una en lloc d'usar `SELECT *`.
:::

::: info Nota
`FETCH_CLASS` és còmode, però acobla els noms de les propietats als noms de les columnes: si canvies el nom d'una columna, has de canviar la propietat. En classes amb conversions més complexes, el mapatge manual (fila → constructor) pot ser preferible.
:::

### 6.9. Practicar amb PDO

Crea un fitxer temporal `public/prova-pdo.php` amb este codi i obri `http://localhost:8080/prova-pdo.php`:

```php
<?php
use App\Model\Lead;
require_once __DIR__ . '/../vendor/autoload.php';

$dsn = 'mysql:host=db;dbname=techleads;charset=utf8mb4';
$opcions = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, 'techleads', 'techleads', $opcions);

    // 1. Llistar tots els leads
    echo '<h3>Tots els leads</h3><pre>';
    print_r($pdo->query('SELECT id, nom, serveis, estat FROM leads')->fetchAll());
    echo '</pre>';

    // 2. Un lead pel seu id (consulta preparada)
    $stmt = $pdo->prepare('SELECT * FROM leads WHERE id = :id');
    $stmt->execute(['id' => 1]);
    echo '<h3>Lead 1</h3><pre>';
    print_r($stmt->fetch());
    echo '</pre>';

    // 3. Inserir i eliminar un lead de prova
    $stmt = $pdo->prepare(
        'INSERT INTO leads (nom, email, serveis, pressupost) VALUES (:nom, :email, :serveis, :pressupost)'
    );
    $stmt->execute([
        'nom'        => 'Prova',
        'email'      => 'prova@exemple.com',
        'serveis'    => json_encode(['SEO']),
        'pressupost' => 100,
    ]);
    $idNou = (int) $pdo->lastInsertId();
    echo "<p>Lead inserit amb id $idNou</p>";

    $stmt = $pdo->prepare('DELETE FROM leads WHERE id = :id');
    $stmt->execute(['id' => $idNou]);
    echo '<p>Files eliminades: ' . $stmt->rowCount() . '</p>';

    // 4. Convertir files en objectes Lead
    $stmt = $pdo->query('SELECT id, nom, email, telefon, empresa, serveis, pressupost, missatge, document, estat FROM leads');
    foreach ($stmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, Lead::class) as $lead) {
        echo $lead->getNom() . ': ' . implode(', ', $lead->getServeis()) . '<br>';
    }
} catch (PDOException $e) {
    echo '<p>Error: ' . $e->getMessage() . '</p>';
}
```

Has de vore els tres leads d'exemple del punt 2, el detall del lead 1, i que la fila de prova s'insereix i s'elimina.

Després, experimenta:

- Canvia el `host` per `localhost` i observa l'error.
- Canvia el nom d'una columna per un de inexistent i observa el missatge.
- Prova a canviar `'id' => 1` per `'id' => '1 OR 1=1'` en la consulta preparada: només tornarà el lead 1 (o cap), mai tots. Compara-ho amb concatenar el valor a l'SQL.

**Esborra el fitxer** quan acabes: no ha de quedar cap credencial en una pàgina pública.

---

📌 **A recordar:** PDO es connecta amb un DSN (`mysql:host=db;dbname=techleads;charset=utf8mb4`) i s'ha de configurar amb excepcions, `FETCH_ASSOC` i `EMULATE_PREPARES` desactivat. Les dades externes **sempre** van en consultes preparades (`prepare` + `execute`), mai concatenades. `lastInsertId()` dona l'`id` generat, i els errors es capturen amb `PDOException`.

📖 Documentació oficial

[PHP: PDO](https://www.php.net/manual/es/book.pdo.php) · [PHP: Sentències preparades](https://www.php.net/manual/es/pdo.prepared-statements.php) · [PHP: PDOException](https://www.php.net/manual/es/class.pdoexception.php) · [OWASP: Injecció SQL](https://owasp.org/www-community/attacks/SQL_Injection)

## 7. La classe `DBConnection` i la injecció de dependències

Al punt anterior hem creat l'objecte `PDO` directament dins d'un script. Si fem això a cada lloc on necessitem la base de dades, tindrem l'usuari i la contrasenya repetits per tot el projecte. En este punt trobarem un lloc únic on crear la connexió i la forma correcta de fer-la arribar a qui la necessite.

### 7.1. El problema: qui crea la connexió?

Hi ha tres formes d'abordar-ho:

| Opció | Com funciona | Problema |
| --- | --- | --- |
| **Cada DAO crea la seua connexió** | `new PDO(...)` dins de `LeadDAO` i de `UsuariDAO` | Credencials duplicades; per a canviar-les cal tocar totes les classes |
| **Connexió global o estàtica** | `DBConnection::getInstance()` des de qualsevol lloc | Dependència oculta, igual que `GestorLead::anyadirLead()` a la unitat 3: no es veu al constructor i és difícil de substituir |
| **Injecció de dependències** | La classe **rep** la connexió ja feta | Cap: és la solució que usarem |

### 7.2. Injecció de dependències

Una **dependència** és qualsevol objecte que una classe necessita per a funcionar. La idea de la **injecció de dependències** és simple: una classe **no crea** les seues dependències, sinó que **les rep** (normalment pel constructor).

```php
// Sense injecció: LeadDAO crea la seua pròpia connexió
class LeadDAO
{
    public function __construct()
    {
        $this->pdo = new PDO('mysql:host=db;...', 'techleads', 'techleads');
    }
}

// Amb injecció: LeadDAO la rep ja feta
class LeadDAO
{
    public function __construct(private DBConnection $connexio)
    {
    }
}
```

Què hi guanyem:

- **Un únic lloc de configuració**: les credencials viuen en un sol fitxer.
- **Dependències visibles**: només mirant el constructor sabem què necessita la classe.
- **Flexibilitat**: podem passar-li una connexió a una base de dades de proves sense tocar el DAO.

::: info Nota
La injecció de dependències és una de les bases de frameworks com Laravel, que té un **contenidor de serveis** que crea i injecta els objectes automàticament. Ací ho farem a mà per a entendre què hi ha darrere.
:::

### 7.3. El fitxer de configuració

Les dades de connexió es guarden en un fitxer a part. Crea `config/database.php`:

```php
<?php

return [
    'host'        => 'db',
    'base_dades'  => 'techleads',
    'usuari'      => 'techleads',
    'contrasenya' => 'techleads',
    'charset'     => 'utf8mb4',
];
```

Un fitxer PHP pot **retornar** un valor amb `return`, i qui l'inclou el recull. Així, un array de configuració es llig d'esta forma:

```php
$config = require __DIR__ . '/../config/database.php';

echo $config['host'];   // db
```

::: warning Atenció
Este fitxer conté contrasenyes. En un projecte real **no es puja al repositori** (s'afig al `.gitignore`) i es proporciona un `database.example.php` sense dades reals. També és habitual llegir les credencials de **variables d'entorn** (`getenv()`), que es poden definir al `docker-compose.yml`. Ací ho deixem en un fitxer per simplicitat.
:::

### 7.4. La classe `DBConnection`

Crea la carpeta `src/Database/` i el fitxer `src/Database/DBConnection.php`:

```php
<?php

namespace App\Database;

use PDO;

class DBConnection
{
    private ?PDO $pdo = null;

    public function __construct(
        private string $host,
        private string $baseDades,
        private string $usuari,
        private string $contrasenya,
        private string $charset = 'utf8mb4'
    ) {
    }

    public function getPDO(): PDO
    {
        if ($this->pdo === null) {
            $dsn = "mysql:host={$this->host};dbname={$this->baseDades};charset={$this->charset}";

            $this->pdo = new PDO($dsn, $this->usuari, $this->contrasenya, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);
        }

        return $this->pdo;
    }
}
```

Els punts clau:

- **El constructor només rep dades**: no connecta. Les opcions de PDO del punt 6.3 queden **centralitzades** ací i no es repeteixen enlloc més.
- **Connexió *lazy* (mandrosa)**: la connexió real no s'obri fins que algú crida a `getPDO()` per primera vegada, i les crides següents **reutilitzen** el mateix objecte. Obrir una connexió costa, i hi ha pàgines que potser no la necessiten.
- **`use PDO;`**: dins d'un espai de noms, cal importar la classe nativa `PDO`, o PHP la buscaria dins de `App\Database`.
- **No capturem la `PDOException`** ací: si falla la connexió, l'excepció puja fins a qui ha demanat la connexió (les pàgines), que és qui sap què mostrar a l'usuari.

::: tip Bona pràctica
Esta classe té **una sola responsabilitat**: crear i entregar la connexió. No fa consultes ni sap res de leads. Cada classe fa una sola cosa.
:::

### 7.5. On es crea la connexió: el punt d'arrancada

Algú ha de crear l'objecte `DBConnection` i passar-lo als DAO. Eixe lloc ha de ser **un sol punt** de l'aplicació, no cada pàgina per separat. Crea `config/bootstrap.php`:

```php
<?php

use App\Database\DBConnection;

require_once __DIR__ . '/../vendor/autoload.php';

session_start();

$config = require __DIR__ . '/database.php';

$connexio = new DBConnection(
    host: $config['host'],
    baseDades: $config['base_dades'],
    usuari: $config['usuari'],
    contrasenya: $config['contrasenya'],
    charset: $config['charset']
);
```

Este fitxer fa tres coses: carrega l'autoload, inicia la sessió i **construïx els objectes de l'aplicació**. Al punt 8 hi afegirem els DAO:

```php
// (punt 8) Cada DAO rep la connexió
$leadDAO = new LeadDAO($connexio);
$usuariDAO = new UsuariDAO($connexio);
```

Observa el patró: les dependències es construïxen **de fora cap a dins**. Primer la connexió, després els DAO que la necessiten, i les pàgines reben els DAO ja fets.

::: info Nota
Fins ara cada pàgina començava amb `require_once` de l'autoload i `session_start()`. Al punt 10, quan reescriguem les pàgines, ho substituirem per una única línia: `require_once __DIR__ . '/../config/bootstrap.php';`.
:::

### 7.6. Comprovar la connexió

Modifica el fitxer temporal `public/prova-pdo.php` (o crea'n un de nou `public/prova-connexio.php`) amb este codi:

```php
<?php

require_once __DIR__ . '/../config/bootstrap.php';

try {
    $pdo = $connexio->getPDO();

    echo 'Leads a la base de dades: ';
    echo $pdo->query('SELECT COUNT(*) FROM leads')->fetchColumn();

    echo '<br>Mateixa connexió reutilitzada? ';
    var_dump($connexio->getPDO() === $pdo);
} catch (PDOException $e) {
    echo 'Error: ' . $e->getMessage();
}
```

Obri `http://localhost:8080/prova-connexio.php`. Has de vore el nombre de leads (3 si no n'has afegit cap altre) i `bool(true)`.

`fetchColumn()` és una drecera de PDO per a llegir **un únic valor** (la primera columna de la primera fila), ideal per a `COUNT(*)`.

Proves per a entendre l'error:

- Canvia la contrasenya a `config/database.php` i recarrega: veuràs un `Access denied`.
- Canvia el `host` a `localhost`: veuràs que no es pot connectar.

Restaura els valors correctes i **esborra els fitxers de prova** (`prova-model.php`, `prova-pdo.php`, `prova-connexio.php`).

---

📌 **A recordar:** `DBConnection` guarda les dades de connexió i crea l'objecte `PDO` només quan es necessita (*lazy*). La **injecció de dependències** consistix a passar les dependències a una classe pel constructor en lloc de crear-les a dins. El punt d'arrancada (`config/bootstrap.php`) construïx els objectes de l'aplicació de fora cap a dins: connexió → DAO → pàgines.

📖 Documentació oficial

[PHP: Constructors i destructors](https://www.php.net/manual/es/language.oop5.decon.php) · [PHP: `include`/`require` i valors retornats](https://www.php.net/manual/es/function.include.php) · [PHP-DI: Understanding dependency injection](https://php-di.org/doc/understanding-di.html)

## 8. Els DAO: `LeadDAO` i `UsuariDAO`

Ara ho unim tot. Un **DAO** (*Data Access Object*) és la classe que s'encarrega d'**accedir a la base de dades per a una entitat concreta**: sap quines consultes cal fer i converteix files en objectes del model i objectes en files.

| Peça | Responsabilitat |
| --- | --- |
| `Lead`, `Usuari` (model) | Guarden dades i regles de negoci |
| `DBConnection` | Entrega la connexió PDO |
| `IDbAccess` | Defineix el contracte (CRUD) |
| **`LeadDAO`, `UsuariDAO`** | **Implementen el contracte per a MySQL** |

Els DAO substituiran `GestorLead` (i l'array `$usuaris`) de la unitat 3.

### 8.1. `LeadDAO`

Crea `src/DAO/LeadDAO.php`:

```php
<?php

namespace App\DAO;

use App\Database\DBConnection;
use App\Model\Lead;
use InvalidArgumentException;
use PDO;

/**
 * @implements IDbAccess<Lead>
 */
class LeadDAO implements IDbAccess
{
    // Columnes que es converteixen en propietats de Lead (sense data_creacio)
    private const COLUMNES = 'id, nom, email, telefon, empresa, serveis, pressupost, missatge, document, estat';

    // Cada fila es converteix en un objecte Lead
    private const MODE = PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE;

    public function __construct(private DBConnection $connexio)
    {
    }

    public function findAll(): array
    {
        $stmt = $this->connexio->getPDO()->query(
            'SELECT ' . self::COLUMNES . ' FROM leads ORDER BY data_creacio DESC, id DESC'
        );

        return $stmt->fetchAll(self::MODE, Lead::class);
    }

    public function findById(int $id): ?Lead
    {
        $stmt = $this->connexio->getPDO()->prepare(
            'SELECT ' . self::COLUMNES . ' FROM leads WHERE id = :id'
        );
        $stmt->setFetchMode(self::MODE, Lead::class);
        $stmt->execute(['id' => $id]);

        $lead = $stmt->fetch();

        return $lead === false ? null : $lead;
    }

    public function insert(object $entitat): int
    {
        $lead = $this->comprovarLead($entitat);
        $pdo = $this->connexio->getPDO();

        $stmt = $pdo->prepare(
            'INSERT INTO leads (nom, email, telefon, empresa, serveis, pressupost, missatge, document, estat)
             VALUES (:nom, :email, :telefon, :empresa, :serveis, :pressupost, :missatge, :document, :estat)'
        );
        $stmt->execute($this->obtenirParametres($lead));

        return (int) $pdo->lastInsertId();
    }

    public function update(object $entitat): void
    {
        $lead = $this->comprovarLead($entitat);

        if ($lead->getId() === null) {
            throw new InvalidArgumentException('No es pot actualitzar un lead sense id.');
        }

        $stmt = $this->connexio->getPDO()->prepare(
            'UPDATE leads
             SET nom = :nom, email = :email, telefon = :telefon, empresa = :empresa,
                 serveis = :serveis, pressupost = :pressupost, missatge = :missatge,
                 document = :document, estat = :estat
             WHERE id = :id'
        );
        $stmt->execute($this->obtenirParametres($lead) + ['id' => $lead->getId()]);
    }

    public function delete(int $id): void
    {
        $stmt = $this->connexio->getPDO()->prepare('DELETE FROM leads WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }

    /**
     * Comprova que l'entitat és un Lead (el instanceof del punt 5).
     */
    private function comprovarLead(object $entitat): Lead
    {
        if (!$entitat instanceof Lead) {
            throw new InvalidArgumentException('LeadDAO només accepta objectes Lead.');
        }

        return $entitat;
    }

    /**
     * Converteix un Lead en els paràmetres de les consultes.
     */
    private function obtenirParametres(Lead $lead): array
    {
        return [
            'nom'        => $lead->getNom(),
            'email'      => $lead->getEmail(),
            'telefon'    => $lead->getTelefon(),
            'empresa'    => $lead->getEmpresa(),
            'serveis'    => json_encode($lead->getServeis(), JSON_UNESCAPED_UNICODE),
            'pressupost' => $lead->getPressupost(),
            'missatge'   => $lead->getMissatge(),
            'document'   => $lead->getDocument(),
            'estat'      => $lead->getEstat(),
        ];
    }
}
```

Algunes claus per a entendre la classe:

- **`implements IDbAccess`** + **`@implements IDbAccess<Lead>`**: la classe signa el contracte del punt 5 i indica a l'editor que treballa amb `Lead`.
- **El constructor rep `DBConnection`**: és la injecció de dependències del punt 7. El DAO **no sap** on està la base de dades ni quines credencials hi ha.
- **`fetchAll(self::MODE, Lead::class)`**: PDO construïx directament els objectes `Lead` (punt 6.8). No cal cap bucle ni cap conversió manual dins del DAO.
- **`COLUMNES`**: enumera les columnes que volem, perquè coincidisquen amb les propietats de `Lead`. És una constant escrita per nosaltres, no una dada de l'usuari, per això es pot concatenar a l'SQL amb seguretat.
- **`findById()` retorna `?Lead`**: `fetch()` dona `false` si no hi ha cap fila i el convertim en `null`. El tipus de retorn és més concret que `?object` de la interfície, i és vàlid.
- **`comprovarLead()`** conté el `instanceof` del punt 5. Si algú intenta passar-li un `Usuari`, llança una excepció clara. Retorna l'objecte ja com a `Lead`, de manera que a partir d'eixe moment l'editor coneix el tipus.
- **`obtenirParametres()`** evita repetir els nou paràmetres a `insert()` i a `update()`. A `update()` s'hi afig l'`id` amb l'operador `+` d'arrays (unió).
- **L'ordre del llistat**: del lead més recent al més antic. `id DESC` desfà els empats quan dos leads s'han creat en el mateix segon.

::: info Nota
Fixa't que **tota la lògica SQL està en esta classe**. Les pàgines no tindran ni una sola consulta: només cridaran a `findAll()`, `insert()`, etc.
:::

::: tip Bona pràctica
Els mètodes `private` (`comprovarLead`, `obtenirParametres`) són detalls interns del DAO. Des de fora, només es veu el contracte públic de `IDbAccess`.
:::

### 8.2. Un ajust a `Usuari`

Al punt 4 vam decidir que `Usuari` **no exposara** el hash de la contrasenya. Ara, però, el DAO necessita llegir-lo per a **guardar-lo** en la base de dades. Afig este mètode a `src/Model/Usuari.php`:

```php
    /**
     * Retorna el hash de la contrasenya. Només l'ha d'usar el DAO per a guardar-lo.
     */
    public function getHash(): string
    {
        return $this->contrasenya;
    }
```

La comprovació de contrasenyes continua fent-se **només** amb `verificarContrasenya()`: el hash no s'utilitza per a res més.

::: warning Atenció
`Usuari` guarda **sempre el hash**, mai la contrasenya en text pla. Per tant, el hash s'ha de calcular **abans** de crear l'objecte:

```php
$usuari = new Usuari('pau', password_hash('secret123', PASSWORD_DEFAULT), Usuari::ROL_COMERCIAL);
```
:::

### 8.3. `UsuariDAO`

Crea `src/DAO/UsuariDAO.php`. Té la mateixa estructura que `LeadDAO`, més un mètode propi, `findByUsuari()`, que necessitarem per al *login*:

```php
<?php

namespace App\DAO;

use App\Database\DBConnection;
use App\Model\Usuari;
use InvalidArgumentException;
use PDO;

/**
 * @implements IDbAccess<Usuari>
 */
class UsuariDAO implements IDbAccess
{
    private const COLUMNES = 'id, usuari, contrasenya, rol';
    private const MODE = PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE;

    public function __construct(private DBConnection $connexio)
    {
    }

    public function findAll(): array
    {
        $stmt = $this->connexio->getPDO()->query(
            'SELECT ' . self::COLUMNES . ' FROM usuaris ORDER BY usuari'
        );

        return $stmt->fetchAll(self::MODE, Usuari::class);
    }

    public function findById(int $id): ?Usuari
    {
        $stmt = $this->connexio->getPDO()->prepare(
            'SELECT ' . self::COLUMNES . ' FROM usuaris WHERE id = :id'
        );
        $stmt->setFetchMode(self::MODE, Usuari::class);
        $stmt->execute(['id' => $id]);

        $usuari = $stmt->fetch();

        return $usuari === false ? null : $usuari;
    }

    /**
     * Mètode propi d'este DAO: busca un usuari pel seu nom d'accés.
     */
    public function findByUsuari(string $nomUsuari): ?Usuari
    {
        $stmt = $this->connexio->getPDO()->prepare(
            'SELECT ' . self::COLUMNES . ' FROM usuaris WHERE usuari = :usuari'
        );
        $stmt->setFetchMode(self::MODE, Usuari::class);
        $stmt->execute(['usuari' => $nomUsuari]);

        $usuari = $stmt->fetch();

        return $usuari === false ? null : $usuari;
    }

    public function insert(object $entitat): int
    {
        $usuari = $this->comprovarUsuari($entitat);
        $pdo = $this->connexio->getPDO();

        $stmt = $pdo->prepare(
            'INSERT INTO usuaris (usuari, contrasenya, rol) VALUES (:usuari, :contrasenya, :rol)'
        );
        $stmt->execute([
            'usuari'      => $usuari->getUsuari(),
            'contrasenya' => $usuari->getHash(),
            'rol'         => $usuari->getRol(),
        ]);

        return (int) $pdo->lastInsertId();
    }

    public function update(object $entitat): void
    {
        $usuari = $this->comprovarUsuari($entitat);

        if ($usuari->getId() === null) {
            throw new InvalidArgumentException('No es pot actualitzar un usuari sense id.');
        }

        $stmt = $this->connexio->getPDO()->prepare(
            'UPDATE usuaris SET usuari = :usuari, contrasenya = :contrasenya, rol = :rol WHERE id = :id'
        );
        $stmt->execute([
            'usuari'      => $usuari->getUsuari(),
            'contrasenya' => $usuari->getHash(),
            'rol'         => $usuari->getRol(),
            'id'          => $usuari->getId(),
        ]);
    }

    public function delete(int $id): void
    {
        $stmt = $this->connexio->getPDO()->prepare('DELETE FROM usuaris WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }

    private function comprovarUsuari(object $entitat): Usuari
    {
        if (!$entitat instanceof Usuari) {
            throw new InvalidArgumentException('UsuariDAO només accepta objectes Usuari.');
        }

        return $entitat;
    }
}
```

`findByUsuari()` és un exemple de com un DAO pot **ampliar** el contracte amb consultes pròpies. Estes consultes no estan a la interfície perquè no tenen sentit per a totes les entitats (un `Lead` no té «nom d'accés»).

::: info Nota
Si s'intenta fer `insert()` d'un usuari el nom del qual ja existeix, MySQL llançarà una `PDOException` amb codi `23000` (clau duplicada), gràcies a la restricció `UNIQUE` del punt 2. El DAO no la captura: qui el use decidirà què mostrar a l'usuari.
:::

### 8.4. Connectar els DAO al punt d'arrancada

Completem `config/bootstrap.php` del punt 7. Afig els `use` a dalt i els DAO al final:

```php
<?php

use App\DAO\LeadDAO;
use App\DAO\UsuariDAO;
use App\Database\DBConnection;

require_once __DIR__ . '/../vendor/autoload.php';

session_start();

$config = require __DIR__ . '/database.php';

$connexio = new DBConnection(
    host: $config['host'],
    baseDades: $config['base_dades'],
    usuari: $config['usuari'],
    contrasenya: $config['contrasenya'],
    charset: $config['charset']
);

$leadDAO = new LeadDAO($connexio);
$usuariDAO = new UsuariDAO($connexio);
```

Cada DAO rep **la mateixa connexió**. Les pàgines que incloguen `bootstrap.php` disposaran de `$leadDAO` i `$usuariDAO` ja preparats.

### 8.5. Retirar `GestorLead`

`GestorLead` ja no té raó de ser: guardava els leads a la sessió, i ara ho fa `LeadDAO`. Però tenia un mètode que no és d'accés a dades: `getColorFromEstat()`, que decidix el color d'una insígnia segons l'estat. És una qüestió de **presentació**, no de dades, així que passa a `src/helpers.php` com a funció.

Afig `use App\Model\Lead;` a dalt del fitxer (just després de `<?php`) i esta funció al final:

```php
function colorEstat(string $estat): string
{
    return match ($estat) {
        Lead::ESTAT_NOU => 'emerald',
        Lead::ESTAT_CONTACTAT => 'amber',
        Lead::ESTAT_TANCAT => 'sky',
        default => 'sky',
    };
}
```

Després **elimina** el fitxer `src/Model/GestorLead.php`. Els usos de `GestorLead::...` que queden a les pàgines els substituirem al punt 10.

::: info Nota
`config/usuaris.php` encara existix: el *login* el continua usant fins al punt 11, quan el substituirà `UsuariDAO`.
:::

### 8.6. Comprovar els DAO

Crea un fitxer temporal `public/prova-dao.php`:

```php
<?php

use App\Model\Lead;

require_once __DIR__ . '/../config/bootstrap.php';

try {
    // 1. Llistar
    echo '<h3>Llistat</h3>';
    foreach ($leadDAO->findAll() as $lead) {
        echo '#' . $lead->getId() . ' ' . $lead->getNom()
            . ' [' . $lead->getEstat() . '] '
            . implode(', ', $lead->getServeis()) . '<br>';
    }

    // 2. Inserir
    $nou = new Lead('Prova', 'prova@exemple.com', null, null, ['SEO', 'Cloud'], 250.5, null, null);
    $id = $leadDAO->insert($nou);
    echo "<p>Inserit amb id $id</p>";

    // 3. Avançar l'estat i guardar-lo
    $lead = $leadDAO->findById($id);
    $lead->avancarEstat();
    $leadDAO->update($lead);
    echo '<p>Nou estat: ' . $leadDAO->findById($id)->getEstat() . '</p>';

    // 4. Eliminar
    $leadDAO->delete($id);
    echo '<p>Després d\'eliminar: ';
    var_dump($leadDAO->findById($id));
    echo '</p>';

    // 5. Usuari
    $ana = $usuariDAO->findByUsuari('ana');
    echo '<p>Contrasenya d\'ana correcta? ';
    var_dump($ana->verificarContrasenya('contrasenya123'));
    echo '</p>';

    // 6. El instanceof protegix els DAO
    $leadDAO->insert($ana);
} catch (Exception $e) {
    echo '<p>' . get_class($e) . ': ' . $e->getMessage() . '</p>';
}
```

Resultat esperat:

1. Els tres leads d'exemple.
2. Un lead nou amb un `id` (4 si no n'has afegit més).
3. L'estat `contactat`.
4. `NULL` en intentar recuperar el lead eliminat.
5. `bool(true)` per a la contrasenya d'`ana`.
6. Al final, una excepció: `InvalidArgumentException: LeadDAO només accepta objectes Lead.`, que és el que volem veure.

**Esborra els fitxers de prova** quan acabes.

---

📌 **A recordar:** un DAO implementa `IDbAccess` per a una entitat: executa les consultes i deixa que PDO construïsca els objectes amb `FETCH_CLASS | FETCH_PROPS_LATE`. Per això el model ha de tindre propietats amb el nom de les columnes i un constructor sense paràmetres obligatoris. Comprova amb `instanceof` que rep l'entitat correcta, rep `DBConnection` pel constructor i es construïx a `bootstrap.php`.

📖 Documentació oficial

[PHP: Interfícies d'objectes](https://www.php.net/manual/es/language.oop5.interfaces.php) · [PHP: `json_encode`](https://www.php.net/manual/es/function.json-encode.php) · [PHP: `json_decode`](https://www.php.net/manual/es/function.json-decode.php) · [PHP: PDOStatement::fetch](https://www.php.net/manual/es/pdostatement.fetch.php)


## 9. Funcions auxiliars: pujar i eliminar arxius

A la unitat 3, `processar_lead.php` tenia unes 35 línies dedicades només a validar i guardar el document adjunt. Ara necessitarem eixa mateixa lògica **en dos llocs** (afegir un lead i editar-lo, punt 10) i, a més, haurem de poder **eliminar** l'arxiu quan s'esborre el lead. Copiar i apegar codi no és una opció: el traiem a **funcions auxiliars**.

Les posarem a `src/helpers.php`, el fitxer que ja carrega Composer automàticament (punt 3) i on tenim `e()` i `colorEstat()`.

### 9.1. Dissenyar `pujarArxiu()`

Abans d'escriure-la, decidim què rep i què retorna:

|  Acció  |  Descripció  |
| --- | --- |
| **Rep** | L'element de `$_FILES` (`$_FILES['document']`), la carpeta de destí, els tipus permesos i la grandària màxima |
| **Retorna** | El **nom** amb què s'ha guardat l'arxiu, o `null` si l'usuari no n'ha triat cap |
| **Si hi ha un error** | Llança una `RuntimeException` amb el missatge per a l'usuari |

Dos decisions importants:

- **`null` si no hi ha arxiu**: el document és opcional. No triar-ne cap no és un error, i `null` és el valor que volem guardar a la columna `document` (millor que una cadena buida).
- **Excepcions per als errors**: igual que `PDOException` al punt 6, un error s'indica amb una excepció. La pàgina la captura amb `try/catch` i mostra el missatge.

### 9.2. La funció

Afig al final de `src/helpers.php`:

```php
/**
 * Puja un arxiu rebut d'un formulari a la carpeta indicada.
 *
 * @param array  $arxiu            Element de $_FILES (p. ex. $_FILES['document'])
 * @param string $carpetaDesti     Carpeta on es guardarà l'arxiu
 * @param array  $tipusPermesos    Tipus MIME permesos i l'extensió que els correspon
 * @param int    $grandariaMaxima  Grandària màxima en bytes
 *
 * @return string|null Nom de l'arxiu guardat, o null si no s'ha triat cap arxiu
 *
 * @throws RuntimeException Si l'arxiu no és vàlid o no es pot guardar
 */
function pujarArxiu(
    array $arxiu,
    string $carpetaDesti,
    array $tipusPermesos = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'application/pdf' => 'pdf'],
    int $grandariaMaxima = 2 * 1024 * 1024
): ?string {
    $codiError = $arxiu['error'] ?? UPLOAD_ERR_NO_FILE;
    $missatgeGrandaria = 'L\'arxiu no pot superar els ' . ($grandariaMaxima / 1024 / 1024) . ' MB.';

    // L'arxiu és opcional: si no s'ha triat cap, no és un error
    if ($codiError === UPLOAD_ERR_NO_FILE) {
        return null;
    }

    // PHP ja ha rebutjat l'arxiu perquè supera els límits de php.ini o del formulari
    if ($codiError === UPLOAD_ERR_INI_SIZE || $codiError === UPLOAD_ERR_FORM_SIZE) {
        throw new RuntimeException($missatgeGrandaria);
    }

    if ($codiError !== UPLOAD_ERR_OK) {
        throw new RuntimeException('Hi ha un error al pujar el document.');
    }

    if ($arxiu['size'] > $grandariaMaxima) {
        throw new RuntimeException($missatgeGrandaria);
    }

    // Tipus REAL de l'arxiu, llegint-ne el contingut
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $tipusReal = $finfo->file($arxiu['tmp_name']);

    if (!isset($tipusPermesos[$tipusReal])) {
        throw new RuntimeException('Tipus d\'arxiu no permés.');
    }

    // L'extensió es deriva del tipus real detectat, no del nom original
    $nomArxiu = uniqid('doc_', true) . '.' . $tipusPermesos[$tipusReal];

    if (!move_uploaded_file($arxiu['tmp_name'], rtrim($carpetaDesti, '/') . '/' . $nomArxiu)) {
        throw new RuntimeException('Error en moure l\'arxiu.');
    }

    return $nomArxiu;
}
```

És el mateix que feia `processar_lead.php`, ordenat en una funció. Convé repassar les decisions de seguretat:

| Què | Com ho fem | Per què |
| --- | --- | --- |
| **Tipus de l'arxiu** | `finfo` llig el **contingut** | `$_FILES['type']` i l'extensió del nom els decidix qui puja l'arxiu: es poden falsificar |
| **Extensió final** | Es deriva del tipus MIME detectat | Així mai es guardarà un `.php` |
| **Nom de l'arxiu** | `uniqid()`, no el nom original | Evita sobreescriure arxius i caràcters estranys |
| **Grandària** | Es comprova al servidor | El límit del formulari HTML només es respecta si el navegador vol |
| **Guardar** | `move_uploaded_file()` | Verifica que l'arxiu ve realment d'una pujada HTTP |

::: warning Atenció
Els arxius de `public/pujades/` són accessibles des del navegador. Per això és essencial que **només** puguen guardar-se extensions inofensives (`jpg`, `png`, `pdf`). Si es permetera guardar un `.php`, qualsevol podria executar-lo visitant la seua URL.
:::

::: info Nota
La clau de l'array de tipus permesos és el tipus **MIME**, i el d'un PDF és `application/pdf` (no `pdf`). Si apareguera mal escrita, els PDF es rebutjarien sempre amb «Tipus d'arxiu no permés».
:::

::: info Nota
El límit `upload_max_filesize` de PHP és, per defecte, de 2 MB. Un arxiu que el supere ni tan sols arriba a la nostra comprovació de grandària: PHP l'ha rebutjat abans amb l'error `UPLOAD_ERR_INI_SIZE`. Per això la funció tracta també eixe codi com un error de grandària.
:::

### 9.3. La funció `eliminarArxiu()`

Quan s'elimine un lead, el seu document ha de desaparéixer també del disc. I si el lead no arriba a guardar-se (per exemple, per un error de MySQL) després d'haver pujat l'arxiu, este quedaria **orfe**. Les dos situacions necessiten esborrar un arxiu:

```php
/**
 * Elimina un arxiu de la carpeta indicada, si existeix.
 */
function eliminarArxiu(string $carpeta, ?string $nomArxiu): void
{
    if ($nomArxiu === null || $nomArxiu === '') {
        return;
    }

    // basename() evita que un nom manipulat isca de la carpeta (p. ex. "../config/database.php")
    $ruta = rtrim($carpeta, '/') . '/' . basename($nomArxiu);

    if (is_file($ruta)) {
        unlink($ruta);
    }
}
```

- Accepta `?string` perquè un lead pot no tindre document (`null`): en eixe cas no fa res.
- **`basename()`** s'queda només amb el nom de l'arxiu. Encara que el nom vinga de la nostra base de dades, no es deu confiar mai en una ruta per a esborrar fitxers.

### 9.4. La carpeta de pujades

Les pàgines estan a profunditats diferents (`public/` i `public/dashboard/`), de manera que escriure la ruta de `pujades/` en cada una seria propens a errors. La definim **una sola vegada** com a constant, al final de `config/bootstrap.php`:

```php
const CARPETA_PUJADES = __DIR__ . '/../public/pujades';
```

Ara qualsevol pàgina pot escriure `CARPETA_PUJADES` i obtindre la ruta correcta.

### 9.5. Com s'usarà

Al punt 10 el codi d'`processar_lead.php` quedarà així de curt:

```php
try {
    $document = pujarArxiu($_FILES['document'] ?? [], CARPETA_PUJADES);
} catch (RuntimeException $e) {
    $errors['document'] = $e->getMessage();
}
```

Les ~35 línies del punt anterior s'han convertit en 5.

### 9.6. Comprovar les funcions

Crea un fitxer temporal `public/prova-arxiu.php`:

```php
<?php

require_once __DIR__ . '/../config/bootstrap.php';

$missatge = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $nom = pujarArxiu($_FILES['document'] ?? [], CARPETA_PUJADES);

        if ($nom === null) {
            $missatge = 'No has triat cap arxiu.';
        } else {
            $ruta = CARPETA_PUJADES . '/' . $nom;
            $existia = is_file($ruta);
            eliminarArxiu(CARPETA_PUJADES, $nom);

            $missatge = 'Pujat com ' . $nom
                . ' | Existia: ' . var_export($existia, true)
                . ' | Existix després d\'eliminar: ' . var_export(is_file($ruta), true);
        }
    } catch (RuntimeException $e) {
        $missatge = 'Error: ' . $e->getMessage();
    }
}
?>
<form method="POST" enctype="multipart/form-data">
    <input type="file" name="document">
    <button type="submit">Pujar</button>
</form>
<p><?= htmlspecialchars($missatge) ?></p>
```

Obri `http://localhost:8080/prova-arxiu.php` i prova aquests casos:

| Prova | Resultat esperat |
| --- | --- |
| Cap arxiu | `No has triat cap arxiu.` |
| Una imatge JPG o PNG | Pujat; existia `true`; després d'eliminar `false` |
| Un PDF | El mateix |
| Un arxiu `.txt` | `Tipus d'arxiu no permés.` |
| Un arxiu de text **renomenat** a `.png` | `Tipus d'arxiu no permés.` (es mira el contingut, no el nom) |
| Un arxiu de més de 2 MB | `L'arxiu no pot superar els 2 MB.` |

La prova de l'arxiu renomenat és la més reveladora: demostra per què mirem el contingut i no l'extensió.

**Esborra el fitxer** `prova-arxiu.php` quan acabes.

---

📌 **A recordar:** les funcions auxiliars viuen a `src/helpers.php` i es carreguen soles amb Composer. `pujarArxiu()` valida el tipus **real** (amb `finfo`), la grandària i guarda l'arxiu amb un nom únic; retorna el nom, `null` si no hi ha arxiu, o llança una `RuntimeException`. `eliminarArxiu()` esborra de manera segura l'arxiu d'un lead eliminat o d'un lead que no s'ha pogut guardar.

📖 Documentació oficial

[PHP: Pujada de fitxers](https://www.php.net/manual/es/features.file-upload.php) · [PHP: finfo](https://www.php.net/manual/es/class.finfo.php) · [PHP: move_uploaded_file](https://www.php.net/manual/es/function.move-uploaded-file.php) · [PHP: Excepcions](https://www.php.net/manual/es/language.exceptions.php)

## 10. El CRUD de leads

Ja tenim totes les peces: les taules, el model, el contracte, els DAO i les funcions auxiliars. Ara fem que **les pàgines** les utilitzen. Un CRUD agrupa les quatre operacions bàsiques sobre les dades:

| Operació | Pàgina | Mètode del DAO |
| --- | --- | --- |
| **C**reate (crear) | Formulari públic (`index.php` + `processar_lead.php`) | `insert()` |
| **R**ead (llistat) | `dashboard/leads.php` | `findAll()` |
| **R**ead (detall) | `dashboard/lead.php?id=...` | `findById()` |
| **U**pdate (editar) | `dashboard/editar.php?id=...` i canvi d'estat | `update()` |
| **D**elete (eliminar) | Acció des del llistat | `delete()` |

Esta primera part cobrix la lectura: el **llistat** i el **detall**. La creació, l'edició i l'eliminació les farem a la segona part.

### 10.1. Les pàgines usen el punt d'arrancada

Fins ara cada pàgina començava carregant l'autoload i iniciant la sessió. Ara tot això ho fa `config/bootstrap.php` (punt 7), que a més ens dona `$leadDAO` i `$usuariDAO`. En cada pàgina, **substituïx** les línies inicials de l'autoload i de `session_start()` per una sola:

| Pàgina | Nova línia |
| --- | --- |
| `public/index.php` | `require_once __DIR__ . '/../config/bootstrap.php';` |
| `public/dashboard/login.php` | `require_once __DIR__ . '/../../config/bootstrap.php';` |
| `public/dashboard/leads.php` | `require_once __DIR__ . '/../../config/bootstrap.php';` |

::: warning Atenció
`bootstrap.php` ja crida a `session_start()`. Si en una pàgina es queda també la crida original, PHP mostrarà un avís de sessió ja iniciada.
:::

**`public/dashboard/logout.php`** es reescriu per complet. A la unitat 3 només esborràvem l'usuari de la sessió perquè **els leads vivien a la sessió** i no els volíem perdre. Ara estan a la base de dades, així que podem destruir la sessió sencera, que és la forma habitual:

```php
<?php

require_once __DIR__ . '/../../config/bootstrap.php';

// Els leads ja no estan a la sessió: podem destruir-la sencera
$_SESSION = [];
session_destroy();

setcookie('usuari', '', time() - 1, '/');

header('Location: login.php');
exit;
```

### 10.2. Reutilitzar la capçalera i el peu del panell

El llistat, el detall i (a la segona part) l'edició comparteixen el mateix `<head>`, la barra superior i el peu. Repetir ~100 línies en cada pàgina no té sentit: ho passem a **vistes**, igual que ja fem amb `capcalera.php` i `peu.php` de la part pública.

1. Crea `vistes/dashboard_capcalera.php` i copia-hi, des de `leads.php`, tot el que va **des de `<!DOCTYPE html>` fins a `</header>`** (inclosa la configuració de Tailwind i la barra amb l'usuari). Només cal canviar l'etiqueta del títol:

```php
<title><?= e($titol ?? 'TechLeads') ?></title>
```

2. Crea `vistes/dashboard_peu.php` i copia-hi, des de `leads.php`, tot el que va **des de `<!-- Peu de pàgina -->` (el `<footer>`) fins al final** (`</body></html>`).

La part de `<main>` queda a cada pàgina, perquè és el que canvia. El patró és el mateix que al punt 3: la pàgina carrega la capçalera, escriu el seu contingut i carrega el peu.

### 10.3. El llistat: `leads.php`

Substituïx **tot el que hi ha abans de `<main>`** (el bloc PHP inicial, el `<head>` i la barra superior) per:

```php
<?php

use App\Model\Lead;

require_once __DIR__ . '/../../config/bootstrap.php';

// Comprovar que l'usuari està autenticat
if (empty($_SESSION['usuari'])) {
    header('Location: login.php');
    exit;
}

$errorBD = null;
$llistaLeads = [];

try {
    // Si ve d'un POST: avançar l'estat d'un lead
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $idLead = (int) ($_POST['id'] ?? 0);

        if ($idLead !== 0) {
            $lead = $leadDAO->findById($idLead);

            if ($lead !== null) {
                $lead->avancarEstat();
                $leadDAO->update($lead);
            }
        }

        header('Location: leads.php');
        exit;
    }

    $llistaLeads = $leadDAO->findAll();
} catch (PDOException $e) {
    error_log($e->getMessage());
    $errorBD = 'No s\'ha pogut accedir a la base de dades.';
}

$titol = 'TechLeads - Panell de leads';
require_once __DIR__ . '/../../vistes/dashboard_capcalera.php';
?>
```

Compara-ho amb la unitat 3. La lògica és la mateixa, però ara:

- **El llistat** ve de `$leadDAO->findAll()`, en lloc de `GestorLead::obtenerListaLeads()`.
- **Avançar l'estat** és un procés en tres passos que ja vam preparar: es **recupera** el lead (`findById`), el **model** aplica la seua regla de negoci (`avancarEstat()`) i el DAO el **guarda** (`update`). Abans era `GestorLead::avancarEstat($id)`.
- **Les errades de MySQL** es capturen: es registren amb `error_log()` i l'usuari només veu un missatge genèric, tal com vam dir al punt 6.7.

Dins de `<main>`, afig just a l'inici el missatge d'error:

```php
<?php if ($errorBD !== null): ?>
    <div class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-medium text-rose-700">
        <?= e($errorBD) ?>
    </div>
<?php endif; ?>
```

Hi ha tres canvis més a la taula:

1. **El color de l'estat**: `GestorLead::getColorFromEstat(...)` passa a ser la funció `colorEstat(...)` del punt 8.5.

```php
$color = colorEstat($lead->getEstat());
```

2. **Un enllaç al detall**, a la cel·la d'accions. Embolica el formulari existent en un `div` i afig-hi l'enllaç:

```php
<td class="py-3.5 px-4 text-right">
    <div class="flex items-center justify-end gap-2">
        <a href="lead.php?id=<?= $lead->getId() ?>"
           class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-semibold bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 transition-colors">
            Detall
        </a>

        <!-- ... el formulari «Avança estat» tal com estava ... -->
    </div>
</td>
```

3. **El peu**: després de `</main>`, substituïx tot el bloc des de `<!-- Peu de pàgina -->` fins al final de l'arxiu per:

```php
<?php require_once __DIR__ . '/../../vistes/dashboard_peu.php';
```

::: tip Bona pràctica
Els noms de fitxer que arriben de la base de dades també s'han d'escapar en l'HTML. A l'enllaç del document, usa `e($lead->getDocument())` en lloc de `$lead->getDocument()`.
:::

::: info Nota
Les targetes de totals comproven els estats amb `array_filter()` sobre la llista completa, carregada en memòria. Amb pocs leads funciona bé; amb milers, seria més eficient que ho calculara MySQL amb `SELECT estat, COUNT(*) ... GROUP BY estat`.
:::

### 10.4. El detall: `lead.php`

Nova pàgina `public/dashboard/lead.php`, que mostra tota la informació d'un lead. L'`id` arriba per la URL (`lead.php?id=2`).

```php
<?php

require_once __DIR__ . '/../../config/bootstrap.php';

if (empty($_SESSION['usuari'])) {
    header('Location: login.php');
    exit;
}

// L'id ve de la URL: s'ha de validar com a enter
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if ($id === null || $id === false) {
    header('Location: leads.php');
    exit;
}

$errorBD = null;
$lead = null;

try {
    $lead = $leadDAO->findById($id);
} catch (PDOException $e) {
    error_log($e->getMessage());
    $errorBD = 'No s\'ha pogut accedir a la base de dades.';
}

if ($lead === null && $errorBD === null) {
    http_response_code(404);
}

$titol = 'TechLeads - Detall del lead';
require_once __DIR__ . '/../../vistes/dashboard_capcalera.php';
?>
<main class="max-w-3xl mx-auto px-6 py-8 flex-1 w-full space-y-6">

    <a href="leads.php" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-indigo-600 transition-colors">
        &larr; Tornar al llistat
    </a>

    <?php if ($errorBD !== null): ?>
        <div class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-medium text-rose-700">
            <?= e($errorBD) ?>
        </div>

    <?php elseif ($lead === null): ?>
        <div class="bg-white p-12 rounded-2xl shadow-xl shadow-slate-200/50 border border-slate-200/80 text-center space-y-2">
            <h1 class="text-xl font-extrabold text-slate-900">Lead no trobat</h1>
            <p class="text-sm text-slate-500">No existeix cap lead amb l'identificador indicat.</p>
        </div>

    <?php else: ?>
        <?php $color = colorEstat($lead->getEstat()); ?>
        <div class="bg-white rounded-2xl shadow-xl shadow-slate-200/60 border border-slate-200/80 overflow-hidden">

            <div class="flex items-start justify-between gap-4 px-6 py-5 border-b border-slate-100">
                <div>
                    <div class="font-mono text-xs font-semibold text-slate-400">#<?= $lead->getId() ?></div>
                    <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight"><?= e($lead->getNom()) ?></h1>
                </div>
                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-<?= $color ?>-50 text-<?= $color ?>-700 border border-<?= $color ?>-200">
                    <span class="h-1.5 w-1.5 rounded-full bg-<?= $color ?>-500"></span>
                    <?= e($lead->getEstat()) ?>
                </span>
            </div>

            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-5 px-6 py-6 text-sm">
                <div>
                    <dt class="text-xs font-bold uppercase tracking-wider text-slate-500">Correu electrònic</dt>
                    <dd class="mt-1">
                        <a href="mailto:<?= e($lead->getEmail()) ?>" class="text-indigo-600 hover:underline"><?= e($lead->getEmail()) ?></a>
                    </dd>
                </div>
                <div>
                    <dt class="text-xs font-bold uppercase tracking-wider text-slate-500">Telèfon</dt>
                    <dd class="mt-1 text-slate-800"><?= e($lead->getTelefon() ?: '--') ?></dd>
                </div>
                <div>
                    <dt class="text-xs font-bold uppercase tracking-wider text-slate-500">Empresa</dt>
                    <dd class="mt-1 text-slate-800"><?= e($lead->getEmpresa() ?: '--') ?></dd>
                </div>
                <div>
                    <dt class="text-xs font-bold uppercase tracking-wider text-slate-500">Pressupost</dt>
                    <dd class="mt-1 font-bold text-slate-800"><?= e(number_format($lead->getPressupost(), 2, ',', '.')) ?> €</dd>
                </div>
                <div class="sm:col-span-2">
                    <dt class="text-xs font-bold uppercase tracking-wider text-slate-500">Serveis</dt>
                    <dd class="mt-1 flex flex-wrap gap-1">
                        <?php foreach ($lead->getServeis() as $servei): ?>
                            <span class="px-2 py-0.5 rounded-md bg-slate-100 text-slate-700 text-xs font-medium"><?= e($servei) ?></span>
                        <?php endforeach; ?>
                    </dd>
                </div>
                <div class="sm:col-span-2">
                    <dt class="text-xs font-bold uppercase tracking-wider text-slate-500">Missatge</dt>
                    <dd class="mt-1 text-slate-800"><?= nl2br(e($lead->getMissatge() ?: '--')) ?></dd>
                </div>
                <div class="sm:col-span-2">
                    <dt class="text-xs font-bold uppercase tracking-wider text-slate-500">Document</dt>
                    <dd class="mt-1">
                        <?php if (!empty($lead->getDocument())): ?>
                            <a href="../pujades/<?= e($lead->getDocument()) ?>" class="text-indigo-600 hover:underline"><?= e($lead->getDocument()) ?></a>
                        <?php else: ?>
                            <span class="text-slate-800">--</span>
                        <?php endif; ?>
                    </dd>
                </div>
            </dl>
        </div>
    <?php endif; ?>

</main>

<?php require_once __DIR__ . '/../../vistes/dashboard_peu.php';
```

Els punts que convé comentar:

- **`filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT)`** llig `$_GET['id']` i el valida com a enter. Retorna `null` si no existeix i `false` si no és un enter. Un valor com `1 OR 1=1` o `abc` es rebutja ací, abans d'arribar al DAO.
- **Tres resultats possibles**: la base de dades falla (`$errorBD`), el lead no existeix (`$lead === null`, amb codi HTTP 404) o es mostra el lead.
- **`e()` a totes les dades** que venen de l'usuari: sense escapar, un lead amb `<script>` al missatge s'executaria en el panell.
- **`nl2br(e(...))`** conserva els salts de línia del missatge. L'ordre importa: primer s'escapa i després s'afigen els `<br>`.
- **`?:` en lloc de `??`** per als camps opcionals, perquè cobrix tant `null` com una cadena buida.

### 10.5. Comprovar el llistat i el detall

1. Entra a `http://localhost:8080/dashboard/login.php` amb `ana` / `contrasenya123`. El *login* continua usant `config/usuaris.php`: ho canviem al punt 11.
2. Has de vore els tres leads d'exemple del punt 2.
3. Prem **Avança estat** en un d'ells. Obri phpMyAdmin i comprova que la columna `estat` ha canviat a la taula `leads`.
4. Reinicia els contenidors amb `docker compose restart` i recarrega el llistat: **els canvis continuen ahí**. És la diferència fonamental respecte a la unitat 3, on tancar la sessió feia desaparéixer les dades.
5. Prem **Detall** en un lead i comprova totes les dades, inclosos els serveis.
6. Prova amb adreces incorrectes:

| Adreça | Resultat |
| --- | --- |
| `lead.php?id=999` | «Lead no trobat» (codi 404) |
| `lead.php?id=abc` | Redirigix al llistat |
| `lead.php` | Redirigix al llistat |

7. Tanca la sessió i comprova que, en intentar obrir `lead.php?id=1`, et redirigix al *login*.

::: danger Error crític
Encara **no envies el formulari públic**: `processar_lead.php` continua cridant a `GestorLead`, que ja no existix. El reescrivim a la segona part.
:::

---

📌 **A recordar:** les pàgines no contenen SQL: usen els DAO que `bootstrap.php` els proporciona. Un avançament d'estat és «recuperar, canviar el model, guardar» (`findById` → `avancarEstat` → `update`). Les dades que arriben per la URL es validen (`filter_input`), les que es mostren s'escapen (`e()`) i els errors de base de dades es registren i es mostra un missatge genèric.

📖 Documentació oficial

[PHP: `filter_input`](https://www.php.net/manual/es/function.filter-input.php) · [PHP: `http_response_code`](https://www.php.net/manual/es/function.http-response-code.php) · [PHP: `error_log`](https://www.php.net/manual/es/function.error-log.php) · [PHP: `nl2br`](https://www.php.net/manual/es/function.nl2br.php)

### 10.6. Peces comunes: validació i utilitats

Crear i editar un lead necessiten **les mateixes validacions** i les mateixes conversions. En lloc de duplicar-les, les passem a funcions auxiliars.

**Un canvi a `Lead`**: la llista de serveis que oferim és una dada del negoci, així que la guardem al model. Afig esta constant a `src/Model/Lead.php`, al costat de les de l'estat:

```php
    public const SERVEIS = ['Web', 'App mòbil', 'SEO', 'Cloud'];
```

**Tres funcions noves** al final de `src/helpers.php`:

```php
/**
 * Valida les dades comunes d'un lead.
 * Retorna un array d'errors (buit si tot és correcte).
 */
function validarDadesLead(string $nom, string $email, mixed $pressupost): array
{
    $errors = [];

    if ($nom === '') {
        $errors['nom'] = 'El camp nom és obligatori.';
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'El correu electrònic no és vàlid.';
    }

    if (!is_numeric($pressupost) || $pressupost <= 0) {
        $errors['pressupost'] = 'El pressupost ha de ser un valor numèric.';
    }

    return $errors;
}

/**
 * Deixa només els serveis que oferim. Ignora qualsevol altre valor.
 */
function filtrarServeis(mixed $serveis): array
{
    if (!is_array($serveis)) {
        return [];
    }

    return array_values(array_filter(
        $serveis,
        fn($servei) => is_string($servei) && in_array($servei, Lead::SERVEIS, true)
    ));
}

/**
 * Converteix una cadena buida en null (per a guardar NULL a la base de dades).
 */
function nullSiBuit(string $text): ?string
{
    return $text === '' ? null : $text;
}
```

- **`validarDadesLead()`** conté les mateixes regles de la unitat 3, ara en un sol lloc.
- **`filtrarServeis()`**: les dades d'un formulari **poden manipular-se**. Aquesta funció garanteix que només es guarden serveis de la llista `Lead::SERVEIS`, encara que algú envie valors inventats o una estructura inesperada.
- **`nullSiBuit()`**: un camp opcional buit del formulari arriba com a `''`. A la base de dades volem `NULL`, que significa «no hi ha dada».

### 10.7. Crear: `processar_lead.php`

El formulari públic ja existix; només canvia el que fa amb les dades. Substituïx **tot** el contingut de `public/processar_lead.php` per:

```php
<?php

use App\Model\Lead;

require_once __DIR__ . '/../config/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

// Recollida de dades
$nom = trim($_POST['contacte']['nom'] ?? '');
$email = trim($_POST['contacte']['email'] ?? '');
$telefon = trim($_POST['contacte']['telefon'] ?? '');
$empresa = trim($_POST['contacte']['empresa'] ?? '');
$serveis = filtrarServeis($_POST['serveis'] ?? []);
$pressupost = $_POST['pressupost'] ?? '';
$missatge = trim($_POST['missatge'] ?? '');
$termes = $_POST['termes'] ?? '';
$document = null;

// Validacions
$errors = validarDadesLead($nom, $email, $pressupost);

if ($termes === '') {
    $errors['termes'] = 'Cal acceptar els termes i condicions.';
}

// Pugem el document si no hi ha errors, per evitar documents orfes
if (empty($errors)) {
    try {
        $document = pujarArxiu($_FILES['document'] ?? [], CARPETA_PUJADES);
    } catch (RuntimeException $e) {
        $errors['document'] = $e->getMessage();
    }
}

if (empty($errors)) {
    $lead = new Lead(
        nom: $nom,
        email: $email,
        telefon: nullSiBuit($telefon),
        empresa: nullSiBuit($empresa),
        serveis: $serveis,
        pressupost: (float) $pressupost,
        missatge: nullSiBuit($missatge),
        document: $document
    );

    try {
        $leadDAO->insert($lead);

        header('Location: index.php?enviat=1');
        exit;
    } catch (PDOException $e) {
        // El lead no s'ha guardat: l'arxiu pujat quedaria orfe
        eliminarArxiu(CARPETA_PUJADES, $document);
        error_log($e->getMessage());
        $errors['bd'] = 'No s\'ha pogut guardar la sol·licitud. Torna-ho a provar més tard.';
    }
}

$_SESSION['errors'] = $errors;
$_SESSION['old'] = [
    'nom' => $nom,
    'email' => $email,
    'empresa' => $empresa,
    'telefon' => $telefon,
    'pressupost' => $pressupost,
    'serveis' => $serveis,
    'missatge' => $missatge,
    'termes' => $termes,
];

header('Location: index.php');
exit;
```

Compara-ho amb la unitat 3: són **la meitat de línies** i el mateix flux.

- **`pujarArxiu()`** substituïx les ~35 línies de la pujada (punt 9). Si no hi ha arxiu, retorna `null`, que és el que volem guardar.
- **`$leadDAO->insert($lead)`** substituïx `GestorLead::anyadirLead()`.
- **Els camps opcionals buits es guarden com a `NULL`** gràcies a `nullSiBuit()`.
- **Si falla MySQL**, l'arxiu ja pujat s'elimina (`eliminarArxiu`) perquè no quede orfe, i es mostra un error genèric a l'usuari. El motiu real es registra amb `error_log()`.
- **El flux d'errors no canvia**: `$_SESSION['errors']` i `$_SESSION['old']` s'usen igual que a la unitat 3, i `index.php` mostra l'error `bd` amb el quadre d'errors que ja tenia.

::: info Nota
Hem invertit la condició inicial: en lloc d'embolicar tot el codi en un `if ($_SERVER['REQUEST_METHOD'] === 'POST')`, comprovem el contrari i **eixim d'hora**. Així el codi principal queda sense un nivell de sagnat.
:::

### 10.8. Editar: `editar.php`

Nova pàgina `public/dashboard/editar.php`. Mostra un formulari amb les dades actuals del lead i, en enviar-lo, les guarda amb `update()`.

```php
<?php

use App\Model\Lead;

require_once __DIR__ . '/../../config/bootstrap.php';

if (empty($_SESSION['usuari'])) {
    header('Location: login.php');
    exit;
}

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if ($id === null || $id === false) {
    header('Location: leads.php');
    exit;
}

$errorBD = null;
$errors = [];
$lead = null;

try {
    $lead = $leadDAO->findById($id);
} catch (PDOException $e) {
    error_log($e->getMessage());
    $errorBD = 'No s\'ha pogut accedir a la base de dades.';
}

if ($lead === null && $errorBD === null) {
    http_response_code(404);
}

if ($lead !== null) {
    // Valors inicials del formulari: les dades actuals del lead
    $valors = [
        'nom'        => $lead->getNom(),
        'email'      => $lead->getEmail(),
        'telefon'    => $lead->getTelefon() ?? '',
        'empresa'    => $lead->getEmpresa() ?? '',
        'serveis'    => $lead->getServeis(),
        'pressupost' => $lead->getPressupost(),
        'missatge'   => $lead->getMissatge() ?? '',
    ];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Si s'ha enviat el formulari, els valors són els enviats
        $valors = [
            'nom'        => trim($_POST['contacte']['nom'] ?? ''),
            'email'      => trim($_POST['contacte']['email'] ?? ''),
            'telefon'    => trim($_POST['contacte']['telefon'] ?? ''),
            'empresa'    => trim($_POST['contacte']['empresa'] ?? ''),
            'serveis'    => filtrarServeis($_POST['serveis'] ?? []),
            'pressupost' => $_POST['pressupost'] ?? '',
            'missatge'   => trim($_POST['missatge'] ?? ''),
        ];

        $errors = validarDadesLead($valors['nom'], $valors['email'], $valors['pressupost']);
        $nouDocument = null;

        // Si s'ha triat un document nou, el pugem (només si no hi ha errors)
        if (empty($errors)) {
            try {
                $nouDocument = pujarArxiu($_FILES['document'] ?? [], CARPETA_PUJADES);
            } catch (RuntimeException $e) {
                $errors['document'] = $e->getMessage();
            }
        }

        if (empty($errors)) {
            // L'id i l'estat es conserven; el document, si no se'n puja un de nou
            $leadActualitzat = new Lead(
                nom: $valors['nom'],
                email: $valors['email'],
                telefon: nullSiBuit($valors['telefon']),
                empresa: nullSiBuit($valors['empresa']),
                serveis: $valors['serveis'],
                pressupost: (float) $valors['pressupost'],
                missatge: nullSiBuit($valors['missatge']),
                document: $nouDocument ?? $lead->getDocument(),
                id: $lead->getId(),
                estat: $lead->getEstat()
            );

            try {
                $leadDAO->update($leadActualitzat);

                // Si s'ha substituït el document, eliminem l'anterior
                if ($nouDocument !== null) {
                    eliminarArxiu(CARPETA_PUJADES, $lead->getDocument());
                }

                header('Location: lead.php?id=' . $lead->getId() . '&desat=1');
                exit;
            } catch (PDOException $e) {
                // No s'ha guardat: el document nou quedaria orfe
                eliminarArxiu(CARPETA_PUJADES, $nouDocument);
                error_log($e->getMessage());
                $errors['bd'] = 'No s\'han pogut desar els canvis. Torna-ho a provar més tard.';
            }
        }
    }
}

$classeLabel = 'block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5';
$classeInput = 'w-full bg-slate-50/70 border border-slate-300 focus:border-indigo-600 focus:ring-4 focus:ring-indigo-500/10 focus:bg-white rounded-xl px-3.5 py-2.5 text-slate-800 text-sm';

$titol = 'TechLeads - Editar lead';
require_once __DIR__ . '/../../vistes/dashboard_capcalera.php';
?>
<main class="max-w-3xl mx-auto px-6 py-8 flex-1 w-full space-y-6">

    <a href="<?= $lead !== null ? 'lead.php?id=' . $lead->getId() : 'leads.php' ?>"
       class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-indigo-600 transition-colors">
        &larr; Tornar
    </a>

    <?php if ($errorBD !== null): ?>
        <div class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-medium text-rose-700">
            <?= e($errorBD) ?>
        </div>

    <?php elseif ($lead === null): ?>
        <div class="bg-white p-12 rounded-2xl shadow-xl shadow-slate-200/50 border border-slate-200/80 text-center space-y-2">
            <h1 class="text-xl font-extrabold text-slate-900">Lead no trobat</h1>
            <p class="text-sm text-slate-500">No existeix cap lead amb l'identificador indicat.</p>
        </div>

    <?php else: ?>
        <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Editar lead #<?= $lead->getId() ?></h1>

        <?php if (!empty($errors)): ?>
            <div class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                <p class="font-semibold">Cal que revises les dades del formulari:</p>
                <ul class="list-disc list-inside mt-1">
                    <?php foreach ($errors as $error): ?>
                        <li><?= e($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="POST" action="editar.php?id=<?= $lead->getId() ?>" enctype="multipart/form-data"
              class="bg-white rounded-2xl shadow-xl shadow-slate-200/60 border border-slate-200/80 p-6 space-y-5">

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label for="nom" class="<?= $classeLabel ?>">Nom *</label>
                    <input type="text" id="nom" name="contacte[nom]" value="<?= e($valors['nom']) ?>" class="<?= $classeInput ?>">
                </div>
                <div>
                    <label for="email" class="<?= $classeLabel ?>">Correu electrònic *</label>
                    <input type="email" id="email" name="contacte[email]" value="<?= e($valors['email']) ?>" class="<?= $classeInput ?>">
                </div>
                <div>
                    <label for="telefon" class="<?= $classeLabel ?>">Telèfon</label>
                    <input type="text" id="telefon" name="contacte[telefon]" value="<?= e($valors['telefon']) ?>" class="<?= $classeInput ?>">
                </div>
                <div>
                    <label for="empresa" class="<?= $classeLabel ?>">Empresa</label>
                    <input type="text" id="empresa" name="contacte[empresa]" value="<?= e($valors['empresa']) ?>" class="<?= $classeInput ?>">
                </div>
            </div>

            <fieldset>
                <legend class="<?= $classeLabel ?>">Serveis</legend>
                <div class="flex flex-wrap gap-4 text-sm text-slate-700">
                    <?php foreach (Lead::SERVEIS as $servei): ?>
                        <label class="inline-flex items-center gap-2">
                            <input type="checkbox" name="serveis[]" value="<?= e($servei) ?>"
                                   <?= in_array($servei, $valors['serveis'], true) ? 'checked' : '' ?>
                                   class="w-4 h-4 rounded text-indigo-600 border-slate-300 focus:ring-indigo-500">
                            <?= e($servei) ?>
                        </label>
                    <?php endforeach; ?>
                </div>
            </fieldset>

            <div>
                <label for="pressupost" class="<?= $classeLabel ?>">Pressupost (€) *</label>
                <input type="text" id="pressupost" name="pressupost" value="<?= e((string) $valors['pressupost']) ?>" class="<?= $classeInput ?>">
            </div>

            <div>
                <label for="missatge" class="<?= $classeLabel ?>">Missatge</label>
                <textarea id="missatge" name="missatge" rows="4" class="<?= $classeInput ?>"><?= e($valors['missatge']) ?></textarea>
            </div>

            <div>
                <label for="document" class="<?= $classeLabel ?>">Document</label>
                <?php if (!empty($lead->getDocument())): ?>
                    <p class="text-sm text-slate-600 mb-2">
                        Document actual:
                        <a href="../pujades/<?= e($lead->getDocument()) ?>" class="text-indigo-600 hover:underline"><?= e($lead->getDocument()) ?></a>
                    </p>
                <?php endif; ?>
                <input type="file" id="document" name="document" class="text-sm text-slate-600">
                <p class="text-xs text-slate-400 mt-1">Opcional. Si en tries un, substituirà l'actual (JPG, PNG o PDF, màxim 2 MB).</p>
            </div>

            <div class="flex items-center justify-end gap-3 pt-2">
                <a href="lead.php?id=<?= $lead->getId() ?>" class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:bg-slate-100 transition-colors">
                    Cancel·lar
                </a>
                <button type="submit" class="px-5 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold shadow-md shadow-indigo-500/20 transition-all">
                    Desar canvis
                </button>
            </div>
        </form>
    <?php endif; ?>

</main>

<?php require_once __DIR__ . '/../../vistes/dashboard_peu.php';
```

Els punts clau:

- **Una sola pàgina per a mostrar i per a guardar.** El formulari s'envia a la mateixa pàgina (`editar.php?id=...`). Si hi ha errors, la pàgina es torna a mostrar **amb els valors enviats** i el quadre d'errors; no cal la sessió. Si tot va bé, es redirigix al detall.
- **`$valors`** són les dades que es mostren al formulari: al principi les del lead; després d'un enviament, les que ha escrit l'usuari.
- **S'han reutilitzat** `validarDadesLead()`, `filtrarServeis()`, `nullSiBuit()` i `pujarArxiu()`.
- **Es construïx un `Lead` nou amb l'`id` i l'`estat` del lead original** (per això el constructor els accepta), i es passa a `update()`.
- **El document** es gestiona amb `$nouDocument ?? $lead->getDocument()`: si no se'n puja un de nou, es conserva l'actual.
- **L'estat no s'edita ací.** Només avança amb el botó «Avança estat» del llistat, perquè la regla `nou → contactat → tancat` viu al model.
- **`$classeLabel` i `$classeInput`** guarden les classes de Tailwind que es repetixen, per a no copiar-les en cada camp.

Cal gestionar també els arxius, i l'**ordre** importa:

| Situació | Què fem amb els arxius |
| --- | --- |
| Es puja un document nou i l'`update` va bé | S'elimina el document **antic** |
| Es puja un document nou i l'`update` falla | S'elimina el document **nou** (l'antic continua sent vàlid) |
| No es puja cap document | No es toca res |

Dos canvis menuts a **`lead.php`** (el detall):

1. Un missatge de confirmació, just darrere de l'enllaç «Tornar al llistat»:

```php
<?php if (isset($_GET['desat'])): ?>
    <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">
        Els canvis s'han desat correctament.
    </div>
<?php endif; ?>
```

2. El botó d'edició, just després del `</dl>` i dins de la targeta:

```php
<div class="flex justify-end px-6 py-4 border-t border-slate-100 bg-slate-50/60">
    <a href="editar.php?id=<?= $lead->getId() ?>"
       class="inline-flex items-center px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold shadow-md shadow-indigo-500/20 transition-all">
        Editar
    </a>
</div>
```

### 10.9. Eliminar

L'eliminació es fa des del llistat, amb el mateix mecanisme que ja teníem per a avançar l'estat: un formulari `POST` amb l'`id` i el botó que indica l'**acció**. Fins ara l'únic valor possible era `avancar`; ara n'hi haurà dos.

**1.** A `leads.php`, dins del `try`, substituïx el bloc `if ($_SERVER['REQUEST_METHOD'] === 'POST') { ... }` per:

```php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $accio = $_POST['accio'] ?? '';
        $idLead = (int) ($_POST['id'] ?? 0);
        $lead = $idLead !== 0 ? $leadDAO->findById($idLead) : null;

        if ($lead !== null && $accio === 'avancar') {
            $lead->avancarEstat();
            $leadDAO->update($lead);
        } elseif ($lead !== null && $accio === 'eliminar') {
            $leadDAO->delete($idLead);

            // Primer la fila de la base de dades; després l'arxiu
            eliminarArxiu(CARPETA_PUJADES, $lead->getDocument());
        }

        header('Location: leads.php');
        exit;
    }
```

**2.** A la cel·la d'accions, dins del `div` que va creant-se, afig després del formulari «Avança estat» un segon formulari:

```php
<form method="POST" action="leads.php" onsubmit="return confirm('Segur que vols eliminar este lead? No es pot desfer.');">
    <input type="hidden" name="id" value="<?= $lead->getId() ?>">
    <button type="submit" name="accio" value="eliminar"
            class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-semibold bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 transition-colors">
        Eliminar
    </button>
</form>
```

Per què així:

- **Sempre amb `POST`, mai amb un enllaç.** Una petició `GET` ha de ser inofensiva: els navegadors, els *crawlers* i les extensions poden «visitar» enllaços sense que ningú els premi. Si eliminar fora `leads.php?eliminar=3`, un robot podria esborrar-ho tot.
- **L'ordre és fiable.** Si primer esborrem l'arxiu i després falla MySQL, el lead continuaria existint apuntant a un document desaparegut. Fent-ho a l'inrevés, el pitjor cas és un arxiu sobrant, que és molt menys greu.
- **`confirm()`** només és una comoditat per a l'usuari. La seguretat real està al servidor.

::: warning Atenció
Este panell **no protegix contra CSRF** (*Cross-Site Request Forgery*): una altra web podria fer que el navegador d'un usuari autenticat envie un `POST` d'eliminació sense que ho sàpia. En una aplicació real cal afegir un *token* secret a cada formulari. Frameworks com Laravel ho fan automàticament (`@csrf`), i ho veurem més endavant.
:::

### 10.10. Comprovar el CRUD complet

Fes el recorregut sencer i mira cada resultat també a **phpMyAdmin** i a la carpeta `public/pujades/`:

| Prova | Resultat esperat |
| --- | --- |
| Enviar el formulari públic buit | Errors; les dades es mantenen |
| Enviar-lo amb tots els camps correctes i un PDF | Missatge d'èxit; lead nou al llistat; fila a `leads` amb `serveis` en JSON i `NULL` en els camps buits |
| Enviar-lo sense telèfon ni empresa | Els camps `telefon` i `empresa` valen `NULL` (no una cadena buida) |
| Aturar MySQL (`docker compose stop db`) i enviar el formulari | Missatge d'error genèric i **cap arxiu nou** a `pujades/` |
| Tornar a arrancar MySQL (`docker compose start db`) | Tot torna a funcionar |
| Editar un lead canviant el nom i el pressupost | Es redirigix al detall amb el missatge «desats» |
| Editar amb un correu incorrecte | Torna al formulari amb l'error i **les dades que has escrit** |
| Editar substituint el document | A `pujades/` apareix el nou i **desapareix l'antic** |
| Eliminar un lead amb document | Confirmació; la fila desapareix de la taula i l'arxiu de `pujades/` |
| Escriure `leads.php?accio=eliminar&id=1` a la barra d'adreces | No passa res: només s'accepta `POST` |

---

📌 **A recordar:** les pàgines no tenen SQL; usen `$leadDAO`. Crear és `insert()`, llegir `findAll()` i `findById()`, editar `update()` (amb un `Lead` nou que conserva `id` i `estat`) i eliminar `delete()`. Les operacions que modifiquen dades van sempre per `POST`. Les dades del formulari es validen i es filtren al servidor, i si falla la base de dades es desfà el que s'havia fet amb els arxius.

📖 Documentació oficial

[PHP: `filter_var`](https://www.php.net/manual/es/function.filter-var.php) · [PHP: `unlink`](https://www.php.net/manual/es/function.unlink.php) · [PHP: Variables de servidor (`$_SERVER`)](https://www.php.net/manual/es/reserved.variables.server.php) · [OWASP: CSRF](https://owasp.org/www-community/attacks/csrf)