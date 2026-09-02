# **Unitat 1: Introducció a la part del servidor d’aplicacions web**

## **Pràctica 1. Preparació de l’entorn de treball amb Docker**

### Resultats d’aprenentatge associats i criteris d’avaluació:  

**RA1. Selecciona les arquitectures i tecnologies de programació Web en entorn servidor, analitzant les seues capacitats i característiques pròpies.**

* S'han identificat els mecanismes d'execució de codi en els servidors Web.  

* S'han reconegut i avaluat les eines de programació en entorn servidor.

### **Temporalització:**
1 sessió

### **Lliurament:**

* En un document en **PDF** han d’estar les activitats de les dos parts.  

* Atenció a les faltes d’ortografia i a la presentació.



## **GUIA PER A LA PREPARACIÓ DE L’ENTORN DE TREBALL**

Per a les pròximes unitats treballarem amb el llenguatge **PHP**, el qual necessita un **servidor web** i un **servidor de bases de dades** per al seu funcionament.  

Tradicionalment, una de les opcions més utilitzades per a muntar aquest entorn de desenvolupament era **XAMPP**, gràcies a la seua simplicitat d’instal·lació i ús.  

Tanmateix, en els darrers anys, **Docker** ha anat consolidant-se com l’alternativa més estesa, ja que permet crear entorns aïllats, més lleugers i fàcils de gestionar.  

A continuació es detalla com fer aquesta preparació sobre **Ubuntu 26**.

### INSTAL·LACIÓ DE DOCKER EN UBUNTU 26

1. **Afegir el repositori de Docker en Ubuntu 26:**

>    `# Afegir la clau GPG oficial de Docker:`  
>    `sudo apt update`  
>    `sudo apt install -y ca-certificates curl`  
>    `sudo install -m 0755 -d /etc/apt/keyrings`  
>    `sudo curl -fsSL https://download.docker.com/linux/ubuntu/gpg -o /etc/apt/keyrings/docker.asc`  
>    `sudo chmod a+r /etc/apt/keyrings/docker.asc`

>    `# Afegir el repositori a les fonts d’APT: 
>    `echo \`  
>      `"deb [arch=$(dpkg --print-architecture) signed-by=/etc/apt/keyrings/docker.asc] https://download.docker.com/linux/ubuntu \`  
>      `$(. /etc/os-release && echo "$VERSION_CODENAME") stable" | \`  
>      `sudo tee /etc/apt/sources.list.d/docker.list > /dev/null`  
>    `sudo apt update`  

2. **Instal·lar els paquets de Docker Engine:**

>    `sudo apt install -y docker-ce docker-ce-cli containerd.io docker-buildx-plugin docker-compose-plugin`  

3. **Assignació de permisos a l’usuari per executar Docker Engine (sense necessitat de sudo):**

>    `sudo groupadd docker`  
>    `sudo usermod -aG docker $USER`  
>    `newgrp docker`  

4. **Configuració de l’arranc automàtic del servei:**

>    `sudo systemctl enable docker.service`  
>    `sudo systemctl enable containerd.service`  

5. **Comprovació de la instal·lació:**

>    `docker ps`

### CONFIGURACIÓ DE L’ENTORN PHP \+ MYSQL \+ PHPMYADMIN

#### 1. **Crear carpeta del projecte**  

Pots crear la carpeta en la ubicació que vulgues i accedir:

>    `mkdir -p ~/el_teu_projecte`  
>    `cd ~/el_teu_projecte`  

#### 2. **Crear l'arxiu docker-compose.yml**  

Dins d’aquesta carpeta, crea l'arxiu ***docker-compose.yml***. Utilitzem l'última versió estable de PHP disponible (php:8.5-apache) i el camí relatiu *./:/var/www/html*, de manera que el contenidor s'executarà directament des del directori actual.

>    `services:`  
>      `app:`  
>        `image: php:8.5-apache`  
>        `container_name: php_app`  
>        `volumes:`  
>          `- ./:/var/www/html`  
>        `ports:`  
>          `- "8080:80"`  
>        `depends_on:`  
>          `- db`  
>        `restart: always`
>
>      `db:`  
>        `image: mysql:8.0`  
>        `container_name: mysql_db`  
>        `environment:`  
>          `MYSQL_ROOT_PASSWORD: rootpass`  
>          `MYSQL_DATABASE: testdb`  
>          `MYSQL_USER: user`  
>          `MYSQL_PASSWORD: userpass`  
>        `volumes:`  
>          `- db_data:/var/lib/mysql`  
>        `restart: always`
>
>      `phpmyadmin:`  
>        `image: phpmyadmin/phpmyadmin`  
>        `container_name: phpmyadmin`  
>        `environment:`  
>          `PMA_HOST: db`  
>          `PMA_USER: root`  
>          `PMA_PASSWORD: rootpass`  
>        `ports:`  
>          `- "8081:80"`  
>        `depends_on:`  
>          `- db`  
>        `restart: always`
>        
>    `volumes:`  
>      `db_data:`  

#### 3. **Crear un arxiu de test PHP**  

Dins del directori del projecte, pots crear una carpeta de prova o un arxiu **index.php** directament:

>    `mkdir -p test`  
>    `nano test/index.php`Afageix el següent contingut d'exemple:

>    `<?php`  
>    `echo "Hola des de test PHP 🚀";`  
>    `?>`  

#### 4. **Arrancar els contenidors**  

Executa l'orde des del mateix directori on es troba el docker-compose.yml:

>    `docker compose up -d`  

5. **Provar en el navegador**  
   * http://localhost:8080/test → obri index.php.  
   * http://localhost:8081 → obri **phpMyAdmin**.

   Credencials MySQL:

   * Usuari administrador: **root** / Contrasenya: **rootpass**  
   * Usuari estàndard: **user** / Contrasenya: **userpass**

### **UTILITATS I GESTIÓ DE CONTENIDORS DOCKER**

A continuació es detallen les ordres bàsiques i avançades per a gestionar el cicle de vida dels teus contenidors:

| Acció / Utilitat | Comanda | Descripció   |
| :---- | :---- | :---- |
| **Aturar els contenidors** | docker compose stop | Atura els serveis sense esborrar els contenidors ni les dades. |
| **Aturar i esborrar contenidors** | docker compose down | Atura i elimina els contenidors i xarxes creats per Compose. |
| **Esborrar incloent volums** | docker compose down \-v | Atura i elimina contenidors, xarxes i la base de dades (volums). |
| **Veure logs (registres)** | docker compose logs | Mostra la eixida de text de tots els serveis. |
| **Seguir logs en temps real** | docker compose logs \-f | Mostra els registres contínuament (útil per a depurar errors). |
| **Logs d'un servei concret** | docker compose logs \-f app | Visualitza únicament els registres del servidor web PHP. |
| **Estat dels contenidors** | docker compose ps | Llista els contenidors de l'entorn actual i el seu estat. |
| **Executar comandes dins el contenidor** | docker exec \-it php\_app bash | Obre una terminal interactiva dins del contenidor de PHP. |
| **Reiniciar un servei** | docker compose restart app | Reinicia el servei indicat sense aturar la resta de l'entorn. |

### **Lliurament Part 1:**

Quan ho tingues, fes una captura de pantalla de la teua **pàgina de test** i una altra amb l’accés a **phpMyAdmin**.