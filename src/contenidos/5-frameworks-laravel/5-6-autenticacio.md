# Bloc 6. Autenticació i autorització

## Temporalització

3 hores (180 min).

## 1. Com sap Laravel qui eres

Ara mateix el panell és públic: qualsevol pot editar i esborrar qualsevol etiqueta. Per a arreglar-ho, l'aplicació ha de saber **qui fa cada petició**. Hi ha dos problemes diferents:

| | Pregunta | Exemple a l'app |
|---|---|---|
| **Autenticació** | Qui eres? | Iniciar sessió amb correu i contrasenya |
| **Autorització** | Què pots fer? | Editar només les teues etiquetes |

Este bloc resol primer l'autenticació (punts 2 a 8) i després l'autorització (punts 9 i 10).

### 1.1. El problema: HTTP no recorda

Cada petició HTTP és independent: el servidor no sap que la de fa un segon i esta són de la mateixa persona. La solució és la **sessió**: en iniciar sessió, el servidor guarda **qui eres** i li dóna al navegador una **cookie** amb un identificador. El navegador la torna en cada petició i així el servidor et reconeix.

A PHP pur ho fas a mà:

```php
session_start();
$_SESSION['user_id'] = $user['id']; // en iniciar sessió
```

Laravel fa el mateix, però amb la facade `Auth` i sense tocar `$_SESSION`.

::: info Nota: què és una *facade*?
Una *facade* és una classe amb mètodes estàtics (`Auth::attempt()`, `Route::get()`) que fa de drecera cap a un objecte real que Laravel gestiona per dins. Per això pots escriure `Auth::user()` sense fer `new` ni passar-la per paràmetre. A la unitat 4 passaves la connexió a les classes per [injecció de dependències](/contenidos/4-acces-a-dades#_7-la-classe-dbconnection-i-la-injeccio-de-dependencies): és l'altra manera d'arribar a un objecte, més explícita. Les facades viuen a `Illuminate\Support\Facades`, i per això cal l'`use` a dalt de l'arxiu.
:::

### 1.2. El recorregut d'un inici de sessió

1. Envies el correu i la contrasenya des del formulari.
2. Laravel busca l'usuari pel correu i **compara la contrasenya amb el *hash*** guardat.
3. Si coincidix, guarda l'`id` de l'usuari **a la sessió** (al servidor) i envia al navegador una cookie amb l'identificador de la sessió.
4. En cada petició següent el navegador torna la cookie, Laravel recupera la sessió i, d'ahí, l'usuari.

::: warning Atenció
La cookie **no du les teues dades**, només l'identificador de la sessió. Però qui la tinga **és tu** per al servidor. Per això, en iniciar sessió, Laravel genera un identificador nou (ho farem al punt 2).
:::

### 1.3. Les peces

| Peça | Què fa | A la nostra app |
|---|---|---|
| ***Guard*** | Com es manté l'usuari autenticat entre peticions | `web`: sessió i cookie |
| ***Provider*** | Com es busca l'usuari | `users`: el model `App\Models\User` (Eloquent) |
| **Facade `Auth`** | El punt d'accés des del nostre codi | `Auth::attempt()`, `Auth::user()`... |

Tot això està configurat a `config/auth.php` i **no hi has de tocar res**: ja ve preparat per a una aplicació com la nostra.

### 1.4. Què farem servir

| Mètode | Què fa | Punt |
|---|---|---|
| `Auth::attempt([...])` | Comprova credencials i, si són correctes, inicia sessió | 2 |
| `Auth::logout()` | Tanca la sessió de l'usuari | 3 |
| `Auth::login($user)` | Inicia sessió amb un usuari que ja tenim | 4 |
| `Auth::check()` | `true` si hi ha un usuari autenticat | 7 |
| `Auth::user()` i `$request->user()` | L'usuari autenticat | 7 i 8 |

### 1.5. Comprovar que funciona

1. Obri <http://localhost/> amb les eines de desenvolupador (`F12`) → pestanya *Application* (a Firefox, *Storage*) → *Cookies*.
2. Hi ha una cookie el nom de la qual acaba en `-session`. El seu valor és només l'identificador de la sessió, sense cap dada teua.

## 2. Iniciar sessió

Ara el formulari de `/accedir` és només una maqueta (`action="#"`). Li donem vida amb tres peces: una ruta `POST`, un `LoginRequest` i un controlador.

### 2.1. La ruta

A `routes/web.php`, afig l'`use` i la ruta `POST`, al costat de la `GET` que ja existix. Són la mateixa URL amb verb distint, com passa amb `/registre`:

```php
use App\Http\Controllers\LoginController;

Route::view('/accedir', 'auth.login')->name('login');
Route::post('/accedir', [LoginController::class, 'authenticate'])->name('login.authenticate');
```

::: info Nota
La ruta `GET` ha de dir-se **`login`**: és el nom que Laravel busca quan envia a algú a iniciar sessió (ho vorem al punt 6).
:::

### 2.2. El `LoginRequest`

Crea la classe:

```bash
sail artisan make:request LoginRequest
```

Com al bloc 5, el generador deixa `authorize()` retornant `false`, i això donaria un error 403 en enviar el formulari. Canvia-ho a `true`. L'arxiu queda així:

```php
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'email'],
            'password' => ['required'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'El correu electrònic és obligatori.',
            'email.email' => 'Escriu un correu electrònic vàlid.',
            'password.required' => 'La contrasenya és obligatòria.',
        ];
    }
}
```

Només validem que **hi ha** un correu i una contrasenya. Si són correctes o no ho decidix `Auth::attempt()`.

Fixa't que **no** posem `min:8` a la contrasenya, com al registre. Al registre cal exigir una contrasenya decent, però ací no la jutgem: només comprovem que no vinga buida i deixem que `Auth::attempt()` la compare amb la guardada.


### 2.3. El controlador

Crea la clase per al controlador de Login:

```bash
sail artisan make:controller LoginController
```
A continuació definim la funció `authenticate` que s'encarregarà de validar l'usuari i contrasenya introduïts:

```php
<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function authenticate(LoginRequest $request): RedirectResponse
    {
        if (Auth::attempt($request->validated())) {
            $request->session()->regenerate();

            return redirect()->intended(route('panel.labels.index'))
                ->with('status', 'Sessió iniciada.');
        }

        return back()->withErrors([
            'email' => 'Les credencials no coincidixen amb els nostres registres.',
        ])->onlyInput('email');
    }
}
```

Què fa cada part i per què:

| Línia | Què fa | Per què |
|---|---|---|
| `Auth::attempt($request->validated())` | Busca l'usuari per `email`, **xifra** la contrasenya enviada i la compara amb el *hash* guardat | No xifres tu la contrasenya: ho fa el framework. Si és correcta, inicia la sessió i retorna `true` |
| `session()->regenerate()` | Canvia l'identificador de la sessió | Evita que algú es quede amb un identificador que ja circulava abans d'iniciar sessió (*session fixation*) |
| `redirect()->intended(...)` | Va a la pàgina que volies visitar abans d'haver d'iniciar sessió; si no n'hi ha, a la ruta que li passem | Fins al punt 6 sempre anirà a la ruta per defecte |
| `withErrors([...])` | L'error cau al camp `email`, on `<x-input>` el mostra | Reutilitzem el mateix mecanisme de la validació |
| `onlyInput('email')` | Només torna a omplir el correu en el formulari | La contrasenya **mai** es torna a omplir |

::: tip Bona pràctica
El missatge d'error és el **mateix** tant si el correu no existix com si la contrasenya és incorrecta. Així ningú pot descobrir quins correus estan registrats.
:::

### 2.4. El formulari

A `resources/views/auth/login.blade.php`, el formulari necessita l'adreça de la ruta i el token `@csrf`. Canvia l'obertura del `<form>` i afig la línia nova:

```blade
<form method="POST" action="{{ route('login.authenticate') }}" class="mt-10 space-y-6">
    @csrf
    {{-- ...els dos <x-input> i el botó, igual que abans... --}}
```

Els `<x-input>` no canvien: ja mostren `@error` i repoblen amb `old()`.

::: info Més professional
Dos afegits que no fem: la casella «Recorda'm» (`Auth::attempt($credentials, $remember)`) i limitar els intents fallits. Els kits oficials de Laravel porten ja eixa limitació; ací no, i es faria amb el *rate limiting* de les rutes.
:::


El `@csrf` afig al formulari un camp ocult amb un token que Laravel comprova en rebre el `POST`. Així el servidor sap que la petició ix d'un formulari de la nostra pàgina i no d'una altra web.

### 2.5. Comprovar que funciona

1. Obri <http://localhost/accedir> i envia el formulari **buit**: apareixen els missatges de `LoginRequest` baix cada camp.
2. Escriu `prova@example.com` amb una contrasenya incorrecta: tornes a `/accedir`, amb l'error `Les credencials no coincidixen amb els nostres registres.` baix del correu, **el correu omplit** i la contrasenya **buida**.
3. Prova amb un correu que no existix (`altre@example.com`): mostra **el mateix** error.
4. Escriu `prova@example.com` i `password`: et porta a `/panell/etiquetes` amb la franja taronja **«Sessió iniciada.»**.

La barra no canvia encara: que has iniciat sessió es nota de veres al punt 7.

## 3. Eixir

Iniciar sessió és la meitat del camí: cal poder **tancar-la**. Ara hem d'afegir un mètode al `LoginController`, una ruta i un botó.

### 3.1. La ruta

A `routes/web.php`, junt a les rutes d'accés:

```php
Route::post('/eixir', [LoginController::class, 'logout'])->name('logout');
```

Ha de ser `POST` i no `GET`. Si fora un enllaç, qualsevol altra pàgina (o una imatge) podria tancar-te la sessió només amb fer que el teu navegador cride a eixa adreça. Amb `POST` i `@csrf`, només ho pot fer un formulari de la nostra pàgina.

### 3.2. El mètode `logout()`

Al `LoginController`, afig l'`use` de `Request` i el mètode nou (l'`authenticate()` no canvia):

