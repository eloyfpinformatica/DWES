# Bloc 4. Models de Dades

## Temporalització

7 hores

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

Per a modificar una taula existent, creem una nova migració: `sail artisan make:migration add_phone_to_users_table`. Dins de l'arxiu utilitzem 
```php
Schema::table('users', function (Blueprint $table) { 
    $table->string('phone')->nullable(); 
});
```
en el mètode `up()` per a afegir la columna. 

En el mètode `down()` definim com desfer el canvi, per exemple, mitjançant `$table->dropColumn('phone');`. 

Finalment, executem `sail artisan migrate` per a aplicar els canvis.
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

::: info Per què utilitzem `create()` 
Utilitzem `create()` perquè ens permet crear un registre nou en la base de dades assignant tots els camps en una única operació, sense haver d'assignar cada propietat del model individualment. Per exemple, en lloc de fer:
```php
$category = new Category();
$category->name = $variable;
$category->save();
```
podem escriure directament `Category::create(['name' => $variable]);`. 

Això fa que el codi siga més senzill, llegible i fàcil de mantindre, especialment quan treballem amb models que tenen molts camps.
:::

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
use App\Models\Category;
    
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
2. Entra en Tinker i crea les dos categories del punt 3.3. `App\Models\ategory::all()` n'ha de tornar dos.
3. Comprova que la taula `labels` també està enllaçada al model: `Label::count()` ha de tornar `0`, sense error.
4. Prova la protecció: `App\Models\Category::create(['name' => 'Fruits secs', 'id' => 99])`. Mira l'`id` de la fila creada: **no** és 99, perquè `id` no és `Fillable` i s'ha descartat.
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
$category = App\Models\Category::first();
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


## 7. Els controladors passen a Eloquent

Les dades ja viuen a la base de dades. Ara els tres controladors deixen de demanar-les a `SampleLabels` i les demanen als models. Com que al bloc 3 vam deixar als controladors la responsabilitat de buscar les dades, el canvi és xicotet: una línia per controlador, més el canvi de la ruta del detall.

::: warning Atenció
Este punt i el següent són **un sol canvi** repartit en dos passos: ací canviem els controladors i al punt 8 les vistes. Entre els dos, les pàgines que mostren etiquetes **donaran error**, perquè les vistes encara esperen arrays. No proves el navegador fins al punt 8.5.
:::

### 7.1. Portada i panell: `Label::all()`

