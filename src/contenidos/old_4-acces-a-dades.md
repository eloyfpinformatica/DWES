# Unitat 4. Accés a dades


En aquesta unitat construirem **des de zero** una aplicació web amb PHP que treballa amb una base de dades MySQL. Continuarem el projecte de **Leads**: un formulari públic per a crear sol·licituds i una zona privada, protegida amb inici de sessió, per a gestionar-les (llistar, filtrar, editar, canviar l'estat, eliminar...). Ho farem pas a pas, amb l'arquitectura **Model - Vista - Controlador**, l'estàndard de codi **PSR-12** i l'autocàrrega **PSR-4** amb Composer.

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

## Índex

1. [Entorn de treball amb Docker](#_1-entorn-de-treball-amb-docker)
   - 1.1. Estructura inicial
   - 1.2. El `Dockerfile`
   - 1.3. El `docker-compose.yml`
   - 1.4. Configuració de Xdebug
   - 1.5. Posada en marxa i comprovació
2. [Creació de la base de dades en phpMyAdmin](#_2-creacio-de-la-base-de-dades-en-phpmyadmin)
   - 2.1. La base de dades
   - 2.2. Les taules
   - 2.3. Script de creació
   - 2.4. Dades d'exemple
   - 2.5. Comprovació
3. [Definició i estructura del projecte](#_3-definicio-i-estructura-del-projecte)
   - 3.1. Arquitectura MVC
   - 3.2. Estructura de carpetes
   - 3.3. Composer i `composer.json`
   - 3.4. Namespaces i autocàrrega PSR-4
   - 3.5. L'estàndard PSR-12
   - 3.6. Comprovació
4. [El model](#_4-el-model)
   - 4.1. La classe `Lead`
   - 4.2. La classe `Usuari`
   - 4.3. Comprovació
5. [La interfície `IDbAccess`](#_5-la-interficie-idbaccess)
   - 5.1. Per què una interfície?
   - 5.2. Crear la interfície
   - 5.3. Com la implementarem
   - 5.4. El problema dels paràmetres: `object` i no `Lead`
   - 5.5. Mètodes que no estan en la interfície
   - 5.6. Comprovació
6. [PDO](#_6-pdo)
   - 6.1. Connexió
   - 6.2. Consultes preparades
   - 6.3. Llegir resultats
   - 6.4. Inserir, modificar i eliminar
   - 6.5. Gestió d'errors: `PDOException`
   - 6.6. Comprovació
7. [La classe `DBConnection`](#_7-la-classe-dbconnection)
   - 7.1. Fitxer de configuració
   - 7.2. Injecció de dependències
   - 7.3. Crear la classe `DBConnection`
   - 7.4. On es munten els objectes: `bootstrap.php`
   - 7.5. Gestió d'errors
   - 7.6. Comprovació
8. [Implementacions de l'accés a dades (DAO)](#_8-implementacions-de-l-acces-a-dades-dao)
   - 8.1. La classe `LeadDAO`
   - 8.2. La classe `UsuariDAO`
   - 8.3. Les excepcions no es capturen en el DAO
   - 8.4. Comprovació
9. [Funcions auxiliars](#_9-funcions-auxiliars)
   - 9.1. La classe `Helpers`
   - 9.2. Netejar, validar i escapar
   - 9.3. Crear la classe: escapament
   - 9.4. Pujar imatges
   - 9.5. La carpeta `uploads`
   - 9.6. Comprovació
10. [CRUD de leads](#_10-crud-de-leads)
    - 10.1. Llistat amb filtres i paginació
    - 10.2. Detall d'un lead
    - 10.3. Afegir i editar un lead
      - Alta: el formulari públic (10.3.1 - 10.3.9)
      - Edició en l'administració (10.3.10 - 10.3.17)
    - 10.4. Eliminar un lead i canviar-ne l'estat
11. [Login contra la base de dades](#_11-login-contra-la-base-de-dades)
    - 11.1. Sessions
    - 11.2. Validar les credencials
    - 11.3. Protegir l'administració
    - 11.4. Missatges *flash*
    - 11.5. Comprovació




## 1. Entorn de treball amb Docker

Per a aquesta unitat necessitem tres serveis: **PHP amb Apache**, **MySQL** i **phpMyAdmin**. A més, afegirem **Xdebug** al contenidor de PHP per a poder depurar el codi des de l'editor.

::: info
Ja hem treballat amb Docker en les unitats anteriors, així que ací només veurem les peces que canvien: l'extensió `pdo_mysql`, Composer, Xdebug i els contenidors de la base de dades.
:::

### 1.1. Estructura inicial

Crea una carpeta per al projecte amb aquesta estructura. El codi PHP anirà dins de `www/`, que definirem en detall en el punt 3.

```text
leads/
├── docker-compose.yml
├── Dockerfile
├── docker/
│   └── xdebug.ini
└── www/
    └── info.php
```

### 1.2. El `Dockerfile`

Partim de la imatge oficial de PHP amb Apache i li afegim:

- **`pdo_mysql`**: el driver que necessita PDO per a connectar amb MySQL.
- **Xdebug**: el depurador.
- **Composer**: el gestor de dependències i d'autocàrrega (el farem servir en el punt 3).

```dockerfile
FROM php:8.5-apache

# Utilitats necessàries per a Composer
RUN apt-get update && apt-get install -y git unzip \
    && rm -rf /var/lib/apt/lists/*

# Extensions de PHP: PDO per a MySQL
RUN docker-php-ext-install pdo pdo_mysql

# Xdebug
RUN pecl install xdebug && docker-php-ext-enable xdebug
COPY docker/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Mòdul d'Apache per a URL amigables (el podem necessitar més endavant)
RUN a2enmod rewrite
```

### 1.3. El `docker-compose.yml`

```yaml
services:
  web:
    build: .
    container_name: leads_web
    ports:
      - "8080:80"
    volumes:
      - ./www:/var/src/html
    extra_hosts:
      - "host.docker.internal:host-gateway"
    depends_on:
      - db

  db:
    image: mysql:8.4
    container_name: leads_db
    environment:
      MYSQL_ROOT_PASSWORD: root
      MYSQL_DATABASE: leads
      MYSQL_USER: leads_user
      MYSQL_PASSWORD: leads_pass
    ports:
      - "3307:3306"
    volumes:
      - db_data:/var/lib/mysql

  phpmyadmin:
    image: phpmyadmin:latest
    container_name: leads_phpmyadmin
    environment:
      PMA_HOST: db
    ports:
      - "8081:80"
    depends_on:
      - db

volumes:
  db_data:
```

Alguns detalls importants:

- El servei de MySQL es diu **`db`**. Dins de la xarxa de Docker, aquest és el **host** que utilitzarem per a connectar des de PHP (no `localhost`).
- El volum `db_data` fa que les dades **no es perguen** quan s'apaguen o s'eliminen els contenidors.
- Les variables `MYSQL_DATABASE`, `MYSQL_USER` i `MYSQL_PASSWORD` creen automàticament la base de dades i l'usuari la primera vegada que s'arranca el contenidor.
- El port `3307` evita conflictes si ja tens un MySQL instal·lat al teu ordinador.

::: warning
Aquestes credencials són només per a desenvolupament en local. En un servidor real mai s'han d'utilitzar contrasenyes tan simples ni pujar-les al repositori.
:::

### 1.4. Configuració de Xdebug

Fitxer `docker/xdebug.ini`:

```ini
xdebug.mode=debug
xdebug.start_with_request=yes
xdebug.client_host=host.docker.internal
xdebug.client_port=9003
xdebug.idekey=VSCODE
```

- `xdebug.mode=debug` activa el depurador pas a pas.
- `xdebug.client_host` és l'adreça de la teua màquina, on l'editor escolta; per això ho hem declarat amb `extra_hosts` en el `docker-compose.yml`.
- `9003` és el port per defecte de Xdebug 3.

Si utilitzes **Visual Studio Code**, instal·la l'extensió **PHP Debug** i crea el fitxer `.vscode/launch.json`:

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
        "/var/www/html": "${workspaceFolder}/www"
      }
    }
  ]
}
```

El `pathMappings` indica a l'editor que la carpeta `/var/www/html` del contenidor equival a la carpeta `www/` del teu projecte.

::: tip
Amb `xdebug.start_with_request=yes`, Xdebug intenta connectar a cada petició. Si no tens l'editor escoltant, la pàgina pot tardar una mica més a carregar. Si et molesta, canvia-ho a `trigger` i activa la depuració només quan la necessites.
:::

### 1.5. Posada en marxa i comprovació

Des de la carpeta del projecte, construeix i arranca els contenidors:

```bash
docker compose up -d --build
```

Crea el fitxer `www/info.php` per a comprovar que PHP funciona i que les extensions estan carregades:

```php
<?php

phpinfo();
```

Comprova en el navegador:

| Servei | URL | Què hem de veure |
| --- | --- | --- |
| PHP / Apache | <http://localhost:8080/info.php> | La pàgina de `phpinfo()` amb les seccions **PDO**, **pdo_mysql** i **xdebug** |
| phpMyAdmin | <http://localhost:8081> | La pantalla d'inici de sessió |

Per a entrar en phpMyAdmin utilitza l'usuari `leads_user` i la contrasenya `leads_pass` (o `root` / `root` si necessites permisos d'administrador).

Recordatori comandes útils:

```bash
docker compose ps          # Estat dels contenidors
docker compose logs -f db  # Registres de MySQL
docker compose down        # Para i elimina els contenidors (les dades es conserven)
docker compose down -v     # També elimina el volum: ESBORRA la base de dades
```

::: danger
La comanda `docker compose down -v` elimina el volum `db_data` i, amb ell, **totes les dades de la base de dades**. Utilitza-la només si vols començar de zero.
:::

## 2. Creació de la base de dades en phpMyAdmin

::: info
El curs passat vau treballar el disseny i el llenguatge SQL en el mòdul de Bases de Dades. Ací només creem les dues taules que necessita el projecte i les omplim amb dades d'exemple.
:::

### 2.1. La base de dades

No cal crear-la a mà: el contenidor de MySQL ja ha creat la base de dades **`leads`** gràcies a la variable `MYSQL_DATABASE` del `docker-compose.yml`.

1. Entra en phpMyAdmin (<http://localhost:8081>) amb l'usuari `leads_user` i la contrasenya `leads_pass`.
2. Selecciona la base de dades `leads` en el panell de l'esquerra.
3. Obri la pestanya **SQL**: ací enganxarem els scripts dels apartats següents.

### 2.2. Les taules

El projecte tindrà dues taules:

- **`leads`**: les sol·licituds d'informació que arriben des del formulari públic.

| Camp | Tipus | Notes |
| --- | --- | --- |
| `id` | `INT UNSIGNED` | Clau primària, autoincremental |
| `nom` | `VARCHAR(100)` | Obligatori |
| `email` | `VARCHAR(150)` | Obligatori |
| `telefon` | `VARCHAR(20)` | Opcional |
| `empresa` | `VARCHAR(100)` | Opcional |
| `missatge` | `TEXT` | Obligatori |
| `fitxer` | `VARCHAR(255)` | Opcional. Nom del fitxer pujat |
| `estat` | `ENUM` | `nou`, `contactat`, `en_proces`, `tancat` |
| `data_creacio` | `DATETIME` | Per defecte, el moment de la inserció |
| `data_actualitzacio` | `DATETIME` | S'actualitza en cada modificació |


- **`usuaris`**: les persones que poden accedir a la zona privada.

| Camp | Tipus | Notes |
| --- | --- | --- |
| `id` | `INT UNSIGNED` | Clau primària, autoincremental |
| `nom` | `VARCHAR(100)` | Obligatori |
| `email` | `VARCHAR(150)` | Obligatori i **únic** |
| `contrasenya` | `VARCHAR(255)` | Hash generat amb `password_hash` |
| `data_creacio` | `DATETIME` | Per defecte, el moment de la inserció |

::: tip
El camp `estat` és un `ENUM` i no una taula a part per a mantindre el model senzill. Si en un futur cal afegir estats nous, s'haurà de modificar la definició de la columna.
:::

### 2.3. Script de creació

Executa aquest script en la pestanya **SQL**:

```sql
CREATE TABLE leads (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    nom VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    telefon VARCHAR(20) NULL,
    empresa VARCHAR(100) NULL,
    missatge TEXT NOT NULL,
    fitxer VARCHAR(255) NULL,
    estat ENUM('nou', 'contactat', 'en_proces', 'tancat') NOT NULL DEFAULT 'nou',
    data_creacio DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    data_actualitzacio DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE usuaris (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    nom VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    contrasenya VARCHAR(255) NOT NULL,
    data_creacio DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uk_usuaris_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

::: details Per què `utf8mb4`?
És la codificació completa d'UTF-8 en MySQL: admet accents, la ela geminada, emojis i qualsevol altre caràcter. La codificació `utf8` de MySQL és una versió reduïda i no es recomana.
:::

### 2.4. Dades d'exemple

Inserim un usuari administrador i alguns leads perquè el llistat tinga contingut des del principi:

```sql
INSERT INTO usuaris (nom, email, contrasenya) VALUES
('Administrador', 'admin@techleads.test',
 '$2y$10$XMlfFSeSawh//.byZIOezu1zU3q6lXjFMB0Gdn0FrXXUpe7eCZQS6');

INSERT INTO leads (nom, email, telefon, empresa, missatge, estat) VALUES
('Marta Ferrer', 'marta@exemple.com', '600111222', 'Ferrer i Fills',
 'Voldria informació sobre els vostres serveis de desenvolupament web.', 'nou'),
('Joan Pons', 'joan@exemple.com', NULL, 'Pons Consulting',
 'Necessitem una aplicació de gestió interna per a la nostra empresa.', 'contactat'),
('Laura Bellver', 'laura@exemple.com', '600333444', NULL,
 'M\'interessa un pressupost per a una botiga en línia.', 'en_proces'),
('Pau Climent', 'pau@exemple.com', '600555666', 'Climent Disseny',
 'Ens agradaria renovar la nostra web corporativa.', 'tancat'),
('Anna Soler', 'anna@exemple.com', NULL, NULL,
 'Podeu enviar-me més informació sobre les vostres tarifes?', 'nou');
```

::: warning
La contrasenya **no es guarda mai en text pla**: el camp `contrasenya` conté el hash que genera `password_hash()`. El hash de l'exemple correspon a la contrasenya `admin123`, que utilitzarem per a iniciar sessió en el punt 11.
:::

Si vols generar el hash d'una altra contrasenya, pots fer-ho des del contenidor:

```bash
docker compose exec web php -r "echo password_hash('la_teua_contrasenya', PASSWORD_DEFAULT);"
```

### 2.5. Comprovació

Executa aquesta consulta i comprova que apareixen els 5 leads i que l'usuari s'ha creat:

```sql
SELECT id, nom, estat FROM leads;
SELECT id, nom, email FROM usuaris;
```

::: tip
Quan acabes, guarda aquest script en un fitxer `database/leads.sql` dins del projecte. Així pots recrear la base de dades en qualsevol moment i compartir-la amb altres persones.
:::

## 3. Definició i estructura del projecte

Abans d'escriure la primera classe, hem de decidir **com organitzem el projecte**. Una bona estructura fa que el codi siga fàcil de trobar, de mantindre i d'ampliar.

### 3.1. Arquitectura MVC

Dividirem l'aplicació en tres capes amb responsabilitats ben definides:

| Capa | Responsabilitat | En el nostre projecte |
| --- | --- | --- |
| **Model** | Les dades i l'accés a la base de dades | `Lead`, `Usuari`, `LeadDAO`, `UsuariDAO`, `DBConnection` |
| **Vista** | El que veu l'usuari (HTML) | Fitxers de la carpeta `vistes/` |
| **Controlador** | Rep la petició, utilitza el model i tria la vista | `LeadController`, `UsuariController` |

El flux d'una petició serà sempre el mateix:

```text
Navegador → fitxer d'entrada → Controlador → Model (DAO) → Base de dades
                                    ↓
                                  Vista → HTML → Navegador
```

::: tip Regla d'or
La vista **no** parla amb la base de dades i el model **no** genera HTML. El controlador és qui connecta les dues capes.
:::

### 3.2. Estructura de carpetes

Dins de la carpeta `www/` (la que està connectada al contenidor) tindrem, al final de la unitat, aquesta estructura. Els fitxers els anirem creant punt a punt; ara només cal crear les carpetes.

```text
leads/
├── .vscode/
│   └── launch.json              ← depuració amb Xdebug (punt 1)
├── docker-compose.yml
├── Dockerfile
├── docker/
│   └── xdebug.ini
├── database/
│   └── leads.sql                ← script de la base de dades (punt 2)
└── www/
    ├── composer.json
    ├── vendor/                  ← la genera Composer
    ├── config/
    │   ├── config.php           ← paràmetres de connexió
    │   └── bootstrap.php        ← autocàrrega, sessió i connexió
    ├── src/                     ← namespace App\
    │   ├── Controllers/
    │   │   ├── BaseController.php
    │   │   ├── LeadController.php
    │   │   └── UsuariController.php
    │   ├── DAO/
    │   │   ├── LeadDAO.php
    │   │   └── UsuariDAO.php
    │   ├── Database/
    │   │   ├── DBConnection.php
    │   │   └── IDbAccess.php
    │   ├── Helpers/
    │   │   ├── Helpers.php
    │   │   └── Sessio.php
    │   └── Models/
    │       ├── Lead.php
    │       └── Usuari.php
    ├── vistes/
    │   ├── layouts/
    │   │   ├── capcalera.php
    │   │   ├── peu.php
    │   │   ├── capcalera-public.php
    │   │   └── peu-public.php
    │   ├── parcials/
    │   │   ├── estat.php
    │   │   └── formulari-lead.php
    │   ├── errors/
    │   │   └── 404.php
    │   ├── admin/
    │   │   ├── login.php
    │   │   └── leads/
    │   │       ├── llistat.php
    │   │       ├── detall.php
    │   │       └── editar.php
    │   └── public/
    │       └── leads/
    │           ├── formulari.php
    │           └── gracies.php
    ├── public/
    │   └── index.php            ← formulari públic
    ├── admin/
    │   ├── index.php            ← gestió de leads (protegit)
    │   └── login.php            ← inici i tancament de sessió
    └── uploads/
        └── .gitkeep             ← imatges pujades
```

Hi ha dues carpetes d'entrada, **`public/`** i **`admin/`**, que són les úniques que l'usuari visita directament:

- <http://localhost:8080/public/> → zona pública (formulari).
- <http://localhost:8080/admin/> → zona privada (gestió, requerix inici de sessió).
- <http://localhost:8080/admin/login.php> → inici de sessió.

Tot el codi de l'aplicació (`src/`, `vistes/`, `config/`) queda fora d'aquestes carpetes i **només el carreguen els fitxers d'entrada**.


### 3.3. Composer i `composer.json`

**Composer** és el gestor de dependències de PHP. Ací no instal·larem cap llibreria externa: l'utilitzarem només per a l'**autocàrrega de classes**, que ens estalviarà escriure `require_once` per a cada classe.

Crea el fitxer `www/composer.json`:

```json
{
    "name": "techleads/leads",
    "description": "Gestió de leads - Unitat 4 de DWES",
    "type": "project",
    "require": {
        "php": ">=8.3"
    },
    "autoload": {
        "psr-4": {
            "App\\": "src/"
        }
    }
}
```

Genera l'autocàrrega executant Composer **dins del contenidor** (ja el vam instal·lar en el `Dockerfile`):

```bash
docker compose exec web composer dump-autoload
```

Es crearà la carpeta `vendor/` amb el fitxer `vendor/autoload.php`.

### 3.4. Namespaces i autocàrrega PSR-4

Un **namespace** (espai de noms) agrupa classes relacionades i evita conflictes entre noms. L'estàndard **PSR-4** defineix una regla molt senzilla: *el namespace correspon a la ruta de carpetes*.

Segons el nostre `composer.json`, el prefix `App\` equival a la carpeta `src/`:

| Classe | Fitxer |
| --- | --- |
| `App\Models\Lead` | `src/Models/Lead.php` |
| `App\Models\Usuari` | `src/Models/Usuari.php` |
| `App\Database\IDbAccess` | `src/Database/IDbAccess.php` |
| `App\Database\DBConnection` | `src/Database/DBConnection.php` |
| `App\DAO\LeadDAO` | `src/DAO/LeadDAO.php` |
| `App\DAO\UsuariDAO` | `src/DAO/UsuariDAO.php` |
| `App\Controllers\BaseController` | `src/Controllers/BaseController.php` |
| `App\Controllers\LeadController` | `src/Controllers/LeadController.php` |
| `App\Controllers\UsuariController` | `src/Controllers/UsuariController.php` |
| `App\Helpers\Helpers` | `src/Helpers/Helpers.php` |
| `App\Helpers\Sessio` | `src/Helpers/Sessio.php` |

Crea ara el fitxer `www/src/Models/Lead.php` amb aquest esquelet buit (el completarem en el punt 4):

```php
<?php

declare(strict_types=1);

namespace App\Models;

class Lead
{
}
```
::: tip
Amb PSR-4 **no cal tornar a executar** `composer dump-autoload` cada vegada que afegim una classe nova: Composer la busca pel nom del *namespace* i la ruta. Només cal executar-lo quan es modifica l'apartat `autoload` del `composer.json`.
:::

Per a utilitzar-la en un fitxer d'entrada només cal carregar l'autocàrrega **una vegada** i importar la classe amb `use`:

```php
<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use App\Models\Lead;

$lead = new Lead();
```

### 3.5. L'estàndard PSR-12

**PSR-12** és la guia d'estil oficial de la comunitat PHP. Ens diu **com s'ha d'escriure** el codi perquè tothom el llegisca de la mateixa manera. Aquestes són les regles que aplicarem:

| Regla | Exemple |
| --- | --- |
| Sagnat amb **4 espais** (no tabuladors) | |
| `declare(strict_types=1);` després de `<?php` | |
| Una línia en blanc entre `namespace`, els `use` i la classe | |
| Classes en `StudlyCaps` | `LeadController` |
| Mètodes i variables en `camelCase` | `getNom()`, `$totalPagines` |
| Constants en **MAJÚSCULES** amb guions baixos | `ESTAT_NOU` |
| Clau d'obertura de **classes, mètodes i funcions** en una línia nova | Vegeu l'exemple |
| Clau d'obertura de `if`, `for`, `foreach`... en la **mateixa línia** | Vegeu l'exemple |
| Visibilitat (`public`, `private`) sempre indicada | |
| Fitxers només amb PHP: **sense** etiqueta de tancament `?>` | |

```php
<?php

declare(strict_types=1);

namespace App\Models;

class Exemple
{
    public const ESTAT_NOU = 'nou';

    private string $nom;

    public function getNom(): string
    {
        return $this->nom;
    }

    public function esNou(string $estat): bool
    {
        if ($estat === self::ESTAT_NOU) {
            return true;
        }

        return false;
    }
}
```
::: info Nota (propietats del model)
PSR-12 **no imposa cap estil** per al nom de les propietats. En les classes del model (punt 4) utilitzem el mateix nom que les columnes de la base de dades (`$data_creacio`, `$data_actualitzacio`), perquè PDO les assigne automàticament. Els mètodes (`getDataCreacio()`) i les variables locals (`$totalPagines`) sí que van en `camelCase`.
:::

::: warning Atenció (diferència amb la unitat 3)
En la unitat 3 vam escriure les funcions amb la clau en la **mateixa línia**:

```php
function e(string $valor): string {
    return htmlspecialchars($valor);
}
```

PSR-12 la demana en una **línia nova** per a classes, mètodes i funcions amb nom, i en la mateixa línia per a `if`, `foreach` i les funcions anònimes. El codi funciona igual: és només una qüestió d'estil. A partir d'ara seguirem PSR-12:

```php
function e(string $valor): string
{
    return htmlspecialchars($valor);
}
```
:::

::: tip
No cal memoritzar-ho tot. Els editors (VS Code amb l'extensió *PHP Intelephense* o *PHP CS Fixer*) poden formatar el codi automàticament segons PSR-12.
:::

### 3.6. Comprovació

::: warning Abans de provar-ho
Aquesta comprovació necessita que existisca el fitxer `www/src/Models/Lead.php` del punt 3.4 i que hages executat `composer dump-autoload` una vegada (punt 3.3). Si no, PHP no trobarà la classe.
:::

Comprovem que l'autocàrrega funciona amb una pàgina d'inici amb Tailwind. Crea `www/public/index.php`:

```php
<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use App\Models\Lead;

$lead = new Lead();
$classe = $lead::class;
?>
<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leads</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white text-slate-900 antialiased">
    <main class="mx-auto max-w-3xl px-6 py-32">
        <h1 class="text-5xl font-semibold tracking-tight">
            Leads
        </h1>
        <p class="mt-8 text-xl leading-relaxed text-slate-600">
            L'autocàrrega funciona correctament.
            S'ha carregat la classe
            <code class="rounded bg-slate-100 px-2 py-1 text-lg">
                <?= $classe ?>
            </code>
        </p>
    </main>
</body>
</html>
```


Visita <http://localhost:8080/public/>. Si veus el missatge amb el nom de la classe `App\Models\Lead`, l'estructura i l'autocàrrega estan ben configurades.

::: warning Si apareix un error
`Class "App\Models\Lead" not found` normalment vol dir que:

- no existeix el fitxer `src/Models/Lead.php`,
- el nom del fitxer no coincideix exactament amb el de la classe (compte amb majúscules i minúscules),
- el `namespace` no correspon a la carpeta (`App\Models` ↔ `src/Models`), o
- falta executar `composer dump-autoload` una primera vez.
:::

## 4. El model

El **model** representa les dades de l'aplicació. Cada taula de la base de dades tindrà una classe que la representa: cada fila serà un **objecte** d'aquesta classe.

| Taula | Classe | Fitxer |
| --- | --- | --- |
| `leads` | `App\Models\Lead` | `src/Models/Lead.php` |
| `usuaris` | `App\Models\Usuari` | `src/Models/Usuari.php` |

Aquestes classes només guarden dades i les seues regles bàsiques. **No saben res de la base de dades**: d'això s'encarregaran els DAO (punt 8).

::: info Nota
Utilitzarem les **propietats promocionades del constructor** (PHP 8) que vam vore en la unitat 3: la propietat es declara i s'assigna en el mateix lloc, dins dels paràmetres del constructor.
:::

::: info Decisions de disseny
- Les propietats són **privades** i s'accedeix a elles amb *getters* i *setters* (encapsulació).
- Les propietats tenen **el mateix nom que les columnes** de la taula (`data_creacio`, `data_actualitzacio`...). Així, en el punt 6, PDO podrà crear els objectes automàticament amb `FETCH_CLASS`. PSR-12 no imposa cap estil per al nom de les propietats; els mètodes sí que van en `camelCase`.
- Les propietats són **tipades**. Les columnes que admeten `NULL` s'indiquen amb `?` (per exemple, `?string`).
- **Tots** els paràmetres del constructor tenen valor per defecte, de manera que es pot fer `new Lead()` sense arguments (PDO ho necessitarà).
:::

### 4.1. La classe `Lead`

Crea el fitxer `www/src/Models/Lead.php` (substitueix l'esquelet buit del punt 3):

```php
<?php

declare(strict_types=1);

namespace App\Models;

use InvalidArgumentException;

class Lead
{
    public const ESTAT_NOU = 'nou';
    public const ESTAT_CONTACTAT = 'contactat';
    public const ESTAT_EN_PROCES = 'en_proces';
    public const ESTAT_TANCAT = 'tancat';

    public const ESTATS = [
        self::ESTAT_NOU => 'Nou',
        self::ESTAT_CONTACTAT => 'Contactat',
        self::ESTAT_EN_PROCES => 'En procés',
        self::ESTAT_TANCAT => 'Tancat',
    ];

    public function __construct(
        private ?int $id = null,
        private string $nom = '',
        private string $email = '',
        private ?string $telefon = null,
        private ?string $empresa = null,
        private string $missatge = '',
        private ?string $fitxer = null,
        private string $estat = self::ESTAT_NOU,
        private ?string $data_creacio = null,
        private ?string $data_actualitzacio = null
    ) {
        $this->setEstat($estat);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getNom(): string
    {
        return $this->nom;
    }

    public function setNom(string $nom): void
    {
        $this->nom = $nom;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getTelefon(): ?string
    {
        return $this->telefon;
    }

    public function setTelefon(?string $telefon): void
    {
        $this->telefon = $telefon;
    }

    public function getEmpresa(): ?string
    {
        return $this->empresa;
    }

    public function setEmpresa(?string $empresa): void
    {
        $this->empresa = $empresa;
    }

    public function getMissatge(): string
    {
        return $this->missatge;
    }

    public function setMissatge(string $missatge): void
    {
        $this->missatge = $missatge;
    }

    public function getFitxer(): ?string
    {
        return $this->fitxer;
    }

    public function setFitxer(?string $fitxer): void
    {
        $this->fitxer = $fitxer;
    }

    public function getEstat(): string
    {
        return $this->estat;
    }

    public function setEstat(string $estat): void
    {
        if (!array_key_exists($estat, self::ESTATS)) {
            throw new InvalidArgumentException("Estat no vàlid: $estat");
        }

        $this->estat = $estat;
    }

    public function getEstatText(): string
    {
        return self::ESTATS[$this->estat];
    }

    public function getDataCreacio(): ?string
    {
        return $this->data_creacio;
    }

    public function getDataActualitzacio(): ?string
    {
        return $this->data_actualitzacio;
    }
}
```

Alguns detalls:

- Cada paràmetre amb `private` en el constructor **declara i assigna alhora** la propietat. Sense aquesta sintaxi caldria escriure deu declaracions i deu assignacions (`$this->nom = $nom;`...).
- Les **constants** (`ESTAT_NOU`...) eviten escriure el text `'nou'` per tot el codi i els errors de picatge. `ESTATS` guarda, a més, el text que mostrarem en les vistes.
- El constructor reutilitza `setEstat()` per a **validar** l'estat: un model ben fet no permet estats que no existisquen en la base de dades.
- Les dates no tenen *setter*: les genera MySQL (`DEFAULT CURRENT_TIMESTAMP`).

::: tip Bona pràctica
Com que el constructor té molts paràmetres, en crear un lead utilitzarem **arguments amb nom** (PHP 8), igual que vam fer amb `setcookie()` en la unitat 3. No cal recordar l'ordre i podem ometre els que no necessitem:

```php
$lead = new Lead(
    nom: 'Marta Ferrer',
    email: 'marta@exemple.com',
    missatge: 'Voldria informació.'
);
```
:::

::: warning Atenció (un detall per al punt 6)
Quan PDO crea objectes amb `FETCH_CLASS`, per defecte **assigna les propietats i després crida el constructor**, que sobreescriuria les dades llegides amb els valors per defecte. Ho evitarem més endavant amb el flag `PDO::FETCH_PROPS_LATE`.
:::

### 4.2. La classe `Usuari`

Crea el fitxer `www/src/Models/Usuari.php`:

```php
<?php

declare(strict_types=1);

namespace App\Models;

class Usuari
{
    public function __construct(
        private ?int $id = null,
        private string $nom = '',
        private string $email = '',
        private string $contrasenya = '',
        private ?string $data_creacio = null
    ) {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getNom(): string
    {
        return $this->nom;
    }

    public function setNom(string $nom): void
    {
        $this->nom = $nom;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getContrasenya(): string
    {
        return $this->contrasenya;
    }

    public function xifrarContrasenya(string $text): void
    {
        $this->contrasenya = password_hash($text, PASSWORD_DEFAULT);
    }

    public function verificarContrasenya(string $text): bool
    {
        return password_verify($text, $this->contrasenya);
    }

    public function getDataCreacio(): ?string
    {
        return $this->data_creacio;
    }
}
```

La contrasenya mereix una atenció especial:

- La propietat `contrasenya` guarda **sempre el hash**, mai el text en clar.
- No hi ha `setContrasenya()`: per a canviar-la s'utilitza `xifrarContrasenya()`, que genera el hash amb `password_hash()` (punt 6 de la unitat 3).
- `verificarContrasenya()` compara el text escrit per l'usuari amb el hash amb `password_verify()`. Ho utilitzarem en el login (punt 11).

### 4.3. Comprovació

Substitueix el bloc PHP del principi de `www/public/index.php` per aquest codi, que crea un lead i un usuari:

```php
<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use App\Models\Lead;
use App\Models\Usuari;

$lead = new Lead(
    nom: 'Marta Ferrer',
    email: 'marta@exemple.com',
    missatge: 'Voldria informació.'
);
$lead->setEstat(Lead::ESTAT_CONTACTAT);

$usuari = new Usuari(nom: 'Administrador', email: 'admin@techleads.test');
$usuari->xifrarContrasenya('admin123');
$contrasenyaCorrecta = $usuari->verificarContrasenya('admin123') ? 'sí' : 'no';
?>
```

I mostra les dades dins del `<main>`, en lloc del paràgraf anterior:

```php
<h1 class="text-5xl font-semibold tracking-tight">
    <?= htmlspecialchars($lead->getNom()) ?>
</h1>
<p class="mt-8 text-xl leading-relaxed text-slate-600">
    Estat: <?= htmlspecialchars($lead->getEstatText()) ?>
</p>
<p class="mt-4 text-xl leading-relaxed text-slate-600">
    La contrasenya de l'usuari és correcta: <?= $contrasenyaCorrecta ?>
</p>
```

Visita <http://localhost:8080/public/>. Hauries de veure `Marta Ferrer`, l'estat `Contactat` i que la contrasenya és correcta.

::: tip Prova l'error
Canvia l'estat per un valor que no existisca, per exemple `$lead->setEstat('adeu');`. PHP llançarà una `InvalidArgumentException`: és la validació del model en acció.
:::

📌 **A recordar:** cada taula té una classe de model amb propietats privades i tipades, del mateix nom que les columnes; les propietats promocionades del constructor eviten repetir codi; el model valida les seues dades (`setEstat()`) i no sap res de la base de dades.

## 5. La interfície `IDbAccess`

Fins ara hem creat el model (`Lead`, `Usuari`). Ara necessitem **llegir i guardar** eixos objectes en la base de dades. Abans de fer-ho, definirem **què** ha de poder fer qualsevol classe d'accés a dades, sense dir encara **com**.

Ho farem amb una **interfície**, concepte que ja coneixes de Java i que vam repassar en la unitat 3 (punt 8).

### 5.1. Per què una interfície?

Les operacions bàsiques sobre una taula són sempre les mateixes: les del **CRUD** (*Create, Read, Update, Delete*). Si totes les classes d'accés a dades les implementen amb els mateixos noms, guanyem:

- **Uniformitat**: `LeadDAO` i `UsuariDAO` es fan servir exactament igual.
- **Contracte**: si una classe diu que implementa `IDbAccess`, PHP **obliga** que tinga tots els mètodes.
- **Flexibilitat**: el controlador pot dependre de la interfície i no d'una classe concreta. Si algun dia canviem MySQL per un altre sistema, el controlador no canvia.

| Operació | Mètode | Retorna |
| --- | --- | --- |
| Llegir tots | `getAll()` | `array` d'objectes |
| Llegir un | `getById(int $id)` | L'objecte, o `null` si no existeix |
| Crear | `insert(object $objecte)` | L'`id` del nou registre |
| Modificar | `update(object $objecte)` | `true` si s'ha executat correctament |
| Eliminar | `delete(int $id)` | `true` si s'ha eliminat el registre |

### 5.2. Crear la interfície

Crea el fitxer `www/src/Database/IDbAccess.php`:

```php
<?php

declare(strict_types=1);

namespace App\Database;

/**
 * Operacions bàsiques (CRUD) d'accés a dades.
 *
 * @template T of object
 */
interface IDbAccess
{
    /**
     * @return array<int, T> Tots els registres.
     */
    public function getAll(): array;

    /**
     * @return T|null L'objecte amb eixe id, o null si no existeix.
     */
    public function getById(int $id): ?object;

    /**
     * @param T $objecte
     * @return int L'id generat pel nou registre.
     */
    public function insert(object $objecte): int;

    /**
     * @param T $objecte
     * @return bool true si la consulta s'ha executat correctament.
     */
    public function update(object $objecte): bool;

    /**
     * @return bool true si s'ha eliminat el registre.
     */
    public function delete(int $id): bool;
}
```

Alguns detalls:

- Una interfície només conté **signatures**: no hi ha cos dels mètodes (`{ ... }`) ni propietats.
- Tots els mètodes d'una interfície són `public` per definició.
- El nom comença per **`I`** (`IDbAccess`), una convenció coneguda de Java i C#. És el nom que utilitzarem en el curs.
- Està en el namespace `App\Database`, així que viu en `src/Database/`, igual que farà `DBConnection` (punt 7).

::: info Nota
PHP **no té genèrics** (com `List<Lead>` en Java). Per això, la interfície rep `object` i indica amb PHPDoc (`@template T of object`) quin tipus d'objecte tractarà cada implementació. Els editors com VS Code l'entenen i ens ajuden amb l'autocompletat, però PHP l'ignora en executar.
:::

### 5.3. Com la implementarem (avançament)

En el punt 8 crearem una classe per cada entitat. Així serà l'esquelet de `LeadDAO` (**no el crees encara**, perquè fins que no estiguen tots els mètodes PHP donaria error):

```php
<?php

declare(strict_types=1);

namespace App\DAO;

use App\Database\IDbAccess;
use App\Models\Lead;

/**
 * @implements IDbAccess<Lead>
 */
class LeadDAO implements IDbAccess
{
    public function getAll(): array
    {
        // ...
    }

    public function getById(int $id): ?object
    {
        // ...
    }

    public function insert(object $objecte): int
    {
        // ...
    }

    public function update(object $objecte): bool
    {
        // ...
    }

    public function delete(int $id): bool
    {
        // ...
    }
}
```

Fixa't en dues coses:

- `implements IDbAccess` és la paraula clau que **signa el contracte**.
- El comentari `@implements IDbAccess<Lead>` indica a l'editor que `T` és `Lead` en aquesta classe.

### 5.4. El problema dels paràmetres: `object` i no `Lead`

Una pregunta lògica és: «per què `insert(object $objecte)` i no `insert(Lead $lead)`?» Prova d'escriure això i mira què passa:

```php
// ❌ Així NO funciona
class LeadDAO implements IDbAccess
{
    public function insert(Lead $lead): int
    {
        // ...
    }
}
```

PHP mostra un error fatal:

```text
Fatal error: Declaration of App\DAO\LeadDAO::insert(App\Models\Lead $lead): int
must be compatible with App\Database\IDbAccess::insert(object $objecte): int
```

::: danger Error crític
**La classe no pot ser més restrictiva que la interfície amb els paràmetres.** La interfície promet que `insert()` accepta *qualsevol* objecte. Si `LeadDAO` només accepta `Lead`, estaria trencant eixa promesa: algú que utilitze la interfície podria passar-li un `Usuari` i el contracte deixaria de complir-se.
:::

La solució que aplicarem en el punt 8 és mantindre `object` en la signatura i **comprovar el tipus real** dins del mètode:

```php
public function insert(object $objecte): int
{
    if (!$objecte instanceof Lead) {
        throw new InvalidArgumentException('S\'esperava un objecte Lead.');
    }

    // A partir d'ací, $objecte és un Lead i l'editor ho sap
    // ...
}
```

::: tip Bona pràctica
Amb `instanceof` fem dues coses: protegim el mètode d'objectes incorrectes i, a més, l'editor reconeix `$objecte` com a `Lead` a partir d'eixe punt, de manera que ens ofereix l'autocompletat dels seus mètodes.
:::

### 5.5. Mètodes que no estan en la interfície

La interfície només recull el CRUD **comú**. El projecte necessita operacions específiques que no tenen sentit per a totes les entitats, com ara:

- Llistat amb **filtres i paginació** de leads.
- **Canviar l'estat** d'un lead.
- **Buscar un usuari pel seu email** (per al login).

Eixos mètodes els afegirem directament a `LeadDAO` i `UsuariDAO`, a més dels de la interfície. Per això, quan un controlador necessite aquestes operacions, utilitzarà la classe concreta (`LeadDAO`) i no la interfície.

### 5.6. Comprovació

La interfície no es pot instanciar (`new IDbAccess()` donaria error), però podem comprovar que l'autocàrrega la troba. Afig aquesta línia al bloc PHP de `www/public/index.php`:

```php
use App\Database\IDbAccess;

$interficieCarregada = interface_exists(IDbAccess::class) ? 'sí' : 'no';
```

I mostra-ho dins del `<main>`:

```php
<p class="mt-4 text-xl leading-relaxed text-slate-600">
    La interfície IDbAccess s'ha carregat: <?= $interficieCarregada ?>
</p>
```

Visita <http://localhost:8080/public/>: ha d'indicar `sí`.

::: warning Atenció
Si apareix `no`, comprova que el fitxer està en `src/Database/IDbAccess.php`, que el `namespace` és `App\Database` i que el nom del fitxer coincideix exactament amb el de la interfície.
:::

📌 **A recordar:** una interfície defineix **què** ha de fer una classe, no **com**; `IDbAccess` recull el CRUD genèric (`getAll`, `getById`, `insert`, `update`, `delete`); les implementacions no poden restringir el tipus dels paràmetres, per això rebem `object` i comprovem amb `instanceof`.

📖 Documentació oficial

[PHP: Interfícies d'objectes](https://www.php.net/manual/es/language.oop5.interfaces.php)

## 6. PDO

**PDO** (*PHP Data Objects*) és l'extensió de PHP per a accedir a bases de dades de forma orientada a objectes. Té una gran avantatge: l'API és **la mateixa** per a MySQL, PostgreSQL, SQLite i altres, i només canvia la cadena de connexió.

::: info Nota
En el `Dockerfile` del punt 1 ja instal·lem l'extensió `pdo_mysql`, que és el controlador de PDO per a MySQL.
:::

::: warning Atenció
Dins d'un fitxer amb `namespace`, la classe `PDO` pertany a l'espai de noms global. Cal importar-la amb `use PDO;` (o escriure `\PDO`). Si no, PHP buscarà `App\Database\PDO` i donarà un error de classe no trobada.
:::

### 6.1. Connexió

Per a connectar necessitem quatre dades: el **DSN** (cadena de connexió), l'usuari, la contrasenya i unes **opcions**.

```php
$pdo = new PDO(
    'mysql:host=db;dbname=leads;charset=utf8mb4',
    'leads_user',
    'leads_pass',
    [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]
);
```

| Part | Significat |
| --- | --- |
| `mysql:` | Controlador que utilitzem |
| `host=db` | Nom del servei de MySQL en el `docker-compose.yml` (**no** `localhost`) |
| `dbname=leads` | Base de dades |
| `charset=utf8mb4` | Codificació de caràcters de la connexió |
| `ATTR_ERRMODE => ERRMODE_EXCEPTION` | Si hi ha un error, PDO llança una excepció `PDOException` |
| `ATTR_EMULATE_PREPARES => false` | Utilitza consultes preparades **natives** de MySQL i retorna els tipus reals (`int` per als enters) |

::: tip Bona pràctica
Les dos opcions són pràcticament obligatòries. Sense `ERRMODE_EXCEPTION`, PDO falla en silenci i és molt difícil depurar els errors.
:::

### 6.2. Consultes preparades

Una consulta SQL es pot executar de dues maneres. Una és **perillosa** i l'altra **segura**.

#### El perill: SQL Injection

Si construïm la consulta concatenant dades de l'usuari, qualsevol pot modificar-la:

```php
// ❌ MAI FAGES AIXÒ
$email = $_POST['email'];
$sql = "SELECT * FROM usuaris WHERE email = '$email'";
$resultat = $pdo->query($sql);
```

Si l'usuari escriu `' OR '1'='1` en el camp, la consulta es transforma en:

```sql
SELECT * FROM usuaris WHERE email = '' OR '1'='1'
```

...i retorna **tots els usuaris**. Un atacant podria iniciar sessió sense contrasenya o, fins i tot, esborrar taules.

::: danger Error crític
**Mai** concatenes variables de l'usuari dins d'una consulta SQL. Escapar amb `htmlspecialchars()` **no** protegeix de la injecció SQL: serveix per a HTML, no per a SQL.
:::

#### La solució: consultes preparades

Una consulta preparada separa la **sentència SQL** de les **dades**. MySQL rep primer l'estructura i després els valors, que mai s'interpreten com a codi.

```php
$stmt = $pdo->prepare('SELECT * FROM usuaris WHERE email = :email');
$stmt->execute([':email' => $email]);
```

Hi ha dos tipus de marcadors:

```php
// Marcadors amb nom (els recomanem: són més llegibles)
$stmt = $pdo->prepare('SELECT * FROM leads WHERE estat = :estat AND empresa = :empresa');
$stmt->execute([':estat' => 'nou', ':empresa' => 'Pons Consulting']);

// Marcadors posicionals (?)
$stmt = $pdo->prepare('SELECT * FROM leads WHERE estat = ? AND empresa = ?');
$stmt->execute(['nou', 'Pons Consulting']);
```

També podem enllaçar els valors un a un amb `bindValue()` i indicar-ne el tipus:

```php
$stmt = $pdo->prepare('SELECT * FROM leads WHERE id = :id');
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$stmt->execute();
```

::: tip Bona pràctica
Amb `execute([...])` n'hi ha prou per a la majoria de consultes. Utilitza `bindValue()` quan necessites indicar el tipus, com ara amb `LIMIT`.
:::

::: warning Atenció (`LIMIT` i `OFFSET`)
Amb `ATTR_EMULATE_PREPARES => false`, MySQL no accepta valors de text en `LIMIT` i `OFFSET`. Com que `execute([...])` envia tot com a text, cal utilitzar `bindValue()` amb `PDO::PARAM_INT`. Ho necessitarem en la paginació del punt 10:

```php
$stmt = $pdo->prepare('SELECT * FROM leads LIMIT :limit OFFSET :offset');
$stmt->bindValue(':limit', 10, PDO::PARAM_INT);
$stmt->bindValue(':offset', 0, PDO::PARAM_INT);
$stmt->execute();
```
:::

Per a les cerques amb `LIKE`, els símbols `%` s'afigen al **valor**, no a la consulta:

```php
$stmt = $pdo->prepare('SELECT * FROM leads WHERE nom LIKE :cerca');
$stmt->execute([':cerca' => "%$text%"]);
```

### 6.3. Llegir resultats

Després d'executar un `SELECT`, el resultat s'obté amb `fetch()` (una fila) o `fetchAll()` (totes). El format depén del **mode de lectura**:

| Mode | Retorna | Exemple d'accés |
| --- | --- | --- |
| `PDO::FETCH_ASSOC` | Array associatiu | `$fila['nom']` |
| `PDO::FETCH_OBJ` | Objecte anònim (`stdClass`) | `$fila->nom` |
| `PDO::FETCH_CLASS` | Objecte de **la nostra classe** | `$lead->getNom()` |

```php
$stmt = $pdo->prepare('SELECT * FROM leads WHERE id = :id');
$stmt->execute([':id' => 1]);

$fila = $stmt->fetch(PDO::FETCH_ASSOC);   // ['id' => 1, 'nom' => 'Marta Ferrer', ...]
// $fila = $stmt->fetch(PDO::FETCH_OBJ);  // $fila->nom
```

Nosaltres utilitzarem **sempre `FETCH_CLASS`**, perquè així PDO crea directament objectes `Lead` o `Usuari`:

```php
$stmt = $pdo->query('SELECT * FROM leads');
$leads = $stmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, Lead::class);

foreach ($leads as $lead) {
    echo $lead->getNom() . ' - ' . $lead->getEstatText();
}
```

Per a obtindre **un sol objecte**, es configura el mode amb `setFetchMode()` i es crida a `fetch()`:

```php
$stmt = $pdo->prepare('SELECT * FROM leads WHERE id = :id');
$stmt->execute([':id' => $id]);
$stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, Lead::class);

$lead = $stmt->fetch();   // un objecte Lead, o false si no existeix
```

::: info Nota
`fetch()` retorna **`false`** quan no hi ha més files. En el nostre `getById()` ho convertirem a `null`.
:::

#### Per què `FETCH_PROPS_LATE`?

En el punt 4 vam avisar-ho. Aquest *flag* fa que PDO **cride primer el constructor** i **després assigne** les dades de la fila:

| | Ordre |
| --- | --- |
| Sense `FETCH_PROPS_LATE` | 1. Assigna les propietats · 2. Executa el constructor (**sobreescriu** les dades amb els valors per defecte) |
| Amb `FETCH_PROPS_LATE` | 1. Executa el constructor · 2. Assigna les propietats (**les dades de la BD manen**) |

PDO pot escriure en propietats `private` i les columnes de la taula coincideixen amb els noms de les propietats (`data_creacio`, `fitxer`...). Per això vam triar eixos noms en el model.

::: danger Error crític
Si t'oblides de `PDO::FETCH_PROPS_LATE`, els objectes apareixeran amb els valors per defecte (nom buit, estat `nou`...) en lloc de les dades de la base de dades.
:::

### 6.4. Inserir, modificar i eliminar

Els `INSERT`, `UPDATE` i `DELETE` es fan igual: `prepare()` i `execute()`.

```php
// INSERT
$stmt = $pdo->prepare(
    'INSERT INTO leads (nom, email, telefon, empresa, missatge, fitxer, estat)
     VALUES (:nom, :email, :telefon, :empresa, :missatge, :fitxer, :estat)'
);
$stmt->execute([
    ':nom' => 'Marta Ferrer',
    ':email' => 'marta@exemple.com',
    ':telefon' => null,
    ':empresa' => null,
    ':missatge' => 'Voldria informació.',
    ':fitxer' => null,
    ':estat' => 'nou',
]);

$idNou = (int) $pdo->lastInsertId();   // id generat per AUTO_INCREMENT
```

```php
// UPDATE
$stmt = $pdo->prepare('UPDATE leads SET estat = :estat WHERE id = :id');
$stmt->execute([':estat' => 'contactat', ':id' => 1]);

$filesAfectades = $stmt->rowCount();
```

```php
// DELETE
$stmt = $pdo->prepare('DELETE FROM leads WHERE id = :id');
$stmt->execute([':id' => 1]);

$eliminat = $stmt->rowCount() > 0;
```

| Mètode | Què fa |
| --- | --- |
| `lastInsertId()` | Retorna l'`id` del darrer registre inserit (en text, per això el convertim amb `(int)`) |
| `rowCount()` | Nombre de files afectades per l'últim `INSERT`, `UPDATE` o `DELETE` |

::: warning Atenció
En un `UPDATE`, `rowCount()` només compta les files que **han canviat realment**. Si guardes un lead sense modificar cap camp, MySQL retorna `0`. Per això el nostre `update()` de la interfície retornarà si la consulta s'ha executat, no si hi ha hagut canvis.
:::

### 6.5. Gestió d'errors: `PDOException`

Com que hem activat `ERRMODE_EXCEPTION`, qualsevol error (credencials incorrectes, taula inexistent, SQL mal escrit...) llança una **`PDOException`**. L'agafem amb `try/catch`:

```php
try {
    $pdo = new PDO($dsn, $usuari, $contrasenya, $opcions);
    $stmt = $pdo->query('SELECT * FROM taula_que_no_existeix');
} catch (PDOException $e) {
    echo 'Error: ' . $e->getMessage();
}
```

Dades útils d'una excepció:

| Mètode | Retorna |
| --- | --- |
| `getMessage()` | Missatge d'error |
| `getCode()` | Codi d'error SQLSTATE (per exemple, `23000` per a una clau duplicada) |

Un cas típic és intentar inserir un usuari amb un correu repetit (la columna `email` és `UNIQUE`):

```php
try {
    $stmt->execute([':email' => 'admin@techleads.test', /* ... */]);
} catch (PDOException $e) {
    if ($e->getCode() === '23000') {
        echo 'Ja existeix un usuari amb aquest correu.';
    } else {
        throw $e;
    }
}
```

::: danger Error crític
**Mai mostres `$e->getMessage()` a l'usuari final.** Els missatges de MySQL poden revelar noms de taules, columnes o fins i tot el servidor. En un entorn real, enregistra l'error amb `error_log($e->getMessage());` i mostra un missatge genèric: «S'ha produït un error, torna-ho a provar».
:::

### 6.6. Comprovació

Fem una prova amb PDO directament, abans d'encapsular-ho en una classe. Crea el fitxer **temporal** `www/public/prova_pdo.php`:

```php
<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use App\Models\Lead;

$leads = [];
$error = null;

try {
    $pdo = new PDO(
        'mysql:host=db;dbname=leads;charset=utf8mb4',
        'leads_user',
        'leads_pass',
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );

    $stmt = $pdo->prepare('SELECT * FROM leads WHERE estat <> :estat');
    $stmt->execute([':estat' => Lead::ESTAT_TANCAT]);
    $leads = $stmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, Lead::class);
} catch (PDOException $e) {
    $error = $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prova PDO</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white text-slate-900 antialiased">
    <main class="mx-auto max-w-3xl px-6 py-24">
        <h1 class="text-5xl font-semibold tracking-tight">Prova PDO</h1>

        <?php if ($error !== null) : ?>
            <p class="mt-8 text-xl leading-relaxed text-red-600">
                Error: <?= htmlspecialchars($error) ?>
            </p>
        <?php else : ?>
            <p class="mt-8 text-xl leading-relaxed text-slate-600">
                Leads no tancats: <?= count($leads) ?>
            </p>
            <ul class="mt-10 space-y-6">
                <?php foreach ($leads as $lead) : ?>
                    <li class="border-b border-slate-200 pb-6">
                        <p class="text-2xl font-medium">
                            <?= htmlspecialchars($lead->getNom()) ?>
                        </p>
                        <p class="mt-2 text-lg text-slate-500">
                            <?= htmlspecialchars($lead->getEmail()) ?>
                            · <?= htmlspecialchars($lead->getEstatText()) ?>
                        </p>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </main>
</body>
</html>
```

Visita <http://localhost:8080/public/prova_pdo.php>. Amb les dades d'exemple del punt 2 has de veure **4 leads** (tots menys el de l'estat `tancat`), amb el seu nom, correu i estat.

::: tip Prova els errors
Canvia la contrasenya `leads_pass` per una altra i recarrega la pàgina: veuràs el missatge de la `PDOException` en pantalla. Eixe és, precisament, el que **no** hem de fer en producció.
:::

::: info Nota
Aquest fitxer és només una prova. Quan acabem el punt 7 el podràs **eliminar**, perquè la connexió viurà en una classe pròpia.
:::

📌 **A recordar:** PDO es configura amb `ERRMODE_EXCEPTION` i `EMULATE_PREPARES => false`; totes les consultes amb dades de l'usuari han de ser **preparades**; `FETCH_CLASS | FETCH_PROPS_LATE` crea objectes del nostre model; els errors es gestionen amb `PDOException` i mai s'ensenyen a l'usuari.

📖 Documentació oficial

[PHP: PDO](https://www.php.net/manual/es/book.pdo.php)

## 7. La classe `DBConnection`

En el punt anterior hem connectat amb PDO escrivint el DSN, l'usuari i la contrasenya directament en el fitxer. Això té dos problemes quan el projecte creix:

- **Codi repetit**: tots els fitxers que necessiten la base de dades haurien de repetir la connexió.
- **Credencials escampades**: canviar la contrasenya implicaria modificar molts fitxers.

La solució és crear **una classe que s'encarregue de la connexió**, llegir-ne les dades d'un **fitxer de configuració** i **passar la connexió** a les classes que la necessiten. Açò últim és la **injecció de dependències**.

### 7.1. Fitxer de configuració

Les dades de connexió no han d'estar dins del codi de les classes. Les guardem en un fitxer a part. Crea `www/config/config.php`:

```php
<?php

declare(strict_types=1);

return [
    'db' => [
        'host' => 'db',
        'name' => 'leads',
        'user' => 'leads_user',
        'password' => 'leads_pass',
        'charset' => 'utf8mb4',
    ],
];
```

Un fitxer PHP pot **retornar un valor** amb `return`. Ací retorna un array, i qui el carregue amb `require` el rebrà en una variable:

```php
$config = require __DIR__ . '/config/config.php';
echo $config['db']['host'];   // db
```

::: warning Atenció
Aquest fitxer conté credencials. Si utilitzes Git, **no el pugues al repositori** (afegeix `config/config.php` al `.gitignore`). En un servidor real, els valors s'obtenen de variables d'entorn o de fitxers fora de l'arrel web.
:::

### 7.2. Injecció de dependències

Una **dependència** és qualsevol objecte que una classe necessita per a funcionar. Els futurs DAO necessitaran una connexió a la base de dades (`PDO`). Hi ha dues maneres d'aconseguir-la.

**Opció A: la classe es crea la seua pròpia dependència**

```php
// ❌ El DAO decideix com i amb quines dades es connecta
class LeadDAO
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = new PDO('mysql:host=db;dbname=leads', 'leads_user', 'leads_pass');
    }
}
```

**Opció B: la classe rep la dependència des de fora (injecció)**

```php
// ✅ El DAO només diu què necessita
class LeadDAO
{
    public function __construct(private PDO $pdo)
    {
    }
}
```

En l'opció B, qui crea el DAO és qui **li passa** la connexió:

```php
$leadDao = new LeadDAO($pdo);
$usuariDao = new UsuariDAO($pdo);   // la mateixa connexió, compartida
```

| | Opció A (crea la seua dependència) | Opció B (injecció) |
| --- | --- | --- |
| Connexions | Una per cada DAO | Una de sola, compartida |
| Dades de connexió | Repetides o amagades dins de la classe | En un únic lloc |
| Dependències | Amagades dins del codi | Visibles en el constructor |
| Proves | Només funciona amb MySQL real | Es pot passar una altra connexió |

::: tip Bona pràctica
Les dependències d'una classe s'han de poder llegir mirant només el seu **constructor**. Si el constructor és buit però la classe fa `new PDO(...)` per dins, té una dependència amagada.
:::

::: info Nota
La injecció de dependències és la base de frameworks com **Laravel**, que té un *contenidor de serveis* que crea i injecta els objectes automàticament. Ací ho farem a mà per a entendre què hi ha darrere.
:::

### 7.3. Crear la classe `DBConnection`

`DBConnection` s'encarrega d'una única cosa: **crear l'objecte `PDO`** a partir de la configuració i gestionar els errors de connexió. Crea el fitxer `www/src/Database/DBConnection.php`:

```php
<?php

declare(strict_types=1);

namespace App\Database;

use PDO;
use PDOException;
use RuntimeException;

class DBConnection
{
    private PDO $pdo;

    /**
     * @param array{host: string, name: string, user: string, password: string, charset: string} $config
     */
    public function __construct(private array $config)
    {
        $dsn = sprintf(
            'mysql:host=%s;dbname=%s;charset=%s',
            $config['host'],
            $config['name'],
            $config['charset']
        );

        try {
            $this->pdo = new PDO($dsn, $config['user'], $config['password'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        } catch (PDOException $e) {
            error_log('Error de connexió amb la BD: ' . $e->getMessage());

            throw new RuntimeException(
                'No s\'ha pogut connectar amb la base de dades.',
                0,
                $e
            );
        }
    }

    public function getConnection(): PDO
    {
        return $this->pdo;
    }
}
```

Anem a vore les peces:

- El constructor **rep la configuració** (`array $config`) en lloc de llegir el fitxer ell mateix. Per tant, la classe és reutilitzable amb qualsevol configuració: és també injecció de dependències.
- `sprintf()` construeix el DSN substituint cada `%s` pel valor corresponent, més llegible que concatenar.
- El comentari PHPDoc `array{host: string, ...}` documenta quines claus té l'array, i l'editor ens ajuda amb l'autocompletat.
- `error_log(...)` + `RuntimeException`: l'error real es **guarda en el registre del servidor** i a la resta del codi arriba un missatge genèric, tal com vam explicar en el punt anterior.
- `new RuntimeException($missatge, 0, $e)`: el tercer paràmetre conserva l'excepció original per si cal depurar.

::: tip Bona pràctica
La classe **no sap res** de `Lead` ni de `Usuari`. Només s'encarrega de la connexió. Cada classe té una única responsabilitat.
:::

### 7.4. On es munten els objectes: `bootstrap.php`

Algú ha de **crear** els objectes i passar-los a qui els necessita. Eixe lloc es diu **arrel de composició** (*composition root*). En el nostre projecte serà un fitxer que carreguen tots els punts d'entrada (`public/`, `admin/`).

Crea `www/config/bootstrap.php`:

```php
<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use App\Database\DBConnection;

$config = require __DIR__ . '/config.php';

try {
    $pdo = (new DBConnection($config['db']))->getConnection();
} catch (RuntimeException $e) {
    http_response_code(500);
    exit($e->getMessage());
}
```

Què fa, pas a pas:

1. Carrega l'**autocàrrega** de Composer.
2. Llig la **configuració**.
3. Crea la connexió amb `DBConnection` i en guarda l'objecte `PDO` en la variable `$pdo`.
4. Si la connexió falla, respon amb un **error 500** i el missatge genèric, i atura l'execució.

Qualsevol fitxer d'entrada només necessita una línia:

```php
require_once __DIR__ . '/../config/bootstrap.php';

// A partir d'ací, $pdo i $config ja estan disponibles
```

Quan creem els DAO i els controladors (punts 8 i 10), ho farem així, començant per la connexió i passant cada peça a la següent:

```php
require_once __DIR__ . '/../config/bootstrap.php';

$leadDao = new LeadDAO($pdo);
$controlador = new LeadController($leadDao);
```

```text
DBConnection → PDO → LeadDAO → LeadController
```

::: info Nota
Una alternativa és el patró **Singleton**: una classe amb constructor privat que només permet una instància, accessible des de qualsevol lloc amb `DBConnection::getInstance()`. És còmode, però crea una dependència **global i amagada**, i dificulta les proves. Per això en aquest curs apostem per la injecció.
:::

### 7.5. Gestió d'errors

Si la connexió falla (per exemple, perquè el contenidor de MySQL no està arrancat), `DBConnection` llança una `RuntimeException` amb un missatge **segur**, i `bootstrap.php` el mostra amb un error 500.

El missatge real de MySQL ha quedat registrat amb `error_log()`. Pots consultar-lo amb:

```bash
docker compose logs web
```

### 7.6. Comprovació

Ja no necessitem el fitxer temporal del punt anterior: **elimina `www/public/prova_pdo.php`**.

Substitueix tot el contingut de `www/public/index.php` per aquest codi:

```php
<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/bootstrap.php';

$versio = (string) $pdo->query('SELECT VERSION()')->fetchColumn();
$total = (int) $pdo->query('SELECT COUNT(*) FROM leads')->fetchColumn();
?>
<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leads</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white text-slate-900 antialiased">
    <main class="mx-auto max-w-3xl px-6 py-32">
        <h1 class="text-5xl font-semibold tracking-tight">Leads</h1>
        <p class="mt-8 text-xl leading-relaxed text-slate-600">
            Connexió correcta amb MySQL <?= htmlspecialchars($versio) ?>.
        </p>
        <p class="mt-4 text-xl leading-relaxed text-slate-600">
            Leads a la base de dades: <?= $total ?>
        </p>
    </main>
</body>
</html>
```

Visita <http://localhost:8080/public/>. Has de veure la versió de MySQL i els 5 leads de les dades d'exemple.

::: tip Prova l'error
Para el contenidor de la base de dades amb `docker compose stop db` i recarrega la pàgina: veuràs el missatge genèric, sense detalls tècnics. Torna'l a arrancar amb `docker compose start db`.
:::

📌 **A recordar:** la connexió viu en una única classe i les dades de connexió en un fitxer de configuració; una classe no crea les seues dependències, **les rep pel constructor**; `bootstrap.php` crea els objectes i els passa a qui els necessita; els errors reals es registren i l'usuari només veu un missatge genèric.

📖 Documentació oficial

[PHP: Promoció de propietats del constructor](https://www.php.net/manual/es/language.oop5.decon.php#language.oop5.decon.constructor.promotion)

## 8. Implementacions de l'accés a dades (DAO)

Ja tenim el model (`Lead`, `Usuari`), el contracte (`IDbAccess`) i la connexió (`PDO`). Ara ho unim: crearem una classe per cada entitat que implemente la interfície i faça les consultes SQL.

Aquest patró es diu **DAO** (*Data Access Object*): tot el SQL d'una entitat viu en **un únic lloc**.

| Capa | Pot escriure SQL? |
| --- | --- |
| Vista | ❌ No |
| Controlador | ❌ No |
| **DAO** | ✅ Sí, és el seu únic treball |

El controlador mai escriurà una consulta: cridarà a `$leadDao->getAll()` o `$leadDao->delete($id)` i el DAO s'encarregarà de la resta.

### 8.1. La classe `LeadDAO`

Crea el fitxer `www/src/DAO/LeadDAO.php`:

```php
<?php

declare(strict_types=1);

namespace App\DAO;

use App\Database\IDbAccess;
use App\Models\Lead;
use InvalidArgumentException;
use PDO;

/**
 * @implements IDbAccess<Lead>
 */
class LeadDAO implements IDbAccess
{
    public function __construct(private PDO $pdo)
    {
    }

    /**
     * @return Lead[]
     */
    public function getAll(): array
    {
        $stmt = $this->pdo->query(
            'SELECT * FROM leads ORDER BY data_creacio DESC, id DESC'
        );

        return $stmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, Lead::class);
    }

    public function getById(int $id): ?Lead
    {
        $stmt = $this->pdo->prepare('SELECT * FROM leads WHERE id = :id');
        $stmt->execute([':id' => $id]);
        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, Lead::class);

        $lead = $stmt->fetch();

        return $lead === false ? null : $lead;
    }

    public function insert(object $objecte): int
    {
        if (!$objecte instanceof Lead) {
            throw new InvalidArgumentException('S\'esperava un objecte Lead.');
        }

        $stmt = $this->pdo->prepare(
            'INSERT INTO leads (nom, email, telefon, empresa, missatge, fitxer, estat)
             VALUES (:nom, :email, :telefon, :empresa, :missatge, :fitxer, :estat)'
        );
        $stmt->execute([
            ':nom' => $objecte->getNom(),
            ':email' => $objecte->getEmail(),
            ':telefon' => $objecte->getTelefon(),
            ':empresa' => $objecte->getEmpresa(),
            ':missatge' => $objecte->getMissatge(),
            ':fitxer' => $objecte->getFitxer(),
            ':estat' => $objecte->getEstat(),
        ]);

        $id = (int) $this->pdo->lastInsertId();
        $objecte->setId($id);

        return $id;
    }

    public function update(object $objecte): bool
    {
        if (!$objecte instanceof Lead) {
            throw new InvalidArgumentException('S\'esperava un objecte Lead.');
        }

        if ($objecte->getId() === null) {
            throw new InvalidArgumentException('El lead no té id, no es pot modificar.');
        }

        $stmt = $this->pdo->prepare(
            'UPDATE leads
             SET nom = :nom, email = :email, telefon = :telefon, empresa = :empresa,
                 missatge = :missatge, fitxer = :fitxer, estat = :estat
             WHERE id = :id'
        );

        return $stmt->execute([
            ':nom' => $objecte->getNom(),
            ':email' => $objecte->getEmail(),
            ':telefon' => $objecte->getTelefon(),
            ':empresa' => $objecte->getEmpresa(),
            ':missatge' => $objecte->getMissatge(),
            ':fitxer' => $objecte->getFitxer(),
            ':estat' => $objecte->getEstat(),
            ':id' => $objecte->getId(),
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare('DELETE FROM leads WHERE id = :id');
        $stmt->execute([':id' => $id]);

        return $stmt->rowCount() > 0;
    }

    public function canviarEstat(int $id, string $estat): bool
    {
        if (!array_key_exists($estat, Lead::ESTATS)) {
            throw new InvalidArgumentException("Estat no vàlid: $estat");
        }

        $stmt = $this->pdo->prepare('UPDATE leads SET estat = :estat WHERE id = :id');

        return $stmt->execute([':estat' => $estat, ':id' => $id]);
    }
}
```

Anem a vore les peces:

- **`__construct(private PDO $pdo)`**: la connexió **s'injecta** (punt 7) i es guarda amb una propietat promocionada.
- **`getAll()`**: executa un `SELECT` sense paràmetres, per això val `query()`. Els més recents primer. `fetchAll` amb `FETCH_CLASS` retorna un array d'objectes `Lead`.
- **`getById()`**: consulta preparada. `fetch()` retorna `false` si no hi ha cap fila, i ho convertim en `null`, tal com diu la interfície.
- **`insert()`**: comprova el tipus amb `instanceof` (explicat en el punt 5), insereix i retorna l'`id` generat. A més, **assigna l'id a l'objecte** amb `setId()`, perquè el programa que l'ha creat el tinga disponible.
- **`update()`**: exigeix que el lead tinga `id`; sense ell no sabríem quina fila modificar.
- **`delete()`**: retorna `true` només si s'ha eliminat alguna fila.
- **`canviarEstat()`**: mètode **propi** de `LeadDAO` (no està en la interfície). Actualitza només la columna `estat`, sense necessitat de llegir i reescriure tot el lead.

::: tip Bona pràctica
Fixa't en les signatures: `getById()` retorna `?Lead` i no `?object`, que és el que diu la interfície. **El tipus de retorn pot ser més específic**, però **els paràmetres no** (per això `insert()` rep `object`). Regla pràctica: una implementació pot **prometre més**, però no pot **exigir més**.
:::

::: info Nota
El mètode `canviarEstat()` valida l'estat abans de llançar la consulta. Així l'error és una `InvalidArgumentException` clara i no un error de MySQL per un valor fora de l'`ENUM`.
:::

### 8.2. La classe `UsuariDAO`

Crea el fitxer `www/src/DAO/UsuariDAO.php`:

```php
<?php

declare(strict_types=1);

namespace App\DAO;

use App\Database\IDbAccess;
use App\Models\Usuari;
use InvalidArgumentException;
use PDO;

/**
 * @implements IDbAccess<Usuari>
 */
class UsuariDAO implements IDbAccess
{
    public function __construct(private PDO $pdo)
    {
    }

    /**
     * @return Usuari[]
     */
    public function getAll(): array
    {
        $stmt = $this->pdo->query('SELECT * FROM usuaris ORDER BY nom');

        return $stmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, Usuari::class);
    }

    public function getById(int $id): ?Usuari
    {
        $stmt = $this->pdo->prepare('SELECT * FROM usuaris WHERE id = :id');
        $stmt->execute([':id' => $id]);
        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, Usuari::class);

        $usuari = $stmt->fetch();

        return $usuari === false ? null : $usuari;
    }

    public function getByEmail(string $email): ?Usuari
    {
        $stmt = $this->pdo->prepare('SELECT * FROM usuaris WHERE email = :email');
        $stmt->execute([':email' => $email]);
        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, Usuari::class);

        $usuari = $stmt->fetch();

        return $usuari === false ? null : $usuari;
    }

    public function insert(object $objecte): int
    {
        if (!$objecte instanceof Usuari) {
            throw new InvalidArgumentException('S\'esperava un objecte Usuari.');
        }

        $stmt = $this->pdo->prepare(
            'INSERT INTO usuaris (nom, email, contrasenya)
             VALUES (:nom, :email, :contrasenya)'
        );
        $stmt->execute([
            ':nom' => $objecte->getNom(),
            ':email' => $objecte->getEmail(),
            ':contrasenya' => $objecte->getContrasenya(),
        ]);

        $id = (int) $this->pdo->lastInsertId();
        $objecte->setId($id);

        return $id;
    }

    public function update(object $objecte): bool
    {
        if (!$objecte instanceof Usuari) {
            throw new InvalidArgumentException('S\'esperava un objecte Usuari.');
        }

        if ($objecte->getId() === null) {
            throw new InvalidArgumentException('L\'usuari no té id, no es pot modificar.');
        }

        $stmt = $this->pdo->prepare(
            'UPDATE usuaris
             SET nom = :nom, email = :email, contrasenya = :contrasenya
             WHERE id = :id'
        );

        return $stmt->execute([
            ':nom' => $objecte->getNom(),
            ':email' => $objecte->getEmail(),
            ':contrasenya' => $objecte->getContrasenya(),
            ':id' => $objecte->getId(),
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare('DELETE FROM usuaris WHERE id = :id');
        $stmt->execute([':id' => $id]);

        return $stmt->rowCount() > 0;
    }
}
```

És pràcticament igual que `LeadDAO`, amb dues diferències:

- Les columnes són les d'`usuaris`.
- Afig el mètode propi **`getByEmail()`**, que necessitarem per al login (punt 11): buscarem l'usuari pel correu i després comprovarem la contrasenya amb `verificarContrasenya()`.

::: info Nota
El `UsuariDAO` guarda el **hash** que ja té l'objecte `Usuari`. Qui crea o modifica la contrasenya ha de cridar abans a `$usuari->xifrarContrasenya('text')`; el DAO no xifra res, només guarda.
:::

### 8.3. Les excepcions no es capturen en el DAO

Els mètodes del DAO **no tenen `try/catch`**: si MySQL falla, la `PDOException` **puja** fins al controlador. Hi ha una raó: el DAO no sap què dir a l'usuari, però el controlador sí.

Un exemple és inserir un usuari amb un correu repetit (la columna és `UNIQUE`). Més endavant, el controlador ho gestionarà així:

```php
try {
    $usuariDao->insert($usuari);
} catch (PDOException $e) {
    if ($e->getCode() === '23000') {
        $error = 'Ja existeix un usuari amb aquest correu.';
    } else {
        throw $e;
    }
}
```

### 8.4. Comprovació

Fem una prova de totes les operacions. Crea el fitxer **temporal** `www/public/prova_dao.php`:

```php
<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/bootstrap.php';

use App\DAO\LeadDAO;
use App\DAO\UsuariDAO;
use App\Models\Lead;

$leadDao = new LeadDAO($pdo);
$usuariDao = new UsuariDAO($pdo);
$passos = [];

$passos[] = 'Leads inicials: ' . count($leadDao->getAll());

$lead = new Lead(nom: 'Prova DAO', email: 'prova@exemple.com', missatge: 'Lead de prova');
$id = $leadDao->insert($lead);
$passos[] = "Lead inserit amb id $id";

$lead = $leadDao->getById($id);
$passos[] = 'Llegit: ' . $lead->getNom() . ' (' . $lead->getEstatText() . ')';

$lead->setNom('Prova DAO modificada');
$lead->setEstat(Lead::ESTAT_CONTACTAT);
$leadDao->update($lead);
$passos[] = 'Modificat: ' . $leadDao->getById($id)->getNom();

$leadDao->canviarEstat($id, Lead::ESTAT_TANCAT);
$passos[] = 'Estat canviat a: ' . $leadDao->getById($id)->getEstatText();

$leadDao->delete($id);
$passos[] = 'Eliminat. Encara existeix? ' . ($leadDao->getById($id) === null ? 'no' : 'sí');

$usuari = $usuariDao->getByEmail('admin@techleads.test');
if ($usuari !== null) {
    $correcta = $usuari->verificarContrasenya('admin123') ? 'sí' : 'no';
    $passos[] = 'Usuari trobat: ' . $usuari->getNom() . ' · contrasenya admin123 correcta: ' . $correcta;
} else {
    $passos[] = 'Usuari no trobat';
}
?>
<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prova DAO</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white text-slate-900 antialiased">
    <main class="mx-auto max-w-3xl px-6 py-24">
        <h1 class="text-5xl font-semibold tracking-tight">Prova DAO</h1>
        <ol class="mt-10 list-decimal space-y-5 pl-6 text-xl leading-relaxed text-slate-600">
            <?php foreach ($passos as $pas) : ?>
                <li><?= htmlspecialchars($pas) ?></li>
            <?php endforeach; ?>
        </ol>
    </main>
</body>
</html>
```

Visita <http://localhost:8080/public/prova_dao.php>. Has de veure una llista numerada com aquesta:

```text
1. Leads inicials: 5
2. Lead inserit amb id 6
3. Llegit: Prova DAO (Nou)
4. Modificat: Prova DAO modificada
5. Estat canviat a: Tancat
6. Eliminat. Encara existeix? no
7. Usuari trobat: Administrador · contrasenya admin123 correcta: sí
```

El número de l'`id` pot variar. Cada vegada que recarregues la pàgina, el lead de prova es crea i s'elimina, així que la base de dades queda igual.

::: warning Atenció
Si falla algun pas, comprova que `FETCH_PROPS_LATE` està en tots els `fetch`, i que els noms de les propietats del model coincideixen amb les columnes. Un error típic és que apareguen els objectes amb els valors per defecte (nom buit, estat `nou`).
:::

::: info Nota
`prova_dao.php` és només una prova: **elimina'l** en acabar. Al punt 10 el substituiran els controladors.
:::

📌 **A recordar:** el DAO és l'únic lloc amb SQL; implementa `IDbAccess` i rep el `PDO` pel constructor; totes les consultes amb dades són preparades; els fetch utilitzen `FETCH_CLASS | FETCH_PROPS_LATE`; els mètodes específics (`canviarEstat`, `getByEmail`) s'afigen a la classe concreta; les excepcions de PDO es deixen pujar fins al controlador.

📖 Documentació oficial

[PHP: PDOStatement](https://www.php.net/manual/es/class.pdostatement.php)

## 9. Funcions auxiliars

Hi ha tasques que es repeteixen en molts llocs del projecte: escapar el text en mostrar-lo i pujar fitxers. En lloc de copiar el codi en cada controlador, les agrupem en una **classe d'utilitats**.

### 9.1. La classe `Helpers`

En la unitat 3 vam escriure funcions soltes com `e()`. Ara, amb l'autocàrrega PSR-4, **les funcions soltes no es carreguen soles**: Composer només sap trobar **classes**. Per això les posem com a **mètodes estàtics** d'una classe:

```php
use App\Helpers\Helpers;

echo Helpers::escapar($text);
```

Un mètode `static` es crida amb `Classe::mètode()` i **no necessita crear cap objecte** (`new Helpers()`). És el que volem per a utilitats que no guarden cap estat.

::: info Nota
Els noms van en valencià i en `camelCase`, tal com acordem en PSR-12: `escapar()`, `pujarImatge()`, `eliminarImatge()`.
:::

### 9.2. Netejar, validar i escapar

Són tres operacions diferents i es fan en **moments diferents**:

| Operació | Quan | Per a què | On |
| --- | --- | --- | --- |
| **Netejar** | En **rebre** les dades | Llevar els espais sobrants | `trim()` en el controlador |
| **Validar** | Abans de **guardar** | Comprovar que el correu és un correu, que el nom no està buit... | Controlador |
| **Escapar** | En **mostrar** les dades | Evitar que un text s'interprete com a HTML (XSS) | `Helpers::escapar()`, en les vistes |

```php
$nom = trim($_POST['nom'] ?? '');
// "  Marta Ferrer  "  →  "Marta Ferrer"
```

::: danger Error crític
**No escapes (`htmlspecialchars`) les dades abans de guardar-les en la base de dades.** Si ho fas, guardaràs `&lt;` en lloc de `<` i, en mostrar-les, es veurien dos vegades escapades. Les dades es guarden **netes** i s'escapen **en el moment de mostrar-les**.
:::

Molts tutorials antics ensenyen una funció que fa `trim()`, `stripslashes()` i `htmlspecialchars()` tot junt. Ja no és una bona pràctica: `stripslashes()` només servia per a una funcionalitat de PHP que ja no existeix, i escapar en entrar barreja dos conceptes.

### 9.3. Crear la classe: escapament

Crea el fitxer `www/src/Helpers/Helpers.php`. De moment, només amb el primer mètode:

```php
<?php

declare(strict_types=1);

namespace App\Helpers;

class Helpers
{
    /**
     * Escapa un text perquè es puga mostrar de forma segura en HTML.
     */
    public static function escapar(?string $text): string
    {
        return htmlspecialchars($text ?? '', ENT_QUOTES, 'UTF-8');
    }
}
```

Exemple d'ús:

```php
echo Helpers::escapar('<script>alert(1)</script>');
// &lt;script&gt;alert(1)&lt;/script&gt;
```

Ens fixem en dos detalls:

- El paràmetre és `?string` (accepta `null`) i utilitzem `?? ''`, així no hi ha errors si el valor no existeix.
- `ENT_QUOTES` fa que s'escapen tant les cometes dobles com les simples, i `'UTF-8'` indica la codificació.

### 9.4. Pujar imatges

Ja vam vore en la unitat 3 com funciona la pujada de fitxers: el formulari ha de tindre `enctype="multipart/form-data"` i PHP deixa les dades en `$_FILES`:

```php
$_FILES['imatge'] = [
    'name' => 'foto.png',          // nom original (NO fiar-se'n)
    'type' => 'image/png',         // tipus segons el navegador (NO fiar-se'n)
    'tmp_name' => '/tmp/phpAb12',  // fitxer temporal en el servidor
    'error' => 0,                  // codi d'error (0 = correcte)
    'size' => 48213,               // mida en bytes
];
```

Hem de comprovar-ho **tot en el servidor**, perquè el navegador (i per tant l'usuari) pot enviar el que vulga:

| Comprovació | Per què |
| --- | --- |
| `error` | Detectar fitxers massa grans o pujades incompletes |
| `size` | Limitar la mida (2 MB) |
| `is_uploaded_file()` | Assegurar que el fitxer ve realment d'una pujada HTTP |
| Tipus **real** amb `finfo` | El camp `type` el controla l'usuari; el contingut no es pot falsejar tan fàcilment |
| Extensió **escollida per nosaltres** | Mai ens fiem de l'extensió del nom original |
| Nom **únic** | Evitar sobreescriure fitxers i noms maliciosos |

Aquesta és la classe `Helpers` completa, amb els mètodes nous:

```php
<?php

declare(strict_types=1);

namespace App\Helpers;

use finfo;
use RuntimeException;

class Helpers
{
    private const TIPUS_PERMESOS = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp',
        'image/gif' => 'gif',
    ];

    /**
     * Escapa un text perquè es puga mostrar de forma segura en HTML.
     */
    public static function escapar(?string $text): string
    {
        return htmlspecialchars($text ?? '', ENT_QUOTES, 'UTF-8');
    }

    /**
     * Valida i guarda una imatge pujada.
     *
     * @param array $fitxer Element de $_FILES
     * @return string|null Nom del fitxer guardat, o null si no s'ha pujat cap
     * @throws RuntimeException Si el fitxer no és vàlid
     */
    public static function pujarImatge(
        array $fitxer,
        string $carpetaDesti,
        int $midaMaxima = 2 * 1024 * 1024
    ): ?string {
        $error = $fitxer['error'] ?? UPLOAD_ERR_NO_FILE;

        if ($error === UPLOAD_ERR_NO_FILE) {
            return null;
        }

        if ($error !== UPLOAD_ERR_OK) {
            throw new RuntimeException(match ($error) {
                UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE
                    => 'El fitxer supera la mida màxima permesa.',
                UPLOAD_ERR_PARTIAL
                    => 'El fitxer s\'ha pujat només parcialment.',
                default => 'No s\'ha pogut pujar el fitxer.',
            });
        }

        if ($fitxer['size'] > $midaMaxima) {
            throw new RuntimeException('El fitxer supera la mida màxima permesa.');
        }

        if (!is_uploaded_file($fitxer['tmp_name'])) {
            throw new RuntimeException('El fitxer no és vàlid.');
        }

        $tipus = (new finfo(FILEINFO_MIME_TYPE))->file($fitxer['tmp_name']);

        if (!isset(self::TIPUS_PERMESOS[$tipus])) {
            throw new RuntimeException('Només s\'admeten imatges JPG, PNG, WEBP o GIF.');
        }

        if (!is_dir($carpetaDesti) || !is_writable($carpetaDesti)) {
            error_log("La carpeta de pujades no existeix o no és escrivible: $carpetaDesti");

            throw new RuntimeException('No s\'ha pogut guardar el fitxer.');
        }

        $extensio = self::TIPUS_PERMESOS[$tipus];
        $nomFinal = uniqid('img_', true) . '.' . $extensio;

        if (!move_uploaded_file($fitxer['tmp_name'], $carpetaDesti . '/' . $nomFinal)) {
            throw new RuntimeException('No s\'ha pogut guardar el fitxer.');
        }

        return $nomFinal;
    }

    /**
     * Elimina una imatge guardada anteriorment (si existeix).
     */
    public static function eliminarImatge(?string $nom, string $carpeta): void
    {
        if ($nom === null || $nom === '') {
            return;
        }

        $ruta = $carpeta . '/' . basename($nom);

        if (is_file($ruta)) {
            unlink($ruta);
        }
    }
}
```

Les peces més importants:

- **`pujarImatge()` retorna `null`** si l'usuari no ha seleccionat cap fitxer. La columna `fitxer` de `leads` admet `NULL`, així que la imatge és **opcional**.
- **Si hi ha algun problema, llança `RuntimeException`** amb un missatge pensat per a l'usuari. El controlador l'agafarà i el mostrarà en el formulari.
- **`match`** (PHP 8) és com un `switch` que retorna un valor; ací tria el missatge d'error segons el codi.
- **`finfo`** llig el contingut del fitxer i en deduïx el tipus MIME real. La **extensió** es tria a partir d'una llista pròpia (`TIPUS_PERMESOS`), mai del nom original.
- **`uniqid('img_', true)`** genera un nom únic basat en el temps, com vam fer en la unitat 3, per exemple `img_65f1c2a3b4d5e6.12345678.png`.
- **`eliminarImatge()`** utilitza `basename()` perquè, si el nom guardat tinguera `../`, no puga eixir de la carpeta.
- **El nom que guardem** en la base de dades és només el nom del fitxer, no la ruta completa.

::: tip Bona pràctica
Els mètodes d'aquesta classe **no accedeixen a `$_FILES` ni a `$_POST` directament**: reben les dades per paràmetre. Així són fàcils de provar i de reutilitzar des de qualsevol controlador.
:::

::: info Nota
`uniqid()` es basa en l'hora en microsegons, així que els noms són **únics però previsibles**. Per a imatges d'un projecte didàctic és suficient. Si els fitxers foren **privats** (per exemple, documents amb dades personals), convindria un nom impossible d'endevinar, com `bin2hex(random_bytes(16))`, perquè la carpeta `uploads/` és accessible des del navegador.
:::

::: warning Atenció
La mida màxima que posem (2 MB) no pot superar el límit de PHP (`upload_max_filesize`, que per defecte també és 2 MB, i `post_max_size`). Si puges el nostre límit, hauràs de pujar també els de PHP.
:::

### 9.5. La carpeta `uploads`

Crea la carpeta `www/uploads/` (si no existeix) i, dins, un fitxer buit `.gitkeep` perquè Git la conserve.

El servidor Apache s'executa amb l'usuari `www-data` dins del contenidor i ha de poder **escriure** en ella. En Windows i macOS normalment funciona directament. Si en Linux apareix un error de permisos (el missatge «No s'ha pogut guardar el fitxer»), executa:

```bash
docker compose exec web chown -R www-data:www-data /var/www/html/uploads
```

Les imatges guardades són accessibles des del navegador en `/uploads/nom_del_fitxer.png`, i es mostren amb:

```php
<img src="/uploads/<?= Helpers::escapar($lead->getFitxer()) ?>" alt="Imatge del lead">
```

### 9.6. Comprovació

Crea el fitxer **temporal** `www/public/prova_helpers.php`:

```php
<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use App\Helpers\Helpers;

$nom = null;
$imatge = null;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom'] ?? '');

    try {
        $imatge = Helpers::pujarImatge($_FILES['imatge'] ?? [], __DIR__ . '/../uploads');
    } catch (RuntimeException $e) {
        $error = $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prova Helpers</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white text-slate-900 antialiased">
    <main class="mx-auto max-w-3xl px-6 py-24">
        <h1 class="text-5xl font-semibold tracking-tight">Prova Helpers</h1>

        <form method="post" enctype="multipart/form-data" class="mt-12 space-y-8">
            <div>
                <label for="nom" class="block text-lg font-medium">Nom</label>
                <input type="text" id="nom" name="nom"
                       class="mt-3 w-full rounded-lg border border-slate-300 px-4 py-3 text-xl">
            </div>
            <div>
                <label for="imatge" class="block text-lg font-medium">Imatge</label>
                <input type="file" id="imatge" name="imatge" class="mt-3 block text-lg">
            </div>
            <button type="submit"
                    class="rounded-lg bg-slate-900 px-6 py-3 text-lg font-medium text-white">
                Enviar
            </button>
        </form>

        <?php if ($_SERVER['REQUEST_METHOD'] === 'POST') : ?>
            <div class="mt-16 space-y-4 text-xl leading-relaxed text-slate-600">
                <p>Nom amb trim(): «<?= Helpers::escapar($nom) ?>»</p>
                <?php if ($error !== null) : ?>
                    <p class="text-red-600"><?= Helpers::escapar($error) ?></p>
                <?php elseif ($imatge === null) : ?>
                    <p>No s'ha pujat cap imatge.</p>
                <?php else : ?>
                    <p>Imatge guardada com <?= Helpers::escapar($imatge) ?></p>
                    <img src="/uploads/<?= Helpers::escapar($imatge) ?>"
                         alt="Imatge pujada" class="mt-6 max-h-72 rounded-lg">
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </main>
</body>
</html>
```

Visita <http://localhost:8080/public/prova_helpers.php> i prova aquests casos:

| Prova | Resultat esperat |
| --- | --- |
| Nom `   Marta   ` i una imatge PNG | `Nom amb trim(): «Marta»` i la imatge es mostra |
| Nom `<b>hola</b>` | Es mostra el text literal, **sense** negreta |
| Sense seleccionar cap fitxer | `No s'ha pujat cap imatge.` |
| Un PDF o un `.txt` | `Només s'admeten imatges JPG, PNG, WEBP o GIF.` |
| Un fitxer `.txt` **renomenat** a `.jpg` | També es rebutja (comprovem el contingut, no el nom) |
| Una imatge de més de 2 MB | `El fitxer supera la mida màxima permesa.` |

Comprova que el fitxer apareix dins de `www/uploads/` amb un nom que comença per `img_`.

::: info Nota
`prova_helpers.php` és només una prova: **elimina'l** en acabar, i buida la carpeta `uploads/` de les imatges de prova. En el punt 10 el formulari real utilitzarà `Helpers`.
:::

📌 **A recordar:** les utilitats viuen en mètodes estàtics de `Helpers` perquè PSR-4 no carrega funcions soltes; es **neteja** amb `trim()` en entrar, es **valida** en el controlador i s'**escapa** en mostrar; mai ens fiem del nom ni del tipus que envia el navegador: es comprova el contingut real, es tria l'extensió i es genera un nom únic.

📖 Documentació oficial

[PHP: Pujada de fitxers](https://www.php.net/manual/es/features.file-upload.php)

## 10. CRUD de leads

Ja tenim tots els ingredients: model, DAO, connexió i utilitats. Ara els unim en la part que veu l'usuari. Un **CRUD** són les quatre operacions bàsiques sobre una entitat: *Create, Read, Update, Delete*. Les construirem per blocs:

| Bloc | Operació | Pantalla |
| --- | --- | --- |
| 10.1 | *Read* (llista) | Llistat amb filtres i paginació |
| 10.2 | *Read* (un) | Detall d'un lead |
| 10.3 | *Create* i *Update* | Formulari públic i edició en l'administració |
| 10.4 | *Delete* i estat | Eliminar un lead i canviar-ne l'estat |

El flux de cada pantalla és el que vam veure en el punt 3:

```text
admin/index.php → LeadController → LeadDAO → Base de dades
                        ↓
                  vistes/admin/leads/llistat.php → HTML
```

### 10.1. Llistat amb filtres i paginació

El llistat de l'administració mostrarà els leads de 3 en 3 i permetrà:

- **Filtrar per estat** (`?estat=nou`).
- **Cercar** per nom, correu o empresa (`?cerca=pons`).
- **Paginar** (`?pagina=2`), mantenint els filtres.

Els tres paràmetres viatgen per la URL (`GET`), perquè no modifiquen dades i així es pot compartir o guardar l'enllaç.

#### 10.1.1. Ampliar el `LeadDAO`

El DAO necessita dos mètodes nous: un que torne **els leads d'una pàgina** i un altre que torne **quants n'hi ha en total** (per a calcular el nombre de pàgines).

Afig aquests mètodes a la classe `LeadDAO` (`www/src/DAO/LeadDAO.php`), abans de la clau final:

```php
    /**
     * @return Lead[]
     */
    public function filtrar(
        ?string $estat,
        ?string $cerca,
        int $pagina,
        int $perPagina
    ): array {
        $sql = 'SELECT * FROM leads WHERE 1 = 1';

        if ($estat !== null) {
            $sql .= ' AND estat = :estat';
        }

        if ($cerca !== null) {
            $sql .= ' AND (nom LIKE :cerca_nom'
                . ' OR email LIKE :cerca_email'
                . ' OR empresa LIKE :cerca_empresa)';
        }

        $sql .= ' ORDER BY data_creacio DESC, id DESC LIMIT :limit OFFSET :offset';

        $stmt = $this->pdo->prepare($sql);

        if ($estat !== null) {
            $stmt->bindValue(':estat', $estat);
        }

        if ($cerca !== null) {
            $stmt->bindValue(':cerca_nom', "%$cerca%");
            $stmt->bindValue(':cerca_email', "%$cerca%");
            $stmt->bindValue(':cerca_empresa', "%$cerca%");
        }

        $stmt->bindValue(':limit', $perPagina, PDO::PARAM_INT);
        $stmt->bindValue(':offset', ($pagina - 1) * $perPagina, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, Lead::class);
    }

    public function comptar(?string $estat, ?string $cerca): int
    {
        $sql = 'SELECT COUNT(*) FROM leads WHERE 1 = 1';

        if ($estat !== null) {
            $sql .= ' AND estat = :estat';
        }

        if ($cerca !== null) {
            $sql .= ' AND (nom LIKE :cerca_nom'
                . ' OR email LIKE :cerca_email'
                . ' OR empresa LIKE :cerca_empresa)';
        }

        $stmt = $this->pdo->prepare($sql);

        if ($estat !== null) {
            $stmt->bindValue(':estat', $estat);
        }

        if ($cerca !== null) {
            $stmt->bindValue(':cerca_nom', "%$cerca%");
            $stmt->bindValue(':cerca_email', "%$cerca%");
            $stmt->bindValue(':cerca_empresa', "%$cerca%");
        }

        $stmt->execute();

        return (int) $stmt->fetchColumn();
    }
```

Els dos mètodes segueixen els mateixos **tres passos**:

1. **Construir el SQL** com un text, afegint un tros per cada filtre que l'usuari haja triat.
2. **Preparar** la consulta amb `prepare()`.
3. **Lligar els valors** amb `bindValue()`, només dels filtres que s'han afegit, i executar.

Algunes idees clau:

- **`WHERE 1 = 1`**: és una condició que sempre és certa. Ens permet afegir cada filtre amb ` AND ...` sense preocupar-nos de si és el primer o no. Si no hi ha cap filtre, la consulta torna tots els leads.
- **`.=`** afig text al final d'una variable: `$sql .= ' AND estat = :estat'` equival a `$sql = $sql . ' AND estat = :estat'`.
- **`LIMIT` i `OFFSET`**: el `LIMIT` indica quantes files volem i l'`OFFSET` quantes saltem. Per a la pàgina 2 amb 3 per pàgina: `LIMIT 3 OFFSET 3`.
- **`bindValue()` amb `PDO::PARAM_INT`**: com vam vore en el punt 6, els números de `LIMIT` i `OFFSET` s'han de lligar com a enters.

::: tip Bona pràctica
Hem concatenat text dins del SQL, i això pot semblar una contradicció amb la regla de «mai concatenes». **No ho és**: només concatenem **text fix escrit per nosaltres** (`' AND estat = :estat'`). Les dades de l'usuari (`$estat`, `$cerca`) **mai** entren en la cadena SQL: viatgen sempre per `bindValue()`.
:::

::: warning Atenció
Cada marcador amb nom només es pot utilitzar **una vegada** en la consulta quan les consultes preparades són natives (`ATTR_EMULATE_PREPARES => false`). Per això, per a cercar en tres columnes no val reutilitzar `:cerca`: hem creat `:cerca_nom`, `:cerca_email` i `:cerca_empresa` amb el mateix valor. Si les repetíem, PDO llançaria l'error `Invalid parameter number`.
:::

::: info Nota
`filtrar()` i `comptar()` repeteixen la construcció del filtre. Ho deixem així perquè és **fàcil de llegir**. Més endavant, en un projecte gran, es podria extraure a un mètode privat compartit.
:::

#### 10.1.2. Un mètode nou en `Helpers`

Els enllaços de la paginació han de **mantindre els filtres** (si estàs en `?estat=nou`, el botó «Següent» ha d'anar a `?pagina=2&estat=nou`). Necessitem un mètode que construïsca eixa cadena d'URL, així que l'afegim a `Helpers`.

Afig aquest mètode a la classe `Helpers` (`www/src/Helpers/Helpers.php`):

```php
    /**
     * Construeix una cadena de consulta (query string) a partir d'un array.
     * Els valors buits o nuls s'ometen.
     *
     * @param array<string, mixed> $parametres
     */
    public static function construirUrl(array $parametres): string
    {
        $resultat = [];

        foreach ($parametres as $clau => $valor) {
            if ($valor !== null && $valor !== '') {
                $resultat[$clau] = $valor;
            }
        }

        return '?' . http_build_query($resultat);
    }
```

Exemples d'ús:

```php
echo Helpers::construirUrl(['pagina' => 2, 'estat' => 'nou', 'cerca' => null]);
// ?pagina=2&estat=nou

echo Helpers::construirUrl(['pagina' => 3, 'estat' => null, 'cerca' => 'pons i fills']);
// ?pagina=3&cerca=pons+i+fills
```

- El `foreach` copia a `$resultat` només els paràmetres que tenen valor, de manera que els filtres inactius no apareixen en la URL.
- **`http_build_query()`** és una funció de PHP que converteix un array en `clau=valor&clau=valor` i **codifica els caràcters especials** (els espais, els accents, el `&`...).

#### 10.1.3. La classe base `BaseController`

Tots els controladors necessitaran fer el mateix: **carregar una vista** passant-li dades, i **redirigir** a una altra URL. Ho escrivim una vegada en una classe base. Crea `www/src/Controllers/BaseController.php`:

```php
<?php

declare(strict_types=1);

namespace App\Controllers;

abstract class BaseController
{
    /**
     * Carrega una vista de la carpeta vistes/ passant-li dades.
     *
     * @param array<string, mixed> $dades
     */
    protected function renderitzar(string $vista, array $dades = []): void
    {
        extract($dades);

        require __DIR__ . '/../../vistes/' . $vista . '.php';
    }

    protected function redirigir(string $url): never
    {
        header('Location: ' . $url);
        exit;
    }
}
```

- **`abstract`**: la classe no es pot instanciar directament; només serveix perquè altres l'hereten.
- **`extract($dades)`** converteix cada clau de l'array en una **variable**: amb `['leads' => $leads]`, la vista tindrà disponible la variable `$leads`.
- **`never`** (PHP 8.1) indica que el mètode **no torna mai** (acaba amb `exit`). Després d'un `header('Location: ...')` sempre cal aturar l'execució.
- **`protected`**: els mètodes només són visibles per a les classes filles.

::: info Nota
La carpeta `vistes/` queda dos nivells per damunt de `src/Controllers/` (`../../vistes/`), per això la ruta comença amb `__DIR__ . '/../../'`.
:::

#### 10.1.4. El `LeadController`

El controlador **llig la petició**, **valida** els paràmetres, **demana les dades al DAO** i **tria la vista**. Crea `www/src/Controllers/LeadController.php`:

```php
<?php

declare(strict_types=1);

namespace App\Controllers;

use App\DAO\LeadDAO;
use App\Models\Lead;

class LeadController extends BaseController
{
    private const PER_PAGINA = 3;

    public function __construct(private LeadDAO $leadDao)
    {
    }

    public function llistar(): void
    {
        $estat = $_GET['estat'] ?? '';
        $estat = array_key_exists($estat, Lead::ESTATS) ? $estat : null;

        $cerca = trim($_GET['cerca'] ?? '');
        $cerca = $cerca === '' ? null : $cerca;

        $pagina = max(1, (int) ($_GET['pagina'] ?? 1));

        $total = $this->leadDao->comptar($estat, $cerca);
        $totalPagines = max(1, (int) ceil($total / self::PER_PAGINA));
        $pagina = min($pagina, $totalPagines);

        $leads = $this->leadDao->filtrar($estat, $cerca, $pagina, self::PER_PAGINA);

        $this->renderitzar('admin/leads/llistat', [
            'titol' => 'Leads',
            'leads' => $leads,
            'total' => $total,
            'estat' => $estat,
            'cerca' => $cerca,
            'pagina' => $pagina,
            'totalPagines' => $totalPagines,
        ]);
    }
}
```

Pas a pas:

1. **Estat**: només s'accepta si és un dels estats vàlids (`Lead::ESTATS`). Qualsevol altre valor es convertix en `null`, és a dir, «sense filtre».
2. **Cerca**: `trim()` i, si queda buida, `null`.
3. **Pàgina**: `(int)` converteix el text a número; `max(1, ...)` evita pàgines negatives o zero.
4. **Total de pàgines**: `ceil()` arredoneix cap amunt (5 leads entre 3 per pàgina = 2 pàgines).
5. **`min($pagina, $totalPagines)`**: si algú escriu `?pagina=99`, el portem a l'última pàgina.
6. **Vista**: es passen totes les dades que necessita en un array.

::: tip Bona pràctica
**Mai ens fiem del que arriba per la URL.** L'usuari pot escriure `?estat=hacker` o `?pagina=-5`. El controlador ho **valida i normalitza** tot abans d'utilitzar-ho.
:::

::: info Nota
`PER_PAGINA` val 3 perquè, amb els 5 leads d'exemple, es puga vore la paginació. En un projecte real seria 10 o 20.
:::

#### 10.1.5. La vista

La vista **només mostra dades**: no fa consultes ni decisions de negoci. Primer creem el disseny comú (capçalera i peu), que reutilitzarem en totes les pantalles de l'administració.

Crea `www/vistes/layouts/capcalera.php`:

```php
<?php

use App\Helpers\Helpers;
?>
<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= Helpers::escapar($titol ?? 'Leads') ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white text-slate-900 antialiased">
    <header class="border-b border-slate-200">
        <div class="mx-auto flex max-w-5xl items-center justify-between px-6 py-6">
            <a href="/admin/" class="text-2xl font-semibold tracking-tight">Leads</a>
            <a href="/public/" class="text-lg text-slate-500 hover:text-slate-900">
                Formulari públic
            </a>
        </div>
    </header>
    <main class="mx-auto max-w-5xl px-6 py-16">
```

I `www/vistes/layouts/peu.php`:

```php
    </main>
</body>
</html>
```

Ara la vista del llistat. Crea `www/vistes/admin/leads/llistat.php`:

```php
<?php

declare(strict_types=1);

use App\Helpers\Helpers;
use App\Models\Lead;

$colors = [
    Lead::ESTAT_NOU => 'bg-blue-50 text-blue-700',
    Lead::ESTAT_CONTACTAT => 'bg-amber-50 text-amber-700',
    Lead::ESTAT_EN_PROCES => 'bg-violet-50 text-violet-700',
    Lead::ESTAT_TANCAT => 'bg-emerald-50 text-emerald-700',
];

// Enllaços de la paginació (mantenen els filtres actius)
$urlAnterior = Helpers::construirUrl([
    'pagina' => $pagina - 1,
    'estat' => $estat,
    'cerca' => $cerca,
]);
$urlSeguent = Helpers::construirUrl([
    'pagina' => $pagina + 1,
    'estat' => $estat,
    'cerca' => $cerca,
]);

require __DIR__ . '/../../layouts/capcalera.php';
?>
<h1 class="text-5xl font-semibold tracking-tight">Leads</h1>
<p class="mt-4 text-xl text-slate-500"><?= $total ?> resultats</p>

<form method="get" class="mt-12 flex flex-col gap-4 sm:flex-row">
    <input type="search" name="cerca" value="<?= Helpers::escapar($cerca) ?>"
           placeholder="Cerca per nom, correu o empresa"
           class="flex-1 rounded-lg border border-slate-300 px-5 py-4 text-xl">
    <select name="estat"
            class="rounded-lg border border-slate-300 px-5 py-4 text-xl">
        <option value="">Tots els estats</option>
        <?php foreach (Lead::ESTATS as $valor => $text) : ?>
            <option value="<?= Helpers::escapar($valor) ?>"
                <?= $estat === $valor ? 'selected' : '' ?>>
                <?= Helpers::escapar($text) ?>
            </option>
        <?php endforeach; ?>
    </select>
    <button type="submit"
            class="rounded-lg bg-slate-900 px-8 py-4 text-xl font-medium text-white">
        Filtrar
    </button>
</form>

<?php if ($leads === []) : ?>
    <p class="mt-16 text-xl text-slate-500">No s'ha trobat cap lead.</p>
<?php else : ?>
    <ul class="mt-12 divide-y divide-slate-200">
        <?php foreach ($leads as $lead) : ?>
            <li class="flex flex-col gap-4 py-8 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-2xl font-medium">
                        <?= Helpers::escapar($lead->getNom()) ?>
                    </p>
                    <p class="mt-2 text-lg text-slate-500">
                        <?= Helpers::escapar($lead->getEmail()) ?>
                        <?php if ($lead->getEmpresa() !== null) : ?>
                            · <?= Helpers::escapar($lead->getEmpresa()) ?>
                        <?php endif; ?>
                    </p>
                </div>
                <div class="flex items-center gap-6">
                    <span class="rounded-full px-4 py-2 text-base font-medium
                                 <?= $colors[$lead->getEstat()] ?>">
                        <?= Helpers::escapar($lead->getEstatText()) ?>
                    </span>
                    <span class="text-lg text-slate-400">
                        <?= date('d/m/Y', strtotime((string) $lead->getDataCreacio())) ?>
                    </span>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>

    <?php if ($totalPagines > 1) : ?>
        <nav class="mt-12 flex items-center justify-between text-lg">
            <?php if ($pagina > 1) : ?>
                <a href="<?= Helpers::escapar($urlAnterior) ?>"
                   class="font-medium hover:underline">← Anterior</a>
            <?php else : ?>
                <span class="text-slate-300">← Anterior</span>
            <?php endif; ?>

            <span class="text-slate-500">Pàgina <?= $pagina ?> de <?= $totalPagines ?></span>

            <?php if ($pagina < $totalPagines) : ?>
                <a href="<?= Helpers::escapar($urlSeguent) ?>"
                   class="font-medium hover:underline">Següent →</a>
            <?php else : ?>
                <span class="text-slate-300">Següent →</span>
            <?php endif; ?>
        </nav>
    <?php endif; ?>
<?php endif; ?>

<?php require __DIR__ . '/../../layouts/peu.php'; ?>
```

Detalls de la vista:

- **Variables disponibles**: `$leads`, `$total`, `$estat`, `$cerca`, `$pagina` i `$totalPagines` arriben gràcies a `extract()` del `BaseController`.
- **Tot el que ve de la base de dades o de l'usuari s'escapa** amb `Helpers::escapar()`, incloent el valor del camp de cerca i les URL. És la prevenció d'XSS que vam explicar en el punt 9.
- **Sintaxi alternativa** (`if (...) :` / `endif;`, `foreach (...) :` / `endforeach;`), pròpia de les vistes: queda més llegible que les claus quan es barreja amb HTML.
- **Disseny**: text gran, molt d'espai entre elements i només un color per a l'estat, de manera que la informació important es lligue d'un colp d'ull.

::: warning Atenció
El `LeadController` hereta de `BaseController`, que carrega les vistes amb `require`. Si la ruta de la vista està malament, PHP llançarà un error `Failed opening required`. Comprova que els fitxers estan en `www/vistes/admin/leads/` i `www/vistes/layouts/`.
:::

#### 10.1.6. El punt d'entrada

Falta el fitxer que rep la petició i **munta les peces**, igual que vam fer en `bootstrap.php`. Crea `www/admin/index.php`:

```php
<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/bootstrap.php';

use App\Controllers\LeadController;
use App\DAO\LeadDAO;

$controlador = new LeadController(new LeadDAO($pdo));
$controlador->llistar();
```

La cadena de dependències és la que vam dibuixar en el punt 7:

```text
DBConnection → PDO → LeadDAO → LeadController
```

#### 10.1.7. Comprovació

Elimina els fitxers temporals de proves (`prova_dao.php` i `prova_helpers.php`) i visita <http://localhost:8080/admin/>. Prova aquests casos:

| URL | Resultat esperat |
| --- | --- |
| `/admin/` | 3 leads (els més recents) i «Pàgina 1 de 2» |
| `/admin/?pagina=2` | Els 2 leads restants |
| `/admin/?estat=nou` | 2 leads, sense paginació |
| `/admin/?cerca=pons` | 1 lead (Joan Pons) |
| `/admin/?cerca=exemple.com&estat=tancat` | 1 lead (Pau Climent): els dos filtres es combinen |
| `/admin/?cerca=zzz` | «No s'ha trobat cap lead.» |
| `/admin/?pagina=99` | Es mostra l'última pàgina |
| `/admin/?pagina=-3` | Es mostra la pàgina 1 |
| `/admin/?estat=hacker` | Se n'ignora l'estat i es mostren tots |

També comprova que, en passar a la pàgina 2 amb un filtre actiu, el filtre **es manté** en l'enllaç.

::: tip Prova la injecció SQL
Escriu en el cercador `' OR 1=1 --` i envia el formulari. No trobarà res: el text es tracta com un valor més, no com a part de la consulta.
:::

::: danger Error crític
Ara mateix la carpeta `admin/` **és accessible per a qualsevol persona**. Fins que no implementem el login (punt 11) no hi guardes dades reals.
:::

📌 **A recordar:** el controlador valida els paràmetres de la URL i demana les dades al DAO; el DAO construeix el SQL concatenant text fix i lliga els valors amb `bindValue()`; `LIMIT` i `OFFSET` es lliguen com a enters; la vista només mostra dades i escapa tot el que imprimeix; cada marcador amb nom s'utilitza una sola vegada en la consulta.

📖 Documentació oficial

[PHP: PDOStatement::bindValue](https://www.php.net/manual/es/pdostatement.bindvalue.php)

### 10.2. Detall d'un lead

Ara que el llistat ja funciona, cada lead ha de tindre la seua **pàgina de detall**, amb totes les dades: telèfon, empresa, missatge complet, imatge i dates.

La URL serà `/admin/?accio=detall&id=3`. Fixa't que **no necessitem tocar el model ni el DAO**: `LeadDAO` ja té el mètode `getById()` des del punt 8. Només afegim una acció al controlador i una vista nova. Aquesta és l'avantatge de separar capes.

#### 10.2.1. Diverses accions: el paràmetre `accio`

Fins ara `admin/index.php` només feia una cosa: mostrar el llistat. Ara ha de poder fer-ne més, així que utilitzarem un paràmetre `accio` per a decidir quin mètode del controlador s'executa.

Substitueix el contingut de `www/admin/index.php`:

```php
<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/bootstrap.php';

use App\Controllers\LeadController;
use App\DAO\LeadDAO;

$controlador = new LeadController(new LeadDAO($pdo));

$accio = $_GET['accio'] ?? 'llistar';

match ($accio) {
    'llistar' => $controlador->llistar(),
    'detall' => $controlador->detall(),
    default => $controlador->noTrobat(),
};
```

- Si no hi ha paràmetre `accio`, s'executa `llistar` (el comportament d'abans).
- **`match`** tria el mètode segons el valor. L'opció `default` agafa **qualsevol altra cosa**: així, `?accio=hola` no trencarà l'aplicació, sinó que mostrarà una pàgina d'error.
- Només s'executen les accions que **nosaltres hem llistat**. L'usuari mai decidix quin mètode es crida.

::: danger Error crític
**No facis mai** `$controlador->$accio()` amb un valor que ve de la URL. Permetria executar qualsevol mètode públic del controlador. Per això utilitzem `match` amb una llista tancada d'accions.
:::

Ens faltaran `detall()` i `noTrobat()`. Anem per ordre.

#### 10.2.2. La pàgina d'error 404

Quan un lead no existisca (`?id=999`) o l'acció siga desconeguda, hem de respondre amb un **error 404** i una pàgina clara. Com que ho necessitarem en més d'un controlador, ho afegim a `BaseController`.

Afig aquest mètode a `www/src/Controllers/BaseController.php`, després de `redirigir()`:

```php
    public function noTrobat(): void
    {
        http_response_code(404);

        $this->renderitzar('errors/404', ['titol' => 'No trobat']);
    }
```

- **`http_response_code(404)`** canvia el codi HTTP de la resposta. No és només una pàgina que diu «no trobat»: el navegador i els cercadors també reben el codi 404.
- És `public` perquè el crida el punt d'entrada (`admin/index.php`), a més de les classes filles.

Crea la vista `www/vistes/errors/404.php`:

```php
<?php

declare(strict_types=1);

require __DIR__ . '/../layouts/capcalera.php';
?>
<h1 class="text-5xl font-semibold tracking-tight">No trobat</h1>
<p class="mt-8 text-xl leading-relaxed text-slate-600">
    La pàgina o el lead que busques no existeix.
</p>
<a href="/admin/"
   class="mt-12 inline-block rounded-lg bg-slate-900 px-8 py-4 text-xl font-medium text-white">
    Tornar al llistat
</a>

<?php require __DIR__ . '/../layouts/peu.php'; ?>
```

#### 10.2.3. L'acció `detall()` en el controlador

Afig aquest mètode a `LeadController` (`www/src/Controllers/LeadController.php`), després de `llistar()`:

```php
    public function detall(): void
    {
        $id = (int) ($_GET['id'] ?? 0);

        $lead = $id > 0 ? $this->leadDao->getById($id) : null;

        if ($lead === null) {
            $this->noTrobat();

            return;
        }

        $this->renderitzar('admin/leads/detall', [
            'titol' => $lead->getNom(),
            'lead' => $lead,
        ]);
    }
```

Pas a pas:

1. **Llegim l'`id`** de la URL i el convertim a número. Si no és un número (`?id=abc`), `(int)` el converteix en `0`.
2. **Només consultem la base de dades si l'`id` és vàlid** (`> 0`).
3. **Clàusula de guarda**: si no s'ha trobat el lead, mostrem el 404 i **eixim del mètode amb `return`**. Així la resta del codi pot assumir que `$lead` existix.
4. **Vista**: se li passa el lead i el títol de la pàgina.

::: tip Bona pràctica
Comprovar primer els casos d'error i eixir amb `return` (el que es diu *guard clause*) deixa el «camí feliç» sense `else` ni nivells d'indentació de més.
:::

#### 10.2.4. Un parcial reutilitzable: l'estat

La insígnia amb el color de l'estat apareix en el llistat i també apareixerà en el detall. En lloc de **copiar el mateix codi**, el posem en una **vista parcial** que s'inclou allà on es necessite.

Crea `www/vistes/parcials/estat.php`:

```php
<?php

declare(strict_types=1);

use App\Helpers\Helpers;
use App\Models\Lead;

$colors = [
    Lead::ESTAT_NOU => 'bg-blue-50 text-blue-700',
    Lead::ESTAT_CONTACTAT => 'bg-amber-50 text-amber-700',
    Lead::ESTAT_EN_PROCES => 'bg-violet-50 text-violet-700',
    Lead::ESTAT_TANCAT => 'bg-emerald-50 text-emerald-700',
];
?>
<span class="rounded-full px-4 py-2 text-base font-medium <?= $colors[$lead->getEstat()] ?>">
    <?= Helpers::escapar($lead->getEstatText()) ?>
</span>
```

El parcial espera trobar una variable **`$lead`** disponible. Com que s'inclou amb `require` des d'una altra vista, **comparteix les seues variables**.

::: info Nota
Una vista parcial és un tros d'HTML reutilitzable. Frameworks com Laravel (amb els components de Blade) ofereixen la mateixa idea de forma més còmoda.
:::

#### 10.2.5. La vista del detall

Crea `www/vistes/admin/leads/detall.php`:

```php
<?php

declare(strict_types=1);

use App\Helpers\Helpers;

require __DIR__ . '/../../layouts/capcalera.php';
?>
<a href="/admin/" class="text-lg text-slate-500 hover:text-slate-900">
    ← Tornar al llistat
</a>

<div class="mt-10 flex flex-col gap-6 sm:flex-row sm:items-start sm:justify-between">
    <div>
        <h1 class="text-5xl font-semibold tracking-tight">
            <?= Helpers::escapar($lead->getNom()) ?>
        </h1>
        <p class="mt-4 text-xl text-slate-500">Lead #<?= $lead->getId() ?></p>
    </div>
    <div>
        <?php require __DIR__ . '/../../parcials/estat.php'; ?>
    </div>
</div>

<dl class="mt-16 divide-y divide-slate-200">
    <div class="grid gap-2 py-8 sm:grid-cols-3">
        <dt class="text-lg text-slate-500">Correu</dt>
        <dd class="text-xl sm:col-span-2">
            <a href="mailto:<?= Helpers::escapar($lead->getEmail()) ?>" class="underline">
                <?= Helpers::escapar($lead->getEmail()) ?>
            </a>
        </dd>
    </div>

    <div class="grid gap-2 py-8 sm:grid-cols-3">
        <dt class="text-lg text-slate-500">Telèfon</dt>
        <dd class="text-xl sm:col-span-2">
            <?= Helpers::escapar($lead->getTelefon() ?? '—') ?>
        </dd>
    </div>

    <div class="grid gap-2 py-8 sm:grid-cols-3">
        <dt class="text-lg text-slate-500">Empresa</dt>
        <dd class="text-xl sm:col-span-2">
            <?= Helpers::escapar($lead->getEmpresa() ?? '—') ?>
        </dd>
    </div>

    <div class="grid gap-2 py-8 sm:grid-cols-3">
        <dt class="text-lg text-slate-500">Missatge</dt>
        <dd class="text-xl leading-relaxed sm:col-span-2">
            <?= nl2br(Helpers::escapar($lead->getMissatge())) ?>
        </dd>
    </div>

    <?php if ($lead->getFitxer() !== null) : ?>
        <div class="grid gap-2 py-8 sm:grid-cols-3">
            <dt class="text-lg text-slate-500">Imatge</dt>
            <dd class="sm:col-span-2">
                <img src="/uploads/<?= Helpers::escapar($lead->getFitxer()) ?>"
                     alt="Imatge adjunta de <?= Helpers::escapar($lead->getNom()) ?>"
                     class="max-h-96 rounded-xl">
            </dd>
        </div>
    <?php endif; ?>

    <div class="grid gap-2 py-8 sm:grid-cols-3">
        <dt class="text-lg text-slate-500">Creat</dt>
        <dd class="text-xl sm:col-span-2">
            <?= date('d/m/Y H:i', strtotime((string) $lead->getDataCreacio())) ?>
        </dd>
    </div>

    <div class="grid gap-2 py-8 sm:grid-cols-3">
        <dt class="text-lg text-slate-500">Última modificació</dt>
        <dd class="text-xl sm:col-span-2">
            <?= date('d/m/Y H:i', strtotime((string) $lead->getDataActualitzacio())) ?>
        </dd>
    </div>
</dl>

<?php require __DIR__ . '/../../layouts/peu.php'; ?>
```

Detalls de la vista:

- **`<dl>`, `<dt>` i `<dd>`** són les etiquetes HTML semàntiques per a una llista de «terme: descripció», que és justament el que mostrem.
- **`?? '—'`** mostra una ratlla quan el camp és `NULL` (telèfon o empresa sense omplir).
- **La imatge només es mostra si existeix** (`getFitxer() !== null`). Els leads sense fitxer simplement no tenen eixa fila.
- **`mailto:`** convertix el correu en un enllaç que obri el client de correu.

::: warning Atenció (`nl2br` i l'ordre)
El missatge pot tindre salts de línia, que HTML ignora. `nl2br()` els convertix en `<br>`. Però **l'ordre importa**:

```php
// ✅ Primer escapem, després convertim els salts de línia
<?= nl2br(Helpers::escapar($lead->getMissatge())) ?>

// ❌ Si ho féiem al revés, escaparíem els <br> o deixaríem passar HTML maliciós
<?= Helpers::escapar(nl2br($lead->getMissatge())) ?>
```
:::

#### 10.2.6. Enllaç des del llistat

Ara cal poder arribar al detall. Modifiquem `www/vistes/admin/leads/llistat.php` en dos llocs.

**1.** Elimina el bloc `$colors = [ ... ];` del principi del fitxer (ara viu en el parcial). Les importacions `use App\Helpers\Helpers;` i `use App\Models\Lead;` es queden, perquè el `select` d'estats encara les utilitza.

**2.** Substitueix tot el `<li> ... </li>` del `foreach` pel següent:

```php
            <li class="flex flex-col gap-4 py-8 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <a href="<?= Helpers::escapar(Helpers::construirUrl([
                        'accio' => 'detall',
                        'id' => $lead->getId(),
                    ])) ?>" class="text-2xl font-medium hover:underline">
                        <?= Helpers::escapar($lead->getNom()) ?>
                    </a>
                    <p class="mt-2 text-lg text-slate-500">
                        <?= Helpers::escapar($lead->getEmail()) ?>
                        <?php if ($lead->getEmpresa() !== null) : ?>
                            · <?= Helpers::escapar($lead->getEmpresa()) ?>
                        <?php endif; ?>
                    </p>
                </div>
                <div class="flex items-center gap-6">
                    <?php require __DIR__ . '/../../parcials/estat.php'; ?>
                    <span class="text-lg text-slate-400">
                        <?= date('d/m/Y', strtotime((string) $lead->getDataCreacio())) ?>
                    </span>
                </div>
            </li>
```

- El nom del lead és ara un **enllaç** a `?accio=detall&id=...`, construït amb el mateix `Helpers::construirUrl()` del bloc anterior.
- La insígnia d'estat es carrega del **parcial**: dins del `foreach`, la variable `$lead` ja existeix, així que el parcial la troba.

#### 10.2.7. Comprovació

Visita <http://localhost:8080/admin/> i fes clic sobre el nom d'un lead. Prova aquests casos:

| URL | Resultat esperat |
| --- | --- |
| `/admin/?accio=detall&id=1` | El detall de Marta Ferrer, amb la insígnia de l'estat |
| `/admin/?accio=detall&id=999` | La pàgina «No trobat» |
| `/admin/?accio=detall&id=abc` | La pàgina «No trobat» |
| `/admin/?accio=detall` | La pàgina «No trobat» |
| `/admin/?accio=hola` | La pàgina «No trobat» |
| `/admin/` | El llistat, amb els noms convertits en enllaços |

Comprova també el **codi HTTP**: obri les eines de desenvolupador del navegador (F12), pestanya *Network* o *Xarxa*, i recarrega `?accio=detall&id=999`. El document ha de tornar **404**.

Per a provar l'escapament i els salts de línia, executa aquesta consulta en phpMyAdmin i visita el detall del lead 1:

```sql
UPDATE leads
SET missatge = CONCAT('<script>alert(1)</script>', CHAR(10), 'Segona línia')
WHERE id = 1;
```

El detall ha de mostrar el text literal `<script>alert(1)</script>` (sense executar-lo) i la «Segona línia» **en una línia nova**.

::: info Nota
Tens la imatge en el detall quan el camp `fitxer` no és `NULL`. De moment cap lead en té, però ho podràs provar quan tinguem el formulari d'alta (10.3).
:::

📌 **A recordar:** el paràmetre `accio` i el `match` decideixen quina acció s'executa, amb una llista tancada; el controlador valida l'`id`, usa clàusules de guarda i respon amb 404 si el lead no existeix; les vistes parcials eviten repetir HTML; en mostrar un text amb salts de línia, primer s'escapa i després s'aplica `nl2br()`.

📖 Documentació oficial

[PHP: match](https://www.php.net/manual/es/control-structures.match.php) · [PHP: nl2br](https://www.php.net/manual/es/function.nl2br.php)

### 10.3. Afegir i editar un lead

Aquest bloc és el més gran de la unitat, així que el dividim en dues parts:

1. **Alta** (*Create*): el formulari públic on qualsevol persona envia una sol·licitud.
2. **Edició** (*Update*): la pàgina de l'administració per a modificar un lead.

Ací fem la primera. El formulari serà un **parcial reutilitzable**, de manera que en l'edició només haurem d'afegir el que canvia.

#### 10.3.1. El flux de l'alta

```text
GET  /public/                  → formulari buit
POST /public/?accio=guardar    → valida i guarda
        ├─ hi ha errors   → torna a mostrar el formulari amb els errors
        └─ tot correcte   → insereix i redirigeix a  /public/?accio=gracies
GET  /public/?accio=gracies    → pàgina de confirmació
```

::: info Nota (patró Post/Redirect/Get)
Després de guardar, **no mostrem la pàgina de gràcies directament**: redirigim a una URL nova. Si l'usuari recarrega la pàgina de gràcies, el navegador torna a fer un `GET` i **no es reenvia el formulari** ni es crea un lead duplicat. Es diu patró *Post/Redirect/Get*.
:::

#### 10.3.2. El controlador: mostrar el formulari i la confirmació

Afig aquestes importacions a la part superior de `LeadController` (`www/src/Controllers/LeadController.php`), junt a les que ja hi ha:

```php
use App\Helpers\Helpers;
use PDOException;
use RuntimeException;
```

I aquesta constant, junt a `PER_PAGINA`:

```php
    private const CARPETA_UPLOADS = __DIR__ . '/../../uploads';
```

Ara afig aquests dos mètodes, després de `detall()`:

```php
    public function formulari(): void
    {
        $this->renderitzar('public/leads/formulari', [
            'titol' => 'Contacta amb nosaltres',
            'lead' => new Lead(),
            'errors' => [],
        ]);
    }

    public function gracies(): void
    {
        $this->renderitzar('public/leads/gracies', ['titol' => 'Gràcies']);
    }
```

- `formulari()` passa un `Lead` **buit** i cap error. El formulari sempre pinta els valors d'un objecte `Lead`: quan és nou estan buits i, si hi ha errors, conserven el que l'usuari havia escrit.
- `gracies()` només mostra la confirmació.

#### 10.3.3. El controlador: validar i guardar

Afig aquest mètode a `LeadController`, després de `gracies()`:

```php
    public function guardar(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirigir('/public/');
        }

        $lead = new Lead(
            nom: trim($_POST['nom'] ?? ''),
            email: trim($_POST['email'] ?? ''),
            telefon: $this->nullSiBuit($_POST['telefon'] ?? ''),
            empresa: $this->nullSiBuit($_POST['empresa'] ?? ''),
            missatge: trim($_POST['missatge'] ?? '')
        );

        $errors = $this->validar($lead);
        $fitxer = null;

        if ($errors === []) {
            try {
                $fitxer = Helpers::pujarImatge(
                    $_FILES['imatge'] ?? [],
                    self::CARPETA_UPLOADS
                );
            } catch (RuntimeException $e) {
                $errors['imatge'] = $e->getMessage();
            }
        }

        if ($errors !== []) {
            $this->renderitzar('public/leads/formulari', [
                'titol' => 'Contacta amb nosaltres',
                'lead' => $lead,
                'errors' => $errors,
            ]);

            return;
        }

        $lead->setFitxer($fitxer);

        try {
            $this->leadDao->insert($lead);
        } catch (PDOException $e) {
            Helpers::eliminarImatge($fitxer, self::CARPETA_UPLOADS);

            throw $e;
        }

        $this->redirigir('/public/?accio=gracies');
    }
```

Pas a pas:

1. **Només s'accepta `POST`**. Si algú visita `?accio=guardar` directament, el redirigim al formulari.
2. **Construïm un `Lead`** amb les dades rebudes, utilitzant arguments amb nom. Fem `trim()` de tots els textos.
3. **Validem** (mètode `validar()`, el pròxim apartat). Si hi ha errors, tornem a mostrar el formulari amb ells.
4. **Només si les dades són correctes** pugem la imatge. Així no deixem fitxers orfes quan el formulari és incorrecte.
5. **Inserim** el lead amb el DAO. Si falla la base de dades, **eliminem la imatge ja pujada** (per a no deixar-la òrfena) i tornem a llançar l'excepció.
6. **Redirigim** a la pàgina de gràcies (patró Post/Redirect/Get).

::: warning Atenció
El **estat** del lead **no es llig del formulari**: sempre comença en `nou` (el valor per defecte del constructor). Mai ens fiem d'un camp que l'usuari puga manipular per a una decisió d'aquest tipus.
:::

::: info Nota
Quan el formulari torna amb errors, el navegador **no conserva el fitxer seleccionat**: l'usuari haurà de tornar a triar-lo. És una limitació de HTML, no un error nostre.
:::

#### 10.3.4. Validar les dades

Afig aquests dos mètodes **privats** al final de `LeadController`, abans de la clau final de la classe:

```php
    /**
     * @return array<string, string> Errors, indexats pel nom del camp
     */
    private function validar(Lead $lead): array
    {
        $errors = [];

        if ($lead->getNom() === '') {
            $errors['nom'] = 'El nom és obligatori.';
        } elseif (mb_strlen($lead->getNom()) > 100) {
            $errors['nom'] = 'El nom no pot tindre més de 100 caràcters.';
        }

        if ($lead->getEmail() === '') {
            $errors['email'] = 'El correu és obligatori.';
        } elseif (!filter_var($lead->getEmail(), FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'El correu no és vàlid.';
        } elseif (mb_strlen($lead->getEmail()) > 150) {
            $errors['email'] = 'El correu no pot tindre més de 150 caràcters.';
        }

        if (
            $lead->getTelefon() !== null
            && !preg_match('/^[0-9 +\-]{6,20}$/', $lead->getTelefon())
        ) {
            $errors['telefon'] = 'El telèfon ha de tindre entre 6 i 20 caràcters: xifres, espais, + o -.';
        }

        if ($lead->getEmpresa() !== null && mb_strlen($lead->getEmpresa()) > 100) {
            $errors['empresa'] = 'L\'empresa no pot tindre més de 100 caràcters.';
        }

        if ($lead->getMissatge() === '') {
            $errors['missatge'] = 'El missatge és obligatori.';
        } elseif (mb_strlen($lead->getMissatge()) < 10) {
            $errors['missatge'] = 'El missatge ha de tindre almenys 10 caràcters.';
        } elseif (mb_strlen($lead->getMissatge()) > 2000) {
            $errors['missatge'] = 'El missatge no pot tindre més de 2000 caràcters.';
        }

        return $errors;
    }

    private function nullSiBuit(string $valor): ?string
    {
        $valor = trim($valor);

        return $valor === '' ? null : $valor;
    }
```

Algunes idees:

- **`$errors` és un array associatiu**: la clau és el nom del camp i el valor és el missatge. Si al final està buit (`[]`), les dades són vàlides.
- **`elseif`**: només es registra **un error per camp** (el primer que falla).
- **`filter_var(..., FILTER_VALIDATE_EMAIL)`** comprova que el text tinga format de correu. Retorna `false` si no el té.
- **`preg_match()`** comprova una expressió regular: `^[0-9 +\-]{6,20}$` significa «de 6 a 20 caràcters, tots ells xifres, espais, `+` o `-`».
- **`mb_strlen()`** compta **caràcters**, no bytes. Amb `strlen()`, una paraula amb accents com «Pàgina» comptaria 7 en lloc de 6.
- **`nullSiBuit()`** convertix un camp opcional buit en `null`, perquè en la base de dades quede com `NULL` i no com un text buit.
- Els mètodes són **privats**: només els utilitza el propi controlador.

::: tip Bona pràctica
La validació del navegador (atributs `required`, `type="email"`...) millora l'experiència, però **l'usuari pot saltar-se-la**. La validació que compta és sempre la del **servidor**. Per això el nostre formulari porta l'atribut `novalidate` i deixa que decidisca PHP.
:::

::: info Nota
Una aplicació real també hauria de protegir el formulari contra enviaments automàtics (captcha) i contra atacs CSRF. Ho deixem fora d'aquesta unitat; Laravel ho integra de sèrie.
:::

#### 10.3.5. El disseny de la zona pública

La zona pública té una capçalera diferent de la d'administració: sense enllaços interns i amb un contingut més estret. Creem dos fitxers de disseny nous.

Crea `www/vistes/layouts/capcalera-public.php`:

```php
<?php

use App\Helpers\Helpers;
?>
<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= Helpers::escapar($titol ?? 'Leads') ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white text-slate-900 antialiased">
    <header>
        <div class="mx-auto max-w-2xl px-6 py-8">
            <a href="/public/" class="text-2xl font-semibold tracking-tight">Leads</a>
        </div>
    </header>
    <main class="mx-auto max-w-2xl px-6 py-12">
```

I `www/vistes/layouts/peu-public.php`:

```php
    </main>
    <footer class="mx-auto max-w-2xl px-6 py-12 text-base text-slate-400">
        <a href="/admin/" class="hover:text-slate-900">Zona privada</a>
    </footer>
</body>
</html>
```

#### 10.3.6. El formulari reutilitzable

El formulari és un **parcial** que espera tindre disponibles aquestes variables:

| Variable | Contingut |
| --- | --- |
| `$lead` | L'objecte `Lead` amb els valors a pintar (buit o amb el que escrigué l'usuari) |
| `$errors` | Array amb els errors, indexats pel nom del camp |
| `$action` | URL on s'envia el formulari |
| `$textBoto` | Text del botó d'enviament |

Crea `www/vistes/parcials/formulari-lead.php`:

```php
<?php

declare(strict_types=1);

use App\Helpers\Helpers;
?>
<form method="post" action="<?= Helpers::escapar($action) ?>"
      enctype="multipart/form-data" novalidate class="space-y-10">

    <?php if ($errors !== []) : ?>
        <p class="rounded-lg bg-red-50 px-5 py-4 text-lg text-red-700">
            Revisa els camps marcats abans de continuar.
        </p>
    <?php endif; ?>

    <div>
        <label for="nom" class="block text-lg font-medium">Nom *</label>
        <input type="text" id="nom" name="nom"
               value="<?= Helpers::escapar($lead->getNom()) ?>"
               class="mt-3 w-full rounded-lg border border-slate-300 px-5 py-4 text-xl">
        <?php if (isset($errors['nom'])) : ?>
            <p class="mt-2 text-base text-red-600"><?= Helpers::escapar($errors['nom']) ?></p>
        <?php endif; ?>
    </div>

    <div>
        <label for="email" class="block text-lg font-medium">Correu electrònic *</label>
        <input type="email" id="email" name="email"
               value="<?= Helpers::escapar($lead->getEmail()) ?>"
               class="mt-3 w-full rounded-lg border border-slate-300 px-5 py-4 text-xl">
        <?php if (isset($errors['email'])) : ?>
            <p class="mt-2 text-base text-red-600"><?= Helpers::escapar($errors['email']) ?></p>
        <?php endif; ?>
    </div>

    <div>
        <label for="telefon" class="block text-lg font-medium">Telèfon</label>
        <input type="tel" id="telefon" name="telefon"
               value="<?= Helpers::escapar($lead->getTelefon()) ?>"
               class="mt-3 w-full rounded-lg border border-slate-300 px-5 py-4 text-xl">
        <?php if (isset($errors['telefon'])) : ?>
            <p class="mt-2 text-base text-red-600"><?= Helpers::escapar($errors['telefon']) ?></p>
        <?php endif; ?>
    </div>

    <div>
        <label for="empresa" class="block text-lg font-medium">Empresa</label>
        <input type="text" id="empresa" name="empresa"
               value="<?= Helpers::escapar($lead->getEmpresa()) ?>"
               class="mt-3 w-full rounded-lg border border-slate-300 px-5 py-4 text-xl">
        <?php if (isset($errors['empresa'])) : ?>
            <p class="mt-2 text-base text-red-600"><?= Helpers::escapar($errors['empresa']) ?></p>
        <?php endif; ?>
    </div>

    <div>
        <label for="missatge" class="block text-lg font-medium">Missatge *</label>
        <textarea id="missatge" name="missatge" rows="6"
                  class="mt-3 w-full rounded-lg border border-slate-300 px-5 py-4 text-xl leading-relaxed"><?= Helpers::escapar($lead->getMissatge()) ?></textarea>
        <?php if (isset($errors['missatge'])) : ?>
            <p class="mt-2 text-base text-red-600"><?= Helpers::escapar($errors['missatge']) ?></p>
        <?php endif; ?>
    </div>

    <div>
        <label for="imatge" class="block text-lg font-medium">Imatge (opcional)</label>
        <input type="file" id="imatge" name="imatge" accept="image/*"
               class="mt-3 block w-full text-lg text-slate-600">
        <p class="mt-2 text-base text-slate-400">JPG, PNG, WEBP o GIF. Màxim 2 MB.</p>
        <?php if (isset($errors['imatge'])) : ?>
            <p class="mt-2 text-base text-red-600"><?= Helpers::escapar($errors['imatge']) ?></p>
        <?php endif; ?>
    </div>

    <button type="submit"
            class="rounded-lg bg-slate-900 px-8 py-4 text-xl font-medium text-white hover:bg-slate-700">
        <?= Helpers::escapar($textBoto) ?>
    </button>
</form>
```

Detalls importants:

- **`enctype="multipart/form-data"`** és imprescindible per a poder pujar fitxers.
- **`novalidate`** desactiva la validació del navegador perquè decidisca el servidor (vegeu l'apartat 10.3.4).
- **`value="<?= ... ?>"`** repinta el que l'usuari havia escrit, escapat amb `Helpers::escapar()`. Si es posara sense escapar, una cometa dins del text trencaria l'atribut HTML.
- **El `<textarea>`** s'escriu **tot en una línia** (`>` i `<?= ... ?>` junts): qualsevol espai o salt de línia entre l'etiqueta i el contingut passaria a formar part del text.
- **`isset($errors['nom'])`** mostra el missatge d'error només per als camps que han fallat.
- Els camps obligatoris porten un `*` a l'etiqueta.

#### 10.3.7. Les vistes públiques

Crea `www/vistes/public/leads/formulari.php`:

```php
<?php

declare(strict_types=1);

$action = '/public/?accio=guardar';
$textBoto = 'Enviar sol·licitud';

require __DIR__ . '/../../layouts/capcalera-public.php';
?>
<h1 class="text-5xl font-semibold tracking-tight">Parla amb nosaltres</h1>
<p class="mt-6 text-xl leading-relaxed text-slate-600">
    Explica'ns què necessites i et respondrem en menys de 24 hores.
</p>

<div class="mt-16">
    <?php require __DIR__ . '/../../parcials/formulari-lead.php'; ?>
</div>

<?php require __DIR__ . '/../../layouts/peu-public.php'; ?>
```

I `www/vistes/public/leads/gracies.php`:

```php
<?php

declare(strict_types=1);

require __DIR__ . '/../../layouts/capcalera-public.php';
?>
<h1 class="text-5xl font-semibold tracking-tight">Gràcies!</h1>
<p class="mt-8 text-xl leading-relaxed text-slate-600">
    Hem rebut la teua sol·licitud. Ens posarem en contacte amb tu en menys de 24 hores.
</p>
<a href="/public/"
   class="mt-12 inline-block rounded-lg bg-slate-900 px-8 py-4 text-xl font-medium text-white">
    Enviar una altra sol·licitud
</a>

<?php require __DIR__ . '/../../layouts/peu-public.php'; ?>
```

Fixa't que la vista del formulari **defineix** `$action` i `$textBoto` abans d'incloure el parcial: és la vista qui decidix on s'envia el formulari. En l'edició, la mateixa peça enviarà les dades a una altra acció.

#### 10.3.8. El punt d'entrada públic

Substitueix el contingut de `www/public/index.php` (la prova de connexió del punt 7 ja no fa falta) per:

```php
<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/bootstrap.php';

use App\Controllers\LeadController;
use App\DAO\LeadDAO;

$controlador = new LeadController(new LeadDAO($pdo));

$accio = $_GET['accio'] ?? 'formulari';

match ($accio) {
    'formulari' => $controlador->formulari(),
    'guardar' => $controlador->guardar(),
    'gracies' => $controlador->gracies(),
    default => $controlador->noTrobat(),
};
```

És el mateix esquema que `admin/index.php`, però amb **les accions de la zona pública**. Les accions `llistar` i `detall` **no estan en la llista**, així que des de `/public/` no es poden executar.

::: info Nota
La vista `errors/404.php` utilitza la capçalera d'administració. Si algú arriba a un error 404 des de la zona pública, veurà eixa capçalera. És un detall menor que es pot millorar més endavant.
:::

#### 10.3.9. Comprovació

Visita <http://localhost:8080/public/> i prova aquests casos:

| Prova | Resultat esperat |
| --- | --- |
| Obrir `/public/` | El formulari buit |
| Enviar-lo sense escriure res | Errors en **nom**, **correu** i **missatge**; la resta de camps sense error |
| Correu `abc` | `El correu no és vàlid.` |
| Telèfon `hola` | Error de telèfon |
| Missatge `Hola` | `El missatge ha de tindre almenys 10 caràcters.` |
| Amb errors, mirar els camps | **Conserven** el que has escrit |
| Dades correctes, sense imatge | Redirecció a «Gràcies!» |
| Recarregar la pàgina de gràcies | No es crea cap lead duplicat |
| Dades correctes, amb una imatge PNG | Redirecció a «Gràcies!» |
| Dades correctes, amb un PDF | `Només s'admeten imatges JPG, PNG, WEBP o GIF.` |
| Nom `<b>hola</b>` | Es guarda i es mostra com a text literal |
| `/admin/?accio=guardar` | Pàgina «No trobat» (aquesta acció no existeix en l'administració) |

Després d'enviar un lead, comprova que:

- Apareix el primer en el llistat d'**`/admin/`**, amb l'estat **Nou**.
- Si havies pujat una imatge, es veu en el **detall** i hi ha un fitxer nou dins de `www/uploads/` amb un nom que comença per `img_`.
- En phpMyAdmin, les columnes `telefon` i `empresa` valen `NULL` (no text buit) quan les has deixades en blanc.

::: danger Error crític
Aquest formulari és públic i guarda dades i fitxers que envia qualsevol persona. Les mesures que hem aplicat (validació, comprovació del tipus real de la imatge, nom únic, escapament en mostrar) són el **mínim** per a un projecte didàctic. En producció caldria afegir limitació d'enviaments i protecció anti-spam.
:::

📌 **A recordar:** les dades del formulari es validen **sempre en el servidor**; els errors es guarden en un array indexat pel nom del camp i es mostren al costat de cada camp; el formulari és un parcial reutilitzable; el patró *Post/Redirect/Get* evita duplicats en recarregar; la imatge només es puja quan les dades són correctes i s'esborra si la base de dades falla.

📖 Documentació oficial

[PHP: filter_var](https://www.php.net/manual/es/function.filter-var.php) · [PHP: mb_strlen](https://www.php.net/manual/es/function.mb-strlen.php)

#### 10.3.10. El flux de l'edició

L'edició segueix el mateix patró que l'alta (*Post/Redirect/Get*), però ara partim d'un lead que **ja existix**:

```text
GET  /admin/?accio=editar&id=3        → formulari amb les dades del lead
POST /admin/?accio=actualitzar&id=3   → valida i guarda
        ├─ hi ha errors   → torna a mostrar el formulari amb els errors
        └─ tot correcte   → actualitza i redirigeix al detall (amb un avís)
```

Reutilitzarem tot el que ja tenim:

| Peça | Origen | Què canvia |
| --- | --- | --- |
| `validar()` | 10.3.4 | Res: les mateixes regles |
| Formulari (parcial) | 10.3.6 | Afegim el camp **estat** i la **imatge actual** |
| `pujarImatge()` / `eliminarImatge()` | Punt 9 | Res |
| `getById()` / `update()` | Punt 8 | Res: el DAO ja ho feia |

#### 10.3.11. Carregar el lead: un mètode compartit

Detall, edició i (en el pròxim bloc) eliminació necessiten el mateix: **llegir l'`id` de la URL i buscar el lead**. En lloc de repetir-ho, ho posem en un mètode privat.

Afig aquest mètode al final de `LeadController`, abans de la clau final de la classe:

```php
    private function carregarLead(): ?Lead
    {
        $id = (int) ($_GET['id'] ?? 0);

        return $id > 0 ? $this->leadDao->getById($id) : null;
    }
```

I simplifica `detall()` perquè l'utilitze. Substitueix tot el mètode per:

```php
    public function detall(): void
    {
        $lead = $this->carregarLead();

        if ($lead === null) {
            $this->noTrobat();

            return;
        }

        $this->renderitzar('admin/leads/detall', [
            'titol' => $lead->getNom(),
            'lead' => $lead,
            'guardat' => isset($_GET['guardat']),
        ]);
    }
```

El paràmetre `guardat` el farem servir al final per a mostrar un avís quan s'han guardat els canvis.

::: tip Bona pràctica
Quan es repetix el mateix codi en dos mètodes, és el moment d'**extraure'l** a un mètode propi. Si algun dia cal canviar com es busca un lead, es canvia en un sol lloc.
:::

#### 10.3.12. Mostrar el formulari d'edició

Afig aquest mètode a `LeadController`, després de `detall()`:

```php
    public function editar(): void
    {
        $lead = $this->carregarLead();

        if ($lead === null) {
            $this->noTrobat();

            return;
        }

        $this->renderitzar('admin/leads/editar', [
            'titol' => 'Editar lead',
            'lead' => $lead,
            'errors' => [],
        ]);
    }
```

És pràcticament igual que `formulari()` del bloc anterior, però el `Lead` ve de la base de dades en lloc d'estar buit.

#### 10.3.13. Guardar els canvis

Afig aquest mètode a `LeadController`, després d'`editar()`:

```php
    public function actualitzar(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirigir('/admin/');
        }

        $lead = $this->carregarLead();

        if ($lead === null) {
            $this->noTrobat();

            return;
        }

        $fitxerAntic = $lead->getFitxer();

        $lead->setNom(trim($_POST['nom'] ?? ''));
        $lead->setEmail(trim($_POST['email'] ?? ''));
        $lead->setTelefon($this->nullSiBuit($_POST['telefon'] ?? ''));
        $lead->setEmpresa($this->nullSiBuit($_POST['empresa'] ?? ''));
        $lead->setMissatge(trim($_POST['missatge'] ?? ''));

        $errors = $this->validar($lead);

        $estat = $_POST['estat'] ?? '';

        if (array_key_exists($estat, Lead::ESTATS)) {
            $lead->setEstat($estat);
        } else {
            $errors['estat'] = 'L\'estat no és vàlid.';
        }

        $fitxerNou = null;

        if ($errors === []) {
            try {
                $fitxerNou = Helpers::pujarImatge(
                    $_FILES['imatge'] ?? [],
                    self::CARPETA_UPLOADS
                );
            } catch (RuntimeException $e) {
                $errors['imatge'] = $e->getMessage();
            }
        }

        if ($errors !== []) {
            $this->renderitzar('admin/leads/editar', [
                'titol' => 'Editar lead',
                'lead' => $lead,
                'errors' => $errors,
            ]);

            return;
        }

        if ($fitxerNou !== null) {
            $lead->setFitxer($fitxerNou);
        } elseif (isset($_POST['eliminar_imatge'])) {
            $lead->setFitxer(null);
        }

        try {
            $this->leadDao->update($lead);
        } catch (PDOException $e) {
            Helpers::eliminarImatge($fitxerNou, self::CARPETA_UPLOADS);

            throw $e;
        }

        if ($fitxerAntic !== $lead->getFitxer()) {
            Helpers::eliminarImatge($fitxerAntic, self::CARPETA_UPLOADS);
        }

        $this->redirigir('/admin/' . Helpers::construirUrl([
            'accio' => 'detall',
            'id' => $lead->getId(),
            'guardat' => 1,
        ]));
    }
```

La diferència principal amb `guardar()`: **no creem un lead nou, modifiquem el que ja existix** amb els *setters*. Així es conserven l'`id`, la imatge i les dates sense haver de tornar a posar-los.

Pas a pas:

1. **Només `POST`**. Si algú entra per `GET`, el portem al llistat.
2. **Carreguem el lead** de la base de dades. Si no existix, 404.
3. **Guardem el nom de la imatge antiga** (`$fitxerAntic`) per a saber més avant si cal esborrar-la.
4. **Apliquem les dades rebudes** al lead amb els *setters* i **validem** amb el mateix `validar()` de l'alta.
5. **Comprovem l'estat**: com que ve d'un `select`, l'usuari podria haver-lo manipulat. Si no és un dels valors de `Lead::ESTATS`, afegim un error.
6. **Imatge**, només si les dades són correctes:
   - Si s'ha pujat un fitxer nou, **substituïx** l'anterior.
   - Si no, però s'ha marcat «Eliminar la imatge actual», **se n'elimina** la referència.
7. **Actualitzem** amb el DAO. Si la base de dades falla, esborrem la imatge nova per a no deixar-la òrfena.
8. **Esborrem el fitxer antic** del disc només si ja no s'utilitza (si ha canviat o s'ha eliminat), i **només després** d'haver guardat amb èxit.
9. **Redirigim** al detall, amb `guardat=1` per a mostrar l'avís.

::: warning Atenció (l'ordre importa)
L'**antiga imatge no s'esborra fins que la base de dades s'ha actualitzat**. Si ho féiem abans i `update()` fallava, el lead quedaria apuntant a un fitxer que ja no existix.
:::

#### 10.3.14. Adaptar el formulari

El parcial del formulari necessita dos canvis: un **camp d'estat** (només en l'administració) i mostrar la **imatge actual** amb l'opció d'eliminar-la.

Primer, afig aquesta importació junt a la que ja hi ha al principi de `www/vistes/parcials/formulari-lead.php`:

```php
use App\Models\Lead;
```

**1.** Afig aquest bloc **abans** del `<div>` de la imatge:

```php
    <?php if ($ambEstat ?? false) : ?>
        <div>
            <label for="estat" class="block text-lg font-medium">Estat</label>
            <select id="estat" name="estat"
                    class="mt-3 w-full rounded-lg border border-slate-300 px-5 py-4 text-xl">
                <?php foreach (Lead::ESTATS as $valor => $text) : ?>
                    <option value="<?= Helpers::escapar($valor) ?>"
                        <?= $lead->getEstat() === $valor ? 'selected' : '' ?>>
                        <?= Helpers::escapar($text) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <?php if (isset($errors['estat'])) : ?>
                <p class="mt-2 text-base text-red-600"><?= Helpers::escapar($errors['estat']) ?></p>
            <?php endif; ?>
        </div>
    <?php endif; ?>
```

**2.** Substitueix el `<div>` de la imatge per aquest:

```php
    <div>
        <label for="imatge" class="block text-lg font-medium">
            <?= $lead->getFitxer() !== null ? 'Substituir la imatge' : 'Imatge (opcional)' ?>
        </label>

        <?php if ($lead->getFitxer() !== null) : ?>
            <img src="/uploads/<?= Helpers::escapar($lead->getFitxer()) ?>"
                 alt="Imatge actual" class="mt-4 max-h-48 rounded-xl">
            <label class="mt-4 flex items-center gap-3 text-lg text-slate-600">
                <input type="checkbox" name="eliminar_imatge" value="1" class="h-5 w-5">
                Eliminar la imatge actual
            </label>
        <?php endif; ?>

        <input type="file" id="imatge" name="imatge" accept="image/*"
               class="mt-3 block w-full text-lg text-slate-600">
        <p class="mt-2 text-base text-slate-400">JPG, PNG, WEBP o GIF. Màxim 2 MB.</p>
        <?php if (isset($errors['imatge'])) : ?>
            <p class="mt-2 text-base text-red-600"><?= Helpers::escapar($errors['imatge']) ?></p>
        <?php endif; ?>
    </div>
```

Dos idees importants:

- **`$ambEstat ?? false`**: l'operador `??` dona un valor per defecte si la variable no existix. El formulari públic **no defineix `$ambEstat`**, així que per a ell el camp d'estat no es mostra i **continua funcionant sense tocar-lo**.
- **La imatge actual** només apareix si el lead en té. En l'alta sempre és `null`, així que allí el formulari es veu igual que abans.

::: info Nota
Un parcial amb comportament opcional (`$ambEstat`) és una forma senzilla de reutilitzar el mateix formulari en dos llocs. Si les diferències creixeren molt, seria millor tindre dos formularis separats.
:::

#### 10.3.15. La vista d'edició

Crea `www/vistes/admin/leads/editar.php`:

```php
<?php

declare(strict_types=1);

use App\Helpers\Helpers;

$action = Helpers::construirUrl([
    'accio' => 'actualitzar',
    'id' => $lead->getId(),
]);
$textBoto = 'Guardar els canvis';
$ambEstat = true;

$urlDetall = Helpers::construirUrl([
    'accio' => 'detall',
    'id' => $lead->getId(),
]);

require __DIR__ . '/../../layouts/capcalera.php';
?>
<a href="<?= Helpers::escapar($urlDetall) ?>"
   class="text-lg text-slate-500 hover:text-slate-900">
    ← Tornar al detall
</a>

<h1 class="mt-10 text-5xl font-semibold tracking-tight">Editar lead</h1>
<p class="mt-4 text-xl text-slate-500">Lead #<?= $lead->getId() ?></p>

<div class="mt-16 max-w-2xl">
    <?php require __DIR__ . '/../../parcials/formulari-lead.php'; ?>
</div>

<?php require __DIR__ . '/../../layouts/peu.php'; ?>
```

- La vista **defineix** `$action`, `$textBoto` i `$ambEstat` abans de carregar el parcial: és ella qui decidix com es comporta el formulari.
- `$action` és una URL **relativa** (`?accio=actualitzar&id=3`) construïda amb `Helpers::construirUrl()`, així que s'envia a la pàgina actual (`/admin/`).

#### 10.3.16. Rutes, botó d'edició i avís

**1. Les rutes.** Afig les dos accions noves al `match` de `www/admin/index.php`:

```php
match ($accio) {
    'llistar' => $controlador->llistar(),
    'detall' => $controlador->detall(),
    'editar' => $controlador->editar(),
    'actualitzar' => $controlador->actualitzar(),
    default => $controlador->noTrobat(),
};
```

**2. El botó «Editar» en el detall.** En `www/vistes/admin/leads/detall.php`, substitueix el `<div>` que conté el parcial de l'estat (el de la dreta de la capçalera) per:

```php
    <div class="flex items-center gap-4">
        <?php require __DIR__ . '/../../parcials/estat.php'; ?>
        <a href="<?= Helpers::escapar(Helpers::construirUrl([
            'accio' => 'editar',
            'id' => $lead->getId(),
        ])) ?>" class="rounded-lg bg-slate-900 px-6 py-3 text-lg font-medium text-white hover:bg-slate-700">
            Editar
        </a>
    </div>
```

**3. L'avís de canvis guardats.** En el mateix fitxer, afig aquest bloc **just després** de l'enllaç «Tornar al llistat»:

```php
<?php if ($guardat) : ?>
    <p class="mt-8 rounded-lg bg-emerald-50 px-5 py-4 text-lg text-emerald-700">
        Els canvis s'han guardat correctament.
    </p>
<?php endif; ?>
```

::: tip Bona pràctica
Passar l'avís pel paràmetre `guardat=1` de la URL és una solució senzilla, però si recarregues la pàgina l'avís continua apareixent. La solució professional són els **missatges flash** guardats en la sessió, que es mostren una sola vegada. Ho veurem amb les sessions del login (punt 11).
:::

#### 10.3.17. Comprovació

Visita el detall d'un lead (<http://localhost:8080/admin/?accio=detall&id=1>) i prova aquests casos:

| Prova | Resultat esperat |
| --- | --- |
| Fer clic a **Editar** | El formulari amb **totes** les dades i l'estat actual seleccionat |
| Canviar el nom i guardar | Es torna al detall amb l'avís verd i el nom nou |
| Esborrar el nom i guardar | Error en el nom; **la resta de canvis es conserven** en el formulari |
| Canviar l'estat a **Tancat** | El llistat mostra la insígnia «Tancat» |
| Pujar una imatge en un lead que no en té | La imatge es mostra en el detall i hi ha un fitxer nou en `www/uploads/` |
| Pujar una **altra** imatge | Es mostra la nova i el **fitxer antic ha desaparegut** de `uploads/` |
| Marcar «Eliminar la imatge actual» i guardar | El detall ja no té fila d'imatge i el fitxer s'ha esborrat |
| Pujar un PDF | `Només s'admeten imatges JPG, PNG, WEBP o GIF.`; la imatge actual **no es toca** |
| Guardar sense canviar res | Es torna al detall amb l'avís |
| Amb les eines de desenvolupador, canviar el `value` d'una opció d'estat a `hacker` i guardar | `L'estat no és vàlid.` |
| `/admin/?accio=editar&id=999` | Pàgina «No trobat» |
| Visitar `/admin/?accio=actualitzar&id=1` (sense enviar el formulari) | Es redirigix al llistat |

Comprova també en el formulari **públic** (`/public/`) que continua igual que abans: sense camp d'estat i sense «Eliminar la imatge».

::: info Nota
Si guardes sense canviar cap dada, la columna «Última modificació» **no canvia**: MySQL només actualitza `ON UPDATE CURRENT_TIMESTAMP` quan alguna columna canvia de valor.
:::

📌 **A recordar:** el mateix formulari serveix per a crear i editar si és un parcial que rep les dades per variables; en l'edició es modifica l'objecte que ve de la base de dades amb els *setters*; l'estat s'ha de validar encara que vinga d'un `select`; la imatge antiga només s'esborra després d'haver guardat el canvi amb èxit; els mètodes que es repetixen (`carregarLead()`) s'extrauen a un mètode propi.

📖 Documentació oficial

[PHP: Operador de fusió de null (`??`)](https://www.php.net/manual/es/language.operators.comparison.php#language.operators.comparison.coalesce)

### 10.4. Eliminar un lead i canviar-ne l'estat

Ens falten les dos operacions que modifiquen un lead existent des del detall:

- **Canviar l'estat** (`nou` → `contactat` → ...) sense obrir el formulari complet d'edició.
- **Eliminar** el lead i la seua imatge.

Per a això no necessitem tocar el model ni el DAO: `LeadDAO` ja té `delete()` i `canviarEstat()` des del punt 8.

#### 10.4.1. Les accions que modifiquen dades es fan amb `POST`

Fins ara hem fet enllaços (`<a href="...">`) que carreguen pàgines. Això és un `GET`, i un `GET` **mai ha de modificar dades**:

| | `GET` | `POST` |
| --- | --- | --- |
| Per a què serveix | Consultar | Modificar, crear, eliminar |
| Es pot escriure en la barra de direccions | Sí | No (requerix un formulari) |
| Els navegadors i cercadors el poden precarregar | Sí | No |

Si eliminàrem amb un enllaç com `?accio=eliminar&id=3`, un cercador, un *prefetch* del navegador o un enllaç compartit per error podrien **esborrar leads sense voler**. Per això ambdues accions es faran amb un **formulari `POST`**.

::: danger Error crític
**Mai elimines (ni canvies) dades amb un enllaç `GET`.** El controlador comprovarà que la petició és `POST` i, si no ho és, no farà res.
:::

::: info Nota
Encara queda un risc: un lloc web maliciós podria enviar eixe `POST` en nom de l'administrador connectat (atac **CSRF**). La defensa són els *tokens CSRF*, que Laravel genera automàticament. Ací els deixem fora per a no complicar la unitat.
:::

#### 10.4.2. El controlador: eliminar

Afig aquest mètode a `LeadController` (`www/src/Controllers/LeadController.php`), després d'`actualitzar()`:

```php
    public function eliminar(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirigir('/admin/');
        }

        $lead = $this->carregarLead();

        if ($lead === null) {
            $this->noTrobat();

            return;
        }

        $this->leadDao->delete($lead->getId());
        Helpers::eliminarImatge($lead->getFitxer(), self::CARPETA_UPLOADS);

        $this->redirigir('/admin/' . Helpers::construirUrl(['eliminat' => 1]));
    }
```

Pas a pas:

1. **Només `POST`**: si algú escriu l'adreça directament, el portem al llistat.
2. **Carreguem el lead** amb el mètode compartit. Si no existix, 404.
3. **Eliminem el registre** de la base de dades.
4. **Eliminem la imatge** del disc, si en tenia.
5. **Redirigim** al llistat amb `eliminat=1`, per a mostrar un avís.

::: warning Atenció (l'ordre importa)
Primer s'esborra de la **base de dades** i **després** el fitxer. Si el `DELETE` falla, no hem perdut res. Si falla l'esborrat del fitxer, només queda una imatge sense usar, que no fa cap mal.
:::

#### 10.4.3. El controlador: canviar l'estat

Afig aquest mètode després d'`eliminar()`:

```php
    public function canviarEstat(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirigir('/admin/');
        }

        $lead = $this->carregarLead();

        if ($lead === null) {
            $this->noTrobat();

            return;
        }

        $estat = $_POST['estat'] ?? '';
        $parametres = ['accio' => 'detall', 'id' => $lead->getId()];

        if (array_key_exists($estat, Lead::ESTATS)) {
            $this->leadDao->canviarEstat($lead->getId(), $estat);
            $parametres['guardat'] = 1;
        }

        $this->redirigir('/admin/' . Helpers::construirUrl($parametres));
    }
```

- Si l'estat rebut és un dels vàlids, es crida a `LeadDAO::canviarEstat()`, que **només actualitza la columna `estat`** (no reescriu tot el lead).
- Si l'estat no és vàlid (el formulari ha sigut manipulat), **no es fa res** i es torna al detall **sense** l'avís de «guardat».
- En els dos casos es redirigix al detall (*Post/Redirect/Get*).

::: tip Bona pràctica
Hem creat **dos camins** per a canviar l'estat: el formulari complet d'edició (10.3) i este de canvi ràpid. És habitual: les accions freqüents tenen el seu propi mètode, ben xicotet i amb un sol propòsit.
:::

#### 10.4.4. El detall: formulari d'estat i zona d'eliminació

Modifiquem `www/vistes/admin/leads/detall.php`.

Primer, afig la importació del model junt a la que ja hi ha:

```php
use App\Models\Lead;
```

**1. Formulari de canvi d'estat.** Afig aquest bloc **just abans** del `<dl>` (després del `<div>` de la capçalera):

```php
<form method="post"
      action="<?= Helpers::escapar(Helpers::construirUrl([
          'accio' => 'canviar-estat',
          'id' => $lead->getId(),
      ])) ?>"
      class="mt-12 flex flex-col gap-4 sm:flex-row sm:items-center">
    <label for="estat" class="text-lg text-slate-500">Canviar l'estat</label>
    <select id="estat" name="estat"
            class="rounded-lg border border-slate-300 px-5 py-3 text-xl">
        <?php foreach (Lead::ESTATS as $valor => $text) : ?>
            <option value="<?= Helpers::escapar($valor) ?>"
                <?= $lead->getEstat() === $valor ? 'selected' : '' ?>>
                <?= Helpers::escapar($text) ?>
            </option>
        <?php endforeach; ?>
    </select>
    <button type="submit"
            class="rounded-lg border border-slate-300 px-6 py-3 text-lg font-medium hover:bg-slate-50">
        Actualitzar
    </button>
</form>
```

**2. Zona d'eliminació.** Afig aquest bloc **just abans** de `<?php require __DIR__ . '/../../layouts/peu.php'; ?>`, al final del fitxer:

```php
<div class="mt-16 border-t border-slate-200 pt-10">
    <h2 class="text-2xl font-semibold">Eliminar el lead</h2>
    <p class="mt-4 text-lg leading-relaxed text-slate-500">
        Aquesta acció no es pot desfer. S'eliminaran les dades i la imatge adjunta.
    </p>
    <form method="post"
          action="<?= Helpers::escapar(Helpers::construirUrl([
              'accio' => 'eliminar',
              'id' => $lead->getId(),
          ])) ?>"
          onsubmit="return confirm('Segur que vols eliminar aquest lead? Aquesta acció no es pot desfer.');"
          class="mt-8">
        <button type="submit"
                class="rounded-lg bg-red-600 px-8 py-4 text-lg font-medium text-white hover:bg-red-700">
            Eliminar lead
        </button>
    </form>
</div>
```

Detalls:

- **`method="post"`** en tots dos formularis. Els formularis envien només el que necessiten: l'`id` va en la URL (`action`) i l'estat en el cos de la petició.
- **`onsubmit="return confirm(...)"`** mostra un quadre de confirmació del navegador. Si l'usuari cancel·la, el formulari **no s'envia**.
- El botó d'eliminar és **roig** i està **separat** de la resta de la pàgina, per a evitar clics per error.

::: warning Atenció
El `confirm()` és una ajuda per a l'usuari, **no una mesura de seguretat**: el JavaScript es pot desactivar. La protecció real és que el servidor només elimina amb `POST` i, en el futur, amb un token CSRF.
:::

#### 10.4.5. L'avís en el llistat

Quan s'elimina un lead tornem al llistat. Hem d'informar-ne.

**1.** En el mètode `llistar()` de `LeadController`, afig una clau al final de l'array que es passa a la vista:

```php
            'totalPagines' => $totalPagines,
            'eliminat' => isset($_GET['eliminat']),
```

**2.** En `www/vistes/admin/leads/llistat.php`, afig aquest bloc **just després** del paràgraf `<p class="mt-4 text-xl text-slate-500"><?= $total ?> resultats</p>`:

```php
<?php if ($eliminat) : ?>
    <p class="mt-8 rounded-lg bg-emerald-50 px-5 py-4 text-lg text-emerald-700">
        El lead s'ha eliminat correctament.
    </p>
<?php endif; ?>
```

#### 10.4.6. Les rutes

Afig les dos accions noves al `match` de `www/admin/index.php`. Queda així:

```php
match ($accio) {
    'llistar' => $controlador->llistar(),
    'detall' => $controlador->detall(),
    'editar' => $controlador->editar(),
    'actualitzar' => $controlador->actualitzar(),
    'canviar-estat' => $controlador->canviarEstat(),
    'eliminar' => $controlador->eliminar(),
    default => $controlador->noTrobat(),
};
```

#### 10.4.7. Comprovació

Abans de provar l'eliminació, **crea un lead de prova** amb el formulari públic (`/public/`), amb una imatge. Així no esborres les dades d'exemple que fem servir en les altres proves. Si les perds, pots recrear-les amb l'script `database/leads.sql` del punt 2.

| Prova | Resultat esperat |
| --- | --- |
| En el detall, triar **Tancat** i prémer *Actualitzar* | Es torna al detall amb l'avís, i la insígnia diu «Tancat» |
| Amb F12, canviar el `value` d'una opció a `hacker` i enviar | Es torna al detall **sense** avís i l'estat **no canvia** |
| Escriure en la barra `/admin/?accio=eliminar&id=ID` | Es redirigix al llistat i el lead **no s'esborra** |
| Escriure `/admin/?accio=canviar-estat&id=ID` | Es redirigix al llistat |
| Prémer **Eliminar lead** i **cancel·lar** el quadre | No passa res |
| Prémer **Eliminar lead** i **acceptar** | El llistat mostra «El lead s'ha eliminat correctament.» i el lead ja no hi és |
| Mirar `www/uploads/` després d'eliminar un lead amb imatge | El fitxer **ha desaparegut** |
| Visitar el detall del lead eliminat | Pàgina «No trobat» |

::: info Nota
Si recarregues el llistat després d'eliminar, l'avís **continua apareixent**, igual que passava amb «canvis guardats». Els missatges *flash* amb sessions ho resoldran en el punt 11.
:::

#### 10.4.8. Resum del CRUD

Estes són totes les accions de l'administració i les seues peces:

| Acció (`?accio=`) | Mètode HTTP | Mètode del controlador | Mètode del DAO | Vista |
| --- | --- | --- | --- | --- |
| `llistar` | GET | `llistar()` | `filtrar()`, `comptar()` | `admin/leads/llistat` |
| `detall` | GET | `detall()` | `getById()` | `admin/leads/detall` |
| `editar` | GET | `editar()` | `getById()` | `admin/leads/editar` |
| `actualitzar` | POST | `actualitzar()` | `update()` | (redirigix) |
| `canviar-estat` | POST | `canviarEstat()` | `canviarEstat()` | (redirigix) |
| `eliminar` | POST | `eliminar()` | `delete()` | (redirigix) |

I les de la zona pública:

| Acció (`?accio=`) | Mètode HTTP | Mètode del controlador | Mètode del DAO | Vista |
| --- | --- | --- | --- | --- |
| `formulari` | GET | `formulari()` | — | `public/leads/formulari` |
| `guardar` | POST | `guardar()` | `insert()` | (redirigix) |
| `gracies` | GET | `gracies()` | — | `public/leads/gracies` |

Totes les accions de lectura (`GET`) **mostren** una vista, i totes les que modifiquen dades (`POST`) **redirigixen** després de fer la seua feina.

📌 **A recordar:** les accions que modifiquen dades es fan amb `POST` i el controlador comprova el mètode; l'estat es valida encara que vinga d'un `select`; es guarda o s'elimina primer en la base de dades i després es toquen els fitxers; el canvi ràpid d'estat reutilitza `LeadDAO::canviarEstat()`; sempre es redirigix després d'un `POST`.

📖 Documentació oficial

[PHP: Variables predefinides, `$_SERVER`](https://www.php.net/manual/es/reserved.variables.server.php)

## 11. Login contra la base de dades

Ara mateix l'administració (`/admin/`) és accessible per a qualsevol persona. En aquest punt la protegirem amb un **inici de sessió** que comprova l'usuari contra la taula `usuaris`.

El flux serà aquest:

```text
GET  /admin/                      → no hi ha sessió → redirigix a /admin/login.php
GET  /admin/login.php             → formulari de login
POST /admin/login.php?accio=entrar
        ├─ credencials incorrectes → torna al formulari amb un error
        └─ correctes               → guarda l'usuari en la sessió → redirigix a /admin/
POST /admin/login.php?accio=eixir → destruïx la sessió → redirigix al login
```

Ja coneixem les peces: la classe `Usuari` amb `verificarContrasenya()` (punt 4), `UsuariDAO::getByEmail()` (punt 8) i les sessions de PHP (unitat 3).

### 11.1. Sessions

#### 11.1.1. Activar les sessions

Totes les pàgines han de poder llegir la sessió, així que la iniciem en `bootstrap.php`. Substitueix el contingut de `www/config/bootstrap.php`:

```php
<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use App\Database\DBConnection;

session_set_cookie_params([
    'httponly' => true,
    'samesite' => 'Lax',
]);
session_start();

$config = require __DIR__ . '/config.php';

try {
    $pdo = (new DBConnection($config['db']))->getConnection();
} catch (RuntimeException $e) {
    http_response_code(500);
    exit($e->getMessage());
}
```

Les dos opcions de `session_set_cookie_params()` protegixen la cookie de sessió:

| Opció | Efecte |
| --- | --- |
| `httponly => true` | JavaScript no pot llegir la cookie (protegix davant d'un XSS) |
| `samesite => 'Lax'` | El navegador no envia la cookie en peticions iniciades des d'altres llocs web (redueix el risc de CSRF) |

::: info Nota
En un servidor amb HTTPS s'afegix també `'secure' => true`, perquè la cookie només viatge xifrada. En el nostre entorn local (`http://localhost`) no es pot activar.
:::

::: tip Bona pràctica
`session_start()` s'ha d'executar **abans d'enviar qualsevol text** al navegador. Per això va al principi, en `bootstrap.php`, que és el primer que carrega cada punt d'entrada.
:::

#### 11.1.2. La classe `Sessio`

Per a no escampar `$_SESSION['...']` per tot el codi, centralitzem el que fem amb la sessió en una classe d'utilitats. Crea `www/src/Helpers/Sessio.php`:

```php
<?php

declare(strict_types=1);

namespace App\Helpers;

use App\Models\Usuari;

class Sessio
{
    public static function iniciarSessio(Usuari $usuari): void
    {
        session_regenerate_id(true);

        $_SESSION['usuari_id'] = $usuari->getId();
        $_SESSION['usuari_nom'] = $usuari->getNom();
    }

    public static function tancarSessio(): void
    {
        $_SESSION = [];
        session_destroy();
    }

    public static function estaConnectat(): bool
    {
        return isset($_SESSION['usuari_id']);
    }

    public static function nomUsuari(): string
    {
        return (string) ($_SESSION['usuari_nom'] ?? '');
    }

    /**
     * Si no hi ha cap usuari connectat, redirigix al login i atura l'execució.
     */
    public static function exigirLogin(): void
    {
        if (!self::estaConnectat()) {
            header('Location: /admin/login.php');
            exit;
        }
    }

    public static function afegirAvis(string $missatge): void
    {
        $_SESSION['avis'] = $missatge;
    }

    /**
     * Retorna l'avís pendent (si n'hi ha) i l'esborra de la sessió.
     */
    public static function agafarAvis(): ?string
    {
        $avis = $_SESSION['avis'] ?? null;
        unset($_SESSION['avis']);

        return $avis;
    }
}
```

Les peces més importants:

- **`session_regenerate_id(true)`** canvia l'identificador de la sessió **just en iniciar sessió**. Evita l'atac de *fixació de sessió*: un atacant que haguera aconseguit l'ID anterior no podria utilitzar-lo.
- **Només guardem en la sessió l'`id` i el nom de l'usuari**. **Mai la contrasenya**, ni el hash.
- **`tancarSessio()`** buida l'array i destruïx la sessió en el servidor.
- **`exigirLogin()`** és el «guarda» de l'administració: si no hi ha usuari, redirigix i **atura** l'execució amb `exit`. Sense `exit`, PHP continuaria executant el codi de sota.
- **`afegirAvis()` i `agafarAvis()`** s'utilitzaran en el punt 11.4 per als missatges *flash*.

### 11.2. Validar les credencials

#### 11.2.1. El `UsuariController`

Crea `www/src/Controllers/UsuariController.php`:

```php
<?php

declare(strict_types=1);

namespace App\Controllers;

use App\DAO\UsuariDAO;
use App\Helpers\Sessio;

class UsuariController extends BaseController
{
    public function __construct(private UsuariDAO $usuariDao)
    {
    }

    public function formulari(): void
    {
        if (Sessio::estaConnectat()) {
            $this->redirigir('/admin/');
        }

        $this->renderitzar('admin/login', [
            'titol' => 'Iniciar sessió',
            'email' => '',
            'error' => null,
        ]);
    }

    public function entrar(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirigir('/admin/login.php');
        }

        $email = trim($_POST['email'] ?? '');
        $contrasenya = $_POST['contrasenya'] ?? '';

        $usuari = $this->usuariDao->getByEmail($email);

        if ($usuari === null || !$usuari->verificarContrasenya($contrasenya)) {
            $this->renderitzar('admin/login', [
                'titol' => 'Iniciar sessió',
                'email' => $email,
                'error' => 'El correu o la contrasenya no són correctes.',
            ]);

            return;
        }

        Sessio::iniciarSessio($usuari);

        $this->redirigir('/admin/');
    }

    public function eixir(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Sessio::tancarSessio();
        }

        $this->redirigir('/admin/login.php');
    }
}
```

Pas a pas:

1. **`formulari()`**: si ja has iniciat sessió, no té sentit tornar al login, així que et porta a l'administració.
2. **`entrar()`** només accepta `POST`. Llig el correu (amb `trim()`) i la contrasenya.
3. **Busquem l'usuari pel correu** amb `UsuariDAO::getByEmail()`.
4. **Comprovem la contrasenya** amb `verificarContrasenya()`, que utilitza `password_verify()` contra el hash guardat.
5. Si falla, **tornem a mostrar el formulari**, conservant el correu escrit.
6. Si va bé, **iniciem la sessió** i redirigim a l'administració (*Post/Redirect/Get*).
7. **`eixir()`** destruïx la sessió només amb `POST` (és una acció que canvia l'estat, com vam vore en el 10.4).

::: warning Atenció
Fixa't que hi ha **un únic missatge d'error** per als dos casos: correu inexistent i contrasenya errònia. Si diguérem «aquest correu no existix», un atacant podria descobrir quins correus estan registrats. Tampoc fem `trim()` a la contrasenya: els espais en una contrasenya són vàlids.
:::

::: info Nota
Aquest login no limita els intents. Una aplicació real hauria de **bloquejar temporalment** després de diversos errors seguits per a dificultar els atacs de força bruta. Ho deixem fora d'aquesta unitat.
:::

#### 11.2.2. La vista del login

El login no utilitza la capçalera de l'administració (no té sentit mostrar un botó d'«Eixir» a qui encara no ha entrat), així que és una pàgina completa. Crea `www/vistes/admin/login.php`:

```php
<?php

declare(strict_types=1);

use App\Helpers\Helpers;
?>
<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= Helpers::escapar($titol) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white text-slate-900 antialiased">
    <main class="mx-auto flex min-h-screen max-w-md flex-col justify-center px-6 py-16">
        <h1 class="text-5xl font-semibold tracking-tight">Iniciar sessió</h1>
        <p class="mt-6 text-xl leading-relaxed text-slate-600">
            Accés a l'administració de leads.
        </p>

        <form method="post" action="/admin/login.php?accio=entrar" class="mt-12 space-y-8">
            <?php if ($error !== null) : ?>
                <p class="rounded-lg bg-red-50 px-5 py-4 text-lg text-red-700">
                    <?= Helpers::escapar($error) ?>
                </p>
            <?php endif; ?>

            <div>
                <label for="email" class="block text-lg font-medium">Correu electrònic</label>
                <input type="email" id="email" name="email" autofocus autocomplete="username"
                       value="<?= Helpers::escapar($email) ?>"
                       class="mt-3 w-full rounded-lg border border-slate-300 px-5 py-4 text-xl">
            </div>

            <div>
                <label for="contrasenya" class="block text-lg font-medium">Contrasenya</label>
                <input type="password" id="contrasenya" name="contrasenya"
                       autocomplete="current-password"
                       class="mt-3 w-full rounded-lg border border-slate-300 px-5 py-4 text-xl">
            </div>

            <button type="submit"
                    class="w-full rounded-lg bg-slate-900 px-8 py-4 text-xl font-medium text-white hover:bg-slate-700">
                Entrar
            </button>
        </form>

        <a href="/public/" class="mt-12 text-lg text-slate-400 hover:text-slate-900">
            ← Tornar al web
        </a>
    </main>
</body>
</html>
```

- **`type="password"`** oculta el que s'escriu.
- **`autocomplete="username"` i `"current-password"`** ajuden els gestors de contrasenyes del navegador.
- **El camp de contrasenya no té `value`**: mai es torna a escriure una contrasenya en el HTML.

#### 11.2.3. El punt d'entrada del login

Crea `www/admin/login.php`:

```php
<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/bootstrap.php';

use App\Controllers\UsuariController;
use App\DAO\UsuariDAO;

$controlador = new UsuariController(new UsuariDAO($pdo));

$accio = $_GET['accio'] ?? 'formulari';

match ($accio) {
    'formulari' => $controlador->formulari(),
    'entrar' => $controlador->entrar(),
    'eixir' => $controlador->eixir(),
    default => $controlador->noTrobat(),
};
```

És el mateix esquema que en `admin/index.php` i `public/index.php`: un punt d'entrada per zona, amb una llista tancada d'accions.

### 11.3. Protegir l'administració

#### 11.3.1. Exigir el login

Només cal una línia nova en `www/admin/index.php`, abans de crear el controlador:

```php
<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/bootstrap.php';

use App\Controllers\LeadController;
use App\DAO\LeadDAO;
use App\Helpers\Sessio;

Sessio::exigirLogin();

$controlador = new LeadController(new LeadDAO($pdo));

$accio = $_GET['accio'] ?? 'llistar';

match ($accio) {
    'llistar' => $controlador->llistar(),
    'detall' => $controlador->detall(),
    'editar' => $controlador->editar(),
    'actualitzar' => $controlador->actualitzar(),
    'canviar-estat' => $controlador->canviarEstat(),
    'eliminar' => $controlador->eliminar(),
    default => $controlador->noTrobat(),
};
```

Com que **tota** l'administració passa per `admin/index.php`, esta única comprovació protegix totes les accions: llistar, detall, editar, actualitzar, canviar estat i eliminar.

::: danger Error crític
La comprovació ha d'estar **abans** de crear el controlador i de qualsevol acció. Si es posara després, o només en algunes accions, quedarien rutes sense protecció. **Protegir el punt d'entrada és més segur que protegir cada pantalla.**
:::

#### 11.3.2. La capçalera: usuari i botó «Eixir»

Substitueix tot el contingut de `www/vistes/layouts/capcalera.php` per aquest. Afig el nom de l'usuari i el botó per a tancar sessió (només si hi ha sessió), i l'espai per als avisos del punt 11.4:

```php
<?php

use App\Helpers\Helpers;
use App\Helpers\Sessio;

$avis = Sessio::agafarAvis();
?>
<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= Helpers::escapar($titol ?? 'Leads') ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white text-slate-900 antialiased">
    <header class="border-b border-slate-200">
        <div class="mx-auto flex max-w-5xl items-center justify-between px-6 py-6">
            <a href="/admin/" class="text-2xl font-semibold tracking-tight">Leads</a>
            <div class="flex items-center gap-8 text-lg">
                <a href="/public/" class="text-slate-500 hover:text-slate-900">
                    Formulari públic
                </a>
                <?php if (Sessio::estaConnectat()) : ?>
                    <span class="text-slate-500">
                        <?= Helpers::escapar(Sessio::nomUsuari()) ?>
                    </span>
                    <form method="post" action="/admin/login.php?accio=eixir">
                        <button type="submit" class="font-medium hover:underline">Eixir</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </header>
    <main class="mx-auto max-w-5xl px-6 py-16">
        <?php if ($avis !== null) : ?>
            <p class="mb-10 rounded-lg bg-emerald-50 px-5 py-4 text-lg text-emerald-700">
                <?= Helpers::escapar($avis) ?>
            </p>
        <?php endif; ?>
```

El botó «Eixir» és un **formulari `POST`**, igual que les altres accions que canvien l'estat.

### 11.4. Missatges *flash*

Hem anat usant `?guardat=1` i `?eliminat=1` per a mostrar avisos, i hem dit que **tornaven a aparéixer en recarregar**. Amb la sessió ho resolem: un **missatge *flash*** es guarda en la sessió, es mostra **una sola vegada** (`agafarAvis()` el llig i l'esborra) i desapareix.

La capçalera ja mostra l'avís. Ara toca **substituir** els paràmetres de la URL pels missatges. Tots els canvis són en `LeadController` i en dos vistes.

**1.** Afig la importació en `LeadController`:

```php
use App\Helpers\Sessio;
```

**2.** En `detall()`, **elimina** la línia `'guardat' => isset($_GET['guardat']),` de l'array.

**3.** En `llistar()`, **elimina** la línia `'eliminat' => isset($_GET['eliminat']),` de l'array.

**4.** En `actualitzar()`, substitueix l'últim `redirigir(...)` per:

```php
        Sessio::afegirAvis('Els canvis s\'han guardat correctament.');

        $this->redirigir('/admin/' . Helpers::construirUrl([
            'accio' => 'detall',
            'id' => $lead->getId(),
        ]));
```

**5.** En `canviarEstat()`, substitueix la part final del mètode (des de `$estat = ...` fins al final) per:

```php
        $estat = $_POST['estat'] ?? '';

        if (array_key_exists($estat, Lead::ESTATS)) {
            $this->leadDao->canviarEstat($lead->getId(), $estat);
            Sessio::afegirAvis('L\'estat s\'ha actualitzat.');
        }

        $this->redirigir('/admin/' . Helpers::construirUrl([
            'accio' => 'detall',
            'id' => $lead->getId(),
        ]));
```

**6.** En `eliminar()`, substitueix l'últim `redirigir(...)` per:

```php
        Sessio::afegirAvis('El lead s\'ha eliminat correctament.');

        $this->redirigir('/admin/');
```

**7.** En `www/vistes/admin/leads/detall.php`, **elimina** el bloc de l'avís:

```php
<?php if ($guardat) : ?>
    <p class="mt-8 rounded-lg bg-emerald-50 px-5 py-4 text-lg text-emerald-700">
        Els canvis s'han guardat correctament.
    </p>
<?php endif; ?>
```

**8.** En `www/vistes/admin/leads/llistat.php`, **elimina** el bloc de l'avís:

```php
<?php if ($eliminat) : ?>
    <p class="mt-8 rounded-lg bg-emerald-50 px-5 py-4 text-lg text-emerald-700">
        El lead s'ha eliminat correctament.
    </p>
<?php endif; ?>
```

Com que l'avís viu **en la capçalera**, qualsevol pantalla de l'administració el mostra sense repetir codi, i els controladors només han de cridar a `Sessio::afegirAvis(...)` abans de redirigir.

::: tip Bona pràctica
Els avisos *flash* són el mateix patró que utilitza Laravel (`session()->flash(...)` i `with('status', ...)`). El que acabes de construir és la versió bàsica del mecanisme.
:::

### 11.5. Comprovació

Visita <http://localhost:8080/admin/> i prova aquests casos:

| Prova | Resultat esperat |
| --- | --- |
| Obrir `/admin/` sense sessió | Et redirigix a `/admin/login.php` |
| Obrir `/admin/?accio=detall&id=1` sense sessió | També et redirigix al login |
| Entrar amb `admin@techleads.test` i `admin123` | Redirecció a `/admin/` i apareix el teu nom en la capçalera |
| Entrar amb una contrasenya errònia | `El correu o la contrasenya no són correctes.` i el correu es conserva |
| Entrar amb un correu que no existix | **El mateix missatge** |
| Visitar `/admin/login.php` ja connectat | Et redirigix a `/admin/` |
| Editar un lead i guardar | Avís verd en el detall |
| **Recarregar** el detall | L'avís **ha desaparegut** |
| Eliminar un lead de prova | Avís verd en el llistat |
| Prémer **Eixir** | Torna al login |
| Després d'eixir, tornar a `/admin/` | Redirigix al login |
| Visitar `/public/` sense sessió | El formulari públic **continua funcionant** |

Comprovacions amb les eines de desenvolupador (F12 → *Application* o *Aplicació* → *Cookies*):

- La cookie `PHPSESSID` apareix amb `HttpOnly` i `SameSite=Lax`.
- L'**ID de sessió canvia** entre abans i després d'iniciar sessió (efecte de `session_regenerate_id`).

::: warning Atenció (les imatges continuen sent públiques)
Els fitxers de `/uploads/` **no passen pel login**: qualsevol persona que conega l'URL d'una imatge pot obrir-la. És un risc acceptable per a imatges d'un projecte didàctic, però per a fitxers privats caldria servir-los amb un script PHP que comprove la sessió. Recorda el consell sobre `uniqid()` del punt 9.
:::

::: info Nota
En el nostre cas la sessió també es crea en la part pública (`bootstrap.php` la inicia sempre). No és un problema, però en un projecte més gran es pot iniciar només on es necessita.
:::

📌 **A recordar:** la contrasenya es comprova amb `password_verify()` contra el hash, i mai es guarda en la sessió; el missatge d'error és el mateix si falla el correu o la contrasenya; es regenera l'ID de sessió en iniciar sessió; es protegix el punt d'entrada de l'administració i no cada pantalla; les accions que canvien l'estat (eixir, eliminar) són `POST`; els avisos *flash* es mostren una sola vegada.

📖 Documentació oficial

[PHP: password_verify](https://www.php.net/manual/es/function.password-verify.php) · [PHP: session_regenerate_id](https://www.php.net/manual/es/function.session-regenerate-id.php)