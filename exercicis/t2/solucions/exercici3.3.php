<?php
    ini_set('display_errors', '1');
    error_reporting(E_ALL);

    $totalPagines = 5;
    $llistaPagines = '';
    for ($i = 1; $i <= $totalPagines; $i++) {
        $llistaPagines .= "<li>Pàgina {$i}</li>";
    }

    $intents = 0;
    $maxIntents = 3;
    $llistaIntents = '';
    while ($intents < $maxIntents) {
        $llistaIntents .= "<li>Intent número: {$intents}</li>";
        $intents++;
    }

    $serveis = ["Web Corporativa", "Botiga Online", "App Mòbil"];

    $llistaServeis = '';
    foreach ($serveis as $servei) {
        $llistaServeis .= "<li>{$servei}</li>";
    }
?>
<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exercici 3.3 - Bucles</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen p-8">

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-4xl mx-auto">

        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-lg font-semibold text-gray-800 mb-2">Pàgines (for)</h3>
            <ul class="list-disc list-inside text-gray-700">
                <?= $llistaPagines ?>
            </ul>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-lg font-semibold text-gray-800 mb-2">Intents (while)</h3>
            <ul class="list-disc list-inside text-gray-700">
                <?= $llistaIntents ?>
            </ul>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-lg font-semibold text-gray-800 mb-2">Serveis (foreach)</h3>
            <ul class="list-disc list-inside text-gray-700">
                <?= $llistaServeis ?>
            </ul>
        </div>

    </div>

</body>
</html>
