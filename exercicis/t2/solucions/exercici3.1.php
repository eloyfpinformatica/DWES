<?php
    $pressupost = 2500;

    if ($pressupost >= 5000) {
        $categoria = "Client Prioritari (Enterprise)";
    } elseif ($pressupost >= 1500) {
        $categoria = "Client Estàndard";
    } else {
        $categoria = "Projecte Menor / A revisar";
    }

    $estat = "nou";

    $etiqueta = ($estat === 'nou') ? 'Pendent de revisar' : 'Atés';
?>
<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exercici 3.1 - Classificació de pressupostos</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">

    <div class="bg-white p-8 rounded-lg shadow-md max-w-md w-full">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Fitxa del lead</h2>
        <p class="text-gray-600 mb-2">Categoria: <span class="font-semibold text-blue-700"><?= $categoria ?></span></p>
        <p class="text-gray-600">Estat de seguiment: <span class="font-semibold text-purple-700"><?= $etiqueta ?></span></p>
    </div>

</body>
</html>
