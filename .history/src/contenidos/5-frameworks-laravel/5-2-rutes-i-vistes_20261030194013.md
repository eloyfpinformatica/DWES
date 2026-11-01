# Bloc 2. Rutes i vistes amb components Blade

## 1. MVC i el cicle de vida d'una petició

Al bloc anterior vam vore que **tota** petició entra per `public/index.php`. Ara toca saber què passa després i on viu cada cosa, perquè durant tota la unitat afegirem peces noves i és important saber en quin lloc va cadascuna.

### 1.1. Del «un fitxer per URL» al punt d'entrada únic

A la [unitat 4](/contenidos/4-acces-a-dades#_3-1-l-estructura-nova), cada URL era un arxiu PHP: Apache trobava `leads.php` pel seu nom i l'executava. A Laravel no hi ha cap arxiu per pàgina:

| Unitat 4                                     | Laravel                                                          |
|----------------------------------------------|------------------------------------------------------------------|
| La URL `/dashboard/leads.php` és un arxiu    | La URL `/panell/etiquetes` **no correspon a cap arxiu**          |
| Apache executa l'arxiu que coincidix amb la URL | Totes les peticions arriben a `public/index.php`              |
| El nom de l'arxiu decidix què es mostra      | Les **rutes** (`routes/web.php`) decidixen què es mostra         |

Una conseqüència pràctica: les URL ja no acaben en `.php` i les escrivim com vulguem.

### 1.2. MVC a Laravel

Laravel organitza el codi amb el patró **MVC** (Model-Vista-Controlador), que ja vam apuntar a la unitat 4. Allí, però, el «controlador» eren les línies de PHP que hi havia al principi de cada pàgina (`leads.php`, `editar.php`...), barrejades amb l'HTML. Laravel separa les peces:

| Peça            | Què fa                                                 | Unitat 4                                                                                                         | Laravel                                | Bloc |
|-----------------|--------------------------------------------------------|------------------------------------------------------------------------------------------------------------------|----------------------------------------|------|
| **Rutes**       | Decidixen quin codi respon a cada URL                  | El nom de l'arxiu                                                                                                | `routes/web.php`                       | 2    |
| **Vista**       | L'HTML que veu l'usuari                                | La carpeta `vistes/` i l'HTML de cada pàgina ([punt 10.2](/contenidos/4-acces-a-dades#_10-2-reutilitzar-la-capcalera-i-el-peu-del-panell)) | `resources/views` (Blade)              | 2    |
| **Controlador** | Rep la petició, ho prepara tot i tria la vista         | El PHP del principi de cada pàgina                                                                               | `app/Http/Controllers`                 | 3    |
| **Model**       | Les dades i com guardar-les                            | `Lead` i `LeadDAO` ([punt 8](/contenidos/4-acces-a-dades#_8-els-dao-leaddao-i-usuaridao))                        | `app/Models` (Eloquent)                | 4    |

Les rutes no són una lletra de MVC, però són el que ho connecta tot: cada ruta apunta a un controlador (o, de moment, a una funció) i este tria la vista.

::: info Nota
Este bloc cobrix **rutes i vistes**. Fins al bloc 3 no hi haurà controladors, i fins al 4 no hi haurà models ni base de dades: les etiquetes seran dades de mostra escrites a mà.
:::

### 1.3. Recorregut d'una petició

Quan l'usuari escriu una URL en el navegador, esta és la seqüència fins que veu la pàgina:

![u5_peticionURL](../media/5_peticionURL.png)
Pas a pas:

1. **`public/index.php`** carrega l'autoload de Composer (com a la unitat 4) i obté l'aplicació de `bootstrap/app.php`.
2. La petició passa al **nucli HTTP** (*HTTP kernel*), que arranca el framework: carrega la configuració i els **proveïdors de serveis** (*service providers*), que preparen les peces de Laravel.
3. El nucli fa passar la petició pels **middleware**: filtres que s'executen abans i després de la ruta. Al bloc 6 en veurem un, `auth`, que deixa passar només els usuaris identificats.
4. El **router** busca a `routes/web.php` la ruta que coincidix amb la URL i el mètode (`GET`, `POST`...).
5. S'executa el codi de la ruta, que torna una **vista**, i Laravel la converteix en la resposta HTML.
6. La resposta torna al navegador.

::: tip Bona pràctica
No cal que memoritzes tots els passos. Per a este bloc en tens prou amb dos: la petició **arriba al router**, i el router **torna una vista**. Tota la resta (nucli, proveïdors...) funciona soleta i no la toquem.
:::

## 2. Rutes bàsiques

Al bloc anterior vam crear i esborrar la ruta `/hola` sense aturar-nos a mirar-la. Ara la veiem amb calma: una **ruta** lliga una URL amb el codi que ha de respondre, i substituïx el «un arxiu per pàgina» de la unitat 4.

### 2.1. `routes/web.php` i `Route::get()`

Totes les rutes de la part web viuen a `routes/web.php`. Laravel les assigna al grup de middleware `web`, que li dona a l'aplicació sessions i protecció CSRF (les veurem als blocs 5 i 6). Obri l'arxiu: ja porta el `use Illuminate\Support\Facades\Route;` a dalt i la ruta de benvinguda que vam vore al bloc 1.

Afig esta ruta al final:

```php
Route::get('/prova', function () {
    return 'Açò és una ruta de prova';
});
```

Una ruta té dues parts:

- **El mètode i la URL**: `Route::get('/prova', ...)` respon a peticions `GET` a `/prova`.
- **El codi que s'executa**: una funció anònima. El que torna (ací, un text) és la resposta que rep el navegador.

Laravel té un mètode per a cada verb HTTP:

| Mètode                                          | Quan s'usa                                                              |
|-------------------------------------------------|-------------------------------------------------------------------------|
| `Route::get()`                                  | Mostrar una pàgina (llistat, detall, formulari buit)                    |
| `Route::post()`                                 | Rebre un formulari per a crear alguna cosa                              |
| `Route::put()` / `Route::patch()`               | Actualitzar                                                             |
| `Route::delete()`                               | Eliminar                                                                |

A la unitat 4 només vam usar `GET` i `POST`, perquè un formulari HTML no sap enviar-ne més. Quan arribem als formularis (bloc 5) vorem com Laravel ho resol amb un camp ocult `_method`.

::: info Nota
Al bloc 3 les funcions anònimes passaran a **controladors**. Ara les usem perquè són el camí més curt per a vore una ruta funcionant.
:::

### 2.2. `Route::view()`: pàgines sense lògica

Si una ruta només ha de mostrar una vista, sense dades ni codi, no cal escriure la funció. `Route::view()` rep la URL i el nom de la vista:

```php
Route::view('/benvinguda', 'welcome');
```

Això és el mateix que:

```php
Route::get('/benvinguda', function () {
    return view('welcome');
});
```

Si la vista necessita dades, es poden passar com a tercer paràmetre, en un array: `Route::view('/benvinguda', 'welcome', ['nom' => 'Laravel'])`. Quan hi ha més lògica, es torna a la funció anònima.

::: tip Bona pràctica
`Route::view()` va molt bé per a pàgines estàtiques (per exemple, «Sobre nosaltres» o «Condicions d'ús»). Si la pàgina ha de preparar dades, usa `Route::get()`.
:::

### 2.3. Veure les rutes amb `route:list`

Quan l'aplicació tinga moltes rutes, necessitarem un resum. Ja has usat esta ordre al bloc 1:

```bash
sail artisan route:list
```

Per a cada ruta mostra el mètode, la URL i què s'executa. Afegint `-v` mostra també els middleware de cada ruta:

```bash
sail artisan route:list -v
```

A més de les teues, veuràs alguna ruta que Laravel registra per ell mateix, com `up`: és una ruta de comprovació de salut de l'aplicació, definida a `bootstrap/app.php`.

::: tip Bona pràctica
Si una URL no et funciona, el primer que has de fer és `sail artisan route:list`: comprova que la ruta existix i que el mètode (`GET`, `POST`...) és el que esperes.
:::

### 2.4. Comprovar que funciona

1. Obri <http://localhost/prova>. Ha d'aparéixer el text **«Açò és una ruta de prova»**, sense cap HTML al voltant.
2. Obri <http://localhost/benvinguda>. Ha d'aparéixer la mateixa pàgina de benvinguda de Laravel que en <http://localhost>.
3. Executa `sail artisan route:list`. Han d'aparéixer les rutes `/`, `prova` i `benvinguda`, totes amb mètode `GET`.
4. Canvia el text de la ruta `/prova`, guarda i recarrega el navegador: el canvi es veu al moment.
5. Obri <http://localhost/no-existix>. Ha d'eixir un error **404**, perquè cap ruta coincidix amb la URL.
6. **Esborra les dos rutes de prova** (`/prova` i `/benvinguda`) de `routes/web.php`, per a deixar només la ruta `/` original. Al punt 3 en crearem de noves amb vistes pròpies.
## 3. Vistes Blade

A la unitat 4, les pàgines barrejaven PHP i HTML (`<?= e($lead->getNom()) ?>`, `foreach`, `endforeach`). **Blade** és el motor de plantilles de Laravel: escrius HTML amb unes poques directives, i Laravel el compila a PHP i el guarda en caché fins que canvies l'arxiu. Així, les vistes continuen sent HTML llegible.

### 3.1. `resources/views` i `view()`

Les vistes viuen a `resources/views` i els seus arxius acaben en `.blade.php`. La funció `view()` les troba pel nom, sense l'extensió; les subcarpetes s'escriuen amb **punts**:

| `view(...)`            | Arxiu                                    |
|------------------------|------------------------------------------|
| `view('home')`         | `resources/views/home.blade.php`         |
| `view('labels.show')`  | `resources/views/labels/show.blade.php`  |

### 3.2. Passar dades a la vista

Encara no tenim base de dades (la veurem al bloc 4), així que les etiquetes seran **dades de mostra** escrites a mà. Són provisionals: al bloc 3 passaran a un controlador i al 4 les substituirà Eloquent.

Obri `routes/web.php` i **substituïx** la ruta `/` (la de benvinguda) per este codi:

```php
$labels = [
    [
        'id' => 1,
        'product' => 'Taronja',
        'variety' => 'Navelina',
        'category' => 'Fruites',
        'author' => 'Marta Ferrer',
        'date' => '12/10/2026',
        'description' => 'Taronges collides a l\'arbre, dolces i amb molt de suc.',
    ],
    [
        'id' => 2,
        'product' => 'Carxofa',
        'variety' => 'Blanca de Tudela',
        'category' => 'Verdures',
        'author' => 'Pau Climent',
        'date' => '08/10/2026',
        'description' => 'Carxofes tendres, <em>ideals per a l\'arròs al forn</em>.',
    ],
    [
        'id' => 3,
        'product' => 'Ametla',
        'variety' => null,
        'category' => 'Fruits secs',
        'author' => 'Laia Sanchis',
        'date' => '03/10/2026',
        'description' => 'Ametles cruades amb pell, envasades en lots xicotets.',
    ],
];

Route::get('/', function () use ($labels) {
    return view('home', ['labels' => $labels]);
});
```

- El segon paràmetre de `view()` és un array: la clau `'labels'` arribarà a la vista com la variable `$labels`.
- **`use ($labels)`** és necessari perquè una funció anònima de PHP no veu les variables de fora. Sense això, `$labels` seria una variable no definida dins de la ruta.
- Les dates són text de moment. Al bloc 4 seran dates reals.

::: info Nota
Observa que la segona etiqueta porta HTML dins de la descripció (`<em>`) i que la tercera no té varietat (`null`). Els usarem a propòsit en els apartats següents.
:::

### 3.3. Mostrar dades amb <code v-pre>{{ }}</code>

Crea l'arxiu `resources/views/home.blade.php`:

```blade
<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-stone-50 text-stone-800 antialiased">
    <main class="mx-auto max-w-5xl px-6 py-16">
        <h1 class="text-5xl font-semibold tracking-tight text-stone-900">Etiquetes</h1>
        <p class="mt-4 text-xl leading-relaxed text-stone-600">Les últimes etiquetes publicades.</p>

        <div class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @forelse ($labels as $label)
                <article class="rounded-2xl border border-stone-200 bg-white p-6">
                    <p class="text-sm font-medium uppercase tracking-wide text-stone-500">{{ $label['category'] }}</p>
                    <h2 class="mt-3 text-2xl font-semibold text-stone-900">{{ $label['product'] }}</h2>

                    @if ($label['variety'])
                        <p class="text-lg text-stone-600">{{ $label['variety'] }}</p>
                    @endif

                    <p class="mt-4 leading-relaxed text-stone-600">{{ $label['description'] }}</p>
                    <p class="mt-6 text-sm text-stone-500">{{ $label['author'] }} · {{ $label['date'] }}</p>
                </article>
            @empty
                <p class="text-lg text-stone-500">Encara no hi ha cap etiqueta publicada.</p>
            @endforelse
        </div>
    </main>
</body>
</html>
```

Esta pàgina porta el `<head>` complet perquè encara no tenim layout. Al punt 5 el passarem a un component i la vista quedarà només amb el contingut.

Les claus dobles <code v-pre>{{ }}</code> imprimixen el valor d'una variable, i **escapen** l'HTML automàticament: és el mateix que feies amb `e()` a la unitat 4, però sense escriure-ho.

| Unitat 4                                                  | Blade                                                  |
|-----------------------------------------------------------|--------------------------------------------------------|
| `<?= e($lead->getNom()) ?>`                               | <code v-pre>{{ $label['product'] }}</code>            |
| `<?php foreach ($llista as $lead): ?> ... <?php endforeach; ?>` | `@foreach ($labels as $label) ... @endforeach`   |
| `<?php if ($x): ?> ... <?php endif; ?>`                   | `@if ($x) ... @endif`                                  |

::: danger Error crític
Hi ha una altra sintaxi, <code v-pre>{!! $x !!}</code>, que **no escapa** res: imprimix l'HTML tal qual. Usa-la només amb contingut teu, mai amb text escrit per un usuari: un `<script>` dins de la descripció s'executaria al navegador de tothom (XSS). La documentació oficial recomana la sintaxi escapada per a qualsevol dada que vinga de l'usuari.
:::

### 3.4. Directives: `@if` i `@forelse`

Les directives són les paraules que comencen per `@`. Les que hem usat:

| Directiva                        | Què fa                                                                                  |
|----------------------------------|-----------------------------------------------------------------------------------------|
| `@if` ... `@endif`               | Mostra un bloc només si la condició és certa. També admet `@elseif` i `@else`.          |
| `@foreach` ... `@endforeach`     | Repetix un bloc per cada element d'un array.                                            |
| `@forelse` ... `@empty` ... `@endforelse` | Com `@foreach`, però amb un bloc alternatiu quan l'array està buit.            |

Fixa't en els dos casos de la vista:

- **`@if ($label['variety'])`** evita pintar un paràgraf buit en l'etiqueta que no té varietat (`null`).
- **`@forelse`** estalvia haver d'escriure un `if (count(...) === 0)` a part: el bloc `@empty` es mostra només si no hi ha etiquetes.

Blade no et limita a les directives: pots escriure PHP normal si cal, però en una vista és millor mantindre'l al mínim.

### 3.5. Comprovar que funciona

1. Comprova que Vite està en marxa (`sail npm run dev`) i obri <http://localhost>. Ha d'aparéixer el títol **«Etiquetes»** i **tres targetes**: ja no es veu la pàgina de benvinguda de Laravel.
2. La tercera targeta (Ametla) **no mostra línia de varietat**, gràcies a l'`@if`.
3. A la segona targeta (Carxofa), el text mostra literalment les etiquetes `<em>...</em>`: <code v-pre>{{ }}</code> ha escapat l'HTML.
4. Prova ara el perill. A `home.blade.php`, canvia <code v-pre>{{ $label['description'] }}</code> per <code v-pre>{!! $label['description'] !!}</code> i recarrega: el text apareix en cursiva i les etiquetes han desaparegut, perquè l'HTML s'ha interpretat. Imagina que en lloc de `<em>` fóra un `<script>`. **Torna-ho a deixar amb** <code v-pre>{{ }}</code>.
5. Canvia temporalment `$labels = [ ... ];` per `$labels = [];` a `routes/web.php`: ha d'eixir el missatge **«Encara no hi ha cap etiqueta publicada»** (el bloc `@empty`). Restaura l'array.
6. Escriu una clau que no existix, per exemple <code v-pre>{{ $label['titol'] }}</code>: Laravel mostra la pàgina d'error amb `Undefined array key "titol"` i la línia exacta. Corregix-ho.
## 4. Components Blade anònims

A la unitat 4 reutilitzàvem trossos d'HTML amb `capcalera.php` i `peu.php`, que s'incloïen amb `require_once` i no podien rebre paràmetres. Un **component** de Blade és el mateix, però amb **paràmetres, contingut interior i etiqueta pròpia**: `<x-badge>Fruites</x-badge>`.

Hi ha dos tipus de components. Nosaltres usarem els **anònims**: un únic arxiu `.blade.php` dins de `resources/views/components`, sense cap classe PHP. Laravel els descobrix automàticament, sense registrar res.

### 4.1. El sistema de disseny: paleta i tipografia

Abans de crear components, decidim com s'ha de veure l'aplicació, perquè cada component portarà ja el disseny incorporat. Així, si algun dia canvies un color, el canvi arriba a tota l'aplicació.


| Nom (`--color-*`) | Valor     | Ús                                                         | Classes de Tailwind       |
|-------------------|-----------|------------------------------------------------------------|---------------------------|
| `brand`           | `#005b97` | Color principal: botons, enllaços, accions                 | `bg-brand`, `text-brand`  |
| `brand-dark`      | `#004474` | El mateix blau, més fosc, per a quan passes el ratolí      | `hover:bg-brand-dark`     |
| `accent`          | `#fbad18` | Detalls i insígnies destacades                             | `bg-accent`               |
| `ink`             | `#111111` | Text (quasi negre)                                         | `text-ink`, `text-ink/60` |

Obri `resources/css/app.css`. Tailwind 4 es configura amb CSS: tot el que declares dins de `@theme` es converteix en classes. Afig este bloc al final de l'arxiu (si ja n'hi ha un `@theme`, posa-hi les quatre línies dins):

```css
@theme {
    --color-brand: #005b97;
    --color-brand-dark: #004474;
    --color-accent: #fbad18;
    --color-ink: #111111;
}
```

Cada variable `--color-xxx` crea totes les utilitats: `bg-brand`, `text-brand`, `border-brand`... També admet transparència: `text-ink/60` és el text negre al 60 % (el que usarem per al text secundari) i `bg-brand/10` és un fons blau molt suau.

Per a la tipografia no cal configurar res: usarem **`font-serif`** per als títols i la tipografia per defecte per al cos del text. Són fonts del sistema, així que no depenem de cap servei extern.

Regles que seguirem en tota l'aplicació:

| Element        | Regla                                                                                      |
|----------------|--------------------------------------------------------------------------------------------|
| Fons           | Blanc (`bg-white`)                                                                         |
| Títols         | `font-serif`, molt grans, `font-semibold tracking-tight`, `text-ink`                       |
| Text           | `text-ink`; el secundari, `text-ink/60` o `text-ink/70`                                    |
| Targetes       | `rounded-2xl border border-ink/10 bg-white`, **sense ombres**                              |
| Botons         | Blau (`bg-brand`), text blanc, vora completament arrodonida (`rounded-full`)               |
| Taronja        | Només com a **fons amb text negre** (insígnies, un filet decoratiu). Mai com a color de text |


**Comprovar-ho.** Amb Vite en marxa (`sail npm run dev`), obri `resources/views/home.blade.php` i canvia les classes del `<h1>` per `font-serif text-5xl font-semibold tracking-tight text-brand`. En recarregar <http://localhost>, el títol ha de veure's amb **serif i en blau**. Este canvi el reescriurem al punt 4.6.

### 4.2. El primer component: `<x-badge>` i `@props`

Una **insígnia** (*badge*) és l'etiqueta xicoteta que mostrarà la categoria («Fruites», «Verdures»...). Crea `resources/views/components/badge.blade.php`:

```blade
@props(['tone' => 'brand'])

<span {{ $attributes->class([
    'inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold uppercase tracking-wide',
    'bg-brand/10 text-brand' => $tone === 'brand',
    'bg-accent text-ink' => $tone === 'accent',
]) }}>
    {{ $slot }}
</span>
```

El nom de l'arxiu és el de l'etiqueta: `badge.blade.php` es crida amb `<x-badge>`. Les subcarpetes s'escriuen amb punts: `components/forms/input.blade.php` seria `<x-forms.input>`.

```blade
<x-badge>Fruites</x-badge>
<x-badge tone="accent">Nova</x-badge>
```

Les peces del component:

- **`@props(['tone' => 'brand'])`** declara les dades que el component espera. `tone` és un paràmetre amb el valor per defecte `'brand'`: si no l'indiques, serà blau. Dins del component és la variable `$tone`.
- **<code v-pre>{{ $slot }}</code>** és el contingut que escrius entre l'etiqueta d'obertura i la de tancament («Fruites»). El vorem a fons al 4.3.
- **`$attributes->class([...])`** afig classes segons una condició: la clau és la classe i el valor és la condició. Ací la insígnia és blava si `$tone` és `'brand'` i taronja si és `'accent'`. Les classes sense clau sempre s'apliquen.

Un detall important: com passar els valors.

| Així                          | Què passa                                                                  |
|-------------------------------|----------------------------------------------------------------------------|
| `tone="accent"`               | Es passa el **text** `accent`                                              |
| `:tone="$estat"`              | Amb els dos punts, el valor és una **expressió PHP** (una variable)        |

Per això, per a passar una dada de la vista escriurem `:label="$label"` i no `label="$label"`.

### 4.3. Slots: `<x-label-card>`

La targeta d'una etiqueta es repetix a la portada, i també al panell privat (punt 7). Però **no tot és igual**: a la portada, a la part de baix es mostra l'autor i la data, i al panell hi aniran els botons d'editar i eliminar. Una zona que canvia segons on s'usa és un **slot amb nom**.

Crea `resources/views/components/label-card.blade.php`:

```blade
@props(['label'])

<article class="flex h-full flex-col rounded-2xl border border-ink/10 bg-white p-6">
    <div class="flex-1">
        <x-badge>{{ $label['category'] }}</x-badge>

        <h2 class="mt-4 font-serif text-3xl font-semibold tracking-tight text-ink">{{ $label['product'] }}</h2>

        @if ($label['variety'])
            <p class="text-lg text-ink/60">{{ $label['variety'] }}</p>
        @endif

        <p class="mt-4 leading-relaxed text-ink/70">{{ $label['description'] }}</p>
    </div>

    @isset($footer)
        <footer class="mt-6 border-t border-ink/10 pt-4 text-sm text-ink/60">
            {{ $footer }}
        </footer>
    @endisset
</article>
```

I s'usa així:

```blade
<x-label-card :label="$label">
    <x-slot:footer>
        {{ $label['author'] }} · {{ $label['date'] }}
    </x-slot>
</x-label-card>
```

- **Slot per defecte (`$slot`)**: tot el que no va dins d'un `<x-slot>`. Ja l'has usat a la insígnia.
- **Slot amb nom**: `<x-slot:footer>` ... `</x-slot>` omplix la variable `$footer`.
- **`@isset($footer)`**: el slot és opcional. Si qui usa el component no n'indica cap, no es pinta el peu.
- Un component pot usar-ne un altre: la targeta utilitza `<x-badge>` per a la categoria.

### 4.4. `$attributes->merge()`: afegir classes des de fora

Qualsevol atribut que escrigues en l'etiqueta i que **no** estiga declarat a `@props` va a una «bossa d'atributs»: la variable `$attributes`. Imprimint-la al lloc adequat, l'atribut arriba a l'HTML. Així no cal declarar un paràmetre per a cada `class`, `id` o `href`.

Fem que la targeta accepte classes noves. Canvia l'etiqueta d'obertura de `label-card.blade.php`:

```blade
<article {{ $attributes->merge(['class' => 'flex h-full flex-col rounded-2xl border border-ink/10 bg-white p-6']) }}>
```

Ara `<x-label-card class="transition hover:border-brand">` afegirà eixes classes a les que ja portava. Amb `class`, `merge()` **suma**; amb qualsevol altre atribut, el valor de `merge()` és el **per defecte** i el que arriba de fora el substituïx.

Ho vegem amb un botó. Crea `resources/views/components/button.blade.php`:

```blade
@props(['href' => null])

@php
    $classes = 'inline-flex items-center justify-center rounded-full bg-brand px-6 py-3 text-base font-semibold text-white transition hover:bg-brand-dark';
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</a>
@else
    <button {{ $attributes->merge(['type' => 'button', 'class' => $classes]) }}>{{ $slot }}</button>
@endif
```

| Ús                                           | HTML resultant                                                     |
|----------------------------------------------|--------------------------------------------------------------------|
| `<x-button href="/">Inici</x-button>`        | Un enllaç `<a>` amb l'aspecte de botó                              |
| `<x-button>Desar</x-button>`                 | `<button type="button">` (el tipus per defecte)                    |
| `<x-button type="submit">Desar</x-button>`   | `<button type="submit">`: el tipus de fora substituïx el per defecte |
| `<x-button class="mt-8">...</x-button>`      | Les classes de fora **se sumen** a les del component               |

::: warning Atenció
Si passes una classe que **xoca** amb una del component (per exemple `px-8` a un botó que ja porta `px-6`), les dos queden a l'HTML i guanya la que apareix **més tard en el CSS de Tailwind**, no la que has escrit última. Per a canviar un estil del component, edita el component.
:::

::: tip Bona pràctica
Un botó que navega és un enllaç (`<a>`) i un botó que envia un formulari és un `<button>`. El component tria un o l'altre segons si li passes `href`.
:::

### 4.5. Components amb classe (només per a saber que existeixen)

Laravel té un segon tipus de component, el **component amb classe**: a més de la vista, té una classe PHP a `app/View/Components` amb el constructor i la lògica. Es crea amb:

```bash
sail artisan make:component Alert
```

| Anònim (el que usem)                     | Amb classe                                                        |
|------------------------------------------|-------------------------------------------------------------------|
| Un sol arxiu `.blade.php`                | Vista + classe PHP                                                |
| Les dades es declaren amb `@props`       | Les dades són propietats del constructor                          |
| Ideal per a peces de disseny             | Quan cal lògica o dependències del contenidor de serveis          |

Per a crear un component anònim amb Artisan: `sail artisan make:component forms.input --view`. A este curs no necessitarem cap component amb classe.

### 4.6. Comprovar que funciona

Ara fem servir els components a la portada. A `resources/views/home.blade.php`, substituïx tot el contingut de `<body>` (de l'etiqueta `<body>` fins a `</body>`) per:

```blade
<body class="bg-white text-ink antialiased">
    <main class="mx-auto max-w-5xl px-6 py-16">
        <h1 class="font-serif text-5xl font-semibold tracking-tight text-ink sm:text-6xl">Etiquetes</h1>
        <div class="mt-6 h-1 w-16 rounded-full bg-accent"></div>
        <p class="mt-6 max-w-xl text-xl leading-relaxed text-ink/70">Les últimes etiquetes publicades.</p>

        <x-button href="#" class="mt-8">Crea la teua etiqueta</x-button>

        <div class="mt-14 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @forelse ($labels as $label)
                <x-label-card :label="$label" class="transition hover:border-brand">
                    <x-slot:footer>
                        {{ $label['author'] }} · {{ $label['date'] }}
                    </x-slot>
                </x-label-card>
            @empty
                <p class="text-lg text-ink/60">Encara no hi ha cap etiqueta publicada.</p>
            @endforelse
        </div>
    </main>
</body>
```

El botó apunta a `#` de moment: al punt 6 el canviarem per una ruta amb nom.

1. Amb Vite en marxa, obri <http://localhost>. Ha de vore's: fons **blanc**, títol gran amb **serif** i negre, un **filet taronja** curt davall, el botó **blau** arrodonit i tres targetes amb la categoria en una insígnia blava suau, el producte en serif i l'autor i la data al peu, separats per una línia fina.
2. Passa el ratolí per una targeta: la vora ha de passar a **blau**. Això és `merge()` sumant la classe `hover:border-brand` a les de la targeta. Comprova-ho amb F12 (Inspeccionar): el `class` de l'`<article>` ha de portar totes.
3. Passa el ratolí per sobre del botó: el blau s'ha d'enfosquir (`brand-dark`).
4. A `label-card.blade.php`, canvia `<x-badge>` per `<x-badge tone="accent">`: les insígnies han de passar a **fons taronja amb text negre**. Torna-ho a deixar com estava.
5. A `home.blade.php`, esborra el bloc `<x-slot:footer>` d'una targeta: ha de mostrar-se **sense peu i sense cap error** (gràcies a `@isset`). Restaura'l.
6. Escriu per error `<x-badg>` en lloc de `<x-badge>`: Laravel mostra un error dient que no troba el component. Corregix-ho.
## 5. El layout com a component

Ara mateix, `home.blade.php` porta el `<head>`, el `@vite` i tota l'estructura de la pàgina. Si hi haguera cinc pàgines, ho tindríem copiat cinc vegades. A la unitat 4 ho resolíem amb `capcalera.php` i `peu.php` ([punt 10.2](/contenidos/4-acces-a-dades#_10-2-reutilitzar-la-capcalera-i-el-peu-del-panell)): cada pàgina incloïa els dos arxius, un al principi i un altre al final. Ací farem el mateix amb un component: el **layout** és la «carcassa» de la pàgina, i cada vista només escriu el seu contingut a dins.

| Unitat 4                                                    | Laravel                                                          |
|-------------------------------------------------------------|------------------------------------------------------------------|
| Dos arxius (`capcalera.php` i `peu.php`) que s'inclouen     | **Un sol** component: `<x-layout>`                               |
| La pàgina ha de recordar incloure els dos                   | La pàgina s'envolta amb l'etiqueta i el peu ve sempre            |
| El títol es passa amb una variable (`$titol`)               | El títol es passa amb un slot amb nom                            |

### 5.1. `<x-layout>`: la carcassa de la pàgina

Crea `resources/views/components/layout.blade.php`:

```blade
<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@isset($title){{ $title }} · @endisset{{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex min-h-screen flex-col bg-white text-ink antialiased">
    <header class="border-b border-ink/10">
        <nav class="mx-auto flex max-w-5xl items-center justify-between px-6 py-5">
            <a href="/" class="font-serif text-2xl font-semibold tracking-tight text-ink">{{ config('app.name') }}</a>

            <div class="flex items-center gap-6">
                <a href="/" class="text-ink/70 transition hover:text-brand">Etiquetes</a>
                <a href="#" class="text-ink/70 transition hover:text-brand">Accedeix</a>
            </div>
        </nav>
    </header>

    <main class="mx-auto w-full max-w-5xl flex-1 px-6 py-16">
        {{ $slot }}
    </main>

    <footer class="border-t border-ink/10">
        <div class="mx-auto max-w-5xl px-6 py-8 text-sm text-ink/60">
            © {{ date('Y') }} {{ config('app.name') }} · Projecte de la unitat 5 de DWES
        </div>
    </footer>
</body>
</html>
```

Les peces importants:

- **<code v-pre>{{ $slot }}</code>** és el lloc on s'encaixarà el contingut de cada pàgina.
- **El títol és un slot amb nom** (`$title`) i és **opcional**: si la pàgina no en defineix cap, la pestanya del navegador mostra només el nom de l'aplicació. `@isset($title)` fa eixa comprovació.
- **`config('app.name')`** llig el nom que vam posar al `.env` (`"Etiquetes"`), així no escrivim el nom a mà en cap lloc.
- **`flex min-h-screen flex-col`** al `<body>` i **`flex-1`** al `<main>`: fan que el peu quede sempre al final de la finestra, encara que la pàgina tinga poc contingut.
- Els enllaços de dalt són **provisionals**: «Accedeix» apunta a `#`. Al punt 6 els canviarem per rutes amb nom.

::: info Nota
Hem posat el títol en dos <code v-pre>{{ }}</code> separats en lloc de concatenar-los amb un punt (`$title . ' · '`). El motiu és que `$title` és un objecte *slot*, no un text, i concatenar-lo el convertiria en cadena i es tornaria a escapar. Escrit així, Blade ho gestiona bé.
:::

### 5.2. Què va al layout i què va a la vista

| Al layout (una sola vegada)                           | A cada vista (el que canvia)                       |
|-------------------------------------------------------|----------------------------------------------------|
| `<!DOCTYPE>`, `<head>` i `@vite`                      | El contingut de la pàgina                          |
| La barra de navegació i el peu                        | El títol de la pestanya (opcional)                 |
| El color de fons i la tipografia base                 | Si cal, un ample diferent per al contingut         |

Com que `@vite` ja viu al layout, **tota** pàgina que l'use tindrà Tailwind sense fer res més. Si l'any que ve vols afegir una lletra, una icona o canviar el peu, es toca un únic arxiu.

### 5.3. Aplicar el layout a la portada

La vista ja no necessita el `<head>`. Substituïx **tot** el contingut de `resources/views/home.blade.php` per:

```blade
<x-layout>
    <x-slot:title>Etiquetes publicades</x-slot>

    <h1 class="font-serif text-5xl font-semibold tracking-tight text-ink sm:text-6xl">Etiquetes</h1>
    <div class="mt-6 h-1 w-16 rounded-full bg-accent"></div>
    <p class="mt-6 max-w-xl text-xl leading-relaxed text-ink/70">Les últimes etiquetes publicades.</p>

    <x-button href="#" class="mt-8">Crea la teua etiqueta</x-button>

    <div class="mt-14 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
        @forelse ($labels as $label)
            <x-label-card :label="$label" class="transition hover:border-brand">
                <x-slot:footer>
                    {{ $label['author'] }} · {{ $label['date'] }}
                </x-slot>
            </x-label-card>
        @empty
            <p class="text-lg text-ink/60">Encara no hi ha cap etiqueta publicada.</p>
        @endforelse
    </div>
</x-layout>
```

De les 40 línies d'abans queden només les del contingut. Tot el que no és contingut ha passat al layout.

Ja no necessitem la pàgina de benvinguda de Laravel: no la usa cap ruta des del punt 3. Esborra-la:

```bash
rm resources/views/welcome.blade.php
```

### 5.4. Comprovar que funciona

1. Obri <http://localhost>. Ha d'aparéixer una **barra superior** amb «Etiquetes» en serif a l'esquerra i dos enllaços a la dreta, el contingut de sempre i un **peu** amb l'any actual i el nom de l'aplicació.
2. Mira el títol de la pestanya del navegador: ha de ser **«Etiquetes publicades · Etiquetes»**.
3. Obri el codi font de la pàgina (`Ctrl + U`): has de vore un únic document complet, amb el `<head>` i les etiquetes de Vite que ha generat `@vite`, tot i que a `home.blade.php` ja no hi són.
4. Esborra temporalment el `<x-slot:title>` de `home.blade.php` i recarrega: el títol de la pestanya passa a ser només **«Etiquetes»**. Restaura-ho.
5. Posa temporalment `$labels = [];` a `routes/web.php`: només es veu el missatge de «cap etiqueta», però el **peu continua al final de la finestra** i no flota a mitja pàgina. Restaura l'array.
6. Canvia el text del peu a `layout.blade.php` i recarrega: el canvi s'aplica a tota l'aplicació. Quan tinguem més pàgines, es veurà a totes.
7. Executa `sail artisan route:list` i comprova que continua apareixent la ruta `/`. La pàgina de benvinguda esborrada no la necessita cap ruta.



## 6. Rutes amb paràmetres i amb nom

Ara mateix l'aplicació només té una pàgina. Necessitem una pàgina de **detall** per a cada etiqueta, i que els enllaços de la barra i de les targetes funcionen de veritat. A la unitat 4 el detall era `lead.php?id=2`, amb l'`id` a la URL i la validació escrita a mà (`ctype_digit`). Ací la ruta ho resol tot.

### 6.1. Paràmetres a la ruta

Un **paràmetre de ruta** és un tros de la URL que canvia, escrit entre claus. Afig esta ruta a `routes/web.php`, **després** de la ruta `/`:

```php
Route::get('/etiquetes/{id}', function (string $id) use ($labels) {
    $label = collect($labels)->firstWhere('id', (int) $id);

    if ($label === null) {
        abort(404);
    }

    return view('labels.show', ['label' => $label]);
})->whereNumber('id')->name('labels.show');
```

- **`{id}`**: la URL `/etiquetes/2` encaixa amb esta ruta i el valor `2` arriba a la funció com a paràmetre `$id`. Sempre arriba com a **text** (`string`), per això el convertim amb `(int)`.
- **`->whereNumber('id')`**: restringeix el paràmetre a xifres. Si la URL no encaixa (`/etiquetes/abc`), Laravel respon directament amb un **404**. Fa el mateix que el `ctype_digit` de la unitat 4, però en una sola línia.
- **`collect($labels)->firstWhere('id', ...)`**: `collect()` converteix l'array en una col·lecció, i `firstWhere` torna el primer element amb l'`id` indicat, o `null` si no n'hi ha cap. Les col·leccions tornaran quan treballem amb Eloquent al bloc 4.
- **`abort(404)`**: llança un error 404 («pàgina no trobada») i talla l'execució.

::: info Nota
Este `if` és el que farà soleta Laravel quan tinguem models: amb l'*implicit model binding* del bloc 4, escriurem `{label}` i Laravel buscarà el model i enviarà el 404 per nosaltres.
:::

Crea la vista `resources/views/labels/show.blade.php`:

```blade
<x-layout>
    <x-slot:title>{{ $label['product'] }}</x-slot>

    <a href="{{ route('home') }}" class="text-ink/60 transition hover:text-brand">← Totes les etiquetes</a>

    <article class="mx-auto mt-10 max-w-2xl">
        <x-badge>{{ $label['category'] }}</x-badge>

        <h1 class="mt-6 font-serif text-5xl font-semibold tracking-tight text-ink sm:text-6xl">{{ $label['product'] }}</h1>

        @if ($label['variety'])
            <p class="mt-2 text-2xl text-ink/60">{{ $label['variety'] }}</p>
        @endif

        <div class="mt-8 h-1 w-16 rounded-full bg-accent"></div>

        <p class="mt-8 text-xl leading-relaxed text-ink/80">{{ $label['description'] }}</p>
        <p class="mt-10 text-ink/60">{{ $label['author'] }} · {{ $label['date'] }}</p>
    </article>
</x-layout>
```

Fixa't que `view('labels.show')` busca `resources/views/labels/show.blade.php`: el punt separa la carpeta de l'arxiu (punt 3.1).

### 6.2. Rutes amb nom i `route()`

Si escrivim els enllaços a mà (`<a href="/etiquetes/2">`), el dia que canviem la URL haurem de buscar-los tots. Una **ruta amb nom** té un identificador propi, i els enllaços es generen a partir d'ell.

El nom es posa amb `->name()` (ja ho has vist a la ruta anterior: `labels.show`). Primer, donem nom a la portada: **canvia** la ruta `/` perquè acabe així:

```php
Route::get('/', function () use ($labels) {
    return view('home', ['labels' => $labels]);
})->name('home');
```

Després, afig estes dos rutes provisionals (al punt 7 tindran la seua pàgina):

```php
Route::get('/accedir', function () {
    return 'Formulari d\'accés (provisional)';
})->name('login');

Route::get('/registre', function () {
    return 'Formulari de registre (provisional)';
})->name('register');
```

La funció `route()` genera la URL a partir del nom:

| Codi                                       | Resultat                                                |
|--------------------------------------------|---------------------------------------------------------|
| `route('home')`                            | `http://localhost`                                      |
| `route('labels.show', ['id' => 2])`        | `http://localhost/etiquetes/2`                          |
| `route('labels.show', ['id' => 2, 'x' => 1])` | `http://localhost/etiquetes/2?x=1` (els sobrants van a la query) |

Ara fem que els enllaços funcionen. Són canvis menuts en tres arxius:

**`resources/views/components/layout.blade.php`**: a la barra de navegació, canvia els tres enllaços:

```blade
<a href="{{ route('home') }}" class="font-serif text-2xl font-semibold tracking-tight text-ink">{{ config('app.name') }}</a>

<div class="flex items-center gap-6">
    <a href="{{ route('home') }}" class="text-ink/70 transition hover:text-brand">Etiquetes</a>
    <a href="{{ route('login') }}" class="text-ink/70 transition hover:text-brand">Accedeix</a>
</div>
```

**`resources/views/components/label-card.blade.php`**: el títol de la targeta passa a ser un enllaç al detall:

```blade
<h2 class="mt-4 font-serif text-3xl font-semibold tracking-tight text-ink">
    <a href="{{ route('labels.show', ['id' => $label['id']]) }}" class="transition hover:text-brand">{{ $label['product'] }}</a>
</h2>
```

**`resources/views/home.blade.php`**: el botó deixa de apuntar a `#`:

```blade
<x-button :href="route('register')" class="mt-8">Crea la teua etiqueta</x-button>
```

Fixa't en els dos punts davant de `href`: com que el valor és una expressió PHP (`route(...)`) i no un text, es passa amb `:` (punt 4.2).

::: tip Bona pràctica
Escriu **sempre** els enllaços amb `route()` i el nom de la ruta, mai amb la URL a mà. Si canvies `/etiquetes/{id}` per `/e/{id}`, només tocaràs `routes/web.php` i tots els enllaços continuaran funcionant.
:::

### 6.3. Grups de rutes: `prefix` i `name`

La zona privada (el panell) tindrà moltes rutes que comencen per `/panell`. Un **grup** permet escriure el que tenen en comú una sola vegada. Afig al final de `routes/web.php`:

```php
Route::prefix('panell')->name('panel.')->group(function () {
    Route::get('/', function () {
        return 'Panell privat (provisional)';
    })->name('index');

    Route::get('/etiquetes', function () {
        return 'Les meues etiquetes (provisional)';
    })->name('labels.index');
});
```

| Dins del grup             | URL                   | Nom                   |
|---------------------------|-----------------------|-----------------------|
| `Route::get('/', ...)`    | `/panell`             | `panel.index`         |
| `Route::get('/etiquetes', ...)` | `/panell/etiquetes` | `panel.labels.index` |

- **`prefix('panell')`** afig `panell` davant de totes les URL del grup.
- **`name('panel.')`** afig `panel.` davant del nom de cada ruta. El **punt final** és obligatori: Laravel escriu el text tal com el poses, sense afegir cap separador.

::: info Nota
Els grups també serveixen per a aplicar un **middleware** a totes les rutes alhora (`Route::middleware('auth')->group(...)`). Al bloc 6 tancarem així el panell perquè només hi accedisquen els usuaris identificats.
:::

### 6.4. Comprovar que funciona

1. Executa `sail artisan route:list`. Han d'aparéixer, cadascuna amb el seu nom: `home`, `labels.show`, `login`, `register`, `panel.index` i `panel.labels.index`.
2. Obri <http://localhost>. Fes clic en el nom d'un producte: has d'anar a `/etiquetes/1` (o 2, o 3) i vore la pàgina de detall amb el títol gran. El títol de la pestanya ha de ser el nom del producte seguit del d'Etiquetes.
3. Al detall de l'Ametla (`/etiquetes/3`), no ha d'aparéixer línia de varietat.
4. Prova les URL incorrectes: `/etiquetes/99` i `/etiquetes/abc` han de mostrar un error **404**. La primera el genera el nostre `abort(404)`, i la segona, la restricció `whereNumber`.
5. Fes clic en «Accedeix» a la barra i en el botó blau de la portada: han de portar a `/accedir` i a `/registre`, amb el text provisional. Obri també `/panell` i `/panell/etiquetes`.
6. **La prova del nom.** A `routes/web.php`, canvia `'/etiquetes/{id}'` per `'/e/{id}'` i recarrega la portada: els enllaços de les targetes ara apunten a `/e/1`, i **continuen funcionant**, perquè s'han generat amb `route()`. Torna-ho a deixar com estava.
7. Obri Tinker (`sail artisan tinker`) i executa `route('labels.show', ['id' => 2])`: ha de tornar una URL acabada en `/etiquetes/2`. Ix amb `exit`.