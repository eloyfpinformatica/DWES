## Solució de la pràctica. Rols d'usuari

### Fitxers modificats

| Fitxer | Canvi |
| --- | --- |
| Base de dades | Columna `rol` (script de la pràctica) |
| `src/Models/Usuari.php` | Propietat `rol`, constants, `esAdmin()` |
| `src/DAO/UsuariDAO.php` | `insert()` i `update()` guarden el rol |
| `src/Helpers/Sessio.php` | Guarda el rol; `rol()` i `esAdmin()` |
| `src/Controllers/BaseController.php` | Mètode `sensePermis()` (403) |
| `src/Controllers/LeadController.php` | `llistar()`, `carregarLead()`, `actualitzar()`, `canviarEstat()`, `eliminar()` |
| `vistes/errors/403.php` | Fitxer nou |
| `vistes/admin/leads/llistat.php` | El filtre d'estat només per a l'admin |
| `vistes/admin/leads/detall.php` | Formulari d'estat i zona d'eliminació només per a l'admin |
| `vistes/admin/leads/editar.php` | `$ambEstat` depén del rol |

### 1. `www/src/Models/Usuari.php`

```php
<?php

declare(strict_types=1);

namespace App\Models;

use InvalidArgumentException;

class Usuari
{
    public const ROL_ADMIN = 'admin';
    public const ROL_COMERCIAL = 'comercial';

    public const ROLS = [
        self::ROL_ADMIN => 'Administrador',
        self::ROL_COMERCIAL => 'Comercial',
    ];

    public function __construct(
        private ?int $id = null,
        private string $nom = '',
        private string $email = '',
        private string $contrasenya = '',
        private string $rol = self::ROL_COMERCIAL,
        private ?string $data_creacio = null
    ) {
        $this->setRol($rol);
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

    public function getRol(): string
    {
        return $this->rol;
    }

    public function setRol(string $rol): void
    {
        if (!array_key_exists($rol, self::ROLS)) {
            throw new InvalidArgumentException("Rol no vàlid: $rol");
        }

        $this->rol = $rol;
    }

    public function esAdmin(): bool
    {
        return $this->rol === self::ROL_ADMIN;
    }

    public function getDataCreacio(): ?string
    {
        return $this->data_creacio;
    }
}
```

Fixa't que el rol per defecte és `ROL_COMERCIAL` (mínim privilegi) i que el constructor valida el rol amb `setRol()`, igual que `Lead` valida l'estat.

### 2. `www/src/DAO/UsuariDAO.php`

Només canvien `insert()` i `update()`. Substitueix-los per:

```php
    public function insert(object $objecte): int
    {
        if (!$objecte instanceof Usuari) {
            throw new InvalidArgumentException('S\'esperava un objecte Usuari.');
        }

        $stmt = $this->pdo->prepare(
            'INSERT INTO usuaris (nom, email, contrasenya, rol)
             VALUES (:nom, :email, :contrasenya, :rol)'
        );
        $stmt->execute([
            ':nom' => $objecte->getNom(),
            ':email' => $objecte->getEmail(),
            ':contrasenya' => $objecte->getContrasenya(),
            ':rol' => $objecte->getRol(),
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
             SET nom = :nom, email = :email, contrasenya = :contrasenya, rol = :rol
             WHERE id = :id'
        );

        return $stmt->execute([
            ':nom' => $objecte->getNom(),
            ':email' => $objecte->getEmail(),
            ':contrasenya' => $objecte->getContrasenya(),
            ':rol' => $objecte->getRol(),
            ':id' => $objecte->getId(),
        ]);
    }
```

`getAll()`, `getById()`, `getByEmail()` i `delete()` **no canvien**: el `SELECT *` ja inclou la columna nova.

### 3. `www/src/Helpers/Sessio.php`

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
        $_SESSION['usuari_rol'] = $usuari->getRol();
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

    public static function rol(): string
    {
        return (string) ($_SESSION['usuari_rol'] ?? '');
    }

    public static function esAdmin(): bool
    {
        return self::rol() === Usuari::ROL_ADMIN;
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

Si no hi ha sessió, `rol()` torna una cadena buida, i `esAdmin()` és `false`: **per defecte, ningú és administrador**.

### 4. `www/src/Controllers/BaseController.php`

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

    public function noTrobat(): void
    {
        http_response_code(404);

        $this->renderitzar('errors/404', ['titol' => 'No trobat']);
    }

    public function sensePermis(): void
    {
        http_response_code(403);

        $this->renderitzar('errors/403', ['titol' => 'Accés denegat']);
    }
}
```

### 5. `www/vistes/errors/403.php` (fitxer nou)

```php
<?php

declare(strict_types=1);

