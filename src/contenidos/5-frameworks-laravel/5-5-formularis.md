# Bloc 5. Formularis

## Temporalització

4 hores

## Índex

| # | Punt |
|---|---|
| 1 | `Route::resource` adaptat al panell | 
| 2 | El formulari de nova etiqueta: Blade, camps, categories i `@csrf` | 
| 3 | Rebre i validar: `validate()`, errors i `old()` |
| 4 | Guardar l'etiqueta: autor provisional i la trampa de l'assignació massiva | 
| 5 | Form Requests |
| 6 | Missatges de validació en valencià (i `APP_LOCALE`) | 
| 7 | Editar i esborrar: `PUT` i `DELETE` en formularis | 
| 8 | Filtre per categoria: formulari `GET`, *scope* i paginació |
| 9 | El formulari de registre: `confirmed` | 
| 10 | Errors comuns i punt de control | 


## 1. `Route::resource` adaptat al panell

Ara mateix el panell només té una ruta: el llistat. Per a crear, editar i esborrar etiquetes en necessitem cinc més, i totes seguixen sempre el mateix patró. Per això Laravel té `Route::resource`, que al bloc 3 vam veure només com a concepte: **una sola línia** que registra les rutes d'un CRUD completat. Ací l'apliquem de veres, adaptat al nostre panell.

### 1.1. Substituir la ruta del llistat

A `routes/web.php`, dins del grup `panell`, **substituïx** la ruta `labels.index` per una sola línia de recurs. El grup queda així:

```php
Route::prefix('panell')->name('panel.')->group(function () {
    Route::redirect('/', '/panell/etiquetes')->name('index');

    Route::resource('etiquetes', Panel\LabelController::class)
        ->except(['show'])
        ->parameters(['etiquetes' => 'label'])
        ->names('labels');
});
```

El recurs per defecte no encaixa del tot amb el nostre projecte, per això porta tres ajustos:

| Crida | Què fa | Per què la necessitem |
|---|---|---|
| `except(['show'])` | No registra la ruta de detall. | El detall és **públic** (`labels.show`, fora del panell). El panell no necessita un segon. |
| `parameters(['etiquetes' => 'label'])` | El paràmetre de les rutes es diu `{label}`. | Per defecte Laravel el deduïx **singularitzant** el nom del recurs, i el singularitzador està pensat per a l'anglés. Ha d'anomenar-se `label` perquè el *binding* implícit casa pel **nom** del paràmetre amb `Label $label`. Es detalla baix.|
| `names('labels')` | Els noms de les rutes seran `panel.labels.*`. | Sense això, el nom eixiria del URI: `panel.etiquetes.*`, i hauríem de canviar tots els `route()` que ja tenim. |

::: info Com funciona `parameters()`

Quan utilitzem `Route::resource`, Laravel genera automàticament les rutes del CRUD. Per defecte, el nom del paràmetre dinàmic es deduïx singularitzant el nom del recurs.

**Sense `parameters()`:**

```php
Route::resource('etiquetes', Panel\LabelController::class);
```

Laravel generaria rutes com:

```text
GET     /panell/etiquetes/{etiquete}/edit
PUT     /panell/etiquetes/{etiquete}
DELETE  /panell/etiquetes/{etiquete}
```

**Amb `parameters()`:**

```php
->parameters(['etiquetes' => 'label'])
```

Li indiquem a Laravel que utilitze `label` com a nom del paràmetre dinàmic:

```text
GET     /panell/etiquetes/{label}/edit
PUT     /panell/etiquetes/{label}
DELETE  /panell/etiquetes/{label}
```

**Important:** Açò no canvia la URL visible per a l'usuari. Per exemple, `/panell/etiquetes/5/edit` continua sent la mateixa. Només canvia el **nom intern del paràmetre**, que ara coincidix amb `Label $label` del controlador i permet que Laravel aplique el *Route Model Binding* implícit.

:::

### 1.2. Les URLs en valencià

Per defecte, `Route::resource` crea les URLs de crear i editar amb verbs en anglés (`create`, `edit`). Per a traduir-los, a `app/Providers/AppServiceProvider.php` afig l'`use` i crida `Route::resourceVerbs()` **al principi** de `boot()`:

```php
use Illuminate\Support\Facades\Route;

public function boot(): void
{
    Route::resourceVerbs([
        'create' => 'nova',
        'edit' => 'editar',
    ]);

    Model::preventLazyLoading(! $this->app->isProduction());
}
```

Ha d'executar-se abans que es registren les rutes; per això va al principi.

### 1.3. Les rutes que has creat

Amb el que hem escrit, Laravel registra estes sis rutes (el nom i el `{label}` ja són els nostres):

| Verb | URL | Acció | Nom |
|---|---|---|---|
| `GET` | `/panell/etiquetes` | `index` | `panel.labels.index` |
| `GET` | `/panell/etiquetes/nova` | `create` | `panel.labels.create` |
| `POST` | `/panell/etiquetes` | `store` | `panel.labels.store` |
| `GET` | `/panell/etiquetes/{label}/editar` | `edit` | `panel.labels.edit` |
| `PUT`/`PATCH` | `/panell/etiquetes/{label}` | `update` | `panel.labels.update` |
| `DELETE` | `/panell/etiquetes/{label}` | `destroy` | `panel.labels.destroy` |

::: info Nota
Fixa't que `update` i `destroy` **compartixen la URL**: només canvia el verb HTTP. Un formulari HTML només sap enviar `GET` i `POST`; com fer arribar un `PUT` o un `DELETE` ho vorem al punt 7.
:::

Com que el nom `panel.labels.index` i la URL `/panell/etiquetes` no han canviat, **no cal tocar cap vista ni cap enllaç**.

### 1.4. Comprovar que funciona

1. Executa `sail artisan route:list --path=panell`. Han d'aparéixer les sis rutes de la taula anterior, amb els noms `panel.labels.*` i les URLs en valencià (`nova`, `editar`), més la redirecció `panel.index`.
2. Obri <http://localhost/panell/etiquetes>: el llistat es veu igual que abans.
3. Obri <http://localhost/panell>: et redirigix a `/panell/etiquetes`.
4. Des de la portada, fes clic a «Les meues etiquetes» de la barra: continua funcionant sense haver modificat cap vista.
5. Obri <http://localhost/panell/etiquetes/nova>: ara **ha de fallar**. No és un 404: la ruta existix, però el controlador encara no té el mètode `create`. El crearem al punt 2.


## 2. El formulari de nova etiqueta

Ja tenim la ruta `panel.labels.create` (`/panell/etiquetes/nova`). Ara hi posem la pàgina: el formulari amb els camps de l'etiqueta. En este punt **només el mostrem**; rebre'l i validar-lo és el punt 3.

### 2.1. El mètode `create()`

El formulari necessita la llista de categories per a un `<select>`. No les escrivim a mà a la vista: les llig el controlador de la base de dades i les passa a la vista.

A `app/Http/Controllers/Panel/LabelController.php`, afig l'`use` i el mètode **dins de la classe**:

```php
use App\Models\Category;

public function create(): View
{
    return view('panel.labels.create', [
        'categories' => Category::orderBy('name')->get(),
    ]);
}
```

L'usuari triarà una categoria pel nom, però el formulari enviarà la seua **clau forana** (`category_id`), que és el que guarda la taula `labels`.

### 2.2. La vista del formulari

Crea `resources/views/panel/labels/create.blade.php`:

```blade
<x-layout>
    <x-slot:title>Nova etiqueta</x-slot:title>

    <h1 class="font-serif text-4xl">Nova etiqueta</h1>

    <form method="POST" action="{{ route('panel.labels.store') }}" class="mt-10 grid gap-6 sm:grid-cols-2">
        @csrf

        <div class="sm:col-span-2">
            <label for="category_id" class="mb-2 block text-sm font-medium">Categoria</label>
            <select id="category_id" name="category_id" class="w-full rounded-2xl border border-ink/10 bg-white px-4 py-3">
                <option value="">Tria una categoria…</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>

        <x-input label="Producte" name="product" />
        <x-input label="Varietat" name="variety" />
        <x-input label="Pes net (g)" name="net_weight_g" type="number" />
        <x-input label="Calibre" name="caliber" />
        <x-input label="Origen" name="origin" />
        <x-input label="Lot" name="batch" />
        <x-input label="Envasador" name="packer" />
        <x-input label="Codi de producte" name="product_code" />
        <x-input label="Codi de barres" name="barcode" inputmode="numeric" />
        <x-input label="Data d'envasat" name="packaged_at" type="date" />

        <div class="sm:col-span-2">
            <label for="description" class="mb-2 block text-sm font-medium">Descripció</label>
            <textarea id="description" name="description" rows="4" class="w-full rounded-2xl border border-ink/10 bg-white px-4 py-3"></textarea>
        </div>

        <div class="flex items-center gap-4 sm:col-span-2">
            <x-button type="submit">Guarda l'etiqueta</x-button>
            <a href="{{ route('panel.labels.index') }}" class="text-sm underline">Cancel·la</a>
        </div>
    </form>
</x-layout>
```