```php
use Illuminate\Http\Request;

public function logout(Request $request): RedirectResponse
{
    Auth::logout();

    $request->session()->invalidate();

    $request->session()->regenerateToken();

    return to_route('home')->with('status', 'Has eixit.');
}
```

Són tres passos i cada un fa una cosa distinta:

| Línia | Què fa | Per què |
|---|---|---|
| `Auth::logout()` | Lleva l'usuari de la sessió | A partir d'ací les peticions ja no estan autenticades |
| `session()->invalidate()` | Esborra **totes** les dades de la sessió i en genera una de nova | Que no es quede res de la sessió anterior, ni l'identificador |
| `session()->regenerateToken()` | Genera un token CSRF nou | El token antic pertanyia a la sessió que acabem d'esborrar |

Per poder mostrar el missatge d'status a `home` cal afegir `<x-flash />` en `home.blade.php`, just baix del `<x-slot>`.

### 3.3. El botó «Ix» a la barra

Per a poder provar-ho necessitem un botó. A `resources/views/components/layout.blade.php`, afig al final del `<div class="flex items-center gap-6">` de la barra:

```blade
@auth
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="cursor-pointer text-ink/70 transition hover:text-brand">Ix</button>
    </form>
@endauth
```

`@auth` és una directiva de Blade: el que hi ha a dins **només es dibuixa si hi ha un usuari autenticat**, i internament consulta el mateix que `Auth::check()`. Un botó dins d'un formulari és el que fa falta, perquè `POST` no es pot fer amb un enllaç.

Per ara la barra **no és completa**: «Accedeix» i «Les meues etiquetes» es continuen veient encara que hages iniciat sessió. Ho arreglem al punt 7.

### 3.4. Comprovar que funciona

1. Obri <http://localhost/> sense haver iniciat sessió: **no** hi ha el botó «Ix».
2. Inicia sessió a `/accedir` amb `prova@example.com` i `password`: ara, a la barra, apareix **«Ix»**.
3. Fes-hi clic: arribes a `/accedir` amb la franja taronja **«Has eixit.»** i el botó «Ix» ja no es veu.
4. Escriu <http://localhost/eixir> a la barra d'adreces: no et tanca la sessió, perquè la ruta només admet `POST`. Dóna un error de mètode no permés (405).


## 4. Registrar-se i entrar directament

Ara mateix, qui es registra arriba a `/accedir` i ha d'escriure de nou les mateixes dades. Millorem l'experiència: en acabar el registre, l'usuari **ja té la sessió iniciada** i va directe al panell.

### 4.1. `Auth::login()`: iniciar sessió sense contrasenya

