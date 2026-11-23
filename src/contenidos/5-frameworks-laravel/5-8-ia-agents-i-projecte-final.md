# Bloc 8. IA Agents i projecte final

| 1 | Les regles del joc: treballar amb agents i amb quines eines 
| 2 | Especificar bé una tasca 
| 3 | L'arxiu `AGENTS.md`
| 4 | Laravel Boost: guies i servidor MCP 
| 5 | Primers tests: configuració i la regla `Ean13` 
| 6 | Tests de la Policy 
| 7 | Demanar tests a l'agent 
| 8 | Revisar un diff: la llista de comprovació
| 9 | Exercici de revisió: un diff amb trampes
| 10 | Funcionalitat guiada: duplicar una etiqueta
| 11 | El projecte final: enunciat, funcionalitats i criteris d'acceptació
| 12 | Planificació: l'especificació de cada funcionalitat 
| 13 | Desenvolupament amb agent i seguiment 
| 14 | Revisió final, lliurament i defensa 
| 15 | Errors comuns i punt de control 

## 1. Les regles del joc: treballar amb agents i amb quines eines

En els blocs 4 a 7 has fet servir la IA com a **assistent**: li demanaves una tasca concreta, et contestava i tu revisaves el que et donava. Ara fem un pas més: treballaràs amb un **agent** i, amb ell, afegiràs funcionalitats noves al projecte. Abans de començar, cal fixar les regles.

### 1.1. Què és un agent i què canvia

| | Assistent (blocs 4–7) | Agent (bloc 8) |
|---|---|---|
| Què veu | Només el que li apegues | Tot el projecte: llig fitxers i els busca ell |
| Què fa | Et dona codi en una resposta | Edita diversos fitxers i executa ordres (com els tests) |
| Qui aplica els canvis | Tu, copiant i apegant | Ell, directament al projecte |
| Risc | Un tros de codi mal fet | Canvis grans repartits per molts fitxers |

L'agent treballa més de pressa, però també canvia més coses de colp. Per això el que importa ja no és escriure el codi, sinó **especificar bé** què vols i **revisar bé** el que et torna.

::: info Nota
Un **diff** és la llista de canvis que ha fet l'agent: les línies llevades i les afegides, fitxer per fitxer. Revisar un diff és llegir eixa llista abans de donar els canvis per bons. Ho practicarem als punts 7 i 8.
:::

### 1.2. Les regles del bloc

- **L'agent és obligatori.** Tot el bloc el faràs treballant amb un agent.
- **Tu especifiques, ell proposa, tu revises.** No acceptes cap canvi sense haver llegit el diff.
- **El codi és teu.** L'has de poder explicar i defendre: al final del bloc ho faràs davant del professor.
- **Els tests manen.** Una funcionalitat no està acabada fins que té tests i passen.
- **Cap secret a l'agent.** No li ensenyes el `.env`, ni claus, ni contrasenyes.
- **La seguretat continua sent el fil conductor:** validació, `#[Fillable]`, autorització, subida d'arxius. Un agent no se'n preocupa si no li ho demanes, i és ací on més errors et deixarà.

### 1.3. Amb quines eines

Pots usar l'eina que vulgues; tot el que ve a continuació val per a qualsevol agent. Nosaltres t'ho expliquem amb **OpenCode**, perquè no obliga a pagar cap subscripció:

- És de codi obert i funciona en terminal, com a aplicació d'escriptori o com a extensió de l'editor.
- Inclou **models gratuïts** que pots usar sense crear cap compte.
- Alternativa: **VS Code amb una extensió d'agent**, si ja en tens una.

::: warning Atenció
Segons OpenCode, quan uses els seus models gratuïts la informació que envies es pot guardar. Per això, mai no li passes el `.env` ni cap dada privada. A més, la llista de models gratuïts canvia amb el temps: si un model ja no hi és, tria'n un altre de gratuït.
:::

### 1.4. Prepara el teu agent

::: warning AGENTS.md
El projecte porta un `AGENTS.md` per defecte que no val per a Sail; el substituirem al punt 3; si l’agent et proposa instal·lar PHP, Composer o `laravel/boost`, diu-li que no.
`laravel/boost` l’instal·larem nosaltres al punt 4
:::

1. Instal·la OpenCode seguint les instruccions de <https://opencode.ai>.
2. Obri la terminal de WSL, la mateixa on fas servir `sail`, i entra a la carpeta del projecte `etiquetes`.
3. Arranca l'agent:

```bash
opencode
```

4. Si l'eina et demana un model, tria'n un dels gratuïts.

### 1.5. Comprovar que funciona

1. Amb l'agent obert a la carpeta del projecte, escriu esta pregunta (només és una pregunta, no cal que modifique res):
   `Explica'm en tres línies què fa la ruta "/" d'este projecte i en quin fitxer està el controlador.`
2. L'agent contesta sense errors i menciona `routes/web.php` i `HomeController`.
3. Eixix de l'agent i obri <http://localhost>: la portada continua funcionant igual que abans.
4. Si no has aconseguit que l'agent conteste en 10 minuts, avisa el professor abans de continuar: el punt 2 ja el necessita.


## 2. Especificar bé una tasca

Un agent fa el que li escrius, i **el que no li escrius ho decidix ell**. Com més incomplet és l'encàrrec, més coses inventa. Este punt va d'escriure encàrrecs que no deixen buits.

### 2.1. Un encàrrec incomplet

Imagina que escrius a l'agent:

```text
Afig un comptador d'etiquetes al panell.
```

Sembla clar, però no ho és. L'agent podria:

- comptar les etiquetes de **tots** els usuaris, no només les teues (un problema de seguretat);
- escriure el text en anglés, perquè no li has dit que la interfície va en valencià;
- crear un component nou en lloc d'usar els que ja tens;
- tocar el controlador, el model o una migració sense que ningú li ho haja demanat.

Cap d'estes coses és «un error de l'agent»: és el que passa quan **tu** no has marcat els límits.

### 2.2. Les cinc parts d'una bona especificació

| Part | Què respon | En el nostre exemple |
|---|---|---|
| **Context** | On som? Què hi ha ja fet? | El panell ja mostra les etiquetes de l'usuari |
| **Objectiu** | Què vull aconseguir, en una frase | Mostrar quantes etiquetes té |
| **Restriccions** | Com ha d'estar fet | Només les seues, en valencià, amb els components existents |
| **Criteris d'acceptació** | Com sabré que està bé | Casos concrets amb un resultat que es puga comprovar |
| **Què no tocar** | Fins on pot arribar | Migracions, models, rutes, paquets |

::: info Nota
Un **criteri d'acceptació** és una condició que pots comprovar amb un sí o un no («amb 3 etiquetes es veu "Tens 3 etiquetes"»). Una frase com «que quede bé» no ho és. Més endavant veuràs que molts criteris es poden convertir directament en tests.
:::

### 2.3. El mateix encàrrec, ben especificat

```text
Context: El projecte és una aplicació Laravel 13 amb Sail. El panell privat
(`resources/views/panel/labels/index.blade.php`, controlador `Panel\LabelController`)
mostra a cada usuari només les seues etiquetes.

Objectiu: Mostrar damunt del llistat del panell el nombre d'etiquetes de l'usuari.

Restriccions:
- Compta només les etiquetes de l'usuari que ha iniciat sessió.
- El text va en valencià, directament a la vista. Els noms del codi van en anglés.
- Usa els components Blade i els estils que ja existixen; no crees components nous.

Criteris d'acceptació:
1. Amb 3 etiquetes, es veu «Tens 3 etiquetes».
2. Amb 1 etiqueta, es veu «Tens 1 etiqueta» (en singular).
3. Sense cap etiqueta, es veu «Encara no tens cap etiqueta».
4. Un altre usuari amb altres etiquetes no veu el teu nombre, ni tu el seu.

No toques: migracions, models, rutes ni cap fitxer fora del panell.
No instal·les paquets.
```

### 2.4. Per què funciona

- Les **restriccions** són on posem la seguretat i les convencions del projecte: «només les seues» evita el problema de comptar les etiquetes de tothom.
- Els **criteris d'acceptació** et donen una llista per a comprovar el resultat, i després per a escriure'n els tests.
- **Què no tocar** limita el que pot canviar l'agent, i un diff xicotet és molt més fàcil de revisar.

::: tip Bona pràctica
Abans de deixar-lo escriure, demana-li que t'explique el pla: **quins fitxers pensa tocar i per què**. Si el pla inclou alguna cosa de la llista de «No toques», corregixes l'encàrrec abans que s'haja fet cap canvi.

En OpenCode això ja ve previst: té dos agents, **Plan** i **Build**. **Plan** analitza el codi i proposa sense fer canvis als fitxers; **Build** és l'agent per defecte i té totes les eines, també per a editar. Canvies d'un a l'altre amb la tecla **Tab**: primer treballes amb Plan i, quan el pla et convinga, passes a Build.
:::

### 2.5. Practica

Este és l'encàrrec incomplet:

```text
Mostra a la portada quantes etiquetes hi ha publicades.
```

Escriu la teua especificació amb les cinc parts, en un fitxer de text **fora del projecte**. Pensa en dos detalls: la portada és **pública** (qui la veu no ha iniciat sessió) i pot haver-hi 0 etiquetes.

### 2.6. Comprovar que funciona

Revisa la teua especificació amb esta llista:

1. Té les cinc parts: context, objectiu, restriccions, criteris d'acceptació i què no tocar.
2. L'objectiu cap en una frase.
3. Hi ha almenys tres criteris d'acceptació i tots es poden comprovar al navegador amb un sí o un no.
4. Un dels criteris cobrix el cas de **cap etiqueta**.
5. A «No toques» hi ha almenys un fitxer o una carpeta concreta.

Si respons que sí a les cinc, l'especificació està llesta per a donar-la a un agent.

## 3. L'arxiu `AGENTS.md`

L'agent no sap res del teu projecte fins que li ho conten, i no recorda res d'una sessió a l'altra. Per a no repetir-li les mateixes coses en cada encàrrec, el projecte porta un fitxer, `AGENTS.md`, que l'agent llig en començar. Ací és on posem les regles del projecte.

### 3.1. L'`AGENTS.md` que porta el projecte

Obri l'`AGENTS.md` de l'arrel. El que hi ha és un arxiu d'arrancada que genera Laravel (el de **Laravel Boost**). Li diu a l'agent que comprove si tens `php` i `composer` a la teua màquina, que els instal·le si falten, i que instal·le el paquet `laravel/boost`.

Al nostre projecte això no val: PHP i Composer només viuen dins de Docker, amb Sail. Un agent que seguisca eixes instruccions podria instal·lar programes al teu sistema o canviar el `composer.json`. Per això el substituïm per un fitxer propi.

### 3.2. El nostre `AGENTS.md`

No hi ha comanda d'`artisan` per a crear este fitxer: ja existix. **Substituïx** tot el contingut d'`AGENTS.md` (arrel del projecte) per este:

```markdown
# Etiquetes

Aplicació Laravel 13 per a crear etiquetes de productes alimentaris. Part pública: les etiquetes publicades, com a posts d'un blog. Part privada (panell): els usuaris registrats creen etiquetes, les generen en PDF i se les envien per correu.

## Entorn

- Tot funciona amb Laravel Sail (Docker). PHP i Composer NO estan instal·lats a la màquina: no els instal·les ni executes `php`, `composer` o `npm` directament. No instal·les `laravel/boost`.
- Comandes: `sail artisan ...`, `sail composer ...`, `sail npm ...`.
- Tests: `sail artisan test`. Format del codi: `sail pint`.
- Serveis: l'app a http://localhost, phpMyAdmin al 8080, Mailpit al 8025.
- Pila: Laravel 13, PHP 8.5, MySQL, Tailwind 4, PHPUnit 12 (no s'usa Pest).

## Idioma

- Identificadors del codi (classes, taules, camps, noms de ruta) en anglés: `Label`, `labels`, `panel.labels.index`.
- Interfície, missatges i comentaris en valencià, escrits directament a les vistes. No hi ha fitxers de traducció.
- Les URL són en valencià: `/panell/etiquetes`.

## Convencions de Laravel 13

- Als models, atributs en lloc de propietats: `#[Fillable([...])]`, `#[Scope]`, i `casts()` com a mètode.
- Vistes amb components Blade (`<x-...>`), no `@extends` ni `@section`.
- Controladors fins, amb `extends Controller`. Els de la part privada, a `app/Http/Controllers/Panel`.
- Validació en un `FormRequest` (`app/Http/Requests`). Les regles pròpies són classes (`artisan make:rule`), mai closures.
- `Model::preventLazyLoading()` està actiu: carrega les relacions amb `with()` o `load()`. No el desactives.
- Si existix una comanda `artisan make:...` per a crear alguna cosa, usa-la.

## Disseny

- Fons blanc i text negre. Els colors estan com a tokens a `resources/css/app.css` (`@theme`): `--color-brand`, `--color-brand-dark`, `--color-accent`, `--color-ink`.
- Targetes `rounded-2xl border border-ink/10 bg-white`, sense ombres. Botons blaus `rounded-full`, amb `cursor-pointer`.
- El color accent només com a fons, amb text negre. Reutilitza els components que ja existixen.

## Seguretat

- Les rutes privades van dins del grup `auth`.
- Cada acció sobre una etiqueta s'autoritza amb la Policy (`Gate::authorize`): cada usuari només toca les seues.
- Valida tota entrada amb un `FormRequest`. Camps com `user_id` o `photo_path` no es poden assignar des del formulari (fora de `#[Fillable]`).
- Arxius pujats: valida tipus i grandària i guarda'ls amb `store()` (nom generat pel servidor), mai amb el nom que envia el client. Si s'esborra una etiqueta, s'esborra també el seu arxiu.
- Els errors previsibles (correu, PDF) es tracten amb `try/catch`, `report($e)` i un missatge amable, no amb la pàgina d'excepció.
- No guardes dades sensibles en cookies.

## Tests

- Cada funcionalitat nova porta tests a `tests/Feature` i han de passar abans de donar la tasca per acabada.

## Què no has de fer

- No canvies `composer.json` ni instal·les paquets sense preguntar.
- No modifiques migracions ja existents: crea'n una de nova.
- No llegisques ni mostres el `.env`.
- No facis canvis fora de l'abast de l'encàrrec. Si la tasca no està clara, pregunta.
```

- **Curt i concret.** Cada línia és una regla que l'agent pot complir. No és documentació del projecte.
- **Les comandes van amb `sail`.** Sense això, l'agent executaria `php artisan` i fallaria, perquè no hi ha PHP a la teua màquina.
- **La part de seguretat ve d'errors que ja has vist.** El `preventLazyLoading` és del bloc 4, `#[Fillable]` del 5, `auth` i la Policy del 6 i la pujada d'arxius del 7. Un agent no s'hi preocupa si no li ho diuen.

::: tip Bona pràctica
Quan l'agent cometa dues voltes el mateix error, no li ho repetisques en cada missatge: afig una línia a l'`AGENTS.md`. El fitxer creix amb les regles que de veritat necessites.
:::

::: info Més professional
Laravel té un paquet oficial per a això: **Laravel Boost**. L'instal·larem al punt següent i veuràs què li aporta a l'agent.
:::

### 3.3. Comprovar que funciona

1. Executa `sail pint`: la comanda s'executa i acaba sense errors.
2. Executa `sail artisan test`: la comanda s'executa i mostra el resum dels tests.
3. Obri una sessió nova de l'agent a la carpeta del projecte, perquè llija el fitxer nou.
4. Pregunta-li (només és una pregunta): `Quina comanda he d'usar per a executar els tests i quina per a formatar el codi?` L'agent respon `sail artisan test` i `sail pint`.
5. Pregunta-li: `Vull instal·lar un paquet nou amb Composer. Què has de fer?` L'agent diu que ha de preguntar-te abans i que l'executaria amb `sail composer`, no amb `composer`.


## 4. Laravel Boost: guies i servidor MCP

Fins ara l'agent només sap el que li has contat tu a l'`AGENTS.md`. **Laravel Boost** és un paquet oficial de Laravel que li dona tres coses més: **guies** de bones pràctiques de Laravel, **skills** (instruccions per a tasques concretes, com escriure tests) i un **servidor MCP** amb eines per a inspeccionar l'aplicació.

::: info Nota
Un **servidor MCP** és un programa que l'agent pot cridar per a obtindre informació o fer coses que no pot fer ell sol, com consultar la base de dades o llegir el log. Les **guies** es carreguen sempre, en començar; les **skills** només quan l'agent les necessita.
:::

### 4.1. Instal·lar Boost

```bash
sail composer require laravel/boost --dev
sail artisan boost:install
```