A la unitat 4, obtindre totes les files volia dir un mètode del [DAO](/contenidos/4-acces-a-dades#_8-els-dao-leaddao-i-usuaridao) amb la seua consulta SQL i el bucle que convertia cada fila en un objecte. Ara és una sola crida: `Label::all()` torna **una col·lecció de models `Label`**, un per cada fila de la taula `labels`.

A `app/Http/Controllers/HomeController.php`, substituïx l'`use` de `SampleLabels` i la línia del `return`:

```php
use App\Models\Label;

// ...

return view('home', ['labels' => Label::all()]);
```

I el mateix a `app/Http/Controllers/Panel/LabelController.php`:

```php
use App\Models\Label;

// ...

return view('panel.labels.index', ['labels' => Label::all()]);
```

La vista continua rebent una variable `labels`, igual que abans. El que canvia és el seu contingut: ja no són arrays, sinó objectes `Label` (punt 8).

::: info Nota
Al controlador del panell, `App\Models\Label` no xoca amb la classe `LabelController`: són noms diferents. El conflicte de noms que vam resoldre al bloc 3 era entre els dos `LabelController`, no amb el model.
:::

### 7.2. Binding implícit: `/etiquetes/{label}`

Fins ara, el detall d'una etiqueta rebia l'`id` com a text, el buscava a mà i, si no el trobava, feia `abort(404)`. Laravel pot fer tot això per nosaltres: si el paràmetre de la ruta i la variable del controlador **tenen el mateix nom** i la variable porta el tipus d'un model Eloquent, Laravel busca el model amb eixe valor i l'injecta. Es diu **binding implícit** (*implicit route model binding*).

A `routes/web.php`, canvia la ruta del detall:

```php
Route::get('/etiquetes/{label}', [LabelController::class, 'show'])
    ->whereNumber('label')
    ->name('labels.show');
```

Tres canvis, i un que **no** hi és:

- El paràmetre passa de `{id}` a **`{label}`**.
- El `whereNumber` s'ha de referir al **nom nou**: `whereNumber('label')`. Continua evitant que `/etiquetes/abc` arribe a consultar la base de dades.
- **El nom de la ruta, `labels.show`, no canvia.**

Ara, a `app/Http/Controllers/LabelController.php`, substituïx tot el contingut per este:

```php
<?php

namespace App\Http\Controllers;

use App\Models\Label;
use Illuminate\View\View;

class LabelController extends Controller
{
    public function show(Label $label): View
    {
        return view('labels.show', ['label' => $label]);
    }
}
```

- **`Label $label`**: el tipus (`Label`) li diu a Laravel quin model ha de buscar, i el nom (`$label`) ha de **coincidir amb `{label}`** de la ruta. Com vam avançar al bloc 3, els paràmetres normals s'injecten per ordre, però el binding implícit és l'excepció: ací **sí que importa el nom**.
- Laravel busca l'etiqueta per la seua clau primària (`id`) i, si no existix, genera sol una resposta **404**: ja no cal l'`abort(404)` ni la conversió `(int) $id`. La pàgina `errors/404.blade.php` continua sent la que es mostra.
- El mètode queda en una sola línia: rebre l'etiqueta i passar-la a la vista.

::: info Nota
Per defecte, el binding busca per `id`. Si algun dia volguérem URL amb un camp llegible (per exemple, un `slug`), s'indicaria la columna a la ruta: `{label:slug}`. Nosaltres mantenim l'`id` perquè és el més senzill.
:::

### 7.3. Esborrar `SampleLabels` i `app/Support`

`SampleLabels` va nàixer com a solució provisional: feia de model i de DAO mentre no teníem base de dades. Ara que cap controlador la usa, s'ha d'eliminar. Una classe que ja no usa ningú només serveix per a confondre.

Des de l'arrel del projecte, esborra l'arxiu i la carpeta (que queda buida):

```bash
rm -r app/Support
```

També pots fer-ho des de l'editor. Després, comprova que **no queda cap referència** a la classe:

```bash
grep -rn "SampleLabels" app routes resources
```

No ha d'eixir res. Si apareix alguna línia, és un `use` o una crida que s'havia quedat sense canviar: arregla-la amb `Label` (punts 7.1 i 7.2).

### 7.4. Comprovar que funciona

Com hem avisat al principi del punt, les vistes encara no estan adaptades: **la portada i el panell no funcionaran fins al punt 8.5**. Per això les comprovacions d'ara no passen per les vistes amb etiquetes.

1. Executa `grep -rn "SampleLabels" app routes resources`. No ha d'eixir cap línia.
2. Executa `sail artisan route:list`. La ruta del detall ha d'aparéixer amb la URI `etiquetes/{label}` (i no `etiquetes/{id}`), amb el nom `labels.show` i el controlador `LabelController`.
3. Obri <http://localhost/etiquetes/9999>. Com no hi ha cap etiqueta amb eixe `id`, Laravel ha de mostrar la pàgina **404** que vam crear al bloc 3.
4. Obri <http://localhost/etiquetes/abc>. També ha d'eixir la pàgina 404, perquè el `whereNumber('label')` no deixa passar un valor que no és un número.
5. Obri la portada <http://localhost/>. **Ha de donar un error**, i és el que esperem: cada targeta genera el seu enllaç amb `route('labels.show', ['id' => ...])`, i la ruta ara espera el paràmetre `label`. Llig el missatge de l'error i comprova que parla d'eixe enllaç. Ho arreglem al punt 8.3.

## 8. Adaptar les vistes: d'arrays a objectes

Els controladors ja envien models `Label`, però les vistes encara els lligen com si foren arrays. Este punt tanca el canvi que vam obrir al punt 7: en acabar, el projecte tornarà a funcionar, ara amb dades de la base de dades.

### 8.1. De `$label['product']` a `$label->product`

Un array s'indexa amb claus (`$label['product']`); un model Eloquent exposa les columnes com a **propietats** (`$label->product`). Estos són els canvis, segons el cas:

| Abans (array) | Ara (model) | Punt |
|---|---|---|
| `$label['product']`, `['variety']`, `['description']` | `$label->product`, `->variety`, `->description` | 8.1 |
| `$label['category']` | `$label->category->name` | 8.2 |
| `$label['author']` | `$label->user->name` | 8.2 |
| `$label['date']` | `$label->created_at->format('d/m/Y')` | 8.2 |
| `route('labels.show', ['id' => $label['id']])` | `route('labels.show', ['label' => $label])` | 8.3 |

Comencem pels canvis directes, que només canvien la sintaxi. A `resources/views/components/label-card.blade.php`:

```blade
@if ($label->variety)
    <p class="text-lg text-ink/60">{{ $label->variety }}</p>
@endif

<p class="mt-4 leading-relaxed text-ink/70">{{ $label->description }}</p>
```

La línia del títol (`<a href=...>`) la deixem per al punt 8.3, perquè canvia en dos llocs a la vegada.

A `resources/views/labels/show.blade.php`:

```blade
<x-slot:title>{{ $label->product }}</x-slot>
```

```blade
<h1 class="mt-6 font-serif text-5xl font-semibold tracking-tight text-ink sm:text-6xl">{{ $label->product }}</h1>

@if ($label->variety)
    <p class="mt-2 text-2xl text-ink/60">{{ $label->variety }}</p>
@endif
```

```blade
<p class="mt-8 text-xl leading-relaxed text-ink/80">{{ $label->description }}</p>
```

### 8.2. Dates amb Carbon, autor i categoria per relació

**Autor i categoria.** Ja no són un text dins de l'array, sinó **relacions** (punt 4): `$label->user` és el model de l'autor i `$label->category` el de la categoria. Per a mostrar el nom, hi accedim a través d'elles.

A `label-card.blade.php`, el distintiu de la categoria:

```blade
<x-badge>{{ $label->category->name }}</x-badge>
```

A `labels/show.blade.php`, el mateix distintiu:

```blade
<x-badge>{{ $label->category->name }}</x-badge>
```

**Dates.** Eloquent converteix `created_at` i `updated_at` en objectes **Carbon** automàticament; un objecte Carbon té mètodes per a formatar-lo. Els usem per a mostrar la data de publicació en format `dd/mm/aaaa`.

A `home.blade.php`, el peu de la targeta:

```blade
<x-slot:footer>
    {{ $label->user->name }} · {{ $label->created_at->format('d/m/Y') }}
</x-slot>
```

I a `labels/show.blade.php`, l'última línia de l'`<article>`:

```blade
<p class="mt-10 text-ink/60">{{ $label->user->name }} · {{ $label->created_at->format('d/m/Y') }}</p>
```

`packaged_at`, en canvi, és una columna nostra: Eloquent no la convertix sola, i tornaria un text (`2026-11-02`). Hem d'indicar-li que és una data. A `app/Models/Label.php`, afig el mètode `casts()` **dins de la classe**:

```php
protected function casts(): array
{
    return [
        'packaged_at' => 'date',
    ];
}
```

Un *cast* diu com s'ha de convertir una columna en llegir-la. Ara `$label->packaged_at` és un objecte Carbon, i podem formatar-lo igual que `created_at`. Aprofitem-ho per a mostrar-lo al detall: a `labels/show.blade.php`, afig esta línia **entre** la descripció i la línia de l'autor:

```blade
<p class="mt-6 text-ink/60">{{ number_format($label->net_weight_g, 0, ',', '.') }} g · Envasat el {{ $label->packaged_at->format('d/m/Y') }}</p>
```

`number_format` escriu el pes amb punt de milers (`1.500 g`).

::: info Nota
El format que posaríem dins del *cast* (`'datetime:Y-m-d'`) només afecta a com es **serialitza** el model quan es convertix en JSON o en un array. Per a pintar la data en una vista, el que usem és `format()`.
:::

::: tip Bona pràctica
Accedir a `$label->user` i a `$label->category` funciona, però cada accés pot fer una consulta a la base de dades. Amb 15 etiquetes no es nota. Això és exactament el que descobrirem, i arreglarem, al punt 10.
:::

### 8.3. Els enllaços: `route('labels.show', ['label' => $label])`

Al punt 7.4 vam veure l'error: la ruta espera un paràmetre `label` i els enllaços li passaven `id`. Ara els arreglem. A més, en lloc de passar l'`id`, passem **l'etiqueta sencera**: la funció `route()` extrau sola la clau del model (el seu `id`).

A `components/label-card.blade.php`, la línia del títol (ja amb el canvi del producte):

```blade
<a href="{{ route('labels.show', ['label' => $label]) }}" class="transition hover:text-brand">{{ $label->product }}</a>
```

I a `panel/labels/index.blade.php`, l'enllaç «Veure»:

```blade
<a href="{{ route('labels.show', ['label' => $label]) }}" class="text-brand hover:underline">Veure</a>
```

Així no queda cap enllaç amb `['id' => ...]`. La clau (`label`) és la que posa el nom a la ruta; el valor, l'etiqueta.

### 8.4. Un marcador mentre no hi ha foto

Cada etiqueta tindrà una foto del producte, però no la podrem pujar fins al bloc 7: la columna `photo_path` és buida. Perquè les vistes no hagen de canviar després, creem ara un component que **sempre pinta un marcador** (un quadre amb la inicial del producte). Al bloc 7 només hi afegirem la imatge real.

Crea `resources/views/components/label-photo.blade.php`:

```blade
@props(['label'])

<div {{ $attributes->merge(['class' => 'flex aspect-[4/3] items-center justify-center rounded-xl bg-ink/5']) }}>
    <span aria-hidden="true" class="font-serif text-6xl font-semibold text-ink/30">{{ mb_strtoupper(mb_substr($label->product, 0, 1)) }}</span>
</div>
```

- És un component anònim amb una propietat, `label`, com els que ja coneixes.
- Les classes de la propietat `$attributes->merge()` es **sumen** a les que passem des de fora.
- Usem `mb_substr` i `mb_strtoupper` (en lloc de `substr` i `strtoupper`) perquè també funcionen bé amb lletres accentuades com la «Ç».

Ara l'usem. A `label-card.blade.php`, com a **primer element** dins del `<div class="flex-1">`:

```blade
<x-label-photo :label="$label" class="mb-6" />
```

I a `labels/show.blade.php`, com a primer element de l'`<article>`:

```blade
<x-label-photo :label="$label" class="mb-8 max-w-sm" />
```

### 8.5. Comprovar que funciona

1. Obri la portada <http://localhost/>. Ha de mostrar 15 targetes, cada una amb el quadre amb la inicial, la categoria, el producte, la varietat (algunes no en tenen), la descripció i un peu amb l'autor i la data.
2. Fes clic en una etiqueta. El detall ha de mostrar el marcador, la categoria, el producte, la descripció, una línia amb el pes (per exemple, `1.500 g`) i la data d'envasat, i l'autor amb la data de publicació.
3. Obri el panell <http://localhost/panell/etiquetes>. Ha de mostrar les mateixes etiquetes amb els enllaços «Veure» i «Editar»; «Veure» ha d'obrir el detall.
4. Obri <http://localhost/etiquetes/9999>: ha de continuar eixint la pàgina 404.
5. Comprova que l'escapat continua funcionant. En Tinker (`sail artisan tinker`): `App\Models\Label::first()->update(['description' => 'Prova <em>HTML</em>'])`. Recarrega la portada: l'etiqueta ha de mostrar el text amb les etiquetes `<em>` **visibles**, no cursiva. Per a restaurar les dades, executa `sail artisan migrate:fresh --seed`.

::: warning Canvis en els estils
Recordeu que quan fem algun canvi en els estils o creem nous elements amb classes de Tailwind que no havíem utilitzat prèviament, cal executar `npm run build` per visualitzar el resultat.
:::

## 9. Paginació de la portada

La portada mostra totes les etiquetes de colp. Amb 15 encara cap, però una aplicació real en tindrà centenars: no té sentit carregar-les totes en cada visita. La solució és la **paginació**: mostrem unes poques per pàgina i afegim uns enllaços per a navegar.

### 9.1. `paginate()` al controlador

A `app/Http/Controllers/HomeController.php`, canvia la línia del `return`:

```php
return view('home', ['labels' => Label::latest('id')->paginate(6)]);
```

- **`latest('id')`**: ordena les etiquetes de la més nova a la més antiga. Ordenem per `id` (que no es repetix) i no per `created_at`: les etiquetes de mostra es creen totes en el mateix segon, i amb dates repetides l'ordre podria no ser estable, de manera que una etiqueta podria repetir-se o desaparéixer en canviar de pàgina.
- **`paginate(6)`**: en lloc de `all()`, torna només les 6 etiquetes de la pàgina actual. Laravel detecta la pàgina pel paràmetre `page` de la URL (`/?page=2`) i calcula sol quines files toca. Per a saber quantes pàgines hi ha, fa a més una consulta que **compta el total** de files.

El resultat ja no és una col·lecció, sinó un **paginador**: es pot recórrer amb `@forelse`, igual que abans, i a més sap en quina pàgina està i quantes n'hi ha. Per això la vista `home.blade.php` continua funcionant sense tocar el bucle.

::: info Nota
El panell **no** es pagina: continua amb `Label::all()` i mostra totes les etiquetes. La paginació és només de la portada pública.
:::

### 9.2. Els enllaços i la vista de paginació amb el nostre disseny

A `resources/views/home.blade.php`, afig els enllaços **després de la graella** (després del `</div>` del `grid`) i abans de `</x-layout>`:

```blade
<div class="mt-12">
    {{ $labels->links() }}
</div>
```

`links()` pinta els enllaços a les altres pàgines. Si hi ha una sola pàgina, no pinta res. Obri la portada i veuràs que ja funcionen, però amb l'aspecte per defecte de Laravel (grisos, ombres, text en anglés), que no encaixa amb el nostre disseny.

Laravel genera estos enllaços amb una vista que podem substituir. Publica-les al projecte:

```bash
sail artisan vendor:publish --tag=laravel-pagination
```

L'ordre copia les vistes de paginació a `resources/views/vendor/pagination`. L'arxiu `tailwind.blade.php` és el que Laravel usa per defecte: obri'l i fes-li una ullada, fixant-te en dues coses: rep una variable **`$paginator`** (el paginador) i una altra **`$elements`** (la llista de números de pàgina i punts suspensius).

Ara substituïx **tot el contingut** de `resources/views/vendor/pagination/tailwind.blade.php` per este:

```blade
@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Paginació" class="flex flex-wrap items-center justify-center gap-2">
        @if ($paginator->onFirstPage())
            <span class="rounded-full border border-ink/10 px-5 py-2 text-ink/30">Anterior</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="rounded-full border border-ink/10 px-5 py-2 text-ink transition hover:border-brand hover:text-brand">Anterior</a>
        @endif

        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="px-2 text-ink/40">{{ $element }}</span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span aria-current="page" class="rounded-full bg-brand px-4 py-2 font-semibold text-white">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" aria-label="Ves a la pàgina {{ $page }}" class="rounded-full px-4 py-2 text-ink transition hover:bg-ink/5">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="rounded-full border border-ink/10 px-5 py-2 text-ink transition hover:border-brand hover:text-brand">Següent</a>
        @else
            <span class="rounded-full border border-ink/10 px-5 py-2 text-ink/30">Següent</span>
        @endif
    </nav>
@endif
```

- **`hasPages()`**: si no fan falta diverses pàgines, no pinta res.
- **«Anterior» i «Següent»**: són enllaços si hi ha pàgina anterior o següent, i un text apagat si no (primera i última pàgina).
- **`$elements`**: cada element és un text (els punts suspensius `...`) o un array `pàgina => URL`. La pàgina actual es pinta com un botó blau i les altres com enllaços.
- El disseny és el del projecte: botons amb `rounded-full`, el blau com a color d'acció, **sense ombres** i els textos escrits directament en valencià.

Com és la vista per defecte, **tots els paginadors de l'aplicació** (també els del panell, si més endavant en paginem algun) tindran este mateix aspecte sense tocar res més.

::: tip Bona pràctica
Les altres vistes que ha publicat Laravel (Bootstrap, versió simple...) no les usem. Pots deixar-les on estan: no molesten.
:::

### 9.3. Comprovar que funciona

1. Obri la portada <http://localhost/>. Ha de mostrar **6 etiquetes** i, a sota, els enllaços de paginació: «Anterior» apagat, els números 1, 2 i 3 (el 1 en blau) i «Següent» actiu.
2. Fes clic en «2» (o en «Següent»). La URL ha de ser `http://localhost/?page=2` i ha de mostrar altres 6 etiquetes. Fes clic en «3»: ha de mostrar les 3 últimes, amb «Següent» apagat.
3. Comprova que **cap etiqueta es repetix** entre pàgines.
4. Obri el panell <http://localhost/panell/etiquetes>: ha de continuar mostrant les 15 etiquetes, sense paginació.
5. Comprova que els botons tenen el nostre disseny (cantons arredonits, blau, sense ombres) i no l'aspecte gris per defecte de Laravel.


## 10. La trampa: el problema N+1

Este és el fallit típic que genera la IA quan escriu codi amb Eloquent: **funciona**, es veu bé, no dona cap error... i fa moltes més consultes a la base de dades de les que cal. Hi ha una situació real en el nostre projecte, i la tenim ja escrita sense adonar-nos-en.

### 10.1. El codi que genera la IA i per què funciona «bé»

Imagina que li demanes a una IA que escriga la portada:

```text
Tinc una aplicació Laravel amb els models Label, User i Category (una
etiqueta pertany a un usuari i a una categoria). Escriu el controlador i
la vista de la portada: ha de mostrar les etiquetes paginades de 6 en 6 i,
a cada targeta, el producte, la categoria i l'autor.
```

Lo normal és que et torne un codi molt paregut al que ja tenim després dels punts 8 i 9. Les línies clau són estes:

```php
// HomeController
return view('home', ['labels' => Label::latest('id')->paginate(6)]);
```

```blade
{{-- Dins de la graella de la portada, per a cada etiqueta --}}
{{ $label->category->name }}
{{ $label->user->name }}
```

Obri la portada: es veu perfecta. Les 6 targetes, cada una amb la seua categoria i el seu autor, i la paginació funciona. **No hi ha cap error**, i per això és perillós: si només mires el resultat, no te n'adones.

La clau és que `$label->user` i `$label->category` són *lazy loading* (punt 4.2): Eloquent no carrega l'autor ni la categoria fins que els demanes. Com els demanem **dins d'un bucle**, per a cada etiqueta fa una consulta nova a la base de dades.

### 10.2. Detectar-lo

#### Mesurar les consultes

Entra en Tinker (`sail artisan tinker`) i repetix el que fa la portada, comptant les consultes:

```php
DB::enableQueryLog();

$labels = App\Models\Label::latest('id')->paginate(6);

foreach ($labels as $label) {
    $label->user->name;
    $label->category->name;
}

count(DB::getQueryLog());
```

El resultat ha de ser al voltant de **14** consultes: 1 per a comptar el total de pàgines, 1 per a les 6 etiquetes, 6 per als autors i 6 per a les categories. Per a veure-les:

```php
collect(DB::getQueryLog())->pluck('query');
```

Veuràs la mateixa consulta a `users` i a `categories` repetida, una vegada per etiqueta. Això és el problema **N+1**: 1 consulta per a obtindre N elements, i després N consultes més, una per cada element, per a obtindre les seues relacions.

::: warning Atenció
Ara són 14 consultes, però el número depén de les etiquetes de la pàgina. Amb 60 etiquetes per pàgina serien unes 122. La pàgina «funciona» mentre n'hi ha poques; quan creix, es torna lenta.
:::

#### Que l'aplicació et cride l'atenció

Mesurar a mà és útil, però no pots fer-ho per cada pàgina. Laravel ofereix una manera de fer **fallar** el codi que carrega relacions en *lazy loading*, mentre desenvolupes. A `app/Providers/AppServiceProvider.php`, dins del mètode `boot()`:

```php
use Illuminate\Database\Eloquent\Model;

public function boot(): void
{
    Model::preventLazyLoading(! $this->app->isProduction());
}
```
::: warning Error en `isProduction()`
Pot ser que VSCode marque `isProduction()` com un mètode que no existeix. És un fals positiu. Ignoreu-lo.
:::

- **`preventLazyLoading()`**: fa que Eloquent llance una excepció (`Illuminate\Database\LazyLoadingViolationException`) quan el codi intenta carregar una relació en *lazy loading*.
- **`! $this->app->isProduction()`**: només actua fora de producció. Així, si algun cas escapa a la revisió, a producció l'aplicació continua funcionant (lenta, però funcionant) en lloc de trencar-se.

Ara recarrega la portada <http://localhost/>: ha de donar un **error** que parla d'un intent de carregar una relació (`user` o `category`) del model `Label` en *lazy loading*. El codi que abans «funcionava» ara avisa del problema.

### 10.3. La solució: `with()`

La solució és dir-li a Eloquent, **en el moment de consultar**, quines relacions necessitarem. S'anomena *eager loading* (càrrega anticipada) i es fa amb `with()`. A `HomeController.php`:

```php
return view('home', [
    'labels' => Label::with(['user', 'category'])->latest('id')->paginate(6),
]);
```

Eloquent fa ara una sola consulta addicional **per relació**, no una per etiqueta:

```sql
select * from users where id in (1, 2, 3)
select * from categories where id in (1, 2, 4)
```

Total: comptar, etiquetes, usuaris i categories, o siga **4 consultes**, siguen 6 o 60 les etiquetes de la pàgina.

El panell té el mateix problema: cada targeta mostra la categoria. A `Panel/LabelController.php`:

```php
return view('panel.labels.index', ['labels' => Label::with('category')->get()]);
```

Fixa't que al panell només carreguem `category`: l'autor no es mostra, i **només es carrega el que la vista usa**.

::: tip Bona pràctica
Quan una IA et done codi que recorre models i accedix a les seues relacions dins d'un bucle o d'una vista, fes-te sempre esta pregunta: **quantes consultes fa?** Deixa `preventLazyLoading()` activat mentre desenvolupes i comprova-ho abans de donar el codi per bo. Mirar només que el resultat es veja bé no és revisar.
:::

::: info Nota
Laravel també pot carregar les relacions automàticament, sense `with()`: s'activa amb `Model::automaticallyEagerLoadRelationships();` al mateix `boot()`. Ací usem `with()` perquè quede clar què passa i perquè és el que trobaràs en la majoria del codi existent.
:::

### 10.4. Comprovar que funciona

1. Recarrega la portada <http://localhost/>: ha de tornar a funcionar, amb `preventLazyLoading()` encara activat. Comprova també les pàgines 2 i 3.
2. Obri el panell <http://localhost/panell/etiquetes>: ha de funcionar.
3. Obri el detall d'una etiqueta, per exemple <http://localhost/etiquetes/1>: ha de funcionar.
4. Mesura les consultes de la versió corregida. Ix de Tinker (`quit`) i torna a entrar (així el registre de consultes comença de zero) i executa:

   ```php
   DB::enableQueryLog();

   $labels = App\Models\Label::with(['user', 'category'])->latest('id')->paginate(6);

   foreach ($labels as $label) {
       $label->user->name;
       $label->category->name;
   }

   count(DB::getQueryLog());
   ```

   Ha de tornar **4**, en lloc de les 14 de abans.


   ## 11. Errors comuns i punt de control

Hem arribat al final del bloc. Abans de passar al següent, repassem els errors que et pots trobar, comprovem que tot està en el seu lloc i deixem constància dels arxius finals.

### 11.1. Errors comuns i la seua causa

| Error o símptoma | Causa probable | Solució |
|---|---|---|
| Error de connexió a la base de dades en migrar o en obrir l'aplicació | `DB_HOST` és `localhost` o `127.0.0.1` dins de Sail, en lloc de `mysql` | Posa `DB_HOST=mysql` al `.env` (punt 1.2) |
| Error de clau forana en executar la migració de `labels` | La migració de `categories` s'executa després perquè té una marca de temps posterior | Fes que `categories` s'execute primer i refés-ho amb `migrate:fresh` (punt 2.1) |
| He canviat una migració i la taula continua igual | La migració ja estava executada | `sail artisan migrate:fresh`, o `migrate:rollback` i `migrate` (punt 2.4) |
| Error de classe no trobada en un model, un seeder o un controlador | El `namespace` o el nom de l'arxiu no coincidix amb la classe, o falta un `use` (per exemple, `use App\Models\Label;`) | Revisa el `namespace`, el nom i els `use` (punts 3.1, 5.1, 7.1 i 7.2) |
| Tinker no troba `Label`, `Category` o `User` | Tinker no importa els models | Escriu el nom complet (`App\Models\Label`) o importa'l amb `use` (punt 3.3) |
| Una columna queda en blanc o a `NULL` després de `create()`, sense cap error | La columna no està en `#[Fillable]` i Eloquent la descarta en silenci | Afig-la a la llista (punt 3.2) |
| Error de taula inexistent en consultar un model | El nom del model no encaixa amb el de la taula (per exemple, un model en plural) | Model en singular, o indica la taula amb `#[Table('...')]` (punt 3.1) |
| `$label->category` o `$label->user` dona error de relació no definida, o un error de columna com `author_id` | El mètode de relació no existix o no es diu `category` / `user` | Defineix-lo amb eixe nom, o passa la clau forana com a segon argument (punt 4.1) |
| Error d'entrada duplicada a `categories` en executar el seeder dos voltes | S'ha usat `create` en lloc de `firstOrCreate` | Usa `firstOrCreate` (punt 5.1) |
| `migrate:fresh --seed` acaba bé però `categories` està buida | `DatabaseSeeder` no crida `CategorySeeder` | Afig-lo al `call([...])` (punt 5.1) |
| La factory falla amb un error de model no trobat | Les categories no existixen (ordre equivocat al seeder) o un nom del catàleg no coincidix amb el del seeder | `CategorySeeder` abans que les etiquetes i noms idèntics (punts 6.2 a 6.4) |
| `Label::factory()` no existix | Falta `use HasFactory;` al model | Afig el *trait* (punt 6.2) |
| Hi ha tants usuaris com etiquetes després de sembrar | Falta `recycle(User::all())` | Punt 6.4 |
| Una etiqueta té un producte en la categoria equivocada | El catàleg generat per la IA no s'ha revisat | Revisa el catàleg (punt 6.3) |
| Error de classe `App\Support\SampleLabels` no trobada | Queda un `use` o una crida a `SampleLabels` en algun arxiu | `grep -rn "SampleLabels" app routes resources` i canvia-ho per `Label` (punts 7.1 a 7.3) |
| `/etiquetes/1` dona 404 però l'etiqueta hauria d'existir | La base de dades està buida: s'ha fet `migrate:fresh` sense `--seed` | `sail artisan migrate:fresh --seed` (punt 7.2) |
| El detall no rep l'etiqueta que toca | El nom del paràmetre de la ruta (`{label}`) i el de la variable del mètode no coincidixen | Que coincidisquen (punt 7.2) |
| `Missing required parameter for [Route: labels.show] [URI: etiquetes/{label}] [Missing parameter: label].` (`UrlGenerationException`) | Queda un `route('labels.show', ['id' => ...])` en alguna vista o component | Usa `['label' => $label]` a `label-card` i al panell (punt 8.3) |
| Error en cridar `format()` sobre un text a la vista del detall | Falta el `casts()` de `packaged_at` a `Label` | Punt 8.2 |
| L'autor o la categoria isquen buits o donen error de propietat | Queda sintaxi d'array (`$label['author']`) o la relació està mal escrita | `$label->user->name` i `$label->category->name` (punt 8.2) |
| El marcador de la foto no té mida ni estil | Vite no està en marxa | `sail npm run dev` en una terminal apart (punt 8.4) |
| Error de mètode `links` inexistent a la portada | El controlador continua amb `Label::all()` | `paginate(6)` (punt 9.1) |
| Els botons de paginació tenen l'aspecte gris per defecte | No s'ha publicat la vista, o s'ha editat un altre arxiu | `vendor:publish` i edita `tailwind.blade.php` (punt 9.2) |
| Una etiqueta apareix a dues pàgines o en falta alguna | S'ordena per una columna amb valors repetits (`created_at`) | Ordena per `id` (punt 9.1) |
| `LazyLoadingViolationException` a la portada o al panell | La vista usa una relació que no s'ha carregat amb `with()` | Afig-la al `with()` del controlador (punt 10.3) |
| La portada «va bé» però fa moltes consultes | Codi sense `with()` i sense `preventLazyLoading()` | Punts 10.2 i 10.3 |

### 11.2. Llista de comprovació

Abans de donar el bloc per tancat, comprova que:

- [ ] `sail artisan migrate:fresh --seed` acaba sense errors.
- [ ] A phpMyAdmin hi ha 4 categories, 3 usuaris i 15 etiquetes.
- [ ] `sail artisan model:show Label` mostra les relacions `category` i `user`.
- [ ] `Label` té `#[Fillable]` amb tots els camps menys `id` i `user_id`, el *trait* `HasFactory` i el `casts()` de `packaged_at`.
- [ ] `grep -rn "SampleLabels" app routes resources` no mostra res i la carpeta `app/Support` ja no existix.
- [ ] `grep -rn "'id' =>" resources` i `grep -rn '\$label\[' resources` no mostren res.
- [ ] `sail artisan route:list` mostra `etiquetes/{label}`, i `/etiquetes/9999` i `/etiquetes/abc` donen 404.
- [ ] La portada mostra 6 etiquetes amb paginació en 3 pàgines, i el panell les 15.
- [ ] Les targetes i el detall mostren el marcador de la foto, la categoria, l'autor i la data.
- [ ] `preventLazyLoading()` està actiu a `AppServiceProvider` i la portada, el panell i el detall funcionen.
- [ ] La portada fa 4 consultes (mesurat amb Tinker).
- [ ] Sé explicar per què `$label->user->name` dins d'un bucle fa una consulta per etiqueta i com ho evita `with()`.
- [ ] He revisat el catàleg de mostra generat amb IA: he comprovat les varietats i les categories.

### 11.3. Arbre d'arxius esperat

Només els arxius nous o modificats en este bloc:

```text
app/
├── Http/Controllers/
│   ├── HomeController.php                   (modificat)
│   ├── LabelController.php                  (modificat)
│   └── Panel/LabelController.php            (modificat)
├── Models/
│   ├── Category.php                         (nou)
│   ├── Label.php                            (nou)
│   └── User.php                             (modificat)
├── Providers/AppServiceProvider.php         (modificat)
└── Support/                                 (esborrada)
database/
├── factories/LabelFactory.php               (nou)
├── migrations/
│   ├── AAAA_MM_DD_HHMMSS_create_categories_table.php   (nou)
│   └── AAAA_MM_DD_HHMMSS_create_labels_table.php       (nou)
└── seeders/
    ├── CategorySeeder.php                   (nou)
    └── DatabaseSeeder.php                   (modificat)
resources/views/
├── components/
│   ├── label-card.blade.php                 (modificat)
│   └── label-photo.blade.php                (nou)
├── labels/show.blade.php                    (modificat)
├── panel/labels/index.blade.php             (modificat)
├── vendor/pagination/tailwind.blade.php     (nou, publicat i modificat)
└── home.blade.php                           (modificat)
routes/web.php                               (modificat)
.env o config/app.php                        (modificat: faker_locale)
```

Els arxius que han canviat al llarg de diversos punts, en la seua versió final:

::: details app/Models/Label.php
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
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

    protected function casts(): array
    {
        return [
            'packaged_at' => 'date',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
```
:::

::: details app/Http/Controllers/HomeController.php
```php
<?php

namespace App\Http\Controllers;

use App\Models\Label;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        return view('home', [
            'labels' => Label::with(['user', 'category'])->latest('id')->paginate(6),
        ]);
    }
}
```
:::

::: details app/Http/Controllers/Panel/LabelController.php
```php
<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Label;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LabelController extends Controller
{
    public function index(): View
    {
        return view('panel.labels.index', ['labels' => Label::with('category')->get()]);
    }
}
```
:::

::: details database/seeders/DatabaseSeeder.php
```php
<?php

namespace Database\Seeders;

use App\Models\Label;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
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
}
```
:::

::: details resources/views/components/label-card.blade.php
```blade
@props(['label'])

