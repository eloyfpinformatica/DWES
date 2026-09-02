# Unitat 2. PHP Bàsic

## Resultats d'aprenentatge i criteris d'avaluació
::: details 📋 Resultats d'aprenentatge i criteris d'avaluació
**RA2. Escriu programes en entorn servidor utilitzant la sintaxi del llenguatge, estructures de control, funcions i tipus de dades propis.**

* **Criteris d'avaluació:**
* **a)** S'han utilitzat les etiquetes d'integració de codi PHP en documents HTML.
* **b)** S'han definit i utilitzat variables, constants i tipus de dades primitius.
* **c)** S'han emprat els operadors del llenguatge per a la manipulació de dades.
* **d)** S'han utilitzat estructures de control de flux per a la presa de decisions i la iteració.
* **e)** S'han definit i utilitzat estructures de dades compostes (arrays indexats, associatius i multidimensionals).
* **f)** S'han creat i invocat funcions d'usuari, aplicant la declaració de tipus i el mode estricte.
* **g)** S'han utilitzat els mecanismes d'inclusió de fitxers per a modularitzar l'aplicació.
* **h)** S'han aplicat tècniques de depuració per a la resolució d'errors en el codi.
* **i)** S'han processat les dades enviades des de formularis web mitjançant els mètodes GET i POST.



**RA3. Escriu blocs de sentències embeguts en llenguatges de marques, seleccionant i utilitzant les estructures de programació.**

* **Criteris d'avaluació:**
* **a)** S'han utilitzat mecanismes de decisió en la creació de blocs de sentències.
* **b)** S'han utilitzat bucles i s'ha verificat el seu funcionament.
* **c)** S'han utilitzat “arrays” per a emmagatzemar i recuperar conjunts de dades.
* **d)** S'han creat i utilitzat funcions.
* **e)** S'han utilitzat formularis web per a interactuar amb l'usuari del navegador web.
* **f)** S'han emprat mètodes per a recuperar la informació introduïda en el formulari.
* **g)** S'han afegit comentaris al codi.
:::




## Temporalització

* 14 hores



## Índex

