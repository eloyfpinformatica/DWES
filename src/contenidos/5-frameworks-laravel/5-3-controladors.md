# Bloc 3. Controladors

## Temporalització

3 hores

## 1. De la closure al controlador

Al bloc anterior vam escriure tota la lògica dins de les rutes, en funcions anònimes (*closures*). Va bé per a començar, però `routes/web.php` ja fa massa coses. En este punt ho ordenem i, de pas, traiem les dades de mostra de les rutes.

### 1.1. Qui fa què

Ara mateix, cada closure de `routes/web.php` fa tres coses alhora: decidix quina URL respon, prepara les dades i tria la vista. Al **MVC** del bloc 2 cada peça té una única responsabilitat:

| Peça | Responsabilitat | A l'aplicació |
|---|---|---|
| **Ruta** | Lliga una URL amb el codi que la ateny | `routes/web.php` |
| **Controlador** | Rep la petició, demana les dades i tria la vista | Bloc 3 |
| **Model** | Sap d'on venen les dades i com tractar-les | Bloc 4 |
| **Vista** | Pinta el resultat en HTML | Blade (bloc 2) |

A la unitat 4 ja havies separat el controlador de la vista i de l'accés a dades. A Laravel passa el mateix, però la ruta ja no conté codi: només apunta al controlador.

::: tip Bona pràctica
Un controlador ha de ser **prim**: rep, demana i respon. Si apareix lògica de dades o càlculs, no és lloc per a ella. Per això ara traiem les dades de mostra fora de les rutes.
:::

### 1.2. Les dades de mostra, a `SampleLabels`

Els tres controladors que farem necessitaran les mateixes etiquetes de mostra, així que no poden viure dins d'un d'ells. Tampoc les deixem a `routes/web.php`. Creem una classe pròpia, `app/Support/SampleLabels.php`. La carpeta `Support` no existix: crea-la.

```php
<?php

namespace App\Support;

class SampleLabels
{
    /**
     * @return array<int, array<string, mixed>>
     */
    public static function all(): array
    {
        return [
            [
                'id' => 1,
                'product' => 'Taronja',
                'variety' => 'Navelina',
                'category' => 'Fruites',
                // ... author, date i description, tal com els tens
            ],
            // Els altres dos arrays, tal com els tens a routes/web.php
        ];
    }

    public static function find(int $id): ?array
    {
        return collect(self::all())->firstWhere('id', $id);
    }
}
```

Copia els tres arrays que tens ara a `routes/web.php` dins de `all()`, sense canviar-los. `find()` torna l'etiqueta amb eixe `id`, o `null` si no existix.