<article {{ $attributes->merge(['class' => 'flex h-full flex-col rounded-2xl border border-ink/10 bg-white p-6']) }}>
    <div class="flex-1">
        <x-label-photo :label="$label" class="mb-6" />

        <x-badge>{{ $label->category->name }}</x-badge>

        <h2 class="mt-4 font-serif text-3xl font-semibold tracking-tight text-ink">
            <a href="{{ route('labels.show', ['label' => $label]) }}" class="transition hover:text-brand">{{ $label->product }}</a>
        </h2>

        @if ($label->variety)
            <p class="text-lg text-ink/60">{{ $label->variety }}</p>
        @endif

        <p class="mt-4 leading-relaxed text-ink/70">{{ $label->description }}</p>
    </div>

    @isset($footer)
        <footer class="mt-6 border-t border-ink/10 pt-4 text-sm text-ink/60">
            {{ $footer }}
        </footer>
    @endisset
</article>
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
    </article>
</x-layout>
```
:::

::: details resources/views/home.blade.php
```blade
<x-layout>
    <x-slot:title>Etiquetes publicades</x-slot>

    <h1 class="font-serif text-5xl font-semibold tracking-tight text-ink sm:text-6xl">Etiquetes</h1>
    <div class="mt-6 h-1 w-16 rounded-full bg-accent"></div>
    <p class="mt-6 max-w-xl text-xl leading-relaxed text-ink/70">Les últimes etiquetes publicades.</p>

    <x-button :href="route('register')" class="mt-8">Crea la teua etiqueta</x-button>

    <div class="mt-14 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
        @forelse ($labels as $label)
            <x-label-card :label="$label" class="transition hover:border-brand">
                <x-slot:footer>
                    {{ $label->user->name }} · {{ $label->created_at->format('d/m/Y') }}
                </x-slot>
            </x-label-card>
        @empty
            <p class="text-lg text-ink/60">Encara no hi ha cap etiqueta publicada.</p>
        @endforelse
    </div>

    <div class="mt-12">
        {{ $labels->links() }}
    </div>
