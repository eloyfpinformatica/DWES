<?php
    // TODO 1: Carrega amb require_once (i una ruta amb __DIR__), en este ordre:
    // Notificable.php, Lead.php, LeadParticular.php i LeadEmpresa.php
    require_once __DIR__ . '/Notificable.php';
    require_once __DIR__ . '/Lead.php';
    require_once __DIR__ . '/LeadParticular.php';
    require_once __DIR__ . '/LeadEmpresa.php';

    // TODO 2: Crea l'array $leads amb estos quatre objectes, en este ordre:
    // - new LeadParticular('Aina Soler', 'aina@exemple.cat', 15)
    // - new LeadEmpresa('Tèxtils S.L.', 'info@textils.cat', 50, 4500)
    // - new LeadParticular('Pau Ferrer', 'pau@exemple.cat', 3)
    // - new LeadEmpresa('Econova', 'hola@econova.cat', 8, 12000)
    $leads = [
        new LeadParticular('Aina Soler', 'aina@exemple.cat', 15),
        new LeadEmpresa('Tèxtils S.L.', 'info@textils.cat', 50, 4500),
        new LeadParticular('Pau Ferrer', 'pau@exemple.cat', 3),
        new LeadEmpresa('Econova', 'hola@econova.cat', 8, 12000),
    ];

    // TODO 3: Declara $puntuacioTotal: recorre $leads amb un foreach i suma el resultat
    // de calcularPuntuacio() de cada lead. Fixa't que no cal saber de quin tipus és cada lead
    $puntuacioTotal = 0;
    foreach ($leads as $lead) {
        $puntuacioTotal += $lead->calcularPuntuacio();
    }

    // TODO 4: Crea l'array $notificacions: per a cada lead de $leads (en el mateix ordre), afig-hi el resultat
    // de cridar a enviarNotificacio('El teu pressupost ja està disponible.')
    $notificacions = [];
    foreach ($leads as $lead) {
        $notificacions[] = $lead->enviarNotificacio('El teu pressupost ja està disponible.');
    }

?>
<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exercici 8.2 - Herència i interfícies</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen p-8">

    <!-- VISTA: no cal tocar res d'ací en avall -->
    <div class="max-w-2xl mx-auto space-y-4">

        <h1 class="text-2xl font-bold text-gray-800">Panell de leads · TechLeads</h1>

        <div class="bg-white p-4 rounded-lg shadow-md">
            <p class="text-gray-700">Puntuació total de la cartera: <span class="font-bold text-blue-700"><?= $puntuacioTotal ?></span></p>
        </div>

        <?php foreach ($leads as $i => $lead): ?>
            <div class="bg-white p-4 rounded-lg shadow-md">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-semibold px-2 py-1 rounded <?= $lead instanceof LeadEmpresa ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' ?>">
                        <?= htmlspecialchars($lead::class) ?>
                    </span>
                    <span class="text-sm text-gray-500">Puntuació: <span class="font-bold text-gray-800"><?= $lead->calcularPuntuacio() ?></span></span>
                </div>
                <p class="text-gray-700"><?= htmlspecialchars($lead->descriure()) ?></p>
                <p class="text-sm text-gray-500 mt-1"><?= htmlspecialchars($notificacions[$i]) ?></p>
            </div>
        <?php endforeach; ?>

    </div>

</body>
</html>
