# Bloc 7. Arxius, correus, sessions i cookies

## 1. Punt de partida: què afegirem a l'aplicació

Fins ara les etiquetes tenen **tota la informació** (producte, lot, codi de barres...), però no tenen **foto**: la vista pinta un marcador amb la inicial del producte. I tampoc podem traure-les de l'aplicació: no hi ha manera de tindre l'etiqueta com un document. En este bloc tanquem el projecte afegint quatre peces:

| Peça | Què farà | On la veuràs |
|---|---|---|
| **Foto del producte** | L'usuari la puja en crear o editar l'etiqueta | Formulari, targeta i detall |
| **PDF de l'etiqueta** | Es genera des d'una vista Blade i es pot descarregar | Panell |
| **Correu amb el PDF** | L'aplicació t'envia el PDF adjunt | Botó «Envia-me el PDF» |
| **Galeta pròpia** | La portada recorda l'última categoria triada | Portada |

Per al PDF instal·larem dos paquets de Composer (`barryvdh/laravel-dompdf` per a generar-lo i `picqer/php-barcode-generator` per a dibuixar el codi de barres), als punts 6 i 7.

Al llarg del bloc utilitzarem la IA com a **assistent en una tasca acotada** (el codi de la pujada) i, com al bloc anterior, comprovarem què ha deixat sense protegir.

::: info Nota
Les 15 etiquetes de mostra **no tenen foto** i continuaran mostrant el marcador amb la inicial. Només les etiquetes noves en tindran. El PDF i la vista prèvia també han de funcionar sense foto.
:::

### 1.1. Comprovar que funciona

Abans de tocar res, comprova que el projecte està en marxa:

```bash
sail up -d
```

1. Obri <http://localhost>, inicia sessió amb `prova@example.com` i `password`, i entra a «Les meues etiquetes»: hi ixen les etiquetes de `prova`.
2. Obri <http://localhost:8025>: s'obri Mailpit, la safata on veurem els correus que envie l'aplicació (no ix de la teua màquina cap correu de veritat).


## 2. Pujar la foto del producte

Ja tenim el formulari, la validació i el panell. Falta que cada etiqueta puga tindre **foto**. És una tasca ben acotada per a delegar-la en una IA, així que la provem així.

### 2.1. El que li demanem a la IA

```
Al projecte Laravel 13 de les etiquetes, afig la pujada d'una foto del producte en crear una etiqueta. Guarda l'arxiu al disc `public`, la ruta a la columna `photo_path` i mostra la foto a la vista.
```

Este és el codi que et proposaria. Funciona a la primera; al punt 3 el repassarem. Primer, preparem on guardar els arxius.

### 2.2. Preparar l'emmagatzematge

1. Al fitxer `.env`, canvia l'adreça de l'aplicació perquè coincidisca amb la que utilitzes al navegador (sense el port 8000):

   ```dotenv
   APP_URL=http://localhost
   ```

   El disc `public` construïx les URL de les fotos amb `APP_URL` + `/storage`. Si ací posara un port que no és el teu, les fotos apuntarien a una adreça que no existix.

2. Crea l'enllaç que fa visibles els arxius pujats:

   ```bash
   sail artisan storage:link
   ```

::: info Nota
**Discs i enllaç simbòlic.** Laravel guarda els arxius en *discs*, definits a `config/filesystems.php`. El teu `.env` té `FILESYSTEM_DISK=local`: eixe disc és **privat** (guarda a `storage/app/private`) i el navegador no pot accedir-hi. El disc `public` guarda a `storage/app/public`, però eixa carpeta està fora de `public/`, l'única que serveix el servidor web. `storage:link` crea un **enllaç simbòlic** (un accés directe) `public/storage` → `storage/app/public`, i així l'arxiu `storage/app/public/labels/a.jpg` es veu a `/storage/labels/a.jpg`.
:::

