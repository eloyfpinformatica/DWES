<?php
include_once __DIR__ . '/helpers.php';
// Bloc 2 - Activitat 2.3
/*$idLead = 1;
    $nomClient = "Maria Garcia";
    $emailClient = "maria@acme.com";
    $empresaClient = "ACME Company";
    $empresaClient = null; // Bloc 3 - Activitat 3.3.
    $serveiClient = "Aplicació mòbil";
    $pressupostClient = 45000;
    $detallsProjecteClient = null; // Bloc 3 - Activitat 3.3.*/

//Bloc 3 - Activitat 4.4.
/*$lead = [
    "nom" => "Maria Garcia",
    "email" => "maria@acme.com",
    "empresa" => "ACME Company",
    "servei" => "Aplicació mòbil",
    "pressupost" => 45000,
    "detalls" => null,
    "estat" => "nou",
    "id" => 1
];*/

//Bloc 6 - Activitat 6.2
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

// Recuperem l'ID del lead de la query string
$idLead = $_GET['id'] ?? null;


// Busquem el lead corresponent
$lead = null;
foreach ($leads as $currentLead) {
    if ($currentLead['id'] == $idLead) {
        $lead = $currentLead;
        break;
    }
}



require_once __DIR__ . '/config.php';

// Bloc 5 - Activitat 5.1
require_once __DIR__ . '/includes/header_admin.php';

// Bloc 5 - Activitat 5.3
require_once __DIR__ . '/functions.php';

?>

<main class="max-w-2xl mx-auto px-6 py-12">

    <!-- Bloc 2 - Activitat 2.3 -->
    <!-- Bloc 4 - Activitat 4.4 -->
    <!-- Bloc 6 - Activitat 6.2 -->
    <?php if ($lead === null) : ?>
        <div class="bg-red-100 text-red-800 p-4 rounded-lg shadow-sm border border-red-200">
            <p class="font-semibold">Lead no trobat.</p>
            <p>El lead amb ID <?= htmlspecialchars($idLead) ?> no existeix.</p>
        </div>
    <?php else : ?>
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8">

            <div class="flex justify-between items-start mb-6">
                <div>
                    <span class="text-xs font-mono text-slate-400">ID: #<?= $lead['id'] ?></span>
                    <h1 class="text-2xl font-bold text-slate-900 mt-1"><?= $lead['nom'] ?></h1>
                    <p class="text-slate-500 text-sm"><?= $lead['empresa'] ?? 'Sense especificar' ?></p> <!-- Bloc 3 - Activitat 3.3. -->
                </div>
                <!-- Bloc 5 - Activitat 5.3. -->
                <?= etiquetes_estat($lead['estat'] ?? 'Nou') ?>
            </div>

            <div class="space-y-4 border-t border-b border-slate-100 py-6 my-6 text-sm">
                <div class="flex justify-between">
                    <span class="text-slate-500">Correu Electrònic:</span>
                    <span class="font-medium text-slate-900"><?= $lead['email'] ?></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500">Servei Sol·licitat:</span>
                    <span class="font-medium text-slate-900"><?= $lead['servei'] ?></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500">Pressupost Estimat:</span>
                    <span class="font-mono font-bold text-indigo-600"><?= $lead['pressupost'] . ' €' ?? 'Sense especificar' ?> </span> <!-- Bloc 3 - Activitat 3.3. -->
                </div>
            </div>

            <!-- Bloc 3 - Activitat 3.3. -->
            <?php if (!empty($lead['detalls'])) : ?>
                <div class="mb-6">
                    <h3 class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Detalls del projecte</h3>
                    <p class="bg-slate-50 p-4 rounded-xl text-slate-700 text-sm border border-slate-200">
                        <?= $lead['detalls'] ?>
                    </p>
                </div>
            <?php endif; ?>
        <?php endif; ?>
        <a href="admin.php" class="inline-block text-sm font-medium text-indigo-600 hover:text-indigo-800">
            &larr; Tornar al llistat de leads
        </a>

        </div>

</main>

</body>

</html>