</x-layout>
```
:::

::: details resources/views/panel/labels/index.blade.php
```blade
<x-layout>
    <x-slot:title>Les meues etiquetes</x-slot>

    <div class="flex flex-wrap items-end justify-between gap-6">
        <div>
            <h1 class="font-serif text-5xl font-semibold tracking-tight text-ink">Les meues etiquetes</h1>
            <div class="mt-6 h-1 w-16 rounded-full bg-accent"></div>
        </div>

        <x-button href="#">Nova etiqueta</x-button>
    </div>

    <div class="mt-14 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
        @forelse ($labels as $label)
            <x-label-card :label="$label">
                <x-slot:footer>
                    <div class="flex gap-6 font-semibold">
                        <a href="{{ route('labels.show', ['label' => $label]) }}" class="text-brand hover:underline">Veure</a>
                        <a href="#" class="text-brand hover:underline">Editar</a>
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

## Annex 1. Altres relacions: un a un i molts a molts

Al bloc 4 hem treballat la relació **d'un a molts**, que és la que usa el nostre projecte. Ací veiem les altres dos relacions bàsiques de les bases de dades relacionals: la d'**un a un** i la de **molts a molts**, esta última amb **atributs pivot**.

Els exemples són de l'aplicació d'etiquetes, però **no formen part del projecte base**: es proven amb Tinker i, en acabar, es poden desfer (apartat A1.5).