Al punt 2 vam usar `Auth::attempt()`, que **comprova** unes credencials. Ací ja sabem qui és l'usuari: acabem de crear-lo. Per a eixe cas, Laravel té `Auth::login($user)`, que inicia la sessió amb un usuari que ja tenim, sense comprovar res.

| Mètode | Quan | Comprova la contrasenya |
|---|---|---|
| `Auth::attempt([...])` | Formulari d'accés | Sí |
| `Auth::login($user)` | Just després de crear l'usuari | No |

::: danger Error crític
`Auth::login()` **no verifica res**. Només s'ha d'usar amb un usuari que has creat o comprovat tu al servidor, mai amb un usuari que ve d'un camp del formulari: si fora així, qualsevol podria entrar com qualsevol.
:::

### 4.2. El `RegisterController`

A `app/Http/Controllers/RegisterController.php`, afig l'`use` de `Auth` i **substituïx el cos de `store()`**:

```php
use Illuminate\Support\Facades\Auth;

public function store(RegisterRequest $request): RedirectResponse
{
    $user = User::create($request->validated());

    Auth::login($user);

    $request->session()->regenerate();

    return to_route('panel.labels.index')
        ->with('status', 'Compte creat correctament. Ja has iniciat sessió.');
}
```

Què ha canviat i per què:

- **`$user = User::create(...)`**: abans no guardàvem el resultat. Ara el necessitem, perquè és l'objecte que li passem a `Auth::login()`. La contrasenya continua xifrant-se sola gràcies al *cast* `hashed` del model.
- **`Auth::login($user)`**: deixa l'usuari autenticat a la sessió.
- **`session()->regenerate()`**: igual que al punt 2, canviem l'identificador de la sessió en el moment en què l'usuari passa d'invitat a autenticat.
- **La redirecció** va al panell, no a `login`, i el missatge ja no diu «ja pots accedir», perquè ja ho ha fet.

::: info Nota
Este canvi substituïx el comportament que vam deixar al bloc 5. El missatge d'abans ja no s'utilitza, i la vista `login` ja no rep ningun missatge de registre.
:::

### 4.3. Comprovar que funciona

1. Obri <http://localhost/registre> i crea un compte nou (`nou@example.com`, contrasenya de més de 8 caràcters repetida): arribes a `/panell/etiquetes` amb la franja **«Compte creat correctament. Ja has iniciat sessió.»** i la barra mostra el botó **«Ix»**: la sessió està iniciada.
2. Fes clic a «Ix» i inicia sessió a `/accedir` amb eixe mateix correu i contrasenya: entra.
3. Torna a registrar-te amb `prova@example.com`: apareix l'error del correu repetit i **no** s'inicia cap sessió (no hi ha «Ix»).
4. A phpMyAdmin, taula `users`: la fila nova té la contrasenya **xifrada**, no en text pla (comença per `$2y$`).


## 5. La trampa: un panell que «funciona»

Ja tenim login i registre. Ara el panell ha de tindre sentit: que cadascú **veja i guarde les seues etiquetes**. És una tasca ben acotada per a delegar-la en una IA, i el resultat sol funcionar a la primera. Però que funcione no vol dir que estiga ben protegit.

### 5.1. El que li demanem a la IA

```
Ja tenim login i registre amb `Auth`. Fes que el panell només mostre les etiquetes de l'usuari que ha iniciat sessió i que, en guardar una etiqueta, l'autor siga eixe usuari.
```
Este és el codi que et proposaria. A `app/Http/Controllers/Panel/LabelController.php`, afig l'`use` de `Auth` i **lleva** `use App\Models\User;` (ja no el necessitem). Després **substituïx** `index()` i `store()`:

```php
use Illuminate\Support\Facades\Auth;

public function index(): View
{
    return view('panel.labels.index', [
        'labels' => Label::with('category')->where('user_id', Auth::id())->get(),
    ]);
}

public function store(LabelRequest $request): RedirectResponse
{
    Auth::user()->labels()->create($request->validated());

    return to_route('panel.labels.index')->with('status', 'Etiqueta guardada correctament.');
}
```

Què fa:

- **`Auth::id()`** retorna l'`id` de l'usuari que ha iniciat sessió. El `where('user_id', ...)` limita el llistat a les seues etiquetes. El `with('category')` continua ahí perquè el *lazy loading* està prohibit.
- **`Auth::user()`** retorna l'usuari autenticat, i `labels()->create(...)` guarda l'etiqueta amb ell com a autor. Ja no hi ha l'usuari de prova provisional.

`edit()`, `update()` i `destroy()` **no canvien**.

### 5.2. Sembla que funciona

1. Inicia sessió amb `prova@example.com` i `password`, i obri «Les meues etiquetes»: només hi ixen les etiquetes que són seues.
2. Crea una etiqueta nova: apareix al llistat i, a la portada, amb el nom de `prova` com a autor.
3. Ix, inicia sessió amb `nou@example.com` (el compte que vas crear al punt 4) i obri el panell: **no** apareix l'etiqueta de `prova`.

Tot correcte. Ara busquem els forats.

### 5.3. Primer forat: sense sessió

1. Ix de la sessió.
2. Obri <http://localhost/panell/etiquetes/nova>: **el formulari s'obri**, encara que no hages iniciat sessió.
3. Ompli'l amb dades correctes i envia'l: error 500 amb `Call to a member function labels() on null`. `Auth::user()` retorna `null` si ningú ha iniciat sessió, i el codi no ho havia previst.

El panell és **públic**: ningú ha dit que calga haver iniciat sessió per a entrar.

### 5.4. Segon forat: l'`id` a l'URL

El llistat només mostra les teues etiquetes, i d'ahí sembla que només pots tocar les teues. Però les rutes d'editar i esborrar no comproven de qui és l'etiqueta.

1. Inicia sessió amb `prova`, crea una etiqueta («Prova trampa») i passa el ratolí per damunt d'«Editar»: l'adreça acaba en `/{id}/editar`. Apunta eixe número.
2. Ix i inicia sessió amb `nou@example.com`.
3. Escriu a la barra d'adreces `http://localhost/panell/etiquetes/{id}/editar`, amb l'`id` que has apuntat. **S'obri el formulari amb l'etiqueta de `prova`.** Canvia el producte i guarda: apareix la franja «Etiqueta actualitzada correctament.».
4. Obri <http://localhost/etiquetes/{id}>: l'etiqueta de `prova` té el producte que has escrit tu.
5. Ara l'esborrat. Crea una etiqueta amb `nou`, obri `F12`, fes clic dret sobre el formulari d'«Esborra» d'eixa etiqueta → *Edit as HTML*, i canvia l'`action` pel de l'etiqueta de `prova` (`/panell/etiquetes/{id}`). Envia: franja «Etiqueta esborrada correctament.» i <http://localhost/etiquetes/{id}> ja no existix.

