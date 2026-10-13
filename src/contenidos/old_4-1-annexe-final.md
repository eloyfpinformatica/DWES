## Annex. Codi final dels fitxers

Alguns fitxers s'han anat modificant al llarg de diversos punts. Ací tens la **versió final completa** de cadascun, amb tots els canvis ja aplicats fins al punt 11. Si alguna cosa no et funciona, compara el teu fitxer amb esta versió.

| Fitxer | Punts on es modifica |
| --- | --- |
| `LeadController.php` | 10.1, 10.2, 10.3, 10.4 i 11.4 |
| `llistat.php` | 10.1, 10.2.6 i 11.4 |
| `formulari-lead.php` | 10.3.6 i 10.3.14 |
| `detall.php` | 10.2, 10.3.16, 10.4.4 i 11.4 |

### A.1. `www/src/Controllers/LeadController.php`

```php
<?php

declare(strict_types=1);

namespace App\Controllers;

use App\DAO\LeadDAO;
use App\Helpers\Helpers;
use App\Helpers\Sessio;
use App\Models\Lead;
use PDOException;
use RuntimeException;

class LeadController extends BaseController
{
    private const PER_PAGINA = 3;
    private const CARPETA_UPLOADS = __DIR__ . '/../../uploads';

    public function __construct(private LeadDAO $leadDao)
    {
    }

    // ---------------------------------------------------------------
    // Zona privada (administració)
    // ---------------------------------------------------------------

    public function llistar(): void
    {
        $estat = $_GET['estat'] ?? '';
        $estat = array_key_exists($estat, Lead::ESTATS) ? $estat : null;

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
        ]);
    }

    public function detall(): void
    {
        $lead = $this->carregarLead();

        if ($lead === null) {
            $this->noTrobat();

            return;
        }

        $this->renderitzar('admin/leads/detall', [
            'titol' => $lead->getNom(),
            'lead' => $lead,
        ]);
    }

    public function editar(): void
    {
        $lead = $this->carregarLead();

        if ($lead === null) {
            $this->noTrobat();

            return;
        }

        $this->renderitzar('admin/leads/editar', [
            'titol' => 'Editar lead',
            'lead' => $lead,
            'errors' => [],
        ]);
    }

    public function actualitzar(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirigir('/admin/');
        }

        $lead = $this->carregarLead();

        if ($lead === null) {
            $this->noTrobat();

            return;
        }

        $fitxerAntic = $lead->getFitxer();

        $lead->setNom(trim($_POST['nom'] ?? ''));
        $lead->setEmail(trim($_POST['email'] ?? ''));
        $lead->setTelefon($this->nullSiBuit($_POST['telefon'] ?? ''));
        $lead->setEmpresa($this->nullSiBuit($_POST['empresa'] ?? ''));
        $lead->setMissatge(trim($_POST['missatge'] ?? ''));

        $errors = $this->validar($lead);

        $estat = $_POST['estat'] ?? '';

        if (array_key_exists($estat, Lead::ESTATS)) {
            $lead->setEstat($estat);
        } else {
            $errors['estat'] = 'L\'estat no és vàlid.';
        }

        $fitxerNou = null;

        if ($errors === []) {
            try {
                $fitxerNou = Helpers::pujarImatge(
                    $_FILES['imatge'] ?? [],
                    self::CARPETA_UPLOADS
                );
            } catch (RuntimeException $e) {
                $errors['imatge'] = $e->getMessage();
            }
        }

        if ($errors !== []) {
            $this->renderitzar('admin/leads/editar', [
                'titol' => 'Editar lead',
                'lead' => $lead,
                'errors' => $errors,
            ]);

            return;
        }

        if ($fitxerNou !== null) {
            $lead->setFitxer($fitxerNou);
        } elseif (isset($_POST['eliminar_imatge'])) {
            $lead->setFitxer(null);
        }

        try {
            $this->leadDao->update($lead);
        } catch (PDOException $e) {
            Helpers::eliminarImatge($fitxerNou, self::CARPETA_UPLOADS);

            throw $e;
        }

        if ($fitxerAntic !== $lead->getFitxer()) {
            Helpers::eliminarImatge($fitxerAntic, self::CARPETA_UPLOADS);
        }

        Sessio::afegirAvis('Els canvis s\'han guardat correctament.');

        $this->redirigir('/admin/' . Helpers::construirUrl([
            'accio' => 'detall',
            'id' => $lead->getId(),
        ]));
    }

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

        if (array_key_exists($estat, Lead::ESTATS)) {
            $this->leadDao->canviarEstat($lead->getId(), $estat);
            Sessio::afegirAvis('L\'estat s\'ha actualitzat.');
        }

        $this->redirigir('/admin/' . Helpers::construirUrl([
            'accio' => 'detall',
            'id' => $lead->getId(),
        ]));
    }

    public function eliminar(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirigir('/admin/');
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

    // ---------------------------------------------------------------
    // Zona pública (formulari d'alta)
    // ---------------------------------------------------------------

    public function formulari(): void
    {
        $this->renderitzar('public/leads/formulari', [
            'titol' => 'Contacta amb nosaltres',
            'lead' => new Lead(),
            'errors' => [],
        ]);
    }

    public function guardar(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirigir('/public/');
        }

        $lead = new Lead(
            nom: trim($_POST['nom'] ?? ''),
            email: trim($_POST['email'] ?? ''),
            telefon: $this->nullSiBuit($_POST['telefon'] ?? ''),
            empresa: $this->nullSiBuit($_POST['empresa'] ?? ''),
            missatge: trim($_POST['missatge'] ?? '')
        );

        $errors = $this->validar($lead);
        $fitxer = null;

        if ($errors === []) {
            try {
                $fitxer = Helpers::pujarImatge(
                    $_FILES['imatge'] ?? [],
                    self::CARPETA_UPLOADS
                );
            } catch (RuntimeException $e) {
                $errors['imatge'] = $e->getMessage();
            }
        }

        if ($errors !== []) {
            $this->renderitzar('public/leads/formulari', [
                'titol' => 'Contacta amb nosaltres',
                'lead' => $lead,
                'errors' => $errors,
            ]);

            return;
        }

        $lead->setFitxer($fitxer);

        try {
            $this->leadDao->insert($lead);
        } catch (PDOException $e) {
            Helpers::eliminarImatge($fitxer, self::CARPETA_UPLOADS);

            throw $e;
        }

        $this->redirigir('/public/?accio=gracies');
    }

    public function gracies(): void
    {
        $this->renderitzar('public/leads/gracies', ['titol' => 'Gràcies']);
    }

    // ---------------------------------------------------------------
    // Mètodes privats
    // ---------------------------------------------------------------

    private function carregarLead(): ?Lead
    {
        $id = (int) ($_GET['id'] ?? 0);

        return $id > 0 ? $this->leadDao->getById($id) : null;
    }

    /**
     * @return array<string, string> Errors, indexats pel nom del camp
     */
    private function validar(Lead $lead): array
    {
        $errors = [];

        if ($lead->getNom() === '') {
            $errors['nom'] = 'El nom és obligatori.';
        } elseif (mb_strlen($lead->getNom()) > 100) {
            $errors['nom'] = 'El nom no pot tindre més de 100 caràcters.';
        }

        if ($lead->getEmail() === '') {
            $errors['email'] = 'El correu és obligatori.';
        } elseif (!filter_var($lead->getEmail(), FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'El correu no és vàlid.';
        } elseif (mb_strlen($lead->getEmail()) > 150) {
            $errors['email'] = 'El correu no pot tindre més de 150 caràcters.';
        }

        if (
            $lead->getTelefon() !== null
            && !preg_match('/^[0-9 +\-]{6,20}$/', $lead->getTelefon())
        ) {
            $errors['telefon'] = 'El telèfon ha de tindre entre 6 i 20 caràcters: xifres, espais, + o -.';
        }

        if ($lead->getEmpresa() !== null && mb_strlen($lead->getEmpresa()) > 100) {
            $errors['empresa'] = 'L\'empresa no pot tindre més de 100 caràcters.';
        }

        if ($lead->getMissatge() === '') {
            $errors['missatge'] = 'El missatge és obligatori.';
        } elseif (mb_strlen($lead->getMissatge()) < 10) {
            $errors['missatge'] = 'El missatge ha de tindre almenys 10 caràcters.';
        } elseif (mb_strlen($lead->getMissatge()) > 2000) {
            $errors['missatge'] = 'El missatge no pot tindre més de 2000 caràcters.';
        }

        return $errors;
    }

    private function nullSiBuit(string $valor): ?string
    {
        $valor = trim($valor);

        return $valor === '' ? null : $valor;
    }
}
```