- **`--dev`**: només es necessita en desenvolupament, no en producció.
- **`boost:install`** fa preguntes. Marca **AI Guidelines**, **Agent Skills** i **Boost MCP Server Configuration**, i com a agent **OpenCode**. Si et pregunta per Sail, respon que sí; si et pregunta per Laravel Cloud, respon que no (no desplegarem l'aplicació).

Quan acaba, han canviat estos fitxers: `composer.json` i `composer.lock`, `AGENTS.md` (afig un bloc `<laravel-boost-guidelines>` baix del teu text), i han aparegut `boost.json`, `opencode.json` i la carpeta `.agents/skills/`.

::: warning Atenció
No escrigues les teues regles dins del bloc `<laravel-boost-guidelines>`: Boost només reescriu el que hi ha dins d'eixe bloc. Les teues regles es queden **fora**, a dalt, com ara.
:::

### 4.2. Adapta `opencode.json` a OpenCode V2

Comprova la versió que tens:

```bash
opencode --version
```

Si és la V2, el fitxer que ha generat Boost no serveix: el genera amb el format antic (el nom del servidor directament dins de `mcp`, amb `enabled`), i la V2 no el llig. El símptoma és que `opencode mcp list` diu `No MCP servers configured`. En la V2 els servidors van dins de `mcp.servers`.

**Substituïx** el contingut d'`opencode.json`:

```json
{
    "$schema": "https://opencode.ai/config.json",
    "mcp": {
        "servers": {
            "laravel-boost": {
                "type": "local",
                "command": ["vendor/bin/sail", "artisan", "boost:mcp"]
            }
        }
    }
}
```

- **`type: "local"`**: OpenCode arranca el servidor com un programa a la teua màquina.
- **`command`**: l'ordre que l'arranca. Passa per `vendor/bin/sail` perquè PHP només viu dins de Docker.

### 4.3. Què veu ara l'agent

Comprova que està connectat:

```bash
opencode mcp list
```

Ha d'eixir `laravel-boost  connected`. Estes són les eines que li dona:

| Eina | Per a què servix |
|---|---|
| `search-docs` | Cerca a la documentació de Laravel i dels paquets instal·lats |
| `database-schema` | Llig l'estructura de la base de dades (taules, columnes, claus) |
| `database-query` | Executa consultes de **només lectura** |
| `database-connections` | Llista les connexions de base de dades |
| `last-error` | Mostra l'última excepció de l'aplicació |
| `read-log-entries` | Llig les últimes línies del log |
| `browser-logs` | Llig els errors de JavaScript del navegador |
| `get-absolute-url` | Convertix una ruta en una URL completa |
| `record-rule` | Guarda una regla del projecte a `.ai/rules` |

::: warning Atenció
`database-query` només llig, però **llig tot**: també els correus i les contrasenyes xifrades dels usuaris. Al nostre projecte són dades de prova; en una aplicació real no li la donaries a un agent. I `search-docs` consulta un servei de documentació extern: no hi incloguis dades personals.
:::

### 4.4. Practica

1. Demana a l'agent: `Amb database-schema, digues quines columnes té la taula labels.`
2. Demana-li: `Amb database-query, quantes etiquetes hi ha?`
3. Demana-li: `Recorda esta regla: el pes net de les etiquetes es guarda en grams, al camp net_weight_g.`

::: tip Bona pràctica
Les regles del projecte es guarden amb `record-rule` (com al pas 3), no creant els fitxers a mà. Boost manté un índex a `.ai/rules/index.md` que l'agent consulta abans d'editar, i una regla escrita a mà no hi eixiria.
:::

### 4.5. Comprovar que funciona

1. `sail composer show laravel/boost` mostra el paquet `laravel/boost` instal·lat.
2. A `AGENTS.md`, el teu text continua a dalt i hi ha un bloc `<laravel-boost-guidelines>` baix.
3. `opencode mcp list` mostra `laravel-boost  connected`.
4. A la pregunta 1 de la pràctica, l'agent llista les columnes de `labels` i entre elles estan `photo_path` i `user_id`.
5. A la pregunta 2, el nombre coincidix amb les files de la taula `labels` que veus a phpMyAdmin (<http://localhost:8080>).
6. Després del pas 3 de la pràctica, existix la carpeta `.ai/rules` amb la regla guardada.
7. Obri <http://localhost>: l'aplicació continua funcionant igual que abans.


## 5. Primers tests: configuració i la regla `Ean13`

Utilitzarem PHPUnit per fer tests a Laravel, i el primer que farem és entendre per què el test d'exemple que ve de sèrie falla en el nostre projecte.

### 5.1. Per què falla l'`ExampleTest`

Executa els tests:

```bash
sail artisan test
```

El test `Tests\Feature\ExampleTest` falla amb un error com este:

```text
Expected response status code [200] but received 500.

PDOException: SQLSTATE[42S02]: Base table or view not found: 1146 Table 'testing.labels' doesn't exist
```

Ho entenem per parts:

- El test fa una petició a `/`, i la portada consulta la taula `labels`.
- Els tests no usen la teua base de dades, sinó una altra que es diu `testing`. Ho indica `phpunit.xml` (`DB_DATABASE`), i així els tests no toquen les dades de l'aplicació.
- La base de dades `testing` està buida: no té ni una taula. Per això la consulta falla i l'aplicació torna un error 500.

::: info Nota
El trait `RefreshDatabase` prepara la base de dades de proves (executa les migracions si cal) i fa que cada test s'execute dins d'una transacció que es desfà en acabar. Així un test no deixa dades per al següent.
:::

### 5.2. Arreglar-lo amb `RefreshDatabase`

Este fitxer és xicotet i canvia poc, així que el donem sencer. **Substituïx** el contingut de `tests/Feature/ExampleTest.php`:

```php
<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
```

Només hem afegit l'`use` de dalt i la línia `use RefreshDatabase;` dins de la classe.

### 5.3. Un test unitari per a la regla `Ean13`

```bash
sail artisan make:test --unit --phpunit Ean13Test
```

- **`--unit`** el crea a `tests/Unit` (sense, aniria a `tests/Feature`).
- **`--phpunit`** fa que use PHPUnit en lloc d'un altre framework de tests.

**Substituïx** el contingut de `tests/Unit/Ean13Test.php`:

```php
<?php

namespace Tests\Unit;

use App\Rules\Ean13;
use PHPUnit\Framework\TestCase;

class Ean13Test extends TestCase
{
    public function test_a_code_with_the_right_check_digit_is_valid(): void
    {
        $this->assertTrue(Ean13::isValid('4006381333931'));
    }

    public function test_a_code_with_the_wrong_check_digit_is_invalid(): void
    {
        $this->assertFalse(Ean13::isValid('4006381333932'));
    }

    public function test_a_code_with_the_wrong_length_is_invalid(): void
    {
        $this->assertFalse(Ean13::isValid('400638133393'));
        $this->assertFalse(Ean13::isValid('40063813339311'));
    }

    public function test_a_code_with_letters_is_invalid(): void
    {
        $this->assertFalse(Ean13::isValid('400638133393A'));
    }
}
```

- Els noms dels mètodes comencen per `test_`: així PHPUnit sap quins són tests.
- `Ean13::isValid()` és un mètode estàtic que només fa càlculs. No necessita ni l'aplicació ni la base de dades, així que usem el `TestCase` de PHPUnit i no el de Laravel.
- `4006381333931` és un codi EAN-13 real; el segon només canvia l'última xifra, que és la de control.

::: info Nota
Un test **unitari** prova una peça aïllada, sense arrancar Laravel ni la base de dades (com este). Un test de **funcionalitat** (*feature*) fa una petició HTTP a l'aplicació, amb la base de dades, com el de l'`ExampleTest`. Als propers punts farem sobretot de funcionalitat.
:::

### 5.4. Comprovar que funciona

1. Executa `sail artisan test`: no hi ha cap error i passen els 6 tests (2 d'exemple i els 4 d'`Ean13Test`).
2. Comprovem que un test detecta un error de veritat. A `app/Rules/Ean13.php`, canvia `($i % 2 === 0 ? 1 : 3)` per `($i % 2 === 0 ? 3 : 1)`.
3. Executa `sail artisan test --filter=Ean13Test`: **falla 1 test** (el del codi vàlid) i passen els altres 3.
4. Desfés el canvi del pas 2 i torna a executar `sail artisan test`: tornen a passar els 6.


## 6. Tests de la Policy

La Policy del bloc 6 és el que garantix que cada usuari només toca les seues etiquetes. Si algú (o un agent) trenca eixa protecció, el projecte seguirà funcionant «per fora» i ningú no s'adonarà. Un test de seguretat serveix per a això: fa saltar l'alarma.

### 6.1. Què provarem

| Qui | Què fa | Resultat esperat |
|---|---|---|
| Visitant (sense sessió) | Entra al panell | Redirigit a la pàgina d'accedir |
| **Un altre usuari** | Obri el formulari d'edició, actualitza, esborra, previsualitza, descarrega el PDF o l'envia per correu | **403** (prohibit) |
| El propietari | Actualitza o esborra la seua etiqueta | Ho pot fer |

El propietari també es prova perquè un test que només diu «no» no demostra res: podria ser que la Policy prohibira a tothom.

### 6.2. El test

```bash
sail artisan make:test --phpunit LabelAuthorizationTest
```

Crea `tests/Feature/LabelAuthorizationTest.php`. **Substituïx** el seu contingut:

```php
<?php

namespace Tests\Feature;

use App\Models\Label;
use App\Models\User;
use Database\Seeders\CategorySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class LabelAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    private User $owner;

    private User $stranger;

    private Label $label;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
        $this->seed(CategorySeeder::class);

        $this->owner = User::factory()->create();
        $this->stranger = User::factory()->create();
        $this->label = Label::factory()->for($this->owner)->create();
    }

    public function test_a_guest_is_redirected_to_the_login(): void
    {
        $this->get(route('panel.labels.index'))
            ->assertRedirect(route('login'));
    }

    public function test_a_stranger_cannot_open_the_edit_form(): void
    {
        $this->actingAs($this->stranger)
            ->get(route('panel.labels.edit', $this->label))
            ->assertForbidden();
    }

    public function test_a_stranger_cannot_update_the_label(): void
    {
        $this->actingAs($this->stranger)
            ->put(route('panel.labels.update', $this->label), $this->validData(['product' => 'Mandarina']))
            ->assertForbidden();

        $this->assertNotSame('Mandarina', $this->label->fresh()->product);
    }

    public function test_a_stranger_cannot_delete_the_label(): void
    {
        $this->actingAs($this->stranger)
            ->delete(route('panel.labels.destroy', $this->label))
            ->assertForbidden();

        $this->assertModelExists($this->label);
    }

    public function test_a_stranger_cannot_preview_the_pdf(): void
    {
        $this->actingAs($this->stranger)
            ->get(route('panel.labels.preview', $this->label))
            ->assertForbidden();
    }

    public function test_a_stranger_cannot_download_the_pdf(): void
    {
        $this->actingAs($this->stranger)
            ->get(route('panel.labels.pdf', $this->label))
            ->assertForbidden();
    }

    public function test_a_stranger_cannot_send_the_pdf_by_mail(): void
    {
        Mail::fake();

        $this->actingAs($this->stranger)
            ->post(route('panel.labels.mail', $this->label))
            ->assertForbidden();

        Mail::assertNothingSent();
    }

    public function test_the_owner_can_update_the_label(): void
    {
        $this->actingAs($this->owner)
            ->put(route('panel.labels.update', $this->label), $this->validData(['product' => 'Mandarina']))
            ->assertRedirect(route('panel.labels.index'));

        $this->assertSame('Mandarina', $this->label->fresh()->product);
    }

    public function test_the_owner_can_delete_the_label(): void
    {
        $this->actingAs($this->owner)
            ->delete(route('panel.labels.destroy', $this->label))
            ->assertRedirect(route('panel.labels.index'));

        $this->assertModelMissing($this->label);
    }

    /**
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    private function validData(array $overrides = []): array
    {
        return array_merge([
            'category_id' => $this->label->category_id,
            'product' => 'Taronja',
            'variety' => 'Navelina',
            'net_weight_g' => 1000,
            'caliber' => '4',
            'origin' => 'La Ribera (València)',
            'batch' => 'L2026-001',
            'packer' => 'Cooperativa Camp i Horta',
            'product_code' => 'TAR-001',
            'barcode' => '4006381333931',
            'packaged_at' => '2026-11-01',
            'description' => 'Taronja de collita pròpia.',
        ], $overrides);
    }
}
```

Alguns detalls que importen:

- **`$this->seed(CategorySeeder::class)`**: crea les categories **dins d'este test**. La factoria d'etiquetes busca la categoria pel nom, i sense elles fallaria. `CategorySeeder` usa `firstOrCreate`, així que no importa que ja existisquen.
- **`Label::factory()->for($this->owner)`** crea l'etiqueta com si fora de l'usuari `$owner`.
- **`actingAs($user)`** fa la petició com si `$user` haguera iniciat sessió.
- **`validData()`**: el `FormRequest` es valida **abans** d'entrar al mètode del controlador, i la Policy es comprova dins. Si enviàrem dades incorrectes, l'altre usuari rebria un error de validació i no arribaria mai al 403. Per això l'actualització envia dades vàlides.
- **`withoutVite()`**: les vistes (també la pàgina d'error 403) carreguen els assets de Vite. Este mètode ens evita haver de tindre Vite en marxa per a provar la seguretat.
- **`assertForbidden()`** comprova que la resposta és un 403. Després comprovem també que no ha passat res: l'etiqueta no ha canviat, existix, i no s'ha enviat cap correu (`Mail::fake()` impedix l'enviament real i deixa comprovar-lo).

::: warning Atenció
Laravel també té un atribut `#[Seeder(...)]` per a sembrar dades en els tests, però només s'aplica **una vegada per execució**, quan es prepara la base de dades de proves. Si abans ha corregut un altre test (com l'`ExampleTest`), les dades no es sembren, i els tests que depenen d'elles fallen només quan s'executen tots junts. Per això sembrem amb `$this->seed()` dins del test.
:::

::: tip Bona pràctica
Un test de seguretat ha de poder **fallar**. Abans de fiar-te'n, trenca a propòsit la protecció i comprova que el test s'en adona. Ho fem al pas 6.3.
:::

### 6.3. Comprovar que funciona

1. Executa `sail artisan test --filter=LabelAuthorizationTest`: passen els 9 tests.
2. Executa `sail artisan test`: passen els 15 (2 d'exemple, 4 d'`Ean13Test` i 9 d'este).
3. Trenquem la protecció. A `app/Http/Controllers/Panel/LabelController.php`, al mètode `destroy`, comenta la línia `Gate::authorize('delete', $label);`.
4. Torna a executar `sail artisan test --filter=LabelAuthorizationTest`: **falla 1 test**, `test_a_stranger_cannot_delete_the_label`, i els altres 8 passen. L'altre usuari ha aconseguit esborrar l'etiqueta i el test ho ha detectat.
5. Descomenta la línia i torna a executar `sail artisan test`: tornen a passar els 15.


## 7. Demanar tests a l'agent

Escriure tests és una de les tasques en què un agent ajuda més, però també una de les que més enganya: pot escriure tests que **sempre passen**, o que només repetixen el codi. Per això el que importa ací no és que l'agent els escriga, sinó que tu sàpigues si valen alguna cosa.

### 7.1. Què demanarem

Els tests de la Policy els has fet tu al punt anterior. Ara li'n demanem a l'agent sobre la foto, el codi de barres i el correu:

| Part | Què ha de comprovar |
|---|---|
| Foto | Es guarda amb un nom generat, es valida (tipus i grandària), es reemplaça i s'esborra |
| Codi de barres | Un EAN-13 amb la xifra de control incorrecta es rebutja |
| Correu | El propietari rep el PDF, i amb un codi de barres invàlid no s'envia res |

::: info Nota
Un **fake** substituïx una cosa real (el disc, el correu) per una versió falsa que apunta en memòria el que passa. No escriu fitxers ni envia res, i et deixa comprovar-ho després. `Storage::fake('public')` falsifica el disc i `Mail::fake()` el correu.
:::

### 7.2. L'especificació

Ho fem com al punt 2, amb les cinc parts. Dona-li això a l'agent:

```text
Context: Projecte Laravel 13 amb Sail i PHPUnit 12. Ja hi ha tests de la Policy a
`tests/Feature/LabelAuthorizationTest.php`: segueix el seu estil (noms `test_...`,
`RefreshDatabase`, `$this->seed(CategorySeeder::class)` al `setUp`, `withoutVite()` i
factories). El codi a provar és `Panel\LabelController` (store, update, destroy),
`Panel\LabelPdfController` (send), la regla `App\Rules\Ean13` i `App\Mail\LabelPdfMail`.

Objectiu: Escriure tests de funcionalitat per a la foto de l'etiqueta, el codi de barres
i l'enviament del PDF per correu.

Restriccions:
- Dos fitxers nous: `tests/Feature/LabelPhotoTest.php` i `tests/Feature/LabelPdfTest.php`.
- Usa `Storage::fake('public')` i `UploadedFile::fake()` per a les fotos, i `Mail::fake()`
  per al correu. Cap test pot escriure al disc real ni enviar correus.
- Les dades vénen de les factories. Cada test és independent dels altres.
- Si trobes un defecte al codi, avisa'm abans d'escriure el test; no el corregisques tu.

Criteris d'acceptació (cada punt és un test):
Foto:
1. Crear una etiqueta amb una foto vàlida: es guarda, la ruta comença per `labels/`, no
   coincidix amb el nom original i el fitxer existix al disc.
2. Crear una etiqueta sense foto: error de validació al camp `photo`.
3. Crear una etiqueta amb un fitxer que no és una imatge (un PDF): error al camp `photo`.
4. Crear una etiqueta amb una imatge de més de 2 MB: error al camp `photo`.
5. Editar una etiqueta pujant una foto nova: la nova existix i l'antiga ja no.
6. Esborrar una etiqueta amb foto: desapareix de la base de dades i el seu fitxer també.
Codi de barres:
7. Crear una etiqueta amb un EAN-13 de dígit de control incorrecte: error al camp `barcode`.
8. Enviar el PDF d'una etiqueta amb codi de barres invàlid: redirigix a l'edició amb error
   al camp `barcode` i no s'envia cap correu.
Correu:
9. El propietari envia el PDF: s'envia un `LabelPdfMail` al seu correu.

No toques: res de `app/`, ni les migracions, ni els tests que ja existixen.
```

- **Context:** li diu quin estil seguir. Sense la referència a `LabelAuthorizationTest`, cada agent escriuria els tests a la seua manera.
- **«Avisa'm abans de corregir»:** si l'agent troba un defecte, vols saber-ho tu. Un agent que «arregla» el codi per a fer passar el seu test t'amaga el problema.
- **Criteris numerats:** cada número és un test, i després els pots comptar un a un.

::: tip Bona pràctica
Abans de deixar-lo escriure, treballa amb l'agent **Plan**: que t'explique quins tests pensa escriure i en quins fitxers. Revisa que hi són els 9 i que no toca `app/`. Quan estiga bé, passa a **Build**.
:::

### 7.3. Revisar els tests de l'agent

Que passen no vol dir que valguen. Quan l'agent acabe, llig els tests amb esta llista:

1. **Es poden trencar?** Un test que no pot fallar no protegix res. Ho comprovem al pas 7.4.
2. **Proven el resultat o repetixen el codi?** Un bon test comprova el que veu l'usuari (el fitxer existix, hi ha un error al camp), no com està escrit el controlador.
3. **Usen els fakes?** Si no hi ha `Storage::fake` o `Mail::fake`, el test pot escriure al disc real o enviar un correu.
4. **Cada test té assercions?** Un test sense `assert...` sempre passa.
5. **Les dades venen de les factories** i el test no depén d'un altre.
6. **El nom diu què comprova?** `test_the_old_photo_is_deleted_when_a_new_one_is_uploaded` és clar; `test_update` no.
7. **Ha tocat `app/`?** No ho hauria de fer. Si ha canviat codi de l'aplicació, desfés-ho i revisa per què.

### 7.4. Comprovar que funciona

1. Executa `sail artisan test`: passen tots els tests, també els teus de la Policy.
2. Trenquem el codi a propòsit, un canvi cada vegada, i comprovem que algun test s'en adona:

| Canvi | Ha de fallar |
|---|---|
| A `Panel\LabelController::destroy`, comenta les tres línies de `if ($label->photo_path) { ... }` | El test que esborra una etiqueta amb foto |
| A `Panel\LabelController::update`, comenta les tres línies de `if ($oldPhoto) { ... }` | El test que substituïx la foto |
| A `LabelRequest`, canvia `'max:2048'` per `'max:20480'` | El test de la imatge de més de 2 MB |

3. Si algun canvi **no fa fallar cap test**, l'agent no ha escrit eixe test (o l'ha escrit mal): demana-li que el corregisca.
4. Desfés els tres canvis i torna a executar `sail artisan test`: tornen a passar tots.


## 8. Revisar un diff: la llista de comprovació

Un agent pot canviar deu fitxers en un minut. Si acceptes els canvis sense llegir-los, el codi deixa de ser teu. Este punt t'ensenya a llegir el que ha fet l'agent amb mètode: una llista curta que pots aplicar a qualsevol canvi.

### 8.1. Com es llig un diff

Recorda: un **diff** és la llista de canvis fitxer per fitxer. Així es veu un canvi al llistat del panell:

```diff
 public function index(): View
 {
     return view('panel.labels.index', [
-        'labels' => Label::with('category')->where('user_id', Auth::id())->get(),
+        'labels' => Label::with('category')->get(),
+        'total' => Label::count(),
     ]);
 }
```

- Les línies amb **`-`** s'han llevat.
- Les línies amb **`+`** s'han afegit.
- Les que no porten res són context, per a saber on estem.

Fixa't que per a entendre un canvi cal mirar **també el que s'ha llevat**, no només el que s'ha afegit.

### 8.2. Les cinc preguntes

Per a cada ruta, mètode o fitxer que toque l'agent, respon estes cinc preguntes:

| Pregunta | Què busques | Com ho resolem al nostre projecte |
|---|---|---|
| **1. Qui pot arribar a esta ruta?** | Que només hi arriben els que han de poder | Les rutes privades estan dins del grup `auth`; un visitant acaba a la pàgina d'accedir |
| **2. De qui és el recurs?** | Que l'usuari només toque el que és seu | `Gate::authorize('update', $label)` i la Policy; al llistat, `where('user_id', Auth::id())` |
| **3. Què valida?** | Que cada dada d'entrada passe per validació | `LabelRequest`; `#[Fillable]` deixa fora `user_id` i `photo_path`; les fotos es guarden amb `store()` |
| **4. Què passa si falla?** | Que els errors es tracten i no s'oculten | `try/catch` amb `report($e)` i un missatge amable en enviar el PDF |
| **5. Què es queda sense esborrar o sense protegir?** | Arxius, dades o rutes que han quedat oblidats | `destroy` esborra la fila **i** el fitxer de la foto; `update` esborra la foto antiga |

### 8.3. Apliquem-les al diff d'abans

El diff del punt 8.1 el podria haver escrit un agent a qui hem demanat «mostra el nombre d'etiquetes al panell» sense dir-li res més (com al punt 2).

- **Abast:** és el fitxer que esperàvem, `Panel\LabelController`.
- **Pregunta 1:** la ruta no ha canviat, continua dins d'`auth`. Bé.
- **Pregunta 2:** **falla.** S'ha llevat el `where('user_id', Auth::id())`. Ara el panell de cada usuari mostra les etiquetes de **tots**, i `Label::count()` compta les de tothom.
- **Preguntes 3, 4 i 5:** no hi ha dades noves, ni errors, ni arxius implicats.

Un error així no el veus si només mires les línies verdes: la protecció s'ha perdut en una línia **llevada**.

Si trobes un problema, no el corregisques tu directament: demana-li a l'agent que ho arregle i que escriga un test que ho detecte. Per exemple:

```text
Has llevat el filtre per usuari al llistat del panell: ara cada usuari veu les etiquetes
dels altres. Torna'l a posar, que el total compte només les etiquetes de l'usuari, i
escriu un test que ho comprove.
```

### 8.4. Abans de les cinc preguntes, mira l'abast

Abans d'entrar en el detall, fes una passada ràpida:

- **Fitxers:** hi ha algun que no esperaves? Un canvi al `.env`, a una migració ja aplicada o al `composer.json` (un paquet nou) sense que ho hagueres demanat és un senyal d'alerta.
- **Grandària:** és molt més gran del que demanaves? Si el diff és massa gran per a revisar-lo bé, el problema és l'encàrrec: parteix-lo en tasques més xicotetes.
- **Tests:** hi ha tests nous o canviats? Un agent que modifica un test perquè passe, en lloc d'arreglar el codi, també és un senyal d'alerta.

::: tip Bona pràctica
Revisa **cada vegada**, encara que el canvi siga xicotet. Una línia llevada pot ser tota la seguretat d'una pantalla, com acabes de veure.
:::

## 9. Exercici de revisió: un diff amb trampes

Ara practiques el que has vist al punt anterior. Tens un diff escrit per un agent i has de trobar què ha deixat sense protegir.

### 9.1. L'encàrrec i el diff

Imagina que has demanat a un agent:

```text
Afig al panell la possibilitat d'enviar l'etiqueta per correu a un client, amb una foto
de l'envasat opcional que es guarde al servidor, i un enllaç per a descarregar el PDF.
```

L'agent ha tornat este diff. **No l'apliques al teu projecte**: només el llegixes.

**`routes/web.php`**

```diff
         Route::get('etiquetes/{label}/pdf', [Panel\LabelPdfController::class, 'download'])->name('labels.pdf');
         Route::post('etiquetes/{label}/pdf/enviar', [Panel\LabelPdfController::class, 'send'])->name('labels.mail');
+        Route::get('etiquetes/{label}/descarrega', [Panel\LabelShareController::class, 'download'])->name('labels.share.download');
+        Route::post('etiquetes/{label}/compartir', [Panel\LabelShareController::class, 'send'])->name('labels.share.send');
     });
 });
```

**`app/Http/Controllers/Panel/LabelShareController.php`** (fitxer nou)

```diff
+<?php
+
+namespace App\Http\Controllers\Panel;
+
+use App\Http\Controllers\Controller;
+use App\Mail\LabelPdfMail;
+use App\Models\Label;
+use App\Services\LabelPdfGenerator;
+use Illuminate\Http\RedirectResponse;
+use Illuminate\Http\Request;
+use Illuminate\Http\Response;
+use Illuminate\Support\Facades\Cookie;
+use Illuminate\Support\Facades\Gate;
+use Illuminate\Support\Facades\Mail;
+use Throwable;
+
+class LabelShareController extends Controller
+{
+    public function download(Label $label, LabelPdfGenerator $generator): Response
+    {
+        return $generator->make($label)->download('etiqueta-'.$label->id.'.pdf');
+    }
+
+    public function send(Request $request, Label $label, LabelPdfGenerator $generator): RedirectResponse
+    {
+        Gate::authorize('download', $label);
+
+        $request->validate(['email' => ['required', 'email']]);
+
+        if ($request->hasFile('photo')) {
+            $request->file('photo')->storeAs('shares', $request->file('photo')->getClientOriginalName(), 'public');
+        }
+
+        Cookie::queue('last_client', $request->email, 60 * 24 * 365);
+
+        try {
+            Mail::to($request->email)->send(new LabelPdfMail($label, $generator->make($label)->output()));
+        } catch (Throwable $e) {
+            return back()->with('status', "No s'ha pogut enviar el correu.");
+        }
+
+        return back()->with('status', "Etiqueta enviada a {$request->email}.");
+    }
+}
```

**`app/Http/Controllers/Panel/LabelController.php`**

```diff
     public function destroy(Label $label): RedirectResponse
     {
         Gate::authorize('delete', $label);
 
         $label->delete();
 
-        if ($label->photo_path) {
-            Storage::disk('public')->delete($label->photo_path);
-        }
-
         return to_route('panel.labels.index')->with('status', 'Etiqueta esborrada correctament.');
     }
```

### 9.2. Què has de fer

1. **Mira l'abast** (punt 8.4): quins fitxers ha tocat l'agent i si tots formaven part de l'encàrrec.
2. **Aplica les cinc preguntes** (punt 8.2) a cada fitxer.
3. **Anota cada problema que trobes:** on està, per què és un problema i com el resoldries.
4. **Digues quin test el detectaria**, o per què no hi ha un test senzill que ho faça.
5. **Ordena els problemes per gravetat** i prepara't per a defendre per què el primer és el més greu.

Hi ha **cinc problemes** i també coses que estan ben fetes. Compte: no tot el que et sembla estrany és un error.

### 9.3. La solució

::: details 🔍 Solució (no la mires fins haver acabat)

**El que està bé:** les rutes noves estan dins del grup `auth`; `send` autoritza amb la Policy; l'adreça de correu es valida; l'enviament té un `try/catch`.

| # | On | Problema | Pregunta | Test que el detectaria |
|---|---|---|---|---|
| 1 | `LabelShareController::download` | **No hi ha `Gate::authorize`.** Qualsevol usuari amb sessió pot descarregar l'etiqueta d'un altre canviant l'`id` de la URL | 2. De qui és el recurs? | Com `LabelAuthorizationTest`: un altre usuari rep 403 en `panel.labels.share.download` |
| 2 | `LabelShareController::send` | **La foto no es valida** (ni tipus, ni grandària) i es guarda amb `storeAs()` i el **nom que envia el client**: pot sobreescriure fitxers i el nom pot acabar en `.php` | 3. Què valida? | Enviar un fitxer que no és una imatge: ha d'haver-hi error de validació al camp `photo` |
| 3 | `LabelController::destroy` | **S'han llevat les línies que esborren la foto.** L'etiqueta desapareix però el fitxer es queda al disc. A més, no formava part de l'encàrrec | 5. Què es queda sense esborrar? | El test 6 del punt 7 (esborrar una etiqueta amb foto) fallaria |
| 4 | `LabelShareController::send` | **El `catch` es traga l'error** sense `report($e)`: ningú sabrà per què fallen els correus | 4. Què passa si falla? | No hi ha un test senzill: es detecta llegint el codi |
| 5 | `LabelShareController::send` | **Es guarda l'adreça d'un tercer (el client) en una cookie durant un any**, sense necessitat | 3. Què valida? / dades innecessàries | `assertCookieMissing('last_client')` després d'enviar |

**Ordre de gravetat:** 1 i 2 són els greus (accés a dades d'altres usuaris i una pujada d'arxius sense control); 3 i 4 són de gravetat mitjana (arxius perduts i errors ocults); 5 és el menys greu: Laravel xifra les cookies, així que ningú no la pot llegir ni modificar des del navegador, però continua sent una dada personal d'un tercer que no cal guardar.

**Com es resoldrien:**

```php
public function download(Label $label, LabelPdfGenerator $generator): Response
{
    Gate::authorize('download', $label);

    return $generator->make($label)->download('etiqueta-'.$label->id.'.pdf');
}
```

```php
$request->validate([
    'email' => ['required', 'email'],
    'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
]);

if ($request->hasFile('photo')) {
    $request->file('photo')->store('shares', 'public');
}
```

```php
} catch (Throwable $e) {
    report($e);

    return back()->with('status', "No s'ha pogut enviar el correu.");
}
```

- **Problema 3:** torna a posar les línies llevades, i pregunta't per què l'agent ha tocat `destroy` si l'encàrrec no parlava d'esborrar.
- **Problema 5:** lleva la línia de la cookie.

**Extra:** la pregunta 5 també s'aplica a la foto guardada a `shares/`: no es guarda enlloc la seua ruta, així que no hi ha manera d'esborrar-la quan s'esborre l'etiqueta. Una solució seria guardar-la en una columna, igual que `photo_path`.
:::


## 10. Funcionalitat guiada: duplicar una etiqueta

Ara ho ajuntem tot en una funcionalitat completa: especificar, deixar que l'agent la faça, revisar el seu diff, provar-la i comprovar que els tests s'en adonen si algú trenca alguna cosa. Així faràs també el projecte final.

La funcionalitat: un botó **«Duplica»** a cada targeta del panell que crea una còpia de l'etiqueta, **amb la seua pròpia foto**, per a poder editar-la sense començar de zero.

### 10.1. L'especificació

Treballa amb l'agent **Plan** (punt 2): dona-li això, revisa el pla i, quan estiga bé, passa a **Build**.

```text
Context: Projecte Laravel 13 amb Sail. El panell (`resources/views/panel/labels/index.blade.php`,
controlador `Panel\LabelController`) mostra a cada usuari les seues etiquetes en targetes,
amb enllaços al peu (Veure, Editar, PDF) i un formulari per a esborrar.

Objectiu: Afegir al peu de cada targeta un botó «Duplica» que crea una còpia de l'etiqueta.

Restriccions:
- Ruta `POST` dins del grup `panell`, en `routes/web.php`, i acció al `Panel\LabelController`.
- El botó és un formulari amb `@csrf` i `cursor-pointer`, amb el mateix estil que els altres
  enllaços del peu.
- Autoritza amb la Policy (`update`): només el propietari pot duplicar la seua etiqueta.
- La còpia és de l'usuari i té les mateixes dades. Si l'original té foto, la còpia té un
  **fitxer propi** al disc `public`, amb un nom generat pel servidor.
- En acabar, torna al llistat del panell amb el missatge «Etiqueta duplicada correctament.».

Criteris d'acceptació:
1. Duplicar una etiqueta crea una etiqueta nova amb les mateixes dades.
2. Si l'original té foto, la còpia té una ruta diferent i els dos fitxers existixen.
3. Si l'original no té foto, la còpia tampoc.
4. Un altre usuari rep un 403 i no es crea cap còpia.
5. Esborrar la còpia no esborra la foto de l'original.

No toques: la Policy, les migracions, `LabelRequest` ni els tests que ja existixen.
```

### 10.2. Què hauria de quedar

Compara el que ha fet l'agent amb esta referència. Pot ser diferent i estar bé, però ha de complir el mateix.

**`routes/web.php`**: afig la ruta al grup `panell`, junt amb les altres de `etiquetes/{label}/...`:

```php
Route::post('etiquetes/{label}/duplicar', [Panel\LabelController::class, 'duplicate'])->name('labels.duplicate');
```

**`app/Http/Controllers/Panel/LabelController.php`**: afig l'`use` i els dos mètodes nous.

```php
use Illuminate\Support\Str;
```

```php
public function duplicate(Label $label): RedirectResponse
{
    Gate::authorize('update', $label);

    $copy = $label->replicate();
    $copy->photo_path = $this->copyPhoto($label->photo_path);
    $copy->save();

    return to_route('panel.labels.index')->with('status', 'Etiqueta duplicada correctament.');
}

private function copyPhoto(?string $path): ?string
{
    if (! $path) {
        return null;
    }

    $newPath = 'labels/'.Str::uuid().'.'.pathinfo($path, PATHINFO_EXTENSION);

    return Storage::disk('public')->copy($path, $newPath) ? $newPath : null;
}
```

- **`replicate()`** fa una còpia de l'etiqueta **sense guardar-la**, sense la clau primària ni les dates.
- **`copyPhoto()`** copia el fitxer amb un nom nou generat pel servidor (mai el nom original, com al bloc 7). Si no es pot copiar, torna `null` i la còpia queda sense foto.
- **`Gate::authorize('update', ...)`** és el mateix permís que per a editar: qui pot editar pot duplicar.

::: warning Atenció
`replicate()` copia **tots** els atributs, també `user_id` i `photo_path` (no passa pel `#[Fillable]`). Si no canviem `photo_path`, la còpia apuntaria **al mateix fitxer** que l'original: en esborrar-ne una, l'altra es quedaria sense foto. Per això és el criteri 5.
:::

**`resources/views/panel/labels/index.blade.php`**: al peu de la targeta, canvia la classe del `<div>` (amb cinc accions pot no cabre en una línia) i afig el formulari entre l'enllaç «PDF» i el formulari «Esborra»:

```blade
<div class="flex flex-wrap items-center gap-x-6 gap-y-2 font-semibold">
```

```blade
<form method="POST" action="{{ route('panel.labels.duplicate', ['label' => $label]) }}">
    @csrf
    <button type="submit" class="cursor-pointer text-brand hover:underline">Duplica</button>
</form>
```

### 10.3. Revisa el diff de l'agent

Aplica les cinc preguntes del punt 8 a este canvi:

| Pregunta | Què mires ací |
|---|---|
| 1. Qui hi arriba? | La ruta nova està dins del grup `auth` |
| 2. De qui és el recurs? | Hi ha `Gate::authorize` abans de fer res |
| 3. Què valida? | No hi ha entrada de l'usuari, però: la foto copiada té un nom generat pel servidor? |
| 4. Què passa si falla? | Què passa si el fitxer de la foto no es pot copiar? |
| 5. Què es queda sense esborrar o sense protegir? | La còpia té el seu propi fitxer, o comparteix el de l'original? |

Mira també l'**abast** (punt 8.4): ha tocat la Policy, una migració o alguna cosa que no li has demanat?

### 10.4. Tests

Demana a l'agent els tests dels criteris 1 a 5 (en un fitxer nou, amb el mateix estil que `LabelAuthorizationTest`) i revisa'ls amb la llista del punt 7.3. Quan els tingues, compara'ls amb els de referència:

::: details 🔍 Tests de referència (no els mires fins haver revisat els de l'agent)

`tests/Feature/LabelDuplicateTest.php`:

```php
<?php

namespace Tests\Feature;

use App\Models\Label;
use App\Models\User;
use Database\Seeders\CategorySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class LabelDuplicateTest extends TestCase
{
    use RefreshDatabase;

    private User $owner;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
        $this->seed(CategorySeeder::class);
        Storage::fake('public');

        $this->owner = User::factory()->create();
    }

    public function test_the_owner_can_duplicate_a_label_with_its_own_photo(): void
    {
        $path = UploadedFile::fake()->image('tomaca.jpg')->store('labels', 'public');
        $label = Label::factory()->for($this->owner)->create(['photo_path' => $path]);

        $this->actingAs($this->owner)
            ->post(route('panel.labels.duplicate', $label))
            ->assertRedirect(route('panel.labels.index'));

        $this->assertSame(2, Label::count());

        $copy = Label::where('id', '!=', $label->id)->firstOrFail();

        $this->assertSame($this->owner->id, $copy->user_id);
        $this->assertSame($label->product, $copy->product);
        $this->assertSame($label->barcode, $copy->barcode);
        $this->assertNotSame($label->photo_path, $copy->photo_path);
        Storage::disk('public')->assertExists($label->photo_path);
        Storage::disk('public')->assertExists($copy->photo_path);
    }

    public function test_a_label_without_photo_is_duplicated_without_photo(): void
    {
        $label = Label::factory()->for($this->owner)->create();

        $this->actingAs($this->owner)
            ->post(route('panel.labels.duplicate', $label))
            ->assertRedirect(route('panel.labels.index'));

        $copy = Label::where('id', '!=', $label->id)->firstOrFail();

        $this->assertNull($copy->photo_path);
    }

    public function test_a_stranger_cannot_duplicate_the_label(): void
    {
        $label = Label::factory()->for($this->owner)->create();
        $stranger = User::factory()->create();

        $this->actingAs($stranger)
            ->post(route('panel.labels.duplicate', $label))
            ->assertForbidden();

        $this->assertSame(1, Label::count());
    }

    public function test_deleting_the_copy_keeps_the_photo_of_the_original(): void
    {
        $path = UploadedFile::fake()->image('tomaca.jpg')->store('labels', 'public');
        $label = Label::factory()->for($this->owner)->create(['photo_path' => $path]);

        $this->actingAs($this->owner)->post(route('panel.labels.duplicate', $label));

        $copy = Label::where('id', '!=', $label->id)->firstOrFail();

        $this->actingAs($this->owner)->delete(route('panel.labels.destroy', $copy));

        Storage::disk('public')->assertExists($label->photo_path);
        Storage::disk('public')->assertMissing($copy->photo_path);
    }
}
```
:::

### 10.5. Comprovar que funciona

1. Al panell, fes clic a «Duplica» en una etiqueta **amb foto**: tornes al llistat amb la franja «Etiqueta duplicada correctament.» i apareix una targeta més amb les mateixes dades.
2. La carpeta `storage/app/public/labels` té un fitxer més, i la còpia mostra la seua foto.
3. Esborra la còpia: l'original continua mostrant la seua foto.
4. Duplica una etiqueta **sense foto**: la còpia apareix sense foto.
5. Mira que el peu de les targetes es veu bé amb els cinc enllaços (Veure, Editar, PDF, Duplica, Esborra).
6. Executa `sail artisan test`: passen tots els tests, també els que ha escrit l'agent.
7. Trenquem el codi a propòsit. Al mètode `duplicate`, comenta la línia `$copy->photo_path = $this->copyPhoto($label->photo_path);`. Amb els tests de referència, **fallen 2 tests**: el de la foto pròpia i el d'esborrar la còpia. Descomenta-la.
8. Comenta ara `Gate::authorize('update', $label);`: **falla 1 test**, el de l'altre usuari. Descomenta-la i torna a executar `sail artisan test`: passen tots.


## 11. El projecte final: enunciat i treball autònom
## 11. El projecte final: enunciat i treball autònom

Has arribat al projecte final. Fins ara has practicat les eines (especificar, `AGENTS.md`, tests, revisar un diff). Ara les aplicaràs tu sol a funcionalitats noves de l'aplicació d'etiquetes, treballant amb un agent.

### 11.1. L'enunciat

Afig a l'aplicació **dues funcionalitats obligatòries** i, si t'hi arribes, **una d'opcional**:

| Funcionalitat | Tipus | Nivell | Què has de vigilar |
|---|---|---|---|
| **Buscador d'etiquetes** | Obligatòria | Bàsic | Entrada de l'usuari en una consulta |
| **Exportació a CSV** | Obligatòria | Bàsic | Qui pot exportar què, i dades que viatgen en un fitxer |
| **Imatge de fons per a l'etiqueta** | Opcional | Alt | Pujada d'arxius, esborrat, PDF |
| **Etiqueta en diversos idiomes** | Opcional | Alt | Entrada de l'usuari que canvia el comportament |

Treballes **sol**. La duplicació d'etiquetes del punt 10 era l'exemple: no compta com una de les teues.

### 11.2. Criteris que valen per a totes

Cada funcionalitat, a més dels seus criteris, ha de complir:

1. **L'aplicació continua funcionant** i `sail artisan test` passa, també els tests que ja hi havia.
2. **Té tests propis** que cobrixen els seus criteris, i han de poder fallar (punt 10.5).
3. **Ha passat per les cinc preguntes** del punt 8.2. Has de poder respondre-les per a cada canvi.
4. **Segueix les convencions** del projecte: l'`AGENTS.md`, la interfície en valencià i el disseny.
5. **Ha tingut una especificació escrita abans** (punt 2), amb les cinc parts.

### 11.3. Buscador d'etiquetes (obligatòria)

**Què és.** Un camp de cerca a la portada pública que filtra les etiquetes publicades per text.

**Criteris d'acceptació:**

1. A la portada hi ha un formulari de cerca amb el mètode `GET`: la URL (`/?q=taronja`) es pot copiar i compartir.
2. Busca el text al producte, la varietat i la descripció. Amb «Taronja» apareixen les etiquetes de taronja.
3. Funciona **junt amb el filtre per categoria** i la paginació: en canviar de pàgina, la cerca es manté.
4. Sense resultats, es mostra el missatge «No s'ha trobat cap etiqueta.».
5. Els caràcters `%` i `_` es tracten com a text normal: cercar `%` no torna totes les etiquetes.
6. No es consulten camps privats (només producte, varietat i descripció).

**Què has de revisar:**

- **Pregunta 3 (què valida):** la cerca és entrada de l'usuari dins d'una consulta. Comprova que l'agent no construïx el SQL concatenant el text.
- **`%` i `_` són comodins** en un `LIKE`. Si no els escapes, l'usuari pot cercar «tot». És fàcil que l'agent ho oblide.
- **Interacció amb el filtre de categoria** (que guarda la categoria en una cookie): comprova que la cerca i el filtre es combinen bé.
- **Consultes:** el projecte té `preventLazyLoading` actiu. Si el llistat falla en afegir la cerca, mira les relacions.

### 11.4. Exportació a CSV (obligatòria)

**Què és.** Un botó al panell que descarrega les etiquetes de l'usuari en un fitxer CSV.

**Criteris d'acceptació:**

1. El panell té un botó «Exporta a CSV».
2. Descarrega un fitxer `.csv` amb una fila de capçalera i una fila per etiqueta.
3. Només hi ixen les **etiquetes de l'usuari**, no les dels altres.
4. Les columnes són: categoria, producte, varietat, pes net (g), calibre, origen, lot, envasador, codi de producte, codi de barres i data d'envasat.
5. Sense etiquetes, el fitxer només té la capçalera.
6. Una descripció o un producte amb comes, cometes o salts de línia no trenca les columnes.
7. Un visitant sense sessió no pot arribar a l'exportació.
8. El fitxer s'obri sense errors amb un full de càlcul, amb les columnes separades i els accents ben vists.

**Què has de revisar:**

- **Preguntes 1 i 2 (qui, de qui):** és una ruta nova. Comprova que només torna les etiquetes de l'usuari que ha iniciat sessió.
- **Pregunta 3:** els valors són text lliure. Un valor que comença per `=`, `+`, `-` o `@` pot interpretar-se com una **fórmula** en obrir el CSV amb un full de càlcul. Decidix què fas i justifica-ho.
- **Quines dades surten del servidor.** No exportes `user_id` ni la ruta de la foto: pensa quines columnes calen de veres.

### 11.5. Imatge de fons per a l'etiqueta (opcional)

**Què és.** L'usuari pot pujar una imatge de fons per a cada etiqueta, i el PDF i la previsualització la mostren darrere de les dades.

**Criteris d'acceptació:**

1. Els formularis de crear i editar tenen un camp opcional «Imatge de fons».
2. Només s'accepten JPG i PNG de com a màxim 2 MB, amb missatges d'error en valencià.
3. Es guarda amb un **nom generat pel servidor**, mai amb el del client.
4. La previsualització i el PDF mostren el fons. Sense fons, tot continua igual.
5. En canviar la imatge o esborrar l'etiqueta, **s'esborra el fitxer antic**.
6. La ruta de la imatge **no es pot assignar** des del formulari (fora de `#[Fillable]`).
7. Només el propietari pot veure o canviar el fons (la Policy no canvia).

**Què has de revisar:**

- És la funcionalitat més pesada: tocarà una **migració nova** (mai una de les existents), el model, el formulari, el controlador i el PDF.
- **Pregunta 5 (què es queda sense esborrar):** el mateix error que al punt 9. Un agent que reemplaça la imatge pot oblidar-se de la d'abans.
- **El PDF** només entén CSS 2.1 i no carrega imatges per URL: comprova com es pinta el fons i com el posa l'agent.

### 11.6. Etiqueta en diversos idiomes (opcional)

**Què és.** L'usuari tria l'idioma en què es genera la previsualització i el PDF (valencià, castellà o anglés). Es tradueixen els **textos fixos** de l'etiqueta (com «Pes net», «Lot» o «Origen»); les dades que escriu l'usuari no es canvien.

**Criteris d'acceptació:**

1. La previsualització té un selector d'idioma amb tres opcions.
2. Els textos fixos de l'etiqueta apareixen en l'idioma triat, tant a la previsualització com al PDF.
3. Les dades de l'etiqueta (producte, origen, lot...) no es toquen.
4. Un idioma que no existix (`?lang=xx`) no trenca res: decidix què passa i justifica-ho.
5. Només el propietari pot veure i descarregar l'etiqueta, també en altres idiomes.
6. El correu amb el PDF també respecta l'idioma triat, o justifiques per què no.

**Què has de revisar:**

- **Pregunta 3 (què valida):** l'idioma ve de l'usuari i canvia el comportament de l'aplicació. Comprova que només s'accepten els tres valors permesos, i que l'agent no el passa directament a cap funció.
- **Decisió de disseny:** l'aplicació no té fitxers de traducció per a la interfície, però ací sí que cal algun mecanisme. Pots usar els fitxers d'idioma de Laravel o un de propi: justifica la teua elecció.
- **Consistència:** si l'etiqueta canvia d'idioma, la previsualització i el PDF han d'usar el mateix component.

### 11.7. Com treballar

Per a cada funcionalitat, segueix l'ordre que ja has practicat:

1. **Escriu l'especificació** amb les cinc parts (punt 2) abans de demanar res a l'agent.
2. Treballa amb l'agent **Plan**, revisa el pla i passa a **Build**.
3. **Revisa el diff** amb les cinc preguntes (punt 8). Mira també l'abast.
4. **Demana els tests**, revisa'ls (punt 7.3) i trenca el codi a propòsit per a comprovar que fallen (punt 10.5).
5. Executa `sail artisan test` i **prova-ho a mà** al navegador.
6. Si l'agent comet dos voltes el mateix error, **afig una regla a l'`AGENTS.md`** (punt 3).

Fes primer les dues obligatòries. L'opcional, només si et queda temps.

### 11.8. Lliurament i defensa

El professor t'explicarà en classe com es lliura i com es defén el projecte.

## 12. Errors comuns i punt de control

### 12.1. Errors comuns

**Laravel Boost i el servidor MCP**

| Símptoma | Causa | Solució |
|---|---|---|
| `opencode mcp list` diu `No MCP servers configured`, o l'agent diu que no veu cap eina de Boost | Boost ha generat `opencode.json` amb el format de la V1 i tens OpenCode V2, que no el llig | Posa el servidor dins de `mcp.servers` i lleva `enabled` (punt 4.2) |

**Tests**

| Símptoma | Causa | Solució |
|---|---|---|
| `Expected response status code [200] but received 500` i `Table 'testing.labels' doesn't exist` | Els tests usen la base de dades `testing`, que està buida | Afig `use RefreshDatabase;` a la classe del test (punt 5.2) |
| Els tests de la Policy passen amb `--filter`, però fallen tots quan s'executa `sail artisan test` | El `#[Seeder]` només s'aplica una vegada per execució, al primer test que prepara la base de dades; si abans corre `ExampleTest`, les categories no existixen | Sembra dins del test amb `$this->seed(CategorySeeder::class)` (punt 6.2) |

### 12.2. Llista de comprovació

- [ ] L'`AGENTS.md` és el teu (punt 3) i, baix, té el bloc `<laravel-boost-guidelines>` de Boost.
- [ ] `opencode mcp list` mostra `laravel-boost  connected`.
- [ ] `sail artisan test` passa tots els tests.
- [ ] `ExampleTest` usa `RefreshDatabase`.
- [ ] Els 9 tests de la Policy passen, i en comentar el `Gate::authorize` de `destroy` en falla un.
- [ ] Els tests de foto, codi de barres i correu els ha escrit l'agent, els has revisat amb la llista del punt 7.3 i fallen en trencar el codi (els tres canvis del punt 7.4).
- [ ] Has trobat els cinc problemes del diff del punt 9 i pots justificar l'ordre de gravetat.
- [ ] El botó «Duplica» crea una còpia amb la seua pròpia foto, i esborrar la còpia no esborra la foto de l'original.
- [ ] Del projecte final: les dues funcionalitats obligatòries, cada una amb la seua especificació, els seus tests i la revisió del diff.
- [ ] L'aplicació continua funcionant al navegador.

### 12.3. Fitxers

Estos són els fitxers nous o modificats en este bloc (sense comptar els de les funcionalitats que triïs per al projecte final):

```text
etiquetes/
├── .agents/skills/                       (nou: les skills de Boost)
├── .ai/rules/                            (nou, si has guardat alguna regla amb record-rule)
├── AGENTS.md                             (modificat)
├── boost.json                            (nou)
├── composer.json                         (modificat)
├── composer.lock                         (modificat)
├── opencode.json                         (nou, amb el format de la V2)
├── app/Http/Controllers/Panel/LabelController.php   (modificat)
├── resources/views/panel/labels/index.blade.php     (modificat)
├── routes/web.php                        (modificat)
└── tests/
    ├── Feature/
    │   ├── ExampleTest.php               (modificat)
    │   ├── LabelAuthorizationTest.php    (nou)
    │   ├── LabelDuplicateTest.php        (nou)
    │   ├── LabelPdfTest.php              (nou, escrit per l'agent)
    │   └── LabelPhotoTest.php            (nou, escrit per l'agent)
    └── Unit/
        └── Ean13Test.php                 (nou)
```

Els noms dels tests que escriu l'agent poden canviar d'un alumne a un altre.

### 12.4. Fitxers finals

Només hi ha dos fitxers que han canviat en més d'un moment del bloc, o que costen d'encertar.

::: details 📄 `AGENTS.md`

El teu text (punt 3) va **a dalt**. Baix, Boost afig el seu bloc `<laravel-boost-guidelines>`, que el genera ell i **no s'escriu a mà**. Estes són les teues regles tal com queden:

```markdown
# Etiquetes

Aplicació Laravel 13 per a crear etiquetes de productes alimentaris. Part pública: les etiquetes publicades, com a posts d'un blog. Part privada (panell): els usuaris registrats creen etiquetes, les generen en PDF i se les envien per correu.

## Entorn

- Tot funciona amb Laravel Sail (Docker). PHP i Composer NO estan instal·lats a la màquina: no els instal·les ni executes `php`, `composer` o `npm` directament.
- Comandes: `sail artisan ...`, `sail composer ...`, `sail npm ...`.
- Tests: `sail artisan test`. Format del codi: `sail pint`.
- Serveis: l'app a http://localhost, phpMyAdmin al 8080, Mailpit al 8025.
- Pila: Laravel 13, PHP 8.5, MySQL, Tailwind 4, PHPUnit 12 (no s'usa Pest).

## Idioma

- Identificadors del codi (classes, taules, camps, noms de ruta) en anglés: `Label`, `labels`, `panel.labels.index`.
- Interfície, missatges i comentaris en valencià, escrits directament a les vistes. No hi ha fitxers de traducció.
- Les URL són en valencià: `/panell/etiquetes`.

## Convencions de Laravel 13

- Als models, atributs en lloc de propietats: `#[Fillable([...])]`, `#[Scope]`, i `casts()` com a mètode.
- Vistes amb components Blade (`<x-...>`), no `@extends` ni `@section`.
- Controladors fins, amb `extends Controller`. Els de la part privada, a `app/Http/Controllers/Panel`.
- Validació en un `FormRequest` (`app/Http/Requests`). Les regles pròpies són classes (`artisan make:rule`), mai closures.
- `Model::preventLazyLoading()` està actiu: carrega les relacions amb `with()` o `load()`. No el desactives.
- Si existix una comanda `artisan make:...` per a crear alguna cosa, usa-la.

## Disseny

- Fons blanc i text negre. Els colors estan com a tokens a `resources/css/app.css` (`@theme`): `--color-brand`, `--color-brand-dark`, `--color-accent`, `--color-ink`.
- Targetes `rounded-2xl border border-ink/10 bg-white`, sense ombres. Botons blaus `rounded-full`, amb `cursor-pointer`.
- El color accent només com a fons, amb text negre. Reutilitza els components que ja existixen.

## Seguretat

- Les rutes privades van dins del grup `auth`.
- Cada acció sobre una etiqueta s'autoritza amb la Policy (`Gate::authorize`): cada usuari només toca les seues.
- Valida tota entrada amb un `FormRequest`. Camps com `user_id` o `photo_path` no es poden assignar des del formulari (fora de `#[Fillable]`).
- Arxius pujats: valida tipus i grandària i guarda'ls amb `store()` (nom generat pel servidor), mai amb el nom que envia el client. Si s'esborra una etiqueta, s'esborra també el seu arxiu.
- Els errors previsibles (correu, PDF) es tracten amb `try/catch`, `report($e)` i un missatge amable, no amb la pàgina d'excepció.
- No guardes dades sensibles en cookies.

## Tests

- Cada funcionalitat nova porta tests a `tests/Feature` i han de passar abans de donar la tasca per acabada.

## Què no has de fer

- No canvies `composer.json` ni instal·les paquets sense preguntar.
- No modifiques migracions ja existents: crea'n una de nova.
- No llegisques ni mostres el `.env`.
- No facis canvis fora de l'abast de l'encàrrec. Si la tasca no està clara, pregunta.
```
:::

::: details 📄 `opencode.json`

Amb OpenCode V2 (punt 4.2):

```json
{
    "$schema": "https://opencode.ai/config.json",
    "mcp": {
        "servers": {
            "laravel-boost": {
                "type": "local",
                "command": ["vendor/bin/sail", "artisan", "boost:mcp"]
            }
        }
    }
}
```
:::