| Relació | Exemple | On va la clau forana | Mètodes del model |
|---|---|---|---|
| Un a molts (bloc 4) | Una categoria té moltes etiquetes | `category_id` a `labels` | `hasMany` / `belongsTo` |
| Un a un | Una etiqueta té **una única** fitxa nutricional | `label_id` (**única**) a `nutrition_facts` | `hasOne` / `belongsTo` |
| Molts a molts | Una etiqueta pot portar diversos al·lèrgens, i un al·lèrgen apareix en moltes etiquetes | **Taula pivot** `allergen_label`, amb les dos claus | `belongsToMany` als dos costats |

### A1.1. Un a un: la fitxa nutricional

Una relació d'un a un és, en el fons, una d'un a molts en què només es permet **un** fill. Sol usar-se quan unes dades són opcionals o ocupen molt, i no volem omplir la taula principal de columnes buides. No totes les etiquetes tindran fitxa nutricional, així que la guardem en una taula pròpia.

Genera la migració:

```bash
sail artisan make:migration create_nutrition_facts_table
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
        Schema::create('nutrition_facts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('label_id')->unique()->constrained()->cascadeOnDelete();
            $table->unsignedInteger('energy_kcal');
            $table->decimal('fat_g', 5, 1);
            $table->decimal('carbs_g', 5, 1);
            $table->decimal('protein_g', 5, 1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nutrition_facts');
    }
};
```

