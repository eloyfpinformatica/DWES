# Pràctica: Notes internes de seguiment per lead

**Mòdul:** Desenvolupament Web en Entorn Servidor (DWES) · CFGS DAW **Projecte base:** TechLeads **Modalitat:** individual **Durada estimada:** 2 sessions de classe + treball autònom

## 1. Context

L'equip comercial de TechLeads gestiona les sol·licituds que arriben des del formulari públic. Fins ara, l'aplicació només permet veure i editar les dades que el client ha enviat, però **no hi ha cap lloc on els comercials puguen deixar constància del seguiment** que fan de cada lead: una trucada, un correu enviat, un acord pendent, etc.

El teu encàrrec és ampliar l'aplicació perquè cada lead tinga un **historial de notes internes** que només veuran els usuaris autenticats del panell privat.

## 2. Objectius d'aprenentatge

Amb aquesta pràctica treballaràs:

- El disseny d'una **relació 1:N** entre dues taules (`leads` i `notes`) amb **claus foranes**.
- La creació d'un **model** nou (`Nota`) seguint les convencions del projecte.
- La creació d'un **DAO** nou (`NotaDAO`) amb consultes preparades PDO, incloent-hi un `JOIN` amb la taula `usuaris`.
- La integració del nou DAO a `config/bootstrap.php`.
- La modificació de **vistes i controladors** existents i la creació d'un nou controlador per a un formulari POST.
- La gestió de **sessions, rols i permisos** en el panell privat.
- Bones pràctiques de seguretat: consultes preparades, escapament de sortida amb `e()` i validació de dades d'entrada.

## 3. Descripció funcional

A la pàgina de detall d'un lead (`public/dashboard/lead.php`) s'ha de poder:

1. **Veure l'historial de notes** d'aquell lead, ordenades de la més recent a la més antiga (o cronològicament, a elecció teua, però has de deixar-ho documentat). Cada nota mostrarà el text, el **nom de l'usuari autor** i la **data i hora** de creació.
2. **Afegir una nota nova** mitjançant un formulari amb un camp de text.
3. **Eliminar una nota**, amb les restriccions de permisos descrites més avall.

Si un lead no té notes, s'ha de mostrar un missatge informatiu (per exemple, «Encara no hi ha notes per a aquest lead»).

Ademés, al **llistat de leads** (`leads.php`) s'ha de mostrar, per a cada lead, el **nombre de notes** que té.

### Regles de negoci

