# Bloc 1. Introducció a Laravel i Laravel Sail

## 1. Què és Laravel

### 1.1. De la unitat 4 a un framework

A la [unitat 4](/contenidos/4-acces-a-dades) vas construir TechLeads sense framework: carpeta `public/`, autoload amb Composer, DAO amb PDO, validació, sessions i pujada d'arxius. Tot això ho necessita **qualsevol** aplicació web, i un **framework** és eixa part comuna ja escrita i provada. Laravel no canvia el que vas aprendre: canvia **qui ho fa**.

| Unitat 4 (a mà)                                       | Laravel                         | Bloc |
|-------------------------------------------------------|---------------------------------|------|
| `Dockerfile` i `docker-compose.yml` propis            | Sail                            | 1    |
| Un arxiu PHP per pàgina, `capcalera.php` i `peu.php`  | Rutes i components Blade        | 2    |
| La lògica dins de la pàgina                           | Controladors                    | 3    |
| `Lead`, `LeadDAO` i SQL escrit a mà                   | Models Eloquent i migracions    | 4    |
| `validarDadesLead()`                                  | Validació i Form Requests       | 5    |
| `$_SESSION['usuari']` i `password_verify()`           | Autenticació                    | 6    |
| `pujarArxiu()`                                        | Arxius, emails, sessions i cookies      | 7    |

A diferència d'una llibreria com PDO, a la qual cridem nosaltres, un framework dona l'estructura i és ell qui crida el nostre codi. Per això es basa en **convencions** de noms i de llocs, com ja vas veure amb PSR-4 o amb `FETCH_CLASS`: un model `Label` buscarà per ell mateix la taula `labels`. Per això el codi d'esta unitat anirà en **anglés**.


### 1.2. Versió i requisits

::: info Nota: el projecte de la unitat
Durant tota la unitat construirem una aplicació per a crear **etiquetes de productes alimentaris**: una part pública, on es mostren les etiquetes publicades com les entrades d'un blog, i una part privada, on els usuaris registrats creen les seues etiquetes i les descarreguen en PDF. Cada bloc la fa créixer un poc.
:::