Qualsevol usuari registrat pot **modificar i esborrar les etiquetes dels altres** només canviant un número a l'URL.

### 5.5. Què ha passat

| Forat | Per què passa | On l'arreglem |
|---|---|---|
| Sense sessió es pot entrar al panell | Les rutes no tenen cap filtre d'autenticació | Punt 6 |
| Es pot editar i esborrar etiquetes alienes | Ningú comprova qui és el propietari | Punts 9 i 10 |

La IA ha resolt el que li hem demanat (mostrar i guardar les teues) i ha deixat sense protegir el que no li hem dit. Ho ha fet tan bé que, si només proves el cas feliç, no ho notaries.

::: tip Bona pràctica
Amb codi d'autenticació generat per una IA, repassa tres preguntes: **qui pot arribar a esta ruta?** (cal sessió?), **de qui és este recurs?** (el comprova alguna cosa?) i **què passa si canvie l'`id` de l'URL?** Amaga una opció d'una pantalla no és protegir-la.
:::

## 6. Protegir les rutes: els middleware `auth` i `guest`

Ja podem iniciar sessió, però **cap ruta ho exigix**: el panell és públic i `/accedir` es veu estigues o no autenticat. Ho arreglem amb dos *middleware* que Laravel ja du preparats.

### 6.1. Què és un *middleware*

::: info Nota: què és un *middleware*
Un *middleware* és un **filtre** que s'executa abans que el controlador. Mira la petició i decidix: la deixa passar o la desvia a un altre lloc. Laravel en du molts ja funcionant (el de la sessió, el de CSRF...), i els coneixes sense haver-los vist. Tu ara n'actives dos, pels seus àlies:

| Àlies | Què fa |
|---|---|
| `auth` | Només deixa passar si **has iniciat sessió**; si no, et porta a l'accés |
| `guest` | Només deixa passar si **no** has iniciat sessió; si ja la tens, et porta a una altra pàgina |
:::

A les rutes, un *middleware* s'enganxa amb `->middleware('nom')`, o a un grup sencer amb `Route::middleware('nom')->group(...)`.

### 6.2. Els dos grups

Els canvis toquen tot el fitxer, així que et el deixe sencer. A `routes/web.php`:

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
    });
});
```

Per què cada ruta va on va:

| Ruta | Grup | Per què |
|---|---|---|
| Portada i `labels.show` | Cap | Són públiques: les etiquetes les pot veure qualsevol |
| Accés i registre (`GET` i `POST`) | `guest` | Si ja has iniciat sessió, no té sentit tornar-hi |
| `/eixir` | `auth` | Només pot eixir qui té sessió |
| Tot el panell | `auth` | Només els usuaris registrats creen i gestionen etiquetes |

Els noms de les rutes **no canvien**: un grup no afecta el `->name()`, així que cap enllaç ni redirecció de les vistes i els controladors es trenca.

### 6.3. On et porta cada un

Els dos *middleware* tenen una destinació per defecte, i la nostra aplicació ja la compleix:

- **`auth`** envia l'invitat a la ruta de nom **`login`**. Per això al punt 2 vam dir que la `GET` havia de dir-se així.
- **`guest`** envia a qui ja ha iniciat sessió a la ruta `dashboard` o, si no existix, a **`home`**: la nostra portada.

No cal tocar res més. A més, `auth` **recorda la pàgina que volies visitar**, i això és el que aprofita el `redirect()->intended(...)` del punt 2, que fins ara sempre anava al panell.

::: info Més professional
Si volgueres un altre destí (per exemple, que qui ja té sessió vaja al panell i no a la portada), es configura a `bootstrap/app.php`, dins de `withMiddleware`, amb `$middleware->redirectGuestsTo(...)` i `$middleware->redirectUsersTo(...)`. Nosaltres no ho necessitem.
:::

### 6.4. Comprovar que funciona

1. Ix de la sessió i obri <http://localhost/panell/etiquetes/nova>: **no s'obri el formulari**, et porta a `/accedir`.
2. Inicia sessió amb `prova@example.com` i `password`: arribes a **`/panell/etiquetes/nova`**, la pàgina que volies visitar, i no al llistat.
3. Amb la sessió iniciada, escriu <http://localhost/accedir> a la barra: et porta a la portada. El mateix passa amb `/registre`.
4. Ix i obri <http://localhost/panell>: també et porta a `/accedir`.
5. Recorda el segon forat del punt 5: amb la sessió iniciada, un usuari **pot** encara editar l'etiqueta d'un altre canviant l'`id`. Encara no està arreglat; ho tanquem als punts 8 i 9.

## 7. La barra i la portada segons qui ets

La barra encara mostra «Accedeix» a qui ja ha iniciat sessió, i «Les meues etiquetes» a qui no pot entrar-hi. Posem cada cosa al seu lloc amb les directives `@auth` i `@guest`.

### 7.1. `@auth` i `@guest`

Són directives de Blade que decidixen què es dibuixa:

| Directiva | Es dibuixa si... | Equival a |
|---|---|---|
| `@auth ... @endauth` | Hi ha un usuari autenticat | `Auth::check()` |
| `@guest ... @endguest` | **No** hi ha usuari autenticat | `! Auth::check()` |

### 7.2. La barra

A `resources/views/components/layout.blade.php`, **substituïx** el `<div class="flex items-center gap-6">` de la barra:

```blade
<div class="flex items-center gap-6">
    <a href="{{ route('home') }}" class="text-ink/70 transition hover:text-brand">Etiquetes</a>

    @auth
        <a href="{{ route('panel.labels.index') }}" class="text-ink/70 transition hover:text-brand">Les meues etiquetes</a>
        <span class="font-semibold text-ink">{{ Auth::user()->name }}</span>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="cursor-pointer text-ink/70 transition hover:text-brand">Ix</button>
        </form>
    @endauth

    @guest
        <a href="{{ route('login') }}" class="text-ink/70 transition hover:text-brand">Accedeix</a>
        <a href="{{ route('register') }}" class="text-ink/70 transition hover:text-brand">Registra't</a>
    @endguest