La clau de la relació d'un a un és `->unique()` sobre `label_id`: sense ell, la base de dades permetria **diverses** fitxes per a la mateixa etiqueta, i tindríem una relació d'un a molts. Amb ell, és la **base de dades** qui garantix que només n'hi haja una. Les columnes numèriques són els valors per cada 100 g (energia en kcal i la resta en grams, amb un decimal).

Executa la migració:

```bash
sail artisan migrate
```

Ara el model. Genera'l:

```bash
sail artisan make:model NutritionFact
```

Substituïx el contingut de `app/Models/NutritionFact.php`:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['energy_kcal', 'fat_g', 'carbs_g', 'protein_g'])]
class NutritionFact extends Model
{
    public function label(): BelongsTo
    {
        return $this->belongsTo(Label::class);
    }
}
```

Fixa't que `label_id` **no** és `Fillable`: igual que `user_id` a `Label`, no ha de vindre d'un formulari, sinó de la relació. La part del costat de la fitxa és idèntica a la que ja coneixes: un `belongsTo`.

A `app/Models/Label.php`, afig l'`use` a dalt i el mètode **dins de la classe**:

```php
use Illuminate\Database\Eloquent\Relations\HasOne;

public function nutritionFact(): HasOne
{
    return $this->hasOne(NutritionFact::class);
}
```

El costat «un» usa **`hasOne`** en lloc de `hasMany`. Eloquent deduïx la clau forana pel nom del model pare: busca `label_id` a la taula `nutrition_facts`. I fixa't en el nom del mètode: va en **singular** (`nutritionFact`), perquè torna **un** model i no una col·lecció.

::: info Nota
Si una etiqueta no té fitxa, `$label->nutritionFact` val `null`, i accedir a una propietat de `null` dona error. Amb `->withDefault()` al final del `hasOne`, Eloquent torna un model buit en lloc de `null` i el codi de les vistes no necessita comprovar si existix. Ací no ho fem per no complicar l'exemple.
:::

Prova-ho en Tinker (`sail artisan tinker`):

```php
$label = App\Models\Label::first();