Els mètodes són `static` perquè la classe no guarda estat: només ofereix dades. Es parega al DAO de la unitat 4 ([els DAO `LeadDAO` i `UsuariDAO`](/contenidos/4-acces-a-dades#_8-els-dao-leaddao-i-usuaridao)): qui la use no sap d'on venen les dades. Al bloc 4 esborrarem esta classe i la substituirà Eloquent.

::: info Nota
Esta classe és **provisional**: fa de model fins que arribem a la base de dades. Al bloc 4 la substituirà el model `Label` d'Eloquent i la esborrarem. Els controladors, en canvi, es queden: només canviaran la línia que demana les dades.
:::

### 1.3. Usar la classe a les rutes

A `routes/web.php`, canvia el següent:

1. Esborra la variable `$labels` de dalt i afig l'`use` de la classe:

```php
use App\Support\SampleLabels;
```

2. A les rutes `home` i `panel.labels.index`, treu el `use ($labels)` de la funció i passa les dades des de la classe:

```php
return view('home', ['labels' => SampleLabels::all()]);
```

3. A la ruta `labels.show`, treu també el `use ($labels)` i busca l'etiqueta amb la classe nova. L'`id` arriba com a text, per això el convertim a enter:

```php
$label = SampleLabels::find((int) $id);
```

El `abort(404)` es queda igual, i les vistes no es toquen.

### 1.4. Comprovar que funciona

1. Obri <http://localhost>. Han d'aparéixer les mateixes tres etiquetes de sempre.
2. Obri una etiqueta des de la portada (<http://localhost/etiquetes/1>). Ha de mostrar el detall igual que abans.
3. Obri <http://localhost/etiquetes/99>. Ha de eixir la pàgina 404.
4. Obri <http://localhost/panell/etiquetes>. Han d'eixir les mateixes tres etiquetes.
5. Executa `sail artisan route:list`. Les rutes i els noms han de ser els mateixos.

## 2. El primer controlador: `LabelController`

Ara que les dades ja no viuen a les rutes, movem la lògica del detall d'una etiqueta a un controlador. Serà el primer, i la resta es faran igual.

### 2.1. Crear el controlador

Els controladors es generen amb Artisan:

```bash
sail artisan make:controller LabelController
```

Crea l'arxiu `app/Http/Controllers/LabelController.php` amb una classe buida. Per convenció, el nom va en singular, en *CamelCase* i acabat en `Controller`.


### 2.2. El mètode `show`

Cada acció és un mètode públic de la classe. Substituïx el contingut de `LabelController.php` per este:

```php
<?php

namespace App\Http\Controllers;

use App\Support\SampleLabels;
use Illuminate\View\View;

class LabelController
{
    public function show(string $id): View
    {
        $label = SampleLabels::find((int) $id);

        if ($label === null) {
            abort(404);
        }

        return view('labels.show', ['label' => $label]);
    }
}
```

És el mateix codi que tenia la funció anònima de la ruta `labels.show`, ara dins d'un mètode:
- **`string $id`**: Laravel passa al mètode el valor de `{id}` de la URL. Els paràmetres de la ruta s'injecten **per ordre**, no pel nom; però usem el mateix nom (`$id`) per claredat, i perquè al bloc 4, amb el binding implícit, sí que haurà de coincidir.
- **`: View`**: indica que el mètode torna una vista (cal l'`use Illuminate\View\View;`). No és obligatori, però documenta què torna.
- **`abort(404)`**: funciona igual que dins d'una closure.

### 2.3. Apuntar la ruta al controlador

A `routes/web.php`, afig l'`use` del controlador i substituïx la funció anònima de la ruta `labels.show`:

```php
use App\Http\Controllers\LabelController;

Route::get('/etiquetes/{id}', [LabelController::class, 'show'])
    ->whereNumber('id')
    ->name('labels.show');
```

La ruta ara diu: «quan arribe una petició a `/etiquetes/{id}`, executa el mètode `show` de `LabelController`». Escrivim `LabelController::class` (en lloc d'un text) perquè així l'editor ens avisa si el nom està mal escrit.

El `whereNumber` i el `name` es queden tal com estaven: **el nom de la ruta no canvia**, i per això les vistes continuen funcionant sense tocar-les.

### 2.4. Comprovar que funciona

1. Obri <http://localhost/etiquetes/1>. Ha de mostrar el detall de l'etiqueta, igual que abans.
2. Des de la portada, fes clic en una etiqueta: ha d'obrir el seu detall.
3. Obri <http://localhost/etiquetes/99>. Ha d'eixir la pàgina 404.
4. Executa `sail artisan route:list`. A la ruta `etiquetes/{id}` ha d'aparéixer el controlador (`LabelController@show`) en lloc d'una funció anònima.


## 3. Controlador d'una sola acció: `HomeController`

La portada només fa una cosa: mostrar les etiquetes. No té detall, ni edició, ni res més. Per a casos així, Laravel permet un controlador amb **una única acció**.

### 3.1. Crear el controlador

Afig l'opció `--invokable` a l'ordre:

```bash
sail artisan make:controller HomeController --invokable
```

Genera `app/Http/Controllers/HomeController.php` amb un únic mètode especial, `__invoke`, que PHP crida quan s'usa l'objecte com si fóra una funció.

### 3.2. El mètode `__invoke`

Substituïx el contingut de l'arxiu per este (el mètode generat pot portar un paràmetre `Request` que ací no necessitem):

```php
<?php

namespace App\Http\Controllers;

use App\Support\SampleLabels;
use Illuminate\View\View;

class HomeController
{
    public function __invoke(): View
    {
        return view('home', ['labels' => SampleLabels::all()]);
    }
}
```

### 3.3. Apuntar la ruta al controlador

A `routes/web.php`, afig l'`use` i substituïx la funció anònima de la ruta `home`:

```php
use App\Http\Controllers\HomeController;

Route::get('/', HomeController::class)->name('home');
```

Com que només té una acció, **no cal indicar el mètode**: n'hi ha prou amb el nom de la classe. Compara-ho amb la ruta del punt 2, on sí que cal l'array `[LabelController::class, 'show']`.

::: tip Bona pràctica
Usa un controlador d'una sola acció quan una pàgina fa una única cosa i no pertany a un recurs (la portada, «Sobre nosaltres»...). Si són accions d'un mateix recurs (veure, crear, editar), agrupa-les en un únic controlador amb diversos mètodes, com `LabelController`. I si la pàgina no necessita dades, `Route::view()` (punt 2.2 del bloc 2) continua sent més curt.
:::

### 3.4. Comprovar que funciona

1. Obri <http://localhost>. Han d'aparéixer les mateixes tres etiquetes de sempre.
2. Fes clic en una etiqueta: ha de portar al seu detall (el controlador del punt 2).
3. Comprova que l'enllaç «Etiquetes» o el logo de la barra (el que apunta a `route('home')`) continua portant a la portada: el nom de la ruta no ha canviat.
4. Executa `sail artisan route:list`. A la ruta `/` ha d'aparéixer `HomeController`, sense mètode.

## 4. El controlador del panell: `Panel\LabelController`

Queda l'última funció anònima: el llistat del panell. El panell és la part privada de l'aplicació, on cada usuari gestionarà les seues etiquetes. Per separar-lo de la part pública, el controlador viurà en una **subcarpeta** pròpia, `Panel`, igual que ja tenim `resources/views/panel/` per a les vistes i el prefix `panel.` per als noms de ruta.

### 4.1. Crear el controlador dins de la carpeta `Panel`

Escriu la subcarpeta davant del nom, separada per una barra:

```bash
sail artisan make:controller Panel/LabelController
```

Crea `app/Http/Controllers/Panel/LabelController.php` (i la carpeta `Panel`, si no existix) amb el namespace `App\Http\Controllers\Panel`. Comprova-ho obrint l'arxiu: com al [punt 1.2](#_1-2-les-dades-de-mostra-a-samplelabels), el namespace ha de coincidir amb la carpeta.

Ara tenim dos controladors amb el mateix nom, però de parts diferents:

| Classe | Arxiu | Part |
|---|---|---|
| `App\Http\Controllers\LabelController` | `Controllers/LabelController.php` | Pública (detall) |
| `App\Http\Controllers\Panel\LabelController` | `Controllers/Panel/LabelController.php` | Privada (panell) |

### 4.2. El mètode `index`

Substituïx el contingut de l'arxiu per este:

```php
<?php

namespace App\Http\Controllers\Panel;

use App\Support\SampleLabels;
use Illuminate\View\View;

class LabelController
{
    public function index(): View
    {
        return view('panel.labels.index', ['labels' => SampleLabels::all()]);
    }
}
```

`index` és el nom que es fa servir per convenció per a un llistat. Al punt 7 vorem d'on ve. Al bloc 5 i al 6 anirem afegint ací els mètodes per a crear, editar i eliminar.

### 4.3. Apuntar la ruta al controlador

Si a `routes/web.php` importàrem `Panel\LabelController` amb un `use`, xocaria amb el `LabelController` públic, que ja està importat. La solució més clara és importar **la carpeta** (el namespace `Panel`) i escriure el nom complet a partir d'ella. Afig l'`use` i canvia només la funció anònima del llistat, dins del grup `panell`:

```php
use App\Http\Controllers\Panel;

Route::prefix('panell')->name('panel.')->group(function () {
    // La ruta panel.index (el redirect) es queda igual

    Route::get('/etiquetes', [Panel\LabelController::class, 'index'])
        ->name('labels.index');
});
```

Fixa't que a les rutes ara apareixen `LabelController` (el públic) i `Panel\LabelController` (el privat): el prefix `Panel\` diu de quina part parlem. La URL i el nom de la ruta (`panel.labels.index`) són els que ja tenies: només canvia qui respon.

::: info Nota
Una alternativa és importar la classe amb un àlies: `use App\Http\Controllers\Panel\LabelController as PanelLabelController;`. Funciona igual, però obliga a recordar un nom que no coincidix amb el de la classe. Per això preferim importar la carpeta.
:::

Ara que cap ruta usa `SampleLabels`, **esborra l'`use App\Support\SampleLabels;` de dalt de `routes/web.php`**: ja només el necessiten els controladors.

::: warning Atenció
De moment el panell és públic: qualsevol persona pot entrar-hi i veu totes les etiquetes. És provisional; al bloc 6 el protegirem amb autenticació.
:::

### 4.4. Comprovar que funciona

1. Obri <http://localhost/panell/etiquetes>. Han d'aparéixer les tres etiquetes amb els botons «Veure» i «Editar».
2. Obri <http://localhost/panell>. Ha de redirigir-te a `/panell/etiquetes`.
3. Fes clic a «Veure» d'una etiqueta: ha de portar al detall (el `LabelController` públic).
4. Executa `sail artisan route:list`. Les rutes `home`, `labels.show` i `panel.labels.index` han de mostrar el seu controlador

## 5. Un `routes/web.php` net

Ja no queda cap funció anònima amb lògica a les rutes. Este punt és una revisió: deixem l'arxiu ordenat i comprovem el resultat amb `route:list`.

### 5.1. Com ha de quedar l'arxiu

`routes/web.php` ara només **lliga URLs amb controladors**. Ordena-ho en dos blocs, la part pública i el panell, i deixa dalt només els `use` que es fan servir:

```php
<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\LabelController;
use App\Http\Controllers\Panel;
use Illuminate\Support\Facades\Route;

// Part pública
Route::get('/', HomeController::class)->name('home');

Route::get('/etiquetes/{id}', [LabelController::class, 'show'])
    ->whereNumber('id')
    ->name('labels.show');

// ... les rutes login i register (Route::view), com les tens

// Panell
Route::prefix('panell')->name('panel.')->group(function () {
    // ... la ruta panel.index (el redirect), com la tens

    Route::get('/etiquetes', [Panel\LabelController::class, 'index'])
        ->name('labels.index');
});
```

Si ho comparem amb el bloc 2, la diferència és que **ja no hi ha dades ni decisions**: cada línia diu quina URL, quin controlador i quin nom. Si vols saber què fa una pàgina, vas al controlador; si vols saber quines pàgines hi ha, ho tens tot ací.

::: info Nota
Quan un controlador tinga diverses rutes (als blocs 5 i 6, el del panell tindrà crear, editar, eliminar...), podràs escriure'l una sola vegada amb `Route::controller()` i indicar després només el nom del mètode:

```php
Route::controller(Panel\LabelController::class)->group(function () {
    Route::get('/etiquetes', 'index')->name('labels.index');
    // ... create, store, edit, update, destroy
});
```

Ara no ho fem: amb una sola ruta per controlador no aporta res.
:::

### 5.2. Revisar les rutes amb `route:list`

Ja coneixes `sail artisan route:list`. Dos filtres que ajuden quan l'aplicació creix:

```bash
sail artisan route:list --except-vendor
sail artisan route:list --path=panell
```

El primer amaga les rutes que afigen paquets de tercers i deixa només les nostres. El segon mostra només les rutes la URL de les quals comença per `panell`.

### 5.3. Comprovar que funciona

1. Executa `sail artisan route:list --except-vendor`. Han d'aparéixer els noms `home`, `labels.show`, `login`, `register`, `panel.index` i `panel.labels.index`, sense cap canvi respecte al bloc 2.
2. Executa `sail artisan route:list --path=panell`. Han d'eixir només les dos rutes del panell.
3. Busca `function (` a `routes/web.php`. Només ha d'aparéixer una vegada, la del `group` del panell.
4. Recorre l'aplicació: portada, detall d'una etiqueta, panell, `/accedir` i `/registre`. Tot ha de funcionar igual que abans, sense haver tocat cap vista.


## 6. Què rep i què retorna un mètode de controlador

Un mètode de controlador és una funció normal: rep una sèrie de valors i en torna un altre. Laravel és qui el crida amb els valors correctes.

### 6.1. Què pot rebre

Hi ha dos tipus de valors:

- **Els paràmetres de la ruta.** Arriben **per ordre**, no pel nom: amb la ruta `/etiquetes/{id}`, el valor de `{id}` entra al primer paràmetre de `show(string $id)`.
- **Dependències, demanades amb el tipus.** Si afegix `Request $request` a la llista de paràmetres, Laravel t'injecta l'objecte de la petició actual. A PHP pur llegies les supervariables (`$_GET`, `$_POST`, `$_SERVER`...); ací tot això ho tens dins d'un únic objecte `Request`.

```php
use Illuminate\Http\Request;

public function show(Request $request, string $id): View
```

Quan hi ha totes dos coses, la documentació recomana posar **primer les dependències i després els paràmetres de la ruta**.

Tres mètodes de `Request` per a començar:

| Mètode | Torna | Equival a PHP pur |
|---|---|---|
| `$request->path()` | La ruta sol, sense domini (`etiquetes/1`) | Part de `$_SERVER['REQUEST_URI']` |
| `$request->method()` | El verb HTTP (`GET`, `POST`...) | `$_SERVER['REQUEST_METHOD']` |
| `$request->query('nom')` | Un valor de la *query string* (o `null`) | `$_GET['nom']` |

Als blocs 5 i 6 farem servir `Request` per a llegir els formularis i les cerques.

### 6.2. Què pot retornar

El que torna el mètode és la resposta que rep el navegador. Els tres retorns que fem servir:

| Retorn | Què fa | Exemple |
|---|---|---|
| `view()` | Pinta una vista Blade i la torna com a HTML | `return view('home', ['labels' => $labels]);` |
| `redirect()->route()` | Redirigix el navegador a una ruta **amb nom** | `return redirect()->route('labels.show', ['id' => 1]);` |
| `abort()` | Para l'execució amb un error HTTP | `abort(404);` |

El `redirect` és l'equivalent de `header('Location: ...')` de la unitat 3 (encapçalaments de resposta), però sense `exit` i amb el nom de la ruta en lloc de la URL. També existix la versió curta `to_route('labels.show', ['id' => 1])`. Ara no el fem servir, però el necessitarem al bloc 5, després de guardar un formulari.

::: warning Atenció
`redirect()->route(...)` **només construïx** la redirecció: cal fer-ne el `return`. Sense `return`, no passa res i la pàgina es queda en blanc o continua el codi. `abort()`, en canvi, para l'execució per si sol.
:::

### 6.3. Comprovar que funciona

Farem una prova temporal a `LabelController`:

1. A `show`, afig `Request $request` com a primer paràmetre (amb el seu `use Illuminate\Http\Request;`) i, com a primera línia del mètode, aquesta:

```php
dd($request->path(), $request->method(), $request->query('prova'));
```

2. Obri <http://localhost/etiquetes/1?prova=hola>. Ha de mostrar tres valors: `"etiquetes/1"`, `"GET"` i `"hola"`.
3. Obri <http://localhost/etiquetes/1>, sense la part `?prova=hola`. El tercer valor ha de ser `null`.
4. **Esborra la línia `dd(...)`, el paràmetre `Request $request` i l'`use`**, i deixa el mètode tal com estava al punt 2. Comprova que <http://localhost/etiquetes/1> torna a mostrar el detall.

## 7. Controladors de recurs

Fins ara cada controlador té una o dues accions. Quan una entitat necessita el cicle complet (llistar, crear, editar, eliminar), Laravel ofereix una convenció que estalvia escriure les rutes una a una: el **controlador de recurs**. Este punt és conceptual: ací no toquem el projecte, però els blocs 5 i 6 s'hi recolzen.

### 7.1. Les set accions d'un CRUD

A la unitat 4 cada acció era un arxiu PHP diferent. A Laravel són mètodes d'un mateix controlador, i per convenció sempre tenen el mateix nom. Per a un recurs `labels`:

| Verb | URI | Acció | Nom de la ruta | Per a què |
|---|---|---|---|---|
| GET | `/labels` | `index` | `labels.index` | Llistat |
| GET | `/labels/create` | `create` | `labels.create` | Formulari de nova etiqueta |
| POST | `/labels` | `store` | `labels.store` | Guardar l'etiqueta nova |
| GET | `/labels/{label}` | `show` | `labels.show` | Veure el detall |
| GET | `/labels/{label}/edit` | `edit` | `labels.edit` | Formulari d'edició |
| PUT/PATCH | `/labels/{label}` | `update` | `labels.update` | Guardar els canvis |
| DELETE | `/labels/{label}` | `destroy` | `labels.destroy` | Eliminar |

Fixa't en dos detalls:

- «Crear» i «editar» són **dos parelles de rutes**: un `GET` que mostra el formulari (`create`, `edit`) i un altre verb que el rep (`store`, `update`).
- `PUT`, `PATCH` i `DELETE` no existixen als formularis HTML. Al bloc 5 vorem com Laravel ho resol.

Ja coneixes dos d'estos noms: l'`index` és el que vam usar al punt 4 per al llistat del panell, i el `show` és el del detall públic del punt 2.

### 7.2. Generar-lo i registrar-lo

Amb un exemple genèric (no ho apliquem a les etiquetes encara), l'opció `--resource` genera un controlador amb un mètode buit per a cada acció:

```bash
sail artisan make:controller PhotoController --resource
```

I **una sola línia** registra les set rutes de la taula, cadascuna amb el seu nom:

```php
Route::resource('photos', PhotoController::class);
```

Pots veure-les amb `sail artisan route:list`.

### 7.3. Només les rutes que necessites

No sempre calen les set. Amb `only` o `except` es limita el conjunt:

```php
Route::resource('photos', PhotoController::class)->only(['index', 'show']);
Route::resource('photos', PhotoController::class)->except(['show']);
```

Al nostre panell, el detall ja el cobrix la ruta pública `labels.show`, així que previsiblement farem servir `->except(['show'])`.

::: info Nota
Per què no l'apliquem ara: encara no tenim `create`, `store`, `edit`, `update` ni `destroy`, i el conjunt té un cost: el nostre panell té **URLs en valencià** (`/panell/etiquetes`) i noms com `panel.labels.index`, mentre que la convenció per defecte els deriva del nom del recurs i en anglés (`/create`, `/edit`). Quan arribem al bloc 5 ho adaptarem: la documentació ofereix `names()`, `parameters()` i `Route::resourceVerbs()` per a això.
:::

::: tip Bona pràctica
Si un controlador necessita mètodes fora d'estes set accions, sol ser senyal que hi ha dos controladors. Mantín-los centrats en un sol recurs.
:::

## 8. Errors comuns i punt de control

### 8.1. Errors comuns i la seua causa

Quan alguna cosa falle, llig el missatge d'error complet i executa `sail artisan route:list`: la major part dels errors d'este bloc són de noms, `use` o namespaces.

| Error o símptoma | Causa probable | Solució |
|---|---|---|
| `Class "App\Support\SampleLabels" not found` | El namespace, la carpeta o el nom de l'arxiu no coincidixen amb la classe | Comprova `namespace App\Support;`, l'arxiu `app/Support/SampleLabels.php` i el nom de la classe (punt 1.2) |
| `Class "App\Http\Controllers\SampleLabels" not found` (o `...\Panel\SampleLabels`) | Falta `use App\Support\SampleLabels;` al controlador | Afig l'`use` a dalt de l'arxiu (punts 2 a 4) |
| `Target class [LabelController] does not exist` (o `[HomeController]`) | Falta l'`use` del controlador a `routes/web.php` | Afig-lo a dalt de l'arxiu (punts 2 i 3) |
| `Target class [Panel\LabelController] does not exist` | Falta `use App\Http\Controllers\Panel;` a les rutes, o el `namespace` de l'arxiu no és `App\Http\Controllers\Panel` | Revisa l'`use` i la primera línia del controlador (punt 4) |
| `Cannot use App\Http\Controllers\Panel\LabelController as LabelController because the name is already in use` | S'ha importat la classe del panell amb `use` i xoca amb el `LabelController` públic | Importa la carpeta (`use App\Http\Controllers\Panel;`) i escriu `Panel\LabelController::class` (punt 4.3) |
| `Invalid route action: [App\Http\Controllers\HomeController]` | La ruta apunta només a la classe, però el controlador no té `__invoke` | Afig `__invoke`, o indica el mètode amb `[LabelController::class, 'show']` (punts 2 i 3) |
| `Method App\Http\Controllers\LabelController::show does not exist` | El nom del mètode de la ruta no coincidix amb el del controlador (error de lletra) | Compara'ls lletra a lletra (punt 2) |
| `Target class [App\Http\Controllers\Request] does not exist` | Falta `use Illuminate\Http\Request;` | Afig l'`use` (punt 6) |
| En obrir una ruta apareix un bolcat de dades en lloc de la pàgina | S'ha oblidat la línia `dd(...)` de la prova | Esborra-la (punt 6.3) |
| Una redirecció no fa res | Falta el `return` davant de `redirect()->route(...)` | Escriu `return redirect()->...` (punt 6.2) |

### 8.2. Llista de comprovació

- [ ] `sail artisan route:list --except-vendor` mostra els sis noms: `home`, `labels.show`, `login`, `register`, `panel.index` i `panel.labels.index`.
- [ ] La portada, el detall (`/etiquetes/1`), el panell, `/accedir` i `/registre` funcionen igual que al bloc 2, i `/etiquetes/99` dona 404.
- [ ] `routes/web.php` només té `use`, rutes i la funció anònima del `group` del panell: cap lògica ni dades.
- [ ] `LabelController` (`show`), `HomeController` (`__invoke`) i `Panel\LabelController` (`index`) existixen i cada ruta apunta al seu.
- [ ] `Panel\LabelController` està a `app/Http/Controllers/Panel/` i el seu namespace és `App\Http\Controllers\Panel`.
- [ ] Només els tres controladors fan servir `SampleLabels`: a `routes/web.php` ja no apareix.
- [ ] No ha quedat cap `dd(...)` ni cap paràmetre `Request` sobrant de la prova del punt 6.
- [ ] No s'ha modificat cap vista en este bloc.

::: tip IA com a tutor
En este bloc la IA és un tutor, no un programador: no li demanes que escriga el codi. Pots demanar-li que t'explique amb altres paraules la diferència entre una ruta i un controlador, que t'ajude a entendre un missatge d'error (enganxa'l complet) o que et pose preguntes per comprovar què has après. Contrasta sempre el que et diga amb la documentació oficial de Laravel 13.x.
:::

### 8.3. Arbre d'arxius esperat

Només els arxius nous o modificats en este bloc:

```text
app/
├── Http/
│   └── Controllers/
│       ├── HomeController.php
│       ├── LabelController.php
│       └── Panel/
│           └── LabelController.php
└── Support/
    └── SampleLabels.php
routes/
└── web.php            (modificat)
```

L'únic arxiu que ha canviat diverses vegades al llarg del bloc és `routes/web.php`; este és el resultat final:

::: details 📄 routes/web.php (versió final del bloc)

```php
<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\LabelController;
use App\Http\Controllers\Panel;
use Illuminate\Support\Facades\Route;

// Part pública
Route::get('/', HomeController::class)->name('home');

Route::get('/etiquetes/{id}', [LabelController::class, 'show'])
    ->whereNumber('id')
    ->name('labels.show');

Route::view('/accedir', 'auth.login')->name('login');
Route::view('/registre', 'auth.register')->name('register');

// Panell
Route::prefix('panell')->name('panel.')->group(function () {
    Route::redirect('/', '/panell/etiquetes')->name('index');

    Route::get('/etiquetes', [Panel\LabelController::class, 'index'])
        ->name('labels.index');
});
```

:::