Utilitzarem **Laravel 13**. Segons la [política de suport oficial](https://laravel.com/framework/docs/releases), cada versió rep correccions d'errors durant 18 mesos i correccions de seguretat durant 2 anys, i cada any (cap al primer trimestre) apareix una versió major.

| Versió | PHP       | Llançament               | Correccions d'errors fins a | Seguretat fins a         |
|--------|-----------|--------------------------|-----------------------------|--------------------------|
| 12     | 8.2 – 8.5 | febrer de 2025           | agost de 2026               | febrer de 2027           |
| 13     | 8.3 – 8.5 | primer trimestre de 2026 | tercer trimestre de 2027    | primer trimestre de 2028 |

Triem la 13 perquè és la més recent i perquè la 12 ja ha deixat de rebre correccions d'errors. A més, el PHP 8.5 que vas usar a la unitat 4 és compatible amb ella.

Per a treballar amb Laravel, la documentació oficial demana tindre instal·lats PHP, Composer i Node. Nosaltres **no instal·larem res d'això**: només necessitem WSL2 (Windows) i Docker, i Laravel Sail portarà la resta dins d'un contenidor (ho veurem al punt 2).

::: warning Atenció
Quan busques informació, **comprova sempre que siga per a Laravel 13**. A Internet, i també en les respostes de la IA, abunden tutorials i exemples de versions antigues que ja no encaixen amb l'estructura actual del projecte. A la web oficial pots canviar de versió amb el selector de dalt a la dreta.
:::

## 2. Laravel Sail

A la unitat 4 vam escriure el `Dockerfile` i el `docker-compose.yml`. **Laravel Sail** és el mateix entorn, però ja preparat: un fitxer `compose.yaml` i un script `sail` que viuen a l'arrel del projecte.

| Unitat 4                               | Laravel Sail                                                     |
|----------------------------------------|------------------------------------------------------------------|
| El nostre `Dockerfile` amb PHP i Apache | Contenidor `laravel.test`: PHP 8.5, Composer i Node 24           |
| Apache serveix la carpeta `public/`    | El servidor integrat de PHP (`artisan serve`)                    |
| MySQL i phpMyAdmin                     | MySQL ja inclòs; phpMyAdmin l'afegirem nosaltres (punt 2.4)      |
| —                                      | Mailpit: una bústia falsa per a veure els correus (bloc 7)       |

### 2.1. Requisits a Windows: WSL2 i Docker

Sail funciona en Windows a través de **WSL2**. Ja el tens de la unitat 4, però comprova-ho abans de començar.

1. Obri **PowerShell** i executa:

```powershell
wsl --list --verbose
```

La columna `VERSION` de la teua distribució ha de ser `2`.

2. Obri el terminal de la teua distribució (Ubuntu) i executa:

```bash
docker --version
docker ps
```

Si `docker ps` mostra una taula buida (sense errors), Docker funciona dins de WSL. Si dona error, obri **Docker Desktop** i activa la integració amb la teua distribució a *Settings → Resources → WSL integration*.

::: tip Bona pràctica
Guarda els projectes **dins de WSL** (per exemple `~/projects`), no en `/mnt/c/...`. Els fitxers de Windows passen per una capa de traducció i Docker va molt més lent amb ells.

Per a editar, instal·la l'extensió **WSL** de VS Code i obri el projecte des del terminal de WSL amb `code .`.
:::

### 2.2. Crear el projecte amb Sail

No tenim PHP ni Composer en Windows, i no els necessitem: l'script oficial s'executa dins de Docker. Des del terminal de WSL:

```bash
mkdir -p ~/projects
cd ~/projects
curl -s "https://laravel.build/etiquetes?with=mysql,mailpit" | bash
```

- `etiquetes` és el nom del projecte i de la carpeta que es crearà.
- `?with=mysql,mailpit` indica els serveis que volem. Només demanem els que necessitarem: MySQL i Mailpit. Així l'entorn gasta menys memòria.

La primera vegada tarda uns minuts, perquè descarrega les imatges de Docker. Al final pot demanar-te la contrasenya del teu usuari de WSL.

Entra en el projecte i obri'l amb VS Code:

```bash
cd etiquetes
code .
```

Obri el fitxer `.env` i comprova que el bloc de la base de dades diu que ens connectem al contenidor de MySQL:

```ini
DB_CONNECTION=mysql
DB_HOST=mysql
```

::: info Nota
`DB_HOST=mysql` és el nom del **servei** al `compose.yaml`, no `localhost`. Dins de Docker, cada contenidor es troba pel nom del seu servei. Ja ho vas fer així a la unitat 4.
:::

### 2.3. Arrancar l'entorn i ordres bàsiques

Per a no escriure `./vendor/bin/sail` cada vegada, crea l'àlies `sail`:

```bash
echo "alias sail='sh \$([ -f sail ] && echo sail || echo vendor/bin/sail)'" >> ~/.bashrc
source ~/.bashrc
```

Ara arranca els contenidors en segon pla:

```bash
sail up -d
```

::: warning Atenció
Abans d'arrancar, comprova que no hi ha res funcionant en el port **80** (servidor web) ni en el **3306** (MySQL). Para els contenidors de la unitat 4 amb `docker compose down` des de la seua carpeta.

L'àlies busca `vendor/bin/sail` en la carpeta actual: executa sempre `sail` des de l'**arrel del projecte**.
:::

Laravel guarda les sessions i altres dades en la base de dades, i encara està buida. Crea les taules per defecte:

```bash
sail artisan migrate
```

Això és una **migració**; al bloc 4 aprendràs a escriure-les tu.

Estes són les ordres que usaràs tota la unitat:

| Ordre                 | Què fa                                                                  |
|-----------------------|-------------------------------------------------------------------------|
| `sail up -d`          | Arranca els contenidors en segon pla                                    |
| `sail stop`           | Para els contenidors                                                    |
| `sail down`           | Para i elimina els contenidors (les dades de MySQL es conserven)       |
| `sail artisan ...`    | Executa Artisan dins del contenidor                                     |
| `sail composer ...`   | Executa Composer dins del contenidor                                    |
| `sail npm ...`        | Executa npm dins del contenidor                                         |
| `sail php ...`        | Executa PHP dins del contenidor                                         |
| `sail shell`          | Obri un terminal dins del contenidor de l'aplicació                     |


::: danger Error crític
`docker compose down -v` (amb `-v`) **esborra el volum de MySQL**, és a dir, tota la base de dades. No l'uses si no vols perdre les dades.
:::

### 2.4. Afegir phpMyAdmin

Sail no porta phpMyAdmin, però ja el coneixes de la unitat 4. Obri `compose.yaml` i afig este servei dins de `services:`, després de `mysql`:

```yaml
    phpmyadmin:
        image: 'phpmyadmin:5'
        ports:
            - '8080:80'
        environment:
            PMA_HOST: mysql
        depends_on:
            - mysql
        networks:
            - sail
```

- `PMA_HOST: mysql` li diu a phpMyAdmin a quin servei s'ha de connectar.
- `networks: - sail` el posa en la mateixa xarxa que els altres contenidors; sense això no trobaria `mysql`.

Aplica el canvi:

```bash
sail up -d
```

::: warning Atenció
Si tens en marxa el phpMyAdmin de la unitat 4, el port `8080` estarà ocupat. Para'l o canvia el port del nou servei (per exemple `'8081:80'`).
:::

### 2.5. Comprovar que funciona

1. **Contenidors en marxa:**

```bash
docker ps
```

Han d'aparéixer quatre contenidors amb noms semblants a `etiquetes-laravel.test-1`, `etiquetes-mysql-1`, `etiquetes-mailpit-1` i `etiquetes-phpmyadmin-1`.

2. **Versions dins del contenidor:**

```bash
sail php --version
sail node --version
```

Han de mostrar PHP 8.5.x i Node v24.x.

3. **L'aplicació:** obri <http://localhost> en el navegador. Ha d'aparéixer la pàgina de benvinguda de Laravel.

4. **Mailpit:** obri <http://localhost:8025>. Ha de mostrar una bústia buida.

5. **phpMyAdmin:** obri <http://localhost:8080> i entra amb els valors de `DB_USERNAME` i `DB_PASSWORD` del teu `.env`. Dins de la base de dades han d'aparéixer les taules creades per la migració, com `users`, `sessions` i `migrations`.

::: tip Bona pràctica
Als blocs 1 a 3 no usarem la IA per a escriure codi, però sí com a **tutor**. Si Sail et dona un error, copia'l **sencer** i pregunta-li què significa i què pots comprovar. No li demanes que ho arregle per tu: entendre l'error és part de l'aprenentatge.
:::

## 3. Estructura del projecte

### 3.1. Mapa de carpetes i .env

Obri el projecte en VS Code i compara'l amb el de la unitat 4. Tot té un lloc, i la majoria de carpetes ja te les imagines:

| Carpeta       | Què conté                                                          | Unitat 4                       | Bloc |
|---------------|--------------------------------------------------------------------|--------------------------------|------|
| `app/`        | El codi de l'aplicació: models, controladors... (espai de noms `App\`) | `src/`                     | 3, 4 |
| `bootstrap/`  | `app.php`, que arranca el framework                                | Part del nostre `bootstrap.php` | —   |
| `config/`     | Tots els fitxers de configuració                                   | `config/`                      | —    |
| `database/`   | Migracions, *factories* i *seeders*                                | `databases/` (només un `.sql`) | 4    |
| `public/`     | `index.php`, l'**únic** punt d'entrada, i els recursos públics     | `public/`                      | 2    |
| `resources/`  | Les vistes i el CSS/JS sense compilar                              | `vistes/`                      | 2, 5 |
| `routes/`     | Les rutes de l'aplicació (`web.php`)                               | Un arxiu PHP per pàgina        | 2    |
| `storage/`    | Arxius generats i pujats, i *logs*                                 | `public/pujades/`              | 7    |
| `vendor/`     | El codi de Composer                                                | `vendor/`                      | —    |

A l'arrel també hi ha fitxers que usarem sovint: `artisan` (l'ordre `sail artisan` l'executa), `composer.json`, `package.json` (npm, per a Tailwind al punt 5) i `.env`.

::: tip Bona pràctica
Igual que a la unitat 4, el navegador només pot arribar a `public/`. Ningú pot demanar des de la URL un arxiu de `app/`, `config/` o `.env`, encara que en sàpia el nom.
:::

#### El fitxer `.env`

A la unitat 4 vam deixar les credencials en `config/database.php` i vam avisar que era millor llegir-les de **variables d'entorn**. Laravel ho fa així: el fitxer `.env` guarda la configuració que **canvia segons l'entorn** (el teu ordinador, el servidor...). Obri'l i busca estes línies:

```ini
APP_NAME=Laravel
APP_ENV=local
APP_KEY=base64:...
APP_DEBUG=true
APP_URL=http://localhost
```

| Variable    | Per a què serveix                                                                       |
|-------------|-----------------------------------------------------------------------------------------|
| `APP_NAME`  | Nom de l'aplicació                                                                      |
| `APP_ENV`   | L'entorn: `local` en el teu ordinador, `production` en un servidor real                 |
| `APP_KEY`   | Clau secreta que Laravel usa per a xifrar, p. ex., les galetes i les sessions           |
| `APP_DEBUG` | Si és `true`, els errors es mostren amb tots els detalls                                |
| `APP_URL`   | L'adreça de l'aplicació                                                                 |

Canvia el nom de l'aplicació (entre cometes, perquè comença amb majúscula i podria tindre espais):

```ini
APP_NAME="Etiquetes"
```

::: danger Error crític
`APP_DEBUG=true` mostra rutes, consultes i fins i tot valors del `.env` quan hi ha un error. És perfecte en el teu ordinador, però **mai** s'ha de deixar activat en un servidor real. És el mateix que vam dir de `$e->getMessage()` a la unitat 4.
:::

::: info Nota
El fitxer `.env` no es comparteix mai, perquè conté contrasenyes i la `APP_KEY`. En el codi de l'aplicació no es llig directament: s'usa `config('app.name')`, que agafa el valor de `config/app.php`. Ho veurem quan faça falta.
:::

### 3.2. Comprovar que funciona

1. Executa:

```bash
sail artisan about
```

Ha de mostrar un resum amb, entre altres coses:

- `Application Name`: **Etiquetes** (el valor que has posat al `.env`).
- `Laravel Version`: **13.x**.
- `PHP Version`: 8.5.x.
- `Environment`: `local`, i `Debug Mode`: `ENABLED`.

2. Comprova que `.env` **no** és accessible des del navegador. Obri <http://localhost/.env>: ha de mostrar un error **404** i, en cap cas, el contingut del fitxer.

3. Torna a `APP_NAME`, posa-hi un altre nom i executa `sail artisan about` una altra vegada: el nom ha de canviar. Deixa'l finalment amb `"Etiquetes"`.
## 4. Primera ruta i artisan

### 4.1. La primera ruta i vista

A la unitat 4, cada URL era un arxiu PHP (`leads.php`, `lead.php`...). A Laravel, **tota** petició entra per `public/index.php` i el framework decidix què fer segons les **rutes** que hem definit. Les rutes de la part web viuen al fitxer `routes/web.php`.

Obri'l. Ha de contindre una ruta com esta, que és la que mostra la pàgina de benvinguda:

```php
Route::get('/', function () {
    return view('welcome');
});
```

- **`Route::get`**: la ruta respon a peticions `GET`.
- **`'/'`**: la URL a la qual respon.
- **La funció**: el codi que s'executa. Ací torna una **vista**: `view('welcome')` carrega l'arxiu `resources/views/welcome.blade.php`.

Les vistes de Laravel són arxius `.blade.php`. Són HTML normal amb algunes ajudes, i les veurem a fons al bloc 2. Crearem una ruta i una vista pròpies.

**`routes/web.php`**: afig esta ruta al final:

```php
Route::get('/hola', function () {
    return view('hola', ['nom' => 'Laravel']);
});
```

El segon paràmetre de `view()` és un array amb les dades que arribaran a la vista.

**`resources/views/hola.blade.php`**: crea este arxiu nou:

```blade
<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <title>Hola</title>
</head>
<body>
    <h1>Hola, {{ $nom }}!</h1>
    <p>Ara són les {{ now()->format('H:i:s') }}.</p>
</body>
</html>
```

Les claus <code v-pre>{{ }}</code> imprimixen el valor **escapat**, igual que la funció `e()` de la unitat 4. La clau `nom` de l'array arriba a la vista com la variable `$nom`.

::: info Nota
Esta ruta i esta vista són **de prova**. Les farem servir al punt 5 per a comprovar Tailwind, i les esborrarem al final del bloc.
:::

### 4.2. Artisan: les ordres imprescindibles

**Artisan** és l'eina de línia d'ordres inclosa amb Laravel. És l'arxiu `artisan` de l'arrel del projecte, i amb Sail l'executem com `sail artisan ...`. Estes són les ordres que més usarem:

| Ordre                       | Què fa                                                                    |
|-----------------------------|---------------------------------------------------------------------------|
| `sail artisan list`         | Mostra totes les ordres disponibles                                       |
| `sail artisan help migrate` | Mostra l'ajuda d'una ordre (ací, la de `migrate`)                         |
| `sail artisan route:list`   | Mostra totes les rutes registrades                                        |
| `sail artisan make:...`     | Crea fitxers nous amb l'estructura bàsica (controladors, models, etc.)    |
| `sail artisan tinker`       | Obri una consola interactiva per a provar codi de l'aplicació             |
| `sail artisan about`        | Mostra el resum de l'aplicació (el que has executat al punt 3)            |

#### Les ordres `make:`

Les ordres que comencen per `make:` creen fitxers **al lloc correcte i amb el *namespace* correcte**, seguint les convencions de Laravel. Tu només n'ompli el contingut. Les usarem a partir del bloc 3:

| Ordre                | Crea                               | Bloc |
|----------------------|------------------------------------|------|
| `make:controller`    | Un controlador                     | 3    |
| `make:model`         | Un model Eloquent                  | 4    |
| `make:migration`     | Una migració                       | 4    |
| `make:seeder`        | Un *seeder*                        | 4    |
| `make:request`       | Un *Form Request*                  | 5    |

Per a veure'n la llista completa, executa `sail artisan list` i busca la secció `make`.

#### Tinker

**Tinker** és una consola on pots executar codi PHP **dins de la teua aplicació**, amb tot carregat: la configuració, les classes, i després la base de dades. És ideal per a provar coses xicotetes sense crear cap pàgina. Entra amb:

```bash
sail artisan tinker
```

Dins, escriu les línies que van darrere de `>>>`:

```php
>>> config('app.name')
>>> now()->format('d/m/Y')
>>> exit
```

La primera torna el nom que has posat al `.env`, la segona la data d'avui, i `exit` t'ha de fer eixir de Tinker.

### 4.3. Comprovar que funciona

1. Obri <http://localhost/hola>. Ha d'aparéixer **«Hola, Laravel!»** i l'hora actual.
2. Executa:

```bash
sail artisan route:list
```

La llista ha d'incloure, entre altres, les rutes `/` i `hola`, totes dos amb el mètode `GET`.

3. Canvia `'Laravel'` per un altre nom a `routes/web.php` i recarrega la pàgina: el text canvia al moment, sense reiniciar res.
4. Obri Tinker i comprova que `config('app.name')` retorna `"Etiquetes"`.
5. Obri <http://localhost/no-existix>: ha d'eixir una pàgina d'error **404**, perquè no hi ha cap ruta amb eixe nom.
6. Prova un error a propòsit. A `hola.blade.php`, canvia <code v-pre>{{ $nom }}</code> per <code v-pre>{{ $nombre }}</code> i recarrega. Com que `APP_DEBUG=true`, Laravel mostra una pàgina amb l'error (`Undefined variable $nombre`) i el codi on s'ha produït. Torna-ho a deixar com estava.
## 5. Tailwind CSS amb Vite

### 5.1. Compilar amb Vite i comprovar Tailwind

L'aplicació usarà **Tailwind CSS** per a tot el disseny. Tailwind no es carrega com un arxiu fet: genera el CSS **només de les classes que fas servir** als teus fitxers. Per a això Laravel usa **Vite**, una eina que compila el CSS i el JavaScript de la carpeta `resources/` i els serveix al navegador. No cal que aprengues Vite a fons: el tractarem com una **caixa negra** que només cal tindre en marxa.

Els projectes nous de Laravel ja porten Tailwind i `vite.config.js` configurats. El teu CSS viu a `resources/css/app.css`.

Vite té dos modes:

| Ordre                 | Quan                                                                                   |
|-----------------------|----------------------------------------------------------------------------------------|
| `sail npm install`    | Una vegada (o en afegir paquets): instal·la les dependències de `package.json`         |
| `sail npm run dev`    | **Mentre desenvolupes**: servidor de Vite que actualitza el navegador en guardar       |
| `sail npm run build`  | En producció: genera els arxius finals (no el farem servir en esta unitat)             |

#### Preparar-ho

1. Instal·la les dependències:

```bash
sail npm install
```

2. Com que treballem amb Sail dins de WSL2, cal indicar-li a Vite que el navegador es connecta a `localhost`. Obri `vite.config.js` i afig la secció `server` dins de `defineConfig`:

```js
export default defineConfig({
    // ... (la resta no canvia)
    server: {
        hmr: {
            host: 'localhost',
        },
    },
});
```

`hmr` (*Hot Module Replacement*) és la recàrrega automàtica: perquè funcione, el navegador ha de poder connectar-se al servidor de Vite.

3. Arranca Vite:

```bash
sail npm run dev
```

::: warning Atenció
Este terminal queda **ocupat** mentre Vite està en marxa: **no el tanques**. Per a la resta d'ordres (`sail artisan ...`, etc.) obri un segon terminal. Per a parar Vite, `Ctrl + C`.
:::

#### Usar Tailwind en una vista

Perquè una vista use el CSS compilat, cal la directiva `@vite` dins del `<head>`. Substituïx el contingut de `resources/views/hola.blade.php` (canvia en quasi totes les línies) per:

```blade
<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <title>Hola</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white text-slate-800 antialiased">
    <main class="mx-auto max-w-2xl px-6 py-20">
        <h1 class="text-5xl font-bold tracking-tight text-emerald-700">Hola, {{ $nom }}!</h1>
        <p class="mt-6 text-xl leading-relaxed text-slate-600">Ara són les {{ now()->format('H:i:s') }}.</p>
    </main>
</body>
</html>
```

Cada classe fa una sola cosa. Per exemple, `text-5xl` fa la lletra molt gran, `text-emerald-700` posa el color, `mt-6` afig espai per damunt i `max-w-2xl` limita l'amplada. Així tindrem lletres grans i text amb aire, com demana el disseny de l'aplicació.

::: warning Atenció
Si en obrir la pàgina apareix l'error `Vite manifest not found`, vol dir que **Vite no està en marxa**. Executa `sail npm run dev` i recarrega.
:::

::: tip Bona pràctica
A partir d'ara, cada vegada que comences a treballar, la rutina és: `sail up -d` i, en un terminal apart, `sail npm run dev`.
:::

### 5.2. Comprovar que funciona

1. Al terminal on has executat `sail npm run dev`, Vite ha de mostrar que està llest i una adreça local amb el port `5173`.
2. Obri <http://localhost/hola>. Ha d'aparéixer el títol **molt gran i en verd**, amb el text de sota més clar i separat.
3. Canvia `text-emerald-700` per `text-sky-700` i guarda. El títol ha de passar a **blau** sense recarregar la pàgina.
4. Para Vite amb `Ctrl + C` i recarrega la pàgina: ha d'eixir l'error `Vite manifest not found`. Torna a arrancar `sail npm run dev` i recarrega: la pàgina torna a funcionar.

::: info Nota
Si el pas 3 no es reflectix en el navegador, afig `watch: { usePolling: true }` dins de la secció `server` de `vite.config.js`, al costat de `hmr`, i reinicia Vite. Normalment no fa falta si el projecte està dins del sistema de fitxers de WSL, com vam recomanar al punt 2.
:::

## 6. Errors comuns i punt de control

### 6.1. Errors comuns i la seua causa

| Error o símptoma                                                            | Causa probable                                                                          | Solució                                                                                   |
|-----------------------------------------------------------------------------|-----------------------------------------------------------------------------------------|-------------------------------------------------------------------------------------------|
| `sail: command not found`                                                   | L'àlies no està creat, o el terminal no l'ha carregat                                   | Repassa el punt 2.3 i executa `source ~/.bashrc` (o obri un terminal nou)                 |
| `vendor/bin/sail: No such file or directory`                                | No estàs a l'arrel del projecte                                                         | Entra amb `cd ~/projects/etiquetes` i torna a provar                                       |
| `Cannot connect to the Docker daemon`                                       | Docker Desktop no està arrancat, o no té activada la integració amb WSL                 | Arranca Docker Desktop i activa la integració (punt 2.1)                                  |
| `Bind for 0.0.0.0:80 failed: port is already allocated`                     | Un altre contenidor o servidor usa el mateix port (80, 3306 o 8080)                     | Para els contenidors de la unitat 4 (`docker compose down`) o canvia el port              |
| `php: command not found` (o `composer`, `npm`)                              | Has executat l'ordre sense `sail`: en WSL no hi ha PHP, Composer ni npm                 | Escriu `sail artisan ...`, `sail composer ...` o `sail npm ...`                           |
| `SQLSTATE[HY000] [2002] Connection refused`                                 | MySQL encara no ha arrancat, o `DB_HOST` al `.env` no és `mysql`                        | Espera uns segons i comprova `DB_HOST=mysql`                                              |
| `Base table or view not found ... sessions' doesn't exist`                  | No has executat les migracions                                                          | `sail artisan migrate` (punt 2.3)                                                         |
| `SQLSTATE[HY000] [1045] Access denied for user`                             | Has canviat `DB_USERNAME` o `DB_PASSWORD` després de la primera arrancada               | Torna als valors originals o, si no importa perdre dades, `sail down -v` i `sail up -d`   |
| `View [hola] not found`                                                     | El fitxer no és `resources/views/hola.blade.php` (nom, extensió o carpeta)              | Comprova el nom i la ubicació                                                             |
| `Undefined variable $nom`                                                   | La clau de l'array de `view()` no coincidix amb el nom de la variable de la vista       | Fes que coincidisquen (`'nom'` i `$nom`)                                                  |
| `Vite manifest not found`                                                   | Vite no està en marxa                                                                   | Executa `sail npm run dev` en un terminal apart                                           |
| Les classes de Tailwind no tenen efecte                                     | Falta `@vite(...)` al `<head>` de la vista, o Vite no està en marxa                     | Revisa el `<head>` i el terminal de Vite                                                  |

::: danger Error crític
`sail down -v` (amb `-v`) **esborra el volum de MySQL**, és a dir, tota la base de dades. Ara mateix no importa perquè està buida, però **no l'uses mai** si ja hi ha dades que vols conservar.
:::

### 6.2. Llista de comprovació final del bloc

Abans de passar al bloc 2 cal esborrar les proves temporals que hem creat al punt 4.

1. **Esborra la ruta de prova.** A `routes/web.php`, elimina la ruta `/hola` sencera:

```php
Route::get('/hola', function () {
    return view('hola', ['nom' => 'Laravel']);
});
```

2. **Esborra la vista de prova:**

```bash
rm resources/views/hola.blade.php
```

Ara repassa la llista. Tot ha de complir-se:

- [ ] `docker ps` mostra quatre contenidors: l'aplicació (`laravel.test`), `mysql`, `mailpit` i `phpmyadmin`.
- [ ] <http://localhost> mostra la pàgina de benvinguda de Laravel.
- [ ] <http://localhost:8025> mostra Mailpit i <http://localhost:8080> mostra phpMyAdmin amb les taules creades per la migració.
- [ ] `sail artisan about` indica **Laravel 13.x**, PHP 8.5.x i l'aplicació `Etiquetes`.
- [ ] `sail artisan route:list` ja no mostra la ruta `hola`, i <http://localhost/hola> dona un error **404**.
- [ ] `sail npm run dev` arranca sense errors.

::: tip Bona pràctica
Si alguna casella no es compleix, busca l'error de la taula 6.1 abans de continuar: cada bloc **parteix del projecte funcionant** del bloc anterior. Al bloc 2 començarem a construir les rutes i les vistes de l'aplicació amb components Blade.
:::