Fixa't en quatre decisions:

- **`method="POST"` i `action` amb `route()`**: com a tots els enllaços del projecte, la URL no s'escriu a mà. Si no poses `method`, el navegador envia el formulari per `GET`.
- **`name` = nom de la columna** (`product`, `net_weight_g`, `category_id`...). Al punt 4 passarem les dades validades directament a `create()`, i això només funciona si els noms coincidixen amb els de `#[Fillable]`.
- **Tipus d'`input`**: `number` per al pes (en grams, enter, com la columna `net_weight_g`) i `date` per a la data. El navegador envia la data com `AAAA-MM-DD`, que és el format que espera la columna `packaged_at`.
- **No hi ha `required`** als camps. Al punt 3 volem vore la validació del **servidor**; la del navegador és una comoditat, no una mesura de seguretat, perquè qualsevol pot enviar una petició sense passar pel formulari.

El formulari **no inclou la foto**: es treballa al bloc 7.

### 2.3. `@csrf`

La directiva `@csrf` genera dins del formulari un camp ocult `_token` amb un valor secret lligat a la sessió de l'usuari. Laravel el comprova en cada petició `POST`, `PUT`, `PATCH` o `DELETE`: així sap que el formulari l'ha servit la nostra aplicació i no una pàgina maliciosa que envia peticions en nom de l'usuari.

