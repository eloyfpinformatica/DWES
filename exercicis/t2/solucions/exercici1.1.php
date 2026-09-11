<?php
    $nomEmpresa = "TechLeads";
    $eslogan = "Convertim leads en clients";
    $anyFundacio = 2020;
?>
<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exercici 1.1 - Primera pàgina PHP</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col items-center justify-center text-center">

    <h1 class="text-4xl font-bold text-blue-700"><?= $nomEmpresa ?></h1>
    <p class="text-lg text-gray-700 mt-2"><?= $eslogan ?></p>
    <p class="text-sm text-gray-500 mt-1">Fundada l'any <?= $anyFundacio ?></p>

</body>
</html>