### A.2. `www/vistes/admin/leads/llistat.php`

```php
<?php

declare(strict_types=1);

use App\Helpers\Helpers;
use App\Models\Lead;

// Enllaços de la paginació (mantenen els filtres actius)
$urlAnterior = Helpers::construirUrl([
    'pagina' => $pagina - 1,
    'estat' => $estat,
    'cerca' => $cerca,
]);
$urlSeguent = Helpers::construirUrl([
    'pagina' => $pagina + 1,
    'estat' => $estat,
    'cerca' => $cerca,
]);

require __DIR__ . '/../../layouts/capcalera.php';
?>
<h1 class="text-5xl font-semibold tracking-tight">Leads</h1>
<p class="mt-4 text-xl text-slate-500"><?= $total ?> resultats</p>

<form method="get" class="mt-12 flex flex-col gap-4 sm:flex-row">
    <input type="search" name="cerca" value="<?= Helpers::escapar($cerca) ?>"
           placeholder="Cerca per nom, correu o empresa"
           class="flex-1 rounded-lg border border-slate-300 px-5 py-4 text-xl">
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
    <button type="submit"
            class="rounded-lg bg-slate-900 px-8 py-4 text-xl font-medium text-white">
        Filtrar
    </button>
</form>

<?php if ($leads === []) : ?>
    <p class="mt-16 text-xl text-slate-500">No s'ha trobat cap lead.</p>
<?php else : ?>
    <ul class="mt-12 divide-y divide-slate-200">
        <?php foreach ($leads as $lead) : ?>
            <li class="flex flex-col gap-4 py-8 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <a href="<?= Helpers::escapar(Helpers::construirUrl([
                        'accio' => 'detall',
                        'id' => $lead->getId(),
                    ])) ?>" class="text-2xl font-medium hover:underline">
                        <?= Helpers::escapar($lead->getNom()) ?>
                    </a>
                    <p class="mt-2 text-lg text-slate-500">
                        <?= Helpers::escapar($lead->getEmail()) ?>
                        <?php if ($lead->getEmpresa() !== null) : ?>
                            · <?= Helpers::escapar($lead->getEmpresa()) ?>
                        <?php endif; ?>
                    </p>
                </div>
                <div class="flex items-center gap-6">
                    <?php require __DIR__ . '/../../parcials/estat.php'; ?>
                    <span class="text-lg text-slate-400">
                        <?= date('d/m/Y', strtotime((string) $lead->getDataCreacio())) ?>
                    </span>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>

    <?php if ($totalPagines > 1) : ?>
        <nav class="mt-12 flex items-center justify-between text-lg">
            <?php if ($pagina > 1) : ?>
                <a href="<?= Helpers::escapar($urlAnterior) ?>"
                   class="font-medium hover:underline">← Anterior</a>
            <?php else : ?>
                <span class="text-slate-300">← Anterior</span>
            <?php endif; ?>

            <span class="text-slate-500">Pàgina <?= $pagina ?> de <?= $totalPagines ?></span>

            <?php if ($pagina < $totalPagines) : ?>
                <a href="<?= Helpers::escapar($urlSeguent) ?>"
                   class="font-medium hover:underline">Següent →</a>
            <?php else : ?>
                <span class="text-slate-300">Següent →</span>
            <?php endif; ?>
        </nav>
    <?php endif; ?>
<?php endif; ?>

<?php require __DIR__ . '/../../layouts/peu.php'; ?>
```