$label->nutritionFact()->create([
    'energy_kcal' => 52,
    'fat_g' => 0.2,
    'carbs_g' => 13.8,
    'protein_g' => 0.3,
]);

$label->nutritionFact->energy_kcal;
$label->nutritionFact->label->product;
```

Com al punt 4.2, creem el fill **a través de la relació** (`nutritionFact()` amb parèntesis), de manera que `label_id` s'omple sol. Les dades són d'exemple.

**Comprovar que funciona**

1. `$label->nutritionFact->energy_kcal` ha de tornar `52`, i `$label->nutritionFact->label->product` el producte de l'etiqueta: la relació funciona en els dos sentits.
2. Torna a executar el `create(...)` anterior amb altres valors. Ha de **fallar** amb un error de la base de dades per un valor duplicat a `label_id`: és la restricció `unique()` impedint una segona fitxa.
3. A phpMyAdmin, la taula `nutrition_facts` ha de tindre una sola fila.

### A1.2. Molts a molts: la taula pivot

Una etiqueta pot portar diversos al·lèrgens (fruits de closca, cacauets...) i un al·lèrgen apareix en moltes etiquetes. No podem posar un `allergen_id` a `labels` (una etiqueta en tindria un de sol) ni un `label_id` a `allergens` (un al·lèrgen només estaria en una etiqueta). Cal una **tercera taula** que relacione les dos.

::: info Nota
Una **taula pivot** (o taula intermèdia) és una taula que només existix per a relacionar dos models en una relació de molts a molts: cada fila diu «esta etiqueta té este al·lèrgen». Normalment no té un model propi, i Eloquent la gestiona per nosaltres.
:::

El nom de la taula pivot es deduïx **ajuntant els noms dels dos models en singular, en ordre alfabètic**: `Allergen` i `Label` donen `allergen_label` (l'«A» va abans que la «L»). Si la nomenes d'una altra manera, Eloquent no la trobarà.

Genera les dos migracions, la dels al·lèrgens primer:

```bash
sail artisan make:migration create_allergens_table
sail artisan make:migration create_allergen_label_table
```

Contingut de la de `allergens`:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('allergens', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('allergens');
    }
};
```