</div>
```

`Auth::user()` retorna l'usuari autenticat, i per això `->name` és el seu nom. A les vistes Blade no cal l'`use` de la facade: `Auth` ja està disponible.

::: warning Atenció
Amagar un enllaç **no protegeix** res. Qui escriga l'adreça a mà continua arribant-hi, i només el *middleware* del punt 6 ho impedix. La barra és comoditat; la seguretat és a les rutes.
:::

### 7.3. El botó de la portada

El botó «Crea la teua etiqueta» porta a `register`, i a qui ja té sessió el *middleware* `guest` el torna a la portada: un botó que no fa res. A `resources/views/home.blade.php`, **substituïx** el botó:

```blade
@auth
    <x-button :href="route('panel.labels.create')" class="mt-8">Crea la teua etiqueta</x-button>
@else
    <x-button :href="route('register')" class="mt-8">Crea la teua etiqueta</x-button>
@endauth
```

El text és el mateix: canvia a on porta. Si has iniciat sessió, directe al formulari d'etiqueta nova; si no, al registre. Dins d'`@auth`, `@else` fa de `@guest`.

### 7.4. La pàgina 404

Una URL que no coincidix amb cap ruta no passa pels *middleware* del grup `web`, entre ells el de la **sessió**. Així, en eixes pàgines la barra no sabria si has iniciat sessió, encara que la tingues. Per a comprovar-ho:

1. Amb la sessió iniciada, obri <http://localhost/etiquetes/10000>. La ruta **sí** que existix (l'etiqueta no), així que la barra mostra el teu nom i «Ix».
2. Obri <http://localhost/ruta-inexistent>: la barra mostra «Accedeix» i «Registra't», com si no tingueres la sessió iniciada.

La solució és una ruta *fallback*: s'executa quan cap altra ruta coincidix, i com que està a `routes/web.php` té tots els *middleware* del grup `web`. Afig-la **al final** del fitxer:

```php
Route::fallback(fn () => abort(404));
```

`abort(404)` mostra la mateixa pàgina 404 de sempre, ara amb la sessió disponible.

### 7.5. Comprovar que funciona

1. Ix de la sessió i obri la portada: la barra mostra **Etiquetes**, **Accedeix** i **Registra't**, i el botó «Crea la teua etiqueta» porta a `/registre`.
2. Inicia sessió amb `prova@example.com`: la barra mostra **Etiquetes**, **Les meues etiquetes**, el teu nom i **Ix**, sense «Accedeix» ni «Registra't». El botó de la portada porta ara a `/panell/etiquetes/nova`.
3. Amb la sessió iniciada, torna a obrir <http://localhost/ruta-inexistent>: ara sí que la barra mostra el teu nom i «Ix».
4. Ix i obri la mateixa URL: mostra els enllaços d'invitat.

## 8. La Policy: cada usuari, les seues etiquetes

El *middleware* `auth` comprova que **has iniciat sessió**, però no sap de qui és cada etiqueta. Ens falta una regla del tipus: *«este usuari pot editar esta etiqueta?»* Eixa regla és una **Policy**.

### 8.1. Què és una Policy

Una *Policy* és una classe que agrupa les regles d'autorització d'**un model**. Té un mètode per cada acció (`update`, `delete`...) que rep l'usuari i el model, i retorna `true` o `false`.

| | *Middleware* `auth` | *Policy* |
|---|---|---|
| Pregunta | Has iniciat sessió? | Pots fer **esta acció** amb **este model**? |
| Mira | La petició | L'usuari i l'etiqueta concreta |
| Si no | Et porta a `login` | Error **403** (prohibit) |

Fixa't que els dos errors són distints: sense sessió et mana a iniciar-la (**401** en esperit, redirecció en pràctica); amb sessió però sense permís, un **403**: ja saps qui eres, i no pots.

### 8.2. Crear la Policy

```bash
sail artisan make:policy LabelPolicy
```

Es crea a `app/Policies/LabelPolicy.php`, una carpeta nova. El generador du una classe buida amb un constructor que no necessitem: lleva'l i escriu les dos regles. L'arxiu queda així:

```php
<?php

namespace App\Policies;

use App\Models\Label;
use App\Models\User;

class LabelPolicy
{
    public function update(User $user, Label $label): bool
    {
        return $user->id === $label->user_id;
    }

    public function delete(User $user, Label $label): bool
    {
        return $this->update($user, $label);
    }
}
```

Explicació, línia per línia:

- **`update(User $user, Label $label)`**: Laravel passa com a primer paràmetre l'usuari que ha iniciat sessió i com a segon l'etiqueta sobre la qual es pregunta.
- **`$user->id === $label->user_id`**: és propietari si l'`id` de l'usuari coincidix amb el `user_id` de l'etiqueta. És l'única regla.
- **`delete()`** reutilitza `update()`: qui pot editar, pot esborrar. Si algun dia les regles es separen, es canvia ací, a un sol lloc.
- **No hi ha cap mètode `create`**: crear l'etiqueta només exigix haver iniciat sessió, i això ja ho fa el *middleware* `auth`.

::: info Nota: no cal registrar-la
Laravel **descobrix la Policy sola** si el nom segueix la convenció: model `Label` ↔ classe `LabelPolicy`, dins d'`app/Policies`. No cal tocar `AppServiceProvider` ni el model. Si el nom no coincidira, s'indicaria amb l'atribut `#[UsePolicy(...)]` al model.
:::

::: info Més professional
`make:policy LabelPolicy --model=Label` genera ja set mètodes (`viewAny`, `view`, `create`, `update`, `delete`, `restore`, `forceDelete`), tots retornant `false`. És útil en aplicacions grans; ací seria esborrar-ne cinc.
:::

### 8.3. Comprovar la Policy abans d'aplicar-la

Encara no l'hem enganxada a cap ruta (això és el punt 9), però podem provar-la des de Tinker:

```bash
sail artisan tinker
```

```php
$label = App\Models\Label::first();
$autor = App\Models\User::find($label->user_id);
$altre = App\Models\User::where('id', '!=', $label->user_id)->first();

$autor->can('update', $label);
$altre->can('update', $label);
$autor->can('delete', $label);
$altre->can('delete', $label);
```

`can()` és un mètode del model `User` que pregunta a la Policy. Les quatre línies han de tornar, **per ordre**: `true`, `false`, `true`, `false`.

### 8.4. Comprovar que funciona

