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

1. [1. Introducció i Entorn](#1-introduccio-i-entorn-de-desenvolupament)

    1.1. [Què és PHP? Conceptes fonamentals](#_1-1-que-es-php-conceptes-fonamentals)

    1.2. [Configuració de l'entorn de desenvolupament amb Docker](#_1-2-configuracio-de-lentorn-de-desenvolupament-amb-docker)

    1.3. [Integració de codi PHP en HTML](#_1-3-integracio-de-codi-php-en-html)

    1.4. [Frontend modern: Introducció a Tailwind CSS (CDN)](#_1-4-frontend-modern-introduccio-a-tailwind-css-cdn)
    
2. [2: Fonaments del Llenguatge PHP](#2-fonaments-del-llenguatge-php)

    2.1. [Variables, tipat dinàmic i case sensitivity](#_2-1-variables-tipat-dinamic-i-case-sensitivity)

    2.2. [Tipus de dades primitius](#_2-2-tipus-de-dades-primitius)
    
    2.3. [Àmbits de les variables (Local vs Global)](#_2-3-ambits-de-les-variables-local-vs-global)
    
    2.4. [Operadors i definició de constants](#_2-4-operadors-i-definicio-de-constants)
    
    2.5. [Comentaris i bones pràctiques](#_2-5-comentaris-i-bones-practiques)

3. [3: Estructures de Control i Depuració](#3-estructures-de-control-i-depuracio)
   
    3.1. [Mecanismes de decisió (if, else, switch)](#31-mecanismes-de-decisio-if-else-switch)

    3.2. [Bucles i iteració (for, while, foreach)](#32-bucles-i-iteracio-for-while-foreach)

    3.3. [Tècniques de depuració (Debug)](#33-tecniques-de-depuracio-debug)


4. [4: Gestió de Dades amb Arrays](#4-gestio-de-dades-amb-arrays)
   
    4.1. [Arrays indexats](#41-arrays-indexats)
    
    4.2. [Arrays associatius](#42-arrays-associatius)

    4.3. [Arrays multidimensionals](#43-arrays-multidimensionals)

    4.4. [Funcions predefinides per a arrays](#44-funcions-predefinides-per-a-arrays)


5. [5: Modularització i Disseny de l'Aplicació](#5-modularitzacio-i-disseny-de-laplicacio)

    5.1. [Funcions d'usuari i pas de paràmetres](#51-funcions-dusuari-i-pas-de-parametres)

    5.2. [Declaracions de tipus i mode estricte](#52-declaracions-de-tipus-i-mode-estricte)

    5.3. [Inclusió de fitxers (require, include)](#53-inclusio-de-fitxers-require-include)

    5.4. [Separació bàsica de Vistes i Lògica](#54-separacio-basica-de-vistes-i-logica)


6. [6: Formularis](#6-formularis)
   
    6.1. [Trameça de dades: Mètodes GET vs POST](#61-trameca-de-dades-metodes-get-vs-post)

    6.2. [Recuperació d'informació amb superglobals ($_GET,$_POST)](#62-recuperacio-dinformacio-amb-superglobals-get-post)

    6.3. [Validació bàsica al costat del servidor](#63-validacio-basica-al-costat-del-servidor)

7. [Exercicis](#exercicis)



## 1. Introducció i Entorn de Desenvolupament

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

## 2. Fonaments del llenguatge PHP

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
|  |  |  |
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

| Criteri | define() | const |
|---|---|---|
| **Àmbit** | Es pot definir en qualsevol lloc (dins d'un if, funció, etc.) | Només en el nivell superior del script o dins de classes |
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





## 3. Estructures de Control i Depuració



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




## 4. Gestió de Dades amb Arrays


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

## 5. Modularització i disseny de l'aplicació


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
|  |  |  |
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



### 5.4. Separació bàsica de vistes i lògica

Mesclar instruccions `echo` complexes de PHP amb estructures HTML dificulta la lectura i el manteniment. El patró recomanat és:

1. **Part Superior (Lògica / Controlador):** Rep la petició, valida, fa càlculs i prepara les variables.
2. **Part Inferior (Vista / Plantilla):** S'encarrega només de renderitzar l'HTML usant la **sintaxi alternativa de PHP** (`if:`, `endif;`, `foreach:`, `endforeach;`) i `<?= ?>` per a imprimir variables.

#### Exemple de separació correcta:

```php
<?php
//  1. LÒGICA I DADES (Backend) 
declare(strict_types=1);

$usuariNom = "Carles";
$notificacions = ["Nou lead assignat", "Pressupost aprovat"];
$hasNotificacions = count($notificacions) > 0;
?>

<!--  2. VISTA / HTML (Frontend)  -->
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
## 6. Formularis



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

## 7. Exercicis

### 7.1. Part 1: introducció i entorn

#### Exercici 1.1 — La teua primera pàgina PHP

**Fitxer de partida:** `exercici1.1.php`
::: details 📄 exercici1.1.php
```php
<?php
    // TODO 1: Declara ací les variables $nomEmpresa, $eslogan i $anyFundacio
?>
<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exercici 1.1 - Primera pàgina PHP</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col items-center justify-center text-center">

    <h1 class="text-4xl font-bold text-blue-700"><?= /* TODO 2: nom de l'empresa */ ?></h1>
    <p class="text-lg text-gray-700 mt-2"><?= /* TODO 3: eslogan */ ?></p>
    <p class="text-sm text-gray-500 mt-1">Fundada l'any <?= /* TODO 4: any de fundació */ ?></p>

</body>
</html>
``
:::

**Objectiu:** Practicar la integració bàsica de PHP en HTML (etiqueta estàndard `<?php ?>` i etiqueta curta `<?= ?>`).

Tasques a fer dins del fitxer:

1. Al bloc `<?php ?>` del principi (marcat amb `// TODO 1`), declara tres variables:
   - `$nomEmpresa` amb el valor `"TechLeads"`
   - `$eslogan` amb el valor `"Convertim leads en clients"`
   - `$anyFundacio` amb el valor `2020`
2. Al `<h1>` (marcat amb `TODO 2`), imprimeix `$nomEmpresa` amb `<?= ?>`.
3. Al primer `<p>` (marcat amb `TODO 3`), imprimeix `$eslogan` amb `<?= ?>`.
4. Al segon `<p>` (marcat amb `TODO 4`), completa la frase *"Fundada l'any ..."* imprimint `$anyFundacio` amb `<?= ?>`.

**Pista:** No cal fer servir `echo` en cap moment; utilitza únicament `<?= $variable ?>`.


### 7.2. Fonaments del llenguatge PHP

#### Exercici 2.1 — Fitxa d'un nou lead

**Fitxer de partida:** `exercici2.1.php`

::: details 📄 exercici2.1.php
```php
<?php
    // TODO 1: Declara $nom (string), $pressupost (float), $contactat (boolean) i $notes (null)


    // TODO 3: Crea $fraseSimple amb cometes simples, que continga literalment "Lead: $nom"


    // TODO 4: Crea $fraseInterpolada amb cometes dobles i claus {},
    // amb el text "El pressupost del lead és de {$pressupost} €"

?>
<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exercici 2.1 - Fitxa d'un nou lead</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen p-8">

    <h1 class="text-2xl font-bold text-gray-800 mb-6">Fitxa del lead</h1>

    <div class="bg-white p-6 rounded-lg shadow-md max-w-xl mb-6">
        <p class="text-gray-700 mb-2"><span class="font-semibold">Cometes simples:</span> <?= /* TODO 5: $fraseSimple */ ?></p>
        <p class="text-gray-700"><span class="font-semibold">Cometes dobles (interpolat):</span> <?= /* TODO 6: $fraseInterpolada */ ?></p>
    </div>

    <h2 class="text-xl font-semibold text-gray-800 mb-2">Depuració de variables</h2>
    <pre class="bg-gray-900 text-green-400 p-4 rounded-lg overflow-x-auto">
<?php
    // TODO 2: Crida var_dump() per a $nom, $pressupost, $contactat i $notes
?>
    </pre>

</body>
</html>
```
:::

**Objectiu:** Practicar els tipus de dades primitius, la depuració amb `var_dump()`, i la diferència entre cometes simples i cometes dobles (amb interpolació avançada `{}`).

Tasques a fer dins del fitxer:

1. Al bloc PHP inicial (`TODO 1`), declara quatre variables per a representar un nou lead:
   - `$nom` (string) amb el valor `"Marc Soler"`
   - `$pressupost` (float) amb el valor `2750.50`
   - `$contactat` (boolean) amb el valor `false`
   - `$notes` (null) — encara no té notes, assigna-li `null`
2. A la zona de depuració (`TODO 2`), dins de les etiquetes `<pre>` ja preparades, crida `var_dump()` per a cadascuna de les quatre variables, perquè es puga veure el seu tipus i valor.
3. A `TODO 3`, crea una variable `$fraseSimple` utilitzant **cometes simples**, que continga literalment el text `Lead: $nom` (sense que PHP interprete la variable — ha d'eixir `$nom` tal qual, no el nom "Marc Soler").
4. A `TODO 4`, crea una variable `$fraseInterpolada` utilitzant **cometes dobles i claus `{}`**, que continga: *"El pressupost del lead és de 2750.5 €"* (utilitzant `{$pressupost}` per a evitar ambigüitats amb el text que ve després).
5. A `TODO 5` i `TODO 6`, imprimeix `$fraseSimple` i `$fraseInterpolada` amb `<?= ?>` per a poder comparar el resultat de totes dues.

**Pista:** Recorda que `var_dump()` mostra el tipus de dada, a diferència de `echo`, que només mostra el valor.



#### Exercici 2.2 — Àmbit de variables: comissió del comercial

**Fitxer de partida:** `exercici2.php`

::: details 📄 exercici2.2.php
```php
<?php
    // TODO 1: Declara la variable global $percentatgeComissio amb el valor 10

    function calcularComissio($pressupostVenda) {
        // TODO 2: Fes accessible ací dins la variable global $percentatgeComissio

        // TODO 3: Calcula la comissió ($pressupostVenda * $percentatgeComissio / 100)
        // i retorna-la amb return

    }

    // TODO 4: Declara $vendaMarc = 4000 i crida calcularComissio($vendaMarc)
    // guardant el resultat en $comissioMarc

?>
<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exercici 2.2 - Comissió del comercial</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">

    <div class="bg-white p-8 rounded-lg shadow-md max-w-md w-full text-center">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Comissió de Marc</h2>
        <p class="text-gray-600">Venda: 4000 €</p>
        <p class="text-3xl font-bold text-green-600 mt-4">
            <?= /* TODO 5: $comissioMarc */ ?> €
        </p>
    </div>

</body>
</html>
```
:::

**Objectiu:** Comprendre la diferència entre l'àmbit global i l'àmbit local d'una funció, i com accedir a una variable global des de dins d'una funció.

Tasques a fer dins del fitxer:

1. A `TODO 1` (fora de qualsevol funció), declara la variable global `$percentatgeComissio` amb el valor `10`.
2. Dins de la funció `calcularComissio()` ja creada (`TODO 2`), utilitza la paraula clau `global` per a poder accedir des de dins a la variable `$percentatgeComissio`.
3. Encara dins de la funció (`TODO 3`), calcula la comissió a partir del paràmetre `$pressupostVenda` que rep la funció i la variable global, i **retorna** el resultat amb `return` (la fórmula és: `$pressupostVenda * $percentatgeComissio / 100`).
4. A `TODO 4`, fora de la funció, declara `$vendaMarc` amb el valor `4000` i crida `calcularComissio($vendaMarc)`, guardant el resultat en una variable `$comissioMarc`.
5. A `TODO 5`, imprimeix `$comissioMarc` dins de la targeta ja preparada.

**Pista:** Si no uses `global` (o `$GLOBALS`) dins de la funció, `$percentatgeComissio` no existirà en l'àmbit local i donarà error o `null`.



#### Exercici 2.3 — Pressupost amb IVA i constants

**Fitxer de partida:** `exercici2.3.php`

::: details 📄 exercici2.3.php
```php
<?php
    // TODO 1: Defineix la constant NOM_EMPRESA = "TechLeads" amb define()

    // TODO 2: Defineix la constant IVA_PERCENT = 21 amb const

    // TODO 3: Declara $preuBase = 1500

    // TODO 6: Afig ací un comentari d'una línia explicant el càlcul següent
    // TODO 4: Calcula $preuIva ($preuBase * IVA_PERCENT / 100)
    // TODO 5: Calcula $preuFinal ($preuBase + $preuIva)

?>
<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exercici 2.3 - Pressupost amb IVA</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">

    <div class="bg-white p-8 rounded-lg shadow-md max-w-md w-full">
        <h2 class="text-2xl font-bold text-gray-800 mb-4"><?= /* TODO 7: NOM_EMPRESA */ ?></h2>
        <p class="text-gray-600">Preu base: <?= /* TODO 8: $preuBase */ ?> €</p>
        <p class="text-gray-600">IVA: <?= IVA_PERCENT ?> %</p>
        <p class="text-2xl font-bold text-blue-700 mt-4">
            Total: <?= /* TODO 9: $preuFinal */ ?> €
        </p>
    </div>

</body>
</html>
```
:::

**Objectiu:** Practicar l'ús d'operadors aritmètics, la definició de constants (amb `define()` i amb `const`) i afegir comentaris seguint les bones pràctiques.

Tasques a fer dins del fitxer:

1. A `TODO 1`, defineix la constant `NOM_EMPRESA` amb el valor `"TechLeads"` utilitzant `define()`.
2. A `TODO 2`, defineix la constant `IVA_PERCENT` amb el valor `21` utilitzant la paraula clau `const`.
3. A `TODO 3`, declara la variable `$preuBase` amb el valor `1500`.
4. A `TODO 4`, calcula `$preuIva` (l'import corresponent a l'IVA) utilitzant l'operador `*` i la constant `IVA_PERCENT` (fórmula: `$preuBase * IVA_PERCENT / 100`).
5. A `TODO 5`, calcula `$preuFinal` sumant `$preuBase` i `$preuIva` amb l'operador `+`.
6. A `TODO 6`, per damunt del càlcul, afig un **comentari d'una sola línia** explicant breument què fa eixe bloc de codi (per exemple, què calcula `$preuFinal`).
7. A `TODO 7`, `TODO 8` i `TODO 9`, imprimeix dins de la targeta ja preparada el nom de l'empresa (constant), el preu base i el preu final.

**Pista:** Les constants no porten el símbol `$` i, per conveni, s'escriuen en `UPPER_SNAKE_CASE`.


### 7.3. Estructures de control

#### Exercici 3.1 — Classificació de pressupostos

**Fitxer de partida:** `exercici3.1.php`

::: details 📄 exercici3.1.php
```php
<?php
    // TODO 1: Declara $pressupost = 2500


    // TODO 2: Amb if/elseif/else, assigna $categoria segons el pressupost
    // >= 5000 -> "Client Prioritari (Enterprise)"
    // >= 1500 -> "Client Estàndard"
    // altrament -> "Projecte Menor / A revisar"


    // TODO 3: Declara $estat = "nou"


    // TODO 4: Amb l'operador ternari, assigna $etiqueta:
    // "Pendent de revisar" si $estat === 'nou', altrament "Atés"

?>
<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exercici 3.1 - Classificació de pressupostos</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">

    <div class="bg-white p-8 rounded-lg shadow-md max-w-md w-full">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Fitxa del lead</h2>
        <p class="text-gray-600 mb-2">Categoria: <span class="font-semibold text-blue-700"><?= /* TODO 5: $categoria */ ?></span></p>
        <p class="text-gray-600">Estat de seguiment: <span class="font-semibold text-purple-700"><?= /* TODO 6: $etiqueta */ ?></span></p>
    </div>

</body>
</html>
```
:::

**Objectiu:** Practicar l'estructura `if` / `elseif` / `else` i l'operador ternari.

Tasques a fer dins del fitxer:

1. A `TODO 1`, declara `$pressupost` amb el valor `2500`.
2. A `TODO 2`, amb una estructura `if` / `elseif` / `else`, assigna a la variable `$categoria` un text segons aquestes regles:
   - Si `$pressupost >= 5000` → `"Client Prioritari (Enterprise)"`
   - Si `$pressupost >= 1500` → `"Client Estàndard"`
   - En qualsevol altre cas → `"Projecte Menor / A revisar"`
3. A `TODO 3`, declara `$estat` amb el valor `"nou"`.
4. A `TODO 4`, utilitza l'**operador ternari** per a assignar a `$etiqueta`:
   - `"Pendent de revisar"` si `$estat === 'nou'`
   - `"Atés"` en cas contrari
5. A `TODO 5` i `TODO 6`, imprimeix `$categoria` i `$etiqueta` dins de la targeta ja preparada.

**Pista:** Recorda utilitzar sempre l'operador d'igualtat estricta `===` per a comparar.



#### Exercici 3.2 — Estat del lead: `switch` vs `match`

**Fitxer de partida:** `exercici3.2.php`

::: details 📄 exercici3.2.php
```php
<?php
    // TODO 1: Declara $estatLead = "contactat"


    // TODO 2: Completa l'estructura switch perquè assigne $missatgeSwitch
    switch ($estatLead) {
        // Escriu ací els case necessaris, amb el seu break;
    }


    // TODO 3: Escriu una expressió match($estatLead) equivalent
    // i assigna el resultat a $missatgeMatch

?>
<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exercici 3.2 - Switch vs Match</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col items-center justify-center gap-4 p-8">

    <div class="bg-white p-6 rounded-lg shadow-md max-w-md w-full">
        <h3 class="text-lg font-semibold text-gray-800 mb-2">Resultat amb <code>switch</code></h3>
        <p class="text-gray-700"><?= /* TODO 4: $missatgeSwitch */ ?></p>
    </div>

    <div class="bg-white p-6 rounded-lg shadow-md max-w-md w-full">
        <h3 class="text-lg font-semibold text-gray-800 mb-2">Resultat amb <code>match</code></h3>
        <p class="text-gray-700"><?= /* TODO 5: $missatgeMatch */ ?></p>
    </div>

</body>
</html>
```
:::

**Objectiu:** Comparar dues formes de fer selecció múltiple: l'estructura clàssica `switch` i l'expressió moderna `match` (PHP 8+).

Tasques a fer dins del fitxer:

1. A `TODO 1`, declara `$estatLead` amb el valor `"contactat"`.
2. A `TODO 2`, completa l'estructura `switch($estatLead)` (ja iniciada) perquè assigne a `$missatgeSwitch` un text diferent segons el cas (no oblides el `break;` en cada `case`):
   - `'nou'` → `"Tens un nou lead pendent d'assignar."`
   - `'contactat'` → `"S'ha enviat un correu al client."`
   - `'convertit'` o `'guanyat'` (mateix missatge per a tots dos) → `"El projecte s'ha acceptat!"`
   - `default` → `"Estat no reconegut."`
3. A `TODO 3`, escriu una expressió `match($estatLead)` equivalent que assigne el mateix resultat a la variable `$missatgeMatch` (recorda: `match` no necessita `break` i retorna el valor directament).
4. A `TODO 4` i `TODO 5`, imprimeix `$missatgeSwitch` i `$missatgeMatch` en les dues targetes ja preparades, per a comprovar que donen el mateix resultat.

**Pista:** Amb `match`, cada línia té la forma `valor => resultat,` i els diversos casos que comparteixen resultat es separen amb comes (`'convertit', 'guanyat' => ...`).



#### Exercici 3.3 — Bucles: pàgines, intents i serveis

**Fitxer de partida:** `exercici3.3.php`

::: details 📄 exercici3.3.php
```php
<?php
    // TODO 7 (repte opcional): activa ací la visualització d'errors
    // amb ini_set('display_errors', '1') i error_reporting(E_ALL)


    // TODO 1: Declara $totalPagines = 5 i, amb un bucle for,
    // construeix $llistaPagines concatenant "<li>Pàgina X</li>" per cada pàgina
    $llistaPagines = '';


    // TODO 2: Declara $intents = 0 i $maxIntents = 3. Amb un bucle while,
    // construeix $llistaIntents concatenant "<li>Intent número: X</li>"
    // (no oblides incrementar $intents dins del bucle)
    $llistaIntents = '';


    // Array ja proporcionat, no cal que el toques
    $serveis = ["Web Corporativa", "Botiga Online", "App Mòbil"];

    // TODO 3: Amb un foreach, construeix $llistaServeis concatenant
    // "<li>Servei</li>" per cada servei de l'array $serveis
    $llistaServeis = '';

?>
<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exercici 3.3 - Bucles</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen p-8">

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-4xl mx-auto">

        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-lg font-semibold text-gray-800 mb-2">Pàgines (for)</h3>
            <ul class="list-disc list-inside text-gray-700">
                <?= /* TODO 4: $llistaPagines */ ?>
            </ul>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-lg font-semibold text-gray-800 mb-2">Intents (while)</h3>
            <ul class="list-disc list-inside text-gray-700">
                <?= /* TODO 5: $llistaIntents */ ?>
            </ul>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-lg font-semibold text-gray-800 mb-2">Serveis (foreach)</h3>
            <ul class="list-disc list-inside text-gray-700">
                <?= /* TODO 6: $llistaServeis */ ?>
            </ul>
        </div>

    </div>

</body>
</html>
```
:::

**Objectiu:** Practicar els tres tipus de bucle (`for`, `while` i `foreach`) construint llistes d'elements `<li>` mitjançant concatenació de cadenes.

Tasques a fer dins del fitxer:

1. **Bucle `for`** (`TODO 1`): declara `$totalPagines = 5` i, amb un bucle `for`, construeix la variable `$llistaPagines` concatenant (`.=`) un `<li>Pàgina X</li>` per cada pàgina, des de l'1 fins a `$totalPagines`.
2. **Bucle `while`** (`TODO 2`): declara `$intents = 0` i `$maxIntents = 3`. Amb un bucle `while`, construeix `$llistaIntents` concatenant un `<li>Intent número: X</li>` per cada volta, i recorda incrementar `$intents` dins del bucle perquè no siga infinit.
3. **Bucle `foreach`** (`TODO 3`): ja tens declarat l'array `$serveis` (no cal que el toques). Amb un `foreach`, construeix `$llistaServeis` concatenant un `<li>` per cada servei de l'array.
4. A `TODO 4`, `TODO 5` i `TODO 6`, imprimeix `$llistaPagines`, `$llistaIntents` i `$llistaServeis` dins de les etiquetes `<ul>` ja preparades.
5. **Repte opcional — Depuració (`TODO 7`):** al començament absolut del fitxer, activa la visualització d'errors afegint `ini_set('display_errors', '1')` i `error_reporting(E_ALL)`. Encara que en aquest exercici no hi haja errors, és una bona pràctica activar-ho sempre en entorn de desenvolupament.

**Pista:** Si el bucle `while` no acaba mai (es queda "penjat" carregant), és que has oblidat incrementar `$intents` dins del bucle.

### 7.4. Gestió de Dades amb Arrays

#### Exercici 4.1 — Tecnologies del stack de TechLeads

**Fitxer de partida:** `exercici4.1.php`

::: details 📄 exercici4.1.
```php
<?php
    // TODO 1: Declara $tecnologies com a array indexat amb "PHP", "MySQL", "TailwindCSS", "JavaScript"


    // TODO 2: Declara $primera accedint a la primera posició (índex 0)


    // TODO 3: Declara $ultima accedint a l'última posició, usant count($tecnologies) - 1


    // TODO 4: Afig "Docker" al final de l'array amb $tecnologies[] = ...


    // TODO 5: Amb foreach ($tecnologies as $index => $tecnologia), construeix $llistaTec
    // concatenant "<li>Llenguatge {index}: {tecnologia}</li>" per cada element
    $llistaTec = '';

?>
<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exercici 4.1 - Tecnologies del stack</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen p-8">

    <div class="max-w-2xl mx-auto">

        <div class="bg-white p-6 rounded-lg shadow-md mb-6">
            <p class="text-gray-700">Primera tecnologia: <span class="font-semibold"><?= /* TODO 6: $primera */ ?></span></p>
            <p class="text-gray-700">Última tecnologia (abans d'afegir Docker): <span class="font-semibold"><?= /* TODO 7: $ultima */ ?></span></p>
            <p class="text-gray-700">Total de tecnologies (després d'afegir Docker): <span class="font-semibold"><?= /* TODO 8: count($tecnologies) */ ?></span></p>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-lg font-semibold text-gray-800 mb-2">Stack complet</h3>
            <ul class="list-disc list-inside text-gray-700">
                <?= /* TODO 9: $llistaTec */ ?>
            </ul>
        </div>

    </div>

</body>
</html>
```
:::

**Objectiu:** Practicar la creació, modificació i recorregut d'un array indexat.

Tasques a fer dins del fitxer:

1. A `TODO 1`, declara `$tecnologies` com un array indexat (sintaxi de claudàtors `[]`) amb els valors: `"PHP"`, `"MySQL"`, `"TailwindCSS"`, `"JavaScript"`.
2. A `TODO 2`, declara `$primera` accedint a la primera posició de l'array (índex `0`).
3. A `TODO 3`, declara `$ultima` accedint a l'última posició de l'array, utilitzant `count($tecnologies) - 1` com a índex (no escrigues el número a mà).
4. A `TODO 4`, afig `"Docker"` al final de l'array utilitzant la sintaxi `$tecnologies[] = ...`.
5. A `TODO 5`, amb un bucle `foreach ($tecnologies as $index => $tecnologia)`, construeix la variable `$llistaTec` concatenant per cada element un `<li>Llenguatge {index}: {tecnologia}</li>`.
6. A `TODO 6`, `TODO 7`, `TODO 8` i `TODO 9`, imprimeix `$primera`, `$ultima`, el nombre total d'elements (amb `count()`) i `$llistaTec` als llocs indicats de la plantilla.

**Pista:** Recorda que els índexs d'un array comencen sempre en `0`, així que l'últim índex és `count($array) - 1`.



#### Exercici 4.2 — Fitxa d'un lead com a array associatiu

**Fitxer de partida:** `exercici4.2.php`

::: details 📄 exercici4.2.
```php
<?php
    // TODO 1: Declara l'array associatiu $lead amb 'id', 'nom', 'email', 'pressupost' i 'actiu'


    // TODO 2: Afig la clau 'telefon' => "600112233"


    // TODO 3: Modifica la clau 'actiu' i posa-la a false


    // TODO 4: Comprova amb isset($lead['nom']) si la clau existeix.
    // Si existeix, $nomMostrat = $lead['nom']; si no, $nomMostrat = "Sense nom"


    // TODO 5: Amb foreach ($lead as $clau => $valor), construeix $llistaLead
    // concatenant "<li><strong>{clau}:</strong> {valor}</li>" per cada parella
    $llistaLead = '';

?>
<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exercici 4.2 - Fitxa d'un lead</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">

    <div class="bg-white p-8 rounded-lg shadow-md max-w-md w-full">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Lead: <?= /* TODO 6: $nomMostrat */ ?></h2>
        <ul class="text-gray-700 space-y-1">
            <?= /* TODO 7: $llistaLead */ ?>
        </ul>
    </div>

</body>
</html>
```
:::


**Objectiu:** Practicar la creació, modificació, comprovació i recorregut d'un array associatiu.

Tasques a fer dins del fitxer:

1. A `TODO 1`, declara l'array associatiu `$lead` amb les claus: `'id' => 101`, `'nom' => "Aina Martínez"`, `'email' => "aina@empresa.valencia"`, `'pressupost' => 3500.00`, `'actiu' => true`.
2. A `TODO 2`, afig una nova clau `'telefon'` amb el valor `"600112233"`.
3. A `TODO 3`, modifica el valor de la clau `'actiu'` i posa'l a `false` (simulem que el lead ja no està actiu).
4. A `TODO 4`, abans d'imprimir res, comprova amb `isset($lead['nom'])` que la clau `'nom'` existeix, i només si existeix, declara `$nomMostrat = $lead['nom']`; si no existira, `$nomMostrat` hauria de valdre `"Sense nom"`.
5. A `TODO 5`, amb un bucle `foreach ($lead as $clau => $valor)`, construeix la variable `$llistaLead` concatenant per cada parella un `<li><strong>{clau}:</strong> {valor}</li>`.
6. A `TODO 6` i `TODO 7`, imprimeix `$nomMostrat` i `$llistaLead` als llocs indicats.

**Pista:** Pots resoldre el `TODO 4` amb un `if`/`else`, o bé amb l'operador ternari: `isset($lead['nom']) ? $lead['nom'] : "Sense nom"`.



#### Exercici 4.3 — Llistat de leads i filtratge

**Fitxer de partida:** `exercici4.3.php`

::: details 📄 exercici4.3.
```php
<?php
    // Array ja proporcionat, no cal que el toques
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

    // TODO 1: Amb foreach ($llistatLeads as $lead), construeix $filesTaula concatenant
    // "<tr><td>id</td><td>nom</td><td>empresa</td><td>pressupost €</td></tr>" per cada lead
    $filesTaula = '';


    // TODO 2: Calcula $totalLeads amb count($llistatLeads)


    // TODO 3: Amb array_filter(), obtín en $leadsEnterprise els leads amb pressupost >= 1500


    // TODO 4: Calcula $totalEnterprise amb count($leadsEnterprise)

?>
<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exercici 4.3 - Llistat de leads</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen p-8">

    <div class="max-w-3xl mx-auto">

        <h1 class="text-2xl font-bold text-gray-800 mb-4">Llistat de Leads</h1>

        <table class="w-full bg-white rounded-lg shadow-md overflow-hidden mb-6">
            <thead class="bg-gray-800 text-white">
                <tr>
                    <th class="p-2 text-left">ID</th>
                    <th class="p-2 text-left">Nom</th>
                    <th class="p-2 text-left">Empresa</th>
                    <th class="p-2 text-left">Pressupost</th>
                </tr>
            </thead>
            <tbody>
                <?= /* TODO 5: $filesTaula */ ?>
            </tbody>
        </table>

        <div class="bg-white p-6 rounded-lg shadow-md flex justify-around text-center">
            <div>
                <p class="text-gray-500 text-sm">Total de leads</p>
                <p class="text-2xl font-bold text-blue-700"><?= /* TODO 6: $totalLeads */ ?></p>
            </div>
            <div>
                <p class="text-gray-500 text-sm">Leads Enterprise (&ge; 1500 €)</p>
                <p class="text-2xl font-bold text-green-700"><?= /* TODO 7: $totalEnterprise */ ?></p>
            </div>
        </div>

    </div>

</body>
</html>
```
:::

**Objectiu:** Practicar arrays multidimensionals (array de leads, cadascun com a array associatiu) i l'ús de funcions predefinides (`count()`, `array_filter()`).

Tasques a fer dins del fitxer:

1. Ja tens declarat (no cal que el toques) l'array multidimensional `$llistatLeads`, amb 3 leads (cadascun amb `id`, `nom`, `empresa`, `pressupost` i `estat`).
2. A `TODO 1`, amb un bucle `foreach ($llistatLeads as $lead)`, construeix la variable `$filesTaula` concatenant, per cada lead, una fila de taula HTML: `<tr><td>{id}</td><td>{nom}</td><td>{empresa}</td><td>{pressupost} €</td></tr>`.
3. A `TODO 2`, calcula `$totalLeads` amb la funció `count()` sobre `$llistatLeads`.
4. A `TODO 3`, utilitza `array_filter()` per a obtindre en `$leadsEnterprise` només els leads amb `pressupost >= 1500` (utilitza una funció anònima com a segon paràmetre, tal com hem vist als apunts).
5. A `TODO 4`, calcula `$totalEnterprise` amb `count()` sobre `$leadsEnterprise`.
6. A `TODO 5`, `TODO 6` i `TODO 7`, imprimeix `$filesTaula`, `$totalLeads` i `$totalEnterprise` als llocs indicats de la plantilla.

**Pista:** `array_filter()` no reindexa l'array resultant, així que si vols que `count()` funcione correctament no cal fer res especial: `count()` compta els elements encara que els índexs no siguen consecutius.


### 7.5. Modularització i Funcions

#### Exercici 5.1 — Funcions i pas de paràmetres

**Fitxer de partida:** `exercici5.1.php`

::: details 📄 exercici5.1.php
```php
<?php
    function calcularIVA(float $import, float $tipus = 21.0): float {
        // TODO 1: Calcula i retorna el preu final amb IVA
        // Fórmula: $import + ($import * $tipus / 100)
    }

    function aplicarDescompte(float &$preu, float $percentatge) {
        // TODO 2: Modifica directament $preu restant-li el percentatge indicat
        // Fórmula: $preu -= $preu * $percentatge / 100
        // Fixa't que $preu es passa per referència (&): no cal fer return
    }

    // TODO 3: Declara $preuBase = 1000 i crida calcularIVA($preuBase)
    // guardant el resultat en $preuAmbIvaDefecte


    // TODO 4: Crida calcularIVA($preuBase, 10.0)
    // guardant el resultat en $preuAmbIvaReduit


    // TODO 5: Declara $pressupostFinal = 2000.0 i crida
    // aplicarDescompte($pressupostFinal, 15)

?>
<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exercici 5.1 - Funcions i pas de paràmetres</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">

    <div class="bg-white p-8 rounded-lg shadow-md max-w-md w-full space-y-3">
        <p class="text-gray-700">Preu amb IVA (21% per defecte): <span class="font-semibold"><?= /* TODO 6: $preuAmbIvaDefecte */ ?> €</span></p>
        <p class="text-gray-700">Preu amb IVA reduït (10%): <span class="font-semibold"><?= /* TODO 7: $preuAmbIvaReduit */ ?> €</span></p>
        <p class="text-gray-700">Pressupost amb 15% de descompte: <span class="font-semibold"><?= /* TODO 8: $pressupostFinal */ ?> €</span></p>
    </div>

</body>
</html>


``` 
:::

**Objectiu:** Practicar la creació de funcions, els paràmetres amb valor per defecte, el `return`, i la diferència entre pas per valor i pas per referència (`&`).

Tasques a fer dins del fitxer:

1. A `TODO 1`, completa la funció `calcularIVA(float $import, float $tipus = 21.0): float`, que calcule i **retorne** (`return`) el preu final amb IVA (fórmula: `$import + ($import * $tipus / 100)`).
2. A `TODO 2`, completa la funció `aplicarDescompte(float &$preu, float $percentatge)`. Fixa't que `$preu` es passa **per referència** (amb `&`): dins de la funció has de **modificar directament** la variable `$preu` (no cal `return`), restant-li el percentatge indicat (`$preu -= $preu * $percentatge / 100`).
3. A `TODO 3`, declara `$preuBase = 1000` i crida `calcularIVA($preuBase)` (sense segon paràmetre, per a utilitzar el 21% per defecte), guardant el resultat en `$preuAmbIvaDefecte`.
4. A `TODO 4`, crida `calcularIVA($preuBase, 10.0)` (amb IVA reduït del 10%), guardant el resultat en `$preuAmbIvaReduit`.
5. A `TODO 5`, declara `$pressupostFinal = 2000.0` i crida `aplicarDescompte($pressupostFinal, 15)`. **No cal** guardar cap valor de retorn: com que el paràmetre és per referència, `$pressupostFinal` quedarà modificada automàticament.
6. A `TODO 6`, `TODO 7` i `TODO 8`, imprimeix `$preuAmbIvaDefecte`, `$preuAmbIvaReduit` i `$pressupostFinal` (ja amb el descompte aplicat) als llocs indicats.

**Pista:** Si t'oblides del `&` a la definició de `aplicarDescompte`, la variable original `$pressupostFinal` no canviarà mai, per molt que la modifiques dins de la funció.



#### Exercici 5.2 — Mode estricte i declaracions de tipus

**Fitxer de partida:** `exercici5.2.php`

::: details 📄 exercici5.2.php
```php

<?php
    // TODO 1: Activa el mode estricte (ha de ser la primera línia absoluta)

    function formatarPressupost(float $quantitat, string $moneda = '€'): string {
        // TODO 2: Retorna number_format($quantitat, 2, ',', '.') seguit d'un espai i la moneda
    }

    function buscarLeadPerId(array $llistat, int $id): ?array {
        // TODO 3: Recorre $llistat amb foreach i retorna l'array del lead
        // el 'id' del qual coincidisca amb $id. Si no el troba, retorna null.
    }

    // Array ja proporcionat, no cal que el toques
    $llistatLeads = [
        ['id' => 1, 'nom' => 'Joan Garcia', 'pressupost' => 4500.00],
        ['id' => 2, 'nom' => 'Laura Sanchis', 'pressupost' => 12000.00],
        ['id' => 3, 'nom' => 'Marc Climent', 'pressupost' => 800.00],
    ];

    // TODO 4: Declara $preuBase = 1500.0 i crida formatarPressupost($preuBase)
    // guardant el resultat en $preuFormatat


    // TODO 5: Crida buscarLeadPerId($llistatLeads, 2)
    // guardant el resultat en $leadTrobat

?>
<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exercici 5.2 - Mode estricte i tipus</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">

    <div class="bg-white p-8 rounded-lg shadow-md max-w-md w-full space-y-3">
        <p class="text-gray-700">Pressupost formatat: <span class="font-semibold"><?= /* TODO 6: $preuFormatat */ ?></span></p>
        <p class="text-gray-700">Lead trobat (ID 2): <span class="font-semibold"><?= /* TODO 7: nom del $leadTrobat o "No trobat" */ ?></span></p>
    </div>

</body>
</html>

``` 
:::

**Objectiu:** Activar `strict_types`, i practicar funcions amb tipus de retorn *nullable* (`?array`) cercant dins d'un array.

Tasques a fer dins del fitxer:

1. A `TODO 1` (que ha de ser la **primera línia absoluta** del fitxer, just després de `<?php`), activa el mode estricte amb `declare(strict_types=1);`.
2. A `TODO 2`, completa la funció `formatarPressupost(float $quantitat, string $moneda = '€'): string`, que retorne la quantitat formatada amb `number_format($quantitat, 2, ',', '.')` seguida d'un espai i la moneda.
3. A `TODO 3`, completa la funció `buscarLeadPerId(array $llistat, int $id): ?array`, que recórrega l'array `$llistat` amb un `foreach` i **retorne** l'array del lead el `id` del qual coincidisca amb el paràmetre `$id`; si no el troba després de recórrer tot l'array, ha de **retornar `null`**.
4. Ja tens declarat (no cal que el toques) l'array `$llistatLeads` amb 3 leads.
5. A `TODO 4`, declara `$preuBase = 1500.0` i crida `formatarPressupost($preuBase)`, guardant el resultat en `$preuFormatat`.
6. A `TODO 5`, crida `buscarLeadPerId($llistatLeads, 2)`, guardant el resultat en `$leadTrobat`.
7. A `TODO 6`, imprimeix `$preuFormatat`.
8. A `TODO 7`, mostra el nom del lead trobat si `$leadTrobat` no és `null` (per exemple amb `$leadTrobat['nom'] ?? "No trobat"`).

**Pista:** Amb `strict_types` activat, si crides `formatarPressupost("1500")` (una cadena en lloc d'un `float`) obtindràs un error `TypeError`, encara que abans (sense mode estricte) PHP ho hauria convertit automàticament.



#### Exercici 5.3 — Inclusió de fitxers i separació de vistes/lògica

**Fitxers de partida:** `helpers.php` i `exercici5.3.php` (`exercici5.3.php` és el fitxer principal; `helpers.php` és el fitxer que s'hi inclourà)

::: details 📄 exercici5.3.php
```php

<?php
    // TODO 2: Inclou helpers.php amb require_once __DIR__ . '/helpers.php'


    // Array ja proporcionat, no cal que el toques
    $llistatLeads = [
        ['id' => 1, 'nom' => 'Joan Garcia', 'estat' => 'nou'],
        ['id' => 2, 'nom' => 'Laura Sanchis', 'estat' => 'convertit'],
        ['id' => 3, 'nom' => 'Marc Climent', 'estat' => 'nou'],
        ['id' => 4, 'nom' => 'Aina Martínez', 'estat' => 'descartat'],
    ];

    // TODO 3: Crida comptarLeadsNous($llistatLeads)
    // guardant el resultat en $totalNous


    // TODO 4: Declara $hasNotificacions com a booleà (true si $totalNous > 0)

?>
<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exercici 5.3 - Inclusió i separació de vistes</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">

    <div class="bg-white p-8 rounded-lg shadow-md max-w-md w-full">

        <!-- VISTA: no cal tocar res d'ací en avall -->
        <?php if ($hasNotificacions): ?>
            <div class="bg-blue-100 text-blue-800 p-4 rounded mb-4">
                Tens <?= $totalNous ?> lead(s) nou(s) pendent(s) d'assignar.
            </div>
            <ul class="list-disc list-inside text-gray-700">
                <?php foreach ($llistatLeads as $lead): ?>
                    <?php if ($lead['estat'] === 'nou'): ?>
                        <li><?= htmlspecialchars($lead['nom']) ?></li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p class="text-gray-600">No tens notificacions pendents.</p>
        <?php endif; ?>

    </div>

</body>
</html>

``` 
:::

::: details 📄 helpers.php
```php

<?php

function comptarLeadsNous(array $llistat): int {
    // TODO 1: Recorre $llistat amb foreach i compta quants leads
    // tenen $lead['estat'] === 'nou'. Retorna eixe nombre.
}

``` 
:::

**Objectiu:** Practicar `require_once` per a modularitzar el codi, i entendre el patró de separació entre la **lògica** (part superior del fitxer) i la **vista** (part inferior, amb sintaxi alternativa `if:`/`endif;` i `foreach:`/`endforeach;`, ja escrita i que no cal tocar).

Tasques a fer:

#### Dins de `helpers.php`

1. A `TODO 1`, completa la funció `comptarLeadsNous(array $llistat): int`, que recórrega `$llistat` amb un `foreach` i **compte** quants leads tenen `$lead['estat'] === 'nou'`, retornant eixe nombre.

#### Dins de `exercici5.3.php`

2. A `TODO 2`, inclou el fitxer `helpers.php` utilitzant `require_once __DIR__ . '/helpers.php'` (recorda: `require_once` perquè és una llibreria de funcions imprescindible).
3. Ja tens declarat (no cal que el toques) l'array `$llistatLeads`.
4. A `TODO 3`, crida `comptarLeadsNous($llistatLeads)`, guardant el resultat en `$totalNous`.
5. A `TODO 4`, declara `$hasNotificacions` com un booleà que siga `true` si `$totalNous > 0`.

La **vista** (la part HTML de davall) ja està completa: si `$hasNotificacions` és `true`, mostrarà un avís amb el nombre de leads nous; si és `false`, mostrarà el missatge "No tens notificacions pendents."

**Pista:** Si obres `exercici5.3.php` directament sense haver completat el `require_once`, PHP donarà un error perquè la funció `comptarLeadsNous()` no existirà encara en eixe fitxer.



### 7.6. Formularis

#### Exercici 6.1 — Cercador de leads (mètode GET)

**Fitxer de partida:** `exercici6.1.php`
::: details  📄 exercici 6.1.php
```php
<?php
    // TODO 1: Captura $_GET['paraula'] amb ?? (per defecte '') i guarda-la en $paraulaCercada


    // TODO 2: Captura $_GET['pagina'] amb ?? (per defecte 1), converteix-la a (int)
    // i guarda-la en $paginaActual

?>
<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exercici 6.1 - Cercador de leads (GET)</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">

    <div class="bg-white p-8 rounded-lg shadow-md max-w-md w-full">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Cercador de leads</h2>

        <form method="GET" action="" class="flex gap-2 mb-4">
            <input type="text" name="paraula" placeholder="Cerca un lead..."
                   class="flex-1 border border-gray-300 rounded px-3 py-2">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded">
                Cercar
            </button>
        </form>

        <!-- VISTA: no cal tocar res d'ací en avall -->
        <?php if (!empty($paraulaCercada)): ?>
            <p class="text-gray-700">
                Has cercat: <strong><?= htmlspecialchars($paraulaCercada) ?></strong>
                (pàgina <?= $paginaActual ?>)
            </p>
        <?php endif; ?>

    </div>

</body>
</html>
```
:::

**Objectiu:** Practicar la recuperació de dades enviades per **GET** amb la superglobal `$_GET`, i el cast de tipus.

Tasques a fer dins del fitxer:

1. A `TODO 1`, captura el valor enviat pel camp `paraula` del formulari, utilitzant l'operador `??` amb `''` com a valor per defecte, i guarda'l en `$paraulaCercada`.
2. A `TODO 2`, captura el valor de `pagina`, també amb `??` (per defecte `1`), i **converteix-lo a enter** utilitzant `(int)`, guardant el resultat en `$paginaActual`.
3. La vista ja mostra, si `$paraulaCercada` no està buida, un missatge amb la paraula cercada i la pàgina actual — no cal que la toques.

**Pista:** Fixa't com, en enviar el formulari, les dades apareixen a la barra d'adreces del navegador (`?paraula=...&pagina=...`), a diferència del que passarà amb POST als següents exercicis.



#### Exercici 6.2 — Formulari de contacte (mètode POST)

**Fitxer de partida:** `exercici6.2.php`
::: details  📄 exercici 6.2.php
```php
<?php
    // TODO 1: Declara $nom = '' i $missatge = ''


    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // TODO 2: Captura $_POST['nom'] amb ?? (per defecte '') i guarda-la en $nom


        // TODO 3: Captura $_POST['missatge'] amb ?? (per defecte '') i guarda-la en $missatge

    }
?>
<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exercici 6.2 - Formulari de contacte (POST)</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">

    <div class="bg-white p-8 rounded-lg shadow-md max-w-md w-full">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Contacta amb TechLeads</h2>

        <form method="POST" action="" class="space-y-3 mb-4">
            <input type="text" name="nom" placeholder="El teu nom"
                   class="w-full border border-gray-300 rounded px-3 py-2">
            <textarea name="missatge" placeholder="El teu missatge" rows="3"
                      class="w-full border border-gray-300 rounded px-3 py-2"></textarea>
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded">
                Enviar
            </button>
        </form>

        <!-- VISTA: no cal tocar res d'ací en avall -->
        <?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
            <div class="bg-green-100 text-green-800 p-4 rounded">
                Gràcies, <strong><?= htmlspecialchars($nom) ?></strong>! Hem rebut el teu missatge:
                "<?= htmlspecialchars($missatge) ?>"
            </div>
        <?php endif; ?>

    </div>

</body>
</html>
```
:::

**Objectiu:** Practicar la recuperació de dades enviades per **POST** amb la superglobal `$_POST`, i la detecció de l'enviament del formulari amb `$_SERVER['REQUEST_METHOD']`.

Tasques a fer dins del fitxer:

1. A `TODO 1`, declara `$nom = ''` i `$missatge = ''` (valors per defecte, per si el formulari encara no s'ha enviat).
2. A `TODO 2`, dins del `if ($_SERVER['REQUEST_METHOD'] === 'POST')` ja iniciat, captura `$_POST['nom']` (amb `??` i per defecte `''`) i guarda'l en `$nom`.
3. A `TODO 3`, fes el mateix amb `$_POST['missatge']`, guardant-lo en `$missatge`.
4. La vista ja mostra, si el formulari s'ha enviat per POST, un missatge de confirmació amb el nom i el missatge rebuts — no cal que la toques.

**Pista:** Amb POST, encara que envies el formulari diverses vegades, mai veuràs les dades a la barra d'adreces del navegador.



#### Exercici 6.3 — Validació d'un formulari de contacte

**Fitxer de partida:** `exercici6.3.php`
::: details  📄 exercici 6.3.php
```php
<?php
    // TODO 1: Declara $errors = [], $nom = '' i $email = ''


    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        // TODO 2: Captura i neteja $_POST['nom'] amb trim() (i ?? per defecte ''), guarda-la en $nom


        // TODO 3: Captura i neteja $_POST['email'] amb trim() (i ?? per defecte ''), guarda-la en $email


        // TODO 4: Valida $nom:
        // si empty($nom) -> $errors['nom'] = "El camp nom és obligatori.";
        // si no, si mb_strlen($nom) < 2 -> $errors['nom'] = "El nom ha de tindre almenys 2 caràcters."


        // TODO 5: Valida $email:
        // si empty($email) -> $errors['email'] = "El correu electrònic és obligatori.";
        // si no, si !filter_var($email, FILTER_VALIDATE_EMAIL) -> $errors['email'] = "El format del correu no és vàlid."

    }
?>
<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exercici 6.3 - Validació de formulari</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">

    <div class="bg-white p-8 rounded-lg shadow-md max-w-md w-full">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Formulari de contacte</h2>

        <!-- VISTA: no cal tocar res d'ací en avall -->
        <?php if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($errors)): ?>
            <div class="bg-green-100 text-green-800 p-4 rounded mb-4">
                Formulari enviat correctament! Gràcies, <?= htmlspecialchars($nom) ?>.
            </div>
        <?php endif; ?>

        <form method="POST" action="" class="space-y-3">
            <div>
                <input type="text" name="nom" value="<?= htmlspecialchars($nom) ?>" placeholder="El teu nom"
                       class="w-full border border-gray-300 rounded px-3 py-2">
                <?php if (isset($errors['nom'])): ?>
                    <p class="text-red-600 text-sm mt-1"><?= $errors['nom'] ?></p>
                <?php endif; ?>
            </div>

            <div>
                <input type="text" name="email" value="<?= htmlspecialchars($email) ?>" placeholder="El teu correu"
                       class="w-full border border-gray-300 rounded px-3 py-2">
                <?php if (isset($errors['email'])): ?>
                    <p class="text-red-600 text-sm mt-1"><?= $errors['email'] ?></p>
                <?php endif; ?>
            </div>

            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded">
                Enviar
            </button>
        </form>

    </div>

</body>
</html>
```
:::

**Objectiu:** Aplicar validació bàsica al servidor: comprovar camps buits, netejar espais amb `trim()`, i validar el format d'un correu electrònic amb `filter_var()`.

Tasques a fer dins del fitxer:

1. A `TODO 1`, declara `$errors = []`, `$nom = ''` i `$email = ''`.
2. Dins del `if ($_SERVER['REQUEST_METHOD'] === 'POST')` ja iniciat:
   - A `TODO 2`, captura i neteja `$_POST['nom']` amb `trim()` (i `??` per defecte `''`), guardant-lo en `$nom`.
   - A `TODO 3`, fes el mateix amb `$_POST['email']`, guardant-lo en `$email`.
   - A `TODO 4`, valida `$nom`: si `empty($nom)`, assigna `$errors['nom'] = "El camp nom és obligatori."`; si no, si `mb_strlen($nom) < 2`, assigna `$errors['nom'] = "El nom ha de tindre almenys 2 caràcters."`.
   - A `TODO 5`, valida `$email`: si `empty($email)`, assigna `$errors['email'] = "El correu electrònic és obligatori."`; si no, si `!filter_var($email, FILTER_VALIDATE_EMAIL)`, assigna `$errors['email'] = "El format del correu no és vàlid."`.
3. La vista ja fa la resta: torna a omplir els camps amb els valors enviats, mostra els missatges d'error davall de cada camp si n'hi ha, i mostra un missatge d'èxit si el formulari s'ha enviat i `$errors` està buit — no cal que la toques.

**Pista:** Prova d'enviar el formulari buit, després amb un nom d'una sola lletra, i finalment amb un correu sense `@`, per a comprovar que cada validació funciona per separat.