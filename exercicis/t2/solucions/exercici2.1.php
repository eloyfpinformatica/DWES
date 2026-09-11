<?php
    $nom = "Marc Soler";
    $pressupost = 2750.50;
    $contactat = false;
    $notes = null;

    $fraseSimple = 'Lead: $nom';

    $fraseInterpolada = "El pressupost del lead és de {$pressupost} €";
?>
<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exercici 2.1 - Fitxa d'un nou lead</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen p-8">

    <h1 class="text-2xl font-bold text-gray-800 mb-6">Fitxa del lead</h1>

    <div class="bg-white p-6 rounded-lg shadow-md max-w-xl mb-6">
        <p class="text-gray-700 mb-2"><span class="font-semibold">Cometes simples:</span> <?= $fraseSimple ?></p>
        <p class="text-gray-700"><span class="font-semibold">Cometes dobles (interpolat):</span> <?= $fraseInterpolada ?></p>
    </div>

    <h2 class="text-xl font-semibold text-gray-800 mb-2">Depuració de variables</h2>
    <pre class="bg-gray-900 text-green-400 p-4 rounded-lg overflow-x-auto">
<?php
    var_dump($nom);
    var_dump($pressupost);
    var_dump($contactat);
    var_dump($notes);
?>
    </pre>

</body>
</html>
