## 1. De les dades de mostra a la base de dades

Fins ara les etiquetes vivien en tres arrays dins de `SampleLabels`. Era una solució provisional: les dades es perden si canviem el codi, no es poden crear etiquetes noves i no hi ha usuaris reals. En este bloc les dades passen a una **base de dades MySQL** i les controlem amb tres peces de Laravel: migracions, models Eloquent i seeders.

### 1.1. Què canvia respecte a la unitat 4

A la unitat 4 férem este mateix camí amb PHP pur: connexió amb [PDO](/contenidos/4-acces-a-dades#_6-pdo-accedir-a-mysql-des-de-php), una classe de [connexió i injecció de dependències](/contenidos/4-acces-a-dades#_7-la-classe-dbconnection-i-la-injeccio-de-dependencies) i uns [DAO](/contenidos/4-acces-a-dades#_8-els-dao-leaddao-i-usuaridao) amb les consultes SQL escrites a mà. Laravel ofereix una peça per a cada cosa:

| Unitat 4 (PHP pur) | Laravel |
|---|---|
| Credencials i classe `DbConnection` | Variables del `.env` + `config/database.php` |
| Taules creades amb un script SQL | **Migracions** (punt 2) |
| DAO amb SQL a mà (`SELECT`, `INSERT`...) | **Models Eloquent** (punts 3 i 4) |
| Dades de prova inserides a mà | **Seeders i factories** (punts 5 i 6) |

Les consultes SQL no desapareixen: Eloquent les genera per nosaltres. Però continuem treballant amb la mateixa base de dades relacional, i les claus alienes del punt 2 són les mateixes que ja coneixes.

### 1.2. La connexió de Sail

Laravel llig les dades de connexió del fitxer `.env` (a l'arrel del projecte). Obri'l i busca les línies que comencen per `DB_`:

```ini
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=...
DB_USERNAME=...
DB_PASSWORD=...
```

Sail ja ho deixa configurat, **no cal tocar res**. Fixa't només en dos detalls:

- **`DB_HOST=mysql`**: no és `localhost`. Dins de Docker, cada servici és accessible pel seu nom; el nostre contenidor `mysql` es diu així en el `compose.yaml`.
- **`DB_DATABASE`, `DB_USERNAME` i `DB_PASSWORD`**: apunta'ls, els necessitaràs per a entrar a phpMyAdmin.

::: info Nota
Una instal·lació de Laravel feta fora de Sail pot portar SQLite per defecte. El nostre projecte usa MySQL perquè és el mateix motor que vam usar a la unitat 4 i el que trobaràs en producció.
:::

### 1.3. Comprovar que funciona

1. Obri phpMyAdmin a <http://localhost:8080> i entra amb l'usuari i la contrasenya del `.env`. A la base de dades del projecte han d'aparéixer les taules de les migracions per defecte, entre elles `users` i `migrations`.
2. Executa:

   ```bash
   sail artisan db:show
   ```

   Ha de mostrar la informació de la connexió (MySQL) i la llista de taules.
3. Obri la portada <http://localhost/>: ha de funcionar exactament igual que abans. Encara usem `SampleLabels`; a partir del punt 7 deixarà de fer-se.

## 2. Migracions: `categories` i `labels`

Les taules de la base de dades no les crearem a mà amb phpMyAdmin: les descriurem amb codi PHP, en uns arxius anomenats **migracions**.

### 2.1. Què és una migració

Una migració és una classe PHP que descriu un canvi en l'esquema de la base de dades: crear una taula, afegir una columna, etc. Les migracions viuen al projecte (`database/migrations`), de manera que l'estructura de la base de dades **viatja amb el codi**: qualsevol company que òbriga el projecte pot reconstruir les mateixes taules amb una sola ordre, sense que ningú li haja d'explicar quines columnes afegir a mà.

Cada migració té dos mètodes:

- **`up()`**: el que s'executa en aplicar-la (crear la taula).
- **`down()`**: el que la desfà (esborrar la taula).

El nom de l'arxiu porta una marca de temps, i Laravel les executa **per ordre**. Això importa: la taula `labels` tindrà claus alienes cap a `categories` i `users`, així que la de `categories` ha de crear-se **abans**. Per això generarem primer la de `categories`. (`users` ja existix: l'han creada les migracions per defecte.)

### 2.2. La migració de `categories`

Genera l'arxiu:

```bash
sail artisan make:migration create_categories_table
```

Laravel crea un arxiu `database/migrations/AAAA_MM_DD_HHMMSS_create_categories_table.php` (la marca de temps depén del moment en què l'executes). Substituïx tot el seu contingut per este:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
```

- **`Schema::create('categories', ...)`**: crea la taula. El nom va en **plural i en anglés**, per convenció; així Eloquent el trobarà sol al punt 3.
- **`$table->id()`**: clau primària autoincremental (`id`).
- **`$table->string('name')->unique()`**: una columna de text (`VARCHAR`) amb un índex únic, perquè no puguen existir dos categories amb el mateix nom.
- **`$table->timestamps()`**: afig `created_at` i `updated_at`, que Eloquent omplirà sol.
- **`down()`**: fa el contrari d'`up()`. Així podrem desfer la migració.

### 2.3. La migració de `labels`

Ara la de les etiquetes:

```bash
sail artisan make:migration create_labels_table
```

Substituïx el contingut de l'arxiu generat per este:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('labels', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->constrained()->restrictOnDelete();

            $table->string('product');
            $table->string('variety')->nullable();
            $table->unsignedInteger('net_weight_g');
            $table->string('caliber')->nullable();
            $table->string('origin');
            $table->string('batch');
            $table->string('packer');
            $table->string('product_code');
            $table->string('barcode', length: 13);
            $table->date('packaged_at');

            $table->text('description');
            $table->string('photo_path')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('labels');
    }
};
```

Els noms de les columnes van en anglés, igual que el codi; el valencià queda per a la interfície. Les decisions de disseny:

| Columna | Tipus | Per què |
|---|---|---|
| `variety`, `caliber` | `string` + `nullable()` | Hi ha productes sense varietat o sense calibre (l'ametla de mostra no en té) |
| `net_weight_g` | `unsignedInteger` | El pes net en **grams**, com a número enter: així podem formatar-lo («500 g») a la vista i al PDF |
| `barcode` | `string`, longitud 13 | Un codi de barres és text, no un número: un enter perdria els zeros inicials |
| `packaged_at` | `date` | Només necessitem el dia d'envasat, no l'hora |
| `description` | `text` | Text lliure, sense el límit dels 255 caràcters d'un `string` |
| `photo_path` | `string` + `nullable()` | Guardarà la ruta de la foto. Ara no hi ha subida (és al bloc 7), així que pot estar buida |

#### Les claus alienes

`foreignId('user_id')` crea una columna numèrica sense signe, del mateix tipus que el `id()` de la taula referenciada. `->constrained()` hi afig la **restricció de clau forana**: Laravel dedueix per convenció que `user_id` apunta a la columna `id` de `users`, i que `category_id` apunta a la de `categories`.

Com en SQL, una clau forana fa que la base de dades **no accepte** una etiqueta amb un `user_id` o un `category_id` que no existisca. I decidix què passa en esborrar el «pare»:

- **`user_id` → `cascadeOnDelete()`**: si s'esborra un usuari, s'esborren també les seues etiquetes (no tenen sentit sense autor).
- **`category_id` → `restrictOnDelete()`**: no es pot esborrar una categoria que encara tinga etiquetes; la base de dades ho impedix.

::: tip Bona pràctica
`cascadeOnDelete()` és còmode, però esborra dades en silenci. Usa'l només quan les dades filles no tinguen sentit sense la mare. Per a la resta, `restrictOnDelete()` és més segur.
:::

### 2.4. Executar, desfer i refer les migracions

Totes les ordres van amb `sail artisan`:

| Ordre | Què fa |
|---|---|
| `migrate` | Executa les migracions pendents |
| `migrate:status` | Mostra quines s'han executat i quines no |
| `migrate:rollback` | Desfà l'**últim lot** de migracions (el que s'ha executat en l'última ordre `migrate`) |
| `migrate:fresh` | **Esborra totes les taules** i torna a executar totes les migracions des de zero |

Quan executem `migrate`, Laravel només aplica les que encara no s'han executat: apunta les ja aplicades en una taula pròpia, `migrations`. Per això les migracions per defecte (com la d'`users`) no es repetixen.

::: warning Atenció
**No edites una migració que ja s'ha executat** esperant que la taula canvie: Laravel la dona per aplicada i no la tornarà a executar. En desenvolupament, refés-ho tot amb `sail artisan migrate:fresh` (esborra totes les dades) o desfés-la amb `migrate:rollback`, corregix-la i torna a executar `migrate`. En una aplicació real, amb dades de veritat, es crea una **migració nova** que modifique la taula.
:::

Executa-les:

```bash
sail artisan migrate
```

### 2.5. Comprovar que funciona

1. Abans d'executar `migrate`, la ordre `sail artisan migrate:status` ha de mostrar les dos migracions noves com a pendents.
2. Després de `sail artisan migrate`, obri phpMyAdmin (<http://localhost:8080>). Han d'aparéixer les taules `categories` i `labels`.
3. A `labels`, pestanya **Estructura**, comprova les columnes i els tipus: `user_id` i `category_id` han de ser enters sense signe, i `variety`, `caliber` i `photo_path` han d'admetre `NULL`. Si trobes la vista de relacions de la taula, hi han de constar les dos claus alienes cap a `users` i `categories`.
4. Prova les restriccions: executa `sail artisan migrate:rollback`. Les taules `categories` i `labels` han de desaparéixer (`users` es queda, perquè pertany a un lot anterior). Torna a executar `sail artisan migrate` i han de tornar.
5. Obri la portada <http://localhost/>: ha de continuar funcionant igual. Encara no hem canviat res de les vistes ni dels controladors.


## 3. Models Eloquent: `Category`, `Label` i `User`

Les taules ja existixen. Ara necessitem les classes PHP amb què el nostre codi les llegirà i les escriurà: els **models**. A la unitat 4 això ho feien l'[entitat i el DAO](/contenidos/4-acces-a-dades#_8-els-dao-leaddao-i-usuaridao) amb SQL escrit a mà; en Eloquent, **un model representa una taula** i cada objecte del model representa **una fila**.

### 3.1. Un model per taula

Genera els dos models nous:

```bash
sail artisan make:model Category
sail artisan make:model Label
```

Es creen a `app/Models/Category.php` i `app/Models/Label.php`. Per convenció, el nom del model va en **singular i en *CamelCase***, i Eloquent dedueix el nom de la taula passant-lo a **plural i *snake_case***: `Category` → `categories`, `Label` → `labels`. Per això a la migració del punt 2 posàrem els noms de taula en plural i en anglés.

El model `User` no cal crear-lo: ve amb Laravel (`app/Models/User.php`) i correspon a la taula `users`. Estén `Authenticatable`, una variant de `Model` preparada per a l'autenticació (bloc 6).

Eloquent també suposa que la clau primària es diu `id` i que la taula té `created_at` i `updated_at`, que ell mateix omplirà en crear o modificar un registre. La nostra taula ho compleix tot, així que els models no necessiten cap configuració de taula.

::: info Nota
`make:model` accepta opcions per a generar a l'hora la migració (`-m`), la factory (`-f`) o el seeder (`-s`). Nosaltres les fem per separat, una peça en cada punt, per a veure bé què fa cadascuna.
:::

### 3.2. Quins camps es poden omplir: `Fillable`

Un model Eloquent està **protegit per defecte** contra l'assignació massiva: si el crees amb `Category::create([...])` o l'ompli amb `fill([...])`, **només s'accepten els camps que has declarat** com a *fillable*; la resta es descarten en silenci. Cal declarar-ho a cada model amb l'atribut `Fillable`.

Substituïx el contingut de `app/Models/Category.php`:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name'])]
class Category extends Model
{
}
```

I el de `app/Models/Label.php`:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

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
}
```

Hem inclòs tots els camps de la migració **excepte `id` i `user_id`**: l'`id` el posa la base de dades, i l'autor (`user_id`) no ha de vindre mai d'un formulari, sinó de l'aplicació. Al bloc 5 vorem per què això importa.

::: info Nota
En molts tutorials i codi antic trobaràs aquesta mateixa llista escrita com una propietat, `protected $fillable = [...];`. Fa la mateixa funció; a la documentació de Laravel 13 s'usa l'atribut.
:::

### 3.3. Provar els models amb Tinker

**Tinker** és una consola interactiva (REPL) amb tota l'aplicació carregada: hi pots escriure codi PHP i usar els teus models directament, sense crear rutes ni vistes. És ideal per a provar Eloquent. S'obri així:

```bash
sail artisan tinker
```

Dins de Tinker, prova aquestes ordres d'una en una (cadascuna mostra el resultat):

```php
Category::create(['name' => 'Fruites']);
Category::create(['name' => 'Verdures']);

Category::all();
Category::find(1);
Category::where('name', 'Verdures')->first();
```

Què fa cada mètode:

- **`create([...])`**: insereix una fila i torna el model creat. Només admet els camps `Fillable`.
- **`all()`**: torna **totes** les files (una col·lecció de models).
- **`find(1)`**: torna la fila amb eixa clau primària, o `null` si no existix.
- **`where(...)->first()`**: filtra i torna la primera coincidència. Qualsevol consulta s'escriu encadenant mètodes (`where`, `orderBy`, `limit`...) i es tanca amb `get()` (varies files) o `first()` (una).

Per a modificar o esborrar, es treballa sobre un objecte:

```php
$categoria = Category::find(1);
$categoria->name = 'Fruita';
$categoria->save();

$categoria->delete();
```

`save()` actualitza la fila (i `updated_at`); `delete()` l'esborra. Per a eixir de Tinker, escriu `exit`.

::: warning Atenció
Si Tinker diu que no troba la classe `Category`, escriu el nom complet: `App\Models\Category`.
:::

### 3.4. Comprovar que funciona

1. Executa `sail artisan model:show Label`. Ha de mostrar el model, la seua taula (`labels`) i les seues columnes.
2. Entra en Tinker i crea les dos categories del punt 3.3. `Category::all()` n'ha de tornar dos.
3. Comprova que la taula `labels` també està enllaçada al model: `Label::count()` ha de tornar `0`, sense error.
4. Prova la protecció: `Category::create(['name' => 'Fruits secs', 'id' => 99])`. Mira l'`id` de la fila creada: **no** és 99, perquè `id` no és `Fillable` i s'ha descartat.
5. Obri phpMyAdmin (<http://localhost:8080>) i mira la taula `categories`: les files han d'estar-hi, amb `created_at` i `updated_at` omplits sense haver-los escrit tu.
6. Obri la portada <http://localhost/>: continua igual (encara usa `SampleLabels`).

Les categories que acabes de crear les perdràs al punt 5, quan fem `migrate:fresh --seed`; no passa res.


## 4. Relacions entre models

Les taules ja estan relacionades amb claus foranes (`user_id`, `category_id`). Ara fem que **els models també ho sàpien**, perquè des d'una etiqueta puguem arribar al seu autor i a la seua categoria sense escriure cap `JOIN`.

### 4.1. `belongsTo` i `hasMany`

Les nostres relacions són d'**un a molts**:

- Una **categoria** té moltes etiquetes; una etiqueta pertany a una categoria.
- Un **usuari** té moltes etiquetes; una etiqueta pertany a un usuari.

Eloquent les expressa com a **mètodes** del model. El costat «molts» (la taula amb la clau forana) usa `belongsTo`; el costat «un» usa `hasMany`:

| Model | Mètode | Tipus | Clau forana que busca |
|---|---|---|---|
| `Label` | `category()` | `belongsTo` | `category_id` (a `labels`) |
| `Label` | `user()` | `belongsTo` | `user_id` (a `labels`) |
| `Category` | `labels()` | `hasMany` | `category_id` (a `labels`) |
| `User` | `labels()` | `hasMany` | `user_id` (a `labels`) |

A `app/Models/Label.php`, afig els dos `use` a dalt i els mètodes **dins de la classe**:

```php
use Illuminate\Database\Eloquent\Relations\BelongsTo;

public function category(): BelongsTo
{
    return $this->belongsTo(Category::class);
}

public function user(): BelongsTo
{
    return $this->belongsTo(User::class);
}
```

A `app/Models/Category.php`:

```php
use Illuminate\Database\Eloquent\Relations\HasMany;

public function labels(): HasMany
{
    return $this->hasMany(Label::class);
}
```

I a `app/Models/User.php` (afig l'`use` amb els que ja hi ha, i el mètode dins de la classe, després dels mètodes existents):

```php
use Illuminate\Database\Eloquent\Relations\HasMany;

public function labels(): HasMany
{
    return $this->hasMany(Label::class);
}
```

No cal dir-li a Eloquent quines columnes uneix: **dedueix la clau forana pel nom**.

- En un `belongsTo`, pel **nom del mètode** més `_id`: el mètode `category()` busca la columna `category_id`.
- En un `hasMany`, pel **nom del model pare** en *snake_case* més `_id`: `Category` → `category_id`, `User` → `user_id`.

Per això els mètodes de `Label` es diuen exactament `category` i `user`.

::: info Nota
Si volguérem anomenar la relació `author()` en lloc de `user()`, Eloquent buscaria una columna `author_id`, que no existix. Caldria indicar-li la clau: `$this->belongsTo(User::class, 'user_id')`. Nosaltres mantenim `user()` i no cal res més.
:::

### 4.2. Usar les relacions des de Tinker

Necessitem un usuari. Laravel porta de sèrie una *factory* d'usuaris (al punt 6 vorem com funciona); de moment la fem servir per a crear-ne un de prova. Entra en Tinker:

```bash
sail artisan tinker
```

```php
$user = User::factory()->create();
$category = Category::first();
```

`Category::first()` torna la primera categoria; si no en tens cap (perquè has refet la base de dades), crea-la amb `Category::create(['name' => 'Fruites'])`.

Ara crea una etiqueta **a través de la relació**:

```php
$label = $user->labels()->create([
    'category_id'  => $category->id,
    'product'      => 'Taronja',
    'variety'      => 'Navelina',
    'net_weight_g' => 1500,
    'caliber'      => '4',
    'origin'       => 'València',
    'batch'        => 'L2026-001',
    'packer'       => 'Cooperativa del Camp',
    'product_code' => 'TAR-001',
    'barcode'      => '8412345678905',
    'packaged_at'  => '2026-11-02',
    'description'  => 'Taronja de collita pròpia, dolça i amb molt de suc.',
]);
```

Fixa't que **no hem escrit `user_id`**: en crear l'etiqueta des de `$user->labels()`, Eloquent l'omple sol (i és el que volem, perquè `user_id` no és `Fillable`). Ara recorre la relació en els dos sentits:

```php
$label->category->name;
$label->user->name;

$category->labels;
$user->labels()->count();
```

- **`$label->category`** (sense parèntesis) és una **propietat dinàmica**: torna directament el model relacionat. Eloquent fa la consulta la **primera vegada** que l'uses (*lazy loading*) i desa el resultat.
- **`$category->labels`** torna la **col·lecció** d'etiquetes de la categoria.
- **`$user->labels()`** (amb parèntesis) torna la **relació com a consulta**, a la qual pots encadenar condicions: `$user->labels()->where('product', 'Taronja')->get()`, `->count()`, etc. Fes servir els parèntesis quan necessites filtrar, ordenar o crear.

::: tip Bona pràctica
Crear les etiquetes des de la relació (`$user->labels()->create(...)`) és la manera més segura d'assignar l'autor: el valor ve de l'objecte, no d'un camp que algú puga manipular.
:::

::: info Relacions 1:1 i N:M
Les relacions d'un a un i de molts a molts, amb els atributs de la taula pivot, es veuran a l'annex 1.
:::

### 4.3. Comprovar que funciona

1. `sail artisan model:show Label`: ara, a més de les columnes, ha de mostrar les relacions `category` i `user`. Fes el mateix amb `Category` i `User`: han de mostrar `labels`.
2. En Tinker, executa tot el punt 4.2: `$label->category->name` ha de tornar el nom de la categoria, `$label->user->name` el de l'usuari, i `$category->labels` ha de contindre l'etiqueta que acabes de crear.
3. Obri phpMyAdmin (<http://localhost:8080>): la taula `labels` ha de tindre la fila, amb `user_id` i `category_id` omplits.
4. Prova la regla `restrictOnDelete` del punt 2: `$category->delete();` ha de fallar amb una excepció de la base de dades (clau forana), perquè la categoria encara té etiquetes.
5. Prova la regla `cascadeOnDelete`: `$user->delete();` ha d'anar bé, i després `Label::count()` ha de tornar `0`, perquè s'han esborrat també les seues etiquetes.
6. Obri la portada <http://localhost/>: continua igual (encara usa `SampleLabels`).


## 5. Seeders: les categories

Les categories no són dades de prova: l'aplicació **necessita** que existisquen perquè es puga crear una etiqueta. Fins ara les hem creat a mà amb Tinker, i cada vegada que refem la base de dades es perden. Un **seeder** és una classe que omple la base de dades amb dades inicials, i es pot tornar a executar quan vulguem.

### 5.1. `CategorySeeder` i `DatabaseSeeder`

Els seeders viuen a `database/seeders`. Genera el de les categories:

```bash
sail artisan make:seeder CategorySeeder
```

Substituïx el contingut de `database/seeders/CategorySeeder.php` per este:

```php
<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $names = ['Fruites', 'Verdures', 'Fruits secs', 'Llegums'];

        foreach ($names as $name) {
            Category::firstOrCreate(['name' => $name]);
        }
    }
}
```

- **`run()`**: és el mètode que s'executa en llançar el seeder. Dins pots usar els teus models amb normalitat.
- **`firstOrCreate(['name' => $name])`**: busca una categoria amb eixe nom i, si no existix, la crea. Així el seeder es pot executar **més d'una vegada** sense duplicar res ni xocar amb l'índex únic de `name`.

Ara cal dir-li a `DatabaseSeeder` que l'execute. `DatabaseSeeder` és el seeder principal: és el que s'executa per defecte i, des d'ell, el mètode `call()` llança els altres, en l'ordre que li indiquem. A `database/seeders/DatabaseSeeder.php`, deixa el mètode `run()` així (esborra el que hi haguera dins):

```php
public function run(): void
{
    $this->call([
        CategorySeeder::class,
    ]);
}
```

Com `CategorySeeder` està al mateix *namespace* (`Database\Seeders`), no cal cap `use`. Si a dalt de l'arxiu queda algun `use` que ja no s'usa (per exemple, el d'`User`), pots esborrar-lo.

::: info Nota
Cada seeder va en el seu arxiu perquè, a mesura que el projecte creixca, `DatabaseSeeder` es manté xicotet i només decidix **l'ordre**. Això importa: al punt 6 afegirem els usuaris i les etiquetes, que necessiten que les categories ja existisquen.
:::

### 5.2. `migrate:fresh --seed`

Hi ha dos maneres d'executar els seeders:

| Ordre | Què fa |
|---|---|
| `sail artisan db:seed` | Executa `DatabaseSeeder` sobre la base de dades **tal com està** |
| `sail artisan migrate:fresh --seed` | **Esborra totes les taules**, torna a executar totes les migracions i després els seeders |

La segona és la que usarem sempre que vulguem partir d'una base de dades neta amb les dades inicials. Per a un seeder concret: `sail artisan db:seed --class=CategorySeeder`.

::: warning Atenció
`migrate:fresh` esborra **tots** els usuaris i les etiquetes, també els que has creat amb Tinker. En desenvolupament és just el que volem; mai l'executes sobre una base de dades amb dades de veritat.
:::

### 5.3. Comprovar que funciona

1. Executa:

   ```bash
   sail artisan migrate:fresh --seed
   ```

2. Obri phpMyAdmin (<http://localhost:8080>). La taula `categories` ha de tindre les quatre categories; `users` i `labels` han d'estar buides.
3. Executa `sail artisan db:seed` una segona vegada. No ha de donar error i la taula ha de continuar amb **quatre** files (no huit).
4. En Tinker (`sail artisan tinker`), `Category::count()` ha de tornar `4`.
5. Obri la portada <http://localhost/>: continua igual (encara usa `SampleLabels`).


## 6. Factories: etiquetes de prova

El seeder del punt 5 afegix dades fixes. Per a provar la portada necessitem **moltes etiquetes diferents**, cada una amb el seu autor i la seua categoria, i no volem escriure-les una a una. Una **factory** és una classe que sap fabricar un model amb dades aleatòries però coherents.

### 6.1. Què és una factory

Laravel ja en porta una: `database/factories/UserFactory.php`. Obri-la i fixa't en la seua estructura:

- El mètode **`definition()`** torna un array amb els valors per defecte de cada columna.
- La funció **`fake()`** dona accés a la llibreria Faker, que genera dades aleatòries (noms, correus, números, dates...).

Ja l'has usada al punt 4 amb `User::factory()->create()`. Les dos maneres de fabricar un model són:

- **`make()`**: crea l'objecte **en memòria**, sense guardar-lo.
- **`create()`**: el crea i el **guarda** a la base de dades. Amb `count(3)` en fabrica tres: `User::factory()->count(3)->create()`.

Perquè un model tinga el mètode `factory()`, cal que use el *trait* `HasFactory`; `User` ja el porta. Laravel troba la factory per convenció: per al model `Label`, busca la classe `LabelFactory` a l'espai de noms `Database\Factories`.

#### Dades en valencià

Faker pot generar noms i correus segons un idioma (*locale*). Obri `config/app.php` i busca l'opció `faker_locale`:

- Si la seua línia llig una variable d'entorn (`env('APP_FAKER_LOCALE', ...)`), canvia-la al `.env`: `APP_FAKER_LOCALE=ca_ES`.
- Si té un valor directe, canvia'l a `'ca_ES'`.

Això afecta només a les dades que genera Faker (els noms dels autors, per exemple). Els productes, les varietats i els textos de les etiquetes els posarem nosaltres, en valencià.

### 6.2. `LabelFactory`

Primer, el model `Label` necessita el *trait*. A `app/Models/Label.php`, afig l'`use` a dalt i el *trait* dins de la classe (si ja hi és, no cal fer res):

```php
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Label extends Model
{
    use HasFactory;

    // ...
}
```

Genera la factory:

```bash
sail artisan make:factory LabelFactory
```

I substituïx el contingut de `database/factories/LabelFactory.php` per este:

```php
<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Label>
 */
class LabelFactory extends Factory
{
    // Categoria => producte => varietats (null = sense varietat)
    private const CATALOG = [
        'Fruites' => [
            'Taronja' => ['Navelina', 'Valencia Late'],
            'Caqui' => ['Rojo Brillante'],
            'Magrana' => ["Mollar d'Elx"],
        ],
        'Verdures' => [
            'Carxofa' => ['Blanca de Tudela'],
            'Tomaca' => ['Raf'],
            'Pebrot' => [null],
        ],
        'Fruits secs' => [
            'Ametla' => ['Marcona', 'Largueta'],
            'Avellana' => ['Negreta'],
            'Nou' => ['Chandler'],
        ],
        'Llegums' => [
            'Llentia' => ['Pardina'],
            'Garrofó' => [null],
            'Cigró' => [null],
        ],
    ];

    public function definition(): array
    {
        $categoryName = fake()->randomElement(array_keys(self::CATALOG));
        $product = fake()->randomElement(array_keys(self::CATALOG[$categoryName]));
        $variety = fake()->randomElement(self::CATALOG[$categoryName][$product]);

        return [
            'user_id' => User::factory(),
            'category_id' => Category::where('name', $categoryName)->firstOrFail()->id,
            'product' => $product,
            'variety' => $variety,
            'net_weight_g' => fake()->randomElement([250, 500, 1000, 1500, 5000]),
            'caliber' => fake()->randomElement([null, '3', '4', '5']),
            'origin' => fake()->randomElement([
                'La Ribera (València)',
                'El Camp de Túria (València)',
                'La Safor (València)',
                'La Plana (Castelló)',
                'El Baix Segura (Alacant)',
            ]),
            'batch' => 'L'.fake()->numerify('####-###'),
            'packer' => fake()->randomElement([
                'Cooperativa Camp i Horta',
                'Fruits del Túria',
                'Envasats Mediterrani',
            ]),
            'product_code' => Str::upper(Str::substr($product, 0, 3)).'-'.fake()->numerify('###'),
            'barcode' => fake()->ean13(),
            'packaged_at' => fake()->dateTimeBetween('-30 days')->format('Y-m-d'),
            'description' => sprintf(fake()->randomElement([
                '%s de collita pròpia, amb el punt just de maduresa.',
                '%s de proximitat, ideal per a consumir en família.',
                '%s: selecció manual i envasat el mateix dia de la collita.',
            ]), $product),
        ];
    }
}
```

El catàleg que veus és el resultat del procés del punt 6.3. De moment, copia'l tal qual. Les decisions importants:

- **Coherència**: primer s'elig la categoria, d'ella el producte i d'ell la varietat. Així mai no eixirà una «Ametla» dins de «Fruites».
- **`category_id`**: busquem la categoria **pel nom** a la base de dades (`firstOrFail()` falla si no existix). Per això les categories han d'estar creades abans (punt 5).
- **`user_id => User::factory()`**: per a una clau forana, assignem una *factory*: si no s'indica un usuari, es crea un de nou. Al punt 6.4 vorem com reutilitzar uns quants.
- **`photo_path`** no apareix: és `nullable` i es quedarà en `NULL` fins al bloc 7.
- **`ean13()`** genera un codi de barres de 13 xifres, i `product_code` es construïx amb les tres primeres lletres del producte.

::: info Nota
Les *factories* i els seeders **desactiven la protecció d'assignació massiva**. Per això la factory pot omplir `user_id`, que no és `Fillable`: ací som nosaltres qui controlem les dades, no un formulari.
:::

### 6.3. IA com a assistent: generar el catàleg de mostra i revisar-lo

Des d'este bloc, la IA entra com a **assistent en tasques acotades, amb revisió obligatòria**. Una tasca ideal és el catàleg del punt anterior: llistes de productes i varietats que ens costaria temps escriure, i on un error no trenca res, però sí que pot passar desapercebut.

Un exemple de petició, concreta i amb el format que necessitem:

```text
Necessite dades de mostra per a una aplicació d'etiquetes de productes
agroalimentaris. Categories: Fruites, Verdures, Fruits secs i Llegums.
Per a cada categoria, dona'm 3 productes amb el nom en valencià i, per a
cada producte, 1 o 2 varietats que existisquen de veritat a Espanya. Si no
coneixes cap varietat, posa null. Torna-ho com un array associatiu de PHP:
categoria => producte => llista de varietats. No inventes res.
```

La IA et tornarà una resposta amb molt bona pinta. **Això no vol dir que siga correcta.** Abans de copiar-la:

- **Comprova les varietats.** Busca cada nom: les IA inventen varietats que sonen bé.
- **Comprova la categoria** de cada producte (una ametla no és una fruita ni un llegum).
- **Revisa l'ortografia en valencià** (`Tomaca`, `Garrofó`, `Cigró`...) amb un diccionari normatiu.
- **Comprova que el format encaixa** amb el codi: noms de categoria idèntics als del `CategorySeeder` i `null` per a «sense varietat».
- **Revisa la concordança** dels textos de les descripcions: una frase amb «seleccionada» deixa de valdre si el producte és masculí.

::: warning Atenció
Les categories del catàleg han de coincidir **exactament** amb les del `CategorySeeder` (`Fruites`, `Verdures`, `Fruits secs`, `Llegums`). Si la IA et torna «Fruits Secs» o «Fruites i hortalisses», `firstOrFail()` fallarà en executar la factory.
:::

### 6.4. Omplir la base de dades des de `DatabaseSeeder`

A `database/seeders/DatabaseSeeder.php`, afig els dos `use` a dalt i deixa el mètode `run()` així:

```php
use App\Models\Label;
use App\Models\User;

public function run(): void
{
    $this->call([
        CategorySeeder::class,
    ]);

    User::factory()->create([
        'name' => 'Usuari de prova',
        'email' => 'prova@example.com',
    ]);
    User::factory()->count(2)->create();

    Label::factory()
        ->count(15)
        ->recycle(User::all())
        ->create();
}
```

- **L'ordre importa**: les categories es creen **abans** que les etiquetes, perquè la factory les necessita.
- **L'usuari de prova** té un correu conegut, per a poder usar-lo quan arribem a l'autenticació (bloc 6). La contrasenya de la factory d'usuaris és `password`; comprova-ho a `UserFactory.php`.
- **`recycle(User::all())`**: en lloc de crear un usuari nou per a cada etiqueta, la factory **tria a l'atzar un dels tres usuaris** existents. Així cada autor acaba amb diverses etiquetes.

### 6.5. Comprovar que funciona

1. Executa:

   ```bash
   sail artisan migrate:fresh --seed
   ```

   No ha de donar cap error.
2. Obri phpMyAdmin (<http://localhost:8080>): `categories` ha de tindre 4 files, `users` 3 i `labels` 15.
3. A `labels`, comprova que **cada producte està en la seua categoria** (per exemple, `Ametla` amb la categoria de `Fruits secs`) i que hi ha etiquetes de més d'un autor.
4. En Tinker (`sail artisan tinker`): `Label::first()->category->name` i `Label::first()->user->name` han de tornar un nom coherent amb el producte. `User::count()` ha de tornar `3`.
5. Executa `Label::factory()->create()` en Tinker i després `User::count()`: ha de tornar `4`, perquè sense `recycle` la factory crea un usuari nou. Torna a la situació neta amb `sail artisan migrate:fresh --seed`.
6. Obri la portada <http://localhost/>: continua igual (encara usa `SampleLabels`; el canvi arriba al punt 7).