### A.3. `www/vistes/parcials/formulari-lead.php`

```php
<?php

declare(strict_types=1);

use App\Helpers\Helpers;
use App\Models\Lead;
?>
<form method="post" action="<?= Helpers::escapar($action) ?>"
      enctype="multipart/form-data" novalidate class="space-y-10">

    <?php if ($errors !== []) : ?>
        <p class="rounded-lg bg-red-50 px-5 py-4 text-lg text-red-700">
            Revisa els camps marcats abans de continuar.
        </p>
    <?php endif; ?>

    <div>
        <label for="nom" class="block text-lg font-medium">Nom *</label>
        <input type="text" id="nom" name="nom"
               value="<?= Helpers::escapar($lead->getNom()) ?>"
               class="mt-3 w-full rounded-lg border border-slate-300 px-5 py-4 text-xl">
        <?php if (isset($errors['nom'])) : ?>
            <p class="mt-2 text-base text-red-600"><?= Helpers::escapar($errors['nom']) ?></p>
        <?php endif; ?>
    </div>

    <div>
        <label for="email" class="block text-lg font-medium">Correu electrònic *</label>
        <input type="email" id="email" name="email"
               value="<?= Helpers::escapar($lead->getEmail()) ?>"
               class="mt-3 w-full rounded-lg border border-slate-300 px-5 py-4 text-xl">
        <?php if (isset($errors['email'])) : ?>
            <p class="mt-2 text-base text-red-600"><?= Helpers::escapar($errors['email']) ?></p>
        <?php endif; ?>
    </div>

    <div>
        <label for="telefon" class="block text-lg font-medium">Telèfon</label>
        <input type="tel" id="telefon" name="telefon"
               value="<?= Helpers::escapar($lead->getTelefon()) ?>"
               class="mt-3 w-full rounded-lg border border-slate-300 px-5 py-4 text-xl">
        <?php if (isset($errors['telefon'])) : ?>
            <p class="mt-2 text-base text-red-600"><?= Helpers::escapar($errors['telefon']) ?></p>
        <?php endif; ?>
    </div>

    <div>
        <label for="empresa" class="block text-lg font-medium">Empresa</label>
        <input type="text" id="empresa" name="empresa"
               value="<?= Helpers::escapar($lead->getEmpresa()) ?>"
               class="mt-3 w-full rounded-lg border border-slate-300 px-5 py-4 text-xl">
        <?php if (isset($errors['empresa'])) : ?>
            <p class="mt-2 text-base text-red-600"><?= Helpers::escapar($errors['empresa']) ?></p>
        <?php endif; ?>
    </div>

    <div>
        <label for="missatge" class="block text-lg font-medium">Missatge *</label>
        <textarea id="missatge" name="missatge" rows="6"
                  class="mt-3 w-full rounded-lg border border-slate-300 px-5 py-4 text-xl leading-relaxed"><?= Helpers::escapar($lead->getMissatge()) ?></textarea>
        <?php if (isset($errors['missatge'])) : ?>
            <p class="mt-2 text-base text-red-600"><?= Helpers::escapar($errors['missatge']) ?></p>
        <?php endif; ?>
    </div>

    <?php if ($ambEstat ?? false) : ?>
        <div>
            <label for="estat" class="block text-lg font-medium">Estat</label>
            <select id="estat" name="estat"
                    class="mt-3 w-full rounded-lg border border-slate-300 px-5 py-4 text-xl">
                <?php foreach (Lead::ESTATS as $valor => $text) : ?>
                    <option value="<?= Helpers::escapar($valor) ?>"
                        <?= $lead->getEstat() === $valor ? 'selected' : '' ?>>
                        <?= Helpers::escapar($text) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <?php if (isset($errors['estat'])) : ?>
                <p class="mt-2 text-base text-red-600"><?= Helpers::escapar($errors['estat']) ?></p>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <div>
        <label for="imatge" class="block text-lg font-medium">
            <?= $lead->getFitxer() !== null ? 'Substituir la imatge' : 'Imatge (opcional)' ?>
        </label>

        <?php if ($lead->getFitxer() !== null) : ?>
            <img src="/uploads/<?= Helpers::escapar($lead->getFitxer()) ?>"
                 alt="Imatge actual" class="mt-4 max-h-48 rounded-xl">
            <label class="mt-4 flex items-center gap-3 text-lg text-slate-600">
                <input type="checkbox" name="eliminar_imatge" value="1" class="h-5 w-5">
                Eliminar la imatge actual
            </label>
        <?php endif; ?>

        <input type="file" id="imatge" name="imatge" accept="image/*"
               class="mt-3 block w-full text-lg text-slate-600">
        <p class="mt-2 text-base text-slate-400">JPG, PNG, WEBP o GIF. Màxim 2 MB.</p>
        <?php if (isset($errors['imatge'])) : ?>
            <p class="mt-2 text-base text-red-600"><?= Helpers::escapar($errors['imatge']) ?></p>
        <?php endif; ?>
    </div>

    <button type="submit"
            class="rounded-lg bg-slate-900 px-8 py-4 text-xl font-medium text-white hover:bg-slate-700">
        <?= Helpers::escapar($textBoto) ?>
    </button>
</form>
```

