<?php
// Bloc 1 - Activitat 1.2
//$autor = "Eloy";

//Bloc 2 - Activitat 2.5
//define("AUTOR", "Eloy");

// Bloc 2 - Activitat 2.2
/*
    $nomClient = "Maria Garcia";
    $emailClient = "maria@acme.com";
    $empresaClient = "ACME Company";
    $empresaClient = null; // Bloc 3 - Activitat 3.1.
    $serveiClient = "Aplicació mòbil";
    $pressupostClient = 45000;
    $detallsProjecteClient = null; // Bloc 3 - Activitat 3.2.
    */

//Bloc 3 - Activitat 4.3.
/*
$lead = [
    "nom" => "Maria Garcia",
    "email" => "maria@acme.com",
    "empresa" => "ACME Company",
    "servei" => "Aplicació mòbil",
    "pressupost" => 45000,
    "detalls" => null
];*/

//Bloc 6 - Activitat 6.1
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $lead = [
        "nom" => $_POST['nom'] ?? null,
        "email" => $_POST['email'] ?? null,
        "empresa" => $_POST['empresa'] ?? null,
        "servei" => $_POST['servei'] ?? null,
        "pressupost" => $_POST['pressupost'] ?? null,
        "detalls" => $_POST['detalls'] ?? null
    ];
} else {
    // Redirigir a la pàgina principal si s'accedeix directament sense enviar el formulari
    header("Location: index.php");
    exit;
}

//Bloc 5 - Activitat 5.2
require_once __DIR__ . '/config.php';

// Bloc 5 - Activitat 5.1
require_once __DIR__ . '/includes/header_public.php';


?>

    <main class="max-w-4xl mx-auto px-6 py-16 w-full text-center">

        <div class="bg-white p-8 rounded-2xl shadow-lg border border-slate-200">
            <div class="w-16 h-16 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl font-bold">
                ✓
            </div>

            <h1 class="text-2xl font-bold text-slate-900 mb-2">Gràcies per la teua sol·licitud!</h1>
            <p class="text-slate-600 text-base mb-6">Hem rebut les teues dades correctament. Ens posarem en contacte amb tu en menys de 24 hores.</p>

            <!-- Resum de Dades Enviades (Estàtic) -->
            <div class="bg-slate-50 rounded-xl p-4 text-left border border-slate-200 space-y-2 text-base mb-6">
                <!-- Bloc 2 - Activitat 2.2. -->
                <!-- Bloc 4 - Activitat 4.3. -->
                <p><strong class="text-slate-700">Nom:</strong> <?= $lead['nom'] ?></p>
                <p><strong class="text-slate-700">Email:</strong> <?= $lead['email'] ?></p>
                <p><strong class="text-slate-700">Empresa:</strong> <?= $lead['empresa'] ?? 'Sense especificar' ?></p> <!-- Bloc 3 - Activitat 3.1. -->
                <p><strong class="text-slate-700">Servei:</strong> <?= $lead['servei'] ?></p>
                <p><strong class="text-slate-700">Pressupost estimat:</strong> <?= $lead['pressupost']  ?? 'Sense especificar' ?> €</p> <!-- Bloc 3 - Activitat 3.1. -->

                <!-- Bloc 3 - Activitat 3.2. -->
                <!-- Bloc 4 - Activitat 4.3. -->
                <?php if (!empty($lead['detalls'])) : ?>
                    <p><strong class="text-slate-700">Detalls del projecte:</strong> <?= $lead['detalls'] ?></p>
                <?php endif; ?>
            </div>

            <a href="index.php" class="inline-block bg-slate-900 hover:bg-slate-800 text-white font-medium text-sm px-5 py-2.5 rounded-lg transition">
                Tornar a l'inici
            </a>
        </div>

    </main>


    <?php

    // Bloc 5 - Activitat 5.1
    require_once __DIR__ . '/includes/footer_public.php';