1. [Bloc1: Introducció i Entorn](#bloc1-introduccio-i-entorn-de-desenvolupament)

    1.1. [Què és PHP? Conceptes fonamentals](#_1-1-que-es-php-conceptes-fonamentals)

    1.2. [Configuració de l'entorn de desenvolupament amb Docker](#_1-2-configuracio-de-lentorn-de-desenvolupament-amb-docker)

    1.3. [Integració de codi PHP en HTML](#_1-3-integracio-de-codi-php-en-html)

    1.4. [Frontend modern: Introducció a Tailwind CSS (CDN)](#_1-4-frontend-modern-introduccio-a-tailwind-css-cdn)
    

2. [Bloc2: Fonaments del Llenguatge PHP](#bloc2-fonaments-del-llenguatge-php)

    2.1. [Variables, tipat dinàmic i case sensitivity](#_2-1-variables-tipat-dinamic-i-case-sensitivity)

    2.2. [Tipus de dades primitius](#_2-2-tipus-de-dades-primitius)
    
    2.3. [Àmbits de les variables (Local vs Global)](#_2-3-ambits-de-les-variables-local-vs-global)
    
    2.4. [Operadors i definició de constants](#_2-4-operadors-i-definicio-de-constants)
    
    2.5. [Comentaris i bones pràctiques](#_2-5-comentaris-i-bones-practiques)

3. [Bloc3: Estructures de Control i Depuració](#bloc3-estructures-de-control-i-depuracio)
    
    3.1. [Mecanismes de decisió (if, else, switch)](#31-mecanismes-de-decisio-if-else-switch)

    3.2. [Bucles i iteració (for, while, foreach)](#32-bucles-i-iteracio-for-while-foreach)

    3.3. [Tècniques de depuració (Debug)](#33-tecniques-de-depuracio-debug)


4. [Bloc4: Gestió de Dades amb Arrays](#bloc4-gestio-de-dades-amb-arrays)
    
    4.1. [Arrays indexats](#41-arrays-indexats)
    
    4.2. [Arrays associatius](#42-arrays-associatius)

    4.3. [Arrays multidimensionals](#43-arrays-multidimensionals)

    4.4. [Funcions predefinides per a arrays](#44-funcions-predefinides-per-a-arrays)


5. [Bloc5: Modularització i Disseny de l'Aplicació](#bloc5-modularitzacio-i-disseny-de-laplicacio)

    5.1. [Funcions d'usuari i pas de paràmetres](#51-funcions-dusuari-i-pas-de-parametres)

    5.2. [Declaracions de tipus i mode estricte](#52-declaracions-de-tipus-i-mode-estricte)

    5.3. [Inclusió de fitxers (require, include)](#53-inclusio-de-fitxers-require-include)

    5.4. [Separació bàsica de Vistes i Lògica](#54-separacio-basica-de-vistes-i-logica)


6. [Bloc6: Formularis](#bloc6-formularis)
    
    6.1. [Trameça de dades: Mètodes GET vs POST](#61-trameca-de-dades-metodes-get-vs-post)

    6.2. [Recuperació d'informació amb superglobals ($_GET,$_POST)](#62-recuperacio-dinformacio-amb-superglobals-get-post)

    6.3. [Validació bàsica al costat del servidor](#63-validacio-basica-al-costat-del-servidor)


7. [Bibliografia i Recursos](#7-bibliografia-i-recursos)


## Bloc 1: Introducció i Entorn de Desenvolupament

En aquest primer Bloc assentarem les bases del llenguatge **PHP**, veurem com configurar un entorn de desenvolupament local de manera ràpida i aprendrem a integrar codi servidor dins de documents HTML, utilitzant **Tailwind CSS** per a la capa visual.



### 1.1. Què és PHP? Conceptes fonamentals

**PHP** (*PHP: Hypertext Preprocessor*) és un llenguatge de programació de codi obert creat originalment per Rasmus Lerdorf en 1994. Encara que va nàixer com un conjunt de ferramentetes senzilles per a mantindre la seua pàgina personal, hui en dia és un llenguatge de propòsit general utilitzat principalment per al desenvolupament web en el **costat del servidor (*Back-end*)**.

#### Característiques clau de PHP

* **Interpretat i executat en el servidor:** El codi PHP s'executa en el servidor web abans d'enviar el resultat al navegador. El client mai veu el codi font PHP, només el resultat final (habitualment HTML, CSS o JSON).
* **Multiplataforma:** S'executa en sistemes operatius Linux, Windows, macOS, etc.
* **Tipat dinàmic:** No cal declarar explícitament el tipus de dada d'una variable; PHP el determina automàticament segons el valor assignat.
* **Gran ecosistema i compatibilitat:** És la base de la majoria dels CMS del mercat (WordPress, Drupal, Joomla) i compta amb *frameworks* de gran rendiment com Laravel o Symfony.

```text
[ Navegador (Client) ] 
       |
       |  1. Petició HTTP (ex: index.php)
       v
[ Servidor Web (Apache/NGINX) ] 
       |
       |  2. Passa el fitxer a l'intèrpret de PHP
       v
[ Engine PHP ]  > Executa la lògica / consulta BD
       |
       |  3. Genera el codi HTML de resposta
       v
[ Navegador (Client) ]  < Rep només HTML + CSS

```

### 1.2. Configuració de l'entorn de desenvolupament amb Docker

Per a executar codi PHP en el nostre equip local, necessitem un servidor web i l'intèrpret de PHP. En lloc d'instal·lar aquests programes directament en el sistema operatiu (amb solucions com XAMPP o MAMP), utilitzarem **Docker**.

#### Què és Docker i per què l'utilitzem?

**Docker** és una plataforma de contenidors que ens permet aïllar l'aplicació i el seu entorn d'execució. Ens garanteix que tots els desenvolupadors de l'equip treballen exactament amb la mateixa configuració de PHP i Apache, independentment del sistema operatiu que utilitzen.



#### Instal·lació de Docker

1. **Windows i macOS:** Instal·la **Docker Desktop** des de la seua web oficial ([docker.com](https://www.docker.com/)).
* *A Windows:* Assegura't de tindre activada la característica **WSL2** (Windows Subsystem for Linux).


2. **Linux (Ubuntu/Debian):** Instal·la el motor de Docker directament des de la terminal:
```bash
sudo apt update
sudo apt install docker.io docker-compose-v2 -y
sudo usermod -aG docker $USER ## Per a executar docker sense sudo (requereix reiniciar sessió)

```





#### Configuració del projecte (`docker-compose.yml` i `Dockerfile`)

Per a alçar el nostre entorn de desenvolupament, utilitzarem dos fitxers de configuració a la arrel del nostre projecte:

##### 1. Fitxer `Dockerfile`

Defineix com es construeix la imatge del nostre servidor PHP.

```dockerfile
FROM php:8.5-apache

# 1. Instal·lació d'extensions base de PHP i mod_rewrite
RUN docker-php-ext-install pdo pdo_mysql \
    && a2enmod rewrite

# 2. Instal·lació i activació d'Xdebug
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

# 3. Configuració d'Xdebug
RUN echo "xdebug.mode=debug" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.start_with_request=trigger" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.client_host=host.docker.internal" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.client_port=9003" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.log=/tmp/xdebug.log" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

```

##### 2. Fitxer `docker-compose.yml`

Defineix els serveis, ports i volums del nostre entorn.

```yaml
services:
  web:
    build: .
    container_name: techleads_web
    ports:
      - "8080:80"
    volumes:
      - ./src:/var/www/html
    extra_hosts:
      - "host.docker.internal:host-gateway"

```

##### 3. Estructura de carpetes inicial

```text
meu-projecte-lead/
├── docker-compose.yml
├── Dockerfile
└── src/
    └── index.php

```



#### Com gestionar l'entorn amb Docker (Arrancar i Parar)

Tots els comandaments s'executen des de la terminal, **situant-nos a la carpeta arrel del projecte** (on estan els fitxers `.yml` i `Dockerfile`):

##### 1. Arrancar l'entorn de desenvolupament

```bash
docker compose up -d

```

* L'argument `-d` (*detached*) fa que el servidor s'execute en segon pla, deixant-te la terminal lliure.
* La primera vegada se descarregarà la imatge de PHP 8.5 i es construirà el contenidor.
* Ara ja pots obrir el navegador i accedir a: **`http://localhost:8080`**.

##### 2. Veure els contenidors en execució

```bash
docker compose ps

```

##### 3. Veure els logs en temps real (útil per a veure errors de PHP)

```bash
docker compose logs -f

```

##### 4. Parar l'entorn de desenvolupament

```bash
docker compose stop

```

##### 5. Aturar i eliminar els contenidors

```bash
docker compose down

```



#### Editors de codi i extensions recomanades

* **Visual Studio Code (VS Code):** L'editor recomanat per al curs. Se suggereix instal·lar:
* **PHP Intelephense:** Per a autocompletat, navegació per codi i detecció d'errors de PHP.
* **Tailwind CSS IntelliSense:** Per a autocompletar les classes d'utilitat de Tailwind.
* **Docker (Microsoft):** Per a gestionar contenidors directament des del panell lateral de VS Code.
* **PhpStorm:** Un IDE professional de JetBrains molt potent, especialment dissenyat per a l'ecosistema PHP (de pagament, però gratuït amb llicència d'estudiant).



### 1.3. Integració de codi PHP en HTML

Una de les grans virtuts de PHP és que es pot **embegut directament dins del codi HTML**. L'intèrpret de PHP només processarà el codi que es trobe contingut dins de les seues etiquetes especials d'obertura i tancament.

#### 1. Etiqueta estàndard d'obertura i tancament

És la forma habitual i recomanada per a escriure blocs de lògica en PHP.

```php
<?php
    // Codi PHP ací
    echo "Hola, món!";
?>

```

::: info **Bones pràctiques** 
Si un fitxer conté **únicament codi PHP** (sense barrejar HTML), és recomanable **ometre l'etiqueta de tancament (`?>`)** al final del fitxer per a evitar problemes amb espais en blanc o salts de línia involuntaris en la resposta HTTP.
:::

#### 2. Etiqueta curta d'impressió (`<?= ?>`)

És una drecera molt útil quan només volem **imprimir o mostrar un valor directament en el document HTML**. És l'equivalent a fer `<?php echo ...; ?>`.

```php
<!-- Etiqueta estàndard -->
<h1><?php echo "Benvingut al curs de PHP"; ?></h1>

<!-- Etiqueta curta d'impressió (més neta) -->
<h1><?= "Benvingut al curs de PHP" ?></h1>

```

#### Exemple combinat: HTML + PHP

```html
<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <title>Exemple Integració</title>
</head>
<body>
    <!-- Execució de lògica bàsica -->
    <?php 
        $titol = "Desenvolupament Web en Entorn Servidor";
        $any = 2026;
    ?>

    <!-- Renderitzat de dades en l'HTML -->
    <h1><?= $titol ?></h1>
    <p>Curs acadèmic: <?= $any ?></p>
</body>
</html>

```



### 1.4. Frontend modern: Introducció a Tailwind CSS (CDN)

Per a donar un aspecte professional i modern a les nostres vistes sense perdre temps escrivint fulls d'estils CSS des de zero, utilitzarem **Tailwind CSS**.

Tailwind és un *framework* CSS basat en **classes d'utilitat** (*utility-first*), la qual cosa significa que apliquem els estils directament sobre els elements HTML mitjançant classes predefinides (`flex`, `pt-4`, `text-center`, `bg-blue-500`, etc.).

#### Com incloure Tailwind via CDN per a prototipatge

Per a projectes ràpids, desenvolupament local o fases de prototipatge, podem incloure Tailwind directament al `<head>` del nostre HTML utilitzant el seu script oficial de CDN:

```html
<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prototip amb Tailwind</title>
    <!-- Script de Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">

    <div class="bg-white p-8 rounded-lg shadow-md max-w-md w-full">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">Targeta de Prova</h2>
        <p class="text-gray-600 mb-6">Així de fàcil és dissenyar la interfície utilitzant Tailwind CSS.</p>
        <button class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded transition">
            Acció
        </button>
    </div>

</body>
</html>

```


## Bloc 2: Fonaments del llenguatge PHP

En aquest bloc aprendrem la sintaxi bàsica de PHP, la gestió de memòria mitjançant variables i constants, els tipus de dades que suporta el llenguatge, el comportament de l'àmbit (*scope*) i els operadors essencials.



### 2.1. Variables, tipat dinàmic i case sensitivity

#### Sintaxi de les variables

En PHP, totes les variables comencen obligatòriament amb el símbol del dòlar (`$`).

* El nom ha de començar per una lletra o un guió baix (`_`), mai per un número.
* No poden contindre espais ni caràcters especials (només lletres, números i `_`).

#### Tipat dinàmic (Weakly Typed / Dynamically Typed)

A diferència de llenguatges com Java o C#, en PHP **no cal declarar el tipus de dada** que contindrà una variable. El tipus s'assigna automàticament en temps d'execució segons el valor que li assignem, i pot canviar al llarg de l'execució del script.

```php
<?php
$element = "Hola DWES"; // $element és de tipus string
$element = 42;          // Ara $element passa a ser un integer
$element = true;        // Ara $element és un boolean
?>

```

#### Sensibilitat a majúscules i minúscules (*Case Sensitivity*)

En PHP hi ha una distinció important que sol confondre els principiants:

1. **Les variables SÓN sensibles a majúscules/minúscules:** `$nom`, `$Nom` i `$NOM` són tres variables completament diferents.
2. **Les funcions, paraules clau del llenguatge i estructures de control NO són sensibles:** `echo`, `ECHO`, `IF`, `if`, `FUNCTION` o `function` funcionen exactament igual.

```php
<?php
$nom = "Laura";
$Nom = "Carles";

echo $nom; // Mostra: Laura
ECHO $Nom; // Mostra: Carles (ECHO funciona igual que echo)
?>

```



### 2.2. Tipus de dades primitius

PHP admet diferents tipus de dades escalar o primitius. Podem comprovar el tipus de dada d'una variable utilitzant les funcions `var_dump()` (per a depuració detallada) o `gettype()`.

| Tipus | Descripció | Exemple |
| --- | --- | --- |
| **Integer** | Números enters (positius o negatius) | `$edat = 25;` |
| **Float / Double** | Números de punt flotant (decimals) | `$preu = 19.99;` |
| **String** | Cadenes de text | `$nom = "TechLeads";` |
| **Boolean** | Valors lògics de veritat | `$esAdmin = true;` |
| **NULL** | Variable sense valor assignat o buidada | `$resultat = null;` |

#### Comportament avançat dels Strings: Cometes dobles vs simples

Aquest és un dels punts clau de PHP que cal tindre clar:

* **Cometes dobles (`"`):** Avaluen i **interpolen** el valor de les variables directament dins de la cadena, a més d'interpretar caràcters d'escapament com `\n` o `\t`.
* **Cometes simples (`'`):** Tracten el text de manera **literal**. Si posem una variable dins, mostrarà el nom de la variable tal qual.

```php
<?php
$empresa = "TechLeads";

// Amb cometes dobles (Interpolació)
echo "Benvingut a $empresa"; 
// Eixida: Benvingut a TechLeads

// Amb cometes simples (Text literal)
echo 'Benvingut a $empresa'; 
// Eixida: Benvingut a $empresa

// Concatenació manual (S'utilitza el punt `.`)
echo 'Benvingut a ' . $empresa; 
// Eixida: Benvingut a TechLeads
?>

```

##### Interpolació avançada amb claus `{}` (Sintaxi complexa)

Quan necessitem concatenar variables amb altre text sense afegir espais, o quan accedim a propietats/arrays dins d'una cadena de text en cometes dobles, l'ús simple de `"$variable"` pot generar ambigüitats que PHP no sap interpretar bé.

Per a solucionar-ho, embolcallem la variable entre claus: `{$variable}`.

```php
<?php
$id = 42;
$seccio = "productes";

// 1. Construcció d'URLs dinàmiques (Paràmetres GET)
// Evita confusions quan la variable està immediatament a pegada a un altre text
$url = "https://lameuaweb.com/{$seccio}/detall.php?id={$id}";
echo $url; 
// Eixida: https://lameuaweb.com/productes/detall.php?id=42


// 2. Resolució d'ambigüitats amb el text del voltant
$quantitat = 5;
$unitat = "metro";

// SENSE CLAUS (ERROR/INCORRECTE):
// PHP intentarà buscar una variable anomenada $unitats (que no existeix)
// echo "Tinc $quantitat $unitats"; 

// AMB CLAUS (CORRECTE):
// PHP entén clarament on acaba el nom de la variable $unitat i on comença el text "s"
echo "Tinc {$quantitat} {$unitat}s d'amplària.";
// Eixida: Tinc 5 metros d'amplària.


// 3. Accés a claus d'Arrays Associatius dins d'un String
$lead = [
    'id' => 10,
    'nom' => 'Carles'
];

// Dins de cometes dobles, utilitzar claus evita haver de concatenar amb punts (.)
echo "El lead amb ID {$lead['id']} correspon a {$lead['nom']}.";
// Eixida: El lead amb ID 10 correspon a Carles.
?>

```


### 2.3. Àmbits de les variables (Local vs Global)

L'àmbit (*scope*) determina en quines parts del codi es pot accedir a una variable. En PHP, l'àmbit funciona de manera lleugerament diferent de JavaScript o C#.

#### 1. Àmbit Global

Una variable declarada fora de qualsevol funció té àmbit global. Es pot accedir a ella des de qualsevol part del script **excepte des de l'interior d'una funció**.

#### 2. Àmbit Local

Una variable declarada dins d'una funció és local a eixa funció i s'elimina quan la funció acaba la seua execució.

```php
<?php
$missatgeGlobal = "Hola des de fora"; // Àmbit Global

function saludar() {
    $missatgeLocal = "Hola des de dins"; // Àmbit Local
    
    // ERROR: Açò generarà un Warning/Error perquè $missatgeGlobal no està accessible ací
    // echo $missatgeGlobal; 
    
    echo $missatgeLocal; // Correcte
}

saludar();

// ERROR: Açò fallarà perquè $missatgeLocal no existeix fora de la funció
// echo $missatgeLocal; 
?>

```

#### Com accedir a variables globals des d'un àmbit local?

Si necessitem accedir a una variable global dins d'una funció, tenim dues opcions:

1. Utilitzar la paraula clau `global`:
2. Utilitzar l'array superglobal `$GLOBALS` de PHP:

```php
<?php
$canal = "DWES";

function mostrarCanal() {
    // Opció 1: Paraula clau global
    global $canal;
    echo "Canal amb global: " . $canal . "<br>";

    // Opció 2: Ús de $GLOBALS
    echo "Canal amb \$GLOBALS: " . $GLOBALS['canal'];
}

mostrarCanal();
?>

```

::: warning **Bones pràctiques:** 
L'ús abusiu de `global` o `$GLOBALS` es considera un "olor de codi" (*code smell*). El més correcte és passar la informació com a paràmetres a la funció i utilitzar valors de retorn (`return`).
:::



### 2.4. Operadors i definició de constants

#### Operadors principals

PHP disposa dels operadors habituals dels llenguatges de programació:

```php
<?php
// 1. Operadors Aritmètics
$a = 10;
$b = 3;

$suma = $a + $b;        // 13
$modul = $a % $b;       // 1 (Residu de la divisió)
$potencia = $a ** $b;   // 1000 (10 elevat a 3)

// 2. Operador de Concatenació (Punt .)
$text1 = "Hola";
$text2 = "Món";
$complet = $text1 . " " . $text2; // "Hola Món"

// 3. Operadors d'Assignació amb operació
$x = 5;
$x += 10; // Equivalent a $x = $x + 10 (Valor final: 15)
$text1 .= " Món"; // Equivalent a $text1 = $text1 . " Món"

// 4. Operadors de Comparació
$num = "5";

var_dump($num == 5);  // true  (Igualtat feble: compara valor, no tipus)
var_dump($num === 5); // false (Igualtat estricta: compara valor I TIPUS)
var_dump($num !== 5); // true  (Diferent estricte)

// 5. Operador "Nul Coalescing" (??) - Molt utilitzat en PHP modern
// Si $_GET['usuari'] existeix i no és null, s'usa; si no, s'assigna 'Anonim'
$usuari = $_GET['usuari'] ?? 'Anonim';
?>

```

#### Definició de Constants

Les constants són valors que **no poden canviar** durant l'execució del script i no porten el símbol del dòlar (`$`). Són globals per defecte.

En PHP tenim dues formes principals de definir constants:

1. **Amb la funció `define()`:** Forma tradicional.
2. **Amb la paraula clau `const`:** Sintaxi més moderna, utilitzada habitualment a nivell de fitxer o dins de classes (POO).

```php
<?php
// 1. Mitjançant define()
define("APP_NAME", "TechLeads");
define("MAX_LOGIN_ATTEMPTS", 5);

// 2. Mitjançant const
const VERSION = "1.0.0";
const DB_HOST = "localhost";

echo "Benvingut a " . APP_NAME . " v" . VERSION;

// ERROR: Açò generaria un error fatal
// APP_NAME = "AltraWeb"; 
?>

```

| Criteri | `define()` | `const` |
| --- | --- | --- |
| **Àmbit** | Es pot definir en qualsevol lloc (dins d'un `if`, funció, etc.) | Només en el nivell superior del script o dins de classes |
| **Execució** | Es defineix en temps d'execució | Es defineix en temps de compilació |
| **Sintaxi** | Funció `define('NOM', valor);` | Paraula clau `const NOM = valor;` |



### 2.5. Comentaris i bones pràctiques

#### Tipus de comentaris en PHP

```php
<?php
// Això és un comentari d'una sola línia (Estil C++)

## Això també és un comentari d'una sola línia (Estil Shell/Python)

/*
   Això és un comentari
   de múltiples línies.
   Útil per a explicacions llargues.
*/

/**
 * Comentari de documentació (PHPDoc)
 * Utilitzat per a descriure funcions, paràmetres i tipus de retorn
 * @param string $nom El nom de l'usuari
 * @return string Missatge de benvinguda
 */
function generarSalutacio(string $nom): string {
    return "Hola, " . $nom;
}
?>

```

#### Bones pràctiques de codificació (PSR-12)

A la comunitat PHP existeixen els estàndards **PSR (PHP Standard Recommendations)**. Per a escriure codi net i professional, heu d'intentar seguir aquestes regles bàsiques:

1. **Noms de variables:** Utilitzar `camelCase` (`$nomUsuari`, `$correuElectronic`).
2. **Noms de constants:** Utilitzar sempre majúscules separades per guió baix (`UPPER_SNAKE_CASE`) com `DB_PORT` o `MAX_LEADS`.
3. **Etiquetes de tancament:** Si un fitxer conté **sols codi PHP**, la bona pràctica és **NO posar l'etiqueta de tancament `?>**` al final per a evitar problemes amb espais en blanc involuntaris o enviament accidental de capçaleres HTTP.
4. **Indentació:** Utilitzar 4 espais per nivell d'indentació.
5. **Comparacions estrictes:** Preferir sempre l'operador d'igualtat estricta (`===`) davant de la feble (`==`) per a evitar comportaments inesperats en la conversió automàtica de tipus.





## Bloc 3: Estructures de Control i Depuració



### 3.1. Mecanismes de decisió (`if`, `else`, `switch`, `match`)

Les estructures de control de flux permeten prendre decisions en el codi segons si es compleixen o no determinades condicions.

#### 1. Estructura Condicional: `if`, `elseif` i `else`

S'utilitzen per a executar blocs de codi en funció d'un o diversos valors booleans.

```php
<?php
$pressupost = 2500;

if ($pressupost >= 5000) {
    echo "Client Prioritari (Enterprise)";
} elseif ($pressupost >= 1500) {
    echo "Client Estàndard";
} else {
    echo "Projecte Menor / A revisar";
}
?>

```

##### Operadors de comparació i lògics comuns:

* **Igualtat/Identitat:** `==` (compara valor) vs `===` (compara valor i tipus de dada).
* **Desigualtat:** `!=` o `!==`.
* **Lògics:** `&&` (AND), `||` (OR), `!` (NOT).

::: warning **Consell** 
Fes ús de l'operador d'identitat estricta `===` per a evitar comportaments inesperats a causa de la conversió automàtica de tipus de PHP.
:::

##### Operador Ternari i Operador Null Coalescing (`??`)

Per a assignacions condicionals curtes:

```php
<?php
$estat = 'nou';

// Operador Ternari: (condició) ? valor_si_cert : valor_si_fals
$etiqueta = ($estat === 'nou') ? 'Pendent de revisar' : 'Atés';

// Operador Null Coalescing (??): Retorna el primer valor si existeix i no és null
$nomClient = $_GET['nom'] ?? 'Anònim';
?>

```



#### 2. Selecció Múltiple: `switch`

Quan tenim una mateixa variable que pot prendre múltiples valors discrets, l'estructura `switch` millora la llegibilitat respecte a molts `elseif` encadenats.

```php
<?php
$estatLead = 'contactat';

switch ($estatLead) {
    case 'nou':
        $missatge = "Tens un nou lead pendent d'assignar.";
        break; // Evita que l'execució continue cap als següents casos

    case 'contactat':
        $missatge = "S'ha enviat un correu al client.";
        break;

    case 'convertit':
    case 'guanyat': // Múltiples casos que executen el mateix codi
        $missatge = "El projecte s'ha acceptat!";
        break;

    default:
        $missatge = "Estat no reconegut.";
        break;
}

echo $missatge;
?>

```



#### 3. L'Expressió `match` (PHP 8+)

Des de PHP 8, l'expressió `match` és la substituta moderna i millorada de `switch`:

* Retorna un valor directament.
* Fa comparacions estrictes (`===`) automàticament.
* No necessita la clàusula `break`.

```php
<?php
$estatLead = 'contactat';

$missatge = match ($estatLead) {
    'nou'                  => "Tens un nou lead pendent d'assignar.",
    'contactat'            => "S'ha enviat un correu al client.",
    'convertit', 'guanyat' => "El projecte s'ha acceptat!",
    default                => "Estat no reconegut."
};

echo $missatge;
?>

```



### 3.2. Bucles i iteració (`for`, `while`, `foreach`)

Els bucles permeten executar un mateix Blocde codi repetidament mentre es complisca una condició.

#### 1. El Bucle `for`

S'utilitza quan **sabem exactament quantes vegades** volem repetir un Blocde codi. Recompte numèric tradicional.

```php
<?php
// Imprimir una llista de 5 paginadors
for ($i = 1; $i <= 5; $i++) {
    echo "Pàgina {$i} | ";
}
?>

```



#### 2. Els Bucles `while` i `do-while`

S'utilitzen quan **no sabem quantes vegades** s'ha de repetir l'execució, sinó que depèn d'una condició dinàmica.

* **`while`:** Avalua la condició **abans** d'executar el Bloc(pot no executar-se mai).
* **`do-while`:** Executa el Bloc**almenys una vegada** abans d'avaluar la condició.

```php
<?php
// Bucle while
$intents = 0;
while ($intents < 3) {
    echo "Intent número: {$intents}<br>";
    $intents++;
}

// Bucle do-while
$executat = false;
do {
    echo "Això s'imprimeix almenys 1 vegada.";
} while ($executat === true);
?>

```



#### 3. El Bucle `foreach`

És l'estructura d'iteració per excel·lència en PHP. Està dissenyat específicament per a **recórrer arrays o col·leccions de dades** sense necessitat de gestionar comptadors manuals.

```php
<?php
// Recórrer un array simple
$serveis = ["Web Corporativa", "Botiga Online", "App Mòbil"];

foreach ($serveis as $servei) {
    echo "Servei disponible: {$servei}<br>";
}

// Recórrer clau i valor d'un array associatiu
$configuracio = [
    'nom_lloc' => 'TechLeads',
    'versio'   => '1.0',
    'idioma'   => 'ca'
];

foreach ($configuracio as $clau => $valor) {
    echo "Paràmetre <strong>{$clau}</strong>: {$valor}<br>";
}
?>

```

##### Control de flux dins dels bucles:

* **`break`:** Interromp i eix immediatament del bucle.
* **`continue`:** Salta la resta de la iteració actual i passa a la següent.



### 3.3. Tècniques de depuració (Debug)

La depuració (*debugging*) és el procés d'identificar, trobar i corregir errors en el codi.

#### 1. Inspecció de Variables: `var_dump()`, `print_r()` i `var_export()`

En PHP no és suficient amb fer `echo` per a totes les variables, especialment quan treballem amb arrays, booleans o valors `null`.

* **`var_dump($var)`:** Mostra informació detallada sobre la variable: el seu **tipus de dada**, la seua **longitud/mida** i el seu **valor**.
* **`print_r($var)`:** Mostra la informació d'una manera més legible per a humans, especialment útil amb arrays.
* **`var_export($var)`:** Retorna la informació estructurada en sintaxi de codi PHP executable.

```php
<?php
$lead = [
    'id' => 1,
    'nom' => 'Maria Garcia',
    'actiu' => true,
    'pressupost' => 1200.50
];

// Recomanació: embolcallar en etiquetes HTML <pre> per a millorar la visualització al navegador
echo "<pre>";
var_dump($lead);
echo "</pre>";

echo "<pre>";
print_r($lead);
echo "</pre>";
?>

```



#### 2. Configuració de Directives d'Error en Desenvolupament

Per defecte, en alguns servidors web o configuracions de producció, PHP amaga els errors (*pantalla blanca de la mort*). En entorn de desenvolupament és imprescindible forçar a PHP a mostrar qualsevol advertència o error.

Podem configurar-ho a l'inici del nostre script amb la funció `ini_set()`:

```php
<?php
// Activar la visualització d'errors en pantalla (NOMÉS PER A DESENVOLUPAMENT)
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');

// Reportar TOTS els nivells d'error de PHP (Warnings, Notices, Fatal Errors, Deprecated...)
error_reporting(E_ALL);
?>

```

##### Nivells d'error principals en PHP:

1. **Notice:** Advertència lleu (ex: intentar accedir a una variable o clau d'array no definida). El script **continua**.
2. **Warning:** Advertència d'importància mitjana (ex: incloure un fitxer no trobat amb `include`). El script **continua**.
3. **Fatal Error:** Error greu (ex: cridar a una funció que no existeix o error de sintaxi). L'execució del script **s'atura immediatament**.



#### 3. Traçabilitat de l'execució i Aturada: `exit()` / `die()`

De vegades necessitem detindre el script en un punt exacte per a comprovar l'estat de les variables abans que s'execute la resta del codi.

```php
<?php
$dadesRebudes = $_POST;

// Tècnica clàssica de depuració ràpida (Dump and Die)
echo "<pre>";
var_dump($dadesRebudes);
echo "</pre>";
exit; // Atura l'execució del servidor ací mateix

// El codi que hi ha a continuació mai s'executarà
$connexio = "Connectant a la BD..."; 
?>

```




## Bloc 4: Gestió de Dades amb Arrays


### 4.1. Arrays indexats

Un **array indexat** (o numèric) és una col·lecció ordenada d'elements on cada posició s'identifica mitjançant un **índex numèric enter** (començant sempre per la posició `0`).

#### 1. Definició i sintaxi

En PHP podem definir arrays amb la sintaxi curta de claudàtors `[]` (recomanada des de PHP 5.4) o amb la funció tradicional `array()`.

```php
<?php
// Sintaxi moderna amb corchets (recomanada)
$estatsLead = ['nou', 'contactat', 'convertit', 'descartat'];

// Sintaxi tradicional
$categories = array('Web', 'eCommerce', 'App Mòbil');

// Accés als elements mitjançant l'índex numèric (comença en 0)
echo $estatsLead[0]; // Imprimeix: nou
echo $estatsLead[2]; // Imprimeix: convertit
?>

```

---

#### 2. Afegir, Modificar i Reassignar Elements

```php
<?php
$serveis = ['Disseny', 'Desenvolupament'];

// 1. Modificar un element existent
$serveis[0] = 'Disseny UI/UX';

// 2. Afegir un element al final de l'array (sense especificar índex)
$serveis[] = 'SEO i Màrqueting'; // S'afegeix automàticament a la posició 2

// 3. Afegir en una posició concreta (compte: pot deixar "forats" en els índexs)
$serveis[5] = 'Consultoria';

echo "<pre>";
print_r($serveis);
echo "</pre>";
?>

```


#### 3. Iteració d'arrays indexats

Podem recórrer un array indexat tant amb un bucle `for` comptant la seua longitud amb `count()`, com amb el bucle especialitzat `foreach`.

```php
<?php
$tecnologies = ['PHP', 'MySQL', 'TailwindCSS', 'JavaScript'];

// Opció A: Bucle for tradicional
echo "<strong>Amb bucle for:</strong><br>";
for ($i = 0; $i < count($tecnologies); $i++) {
    echo "Llenguatge {$i}: {$tecnologies[$i]}<br>";
}

// Opció B: Bucle foreach (més net i idiomàtic en PHP)
echo "<br><strong>Amb bucle foreach:</strong><br>";
foreach ($tecnologies as $index => $tecnologia) {
    echo "Llenguatge {$index}: {$tecnologia}<br>";
}
?>

```


### 4.2. Arrays Associatius

Un **array associatiu** permet utilitzar **claus personalitzades (cadenes de text/strings)** en lloc d'índexs numèrics. Aquest tipus d'array és ideal per a representar registres d'informació estructurada o "objectes simulats" (com un usuari, un lead o un producte).

#### 1. Definició i Accés

S'utilitza l'operador d'assignació d'array `=>` (*double arrow*) per a associar una clau amb el seu valor.

```php
<?php
// Representació d'un sol Lead com a array associatiu
$lead = [
    'id'         => 101,
    'nom'        => 'Aina Martínez',
    'email'      => 'aina@empresa.valencia',
    'pressupost' => 3500.00,
    'actiu'      => true
];

// Accés mitjançant la clau textual (String)
echo "Nom del client: " . $lead['nom'] . "<br>";
echo "Correu electrònic: " . $lead['email'] . "<br>";
?>

```

---

#### 2. Manipulació d'Arrays Associatius

```php
<?php
$configuracioApp = [
    'nom_lloc' => 'TechLeads',
    'idioma'   => 'ca'
];

// Afegir una nova clau-valor
$configuracioApp['versio'] = '1.2.0';

// Modificar un valor existent
$configuracioApp['idioma'] = 'es';

// Comprovar si una clau existeix abans d'accedir-hi (Molt important per a evitar Warnings)
if (isset($configuracioApp['nom_lloc'])) {
    echo "El lloc es diu: " . $configuracioApp['nom_lloc'];
}

// O bé comprovar si existeix la clau encara que el valor siga null
if (array_key_exists('versio', $configuracioApp)) {
    echo "Versió: " . $configuracioApp['versio'];
}
?>

```

---

#### 3. Iteració clau-valor amb `foreach`

```php
<?php
$perfilUsuari = [
    'usuari' => 'admin_carlos',
    'rol'    => 'Administrador',
    'email'  => 'carlos@techleads.valencia',
    'estat'  => 'Actiu'
];

echo "<ul>";
foreach ($perfilUsuari as $clau => $valor) {
    // htmlspecialchars per seguretat en mostrar dades directes
    echo "<li><strong>" . ucfirst($clau) . ":</strong> " . htmlspecialchars($valor) . "</li>";
}
echo "</ul>";
?>

```

---

### 4.3. Arrays Multidimensionals

Un **array multidimensional** és simplement un array que conté un o més arrays com a valors dels seus elements. S'utilitzen de manera massiva en PHP per a representar **llistats de taules de la base de dades** (on cada fila és un array associatiu).

#### 1. Definició i Estructura (Array de 2 Dimensions)

```php
<?php
// Llistat de diversos Leads (Array indexat que conté arrays associatius)
$llistatLeads = [
    [
        'id'         => 1,
        'nom'        => 'Joan Garcia',
        'empresa'    => 'Textils S.L.',
        'pressupost' => 4500.00,
        'estat'      => 'nou'
    ],
    [
        'id'         => 2,
        'nom'        => 'Laura Sanchis',
        'empresa'    => 'Innovació Tech',
        'pressupost' => 12000.00,
        'estat'      => 'convertit'
    ],
    [
        'id'         => 3,
        'nom'        => 'Marc Climent',
        'empresa'    => 'Econova',
        'pressupost' => 800.00,
        'estat'      => 'descartat'
    ]
];

// Accés a un valor específic: [Índex de la fila][Clau de la columna]
echo "Nom del segon lead: " . $llistatLeads[1]['nom'] . "<br>"; // Imprimeix: Laura Sanchis
echo "Pressupost del tercer lead: " . $llistatLeads[2]['pressupost'] . " €<br>"; // Imprimeix: 800 €
?>

```

---

#### 2. Recorregut de Matrius i Arrays Multidimensionals

##### Opció A: Neteja amb un sol `foreach` per a llistats tipus taula (el cas més comú)

```php
<?php
echo "<h2>Llistat de Leads Registrats</h2>";
echo "<table border='1' cellpadding='5'>";
echo "<tr><th>ID</th><th>Nom</th><th>Empresa</th><th>Pressupost</th></tr>";

foreach ($llistatLeads as $lead) {
    echo "<tr>";
    echo "<td>" . $lead['id'] . "</td>";
    echo "<td>" . htmlspecialchars($lead['nom']) . "</td>";
    echo "<td>" . htmlspecialchars($lead['empresa']) . "</td>";
    echo "<td>" . number_format($lead['pressupost'], 2, ',', '.') . " €</td>";
    echo "</tr>";
}

echo "</table>";
?>

```

##### Opció B: Bucles anidats (Quan no coneixem l'estructura de les claus)

```php
<?php
// Recórrer cada registre i totes les seues claus dinàmicament
foreach ($llistatLeads as $index => $lead) {
    echo "<h3>Lead #" . ($index + 1) . "</h3>";
    echo "<ul>";
    foreach ($lead as $clau => $valor) {
        echo "<li><strong>{$clau}:</strong> {$valor}</li>";
    }
    echo "</ul>";
}
?>

```

---

### 4.4. Funcions per a arrays

PHP té una llibreria nativa extremadament potent per a manipular arrays. Aquestes són les funcions essencials agrupades per utilitat:

#### 1. Comprovació, Mida i Detecció

* `count($array)`: Retorna el nombre d'elements de l'array.
* `in_array($cerca, $array, $estricte)`: Comprova si un valor existeix en l'array.
* `array_key_exists($clau, $array)`: Comprova si existeix una clau concreta.

```php
<?php
$estatsValids = ['nou', 'contactat', 'convertit', 'descartat'];
$estatActual = 'pendent';

if (!in_array($estatActual, $estatsValids)) {
    echo "L'estat '{$estatActual}' no és un estat vàlid per al sistema.";
}
?>

```

---

#### 2. Extracció de Claus i Valors

* `array_keys($array)`: Extrau totes les claus d'un array associatiu i les retorna en un array indexat.
* `array_values($array)`: Extrau tots els valors i reindexa l'array numèricament.

```php
<?php
$lead = ['id' => 1, 'nom' => 'Pau', 'email' => 'pau@test.com'];

$claus = array_keys($lead); 
// Resultat: ['id', 'nom', 'email']

$valors = array_values($lead); 
// Resultat: [1, 'Pau', 'pau@test.com']
?>

```

---

#### 3. Inserció, Eliminació i Cerca

* `array_push($array, $elem1, ...)`: Afegeix un o més elements al **final**.
* `array_pop($array)`: Extrau i elimina l'**últim** element.
* `array_unshift($array, $elem1, ...)`: Afegeix un o més elements al **principi**.
* `array_shift($array)`: Extrau i elimina el **primer** element.
* `array_search($cerca, $array)`: Cerca un valor i retorna la seua clau o índex (o `false` si no el troba).

```php
<?php
$cuaAtencio = ['Client A', 'Client B'];

// Entra un nou client
array_push($cuaAtencio, 'Client C'); // ['Client A', 'Client B', 'Client C']

// Atenem al primer client de la cua
$clientAtes = array_shift($cuaAtencio); // Atén 'Client A'. Queda ['Client B', 'Client C']

echo "S'ha atés a: " . $clientAtes;
?>

```

---

#### 4. Ordenació (*Sorting*)

::: warning **⚠️ Atenció:** 
La majoria de funcions d'ordenació en PHP modifiquen l'array original directament per referència i retornen un booleà (`true`/`false`).
:::

* `sort($array)`: Ordena de menor a major (els arrays associatius perden les seues claus originals).
* `rsort($array)`: Ordena de major a menor.
* `asort($array)`: Ordena un array associatiu pels seus **valors**, mantenint la correlació de claus.
* `ksort($array)`: Ordena un array associatiu per les seues **claus**.

```php
<?php
$preus = [1200, 4500, 300, 850];
sort($preus); 
// $preus ara és: [300, 850, 1200, 4500]

$leadCategories = [
    'Zion Tech' => 5000,
    'Acme S.L.' => 1500,
    'Beta Group' => 3000
];
asort($leadCategories); // Ordena pel pressupost mantenint la clau
// Resultat: Acme (1500), Beta (3000), Zion (5000)
?>

```

---

#### 5. Filtratge, Mapeig i Fusió (Avançat)

* `array_merge($array1, $array2)`: Combina dos o més arrays.
* `array_filter($array, $callback)`: Filtra els elements d'un array mitjançant una funció d'activació o condició booleana.
* `array_map($callback, $array)`: Aplica una funció a cada element de l'array per a transformar-lo.

```php
<?php
$leads = [
    ['nom' => 'Ana', 'pressupost' => 4000],
    ['nom' => 'Pere', 'pressupost' => 900],
    ['nom' => 'Marta', 'pressupost' => 6000]
];

// Filtrar NOMÉS els leads Enterprise (pressupost >= 1500)
$leadsEnterprise = array_filter($leads, function($lead) {
    return $lead['pressupost'] >= 1500;
});

/*
Resultat de $leadsEnterprise:
[
    ['nom' => 'Ana', 'pressupost' => 4000],
    ['nom' => 'Marta', 'pressupost' => 6000]
]
*/
?>

```

## Bloc 5: Modularització i disseny de l'aplicació


### 5.1. Funcions

Les funcions permeten agrupar blocs de codi reutilitzables, evitant duplicar lògica i millorant el manteniment de l'aplicació.

#### 1. Sintaxi bàsica i retorn de valors

Una funció es defineix amb la paraula clau `function`. Pot rebre **paràmetres** d'entrada i retornar un valor mitjançant `return`.

```php
<?php
function calcularIVA(float $import, float $tipus = 21.0): float {
    $iva = $import * ($tipus / 100);
    return $import + $iva;
}

$preuBase = 1000;
$preuFinal = calcularIVA($preuBase); // Utilitza el 21% per defecte -> 1210
$preuReduit = calcularIVA($preuBase, 10.0); // 1100
?>

```

---

#### 2. Pas de paràmetres: valor vs referència

* **Per valor (per defecte):** Es passa una còpia del valor. Si modifiquem la variable dins de la funció, fora no canvia.
* **Per referència (`&`):** Es passa la posició de memòria. Si es modifica la variable dins de la funció, **el canvi afecta la variable original**.

```php
<?php
// Pas per valor
function afegirSufix(string $text) {
    $text .= " - Processat";
}

$estat = "Pendent";
afegirSufix($estat);
echo $estat; // Imprimeix: "Pendent" (No ha canviat)

// Pas per referència (&)
function aplicarDescompte(float &$preu, float $percentatge) {
    $preu -= $preu * ($percentatge / 100);
}

$pressupost = 2000.0;
aplicarDescompte($pressupost, 15);
echo $pressupost; // Imprimeix: 1700 (S'ha modificat l'original)
?>

```

---

#### 3. Àmbit de les variables (*Scope*)

Les variables creades fora d'una funció **no estan disponibles** dins d'aquesta per defecte, i viceversa.

```php
<?php
$empresa = "TechLeads";

function mostrarEmpresa() {
    // Error: $empresa no existeix en aquest àmbit local!
    // Per a usar-la caldria fer: global $empresa; (no recomanat) 
    // O millor: passar-la com a paràmetre.
}
?>

```

---

### 5.2. Declaracions de tipus i mode estricte

A partir de PHP 7 i 8, podem especificar el tipus de dades dels paràmetres i del valor de retorn (*type hinting*). Això evita errors en temps d'execució i fa el codi molt més autodocumentat.

#### 1. Activar el mode estricte (`strict_types`)

Per defecte, PHP intenta convertir tipus automàticament (p. ex., la cadena `"500"` a un `int`). Si activem el mode estricte, PHP llançarà un error `TypeError` si els tipus no coincideixen exactament.

::: info **Regla d'or:** 
La directiva `declare(strict_types=1);` **ha de ser la primeres línia absoluta del fitxer**, just després de `<?php`.
:::

```php
<?php
declare(strict_types=1);

function formatarPressupost(float $quantitat, string $moneda = '€'): string {
    return number_format($quantitat, 2, ',', '.') . ' ' . $moneda;
}

// Correcte:
echo formatarPressupost(1500.50); // Retorna "1.500,50 €"

// Llançarà un TypeError en mode estricte (perquè "1500.50" és string, no float):
// echo formatarPressupost("1500.50"); 
?>

```

#### 2. Tipus Unió (*Union Types*) i Nullable (PHP 8+)

Podem permetre més d'un tipus de dades usant la barra vertical `|` o indicar que un valor pot ser `null` amb `?`.

```php
<?php
declare(strict_types=1);

// Permet int o float com a paràmetre, i retorna string o null
function buscarLeadPerId(array $llistat, int|string $id): ?array {
    foreach ($llistat as $lead) {
        if ($lead['id'] == $id) {
            return $lead; // Retorna array
        }
    }
    return null; // Retorna null si no el troba
}
?>

```

### 5.3. Inclusió de Fitxers (`require`, `include`)

PHP permet dividir el codi en diferents fitxers. Hi ha 4 opcions principals:

| Funció | Descripció | Si el fitxer NO existeix... |
| --- | --- | --- |
| `include` | Inclou el fitxer. | Llança un **Warning** i **continua** l'execució. |
| `require` | Inclou el fitxer. | Llança un **Fatal Error** i **atura** l'execució. |
| `include_once` | Com `include`, però només el carregarà **una vegada**. | Warning i continua. |
| `require_once` | Com `require`, però només el carregarà **una vegada**. | Fatal Error i atura. |

### Quina opció hem d'utilitzar?

* **`require_once`**: Per a carregar configuracions, llibreries de funcions o classes. Si falten, l'aplicació no pot funcionar.
* **`include`** / **`include_once`**: Per a carregar components visuals opcionalment (p. ex., un banner o un footer).

```php
<?php
// Carrega de funcions o configuració crítica
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/helpers.php';

// Renderitzat de plantilla
include __DIR__ . '/templates/header.php';
?>

```

---

### 5.4. Separació bàsica de vistes i lògica

Mesclar instruccions `echo` complexes de PHP amb estructures HTML dificulta la lectura i el manteniment. El patró recomanat és:

1. **Part Superior (Lògica / Controlador):** Rep la petició, valida, fa càlculs i prepara les variables.
2. **Part Inferior (Vista / Plantilla):** S'encarrega només de renderitzar l'HTML usant la **sintaxi alternativa de PHP** (`if:`, `endif;`, `foreach:`, `endforeach;`) i `<?= ?>` per a imprimir variables.

#### Exemple de separació correcta:

```php
<?php
// --- 1. LÒGICA I DADES (Backend) ---
declare(strict_types=1);

$usuariNom = "Carles";
$notificacions = ["Nou lead assignat", "Pressupost aprovat"];
$hasNotificacions = count($notificacions) > 0;
?>

<!-- --- 2. VISTA / HTML (Frontend) --- -->
<!DOCTYPE html>
<html lang="ca">
<head><title>Tauler</title></head>
<body>
    <h1>Hola, <?= htmlspecialchars($usuariNom) ?></h1>

    <?php if ($hasNotificacions): ?>
        <ul>
            <?php foreach ($notificacions as $msg): ?>
                <li><?= htmlspecialchars($msg) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No tens notificacions pendents.</p>
    <?php endif; ?>
</body>
</html>

```
## Bloc 6: Formularis

---

### 6.1. Trameça de Dades: Mètodes GET vs POST

Quan un formulari HTML s'envia, les dades viatgen utilitzant un mètode de petició HTTP. Els dos mètodes principals són **GET** i **POST**.

#### 1. Mètode GET

* **Com funciona:** Les dades s'adjunten directament a l'URL com a paràmetres de cerca (p. ex., `admin.php?estat=nou&orden=asc`).
* **Característiques:**
* **Visible:** Les dades es veuen en la barra d'adreces del navegador.
* **Limitat:** Té un límit de caràcters (aprox. 2.000 caràcters).
* **Marcable:** La pàgina es pot afegir als favorits o compartir l'enllaç amb la cerca feta.


* **Quan utilitzar-lo:** Per a cerques, filtres, paginació o consultes (operacions que **no modifiquen** dades al servidor).

#### 2. Mètode POST

* **Com funciona:** Les dades s'envien amagades en el cos (*body*) de la petició HTTP.
* **Característiques:**
* **Invisible en l'URL:** No es veuen les dades a la barra d'adreces.
* **Sense límit pràctic:** Permet enviar grans quantitats de text o fitxers (imatges, PDF...).
* **Més segur per a dades sensibles:** Evita que contrasenyes o dades privades queden registrades en l'historial del navegador.


* **Quan utilitzar-lo:** Per a creació o modificació de dades (registre d'usuaris, enviaments de formularis de contacte, pagaments, etc.).

---

### 6.2. Recuperació d'Informació amb superglobals (`$_GET`, `$_POST`)

PHP crea automàticament arrays associatius anomenats **superglobals** que estan disponibles en qualsevol part del codi.

#### 1. L'array `$_GET`

Conté totes les variables passades per la barra d'adreces o un formulari amb `method="GET"`.

```php
<?php
// Si l'URL és: cercar.php?paraula=php&pagina=2

$cerca = $_GET['paraula'] ?? ''; // "php"
$pagina = (int)($_GET['pagina'] ?? 1); // 2
?>

```

#### 2. L'array `$_POST`

Conté les dades enviades mitjançant un formulari amb `method="POST"`. Les claus de l'array corresponen a l'atribut `name` dels camps HTML.

```html
<!-- HTML (Formulari) -->
<form action="processar.php" method="POST">
    <input type="text" name="nom_client">
    <button type="submit">Enviar</button>
</form>

```

```php
<?php
// PHP (processar.php)
$nom = $_POST['nom_client'] ?? '';
?>

```

#### 3. Detectar si el formulari s'ha enviat

La variable superglobal `$_SERVER['REQUEST_METHOD']` ens indica quin mètode s'ha fet servir per a carregar la pàgina.

```php
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // El formulari s'ha enviat via POST! Procés de dades...
}
?>

```

---

### 6.3. Validació en el servidor

::: warning **⚠️ Regla de seguretat fonamental:** 
*Mai no et fies de les dades de l'usuari.* Qualsevol validació feta amb HTML5 (`required`, `type="email"`) o JavaScript es pot saltar fàcilment des del navegador. La validació real **SEMPRE s'ha de fer a PHP (Backend)**.
:::

Una validació bàsica consta de tres opcions principals:

1. **Comprovar si el camp està buit:** `empty(trim($valor))`
2. **Netejar caràcters espaiats redundants:** `trim($cadena)`
3. **Guardar els missatges d'error en un array.**

#### Exemple de Validació de Formulari

```php
<?php
declare(strict_types=1);

$errors = [];
$nom = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // 1. Aconseguir i netejar espais en blanc dels extrems
    $nom = trim($_POST['nom'] ?? '');
    $email = trim($_POST['email'] ?? '');

    // 2. Validar el nom
    if (empty($nom)) {
        $errors['nom'] = 'El camp nom és obligatori.';
    } elseif (mb_strlen($nom) < 2) {
        $errors['nom'] = 'El nom ha de tindre almenys 2 caràcters.';
    }

    // 3. Validar l'email
    if (empty($email)) {
        $errors['email'] = 'El correu electrònic és obligatori.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'El format del correu no és vàlid.';
    }

    // 4. Si no hi ha errors, processar dades!
    if (empty($errors)) {
        // Dades vàlides!
        // (Això ho farem a la Fase 6)
    }
}
?>

```

## Bloc 7. Bibliografia i Recursos

Pendent completar


## Projecte final: TechLeads App

### Descripció del projecte

**TechLeads** és un panell de captació i gestió de potencials clients (*leads*) per a una agència de serveis digitals (desenvolupament web, e-commerce, SEO, apps mòbils).

L'objectiu del projecte és transformar una interfície en HTML/CSS estàtica en una **aplicació web dinàmica, modular, segura i persistent en PHP**, desenvolupant les seues funcionalitats pas a pas a mesura que s'avançarà en els diferents blocs del curs.

---

### Arquitectura inicial dels fitxers

L'estructura inicial del repositori de treball serà la següent:

```text
techleads/
├── Dockerfile
├── docker-compose.yml
└── src/
    ├── index.php        <-- Landing page pública + Formulari de contacte
    ├── confirmacio.php  <-- Pàgina de recepció del formulari (Pública)
    ├── admin.php        <-- Panell privat de gestió de leads (Taula + Mètrics)
    └── detall.php       <-- Fitxer de detall individual d'un lead (Privat)

```


### Plantilles base de l'aplicació (`src/`)

*(Utilitzem **Tailwind CSS via CDN** en totes les plantilles per a mantindre un disseny modern i clar sense necessitat de fitxers CSS addicionals).*

::: details `src/index.php` (Landing page i formulari públic)

```php
<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TechLeads | Serveis de Desenvolupament Web</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 text-slate-800 font-sans min-h-screen flex flex-col justify-between">

    <!-- Capçalera Pública -->
    <header class="bg-white border-b border-slate-200 py-4 shadow-sm">
        <div class="max-w-7xl mx-auto px-6 flex justify-between items-center">
            <div class="flex items-center gap-2">
                <span class="font-extrabold text-2xl text-indigo-600">TechLeads</span>
                <span class="text-xs bg-slate-100 text-slate-600 px-2 py-1 rounded font-mono">v1.0</span>
            </div>
            <a href="admin.php" class="text-xs font-semibold text-slate-500 hover:text-indigo-600 transition">
                Accés Privat (Admin) &rarr;
            </a>
        </div>
    </header>

    <!-- Contingut Principal (Layout a 2 columnes) -->
    <main class="max-w-7xl mx-auto px-6 py-12 w-full">

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
            
            <!-- Columna Esquerra: Informació dels Serveis -->
            <div class="lg:col-span-5 space-y-8">
                <div>
                    <span class="text-xs font-semibold uppercase tracking-wider text-indigo-600 bg-indigo-50 px-3 py-1 rounded-full border border-indigo-100">
                        Agència de Desenvolupament Digital
                    </span>
                    <h1 class="text-4xl font-extrabold text-slate-900 tracking-tight mt-4">
                        Transformem la teua presència digital
                    </h1>
                    <p class="text-slate-600 mt-4 text-base leading-relaxed">
                        Ajudem empreses i startups a escalar els seus negocis dissenyant experiències digitals d'alt rendiment, segures i optimitzades per a la conversió.
                    </p>
                </div>

                <!-- Llista de Serveis -->
                <div class="space-y-4">
                    <h2 class="text-sm font-bold uppercase tracking-wider text-slate-400">Els nostres serveis</h2>
                    
                    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex items-start gap-4">
                        <div class="bg-indigo-100 text-indigo-600 p-2.5 rounded-lg text-lg font-bold">💻</div>
                        <div>
                            <h3 class="font-bold text-slate-900 text-sm">Desenvolupament Web Custom</h3>
                            <p class="text-slate-500 text-xs mt-0.5">Llocs web ràpids, a mida i optimitzats per a motors de cerca.</p>
                        </div>
                    </div>

                    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex items-start gap-4">
                        <div class="bg-indigo-100 text-indigo-600 p-2.5 rounded-lg text-lg font-bold">🛒</div>
                        <div>
                            <h3 class="font-bold text-slate-900 text-sm">Botiga Online (eCommerce)</h3>
                            <p class="text-slate-500 text-xs mt-0.5">Plataformes de venda en línia escalables integrades amb ERP i pasarel·les de pagament.</p>
                        </div>
                    </div>

                    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex items-start gap-4">
                        <div class="bg-indigo-100 text-indigo-600 p-2.5 rounded-lg text-lg font-bold">⚡</div>
                        <div>
                            <h3 class="font-bold text-slate-900 text-sm">Auditoria SEO & Rendiment</h3>
                            <p class="text-slate-500 text-xs mt-0.5">Optimització Core Web Vitals i estratègies per a millorar el posicionament orgànic.</p>
                        </div>
                    </div>

                    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex items-start gap-4">
                        <div class="bg-indigo-100 text-indigo-600 p-2.5 rounded-lg text-lg font-bold">📱</div>
                        <div>
                            <h3 class="font-bold text-slate-900 text-sm">Aplicació Mòbil (App)</h3>
                            <p class="text-slate-500 text-xs mt-0.5">Desenvolupament aplicacions natives i multiplataforma per a iOS i Android.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Columna Dreta: Formulari de Captació -->
            <div class="lg:col-span-7">
                <div class="bg-white p-8 rounded-2xl shadow-lg border border-slate-200">
                    <h2 class="text-2xl font-bold text-slate-900 mb-2">Sol·licita el teu pressupost</h2>
                    <p class="text-slate-500 text-sm mb-6">Emplena el formulari i ens posarem en contacte en menys de 24 hores.</p>

                    <form action="confirmacio.php" method="POST" class="space-y-6" novalidate>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Nom -->
                            <div>
                                <label for="nom" class="block text-sm font-semibold text-slate-700 mb-1">Nom complet *</label>
                                <input type="text" id="nom" name="nom" placeholder="Ex: Maria Garcia" class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                            </div>

                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-sm font-semibold text-slate-700 mb-1">Correu electrònic *</label>
                                <input type="email" id="email" name="email" placeholder="maria@empresa.com" class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Empresa -->
                            <div>
                                <label for="empresa" class="block text-sm font-semibold text-slate-700 mb-1">Nom de l'Empresa</label>
                                <input type="text" id="empresa" name="empresa" placeholder="Ex: Logística S.L." class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                            </div>

                            <!-- Servei -->
                            <div>
                                <label for="servei" class="block text-sm font-semibold text-slate-700 mb-1">Servei d'interés *</label>
                                <select id="servei" name="servei" class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition bg-white">
                                    <option value="">Selecciona un servei...</option>
                                    <option value="web">Desenvolupament Web Custom</option>
                                    <option value="ecommerce">Botiga Online (eCommerce)</option>
                                    <option value="seo">Auditoria SEO & Rendiment</option>
                                    <option value="app">Aplicació Mòbil (App)</option>
                                </select>
                            </div>
                        </div>

                        <!-- Pressupost Estimat -->
                        <div>
                            <label for="pressupost" class="block text-sm font-semibold text-slate-700 mb-1">Pressupost estimat (€)</label>
                            <input type="number" id="pressupost" name="pressupost" placeholder="Ex: 3500" class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                        </div>

                        <!-- Missatge -->
                        <div>
                            <label for="missatge" class="block text-sm font-semibold text-slate-700 mb-1">Detalls del projecte</label>
                            <textarea id="missatge" name="missatge" rows="4" placeholder="Explica'ns breument què necessites..." class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition"></textarea>
                        </div>

                        <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3.5 px-6 rounded-xl shadow-md transition duration-200">
                            Enviar Sol·licitud de Pressupost
                        </button>

                    </form>
                </div>
            </div>

        </div>

    </main>

    <!-- Peu de pàgina -->
    <footer class="bg-white border-t border-slate-200 py-6 text-center text-xs text-slate-500">
        <p>TechLeads &copy; 2026 — Tots els drets reservats.</p>
    </footer>

</body>
</html>

```
:::

::: details `src/confirmacio.php` (Pàgina de confirmació)

```php
<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sol·licitud Rebudes | TechLeads</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 text-slate-800 font-sans min-h-screen flex flex-col justify-between">

    <header class="bg-white border-b border-slate-200 py-4 shadow-sm">
        <div class="max-w-6xl mx-auto px-6 flex justify-between items-center">
            <span class="font-extrabold text-2xl text-indigo-600">TechLeads</span>
        </div>
    </header>

    <main class="max-w-xl mx-auto px-6 py-16 w-full text-center">

        <div class="bg-white p-8 rounded-2xl shadow-lg border border-slate-200">
            <div class="w-16 h-16 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl font-bold">
                ✓
            </div>

            <h1 class="text-2xl font-bold text-slate-900 mb-2">Gràcies per la teua sol·licitud!</h1>
            <p class="text-slate-600 text-sm mb-6">Hem rebut les teues dades correctament. Ens posarem en contacte amb tu en menys de 24 hores.</p>

            <!-- Resum de Dades Enviades (Estàtic) -->
            <div class="bg-slate-50 rounded-xl p-4 text-left border border-slate-200 space-y-2 text-xs mb-6">
                <p><strong class="text-slate-700">Nom:</strong> Maria Garcia</p>
                <p><strong class="text-slate-700">Email:</strong> maria@empresa.com</p>
                <p><strong class="text-slate-700">Empresa:</strong> Logística S.L.</p>
                <p><strong class="text-slate-700">Servei:</strong> Botiga Online (eCommerce)</p>
                <p><strong class="text-slate-700">Pressupost estimat:</strong> 3.500,00 €</p>
            </div>

            <a href="index.php" class="inline-block bg-slate-900 hover:bg-slate-800 text-white font-medium text-sm px-5 py-2.5 rounded-lg transition">
                Tornar a l'inici
            </a>
        </div>

    </main>

    <footer class="bg-white border-t border-slate-200 py-6 text-center text-xs text-slate-500">
        <p>TechLeads &copy; 2026 — Tots els drets reservats.</p>
    </footer>

</body>
</html>

```
:::

::: details `src/admin.php` (Panell privat d'administració)

```php
<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panell Privat d'Administració | TechLeads</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 text-slate-800 font-sans min-h-screen">

    <header class="bg-slate-900 text-white py-4 shadow-lg">
        <div class="max-w-7xl mx-auto px-6 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <span class="font-bold text-xl text-indigo-400">TechLeads</span>
                <span class="text-xs bg-indigo-900/60 text-indigo-300 border border-indigo-700 px-2.5 py-0.5 rounded-full font-mono">Àrea Privada</span>
            </div>
            <a href="index.php" class="text-xs text-slate-400 hover:text-white transition">&larr; Anar a la Web Pública</a>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-6 py-8">

        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900">Gestió de Leads</h1>
                <p class="text-slate-500 text-sm mt-1">Llistat global i analítica de les sol·licituds rebudes.</p>
            </div>
        </div>

        <!-- Targetes Mètriques (Amb Targeta de Visites afegida) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
            
            <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
                <p class="text-xs font-semibold text-slate-400 uppercase">Visites Pàgina</p>
                <p class="text-2xl font-bold text-slate-900 mt-1">50</p>
                <p class="text-[10px] text-slate-400 mt-1">Tràfic total registrat</p>
            </div>

            <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
                <p class="text-xs font-semibold text-slate-400 uppercase">Total Leads</p>
                <p class="text-2xl font-bold text-slate-900 mt-1">3</p>
                <p class="text-[10px] text-slate-400 mt-1">Sol·licituds creades</p>
            </div>

            <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
                <p class="text-xs font-semibold text-slate-400 uppercase">Taxa Conversió</p>
                <p class="text-2xl font-bold text-emerald-600 mt-1">6.0%</p>
                <p class="text-[10px] text-slate-400 mt-1">Leads / Visites (3/50)</p>
            </div>

            <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
                <p class="text-xs font-semibold text-slate-400 uppercase">Volum Total</p>
                <p class="text-2xl font-bold text-indigo-600 mt-1">16.200,00 €</p>
                <p class="text-[10px] text-slate-400 mt-1">Pressupost acumulat</p>
            </div>

            <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
                <p class="text-xs font-semibold text-slate-400 uppercase">Mitjana / Projecte</p>
                <p class="text-2xl font-bold text-slate-900 mt-1">5.400,00 €</p>
                <p class="text-[10px] text-slate-400 mt-1">Estimació mitjana</p>
            </div>

        </div>

        <!-- Filtre de Cerca (Estat, Servei i Cerca per Nom) -->
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm mb-6">
            <form action="admin.php" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                
                <!-- Cercar per Nom -->
                <div>
                    <label for="nom" class="block text-xs font-semibold text-slate-500 uppercase mb-1">Cercar per client</label>
                    <input type="text" id="nom" name="nom" placeholder="Nom o email..." class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500 outline-none">
                </div>

                <!-- Filtrar per Estat -->
                <div>
                    <label for="estat" class="block text-xs font-semibold text-slate-500 uppercase mb-1">Estat</label>
                    <select id="estat" name="estat" class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500 outline-none bg-white">
                        <option value="">Tots els estats</option>
                        <option value="nou">Nou</option>
                        <option value="contactat">Contactat</option>
                        <option value="convertit">Convertit</option>
                        <option value="descartat">Descartat</option>
                    </select>
                </div>

                <!-- Filtrar per Servei -->
                <div>
                    <label for="servei" class="block text-xs font-semibold text-slate-500 uppercase mb-1">Servei</label>
                    <select id="servei" name="servei" class="w-full px-3 py-2 text-sm rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500 outline-none bg-white">
                        <option value="">Tots els serveis</option>
                        <option value="web">Desenvolupament Web Custom</option>
                        <option value="ecommerce">Botiga Online (eCommerce)</option>
                        <option value="seo">Auditoria SEO & Rendiment</option>
                        <option value="app">Aplicació Mòbil (App)</option>
                    </select>
                </div>

                <!-- Botó d'Acció -->
                <div class="flex gap-2">
                    <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-sm py-2 px-4 rounded-lg transition">
                        Filtrar
                    </button>
                    <a href="admin.php" class="bg-slate-100 hover:bg-slate-200 text-slate-600 font-semibold text-sm py-2 px-3 rounded-lg transition text-center flex items-center justify-center">
                        Netejar
                    </a>
                </div>

            </form>
        </div>

        <!-- Taula de Dades -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-600">
                    <thead class="bg-slate-100 text-xs text-slate-700 uppercase font-bold border-b border-slate-200">
                        <tr>
                            <th class="py-3.5 px-4">ID</th>
                            <th class="py-3.5 px-4">Client / Empresa</th>
                            <th class="py-3.5 px-4">Servei</th>
                            <th class="py-3.5 px-4">Pressupost</th>
                            <th class="py-3.5 px-4">Estat</th>
                            <th class="py-3.5 px-4 text-right">Accions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <!-- Fila 1 d'exemple -->
                        <tr class="hover:bg-slate-50 transition">
                            <td class="py-3.5 px-4 font-mono text-xs text-slate-400">#001</td>
                            <td class="py-3.5 px-4">
                                <div class="font-semibold text-slate-900">Maria Garcia</div>
                                <div class="text-xs text-slate-400">maria@empresa.com</div>
                            </td>
                            <td class="py-3.5 px-4">Botiga Online</td>
                            <td class="py-3.5 px-4 font-mono font-semibold">4.500,00 €</td>
                            <td class="py-3.5 px-4">
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-800 border border-amber-200">Nou</span>
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <a href="detall.php?id=1" class="text-indigo-600 hover:text-indigo-900 font-medium text-xs">Veure Detall &rarr;</a>
                            </td>
                        </tr>
                        <!-- Fila 2 d'exemple -->
                        <tr class="hover:bg-slate-50 transition">
                            <td class="py-3.5 px-4 font-mono text-xs text-slate-400">#002</td>
                            <td class="py-3.5 px-4">
                                <div class="font-semibold text-slate-900">Vicent Pastor</div>
                                <div class="text-xs text-slate-400">vpastor@innovatech.valencia</div>
                            </td>
                            <td class="py-3.5 px-4">Desenvolupament Web Custom</td>
                            <td class="py-3.5 px-4 font-mono font-semibold">8.200,00 €</td>
                            <td class="py-3.5 px-4">
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800 border border-emerald-200">Convertit</span>
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <a href="detall.php?id=2" class="text-indigo-600 hover:text-indigo-900 font-medium text-xs">Veure Detall &rarr;</a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- APARTAT PER A QUAN NO ES TROBEN RESULTATS (Empty State) -->
            <!-- Nota: Durant la programació en PHP, aquest bloc només es mostrarà si la llista de leads està buida -->
            <div class="hidden py-12 text-center">
                <div class="w-16 h-16 bg-slate-100 text-slate-400 rounded-full flex items-center justify-center mx-auto mb-3 text-2xl">
                    🔍
                </div>
                <h3 class="text-base font-bold text-slate-800">No s'han trobat leads</h3>
                <p class="text-slate-500 text-sm mt-1 max-w-md mx-auto">
                    No hi ha cap sol·licitud que coincidisca amb els filtres seleccionats o encara no s'ha registrat cap lead.
                </p>
                <a href="admin.php" class="inline-block mt-4 text-xs font-semibold text-indigo-600 hover:text-indigo-800">
                    Restablir tots els filtres
                </a>
            </div>

        </div>

    </main>

</body>
</html>

```
:::

::: details `src/detall.php` (Detall privat d'un lead)

```php
<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detall del Lead | TechLeads</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 text-slate-800 font-sans min-h-screen">

    <header class="bg-slate-900 text-white py-4 shadow-lg">
        <div class="max-w-7xl mx-auto px-6 flex justify-between items-center">
            <span class="font-bold text-xl text-indigo-400">TechLeads Admin</span>
            <a href="admin.php" class="text-xs text-slate-400 hover:text-white transition">&larr; Tornar al Panell</a>
        </div>
    </header>

    <main class="max-w-2xl mx-auto px-6 py-12">

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8">
            
            <div class="flex justify-between items-start mb-6">
                <div>
                    <span class="text-xs font-mono text-slate-400">ID: #001</span>
                    <h1 class="text-2xl font-bold text-slate-900 mt-1">Maria Garcia</h1>
                    <p class="text-slate-500 text-sm">Logística Garcia S.L.</p>
                </div>
                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-800 border border-amber-200">Nou</span>
            </div>

            <div class="space-y-4 border-t border-b border-slate-100 py-6 my-6 text-sm">
                <div class="flex justify-between">
                    <span class="text-slate-500">Correu Electrònic:</span>
                    <span class="font-medium text-slate-900">maria@empresa.com</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500">Servei Sol·licitat:</span>
                    <span class="font-medium text-slate-900">Botiga Online (eCommerce)</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500">Pressupost Estimat:</span>
                    <span class="font-mono font-bold text-indigo-600">4.500,00 €</span>
                </div>
            </div>

            <div class="mb-6">
                <h3 class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Detalls del projecte</h3>
                <p class="bg-slate-50 p-4 rounded-xl text-slate-700 text-sm border border-slate-200">
                    Necessitem una plataforma e-commerce per a gestionar les nostres vendes de recanvis industrials amb integració ERP.
                </p>
            </div>

            <a href="admin.php" class="inline-block text-sm font-medium text-indigo-600 hover:text-indigo-800">
                &larr; Tornar al llistat de leads
            </a>

        </div>

    </main>

</body>
</html>

```
:::

### Activitats a realitzar

#### **Bloc 1: Configuració de l'Entorn i Primeres Variables (PHP Bàsic)**

**Activitat 1.1 — Desplegament de l'entorn:** 

Desplega el projecte utilitzant Docker a partir dels exemples i configuracions vistos a la unitat. Comprova des del navegador que tens accés a totes les pàgines del projecte (`index.php`, `confirmacio.php`, `admin.php` i `detall.php`).

**Activitat 1.2 — Primera variable i eixida de dades:**
Defineix una variable anomenada `$autor` amb el teu nom complet. Mostra el seu valor al peu de pàgina (*footer*) de totes les pàgines de l'aplicació fent ús de la sintaxi curta d'impressió (`<?= $autor ?>`).



#### **Bloc 2: Maneig de Variables, Constants i Operacions (PHP Bàsic)**

**Activitat 2.1 — Estructura i comentaris:** 
Inclou un comentari en el codi abans de cada exercici indicant el bloc i el número d'activitat (per exemple: `// Bloc 2 - Activitat 2.2`).

**Activitat 2.2 — Càrrega de dades simulades en `confirmacio.php`:** 
Declara les variables necessàries a l'inici del fitxer `confirmacio.php` per a simular les dades rebudes d'un formulari (nom, email, empresa, servei, pressupost, detalls). Substitueix el text estàtic de la plantilla per les corresponents variables PHP.

**Activitat 2.3 — Càrrega de dades simulades en `detall.php`:** 
Realitza el mateix procés en `detall.php`: defineix les variables necessàries a l'inici del fitxer per a simular la informació d'un lead concret (incloent-hi el seu estat actual) i mostra-les en el seu lloc corresponent.

**Activitat 2.4 — Càlcul de mètriques dinàmiques en `admin.php`:** 
Defineix variables a l'inici d'’`admin.php` per a emmagatzemar el número de visites i el número de leads.
* Utilitza la funció `random_int()` per a generar valors aleatoris.
* Calcula de manera dinàmica la **taxa de conversió** mitjançant la fórmula:
`taxa_conversio = (leads / visites)* 100`


* Imprimeix aquests valors i el resultat del càlcul en les targetes de mètriques corresponents.
* Utilitza la funció `number_format` per mostrar la taxa de conversió amb 2 decimals.


**Activitat 2.5 — Refactorització a constant:** Modifica la variable `$autor` creada en el Bloc 1 perquè siga una **constant** (mitjançant `define()` o `const`). Actualitza el *footer* de totes les pàgines per a imprimir la nova constant.


#### **Bloc 3: Control de Flux i Estructures Condicionals**

**Activitat 3.1 — Valors per defecte amb l'operador Null Coalescing (`??`):** 
En `confirmacio.php`, utilitza l'operador `??` per a assignar el text `"Sense especificar"` si les variables del nom de l'empresa o del pressupost no tenen un valor definit o són nul·les.


**Activitat 3.2 — Renderitzat condicional de camps opcional:** 
En `confirmacio.php`, mostra el bloc de "Detalls del projecte" **únicament** si la variable conté informació. Si està buida o no existeix, eix bloc no s'ha de dibuixar en el HTML.

**Activitat 3.3 — Condicionals aplicats a `detall.php`:** 
Aplica les mateixes lògiques de les activitats 3.1 i 3.2 al fitxer `detall.php` per a gestionar els camps opcionals i els detalls del projecte.

**Activitat 3.4 — Estat buit (*Empty State*) en `admin.php`:** 
En `admin.php`, afegeix una estructura condicional (`if/else`):
* Si el número de leads és **Major que 0**, mostra la taula amb el llistat de leads.
* Si el número de leads és **0**, amaga la taula i elimina la classe `hidden` del contenidor de "Sense resultats" (*empty state*) perquè siga visible.



#### **Bloc 4: Estructures de Dades (Arrays i Recorregut amb Bucles)**

**Activitat 4.1 — Catàleg de serveis com a Array Associatiu:**
Crea un array associatiu amb els serveis que ofereix l'empresa.
* La **clau** (*key*) ha de coincidir amb l'atribut `value` de les opcions del desplegable del formulari (`web`, `ecommerce`, `seo`, `app`).
* El **valor** contindrà un array amb el nom i la descripció del servei que es mostra en `index.php`.


**Activitat 4.2 — Generació dinàmica del desplegable de serveis:**
Modifica el formulari de `index.php` perquè les opcions (`<option>`) del camp desplegable de serveis es generen de manera **100% dinàmica** recorrent l'array associatiu creat en l'activitat 4.1 mitjançant un bucle `foreach`.

**Activitat 4.3 — Refactorització a Array Associatiu en `confirmacio.php`:**
Agrupa totes les variables individuals creades anteriorment en `confirmacio.php` dins d'un únic **array associatiu** anomenat `$lead` (amb claus com `'nom'`, `'email'`, `'empresa'`, `'servei'`, etc.) i actualitza la pàgina per a imprimir les dades des d'aquest array.

**Activitat 4.4 — Refactorització a Array Associatiu en `detall.php`:**
Aplica el mateix canvi en `detall.php`: agrupa la informació del lead en un array associatiu `$lead` (incloent-hi la clau `'estat'`) i utilitza les seues claus per a mostrar la informació en el resum de la sol·licitud.

**Activitat 4.5 — Llistat dinàmic i càlcul de mètriques des d'un Array Multidimensional en `admin.php`:**
En `admin.php`, crea un **array multidimensional** `$leads` que continga un conjunt de leads de prova (cada element serà un array associatiu com el dels exercicis anteriors).
* Recórre l'array mitjançant un bucle (`foreach`) per a generar dinàmicament les files (`<tr>`) de la taula d'administració.
* **Càlcul dinàmic:** Substitueix els valors manuals de les targetes de mètriques per càlculs automàtics basats en l'array `$leads`:
* **Total Leads:** Calcula'l amb `count($leads)`.
* **Volum Total:** Suma tots els pressupostos dels leads iterant sobre l'array.
* **Mitjana per projecte:** Divideix el Volum Total entre el Total de Leads.


* Mantén la lògica del **Bloc 3**: si l'array `$leads` està buit (`empty($leads)`), mostra el bloc de *Sense resultats* (*empty state*) i amaga la taula.



#### **Bloc 5: Modularització, Helpers i Funcions Personalitzades**

**Activitat 5.1 — Creació de components globals (`header.php` i `footer.php`):**
Crea la carpeta `src/includes/` i extrau el codi comú de la capçalera i del peu de pàgina a dos fitxers independents: `header.php` i `footer.php`.
Carrega aquests components en `index.php`, `confirmacio.php`, `admin.php` i `detall.php` utilitzant `require_once` per a evitar duplicar codi HTML.

**Activitat 5.2 — Centralització de la configuració (`config.php`):**
Crea el fitxer `config.php` per a definir les configuracions globals de l'aplicació:
* Constant de l'autor (`AUTOR`) i nom de l'aplicació (`APP_NAME`).
* Llistat de serveis disponibles (trasllada ací l'array de l'activitat 4.1) i llistat d'estats permesos (`nou`, `contactat`, `convertit`, `descartat`).
Inclou aquest fitxer (`require_once 'config.php'`) en totes les pàgines i utilitza aquestes constants i estructures globals.


**Activitat 5.3 — Funció de depuració (*Debug Helper*) (`helpers.php`):**
Crea el fitxer `helpers.php` i defineix la funció `dd($variable)` (*Dump and Die*).
* La funció ha d'imprimir el contingut de qualsevol variable formatat entre etiquetes HTML `<pre>` utilitzant `var_dump()` o `print_r()`.
* Després d'imprimir, ha d'aturar immediatament l'execució de l'script amb `exit;` o `die();`.


**Activitat 5.4 — Formata d'estats amb l'expressió `match` (`funcions.php`):**
Crea el fitxer `funcions.php` i defineix la funció `etiquetes_estat(string $estat): string`.
* Utilitza l'expressió `match` de PHP 8 per a avaluar el paràmetre `$estat`.
* La funció ha de retornar l'etiqueta HTML (*badge*) de Tailwind formatada amb el text i els colors corresponents segons l'estat (ex: verd per a `convertit`, groc per a `nou`, roig per a `descartat`, etc.).
* Utilitza aquesta funció en `admin.php` i `detall.php` per a renderitzar la columna d'estat de manera neta i reutilitzable.


#### **Bloc 6: Captura de Dades, Paràmetres d'URL i Filtres (Superglobals `$_POST` i `$_GET`)**

**Activitat 6.1 — Processament i recepció del formulari via `$_POST`:**
Configura el formulari de `index.php` perquè envie les dades mitjançant el mètode `POST` a la pàgina `confirmacio.php`.
* En `confirmacio.php`, captura els valors enviats des de la superglobal `$_POST`.
* Agrupa aquests valors dins de l'array associatiu `$lead` definit en blocs anteriors.
* Imprimeix la informació de la sol·licitud rebuda a la interfície de confirmació.
* *(Nota: De moment no realitzarem validacions complexes de seguretat; ens centrarem en la correcta recepció de dades).*


**Activitat 6.2 — Consulta de detall per paràmetre d'URL via `$_GET`:**
En `admin.php`, assegura't que els enllaços de *"Veure Detall"* de la taula de leads apunten a `detall.php?id=X` (on `X` és l'ID del lead).
En `detall.php`:
* Captura l'ID del lead a través de la superglobal `$_GET['id']`.
* Cerca el lead corresponent dins de l'array multidimensional de leads.
* Si el lead existeix, mostra la seua informació completa.
* Si l'ID no s'ha enviat o el lead no es troba, mostra un missatge d'error clar (*"El lead sol·licitat no existeix"*) i amaga la targeta de detall.


**Activitat 6.3 — Cerca i filtrat dinàmic de leads (`filtrar_leads`):**
En el fitxer `funcions.php`, crea la funció `filtrar_leads(array $leads, array $filtres): array`.
* La funció ha de rebre l'array complet de leads i un array amb els 3 criteris de cerca capturats per `$_GET` des del formulari de filtres d'’`admin.php`:
1. **`nom`** (Cerca per text/coincidència parcial en el nom o email).
2. **`estat`** (Filtrat per estat exacte: `nou`, `contactat`, `convertit`, etc.).
3. **`servei`** (Filtrat per tipus de servei sol·licitat).


* Utilitza la funció en `admin.php` per a obtindre l'array de leads filtrat i actualitza les taules i el càlcul de les mètriques d'acord amb els resultats obtinguts.


**Activitat 6.4 — Persistència visual dels filtres actius (UX):**
Modifica el formulari de cerca i filtrat de `admin.php` per a mantindre reflectits els valors que l'usuari acaba de filtrar després de recarregar la pàgina:
* Fes servir la superglobal `$_GET` (amb l'operador `?? ''`) per a establir l'atribut `value="..."` en l'input del cercador de nom.
* Afegeix l'atribut `selected` a l'opció (`<option>`) corresponent en els desplegables d'**Estat** i **Servei** si el seu valor coincideix amb el filtre actiu enviat per `$_GET`.

