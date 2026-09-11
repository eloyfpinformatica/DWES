<?php
    define("NOM_EMPRESA", "TechLeads");

    const IVA_PERCENT = 21;

    $preuBase = 1500;

    // Calculem l'import de l'IVA i el sumem al preu base per a obtindre el total
    $preuIva = $preuBase * IVA_PERCENT / 100;
    $preuFinal = $preuBase + $preuIva;
?>
<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exercici 2.3 - Pressupost amb IVA</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">

    <div class="bg-white p-8 rounded-lg shadow-md max-w-md w-full">
        <h2 class="text-2xl font-bold text-gray-800 mb-4"><?= NOM_EMPRESA ?></h2>
        <p class="text-gray-600">Preu base: <?= $preuBase ?> €</p>
        <p class="text-gray-600">IVA: <?= IVA_PERCENT ?> %</p>
        <p class="text-2xl font-bold text-blue-700 mt-4">
            Total: <?= $preuFinal ?> €
        </p>
    </div>

</body>
</html>
