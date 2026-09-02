# Unitat 1. Introducció a la part del servidor de les aplicacions web
**2n DAW - IES Maria Enríquez**

---

## Resultats d'aprenentatge i criteris d'avaluació

### RA1. Selecciona les arquitectures i tecnologies de programació Web en entorn servidor, analitzant les seues capacitats i característiques pròpies.

#### Criteris d'avaluació:
- **a)** S'han caracteritzat i diferenciat els models d'execució de codi en el servidor i en el client Web.
- **b)** S'han reconegut els avantatges que proporciona la generació dinàmica de pàgines Web i les seues diferències amb la inclusió de sentències de guions a l'interior de les pàgines Web.
- **c)** S'han identificat els mecanismes d'execució de codi en els servidors Web.
- **d)** S'han reconegut les funcionalitats que aporten els servidors d'aplicacions i la seua integració amb els servidors Web.
- **e)** S'han identificat i caracteritzat els principals llenguatges i tecnologies relacionats amb la programació Web en entorn servidor.
- **f)** S'han verificat els mecanismes d'integració dels llenguatges de marques amb els llenguatges de programació en entorn servidor.
- **g)** S'han reconegut i avaluat les eines de programació en entorn servidor.

---

## Estructura de la unitat

1. **Arquitectura Web:** Models client-servidor, comunicació HTTP/HTTPS, galetes i transaccions.
2. **Servidors:** Diferències entre servidor web i d'aplicacions. Exemples: Apache, Nginx, Tomcat, IIS.
3. **Model de 3 Capes:** Capes de presentació, lògica de negoci i dades. Perfils professionals Front-End i Back-End.
4. **Tecnologies:** Introducció pràctica a llenguatges de servidor: PHP, JSP/Servlets, Python (Django) i ASP.NET.

---

## Què anem a fer en aquesta unitat?

- Estructura d'una aplicació web
- Preparació entorn de treball

---

## 1. Arquitectura d'una aplicació web

### Quina diferència hi ha entre aplicacions d'escriptori i aplicacions web?

A diferència de les aplicacions d'escriptori, que utilitzen els recursos d'un únic ordinador, les aplicacions web són **distribuïdes**, és a dir, hi intervenen com a mínim dos equips diferents: el **client** i el **servidor**.

La comunicació es du a terme mitjançant el **protocol HTTP**, base de la World Wide Web.



---

## El model client / servidor

En el model client-servidor, tots els clients estan connectats a un servidor on es centralitzen els diferents recursos. Aquests recursos estan a disposició dels clients cada cop que els sol·liciten.

Això fa que totes les gestions que es realitzen es concentren en el servidor, que disposa dels requeriments dels clients amb prioritat: els arxius que són d'ús públic i els restringits, els arxius de només lectura, els que poden ser modificats, etc.



### Funcionament general:
- El dispositiu que sol·licita informació es denomina **client** i el dispositiu que respon la sol·licitud es denomina **servidor**. Els processos de client i servidor es consideren una part de la capa d'aplicació.
- El client comença l'intercanvi sol·licitant les dades al servidor, que respon enviant un o més blocs de dades al client.
- Els protocols de capa d'aplicació descriuen el format de les sol·licituds i respostes entre clients i servidor. A més de la transferència real de dades, aquest intercanvi pot requerir informació addicional, com l'autentificació de l'usuari o la identificació d'un arxiu de dades per transferir.
- Encara que les dades generalment es descriuen com un flux del servidor al client, algunes dades sempre flueixen del client al servidor (pujada/upload vs baixada/download).

---

## El protocol HTTP

El protocol de transferència d'hipertext (HTTP) és un protocol client-servidor molt senzill que articula els intercanvis d'informació entre els clients HTTP (navegadors) i els servidors HTTP.

