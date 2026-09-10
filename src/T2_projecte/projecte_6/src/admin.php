<?php

//Bloc 2 - Activitat 2.4

$numVisites = random_int(100, 9999);
//$numLeads = random_int((int)($numVisites/100), (int)($numVisites/20));
//$numLeads = 2; // Bloc 3 - Activitat 3.4. (Per a provar l'estat buit de la taula)

// Bloc 4 - Activitat 4.5.

//Dades leads
$leads = [
    [
        "id" => 1,
        "nom" => "Maria Garcia",
        "email" => "maria@acme.com",
        "empresa" => "ACME Company",
        "servei" => "Aplicació mòbil",
        "pressupost" => 45000,
        "detalls" => null,
        "estat" => "nou"
    ],
    [
        "id" => 2,
        "nom" => "Vicent Pastor",
        "email" => "vicent@acme.com",
        "empresa" => "Valencia Innovatech",
        "servei" => "Desenvolupament Web Custom",
        "pressupost" => 35000,
        "detalls" => null,
        "estat" => "contactat"
    ],
    [
        "id" => 3,
        "nom" => "Laura Martínez",
        "email" => "laura@acme.com",
        "empresa" => "Gandia Tech Solutions",
        "servei" => "Botiga Online (eCommerce)",
        "pressupost" => 50000,
        "detalls" => null,
        "estat" => "convertit"
    ],
    [
        "id" => 4,
        "nom" => "Jordi López",
        "email" => "jordi@acme.com",
        "empresa" => "Azahar Digital",
        "servei" => "Auditoria SEO & Rendiment",
        "pressupost" => 25000,
        "detalls" => null,
        "estat" => "nou"
    ]
];

//Càlcul de mètriques
$numLeads = count($leads);

//Càlcul volum total pressupost
$volumTotal = 0;
foreach ($leads as $lead) {
    $volumTotal += $lead['pressupost'];
}

$mitjanaVolum = $numLeads > 0 ? $volumTotal / $numLeads : 0;

//Equivalent
/*
$mitjanaVolum = 0;
if ($numLeads > 0) {
    $mitjanaVolum = $volumTotal / $numLeads;
}
*/

// Bloc 5 - Activitat 5.2
require_once __DIR__ . '/config.php';

// Bloc 5 - Activitat 5.1
require_once __DIR__ . '/includes/header_admin.php';

// Bloc 5 - Activitat 5.3
require_once __DIR__ . '/functions.php';

?>



    <main class="max-w-7xl mx-auto px-6 py-8">

        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900">Gestió de Leads</h1>
                <p class="text-slate-500 text-sm mt-1">Llistat global i analítica de les sol·licituds rebudes.</p>
            </div>
        </div>

        <!-- Targetes Mètriques (Amb Targeta de Visites afegida) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
            
        <!-- Bloc 2 - Activitat 2.4 -->
            <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
                <p class="text-xs font-semibold text-slate-400 uppercase">Visites Pàgina</p>
                <p class="text-2xl font-bold text-slate-900 mt-1"><?= $numVisites ?></p>
                <p class="text-[10px] text-slate-400 mt-1">Tràfic total registrat</p>
            </div>

            <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
                <p class="text-xs font-semibold text-slate-400 uppercase">Total Leads</p>
                <p class="text-2xl font-bold text-slate-900 mt-1"><?= $numLeads ?></p>
                <p class="text-[10px] text-slate-400 mt-1">Sol·licituds creades</p>
            </div>

            <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
                <p class="text-xs font-semibold text-slate-400 uppercase">Taxa Conversió</p>
                <p class="text-2xl font-bold text-emerald-600 mt-1"><?= number_format(($numLeads / $numVisites) * 100, 2) ?>%</p>
                <p class="text-[10px] text-slate-400 mt-1">Leads / Visites (<?= $numLeads ?>/<?= $numVisites ?>)</p>
            </div>

            <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
                <p class="text-xs font-semibold text-slate-400 uppercase">Volum Total</p>
                <p class="text-2xl font-bold text-indigo-600 mt-1"><?= number_format($volumTotal, 2) ?> €</p>
                <p class="text-[10px] text-slate-400 mt-1">Pressupost acumulat</p>
            </div>

            <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
                <p class="text-xs font-semibold text-slate-400 uppercase">Mitjana / Projecte</p>
                <p class="text-2xl font-bold text-slate-900 mt-1"><?= number_format($mitjanaVolum, 2) ?> €</p>
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
         <!-- Bloc 3 - Activitat 3.4 -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden <?= empty($numLeads) ? 'hidden' : '' ?>">
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
                        <?php foreach ($leads as $lead) : ?>
                        <tr class="hover:bg-slate-50 transition">
                            <td class="py-3.5 px-4 font-mono text-xs text-slate-400">#<?= $lead['id'] ?></td>
                            <td class="py-3.5 px-4">
                                <div class="font-semibold text-slate-900"><?= $lead['nom'] ?></div>
                                <div class="text-xs text-slate-400"><?= $lead['email'] ?></div>
                            </td>
                            <td class="py-3.5 px-4"><?= $lead['servei'] ?></td>
                            <td class="py-3.5 px-4 font-mono font-semibold"><?= number_format($lead['pressupost'], 2) ?> €</td>
                            <td class="py-3.5 px-4">
                                <!-- Bloc 5 - Activitat 5.3. -->
                                <?= etiquetes_estat($lead['estat'] ?? 'Nou') ?>
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <a href="detall.php?id=<?= $lead['id'] ?>" class="text-indigo-600 hover:text-indigo-900 font-medium text-xs">Veure Detall &rarr;</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        
                    </tbody>
                </table>
            </div>

            <!-- APARTAT PER A QUAN NO ES TROBEN RESULTATS (Empty State) -->
            <!-- Nota: Durant la programació en PHP, aquest bloc només es mostrarà si la llista de leads està buida -->
             <!-- Bloc 3 - Activitat 3.4 -->
            <div class="py-12 text-center  <?= empty($numLeads) ? '' : 'hidden' ?> ">
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