1. Les quatre comprovacions de Tinker donen `true`, `false`, `true`, `false`.
2. L'`id` d'`$altre` és diferent del `user_id` de l'etiqueta: la Policy no deixa que ho toque.



## 9. Aplicar la Policy

La Policy ja sap respondre «pot este usuari tocar esta etiqueta?». Ara cal **preguntar-li-ho** abans de fer res. Ho fem al controlador del panell.

### 9.1. `Gate::authorize()` al controlador

A `app/Http/Controllers/Panel/LabelController.php`, afig l'`use` i la línia nova al principi de `edit()`, `update()` i `destroy()`:

```php
use Illuminate\Support\Facades\Gate;

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

    $label->update($request->validated());

    return to_route('panel.labels.index')->with('status', 'Etiqueta actualitzada correctament.');
}

public function destroy(Label $label): RedirectResponse
{
    Gate::authorize('delete', $label);

    $label->delete();

    return to_route('panel.labels.index')->with('status', 'Etiqueta esborrada correctament.');
}
```

`Gate::authorize('update', $label)` fa tres coses: busca la Policy de `Label`, crida el seu mètode `update` amb l'usuari que ha iniciat sessió i l'etiqueta, i si retorna `false` **llança una excepció** que Laravel converteix en un **403**. Si retorna `true`, no passa res i el codi continua. Per això no cal cap `if`.

| Acció del controlador | Què es pregunta a la Policy |
|---|---|
| `edit()` | `update`: si no pots actualitzar l'etiqueta, no tens motiu per a veure'n el formulari |
| `update()` | `update` |
| `destroy()` | `delete` |

`index()`, `create()` i `store()` no la necessiten: `index()` ja només carrega les etiquetes de l'usuari, `create()` no toca cap etiqueta, i `store()` assigna l'autor a partir de l'usuari autenticat.

::: tip Bona pràctica
Cada mètode que reba una etiqueta per l'URL (`edit`, `update`, `destroy`) ha de **preguntar abans d'actuar**. Este és justament el que la IA va deixar sense fer al punt 5: dos línies que ho canvien tot.
:::

### 9.2. I el `LabelRequest::authorize()`?

El `LabelRequest` té un `authorize()` que retorna `true`. Podria usar la Policy, però el deixem així:

- La mateixa classe valida **crear** i **editar**, i en crear no hi ha etiqueta contra la qual preguntar.
- Si ho posàrem als dos llocs, hi hauria dos punts de comprovació per a la mateixa regla.

Fixa't en l'ordre: Laravel valida el `LabelRequest` **abans** d'entrar al mètode. Si algú intenta editar l'etiqueta d'un altre amb dades invàlides, veurà primer els errors de validació, i amb dades correctes, el 403. En els dos casos, l'etiqueta no es toca.

::: info Nota
Si l'`id` de l'URL no existix, Laravel ja respon amb un **404** abans d'arribar a la Policy: el 403 només apareix quan l'etiqueta existix i no és teua.
:::

### 9.3. La pàgina d'error

Laravel mostra el 403 amb la vista `resources/views/errors/403.blade.php` si existix, igual que fa amb el 404 que ja tenim. Si no l'has creada, mostrarà la pàgina per defecte, amb el text **«This action is unauthorized.»**.

Si no la tens i vols personalitzar-la com la `404`, crea `resources/views/errors/403.blade.php`:

```php
<x-layout>
    <x-slot:title>Acció no autoritzada</x-slot>

    <div class="mx-auto max-w-xl py-12 text-center">
        <p class="font-serif text-8xl font-semibold tracking-tight text-brand">403</p>
        <div class="mx-auto mt-8 h-1 w-16 rounded-full bg-accent"></div>

        <h1 class="mt-8 font-serif text-4xl font-semibold tracking-tight text-ink">Acció no autoritzada</h1>
        <p class="mt-4 text-xl leading-relaxed text-ink/70">No tens permisos per realitzar aquesta acció.</p>

        <x-button :href="route('home')" class="mt-10">Torna a la portada</x-button>
    </div>
</x-layout>
```

### 9.4. `@can`: el botó d'editar només per al propietari

La pàgina de detall d'una etiqueta (`/etiquetes/{id}`) és **pública**: la veu tothom, siga o no l'autor. Si ets el propietari, tindria sentit tindre un botó per a editar-la directament. Per a això, Blade té la directiva `@can`, que pregunta a la mateixa Policy.

A `resources/views/labels/show.blade.php`, afig este bloc **després de la línia de l'autor** (`<p class="mt-10 ..."> $label->user->name ...</p>`), dins de l'`<article>`:

```php
@can('update', $label)
    <x-button :href="route('panel.labels.edit', ['label' => $label])" class="mt-8">Edita esta etiqueta</x-button>
@endcan
```

El botó **només es dibuixa** si `LabelPolicy::update()` retorna `true` per a l'usuari actual i esta etiqueta. A un visitant sense sessió la Policy li respon `false` automàticament, així que no cal comprovar abans si hi ha sessió.

Ara la mateixa pregunta s'ha fet de tres maneres, segons on estiguem:

| On | Com | Què fa si no |
|---|---|---|
| Controlador | `Gate::authorize('update', $label)` | Llança l'error **403** |
| Vista Blade | `@can('update', $label)` | **Amaga** el que hi ha a dins |
| Tinker o codi | `$user->can('update', $label)` | Retorna `false` |

::: warning Atenció
Com al punt 7: el botó **és comoditat, no seguretat**. Qui escriga l'adreça d'edició a mà no depén del botó; el que l'atura és `Gate::authorize` del punt 9.1. Totes dos coses fan falta: una per a no confondre l'usuari i l'altra per a protegir de veres.
:::

### 9.5. Comprovar que funciona

Repetim el segon forat del punt 5.

