## Pràctica. Rols d'usuari: administrador i comercial

**Temps estimat:** 3 hores.

Fins ara, qualsevol persona que inicie sessió en l'administració pot fer **tot**: veure tots els leads, canviar-ne l'estat i eliminar-los. L'empresa vol que els **comercials** tinguen accés només al que necessiten: els leads **nous**, que són els que han de començar a treballar.

Ampliaràs el projecte de la unitat amb dos **rols**:

- **`admin`**: pot fer tot el que fa ara.
- **`comercial`**: només treballa amb els leads en estat `nou`.

::: info Nota
En esta pràctica es valora que les regles **es complisquen en el servidor**, no l'aspecte visual. Amagar un botó no és protegir una acció: qualsevol persona pot escriure l'adreça a mà.
:::

### Punt de partida

El projecte complet de la unitat (fins al punt 11), funcionant. Abans de començar, fes-ne una còpia (o un *commit* si utilitzes Git) per si necessites tornar arrere.

### Què cal aconseguir

#### Matriu de permisos

| Acció | `admin` | `comercial` |
| --- | --- | --- |
| Veure el **llistat** | Tots els leads | Només els leads en estat `nou` |
| **Filtrar** per estat | Sí | No (el filtre ni apareix) |
| Veure el **detall** d'un lead | Qualsevol | Només si és `nou`; si no, error **404** |
| **Editar** les dades d'un lead | Qualsevol | Només si és `nou`; si no, error **404** |
| Canviar l'**estat** (formulari ràpid i camp del formulari d'edició) | Sí | **No**: error **403** i el camp no apareix |
| **Eliminar** un lead | Sí | **No**: error **403** i el botó no apareix |

#### Requisits

1. **Base de dades.** La taula `usuaris` té una columna nova `rol`.
2. **Model i DAO.** `Usuari` té un rol i `UsuariDAO` el llig i el guarda.
3. **Sessió.** En iniciar sessió es guarda el rol, i `Sessio` ofereix una forma de consultar-lo (`esAdmin()`).
4. **Llistat.** Un comercial només veu leads `nou`, **encara que escriga `?estat=tancat` en la URL**. El total de resultats i la paginació han de ser coherents.
5. **Accés directe.** Un comercial que intente obrir (`detall`, `editar`, `actualitzar`) un lead que no és `nou` rep un **404**, com si no existira.
6. **Accions prohibides.** Si un comercial intenta eliminar o canviar l'estat, rep un **403** (pàgina d'error pròpia). A més, els botons i camps corresponents **no s'han de mostrar**.

::: tip Bona pràctica
**404 o 403?** Un **404** diu «això no existeix» i no revela res; es fa servir per als leads que el comercial no té dret a vore. Un **403** diu «ho coneix, però no tens permís» i és adequat per a accions com eliminar, que el comercial sap que existeixen.
:::

### Dades de prova

Executa este script en phpMyAdmin. Afig la columna, assigna el rol d'administrador a l'usuari existent i crea un comercial:

```sql
ALTER TABLE usuaris
    ADD COLUMN rol ENUM('admin', 'comercial') NOT NULL DEFAULT 'comercial'
    AFTER contrasenya;

UPDATE usuaris SET rol = 'admin' WHERE email = 'admin@techleads.test';

INSERT INTO usuaris (nom, email, contrasenya, rol) VALUES
('Comercial', 'comercial@techleads.test',
 '$2y$10$ehHvQACh/LOZm/oWdCOQxeJJLIB8vypuNPexq6ERaD7SG6UvrkdwO',
 'comercial');
```

| Usuari | Correu | Contrasenya | Rol |
| --- | --- | --- | --- |
| Administrador | `admin@techleads.test` | `admin123` | `admin` |
| Comercial | `comercial@techleads.test` | `comercial123` | `comercial` |

::: info Nota
El rol per defecte és `comercial`, no `admin`. És el **principi del mínim privilegi**: si alguna vegada es crea un usuari sense indicar el rol, ha de tindre els permisos més xicotets.
:::

### Com comprovar-ho

Fes cada prova **amb els dos usuaris**.

| Usuari | Prova | Resultat esperat |
| --- | --- | --- |
| `admin` | Entrar i vore el llistat | Tots els leads, amb el filtre d'estat |
| `comercial` | Entrar i vore el llistat | Només leads `nou`; no hi ha filtre d'estat |
| `comercial` | Obrir `/admin/?estat=tancat` | Continua mostrant només leads `nou` |
| `comercial` | Comprovar el text «N resultats» i la paginació | Compten només els leads `nou` |
| `comercial` | Obrir el detall d'un lead `nou` | Es veu, amb el botó **Editar**, però **sense** el formulari d'estat ni la zona d'eliminació |
| `comercial` | Obrir `/admin/?accio=detall&id=X` amb l'`id` d'un lead que **no** és `nou` | Pàgina «No trobat» (404) |
| `comercial` | Fer el mateix amb `accio=editar` | 404 |
| `comercial` | Editar un lead `nou` | El formulari **no** té el camp d'estat; en guardar, l'estat no canvia |
| `comercial` | Eliminar (vegeu la nota de sota) | **403** |
| `comercial` | Canviar l'estat (vegeu la nota de sota) | **403** |
| `admin` | Fer totes les accions | Tot continua funcionant com abans |

Com que els botons ja no apareixen, per a provar els **403** envia la petició a mà. Amb una sessió de comercial iniciada, obri les eines de desenvolupador (F12), pestanya *Console*, i executa:

```js
fetch('/admin/?accio=eliminar&id=2', { method: 'POST' }).then(r => console.log(r.status));
fetch('/admin/?accio=canviar-estat&id=2', { method: 'POST' }).then(r => console.log(r.status));
```

Han de mostrar `403` (substituïx l'`id` per un lead real). Per a comprovar el **404**, utilitza l'`id` d'un lead que no siga `nou`:

```js
fetch('/admin/?accio=detall&id=4').then(r => console.log(r.status));
```

### Pistes

Intenta primer la pràctica **sense** les pistes. Si et bloqueges, obri-les **per ordre**.

::: details Pista 1. Per on començar
Els canvis seguixen el camí de les dades, de baix a dalt:

1. **Base de dades**: l'script d'adalt.
2. **Model i DAO**: `Usuari` i `UsuariDAO`.
3. **Sessió**: `Sessio::iniciarSessio()` i un mètode per a consultar el rol.
4. **Controlador**: `LeadController`, i un mètode nou per al 403 en `BaseController`.
5. **Vistes**: el llistat, el detall, el formulari d'edició i la pàgina d'error 403.

Mira quins mètodes de `LeadController` reben un `id` de la URL. Tots ells compartixen un mateix pas. I recorda: la regla ha d'estar en el **servidor**.
:::

::: details Pista 2. Estructura de la solució
- **`Usuari`**: afig una propietat `rol` (amb valor per defecte `comercial`), constants per als dos rols i un mètode `esAdmin()`. Pensa també en `getRol()`.
- **`UsuariDAO`**: `insert()` i `update()` han de guardar la columna `rol`. El `SELECT *` ja la llig.
- **`Sessio`**: guarda el rol en la sessió en iniciar sessió, i afig mètodes `rol()` i `esAdmin()`.
- **Llistat**: si l'usuari no és administrador, **força** el filtre d'estat a `nou` (ignorant el que vinga en la URL). Passa a la vista un booleà per a decidir si es mostra el `select`.
- **Leads amb `id`**: tots passen per `carregarLead()`. Si és ací on decidixes que un comercial no pot vore un lead que no és `nou`, **una sola comprovació** protegix `detall`, `editar`, `actualitzar`, `canviarEstat` i `eliminar`.
- **403**: crea en `BaseController` un mètode `senseePermis()`, paregut a `noTrobat()`, que envie el codi 403 i carregue la vista `errors/403`. Crida'l al principi de `eliminar()` i `canviarEstat()` si l'usuari no és administrador.
- **`actualitzar()`**: només ha de llegir i validar el camp `estat` si l'usuari és administrador.
- **Vistes**: el formulari d'edició ja té una variable `$ambEstat`. En el detall, amaga el formulari d'estat i la zona d'eliminació als comercials.
:::

::: details Pista 3. Les peces clau
La part més delicada és la que no es veu en la pantalla. Esta és la idea per a `Sessio` i per a `carregarLead()`:

```php
// Sessio
public static function rol(): string
{
    return (string) ($_SESSION['usuari_rol'] ?? '');
}

public static function esAdmin(): bool
{
    return self::rol() === Usuari::ROL_ADMIN;
}
```

```php
// LeadController::carregarLead()
$lead = $id > 0 ? $this->leadDao->getById($id) : null;

if ($lead !== null && !Sessio::esAdmin() && $lead->getEstat() !== Lead::ESTAT_NOU) {
    return null;   // per a un comercial, no existix
}

return $lead;
```

La resta (llistat, 403, vistes) ja ho pots resoldre amb el que has après.
:::

### Compte amb...

- **Un avís de PHP després de crear la columna.** Si apareix un `Deprecated: Creation of dynamic property...` en iniciar sessió, vol dir que la taula té una columna (`rol`) que la classe `Usuari` encara no té com a propietat. Afig la propietat al model.
- **El rol viu en la sessió.** Si canvies el rol d'un usuari en la base de dades, no s'actualitza en una sessió ja iniciada: cal tancar-la i tornar a entrar.
- **El total i la paginació del llistat.** Si només filtres els resultats però no el recompte, un comercial veuria «5 resultats» amb només 2 leads. El filtre ha d'afectar **a les dos consultes** (`filtrar()` i `comptar()`).

### Preguntes de reflexió

Respon-les per escrit (2-3 línies cadascuna):

1. Per què cal comprovar el rol **en el servidor** si ja hem amagat el botó d'eliminar?
2. Quina diferència hi ha entre un error 403 i un 404, i per què hem triat cada un?
3. Per què el rol per defecte és `comercial` i no `admin`?
4. Si demà afegim un tercer rol, `supervisor`, quins fitxers hauràs de tocar? Què canviaria si les regles de permisos estigueren concentrades en un únic lloc?

### Reptes opcionals

1. **Fluxe del comercial.** Permet que un comercial marque un lead com a `contactat` (només eixa transició). Compte: en canviar-lo, el lead **deixa de ser `nou`** i, per tant, desapareix de la seua vista. On ha de redirigir `canviarEstat()` en eixe cas?
2. **Mostra el rol** al costat del nom, en la capçalera.
3. **Gestió d'usuaris.** Una pàgina només per a administradors que permeta crear un comercial nou (recorda `xifrarContrasenya()`).

### Lliurament

Lliura la carpeta `www/` del projecte, l'script SQL amb els canvis a la base de dades i les respostes de reflexió.