::: info Nota
El middleware que ho comprova (`PreventRequestForgery`) ve activat de sèrie al grup `web`. A Laravel 13 fa dos controls: primer mira la capçalera `Sec-Fetch-Site` que envien els navegadors moderns (si diu que la petició ve del mateix origen, l'accepta), i si no pot, **recorre al token**. Per això el posem sempre: és el que protegix quan el primer control no s'aplica.
:::

### 2.4. Enllaçar-lo des del panell

A `resources/views/panel/labels/index.blade.php`, el botó «Nova etiqueta» té `href="#"`. Substituïx-lo per l'enllaç real:

```blade
:href="route('panel.labels.create')"
```

### 2.5. Comprovar que funciona

1. Obri <http://localhost/panell/etiquetes> i fes clic a «Nova etiqueta»: arribes a `/panell/etiquetes/nova` i es veu el formulari amb la categoria, els 10 camps i la descripció.
2. El desplegable de categories ha de mostrar les quatre del seeder, ordenades alfabèticament.
3. El camp «Data d'envasat» ha d'obrir el selector de data del navegador, i «Pes net (g)» no ha d'acceptar lletres.
4. Mira el codi font de la pàgina (`Ctrl+U`): dins del `<form>` ha d'haver un `<input type="hidden" name="_token" ...>` amb un valor llarg.
5. Fes clic a «Cancel·la»: tornes al llistat.
6. Omple el formulari i envia'l: **ha de fallar**, perquè el controlador encara no té el mètode `store`. Que falle ara és normal; ho resolem al punt 3. 


## 3. Rebre i validar: `validate()`, errors i `old()`

Un formulari només és còmode per a qui l'usa bé. **Qualsevol pot enviar a `store` el que vulga**, passe o no pel nostre formulari, així que el servidor ha de comprovar les dades abans de fer-ne res. A la unitat 4 vam escriure a mà les peces de validació en PHP pur ([peces comunes: validació i utilitats](/contenidos/4-acces-a-dades#_10-6-peces-comunes-validacio-i-utilitats)); ací Laravel ho porta fet: tu només declares les **regles**.

### 3.1. `store()` amb `validate()`

A `app/Http/Controllers/Panel/LabelController.php`, afig el mètode **dins de la classe** (`Request` ja estava importat):

```php
public function store(Request $request)
{
    $validated = $request->validate([
        'category_id'  => ['required', 'exists:categories,id'],
        'product'      => ['required', 'string', 'max:255'],
        'variety'      => ['nullable', 'string', 'max:255'],
        'net_weight_g' => ['required', 'integer', 'min:1'],
        'caliber'      => ['nullable', 'string', 'max:255'],
        'origin'       => ['required', 'string', 'max:255'],
        'batch'        => ['required', 'string', 'max:255'],
        'packer'       => ['required', 'string', 'max:255'],
        'product_code' => ['required', 'string', 'max:255'],
        'barcode'      => ['required', 'digits:13'],
        'packaged_at'  => ['required', 'date'],
        'description'  => ['required', 'string'],
    ]);

    dd($validated);
}
```

Així funciona `validate()`:

- Si **totes les regles es complixen**, el codi continua i `$validated` conté les dades que han passat la validació.
- Si **alguna falla**, Laravel llança una excepció i et **redirigix a la pàgina anterior** (el formulari). Duu amb ell els errors i tot el que havia escrit l'usuari, guardats a la sessió només per a esta redirecció.

El `dd($validated)` és provisional: bolca les dades en pantalla perquè vegem què ha passat. Al punt 4 el substituïm per guardar l'etiqueta.

### 3.2. Les regles de cada camp

| Camps | Regles | Per què |
|---|---|---|
| `category_id` | `required`, `exists:categories,id` | Ha d'existir a la taula `categories`: el desplegable es pot manipular. |
| `product`, `origin`, `batch`, `packer`, `product_code` | `required`, `string`, `max:255` | 255 és la longitud que té per defecte una columna `string()` de la migració. |
| `variety`, `caliber` | `nullable`, `string`, `max:255` | Són opcionals. Laravel converteix els camps buits en `null`, i sense `nullable` un `null` es consideraria invàlid. |
| `net_weight_g` | `required`, `integer`, `min:1` | Gramos, un enter positiu. |
| `barcode` | `required`, `digits:13` | Exactament 13 xifres, com la columna. |
| `packaged_at` | `required`, `date` | Una data vàlida (el navegador l'envia com `AAAA-MM-DD`). |
| `description` | `required`, `string` | Text lliure. |

Escrivim les regles com a **arrays** (no amb `|`): són més fàcils de llegir i de combinar amb regles amb objectes.

### 3.3. Mostrar els errors i recuperar el que s'havia escrit

Hi ha dues peces, i les dos viuen als components perquè no les repetim dotze vegades:

- **`@error('camp') ... @enderror`**: el seu contingut només es mostra si el camp té un error, i dins disposes de `$message`. La variable `$errors` la compartix amb totes les vistes el grup de middleware `web`.
- **`old('camp')`**: torna el valor que l'usuari havia enviat en l'intent anterior, o `null` si no n'hi ha. Així el formulari no es queda buit quan falla.

Substituïx `resources/views/components/input.blade.php` (afig el `value` i el missatge d'error):

```blade
@props(['label', 'name', 'type' => 'text'])

<div>
    <label for="{{ $name }}" class="block text-sm font-semibold text-ink">{{ $label }}</label>

    <input
        id="{{ $name }}"
        name="{{ $name }}"
        type="{{ $type }}"
        value="{{ old($name) }}"
        {{ $attributes->merge(['class' => 'mt-2 block w-full rounded-xl border border-ink/20 bg-white px-4 py-3 text-lg text-ink placeholder:text-ink/40 focus:border-brand focus:outline-none focus:ring-2 focus:ring-brand/20']) }}
    >

    @error($name)
        <p class="mt-2 text-sm text-red-700">{{ $message }}</p>
    @enderror
</div>
```

Crea `resources/views/components/select.blade.php`. Les opcions les posa qui l'usa, dins del *slot*:

```blade
@props(['label', 'name'])

<div>
    <label for="{{ $name }}" class="block text-sm font-semibold text-ink">{{ $label }}</label>

    <select
        id="{{ $name }}"
        name="{{ $name }}"
        {{ $attributes->merge(['class' => 'mt-2 block w-full rounded-xl border border-ink/20 bg-white px-4 py-3 text-lg text-ink focus:border-brand focus:outline-none focus:ring-2 focus:ring-brand/20']) }}
    >
        {{ $slot }}
    </select>

    @error($name)
        <p class="mt-2 text-sm text-red-700">{{ $message }}</p>
    @enderror
</div>
```

I `resources/views/components/textarea.blade.php`. Ací el valor antic va **entre les etiquetes** (un `<textarea>` no té atribut `value`) i sense espais, perquè no es colen al contingut:

```blade
@props(['label', 'name'])

<div>
    <label for="{{ $name }}" class="block text-sm font-semibold text-ink">{{ $label }}</label>

    <textarea
        id="{{ $name }}"
        name="{{ $name }}"
        {{ $attributes->merge(['class' => 'mt-2 block w-full rounded-xl border border-ink/20 bg-white px-4 py-3 text-lg text-ink placeholder:text-ink/40 focus:border-brand focus:outline-none focus:ring-2 focus:ring-brand/20']) }}
    >{{ old($name) }}</textarea>

    @error($name)
        <p class="mt-2 text-sm text-red-700">{{ $message }}</p>
    @enderror
</div>
```

Ara, a `resources/views/panel/labels/create.blade.php`, substituïx el bloc del `<select>` i el del `<textarea>` que escriguérem al punt 2:

```blade
<div class="sm:col-span-2">
    <x-select label="Categoria" name="category_id">
        <option value="">Tria una categoria…</option>
        @foreach ($categories as $category)
            <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>{{ $category->name }}</option>
        @endforeach
    </x-select>
</div>
```

```blade
<div class="sm:col-span-2">
    <x-textarea label="Descripció" name="description" rows="4" />
</div>
```

La directiva `@selected(condició)` escriu `selected` si la condició és certa. Fem servir `==` (i no `===`) perquè el valor antic és un **text** (`"2"`) i l'`id` és un **número** (`2`).

::: tip Bona pràctica
Els errors ixen sota cada camp, que és on l'usuari els busca. Per al text d'error fem servir roig (`text-red-700`), una excepció a la paleta de la marca: un error ha de distingir-se bé.
:::

### 3.4. Comprovar que funciona

1. Obri <http://localhost/panell/etiquetes/nova> i envia el formulari **buit**: tornes al mateix formulari, i apareix un error sota cada camp obligatori. `Varietat` i `Calibre` no en donen cap, perquè són opcionals.
2. Omple tots els camps **menys el producte** i envia: l'error només ix al producte, i la resta de camps conserven el que havies escrit, també la categoria triada.
3. Prova valors incorrectes: un codi de barres amb 12 xifres, o un pes de `0`. Cada un ha de donar el seu error.
4. Envia el formulari amb tot correcte: apareix el bolcat de `dd` amb **dotze claus**, sense `_token`, i `variety` i `caliber` a `null` si els has deixat buits. **Encara no es guarda res a la base de dades.**
5. **Experiment CSRF:** lleva temporalment `@csrf` de la vista, envia el formulari correcte i anota què passa. Torna a posar `@csrf` després. Resultat: *(Pendent: ho completaràs tu amb el que veges al teu navegador.)*


## 4. Guardar l'etiqueta: autor provisional i la trampa de l'assignació massiva

Ja sabem rebre i validar les dades. Ara toca **guardar-les**. Hi ha una pregunta que el formulari no respon: **qui és l'autor de l'etiqueta?** A la taula `labels` hi ha un `user_id` obligatori, però no tenim encara usuaris que iniciïn sessió (això és el bloc 6).

### 4.1. L'autor provisional i un primer intent

`user_id` **no ha de vindre del formulari**: no és a `#[Fillable]` i no el posem als camps, perquè qualsevol podria escriure l'`id` d'un altre. Mentre no hi ha autenticació, assignarem **sempre l'usuari de prova** (el primer de la taula `users`, el que crea el *seeder*).

::: warning Atenció
Això és **provisional**. Al bloc 6 el substituirem per l'usuari que ha iniciat sessió.
:::

Este és el codi que et generaria una IA si li demanes «guarda l'etiqueta amb l'usuari de prova». A `app/Http/Controllers/Panel/LabelController.php`, afig l'`use` i **substituïx el cos de `store()`**:

```php
use App\Models\User;

public function store(Request $request)
{
    $request->validate([
        // ...les mateixes regles del punt 3...
    ]);

    Label::create($request->all() + ['user_id' => User::firstOrFail()->id]);

    return to_route('panel.labels.index');
}
```

Fixa't en el que fa:

- `$request->all()` és **tot el que ha enviat el navegador**.
- L'operador `+` entre dos arrays **conserva les claus de l'esquerra**; només afig les que falten. Si l'esquerra ja duu `user_id`, esta guanya.
- `to_route()` redirigix al llistat del panell.

Envia un formulari correcte. **Ha de fallar** amb un error de la base de dades relacionat amb `user_id`.:

```sql
SQLSTATE[HY000]: General error: 1364 Field 'user_id' doesn't have a default value 
```

La raó: `user_id` no és `Fillable`, així que Eloquent l'**ha descartat en silenci** i ha intentat inserir la fila sense autor.

### 4.2. El «remei ràpid»

Davant d'este error, una IA (o algú amb pressa) sol fer el més curt: **desprotegir el model**. A `app/Models/Label.php`, **substituïx** l'atribut `#[Fillable([...])]` per `#[Unguarded]`. Guarda't la llista de camps, que la necessitem després:

```php
use Illuminate\Database\Eloquent\Attributes\Unguarded;

#[Unguarded]
class Label extends Model
```

Esborra també l'`use` de `Fillable`. Hi ha una raó per a **substituir** i no afegir-lo: si deixes els dos atributs, `#[Fillable]` continua manant i `user_id` es descarta igual.

Envia el formulari: ara **guarda l'etiqueta** i et porta al llistat. El problema d'abans ha desaparegut. Però acabes de deixar el model obert.

### 4.3. La trampa: un `user_id` que no has posat tu

El formulari no té cap camp `user_id`, però **el navegador és de qui l'usa**. Comprova el forat:

1. Obri <http://localhost/panell/etiquetes/nova>. Amb les eines de desenvolupador (`F12`), a la pestanya d'elements, fes clic dret sobre el `<form>` → *Edit as HTML*, i afig dins un camp ocult: `<input type="hidden" name="user_id" value="2">`.
2. Omple la resta del formulari amb dades correctes i envia'l.
3. Obri phpMyAdmin (<http://localhost:8080>) i mira l'última fila de `labels`: té `user_id = 2`, **encara que el codi diu que l'autor és l'usuari de prova**.

Ha passat perquè `$request->all()` inclou el `user_id` manipulat, a l'esquerra del `+` guanya, i el model sense protecció l'accepta. Si en lloc d'un `2` hi posa el seu propi `id`, qualsevol podria **firmar etiquetes en nom d'una altra persona**.

::: tip Bona pràctica
Quan una IA et done codi que guarda dades, repassa tres coses: **d'on ve cada valor** que arriba a `create()`, si ha **tocat el model** només perquè «funcione», i si usa `$request->all()`. Que el codi funcione no vol dir que siga segur.
:::

### 4.4. La solució

Hi ha quatre capes, i cada una atura una cosa distinta:

| Capa | Què evita |
|---|---|
| `validate()` | Que arriben dades invàlides. |
| Usar `$validated` en lloc de `$request->all()` | Que arriben camps que **no has validat** (`user_id` no és a les regles, així que no apareix). |
| `#[Fillable]` al model | Que s'assigne un camp que el model no permet. |
| `labels()->create(...)` | Que l'autor vinga d'un camp: l'agafa de l'objecte `User`. |

Primer, **restaura** `app/Models/Label.php`: lleva `#[Unguarded]` i torna a posar la llista de camps:

```php
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable([
    'category_id',
    'product',
    'variety',
    'net_weight_g',
    'caliber',
    'origin',
    'batch',
    'packer',
    'product_code',
    'barcode',
    'packaged_at',
    'description',
    'photo_path',
])]
class Label extends Model
```

Després, a `LabelController`, afig l'`use` i substituïx `store()`:

```php
use Illuminate\Http\RedirectResponse;

public function store(Request $request): RedirectResponse
{
    $validated = $request->validate([
        // ...les mateixes regles del punt 3...
    ]);

    User::firstOrFail()->labels()->create($validated);

    return to_route('panel.labels.index');
}
```

El que abans eren dos mecanismes que fallaven («el model deixa passar tot» i «guarda el que arribe») ara és un únic camí: **dades validades → relació de l'usuari → camps permesos**.

### 4.5. Comprovar que funciona

1. Obri el formulari, afig de nou el camp ocult `user_id` amb valor `2` (com al punt 4.3) i envia amb dades correctes. Al llistat apareix l'etiqueta, i a phpMyAdmin la fila nova té el `user_id` de l'**usuari de prova**, no el `2`. El valor manipulat s'ignora.
2. Obri la portada <http://localhost/>: l'etiqueta nova és la primera de la graella, amb el nom de l'usuari de prova com a autor.
3. Envia una etiqueta deixant buits `Varietat` i `Calibre`: a phpMyAdmin les dos columnes són `NULL`.
4. Envia el formulari amb algun camp incorrecte: continuen eixint els errors del punt 3 i **no es guarda cap fila**.


## 5. Form Requests

Al punt 4 el mètode `store()` fa tres coses: **valida** (amb dotze regles), **guarda** i **redirigix**. I encara ens falta `update()`, que necessitarà les mateixes regles. Laravel té una peça per a traure la validació del controlador: el **Form Request**, una classe pròpia que valida la petició **abans** que arribe al controlador.

### 5.1. Crear el Form Request

```bash
sail artisan make:request LabelRequest
```

Això crea `app/Http/Requests/LabelRequest.php` amb dos mètodes buits: `authorize()` i `rules()`. Substituïx el contingut per:

```php
<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class LabelRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'category_id'  => ['required', 'exists:categories,id'],
            'product'      => ['required', 'string', 'max:255'],
            'variety'      => ['nullable', 'string', 'max:255'],
            'net_weight_g' => ['required', 'integer', 'min:1'],
            'caliber'      => ['nullable', 'string', 'max:255'],
            'origin'       => ['required', 'string', 'max:255'],
            'batch'        => ['required', 'string', 'max:255'],
            'packer'       => ['required', 'string', 'max:255'],
            'product_code' => ['required', 'string', 'max:255'],
            'barcode'      => ['required', 'digits:13'],
            'packaged_at'  => ['required', 'date'],
            'description'  => ['required', 'string'],
        ];
    }
}
```

Dos coses que cal entendre:

- **`rules()`** torna exactament el mateix array que escriguérem al punt 3. Només ha canviat de lloc.
- **`authorize()`** decidix si este usuari pot fer la petició. Laravel el genera amb `return false`, i amb això **qualsevol enviament del formulari dóna un error 403**. Ací el posem a `true`: el panell és públic de moment. La comprovació de qui és el propietari de l'etiqueta és del bloc 6.

Fem **una sola classe per a crear i per a editar**: els camps i les regles són els mateixos. Així al punt 7 no hem de duplicar res.

### 5.2. Usar-lo al controlador

A `app/Http/Controllers/Panel/LabelController.php`, afig l'`use` i substituïx `store()`:

```php
use App\Http\Requests\LabelRequest;

public function store(LabelRequest $request): RedirectResponse
{
    User::firstOrFail()->labels()->create($request->validated());

    return to_route('panel.labels.index');
}
```

El canvi clau és el **tipus del paràmetre**: en comptes de `Request`, ara és `LabelRequest`. Laravel el crea i el valida **abans** de cridar el mètode. Si falla, redirigix al formulari amb els errors i les dades escrites, exactament com feia `validate()`; el controlador ni s'arriba a executar. Si passa, `$request->validated()` conté les dades validades.

Ja no fem servir `Illuminate\Http\Request` en este mètode: pots llevar-ne l'`use`, si encara hi és.

Les vistes i els components **no canvien**: `@error` i `old()` funcionen igual.

::: info Nota
Per a dos o tres regles, `validate()` al controlador va bé. El Form Request compensa quan hi ha **moltes regles**, quan **es reutilitzen** (crear i editar) o quan cal una **autorització** en la mateixa petició, com ens passarà al bloc 6.
:::

### 5.3. Comprovar que funciona

1. Envia el formulari **buit**: apareixen els mateixos errors que abans, sota cada camp obligatori. No hauries de notar cap canvi des del navegador.
2. Envia dades **correctes**: es guarda, et porta al llistat i la fila nova té l'`user_id` de l'usuari de prova.
3. Repeteix el truc del camp ocult `user_id` amb valor `2`: continua ignorant-se.
4. **Experiment:** canvia temporalment `authorize()` a `return false;` i envia el formulari amb dades correctes. Ha d'eixir una pàgina d'error **403** i no es guarda res. Torna a posar `true` després.
5. Obri `LabelController`: `store()` ara té només dues línies de lògica.


## 6. Missatges de validació en valencià (i `APP_LOCALE`)

Ara els errors ixen en anglés i amb el nom tècnic del camp: «The category id field is required.» o «The barcode field must be 13 digits.». Hi ha dos problemes: l'**idioma** i el **nom del camp** (`category id`, no «categoria»). Els dos es resolen al mateix Form Request, amb dos mètodes: `attributes()` i `messages()`. Sense fitxers de traducció.

### 6.1. `attributes()`: el nom de cada camp

Quan un missatge porta el marcador `:attribute`, Laravel el substituïx pel nom del camp. Amb `attributes()` li diem quin nom ha de posar. A `app/Http/Requests/LabelRequest.php`, afig este mètode **dins de la classe**:

```php
public function attributes(): array
{
    return [
        'category_id'  => 'categoria',
        'product'      => 'producte',
        'variety'      => 'varietat',
        'net_weight_g' => 'pes net',
        'caliber'      => 'calibre',
        'origin'       => 'origen',
        'batch'        => 'lot',
        'packer'       => 'envasador',
        'product_code' => 'codi de producte',
        'barcode'      => 'codi de barres',
        'packaged_at'  => "data d'envasat",
        'description'  => 'descripció',
    ];
}
```

### 6.2. `messages()`: un missatge per regla

Ara el text de cada regla. Escrivim **un missatge per regla**, amb els marcadors de Laravel (`:attribute`, `:max`, `:min`, `:digits`), que se substituïxen pel nom del camp i pel valor de la regla:

```php
public function messages(): array
{
    return [
        'required' => 'El camp :attribute és obligatori.',
        'string'   => 'El camp :attribute ha de ser un text.',
        'max'      => 'El camp :attribute no pot tindre més de :max caràcters.',
        'integer'  => 'El camp :attribute ha de ser un nombre enter.',
        'min'      => 'El camp :attribute ha de ser com a mínim :min.',
        'digits'   => 'El camp :attribute ha de tindre :digits xifres.',
        'date'     => 'El camp :attribute ha de ser una data vàlida.',
        'exists'   => 'El valor triat per a :attribute no és vàlid.',
    ];
}
```

::: info Nota
Este esquema funciona perquè només usem `max` en textos i `min` en un número. Si algun dia necessitem un missatge distint per a un camp concret, es posa amb la clau `camp.regla`, per exemple `'product.max'`.
:::

### 6.3. Redactar-los amb ajuda de la IA (i revisar-los)

Escriure dotze nomes i huit missatges és una tasca **acotada i repetitiva**: ideal per a delegar-la en una IA. Un prompt de partida:

```
Tinc un Form Request de Laravel amb estes regles: *(enganxa el contingut de `rules()`)*. 
Escriu en valencià els mètodes `messages()` i `attributes()`. Un missatge per regla, curt i clar, amb els marcadors de Laravel (`:attribute`, `:max`, `:min`, `:digits`). 
No inventes regles que no estiguen a la llista.
```

La revisió **és obligatòria**, i és on es veu el teu criteri:

1. Els marcadors (`:attribute`, `:max`...) han d'aparéixer **tal qual**. Si la IA els tradueix, Laravel no els substituïx i l'usuari veuria el text `:attribute`.
2. Ha d'haver-hi un missatge per a **cada regla** que usem i cap per a regles que no existixen.
3. Ha de ser **valencià**, no castellà amb accent. Revisa'l tu mateix.
4. **Prova'ls**: provoca cada error al formulari i llig el que ix.

### 6.4. I `APP_LOCALE`?

`APP_LOCALE` és l'idioma per defecte de l'aplicació i viu al fitxer `.env`. Posa'l en valencià (el codi d'idioma és `ca`):

```text
APP_LOCALE=ca
```

Però **no traduïx res per si sol**. Laravel no porta missatges en valencià, i el projecte no té carpeta `lang`. Si hi ha un text que no hem escrit nosaltres, Laravel recorre a l'**idioma de reserva** (`APP_FALLBACK_LOCALE`, que és `en`) i eixirà en anglés. Per això escrivim `messages()`: cobrix totes les regles que fem servir. Si afigim una regla nova i no n'escrivim el missatge, eixirà en anglés.

### 6.5. Comprovar que funciona

1. Envia el formulari **buit**: els errors ixen en valencià, com «El camp categoria és obligatori.» o «El camp pes net és obligatori.». Varietat i Calibre no donen cap error.
2. Escriu un codi de barres de 12 xifres: «El camp codi de barres ha de tindre 13 xifres.».
3. Escriu un pes de `0`: «El camp pes net ha de ser com a mínim 1.».
4. Escriu més de 255 caràcters al producte: «El camp producte no pot tindre més de 255 caràcters.».
5. Amb les eines de desenvolupador, canvia el `value` d'una `<option>` de categoria a `999` i envia: «El valor triat per a categoria no és vàlid.».
6. Si trobes algun error que continue en anglés, copia'l: falta el missatge d'eixa regla.


## 7. Editar i esborrar: `PUT` i `DELETE` en formularis

Ja tenim les rutes `edit`, `update` i `destroy` del punt 1. Falta fer-les funcionar. Hi ha dos problemes: el **formulari d'edició és el mateix que el de creació** (no el volem escriure dues vegades), i un formulari HTML **no sap enviar `PUT` ni `DELETE`**.

### 7.1. `PUT` i `DELETE` en un formulari

Un formulari HTML només admet `GET` i `POST`. Per a arribar a les rutes `PUT` i `DELETE`, el formulari s'envia per `POST` i du un camp ocult `_method` amb el verb que volem. Laravel el llig i tracta la petició com si fóra eixe verb. La directiva `@method('PUT')` genera eixe camp:

```blade
<form method="POST" action="...">
    @csrf
    @method('PUT')
</form>
```

Fixa't que `method="POST"` **continua sent `POST`**: el verb real va dins de `_method`. I `@csrf` fa falta igualment.

### 7.2. Un formulari per a dos usos

El formulari de creació i el d'edició tenen els mateixos camps; només canvien l'adreça, el verb i els valors inicials. El convertim en un **component**.

Crea `resources/views/components/label-form.blade.php` (és el formulari del punt 2, amb els canvis):

```blade
@props(['action', 'categories', 'submit', 'label' => null, 'method' => null])

<form method="POST" action="{{ $action }}" class="mt-10 grid gap-6 sm:grid-cols-2">
    @csrf
    @if ($method)
        @method($method)
    @endif

    <div class="sm:col-span-2">
        <x-select label="Categoria" name="category_id">
            <option value="">Tria una categoria…</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" @selected(old('category_id', $label?->category_id) == $category->id)>{{ $category->name }}</option>
            @endforeach
        </x-select>
    </div>

    <x-input label="Producte" name="product" :value="$label?->product" />
    <x-input label="Varietat" name="variety" :value="$label?->variety" />
    <x-input label="Pes net (g)" name="net_weight_g" type="number" :value="$label?->net_weight_g" />
    <x-input label="Calibre" name="caliber" :value="$label?->caliber" />
    <x-input label="Origen" name="origin" :value="$label?->origin" />
    <x-input label="Lot" name="batch" :value="$label?->batch" />
    <x-input label="Envasador" name="packer" :value="$label?->packer" />
    <x-input label="Codi de producte" name="product_code" :value="$label?->product_code" />
    <x-input label="Codi de barres" name="barcode" inputmode="numeric" :value="$label?->barcode" />
    <x-input label="Data d'envasat" name="packaged_at" type="date" :value="$label?->packaged_at->format('Y-m-d')" />

    <div class="sm:col-span-2">
        <x-textarea label="Descripció" name="description" rows="4" :value="$label?->description" />
    </div>

    <div class="flex items-center gap-4 sm:col-span-2">
        <x-button type="submit">{{ $submit }}</x-button>
        <a href="{{ route('panel.labels.index') }}" class="text-sm underline">Cancel·la</a>
    </div>
</form>
```

Tres detalls:

- **`$label?->...`**: l'operador `?->` torna `null` si `$label` és `null`. Al formulari de creació no hi ha etiqueta, i així el mateix codi serveix per als dos casos.
- **`old('category_id', $label?->category_id)`**: el segon argument de `old()` és el valor per defecte. Si l'usuari ha enviat el formulari i ha fallat, guanya el que va escriure; si no, es mostra el que hi ha a la base de dades.
- **La data** es passa com `Y-m-d`: `packaged_at` és una data de PHP (per la conversió del model) i el camp `date` del navegador només entén eixe format.

Ara els components de camp han d'acceptar el valor inicial. A `components/input.blade.php`, canvia la primera línia i la del `value`:

```blade
@props(['label', 'name', 'type' => 'text', 'value' => null])
```

```blade
        value="{{ old($name, $value) }}"
```

I a `components/textarea.blade.php`, igual:

```blade
@props(['label', 'name', 'value' => null])
```

```blade
    >{{ old($name, $value) }}</textarea>
```

### 7.3. Les vistes de crear i d'editar

Les dos vistes queden xicotetes. Substituïx `resources/views/panel/labels/create.blade.php`:

```blade
<x-layout>
    <x-slot:title>Nova etiqueta</x-slot:title>

    <h1 class="font-serif text-4xl">Nova etiqueta</h1>

    <x-label-form :action="route('panel.labels.store')" :categories="$categories" submit="Guarda l'etiqueta" />
</x-layout>
```

I crea `resources/views/panel/labels/edit.blade.php`:

```blade
<x-layout>
    <x-slot:title>Edita l'etiqueta</x-slot:title>

    <h1 class="font-serif text-4xl">Edita l'etiqueta</h1>

    <x-label-form
        :action="route('panel.labels.update', ['label' => $label])"
        method="PUT"
        :label="$label"
        :categories="$categories"
        submit="Guarda els canvis"
    />
</x-layout>
```

### 7.4. Els mètodes del controlador

A `app/Http/Controllers/Panel/LabelController.php`, afig estos tres mètodes **dins de la classe**:

```php
public function edit(Label $label): View
{
    return view('panel.labels.edit', [
        'label' => $label,
        'categories' => Category::orderBy('name')->get(),
    ]);
}

public function update(LabelRequest $request, Label $label): RedirectResponse
{
    $label->update($request->validated());

    return to_route('panel.labels.index');
}

public function destroy(Label $label): RedirectResponse
{
    $label->delete();

    return to_route('panel.labels.index');
}
```

- `Label $label` es resol pel **binding implícit**: el paràmetre de la ruta es diu `{label}` (el punt 1 ho va preparar) i coincidix amb el nom de la variable.
- `update()` rep el mateix `LabelRequest` que `store()`: les regles i els missatges són els mateixos.
- `$label->update(...)` respecta `#[Fillable]`, com `create()`: tampoc pot canviar-se el `user_id`.

### 7.5. Els botons «Editar» i «Esborra»

A `resources/views/panel/labels/index.blade.php`, **substituïx** el bloc del peu de la targeta (el que té «Veure» i «Editar»):

```blade
<x-slot:footer>
    <div class="flex items-center gap-6 font-semibold">
        <a href="{{ route('labels.show', ['label' => $label]) }}" class="text-brand hover:underline">Veure</a>
        <a href="{{ route('panel.labels.edit', ['label' => $label]) }}" class="text-brand hover:underline">Editar</a>

        <form method="POST" action="{{ route('panel.labels.destroy', ['label' => $label]) }}"
              onsubmit="return confirm('Segur que vols esborrar esta etiqueta?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="text-brand hover:underline">Esborra</button>
        </form>
    </div>
</x-slot>
```

Esborrar és un `<form>` i **no un enllaç**: un enllaç és un `GET`, i un `GET` no deu canviar dades (un navegador o un rastrejador podria seguir-lo). El `confirm(...)` és la confirmació mínima abans d'enviar.

::: warning Atenció
De moment **qualsevol pot editar i esborrar qualsevol etiqueta**: el panell és públic i no comprovem de qui és. Ho resolem al bloc 6.
:::

### 7.6. Missatges de confirmació

Quan guardem, editem o esborrem, la pàgina torna al llistat sense dir res: l'usuari no sap si ha anat bé. Laravel té els **missatges *flash***: textos que es guarden a la sessió **només per a la petició següent**. Així el controlador els deixa en redirigir, el llistat els mostra, i desapareixen en recarregar la pàgina.

Primer, un component per a mostrar-los. Crea `resources/views/components/flash.blade.php`:

```blade
@if (session('status'))
    <div class="mb-8 rounded-2xl bg-accent px-6 py-4 font-semibold text-ink">
        {{ session('status') }}
    </div>
@endif
```

Mostra el missatge guardat amb la clau `status`, si n'hi ha. Va amb fons taronja i text negre, com marca el disseny.

A `resources/views/panel/labels/index.blade.php`, afig el component just a sota de la línia `<x-slot:title>`:

```blade
<x-flash />
```

Ara, als tres mètodes de `app/Http/Controllers/Panel/LabelController.php`, canvia la **línia del `return`** perquè deixe el missatge amb `with()`:

```php
// store()
return to_route('panel.labels.index')->with('status', 'Etiqueta guardada correctament.');

// update()
return to_route('panel.labels.index')->with('status', 'Etiqueta actualitzada correctament.');

// destroy()
return to_route('panel.labels.index')->with('status', 'Etiqueta esborrada correctament.');
```

::: tip Bona pràctica
Usa sempre la mateixa clau (`status`) per als missatges d'èxit. Així un únic component serveix per a tota l'aplicació; al bloc 6 el reutilitzarem per a l'inici de sessió.
:::

### 7.7. Comprovar que funciona

1. Obri <http://localhost/panell/etiquetes>: cada targeta mostra «Veure», «Editar» i «Esborra».
2. Fes clic a «Editar» d'una etiqueta: el formulari apareix amb **tots els camps omplits**, inclosa la data, la categoria seleccionada i la descripció.
3. Canvia el producte i guarda: tornes al llistat amb el canvi. A phpMyAdmin continua havent-hi la **mateixa fila** (no n'ha aparegut una de nova).
4. Edita una etiqueta, buida el producte i guarda: apareix l'error en valencià, i el formulari conserva els valors que acabes d'escriure.
5. Mira el codi font del formulari d'edició (`Ctrl+U`): ha de tindre `<input type="hidden" name="_method" value="PUT">` i el `_token`.
6. Fes clic a «Esborra» i cancel·la el diàleg: no passa res. Torna a fer clic i accepta: l'etiqueta desapareix del llistat i de phpMyAdmin.
7. Comprova que **crear** continua funcionant (amb errors i amb `old()`): ara usa el component nou.
8. **Experiment:** lleva temporalment `@method($method)` del component, edita una etiqueta i guarda. Anota què passa i torna'l a posar.
9. Crea una etiqueta: en tornar al llistat apareix a dalt una barra taronja amb «Etiqueta guardada correctament.». **Recarrega la pàgina**: el missatge ha desaparegut.
10. Edita i esborra una etiqueta: cada acció mostra el seu missatge («actualitzada», «esborrada»).
    


## 8. Filtre per categoria: formulari `GET`, *scope* i paginació

La portada mostra totes les etiquetes. Volem que es puga triar una **categoria** i veure només les d'eixa. Per a fer-ho necessitem tres peces: un formulari, una condició reutilitzable al model (un *scope*) i fer que la paginació no oblide el filtre.

### 8.1. Un formulari `GET`

Els formularis dels punts anteriors eren `POST`: canvien dades. Este només **consulta**, i per això usa `GET`: el que tria l'usuari viatja a la URL (`/?category=2`). Així es pot guardar als favorits, enviar a algú o tornar enrere amb el navegador.

::: info Nota
Un formulari `GET` **no porta `@csrf`**: no modifica res, i el testimoni acabaria visible a la URL.
:::

### 8.2. El *scope* a `Label`

Un *scope* local és una condició amb nom que es pot reutilitzar en qualsevol consulta del model. A `app/Models/Label.php`, afig els dos `use` i el mètode **dins de la classe**:

```php
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;

#[Scope]
protected function inCategory(Builder $query, int $categoryId): void
{
    $query->when($categoryId, fn (Builder $query) => $query->where('category_id', $categoryId));
}
```

- L'atribut `#[Scope]` marca el mètode com a *scope*. El primer paràmetre és sempre la consulta (`$query`); els següents són els que passem en cridar-lo.
- Es crida pel nom, **sense `scope`** ni res més: `Label::inCategory(2)`.
- `when($categoryId, ...)` només aplica el `where` **si el valor és veritable**. Amb `0` (sense filtre) no fa res, i així el mateix *scope* serveix per a «totes» i per a «una categoria».

### 8.3. El controlador

Substituïx `app/Http/Controllers/HomeController.php` sencer:

```php
<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Label;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(Request $request): View
    {
        $categoryId = $request->integer('category');

        return view('home', [
            'labels' => Label::with(['user', 'category'])
                ->inCategory($categoryId)
                ->latest('id')
                ->paginate(6)
                ->withQueryString(),
            'categories' => Category::orderBy('name')->get(),
            'categoryId' => $categoryId,
        ]);
    }
}
```

- **`$request->integer('category')`** llig el valor de la URL com a nombre enter. Qualsevol pot escriure el que vulga a la barra d'adreces; si no és un nombre, el filtre simplement no s'aplica.
- El `with(['user', 'category'])` **continua** (no deixem que el *lazy loading* torne).
- **`withQueryString()`** va **després** de `paginate()`. Afig a cada enllaç de pàgina tot el que hi ha a la URL actual (ací `category=2`). Sense això, en passar a la pàgina 2 el filtre es perd.

### 8.4. El formulari a la vista

A `resources/views/home.blade.php`, afig este bloc entre el botó «Crea la teua etiqueta» i la graella:

```blade
<form method="GET" action="{{ route('home') }}" class="mt-14 flex flex-wrap items-end gap-4">
    <x-select label="Filtra per categoria" name="category">
        <option value="">Totes les categories</option>
        @foreach ($categories as $category)
            <option value="{{ $category->id }}" @selected($categoryId == $category->id)>{{ $category->name }}</option>
        @endforeach
    </x-select>

    <x-button type="submit">Filtra</x-button>

    @if ($categoryId)
        <a href="{{ route('home') }}" class="pb-3 text-sm underline">Neteja el filtre</a>
    @endif
</form>
```

I, perquè el missatge de graella buida tinga sentit amb filtre, substituïx el bloc `@empty`:

```blade
@empty
    <p class="text-lg text-ink/60">
        {{ $categoryId ? 'No hi ha etiquetes en esta categoria.' : 'Encara no hi ha cap etiqueta publicada.' }}
    </p>
@endforelse
```

### 8.5. Comprovar que funciona

1. Obri la portada: veus el desplegable «Filtra per categoria» amb «Totes les categories» i el botó «Filtra». La graella és igual que abans.
2. Tria una categoria i fes clic a «Filtra»: la URL és `/?category=N`, només ixen etiquetes d'eixa categoria, el desplegable manté la tria i apareix «Neteja el filtre».
3. Fes clic a «Neteja el filtre»: tornes a vore totes.
4. Prova `/?category=abc`: ixen totes, sense error. Prova `/?category=999`: ix «No hi ha etiquetes en esta categoria.».
5. **Paginació:** si cap categoria té més de 6 etiquetes, canvia temporalment `paginate(6)` per `paginate(2)`. Filtra i fes clic a la pàgina 2: la URL conserva `category=N&page=2` i continua filtrant.
6. **Experiment:** lleva `->withQueryString()` i repeteix el pas 5: la pàgina 2 mostra etiquetes de totes les categories. Torna'l a posar, i torna `paginate(2)` a `paginate(6)`.


## 9. El formulari de registre: `confirmed`

Les maquetes `auth/register` i `auth/login` encara no processen res. Al bloc 6 farem l'autenticació completa (`Auth::attempt`). Ací fem **funcional el registre**: validar el formulari, incloent-hi la contrasenya repetida, i **crear l'usuari**. De moment no iniciarà sessió; això també és del bloc 6.

### 9.1. La regla `confirmed`

Als formularis de registre sempre es demana la contrasenya **dos vegades**, per a evitar errors de teclat. Laravel ho comprova amb la regla `confirmed`, que es posa **al camp principal** (`password`): exigix que existisca un camp `password_confirmation` amb el mateix valor. El nom és sempre el del camp més `_confirmation`, i el formulari ja el du.

### 9.2. El Form Request

```bash
sail artisan make:request RegisterRequest
```

Substituïx el contingut de `app/Http/Requests/RegisterRequest.php`:

```php
<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name'     => 'nom',
            'email'    => 'correu electrònic',
            'password' => 'contrasenya',
        ];
    }

    public function messages(): array
    {
        return [
            'required'           => 'El camp :attribute és obligatori.',
            'string'             => 'El camp :attribute ha de ser un text.',
            'max'                => 'El camp :attribute no pot tindre més de :max caràcters.',
            'email.email'        => 'El correu electrònic no és vàlid.',
            'email.unique'       => 'Ja hi ha un compte amb este correu electrònic.',
            'password.min'       => 'La contrasenya ha de tindre almenys :min caràcters.',
            'password.confirmed' => 'Les dos contrasenyes no coincidixen.',
        ];
    }
}
```

- **`unique:users,email`**: comprova que no hi haja ja un usuari amb eixe correu a la taula `users`.
- **`min:8`**: açò és una regla sobre un **text**, a diferència del `min` del pes net, que era sobre un nombre. Per això ací el missatge va amb la clau **`password.min`** (el cas que vam avançar al punt 6).
- Els missatges `required`, `string` i `max` són els mateixos que a `LabelRequest`. Repetir-los en dos classes és un preu raonable per a mantindre cada Form Request independent.

### 9.3. La ruta i el controlador

La ruta `register` (`GET`) ja existix. Afig la del `POST`, amb la **mateixa URL**; només canvia el verb. A `routes/web.php`, afig l'`use` i la ruta:

```php
use App\Http\Controllers\RegisterController;

Route::post('/registre', [RegisterController::class, 'store'])->name('register.store');
```

Crea el controlador amb `sail artisan make:controller RegisterController`. Deixa'l així (amb `extends Controller`, com els altres):

```php
<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;

class RegisterController extends Controller
{
    public function store(RegisterRequest $request): RedirectResponse
    {
        User::create($request->validated());

        return to_route('login')->with('status', 'Compte creat correctament. Ja pots accedir.');
    }
}
```

No cal fer res amb la contrasenya: el model `User` ja la **xifra automàticament** (el *cast* `hashed`). En PHP pur ho faríem a mà amb `password_hash()`. I com `User` té `#[Fillable(['name', 'email', 'password'])]`, qualsevol altre camp que arribe s'ignora.

### 9.4. La vista

A `resources/views/auth/register.blade.php`, canvia l'`action` del formulari i afig `@csrf`:

```blade
<form method="POST" action="{{ route('register.store') }}" class="mt-10 space-y-6">
    @csrf
```

Perquè es vegi el missatge de «Compte creat», afig `<x-flash />` a `resources/views/auth/login.blade.php`, just a sota de la línia `<x-slot:title>`, com al panell.

::: info Nota
Si envies el formulari amb algun error, **els camps de contrasenya tornen buits**, però el nom i el correu es conserven. Laravel exclou `password` i `password_confirmation` de les dades que guarda per a tornar-te-les a mostrar.
:::

### 9.5. Comprovar que funciona

1. Obri <http://localhost/registre> i envia el formulari **buit**: apareix un error en valencià sota el nom, el correu i la contrasenya.
2. Escriu dos contrasenyes **diferents**: el missatge «Les dos contrasenyes no coincidixen.» ix sota el camp *Contrasenya*. El nom i el correu es conserven; els camps de contrasenya queden buits.
3. Escriu una contrasenya de menys de 8 caràcters: «La contrasenya ha de tindre almenys 8 caràcters.».
4. Escriu el correu `prova@example.com`: «Ja hi ha un compte amb este correu electrònic.».
5. Escriu dades correctes: et porta a `/accedir`, amb la barra taronja «Compte creat correctament. Ja pots accedir.».
6. A phpMyAdmin, mira la taula `users`: hi ha una fila nova, i la columna `password` **no és el text que has escrit**, sinó un valor llarg i il·legible (un *hash*).
7. Observa la barra: continues sense sessió iniciada (apareix «Accedeix»). Això és del bloc 6.


## 10. Errors comuns i punt de control

Este és l'últim punt del bloc. Reunix els errors que et pots trobar, una llista per a comprovar que tot funciona i els fitxers que has creat o modificat.

### 10.1. Errors comuns

**Rutes i controladors**

| Símptoma | Causa | Solució |
|---|---|---|
| Les rutes `panel.labels.*` no es troben (els enllaços fallen) | Falta `->names('labels')` al recurs: els noms ixen com `panel.etiquetes.*` | Afig `->names('labels')` (punt 1) |
| Les URLs de crear i editar acaben en `/create` i `/edit` | `Route::resourceVerbs()` fora de `boot()` o després de registrar les rutes | Posa-la al principi de `boot()` (punt 1) |
| En enviar un formulari correcte ix un error 403 | `authorize()` torna `false`, que és el que genera el comandi | Canvia'l a `true` (punt 5) |

**Formularis i vistes**

| Símptoma | Causa | Solució |
|---|---|---|
| En enviar, es recarrega el llistat i la URL du els camps (`?product=...`) | Falta `method="POST"` al `<form>`: el navegador usa `GET` | Afig `method="POST"` (punt 2) |
| Un camp no arriba al servidor o arriba buit | El `name` no coincidix amb el nom de la columna | Fes-lo coincidir (punt 2) |
| Es guarda el text de l'opció en lloc de l'`id` | `<option>` sense `value` | Afig `value` (punt 2) |
| La categoria triada es perd després d'un error | `@selected` amb `===`: el valor antic és text i l'`id` és un nombre | Usa `==` (punt 3) |
| El `textarea` torna buit o creix amb espais després d'un error | El valor s'ha posat com a atribut `value`, o hi ha espais dins de `<textarea>` | <code v-pre>{{ old(...) }}</code> entre les etiquetes i sense espais (punts 3 i 7) |
| El camp de data de l'edició apareix buit | Falta `->format('Y-m-d')` | Afig-lo (punt 7) |
| El formulari d'edició s'envia com a `GET` | `method="PUT"` a l'etiqueta `<form>` | `method="POST"` més `@method('PUT')` (punt 7) |
| No ix cap missatge de confirmació | Falta `<x-flash />` a la vista, o la clau de `with()` no és `status` | Revisa ambdós (punt 7.6) |

**Validació i missatges**

| Símptoma | Causa | Solució |
|---|---|---|
| Un camp opcional buit dóna error | Falta `nullable` | Afig-lo a les regles (punt 3) |
| Missatges en anglés, com «The category id field is required.» o «The barcode field must be 13 digits.» | Falten `messages()` i `attributes()`; `APP_LOCALE=ca` per si sol no tradueix | Afig-los al Form Request (punt 6) |
| El missatge mostra el text `:atribut` | La IA ha traduït el marcador; Laravel només reconeix `:attribute` | Corregix-lo i revisa'ls tots (punt 6.3) |
| Un error continua en anglés tot i haver escrit el seu missatge | Clau mal escrita a `messages()` | Revisa el nom de la regla (punt 6) |
| La contrasenya diu «com a mínim 8» sense «caràcters» | S'usa el `min` genèric, pensat per a un nombre | Missatge `password.min` (punt 9) |
| `confirmed` falla sempre | El camp de confirmació no es diu `password_confirmation`, o la regla està en el camp equivocat | La regla va a `password` (punt 9) |

**Assignació massiva**

| Símptoma | Causa | Solució |
|---|---|---|
| L'etiqueta es guarda, però sense una dada nova | Camp nou que no és a `#[Fillable]`: es descarta en silenci | Afig-lo a `#[Fillable]` (punt 4) |
| L'autor es pot canviar des del navegador | `$request->all()` amb el model en `#[Unguarded]` | `validated()`, `#[Fillable]` i la relació (punt 4) |
| Has posat `#[Unguarded]` i el model continua filtrant camps | Conviu amb `#[Fillable]`, que mana | Substituïx-lo, no l'afigues (punt 4) |

**Filtre per categoria**

| Símptoma | Causa | Solució |
|---|---|---|
| La pàgina 2 perd el filtre | Falta `withQueryString()` | Afig-lo després de `paginate()` (punt 8) |
| La URL du `?_token=...` | `@csrf` en un formulari `GET` | Lleva'l (punt 8) |

### 10.2. Llista de comprovació

- [ ] `sail artisan route:list --path=panell` mostra les sis rutes del recurs, amb noms `panel.labels.*` i URLs amb `nova` i `editar`.
- [ ] El formulari de nova etiqueta té els camps, el desplegable de categories ordenades i `@csrf`.
- [ ] Un formulari buit mostra errors en valencià sota cada camp obligatori i conserva el que has escrit (`old()`).
- [ ] Guardar crea l'etiqueta amb l'usuari de prova com a autor, i un `user_id` manipulat s'ignora.
- [ ] `Label` té `#[Fillable]` (sense `#[Unguarded]`), i `store()` i `update()` usen `LabelRequest` i `validated()`.
- [ ] Editar mostra les dades precarregades, guarda sobre la mateixa fila i el formulari du `_method` amb `PUT`.
- [ ] Esborrar demana confirmació i elimina la fila (`DELETE`).
- [ ] Guardar, actualitzar i esborrar mostren la barra de confirmació, que desapareix en recarregar.
- [ ] El filtre per categoria funciona a la portada, manté la selecció i la paginació.
- [ ] El registre valida `confirmed`, rebutja un correu repetit, xifra la contrasenya i redirigix a `/accedir`.
- [ ] No hi ha cap enllaç escrit a mà: tots van amb `route()`.
- [ ] La portada, el detall i el panell funcionen sense excepcions de *lazy loading*.

### 10.3. Fitxers del bloc

```text
app/
├── Http/
│   ├── Controllers/
│   │   ├── HomeController.php            (modificat)
│   │   ├── RegisterController.php        (nou)
│   │   └── Panel/
│   │       └── LabelController.php       (modificat)
│   └── Requests/
│       ├── LabelRequest.php              (nou)
│       └── RegisterRequest.php           (nou)
├── Models/
│   └── Label.php                         (modificat)
└── Providers/
    └── AppServiceProvider.php            (modificat)
resources/views/
├── auth/
│   ├── login.blade.php                   (modificat)
│   └── register.blade.php                (modificat)
├── components/
│   ├── flash.blade.php                   (nou)
│   ├── input.blade.php                   (modificat)
│   ├── label-form.blade.php              (nou)
│   ├── select.blade.php                  (nou)
│   └── textarea.blade.php                (nou)
├── home.blade.php                        (modificat)
└── panel/labels/
    ├── create.blade.php                  (nou)
    ├── edit.blade.php                    (nou)
    └── index.blade.php                   (modificat)
routes/web.php                            (modificat)
.env                                      (APP_LOCALE=ca)
```

### 10.4. Fitxers finals

Estos fitxers han canviat en diversos punts. Així han d'quedar al final del bloc.

::: details routes/web.php

```php
<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\LabelController;
use App\Http\Controllers\Panel;
use App\Http\Controllers\RegisterController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');

Route::get('/etiquetes/{label}', [LabelController::class, 'show'])
    ->whereNumber('label')
    ->name('labels.show');

Route::view('/accedir', 'auth.login')->name('login');
Route::view('/registre', 'auth.register')->name('register');
Route::post('/registre', [RegisterController::class, 'store'])->name('register.store');

Route::prefix('panell')->name('panel.')->group(function () {
    Route::redirect('/', '/panell/etiquetes')->name('index');

    Route::resource('etiquetes', Panel\LabelController::class)
        ->except(['show'])
        ->parameters(['etiquetes' => 'label'])
        ->names('labels');
});
```

:::

::: details app/Http/Controllers/Panel/LabelController.php

```php
<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Http\Requests\LabelRequest;
use App\Models\Category;
use App\Models\Label;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class LabelController extends Controller
{
    public function index(): View
    {
        return view('panel.labels.index', [
            'labels' => Label::with('category')->get(),
        ]);
    }

    public function create(): View
    {
        return view('panel.labels.create', [
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function store(LabelRequest $request): RedirectResponse
    {
        // Provisional: al bloc 6 serà l'usuari autenticat.
        User::firstOrFail()->labels()->create($request->validated());

        return to_route('panel.labels.index')->with('status', 'Etiqueta guardada correctament.');
    }

    public function edit(Label $label): View
    {
        return view('panel.labels.edit', [
            'label' => $label,
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function update(LabelRequest $request, Label $label): RedirectResponse
    {
        $label->update($request->validated());

        return to_route('panel.labels.index')->with('status', 'Etiqueta actualitzada correctament.');
    }

    public function destroy(Label $label): RedirectResponse
    {
        $label->delete();

        return to_route('panel.labels.index')->with('status', 'Etiqueta esborrada correctament.');
    }
}
```

:::

::: details app/Models/Label.php

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'category_id',
    'product',
    'variety',
    'net_weight_g',
    'caliber',
    'origin',
    'batch',
    'packer',
    'product_code',
    'barcode',
    'packaged_at',
    'description',
    'photo_path',
])]
class Label extends Model
{
    use HasFactory;

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    #[Scope]
    protected function inCategory(Builder $query, int $categoryId): void
    {
        $query->when($categoryId, fn (Builder $query) => $query->where('category_id', $categoryId));
    }

    protected function casts(): array
    {
        return [
            'packaged_at' => 'date',
        ];
    }
}
```

:::

::: details app/Providers/AppServiceProvider.php (només `boot()` i l'`use` nou)

```php
use Illuminate\Support\Facades\Route;

public function boot(): void
{
    Route::resourceVerbs([
        'create' => 'nova',
        'edit' => 'editar',
    ]);

    Model::preventLazyLoading(! $this->app->isProduction());
}
```

:::

::: details resources/views/components/input.blade.php

```blade
@props(['label', 'name', 'type' => 'text', 'value' => null])

<div>
    <label for="{{ $name }}" class="block text-sm font-semibold text-ink">{{ $label }}</label>

    <input
        id="{{ $name }}"
        name="{{ $name }}"
        type="{{ $type }}"
        value="{{ old($name, $value) }}"
        {{ $attributes->merge(['class' => 'mt-2 block w-full rounded-xl border border-ink/20 bg-white px-4 py-3 text-lg text-ink placeholder:text-ink/40 focus:border-brand focus:outline-none focus:ring-2 focus:ring-brand/20']) }}
    >

    @error($name)
        <p class="mt-2 text-sm text-red-700">{{ $message }}</p>
    @enderror
</div>
```

:::

::: details resources/views/components/textarea.blade.php

```blade
@props(['label', 'name', 'value' => null])

<div>
    <label for="{{ $name }}" class="block text-sm font-semibold text-ink">{{ $label }}</label>

    <textarea
        id="{{ $name }}"
        name="{{ $name }}"
        {{ $attributes->merge(['class' => 'mt-2 block w-full rounded-xl border border-ink/20 bg-white px-4 py-3 text-lg text-ink placeholder:text-ink/40 focus:border-brand focus:outline-none focus:ring-2 focus:ring-brand/20']) }}
    >{{ old($name, $value) }}</textarea>

    @error($name)
        <p class="mt-2 text-sm text-red-700">{{ $message }}</p>
    @enderror
</div>
```

:::

::: details resources/views/panel/labels/index.blade.php

```blade
<x-layout>
    <x-slot:title>Les meues etiquetes</x-slot>

    <x-flash />

    <div class="flex flex-wrap items-end justify-between gap-6">
        <div>
            <h1 class="font-serif text-5xl font-semibold tracking-tight text-ink">Les meues etiquetes</h1>
            <div class="mt-6 h-1 w-16 rounded-full bg-accent"></div>
        </div>

        <x-button :href="route('panel.labels.create')">Nova etiqueta</x-button>
    </div>

    <div class="mt-14 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
        @forelse ($labels as $label)
            <x-label-card :label="$label">
                <x-slot:footer>
                    <div class="flex items-center gap-6 font-semibold">
                        <a href="{{ route('labels.show', ['label' => $label]) }}" class="text-brand hover:underline">Veure</a>
                        <a href="{{ route('panel.labels.edit', ['label' => $label]) }}" class="text-brand hover:underline">Editar</a>

                        <form method="POST" action="{{ route('panel.labels.destroy', ['label' => $label]) }}"
                              onsubmit="return confirm('Segur que vols esborrar esta etiqueta?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-brand hover:underline">Esborra</button>
                        </form>
                    </div>
                </x-slot>
            </x-label-card>
        @empty
            <p class="text-lg text-ink/60">Encara no has creat cap etiqueta.</p>
        @endforelse
    </div>
</x-layout>
```

:::