1. Inicia sessió amb `prova@example.com`, crea una etiqueta («Prova trampa») i apunta el seu `id` passant el ratolí per damunt d'«Editar». Ix.
2. Inicia sessió amb `nou@example.com` i obri `http://localhost/panell/etiquetes/{id}/editar` amb eixe `id`: **ja no s'obri el formulari**, apareix un error **403**.
3. Prova d'esborrar-la: crea una etiqueta amb `nou`, i a les eines de desenvolupador (`F12`) canvia l'`action` del formulari d'«Esborra» per `/panell/etiquetes/{id}` (el de l'etiqueta de `prova`). Envia'l: **403**, i <http://localhost/etiquetes/{id}> continua existint.
4. Prova d'editar: obri l'edició d'una etiqueta de `nou` i, a `F12`, canvia l'`action` del formulari pel de l'etiqueta de `prova`. Envia amb dades correctes: **403**, i el producte de l'etiqueta de `prova` no ha canviat.
5. Amb `nou`, edita i esborra **una etiqueta seua**: funciona com sempre.
6. Obri <http://localhost/panell/etiquetes/99999/editar>: **404**, no 403.
7. Sense sessió, obri una etiqueta des de la portada (`/etiquetes/{id}`): **no** hi ha cap botó d'editar.
8. Amb la sessió de `prova`, obri una etiqueta que siga seua: apareix **«Edita esta etiqueta»**, i el botó porta al formulari d'edició.
9. Amb la sessió de `prova`, obri una etiqueta d'un altre usuari: el botó **no** apareix.


## 10. Errors comuns i punt de control

### 10.1. Errors comuns

#### Sessió i accés

| Símptoma | Causa | Solució |
|---|---|---|
| `Class "App\Http\Controllers\Auth" not found` en iniciar sessió o registrar-se | Falta importar la facade al controlador | Afig `use Illuminate\Support\Facades\Auth;` a dalt de l'arxiu |
| Després d'eixir, no apareix el missatge «Has eixit.» | La pàgina de destí no du `<x-flash />`, o el `with()` va abans d'`invalidate()` | Posa `<x-flash />` a `home.blade.php` i el `with()` al `return`, després d'`invalidate()` |
| El botó «Ix» no canvia el cursor com els enllaços | Tailwind 4 no posa el cursor de mà als `<button>` | Afig `cursor-pointer` al botó |
| Amb la sessió iniciada, `/accedir` i `/registre` et porten a la portada | No és un error: és el *middleware* `guest` | Res; és el comportament esperat |
| A una URL que no existix, la barra mostra «Accedeix» encara que tingues sessió | Eixa pàgina no passa pel grup `web` i no té sessió | Afig `Route::fallback(fn () => abort(404));` al final de `routes/web.php` |

#### Autorització

| Símptoma | Causa | Solució |
|---|---|---|
| `Call to a member function labels() on null` en guardar una etiqueta sense sessió | `Auth::user()` és `null`: la ruta no està dins del grup `auth` | Mou la ruta al grup `Route::middleware('auth')` |
| Qualsevol usuari edita o esborra etiquetes alienes canviant l'`id` | Falta `Gate::authorize()` al controlador | Afig-lo a `edit()`, `update()` i `destroy()` |
| Error **403** amb el text `This action is unauthorized.` en editar o esborrar | La Policy ha dit que no: l'etiqueta no és teua | És el comportament esperat. Si l'etiqueta **és** teua, revisa que `LabelPolicy` compara `$user->id` amb `$label->user_id` |
| Error **404** en editar o esborrar | L'`id` no existix | Comprova l'URL |
| La Policy no s'aplica (tot dóna 403 o tot passa) | La classe no segueix el nom `LabelPolicy` dins d'`app/Policies` | Revisa el nom i la carpeta: no cal registrar-la |

### 10.2. Llista de comprovació

- [ ] **Accés:** amb `prova@example.com` i `password` entres i veus «Sessió iniciada.».
- [ ] Amb una contrasenya incorrecta, l'error eixix baix del correu, el correu continua escrit i la contrasenya no.
- [ ] **Eixir:** el botó «Ix» tanca la sessió i la portada mostra «Has eixit.».
- [ ] **Registre:** crear un compte et deixa dins i et porta al panell.
- [ ] Sense sessió, `/panell/etiquetes` et porta a `/accedir`, i en entrar tornes a la pàgina que volies.
- [ ] Amb sessió, `/accedir` i `/registre` et porten a la portada.
- [ ] **Barra:** invitat veu «Accedeix» i «Registra't»; autenticat, «Les meues etiquetes», el seu nom i «Ix». També a la pàgina 404.
- [ ] **Panell:** només apareixen les teues etiquetes, i l'etiqueta nova té el teu nom com a autor.
- [ ] **Policy:** editar o esborrar una etiqueta d'un altre usuari dóna un **403**, i un `id` que no existix, un **404**.
- [ ] «Edita esta etiqueta» a la pàgina de detall només es veu al propietari.
- [ ] No queda cap `User::firstOrFail()` al controlador del panell.
- [ ] Has repassat el codi que et done una IA amb les tres preguntes del punt 5: **qui pot arribar-hi, de qui és el recurs, i què passa si canvie l'`id` de l'URL**.

### 10.3. Arbre d'arxius

Només els nous (**nou**) i els modificats:

```text
app/
├── Http/
│   ├── Controllers/
│   │   ├── LoginController.php          (nou)
│   │   ├── RegisterController.php       (modificat)
│   │   └── Panel/
│   │       └── LabelController.php      (modificat)
│   └── Requests/
│       └── LoginRequest.php             (nou)
└── Policies/
    └── LabelPolicy.php                  (nou)
resources/views/
├── auth/
│   └── login.blade.php                  (modificat)
├── components/
│   └── layout.blade.php                 (modificat)
├── errors/
│   └── 403.blade.php                    (nou)
├── labels/
│   └── show.blade.php                   (modificat)
└── home.blade.php                       (modificat)
routes/
└── web.php                              (modificat)
```

### 10.4. Arxius finals

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
    });
});

Route::fallback(fn () => abort(404));
```

:::

::: details app/Http/Controllers/LoginController.php

```php
<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function authenticate(LoginRequest $request): RedirectResponse
    {
        if (Auth::attempt($request->validated())) {
            $request->session()->regenerate();

            return redirect()->intended(route('panel.labels.index'))
                ->with('status', 'Sessió iniciada.');
        }

        return back()->withErrors([
            'email' => 'Les credencials no coincidixen amb els nostres registres.',
        ])->onlyInput('email');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return to_route('home')->with('status', 'Has eixit.');
    }
}
```

:::

::: details app/Http/Controllers/RegisterController.php

```php
<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function store(RegisterRequest $request): RedirectResponse
    {
        $user = User::create($request->validated());

        Auth::login($user);

        $request->session()->regenerate();

        return to_route('panel.labels.index')
            ->with('status', 'Compte creat correctament. Ja has iniciat sessió.');
    }
}
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
        Auth::user()->labels()->create($request->validated());

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

        $label->update($request->validated());

        return to_route('panel.labels.index')->with('status', 'Etiqueta actualitzada correctament.');
    }

    public function destroy(Label $label): RedirectResponse
    {
        Gate::authorize('delete', $label);

        $label->delete();

        return to_route('panel.labels.index')->with('status', 'Etiqueta esborrada correctament.');
    }
}
```

:::

::: details app/Http/Requests/LoginRequest.php

```php
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'email'],
            'password' => ['required'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'El correu electrònic és obligatori.',
            'email.email' => 'Escriu un correu electrònic vàlid.',
            'password.required' => 'La contrasenya és obligatòria.',
        ];
    }
}
```

:::

::: details app/Policies/LabelPolicy.php

```php
<?php