I el de la taula pivot, `allergen_label`:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('allergen_label', function (Blueprint $table) {
            $table->foreignId('allergen_id')->constrained()->cascadeOnDelete();
            $table->foreignId('label_id')->constrained()->cascadeOnDelete();
            $table->boolean('may_contain_traces')->default(false);
            $table->timestamps();

            $table->primary(['allergen_id', 'label_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('allergen_label');
    }
};
```

- **Dos claus foranes**, una per cada costat de la relació. Amb `cascadeOnDelete()`, si s'esborra una etiqueta o un al·lèrgen, desapareixen també les seues files de la pivot.
- **`may_contain_traces`**: una columna extra que només té sentit en la **relació**, no en l'al·lèrgen ni en l'etiqueta: diu si l'al·lèrgen «hi és» (`false`) o «pot contindre traces» (`true`). És un **atribut pivot**.
- **`primary(['allergen_id', 'label_id'])`**: una clau primària **composta**, de les dos columnes. Impedix que el mateix al·lèrgen s'associe dos vegades a la mateixa etiqueta.

Executa-les:

```bash
sail artisan migrate
```

### A1.3. Els models: `belongsToMany` i els atributs pivot

Genera el model dels al·lèrgens (la taula pivot no en necessita):

```bash
sail artisan make:model Allergen
```

Contingut de `app/Models/Allergen.php`:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[Fillable(['name'])]
class Allergen extends Model
{
    public function labels(): BelongsToMany
    {
        return $this->belongsToMany(Label::class)
            ->withPivot('may_contain_traces')
            ->withTimestamps();
    }
}
```

I a `app/Models/Label.php`, afig l'`use` i el mètode **dins de la classe**:

```php
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

public function allergens(): BelongsToMany
{
    return $this->belongsToMany(Allergen::class)
        ->withPivot('may_contain_traces')
        ->withTimestamps();
}
```

La relació es defineix **als dos costats** amb el mateix mètode, `belongsToMany` (no hi ha «un» i «molts»: tots dos són «molts»). Cada part fa una cosa:

- **`belongsToMany(Allergen::class)`**: Eloquent busca la taula pivot `allergen_label` i les columnes `allergen_id` i `label_id`.
- **`withPivot('may_contain_traces')`**: per defecte, de la taula pivot només es lligen les claus. Si volem llegir una columna extra, cal indicar-la ací. Cada costat de la relació ha de declarar-la.
- **`withTimestamps()`**: fa que Eloquent òmpliga `created_at` i `updated_at` de la pivot en afegir o modificar una fila. La taula ha de tindre les dos columnes, i la nostra les té.

::: info Més professional
Si volem afegir comportament a la taula pivot (per exemple, un *cast* perquè `may_contain_traces` torne `true` o `false` en lloc d'`1` o `0`), es pot crear un model propi que estenga `Illuminate\Database\Eloquent\Relations\Pivot` i indicar-lo amb `->using(...)` a la relació. Ací usem la versió senzilla.
:::

Perquè hi haja al·lèrgens amb els quals provar, crea un seeder:

```bash
sail artisan make:seeder AllergenSeeder
```

Contingut de `database/seeders/AllergenSeeder.php`:

```php
<?php

namespace Database\Seeders;

use App\Models\Allergen;
use Illuminate\Database\Seeder;

class AllergenSeeder extends Seeder
{
    public function run(): void
    {
        $names = ['Fruits de closca', 'Cacauets', 'Gluten', 'Soja'];

        foreach ($names as $name) {
            Allergen::firstOrCreate(['name' => $name]);
        }
    }
}
```

No l'afegim a `DatabaseSeeder`: l'executem a mà, perquè l'annex no condicione el projecte base.

```bash
sail artisan db:seed --class=AllergenSeeder
```

### A1.4. Afegir, llevar i sincronitzar al·lèrgens

Les etiquetes de la categoria «Fruits secs» són les més coherents per a provar això. Entra en Tinker i prepara les dades:

```php
$label = App\Models\Category::where('name', 'Fruits secs')->first()->labels()->first();
$closca = App\Models\Allergen::where('name', 'Fruits de closca')->first();
$cacauets = App\Models\Allergen::where('name', 'Cacauets')->first();
$soja = App\Models\Allergen::where('name', 'Soja')->first();
```

Si `$label` és `null`, no hi ha cap etiqueta de «Fruits secs» entre les 15 de mostra: executa `sail artisan migrate:fresh --seed`, torna a executar el seeder d'al·lèrgens i comença de nou.

**Afegir amb `attach`**, amb dades pivot opcionals:

```php
$label->allergens()->attach($closca->id);
$label->allergens()->attach($cacauets->id, ['may_contain_traces' => true]);
```

Com sempre, usem la relació **amb parèntesis** (`allergens()`) per a modificar. El segon argument és un array amb els valors de les columnes extra de la pivot.

**Llegir**, amb la propietat `pivot` de cada al·lèrgen:

```php
foreach ($label->allergens as $allergen) {
    echo $allergen->name.': '.($allergen->pivot->may_contain_traces ? 'pot contindre traces' : 'conté').PHP_EOL;
}
```

`$allergen->pivot` és l'objecte amb les columnes de la taula pivot que hem demanat amb `withPivot`. I des de l'altre costat:

```php
$closca->labels;
```

torna totes les etiquetes que porten fruits de closca.

**Modificar i eliminar**:

```php
$label->allergens()->updateExistingPivot($cacauets->id, ['may_contain_traces' => false]);
$label->allergens()->detach($cacauets->id);
```

**Sincronitzar amb `sync`**: deixa la pivot **exactament** amb el que li passes.

```php
$label->allergens()->sync([
    $closca->id => ['may_contain_traces' => false],
    $soja->id => ['may_contain_traces' => true],
]);
```

Si ja havies llegit `$label->allergens` abans, Eloquent en té una còpia en memòria: recarrega-la amb `$label->load('allergens');` abans de tornar a llegir.

Estos són els mètodes principals per a gestionar una relació de molts a molts:

| Mètode | Què fa |
|---|---|
| `attach($id, [...])` | Afig una fila a la pivot, amb dades extra opcionals |
| `detach($id)` | Lleva la fila de la pivot; sense argument, les lleva totes. No esborra cap model |
| `sync([...])` | Deixa la pivot amb **només** els IDs indicats: afig els que falten i lleva els que sobren |
| `syncWithoutDetaching([...])` | Com `sync`, però sense llevar els que ja hi eren |
| `toggle([...])` | Si l'ID està relacionat, el lleva; si no, l'afig |
| `updateExistingPivot($id, [...])` | Modifica els atributs pivot d'una fila existent |

::: tip Bona pràctica
Si algun dia afegim al formulari de l'etiqueta una llista de caselles d'al·lèrgens, `sync()` és el mètode que et serà útil: rep els IDs marcats i deixa la pivot exactament així, siguen nous o s'hagen desmarcat. Si en mostres els al·lèrgens en un llistat d'etiquetes, carrega'ls amb `with('allergens')` (punt 10): recorda que el *lazy loading* està prohibit en el projecte.
:::

**Comprovar que funciona**

1. Després dels dos `attach`, el bucle de lectura ha d'escriure «Fruits de closca: conté» i «Cacauets: pot contindre traces». L'ordre pot variar.
2. `$closca->labels` ha d'incloure l'etiqueta amb què treballes.
3. Després de `updateExistingPivot` i `detach`, `$label->load('allergens')` i `$label->allergens` han de mostrar només «Fruits de closca».
4. Després del `sync`, el bucle ha de mostrar «Fruits de closca: conté» i «Soja: pot contindre traces», i res més.
5. A phpMyAdmin, la taula `allergen_label` ha de tindre exactament eixes dos files, amb `created_at` i `updated_at` omplits.

### A1.5. Errors comuns i com desfer l'annex

| Error o símptoma | Causa probable | Solució |
|---|---|---|
| Eloquent no troba la taula pivot | S'ha nomenat de manera diferent a la convenció (per exemple, `label_allergen`) | Ajunta els noms en singular i en ordre alfabètic: `allergen_label` (A1.2) |
| `$allergen->pivot->may_contain_traces` torna `null` | Falta `withPivot('may_contain_traces')` en la relació del costat des d'on es llig | Afig-lo als dos costats (A1.3) |
| Una etiqueta pot tindre dues fitxes nutricionals | Falta `->unique()` sobre `label_id` a la migració | Afig-lo i refés amb `migrate:fresh` (A1.1) |
| Un al·lèrgen apareix dos vegades en la mateixa etiqueta | Falta la clau primària composta de la pivot | Afig `primary([...])` (A1.2) |
| `LazyLoadingViolationException` en un llistat | Es llig `$label->allergens` per a cada etiqueta sense carregar-los abans | `Label::with('allergens')` (punt 10) |

**Desfer l'annex (opcional).** Les taules de l'annex no són necessàries per al projecte. Si no les vols, desfés les dos tandes de migracions:

```bash
sail artisan migrate:rollback
sail artisan migrate:rollback
```

La primera desfà `allergens` i `allergen_label` (es van executar juntes), i la segona `nutrition_facts`. Els models, els mètodes de `Label` i el seeder queden com a arxius sense ús, i els pots esborrar.