### A.4. `www/vistes/admin/leads/detall.php`

```php
<?php

declare(strict_types=1);

use App\Helpers\Helpers;
use App\Models\Lead;

require __DIR__ . '/../../layouts/capcalera.php';
?>
<a href="/admin/" class="text-lg text-slate-500 hover:text-slate-900">
    ← Tornar al llistat
</a>

<div class="mt-10 flex flex-col gap-6 sm:flex-row sm:items-start sm:justify-between">
    <div>
        <h1 class="text-5xl font-semibold tracking-tight">
            <?= Helpers::escapar($lead->getNom()) ?>
        </h1>
        <p class="mt-4 text-xl text-slate-500">Lead #<?= $lead->getId() ?></p>
    </div>
    <div class="flex items-center gap-4">
        <?php require __DIR__ . '/../../parcials/estat.php'; ?>
        <a href="<?= Helpers::escapar(Helpers::construirUrl([
            'accio' => 'editar',
            'id' => $lead->getId(),
        ])) ?>" class="rounded-lg bg-slate-900 px-6 py-3 text-lg font-medium text-white hover:bg-slate-700">
            Editar
        </a>
    </div>
</div>

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

<dl class="mt-16 divide-y divide-slate-200">
    <div class="grid gap-2 py-8 sm:grid-cols-3">
        <dt class="text-lg text-slate-500">Correu</dt>
        <dd class="text-xl sm:col-span-2">
            <a href="mailto:<?= Helpers::escapar($lead->getEmail()) ?>" class="underline">
                <?= Helpers::escapar($lead->getEmail()) ?>
            </a>
        </dd>
    </div>

    <div class="grid gap-2 py-8 sm:grid-cols-3">
        <dt class="text-lg text-slate-500">Telèfon</dt>
        <dd class="text-xl sm:col-span-2">
            <?= Helpers::escapar($lead->getTelefon() ?? '—') ?>
        </dd>
    </div>

    <div class="grid gap-2 py-8 sm:grid-cols-3">
        <dt class="text-lg text-slate-500">Empresa</dt>
        <dd class="text-xl sm:col-span-2">
            <?= Helpers::escapar($lead->getEmpresa() ?? '—') ?>
        </dd>
    </div>

    <div class="grid gap-2 py-8 sm:grid-cols-3">
        <dt class="text-lg text-slate-500">Missatge</dt>
        <dd class="text-xl leading-relaxed sm:col-span-2">
            <?= nl2br(Helpers::escapar($lead->getMissatge())) ?>
        </dd>
    </div>

    <?php if ($lead->getFitxer() !== null) : ?>
        <div class="grid gap-2 py-8 sm:grid-cols-3">
            <dt class="text-lg text-slate-500">Imatge</dt>
            <dd class="sm:col-span-2">
                <img src="/uploads/<?= Helpers::escapar($lead->getFitxer()) ?>"
                     alt="Imatge adjunta de <?= Helpers::escapar($lead->getNom()) ?>"
                     class="max-h-96 rounded-xl">
            </dd>
        </div>
    <?php endif; ?>

    <div class="grid gap-2 py-8 sm:grid-cols-3">
        <dt class="text-lg text-slate-500">Creat</dt>
        <dd class="text-xl sm:col-span-2">
            <?= date('d/m/Y H:i', strtotime((string) $lead->getDataCreacio())) ?>
        </dd>
    </div>

    <div class="grid gap-2 py-8 sm:grid-cols-3">
        <dt class="text-lg text-slate-500">Última modificació</dt>
        <dd class="text-xl sm:col-span-2">
            <?= date('d/m/Y H:i', strtotime((string) $lead->getDataActualitzacio())) ?>
        </dd>
    </div>
</dl>

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

<?php require __DIR__ . '/../../layouts/peu.php'; ?>
```

