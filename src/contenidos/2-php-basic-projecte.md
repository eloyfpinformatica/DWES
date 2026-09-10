# Unitat 2. PHP Bàsic


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