HTTP es basa en operacions senzilles de **sol·licitud/resposta**:
1. Quan un client estableix una connexió amb un servidor i envia un missatge amb les dades de la sol·licitud, el servidor respon amb un missatge similar que conté l'estat de l'operació i el seu resultat.
2. Totes les operacions poden adjuntar un objecte o recurs sobre el qual actuen; cada objecte web (document HTML, arxiu multimèdia o aplicació CGI) és conegut pel seu localitzador uniforme de recursos (**URL**, *Uniform Resource Locator*).

### Gestió de l'estat (Cookies / Galetes)
HTTP és un protocol **sense estat**, és a dir, no guarda cap informació sobre connexions anteriors.

El desenvolupament d'aplicacions web freqüentment necessita mantenir estat. Per això s'utilitzen les **galetes (cookies)**, és a dir, la informació que un servidor pot emmagatzemar en el sistema client. Això permet que les aplicacions web institueixin la noció de **"sessió"**, i, alhora, permet rastrejar usuaris, ja que les galetes es poden emmagatzemar en el client durant un temps indeterminat.

---

## Transacció HTTP

Cada vegada que un client fa una petició a un servidor, s'executen un seguit d'accions:

1. Un usuari accedeix a una adreça d'Internet (URL) separant-ne les diferents parts: protocol d'accés, el node (nom de domini o adreça IP), el port opcional (per defecte 80 per a HTTP i 443 per a HTTPS) i l'objecte del servidor requerit.
2. S'obre una connexió TCP/IP amb el servidor i es fa la petició enviant l'ordre necessària (`GET`, `POST`, `HEAD`, etc.), l'adreça de l'objecte requerit, la versió del protocol HTTP (normalment HTTP/1.1 o HTTP/2) i capçaleres amb dades sobre el navegador, etc.
3. El servidor localitza el recurs sol·licitat i torna la resposta al client.
4. Aquesta resposta consisteix en un codi d'estat i el tipus de dada (**MIME**, *Multipurpose Internet Mail Extension*) de la informació de tornada, seguit de la mateixa informació.
5. El client formata i mostra el recurs rebut.
6. Es tanca la connexió TCP.

> **IMPORTANT:** Aquest procés es repeteix en cada accés que es faça al servidor HTTP. Per exemple, si es recull un document HTML que conté quatre imatges, el procés de transició es repeteix cinc vegades (una pel document HTML i quatre per les imatges).

Si el recurs sol·licitat és un programa (CGI, ASP.NET, PHP, etc.), el servidor HTTP redirigirà la petició a la llibreria o intèrpret adequat que executarà el programa i tornarà el control al servidor web.

---

## Format de les URL

La sintaxi general de les URL consisteix en una seqüència jeràrquica de 5 components:

```text
URI = scheme:[//authority]path[?query][#fragment]
```

On el component `authority` es divideix en tres subcomponents:

```text
authority = [userinfo@]host[:port]
```



---

## 2. Servidors Web vs Servidors d'Aplicacions

### Comparativa i definicions

#### Aproximació 1:
- **Servidor Web:** Aplicació que rep una petició HTTP i retorna la pàgina web sol·licitada escrita en llenguatge HTML (podent contenir CSS i JavaScript) perquè siga interpretada i visualitzada pel navegador.
- **Servidor d'Aplicacions:** Servidor que permet l'execució d'aplicacions web i lògica de negoci complexa.

#### Aproximació 2 (AWS):

| Característica | Servidor Web | Servidor d'Aplicacions |
| :--- | :--- | :--- |
| **Tasques realitzades** | Ofereixen respostes a sol·licituds senzilles. | Ofereix contingut més complex de bases de dades, serveis i sistemes empresarials. |
| **Protocols utilitzats** | Utilitzen principalment HTTP (també FTP, SMTP). | Admeten molts protocols. |
| **Tipus de continguts** | Ofereixen contingut estàtic (HTML, imatges, vídeos, arxius). | Ofereixen contingut dinàmic (actualitzacions en temps real, informació personalitzada, atenció al client). |
| **Subprocessament múltiple** | No sol utilitzar subprocessos múltiples. | Utilitza subprocessos múltiples per a processar sol·licituds de forma simultània. |

