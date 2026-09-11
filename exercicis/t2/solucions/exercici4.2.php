<?php
    $lead = [
        'id'         => 101,
        'nom'        => "Aina Martínez",
        'email'      => "aina@empresa.valencia",
        'pressupost' => 3500.00,
        'actiu'      => true
    ];

    $lead['telefon'] = "600112233";

    $lead['actiu'] = false;

    $nomMostrat = isset($lead['nom']) ? $lead['nom'] : "Sense nom";

    $llistaLead = '';
    foreach ($lead as $clau => $valor) {
        $llistaLead .= "<li><strong>{$clau}:</strong> {$valor}</li>";
    }
?>
<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exercici 4.2 - Fitxa d'un lead</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">

    <div class="bg-white p-8 rounded-lg shadow-md max-w-md w-full">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Lead: <?= $nomMostrat ?></h2>
        <ul class="text-gray-700 space-y-1">
            <?= $llistaLead ?>
        </ul>
    </div>

</body>
</html>