A la unitat 4 ([funcions auxiliars per a pujar i eliminar arxius](/contenidos/4-acces-a-dades#_9-funcions-auxiliars-pujar-i-eliminar-arxius)) ho feies tot a mà. Ací Laravel s'encarrega de moure l'arxiu i de triar el disc.

### 2.3. El formulari

A `resources/views/components/label-form.blade.php`, **substituïx** l'etiqueta `<form ...>` i afig el camp de la foto abans del de la descripció:

```blade
<form method="POST" action="{{ $action }}" enctype="multipart/form-data" class="mt-10 grid gap-6 sm:grid-cols-2">
```

```blade
<div class="sm:col-span-2">
    <x-input label="Foto del producte" name="photo" type="file" />
</div>
```

- **`enctype="multipart/form-data"`** és imprescindible: sense ell, el navegador només envia el *nom* de l'arxiu, no el seu contingut.
- Reutilitzem `<x-input>` amb `type="file"`, que ja pinta l'etiqueta i l'error de `photo`.

::: info Nota
Si el formulari torna amb errors, el navegador **no recorda l'arxiu triat** (els camps de tipus arxiu no es poden omplir de nou): cal triar-lo una altra vegada.
:::

El formulari és el mateix per a editar, així que el camp també ix a l'edició. Fins al punt 5 `update()` l'ignora.

### 2.4. El controlador

A `app/Models/Label.php`, **lleva** `'photo_path',` de la llista de `#[Fillable]`. La ruta de la foto l'omple el servidor, mai ha de vindre d'un formulari.

A `app/Http/Controllers/Panel/LabelController.php`, **substituïx** `store()`:

```php
public function store(LabelRequest $request): RedirectResponse
{
    $label = Auth::user()->labels()->create($request->validated());

    if ($request->hasFile('photo')) {
        $file = $request->file('photo');

        $label->photo_path = $file->storeAs('labels', $file->getClientOriginalName(), 'public');
        $label->save();
    }

    return to_route('panel.labels.index')->with('status', 'Etiqueta guardada correctament.');
}
```

Què fa:

- **`hasFile('photo')`** és `true` si l'usuari ha triat un arxiu, i **`file('photo')`** el retorna com un objecte amb mètodes per a guardar-lo.
- **`storeAs('labels', nom, 'public')`** guarda l'arxiu a `storage/app/public/labels/` amb el nom indicat i retorna la ruta relativa (`labels/nom.jpg`). El tercer paràmetre és el disc: cal posar-lo perquè el disc per defecte és `local` (privat).
- **`getClientOriginalName()`** és el nom que té l'arxiu a l'ordinador de l'usuari.
- `photo_path` s'assigna a mà i es guarda amb `save()`: per això ja no va a `#[Fillable]`.

### 2.5. La vista

Les targetes i el detall ja pinten `<x-label-photo>`, així que només cal canviar eixe component. **Substituïx** el contingut de `resources/views/components/label-photo.blade.php`:

```blade
@props(['label'])

<div {{ $attributes->merge(['class' => 'flex aspect-[4/3] items-center justify-center overflow-hidden rounded-xl bg-ink/5']) }}>
    @if ($label->photo_path)
        <img src="{{ Storage::disk('public')->url($label->photo_path) }}" alt="Foto de {{ $label->product }}" class="h-full w-full object-cover">
    @else
        <span aria-hidden="true" class="font-serif text-6xl font-semibold text-ink/30">{{ mb_strtoupper(mb_substr($label->product, 0, 1)) }}</span>
    @endif
</div>
```

- **`Storage::disk('public')->url(...)`** construïx l'adreça completa de la foto a partir de la configuració del disc. Sense `disk('public')` s'utilitzaria el disc per defecte (`local`), que no és el nostre.
- **`object-cover`** fa que la foto òmpliga el marc 4:3 **mantenint la proporció**: mai s'estira ni es deforma, però si la foto no és 4:3 es retalla l'excés per les vores (es veu el centre). Així totes les targetes tenen la mateixa grandària.
- L'**`alt`** descriu la imatge per a lectors de pantalla.
- Les etiquetes sense foto (les de mostra) continuen mostrant la inicial.

::: info Nota
Si preferixes veure la foto **sencera** (amb franges buides als costats), canvia `object-cover` per `object-contain`. Ací triem `object-cover` perquè la graella queda uniforme.
:::

### 2.6. Comprovar que funciona

1. Executa `sail artisan storage:link`: mostra un missatge dient que l'enllaç s'ha creat (si ja existia, t'ho diu i no passa res).
2. Canvia `APP_URL` al `.env` com al punt 2.2.
3. Inicia sessió amb `prova@example.com`, fes clic a «Nova etiqueta», omple el formulari i tria una imatge de la teua màquina (reanomena-la a `tomaques.jpg`; la necessitarem al punt 3). Envia: al panell, la targeta de la nova etiqueta mostra la foto.
4. Fes clic dret sobre la foto → *Copiar l'adreça de la imatge*: és `http://localhost/storage/labels/tomaques.jpg`.
5. Obri el detall («Veure») i la portada: la foto ix a tots dos llocs. Les etiquetes de mostra mantenen la inicial.
6. A phpMyAdmin, la columna `photo_path` de la fila nova val `labels/tomaques.jpg`. L'arxiu existix a `storage/app/public/labels/` dins del projecte.
7. Crea una altra etiqueta **sense** triar foto: es guarda i mostra la inicial.
8. Edita la primera, tria una altra imatge i guarda: la foto **no canvia**. Ho arreglem al punt 5.


## 3. La trampa: una pujada que «funciona»

Ja tenim la foto: es puja, es guarda i es veu. Era una tasca ben acotada i la IA l'ha resolta a la primera. Però que funcione amb una foto normal no vol dir que estiga ben fet: no li hem dit **què** ha d'acceptar, i no ho ha previst.

### 3.1. Primer forat: qualsevol arxiu és una «foto»

1. Crea un arxiu `apunts.txt` amb qualsevol text.
2. Com a `prova`, crea una etiqueta i tria `apunts.txt` com a foto. **S'accepta** i es guarda l'etiqueta.
3. Al panell, la imatge no es carrega (el navegador mostra una imatge trencada o el text alternatiu). A phpMyAdmin, `photo_path` val `labels/apunts.txt`.

Cap regla comprova que l'arxiu siga una imatge.

### 3.2. Segon forat: el nom el posa l'usuari

El codi guarda l'arxiu amb el nom que ve de l'ordinador de l'usuari. Dos arxius amb el mateix nom acaben a la mateixa ruta.

1. Ix i inicia sessió amb `nou@example.com`.
2. Crea una etiqueta amb una imatge **diferent** de la del punt 2, reanomenada també a `tomaques.jpg`.
3. Obri la portada i prem `Ctrl+F5` (per a saltar-te la memòria cau del navegador): l'etiqueta de `prova` mostra ara la imatge de `nou`.

L'arxiu s'ha **sobreescrit** sense cap avís: un usuari ha canviat la foto d'una etiqueta que no és seua.

### 3.3. Tercer forat: es pot pujar una pàgina web

1. Crea un arxiu `hola.html` amb este contingut:

   ```html
   <script>alert('hola')</script>
   ```

2. Puja'l com a foto d'una etiqueta.
3. Obri <http://localhost/storage/labels/hola.html>: el navegador l'obri com a pàgina web i **executa l'script** (apareix l'alerta).

L'arxiu es serveix des de la mateixa adreça que la teua aplicació, així que el codi que porte s'executa com si fora de l'aplicació.

::: warning Atenció
La documentació de Laravel avisa del cas pitjor: si el nom que posa l'usuari acaba en `.php`, l'arxiu podria executar-se com a codi PHP al servidor. Per això no es deriva mai el nom d'un arxiu del que envia l'usuari. No ho provem.
:::

### 3.4. Què ha passat

| Forat | Per què passa | On l'arreglem |
|---|---|---|
| Es guarda qualsevol tipus d'arxiu | Cap regla comprova que siga una imatge | Punt 4 |
| Una foto en sobreescriu una altra | El nom el posa l'usuari, i el mateix nom és la mateixa ruta | Punt 4 |
| Es pot pujar una pàgina HTML amb codi | Es guarda tal qual i el servidor la serveix com a pàgina web | Punt 4 |
| La mida no té límit en l'aplicació | Cap regla la limita | Punt 4 |

La IA ha resolt el que li hem demanat (guardar i mostrar la foto) i ha deixat sense protegir el que no li hem dit. Amb una foto normal no es nota res.

::: tip Bona pràctica
Amb codi de pujada d'arxius generat per una IA, repassa tres preguntes: **quin tipus d'arxiu accepte?**, **quant pot pesar?** i **qui decidix el nom amb què es guarda?** Si alguna no té resposta al codi, falta una protecció.
:::

Deixa les etiquetes i els arxius tal com estan: al punt 4 arreglem els forats i netegem.

## 4. Validar la foto i posar-li un nom segur

Al punt 3 hem vist els forats de la pujada. Els tapem amb tres decisions: **quin tipus d'arxiu acceptem** (JPG o PNG), **quant pot pesar** (2 MB) i **qui posa el nom** (Laravel, mai l'usuari).

### 4.1. La regla de la foto

A `app/Http/Requests/LabelRequest.php`, afig esta regla al final de `rules()`:

```php
'photo' => [
    $this->isMethod('POST') ? 'required' : 'nullable',
    'image',
    'mimes:jpg,jpeg,png',
    'max:2048',
],
```

Afig també este nom a `attributes()`:

```php
'photo' => 'foto',
```

I estos missatges a `messages()`:

```php
'photo.image'    => 'La foto ha de ser una imatge.',
'photo.mimes'    => 'La foto ha de ser un arxiu JPG o PNG.',
'photo.max'      => 'La foto no pot pesar més de 2 MB.',
'photo.uploaded' => "No s'ha pogut pujar la foto.",
```

Què fa cada regla:

- **`required` / `nullable`**: en crear l'etiqueta (`POST`) la foto és obligatòria; en editar (`PUT`) és opcional, i si no en tries una, es conserva l'anterior (punt 5).
- **`image`**: comprova que l'arxiu és una imatge. Per defecte **no accepta SVG**, perquè un SVG pot dur codi.
- **`mimes:jpg,jpeg,png`**: restringix els formats. Llig el **contingut** de l'arxiu per a esbrinar-ne el tipus; no es fia de l'extensió del nom.
- **`max:2048`**: en un arxiu, el nombre són **quilobytes**: 2048 KB = 2 MB.
- **`photo.uploaded`** surt quan el servidor ni tan sols ha pogut rebre l'arxiu (per exemple, per superar el límit de PHP).

::: info Nota
Per què `photo.max` i no el `max` que ja tenim? Perquè el missatge `max` genèric diu «no pot tindre més de :max **caràcters**» i s'aplicaria també a la foto. Laravel busca primer el missatge específic del camp (`photo.max`) i després el genèric, com vam veure amb `password.min`.
:::

### 4.2. El nom el posa Laravel

A `app/Http/Controllers/Panel/LabelController.php`, **substituïx** `store()`:

```php
public function store(LabelRequest $request): RedirectResponse
{
    $label = Auth::user()->labels()->create($request->safe()->except('photo'));

    $label->photo_path = $request->file('photo')->store('labels', 'public');
    $label->save();

    return to_route('panel.labels.index')->with('status', 'Etiqueta guardada correctament.');
}
```

- **`store('labels', 'public')`** (sense el nom) fa que Laravel genere un **nom únic i aleatori**, i l'extensió la dedunix del **tipus real de l'arxiu**. Ja no importa com es diga l'arxiu a l'ordinador de l'usuari: `tomaques.jpg` de dos usuaris acaben com a dos arxius diferents.
- **`safe()->except('photo')`** dona les dades validades sense l'arxiu, que no és una columna de la taula.
- Ja no cal `hasFile()`: la foto és obligatòria, així que si arribem ací, ja existix.

::: warning Atenció
La documentació ho avisa expressament: validar el tipus **no** substituïx el nom segur. Un arxiu pot passar per imatge i dur codi, i si es guardara amb extensió `.php` podria executar-se. Per això, el nom i l'extensió sempre els posa el servidor.
:::

### 4.3. El formulari

A `resources/views/components/label-form.blade.php`, **substituïx** el camp de la foto:

```blade
<x-input label="Foto del producte (JPG o PNG, màx. 2 MB)" name="photo" type="file" accept="image/jpeg,image/png" />
```

L'atribut **`accept`** fa que el selector d'arxius del navegador només propose JPG i PNG. És una **comoditat**, no una protecció: l'usuari pot saltar-se-la (o enviar la petició sense el formulari). La protecció real és la validació del servidor.

::: info Més professional
Laravel també té regles en forma d'objecte: `File::image()->max(2 * 1024)->dimensions(...)` permet, per exemple, limitar les mides en píxels. En projectes reals es redimensiona la foto en pujar-la (miniatures) amb una llibreria d'imatges. Ací ens quedem amb la versió simple.
:::

### 4.4. Comprovar que funciona

1. Crea una etiqueta amb tots els camps correctes **sense triar foto**: sota el camp ix «El camp foto és obligatori.».
2. Tria `apunts.txt` (si el selector només et deixa veure imatges, canvia el filtre a «Tots els fitxers»): «La foto ha de ser una imatge.». Passa el mateix amb `hola.html`.
3. Tria una imatge que no siga JPG ni PNG (un `.gif` o un `.webp`): «La foto ha de ser un arxiu JPG o PNG.».
4. Tria una foto de més de 2 MB (les del mòbil solen passar-ne): «La foto no pot pesar més de 2 MB.». Si ix «No s'ha pogut pujar la foto.», el límit és el de PHP: avisa'm.
5. Tria una foto correcta: es guarda i es veu. A `storage/app/public/labels/` hi ha un arxiu nou amb un **nom llarg i aleatori** acabat en `.jpg` o `.png`, i `photo_path` a phpMyAdmin té eixe nom.
6. Puja la mateixa imatge (`tomaques.jpg`) com a `prova` i com a `nou`: s'han creat **dos arxius diferents** i cada etiqueta mostra la seua. Ja no se sobreescriuen.
7. Edita una etiqueta **sense triar foto**: es guarda sense errors. Si hi tries `apunts.txt`, ix l'error de l'apartat 2.

Les etiquetes i els arxius de la trampa (`apunts.txt`, `hola.html`, `tomaques.jpg`) els deixem així: al punt 5, quan esborrar una etiqueta també esborre la seua foto, els traurem.

## 5. Reemplaçar i esborrar la foto

Ara que la foto es guarda bé, falta mantindre-la en ordre: si l'usuari **canvia** la foto d'una etiqueta o **esborra** l'etiqueta, l'arxiu antic no ha de quedar a `storage` per a sempre.

::: info Nota
La base de dades només guarda un **text** (`photo_path`). Quan esborres la fila, l'arxiu continua al disc: Laravel no sap que estan relacionats. Hem de dir-li-ho nosaltres.
:::

### 5.1. El controlador

A `app/Http/Controllers/Panel/LabelController.php`, afig l'`use` de `Storage` (estem dins d'un `namespace`, així que la *facade* necessita el seu `use`):

```php
use Illuminate\Support\Facades\Storage;
```

**Substituïx** `update()` i `destroy()`:

```php
public function update(LabelRequest $request, Label $label): RedirectResponse
{
    Gate::authorize('update', $label);

    $label->update($request->safe()->except('photo'));

    if ($request->hasFile('photo')) {
        $oldPhoto = $label->photo_path;

        $label->photo_path = $request->file('photo')->store('labels', 'public');
        $label->save();

        if ($oldPhoto) {
            Storage::disk('public')->delete($oldPhoto);
        }
    }

    return to_route('panel.labels.index')->with('status', 'Etiqueta actualitzada correctament.');
}

public function destroy(Label $label): RedirectResponse
{
    Gate::authorize('delete', $label);

    $label->delete();

    if ($label->photo_path) {
        Storage::disk('public')->delete($label->photo_path);
    }

    return to_route('panel.labels.index')->with('status', 'Etiqueta esborrada correctament.');
}
```

Què fa:

- **`safe()->except('photo')`**: igual que a `store()`, les dades validades sense l'arxiu.
- **`hasFile('photo')`**: a l'edició la foto és opcional. Si l'usuari no tria cap, no es toca res i es conserva l'anterior.
- **L'ordre en reemplaçar**: primer es guarda la foto nova i s'actualitza la base de dades, i **després** s'esborra l'antiga. Si alguna cosa fallara al mig, l'etiqueta mai es queda sense foto.
- **`if ($oldPhoto)`**: les etiquetes de mostra no tenen foto (`photo_path` és `null`), i no hi ha res a esborrar.
- **`Storage::disk('public')->delete(...)`** rep la ruta relativa que tenim a `photo_path` (`labels/xxxx.jpg`), la mateixa que va tornar `store()`.
- **En esborrar**, primer s'esborra la fila i després l'arxiu: si el segon pas fallara, quedaria un arxiu perdut, però mai una etiqueta amb la foto trencada.
- **`Gate::authorize` continua al principi** de tots dos mètodes: l'arxiu només es toca **després** d'haver comprovat que l'etiqueta és de l'usuari. El Form Request valida l'arxiu abans d'entrar ací, però no guarda res.

::: info Més professional
Esborrar l'arxiu des de cada controlador funciona, però cal recordar-ho cada vegada que s'esborre una etiqueta. En projectes més grans es fa amb un *observer* del model (que reacciona a l'esdeveniment «esborrat») perquè passe sempre, des d'on siga.
:::

### 5.2. La foto actual a l'edició

Perquè l'usuari veja quina foto té, a `resources/views/components/label-form.blade.php` **substituïx** el camp de la foto:

```blade
<div class="sm:col-span-2">
    @if ($label?->photo_path)
        <x-label-photo :label="$label" class="mb-4 max-w-xs" />
    @endif

    <x-input label="Foto del producte (JPG o PNG, màx. 2 MB)" name="photo" type="file" accept="image/jpeg,image/png" />
</div>
```

En crear, `$label` és `null` i no es mostra res. En editar, apareix la foto actual damunt del camp; si no en tries una de nova, es conserva.

### 5.3. Comprovar que funciona

1. Edita una etiqueta amb foto: apareix la foto actual damunt del camp. Tria una altra, guarda: la targeta mostra la nova. A `storage/app/public/labels/` l'arxiu antic **ja no hi és** i n'hi ha un de nou.
2. Edita la mateixa etiqueta **sense triar foto** i canvia el producte: es guarda i la foto és la mateixa.
3. Edita una etiqueta de mostra (no té foto) i puja-li una: es guarda sense errors i ara mostra la foto.
4. Esborra l'etiqueta del pas 3 amb «Esborra»: desapareix de la llista i el seu arxiu desapareix de `storage/app/public/labels/`.
5. Neteja les proves del punt 3: esborra des del panell les etiquetes de `prova` i de `nou` que tenien `apunts.txt`, `hola.html` i `tomaques.jpg`. Els arxius corresponents desapareixen de la carpeta. Després obri <http://localhost/storage/labels/hola.html>: ja no mostra l'alerta (apareix la pàgina d'error 404).
6. Deixa **una etiqueta amb foto** correcta: la necessitarem al PDF dels punts 6 i 7.
## 6. L'etiqueta en PDF amb dompdf

Ja tenim totes les dades i la foto. Ara generem el **PDF** de l'etiqueta des d'una vista Blade, i una **previsualització** a la web que utilitza exactament el mateix disseny.

### 6.1. Instal·lar dompdf

```bash
sail composer require barryvdh/laravel-dompdf
```

Composer afig el paquet a `composer.json` i Laravel el detecta sol: no cal registrar res.

::: info Nota
**dompdf no és un navegador.** Convertix HTML i CSS a PDF, però només entén **CSS 2.1**: res de *flexbox*, de *grid* ni de Tailwind. Per això el disseny de l'etiqueta fa servir CSS propi i **taules** per a posar les coses en columnes. I com no té `object-fit`, ajustarem la foto a mà.
:::

### 6.2. El permís i les rutes

Només l'autor ha de poder veure i descarregar el PDF d'una etiqueta seua. A `app/Policies/LabelPolicy.php`, afig un mètode que reutilitza `update()`, com ja fa `delete()`:

```php
public function download(User $user, Label $label): bool
{
    return $this->update($user, $label);
}
```

A `routes/web.php`, dins del grup `panell` i darrere del `Route::resource(...)`, afig les dos rutes:

```php
Route::get('etiquetes/{label}/previsualitzacio', [Panel\LabelPdfController::class, 'show'])->name('labels.preview');
Route::get('etiquetes/{label}/pdf', [Panel\LabelPdfController::class, 'download'])->name('labels.pdf');
```

Estan dins del grup `auth` (cal sessió) i del prefix `panell`, així que els noms complets són `panel.labels.preview` i `panel.labels.pdf`.

### 6.3. El controlador

```bash
sail artisan make:controller Panel/LabelPdfController
```

**Substituïx** el contingut de `app/Http/Controllers/Panel/LabelPdfController.php` (el `use Illuminate\Http\Request;` que genera Laravel sobra):

```php
<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Label;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class LabelPdfController extends Controller
{
    public function show(Label $label): View
    {
        Gate::authorize('download', $label);

        return view('panel.labels.preview', ['label' => $label->load('category')]);
    }

    public function download(Label $label): Response
    {
        Gate::authorize('download', $label);

        return Pdf::loadView('pdf.label', ['label' => $label->load('category')])
            ->setPaper('a4', 'landscape')
            ->download('etiqueta-'.$label->id.'.pdf');
    }
}
```

- **`Gate::authorize('download', ...)`** va al principi, com als altres controladors: si l'etiqueta no és teua, error 403.
- **`$label->load('category')`**: el *lazy loading* està prohibit i el *binding* no carrega la categoria, així que la carreguem de forma explícita.
- **`Pdf::loadView('pdf.label', ...)`** genera el PDF a partir d'una vista Blade, i **`download()`** el torna perquè el navegador el descarregue.
- **`setPaper('a4', 'landscape')`**: full A4 en horitzontal.

### 6.4. El full de l'etiqueta

Este component és el disseny que compartixen el PDF i la previsualització. Crea'l:

```bash
sail artisan make:component label-sheet --view
```

**Substituïx** el contingut de `resources/views/components/label-sheet.blade.php`:

```blade
@props(['label'])

@php
    $disk = Storage::disk('public');
    $photo = null;

    if ($label->photo_path && $disk->exists($label->photo_path)) {
        $bytes = $disk->get($label->photo_path);
        [$width, $height] = getimagesizefromstring($bytes);

        $photo = 'data:'.$disk->mimeType($label->photo_path).';base64,'.base64_encode($bytes);
        $fit = $width / $height > 4 / 3 ? 'width: 7cm;' : 'height: 5.25cm;';
    }
@endphp

<style>
    .etq { width: 19cm; padding: 0.8cm; border: 2px solid #005b97; background: #fff; color: #000; font-family: 'DejaVu Sans', sans-serif; }
    .etq table { width: 100%; border-collapse: collapse; }
    .etq .etq-left { width: 7.4cm; vertical-align: top; }
    .etq .etq-right { padding-left: 0.6cm; vertical-align: top; }
    .etq .etq-ph { width: 7cm; height: 5.25cm; background: #eee; color: #bbb; font-size: 60pt; line-height: 5.25cm; text-align: center; }
    .etq .etq-cat { padding: 2px 8px; background: #fbad18; font-size: 9pt; }
    .etq h1 { margin: 10px 0 0 0; font-size: 24pt; }
    .etq .etq-var { margin: 2px 0 10px 0; color: #555; font-size: 13pt; }
    .etq .etq-data td { padding: 5px 0; border-bottom: 1px solid #ddd; font-size: 11pt; }
    .etq .etq-key { width: 4cm; color: #555; }
    .etq .etq-barcode { margin-top: 0.6cm; font-size: 11pt; letter-spacing: 2px; }
</style>

<div class="etq">
    <table>
        <tr>
            <td class="etq-left">
                @if ($photo)
                    <img src="{{ $photo }}" alt="Foto de {{ $label->product }}" style="{{ $fit }}">
                @else
                    <div class="etq-ph">{{ mb_strtoupper(mb_substr($label->product, 0, 1)) }}</div>
                @endif
            </td>
            <td class="etq-right">
                <span class="etq-cat">{{ $label->category->name }}</span>
                <h1>{{ $label->product }}</h1>
                @if ($label->variety)
                    <p class="etq-var">{{ $label->variety }}</p>
                @endif

                <table class="etq-data">
                    <tr><td class="etq-key">Pes net</td><td>{{ number_format($label->net_weight_g, 0, ',', '.') }} g</td></tr>
                    @if ($label->caliber)
                        <tr><td class="etq-key">Calibre</td><td>{{ $label->caliber }}</td></tr>
                    @endif
                    <tr><td class="etq-key">Origen</td><td>{{ $label->origin }}</td></tr>
                    <tr><td class="etq-key">Lot</td><td>{{ $label->batch }}</td></tr>
                    <tr><td class="etq-key">Envasador</td><td>{{ $label->packer }}</td></tr>
                    <tr><td class="etq-key">Codi de producte</td><td>{{ $label->product_code }}</td></tr>
                    <tr><td class="etq-key">Envasat el</td><td>{{ $label->packaged_at->format('d/m/Y') }}</td></tr>
                </table>

                <div class="etq-barcode">{{ $label->barcode }}</div>
            </td>
        </tr>
    </table>
</div>
```

Què fa:

- **La foto va incrustada** en el mateix HTML (`data:...;base64,...`). Així funciona igual al PDF (dompdf no descarrega imatges per adreça web si no s'activa expressament) i al navegador. Si l'etiqueta no té foto, es pinta un requadre gris amb la inicial.
- **L'ajust de la foto**: el marc és de 7 × 5,25 cm (4:3). Si la foto és més apaïsada que 4:3, se li fixa l'**amplada**; si no, l'**alçada**. L'altra mesura s'ajusta sola i la foto **no es deforma ni es retalla**, tot al contrari que `object-cover` de la web.
- **El disseny**: una taula de dos columnes (foto a l'esquerra, dades a la dreta) i una altra taula per a les dades. Els colors són els de la web: el taronja només de fons amb text negre.
- **`'DejaVu Sans'`** és una tipografia que dompdf porta incorporada i que té accents i símbols.
- El codi de barres ix de moment com a **número**; al punt 7 el convertim en barres.

### 6.5. Les vistes

La vista del PDF és un document HTML complet:

```bash
sail artisan make:view pdf.label
```

**Substituïx** el contingut de `resources/views/pdf/label.blade.php`:

```blade
<!DOCTYPE html>
<html lang="ca">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Etiqueta {{ $label->product }}</title>
    <style>
        @page { margin: 1cm; }
        body { margin: 0; }
    </style>
</head>
<body>
    <x-label-sheet :label="$label" />
</body>
</html>
```

I la de la previsualització usa el disseny de la web amb el mateix component:

```bash
sail artisan make:view panel.labels.preview
```

**Substituïx** el contingut de `resources/views/panel/labels/preview.blade.php`:

```blade
<x-layout>
    <x-slot:title>Previsualització · {{ $label->product }}</x-slot>

    <a href="{{ route('panel.labels.index') }}" class="text-ink/60 transition hover:text-brand">← Les meues etiquetes</a>

    <h1 class="mt-6 font-serif text-5xl font-semibold tracking-tight text-ink">Previsualització</h1>
    <div class="mt-6 h-1 w-16 rounded-full bg-accent"></div>
    <p class="mt-6 max-w-xl text-xl leading-relaxed text-ink/70">Així quedarà el PDF de l'etiqueta.</p>

    <div class="mt-10 overflow-x-auto">
        <x-label-sheet :label="$label" />
    </div>

    <x-button :href="route('panel.labels.pdf', ['label' => $label])" class="mt-8">Descarrega el PDF</x-button>
</x-layout>
```

El `overflow-x-auto` fa que, en una pantalla estreta, l'etiqueta (de 19 cm) es desplace en horitzontal en lloc de trencar la pàgina.

### 6.6. L'accés des del panell

A `resources/views/panel/labels/index.blade.php`, afig este enllaç al `footer` de la targeta, entre «Editar» i el formulari d'«Esborra»:

```blade
<a href="{{ route('panel.labels.preview', ['label' => $label]) }}" class="text-brand hover:underline">PDF</a>
```

### 6.7. Comprovar que funciona

1. A `composer.json` ha aparegut `barryvdh/laravel-dompdf` dins de `require`.
2. Al panell, fes clic a «PDF» en una etiqueta **amb foto**: s'obri la previsualització amb la foto, l'etiqueta de la categoria (taronja), les dades i el número del codi de barres.
3. Fes clic a «Descarrega el PDF»: es descarrega `etiqueta-<id>.pdf`. Obri'l: és una pàgina A4 en horitzontal **d'una sola pàgina** amb el mateix contingut. Comprova que els accents i els caràcters especials (à, é, ç, l·l) es veuen bé: si ix algun `?`, avisa'm.
4. Canvia la foto d'una etiqueta per una de **vertical** i una altra per una molt **apaïsada** i genera el PDF de cada una: la foto no es veu estirada ni aixafada.
5. Fes el PDF d'una etiqueta de mostra (sense foto): ix el requadre gris amb la inicial, sense errors.
6. Inicia sessió com a `nou@example.com` i obri `http://localhost/panell/etiquetes/{id}/pdf` i `.../previsualitzacio` amb l'`id` d'una etiqueta de `prova` (l'ixen a `/etiquetes/{id}` en passar el ratolí sobre «Veure»): error 403 en les dos.
7. Ix de la sessió i obri `http://localhost/panell/etiquetes/1/pdf`: et porta a `/accedir`.

## 7. El codi de barres

Ara substituïm el número del codi de barres per les **barres** de veritat, dibuixades amb la llibreria `picqer/php-barcode-generator`.

### 7.1. Instal·lar la llibreria

```bash
sail composer require picqer/php-barcode-generator
```

La llibreria dibuixa les barres com a imatge PNG, i per a això necessita l'extensió **GD** (o Imagick) de PHP. Comprova que la tens:

```bash
sail php -m
```

Entre les extensions ha d'aparéixer `gd` (o `imagick`).

### 7.2. Un codi de barres vàlid

Els codis EAN-13 (els de les caixes de fruita) tenen 13 xifres, i l'**última és de control**: es calcula a partir de les altres dotze i els escàners la fan servir per a detectar errors de lectura. La llibreria **es nega a dibuixar** un codi de 13 xifres amb la xifra de control incorrecta, i ara mateix el formulari només comprova que siguen 13 xifres. Un codi inventat, com `1234567890123`, trencaria la previsualització i el PDF.

Ho solucionem amb una **regla de validació pròpia**. Laravel la genera amb una comanda:

```bash
sail artisan make:rule Ean13
```

**Substituïx** el contingut de `app/Rules/Ean13.php`:

```php
<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class Ean13 implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! self::isValid((string) $value)) {
            $fail("El codi de barres no és vàlid: l'última xifra no coincidix amb les anteriors.");
        }
    }

    public static function isValid(string $code): bool
    {
        if (! preg_match('/^\d{13}$/', $code)) {
            return false;
        }

        $sum = 0;

        foreach (str_split(substr($code, 0, 12)) as $i => $digit) {
            $sum += (int) $digit * ($i % 2 === 0 ? 1 : 3);
        }

        return (10 - $sum % 10) % 10 === (int) $code[12];
    }
}
```

- **`validate()`** és l'únic mètode que Laravel exigix a una regla: rep el nom del camp, el valor i una funció `$fail` que cridem, amb el missatge, si el valor no és vàlid.
- **`isValid()`** conté el càlcul: multiplica les dotze primeres xifres per 1 i per 3 alternativament, i la xifra de control ha de completar la suma fins a la desena següent. És un mètode a part (i `static`) perquè el reutilitzarem fora de la validació.

A `app/Http/Requests/LabelRequest.php`, afig l'`use`:

```php
use App\Rules\Ean13;
```

I **substituïx** la regla de `barcode`:

```php
'barcode' => ['bail', 'required', 'digits:13', new Ean13],
```

**`bail`** fa que, si falla una regla (per exemple, `digits:13`), no s'avalue la resta: així `Ean13` només s'executa amb 13 xifres i l'usuari veu un sol error.

### 7.3. Etiquetes amb un codi invàlid

La regla protegix les etiquetes **noves**, però pot haver-hi etiquetes amb un codi invàlid guardat abans (o editat directament a la base de dades). Si intentem dibuixar-lo, la llibreria llança una excepció i l'usuari veuria una pàgina d'error tècnica. És millor **comprovar-ho abans** i portar-lo a l'edició de l'etiqueta, amb un missatge clar sota el camp.

**Substituïx** el contingut de `app/Http/Controllers/Panel/LabelPdfController.php`:

```php
<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Label;
use App\Rules\Ean13;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class LabelPdfController extends Controller
{
    public function show(Label $label): View|RedirectResponse
    {
        Gate::authorize('download', $label);

        if (! Ean13::isValid($label->barcode)) {
            return $this->invalidBarcode($label);
        }

        return view('panel.labels.preview', ['label' => $label->load('category')]);
    }

    public function download(Label $label): Response|RedirectResponse
    {
        Gate::authorize('download', $label);

        if (! Ean13::isValid($label->barcode)) {
            return $this->invalidBarcode($label);
        }

        return Pdf::loadView('pdf.label', ['label' => $label->load('category')])
            ->setPaper('a4', 'landscape')
            ->download('etiqueta-'.$label->id.'.pdf');
    }

    private function invalidBarcode(Label $label): RedirectResponse
    {
        return to_route('panel.labels.edit', ['label' => $label])
            ->withErrors(['barcode' => "El codi de barres d'esta etiqueta no és vàlid. Corregix-lo per a poder generar el PDF."]);
    }
}
```

- La comprovació va **després** de `Gate::authorize`: primer es mira que l'etiqueta siga teua.
- **`withErrors([...])`** en una redirecció fa que l'error isca sota el camp `barcode` del formulari d'edició, igual que els errors de validació.
- Redirigim a l'edició perquè és **on l'usuari pot arreglar-ho**.

::: info Més professional
Este tipus d'excepcions també es poden gestionar de manera global, a `bootstrap/app.php`, amb `withExceptions`. Ací ens quedem amb la comprovació al controlador, que és més fàcil de seguir.
:::

### 7.4. Les barres a l'etiqueta

A `resources/views/components/label-sheet.blade.php`, afig esta línia just baix de `@props(['label'])`:

```blade
@use('Picqer\Barcode\BarcodeGeneratorPNG')
```

Dins del bloc `@php`, afig al final:

```php
$barcode = base64_encode((new BarcodeGeneratorPNG())->getBarcode($label->barcode, BarcodeGeneratorPNG::TYPE_EAN_13, 3, 70));
```

I **substituïx** el `div` del codi de barres:

```blade
<div class="etq-barcode">
    <img src="data:image/png;base64,{{ $barcode }}" alt="Codi de barres {{ $label->barcode }}"><br>
    {{ $label->barcode }}
</div>
```

- **`getBarcode(codi, tipus, amplada, alçada)`** retorna la imatge PNG. `TYPE_EAN_13` és el tipus de codi; `3` és l'amplada de cada barra, en píxels, i `70` l'alçada.
- Com amb la foto, la imatge va **incrustada** en base64 perquè funcione igual al PDF i a la previsualització.
- El número es manté baix de les barres, com als productes reals.

::: info Més professional
Es pot generar el codi com a **SVG** (vectorial, més nítid en imprimir) i així no caldria GD. Ací usem PNG perquè és el mateix mecanisme que la foto i dompdf el tracta sense problemes.
:::

### 7.5. Comprovar que funciona

1. `sail php -m` mostra `gd` o `imagick`.
2. Obri la previsualització d'una etiqueta amb un codi vàlid: baix de les dades ixen les **barres** amb el número a sota.
3. Descarrega el PDF: les barres també hi ixen.
4. Si tens un escàner al mòbil, llig el codi des de la pantalla: dona les mateixes 13 xifres.
5. Edita una etiqueta i posa el codi `1234567890123`: no es guarda i ix «El codi de barres no és vàlid: l'última xifra no coincidix amb les anteriors.». Amb `1234567890128` sí que es guarda.
6. Posa `12345`: ix «El camp codi de barres ha de tindre 13 xifres.» i només eixe error.
7. Ara el cas de l'etiqueta amb un codi invàlid ja guardat: a phpMyAdmin, canvia el `barcode` d'una etiqueta teua a `1234567890123`. Al panell, fes clic a «PDF»: no ix cap error tècnic, sinó que et porta al formulari d'edició amb el missatge «El codi de barres d'esta etiqueta no és vàlid. Corregix-lo per a poder generar el PDF.» sota el camp. Corregix-lo, guarda, i la previsualització ja funciona.

## 8. Enviar el PDF per correu

L'usuari ja pot descarregar el PDF. Ara fem que també puga **rebre'l per correu**, amb l'etiqueta adjunta. Els correus no ixen de la teua màquina: els captura **Mailpit** (<http://localhost:8025>), que és la safata que vam veure al punt 1.

### 8.1. Una classe que genera el PDF

Ara el PDF es construirà en dos llocs (la descàrrega i el correu). Per no repetir el mateix codi, el movem a una classe pròpia. Laravel no té comanda d'`artisan` per a esta classe: crea la carpeta `app/Services` i, dins, el fitxer `LabelPdfGenerator.php`:

```php
<?php

namespace App\Services;

use App\Models\Label;
use Barryvdh\DomPDF\Facade\Pdf;
use Barryvdh\DomPDF\PDF as PdfDocument;

class LabelPdfGenerator
{
    public function make(Label $label): PdfDocument
    {
        return Pdf::loadView('pdf.label', ['label' => $label->loadMissing('category')])
            ->setPaper('a4', 'landscape');
    }
}
```

- **`loadMissing('category')`** carrega la categoria només si encara no està carregada.
- Importem la classe `PDF` amb l'àlies `PdfDocument` perquè PHP no distingix majúscules en els noms de classe i `Pdf` (la *facade*) i `PDF` (el document) es confondrien.

::: info Nota
Una **classe de servei** és una classe senzilla que agrupa una tasca (ací, «generar el PDF d'una etiqueta») perquè la puguen utilitzar diversos llocs. Laravel te la **injecta** sola si la demanes com a paràmetre d'un mètode del controlador, com la injecció de dependències que vam veure a la unitat 4 ([la classe `DBConnection` i la injecció de dependències](/contenidos/4-acces-a-dades#_7-la-classe-dbconnection-i-la-injeccio-de-dependencies)).
:::

### 8.2. El correu

```bash
sail artisan make:mail LabelPdfMail
```

**Substituïx** el contingut de `app/Mail/LabelPdfMail.php`:

```php
<?php

namespace App\Mail;

use App\Models\Label;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class LabelPdfMail extends Mailable
{
    public function __construct(public Label $label, protected string $pdf)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(subject: "Etiqueta de {$this->label->product}");
    }

    public function content(): Content
    {
        return new Content(view: 'mail.label-pdf');
    }

    public function attachments(): array
    {
        return [
            Attachment::fromData(fn () => $this->pdf, 'etiqueta-'.$this->label->id.'.pdf')
                ->withMime('application/pdf'),
        ];
    }
}
```

::: info Nota
Cada tipus de correu és una classe, un **Mailable**. Té tres parts: l'**assumpte** (`envelope`), el **cos** (`content`, que apunta a una vista Blade) i els **adjunts** (`attachments`). Les propietats `public` (ací `$label`) arriben soles a la vista del correu.
:::

- **`Attachment::fromData`** adjunta un PDF que tenim **en memòria**: no cal guardar cap arxiu al disc.
- Hem llevat `Queueable` i `SerializesModels` que genera la comanda, perquè enviarem el correu directament.

I la vista del correu, `resources/views/mail/label-pdf.blade.php` (crea-la amb `sail artisan make:view mail.label-pdf`):

```blade
<p>Hola!</p>

<p>Ací tens adjunt el PDF de l'etiqueta de <strong>{{ $label->product }}</strong>.</p>

<p>{{ config('app.name') }}</p>
```

Sense Tailwind: els clients de correu no el carreguen.

### 8.3. La ruta

A `routes/web.php`, dins del grup `panell`, afig la ruta, que és un `POST` perquè **fa una acció**:

```php
Route::post('etiquetes/{label}/pdf/enviar', [Panel\LabelPdfController::class, 'send'])->name('labels.mail');
```

### 8.4. El controlador

**Substituïx** el contingut de `app/Http/Controllers/Panel/LabelPdfController.php`:

```php
<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Mail\LabelPdfMail;
use App\Models\Label;
use App\Rules\Ean13;
use App\Services\LabelPdfGenerator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use Throwable;

class LabelPdfController extends Controller
{
    public function show(Label $label): View|RedirectResponse
    {
        Gate::authorize('download', $label);

        if (! Ean13::isValid($label->barcode)) {
            return $this->invalidBarcode($label);
        }

        return view('panel.labels.preview', ['label' => $label->load('category')]);
    }

    public function download(Label $label, LabelPdfGenerator $generator): Response|RedirectResponse
    {
        Gate::authorize('download', $label);

        if (! Ean13::isValid($label->barcode)) {
            return $this->invalidBarcode($label);
        }

        return $generator->make($label)->download('etiqueta-'.$label->id.'.pdf');
    }

    public function send(Label $label, LabelPdfGenerator $generator): RedirectResponse
    {
        Gate::authorize('download', $label);

        if (! Ean13::isValid($label->barcode)) {
            return $this->invalidBarcode($label);
        }

        $user = Auth::user();

        try {
            Mail::to($user)->send(new LabelPdfMail($label, $generator->make($label)->output()));
        } catch (Throwable $e) {
            report($e);

            return to_route('panel.labels.preview', ['label' => $label])
                ->with('status', "No s'ha pogut enviar el correu. Torna-ho a provar més tard.");
        }

        return to_route('panel.labels.preview', ['label' => $label])
            ->with('status', "T'hem enviat el PDF a {$user->email}.");
    }

    private function invalidBarcode(Label $label): RedirectResponse
    {
        return to_route('panel.labels.edit', ['label' => $label])
            ->withErrors(['barcode' => "El codi de barres d'esta etiqueta no és vàlid. Corregix-lo per a poder generar el PDF."]);
    }
}
```

- **`Mail::to($user)->send(...)`** envia el correu a l'usuari que ha iniciat sessió, que és l'autor de l'etiqueta (hem comprovat el permís abans).
- **`output()`** dona els bytes del PDF; el Mailable els adjunta.
- **`try/catch`**: enviar un correu pot fallar per raons alienes a nosaltres (servidor de correu caigut, xarxa...). Si falla, **`report($e)`** guarda l'error al registre (`storage/logs/laravel.log`) i l'usuari veu un missatge normal, no una pàgina d'error.
- **Síncron**: l'usuari espera uns segons mentre s'envia, i a canvi sabem si ha anat bé.

::: info Més professional
En producció els correus se solen enviar **en cua** (`Mail::to(...)->queue(...)`): l'usuari no espera, i un procés apart (`queue:work`) els va enviant. El projecte ja té la cua configurada a la base de dades (`QUEUE_CONNECTION=database`), però ací ens quedem amb l'enviament directe, que és més fàcil de seguir.
:::

### 8.5. El botó

A `resources/views/panel/labels/preview.blade.php` afig `<x-flash />` baix de la línia `<x-slot:title>` (perquè el missatge isca en esta pàgina) i **substituïx** el botó de descàrrega pels dos botons:

```blade
<x-flash />
```

```blade
<div class="mt-8 flex flex-wrap items-center gap-4">
    <x-button :href="route('panel.labels.pdf', ['label' => $label])">Descarrega el PDF</x-button>

    <form method="POST" action="{{ route('panel.labels.mail', ['label' => $label]) }}">
        @csrf
        <x-button type="submit">Envia-me el PDF</x-button>
    </form>
</div>
```

### 8.6. Comprovar que funciona

1. Al panell, fes clic a «PDF» en una etiqueta teua i, a la previsualització, a «Envia-me el PDF»: tornes a la mateixa pàgina amb la franja taronja «T'hem enviat el PDF a prova@example.com.».
2. Obri <http://localhost:8025>: hi ha un correu nou per a `prova@example.com` amb l'assumpte «Etiqueta de ...» i un adjunt `etiqueta-<id>.pdf`. Obri l'adjunt: és el mateix PDF que el de la descàrrega.
3. «Descarrega el PDF» continua funcionant igual que abans.
4. Provem un error d'enviament. Al `.env`, canvia `MAIL_PORT=1025` per `MAIL_PORT=1026` i reinicia amb `sail restart`. Fes clic a «Envia-me el PDF»: ix la franja «No s'ha pogut enviar el correu. Torna-ho a provar més tard.», sense cap pàgina d'error tècnica. A `storage/logs/laravel.log` hi ha una entrada nova amb l'error.
5. Torna a posar `MAIL_PORT=1025`, reinicia amb `sail restart` i comprova que l'enviament funciona altra vegada.

## 9. Sessions i galetes

Ja hem utilitzat la sessió per a l'autenticació i per als missatges flash, però fins ara era una caixa negra. En este punt mirem **què hi ha dins** i, després, creem una **galeta pròpia**.

### 9.1. La galeta de la sessió

1. Inicia sessió amb `prova@example.com`.
2. Obri les eines de desenvolupador (`F12`) → pestanya *Application* (a Firefox, *Storage*) → *Cookies* → `http://localhost`.

Hi trobaràs, entre altres, esta galeta:

| Camp | Valor | Per què |
|---|---|---|
| Nom | `etiquetes-session` | `config/session.php` el forma amb el nom de l'aplicació (`APP_NAME`) i `-session` |
| Valor | un text llarg i il·legible | Laravel el **xifra** |
| `HttpOnly` | marcat | JavaScript no pot llegir-la (`http_only`) |
| `SameSite` | `Lax` | no s'envia en la majoria de peticions que vénen d'altres llocs (`same_site`) |
| `Secure` | sense marcar | només s'exigiria amb HTTPS (`SESSION_SECURE_COOKIE`) |
| Caducitat | 120 minuts d'inactivitat | `SESSION_LIFETIME` |

També hi apareix `XSRF-TOKEN`, que forma part de la protecció CSRF i que ara no necessitem.

::: info Nota
Una **galeta** (*cookie*) és un text xicotet que el servidor demana al navegador que guarde, i que el navegador **torna a enviar en cada petició** al mateix lloc. És la manera que té el servidor de reconéixer-te entre una pàgina i la següent, perquè HTTP no recorda res.
:::

La galeta **no conté ni el teu nom ni el teu correu**: només un **identificador** de la sessió. Les dades viuen al servidor.

### 9.2. On viuen les dades

El teu `.env` té `SESSION_DRIVER=database`: la sessió es guarda a la taula `sessions` de la base de dades. Obri-la a phpMyAdmin:

| Columna | Què guarda |
|---|---|
| `id` | l'identificador de la sessió |
| `user_id` | l'usuari que ha iniciat sessió (és buit per als convidats) |
| `ip_address`, `user_agent` | l'adreça i el navegador de l'última petició |
| `payload` | les **dades** de la sessió, codificades en base64 |
| `last_activity` | l'última vegada que es va usar |

Per a veure el `payload` descodificat, dins de `sail artisan tinker`:

```php
json_decode(base64_decode(Illuminate\Support\Facades\DB::table('sessions')->whereNotNull('user_id')->orderByDesc('last_activity')->value('payload')), true)
```

Entre altres claus, hi ixen:

- **`_token`**: el token CSRF que posa `@csrf` als formularis.
- **`login_web_...`** (amb un número llarg al final): és on `Auth` guarda **qui ets**, amb el teu `id`.
- **`_flash`**: els missatges flash pendents (apartat següent).

Les dades les llig `json_decode` perquè `config/session.php` guarda la sessió en JSON (`serialization`). I es poden llegir perquè `SESSION_ENCRYPT=false`: amb `true`, Laravel xifraria també el contingut de la sessió.

En PHP pur, `session_start()` i `$_SESSION` fan el mateix: una galeta amb un identificador i les dades guardades al servidor. Laravel ho gestiona per nosaltres i permet canviar on es guarden (base de dades, arxius, Redis...) sense tocar el codi.

### 9.3. Com funcionen els missatges flash

Quan escrivim `->with('status', '...')` en una redirecció, Laravel guarda el missatge a la sessió com a **flash**, i `<x-flash />` el llig. El cicle és este:

1. En la petició que redirigix, el missatge es guarda a `_flash.new`.
2. En la **petició següent** (la pàgina de destiació), el missatge es pot llegir i passa a `_flash.old`.
3. Quan acaba eixa petició, s'esborra.

Per això el missatge ix **una sola vegada**: si recarregues la pàgina, ja no hi és.

### 9.4. Una galeta pròpia: recordar el filtre

Volem que la portada **recorde l'última categoria triada**, també per als visitants que no han iniciat sessió i encara que tanquen el navegador. Una galeta és la millor eina per a això:

| | Sessió | Galeta pròpia |
|---|---|---|
| On es guarda | al servidor (taula `sessions`) | al navegador |
| Què viatja | només l'identificador | el valor mateix |
| Dura | fins a 120 minuts sense activitat | el temps que li diguem |

Ho resolem a `app/Http/Controllers/HomeController.php`. **Substituïx** el contingut:

```php
<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Label;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(Request $request): View
    {
        $categoryId = $this->categoryFilter($request);

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

    private function categoryFilter(Request $request): int
    {
        if (! $request->has('category')) {
            return (int) $request->cookie('home_category');
        }

        $categoryId = $request->integer('category');

        if ($categoryId) {
            Cookie::queue('home_category', $categoryId, 60 * 24 * 30);
        } else {
            Cookie::expire('home_category');
        }

        return $categoryId;
    }
}
```

La lògica és esta:

- **Si l'adreça no du `category`**, s'utilitza la categoria de la galeta (si no n'hi ha, `0`, que vol dir «totes»).
- **Si l'adreça du `category`**, mana l'adreça: es guarda la categoria a la galeta. I si ve **buit** (`?category=`), vol dir «totes» i s'esborra la galeta. `has()` és `true` encara que el valor estiga buit.
- **`Cookie::queue(nom, valor, minuts)`** afig la galeta a la resposta: ací dura 30 dies (`60 * 24 * 30` minuts). **`Cookie::expire(nom)`** la fa caducar.
- **`$request->cookie('home_category')`** llig la galeta que envia el navegador. Laravel la xifra en guardar-la i la desxifra en llegir-la.

A `resources/views/home.blade.php`, **substituïx** l'enllaç de «Neteja el filtre»:

```blade
<a href="{{ route('home', ['category' => '']) }}" class="pb-3 text-sm underline">Neteja el filtre</a>
```

Ara l'enllaç du `?category=` (buit) i esborra la galeta. Amb el `route('home')` d'abans, l'adreça no duria `category` i s'aplicaria la galeta: el filtre no s'aniria mai. L'opció «Totes les categories» del formulari ja envia `category=` buit.

::: warning Atenció
Una galeta viu al navegador de l'usuari: pot esborrar-la o manipular-la. Per això a la galeta només guardem una preferència i, quan la llegim, la convertim a nombre. **Mai** hi guardes dades sensibles ni decisions de seguretat.
:::

::: info Nota
Segons el que guarden, les galetes que no són imprescindibles poden obligar a **avisar l'usuari** i demanar-li el consentiment. Ací no ho fem, però una aplicació real hauria de revisar-ho.
:::

### 9.5. Comprovar que funciona

1. Amb la sessió iniciada, a les eines de desenvolupador comprova la galeta `etiquetes-session`: té `HttpOnly` i `SameSite` en `Lax`, i el valor és un text il·legible, sense el teu nom.
2. A phpMyAdmin, a la taula `sessions`, hi ha una fila amb el `user_id` del teu usuari.
3. Dins de `sail artisan tinker`, executa la línia de `json_decode`: apareixen les claus `_token`, `_flash` i una que comença per `login_web_`.
4. Ix de la sessió i torna a mirar la taula: ja no hi ha cap fila amb el teu `user_id`.
5. Crea una etiqueta nova: ix la franja taronja de «Etiqueta guardada correctament.». Recarrega la pàgina amb `F5`: **la franja ja no hi és**.
6. A la portada, tria una categoria i prem «Filtra»: l'adreça du `?category=` seguit del número, i a *Cookies* ix una galeta nova, `home_category`, amb una caducitat d'uns 30 dies i el valor xifrat (no es veu el número).
7. Escriu l'adreça `http://localhost` **sense** `?category=`: la portada continua filtrada i el selector mostra la categoria triada.
8. Fes clic a «Neteja el filtre»: l'adreça acaba en `?category=`, es veuen totes les etiquetes i la galeta `home_category` desapareix. Escriu `http://localhost` altra vegada: ja no hi ha filtre.
9. Obri una finestra privada i entra a `http://localhost`: no hi ha cap filtre, perquè la galeta és d'un altre navegador.
## 10. Errors comuns i punt de control

Hem tancat el projecte: ara cada etiqueta té foto, es pot descarregar en PDF, s'envia per correu i la portada recorda el filtre. Repassem què pot fallar i comprovem que tot funciona.

### 10.1. Errors comuns

#### Pujar fotos

| Símptoma | Causa | Solució |
|---|---|---|
| Ix «La foto ha de ser una imatge.» encara que la foto és correcta | Falta `enctype="multipart/form-data"` al `<form>`: el navegador només envia el nom de l'arxiu, no el contingut | Afig-lo a `<x-label-form>` |
| La foto es guarda però no es veu (imatge trencada) i la seua adreça du un port, per exemple `:8000` | `APP_URL` del `.env` no coincidix amb l'adreça que utilitzes al navegador | Posa `APP_URL=http://localhost` |
| La foto no es veu i l'adreça és correcta | Falta l'enllaç `public/storage` | `sail artisan storage:link` |
| «La foto no pot pesar més de 2 MB.» o «No s'ha pogut pujar la foto.» | La foto supera els 2 MB o el límit de PHP | Tria una foto més xicoteta |

#### PDF i codi de barres

| Símptoma | Causa | Solució |
|---|---|---|
| `Picqer\Barcode\Exceptions\InvalidCheckDigitException` | El codi de barres té 13 xifres, però l'última no concorda amb les anteriors (es va guardar abans de la regla `Ean13`) | Ara et porta a l'edició amb un missatge sota el camp: corregix el codi |
| Excepció de *lazy loading* en previsualitzar o descarregar | No s'ha carregat la categoria de l'etiqueta | `load('category')` al controlador o `loadMissing('category')` al generador |
| Error 403 en «PDF» o «Envia-me el PDF» | L'etiqueta no és teua: la Policy `download` només deixa passar l'autor | Es el comportament esperat |

#### Correu

| Símptoma | Causa | Solució |
|---|---|---|
| «No s'ha pogut enviar el correu. Torna-ho a provar més tard.» | Mailpit no està en marxa o `MAIL_HOST` i `MAIL_PORT` del `.env` són incorrectes | Mira l'error real a `storage/logs/laravel.log`; comprova que Mailpit està actiu (`sail up -d`) |

#### Sessions i galetes

| Símptoma | Causa | Solució |
|---|---|---|
| «Neteja el filtre» no neteja el filtre | L'enllaç va a `route('home')` sense `category`: s'aplica la galeta | Enllaça amb `route('home', ['category' => ''])` |
| El missatge flash no ix | Falta `<x-flash />` a la vista de **destí** (previsualització, portada, panell) | Afig-lo a eixa vista |

#### Classes no trobades

| Símptoma | Causa | Solució |
|---|---|---|
| `Class "App\Http\Controllers\Auth" not found` (o el mateix amb `Storage`, `Mail`, `Cookie`...) | Dins d'un `namespace`, les *facades* necessiten el seu `use` | Afig l'`use` (`use Illuminate\Support\Facades\Storage;`) |

### 10.2. Punt de control

Comprova que:

- [ ] `sail artisan storage:link` està executat i el `.env` té `APP_URL=http://localhost`.
- [ ] Pots crear una etiqueta amb foto i es veu al panell, a la portada i al detall.
- [ ] Una foto que no és JPG ni PNG, o que pesa més de 2 MB, es rebutja amb un missatge en valencià.
- [ ] Els arxius pujats tenen un nom aleatori, no el de l'ordinador de l'usuari.
- [ ] En editar, pots canviar la foto (l'antiga s'esborra) o deixar-la com està.
- [ ] En esborrar una etiqueta, el seu arxiu desapareix de `storage/app/public/labels/`.
- [ ] El PDF es descarrega amb foto, dades i codi de barres, en una sola pàgina.
- [ ] La previsualització mostra el mateix disseny que el PDF.
- [ ] Només l'autor pot veure, descarregar i enviar el PDF d'una etiqueta (la resta veu un 403).
- [ ] Un codi de barres de 13 xifres amb l'última xifra incorrecta no es guarda.
- [ ] «Envia-me el PDF» envia el correu amb l'adjunt (Mailpit) i, si falla, mostra un missatge sense pàgina d'error.
- [ ] La galeta `home_category` recorda el filtre de la portada i «Neteja el filtre» l'esborra.
- [ ] Saps explicar què hi ha a la galeta `etiquetes-session` i què hi ha a la taula `sessions`.

### 10.3. Arxius nous i modificats

```text
app/
├── Http/
│   ├── Controllers/
│   │   ├── HomeController.php            (modificat)
│   │   └── Panel/
│   │       ├── LabelController.php       (modificat)
│   │       └── LabelPdfController.php    (nou)
│   └── Requests/
│       └── LabelRequest.php              (modificat)
├── Mail/
│   └── LabelPdfMail.php                  (nou)
├── Models/
│   └── Label.php                         (modificat)
├── Policies/
│   └── LabelPolicy.php                   (modificat)
├── Rules/
│   └── Ean13.php                         (nou)
└── Services/
    └── LabelPdfGenerator.php             (nou)
resources/views/
├── components/
│   ├── label-form.blade.php              (modificat)
│   ├── label-photo.blade.php             (modificat)
│   └── label-sheet.blade.php             (nou)
├── home.blade.php                        (modificat)
├── mail/
│   └── label-pdf.blade.php               (nou)
├── panel/labels/
│   ├── index.blade.php                   (modificat)
│   └── preview.blade.php                 (nou)
└── pdf/
    └── label.blade.php                   (nou)
routes/web.php                            (modificat)
composer.json, composer.lock              (modificats: dompdf i picqer)
```

El `.env` també ha canviat (`APP_URL`), però Git no el versiona.

### 10.4. Arxius finals

Estos són els arxius que han canviat en més d'un punt del bloc, tal com han de quedar.

::: details routes/web.php

```php
<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\LabelController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\Panel;
use App\Http\Controllers\RegisterController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');

Route::get('/etiquetes/{label}', [LabelController::class, 'show'])
    ->whereNumber('label')
    ->name('labels.show');

Route::middleware('guest')->group(function () {
    Route::view('/accedir', 'auth.login')->name('login');
    Route::post('/accedir', [LoginController::class, 'authenticate'])->name('login.authenticate');
    Route::view('/registre', 'auth.register')->name('register');
    Route::post('/registre', [RegisterController::class, 'store'])->name('register.store');
});

Route::middleware('auth')->group(function () {
    Route::post('/eixir', [LoginController::class, 'logout'])->name('logout');

    Route::prefix('panell')->name('panel.')->group(function () {
        Route::redirect('/', '/panell/etiquetes')->name('index');

        Route::resource('etiquetes', Panel\LabelController::class)
            ->except(['show'])
            ->parameters(['etiquetes' => 'label'])
            ->names('labels');

        Route::get('etiquetes/{label}/previsualitzacio', [Panel\LabelPdfController::class, 'show'])->name('labels.preview');
        Route::get('etiquetes/{label}/pdf', [Panel\LabelPdfController::class, 'download'])->name('labels.pdf');
        Route::post('etiquetes/{label}/pdf/enviar', [Panel\LabelPdfController::class, 'send'])->name('labels.mail');
    });
});

Route::fallback(fn () => abort(404));
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
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class LabelController extends Controller
{
    public function index(): View
    {
        return view('panel.labels.index', [
            'labels' => Label::with('category')->where('user_id', Auth::id())->get(),
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
        $label = Auth::user()->labels()->create($request->safe()->except('photo'));

        $label->photo_path = $request->file('photo')->store('labels', 'public');
        $label->save();

        return to_route('panel.labels.index')->with('status', 'Etiqueta guardada correctament.');
    }

    public function edit(Label $label): View
    {
        Gate::authorize('update', $label);

        return view('panel.labels.edit', [
            'label' => $label,
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function update(LabelRequest $request, Label $label): RedirectResponse
    {
        Gate::authorize('update', $label);

        $label->update($request->safe()->except('photo'));

        if ($request->hasFile('photo')) {
            $oldPhoto = $label->photo_path;

            $label->photo_path = $request->file('photo')->store('labels', 'public');
            $label->save();

            if ($oldPhoto) {
                Storage::disk('public')->delete($oldPhoto);
            }
        }

        return to_route('panel.labels.index')->with('status', 'Etiqueta actualitzada correctament.');
    }

    public function destroy(Label $label): RedirectResponse
    {
        Gate::authorize('delete', $label);

        $label->delete();

        if ($label->photo_path) {
            Storage::disk('public')->delete($label->photo_path);
        }

        return to_route('panel.labels.index')->with('status', 'Etiqueta esborrada correctament.');
    }
}
```

:::

::: details app/Http/Requests/LabelRequest.php

```php
<?php

namespace App\Http\Requests;

use App\Rules\Ean13;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class LabelRequest extends FormRequest
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
            'category_id'  => ['required', 'exists:categories,id'],
            'product'      => ['required', 'string', 'max:255'],
            'variety'      => ['nullable', 'string', 'max:255'],
            'net_weight_g' => ['required', 'integer', 'min:1'],
            'caliber'      => ['nullable', 'string', 'max:255'],
            'origin'       => ['required', 'string', 'max:255'],
            'batch'        => ['required', 'string', 'max:255'],
            'packer'       => ['required', 'string', 'max:255'],
            'product_code' => ['required', 'string', 'max:255'],
            'barcode'      => ['bail', 'required', 'digits:13', new Ean13],
            'packaged_at'  => ['required', 'date'],
            'description'  => ['required', 'string'],
            'photo'        => [
                $this->isMethod('POST') ? 'required' : 'nullable',
                'image',
                'mimes:jpg,jpeg,png',
                'max:2048',
            ],
        ];
    }

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
            'photo'        => 'foto',
        ];
    }

    public function messages(): array
    {
        return [
            'required'       => 'El camp :attribute és obligatori.',
            'string'         => 'El camp :attribute ha de ser un text.',
            'max'            => 'El camp :attribute no pot tindre més de :max caràcters.',
            'integer'        => 'El camp :attribute ha de ser un nombre enter.',
            'min'            => 'El camp :attribute ha de ser com a mínim :min.',
            'digits'         => 'El camp :attribute ha de tindre :digits xifres.',
            'date'           => 'El camp :attribute ha de ser una data vàlida.',
            'exists'         => 'El valor triat per a :attribute no és vàlid.',
            'photo.image'    => 'La foto ha de ser una imatge.',
            'photo.mimes'    => 'La foto ha de ser un arxiu JPG o PNG.',
            'photo.max'      => 'La foto no pot pesar més de 2 MB.',
            'photo.uploaded' => "No s'ha pogut pujar la foto.",
        ];
    }
}
```

:::

::: details resources/views/components/label-form.blade.php

```blade
@props(['action', 'categories', 'submit', 'label' => null, 'method' => null])

<form method="POST" action="{{ $action }}" enctype="multipart/form-data" class="mt-10 grid gap-6 sm:grid-cols-2">
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
        @if ($label?->photo_path)
            <x-label-photo :label="$label" class="mb-4 max-w-xs" />
        @endif

        <x-input label="Foto del producte (JPG o PNG, màx. 2 MB)" name="photo" type="file" accept="image/jpeg,image/png" />
    </div>

    <div class="sm:col-span-2">
        <x-textarea label="Descripció" name="description" rows="4" :value="$label?->description" />
    </div>

    <div class="flex items-center gap-4 sm:col-span-2">
        <x-button type="submit">{{ $submit }}</x-button>
        <a href="{{ route('panel.labels.index') }}" class="text-sm underline">Cancel·la</a>
    </div>
</form>
```

:::

::: details app/Http/Controllers/Panel/LabelPdfController.php

```php
<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Mail\LabelPdfMail;
use App\Models\Label;
use App\Rules\Ean13;
use App\Services\LabelPdfGenerator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use Throwable;

class LabelPdfController extends Controller
{
    public function show(Label $label): View|RedirectResponse
    {
        Gate::authorize('download', $label);

        if (! Ean13::isValid($label->barcode)) {
            return $this->invalidBarcode($label);
        }

        return view('panel.labels.preview', ['label' => $label->load('category')]);
    }

    public function download(Label $label, LabelPdfGenerator $generator): Response|RedirectResponse
    {
        Gate::authorize('download', $label);

        if (! Ean13::isValid($label->barcode)) {
            return $this->invalidBarcode($label);
        }

        return $generator->make($label)->download('etiqueta-'.$label->id.'.pdf');
    }

    public function send(Label $label, LabelPdfGenerator $generator): RedirectResponse
    {
        Gate::authorize('download', $label);

        if (! Ean13::isValid($label->barcode)) {
            return $this->invalidBarcode($label);
        }

        $user = Auth::user();

        try {
            Mail::to($user)->send(new LabelPdfMail($label, $generator->make($label)->output()));
        } catch (Throwable $e) {
            report($e);

            return to_route('panel.labels.preview', ['label' => $label])
                ->with('status', "No s'ha pogut enviar el correu. Torna-ho a provar més tard.");
        }

        return to_route('panel.labels.preview', ['label' => $label])
            ->with('status', "T'hem enviat el PDF a {$user->email}.");
    }

    private function invalidBarcode(Label $label): RedirectResponse
    {
        return to_route('panel.labels.edit', ['label' => $label])
            ->withErrors(['barcode' => "El codi de barres d'esta etiqueta no és vàlid. Corregix-lo per a poder generar el PDF."]);
    }
}
```

:::

::: details resources/views/panel/labels/preview.blade.php

```blade
<x-layout>
    <x-slot:title>Previsualització · {{ $label->product }}</x-slot>
    <x-flash />

    <a href="{{ route('panel.labels.index') }}" class="text-ink/60 transition hover:text-brand">← Les meues etiquetes</a>

    <h1 class="mt-6 font-serif text-5xl font-semibold tracking-tight text-ink">Previsualització</h1>
    <div class="mt-6 h-1 w-16 rounded-full bg-accent"></div>
    <p class="mt-6 max-w-xl text-xl leading-relaxed text-ink/70">Així quedarà el PDF de l'etiqueta.</p>

    <div class="mt-10 overflow-x-auto">
        <x-label-sheet :label="$label" />
    </div>

    <div class="mt-8 flex flex-wrap items-center gap-4">
        <x-button :href="route('panel.labels.pdf', ['label' => $label])">Descarrega el PDF</x-button>

        <form method="POST" action="{{ route('panel.labels.mail', ['label' => $label]) }}">
            @csrf
            <x-button type="submit">Envia-me el PDF</x-button>
        </form>
    </div>
</x-layout>
```

:::

::: details resources/views/components/label-sheet.blade.php

```blade
@props(['label'])
@use('Picqer\Barcode\BarcodeGeneratorPNG')

@php
    $disk = Storage::disk('public');
    $photo = null;

    if ($label->photo_path && $disk->exists($label->photo_path)) {
        $bytes = $disk->get($label->photo_path);
        [$width, $height] = getimagesizefromstring($bytes);

        $photo = 'data:'.$disk->mimeType($label->photo_path).';base64,'.base64_encode($bytes);
        $fit = $width / $height > 4 / 3 ? 'width: 7cm;' : 'height: 5.25cm;';
    }

    $barcode = base64_encode((new BarcodeGeneratorPNG())->getBarcode($label->barcode, BarcodeGeneratorPNG::TYPE_EAN_13, 3, 70));
@endphp

<style>
    .etq { width: 19cm; padding: 0.8cm; border: 2px solid #005b97; background: #fff; color: #000; font-family: 'DejaVu Sans', sans-serif; }
    .etq table { width: 100%; border-collapse: collapse; }
    .etq .etq-left { width: 7.4cm; vertical-align: top; }
    .etq .etq-right { padding-left: 0.6cm; vertical-align: top; }
    .etq .etq-ph { width: 7cm; height: 5.25cm; background: #eee; color: #bbb; font-size: 60pt; line-height: 5.25cm; text-align: center; }
    .etq .etq-cat { padding: 2px 8px; background: #fbad18; font-size: 9pt; }
    .etq h1 { margin: 10px 0 0 0; font-size: 24pt; }
    .etq .etq-var { margin: 2px 0 10px 0; color: #555; font-size: 13pt; }
    .etq .etq-data td { padding: 5px 0; border-bottom: 1px solid #ddd; font-size: 11pt; }
    .etq .etq-key { width: 4cm; color: #555; }
    .etq .etq-barcode { margin-top: 0.6cm; font-size: 11pt; letter-spacing: 2px; }
</style>

<div class="etq">
    <table>
        <tr>
            <td class="etq-left">
                @if ($photo)
                    <img src="{{ $photo }}" alt="Foto de {{ $label->product }}" style="{{ $fit }}">
                @else
                    <div class="etq-ph">{{ mb_strtoupper(mb_substr($label->product, 0, 1)) }}</div>
                @endif
            </td>
            <td class="etq-right">
                <span class="etq-cat">{{ $label->category->name }}</span>
                <h1>{{ $label->product }}</h1>
                @if ($label->variety)
                    <p class="etq-var">{{ $label->variety }}</p>
                @endif

                <table class="etq-data">
                    <tr><td class="etq-key">Pes net</td><td>{{ number_format($label->net_weight_g, 0, ',', '.') }} g</td></tr>
                    @if ($label->caliber)
                        <tr><td class="etq-key">Calibre</td><td>{{ $label->caliber }}</td></tr>
                    @endif
                    <tr><td class="etq-key">Origen</td><td>{{ $label->origin }}</td></tr>
                    <tr><td class="etq-key">Lot</td><td>{{ $label->batch }}</td></tr>
                    <tr><td class="etq-key">Envasador</td><td>{{ $label->packer }}</td></tr>
                    <tr><td class="etq-key">Codi de producte</td><td>{{ $label->product_code }}</td></tr>
                    <tr><td class="etq-key">Envasat el</td><td>{{ $label->packaged_at->format('d/m/Y') }}</td></tr>
                </table>

                <div class="etq-barcode">
                    <img src="data:image/png;base64,{{ $barcode }}" alt="Codi de barres {{ $label->barcode }}"><br>
                    {{ $label->barcode }}
                </div>
            </td>
        </tr>
    </table>
</div>
```

:::