#### Aproximació 3:
- **Servidor web:** Ofereix arxius bàsics com HTML, imatges, CSS... (pàgines web estàtiques).
- **Servidor d'aplicacions web:** Requereix execució de codi en el servidor per posteriorment enviar l'HTML processat al client (p. ex., accés a bases de dades, pàgines web dinàmiques).

---

## Exemples de Servidors

### Servidors Web
- **Apache:** És un dels servidors web més coneguts. Programari lliure i multiplataforma (el ~90% s'executa en entorns Linux). Molt modular i flexible, permet donar servei a webs escrites en diversos llenguatges (PHP, Python, ASP...) a través de mòduls.
- **NGINX:** Servidor web / proxy invers lleuger d'alt rendiment i proxy per a protocols de correu electrònic (IMAP/POP3).

### Servidors d'Aplicacions
- **Apache Tomcat:** Aplicacions web basades en Java que no necessiten la funcionalitat completa de Java EE, serveis RESTful a Java, aplicacions basades en JSP/Servlet.
- **Red Hat JBoss:** Aplicacions empresarials basades en Java que necessiten transaccions distribuïdes, integració amb bases de dades, serveis web SOAP i RESTful, i aplicacions de gran escala amb alta disponibilitat.
- **Microsoft IIS (Internet Information Services):** Aplicacions empresarials en entorns Windows, aplicacions basades en ASP.NET, ASP.NET Core, serveis web i aplicacions de comerç electrònic.Pàgines Web Estàtiques i Dinàmiques

### Pàgines web estàtiques
Són aquelles en què el seu contingut no varia, per la qual cosa mostraran sempre la mateixa informació cada vegada que es carreguen. Es creen en HTML, CSS i JavaScript. Només canvien si el programador web modifica l'arxiu.

### Pàgines web dinàmiques
El contingut varia a partir de certa informació: base de dades, identificació de l'usuari, hora del dia, etc. Es realitzen mitjançant un llenguatge de programació en servidor.

---

## Execució de codi en el client i en el servidor

En una aplicació web podem distingir entre:
- **Client-side / Front-end:** Tecnologies que s'executen en el navegador.
- **Server-side / Back-end:** Tecnologies que s'executen en el servidor.

---

## 3. Model de 3 Capes

El model de 3 capes estructura l'arquitectura del programari en:
1. **Capa de Presentació (Front-End):** Navegador Web / HTML, CSS, JS.
2. **Capa de Lògica de Negoci (Back-End):** Servidor Web + Llenguatge de programació (ex. Apache + PHP, IIS + ASP, Tomcat + Servlets).
3. **Capa de Dades:** Sistema de Gestió de Bases de Dades (SGBD) com MySQL, PostgreSQL, Oracle, etc.

---

## Perfils Professionals en Desenvolupament Web

- **Front-end:** S'encarrega del disseny i maquetació de l'aplicació web utilitzant tecnologies com HTML, CSS i JavaScript (i els seus frameworks). Es preocupa per la correcta presentació en qualsevol dispositiu (responsive) i del posicionament en cercadors (SEO).
- **Back-end:** S'encarrega del costat servidor utilitzant tecnologies com PHP, Java, Python, C#, Node.js. També s'encarrega de l'administració del servidor d'aplicacions i de la base de dades.
- **Full stack:** Perfil que engloba els dos anteriors. Té amplis coneixements de tot el conjunt i és capaç de col·laborar en qualsevol de les parts del desenvolupament.

---

## Tipologia de les Aplicacions Web

- **Tradicionals o clàssiques (Multi-Page Apps, MPAs):** Pràcticament tota la lògica i la presentació es genera en el costat de servidor.
- **Progressives (Progressively Enhanced Multi-Page Apps, PEMPAs):** Una aplicació tradicional incorpora tecnologies en el costat del client per a millorar l'experiència de l'usuari.
- **Single Page Applications (SPA):** En el costat servidor es generen una sèrie de funcionalitats accessibles mitjançant una API (Application Programming Interface) i tota la càrrega de la presentació es realitza en el costat del client.

---

## 4. Tecnologies i Llenguatges de Servidor

### PHP (PHP Hypertext Preprocessor)
Llenguatge de programació de costat servidor dissenyat principalment per al desenvolupament web. S'utilitza com a llenguatge de script embegut en pàgines HTML i funciona, normalment, com un mòdul del servidor web (p. ex. Apache). ÉS l'element clau de l'arquitectura **LAMP** (Linux, Apache, MySQL, PHP).

```php
<!DOCTYPE html>
<html>
<body>

<?php
echo "El meu primer script en PHP!";
?>

</body>
</html>
```

### Spring Boot (Java)
Actualment, l'ecosistema Java en el desenvolupament web gira al voltant de Spring Framework i, especialment, **Spring Boot**. Permet crear aplicacions web i APIs RESTful de manera ràpida, autònoma (inclou un servidor web encastat com Tomcat) i evitant la complexitat de configuració de les antigues tecnologies Java EE / Jakarta EE (JSP).

```java
@RestController
public class HelloController {

    @GetMapping("/")
    public String index() {
        return "Greetings from Spring Boot!";
    }

}
```



### Python (Django / Flask)

Llenguatge dissenyat per Guido van Rossum, de codi obert i amplament utilitzat en desenvolupament web gràcies a frameworks com **Django** (model MVT - Model View Template) o **Flask**.

```python
from django.http import HttpResponse

def index(request):
    return HttpResponse("Hello, world. You're at the polls index.")
```



### ASP.NET Core (.NET / C#)

Framework modern de codi obert i multiplataforma creat per Microsoft per al desenvolupament d'aplicacions web d'alt rendiment, APIs REST i serveis web.
- **Llenguatges:** Utilitza principalment **C#**.
- **Execució:** Multiplataforma (Linux, Windows, Docker) utilitzant servidors d'alta velocitat integrats (Kestrel) o proxies inversos com Nginx.

```csharp
var builder = WebApplication.CreateBuilder(args);
var app = builder.Build();

app.MapGet("/", () => "Hello World!");

app.Run();
```



### Node.js (JavaScript en Servidor)

No és un llenguatge nou, sinó un **entorn d'execució** per a JavaScript al costat del servidor basat en el motor V8 de Google Chrome.
- **Característiques:** Funciona de manera asíncrona i orientada a esdeveniments (*non-blocking I/O*), ràpid i eficient per a peticions concurrents.
- **Ecosistema:** S'acompanya de frameworks lleugers com **Express.js**.
- **Avantatge clau:** Permet utilitzar un únic llenguatge (**JavaScript**) tant al Front-End com al Back-End.

```javascript
const express = require('express');
const app = express();
const port = 3000;

app.get('/', (req, res) => {
  res.send('Hello World!');
});

app.listen(port, () => {
  console.log(`Example app listening on port ${port}`);
});
```

---

## Bibliografia i Referències

- IBM Education, 2020. *What is Three-Tier Architecture*. [online] Ibm.com. Available at: https://www.ibm.com/cloud/learn/three-tier-architecture
- MDN contributors, 2022. *Introduction to the server side - Learn web development | MDN*. [online] Developer.mozilla.org. Available at: https://developer.mozilla.org/en-US/docs/Learn/Server-side/First_steps/Introduction
- Dodds, K.C., 2023. *The web’s next transition* [online] Epic Web Dev by Kent C. Dodds. Available at: https://www.epicweb.dev/the-webs-next-transition