require __DIR__ . '/../layouts/capcalera.php';
?>
<h1 class="text-5xl font-semibold tracking-tight">Accés denegat</h1>
<p class="mt-8 text-xl leading-relaxed text-slate-600">
    No tens permís per a fer esta acció.
</p>
<a href="/admin/"
   class="mt-12 inline-block rounded-lg bg-slate-900 px-8 py-4 text-xl font-medium text-white">
    Tornar al llistat
</a>

<?php require __DIR__ . '/../layouts/peu.php'; ?>
```

### 6. `www/src/Controllers/LeadController.php`

`LeadController` ja importa `Sessio` i `Lead`. Substitueix aquests cinc mètodes.

**`llistar()`**: força el filtre per als comercials i indica a la vista si cal mostrar-lo:

```php
    public function llistar(): void
    {
        $estat = $_GET['estat'] ?? '';
        $estat = array_key_exists($estat, Lead::ESTATS) ? $estat : null;

        if (!Sessio::esAdmin()) {
            $estat = Lead::ESTAT_NOU;
        }

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
            'mostrarFiltreEstat' => Sessio::esAdmin(),
        ]);
    }
```

Com que el filtre es força **abans** de cridar a `comptar()` i a `filtrar()`, les dos consultes el reben: el total i la paginació són coherents.

**`carregarLead()`**: el punt únic que protegix `detall`, `editar`, `actualitzar`, `canviarEstat` i `eliminar`:

```php
    private function carregarLead(): ?Lead
    {
        $id = (int) ($_GET['id'] ?? 0);

        $lead = $id > 0 ? $this->leadDao->getById($id) : null;

        if ($lead !== null && !Sessio::esAdmin() && $lead->getEstat() !== Lead::ESTAT_NOU) {
            return null;
        }

        return $lead;
    }
```

**`actualitzar()`**: només l'administrador pot tocar l'estat. Substitueix el fragment que valida l'estat (el que comença per `$estat = $_POST['estat'] ?? '';`) per:

```php
        if (Sessio::esAdmin()) {
            $estat = $_POST['estat'] ?? '';

            if (array_key_exists($estat, Lead::ESTATS)) {
                $lead->setEstat($estat);
            } else {
                $errors['estat'] = 'L\'estat no és vàlid.';
            }
        }
```

El resta del mètode queda igual. Si un comercial manipula la petició i envia un camp `estat`, **s'ignora**.

**`canviarEstat()`**:

```php
    public function canviarEstat(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirigir('/admin/');
        }

        if (!Sessio::esAdmin()) {
            $this->sensePermis();

            return;
        }

        $lead = $this->carregarLead();

        if ($lead === null) {
            $this->noTrobat();

            return;
        }

        $estat = $_POST['estat'] ?? '';

        if (array_key_exists($estat, Lead::ESTATS)) {
            $this->leadDao->canviarEstat($lead->getId(), $estat);
            Sessio::afegirAvis('L\'estat s\'ha actualitzat.');
        }

        $this->redirigir('/admin/' . Helpers::construirUrl([
            'accio' => 'detall',
            'id' => $lead->getId(),
        ]));
    }
```

**`eliminar()`**:

```php
    public function eliminar(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirigir('/admin/');
        }

        if (!Sessio::esAdmin()) {
            $this->sensePermis();

            return;
        }

        $lead = $this->carregarLead();

        if ($lead === null) {
            $this->noTrobat();

            return;
        }

        $this->leadDao->delete($lead->getId());
        Helpers::eliminarImatge($lead->getFitxer(), self::CARPETA_UPLOADS);

        Sessio::afegirAvis('El lead s\'ha eliminat correctament.');

        $this->redirigir('/admin/');
    }
```

::: info Nota
En `eliminar()` i `canviarEstat()`, la comprovació del rol va **abans** de carregar el lead. Així un comercial rep sempre un 403, siga quin siga l'`id`, i no es pot usar la diferència entre 403 i 404 per a esbrinar quins leads existeixen.
:::

### 7. Vistes

**`www/vistes/admin/leads/llistat.php`**: envolta el `select` d'estat amb una condició. El camp de cerca es queda per a tots dos rols:

```php
    <?php if ($mostrarFiltreEstat) : ?>
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
    <?php endif; ?>