namespace App\Policies;

use App\Models\Label;
use App\Models\User;

class LabelPolicy
{
    public function update(User $user, Label $label): bool
    {
        return $user->id === $label->user_id;
    }

    public function delete(User $user, Label $label): bool
    {
        return $this->update($user, $label);
    }
}
```

:::

::: details resources/views/auth/login.blade.php

```blade
<x-layout>
    <x-slot:title>Accedeix</x-slot>
    <x-flash />

    <div class="mx-auto max-w-md">
        <h1 class="font-serif text-4xl font-semibold tracking-tight text-ink">Accedeix</h1>
        <p class="mt-3 text-lg text-ink/70">Entra per a crear i gestionar les teues etiquetes.</p>

        <form method="POST" action="{{ route('login.authenticate') }}" class="mt-10 space-y-6">
            @csrf
            <x-input label="Correu electrònic" name="email" type="email" autocomplete="email" />
            <x-input label="Contrasenya" name="password" type="password" autocomplete="current-password" />

            <x-button type="submit" class="w-full mt-4">Entra</x-button>
        </form>

        <p class="mt-8 text-ink/70">
            Encara no tens compte?
            <a href="{{ route('register') }}" class="font-semibold text-brand hover:underline">Registra't</a>
        </p>
    </div>
</x-layout>
```

:::

::: details resources/views/components/layout.blade.php

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
            <a href="{{ route('home') }}" class="font-serif text-2xl font-semibold tracking-tight text-ink">{{ config('app.name') }}</a>

            <div class="flex items-center gap-6">
                <a href="{{ route('home') }}" class="text-ink/70 transition hover:text-brand">Etiquetes</a>

                @auth
                    <a href="{{ route('panel.labels.index') }}" class="text-ink/70 transition hover:text-brand">Les meues etiquetes</a>
                    <span class="font-semibold text-ink">{{ Auth::user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="cursor-pointer text-ink/70 transition hover:text-brand">Ix</button>
                    </form>
                @endauth

                @guest
                    <a href="{{ route('login') }}" class="text-ink/70 transition hover:text-brand">Accedeix</a>
                    <a href="{{ route('register') }}" class="text-ink/70 transition hover:text-brand">Registra't</a>
                @endguest
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

:::

::: details resources/views/errors/403.blade.php

```blade
<x-layout>
    <x-slot:title>Acció no autoritzada</x-slot>

    <div class="mx-auto max-w-xl py-12 text-center">
        <p class="font-serif text-8xl font-semibold tracking-tight text-brand">403</p>
        <div class="mx-auto mt-8 h-1 w-16 rounded-full bg-accent"></div>

        <h1 class="mt-8 font-serif text-4xl font-semibold tracking-tight text-ink">Acció no autoritzada</h1>
        <p class="mt-4 text-xl leading-relaxed text-ink/70">No tens permisos per realitzar aquesta acció.</p>

        <x-button :href="route('home')" class="mt-10">Torna a la portada</x-button>
    </div>
</x-layout>
```

:::

::: details resources/views/home.blade.php

```blade
<x-layout>
    <x-slot:title>Etiquetes publicades</x-slot>
    <x-flash />

    <h1 class="font-serif text-5xl font-semibold tracking-tight text-ink sm:text-6xl">Etiquetes</h1>
    <div class="mt-6 h-1 w-16 rounded-full bg-accent"></div>
    <p class="mt-6 max-w-xl text-xl leading-relaxed text-ink/70">Les últimes etiquetes publicades.</p>

    @auth
        <x-button :href="route('panel.labels.create')" class="mt-8">Crea la teua etiqueta</x-button>
    @else
        <x-button :href="route('register')" class="mt-8">Crea la teua etiqueta</x-button>
    @endauth

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

    <div class="mt-14 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
        @forelse ($labels as $label)
            <x-label-card :label="$label" class="transition hover:border-brand">
                <x-slot:footer>
                    {{ $label->user->name }} · {{ $label->created_at->format('d/m/Y') }}
                </x-slot>
            </x-label-card>
        @empty
            <p class="text-lg text-ink/60">
                {{ $categoryId ? 'No hi ha etiquetes en esta categoria.' : 'Encara no hi ha cap etiqueta publicada.' }}
            </p>
        @endforelse
    </div>
    <div class="mt-12">
        {{ $labels->links() }}
    </div>
</x-layout>
```

:::

::: details resources/views/labels/show.blade.php

```blade
<x-layout>
    <x-slot:title>{{ $label->product }}</x-slot>

    <a href="{{ route('home') }}" class="text-ink/60 transition hover:text-brand">← Totes les etiquetes</a>

    <article class="mx-auto mt-10 max-w-2xl">
        <x-label-photo :label="$label" class="mb-8 max-w-sm" />
        <x-badge>{{ $label->category->name }}</x-badge>

        <h1 class="mt-6 font-serif text-5xl font-semibold tracking-tight text-ink sm:text-6xl">{{ $label->product }}</h1>

        @if ($label->variety)
            <p class="mt-2 text-2xl text-ink/60">{{ $label->variety }}</p>
        @endif

        <div class="mt-8 h-1 w-16 rounded-full bg-accent"></div>

        <p class="mt-8 text-xl leading-relaxed text-ink/80">{{ $label->description }}</p>
        <p class="mt-6 text-ink/60">{{ number_format($label->net_weight_g, 0, ',', '.') }} g · Envasat el {{ $label->packaged_at->format('d/m/Y') }}</p>
        <p class="mt-10 text-ink/60">{{ $label->user->name }} · {{ $label->created_at->format('d/m/Y') }}</p>

        @can('update', $label)
            <x-button :href="route('panel.labels.edit', ['label' => $label])" class="mt-8">Edita esta etiqueta</x-button>
        @endcan
    </article>
</x-layout>
```

:::