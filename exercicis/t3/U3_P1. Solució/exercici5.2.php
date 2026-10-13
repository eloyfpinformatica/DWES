<?php
    // TODO 1: Inicia la sessió amb session_start()
    session_start();

    // TODO 2: Si $_GET['accio'] val 'reiniciar', buida $_SESSION (amb un array buit),
    // destruïx la sessió amb session_destroy() i redirigeix a 'exercici5.2.php' amb exit
    if (($_GET['accio'] ?? '') === 'reiniciar') {
        $_SESSION = [];
        session_destroy();
        header('Location: exercici5.2.php');
        exit;
    }

    // TODO 3: Incrementa en 1 el comptador $_SESSION['visites']
    // (si encara no existix, ha de començar en 0)
    $_SESSION['visites'] = ($_SESSION['visites'] ?? 0) + 1;

    // TODO 4: Si $_SESSION['primera_visita'] encara no existix,
    // guarda-hi l'hora actual amb date('H:i:s')
    if (!isset($_SESSION['primera_visita'])) {
        $_SESSION['primera_visita'] = date('H:i:s');
    }

    // TODO 5: Guarda $_SESSION['visites'] en $visites
    // i $_SESSION['primera_visita'] en $primeraVisita
    $visites       = $_SESSION['visites'];
    $primeraVisita = $_SESSION['primera_visita'];

?>
<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exercici 5.2 - Comptador de visites</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-8">

    <!-- VISTA: no cal tocar res d'ací en avall -->
    <div class="bg-white p-8 rounded-lg shadow-md max-w-md w-full text-center space-y-4">
        <h1 class="text-2xl font-bold text-gray-800">Àrea de clients · TechLeads</h1>

        <p class="text-gray-700">
            Has visitat esta pàgina
            <span class="text-3xl font-bold text-blue-700 block my-2"><?= (int) $visites ?></span>
            <?= $visites === 1 ? 'vegada' : 'vegades' ?> en esta sessió.
        </p>
        <p class="text-sm text-gray-500">Primera visita: <?= htmlspecialchars($primeraVisita) ?></p>

        <div class="flex justify-center gap-3">
            <a href="exercici5.2.php" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded">Recarregar</a>
            <a href="?accio=reiniciar" class="bg-red-500 hover:bg-red-600 text-white font-semibold px-4 py-2 rounded">Reiniciar sessió</a>
        </div>
    </div>

</body>
</html>