```

**`www/vistes/admin/leads/detall.php`**: afig la importació junt a les altres:

```php
use App\Helpers\Sessio;
```

I envolta amb `<?php if (Sessio::esAdmin()) : ?> ... <?php endif; ?>` el formulari de canvi d'estat:

```php
<?php if (Sessio::esAdmin()) : ?>
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
<?php endif; ?>
```

I la zona d'eliminació del final:

```php
<?php if (Sessio::esAdmin()) : ?>
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
<?php endif; ?>
```

**`www/vistes/admin/leads/editar.php`**: afig la importació i canvia el valor de `$ambEstat`:

```php
use App\Helpers\Sessio;
```

```php
$ambEstat = Sessio::esAdmin();
```

### Reflexió: respostes model

1. **Per què comprovar el rol en el servidor?** Amagar un botó només canvia el que veu l'usuari. Qualsevol persona pot escriure l'adreça a mà, modificar el HTML amb F12 o enviar la petició amb `fetch`/`curl`. El servidor és l'únic lloc que l'usuari no pot manipular.
2. **403 o 404?** El **403** diu «sé qui eres i què vols, però no tens permís»; s'usa per a accions (eliminar, canviar estat). El **404** diu «això no existix»; s'usa per als leads que el comercial no pot vore, perquè no revela que existixen.
3. **Per què `comercial` per defecte?** Principi del mínim privilegi: si algú oblida indicar el rol, el que s'obté són els permisos més xicotets. Si fora `admin`, un oblit donaria accés complet.
4. **Un tercer rol, `supervisor`.** Caldria tocar: la columna `ENUM` de la BD, `Usuari` (constants i `ROLS`), `Sessio` (un mètode nou) i **cada lloc on es comprova `esAdmin()`**: `llistar()`, `carregarLead()`, `canviarEstat()`, `eliminar()`, `actualitzar()` i tres vistes. Si les regles estigueren concentrades en una única classe de permisos (per exemple, `Permisos::potEliminar()` o `Permisos::potVeure($lead)`), només canviaria eixa classe. Laravel ho fa així amb les *policies*.

### Solució dels reptes opcionals

#### Repte 1. El comercial pot marcar un lead com a `contactat`

**Controlador**: substitueix `canviarEstat()` per:

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

        if (!Sessio::esAdmin() && $estat !== Lead::ESTAT_CONTACTAT) {
            $this->sensePermis();

            return;
        }

        if (array_key_exists($estat, Lead::ESTATS)) {
            $this->leadDao->canviarEstat($lead->getId(), $estat);
            Sessio::afegirAvis('L\'estat s\'ha actualitzat.');
        }

        if (!Sessio::esAdmin()) {
            $this->redirigir('/admin/');
        }

        $this->redirigir('/admin/' . Helpers::construirUrl([
            'accio' => 'detall',
            'id' => $lead->getId(),
        ]));
    }
```

El comercial **només pot** enviar `contactat`; qualsevol altre estat rep un 403. Després del canvi, el lead ja no és `nou` i **desapareix de la seua vista**, així que redirigim al llistat. Si redirigírem al detall, rebria un 404.

**Vista (`detall.php`)**: el `if` del formulari d'estat passa a tindre un `else` per al comercial:

```php
<?php if (Sessio::esAdmin()) : ?>
    <?php /* ... formulari complet d'estat, com abans ... */ ?>
<?php else : ?>
    <form method="post"
          action="<?= Helpers::escapar(Helpers::construirUrl([
              'accio' => 'canviar-estat',
              'id' => $lead->getId(),
          ])) ?>"
          class="mt-12">
        <input type="hidden" name="estat"
               value="<?= Helpers::escapar(Lead::ESTAT_CONTACTAT) ?>">
        <button type="submit"
                class="rounded-lg bg-slate-900 px-6 py-3 text-lg font-medium text-white hover:bg-slate-700">
            Marcar com a contactat
        </button>
    </form>
<?php endif; ?>
```

#### Repte 2. Mostrar el rol en la capçalera

En `www/vistes/layouts/capcalera.php`, afig la importació:

```php
use App\Models\Usuari;
```

I substitueix el `<span>` del nom per:

```php
                    <span class="text-slate-500">
                        <?= Helpers::escapar(Sessio::nomUsuari()) ?>
                        · <?= Helpers::escapar(Usuari::ROLS[Sessio::rol()] ?? '') ?>
                    </span>
```

#### Repte 3. Gestió d'usuaris (esquema)

No es dona el codi complet. Els passos són:

1. Una acció nova (`crear-usuari`) en un `UsuariController` accessible només si `Sessio::esAdmin()`; en cas contrari, 403.
2. Un formulari amb nom, correu, contrasenya i rol (un `select` amb `Usuari::ROLS`).
3. Validació: tots els camps obligatoris, correu vàlid i contrasenya amb una longitud mínima.
4. Crear un `Usuari`, cridar a `xifrarContrasenya()` abans de guardar i inserir amb `UsuariDAO::insert()`.
5. Capturar la `PDOException` amb codi `23000` per a avisar que el correu ja existix.