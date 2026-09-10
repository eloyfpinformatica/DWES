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