- Només els usuaris amb sessió iniciada poden veure, crear o eliminar notes.
- El text de la nota és **obligatori**, no pot estar format només per espais i tindrà una longitud màxima de **500 caràcters**.
- L'autor d'una nota és sempre **l'usuari que ha iniciat sessió**; mai no s'ha de rebre de l'usuari com a camp del formulari.
- Una nota **només pot ser eliminada pel seu autor o per un usuari amb rol `administrador`**.
- Les notes **no es poden editar** (l'historial ha de ser fiable). Si vols oferir edició, consulta l'apartat d'ampliacions.
- Quan s'elimina un lead, s'han d'eliminar també les seues notes.

## 4. Requisits tècnics

### 4.1. Base de dades

Afegeix a `databases/base-de-dades.sql` la definició de la nova taula `notes`, amb almenys aquests camps:

| Camp           | Descripció                                                   |
| -------------- | ------------------------------------------------------------ |
| `id`           | Clau primària autoincremental                                |
| `lead_id`      | Clau forana cap a `leads(id)`, amb `ON DELETE CASCADE`       |
| `usuari_id`    | Clau forana cap a `usuaris(id)`                              |
| `text`         | Contingut de la nota                                         |
| `data_creacio` | Data i hora de creació, amb valor per defecte `CURRENT_TIMESTAMP` |

Requisits:

- Defineix correctament els **tipus de dades**, les restriccions `NOT NULL` i els **índexs** que consideres necessaris.
- Inclou en el script **algunes notes d'exemple** que permeten provar l'aplicació (almenys 3 notes repartides en 2 leads diferents).
- Recorda que `docker-compose.yml` no importa automàticament el SQL i que el volum `db_data` conserva les dades: hauràs d'importar el script manualment (per exemple, amb phpMyAdmin) o recrear el volum. **Documenta en el lliurament quin procediment has seguit.**

### 4.2. Model

Crea `src/Model/Nota.php` sota el namespace `App\Model`, seguint l'estil de `Lead.php` i `Usuari.php`:

- Propietats per a tots els camps de la taula.
- Constructor, *getters* i *setters* (o l'estil que utilitze el projecte).
- Una propietat addicional per a guardar el **nom de l'usuari autor**, de manera que la vista puga mostrar-lo sense fer consultes addicionals.

### 4.3. DAO

Crea `src/DAO/NotaDAO.php` sota el namespace `App\DAO`. Ha d'implementar o adaptar el contracte `IDbAccess` segons el que siga coherent amb el projecte, i oferir com a mínim:

| Mètode                          | Funció                                                       |
| ------------------------------- | ------------------------------------------------------------ |
| `crear(Nota $nota)`             | Insereix una nota nova                                       |
| `obtenirPerLead(int $leadId)`   | Retorna les notes d'un lead, amb el nom de l'autor (`JOIN` amb `usuaris`) i ordenades per data |
| `obtenirPerId(int $id)`         | Retorna una nota concreta (necessari per a comprovar permisos) |
| `eliminar(int $id)`             | Elimina una nota                                             |
| `comptarPerLead()` o equivalent | Permet obtenir el nombre de notes de cada lead per al llistat |

Requisits:

- **Totes** les consultes amb valors externs han de ser **consultes preparades**.
- Per al recompte al llistat, evita el problema de les «N+1 consultes»: resol-ho amb una única consulta (`LEFT JOIN` + `GROUP BY`, o una subconsulta) en lloc de consultar el DAO una vegada per cada lead.

Registra el nou DAO a `config/bootstrap.php`, junt amb els ja existents.

### 4.4. Controlador i vistes

1. **`public/dashboard/lead.php`**: carrega les notes del lead amb `NotaDAO`, mostra'n el llistat i inclou el formulari per a afegir-ne una de nova. Gestiona el cas d'un lead inexistent.
2. **`public/dashboard/afegir_nota.php`** (nou): rep el POST, comprova la sessió, valida el text, crea la nota amb l'usuari de la sessió i redirigeix de nou al detall del lead. En cas d'error de validació, desa l'error i el valor anterior a la sessió i redirigeix (com ja fa `processar_lead.php`).
3. **`public/dashboard/eliminar_nota.php`** (nou): rep el POST, comprova la sessió i els permisos (autor o administrador), elimina la nota i redirigeix.
4. **`public/dashboard/leads.php`**: afegeix una columna amb el nombre de notes de cada lead.

Requisits generals:

- Totes les pàgines noves del panell han de cridar a `requereixLogin()`.
- Tot el contingut dinàmic mostrat en HTML s'ha d'escapar amb `e()`.
- Les operacions que modifiquen dades (afegir i eliminar) s'han de fer per **POST**, mai per GET.
- Els missatges i el text de la interfície han d'estar **en valencià/català**, seguint l'estil existent.
- Manté l'estructura de capes: les vistes no han de contindre consultes SQL; aquestes pertanyen als DAO.
- Reutilitza les cabeceres i peus de `vistes/` i l'estil Tailwind que ja utilitza el projecte.

### 4.5. Seguretat

Abans de lliurar, revisa expressament aquests punts:

- Cap consulta es construeix concatenant dades de l'usuari.
- El `lead_id` i l'`id` de la nota es validen com a enters i es comprova que existeixen.
- No es pot eliminar una nota d'un altre usuari si no ets administrador, encara que manipules el formulari o la petició.
- El nom de l'autor no es pren mai del formulari.
- Una nota amb codi HTML o `<script>` es mostra com a text, sense executar-se.

## 5. Passos suggerits

1. Dissenya la taula `notes` i prova el SQL a phpMyAdmin.
2. Crea el model `Nota`.
3. Crea `NotaDAO` i comprova'l amb una pàgina de prova temporal (com les `prova-*.php` existents).
4. Registra el DAO a `bootstrap.php`.
5. Mostra les notes a `lead.php`.
6. Implementa l'alta de notes i la gestió d'errors.
7. Implementa l'eliminació amb control de permisos.
8. Afig el recompte de notes al llistat.
9. Revisa la seguretat i elimina el codi de proves que no siga necessari.

## 6. Lliurament

Has de lliurar:

- El **codi font del projecte** complet (comprimit en `.zip` o mitjançant el repositori que s'indique), sense la carpeta `vendor/` ni documents pujats de prova.
- El **script SQL actualitzat** amb la taula `notes` i les dades d'exemple.
- Un breu fitxer **`LLEGIMME.md`** (màxim una pàgina) que incloga:
  - Els passos per a posar en marxa el projecte i importar la base de dades.
  - Una llista dels fitxers que has creat o modificat.
  - Les decisions de disseny que has pres (per exemple, l'ordre de les notes o els índexs definits).
  - Qualsevol limitació coneguda o apartat que no hagues pogut completar.

**Data límit de lliurament:** *\[a completar pel professorat\]*

## 7. Criteris d'avaluació

La puntuació total és de **10 punts**.

| Apartat                                                      | Punts |
| ------------------------------------------------------------ | ----- |
| Base de dades: taula, claus foranes, tipus, restriccions i dades d'exemple | 1,5   |
| Model `Nota`                                                 | 1     |
| `NotaDAO`: mètodes, consultes preparades i `JOIN`            | 2     |
| Integració a `bootstrap.php` i estructura per capes          | 0,5   |
| Vista de detall: llistat i formulari de notes                | 1,5   |
| Alta i eliminació: validació, permisos i redirecció          | 1,5   |
| Recompte de notes al llistat (sense consultes N+1)           | 1     |
| Seguretat (escapament, POST, comprovacions de sessió i permisos) | 0,5   |
| Qualitat del codi, convencions i `LLEGIMME.md`               | 0,5   |

### Rúbrica orientativa per nivells

| Apartat           | Excel·lent                                                   | Adequat                                                      | Insuficient                                       |
| ----------------- | ------------------------------------------------------------ | ------------------------------------------------------------ | ------------------------------------------------- |
| **Base de dades** | Claus foranes amb `ON DELETE CASCADE`, tipus adequats, índexs i dades de prova | Taula funcional però sense algun detall (índex, cascada o dades) | Taula sense claus foranes o amb tipus incorrectes |
| **DAO**           | Totes les consultes preparades, `JOIN` correcte i recompte eficient | Funciona però alguna consulta és millorable                  | Consultes concatenades o mètodes que falten       |
| **Permisos**      | Comprovats en el servidor per a autor i administrador        | Comprovats només a la vista (s'amaga el botó)                | Sense comprovació                                 |
| **Seguretat**     | Tot escapat, tot per POST, entrada validada                  | Algun punt aïllat sense revisar                              | Sortida sense escapar o operacions per GET        |
| **Estructura**    | Capes respectades, cap SQL a les vistes                      | Alguna barreja puntual                                       | SQL i lògica barrejats a les vistes               |

### Penalitzacions

- El projecte no arranca o no es pot instal·lar seguint el `LLEGIMME.md`: fins a **−2 punts**.
- Codi copiat sense comprendre'l o idèntic al d'un altre company: la pràctica es considera **no superada**.
- Text d'interfície en un idioma diferent del valencià/català: fins a **−0,5 punts**.

## 8. Ampliacions opcionals (fins a +1 punt)

Si has acabat amb temps, pots triar **una** d'aquestes ampliacions:

1. **Protecció CSRF** als formularis d'alta i eliminació de notes (token de sessió verificat al servidor).
2. **Paginació o botó «Mostra'n més»** a l'historial quan hi haja moltes notes.
3. **Notes fixades:** afig un camp `fixada` perquè una nota important aparega sempre al capdamunt.
4. **Transacció PDO** per a un cas combinat: en canviar l'estat d'un lead a `tancat`, crea automàticament una nota de sistema i fes-ho de forma atòmica.

Indica al `LLEGIMME.md` quina ampliació has fet i com provar-la.

## 9. Consells finals

- Fes commits o còpies de seguretat freqüents abans de tocar fitxers que ja funcionen.
- Prova sempre amb **dos usuaris diferents** (un `comercial` i un `administrador`) per a verificar els permisos.
- Prova també amb textos «incòmodes»: buit, només espais, 501 caràcters, comentaris HTML i cometes.
- Si alguna cosa no et funciona, activa els errors de PDO i llig el missatge complet abans de preguntar.


## 10. Plantilles

::: tip `lead.php` Historial de notes i formulari
<?php
/*
 * FRAGMENT PER A public/dashboard/lead.php
 * Apega'l dins del contingut de la pàgina, després de les dades del lead.
 *
 * Variables que ha de preparar el controlador (part PHP superior de lead.php):
 *   $lead         -> el lead actual (només s'usa getId())
 *   $notes        -> array d'objectes Nota (NotaDAO::obtenirPerLead)
 *   $usuariId     -> id de l'usuari de la sessió
 *   $esAdmin      -> true si el rol és 'administrador'
 *   $errorNota    -> missatge d'error de validació (o null)
 *   $valorNota    -> text anterior escrit per l'usuari (o '')
 *
 * Adapta els getters (getText, getAutorNom...) a l'estil del teu model Nota.
 */
?>

<section id="notes" class="mt-12">
    <h2 class="text-2xl font-semibold text-slate-900 mb-6">
        Notes de seguiment
        <span class="ml-2 text-base font-normal text-slate-500">(<?= count($notes) ?>)</span>
    </h2>

    <!-- Formulari per afegir una nota -->
    <form action="afegir_nota.php" method="POST"
          class="bg-white border border-slate-200 rounded-xl p-6 mb-8 shadow-sm">
        <input type="hidden" name="lead_id" value="<?= e((string) $lead->getId()) ?>">

        <label for="text" class="block text-base font-medium text-slate-700 mb-2">
            Afig una nota
        </label>
        <textarea id="text" name="text" rows="4" maxlength="500" required
                  placeholder="Ex.: He telefonat al client, torne a cridar dijous."
                  class="w-full rounded-lg border border-slate-300 px-4 py-3 text-base leading-relaxed
                         focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"><?= e($valorNota ?? '') ?></textarea>

        <?php if (!empty($errorNota)): ?>
            <p class="mt-2 text-sm text-red-600"><?= e($errorNota) ?></p>
        <?php endif; ?>

        <div class="mt-4 flex items-center justify-between">
            <span class="text-sm text-slate-500">Màxim 500 caràcters</span>
            <button type="submit"
                    class="inline-flex items-center rounded-lg bg-indigo-600 px-5 py-2.5 text-base font-medium
                           text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                Afegir nota
            </button>
        </div>
    </form>

    <!-- Historial de notes -->
    <?php if (empty($notes)): ?>
        <p class="rounded-xl border border-dashed border-slate-300 p-6 text-center text-slate-500">
            Encara no hi ha notes per a aquest lead.
        </p>
    <?php else: ?>
        <ul class="space-y-4">
            <?php foreach ($notes as $nota): ?>
                <li class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm">
                    <div class="flex items-start justify-between gap-4">
                        <div class="text-sm text-slate-500">
                            <span class="font-medium text-slate-800"><?= e($nota->getAutorNom()) ?></span>
                            ·
                            <time datetime="<?= e($nota->getDataCreacio()) ?>">
                                <?= e(date('d/m/Y H:i', strtotime($nota->getDataCreacio()))) ?>
                            </time>
                        </div>

                        <?php if ($esAdmin || $nota->getUsuariId() === $usuariId): ?>
                            <form action="eliminar_nota.php" method="POST"
                                  onsubmit="return confirm('Segur que vols eliminar aquesta nota?');">
                                <input type="hidden" name="nota_id" value="<?= e((string) $nota->getId()) ?>">
                                <input type="hidden" name="lead_id" value="<?= e((string) $lead->getId()) ?>">
                                <button type="submit"
                                        class="text-sm text-red-600 hover:text-red-800 hover:underline">
                                    Eliminar
                                </button>
                            </form>
                        <?php endif; ?>
                    </div>

                    <p class="mt-3 text-base leading-relaxed text-slate-800 whitespace-pre-line"><?= e($nota->getText()) ?></p>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</section>
:::

::: tip `leads.php` Columna per a mostrar el número de notes
<?php
/*
 * FRAGMENTS PER A public/dashboard/leads.php
 *
 * Variable que ha de preparar el controlador (part PHP superior de leads.php):
 *   $notesPerLead -> array [lead_id => nombre_de_notes], obtingut amb UNA sola
 *                    consulta (NotaDAO::comptarPerLead). Exemple:
 *                    $notesPerLead = $notaDAO->comptarPerLead();
 */
?>

<!-- 1) Capçalera de la taula: afig aquesta columna on et semble (p. ex. abans d'"Estat") -->
<th scope="col" class="px-4 py-3 text-center text-sm font-semibold text-slate-700">
    Notes
</th>

<!-- 2) Cel·la dins del bucle foreach dels leads (en la mateixa posició que la capçalera) -->
<?php $numNotes = $notesPerLead[$lead->getId()] ?? 0; ?>
<td class="px-4 py-4 text-center">
    <?php if ($numNotes > 0): ?>
        <a href="lead.php?id=<?= e((string) $lead->getId()) ?>#notes"
           class="inline-flex items-center rounded-full bg-indigo-100 px-3 py-1 text-sm font-medium
                  text-indigo-700 hover:bg-indigo-200"
           title="Veure les notes d'aquest lead">
            <?= e((string) $numNotes) ?>
        </a>
    <?php else: ?>
        <span class="inline-flex items-center rounded-full bg-slate-100 px-3 py-1 text-sm text-slate-500">
            0
        </span>
    <?php endif; ?>
</td>
:::