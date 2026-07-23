# Unitat 1. Introducció a la part del servidor de les aplicacions web

## Resultats d'aprenentatge i criteris d'avaluació

**RA1. Selecciona les arquitectures i tecnologies de programació Web en entorn servidor, analitzant les seues capacitats i característiques pròpies.**

* **Criteris d'avaluació:**
    * **a)** S'han caracteritzat i diferenciat els models d'execució de codi en el servidor i en el client Web.
    * **b)** S'han reconegut els avantatges que proporciona la generació dinàmica de pàgines Web i les seues diferències amb la inclusió de sentències de guions a l'interior de les pàgines Web.
    * **c)** S'han identificat els mecanismes d'execució de codi en els servidors Web.
    * **d)** S'han reconegut les funcionalitats que aporten els servidors d'aplicacions i la seua integració amb els servidors Web.
    * **e)** S'han identificat i caracteritzat els principals llenguatges i tecnologies relacionats amb la programació Web en entorn servidor.
    * **f)** S'han verificat els mecanismes d'integració dels llenguatges de marques amb els llenguatges de programació en entorn servidor.
    * **g)** S'han reconegut i avaluat les eines de programació en entorn servidor.

---

## Temporalització

* **Durada estimada:** 4 hores

---

## Índex

1. [Arquitectura d'una aplicació web](#1-arquitectura-duna-aplicacio-web)
    1.1. [Aplicacions d'escriptori vs Aplicacions web](#11-aplicacions-descriptori-vs-aplicacions-web)  
    1.2. [El model client/servidor](#12-el-model-clientservidor)
2. [El protocol HTTP](#2-el-protocol-http)
    2.1. [Funcionament i característiques](#21-funcionament-i-caracteristiques)  
    2.2. [Manteniment de l'estat: Galetes (Cookies) i Sessions](#22-manteniment-de-lestat-galetes-cookies-i-sessions)  
    2.3. [Anatomia d'una transacció HTTP](#23-anatomia-duna-transaccio-http)  
    2.4. [Format de les URL / URI](#24-format-de-les-url-uri)
3. [Servidor Web vs Servidor d'Aplicacions](#3-servidor-web-vs-servidor-daplicacions)
    3.1. [Diferències principals](#31-diferencies-principals)  
    3.2. [Exemples de servidors web (Apache, NGINX)](#32-exemples-de-servidors-web-apache-nginx)  
    3.3. [Exemples de servidors d'aplicacions (Tomcat, JBoss, IIS)](#33-exemples-de-servidors-daplicacions-tomcat-jboss-iis)
4. [Pàgines web estàtiques i dinàmiques](#4-pagines-web-estatiques-i-dinamiques)
    4.1. [Execució de codi en el client vs Servidor](#41-execucio-de-codi-en-el-client-vs-servidor)
5. [Model de desenvolupament en 3 capes](#5-model-de-desenvolupament-en-3-capes)
    5.1. [Capa de presentació, procés/negoci i dades](#51-capa-de-presentacio-procesnegoci-i-dades)  
    5.2. [Exemples de tecnologies per capa](#52-exemples-de-tecnologies-per-capa)
6. [Perfils professionals en el desenvolupament web](#6-perfils-professionals-en-el-desenvolupament-web)
    6.1. [Front-end, Back-end i Full Stack](#61-front-end-back-end-i-full-stack)
7. [Tipologia de les aplicacions web](#7-tipologia-de-les-aplicacions-web)
    7.1. [MPA, PEMPA i SPA](#71-mpa-pempa-i-spa)
8. [Llenguatges de programació en entorn servidor](#8-llenguatges-de-programacio-en-entorn-servidor)
    8.1. [PHP (LAMP)](#81-php-lamp)  
    8.2. [JSP / Servlets](#82-jsp-servlets)  
    8.3. [Python (Django)](#83-python-django)  
    8.4. [ASP.NET](#84-aspnet)
9. [Bibliografia i Recursos](#9-bibliografia-i-recursos)

# 1. Arquitectura d'una aplicació web

L'arquitectura d'una aplicació web defineix com es distribueixen les diferents responsabilitats i tasques entre els components del sistema. A diferència del software tradicional de sobretaula, les aplicacions web es basen en una estructura distribuïda que depén d'una xarxa (com Internet o una intranet) per a funcionar.

---

## 1.1. Aplicacions d'escriptori vs Aplicacions web

Per a comprendre la importància de la programació en entorn servidor, és fonamental comparar el model d'aplicació d'escriptori tradicional amb el model d'aplicació web:

| Característica                  | Aplicació d'escriptori                                       | Aplicació web                                                |
| :------------------------------ | :----------------------------------------------------------- | :----------------------------------------------------------- |
| **Instal·lació**                | Requereix instal·lació local en cada equip client.           | No requereix instal·lació local; s'executa a través del navegador web. |
| **Manteniment i Actualització** | Complex. Cal actualitzar cada equip individualment.          | Senzill. Es modifica el codi al servidor i tots els usuaris accedeixen directament a la nova versió. |
| **Execució de codi**            | Tota la lògica i el processament s'executen a la màquina local. | La lògica principal es processa al **servidor**, i el **client** mostra la interfície (HTML/CSS/JS). |
| **Dependència de la xarxa**     | Generalment no requereix connexió a Internet per a funcionar. | Depén d'una connexió a la xarxa per a comunicar-se amb el servidor. |
| **Portabilitat i Plataforma**   | Depén del sistema operatiu (Windows, macOS, Linux).          | Multiplataforma per naturalesa, només requereix un navegador web compatible. |
| **Consum de recursos locals**   | Utilitza la CPU, RAM i disc de la màquina local.             | El consum en el client és reduït; l'esforç de càlcul principal recau en el servidor. |

---

## 1.2. El model client/servidor

L'arquitectura fonamental sobre la qual es construeix la web és el **model client/servidor**. Aquest és un model de comunicació distribuït on les tasques es reparteixen entre els proveïdors de recursos o serveis (**servidors**) i els demandants d'aquests serveis (**clients**).# 1. Arquitectura d'una aplicació web

L'arquitectura d'una aplicació web defineix com es distribueixen les diferents responsabilitats i tasques entre els components del sistema. A diferència del software tradicional de sobretaula, les aplicacions web es basen en una estructura distribuïda que depén d'una xarxa (com Internet o una intranet) per a funcionar.

---

## 1.1. Aplicacions d'escriptori vs Aplicacions web

Per a comprendre la importància de la programació en entorn servidor, és fonamental comparar el model d'aplicació d'escriptori tradicional amb el model d'aplicació web:

| Característica | Aplicació d'escriptori | Aplicació web |
| :--- | :--- | :--- |
| **Instal·lació** | Requereix instal·lació local en cada equip client. | No requereix instal·lació local; s'executa a través del navegador web. |
| **Manteniment i Actualització** | Complex. Cal actualitzar cada equip individualment. | Senzill. Es modifica el codi al servidor i tots els usuaris accedeixen directament a la nova versió. |
| **Execució de codi** | Tota la lògica i el processament s'executen a la màquina local. | La lògica principal es processa al **servidor**, i el **client** mostra la interfície (HTML/CSS/JS). |
| **Dependència de la xarxa** | Generalment no requereix connexió a Internet per a funcionar. | Depén d'una connexió a la xarxa per a comunicar-se amb el servidor. |
| **Portabilitat i Plataforma** | Depén del sistema operatiu (Windows, macOS, Linux). | Multiplataforma per naturalesa, només requereix un navegador web compatible. |
| **Consum de recursos locals** | Utilitza la CPU, RAM i disc de la màquina local. | El consum en el client és reduït; l'esforç de càlcul principal recau en el servidor. |

---

## 1.2. El model client/servidor

L'arquitectura fonamental sobre la qual es construeix la web és el **model client/servidor**. Aquest és un model de comunicació distribuït on les tasques es reparteixen entre els proveïdors de recursos o serveis (**servidors**) i els demandants d'aquests serveis (**clients**).

```text
+-----------------+                      +-----------------+
|                 | ---- 1. Petició ---> |                 |
|     CLIENT      |     (Request)        |    SERVIDOR     |
| (Navegador Web) |                      |   (Web Server)  |
|                 | <--- 2. Resposta --- |                 |
+-----------------+     (Response)       +-----------------+
```
### El Client
* És l'entitat que **inicia la interacció** sol·licitant un recurs o servei.
* En l'entorn web, el client sol ser un **navegador web** (Chrome, Firefox, Edge, Safari), tot i que també pot ser una aplicació mòbil, una CLI o un altre servei integrat.
* **Funció principal:** Interpretació i representació de la interfície d'usuari (UI) utilitzant HTML, CSS i JavaScript, així com la gestió d'esdeveniments d'usuari en el costat del client.

### El Servidor
* És un sistema (programari i maquinari) que **espera peticions de manera contínua** i les processa per a retornar un resultat.
* En el context de la programació en entorn servidor, rep les sol·licituds HTTP del client, executa la lògica de negoci necessària, interactua amb les bases de dades i genera la resposta adequada.
* **Funció principal:** Processar les peticions d'un o molts clients de forma simultània, garantint la seguretat, la persistència de dades i l'accés centralitzat a la informació.

### Dinàmica de funcionament
1. **Petició (*Request*):** El client envia una sol·licitud a una adreça específica (URL) del servidor utilitzant el protocol HTTP/HTTPS.
2. **Processament:** El servidor rep la petició, executa el codi en el servidor (PHP, Java, Python, Node.js, etc.) i realitza les consultes necessàries a la base de dades.
3. **Resposta (*Response*):** El servidor construeix una resposta (habitualment codi HTML, dades en format JSON o fitxers multimèdia) i la retorna al client, tancant o mantenint la connexió segons el cas.

### Analogia de la vida real: Un restaurant

Per a entendre fàcilment aquest model, podem pensar en com funciona un **restaurant**:

- **El Client (El comensal):** Se senta a la taula, mira la carta i decideix què vol demanar. No entra a la cuina ni cuina el seu propi menjar. Només fa la comanda (petició).
- **El Cambrer / Protocol (HTTP):** És el mitjà de transport i comunicació. Agafa la petició del comensal, la porta a la cuina i, quan el plat està llest, el torna a la taula (resposta).
- **El Servidor (La cuina):** Rep la comanda, comprova si té els ingredients a la meua despensa/nevera (base de dades), cuina el plat seguint la recepta (lògica de negoci/programació) i el prepara per a ser servit.



Ací tens el desenvolupament del **Punt 2: El protocol HTTP**, adaptat per a ser un repàs teòric de nivell d'introducció i formatat directament en Markdown per a MkDocs.

# 2. El protocol HTTP

El protocol **HTTP** (*HyperText Transfer Protocol*) és el protocol de la capa d'aplicació sobre el qual es fonamenta la comunicació a la Web. Defineix les regles i formats per a l'intercanvi d'informació entre el client (navegador) i el servidor.

## 2.1. Funcionament i característiques

HTTP és un protocol que presenta les següents característiques clau:

- **Basat en petició-resposta (\*Request-Response\*):** La comunicació sempre la inicia el client enviant una petició (*Request*), i el servidor respon amb el recurs sol·licitat o un missatge d'error (*Response*).
- **Sense estat (\*Stateless\*):** HTTP no manté informació sobre les peticions anteriors. Cada petició és independent i el servidor no "recorda" si un client ja l'ha visitat abans.
- **Sense connexió persistent (per defecte en orígens):** Tradicionalment obria una connexió TCP per a cada petició/resposta i la tancava. Des d'HTTP/1.1 s'utilitzen connexions persistents (*Keep-Alive*) per a millorar el rendiment, però conceptualment les transaccions continuen sent independents.
- **Seguretat (HTTPS):** La versió segura d'HTTP utilitza xifrat mitjançant TLS/SSL (port 443 per defecte en comptes del port 80) per a garantir la confidencialitat i la integritat de les dades.

## 2.2. Manteniment de l'estat: Galetes (Cookies) i Sessions

Com que HTTP és un protocol **sense estat**, es requereixen mecanismes addicionals per a aconseguir que una aplicació web "recorde" a un usuari (per exemple, per a mantindre la sessió iniciada o el carret de la compra).

Plaintext

```
                  +-----------------------------------+
                  |   Mecanismes de control d'estat   |
                  +-----------------------------------+
                                    |
          +-------------------------+-------------------------+
          |                                                   |
          v                                                   v
+-------------------+                               +-------------------+
|      GALETES      |                               |     SESSIONS      |
|    (Cookies)      |                               |    (Sessions)     |
+-------------------+                               +-------------------+
| Dades guardades   |                               | Dades guardades   |
| al CLIENT         |                               | al SERVIDOR       |
| (Navegador Web)   |                               |                   |
+-------------------+                               +-------------------+
```

### Galetes (*Cookies*)

- **Ubicació:** S'emmagatzemen al **client** (en el navegador).
- **Funcionament:** El servidor envia una galeta en la capçalera de la resposta HTTP (`Set-Cookie`) i el navegador la torna a enviar automàticament en cada petició posterior cap a eix mateix domini (`Cookie`).
- **Ús típic:** Preferències d'usuari, identificadors de seguiment, recordar l'idioma.

### Sessions (*Sessions*)

- **Ubicació:** S'emmagatzemen al **servidor**.
- **Funcionament:** El servidor guarda la informació de l'usuari en la seua memòria o base de dades i genera un identificador únic de sessió (*Session ID*). Aquest *ID* s'envia al client (habitualment en una *cookie*) perquè l'incloga en les seues peticions.
- **Ús típic:** Autenticació d'usuaris (login), carret de la compra, dades sensibles.

## 2.3. Anatomia d'una transacció HTTP

Una transacció HTTP es compon d'una **Petició** (*Request*) i una **Resposta** (*Response*).

### A) La Petició HTTP (*Request*)

Es compon de:

1. **Línia de petició:** Conté el mètode HTTP, la ruta del recurs i la versió del protocol.
2. **Capçaleres (\*Headers\*):** Informació addicional sobre el client, la petició o el tipus de contingut acceptat (p. ex., `Host`, `User-Agent`, `Accept`).
3. **Cos (\*Body\*):** Opcional. Conté les dades enviades al servidor (p. ex., dades d'un formulari o un objecte JSON).

#### Principals mètodes HTTP:

- **`GET`:** Sol·licita un recurs al servidor. Les dades s'envien en la mateixa URL (com a paràmetres de cerca). No ha de tindre efectes secundaris al servidor.
- **`POST`:** Envia dades al servidor per a ser processades (p. ex., crear un nou recurs). Les dades viatgen en el cos (*body*) de la petició.
- **`PUT` / `PATCH`:** S'utilitzen per a actualitzar un recurs existent al servidor.
- **`DELETE`:** Sol·licita l'eliminació d'un recurs del servidor.

### B) La Resposta HTTP (*Response*)

Es compon de:

1. **Línia d'estat:** Versió del protocol i el **codi d'estat** (amb el seu text explicatiu).
2. **Capçaleres (\*Headers\*):** Metadades sobre el servidor i la resposta (p. ex., `Content-Type`, `Content-Length`, `Set-Cookie`).
3. **Cos (\*Body\*):** El contingut retornat (HTML, JSON, imatge, etc.).

#### Codis d'estat HTTP principals (Classes):

| **Rang** | **Categoria**       | **Descripció i Exemples**                                    |
| -------- | ------------------- | ------------------------------------------------------------ |
| **1xx**  | Informatius         | La petició ha sigut rebuda i continua el procés (p. ex., `100 Continue`). |
| **2xx**  | Èxit (*OK*)         | La petició s'ha rebut, entés i acceptat correctament (p. ex., `200 OK`, `201 Created`). |
| **3xx**  | Redireccions        | Cal fer accions addicionals per a completar la petició (p. ex., `301 Moved Permanently`, `302 Found`). |
| **4xx**  | Errors del Client   | La petició conté una sintaxi errònia o no es pot processar (p. ex., `400 Bad Request`, `403 Forbidden`, `404 Not Found`). |
| **5xx**  | Errors del Servidor | El servidor ha fallat en intentar processar una petició vàlida (p. ex., `500 Internal Server Error`, `503 Service Unavailable`). |

## 2.4. Format de les URL / URI

Per a accedir a qualsevol recurs a la web, s'utilitza un identificador anomenat **URI** (*Uniform Resource Identifier*), sent el tipus més comú la **URL** (*Uniform Resource Locator*).

### Estructura d'una URL:

Plaintext

```
  esquema://usuari:contrasenya@host:port/ruta?consulta#fragment
  \____/   \_________________/ \__/ \__/ \__/ \______/ \______/
    |               |            |    |    |      |        |
 Protocol        Usuari/Pass    Domini Port Ruta Paràmetres Àncora
```

- **Esquema (\*Scheme\*):** El protocol utilitzat (`http`, `https`, `ftp`).
- **Host / Domini:** El nom de domini o adreça IP del servidor (`[www.example.com](https://www.example.com)`).
- **Port:** El port de xarxa pel qual escolta el servei (`:80` per a HTTP, `:443` per a HTTPS, opcional si s'utilitzen els ports per defecte).
- **Ruta (\*Path\*):** La ruta absoluta al recurs dins del servidor (`/productes/detall`).
- **Paràmetres de consulta (\*Query string\*):** Paràmetres clau-valor separats per `&` que comencen amb un signe `?` (`?id=15&categoria=electronica`).
- **Fragment (\*Anchor\*):** Apunta a una secció específica dins de la pàgina (`#caracteristiques`).



Ací tens el desenvolupament del **Punt 3: Servidor Web vs Servidor d'Aplicacions**, estructurat per a MkDocs com un repàs teòric introductori i sense contingut extres.

# 3. Servidor Web vs Servidor d’Aplicacions

En el desenvolupament i desplegament d'aplicacions web és comú trobar dos tipus de servidors que treballen conjuntament: el **servidor web** i el **servidor d'aplicacions**. Encara que la frontera entre ambdós s'ha anat difuminant amb el temps, compleixen rols diferents i complementaris en l'arquitectura del sistema.

## 3.1. Diferències principals

La diferència principal rau en el **tipus de contingut** que gestionen i en la seua capacitat per a **executar lògica de negoci**.

Plaintext

```
               +-------------------------------------------------------+
               |                    SERVIDORS WEB                      |
               |                                                       |
               |  - Atén peticions HTTP/HTTPS.                         |
               |  - Serveix contingut ESTÀTIC (HTML, CSS, JS, imatges). |
               |  - Alta eficiència i rendiment en transferència.      |
               +-------------------------------------------------------+
                                           |
                                           v (Delega peticions dinàmiques)
               +-------------------------------------------------------+
               |               SERVIDORS D'APLICACIONS                 |
               |                                                       |
               |  - Executa LÒGICA DE NEGOCI (codi en entorn servidor).|
               |  - Genera contingut DINÀMIC (HTML, JSON, XML).        |
               |  - Ofereix serveis complexos (transaccions, seguretat)|
               +-------------------------------------------------------+
```

| **Característica**      | **Servidor Web**                                            | **Servidor d'Aplicacions**                                   |
| ----------------------- | ----------------------------------------------------------- | ------------------------------------------------------------ |
| **Funció principal**    | Servir contingut estàtic i gestionar la comunicació HTTP.   | Executar lògica de negoci i generar contingut dinàmic.       |
| **Tipus de recursos**   | Fitxers HTML, CSS, JavaScript, imatges, vídeos, PDF.        | Codi executable (PHP, Java Servlets/JSP, Python, C#, etc.).  |
| **Consum de recursos**  | Baix consum de CPU i memòria (molt optimitzat per a I/O).   | Major consum de CPU i memòria a causa del processament de dades. |
| **Serveis addicionals** | Compressió, SSL/TLS, *caching*, redireccions, proxy invers. | Gestió de transaccions, connexió a bases de dades, seguretat de negoci. |

## 3.2. Exemples de servidors web

Els servidors web estan dissenyats principalment per a respondre a peticions HTTP de manera ràpida i eficient.

- **Apache HTTP Server:** Un dels servidors web més populars i històrics. És modular, molt configurable i extensible mitjançant mòduls (com `mod_php` per a interpretar PHP directament).

- **NGINX:** Destaca pel seu alt rendiment, baix consum de memòria i arquitectura orientada a esdeveniments. S'utilitza habitualment com a servidor de recursos estàtics, **proxy invers** i **balancejador de càrrega**.

                  +---------------------------------------+
                  |            PROXY INVERS               |
                  |           (p. ex. NGINX)              |
                  +---------------------------------------+
                               /      |      \
                              /       |       \
                             v        v        v
                      +---------+ +---------+ +---------+
                      |Servidor1| |Servidor2| |Servidor3|
                      +---------+ +---------+ +---------+

- **Caddy:** Servidor web modern escrit en Go, conegut per la seua facilitat de configuració i la gestió automàtica de certificats HTTPS (Let's Encrypt).

## 3.3. Exemples de servidors d'aplicacions

Els servidors d'aplicacions proporcionen l'entorn d'execució (*runtime*) necessari perquè el codi en el servidor s'execute, interactue amb la base de dades i genere una resposta.

- **Apache Tomcat / Jetty:** Servidors/contenidors d'aplicacions per a l'ecosistema Java (específicament per a executar Servlets i JSP).
- **Red Hat WildFly (abans JBoss) / GlassFish:** Servidors d'aplicacions Java EE / Jakarta EE complets, que ofereixen suport per a transaccions complexes, missatgeria enterprise, etc.
- **Microsoft IIS (Internet Information Services):** Servidor de Microsoft que actua tant de servidor web com de servidor d'aplicacions per al *stack* de tecnologies .NET (ASP.NET).
- **Gunicorn / uWSGI:** Servidors d'aplicacions utilitzats en l'ecosistema Python per a executar aplicacions desenvolupades amb *frameworks* com Django o Flask.
- **Node.js (com a runtime):** Permet crear tant el servidor web com la lògica de l'aplicació en un mateix entorn utilitzant JavaScript.

Ací tens el desenvolupament del **Punt 4: Pàgines web estàtiques i dinàmiques**, preparat en format Markdown per a MkDocs i enfocat com a repàs teòric d'un curs de desenvolupament web.

---

# 4. Pàgines web estàtiques i dinàmiques

La diferència fonamental entre una pàgina web estàtica i una dinàmica rau en **quan i on es genera el contingut** que rep el navegador de l'usuari.

* **Pàgines estàtiques:** El contingut està predefinit i emmagatzemat en el servidor en fitxers (HTML, CSS, imatges). La resposta és idèntica per a tots els usuaris i no canvia a menys que el desenvolupador modifique manualment el fitxer font.
* **Pàgines dinàmiques:** El contingut es genera de manera personalitzada i en temps real (al vol) en funció de les peticions de l'usuari, les dades emmagatzemades en una base de dades o l'estat de la sessió.

---

## 4.1. Execució de codi en el client vs Servidor

En la creació de pàgines web dinàmiques, el codi es pot executar en dues localitzacions diferents, cadascuna amb un propòsit i un entorn d'execució clarament diferenciats.

```text
  CLIENT (Navegador)                               SERVIDORS (Web / Aplicacions)
+-----------------------+                       +-------------------------------+
|                       |  ---- 1. Request ---> |                               |
| - Interfície d'usuari |                       | - Lògica de negoci principal  |
| - Validació bàsica    |                       | - Consultes a Base de Dades   |
| - Executa JavaScript  |  <--- 2. Response --- | - Executa PHP, Java, Python...|
|                       |    (HTML/JSON/etc.)   |                               |
+-----------------------+                       +-------------------------------+

```

### Execució de codi en el Client (*Front-end*)

* **On s'executa:** En el navegador web de l'usuari final.
* **Tecnologies principals:** HTML5, CSS3, JavaScript (i *frameworks* com React, Vue, Angular).
* **Funció principal:** Millorar l'experiència d'usuari (UX), gestionar la interactivitat visual, manipular el DOM, realitzar validacions en formularis abans de l'enviament i realitzar peticions asíncrones (AJAX/Fetch).
* **Característiques:**
* Depén del navegador de l'usuari i de la potència de la seua màquina.
* El codi font és **públic i visible** per a qualsevol usuari (mitjançant la funció "Veure codi font").
* **No és segur** per a processar lògica crítica o dades sensibles (autenticació, pagaments).



---

### Execució de codi en el Servidor (*Back-end*)

* **On s'executa:** En el servidor web o d'aplicacions.
* **Tecnologies principals:** PHP, Java (Servlets/Spring), Python (Django/Flask), Node.js, C# (.NET), Ruby, etc.
* **Funció principal:** Accés i coordinació de bases de dades, control d'accés i seguretat, processament de transaccions, gestió de sessions i generació de la resposta final.
* **Característiques:**
* L'execució és independent de l'equip de l'usuari.
* El codi font és **privat i inaccessible** per als usuaris externs.
* És l'entorn on es garanteix la **seguretat i la integritat de les dades**.



---

### Taula comparativa: Execució Client vs Servidor

| Criteri | Execució en el Client (*Front-end*) | Execució en el Servidor (*Back-end*) |
| --- | --- | --- |
| **Lloc d'execució** | Navegador de l'usuari. | Servidor remot. |
| **Visibilitat del codi** | Codi visible directament per l'usuari. | Codi privat i protegit al servidor. |
| **Principal avantatge** | Resposta immediata en la interfície sense recarregar la pàgina. | Seguretat, accés centralitzat a dades i gran capacitat de procés. |
| **Seguretat** | Baixa. No es pot confiar en les dades validades només en el client. | Alta. És l'encarregat de validar i protegir les dades realment. |
| **Accés a bases de dades** | Indirecte (mitjançant peticions a una API del servidor). | Directe (mitjançant connectors i orígens de dades). |

Ací tens un parell d'exemples clars per a veure la diferència a la pràctica:

### Exemples d'una Pàgina Web Estàtica

1. **El menú d'un restaurant local en format PDF o HTML simple:**

   El propietari del restaurant penja un fitxer `.html` amb el text de la carta i els preus. Totes les persones que entren a la web veuen exactament el mateix menú, a la mateixa hora, sense cap variació. Si el xef canvia un preu, el programador ha d'obrir el fitxer, editar el codi i tornar a pujar-lo al servidor.

2. **Un portafoli personal senzill d'un dissenyador:**

   Consisteix en fitxers HTML, CSS i imatges fixes. L'únic que fa el servidor quan rep la petició és enviar eixos fitxers tal com estan guardats al disc dur.

### Exemples d'una Pàgina Web Dinàmica

1. **L'alimentació (\*feed\*) de la xarxa social Instagram o X (Twitter):**

   Encara que dos usuaris entren a la mateixa adreça URL (`instagram.com`), el servidor executa codi en el servidor, comprova quina sessió està iniciada, consulta la base de dades i construeix al vol una pàgina única per a cadascun, mostrant només les fotos dels comptes que eix usuari segueix.

2. **Una botiga online com Amazon o Pccomponentes:**

   Quan busques un "teclat mecànic", el servidor no té una pàgina HTML feta prèviament per a eixa cerca. El servidor d'aplicacions pren la paraula "teclat mecànic", consulta la base de dades de productes, comprova l'stock en temps real, calcula els descomptes de la teua xarxa i **genera dinàmicament l'HTML** (o les dades JSON) amb els resultats directament per a tu.



# 5. Model de desenvolupament en 3 capes

El **model de desenvolupament en 3 capes** (o arquitectura en tres nivells) és un patró d'arquitectura de software que separa l'aplicació en tres components lògics independents. Aquesta divisió facilita el manteniment, la reusabilitat del codi i la l'escalabilitat del sistema.

## 5.1. Capa de presentació, procés/negoci i dades

Plaintext

```
+-------------------------------------------------------+
|                1. CAPA DE PRESENTACIÓ                 |
|                   (Interfície / UI)                   |
+-------------------------------------------------------+
                            |
                            v (Peticions / Dades)
+-------------------------------------------------------+
|               2. CAPA DE PROCÉS / NEGOCI              |
|                 (Lògica de l'aplicació)               |
+-------------------------------------------------------+
                            |
                            v (Consultes / Persistència)
+-------------------------------------------------------+
|                   3. CAPA DE DADES                    |
|             (Base de Dades / Persistència)            |
+-------------------------------------------------------+
```

### 1. Capa de Presentació (*Presentation Layer*)

- **Funció:** És la interfície amb la qual interactua l'usuari final. S'encarrega de capturar les accions de l'usuari (fets, clics, formularis) i de mostrar-li els resultats enviats per la capa inferior.
- **On resideix:** Principalment en el **client** (navegador web o aplicació mòbil).
- **Responsabilitats:** Mostrar la informació, formatar el contingut visual i realitzar validacions primàries d'entrada.

### 2. Capa de Procés / Negoci (*Business Logic Layer*)

- **Funció:** És el "cervell" de l'aplicació. Implementa les regles de negoci, processa les dades rebudes de la capa de presentació i realitza els càlculs o transformacions necessàries.
- **On resideix:** S'executa al **servidor d'aplicacions** (*Back-end*).
- **Responsabilitats:** Validar l'autenticitat dels usuaris, coordinar el flux de l'aplicació, aplicar regles de negoci (com ara aplicar un descompte o validar un pagament) i sol·licitar o emmagatzemar dades en la capa inferior.

### 3. Capa de Dades (*Data Access Layer*)

- **Funció:** S'encarrega d'emmagatzemar, gestionar i recuperar la informació de manera persistent.
- **On resideix:** En el **servidor de bases de dades**.
- **Responsabilitats:** Guardar la informació en taules o documents, executar consultes d'alta velocitat (SQL o NoSQL) i mantindre la integritat i consistència de les dades.

## 5.2. Exemples de tecnologies per capa

A continuació es mostren les tecnologies més habituals utilitzades en cadascuna de les capes en el desenvolupament web actual:

| **Capa**                         | **Funció principal**                      | **Tecnologies / Eines**                                      |
| -------------------------------- | ----------------------------------------- | ------------------------------------------------------------ |
| **Presentació** (*Front-end*)    | Interfície d'usuari i experiència visual. | HTML5, CSS3, JavaScript, TypeScript, React, Angular, Vue.js, Bootstrap, Tailwind CSS. |
| **Procés / Negoci** (*Back-end*) | Lògica, seguretat i coordinació.          | PHP (Laravel, Symfony), Java (Spring Boot), Python (Django, FastAPI), Node.js (Express), C# (.NET Core). |
| **Dades** (*Database*)           | Persistència i gestió de la informació.   | **Relacionals:** PostgreSQL, MySQL, MariaDB, Oracle. **No relacionals:** MongoDB, Redis, Cassandra. |

# 6. Perfils professionals en el desenvolupament web

El desenvolupament d'aplicacions web modernes implica diferents tecnologies, eines i competències. Per aquest motiu, el sector professional s'ha especialitzat en diferents perfils segons la capa o la part de l'arquitectura en què es focalitza cada desenvolupador.

## 6.1. Front-end, Back-end i Full Stack

Plaintext

```
+-----------------------------------------------------------------------+
|                              FULL STACK                               |
|                  (Domina el Front-end i el Back-end)                  |
+-----------------------------------------------------------------------+
                |                                       |
                v                                       v
+-------------------------------+       +-------------------------------+
|           FRONT-END           |       |            BACK-END           |
|      (Costat del Client)      |       |      (Costat del Servidor)     |
+-------------------------------+       +-------------------------------+
| - HTML, CSS, JavaScript       |       | - PHP, Java, Python, Node.js  |
| - Frameworks (React, Vue)     |       | - Gestió de Bases de Dades    |
| - Disseny adaptable (RWD)     |       | - Disseny d'APIs i Seguretat  |
| - Experiència d'usuari (UX)   |       | - Rendiment i arquitectura    |
+-------------------------------+       +-------------------------------+
```

### 1. Desenvolupador/a Front-end

- **Enfocament:** Es centra en la **interfície d'usuari** (UI) i en l'**experiència d'usuari** (UX). S'encarrega de tot allò que l'usuari veu i amb què interactua directament en el navegador web.
- **Responsabilitats:**
  - Maquetació de dissenys web adaptables a diferents dispositius (*Responsive Web Design*).
  - Programació de la interactivitat de la pàgina.
  - Consum d'APIs creades pel Back-end per a mostrar informació dinàmica.
  - Optimització del rendiment de càrrega en el client.
- **Tecnologies principals:** HTML5, CSS3, JavaScript/TypeScript, *frameworks* i llibreries com React, Angular, Vue.js, i eines de maquetació com Tailwind CSS o Bootstrap.

### 2. Desenvolupador/a Back-end

- **Enfocament:** Es centra en la **lògica interna**, l'estructura de dades i els processos que tenen lloc al **servidor**. L'usuari no veu directament aquest codi, però en pateix les conseqüències si no està ben optimitzat o segur.
- **Responsabilitats:**
  - Disseny i manteniment de la lògica de negoci de l'aplicació.
  - Disseny i gestió de la base de dades (modelatge, consultes, optimització).
  - Creació d'APIs (REST, GraphQL) per a comunicar-se amb el Front-end o amb aplicacions terceres.
  - Gestió de la seguretat, autenticació, autorització i protecció de dades sensibles.
- **Tecnologies principals:** PHP, Java, Python, Node.js, C# (.NET), bases de dades relacionals (MySQL, PostgreSQL) i no relacionals (MongoDB, Redis).

### 3. Desenvolupador/a Full Stack

- **Enfocament:** És un perfil versàtil que posseeix coneixements i habilitats tant en el desenvolupament **Front-end** com en el **Back-end**.
- **Responsabilitats:**
  - Capaç de dissenyar i desenvolupar una aplicació web completa des de zero, des de la base de dades fins a la interfície d'usuari.
  - Comprén la totalitat del flux de dades i l'arquitectura de l'aplicació.
  - Facilita la comunicació i la coordinació entre equips especialitzats de Front-end i Back-end.
- **Nota:** Ser Full Stack no implica ser un expert absolut en totes les tecnologies, sinó tindre la capacitat de treballar de manera eficaç en qualsevol de les capes del projecte.



# 7. Tipologia de les aplicacions web

Segons com es gestió la navegació, la càrrega de dades i la generació del contingut entre el client i el servidor, les aplicacions web es classifiquen en diferents tipus d'arquitectura.

## 7.1. MPA, PEMPA i SPA

Plaintext

```
  MPA (Multi-Page App)           SPA (Single-Page App)
+----------------------+        +----------------------+
|  Navegador           |        |  Navegador           |
|  [Pàgina 1]          |        |  [Pàgina única]      |
|      |               |        |      | (AJAX/Fetch)  |
|      v               |        |      v               |
|  Recàrrega completa  |        |  Actualitza només    |
|      |               |        |  el DOM necessari    |
|      v               |        |                      |
|  [Pàgina 2]          |        |                      |
+----------------------+        +----------------------+
```

### 1. MPA (*Multi-Page Application*) - Aplicació Multi-pàgina

És l'arquitectura tradicional de la web.

- **Funcionament:** Cada vegada que l'usuari fa clic en un enllaç o envia un formulari, el navegador fa una nova petició HTTP al servidor. El servidor processa la petició, genera una pàgina HTML nova per complet i la torna al navegador, provocant una **recàrrega completa** de la pàgina.
- **Avantatges:**
  - Molt bon posicionament en cercadors (**SEO**), ja que cada recurs té la seua pròpia URL i l'HTML ve complet des del servidor.
  - Senzilla d'implementar en projectes tradicionals.
- **Inconvenients:** Experiència d'usuari més lenta i menys fluida a causa de la recàrrega constant de tota la pàgina.
- **Exemples:** Viquipèdia, la majoria de diaris digitals, blogs tradicionals.

### 2. SPA (*Single-Page Application*) - Aplicació de Pàgina Única

És l'arquitectura moderna habitual per a aplicacions web interactives.

- **Funcionament:** El servidor envia al client un sol document HTML inicial juntament amb el JavaScript necessari. A partir d'eixe moment, la navegació i les accions de l'usuari no recarreguen la pàgina: JavaScript intercepta les accions, demana només les dades necessàries al servidor (mitjançant **AJAX** o l'API `fetch`, habitualment en format JSON) i **actualitza dinàmicament el DOM** de la pàgina.
- **Avantatges:**
  - Experiència d'usuari molt fluida i ràpida (similar a una aplicació nativa d'escriptori o mòbil).
  - Reducció del tràfic de xarxa, ja que només s'intercanvien dades i no estructures HTML completes en cada acció.
- **Inconvenients:**
  - Càrrega inicial potencialment més lenta.
  - Més complexitat en el posicionament SEO (cal utilitzar tècniques addicionals com *Server-Side Rendering* o SSR).
- **Exemples:** Gmail, Spotify Web, Trello, Google Maps.

### 3. PEMPA / MAPA (*Progressive Enhancement Multi-Page Application*)

Una solució híbrida o d'evolució progressiva.

- **Funcionament:** Parteix de la base d'una aplicació multi-pàgina tradicional (MPA) per a garantir que funcione en qualsevol entorn o navegador. A continuació, utilitza tècniques de **millora progressiva** (*Progressive Enhancement*), afegint capes de JavaScript per a dur a terme actualitzacions parcials de la pàgina sense recarregar-la quan el navegador ho permet.
- **Avantatges:** Manté l'accessibilitat, la robustesa i el SEO d'una MPA, millorant l'experiència d'usuari allà on és possible.
- **Tecnologies relacionades:** *Frameworks* moderns com htmx, Hotwire (Stimulus/Turbo) o Phoenix LiveView s'alineen amb aquesta filosofia.

### Taula comparativa

| **Característica**        | **MPA (Multi-Page)**                        | **SPA (Single-Page)**                                    |
| ------------------------- | ------------------------------------------- | -------------------------------------------------------- |
| **Càrrega de pàgina**     | Recàrrega completa en cada navegació.       | Càrrega inicial única; posteriorment només dades.        |
| **Generació d'HTML**      | Principalment en el **Servidor**.           | Principalment en el **Client** (via JavaScript).         |
| **Experiència d'usuari**  | Tradicional, amb breus pauses de recàrrega. | Fluida i ràpida, similar a una aplicació nativa.         |
| **SEO (Cercadors)**       | Excel·lent de manera nativa.                | Requereix configuracions addicionals (SSR/Prerendering). |
| **Complexitat Front-end** | Baixa / Mitjana.                            | Alta (requereix gestió d'estat, rutes en client, etc.).  |

# 8. Llenguatges de programació en entorn servidor

Per a desenvolupar la lògica de negoci en el costat del servidor (*Back-end*), existeixen diversos llenguatges i ecosistemes de programació. La selecció de la tecnologia depén de factors com el rendiment requerit, la complexitat del projecte, l'equip de desenvolupament o l'arquitectura existent.

## 8.1. PHP (LAMP)

**PHP** (*Hypertext Preprocessor*) és un dels llenguatges més populars i utilitzats en la història de la Web, dissenyat específicament per al desenvolupament web en entorn servidor.

- **Stack LAMP:** Històricament s'associa a l'arquitectura **L**inux (sistema operatiu), **A**pache (servidor web), **M**ySQL (base de dades) i **P**HP (llenguatge).
- **Característiques:**
  - S'executa integrat en el servidor web o mitjançant PHP-FPM.
  - Facilita la creació ràpida de prototips i té una basca d'aprenentatge molt accessible.
  - Molt utilitzat en sistemes de gestió de continguts (CMS) com WordPress, Drupal o Joomla.
- **Frameworks moderns:** Laravel, Symfony.

## 8.2. JSP / Servlets (Java)

L'ecosistema **Java** és l'estàndard corporatiu per a aplicacions web d'alta escala, robustesa i seguretat.

- **Servlets:** Classes Java que s'executen en un servidor d'aplicacions (com Tomcat) per a processar peticions HTTP i generar respostes.
- **JSP (\*JavaServer Pages\*):** Tecnologia que permet barrejar codi HTML amb etiquetes Java per a facilitar la creació de pàgines dinàmiques (que després es recompilen internament com a Servlets).
- **Característiques:**
  - Tipat fort, alt rendiment i suport per a multithreading avançat.
  - Utilitzat principalment en entorns bancaris, grans empreses i administracions públiques.
- **Frameworks moderns:** Spring Boot, Jakarta EE.

## 8.3. Python (Django / Flask)

**Python** és un llenguatge de propòsit general reconegut per la seua sintaxi neta, llegibilitat i gran versatilitat.

- **Característiques:**
  - Destaca en projectes que integren intelligència artificial, ciència de dades, *machine learning* o automatització juntament amb l'aplicació web.
  - Execució en servidor mitjançant interfícies com WSGI o ASGI (utilitzant servidors com Gunicorn o Uvicorn).
- **Frameworks principals:**
  - **Django:** Framework "*batteries-included*" molt complet, amb ORM, panell d'administració i seguretat integrats de sèrie.
  - **Flask / FastAPI:** Microframeworks lleugers i modulars, ideals per a la creació d'APIs REST ràpides.

## 8.4. ASP.NET (C#)

**ASP.NET** (i la seua evolució moderna **.NET Core / .NET**) és el marc de desenvolupament creat per Microsoft per a la construcció d'aplicacions web i APIs.

- **Característiques:**
  - Utilitza principalment el llenguatge **C#**, oferint un entorn de desenvolupament molt estructurat, de tipat fort i alt rendiment.
  - Originalment integrat només en sistemes Windows amb IIS, actualment és totalment **multiplataforma** (Linux, macOS, Windows).
  - Integra mecanismes d'autenticació, optimització de memòria i eines d'alt nivell.
- **Frameworks/Patrons:** ASP.NET Core MVC, Razor Pages, Blazor.



### 8.5. Node.js (JavaScript / TypeScript)

**Node.js** no és un llenguatge en si mateix, sinó un entorn d'execució per a **JavaScript** construït sobre el motor V8 de Google Chrome.

- **Característiques:**
  - **Model E/S no bloquejant i orientat a esdeveniments (\*Event Loop\*):** Permet gestionar milers de connexions de manera simultània amb un consum molt baix de recursos.
  - **Llenguatge únic (\*Full Stack JS\*):** Permet utilitzar el mateix llenguatge (JavaScript o TypeScript) tant al client (*Front-end*) com al servidor (*Back-end*).
  - **Ecosistema NPM:** Compta amb el repositori de llibreries i mòduls més gran del món.
- **Frameworks principals:** Express.js, NestJS (molt utilitzat en entorns enterprise amb TypeScript), Fastify, Koa.

### Taula comparativa

| **Llenguatge / Entorn**  | **Framework principal** | **Tipat**                | **Casos d'ús típics**                                        |
| ------------------------ | ----------------------- | ------------------------ | ------------------------------------------------------------ |
| **PHP**                  | Laravel, Symfony        | Dinàmic                  | Webs corporatives, CMS, e-Commerce, pimes.                   |
| **Java**                 | Spring Boot, Jakarta EE | Estàtic (fort)           | Sistemes bancaris, aplicacions enterprise.                   |
| **Python**               | Django, FastAPI         | Dinàmic                  | Data Science, IA, APIs ràpides.                              |
| **C#**                   | ASP.NET Core            | Estàtic (fort)           | Entorns corporatius, serveis cloud escalables.               |
| **Node.js (JavaScript)** | Express, NestJS         | Dinàmic (Estàtic amb TS) | Aplicacions en temps real (xats, streaming), APIs REST, microserveis. |

# 9. Bibliografia i Recursos

Per a aprofundir en els conceptes teòrics i pràctics introduïts en aquesta unitat, es recomana la consulta de la següent documentació oficial, estàndards web i recursos educatius:

## 9.1. Documentació Oficial i Estàndards Web

- **Mozilla Developer Network (MDN Web Docs):**
  - *HTTP Reference & Guides:* Documentació de referència sobre el protocol HTTP, capçaleres, codis d'estat i cookies.
  - URL: https://developer.mozilla.org/es/docs/Web/HTTP
- **World Wide Web Consortium (W3C):**
  - Estàndards i especificacions de la web (HTML5, arquitectura d'Internet).
  - URL: https://www.w3.org/
- **IETF (Internet Engineering Task Force):**
  - Especificació de les RFCs del protocol HTTP (RFC 7230, RFC 9110).
  - URL: https://www.ietf.org/

## 9.2. Documentació de Servidors i Entorns d'Execució

- **Apache HTTP Server Documentation:** https://httpd.apache.org/docs/
- **NGINX Documentation:** https://nginx.org/en/docs/
- **PHP Documentation:** https://www.php.net/docs.php
- **Node.js Documentation:** https://nodejs.org/en/docs/

## 9.3. Recursos i Guies d'Arquitectura

- **OWASP (Open Web Application Security Project):**
  - Guia de seguretat en aplicacions web i bones pràctiques per a desenvolupadors.
  - URL: https://owasp.org/
- **Web.dev (Google Developers):**
  - Articles i guies sobre rendiment web, arquitectures modernes (SPA, SSR, MPA) i bones pràctiques de desenvolupament.
  - URL: https://web.dev/

