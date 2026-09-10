<?php

//Bloc 5 - Activitat 5.2
require_once __DIR__ . '/config.php';
// Bloc 5 - Activitat 5.1
require_once __DIR__ . '/includes/header_public.php';


?>

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

                <!-- Bloc 6 - Activitat 6.1 -->
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
                                <!-- Bloc 4 - Activitat 4.2 -->
                                <!-- Bloc 5 - Activitat 5.2 modificat-->
                                <?php foreach (CATALOG_SERVEIS as $clau => $nomServei) : ?>
                                    <option value="<?= $clau ?>"><?= $nomServei ?></option>
                                <?php endforeach; ?>
                                <!--
                                    <option value="web">Desenvolupament Web Custom</option>
                                    <option value="ecommerce">Botiga Online (eCommerce)</option>
                                    <option value="seo">Auditoria SEO & Rendiment</option>
                                    <option value="app">Aplicació Mòbil (App)</option>
                                    -->
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

<?php

// Bloc 5 - Activitat 5.1
require_once __DIR__ . '/includes/footer_public.php';
